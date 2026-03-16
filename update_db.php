<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';

echo "<h1>Database Schema Synchronizer</h1>";

try {
    $db = new Database();
    $conn = $db->getConnection();
    echo "✅ Conectado a la base de datos.<br><br>";

    // 1. Crear tabla ofertas_servicios si no existe
    echo "Creando tabla 'ofertas_servicios'... ";
    $sql_ofertas = "CREATE TABLE IF NOT EXISTS ofertas_servicios (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre VARCHAR(100) NOT NULL,
        descuento_porcentaje DECIMAL(5,2) NOT NULL,
        condicion ENUM('TODOS', 'PRIMERA_VEZ') DEFAULT 'TODOS',
        fecha_inicio DATE NOT NULL,
        fecha_fin DATE NOT NULL,
        activa TINYINT(1) DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    $conn->exec($sql_ofertas);
    echo "✅ OK.<br>";

    // 2. Añadir columnas a servicio_tecnico
    echo "Actualizando tabla 'servicio_tecnico'... <br>";
    $cols_servicio = [
        "precio_base DECIMAL(10,2) NULL",
        "descuento_porcentaje DECIMAL(5,2) NULL",
        "nombre_oferta VARCHAR(100) NULL",
        "tipo_entrega VARCHAR(50) DEFAULT 'Entrega fisica'",
        "ubicacion_domicilio TEXT NULL",
        "fecha_domicilio DATE NULL",
        "hora_domicilio TIME NULL",
        "sucursal_local VARCHAR(100) NULL",
        "metodo_envio VARCHAR(100) NULL",
        "fecha_local DATE NULL",
        "hora_local TIME NULL"
    ];

    foreach ($cols_servicio as $col_def) {
        $col_name = explode(' ', $col_def)[0];
        try {
            $conn->exec("ALTER TABLE servicio_tecnico ADD COLUMN $col_def;");
            echo "&nbsp;&nbsp;&nbsp; ✅ Columna '$col_name' añadida.<br>";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                echo "&nbsp;&nbsp;&nbsp; ℹ️ Columna '$col_name' ya existe.<br>";
            } else {
                echo "&nbsp;&nbsp;&nbsp; ❌ Error en '$col_name': " . $e->getMessage() . "<br>";
            }
        }
    }

    // 3. Añadir columnas a noticias
    echo "<br>Actualizando tabla 'noticias'... <br>";
    $cols_noticias = [
        "oferta_servicio_id INT NULL",
        "oferta_producto_id INT NULL",
        "fecha_fin DATE NULL"
    ];

    foreach ($cols_noticias as $col_def) {
        $col_name = explode(' ', $col_def)[0];
        try {
            $conn->exec("ALTER TABLE noticias ADD COLUMN $col_def;");
            echo "&nbsp;&nbsp;&nbsp; ✅ Columna '$col_name' añadida.<br>";
        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
                echo "&nbsp;&nbsp;&nbsp; ℹ️ Columna '$col_name' ya existe.<br>";
            } else {
                echo "&nbsp;&nbsp;&nbsp; ❌ Error en '$col_name': " . $e->getMessage() . "<br>";
            }
        }
    }

    echo "<br><strong>🎉 Sincronización completada con éxito.</strong>";

} catch (Throwable $t) {
    echo "<br><span style='color:red;'>❌ Error crítico: " . $t->getMessage() . "</span>";
}
