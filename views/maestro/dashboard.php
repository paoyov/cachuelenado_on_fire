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
    
    <div class="row">
        <div class="col-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-star" style="font-size: 3rem; color: var(--warning-color);"></i>
                    <h3><?php echo number_format($maestro['calificacion_promedio'], 1); ?></h3>
                    <p>Calificación Promedio</p>
                </div>
            </div>
        </div>
        
        <div class="col-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-briefcase" style="font-size: 3rem; color: var(--primary-color);"></i>
                    <h3><?php echo $maestro['total_trabajos']; ?></h3>
                    <p>Trabajos Completados</p>
                </div>
            </div>
        </div>
        
        <div class="col-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-eye" style="font-size: 3rem; color: var(--info-color);"></i>
                    <h3><?php echo $maestro['total_vistas']; ?></h3>
                    <p>Vistas del Perfil</p>
                </div>
            </div>
        </div>
        
        <div class="col-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-comments" style="font-size: 3rem; color: var(--success-color);"></i>
                    <h3><?php echo count($mensajes_recientes); ?></h3>
                    <p>Conversaciones</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mensajes Recientes</h3>
                    <a href="<?php echo BASE_URL; ?>maestro/mensajes" class="btn btn-sm btn-outline">Ver Todos</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($mensajes_recientes)): ?>
                        <?php foreach (array_slice($mensajes_recientes, 0, 5) as $conversacion): ?>
                        <div class="message-item">
                            <div class="d-flex align-items-center gap-2">
                                <div>
                                    <?php if (!empty($conversacion['foto_perfil'])): ?>
                                        <img src="<?php echo UPLOAD_URL . $conversacion['foto_perfil']; ?>" alt="" style="width: 40px; height: 40px; border-radius: 50%;">
                                    <?php else: ?>
                                        <i class="fas fa-user-circle" style="font-size: 2rem; color: var(--gray-color);"></i>
                                    <?php endif; ?>
                                </div>
                                <div style="flex: 1;">
                                    <strong><?php echo htmlspecialchars($conversacion['nombre_completo']); ?></strong>
                                    <p style="margin: 0; font-size: 0.875rem; color: var(--gray-color);">
                                        <?php echo htmlspecialchars(substr($conversacion['ultimo_mensaje'], 0, 50)); ?>...
                                    </p>
                                </div>
                                <?php if ($conversacion['no_leidos'] > 0): ?>
                                <span class="badge" style="background: var(--primary-color); color: white; border-radius: 50%; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;">
                                    <?php echo $conversacion['no_leidos']; ?>
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <hr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center" style="color: var(--gray-color);">No hay mensajes</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Calificaciones Recientes</h3>
                    <a href="<?php echo BASE_URL; ?>maestro/calificaciones" class="btn btn-sm btn-outline">Ver Todas</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($calificaciones_recientes)): ?>
                        <?php foreach ($calificaciones_recientes as $calificacion): ?>
                        <div class="rating-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong><?php echo htmlspecialchars($calificacion['nombre_completo']); ?></strong>
                                    <div class="stars">
                                        <?php 
                                        $promedio = ($calificacion['puntualidad'] + $calificacion['calidad'] + $calificacion['trato'] + $calificacion['limpieza']) / 4;
                                        for ($i = 1; $i <= 5; $i++): 
                                        ?>
                                            <i class="fas fa-star <?php echo $i <= round($promedio) ? 'text-warning' : ''; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <small style="color: var(--gray-color);"><?php echo formatDate($calificacion['fecha_calificacion']); ?></small>
                            </div>
                            <?php if (!empty($calificacion['comentario'])): ?>
                            <p style="margin-top: 0.5rem; color: var(--gray-color); font-size: 0.875rem;">
                                <?php echo htmlspecialchars($calificacion['comentario']); ?>
                            </p>
                            <?php endif; ?>
                        </div>
                        <hr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center" style="color: var(--gray-color);">No hay calificaciones</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Acciones Rápidas</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?php echo BASE_URL; ?>maestro/perfil-editar" class="btn btn-primary">
                            <i class="fas fa-user-edit"></i> Editar Perfil
                        </a>
                        <a href="<?php echo BASE_URL; ?>maestro/portafolio" class="btn btn-primary">
                            <i class="fas fa-images"></i> Gestionar Portafolio
                        </a>
                        <a href="<?php echo BASE_URL; ?>maestro/disponibilidad" class="btn btn-primary">
                            <i class="fas fa-clock"></i> Actualizar Disponibilidad
                        </a>
                        <a href="<?php echo BASE_URL; ?>maestro/mensajes" class="btn btn-primary">
                            <i class="fas fa-comments"></i> Ver Mensajes
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../views/layout/testimonials.php'; ?>

<style>
.message-item, .rating-item {
    padding: 0.75rem 0;
}

.badge {
    font-size: 0.75rem;
}

.stars {
    color: #ddd;
}

.stars .text-warning {
    color: var(--warning-color);
}
</style>

