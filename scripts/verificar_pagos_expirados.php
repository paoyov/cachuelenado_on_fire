<?php
/**
 * Script para verificar pagos expirados
 * Ejecutar periódicamente mediante cron job o tarea programada
 * Ejemplo cron: */30 * * * * php /ruta/al/proyecto/scripts/verificar_pagos_expirados.php
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/PagoMaestro.php';
require_once __DIR__ . '/../models/Notificacion.php';

try {
    $pagoModel = new PagoMaestro($db);
    $notificacionModel = new Notificacion($db);
    
    // Verificar y actualizar pagos expirados
    $pagoModel->verificarPagosExpirados();
    
    // Notificar a maestros con pagos expirados
    $expirados = $pagoModel->getPagosExpirados();
    foreach ($expirados as $maestro) {
        $titulo = 'Pago Expirado';
        $mensaje = 'Su pago ha expirado. Por favor, realice un nuevo pago de S/ 3.00 para mantener su perfil activo.';
        $notificacionModel->create($maestro['usuario_id'], 'sistema', $titulo, $mensaje);
    }
    
    // Notificar a maestros con pagos próximos a expirar (menos de 6 horas)
    $porExpirar = $pagoModel->getPagosPorExpirar();
    foreach ($porExpirar as $maestro) {
        $titulo = 'Pago Próximo a Expirar';
        $mensaje = 'Su pago expirará pronto. Realice un nuevo pago de S/ 3.00 para mantener su perfil activo.';
        $notificacionModel->create($maestro['usuario_id'], 'sistema', $titulo, $mensaje);
    }
    
    echo "Pagos verificados exitosamente. " . count($expirados) . " expirados, " . count($porExpirar) . " por expirar.\n";
    
} catch (Exception $e) {
    error_log("Error al verificar pagos expirados: " . $e->getMessage());
    echo "Error: " . $e->getMessage() . "\n";
}
