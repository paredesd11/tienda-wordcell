<?php
// Script temporal para generar hashes de contraseñas.
// Borra este archivo después de usarlo.

$password_a_hashear = "TuNuevaContraseña123!"; // Cambia esto por la contraseña que quieras

$hash = hash('sha256', $password_a_hashear);

echo "<h1>Generador de Hash SHA-256 para Contraseñas</h1>";
echo "<p>Contraseña original: <strong>" . htmlspecialchars($password_a_hashear) . "</strong></p>";
echo "<p>Hash SHA-256 generado: <strong>" . $hash . "</strong></p>";
echo "<p>Copia este hash y pégalo en tu consulta SQL o en phpMyAdmin.</p>";
?>
