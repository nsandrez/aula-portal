@props(['titulo', 'valor'])

<div class="tarjeta">
    <p class="text-base text-slate-600">{{ $titulo }}</p>
    <p class="mt-1 text-3xl font-bold text-slate-900">{{ $valor }}</p>
    @if($slot->isNotEmpty())
        <p class="mt-1 text-sm text-slate-500">{{ $slot }}</p>
    @endif
</div>
