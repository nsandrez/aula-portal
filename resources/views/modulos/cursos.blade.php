@extends('layouts.app')

@section('titulo', 'Cursos y Asignaturas')
@section('encabezado', 'Cursos y Asignaturas Curriculares')

@section('contenido')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Encabezado en Tarjeta Blanca -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 rounded-2xl bg-white border border-slate-200 shadow-xs">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200 mb-2">
                <span>Estructura Curricular 2026</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Cursos y sus Asignaturas Asociadas
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">
                Cada curso tiene sus respectivas asignaturas del plan de estudio vinculadas con su profesor titular y horas semanales.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Asociar Asignatura a Curso</span>
            </button>
        </div>
    </div>

    <!-- Despliegue de Cada Curso con sus Asignaturas Asociadas -->
    <div class="space-y-6">
        @forelse($cursos as $curso)
            <div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden">
                <!-- Cabecera del Curso -->
                <div class="p-5 bg-slate-50/80 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-amber-500 text-slate-950 font-bold flex items-center justify-center text-sm shadow-xs">
                            {{ substr($curso->nombre, 0, 2) }}
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900">{{ $curso->nombre }}</h3>
                            <p class="text-xs text-slate-500">
                                {{ $curso->nivel }} • Profesor(a) Jefe: 
                                <strong class="text-slate-800 font-semibold">{{ $curso->profesorJefe?->name ?? 'No asignado' }}</strong>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            <span>{{ $curso->matriculas_count }} Estudiantes</span>
                        </span>

                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                            <span>{{ $curso->cursoAsignaturas->count() }} Asignaturas Asociadas</span>
                        </span>
                    </div>
                </div>

                <!-- Tabla de Asignaturas que componen este curso -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-100/70 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="px-5 py-3 font-semibold">Código</th>
                                <th class="px-5 py-3 font-semibold">Asignatura del Curso</th>
                                <th class="px-5 py-3 font-semibold">Docente Titular a Cargo</th>
                                <th class="px-5 py-3 text-center font-semibold">Carga Horaria</th>
                                <th class="px-5 py-3 text-right font-semibold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($curso->cursoAsignaturas as $asociacion)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-5 py-3.5 font-mono text-slate-500 font-medium">
                                        {{ $asociacion->asignatura->codigo }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <p class="font-bold text-slate-900">{{ $asociacion->asignatura->nombre }}</p>
                                        <p class="text-[11px] text-slate-500">{{ $asociacion->asignatura->descripcion }}</p>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center font-bold text-[10px] text-slate-700">
                                                {{ strtoupper(substr($asociacion->docente?->name ?? 'P', 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-slate-800">{{ $asociacion->docente?->name ?? 'Sin asignar' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200 font-mono">
                                            {{ $asociacion->horas_semanales }} hrs/sem
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <a href="{{ route('notas.index') }}" class="text-xs font-semibold text-amber-600 hover:text-amber-700">
                                            Libro de Notas
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-6 text-center text-slate-500 text-xs">
                                        No hay asignaturas asociadas a este curso aún.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="p-8 text-center bg-white rounded-2xl border border-slate-200">
                <p class="text-sm text-slate-500">No se encontraron cursos registrados.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
