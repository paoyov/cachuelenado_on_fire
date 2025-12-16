<?php
/**
 * Controlador de Pagos
 * Maneja los pagos de maestros para validación
 */

class PagoController extends Controller {
    private $pagoModel;
    private $maestroModel;
    private $notificacionModel;

    public function __construct() {
        parent::__construct();
        require_once 'models/PagoMaestro.php';
        $this->pagoModel = new PagoMaestro($this->db);
        $this->maestroModel = new Maestro($this->db);
        $this->notificacionModel = new Notificacion($this->db);
    }

    /**
     * Procesar pago de maestro
     */
    public function procesarPago() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('maestro/dashboard');
        }

        if (!isLoggedIn() || !isMaestro()) {
            $_SESSION['error'] = 'No autorizado';
            redirect('home');
        }

        $maestro = $this->maestroModel->getByUsuarioId($_SESSION['usuario_id']);
        if (!$maestro) {
            $_SESSION['error'] = 'Perfil de maestro no encontrado';
            redirect('maestro/dashboard');
        }

        $numero_comprobante = sanitize($_POST['numero_comprobante'] ?? '');
        $comprobante_imagen = null;

        // Subir imagen del comprobante si existe
        if (isset($_FILES['comprobante_imagen']) && $_FILES['comprobante_imagen']['error'] === UPLOAD_ERR_OK) {
            $upload = $this->uploadFile($_FILES['comprobante_imagen'], 'comprobantes', ALLOWED_IMAGE_TYPES);
            if ($upload['success']) {
                $comprobante_imagen = $upload['path'];
            }
        }

        $data = [
            'monto' => 3.00,
            'metodo_pago' => 'yape',
            'numero_comprobante' => $numero_comprobante,
            'comprobante_imagen' => $comprobante_imagen,
            'estado' => 'pendiente'
        ];

        $pago_id = $this->pagoModel->create($maestro['id'], $_SESSION['usuario_id'], $data);

        if ($pago_id) {
            // Notificar a todos los administradores
            $this->notificarAdministradores($maestro, $pago_id);
            
            // Eliminar flag de mostrar modal
            unset($_SESSION['mostrar_modal_pago']);
            
            $_SESSION['success'] = 'Pago registrado exitosamente. Será verificado por un administrador en breve.';
        } else {
            $_SESSION['error'] = 'Error al registrar el pago. Por favor, intente nuevamente.';
        }

        redirect('maestro/dashboard');
    }

    /**
     * Notificar a todos los administradores sobre un nuevo pago
     */
    private function notificarAdministradores($maestro, $pago_id) {
        $query = "SELECT id FROM usuarios WHERE tipo_usuario = 'administrador'";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $admins = $stmt->fetchAll();

        foreach ($admins as $admin) {
            $titulo = 'Nuevo Pago de Maestro';
            $mensaje = "El maestro {$maestro['nombre_completo']} ha realizado un pago de S/ 3.00. Por favor, verifica el pago para validar su perfil.";
            
            $this->notificacionModel->create(
                $admin['id'],
                'sistema',
                $titulo,
                $mensaje
            );
        }
    }

    /**
     * Verificar pago (solo administradores)
     */
    public function verificarPago() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/maestros');
        }

        if (!isAdmin()) {
            $_SESSION['error'] = 'No autorizado';
            redirect('home');
        }

        $pago_id = (int)($_POST['pago_id'] ?? 0);
        $accion = sanitize($_POST['accion'] ?? '');
        $observaciones = sanitize($_POST['observaciones'] ?? null);

        if (!in_array($accion, ['verificar', 'rechazar'])) {
            $_SESSION['error'] = 'Acción inválida';
            redirect('admin/maestros');
        }

        $pago = $this->pagoModel->getById($pago_id);
        if (!$pago) {
            $_SESSION['error'] = 'Pago no encontrado';
            redirect('admin/maestros');
        }

        if ($accion === 'verificar') {
            if ($this->pagoModel->verificar($pago_id, $_SESSION['usuario_id'], $observaciones)) {
                // Notificar al maestro
                $titulo = 'Pago Verificado';
                $mensaje = 'Su pago ha sido verificado exitosamente. Su perfil está activo por 24 horas.';
                $this->notificacionModel->create($pago['usuario_id'], 'sistema', $titulo, $mensaje);
                
                $_SESSION['success'] = 'Pago verificado exitosamente';
            } else {
                $_SESSION['error'] = 'Error al verificar el pago';
            }
        } else {
            if ($this->pagoModel->rechazar($pago_id, $_SESSION['usuario_id'], $observaciones)) {
                // Notificar al maestro
                $titulo = 'Pago Rechazado';
                $mensaje = 'Su pago ha sido rechazado. Motivo: ' . ($observaciones ?? 'No especificado');
                $this->notificacionModel->create($pago['usuario_id'], 'sistema', $titulo, $mensaje);
                
                $_SESSION['success'] = 'Pago rechazado';
            } else {
                $_SESSION['error'] = 'Error al rechazar el pago';
            }
        }

        redirect('admin/pagos');
    }

    /**
     * Cerrar modal de pago
     */
    public function cerrarModal() {
        unset($_SESSION['mostrar_modal_pago']);
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }

    /**
     * Verificar pagos expirados (tarea programada)
     */
    public function verificarExpirados() {
        $this->pagoModel->verificarPagosExpirados();
        
        // Notificar a maestros con pagos expirados
        $expirados = $this->pagoModel->getPagosExpirados();
        foreach ($expirados as $maestro) {
            $titulo = 'Pago Expirado';
            $mensaje = 'Su pago ha expirado. Por favor, realice un nuevo pago para mantener su perfil activo.';
            $this->notificacionModel->create($maestro['usuario_id'], 'sistema', $titulo, $mensaje);
        }

        // Notificar a maestros con pagos próximos a expirar
        $porExpirar = $this->pagoModel->getPagosPorExpirar();
        foreach ($porExpirar as $maestro) {
            $titulo = 'Pago Próximo a Expirar';
            $mensaje = 'Su pago expirará pronto. Realice un nuevo pago para mantener su perfil activo.';
            $this->notificacionModel->create($maestro['usuario_id'], 'sistema', $titulo, $mensaje);
        }
    }
}
