<?php
class CarritoController extends Controller {
    
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = [];
        }
    }

    // View the cart page
    public function index() {
        $this->view('carrito/index', ['carrito' => $_SESSION['carrito']]);
    }

    // Add API to handle AJAX fetch requests from the "Añadir al Carrito" buttons
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Read JSON payload if sent via fetch
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if ($data) {
                $id = $data['id'] ?? null;
                $nombre = $data['nombre'] ?? '';
                $precio = $data['precio'] ?? 0;
                $imagen = $data['imagen'] ?? '';
                $cantidad = $data['cantidad'] ?? 1;

                if ($id) {
                    // Check if item already exists in cart
                    $found = false;
                    foreach ($_SESSION['carrito'] as &$item) {
                        if ($item['id'] == $id) {
                            $item['cantidad'] += $cantidad;
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) {
                        $_SESSION['carrito'][] = [
                            'id' => $id,
                            'nombre' => $nombre,
                            'precio' => floatval($precio),
                            'imagen' => $imagen,
                            'cantidad' => intval($cantidad)
                        ];
                    }

                    echo json_encode(['success' => true, 'count' => $this->getCartCount()]);
                    return;
                }
            }
        }
        
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }

    // Update quantity via AJAX
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            $id = $data['id'] ?? null;
            $action = $data['action'] ?? ''; // 'increase' or 'decrease'

            if ($id && $action) {
                foreach ($_SESSION['carrito'] as $k => &$item) {
                    if ($item['id'] == $id) {
                        if ($action === 'increase') {
                            $item['cantidad']++;
                        } elseif ($action === 'decrease') {
                            $item['cantidad']--;
                            if ($item['cantidad'] <= 0) {
                                unset($_SESSION['carrito'][$k]);
                            }
                        }
                        break;
                    }
                }
                // Re-index array
                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
                
                echo json_encode(['success' => true, 'count' => $this->getCartCount()]);
                return;
            }
        }
        echo json_encode(['success' => false]);
    }

    // Remove single item via POST
    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            $id = $data['id'] ?? null;

            if ($id) {
                foreach ($_SESSION['carrito'] as $k => $item) {
                    if ($item['id'] == $id) {
                        unset($_SESSION['carrito'][$k]);
                        break;
                    }
                }
                $_SESSION['carrito'] = array_values($_SESSION['carrito']);
                echo json_encode(['success' => true, 'count' => $this->getCartCount()]);
                return;
            }
        }
        echo json_encode(['success' => false]);
    }

    // Completely clear the cart
    public function clear() {
        $_SESSION['carrito'] = [];
        header('Location: ' . URL_BASE . 'carrito');
        exit;
    }

    // Endpoint to get the current cart count
    public function count() {
        echo json_encode(['count' => $this->getCartCount()]);
    }

    private function getCartCount() {
        $count = 0;
        if (isset($_SESSION['carrito'])) {
            foreach ($_SESSION['carrito'] as $item) {
                $count += $item['cantidad'];
            }
        }
        return $count;
    }
}
