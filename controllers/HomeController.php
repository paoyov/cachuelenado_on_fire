<?php
/**
 * Controlador Home
 */

class HomeController extends Controller {
    public function index() {
        $maestroModel = new Maestro($this->db);
        $especialidadModel = new Especialidad($this->db);
        
        // Obtener maestros destacados (mejor calificación)
        $maestrosDestacados = $maestroModel->search(['limit' => 6]);
        
        // Obtener calificaciones recientes
        $calificacionModel = new Calificacion($this->db);
        $calificacionesRecientes = $calificacionModel->getRecent(6);

        $data = [
            'maestros_destacados' => $maestrosDestacados,
            'especialidades' => $especialidadModel->getAll(),
            'calificaciones_globales' => $calificacionesRecientes
        ];
        
        $this->view('home/index', $data);
    }
}

