@extends('layouts.app')

@section('titulo', 'Calificaciones y Notas')
@section('encabezado', 'Libro de Calificaciones')

@section('contenido')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Encabezado en Tarjeta Blanca -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 rounded-2xl bg-white border border-slate-200 shadow-xs">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200 mb-2">
                <span>Evaluación Continua 2026</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
                    Libro Digital de Calificaciones por Asignatura
                @elseif($usuario?->esEstudiante())
                    Mis Calificaciones y Rendimiento
                @else
                    Boletín de Calificaciones de Pupilos
                @endif
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">
                @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
                    Cada curso tiene sus asignaturas asociadas para ingresar evaluaciones parciales y promedios semestrales.
                @elseif($usuario?->esEstudiante())
                    Consulta tus notas parciales por asignatura cursada en el ciclo escolar.
                @else
                    Seguimiento detallado de notas por asignatura de tus pupilos.
                @endif
            </p>
        </div>

        @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
            <div class="flex items-center gap-3">
                <button type="button" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Ingresar Nueva Nota</span>
                </button>
            </div>
        @endif
    </div>

    @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
        <!-- Métricas Rápidas en Blanco -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Cursos Asignados</p>
                <p class="text-2xl font-bold text-slate-900 mt-1">{{ $cursos->count() }} Cursos</p>
                <p class="text-[11px] text-slate-500 mt-0.5">1° Medio A, 1° Medio B, 2° Medio A</p>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Escala de Calificaciones</p>
                <p class="text-2xl font-bold text-amber-600 mt-1">1.0 a 7.0</p>
                <p class="text-[11px] text-slate-500 mt-0.5">Nota mínima de aprobación: 4.0</p>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs">
                <p class="text-xs text-slate-500 font-semibold uppercase tracking-wider">Promedio General del Nivel</p>
                <p class="text-2xl font-bold text-emerald-600 mt-1">6.2</p>
                <p class="text-[11px] text-slate-500 mt-0.5">Primer Semestre 2026</p>
            </div>
        </div>

        <!-- Tabla Simulada de Muestra Docente en Blanco -->
        <div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/70">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Curso:</span>
                    <span class="px-3 py-1 bg-white text-slate-900 rounded-lg text-xs font-bold border border-slate-200 shadow-2xs">1° Medio A - Matemáticas (MAT-101)</span>
                </div>
                <div class="text-xs text-slate-500">
                    Docente: <strong class="text-slate-800">Prof. Rodrigo Sánchez</strong> (6 hrs/semanales)
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-700">
                    <thead class="bg-slate-100/70 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="px-5 py-3 font-semibold">N°</th>
                            <th class="px-5 py-3 font-semibold">Estudiante</th>
                            <th class="px-5 py-3 font-semibold">RUT</th>
                            <th class="px-3 py-3 text-center font-semibold">Nota 1 (25%)</th>
                            <th class="px-3 py-3 text-center font-semibold">Nota 2 (25%)</th>
                            <th class="px-3 py-3 text-center font-semibold">Nota 3 (25%)</th>
                            <th class="px-3 py-3 text-center font-semibold">Nota 4 (25%)</th>
                            <th class="px-5 py-3 text-center font-semibold">Promedio</th>
                            <th class="px-5 py-3 text-center font-semibold">Situación</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5 font-mono text-slate-400 font-medium">01</td>
                            <td class="px-5 py-3.5 font-bold text-slate-900">Álvarez Contreras, Sofía</td>
                            <td class="px-5 py-3.5 font-mono text-slate-500">44444444-4</td>
                            <td class="px-3 py-3.5 text-center font-mono font-bold text-slate-900">6.5</td>
                            <td class="px-3 py-3.5 text-center font-mono font-bold text-slate-900">6.0</td>
                            <td class="px-3 py-3.5 text-center font-mono font-bold text-slate-900">7.0</td>
                            <td class="px-3 py-3.5 text-center font-mono text-slate-400">-</td>
                            <td class="px-5 py-3.5 text-center font-mono font-bold text-amber-600">6.5</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Aprobado</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5 font-mono text-slate-400 font-medium">02</td>
                            <td class="px-5 py-3.5 font-bold text-slate-900">Bustamante Morales, Matías</td>
                            <td class="px-5 py-3.5 font-mono text-slate-500">22134567-8</td>
                            <td class="px-3 py-3.5 text-center font-mono font-bold text-slate-900">5.2</td>
                            <td class="px-3 py-3.5 text-center font-mono font-bold text-slate-900">4.8</td>
                            <td class="px-3 py-3.5 text-center font-mono font-bold text-slate-900">5.5</td>
                            <td class="px-3 py-3.5 text-center font-mono text-slate-400">-</td>
                            <td class="px-5 py-3.5 text-center font-mono font-bold text-amber-600">5.2</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">Aprobado</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5 font-mono text-slate-400 font-medium">03</td>
                            <td class="px-5 py-3.5 font-bold text-slate-900">Castillo Reyes, Daniela</td>
                            <td class="px-5 py-3.5 font-mono text-slate-500">21987654-3</td>
                            <td class="px-3 py-3.5 text-center font-mono font-bold text-red-600">3.8</td>
                            <td class="px-3 py-3.5 text-center font-mono font-bold text-slate-900">4.2</td>
                            <td class="px-3 py-3.5 text-center font-mono font-bold text-red-600">3.5</td>
                            <td class="px-3 py-3.5 text-center font-mono text-slate-400">-</td>
                            <td class="px-5 py-3.5 text-center font-mono font-bold text-red-600">3.8</td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-red-50 text-red-700 border border-red-200">En Riesgo</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    @elseif($usuario?->esEstudiante())
        <!-- Vista para Estudiante en Blanco -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Promedio General</span>
                <p class="text-3xl font-bold text-amber-600 mt-2">6.2</p>
                <p class="text-xs text-slate-500 mt-1">Escala de 1.0 a 7.0</p>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Asignaturas de Mi Curso</span>
                <p class="text-3xl font-bold text-slate-900 mt-2">{{ $matriculaEstudiante?->curso?->cursoAsignaturas?->count() ?? 5 }} Materias</p>
                <p class="text-xs text-slate-500 mt-1">Curso: {{ $matriculaEstudiante?->curso?->nombre ?? '1° Medio A' }}</p>
            </div>
            <div class="p-5 rounded-2xl bg-white border border-slate-200 shadow-xs">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado Académico</span>
                <p class="text-2xl font-bold text-emerald-600 mt-2">Promovido(a)</p>
                <p class="text-xs text-slate-500 mt-1">100% de aprobación</p>
            </div>
        </div>

        <div class="rounded-2xl bg-white border border-slate-200 shadow-xs p-5 space-y-4">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Calificaciones por Asignatura</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Matemáticas</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Prof. Rodrigo Sánchez</p>
                        <div class="flex items-center gap-1.5 mt-2">
                            <span class="px-2 py-0.5 bg-white border border-slate-200 text-slate-800 rounded text-xs font-mono font-bold">6.5</span>
                            <span class="px-2 py-0.5 bg-white border border-slate-200 text-slate-800 rounded text-xs font-mono font-bold">6.0</span>
                            <span class="px-2 py-0.5 bg-white border border-slate-200 text-slate-800 rounded text-xs font-mono font-bold">7.0</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400 block font-medium">Promedio</span>
                        <span class="text-xl font-bold text-amber-600 font-mono">6.5</span>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div>
                        <h4 class="font-bold text-slate-900 text-sm">Lengua y Literatura</h4>
                        <p class="text-xs text-slate-500 mt-0.5">Prof. Carolina Silva</p>
                        <div class="flex items-center gap-1.5 mt-2">
                            <span class="px-2 py-0.5 bg-white border border-slate-200 text-slate-800 rounded text-xs font-mono font-bold">6.0</span>
                            <span class="px-2 py-0.5 bg-white border border-slate-200 text-slate-800 rounded text-xs font-mono font-bold">6.5</span>
                            <span class="px-2 py-0.5 bg-white border border-slate-200 text-slate-800 rounded text-xs font-mono font-bold">6.2</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-slate-400 block font-medium">Promedio</span>
                        <span class="text-xl font-bold text-amber-600 font-mono">6.2</span>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Vista para Apoderado en Blanco -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200 shadow-xs space-y-4">
            <h3 class="text-base font-bold text-slate-900">Pupilos Asignados</h3>
            <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-100 text-amber-800 font-bold flex items-center justify-center text-sm">
                        SO
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900">Sofía Álvarez Contreras</p>
                        <p class="text-xs text-slate-500">1° Medio A • Promedio General: <strong class="text-amber-600 font-bold">6.5</strong></p>
                    </div>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    Rendimiento Sobresaliente
                </span>
            </div>
        </div>
    @endif
</div>
@endsection
