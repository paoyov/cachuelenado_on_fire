-- =============================================
-- Base de Datos: Cachueleando On Fire
-- Descripción: Plataforma web para conectar clientes con maestros de oficio
-- =============================================

CREATE DATABASE IF NOT EXISTS `cachueleando_on_fire` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `cachueleando_on_fire`;

-- =============================================
-- Tabla: usuarios
-- Descripción: Almacena información de todos los usuarios (clientes, maestros, administradores)
-- =============================================
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `tipo_usuario` ENUM('cliente', 'maestro', 'administrador') NOT NULL,
  `nombre_completo` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `telefono` VARCHAR(20) DEFAULT NULL,
  `dni` VARCHAR(20) DEFAULT NULL,
  `foto_perfil` VARCHAR(255) DEFAULT NULL,
  `chapa` VARCHAR(100) DEFAULT NULL,
  `estado` ENUM('activo', 'suspendido', 'eliminado') DEFAULT 'activo',
  `fecha_registro` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `ultimo_acceso` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_email` (`email`),
  INDEX `idx_tipo_usuario` (`tipo_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: maestros
-- Descripción: Información específica de los maestros de oficio
-- =============================================
CREATE TABLE IF NOT EXISTS `maestros` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` INT(11) NOT NULL,
  `anios_experiencia` INT(11) DEFAULT 0,
  `area_preferida` VARCHAR(255) DEFAULT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `disponibilidad` ENUM('disponible', 'ocupado', 'no_disponible') DEFAULT 'disponible',
  `estado_perfil` ENUM('pendiente', 'validado', 'rechazado') DEFAULT 'pendiente',
  `motivo_rechazo` TEXT DEFAULT NULL,
  `calificacion_promedio` DECIMAL(3,2) DEFAULT 0.00,
  `total_calificaciones` INT(11) DEFAULT 0,
  `total_trabajos` INT(11) DEFAULT 0,
  `total_vistas` INT(11) DEFAULT 0,
  `notificaciones_activas` TINYINT(1) DEFAULT 1,
  `fecha_validacion` DATETIME DEFAULT NULL,
  `validado_por` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuario_id` (`usuario_id`),
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`validado_por`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL,
  INDEX `idx_estado_perfil` (`estado_perfil`),
  INDEX `idx_disponibilidad` (`disponibilidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: especialidades
-- Descripción: Catálogo de especialidades disponibles
-- =============================================
CREATE TABLE IF NOT EXISTS `especialidades` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL UNIQUE,
  `descripcion` TEXT DEFAULT NULL,
  `icono` VARCHAR(50) DEFAULT NULL,
  `activo` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: maestro_especialidades
-- Descripción: Relación muchos a muchos entre maestros y especialidades
-- =============================================
CREATE TABLE IF NOT EXISTS `maestro_especialidades` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `maestro_id` INT(11) NOT NULL,
  `especialidad_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `maestro_especialidad` (`maestro_id`, `especialidad_id`),
  FOREIGN KEY (`maestro_id`) REFERENCES `maestros`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`especialidad_id`) REFERENCES `especialidades`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: distritos
-- Descripción: Catálogo de distritos de Lima
-- =============================================
CREATE TABLE IF NOT EXISTS `distritos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(100) NOT NULL UNIQUE,
  `activo` TINYINT(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: maestro_distritos
-- Descripción: Distritos donde trabaja cada maestro
-- =============================================
CREATE TABLE IF NOT EXISTS `maestro_distritos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `maestro_id` INT(11) NOT NULL,
  `distrito_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `maestro_distrito` (`maestro_id`, `distrito_id`),
  FOREIGN KEY (`maestro_id`) REFERENCES `maestros`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`distrito_id`) REFERENCES `distritos`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: documentos_maestro
-- Descripción: Documentos subidos por los maestros para validación
-- =============================================
CREATE TABLE IF NOT EXISTS `documentos_maestro` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `maestro_id` INT(11) NOT NULL,
  `tipo_documento` ENUM('dni', 'certificado', 'foto_trabajo') NOT NULL,
  `nombre_archivo` VARCHAR(255) NOT NULL,
  `ruta_archivo` VARCHAR(500) NOT NULL,
  `fecha_subida` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`maestro_id`) REFERENCES `maestros`(`id`) ON DELETE CASCADE,
  INDEX `idx_maestro_tipo` (`maestro_id`, `tipo_documento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: portafolio
-- Descripción: Portafolio de trabajos del maestro (máximo 10 fotos)
-- =============================================
CREATE TABLE IF NOT EXISTS `portafolio` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `maestro_id` INT(11) NOT NULL,
  `titulo` VARCHAR(255) DEFAULT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `imagen` VARCHAR(500) NOT NULL,
  `orden` INT(11) DEFAULT 0,
  `fecha_subida` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`maestro_id`) REFERENCES `maestros`(`id`) ON DELETE CASCADE,
  INDEX `idx_maestro_orden` (`maestro_id`, `orden`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: trabajos
-- Descripción: Trabajos realizados por maestros para clientes
-- =============================================
CREATE TABLE IF NOT EXISTS `trabajos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` INT(11) NOT NULL,
  `maestro_id` INT(11) NOT NULL,
  `titulo` VARCHAR(255) NOT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `fecha_inicio` DATE DEFAULT NULL,
  `fecha_fin` DATE DEFAULT NULL,
  `estado` ENUM('pendiente', 'en_proceso', 'completado', 'cancelado') DEFAULT 'pendiente',
  `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cliente_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`maestro_id`) REFERENCES `maestros`(`id`) ON DELETE CASCADE,
  INDEX `idx_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: calificaciones
-- Descripción: Calificaciones y comentarios de clientes sobre maestros
-- =============================================
CREATE TABLE IF NOT EXISTS `calificaciones` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` INT(11) NOT NULL,
  `maestro_id` INT(11) NOT NULL,
  `trabajo_id` INT(11) DEFAULT NULL,
  `puntualidad` INT(1) NOT NULL CHECK (`puntualidad` BETWEEN 1 AND 5),
  `calidad` INT(1) NOT NULL CHECK (`calidad` BETWEEN 1 AND 5),
  `trato` INT(1) NOT NULL CHECK (`trato` BETWEEN 1 AND 5),
  `limpieza` INT(1) NOT NULL CHECK (`limpieza` BETWEEN 1 AND 5),
  `comentario` TEXT DEFAULT NULL,
  `fecha_calificacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cliente_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`maestro_id`) REFERENCES `maestros`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`trabajo_id`) REFERENCES `trabajos`(`id`) ON DELETE SET NULL,
  UNIQUE KEY `trabajo_calificacion` (`trabajo_id`),
  INDEX `idx_maestro` (`maestro_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: busquedas
-- Descripción: Registro de búsquedas realizadas por clientes
-- =============================================
CREATE TABLE IF NOT EXISTS `busquedas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` INT(11) DEFAULT NULL,
  `especialidad_id` INT(11) DEFAULT NULL,
  `distrito_id` INT(11) DEFAULT NULL,
  `calificacion_minima` DECIMAL(3,2) DEFAULT NULL,
  `disponibilidad` ENUM('disponible', 'ocupado', 'no_disponible') DEFAULT NULL,
  `fecha_busqueda` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`cliente_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`especialidad_id`) REFERENCES `especialidades`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`distrito_id`) REFERENCES `distritos`(`id`) ON DELETE SET NULL,
  INDEX `idx_fecha_busqueda` (`fecha_busqueda`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: notificaciones
-- Descripción: Notificaciones del sistema
-- =============================================
CREATE TABLE IF NOT EXISTS `notificaciones` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` INT(11) NOT NULL,
  `tipo` ENUM('validacion', 'trabajo', 'calificacion', 'sistema') NOT NULL,
  `titulo` VARCHAR(255) NOT NULL,
  `mensaje` TEXT NOT NULL,
  `leida` TINYINT(1) DEFAULT 0,
  `fecha_creacion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  INDEX `idx_usuario_leida` (`usuario_id`, `leida`),
  INDEX `idx_fecha_creacion` (`fecha_creacion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: reportes
-- Descripción: Reportes de problemas o quejas
-- =============================================
CREATE TABLE IF NOT EXISTS `reportes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `reportado_por` INT(11) NOT NULL,
  `reportado_a` INT(11) NOT NULL,
  `tipo` ENUM('usuario', 'trabajo', 'otro') NOT NULL,
  `motivo` TEXT NOT NULL,
  `estado` ENUM('pendiente', 'en_revision', 'resuelto', 'descartado') DEFAULT 'pendiente',
  `fecha_reporte` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`reportado_por`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`reportado_a`) REFERENCES `usuarios`(`id`) ON DELETE CASCADE,
  INDEX `idx_estado` (`estado`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tabla: historial_actividad
-- Descripción: Historial de actividades del sistema
-- =============================================
CREATE TABLE IF NOT EXISTS `historial_actividad` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` INT(11) DEFAULT NULL,
  `tipo_accion` VARCHAR(100) NOT NULL,
  `descripcion` TEXT DEFAULT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `fecha_accion` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`usuario_id`) REFERENCES `usuarios`(`id`) ON DELETE SET NULL,
  INDEX `idx_fecha_accion` (`fecha_accion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- INSERTAR DATOS INICIALES
-- =============================================

-- Insertar especialidades
INSERT INTO `especialidades` (`nombre`, `descripcion`, `icono`) VALUES
('Gasfitería', 'Instalación y reparación de sistemas de agua y desagüe', 'fa-faucet'),
('Carpintería', 'Trabajos en madera, muebles y estructuras', 'fa-hammer'),
('Cerrajería', 'Instalación y reparación de cerraduras y candados', 'fa-key'),
('Albañilería', 'Construcción y reparación de muros y estructuras', 'fa-trowel'),
('Enchapado de Pisos', 'Instalación de pisos y revestimientos', 'fa-square'),
('Pintura', 'Pintado de interiores y exteriores', 'fa-paint-brush'),
('Electricidad', 'Instalaciones y reparaciones eléctricas', 'fa-bolt'),
('Techado', 'Reparación e instalación de techos', 'fa-home'),
('Jardinería', 'Mantenimiento de jardines y áreas verdes', 'fa-leaf'),
('Limpieza', 'Servicios de limpieza profunda', 'fa-broom');

-- Insertar distritos de Lima
INSERT INTO `distritos` (`nombre`) VALUES
('Lima Cercado'), ('San Isidro'), ('Miraflores'), ('Surco'), ('La Molina'),
('San Borja'), ('Pueblo Libre'), ('Magdalena'), ('Jesús María'), ('Breña'),
('Rímac'), ('San Martín de Porres'), ('Los Olivos'), ('Independencia'), ('Comas'),
('Carabayllo'), ('Puente Piedra'), ('Villa El Salvador'), ('San Juan de Miraflores'), ('Villa María del Triunfo'),
('Chorrillos'), ('Barranco'), ('Surquillo'), ('San Miguel'), ('Callao');

-- Crear usuario administrador por defecto
-- Password: admin123 (hash bcrypt)
INSERT INTO `usuarios` (`tipo_usuario`, `nombre_completo`, `email`, `password`, `telefono`, `estado`) VALUES
('administrador', 'Administrador del Sistema', 'admin@cachueleando.com', '$2y$10$2fVt87B5ugDB/ZcotMCTkOTbxyN4RWYT.UYXdtcK/h4qZTzvkE7bG', '999999999', 'activo');

-- =============================================
-- TRIGGERS
-- =============================================

-- Trigger para actualizar calificación promedio del maestro
DELIMITER //
CREATE TRIGGER `actualizar_calificacion_maestro` 
AFTER INSERT ON `calificaciones`
FOR EACH ROW
BEGIN
    UPDATE `maestros` 
    SET 
        `calificacion_promedio` = (
            SELECT AVG((`puntualidad` + `calidad` + `trato` + `limpieza`) / 4)
            FROM `calificaciones`
            WHERE `maestro_id` = NEW.`maestro_id`
        ),
        `total_calificaciones` = (
            SELECT COUNT(*)
            FROM `calificaciones`
            WHERE `maestro_id` = NEW.`maestro_id`
        )
    WHERE `id` = NEW.`maestro_id`;
END//
DELIMITER ;

-- Trigger para actualizar total de trabajos del maestro
DELIMITER //
CREATE TRIGGER `actualizar_total_trabajos` 
AFTER UPDATE ON `trabajos`
FOR EACH ROW
BEGIN
    IF NEW.`estado` = 'completado' AND OLD.`estado` != 'completado' THEN
        UPDATE `maestros` 
        SET `total_trabajos` = `total_trabajos` + 1
        WHERE `id` = NEW.`maestro_id`;
    END IF;
END//
DELIMITER ;

-- Trigger para limitar portafolio a 10 imágenes
DELIMITER //
CREATE TRIGGER `limitar_portafolio` 
BEFORE INSERT ON `portafolio`
FOR EACH ROW
BEGIN
    DECLARE total_fotos INT;
    SELECT COUNT(*) INTO total_fotos 
    FROM `portafolio` 
    WHERE `maestro_id` = NEW.`maestro_id`;
    
    IF total_fotos >= 10 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El portafolio no puede tener más de 10 imágenes';
    END IF;
END//
DELIMITER ;

