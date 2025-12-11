<?php
/**
 * Controlador API
 */

class ApiController extends Controller {




    public function buscar() {
        header('Content-Type: application/json');
        
        $maestroModel = new Maestro($this->db);
        $filters = [
            'especialidad_id' => $_GET['especialidad'] ?? null,
            'distrito_id' => $_GET['distrito'] ?? null,
            'calificacion_minima' => $_GET['calificacion'] ?? null,
            'disponibilidad' => $_GET['disponibilidad'] ?? null
        ];

        $resultados = $maestroModel->search($filters);
        $this->json(['success' => true, 'resultados' => $resultados]);
    }

    public function actualizarDisponibilidad() {
        header('Content-Type: application/json');
        
        if (!isMaestro()) {
            $this->json(['success' => false, 'message' => 'No autorizado'], 401);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
        }

        $disponibilidad = sanitize($_POST['disponibilidad'] ?? '');
        
        if (!in_array($disponibilidad, ['disponible', 'ocupado', 'no_disponible'])) {
            $this->json(['success' => false, 'message' => 'Disponibilidad inválida']);
        }

        $maestroModel = new Maestro($this->db);
        $maestro = $maestroModel->getByUsuarioId($_SESSION['usuario_id']);
        
        if ($maestro && $maestroModel->updateDisponibilidad($maestro['id'], $disponibilidad)) {
            $this->json(['success' => true, 'message' => 'Disponibilidad actualizada']);
        } else {
            $this->json(['success' => false, 'message' => 'Error al actualizar disponibilidad']);
        }
    }

    public function calificar() {
        header('Content-Type: application/json');
        
        if (!isCliente()) {
            $this->json(['success' => false, 'message' => 'No autorizado'], 401);
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
        }

        $data = [
            'cliente_id' => $_SESSION['usuario_id'],
            'maestro_id' => (int)($_POST['maestro_id'] ?? 0),
            'trabajo_id' => !empty($_POST['trabajo_id']) ? (int)$_POST['trabajo_id'] : null,
            'puntualidad' => (int)($_POST['puntualidad'] ?? 0),
            'calidad' => (int)($_POST['calidad'] ?? 0),
            'trato' => (int)($_POST['trato'] ?? 0),
            'limpieza' => (int)($_POST['limpieza'] ?? 0),
            'comentario' => sanitize($_POST['comentario'] ?? '')
        ];

        // Validar calificaciones
        foreach (['puntualidad', 'calidad', 'trato', 'limpieza'] as $campo) {
            if ($data[$campo] < 1 || $data[$campo] > 5) {
                $this->json(['success' => false, 'message' => 'Las calificaciones deben estar entre 1 y 5']);
            }
        }

        $calificacionModel = new Calificacion($this->db);
        
        // Verificar si ya calificó este trabajo
        if ($data['trabajo_id'] && $calificacionModel->hasRated($data['cliente_id'], $data['trabajo_id'])) {
            $this->json(['success' => false, 'message' => 'Ya has calificado este trabajo']);
        }

        if ($calificacionModel->create($data)) {
            $this->json(['success' => true, 'message' => 'Calificación registrada']);
        } else {
            $this->json(['success' => false, 'message' => 'Error al registrar calificación']);
        }
    }

    public function marcarNotificacion() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Método no permitido'], 405);
        }
        if (!isLoggedIn()) {
            $this->json(['success' => false, 'message' => 'No autorizado'], 401);
        }

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            $this->json(['success' => false, 'message' => 'ID inválido']);
        }

        $notModel = new Notificacion($this->db);
        if ($notModel->markAsRead($id, $_SESSION['usuario_id'])) {
            $this->json(['success' => true]);
        }
        $this->json(['success' => false, 'message' => 'No se pudo marcar la notificación']);
    }


}

