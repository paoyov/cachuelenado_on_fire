<?php
$title = 'Perfil del Maestro';
?>

<div class="container mt-4">
    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-body text-center">
                    <?php if (!empty($maestro['foto_perfil'])): ?>
                        <img src="<?php echo UPLOAD_URL . $maestro['foto_perfil']; ?>" alt="<?php echo htmlspecialchars($maestro['nombre_completo']); ?>" style="width: 200px; height: 200px; border-radius: 50%; object-fit: cover; margin-bottom: 1rem;">
                    <?php else: ?>
                        <i class="fas fa-user-circle" style="font-size: 10rem; color: var(--gray-color);"></i>
                    <?php endif; ?>
                    
                    <h2><?php echo htmlspecialchars($maestro['chapa'] ?: $maestro['nombre_completo']); ?></h2>
                    <p style="color: var(--gray-color);"><?php echo htmlspecialchars($maestro['nombre_completo']); ?></p>
                    
                    <div class="maestro-card-rating mb-3">
                        <i class="fas fa-star"></i>
                        <span style="font-size: 1.5rem; font-weight: 600;"><?php echo number_format($maestro['calificacion_promedio'], 1); ?></span>
                        <span style="color: var(--gray-color);">(<?php echo $maestro['total_calificaciones']; ?> calificaciones)</span>
                    </div>
                    
                    <div class="maestro-card-status status-<?php echo $maestro['disponibilidad']; ?> mb-3">
                        <i class="fas fa-circle"></i>
                        <?php 
                        $status = [
                            'disponible' => 'Disponible',
                            'ocupado' => 'Ocupado',
                            'no_disponible' => 'No disponible'
                        ];
                        echo $status[$maestro['disponibilidad']] ?? 'Disponible';
                        ?>
                    </div>
                    

                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Información</h3>
                </div>
                <div class="card-body">
                    <p><i class="fas fa-briefcase"></i> <strong>Experiencia:</strong> <?php echo $maestro['anios_experiencia']; ?> años</p>
                    <p><i class="fas fa-map-marker-alt"></i> <strong>Área:</strong> <?php echo htmlspecialchars($maestro['area_preferida'] ?: 'Lima'); ?></p>
                    <p><i class="fas fa-eye"></i> <strong>Vistas:</strong> <?php echo $maestro['total_vistas']; ?></p>
                    <?php if (!empty($maestro['telefono'])): ?>
                        <p><i class="fas fa-phone"></i> <strong>Teléfono:</strong> <?php echo htmlspecialchars($maestro['telefono']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($maestro['telefono'])): ?>
                        <?php 
                        $telefono_limpio = preg_replace('/[^0-9]/', '', $maestro['telefono']);
                        $whatsapp_url = "https://wa.me/51{$telefono_limpio}";
                        ?>
                        <p class="mt-3 mb-0">
                            <a href="<?php echo $whatsapp_url; ?>" target="_blank" class="btn btn-success btn-sm btn-block">
                                <i class="fab fa-whatsapp"></i> Contactar por WhatsApp
                            </a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="col-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Sobre el Maestro</h3>
                </div>
                <div class="card-body">
                    <p><?php echo nl2br(htmlspecialchars($maestro['descripcion'] ?: 'No hay descripción disponible.')); ?></p>
                </div>
            </div>
            
            <?php if (!empty($especialidades)): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Especialidades</h3>
                </div>
                <div class="card-body">
                    <div class="maestro-card-tags">
                        <?php foreach ($especialidades as $esp): ?>
                        <span class="tag tag-primary"><?php echo htmlspecialchars($esp['nombre']); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($portafolio)): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Portafolio</h3>
                </div>
                <div class="card-body">
                    <div class="portfolio-grid">
                        <?php foreach ($portafolio as $item): ?>
                        <div class="portfolio-item">
                            <img src="<?php echo UPLOAD_URL . $item['imagen']; ?>" alt="<?php echo htmlspecialchars($item['titulo']); ?>" onclick="openModal('<?php echo UPLOAD_URL . $item['imagen']; ?>', '<?php echo htmlspecialchars($item['titulo']); ?>')">
                            <?php if (!empty($item['titulo'])): ?>
                            <p style="margin-top: 0.5rem; font-size: 0.875rem; text-align: center;"><?php echo htmlspecialchars($item['titulo']); ?></p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($calificaciones)): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">Calificaciones y Comentarios</h3>
                </div>
                <div class="card-body">
                    <?php foreach ($calificaciones as $cal): ?>
                    <div class="rating-item mb-3 pb-3" style="border-bottom: 1px solid var(--light-color);">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <strong><?php echo htmlspecialchars($cal['nombre_completo']); ?></strong>
                                <div class="stars">
                                    <?php 
                                    $promedio = ($cal['puntualidad'] + $cal['calidad'] + $cal['trato'] + $cal['limpieza']) / 4;
                                    for ($i = 1; $i <= 5; $i++): 
                                    ?>
                                        <i class="fas fa-star <?php echo $i <= round($promedio) ? 'text-warning' : ''; ?>"></i>
                                    <?php endfor; ?>
                                    <span style="margin-left: 0.5rem; color: var(--gray-color);"><?php echo number_format($promedio, 1); ?></span>
                                </div>
                            </div>
                            <small style="color: var(--gray-color);"><?php echo formatDate($cal['fecha_calificacion']); ?></small>
                        </div>
                        <div style="font-size: 0.875rem; color: var(--gray-color); margin-bottom: 0.5rem;">
                            <span>Puntualidad: <?php echo $cal['puntualidad']; ?>/5</span> |
                            <span>Calidad: <?php echo $cal['calidad']; ?>/5</span> |
                            <span>Trato: <?php echo $cal['trato']; ?>/5</span> |
                            <span>Limpieza: <?php echo $cal['limpieza']; ?>/5</span>
                        </div>
                        <?php if (!empty($cal['comentario'])): ?>
                        <p style="margin: 0;"><?php echo nl2br(htmlspecialchars($cal['comentario'])); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para imágenes del portafolio -->
<div id="portfolioModal" class="modal" onclick="closeModal()">
    <span class="modal-close">&times;</span>
    <img class="modal-content" id="modalImage">
    <div id="modalCaption"></div>
</div>

<style>
.portfolio-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
}

.portfolio-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-radius: var(--border-radius);
    cursor: pointer;
    transition: var(--transition);
}

.portfolio-item img:hover {
    transform: scale(1.05);
    box-shadow: var(--shadow-lg);
}

.modal {
    display: none;
    position: fixed;
    z-index: 2000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.9);
    cursor: pointer;
}

.modal-content {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 900px;
    margin-top: 50px;
    animation: zoom 0.3s;
}

@keyframes zoom {
    from {transform: scale(0)}
    to {transform: scale(1)}
}

.modal-close {
    position: absolute;
    top: 15px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
}

.modal-close:hover {
    color: var(--primary-color);
}

#modalCaption {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 900px;
    text-align: center;
    color: #ccc;
    padding: 10px 0;
    height: 150px;
}
</style>

<script>
function openModal(src, title) {
    const modal = document.getElementById('portfolioModal');
    const modalImg = document.getElementById('modalImage');
    const captionText = document.getElementById('modalCaption');
    
    modal.style.display = 'block';
    modalImg.src = src;
    captionText.innerHTML = title;
}

function closeModal() {
    document.getElementById('portfolioModal').style.display = 'none';
}
</script>

