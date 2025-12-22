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
                                <th>Pago Realizado</th>
                                <th style="text-align: center;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="maestrosTableBody">
                            <?php foreach ($maestros as $maestro): ?>
                            <tr data-maestro-id="<?php echo $maestro['id']; ?>" data-pago-id="<?php echo !empty($maestro['pago']) ? $maestro['pago']['id'] : ''; ?>">
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
                                    <?php if (!empty($maestro['pago'])): 
                                        // Verificar si el pago está caducado
                                        $pago_caducado = false;
                                        $estado_display = $maestro['pago']['estado'];
                                        
                                        if (!empty($maestro['pago']['fecha_expiracion']) && $maestro['pago']['estado'] === 'verificado') {
                                            $fecha_expiracion = new DateTime($maestro['pago']['fecha_expiracion']);
                                            $fecha_actual = new DateTime();
                                            if ($fecha_expiracion < $fecha_actual) {
                                                $pago_caducado = true;
                                                $estado_display = 'caducado';
                                            }
                                        }
                                        
                                        // Determinar el color del badge según el estado
                                        $badge_class = 'badge-secondary';
                                        if ($pago_caducado) {
                                            $badge_class = 'badge-expired';
                                        } elseif ($maestro['pago']['estado'] === 'verificado') {
                                            $badge_class = 'badge-success';
                                        } elseif ($maestro['pago']['estado'] === 'rechazado') {
                                            $badge_class = 'badge-danger';
                                        } else {
                                            $badge_class = 'badge-warning';
                                        }
                                    ?>
                                        <span class="badge <?php echo $badge_class; ?>" data-estado="<?php echo htmlspecialchars($maestro['pago']['estado']); ?>" data-expiracion="<?php echo !empty($maestro['pago']['fecha_expiracion']) ? htmlspecialchars($maestro['pago']['fecha_expiracion']) : ''; ?>">
                                            <?php echo ucfirst($estado_display); ?>
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            S/ <?php echo number_format($maestro['pago']['monto'], 2); ?>
                                        </small>
                                        <?php if ($pago_caducado && !empty($maestro['pago']['fecha_expiracion'])): ?>
                                        <br>
                                        <small class="text-danger" style="font-size: 0.75rem;">
                                            <i class="fas fa-clock"></i> Expiró: <?php echo date('d/m/Y H:i', strtotime($maestro['pago']['fecha_expiracion'])); ?>
                                        </small>
                                        <?php elseif (!empty($maestro['pago']['fecha_expiracion']) && $maestro['pago']['estado'] === 'verificado'): ?>
                                        <br>
                                        <small class="text-muted" style="font-size: 0.75rem;">
                                            <i class="fas fa-clock"></i> Expira: <?php echo date('d/m/Y H:i', strtotime($maestro['pago']['fecha_expiracion'])); ?>
                                        </small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Sin pago</span>
                                    <?php endif; ?>
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
                                        <?php if (!empty($maestro['pago'])): ?>
                                        <button class="btn btn-sm btn-purple" onclick="verPago(<?php echo $maestro['id']; ?>)">
                                            <i class="fas fa-money-bill-wave"></i> Pagos
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

<!-- Modal de confirmación para validar perfil -->
<div id="validarConfirmModal" class="modal" style="display: none;" onclick="if(event.target === this) closeValidarConfirmModal()">
    <div class="modal-dialog modal-confirm" onclick="event.stopPropagation()">
        <div class="modal-content">
            <div class="modal-header confirm-header-success">
                <span class="modal-close" onclick="closeValidarConfirmModal()">&times;</span>
                <div class="confirm-icon-wrapper">
                    <i class="fas fa-check-circle confirm-icon"></i>
                </div>
                <h3>¿Validar este perfil?</h3>
            </div>
            <div class="modal-body confirm-body">
                <p class="confirm-message">
                    ¿Estás seguro de que deseas <strong>validar este perfil</strong>?<br>
                    El maestro podrá aparecer en las búsquedas de los clientes.
                </p>
            </div>
            <div class="modal-footer confirm-footer">
                <button type="button" class="btn btn-outline-cancel" onclick="closeValidarConfirmModal()">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="button" class="btn btn-confirm-success" onclick="confirmarValidacion()">
                    <i class="fas fa-check"></i> Sí, Validar
                </button>
            </div>
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

<!-- Modal para ver comprobante de pago -->
<div id="pagoModal" class="modal" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-money-bill-wave"></i> Comprobante de Pago</h3>
                <span class="modal-close" onclick="closePagoModal()">&times;</span>
            </div>
            <div class="modal-body">
                <div id="loadingPago" class="text-center py-5">
                    <i class="fas fa-spinner fa-spin fa-3x" style="color: var(--primary-color);"></i>
                    <p class="mt-2">Cargando información del pago...</p>
                </div>
                
                <div id="contentPago" style="display: none;">
                    <div class="pago-info-section mb-4">
                        <h4 class="section-title-pago"><i class="fas fa-info-circle"></i> Información del Pago</h4>
                        <div class="info-grid-pago">
                            <div>
                                <strong>Estado:</strong>
                                <span id="pago_estado" class="badge-pago"></span>
                            </div>
                            <div>
                                <strong>Monto:</strong>
                                <span id="pago_monto" class="text-success font-weight-bold"></span>
                            </div>
                            <div>
                                <strong>Método de Pago:</strong>
                                <span id="pago_metodo"></span>
                            </div>
                            <div>
                                <strong>Número de Comprobante:</strong>
                                <span id="pago_comprobante"></span>
                            </div>
                            <div>
                                <strong>Fecha de Pago:</strong>
                                <span id="pago_fecha"></span>
                            </div>
                            <div>
                                <strong>Fecha de Expiración:</strong>
                                <span id="pago_expiracion"></span>
                            </div>
                        </div>
                    </div>

                    <div class="comprobante-section">
                        <h4 class="section-title-pago"><i class="fas fa-receipt"></i> Comprobante</h4>
                        <div class="comprobante-container">
                            <img id="pago_imagen" src="" alt="Comprobante de Pago" class="comprobante-image">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closePagoModal()">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver detalles del maestro -->
<div id="detalleMaestroModal" class="modal" style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Detalle del Maestro</h3>
                <span class="modal-close" onclick="closeDetalleModal()">&times;</span>
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
let maestroIdToValidate = null;

function validarPerfil(id) {
    maestroIdToValidate = id;
    document.getElementById('validarConfirmModal').style.display = 'flex';
}

function closeValidarConfirmModal() {
    document.getElementById('validarConfirmModal').style.display = 'none';
    maestroIdToValidate = null;
}

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('validarConfirmModal');
        if (modal && modal.style.display === 'flex') {
            closeValidarConfirmModal();
        }
    }
});

function confirmarValidacion() {
    if (!maestroIdToValidate) return;
    
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?php echo BASE_URL; ?>admin/validar-perfil';
    
    const inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'maestro_id';
    inputId.value = maestroIdToValidate;
    
    const inputAccion = document.createElement('input');
    inputAccion.type = 'hidden';
    inputAccion.name = 'accion';
    inputAccion.value = 'validar';
    
    form.appendChild(inputId);
    form.appendChild(inputAccion);
    document.body.appendChild(form);
    form.submit();
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

function verPago(maestroId) {
    const modal = document.getElementById('pagoModal');
    const loading = document.getElementById('loadingPago');
    const content = document.getElementById('contentPago');
    
    modal.style.display = 'flex';
    loading.style.display = 'block';
    content.style.display = 'none';

    // Fetch pago details
    fetch('<?php echo BASE_URL; ?>admin/get-maestro-pago?id=' + maestroId)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                closePagoModal();
                return;
            }

            const pago = data.pago;
            
            // Populate pago info
            // Verificar si el pago está caducado
            let estadoDisplay = pago.estado;
            let esCaducado = false;
            
            if (pago.estado === 'verificado' && pago.fecha_expiracion) {
                const fechaExpiracion = new Date(pago.fecha_expiracion);
                const fechaActual = new Date();
                if (fechaExpiracion < fechaActual) {
                    esCaducado = true;
                    estadoDisplay = 'caducado';
                }
            }
            
            const estadoBadge = document.getElementById('pago_estado');
            estadoBadge.textContent = estadoDisplay.charAt(0).toUpperCase() + estadoDisplay.slice(1);
            
            // Determinar la clase del badge
            let badgeClass = 'badge-pago badge-warning';
            if (esCaducado) {
                badgeClass = 'badge-pago badge-expired';
            } else if (pago.estado === 'verificado') {
                badgeClass = 'badge-pago badge-success';
            } else if (pago.estado === 'rechazado') {
                badgeClass = 'badge-pago badge-danger';
            }
            
            estadoBadge.className = badgeClass;
            
            document.getElementById('pago_monto').textContent = 'S/ ' + parseFloat(pago.monto).toFixed(2);
            document.getElementById('pago_metodo').textContent = pago.metodo_pago ? pago.metodo_pago.charAt(0).toUpperCase() + pago.metodo_pago.slice(1) : 'N/A';
            document.getElementById('pago_comprobante').textContent = pago.numero_comprobante || 'N/A';
            document.getElementById('pago_fecha').textContent = pago.fecha_pago ? new Date(pago.fecha_pago).toLocaleString('es-PE') : 'N/A';
            
            // Mostrar fecha de expiración con indicador si está caducado
            const fechaExpiracionElement = document.getElementById('pago_expiracion');
            if (pago.fecha_expiracion) {
                const fechaExpiracion = new Date(pago.fecha_expiracion);
                const fechaActual = new Date();
                const fechaFormateada = fechaExpiracion.toLocaleString('es-PE');
                
                if (esCaducado) {
                    fechaExpiracionElement.innerHTML = '<span class="text-danger"><i class="fas fa-exclamation-triangle"></i> ' + fechaFormateada + ' (Caducado)</span>';
                } else {
                    fechaExpiracionElement.textContent = fechaFormateada;
                }
            } else {
                fechaExpiracionElement.textContent = 'N/A';
            }

            // Comprobante imagen
            const imgElement = document.getElementById('pago_imagen');
            if (pago.comprobante_imagen) {
                imgElement.src = '<?php echo UPLOAD_URL; ?>' + pago.comprobante_imagen;
                imgElement.style.display = 'block';
            } else {
                imgElement.style.display = 'none';
            }

            // Show content
            loading.style.display = 'none';
            content.style.display = 'block';
        })
        .catch(err => {
            console.error(err);
            alert('Error al cargar la información del pago');
            closePagoModal();
        });
}

function closePagoModal() {
    document.getElementById('pagoModal').style.display = 'none';
}

// Actualización en tiempo real del estado de pagos caducados
function actualizarEstadosPagos() {
    const badges = document.querySelectorAll('[data-estado="verificado"][data-expiracion]');
    const ahora = new Date();
    
    badges.forEach(badge => {
        const fechaExpiracionStr = badge.getAttribute('data-expiracion');
        if (!fechaExpiracionStr) return;
        
        const fechaExpiracion = new Date(fechaExpiracionStr);
        
        if (fechaExpiracion < ahora) {
            // El pago ha caducado
            badge.textContent = 'Caducado';
            badge.className = 'badge badge-expired';
            
            // Actualizar también la fecha de expiración si existe un elemento pequeño cerca
            const smallElement = badge.parentElement.querySelector('small.text-muted');
            if (smallElement && smallElement.textContent.includes('Expira:')) {
                smallElement.className = 'text-danger';
                smallElement.style.fontSize = '0.75rem';
                smallElement.innerHTML = '<i class="fas fa-clock"></i> Expiró: ' + 
                    fechaExpiracion.toLocaleDateString('es-PE', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
            }
        }
    });
}

// Ejecutar actualización cada minuto
setInterval(actualizarEstadosPagos, 60000);

// Ejecutar inmediatamente al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    actualizarEstadosPagos();
});

// Actualizar también cuando se vuelve a la pestaña del navegador
document.addEventListener('visibilitychange', function() {
    if (!document.hidden) {
        actualizarEstadosPagos();
    }
});

// ============================================
// ACTUALIZACIÓN EN TIEMPO REAL DE PAGOS
// ============================================
<?php if ($estado === 'pendiente'): ?>
let pollingInterval = null;
let maestrosActuales = new Map(); // Para rastrear maestros existentes

// Función para obtener el HTML del badge de pago
function getPagoBadgeHTML(pago) {
    if (!pago) {
        return '<span class="badge badge-secondary">Sin pago</span>';
    }
    
    const estado = pago.estado_display || pago.estado;
    const caducado = pago.caducado || false;
    
    let badge_class = 'badge-secondary';
    if (caducado) {
        badge_class = 'badge-expired';
    } else if (pago.estado === 'verificado') {
        badge_class = 'badge-success';
    } else if (pago.estado === 'rechazado') {
        badge_class = 'badge-danger';
    } else {
        badge_class = 'badge-warning';
    }
    
    let html = `<span class="badge ${badge_class}" data-estado="${pago.estado}" data-expiracion="${pago.fecha_expiracion || ''}">${estado.charAt(0).toUpperCase() + estado.slice(1)}</span>`;
    html += `<br><small class="text-muted">S/ ${parseFloat(pago.monto).toFixed(2)}</small>`;
    
    if (caducado && pago.fecha_expiracion) {
        const fechaExp = new Date(pago.fecha_expiracion);
        html += `<br><small class="text-danger" style="font-size: 0.75rem;"><i class="fas fa-clock"></i> Expiró: ${fechaExp.toLocaleDateString('es-PE')} ${fechaExp.toLocaleTimeString('es-PE', {hour: '2-digit', minute: '2-digit'})}</small>`;
    } else if (pago.fecha_expiracion && pago.estado === 'verificado') {
        const fechaExp = new Date(pago.fecha_expiracion);
        html += `<br><small class="text-muted" style="font-size: 0.75rem;"><i class="fas fa-clock"></i> Expira: ${fechaExp.toLocaleDateString('es-PE')} ${fechaExp.toLocaleTimeString('es-PE', {hour: '2-digit', minute: '2-digit'})}</small>`;
    }
    
    return html;
}

// Función para obtener el HTML de especialidades
function getEspecialidadesHTML(especialidades) {
    return especialidades.map(esp => `<span class="tag">${esp}</span>`).join(' ');
}

// Función para obtener el HTML de acciones
function getAccionesHTML(maestroId, tienePago) {
    let html = '<div style="display: flex; gap: 0.5rem; justify-content: center; flex-wrap: wrap;">';
    html += `<button class="btn btn-sm btn-success" onclick="validarPerfil(${maestroId})"><i class="fas fa-check"></i> Validar</button>`;
    html += `<button class="btn btn-sm btn-danger" onclick="rechazarPerfil(${maestroId})"><i class="fas fa-times"></i> Rechazar</button>`;
    if (tienePago) {
        html += `<button class="btn btn-sm btn-purple" onclick="verPago(${maestroId})"><i class="fas fa-money-bill-wave"></i> Pagos</button>`;
    }
    html += `<button class="btn btn-sm btn-outline" onclick="verDetalleMaestro(${maestroId})"><i class="fas fa-eye"></i> Ver</button>`;
    html += '</div>';
    return html;
}

// Función para actualizar la tabla con datos en tiempo real
function actualizarTablaMaestros(maestrosData) {
    const tbody = document.getElementById('maestrosTableBody');
    if (!tbody) return;
    
    const maestrosMap = new Map();
    maestrosData.forEach(maestro => {
        maestrosMap.set(maestro.id, maestro);
    });
    
    // Actualizar filas existentes o agregar nuevas
    maestrosData.forEach(maestro => {
        let row = tbody.querySelector(`tr[data-maestro-id="${maestro.id}"]`);
        
        if (!row) {
            // Crear nueva fila si no existe
            row = document.createElement('tr');
            row.setAttribute('data-maestro-id', maestro.id);
            row.setAttribute('data-pago-id', maestro.pago ? maestro.pago.id : '');
            row.innerHTML = `
                <td>${maestro.nombre_completo}</td>
                <td>${maestro.email}</td>
                <td>${maestro.telefono}</td>
                <td>${maestro.dni}</td>
                <td>${getEspecialidadesHTML(maestro.especialidades)}</td>
                <td>${maestro.documentos_count} documento(s)</td>
                <td>${getPagoBadgeHTML(maestro.pago)}</td>
                <td>${getAccionesHTML(maestro.id, !!maestro.pago)}</td>
            `;
            tbody.appendChild(row);
            
            // Animación para nueva fila
            row.style.opacity = '0';
            row.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                row.style.transition = 'all 0.3s ease';
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
            }, 10);
        } else {
            // Actualizar fila existente
            const pagoCell = row.cells[6]; // Columna de pago
            const pagoIdActual = row.getAttribute('data-pago-id');
            const pagoIdNuevo = maestro.pago ? maestro.pago.id : '';
            
            // Solo actualizar si el pago cambió
            if (pagoIdActual !== pagoIdNuevo || !pagoIdActual) {
                pagoCell.innerHTML = getPagoBadgeHTML(maestro.pago);
                row.setAttribute('data-pago-id', pagoIdNuevo);
                
                // Actualizar botón de pagos si es necesario
                const accionesCell = row.cells[7];
                accionesCell.innerHTML = getAccionesHTML(maestro.id, !!maestro.pago);
                
                // Efecto visual de actualización
                pagoCell.style.backgroundColor = '#fff3cd';
                setTimeout(() => {
                    pagoCell.style.transition = 'background-color 0.5s ease';
                    pagoCell.style.backgroundColor = '';
                }, 500);
            }
        }
    });
    
    // Remover maestros que ya no están pendientes (opcional, solo si cambian de estado)
    // Esto se puede activar si queremos que desaparezcan cuando se validan
}

// Función para obtener maestros actualizados del servidor
function obtenerMaestrosActualizados() {
    fetch('<?php echo BASE_URL; ?>admin/get-maestros-pendientes-actualizados', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.maestros) {
            actualizarTablaMaestros(data.maestros);
        }
    })
    .catch(error => {
        console.error('Error al obtener maestros actualizados:', error);
    });
}

// Iniciar polling solo en la página de pendientes
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const estado = urlParams.get('estado') || 'pendiente';
    
    if (estado === 'pendiente') {
        // Iniciar polling cada 3 segundos para actualización en tiempo real
        pollingInterval = setInterval(obtenerMaestrosActualizados, 3000);
        
        // Ejecutar inmediatamente
        obtenerMaestrosActualizados();
        
        // Actualizar cuando se vuelve a la pestaña
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                obtenerMaestrosActualizados();
            }
        });
    }
});

// Limpiar intervalo al salir de la página
window.addEventListener('beforeunload', function() {
    if (pollingInterval) {
        clearInterval(pollingInterval);
    }
});
<?php endif; ?>
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

/* Badges básicos */
.badge {
    display: inline-block;
    padding: 0.35em 0.65em;
    font-size: 0.875em;
    font-weight: 600;
    line-height: 1;
    text-align: center;
    white-space: nowrap;
    vertical-align: baseline;
    border-radius: 0.375rem;
    transition: all 0.2s ease;
}

.badge-success {
    background-color: #28a745;
    color: #fff;
}

.badge-danger {
    background-color: #dc3545;
    color: #fff;
}

.badge-warning {
    background-color: #ffc107;
    color: #212529;
}

.badge-secondary {
    background-color: #6c757d;
    color: #fff;
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
    align-items: center;
    position: relative; /* Para posicionar el botón close */
}

.modal-header h3 {
    font-weight: 700;
    font-size: 1.5rem;
    letter-spacing: 0.5px;
    margin: 0;
}

/* Modal de Confirmación de Validación */
.modal-confirm {
    max-width: 450px;
}

.confirm-header-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    text-align: center;
    padding: 2.5rem 2rem 2rem;
    border-bottom: none;
    position: relative;
}

.confirm-header-success .modal-close {
    position: absolute;
    top: 1rem;
    right: 1.5rem;
    background: rgba(255, 255, 255, 0.2);
    border-color: rgba(255, 255, 255, 0.3);
}

.confirm-header-success .modal-close:hover {
    background: rgba(255, 255, 255, 0.95);
    color: #28a745;
}

.confirm-icon-wrapper {
    margin-bottom: 1rem;
}

.confirm-icon {
    font-size: 4rem;
    color: white;
    animation: scaleIn 0.3s ease-out;
}

@keyframes scaleIn {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

.confirm-header-success h3 {
    color: white;
    font-size: 1.5rem;
    font-weight: 600;
    margin: 0;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.confirm-body {
    padding: 2rem;
    text-align: center;
}

.confirm-message {
    font-size: 1.1rem;
    color: #495057;
    line-height: 1.6;
    margin: 0;
}

.confirm-message strong {
    color: #28a745;
    font-weight: 600;
}

.confirm-footer {
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: center;
    gap: 1rem;
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
}

.btn-confirm-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border: none;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 6px rgba(40, 167, 69, 0.25);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-confirm-success:hover {
    background: linear-gradient(135deg, #218838 0%, #1da88a 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(40, 167, 69, 0.35);
}

.btn-confirm-success:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.25);
}

.btn-outline-cancel {
    background: white;
    color: #6c757d;
    border: 2px solid #dee2e6;
    padding: 0.75rem 2rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-outline-cancel:hover {
    background: #f8f9fa;
    border-color: #adb5bd;
    color: #495057;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.btn-outline-cancel:active {
    transform: translateY(0);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.modal-close {
    position: absolute;
    top: 1rem;
    right: 1.5rem;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    color: white;
    font-size: 1.5rem;
    font-weight: 300;
    line-height: 1;
    cursor: pointer;
    transition: all 0.3s ease;
    text-shadow: none;
    opacity: 1;
    z-index: 10;
    backdrop-filter: blur(4px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.modal-close:hover {
    background: rgba(255, 255, 255, 0.95);
    color: var(--primary-color);
    border-color: rgba(255, 255, 255, 0.9);
    transform: scale(1.1) rotate(90deg);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

.modal-close:active {
    transform: scale(0.95) rotate(90deg);
    background: rgba(255, 255, 255, 1);
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

/* Pago Modal Styles */
.btn-purple {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    transition: all 0.3s ease;
}

.btn-purple:hover {
    background: linear-gradient(135deg, #5568d3 0%, #653a8f 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.section-title-pago {
    color: var(--primary-color);
    font-size: 1.1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.8rem;
    border-bottom: 2px solid rgba(0,0,0,0.05);
    padding-bottom: 0.8rem;
}

.section-title-pago i {
    background: rgba(102, 126, 234, 0.1);
    padding: 10px;
    border-radius: 8px;
    font-size: 1.1rem;
    color: #667eea;
}

.pago-info-section {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 12px;
    margin-bottom: 2rem;
}

.info-grid-pago {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.info-grid-pago div {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: white;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.info-grid-pago strong {
    color: #495057;
    font-weight: 600;
}

.badge-pago {
    padding: 0.4rem 0.8rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.badge-pago.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-pago.badge-warning {
    background: #fff3cd;
    color: #856404;
}

.badge-pago.badge-danger {
    background: #f8d7da;
    color: #721c24;
}

.badge-pago.badge-expired,
.badge.badge-expired {
    background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    color: white;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    animation: pulse-expired 2s ease-in-out infinite;
}

@keyframes pulse-expired {
    0%, 100% {
        opacity: 1;
        box-shadow: 0 0 0 0 rgba(108, 117, 125, 0.7);
    }
    50% {
        opacity: 0.9;
        box-shadow: 0 0 0 4px rgba(108, 117, 125, 0);
    }
}

.comprobante-section {
    margin-top: 2rem;
}

.comprobante-container {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    text-align: center;
}

.comprobante-image {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    cursor: pointer;
    transition: transform 0.3s ease;
}

.comprobante-image:hover {
    transform: scale(1.02);
}

@media (max-width: 768px) {
    .info-grid-pago {
        grid-template-columns: 1fr;
    }
}

</style>

