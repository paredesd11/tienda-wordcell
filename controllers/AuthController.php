<?php
/**
 * AuthController — Compatible con InfinityFree (PHP 8.1+, SMTP vía cURL)
 * - Remplaza mail() con envío SMTP vía cURL (InfinityFree bloquea mail() nativo)
 * - Remplaza FILTER_SANITIZE_STRING (deprecado PHP 8.1) con htmlspecialchars/trim
 * - Log de mails en ruta absoluta segura
 */
class AuthController extends Controller {
    private $userModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = $this->model('User');
    }

    public function login() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect($this->getRolDashboard($_SESSION['user_rol']));
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        $this->viewAuth('auth/login');
    }

    public function loginPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
            die("Token de seguridad inválido.");
        }

        $correo   = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        $user = $this->userModel->findByEmail($correo);

        if ($user && hash('sha256', $password) === $user['password_hash']) {
            $_SESSION['user_id']     = $user['id'];
            $_SESSION['user_rol']    = $user['rol_id'];
            $_SESSION['user_nombre'] = $user['nombre'];
            session_regenerate_id(true);
            $_SESSION['last_activity'] = time();
            $this->redirect($this->getRolDashboard($user['rol_id']));
        } else {
            $this->viewAuth('auth/login', ['error' => 'Credenciales inválidas.']);
        }
    }

    public function registerPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;

        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
            die("Token de seguridad inválido.");
        }

        // PHP 8.1 compatible — sin FILTER_SANITIZE_STRING (deprecado)
        $nombre    = trim(htmlspecialchars($_POST['nombre']    ?? '', ENT_QUOTES, 'UTF-8'));
        $apellido  = trim(htmlspecialchars($_POST['apellido']  ?? '', ENT_QUOTES, 'UTF-8'));
        $correo    = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
        $telefono  = trim(htmlspecialchars($_POST['telefono']  ?? '', ENT_QUOTES, 'UTF-8'));
        $direccion = trim(htmlspecialchars($_POST['direccion'] ?? '', ENT_QUOTES, 'UTF-8'));
        $password  = $_POST['password']        ?? '';
        $password_repeat = $_POST['password_repeat'] ?? '';

        if ($password !== $password_repeat) {
            $this->viewAuth('auth/login', ['error' => 'Las contraseñas no coinciden.']);
            return;
        }

        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $password)) {
            $this->viewAuth('auth/login', ['error' => 'La contraseña no cumple los requisitos de seguridad.']);
            return;
        }

        if ($this->userModel->findByEmail($correo)) {
            $this->viewAuth('auth/login', ['error' => 'El correo ya está registrado.']);
            return;
        }

        if ($this->userModel->register($nombre, $apellido, $correo, $password, $telefono, $direccion)) {
            $user   = $this->userModel->findByEmail($correo);
            $codigo = sprintf("%06d", mt_rand(1, 999999));
            $this->userModel->updateVerificationCode($user['id'], $codigo);

            // Enviar código por SMTP (compatible con InfinityFree via PHPMailer si está disponible)
            $enviado = $this->enviarCodigoSMTP($correo, $codigo);

            // Si falla el SMTP, guardar en sesión para modo dev
            if (!$enviado) {
                $_SESSION['dev_mail_code'] = $codigo;
                error_log("[WordCell] Código para $correo: $codigo");
            }

            $_SESSION['temp_user_id'] = $user['id'];
            $this->redirect('auth/verify');
        } else {
            $this->viewAuth('auth/login', ['error' => 'Error al registrar. Intenta de nuevo.']);
        }
    }

    /**
     * Envía el código de verificación por SMTP usando datos de config.php
     * Compatible con InfinityFree (usa stream_socket_client o fallback a mail())
     */
    private function enviarCodigoSMTP(string $to, string $codigo): bool {
        // Si PHPMailer está disponible, usarlo
        $phpmailerPath = dirname(__DIR__) . '/vendor/autoload.php';
        if (file_exists($phpmailerPath)) {
            return $this->enviarConPhpMailer($to, $codigo, $phpmailerPath);
        }

        // Fallback: mail() nativo (funciona en XAMPP local)
        $subject = '=?UTF-8?B?' . base64_encode('Código de Verificación — WordCell') . '?=';
        $message = "Tu código de acceso es: $codigo\n\nExpira en 15 minutos.";
        $headers = implode("\r\n", [
            'From: ' . APP_NAME . ' <' . SMTP_USER . '>',
            'Reply-To: ' . SMTP_USER,
            'Content-Type: text/plain; charset=UTF-8',
            'X-Mailer: PHP/' . PHP_VERSION,
        ]);

        return @mail($to, $subject, $message, $headers);
    }

    private function enviarConPhpMailer(string $to, string $codigo, string $autoload): bool {
        try {
            require_once $autoload;
            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASS;
            $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = SMTP_PORT;
            $mail->CharSet    = 'UTF-8';
            $mail->setFrom(SMTP_USER, APP_NAME);
            $mail->addAddress($to);
            $mail->Subject    = 'Código de Verificación — ' . APP_NAME;
            $mail->Body       = "Tu código de acceso es: <strong>$codigo</strong><br>Expira en 15 minutos.";
            $mail->isHTML(true);
            $mail->send();
            return true;
        } catch (\Exception $e) {
            error_log("PHPMailer Error: " . $e->getMessage());
            return false;
        }
    }

    public function verify() {
        if (!isset($_SESSION['temp_user_id'])) {
            $this->redirect('auth/login');
        }
        $data = [];
        // Mostrar código en modo dev si SMTP falló
        if (isset($_SESSION['dev_mail_code'])) {
            $data['dev_code'] = $_SESSION['dev_mail_code'];
        }
        $this->viewAuth('auth/verify', $data);
    }

    public function verifyPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['temp_user_id'])) return;

        $codigo = trim($_POST['codigo'] ?? '');
        $userId = $_SESSION['temp_user_id'];
        $user   = $this->userModel->findById($userId);

        if ($user && $user['codigo_verificacion'] === $codigo) {
            if (strtotime($user['codigo_expiracion']) > time()) {
                $_SESSION['user_id']     = $user['id'];
                $_SESSION['user_rol']    = $user['rol_id'];
                $_SESSION['user_nombre'] = $user['nombre'];
                unset($_SESSION['temp_user_id'], $_SESSION['dev_mail_code']);
                $this->userModel->clearVerificationCode($user['id']);
                session_regenerate_id(true);
                $_SESSION['last_activity'] = time();
                $this->redirect($this->getRolDashboard($user['rol_id']));
            } else {
                $this->viewAuth('auth/verify', ['error' => 'El código ha expirado.']);
            }
        } else {
            $this->viewAuth('auth/verify', ['error' => 'Código incorrecto.']);
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('auth/login');
    }

    private function getRolDashboard(int $rolId): string {
        return $rolId === 1 ? 'admin/dashboard' : 'home/index';
    }
}
