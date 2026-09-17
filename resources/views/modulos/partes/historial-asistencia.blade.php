{{-- Tabla de asistencia día a día de un estudiante --}}
@use('App\Utils\FormateadorFecha')

<div class="overflow-x-auto">
    <table class="tabla">
        <thead>
            <tr>
                <th scope="col">Día</th>
                <th scope="col">Estado</th>
                <th scope="col">Hora de llegada</th>
                <th scope="col">Comentario</th>
            </tr>
        </thead>
        <tbody>
            @forelse($historial ?? [] as $asistencia)
                <tr>
                    <td class="whitespace-nowrap">{{ FormateadorFecha::formatearFecha($asistencia->fecha) }}</td>
                    <td>@include('modulos.partes.estado-asistencia', ['estado' => $asistencia->estado])</td>
                    <td>{{ $asistencia->hora_llegada ? substr($asistencia->hora_llegada, 0, 5) : '—' }}</td>
                    <td class="text-slate-600">{{ $asistencia->observacion ?: '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="texto-ayuda">Todavía no hay días registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
