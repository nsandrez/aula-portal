@props(['ruta', 'icono', 'titulo'])

<a href="{{ $ruta }}" class="tarjeta group flex items-center gap-4 transition-colors hover:border-marca-600 hover:bg-marca-50">
    <span class="flex size-14 shrink-0 items-center justify-center rounded-xl bg-marca-50 text-marca-600 group-hover:bg-white">
        <x-icono :nombre="$icono" clase="size-7" />
    </span>
    <span class="min-w-0 flex-1">
        <span class="block text-lg font-semibold text-slate-900">{{ $titulo }}</span>
        <span class="block text-base text-slate-600">{{ $slot }}</span>
    </span>
    <x-icono nombre="flecha" clase="size-5 shrink-0 text-slate-400 group-hover:text-marca-600" />
</a>
