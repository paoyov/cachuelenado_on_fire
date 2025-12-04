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
                            <th class="border-0 px-4 py-3">Usuario</th>
                            <th class="border-0 py-3">Rol</th>
                            <th class="border-0 py-3">Estado</th>
                            <th class="border-0 py-3">Registro</th>
                            <th class="border-0 px-4 py-3 text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (isset($usuarios) && count($usuarios) > 0): ?>
                            <?php foreach ($usuarios as $u): ?>
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-3">
                                                <?php if (!empty($u['foto_perfil'])): ?>
                                                    <img src="<?php echo UPLOAD_URL . $u['foto_perfil']; ?>" alt="Avatar">
                                                <?php else: ?>
                                                    <span><?php echo strtoupper(substr($u['nombre_completo'], 0, 1)); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-dark font-weight-bold"><?php echo htmlspecialchars($u['nombre_completo']); ?></h6>
                                                <small class="text-muted"><?php echo htmlspecialchars($u['email']); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">
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
                                    <td class="py-3">
                                        <span class="status-indicator status-<?php echo $u['estado']; ?>">
                                            <i class="fas fa-circle"></i> <?php echo ucfirst($u['estado']); ?>
                                        </span>
                                    </td>
                                    <td class="py-3 text-muted">
                                        <i class="far fa-calendar-alt me-1"></i> <?php echo date('d M Y', strtotime($u['fecha_registro'])); ?>
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <div class="action-buttons">
                                            <button type="button" 
                                                    class="btn-icon btn-icon-primary view-user-btn" 
                                                    title="Ver Detalle"
                                                    data-user='<?php echo htmlspecialchars(json_encode($u), ENT_QUOTES, 'UTF-8'); ?>'>
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <?php if ($u['estado'] !== 'suspendido'): ?>
                                            <form method="post" action="<?php echo BASE_URL; ?>admin/usuarios" class="d-inline" onsubmit="return confirm('¿Suspender usuario?');">
                                                <input type="hidden" name="usuario_id" value="<?php echo $u['id']; ?>">
                                                <button type="submit" name="accion" value="suspender" class="btn-icon btn-icon-warning" title="Suspender">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                            <?php endif; ?>

                                            <form method="post" action="<?php echo BASE_URL; ?>admin/usuarios" class="d-inline" onsubmit="return confirm('¿Eliminar permanentemente?');">
                                                <input type="hidden" name="usuario_id" value="<?php echo $u['id']; ?>">
                                                <button type="submit" name="accion" value="eliminar" class="btn-icon btn-icon-danger" title="Eliminar">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
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
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">Mostrando <?php echo count($usuarios); ?> registros</small>
            </div>
    </div>
</div>

<!-- Modal Detalle Usuario -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" style="max-width: 700px;">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 24px; overflow: hidden; background: #fff;">
      
      <!-- Header with Gradient -->
      <div class="modal-header border-0 p-0" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%); height: 100px; position: relative;">
        <button type="button" class="close-modal-btn text-white position-absolute top-0 end-0 m-3" onclick="closeUserModal()" style="z-index: 10;">
            <i class="fas fa-times fa-lg"></i>
        </button>
      </div>

      <div class="modal-body px-4 pb-4 pt-0">
        <div class="row">
            <!-- Profile Info (Overlapping Header) -->
            <div class="col-12 text-center" style="margin-top: -50px;">
                <div class="avatar-large mx-auto mb-3 shadow-lg bg-white">
                    <img id="modal-img" src="" alt="Avatar" style="display:none;">
                    <span id="modal-initials"></span>
                </div>
                <h4 id="modal-name" class="font-weight-bold mb-1 text-dark"></h4>
                <p id="modal-email" class="text-muted mb-3"></p>
                
                <div class="d-flex justify-content-center gap-2 mb-4">
                    <span id="modal-role" class="badge-pill shadow-sm"></span>
                    <span id="modal-status" class="status-indicator px-3 py-1 bg-light rounded-pill border"></span>
                </div>
            </div>

            <!-- ID Badge -->
            <div class="col-12 text-center mb-4">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill bg-light border">
                    <span class="text-uppercase text-muted font-weight-bold" style="font-size: 0.7rem;">ID</span>
                    <span id="modal-id" class="font-family-monospace text-dark font-weight-bold">#</span>
                </div>
            </div>
            
            <!-- Contact Info Grid -->
            <div class="col-12">
                <h6 class="text-primary font-weight-bold mb-3 ps-2 border-start border-4 border-primary">Información Personal</h6>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-lg bg-light h-100 d-flex align-items-center gap-3">
                            <div class="icon-box bg-white text-primary rounded-circle shadow-sm">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <label class="d-block text-muted x-small text-uppercase mb-0">Teléfono</label>
                                <span id="modal-phone" class="font-weight-medium text-dark"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-lg bg-light h-100 d-flex align-items-center gap-3">
                            <div class="icon-box bg-white text-primary rounded-circle shadow-sm">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <div>
                                <label class="d-block text-muted x-small text-uppercase mb-0">DNI / Doc</label>
                                <span id="modal-dni" class="font-weight-medium text-dark"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-lg bg-light h-100 d-flex align-items-center gap-3">
                            <div class="icon-box bg-white text-primary rounded-circle shadow-sm">
                                <i class="far fa-calendar-alt"></i>
                            </div>
                            <div>
                                <label class="d-block text-muted x-small text-uppercase mb-0">Registro</label>
                                <span id="modal-date" class="font-weight-medium text-dark"></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-lg bg-light h-100 d-flex align-items-center gap-3">
                            <div class="icon-box bg-white text-primary rounded-circle shadow-sm">
                                <i class="far fa-clock"></i>
                            </div>
                            <div>
                                <label class="d-block text-muted x-small text-uppercase mb-0">Último Acceso</label>
                                <span id="modal-last-access" class="font-weight-medium text-dark"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="maestro-fields" style="display:none;" class="mt-4">
                    <div class="alert alert-warning bg-warning-light border-0 rounded-lg d-flex align-items-center justify-content-between p-3">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-white p-2 rounded-circle text-warning shadow-sm">
                                <i class="fas fa-hard-hat fa-lg"></i>
                            </div>
                            <div>
                                <strong class="text-dark d-block">Perfil Profesional</strong>
                                <small class="text-muted">Usuario verificado como maestro</small>
                            </div>
                        </div>
                        <a href="#" id="modal-maestro-link" class="btn btn-sm btn-warning text-dark font-weight-bold shadow-sm px-3 rounded-pill">Ver Perfil</a>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal-backdrop fade" id="modalBackdrop" style="display:none;"></div>

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

            // Maestro specific
            const maestroFields = document.getElementById('maestro-fields');
            if (user.tipo_usuario === 'maestro') {
                maestroFields.style.display = 'block';
                document.getElementById('modal-maestro-link').href = BASE_URL + 'maestro/perfil?id=' + user.id; 
            } else {
                maestroFields.style.display = 'none';
            }

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

/* Table Styling */
.table thead th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    background: #f8f9fa;
    color: #6c757d;
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
.btn-icon {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    background: transparent;
    color: var(--gray-color);
}
.btn-icon:hover { background: var(--light-color); transform: translateY(-2px); }
.btn-icon-primary:hover { color: var(--primary-color); background: rgba(255, 107, 53, 0.1); }
.btn-icon-warning:hover { color: var(--warning-color); background: rgba(255, 193, 7, 0.1); }
.btn-icon-danger:hover { color: var(--danger-color); background: rgba(220, 53, 69, 0.1); }

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
</style>
