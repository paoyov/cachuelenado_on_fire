# Cachueleando On Fire

Plataforma web para conectar clientes con maestros de oficio en Lima, Perú.

## Características

- ✅ Sistema de registro para Clientes, Maestros y Administradores
- ✅ Búsqueda avanzada de maestros con filtros (especialidad, distrito, calificación, disponibilidad)
- ✅ Sistema de mensajería entre clientes y maestros
- ✅ Portafolio de trabajos (máximo 10 imágenes por maestro)
- ✅ Sistema de calificaciones (puntualidad, calidad, trato, limpieza)
- ✅ Panel de administración para validar perfiles de maestros
- ✅ Estadísticas y reportes mensuales
- ✅ Notificaciones del sistema
- ✅ Diseño responsive y moderno
- ✅ Arquitectura MVC con PHP nativo

## Requisitos

- PHP 7.4 o superior
- MySQL 5.7 o superior (o MariaDB)
- Apache con mod_rewrite habilitado
- Extensiones PHP: PDO, PDO_MySQL, GD (para imágenes)

## Instalación

### 1. Clonar o descargar el proyecto

Coloca el proyecto en tu directorio web (por ejemplo: `C:\xampp\htdocs\Cachueleando_On_Fire`)

### 2. Configurar la base de datos

1. Abre phpMyAdmin (http://localhost/phpmyadmin)
2. Importa el archivo `database/cachueleando_on_fire.sql`
3. O ejecuta el script SQL directamente en phpMyAdmin

### 3. Configurar la conexión a la base de datos

Edita el archivo `config/database.php` y ajusta las credenciales si es necesario:

```php
private $host = 'localhost';
private $db_name = 'cachueleando_on_fire';
private $username = 'root';
private $password = '';
```

### 4. Configurar las rutas base

Edita el archivo `config/config.php` y ajusta la constante `BASE_URL` según tu configuración:

```php
define('BASE_URL', 'http://localhost/Cachueleando_On_Fire/');
```

### 5. Crear directorios necesarios

Crea los siguientes directorios con permisos de escritura:

```
uploads/
uploads/perfiles/
uploads/documentos/
uploads/documentos/dni/
uploads/documentos/certificado/
uploads/documentos/foto_trabajo/
uploads/portafolio/
logs/
```

### 6. Configurar permisos (Linux/Mac)

```bash
chmod -R 755 uploads/
chmod -R 755 logs/
```

### 7. Acceder a la aplicación

Abre tu navegador y visita: `http://localhost/Cachueleando_On_Fire/`

## Credenciales por defecto

### Administrador
- **Email:** admin@cachueleando.com
- **Password:** admin123

## Estructura del Proyecto

```
Cachueleando_On_Fire/
├── assets/
│   ├── css/
│   │   └── style.css
│   └── js/
│       ├── main.js
│       └── register.js
├── config/
│   ├── config.php
│   └── database.php
├── controllers/
│   ├── AdminController.php
│   ├── ApiController.php
│   ├── AuthController.php
│   ├── BuscarController.php
│   ├── ClienteController.php
│   ├── HomeController.php
│   └── MaestroController.php
├── core/
│   ├── Controller.php
│   └── Router.php
├── database/
│   └── cachueleando_on_fire.sql
├── models/
│   ├── Busqueda.php
│   ├── Calificacion.php
│   ├── Distrito.php
│   ├── DocumentoMaestro.php
│   ├── Especialidad.php
│   ├── Maestro.php
│   ├── Mensaje.php
│   ├── Notificacion.php
│   ├── Portafolio.php
│   └── Usuario.php
├── uploads/
├── views/
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   ├── buscar/
│   │   └── index.php
│   ├── cliente/
│   ├── errors/
│   │   └── 404.php
│   ├── home/
│   │   └── index.php
│   ├── layout/
│   │   ├── footer.php
│   │   └── header.php
│   └── maestro/
│       ├── dashboard.php
│       └── perfil.php
├── .htaccess
├── index.php
└── README.md
```

## Funcionalidades por Usuario

### Cliente
- Buscar maestros con filtros avanzados
- Ver perfiles de maestros
- Enviar mensajes a maestros
- Calificar trabajos realizados
- Ver historial de trabajos

### Maestro
- Crear y editar perfil profesional
- Subir portafolio (máximo 10 imágenes)
- Actualizar disponibilidad en tiempo real
- Responder mensajes de clientes
- Ver calificaciones recibidas
- Ver estadísticas del perfil

### Administrador
- Validar o rechazar perfiles de maestros
- Ver estadísticas del sistema
- Gestionar usuarios (suspender/eliminar)
- Ver reportes de problemas
- Generar reportes mensuales

## Requerimientos No Funcionales

- ✅ Interfaces responsive (PC, tablet, smartphone)
- ✅ Tiempo de carga < 3 segundos
- ✅ Acciones completadas en < 5 segundos
- ✅ Disponibilidad 99% (24/7/365)
- ✅ Contraseñas encriptadas con bcrypt
- ✅ Comunicación HTTPS (configurar en producción)
- ✅ Soporte para 500 usuarios concurrentes
- ✅ Código documentado y siguiendo buenas prácticas

## Notas de Desarrollo

- El sistema usa arquitectura MVC (Model-View-Controller)
- Las contraseñas se almacenan con hash bcrypt
- Los archivos subidos se guardan en el directorio `uploads/`
- Las notificaciones se almacenan en la base de datos
- El sistema de mensajería es interno (no requiere servicios externos)

## Próximas Mejoras

- [ ] Integración con WhatsApp API para notificaciones
- [ ] Integración con servicio de email (SMTP)
- [ ] Sistema de pagos
- [ ] Aplicación móvil
- [ ] Chat en tiempo real con WebSockets
- [ ] Sistema de geolocalización

## Soporte

Para soporte técnico, contacta a: contacto@cachueleando.com

## Licencia

Este proyecto es de uso educativo y comercial.

---

**Desarrollado con ❤️ para conectar maestros de oficio con clientes en Lima**

# cachuelenado_on_fire
