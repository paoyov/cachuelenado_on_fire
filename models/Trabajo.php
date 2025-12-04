<?php
/**
 * Modelo Trabajo
 */

class Trabajo {
    private $conn;
    private $table = 'trabajos';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (cliente_id, maestro_id, titulo, descripcion, fecha_inicio, estado) 
                  VALUES 
                  (:cliente_id, :maestro_id, :titulo, :descripcion, :fecha_inicio, 'pendiente')";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cliente_id', $data['cliente_id']);
        $stmt->bindParam(':maestro_id', $data['maestro_id']);
        $stmt->bindParam(':titulo', $data['titulo']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':fecha_inicio', $data['fecha_inicio']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function getById($id) {
        $query = "SELECT t.*, m.usuario_id as maestro_usuario_id, u.nombre_completo as maestro_nombre 
                  FROM {$this->table} t
                  INNER JOIN maestros m ON t.maestro_id = m.id
                  INNER JOIN usuarios u ON m.usuario_id = u.id
                  WHERE t.id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getByCliente($cliente_id) {
        $query = "SELECT t.*, u.nombre_completo as maestro_nombre, u.foto_perfil as maestro_foto, m.id as maestro_id
                  FROM {$this->table} t
                  INNER JOIN maestros m ON t.maestro_id = m.id
                  INNER JOIN usuarios u ON m.usuario_id = u.id
                  WHERE t.cliente_id = :cliente_id
                  ORDER BY t.fecha_creacion DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateEstado($id, $estado) {
        $query = "UPDATE {$this->table} SET estado = :estado";
        if ($estado === 'completado') {
            $query .= ", fecha_fin = NOW()";
        }
        $query .= " WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
