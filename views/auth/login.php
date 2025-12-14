<?php
$title = 'Iniciar Sesión';
?>

<div class="auth-container">
    <div class="container">
        <div class="auth-wrapper">
            <div class="auth-card">
                <div class="auth-header">
                    <h1><i class="fas fa-fire"></i> Iniciar Sesión</h1>
                    <p>Accede a tu cuenta de Cachueleando On Fire</p>
                </div>
                
                <form method="POST" action="<?php echo BASE_URL; ?>auth/login" class="auth-form">
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i> Email
                        </label>
                        <input type="email" id="email" name="email" class="form-control" required autofocus>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i> Contraseña
                        </label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" class="form-control" required>
                            <button type="button" class="password-toggle-btn" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="password-icon"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                        </button>
                    </div>
                </form>
                
                <div class="auth-footer">
                    <p>¿No tienes una cuenta? <a href="<?php echo BASE_URL; ?>register">Regístrate aquí</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.auth-container {
    min-height: calc(100vh - 200px);
    display: flex;
    align-items: center;
    padding: 2rem 0;
}

.auth-wrapper {
    max-width: 450px;
    margin: 0 auto;
}

.auth-card {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow-lg);
    padding: 2.5rem;
}

.auth-header {
    text-align: center;
    margin-bottom: 2rem;
}

.auth-header h1 {
    font-size: 2rem;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.auth-header p {
    color: var(--gray-color);
}

.auth-form {
    margin-bottom: 1.5rem;
}

.auth-footer {
    text-align: center;
    padding-top: 1.5rem;
    border-top: 1px solid var(--light-color);
}

.auth-footer a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
}

.auth-footer a:hover {
    text-decoration: underline;
}

@media (max-width: 576px) {
    .auth-card {
        padding: 1.5rem;
    }
}
</style>

