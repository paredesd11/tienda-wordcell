<?php
// Punto de entrada único
require_once '../config/config.php';
require_once '../core/Database.php';
require_once '../core/Controller.php';
require_once '../core/Router.php';

// Inicializar y rutear
$router = new Router();
$router->dispatch($_GET['url'] ?? '');
