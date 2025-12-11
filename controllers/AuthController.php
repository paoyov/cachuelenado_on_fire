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
                
                // Debug: Log file upload attempt
                error_log("=== DOCUMENT UPLOAD DEBUG ===");
                error_log("Maestro ID created: " . $maestro_id);
                error_log("FILES array: " . print_r($_FILES, true));
                error_log("POST array: " . print_r($_POST, true));
                
                // Subir documentos
                if (isset($_FILES['documentos'])) {
                    error_log("documentos key exists in FILES");
                    $documentoModel = new DocumentoMaestro($this->db);
                    $this->uploadDocuments($maestro_id, $_FILES['documentos'], $documentoModel);
                } else {
                    error_log("WARNING: documentos key NOT found in FILES array!");
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
        error_log("=== uploadDocuments called ===");
        error_log("Maestro ID: " . $maestro_id);
        error_log("FILES structure: " . print_r($files, true));
        
        // The HTML inputs create this structure:
        // <input name="documentos[dni][]"> creates $_FILES['documentos']['name']['dni'][0]
        // NOT $_FILES['documentos']['dni']['name'][0]
        
        $tipos = ['dni', 'certificado', 'foto_trabajo'];
        $uploadCount = 0;
        
        foreach ($tipos as $tipo) {
            error_log("Processing tipo: " . $tipo);
            
            // Check if this document type has any files
            if (isset($files['name'][$tipo]) && is_array($files['name'][$tipo])) {
                $fileCount = count($files['name'][$tipo]);
                error_log("Found {$fileCount} file(s) for tipo: {$tipo}");
                
                for ($i = 0; $i < $fileCount; $i++) {
                    // Skip if no file was uploaded (empty file input)
                    if (empty($files['name'][$tipo][$i])) {
                        error_log("Skipping empty file at index {$i} for tipo: {$tipo}");
                        continue;
                    }
                    
                    // Check for upload errors
                    if ($files['error'][$tipo][$i] !== UPLOAD_ERR_OK) {
                        error_log("Upload error for {$tipo}[{$i}]: " . $files['error'][$tipo][$i]);
                        continue;
                    }
                    
                    // Reconstruct file array for uploadFile method
                    $file = [
                        'name' => $files['name'][$tipo][$i],
                        'type' => $files['type'][$tipo][$i],
                        'tmp_name' => $files['tmp_name'][$tipo][$i],
                        'error' => $files['error'][$tipo][$i],
                        'size' => $files['size'][$tipo][$i]
                    ];
                    
                    error_log("Uploading file: {$file['name']} (size: {$file['size']} bytes)");
                    
                    // Upload the file
                    $upload = $this->uploadFile($file, 'documentos/' . $tipo, ALLOWED_DOCUMENT_TYPES);
                    
                    if ($upload['success']) {
                        error_log("File uploaded successfully: " . $upload['path']);
                        
                        // Save to database
                        $result = $documentoModel->add($maestro_id, $tipo, $upload['filename'], $upload['path']);
                        
                        if ($result) {
                            error_log("✓ Document saved to database: {$tipo} - {$upload['filename']}");
                            $uploadCount++;
                        } else {
                            error_log("✗ Failed to save document to database");
                        }
                    } else {
                        error_log("✗ File upload failed: " . ($upload['message'] ?? 'Unknown error'));
                    }
                }
            } else {
                error_log("No files found for tipo: {$tipo}");
            }
        }
        
        error_log("=== Upload complete: {$uploadCount} document(s) saved ===");
        return $uploadCount;
    }
}

