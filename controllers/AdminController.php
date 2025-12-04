<?php
/**
 * Controlador Administrador
 */

class AdminController extends Controller {
    private $usuarioModel;
    private $maestroModel;

    public function __construct() {
        parent::__construct();
        $this->requireAuth(['administrador']);
        $this->usuarioModel = new Usuario($this->db);
        $this->maestroModel = new Maestro($this->db);
    }

    public function dashboard() {
        $busquedaModel = new Busqueda($this->db);
        $notificacionModel = new Notificacion($this->db);

        $data = [
            'total_clientes' => $this->usuarioModel->countByType('cliente'),
            'total_maestros' => $this->usuarioModel->countByType('maestro'),
            'total_busquedas' => $busquedaModel->getTotalSearches(),
            'maestros_pendientes' => count($this->maestroModel->getPendingValidation()),
            'notificaciones' => $notificacionModel->getByUsuario($_SESSION['usuario_id'], 5, true)
        ];

        $this->view('admin/dashboard', $data);
    }

    public function maestros() {
        $estado = $_GET['estado'] ?? 'pendiente';
        
        if ($estado === 'pendiente') {
            $maestros = $this->maestroModel->getPendingValidation();
        } else {
            $query = "SELECT m.*, u.nombre_completo, u.email, u.telefono, u.dni, u.foto_perfil
                      FROM maestros m
                      INNER JOIN usuarios u ON m.usuario_id = u.id
                      WHERE m.estado_perfil = :estado
                      ORDER BY m.fecha_validacion DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':estado', $estado);
            $stmt->execute();
            $maestros = $stmt->fetchAll();
        }

        $data = [
            'maestros' => $maestros,
            'estado' => $estado
        ];

        $this->view('admin/maestros', $data);
    }

    public function validarPerfil() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/maestros');
        }

        $maestro_id = (int)($_POST['maestro_id'] ?? 0);
        $accion = sanitize($_POST['accion'] ?? '');
        $motivo_rechazo = sanitize($_POST['motivo_rechazo'] ?? null);

        if (!in_array($accion, ['validar', 'rechazar'])) {
            $_SESSION['error'] = 'Acción inválida';
            redirect('admin/maestros');
        }

        $estado = $accion === 'validar' ? 'validado' : 'rechazado';
        
        if ($this->maestroModel->validate($maestro_id, $estado, $_SESSION['usuario_id'], $motivo_rechazo)) {
            // Obtener información del maestro para notificación
            $maestro = $this->maestroModel->getById($maestro_id);
            $usuario = $this->usuarioModel->getById($maestro['usuario_id']);
            
            // Crear notificación
            $notificacionModel = new Notificacion($this->db);
            $titulo = $accion === 'validar' ? 'Perfil Validado' : 'Perfil Rechazado';
            $mensaje = $accion === 'validar' 
                ? 'Su perfil ha sido validado y ahora es visible para los clientes.'
                : 'Su perfil ha sido rechazado. Motivo: ' . ($motivo_rechazo ?? 'No especificado');
            
            $notificacionModel->create($maestro['usuario_id'], 'validacion', $titulo, $mensaje);
            
            // Enviar email y WhatsApp
            try {
                $emailService = new EmailService();
                $emailService->sendValidationNotification(
                    $usuario['email'],
                    $usuario['nombre_completo'],
                    $accion === 'validar',
                    $motivo_rechazo
                );
            } catch (Exception $e) {
                error_log("Error al enviar email: " . $e->getMessage());
            }
            
            try {
                if (!empty($usuario['telefono'])) {
                    $whatsappService = new WhatsAppService();
                    $whatsappService->sendValidationNotification(
                        $usuario['telefono'],
                        $usuario['nombre_completo'],
                        $accion === 'validar',
                        $motivo_rechazo
                    );
                }
            } catch (Exception $e) {
                error_log("Error al enviar WhatsApp: " . $e->getMessage());
            }
            
            $_SESSION['success'] = 'Perfil ' . ($accion === 'validar' ? 'validado' : 'rechazado') . ' correctamente';
        } else {
            $_SESSION['error'] = 'Error al procesar la validación';
        }

        redirect('admin/maestros');
    }

    public function estadisticas() {
        $busquedaModel = new Busqueda($this->db);
        $calificacionModel = new Calificacion($this->db);

        // Calcular calificación promedio del sistema
        $query = "SELECT AVG(calificacion_promedio) as promedio FROM maestros WHERE estado_perfil = 'validado'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        $calificacion_promedio = $result['promedio'] ?? 0;

        // Consultar total de trabajos
        $queryTrabajos = "SELECT COUNT(*) as total FROM trabajos";
        $stmtTrabajos = $this->db->prepare($queryTrabajos);
        $stmtTrabajos->execute();
        $trabajosResult = $stmtTrabajos->fetch();
        $total_trabajos = $trabajosResult['total'] ?? 0;

        $data = [
            'total_clientes' => $this->usuarioModel->countByType('cliente'),
            'total_maestros' => $this->usuarioModel->countByType('maestro'),
            'total_busquedas' => $busquedaModel->getTotalSearches(),
            'total_trabajos' => $total_trabajos,
            'calificacion_promedio' => round($calificacion_promedio, 2)
        ];

        $this->view('admin/estadisticas', $data);
    }

    public function usuarios() {
        // Procesar acciones POST: ver, suspender, eliminar
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario_id = (int)($_POST['usuario_id'] ?? 0);
            $accion = $_POST['accion'] ?? '';
            if ($usuario_id > 0 && in_array($accion, ['ver', 'suspender', 'eliminar'])) {
                if ($accion === 'suspender') {
                    if ($this->usuarioModel->suspend($usuario_id)) {
                        $_SESSION['success'] = 'Usuario suspendido correctamente.';
                    } else {
                        $_SESSION['error'] = 'No se pudo suspender el usuario.';
                    }
                } elseif ($accion === 'eliminar') {
                    if ($this->usuarioModel->delete($usuario_id)) {
                        $_SESSION['success'] = 'Usuario eliminado correctamente.';
                    } else {
                        $_SESSION['error'] = 'No se pudo eliminar el usuario.';
                    }
                } elseif ($accion === 'ver') {
                    $usuario = $this->usuarioModel->getById($usuario_id);
                    if ($usuario) {
                        $_SESSION['usuario_view'] = $usuario;
                    } else {
                        $_SESSION['error'] = 'Usuario no encontrado.';
                    }
                }
                // Redirigir para evitar reenvío de formulario
                redirect('admin/usuarios');
            }
        }

        $tipo = $_GET['tipo'] ?? null;
        $usuarios = $this->usuarioModel->getAll($tipo);

        // Si hay usuario para ver, pásalo a la vista
        $usuario_view = $_SESSION['usuario_view'] ?? null;
        unset($_SESSION['usuario_view']);

        $data = [
            'usuarios' => $usuarios,
            'tipo' => $tipo,
            'usuario_view' => $usuario_view
        ];

        $this->view('admin/usuarios', $data);
    }

    public function reportes() {
        // Procesar acciones POST para cambiar el estado del reporte
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reporte_id = (int)($_POST['reporte_id'] ?? 0);
            $accion = $_POST['accion'] ?? '';

            if ($reporte_id > 0 && $accion === 'cambiar_estado') {
                $nuevo_estado = sanitize($_POST['nuevo_estado'] ?? 'pendiente');
                $updateQuery = "UPDATE reportes SET estado = :estado WHERE id = :id";
                $stmtUp = $this->db->prepare($updateQuery);
                $stmtUp->bindParam(':estado', $nuevo_estado);
                $stmtUp->bindParam(':id', $reporte_id);
                if ($stmtUp->execute()) {
                    $_SESSION['success'] = 'Estado del reporte actualizado correctamente.';
                } else {
                    $_SESSION['error'] = 'No se pudo actualizar el estado del reporte.';
                }
            }

            redirect('admin/reportes');
        }

        $query = "SELECT r.*, 
                         u1.nombre_completo as reportado_por_nombre,
                         u2.nombre_completo as reportado_a_nombre
                  FROM reportes r
                  INNER JOIN usuarios u1 ON r.reportado_por = u1.id
                  INNER JOIN usuarios u2 ON r.reportado_a = u2.id
                  ORDER BY r.fecha_reporte DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $reportes = $stmt->fetchAll();

        // Export CSV
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            // CSV filename
            $filename = 'reportes_' . date('Ymd_His') . '.csv';
            header('Content-Encoding: UTF-8');
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            // Output BOM for Excel
            echo "\xEF\xBB\xBF";
            $output = fopen('php://output', 'w');
            // Header row
            fputcsv($output, ['ID', 'Reportado por', 'Reportado a', 'Tipo', 'Motivo', 'Estado', 'Fecha']);

            foreach ($reportes as $r) {
                fputcsv($output, [
                    $r['id'],
                    $r['reportado_por_nombre'],
                    $r['reportado_a_nombre'],
                    $r['tipo'],
                    $r['motivo'],
                    $r['estado'],
                    $r['fecha_reporte']
                ]);
            }
            fclose($output);
            exit;
        }

        // Print view (usable to Save as PDF from browser)
        if (isset($_GET['export']) && $_GET['export'] === 'print') {
            $data = ['reportes' => $reportes];
            // Render a print-friendly view
            $this->view('admin/reportes-print', $data);
            return;
        }

        $data = ['reportes' => $reportes];
        $this->view('admin/reportes', $data);
    }

    public function reportesMensuales() {
        // Mes/Año seleccionados (opcional)
        $selectedMes = isset($_GET['mes']) ? (int)$_GET['mes'] : null;
        $selectedAno = isset($_GET['ano']) ? (int)$_GET['ano'] : null;

        // Preparar los últimos 12 meses
        $labels = [];
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $ts = strtotime("-{$i} months");
            $m = (int)date('m', $ts);
            $y = (int)date('Y', $ts);
            $labels[] = date('M Y', $ts);
            $months[] = ['mes' => $m, 'ano' => $y];
        }

        $clientesSeries = [];
        $maestrosSeries = [];
        $trabajosSeries = [];
        $busquedasSeries = [];
        $reportesSeries = [];

        // Consultas preparadas reutilizables
        $qClientes = $this->db->prepare("SELECT COUNT(*) as total FROM usuarios WHERE tipo_usuario = 'cliente' AND MONTH(fecha_registro)=:mes AND YEAR(fecha_registro)=:ano");
        $qMaestros = $this->db->prepare("SELECT COUNT(*) as total FROM usuarios WHERE tipo_usuario = 'maestro' AND MONTH(fecha_registro)=:mes AND YEAR(fecha_registro)=:ano");
        $qTrabajos = $this->db->prepare("SELECT COUNT(*) as total FROM trabajos WHERE MONTH(fecha_creacion)=:mes AND YEAR(fecha_creacion)=:ano");
        $qBusquedas = $this->db->prepare("SELECT COUNT(*) as total FROM busquedas WHERE MONTH(fecha_busqueda)=:mes AND YEAR(fecha_busqueda)=:ano");
        $qReportes = $this->db->prepare("SELECT COUNT(*) as total FROM reportes WHERE MONTH(fecha_reporte)=:mes AND YEAR(fecha_reporte)=:ano");

        foreach ($months as $mInfo) {
            $m = $mInfo['mes'];
            $y = $mInfo['ano'];

            $qClientes->execute([':mes' => $m, ':ano' => $y]);
            $r = $qClientes->fetch();
            $clientesSeries[] = (int)($r['total'] ?? 0);

            $qMaestros->execute([':mes' => $m, ':ano' => $y]);
            $r = $qMaestros->fetch();
            $maestrosSeries[] = (int)($r['total'] ?? 0);

            $qTrabajos->execute([':mes' => $m, ':ano' => $y]);
            $r = $qTrabajos->fetch();
            $trabajosSeries[] = (int)($r['total'] ?? 0);

            $qBusquedas->execute([':mes' => $m, ':ano' => $y]);
            $r = $qBusquedas->fetch();
            $busquedasSeries[] = (int)($r['total'] ?? 0);

            $qReportes->execute([':mes' => $m, ':ano' => $y]);
            $r = $qReportes->fetch();
            $reportesSeries[] = (int)($r['total'] ?? 0);
        }

        // Si el usuario pidió exportar CSV de los últimos 12 meses
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            $filename = 'reportes_mensuales_' . date('Ymd_His') . '.csv';
            header('Content-Encoding: UTF-8');
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            echo "\xEF\xBB\xBF";
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Mes', 'Clientes', 'Maestros', 'Trabajos', 'Busquedas', 'Reportes']);
            for ($i = 0; $i < count($labels); $i++) {
                fputcsv($out, [$labels[$i], $clientesSeries[$i], $maestrosSeries[$i], $trabajosSeries[$i], $busquedasSeries[$i], $reportesSeries[$i]]);
            }
            fclose($out);
            exit;
        }

        // Datos resumen para el mes seleccionado (por defecto el más reciente)
        $selectedIndex = count($labels) - 1; // último mes por defecto
        if ($selectedMes !== null && $selectedAno !== null) {
            foreach ($months as $idx => $mInfo) {
                if ($mInfo['mes'] == $selectedMes && $mInfo['ano'] == $selectedAno) {
                    $selectedIndex = $idx;
                    break;
                }
            }
        }

        $data = [
            'labels' => $labels,
            'clientes_series' => $clientesSeries,
            'maestros_series' => $maestrosSeries,
            'trabajos_series' => $trabajosSeries,
            'busquedas_series' => $busquedasSeries,
            'reportes_series' => $reportesSeries,
            'selected_label' => $labels[$selectedIndex],
            'selected_cliente' => $clientesSeries[$selectedIndex],
            'selected_maestro' => $maestrosSeries[$selectedIndex],
            'selected_trabajo' => $trabajosSeries[$selectedIndex],
            'selected_busqueda' => $busquedasSeries[$selectedIndex],
            'selected_reporte' => $reportesSeries[$selectedIndex]
        ];

        $this->view('admin/reportes-mensuales', $data);
    }

    private function getUsersByMonth($tipo, $mes, $ano) {
        $query = "SELECT COUNT(*) as total FROM usuarios 
                  WHERE tipo_usuario = :tipo 
                  AND MONTH(fecha_registro) = :mes 
                  AND YEAR(fecha_registro) = :ano";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':mes', $mes);
        $stmt->bindParam(':ano', $ano);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['total'];
    }

    public function perfil() {
        $usuario = $this->usuarioModel->getById($_SESSION['usuario_id']);
        $this->view('admin/perfil', ['usuario' => $usuario]);
    }

    public function actualizarPassword() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/perfil');
        }

        $password_actual = $_POST['password_actual'] ?? '';
        $password_nueva = $_POST['password_nueva'] ?? '';
        $password_confirmar = $_POST['password_confirmar'] ?? '';

        if (empty($password_actual) || empty($password_nueva) || empty($password_confirmar)) {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            redirect('admin/perfil');
        }

        if ($password_nueva !== $password_confirmar) {
            $_SESSION['error'] = 'Las contraseñas nuevas no coinciden';
            redirect('admin/perfil');
        }

        if (strlen($password_nueva) < 6) {
            $_SESSION['error'] = 'La contraseña nueva debe tener al menos 6 caracteres';
            redirect('admin/perfil');
        }

        $usuario = $this->usuarioModel->getById($_SESSION['usuario_id']);
        
        if (!password_verify($password_actual, $usuario['password'])) {
            $_SESSION['error'] = 'La contraseña actual es incorrecta';
            redirect('admin/perfil');
        }

        if ($this->usuarioModel->updatePassword($_SESSION['usuario_id'], $password_nueva)) {
            $_SESSION['success'] = 'Contraseña actualizada correctamente';
        } else {
            $_SESSION['error'] = 'Error al actualizar la contraseña';
        }

        redirect('admin/perfil');
    }
}

