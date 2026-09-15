@extends('layouts.app')

@section('titulo', 'Panel Principal')
@section('encabezado', 'Panel de Control Escolar')

@section('contenido')
<div class="space-y-6">
    <!-- Banner de Bienvenida Institucional -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-[#161920] to-[#1a1e27] border border-zinc-800 p-6 sm:p-8">
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold {{ $usuario?->rol?->obtenerClasesInsignia() ?? 'bg-zinc-800 text-zinc-300' }} border mb-3">
                <span>{{ $usuario?->rol?->obtenerEtiqueta() ?? 'Invitado' }}</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
                ¡Bienvenido(a), {{ $usuario?->name ?? 'Usuario' }}!
            </h2>
            <p class="mt-1.5 text-sm text-zinc-400 max-w-2xl">
                Has ingresado a tu plataforma académica de <strong>Aula Portal</strong>. Desde este panel tienes acceso a las herramientas asignadas a tu rol escolar.
            </p>
        </div>
        <!-- Acento decorativo sutil en ámbar institucional -->
        <div class="absolute -right-10 -bottom-10 w-48 h-48 rounded-full bg-amber-500/5 blur-3xl pointer-events-none"></div>
    </div>

    <!-- Módulos y Accesos Rápidos según el Rol -->
    <div>
        <h3 class="text-sm font-semibold uppercase tracking-wider text-zinc-400 mb-4">
            Módulos del Sistema
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            
            <!-- Tarjeta Módulo de Calificaciones -->
            <div class="p-6 rounded-2xl bg-[#161920] border border-zinc-800 hover:border-amber-500/40 transition-colors group flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </div>
                    <h4 class="text-base font-semibold text-white">Notas y Calificaciones</h4>
                    <p class="mt-1 text-xs text-zinc-400 leading-relaxed">
                        @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
                            Registro, ponderaciones y actas oficiales de notas por asignatura.
                        @elseif($usuario?->esEstudiante())
                            Consulta de calificaciones parciales y promedios semestrales.
                        @else
                            Boletines de notas y rendimiento escolar de tus pupilos.
                        @endif
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-zinc-800/80">
                    <a href="{{ route('notas.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-400 hover:text-amber-300">
                        <span>Acceder a Notas</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Tarjeta Módulo de Asistencias -->
            <div class="p-6 rounded-2xl bg-[#161920] border border-zinc-800 hover:border-amber-500/40 transition-colors group flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h4 class="text-base font-semibold text-white">Control de Asistencia</h4>
                    <p class="mt-1 text-xs text-zinc-400 leading-relaxed">
                        @if($usuario?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
                            Pase de lista diario por curso con estados de atraso y justificación.
                        @elseif($usuario?->esEstudiante())
                            Historial de asistencia y porcentaje acumulado del año lectivo.
                        @else
                            Seguimiento de asistencia diaria, atrasos y justificación de faltas.
                        @endif
                    </p>
                </div>
                <div class="mt-5 pt-4 border-t border-zinc-800/80">
                    <a href="{{ route('asistencias.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-amber-400 hover:text-amber-300">
                        <span>Acceder a Asistencias</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Tarjeta Módulo Administrativo (o Información General para Estudiantes/Apoderados) -->
            @if($usuario?->tieneRol(\App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
                <div class="p-6 rounded-2xl bg-[#161920] border border-zinc-800 hover:border-amber-500/40 transition-colors group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-blue-500/10 text-blue-400 border border-blue-500/20 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                            </svg>
                        </div>
                        <h4 class="text-base font-semibold text-white">Cursos y Matrículas</h4>
                        <p class="mt-1 text-xs text-zinc-400 leading-relaxed">
                            Organización de niveles académicos, asignación de profesores jefes y lista escolar.
                        </p>
                    </div>
                    <div class="mt-5 pt-4 border-t border-zinc-800/80">
                        <a href="{{ route('cursos.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-400 hover:text-blue-300">
                            <span>Gestionar Cursos</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    </div>
                </div>
            @endif

            @if($usuario?->esSuperUsuario())
                <div class="p-6 rounded-2xl bg-[#161920] border border-zinc-800 hover:border-purple-500/40 transition-colors group flex flex-col justify-between">
                    <div>
                        <div class="w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 border border-purple-500/20 flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                            </svg>
                        </div>
                        <h4 class="text-base font-semibold text-white">Administración de Usuarios</h4>
                        <p class="mt-1 text-xs text-zinc-400 leading-relaxed">
                            Control total de cuentas, asignación de roles y parámetros globales del sistema.
                        </p>
                    </div>
                    <div class="mt-5 pt-4 border-t border-zinc-800/80">
                        <a href="{{ route('usuarios.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-purple-400 hover:text-purple-300">
                            <span>Configuración TI</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
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
