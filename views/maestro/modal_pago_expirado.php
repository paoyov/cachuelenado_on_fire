<!-- Modal de Pago Expirado (Obligatorio) -->
<div class="modal-pago-overlay modal-pago-obligatorio" id="modalPagoExpirado" style="display: flex;">
    <div class="modal-pago-container modal-pago-expirado">
        <div class="modal-pago-content modal-expirado-content">
            <div class="modal-pago-header modal-expirado-header">
                <div class="modal-pago-header-content">
                    <div class="modal-pago-icon-wrapper modal-expirado-icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div>
                        <h3 class="modal-pago-title">Pago Expirado</h3>
                        <p class="modal-pago-subtitle">Renovación Requerida</p>
                    </div>
                </div>
                <!-- Sin botón de cerrar - Modal obligatorio -->
            </div>
            
            <div class="modal-pago-body">
                <div class="alert-pago-expirado">
                    <i class="fas fa-clock"></i>
                    <div>
                        <strong>Tu pago ha expirado</strong>
                        <p>Tu período de 24 horas ha finalizado. Para continuar usando la plataforma y mantener tu perfil visible para los clientes, debes realizar un nuevo pago de <strong>S/ 3.00</strong>.</p>
                        <p class="alert-pago-warning-text">
                            <i class="fas fa-lock"></i> Este modal permanecerá visible hasta que realices el pago y sea verificado por el administrador.
                        </p>
                    </div>
                </div>

                <form id="formPagoExpirado" method="POST" action="<?php echo BASE_URL; ?>pago/procesar" enctype="multipart/form-data">
                    <div class="pago-content-grid">
                        <!-- Información de Pago -->
                        <div class="pago-info-section">
                            <div class="pago-info-card">
                                <div class="yape-brand-section">
                                    <div class="wallet-icon-wrapper">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                    <h2 class="yape-brand-name">Yape</h2>
                                    <p class="yape-brand-subtitle">Realiza el pago a través de Yape</p>
                                </div>
                                
                                <div class="payment-details-card">
                                    <div class="payment-detail-row">
                                        <span class="detail-label">Número Yape:</span>
                                        <span class="detail-value yape-number">972256396</span>
                                    </div>
                                    <div class="payment-detail-row">
                                        <span class="detail-label">Monto a Pagar:</span>
                                        <span class="detail-value amount">S/ 3.00</span>
                                    </div>
                                    <div class="payment-detail-row">
                                        <span class="detail-label">Vigencia:</span>
                                        <span class="detail-value validity">24 horas</span>
                                    </div>
                                </div>

                                <!-- QR Code Yape -->
                                <div class="qr-section">
                                    <p class="qr-title">Escanea el código QR</p>
                                    <div class="qr-placeholder">
                                        <img src="<?php echo asset('images/yape-qr.png'); ?>" 
                                             alt="Código QR Yape" 
                                             class="qr-image">
                                    </div>
                                    <p class="qr-alternative">O transfiere al número: <strong>972256396</strong></p>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario de Pago -->
                        <div class="pago-form-section">
                            <div class="pago-form-card">
                                <h5 class="form-section-title">
                                    <i class="fas fa-file-invoice"></i> Comprobante de Pago
                                </h5>

                                <div class="form-group-pago">
                                    <label for="numero_comprobante_expirado" class="form-label-pago">
                                        <i class="fas fa-hashtag"></i> Número de Comprobante (Opcional)
                                    </label>
                                    <input type="text" 
                                           id="numero_comprobante_expirado" 
                                           name="numero_comprobante" 
                                           class="form-control-pago" 
                                           placeholder="Ej: 001-123456"
                                           maxlength="50">
                                    <small class="form-text-pago">Ingresa el número de comprobante si lo tienes</small>
                                </div>

                                <div class="form-group-pago">
                                    <label for="comprobante_imagen_expirado" class="form-label-pago">
                                        <i class="fas fa-image"></i> Captura de Pantalla del Pago *
                                    </label>
                                    <div class="file-upload-area-pago" id="fileUploadAreaExpirado">
                                        <input type="file" 
                                               id="comprobante_imagen_expirado" 
                                               name="comprobante_imagen" 
                                               class="file-input-pago" 
                                               accept="image/*" 
                                               onchange="previewComprobanteExpirado(this)"
                                               required>
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                            <p class="upload-text-primary">Haz clic para subir</p>
                                            <p class="upload-text-secondary">o arrastra la imagen aquí</p>
                                            <p class="upload-text-formats">Formatos: JPG, PNG, GIF (Max 5MB)</p>
                                        </div>
                                    </div>
                                    <div id="previewComprobanteExpirado" class="preview-container">
                                        <img id="previewImgExpirado" src="" alt="Preview" class="preview-image">
                                        <button type="button" class="btn-remove-preview" onclick="removePreviewExpirado()">
                                            <i class="fas fa-times"></i> Eliminar
                                        </button>
                                    </div>
                                </div>

                                <div class="alert-pago-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <div>
                                        <strong>Nota:</strong> Una vez que subas el comprobante, un administrador verificará tu pago. Recibirás una notificación cuando tu pago sea verificado.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-pago-footer">
                        <button type="submit" class="btn-pago-submit btn-pago-renovar">
                            <i class="fas fa-sync-alt"></i> Renovar Pago Ahora
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function previewComprobanteExpirado(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImgExpirado').src = e.target.result;
            document.getElementById('previewComprobanteExpirado').classList.add('show');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removePreviewExpirado() {
    document.getElementById('comprobante_imagen_expirado').value = '';
    document.getElementById('previewComprobanteExpirado').classList.remove('show');
    document.getElementById('previewImgExpirado').src = '';
}

// Hacer clic en el área de carga para abrir el selector de archivos
document.addEventListener('DOMContentLoaded', function() {
    const fileUploadArea = document.getElementById('fileUploadAreaExpirado');
    const fileInput = document.getElementById('comprobante_imagen_expirado');
    
    if (fileUploadArea && fileInput) {
        fileUploadArea.addEventListener('click', function(e) {
            if (e.target !== fileInput) {
                fileInput.click();
            }
        });
    }
    
    // Prevenir cerrar el modal haciendo clic fuera
    const modal = document.getElementById('modalPagoExpirado');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                e.stopPropagation();
                // No hacer nada - modal no se puede cerrar
            }
        });
    }
});
</script>

<style>
/* Modal Obligatorio - No se puede cerrar */
.modal-pago-obligatorio {
    z-index: 9999 !important;
    pointer-events: auto;
}

.modal-pago-obligatorio .modal-pago-container {
    pointer-events: auto;
}

.modal-pago-expirado .modal-pago-content {
    border: 3px solid #dc3545;
    box-shadow: 0 20px 60px rgba(220, 53, 69, 0.4);
    animation: shake 0.5s ease;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-10px); }
    75% { transform: translateX(10px); }
}

.modal-expirado-header {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.modal-expirado-icon {
    background: rgba(255, 255, 255, 0.2);
}

.modal-expirado-icon i {
    color: white;
    font-size: 2rem;
}

.alert-pago-expirado {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-left: 5px solid #dc3545;
    border-radius: 12px;
    padding: 1.5rem 2rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: flex-start;
    gap: 1.25rem;
}

.alert-pago-expirado i {
    color: #dc3545;
    font-size: 2rem;
    margin-top: 0.2rem;
    flex-shrink: 0;
    animation: pulse 2s ease infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

.alert-pago-expirado strong {
    color: #dc3545;
    font-size: 1.2rem;
    display: block;
    margin-bottom: 0.75rem;
}

.alert-pago-expirado p {
    color: #856404;
    margin: 0.5rem 0;
    line-height: 1.6;
}

.alert-pago-warning-text {
    background: rgba(220, 53, 69, 0.1);
    padding: 0.75rem 1rem;
    border-radius: 8px;
    margin-top: 1rem !important;
    border-left: 3px solid #dc3545;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.alert-pago-warning-text i {
    color: #dc3545;
    font-size: 1.1rem;
}

.btn-pago-renovar {
    width: 100%;
    justify-content: center;
    font-size: 1.1rem;
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    animation: pulse-button 2s ease infinite;
}

@keyframes pulse-button {
    0%, 100% {
        box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
    }
    50% {
        box-shadow: 0 6px 25px rgba(220, 53, 69, 0.6);
    }
}

.btn-pago-renovar:hover {
    background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(220, 53, 69, 0.5);
}

/* Bloquear interacción con el contenido de fondo */
.modal-pago-obligatorio::before {
    content: '';
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.85);
    z-index: -1;
}

/* Responsive */
@media (max-width: 968px) {
    .modal-pago-expirado .pago-content-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .alert-pago-expirado {
        padding: 1.25rem 1.5rem;
    }
    
    .alert-pago-expirado i {
        font-size: 1.5rem;
    }
    
    .alert-pago-expirado strong {
        font-size: 1.1rem;
    }
}
</style>

