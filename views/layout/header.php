<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?>Cachueleando On Fire</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Principal -->
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
    
    <?php if (isset($extra_css)): ?>
        <?php foreach ($extra_css as $css): ?>
            <link rel="stylesheet" href="<?php echo asset($css); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="container">
            <div class="navbar-content">
                <a href="<?php echo BASE_URL; ?>" class="navbar-brand">
                    <i class="fas fa-fire"></i>
                    <span>Cachueleando On Fire</span>
                </a>
                
                <button class="navbar-toggle" id="navbarToggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                
                <ul class="navbar-menu" id="navbarMenu">
                    <li><a href="<?php echo BASE_URL; ?>">Inicio</a></li>
                    <li><a href="<?php echo BASE_URL; ?>buscar">Buscar Maestros</a></li>

                    <?php if (isLoggedIn()): ?>
                        <?php if (isCliente()): ?>
                            <li><a href="<?php echo BASE_URL; ?>cliente/calificaciones">Calificaciones</a></li>
                        <?php elseif (isMaestro()): ?>
                            <li><a href="<?php echo BASE_URL; ?>maestro/calificaciones">Calificaciones</a></li>
                        <?php endif; ?>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_URL; ?>#calificaciones">Calificaciones</a></li>
                    <?php endif; ?>
                    
                    <?php if (isLoggedIn()): ?>
                        <?php if (isCliente()): ?>
                            <li><a href="<?php echo BASE_URL; ?>cliente/dashboard">Mi Panel</a></li>
                        <?php elseif (isMaestro()): ?>
                            <li><a href="<?php echo BASE_URL; ?>maestro/dashboard">Mi Panel</a></li>
                        <?php elseif (isAdmin()): ?>
                            <li><a href="<?php echo BASE_URL; ?>admin/dashboard">Panel Admin</a></li>
                        <?php endif; ?>
                        
                        <li class="navbar-dropdown">
                            <a href="#" class="navbar-user">
                                <?php if (!empty($_SESSION['foto_perfil'])): ?>
                                    <img src="<?php echo UPLOAD_URL . $_SESSION['foto_perfil']; ?>" alt="Perfil">
                                <?php else: ?>
                                    <i class="fas fa-user-circle"></i>
                                <?php endif; ?>
                                <span><?php echo htmlspecialchars($_SESSION['nombre_completo']); ?></span>
                                <i class="fas fa-chevron-down"></i>
                            </a>
                            <ul class="dropdown-menu">
                                <?php if (isCliente()): ?>
                                    <li><a href="<?php echo BASE_URL; ?>cliente/perfil"><i class="fas fa-user"></i> Mi Perfil</a></li>

                                <?php elseif (isMaestro()): ?>
                                    <li><a href="<?php echo BASE_URL; ?>maestro/perfil-editar"><i class="fas fa-user-edit"></i> Editar Perfil</a></li>
                                    <li><a href="<?php echo BASE_URL; ?>maestro/portafolio"><i class="fas fa-images"></i> Portafolio</a></li>
                                    <li><a href="<?php echo BASE_URL; ?>maestro/configuracion"><i class="fas fa-cog"></i> Configuración</a></li>
                                <?php elseif (isAdmin()): ?>
                                    <li><a href="<?php echo BASE_URL; ?>admin/perfil"><i class="fas fa-user-shield"></i> Mi Perfil</a></li>
                                    <li><a href="<?php echo BASE_URL; ?>admin/estadisticas"><i class="fas fa-chart-bar"></i> Estadísticas</a></li>
                                    <li><a href="<?php echo BASE_URL; ?>admin/usuarios"><i class="fas fa-users"></i> Usuarios</a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a href="<?php echo BASE_URL; ?>logout"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_URL; ?>login" class="btn btn-outline">Iniciar Sesión</a></li>
                        <li><a href="<?php echo BASE_URL; ?>register" class="btn btn-primary">Registrarse</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Alertas -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

