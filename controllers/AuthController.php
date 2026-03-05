<?php
class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = $this->model('User');
    }

    public function login() {
        // Redirigir si ya está logueado
        if (isset($_SESSION['user_id'])) {
            $this->redirect('admin/dashboard'); // O Home
        }

        // Generar CSRF
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $this->viewAuth('auth/login');
    }

    public function loginPost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validar CSRF
            if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                die("CSRF token validation failed");
            }

            $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'];

            $user = $this->userModel->findByEmail($correo);

            if ($user && hash('sha256', $password) === $user['password_hash']) {
                // Instaurar sesión directamente sin 2FA
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_rol'] = $user['rol_id'];
                $_SESSION['user_nombre'] = $user['nombre'];
                
                // Prevenir fijación de sesión
                session_regenerate_id(true);

                // Timeout inactividad
                $_SESSION['last_activity'] = time();

                if($user['rol_id'] == 1) {
                    $this->redirect('admin/dashboard');
                } else {
                    $this->redirect('home/index');
                }
            } else {
                $this->viewAuth('auth/login', ['error' => 'Credenciales inválidas.']);
            }
        }
    }

    public function registerPost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
             // Validar CSRF
             if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
                die("CSRF token validation failed");
            }

            $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
            $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_STRING);
            $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
            $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_STRING);
            $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);
            $password = $_POST['password'];
            $password_repeat = $_POST['password_repeat'] ?? '';

            if ($password !== $password_repeat) {
                $this->viewAuth('auth/login', ['error' => 'Las contraseñas no coinciden.']);
                return;
            }

            // Validar requerimientos de contraseña: >8 chars, Upper, Lower, Number, Special
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{9,}$/', $password)) {
                $this->viewAuth('auth/login', ['error' => 'La contraseña no cumple con todos los requisitos de seguridad.']);
                return;
            }

            // Verificar si el correo ya existe
            if ($this->userModel->findByEmail($correo)) {
                $this->viewAuth('auth/login', ['error' => 'El correo ya está registrado.']);
                return;
            }

            if ($this->userModel->register($nombre, $apellido, $correo, $password, $telefono, $direccion)) {
                $user = $this->userModel->findByEmail($correo);
                
                // Generar código 2FA al registrar
                $codigo = sprintf("%06d", mt_rand(1, 999999));
                $this->userModel->updateVerificationCode($user['id'], $codigo);

                $to = $correo;
                $subject = "Confirmar tu Registro";
                $message = "Tu código de acceso es: " . $codigo;
                $headers = "From: no-reply@tienda.com";
                
                mail($to, $subject, $message, $headers);
                @file_put_contents('../logs/mail.log', "[$to] $message\n", FILE_APPEND);

                if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['SERVER_NAME'] === 'localhost') {
                    $_SESSION['dev_mail_code'] = $codigo;
                }

                $_SESSION['temp_user_id'] = $user['id'];
                
                $this->redirect('auth/verify');
            } else {
                $this->viewAuth('auth/login', ['error' => 'Error al registrar.']);
            }
        }
    }

    public function verify() {
        if (!isset($_SESSION['temp_user_id'])) {
            $this->redirect('auth/login');
        }
        $this->viewAuth('auth/verify');
    }

    public function verifyPost() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['temp_user_id'])) {
            $codigo = filter_input(INPUT_POST, 'codigo', FILTER_SANITIZE_STRING);
            $userId = $_SESSION['temp_user_id'];

            $user = $this->userModel->findById($userId);

            if ($user && $user['codigo_verificacion'] === $codigo) {
                // Verificar expiración
                if (strtotime($user['codigo_expiracion']) > time()) {
                    // Login exitoso
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_rol'] = $user['rol_id'];
                    $_SESSION['user_nombre'] = $user['nombre'];
                    
                    // Limpiar datos
                    unset($_SESSION['temp_user_id']);
                    $this->userModel->clearVerificationCode($user['id']);

                    // Prevenir fijación de sesión
                    session_regenerate_id(true);

                    // Timeout inactividad (15 min según Fase 1) - Guardamos el timestamp
                    $_SESSION['last_activity'] = time();

                    if($user['rol_id'] == 1) {
                        $this->redirect('admin/dashboard');
                    } else {
                        $this->redirect('home/index');
                    }

                } else {
                    $this->viewAuth('auth/verify', ['error' => 'El código ha expirado.']);
                }
            } else {
                $this->viewAuth('auth/verify', ['error' => 'Código incorrecto.']);
            }
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('auth/login');
    }
}
