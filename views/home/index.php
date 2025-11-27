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
                <a href="<?php echo BASE_URL; ?>register" class="btn btn-outline btn-lg">Soy Maestro</a>
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
                            <p>"<?php echo htmlspecialchars($cal['comentario']); ?>"</p>
                        </div>
                        <div class="testimonial-footer">
                            <span class="date">
                                <i class="far fa-calendar-alt"></i> <?php echo formatDateTime($cal['fecha_calificacion']); ?>
                            </span>
                            <div style="margin-top: 5px; font-size: 0.85rem; color: #666;">
                                Trabajo: <strong><?php echo htmlspecialchars($cal['trabajo_titulo']); ?></strong>
                            </div>
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
}

.testimonials {
    padding: 4rem 0;
    background: var(--white);
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

.testimonial-body p {
    color: #555;
    font-style: italic;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.testimonial-footer {
    border-top: 1px solid #f0f0f0;
    padding-top: 0.8rem;
    text-align: right;
}

.testimonial-footer .date {
    font-size: 0.8rem;
    color: #999;
}
</style>

