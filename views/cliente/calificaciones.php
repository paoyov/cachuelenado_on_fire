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

    <!-- Sección de Calificaciones Recientes (Estáticas) -->
    <h3 class="mb-3">Calificaciones Recientes</h3>
    <div class="ratings-grid">
        <!-- Card Estática 1 -->
        <div class="rating-card">
            <div class="rating-header">
                <div class="maestro-info">
                    <img src="https://ui-avatars.com/api/?name=Juan+Perez&background=random" alt="Maestro" class="maestro-img">
                    <div>
                        <h4>Juan Perez</h4>
                        <span class="service-type">Gasfitería</span>
                    </div>
                </div>
                <div class="rating-score">
                    <span class="score">4.8</span>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
            <div class="rating-body">
                <p class="comentario">"Excelente trabajo, llegó puntual y solucionó el problema de la fuga rápidamente. Muy recomendado."</p>
                <div class="rating-date">
                    <i class="far fa-calendar-alt"></i> 24 Nov 2023
                </div>
            </div>
            <div class="rating-footer">
                <div class="rating-breakdown">
                    <span title="Puntualidad"><i class="far fa-clock"></i> 5.0</span>
                    <span title="Calidad"><i class="fas fa-tools"></i> 5.0</span>
                    <span title="Trato"><i class="far fa-smile"></i> 4.0</span>
                </div>
            </div>
        </div>

        <!-- Card Estática 2 -->
        <div class="rating-card">
            <div class="rating-header">
                <div class="maestro-info">
                    <img src="https://ui-avatars.com/api/?name=Maria+Lopez&background=random" alt="Maestro" class="maestro-img">
                    <div>
                        <h4>Maria Lopez</h4>
                        <span class="service-type">Limpieza</span>
                    </div>
                </div>
                <div class="rating-score">
                    <span class="score">5.0</span>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
            </div>
            <div class="rating-body">
                <p class="comentario">"Impecable servicio. Dejó todo reluciente y fue muy amable en todo momento. Volveré a contratarla."</p>
                <div class="rating-date">
                    <i class="far fa-calendar-alt"></i> 20 Nov 2023
                </div>
            </div>
            <div class="rating-footer">
                <div class="rating-breakdown">
                    <span title="Puntualidad"><i class="far fa-clock"></i> 5.0</span>
                    <span title="Calidad"><i class="fas fa-tools"></i> 5.0</span>
                    <span title="Trato"><i class="far fa-smile"></i> 5.0</span>
                </div>
            </div>
        </div>

        <!-- Card Estática 3 -->
        <div class="rating-card">
            <div class="rating-header">
                <div class="maestro-info">
                    <img src="https://ui-avatars.com/api/?name=Carlos+Ruiz&background=random" alt="Maestro" class="maestro-img">
                    <div>
                        <h4>Carlos Ruiz</h4>
                        <span class="service-type">Electricidad</span>
                    </div>
                </div>
                <div class="rating-score">
                    <span class="score">4.5</span>
                    <div class="stars">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
            </div>
            <div class="rating-body">
                <p class="comentario">"Buen electricista, sabe lo que hace. Un poco serio pero muy profesional en su trabajo."</p>
                <div class="rating-date">
                    <i class="far fa-calendar-alt"></i> 15 Nov 2023
                </div>
            </div>
            <div class="rating-footer">
                <div class="rating-breakdown">
                    <span title="Puntualidad"><i class="far fa-clock"></i> 4.0</span>
                    <span title="Calidad"><i class="fas fa-tools"></i> 5.0</span>
                    <span title="Trato"><i class="far fa-smile"></i> 4.0</span>
                </div>
            </div>
        </div>
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

/* Modal trigger mock function */
<script>
function openRatingModal() {
    // En una implementación real, esto abriría el modal con datos pre-cargados o vacíos
    // Como estamos reutilizando el modal existente que espera datos de un trabajo,
    // aquí solo mostramos el modal para propósitos de demostración visual.
    document.getElementById('ratingModal').style.display = 'block';
    
    // Mock data para visualización
    document.getElementById('rating-master-name').textContent = 'Maestro Ejemplo';
    document.getElementById('rating-master-img').src = 'https://ui-avatars.com/api/?name=Maestro+Ejemplo';
}
</script>
</style>
