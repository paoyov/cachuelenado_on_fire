<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php require_once __DIR__ . '/../layout/sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Mi Perfil</h1>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Información del Administrador</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="fw-bold">Nombre Completo:</label>
                                <p><?= htmlspecialchars($data['usuario']['nombre_completo']) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Email:</label>
                                <p><?= htmlspecialchars($data['usuario']['email']) ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Teléfono:</label>
                                <p><?= htmlspecialchars($data['usuario']['telefono'] ?? 'No registrado') ?></p>
                            </div>
                            <div class="mb-3">
                                <label class="fw-bold">Estado:</label>
                                <span class="badge bg-success"><?= ucfirst($data['usuario']['estado']) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">Cambiar Contraseña</h5>
                        </div>
                        <div class="card-body">
                            <form action="<?= url('admin/actualizarPassword') ?>" method="POST">
                                <div class="mb-3">
                                    <label for="password_actual" class="form-label">Contraseña Actual</label>
                                    <div class="password-wrapper">
                                        <input type="password" class="form-control" id="password_actual" name="password_actual" required>
                                        <button type="button" class="password-toggle-btn" onclick="togglePassword('password_actual')">
                                            <i class="fas fa-eye" id="password_actual-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="password_nueva" class="form-label">Nueva Contraseña</label>
                                    <div class="password-wrapper">
                                        <input type="password" class="form-control" id="password_nueva" name="password_nueva" required minlength="6">
                                        <button type="button" class="password-toggle-btn" onclick="togglePassword('password_nueva')">
                                            <i class="fas fa-eye" id="password_nueva-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmar" class="form-label">Confirmar Nueva Contraseña</label>
                                    <div class="password-wrapper">
                                        <input type="password" class="form-control" id="password_confirmar" name="password_confirmar" required minlength="6">
                                        <button type="button" class="password-toggle-btn" onclick="togglePassword('password_confirmar')">
                                            <i class="fas fa-eye" id="password_confirmar-icon"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-warning">Actualizar Contraseña</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
