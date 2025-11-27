<?php
/**
 * Controlador de Búsqueda
 */

class BuscarController extends Controller {
    public function index() {
        $maestroModel = new Maestro($this->db);
        $especialidadModel = new Especialidad($this->db);
        $distritoModel = new Distrito($this->db);
        $busquedaModel = new Busqueda($this->db);

        $filters = [
            'especialidad_id' => $_GET['especialidad'] ?? null,
            'distrito_id' => $_GET['distrito'] ?? null,
            'calificacion_minima' => $_GET['calificacion'] ?? null,
            'disponibilidad' => $_GET['disponibilidad'] ?? null
        ];

        // Normalizar filtros: convertir cadenas vacías a null y castear IDs
        foreach ($filters as $key => $val) {
            if (is_string($val)) {
                $val = trim($val);
                if ($val === '') {
                    $filters[$key] = null;
                    continue;
                }
            }
            // castear campos numéricos
            if (in_array($key, ['especialidad_id', 'distrito_id', 'calificacion_minima']) && $filters[$key] !== null) {
                $filters[$key] = (int)$filters[$key];
            }
        }

        // Registrar búsqueda si hay un cliente logueado
        if (isCliente() && !empty(array_filter($filters))) {
            $busquedaModel->register([
                'cliente_id' => $_SESSION['usuario_id'],
                'especialidad_id' => $filters['especialidad_id'],
                'distrito_id' => $filters['distrito_id'],
                'calificacion_minima' => $filters['calificacion_minima'],
                'disponibilidad' => $filters['disponibilidad']
            ]);
        }

        $resultados = [];
        // Verificar si se ha realizado una búsqueda (si existen los parámetros en GET)
        $isSearch = isset($_GET['especialidad']) || isset($_GET['distrito']) || isset($_GET['calificacion']) || isset($_GET['disponibilidad']);

        if ($isSearch) {
            $resultados = $maestroModel->search($filters);
        }

        $data = [
            'resultados' => $resultados,
            'especialidades' => $especialidadModel->getAll(),
            'distritos' => $distritoModel->getAll(),
            'filters' => $filters
        ];

        $this->view('buscar/index', $data);
    }
}

