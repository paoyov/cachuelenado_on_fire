<?php
$title = 'Mensajes';
?>

<div class="container">
    <h1 style="margin-bottom:1.5rem">Mensajes</h1>

        <div class="chat-layout">
            <aside class="chat-left">
                <div class="left-header">
                    <h5>Conversaciones</h5>
                </div>
                <div class="conversations">
                    <?php if (!empty($conversaciones)): ?>
                        <?php foreach ($conversaciones as $c): ?>
                            <a class="conv-item" href="<?php echo BASE_URL; ?>cliente/mensajes?maestro_id=<?php echo $c['maestro_id']; ?>">
                                <div class="conv-avatar"><img src="<?php echo UPLOAD_URL . ($c['foto_perfil'] ?: 'perfiles/default.png'); ?>" alt=""/></div>
                                <div class="conv-meta">
                                    <strong><?php echo htmlspecialchars($c['nombre_completo']); ?></strong>
                                    <?php if (!empty($c['ultimo_mensaje'])): ?>
                                        <div class="conv-last"><?php echo htmlspecialchars(mb_strimwidth($c['ultimo_mensaje'], 0, 40, '...')); ?></div>
                                    <?php endif; ?>
                                </div>
                                <?php if (!empty($c['no_leidos'])): ?> <span class="conv-badge"><?php echo $c['no_leidos']; ?></span><?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="muted">No hay conversaciones.</p>
                    <?php endif; ?>
                </div>
            </aside>

            <main class="chat-main">
                <div class="chat-top">
                    <?php if (!empty($maestro)): ?>
                        <div class="maestro-info">
                            <img src="<?php echo UPLOAD_URL . ($maestro['foto_perfil'] ?: 'perfiles/default.png'); ?>" alt="" class="maestro-thumb"/>
                            <div>
                                <strong><?php echo htmlspecialchars($maestro['nombre_completo'] ?? 'Sin seleccionar'); ?></strong>
                                <div class="maestro-sub"><?php echo htmlspecialchars($maestro['chapa'] ?? ''); ?></div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="maestro-info"><strong>Selecciona una conversación</strong></div>
                    <?php endif; ?>
                </div>

                <div class="chat-window">
                    <div class="chat-box" id="chat-box">
                        <?php if (!empty($mensajes)): ?>
                            <?php foreach ($mensajes as $m): ?>
                                <div class="chat-message <?php echo $m['enviado_por'] === 'cliente' ? 'me' : 'other'; ?>" data-id="<?php echo $m['id']; ?>">
                                    <?php if ($m['enviado_por'] !== 'cliente'): ?><div class="msg-avatar"><img src="<?php echo UPLOAD_URL . ($maestro['foto_perfil'] ?? 'perfiles/default.png'); ?>"/></div><?php endif; ?>
                                    <div class="msg-body">
                                        <?php if (!empty($m['adjunto'])): ?>
                                            <?php if ($m['tipo'] === 'imagen' || $m['tipo'] === 'sticker'): ?>
                                                <img src="<?php echo UPLOAD_URL . $m['adjunto']; ?>" class="msg-attach" />
                                            <?php elseif ($m['tipo'] === 'video'): ?>
                                                <video controls class="msg-attach"><source src="<?php echo UPLOAD_URL . $m['adjunto']; ?>"></video>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if (!empty($m['mensaje'])): ?><div class="msg-text"><?php echo nl2br(htmlspecialchars($m['mensaje'])); ?></div><?php endif; ?>
                                        <small class="msg-time"><?php echo htmlspecialchars($m['fecha_envio']); ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-chat">No hay mensajes aún. Envía el primero usando el formulario abajo.</div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="chat-input">
                    <div id="file-preview" class="file-preview" style="display:none;">
                        <img id="preview-img" src="" alt="Preview" />
                        <button type="button" id="remove-preview">×</button>
                    </div>
                    <form id="sendForm" enctype="multipart/form-data">
                        <?php if (!empty($maestro)): ?>
                            <input type="hidden" name="maestro_id" value="<?php echo $maestro['id']; ?>">
                        <?php endif; ?>
                        <div class="input-row">
                            <textarea name="mensaje" id="mensaje" placeholder="<?php echo !empty($maestro) ? 'Escribe un mensaje...' : 'Selecciona una conversación para escribir...'; ?>" <?php echo empty($maestro) ? 'disabled' : ''; ?>></textarea>
                            <div class="input-actions">
                                <label class="attach-btn">
                                    <input type="file" name="adjunto" id="adjunto" accept="image/*,video/*" <?php echo empty($maestro) ? 'disabled' : ''; ?> />
                                    📎
                                </label>
                                <button type="button" id="sendBtn" class="send-btn" <?php echo empty($maestro) ? 'disabled' : ''; ?>>➤</button>
                            </div>
                        </div>
                    </form>
                </div>
            </main>

            <aside class="chat-right">
                <?php if (!empty($maestro)): ?>
                    <div class="profile-card">
                        <img src="<?php echo UPLOAD_URL . ($maestro['foto_perfil'] ?: 'perfiles/default.png'); ?>" class="profile-pic" />
                        <h4><?php echo htmlspecialchars($maestro['nombre_completo']); ?></h4>
                        <p class="muted"><?php echo htmlspecialchars($maestro['chapa'] ?? ''); ?></p>
                        <p><strong>Especialidad:</strong> <?php echo htmlspecialchars($maestro['especialidad'] ?? '—'); ?></p>
                        <p><strong>Experiencia:</strong> <?php echo (int)($maestro['anios_experiencia'] ?? 0); ?> años</p>
                    </div>
                <?php else: ?>
                    <div class="profile-card muted">Selecciona una conversación para ver detalles del maestro.</div>
                <?php endif; ?>
            </aside>
        </div>
</div>

<!-- Lightbox -->
<div id="lightbox" class="lightbox" style="display:none;">
    <span class="lightbox-close">&times;</span>
    <img class="lightbox-content" id="lightbox-img">
</div>

<style>
.chat-layout{ display:grid; grid-template-columns: 300px 1fr 300px; gap:20px; align-items:start; }
.chat-left{ background:#fff; border:1px solid #eee; border-radius:6px; padding:10px; height:700px; overflow:auto }
.chat-main{ background:#fff; border:1px solid #eee; border-radius:6px; display:flex; flex-direction:column; height:700px; }
.chat-right{ background:#fff; border:1px solid #eee; border-radius:6px; padding:16px; height:700px; }
.conversations .conv-item{ display:flex; align-items:center; gap:10px; padding:10px; border-radius:6px; text-decoration:none; color:inherit; }
.conversations .conv-item:hover{ background:#f7f7f7 }
.conv-avatar img{ width:48px; height:48px; border-radius:50%; object-fit:cover }
.conv-meta{ flex:1 }
.conv-badge{ background:var(--primary-color); color:#fff; padding:4px 8px; border-radius:12px; font-size:0.8rem }
.chat-top{ padding:12px 16px; border-bottom:1px solid #f0f0f0 }
.maestro-info{ display:flex; gap:12px; align-items:center }
.maestro-thumb{ width:48px; height:48px; border-radius:50%; object-fit:cover }
.chat-window{ flex:1; overflow:hidden; display:flex; flex-direction:column }
.chat-box{ padding:20px; overflow-y:auto; flex:1; background:#f9fafb }
.chat-message{ display:flex; gap:10px; margin-bottom:12px; align-items:flex-end }
.chat-message.me{ justify-content:flex-end }
.chat-message.other{ justify-content:flex-start }
.msg-avatar img{ width:34px; height:34px; border-radius:50%; object-fit:cover }
.msg-body{ max-width:60%; background:#fff; padding:10px 12px; border-radius:10px; box-shadow:var(--shadow-sm) }
.chat-message.me .msg-body{ background:var(--primary-color); color:#fff }
.msg-attach{ max-width:280px; border-radius:8px; display:block; margin-bottom:8px }
.msg-text{ white-space:pre-wrap }
.msg-time{ display:block; font-size:0.75rem; color:var(--gray-color); margin-top:6px }
.msg-time{ display:block; font-size:0.75rem; color:var(--gray-color); margin-top:6px }
.chat-input{ padding:12px; border-top:1px solid #eee }
.file-preview{ position:relative; display:inline-block; margin-bottom:10px; border:1px solid #ddd; padding:5px; border-radius:6px; background:#f9f9f9; }
.file-preview img{ max-height:100px; display:block; }
.file-preview button{ position:absolute; top:-5px; right:-5px; background:red; color:white; border:none; border-radius:50%; width:20px; height:20px; cursor:pointer; font-size:12px; line-height:1; }
.input-row{ display:flex; gap:10px; align-items:center }
.input-row textarea{ flex:1; min-height:48px; max-height:120px; padding:8px; border-radius:8px; border:1px solid #ddd }
.input-actions{ display:flex; gap:8px; align-items:center }
.attach-btn input{ display:none }
.send-btn{ background:var(--primary-color); color:#fff; border:none; padding:10px 14px; border-radius:8px; cursor:pointer }
.profile-card{ text-align:center }
.profile-pic{ width:120px; height:120px; border-radius:50%; object-fit:cover }
.empty-chat{ padding:40px; color:var(--gray-color); text-align:center }
.muted{ color:var(--gray-color) }

/* Lightbox */
.lightbox { display:none; position:fixed; z-index:1000; padding-top:100px; left:0; top:0; width:100%; height:100%; overflow:auto; background-color:rgba(0,0,0,0.9); }
.lightbox-content { margin:auto; display:block; width:80%; max-width:700px; animation-name: zoom; animation-duration: 0.6s; }
.lightbox-close { position:absolute; top:15px; right:35px; color:#f1f1f1; font-size:40px; font-weight:bold; transition:0.3s; cursor:pointer; }
.lightbox-close:hover, .lightbox-close:focus { color:#bbb; text-decoration:none; cursor:pointer; }
@keyframes zoom { from {transform:scale(0)} to {transform:scale(1)} }

</style>

<script>
(() => {
    const chatBox = document.getElementById('chat-box');
    const sendBtn = document.getElementById('sendBtn');
    const sendForm = document.getElementById('sendForm');
    const fileInput = document.getElementById('adjunto');
    const previewContainer = document.getElementById('file-preview');
    const previewImg = document.getElementById('preview-img');
    const removePreviewBtn = document.getElementById('remove-preview');
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const lightboxClose = document.querySelector('.lightbox-close');
    const maestroId = document.querySelector('input[name="maestro_id"]') ? document.querySelector('input[name="maestro_id"]').value : null;

    const BASE = '<?php echo BASE_URL; ?>';
    const WS_HOST = 'ws://localhost:8080';
    const userId = <?php echo (int)$_SESSION['usuario_id']; ?>;
    const userType = 'cliente';
    
    // Avatar for the other person (maestro)
    const otherAvatar = "<?php echo UPLOAD_URL . ($maestro['foto_perfil'] ?? 'perfiles/default.png'); ?>";

    // Preview Logic
    if(fileInput){
        fileInput.addEventListener('change', function(){
            const file = this.files[0];
            if(file){
                const reader = new FileReader();
                reader.onload = function(e){
                    previewImg.src = e.target.result;
                    previewContainer.style.display = 'inline-block';
                }
                reader.readAsDataURL(file);
            }
        });
    }
    if(removePreviewBtn){
        removePreviewBtn.addEventListener('click', function(){
            fileInput.value = '';
            previewContainer.style.display = 'none';
            previewImg.src = '';
        });
    }

    // Lightbox Logic
    function openLightbox(src){
        lightbox.style.display = 'block';
        lightboxImg.src = src;
    }
    if(lightboxClose){
        lightboxClose.addEventListener('click', () => { lightbox.style.display = 'none'; });
    }
    window.addEventListener('click', (e) => {
        if (e.target == lightbox) lightbox.style.display = 'none';
    });

    function scrollBottom(){ if(chatBox) chatBox.scrollTop = chatBox.scrollHeight; }

    function renderMessage(m){
        if (!chatBox) return;
        const div = document.createElement('div');
        const isMe = (m.enviado_por === 'cliente');
        
        div.className = 'chat-message ' + (isMe ? 'me' : 'other');
        div.dataset.id = m.id;

        let html = '';
        
        // If not me, show avatar
        if (!isMe) {
            html += `<div class="msg-avatar"><img src="${otherAvatar}"/></div>`;
        }

        html += `<div class="msg-body">`;
        
        // Attachments
        if (m.adjunto){
            if (m.tipo === 'imagen' || m.tipo === 'sticker') {
                html += `<img src='${BASE}uploads/${m.adjunto}' class="msg-attach" onclick="openLightbox(this.src)" style="cursor:pointer;" />`;
            } else if (m.tipo === 'video') {
                html += `<video controls class="msg-attach"><source src='${BASE}uploads/${m.adjunto}'></video>`;
            }
        }
        
        // Message text
        if (m.mensaje) {
            // simple nl2br
            const safeText = m.mensaje.replace(/&/g, "&amp;")
                                      .replace(/</g, "&lt;")
                                      .replace(/>/g, "&gt;")
                                      .replace(/"/g, "&quot;")
                                      .replace(/'/g, "&#039;")
                                      .replace(/\n/g,'<br>');
            html += `<div class="msg-text">${safeText}</div>`;
        }
        
        // Time
        html += `<small class="msg-time">${m.fecha_envio}</small>`;
        
        html += `</div>`; // end msg-body

        div.innerHTML = html;
        chatBox.appendChild(div);
        scrollBottom();
    }

    // WebSocket
    let ws;
    try {
        ws = new WebSocket(WS_HOST);
        ws.addEventListener('open', () => {
            ws.send(JSON.stringify({ action: 'register', user_id: userId, user_type: userType }));
        });
        ws.addEventListener('message', (ev) => {
            try{
                const data = JSON.parse(ev.data);
                if (data.action === 'new_message' && data.mensaje) {
                    // If message belongs to this conversation, render
                    const m = data.mensaje;
                    const belongs = (m.maestro_id == maestroId && m.cliente_id == userId) || (m.maestro_id == userId && m.cliente_id == maestroId);
                    if (belongs) renderMessage(m);
                }
            }catch(e){ console.error(e); }
        });
    } catch (e) { console.error('WS error', e); }

    sendBtn.addEventListener('click', async (e) => {
        e.preventDefault();
        if (!maestroId) return alert('Selecciona una conversación');

        const txt = document.getElementById('mensaje').value.trim();
        // fileInput is already defined above

        let adjPath = null;
        let tipo = 'texto';

        if (fileInput && fileInput.files && fileInput.files.length > 0) {
            const fd = new FormData();
            fd.append('file', fileInput.files[0]);
            const up = await fetch(BASE + 'api/upload_mensaje.php', { method: 'POST', body: fd });
            const r = await up.json();
            if (!r.success) return alert(r.message || 'Error al subir archivo');
            adjPath = r.path; // e.g. mensajes/xxx.jpg
            const mime = fileInput.files[0].type;
            if (mime.indexOf('image/') === 0) tipo = 'imagen';
            else if (mime.indexOf('video/') === 0) tipo = 'video';
        }

        const payload = { action: 'send_message', sender_id: userId, sender_type: userType, receiver_id: parseInt(maestroId), mensaje: txt || null, tipo: tipo, adjunto: adjPath };

        // send via WS
        if (ws && ws.readyState === WebSocket.OPEN) {
            ws.send(JSON.stringify(payload));
            // clear inputs
            document.getElementById('mensaje').value = '';
            document.getElementById('adjunto').value = '';
            previewContainer.style.display = 'none';
            previewImg.src = '';
        } else {
            alert('Conexión en tiempo real no disponible. Intenta recargar la página.');
        }
    });

    // Initial load of past messages via API
    (async function loadInitial(){
        if (!maestroId) return;
        try{
            const res = await fetch(`${BASE}api/mensajes/obtener?cliente_id=<?php echo (int)$_SESSION['usuario_id']; ?>&maestro_id=${maestroId}`);
            const data = await res.json();
            if (data.success) data.mensajes.forEach(m => renderMessage(m));
        }catch(e){console.error(e)}
    })();

})();
</script>
