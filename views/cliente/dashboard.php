<?php
$title = 'Panel del Cliente';
?>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-home"></i> Panel del Cliente</h1>
        <p>Bienvenido, <?php echo htmlspecialchars($usuario['nombre_completo'] ?? ''); ?></p>
    </div>
</div>

<div class="container h-100">
    <div class="row min-vh-50 justify-content-center align-items-center py-5">
        <div class="col-lg-10">
            <div class="row g-4 justify-content-center flex-column align-items-center">
                
                <!-- Búsquedas Realizadas Card -->
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card h-100 border-0 shadow-lg hover-elevate bg-gradient-primary text-white">
                        <div class="card-body p-4 text-center d-flex flex-column justify-content-center align-items-center gap-3">
                            <div class="icon-circle-glass">
                                <i class="fas fa-search"></i>
                            </div>
                            <div class="text-center">
                                <h2 class="display-3 fw-bold mb-0"><?php echo (int)($busquedas_count ?? 0); ?></h2>
                                <p class="text-white-50 text-uppercase fw-bold letter-spacing-1 mb-0" style="font-size: 0.9rem;">Búsquedas Realizadas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas Card -->
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card h-100 border-0 shadow-lg" style="background: #fff;">
                        <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0 text-center">
                            <h5 class="fw-bold mb-0 text-dark"><i class="fas fa-bolt text-warning me-2"></i>Acciones Rápidas</h5>
                        </div>
                            <div class="d-flex flex-wrap gap-3 justify-content-center w-100" style="max-width: 600px; margin: 0 auto;">
                                <a href="<?php echo BASE_URL; ?>buscar" class="btn btn-primary d-flex align-items-center justify-content-center p-3 btn-action shadow-sm flex-grow-1" style="min-width: 200px;">
                                    <div class="icon-square me-2 bg-white text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                        <i class="fas fa-search fa-sm"></i>
                                    </div>
                                    <span class="fw-bold">Buscar Maestros</span>
                                </a>

                                <a href="<?php echo BASE_URL; ?>cliente/perfil" class="btn btn-primary d-flex align-items-center justify-content-center p-3 btn-action shadow-sm flex-grow-1" style="min-width: 160px;">
                                    <i class="fas fa-user-edit me-2"></i>
                                    <span class="fw-bold">Editar Perfil</span>
                                </a>

                                <a href="<?php echo BASE_URL; ?>cliente/calificaciones" class="btn btn-primary d-flex align-items-center justify-content-center p-3 btn-action shadow-sm flex-grow-1" style="min-width: 160px;">
                                    <i class="fas fa-star me-2"></i>
                                    <span class="fw-bold">Calificaciones</span>
                                </a>
                            </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
/* Dashboard Styles */
.min-vh-50 { min-height: 50vh; }

/* Primary Gradient Background */
.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, #ff9f43 100%);
}

.hover-elevate {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.hover-elevate:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(247, 107, 28, 0.3) !important;
}

/* Glassmorphism Icon */
.icon-circle-glass {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3.5rem;
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.3);
}

.letter-spacing-1 { letter-spacing: 1px; }

.btn-action {
    transition: all 0.3s ease;
    border: none;
    background: var(--primary-color);
}
.btn-action:hover {
    transform: translateY(-3px);
    background: #e65b0e; /* Darker shade of orange */
}

.btn-hover-effect {
    transition: all 0.3s ease;
}
.btn-hover-effect:hover {
    transform: translateY(-3px);
    border-color: var(--primary-color) !important;
    background-color: #fff0e6 !important;
}
</style>

<?php include 'calificar_modal.php'; ?>

<script>
    (function(){
        // Scripts específicos del dashboard si fueran necesarios
    })();
</script>
