@extends('layouts.app')

@section('titulo', 'Matrículas')
@section('encabezado', 'Matrículas y Estudiantes')

@php
    $esGestion = auth()->user()?->tieneRol(\App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario);
    $esSuperUsuario = auth()->user()?->esSuperUsuario();
@endphp

@section('contenido')
    <section class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="titulo-pagina">Estudiantes matriculados {{ $anioVigente }}</h2>
            <p class="texto-ayuda">
                {{ $matriculas->count() }} estudiantes ·
                {{ $matriculas->whereNull('apoderado_id')->count() }} sin apoderado asignado
            </p>
        </div>
        @if($esGestion)
            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="button" data-abrir-modal="modal-vincular-apoderado" class="boton-secundario">Asignar apoderado</button>
                <button type="button" data-abrir-matricula="nuevo" class="boton-primario">
                    <x-icono nombre="mas" clase="size-5" />
                    Matricular estudiante
                </button>
            </div>
        @endif
    </section>

    <section class="tarjeta space-y-4" data-filtro-tabla="tabla-matriculas">
        <div class="grid gap-4 md:grid-cols-[2fr_1fr_1fr]">
            <div>
                <label for="filtro-buscador-matricula" class="etiqueta">Buscar estudiante</label>
                <input type="search" id="filtro-buscador-matricula" data-filtro-texto placeholder="Nombre o RUT" class="campo">
            </div>
            <div>
                <label for="filtro-curso-matricula" class="etiqueta">Curso</label>
                <select id="filtro-curso-matricula" data-filtro-campo="curso" class="campo">
                    <option value="">Todos</option>
                    @foreach($cursos as $curso)
                        <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="filtro-estado-matricula" class="etiqueta">Estado</label>
                <select id="filtro-estado-matricula" data-filtro-campo="estado" class="campo">
                    <option value="">Todos</option>
                    <option value="regular">Regular</option>
                    <option value="retirado">Retirado</option>
                </select>
            </div>
        </div>

        <p class="texto-ayuda" aria-live="polite">Mostrando <span data-filtro-contador>{{ $matriculas->count() }}</span> de {{ $matriculas->count() }}</p>

        <div class="overflow-x-auto">
            <table id="tabla-matriculas" class="tabla">
                <thead>
                    <tr>
                        <th scope="col">N°</th>
                        <th scope="col">Estudiante</th>
                        <th scope="col">Curso</th>
                        <th scope="col">Apoderado</th>
                        <th scope="col">Estado</th>
                        @if($esSuperUsuario)
                            <th scope="col"><span class="sr-only">Acciones</span></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($matriculas as $matricula)
                        <tr data-buscar="{{ mb_strtolower(($matricula->estudiante?->name ?? '').' '.($matricula->estudiante?->rut ?? '')) }}"
                            data-curso="{{ $matricula->curso_id }}"
                            data-estado="{{ $matricula->estado }}">
                            <td class="text-slate-500">{{ $matricula->numero_lista }}</td>
                            <td>
                                <span class="font-medium text-slate-900">{{ $matricula->estudiante?->name }}</span>
                                <span class="block text-sm text-slate-500">{{ $matricula->estudiante?->rut ?? $matricula->estudiante?->email }}</span>
                            </td>
                            <td class="whitespace-nowrap">{{ $matricula->curso?->nombre }}</td>
                            <td>
                                @if($matricula->apoderado)
                                    {{ $matricula->apoderado->name }}
                                @else
                                    <span class="insignia-amarilla">Falta asignar</span>
                                @endif
                            </td>
                            <td>
                                <span class="{{ $matricula->estado === 'regular' ? 'insignia-verde' : 'insignia-gris' }}">
                                    {{ $matricula->estado === 'regular' ? 'Regular' : 'Retirado' }}
                                </span>
                            </td>
                            @if($esSuperUsuario)
                                <td class="text-right">
                                    <button type="button" class="boton-secundario min-h-10 px-3 text-sm"
                                            data-editar-matricula="{{ json_encode($matricula->only(['id', 'numero_lista', 'estado', 'apoderado_id'])) }}">
                                        Editar
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="texto-ayuda">Todavía no hay estudiantes matriculados este año.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if($esGestion)
        {{-- Matricular estudiante --}}
        <x-modal id="modal-nueva-matricula" titulo="Matricular estudiante">
            <form action="{{ route('matriculas.guardar') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" id="matricula-tipo-registro" name="tipo_registro" value="nuevo">

                <fieldset>
                    <legend class="etiqueta">¿El estudiante ya tiene cuenta en el portal?</legend>
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" data-opcion-tipo-matricula="nuevo" aria-pressed="true" class="boton-primario">No, es nuevo</button>
                        <button type="button" data-opcion-tipo-matricula="existente" aria-pressed="false" class="boton-secundario">Sí, ya tiene</button>
                    </div>
                </fieldset>

                <div id="seccion-alumno-existente" class="hidden">
                    <label for="matricula-estudiante" class="etiqueta">Estudiante</label>
                    <select id="matricula-estudiante" name="estudiante_id" class="campo">
                        <option value="">Elige un estudiante</option>
                        @foreach($estudiantes as $estudiante)
                            <option value="{{ $estudiante->id }}">{{ $estudiante->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="seccion-alumno-nuevo" class="space-y-4">
                    <div>
                        <label for="nuevo-alumno-nombre" class="etiqueta">Nombre completo</label>
                        <input type="text" id="nuevo-alumno-nombre" name="nombre_estudiante" required class="campo">
                    </div>
                    <div>
                        <label for="nuevo-alumno-email" class="etiqueta">Correo</label>
                        <input type="email" id="nuevo-alumno-email" name="email_estudiante" required class="campo">
                    </div>
                    <div>
                        <label for="nuevo-alumno-rut" class="etiqueta">RUT <span class="font-normal text-slate-500">(opcional)</span></label>
                        <input type="text" id="nuevo-alumno-rut" name="rut_estudiante" placeholder="12345678-9" class="campo">
                    </div>
                    <div>
                        <label for="nuevo-alumno-password" class="etiqueta">Contraseña <span class="font-normal text-slate-500">(opcional)</span></label>
                        <input type="password" id="nuevo-alumno-password" name="password_estudiante" autocomplete="new-password" class="campo">
                        <p class="mt-1.5 text-sm text-slate-500">Si la dejas vacía, la contraseña será «estudiante2026».</p>
                    </div>
                </div>

                <div>
                    <label for="matricula-curso" class="etiqueta">Curso</label>
                    <select id="matricula-curso" name="curso_id" required class="campo">
                        <option value="">Elige un curso</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id }}">{{ $curso->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="matricula-lista" class="etiqueta">Número de lista</label>
                        <input type="number" id="matricula-lista" name="numero_lista" value="{{ ($matriculas->max('numero_lista') ?? 0) + 1 }}" min="1" max="60" required class="campo">
                    </div>
                    <div>
                        <label for="matricula-anio" class="etiqueta">Año</label>
                        <input type="number" id="matricula-anio" name="anio" value="{{ $anioVigente }}" required class="campo">
                    </div>
                </div>

                <div>
                    <label for="matricula-apoderado" class="etiqueta">Apoderado <span class="font-normal text-slate-500">(opcional)</span></label>
                    <select id="matricula-apoderado" name="apoderado_id" class="campo">
                        <option value="">Asignar más tarde</option>
                        @foreach($apoderados as $apoderado)
                            <option value="{{ $apoderado->id }}">{{ $apoderado->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                    <button type="button" data-cerrar-modal="modal-nueva-matricula" class="boton-secundario">Cancelar</button>
                    <button type="submit" class="boton-primario">Matricular</button>
                </div>
            </form>
        </x-modal>

        {{-- Asignar apoderado: primero buscar y elegir apoderado por RUT o nombre, luego buscar a cada hijo por RUT o nombre --}}
        <x-modal id="modal-vincular-apoderado" titulo="Asignar apoderado">
            <form action="{{ route('matriculas.asociar_apoderado') }}" method="POST" class="space-y-5"
                  data-vincular-apoderado
                  data-url-busqueda="{{ route('matriculas.buscar_estudiantes') }}"
                  data-url-busqueda-apoderados="{{ route('matriculas.buscar_apoderados') }}">
                @csrf
                <div>
                    <label for="vincular-apoderado-busqueda" class="etiqueta">1. Busca al apoderado</label>
                    <div data-bloque-busqueda-apoderado class="space-y-2">
                        <div class="relative">
                            <x-icono nombre="buscar" clase="pointer-events-none absolute left-4 top-1/2 size-5 -translate-y-1/2 text-slate-400" />
                            <input type="search" id="vincular-apoderado-busqueda" data-campo-busqueda-apoderado autocomplete="off"
                                   placeholder="RUT (ej. 12345678-9) o Nombre y Apellido" class="campo pl-11">
                        </div>
                        <p class="mt-1.5 text-sm text-slate-500" data-mensaje-busqueda-apoderado aria-live="polite">Ingresa el nombre y al menos un apellido, o RUT completo.</p>
                        <ul class="divide-y divide-slate-100 rounded-xl border border-slate-200 empty:hidden max-h-52 overflow-y-auto" data-resultados-busqueda-apoderado></ul>
                    </div>

                    <div data-apoderado-seleccionado class="hidden flex items-center justify-between gap-3 rounded-xl border border-marca-200 bg-marca-50 p-3">
                        <div class="min-w-0 flex-1">
                            <span class="block font-medium text-slate-900" data-apoderado-nombre></span>
                            <span class="block text-sm text-slate-600 truncate" data-apoderado-detalle></span>
                        </div>
                        <button type="button" class="boton-secundario min-h-10 px-3 text-sm shrink-0" data-boton-cambiar-apoderado>
                            Cambiar
                        </button>
                    </div>

                    <input type="hidden" id="vincular-apoderado-id" name="apoderado_id" value="" required data-selector-apoderado>
                </div>

                <fieldset data-paso-busqueda disabled class="space-y-3 disabled:opacity-50">
                    <legend class="etiqueta">2. Busca a su hijo o pupilo</legend>
                    <div>
                        <label for="vincular-busqueda" class="sr-only">RUT o nombre completo del estudiante</label>
                        <div class="relative">
                            <x-icono nombre="buscar" clase="pointer-events-none absolute left-4 top-1/2 size-5 -translate-y-1/2 text-slate-400" />
                            <input type="search" id="vincular-busqueda" data-campo-busqueda autocomplete="off"
                                   placeholder="RUT (ej. 12345678-9) o Nombre y Apellido" class="campo pl-11">
                        </div>
                        <p class="mt-1.5 text-sm text-slate-500" data-mensaje-busqueda aria-live="polite">Ingresa el nombre y al menos un apellido, o RUT completo.</p>
                    </div>
                    <ul class="divide-y divide-slate-100 rounded-xl border border-slate-200 empty:hidden" data-resultados-busqueda></ul>
                </fieldset>

                <div data-paso-seleccion class="hidden space-y-2">
                    <p class="etiqueta">3. Hijos que se vincularán</p>
                    <ul class="space-y-2" data-lista-seleccionados></ul>
                </div>

                <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                    <button type="button" data-cerrar-modal="modal-vincular-apoderado" class="boton-secundario">Cancelar</button>
                    <button type="submit" class="boton-primario disabled:cursor-not-allowed disabled:opacity-50" data-boton-guardar-vinculo disabled>Guardar</button>
                </div>
            </form>
        </x-modal>
    @endif

    @if($esSuperUsuario)
        <x-modal id="modal-editar-matricula" titulo="Editar matrícula">
            <form id="form-editar-matricula" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="editar-matricula-lista" class="etiqueta">Número de lista</label>
                        <input type="number" id="editar-matricula-lista" name="numero_lista" min="1" max="60" required class="campo">
                    </div>
                    <div>
                        <label for="editar-matricula-estado" class="etiqueta">Estado</label>
                        <select id="editar-matricula-estado" name="estado" required class="campo">
                            <option value="regular">Regular</option>
                            <option value="retirado">Retirado</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label for="editar-matricula-apoderado" class="etiqueta">Apoderado</label>
                    <select id="editar-matricula-apoderado" name="apoderado_id" class="campo">
                        <option value="">Sin apoderado</option>
                        @foreach($apoderados as $apoderado)
                            <option value="{{ $apoderado->id }}">{{ $apoderado->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                    <button type="button" data-cerrar-modal="modal-editar-matricula" class="boton-secundario">Cancelar</button>
                    <button type="submit" class="boton-primario">Guardar cambios</button>
                </div>
            </form>
        </x-modal>
    @endif
@endsection
