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
    // ⚠️ CAMBIAR: Datos de la base de datos de producción proporcionados por el cliente o el hosting del cliente.
    define('DB_HOST', 'sql213.infinityfree.com');
    define('DB_USER', 'if0_41339156');
    define('DB_PASS', 'Danny110404');
    define('DB_NAME', 'if0_41339156_tienda_mvc');
    // ⚠️ CAMBIAR: Dominio oficial del cliente
    define('URL_BASE', 'http://wordcell-ec.wuaze.com/');
}

// ── Configuración general ─────────────────────────
// ⚠️ CAMBIAR: Nombre oficial de la tienda/empresa del cliente
define('APP_NAME', 'WordCell');

// ── SMTP (Gmail) ──────────────────────────────────
// ⚠️ CAMBIAR: Estos datos son para las alertas, verificación en 2 pasos y contacto. 
// Debes colocar el correo oficial del cliente y su contraseña de aplicación (App Password).
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'paredesd1104@gmail.com'); // Correo del cliente
define('SMTP_PASS', 'nnlr dorv icad udbw');    // Contraseña de aplicación del cliente
define('SMTP_PORT', 587);

// ── Zona Horaria ──────────────────────────────────
date_default_timezone_set('America/Guayaquil');
