@props(['id', 'titulo'])

<div id="{{ $id }}" class="modal-fondo hidden" role="dialog" aria-modal="true" aria-labelledby="{{ $id }}-titulo">
    <div class="modal-caja">
        <div class="mb-5 flex items-start justify-between gap-4">
            <h2 id="{{ $id }}-titulo" class="text-xl font-bold text-slate-900">{{ $titulo }}</h2>
            <button type="button" data-cerrar-modal="{{ $id }}" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" aria-label="Cerrar ventana">
                <x-icono nombre="cerrar" clase="size-6" />
            </button>
        </div>
        {{ $slot }}
    </div>
</div>
