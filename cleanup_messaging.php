<?php
// cleanup_messaging.php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/config/database.php';

echo "Starting cleanup...\n";

// Drop Table
try {
    $db = new Database();
    $conn = $db->getConnection();
    $conn->exec("DROP TABLE IF EXISTS mensajes");
    echo "[OK] Table 'mensajes' dropped.\n";
} catch (Exception $e) {
    echo "[ERROR] Dropping table: " . $e->getMessage() . "\n";
}

// Files to delete
$files = [
    'core/ChatServer.php',
    'start_chat_server.bat',
    'api/upload_mensaje.php',
    'models/Mensaje.php',
    'views/cliente/mensajes.php',
    'views/maestro/mensajes.php'
];

foreach ($files as $relPath) {
    $absPath = __DIR__ . '/' . $relPath;
    if (file_exists($absPath)) {
        if (unlink($absPath)) {
            echo "[OK] Deleted file: $relPath\n";
        } else {
            echo "[ERROR] Failed to delete file: $relPath\n";
        }
    } else {
        echo "[INFO] File not found (already deleted): $relPath\n";
    }
}

// Directory to delete
$dir = __DIR__ . '/uploads/mensajes';
if (is_dir($dir)) {
    try {
        $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($dir);
        echo "[OK] Deleted directory: uploads/mensajes\n";
    } catch (Exception $e) {
        echo "[ERROR] Deleting directory: " . $e->getMessage() . "\n";
    }
} else {
    echo "[INFO] Directory not found: uploads/mensajes\n";
}

echo "Cleanup complete.\n";
