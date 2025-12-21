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
        require_once 'models/PagoMaestro.php';
        $pagoModel = new PagoMaestro($this->db);
        
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

        // Agregar información de pago a cada maestro
        foreach ($maestros as &$maestro) {
            $pago = $pagoModel->getPagoParaValidar($maestro['id']);
            $maestro['pago'] = $pago;
        }

        $data = [
            'maestros' => $maestros,
            'estado' => $estado
        ];

        $this->view('admin/maestros', $data);
    }

    public function pagos() {
        require_once 'models/PagoMaestro.php';
        $pagoModel = new PagoMaestro($this->db);
        
        $estado = $_GET['estado'] ?? 'pendiente';
        
        if ($estado === 'pendiente') {
            $pagos = $pagoModel->getPendientes();
        } else {
            $query = "SELECT p.*, m.usuario_id, u.nombre_completo, u.email, u.telefono,
                             admin.nombre_completo as admin_nombre
                      FROM pagos_maestros p
                      INNER JOIN maestros m ON p.maestro_id = m.id
                      INNER JOIN usuarios u ON p.usuario_id = u.id
                      LEFT JOIN usuarios admin ON p.verificado_por = admin.id
                      WHERE p.estado = :estado
                      ORDER BY p.fecha_pago DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':estado', $estado);
            $stmt->execute();
            $pagos = $stmt->fetchAll();
        }

        $data = [
            'pagos' => $pagos,
            'estado' => $estado,
            'db' => $this->db
        ];

        $this->view('admin/pagos', $data);
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

        // Verificar que el maestro tenga un pago (pendiente o verificado) antes de validar
        if ($accion === 'validar') {
            require_once 'models/PagoMaestro.php';
            $pagoModel = new PagoMaestro($this->db);
            $pago = $pagoModel->getPagoParaValidar($maestro_id);
            
            if (!$pago) {
                $_SESSION['error'] = 'No se puede validar el perfil. El maestro debe haber realizado un pago primero.';
                redirect('admin/maestros');
            }
            
            // Si el pago está pendiente, verificarlo automáticamente al validar el perfil
            if ($pago['estado'] === 'pendiente') {
                $pagoModel->verificar($pago['id'], $_SESSION['usuario_id'], 'Pago verificado automáticamente al validar el perfil');
            }
        }

        $estado = $accion === 'validar' ? 'validado' : 'rechazado';
        
        if ($this->maestroModel->validate($maestro_id, $estado, $_SESSION['usuario_id'], $motivo_rechazo)) {
            // Obtener información del maestro para notificación
            $maestro = $this->maestroModel->getById($maestro_id);
            $usuario = $this->usuarioModel->getById($maestro['usuario_id']);
            
            // Si se rechaza el maestro, crear un reporte en la tabla reportes
            if ($accion === 'rechazar' && $motivo_rechazo) {
                require_once 'models/Reporte.php';
                $reporteModel = new Reporte($this->db);
                $reporteModel->create([
                    'reportado_por' => $_SESSION['usuario_id'], // Admin que rechaza
                    'reportado_a' => $maestro['usuario_id'],   // Usuario maestro rechazado
                    'tipo' => 'usuario',
                    'motivo' => $motivo_rechazo,
                    'estado' => 'resuelto' // Los rechazos se consideran resueltos
                ]);
            }
            
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
            if ($usuario_id > 0 && in_array($accion, ['ver', 'suspender', 'activar', 'eliminar'])) {
                if ($accion === 'suspender') {
                    if ($this->usuarioModel->suspend($usuario_id)) {
                        $_SESSION['success'] = 'Usuario suspendido correctamente.';
                    } else {
                        $_SESSION['error'] = 'No se pudo suspender el usuario.';
                    }
                } elseif ($accion === 'activar') {
                    if ($this->usuarioModel->activate($usuario_id)) {
                        $_SESSION['success'] = 'Usuario activado correctamente.';
                    } else {
                        $_SESSION['error'] = 'No se pudo activar el usuario.';
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
        // Obtener reportes de maestros rechazados desde la tabla reportes
        require_once 'models/Reporte.php';
        $reporteModel = new Reporte($this->db);
        $reportes = $reporteModel->getMaestrosRechazados();

        // Export CSV
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            $filename = 'reportes_maestros_rechazados_' . date('Ymd_His') . '.csv';
            header('Content-Encoding: UTF-8');
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');

            echo "\xEF\xBB\xBF";
            $output = fopen('php://output', 'w');
            fputcsv($output, ['ID Maestro', 'Nombre del Usuario', 'Email', 'Motivo de Rechazo', 'Estado', 'Fecha de Rechazo', 'Fecha de Registro']);

            foreach ($reportes as $r) {
                fputcsv($output, [
                    $r['maestro_id'],
                    $r['nombre_completo'],
                    $r['email'],
                    $r['motivo'] ?? $r['motivo_rechazo'] ?? 'No especificado',
                    ucfirst($r['estado_perfil'] ?? 'rechazado'),
                    $r['fecha_reporte'] ? date('d/m/Y H:i', strtotime($r['fecha_reporte'])) : ($r['fecha_validacion'] ? date('d/m/Y H:i', strtotime($r['fecha_validacion'])) : 'N/A'),
                    date('d/m/Y', strtotime($r['fecha_registro']))
                ]);
            }
            fclose($output);
            exit;
        }

        // Print view
        if (isset($_GET['export']) && $_GET['export'] === 'print') {
            $data = ['reportes' => $reportes];
            $this->view('admin/reportes-print', $data);
            return;
        }

        $data = ['reportes' => $reportes];
        $this->view('admin/reportes', $data);
    }

    private function getMesEspanol($mes, $ano) {
        $meses = [
            'Jan' => 'Ene', 'Feb' => 'Feb', 'Mar' => 'Mar',
            'Apr' => 'Abr', 'May' => 'May', 'Jun' => 'Jun',
            'Jul' => 'Jul', 'Aug' => 'Ago', 'Sep' => 'Sep',
            'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Dic'
        ];
        
        $mesIngles = date('M', mktime(0, 0, 0, $mes, 1, $ano));
        $mesEspanol = $meses[$mesIngles] ?? $mesIngles;
        
        return $mesEspanol . ' ' . $ano;
    }

    public function reportesMensuales() {
        // Filtro de fechas (opcional)
        $fechaDesde = isset($_GET['fecha_desde']) && !empty($_GET['fecha_desde']) ? $_GET['fecha_desde'] : null;
        $fechaHasta = isset($_GET['fecha_hasta']) && !empty($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : null;
        $usarFiltro = $fechaDesde !== null && $fechaHasta !== null;

        // Validar que fecha_desde <= fecha_hasta
        if ($usarFiltro && strtotime($fechaDesde) > strtotime($fechaHasta)) {
            $_SESSION['error'] = 'La fecha inicial no puede ser mayor a la fecha final.';
            $usarFiltro = false;
        }

        $labels = [];
        $months = [];
        $clientesSeries = [];
        $maestrosSeries = [];
        $trabajosSeries = [];
        $busquedasSeries = [];
        $reportesSeries = [];

        if ($usarFiltro) {
            // Filtrar por rango de fechas
            $desde = date('Y-m-d 00:00:00', strtotime($fechaDesde));
            $hasta = date('Y-m-d 23:59:59', strtotime($fechaHasta));

            // Generar array de meses entre las fechas
            $start = new DateTime($fechaDesde);
            $end = new DateTime($fechaHasta);
            
            // Calcular la diferencia en meses
            $diffMonths = (int)$start->diff($end)->format('%m') + ($start->format('Y') != $end->format('Y') ? ($end->format('Y') - $start->format('Y')) * 12 : 0);
            
            // Si el rango es menor a un mes, mostrar solo un período
            if ($diffMonths == 0 || ($start->format('Y-m') == $end->format('Y-m'))) {
                $m = (int)$start->format('m');
                $y = (int)$start->format('Y');
                $labels[] = $this->getMesEspanol($m, $y);
                $months[] = ['mes' => $m, 'ano' => $y, 'inicio' => $desde, 'fin' => $hasta];
            } else {
                // Generar períodos mensuales
                $current = clone $start;
                $current->modify('first day of this month');
                
                while ($current <= $end) {
                    $m = (int)$current->format('m');
                    $y = (int)$current->format('Y');
                    
                    // Determinar inicio y fin del período
                    $periodStart = ($current->format('Y-m') == $start->format('Y-m')) ? $desde : $current->format('Y-m-01 00:00:00');
                    $periodEnd = ($current->format('Y-m') == $end->format('Y-m')) ? $hasta : $current->format('Y-m-t 23:59:59');
                    
                    $labels[] = $this->getMesEspanol($m, $y);
                    $months[] = ['mes' => $m, 'ano' => $y, 'inicio' => $periodStart, 'fin' => $periodEnd];
                    
                    $current->modify('+1 month');
                }
            }

            // Consultas con filtro de rango de fechas
            foreach ($months as $mInfo) {
                $inicio = $mInfo['inicio'];
                $fin = $mInfo['fin'];

                // Clientes
                $qClientes = $this->db->prepare("SELECT COUNT(*) as total FROM usuarios WHERE tipo_usuario = 'cliente' AND fecha_registro >= :inicio AND fecha_registro <= :fin");
                $qClientes->execute([':inicio' => $inicio, ':fin' => $fin]);
                $r = $qClientes->fetch();
                $clientesSeries[] = (int)($r['total'] ?? 0);

                // Maestros
                $qMaestros = $this->db->prepare("SELECT COUNT(*) as total FROM usuarios WHERE tipo_usuario = 'maestro' AND fecha_registro >= :inicio AND fecha_registro <= :fin");
                $qMaestros->execute([':inicio' => $inicio, ':fin' => $fin]);
                $r = $qMaestros->fetch();
                $maestrosSeries[] = (int)($r['total'] ?? 0);

                // Trabajos
                $qTrabajos = $this->db->prepare("SELECT COUNT(*) as total FROM trabajos WHERE fecha_creacion >= :inicio AND fecha_creacion <= :fin");
                $qTrabajos->execute([':inicio' => $inicio, ':fin' => $fin]);
                $r = $qTrabajos->fetch();
                $trabajosSeries[] = (int)($r['total'] ?? 0);

                // Búsquedas
                $qBusquedas = $this->db->prepare("SELECT COUNT(*) as total FROM busquedas WHERE fecha_busqueda >= :inicio AND fecha_busqueda <= :fin");
                $qBusquedas->execute([':inicio' => $inicio, ':fin' => $fin]);
                $r = $qBusquedas->fetch();
                $busquedasSeries[] = (int)($r['total'] ?? 0);

                // Reportes
                $qReportes = $this->db->prepare("SELECT COUNT(*) as total FROM reportes WHERE fecha_reporte >= :inicio AND fecha_reporte <= :fin");
                $qReportes->execute([':inicio' => $inicio, ':fin' => $fin]);
                $r = $qReportes->fetch();
                $reportesSeries[] = (int)($r['total'] ?? 0);
            }
        } else {
            // Comportamiento por defecto: últimos 12 meses
            for ($i = 11; $i >= 0; $i--) {
                $ts = strtotime("-{$i} months");
                $m = (int)date('m', $ts);
                $y = (int)date('Y', $ts);
                $labels[] = $this->getMesEspanol($m, $y);
                $months[] = ['mes' => $m, 'ano' => $y];
            }

            // Consultas preparadas reutilizables (por mes/año)
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
        }

        // Exportar CSV
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            if ($usarFiltro) {
                $filename = 'Reportes_Mensuales_' . date('d-m-Y', strtotime($fechaDesde)) . '_al_' . date('d-m-Y', strtotime($fechaHasta)) . '.csv';
            } else {
                $filename = 'Reportes_Mensuales_' . date('d-m-Y_His') . '.csv';
            }
            
            // Headers para Excel en español (usar punto y coma como separador)
            header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            
            // BOM UTF-8 para Excel
            echo "\xEF\xBB\xBF";
            
            // Headers en español (eliminado Trabajos)
            echo "Mes;Clientes;Maestros;Búsquedas;Reportes\n";
            
            // Escribir datos (usar punto y coma como separador para compatibilidad con Excel en español)
            for ($i = 0; $i < count($labels); $i++) {
                $mes = str_replace(',', '', $labels[$i]); // Limpiar comas del mes
                $clientes = (int)($clientesSeries[$i] ?? 0);
                $maestros = (int)($maestrosSeries[$i] ?? 0);
                $busquedas = (int)($busquedasSeries[$i] ?? 0);
                $reportes = (int)($reportesSeries[$i] ?? 0);
                
                // Usar punto y coma como separador y escapar valores si contienen punto y coma
                echo '"' . str_replace('"', '""', $mes) . '";';
                echo $clientes . ';';
                echo $maestros . ';';
                echo $busquedas . ';';
                echo $reportes . "\n";
            }
            exit;
        }

        // Datos resumen para el mes seleccionado (por defecto el más reciente)
        $selectedIndex = count($labels) > 0 ? count($labels) - 1 : 0;

        $data = [
            'labels' => $labels,
            'clientes_series' => $clientesSeries,
            'maestros_series' => $maestrosSeries,
            'trabajos_series' => $trabajosSeries,
            'busquedas_series' => $busquedasSeries,
            'reportes_series' => $reportesSeries,
            'selected_label' => count($labels) > 0 ? $labels[$selectedIndex] : $this->getMesEspanol((int)date('m'), (int)date('Y')),
            'selected_cliente' => count($clientesSeries) > 0 ? $clientesSeries[$selectedIndex] : 0,
            'selected_maestro' => count($maestrosSeries) > 0 ? $maestrosSeries[$selectedIndex] : 0,
            'selected_trabajo' => count($trabajosSeries) > 0 ? $trabajosSeries[$selectedIndex] : 0,
            'selected_busqueda' => count($busquedasSeries) > 0 ? $busquedasSeries[$selectedIndex] : 0,
            'selected_reporte' => count($reportesSeries) > 0 ? $reportesSeries[$selectedIndex] : 0,
            'fecha_desde' => $fechaDesde,
            'fecha_hasta' => $fechaHasta,
            'usar_filtro' => $usarFiltro
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
                    // Guardar la ruta relativa en sesión
                    $_SESSION['foto_perfil'] = $upload['path'];
                }
            }

            if ($this->usuarioModel->update($_SESSION['usuario_id'], $data)) {
                // Actualizar sesión con el nuevo nombre si fue cambiado
                if (isset($data['nombre_completo'])) {
                    $_SESSION['nombre_completo'] = $data['nombre_completo'];
                }

                $_SESSION['success'] = 'Perfil actualizado correctamente';
                redirect('admin/perfil');
            } else {
                $_SESSION['error'] = 'Error al actualizar el perfil';
            }
        }

        $data = ['usuario' => $usuario];
        $this->view('admin/perfil', $data);
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
    public function getMaestroDetails() {
        if (!isset($_GET['id'])) {
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }

        $maestro_id = (int)$_GET['id'];
        $maestro = $this->maestroModel->getById($maestro_id);

        if (!$maestro) {
            echo json_encode(['error' => 'Maestro no encontrado']);
            return;
        }

        $usuario = $this->usuarioModel->getById($maestro['usuario_id']);
        $especialidades = $this->maestroModel->getEspecialidades($maestro_id);
        
        $documentoModel = new DocumentoMaestro($this->db);
        $documentos = $documentoModel->getByMaestro($maestro_id);
        
        // Debug: Log the query
        error_log("Fetching documents for maestro_id: " . $maestro_id);
        error_log("Documents found: " . count($documentos));
        error_log("Documents data: " . print_r($documentos, true));

        $data = [
            'maestro' => $maestro,
            'usuario' => $usuario,
            'especialidades' => $especialidades,
            'documentos' => $documentos,
            'debug' => [
                'maestro_id' => $maestro_id,
                'document_count' => count($documentos)
            ]
        ];

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function getMaestroPago() {
        if (!isset($_GET['id'])) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'ID no proporcionado']);
            return;
        }

        require_once 'models/PagoMaestro.php';
        $pagoModel = new PagoMaestro($this->db);
        
        $maestro_id = (int)$_GET['id'];
        $pago = $pagoModel->getPagoParaValidar($maestro_id);

        if (!$pago) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No se encontró pago para este maestro']);
            return;
        }

        header('Content-Type: application/json');
        echo json_encode(['pago' => $pago]);
    }
}

