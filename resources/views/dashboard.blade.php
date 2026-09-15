@extends('layouts.app')

@section('titulo', 'Panel General')
@section('encabezado', 'Panel General Institucional')

@section('contenido')
<div class="space-y-6 max-w-[1600px] w-full mx-auto">
    <!-- Banner de Bienvenida Institucional Azul Marino Académico -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 border border-slate-800 p-7 sm:p-8 shadow-lg text-white">
        <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="space-y-2.5">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-slate-800/90 text-amber-400 border border-amber-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                    <span>{{ $usuario?->rol?->obtenerEtiqueta() ?? 'Invitado' }}</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                    ¡Bienvenido(a), {{ $usuario?->name ?? 'Usuario' }}!
                </h2>
                <p class="text-sm text-slate-300 max-w-2xl leading-relaxed">
                    Portal de gestión escolar para el seguimiento curricular, libro digital de notas y control diario de asistencia escolar.
                </p>
            </div>

            <!-- Resumen Rápido -->
            <div class="flex items-center gap-3 self-start md:self-center shrink-0">
                <div class="p-3.5 bg-slate-800/90 rounded-2xl border border-slate-700/80 flex items-center gap-3 shadow-md">
                    <div class="w-11 h-11 rounded-xl bg-amber-400 text-slate-950 font-bold flex items-center justify-center text-sm shadow-sm">
                        2026
                    </div>
                    <div>
                        <p class="text-xs text-amber-400 font-bold">Régimen Semestral</p>
                        <p class="text-[11px] text-slate-300 font-medium">Primer Semestre en Curso</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Indicadores Estadísticos Claves con Acentos de Color -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <div class="p-6 rounded-2xl bg-white border border-slate-200/90 border-l-4 border-l-blue-500 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                    {{ $usuario?->esApoderado() ? 'Pupilos a Cargo' : 'Cursos Activos' }}
                </span>
                <span class="w-9 h-9 rounded-xl bg-blue-50 text-blue-700 border border-blue-200 flex items-center justify-center font-bold text-xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 mt-2">
                @if($usuario?->esApoderado())
                    {{ $pupilos->count() }} Hijos
                @else
                    {{ $cursos->count() }} Cursos
                @endif
            </p>
            <p class="text-xs text-blue-700 font-medium mt-1">
                {{ $usuario?->esApoderado() ? 'Estudiantes asociados' : 'Con asignaturas asociadas' }}
            </p>
        </div>

        <div class="p-6 rounded-2xl bg-white border border-slate-200/90 border-l-4 border-l-emerald-500 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                    {{ $usuario?->esApoderado() ? 'Cursos Vinculados' : 'Estudiantes Matriculados' }}
                </span>
                <span class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center font-bold text-xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 mt-2">
                @if($usuario?->esApoderado())
                    {{ $pupilos->pluck('curso_id')->unique()->count() }} Cursos
                @else
                    {{ $totalEstudiantes }} Alumnos
                @endif
            </p>
            <p class="text-xs text-emerald-700 font-medium mt-1">
                {{ $usuario?->esApoderado() ? 'Niveles escolares cursados' : 'Matrícula escolar 2026' }}
            </p>
        </div>

        <div class="p-6 rounded-2xl bg-white border border-slate-200/90 border-l-4 border-l-amber-500 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                    {{ $usuario?->esApoderado() ? 'Promedio Asistencia' : 'Asistencia General' }}
                </span>
                <span class="w-9 h-9 rounded-xl bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center font-bold text-xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 mt-2">
                @if($usuario?->esEstudiante())
                    {{ $porcentajeAsistencia }}%
                @elseif($usuario?->esApoderado())
                    {{ number_format($pupilos->avg('porcentaje_asistencia') ?? 100, 1) }}%
                @else
                    95.2%
                @endif
            </p>
            <p class="text-xs text-amber-800 font-medium mt-1">
                @if($usuario?->esEstudiante())
                    Mi asistencia acumulada
                @elseif($usuario?->esApoderado())
                    Promedio de mis pupilos
                @else
                    Promedio general del colegio
                @endif
            </p>
        </div>

        <div class="p-6 rounded-2xl bg-white border border-slate-200/90 border-l-4 border-l-purple-500 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">
                    {{ $usuario?->esApoderado() ? 'Estado Escolar' : 'Cuerpo Docente' }}
                </span>
                <span class="w-9 h-9 rounded-xl bg-purple-50 text-purple-700 border border-purple-200 flex items-center justify-center font-bold text-xs">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-extrabold text-slate-900 mt-2">
                @if($usuario?->esApoderado())
                    Regular
                @else
                    100%
                @endif
            </p>
            <p class="text-xs text-purple-700 font-medium mt-1">
                {{ $usuario?->esApoderado() ? 'Al día ciclo escolar 2026' : 'Asignaturas cubiertas' }}
            </p>
        </div>
    </div>

    <!-- Sección de Pupilos Asignados para Apoderado -->
    @if($usuario?->esApoderado())
        <div class="space-y-4">
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500">
                    Mis Pupilos / Hijos a Cargo ({{ $pupilos->count() }})
                </h3>
                <p class="text-xs text-slate-600 mt-0.5">
                    Acceso directo al libro de notas y control de asistencia individual de cada uno de tus hijos.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @forelse($pupilos as $pupilo)
                    <div class="p-6 rounded-2xl bg-white border border-slate-200 hover:border-amber-400/80 transition-all shadow-xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-start justify-between gap-3 mb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-800 font-bold flex items-center justify-center text-sm border border-amber-200">
                                        {{ strtoupper(substr($pupilo->estudiante?->name ?? 'P', 0, 2)) }}
                                    </div>
                                    <div>
                                        <h4 class="text-base font-bold text-slate-900">{{ $pupilo->estudiante?->name }}</h4>
                                        <p class="text-xs text-slate-500 font-mono">{{ $pupilo->estudiante?->rut ?? $pupilo->estudiante?->email }}</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    {{ $pupilo->curso?->nombre }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-3 py-3 border-y border-slate-100 text-xs">
                                <div>
                                    <span class="text-slate-400 block font-medium">Profesor(a) Jefe:</span>
                                    <span class="font-semibold text-slate-800">{{ $pupilo->curso?->profesorJefe?->name ?? 'Por asignar' }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block font-medium">Asistencia Acumulada:</span>
                                    <span class="font-bold text-emerald-600 font-mono">{{ $pupilo->porcentaje_asistencia ?? 100 }}%</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 pt-3 flex items-center justify-between gap-3">
                            <a href="{{ route('notas.index', ['pupilo_id' => $pupilo->estudiante_id]) }}" class="flex-1 py-2 px-3 text-center bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold rounded-xl transition-colors">
                                Ver Calificaciones
                            </a>
                            <a href="{{ route('asistencias.index', ['pupilo_id' => $pupilo->estudiante_id]) }}" class="flex-1 py-2 px-3 text-center bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-bold rounded-xl shadow-xs transition-colors">
                                Ver Asistencias
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="p-8 rounded-2xl bg-white border border-slate-200 text-center col-span-2 text-slate-500 text-xs">
                        No tienes pupilos matriculados asignados actualmente. Contacta a la Dirección del establecimiento.
                    </div>
                @endforelse
            </div>
        </div>
    @endif

    <!-- Módulos del Sistema en Tarjetas con Acentos de Color -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500">
                Módulos del Sistema
            </h3>
            <span class="text-xs text-slate-400 font-medium">Accesos rápidos escolares</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            
            <!-- Módulo Calificaciones (Ámbar) -->
            <div class="p-6 rounded-2xl bg-white border border-slate-200/90 border-t-4 border-t-amber-500 hover:border-amber-400 hover:shadow-md transition-all shadow-sm group flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-100 text-amber-800 border border-amber-200 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform shadow-xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-900">Notas y Calificaciones</h4>
                    <p class="mt-1.5 text-xs text-slate-600 leading-relaxed">
                        @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
                            Registro de notas por asignatura asociada a cada curso, ponderaciones y actas de examen.
                        @elseif($usuario?->esEstudiante())
                            Consulta de tus notas parciales por materia y promedio semestral acumulado.
                        @else
                            Boletín de notas consolidado y situación académica de tus pupilos.
                        @endif
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <a href="{{ route('notas.index') }}" class="inline-flex items-center justify-between w-full px-3.5 py-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 border border-amber-200/80 text-xs font-bold transition-colors">
                        <span>Ingresar a Calificaciones</span>
                        <svg class="w-3.5 h-3.5 text-amber-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Módulo Asistencias (Esmeralda) -->
            <div class="p-6 rounded-2xl bg-white border border-slate-200/90 border-t-4 border-t-emerald-500 hover:border-emerald-400 hover:shadow-md transition-all shadow-sm group flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform shadow-xs">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="text-lg font-bold text-slate-900">Control de Asistencia Diaria</h4>
                    <p class="mt-1.5 text-xs text-slate-600 leading-relaxed">
                        @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
                            Pase de lista diario día a día por curso, registro de atrasos con hora exacta y faltas justificadas.
                        @elseif($usuario?->esEstudiante())
                            Historial detallado de asistencia diaria y porcentaje de cumplimiento legal.
                        @else
                            Notificación de inasistencias diarias, registro de atrasos y justificaciones.
                        @endif
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100">
                    <a href="{{ route('asistencias.index') }}" class="inline-flex items-center justify-between w-full px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-900 border border-emerald-200/80 text-xs font-bold transition-colors">
                        <span>Ingresar a Asistencias</span>
                        <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Módulo Cursos y Asignaturas (Azul) -->
            @if($usuario?->tieneRol(\App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
                <div class="p-6 rounded-2xl bg-white border border-slate-200/90 border-t-4 border-t-blue-500 hover:border-blue-400 hover:shadow-md transition-all shadow-sm group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-100 text-blue-800 border border-blue-200 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform shadow-xs">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900">Cursos y Asignaturas</h4>
                        <p class="mt-1.5 text-xs text-slate-600 leading-relaxed">
                            Organización curricular: cada curso cuenta con sus asignaturas asociadas, carga horaria semanal y docentes titulares.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <a href="{{ route('cursos.index') }}" class="inline-flex items-center justify-between w-full px-3.5 py-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-900 border border-blue-200/80 text-xs font-bold transition-colors">
                            <span>Ver Asignaturas por Curso</span>
                            <svg class="w-3.5 h-3.5 text-blue-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Módulo Matrículas (Teal / Verde Azulado) -->
            @if($usuario?->tieneRol(\App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
                <div class="p-6 rounded-2xl bg-white border border-slate-200/90 border-t-4 border-t-teal-500 hover:border-teal-400 hover:shadow-md transition-all shadow-sm group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-800 border border-teal-200 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform shadow-xs">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900">Matrículas Escolares</h4>
                        <p class="mt-1.5 text-xs text-slate-600 leading-relaxed">
                            Asignación de estudiantes a sus respectivos cursos, números de lista y vinculación con apoderados legales.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <a href="{{ route('matriculas.index') }}" class="inline-flex items-center justify-between w-full px-3.5 py-2 rounded-xl bg-teal-50 hover:bg-teal-100 text-teal-900 border border-teal-200/80 text-xs font-bold transition-colors">
                            <span>Gestionar Matrículas</span>
                            <svg class="w-3.5 h-3.5 text-teal-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endif

            <!-- Módulo TI Usuarios (Púrpura / Morado) -->
            @if($usuario?->esSuperUsuario())
                <div class="p-6 rounded-2xl bg-white border border-slate-200/90 border-t-4 border-t-purple-500 hover:border-purple-400 hover:shadow-md transition-all shadow-sm group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-800 border border-purple-200 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform shadow-xs">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900">Administración TI</h4>
                        <p class="mt-1.5 text-xs text-slate-600 leading-relaxed">
                            Control de cuentas, asignación de roles jerárquicos y seguridad del sistema Aula Portal.
                        </p>
                    </div>
                    <div class="mt-6 pt-4 border-t border-slate-100">
                        <a href="{{ route('usuarios.index') }}" class="inline-flex items-center justify-between w-full px-3.5 py-2 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-900 border border-purple-200/80 text-xs font-bold transition-colors">
                            <span>Configuración de Usuarios</span>
                            <svg class="w-3.5 h-3.5 text-purple-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
