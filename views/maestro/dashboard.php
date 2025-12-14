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


