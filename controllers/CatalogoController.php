<?php
require_once __DIR__ . '/../core/Controller.php';

class CatalogoController extends Controller {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = (new Database())->getConnection();
    }

    public function index() {
        // Obtenemos los filtros desde GET
        $search = $_GET['search'] ?? '';
        $categoria_id = $_GET['categoria'] ?? '';
        $marca_id = $_GET['marca'] ?? '';

        // Construir la consulta de productos de forma dinámica
        $sql = "SELECT p.*, c.nombre as categoria_nombre, m.nombre as marca_nombre 
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                LEFT JOIN marcas m ON p.marca_id = m.id
                WHERE p.estado = 1";
        
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (p.nombre LIKE ? OR p.descripcion LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if (!empty($categoria_id)) {
            $sql .= " AND p.categoria_id = ?";
            $params[] = $categoria_id;
        }

        if (!empty($marca_id)) {
            if (is_numeric($marca_id)) {
                $sql .= " AND p.marca_id = ?";
                $params[] = $marca_id;
            } else {
                // Si pasaron el nombre (ej: ?marca=Apple)
                $sql .= " AND m.nombre = ?";
                $params[] = $marca_id;
            }
        }

        $sql .= " ORDER BY p.fecha_creacion DESC";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Obtener categorías (solo activas)
            $categorias = $this->db->query("SELECT id, nombre FROM categorias WHERE estado = 1 ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);
            
            // Obtener marcas (solo activas)
            $marcas = $this->db->query("SELECT id, nombre FROM marcas WHERE estado = 1 ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);

        } catch (Exception $e) {
            $productos = [];
            $categorias = [];
            $marcas = [];
            error_log("Error in Catalogo: " . $e->getMessage());
        }

        $this->view('catalogo/index', [
            'productos' => $productos,
            'categorias' => $categorias,
            'marcas' => $marcas,
            'search' => $search,
            'selected_categoria' => $categoria_id,
            'selected_marca' => $marca_id
        ]);
    }
}
