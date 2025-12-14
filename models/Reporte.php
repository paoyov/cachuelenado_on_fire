<?php
/**
 * Modelo Reporte
 */

class Reporte {
    private $conn;
    private $table = 'reportes';

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Crear un nuevo reporte
     */
    public function create($data) {
        $query = "INSERT INTO {$this->table} 
                  (reportado_por, reportado_a, tipo, motivo, estado) 
                  VALUES 
                  (:reportado_por, :reportado_a, :tipo, :motivo, :estado)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':reportado_por', $data['reportado_por']);
        $stmt->bindParam(':reportado_a', $data['reportado_a']);
        $stmt->bindParam(':tipo', $data['tipo']);
        $stmt->bindParam(':motivo', $data['motivo']);
        $stmt->bindParam(':estado', $data['estado']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    /**
     * Obtener todos los reportes con información de usuarios
     */
    public function getAll() {
        $query = "SELECT r.*,
                         u_reporter.nombre_completo as reportado_por_nombre,
                         u_reporter.email as reportado_por_email,
                         u_reported.nombre_completo as reportado_a_nombre,
                         u_reported.email as reportado_a_email,
                         u_reported.foto_perfil as reportado_a_foto,
                         u_reported.fecha_registro as reportado_a_fecha_registro
                  FROM {$this->table} r
                  INNER JOIN usuarios u_reporter ON r.reportado_por = u_reporter.id
                  INNER JOIN usuarios u_reported ON r.reportado_a = u_reported.id
                  ORDER BY r.fecha_reporte DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener reportes de maestros rechazados (tipo 'usuario')
     */
    public function getMaestrosRechazados() {
        $query = "SELECT r.*,
                         u_reporter.nombre_completo as validado_por_nombre,
                         u_reported.id as usuario_id,
                         u_reported.nombre_completo,
                         u_reported.email,
                         u_reported.fecha_registro,
                         u_reported.foto_perfil,
                         m.id as maestro_id,
                         m.estado_perfil,
                         m.fecha_validacion
                  FROM {$this->table} r
                  INNER JOIN usuarios u_reporter ON r.reportado_por = u_reporter.id
                  INNER JOIN usuarios u_reported ON r.reportado_a = u_reported.id
                  LEFT JOIN maestros m ON m.usuario_id = u_reported.id
                  WHERE r.tipo = 'usuario' AND m.estado_perfil = 'rechazado'
                  ORDER BY r.fecha_reporte DESC, u_reported.fecha_registro DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener reporte por ID
     */
    public function getById($id) {
        $query = "SELECT r.*,
                         u_reporter.nombre_completo as reportado_por_nombre,
                         u_reported.nombre_completo as reportado_a_nombre
                  FROM {$this->table} r
                  INNER JOIN usuarios u_reporter ON r.reportado_por = u_reporter.id
                  INNER JOIN usuarios u_reported ON r.reportado_a = u_reported.id
                  WHERE r.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Actualizar estado del reporte
     */
    public function updateEstado($id, $estado) {
        $query = "UPDATE {$this->table} SET estado = :estado WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':estado', $estado);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    /**
     * Contar reportes por mes y año
     */
    public function countByMonth($month, $year) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} 
                  WHERE MONTH(fecha_reporte) = :month AND YEAR(fecha_reporte) = :year";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':month', $month);
        $stmt->bindParam(':year', $year);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'] ?? 0;
    }
}

