<?php
$title = 'Inicio';
?>

<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1>Encuentra el Maestro de Oficio que Necesitas</h1>
            <p>Conectamos clientes con maestros de oficio profesionales y de confianza en Lima</p>
            <div class="hero-buttons">
                <a href="<?php echo BASE_URL; ?>buscar" class="btn btn-primary btn-lg">Buscar Maestros</a>
                <a href="<?php echo BASE_URL; ?>register" class="btn btn-primary btn-lg">Soy Maestro</a>
            </div>
        </div>
    </div>
</section>

<!-- Sección de Video -->
<section class="video-section">
    <div class="container">
        <div class="video-wrapper">
            <div class="video-header">
                <h2 class="video-title">
                    <i class="fas fa-play-circle"></i> Conoce Nuestra Plataforma
                </h2>
                <p class="video-subtitle">Descubre cómo conectamos a los mejores maestros de oficio con clientes en Lima</p>
            </div>
            <div class="video-container">
                <div class="video-player-wrapper">
                    <video 
                        id="homeVideo" 
                        class="video-player" 
                        controls 
                        preload="metadata"
                        <?php if (file_exists(BASE_PATH . 'assets/images/video-poster.jpg')): ?>
                        poster="<?php echo BASE_URL; ?>assets/images/video-poster.jpg"
                        <?php endif; ?>
                        playsinline>
                        <source src="<?php echo BASE_URL; ?>assets/videos/video_con.mp4" type="video/mp4">
                        Tu navegador no soporta la reproducción de videos.
                    </video>
                    <div class="video-overlay">
                        <button class="video-play-btn" id="videoPlayBtn" aria-label="Reproducir video">
                            <i class="fas fa-play"></i>
                        </button>
                    </div>
                </div>
                <div class="video-info">
                    <div class="video-features">
                        <div class="video-feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Plataforma 100% segura</span>
                        </div>
                        <div class="video-feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Maestros verificados</span>
                        </div>
                        <div class="video-feature-item">
                            <i class="fas fa-check-circle"></i>
                            <span>Calificaciones reales</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="features">
    <div class="container">
        <h2 class="section-title">¿Por qué elegirnos?</h2>
        <div class="row">
            <div class="col-4">
                <div class="feature-card">
                    <i class="fas fa-shield-alt"></i>
                    <h3>Perfiles Verificados</h3>
                    <p>Todos los maestros son validados por nuestro equipo de administración</p>
                </div>
            </div>
            <div class="col-4">
                <div class="feature-card">
                    <i class="fas fa-star"></i>
                    <h3>Calificaciones Reales</h3>
                    <p>Lee las opiniones de otros clientes antes de contratar</p>
                </div>
            </div>
            <div class="col-4">
                <div class="feature-card">
                    <i class="fas fa-clock"></i>
                    <h3>Disponibilidad en Tiempo Real</h3>
                    <p>Ve quién está disponible ahora mismo para trabajar</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php if (!empty($maestros_destacados)): ?>
<section class="maestros-destacados">
    <div class="container">
        <h2 class="section-title">Maestros Destacados</h2>
        <div class="row">
            <?php foreach ($maestros_destacados as $maestro): ?>
            <div class="col-4">
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
                            <a href="<?php echo BASE_URL; ?>maestro/perfil?id=<?php echo $maestro['id']; ?>" class="btn btn-primary btn-sm">Ver Perfil</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?php echo BASE_URL; ?>buscar" class="btn btn-primary">Ver Todos los Maestros</a>
        </div>
    </div>
</section>
<?php endif; ?>

<section id="calificaciones" class="testimonials">
    <div class="container">
        <h2 class="section-title">Lo que dicen nuestros clientes</h2>
        
        <?php if (!empty($calificaciones_globales)): ?>
            <div class="row">
                <?php foreach ($calificaciones_globales as $cal): ?>
                <div class="col-4">
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="client-info">
                                <?php if (!empty($cal['cliente_foto'])): ?>
                                    <img src="<?php echo UPLOAD_URL . $cal['cliente_foto']; ?>" alt="<?php echo htmlspecialchars($cal['cliente_nombre']); ?>">
                                <?php else: ?>
                                    <div class="client-avatar-placeholder">
                                        <?php echo strtoupper(substr($cal['cliente_nombre'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                                <div>
                                    <h4><?php echo htmlspecialchars($cal['cliente_nombre']); ?></h4>
                                    <span>Cliente</span>
                                </div>
                            </div>
                            <div class="testimonial-rating">
                                <?php 
                                $promedio = round(($cal['puntualidad'] + $cal['calidad'] + $cal['trato'] + $cal['limpieza']) / 4);
                                for($i=1; $i<=5; $i++): 
                                ?>
                                    <i class="fas fa-star <?php echo $i <= $promedio ? 'active' : ''; ?>"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <div class="testimonial-body">
                            <?php if (!empty($cal['comentario'])): ?>
                                <p>"<?php echo htmlspecialchars($cal['comentario'], ENT_QUOTES, 'UTF-8'); ?>"</p>
                            <?php else: ?>
                                <p class="text-muted" style="font-style: italic;">Calificación sin comentario</p>
                            <?php endif; ?>
                        </div>
                        <div class="testimonial-footer">
                            <span class="date">
                                <i class="far fa-calendar-alt"></i> <?php echo formatDateTime($cal['fecha_calificacion']); ?>
                            </span>
                            <?php if (isset($cal['maestro_nombre']) && !empty(trim($cal['maestro_nombre']))): ?>
                            <div style="margin-top: 12px; font-size: 0.9rem; color: #555; text-align: center;">
                                <i class="fas fa-user-tie" style="color: var(--primary-color); margin-right: 5px;"></i>
                                Maestro: <strong style="color: var(--primary-color);"><?php echo htmlspecialchars($cal['maestro_nombre'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            </div>
                            <?php endif; ?>
                            <?php if (isset($cal['trabajo_titulo']) && !empty(trim($cal['trabajo_titulo']))): ?>
                            <div style="margin-top: 8px; font-size: 0.85rem; color: #666; text-align: center;">
                                <i class="fas fa-briefcase" style="margin-right: 5px;"></i>
                                Trabajo: <strong><?php echo htmlspecialchars($cal['trabajo_titulo'], ENT_QUOTES, 'UTF-8'); ?></strong>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <!-- Static Testimonials (Demo) -->
            <div class="row">
                <div class="col-4">
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="client-info">
                                <img src="https://ui-avatars.com/api/?name=Ana+Torres&background=random" alt="Ana Torres">
                                <div>
                                    <h4>Ana Torres</h4>
                                    <span>Cliente</span>
                                </div>
                            </div>
                            <div class="testimonial-rating">
                                <i class="fas fa-star active"></i>
                                <i class="fas fa-star active"></i>
                                <i class="fas fa-star active"></i>
                                <i class="fas fa-star active"></i>
                                <i class="fas fa-star active"></i>
                            </div>
                        </div>
                        <div class="testimonial-body">
                            <p>"Excelente servicio, el maestro llegó muy puntual y realizó el trabajo de gasfitería de manera impecable. Totalmente recomendado."</p>
                        </div>
                        <div class="testimonial-footer">
                            <span class="date"><i class="far fa-calendar-alt"></i> 20 Nov 2023</span>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="client-info">
                                <img src="https://ui-avatars.com/api/?name=Roberto+Diaz&background=random" alt="Roberto Diaz">
                                <div>
                                    <h4>Roberto Diaz</h4>
                                    <span>Cliente</span>
                                </div>
                            </div>
                            <div class="testimonial-rating">
                                <i class="fas fa-star active"></i>
                                <i class="fas fa-star active"></i>
                                <i class="fas fa-star active"></i>
                                <i class="fas fa-star active"></i>
                                <i class="fas fa-star-half-alt active"></i>
                            </div>
                        </div>
                        <div class="testimonial-body">
                            <p>"Muy buen electricista, solucionó un problema complejo en mi instalación. El precio fue justo y el trato muy cordial."</p>
                        </div>
                        <div class="testimonial-footer">
                            <span class="date"><i class="far fa-calendar-alt"></i> 18 Nov 2023</span>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="client-info">
                                <img src="https://ui-avatars.com/api/?name=Carla+Mendez&background=random" alt="Carla Mendez">
                                <div>
                                    <h4>Carla Mendez</h4>
                                    <span>Cliente</span>
                                </div>
                            </div>
                            <div class="testimonial-rating">
                                <i class="fas fa-star active"></i>
                                <i class="fas fa-star active"></i>
                                <i class="fas fa-star active"></i>
                                <i class="fas fa-star active"></i>
                                <i class="fas fa-star active"></i>
                            </div>
                        </div>
                        <div class="testimonial-body">
                            <p>"Contraté un servicio de limpieza y quedé encantada. Todo quedó reluciente. Definitivamente volveré a usar la plataforma."</p>
                        </div>
                        <div class="testimonial-footer">
                            <span class="date"><i class="far fa-calendar-alt"></i> 15 Nov 2023</span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.hero {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: var(--white);
    padding: 5rem 0;
    text-align: center;
}

.hero-content h1 {
    font-size: 3rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.hero-content p {
    font-size: 1.25rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-lg {
    padding: 1rem 2rem;
    font-size: 1.1rem;
}

.features {
    padding: 4rem 0;
    background: var(--white);
}

.section-title {
    text-align: center;
    font-size: 2.5rem;
    margin-bottom: 3rem;
    color: var(--dark-color);
}

.feature-card {
    text-align: center;
    padding: 2rem;
}

.feature-card i {
    font-size: 3rem;
    color: var(--primary-color);
    margin-bottom: 1rem;
}

.feature-card h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
    color: var(--dark-color);
}

.feature-card p {
    color: var(--gray-color);
    line-height: 1.8;
}

.maestros-destacados {
    padding: 4rem 0;
    background: var(--light-color);
}

.maestros-destacados .row {
    margin: 0 -20px;
}

.maestros-destacados .col-4 {
    padding: 0 20px;
    margin-bottom: 2.5rem;
}

.maestros-destacados .maestro-card {
    margin-bottom: 0;
}

@media (max-width: 768px) {
    .hero-content h1 {
        font-size: 2rem;
    }
    
    .hero-content p {
        font-size: 1rem;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .testimonials .section-title {
        font-size: 2.25rem;
        margin-bottom: 3rem;
    }
    
    .testimonials {
        padding: 3rem 0;
    }
}

.testimonials {
    padding: 5rem 0;
    background: var(--white);
}

.testimonials .section-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 4rem;
    color: var(--dark-color);
    letter-spacing: -0.02em;
    line-height: 1.2;
}

.testimonials .row {
    margin-top: 2rem;
}

.testimonial-card {
    background: #fff;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transition: transform 0.3s ease;
    height: 100%;
    border: 1px solid #eee;
}

.testimonial-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.testimonial-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
}

.client-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.client-info img {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
}

.client-avatar-placeholder {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
}

.client-info h4 {
    font-size: 1rem;
    margin: 0;
    color: var(--dark-color);
}

.client-info span {
    font-size: 0.8rem;
    color: #777;
}

.testimonial-rating {
    color: #ffc107;
    font-size: 0.9rem;
}

.testimonial-rating .fas:not(.active) {
    color: #ddd;
}

.testimonial-body {
    text-align: center;
}

.testimonial-body p {
    color: #555;
    font-style: italic;
    line-height: 1.6;
    margin-bottom: 1rem;
    text-align: center;
}

.testimonial-footer {
    border-top: 1px solid #f0f0f0;
    padding-top: 0.8rem;
    text-align: center;
}

.testimonial-footer .date {
    font-size: 0.8rem;
    color: #999;
    display: block;
    margin-bottom: 8px;
}

/* ============================================
   Sección de Video
   ============================================ */
.video-section {
    padding: 5rem 0;
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    position: relative;
    overflow: hidden;
}

.video-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: 
        radial-gradient(circle at 20% 50%, rgba(255, 107, 53, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 80% 80%, rgba(255, 107, 53, 0.05) 0%, transparent 50%);
    pointer-events: none;
}

.video-wrapper {
    position: relative;
    z-index: 1;
}

.video-header {
    text-align: center;
    margin-bottom: 3rem;
}

.video-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--dark-color);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
}

.video-title i {
    color: var(--primary-color);
    font-size: 2.5rem;
    animation: pulse-video-icon 2s ease infinite;
}

@keyframes pulse-video-icon {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

.video-subtitle {
    font-size: 1.2rem;
    color: var(--gray-color);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.video-container {
    max-width: 1000px;
    margin: 0 auto;
}

.video-player-wrapper {
    position: relative;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    background: #000;
    margin-bottom: 2rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.video-player-wrapper:hover {
    transform: translateY(-5px);
    box-shadow: 0 25px 70px rgba(0, 0, 0, 0.2);
}

.video-player {
    width: 100%;
    height: auto;
    display: block;
    border-radius: 20px;
    outline: none;
    background: #000;
}

.video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 20px;
    opacity: 0;
    transition: opacity 0.3s ease;
    pointer-events: none; /* Siempre none para no bloquear controles */
    z-index: 1;
}

.video-player-wrapper.paused .video-overlay {
    opacity: 1;
}

/* Asegurar que los controles del video estén siempre accesibles */
.video-player {
    position: relative;
    z-index: 2;
}

.video-player::-webkit-media-controls-panel {
    z-index: 3;
}

/* El overlay siempre tiene pointer-events: none para no bloquear controles */
/* Solo el botón de play tiene pointer-events cuando está pausado */
.video-player-wrapper.paused .video-play-btn {
    pointer-events: auto;
}

.video-play-btn {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 107, 53, 0.95);
    border: 4px solid white;
    color: white;
    font-size: 2rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(255, 107, 53, 0.4);
    z-index: 2;
    pointer-events: auto;
}

.video-play-btn:hover {
    background: var(--primary-color);
    transform: scale(1.1);
    box-shadow: 0 15px 40px rgba(255, 107, 53, 0.6);
}

.video-play-btn:active {
    transform: scale(0.95);
}

.video-info {
    text-align: center;
}

.video-features {
    display: flex;
    justify-content: center;
    gap: 3rem;
    flex-wrap: wrap;
    padding: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
}

.video-feature-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.1rem;
    color: var(--dark-color);
    font-weight: 500;
}

.video-feature-item i {
    color: var(--primary-color);
    font-size: 1.3rem;
}

/* Responsive */
@media (max-width: 768px) {
    .video-section {
        padding: 3rem 0;
    }
    
    .video-title {
        font-size: 2rem;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .video-title i {
        font-size: 2rem;
    }
    
    .video-subtitle {
        font-size: 1rem;
        padding: 0 1rem;
    }
    
    .video-player-wrapper {
        border-radius: 15px;
        margin: 0 1rem 2rem 1rem;
    }
    
    .video-player {
        border-radius: 15px;
    }
    
    .video-overlay {
        border-radius: 15px;
    }
    
    .video-play-btn {
        width: 70px;
        height: 70px;
        font-size: 1.8rem;
    }
    
    .video-features {
        flex-direction: column;
        gap: 1.5rem;
        padding: 1.5rem;
        margin: 0 1rem;
    }
    
    .video-feature-item {
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .video-title {
        font-size: 1.75rem;
    }
    
    .video-subtitle {
        font-size: 0.95rem;
    }
    
    .video-play-btn {
        width: 60px;
        height: 60px;
        font-size: 1.5rem;
    }
}
</style>

<script>
// Control del video
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('homeVideo');
    const playBtn = document.getElementById('videoPlayBtn');
    const wrapper = document.querySelector('.video-player-wrapper');
    
    if (video && playBtn && wrapper) {
        // Inicializar estado
        if (video.paused) {
            wrapper.classList.add('paused');
        } else {
            wrapper.classList.remove('paused');
        }
        
        // Mostrar overlay cuando el video está pausado
        video.addEventListener('play', function() {
            wrapper.classList.remove('paused');
        });
        
        video.addEventListener('pause', function() {
            wrapper.classList.add('paused');
        });
        
        // Control del botón de play personalizado
        playBtn.addEventListener('click', function(e) {
            e.stopPropagation(); // Evitar que el click llegue al video
            if (video.paused) {
                video.play();
            } else {
                video.pause();
            }
        });
        
        // Actualizar icono del botón según el estado
        video.addEventListener('play', function() {
            playBtn.innerHTML = '<i class="fas fa-pause"></i>';
        });
        
        video.addEventListener('pause', function() {
            playBtn.innerHTML = '<i class="fas fa-play"></i>';
        });
        
        // Asegurar que los controles nativos siempre funcionen
        video.addEventListener('loadedmetadata', function() {
            // Forzar que los controles sean visibles
            video.controls = true;
        });
    }
});
</script>

<!-- Chatbot Script -->
<script src="<?php echo asset('js/chatbot.js'); ?>"></script>

