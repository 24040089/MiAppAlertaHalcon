// ===================================
// CALENDARIO ACADÉMICO HALCÓN
// JavaScript para funcionalidad de tabs
// ===================================

/**
 * Función para cambiar entre tabs
 * @param {number} index - Índice del tab a activar (0, 1, 2, 3)
 */
function cambiarTab(index) {
    // Obtener todos los elementos tab y tab-content
    const tabs = document.querySelectorAll('.tab');
    const contents = document.querySelectorAll('.tab-content');

    // Remover la clase 'active' de todos los tabs
    tabs.forEach(tab => {
        tab.classList.remove('active');
    });

    // Remover la clase 'active' de todos los contenidos
    contents.forEach(content => {
        content.classList.remove('active');
    });

    // Agregar clase 'active' al tab seleccionado
    tabs[index].classList.add('active');

    // Agregar clase 'active' al contenido correspondiente
    contents[index].classList.add('active');
}

/**
 * Función para auto-ocultar mensajes después de 5 segundos
 */
document.addEventListener('DOMContentLoaded', function() {
    const mensaje = document.querySelector('.mensaje');

    if (mensaje) {
        setTimeout(function() {
            mensaje.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            mensaje.style.opacity = '0';
            mensaje.style.transform = 'translateY(-20px)';

            setTimeout(function() {
                mensaje.remove();
            }, 500);
        }, 5000);
    }
});

/**
 * Función para confirmar eliminación con animación
 */
function confirmarEliminacion(tipo, id) {
    const mensaje = tipo === 'clase' ?
        '¿Estás seguro de eliminar esta clase?' :
        '¿Estás seguro de eliminar este calendario?';

    if (confirm(mensaje)) {
        const parametro = tipo === 'clase' ? 'eliminar_clase' : 'eliminar_calendario';
        window.location.href = `?${parametro}=${id}`;
        return true;
    }
    return false;
}

/**
 * Validar formulario de agregar clase
 */
function validarFormularioClase(event) {
    const checkboxes = document.querySelectorAll('input[name="dias[]"]:checked');

    if (checkboxes.length === 0) {
        event.preventDefault();
        alert('❌ Debes seleccionar al menos un día de la semana');
        return false;
    }

    const horaInicio = document.querySelector('input[name="hora_inicio"]').value;
    const horaFin = document.querySelector('input[name="hora_fin"]').value;

    if (horaInicio && horaFin && horaInicio >= horaFin) {
        event.preventDefault();
        alert('❌ La hora de fin debe ser mayor que la hora de inicio');
        return false;
    }

    return true;
}

/**
 * Validar archivo antes de subir
 */
function validarArchivoCalendario(input) {
    const archivo = input.files[0];

    if (archivo) {
        const extension = archivo.name.split('.').pop().toLowerCase();
        const extensionesPermitidas = ['pdf', 'jpg', 'jpeg', 'png'];

        if (!extensionesPermitidas.includes(extension)) {
            alert('❌ Solo se permiten archivos PDF, JPG o PNG');
            input.value = '';
            return false;
        }

        // Validar tamaño (máximo 10MB)
        const tamañoMaximo = 10 * 1024 * 1024; // 10MB en bytes
        if (archivo.size > tamañoMaximo) {
            alert('❌ El archivo es demasiado grande. Máximo 10MB');
            input.value = '';
            return false;
        }

        // Mostrar preview del nombre
        console.log('✅ Archivo válido:', archivo.name);
    }

    return true;
}

/**
 * Agregar event listeners cuando el DOM esté listo
 */
document.addEventListener('DOMContentLoaded', function() {

    // Agregar validación al formulario de clase
    const formClase = document.querySelector('form[action=""][method="POST"]');
    if (formClase && formClase.querySelector('input[name="agregar_clase"]')) {
        formClase.addEventListener('submit', validarFormularioClase);
    }

    // Agregar validación al input de archivo
    const inputArchivo = document.querySelector('input[name="archivo_calendario"]');
    if (inputArchivo) {
        inputArchivo.addEventListener('change', function() {
            validarArchivoCalendario(this);
        });
    }

    // Animación suave al hacer hover en las clases
    const clasesItems = document.querySelectorAll('.clase-item');
    clasesItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });

        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });

    console.log('✅ Calendario Académico Halcón cargado correctamente');
});

/**
 * Función para mostrar vista previa del color seleccionado
 */
function actualizarVistaColor() {
    const inputColor = document.querySelector('input[name="color"]');
    if (inputColor) {
        inputColor.addEventListener('change', function() {
            console.log('Color seleccionado:', this.value);
        });
    }
}

// Ejecutar cuando el documento esté listo
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', actualizarVistaColor);
} else {
    actualizarVistaColor();
}