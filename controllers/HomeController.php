<?php
require_once __DIR__ . '/../core/NotificationHelper.php';

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
        $precio = (float)($_POST['precio_estimado'] ?? 0);
        $oferta_id = !empty($_POST['oferta_servicio_id']) ? (int)$_POST['oferta_servicio_id'] : null;

        // Validar y auditar la oferta si fue enviada
        $precio_base = null;
        $descuento_porcentaje_guardado = null;
        $nombre_oferta_guardado = null;

        if ($oferta_id) {
            try {
                $oferta = $this->db->prepare("SELECT nombre, descuento_porcentaje FROM ofertas_servicios WHERE id = ? AND activa = 1 AND fecha_inicio <= CURDATE() AND fecha_fin >= CURDATE()");
                $oferta->execute([$oferta_id]);
                $ofertaData = $oferta->fetch(PDO::FETCH_ASSOC);

                if ($ofertaData) {
                    $desc_pct = (float)$ofertaData['descuento_porcentaje'];
                    // El precio que llega ya fue descontado. Recomputamos el base.
                    // precio_final = base * (1 - desc/100) => base = precio_final / (1 - desc/100)
                    $precio_base = round($precio / (1 - $desc_pct / 100), 2);
                    $descuento_porcentaje_guardado = $desc_pct;
                    $nombre_oferta_guardado = $ofertaData['nombre'];
                }
            } catch (Exception $e) {}
        }

        $tipo_entrega = $_POST['tipo_entrega'] ?? 'Entrega fisica';
        $ubicacion_domicilio = !empty($_POST['ubicacion_domicilio']) ? $_POST['ubicacion_domicilio'] : null;
        $fecha_domicilio = !empty($_POST['fecha_domicilio']) ? $_POST['fecha_domicilio'] : null;
        $hora_domicilio = !empty($_POST['hora_domicilio']) ? $_POST['hora_domicilio'] : null;
        $sucursal_local = !empty($_POST['sucursal_local']) ? $_POST['sucursal_local'] : null;
        $metodo_envio = !empty($_POST['metodo_envio']) ? $_POST['metodo_envio'] : null;
        $fecha_local = !empty($_POST['fecha_local']) ? $_POST['fecha_local'] : null;
        $hora_local = !empty($_POST['hora_local']) ? $_POST['hora_local'] : null;

        try {
            $stmt = $this->db->prepare("INSERT INTO servicio_tecnico (usuario_id, dispositivo, descripcion_problema, prioridad, fecha_fin, precio_estimado, precio_base, descuento_porcentaje, nombre_oferta, tipo_entrega, ubicacion_domicilio, fecha_domicilio, hora_domicilio, sucursal_local, metodo_envio, fecha_local, hora_local) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$usuario_id, $dispositivo, $descripcion, $prioridad, $fecha_fin, $precio, $precio_base, $descuento_porcentaje_guardado, $nombre_oferta_guardado, $tipo_entrega, $ubicacion_domicilio, $fecha_domicilio, $hora_domicilio, $sucursal_local, $metodo_envio, $fecha_local, $hora_local]);
            
            // Notificar a Admins via WhatsApp
            $this->notificarAdminsWhatsApp($dispositivo, $prioridad, $precio, $descripcion, $fecha_fin, $_POST['fecha_inicio'] ?? '');

            // Notificar email de pendientes
            NotificationHelper::sendPendingNotification($this->db);
            
            $id = $this->db->lastInsertId();

            echo json_encode(['success' => true, 'id' => $id]);
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

        // Noticias — filtradas según elegibilidad de oferta vinculada
        try {
            // Primero resolvemos si el usuario ya hizo alguna solicitud de servicio
            $userId = $_SESSION['user_id'] ?? null;
            $yaHizoSolicitud = false;
            if ($userId) {
                $countServ = $this->db->query("SELECT COUNT(*) FROM servicio_tecnico WHERE usuario_id = $userId")->fetchColumn();
                $yaHizoSolicitud = ($countServ > 0);
            }

            $todasNoticias = $this->db->query("
                SELECT n.*, os.condicion AS oferta_condicion, os.activa AS oferta_activa,
                       os.fecha_fin AS oferta_fecha_fin
                FROM noticias n
                LEFT JOIN ofertas_servicios os ON n.oferta_servicio_id = os.id
                ORDER BY n.id DESC LIMIT 10
            ")->fetchAll(PDO::FETCH_ASSOC);

            $noticias = [];
            foreach ($todasNoticias as $noticia) {
                // Noticia con fecha_fin propia vencida: no mostrar (aplica a ofertas de producto)
                if (!empty($noticia['fecha_fin']) && strtotime($noticia['fecha_fin']) < strtotime(date('Y-m-d'))) {
                    continue;
                }

                // Sin oferta de servicio vinculada: visible para todos
                if (empty($noticia['oferta_servicio_id'])) {
                    $noticias[] = $noticia;
                    continue;
                }

                // Oferta servicio expirada: no mostrar
                if (!empty($noticia['oferta_fecha_fin']) && strtotime($noticia['oferta_fecha_fin']) < strtotime(date('Y-m-d'))) {
                    continue;
                }

                // Oferta inactiva: no mostrar
                if (isset($noticia['oferta_activa']) && !$noticia['oferta_activa']) {
                    continue;
                }

                // Condición PRIMERA_VEZ: solo para usuarios que nunca han hecho solicitud
                if ($noticia['oferta_condicion'] === 'PRIMERA_VEZ') {
                    if ($userId && !$yaHizoSolicitud) {
                        $noticias[] = $noticia;
                    }
                    continue;
                }

                // Condición TODOS: visible para cualquiera
                $noticias[] = $noticia;
            }
            $noticias = array_slice($noticias, 0, 6);
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

        // Ofertas Activas (Productos)
        try {
            $ofertas = $this->db->query("
                SELECT o.*, p.nombre, p.precio, p.imagen_url, c.nombre as categoria_nombre 
                FROM ofertas o 
                JOIN productos p ON o.producto_id = p.id 
                JOIN categorias c ON p.categoria_id = c.id 
                WHERE o.fecha_inicio <= NOW() AND o.fecha_fin >= NOW()
            ")->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { $ofertas = []; }

        // Ofertas Activas (Servicios Técnicos)
        $ofertasServicios = [];
        try {
            $ofertasServicios = $this->db->query("
                SELECT * FROM ofertas_servicios 
                WHERE activa = 1 AND fecha_inicio <= CURDATE() AND fecha_fin >= CURDATE()
            ")->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {}

        // ¿Es primera solicitud del usuario?
        $isFirstRequest = true;
        if(isset($_SESSION['user_id'])){
            $uid = $_SESSION['user_id'];
            $count = $this->db->query("SELECT COUNT(*) FROM servicio_tecnico WHERE usuario_id = $uid")->fetchColumn();
            if($count > 0) $isFirstRequest = false;
        }

        // Servicios de Mantenimiento
        try {
            $servicios = $this->db->query("SELECT * FROM servicios_mantenimiento WHERE estado = 1 ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { $servicios = []; }

        // Configuración de Precios para el JS
        try {
            $precios_config = $this->db->query("SELECT concepto, tipo, valor FROM configuracion_precios_servicio")->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) { $precios_config = []; }

        $this->view('home/index', [
            'ofertasServicios' => $ofertasServicios,
            'isFirstRequest' => $isFirstRequest,
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
