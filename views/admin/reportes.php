<?php
$title = 'Reportes del Sistema';
?>
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="mb-0">Reportes</h2>
            <small class="text-muted">Listado de reportes de problemas, quejas y otros eventos.</small>
        </div>
        <div>
            <a href="<?php echo BASE_URL; ?>admin/reportes?export=csv" class="btn btn-outline-primary btn-sm me-2">Exportar CSV</a>
            <a href="<?php echo BASE_URL; ?>admin/reportes?export=print" target="_blank" class="btn btn-outline-secondary btn-sm">Exportar PDF</a>
        </div>
    </div>
    
    <div class="table-responsive shadow rounded bg-white">
        <table class="table table-hover mb-0">
            <thead class="table-light"> 
                <tr>
                    <th>ID</th>
                    <th>Reportado por</th>
                    <th>Reportado a</th>
                    <th>Tipo</th>
                    <th>Motivo</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($reportes) && count($reportes) > 0): ?>
                    <?php foreach ($reportes as $r): ?>
                        <tr>
                            <td><?php echo $r['id']; ?></td>
                            <td><?php echo htmlspecialchars($r['reportado_por_nombre']); ?></td>
                            <td><?php echo htmlspecialchars($r['reportado_a_nombre']); ?></td>
                            <td><?php echo htmlspecialchars(ucfirst($r['tipo'])); ?></td>
                            <td class="text-truncate" style="max-width:300px"><?php echo htmlspecialchars($r['motivo']); ?></td>
                            <td>
                                <span class="badge <?php echo $r['estado'] === 'pendiente' ? 'bg-secondary' : ($r['estado'] === 'en_revision' ? 'bg-warning text-dark' : ($r['estado'] === 'resuelto' ? 'bg-success' : 'bg-danger')); ?>">
                                    <?php echo ucfirst($r['estado']); ?>
                                </span>
                            </td>
                            <td><?php echo $r['fecha_reporte']; ?></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-primary btn-view-report" data-report='<?php echo json_encode($r); ?>'>Ver</button>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Estado</button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <form method="post" action="<?php echo BASE_URL; ?>admin/reportes" class="px-3 py-1">
                                                <input type="hidden" name="reporte_id" value="<?php echo $r['id']; ?>">
                                                <input type="hidden" name="accion" value="cambiar_estado">
                                                <input type="hidden" name="nuevo_estado" value="en_revision">
                                                <button type="submit" class="btn btn-sm btn-link">Marcar en revisión</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="post" action="<?php echo BASE_URL; ?>admin/reportes" class="px-3 py-1">
                                                <input type="hidden" name="reporte_id" value="<?php echo $r['id']; ?>">
                                                <input type="hidden" name="accion" value="cambiar_estado">
                                                <input type="hidden" name="nuevo_estado" value="resuelto">
                                                <button type="submit" class="btn btn-sm btn-link">Marcar resuelto</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="post" action="<?php echo BASE_URL; ?>admin/reportes" class="px-3 py-1">
                                                <input type="hidden" name="reporte_id" value="<?php echo $r['id']; ?>">
                                                <input type="hidden" name="accion" value="cambiar_estado">
                                                <input type="hidden" name="nuevo_estado" value="descartado">
                                                <button type="submit" class="btn btn-sm btn-link text-danger">Descartar</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center py-4">No hay reportes registrados.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para ver detalle de reporte -->
    <div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="reportModalLabel">Detalle del Reporte</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-8">
                <h5 id="r_tipo"></h5>
                <p id="r_motivo"></p>
              </div>
              <div class="col-md-4 text-md-end">
                <p><strong>ID:</strong> <span id="r_id"></span></p>
                <p><strong>Reportado por:</strong> <span id="r_por"></span></p>
                <p><strong>Reportado a:</strong> <span id="r_a"></span></p>
                <p><strong>Estado:</strong> <span id="r_estado" class="badge"></span></p>
                <p><strong>Fecha:</strong> <span id="r_fecha"></span></p>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function(){
        const modalEl = document.getElementById('reportModal');
        if (!modalEl) return;
        const bsModal = new bootstrap.Modal(modalEl);

        document.querySelectorAll('.btn-view-report').forEach(btn => {
            btn.addEventListener('click', function(){
                try {
                    const data = JSON.parse(this.getAttribute('data-report'));
                    document.getElementById('r_id').textContent = data.id || '';
                    document.getElementById('r_tipo').textContent = (data.tipo || '').toUpperCase();
                    document.getElementById('r_motivo').textContent = data.motivo || '';
                    document.getElementById('r_por').textContent = data.reportado_por_nombre || '';
                    document.getElementById('r_a').textContent = data.reportado_a_nombre || '';
                    document.getElementById('r_fecha').textContent = data.fecha_reporte || '';
                    const estadoEl = document.getElementById('r_estado');
                    estadoEl.textContent = data.estado || '';
                    estadoEl.className = 'badge ' + (data.estado === 'pendiente' ? 'bg-secondary' : (data.estado === 'en_revision' ? 'bg-warning text-dark' : (data.estado === 'resuelto' ? 'bg-success' : 'bg-danger')));
                    bsModal.show();
                } catch(e) { console.error(e); }
            });
        });
    });
    </script>

</div>
