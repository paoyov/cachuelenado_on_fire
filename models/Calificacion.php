<?php
/**
 * Modelo Calificacion
 */

class Calificacion {
    private $conn;
    private $table = 'calificaciones';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (cliente_id, maestro_id, trabajo_id, puntualidad, calidad, trato, limpieza, comentario) 
                  VALUES 
                  (:cliente_id, :maestro_id, :trabajo_id, :puntualidad, :calidad, :trato, :limpieza, :comentario)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cliente_id', $data['cliente_id']);
        $stmt->bindParam(':maestro_id', $data['maestro_id']);
        $stmt->bindParam(':trabajo_id', $data['trabajo_id']);
        $stmt->bindParam(':puntualidad', $data['puntualidad']);
        $stmt->bindParam(':calidad', $data['calidad']);
        $stmt->bindParam(':trato', $data['trato']);
        $stmt->bindParam(':limpieza', $data['limpieza']);
        $stmt->bindParam(':comentario', $data['comentario']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function getByMaestro($maestro_id, $limit = null) {
        $query = "SELECT c.*, u.nombre_completo, u.foto_perfil, t.titulo as trabajo_titulo
                  FROM {$this->table} c
                  INNER JOIN usuarios u ON c.cliente_id = u.id
                  LEFT JOIN trabajos t ON c.trabajo_id = t.id
                  WHERE c.maestro_id = :maestro_id
                  ORDER BY c.fecha_calificacion DESC";
        
        if ($limit) {
            $query .= " LIMIT :limit";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maestro_id', $maestro_id);
        if ($limit) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByCliente($cliente_id) {
        $query = "SELECT c.*, m.id as maestro_id, u.nombre_completo as maestro_nombre, u.foto_perfil as maestro_foto, es.nombre as especialidad
                  FROM {$this->table} c
                  INNER JOIN maestros m ON c.maestro_id = m.id
                  INNER JOIN usuarios u ON m.usuario_id = u.id
                  LEFT JOIN maestro_especialidades me ON m.id = me.maestro_id
                  LEFT JOIN especialidades es ON me.especialidad_id = es.id
                  WHERE c.cliente_id = :cliente_id
                  GROUP BY c.id
                  ORDER BY c.fecha_calificacion DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByTrabajo($trabajo_id) {
        $query = "SELECT * FROM {$this->table} WHERE trabajo_id = :trabajo_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':trabajo_id', $trabajo_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function hasRated($cliente_id, $trabajo_id) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} 
                  WHERE cliente_id = :cliente_id AND trabajo_id = :trabajo_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->bindParam(':trabajo_id', $trabajo_id);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] > 0;
    }

    public function getRecent($limit = 6) {
        $query = "SELECT c.*, 
                         uc.nombre_completo as cliente_nombre, 
                         uc.foto_perfil as cliente_foto,
                         um.nombre_completo as maestro_nombre
                  FROM {$this->table} c
                  INNER JOIN usuarios uc ON c.cliente_id = uc.id
                  INNER JOIN maestros m ON c.maestro_id = m.id
                  INNER JOIN usuarios um ON m.usuario_id = um.id
                  ORDER BY c.fecha_calificacion DESC
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

