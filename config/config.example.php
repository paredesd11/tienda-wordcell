<?php
// =============================================
//  config.php — Detección automática de entorno
//  XAMPP (localhost) o InfinityFree (producción)
// =============================================

$isLocal = (
    isset($_SERVER['SERVER_NAME']) && in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']) ||
    isset($_SERVER['HTTP_HOST'])   && in_array(explode(':', $_SERVER['HTTP_HOST'])[0], ['localhost', '127.0.0.1'])
);

if ($isLocal) {
    // ── ENTORNO LOCAL (XAMPP) ─────────────────────
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'tienda_mvc');
    define('URL_BASE', 'http://localhost/Tienda/');

} else {
    // ── ENTORNO PRODUCCIÓN (InfinityFree) ─────────
    define('DB_HOST', 'sql307.infinityfree.com');
    define('DB_USER', 'if0_41073337');
    define('DB_PASS', '6hOywZhEBmvYGM');
    define('DB_NAME', 'if0_41073337_tienda_mvc');
    define('URL_BASE', 'https://wordcell.kesug.com'); // AÑADIDO HTTPS:// Y LA BARRA FINAL /
}

// ── Configuración general ─────────────────────────
define('APP_NAME', 'WordCell');

// ── SMTP (Gmail) ──────────────────────────────────
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'paredesd1104@gmail.com');
define('SMTP_PASS', 'nnlr dorv icad udbw');
define('SMTP_PORT', 587);

// ── Zona Horaria ──────────────────────────────────
date_default_timezone_set('America/Guayaquil');
