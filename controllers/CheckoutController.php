<?php
require_once __DIR__ . '/../core/NotificationHelper.php';

class CheckoutController extends Controller {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }

        $this->db = (new Database())->getConnection();
    }

    // Recibe el ID del producto directamente para compra rápida
    public function procesar($producto_id) {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = ?");
        $stmt->execute([$producto_id]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto || $producto['stock'] <= 0) {
            die("Producto no disponible o sin stock.");
        }

        $stmtMetodos = $this->db->query("SELECT * FROM metodos_pago WHERE estado = 1");
        $metodos = $stmtMetodos->fetchAll(PDO::FETCH_ASSOC);

        $this->view('checkout/index', ['producto' => $producto, 'metodos' => $metodos]);
    }

    public function confirmar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $producto_id = filter_input(INPUT_POST, 'producto_id', FILTER_SANITIZE_NUMBER_INT);
            $metodo_pago_id = filter_input(INPUT_POST, 'metodo_pago_id', FILTER_SANITIZE_NUMBER_INT);
            $total = filter_input(INPUT_POST, 'total', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

            $stmtMet = $this->db->prepare("SELECT * FROM metodos_pago WHERE id = ?");
            $stmtMet->execute([$metodo_pago_id]);
            $metodo = $stmtMet->fetch(PDO::FETCH_ASSOC);

            // Regla: PayPhone (Confirmado -> baja stock), Transferencia (Pendiente -> No baja aún)
            $estado = ($metodo['tipo'] == 'PayPhone') ? 'Confirmado' : 'Pendiente';

            try {
                $this->db->beginTransaction();

                // Crear Pedido
                $stmt = $this->db->prepare("INSERT INTO pedidos (usuario_id, metodo_pago_id, total, estado) VALUES (?, ?, ?, ?)");
                $stmt->execute([$_SESSION['user_id'], $metodo_pago_id, $total, $estado]);
                $pedido_id = $this->db->lastInsertId();

                // Insertar Detalle
                $stmtDet = $this->db->prepare("INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, 1, ?)");
                $stmtDet->execute([$pedido_id, $producto_id, $total]);

                if ($estado == 'Confirmado') {
                    // Bajar stock local
                    $stmtStock = $this->db->prepare("UPDATE productos SET stock = stock - 1 WHERE id = ?");
                    $stmtStock->execute([$producto_id]);
                } else if ($estado == 'Pendiente') {
                    NotificationHelper::sendPendingNotification($this->db);
                }

                $this->db->commit();
                $this->view('checkout/success', ['pedido_id' => $pedido_id, 'estado' => $estado, 'metodo' => $metodo['tipo']]);

            } catch(PDOException $e) {
                $this->db->rollBack();
                die("Error al procesar el pago: " . $e->getMessage());
            }
        }
    }

    public function procesarCart() {
        if (empty($_SESSION['carrito'])) {
            header('Location: ' . URL_BASE . 'carrito');
            exit;
        }

        $stmtMetodos = $this->db->query("SELECT * FROM metodos_pago WHERE estado = 1");
        $metodos = $stmtMetodos->fetchAll(PDO::FETCH_ASSOC);

        $stmtEnvios = $this->db->query("SELECT * FROM metodos_envio WHERE estado = 1");
        $metodos_envio = $stmtEnvios->fetchAll(PDO::FETCH_ASSOC);

        $stmtReglas = $this->db->query("SELECT * FROM reglas_envio WHERE estado = 1");
        $reglas_envio = $stmtReglas->fetchAll(PDO::FETCH_ASSOC);

        $this->view('checkout/index_cart', [
            'carrito' => $_SESSION['carrito'], 
            'metodos' => $metodos,
            'metodos_envio' => $metodos_envio,
            'reglas_envio' => $reglas_envio
        ]);
    }

    public function confirmarCart() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_SESSION['carrito'])) {
                die("El carrito está vacío.");
            }

            $metodo_pago_id = filter_input(INPUT_POST, 'metodo_pago_id', FILTER_SANITIZE_NUMBER_INT);
            
            // Calcular subtotal en el servidor por seguridad
            $subtotal = 0;
            foreach ($_SESSION['carrito'] as $item) {
                $subtotal += ($item['precio'] * $item['cantidad']);
            }

            $metodo_envio_id = filter_input(INPUT_POST, 'metodo_envio_id', FILTER_SANITIZE_NUMBER_INT) ?? null;
            $costo_envio = 0;

            if ($metodo_envio_id) {
                $stmtEnv = $this->db->prepare("SELECT * FROM metodos_envio WHERE id = ?");
                $stmtEnv->execute([$metodo_envio_id]);
                $envio = $stmtEnv->fetch(PDO::FETCH_ASSOC);
                
                if ($envio) {
                    $costo_envio = $envio['costo_base'];
                    
                    // Verificar reglas (ofertas)
                    $stmtReglas = $this->db->query("SELECT * FROM reglas_envio WHERE estado = 1");
                    $reglas = $stmtReglas->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($reglas as $regla) {
                        if ($subtotal >= $regla['monto_minimo_carrito']) {
                            $costo_envio = $regla['costo_fijo'];
                            if ($costo_envio == 0) break; // Priorizar envío gratis
                        }
                    }
                }
            }

            $total = $subtotal + $costo_envio;

            $stmtMet = $this->db->prepare("SELECT * FROM metodos_pago WHERE id = ?");
            $stmtMet->execute([$metodo_pago_id]);
            $metodo = $stmtMet->fetch(PDO::FETCH_ASSOC);

            if ($metodo['tipo'] == 'PayPhone') {
                // Guarda temporalmente para el pago
                $_SESSION['checkout_payphone'] = [
                    'metodo_pago_id' => $metodo_pago_id,
                    'total' => $total,
                    'metodo_envio_id' => $metodo_envio_id,
                    'costo_envio' => $costo_envio
                ];
                $this->redirect('checkout/payphone');
                return;
            }

            // Regla: Solo Transferencia llega aquí directo
            $estado = 'Pendiente';
            $comprobante_url = null;

            // Procesar la subida del comprobante si es por Transferencia
            if ($metodo['tipo'] == 'Transferencia') {
                $fileInputName = 'comprobante_' . $metodo_pago_id;
                if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] == UPLOAD_ERR_OK) {
                    $fsUploadDir = __DIR__ . '/../public/img/comprobantes/';
                    $urlUploadDir = 'public/img/comprobantes/';
                    
                    if (!is_dir($fsUploadDir)) {
                        mkdir($fsUploadDir, 0755, true);
                    }
                    
                    $filename = uniqid('receipt_') . '_' . time() . '.' . pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION);
                    $fsDestination = $fsUploadDir . $filename;
                    $dbDestination = $urlUploadDir . $filename;
                    
                    if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $fsDestination)) {
                        $comprobante_url = $dbDestination;
                    } else {
                        die("Error al guardar el comprobante de transferencia.");
                    }
                } else {
                    die("No se proporcionó un comprobante válido.");
                }
            }

            try {
                $this->db->beginTransaction();

                // Crear Pedido principal (ahora incluye comprobante_url)
                // Crear Pedido principal (ahora incluye comprobante_url, metodo_envio_id, costo_envio)
                $stmt = $this->db->prepare("INSERT INTO pedidos (usuario_id, metodo_pago_id, total, estado, comprobante_url, metodo_envio_id, costo_envio) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$_SESSION['user_id'], $metodo_pago_id, $total, $estado, $comprobante_url, $metodo_envio_id, $costo_envio]);
                $pedido_id = $this->db->lastInsertId();

                // Insertar Detalle para cada producto en el carrito
                $stmtDet = $this->db->prepare("INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");

                foreach ($_SESSION['carrito'] as $item) {
                    $producto_id = $item['id'];
                    $cantidad = $item['cantidad'];
                    $precio_unitario = $item['precio'];

                    $stmtDet->execute([$pedido_id, $producto_id, $cantidad, $precio_unitario]);
                }

                $this->db->commit();
                
                // Vaciar el carrito tras crearlo
                $_SESSION['carrito'] = [];

                if ($estado == 'Pendiente') {
                    NotificationHelper::sendPendingNotification($this->db);
                }

                $this->view('checkout/success', ['pedido_id' => $pedido_id, 'estado' => $estado, 'metodo' => $metodo['tipo']]);

            } catch(PDOException $e) {
                $this->db->rollBack();
                die("Error al procesar el pago del carrito: " . $e->getMessage());
            }
        }
    }

    // --- PAYPHONE INTEGRATION EXPERT ---
    public function payphone() {
        if (empty($_SESSION['carrito']) || empty($_SESSION['checkout_payphone'])) {
            $this->redirect('carrito');
        }

        // Obtener configuración de PayPhone
        $config = $this->db->query("SELECT * FROM configuracion_payphone LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        
        if (!$config || empty($config['store_id']) || $config['store_id'] === 'Ingresa_tu_tienda_aqui') {
            $html = '<!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Configuración Incompleta - PayPhone</title>
                <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
                <style>
                    body {
                        margin: 0; padding: 0;
                        font-family: \'Inter\', sans-serif;
                        background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
                        color: #f8fafc;
                        min-height: 100vh;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }
                    .glass-container {
                        background: rgba(255, 255, 255, 0.05);
                        backdrop-filter: blur(16px);
                        -webkit-backdrop-filter: blur(16px);
                        border: 1px solid rgba(255, 255, 255, 0.1);
                        border-radius: 20px;
                        padding: 3rem 2.5rem;
                        max-width: 450px;
                        width: 90%;
                        text-align: center;
                        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
                    }
                    .icon-wrapper {
                        width: 80px; height: 80px;
                        background: rgba(245, 158, 11, 0.15);
                        border: 2px solid #f59e0b;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        margin: 0 auto 1.5rem;
                        font-size: 2.5rem;
                        color: #f59e0b;
                    }
                    h2 {
                        margin: 0 0 1rem;
                        font-weight: 600;
                        font-size: 1.5rem;
                    }
                    p {
                        color: #94a3b8;
                        line-height: 1.6;
                        margin: 0 0 2rem;
                        font-size: 0.95rem;
                    }
                    .btn-back {
                        display: inline-block;
                        background: #3b82f6;
                        color: white;
                        text-decoration: none;
                        padding: 0.8rem 2rem;
                        border-radius: 10px;
                        font-weight: 500;
                        transition: all 0.3s ease;
                        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
                    }
                    .btn-back:hover {
                        background: #2563eb;
                        transform: translateY(-2px);
                        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
                    }
                    .btn-back i { margin-right: 8px; }
                </style>
            </head>
            <body>
                <div class="glass-container">
                    <div class="icon-wrapper">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <h2>Pago Temporalmente No Disponible</h2>
                    <p>La tienda aún no ha configurado sus credenciales para habilitar el pago con tarjetas a través de PayPhone. Por favor, selecciona otro método de pago.</p>
                    <a href="' . URL_BASE . 'carrito" class="btn-back"><i class="fas fa-arrow-left"></i> Volver al carrito</a>
                </div>
            </body>
            </html>';
            die($html);
        }

        $this->view('checkout/payphone', [
            'total' => $_SESSION['checkout_payphone']['total'],
            'config' => $config
        ]);
    }

    public function procesarPayphone() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            file_put_contents('payphone_log.txt', print_r($_POST, true), FILE_APPEND);

            // PayPhone invoca esto si el pago es exitoso
            $status = $_POST['status'] ?? '';
            $transactionId = $_POST['transactionId'] ?? '';
            
            if ($status === 'Approved') {
                $metodo_pago_id = $_SESSION['checkout_payphone']['metodo_pago_id'];
                $total = $_SESSION['checkout_payphone']['total'];
                $metodo_envio_id = $_SESSION['checkout_payphone']['metodo_envio_id'] ?? null;
                $costo_envio = $_SESSION['checkout_payphone']['costo_envio'] ?? 0;
                $estado = 'Confirmado';

                try {
                    $this->db->beginTransaction();

                    $stmt = $this->db->prepare("INSERT INTO pedidos (usuario_id, metodo_pago_id, total, estado, metodo_envio_id, costo_envio) VALUES (?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$_SESSION['user_id'], $metodo_pago_id, $total, $estado, $metodo_envio_id, $costo_envio]);
                    $pedido_id = $this->db->lastInsertId();

                    $stmtDet = $this->db->prepare("INSERT INTO detalle_pedidos (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
                    $stmtStock = $this->db->prepare("UPDATE productos SET stock = stock - ? WHERE id = ?");

                    foreach ($_SESSION['carrito'] as $item) {
                        $producto_id = $item['id'];
                        $cantidad = $item['cantidad'];
                        $precio_unitario = $item['precio'];

                        $stmtDet->execute([$pedido_id, $producto_id, $cantidad, $precio_unitario]);
                        $stmtStock->execute([$cantidad, $producto_id]); // Baja stock porque ya está pagado
                    }

                    $this->db->commit();
                    
                    $_SESSION['carrito'] = [];
                    unset($_SESSION['checkout_payphone']);

                    echo json_encode(['success' => true, 'redirect' => URL_BASE . 'checkout/successPayphone?id=' . $pedido_id]);
                    return;

                } catch(PDOException $e) {
                    $this->db->rollBack();
                    echo json_encode(['success' => false, 'message' => 'Error de BD: ' . $e->getMessage()]);
                    return;
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'El pago no fue aprobado por el banco.']);
                return;
            }
        }
    }

    public function successPayphone() {
        $pedido_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $this->view('checkout/success', ['pedido_id' => $pedido_id, 'estado' => 'Confirmado', 'metodo' => 'PayPhone (Tarjeta de Crédito/Débito)']);
    }
}
