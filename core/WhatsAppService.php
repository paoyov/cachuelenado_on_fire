<?php
/**
 * Servicio de WhatsApp
 * Nota: Requiere integración con API de WhatsApp (Twilio, WhatsApp Business API, etc.)
 * Este es un ejemplo básico que necesita ser implementado según la API elegida
 */

class WhatsAppService {
    private $api_key;
    private $api_url;

    public function __construct() {
        $this->api_key = WHATSAPP_API_KEY;
        $this->api_url = WHATSAPP_API_URL;
    }

    /**
     * Enviar mensaje de WhatsApp
     * NOTA: Esta es una implementación de ejemplo
     * Para producción, usar una API real como Twilio, WhatsApp Business API, etc.
     */
    public function send($telefono, $mensaje) {
        // Formatear número de teléfono (agregar código de país si es necesario)
        $telefono = $this->formatPhoneNumber($telefono);
        
        // Construir URL de WhatsApp Web (solución temporal)
        // En producción, usar API real
        $url = "https://wa.me/{$telefono}?text=" . urlencode($mensaje);
        
        // Por ahora, solo retornamos la URL
        // En producción, hacer la llamada real a la API
        return [
            'success' => true,
            'url' => $url,
            'message' => 'Redirigir a WhatsApp para enviar mensaje'
        ];
        
        /* Ejemplo de implementación con API real:
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'to' => $telefono,
            'message' => $mensaje
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->api_key,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $httpCode === 200;
        */
    }

    /**
     * Enviar notificación de validación
     */
    public function sendValidationNotification($telefono, $nombre, $validado = true, $motivo = null) {
        if ($validado) {
            $mensaje = "¡Hola {$nombre}! Tu perfil en Cachueleando On Fire ha sido VALIDADO. Ya es visible para los clientes. Accede a: " . BASE_URL;
        } else {
            $mensaje = "Hola {$nombre}. Tu perfil fue RECHAZADO. " . ($motivo ? "Motivo: {$motivo}" : "") . " Revisa tu perfil en: " . BASE_URL;
        }
        
        return $this->send($telefono, $mensaje);
    }

    /**
     * Formatear número de teléfono
     */
    private function formatPhoneNumber($telefono) {
        // Remover caracteres no numéricos
        $telefono = preg_replace('/[^0-9]/', '', $telefono);
        
        // Si no tiene código de país, agregar código de Perú (51)
        if (strlen($telefono) === 9) {
            $telefono = '51' . $telefono;
        }
        
        return $telefono;
    }
}

