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
 * Modal «Asignar apoderado»: 1) buscar/elegir apoderado por RUT o nombre, 2) buscar hijo por RUT o nombre, 3) revisar y guardar.
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

    // Elementos del buscador de apoderado
    const campoBusquedaApoderado = formulario.querySelector('[data-campo-busqueda-apoderado]');
    const mensajeBusquedaApoderado = formulario.querySelector('[data-mensaje-busqueda-apoderado]');
    const listaResultadosApoderado = formulario.querySelector('[data-resultados-busqueda-apoderado]');
    const bloqueBusquedaApoderado = formulario.querySelector('[data-bloque-busqueda-apoderado]');
    const tarjetaApoderadoSeleccionado = formulario.querySelector('[data-apoderado-seleccionado]');
    const apoderadoNombre = formulario.querySelector('[data-apoderado-nombre]');
    const apoderadoDetalle = formulario.querySelector('[data-apoderado-detalle]');
    const botonCambiarApoderado = formulario.querySelector('[data-boton-cambiar-apoderado]');

    const seleccionados = new Map();
    let temporizadorEstudiante = null;
    let ultimaBusquedaEstudiante = '';
    let temporizadorApoderado = null;
    let ultimaBusquedaApoderado = '';

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

    // Lógica de elección y cambio de apoderado
    const elegirApoderado = (apoderado) => {
        selectorApoderado.value = apoderado.id;
        if (apoderadoNombre) {
            apoderadoNombre.textContent = apoderado.nombre;
        }
        if (apoderadoDetalle) {
            const infoRUT = apoderado.rut || 'Sin RUT';
            const infoEmail = apoderado.email ? ` · ${apoderado.email}` : '';
            const infoPupilos = apoderado.pupilos_count > 0
                ? ` · ${apoderado.pupilos_count} pupilo(s) vinculado(s)`
                : ' · Sin pupilos actuales';
            apoderadoDetalle.textContent = `${infoRUT}${infoEmail}${infoPupilos}`;
        }
        if (bloqueBusquedaApoderado) {
            bloqueBusquedaApoderado.classList.add('hidden');
        }
        if (tarjetaApoderadoSeleccionado) {
            tarjetaApoderadoSeleccionado.classList.remove('hidden');
        }
        if (listaResultadosApoderado) {
            listaResultadosApoderado.replaceChildren();
        }

        pasoBusqueda.disabled = false;
        campoBusqueda.focus();
        actualizarSeleccion();
    };

    const deseleccionarApoderado = () => {
        selectorApoderado.value = '';
        if (tarjetaApoderadoSeleccionado) {
            tarjetaApoderadoSeleccionado.classList.add('hidden');
        }
        if (bloqueBusquedaApoderado) {
            bloqueBusquedaApoderado.classList.remove('hidden');
        }
        if (campoBusquedaApoderado) {
            campoBusquedaApoderado.value = '';
            campoBusquedaApoderado.focus();
        }
        if (mensajeBusquedaApoderado) {
            mensajeBusquedaApoderado.textContent = 'Escribe al menos 2 caracteres.';
        }
        if (listaResultadosApoderado) {
            listaResultadosApoderado.replaceChildren();
        }

        pasoBusqueda.disabled = true;
        actualizarSeleccion();
    };

    const mostrarResultadosApoderado = (apoderados) => {
        listaResultadosApoderado.replaceChildren();

        if (apoderados.length === 0) {
            mensajeBusquedaApoderado.textContent = 'No encontramos apoderados con ese RUT o nombre.';
            return;
        }

        mensajeBusquedaApoderado.textContent = `${apoderados.length} resultado(s). Presiona «Elegir» en el apoderado correcto.`;

        apoderados.forEach((apoderado) => {
            const item = crearElemento('li', 'flex items-center justify-between gap-3 p-3 hover:bg-slate-50');
            const datos = crearElemento('span');
            const infoRUT = apoderado.rut || 'Sin RUT';
            const infoPupilos = apoderado.pupilos_count > 0
                ? `${apoderado.pupilos_count} pupilo(s)`
                : 'Sin pupilos';
            datos.append(
                crearElemento('span', 'block font-medium text-slate-900', apoderado.nombre),
                crearElemento('span', 'block text-sm text-slate-500', `${infoRUT} · ${apoderado.email} · ${infoPupilos}`),
            );

            const botonElegir = crearElemento('button', 'boton-primario min-h-10 px-3 text-sm shrink-0', 'Elegir');
            botonElegir.type = 'button';
            botonElegir.addEventListener('click', () => elegirApoderado(apoderado));

            item.append(datos, botonElegir);
            listaResultadosApoderado.append(item);
        });
    };

    const buscarApoderados = async (texto) => {
        ultimaBusquedaApoderado = texto;
        mensajeBusquedaApoderado.textContent = 'Buscando apoderados…';

        try {
            const url = new URL(formulario.dataset.urlBusquedaApoderados, window.location.origin);
            url.searchParams.set('busqueda', texto);
            const respuesta = await fetch(url, {
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            });
            const datos = await respuesta.json();

            if (texto !== ultimaBusquedaApoderado) {
                return;
            }

            if (!respuesta.ok) {
                listaResultadosApoderado.replaceChildren();
                mensajeBusquedaApoderado.textContent = datos.message || 'No se pudo buscar. Intenta nuevamente.';
                return;
            }

            mostrarResultadosApoderado(datos.apoderados || []);
        } catch {
            mensajeBusquedaApoderado.textContent = 'No se pudo buscar. Revisa tu conexión e intenta nuevamente.';
        }
    };

    const mostrarResultadosEstudiantes = (estudiantes) => {
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

    const buscarEstudiantes = async (texto) => {
        ultimaBusquedaEstudiante = texto;
        mensajeBusqueda.textContent = 'Buscando…';

        try {
            const url = new URL(formulario.dataset.urlBusqueda, window.location.origin);
            url.searchParams.set('busqueda', texto);
            const respuesta = await fetch(url, {
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            });
            const datos = await respuesta.json();

            if (texto !== ultimaBusquedaEstudiante) {
                return;
            }

            if (!respuesta.ok) {
                listaResultados.replaceChildren();
                mensajeBusqueda.textContent = datos.message || 'No se pudo buscar. Intenta nuevamente.';
                return;
            }

            mostrarResultadosEstudiantes(datos.estudiantes || []);
        } catch {
            mensajeBusqueda.textContent = 'No se pudo buscar. Revisa tu conexión e intenta nuevamente.';
        }
    };

    // Eventos para buscador de apoderados
    if (campoBusquedaApoderado) {
        campoBusquedaApoderado.addEventListener('input', () => {
            clearTimeout(temporizadorApoderado);
            const texto = campoBusquedaApoderado.value.trim();

            if (texto.length < 2) {
                ultimaBusquedaApoderado = '';
                listaResultadosApoderado.replaceChildren();
                mensajeBusquedaApoderado.textContent = 'Escribe al menos 2 caracteres.';
                return;
            }

            temporizadorApoderado = setTimeout(() => buscarApoderados(texto), 300);
        });

        campoBusquedaApoderado.addEventListener('keydown', (evento) => {
            if (evento.key === 'Enter') {
                evento.preventDefault();
            }
        });
    }

    if (botonCambiarApoderado) {
        botonCambiarApoderado.addEventListener('click', deseleccionarApoderado);
    }

    // Compatibilidad en caso de que exista un select tradicional
    if (selectorApoderado && selectorApoderado.tagName === 'SELECT') {
        selectorApoderado.addEventListener('change', () => {
            pasoBusqueda.disabled = !selectorApoderado.value;
            if (selectorApoderado.value) {
                campoBusqueda.focus();
            }
            actualizarSeleccion();
        });
    }

    // Eventos para buscador de estudiantes
    campoBusqueda.addEventListener('input', () => {
        clearTimeout(temporizadorEstudiante);
        const texto = campoBusqueda.value.trim();

        if (texto.length < 3) {
            ultimaBusquedaEstudiante = '';
            listaResultados.replaceChildren();
            mensajeBusqueda.textContent = 'Escribe al menos 3 caracteres.';
            return;
        }

        temporizadorEstudiante = setTimeout(() => buscarEstudiantes(texto), 300);
    });

    // Enter en el buscador de estudiante no debe enviar el formulario
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
