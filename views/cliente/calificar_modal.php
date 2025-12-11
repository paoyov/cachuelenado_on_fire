<!-- Modal de Calificación -->
<div id="ratingModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Calificar Trabajo Realizado</h3>
            <span class="close-modal" onclick="closeRatingModal()">&times;</span>
        </div>
        <div class="modal-body">
            <!-- Selector de Maestro (para nueva calificación) -->
            <div id="maestro-selector-container" class="mb-4">
                <label class="form-label fw-bold">Selecciona el Maestro</label>
                <select id="maestro-select" class="form-control form-select" onchange="updateMaestroInfo(this)">
                    <option value="">-- Buscar Maestro --</option>
                    <!-- Options populated via JS -->
                </select>
            </div>

            <!-- Pre-visualización del Maestro -->
            <div id="maestro-info-container" class="text-center mb-4" style="display:none;">
                <img id="rating-master-img" src="" alt="Maestro" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                <h4 id="rating-master-name" class="mt-2 text-dark font-weight-bold"></h4>
                <p class="text-muted small">¿Qué tal estuvo el servicio?</p>
            </div>

            <form id="ratingForm">
                <input type="hidden" name="maestro_id" id="rating-maestro-id">
                <input type="hidden" name="trabajo_id" id="rating-trabajo-id">

                <div class="rating-group mb-3">
                    <label>Puntualidad</label>
                    <div class="star-rating" data-field="puntualidad">
                        <i class="fas fa-star" data-value="1"></i>
                        <i class="fas fa-star" data-value="2"></i>
                        <i class="fas fa-star" data-value="3"></i>
                        <i class="fas fa-star" data-value="4"></i>
                        <i class="fas fa-star" data-value="5"></i>
                        <input type="hidden" name="puntualidad" value="5">
                    </div>
                </div>

                <div class="rating-group mb-3">
                    <label>Calidad del Trabajo</label>
                    <div class="star-rating" data-field="calidad">
                        <i class="fas fa-star" data-value="1"></i>
                        <i class="fas fa-star" data-value="2"></i>
                        <i class="fas fa-star" data-value="3"></i>
                        <i class="fas fa-star" data-value="4"></i>
                        <i class="fas fa-star" data-value="5"></i>
                        <input type="hidden" name="calidad" value="5">
                    </div>
                </div>

                <div class="rating-group mb-3">
                    <label>Trato y Amabilidad</label>
                    <div class="star-rating" data-field="trato">
                        <i class="fas fa-star" data-value="1"></i>
                        <i class="fas fa-star" data-value="2"></i>
                        <i class="fas fa-star" data-value="3"></i>
                        <i class="fas fa-star" data-value="4"></i>
                        <i class="fas fa-star" data-value="5"></i>
                        <input type="hidden" name="trato" value="5">
                    </div>
                </div>

                <div class="rating-group mb-3">
                    <label>Limpieza y Orden</label>
                    <div class="star-rating" data-field="limpieza">
                        <i class="fas fa-star" data-value="1"></i>
                        <i class="fas fa-star" data-value="2"></i>
                        <i class="fas fa-star" data-value="3"></i>
                        <i class="fas fa-star" data-value="4"></i>
                        <i class="fas fa-star" data-value="5"></i>
                        <input type="hidden" name="limpieza" value="5">
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label>Comentario (Opcional)</label>
                    <textarea name="comentario" class="form-control" rows="3" placeholder="Cuéntanos más sobre tu experiencia..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary btn-block">Enviar Calificación</button>
            </form>
        </div>
    </div>
</div>

<!-- Custom Success Modal -->
<div id="successModal" class="modal" style="display:none;">
    <div class="modal-content text-center p-4">
        <div class="success-icon mb-3">
            <div class="icon-circle">
                <i class="fas fa-tools tool-icon"></i>
                <i class="fas fa-check-circle check-icon"></i>
            </div>
        </div>
        <h3 class="mb-2 text-dark font-weight-bold">¡Excelente!</h3>
        <p class="text-muted mb-4">Tu calificación ha sido registrada con éxito.</p>
        <button class="btn btn-primary btn-block w-50 mx-auto" onclick="location.reload()">Entendido</button>
    </div>
</div>

<style>

/* Modal Overlay - Centered with Flexbox */
.modal {
    display: none; /* Hidden by default */
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background-color: rgba(0,0,0,0.6); /* Darker backdrop for focus */
    backdrop-filter: blur(4px); /* Modern frosted glass effect */
    display: flex; /* Flexbox for centering */
    align-items: center;
    justify-content: center;
    /* Important for centering to work if display is toggled */ 
    /* Javascript sets display:block, which breaks flex centering if not handled. 
       We need !important or specific rule to keep flex behavior when active */
}

/* Modal Content - Modern Card Style */
.modal-content {
    background-color: #ffffff;
    margin: auto;
    padding: 0;
    border: none;
    width: 90%;
    max-width: 500px;
    max-height: 90vh; /* Limitar altura máxima */
    border-radius: 16px; /* Smooth corners */
    box-shadow: 0 15px 35px rgba(0,0,0,0.25);
    animation: slideIn 0.3s ease-out;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

/* Modal Header */
.modal-header {
    background: #FF6B35; /* Primary Orange */
    color: white;
    padding: 1.5rem;
    display: flex;
    justify-content: center;
    align-items: center;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    flex-shrink: 0; /* Evitar que el header se comprima */
    position: relative;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    text-align: center;
    flex: 1;
}

.close-modal {
    color: white;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    line-height: 1;
    opacity: 0.8;
    transition: opacity 0.2s;
    position: absolute;
    right: 1.5rem;
    top: 50%;
    transform: translateY(-50%);
}

.close-modal:hover {
    opacity: 1;
}

/* Modal Body */
.modal-body {
    padding: 2rem;
    overflow-y: auto;
    overflow-x: hidden;
    flex: 1;
    max-height: calc(90vh - 80px); /* Altura máxima menos el header */
}

/* Scrollbar personalizado para el modal */
.modal-body::-webkit-scrollbar {
    width: 8px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #FF6B35;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #e55a2b;
}

/* Form Elements */
.form-label {
    color: #444;
    font-weight: 600;
    margin-bottom: 0.5rem;
    display: block;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #ddd;
    padding: 0.75rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-control:focus, .form-select:focus {
    border-color: #FF6B35;
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
    outline: none;
}

/* Rating Groups */
.rating-group {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f9f9f9;
}

.rating-group:last-of-type {
    border-bottom: none;
}

.rating-group label {
    font-weight: 500;
    color: #555;
    margin: 0;
}

.star-rating {
    color: #e0e0e0;
    cursor: pointer;
    font-size: 1.4rem;
    transition: transform 0.2s;
}

.star-rating .fas {
    transition: color 0.2s;
}

.star-rating .fas.active {
    color: #FFC107; /* Gold Star */
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.star-rating:hover .fas {
     color: #FFC107;
}

/* Submit Button */
.btn-primary.btn-block {
    background: #FF6B35;
    border: none;
    border-radius: 8px;
    padding: 1rem;
    font-size: 1rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: 100%;
    margin-top: 1rem;
    box-shadow: 0 4px 6px rgba(255, 107, 53, 0.3);
    transition: transform 0.2s, box-shadow 0.2s;
}

.btn-primary.btn-block:hover {
    background: #e55a2b;
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(255, 107, 53, 0.4);
}

/* Maestro Preview */
#maestro-info-container img {
    border: 4px solid #fff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* Animation */
@keyframes slideIn {
    from { transform: translateY(-30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* Success Icon Styling */
.success-icon {
    display: flex;
    justify-content: center;
    position: relative;
    height: 100px;
    align-items: center;
}

.icon-circle {
    width: 80px;
    height: 80px;
    background: #e8f5e9;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
    box-shadow: 0 4px 10px rgba(76, 175, 80, 0.2);
}

.tool-icon {
    font-size: 35px;
    color: #4CAF50;
    transform: rotate(-10deg);
}

.check-icon {
    position: absolute;
    bottom: -5px;
    right: -5px;
    font-size: 28px;
    color: #2E7D32;
    background: white;
    border-radius: 50%;
    border: 2px solid white;
}

/* Enhancing Success Modal typography */
#successModal h3 {
    color: #333;
}
</style>

<script>
// ... (Existing variables) ...
// Ensure it's a valid array even if PHP returns null/false
const maestrosData = <?php echo !empty($maestros) ? json_encode($maestros) : '[]'; ?>;

// ... (Existing functions: updateMaestroInfo, updateStars) ...
function updateMaestroInfo(select) {
    const selectedOption = select.options[select.selectedIndex];
    const maestroId = selectedOption.value;
    
    document.getElementById('rating-maestro-id').value = maestroId;

    if (maestroId) {
        const name = selectedOption.getAttribute('data-name');
        const img = selectedOption.getAttribute('data-img');
        
        document.getElementById('rating-master-name').textContent = name;
        document.getElementById('rating-master-img').src = img || '<?php echo UPLOAD_URL; ?>perfiles/default.png';
        document.getElementById('maestro-info-container').style.display = 'block';
    } else {
        document.getElementById('maestro-info-container').style.display = 'none';
    }
}

document.querySelectorAll('.star-rating').forEach(group => {
    const stars = group.querySelectorAll('.fas');
    const input = group.querySelector('input');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            input.value = value;
            updateStars(group, value);
        });
    });

    // Initialize with 5 stars
    updateStars(group, 5);
});

function updateStars(group, value) {
    const stars = group.querySelectorAll('.fas');
    stars.forEach(star => {
        if (star.getAttribute('data-value') <= value) {
            star.classList.add('active');
        } else {
            star.classList.remove('active');
        }
    });
}

function closeRatingModal() {
    document.getElementById('ratingModal').style.display = 'none';
}

function openSuccessModal() {
    document.getElementById('ratingModal').style.display = 'none';
    const successModal = document.getElementById('successModal');
    // Using flex to ensure the CSS centering works correctly
    successModal.style.display = 'flex'; 
}

document.getElementById('ratingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate that a maestro is selected
    if (!document.getElementById('rating-maestro-id').value) {
        alert('Por favor, selecciona un maestro.');
        return;
    }
    
    const formData = new FormData(this);
    
    fetch('<?php echo BASE_URL; ?>cliente/calificar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Replace alert with custom modal
            openSuccessModal();
        } else {
            alert('Error: ' + (data.message || 'No se pudo guardar la calificación'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error al enviar la calificación');
    });
});
</script>
