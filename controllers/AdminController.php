<?php
use Dompdf\Dompdf;
use Dompdf\Options;

class AdminController extends Controller {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Protección de rutas Admin (Fase 13)
        if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] != 1) {
            $this->redirect('auth/login');
        }

        // Renovar timeout
        $_SESSION['last_activity'] = time();

        $this->db = (new Database())->getConnection();
    }

    public function index() {
        $this->dashboard();
    }

    public function dashboard() {
        // Stats básicos
        $usersCount    = $this->db->query("SELECT count(*) FROM usuarios")->fetchColumn();
        $productsCount = $this->db->query("SELECT count(*) FROM productos")->fetchColumn();
        $ordersCount   = $this->db->query("SELECT count(*) FROM pedidos")->fetchColumn();
        $categoriasCount = $this->db->query("SELECT count(*) FROM categorias")->fetchColumn();
        $marcasCount   = $this->db->query("SELECT count(*) FROM marcas")->fetchColumn();

        // Pendientes para listar
        $pendingOrders = $this->db->query("
            SELECT p.id, p.total, p.fecha_pedido, u.nombre, u.apellido 
            FROM pedidos p
            LEFT JOIN usuarios u ON p.usuario_id = u.id
            WHERE p.estado = 'Pendiente'
            ORDER BY p.fecha_pedido DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $pendingServices = $this->db->query("
            SELECT s.id, s.dispositivo, s.descripcion_problema, s.fecha_solicitud, u.nombre, u.apellido
            FROM servicio_tecnico s
            LEFT JOIN usuarios u ON s.usuario_id = u.id
            WHERE s.estado = 'Pendiente'
            ORDER BY s.fecha_solicitud DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $this->viewAdmin('admin/dashboard', [
            'usersCount'      => $usersCount,
            'productsCount'   => $productsCount,
            'ordersCount'     => $ordersCount,
            'categoriasCount' => $categoriasCount,
            'marcasCount'     => $marcasCount,
            'pendingOrders'   => $pendingOrders,
            'pendingServices' => $pendingServices
        ]);
    }

    private function handleUpload($inputName, $folder = 'img/') {
        if (!isset($_FILES[$inputName])) {
            return '';
        }

        // Ruta dinámica: funciona en localhost /Tienda/ e InfinityFree /htdocs/
        $publicPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR;
        $targetDir  = $publicPath . str_replace('/', DIRECTORY_SEPARATOR, $folder);

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $allowedTypes  = ['jpg', 'png', 'jpeg', 'gif', 'svg', 'webp', 'mp4', 'webm'];
        $uploadedPaths = [];

        // Check if multiple files were uploaded (array)
        if (is_array($_FILES[$inputName]['name'])) {
            $fileCount = count($_FILES[$inputName]['name']);
            for ($i = 0; $i < $fileCount; $i++) {
                if ($_FILES[$inputName]['error'][$i] == 0) {
                    $fileName = time() . '_' . $i . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', basename($_FILES[$inputName]['name'][$i]));
                    $targetFilePath = $targetDir . $fileName;
                    $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

                    if (in_array($fileType, $allowedTypes)) {
                        if (move_uploaded_file($_FILES[$inputName]['tmp_name'][$i], $targetFilePath)) {
                            $uploadedPaths[] = 'public/' . $folder . $fileName;
                        }
                    }
                }
            }
            return json_encode($uploadedPaths);
        } else {
            // Single file upload
            if ($_FILES[$inputName]['error'] == 0) {
                $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9.\-_]/', '', basename($_FILES[$inputName]['name']));
                $targetFilePath = $targetDir . $fileName;
                $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

                if (in_array($fileType, $allowedTypes)) {
                    if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFilePath)) {
                        return 'public/' . $folder . $fileName;
                    }
                }
            }
            return '';
        }
    }

    // --- Productos CRUD ---
    public function productos() {
        $stmt = $this->db->query("
            SELECT p.*, c.nombre as categoria, m.nombre as marca 
            FROM productos p 
            JOIN categorias c ON p.categoria_id = c.id 
            JOIN marcas m ON p.marca_id = m.id
            ORDER BY p.id DESC
        ");
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $this->viewAdmin('admin/productos/index', ['productos' => $productos]);
    }

    public function productosCreate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre   = trim(htmlspecialchars($_POST['nombre'] ?? '', ENT_QUOTES, 'UTF-8'));
            $precio = filter_input(INPUT_POST, 'precio', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $stock = filter_input(INPUT_POST, 'stock', FILTER_SANITIZE_NUMBER_INT);
            $cat_id = filter_input(INPUT_POST, 'categoria_id', FILTER_SANITIZE_NUMBER_INT);
            $marca_id = filter_input(INPUT_POST, 'marca_id', FILTER_SANITIZE_NUMBER_INT);
            $imagen_url = $this->handleUpload('file_upload', 'img/productos/');

            $stmt = $this->db->prepare("INSERT INTO productos (nombre, precio, stock, categoria_id, marca_id, imagen_url) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $precio, $stock, $cat_id, $marca_id, $imagen_url]);
            
            $this->redirect('admin/productos');
        } else {
            // Cargar foráneas
            $categorias = $this->db->query("SELECT * FROM categorias")->fetchAll(PDO::FETCH_ASSOC);
            $marcas = $this->db->query("SELECT * FROM marcas")->fetchAll(PDO::FETCH_ASSOC);
            $this->viewAdmin('admin/productos/create', ['categorias' => $categorias, 'marcas' => $marcas]);
        }
    }

    public function productosDelete($id) {
        $stmt = $this->db->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        $this->redirect('admin/productos');
    }

    // --- Categorías CRUD ---
    public function categorias() {
        $records = $this->db->query("SELECT * FROM categorias ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $this->viewAdmin('admin/categorias/index', ['records' => $records]);
    }

    public function categoriasCreate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $stmt = $this->db->prepare("INSERT INTO categorias (nombre, descripcion) VALUES (?, ?)");
            $stmt->execute([$nombre, $descripcion]);
            $this->redirect('admin/categorias');
        } else {
            $this->viewAdmin('admin/categorias/create');
        }
    }

    public function categoriasDelete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM categorias WHERE id = ?");
            $stmt->execute([$id]);
            $this->redirect('admin/categorias');
        } catch (PDOException $e) {
            $this->redirect('admin/categorias?error=in_use');
        }
    }

    // --- Marcas CRUD ---
    public function marcas() {
        $records = $this->db->query("SELECT * FROM marcas ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $this->viewAdmin('admin/marcas/index', ['records' => $records]);
    }

    public function marcasCreate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $logo_url = $this->handleUpload('logo', 'img/marcas/');
            
            $stmt = $this->db->prepare("INSERT INTO marcas (nombre, logo_url) VALUES (?, ?)");
            $stmt->execute([$nombre, $logo_url]);
            $this->redirect('admin/marcas');
        } else {
            $this->viewAdmin('admin/marcas/create');
        }
    }

    public function marcasDelete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM marcas WHERE id = ?");
            $stmt->execute([$id]);
            $this->redirect('admin/marcas');
        } catch (PDOException $e) {
            // Cannot delete because it's tied to a product
            $this->redirect('admin/marcas?error=in_use');
        }
    }

    // --- Referencias CRUD ---
    public function referencias() {
        $records = $this->db->query("SELECT * FROM referencias ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $this->viewAdmin('admin/referencias/index', ['records' => $records]);
    }

    public function referenciasCreate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre_autor  = $_POST['nombre_autor'] ?? '';
            $comentario    = $_POST['comentario'] ?? '';
            $estrellas     = $_POST['estrellas'] ?? 5;
            $tipo_media    = $_POST['tipo_media'] ?? 'texto';
            $url_referencia = $_POST['url_referencia'] ?? '';
            
            $media_url = null;
            if ($tipo_media != 'texto') {
                $media_url = $this->handleUpload('file_upload', 'public/img/referencias/');
            }

            $stmt = $this->db->prepare("INSERT INTO referencias (nombre_autor, comentario, estrellas, media_url, tipo_media, url_referencia) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nombre_autor, $comentario, $estrellas, $media_url, $tipo_media, $url_referencia]);
            $this->redirect('admin/referencias');
        } else {
            $this->redirect('admin/referencias');
        }
    }

    public function referenciasEdit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre_autor  = $_POST['nombre_autor'] ?? '';
            $comentario    = $_POST['comentario'] ?? '';
            $estrellas     = $_POST['estrellas'] ?? 5;
            $tipo_media    = $_POST['tipo_media'] ?? 'texto';
            $url_referencia = $_POST['url_referencia'] ?? '';
            $media_actual  = $_POST['media_actual'] ?? '';

            $media_url = $media_actual;
            if ($tipo_media != 'texto' && isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
                $media_url = $this->handleUpload('file_upload', 'public/img/referencias/');
            }

            $stmt = $this->db->prepare("UPDATE referencias SET nombre_autor = ?, comentario = ?, estrellas = ?, media_url = ?, tipo_media = ?, url_referencia = ? WHERE id = ?");
            $stmt->execute([$nombre_autor, $comentario, $estrellas, $media_url, $tipo_media, $url_referencia, $id]);
            $this->redirect('admin/referencias');
        }
    }

    public function referenciasDelete($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM referencias WHERE id = ?");
            $stmt->execute([$id]);
            $this->redirect('admin/referencias');
        } catch (PDOException $e) {
            $this->redirect('admin/referencias?error=in_use');
        }
    }

    // --- Ofertas CRUD ---
    public function ofertas() {
        $records = $this->db->query("
            SELECT o.*, p.nombre AS producto_nombre
            FROM ofertas o
            LEFT JOIN productos p ON o.producto_id = p.id
            ORDER BY o.id DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $ofertas_servicios = $this->db->query("
            SELECT * FROM ofertas_servicios ORDER BY id DESC
        ")->fetchAll(PDO::FETCH_ASSOC);

        $productos = $this->db->query("SELECT id, nombre FROM productos ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
        $this->viewAdmin('admin/ofertas/index', [
            'records' => $records, 
            'productos' => $productos,
            'ofertas_servicios' => $ofertas_servicios
        ]);
    }

    public function ofertasServiciosCreate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $descuento = $_POST['descuento_porcentaje'] ?? 0;
            $condicion = $_POST['condicion'] ?? 'TODOS';
            $fecha_inicio = $_POST['fecha_inicio'] ?? '';
            $fecha_fin = $_POST['fecha_fin'] ?? '';

            $stmt = $this->db->prepare("INSERT INTO ofertas_servicios (nombre, descuento_porcentaje, condicion, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $descuento, $condicion, $fecha_inicio, $fecha_fin]);

            // --- Crear noticia automática atractiva ---
            try {
                $condicion_texto = $condicion === 'PRIMERA_VEZ'
                    ? 'Exclusivo para nuevos clientes que soliciten su primer servicio técnico a través de nuestra página web.'
                    : 'Aplica para todos nuestros clientes. ¡No pierdas esta oportunidad!';

                $fecha_fin_format = date('d \d\e F \d\e Y', strtotime($fecha_fin));

                $titulo_noticia = "🔥 ¡OFERTA ESPECIAL! -{$descuento}% en Servicio Técnico — {$nombre}";

                $contenido_noticia =
                    "¡Tenemos una promoción increíble para ti! 🎉\n\n" .
                    "Aprovecha un DESCUENTO del {$descuento}% en todos nuestros servicios técnicos especializados.\n\n" .
                    "⚡ ¿Qué cubre esta oferta?\n" .
                    "Reparación de celulares, laptops, tablets, computadoras de escritorio y mucho más. Nuestro equipo técnico certificado está listo para ayudarte.\n\n" .
                    "📋 Condición: {$condicion_texto}\n\n" .
                    "📅 Válida hasta el {$fecha_fin_format}.\n\n" .
                    "¡Solicita tu servicio ahora mismo desde nuestra página web y obtén tu descuento de forma automática! 🚀";

                $oferta_new_id = $this->db->lastInsertId();

                $noticia_stmt = $this->db->prepare("INSERT INTO noticias (titulo, autor, contenido, tipo_media, oferta_servicio_id, fecha_publicacion) VALUES (?, ?, ?, ?, ?, NOW())");
                $noticia_stmt->execute([$titulo_noticia, 'WorldCell', $contenido_noticia, null, $oferta_new_id]);
            } catch (\Exception $e) {
                // Silencioso si falla la noticia
            }
            // --- Fin noticia automática ---

            $this->redirect('admin/ofertas');
        }
    }

    public function ofertasServiciosDelete($id) {
        $stmt = $this->db->prepare("DELETE FROM ofertas_servicios WHERE id = ?");
        $stmt->execute([$id]);
        $this->redirect('admin/ofertas');
    }

    public function ofertasCreate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $producto_id = $_POST['producto_id'] ?? '';
            $descuento_porcentaje = $_POST['descuento_porcentaje'] ?? '';
            $fecha_inicio = $_POST['fecha_inicio'] ?? '';
            $fecha_fin = $_POST['fecha_fin'] ?? '';
            $stmt = $this->db->prepare("INSERT INTO ofertas (producto_id, descuento_porcentaje, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?)");
            $stmt->execute([$producto_id, $descuento_porcentaje, $fecha_inicio, $fecha_fin]);

            // --- Crear noticia automática para la oferta de producto ---
            try {
                $prod = $this->db->prepare("SELECT p.nombre, p.precio, p.imagen_url, c.nombre AS categoria FROM productos p LEFT JOIN categorias c ON p.categoria_id = c.id WHERE p.id = ?");
                $prod->execute([$producto_id]);
                $producto = $prod->fetch(PDO::FETCH_ASSOC);

                if ($producto) {
                    $nombre_prod    = $producto['nombre'];
                    $categoria      = $producto['categoria'] ?? 'Tecnología';
                    $precio_orig    = number_format($producto['precio'], 2);
                    $precio_desc    = number_format($producto['precio'] * (1 - $descuento_porcentaje / 100), 2);
                    $fecha_fin_fmt  = date('d \d\e F \d\e Y', strtotime($fecha_fin));

                    $titulo = "🔥 ¡OFERTA! -{$descuento_porcentaje}% en {$nombre_prod}";

                    $contenido =
                        "¡Aprovecha esta promoción exclusiva! 🎉\n\n" .
                        "Tenemos el {$nombre_prod} con un increíble {$descuento_porcentaje}% de descuento.\n\n" .
                        "💰 Precio original: \${$precio_orig}\n" .
                        "🏷️ Precio con descuento: \${$precio_desc}\n\n" .
                        "📦 Categoría: {$categoria}\n\n" .
                        "📅 Oferta válida hasta el {$fecha_fin_fmt}.\n\n" .
                        "¡No dejes pasar esta oportunidad! Visita nuestra tienda y adquiere el tuyo antes de que se acabe el stock. 🚀";

                    $oferta_prod_id = $this->db->lastInsertId();

                    $n_stmt = $this->db->prepare("INSERT INTO noticias (titulo, autor, contenido, tipo_media, oferta_producto_id, fecha_fin, fecha_publicacion) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                    $n_stmt->execute([$titulo, 'WorldCell', $contenido, null, $oferta_prod_id, $fecha_fin ?: null]);
                }
            } catch (\Exception $e) {
                // Silencioso si falla la noticia
            }
            // --- Fin noticia automática ---

            $this->redirect('admin/ofertas');
        } else {
            $this->viewAdmin('admin/ofertas/create');
        }
    }

    public function ofertasDelete($id) {
        $stmt = $this->db->prepare("DELETE FROM ofertas WHERE id = ?");
        $stmt->execute([$id]);
        $this->redirect('admin/ofertas');
    }

    // --- Noticias CRUD ---
    public function noticias() {
        $records = $this->db->query("SELECT * FROM noticias ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $this->viewAdmin('admin/noticias/index', ['records' => $records]);
    }

    public function noticiasCreate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titulo = $_POST['titulo'] ?? '';
            $autor = $_POST['autor'] ?? 'Redacción';
            $contenido = $_POST['contenido'] ?? '';
            $tipo_media = $_POST['tipo_media'] ?? 'texto';
            $url_externa = $_POST['url_externa'] ?? '';
            $media_url = $this->handleUpload('file_upload', 'img/noticias/');
            
            $stmt = $this->db->prepare("INSERT INTO noticias (titulo, autor, contenido, tipo_media, media_url, url_externa) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$titulo, $autor, $contenido, $tipo_media, $media_url, $url_externa]);
            $this->redirect('admin/noticias');
        } else {
            $this->viewAdmin('admin/noticias/create');
        }
    }

    public function noticiasEdit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titulo = $_POST['titulo'] ?? '';
            $autor = $_POST['autor'] ?? '';
            $contenido = $_POST['contenido'] ?? '';
            $tipo_media = $_POST['tipo_media'] ?? 'texto';
            $url_externa = $_POST['url_externa'] ?? '';
            
            // Handle file upload
            $media_url = $_POST['current_media'] ?? '';
            if (!empty($_FILES['file_upload']['name'])) {
                $new_media = $this->handleUpload('file_upload', 'img/noticias/');
                if ($new_media) $media_url = $new_media;
            }

            $stmt = $this->db->prepare("UPDATE noticias SET titulo = ?, autor = ?, contenido = ?, tipo_media = ?, media_url = ?, url_externa = ? WHERE id = ?");
            $stmt->execute([$titulo, $autor, $contenido, $tipo_media, $media_url, $url_externa, $id]);
            $this->redirect('admin/noticias');
        } else {
            $stmt = $this->db->prepare("SELECT * FROM noticias WHERE id = ?");
            $stmt->execute([$id]);
            $record = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->viewAdmin('admin/noticias/edit', ['record' => $record]);
        }
    }

    public function noticiasDelete($id) {
        $stmt = $this->db->prepare("DELETE FROM noticias WHERE id = ?");
        $stmt->execute([$id]);
        $this->redirect('admin/noticias');
    }

    // --- Servicio Técnico CRUD ---
public function servicio() {
    // Solo mostrar servicios que NO estén entregados hace más de 24 horas
    $records = $this->db->query("
        SELECT s.*, CONCAT(u.nombre, ' ', u.apellido) AS usuario_nombre
        FROM servicio_tecnico s
        LEFT JOIN usuarios u ON s.usuario_id = u.id
        WHERE s.estado != 'Entregado' 
           OR s.fecha_entregado IS NULL 
           OR s.fecha_entregado > (NOW() - INTERVAL 24 HOUR)
        ORDER BY s.id DESC
    ")->fetchAll(PDO::FETCH_ASSOC);
        $usuarios = $this->db->query("SELECT id, nombre, apellido, correo FROM usuarios ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
        
        // Configuración WhatsApp
        $whatsapp_config = $this->db->query("SELECT * FROM configuracion_whatsapp ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

        // Configuración de Precios
    $precios_config = $this->db->query("SELECT * FROM configuracion_precios_servicio ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

    // Configuración API WhatsApp
    $whatsapp_api = $this->db->query("SELECT * FROM configuracion_whatsapp_api WHERE id = 1")->fetch(PDO::FETCH_ASSOC);

    $this->viewAdmin('admin/servicio/index', [
        'records' => $records, 
        'usuarios' => $usuarios,
        'whatsapp_config' => $whatsapp_config,
        'precios_config' => $precios_config,
        'whatsapp_api' => $whatsapp_api
    ]);
}

    public function servicioCreate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario_id = $_POST['usuario_id'];
            $dispositivo = $_POST['dispositivo'] ?? '';
            $descripcion = $_POST['descripcion_problema'];
            $prioridad = $_POST['prioridad'] ?? 'Media';
            $estado = $_POST['estado'] ?? 'Pendiente';
            $fecha_fin = !empty($_POST['fecha_fin']) ? $_POST['fecha_fin'] : null;
            $precio = $_POST['precio_estimado'] ?? 0;

            $tipo_entrega = $_POST['tipo_entrega'] ?? 'Entrega fisica';
            $ubicacion_domicilio = !empty($_POST['ubicacion_domicilio']) ? $_POST['ubicacion_domicilio'] : null;
            $fecha_domicilio = !empty($_POST['fecha_domicilio']) ? $_POST['fecha_domicilio'] : null;
            $hora_domicilio = !empty($_POST['hora_domicilio']) ? $_POST['hora_domicilio'] : null;
            $sucursal_local = !empty($_POST['sucursal_local']) ? $_POST['sucursal_local'] : null;
            $metodo_envio = !empty($_POST['metodo_envio']) ? $_POST['metodo_envio'] : null;
            $fecha_local = !empty($_POST['fecha_local']) ? $_POST['fecha_local'] : null;
            $hora_local = !empty($_POST['hora_local']) ? $_POST['hora_local'] : null;

            $stmt = $this->db->prepare("INSERT INTO servicio_tecnico (usuario_id, dispositivo, descripcion_problema, prioridad, estado, fecha_fin, precio_estimado, tipo_entrega, ubicacion_domicilio, fecha_domicilio, hora_domicilio, sucursal_local, metodo_envio, fecha_local, hora_local) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$usuario_id, $dispositivo, $descripcion, $prioridad, $estado, $fecha_fin, $precio, $tipo_entrega, $ubicacion_domicilio, $fecha_domicilio, $hora_domicilio, $sucursal_local, $metodo_envio, $fecha_local, $hora_local]);
            
            $id = $this->db->lastInsertId();
            $this->enviarComprobanteServicio($id);
            $_SESSION['download_pdf_id'] = $id;

            header('Location: ' . URL_BASE . 'admin/servicio');
        } else {
            $this->viewAdmin('admin/servicio/create');
        }
    }

    public function servicioPdf($id) {
        $pdfContent = $this->generarPdfServicio($id);
        if ($pdfContent) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="Comprobante_Servicio_'.$id.'.pdf"');
            echo $pdfContent;
        } else {
            echo "Error al generar PDF o servicio no encontrado.";
        }
    }

    private function generarPdfServicio($id) {
        $stmt = $this->db->prepare("
            SELECT s.*, u.nombre, u.apellido, u.correo, u.cedula, u.telefono 
            FROM servicio_tecnico s 
            JOIN usuarios u ON s.usuario_id = u.id 
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return false;

        require_once dirname(__DIR__) . '/vendor/autoload.php';
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);

        ob_start();
        include dirname(__DIR__) . '/views/templates/pdf_servicio.php';
        $html = ob_get_clean();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->output();
    }

    private function enviarComprobanteServicio($id) {
        $stmt = $this->db->prepare("
            SELECT s.*, u.correo, u.nombre, u.apellido 
            FROM servicio_tecnico s 
            JOIN usuarios u ON s.usuario_id = u.id 
            WHERE s.id = ?
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$row || empty($row['correo'])) return;

        $pdfContent = $this->generarPdfServicio($id);
        if (!$pdfContent) return;

        $correo_cliente = $row['correo'];
        $nombre_cliente = $row['nombre'].' '.$row['apellido'];
        $asunto = "Comprobante de Servicio Tecnico #" . $id . " - " . APP_NAME;
        $mensajeHTML = "Hola " . $nombre_cliente . ",<br><br>Adjuntamos el comprobante de su solicitud de servicio tecnico.<br><br>Estado: " . $row['estado'] . "<br><br>Gracias por confiar en " . APP_NAME . ".";

        $phpmailerPath = dirname(__DIR__) . '/vendor/autoload.php';
        if (file_exists($phpmailerPath)) {
            require_once $phpmailerPath;
            try {
                $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
                $mail->isSMTP();
                $mail->Host       = SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = SMTP_USER;
                $mail->Password   = SMTP_PASS;
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = SMTP_PORT;

                $mail->setFrom(SMTP_USER, APP_NAME);
                $mail->addAddress($correo_cliente, $nombre_cliente);

                $mail->isHTML(true);
                $mail->Subject = $asunto;
                $mail->Body    = $mensajeHTML;

                $mail->addStringAttachment($pdfContent, 'Comprobante_Servicio_'.$id.'.pdf');

                $mail->send();
            } catch (Exception $e) {
                error_log("PHPMailer Error en Comprobante: " . $e->getMessage());
            }
        }
    }

    // --- WhatsApp Config CRUD ---
    public function whatsappConfigSave() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $numero = $_POST['numero'];
            $nombre = $_POST['nombre_admin'];
            $id = $_POST['id'] ?? null;

            if ($id) {
                $stmt = $this->db->prepare("UPDATE configuracion_whatsapp SET numero = ?, nombre_admin = ? WHERE id = ?");
                $stmt->execute([$numero, $nombre, $id]);
            } else {
                $stmt = $this->db->prepare("INSERT INTO configuracion_whatsapp (numero, nombre_admin) VALUES (?, ?)");
                $stmt->execute([$numero, $nombre]);
            }
        }
        header('Location: ' . URL_BASE . 'admin/servicio');
    }

    // --- Pricing Config CRUD ---
    public function pricingConfigSave() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $valor = $_POST['valor'];
            $descripcion = $_POST['descripcion'] ?? '';

            if ($id) {
                $stmt = $this->db->prepare("UPDATE configuracion_precios_servicio SET valor = ?, descripcion = ? WHERE id = ?");
                $stmt->execute([$valor, $descripcion, $id]);
            }
        }
        header('Location: ' . URL_BASE . 'admin/servicio');
    }

    public function servicioUpdate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $estado_nuevo = $_POST['estado'];
            $precio_nuevo = $_POST['precio_estimado'];
            $nota_admin = trim($_POST['nota_admin'] ?? '');
            
            // Obtener datos anteriores para comparar y para el mensaje
            $old = $this->db->query("
                SELECT s.*, CONCAT(u.nombre, ' ', u.apellido) AS usuario_nombre, u.telefono, u.correo
                FROM servicio_tecnico s 
                JOIN usuarios u ON s.usuario_id = u.id 
                WHERE s.id = $id
            ")->fetch(PDO::FETCH_ASSOC);

            // Obtener información de ubicación del local
            $ubicacion = $this->db->query("SELECT nombre, direccion FROM ubicacion_local LIMIT 1")->fetch(PDO::FETCH_ASSOC);
            $nombre_local = $ubicacion ? $ubicacion['nombre'] : "nuestro local";
            $direccion_local = $ubicacion ? $ubicacion['direccion'] : "nuestra matriz";

            // Si el estado cambia a Entregado, guardamos el momento actual
            $fecha_entregado = null;
            if ($estado_nuevo === 'Entregado') {
                $fecha_entregado = date('Y-m-d H:i:s');
            }

            // Si el servicio tiene un descuento vigente, el admin ingresa el precio BASE
            // y nosotros calculamos el precio final con el descuento
            $precio_base_guardado = null;
            $precio_final = (float)$precio_nuevo;

            if (!empty($old['descuento_porcentaje']) && (float)$old['descuento_porcentaje'] > 0) {
                $precio_base_guardado = (float)$precio_nuevo;
                $precio_final = round($precio_base_guardado * (1 - (float)$old['descuento_porcentaje'] / 100), 2);
            }

            $stmt = $this->db->prepare("UPDATE servicio_tecnico SET estado = ?, precio_estimado = ?, precio_base = COALESCE(?, precio_base), fecha_entregado = IFNULL(?, fecha_entregado) WHERE id = ?");
            if ($stmt->execute([$estado_nuevo, $precio_final, $precio_base_guardado, $fecha_entregado, $id])) {
                $precio_nuevo = $precio_final; // Use final price for messaging
                
                // Formatear Fecha Entrega si existe
                $fecha_fin_format = !empty($old['fecha_fin']) ? date('d/m/Y - H:i', strtotime($old['fecha_fin'])) : 'No definida';
                
                // Construir base del mensaje con la información del formulario (datos actualizados)
                $datosServicio = "📋 *Datos del Servicio (#$id)*\n";
                $datosServicio .= "👤 *Cliente:* {$old['usuario_nombre']}\n";
                $datosServicio .= "📱 *Número:* {$old['telefono']}\n";
                $datosServicio .= "💻 *Equipo:* " . ($old['dispositivo'] ?? 'N/A') . "\n";
                $datosServicio .= "🚨 *Prioridad:* {$old['prioridad']}\n";
                $datosServicio .= "📅 *Entrega Estimada:* $fecha_fin_format\n";
                $datosServicio .= "📝 *Problema:* " . ($old['descripcion_problema'] ?? 'N/A') . "\n";
                $datosServicio .= "----------------------\n";
                $datosServicio .= "🔹 *Estado Actual:* *$estado_nuevo*\n";
                $datosServicio .= "💰 *Total a pagar:* *\$$precio_nuevo*\n";
                
                // Detectar cambios específicos para alertar
                $alertas = [];
                if ($old['estado'] !== $estado_nuevo) {
                    $alertas[] = "⚠️ *Atención:* El estado del equipo cambió de '{$old['estado']}' a '*$estado_nuevo*'.";
                }
                if (floatval($old['precio_estimado']) !== floatval($precio_nuevo)) {
                    $alertas[] = "⚠️ *Atención:* El precio se actualizó de \${$old['precio_estimado']} a '*\$$precio_nuevo*'.";
                }

                $alertasTexto = !empty($alertas) ? "\n\n" . implode("\n", $alertas) . "\n" : "";
                
                $notaAdminTexto = "";
                if (!empty($nota_admin)) {
                    $notaAdminTexto = "\n📌 *Nota del Administrador:*\n\"$nota_admin\"\n";
                }

                // 1. Enviar Mensaje a Administradores (solo si hubo algún cambio de estado, precio o hay una nota)
                if (!empty($alertas) || !empty($nota_admin)) {
                    $admins = $this->db->query("SELECT numero FROM configuracion_whatsapp")->fetchAll(PDO::FETCH_COLUMN);
                    
                    $mensajeAdmin = "🔄 *ACTUALIZACIÓN DE SERVICIO TÉCNICO*\n\n";
                    $mensajeAdmin .= "Se ha modificado la solicitud por parte de un administrador.\n\n";
                    $mensajeAdmin .= $datosServicio;
                    $mensajeAdmin .= $alertasTexto;
                    $mensajeAdmin .= $notaAdminTexto;
                    $mensajeAdmin .= "\nPor favor revisa el panel para más detalles.";

                    foreach ($admins as $numero) {
                        if (empty(trim($numero))) continue;
                        $this->sendWhatsApp($numero, $mensajeAdmin);
                    }

                    // 2. Enviar Mensaje al Cliente (Notificando de las actualizaciones)
                    if (!empty($old['telefono'])) {
                        $mensajeCliente = "🛠️ *ACTUALIZACIÓN DE TU SERVICIO (#$id)*\n\n";
                        
                        // Mensaje específico mejorado según el estado nuevo
                        if ($estado_nuevo === 'En Progreso') {
                            $mensajeCliente .= "Hola *{$old['usuario_nombre']}*, el diagnóstico inicial finalizó.\n";
                            $mensajeCliente .= "Te informamos que su dispositivo ya se encuentra en nuestro local *$nombre_local* ($direccion_local) y se empezará con su reparación.\n\n";
                        } elseif ($estado_nuevo === 'Terminado') {
                            $mensajeCliente .= "¡Excelentes noticias *{$old['usuario_nombre']}*! 🎉\n";
                            $mensajeCliente .= "La reparación de su dispositivo *{$old['dispositivo']}* ha terminado con éxito. Puede acercarse a nuestro local *$nombre_local* ubicado en ($direccion_local) a retirarlo.\n\n";
                        } elseif ($estado_nuevo === 'Entregado') {
                            $mensajeCliente .= "¡Hola *{$old['usuario_nombre']}*! ✨\n";
                            $mensajeCliente .= "Hemos registrado la entrega de su dispositivo *{$old['dispositivo']}*.\nQueremos agradecerte por haber confiado en nosotros. Esperamos que el servicio haya sido de tu completo agrado. ¡Te esperamos en tu próxima visita!\n\n";
                        } else {
                            $mensajeCliente .= "Hola *{$old['usuario_nombre']}*, un técnico ha actualizado la información sobre tu equipo:\n\n";
                        }

                        $mensajeCliente .= $datosServicio;
                        $mensajeCliente .= $alertasTexto;
                        $mensajeCliente .= $notaAdminTexto; // Incluir la nota del administrador para el cliente
                        
                        if ($estado_nuevo !== 'Entregado') {
                            $mensajeCliente .= "\nSi tienes alguna consulta adicional, no dudes en escribirnos. Gracias por preferirnos.";
                        }

                        $this->sendWhatsApp($old['telefono'], $mensajeCliente);
                    }
                }
            }
        }
        header('Location: ' . URL_BASE . 'admin/servicio');
    }

    public function whatsappApiSave() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $instance = trim($_POST['instance_id']);
            $token = trim($_POST['mensaje_token']);
            $url = trim($_POST['api_url']);

            $stmt = $this->db->prepare("UPDATE configuracion_whatsapp_api SET instance_id = ?, mensaje_token = ?, api_url = ? WHERE id = 1");
            $stmt->execute([$instance, $token, $url]);
        }
        header('Location: ' . URL_BASE . 'admin/servicio');
    }

    public function whatsappConfigDelete($id) {
        if ($id) {
            $stmt = $this->db->prepare("DELETE FROM configuracion_whatsapp WHERE id = ?");
            $stmt->execute([$id]);
        }
        header('Location: ' . URL_BASE . 'admin/servicio');
    }

    public function servicioDelete($id) {
        $stmt = $this->db->prepare("DELETE FROM servicio_tecnico WHERE id = ?");
        $stmt->execute([$id]);
        $this->redirect('admin/servicio');
    }

    // --- Pedidos CRUD ---
    public function pedidos() {
        $records = $this->db->query("
            SELECT p.*, m.tipo, e.nombre as metodo_envio 
            FROM pedidos p 
            LEFT JOIN metodos_pago m ON p.metodo_pago_id = m.id 
            LEFT JOIN metodos_envio e ON p.metodo_envio_id = e.id
            ORDER BY p.id DESC
        ")->fetchAll(PDO::FETCH_ASSOC);
        $this->viewAdmin('admin/pedidos/index', ['records' => $records]);
    }

    public function pedidosCreate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario_id     = (int)($_POST['usuario_id'] ?? 0);
            $metodo_pago_id = (int)($_POST['metodo_pago_id'] ?? 0);
            $total          = $_POST['total'] ?? 0;
            $estado         = $_POST['estado'] ?? 'Pendiente';

            if (!$usuario_id || !$metodo_pago_id) {
                $this->redirect('admin/pedidosCreate');
            }

            $stmt = $this->db->prepare("INSERT INTO pedidos (usuario_id, metodo_pago_id, total, estado) VALUES (?, ?, ?, ?)");
            $stmt->execute([$usuario_id, $metodo_pago_id, $total, $estado]);
            $pedido_id = $this->db->lastInsertId();

            // --- WhatsApp notification to client ---
            $stmtU = $this->db->prepare("SELECT nombre, apellido, telefono FROM usuarios WHERE id = ?");
            $stmtU->execute([$usuario_id]);
            $cliente = $stmtU->fetch(PDO::FETCH_ASSOC);

            $stmtM = $this->db->prepare("SELECT tipo, banco FROM metodos_pago WHERE id = ?");
            $stmtM->execute([$metodo_pago_id]);
            $metodo = $stmtM->fetch(PDO::FETCH_ASSOC);

            if ($cliente && !empty($cliente['telefono'])) {
                $telefono = preg_replace('/[^0-9]/', '', $cliente['telefono']);
                if (strpos($telefono, '593') !== 0 && strlen($telefono) == 10) {
                    $telefono = '593' . substr($telefono, 1);
                }

                $metodo_texto = $metodo ? trim($metodo['tipo'] . ' ' . ($metodo['banco'] ?? '')) : 'N/A';
                $total_fmt    = '$' . number_format((float)$total, 2);

                $mensaje  = "🛒 *NUEVO PEDIDO REGISTRADO (#{$pedido_id})*\n\n";
                $mensaje .= "Hola *{$cliente['nombre']} {$cliente['apellido']}*, ";
                $mensaje .= "se ha registrado un pedido manualmente en el sistema.\n\n";
                $mensaje .= "📋 *Detalles del Pedido:*\n";
                $mensaje .= "🔖 *N° Pedido:* #{$pedido_id}\n";
                $mensaje .= "💳 *Método de Pago:* {$metodo_texto}\n";
                $mensaje .= "💰 *Total:* {$total_fmt}\n";
                $mensaje .= "📦 *Estado:* {$estado}\n\n";
                $mensaje .= "Gracias por confiar en nosotros. Si tienes dudas, no dudes en contactarnos. 😊";

                $this->sendWhatsApp($telefono, $mensaje);
            }

            $this->redirect('admin/pedidos');
        } else {
            $metodos_pago = $this->db->query("SELECT id, tipo, banco FROM metodos_pago ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
            $this->viewAdmin('admin/pedidos/create', ['metodos_pago' => $metodos_pago]);
        }
    }

    public function pedidosDelete($id) {
        // Eliminar detalles del pedido primero para evitar error de foreign key
        $stmtDetalle = $this->db->prepare("DELETE FROM detalle_pedidos WHERE pedido_id = ?");
        $stmtDetalle->execute([$id]);

        $stmt = $this->db->prepare("DELETE FROM pedidos WHERE id = ?");
        $stmt->execute([$id]);
        $this->redirect('admin/pedidos');
    }

    public function pedidosValidarPago() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $estado_nuevo = $_POST['estado'] ?? ''; 
            $motivo_rechazo = trim($_POST['motivo_rechazo'] ?? '');

            if (!$id || !in_array($estado_nuevo, ['Pendiente', 'Confirmado', 'Rechazado', 'Entregado'])) {
                $this->redirect('admin/pedidos');
            }

            try {
                $this->db->beginTransaction();

                $stmt = $this->db->prepare("SELECT p.*, u.telefono, u.nombre, e.nombre as metodo_envio FROM pedidos p JOIN usuarios u ON p.usuario_id = u.id LEFT JOIN metodos_envio e ON p.metodo_envio_id = e.id WHERE p.id = ? FOR UPDATE");
                $stmt->execute([$id]);
                $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($pedido && $pedido['estado'] !== $estado_nuevo) {
                    $estado_anterior = $pedido['estado'];

                    $stmtUpdate = $this->db->prepare("UPDATE pedidos SET estado = ?, motivo_rechazo = ? WHERE id = ?");
                    $stmtUpdate->execute([$estado_nuevo, $motivo_rechazo, $id]);

                    // Reducir stock si pasa DE Pendiente/Rechazado A Confirmado/Entregado
                    if (in_array($estado_nuevo, ['Confirmado', 'Entregado']) && in_array($estado_anterior, ['Pendiente', 'Rechazado'])) {
                        $stmtStock = $this->db->prepare("UPDATE productos p JOIN detalle_pedidos dp ON p.id = dp.producto_id SET p.stock = p.stock - dp.cantidad WHERE dp.pedido_id = ?");
                        $stmtStock->execute([$id]);
                    } 
                    // Si pasa DE Confirmado/Entregado A Rechazado/Pendiente, devolver stock
                    elseif (in_array($estado_nuevo, ['Rechazado', 'Pendiente']) && in_array($estado_anterior, ['Confirmado', 'Entregado'])) {
                        $stmtStock = $this->db->prepare("UPDATE productos p JOIN detalle_pedidos dp ON p.id = dp.producto_id SET p.stock = p.stock + dp.cantidad WHERE dp.pedido_id = ?");
                        $stmtStock->execute([$id]);
                    }

                    $this->db->commit();

                    // Construir mensaje de WhatsApp usando UltraMsg
                    $envio_texto = $pedido['metodo_envio'] 
                        ? "🚚 *Envío:* " . trim($pedido['metodo_envio']) . " ($" . number_format($pedido['costo_envio'] ?? 0, 2) . ")" 
                        : "📍 *Envío:* Retiro en local";
                    
                    $mensaje = "🛒 *ACTUALIZACIÓN DE TU PEDIDO (#{$id})*\n\n";
                    $mensaje .= "Hola *{$pedido['nombre']}*, te informamos que el estado de tu pedido ha cambiado a: *{$estado_nuevo}*.\n";
                    $mensaje .= "{$envio_texto}\n";
                    
                    if (!empty($motivo_rechazo)) {
                        $mensaje .= "\n*Nota Administrativa:*\n_{$motivo_rechazo}_\n";
                    }

                    if ($estado_nuevo === 'Confirmado') {
                        $mensaje .= "\nTu pago ha sido validado con éxito. ¡Ya estamos preparando todo para ti! 🎉";
                    } elseif ($estado_nuevo === 'Rechazado') {
                        $mensaje .= "\nPor favor, contáctanos si crees que esto es un error o necesitas ayuda.";
                    }

                    // Limpiar teléfono y usar el método integrado de UltraMsg
                    $telefono_limpio = preg_replace('/[^0-9]/', '', $pedido['telefono']);
                    if (strpos($telefono_limpio, '593') !== 0 && strlen($telefono_limpio) == 10) {
                        $telefono_limpio = '593' . substr($telefono_limpio, 1);
                    }
                    
                    $this->sendWhatsApp($telefono_limpio, $mensaje);
                } else {
                    $this->db->rollback();
                }
                
                $this->redirect('admin/pedidos');

            } catch(PDOException $e) {
                $this->db->rollBack();
                die("Error validando el pedido: " . $e->getMessage());
            }
        } else {
            $this->redirect('admin/pedidos');
        }
    }
    // --- Reportes y Ventas ---
    public function reportes() {
        // Leer filtros de fecha
        $inicio = filter_input(INPUT_GET, 'inicio', FILTER_SANITIZE_STRING) ?: null;
        $fin = filter_input(INPUT_GET, 'fin', FILTER_SANITIZE_STRING) ?: null;

        $wherePedidos = "";
        $whereServicios = "";
        $params = [];

        if ($inicio && $fin) {
            $wherePedidos = "WHERE DATE(fecha_pedido) BETWEEN ? AND ?";
            $whereServicios = "WHERE DATE(fecha_solicitud) BETWEEN ? AND ?";
            $params = [$inicio, $fin];
        }

        // Totales de Pedidos agrupados por estado
        $stmtPedidos = $this->db->prepare("
            SELECT estado, COUNT(*) as cantidad, SUM(total) as monto 
            FROM pedidos 
            $wherePedidos
            GROUP BY estado
        ");
        $stmtPedidos->execute($params);
        $reportePedidos = $stmtPedidos->fetchAll(PDO::FETCH_ASSOC);

        // Totales de Servicios Técnicos agrupados por estado
        $stmtServicios = $this->db->prepare("
            SELECT estado, COUNT(*) as cantidad, SUM(precio_estimado) as monto 
            FROM servicio_tecnico 
            $whereServicios
            GROUP BY estado
        ");
        $stmtServicios->execute($params);
        $reporteServicios = $stmtServicios->fetchAll(PDO::FETCH_ASSOC);

        // Usuario actual para imprimir en el PDF
        $user_id = $_SESSION['user_id'] ?? 0;
        $stmtUser = $this->db->prepare("SELECT nombre, apellido, cedula FROM usuarios WHERE id = ?");
        $stmtUser->execute([$user_id]);
        $currentUser = $stmtUser->fetch(PDO::FETCH_ASSOC);

        // --- Extracción de Detalles para el PDF ---
        $stmtListaPedidos = $this->db->prepare("
            SELECT 
                p.id as pedido_id, p.fecha_pedido, p.total, p.estado, 
                u.nombre, u.apellido, u.cedula, u.correo,
                (SELECT GROUP_CONCAT(CONCAT(pr.nombre, ' (x', dp.cantidad, ')') SEPARATOR ', ') 
                 FROM detalle_pedidos dp 
                 JOIN productos pr ON dp.producto_id = pr.id 
                 WHERE dp.pedido_id = p.id) as detalle
            FROM pedidos p
            LEFT JOIN usuarios u ON p.usuario_id = u.id
            $wherePedidos
            ORDER BY p.fecha_pedido DESC
        ");
        $stmtListaPedidos->execute($params);
        $listaPedidos = $stmtListaPedidos->fetchAll(PDO::FETCH_ASSOC);

        $stmtListaServicios = $this->db->prepare("
            SELECT 
                s.id as servicio_id, s.fecha_solicitud, s.precio_estimado as total, s.estado, 
                u.nombre, u.apellido, u.cedula, u.correo,
                CONCAT(s.dispositivo, ' - ', s.descripcion_problema) as detalle
            FROM servicio_tecnico s
            LEFT JOIN usuarios u ON s.usuario_id = u.id
            $whereServicios
            ORDER BY s.fecha_solicitud DESC
        ");
        $stmtListaServicios->execute($params);
        $listaServicios = $stmtListaServicios->fetchAll(PDO::FETCH_ASSOC);

        $this->viewAdmin('admin/reportes/index', [
            'reportePedidos' => $reportePedidos,
            'reporteServicios' => $reporteServicios,
            'listaPedidos' => $listaPedidos,
            'listaServicios' => $listaServicios,
            'currentUser' => $currentUser,
            'inicio' => $inicio,
            'fin' => $fin
        ]);
    }

    // --- Usuarios CRUD ---
    public function usuarios() {
        $records = $this->db->query("SELECT * FROM usuarios ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $this->viewAdmin('admin/usuarios/index', ['records' => $records]);
    }

    public function usuariosCreate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $rol_id = $_POST['rol_id'] ?? '';
            $nombre = $_POST['nombre'] ?? '';
            $apellido = $_POST['apellido'] ?? '';
            $correo = $_POST['correo'] ?? '';
            $stmt = $this->db->prepare("INSERT INTO usuarios (rol_id, nombre, apellido, correo) VALUES (?, ?, ?, ?)");
            $stmt->execute([$rol_id, $nombre, $apellido, $correo]);
            $this->redirect('admin/usuarios');
        } else {
            $this->viewAdmin('admin/usuarios/create');
        }
    }

    public function usuariosDelete($id) {
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $this->redirect('admin/usuarios');
    }

    // --- Métodos de Pago CRUD ---
    public function metodos_pago() {
        $records = $this->db->query("SELECT * FROM metodos_pago ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $this->viewAdmin('admin/metodos_pago/index', ['records' => $records]);
    }

    public function metodos_pagoCreate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $tipo = $_POST['tipo'] ?? '';
            $banco = $_POST['banco'] ?? '';
            $numero_cuenta = $_POST['numero_cuenta'] ?? '';
            $titular = $_POST['titular'] ?? '';
            $stmt = $this->db->prepare("INSERT INTO metodos_pago (tipo, banco, numero_cuenta, titular) VALUES (?, ?, ?, ?)");
            $stmt->execute([$tipo, $banco, $numero_cuenta, $titular]);
            $this->redirect('admin/metodos_pago');
        } else {
            $this->viewAdmin('admin/metodos_pago/create');
        }
    }

    public function metodos_pagoDelete($id) {
        $stmt = $this->db->prepare("DELETE FROM metodos_pago WHERE id = ?");
        $stmt->execute([$id]);
        $this->redirect('admin/metodos_pago');
    }

    // --- Servicios de Mantenimiento CRUD ---
    public function servicios() {
        $records = $this->db->query("SELECT * FROM servicios_mantenimiento ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $this->viewAdmin('admin/servicios/index', ['records' => $records]);
    }

    public function serviciosCreate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $icono = $_POST['icono'] ?? 'fas fa-tools';
            $precio_desde = $_POST['precio_desde'] ?? 0;
            
            $stmt = $this->db->prepare("INSERT INTO servicios_mantenimiento (nombre, descripcion, icono, precio_desde) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nombre, $descripcion, $icono, $precio_desde]);
            $this->redirect('admin/servicios');
        } else {
            $this->viewAdmin('admin/servicios/create');
        }
    }

    public function serviciosEdit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $descripcion = $_POST['descripcion'] ?? '';
            $icono = $_POST['icono'] ?? 'fas fa-tools';
            $precio_desde = $_POST['precio_desde'] ?? 0;
            $estado = isset($_POST['estado']) ? 1 : 0;

            $stmt = $this->db->prepare("UPDATE servicios_mantenimiento SET nombre = ?, descripcion = ?, icono = ?, precio_desde = ?, estado = ? WHERE id = ?");
            $stmt->execute([$nombre, $descripcion, $icono, $precio_desde, $estado, $id]);
            $this->redirect('admin/servicios');
        } else {
            $stmt = $this->db->prepare("SELECT * FROM servicios_mantenimiento WHERE id = ?");
            $stmt->execute([$id]);
            $record = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->viewAdmin('admin/servicios/edit', ['record' => $record]);
        }
    }

    public function serviciosDelete($id) {
        $stmt = $this->db->prepare("DELETE FROM servicios_mantenimiento WHERE id = ?");
        $stmt->execute([$id]);
        $this->redirect('admin/servicios');
    }

    // --- Ubicaciones CRUD ---
    public function ubicaciones() {
        $records = $this->db->query("SELECT * FROM ubicacion_local ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $this->viewAdmin('admin/ubicaciones/index', ['records' => $records]);
    }

    public function ubicacionesCreate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $latitud = $_POST['latitud'] ?? '';
            $longitud = $_POST['longitud'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $horario = $_POST['horario'] ?? '';
            
            // Auto-generar el iframe de Google Maps usando la latitud y longitud
            $iframe_mapa = '';
            if (!empty($latitud) && !empty($longitud)) {
                $iframe_mapa = '<iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=' . rawurlencode($latitud) . ',' . rawurlencode($longitud) . '&hl=es&z=14&amp;output=embed"></iframe>';
            }

            $stmt = $this->db->prepare("INSERT INTO ubicacion_local (nombre, direccion, latitud, longitud, telefono, horario, iframe_mapa) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $direccion, $latitud, $longitud, $telefono, $horario, $iframe_mapa]);
            $this->redirect('admin/ubicaciones');
        } else {
            $this->viewAdmin('admin/ubicaciones/create');
        }
    }
    public function ubicacionesEdit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $direccion = $_POST['direccion'] ?? '';
            $latitud = $_POST['latitud'] ?? '';
            $longitud = $_POST['longitud'] ?? '';
            $telefono = $_POST['telefono'] ?? '';
            $horario = $_POST['horario'] ?? '';
            
            // Auto-generar el iframe de Google Maps al actualizar
            $iframe_mapa = '';
            if (!empty($latitud) && !empty($longitud)) {
                $iframe_mapa = '<iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=' . rawurlencode($latitud) . ',' . rawurlencode($longitud) . '&hl=es&z=14&amp;output=embed"></iframe>';
            }
            
            $stmt = $this->db->prepare("UPDATE ubicacion_local SET nombre = ?, direccion = ?, latitud = ?, longitud = ?, telefono = ?, horario = ?, iframe_mapa = ? WHERE id = ?");
            $stmt->execute([$nombre, $direccion, $latitud, $longitud, $telefono, $horario, $iframe_mapa, $id]);
            $this->redirect('admin/ubicaciones');
        } else {
            $stmt = $this->db->prepare("SELECT * FROM ubicacion_local WHERE id = ?");
            $stmt->execute([$id]);
            $ubicacion = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$ubicacion) $this->redirect('admin/ubicaciones');
            $this->viewAdmin('admin/ubicaciones/edit', ['ubicacion' => $ubicacion]);
        }
    }
    public function ubicacionesDelete($id) {
        $stmt = $this->db->prepare("DELETE FROM ubicacion_local WHERE id = ?");
        $stmt->execute([$id]);
        $this->redirect('admin/ubicaciones');
    }

    // --- Configuración CRUD ---
    public function configuracion() {
        $records = $this->db->query("SELECT * FROM configuracion_general ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $this->viewAdmin('admin/configuracion/index', ['records' => $records]);
    }

    public function configuracionCreate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $clave = $_POST['clave'] ?? '';
            $valor = $_POST['valor'] ?? '';
            
            $uploadedPath = $this->handleUpload('file_upload', 'img/config/');
            if ($uploadedPath !== '') {
                $valor = $uploadedPath;
            }

            $stmt = $this->db->prepare("INSERT INTO configuracion_general (clave, valor) VALUES (?, ?) ON DUPLICATE KEY UPDATE valor = ?");
            $stmt->execute([$clave, $valor, $valor]);
            $this->redirect('admin/configuracion');
        } else {
            $this->viewAdmin('admin/configuracion/create');
        }
    }

    public function configuracionDelete($id) {
        $stmt = $this->db->prepare("DELETE FROM configuracion_general WHERE id = ?");
        $stmt->execute([$id]);
        $this->redirect('admin/configuracion');
    }

    // --- Redes Sociales CRUD ---
    public function redes_sociales() {
        $records = $this->db->query("SELECT * FROM redes_sociales ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $this->viewAdmin('admin/redes_sociales/index', ['records' => $records]);
    }

    public function redes_socialesCreate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $url_destino = $_POST['url_destino'] ?? '';
            $icono = $_POST['icono'] ?? '';
            
            $stmt = $this->db->prepare("INSERT INTO redes_sociales (nombre, url_destino, icono) VALUES (?, ?, ?)");
            $stmt->execute([$nombre, $url_destino, $icono]);
            $this->redirect('admin/redes_sociales');
        } else {
            $this->redirect('admin/redes_sociales');
        }
    }

    public function redes_socialesDelete($id) {
        $stmt = $this->db->prepare("DELETE FROM redes_sociales WHERE id = ?");
        $stmt->execute([$id]);
        $this->redirect('admin/redes_sociales');
    }

    // --- Configuración PayPhone ---
    public function configuracionPayphone() {
        $config = $this->db->query("SELECT * FROM configuracion_payphone LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        $this->viewAdmin('admin/configuracion/payphone', ['config' => $config]);
    }

    public function configuracionPayphoneUpdate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $token_autorizacion = trim($_POST['token_autorizacion'] ?? '');
            $store_id = trim($_POST['store_id'] ?? '');
            $ambiente = trim($_POST['ambiente'] ?? 'Pruebas');
            
            $stmt = $this->db->prepare("
                UPDATE configuracion_payphone 
                SET token_autorizacion = ?, store_id = ?, ambiente = ? 
                WHERE id = 1
            ");
            
            // If the table is empty for some reason, insert it first (fallback)
            $check = $this->db->query("SELECT COUNT(*) FROM configuracion_payphone")->fetchColumn();
            if ($check == 0) {
                $stmtInsert = $this->db->prepare("INSERT INTO configuracion_payphone (id, token_autorizacion, store_id, ambiente) VALUES (1, ?, ?, ?)");
                $stmtInsert->execute([$token_autorizacion, $store_id, $ambiente]);
            } else {
                $stmt->execute([$token_autorizacion, $store_id, $ambiente]);
            }
            
            $_SESSION['flash_message'] = "Configuración de PayPhone actualizada exitosamente.";
            $this->redirect('admin/configuracionPayphone');
        } else {
            $this->redirect('admin/configuracionPayphone');
        }
    }

    // --- MÉTODOS DE ENVÍO CRUD ---
    public function metodos() {
        $records = $this->db->query("SELECT * FROM metodos_envio ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $this->viewAdmin('admin/metodos_envio/index', ['records' => $records]);
    }

    public function metodosCreate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $costo_base = $_POST['costo_base'] ?? 0;
            $tiempo_estimado = $_POST['tiempo_estimado'] ?? '';
            $estado = isset($_POST['estado']) ? 1 : 0;
            
            $stmt = $this->db->prepare("INSERT INTO metodos_envio (nombre, costo_base, tiempo_estimado, estado) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nombre, $costo_base, $tiempo_estimado, $estado]);
            $this->redirect('admin/metodos');
        }
    }

    public function metodosEdit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $costo_base = $_POST['costo_base'] ?? 0;
            $tiempo_estimado = $_POST['tiempo_estimado'] ?? '';
            $estado = isset($_POST['estado']) ? 1 : 0;
            
            $stmt = $this->db->prepare("UPDATE metodos_envio SET nombre = ?, costo_base = ?, tiempo_estimado = ?, estado = ? WHERE id = ?");
            $stmt->execute([$nombre, $costo_base, $tiempo_estimado, $estado, $id]);
            $this->redirect('admin/metodos');
        }
    }

    public function metodosDelete($id) {
        $stmt = $this->db->prepare("DELETE FROM metodos_envio WHERE id = ?");
        $stmt->execute([$id]);
        $this->redirect('admin/metodos');
    }

    // --- REGLAS DE ENVÍO CRUD ---
    public function reglas_envio() {
        $records = $this->db->query("SELECT * FROM reglas_envio ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        $this->viewAdmin('admin/reglas_envio/index', ['records' => $records]);
    }

    public function reglas_envioCreate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $monto_minimo_carrito = $_POST['monto_minimo_carrito'] ?? 0;
            $costo_fijo = $_POST['costo_fijo'] ?? 0;
            $estado = isset($_POST['estado']) ? 1 : 0;
            
            $stmt = $this->db->prepare("INSERT INTO reglas_envio (nombre, monto_minimo_carrito, costo_fijo, estado) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nombre, $monto_minimo_carrito, $costo_fijo, $estado]);
            $this->redirect('admin/reglas_envio');
        }
    }

    public function reglas_envioEdit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = $_POST['nombre'] ?? '';
            $monto_minimo_carrito = $_POST['monto_minimo_carrito'] ?? 0;
            $costo_fijo = $_POST['costo_fijo'] ?? 0;
            $estado = isset($_POST['estado']) ? 1 : 0;
            
            $stmt = $this->db->prepare("UPDATE reglas_envio SET nombre = ?, monto_minimo_carrito = ?, costo_fijo = ?, estado = ? WHERE id = ?");
            $stmt->execute([$nombre, $monto_minimo_carrito, $costo_fijo, $estado, $id]);
            $this->redirect('admin/reglas_envio');
        }
    }

    public function reglas_envioDelete($id) {
        $stmt = $this->db->prepare("DELETE FROM reglas_envio WHERE id = ?");
        $stmt->execute([$id]);
        $this->redirect('admin/reglas_envio');
    }

    public function ajaxSearchUsers() {
        header('Content-Type: application/json');
        if (!isset($_GET['q']) || trim($_GET['q']) === '') {
            echo json_encode([]);
            return;
        }
        
        $q = '%' . trim($_GET['q']) . '%';
        $sql = "SELECT id, nombre, apellido, correo, cedula 
                FROM usuarios 
                WHERE nombre LIKE ? 
                   OR apellido LIKE ? 
                   OR correo LIKE ? 
                   OR cedula LIKE ? 
                LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$q, $q, $q, $q]);
        
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
    }
}
