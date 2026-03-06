<?php
// Fallback para hostings compartidos como InfinityFree que requieren un archivo
// de índice en el directorio raíz para evitar el error 403 (Forbidden).
// Redirige todo el tráfico que llega aquí al index real dentro de la carpeta public.

require_once __DIR__ . '/public/index.php';
