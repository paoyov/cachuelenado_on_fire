<?php
$title = 'Estadísticas del Sistema';
?>

<div class="container">
    <h2>Estadísticas Generales</h2>
    <p>Aquí puedes ver métricas globales del sistema y gráficos interactivos.</p>

    <div class="row mb-4 justify-content-center" style="gap: 20px;">
        <div class="col-md-3">
            <div class="card text-center h-100 shadow-hover">
                <div class="card-body py-4">
                    <h4 style="color: #ff6a2a;">Clientes</h4>
                    <span class="display-4 metric-clientes" style="color: #ff6a2a;"><?php echo isset($total_clientes) ? $total_clientes : '0'; ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100 shadow-hover">
                <div class="card-body py-4">
                    <h4 style="color: #2a9dff;">Maestros</h4>
                    <span class="display-4 metric-maestros" style="color: #2a9dff;"><?php echo isset($total_maestros) ? $total_maestros : '0'; ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center h-100 shadow-hover">
                <div class="card-body py-4">
                    <h4 style="color: #4CAF50;">Búsquedas</h4>
                    <span class="display-4 metric-busquedas" style="color: #4CAF50;"><?php echo isset($total_busquedas) ? $total_busquedas : '0'; ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Distribución de Usuarios</h5>
                    <canvas id="pieUsuarios"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Actividad General</h5>
                    <canvas id="barMetricas"></canvas>
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
.card-title { color: var(--primary-color); }
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
</style>
