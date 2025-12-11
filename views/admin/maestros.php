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
            <div class="d-flex justify-content-between align-items-center" style="gap: 3rem;">
                <h3 class="card-title">Maestros - <?php echo ucfirst($estado); ?></h3>
                <div style="display: flex; gap: 0.5rem;">
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
                                <th style="text-align: center;">Acciones</th>
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
                                    <div style="display: flex; gap: 0.5rem; justify-content: center; flex-wrap: wrap;">
                                        <?php if ($estado === 'pendiente'): ?>
                                        <button class="btn btn-sm btn-success" onclick="validarPerfil(<?php echo $maestro['id']; ?>)">
                                            <i class="fas fa-check"></i> Validar
                                        </button>
                                        <button class="btn btn-sm btn-danger" onclick="rechazarPerfil(<?php echo $maestro['id']; ?>)">
                                            <i class="fas fa-times"></i> Rechazar
                                        </button>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-outline" onclick="verDetalleMaestro(<?php echo $maestro['id']; ?>)">
                                            <i class="fas fa-eye"></i> Ver
                                        </button>
                                    </div>
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
            </div>
            <form method="POST" action="<?php echo BASE_URL; ?>admin/validar-perfil">
                <input type="hidden" name="maestro_id" id="maestro_id_rechazar">
                <input type="hidden" name="accion" value="rechazar">
                <div class="modal-body text-center">
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

<!-- Modal para ver detalles del maestro -->
<div id="detalleMaestroModal" class="modal" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Detalle del Maestro</h3>
            </div>
            <div class="modal-body">
                <div id="loadingDetalle" class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-3x" style="color: var(--primary-color);"></i>
                    <p class="mt-2">Cargando información...</p>
                </div>
                
                <div id="contentDetalle" style="display: none;">
                    <div class="text-center mb-4">
                        <img id="m_foto" src="" alt="Foto de Perfil" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid white;">
                        <h4 id="m_nombre" class="mb-1"></h4>
                        <p id="m_email" class="text-muted mb-1"></p>
                        <span id="m_estado" class="badge"></span>
                    </div>
                    
                    <div class="text-center">
                        <h5 class="section-title"><i class="fas fa-id-card"></i> Información Personal</h5>
                        <div class="info-grid">
                            <div><strong>DNI:</strong> <span id="m_dni"></span></div>
                            <div><strong>Teléfono:</strong> <span id="m_telefono"></span></div>
                            <div><strong>Fecha Registro:</strong> <span id="m_fecha_registro"></span></div>
                        </div>
                        
                        <h5 class="section-title mt-4"><i class="fas fa-file-alt"></i> Documentos</h5>
                        <div id="m_documentos" class="list-group">
                            <!-- Documentos populados via JS -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeDetalleModal()">Cerrar</button>
            </div>
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
    document.getElementById('rechazarModal').style.display = 'flex';
}

function closeRechazarModal() {
    document.getElementById('rechazarModal').style.display = 'none';
    document.getElementById('motivo_rechazo').value = '';
}

function verDetalleMaestro(id) {
    const modal = document.getElementById('detalleMaestroModal');
    const loading = document.getElementById('loadingDetalle');
    const content = document.getElementById('contentDetalle');
    
    modal.style.display = 'flex';
    loading.style.display = 'block';
    content.style.display = 'none';

    // Fetch details
    fetch('<?php echo BASE_URL; ?>admin/get-maestro-details?id=' + id)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                closeDetalleModal();
                return;
            }

            const u = data.usuario;
            const m = data.maestro;
            
            // Debug logging
            console.log('API Response:', data);
            console.log('Documents:', data.documentos);
            if (data.debug) {
                console.log('Debug Info:', data.debug);
                console.log('Maestro ID being queried:', data.debug.maestro_id);
                console.log('Document count from query:', data.debug.document_count);
            }
            
            // Populate basic info
            document.getElementById('m_nombre').textContent = u.nombre_completo;
            document.getElementById('m_email').textContent = u.email;
            document.getElementById('m_dni').textContent = u.dni;
            document.getElementById('m_telefono').textContent = u.telefono || 'No registrado';
            document.getElementById('m_fecha_registro').textContent = u.fecha_registro;
            
            // Image
            const imgPath = u.foto_perfil ? '<?php echo UPLOAD_URL; ?>' + u.foto_perfil : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(u.nombre_completo);
            document.getElementById('m_foto').src = imgPath;

            // Documents
            const docsContainer = document.getElementById('m_documentos');
            docsContainer.innerHTML = '';
            if (data.documentos && data.documentos.length > 0) {
                data.documentos.forEach(doc => {
                    console.log('Processing document:', doc);
                    // Determine icon based on file type (matching database ENUM)
                    let icon = 'fa-file';
                    let displayName = doc.tipo_documento;
                    
                    if(doc.tipo_documento === 'dni') {
                        icon = 'fa-id-card';
                        displayName = 'DNI';
                    }
                    if(doc.tipo_documento === 'certificado') {
                        icon = 'fa-certificate';
                        displayName = 'Certificado';
                    }
                    if(doc.tipo_documento === 'foto_trabajo') {
                        icon = 'fa-image';
                        displayName = 'Foto de Trabajo';
                    }
                    
                    const docUrl = '<?php echo UPLOAD_URL; ?>' + doc.ruta_archivo;
                    console.log('Document URL:', docUrl);
                    
                    docsContainer.innerHTML += `
                        <div class="d-flex justify-content-between align-items-center p-2 border-bottom">
                            <div>
                                <i class="fas ${icon} me-2 text-primary"></i>
                                <strong>${displayName}</strong>
                                <br>
                                <small class="text-muted">${doc.nombre_archivo || 'Documento'}</small>
                            </div>
                            <a href="${docUrl}" target="_blank" class="btn btn-sm btn-primary" download>
                                <i class="fas fa-download"></i> Descargar
                            </a>
                        </div>
                    `;
                });
            } else {
                console.log('No documents found or documents array is empty');
                docsContainer.innerHTML = '<p class="text-muted">No hay documentos subidos.</p>';
            }

            // Show content
            loading.style.display = 'none';
            content.style.display = 'block';
        })
        .catch(err => {
            console.error(err);
            alert('Error al cargar los detalles');
            closeDetalleModal();
        });
}

function closeDetalleModal() {
    document.getElementById('detalleMaestroModal').style.display = 'none';
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
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(5px);
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Modal Professional Design */
.modal-dialog {
    background: #f8f9fa;
    border-radius: 16px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
    border: none;
    overflow: hidden;
    max-width: 600px; /* Reasonable width for reject modal */
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-content {
    padding: 0;
}

.modal-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #ff8e5d 100%);
    color: white;
    padding: 1.5rem 2rem;
    border-bottom: none;
    display: flex;
    justify-content: center; /* Centered content */
}

.modal-header h3 {
    font-weight: 700;
    font-size: 1.5rem;
    letter-spacing: 0.5px;
}

.modal-close {
    color: rgba(255,255,255,0.8);
    transition: color 0.2s;
    text-shadow: none;
    opacity: 1;
}

.modal-close:hover {
    color: white;
    transform: scale(1.1);
}

.modal-body {
    padding: 2rem;
}

/* Profile Left Column */
#m_foto {
    border: 4px solid white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

#m_foto:hover {
    transform: scale(1.02);
}

#m_nombre {
    font-weight: 700;
    color: #2c3e50;
    margin-top: 1.5rem; /* Increased margin */
    margin-bottom: 0.5rem;
    font-size: 1.25rem; /* Slightly larger */
}

#m_email {
    font-size: 1rem;
    color: #6c757d;
    margin-bottom: 1rem;
}

/* Sections */
.section-title {
    color: var(--primary-color);
    font-size: 1.1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center; /* Center the title */
    gap: 0.8rem;
    border-bottom: 2px solid rgba(0,0,0,0.05);
    padding-bottom: 0.8rem;
}

.section-title i {
    background: rgba(255, 106, 42, 0.1); 
    padding: 10px;
    border-radius: 8px;
    font-size: 1.1rem;
}

.info-grid {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    margin-bottom: 2rem;
    font-size: 1rem;
    max-width: 600px; /* Limit width for better readability */
    margin-left: auto;
    margin-right: auto;
}

.info-grid div {
    margin-bottom: 0.8rem; /* More space between rows */
    display: flex;
    justify-content: space-between;
    border-bottom: 1px dashed #eee;
    padding-bottom: 0.8rem; /* More space inside rows */
    line-height: 1.6; /* Better text flow */
}

.info-grid div:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

/* Documents */
.list-group {
    border: none;
    gap: 0.8rem;
    overflow: visible; /* Allow shadows */
}

.list-group > div {
    background: white;
    border: 1px solid #eee;
    border-radius: 10px;
    padding: 1rem;
    transition: all 0.2s;
    box-shadow: 0 2px 5px rgba(0,0,0,0.02);
}

.list-group > div:hover {
    border-color: var(--primary-color);
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    transform: translateY(-2px);
}

.modal-footer {
    background: white;
    border-top: 1px solid #eee;
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: center; /* Centered button */
    gap: 1rem;
}

/* Reject Modal Specific Styles */
#rechazarModal .modal-body {
    max-width: 500px;
    margin: 0 auto;
}

#rechazarModal .form-label {
    display: block;
    text-align: center;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #2c3e50;
}

#rechazarModal .form-control {
    width: 100%;
    text-align: left; /* Keep textarea text left-aligned for readability */
}

</style>

