<?php
$title = 'Mi Perfil - Administrador';
?>

<div class="page-header">
    <div class="container">
        <h1><i class="fas fa-user-shield"></i> Mi Perfil</h1>
        <p>Actualiza tus datos personales</p>
    </div>
</div>

<div class="container mt-4">
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <?php if (!empty($usuario['foto_perfil'])): ?>
                        <img src="<?php echo UPLOAD_URL . $usuario['foto_perfil']; ?>" alt="<?php echo htmlspecialchars($usuario['nombre_completo']); ?>" style="width:160px;height:160px;border-radius:50%;object-fit:cover;margin-bottom:1rem;">
                    <?php else: ?>
                        <i class="fas fa-user-circle" style="font-size:8rem;color:var(--gray-color);"></i>
                    <?php endif; ?>

                    <h3 style="margin-top:0.5rem;"><?php echo htmlspecialchars($usuario['nombre_completo']); ?></h3>
                    <p style="color:var(--gray-color);margin-bottom:0.5rem;"><?php echo htmlspecialchars($usuario['email']); ?></p>
                    <p style="margin-bottom:1rem;">
                        <span class="badge badge-success">Administrador</span>
                    </p>

                    <a href="<?php echo BASE_URL; ?>admin/dashboard" class="btn btn-outline btn-block">Volver al Panel</a>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Editar Información</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="<?php echo BASE_URL; ?>admin/perfil" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="nombre_completo" class="form-label">Nombre completo</label>
                            <input type="text" name="nombre_completo" id="nombre_completo" class="form-control" value="<?php echo htmlspecialchars($usuario['nombre_completo']); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" name="telefono" id="telefono" class="form-control" value="<?php echo htmlspecialchars($usuario['telefono'] ?? ''); ?>">
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" class="form-control" value="<?php echo htmlspecialchars($usuario['email']); ?>" disabled>
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Nueva contraseña <small style="color:var(--gray-color);">(dejar en blanco para mantener la actual)</small></label>
                            <div class="password-wrapper">
                                <input type="password" name="password" id="password" class="form-control">
                                <button type="button" class="password-toggle-btn" onclick="togglePassword('password')">
                                    <i class="fas fa-eye" id="password-icon"></i>
                                </button>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="foto_perfil" class="form-label">Foto de perfil</label>
                            <input type="file" name="foto_perfil" id="foto_perfil" class="form-control" accept="image/*">
                        </div>

                        <div class="form-group mt-4" style="display: flex; justify-content: center;">
                            <button type="submit" class="btn btn-primary px-5">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card { 
        box-shadow: var(--shadow-lg); 
    }
    .card-title { 
        color: var(--primary-color); 
    }
    .password-wrapper {
        position: relative;
    }
    .password-wrapper input {
        padding-right: 40px;
    }
    .password-toggle-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: var(--gray-color);
        cursor: pointer;
        padding: 5px;
        font-size: 1rem;
    }
    .password-toggle-btn:hover {
        color: var(--primary-color);
    }
</style>
