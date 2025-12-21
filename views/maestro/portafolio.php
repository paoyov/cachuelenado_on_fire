<?php
$title = 'Portafolio';
?>

<div class="container">
    <h1>Portafolio</h1>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form method="POST" action="<?php echo BASE_URL; ?>maestro/portafolio" enctype="multipart/form-data">
        <div class="form-group">
            <label>Título</label>
            <input type="text" name="titulo" class="form-control">
        </div>
        <div class="form-group">
            <label>Descripción</label>
            <input type="text" name="descripcion" class="form-control">
        </div>
        <div class="form-group">
            <label>Imagen</label>
            <input type="file" name="imagen" accept="image/*" required>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Subir Imagen</button>
        </div>
    </form>

    <hr>
    <h2>Imágenes</h2>
    <?php if (!empty($portafolio)): ?>
        <div class="portfolio-grid">
            <?php foreach ($portafolio as $item): ?>
                <div class="portfolio-item">
                    <img src="<?php echo UPLOAD_URL . $item['imagen']; ?>" alt="" style="max-width:200px;">
                    <p><?php echo htmlspecialchars($item['titulo']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No hay imágenes en el portafolio.</p>
    <?php endif; ?>
</div>

<?php if (isset($pago_expirado) && $pago_expirado): ?>
    <?php include 'modal_pago_expirado.php'; ?>
<?php endif; ?>

<style>
.portfolio-grid { display:flex; gap:1rem; flex-wrap:wrap; }
.portfolio-item { text-align:center; }
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
