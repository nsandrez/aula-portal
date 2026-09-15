@extends('layouts.app')

@section('titulo', 'Calificaciones y Notas')
@section('encabezado', 'Módulo de Calificaciones')

@section('contenido')
<div class="space-y-6">
    <!-- Encabezado del Módulo con badge de rol -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 rounded-2xl bg-[#161920] border border-zinc-800">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $usuario?->rol?->obtenerClasesInsignia() ?? 'bg-zinc-800 text-zinc-300' }} border mb-2">
                <span>{{ $usuario?->rol?->obtenerEtiqueta() ?? 'Usuario' }}</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
                    Libro Digital de Calificaciones
                @elseif($usuario?->esEstudiante())
                    Mis Calificaciones y Rendimiento
                @else
                    Boletín de Calificaciones de Pupilos
                @endif
            </h2>
            <p class="text-xs sm:text-sm text-zinc-400 mt-1">
                @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
                    Seleccione un curso y asignatura para registrar notas, calcular promedios y revisar ponderaciones.
                @elseif($usuario?->esEstudiante())
                    Revisa tus notas parciales por asignatura y promedio acumulado del año 2026.
                @else
                    Seguimiento académico detallado y libreta de calificaciones oficiales.
                @endif
            </p>
        </div>

        @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
            <div class="flex items-center gap-3">
                <button type="button" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-zinc-950 font-semibold text-xs rounded-xl shadow-sm transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Ingresar Nueva Evaluación</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Contenido según el Perfil -->
    @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
        <!-- Panel para Docente / Admin: Selección de Asignatura y Tabla de Alumnos -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-5 rounded-xl bg-[#161920] border border-zinc-800">
                <p class="text-xs text-zinc-400 font-medium">Asignaturas Asignadas</p>
                <p class="text-2xl font-bold text-white mt-1">4 Cursos</p>
                <p class="text-[11px] text-zinc-500 mt-0.5">Matemáticas, Álgebra, Geometría</p>
            </div>
            <div class="p-5 rounded-xl bg-[#161920] border border-zinc-800">
                <p class="text-xs text-zinc-400 font-medium">Evaluaciones Registradas</p>
                <p class="text-2xl font-bold text-amber-400 mt-1">18 Evaluaciones</p>
                <p class="text-[11px] text-zinc-500 mt-0.5">Primer Semestre 2026</p>
            </div>
            <div class="p-5 rounded-xl bg-[#161920] border border-zinc-800">
                <p class="text-xs text-zinc-400 font-medium">Promedio General del Nivel</p>
                <p class="text-2xl font-bold text-emerald-400 mt-1">6.1</p>
                <p class="text-[11px] text-zinc-500 mt-0.5">Escala estándar de 1.0 a 7.0</p>
            </div>
        </div>

        <!-- Tabla Simulada de Muestra Docente -->
        <div class="rounded-2xl bg-[#161920] border border-zinc-800 overflow-hidden">
            <div class="p-4 sm:p-5 border-b border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">Curso:</span>
                    <span class="px-3 py-1 bg-zinc-800 text-white rounded-lg text-xs font-medium border border-zinc-700">1° Medio A - Matemáticas</span>
                </div>
                <div class="text-xs text-zinc-400">
                    Escala de Calificaciones: <strong class="text-zinc-200">1.0 a 7.0 (Mínimo aprobación: 4.0)</strong>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-zinc-300">
                    <thead class="bg-[#12141a] text-zinc-400 uppercase text-[11px] tracking-wider border-b border-zinc-800">
                        <tr>
                            <th class="px-4 py-3 font-semibold">N°</th>
                            <th class="px-4 py-3 font-semibold">Estudiante</th>
                            <th class="px-4 py-3 font-semibold">RUT</th>
                            <th class="px-3 py-3 text-center font-semibold">Nota 1 (25%)</th>
                            <th class="px-3 py-3 text-center font-semibold">Nota 2 (25%)</th>
                            <th class="px-3 py-3 text-center font-semibold">Nota 3 (25%)</th>
                            <th class="px-3 py-3 text-center font-semibold">Nota 4 (25%)</th>
                            <th class="px-4 py-3 text-center font-semibold">Promedio</th>
                            <th class="px-4 py-3 text-center font-semibold">Situación</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/80">
                        <tr class="hover:bg-zinc-800/30 transition-colors">
                            <td class="px-4 py-3 text-zinc-500 font-mono">01</td>
                            <td class="px-4 py-3 font-medium text-white">Álvarez Contreras, Sofía</td>
                            <td class="px-4 py-3 font-mono text-zinc-400">21458932-1</td>
                            <td class="px-3 py-3 text-center font-mono font-medium text-white">6.5</td>
                            <td class="px-3 py-3 text-center font-mono font-medium text-white">6.0</td>
                            <td class="px-3 py-3 text-center font-mono font-medium text-white">7.0</td>
                            <td class="px-3 py-3 text-center font-mono font-medium text-zinc-500">-</td>
                            <td class="px-4 py-3 text-center font-mono font-bold text-amber-400">6.5</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Aprobado</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-zinc-800/30 transition-colors">
                            <td class="px-4 py-3 text-zinc-500 font-mono">02</td>
                            <td class="px-4 py-3 font-medium text-white">Bustamante Morales, Matías</td>
                            <td class="px-4 py-3 font-mono text-zinc-400">22134567-8</td>
                            <td class="px-3 py-3 text-center font-mono font-medium text-white">5.2</td>
                            <td class="px-3 py-3 text-center font-mono font-medium text-white">4.8</td>
                            <td class="px-3 py-3 text-center font-mono font-medium text-white">5.5</td>
                            <td class="px-3 py-3 text-center font-mono font-medium text-zinc-500">-</td>
                            <td class="px-4 py-3 text-center font-mono font-bold text-amber-400">5.2</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Aprobado</span>
                            </td>
                        </tr>
                        <tr class="hover:bg-zinc-800/30 transition-colors">
                            <td class="px-4 py-3 text-zinc-500 font-mono">03</td>
                            <td class="px-4 py-3 font-medium text-white">Castillo Reyes, Daniela</td>
                            <td class="px-4 py-3 font-mono text-zinc-400">21987654-3</td>
                            <td class="px-3 py-3 text-center font-mono font-medium text-red-400">3.8</td>
                            <td class="px-3 py-3 text-center font-mono font-medium text-white">4.2</td>
                            <td class="px-3 py-3 text-center font-mono font-medium text-red-400">3.5</td>
                            <td class="px-3 py-3 text-center font-mono font-medium text-zinc-500">-</td>
                            <td class="px-4 py-3 text-center font-mono font-bold text-red-400">3.8</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-red-500/10 text-red-400 border border-red-500/30">En Riesgo</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    @elseif($usuario?->esEstudiante())
        <!-- Vista de Estudiante: Mis Asignaturas y Calificaciones -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-5 rounded-xl bg-[#161920] border border-zinc-800">
                <p class="text-xs text-zinc-400 font-medium">Promedio General</p>
                <p class="text-3xl font-bold text-amber-400 mt-1">6.2</p>
                <p class="text-[11px] text-zinc-500 mt-0.5">Rendimiento Destacado</p>
            </div>
            <div class="p-5 rounded-xl bg-[#161920] border border-zinc-800">
                <p class="text-xs text-zinc-400 font-medium">Asignaturas Evaluadas</p>
                <p class="text-3xl font-bold text-white mt-1">6 / 8</p>
                <p class="text-[11px] text-zinc-500 mt-0.5">Semestre en Curso</p>
            </div>
            <div class="p-5 rounded-xl bg-[#161920] border border-zinc-800">
                <p class="text-xs text-zinc-400 font-medium">Tasa de Aprobación</p>
                <p class="text-3xl font-bold text-emerald-400 mt-1">100%</p>
                <p class="text-[11px] text-zinc-500 mt-0.5">Todas las notas sobre 4.0</p>
            </div>
        </div>

        <div class="rounded-2xl bg-[#161920] border border-zinc-800 p-5 space-y-4">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-zinc-400">Detalle por Asignatura</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-[#12141a] border border-zinc-800/80 flex items-center justify-between">
                    <div>
                        <h4 class="font-semibold text-white text-sm">Lengua y Literatura</h4>
                        <p class="text-xs text-zinc-400 mt-0.5">Profesor: Carlos Valenzuela</p>
                        <div class="flex items-center gap-1.5 mt-2">
                            <span class="px-2 py-0.5 bg-zinc-800 text-white rounded text-[11px] font-mono">6.0</span>
                            <span class="px-2 py-0.5 bg-zinc-800 text-white rounded text-[11px] font-mono">6.5</span>
                            <span class="px-2 py-0.5 bg-zinc-800 text-white rounded text-[11px] font-mono">6.2</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-zinc-500 block">Promedio</span>
                        <span class="text-xl font-bold text-amber-400 font-mono">6.2</span>
                    </div>
                </div>

                <div class="p-4 rounded-xl bg-[#12141a] border border-zinc-800/80 flex items-center justify-between">
                    <div>
                        <h4 class="font-semibold text-white text-sm">Matemáticas</h4>
                        <p class="text-xs text-zinc-400 mt-0.5">Profesor: Rodrigo Sánchez</p>
                        <div class="flex items-center gap-1.5 mt-2">
                            <span class="px-2 py-0.5 bg-zinc-800 text-white rounded text-[11px] font-mono">6.5</span>
                            <span class="px-2 py-0.5 bg-zinc-800 text-white rounded text-[11px] font-mono">6.0</span>
                            <span class="px-2 py-0.5 bg-zinc-800 text-white rounded text-[11px] font-mono">7.0</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-zinc-500 block">Promedio</span>
                        <span class="text-xl font-bold text-amber-400 font-mono">6.5</span>
                    </div>
                </div>
            </div>
        </div>

    @else
        <!-- Vista para Apoderado -->
        <div class="p-6 rounded-2xl bg-[#161920] border border-zinc-800 space-y-4">
            <h3 class="text-base font-semibold text-white">Pupilos Asignados</h3>
            <p class="text-xs text-zinc-400">Seleccione el estudiante para ver el reporte consolidado de calificaciones del periodo escolar.</p>
            <div class="p-4 rounded-xl bg-[#12141a] border border-zinc-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/30 flex items-center justify-center font-bold text-sm">
                        SO
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white">Sofía Álvarez Contreras</p>
                        <p class="text-xs text-zinc-500">1° Medio A • Promedio General: <strong class="text-amber-400">6.5</strong></p>
                    </div>
                </div>
                <span class="px-2.5 py-1 rounded-lg text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">
                    Rendimiento Óptimo
                </span>
            </div>
        </div>
    @endif
</div>
@endsection
