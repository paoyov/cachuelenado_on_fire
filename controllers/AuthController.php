<?php
/**
 * Controlador de Autenticación
 */

class AuthController extends Controller {
    private $usuarioModel;

    public function __construct() {
        parent::__construct();
        $this->usuarioModel = new Usuario($this->db);
    }

    public function login() {
        if (isLoggedIn()) {
            redirect($this->getDashboardUrl());
        }
        $this->view('auth/login');
    }

    public function register() {
        if (isLoggedIn()) {
            redirect($this->getDashboardUrl());
        }
        
        $especialidadModel = new Especialidad($this->db);
        $distritoModel = new Distrito($this->db);
        
        $data = [
            'especialidades' => $especialidadModel->getAll(),
            'distritos' => $distritoModel->getAll()
        ];
        
        $this->view('auth/register', $data);
    }

    public function processLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('login');
        }

        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Por favor, complete todos los campos';
            redirect('login');
        }

        $usuario = $this->usuarioModel->login($email, $password);

        if ($usuario) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
            $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
            $_SESSION['foto_perfil'] = $usuario['foto_perfil'] ?? null;
            
            redirect($this->getDashboardUrl());
        } else {
            $_SESSION['error'] = 'Email o contraseña incorrectos';
            redirect('login');
        }
    }

    public function processRegister() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('register');
        }

        $tipo_usuario = sanitize($_POST['tipo_usuario'] ?? '');
        
        if (!in_array($tipo_usuario, ['cliente', 'maestro'])) {
            $_SESSION['error'] = 'Tipo de usuario inválido';
            redirect('register');
        }

        // Validar datos comunes
        $nombre_completo = sanitize($_POST['nombre_completo'] ?? '');
        $email = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $telefono = sanitize($_POST['telefono'] ?? '');
        $dni = sanitize($_POST['dni'] ?? '');

        if (empty($nombre_completo) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'Por favor, complete todos los campos obligatorios';
            redirect('register');
        }

        // Verificar si el email ya existe
        if ($this->usuarioModel->getByEmail($email)) {
            $_SESSION['error'] = 'El email ya está registrado';
            redirect('register');
        }

        // Validar contraseña
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'La contraseña debe tener al menos 6 caracteres';
            redirect('register');
        }

        // Subir foto de perfil si existe
        $foto_perfil = null;
        if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
            $upload = $this->uploadFile($_FILES['foto_perfil'], 'perfiles', ALLOWED_IMAGE_TYPES);
            if ($upload['success']) {
                $foto_perfil = $upload['path'];
            }
        }

        $usuarioData = [
            'tipo_usuario' => $tipo_usuario,
            'nombre_completo' => $nombre_completo,
            'email' => $email,
            'password' => $password,
            'telefono' => $telefono,
            'dni' => $dni,
            'foto_perfil' => $foto_perfil,
            'chapa' => sanitize($_POST['chapa'] ?? '')
        ];

        $usuario_id = $this->usuarioModel->register($usuarioData);

        if ($usuario_id) {
            // Si es maestro, crear perfil de maestro
            if ($tipo_usuario === 'maestro') {
                $maestroModel = new Maestro($this->db);
                $maestroData = [
                    'anios_experiencia' => (int)($_POST['anios_experiencia'] ?? 0),
                    'area_preferida' => sanitize($_POST['area_preferida'] ?? ''),
                    'descripcion' => sanitize($_POST['descripcion'] ?? ''),
                    'disponibilidad' => 'disponible',
                    'especialidades' => $_POST['especialidades'] ?? [],
                    'distritos' => $_POST['distritos'] ?? []
                ];
                
                $maestro_id = $maestroModel->create($usuario_id, $maestroData);
                
                // Subir documentos
                if (isset($_FILES['documentos'])) {
                    $documentoModel = new DocumentoMaestro($this->db);
                    $this->uploadDocuments($maestro_id, $_FILES['documentos'], $documentoModel);
                }
            }

            // Iniciar sesión automáticamente
            $_SESSION['usuario_id'] = $usuario_id;
            $_SESSION['tipo_usuario'] = $tipo_usuario;
            $_SESSION['nombre_completo'] = $nombre_completo;
            $_SESSION['foto_perfil'] = $foto_perfil;

            $_SESSION['success'] = 'Registro exitoso. ' . 
                ($tipo_usuario === 'maestro' ? 'Su perfil está pendiente de validación.' : '');
            
            redirect($this->getDashboardUrl());
        } else {
            $_SESSION['error'] = 'Error al registrar. Por favor, intente nuevamente';
            redirect('register');
        }
    }

    public function logout() {
        session_destroy();
        redirect('home');
    }

    private function getDashboardUrl() {
        if (!isLoggedIn()) {
            return 'home';
        }

        switch ($_SESSION['tipo_usuario']) {
            case 'administrador':
                return 'admin/dashboard';
            case 'maestro':
                return 'maestro/dashboard';
            case 'cliente':
                return 'cliente/dashboard';
            default:
                return 'home';
        }
    }

    private function uploadDocuments($maestro_id, $files, $documentoModel) {
        $tipos = ['dni', 'certificado', 'foto_trabajo'];
        
        foreach ($tipos as $tipo) {
            if (isset($files[$tipo]) && is_array($files[$tipo]['name'])) {
                foreach ($files[$tipo]['name'] as $key => $name) {
                    if ($files[$tipo]['error'][$key] === UPLOAD_ERR_OK) {
                        $file = [
                            'name' => $files[$tipo]['name'][$key],
                            'type' => $files[$tipo]['type'][$key],
                            'tmp_name' => $files[$tipo]['tmp_name'][$key],
                            'error' => $files[$tipo]['error'][$key],
                            'size' => $files[$tipo]['size'][$key]
                        ];
                        
                        $upload = $this->uploadFile($file, 'documentos/' . $tipo, ALLOWED_DOCUMENT_TYPES);
                        if ($upload['success']) {
                            $documentoModel->add($maestro_id, $tipo, $upload['filename'], $upload['path']);
                        }
                    }
                }
            }
        }
    }
}

