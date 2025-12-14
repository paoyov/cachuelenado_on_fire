document.addEventListener('DOMContentLoaded', function() {
    const tipoUsuario = document.querySelectorAll('input[name="tipo_usuario"]');
    const maestroFields = document.getElementById('maestroFields');
    const chapaGroup = document.getElementById('chapaGroup');
    
    function toggleMaestroFields(isMaestro) {
        if (isMaestro) {
            maestroFields.style.display = 'block';
            chapaGroup.style.display = 'block';
            // Hacer campos requeridos
            const aniosExp = document.getElementById('anios_experiencia');
            if (aniosExp) aniosExp.required = true;
            const dniFile = document.getElementById('dni_file');
            if (dniFile) dniFile.required = true;
        } else {
            maestroFields.style.display = 'none';
            chapaGroup.style.display = 'none';
            // Quitar requeridos de campos ocultos
            const aniosExp = document.getElementById('anios_experiencia');
            if (aniosExp) {
                aniosExp.required = false;
                aniosExp.value = '';
            }
            const dniFile = document.getElementById('dni_file');
            if (dniFile) {
                dniFile.required = false;
                dniFile.value = '';
            }
            // Limpiar otros campos de maestro
            const areaPreferida = document.getElementById('area_preferida');
            if (areaPreferida) areaPreferida.value = '';
            const descripcion = document.getElementById('descripcion');
            if (descripcion) descripcion.value = '';
            // Desmarcar checkboxes
            const especialidades = document.querySelectorAll('input[name="especialidades[]"]');
            especialidades.forEach(cb => cb.checked = false);
            const distritos = document.querySelectorAll('input[name="distritos[]"]');
            distritos.forEach(cb => cb.checked = false);
        }
    }
    
    tipoUsuario.forEach(radio => {
        radio.addEventListener('change', function() {
            toggleMaestroFields(this.value === 'maestro');
        });
    });
    
    // Inicializar según el tipo seleccionado por defecto
    const tipoSeleccionado = document.querySelector('input[name="tipo_usuario"]:checked');
    if (tipoSeleccionado) {
        toggleMaestroFields(tipoSeleccionado.value === 'maestro');
    }
    
    // Validación del formulario
    const form = document.getElementById('registerForm');
    form.addEventListener('submit', function(e) {
        const tipo = document.querySelector('input[name="tipo_usuario"]:checked').value;
        
        if (tipo === 'maestro') {
            // Validar años de experiencia
            const aniosExp = document.getElementById('anios_experiencia');
            if (!aniosExp.value || aniosExp.value < 0) {
                e.preventDefault();
                alert('Por favor, ingresa los años de experiencia');
                aniosExp.focus();
                return false;
            }
            
            // Validar DNI escaneado
            const dniFile = document.getElementById('dni_file');
            if (!dniFile.files || dniFile.files.length === 0) {
                e.preventDefault();
                alert('Por favor, sube una copia de tu DNI escaneado');
                dniFile.focus();
                return false;
            }
            
            // Validar especialidades
            const especialidades = document.querySelectorAll('input[name="especialidades[]"]:checked');
            if (especialidades.length === 0) {
                e.preventDefault();
                alert('Por favor, selecciona al menos una especialidad');
                return false;
            }
            
            // Validar distritos
            const distritos = document.querySelectorAll('input[name="distritos[]"]:checked');
            if (distritos.length === 0) {
                e.preventDefault();
                alert('Por favor, selecciona al menos un distrito');
                return false;
            }
        } else {
            // Si es cliente, asegurarse de que los campos de maestro no sean requeridos
            toggleMaestroFields(false);
        }
    });
});

