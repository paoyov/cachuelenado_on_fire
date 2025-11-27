<?php
/**
 * Modelo DocumentoMaestro
 */

class DocumentoMaestro {
    private $conn;
    private $table = 'documentos_maestro';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function add($maestro_id, $tipo_documento, $nombre_archivo, $ruta_archivo) {
        $query = "INSERT INTO {$this->table} (maestro_id, tipo_documento, nombre_archivo, ruta_archivo) 
                  VALUES (:maestro_id, :tipo_documento, :nombre_archivo, :ruta_archivo)";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maestro_id', $maestro_id);
        $stmt->bindParam(':tipo_documento', $tipo_documento);
        $stmt->bindParam(':nombre_archivo', $nombre_archivo);
        $stmt->bindParam(':ruta_archivo', $ruta_archivo);

        return $stmt->execute();
    }

    public function getByMaestro($maestro_id) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE maestro_id = :maestro_id 
                  ORDER BY tipo_documento, fecha_subida DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maestro_id', $maestro_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getByMaestroAndType($maestro_id, $tipo_documento) {
        $query = "SELECT * FROM {$this->table} 
                  WHERE maestro_id = :maestro_id AND tipo_documento = :tipo_documento
                  ORDER BY fecha_subida DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':maestro_id', $maestro_id);
        $stmt->bindParam(':tipo_documento', $tipo_documento);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function delete($id, $maestro_id) {
        // Obtener información del documento antes de eliminar
        $doc = $this->getById($id);
        if (!$doc || $doc['maestro_id'] != $maestro_id) {
            return false;
        }

        $query = "DELETE FROM {$this->table} WHERE id = :id AND maestro_id = :maestro_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':maestro_id', $maestro_id);
        
        if ($stmt->execute()) {
            // Eliminar archivo físico
            if (file_exists(UPLOAD_PATH . $doc['ruta_archivo'])) {
                unlink(UPLOAD_PATH . $doc['ruta_archivo']);
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
}

