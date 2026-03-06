<?php
require_once __DIR__ . '/../core/Controller.php';

class ProductoController extends Controller {
    private $db;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->db = (new Database())->getConnection();
    }

    public function index($id = null) {
        if (!$id) {
            $this->redirect('catalogo');
        }

        // Fetch product with category and brand names
        $stmt = $this->db->prepare("
            SELECT p.*, c.nombre as categoria_nombre, m.nombre as marca_nombre 
            FROM productos p
            LEFT JOIN categorias c ON p.categoria_id = c.id
            LEFT JOIN marcas m ON p.marca_id = m.id
            WHERE p.id = ? AND p.estado = 1
        ");
        $stmt->execute([$id]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            $this->redirect('catalogo');
        }

        // Decode the JSON image array
        $imagenes = [];
        if (!empty($producto['imagen_url'])) {
            $decoded = json_decode($producto['imagen_url'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $imagenes = $decoded;
            } else {
                $imagenes = [$producto['imagen_url']];
            }
        }

        // If completely empty, provide a fallback
        if (empty($imagenes)) {
            $imagenes = ['public/img/Logo.webp'];
        }

        $this->view('producto/index', [
            'producto' => $producto,
            'imagenes' => $imagenes
        ]);
    }
}
