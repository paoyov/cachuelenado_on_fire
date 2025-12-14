<?php
$title = 'Estadísticas del Sistema';
?>

<div class="container">
    <h2>Estadísticas Generales</h2>
    <p>Aquí puedes ver métricas globales del sistema y gráficos interactivos.</p>

    <div class="stats-cards-container">
        <div class="row justify-content-center mb-4">
            <div class="col-12 col-md-10 col-lg-9 col-xl-8">
                <div class="row justify-content-center stats-row">
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                        <div class="card text-center h-100 shadow-hover">
                            <div class="card-body py-4">
                                <h4 style="color: #ff6a2a;">Clientes</h4>
                                <span class="display-4 metric-clientes" style="color: #ff6a2a;"><?php echo isset($total_clientes) ? $total_clientes : '0'; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                        <div class="card text-center h-100 shadow-hover">
                            <div class="card-body py-4">
                                <h4 style="color: #2a9dff;">Maestros</h4>
                                <span class="display-4 metric-maestros" style="color: #2a9dff;"><?php echo isset($total_maestros) ? $total_maestros : '0'; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4">
                        <div class="card text-center h-100 shadow-hover">
                            <div class="card-body py-4">
                                <h4 style="color: #4CAF50;">Búsquedas</h4>
                                <span class="display-4 metric-busquedas" style="color: #4CAF50;"><?php echo isset($total_busquedas) ? $total_busquedas : '0'; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="charts-container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-11 col-xl-10">
                <div class="row justify-content-center chart-row">
                    <div class="col-12 col-md-10 col-lg-5 col-xl-5">
                        <div class="card chart-card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-chart-pie"></i> Distribución de Usuarios
                                </h5>
                                <div class="chart-wrapper">
                                    <canvas id="pieUsuarios"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-10 col-lg-5 col-xl-5">
                        <div class="card chart-card mb-4">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="fas fa-chart-bar"></i> Actividad General
                                </h5>
                                <div class="chart-wrapper">
                                    <canvas id="barMetricas"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>
    <p>Los gráficos se actualizan con los datos globales del sistema. Puedes personalizar la vista para mostrar más detalles y filtros.</p>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Obtener métricas desde el DOM
const clientes = parseInt(document.querySelector('.metric-clientes').textContent) || 0;
const maestros = parseInt(document.querySelector('.metric-maestros').textContent) || 0;
const busquedas = parseInt(document.querySelector('.metric-busquedas').textContent) || 0;

// Gráfico de pastel: distribución de usuarios
const ctxPie = document.getElementById('pieUsuarios').getContext('2d');
new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: ['Clientes', 'Maestros'],
        datasets: [{
            data: [clientes, maestros],
            backgroundColor: ['#ff6a2a', '#2a9dff'],
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom' },
            title: { display: true, text: 'Distribución de Usuarios' }
        }
    }
});

// Gráfico de barras: actividad general
const ctxBar = document.getElementById('barMetricas').getContext('2d');
new Chart(ctxBar, {
    type: 'bar',
    data: {
        labels: ['Clientes', 'Maestros', 'Búsquedas'],
        datasets: [{
            label: 'Cantidad',
            data: [clientes, maestros, busquedas],
            backgroundColor: [
                '#ff6a2a', '#2a9dff', '#6aff2a'
            ],
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            title: { display: true, text: 'Actividad General' }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

<style>
.stats-cards-container {
    margin: 2rem 0;
    display: flex;
    justify-content: center;
    align-items: center;
}

.stats-cards-container > .row {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
}

.stats-row {
    gap: 1.5rem;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: stretch;
}

.stats-row > div {
    margin-bottom: 1.5rem;
}

.charts-container {
    margin-top: 3rem;
    margin-bottom: 3rem;
    display: flex;
    justify-content: center;
    align-items: center;
}

.charts-container > .row {
    width: 100%;
    max-width: 1400px;
    margin: 0 auto;
}

.chart-row {
    gap: 3rem;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: stretch;
}

.chart-row > div {
    padding: 0;
    display: flex;
    justify-content: center;
}

.chart-card {
    background: white;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 16px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    height: 100%;
    overflow: hidden;
}

.chart-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.15);
    border-color: rgba(255, 107, 53, 0.2);
}

.chart-card .card-body {
    padding: 2rem 2.5rem;
}

.chart-card .card-title {
    color: var(--primary-color);
    font-weight: 700;
    font-size: 1.25rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid rgba(255, 107, 53, 0.1);
}

.chart-card .card-title i {
    font-size: 1.2rem;
    color: var(--primary-color);
}

.chart-wrapper {
    position: relative;
    height: 350px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem 0;
}

.card-title { 
    color: var(--primary-color); 
}

.card { 
    box-shadow: 0 4px 12px rgba(0,0,0,0.08); 
    border: none;
    border-radius: 12px;
    transition: transform 0.2s;
}

.shadow-hover:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.12);
}

.display-4 { 
    font-size: 3.5rem; 
    font-weight: 700; 
    display: block;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.05);
}

.card h4 {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.9rem;
    letter-spacing: 1px;
    opacity: 0.9;
}

@media (max-width: 768px) {
    .chart-row {
        gap: 1.5rem;
    }
    
    .chart-wrapper {
        height: 280px;
    }
    
    .chart-card .card-body {
        padding: 1.5rem;
    }
    
    .chart-card .card-title {
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }
}

@media (min-width: 992px) {
    .chart-row {
        justify-content: center;
    }
    
    .chart-row > div {
        max-width: 500px;
    }
    
    .charts-container {
        padding: 0 2rem;
    }
}

@media (min-width: 1200px) {
    .chart-row {
        gap: 4rem;
    }
}
</style>
