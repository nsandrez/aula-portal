@extends('layouts.app')

@use('App\Enums\RolUsuario')

@php
    $esPersonalEscolar = $usuario?->tieneRol(RolUsuario::Docente, RolUsuario::Administrador, RolUsuario::SuperUsuario);
@endphp

@section('titulo', 'Notas')
@section('encabezado', $esPersonalEscolar ? 'Notas por curso' : ($usuario?->esEstudiante() ? 'Mis notas' : 'Notas de mis hijos'))

@section('contenido')
    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-base text-amber-900" role="note">
        El registro de notas se está habilitando. Por ahora puedes ver las asignaturas y sus profesores.
        La escala es de 1,0 a 7,0 y se aprueba con 4,0.
    </div>

    @if($esPersonalEscolar)
        <section class="space-y-4">
            <h2 class="titulo-seccion">Cursos y asignaturas</h2>

            @forelse($cursos as $curso)
                <article class="tarjeta">
                    <h3 class="text-xl font-semibold text-slate-900">{{ $curso->nombre }}</h3>
                    <p class="texto-ayuda">Profesor(a) jefe: {{ $curso->profesorJefe?->name ?? 'Por asignar' }}</p>

                    @include('modulos.partes.lista-asignaturas', ['cursoAsignaturas' => $curso->cursoAsignaturas])
                </article>
            @empty
                <p class="tarjeta texto-ayuda">Todavía no hay cursos creados.</p>
            @endforelse
        </section>

    @elseif($usuario?->esEstudiante())
        <section class="tarjeta">
            <h2 class="titulo-seccion">Mis asignaturas</h2>
            <p class="texto-ayuda">Curso: {{ $matriculaEstudiante?->curso?->nombre ?? 'Sin curso asignado' }}</p>

            @include('modulos.partes.lista-asignaturas', ['cursoAsignaturas' => $matriculaEstudiante?->curso?->cursoAsignaturas ?? collect()])
        </section>

    @else
        @if($pupilos->isEmpty())
            <p class="tarjeta texto-ayuda">Todavía no tienes hijos asociados. Pide en secretaría que los vinculen a tu cuenta.</p>
        @else
            @include('modulos.partes.selector-pupilo', ['rutaModulo' => 'notas.index'])

            @if($pupiloSeleccionado)
                <section class="tarjeta">
                    <h2 class="titulo-seccion">Calificaciones del Primer Semestre - {{ $pupiloSeleccionado->estudiante?->name }}</h2>
                    <p class="texto-ayuda">
                        {{ $pupiloSeleccionado->curso?->nombre }} · Profesor(a) jefe: {{ $pupiloSeleccionado->curso?->profesorJefe?->name ?? 'Por asignar' }}
                    </p>

                    @include('modulos.partes.lista-asignaturas', ['cursoAsignaturas' => $pupiloSeleccionado->curso?->cursoAsignaturas ?? collect()])
                </section>
            @endif
        @endif
    @endif
@endsection
