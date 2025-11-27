<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

if (!isset($_FILES['file'])) {
    echo json_encode(['success' => false, 'message' => 'No file uploaded']);
    exit;
}

$file = $_FILES['file'];
if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'message' => 'Error en subida']);
    exit;
}

$allowed = ['image/jpeg','image/png','image/gif','video/mp4','video/webm'];
if (!in_array($file['type'], $allowed)) {
    echo json_encode(['success' => false, 'message' => 'Tipo no permitido']);
    exit;
}

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$name = uniqid('msg_') . '.' . $ext;
$destDir = __DIR__ . '/../uploads/mensajes/';
if (!is_dir($destDir)) mkdir($destDir, 0755, true);
$dest = $destDir . $name;
if (!move_uploaded_file($file['tmp_name'], $dest)) {
    echo json_encode(['success' => false, 'message' => 'No se pudo guardar archivo']);
    exit;
}

// return path relative to uploads folder (e.g. mensajes/xxxx.jpg)
$relative = 'mensajes/' . $name;
echo json_encode(['success' => true, 'path' => $relative]);
