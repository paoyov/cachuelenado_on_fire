<?php
$title = 'Disponibilidad';
?>

<div class="container">
    <h1>Disponibilidad</h1>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form method="POST" action="<?php echo BASE_URL; ?>maestro/disponibilidad">
        <div class="form-group">
            <label>Estado</label>
            <select name="disponibilidad" class="form-control form-select">
                <option value="disponible" <?php echo (isset($maestro['disponibilidad']) && $maestro['disponibilidad'] === 'disponible') ? 'selected' : ''; ?>>Disponible</option>
                <option value="ocupado" <?php echo (isset($maestro['disponibilidad']) && $maestro['disponibilidad'] === 'ocupado') ? 'selected' : ''; ?>>Ocupado</option>
                <option value="no_disponible" <?php echo (isset($maestro['disponibilidad']) && $maestro['disponibilidad'] === 'no_disponible') ? 'selected' : ''; ?>>No disponible</option>
            </select>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</div>
