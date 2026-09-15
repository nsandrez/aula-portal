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

        @if(auth()->user()?->tieneRol(\App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
            <div class="flex flex-wrap items-center gap-2.5">
                <button type="button" data-abrir-modal="modal-vincular-apoderado" class="px-3.5 py-2 bg-white hover:bg-slate-50 text-slate-800 font-bold text-xs rounded-xl border border-slate-300 shadow-xs transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                    <span>Vincular Pupilos a Apoderado</span>
                </button>
                <button type="button" onclick="cambiarTipoMatricula('nuevo'); abrirModal('modal-nueva-matricula');" class="px-3.5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                    </svg>
                    <span>+ Registrar Nuevo Alumno</span>
                </button>
                <button type="button" onclick="cambiarTipoMatricula('existente'); abrirModal('modal-nueva-matricula');" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Matricular Alumno</span>
                </button>
            </div>
        @endif
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
                        <th class="px-5 py-3 text-right font-semibold">Acciones</th>
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
                                @if($m->apoderado)
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <span class="font-bold text-slate-900">{{ $m->apoderado->name }}</span>
                                        @php
                                            $totalPupilos = $matriculas->where('apoderado_id', $m->apoderado_id)->count();
                                        @endphp
                                        @if($totalPupilos > 1)
                                            <span class="px-1.5 py-0.5 rounded-full bg-amber-100 text-amber-800 text-[10px] font-bold border border-amber-200" title="{{ $totalPupilos }} pupilos a cargo de este apoderado">
                                                {{ $totalPupilos }} pupilos
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400 italic">No registrado</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                @if($m->estado === 'regular')
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Regular
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-50 text-red-700 border border-red-200">
                                        Retirado
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                @if(auth()->user()?->esSuperUsuario())
                                    <button type="button" onclick="editarMatricula({{ $m->id }}, {{ $m->numero_lista }}, '{{ $m->estado }}', {{ $m->apoderado_id ?? 'null' }})" class="text-xs font-bold text-purple-600 hover:text-purple-700 cursor-pointer" title="Solo SuperUsuario">
                                        Editar
                                    </button>
                                @else
                                    <span class="text-xs text-slate-400 font-medium">Solo lectura</span>
                                @endif
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
</script>
@endsection
