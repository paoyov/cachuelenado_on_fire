<?php
$title = 'Editar Perfil';
?>

<div class="container">
    <h1>Editar Perfil</h1>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <?php if ($maestro['estado_perfil'] === 'rechazado'): ?>
    <div class="alert alert-warning" style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 1.25rem 1.5rem; margin-bottom: 2rem; border-radius: 8px;">
        <div style="display: flex; align-items: flex-start; gap: 1rem;">
            <i class="fas fa-exclamation-triangle" style="font-size: 1.5rem; color: #ffc107; margin-top: 0.2rem;"></i>
            <div style="flex: 1;">
                <strong style="color: #856404; display: block; margin-bottom: 0.5rem;">Tu perfil ha sido rechazado</strong>
                <?php if (!empty($maestro['motivo_rechazo'])): ?>
                <p style="color: #856404; margin: 0.5rem 0;">
                    <strong>Motivo del rechazo:</strong> <?php echo htmlspecialchars($maestro['motivo_rechazo']); ?>
                </p>
                <?php endif; ?>
                <p style="color: #856404; margin: 0.5rem 0 0 0;">
                    Por favor, corrige los problemas indicados y guarda los cambios. Tu perfil será enviado nuevamente para validación.
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo BASE_URL; ?>maestro/perfil-editar" enctype="multipart/form-data">
        <div class="form-group">
            <label>Nombre Completo</label>
            <input type="text" name="nombre_completo" class="form-control" value="<?php echo htmlspecialchars($maestro['nombre_completo'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="<?php echo htmlspecialchars($maestro['telefono'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Años de Experiencia</label>
            <input type="number" name="anios_experiencia" class="form-control" min="0" value="<?php echo htmlspecialchars($maestro['anios_experiencia'] ?? '0'); ?>">
        </div>

        <div class="form-group">
            <label>Área Preferida</label>
            <input type="text" name="area_preferida" class="form-control" value="<?php echo htmlspecialchars($maestro['area_preferida'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4"><?php echo htmlspecialchars($maestro['descripcion'] ?? ''); ?></textarea>
        </div>

        <?php if (!empty($especialidades)): ?>
        <div class="form-group">
            <label>Especialidades</label>
            <div class="form-check-group">
                <?php foreach ($especialidades as $esp): ?>
                    <?php $checked = in_array($esp['id'], array_column($maestro_especialidades ?? [], 'id')) ? 'checked' : ''; ?>
                    <label class="form-check"><input type="checkbox" name="especialidades[]" value="<?php echo $esp['id']; ?>" <?php echo $checked; ?>> <?php echo htmlspecialchars($esp['nombre']); ?></label>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($distritos)): ?>
        <div class="form-group">
            <label>Distritos</label>
            <div class="form-check-group">
                <?php foreach ($distritos as $dist): ?>
                    <?php $checked = in_array($dist['id'], array_column($maestro_distritos ?? [], 'id')) ? 'checked' : ''; ?>
                    <label class="form-check"><input type="checkbox" name="distritos[]" value="<?php echo $dist['id']; ?>" <?php echo $checked; ?>> <?php echo htmlspecialchars($dist['nombre']); ?></label>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="form-group">
            <label>Foto de Perfil</label>
            <input type="file" name="foto_perfil" accept="image/*">
            <?php if (!empty($maestro['foto_perfil'])): ?>
                <div style="margin-top:8px;"><img src="<?php echo UPLOAD_URL . $maestro['foto_perfil']; ?>" alt="" style="max-width:120px;border-radius:6px;"></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="password" class="form-label">Nueva Contraseña <small style="color:var(--gray-color);">(dejar en blanco para mantener la actual)</small></label>
            <div class="password-wrapper">
                <input type="password" name="password" id="password" class="form-control" minlength="6">
                <button type="button" class="password-toggle-btn" onclick="togglePassword('password')">
                    <i class="fas fa-eye" id="password-icon"></i>
                </button>
            </div>
            <small class="form-text">Mínimo 6 caracteres</small>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
    </form>
</div>

<?php if (isset($pago_expirado) && $pago_expirado): ?>
    <?php include 'modal_pago_expirado.php'; ?>
<?php endif; ?>

<style>
.form-check-group { display:grid; grid-template-columns: repeat(auto-fit,minmax(200px,1fr)); gap:0.5rem; }
</style>

<script>
<?php if (isset($pago_expirado) && $pago_expirado): ?>
document.addEventListener('DOMContentLoaded', function() {
    const modalExpirado = document.getElementById('modalPagoExpirado');
    if (modalExpirado) {
        document.body.style.overflow = 'hidden';
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                e.preventDefault();
                e.stopPropagation();
            }
        }, true);
    }
});
<?php endif; ?>
</script>
