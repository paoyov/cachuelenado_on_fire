<?php
$title = 'Panel del Cliente';
?>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-home"></i> Panel del Cliente</h1>
        <p>Bienvenido, <?php echo htmlspecialchars($usuario['nombre_completo'] ?? ''); ?></p>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-search" style="font-size: 3rem; color: var(--primary-color);"></i>
                    <h3><?php echo (int)($busquedas_count ?? 0); ?></h3>
                    <p>Búsquedas realizadas</p>
                </div>
            </div>
        </div>
        
        <div class="col-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-clipboard-list" style="font-size: 3rem; color: var(--success-color);"></i>
                    <h3><?php echo (int)($trabajos_count ?? 0); ?></h3>
                    <p>Trabajos solicitados</p>
                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-comments" style="font-size: 3rem; color: var(--info-color);"></i>
                    <h3><?php echo count($mensajes_recientes ?? []); ?></h3>
                    <p>Conversaciones</p>
                </div>
            </div>
        </div>

        <div class="col-3">
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-bell" style="font-size: 3rem; color: var(--warning-color);"></i>
                    <h3><?php echo count($notificaciones ?? []); ?></h3>
                    <p>Notificaciones recientes</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Mensajes Recientes</h3>
                    <div>
                        <a href="<?php echo BASE_URL; ?>cliente/mensajes" class="btn btn-sm btn-outline">Ver Todos</a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($mensajes_recientes)): ?>
                        <?php foreach (array_slice($mensajes_recientes, 0, 6) as $conversacion): ?>
                        <div class="message-item d-flex align-items-center justify-content-between" data-maestro-id="<?php echo (int)$conversacion['maestro_id']; ?>">
                            <div class="d-flex align-items-center gap-2" style="flex:1;">
                                <div>
                                    <?php if (!empty($conversacion['foto_perfil'])): ?>
                                        <img src="<?php echo UPLOAD_URL . $conversacion['foto_perfil']; ?>" alt="" style="width: 44px; height: 44px; border-radius: 50%;">
                                    <?php else: ?>
                                        <i class="fas fa-user-circle" style="font-size: 2rem; color: var(--gray-color);"></i>
                                    <?php endif; ?>
                                </div>
                                <div style="flex: 1;">
                                    <strong><?php echo htmlspecialchars($conversacion['nombre_completo']); ?></strong>
                                    <p style="margin: 0; font-size: 0.9rem; color: var(--gray-color);">
                                        <?php echo htmlspecialchars(substr($conversacion['ultimo_mensaje'] ?? '', 0, 60)); ?>
                                    </p>
                                </div>
                                <?php if (!empty($conversacion['no_leidos']) && $conversacion['no_leidos'] > 0): ?>
                                <span class="badge unread-count" style="background: var(--primary-color); color: white; border-radius: 50%; width: 28px; height: 28px; display: flex; align-items: center; justify-content: center;">
                                    <?php echo (int)$conversacion['no_leidos']; ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <div class="ms-3">
                                <?php if (!empty($conversacion['no_leidos']) && $conversacion['no_leidos'] > 0): ?>
                                    <button class="btn btn-sm btn-outline mark-messages-read" data-maestro-id="<?php echo (int)$conversacion['maestro_id']; ?>">Marcar leídos</button>
                                <?php else: ?>
                                    <a href="<?php echo BASE_URL; ?>cliente/mensajes?maestro_id=<?php echo (int)$conversacion['maestro_id']; ?>" class="btn btn-sm btn-outline">Abrir</a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <hr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center" style="color: var(--gray-color);">No hay mensajes recientes</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Notificaciones</h3>
                    <a href="<?php echo BASE_URL; ?>" class="btn btn-sm btn-outline">Ver todas</a>
                </div>
                <div class="card-body">
                    <?php if (!empty($notificaciones)): ?>
                        <div class="mb-2 text-end">
                            <button id="mark-all-notifications" class="btn btn-sm btn-outline">Marcar todas como leídas</button>
                        </div>
                        <?php foreach ($notificaciones as $n): ?>
                        <div class="notification-item d-flex justify-content-between align-items-start" data-notification-id="<?php echo (int)$n['id']; ?>">
                            <div>
                                <strong><?php echo htmlspecialchars($n['titulo']); ?></strong>
                                <p style="margin:0; color: var(--gray-color); font-size:0.9rem;"><?php echo htmlspecialchars(substr($n['mensaje'],0,80)); ?></p>
                            </div>
                            <div class="text-end">
                                <small style="color: var(--gray-color); display:block;"><?php echo formatDateTime($n['fecha_creacion']); ?></small>
                                <button class="btn btn-sm btn-outline mark-notification-read mt-1" data-id="<?php echo (int)$n['id']; ?>">Marcar leída</button>
                            </div>
                        </div>
                        <hr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center" style="color: var(--gray-color);">No hay notificaciones</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Mis Trabajos Recientes</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($trabajos_activos)): ?>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Trabajo</th>
                                        <th>Maestro</th>
                                        <th>Estado</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($trabajos_activos as $trabajo): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($trabajo['titulo']); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if (!empty($trabajo['maestro_foto'])): ?>
                                                    <img src="<?php echo UPLOAD_URL . $trabajo['maestro_foto']; ?>" style="width: 30px; height: 30px; border-radius: 50%;">
                                                <?php else: ?>
                                                    <i class="fas fa-user-circle"></i>
                                                <?php endif; ?>
                                                <?php echo htmlspecialchars($trabajo['maestro_nombre']); ?>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo $trabajo['estado'] == 'completado' ? 'success' : ($trabajo['estado'] == 'pendiente' ? 'warning' : 'info'); ?>">
                                                <?php echo ucfirst($trabajo['estado']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo formatDate($trabajo['fecha_creacion']); ?></td>
                                        <td>
                                            <?php if ($trabajo['estado'] != 'completado' && $trabajo['estado'] != 'cancelado'): ?>
                                                <button class="btn btn-sm btn-success btn-finish-job" 
                                                        data-id="<?php echo $trabajo['id']; ?>"
                                                        data-maestro-id="<?php echo $trabajo['maestro_id']; ?>"
                                                        data-maestro-name="<?php echo htmlspecialchars($trabajo['maestro_nombre']); ?>"
                                                        data-maestro-foto="<?php echo !empty($trabajo['maestro_foto']) ? UPLOAD_URL . $trabajo['maestro_foto'] : ''; ?>">
                                                    <i class="fas fa-check"></i> Finalizar
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-center text-muted">No tienes trabajos registrados aún.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Acciones Rápidas</h3>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?php echo BASE_URL; ?>buscar" class="btn btn-primary">
                            <i class="fas fa-search"></i> Buscar Maestros
                        </a>
                        <a href="<?php echo BASE_URL; ?>cliente/mensajes" class="btn btn-primary">
                            <i class="fas fa-comments"></i> Mis Mensajes
                        </a>
                        <a href="<?php echo BASE_URL; ?>cliente/perfil" class="btn btn-primary">
                            <i class="fas fa-user-edit"></i> Editar Perfil
                        </a>
                        <a href="<?php echo BASE_URL; ?>cliente/historial" class="btn btn-primary">
                            <i class="fas fa-history"></i> Historial
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../../views/layout/testimonials.php'; ?>

<style>
.message-item, .notification-item {
    padding: 0.6rem 0;
}

.badge { font-size: 0.8rem; }
</style>

<?php include 'calificar_modal.php'; ?>

<script>
    (function(){
        const BASE_URL = '<?php echo BASE_URL; ?>';

        function postForm(url, data) {
            const form = new URLSearchParams();
            for (const k in data) form.append(k, data[k]);
            return fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: form.toString(),
                credentials: 'same-origin'
            }).then(r => r.json());
        }

        // Marcar mensajes leídos por conversación (maestro)
        document.addEventListener('click', function(e){
            if (e.target.matches('.mark-messages-read')) {
                e.preventDefault();
                const btn = e.target;
                const maestroId = btn.getAttribute('data-maestro-id');
                if (!maestroId) return;
                btn.disabled = true;
                btn.textContent = 'Procesando...';

                postForm(BASE_URL + 'api/mensajes/marcar', { maestro_id: maestroId })
                .then(res => {
                    if (res && res.success) {
                        const item = btn.closest('.message-item');
                        if (item) {
                            const badge = item.querySelector('.unread-count');
                            if (badge) badge.remove();
                            // reemplazar botón por enlace a conversación
                            const link = document.createElement('a');
                            link.className = 'btn btn-sm btn-outline';
                            link.href = BASE_URL + 'cliente/mensajes?maestro_id=' + maestroId;
                            link.textContent = 'Abrir';
                            btn.replaceWith(link);
                        }
                    } else {
                        btn.disabled = false;
                        btn.textContent = 'Marcar leídos';
                        alert((res && res.message) ? res.message : 'Error al marcar como leídos');
                    }
                }).catch(()=>{
                    btn.disabled = false;
                    btn.textContent = 'Marcar leídos';
                    alert('Error de red');
                });
            }
        });

        // Marcar una notificación como leída
        document.addEventListener('click', function(e){
            if (e.target.matches('.mark-notification-read')) {
                e.preventDefault();
                const btn = e.target;
                const id = btn.getAttribute('data-id');
                if (!id) return;
                btn.disabled = true;
                btn.textContent = 'Procesando...';

                postForm(BASE_URL + 'api/notificaciones/marcar', { id: id })
                .then(res => {
                    if (res && res.success) {
                        const item = btn.closest('.notification-item');
                        if (item) item.remove();
                    } else {
                        btn.disabled = false;
                        btn.textContent = 'Marcar leída';
                        alert((res && res.message) ? res.message : 'No se pudo marcar');
                    }
                }).catch(()=>{
                    btn.disabled = false;
                    btn.textContent = 'Marcar leída';
                    alert('Error de red');
                });
            }
        });

        // Marcar todas las notificaciones
        const markAllBtn = document.getElementById('mark-all-notifications');
        if (markAllBtn) {
            markAllBtn.addEventListener('click', function(){
                const buttons = Array.from(document.querySelectorAll('.mark-notification-read'));
                if (!buttons.length) return;
                markAllBtn.disabled = true;
                markAllBtn.textContent = 'Procesando...';

                // Ejecutar secuencialmente para no saturar
                (async function(){
                    for (const b of buttons) {
                        const id = b.getAttribute('data-id');
                        if (!id) continue;
                        try {
                            const res = await postForm(BASE_URL + 'api/notificaciones/marcar', { id: id });
                            if (res && res.success) {
                                const item = b.closest('.notification-item');
                                if (item) item.remove();
                            }
                        } catch (err) {
                            // continuar con siguientes
                        }
                    }
                    markAllBtn.disabled = false;
                    markAllBtn.textContent = 'Marcar todas como leídas';
                })();
            });
        }

        // Finalizar Trabajo
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-finish-job')) {
                const btn = e.target.closest('.btn-finish-job');
                const trabajoId = btn.getAttribute('data-id');
                const maestroId = btn.getAttribute('data-maestro-id');
                const maestroName = btn.getAttribute('data-maestro-name');
                const maestroFoto = btn.getAttribute('data-maestro-foto');

                if (confirm('¿Estás seguro de que deseas finalizar este trabajo?')) {
                    postForm(BASE_URL + 'cliente/completar_trabajo', { trabajo_id: trabajoId })
                    .then(res => {
                        if (res && res.success) {
                            // Abrir modal de calificación
                            document.getElementById('rating-maestro-id').value = maestroId;
                            document.getElementById('rating-trabajo-id').value = trabajoId;
                            document.getElementById('rating-master-name').textContent = maestroName;
                            if (maestroFoto) {
                                document.getElementById('rating-master-img').src = maestroFoto;
                            } else {
                                // Default image logic if needed
                            }
                            document.getElementById('ratingModal').style.display = 'block';
                        } else {
                            alert('Error al finalizar el trabajo');
                        }
                    }).catch(() => {
                        alert('Error de red');
                    });
                }
            }
        });

    })();
</script>
