<?php
/**
 * Modelo Notificacion
 */

class Notificacion {
    private $conn;
    private $table = 'notificaciones';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($usuario_id, $tipo, $titulo, $mensaje) {
        $query = "INSERT INTO {$this->table} (usuario_id, tipo, titulo, mensaje) 
                  VALUES (:usuario_id, :tipo, :titulo, :mensaje)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':mensaje', $mensaje);

        return $stmt->execute();
    }

    public function getByUsuario($usuario_id, $limit = null, $no_leidas = false) {
        $query = "SELECT * FROM {$this->table} WHERE usuario_id = :usuario_id";
        
        if ($no_leidas) {
            $query .= " AND leida = 0";
        }
        
        $query .= " ORDER BY fecha_creacion DESC";
        
        if ($limit) {
            $query .= " LIMIT :limit";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function markAsRead($id, $usuario_id) {
        $query = "UPDATE {$this->table} SET leida = 1 WHERE id = :id AND usuario_id = :usuario_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        return $stmt->execute();
    }

    public function markAllAsRead($usuario_id) {
        $query = "UPDATE {$this->table} SET leida = 1 WHERE usuario_id = :usuario_id AND leida = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        return $stmt->execute();
    }

    public function countUnread($usuario_id) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} 
                  WHERE usuario_id = :usuario_id AND leida = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
}

