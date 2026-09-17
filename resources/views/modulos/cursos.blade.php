@extends('layouts.app')

@section('titulo', 'Cursos')
@section('encabezado', 'Cursos y Asignaturas')

@php
    $esSuperUsuario = auth()->user()?->esSuperUsuario();
    $esGestion = auth()->user()?->tieneRol(\App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario);
    $nivelesEducativos = ['Educación Parvularia', 'Enseñanza Básica', 'Enseñanza Media'];
@endphp

@section('contenido')
    <section class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="titulo-pagina">Cursos del colegio</h2>
            <p class="texto-ayuda">{{ $cursos->count() }} cursos · {{ $cursos->sum('matriculas_count') }} estudiantes</p>
        </div>
        @if($esGestion)
            <button type="button" data-abrir-modal="modal-crear-curso" class="boton-primario">
                <x-icono nombre="mas" clase="size-5" />
                Crear curso
            </button>
        @endif
    </section>

    @forelse($cursos as $curso)
        <article class="tarjeta space-y-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-slate-900">{{ $curso->nombre }}</h3>
                    <p class="texto-ayuda">
                        {{ $curso->nivel }} · {{ $curso->matriculas_count }} estudiantes ·
                        Profesor(a) jefe: {{ $curso->profesorJefe?->name ?? 'Por asignar' }}
                    </p>
                </div>
                <div class="flex shrink-0 flex-wrap gap-2">
                    @if($esGestion)
                        <button type="button" data-asociar-en-curso="{{ $curso->id }}" class="boton-secundario">
                            <x-icono nombre="mas" clase="size-5" />
                            Agregar asignatura
                        </button>
                    @endif
                    @if($esSuperUsuario)
                        <button type="button" class="boton-secundario"
                                data-editar-curso="{{ json_encode($curso->only(['id', 'nombre', 'nivel', 'anio', 'profesor_jefe_id'])) }}">
                            <x-icono nombre="editar" clase="size-5" />
                            Editar
                        </button>
                    @endif
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th scope="col">Asignatura</th>
                            <th scope="col">Profesor(a)</th>
                            <th scope="col">Horas por semana</th>
                            @if($esSuperUsuario)
                                <th scope="col"><span class="sr-only">Acciones</span></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($curso->cursoAsignaturas as $asociacion)
                            <tr>
                                <td>
                                    <span class="font-medium text-slate-900">{{ $asociacion->asignatura->nombre }}</span>
                                    <span class="block text-sm text-slate-500">{{ $asociacion->asignatura->codigo }}</span>
                                </td>
                                <td>{{ $asociacion->docente?->name ?? 'Por asignar' }}</td>
                                <td>{{ $asociacion->horas_semanales }}</td>
                                @if($esSuperUsuario)
                                    <td class="text-right">
                                        <form action="{{ route('cursos.desasociar_asignatura', [$curso, $asociacion]) }}" method="POST"
                                              data-confirmar="¿Quitar {{ $asociacion->asignatura->nombre }} de {{ $curso->nombre }}?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="boton-peligro min-h-10 px-3 text-sm">Quitar</button>
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="texto-ayuda">Este curso todavía no tiene asignaturas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    @empty
        <p class="tarjeta texto-ayuda">Todavía no hay cursos. Usa el botón «Crear curso» para empezar.</p>
    @endforelse

    {{-- Crear curso --}}
    <x-modal id="modal-crear-curso" titulo="Crear curso">
        <form action="{{ route('cursos.guardar') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="curso-nombre" class="etiqueta">Nombre del curso</label>
                <input type="text" id="curso-nombre" name="nombre" placeholder="Ejemplo: 3° Medio A" required class="campo">
            </div>
            <div>
                <label for="curso-nivel" class="etiqueta">Nivel</label>
                <select id="curso-nivel" name="nivel" required class="campo">
                    @foreach($nivelesEducativos as $nivel)
                        <option value="{{ $nivel }}">{{ $nivel }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="curso-profesor" class="etiqueta">Profesor(a) jefe <span class="font-normal text-slate-500">(opcional)</span></label>
                <select id="curso-profesor" name="profesor_jefe_id" class="campo">
                    <option value="">Elegir más tarde</option>
                    @foreach($docentes as $docente)
                        <option value="{{ $docente->id }}">{{ $docente->name }}</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" name="anio" value="{{ \App\Utils\PeriodoEscolar::anioVigente() }}">
            <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                <button type="button" data-cerrar-modal="modal-crear-curso" class="boton-secundario">Cancelar</button>
                <button type="submit" class="boton-primario">Guardar curso</button>
            </div>
        </form>
    </x-modal>

    {{-- Editar curso (solo SuperUsuario) --}}
    @if($esSuperUsuario)
        <x-modal id="modal-editar-curso" titulo="Editar curso">
            <form id="form-editar-curso" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="editar-curso-nombre" class="etiqueta">Nombre del curso</label>
                    <input type="text" id="editar-curso-nombre" name="nombre" required class="campo">
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="editar-curso-nivel" class="etiqueta">Nivel</label>
                        <select id="editar-curso-nivel" name="nivel" required class="campo">
                            @foreach($nivelesEducativos as $nivel)
                                <option value="{{ $nivel }}">{{ $nivel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="editar-curso-anio" class="etiqueta">Año</label>
                        <input type="number" id="editar-curso-anio" name="anio" required class="campo">
                    </div>
                </div>
                <div>
                    <label for="editar-curso-profesor" class="etiqueta">Profesor(a) jefe</label>
                    <select id="editar-curso-profesor" name="profesor_jefe_id" class="campo">
                        <option value="">Sin profesor jefe</option>
                        @foreach($docentes as $docente)
                            <option value="{{ $docente->id }}">{{ $docente->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                    <button type="button" data-cerrar-modal="modal-editar-curso" class="boton-secundario">Cancelar</button>
                    <button type="submit" class="boton-primario">Guardar cambios</button>
                </div>
            </form>
        </x-modal>
    @endif

    {{-- Agregar asignatura a un curso --}}
    <x-modal id="modal-asociar-asignatura" titulo="Agregar asignatura">
        <form id="form-asociar-asignatura" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label for="asociar-curso" class="etiqueta">Curso</label>
                <select id="asociar-curso" required class="campo">
                    <option value="">Elige un curso</option>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}" data-accion="{{ route('cursos.asociar_asignatura', $curso) }}">{{ $curso->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="asociar-asignatura-id" class="etiqueta">Asignatura</label>
                <select id="asociar-asignatura-id" name="asignatura_id" required class="campo">
                    <option value="">Elige una asignatura</option>
                    @foreach($catalogoAsignaturas as $asignatura)
                        <option value="{{ $asignatura->id }}">{{ $asignatura->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="asociar-docente" class="etiqueta">Profesor(a) <span class="font-normal text-slate-500">(opcional)</span></label>
                <select id="asociar-docente" name="docente_id" class="campo">
                    <option value="">Elegir más tarde</option>
                    @foreach($docentes as $docente)
                        <option value="{{ $docente->id }}">{{ $docente->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="asociar-horas" class="etiqueta">Horas por semana</label>
                <input type="number" id="asociar-horas" name="horas_semanales" value="4" min="1" max="20" required class="campo">
            </div>
            <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                <button type="button" data-cerrar-modal="modal-asociar-asignatura" class="boton-secundario">Cancelar</button>
                <button type="submit" class="boton-primario">Agregar asignatura</button>
            </div>
        </form>
    </x-modal>
@endsection
