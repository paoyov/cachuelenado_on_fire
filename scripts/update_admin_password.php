<?php
/**
 * Script CLI: Actualiza la contraseña del usuario administrador
 * Uso: php scripts/update_admin_password.php NUEVA_CONTRASEÑA
 */

require_once __DIR__ . '/../config/config.php';

// Cargar Database class
if (!class_exists('Database')) {
    require_once BASE_PATH . 'config/database.php';
}

$new = $argv[1] ?? null;
if (php_sapi_name() !== 'cli') {
    echo "Este script debe ejecutarse desde la línea de comandos.\n";
    exit(1);
}

if (empty($new)) {
    echo "Uso: php scripts/update_admin_password.php NUEVA_CONTRASEÑA\n";
    exit(1);
}

// Validaciones mínimas
if (strlen($new) < 8) {
    echo "Advertencia: la contraseña es corta (menos de 8 caracteres). Continúo? (s/n): ";
    $handle = fopen('php://stdin','r');
    $line = trim(fgets($handle));
    if (strtolower($line) !== 's') {
        echo "Operación cancelada.\n";
        exit(1);
    }
}

$db = new Database();
$conn = $db->getConnection();

$hash = password_hash($new, PASSWORD_BCRYPT);

try {
    $stmt = $conn->prepare("UPDATE usuarios SET password = :hash WHERE tipo_usuario = 'administrador' LIMIT 1");
    $stmt->bindParam(':hash', $hash);
    $ok = $stmt->execute();
    if ($ok) {
        echo "Contraseña del administrador actualizada correctamente.\n";
        echo "Hash guardado: " . $hash . "\n";
        exit(0);
    }
} catch (PDOException $e) {
    echo "Error al actualizar la contraseña: " . $e->getMessage() . "\n";
    exit(1);
}

echo "No se pudo actualizar la contraseña.\n";
exit(1);
