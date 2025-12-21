<?php if (!empty($calificaciones_globales)): ?>
<section class="testimonials" id="calificaciones">
    <div class="container">
        <h2 class="section-title">Lo que dicen nuestros clientes</h2>
        <div class="row">
            <?php foreach ($calificaciones_globales as $calificacion): ?>
            <div class="col-4">
                <div class="testimonial-card">
                    <div class="testimonial-header">
                        <div class="client-info">
                            <?php if (!empty($calificacion['cliente_foto'])): ?>
                                <img src="<?php echo UPLOAD_URL . $calificacion['cliente_foto']; ?>" alt="Cliente">
                            <?php else: ?>
                                <div class="client-avatar-placeholder">
                                    <?php echo strtoupper(substr($calificacion['cliente_nombre'], 0, 1)); ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h4><?php echo htmlspecialchars($calificacion['cliente_nombre']); ?></h4>
                                <span class="text-muted">Sobre <?php echo htmlspecialchars($calificacion['maestro_nombre']); ?></span>
                            </div>
                        </div>
                        <div class="testimonial-rating">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <i class="fas fa-star <?php echo $i <= $calificacion['calidad'] ? 'active' : ''; ?>"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <div class="testimonial-body">
                        <p>"<?php echo htmlspecialchars($calificacion['comentario'] ?: 'Excelente servicio, muy recomendado.'); ?>"</p>
                    </div>
                    <div class="testimonial-footer">
                        <span class="date"><?php echo date('d/m/Y', strtotime($calificacion['fecha_calificacion'])); ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
.testimonials {
    padding: 5rem 0;
    background: var(--white);
}

.testimonials .section-title {
    text-align: center;
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

/* Responsive para testimonials */
@media (max-width: 768px) {
    .testimonials .section-title {
        font-size: 2.25rem;
        margin-bottom: 3rem;
    }
    
    .testimonials {
        padding: 3rem 0;
    }
}
</style>
<?php endif; ?>
