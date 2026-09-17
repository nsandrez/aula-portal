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

/**
 * Crea un elemento con clases y texto (siempre como texto, nunca como HTML).
 */
function crearElemento(etiqueta, clases = '', texto = '') {
    const elemento = document.createElement(etiqueta);
    elemento.className = clases;
    elemento.textContent = texto;
    return elemento;
}

/**
 * Modal «Asignar apoderado»: 1) elegir apoderado, 2) buscar hijo por RUT o nombre, 3) revisar y guardar.
 */
export function iniciarVinculoApoderado(formulario) {
    const selectorApoderado = formulario.querySelector('[data-selector-apoderado]');
    const pasoBusqueda = formulario.querySelector('[data-paso-busqueda]');
    const campoBusqueda = formulario.querySelector('[data-campo-busqueda]');
    const mensajeBusqueda = formulario.querySelector('[data-mensaje-busqueda]');
    const listaResultados = formulario.querySelector('[data-resultados-busqueda]');
    const pasoSeleccion = formulario.querySelector('[data-paso-seleccion]');
    const listaSeleccionados = formulario.querySelector('[data-lista-seleccionados]');
    const botonGuardar = formulario.querySelector('[data-boton-guardar-vinculo]');
    const seleccionados = new Map();
    let temporizador = null;
    let ultimaBusqueda = '';

    const actualizarSeleccion = () => {
        listaSeleccionados.replaceChildren();

        seleccionados.forEach((estudiante, estudianteId) => {
            const item = crearElemento('li', 'flex items-center justify-between gap-3 rounded-xl border border-marca-100 bg-marca-50 p-3');
            const datos = crearElemento('span');
            datos.append(
                crearElemento('span', 'block font-medium text-slate-900', estudiante.nombre),
                crearElemento('span', 'block text-sm text-slate-600', `${estudiante.rut || 'Sin RUT'} · ${estudiante.curso}`),
            );

            const campoOculto = document.createElement('input');
            campoOculto.type = 'hidden';
            campoOculto.name = 'estudiante_ids[]';
            campoOculto.value = estudianteId;

            const botonQuitar = crearElemento('button', 'boton-secundario min-h-10 px-3 text-sm', 'Quitar');
            botonQuitar.type = 'button';
            botonQuitar.addEventListener('click', () => {
                seleccionados.delete(estudianteId);
                actualizarSeleccion();
            });

            item.append(datos, campoOculto, botonQuitar);
            listaSeleccionados.append(item);
        });

        pasoSeleccion.classList.toggle('hidden', seleccionados.size === 0);
        botonGuardar.disabled = !selectorApoderado.value || seleccionados.size === 0;
    };

    const mostrarResultados = (estudiantes) => {
        listaResultados.replaceChildren();

        if (estudiantes.length === 0) {
            mensajeBusqueda.textContent = 'No encontramos estudiantes con ese RUT o nombre.';
            return;
        }

        mensajeBusqueda.textContent = `${estudiantes.length} resultado(s). Presiona «Agregar» en el estudiante correcto.`;

        estudiantes.forEach((estudiante) => {
            const estudianteId = String(estudiante.estudiante_id);
            const yaElegido = seleccionados.has(estudianteId);
            const item = crearElemento('li', 'flex items-center justify-between gap-3 p-3');
            const datos = crearElemento('span');
            datos.append(
                crearElemento('span', 'block font-medium text-slate-900', estudiante.nombre),
                crearElemento(
                    'span',
                    'block text-sm text-slate-500',
                    `${estudiante.rut || 'Sin RUT'} · ${estudiante.curso} · Apoderado actual: ${estudiante.apoderado_actual || 'ninguno'}`,
                ),
            );

            const botonAgregar = crearElemento('button', yaElegido ? 'boton-secundario min-h-10 px-3 text-sm' : 'boton-primario min-h-10 px-3 text-sm', yaElegido ? 'Agregado' : 'Agregar');
            botonAgregar.type = 'button';
            botonAgregar.disabled = yaElegido;
            botonAgregar.addEventListener('click', () => {
                seleccionados.set(estudianteId, estudiante);
                actualizarSeleccion();
                listaResultados.replaceChildren();
                campoBusqueda.value = '';
                mensajeBusqueda.textContent = 'Listo. Si tiene otro hijo, búscalo aquí.';
                campoBusqueda.focus();
            });

            item.append(datos, botonAgregar);
            listaResultados.append(item);
        });
    };

    const buscar = async (texto) => {
        ultimaBusqueda = texto;
        mensajeBusqueda.textContent = 'Buscando…';

        try {
            const url = new URL(formulario.dataset.urlBusqueda, window.location.origin);
            url.searchParams.set('busqueda', texto);
            const respuesta = await fetch(url, {
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            });
            const datos = await respuesta.json();

            if (texto !== ultimaBusqueda) {
                return;
            }

            if (!respuesta.ok) {
                listaResultados.replaceChildren();
                mensajeBusqueda.textContent = datos.message || 'No se pudo buscar. Intenta nuevamente.';
                return;
            }

            mostrarResultados(datos.estudiantes || []);
        } catch {
            mensajeBusqueda.textContent = 'No se pudo buscar. Revisa tu conexión e intenta nuevamente.';
        }
    };

    selectorApoderado.addEventListener('change', () => {
        pasoBusqueda.disabled = !selectorApoderado.value;
        if (selectorApoderado.value) {
            campoBusqueda.focus();
        }
        actualizarSeleccion();
    });

    campoBusqueda.addEventListener('input', () => {
        clearTimeout(temporizador);
        const texto = campoBusqueda.value.trim();

        if (texto.length < 3) {
            ultimaBusqueda = '';
            listaResultados.replaceChildren();
            mensajeBusqueda.textContent = 'Escribe al menos 3 caracteres.';
            return;
        }

        temporizador = setTimeout(() => buscar(texto), 300);
    });

    // Enter en el buscador no debe enviar el formulario
    campoBusqueda.addEventListener('keydown', (evento) => {
        if (evento.key === 'Enter') {
            evento.preventDefault();
        }
    });
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

    document.querySelectorAll('form[data-vincular-apoderado]').forEach(iniciarVinculoApoderado);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', iniciarMatriculas);
} else {
    iniciarMatriculas();
}
