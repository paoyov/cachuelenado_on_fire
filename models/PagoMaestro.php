<?php
/**
 * Modelo PagoMaestro
 * Maneja los pagos realizados por maestros para validación
 */

class PagoMaestro {
    private $conn;
    private $table = 'pagos_maestros';

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Crear un nuevo pago
     */
    public function create($maestro_id, $usuario_id, $data = []) {
        // Calcular fecha de expiración (24 horas desde ahora)
        $fecha_expiracion = date('Y-m-d H:i:s', strtotime('+24 hours'));
        
        $query = "INSERT INTO {$this->table} 
                  (maestro_id, usuario_id, monto, metodo_pago, numero_comprobante, comprobante_imagen, estado, fecha_expiracion) 
                  VALUES 
                  (:maestro_id, :usuario_id, :monto, :metodo_pago, :numero_comprobante, :comprobante_imagen, :estado, :fecha_expiracion)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maestro_id', $maestro_id);
        $stmt->bindParam(':usuario_id', $usuario_id);
        $stmt->bindValue(':monto', $data['monto'] ?? 3.00);
        $stmt->bindValue(':metodo_pago', $data['metodo_pago'] ?? 'yape');
        $stmt->bindValue(':numero_comprobante', $data['numero_comprobante'] ?? null);
        $stmt->bindValue(':comprobante_imagen', $data['comprobante_imagen'] ?? null);
        $stmt->bindValue(':estado', $data['estado'] ?? 'pendiente');
        $stmt->bindParam(':fecha_expiracion', $fecha_expiracion);

        if ($stmt->execute()) {
            $pago_id = $this->conn->lastInsertId();
            
            // Actualizar estado de pago en la tabla maestros
            $this->updateMaestroPagoStatus($maestro_id, true, $fecha_expiracion);
            
            return $pago_id;
        }
        return false;
    }

    /**
     * Obtener pago por ID
     */
    public function getById($id) {
        $query = "SELECT p.*, m.usuario_id, u.nombre_completo, u.email, u.telefono
                  FROM {$this->table} p
                  INNER JOIN maestros m ON p.maestro_id = m.id
                  INNER JOIN usuarios u ON p.usuario_id = u.id
                  WHERE p.id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Obtener pagos por maestro
     */
    public function getByMaestroId($maestro_id) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE maestro_id = :maestro_id 
                  ORDER BY fecha_pago DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maestro_id', $maestro_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener pago activo del maestro
     */
    public function getPagoActivo($maestro_id) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE maestro_id = :maestro_id 
                  AND estado = 'verificado' 
                  AND fecha_expiracion > NOW()
                  ORDER BY fecha_pago DESC 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maestro_id', $maestro_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Obtener pago pendiente o verificado del maestro (para validación de perfil)
     */
    public function getPagoParaValidar($maestro_id) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE maestro_id = :maestro_id 
                  AND estado IN ('pendiente', 'verificado')
                  ORDER BY fecha_pago DESC 
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maestro_id', $maestro_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * Obtener pagos pendientes de verificación
     */
    public function getPendientes() {
        $query = "SELECT p.*, m.usuario_id, u.nombre_completo, u.email, u.telefono
                  FROM {$this->table} p
                  INNER JOIN maestros m ON p.maestro_id = m.id
                  INNER JOIN usuarios u ON p.usuario_id = u.id
                  WHERE p.estado = 'pendiente'
                  ORDER BY p.fecha_pago DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Verificar pago (cambiar estado a verificado)
     */
    public function verificar($pago_id, $admin_id, $observaciones = null) {
        $query = "UPDATE {$this->table} 
                  SET estado = 'verificado', 
                      fecha_verificacion = NOW(), 
                      verificado_por = :admin_id,
                      observaciones = :observaciones
                  WHERE id = :pago_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pago_id', $pago_id);
        $stmt->bindParam(':admin_id', $admin_id);
        $stmt->bindValue(':observaciones', $observaciones);
        
        if ($stmt->execute()) {
            // Obtener información del pago
            $pago = $this->getById($pago_id);
            
            // Actualizar estado de pago en la tabla maestros
            $this->updateMaestroPagoStatus($pago['maestro_id'], true, $pago['fecha_expiracion']);
            
            return true;
        }
        return false;
    }

    /**
     * Rechazar pago
     */
    public function rechazar($pago_id, $admin_id, $observaciones) {
        $query = "UPDATE {$this->table} 
                  SET estado = 'rechazado', 
                      fecha_verificacion = NOW(), 
                      verificado_por = :admin_id,
                      observaciones = :observaciones
                  WHERE id = :pago_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':pago_id', $pago_id);
        $stmt->bindParam(':admin_id', $admin_id);
        $stmt->bindParam(':observaciones', $observaciones);
        
        return $stmt->execute();
    }

    /**
     * Actualizar estado de pago en la tabla maestros
     */
    private function updateMaestroPagoStatus($maestro_id, $activo, $fecha_expiracion) {
        $query = "UPDATE maestros 
                  SET pago_activo = :activo, 
                      fecha_expiracion_pago = :fecha_expiracion
                  WHERE id = :maestro_id";
        
        $stmt = $this->conn->prepare($query);
        $activo_int = $activo ? 1 : 0;
        $stmt->bindParam(':activo', $activo_int, PDO::PARAM_INT);
        $stmt->bindParam(':fecha_expiracion', $fecha_expiracion);
        $stmt->bindParam(':maestro_id', $maestro_id);
        
        return $stmt->execute();
    }

    /**
     * Verificar y actualizar pagos expirados
     */
    public function verificarPagosExpirados() {
        $query = "UPDATE maestros 
                  SET pago_activo = 0, 
                      fecha_expiracion_pago = NULL
                  WHERE pago_activo = 1 
                  AND fecha_expiracion_pago < NOW()";
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }

    /**
     * Obtener maestros con pago próximo a expirar (menos de 6 horas)
     */
    public function getPagosPorExpirar() {
        $query = "SELECT m.*, u.nombre_completo, u.email, p.fecha_expiracion
                  FROM maestros m
                  INNER JOIN usuarios u ON m.usuario_id = u.id
                  INNER JOIN pagos_maestros p ON m.id = p.maestro_id
                  WHERE m.pago_activo = 1 
                  AND p.estado = 'verificado'
                  AND p.fecha_expiracion BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 6 HOUR)
                  ORDER BY p.fecha_expiracion ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Obtener maestros con pago expirado
     */
    public function getPagosExpirados() {
        $query = "SELECT m.*, u.nombre_completo, u.email, p.fecha_expiracion
                  FROM maestros m
                  INNER JOIN usuarios u ON m.usuario_id = u.id
                  INNER JOIN pagos_maestros p ON m.id = p.maestro_id
                  WHERE m.pago_activo = 1 
                  AND p.estado = 'verificado'
                  AND p.fecha_expiracion < NOW()";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
