@extends('layouts.app')

@section('titulo', 'Cursos y Asignaturas')
@section('encabezado', 'Módulo de Gestión de Cursos')

@section('contenido')
<div class="space-y-6">
    <!-- Encabezado del Módulo -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 rounded-2xl bg-[#161920] border border-zinc-800">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $usuario?->rol?->obtenerClasesInsignia() ?? 'bg-zinc-800 text-zinc-300' }} border mb-2">
                <span>{{ $usuario?->rol?->obtenerEtiqueta() ?? 'Usuario' }}</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                Estructura Curricular y Cursos
            </h2>
            <p class="text-xs sm:text-sm text-zinc-400 mt-1">
                Administración de niveles educativos, cursos, asignación de profesores jefes y salas de clase.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-zinc-950 font-semibold text-xs rounded-xl shadow-sm transition-colors flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Crear Nuevo Curso</span>
            </button>
        </div>
    </div>

    <!-- Resumen de Cursos del Establecimiento -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="p-5 rounded-xl bg-[#161920] border border-zinc-800">
            <p class="text-xs text-zinc-400 font-medium">Total de Cursos Activos</p>
            <p class="text-2xl font-bold text-white mt-1">16 Cursos</p>
            <p class="text-[11px] text-zinc-500 mt-0.5">Desde 1° Básico hasta 4° Medio</p>
        </div>
        <div class="p-5 rounded-xl bg-[#161920] border border-zinc-800">
            <p class="text-xs text-zinc-400 font-medium">Capacidad Total Ocupada</p>
            <p class="text-2xl font-bold text-amber-400 mt-1">542 / 600</p>
            <p class="text-[11px] text-zinc-500 mt-0.5">90.3% ocupación de matrícula</p>
        </div>
        <div class="p-5 rounded-xl bg-[#161920] border border-zinc-800">
            <p class="text-xs text-zinc-400 font-medium">Cuerpo Docente Titular</p>
            <p class="text-2xl font-bold text-blue-400 mt-1">24 Docentes</p>
            <p class="text-[11px] text-zinc-500 mt-0.5">100% jefaturas asignadas</p>
        </div>
    </div>

    <!-- Lista de Cursos -->
    <div class="rounded-2xl bg-[#161920] border border-zinc-800 overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-zinc-800 flex items-center justify-between">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-zinc-400">Nómina de Cursos - Ciclo 2026</h3>
            <span class="text-xs text-zinc-500 font-mono">16 registros</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="bg-[#12141a] text-zinc-400 uppercase text-[11px] tracking-wider border-b border-zinc-800">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Nivel / Curso</th>
                        <th class="px-4 py-3 font-semibold">Profesor(a) Jefe</th>
                        <th class="px-4 py-3 text-center font-semibold">Sala</th>
                        <th class="px-4 py-3 text-center font-semibold">Matrícula</th>
                        <th class="px-4 py-3 text-center font-semibold">Estado</th>
                        <th class="px-4 py-3 text-right font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/80">
                    <tr class="hover:bg-zinc-800/30 transition-colors">
                        <td class="px-4 py-3.5">
                            <span class="font-semibold text-white text-sm">1° Medio A</span>
                            <span class="block text-[11px] text-zinc-500">Enseñanza Media Científico-Humanista</span>
                        </td>
                        <td class="px-4 py-3.5 text-zinc-300">Prof. Rodrigo Sánchez (Matemáticas)</td>
                        <td class="px-4 py-3.5 text-center font-mono text-zinc-400">Pabellón B - Sala 12</td>
                        <td class="px-4 py-3.5 text-center font-mono text-zinc-200">36 / 40</td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Activo</span>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <button type="button" class="text-xs text-amber-400 hover:text-amber-300 font-medium cursor-pointer">Ver Asignaturas</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-zinc-800/30 transition-colors">
                        <td class="px-4 py-3.5">
                            <span class="font-semibold text-white text-sm">1° Medio B</span>
                            <span class="block text-[11px] text-zinc-500">Enseñanza Media Científico-Humanista</span>
                        </td>
                        <td class="px-4 py-3.5 text-zinc-300">Prof. Carolina Silva (Lenguaje)</td>
                        <td class="px-4 py-3.5 text-center font-mono text-zinc-400">Pabellón B - Sala 14</td>
                        <td class="px-4 py-3.5 text-center font-mono text-zinc-200">35 / 40</td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Activo</span>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <button type="button" class="text-xs text-amber-400 hover:text-amber-300 font-medium cursor-pointer">Ver Asignaturas</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-zinc-800/30 transition-colors">
                        <td class="px-4 py-3.5">
                            <span class="font-semibold text-white text-sm">2° Medio A</span>
                            <span class="block text-[11px] text-zinc-500">Enseñanza Media Científico-Humanista</span>
                        </td>
                        <td class="px-4 py-3.5 text-zinc-300">Prof. Mauricio González (Historia)</td>
                        <td class="px-4 py-3.5 text-center font-mono text-zinc-400">Pabellón B - Sala 15</td>
                        <td class="px-4 py-3.5 text-center font-mono text-zinc-200">34 / 40</td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Activo</span>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <button type="button" class="text-xs text-amber-400 hover:text-amber-300 font-medium cursor-pointer">Ver Asignaturas</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
