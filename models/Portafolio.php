<?php
/**
 * Modelo Portafolio
 */

class Portafolio {
    private $conn;
    private $table = 'portafolio';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function add($maestro_id, $data) {
        // Verificar límite de 10 imágenes
        $count = $this->countByMaestro($maestro_id);
        if ($count >= 10) {
            return ['success' => false, 'message' => 'El portafolio no puede tener más de 10 imágenes'];
        }

        $query = "INSERT INTO {$this->table} (maestro_id, titulo, descripcion, imagen, orden) 
                  VALUES (:maestro_id, :titulo, :descripcion, :imagen, :orden)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maestro_id', $maestro_id);
        $stmt->bindParam(':titulo', $data['titulo']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':imagen', $data['imagen']);
        $stmt->bindParam(':orden', $data['orden']);

        if ($stmt->execute()) {
            return ['success' => true, 'id' => $this->conn->lastInsertId()];
        }
        return ['success' => false, 'message' => 'Error al agregar imagen al portafolio'];
    }

    public function getByMaestro($maestro_id) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE maestro_id = :maestro_id 
                  ORDER BY orden ASC, fecha_subida DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maestro_id', $maestro_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function delete($id, $maestro_id) {
        // Obtener información de la imagen antes de eliminar
        $item = $this->getById($id);
        if (!$item || $item['maestro_id'] != $maestro_id) {
            return false;
        }

        $query = "DELETE FROM {$this->table} WHERE id = :id AND maestro_id = :maestro_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':maestro_id', $maestro_id);
        
        if ($stmt->execute()) {
            // Eliminar archivo físico
            if (file_exists(UPLOAD_PATH . $item['imagen'])) {
                unlink(UPLOAD_PATH . $item['imagen']);
            }
            return true;
        }
        return false;
    }

    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    private function countByMaestro($maestro_id) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} WHERE maestro_id = :maestro_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maestro_id', $maestro_id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
}

