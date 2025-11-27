<?php
$title = 'Calificaciones';
?>

<div class="container">
    <h1>Calificaciones</h1>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if (!empty($calificaciones)): ?>
        <div class="ratings-list">
            <?php foreach ($calificaciones as $cal): ?>
                <div class="rating-card">
                    <div class="rating-header">
                        <div>
                            <strong><?php echo htmlspecialchars($cal['nombre_completo']); ?></strong>
                            <div class="rating-date"><?php echo formatDateTime($cal['fecha_calificacion'], 'd/m/Y H:i'); ?></div>
                        </div>
                        <div class="rating-score">
                            <?php
                                $prom = round((($cal['puntualidad'] + $cal['calidad'] + $cal['trato'] + $cal['limpieza'])/4),1);
                                echo '<span class="score">' . number_format($prom,1) . '</span>';
                            ?>
                        </div>
                    </div>
                    <div class="rating-body">
                        <div class="rating-breakdown">
                            <div>Puntualidad: <?php echo (int)$cal['puntualidad']; ?>/5</div>
                            <div>Calidad: <?php echo (int)$cal['calidad']; ?>/5</div>
                            <div>Trato: <?php echo (int)$cal['trato']; ?>/5</div>
                            <div>Limpieza: <?php echo (int)$cal['limpieza']; ?>/5</div>
                        </div>
                        <?php if (!empty($cal['comentario'])): ?>
                            <p class="comentario"><?php echo nl2br(htmlspecialchars($cal['comentario'])); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <!-- Static Cards for Demo (Requested) -->
        <div class="ratings-list">
             <!-- Card Estática 1 -->
            <div class="rating-card">
                <div class="rating-header">
                    <div>
                        <strong>Juan Perez</strong>
                        <div class="rating-date">24/11/2023 10:30</div>
                    </div>
                    <div class="rating-score">
                        <span class="score">4.8</span>
                    </div>
                </div>
                <div class="rating-body">
                    <div class="rating-breakdown">
                        <div>Puntualidad: 5/5</div>
                        <div>Calidad: 5/5</div>
                        <div>Trato: 4/5</div>
                        <div>Limpieza: 5/5</div>
                    </div>
                    <p class="comentario">"Excelente trabajo, llegó puntual y solucionó el problema de la fuga rápidamente. Muy recomendado."</p>
                </div>
            </div>

            <!-- Card Estática 2 -->
            <div class="rating-card">
                <div class="rating-header">
                    <div>
                        <strong>Maria Lopez</strong>
                        <div class="rating-date">20/11/2023 15:45</div>
                    </div>
                    <div class="rating-score">
                        <span class="score">5.0</span>
                    </div>
                </div>
                <div class="rating-body">
                    <div class="rating-breakdown">
                        <div>Puntualidad: 5/5</div>
                        <div>Calidad: 5/5</div>
                        <div>Trato: 5/5</div>
                        <div>Limpieza: 5/5</div>
                    </div>
                    <p class="comentario">"Impecable servicio. Dejó todo reluciente y fue muy amable en todo momento. Volveré a contratarla."</p>
                </div>
            </div>

            <!-- Card Estática 3 -->
            <div class="rating-card">
                <div class="rating-header">
                    <div>
                        <strong>Carlos Ruiz</strong>
                        <div class="rating-date">15/11/2023 09:15</div>
                    </div>
                    <div class="rating-score">
                        <span class="score">4.5</span>
                    </div>
                </div>
                <div class="rating-body">
                    <div class="rating-breakdown">
                        <div>Puntualidad: 4/5</div>
                        <div>Calidad: 5/5</div>
                        <div>Trato: 4/5</div>
                        <div>Limpieza: 5/5</div>
                    </div>
                    <p class="comentario">"Buen electricista, sabe lo que hace. Un poco serio pero muy profesional en su trabajo."</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div style="margin-top:1rem;">
        <a href="<?php echo BASE_URL; ?>maestro/dashboard" class="btn btn-outline">Volver al Panel</a>
    </div>
</div>

<style>
.ratings-list { display:flex; flex-direction:column; gap:1rem; }
.rating-card { border:1px solid #eee; padding:1rem; border-radius:8px; background:#fff; }
.rating-header { display:flex; justify-content:space-between; align-items:center; }
.rating-date { color: #888; font-size:0.9rem; }
.rating-score .score { background:#ffefdb; color:#ff6b35; padding:6px 10px; border-radius:6px; font-weight:700; }
.rating-breakdown { display:flex; gap:1rem; flex-wrap:wrap; margin-top:0.5rem; color:#555; }
.comentario { margin-top:0.75rem; color:#333; }
</style>
