{{-- Botones para que el apoderado elija a cuál de sus hijos quiere ver --}}
@if($pupilos->count() > 1)
    <nav class="tarjeta" aria-label="Elegir hijo o hija">
        <p class="mb-3 text-base font-medium text-slate-800">¿A quién quieres revisar?</p>
        <div class="flex flex-wrap gap-3">
            @foreach($pupilos as $pupilo)
                @php($estaSeleccionado = $pupiloSeleccionado?->estudiante_id === $pupilo->estudiante_id)
                <a href="{{ route($rutaModulo, ['pupilo_id' => $pupilo->estudiante_id]) }}"
                   @if($estaSeleccionado) aria-current="true" @endif
                   class="{{ $estaSeleccionado ? 'boton-primario' : 'boton-secundario' }}">
                    {{ $pupilo->estudiante?->name }}
                    <span class="font-normal opacity-80">({{ $pupilo->curso?->nombre }})</span>
                </a>
            @endforeach
        </div>
    </nav>
@endif
