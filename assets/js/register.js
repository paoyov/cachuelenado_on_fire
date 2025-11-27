document.addEventListener('DOMContentLoaded', function() {
    const tipoUsuario = document.querySelectorAll('input[name="tipo_usuario"]');
    const maestroFields = document.getElementById('maestroFields');
    const chapaGroup = document.getElementById('chapaGroup');
    
    tipoUsuario.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'maestro') {
                maestroFields.style.display = 'block';
                chapaGroup.style.display = 'block';
                // Hacer campos requeridos
                document.getElementById('anios_experiencia').required = true;
                const especialidades = document.querySelectorAll('input[name="especialidades[]"]');
                const distritos = document.querySelectorAll('input[name="distritos[]"]');
                // Validar que al menos una especialidad y un distrito estén seleccionados
            } else {
                maestroFields.style.display = 'none';
                chapaGroup.style.display = 'none';
                document.getElementById('anios_experiencia').required = false;
            }
        });
    });
    
    // Validación del formulario
    const form = document.getElementById('registerForm');
    form.addEventListener('submit', function(e) {
        const tipo = document.querySelector('input[name="tipo_usuario"]:checked').value;
        
        if (tipo === 'maestro') {
            const especialidades = document.querySelectorAll('input[name="especialidades[]"]:checked');
            const distritos = document.querySelectorAll('input[name="distritos[]"]:checked');
            
            if (especialidades.length === 0) {
                e.preventDefault();
                alert('Por favor, selecciona al menos una especialidad');
                return false;
            }
            
            if (distritos.length === 0) {
                e.preventDefault();
                alert('Por favor, selecciona al menos un distrito');
                return false;
            }
        }
    });
});

