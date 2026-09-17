/**
 * Pase de lista: reloj en vivo, "marcar a todos presentes" y campos que
 * aparecen solo cuando se elige «Atraso» (hora) o «Justificado» (motivo).
 */

function horaActual(zonaHoraria) {
    return new Intl.DateTimeFormat('es-CL', {
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit',
        hour12: false,
        timeZone: zonaHoraria,
    }).format(new Date());
}

export function iniciarReloj() {
    document.querySelectorAll('[data-reloj]').forEach((reloj) => {
        const zonaHoraria = reloj.dataset.zonaHoraria || 'America/Santiago';
        const actualizar = () => {
            reloj.textContent = horaActual(zonaHoraria);
        };
        actualizar();
        setInterval(actualizar, 1000);
    });
}

export function mostrarDetalleSegunEstado(fila) {
    const estadoElegido = fila.querySelector('[data-estado-asistencia]:checked')?.value;

    fila.querySelectorAll('[data-detalle-estado]').forEach((detalle) => {
        const corresponde = detalle.dataset.detalleEstado === estadoElegido;
        detalle.classList.toggle('hidden', !corresponde);

        detalle.querySelectorAll('input').forEach((campo) => {
            campo.disabled = !corresponde;

            // Al marcar «Atraso» se propone la hora actual (HH:MM)
            if (corresponde && campo.type === 'time' && !campo.value) {
                const zonaHoraria = document.querySelector('[data-reloj]')?.dataset.zonaHoraria || 'America/Santiago';
                campo.value = horaActual(zonaHoraria).slice(0, 5);
            }
        });

        if (corresponde && detalle.dataset.detalleEstado === 'justificado') {
            detalle.querySelector('input')?.focus();
        }
    });
}

export function marcarTodosPresentes() {
    document.querySelectorAll('.estado-presente').forEach((opcionPresente) => {
        opcionPresente.checked = true;
    });
    document.querySelectorAll('[data-fila-asistencia]').forEach(mostrarDetalleSegunEstado);
}

export function iniciarAsistencia() {
    iniciarReloj();

    document.querySelectorAll('[data-marcar-todos-presentes]').forEach((boton) => {
        boton.addEventListener('click', marcarTodosPresentes);
    });

    document.querySelectorAll('[data-fila-asistencia]').forEach((fila) => {
        fila.querySelectorAll('[data-estado-asistencia]').forEach((opcion) => {
            opcion.addEventListener('change', () => mostrarDetalleSegunEstado(fila));
        });
    });

    document.querySelectorAll('select[data-enviar-al-cambiar]').forEach((selector) => {
        selector.addEventListener('change', () => selector.form?.submit());
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', iniciarAsistencia);
} else {
    iniciarAsistencia();
}
