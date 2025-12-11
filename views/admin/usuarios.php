<?php
$title = 'Gestión de Usuarios';
?>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-users-cog"></i> Gestión de Usuarios</h1>
        <p>Administra las cuentas de clientes, maestros y administradores</p>
    </div>
</div>

<div class="container">
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-4">
            <div class="stat-card bg-primary-light">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-info">
                    <h3><?php echo count($usuarios); ?></h3>
                    <p>Total Usuarios</p>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card bg-success-light">
                <div class="stat-icon"><i class="fas fa-user-check"></i></div>
                <div class="stat-info">
                    <h3><?php echo count(array_filter($usuarios, fn($u) => $u['estado'] === 'activo')); ?></h3>
                    <p>Activos</p>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="stat-card bg-warning-light">
                <div class="stat-icon"><i class="fas fa-user-clock"></i></div>
                <div class="stat-info">
                    <h3><?php echo count(array_filter($usuarios, fn($u) => $u['estado'] === 'suspendido')); ?></h3>
                    <p>Suspendidos</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-primary font-weight-bold"><i class="fas fa-list"></i> Lista de Usuarios</h5>
            <div class="card-actions">
                <div class="btn-group">
                    <a href="<?php echo BASE_URL; ?>admin/usuarios" class="btn btn-sm <?php echo !$tipo ? 'btn-primary' : 'btn-outline-primary'; ?>">Todos</a>
                    <a href="<?php echo BASE_URL; ?>admin/usuarios?tipo=cliente" class="btn btn-sm <?php echo $tipo === 'cliente' ? 'btn-primary' : 'btn-outline-primary'; ?>">Clientes</a>
                    <a href="<?php echo BASE_URL; ?>admin/usuarios?tipo=maestro" class="btn btn-sm <?php echo $tipo === 'maestro' ? 'btn-primary' : 'btn-outline-primary'; ?>">Maestros</a>
                    <a href="<?php echo BASE_URL; ?>admin/usuarios?tipo=administrador" class="btn btn-sm <?php echo $tipo === 'administrador' ? 'btn-primary' : 'btn-outline-primary'; ?>">Admins</a>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light text-muted">
                        <tr>
                            <th class="border-0 px-4 py-3 text-center">Usuario</th>
                            <th class="border-0 py-3 text-center">Rol</th>
                            <th class="border-0 py-3 text-center">Estado</th>
                            <th class="border-0 py-3 text-center">Registro</th>
                            <th class="border-0 px-4 py-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($usuarios) && count($usuarios) > 0): ?>
                            <?php foreach ($usuarios as $u): ?>
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="avatar-circle me-3">
                                                <?php if (!empty($u['foto_perfil'])): ?>
                                                    <img src="<?php echo UPLOAD_URL . $u['foto_perfil']; ?>" alt="Avatar">
                                                <?php else: ?>
                                                    <span><?php echo strtoupper(substr($u['nombre_completo'], 0, 1)); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-start">
                                                <h6 class="mb-0 text-dark font-weight-bold"><?php echo htmlspecialchars($u['nombre_completo']); ?></h6>
                                                <small class="text-muted"><?php echo htmlspecialchars($u['email']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 text-center">
                                        <?php 
                                            $roleClass = match($u['tipo_usuario']) {
                                                'administrador' => 'badge-purple',
                                                'maestro' => 'badge-info',
                                                default => 'badge-secondary'
                                            };
                                            $roleIcon = match($u['tipo_usuario']) {
                                                'administrador' => 'fa-user-shield',
                                                'maestro' => 'fa-hard-hat',
                                                default => 'fa-user'
                                            };
                                        ?>
                                        <span class="badge-pill <?php echo $roleClass; ?>">
                                            <i class="fas <?php echo $roleIcon; ?> me-1"></i> <?php echo ucfirst($u['tipo_usuario']); ?>
                                        </span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="status-indicator status-<?php echo $u['estado']; ?>">
                                            <i class="fas fa-circle"></i> <?php echo ucfirst($u['estado']); ?>
                                        </span>
                                    </td>
                                    <td class="py-3 text-center text-muted">
                                        <i class="far fa-calendar-alt me-1"></i> <?php echo date('d M Y', strtotime($u['fecha_registro'])); ?>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="action-buttons d-flex justify-content-center gap-2">
                                            <button type="button" 
                                                    class="btn-icon btn-icon-primary view-user-btn" 
                                                    title="Ver Detalle"
                                                    data-user='<?php echo htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8'); ?>'>
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <?php if ($u['estado'] !== 'suspendido'): ?>
                                            <button type="button" 
                                                    class="btn-icon btn-icon-warning suspend-user-btn" 
                                                    title="Suspender"
                                                    data-user-id="<?php echo $u['id']; ?>"
                                                    data-user-name="<?php echo htmlspecialchars($u['nombre_completo']); ?>"
                                                    data-action="suspender">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                            <?php else: ?>
                                            <button type="button" 
                                                    class="btn-icon btn-icon-success activate-user-btn" 
                                                    title="Activar"
                                                    data-user-id="<?php echo $u['id']; ?>"
                                                    data-user-name="<?php echo htmlspecialchars($u['nombre_completo']); ?>"
                                                    data-action="activar">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                            <?php endif; ?>

                                            <button type="button" 
                                                    class="btn-icon btn-icon-danger delete-user-btn" 
                                                    title="Eliminar"
                                                    data-user-id="<?php echo $u['id']; ?>"
                                                    data-user-name="<?php echo htmlspecialchars($u['nombre_completo']); ?>"
                                                    data-action="eliminar">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center py-5 text-muted">No se encontraron usuarios.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3 border-top">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <small class="text-muted">
                        <i class="fas fa-users me-2"></i>
                        Mostrando <strong><?php echo count($usuarios); ?></strong> registros
                        <?php if ($tipo): ?>
                            de tipo <strong><?php echo ucfirst($tipo); ?></strong>
                        <?php endif; ?>
                    </small>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">
                        <i class="fas fa-clock me-2"></i>
                        Última actualización: <?php echo date('d/m/Y H:i'); ?>
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle Usuario -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Detalle del Usuario</h3>
                <button type="button" class="close-modal-btn" onclick="closeUserModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img id="modal-img" src="" alt="Foto de Perfil" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid white; display: none;">
                    <div id="modal-initials" class="avatar-large mx-auto mb-3" style="display: none;"></div>
                    <h4 id="modal-name" class="mb-1"></h4>
                    <p id="modal-email" class="text-muted mb-2"></p>
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        <span id="modal-role" class="badge-pill"></span>
                        <span id="modal-status" class="status-indicator"></span>
                    </div>
                    <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-light border">
                        <span class="text-uppercase text-muted font-weight-bold" style="font-size: 0.7rem;">ID</span>
                        <span id="modal-id" class="font-family-monospace text-dark font-weight-bold">#</span>
                    </div>
                </div>
                
                <div class="text-center">
                    <h5 class="section-title"><i class="fas fa-id-card"></i> Información Personal</h5>
                    <div class="info-grid">
                        <div><strong>Teléfono:</strong> <span id="modal-phone"></span></div>
                        <div><strong>DNI:</strong> <span id="modal-dni"></span></div>
                        <div><strong>Fecha Registro:</strong> <span id="modal-date"></span></div>
                        <div><strong>Último Acceso:</strong> <span id="modal-last-access"></span></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeUserModal()">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade" id="modalBackdrop" style="display:none;"></div>

<!-- Modal Confirmación Suspender/Activar Usuario -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true" style="display:none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content confirm-modal-content">
            <div class="modal-body confirm-modal-body">
                <div class="confirm-icon-wrapper">
                    <i class="fas" id="confirm-icon"></i>
                </div>
                <h4 class="confirm-title" id="confirm-title"></h4>
                <p class="confirm-message" id="confirm-message"></p>
                <div class="confirm-buttons">
                    <form method="post" action="<?php echo BASE_URL; ?>admin/usuarios" id="confirm-form" style="display: inline;">
                        <input type="hidden" name="usuario_id" id="confirm-user-id">
                        <input type="hidden" name="accion" id="confirm-action">
                        <button type="button" class="btn btn-cancel" onclick="closeConfirmModal()">Cancelar</button>
                        <button type="submit" class="btn btn-confirm" id="confirm-submit-btn">Aceptar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade" id="confirmModalBackdrop" style="display:none;"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('userModal');
    const backdrop = document.getElementById('modalBackdrop');
    const UPLOAD_URL = '<?php echo UPLOAD_URL; ?>';
    const BASE_URL = '<?php echo BASE_URL; ?>';

    // Open Modal
    document.querySelectorAll('.view-user-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const user = JSON.parse(this.dataset.user);
            
            // Populate Data
            document.getElementById('modal-id').textContent = '#' + user.id;
            document.getElementById('modal-name').textContent = user.nombre_completo;
            document.getElementById('modal-email').textContent = user.email;
            document.getElementById('modal-phone').textContent = user.telefono || 'No registrado';
            document.getElementById('modal-dni').textContent = user.dni || 'No registrado';
            document.getElementById('modal-date').textContent = new Date(user.fecha_registro).toLocaleDateString();
            document.getElementById('modal-last-access').textContent = user.ultimo_acceso ? new Date(user.ultimo_acceso).toLocaleDateString() : 'Nunca';

            // Avatar
            const img = document.getElementById('modal-img');
            const initials = document.getElementById('modal-initials');
            if (user.foto_perfil) {
                img.src = UPLOAD_URL + user.foto_perfil;
                img.style.display = 'block';
                initials.style.display = 'none';
            } else {
                img.style.display = 'none';
                initials.style.display = 'block';
                initials.textContent = user.nombre_completo.charAt(0).toUpperCase();
            }

            // Role Badge
            const roleBadge = document.getElementById('modal-role');
            roleBadge.textContent = user.tipo_usuario.charAt(0).toUpperCase() + user.tipo_usuario.slice(1);
            roleBadge.className = 'badge-pill shadow-sm ' + (
                user.tipo_usuario === 'administrador' ? 'badge-purple' : 
                (user.tipo_usuario === 'maestro' ? 'badge-info' : 'badge-secondary')
            );

            // Status
            const statusSpan = document.getElementById('modal-status');
            statusSpan.innerHTML = `<i class="fas fa-circle fa-xs me-1"></i> ${user.estado.charAt(0).toUpperCase() + user.estado.slice(1)}`;
            statusSpan.className = `status-indicator px-3 py-1 bg-white rounded-pill shadow-sm border status-${user.estado}`;

            // Show
            modal.style.display = 'block';
            modal.classList.add('show');
            backdrop.style.display = 'block';
            backdrop.classList.add('show');
            document.body.classList.add('modal-open');
        });
    });

    // Close Modal
    window.closeUserModal = function() {
        modal.classList.remove('show');
        backdrop.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
            backdrop.style.display = 'none';
            document.body.classList.remove('modal-open');
        }, 150);
    };

    // Click outside to close
    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeUserModal();
        }
    });

    // Confirm Modal (Suspender/Activar)
    const confirmModal = document.getElementById('confirmModal');
    const confirmBackdrop = document.getElementById('confirmModalBackdrop');
    const confirmForm = document.getElementById('confirm-form');

    // Open Confirm Modal for Suspend
    document.querySelectorAll('.suspend-user-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            
            document.getElementById('confirm-user-id').value = userId;
            document.getElementById('confirm-action').value = 'suspender';
            document.getElementById('confirm-title').textContent = '¿Suspender usuario?';
            document.getElementById('confirm-message').textContent = `¿Estás seguro de que deseas suspender a ${userName}? El usuario no podrá acceder al sistema hasta que sea reactivado.`;
            document.getElementById('confirm-icon').className = 'fas fa-ban';
            document.getElementById('confirm-icon').parentElement.className = 'confirm-icon-wrapper warning';
            document.getElementById('confirm-submit-btn').className = 'btn btn-confirm btn-warning';
            document.getElementById('confirm-submit-btn').textContent = 'Suspender';
            
            showConfirmModal();
        });
    });

    // Open Confirm Modal for Activate
    document.querySelectorAll('.activate-user-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            
            document.getElementById('confirm-user-id').value = userId;
            document.getElementById('confirm-action').value = 'activar';
            document.getElementById('confirm-title').textContent = '¿Activar usuario?';
            document.getElementById('confirm-message').textContent = `¿Estás seguro de que deseas activar a ${userName}? El usuario podrá acceder nuevamente al sistema.`;
            document.getElementById('confirm-icon').className = 'fas fa-check-circle';
            document.getElementById('confirm-icon').parentElement.className = 'confirm-icon-wrapper success';
            document.getElementById('confirm-submit-btn').className = 'btn btn-confirm btn-success';
            document.getElementById('confirm-submit-btn').textContent = 'Activar';
            
            showConfirmModal();
        });
    });

    // Open Confirm Modal for Delete
    document.querySelectorAll('.delete-user-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const userName = this.dataset.userName;
            
            document.getElementById('confirm-user-id').value = userId;
            document.getElementById('confirm-action').value = 'eliminar';
            document.getElementById('confirm-title').textContent = '¿Eliminar usuario?';
            document.getElementById('confirm-message').textContent = `¿Estás seguro de que deseas eliminar permanentemente a ${userName}? Esta acción no se puede deshacer y se eliminará toda la información del usuario.`;
            document.getElementById('confirm-icon').className = 'fas fa-trash-alt';
            document.getElementById('confirm-icon').parentElement.className = 'confirm-icon-wrapper danger';
            document.getElementById('confirm-submit-btn').className = 'btn btn-confirm btn-danger';
            document.getElementById('confirm-submit-btn').textContent = 'Eliminar';
            
            showConfirmModal();
        });
    });

    function showConfirmModal() {
        confirmModal.style.display = 'flex';
        confirmModal.classList.add('show');
        confirmBackdrop.style.display = 'block';
        confirmBackdrop.classList.add('show');
        document.body.classList.add('modal-open');
    }

    window.closeConfirmModal = function() {
        confirmModal.classList.remove('show');
        confirmBackdrop.classList.remove('show');
        setTimeout(() => {
            confirmModal.style.display = 'none';
            confirmBackdrop.style.display = 'none';
            document.body.classList.remove('modal-open');
        }, 150);
    };

    // Click outside to close confirm modal
    window.addEventListener('click', function(e) {
        if (e.target === confirmModal) {
            closeConfirmModal();
        }
    });
});
</script>

<style>
/* Page Header */
.page-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    padding: 2.5rem 0;
    margin-bottom: 2rem;
    border-radius: 0 0 20px 20px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin-top: -20px;
}

/* Stat Cards */
.stat-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    transition: transform 0.2s;
}
.stat-card:hover { transform: translateY(-3px); }
.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
}
.bg-primary-light .stat-icon { background: var(--primary-color); box-shadow: 0 4px 10px rgba(255, 107, 53, 0.3); }
.bg-success-light .stat-icon { background: var(--success-color); box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3); }
.bg-warning-light .stat-icon { background: var(--warning-color); box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3); }

.stat-info h3 { margin: 0; font-size: 1.8rem; font-weight: 700; color: var(--dark-color); }
.stat-info p { margin: 0; color: var(--gray-color); font-size: 0.9rem; }

/* Filter Buttons */
.btn-group {
    display: flex;
    gap: 0.5rem;
}

.btn-group .btn {
    padding: 0.5rem 1.2rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.875rem;
    border: none;
    transition: all 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-outline-primary {
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
}

.btn-outline-primary:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: transparent;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}


/* Table Styling */
.table-responsive {
    width: 100%;
    overflow-x: auto;
}

.table {
    width: 100%;
    margin-bottom: 0;
}

.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    background: #f8f9fa;
    color: #6c757d;
    white-space: nowrap;
}
.table tbody tr { transition: background 0.2s; }
.table tbody tr:hover { background: #f8f9fa; }

/* Avatar */
.avatar-circle {
    width: 40px;
    height: 40px;
    min-width: 40px;
    border-radius: 50%;
    background: var(--light-color);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    font-weight: bold;
    color: var(--primary-color);
}
.avatar-circle img { width: 100%; height: 100%; object-fit: cover; }

.avatar-large {
    width: 120px;
    height: 120px;
    min-width: 120px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    position: relative;
    border: 4px solid white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
    margin: 0 auto;
}
.avatar-large img { 
    width: 100%; 
    height: 100%; 
    object-fit: cover; 
    display: block;
}

/* Badges */
.badge-pill {
    padding: 0.4em 0.8em;
    border-radius: 50rem;
    font-size: 0.75rem;
    font-weight: 600;
}
.badge-purple { background: #6f42c1; color: white; }
.badge-info { background: #17a2b8; color: white; }
.badge-secondary { background: #6c757d; color: white; }

.status-indicator { font-weight: 500; font-size: 0.9rem; display: inline-flex; align-items: center; gap: 5px; }
.status-activo { color: var(--success-color); }
.status-suspendido { color: var(--warning-color); }
.status-eliminado { color: var(--danger-color); }

/* Action Buttons */
.action-buttons {
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.action-buttons form {
    display: inline-flex;
    margin: 0;
}

.btn-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    background: #f8f9fa;
    color: #6c757d;
    cursor: pointer;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    font-size: 0.9rem;
}

.btn-icon:hover { 
    transform: translateY(-3px); 
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-icon-primary { 
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}
.btn-icon-primary:hover { 
    background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.btn-icon-warning { 
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
}
.btn-icon-warning:hover { 
    background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
    box-shadow: 0 4px 15px rgba(245, 87, 108, 0.4);
}

.btn-icon-danger { 
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: white;
}
.btn-icon-danger:hover { 
    background: linear-gradient(135deg, #fee140 0%, #fa709a 100%);
    box-shadow: 0 4px 15px rgba(250, 112, 154, 0.4);
}

.btn-icon-success { 
    background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
    color: white;
}
.btn-icon-success:hover { 
    background: linear-gradient(135deg, #a8e063 0%, #56ab2f 100%);
    box-shadow: 0 4px 15px rgba(86, 171, 47, 0.4);
}

/* Utilities */
.me-1 { margin-right: 0.25rem; }
.me-3 { margin-right: 1rem; }
.text-end { text-align: right; }
.rounded-lg { border-radius: 12px !important; }
.border-0 { border: none !important; }
.shadow-sm { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; }
.bg-light { background-color: #f8f9fa!important; }
.text-muted { color: #6c757d!important; }
.font-weight-bold { font-weight: 700!important; }

/* Modal */
.modal-backdrop { background: rgba(0,0,0,0.5); }
.close-modal:hover { opacity: 0.8; }
.close-modal-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    opacity: 0.8;
    transition: opacity 0.2s;
    padding: 0;
    line-height: 1;
}
.close-modal-btn:hover { opacity: 1; }

.font-weight-medium { font-weight: 500; }
.opacity-75 { opacity: 0.75; }
.opacity-50 { opacity: 0.5; }

.info-group {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    height: 100%;
    transition: background 0.2s;
}
.info-group:hover { background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }

.modal-open { overflow: hidden; }
.modal-backdrop.show { opacity: 0.5; }

/* Modal Critical CSS */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1050;
    display: none;
    width: 100%;
    height: 100%;
    overflow: hidden;
    outline: 0;
}
.modal-dialog {
    position: relative;
    width: auto;
    margin: 0.5rem;
    pointer-events: none;
}
.modal.fade .modal-dialog {
    transition: transform .3s ease-out;
    transform: translate(0,-50px);
}
.modal.show .modal-dialog {
    transform: none;
}
.modal.show {
    display: block;
    overflow-x: hidden;
    overflow-y: auto;
}
.modal-dialog-centered {
    display: flex;
    align-items: center;
    min-height: calc(100% - 1rem);
}
.modal-content {
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    pointer-events: auto;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.2);
    border-radius: .3rem;
    outline: 0;
}
.modal-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1040;
    width: 100vw;
    height: 100vh;
    background-color: #000;
}
@media (min-width: 576px) {
    .modal-dialog {
        max-width: 500px;
        margin: 1.75rem auto;
    }
    .modal-dialog-centered {
        min-height: calc(100% - 3.5rem);
    }
}
@media (min-width: 992px) {
    .modal-lg {
        max-width: 800px;
    }
}

/* Enhanced Modal Styles */
.icon-box {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}
.x-small {
    font-size: 0.7rem;
    letter-spacing: 0.5px;
    font-weight: 600;
}
.bg-primary-light {
    background-color: rgba(255, 107, 53, 0.1);
}
.bg-warning-light {
    background-color: rgba(255, 193, 7, 0.1);
}

/* Modal Enhancements for User Detail */
#userModal .modal-content {
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
}

#userModal .modal-header {
    background: linear-gradient(135deg, var(--primary-color) 0%, #ff8e5d 100%);
    color: white;
    padding: 1.5rem 2rem;
    border-bottom: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 12px 12px 0 0;
}

#userModal .modal-header h3 {
    font-weight: 700;
    font-size: 1.5rem;
    letter-spacing: 0.5px;
    margin: 0;
}

#userModal .close-modal-btn {
    background: none;
    border: none;
    color: rgba(255,255,255,0.9);
    font-size: 1.5rem;
    cursor: pointer;
    transition: all 0.2s;
    padding: 0;
    line-height: 1;
}

#userModal .close-modal-btn:hover {
    color: white;
    transform: scale(1.1);
}

#userModal .modal-body {
    padding: 2rem;
}

#userModal .section-title {
    background: rgba(255, 106, 42, 0.1);
    padding: 10px;
    border-radius: 8px;
    font-size: 1.1rem;
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 1.5rem;
}

#userModal .section-title i {
    margin-right: 0.5rem;
}

#userModal .info-grid {
    background: white;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    margin-bottom: 2rem;
    font-size: 1rem;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

#userModal .info-grid div {
    margin-bottom: 0.8rem;
    display: flex;
    justify-content: space-between;
    border-bottom: 1px dashed #eee;
    padding-bottom: 0.8rem;
    line-height: 1.6;
}

#userModal .info-grid div:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

#userModal .avatar-large {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    background: var(--light-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    font-weight: bold;
    color: var(--primary-color);
    border: 4px solid white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

#userModal #modal-img {
    border: 4px solid white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    margin: 0 auto;
    display: block;
}

#userModal .modal-footer {
    padding: 1rem 2rem;
    border-top: 1px solid #eee;
    display: flex;
    justify-content: center;
}

/* Confirm Modal Styles */
#confirmModal {
    z-index: 1060;
    display: none;
    align-items: center;
    justify-content: center;
}

#confirmModal.show {
    display: flex;
}

#confirmModal .modal-dialog {
    margin: 0;
    max-width: 450px;
    width: 90%;
}

#confirmModal .modal-dialog-centered {
    display: flex;
    align-items: center;
    min-height: calc(100% - 1rem);
    justify-content: center;
    padding: 1rem;
}

.confirm-modal-content {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    border: none;
    max-width: 450px;
    margin: 0 auto;
}

.confirm-modal-body {
    padding: 2.5rem;
    text-align: center;
    background: white;
}

.confirm-icon-wrapper {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2.5rem;
    color: white;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.confirm-icon-wrapper.warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.confirm-icon-wrapper.success {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
}

.confirm-icon-wrapper.danger {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.confirm-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--dark-color);
    margin-bottom: 1rem;
    letter-spacing: 0.3px;
}

.confirm-message {
    font-size: 1rem;
    color: var(--gray-color);
    line-height: 1.6;
    margin-bottom: 2rem;
}

.confirm-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.confirm-buttons .btn {
    padding: 0.75rem 2rem;
    border-radius: 10px;
    font-weight: 600;
    font-size: 0.95rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    min-width: 120px;
}

.btn-cancel {
    background: #f8f9fa;
    color: var(--gray-color);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.btn-cancel:hover {
    background: #e9ecef;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-confirm {
    color: white;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.btn-confirm.btn-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.btn-confirm.btn-warning:hover {
    background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(245, 87, 108, 0.4);
}

.btn-confirm.btn-success {
    background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
}

.btn-confirm.btn-success:hover {
    background: linear-gradient(135deg, #a8e063 0%, #56ab2f 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(86, 171, 47, 0.4);
}

.btn-confirm.btn-danger {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.btn-confirm.btn-danger:hover {
    background: linear-gradient(135deg, #fee140 0%, #fa709a 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(250, 112, 154, 0.4);
}

#confirmModalBackdrop {
    z-index: 1055;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
}

@media (max-width: 576px) {
    .confirm-modal-body {
        padding: 2rem 1.5rem;
    }
    
    .confirm-icon-wrapper {
        width: 70px;
        height: 70px;
        font-size: 2rem;
    }
    
    .confirm-title {
        font-size: 1.3rem;
    }
    
    .confirm-buttons {
        flex-direction: column;
    }
    
    .confirm-buttons .btn {
        width: 100%;
    }
}

</style>
