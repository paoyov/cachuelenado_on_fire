<?php
/**
 * Configuración General del Sistema
 * Cachueleando On Fire
 */

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/error.log');

// Configuración de zona horaria
date_default_timezone_set('America/Lima');

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 en producción con HTTPS

// Rutas base
// Si usas XAMPP en diferentes puertos cambia APP_PORT a 8012 (HTTP) o 1443 (HTTPS) según muestra XAMPP
define('APP_PORT', getenv('APP_PORT') ?: ($_SERVER['SERVER_PORT'] ?? 80));
define('APP_PROTOCOL', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http');
// Host local (puedes cambiar a '127.0.0.1' si lo prefieres)
define('APP_HOST', getenv('APP_HOST') ?: 'localhost');
// Construye la BASE_URL con puerto para evitar problemas cuando Apache no usa 80
define('BASE_URL', APP_PROTOCOL . '://' . APP_HOST . ':' . APP_PORT . '/Cachueleando_On_Fire/');
define('BASE_PATH', __DIR__ . '/../');

// Rutas de archivos
define('UPLOAD_PATH', BASE_PATH . 'uploads/');
define('UPLOAD_URL', BASE_URL . 'uploads/');

// Configuración de subida de archivos
define('MAX_FILE_SIZE', 10 * 1024 * 1024); // 10MB (mensajes pueden contener videos pequeños)
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/jpg', 'image/gif']);
define('ALLOWED_VIDEO_TYPES', ['video/mp4', 'video/webm', 'video/quicktime']);
define('ALLOWED_DOCUMENT_TYPES', ['application/pdf', 'image/jpeg', 'image/png', 'image/jpg']);
// Tipos permitidos para adjuntos en mensajes (imagenes + videos)
define('ALLOWED_MEDIA_TYPES', array_merge(ALLOWED_IMAGE_TYPES, ALLOWED_VIDEO_TYPES));

// Configuración de email (para notificaciones)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'tu_email@gmail.com');
define('SMTP_PASS', 'tu_password');
define('SMTP_FROM_EMAIL', 'noreply@cachueleando.com');
define('SMTP_FROM_NAME', 'Cachueleando On Fire');

// Configuración de WhatsApp API (Twilio o similar)
define('WHATSAPP_API_KEY', 'tu_api_key');
define('WHATSAPP_API_URL', 'https://api.whatsapp.com/send');

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoload de clases
spl_autoload_register(function ($class) {
    $paths = [
        BASE_PATH . 'models/' . $class . '.php',
        BASE_PATH . 'controllers/' . $class . '.php',
        BASE_PATH . 'core/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Función helper para redirección
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit();
}

// Función helper para sanitizar entrada
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

// Función helper para verificar si el usuario está logueado
function isLoggedIn() {
    return isset($_SESSION['usuario_id']) && isset($_SESSION['tipo_usuario']);
}

// Función helper para verificar tipo de usuario
function isAdmin() {
    return isLoggedIn() && $_SESSION['tipo_usuario'] === 'administrador';
}

function isMaestro() {
    return isLoggedIn() && $_SESSION['tipo_usuario'] === 'maestro';
}

function isCliente() {
    return isLoggedIn() && $_SESSION['tipo_usuario'] === 'cliente';
}

// Función helper para obtener URL de assets
function asset($path) {
    return BASE_URL . 'assets/' . $path;
}

// Función helper para formatear fecha
function formatDate($date, $format = 'd/m/Y') {
    if (empty($date)) return '';
    $dateObj = new DateTime($date);
    return $dateObj->format($format);
}

// Función helper para formatear fecha y hora
function formatDateTime($date, $format = 'd/m/Y H:i') {
    if (empty($date)) return '';
    $dateObj = new DateTime($date);
    return $dateObj->format($format);
}

