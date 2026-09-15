@extends('layouts.app')

@section('titulo', 'Matrículas y Estudiantes')
@section('encabezado', 'Módulo de Matrículas Escolares')

@section('contenido')
<div class="space-y-6">
    <!-- Encabezado del Módulo -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 rounded-2xl bg-[#161920] border border-zinc-800">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $usuario?->rol?->obtenerClasesInsignia() ?? 'bg-zinc-800 text-zinc-300' }} border mb-2">
                <span>{{ $usuario?->rol?->obtenerEtiqueta() ?? 'Usuario' }}</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                Registro y Matrículas de Estudiantes
            </h2>
            <p class="text-xs sm:text-sm text-zinc-400 mt-1">
                Administración de expedientes escolares, asignación de cursos y vinculación de apoderados.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-zinc-950 font-semibold text-xs rounded-xl shadow-sm transition-colors flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                </svg>
                <span>Nueva Matrícula</span>
            </button>
        </div>
    </div>

    <!-- Filtros de búsqueda -->
    <div class="p-4 rounded-xl bg-[#161920] border border-zinc-800 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="w-full sm:w-80">
            <input type="text" placeholder="Buscar por nombre o RUT (ej: 12345678-9)..." class="w-full px-3 py-2 bg-[#0d0f12] border border-zinc-700/80 rounded-xl text-xs text-zinc-200 placeholder-zinc-500 focus:outline-none focus:border-amber-500/80">
        </div>
        <div class="flex items-center gap-2 w-full sm:w-auto">
            <select class="px-3 py-2 bg-[#0d0f12] border border-zinc-700/80 rounded-xl text-xs text-zinc-300 focus:outline-none focus:border-amber-500/80">
                <option value="">Todos los cursos</option>
                <option value="1ma">1° Medio A</option>
                <option value="1mb">1° Medio B</option>
                <option value="2ma">2° Medio A</option>
            </select>
            <select class="px-3 py-2 bg-[#0d0f12] border border-zinc-700/80 rounded-xl text-xs text-zinc-300 focus:outline-none focus:border-amber-500/80">
                <option value="activo">Solo Activos</option>
                <option value="todos">Todos los Estados</option>
            </select>
        </div>
    </div>

    <!-- Lista de Estudiantes Matriculados -->
    <div class="rounded-2xl bg-[#161920] border border-zinc-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="bg-[#12141a] text-zinc-400 uppercase text-[11px] tracking-wider border-b border-zinc-800">
                    <tr>
                        <th class="px-4 py-3 font-semibold">N° Matrícula</th>
                        <th class="px-4 py-3 font-semibold">Estudiante</th>
                        <th class="px-4 py-3 font-semibold">RUT</th>
                        <th class="px-4 py-3 font-semibold">Curso</th>
                        <th class="px-4 py-3 font-semibold">Apoderado Responsable</th>
                        <th class="px-4 py-3 text-center font-semibold">Estado</th>
                        <th class="px-4 py-3 text-right font-semibold">Ficha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/80">
                    <tr class="hover:bg-zinc-800/30 transition-colors">
                        <td class="px-4 py-3.5 font-mono text-zinc-500">#MAT-2026-001</td>
                        <td class="px-4 py-3.5 font-medium text-white">Álvarez Contreras, Sofía</td>
                        <td class="px-4 py-3.5 font-mono text-zinc-300">21458932-1</td>
                        <td class="px-4 py-3.5">
                            <span class="px-2 py-0.5 rounded bg-zinc-800 text-zinc-300 border border-zinc-700 font-mono text-[11px]">1° Medio A</span>
                        </td>
                        <td class="px-4 py-3.5 text-zinc-400">Marcela Contreras (+56 9 9876 5432)</td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Regular</span>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <button type="button" class="text-xs text-amber-400 hover:text-amber-300 font-medium cursor-pointer">Ver Ficha</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-zinc-800/30 transition-colors">
                        <td class="px-4 py-3.5 font-mono text-zinc-500">#MAT-2026-002</td>
                        <td class="px-4 py-3.5 font-medium text-white">Bustamante Morales, Matías</td>
                        <td class="px-4 py-3.5 font-mono text-zinc-300">22134567-8</td>
                        <td class="px-4 py-3.5">
                            <span class="px-2 py-0.5 rounded bg-zinc-800 text-zinc-300 border border-zinc-700 font-mono text-[11px]">1° Medio A</span>
                        </td>
                        <td class="px-4 py-3.5 text-zinc-400">Hernán Bustamante (+56 9 8765 4321)</td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Regular</span>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <button type="button" class="text-xs text-amber-400 hover:text-amber-300 font-medium cursor-pointer">Ver Ficha</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-zinc-800/30 transition-colors">
                        <td class="px-4 py-3.5 font-mono text-zinc-500">#MAT-2026-003</td>
                        <td class="px-4 py-3.5 font-medium text-white">Castillo Reyes, Daniela</td>
                        <td class="px-4 py-3.5 font-mono text-zinc-300">21987654-3</td>
                        <td class="px-4 py-3.5">
                            <span class="px-2 py-0.5 rounded bg-zinc-800 text-zinc-300 border border-zinc-700 font-mono text-[11px]">1° Medio A</span>
                        </td>
                        <td class="px-4 py-3.5 text-zinc-400">Patricia Reyes (+56 9 7654 3210)</td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Regular</span>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <button type="button" class="text-xs text-amber-400 hover:text-amber-300 font-medium cursor-pointer">Ver Ficha</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
