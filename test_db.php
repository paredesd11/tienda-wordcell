<?php
require_once __DIR__ . '/config/config.php';

echo "<h2>Probando Conexión a Base de Datos</h2>";
echo "Host: " . DB_HOST . "<br>";
echo "User: " . DB_USER . "<br>";
echo "DB Name: " . DB_NAME . "<br><br>";

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5
    ];
    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    echo "<p style='color: green;'>✅ Conexión exitosa!</p>";
    
    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    echo "<h3>Tablas encontradas:</h3><ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Error de conexión: " . $e->getMessage() . "</p>";
    echo "<strong>Nota:</strong> En InfinityFree, la conexión externa a MySQL no funciona. Este script solo funcionará si se ejecuta desde el servidor de InfinityFree.";
}
