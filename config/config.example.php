<?php
// =============================================
//  ARCHIVO DE EJEMPLO — copia esto como config.php
//  y completa tus propios valores
// =============================================

// Configuración de la base de datos
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'TU_CONTRASEÑA_BD');
define('DB_NAME', 'tienda_mvc');

// Configuración de la aplicación
define('URL_BASE', 'http://localhost/Tienda/');
define('APP_NAME', 'WordCell');

// Configuración SMTP para verificación por correo
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_USER', 'tu_correo@gmail.com');
define('SMTP_PASS', 'tu_contraseña_de_aplicacion');
define('SMTP_PORT', 587);

// Configuración de Zona Horaria
date_default_timezone_set('America/Guayaquil');
