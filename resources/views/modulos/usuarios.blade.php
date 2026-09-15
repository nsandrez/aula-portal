@extends('layouts.app')

@section('titulo', 'Usuarios y Permisos')
@section('encabezado', 'Módulo de Administración TI')

@section('contenido')
<div class="space-y-6">
    <!-- Encabezado del Módulo -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 rounded-2xl bg-[#161920] border border-zinc-800">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $usuario?->rol?->obtenerClasesInsignia() ?? 'bg-zinc-800 text-zinc-300' }} border mb-2">
                <span>{{ $usuario?->rol?->obtenerEtiqueta() ?? 'Usuario' }}</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold text-white tracking-tight">
                Control de Usuarios y Roles de Acceso
            </h2>
            <p class="text-xs sm:text-sm text-zinc-400 mt-1">
                Administración de cuentas institucionales para SuperUsuario, Administrador, Docente, Estudiante y Apoderado.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-zinc-950 font-semibold text-xs rounded-xl shadow-sm transition-colors flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                </svg>
                <span>Crear Usuario</span>
            </button>
        </div>
    </div>

    <!-- Estadísticas por Rol -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="p-4 rounded-xl bg-[#161920] border border-zinc-800">
            <span class="text-[10px] font-semibold text-purple-400 uppercase tracking-wider">SuperUsuario</span>
            <p class="text-xl font-bold text-white mt-1">2</p>
            <p class="text-[10px] text-zinc-500">Acceso total TI</p>
        </div>
        <div class="p-4 rounded-xl bg-[#161920] border border-zinc-800">
            <span class="text-[10px] font-semibold text-blue-400 uppercase tracking-wider">Administrador</span>
            <p class="text-xl font-bold text-white mt-1">5</p>
            <p class="text-[10px] text-zinc-500">Directivos</p>
        </div>
        <div class="p-4 rounded-xl bg-[#161920] border border-zinc-800">
            <span class="text-[10px] font-semibold text-amber-400 uppercase tracking-wider">Docentes</span>
            <p class="text-xl font-bold text-white mt-1">24</p>
            <p class="text-[10px] text-zinc-500">Profesores activos</p>
        </div>
        <div class="p-4 rounded-xl bg-[#161920] border border-zinc-800">
            <span class="text-[10px] font-semibold text-emerald-400 uppercase tracking-wider">Estudiantes</span>
            <p class="text-xl font-bold text-white mt-1">542</p>
            <p class="text-[10px] text-zinc-500">Alumnos regulares</p>
        </div>
        <div class="p-4 rounded-xl bg-[#161920] border border-zinc-800">
            <span class="text-[10px] font-semibold text-cyan-400 uppercase tracking-wider">Apoderados</span>
            <p class="text-xl font-bold text-white mt-1">498</p>
            <p class="text-[10px] text-zinc-500">Tutores legales</p>
        </div>
    </div>

    <!-- Lista de Usuarios -->
    <div class="rounded-2xl bg-[#161920] border border-zinc-800 overflow-hidden">
        <div class="p-4 border-b border-zinc-800 flex items-center justify-between">
            <h3 class="text-sm font-semibold uppercase tracking-wider text-zinc-400">Nómina de Usuarios del Sistema</h3>
            <span class="text-xs text-zinc-500 font-mono">5 roles configurados</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-300">
                <thead class="bg-[#12141a] text-zinc-400 uppercase text-[11px] tracking-wider border-b border-zinc-800">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Usuario</th>
                        <th class="px-4 py-3 font-semibold">Correo Electrónico</th>
                        <th class="px-4 py-3 font-semibold">RUT</th>
                        <th class="px-4 py-3 text-center font-semibold">Rol Asignado</th>
                        <th class="px-4 py-3 text-center font-semibold">Estado</th>
                        <th class="px-4 py-3 text-right font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/80">
                    <tr class="hover:bg-zinc-800/30 transition-colors">
                        <td class="px-4 py-3.5 font-medium text-white">Nicolás Sánchez</td>
                        <td class="px-4 py-3.5 text-zinc-400 font-mono">admin@aula-portal.cl</td>
                        <td class="px-4 py-3.5 font-mono text-zinc-400">12345678-9</td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-purple-500/10 text-purple-400 border border-purple-500/30">SuperUsuario</span>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Activo</span>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <button type="button" class="text-xs text-amber-400 hover:text-amber-300 font-medium cursor-pointer">Editar Rol</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-zinc-800/30 transition-colors">
                        <td class="px-4 py-3.5 font-medium text-white">Dirección Académica</td>
                        <td class="px-4 py-3.5 text-zinc-400 font-mono">direccion@colegio.cl</td>
                        <td class="px-4 py-3.5 font-mono text-zinc-400">11223344-5</td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-500/10 text-blue-400 border border-blue-500/30">Administrador</span>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Activo</span>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <button type="button" class="text-xs text-amber-400 hover:text-amber-300 font-medium cursor-pointer">Editar Rol</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-zinc-800/30 transition-colors">
                        <td class="px-4 py-3.5 font-medium text-white">Prof. Rodrigo Sánchez</td>
                        <td class="px-4 py-3.5 text-zinc-400 font-mono">profesor.sanchez@colegio.cl</td>
                        <td class="px-4 py-3.5 font-mono text-zinc-400">15678901-2</td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/30">Docente</span>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30">Activo</span>
                        </td>
                        <td class="px-4 py-3.5 text-right">
                            <button type="button" class="text-xs text-amber-400 hover:text-amber-300 font-medium cursor-pointer">Editar Rol</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
