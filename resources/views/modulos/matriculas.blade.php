@extends('layouts.app')

@section('titulo', 'Matrículas y Estudiantes')
@section('encabezado', 'Módulo de Matrículas Escolares')

@section('contenido')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Encabezado en Tarjeta Blanca -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 rounded-2xl bg-white border border-slate-200 shadow-xs">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200 mb-2">
                <span>Nómina Escolar 2026</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Registro de Matrículas y Estudiantes
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">
                Asignación de alumnos a sus respectivos cursos, número de lista y datos de contacto de apoderados.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                </svg>
                <span>Nueva Matrícula</span>
            </button>
        </div>
    </div>

    <!-- Lista de Estudiantes Matriculados en Blanco -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-200 flex items-center justify-between bg-slate-50/70">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Estudiantes Matriculados</h3>
            <span class="text-xs text-slate-500 font-mono">{{ $matriculas->count() }} alumnos activos</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-100/70 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3 font-semibold">N° Lista</th>
                        <th class="px-5 py-3 font-semibold">Estudiante</th>
                        <th class="px-5 py-3 font-semibold">RUT</th>
                        <th class="px-5 py-3 font-semibold">Curso Asignado</th>
                        <th class="px-5 py-3 font-semibold">Apoderado Responsable</th>
                        <th class="px-5 py-3 text-center font-semibold">Estado</th>
                        <th class="px-5 py-3 text-right font-semibold">Ficha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($matriculas as $m)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5 font-mono text-slate-400 font-medium">
                                {{ str_pad((string)$m->numero_lista, 2, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="px-5 py-3.5 font-bold text-slate-900">
                                {{ $m->estudiante?->name }}
                            </td>
                            <td class="px-5 py-3.5 font-mono text-slate-500">
                                {{ $m->estudiante?->rut }}
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-800 font-bold border border-slate-200 text-xs">
                                    {{ $m->curso?->nombre }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-slate-600">
                                {{ $m->apoderado?->name ?? 'No registrado' }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Regular
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <button type="button" class="text-xs font-bold text-amber-600 hover:text-amber-700 cursor-pointer">
                                    Ver Ficha
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-8 text-center text-slate-500">
                                No se encontraron matrículas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
