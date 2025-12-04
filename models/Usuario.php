<?php
/**
 * Modelo Usuario
 */

class Usuario {
    private $conn;
    private $table = 'usuarios';

    public $id;
    public $tipo_usuario;
    public $nombre_completo;
    public $email;
    public $password;
    public $telefono;
    public $dni;
    public $foto_perfil;
    public $chapa;
    public $estado;
    public $fecha_registro;
    public $ultimo_acceso;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($email, $password) {
        $query = "SELECT id, tipo_usuario, nombre_completo, email, password, estado, foto_perfil 
                  FROM {$this->table} 
                  WHERE email = :email AND estado = 'activo'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            if (password_verify($password, $row['password'])) {
                // Actualizar último acceso
                $this->updateLastAccess($row['id']);
                return $row;
            }
        }
        return false;
    }

    public function register($data) {
        $query = "INSERT INTO {$this->table} 
                  (tipo_usuario, nombre_completo, email, password, telefono, dni, chapa, foto_perfil) 
                  VALUES 
                  (:tipo_usuario, :nombre_completo, :email, :password, :telefono, :dni, :chapa, :foto_perfil)";
        
        $stmt = $this->conn->prepare($query);
        
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        
        $stmt->bindParam(':tipo_usuario', $data['tipo_usuario']);
        $stmt->bindParam(':nombre_completo', $data['nombre_completo']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':telefono', $data['telefono']);
        $stmt->bindParam(':dni', $data['dni']);
        $stmt->bindParam(':chapa', $data['chapa']);
        $stmt->bindParam(':foto_perfil', $data['foto_perfil']);

        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
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

    public function getByEmail($email) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function update($id, $data) {
        $fields = [];
        $params = [':id' => $id];

        foreach ($data as $key => $value) {
            if ($key !== 'password') {
                $fields[] = "{$key} = :{$key}";
                $params[":{$key}"] = $value;
            }
        }

        if (isset($data['password'])) {
            $fields[] = "password = :password";
            $params[':password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $query = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($params);
    }

    private function updateLastAccess($id) {
        $query = "UPDATE {$this->table} SET ultimo_acceso = NOW() WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }

    public function suspend($id) {
        $query = "UPDATE {$this->table} SET estado = 'suspendido' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "UPDATE {$this->table} SET estado = 'eliminado' WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function getAll($tipo = null) {
        $query = "SELECT * FROM {$this->table} WHERE estado != 'eliminado'";
        if ($tipo) {
            $query .= " AND tipo_usuario = :tipo";
        }
        $query .= " ORDER BY fecha_registro DESC";
        
        $stmt = $this->conn->prepare($query);
        if ($tipo) {
            $stmt->bindParam(':tipo', $tipo);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countByType($tipo) {
        $query = "SELECT COUNT(*) as total FROM {$this->table} 
                  WHERE tipo_usuario = :tipo AND estado = 'activo'";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }
}

