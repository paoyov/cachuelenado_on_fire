<?php
$title = 'Buscar Maestros';
?>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-search"></i> Buscar Maestros</h1>
        <p>Encuentra el profesional que necesitas</p>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filtros de Búsqueda</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?php echo BASE_URL; ?>buscar">
                        <div class="form-group">
                            <label for="especialidad" class="form-label">Especialidad</label>
                            <select name="especialidad" id="especialidad" class="form-control form-select">
                                <option value="">Todas</option>
                                <?php foreach ($especialidades as $esp): ?>
                                <option value="<?php echo $esp['id']; ?>" <?php echo (isset($filters['especialidad_id']) && $filters['especialidad_id'] == $esp['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($esp['nombre']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="distrito" class="form-label">Distrito</label>
                            <select name="distrito" id="distrito" class="form-control form-select">
                                <option value="">Todos</option>
                                <?php foreach ($distritos as $dist): ?>
                                <option value="<?php echo $dist['id']; ?>" <?php echo (isset($filters['distrito_id']) && $filters['distrito_id'] == $dist['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($dist['nombre']); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="calificacion" class="form-label">Calificación Mínima</label>
                            <select name="calificacion" id="calificacion" class="form-control form-select">
                                <option value="">Todas</option>
                                <option value="4.5" <?php echo (isset($filters['calificacion_minima']) && $filters['calificacion_minima'] == '4.5') ? 'selected' : ''; ?>>4.5+</option>
                                <option value="4.0" <?php echo (isset($filters['calificacion_minima']) && $filters['calificacion_minima'] == '4.0') ? 'selected' : ''; ?>>4.0+</option>
                                <option value="3.5" <?php echo (isset($filters['calificacion_minima']) && $filters['calificacion_minima'] == '3.5') ? 'selected' : ''; ?>>3.5+</option>
                                <option value="3.0" <?php echo (isset($filters['calificacion_minima']) && $filters['calificacion_minima'] == '3.0') ? 'selected' : ''; ?>>3.0+</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="disponibilidad" class="form-label">Disponibilidad</label>
                            <select name="disponibilidad" id="disponibilidad" class="form-control form-select">
                                <option value="">Todas</option>
                                <option value="disponible" <?php echo (isset($filters['disponibilidad']) && $filters['disponibilidad'] == 'disponible') ? 'selected' : ''; ?>>Disponible</option>
                                <option value="ocupado" <?php echo (isset($filters['disponibilidad']) && $filters['disponibilidad'] == 'ocupado') ? 'selected' : ''; ?>>Ocupado</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        
                        <a href="<?php echo BASE_URL; ?>buscar" class="btn btn-outline btn-block mt-2">
                            <i class="fas fa-redo"></i> Limpiar Filtros
                        </a>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-9">
            <?php if (!empty($resultados)): ?>
                <div class="results-header mb-3">
                    <h2>Resultados de Búsqueda (<?php echo count($resultados); ?>)</h2>
                </div>
                
                <div class="row">
                    <?php foreach ($resultados as $maestro): ?>
                    <div class="col-6 mb-3">
                        <div class="maestro-card">
                            <div class="maestro-card-image">
                                <?php if (!empty($maestro['foto_perfil'])): ?>
                                    <img src="<?php echo UPLOAD_URL . $maestro['foto_perfil']; ?>" alt="<?php echo htmlspecialchars($maestro['nombre_completo']); ?>">
                                <?php else: ?>
                                    <i class="fas fa-user"></i>
                                <?php endif; ?>
                            </div>
                            <div class="maestro-card-body">
                                <div class="maestro-card-header">
                                    <h3 class="maestro-card-title"><?php echo htmlspecialchars($maestro['chapa'] ?: $maestro['nombre_completo']); ?></h3>
                                    <div class="maestro-card-rating">
                                        <i class="fas fa-star"></i>
                                        <span><?php echo number_format($maestro['calificacion_promedio'], 1); ?></span>
                                    </div>
                                </div>
                                <div class="maestro-card-info">
                                    <p><i class="fas fa-briefcase"></i> <?php echo $maestro['anios_experiencia']; ?> años de experiencia</p>
                                    <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($maestro['area_preferida'] ?: 'Lima'); ?></p>
                                    <?php if (!empty($maestro['telefono'])): ?>
                                        <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($maestro['telefono']); ?></p>
                                    <?php endif; ?>
                                </div>
                                <div class="maestro-card-footer">
                                    <span class="maestro-card-status status-<?php echo $maestro['disponibilidad']; ?>">
                                        <i class="fas fa-circle"></i>
                                        <?php 
                                        $status = [
                                            'disponible' => 'Disponible',
                                            'ocupado' => 'Ocupado',
                                            'no_disponible' => 'No disponible'
                                        ];
                                        echo $status[$maestro['disponibilidad']] ?? 'Disponible';
                                        ?>
                                    </span>
                                    <div class="maestro-card-actions">
                                        <?php if (!empty($maestro['telefono'])): ?>
                                            <?php 
                                            $telefono_limpio = preg_replace('/[^0-9]/', '', $maestro['telefono']);
                                            $whatsapp_url = "https://wa.me/51{$telefono_limpio}";
                                            ?>
                                            <a href="<?php echo $whatsapp_url; ?>" target="_blank" class="btn btn-success btn-sm" title="Contactar por WhatsApp">
                                                <i class="fab fa-whatsapp"></i> Contactar
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?php echo BASE_URL; ?>maestro/perfil?id=<?php echo $maestro['id']; ?>" class="btn btn-primary btn-sm">Ver Perfil</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <?php if ($isSearch): ?>
                    <!-- Se mostrará la alerta mediante JavaScript -->
                <?php else: ?>
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-search" style="font-size: 4rem; color: var(--gray-color); margin-bottom: 1rem;"></i>
                            <h3>Busca maestros de oficio</h3>
                            <p>Utiliza los filtros para encontrar el profesional que necesitas</p>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.results-header h2 {
    font-size: 1.5rem;
    color: var(--dark-color);
}

/* Asegurar que los cards se muestren completos */
.row .col-6 {
    margin-bottom: 1.5rem;
}

.row .col-6 .maestro-card {
    height: auto;
    min-height: auto;
}

/* Asegurar que las imágenes no se corten */
.maestro-card-image {
    flex-shrink: 0;
}

</style>

<!-- Modal de Alerta Profesional -->
<div id="infoAlertModal" class="modal-alert" style="display: none;" onclick="if(event.target === this) closeInfoAlertModal()">
    <div class="modal-alert-content" onclick="event.stopPropagation()">
        <div class="modal-alert-header">
            <button type="button" class="modal-alert-close" onclick="closeInfoAlertModal()">
                <i class="fas fa-times"></i>
            </button>
            <div class="modal-alert-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
        <div class="modal-alert-body">
            <h3 class="modal-alert-title">Advertencia</h3>
            <p class="modal-alert-message" id="infoAlertMessage"></p>
        </div>
        <div class="modal-alert-footer">
            <button type="button" class="btn btn-primary" onclick="closeInfoAlertModal()">
                <i class="fas fa-check"></i> Entendido
            </button>
        </div>
    </div>
</div>

<style>
/* ============================================
   Modal de Alerta Profesional
   ============================================ */

.modal-alert {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 10000;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.modal-alert-content {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    max-width: 450px;
    width: 90%;
    overflow: hidden;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-alert-header {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem 1.5rem 1.5rem;
    background: linear-gradient(135deg, #FF6B35 0%, #FF8C5A 100%);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    position: relative;
}

.modal-alert-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    margin: 0 auto;
    position: relative;
}

.modal-alert-icon i {
    font-size: 2rem;
    color: #ffc107;
}

.modal-alert-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(255, 255, 255, 0.2);
    border: none;
    font-size: 1.25rem;
    color: #fff;
    cursor: pointer;
    width: 35px;
    height: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.modal-alert-close:hover {
    background: rgba(255, 255, 255, 0.3);
    color: #fff;
    transform: rotate(90deg);
}

.modal-alert-body {
    padding: 2rem 1.5rem 1.5rem;
    text-align: center;
    padding-top: 1.5rem;
}

.modal-alert-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: #212529;
    margin: 0 0 1rem 0;
}

.modal-alert-message {
    font-size: 1rem;
    color: #6c757d;
    line-height: 1.6;
    margin: 0;
}

.modal-alert-footer {
    padding: 1rem 1.5rem 1.5rem;
    display: flex;
    justify-content: center;
    gap: 1rem;
}

.modal-alert-footer .btn {
    min-width: 120px;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.modal-alert-footer .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Variantes de color según el tipo */
.modal-alert[data-type="warning"] .modal-alert-header {
    background: linear-gradient(135deg, #FF6B35 0%, #FF8C5A 100%);
}

.modal-alert[data-type="warning"] .modal-alert-icon i {
    color: #ffc107;
}

.modal-alert[data-type="error"] .modal-alert-header {
    background: linear-gradient(135deg, #dc3545 0%, #e85d75 100%);
}

.modal-alert[data-type="error"] .modal-alert-icon i {
    color: #dc3545;
}

.modal-alert[data-type="success"] .modal-alert-header {
    background: linear-gradient(135deg, #28a745 0%, #48c765 100%);
}

.modal-alert[data-type="success"] .modal-alert-icon i {
    color: #28a745;
}

.modal-alert[data-type="info"] .modal-alert-header {
    background: linear-gradient(135deg, #17a2b8 0%, #3db8d1 100%);
}

.modal-alert[data-type="info"] .modal-alert-icon i {
    color: #17a2b8;
}

/* Responsive */
@media (max-width: 576px) {
    .modal-alert-content {
        width: 95%;
        max-width: none;
    }
    
    .modal-alert-body {
        padding: 1.5rem 1rem 1rem;
    }
    
    .modal-alert-title {
        font-size: 1.25rem;
    }
    
    .modal-alert-message {
        font-size: 0.9rem;
    }
}
</style>

<script>
// Función para mostrar alerta profesional
function showInfoAlert(message, type = 'info') {
    const modal = document.getElementById('infoAlertModal');
    const messageEl = document.getElementById('infoAlertMessage');
    const iconEl = modal.querySelector('.modal-alert-icon i');
    const titleEl = modal.querySelector('.modal-alert-title');
    
    if (!modal || !messageEl) return;
    
    // Configurar icono y color según el tipo
    const config = {
        'info': { icon: 'fa-info-circle', title: 'Información', color: '#17a2b8' },
        'warning': { icon: 'fa-exclamation-triangle', title: 'Advertencia', color: '#ffc107' },
        'error': { icon: 'fa-times-circle', title: 'Error', color: '#dc3545' },
        'success': { icon: 'fa-check-circle', title: 'Éxito', color: '#28a745' }
    };
    
    const alertConfig = config[type] || config['info'];
    
    iconEl.className = 'fas ' + alertConfig.icon;
    iconEl.style.color = alertConfig.color;
    titleEl.textContent = alertConfig.title;
    messageEl.textContent = message;
    
    modal.style.display = 'flex';
}

function closeInfoAlertModal() {
    const modal = document.getElementById('infoAlertModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('infoAlertModal');
        if (modal && modal.style.display === 'flex') {
            closeInfoAlertModal();
        }
    }
});

// Verificar si hay resultados después de una búsqueda
<?php if ($isSearch && empty($resultados)): ?>
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar alerta cuando no hay resultados después de una búsqueda
    showInfoAlert(
        'No se encontraron maestros que coincidan con tus criterios de búsqueda. Solo se muestran maestros con pago activo y vigente. Por favor, intenta ajustar los filtros o intenta más tarde.',
        'warning'
    );
});
<?php endif; ?>
</script>

