@extends('layouts.app')

@use('App\Enums\RolUsuario')
@use('App\Utils\FormateadorFecha')

@php
    $esPersonalEscolar = $usuario?->tieneRol(RolUsuario::Docente, RolUsuario::Administrador, RolUsuario::SuperUsuario);
    $opcionesEstado = ['presente' => 'Presente', 'atraso' => 'Atraso', 'justificado' => 'Justificado', 'ausente' => 'Ausente'];
@endphp

@section('titulo', 'Asistencia')
@section('encabezado', $esPersonalEscolar ? 'Control de Asistencia del Día a Día' : ($usuario?->esEstudiante() ? 'Mi asistencia' : 'Asistencia de mis hijos'))

@section('contenido')
    @if($esPersonalEscolar)
        {{-- Paso 1: elegir curso y día --}}
        <form method="GET" action="{{ route('asistencias.index') }}" class="tarjeta grid gap-4 sm:grid-cols-[1fr_1fr_auto] sm:items-end">
            <div>
                <label for="curso_id" class="etiqueta">1. Elige el curso</label>
                <select id="curso_id" name="curso_id" class="campo">
                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}" @selected($cursoSeleccionado?->id === $curso->id)>{{ $curso->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="fecha" class="etiqueta">2. Elige el día</label>
                <input type="date" id="fecha" name="fecha" value="{{ $fecha }}" class="campo">
            </div>
            <button type="submit" class="boton-secundario">Ver lista</button>
        </form>

        {{-- Paso 2: marcar la asistencia --}}
        <form id="form-guardar-asistencia" action="{{ route('asistencias.guardar') }}" method="POST" class="tarjeta space-y-5">
            @csrf
            <input type="hidden" name="curso_id" value="{{ $cursoSeleccionado?->id }}">
            <input type="hidden" name="fecha" value="{{ $fecha }}">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="titulo-seccion">3. Marca la asistencia de {{ $cursoSeleccionado?->nombre }}</h2>
                    <p class="texto-ayuda">{{ ucfirst(FormateadorFecha::formatearFechaLarga($fecha)) }}</p>
                </div>
                @if($asistencias->isNotEmpty())
                    <button type="button" data-marcar-todos-presentes class="boton-secundario">Marcar a todos presentes</button>
                @endif
            </div>

            <p class="flex flex-wrap gap-2 text-base" aria-label="Resumen del día">
                <span class="insignia-verde">{{ $resumen['presentes'] }} presentes</span>
                <span class="insignia-amarilla">{{ $resumen['atrasos'] }} atrasos</span>
                <span class="insignia-gris">{{ $resumen['justificados'] }} justificados</span>
                <span class="insignia-roja">{{ $resumen['ausentes'] }} ausentes</span>
                <span class="insignia-gris">Asistencia {{ FormateadorFecha::formatearPorcentaje($resumen['porcentaje_asistencia']) }}</span>
            </p>

            <ol class="divide-y divide-slate-100 border-y border-slate-100">
                @forelse($asistencias as $indice => $registro)
                    <li class="space-y-3 py-4">
                        <input type="hidden" name="asistencias[{{ $indice }}][estudiante_id]" value="{{ $registro->estudiante_id }}">

                        <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                            <p class="text-lg font-medium text-slate-900">
                                <span class="mr-2 text-slate-400">{{ $indice + 1 }}.</span>{{ $registro->estudiante?->name }}
                            </p>

                            <fieldset class="flex flex-wrap gap-2">
                                <legend class="sr-only">Asistencia de {{ $registro->estudiante?->name }}</legend>
                                @foreach($opcionesEstado as $valor => $texto)
                                    <label class="cursor-pointer">
                                        <input type="radio" name="asistencias[{{ $indice }}][estado]" value="{{ $valor }}"
                                               @checked($registro->estado === $valor)
                                               class="peer sr-only {{ $valor === 'presente' ? 'estado-presente' : '' }}">
                                        <span class="inline-flex min-h-11 items-center rounded-xl border border-slate-300 px-4 text-base text-slate-700 peer-checked:border-marca-600 peer-checked:bg-marca-600 peer-checked:text-white peer-focus-visible:outline-3 peer-focus-visible:outline-marca-600">
                                            {{ $texto }}
                                        </span>
                                    </label>
                                @endforeach
                            </fieldset>
                        </div>

                        <details class="text-base">
                            <summary class="cursor-pointer text-marca-700">Agregar hora de llegada o comentario</summary>
                            <div class="mt-3 grid gap-3 sm:grid-cols-[12rem_1fr]">
                                <div>
                                    <label for="hora-{{ $indice }}" class="etiqueta">Hora de llegada</label>
                                    <input type="time" id="hora-{{ $indice }}" name="asistencias[{{ $indice }}][hora_llegada]"
                                           value="{{ $registro->hora_llegada ? substr($registro->hora_llegada, 0, 5) : '' }}" class="campo">
                                </div>
                                <div>
                                    <label for="observacion-{{ $indice }}" class="etiqueta">Comentario</label>
                                    <input type="text" id="observacion-{{ $indice }}" name="asistencias[{{ $indice }}][observacion]"
                                           value="{{ $registro->observacion }}" placeholder="Ejemplo: llegó con certificado médico" class="campo">
                                </div>
                            </div>
                        </details>
                    </li>
                @empty
                    <li class="py-4 texto-ayuda">Este curso todavía no tiene estudiantes matriculados.</li>
                @endforelse
            </ol>

            @if($asistencias->isNotEmpty())
                <div class="flex justify-end">
                    <button type="submit" class="boton-primario w-full sm:w-auto">Guardar asistencia</button>
                </div>
            @endif
        </form>

    @elseif($usuario?->esEstudiante())
        <section class="grid gap-4 sm:grid-cols-2">
            <x-dato titulo="Mi asistencia" :valor="FormateadorFecha::formatearPorcentaje($porcentajeEstudiante)">
                Se necesita al menos 85% para aprobar
            </x-dato>
            <x-dato titulo="Días registrados" :valor="$historialEstudiante?->count() ?? 0" />
        </section>

        <section class="tarjeta space-y-4">
            <h2 class="titulo-seccion">Historial Reciente Día a Día</h2>
            @include('modulos.partes.historial-asistencia', ['historial' => $historialEstudiante])
        </section>

    @else
        @if($pupilos->isEmpty())
            <p class="tarjeta texto-ayuda">Todavía no tienes hijos asociados. Pide en secretaría que los vinculen a tu cuenta.</p>
        @else
            @include('modulos.partes.selector-pupilo', ['rutaModulo' => 'asistencias.index'])

            @if($pupiloSeleccionado)
                <section class="grid gap-4 sm:grid-cols-2">
                    <div class="tarjeta">
                        <p class="texto-ayuda">Último registro</p>
                        <div class="mt-2 flex items-center gap-3 text-lg">
                            @if($asistenciaHoyPupilo)
                                @include('modulos.partes.estado-asistencia', ['estado' => $asistenciaHoyPupilo->estado])
                                <span class="text-slate-600">{{ FormateadorFecha::formatearFecha($asistenciaHoyPupilo->fecha) }}</span>
                            @else
                                <span class="text-slate-600">Sin registros todavía</span>
                            @endif
                        </div>
                    </div>
                    <x-dato titulo="Asistencia acumulada" :valor="FormateadorFecha::formatearPorcentaje($porcentajeEstudiante)">
                        Se necesita al menos 85% para aprobar
                    </x-dato>
                </section>

                <section class="tarjeta space-y-4">
                    <div>
                        <h2 class="titulo-seccion">Historial Reciente Día a Día - {{ $pupiloSeleccionado->estudiante?->name }}</h2>
                        <p class="texto-ayuda">{{ $pupiloSeleccionado->curso?->nombre }}</p>
                    </div>
                    @include('modulos.partes.historial-asistencia', ['historial' => $historialEstudiante])
                </section>
            @endif
        @endif
    @endif
@endsection
