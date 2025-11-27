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
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </div>
    </form>
</div>

<style>
.form-check-group { display:grid; grid-template-columns: repeat(auto-fit,minmax(200px,1fr)); gap:0.5rem; }
</style>
