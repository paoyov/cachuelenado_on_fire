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
                    <p class="auth-footer-text">
                        <span class="auth-footer-question">¿No tienes una cuenta?</span>
                        <a href="<?php echo BASE_URL; ?>register" class="auth-footer-link">
                            <i class="fas fa-user-plus"></i> Regístrate aquí
                        </a>
                    </p>
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
    padding: 2rem 0 4rem 0;
}

.auth-wrapper {
    max-width: 450px;
    margin: 0 auto 3rem auto;
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
    padding-top: 2rem;
    margin-top: 2rem;
    margin-bottom: 3rem;
    border-top: 2px solid #e9ecef;
    position: relative;
}

.auth-footer::before {
    content: '';
    position: absolute;
    top: -2px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
}

.auth-footer-text {
    margin: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.95rem;
}

.auth-footer-question {
    color: var(--gray-color);
    font-weight: 400;
}

.auth-footer-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    padding: 0.5rem 1.25rem;
    border-radius: 8px;
    background: linear-gradient(135deg, rgba(255, 107, 53, 0.1) 0%, rgba(255, 107, 53, 0.05) 100%);
    border: 2px solid rgba(255, 107, 53, 0.2);
    transition: all 0.3s ease;
}

.auth-footer-link i {
    font-size: 0.9rem;
}

.auth-footer-link:hover {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
    text-decoration: none;
}

.auth-footer-link:hover i {
    transform: translateX(3px);
}

@media (max-width: 576px) {
    .auth-card {
        padding: 1.5rem;
    }
}
</style>

