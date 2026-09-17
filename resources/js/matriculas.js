import { abrirModal } from './modales';

/**
 * Cambia el formulario de matrícula entre "estudiante que ya tiene cuenta" y "estudiante nuevo".
 */
export function cambiarTipoMatricula(tipo) {
    const campoTipo = document.getElementById('matricula-tipo-registro');

    if (!campoTipo) {
        return;
    }

    const esNuevo = tipo === 'nuevo';
    campoTipo.value = esNuevo ? 'nuevo' : 'existente';

    document.getElementById('seccion-alumno-existente')?.classList.toggle('hidden', esNuevo);
    document.getElementById('seccion-alumno-nuevo')?.classList.toggle('hidden', !esNuevo);
    document.getElementById('matricula-estudiante')?.toggleAttribute('required', !esNuevo);
    document.getElementById('nuevo-alumno-nombre')?.toggleAttribute('required', esNuevo);
    document.getElementById('nuevo-alumno-email')?.toggleAttribute('required', esNuevo);

    document.querySelectorAll('[data-opcion-tipo-matricula]').forEach((opcion) => {
        const estaElegida = opcion.dataset.opcionTipoMatricula === campoTipo.value;
        opcion.setAttribute('aria-pressed', estaElegida ? 'true' : 'false');
        opcion.classList.toggle('boton-primario', estaElegida);
        opcion.classList.toggle('boton-secundario', !estaElegida);
    });
}

export function editarMatricula(matricula) {
    const formulario = document.getElementById('form-editar-matricula');

    if (!formulario) {
        return;
    }

    formulario.action = `/matriculas/${matricula.id}`;
    document.getElementById('editar-matricula-lista').value = matricula.numero_lista;
    document.getElementById('editar-matricula-estado').value = matricula.estado;
    document.getElementById('editar-matricula-apoderado').value = matricula.apoderado_id || '';
    abrirModal('modal-editar-matricula');
}

export function iniciarMatriculas() {
    document.querySelectorAll('[data-abrir-matricula]').forEach((boton) => {
        boton.addEventListener('click', () => {
            cambiarTipoMatricula(boton.dataset.abrirMatricula);
            abrirModal('modal-nueva-matricula');
        });
    });

    document.querySelectorAll('[data-opcion-tipo-matricula]').forEach((opcion) => {
        opcion.addEventListener('click', () => cambiarTipoMatricula(opcion.dataset.opcionTipoMatricula));
    });

    document.querySelectorAll('[data-editar-matricula]').forEach((boton) => {
        boton.addEventListener('click', () => editarMatricula(JSON.parse(boton.dataset.editarMatricula)));
    });

    document.querySelectorAll('[data-marcar-alumnos]').forEach((boton) => {
        boton.addEventListener('click', () => {
            const marcar = boton.dataset.marcarAlumnos === 'si';
            document.querySelectorAll('.casilla-alumno').forEach((casilla) => {
                casilla.checked = marcar;
            });
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', iniciarMatriculas);
} else {
    iniciarMatriculas();
}
