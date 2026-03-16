<?php
/**
 * Core Helper to send email notifications for pending orders and technical services.
 */
class NotificationHelper {

    public static function sendPendingNotification($db) {
        // Query pending orders
        $stmtOrders = $db->query("
            SELECT p.id, p.total, p.fecha_pedido, u.nombre, u.apellido 
            FROM pedidos p
            LEFT JOIN usuarios u ON p.usuario_id = u.id
            WHERE p.estado = 'Pendiente'
            ORDER BY p.fecha_pedido DESC
        ");
        $pendingOrders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);

        // Query pending services
        $stmtServices = $db->query("
            SELECT s.id, s.dispositivo, s.descripcion_problema, s.fecha_solicitud, u.nombre, u.apellido
            FROM servicio_tecnico s
            LEFT JOIN usuarios u ON s.usuario_id = u.id
            WHERE s.estado = 'Pendiente'
            ORDER BY s.fecha_solicitud DESC
        ");
        $pendingServices = $stmtServices->fetchAll(PDO::FETCH_ASSOC);

        if (empty($pendingOrders) && empty($pendingServices)) {
            return false; // Nothing strictly pending to notify immediately, but usually called after insert
        }

        $htmlContent = self::buildHtmlEmail($pendingOrders, $pendingServices);
        $to = SMTP_USER; // Sent to the 2-step verification email
        
        return self::enviarSMTP($to, 'Notificación de Pendientes - ' . APP_NAME, $htmlContent);
    }

    private static function buildHtmlEmail($orders, $services) {
        $html = "<div style='font-family: Arial, sans-serif; color: #333;'>";
        $html .= "<h2 style='color: #d9534f;'>🚨 Tienes Nuevos Elementos Pendientes 🚨</h2>";
        $html .= "<p>Revisa el sistema para aprobar o procesar los siguientes pendientes:</p>";

        if (!empty($orders)) {
            $html .= "<h3 style='border-bottom: 2px solid #5bc0de; display: inline-block;'>Pedidos Pendientes (" . count($orders) . ")</h3>";
            $html .= "<table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>";
            $html .= "<tr style='background-color: #f9f9f9;'><th style='padding: 8px; border: 1px solid #ddd;'>ID</th><th style='padding: 8px; border: 1px solid #ddd;'>Cliente</th><th style='padding: 8px; border: 1px solid #ddd;'>Total</th><th style='padding: 8px; border: 1px solid #ddd;'>Fecha</th></tr>";
            foreach ($orders as $o) {
                $html .= "<tr>";
                $html .= "<td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>#{$o['id']}</td>";
                $html .= "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($o['nombre'] . ' ' . $o['apellido']) . "</td>";
                $html .= "<td style='padding: 8px; border: 1px solid #ddd; text-align: right;'>$" . number_format($o['total'], 2) . "</td>";
                $html .= "<td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>" . date('d/m/Y H:i', strtotime($o['fecha_pedido'])) . "</td>";
                $html .= "</tr>";
            }
            $html .= "</table>";
        }

        if (!empty($services)) {
            $html .= "<h3 style='border-bottom: 2px solid #f0ad4e; display: inline-block;'>Servicios Técnicos Pendientes (" . count($services) . ")</h3>";
            $html .= "<table style='width: 100%; border-collapse: collapse; margin-bottom: 20px;'>";
            $html .= "<tr style='background-color: #f9f9f9;'><th style='padding: 8px; border: 1px solid #ddd;'>ID</th><th style='padding: 8px; border: 1px solid #ddd;'>Cliente</th><th style='padding: 8px; border: 1px solid #ddd;'>Dispositivo</th><th style='padding: 8px; border: 1px solid #ddd;'>Fecha</th></tr>";
            foreach ($services as $s) {
                $html .= "<tr>";
                $html .= "<td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>#{$s['id']}</td>";
                $html .= "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($s['nombre'] . ' ' . $s['apellido']) . "</td>";
                $html .= "<td style='padding: 8px; border: 1px solid #ddd;'>" . htmlspecialchars($s['dispositivo']) . "</td>";
                $html .= "<td style='padding: 8px; border: 1px solid #ddd; text-align: center;'>" . date('d/m/Y H:i', strtotime($s['fecha_solicitud'])) . "</td>";
                $html .= "</tr>";
            }
            $html .= "</table>";
        }

        $html .= "<p style='margin-top: 30px; font-size: 12px; color: #777;'>Este es un mensaje automático de " . APP_NAME . ". Por favor, no respondas a este correo.</p>";
        $html .= "</div>";

        return $html;
    }

    private static function enviarSMTP(string $to, string $subject, string $htmlBody): bool {
        // Same logic as AuthController compatible with InfinityFree/PHPMailer
        $phpmailerPath = dirname(__DIR__) . '/vendor/autoload.php';
        if (file_exists($phpmailerPath)) {
            try {
                require_once $phpmailerPath;
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
                $mail->Subject    = $subject;
                $mail->Body       = $htmlBody;
                $mail->isHTML(true);
                $mail->send();
                return true;
            } catch (\Exception $e) {
                error_log("PHPMailer Error in NotificationHelper: " . $e->getMessage());
                return false;
            }
        }

        // Fallback: mail() nativo
        $subjectEncoded = '=?UTF-8?B?' . base64_encode($subject) . '?=';
        $headers = implode("\r\n", [
            'From: ' . APP_NAME . ' <' . SMTP_USER . '>',
            'Reply-To: ' . SMTP_USER,
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'X-Mailer: PHP/' . PHP_VERSION,
        ]);

        return @mail($to, $subjectEncoded, $htmlBody, $headers);
    }
}
