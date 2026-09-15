@extends('layouts.app')

@section('titulo', 'Cursos y Asignaturas')
@section('encabezado', 'Cursos y Asignaturas Curriculares')

@section('contenido')
<div class="space-y-6 max-w-[1600px] w-full mx-auto">
    <!-- Encabezado en Tarjeta Blanca -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-6 sm:p-7 rounded-2xl bg-white border border-slate-200/90 shadow-xs">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-900 border border-amber-200 mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                <span>Estructura Curricular 2026</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                Cursos y Asignaturas Curriculares
            </h2>
            <p class="text-sm text-slate-600 mt-1 max-w-3xl">
                Administración de niveles educativos, vinculación de asignaturas al plan de estudios, profesores jefes y docentes titulares a cargo.
            </p>
        </div>

        @if(auth()->user()?->tieneRol(\App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
            <div class="flex flex-wrap items-center gap-3">
                <button type="button" data-abrir-modal="modal-crear-curso" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl shadow-xs transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Nuevo Curso</span>
                </button>
                <button type="button" data-abrir-modal="modal-asociar-asignatura" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-sm rounded-xl shadow-xs transition-colors flex items-center gap-2 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" />
                    </svg>
                    <span>Asociar Asignatura a Curso</span>
                </button>
            </div>
        @endif
    </div>

    <!-- Indicadores Curriculares Institucionales -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Cursos Registrados</p>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $cursos->count() }}</p>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">Ciclo escolar activo 2026</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center text-amber-700">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342" />
                </svg>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Asignaturas en Malla</p>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $cursos->sum(fn($c) => $c->cursoAsignaturas->count()) }}</p>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">Impartidas entre todos los cursos</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-700">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Estudiantes Inscritos</p>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $cursos->sum('matriculas_count') }}</p>
                <p class="text-xs text-slate-500 mt-0.5 font-medium">Distribuidos en cursos oficiales</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 border border-blue-200 flex items-center justify-center text-blue-700">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                </svg>
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white border border-slate-200/90 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Jefaturas de Curso</p>
                <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $cursos->whereNotNull('profesor_jefe_id')->count() }} <span class="text-base text-slate-400 font-semibold">/ {{ $cursos->count() }}</span></p>
                <p class="text-xs text-emerald-600 font-medium mt-0.5">Profesores jefes asignados</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 border border-purple-200 flex items-center justify-center text-purple-700">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Despliegue de Cada Curso con sus Asignaturas Asociadas -->
    <div class="space-y-6">
        @forelse($cursos as $curso)
            <div class="rounded-2xl bg-white border border-slate-200 shadow-xs overflow-hidden">
                <!-- Cabecera del Curso -->
                <div class="p-6 bg-slate-50/80 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-amber-500 text-slate-950 font-bold flex items-center justify-center text-base shadow-xs shrink-0">
                            {{ substr($curso->nombre, 0, 2) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2.5">
                                <h3 class="text-lg font-bold text-slate-900">{{ $curso->nombre }}</h3>
                                @if(auth()->user()?->esSuperUsuario())
                                    <button type="button" data-editar-curso='@json($curso)' class="text-xs px-2.5 py-1 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-900 font-bold border border-amber-300 transition-colors cursor-pointer" title="Solo SuperUsuario">
                                        Editar Curso
                                    </button>
                                @endif
                            </div>
                            <p class="text-sm text-slate-500 mt-0.5">
                                {{ $curso->nivel }} (Año Lectivo {{ $curso->anio }}) • Profesor(a) Jefe: 
                                <strong class="text-slate-800 font-semibold">{{ $curso->profesorJefe?->name ?? 'No asignado' }}</strong>
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-white border border-slate-200 text-slate-700 shadow-2xs">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                            <span>{{ $curso->matriculas_count }} Estudiantes Inscritos</span>
                        </span>

                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                            <span>{{ $curso->cursoAsignaturas->count() }} Asignaturas</span>
                        </span>
                    </div>
                </div>

                <!-- Tabla de Asignaturas que componen este curso -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-700">
                        <thead class="bg-slate-100/70 text-slate-600 uppercase text-xs font-bold tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3.5 font-semibold">Código</th>
                                <th class="px-6 py-3.5 font-semibold">Asignatura del Plan Curricular</th>
                                <th class="px-6 py-3.5 font-semibold">Docente Titular a Cargo</th>
                                <th class="px-6 py-3.5 text-center font-semibold">Carga Horaria</th>
                                <th class="px-6 py-3.5 text-right font-semibold">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($curso->cursoAsignaturas as $asociacion)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-6 py-4 font-mono text-slate-600 font-semibold text-xs">
                                        {{ $asociacion->asignatura->codigo }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-900 text-sm">{{ $asociacion->asignatura->nombre }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5">{{ $asociacion->asignatura->descripcion }}</p>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-7 h-7 rounded-full bg-slate-200 flex items-center justify-center font-bold text-xs text-slate-700">
                                                {{ strtoupper(substr($asociacion->docente?->name ?? 'P', 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-slate-800 text-sm">{{ $asociacion->docente?->name ?? 'Sin asignar' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 font-mono">
                                            {{ $asociacion->horas_semanales }} hrs/sem
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="inline-flex items-center gap-3">
                                            <a href="{{ route('notas.index') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700">
                                                Notas
                                            </a>
                                            @if(auth()->user()?->esSuperUsuario())
                                                <form action="{{ route('cursos.desasociar_asignatura', [$curso, $asociacion]) }}" method="POST" onsubmit="return confirm('¿Desvincular esta asignatura del curso?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-700 cursor-pointer" title="Solo SuperUsuario">
                                                        Desvincular
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-slate-500 text-sm">
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
