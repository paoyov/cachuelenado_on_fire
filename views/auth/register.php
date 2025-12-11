<?php
$title = 'Registrarse';
$extra_js = ['js/register.js'];
?>

<div class="auth-container">
    <div class="container">
        <div class="auth-wrapper">
            <div class="auth-card">
                <div class="auth-header">
                    <h1><i class="fas fa-user-plus"></i> Crear Cuenta</h1>
                    <p>Únete a Cachueleando On Fire</p>
                </div>
                
                <form method="POST" action="<?php echo BASE_URL; ?>auth/register" enctype="multipart/form-data" class="auth-form" id="registerForm">
                    <div class="form-group">
                        <label class="form-label">Tipo de Usuario *</label>
                        <div class="form-check-group">
                            <label class="form-check">
                                <input type="radio" name="tipo_usuario" value="cliente" checked required>
                                <span>Cliente</span>
                            </label>
                            <label class="form-check">
                                <input type="radio" name="tipo_usuario" value="maestro" required>
                                <span>Maestro de Oficio</span>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="nombre_completo" class="form-label">Nombre Completo *</label>
                        <input type="text" id="nombre_completo" name="nombre_completo" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" id="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña *</label>
                        <input type="password" id="password" name="password" class="form-control" required minlength="6">
                        <small class="form-text">Mínimo 6 caracteres</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="telefono" class="form-label">Teléfono *</label>
                        <input type="tel" id="telefono" name="telefono" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="dni" class="form-label">DNI *</label>
                        <input type="text" id="dni" name="dni" class="form-control" required maxlength="8">
                    </div>
                    
                    <div class="form-group" id="chapaGroup" style="display: none;">
                        <label for="chapa" class="form-label">Chapa / Apodo</label>
                        <input type="text" id="chapa" name="chapa" class="form-control">
                    </div>
                    
                    <div class="form-group" id="maestroFields" style="display: none;">
                        <label for="anios_experiencia" class="form-label">Años de Experiencia *</label>
                        <input type="number" id="anios_experiencia" name="anios_experiencia" class="form-control" min="0">
                        
                        <label for="area_preferida" class="form-label">Área Preferida</label>
                        <input type="text" id="area_preferida" name="area_preferida" class="form-control">
                        
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea id="descripcion" name="descripcion" class="form-control" rows="3"></textarea>
                        
                        <label class="form-label">Especialidades *</label>
                        <div class="form-check-group">
                            <?php foreach ($especialidades as $especialidad): ?>
                            <label class="form-check">
                                <input type="checkbox" name="especialidades[]" value="<?php echo $especialidad['id']; ?>">
                                <span><?php echo htmlspecialchars($especialidad['nombre']); ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                        
                        <label class="form-label">Distritos donde Trabaja *</label>
                        <div class="form-check-group">
                            <?php foreach ($distritos as $distrito): ?>
                            <label class="form-check">
                                <input type="checkbox" name="distritos[]" value="<?php echo $distrito['id']; ?>">
                                <span><?php echo htmlspecialchars($distrito['nombre']); ?></span>
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
                                <input type="file" name="documentos[dni][]" class="form-control document-input" accept=".pdf,.jpg,.jpeg,.png" id="dni_file" required>
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
                        <label for="foto_perfil" class="form-label">Foto de Perfil</label>
                        <input type="file" id="foto_perfil" name="foto_perfil" class="form-control" accept="image/*" data-preview="fotoPreview">
                        <img id="fotoPreview" src="" alt="Preview" style="display: none; max-width: 200px; margin-top: 1rem; border-radius: var(--border-radius);">
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-user-plus"></i> Registrarse
                        </button>
                    </div>
                </form>
                
                <div class="auth-footer">
                    <p>¿Ya tienes una cuenta? <a href="<?php echo BASE_URL; ?>login">Inicia sesión aquí</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo asset('js/register.js'); ?>"></script>

<style>
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
    display: block;
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: var(--gray-color);
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
</style>

