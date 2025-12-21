<?php
$title = 'Registrarse';
$extra_js = ['js/register.js'];
?>

<div class="auth-container">
    <div class="container">
        <div class="auth-wrapper">
            <div class="auth-card">
                <div class="auth-header">
                    <div class="auth-header-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <h1>Crear Cuenta</h1>
                    <p>Únete a nuestra comunidad de maestros y clientes</p>
                </div>
                
                <form method="POST" action="<?php echo BASE_URL; ?>auth/register" enctype="multipart/form-data" class="auth-form" id="registerForm">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user-tag"></i> Tipo de Usuario *
                        </label>
                        <div class="radio-group">
                            <label class="radio-option">
                                <input type="radio" name="tipo_usuario" value="cliente" checked required>
                                <div class="radio-content">
                                    <i class="fas fa-user"></i>
                                    <span>Cliente</span>
                                </div>
                            </label>
                            <label class="radio-option">
                                <input type="radio" name="tipo_usuario" value="maestro" required>
                                <div class="radio-content">
                                    <i class="fas fa-tools"></i>
                                    <span>Maestro de Oficio</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="nombre_completo" class="form-label">
                            <i class="fas fa-user"></i> Nombre Completo *
                        </label>
                        <div class="input-wrapper">
                            <i class="input-icon fas fa-user"></i>
                            <input type="text" id="nombre_completo" name="nombre_completo" class="form-control enhanced-input" placeholder="Ingresa tu nombre completo" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope"></i> Email *
                        </label>
                        <div class="input-wrapper">
                            <i class="input-icon fas fa-envelope"></i>
                            <input type="email" id="email" name="email" class="form-control enhanced-input" placeholder="tu@email.com" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i> Contraseña *
                        </label>
                        <div class="input-wrapper password-wrapper">
                            <i class="input-icon fas fa-lock"></i>
                            <input type="password" id="password" name="password" class="form-control enhanced-input" placeholder="Mínimo 6 caracteres" required minlength="6">
                            <button type="button" class="password-toggle-btn" onclick="togglePassword('password')">
                                <i class="fas fa-eye" id="password-icon"></i>
                            </button>
                        </div>
                        <small class="form-text">
                            <i class="fas fa-info-circle"></i> Mínimo 6 caracteres
                        </small>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono" class="form-label">
                            <i class="fas fa-phone"></i> Teléfono *
                        </label>
                        <div class="input-wrapper">
                            <i class="input-icon fas fa-phone"></i>
                            <input type="tel" id="telefono" name="telefono" class="form-control enhanced-input" placeholder="+51 999 999 999" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="dni" class="form-label">
                            <i class="fas fa-id-card"></i> DNI *
                        </label>
                        <div class="input-wrapper">
                            <i class="input-icon fas fa-id-card"></i>
                            <input type="text" id="dni" name="dni" class="form-control enhanced-input" placeholder="12345678" required maxlength="8">
                        </div>
                    </div>
                    
                    <div class="form-group" id="chapaGroup" style="display: none;">
                        <label for="chapa" class="form-label">
                            <i class="fas fa-tag"></i> Chapa / Apodo
                        </label>
                        <div class="input-wrapper">
                            <i class="input-icon fas fa-tag"></i>
                            <input type="text" id="chapa" name="chapa" class="form-control enhanced-input" placeholder="Tu apodo (opcional)">
                        </div>
                    </div>
                    
                    <div class="form-group" id="maestroFields" style="display: none;">
                        <div class="section-divider">
                            <span>Información Profesional</span>
                        </div>
                        
                        <label for="anios_experiencia" class="form-label">
                            <i class="fas fa-calendar-alt"></i> Años de Experiencia *
                        </label>
                        <div class="input-wrapper">
                            <i class="input-icon fas fa-calendar-alt"></i>
                            <input type="number" id="anios_experiencia" name="anios_experiencia" class="form-control enhanced-input" placeholder="Ej: 5" min="0">
                        </div>
                        
                        <label for="area_preferida" class="form-label">
                            <i class="fas fa-map-marker-alt"></i> Área Preferida
                        </label>
                        <div class="input-wrapper">
                            <i class="input-icon fas fa-map-marker-alt"></i>
                            <input type="text" id="area_preferida" name="area_preferida" class="form-control enhanced-input" placeholder="Ej: Lima Centro">
                        </div>
                        
                        <label for="descripcion" class="form-label">
                            <i class="fas fa-align-left"></i> Descripción
                        </label>
                        <div class="textarea-wrapper">
                            <textarea id="descripcion" name="descripcion" class="form-control enhanced-textarea" rows="4" placeholder="Cuéntanos sobre tu experiencia y especialidades..."></textarea>
                        </div>
                        
                        <label class="form-label">
                            <i class="fas fa-briefcase"></i> Especialidades *
                        </label>
                        <div class="checkbox-group">
                            <?php foreach ($especialidades as $especialidad): ?>
                            <label class="checkbox-option">
                                <input type="checkbox" name="especialidades[]" value="<?php echo $especialidad['id']; ?>">
                                <span class="checkmark"></span>
                                <span class="checkbox-label"><?php echo htmlspecialchars($especialidad['nombre']); ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                        
                        <label class="form-label">
                            <i class="fas fa-map-marked-alt"></i> Distritos donde Trabaja *
                        </label>
                        <div class="checkbox-group">
                            <?php foreach ($distritos as $distrito): ?>
                            <label class="checkbox-option">
                                <input type="checkbox" name="distritos[]" value="<?php echo $distrito['id']; ?>">
                                <span class="checkmark"></span>
                                <span class="checkbox-label"><?php echo htmlspecialchars($distrito['nombre']); ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="document-upload-section">
                            <label class="form-label section-label">
                                <i class="fas fa-file-upload"></i> Documentos de Respaldo *
                            </label>
                            <p class="form-text mb-3">Sube tus documentos para verificación. Formatos aceptados: PDF, JPG, PNG</p>
                            
                            <div class="form-group document-group">
                                <label class="document-label">
                                    <i class="fas fa-id-card text-primary"></i> DNI Escaneado *
                                    <span class="badge bg-primary">Requerido</span>
                                </label>
                                <input type="file" name="documentos[dni][]" class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png" id="dni_file">
                                <small class="form-text">Sube una copia clara de tu DNI (ambos lados si es posible)</small>
                            </div>
                            
                            <div class="form-group document-group">
                                <label class="document-label">
                                    <i class="fas fa-certificate text-success"></i> Certificados
                                    <span class="badge bg-secondary">Opcional</span>
                                </label>
                                <input type="file" name="documentos[certificado][]" class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png" multiple id="cert_file">
                                <small class="form-text">Certificados de cursos, capacitaciones o experiencia laboral (puedes subir varios)</small>
                            </div>
                            
                            <div class="form-group document-group">
                                <label class="document-label">
                                    <i class="fas fa-images text-warning"></i> Fotos de Trabajos Realizados
                                    <span class="badge bg-secondary">Opcional</span>
                                </label>
                                <input type="file" name="documentos[foto_trabajo][]" class="form-control document-input" accept=".jpg,.jpeg,.png" multiple id="work_photos">
                                <small class="form-text">Fotos de trabajos que hayas realizado para mostrar tu experiencia (puedes subir varias)</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="foto_perfil" class="form-label">
                            <i class="fas fa-camera"></i> Foto de Perfil
                        </label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="foto_perfil" name="foto_perfil" class="file-input" accept="image/*" data-preview="fotoPreview">
                            <label for="foto_perfil" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Seleccionar imagen</span>
                            </label>
                            <div class="file-preview" id="fotoPreviewContainer" style="display: none;">
                                <img id="fotoPreview" src="" alt="Preview">
                                <button type="button" class="remove-image-btn" onclick="removeImagePreview()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group submit-group">
                        <button type="submit" class="btn-submit">
                            <span class="btn-content">
                                <i class="fas fa-user-plus"></i>
                                <span>Crear Cuenta</span>
                            </span>
                            <span class="btn-loader" style="display: none;">
                                <i class="fas fa-spinner fa-spin"></i>
                            </span>
                        </button>
                    </div>
                </form>
                
                <div class="auth-footer">
                    <p class="auth-footer-text">
                        <span class="auth-footer-question">¿Ya tienes una cuenta?</span>
                        <a href="<?php echo BASE_URL; ?>login" class="auth-footer-link">
                            <i class="fas fa-sign-in-alt"></i> Inicia sesión aquí
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo asset('js/register.js'); ?>"></script>

<style>
/* ============================================
   Auth Header Styles
   ============================================ */
.auth-header {
    text-align: center;
    margin-bottom: 2.5rem;
    padding-bottom: 2rem;
    border-bottom: 2px solid #f0f0f0;
    position: relative;
}

.auth-header::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
    border-radius: 2px;
}

.auth-header-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 8px 20px rgba(255, 107, 53, 0.3);
    animation: float 3s ease-in-out infinite;
}

.auth-header-icon i {
    font-size: 2rem;
    color: white;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.auth-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: var(--dark-color);
    margin-bottom: 0.5rem;
    letter-spacing: -0.5px;
}

.auth-header p {
    color: var(--gray-color);
    font-size: 1rem;
    margin: 0;
}

/* ============================================
   Enhanced Form Styles
   ============================================ */
.auth-card {
    background: var(--white);
    border-radius: 16px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    padding: 3rem;
    position: relative;
    overflow: hidden;
}

.auth-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--primary-dark), var(--primary-color));
}

.form-group {
    margin-bottom: 2rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 0.75rem;
    font-weight: 600;
    color: var(--dark-color);
    font-size: 0.95rem;
}

.form-label i {
    color: var(--primary-color);
    font-size: 0.9rem;
}

/* Input Wrapper with Icons */
.input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}

.input-icon {
    position: absolute;
    left: 1rem;
    color: var(--gray-color);
    z-index: 1;
    transition: color 0.3s ease;
}

.enhanced-input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #fafafa;
    font-family: inherit;
}

.enhanced-input:focus {
    outline: none;
    border-color: var(--primary-color);
    background: white;
    box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
    transform: translateY(-2px);
}

.enhanced-input:focus + .input-icon,
.input-wrapper:focus-within .input-icon {
    color: var(--primary-color);
}

.enhanced-input::placeholder {
    color: #adb5bd;
}

/* Password Wrapper */
.password-wrapper {
    position: relative;
}

.password-wrapper .input-icon {
    left: 1rem;
}

.password-wrapper .enhanced-input {
    padding-right: 3.5rem;
}

.password-toggle-btn {
    position: absolute;
    right: 1rem;
    background: none;
    border: none;
    color: var(--gray-color);
    cursor: pointer;
    padding: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    z-index: 2;
    border-radius: 6px;
}

.password-toggle-btn:hover {
    color: var(--primary-color);
    background: rgba(255, 107, 53, 0.1);
}

/* Textarea */
.textarea-wrapper {
    position: relative;
}

.enhanced-textarea {
    width: 100%;
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #fafafa;
    font-family: inherit;
    resize: vertical;
    min-height: 120px;
}

.enhanced-textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    background: white;
    box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
}

.enhanced-textarea::placeholder {
    color: #adb5bd;
}

/* Radio Buttons */
.radio-group {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 0.5rem;
}

.radio-option {
    position: relative;
    cursor: pointer;
}

.radio-option input[type="radio"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.radio-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem 1rem;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    background: #fafafa;
    transition: all 0.3s ease;
    text-align: center;
    gap: 0.75rem;
}

.radio-content i {
    font-size: 2rem;
    color: var(--gray-color);
    transition: all 0.3s ease;
}

.radio-content span {
    font-weight: 600;
    color: var(--dark-color);
    transition: color 0.3s ease;
}

.radio-option input[type="radio"]:checked + .radio-content {
    border-color: var(--primary-color);
    background: linear-gradient(135deg, rgba(255, 107, 53, 0.1) 0%, rgba(255, 107, 53, 0.05) 100%);
    box-shadow: 0 4px 12px rgba(255, 107, 53, 0.2);
    transform: translateY(-2px);
}

.radio-option input[type="radio"]:checked + .radio-content i {
    color: var(--primary-color);
    transform: scale(1.1);
}

.radio-option:hover .radio-content {
    border-color: var(--primary-color);
    background: white;
}

/* Checkbox Group */
.checkbox-group {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 0.75rem;
    max-height: 250px;
    overflow-y: auto;
    padding: 1.25rem;
    border: 2px solid #e9ecef;
    border-radius: 12px;
    background: #fafafa;
    margin-top: 0.5rem;
}

.checkbox-group::-webkit-scrollbar {
    width: 8px;
}

.checkbox-group::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.checkbox-group::-webkit-scrollbar-thumb {
    background: var(--primary-color);
    border-radius: 10px;
}

.checkbox-group::-webkit-scrollbar-thumb:hover {
    background: var(--primary-dark);
}

.checkbox-option {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    padding: 0.75rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    position: relative;
}

.checkbox-option:hover {
    background: rgba(255, 107, 53, 0.05);
}

.checkbox-option input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.checkmark {
    width: 22px;
    height: 22px;
    border: 2px solid #dee2e6;
    border-radius: 6px;
    background: white;
    position: relative;
    transition: all 0.3s ease;
    flex-shrink: 0;
}

.checkbox-option input[type="checkbox"]:checked + .checkmark {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

.checkbox-option input[type="checkbox"]:checked + .checkmark::after {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 0.75rem;
}

.checkbox-label {
    font-weight: 500;
    color: var(--dark-color);
    user-select: none;
}

/* Section Divider */
.section-divider {
    text-align: center;
    margin: 2rem 0 1.5rem;
    position: relative;
}

.section-divider::before,
.section-divider::after {
    content: '';
    position: absolute;
    top: 50%;
    width: 30%;
    height: 1px;
    background: linear-gradient(90deg, transparent, #e9ecef, transparent);
}

.section-divider::before {
    left: 0;
}

.section-divider::after {
    right: 0;
}

.section-divider span {
    background: white;
    padding: 0 1.5rem;
    color: var(--gray-color);
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 1px;
    position: relative;
    z-index: 1;
}

/* File Upload */
.file-upload-wrapper {
    position: relative;
}

.file-input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.file-upload-label {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 1.25rem;
    border: 2px dashed #dee2e6;
    border-radius: 12px;
    background: #fafafa;
    cursor: pointer;
    transition: all 0.3s ease;
    font-weight: 500;
    color: var(--dark-color);
}

.file-upload-label:hover {
    border-color: var(--primary-color);
    background: rgba(255, 107, 53, 0.05);
    transform: translateY(-2px);
}

.file-upload-label i {
    color: var(--primary-color);
    font-size: 1.25rem;
}

.file-preview {
    margin-top: 1rem;
    position: relative;
    display: inline-block;
}

.file-preview img {
    max-width: 200px;
    max-height: 200px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.remove-image-btn {
    position: absolute;
    top: -10px;
    right: -10px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--danger-color);
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    transition: all 0.3s ease;
}

.remove-image-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
}

/* Submit Button */
.submit-group {
    margin-top: 2.5rem;
}

.btn-submit {
    width: 100%;
    padding: 1.25rem 2rem;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
    position: relative;
    overflow: hidden;
}

.btn-submit::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.btn-submit:hover::before {
    left: 100%;
}

.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
}

.btn-submit:active {
    transform: translateY(0);
}

.btn-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
}

.btn-loader {
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-check-group {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.5rem;
    max-height: 200px;
    overflow-y: auto;
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: var(--border-radius);
}

.form-text {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 0.5rem;
    font-size: 0.875rem;
    color: var(--gray-color);
}

.form-text i {
    font-size: 0.75rem;
}

/* Document Upload Section Styling */
.document-upload-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 1.5rem;
    border-radius: 12px;
    border: 2px solid #dee2e6;
    margin-top: 1.5rem;
}

.section-label {
    font-size: 1.1rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.document-group {
    background: white;
    padding: 1.2rem;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.document-group:hover {
    border-color: var(--primary-color);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    transform: translateY(-2px);
}

.document-label {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 0.8rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1rem;
}

.document-label i {
    font-size: 1.2rem;
}

.document-label .badge {
    margin-left: auto;
    font-size: 0.75rem;
    padding: 0.25rem 0.6rem;
}

.document-input {
    border: 2px dashed #ced4da;
    padding: 0.8rem;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.document-input:hover {
    border-color: var(--primary-color);
    background: white;
}

.document-input:focus {
    border-color: var(--primary-color);
    border-style: solid;
    background: white;
    box-shadow: 0 0 0 0.2rem rgba(255, 106, 42, 0.15);
}

.mb-3 {
    margin-bottom: 1rem;
}

/* Espaciado del contenedor de autenticación */
.auth-container {
    padding-bottom: 4rem;
}

.auth-wrapper {
    margin-bottom: 3rem;
}

/* Estilos mejorados para auth-footer */
.auth-footer {
    text-align: center;
    padding-top: 2rem;
    margin-top: 2rem;
    margin-bottom: 3rem;
    border-top: 2px solid #e9ecef;
    position: relative;
}

.auth-footer::before {
    content: '';
    position: absolute;
    top: -2px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 2px;
    background: linear-gradient(90deg, transparent, var(--primary-color), transparent);
}

.auth-footer-text {
    margin: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.95rem;
}

.auth-footer-question {
    color: var(--gray-color);
    font-weight: 400;
}

.auth-footer-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    padding: 0.5rem 1.25rem;
    border-radius: 8px;
    background: linear-gradient(135deg, rgba(255, 107, 53, 0.1) 0%, rgba(255, 107, 53, 0.05) 100%);
    border: 2px solid rgba(255, 107, 53, 0.2);
    transition: all 0.3s ease;
}

.auth-footer-link i {
    font-size: 0.9rem;
}

.auth-footer-link:hover {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
    color: white;
    border-color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
    text-decoration: none;
}

.auth-footer-link:hover i {
    transform: translateX(3px);
}

/* ============================================
   Responsive Styles
   ============================================ */
@media (max-width: 768px) {
    .auth-card {
        padding: 2rem 1.5rem;
    }
    
    .auth-header h1 {
        font-size: 1.75rem;
    }
    
    .auth-header-icon {
        width: 70px;
        height: 70px;
    }
    
    .auth-header-icon i {
        font-size: 1.75rem;
    }
    
    .radio-group {
        grid-template-columns: 1fr;
    }
    
    .checkbox-group {
        grid-template-columns: 1fr;
        max-height: 200px;
    }
    
    .btn-submit {
        padding: 1rem 1.5rem;
        font-size: 1rem;
    }
}

@media (max-width: 576px) {
    .auth-card {
        padding: 1.5rem 1rem;
        border-radius: 12px;
    }
    
    .auth-header {
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
    }
    
    .auth-header h1 {
        font-size: 1.5rem;
    }
    
    .auth-header-icon {
        width: 60px;
        height: 60px;
        margin-bottom: 1rem;
    }
    
    .auth-header-icon i {
        font-size: 1.5rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .enhanced-input,
    .enhanced-textarea {
        padding: 0.875rem 0.875rem 0.875rem 2.75rem;
        font-size: 0.95rem;
    }
    
    .input-icon {
        left: 0.875rem;
        font-size: 0.9rem;
    }
    
    .radio-content {
        padding: 1.25rem 0.75rem;
    }
    
    .radio-content i {
        font-size: 1.75rem;
    }
    
    .checkbox-group {
        padding: 1rem;
        gap: 0.5rem;
    }
    
    .auth-footer-text {
        font-size: 0.9rem;
    }
    
    .auth-footer-link {
        font-size: 0.95rem;
        padding: 0.45rem 1rem;
    }
    
    .file-preview img {
        max-width: 150px;
        max-height: 150px;
    }
}
</style>

