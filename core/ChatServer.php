<?php
namespace Core;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

require_once __DIR__ . '/../config/database.php';

class ChatServer implements MessageComponentInterface {
    protected $clients;
    // Map "type_id" to connection
    protected $users = [];

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        // no user id yet; client should send a register message
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);
        if (!$data) return;

        if (isset($data['action']) && $data['action'] === 'register') {
            $userId = (int)($data['user_id'] ?? 0);
            $userType = $data['user_type'] ?? 'cliente'; // Default to cliente if not set, but should be set
            
            if ($userId) {
                $key = $userType . '_' . $userId;
                $this->users[$key] = $from;
                $from->userKey = $key;
                // echo "Registered: $key\n";
            }
            return;
        }

        if (isset($data['action']) && $data['action'] === 'send_message') {
            // Expected payload: sender_id, sender_type, receiver_id, mensaje, tipo, adjunto (optional)
            $senderId = (int)$data['sender_id'];
            $senderType = $data['sender_type'] ?? 'cliente';
            $receiverId = (int)$data['receiver_id'];
            $mensaje = $data['mensaje'] ?? null;
            $tipo = $data['tipo'] ?? 'texto';
            $adjunto = $data['adjunto'] ?? null; // path relative under uploads/

            // persist to DB
            try {
                $db = (new \Database())->getConnection();
                $stmt = $db->prepare("INSERT INTO mensajes (cliente_id, maestro_id, enviado_por, mensaje, adjunto, tipo, fecha_envio, leido) VALUES (:cliente_id, :maestro_id, :enviado_por, :mensaje, :adjunto, :tipo, NOW(), 0)");
                
                if ($senderType === 'cliente') {
                    $cliente_id = $senderId; 
                    $maestro_id = $receiverId; 
                    $enviado_por = 'cliente';
                    $targetKey = 'maestro_' . $maestro_id;
                } else {
                    $cliente_id = $receiverId; 
                    $maestro_id = $senderId; 
                    $enviado_por = 'maestro';
                    $targetKey = 'cliente_' . $cliente_id;
                }

                $stmt->bindValue(':cliente_id', $cliente_id ?: null, \PDO::PARAM_INT);
                $stmt->bindValue(':maestro_id', $maestro_id ?: null, \PDO::PARAM_INT);
                $stmt->bindValue(':enviado_por', $enviado_por);
                $stmt->bindValue(':mensaje', $mensaje);
                $stmt->bindValue(':adjunto', $adjunto);
                $stmt->bindValue(':tipo', $tipo);
                $stmt->execute();
                $insertId = (int)$db->lastInsertId();

                // Build message payload to broadcast
                $out = [
                    'action' => 'new_message',
                    'mensaje' => [
                        'id' => $insertId,
                        'cliente_id' => $cliente_id,
                        'maestro_id' => $maestro_id,
                        'enviado_por' => $enviado_por,
                        'mensaje' => $mensaje,
                        'adjunto' => $adjunto,
                        'tipo' => $tipo,
                        'fecha_envio' => date('Y-m-d H:i:s')
                    ]
                ];

                $payload = json_encode($out);

                // send to receiver if connected
                if (isset($this->users[$targetKey])) {
                    $this->users[$targetKey]->send($payload);
                }

                // also send to sender connection to update UI (confirmation)
                $from->send($payload);

            } catch (\Exception $e) {
                $from->send(json_encode(['action'=>'error','message'=>$e->getMessage()]));
                echo "Error: " . $e->getMessage() . "\n";
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        if (isset($conn->userKey)) {
            unset($this->users[$conn->userKey]);
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}
