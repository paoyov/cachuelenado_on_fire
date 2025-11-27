<?php
/**
 * Modelo Busqueda
 */

class Busqueda {
    private $conn;
    private $table = 'busquedas';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($data) {
        $query = "INSERT INTO {$this->table} 
                  (cliente_id, especialidad_id, distrito_id, calificacion_minima, disponibilidad) 
                  VALUES 
                  (:cliente_id, :especialidad_id, :distrito_id, :calificacion_minima, :disponibilidad)";
        
        $stmt = $this->conn->prepare($query);
        // cliente_id (int)
        $stmt->bindValue(':cliente_id', isset($data['cliente_id']) ? (int)$data['cliente_id'] : null, PDO::PARAM_INT);

        // especialidad_id (int or null)
        if (isset($data['especialidad_id']) && $data['especialidad_id'] !== null) {
            $stmt->bindValue(':especialidad_id', (int)$data['especialidad_id'], PDO::PARAM_INT);
        } else {
            $stmt->bindValue(':especialidad_id', null, PDO::PARAM_NULL);
        }

        // distrito_id (int or null)
        if (isset($data['distrito_id']) && $data['distrito_id'] !== null) {
            $stmt->bindValue(':distrito_id', (int)$data['distrito_id'], PDO::PARAM_INT);
        } else {
            $stmt->bindValue(':distrito_id', null, PDO::PARAM_NULL);
        }

        // calificacion_minima (float or null)
        if (isset($data['calificacion_minima']) && $data['calificacion_minima'] !== null) {
            $stmt->bindValue(':calificacion_minima', $data['calificacion_minima']);
        } else {
            $stmt->bindValue(':calificacion_minima', null, PDO::PARAM_NULL);
        }

        // disponibilidad (string or null)
        if (isset($data['disponibilidad']) && $data['disponibilidad'] !== null) {
            $stmt->bindValue(':disponibilidad', $data['disponibilidad']);
        } else {
            $stmt->bindValue(':disponibilidad', null, PDO::PARAM_NULL);
        }

        return $stmt->execute();
    }

    public function getTotalSearches() {
        $query = "SELECT COUNT(*) as total FROM {$this->table}";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

    public function getSearchesByMonth($month, $year) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} 
                  WHERE MONTH(fecha_busqueda) = :month AND YEAR(fecha_busqueda) = :year";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':month', $month);
        $stmt->bindParam(':year', $year);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
}

