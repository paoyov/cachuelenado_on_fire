<?php
$title = 'Gestionar Maestros';
?>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-user-check"></i> Gestionar Maestros</h1>
        <p>Validar o rechazar perfiles de maestros</p>
    </div>
</div>

<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title">Maestros - <?php echo ucfirst($estado); ?></h3>
                <div>
                    <a href="<?php echo BASE_URL; ?>admin/maestros?estado=pendiente" class="btn btn-sm <?php echo $estado === 'pendiente' ? 'btn-primary' : 'btn-outline'; ?>">Pendientes</a>
                    <a href="<?php echo BASE_URL; ?>admin/maestros?estado=validado" class="btn btn-sm <?php echo $estado === 'validado' ? 'btn-primary' : 'btn-outline'; ?>">Validados</a>
                    <a href="<?php echo BASE_URL; ?>admin/maestros?estado=rechazado" class="btn btn-sm <?php echo $estado === 'rechazado' ? 'btn-primary' : 'btn-outline'; ?>">Rechazados</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($maestros)): ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>DNI</th>
                                <th>Especialidades</th>
                                <th>Documentos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($maestros as $maestro): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($maestro['nombre_completo']); ?></td>
                                <td><?php echo htmlspecialchars($maestro['email']); ?></td>
                                <td><?php echo htmlspecialchars($maestro['telefono']); ?></td>
                                <td><?php echo htmlspecialchars($maestro['dni']); ?></td>
                                <td>
                                    <?php
                                    $maestroModel = new Maestro($this->db);
                                    $especialidades = $maestroModel->getEspecialidades($maestro['id']);
                                    foreach ($especialidades as $esp) {
                                        echo '<span class="tag">' . htmlspecialchars($esp['nombre']) . '</span> ';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    $documentoModel = new DocumentoMaestro($this->db);
                                    $documentos = $documentoModel->getByMaestro($maestro['id']);
                                    echo count($documentos) . ' documento(s)';
                                    ?>
                                </td>
                                <td>
                                    <?php if ($estado === 'pendiente'): ?>
                                    <button class="btn btn-sm btn-success" onclick="validarPerfil(<?php echo $maestro['id']; ?>)">
                                        <i class="fas fa-check"></i> Validar
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="rechazarPerfil(<?php echo $maestro['id']; ?>)">
                                        <i class="fas fa-times"></i> Rechazar
                                    </button>
                                    <?php endif; ?>
                                    <a href="<?php echo BASE_URL; ?>maestro/perfil?id=<?php echo $maestro['id']; ?>" class="btn btn-sm btn-outline">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center" style="color: var(--gray-color);">No hay maestros con estado "<?php echo $estado; ?>"</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para rechazar perfil -->
<div id="rechazarModal" class="modal" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Rechazar Perfil</h3>
                <span class="modal-close" onclick="closeRechazarModal()">&times;</span>
            </div>
            <form method="POST" action="<?php echo BASE_URL; ?>admin/validar-perfil">
                <input type="hidden" name="maestro_id" id="maestro_id_rechazar">
                <input type="hidden" name="accion" value="rechazar">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="motivo_rechazo" class="form-label">Motivo del Rechazo *</label>
                        <textarea name="motivo_rechazo" id="motivo_rechazo" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline" onclick="closeRechazarModal()">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Rechazar Perfil</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function validarPerfil(id) {
    if (confirm('¿Estás seguro de validar este perfil?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo BASE_URL; ?>admin/validar-perfil';
        
        const inputId = document.createElement('input');
        inputId.type = 'hidden';
        inputId.name = 'maestro_id';
        inputId.value = id;
        
        const inputAccion = document.createElement('input');
        inputAccion.type = 'hidden';
        inputAccion.name = 'accion';
        inputAccion.value = 'validar';
        
        form.appendChild(inputId);
        form.appendChild(inputAccion);
        document.body.appendChild(form);
        form.submit();
    }
}

function rechazarPerfil(id) {
    document.getElementById('maestro_id_rechazar').value = id;
    document.getElementById('rechazarModal').style.display = 'block';
}

function closeRechazarModal() {
    document.getElementById('rechazarModal').style.display = 'none';
    document.getElementById('motivo_rechazo').value = '';
}
</script>

<style>
.table {
    width: 100%;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid var(--light-color);
}

.table th {
    background: var(--light-color);
    font-weight: 600;
}

.table-responsive {
    overflow-x: auto;
}

.modal {
    position: fixed;
    z-index: 2000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-dialog {
    background: var(--white);
    border-radius: var(--border-radius);
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-content {
    padding: 0;
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--light-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    margin: 0;
}

.modal-close {
    font-size: 2rem;
    cursor: pointer;
    color: var(--gray-color);
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    padding: 1.5rem;
    border-top: 1px solid var(--light-color);
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}
</style>

