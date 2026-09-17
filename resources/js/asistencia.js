/**
 * Botón "Marcar a todos presentes" del pase de lista.
 */
export function marcarTodosPresentes() {
    document.querySelectorAll('.estado-presente').forEach((opcionPresente) => {
        opcionPresente.checked = true;
    });
}

export function iniciarAsistencia() {
    document.querySelectorAll('[data-marcar-todos-presentes]').forEach((boton) => {
        boton.addEventListener('click', marcarTodosPresentes);
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', iniciarAsistencia);
} else {
    iniciarAsistencia();
}
