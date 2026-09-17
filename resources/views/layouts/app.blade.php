@use('App\Enums\RolUsuario')
@use('App\Utils\PeriodoEscolar')

@php
    $usuarioActual = auth()->user();
    $esPersonalEscolar = $usuarioActual?->tieneRol(RolUsuario::Docente, RolUsuario::Administrador, RolUsuario::SuperUsuario);
    $esGestion = $usuarioActual?->tieneRol(RolUsuario::Administrador, RolUsuario::SuperUsuario);
@endphp
<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Inicio') | Aula Portal</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/school-logo.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50">
    <a href="#contenido-principal" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-white focus:px-4 focus:py-2">
        Saltar al contenido
    </a>

    <div class="flex min-h-screen">
        <div id="sidebar-backdrop" class="fixed inset-0 z-40 hidden bg-slate-900/50 lg:hidden"></div>

        {{-- Menú lateral --}}
        <aside id="sidebar-movil" class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-slate-200 bg-white transition-transform duration-200 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0">
            <div class="flex items-center justify-between gap-3 border-b border-slate-200 px-5 py-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <img src="{{ asset('images/school-logo.svg') }}" alt="" class="size-10">
                    <span class="text-xl font-bold text-slate-900">Aula Portal</span>
                </a>
                <button id="boton-cerrar-sidebar" type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden" aria-label="Cerrar menú">
                    <x-icono nombre="cerrar" />
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto p-4" aria-label="Menú principal">
                <x-enlace-menu :ruta="route('home')" :activo="request()->routeIs('home')" icono="inicio">Inicio</x-enlace-menu>

                @if($esPersonalEscolar)
                    <x-enlace-menu :ruta="route('notas.index')" :activo="request()->routeIs('notas.*')" icono="notas">Gestión de Notas</x-enlace-menu>
                    <x-enlace-menu :ruta="route('asistencias.index')" :activo="request()->routeIs('asistencias.*')" icono="asistencia">Control de Asistencia</x-enlace-menu>
                @elseif($usuarioActual?->esEstudiante())
                    <x-enlace-menu :ruta="route('notas.index')" :activo="request()->routeIs('notas.*')" icono="notas">Mis Calificaciones</x-enlace-menu>
                    <x-enlace-menu :ruta="route('asistencias.index')" :activo="request()->routeIs('asistencias.*')" icono="asistencia">Mi Asistencia</x-enlace-menu>
                @elseif($usuarioActual?->esApoderado())
                    <x-enlace-menu :ruta="route('notas.index')" :activo="request()->routeIs('notas.*')" icono="notas">Boletín de Pupilos</x-enlace-menu>
                    <x-enlace-menu :ruta="route('asistencias.index')" :activo="request()->routeIs('asistencias.*')" icono="asistencia">Asistencia y Atrasos</x-enlace-menu>
                @endif

                @if($esGestion)
                    <p class="px-4 pb-1 pt-5 text-sm font-semibold text-slate-500">Colegio</p>
                    <x-enlace-menu :ruta="route('cursos.index')" :activo="request()->routeIs('cursos.*')" icono="cursos">Cursos y Asignaturas</x-enlace-menu>
                    <x-enlace-menu :ruta="route('matriculas.index')" :activo="request()->routeIs('matriculas.*')" icono="matriculas">Matrículas y Estudiantes</x-enlace-menu>
                @endif

                @if($usuarioActual?->esSuperUsuario())
                    <p class="px-4 pb-1 pt-5 text-sm font-semibold text-slate-500">Administración TI</p>
                    <x-enlace-menu :ruta="route('usuarios.index')" :activo="request()->routeIs('usuarios.*')" icono="usuarios">Usuarios y Roles</x-enlace-menu>
                @endif
            </nav>

            @auth
                <div class="border-t border-slate-200 p-4">
                    <p class="truncate text-base font-semibold text-slate-900">{{ $usuarioActual->name }}</p>
                    <p class="text-sm text-slate-500">{{ $usuarioActual->rol?->obtenerEtiqueta() ?? 'Usuario' }}</p>
                    <form action="{{ route('logout') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="boton-secundario w-full">
                            <x-icono nombre="salir" clase="size-5" />
                            <span>Cerrar sesión</span>
                        </button>
                    </form>
                </div>
            @endauth
        </aside>

        {{-- Contenido --}}
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 flex min-h-16 items-center gap-3 border-b border-slate-200 bg-white px-4 sm:px-8">
                <button id="boton-abrir-sidebar" type="button" class="boton-secundario px-3 lg:hidden" aria-label="Abrir menú">
                    <x-icono nombre="menu" />
                    <span>Menú</span>
                </button>
                <h1 class="flex-1 truncate text-xl font-bold text-slate-900">@yield('encabezado', 'Aula Portal')</h1>
                <span class="hidden text-base text-slate-500 sm:inline">Año escolar {{ PeriodoEscolar::anioVigente() }}</span>
            </header>

            <main id="contenido-principal" class="mx-auto w-full max-w-6xl flex-1 space-y-6 p-4 sm:p-8">
                @if(session('exito'))
                    <div class="flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-base text-green-900" role="status">
                        <x-icono nombre="asistencia" clase="size-6 shrink-0 text-green-700" />
                        <p>{{ session('exito') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-base text-red-900" role="alert">
                        <p class="flex items-center gap-2 font-semibold">
                            <x-icono nombre="alerta" clase="size-6 text-red-700" />
                            Revisa estos datos antes de continuar:
                        </p>
                        <ul class="mt-2 list-inside list-disc space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('contenido')
            </main>
        </div>
    </div>
</body>
</html>
