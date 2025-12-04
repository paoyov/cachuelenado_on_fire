<?php
/**
 * Controlador Base
 */

class Controller {
    protected $db;
    protected $model;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    protected function view($view, $data = []) {
        extract($data);
        $viewFile = BASE_PATH . 'views/' . $view . '.php';
        
        if (file_exists($viewFile)) {
            require_once BASE_PATH . 'views/layout/header.php';
            require_once $viewFile;
            require_once BASE_PATH . 'views/layout/footer.php';
        } else {
            die("Vista no encontrada: {$view}");
        }
    }

    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function requireAuth($allowedTypes = []) {
        if (!isLoggedIn()) {
            redirect('login');
        }

        if (!empty($allowedTypes) && !in_array($_SESSION['tipo_usuario'], $allowedTypes)) {
            redirect('home');
        }
    }

    protected function uploadFile($file, $directory, $allowedTypes = []) {
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['success' => false, 'message' => 'Parámetros de archivo inválidos'];
        }

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Error al subir el archivo'];
        }

        if ($file['size'] > MAX_FILE_SIZE) {
            return ['success' => false, 'message' => 'El archivo excede el tamaño máximo permitido'];
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file['tmp_name']);

        if (!empty($allowedTypes) && !in_array($mimeType, $allowedTypes)) {
            return ['success' => false, 'message' => 'Tipo de archivo no permitido'];
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $uploadPath = UPLOAD_PATH . $directory . '/' . $filename;

        // Crear directorio si no existe
        if (!is_dir(UPLOAD_PATH . $directory)) {
            mkdir(UPLOAD_PATH . $directory, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return [
                'success' => true,
                'filename' => $filename,
                'path' => $directory . '/' . $filename,
                'url' => UPLOAD_URL . $directory . '/' . $filename
            ];
        }

        return ['success' => false, 'message' => 'Error al guardar el archivo'];
    }
}

