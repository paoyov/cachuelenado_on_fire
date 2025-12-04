<?php
/**
 * Servicio de Email
 * Nota: Requiere configuración SMTP en config/config.php
 */

class EmailService {
    private $smtp_host;
    private $smtp_port;
    private $smtp_user;
    private $smtp_pass;
    private $from_email;
    private $from_name;

    public function __construct() {
        $this->smtp_host = SMTP_HOST;
        $this->smtp_port = SMTP_PORT;
        $this->smtp_user = SMTP_USER;
        $this->smtp_pass = SMTP_PASS;
        $this->from_email = SMTP_FROM_EMAIL;
        $this->from_name = SMTP_FROM_NAME;
    }

    /**
     * Enviar email usando mail() de PHP
     * Para producción, se recomienda usar PHPMailer o similar
     */
    public function send($to, $subject, $message, $html = true) {
        $headers = [];
        $headers[] = "From: {$this->from_name} <{$this->from_email}>";
        $headers[] = "Reply-To: {$this->from_email}";
        $headers[] = "X-Mailer: PHP/" . phpversion();
        
        if ($html) {
            $headers[] = "MIME-Version: 1.0";
            $headers[] = "Content-type: text/html; charset=UTF-8";
        }

        $headers_string = implode("\r\n", $headers);

        return mail($to, $subject, $message, $headers_string);
    }

    /**
     * Enviar notificación de validación de perfil
     */
    public function sendValidationNotification($to, $nombre, $validado = true, $motivo = null) {
        $subject = $validado 
            ? 'Tu perfil ha sido validado - Cachueleando On Fire'
            : 'Tu perfil ha sido rechazado - Cachueleando On Fire';
        
        $message = $this->getValidationEmailTemplate($nombre, $validado, $motivo);
        
        return $this->send($to, $subject, $message);
    }

    /**
     * Template de email para validación
     */
    private function getValidationEmailTemplate($nombre, $validado, $motivo) {
        if ($validado) {
            return "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #ff6b35; color: white; padding: 20px; text-align: center; }
                    .content { padding: 20px; background: #f9f9f9; }
                    .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Cachueleando On Fire</h1>
                    </div>
                    <div class='content'>
                        <h2>¡Felicidades, {$nombre}!</h2>
                        <p>Tu perfil de maestro ha sido <strong>validado</strong> exitosamente.</p>
                        <p>Ahora tu perfil es visible para todos los clientes en la plataforma.</p>
                        <p><a href='" . BASE_URL . "maestro/dashboard' style='background: #ff6b35; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Acceder a mi Panel</a></p>
                    </div>
                    <div class='footer'>
                        <p>&copy; " . date('Y') . " Cachueleando On Fire. Todos los derechos reservados.</p>
                    </div>
                </div>
            </body>
            </html>
            ";
        } else {
            return "
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset='UTF-8'>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: #dc3545; color: white; padding: 20px; text-align: center; }
                    .content { padding: 20px; background: #f9f9f9; }
                    .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Cachueleando On Fire</h1>
                    </div>
                    <div class='content'>
                        <h2>Hola, {$nombre}</h2>
                        <p>Lamentamos informarte que tu perfil ha sido <strong>rechazado</strong>.</p>
                        " . ($motivo ? "<p><strong>Motivo:</strong> {$motivo}</p>" : "") . "
                        <p>Por favor, revisa la información de tu perfil y los documentos subidos, y vuelve a intentarlo.</p>
                        <p><a href='" . BASE_URL . "maestro/perfil-editar' style='background: #ff6b35; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;'>Editar mi Perfil</a></p>
                    </div>
                    <div class='footer'>
                        <p>&copy; " . date('Y') . " Cachueleando On Fire. Todos los derechos reservados.</p>
                    </div>
                </div>
            </body>
            </html>
            ";
        }
    }
}

