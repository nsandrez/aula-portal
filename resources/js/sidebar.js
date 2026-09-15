/**
 * Lógica interactiva para el Sidebar Escolar (Móvil y Escritorio)
 * 100% funciones y variables en español
 */

export function alternarSidebar() {
    const contenedorSidebar = document.getElementById('sidebar-movil');
    const telonFondo = document.getElementById('sidebar-backdrop');

    if (!contenedorSidebar) {
        return;
    }

    const estaOculto = contenedorSidebar.classList.contains('-translate-x-full');

    if (estaOculto) {
        contenedorSidebar.classList.remove('-translate-x-full');
        contenedorSidebar.classList.add('translate-x-0');
        if (telonFondo) {
            telonFondo.classList.remove('hidden');
        }
        document.body.classList.add('overflow-hidden');
    } else {
        cerrarSidebar();
    }
}

export function cerrarSidebar() {
    const contenedorSidebar = document.getElementById('sidebar-movil');
    const telonFondo = document.getElementById('sidebar-backdrop');

    if (contenedorSidebar) {
        contenedorSidebar.classList.remove('translate-x-0');
        contenedorSidebar.classList.add('-translate-x-full');
    }

    if (telonFondo) {
        telonFondo.classList.add('hidden');
    }

    document.body.classList.remove('overflow-hidden');
}

export function iniciarSidebar() {
    const botonAbrir = document.getElementById('boton-abrir-sidebar');
    const botonCerrar = document.getElementById('boton-cerrar-sidebar');
    const telonFondo = document.getElementById('sidebar-backdrop');

    if (botonAbrir) {
        botonAbrir.addEventListener('click', alternarSidebar);
    }

    if (botonCerrar) {
        botonCerrar.addEventListener('click', cerrarSidebar);
    }

    if (telonFondo) {
        telonFondo.addEventListener('click', cerrarSidebar);
    }

    // Cerrar con tecla Escape
    document.addEventListener('keydown', (evento) => {
        if (evento.key === 'Escape') {
            cerrarSidebar();
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', iniciarSidebar);
} else {
    iniciarSidebar();
}
