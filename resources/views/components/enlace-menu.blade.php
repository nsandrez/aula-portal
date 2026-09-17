@props(['ruta', 'activo' => false, 'icono'])

<a href="{{ $ruta }}"
   @if($activo) aria-current="page" @endif
   {{ $attributes->class([
       'flex min-h-12 items-center gap-3 rounded-xl px-4 text-base font-medium transition-colors',
       'bg-marca-50 text-marca-800' => $activo,
       'text-slate-700 hover:bg-slate-100' => ! $activo,
   ]) }}>
    <x-icono :nombre="$icono" :clase="$activo ? 'size-6 shrink-0 text-marca-600' : 'size-6 shrink-0 text-slate-400'" />
    <span>{{ $slot }}</span>
</a>
