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

    public function dashboard() {
        $this->requireAuth(['maestro']);
        
        $maestro = $this->maestroModel->getByUsuarioId($_SESSION['usuario_id']);
        if (!$maestro) {
            redirect('register');
        }

        $calificacionModel = new Calificacion($this->db);
        $notificacionModel = new Notificacion($this->db);

        $data = [
            'maestro' => $maestro,
            'calificaciones_recientes' => $calificacionModel->getByMaestro($maestro['id'], 5),
            'notificaciones' => $notificacionModel->getByUsuario($_SESSION['usuario_id'], 5, true),
            'calificaciones_globales' => $calificacionModel->getRecent(6)
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
                // Actualizar datos del usuario
                $usuarioData = [];
                if (isset($_POST['nombre_completo'])) {
                    $usuarioData['nombre_completo'] = sanitize($_POST['nombre_completo']);
                }
                if (isset($_POST['telefono'])) {
                    $usuarioData['telefono'] = sanitize($_POST['telefono']);
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

                $_SESSION['success'] = 'Perfil actualizado correctamente';
                redirect('maestro/dashboard');
            } else {
                $_SESSION['error'] = 'Error al actualizar el perfil';
            }
        }

        $especialidadModel = new Especialidad($this->db);
        $distritoModel = new Distrito($this->db);

        $data = [
            'maestro' => $maestro,
            'especialidades' => $especialidadModel->getAll(),
            'distritos' => $distritoModel->getAll(),
            'maestro_especialidades' => $this->maestroModel->getEspecialidades($maestro['id']),
            'maestro_distritos' => $this->maestroModel->getDistritos($maestro['id'])
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

        $data = [
            'maestro' => $maestro,
            'portafolio' => $portafolioModel->getByMaestro($maestro['id'])
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

        $data = ['maestro' => $maestro];
        $this->view('maestro/disponibilidad', $data);
    }

    public function calificaciones() {
        $this->requireAuth(['maestro']);
        
        $maestro = $this->maestroModel->getByUsuarioId($_SESSION['usuario_id']);
        if (!$maestro) {
            redirect('register');
        }

        $calificacionModel = new Calificacion($this->db);

        $data = [
            'maestro' => $maestro,
            'calificaciones' => $calificacionModel->getByMaestro($maestro['id'])
        ];

        $this->view('maestro/calificaciones', $data);
    }

    public function historial() {
        $this->requireAuth(['maestro']);
        
        $maestro = $this->maestroModel->getByUsuarioId($_SESSION['usuario_id']);
        if (!$maestro) {
            redirect('register');
        }

        $data = ['maestro' => $maestro];
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

        $data = ['maestro' => $maestro];
        $this->view('maestro/configuracion', $data);
    }
}

