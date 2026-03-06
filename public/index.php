<?php
// Punto de entrada único
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Router.php';

// Inicializar y rutear
$router = new Router();
$router->dispatch($_GET['url'] ?? '');
