@extends('layouts.app')

@section('titulo', 'Cursos y Asignaturas')
@section('encabezado', 'Cursos y Asignaturas Curriculares')

@section('contenido')
<div class="space-y-6 max-w-7xl mx-auto">
    <!-- Encabezado en Tarjeta Blanca -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 rounded-2xl bg-white border border-slate-200 shadow-xs">
        <div>
            <div class="inline-flex items-center gap-2 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200 mb-2">
                <span>Estructura Curricular 2026</span>
            </div>
            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">
                Cursos y sus Asignaturas Asociadas
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-1">
                Cada curso tiene sus respectivas asignaturas del plan de estudio vinculadas con su profesor titular y horas semanales.
            </p>
        </div>

        @if(auth()->user()?->tieneRol(\App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
            <div class="flex flex-wrap items-center gap-2.5">
                <button type="button" data-abrir-modal="modal-crear-curso" class="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Nuevo Curso</span>
                </button>
                <button type="button" data-abrir-modal="modal-asociar-asignatura" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-xl shadow-xs transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                    </svg>
                    <span>Asociar Asignatura a Curso</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Despliegue de Cada Curso con sus Asignaturas Asociadas -->
    <div class="space-y-6">
        @forelse($cursos as $curso)
            <div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden">
                <!-- Cabecera del Curso -->
                <div class="p-5 bg-slate-50/80 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-3">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-amber-500 text-slate-950 font-bold flex items-center justify-center text-sm shadow-xs">
                            {{ substr($curso->nombre, 0, 2) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-bold text-slate-900">{{ $curso->nombre }}</h3>
                                @if(auth()->user()?->esSuperUsuario())
                                    <button type="button" data-editar-curso='@json($curso)' class="text-[11px] px-2 py-0.5 rounded bg-amber-100 hover:bg-amber-200 text-amber-900 font-bold border border-amber-300 transition-colors cursor-pointer" title="Solo SuperUsuario">
                                        Editar Curso
                                    </button>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500">
                                {{ $curso->nivel }} (Año {{ $curso->anio }}) • Profesor(a) Jefe: 
                                <strong class="text-slate-800 font-semibold">{{ $curso->profesorJefe?->name ?? 'No asignado' }}</strong>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 shadow-2xs">
                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            <span>{{ $curso->matriculas_count }} Estudiantes</span>
                        </span>

                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                            <span>{{ $curso->cursoAsignaturas->count() }} Asignaturas Asociadas</span>
                        </span>
                    </div>
                </div>

                <!-- Tabla de Asignaturas que componen este curso -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-100/70 text-slate-600 uppercase text-[11px] font-bold tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="px-5 py-3 font-semibold">Código</th>
                                <th class="px-5 py-3 font-semibold">Asignatura del Curso</th>
                                <th class="px-5 py-3 font-semibold">Docente Titular a Cargo</th>
                                <th class="px-5 py-3 text-center font-semibold">Carga Horaria</th>
                                <th class="px-5 py-3 text-right font-semibold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($curso->cursoAsignaturas as $asociacion)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-5 py-3.5 font-mono text-slate-500 font-medium">
                                        {{ $asociacion->asignatura->codigo }}
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <p class="font-bold text-slate-900">{{ $asociacion->asignatura->nombre }}</p>
                                        <p class="text-[11px] text-slate-500">{{ $asociacion->asignatura->descripcion }}</p>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center font-bold text-[10px] text-slate-700">
                                                {{ strtoupper(substr($asociacion->docente?->name ?? 'P', 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-slate-800">{{ $asociacion->docente?->name ?? 'Sin asignar' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200 font-mono">
                                            {{ $asociacion->horas_semanales }} hrs/sem
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('notas.index') }}" class="text-xs font-semibold text-amber-600 hover:text-amber-700">
                                                Notas
                                            </a>
                                            @if(auth()->user()?->esSuperUsuario())
                                                <form action="{{ route('cursos.desasociar_asignatura', [$curso, $asociacion]) }}" method="POST" onsubmit="return confirm('¿Desvincular esta asignatura del curso?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-700 cursor-pointer" title="Solo SuperUsuario">
                                                        Desvincular
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-5 py-6 text-center text-slate-500 text-xs">
                                        No hay asignaturas asociadas a este curso aún.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="p-8 text-center bg-white rounded-2xl border border-slate-200">
                <p class="text-sm text-slate-500">No se encontraron cursos registrados.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: CREAR NUEVO CURSO                                 -->
<!-- ======================================================== -->
<div id="modal-crear-curso" class="modal-fondo fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Crear Nuevo Curso</h3>
                <p class="text-xs text-slate-500">Registra un nivel educativo en el sistema institucional</p>
            </div>
            <button type="button" data-cerrar-modal="modal-crear-curso" class="p-1 text-slate-400 hover:text-slate-700 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('cursos.guardar') }}" method="POST" class="mt-4 space-y-4">
            @csrf
            <div>
                <label for="curso-nombre" class="block text-xs font-semibold text-slate-700 mb-1">Nombre del Curso</label>
                <input type="text" id="curso-nombre" name="nombre" placeholder="Ej: 3° Medio A" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="curso-nivel" class="block text-xs font-semibold text-slate-700 mb-1">Nivel Educativo</label>
                    <select id="curso-nivel" name="nivel" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                        <option value="Enseñanza Media">Enseñanza Media</option>
                        <option value="Enseñanza Básica">Enseñanza Básica</option>
                        <option value="Educación Parvularia">Educación Parvularia</option>
                    </select>
                </div>
                <div>
                    <label for="curso-anio" class="block text-xs font-semibold text-slate-700 mb-1">Año Lectivo</label>
                    <input type="number" id="curso-anio" name="anio" value="2026" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <div>
                <label for="curso-profesor" class="block text-xs font-semibold text-slate-700 mb-1">Profesor(a) Jefe</label>
                <select id="curso-profesor" name="profesor_jefe_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                    <option value="">-- Sin profesor jefe por ahora --</option>
                    @foreach($docentes ?? [] as $docente)
                        <option value="{{ $docente->id }}">{{ $docente->name }} ({{ $docente->rut ?? $docente->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" data-cerrar-modal="modal-crear-curso" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-amber-500 text-slate-950 text-xs font-bold rounded-xl hover:bg-amber-600 shadow-xs">
                    Guardar Curso
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: EDITAR CURSO (SOLO SUPERUSUARIO)                   -->
<!-- ======================================================== -->
<div id="modal-editar-curso" class="modal-fondo fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded bg-purple-100 text-purple-800 text-[10px] font-bold uppercase mb-1">
                    Exclusivo SuperUsuario
                </div>
                <h3 class="text-base font-bold text-slate-900">Editar Información del Curso</h3>
            </div>
            <button type="button" data-cerrar-modal="modal-editar-curso" class="p-1 text-slate-400 hover:text-slate-700 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="form-editar-curso" method="POST" class="mt-4 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="editar-curso-nombre" class="block text-xs font-semibold text-slate-700 mb-1">Nombre del Curso</label>
                <input type="text" id="editar-curso-nombre" name="nombre" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="editar-curso-nivel" class="block text-xs font-semibold text-slate-700 mb-1">Nivel Educativo</label>
                    <select id="editar-curso-nivel" name="nivel" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                        <option value="Enseñanza Media">Enseñanza Media</option>
                        <option value="Enseñanza Básica">Enseñanza Básica</option>
                        <option value="Educación Parvularia">Educación Parvularia</option>
                    </select>
                </div>
                <div>
                    <label for="editar-curso-anio" class="block text-xs font-semibold text-slate-700 mb-1">Año Lectivo</label>
                    <input type="number" id="editar-curso-anio" name="anio" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-amber-500">
                </div>
            </div>

            <div>
                <label for="editar-curso-profesor" class="block text-xs font-semibold text-slate-700 mb-1">Profesor(a) Jefe</label>
                <select id="editar-curso-profesor" name="profesor_jefe_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                    <option value="">-- Sin profesor jefe --</option>
                    @foreach($docentes ?? [] as $docente)
                        <option value="{{ $docente->id }}">{{ $docente->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" data-cerrar-modal="modal-editar-curso" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-purple-600 text-white text-xs font-bold rounded-xl hover:bg-purple-700 shadow-xs">
                    Actualizar Curso
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================== -->
<!-- MODAL: ASOCIAR ASIGNATURA A UN CURSO                     -->
<!-- ======================================================== -->
<div id="modal-asociar-asignatura" class="modal-fondo fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4 backdrop-blur-xs">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-slate-200">
        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Asociar Asignatura a Curso</h3>
                <p class="text-xs text-slate-500">Vincula una materia curricular, asigna docente y horas semanales</p>
            </div>
            <button type="button" data-cerrar-modal="modal-asociar-asignatura" class="p-1 text-slate-400 hover:text-slate-700 rounded-lg">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="form-asociar-asignatura" method="POST" action="" class="mt-4 space-y-4">
            @csrf
            <div>
                <label for="asociar-curso" class="block text-xs font-semibold text-slate-700 mb-1">Seleccionar Curso</label>
                <select id="asociar-curso" required onchange="document.getElementById('form-asociar-asignatura').action = '/cursos/' + this.value + '/asignaturas'" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                    <option value="">-- Selecciona el curso --</option>
                    @foreach($cursos as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre }} ({{ $c->nivel }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="asociar-asignatura-id" class="block text-xs font-semibold text-slate-700 mb-1">Asignatura del Catálogo</label>
                <select id="asociar-asignatura-id" name="asignatura_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                    <option value="">-- Selecciona la asignatura --</option>
                    @foreach($catalogoAsignaturas ?? [] as $asig)
                        <option value="{{ $asig->id }}">{{ $asig->codigo }} - {{ $asig->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="asociar-docente" class="block text-xs font-semibold text-slate-700 mb-1">Docente Titular Asignado</label>
                <select id="asociar-docente" name="docente_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-900 focus:outline-none focus:border-amber-500">
                    <option value="">-- Sin docente asignado --</option>
                    @foreach($docentes ?? [] as $docente)
                        <option value="{{ $docente->id }}">{{ $docente->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="asociar-horas" class="block text-xs font-semibold text-slate-700 mb-1">Carga Horaria Semanal</label>
                <input type="number" id="asociar-horas" name="horas_semanales" value="4" min="1" max="20" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs font-mono text-slate-900 focus:outline-none focus:border-amber-500">
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" data-cerrar-modal="modal-asociar-asignatura" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-xl hover:bg-slate-200">
                    Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-amber-500 text-slate-950 text-xs font-bold rounded-xl hover:bg-amber-600 shadow-xs">
                    Asociar Asignatura
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
