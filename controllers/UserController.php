<?php
class UserController extends Controller {
    private $db;
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('auth/login');
        }

        $_SESSION['last_activity'] = time();

        $this->db = (new Database())->getConnection();
        $this->userModel = $this->model('User');
    }

    public function panel() {
        $user = $this->userModel->findById($_SESSION['user_id']);
        
        // Historial de pedidos
        $stmt = $this->db->prepare("SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha_pedido DESC");
        $stmt->execute([$_SESSION['user_id']]);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Historial de reparaciones (Servicio Técnico)
        $stmt_rep = $this->db->prepare("SELECT * FROM servicio_tecnico WHERE usuario_id = ? ORDER BY fecha_solicitud DESC");
        $stmt_rep->execute([$_SESSION['user_id']]);
        $reparaciones = $stmt_rep->fetchAll(PDO::FETCH_ASSOC);

        $this->view('user/panel', [
            'user' => $user, 
            'pedidos' => $pedidos,
            'reparaciones' => $reparaciones
        ]);
    }

    public function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_STRING);
            $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
            $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);

            if ($this->userModel->updateProfile($_SESSION['user_id'], $nombre, $apellido, $telefono, $direccion)) {
                $_SESSION['user_nombre'] = $nombre;
            }
            $this->redirect('user/panel');
        }
    }

    public function cambiarPassword() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $current = $_POST['current_password'];
            $new = $_POST['new_password'];
            $confirm = $_POST['confirm_password'];

            $user = $this->userModel->findById($_SESSION['user_id']);

            if (hash('sha256', $current) !== $user['password_hash']) {
                $_SESSION['error_password'] = "La contraseña actual es incorrecta.";
            } elseif ($new !== $confirm) {
                $_SESSION['error_password'] = "Las contraseñas nuevas no coinciden.";
            } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{9,}$/', $new)) {
                $_SESSION['error_password'] = "La nueva contraseña no cumple los requisitos de seguridad.";
            } else {
                if ($this->userModel->updatePassword($_SESSION['user_id'], $new)) {
                    $_SESSION['success_password'] = "Contraseña actualizada correctamente.";
                } else {
                    $_SESSION['error_password'] = "Error al actualizar la contraseña.";
                }
            }
            $this->redirect('user/panel');
        }
    }

    // Para cambiar correo o contraseña habría que reusar la lógica 2FA (omitido temporalmente para hacerlo "basico y claro")
    // Se deja estructurado.
}
