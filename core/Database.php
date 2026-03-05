<?php
/**
 * Database — PDO conn compatible con InfinityFree (LiteSpeed + MySQL compartido)
 * - Sin PDO::ATTR_PERSISTENT (causa issues en shared hosting)
 * - charset=utf8mb4 en DSN
 * - Manejo de error silencioso para producción
 */
class Database {
    private $host   = DB_HOST;
    private $user   = DB_USER;
    private $pass   = DB_PASS;
    private $dbname = DB_NAME;
    private $dbh;

    public function __construct() {
        $dsn = 'mysql:host=' . $this->host
             . ';dbname='    . $this->dbname
             . ';charset=utf8mb4';

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci",
        ];

        try {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            // No exponer detalles en producción
            error_log("DB Connection Error: " . $e->getMessage());
            die("Error de conexión. Por favor intenta más tarde.");
        }
    }

    public function getConnection(): PDO {
        return $this->dbh;
    }
}
