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
        $mensajeModel = new Mensaje($this->db);
        $notificacionModel = new Notificacion($this->db);
        $cliente_id = $_SESSION['usuario_id'];

        // Contadores rápidos
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM busquedas WHERE cliente_id = :cliente_id");
        $stmt->bindParam(':cliente_id', $cliente_id);
        $stmt->execute();
        $busquedas_count = $stmt->fetchColumn() ?: 0;

        $stmt2 = $this->db->prepare("SELECT COUNT(*) as total FROM trabajos WHERE cliente_id = :cliente_id");
        $stmt2->bindParam(':cliente_id', $cliente_id);
        $stmt2->execute();
        $trabajos_count = $stmt2->fetchColumn() ?: 0;

        $usuario = $this->usuarioModel->getById($cliente_id);

        $calificacionModel = new Calificacion($this->db);
        $calificacionesGlobales = $calificacionModel->getRecent(6);

        $data = [
            'usuario' => $usuario,
            'mensajes_recientes' => $mensajeModel->getConversationsByCliente($cliente_id),
            'notificaciones' => $notificacionModel->getByUsuario($cliente_id, 5, true),
            'busquedas_count' => (int)$busquedas_count,
            'trabajos_count' => (int)$trabajos_count,
            'trabajos_activos' => $this->trabajoModel->getByCliente($cliente_id),
            'calificaciones_globales' => $calificacionesGlobales
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
                'trabajo_id' => $_POST['trabajo_id'],
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

    public function mensajes() {
        $mensajeModel = new Mensaje($this->db);
        $maestro_id = isset($_GET['maestro_id']) ? (int)$_GET['maestro_id'] : null;

        $data = [
            'conversaciones' => $mensajeModel->getConversationsByCliente($_SESSION['usuario_id']),
            'maestro_id' => $maestro_id
        ];

        if ($maestro_id) {
            $maestroModel = new Maestro($this->db);
            $maestro = $maestroModel->getById($maestro_id);
            if ($maestro) {
                // Permitimos iniciar la conversación aunque el perfil no esté marcado como 'validado'.
                
                // Obtener especialidades
                $especialidades = $maestroModel->getEspecialidades($maestro_id);
                $nombres_especialidades = array_map(function($e) { return $e['nombre']; }, $especialidades);
                $maestro['especialidad'] = !empty($nombres_especialidades) ? implode(', ', $nombres_especialidades) : '—';

                $data['maestro'] = $maestro;
                $data['mensajes'] = $mensajeModel->getConversation($_SESSION['usuario_id'], $maestro_id);
                $mensajeModel->markAsRead($_SESSION['usuario_id'], $maestro_id, 'maestro');
            }
        }

        $this->view('cliente/mensajes', $data);
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
        
        // En una implementación real, aquí obtendríamos las calificaciones dadas por el cliente
        // o las calificaciones recibidas si fuera relevante.
        // Por ahora, pasamos un array vacío o datos de prueba si el modelo lo soportara.
        $data = [
            'calificaciones' => [] 
        ];

        $this->view('cliente/calificaciones', $data);
    }
}

