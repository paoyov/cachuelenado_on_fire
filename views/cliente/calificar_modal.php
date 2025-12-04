<!-- Modal de Calificación -->
<div id="ratingModal" class="modal" style="display:none;">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Calificar Trabajo Realizado</h3>
            <span class="close-modal" onclick="closeRatingModal()">&times;</span>
        </div>
        <div class="modal-body">
            <div class="text-center mb-4">
                <img id="rating-master-img" src="" alt="Maestro" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                <h4 id="rating-master-name" class="mt-2"></h4>
                <p class="text-muted">¿Qué tal estuvo el servicio?</p>
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

<style>
.rating-group {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.star-rating {
    color: #ddd;
    cursor: pointer;
    font-size: 1.2rem;
}
.star-rating .fas.active {
    color: #ffc107;
}
.star-rating .fas:hover,
.star-rating .fas:hover ~ .fas {
    color: #ddd;
}
.star-rating:hover .fas {
    color: #ffc107;
}
</style>

<script>
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

document.getElementById('ratingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('<?php echo BASE_URL; ?>cliente/calificar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('¡Gracias por tu calificación!');
            closeRatingModal();
            location.reload();
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
