@extends('layouts.app')

@section('titulo', 'Usuarios y Roles')
@section('encabezado', 'Administración TI de Usuarios')

@section('contenido')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Encabezado en Tarjeta Blanca -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 rounded-2xl bg-white border border-slate-200 shadow-xs">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-50 text-purple-800 border border-purple-200 mb-2">
                <span>Seguridad y Permisos TI</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Control de Cuentas y Roles Institucionales
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">
                Gestión de cuentas institucionales para SuperUsuario, Administrador, Docente, Estudiante y Apoderado.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <button type="button" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                </svg>
                <span>Crear Cuenta de Usuario</span>
            </button>
        </div>
    </div>

    <!-- Estadísticas por Rol en Blanco -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
        <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[11px] font-bold text-purple-700 uppercase tracking-wider">SuperUsuario</span>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $usuarios->where('rol.value', 'superusuario')->count() }}</p>
            <p class="text-[11px] text-slate-500">Acceso total TI</p>
        </div>
        <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Administrador</span>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $usuarios->where('rol.value', 'administrador')->count() }}</p>
            <p class="text-[11px] text-slate-500">Dirección y gestión</p>
        </div>
        <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[11px] font-bold text-amber-700 uppercase tracking-wider">Docentes</span>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $usuarios->where('rol.value', 'docente')->count() }}</p>
            <p class="text-[11px] text-slate-500">Profesores activos</p>
        </div>
        <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs">
            <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">Estudiantes</span>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $usuarios->where('rol.value', 'estudiante')->count() }}</p>
            <p class="text-[11px] text-slate-500">Alumnos del colegio</p>
        </div>
        <div class="p-4 rounded-xl bg-white border border-slate-200 shadow-xs col-span-2 sm:col-span-1">
            <span class="text-[11px] font-bold text-cyan-700 uppercase tracking-wider">Apoderados</span>
            <p class="text-2xl font-bold text-slate-900 mt-1">{{ $usuarios->where('rol.value', 'apoderado')->count() }}</p>
            <p class="text-[11px] text-slate-500">Tutores legales</p>
        </div>
    </div>

    <!-- Lista de Usuarios en Blanco -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-200 flex items-center justify-between bg-slate-50/70">
            <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Nómina de Usuarios del Sistema</h3>
            <span class="text-xs text-slate-500 font-mono">{{ $usuarios->count() }} cuentas registradas</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-700">
                <thead class="bg-slate-100/70 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-5 py-3 font-semibold">Usuario</th>
                        <th class="px-5 py-3 font-semibold">Correo Electrónico</th>
                        <th class="px-5 py-3 font-semibold">RUT</th>
                        <th class="px-5 py-3 text-center font-semibold">Rol Asignado</th>
                        <th class="px-5 py-3 text-center font-semibold">Estado</th>
                        <th class="px-5 py-3 text-right font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($usuarios as $u)
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-5 py-3.5 font-bold text-slate-900">
                                {{ $u->name }}
                            </td>
                            <td class="px-5 py-3.5 font-mono text-slate-500">
                                {{ $u->email }}
                            </td>
                            <td class="px-5 py-3.5 font-mono text-slate-500">
                                {{ $u->rut ?? 'Sin RUT' }}
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $u->rol?->obtenerClasesInsignia() ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $u->rol?->obtenerEtiqueta() ?? 'Usuario' }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Activo
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <button type="button" class="text-xs font-bold text-amber-600 hover:text-amber-700 cursor-pointer">
                                    Editar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-8 text-center text-slate-500">
                                No se encontraron usuarios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
