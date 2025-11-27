<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página no encontrada</title>
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
</head>
<body>
    <div class="error-page">
        <div class="container">
            <div class="error-content">
                <h1>404</h1>
                <h2>Página no encontrada</h2>
                <p>Lo sentimos, la página que buscas no existe.</p>
                <a href="<?php echo BASE_URL; ?>" class="btn btn-primary">Volver al Inicio</a>
            </div>
        </div>
    </div>
    
    <style>
    .error-page {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    }
    
    .error-content {
        text-align: center;
        color: var(--white);
    }
    
    .error-content h1 {
        font-size: 8rem;
        font-weight: 700;
        margin-bottom: 1rem;
        line-height: 1;
    }
    
    .error-content h2 {
        font-size: 2rem;
        margin-bottom: 1rem;
    }
    
    .error-content p {
        font-size: 1.25rem;
        margin-bottom: 2rem;
        opacity: 0.9;
    }
    </style>
</body>
</html>

