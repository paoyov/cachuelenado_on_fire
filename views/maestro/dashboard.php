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
    $mostrar_modal = isset($mostrar_modal_pago) ? $mostrar_modal_pago : false;
    // Si no hay pago activo Y no está expirado (usuario nuevo), mostrar el modal normal
    if (!$pago_activo && !$pago_expirado) {
        $mostrar_modal = true;
    }
    // Si está expirado, NO mostrar el modal normal, mostrar el expirado
    if ($pago_expirado) {
        $mostrar_modal = false;
    }
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
// Función para ir a registro (volver a registrarse) - DEFINIDA ANTES DEL MODAL
window.irAEditarPerfil = function(e) {
    if (e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    console.log('=== FUNCIÓN irAEditarPerfil EJECUTADA ===');
    
    // Cerrar modal
    const modal = document.getElementById('modalRechazoPerfil');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
    
    // URL directa - usar múltiples métodos para asegurar que funcione
    const baseUrl = '<?php echo BASE_URL; ?>';
    const registerUrl = baseUrl + 'register';
    const fallbackUrl = 'http://localhost:8012/Cachueleando_On_Fire/register';
    
    console.log('BASE_URL:', baseUrl);
    console.log('URL completa:', registerUrl);
    console.log('URL fallback:', fallbackUrl);
    
    // Intentar redirección con múltiples métodos
    try {
        // Método 1: window.location.replace (recomendado)
        window.location.replace(registerUrl);
    } catch (error) {
        console.error('Error con replace:', error);
        try {
            // Método 2: window.location.href
            window.location.href = registerUrl;
        } catch (error2) {
            console.error('Error con href:', error2);
            // Método 3: Fallback a URL hardcodeada
            window.location.href = fallbackUrl;
        }
    }
    
    // Si después de 500ms no ha redirigido, forzar con fallback
    setTimeout(function() {
        if (window.location.href.indexOf('register') === -1) {
            console.log('Forzando redirección con fallback...');
            window.location.href = fallbackUrl;
        }
    }, 500);
    
    return false;
};
</script>

<!-- Modal de Rechazo de Perfil (Tiempo Real) -->
<div class="modal-rechazo-overlay" id="modalRechazoPerfil" style="display: none;">
    <div class="modal-rechazo-container">
        <div class="modal-rechazo-content">
            <div class="modal-rechazo-header">
                <div class="modal-rechazo-icon-wrapper">
                    <i class="fas fa-times-circle"></i>
                </div>
                <h3 class="modal-rechazo-title">Perfil Rechazado</h3>
                <p class="modal-rechazo-subtitle">Tu perfil ha sido rechazado por el administrador</p>
            </div>
            
            <div class="modal-rechazo-body">
                <div class="rechazo-alert">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div>
                        <strong>Tu solicitud de registro ha sido rechazada</strong>
                        <p>El administrador ha revisado tu perfil y ha decidido rechazarlo. Por favor, revisa el motivo y corrige los problemas indicados.</p>
                    </div>
                </div>
                
                <div class="rechazo-motivo-section">
                    <h4 class="rechazo-motivo-title">
                        <i class="fas fa-comment-alt"></i> Motivo del Rechazo
                    </h4>
                    <div class="rechazo-motivo-content" id="rechazoMotivoContent">
                        <p class="rechazo-motivo-text">Cargando motivo...</p>
                    </div>
                </div>
                
                <div class="rechazo-acciones">
                    <p class="rechazo-acciones-text">
                        <i class="fas fa-info-circle"></i>
                        Puedes volver a registrarte corrigiendo los problemas indicados. Tu nuevo registro será enviado para validación.
                    </p>
                </div>
            </div>
            
            <div class="modal-rechazo-footer">
                <button type="button" class="btn-rechazo-cancelar" onclick="cerrarModalRechazo()">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function abrirModalPago() {
    const modal = document.getElementById('modalPagoYape');
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

// Mostrar modal automáticamente si debe mostrarse
<?php if ($mostrar_modal || !$pago_activo): ?>
console.log('Intentando mostrar modal de pago. mostrar_modal: <?php echo $mostrar_modal ? "true" : "false"; ?>, pago_activo: <?php echo $pago_activo ? "true" : "false"; ?>');
(function() {
    let intentos = 0;
    const maxIntentos = 50;
    
    function mostrarModalAuto() {
        intentos++;
        const modal = document.getElementById('modalPagoYape');
        console.log('Intento ' + intentos + ': Buscando modal...', modal ? 'Encontrado' : 'No encontrado');
        
        if (modal) {
            console.log('Mostrando modal de pago');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            return true;
        } else if (intentos < maxIntentos) {
            // Si el modal aún no existe, intentar de nuevo
            setTimeout(mostrarModalAuto, 100);
        } else {
            console.error('No se pudo encontrar el modal después de ' + maxIntentos + ' intentos');
        }
        return false;
    }
    
    // Intentar mostrar inmediatamente
    if (!mostrarModalAuto()) {
        // También cuando el DOM esté completamente cargado
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                console.log('DOM cargado, intentando mostrar modal');
                setTimeout(mostrarModalAuto, 50);
            });
        } else {
            setTimeout(mostrarModalAuto, 50);
        }
    }
})();
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

// ============================================
// DETECCIÓN DE RECHAZO EN TIEMPO REAL
// ============================================
let rechazoPollingInterval = null;
let rechazoYaMostrado = false;

// Función para mostrar modal de rechazo
function mostrarModalRechazo(motivo) {
    if (rechazoYaMostrado) return; // Evitar mostrar múltiples veces
    
    const modal = document.getElementById('modalRechazoPerfil');
    const motivoContent = document.getElementById('rechazoMotivoContent');
    
    if (modal && motivoContent) {
        // Escapar HTML para seguridad
        const motivoEscapado = motivo ? motivo.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>') : 'No se especificó un motivo.';
        motivoContent.innerHTML = `<p class="rechazo-motivo-text">${motivoEscapado}</p>`;
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
        rechazoYaMostrado = true;
        
        // Detener polling una vez mostrado
        if (rechazoPollingInterval) {
            clearInterval(rechazoPollingInterval);
        }
    }
}

// Función para cerrar modal de rechazo
function cerrarModalRechazo() {
    const modal = document.getElementById('modalRechazoPerfil');
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// La función irAEditarPerfil ya está definida arriba antes del modal (línea 223)

// Función para verificar estado del perfil
function verificarEstadoPerfil() {
    fetch('<?php echo BASE_URL; ?>maestro/verificar-estado-perfil', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.rechazado && data.motivo_rechazo && !rechazoYaMostrado) {
            // Perfil rechazado, mostrar modal
            mostrarModalRechazo(data.motivo_rechazo);
        }
    })
    .catch(error => {
        console.error('Error al verificar estado del perfil:', error);
    });
}

// Iniciar polling para verificar rechazo en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    // Verificar inmediatamente
    verificarEstadoPerfil();
    
    // Verificar cada 3 segundos
    rechazoPollingInterval = setInterval(verificarEstadoPerfil, 3000);
    
    // Verificar cuando se vuelve a la pestaña
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && !rechazoYaMostrado) {
            verificarEstadoPerfil();
        }
    });
    
});

// Limpiar intervalo al salir
window.addEventListener('beforeunload', function() {
    if (rechazoPollingInterval) {
        clearInterval(rechazoPollingInterval);
    }
});
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

<style>
/* ============================================
   Modal de Rechazo de Perfil
   ============================================ */
.modal-rechazo-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.75);
    backdrop-filter: blur(5px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10001;
    padding: 20px;
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.modal-rechazo-container {
    width: 100%;
    max-width: 600px;
    margin: auto;
}

.modal-rechazo-content {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    animation: slideUp 0.3s ease-out;
    border: 3px solid #dc3545;
}

@keyframes slideUp {
    from {
        transform: translateY(30px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-rechazo-header {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    color: white;
    padding: 2.5rem 2rem;
    text-align: center;
    position: relative;
}

.modal-rechazo-icon-wrapper {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 3rem;
    animation: shake 0.5s ease;
}

@keyframes shake {
    0%, 100% {
        transform: translateX(0);
    }
    25% {
        transform: translateX(-10px) rotate(-5deg);
    }
    75% {
        transform: translateX(10px) rotate(5deg);
    }
}

.modal-rechazo-title {
    margin: 0 0 0.5rem 0;
    font-size: 1.75rem;
    font-weight: 700;
    color: white;
}

.modal-rechazo-subtitle {
    margin: 0;
    font-size: 1rem;
    opacity: 0.9;
    color: white;
}

.modal-rechazo-body {
    padding: 2.5rem 2rem;
}

.rechazo-alert {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-left: 5px solid #ffc107;
    border-radius: 12px;
    padding: 1.5rem 2rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: flex-start;
    gap: 1.25rem;
}

.rechazo-alert i {
    color: #ffc107;
    font-size: 2rem;
    margin-top: 0.2rem;
    flex-shrink: 0;
}

.rechazo-alert strong {
    display: block;
    color: #856404;
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
}

.rechazo-alert p {
    margin: 0;
    color: #856404;
    line-height: 1.6;
    font-size: 0.95rem;
}

.rechazo-motivo-section {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    border: 2px solid #e9ecef;
}

.rechazo-motivo-title {
    margin: 0 0 1rem 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.rechazo-motivo-title i {
    color: #dc3545;
    font-size: 1.2rem;
}

.rechazo-motivo-content {
    background: white;
    border-radius: 8px;
    padding: 1.25rem;
    border: 2px solid #dc3545;
    min-height: 100px;
}

.rechazo-motivo-text {
    margin: 0;
    color: #495057;
    line-height: 1.8;
    font-size: 1rem;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.rechazo-acciones {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-left: 4px solid #2196f3;
    border-radius: 12px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 1rem;
}

.rechazo-acciones-text {
    margin: 0;
    color: #1565c0;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    line-height: 1.6;
}

.rechazo-acciones-text i {
    font-size: 1.1rem;
    flex-shrink: 0;
}

.modal-rechazo-footer {
    border-top: 2px solid #f0f0f0;
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: center;
    background: #f8f9fa;
}

.btn-rechazo-cancelar {
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 0.875rem 2rem;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-rechazo-cancelar:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
}

.btn-rechazo-entendido {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white !important;
    border: none;
    border-radius: 10px;
    padding: 0.875rem 2.5rem;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
    text-decoration: none !important;
}

.btn-rechazo-entendido:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(40, 167, 69, 0.5);
}

.btn-rechazo-entendido:active {
    transform: translateY(0);
}

.btn-rechazo-entendido i {
    font-size: 1rem;
}

/* Responsive */
@media (max-width: 576px) {
    .modal-rechazo-container {
        max-width: 100%;
    }
    
    .modal-rechazo-header {
        padding: 2rem 1.5rem;
    }
    
    .modal-rechazo-body {
        padding: 2rem 1.5rem;
    }
    
    .modal-rechazo-title {
        font-size: 1.5rem;
    }
    
    .modal-rechazo-icon-wrapper {
        width: 60px;
        height: 60px;
        font-size: 2.5rem;
    }
    
    .rechazo-alert {
        padding: 1.25rem 1.5rem;
        flex-direction: column;
        text-align: center;
    }
    
    .rechazo-motivo-section {
        padding: 1.25rem;
    }
}
</style>


