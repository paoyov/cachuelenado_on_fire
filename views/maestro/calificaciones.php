<?php
$title = 'Calificaciones';
?>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-star"></i> Calificaciones</h1>
        <p>Revisa todas tus calificaciones y comentarios</p>
    </div>
</div>

<div class="container">
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <div class="ratings-container">
        <?php if (!empty($calificaciones)): ?>
            <div class="ratings-list">
                <?php foreach ($calificaciones as $cal): ?>
                    <?php
                    $prom = round((($cal['puntualidad'] + $cal['calidad'] + $cal['trato'] + $cal['limpieza'])/4),1);
                    ?>
                    <div class="rating-card">
                        <div class="rating-card-header">
                            <div class="rating-user-section">
                                <div class="rating-user-avatar">
                                    <?php if (!empty($cal['foto_perfil'])): ?>
                                        <?php 
                                        $inicial = strtoupper(substr($cal['nombre_completo'], 0, 1));
                                        $foto_url = UPLOAD_URL . $cal['foto_perfil'];
                                        ?>
                                        <img src="<?php echo $foto_url; ?>" 
                                             alt="<?php echo htmlspecialchars($cal['nombre_completo']); ?>"
                                             onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <span class="avatar-fallback" style="display: none;"><?php echo $inicial; ?></span>
                                    <?php else: ?>
                                        <?php echo strtoupper(substr($cal['nombre_completo'], 0, 1)); ?>
                                    <?php endif; ?>
                                </div>
                                <div class="rating-user-info">
                                    <strong class="rating-user-name"><?php echo htmlspecialchars($cal['nombre_completo']); ?></strong>
                                    <div class="rating-date">
                                        <i class="fas fa-clock"></i> <?php echo formatDateTime($cal['fecha_calificacion'], 'd/m/Y H:i'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="rating-score-badge">
                                <span class="score-value"><?php echo number_format($prom, 1); ?></span>
                                <div class="rating-stars-mini">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?php echo $i <= round($prom) ? 'star-filled' : 'star-empty'; ?>"></i>
                                    <?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <div class="rating-card-body">
                            <div class="rating-breakdown">
                                <div class="breakdown-item">
                                    <i class="fas fa-clock"></i>
                                    <span>Puntualidad</span>
                                    <span class="breakdown-score"><?php echo (int)$cal['puntualidad']; ?>/5</span>
                                </div>
                                <div class="breakdown-item">
                                    <i class="fas fa-award"></i>
                                    <span>Calidad</span>
                                    <span class="breakdown-score"><?php echo (int)$cal['calidad']; ?>/5</span>
                                </div>
                                <div class="breakdown-item">
                                    <i class="fas fa-handshake"></i>
                                    <span>Trato</span>
                                    <span class="breakdown-score"><?php echo (int)$cal['trato']; ?>/5</span>
                                </div>
                                <div class="breakdown-item">
                                    <i class="fas fa-broom"></i>
                                    <span>Limpieza</span>
                                    <span class="breakdown-score"><?php echo (int)$cal['limpieza']; ?>/5</span>
                                </div>
                            </div>
                            <?php if (!empty($cal['comentario'])): ?>
                                <div class="rating-comment">
                                    <i class="fas fa-quote-left"></i>
                                    <p><?php echo nl2br(htmlspecialchars($cal['comentario'])); ?></p>
                                </div>
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
                    <div class="rating-card-header">
                        <div class="rating-user-section">
                            <div class="rating-user-avatar">J</div>
                            <div class="rating-user-info">
                                <strong class="rating-user-name">Juan Perez</strong>
                                <div class="rating-date">
                                    <i class="fas fa-clock"></i> 24/11/2023 10:30
                                </div>
                            </div>
                        </div>
                        <div class="rating-score-badge">
                            <span class="score-value">4.8</span>
                            <div class="rating-stars-mini">
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                            </div>
                        </div>
                    </div>
                    <div class="rating-card-body">
                        <div class="rating-breakdown">
                            <div class="breakdown-item">
                                <i class="fas fa-clock"></i>
                                <span>Puntualidad</span>
                                <span class="breakdown-score">5/5</span>
                            </div>
                            <div class="breakdown-item">
                                <i class="fas fa-award"></i>
                                <span>Calidad</span>
                                <span class="breakdown-score">5/5</span>
                            </div>
                            <div class="breakdown-item">
                                <i class="fas fa-handshake"></i>
                                <span>Trato</span>
                                <span class="breakdown-score">4/5</span>
                            </div>
                            <div class="breakdown-item">
                                <i class="fas fa-broom"></i>
                                <span>Limpieza</span>
                                <span class="breakdown-score">5/5</span>
                            </div>
                        </div>
                        <div class="rating-comment">
                            <i class="fas fa-quote-left"></i>
                            <p>"Excelente trabajo, llegó puntual y solucionó el problema de la fuga rápidamente. Muy recomendado."</p>
                        </div>
                    </div>
                </div>

                <!-- Card Estática 2 -->
                <div class="rating-card">
                    <div class="rating-card-header">
                        <div class="rating-user-section">
                            <div class="rating-user-avatar">M</div>
                            <div class="rating-user-info">
                                <strong class="rating-user-name">Maria Lopez</strong>
                                <div class="rating-date">
                                    <i class="fas fa-clock"></i> 20/11/2023 15:45
                                </div>
                            </div>
                        </div>
                        <div class="rating-score-badge">
                            <span class="score-value">5.0</span>
                            <div class="rating-stars-mini">
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                            </div>
                        </div>
                    </div>
                    <div class="rating-card-body">
                        <div class="rating-breakdown">
                            <div class="breakdown-item">
                                <i class="fas fa-clock"></i>
                                <span>Puntualidad</span>
                                <span class="breakdown-score">5/5</span>
                            </div>
                            <div class="breakdown-item">
                                <i class="fas fa-award"></i>
                                <span>Calidad</span>
                                <span class="breakdown-score">5/5</span>
                            </div>
                            <div class="breakdown-item">
                                <i class="fas fa-handshake"></i>
                                <span>Trato</span>
                                <span class="breakdown-score">5/5</span>
                            </div>
                            <div class="breakdown-item">
                                <i class="fas fa-broom"></i>
                                <span>Limpieza</span>
                                <span class="breakdown-score">5/5</span>
                            </div>
                        </div>
                        <div class="rating-comment">
                            <i class="fas fa-quote-left"></i>
                            <p>"Impecable servicio. Dejó todo reluciente y fue muy amable en todo momento. Volveré a contratarla."</p>
                        </div>
                    </div>
                </div>

                <!-- Card Estática 3 -->
                <div class="rating-card">
                    <div class="rating-card-header">
                        <div class="rating-user-section">
                            <div class="rating-user-avatar">C</div>
                            <div class="rating-user-info">
                                <strong class="rating-user-name">Carlos Ruiz</strong>
                                <div class="rating-date">
                                    <i class="fas fa-clock"></i> 15/11/2023 09:15
                                </div>
                            </div>
                        </div>
                        <div class="rating-score-badge">
                            <span class="score-value">4.5</span>
                            <div class="rating-stars-mini">
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-filled"></i>
                                <i class="fas fa-star star-empty"></i>
                            </div>
                        </div>
                    </div>
                    <div class="rating-card-body">
                        <div class="rating-breakdown">
                            <div class="breakdown-item">
                                <i class="fas fa-clock"></i>
                                <span>Puntualidad</span>
                                <span class="breakdown-score">4/5</span>
                            </div>
                            <div class="breakdown-item">
                                <i class="fas fa-award"></i>
                                <span>Calidad</span>
                                <span class="breakdown-score">5/5</span>
                            </div>
                            <div class="breakdown-item">
                                <i class="fas fa-handshake"></i>
                                <span>Trato</span>
                                <span class="breakdown-score">4/5</span>
                            </div>
                            <div class="breakdown-item">
                                <i class="fas fa-broom"></i>
                                <span>Limpieza</span>
                                <span class="breakdown-score">5/5</span>
                            </div>
                        </div>
                        <div class="rating-comment">
                            <i class="fas fa-quote-left"></i>
                            <p>"Buen electricista, sabe lo que hace. Un poco serio pero muy profesional en su trabajo."</p>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="back-button-wrapper">
        <a href="<?php echo BASE_URL; ?>maestro/dashboard" class="btn-back">
            <i class="fas fa-arrow-left"></i> Volver al Panel
        </a>
    </div>
</div>
