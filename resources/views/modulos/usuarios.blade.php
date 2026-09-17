@extends('layouts.app')

@section('titulo', 'Usuarios')
@section('encabezado', 'Usuarios y Roles')

@php
    $esSuperUsuario = auth()->user()?->esSuperUsuario();
@endphp

@section('contenido')
    <section class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="titulo-pagina">Cuentas del portal</h2>
            <p class="texto-ayuda">
                @foreach($roles as $rol)
                    {{ $usuarios->where('rol', $rol)->count() }} {{ mb_strtolower($rol->obtenerEtiqueta()) }}@if(! $loop->last) · @endif
                @endforeach
            </p>
        </div>
        @if($esSuperUsuario)
            <button type="button" data-abrir-modal="modal-crear-usuario" class="boton-primario">
                <x-icono nombre="mas" clase="size-5" />
                Crear cuenta
            </button>
        @endif
    </section>

    <section class="tarjeta space-y-4" data-filtro-tabla="tabla-usuarios">
        <div class="grid gap-4 md:grid-cols-[2fr_1fr]">
            <div>
                <label for="filtro-usuarios-busqueda" class="etiqueta">Buscar</label>
                <input type="search" id="filtro-usuarios-busqueda" data-filtro-texto placeholder="Nombre, correo o RUT" class="campo">
            </div>
            <div>
                <label for="filtro-usuarios-rol" class="etiqueta">Rol</label>
                <select id="filtro-usuarios-rol" data-filtro-campo="rol" class="campo">
                    <option value="">Todos</option>
                    @foreach($roles as $rol)
                        <option value="{{ $rol->value }}">{{ $rol->obtenerEtiqueta() }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <p class="texto-ayuda" aria-live="polite">Mostrando <span data-filtro-contador>{{ $usuarios->count() }}</span> de {{ $usuarios->count() }}</p>

        <div class="overflow-x-auto">
            <table id="tabla-usuarios" class="tabla">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Correo</th>
                        <th scope="col">RUT</th>
                        <th scope="col">Rol</th>
                        @if($esSuperUsuario)
                            <th scope="col"><span class="sr-only">Acciones</span></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $cuenta)
                        <tr data-buscar="{{ mb_strtolower($cuenta->name.' '.$cuenta->email.' '.$cuenta->rut) }}"
                            data-rol="{{ $cuenta->rol?->value }}">
                            <td class="font-medium text-slate-900">{{ $cuenta->name }}</td>
                            <td class="text-slate-600">{{ $cuenta->email }}</td>
                            <td class="whitespace-nowrap text-slate-600">{{ $cuenta->rut ?? '—' }}</td>
                            <td><span class="insignia-gris">{{ $cuenta->rol?->obtenerEtiqueta() ?? 'Usuario' }}</span></td>
                            @if($esSuperUsuario)
                                <td class="text-right">
                                    <button type="button" class="boton-secundario min-h-10 px-3 text-sm"
                                            data-editar-usuario="{{ json_encode($cuenta->only(['id', 'name', 'email', 'rut', 'rol'])) }}">
                                        Editar
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="texto-ayuda">No hay cuentas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if($esSuperUsuario)
        <x-modal id="modal-crear-usuario" titulo="Crear cuenta">
            <form action="{{ route('usuarios.guardar') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="crear-usuario-nombre" class="etiqueta">Nombre completo</label>
                    <input type="text" id="crear-usuario-nombre" name="name" value="{{ old('name') }}" required class="campo">
                </div>
                <div>
                    <label for="crear-usuario-email" class="etiqueta">Correo</label>
                    <input type="email" id="crear-usuario-email" name="email" value="{{ old('email') }}" required class="campo">
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="crear-usuario-rut" class="etiqueta">RUT <span class="font-normal text-slate-500">(opcional)</span></label>
                        <input type="text" id="crear-usuario-rut" name="rut" value="{{ old('rut') }}" placeholder="12345678-9" class="campo">
                    </div>
                    <div>
                        <label for="crear-usuario-rol" class="etiqueta">Rol</label>
                        <select id="crear-usuario-rol" name="rol" required class="campo">
                            @foreach($roles as $rol)
                                <option value="{{ $rol->value }}" @selected(old('rol') === $rol->value)>{{ $rol->obtenerEtiqueta() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label for="crear-usuario-clave" class="etiqueta">Contraseña</label>
                    <input type="password" id="crear-usuario-clave" name="password" autocomplete="new-password" minlength="6" required class="campo">
                    <p class="mt-1.5 text-sm text-slate-500">Mínimo 6 caracteres. Entrégala a la persona en forma privada.</p>
                </div>
                <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                    <button type="button" data-cerrar-modal="modal-crear-usuario" class="boton-secundario">Cancelar</button>
                    <button type="submit" class="boton-primario">Crear cuenta</button>
                </div>
            </form>
        </x-modal>

        <x-modal id="modal-editar-usuario" titulo="Editar cuenta">
            <form id="form-editar-usuario" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="editar-usuario-id" name="id">
                <div>
                    <label for="editar-usuario-nombre" class="etiqueta">Nombre completo</label>
                    <input type="text" id="editar-usuario-nombre" name="name" required class="campo">
                </div>
                <div>
                    <label for="editar-usuario-email" class="etiqueta">Correo</label>
                    <input type="email" id="editar-usuario-email" name="email" required class="campo">
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="editar-usuario-rut" class="etiqueta">RUT</label>
                        <input type="text" id="editar-usuario-rut" name="rut" class="campo">
                    </div>
                    <div>
                        <label for="editar-usuario-rol" class="etiqueta">Rol</label>
                        <select id="editar-usuario-rol" name="rol" required class="campo">
                            @foreach($roles as $rol)
                                <option value="{{ $rol->value }}">{{ $rol->obtenerEtiqueta() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label for="editar-usuario-clave" class="etiqueta">Nueva contraseña <span class="font-normal text-slate-500">(opcional)</span></label>
                    <input type="password" id="editar-usuario-clave" name="password" autocomplete="new-password" class="campo">
                    <p class="mt-1.5 text-sm text-slate-500">Déjala vacía para mantener la contraseña actual.</p>
                </div>
                <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
                    <button type="button" data-cerrar-modal="modal-editar-usuario" class="boton-secundario">Cancelar</button>
                    <button type="submit" class="boton-primario">Guardar cambios</button>
                </div>
            </form>
        </x-modal>
    @endif
@endsection
