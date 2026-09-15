@extends('layouts.app')

@section('titulo', 'Matrículas y Estudiantes')
@section('encabezado', 'Módulo de Matrículas Escolares')

@section('contenido')
<div class="space-y-6 max-w-[1600px] w-full mx-auto">
    <!-- Encabezado en Tarjeta Blanca Amplia -->
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 p-6 sm:p-7 rounded-3xl bg-white border border-slate-200/90 shadow-xs">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-amber-100/70 text-amber-900 border border-amber-300/60 mb-2.5">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                <span>Registro Oficial de Nómina • Ciclo 2026</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                Gestión de Matrículas y Estudiantes
            </h2>
            <p class="text-sm text-slate-600 mt-1 max-w-3xl leading-relaxed">
                Inscripción de alumnos, números de lista en aula, distribución de cursos y vinculación con apoderados legales.
            </p>
        </div>

        @if(auth()->user()?->tieneRol(\App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" data-abrir-modal="modal-vincular-apoderado" class="px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-800 font-bold text-xs rounded-xl border border-slate-300 shadow-2xs transition-all flex items-center gap-2 cursor-pointer hover:border-slate-400">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                    <span>Vincular Pupilos a Apoderado</span>
                </button>
                <button type="button" onclick="cambiarTipoMatricula('nuevo'); abrirModal('modal-nueva-matricula');" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition-all flex items-center gap-2 cursor-pointer hover:shadow-md">
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                    </svg>
                    <span>+ Registrar Nuevo Alumno</span>
                </button>
                <button type="button" onclick="cambiarTipoMatricula('existente'); abrirModal('modal-nueva-matricula');" class="px-4.5 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-xs transition-all flex items-center gap-2 cursor-pointer hover:shadow-md">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Matricular Alumno</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Indicadores KPI Institucionales de Matrícula (Elimina el vacío superior) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <!-- 1. Total Alumnos -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Matriculados</span>
                <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-800 flex items-center justify-center font-bold text-xs border border-amber-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">{{ $matriculas->count() }} Alumnos</p>
            <div class="mt-2.5 flex items-center gap-2">
                <div class="flex-1 h-2 rounded-full bg-slate-100 overflow-hidden">
                    <div class="h-full bg-amber-500 rounded-full" style="width: {{ min(100, ($matriculas->count() / 30) * 100) }}%"></div>
                </div>
                <span class="text-xs font-semibold text-slate-500 font-mono">{{ $matriculas->count() }}/30 cupos</span>
            </div>
        </div>

        <!-- 2. Alumnos Regulares -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Alumnos Regulares</span>
                <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold text-xs border border-emerald-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
                {{ $matriculas->where('estado', 'regular')->count() }} Activos
            </p>
            <p class="text-xs text-emerald-700 font-semibold mt-1">100% de retención escolar vigente</p>
        </div>

        <!-- 3. Cobertura Apoderados -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Apoderados Vinculados</span>
                <span class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center font-bold text-xs border border-blue-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
                {{ $matriculas->whereNotNull('apoderado_id')->count() }} Alumnos
            </p>
            <p class="text-xs text-blue-700 font-semibold mt-1">Con tutor legal asignado en el portal</p>
        </div>

        <!-- 4. Cursos Activos -->
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-2xs">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Cursos Activos</span>
                <span class="w-9 h-9 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold text-xs border border-purple-200">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342" />
                    </svg>
                </span>
            </div>
            <p class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">{{ $cursos->count() }} Cursos</p>
            <p class="text-xs text-slate-500 mt-1">Distribución en Enseñanza Media</p>
        </div>
    </div>

    <!-- Layout en 2 Columnas Equilibradas para Pantallas Amplias -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Columna Principal: Buscador y Tabla de Alumnos (lg:col-span-8 o 9) -->
        <div class="lg:col-span-8 xl:col-span-9 space-y-4">
            
            <!-- Barra de Filtros y Búsqueda Instantánea -->
            <div class="p-4 rounded-2xl bg-white border border-slate-200/90 shadow-2xs flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="relative w-full sm:w-80">
                    <input type="text" id="filtro-buscador-matricula" placeholder="Buscar por nombre o RUT..." class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-300/80 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:border-amber-500 focus:bg-white transition-all">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>

                <div class="flex items-center gap-2.5 w-full sm:w-auto">
                    <!-- Selector de Curso -->
                    <select id="filtro-curso-matricula" class="flex-1 sm:flex-initial px-3 py-2 bg-slate-50 border border-slate-300/80 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-amber-500">
                        <option value="">Todos los cursos</option>
                        @foreach($cursos as $c)
                            <option value="{{ strtolower($c->nombre) }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>

                    <!-- Selector de Estado -->
                    <select id="filtro-estado-matricula" class="px-3 py-2 bg-slate-50 border border-slate-300/80 rounded-xl text-xs font-semibold text-slate-700 focus:outline-none focus:border-amber-500">
                        <option value="">Todos los estados</option>
                        <option value="regular">Regular</option>
                        <option value="retirado">Retirado</option>
                    </select>
                </div>
            </div>

            <!-- Tabla de Matrículas en Blanco Elegante y Espacioso -->
            <div class="rounded-3xl bg-white border border-slate-200/90 shadow-2xs overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider">Estudiantes en Nómina</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Alumnos con matrícula activa en el año académico 2026</p>
                    </div>
                    <span id="contador-visibles-matricula" class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 font-bold text-xs font-mono border border-slate-200">
                        {{ $matriculas->count() }} alumnos
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700">
                        <thead class="bg-slate-100/60 text-slate-600 uppercase text-xs font-bold tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="px-5 py-3.5 font-bold">N° Lista</th>
                                <th class="px-5 py-3.5 font-bold">Estudiante</th>
                                <th class="px-5 py-3.5 font-bold">Curso</th>
                                <th class="px-5 py-3.5 font-bold">Apoderado Responsable</th>
                                <th class="px-5 py-3.5 text-center font-bold">Estado</th>
                                <th class="px-5 py-3.5 text-right font-bold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($matriculas as $m)
                                <tr class="fila-matricula hover:bg-slate-50/80 transition-colors" 
                                    data-nombre="{{ strtolower($m->estudiante?->name ?? '') }}" 
                                    data-rut="{{ strtolower($m->estudiante?->rut ?? '') }}"
                                    data-curso="{{ strtolower($m->curso?->nombre ?? '') }}"
                                    data-estado="{{ strtolower($m->estado) }}">
                                    <td class="px-5 py-4 font-mono text-slate-500 font-bold text-sm">
                                        {{ str_pad((string)$m->numero_lista, 2, '0', STR_PAD_LEFT) }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-900 font-bold flex items-center justify-center text-xs border border-amber-300/80 shrink-0">
                                                {{ strtoupper(substr($m->estudiante?->name ?? 'A', 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 text-sm leading-tight">{{ $m->estudiante?->name }}</p>
                                                <p class="text-xs font-mono text-slate-500 mt-0.5">{{ $m->estudiante?->rut ?? $m->estudiante?->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-slate-100 text-slate-800 font-bold border border-slate-200 text-xs">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            <span>{{ $m->curso?->nombre }}</span>
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        @if($m->apoderado)
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="font-bold text-slate-900 text-xs">{{ $m->apoderado->name }}</span>
                                                @php
                                                    $totalPupilos = $matriculas->where('apoderado_id', $m->apoderado_id)->count();
                                                @endphp
                                                @if($totalPupilos > 1)
                                                    <span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-900 text-[11px] font-bold border border-amber-300" title="{{ $totalPupilos }} pupilos a cargo de este apoderado">
                                                        {{ $totalPupilos }} hijos
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic text-xs">No registrado</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        @if($m->estado === 'regular')
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 inline-flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                <span>Regular</span>
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-50 text-red-700 border border-red-200">
                                                Retirado
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        @if(auth()->user()?->esSuperUsuario())
                                            <button type="button" onclick="editarMatricula({{ $m->id }}, {{ $m->numero_lista }}, '{{ $m->estado }}', {{ $m->apoderado_id ?? 'null' }})" class="px-3 py-1 bg-purple-50 hover:bg-purple-100 text-purple-700 font-bold text-xs rounded-lg border border-purple-200 transition-colors cursor-pointer" title="Editar datos de matrícula">
                                                Editar
                                            </button>
                                        @else
                                            <span class="text-xs text-slate-400 font-medium">Solo lectura</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-10 text-center text-slate-500 text-sm">
                                        No se encontraron matrículas registradas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Columna Lateral: Resumen de Capacidad y Herramientas Escolares (lg:col-span-4 o 3) -->
        <div class="lg:col-span-4 xl:col-span-3 space-y-5">
            
            <!-- Widget 1: Capacidad de Cupos por Curso -->
            <div class="p-5 rounded-3xl bg-white border border-slate-200/90 shadow-2xs space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Capacidad por Curso</h4>
                    <span class="text-[11px] text-amber-700 font-bold bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">2026</span>
                </div>

                <div class="space-y-3.5">
                    @foreach($cursos as $c)
                        @php
                            $matriculadosCurso = $matriculas->where('curso_id', $c->id)->count();
                            $cupoMaximo = 30;
                            $porcentaje = min(100, round(($matriculadosCurso / $cupoMaximo) * 100));
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-xs font-semibold mb-1">
                                <span class="text-slate-800">{{ $c->nombre }}</span>
                                <span class="text-slate-500 font-mono">{{ $matriculadosCurso }} / {{ $cupoMaximo }}</span>
                            </div>
                            <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full {{ $porcentaje > 80 ? 'bg-red-500' : ($porcentaje > 40 ? 'bg-amber-500' : 'bg-emerald-500') }} rounded-full transition-all" style="width: {{ $porcentaje }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Widget 2: Acciones Rápidas Institucionales -->
            <div class="p-5 rounded-3xl bg-white border border-slate-200/90 shadow-2xs space-y-3">
                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3">
                    Operaciones Rápidas
                </h4>
                
                <div class="space-y-2">
                    <button type="button" onclick="cambiarTipoMatricula('nuevo'); abrirModal('modal-nueva-matricula');" class="w-full p-2.5 rounded-xl bg-slate-50 hover:bg-amber-50 hover:border-amber-300 border border-slate-200 text-left transition-all flex items-center gap-3 group cursor-pointer">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-900 group-hover:text-amber-900">Inscribir Nuevo Alumno</p>
                            <p class="text-[11px] text-slate-500">Crea cuenta y asigna curso</p>
                        </div>
                    </button>

                    <button type="button" data-abrir-modal="modal-vincular-apoderado" class="w-full p-2.5 rounded-xl bg-slate-50 hover:bg-amber-50 hover:border-amber-300 border border-slate-200 text-left transition-all flex items-center gap-3 group cursor-pointer">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-900 group-hover:text-amber-900">Vincular Familia / Pupilos</p>
                            <p class="text-[11px] text-slate-500">Asigna varios hijos a apoderado</p>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Widget 3: Normativa Institucional -->
            <div class="p-5 rounded-3xl bg-amber-50/60 border border-amber-200/80 shadow-2xs space-y-2">
                <div class="flex items-center gap-2 text-amber-900 font-bold text-xs">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                    </svg>
                    <span>Reglamento de Matrícula</span>
                </div>
                <p class="text-xs text-amber-800/90 leading-relaxed">
                    Todo alumno matriculado debe contar con su RUT verificado y al menos un apoderado registrado para la recepción de reportes de asistencia y boletines de notas.
                </p>
            </div>

        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: NUEVA MATRÍCULA / REGISTRO DE ALUMNO              -->
<!-- ======================================================== -->
<div id="modal-nueva-matricula" class="modal-fondo fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Matrícula y Registro de Alumnos</h3>
                <p class="text-xs text-slate-500">Inscribe a un alumno existente o registra un nuevo estudiante</p>
            </div>
            <button type="button" data-cerrar-modal="modal-nueva-matricula" class="p-1 text-slate-400 hover:text-slate-700 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('matriculas.guardar') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <input type="hidden" id="matricula-tipo-registro" name="tipo_registro" value="existente">

            <!-- Selector de Modo: Alumno Existente vs Nuevo Alumno -->
            <div class="flex items-center p-1 bg-slate-100 rounded-xl border border-slate-200">
                <button type="button" id="tab-alumno-existente" onclick="cambiarTipoMatricula('existente')" class="flex-1 py-1.5 px-3 rounded-lg text-xs font-bold transition-all bg-white text-slate-900 shadow-2xs">
                    Alumno Existente
                </button>
                <button type="button" id="tab-alumno-nuevo" onclick="cambiarTipoMatricula('nuevo')" class="flex-1 py-1.5 px-3 rounded-lg text-xs font-bold transition-all text-slate-500 hover:text-slate-900">
                    + Registrar Nuevo Alumno
                </button>
            </div>

            <!-- 1. MODO: ALUMNO EXISTENTE -->
            <div id="seccion-alumno-existente">
                <label for="matricula-estudiante" class="block text-xs font-semibold text-slate-700 mb-1">Seleccionar Estudiante Existente</label>
                <select id="matricula-estudiante" name="estudiante_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                    <option value="">-- Selecciona el estudiante --</option>
                    @foreach($estudiantes ?? [] as $estudiante)
                        <option value="{{ $estudiante->id }}">{{ $estudiante->name }} ({{ $estudiante->rut ?? $estudiante->email }})</option>
                    @endforeach
                </select>
            </div>

            <!-- 2. MODO: CREAR NUEVO ALUMNO -->
            <div id="seccion-alumno-nuevo" class="hidden space-y-3 p-3.5 bg-amber-50/50 rounded-xl border border-amber-200/80">
                <div>
                    <label for="nuevo-alumno-nombre" class="block text-xs font-semibold text-slate-800 mb-1">Nombre Completo del Alumno</label>
                    <input type="text" id="nuevo-alumno-nombre" name="nombre_estudiante" placeholder="Ej: Javier Ignacio Silva Contreras" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="nuevo-alumno-email" class="block text-xs font-semibold text-slate-800 mb-1">Correo Electrónico</label>
                        <input type="email" id="nuevo-alumno-email" name="email_estudiante" placeholder="alumno@aula-portal.cl" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                    </div>
                    <div>
                        <label for="nuevo-alumno-rut" class="block text-xs font-semibold text-slate-800 mb-1">RUT (sin puntos con guión)</label>
                        <input type="text" id="nuevo-alumno-rut" name="rut_estudiante" placeholder="22334455-6" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-amber-500">
                    </div>
                </div>

                <div>
                    <label for="nuevo-alumno-password" class="block text-xs font-semibold text-slate-800 mb-1">Contraseña Inicial (opcional)</label>
                    <input type="password" id="nuevo-alumno-password" name="password_estudiante" placeholder="Por defecto: estudiante2026" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <!-- CAMPOS COMUNES DE MATRÍCULA -->
            <div>
                <label for="matricula-curso" class="block text-xs font-semibold text-slate-700 mb-1">Curso de Destino</label>
                <select id="matricula-curso" name="curso_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                    <option value="">-- Selecciona el curso --</option>
                    @foreach($cursos ?? [] as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre }} ({{ $c->nivel }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="matricula-lista" class="block text-xs font-semibold text-slate-700 mb-1">Número de Lista</label>
                    <input type="number" id="matricula-lista" name="numero_lista" value="{{ ($matriculas->max('numero_lista') ?? 0) + 1 }}" min="1" max="60" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label for="matricula-anio" class="block text-xs font-semibold text-slate-700 mb-1">Año Lectivo</label>
                    <input type="number" id="matricula-anio" name="anio" value="2026" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <div>
                <label for="matricula-apoderado" class="block text-xs font-semibold text-slate-700 mb-1">Apoderado Responsable</label>
                <select id="matricula-apoderado" name="apoderado_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                    <option value="">-- Sin apoderado registrado --</option>
                    @foreach($apoderados ?? [] as $apoderado)
                        <option value="{{ $apoderado->id }}">{{ $apoderado->name }} ({{ $apoderado->rut ?? $apoderado->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" data-cerrar-modal="modal-nueva-matricula" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-amber-500 text-slate-950 text-xs font-bold rounded-xl hover:bg-amber-600 shadow-xs">
                    Completar Matrícula
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: EDITAR MATRÍCULA (SOLO SUPERUSUARIO)               -->
<!-- ======================================================== -->
<div id="modal-editar-matricula" class="modal-fondo fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-purple-100 text-purple-800 text-[10px] font-bold uppercase mb-1">
                    Exclusivo SuperUsuario
                </div>
                <h3 class="text-base font-bold text-slate-900">Editar Matrícula Escolar</h3>
            </div>
            <button type="button" data-cerrar-modal="modal-editar-matricula" class="p-1 text-slate-400 hover:text-slate-700 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="form-editar-matricula" method="POST" class="mt-4 space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="editar-matricula-lista" class="block text-xs font-semibold text-slate-700 mb-1">Número de Lista</label>
                    <input type="number" id="editar-matricula-lista" name="numero_lista" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label for="editar-matricula-estado" class="block text-xs font-semibold text-slate-700 mb-1">Estado de Matrícula</label>
                    <select id="editar-matricula-estado" name="estado" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                        <option value="regular">Regular</option>
                        <option value="retirado">Retirado</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="editar-matricula-apoderado" class="block text-xs font-semibold text-slate-700 mb-1">Apoderado</label>
                <select id="editar-matricula-apoderado" name="apoderado_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                    <option value="">-- Sin apoderado --</option>
                    @foreach($apoderados ?? [] as $apoderado)
                        <option value="{{ $apoderado->id }}">{{ $apoderado->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" data-cerrar-modal="modal-editar-matricula" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-xs font-bold rounded-xl hover:bg-purple-700 shadow-xs">
                    Actualizar Matrícula
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: VINCULAR PUPILOS A APODERADO                      -->
<!-- ======================================================== -->
<div id="modal-vincular-apoderado" class="modal-fondo fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs">
    <div class="w-full max-w-xl rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-amber-100 text-amber-800 text-[10px] font-bold uppercase mb-1">
                    Gestión Multifamiliar
                </div>
                <h3 class="text-base font-bold text-slate-900">Vincular Pupilos a Apoderado</h3>
                <p class="text-xs text-slate-500">Asocia uno o más hijos (estudiantes) a un mismo apoderado legal</p>
            </div>
            <button type="button" data-cerrar-modal="modal-vincular-apoderado" class="p-1 text-slate-400 hover:text-slate-700 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('matriculas.asociar_apoderado') }}" method="POST" class="mt-4 space-y-4">
            @csrf

            <!-- Selector de Apoderado -->
            <div>
                <label for="vincular-apoderado-id" class="block text-xs font-semibold text-slate-700 mb-1">
                    Apoderado Responsable
                </label>
                <select id="vincular-apoderado-id" name="apoderado_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-medium text-slate-900 focus:outline-none focus:border-amber-500">
                    <option value="">-- Selecciona el apoderado --</option>
                    @foreach($apoderados ?? [] as $apoderado)
                        @php
                            $hijosCount = $matriculas->where('apoderado_id', $apoderado->id)->count();
                        @endphp
                        <option value="{{ $apoderado->id }}">
                            {{ $apoderado->name }} ({{ $apoderado->rut ?? $apoderado->email }}) - {{ $hijosCount }} pupilo(s) actual(es)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Listado de Estudiantes con Checkboxes -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-xs font-semibold text-slate-700">
                        Seleccionar Alumnos a Vincular (Hijos / Pupilos):
                    </label>
                    <div class="flex items-center gap-2">
                        <button type="button" onclick="marcarTodosAlumnos(true)" class="text-[11px] font-bold text-amber-600 hover:text-amber-700 cursor-pointer">
                            Marcar todos
                        </button>
                        <span class="text-slate-300">•</span>
                        <button type="button" onclick="marcarTodosAlumnos(false)" class="text-[11px] font-bold text-slate-500 hover:text-slate-700 cursor-pointer">
                            Desmarcar
                        </button>
                    </div>
                </div>

                <div class="max-h-60 overflow-y-auto rounded-xl border border-slate-200 bg-slate-50 divide-y divide-slate-200 p-1">
                    @forelse($matriculas as $mat)
                        <label class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-white transition-colors cursor-pointer text-xs">
                            <input type="checkbox" name="estudiante_ids[]" value="{{ $mat->estudiante_id }}" class="checkbox-alumno rounded text-amber-500 focus:ring-amber-400">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <p class="font-bold text-slate-900 truncate">{{ $mat->estudiante?->name }}</p>
                                    <span class="text-[10px] font-mono text-slate-500">{{ $mat->estudiante?->rut }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-[11px] text-slate-500 mt-0.5">
                                    <span class="font-medium text-slate-700">{{ $mat->curso?->nombre }} (N° {{ $mat->numero_lista }})</span>
                                    <span>•</span>
                                    <span>Apoderado actual: <strong class="text-slate-700 font-semibold">{{ $mat->apoderado?->name ?? 'Sin asignar' }}</strong></span>
                                </div>
                            </div>
                        </label>
                    @empty
                        <div class="p-4 text-center text-xs text-slate-500">
                            No hay alumnos matriculados disponibles.
                        </div>
                    @endforelse
                </div>
                <p class="text-[11px] text-slate-500 mt-1.5">
                    💡 Puedes seleccionar dos o más alumnos para asignarlos a un mismo apoderado legal.
                </p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" data-cerrar-modal="modal-vincular-apoderado" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-amber-500 text-slate-950 text-xs font-bold rounded-xl hover:bg-amber-600 shadow-xs flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                    <span>Guardar Vinculación Familiar</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function marcarTodosAlumnos(marcar) {
        document.querySelectorAll('.checkbox-alumno').forEach(cb => {
            cb.checked = marcar;
        });
    }
    function cambiarTipoMatricula(tipo) {
        const inputTipo = document.getElementById('matricula-tipo-registro');
        const tabExistente = document.getElementById('tab-alumno-existente');
        const tabNuevo = document.getElementById('tab-alumno-nuevo');
        const seccionExistente = document.getElementById('seccion-alumno-existente');
        const seccionNuevo = document.getElementById('seccion-alumno-nuevo');
        const selectEstudiante = document.getElementById('matricula-estudiante');
        const inputNuevoNombre = document.getElementById('nuevo-alumno-nombre');
        const inputNuevoEmail = document.getElementById('nuevo-alumno-email');

        if (!inputTipo) return;

        if (tipo === 'nuevo') {
            inputTipo.value = 'nuevo';
            if (tabNuevo) tabNuevo.className = 'flex-1 py-1.5 px-3 rounded-lg text-xs font-bold transition-all bg-white text-slate-900 shadow-2xs';
            if (tabExistente) tabExistente.className = 'flex-1 py-1.5 px-3 rounded-lg text-xs font-bold transition-all text-slate-500 hover:text-slate-900';
            if (seccionExistente) seccionExistente.classList.add('hidden');
            if (seccionNuevo) seccionNuevo.classList.remove('hidden');
            if (selectEstudiante) selectEstudiante.removeAttribute('required');
            if (inputNuevoNombre) inputNuevoNombre.setAttribute('required', 'required');
            if (inputNuevoEmail) inputNuevoEmail.setAttribute('required', 'required');
        } else {
            inputTipo.value = 'existente';
            if (tabExistente) tabExistente.className = 'flex-1 py-1.5 px-3 rounded-lg text-xs font-bold transition-all bg-white text-slate-900 shadow-2xs';
            if (tabNuevo) tabNuevo.className = 'flex-1 py-1.5 px-3 rounded-lg text-xs font-bold transition-all text-slate-500 hover:text-slate-900';
            if (seccionExistente) seccionExistente.classList.remove('hidden');
            if (seccionNuevo) seccionNuevo.classList.add('hidden');
            if (selectEstudiante) selectEstudiante.setAttribute('required', 'required');
            if (inputNuevoNombre) inputNuevoNombre.removeAttribute('required');
            if (inputNuevoEmail) inputNuevoEmail.removeAttribute('required');
        }
    }

    function editarMatricula(id, lista, estado, apoderadoId) {
        const form = document.getElementById('form-editar-matricula');
        form.action = '/matriculas/' + id;
        document.getElementById('editar-matricula-lista').value = lista;
        document.getElementById('editar-matricula-estado').value = estado;
        document.getElementById('editar-matricula-apoderado').value = apoderadoId || '';
        
        const modal = document.getElementById('modal-editar-matricula');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Filtrado en vivo e instantáneo de alumnos matriculados
    document.addEventListener('DOMContentLoaded', () => {
        const inputBuscar = document.getElementById('filtro-buscador-matricula');
        const selectCurso = document.getElementById('filtro-curso-matricula');
        const selectEstado = document.getElementById('filtro-estado-matricula');
        const filas = document.querySelectorAll('.fila-matricula');
        const contador = document.getElementById('contador-visibles-matricula');

        function filtrarFilas() {
            const texto = (inputBuscar?.value || '').toLowerCase().trim();
            const curso = (selectCurso?.value || '').toLowerCase().trim();
            const estado = (selectEstado?.value || '').toLowerCase().trim();

            let visibles = 0;

            filas.forEach(fila => {
                const nombre = fila.getAttribute('data-nombre') || '';
                const rut = fila.getAttribute('data-rut') || '';
                const c = fila.getAttribute('data-curso') || '';
                const est = fila.getAttribute('data-estado') || '';

                const coincideTexto = !texto || nombre.includes(texto) || rut.includes(texto);
                const coincideCurso = !curso || c.includes(curso);
                const coincideEstado = !estado || est === estado;

                if (coincideTexto && coincideCurso && coincideEstado) {
                    fila.classList.remove('hidden');
                    visibles++;
                } else {
                    fila.classList.add('hidden');
                }
            });

            if (contador) {
                contador.textContent = `${visibles} alumnos`;
            }
        }

        inputBuscar?.addEventListener('input', filtrarFilas);
        selectCurso?.addEventListener('change', filtrarFilas);
        selectEstado?.addEventListener('change', filtrarFilas);
    });
</script>
@endsection
