<?php
$title = 'Panel del Maestro';
?>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-tachometer-alt"></i> Panel del Maestro</h1>
        <p>Bienvenido, <?php echo htmlspecialchars($maestro['nombre_completo']); ?></p>
    </div>
</div>

<div class="container">
    <?php 
    $pago_activo = $pago_activo ?? null;
    $mostrar_modal = $mostrar_modal_pago ?? false;
    ?>
    
    <?php if (!$pago_activo || (isset($pago_activo['fecha_expiracion']) && strtotime($pago_activo['fecha_expiracion']) < time())): ?>
    <div class="alert alert-warning" style="background: #fff3cd; border-left: 4px solid var(--warning-color);">
        <i class="fas fa-exclamation-triangle"></i>
        <strong>Pago Requerido:</strong> Para que tu perfil sea visible y validado, debes realizar un pago de S/ 3.00. 
        <button type="button" class="btn btn-sm btn-primary ml-2" onclick="abrirModalPago()" style="background: var(--primary-color);">
            <i class="fas fa-wallet"></i> Realizar Pago
        </button>
    </div>
    <?php elseif (isset($pago_activo['fecha_expiracion'])): 
        $horas_restantes = (strtotime($pago_activo['fecha_expiracion']) - time()) / 3600;
        if ($horas_restantes < 6 && $horas_restantes > 0): ?>
    <div class="alert alert-warning" style="background: #fff3cd; border-left: 4px solid var(--warning-color);">
        <i class="fas fa-clock"></i>
        <strong>Atención:</strong> Tu pago expirará en <?php echo round($horas_restantes, 1); ?> horas. 
        <button type="button" class="btn btn-sm btn-primary ml-2" onclick="abrirModalPago()" style="background: var(--primary-color);">
            <i class="fas fa-wallet"></i> Renovar Pago
        </button>
    </div>
    <?php endif; endif; ?>
    
    <?php if ($maestro['estado_perfil'] === 'pendiente'): ?>
    <div class="alert alert-error">
        <i class="fas fa-clock"></i>
        Tu perfil está pendiente de validación. Una vez validado, será visible para los clientes.
    </div>
    <?php elseif ($maestro['estado_perfil'] === 'rechazado'): ?>
    <div class="alert alert-error">
        <i class="fas fa-times-circle"></i>
        Tu perfil ha sido rechazado. <?php if (!empty($maestro['motivo_rechazo'])): ?>
        Motivo: <?php echo htmlspecialchars($maestro['motivo_rechazo']); ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <?php if ($pago_activo && isset($pago_activo['fecha_expiracion']) && !$pago_expirado): ?>
    <!-- Contador de Tiempo Restante -->
    <div class="countdown-container">
        <div class="countdown-card">
            <div class="countdown-header">
                <div class="countdown-icon-wrapper">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="countdown-header-text">
                    <h3 class="countdown-title">Tiempo Restante de Registro</h3>
                    <p class="countdown-subtitle">Tu perfil estará activo por</p>
                </div>
            </div>
            <div class="countdown-body">
                <div class="countdown-display" id="countdownDisplay">
                    <div class="countdown-item">
                        <div class="countdown-value" id="hours">00</div>
                        <div class="countdown-label">Horas</div>
                    </div>
                    <div class="countdown-separator">:</div>
                    <div class="countdown-item">
                        <div class="countdown-value" id="minutes">00</div>
                        <div class="countdown-label">Minutos</div>
                    </div>
                    <div class="countdown-separator">:</div>
                    <div class="countdown-item">
                        <div class="countdown-value" id="seconds">00</div>
                        <div class="countdown-label">Segundos</div>
                    </div>
                </div>
                <div class="countdown-progress">
                    <div class="progress-bar-wrapper">
                        <div class="progress-bar" id="progressBar"></div>
                    </div>
                    <p class="countdown-info">
                        <i class="fas fa-info-circle"></i>
                        Renueva tu pago antes de que expire para mantener tu perfil activo
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="maestro-ratings-card-wrapper">
        <div class="ratings-card">
            <div class="ratings-card-header">
                <h3 class="ratings-card-title">
                    <i class="fas fa-star"></i> Calificaciones Recientes
                </h3>
                <a href="<?php echo BASE_URL; ?>maestro/calificaciones" class="btn-ratings-view-all">
                    Ver Todas <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="ratings-card-body">
                <?php if (!empty($calificaciones_recientes)): ?>
                    <?php foreach ($calificaciones_recientes as $calificacion): ?>
                    <div class="rating-item">
                        <div class="rating-header">
                            <div class="rating-user-section">
                                <div class="rating-user-avatar-small">
                                    <?php if (!empty($calificacion['foto_perfil'])): ?>
                                        <?php 
                                        $inicial = strtoupper(substr($calificacion['nombre_completo'], 0, 1));
                                        $foto_url = UPLOAD_URL . $calificacion['foto_perfil'];
                                        ?>
                                        <img src="<?php echo $foto_url; ?>" 
                                             alt="<?php echo htmlspecialchars($calificacion['nombre_completo']); ?>"
                                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <span class="avatar-fallback" style="display: none;"><?php echo $inicial; ?></span>
                                    <?php else: ?>
                                        <?php echo strtoupper(substr($calificacion['nombre_completo'], 0, 1)); ?>
                                    <?php endif; ?>
                                </div>
                                <div class="rating-user-info">
                                    <strong class="rating-user-name"><?php echo htmlspecialchars($calificacion['nombre_completo']); ?></strong>
                                    <div class="rating-stars">
                                        <?php 
                                        $promedio = ($calificacion['puntualidad'] + $calificacion['calidad'] + $calificacion['trato'] + $calificacion['limpieza']) / 4;
                                        for ($i = 1; $i <= 5; $i++): 
                                        ?>
                                            <i class="fas fa-star <?php echo $i <= round($promedio) ? 'star-filled' : 'star-empty'; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </div>
                            <span class="rating-date"><?php echo formatDate($calificacion['fecha_calificacion']); ?></span>
                        </div>
                        <?php if (!empty($calificacion['comentario'])): ?>
                        <p class="rating-comment">
                            <?php echo htmlspecialchars($calificacion['comentario']); ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="no-ratings">
                        <i class="fas fa-star"></i>
                        <p>No hay calificaciones aún</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="maestro-dashboard-cards-wrapper">
        <div class="maestro-dashboard-cards-row">
            <div class="maestro-dashboard-card-item">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-star" style="font-size: 3rem; color: var(--warning-color);"></i>
                        <h3><?php echo number_format($maestro['calificacion_promedio'], 1); ?></h3>
                        <p>Calificación Promedio</p>
                    </div>
                </div>
            </div>
            
            <div class="maestro-dashboard-card-item">
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-eye" style="font-size: 3rem; color: var(--info-color);"></i>
                        <h3><?php echo $maestro['total_vistas']; ?></h3>
                        <p>Vistas del Perfil</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="quick-actions-wrapper">
        <div class="quick-actions-card">
            <div class="quick-actions-header">
                <h3 class="quick-actions-title">
                    <i class="fas fa-bolt"></i> Acciones Rápidas
                </h3>
            </div>
            <div class="quick-actions-body">
                <div class="quick-actions-buttons">
                    <a href="<?php echo BASE_URL; ?>maestro/perfil-editar" class="quick-action-btn">
                        <i class="fas fa-user-edit"></i> Editar Perfil
                    </a>
                    <a href="<?php echo BASE_URL; ?>maestro/portafolio" class="quick-action-btn">
                        <i class="fas fa-images"></i> Gestionar Portafolio
                    </a>
                    <a href="<?php echo BASE_URL; ?>maestro/disponibilidad" class="quick-action-btn">
                        <i class="fas fa-clock"></i> Actualizar Disponibilidad
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../views/layout/testimonials.php'; ?>

<?php if ($pago_expirado): ?>
    <?php include 'modal_pago_expirado.php'; ?>
<?php elseif ($mostrar_modal || !$pago_activo): ?>
    <?php include 'modal_pago.php'; ?>
<?php endif; ?>

<script>
function abrirModalPago() {
    const modal = document.getElementById('modalPagoYape');
    if (modal) {
        modal.style.display = 'flex';
    }
}

// Mostrar modal automáticamente si está en sesión
<?php if ($mostrar_modal): ?>
document.addEventListener('DOMContentLoaded', function() {
    abrirModalPago();
});
<?php endif; ?>

// Función para configurar el modal expirado
function configurarModalExpirado() {
    const modalExpirado = document.getElementById('modalPagoExpirado');
    if (modalExpirado) {
        // Bloquear scroll del body
        document.body.style.overflow = 'hidden';
        
        // Prevenir cerrar con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                e.preventDefault();
                e.stopPropagation();
            }
        }, true);
        
        // Prevenir cerrar haciendo clic fuera
        modalExpirado.addEventListener('click', function(e) {
            if (e.target === modalExpirado) {
                e.stopPropagation();
            }
        });
    }
}

// El modal de pago expirado se muestra automáticamente (ya está en display: flex en el HTML)
<?php if ($pago_expirado): ?>
document.addEventListener('DOMContentLoaded', function() {
    configurarModalExpirado();
});
<?php endif; ?>

// Contador de tiempo restante
<?php if ($pago_activo && isset($pago_activo['fecha_expiracion']) && !$pago_expirado): ?>
document.addEventListener('DOMContentLoaded', function() {
    const fechaExpiracion = new Date('<?php echo $pago_activo['fecha_expiracion']; ?>').getTime();
    const horasElement = document.getElementById('hours');
    const minutosElement = document.getElementById('minutes');
    const segundosElement = document.getElementById('seconds');
    const progressBar = document.getElementById('progressBar');
    
    // Duración total en milisegundos (24 horas)
    const duracionTotal = 24 * 60 * 60 * 1000;
    
    function updateCountdown() {
        const ahora = new Date().getTime();
        const diferencia = fechaExpiracion - ahora;
        
        if (diferencia > 0) {
            // Calcular horas, minutos y segundos
            const horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
            const segundos = Math.floor((diferencia % (1000 * 60)) / 1000);
            
            // Actualizar elementos
            horasElement.textContent = String(horas).padStart(2, '0');
            minutosElement.textContent = String(minutos).padStart(2, '0');
            segundosElement.textContent = String(segundos).padStart(2, '0');
            
            // Calcular porcentaje restante
            const tiempoTranscurrido = duracionTotal - diferencia;
            const porcentaje = (tiempoTranscurrido / duracionTotal) * 100;
            progressBar.style.width = porcentaje + '%';
            
            // Cambiar color según tiempo restante
            const horasRestantes = horas + (minutos / 60);
            if (horasRestantes < 2) {
                progressBar.className = 'progress-bar progress-bar-danger';
                horasElement.parentElement.className = 'countdown-item countdown-item-danger';
                minutosElement.parentElement.className = 'countdown-item countdown-item-danger';
                segundosElement.parentElement.className = 'countdown-item countdown-item-danger';
            } else if (horasRestantes < 6) {
                progressBar.className = 'progress-bar progress-bar-warning';
                horasElement.parentElement.className = 'countdown-item countdown-item-warning';
                minutosElement.parentElement.className = 'countdown-item countdown-item-warning';
                segundosElement.parentElement.className = 'countdown-item countdown-item-warning';
            } else {
                progressBar.className = 'progress-bar progress-bar-success';
                horasElement.parentElement.className = 'countdown-item';
                minutosElement.parentElement.className = 'countdown-item';
                segundosElement.parentElement.className = 'countdown-item';
            }
        } else {
            // Tiempo expirado
            horasElement.textContent = '00';
            minutosElement.textContent = '00';
            segundosElement.textContent = '00';
            progressBar.style.width = '100%';
            progressBar.className = 'progress-bar progress-bar-danger';
            
            // Ocultar contador
            const countdownContainer = document.querySelector('.countdown-container');
            if (countdownContainer) {
                countdownContainer.style.display = 'none';
            }
            
            // Recargar página inmediatamente para mostrar modal de pago expirado
            window.location.reload();
        }
    }
    
    // Actualizar cada segundo
    updateCountdown();
    setInterval(updateCountdown, 1000);
});
<?php endif; ?>
</script>

<style>
/* ============================================
   Contador de Tiempo Restante
   ============================================ */
.countdown-container {
    margin-bottom: 2rem;
}

.countdown-card {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.countdown-card:hover {
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.countdown-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    padding: 1.5rem 2rem;
    display: flex;
    align-items: center;
    gap: 1.25rem;
    color: white;
}

.countdown-icon-wrapper {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.75rem;
    backdrop-filter: blur(10px);
    animation: pulse-icon 2s ease infinite;
}

@keyframes pulse-icon {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.countdown-header-text {
    flex: 1;
}

.countdown-title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: white;
}

.countdown-subtitle {
    margin: 0.25rem 0 0 0;
    font-size: 0.95rem;
    opacity: 0.9;
}

.countdown-body {
    padding: 2.5rem 2rem;
}

.countdown-display {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.countdown-item {
    text-align: center;
    transition: all 0.3s ease;
}

.countdown-value {
    font-size: 3.5rem;
    font-weight: 700;
    color: var(--primary-color);
    line-height: 1;
    margin-bottom: 0.5rem;
    font-family: 'Courier New', monospace;
    text-shadow: 0 2px 8px rgba(255, 107, 53, 0.2);
    transition: all 0.3s ease;
}

.countdown-item-warning .countdown-value {
    color: var(--warning-color);
    text-shadow: 0 2px 8px rgba(255, 193, 7, 0.2);
    animation: pulse-warning 1s ease infinite;
}

.countdown-item-danger .countdown-value {
    color: var(--danger-color);
    text-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
    animation: pulse-danger 0.5s ease infinite;
}

@keyframes pulse-warning {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

@keyframes pulse-danger {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

.countdown-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--gray-color);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.countdown-separator {
    font-size: 3rem;
    font-weight: 700;
    color: var(--primary-color);
    line-height: 1;
    animation: blink 1s ease infinite;
}

@keyframes blink {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.3;
    }
}

.countdown-progress {
    margin-top: 2rem;
}

.progress-bar-wrapper {
    width: 100%;
    height: 12px;
    background: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 1rem;
    box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    border-radius: 10px;
    transition: width 1s ease, background 0.3s ease;
    box-shadow: 0 2px 8px rgba(255, 107, 53, 0.3);
}

.progress-bar-success {
    background: linear-gradient(90deg, #28a745 0%, #20c997 100%);
    box-shadow: 0 2px 8px rgba(40, 167, 69, 0.3);
}

.progress-bar-warning {
    background: linear-gradient(90deg, var(--warning-color) 0%, #ff9800 100%);
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    animation: pulse-progress 1s ease infinite;
}

.progress-bar-danger {
    background: linear-gradient(90deg, var(--danger-color) 0%, #c82333 100%);
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    animation: pulse-progress-danger 0.5s ease infinite;
}

@keyframes pulse-progress {
    0%, 100% {
        box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
    }
    50% {
        box-shadow: 0 4px 16px rgba(255, 193, 7, 0.5);
    }
}

@keyframes pulse-progress-danger {
    0%, 100% {
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }
    50% {
        box-shadow: 0 4px 16px rgba(220, 53, 69, 0.6);
    }
}

.countdown-info {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin: 0;
    font-size: 0.9rem;
    color: var(--gray-color);
    text-align: center;
}

.countdown-info i {
    color: var(--primary-color);
}

/* Responsive */
@media (max-width: 768px) {
    .countdown-display {
        gap: 1rem;
    }
    
    .countdown-value {
        font-size: 2.5rem;
    }
    
    .countdown-separator {
        font-size: 2rem;
    }
    
    .countdown-header {
        padding: 1.25rem 1.5rem;
    }
    
    .countdown-body {
        padding: 2rem 1.5rem;
    }
    
    .countdown-title {
        font-size: 1.25rem;
    }
}

@media (max-width: 576px) {
    .countdown-display {
        gap: 0.5rem;
    }
    
    .countdown-value {
        font-size: 2rem;
    }
    
    .countdown-separator {
        font-size: 1.5rem;
    }
    
    .countdown-label {
        font-size: 0.75rem;
    }
    
    .countdown-icon-wrapper {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
    }
    
    .countdown-header {
        padding: 1rem;
        flex-direction: column;
        text-align: center;
    }
}
</style>


