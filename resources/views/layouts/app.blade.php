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

        {{-- Menú lateral Institucional --}}
        <aside id="sidebar-movil" class="fixed inset-y-0 left-0 z-50 flex w-72 -translate-x-full flex-col border-r border-slate-800 bg-[#0f172a] text-slate-300 transition-transform duration-200 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0 shadow-2xl lg:shadow-none">
            <div class="flex items-center justify-between gap-3 border-b border-slate-800/80 bg-slate-950/40 px-5 py-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="flex size-10 items-center justify-center rounded-xl bg-amber-400 font-black text-slate-950 shadow-xs ring-2 ring-amber-300/60">
                        <x-icono nombre="cursos" clase="size-6 text-slate-950" />
                    </span>
                    <div>
                        <span class="block text-lg font-black tracking-tight text-white">Aula Portal</span>
                        <span class="block text-xs font-semibold text-amber-400">Gestión Escolar</span>
                    </div>
                </a>
                <button id="boton-cerrar-sidebar" type="button" class="rounded-lg p-2 text-slate-400 hover:bg-slate-800 hover:text-white lg:hidden cursor-pointer" aria-label="Cerrar menú">
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
                    <p class="px-3 pb-1 pt-4 text-xs font-bold uppercase tracking-wider text-slate-400">Colegio</p>
                    <x-enlace-menu :ruta="route('cursos.index')" :activo="request()->routeIs('cursos.*')" icono="cursos">Cursos y Asignaturas</x-enlace-menu>
                    <x-enlace-menu :ruta="route('matriculas.index')" :activo="request()->routeIs('matriculas.*')" icono="matriculas">Matrículas y Estudiantes</x-enlace-menu>
                @endif

                @if($usuarioActual?->esSuperUsuario())
                    <p class="px-3 pb-1 pt-4 text-xs font-bold uppercase tracking-wider text-slate-400">Administración TI</p>
                    <x-enlace-menu :ruta="route('usuarios.index')" :activo="request()->routeIs('usuarios.*')" icono="usuarios">Usuarios y Roles</x-enlace-menu>
                @endif
            </nav>

            @auth
                <div class="border-t border-slate-800/80 bg-slate-950/50 p-4">
                    <div class="flex items-center gap-3">
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-xl bg-amber-400 font-black text-slate-950 text-sm">
                            {{ mb_substr($usuarioActual->name, 0, 1) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-bold text-white">{{ $usuarioActual->name }}</p>
                            <span class="inline-block mt-0.5 rounded-full border border-amber-500/30 bg-slate-900 px-2.5 py-0.5 text-[11px] font-semibold text-amber-300">
                                {{ $usuarioActual->rol?->obtenerEtiqueta() ?? 'Usuario' }}
                            </span>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl border border-slate-800 bg-slate-900/80 py-2 px-3 text-xs font-bold text-slate-300 hover:border-red-900/60 hover:bg-red-950/40 hover:text-red-300 transition-colors cursor-pointer">
                            <x-icono nombre="salir" clase="size-4" />
                            <span>Cerrar sesión</span>
                        </button>
                    </form>
                </div>
            @endauth
        </aside>

        {{-- Contenido Principal --}}
        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 flex min-h-16 items-center gap-3 border-b border-slate-200/90 bg-white/95 backdrop-blur-xs px-4 sm:px-8 shadow-2xs">
                <button id="boton-abrir-sidebar" type="button" class="boton-secundario px-3 lg:hidden" aria-label="Abrir menú">
                    <x-icono nombre="menu" />
                    <span>Menú</span>
                </button>
                <h1 class="flex-1 truncate text-xl font-bold tracking-tight text-slate-900">@yield('encabezado', 'Aula Portal')</h1>
                <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-800 border border-blue-200/70">
                    <span class="size-1.5 rounded-full bg-blue-600"></span>
                    Año escolar {{ PeriodoEscolar::anioVigente() }}
                </span>
            </header>

            <main id="contenido-principal" class="mx-auto w-full max-w-7xl flex-1 space-y-6 p-4 sm:p-8">
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
