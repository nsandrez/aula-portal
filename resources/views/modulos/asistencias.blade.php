@extends('layouts.app')

@section('titulo', 'Control de Asistencias')
@section('encabezado', 'Módulo de Asistencias')

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
                    Control Diario de Asistencias
                @elseif($usuario?->esEstudiante())
                    Mi Registro de Asistencia Escolar
                @else
                    Asistencia y Atrasos de Pupilos
                @endif
            </h2>
            <p class="text-xs sm:text-sm text-zinc-400 mt-1">
                @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
                    Toma de lista diaria por curso, registro de atrasos y gestión de inasistencias justificadas.
                @elseif($usuario?->esEstudiante())
                    Consulta tu porcentaje de asistencia anual y registro de inasistencias.
                @else
                    Monitoreo en tiempo real del ingreso al establecimiento escolar de tus pupilos.
                @endif
            </p>
        </div>

        @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
            <div class="flex items-center gap-2">
                <button type="button" class="px-3 py-2 bg-zinc-800 hover:bg-zinc-700 text-zinc-200 text-xs font-medium rounded-xl border border-zinc-700 transition-colors cursor-pointer">
                    Marcar Todos Presentes
                </button>
                <button type="button" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-zinc-950 font-semibold text-xs rounded-xl shadow-sm transition-colors cursor-pointer">
                    Guardar Lista
                </button>
            </div>
        @endif
    </div>

    <!-- Contenido según el Perfil -->
    @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
        <!-- Resumen de Métricas del Día -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl bg-[#161920] border border-zinc-800">
                <p class="text-[11px] text-zinc-500 font-semibold uppercase tracking-wider">Presentes</p>
                <p class="text-2xl font-bold text-emerald-400 mt-1">32</p>
                <p class="text-[10px] text-zinc-500 mt-0.5">88.8% del curso</p>
            </div>
            <div class="p-4 rounded-xl bg-[#161920] border border-zinc-800">
                <p class="text-[11px] text-zinc-500 font-semibold uppercase tracking-wider">Ausentes</p>
                <p class="text-2xl font-bold text-red-400 mt-1">2</p>
                <p class="text-[10px] text-zinc-500 mt-0.5">5.6% sin justificar</p>
            </div>
            <div class="p-4 rounded-xl bg-[#161920] border border-zinc-800">
                <p class="text-[11px] text-zinc-500 font-semibold uppercase tracking-wider">Atrasos</p>
                <p class="text-2xl font-bold text-amber-400 mt-1">1</p>
                <p class="text-[10px] text-zinc-500 mt-0.5">Ingreso posterior a 08:15</p>
            </div>
            <div class="p-4 rounded-xl bg-[#161920] border border-zinc-800">
                <p class="text-[11px] text-zinc-500 font-semibold uppercase tracking-wider">Justificados</p>
                <p class="text-2xl font-bold text-blue-400 mt-1">1</p>
                <p class="text-[10px] text-zinc-500 mt-0.5">Certificado médico recibido</p>
            </div>
        </div>

        <!-- Tabla de Toma de Lista -->
        <div class="rounded-2xl bg-[#161920] border border-zinc-800 overflow-hidden">
            <div class="p-4 border-b border-zinc-800 flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-semibold text-zinc-400 uppercase tracking-wider">Curso:</span>
                    <span class="px-3 py-1 bg-zinc-800 text-white rounded-lg text-xs font-medium border border-zinc-700">1° Medio A (36 Estudiantes)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-zinc-400">Fecha:</span>
                    <span class="px-3 py-1 bg-[#12141a] text-zinc-300 rounded-lg text-xs font-mono border border-zinc-800">15 de Septiembre, 2026</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-zinc-300">
                    <thead class="bg-[#12141a] text-zinc-400 uppercase text-[11px] tracking-wider border-b border-zinc-800">
                        <tr>
                            <th class="px-4 py-3 font-semibold">N°</th>
                            <th class="px-4 py-3 font-semibold">Estudiante</th>
                            <th class="px-4 py-3 font-semibold">RUT</th>
                            <th class="px-4 py-3 text-center font-semibold">Estado de Asistencia</th>
                            <th class="px-4 py-3 font-semibold">Observación / Motivo</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/80">
                        <tr class="hover:bg-zinc-800/30 transition-colors">
                            <td class="px-4 py-3 text-zinc-500 font-mono">01</td>
                            <td class="px-4 py-3 font-medium text-white">Álvarez Contreras, Sofía</td>
                            <td class="px-4 py-3 font-mono text-zinc-400">21458932-1</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                                    Presente
                                </span>
                            </td>
                            <td class="px-4 py-3 text-zinc-500">-</td>
                        </tr>
                        <tr class="hover:bg-zinc-800/30 transition-colors">
                            <td class="px-4 py-3 text-zinc-500 font-mono">02</td>
                            <td class="px-4 py-3 font-medium text-white">Bustamante Morales, Matías</td>
                            <td class="px-4 py-3 font-mono text-zinc-400">22134567-8</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-amber-500/15 text-amber-400 border border-amber-500/30">
                                    Atraso (08:25 hrs)
                                </span>
                            </td>
                            <td class="px-4 py-3 text-zinc-400 text-xs">Pase de inspectoría</td>
                        </tr>
                        <tr class="hover:bg-zinc-800/30 transition-colors">
                            <td class="px-4 py-3 text-zinc-500 font-mono">03</td>
                            <td class="px-4 py-3 font-medium text-white">Castillo Reyes, Daniela</td>
                            <td class="px-4 py-3 font-mono text-zinc-400">21987654-3</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-blue-500/15 text-blue-400 border border-blue-500/30">
                                    Justificado
                                </span>
                            </td>
                            <td class="px-4 py-3 text-zinc-400 text-xs">Licencia médica por 48 horas</td>
                        </tr>
                        <tr class="hover:bg-zinc-800/30 transition-colors">
                            <td class="px-4 py-3 text-zinc-500 font-mono">04</td>
                            <td class="px-4 py-3 font-medium text-white">Díaz Herrera, Lucas</td>
                            <td class="px-4 py-3 font-mono text-zinc-400">22345678-9</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-red-500/15 text-red-400 border border-red-500/30">
                                    Ausente
                                </span>
                            </td>
                            <td class="px-4 py-3 text-red-400/80 text-xs">Sin justificación informada</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    @elseif($usuario?->esEstudiante())
        <!-- Vista de Estudiante -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-5 rounded-xl bg-[#161920] border border-zinc-800">
                <p class="text-xs text-zinc-400 font-medium">Asistencia Acumulada</p>
                <p class="text-3xl font-bold text-emerald-400 mt-1">96.4%</p>
                <p class="text-[11px] text-zinc-500 mt-0.5">Excediendo requisito mínimo (85%)</p>
            </div>
            <div class="p-5 rounded-xl bg-[#161920] border border-zinc-800">
                <p class="text-xs text-zinc-400 font-medium">Días Asistidos</p>
                <p class="text-3xl font-bold text-white mt-1">108 / 112</p>
                <p class="text-[11px] text-zinc-500 mt-0.5">Días lectivos cumplidos</p>
            </div>
            <div class="p-5 rounded-xl bg-[#161920] border border-zinc-800">
                <p class="text-xs text-zinc-400 font-medium">Total de Atrasos</p>
                <p class="text-3xl font-bold text-amber-400 mt-1">2</p>
                <p class="text-[11px] text-zinc-500 mt-0.5">Dentro del margen permitido</p>
            </div>
        </div>

        <div class="rounded-2xl bg-[#161920] border border-zinc-800 p-5 space-y-4">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-zinc-400">Registro Reciente de Asistencia</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-zinc-300">
                    <thead class="bg-[#12141a] text-zinc-400 uppercase text-[11px] tracking-wider border-b border-zinc-800">
                        <tr>
                            <th class="px-4 py-3 font-semibold">Fecha</th>
                            <th class="px-4 py-3 font-semibold">Hora Ingreso</th>
                            <th class="px-4 py-3 font-semibold">Estado</th>
                            <th class="px-4 py-3 font-semibold">Observación</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800/80">
                        <tr class="hover:bg-zinc-800/30">
                            <td class="px-4 py-3 font-mono">15/09/2026</td>
                            <td class="px-4 py-3 font-mono">07:55 hrs</td>
                            <td class="px-4 py-3"><span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Presente</span></td>
                            <td class="px-4 py-3 text-zinc-500">Normal</td>
                        </tr>
                        <tr class="hover:bg-zinc-800/30">
                            <td class="px-4 py-3 font-mono">14/09/2026</td>
                            <td class="px-4 py-3 font-mono">08:20 hrs</td>
                            <td class="px-4 py-3"><span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30">Atraso</span></td>
                            <td class="px-4 py-3 text-zinc-400">Atraso 15 minutos</td>
                        </tr>
                        <tr class="hover:bg-zinc-800/30">
                            <td class="px-4 py-3 font-mono">13/09/2026</td>
                            <td class="px-4 py-3 font-mono">07:50 hrs</td>
                            <td class="px-4 py-3"><span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Presente</span></td>
                            <td class="px-4 py-3 text-zinc-500">Normal</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    @else
        <!-- Vista para Apoderado -->
        <div class="p-6 rounded-2xl bg-[#161920] border border-zinc-800 space-y-4">
            <h3 class="text-base font-semibold text-white">Estado de Asistencia del Pupilo</h3>
            <div class="p-4 rounded-xl bg-[#12141a] border border-zinc-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 flex items-center justify-center font-bold text-sm">
                        ✓
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-white">Sofía Álvarez Contreras (1° Medio A)</p>
                        <p class="text-xs text-zinc-400">Asistencia Hoy: <strong class="text-emerald-400">Presente (Ingreso: 07:55 hrs)</strong></p>
                    </div>
                </div>
                <div class="text-sm font-mono text-zinc-300 bg-zinc-800 px-3 py-1.5 rounded-lg border border-zinc-700">
                    Asistencia General: <span class="font-bold text-emerald-400">96.4%</span>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
