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
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-search" style="font-size: 4rem; color: var(--gray-color); margin-bottom: 1rem;"></i>
                        <h3>No se encontraron resultados</h3>
                        <p>Intenta ajustar los filtros de búsqueda</p>
                    </div>
                </div>
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

