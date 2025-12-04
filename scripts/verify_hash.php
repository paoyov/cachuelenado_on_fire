<?php
$password = 'admin123';
$currentHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

echo "Testing 'admin123' against current hash:\n";
if (password_verify($password, $currentHash)) {
    echo "MATCH! The current hash is correct.\n";
} else {
    echo "FAIL! The current hash is INCORRECT.\n";
    echo "Generating new hash for 'admin123'...\n";
    $newHash = password_hash($password, PASSWORD_BCRYPT);
    echo "New Hash: " . $newHash . "\n";
}
