<?php
$title = 'Reportes Mensuales';
?>
<div class="page-header-monthly">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="mb-0">
                    <i class="fas fa-chart-bar"></i> Reportes Mensuales
                </h1>
                <p class="mb-0">Resumen de actividad y métricas por mes.</p>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">

    <!-- Cards de Estadísticas -->
    <div class="stats-cards-wrapper">
        <div class="stats-cards-row">
            <div class="stats-card-item">
                <div class="card shadow-sm stats-card stats-card-month">
                    <div class="card-body text-center">
                        <div class="stats-icon-wrapper">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h6 class="card-title stats-title">Mes</h6>
                        <h4 class="card-text stats-value stats-value-month"><?php echo htmlspecialchars($selected_label ?? date('M Y')); ?></h4>
                    </div>
                </div>
            </div>
            <div class="stats-card-item">
                <div class="card shadow-sm stats-card stats-card-success">
                    <div class="card-body text-center">
                        <div class="stats-icon-wrapper">
                            <i class="fas fa-users"></i>
                        </div>
                        <h6 class="card-title stats-title">Clientes</h6>
                        <h2 class="card-text stats-value stats-value-success"><?php echo (int)($selected_cliente ?? 0); ?></h2>
                    </div>
                </div>
            </div>
            <div class="stats-card-item">
                <div class="card shadow-sm stats-card stats-card-info">
                    <div class="card-body text-center">
                        <div class="stats-icon-wrapper">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <h6 class="card-title stats-title">Maestros</h6>
                        <h2 class="card-text stats-value stats-value-info"><?php echo (int)($selected_maestro ?? 0); ?></h2>
                    </div>
                </div>
            </div>
            <div class="stats-card-item">
                <div class="card shadow-sm stats-card stats-card-danger">
                    <div class="card-body text-center">
                        <div class="stats-icon-wrapper">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h6 class="card-title stats-title">Reportes</h6>
                        <h2 class="card-text stats-value stats-value-danger"><?php echo (int)($selected_reporte ?? 0); ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtro de fechas -->
    <div class="card shadow-sm mb-4 date-filter-card">
        <div class="card-body date-filter-body">
            <div class="date-filter-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-filter"></i> Filtro de Fechas
                </h5>
            </div>
            
            <form method="GET" action="<?php echo BASE_URL . 'admin/reportes-mensuales'; ?>" class="date-filter-form" id="filterForm">
                <div class="date-filter-inputs">
                    <div class="date-input-group">
                        <label for="fecha_desde" class="form-label">
                            <i class="fas fa-calendar-alt"></i> Fecha Desde
                        </label>
                        <input type="date" 
                               class="form-control date-input" 
                               id="fecha_desde" 
                               name="fecha_desde" 
                               value="<?php echo htmlspecialchars($fecha_desde ?? ''); ?>"
                               max="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="date-separator">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                    
                    <div class="date-input-group">
                        <label for="fecha_hasta" class="form-label">
                            <i class="fas fa-calendar-alt"></i> Fecha Hasta
                        </label>
                        <input type="date" 
                               class="form-control date-input" 
                               id="fecha_hasta" 
                               name="fecha_hasta" 
                               value="<?php echo htmlspecialchars($fecha_hasta ?? ''); ?>"
                               max="<?php echo date('Y-m-d'); ?>">
                    </div>
                    
                    <div class="date-filter-actions">
                        <button type="submit" class="btn btn-primary btn-filter">
                            <i class="fas fa-search"></i> Filtrar
                        </button>
                        <?php if (isset($usar_filtro) && $usar_filtro): ?>
                            <a href="<?php echo BASE_URL . 'admin/reportes-mensuales'; ?>" class="btn btn-outline-secondary btn-clear">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                            <?php
                            $exportUrl = BASE_URL . 'admin/reportes-mensuales?export=csv&fecha_desde=' . urlencode($fecha_desde) . '&fecha_hasta=' . urlencode($fecha_hasta);
                            ?>
                            <a href="<?php echo $exportUrl; ?>" class="btn btn-success btn-export">
                                <i class="fas fa-download"></i> Exportar CSV
                            </a>
                        <?php else: ?>
                            <?php
                            $exportUrl = BASE_URL . 'admin/reportes-mensuales?export=csv';
                            ?>
                            <a href="<?php echo $exportUrl; ?>" class="btn btn-success btn-export">
                                <i class="fas fa-download"></i> Exportar CSV
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
            
            <?php if (isset($usar_filtro) && $usar_filtro): ?>
                <div class="date-filter-info active">
                    <i class="fas fa-info-circle"></i>
                    <span>
                        Mostrando datos desde <strong><?php echo date('d/m/Y', strtotime($fecha_desde)); ?></strong> 
                        hasta <strong><?php echo date('d/m/Y', strtotime($fecha_hasta)); ?></strong>
                    </span>
                </div>
            <?php else: ?>
                <div class="date-filter-info">
                    <i class="fas fa-info-circle"></i>
                    <span>Por defecto se muestran los últimos 12 meses. Selecciona un rango de fechas para filtrar.</span>
                </div>
            <?php endif; ?>
            
            <div id="dateError" class="alert alert-danger date-error-alert" style="display: none;">
                <i class="fas fa-exclamation-triangle"></i> <span id="dateErrorText"></span>
            </div>
        </div>
    </div>

    <!-- Gráficos Unificados -->
    <div class="charts-container-wrapper mb-4">
        <div class="charts-container">
            <div class="chart-card-unified">
                <div class="chart-card-header">
                    <h5 class="chart-card-title">
                        <i class="fas fa-chart-line"></i> Análisis de Métricas
                    </h5>
                </div>
                <div class="chart-card-body">
                    <div class="charts-grid">
                        <div class="chart-item">
                            <h6 class="chart-subtitle">
                                <i class="fas fa-chart-area"></i> Tendencia últimos 12 meses
                            </h6>
                            <div class="chart-wrapper">
                                <canvas id="trendChart"></canvas>
                            </div>
                        </div>
                        <div class="chart-item">
                            <h6 class="chart-subtitle">
                                <i class="fas fa-chart-pie"></i> Distribución (último mes)
                            </h6>
                            <div class="chart-wrapper">
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card de Detalle Mensual -->
    <div class="detail-table-card-wrapper">
        <div class="detail-table-card mt-3">
            <div class="detail-table-header">
                <h5 class="detail-table-title">
                    <i class="fas fa-table"></i> Detalle mensual
                </h5>
            </div>
            <div class="card-body detail-table-body">
                <div class="table-responsive">
                    <table class="table detail-table align-middle">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <i class="fas fa-calendar-alt"></i> Mes
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-users text-success"></i> Clientes
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-user-tie text-info"></i> Maestros
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-search text-purple"></i> Búsquedas
                                </th>
                                <th class="text-center">
                                    <i class="fas fa-exclamation-triangle text-danger"></i> Reportes
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $labels = $labels ?? [];
                            $clientes = $clientes_series ?? [];
                            $maestros = $maestros_series ?? [];
                            $trabajos = $trabajos_series ?? [];
                            $busquedas = $busquedas_series ?? [];
                            $reportes = $reportes_series ?? [];

                            if (count($labels) === 0) : ?>
                                <tr>
                                    <td colspan="5" class="text-center no-data">
                                        <i class="fas fa-inbox"></i> No hay reportes mensuales registrados.
                                    </td>
                                </tr>
                            <?php else:
                                for ($i = 0; $i < count($labels); $i++): ?>
                                    <tr>
                                        <td class="text-center month-cell">
                                            <strong><?php echo htmlspecialchars($labels[$i]); ?></strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="metric-badge metric-success"><?php echo (int)($clientes[$i] ?? 0); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="metric-badge metric-info"><?php echo (int)($maestros[$i] ?? 0); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="metric-badge metric-purple"><?php echo (int)($busquedas[$i] ?? 0); ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="metric-badge metric-danger"><?php echo (int)($reportes[$i] ?? 0); ?></span>
                                        </td>
                                    </tr>
                                <?php endfor;
                            endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Validación de fechas en el formulario de filtro
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        const fechaDesde = document.getElementById('fecha_desde');
        const fechaHasta = document.getElementById('fecha_hasta');
        const dateError = document.getElementById('dateError');
        const dateErrorText = document.getElementById('dateErrorText');

        // Validar cuando cambian las fechas
        fechaDesde.addEventListener('change', function() {
            // Establecer el mínimo de fecha_hasta como fecha_desde
            if (fechaDesde.value) {
                fechaHasta.min = fechaDesde.value;
            }
            validateDates();
        });

        fechaHasta.addEventListener('change', function() {
            // Establecer el máximo de fecha_desde como fecha_hasta
            if (fechaHasta.value) {
                fechaDesde.max = fechaHasta.value;
            }
            validateDates();
        });

        // Validar antes de enviar el formulario
        filterForm.addEventListener('submit', function(e) {
            if (!validateDates()) {
                e.preventDefault();
                return false;
            }
        });

        function validateDates() {
            const desde = fechaDesde.value;
            const hasta = fechaHasta.value;

            // Si ambos campos están vacíos, permitir envío (mostrar últimos 12 meses)
            if (!desde && !hasta) {
                dateError.style.display = 'none';
                return true;
            }

            // Si solo uno está lleno, mostrar error
            if ((desde && !hasta) || (!desde && hasta)) {
                dateErrorText.textContent = 'Por favor, selecciona ambas fechas (Desde y Hasta) o deja ambas vacías para ver los últimos 12 meses.';
                dateError.style.display = 'block';
                return false;
            }

            // Validar que fecha_desde <= fecha_hasta
            if (desde && hasta) {
                if (new Date(desde) > new Date(hasta)) {
                    dateErrorText.textContent = 'La fecha "Desde" no puede ser mayor que la fecha "Hasta".';
                    dateError.style.display = 'block';
                    return false;
                }
            }

            dateError.style.display = 'none';
            return true;
        }
    });
</script>
<script>
    const labels = <?php echo json_encode($labels ?? []); ?>;
    const clientes = <?php echo json_encode($clientes_series ?? []); ?>;
    const maestros = <?php echo json_encode($maestros_series ?? []); ?>;
    const trabajos = <?php echo json_encode($trabajos_series ?? []); ?>;
    const busquedas = <?php echo json_encode($busquedas_series ?? []); ?>;
    const reportes = <?php echo json_encode($reportes_series ?? []); ?>;

    const ctx = document.getElementById('trendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                { label: 'Clientes', data: clientes, borderColor: '#2ecc71', backgroundColor: 'rgba(46,204,113,0.08)', tension:0.3, borderWidth: 2 },
                { label: 'Maestros', data: maestros, borderColor: '#3498db', backgroundColor: 'rgba(52,152,219,0.06)', tension:0.3, borderWidth: 2 },
                { label: 'Búsquedas', data: busquedas, borderColor: '#9b59b6', backgroundColor: 'rgba(155,89,182,0.06)', tension:0.3, borderWidth: 2 },
                { label: 'Reportes', data: reportes, borderColor: '#e74c3c', backgroundColor: 'rgba(231,76,60,0.06)', tension:0.3, borderWidth: 2 }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    position: 'top',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12,
                            weight: '600'
                        },
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 13
                    },
                    cornerRadius: 8
                }
            },
            scales: { 
                x: { 
                    ticks: { maxRotation: 0, minRotation: 0 },
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }, 
                y: { 
                    beginAtZero: true,
                    grid: {
                        display: true,
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                } 
            }
        }
    });

    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const lastIndex = labels.length - 1;
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Clientes','Maestros','Búsquedas','Reportes'],
            datasets: [{
                data: [clientes[lastIndex] || 0, maestros[lastIndex] || 0, busquedas[lastIndex] || 0, reportes[lastIndex] || 0],
                backgroundColor: ['#2ecc71','#3498db','#9b59b6','#e74c3c'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: { 
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { 
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: {
                            size: 12,
                            weight: '600'
                        },
                        usePointStyle: true
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: '600'
                    },
                    bodyFont: {
                        size: 13
                    },
                    cornerRadius: 8
                }
            }
        }
    });
</script>
