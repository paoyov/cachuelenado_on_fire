<?php
$title = 'Mis Calificaciones';
?>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-star text-warning"></i> Mis Calificaciones</h1>
        <p class="text-muted">Gestiona y visualiza las calificaciones de tus servicios</p>
    </div>
</div>

<div class="container">

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Sección de Registro de Nueva Calificación -->
    <div class="card mb-4">
        <div class="card-header">
            <h3><i class="fas fa-pen-alt"></i> Calificar un Servicio</h3>
        </div>
        <div class="card-body">
            <p>¿Recibiste un servicio recientemente? ¡Califícalo aquí!</p>
            <button class="btn btn-primary" onclick="openRatingModal()">
                <i class="fas fa-plus"></i> Nueva Calificación
            </button>
        </div>
    </div>

    <!-- Sección de Calificaciones Recientes -->
    <h3 class="mb-3">Calificaciones Recientes</h3>
    <div class="ratings-grid">
        <?php if (empty($calificaciones)): ?>
            <div class="col-12">
                <p class="text-muted text-center">No has realizado ninguna calificación aún.</p>
            </div>
        <?php else: ?>
            <?php foreach ($calificaciones as $cal): ?>
            <div class="rating-card">
                <div class="rating-header">
                    <div class="maestro-info">
                        <?php 
                            // Construct valid image URL
                            $fotoUrl = 'https://ui-avatars.com/api/?name=' . urlencode($cal['maestro_nombre']);
                            if (!empty($cal['maestro_foto'])) {
                                // Check if it's already a full URL or needs base
                                if (filter_var($cal['maestro_foto'], FILTER_VALIDATE_URL)) {
                                    $fotoUrl = $cal['maestro_foto'];
                                } else {
                                    // Use UPLOAD_URL if it's a relative path stored by the system
                                    // Assuming stored as 'perfiles/filename.jpg'
                                    $fotoUrl = UPLOAD_URL . $cal['maestro_foto'];
                                }
                            }
                        ?>
                        <img src="<?php echo $fotoUrl; ?>" alt="Maestro" class="maestro-img" onerror="this.src='https://ui-avatars.com/api/?name=<?php echo urlencode($cal['maestro_nombre']); ?>'">
                        <div>
                            <h4><?php echo htmlspecialchars($cal['maestro_nombre']); ?></h4>
                            <span class="service-type"><?php echo htmlspecialchars($cal['especialidad'] ?? 'General'); ?></span>
                        </div>
                    </div>
                    <div class="rating-score">
                        <?php 
                            $promedio = ($cal['puntualidad'] + $cal['calidad'] + $cal['trato'] + $cal['limpieza']) / 4; 
                        ?>
                        <span class="score"><?php echo number_format($promedio, 1); ?></span>
                        <div class="stars">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <i class="<?php echo $i <= round($promedio) ? 'fas' : 'far'; ?> fa-star"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
                <div class="rating-body">
                    <?php if (!empty($cal['comentario'])): ?>
                        <p class="comentario">"<?php echo htmlspecialchars($cal['comentario']); ?>"</p>
                    <?php endif; ?>
                    <div class="rating-date">
                        <i class="far fa-calendar-alt"></i> <?php echo date('d M Y', strtotime($cal['fecha_calificacion'])); ?>
                    </div>
                </div>
                <div class="rating-footer">
                    <div class="rating-breakdown">
                        <span title="Puntualidad"><i class="far fa-clock"></i> <?php echo number_format($cal['puntualidad'], 1); ?></span>
                        <span title="Calidad"><i class="fas fa-tools"></i> <?php echo number_format($cal['calidad'], 1); ?></span>
                        <span title="Trato"><i class="far fa-smile"></i> <?php echo number_format($cal['trato'], 1); ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Incluir el modal de calificación existente -->
<?php include 'calificar_modal.php'; ?>

<style>
.page-header {
    margin-bottom: 2rem;
    border-bottom: 1px solid #eee;
    padding-bottom: 1rem;
}

.ratings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1.5rem;
}

.rating-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    padding: 1.5rem;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid #f0f0f0;
}

.rating-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.rating-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.maestro-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.maestro-img {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.maestro-info h4 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #333;
}

.service-type {
    font-size: 0.85rem;
    color: #666;
    background: #f8f9fa;
    padding: 2px 8px;
    border-radius: 12px;
}

.rating-score {
    text-align: right;
}

.score {
    font-size: 1.2rem;
    font-weight: 700;
    color: #ff6b35;
    display: block;
}

.stars {
    color: #ffc107;
    font-size: 0.8rem;
}

.rating-body {
    margin-bottom: 1rem;
}

.comentario {
    font-style: italic;
    color: #555;
    font-size: 0.95rem;
    line-height: 1.5;
    margin-bottom: 0.5rem;
}

.rating-date {
    font-size: 0.8rem;
    color: #999;
    text-align: right;
}

.rating-footer {
    border-top: 1px solid #f0f0f0;
    padding-top: 1rem;
}

.rating-breakdown {
    display: flex;
    justify-content: space-around;
    font-size: 0.85rem;
    color: #666;
}

.rating-breakdown span {
    display: flex;
    align-items: center;
    gap: 5px;
}

.rating-breakdown i {
    color: #ff6b35;
}


</style>

<!-- Modal trigger mock function -->
<script>
    // Pass Maestros Data from PHP to JS
    // Ensure it's a valid array even if PHP returns null/false
    // maestrosData is already defined in calificar_modal.php

    function openRatingModal() {
        try {
            const select = document.getElementById('maestro-select');
            if (!select) {
                console.error('Element #maestro-select not found');
                alert('Error: No se encontró el selector de maestros.');
                return;
            }

            select.innerHTML = '<option value="">-- Buscar Maestro --</option>';
            
            if (!Array.isArray(maestrosData)) {
                 console.error('maestrosData is not an array', maestrosData);
                 alert('Error de datos: No se pudieron cargar los maestros.');
                 return;
            }

            // Populate Dropdown
            maestrosData.forEach(m => {
                const option = document.createElement('option');
                option.value = m.id;
                const name = m.nombre_completo || 'Maestro sin nombre';
                option.textContent = name;
                
                option.setAttribute('data-name', name);
                // Handle potential missing foto_perfil
                let imgUrl = 'https://ui-avatars.com/api/?name=' + encodeURIComponent(name);
                if (m.foto_perfil) {
                    // Check if absolute URL
                    if (m.foto_perfil.startsWith('http')) {
                        imgUrl = m.foto_perfil;
                    } else {
                        imgUrl = '<?php echo UPLOAD_URL; ?>' + m.foto_perfil;
                    }
                }
                option.setAttribute('data-img', imgUrl);
                
                select.appendChild(option);
            });
            
            // Reset Fields
            const maestroIdInput = document.getElementById('rating-maestro-id');
            const trabajoIdInput = document.getElementById('rating-trabajo-id');
            const form = document.getElementById('ratingForm');
            const containerSelect = document.getElementById('maestro-selector-container');
            const containerInfo = document.getElementById('maestro-info-container');
            const modal = document.getElementById('ratingModal');

            if(maestroIdInput) maestroIdInput.value = '';
            if(trabajoIdInput) trabajoIdInput.value = '';
            if(form) form.reset();
            
            // Show Select, Hide Static Info
            if(containerSelect) containerSelect.style.display = 'block';
            if(containerInfo) containerInfo.style.display = 'none';
            
            if(modal) {
                // Using flex to ensure the CSS centering works correctly
                modal.style.display = 'flex';
            } else {
                alert('Error: No se encontró el modal.');
            }

        } catch (e) {
            console.error('Error opening modal:', e);
            alert('Ocurrió un error al abrir el formulario: ' + e.message);
        }
    }
</script>
