@extends('layouts.app')

@section('titulo', 'Control de Asistencias')
@section('encabezado', 'Control Diario de Asistencias')

@section('contenido')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Encabezado en Tarjeta Blanca -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 rounded-2xl bg-white border border-slate-200 shadow-xs">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200 mb-2">
                <span>Registro Oficial de Asistencia</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
                    Control de Asistencia del Día a Día
                @elseif($usuario?->esEstudiante())
                    Mi Asistencia Escolar Diaria
                @else
                    Asistencia Diaria de Pupilos
                @endif
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">
                @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
                    Pase de lista diario por curso con control de hora de atraso, faltas justificadas e inasistencias.
                @elseif($usuario?->esEstudiante())
                    Revisión de tu asistencia por jornada escolar del año lectivo 2026.
                @else
                    Monitoreo diario del ingreso y permanencia escolar de tus pupilos.
                @endif
            </p>
        </div>

        @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
            <div class="flex items-center gap-2.5">
                <button type="button" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-xs transition-colors cursor-pointer">
                    Guardar Lista del Día
                </button>
            </div>
        @endif
    </div>

    @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
        <!-- Barra de Selección de Curso y Fecha ("Día a Día") -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
            <form method="GET" action="{{ route('asistencias.index') }}" class="flex flex-wrap items-center gap-3 w-full">
                <!-- Selector de Curso -->
                <div class="flex items-center gap-2">
                    <label for="curso_id" class="text-xs font-semibold text-slate-600">Curso:</label>
                    <select id="curso_id" name="curso_id" onchange="this.form.submit()" class="px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:border-amber-500">
                        @foreach($cursos as $c)
                            <option value="{{ $c->id }}" {{ $cursoSeleccionado?->id === $c->id ? 'selected' : '' }}>
                                {{ $c->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Selector de Fecha ("Día a Día") -->
                <div class="flex items-center gap-2">
                    <label for="fecha" class="text-xs font-semibold text-slate-600">Fecha del Día:</label>
                    <input type="date" id="fecha" name="fecha" value="{{ $fecha }}" onchange="this.form.submit()" class="px-3 py-1.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono font-medium text-slate-800 focus:outline-none focus:border-amber-500">
                </div>

                <div class="ml-auto text-xs text-slate-500">
                    Fecha activa: <strong class="text-slate-900 font-semibold">{{ \Carbon\Carbon::parse($fecha)->translatedFormat('l, d \d\e F \d\e Y') }}</strong>
                </div>
            </form>
        </div>

        <!-- Métricas del Día Seleccionado -->
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs">
                <span class="text-[11px] font-semibold text-emerald-700 uppercase tracking-wider">Presentes</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $resumen['presentes'] }}</p>
                <p class="text-[11px] text-slate-500">Alumnos a tiempo</p>
            </div>
            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs">
                <span class="text-[11px] font-semibold text-amber-700 uppercase tracking-wider">Atrasos</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $resumen['atrasos'] }}</p>
                <p class="text-[11px] text-slate-500">Con pase de hora</p>
            </div>
            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs">
                <span class="text-[11px] font-semibold text-blue-700 uppercase tracking-wider">Justificados</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $resumen['justificados'] }}</p>
                <p class="text-[11px] text-slate-500">Con certificado</p>
            </div>
            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs">
                <span class="text-[11px] font-semibold text-red-700 uppercase tracking-wider">Ausentes</span>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $resumen['ausentes'] }}</p>
                <p class="text-[11px] text-slate-500">Sin justificar</p>
            </div>
            <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs col-span-2 sm:col-span-1">
                <span class="text-[11px] font-semibold text-slate-600 uppercase tracking-wider">% Día</span>
                <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $resumen['porcentaje_asistencia'] }}%</p>
                <p class="text-[11px] text-slate-500">Asistencia efectiva</p>
            </div>
        </div>

        <!-- Tabla de Asistencia del Día a Día -->
        <div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">
                    Nómina Diaria: {{ $cursoSeleccionado?->nombre }}
                </h3>
                <span class="text-xs text-slate-500 font-mono">{{ $asistencias->count() }} alumnos evaluados hoy</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-100/70 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3 font-semibold">N°</th>
                            <th class="px-5 py-3 font-semibold">Estudiante</th>
                            <th class="px-5 py-3 font-semibold">RUT</th>
                            <th class="px-5 py-3 text-center font-semibold">Estado del Día</th>
                            <th class="px-5 py-3 font-semibold">Hora de Ingreso</th>
                            <th class="px-5 py-3 font-semibold">Observación</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($asistencias as $indice => $registro)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-5 py-3.5 font-mono text-slate-400 font-medium">
                                    {{ str_pad((string)($indice + 1), 2, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-5 py-3.5 font-bold text-slate-900">
                                    {{ $registro->estudiante?->name }}
                                </td>
                                <td class="px-5 py-3.5 font-mono text-slate-500">
                                    {{ $registro->estudiante?->rut }}
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    @if($registro->estado === 'presente')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                            Presente
                                        </span>
                                    @elseif($registro->estado === 'atraso')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                            Atraso
                                        </span>
                                    @elseif($registro->estado === 'justificado')
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                            Justificado
                                        </span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                            Ausente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 font-mono text-slate-600">
                                    {{ $registro->hora_llegada ? substr($registro->hora_llegada, 0, 5) . ' hrs' : '-' }}
                                </td>
                                <td class="px-5 py-3.5 text-slate-500 text-xs">
                                    {{ $registro->observacion ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-slate-500 text-xs">
                                    No hay registros de asistencia para la fecha seleccionada ({{ $fecha }}).
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    @elseif($usuario?->esEstudiante())
        <!-- Vista para Estudiante en Blanco Institucional -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Mi Asistencia Acumulada</span>
                <p class="text-3xl font-bold text-emerald-600 mt-2">{{ $porcentajeEstudiante }}%</p>
                <p class="text-xs text-slate-500 mt-1">Mínimo legal de aprobación: 85%</p>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Jornadas Evaluadas</span>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $historialEstudiante?->count() ?? 0 }} Días</p>
                <p class="text-xs text-slate-500 mt-1">Registros del primer semestre</p>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado de Regularidad</span>
                <p class="text-2xl font-bold text-emerald-600 mt-2">Alumno Regular</p>
                <p class="text-xs text-slate-500 mt-1">Al día con los requerimientos</p>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-slate-200 shadow-xs p-5 space-y-4">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Historial Reciente Día a Día</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-100/70 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Fecha</th>
                            <th class="px-5 py-3 font-semibold">Hora de Ingreso</th>
                            <th class="px-5 py-3 font-semibold">Estado</th>
                            <th class="px-5 py-3 font-semibold">Observación</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($historialEstudiante ?? [] as $asistencia)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-5 py-3.5 font-mono text-slate-900 font-semibold">
                                    {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}
                                </td>
                                <td class="px-5 py-3.5 font-mono text-slate-600">
                                    {{ $asistencia->hora_llegada ? substr($asistencia->hora_llegada, 0, 5) . ' hrs' : '-' }}
                                </td>
                                <td class="px-5 py-3.5">
                                    @if($asistencia->estado === 'presente')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Presente</span>
                                    @elseif($asistencia->estado === 'atraso')
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">Atraso</span>
                                    @else
                                        <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">Justificado</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-500">
                                    {{ $asistencia->observacion ?? 'Normal' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-6 text-center text-slate-500">Sin historial de asistencias disponible.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Vista para Apoderado en Blanco Institucional -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-4">
            <h3 class="text-base font-bold text-slate-900">Control de Asistencia del Pupilo</h3>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-700 font-bold flex items-center justify-center text-sm">
                        ✓
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900">Sofía Álvarez Contreras (1° Medio A)</p>
                        <p class="text-xs text-slate-500">Asistencia Registrada Hoy: <strong class="text-emerald-700">Presente (07:55 hrs)</strong></p>
                    </div>
                </div>
                <div class="text-xs font-mono text-slate-700 bg-white px-3 py-1.5 rounded-lg border border-slate-200">
                    Asistencia General: <span class="font-bold text-emerald-600">100.0%</span>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
