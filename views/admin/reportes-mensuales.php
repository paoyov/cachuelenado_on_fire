<?php
$title = 'Reportes Mensuales';
?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-0">Reportes Mensuales</h2>
            <small class="text-muted">Resumen de actividad y métricas por mes.</small>
        </div>
        <div class="text-end">
            <a href="<?php echo BASE_URL . 'admin/reportesMensuales?export=csv'; ?>" class="btn btn-outline-secondary me-2">Exportar CSV</a>
            <a href="javascript:window.print()" class="btn btn-outline-primary">Imprimir</a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6 class="card-title">Mes</h6>
                    <h5 class="card-text"><?php echo htmlspecialchars($selected_label ?? date('M Y')); ?></h5>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-start border-4 border-success">
                <div class="card-body">
                    <h6 class="card-title">Clientes</h6>
                    <h3 class="card-text text-success"><?php echo (int)($selected_cliente ?? 0); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm border-start border-4 border-info">
                <div class="card-body">
                    <h6 class="card-title">Maestros</h6>
                    <h3 class="card-text text-info"><?php echo (int)($selected_maestro ?? 0); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm border-start border-4 border-warning">
                <div class="card-body">
                    <h6 class="card-title">Trabajos</h6>
                    <h3 class="card-text text-warning"><?php echo (int)($selected_trabajo ?? 0); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card shadow-sm border-start border-4 border-danger">
                <div class="card-body">
                    <h6 class="card-title">Reportes</h6>
                    <h3 class="card-text text-danger"><?php echo (int)($selected_reporte ?? 0); ?></h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Tendencia últimos 12 meses</h6>
                    <canvas id="trendChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h6>Distribución (último mes)</h6>
                    <canvas id="pieChart" height="240"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mt-3">
        <div class="card-body">
            <h6 class="mb-3">Detalle mensual</h6>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Mes</th>
                            <th class="text-end">Clientes</th>
                            <th class="text-end">Maestros</th>
                            <th class="text-end">Trabajos</th>
                            <th class="text-end">Búsquedas</th>
                            <th class="text-end">Reportes</th>
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
                            <tr><td colspan="6">No hay reportes mensuales registrados.</td></tr>
                        <?php else:
                            for ($i = 0; $i < count($labels); $i++): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($labels[$i]); ?></td>
                                    <td class="text-end"><?php echo (int)($clientes[$i] ?? 0); ?></td>
                                    <td class="text-end"><?php echo (int)($maestros[$i] ?? 0); ?></td>
                                    <td class="text-end"><?php echo (int)($trabajos[$i] ?? 0); ?></td>
                                    <td class="text-end"><?php echo (int)($busquedas[$i] ?? 0); ?></td>
                                    <td class="text-end"><?php echo (int)($reportes[$i] ?? 0); ?></td>
                                </tr>
                            <?php endfor;
                        endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                { label: 'Clientes', data: clientes, borderColor: '#2ecc71', backgroundColor: 'rgba(46,204,113,0.08)', tension:0.3 },
                { label: 'Maestros', data: maestros, borderColor: '#3498db', backgroundColor: 'rgba(52,152,219,0.06)', tension:0.3 },
                { label: 'Trabajos', data: trabajos, borderColor: '#f39c12', backgroundColor: 'rgba(243,156,18,0.06)', tension:0.3 },
                { label: 'Búsquedas', data: busquedas, borderColor: '#9b59b6', backgroundColor: 'rgba(155,89,182,0.06)', tension:0.3 },
                { label: 'Reportes', data: reportes, borderColor: '#e74c3c', backgroundColor: 'rgba(231,76,60,0.06)', tension:0.3 }
            ]
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'top' } },
            scales: { x: { ticks: { maxRotation: 0, minRotation: 0 } }, y: { beginAtZero: true } }
        }
    });

    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const lastIndex = labels.length - 1;
    new Chart(pieCtx, {
        type: 'doughnut',
        data: {
            labels: ['Clientes','Maestros','Trabajos','Búsquedas','Reportes'],
            datasets: [{
                data: [clientes[lastIndex] || 0, maestros[lastIndex] || 0, trabajos[lastIndex] || 0, busquedas[lastIndex] || 0, reportes[lastIndex] || 0],
                backgroundColor: ['#2ecc71','#3498db','#f39c12','#9b59b6','#e74c3c']
            }]
        },
        options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
    });
</script>
