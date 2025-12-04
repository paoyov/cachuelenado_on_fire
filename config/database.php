<?php
/**
 * Configuración de Base de Datos
 * Cachueleando On Fire
 */

class Database {
    private $host = '127.0.0.1';
    // Si tu MySQL en XAMPP usa otro puerto (ver XAMPP Control Panel), actualiza esto.
    private $port = 33065;
    private $db_name = 'cachueleando_on_fire';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4";

            // Para DEBUG: puedes habilitar temporalmente registro de DSN
            // error_log('PDO DSN: ' . $dsn);

            $this->conn = new PDO(
                $dsn,
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );
        } catch(PDOException $exception) {
            error_log("Error de conexión: " . $exception->getMessage());
            die("Error de conexión a la base de datos. Por favor, contacte al administrador.");
        }

        return $this->conn;
    }
}

