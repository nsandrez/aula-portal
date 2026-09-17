@extends('layouts.app')

@use('App\Enums\RolUsuario')
@use('App\Utils\FormateadorFecha')

@php
    $esPersonalEscolar = $usuario?->tieneRol(RolUsuario::Docente, RolUsuario::Administrador, RolUsuario::SuperUsuario);
    $esGestion = $usuario?->tieneRol(RolUsuario::Administrador, RolUsuario::SuperUsuario);
@endphp

@section('titulo', 'Inicio')
@section('encabezado', 'Inicio')

@section('contenido')
    <section>
        <h2 class="titulo-pagina">Hola, {{ $usuario?->name ?? 'bienvenido(a)' }}</h2>
        <p class="mt-1 texto-ayuda">¿Qué necesitas hacer hoy? Elige una opción.</p>
    </section>

    {{-- Resumen corto según el rol --}}
    <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3" aria-label="Resumen">
        @if($usuario?->esApoderado())
            <x-dato titulo="Hijos a cargo" :valor="$pupilos->count().' '.($pupilos->count() === 1 ? 'Hijo' : 'Hijos')" />
            <x-dato titulo="Asistencia promedio" :valor="FormateadorFecha::formatearPorcentaje($pupilos->avg('porcentaje_asistencia') ?? 100)" />
        @elseif($usuario?->esEstudiante())
            <x-dato titulo="Mi curso" :valor="$matriculaEstudiante?->curso?->nombre ?? 'Sin curso'" />
            <x-dato titulo="Mi asistencia" :valor="FormateadorFecha::formatearPorcentaje($porcentajeAsistencia)">
                Se recomienda sobre 85%
            </x-dato>
        @else
            <x-dato titulo="Cursos" :valor="$cursos->count()" />
            <x-dato titulo="Estudiantes matriculados" :valor="$totalEstudiantes" />
        @endif
    </section>

    {{-- Hijos del apoderado --}}
    @if($usuario?->esApoderado())
        <section class="space-y-4">
            <h2 class="titulo-seccion">Mis Pupilos / Hijos a Cargo ({{ $pupilos->count() }})</h2>

            <div class="grid gap-4 md:grid-cols-2">
                @forelse($pupilos as $pupilo)
                    <article class="tarjeta space-y-4">
                        <div>
                            <h3 class="text-xl font-semibold text-slate-900">{{ $pupilo->estudiante?->name }}</h3>
                            <p class="texto-ayuda">{{ $pupilo->curso?->nombre }}</p>
                        </div>

                        <dl class="grid grid-cols-2 gap-4 text-base">
                            <div>
                                <dt class="text-slate-500">Profesor(a) jefe</dt>
                                <dd class="font-medium text-slate-900">{{ $pupilo->curso?->profesorJefe?->name ?? 'Por asignar' }}</dd>
                            </div>
                            <div>
                                <dt class="text-slate-500">Asistencia</dt>
                                <dd class="font-medium text-slate-900">{{ FormateadorFecha::formatearPorcentaje($pupilo->porcentaje_asistencia ?? 100) }}</dd>
                            </div>
                        </dl>

                        <div class="flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('notas.index', ['pupilo_id' => $pupilo->estudiante_id]) }}" class="boton-secundario flex-1">Ver notas</a>
                            <a href="{{ route('asistencias.index', ['pupilo_id' => $pupilo->estudiante_id]) }}" class="boton-secundario flex-1">Ver asistencia</a>
                        </div>
                    </article>
                @empty
                    <p class="tarjeta texto-ayuda md:col-span-2">
                        Todavía no tienes hijos asociados. Pide en secretaría que los vinculen a tu cuenta.
                    </p>
                @endforelse
            </div>
        </section>
    @endif

    {{-- Accesos rápidos --}}
    @unless($usuario?->esApoderado())
    <section class="space-y-4">
        <h2 class="titulo-seccion">Accesos rápidos</h2>

        <div class="grid gap-4 md:grid-cols-2">
            @if($esPersonalEscolar)
                <x-acceso-rapido :ruta="route('asistencias.index')" icono="asistencia" titulo="Pasar lista">
                    Marca quién vino hoy a clases
                </x-acceso-rapido>
                <x-acceso-rapido :ruta="route('notas.index')" icono="notas" titulo="Notas">
                    Revisa las notas de cada curso
                </x-acceso-rapido>
            @elseif($usuario?->esEstudiante())
                <x-acceso-rapido :ruta="route('notas.index')" icono="notas" titulo="Mis notas">
                    Revisa tus notas por asignatura
                </x-acceso-rapido>
                <x-acceso-rapido :ruta="route('asistencias.index')" icono="asistencia" titulo="Mi asistencia">
                    Revisa los días que asististe
                </x-acceso-rapido>
            @endif

            @if($esGestion)
                <x-acceso-rapido :ruta="route('matriculas.index')" icono="matriculas" titulo="Matrículas">
                    Matricula estudiantes y asigna apoderados
                </x-acceso-rapido>
                <x-acceso-rapido :ruta="route('cursos.index')" icono="cursos" titulo="Cursos">
                    Crea cursos y asigna asignaturas
                </x-acceso-rapido>
            @endif

            @if($usuario?->esSuperUsuario())
                <x-acceso-rapido :ruta="route('usuarios.index')" icono="usuarios" titulo="Usuarios">
                    Crea cuentas y cambia contraseñas
                </x-acceso-rapido>
            @endif
        </div>
    </section>
    @endunless
@endsection
