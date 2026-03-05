<?php
class Controller {
    public function model($model) {
        require_once '../models/' . $model . '.php';
        return new $model();
    }

    public function view($view, $data = []) {
        if (file_exists('../views/' . $view . '.php')) {
            // Automatically fetch global footer data if not provided
            try {
                require_once '../core/Database.php';
                $db = (new Database())->getConnection();
                
                if (!isset($data['redesSociales'])) {
                    $data['redesSociales'] = $db->query("SELECT * FROM redes_sociales ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);
                }
                if (!isset($data['ubicaciones'])) {
                    $data['ubicaciones'] = $db->query("SELECT * FROM ubicacion_local")->fetchAll(PDO::FETCH_ASSOC);
                }
            } catch (Exception $e) {
                if (!isset($data['redesSociales'])) $data['redesSociales'] = [];
                if (!isset($data['ubicaciones'])) $data['ubicaciones'] = [];
            }

            // Extract variables to be accessible in view scope
            if(!empty($data)) extract($data);
            
            require_once '../views/layout/header.php';
            require_once '../views/' . $view . '.php';
            require_once '../views/layout/footer.php';
        } else {
            die("La vista no existe.");
        }
    }

    public function viewAdmin($view, $data = []) {
        if (file_exists('../views/' . $view . '.php')) {
            if(!empty($data)) extract($data);
            
            require_once '../views/admin/layout_header.php';
            require_once '../views/' . $view . '.php';
            require_once '../views/admin/layout_footer.php';
        } else {
            die("La vista admin no existe.");
        }
    }

    public function viewAuth($view, $data = []) {
        if (file_exists('../views/' . $view . '.php')) {
            if(!empty($data)) extract($data);
            
            // Layout simplified for auth
            require_once '../views/auth/layout_header.php';
            require_once '../views/' . $view . '.php';
            require_once '../views/auth/layout_footer.php';
        } else {
            die("La vista auth no existe.");
        }
    }

    protected function redirect($url) {
        header("Location: " . URL_BASE . $url);
        exit;
    }

    protected function sendWhatsApp($to, $message) {
        try {
            require_once '../core/Database.php';
            $db = (new Database())->getConnection();
            $config = $db->query("SELECT * FROM configuracion_whatsapp_api LIMIT 1")->fetch(PDO::FETCH_ASSOC);

            if (!$config || empty($config['instance_id']) || empty($config['mensaje_token'])) {
                error_log("WhatsApp API no configurada.");
                return false;
            }

            $params = array(
                'token' => $config['mensaje_token'],
                'to' => $to,
                'body' => $message
            );

            $instance_id = $config['instance_id'];
            if (!str_starts_with($instance_id, 'instance')) {
                $instance_id = 'instance' . $instance_id;
            }
            $url = rtrim($config['api_url'], '/') . '/' . $instance_id . '/messages/chat';
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => http_build_query($params),
                CURLOPT_HTTPHEADER => array(
                    "content-type: application/x-www-form-urlencoded"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                error_log("WhatsApp Error: " . $err);
                return false;
            } else {
                return true;
            }
        } catch (Exception $e) {
            error_log("WhatsApp Exception: " . $e->getMessage());
            return false;
        }
    }
}
