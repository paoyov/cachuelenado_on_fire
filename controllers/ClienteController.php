<?php
/**
 * Controlador Cliente
 */

class ClienteController extends Controller {
    private $usuarioModel;
    private $trabajoModel;

    public function __construct() {
        parent::__construct();
        $this->requireAuth(['cliente']);
        $this->usuarioModel = new Usuario($this->db);
        $this->trabajoModel = new Trabajo($this->db);
    }

    public function dashboard() {
        $cliente_id = $_SESSION['usuario_id'];

        // Contadores rápidos
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM busquedas WHERE cliente_id = :cliente_id");
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->execute();
        $busquedas_count = $stmt->fetchColumn() ?: 0;

        $usuario = $this->usuarioModel->getById($cliente_id);

        $data = [
            'usuario' => $usuario,
            'busquedas_count' => (int)$busquedas_count
        ];

        $this->view('cliente/dashboard', $data);
    }

    public function completar_trabajo() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $trabajo_id = $_POST['trabajo_id'] ?? null;
            if ($trabajo_id) {
                $trabajo = $this->trabajoModel->getById($trabajo_id);
                if ($trabajo && $trabajo['cliente_id'] == $_SESSION['usuario_id']) {
                    if ($this->trabajoModel->updateEstado($trabajo_id, 'completado')) {
                        echo json_encode(['success' => true]);
                        return;
                    }
                }
            }
        }
        echo json_encode(['success' => false, 'message' => 'Error al completar trabajo']);
    }

    public function calificar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'cliente_id' => $_SESSION['usuario_id'],
                'maestro_id' => $_POST['maestro_id'],
                'trabajo_id' => !empty($_POST['trabajo_id']) ? $_POST['trabajo_id'] : null,
                'puntualidad' => $_POST['puntualidad'],
                'calidad' => $_POST['calidad'],
                'trato' => $_POST['trato'],
                'limpieza' => $_POST['limpieza'],
                'comentario' => $_POST['comentario']
            ];

            $calificacionModel = new Calificacion($this->db);
            if ($calificacionModel->create($data)) {
                echo json_encode(['success' => true]);
                return;
            }
        }
        echo json_encode(['success' => false, 'message' => 'Error al guardar calificación']);
    }

    public function perfil() {
        $usuario = $this->usuarioModel->getById($_SESSION['usuario_id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [];
            if (isset($_POST['nombre_completo'])) {
                $data['nombre_completo'] = sanitize($_POST['nombre_completo']);
            }
            if (isset($_POST['telefono'])) {
                $data['telefono'] = sanitize($_POST['telefono']);
            }
            if (isset($_POST['password']) && !empty($_POST['password'])) {
                $data['password'] = $_POST['password'];
            }
            if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
                $upload = $this->uploadFile($_FILES['foto_perfil'], 'perfiles', ALLOWED_IMAGE_TYPES);
                if ($upload['success']) {
                    $data['foto_perfil'] = $upload['path'];
                    // Guardar la ruta relativa en sesión (se concatenará con UPLOAD_URL en la vista)
                    $_SESSION['foto_perfil'] = $upload['path'];
                }
            }

            if ($this->usuarioModel->update($_SESSION['usuario_id'], $data)) {
                // Actualizar sesión con el nuevo nombre si fue cambiado
                if (isset($data['nombre_completo'])) {
                    $_SESSION['nombre_completo'] = $data['nombre_completo'];
                }

                $_SESSION['success'] = 'Perfil actualizado correctamente';
                redirect('cliente/perfil');
            } else {
                $_SESSION['error'] = 'Error al actualizar el perfil';
            }
        }

        $data = ['usuario' => $usuario];
        $this->view('cliente/perfil', $data);
    }

    public function historial() {
        $calificacionModel = new Calificacion($this->db);
        
        $data = [
            'calificaciones' => $calificacionModel->getByMaestro(null) // Necesitaría ajustar este método
        ];

        $this->view('cliente/historial', $data);
    }

    public function calificaciones() {
        $calificacionModel = new Calificacion($this->db);
        
        $maestroModel = new Maestro($this->db);
        
        // Obtener calificaciones hechas por el cliente
        $calificaciones = $calificacionModel->getByCliente($_SESSION['usuario_id']);
        
        // Obtener lista de maestros para el selector (solo validados)
        $maestros = $maestroModel->search();

        $data = [
            'calificaciones' => $calificaciones,
            'maestros' => $maestros
        ];

        $this->view('cliente/calificaciones', $data);
    }
}

