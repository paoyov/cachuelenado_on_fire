<?php
$title = 'Reportes de Maestros Rechazados';
?>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-exclamation-triangle"></i> Reportes de Maestros Rechazados</h1>
        <p>Listado de maestros rechazados con motivo de rechazo y detalles</p>
    </div>
</div>

<div class="container">
    <div class="mb-4">
        <h2 class="mb-0">Reportes</h2>
        <small class="text-muted">Total: <?php echo count($reportes); ?> maestros rechazados</small>
    </div>
    
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 reports-table">
                    <thead class="table-header">
                        <tr>
                            <th class="text-center" style="width: 60px;">ID</th>
                            <th>Usuario Rechazado</th>
                            <th>Motivo de Rechazo</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Fecha de Rechazo</th>
                            <th class="text-center">Fecha de Registro</th>
                            <th class="text-center" style="width: 100px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($reportes) && count($reportes) > 0): ?>
                            <?php foreach ($reportes as $r): ?>
                                <tr class="report-row">
                                    <td class="text-center">
                                        <span class="report-id">#<?php echo $r['maestro_id']; ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($r['foto_perfil'])): ?>
                                                <img src="<?php echo UPLOAD_URL . $r['foto_perfil']; ?>" 
                                                     alt="<?php echo htmlspecialchars($r['nombre_completo']); ?>" 
                                                     class="user-avatar">
                                            <?php else: ?>
                                                <div class="user-avatar">
                                                    <?php echo strtoupper(substr($r['nombre_completo'], 0, 1)); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="user-info-wrapper">
                                                <div class="user-name"><?php echo htmlspecialchars($r['nombre_completo']); ?></div>
                                                <small class="text-muted user-email"><?php echo htmlspecialchars($r['email']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="motivo-rechazo">
                                            <i class="fas fa-ban text-danger me-2"></i>
                                            <span><?php echo htmlspecialchars($r['motivo'] ?? 'No especificado'); ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-status status-rechazado">
                                            <i class="fas fa-times-circle"></i>
                                            Rechazado
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($r['fecha_reporte']): ?>
                                            <div class="date-info">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                <?php echo date('d/m/Y', strtotime($r['fecha_reporte'])); ?>
                                            </div>
                                            <small class="text-muted">
                                                <?php echo date('H:i', strtotime($r['fecha_reporte'])); ?>
                                            </small>
                                        <?php else: ?>
                                            <span class="text-muted">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="date-info">
                                            <i class="far fa-calendar-alt me-1"></i>
                                            <?php echo date('d/m/Y', strtotime($r['fecha_registro'])); ?>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" 
                                                class="btn btn-sm btn-icon btn-icon-primary view-detail-btn" 
                                                title="Ver Detalle"
                                                data-report='<?php echo htmlspecialchars(json_encode($r), ENT_QUOTES, 'UTF-8'); ?>'>
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox" style="font-size: 3rem; color: var(--gray-color); margin-bottom: 1rem;"></i>
                                        <h4>No hay reportes registrados</h4>
                                        <p class="text-muted">No se han encontrado maestros rechazados con motivo de rechazo.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle Reporte -->
<div class="modal fade" id="reportDetailModal" tabindex="-1" aria-hidden="true" style="display:none;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content report-modal-content">
            <div class="modal-header report-modal-header">
                <div class="header-content">
                    <div class="header-icon-wrapper">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="header-text">
                        <h3 class="modal-title">Detalle del Reporte</h3>
                        <p class="modal-subtitle">Información completa del maestro rechazado</p>
                    </div>
                </div>
                <button type="button" class="close-modal-btn" onclick="closeReportModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body report-modal-body">
                <div class="report-detail-content">
                    <!-- Información del Usuario -->
                    <div class="detail-section user-info-section">
                        <div class="section-header">
                            <div class="section-icon user-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <h5>Información del Usuario</h5>
                        </div>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-hashtag"></i>
                                    ID Maestro
                                </div>
                                <div class="detail-value" id="detail-maestro-id">-</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-user-circle"></i>
                                    Nombre Completo
                                </div>
                                <div class="detail-value" id="detail-nombre">-</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-envelope"></i>
                                    Email
                                </div>
                                <div class="detail-value" id="detail-email">-</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-info-circle"></i>
                                    Estado
                                </div>
                                <div class="detail-value">
                                    <span id="detail-estado" class="badge-status status-rechazado">
                                        <i class="fas fa-times-circle"></i>
                                        Rechazado
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Motivo de Rechazo -->
                    <div class="detail-section motivo-section">
                        <div class="section-header">
                            <div class="section-icon motivo-icon">
                                <i class="fas fa-ban"></i>
                            </div>
                            <h5>Motivo de Rechazo</h5>
                        </div>
                        <div class="motivo-box">
                            <div class="motivo-content">
                                <i class="fas fa-exclamation-triangle"></i>
                                <p id="detail-motivo">-</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información de Fechas -->
                    <div class="detail-section dates-section">
                        <div class="section-header">
                            <div class="section-icon date-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <h5>Información de Fechas</h5>
                        </div>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="far fa-calendar-plus"></i>
                                    Fecha de Registro
                                </div>
                                <div class="detail-value" id="detail-fecha-registro">-</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="far fa-calendar-times"></i>
                                    Fecha de Rechazo
                                </div>
                                <div class="detail-value" id="detail-fecha-rechazo">-</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">
                                    <i class="fas fa-user-shield"></i>
                                    Validado por
                                </div>
                                <div class="detail-value" id="detail-validado-por">-</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer report-modal-footer">
                <button type="button" class="btn btn-close-modal" onclick="closeReportModal()">
                    <i class="fas fa-times me-2"></i>
                    Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade" id="reportModalBackdrop" style="display:none;"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('reportDetailModal');
    const backdrop = document.getElementById('reportModalBackdrop');

    document.querySelectorAll('.view-detail-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            try {
                const report = JSON.parse(this.dataset.report);
                
                // Información del Usuario
                document.getElementById('detail-maestro-id').textContent = '#' + report.maestro_id;
                document.getElementById('detail-nombre').textContent = report.nombre_completo || 'N/A';
                document.getElementById('detail-email').textContent = report.email || 'N/A';
                
                // Estado (ya está en el HTML, solo confirmamos)
                const estadoEl = document.getElementById('detail-estado');
                if (estadoEl) {
                    estadoEl.innerHTML = '<i class="fas fa-times-circle"></i> Rechazado';
                }
                
                // Motivo de Rechazo
                const motivoEl = document.getElementById('detail-motivo');
                if (motivoEl) {
                    motivoEl.textContent = report.motivo || report.motivo_rechazo || 'No especificado';
                }
                
                // Fechas
                const fechaRegistro = new Date(report.fecha_registro);
                document.getElementById('detail-fecha-registro').textContent = fechaRegistro.toLocaleDateString('es-PE', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                
                if (report.fecha_reporte || report.fecha_validacion) {
                    const fechaRechazo = new Date(report.fecha_reporte || report.fecha_validacion);
                    document.getElementById('detail-fecha-rechazo').textContent = fechaRechazo.toLocaleDateString('es-PE', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                } else {
                    document.getElementById('detail-fecha-rechazo').textContent = 'N/A';
                }
                
                document.getElementById('detail-validado-por').textContent = report.validado_por_nombre || 'Sistema';
                
                showReportModal();
            } catch(e) {
                console.error('Error al cargar detalle:', e);
            }
        });
    });

    function showReportModal() {
        modal.style.display = 'flex';
        modal.classList.add('show');
        backdrop.style.display = 'block';
        backdrop.classList.add('show');
        document.body.classList.add('modal-open');
    }

    window.closeReportModal = function() {
        modal.classList.remove('show');
        backdrop.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
            backdrop.style.display = 'none';
            document.body.classList.remove('modal-open');
        }, 150);
    };

    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeReportModal();
        }
    });
});
</script>

<style>
/* Reports Table Styles */
.reports-table {
    font-size: 0.95rem;
}

.table-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.table-header th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    padding: 1rem;
    border: none;
}

.report-row {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f0f0f0;
}

.report-row:hover {
    background: #f8f9fa;
    transform: scale(1.01);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.report-id {
    font-weight: 700;
    color: var(--primary-color);
    font-size: 0.9rem;
}

.user-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e9ecef;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    font-weight: 700;
    font-size: 1.1rem;
    flex-shrink: 0;
    margin-right: 1.25rem;
}

.user-info-wrapper {
    display: flex;
    flex-direction: column;
    gap: 0.35rem;
    padding: 0.15rem 0;
}

.user-name {
    font-weight: 600;
    color: var(--dark-color);
    letter-spacing: 0.4px;
    line-height: 1.6;
    font-size: 1.05rem;
    margin: 0;
    padding: 0;
}

.user-email {
    display: block;
    margin-top: 0.15rem;
    font-size: 0.875rem;
    opacity: 0.75;
}

.motivo-rechazo {
    color: #2d3748;
    font-weight: 500;
    line-height: 1.5;
    max-width: 400px;
}

.badge-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 0.4rem 0.9rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85rem;
}

.status-rechazado {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: white;
}

.date-info {
    font-weight: 500;
    color: var(--dark-color);
}

/* Empty State */
.empty-state {
    padding: 3rem 1rem;
}

/* Modal Styles */
#reportDetailModal {
    z-index: 1060;
    display: none;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

#reportDetailModal.show {
    display: flex;
}

#reportDetailModal .modal-dialog {
    max-width: 900px;
    margin: 1.75rem auto;
    width: 90%;
}

#reportDetailModal .modal-dialog-centered {
    display: flex;
    align-items: center;
    min-height: calc(100% - 3.5rem);
}

.report-modal-content {
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 25px 80px rgba(0,0,0,0.25);
    border: none;
}

.report-modal-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    padding: 2rem 2.5rem;
    border-bottom: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.header-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex: 1;
}

.header-icon-wrapper {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    flex-shrink: 0;
    backdrop-filter: blur(10px);
}

.header-text {
    flex: 1;
}

.report-modal-header .modal-title {
    margin: 0;
    font-weight: 700;
    font-size: 1.75rem;
    letter-spacing: -0.02em;
}

.modal-subtitle {
    margin: 0.25rem 0 0 0;
    opacity: 0.9;
    font-size: 0.95rem;
    font-weight: 400;
}

.close-modal-btn {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: white;
    font-size: 1.25rem;
    cursor: pointer;
    transition: all 0.3s ease;
    padding: 0.5rem;
    line-height: 1;
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.close-modal-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: rotate(90deg);
}

.report-modal-body {
    padding: 2.5rem;
    background: #fafbfc;
    max-height: calc(100vh - 250px);
    overflow-y: auto;
}

.report-detail-content {
    padding: 0;
}

.detail-section {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    border: 1px solid #e9ecef;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f0f0;
}

.section-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    flex-shrink: 0;
}

.user-icon {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.motivo-icon {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.date-icon {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
}

.section-header h5 {
    margin: 0;
    font-weight: 700;
    font-size: 1.3rem;
    color: var(--dark-color);
    letter-spacing: -0.01em;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.25rem;
}

.detail-item {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 1.25rem;
    transition: all 0.2s ease;
    border: 1px solid #e9ecef;
}

.detail-item:hover {
    background: #f0f2f5;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.detail-label {
    color: #6c757d;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-label i {
    font-size: 0.9rem;
    color: var(--primary-color);
}

.detail-value {
    color: var(--dark-color);
    font-size: 1.05rem;
    font-weight: 600;
    line-height: 1.4;
}

.motivo-section {
    background: linear-gradient(135deg, #fff9e6 0%, #fff3cd 100%);
    border: 2px solid #ffc107;
}

.motivo-box {
    background: white;
    border-radius: 10px;
    padding: 1.75rem;
    margin-top: 0.5rem;
    border: 1px solid #ffeaa7;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.1);
}

.motivo-content {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.motivo-content i {
    font-size: 1.5rem;
    color: #f59e0b;
    margin-top: 0.25rem;
    flex-shrink: 0;
}

.motivo-content p {
    margin: 0;
    color: #856404;
    line-height: 1.7;
    font-size: 1.05rem;
    font-weight: 500;
}

.report-modal-footer {
    padding: 1.5rem 2.5rem;
    border-top: 1px solid #e9ecef;
    background: white;
    display: flex;
    justify-content: flex-end;
}

.btn-close-modal {
    padding: 0.75rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 1rem;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
    cursor: pointer;
}

.btn-close-modal:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
}

#reportModalBackdrop {
    z-index: 1055;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
}

@media (max-width: 768px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .table-header th {
        font-size: 0.7rem;
        padding: 0.75rem 0.5rem;
    }
    
    #reportDetailModal .modal-dialog {
        max-width: 95%;
        margin: 1rem auto;
    }
    
    .report-modal-header {
        padding: 1.5rem;
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .header-content {
        width: 100%;
    }
    
    .report-modal-body {
        padding: 1.5rem;
    }
    
    .detail-section {
        padding: 1.5rem;
    }
    
    .section-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
    }
}
</style>
