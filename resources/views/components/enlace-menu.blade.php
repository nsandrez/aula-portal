@props(['ruta', 'activo' => false, 'icono'])

<a href="{{ $ruta }}"
   @if($activo) aria-current="page" @endif
   {{ $attributes->class([
       'group flex min-h-11 items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition-all',
       'bg-amber-500 text-slate-950 font-bold shadow-md shadow-amber-500/20' => $activo,
       'text-slate-300 hover:text-white hover:bg-slate-800/80' => ! $activo,
   ]) }}>
    <x-icono :nombre="$icono" :clase="$activo ? 'size-5 shrink-0 text-slate-950' : 'size-5 shrink-0 text-slate-400 group-hover:text-slate-200 transition-colors'" />
    <span>{{ $slot }}</span>
</a>
