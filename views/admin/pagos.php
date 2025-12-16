<?php
$title = 'Gestión de Pagos';
?>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-wallet"></i> Gestión de Pagos de Maestros</h1>
        <p>Verifica y gestiona los pagos realizados por los maestros</p>
    </div>
</div>

<div class="container">
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link <?php echo $estado === 'pendiente' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>admin/pagos?estado=pendiente">
                        <i class="fas fa-clock"></i> Pendientes
                        <?php if ($estado === 'pendiente'): ?>
                            <span class="badge bg-warning"><?php echo count($pagos); ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $estado === 'verificado' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>admin/pagos?estado=verificado">
                        <i class="fas fa-check-circle"></i> Verificados
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo $estado === 'rechazado' ? 'active' : ''; ?>" 
                       href="<?php echo BASE_URL; ?>admin/pagos?estado=rechazado">
                        <i class="fas fa-times-circle"></i> Rechazados
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <?php if (empty($pagos)): ?>
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> No hay pagos <?php echo $estado === 'pendiente' ? 'pendientes' : ($estado === 'verificado' ? 'verificados' : 'rechazados'); ?>.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Maestro</th>
                                <th>Monto</th>
                                <th>Método</th>
                                <th>Comprobante</th>
                                <th>Fecha Pago</th>
                                <th>Expiración</th>
                                <?php if ($estado === 'pendiente'): ?>
                                    <th>Acciones</th>
                                <?php else: ?>
                                    <th>Verificado Por</th>
                                    <th>Fecha Verificación</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pagos as $pago): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <i class="fas fa-user-circle" style="font-size: 2rem; color: var(--primary-color);"></i>
                                        </div>
                                        <div>
                                            <strong><?php echo htmlspecialchars($pago['nombre_completo']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($pago['email']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success" style="font-size: 1rem; padding: 0.5rem 1rem;">
                                        S/ <?php echo number_format($pago['monto'], 2); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        <i class="fab fa-yape"></i> <?php echo strtoupper($pago['metodo_pago']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!empty($pago['comprobante_imagen'])): ?>
                                        <a href="<?php echo UPLOAD_URL . $pago['comprobante_imagen']; ?>" 
                                           target="_blank" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Ver
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Sin imagen</span>
                                    <?php endif; ?>
                                    <?php if (!empty($pago['numero_comprobante'])): ?>
                                        <br><small class="text-muted">#<?php echo htmlspecialchars($pago['numero_comprobante']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo formatDateTime($pago['fecha_pago']); ?></td>
                                <td>
                                    <?php 
                                    $fecha_exp = new DateTime($pago['fecha_expiracion']);
                                    $ahora = new DateTime();
                                    $diferencia = $ahora->diff($fecha_exp);
                                    
                                    if ($fecha_exp > $ahora):
                                        $horas = ($diferencia->days * 24) + $diferencia->h;
                                    ?>
                                        <span class="badge bg-success">
                                            <?php echo $horas; ?>h restantes
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Expirado</span>
                                    <?php endif; ?>
                                    <br><small class="text-muted"><?php echo formatDateTime($pago['fecha_expiracion']); ?></small>
                                </td>
                                <?php if ($estado === 'pendiente'): ?>
                                    <td>
                                        <form method="POST" action="<?php echo BASE_URL; ?>pago/verificar" style="display: inline;">
                                            <input type="hidden" name="pago_id" value="<?php echo $pago['id']; ?>">
                                            <input type="hidden" name="accion" value="verificar">
                                            <button type="submit" 
                                                    class="btn btn-sm btn-success" 
                                                    onclick="return confirm('¿Estás seguro de verificar este pago?');">
                                                <i class="fas fa-check"></i> Verificar
                                            </button>
                                        </form>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="rechazarPago(<?php echo $pago['id']; ?>)">
                                            <i class="fas fa-times"></i> Rechazar
                                        </button>
                                    </td>
                                <?php else: ?>
                                    <td>
                                        <?php 
                                        if (!empty($pago['admin_nombre'])):
                                            echo htmlspecialchars($pago['admin_nombre']);
                                        elseif ($pago['verificado_por']):
                                            echo 'Admin ID: ' . $pago['verificado_por'];
                                        else:
                                            echo '-';
                                        endif;
                                        ?>
                                    </td>
                                    <td><?php echo $pago['fecha_verificacion'] ? formatDateTime($pago['fecha_verificacion']) : '-'; ?></td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para rechazar pago -->
<div class="modal fade" id="modalRechazarPago" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-times-circle"></i> Rechazar Pago</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="<?php echo BASE_URL; ?>pago/verificar" id="formRechazarPago">
                <div class="modal-body">
                    <input type="hidden" name="pago_id" id="pago_id_rechazar">
                    <input type="hidden" name="accion" value="rechazar">
                    <div class="form-group">
                        <label for="observaciones_rechazo">Motivo del Rechazo *</label>
                        <textarea class="form-control" 
                                  id="observaciones_rechazo" 
                                  name="observaciones" 
                                  rows="4" 
                                  required 
                                  placeholder="Indica el motivo por el cual se rechaza el pago..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Rechazar Pago</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function rechazarPago(pagoId) {
    document.getElementById('pago_id_rechazar').value = pagoId;
    $('#modalRechazarPago').modal('show');
}
</script>

<style>
.nav-tabs .nav-link {
    color: var(--dark-color);
}

.nav-tabs .nav-link.active {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.table th {
    background: #f8f9fa;
    font-weight: 600;
}
</style>
