<!-- Modal de Pago Yape -->
<div class="modal-pago-overlay" id="modalPagoYape" style="display: none;" onclick="if(event.target === this) cerrarModalPago()">
    <div class="modal-pago-container">
        <div class="modal-pago-content">
            <div class="modal-pago-header">
                <div class="modal-pago-header-content">
                    <div class="modal-pago-icon-wrapper">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <div>
                        <h3 class="modal-pago-title">Pago de Validación</h3>
                        <p class="modal-pago-subtitle">Maestro de Oficio</p>
                    </div>
                </div>
                <button type="button" class="modal-pago-close" onclick="cerrarModalPago()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="modal-pago-body">
                <div class="alert-pago-info">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <strong>Importante:</strong> Para que tu perfil sea validado por el administrador, debes realizar un pago de <strong>S/ 3.00</strong> mediante Yape. Este pago es válido por <strong>24 horas</strong>.
                    </div>
                </div>

                <form id="formPagoYape" method="POST" action="<?php echo BASE_URL; ?>pago/procesar" enctype="multipart/form-data">
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
                                    <label for="numero_comprobante" class="form-label-pago">
                                        <i class="fas fa-hashtag"></i> Número de Comprobante (Opcional)
                                    </label>
                                    <input type="text" 
                                           id="numero_comprobante" 
                                           name="numero_comprobante" 
                                           class="form-control-pago" 
                                           placeholder="Ej: 001-123456"
                                           maxlength="50">
                                    <small class="form-text-pago">Ingresa el número de comprobante si lo tienes</small>
                                </div>

                                <div class="form-group-pago">
                                    <label for="comprobante_imagen" class="form-label-pago">
                                        <i class="fas fa-image"></i> Captura de Pantalla del Pago *
                                    </label>
                                    <div class="file-upload-area-pago" id="fileUploadArea">
                                        <input type="file" 
                                               id="comprobante_imagen" 
                                               name="comprobante_imagen" 
                                               class="file-input-pago" 
                                               accept="image/*" 
                                               onchange="previewComprobante(this)">
                                        <div class="upload-content">
                                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                            <p class="upload-text-primary">Haz clic para subir</p>
                                            <p class="upload-text-secondary">o arrastra la imagen aquí</p>
                                            <p class="upload-text-formats">Formatos: JPG, PNG, GIF (Max 5MB)</p>
                                        </div>
                                    </div>
                                    <div id="previewComprobante" class="preview-container">
                                        <img id="previewImg" src="" alt="Preview" class="preview-image">
                                        <button type="button" class="btn-remove-preview" onclick="removePreview()">
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
                        <button type="button" class="btn-pago-cancel" onclick="cerrarModalPago()">
                            <i class="fas fa-times"></i> Cancelar
                        </button>
                        <button type="submit" class="btn-pago-submit">
                            <i class="fas fa-check"></i> Enviar Comprobante
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function cerrarModalPago() {
    const modal = document.getElementById('modalPagoYape');
    if (modal) {
        modal.style.display = 'none';
        // Restaurar scroll del body
        document.body.style.overflow = '';
        // Eliminar el flag de la sesión mediante AJAX
        fetch('<?php echo BASE_URL; ?>pago/cerrar-modal', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        });
    }
}

function previewComprobante(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('previewComprobante').classList.add('show');
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function removePreview() {
    document.getElementById('comprobante_imagen').value = '';
    document.getElementById('previewComprobante').classList.remove('show');
    document.getElementById('previewImg').src = '';
}

// Hacer clic en el área de carga para abrir el selector de archivos
document.addEventListener('DOMContentLoaded', function() {
    const fileUploadArea = document.getElementById('fileUploadArea');
    const fileInput = document.getElementById('comprobante_imagen');
    
    if (fileUploadArea && fileInput) {
        fileUploadArea.addEventListener('click', function(e) {
            if (e.target !== fileInput) {
                fileInput.click();
            }
        });
    }
    
    // Manejar envío del formulario por AJAX
    const formPago = document.getElementById('formPagoYape');
    if (formPago) {
        formPago.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = formPago.querySelector('.btn-pago-submit');
            const fileInput = document.getElementById('comprobante_imagen');
            
            // Validar que se haya subido una imagen
            if (!fileInput.files || fileInput.files.length === 0) {
                mostrarAlertaPersonalizada('Por favor, sube una captura de pantalla del pago', 'warning');
                return;
            }
            
            // Deshabilitar botón
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando...';
            
            // Crear FormData
            const formData = new FormData(formPago);
            
            // Enviar por AJAX
            fetch('<?php echo BASE_URL; ?>pago/procesar', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Cerrar modal de pago
                    cerrarModalPago();
                    
                    // Mostrar modal de espera
                    mostrarModalEspera();
                    
                    // Iniciar polling para verificar estado
                    iniciarPolling();
                } else {
                    mostrarAlertaPersonalizada(data.message || 'Error al enviar el comprobante', 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-check"></i> Enviar Comprobante';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                mostrarAlertaPersonalizada('Error al enviar el comprobante. Por favor, intente nuevamente.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check"></i> Enviar Comprobante';
            });
        });
    }
});

// Función para mostrar modal de espera
function mostrarModalEspera() {
    const modalEspera = document.getElementById('modalEsperaValidacion');
    if (modalEspera) {
        modalEspera.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
}

// Función para cerrar modal de espera
function cerrarModalEspera() {
    const modalEspera = document.getElementById('modalEsperaValidacion');
    if (modalEspera) {
        modalEspera.style.display = 'none';
        document.body.style.overflow = '';
    }
}

// Polling para verificar estado del pago
let pollingInterval = null;

function iniciarPolling() {
    // Verificar cada 3 segundos
    pollingInterval = setInterval(function() {
        fetch('<?php echo BASE_URL; ?>pago/verificar-estado', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.verificado) {
                // Pago verificado, detener polling
                clearInterval(pollingInterval);
                
                // Cerrar modal de espera
                cerrarModalEspera();
                
                // Mostrar mensaje de éxito
                mostrarMensajeExito();
                
                // Recargar página después de 2 segundos
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            } else if (data.success && data.estado === 'rechazado') {
                // Pago rechazado
                clearInterval(pollingInterval);
                cerrarModalEspera();
                mostrarAlertaPersonalizada('Tu pago ha sido rechazado. Por favor, verifica los datos e intenta nuevamente.', 'error');
            }
        })
        .catch(error => {
            console.error('Error al verificar estado:', error);
        });
    }, 3000);
}

// Función para mostrar mensaje de éxito
function mostrarMensajeExito() {
    mostrarAlertaPersonalizada('¡Pago verificado exitosamente! Tu perfil está activo.', 'success');
}

// Función para mostrar alerta personalizada profesional
function mostrarAlertaPersonalizada(mensaje, tipo = 'info') {
    // Tipos: success, error, warning, info
    const tipos = {
        success: {
            icon: 'fa-check-circle',
            color: '#28a745',
            bgColor: '#d4edda',
            borderColor: '#c3e6cb',
            title: 'Éxito'
        },
        error: {
            icon: 'fa-exclamation-circle',
            color: '#dc3545',
            bgColor: '#f8d7da',
            borderColor: '#f5c6cb',
            title: 'Error'
        },
        warning: {
            icon: 'fa-exclamation-triangle',
            color: '#ffc107',
            bgColor: '#fff3cd',
            borderColor: '#ffeaa7',
            title: 'Advertencia'
        },
        info: {
            icon: 'fa-info-circle',
            color: '#17a2b8',
            bgColor: '#d1ecf1',
            borderColor: '#bee5eb',
            title: 'Información'
        }
    };
    
    const config = tipos[tipo] || tipos.info;
    
    // Crear contenedor de alerta
    const alerta = document.createElement('div');
    alerta.className = 'alerta-personalizada alerta-' + tipo;
    alerta.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10002;
        min-width: 350px;
        max-width: 500px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
        border-left: 4px solid ${config.color};
        animation: slideInRight 0.3s ease-out;
        overflow: hidden;
    `;
    
    alerta.innerHTML = `
        <div class="alerta-personalizada-content" style="
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
        ">
            <div class="alerta-icon-wrapper" style="
                width: 48px;
                height: 48px;
                background: ${config.bgColor};
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
            ">
                <i class="fas ${config.icon}" style="
                    font-size: 1.5rem;
                    color: ${config.color};
                "></i>
            </div>
            <div class="alerta-text-content" style="flex: 1;">
                <h4 class="alerta-title" style="
                    margin: 0 0 0.5rem 0;
                    font-size: 1rem;
                    font-weight: 600;
                    color: #2c3e50;
                ">${config.title}</h4>
                <p class="alerta-message" style="
                    margin: 0;
                    font-size: 0.95rem;
                    color: #495057;
                    line-height: 1.5;
                ">${mensaje}</p>
            </div>
            <button class="alerta-close-btn" onclick="this.parentElement.parentElement.remove()" style="
                background: none;
                border: none;
                color: #6c757d;
                font-size: 1.25rem;
                cursor: pointer;
                padding: 0;
                width: 24px;
                height: 24px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: color 0.3s ease;
                flex-shrink: 0;
            " onmouseover="this.style.color='#dc3545'" onmouseout="this.style.color='#6c757d'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    // Agregar estilos de animación si no existen
    if (!document.getElementById('alerta-personalizada-styles')) {
        const style = document.createElement('style');
        style.id = 'alerta-personalizada-styles';
        style.textContent = `
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
            
            .alerta-personalizada {
                animation: slideInRight 0.3s ease-out;
            }
            
            .alerta-personalizada.removing {
                animation: slideOutRight 0.3s ease-out;
            }
            
            .alerta-close-btn:hover {
                transform: scale(1.1);
            }
            
            @media (max-width: 576px) {
                .alerta-personalizada {
                    min-width: calc(100% - 40px);
                    right: 20px;
                    left: 20px;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(alerta);
    
    // Auto-cerrar después de 5 segundos (excepto para errores críticos)
    if (tipo !== 'error') {
        setTimeout(function() {
            alerta.classList.add('removing');
            setTimeout(function() {
                if (alerta.parentElement) {
                    alerta.remove();
                }
            }, 300);
        }, 5000);
    }
}
</script>

<style>
/* Modal Overlay - Centrado perfecto */
.modal-pago-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 20px;
    overflow-y: auto;
}

/* Contenedor del Modal - Centrado */
.modal-pago-container {
    width: 100%;
    max-width: 900px;
    margin: auto;
    position: relative;
    z-index: 10000;
}

/* Contenido del Modal */
.modal-pago-content {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    animation: modalSlideIn 0.3s ease-out;
    position: relative;
    z-index: 10001;
    display: block;
    visibility: visible;
    opacity: 1;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

/* Header del Modal */
.modal-pago-header {
    background: linear-gradient(135deg, #ff6b35 0%, #e55a2b 100%);
    color: white;
    padding: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: relative;
}

.modal-pago-header-content {
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.modal-pago-icon-wrapper {
    width: 60px;
    height: 60px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    backdrop-filter: blur(10px);
}

.modal-pago-title {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 700;
}

.modal-pago-subtitle {
    margin: 0.25rem 0 0 0;
    font-size: 0.95rem;
    opacity: 0.9;
}

.modal-pago-close {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 1.2rem;
}

.modal-pago-close:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: rotate(90deg);
}

/* Body del Modal */
.modal-pago-body {
    padding: 2.5rem;
}

.alert-pago-info {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border-left: 4px solid #2196f3;
    border-radius: 10px;
    padding: 1.25rem 1.5rem;
    margin-bottom: 2rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.alert-pago-info i {
    color: #2196f3;
    font-size: 1.5rem;
    margin-top: 0.2rem;
}

.alert-pago-info strong {
    color: #1565c0;
}

/* Grid de contenido */
.pago-content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

/* Sección de Información de Pago */
.pago-info-section {
    display: flex;
    flex-direction: column;
}

.pago-info-card {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    border: 2px solid #e9ecef;
    border-radius: 16px;
    padding: 2rem;
    height: 100%;
}

.yape-brand-section {
    text-align: center;
    margin-bottom: 2rem;
}

.wallet-icon-wrapper {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #ff6b35 0%, #e55a2b 100%);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
}

.wallet-icon-wrapper i {
    font-size: 2.5rem;
    color: white;
}

.yape-brand-name {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin: 0 0 0.5rem 0;
}

.yape-brand-subtitle {
    color: #6c757d;
    font-size: 0.95rem;
    margin: 0;
}

.payment-details-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.payment-detail-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #f0f0f0;
}

.payment-detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #495057;
    font-size: 0.95rem;
}

.detail-value {
    font-weight: 700;
    font-size: 1.1rem;
}

.detail-value.yape-number {
    color: #ff6b35;
    font-size: 1.3rem;
}

.detail-value.amount {
    color: #28a745;
    font-size: 1.4rem;
}

.detail-value.validity {
    color: #ffc107;
}

/* Sección QR */
.qr-section {
    text-align: center;
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
}

.qr-title {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 1rem;
    font-size: 1rem;
}

.qr-placeholder {
    width: 220px;
    height: 220px;
    margin: 0 auto 1rem;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
}

.qr-image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
}

.qr-alternative {
    font-size: 0.85rem;
    color: #6c757d;
    margin: 0;
}

/* Sección de Formulario */
.pago-form-section {
    display: flex;
    flex-direction: column;
}

.pago-form-card {
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 16px;
    padding: 2rem;
    height: 100%;
}

.form-section-title {
    color: #2c3e50;
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.form-section-title i {
    color: #ff6b35;
}

.form-group-pago {
    margin-bottom: 1.5rem;
}

.form-label-pago {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}

.form-label-pago i {
    color: #ff6b35;
}

.form-control-pago {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.form-control-pago:focus {
    outline: none;
    border-color: #ff6b35;
    background: white;
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
}

.form-text-pago {
    display: block;
    margin-top: 0.5rem;
    font-size: 0.85rem;
    color: #6c757d;
}

/* Área de carga de archivos */
.file-upload-area-pago {
    border: 2px dashed #ff6b35;
    border-radius: 12px;
    padding: 2.5rem 2rem;
    text-align: center;
    background: linear-gradient(135deg, #fff5f0 0%, #ffffff 100%);
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.file-upload-area-pago:hover {
    background: white;
    border-color: #e55a2b;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 107, 53, 0.15);
}

.file-input-pago {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    opacity: 0;
    cursor: pointer;
}

.upload-content {
    pointer-events: none;
}

.upload-icon {
    font-size: 3.5rem;
    color: #ff6b35;
    margin-bottom: 1rem;
    display: block;
}

.upload-text-primary {
    margin: 0;
    color: #2c3e50;
    font-weight: 600;
    font-size: 1rem;
}

.upload-text-secondary {
    margin: 0.5rem 0 0 0;
    color: #6c757d;
    font-size: 0.9rem;
}

.upload-text-formats {
    margin: 0.5rem 0 0 0;
    color: #6c757d;
    font-size: 0.85rem;
}

.preview-container {
    margin-top: 1rem;
    display: none;
}

.preview-container.show {
    display: block;
}

.preview-image {
    max-width: 100%;
    border-radius: 10px;
    border: 2px solid #e9ecef;
    margin-bottom: 0.75rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.btn-remove-preview {
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 8px;
    padding: 0.5rem 1rem;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-remove-preview:hover {
    background: #c82333;
    transform: translateY(-2px);
}

.alert-pago-warning {
    background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    border-left: 4px solid #ffc107;
    border-radius: 10px;
    padding: 1.25rem 1.5rem;
    margin-top: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.alert-pago-warning i {
    color: #ffc107;
    font-size: 1.5rem;
    margin-top: 0.2rem;
}

/* Footer del Modal */
.modal-pago-footer {
    border-top: 2px solid #f0f0f0;
    padding: 1.5rem 2.5rem;
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    background: #f8f9fa;
}

.btn-pago-cancel {
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 0.875rem 2rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-pago-cancel:hover {
    background: #5a6268;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
}

.btn-pago-submit {
    background: linear-gradient(135deg, #ff6b35 0%, #e55a2b 100%);
    color: white;
    border: none;
    border-radius: 10px;
    padding: 0.875rem 2rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.4);
}

.btn-pago-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 107, 53, 0.5);
}

/* Responsive */
@media (max-width: 968px) {
    .pago-content-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-pago-body {
        padding: 1.5rem;
    }
    
    .modal-pago-header {
        padding: 1.5rem;
    }
    
    .modal-pago-footer {
        flex-direction: column;
        padding: 1.5rem;
    }
    
    .btn-pago-cancel,
    .btn-pago-submit {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .modal-pago-overlay {
        padding: 10px;
    }
    
    .modal-pago-content {
        border-radius: 15px;
    }
    
    .qr-placeholder {
        width: 180px;
        height: 180px;
    }
}
</style>

<!-- Modal de Espera de Validación -->
<div class="modal-espera-overlay" id="modalEsperaValidacion" style="display: none;">
    <div class="modal-espera-container">
        <div class="modal-espera-content">
            <div class="modal-espera-header">
                <div class="modal-espera-icon-wrapper">
                    <i class="fas fa-clock"></i>
                </div>
                <h3 class="modal-espera-title">Esperando Validación</h3>
                <p class="modal-espera-subtitle">Tu comprobante está siendo revisado</p>
            </div>
            
            <div class="modal-espera-body">
                <div class="espera-animation">
                    <div class="spinner-wrapper">
                        <div class="spinner"></div>
                    </div>
                </div>
                
                <div class="espera-message">
                    <p class="espera-text-primary">
                        <i class="fas fa-info-circle"></i>
                        <strong>Tu comprobante ha sido enviado correctamente</strong>
                    </p>
                    <p class="espera-text-secondary">
                        Un administrador está revisando tu comprobante de pago. 
                        Este modal se cerrará automáticamente cuando tu pago sea verificado.
                    </p>
                    <p class="espera-text-note">
                        <i class="fas fa-hourglass-half"></i>
                        Por favor, espera mientras validamos tu pago...
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Modal de Espera de Validación */
.modal-espera-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(5px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    padding: 20px;
}

.modal-espera-container {
    width: 100%;
    max-width: 500px;
    margin: auto;
}

.modal-espera-content {
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    animation: modalSlideIn 0.3s ease-out;
}

.modal-espera-header {
    background: linear-gradient(135deg, #ff6b35 0%, #e55a2b 100%);
    color: white;
    padding: 2.5rem 2rem;
    text-align: center;
}

.modal-espera-icon-wrapper {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2.5rem;
    animation: pulse 2s ease infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
        opacity: 1;
    }
    50% {
        transform: scale(1.1);
        opacity: 0.8;
    }
}

.modal-espera-title {
    margin: 0 0 0.5rem 0;
    font-size: 1.75rem;
    font-weight: 700;
}

.modal-espera-subtitle {
    margin: 0;
    font-size: 1rem;
    opacity: 0.9;
}

.modal-espera-body {
    padding: 2.5rem 2rem;
    text-align: center;
}

.espera-animation {
    margin-bottom: 2rem;
}

.spinner-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 1.5rem;
}

.spinner {
    width: 60px;
    height: 60px;
    border: 5px solid #f3f3f3;
    border-top: 5px solid #ff6b35;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.espera-message {
    text-align: center;
}

.espera-text-primary {
    font-size: 1.1rem;
    color: #2c3e50;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.espera-text-primary i {
    color: #ff6b35;
    font-size: 1.3rem;
}

.espera-text-secondary {
    font-size: 0.95rem;
    color: #6c757d;
    line-height: 1.6;
    margin-bottom: 1rem;
}

.espera-text-note {
    font-size: 0.9rem;
    color: #ff6b35;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1.5rem;
    font-weight: 600;
}

.espera-text-note i {
    animation: rotate 2s linear infinite;
}

@keyframes rotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@media (max-width: 576px) {
    .modal-espera-container {
        max-width: 100%;
    }
    
    .modal-espera-header {
        padding: 2rem 1.5rem;
    }
    
    .modal-espera-body {
        padding: 2rem 1.5rem;
    }
    
    .modal-espera-title {
        font-size: 1.5rem;
    }
}
</style>
