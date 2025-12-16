<?php
/**
 * Punto de entrada principal
 * Cachueleando On Fire
 */

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'core/Router.php';
require_once 'core/Controller.php';

// Crear instancia del router
$router = new Router();

// Definir rutas públicas
$router->addRoute('', 'HomeController', 'index');
$router->addRoute('home', 'HomeController', 'index');
$router->addRoute('buscar', 'BuscarController', 'index');
$router->addRoute('maestro/perfil', 'MaestroController', 'verPerfil');
$router->addRoute('login', 'AuthController', 'login');
$router->addRoute('register', 'AuthController', 'register');
$router->addRoute('logout', 'AuthController', 'logout');

// Rutas de autenticación
$router->addRoute('auth/login', 'AuthController', 'processLogin');
$router->addRoute('auth/register', 'AuthController', 'processRegister');

// Rutas de cliente
$router->addRoute('cliente/dashboard', 'ClienteController', 'dashboard');
$router->addRoute('cliente/perfil', 'ClienteController', 'perfil');

$router->addRoute('cliente/historial', 'ClienteController', 'historial');
$router->addRoute('cliente/calificaciones', 'ClienteController', 'calificaciones');
$router->addRoute('cliente/calificar', 'ClienteController', 'calificar');

// Rutas de maestro
$router->addRoute('maestro/dashboard', 'MaestroController', 'dashboard');
$router->addRoute('maestro/perfil-editar', 'MaestroController', 'editarPerfil');
$router->addRoute('maestro/portafolio', 'MaestroController', 'portafolio');
$router->addRoute('maestro/disponibilidad', 'MaestroController', 'disponibilidad');

$router->addRoute('maestro/calificaciones', 'MaestroController', 'calificaciones');
$router->addRoute('maestro/historial', 'MaestroController', 'historial');
$router->addRoute('maestro/configuracion', 'MaestroController', 'configuracion');

// Rutas de administrador
$router->addRoute('admin/dashboard', 'AdminController', 'dashboard');
$router->addRoute('admin/maestros', 'AdminController', 'maestros');
$router->addRoute('admin/validar-perfil', 'AdminController', 'validarPerfil');
$router->addRoute('admin/estadisticas', 'AdminController', 'estadisticas');
$router->addRoute('admin/usuarios', 'AdminController', 'usuarios');
$router->addRoute('admin/reportes', 'AdminController', 'reportes');
$router->addRoute('admin/reportes-mensuales', 'AdminController', 'reportesMensuales');
$router->addRoute('admin/get-maestro-details', 'AdminController', 'getMaestroDetails');
$router->addRoute('admin/pagos', 'AdminController', 'pagos');

// Rutas de pago
$router->addRoute('pago/procesar', 'PagoController', 'procesarPago');
$router->addRoute('pago/verificar', 'PagoController', 'verificarPago');
$router->addRoute('pago/cerrar-modal', 'PagoController', 'cerrarModal');

// Rutas API

$router->addRoute('api/buscar', 'ApiController', 'buscar');
$router->addRoute('api/disponibilidad', 'ApiController', 'actualizarDisponibilidad');
$router->addRoute('api/calificar', 'ApiController', 'calificar');
// API para marcar leídos
$router->addRoute('api/notificaciones/marcar', 'ApiController', 'marcarNotificacion');


// Procesar la ruta
$url = isset($_GET['url']) ? $_GET['url'] : '';
$router->dispatch($url);

