/**
 * Filtro instantáneo para tablas.
 *
 * Uso en la vista:
 *  - Contenedor: data-filtro-tabla="id-de-la-tabla"
 *  - Buscador:   <input data-filtro-texto>
 *  - Selectores: <select data-filtro-campo="curso"> (compara con data-curso de cada fila)
 *  - Contador:   <span data-filtro-contador>
 *  - Filas:      <tr data-buscar="texto en minúsculas" data-curso="...">
 */
export function filtrarTabla(contenedor) {
    const tabla = document.getElementById(contenedor.dataset.filtroTabla);

    if (!tabla) {
        return;
    }

    const textoBuscado = (contenedor.querySelector('[data-filtro-texto]')?.value || '').toLowerCase().trim();
    const selectores = contenedor.querySelectorAll('[data-filtro-campo]');
    let filasVisibles = 0;

    tabla.querySelectorAll('tr[data-buscar]').forEach((fila) => {
        const coincideTexto = !textoBuscado || fila.dataset.buscar.includes(textoBuscado);
        const coincideSelectores = [...selectores].every((selector) => {
            return !selector.value || fila.dataset[selector.dataset.filtroCampo] === selector.value;
        });
        const esVisible = coincideTexto && coincideSelectores;

        fila.classList.toggle('hidden', !esVisible);
        filasVisibles += esVisible ? 1 : 0;
    });

    const contador = contenedor.querySelector('[data-filtro-contador]');
    if (contador) {
        contador.textContent = filasVisibles;
    }
}

export function iniciarFiltros() {
    document.querySelectorAll('[data-filtro-tabla]').forEach((contenedor) => {
        contenedor.querySelectorAll('input, select').forEach((control) => {
            control.addEventListener('input', () => filtrarTabla(contenedor));
        });
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', iniciarFiltros);
} else {
    iniciarFiltros();
}
