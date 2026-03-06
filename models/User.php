<?php
class User {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function register($nombre, $apellido, $correo, $password, $telefono = null, $direccion = null, $cedula = null) {
        $hash = hash('sha256', $password);
        $sql = "INSERT INTO usuarios (nombre, apellido, cedula, correo, password_hash, telefono, direccion) VALUES (:nombre, :apellido, :correo, :hash, :telefono, :direccion, :cedula)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':hash', $hash);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        
        try {
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    public function findByEmail($correo) {
        $sql = "SELECT * FROM usuarios WHERE correo = :correo LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':correo', $correo);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findByCedula($cedula) {
        $sql = "SELECT * FROM usuarios WHERE cedula = :cedula LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function findById($id) {
        $sql = "SELECT * FROM usuarios WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateVerificationCode($userId, $code) {
        $expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        $sql = "UPDATE usuarios SET codigo_verificacion = :codigo, codigo_expiracion = :expiracion WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':codigo', $code);
        $stmt->bindParam(':expiracion', $expiry);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }

    public function clearVerificationCode($userId) {
        $sql = "UPDATE usuarios SET codigo_verificacion = NULL, codigo_expiracion = NULL WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $userId);
        return $stmt->execute();
    }

    public function updateProfile($id, $nombre, $apellido, $telefono, $direccion, $cedula = null) {
        $sql = "UPDATE usuarios 
                SET nombre = :nombre, 
                    apellido = :apellido, 
                    telefono = :telefono, 
                    direccion = :direccion,
                    cedula = CASE WHEN cedula IS NULL OR cedula = '' THEN :cedula ELSE cedula END
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':telefono', $telefono);
        $stmt->bindParam(':direccion', $direccion);
        $stmt->bindParam(':cedula', $cedula);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function updatePassword($id, $newPassword) {
        $hash = hash('sha256', $newPassword);
        $sql = "UPDATE usuarios SET password_hash = :hash WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':hash', $hash);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
