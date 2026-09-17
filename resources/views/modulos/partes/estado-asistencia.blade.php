{{-- Insignia de color para el estado de asistencia de un día --}}
@switch($estado)
    @case('presente')
        <span class="insignia-verde">Presente</span>
        @break
    @case('atraso')
        <span class="insignia-amarilla">Atraso</span>
        @break
    @case('justificado')
        <span class="insignia-gris">Justificado</span>
        @break
    @default
        <span class="insignia-roja">Ausente</span>
@endswitch
