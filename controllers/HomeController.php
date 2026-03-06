<?php
class HomeController extends Controller {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = (new Database())->getConnection();
    }

    public function solicitarServicio() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
            echo json_encode([
                'success' => false, 
                'message' => 'Tu sesión ha expirado o no tienes acceso. Por favor, inicia sesión nuevamente.',
                'error_type' => 'auth'
            ]);
            return;
        }

        $usuario_id = $_SESSION['user_id'];
        $dispositivo = $_POST['dispositivo'] ?? '';
        $descripcion = $_POST['descripcion_problema'] ?? '';
        $prioridad = $_POST['prioridad'] ?? 'Media';
        $fecha_fin = $_POST['fecha_fin'] ?? null;
        $precio = $_POST['precio_estimado'] ?? 0;

        try {
            $stmt = $this->db->prepare("INSERT INTO servicio_tecnico (usuario_id, dispositivo, descripcion_problema, prioridad, fecha_fin, precio_estimado) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$usuario_id, $dispositivo, $descripcion, $prioridad, $fecha_fin, $precio]);
            
            // Notificar a Admins via WhatsApp
            $this->notificarAdminsWhatsApp($dispositivo, $prioridad, $precio, $descripcion, $fecha_fin, $_POST['fecha_inicio'] ?? '');

            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function notificarAdminsWhatsApp($dispositivo, $prioridad, $precio, $descripcion, $fecha_fin, $fecha_inicio) {
        try {
            file_put_contents(__DIR__ . '/../wa_debug2.txt', "Starting notification process...\n", FILE_APPEND);
            $admins = $this->db->query("SELECT numero FROM configuracion_whatsapp")->fetchAll(PDO::FETCH_COLUMN);
            $userId = $_SESSION['user_id'];
            $user = $this->db->query("SELECT nombre, apellido, telefono, direccion FROM usuarios WHERE id = $userId")->fetch(PDO::FETCH_ASSOC);

            file_put_contents(__DIR__ . '/../wa_debug2.txt', "Found " . count($admins) . " admins.\n", FILE_APPEND);

            $nombre_cliente = ($user['nombre'] ?? 'Cliente') . ' ' . ($user['apellido'] ?? '');
            $telefono_cliente = $user['telefono'] ?? 'No registrado';
            $direccion_cliente = $user['direccion'] ?? 'No registrada';

            $mensaje = "Teléfono del Cliente: $telefono_cliente\n";
            $mensaje .= "Equipo: $dispositivo\n";
            $mensaje .= "Prioridad: $prioridad\n";
            $mensaje .= "Fecha Entrega del Equipo: $fecha_inicio\n";
            $mensaje .= "Fecha Estimada de Retiro: $fecha_fin\n";
            $mensaje .= "Precio Estimado: $$precio\n";
            $mensaje .= "Descripción del Problema: $descripcion";

            foreach ($admins as $numero) {
                if (empty(trim($numero))) {
                    file_put_contents(__DIR__ . '/../wa_debug2.txt', "Skipped empty number.\n", FILE_APPEND);
                    continue;
                }
                file_put_contents(__DIR__ . '/../wa_debug2.txt', "Sending to $numero...\n", FILE_APPEND);
                $res = $this->sendWhatsApp($numero, $mensaje);
                file_put_contents(__DIR__ . '/../wa_debug2.txt', "Send Result: " . ($res ? "OK" : "FAIL") . "\n", FILE_APPEND);
            }
        } catch (Exception $e) {
            file_put_contents(__DIR__ . '/../wa_debug2.txt', "Exception: " . $e->getMessage() . "\n", FILE_APPEND);
            error_log("Error notificando WhatsApp: " . $e->getMessage());
        }
    }

    public function index() {
        // Marcas (para strip en hero)
        try {
            $marcas = $this->db->query("SELECT id, nombre, logo_url FROM marcas ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { $marcas = []; }

        // Últimos productos activos
        try {
            $productos = $this->db->query("SELECT * FROM productos WHERE estado = 1 ORDER BY id DESC LIMIT 8")->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { $productos = []; }

        // Noticias
        try {
            $noticias = $this->db->query("SELECT * FROM noticias ORDER BY id DESC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { $noticias = []; }

        // Referencias (Nuestros Clientes)
        try {
            $referencias = $this->db->query("SELECT * FROM referencias ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { $referencias = []; }

        // Ubicaciones
        try {
            $ubicaciones = $this->db->query("SELECT * FROM ubicacion_local")->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { $ubicaciones = []; }

        // Redes Sociales
        try {
            $redesSociales = $this->db->query("SELECT * FROM redes_sociales ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { $redesSociales = []; }

        // Ofertas Activas
        try {
            $ofertas = $this->db->query("
                SELECT o.*, p.nombre, p.precio, p.imagen_url, c.nombre as categoria_nombre 
                FROM ofertas o 
                JOIN productos p ON o.producto_id = p.id 
                JOIN categorias c ON p.categoria_id = c.id 
                WHERE o.fecha_inicio <= NOW() AND o.fecha_fin >= NOW()
            ")->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { $ofertas = []; }

        // Servicios de Mantenimiento
        try {
            $servicios = $this->db->query("SELECT * FROM servicios_mantenimiento WHERE estado = 1 ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { $servicios = []; }

        // Configuración de Precios para el JS
        try {
            $precios_config = $this->db->query("SELECT concepto, tipo, valor FROM configuracion_precios_servicio")->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { $precios_config = []; }

        $this->view('home/index', [
            'marcas'         => $marcas,
            'productos'      => $productos,
            'noticias'       => $noticias,
            'referencias'    => $referencias,
            'servicios'      => $servicios,
            'ubicaciones'    => $ubicaciones,
            'redesSociales'  => $redesSociales,
            'ofertas'        => $ofertas,
            'precios_config' => $precios_config
        ]);
    }
}
