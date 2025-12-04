<?php
/**
 * Modelo Mensaje
 */

class Mensaje {
    private $conn;
    private $table = 'mensajes';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function send($cliente_id, $maestro_id, $mensaje = null, $enviado_por = 'cliente', $adjunto = null, $tipo = 'texto') {
        $query = "INSERT INTO {$this->table} (cliente_id, maestro_id, mensaje, adjunto, tipo, enviado_por) 
                  VALUES (:cliente_id, :maestro_id, :mensaje, :adjunto, :tipo, :enviado_por)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->bindParam(':maestro_id', $maestro_id, PDO::PARAM_INT);

        if ($mensaje === null) {
            $stmt->bindValue(':mensaje', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':mensaje', $mensaje);
        }

        $adjunto = $this->sanitizePath($adjunto ?? null);
        if ($adjunto === null) {
            $stmt->bindValue(':adjunto', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindParam(':adjunto', $adjunto);
        }

        $tipo = $tipo ?? 'texto';
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':enviado_por', $enviado_por);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    private function sanitizePath($path) {
        if (empty($path)) return null;
        return basename($path) === $path ? $path : $path; // keep relative paths intact
    }

    public function getConversation($cliente_id, $maestro_id) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE (cliente_id = :cliente_id AND maestro_id = :maestro_id)
                  ORDER BY fecha_envio ASC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->bindParam(':maestro_id', $maestro_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getConversationsByCliente($cliente_id) {
        $query = "SELECT DISTINCT m.maestro_id, u.nombre_completo, u.foto_perfil, u.chapa,
                         (SELECT mensaje FROM {$this->table} 
                          WHERE (cliente_id = :cliente_id_sub AND maestro_id = m.maestro_id)
                          ORDER BY fecha_envio DESC LIMIT 1) as ultimo_mensaje,
                         (SELECT fecha_envio FROM {$this->table} 
                          WHERE (cliente_id = :cliente_id_sub2 AND maestro_id = m.maestro_id)
                          ORDER BY fecha_envio DESC LIMIT 1) as ultima_fecha,
                         (SELECT COUNT(*) FROM {$this->table} 
                          WHERE cliente_id = :cliente_id_sub3 AND maestro_id = m.maestro_id AND leido = 0 AND enviado_por = 'maestro') as no_leidos
                  FROM {$this->table} m
                  INNER JOIN maestros ma ON m.maestro_id = ma.id
                  INNER JOIN usuarios u ON ma.usuario_id = u.id
                  WHERE m.cliente_id = :cliente_id
                  ORDER BY ultima_fecha DESC";
        
        $stmt = $this->conn->prepare($query);
        // Cuando PDO emula prepares en false no permite parámetros con mismo nombre múltiples veces.
        // Usamos nombres únicos y enlazamos repetidamente el mismo valor.
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->bindParam(':cliente_id_sub', $cliente_id);
        $stmt->bindParam(':cliente_id_sub2', $cliente_id);
        $stmt->bindParam(':cliente_id_sub3', $cliente_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getConversationsByMaestro($maestro_id) {
        $query = "SELECT DISTINCT m.cliente_id, u.nombre_completo, u.foto_perfil,
                         (SELECT mensaje FROM {$this->table} 
                          WHERE (cliente_id = m.cliente_id AND maestro_id = :maestro_id_sub)
                          ORDER BY fecha_envio DESC LIMIT 1) as ultimo_mensaje,
                         (SELECT fecha_envio FROM {$this->table} 
                          WHERE (cliente_id = m.cliente_id AND maestro_id = :maestro_id_sub2)
                          ORDER BY fecha_envio DESC LIMIT 1) as ultima_fecha,
                         (SELECT COUNT(*) FROM {$this->table} 
                          WHERE cliente_id = m.cliente_id AND maestro_id = :maestro_id_sub3 AND leido = 0 AND enviado_por = 'cliente') as no_leidos
                  FROM {$this->table} m
                  INNER JOIN usuarios u ON m.cliente_id = u.id
                  WHERE m.maestro_id = :maestro_id
                  ORDER BY ultima_fecha DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maestro_id', $maestro_id);
        $stmt->bindParam(':maestro_id_sub', $maestro_id);
        $stmt->bindParam(':maestro_id_sub2', $maestro_id);
        $stmt->bindParam(':maestro_id_sub3', $maestro_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function markAsRead($cliente_id, $maestro_id, $enviado_por) {
        $query = "UPDATE {$this->table} 
                  SET leido = 1 
                  WHERE cliente_id = :cliente_id 
                  AND maestro_id = :maestro_id 
                  AND enviado_por = :enviado_por 
                  AND leido = 0";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->bindParam(':maestro_id', $maestro_id);
        $stmt->bindParam(':enviado_por', $enviado_por);
        return $stmt->execute();
    }
}

