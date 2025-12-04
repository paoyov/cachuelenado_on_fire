<?php
$title = 'Panel de Administración';
?>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-tachometer-alt"></i> Panel de Administración</h1>
        <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_completo']); ?></p>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-users" style="font-size: 3rem; color: var(--primary-color);"></i>
                    <h3><?php echo $total_clientes; ?></h3>
                    <p>Clientes Registrados</p>
                </div>
            </div>
        </div>
        
        <div class="col-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-hammer" style="font-size: 3rem; color: var(--success-color);"></i>
                    <h3><?php echo $total_maestros; ?></h3>
                    <p>Maestros Registrados</p>
                </div>
            </div>
        </div>
        
        <div class="col-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-search" style="font-size: 3rem; color: var(--info-color);"></i>
                    <h3><?php echo $total_busquedas; ?></h3>
                    <p>Búsquedas Realizadas</p>
                </div>
            </div>
        </div>
        
        <div class="col-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-clock" style="font-size: 3rem; color: var(--warning-color);"></i>
                    <h3><?php echo $maestros_pendientes; ?></h3>
                    <p>Perfiles Pendientes</p>
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
                        <a href="<?php echo BASE_URL; ?>admin/maestros" class="btn btn-primary">
                            <i class="fas fa-user-check"></i> Validar Perfiles
                        </a>
                        <a href="<?php echo BASE_URL; ?>admin/estadisticas" class="btn btn-primary">
                            <i class="fas fa-chart-bar"></i> Ver Estadísticas
                        </a>
                        <a href="<?php echo BASE_URL; ?>admin/usuarios" class="btn btn-primary">
                            <i class="fas fa-users-cog"></i> Gestionar Usuarios
                        </a>
                        <a href="<?php echo BASE_URL; ?>admin/reportes" class="btn btn-primary">
                            <i class="fas fa-exclamation-triangle"></i> Ver Reportes
                        </a>
                        <a href="<?php echo BASE_URL; ?>admin/reportes-mensuales" class="btn btn-primary">
                            <i class="fas fa-file-alt"></i> Reportes Mensuales
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

