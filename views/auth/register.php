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
                        
                        <label class="form-label">Documentos de Respaldo</label>
                        <div class="form-group">
                            <label>DNI Escaneado</label>
                            <input type="file" name="documentos[dni][]" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                        </div>
                        <div class="form-group">
                            <label>Certificados</label>
                            <input type="file" name="documentos[certificado][]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" multiple>
                        </div>
                        <div class="form-group">
                            <label>Fotos de Trabajos</label>
                            <input type="file" name="documentos[foto_trabajo][]" class="form-control" accept=".jpg,.jpeg,.png" multiple>
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
</style>

