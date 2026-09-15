@extends('layouts.app')

@section('titulo', 'Usuarios y Roles')
@section('encabezado', 'Administración TI de Usuarios')

@section('contenido')
<div class="space-y-6 max-w-[1600px] w-full mx-auto">
    <!-- Encabezado en Tarjeta Blanca -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 sm:p-7 rounded-2xl bg-white border border-slate-200/90 shadow-xs">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-900 border border-purple-200 mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                <span>Seguridad y Permisos TI</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                Control de Cuentas y Roles Institucionales
            </h2>
            <p class="text-sm text-slate-600 mt-1 max-w-3xl">
                Gestión de cuentas institucionales y privilegios de acceso para SuperUsuario, Administrador, Docente, Estudiante y Apoderado.
            </p>
        </div>

        @if(auth()->user()?->esSuperUsuario())
            <div class="flex items-center gap-3">
                <button type="button" data-abrir-modal="modal-crear-usuario" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-sm rounded-xl shadow-xs transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                    </svg>
                    <span>Crear Cuenta de Usuario</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Estadísticas por Rol en Blanco -->
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs">
            <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">SuperUsuario</span>
            <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $usuarios->where('rol.value', 'superusuario')->count() }}</p>
            <p class="text-xs text-slate-500 mt-0.5 font-medium">Acceso total TI</p>
        </div>
        <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs">
            <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Administrador</span>
            <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $usuarios->where('rol.value', 'administrador')->count() }}</p>
            <p class="text-xs text-slate-500 mt-0.5 font-medium">Dirección y gestión</p>
        </div>
        <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs">
            <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Docentes</span>
            <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $usuarios->where('rol.value', 'docente')->count() }}</p>
            <p class="text-xs text-slate-500 mt-0.5 font-medium">Profesores activos</p>
        </div>
        <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs">
            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Estudiantes</span>
            <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $usuarios->where('rol.value', 'estudiante')->count() }}</p>
            <p class="text-xs text-slate-500 mt-0.5 font-medium">Alumnos regulares</p>
        </div>
        <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs col-span-2 sm:col-span-1">
            <span class="text-xs font-bold text-cyan-700 uppercase tracking-wider">Apoderados</span>
            <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $usuarios->where('rol.value', 'apoderado')->count() }}</p>
            <p class="text-xs text-slate-500 mt-0.5 font-medium">Tutores de familia</p>
        </div>
    </div>

    <!-- Barra de Filtros y Búsqueda en Tiempo Real -->
    <div class="p-4 sm:p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <div class="relative flex-1 sm:w-80">
                <svg class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input type="text" id="filtro-usuarios-busqueda" placeholder="Buscar por nombre, correo o RUT..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:border-amber-500">
            </div>

            <div class="flex items-center gap-2">
                <label for="filtro-usuarios-rol" class="text-xs font-bold text-slate-600 uppercase tracking-wider">Rol:</label>
                <select id="filtro-usuarios-rol" class="px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:border-amber-500">
                    <option value="">Todos los roles</option>
                    <option value="superusuario">SuperUsuario</option>
                    <option value="administrador">Administrador</option>
                    <option value="docente">Docente</option>
                    <option value="estudiante">Estudiante</option>
                    <option value="apoderado">Apoderado</option>
                </select>
            </div>
        </div>

        <div class="text-xs text-slate-500 font-mono font-medium">
            <span id="contador-usuarios-visibles">{{ $usuarios->count() }}</span> de {{ $usuarios->count() }} cuentas registradas
        </div>
    </div>

    <!-- Lista de Usuarios en Blanco -->
    <div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-700" id="tabla-usuarios">
                <thead class="bg-slate-100/70 text-slate-600 uppercase text-xs font-bold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Usuario</th>
                        <th class="px-6 py-4 font-semibold">Correo Institucional</th>
                        <th class="px-6 py-4 font-semibold">RUT</th>
                        <th class="px-6 py-4 text-center font-semibold">Rol Institucional</th>
                        <th class="px-6 py-4 text-center font-semibold">Estado</th>
                        <th class="px-6 py-4 text-right font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($usuarios as $u)
                        <tr class="fila-usuario hover:bg-slate-50/80 transition-colors" data-nombre="{{ strtolower($u->name) }}" data-email="{{ strtolower($u->email) }}" data-rut="{{ strtolower($u->rut ?? '') }}" data-rol="{{ strtolower($u->rol?->value ?? '') }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-amber-500 text-slate-950 font-bold flex items-center justify-center text-xs shadow-2xs shrink-0">
                                        {{ strtoupper(substr($u->name, 0, 2)) }}
                                    </div>
                                    <span class="font-bold text-slate-900 text-sm">
                                        {{ $u->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-600 text-xs">
                                {{ $u->email }}
                            </td>
                            <td class="px-6 py-4 font-mono text-slate-600 text-xs">
                                {{ $u->rut ?? 'Sin RUT' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $u->rol?->obtenerClasesInsignia() ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $u->rol?->obtenerEtiqueta() ?? 'Usuario' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Activo
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(auth()->user()?->esSuperUsuario())
                                    <button type="button" data-editar-usuario='@json($u)' class="text-xs font-bold text-purple-600 hover:text-purple-700 cursor-pointer" title="Solo SuperUsuario">
                                        Editar Cuenta
                                    </button>
                                @else
                                    <span class="text-xs text-slate-400 font-medium">Solo lectura</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500 text-sm">
                                No se encontraron usuarios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: CREAR CUENTA DE USUARIO (SUPERUSUARIO)             -->
<!-- ======================================================== -->
<div id="modal-crear-usuario" class="modal-fondo fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Crear Cuenta de Usuario</h3>
                <p class="text-xs text-slate-500">Ingresa los datos para registrar un nuevo integrante del colegio</p>
            </div>
            <button type="button" data-cerrar-modal="modal-crear-usuario" class="p-1 text-slate-400 hover:text-slate-700 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('usuarios.guardar') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <div>
                <label for="crear-usuario-nombre" class="block text-xs font-semibold text-slate-700 mb-1">Nombre Completo</label>
                <input type="text" id="crear-usuario-nombre" name="name" placeholder="Ej: Patricia Morales Castro" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="crear-usuario-email" class="block text-xs font-semibold text-slate-700 mb-1">Correo Institucional</label>
                    <input type="email" id="crear-usuario-email" name="email" placeholder="usuario@aula-portal.cl" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label for="crear-usuario-rut" class="block text-xs font-semibold text-slate-700 mb-1">RUT (sin puntos con guión)</label>
                    <input type="text" id="crear-usuario-rut" name="rut" placeholder="12345678-9" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="crear-usuario-rol" class="block text-xs font-semibold text-slate-700 mb-1">Rol en el Portal</label>
                    <select id="crear-usuario-rol" name="rol" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                        @foreach($roles ?? [] as $rol)
                            <option value="{{ $rol->value }}">{{ $rol->obtenerEtiqueta() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="crear-usuario-clave" class="block text-xs font-semibold text-slate-700 mb-1">Contraseña Inicial</label>
                    <input type="password" id="crear-usuario-clave" name="password" placeholder="Mínimo 6 caracteres" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" data-cerrar-modal="modal-crear-usuario" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-amber-500 text-slate-950 text-xs font-bold rounded-xl hover:bg-amber-600 shadow-xs">
                    Crear Cuenta
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: EDITAR USUARIO (SUPERUSUARIO)                      -->
<!-- ======================================================== -->
<div id="modal-editar-usuario" class="modal-fondo fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-purple-100 text-purple-800 text-[10px] font-bold uppercase mb-1">
                    Exclusivo SuperUsuario
                </div>
                <h3 class="text-base font-bold text-slate-900">Editar Información del Usuario</h3>
            </div>
            <button type="button" data-cerrar-modal="modal-editar-usuario" class="p-1 text-slate-400 hover:text-slate-700 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="form-editar-usuario" method="POST" class="mt-4 space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" id="editar-usuario-id" name="id">

            <div>
                <label for="editar-usuario-nombre" class="block text-xs font-semibold text-slate-700 mb-1">Nombre Completo</label>
                <input type="text" id="editar-usuario-nombre" name="name" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="editar-usuario-email" class="block text-xs font-semibold text-slate-700 mb-1">Correo Institucional</label>
                    <input type="email" id="editar-usuario-email" name="email" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                </div>
                <div>
                    <label for="editar-usuario-rut" class="block text-xs font-semibold text-slate-700 mb-1">RUT</label>
                    <input type="text" id="editar-usuario-rut" name="rut" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="editar-usuario-rol" class="block text-xs font-semibold text-slate-700 mb-1">Rol Asignado</label>
                    <select id="editar-usuario-rol" name="rol" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                        @foreach($roles ?? [] as $rol)
                            <option value="{{ $rol->value }}">{{ $rol->obtenerEtiqueta() }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="editar-usuario-clave" class="block text-xs font-semibold text-slate-700 mb-1">Nueva Clave (opcional)</label>
                    <input type="password" id="editar-usuario-clave" name="password" placeholder="Dejar en blanco para conservar" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" data-cerrar-modal="modal-editar-usuario" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-xs font-bold rounded-xl hover:bg-purple-700 shadow-xs">
                    Actualizar Usuario
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const inputBuscar = document.getElementById('filtro-usuarios-busqueda');
        const selectRol = document.getElementById('filtro-usuarios-rol');
        const filas = document.querySelectorAll('.fila-usuario');
        const contador = document.getElementById('contador-usuarios-visibles');

        function filtrarUsuarios() {
            const texto = (inputBuscar?.value || '').toLowerCase().trim();
            const rol = (selectRol?.value || '').toLowerCase().trim();

            let visibles = 0;

            filas.forEach(fila => {
                const nombre = fila.getAttribute('data-nombre') || '';
                const email = fila.getAttribute('data-email') || '';
                const rut = fila.getAttribute('data-rut') || '';
                const rolUsuario = fila.getAttribute('data-rol') || '';

                const coincideTexto = !texto || nombre.includes(texto) || email.includes(texto) || rut.includes(texto);
                const coincideRol = !rol || rolUsuario === rol;

                if (coincideTexto && coincideRol) {
                    fila.classList.remove('hidden');
                    visibles++;
                } else {
                    fila.classList.add('hidden');
                }
            });

            if (contador) {
                contador.textContent = visibles;
            }
        }

        inputBuscar?.addEventListener('input', filtrarUsuarios);
        selectRol?.addEventListener('change', filtrarUsuarios);
    });
</script>
@endsection
