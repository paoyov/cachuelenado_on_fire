<?php
/**
 * Modelo Maestro
 */

class Maestro {
    private $conn;
    private $table = 'maestros';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($usuario_id, $data) {
        $query = "INSERT INTO {$this->table} 
                  (usuario_id, anios_experiencia, area_preferida, descripcion, disponibilidad) 
                  VALUES 
                  (:usuario_id, :anios_experiencia, :area_preferida, :descripcion, :disponibilidad)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindParam(':anios_experiencia', $data['anios_experiencia']);
        $stmt->bindParam(':area_preferida', $data['area_preferida']);
        $stmt->bindParam(':descripcion', $data['descripcion']);
        $stmt->bindParam(':disponibilidad', $data['disponibilidad']);

        if ($stmt->execute()) {
            $maestro_id = $this->conn->lastInsertId();
            
            // Agregar especialidades
            if (isset($data['especialidades']) && is_array($data['especialidades'])) {
                $this->addEspecialidades($maestro_id, $data['especialidades']);
            }
            
            // Agregar distritos
            if (isset($data['distritos']) && is_array($data['distritos'])) {
                $this->addDistritos($maestro_id, $data['distritos']);
            }
            
            return $maestro_id;
        }
        return false;
    }

    public function getById($id) {
        $query = "SELECT m.*, u.nombre_completo, u.email, u.telefono, u.foto_perfil, u.chapa, u.dni
                  FROM {$this->table} m
                  INNER JOIN usuarios u ON m.usuario_id = u.id
                  WHERE m.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getByUsuarioId($usuario_id) {
        $query = "SELECT m.*, u.nombre_completo, u.email, u.telefono, u.foto_perfil, u.chapa, u.dni
                  FROM {$this->table} m
                  INNER JOIN usuarios u ON m.usuario_id = u.id
                  WHERE m.usuario_id = :usuario_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        $allowedFields = ['anios_experiencia', 'area_preferida', 'descripcion', 'disponibilidad', 'notificaciones_activas'];
        
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $fields[] = "{$field} = :{$field}";
                $params[":{$field}"] = $data[$field];
            }
        }

        if (empty($fields)) {
            return false;
        }

        $query = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        if ($stmt->execute($params)) {
            // Actualizar especialidades si se proporcionan
            if (isset($data['especialidades']) && is_array($data['especialidades'])) {
                $this->removeEspecialidades($id);
                $this->addEspecialidades($id, $data['especialidades']);
            }
            
            // Actualizar distritos si se proporcionan
            if (isset($data['distritos']) && is_array($data['distritos'])) {
                $this->removeDistritos($id);
                $this->addDistritos($id, $data['distritos']);
            }
            
            return true;
        }
        return false;
    }

    public function updateDisponibilidad($id, $disponibilidad) {
        $query = "UPDATE {$this->table} SET disponibilidad = :disponibilidad WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':disponibilidad', $disponibilidad);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function validate($id, $estado, $validado_por, $motivo_rechazo = null) {
        $query = "UPDATE {$this->table} 
                  SET estado_perfil = :estado, 
                      validado_por = :validado_por, 
                      fecha_validacion = NOW(),
                      motivo_rechazo = :motivo_rechazo
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':validado_por', $validado_por);
        $stmt->bindParam(':motivo_rechazo', $motivo_rechazo);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function search($filters = []) {
        $query = "SELECT DISTINCT m.*, u.nombre_completo, u.email, u.telefono, u.foto_perfil, u.chapa
                  FROM {$this->table} m
                  INNER JOIN usuarios u ON m.usuario_id = u.id
                  WHERE m.estado_perfil = 'validado' 
                  AND u.estado = 'activo'
                  AND m.pago_activo = 1
                  AND (m.fecha_expiracion_pago IS NULL OR m.fecha_expiracion_pago > NOW())";
        
        $params = [];

        if (isset($filters['especialidad_id']) && !empty($filters['especialidad_id'])) {
            $query .= " AND m.id IN (
                SELECT maestro_id FROM maestro_especialidades WHERE especialidad_id = :especialidad_id
            )";
            $params[':especialidad_id'] = $filters['especialidad_id'];
        }

        if (isset($filters['distrito_id']) && !empty($filters['distrito_id'])) {
            $query .= " AND m.id IN (
                SELECT maestro_id FROM maestro_distritos WHERE distrito_id = :distrito_id
            )";
            $params[':distrito_id'] = $filters['distrito_id'];
        }

        if (isset($filters['calificacion_minima']) && !empty($filters['calificacion_minima'])) {
            $query .= " AND m.calificacion_promedio >= :calificacion_minima";
            $params[':calificacion_minima'] = $filters['calificacion_minima'];
        }

        if (isset($filters['disponibilidad']) && !empty($filters['disponibilidad'])) {
            $query .= " AND m.disponibilidad = :disponibilidad";
            $params[':disponibilidad'] = $filters['disponibilidad'];
        }

        $query .= " ORDER BY m.calificacion_promedio DESC, m.total_trabajos DESC";
        
        if (isset($filters['limit'])) {
            $query .= " LIMIT :limit";
            $params[':limit'] = (int)$filters['limit'];
        }

        $stmt = $this->conn->prepare($query);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getPendingValidation() {
        $query = "SELECT m.*, u.nombre_completo, u.email, u.telefono, u.dni, u.foto_perfil, u.chapa
                  FROM {$this->table} m
                  INNER JOIN usuarios u ON m.usuario_id = u.id
                  WHERE m.estado_perfil = 'pendiente'
                  ORDER BY m.id DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener todos los maestros validados con pago activo y vigente
     * Solo muestra maestros que han realizado el pago y que el pago no ha caducado
     * Útil para calificaciones donde queremos mostrar solo maestros con pago vigente
     */
    public function getAllValidados() {
        $query = "SELECT DISTINCT m.*, u.nombre_completo, u.email, u.telefono, u.foto_perfil, u.chapa
                  FROM {$this->table} m
                  INNER JOIN usuarios u ON m.usuario_id = u.id
                  WHERE m.estado_perfil = 'validado' 
                  AND u.estado = 'activo'
                  AND m.pago_activo = 1
                  AND (m.fecha_expiracion_pago IS NULL OR m.fecha_expiracion_pago > NOW())
                  ORDER BY u.nombre_completo ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function incrementViews($id) {
        $query = "UPDATE {$this->table} SET total_vistas = total_vistas + 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    // Métodos para especialidades
    public function addEspecialidades($maestro_id, $especialidades) {
        $query = "INSERT INTO maestro_especialidades (maestro_id, especialidad_id) VALUES (:maestro_id, :especialidad_id)";
        $stmt = $this->conn->prepare($query);
        
        foreach ($especialidades as $especialidad_id) {
            $stmt->bindParam(':maestro_id', $maestro_id);
            $stmt->bindParam(':especialidad_id', $especialidad_id);
            $stmt->execute();
        }
    }

    public function removeEspecialidades($maestro_id) {
        $query = "DELETE FROM maestro_especialidades WHERE maestro_id = :maestro_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maestro_id', $maestro_id);
        $stmt->execute();
    }

    public function getEspecialidades($maestro_id) {
        $query = "SELECT e.* FROM especialidades e
                  INNER JOIN maestro_especialidades me ON e.id = me.especialidad_id
                  WHERE me.maestro_id = :maestro_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maestro_id', $maestro_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Métodos para distritos
    public function addDistritos($maestro_id, $distritos) {
        $query = "INSERT INTO maestro_distritos (maestro_id, distrito_id) VALUES (:maestro_id, :distrito_id)";
        $stmt = $this->conn->prepare($query);
        
        foreach ($distritos as $distrito_id) {
            $stmt->bindParam(':maestro_id', $maestro_id);
            $stmt->bindParam(':distrito_id', $distrito_id);
            $stmt->execute();
        }
    }

    public function removeDistritos($maestro_id) {
        $query = "DELETE FROM maestro_distritos WHERE maestro_id = :maestro_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maestro_id', $maestro_id);
        $stmt->execute();
    }

    public function getDistritos($maestro_id) {
        $query = "SELECT d.* FROM distritos d
                  INNER JOIN maestro_distritos md ON d.id = md.distrito_id
                  WHERE md.maestro_id = :maestro_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maestro_id', $maestro_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

