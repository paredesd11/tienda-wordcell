<?php
class Router {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        // ... Start session properly
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function dispatch($url) {
        $url = $this->parseUrl($url);

        if (isset($url[0]) && file_exists('../controllers/' . ucfirst($url[0]) . 'Controller.php')) {
            $this->controller = ucfirst($url[0]) . 'Controller';
            unset($url[0]);
        }

        require_once '../controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    protected function parseUrl($url) {
        if ($url) {
            return explode('/', filter_var(rtrim($url, '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
