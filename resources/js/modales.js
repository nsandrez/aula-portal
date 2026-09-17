/**
 * Gestión interactiva de modales y formularios escolares en español.
 */

export function abrirModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }
}

export function cerrarModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
    }
}

window.abrirModal = abrirModal;
window.cerrarModal = cerrarModal;

export function iniciarModales() {
    // Abrir modales vía atributo data-abrir-modal
    document.querySelectorAll('[data-abrir-modal]').forEach((boton) => {
        boton.addEventListener('click', () => {
            const modalId = boton.getAttribute('data-abrir-modal');
            abrirModal(modalId);
        });
    });

    // Cerrar modales vía atributo data-cerrar-modal
    document.querySelectorAll('[data-cerrar-modal]').forEach((boton) => {
        boton.addEventListener('click', () => {
            const modalId = boton.getAttribute('data-cerrar-modal');
            cerrarModal(modalId);
        });
    });

    // Cerrar al hacer clic en el telón de fondo (backdrop)
    document.querySelectorAll('.modal-fondo').forEach((modal) => {
        modal.addEventListener('click', (evento) => {
            if (evento.target === modal) {
                cerrarModal(modal.id);
            }
        });
    });

    // Cerrar con la tecla Escape
    document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Escape') {
            document.querySelectorAll('.modal-fondo.flex').forEach((modal) => {
                cerrarModal(modal.id);
            });
        }
    });

    // Manejo de edición de Curso (SuperUsuario)
    document.querySelectorAll('[data-editar-curso]').forEach((boton) => {
        boton.addEventListener('click', () => {
            const curso = JSON.parse(boton.getAttribute('data-editar-curso'));
            const formulario = document.getElementById('form-editar-curso');
            if (formulario) {
                formulario.action = `/cursos/${curso.id}`;
                const inputNombre = document.getElementById('editar-curso-nombre');
                const inputNivel = document.getElementById('editar-curso-nivel');
                const inputAnio = document.getElementById('editar-curso-anio');
                const selectProfesor = document.getElementById('editar-curso-profesor');

                if (inputNombre) inputNombre.value = curso.nombre;
                if (inputNivel) inputNivel.value = curso.nivel;
                if (inputAnio) inputAnio.value = curso.anio;
                if (selectProfesor) selectProfesor.value = curso.profesor_jefe_id || '';

                abrirModal('modal-editar-curso');
            }
        });
    });

    // Manejo de edición de Usuario (SuperUsuario)
    document.querySelectorAll('[data-editar-usuario]').forEach((boton) => {
        boton.addEventListener('click', () => {
            const usuario = JSON.parse(boton.getAttribute('data-editar-usuario'));
            const formulario = document.getElementById('form-editar-usuario');
            if (formulario) {
                formulario.action = `/usuarios/${usuario.id}`;
                const inputId = document.getElementById('editar-usuario-id');
                const inputNombre = document.getElementById('editar-usuario-nombre');
                const inputEmail = document.getElementById('editar-usuario-email');
                const inputRut = document.getElementById('editar-usuario-rut');
                const selectRol = document.getElementById('editar-usuario-rol');

                if (inputId) inputId.value = usuario.id;
                if (inputNombre) inputNombre.value = usuario.name;
                if (inputEmail) inputEmail.value = usuario.email;
                if (inputRut) inputRut.value = usuario.rut || '';
                if (selectRol) selectRol.value = usuario.rol || 'estudiante';

                abrirModal('modal-editar-usuario');
            }
        });
    });
}

/**
 * Ajusta el destino del formulario "Agregar asignatura" según el curso elegido.
 */
export function actualizarCursoAsociado(cursoId) {
    const formulario = document.getElementById('form-asociar-asignatura');
    const selectorCurso = document.getElementById('asociar-curso');

    if (!formulario || !selectorCurso) {
        return;
    }

    selectorCurso.value = cursoId || '';
    const opcionElegida = selectorCurso.selectedOptions[0];
    formulario.action = opcionElegida?.dataset.accion ?? '';
}

export function iniciarAsociacionAsignaturas() {
    const selectorCurso = document.getElementById('asociar-curso');

    if (selectorCurso) {
        selectorCurso.addEventListener('change', () => actualizarCursoAsociado(selectorCurso.value));
    }

    document.querySelectorAll('[data-asociar-en-curso]').forEach((boton) => {
        boton.addEventListener('click', () => {
            actualizarCursoAsociado(boton.dataset.asociarEnCurso);
            abrirModal('modal-asociar-asignatura');
        });
    });

    // Pide confirmación antes de enviar formularios delicados (ej. quitar asignatura)
    document.querySelectorAll('form[data-confirmar]').forEach((formulario) => {
        formulario.addEventListener('submit', (evento) => {
            if (!window.confirm(formulario.dataset.confirmar)) {
                evento.preventDefault();
            }
        });
    });
}

// Iniciar al cargar el DOM
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        iniciarModales();
        iniciarAsociacionAsignaturas();
    });
} else {
    iniciarModales();
    iniciarAsociacionAsignaturas();
}
