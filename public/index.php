<?php
echo "<!-- DEBUG: Entering public/index.php -->";
// Punto de entrada único
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Router.php';

echo "<!-- DEBUG: Initializing Router -->";
// Inicializar y rutear
$router = new Router();
echo "<!-- DEBUG: Dispatching URL: " . ($_GET['url'] ?? 'empty') . " -->";
$router->dispatch($_GET['url'] ?? '');
echo "<!-- DEBUG: Finished public/index.php -->";
