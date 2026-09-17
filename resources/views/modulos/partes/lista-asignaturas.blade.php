{{-- Lista simple de asignaturas con su profesor y horas semanales --}}
@if($cursoAsignaturas->isEmpty())
    <p class="mt-4 texto-ayuda">Este curso todavía no tiene asignaturas.</p>
@else
    <ul class="mt-4 divide-y divide-slate-100 border-t border-slate-100">
        @foreach($cursoAsignaturas as $cursoAsignatura)
            <li class="flex flex-col gap-1 py-3 sm:flex-row sm:items-center sm:justify-between">
                <span class="text-base font-medium text-slate-900">{{ $cursoAsignatura->asignatura?->nombre }}</span>
                <span class="text-base text-slate-600">
                    {{ $cursoAsignatura->docente?->name ?? 'Profesor por asignar' }} · {{ $cursoAsignatura->horas_semanales }} horas a la semana
                </span>
            </li>
        @endforeach
    </ul>
@endif
