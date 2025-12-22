<?php
/**
 * Controlador Maestro
 */

class MaestroController extends Controller {
    private $maestroModel;
    private $usuarioModel;

    public function __construct() {
        parent::__construct();
        $this->maestroModel = new Maestro($this->db);
        $this->usuarioModel = new Usuario($this->db);
    }

    /**
     * Verificar si el pago del maestro está expirado
     * @return array ['expirado' => bool, 'pago_activo' => array|null]
     */
    private function verificarPagoExpirado($maestro_id) {
        require_once 'models/PagoMaestro.php';
        $pagoModel = new PagoMaestro($this->db);
        $pago_activo = $pagoModel->getPagoActivo($maestro_id);
        
        $pago_expirado = false;
        if (!$pago_activo) {
            // Verificar si hay un pago pero está expirado
            $pago_reciente = $pagoModel->getPagoParaValidar($maestro_id);
            if ($pago_reciente && isset($pago_reciente['fecha_expiracion'])) {
                $fecha_expiracion = strtotime($pago_reciente['fecha_expiracion']);
                $pago_expirado = $fecha_expiracion < time();
            }
            // Si no hay pago activo ni pago reciente, NO es expirado, simplemente no tiene pago
            // Solo es expirado si había un pago activo que ya venció
        } elseif (isset($pago_activo['fecha_expiracion'])) {
            $fecha_expiracion = strtotime($pago_activo['fecha_expiracion']);
            $pago_expirado = $fecha_expiracion < time();
        }
        
        return [
            'expirado' => $pago_expirado,
            'pago_activo' => $pago_activo
        ];
    }

    public function dashboard() {
        $this->requireAuth(['maestro']);
        
        $maestro = $this->maestroModel->getByUsuarioId($_SESSION['usuario_id']);
        if (!$maestro) {
            redirect('register');
        }

        $calificacionModel = new Calificacion($this->db);
        $notificacionModel = new Notificacion($this->db);
        
        // Verificar estado de pago
        $pagoInfo = $this->verificarPagoExpirado($maestro['id']);
        $pago_activo = $pagoInfo['pago_activo'];
        $pago_expirado = $pagoInfo['expirado'];
        $mostrar_modal = isset($_SESSION['mostrar_modal_pago']) && $_SESSION['mostrar_modal_pago'];

        // Calcular tiempo restante del pago
        $fecha_expiracion = null;
        if ($pago_activo && isset($pago_activo['fecha_expiracion'])) {
            $fecha_expiracion = $pago_activo['fecha_expiracion'];
        }

        $data = [
            'maestro' => $maestro,
            'calificaciones_recientes' => $calificacionModel->getByMaestro($maestro['id'], 5),
            'notificaciones' => $notificacionModel->getByUsuario($_SESSION['usuario_id'], 5, true),
            'calificaciones_globales' => $calificacionModel->getRecent(6),
            'pago_activo' => $pago_activo,
            'mostrar_modal_pago' => $mostrar_modal,
            'pago_expirado' => $pago_expirado,
            'fecha_expiracion' => $fecha_expiracion
        ];

        $this->view('maestro/dashboard', $data);
    }

    public function verPerfil() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            redirect('home');
        }

        $maestro = $this->maestroModel->getById($id);
        if (!$maestro || $maestro['estado_perfil'] !== 'validado') {
            redirect('home');
        }

        // Incrementar vistas
        $this->maestroModel->incrementViews($id);

        $portafolioModel = new Portafolio($this->db);
        $calificacionModel = new Calificacion($this->db);
        $especialidadModel = new Especialidad($this->db);

        $data = [
            'maestro' => $maestro,
            'especialidades' => $this->maestroModel->getEspecialidades($id),
            'distritos' => $this->maestroModel->getDistritos($id),
            'portafolio' => $portafolioModel->getByMaestro($id),
            'calificaciones' => $calificacionModel->getByMaestro($id)
        ];

        $this->view('maestro/perfil', $data);
    }

    public function editarPerfil() {
        $this->requireAuth(['maestro']);
        
        $maestro = $this->maestroModel->getByUsuarioId($_SESSION['usuario_id']);
        if (!$maestro) {
            redirect('register');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'anios_experiencia' => (int)($_POST['anios_experiencia'] ?? 0),
                'area_preferida' => sanitize($_POST['area_preferida'] ?? ''),
                'descripcion' => sanitize($_POST['descripcion'] ?? ''),
                'especialidades' => $_POST['especialidades'] ?? [],
                'distritos' => $_POST['distritos'] ?? []
            ];

            if ($this->maestroModel->update($maestro['id'], $data)) {
                // Si el perfil estaba rechazado, cambiar a pendiente para nueva validación
                if ($maestro['estado_perfil'] === 'rechazado') {
                    $this->maestroModel->validate($maestro['id'], 'pendiente', null, null);
                    $_SESSION['success'] = 'Perfil actualizado correctamente. Tu perfil ha sido enviado nuevamente para validación.';
                } else {
                    $_SESSION['success'] = 'Perfil actualizado correctamente';
                }
                
                // Actualizar datos del usuario
                $usuarioData = [];
                if (isset($_POST['nombre_completo'])) {
                    $usuarioData['nombre_completo'] = sanitize($_POST['nombre_completo']);
                }
                if (isset($_POST['telefono'])) {
                    $usuarioData['telefono'] = sanitize($_POST['telefono']);
                }
                if (isset($_POST['password']) && !empty($_POST['password'])) {
                    $usuarioData['password'] = $_POST['password'];
                }
                if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                    $upload = $this->uploadFile($_FILES['foto_perfil'], 'perfiles', ALLOWED_IMAGE_TYPES);
                    if ($upload['success']) {
                            $usuarioData['foto_perfil'] = $upload['path'];
                            // Guardar ruta relativa en sesión para consistencia
                            $_SESSION['foto_perfil'] = $upload['path'];
                    }
                }
                
                if (!empty($usuarioData)) {
                    if ($this->usuarioModel->update($_SESSION['usuario_id'], $usuarioData)) {
                        if (isset($usuarioData['nombre_completo'])) {
                            $_SESSION['nombre_completo'] = $usuarioData['nombre_completo'];
                        }
                        if (isset($usuarioData['foto_perfil'])) {
                            $_SESSION['foto_perfil'] = $usuarioData['foto_perfil'];
                        }
                    }
                }

                redirect('maestro/dashboard');
            } else {
                $_SESSION['error'] = 'Error al actualizar el perfil';
            }
        }

        // Verificar estado de pago
        $pagoInfo = $this->verificarPagoExpirado($maestro['id']);
        $pago_expirado = $pagoInfo['expirado'];

        $especialidadModel = new Especialidad($this->db);
        $distritoModel = new Distrito($this->db);

        $data = [
            'maestro' => $maestro,
            'especialidades' => $especialidadModel->getAll(),
            'distritos' => $distritoModel->getAll(),
            'maestro_especialidades' => $this->maestroModel->getEspecialidades($maestro['id']),
            'maestro_distritos' => $this->maestroModel->getDistritos($maestro['id']),
            'pago_expirado' => $pago_expirado
        ];

        $this->view('maestro/editar-perfil', $data);
    }

    public function portafolio() {
        $this->requireAuth(['maestro']);
        
        $maestro = $this->maestroModel->getByUsuarioId($_SESSION['usuario_id']);
        if (!$maestro) {
            redirect('register');
        }

        $portafolioModel = new Portafolio($this->db);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['action']) && $_POST['action'] === 'delete') {
                $id = (int)($_POST['id'] ?? 0);
                if ($portafolioModel->delete($id, $maestro['id'])) {
                    $_SESSION['success'] = 'Imagen eliminada del portafolio';
                } else {
                    $_SESSION['error'] = 'Error al eliminar la imagen';
                }
                redirect('maestro/portafolio');
            }

            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $upload = $this->uploadFile($_FILES['imagen'], 'portafolio', ALLOWED_IMAGE_TYPES);
                if ($upload['success']) {
                    $data = [
                        'titulo' => sanitize($_POST['titulo'] ?? ''),
                        'descripcion' => sanitize($_POST['descripcion'] ?? ''),
                        'imagen' => $upload['path'],
                        'orden' => (int)($_POST['orden'] ?? 0)
                    ];
                    
                    $result = $portafolioModel->add($maestro['id'], $data);
                    if ($result['success']) {
                        $_SESSION['success'] = 'Imagen agregada al portafolio';
                    } else {
                        $_SESSION['error'] = $result['message'];
                    }
                } else {
                    $_SESSION['error'] = $upload['message'];
                }
                redirect('maestro/portafolio');
            }
        }

        // Verificar estado de pago
        $pagoInfo = $this->verificarPagoExpirado($maestro['id']);
        $pago_expirado = $pagoInfo['expirado'];

        $data = [
            'maestro' => $maestro,
            'portafolio' => $portafolioModel->getByMaestro($maestro['id']),
            'pago_expirado' => $pago_expirado
        ];

        $this->view('maestro/portafolio', $data);
    }

    public function disponibilidad() {
        $this->requireAuth(['maestro']);
        
        $maestro = $this->maestroModel->getByUsuarioId($_SESSION['usuario_id']);
        if (!$maestro) {
            redirect('register');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $disponibilidad = sanitize($_POST['disponibilidad'] ?? '');
            
            if (in_array($disponibilidad, ['disponible', 'ocupado', 'no_disponible'])) {
                if ($this->maestroModel->updateDisponibilidad($maestro['id'], $disponibilidad)) {
                    $_SESSION['success'] = 'Disponibilidad actualizada';
                } else {
                    $_SESSION['error'] = 'Error al actualizar disponibilidad';
                }
            }
            redirect('maestro/disponibilidad');
        }

        // Verificar estado de pago
        $pagoInfo = $this->verificarPagoExpirado($maestro['id']);
        $pago_expirado = $pagoInfo['expirado'];

        $data = [
            'maestro' => $maestro,
            'pago_expirado' => $pago_expirado
        ];
        $this->view('maestro/disponibilidad', $data);
    }

    public function calificaciones() {
        $this->requireAuth(['maestro']);
        
        $maestro = $this->maestroModel->getByUsuarioId($_SESSION['usuario_id']);
        if (!$maestro) {
            redirect('register');
        }

        $calificacionModel = new Calificacion($this->db);

        // Verificar estado de pago
        $pagoInfo = $this->verificarPagoExpirado($maestro['id']);
        $pago_expirado = $pagoInfo['expirado'];

        $data = [
            'maestro' => $maestro,
            'calificaciones' => $calificacionModel->getByMaestro($maestro['id']),
            'pago_expirado' => $pago_expirado
        ];

        $this->view('maestro/calificaciones', $data);
    }

    public function historial() {
        $this->requireAuth(['maestro']);
        
        $maestro = $this->maestroModel->getByUsuarioId($_SESSION['usuario_id']);
        if (!$maestro) {
            redirect('register');
        }

        // Verificar estado de pago
        $pagoInfo = $this->verificarPagoExpirado($maestro['id']);
        $pago_expirado = $pagoInfo['expirado'];

        $data = [
            'maestro' => $maestro,
            'pago_expirado' => $pago_expirado
        ];
        $this->view('maestro/historial', $data);
    }

    public function configuracion() {
        $this->requireAuth(['maestro']);
        
        $maestro = $this->maestroModel->getByUsuarioId($_SESSION['usuario_id']);
        if (!$maestro) {
            redirect('register');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $notificaciones_activas = isset($_POST['notificaciones_activas']) ? 1 : 0;
            
            if ($this->maestroModel->update($maestro['id'], ['notificaciones_activas' => $notificaciones_activas])) {
                $_SESSION['success'] = 'Configuración actualizada';
            } else {
                $_SESSION['error'] = 'Error al actualizar configuración';
            }
            redirect('maestro/configuracion');
        }

        // Verificar estado de pago
        $pagoInfo = $this->verificarPagoExpirado($maestro['id']);
        $pago_expirado = $pagoInfo['expirado'];

        $data = [
            'maestro' => $maestro,
            'pago_expirado' => $pago_expirado
        ];
        $this->view('maestro/configuracion', $data);
    }
    
    /**
     * API: Verificar estado del perfil (para detectar rechazos en tiempo real)
     */
    public function verificarEstadoPerfil() {
        if (!isLoggedIn() || !isMaestro()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            exit;
        }

        $maestro = $this->maestroModel->getByUsuarioId($_SESSION['usuario_id']);
        if (!$maestro) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Perfil no encontrado']);
            exit;
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'estado_perfil' => $maestro['estado_perfil'],
            'motivo_rechazo' => $maestro['motivo_rechazo'] ?? null,
            'fecha_validacion' => $maestro['fecha_validacion'] ?? null,
            'rechazado' => $maestro['estado_perfil'] === 'rechazado'
        ]);
        exit;
    }
}

