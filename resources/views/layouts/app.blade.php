<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('titulo', 'Aula Portal') | Sistema de Gestión Escolar</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/school-logo.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 text-slate-800 antialiased selection:bg-amber-400 selection:text-slate-950">
    <div class="min-h-screen flex">
        
        <!-- Telón de fondo para móvil -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/60 z-40 lg:hidden hidden transition-opacity"></div>

        <!-- SIDEBAR INSTITUCIONAL (Negro carbón con detalles en amarillo escolar) -->
        <aside id="sidebar-movil" class="fixed inset-y-0 left-0 z-50 w-72 bg-[#12151c] border-r border-slate-800 flex flex-col justify-between transition-transform duration-200 ease-in-out -translate-x-full lg:translate-x-0 lg:static">
            
            <!-- Parte superior del Sidebar -->
            <div class="flex-1 flex flex-col overflow-y-auto">
                
                <!-- Encabezado del Colegio con Logo Institucional Amarillo y Negro -->
                <div class="p-5 border-b border-slate-800 flex items-center justify-between">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-black border border-amber-500/40 p-1.5 flex items-center justify-center group-hover:border-amber-400 transition-colors shadow-sm">
                            <img src="{{ asset('images/school-logo.svg') }}" alt="Logo Colegio" class="w-7 h-7 object-contain">
                        </div>
                        <div>
                            <h2 class="text-base font-bold text-white tracking-tight leading-none group-hover:text-amber-400 transition-colors">Aula Portal</h2>
                            <p class="text-[11px] text-amber-400/90 font-medium mt-1">Gestión Institucional</p>
                        </div>
                    </a>

                    <!-- Botón cerrar en móvil -->
                    <button id="boton-cerrar-sidebar" type="button" class="lg:hidden text-slate-400 hover:text-white p-1 rounded-lg focus:outline-none" aria-label="Cerrar menú">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Tarjeta de Perfil y Rol del Usuario -->
                @auth
                    <div class="p-3.5 mx-3.5 my-3.5 rounded-xl bg-[#191d26] border border-slate-800">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg bg-amber-500/20 flex items-center justify-center font-bold text-amber-400 text-xs border border-amber-500/40">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->rut ?? auth()->user()->email }}</p>
                            </div>
                        </div>
                        <div class="mt-2.5 pt-2 border-t border-slate-800 flex items-center justify-between">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold uppercase tracking-wider border {{ auth()->user()->rol?->obtenerClasesInsignia() ?? 'bg-slate-800 text-slate-300 border-slate-700' }}">
                                {{ auth()->user()->rol?->obtenerEtiqueta() ?? 'Usuario' }}
                            </span>
                            <span class="text-[10px] text-amber-400/80 font-medium">Ciclo 2026</span>
                        </div>
                    </div>
                @endauth

                <!-- Navegación por Módulos y Permisos -->
                <nav class="px-3 py-2 space-y-5 text-sm">
                    
                    <!-- 1. SECCIÓN PRINCIPAL -->
                    <div>
                        <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Navegación</p>
                        <div class="space-y-0.5">
                            <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium {{ request()->routeIs('home') ? 'bg-amber-500/15 text-amber-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }} transition-colors">
                                <svg class="w-4 h-4 {{ request()->routeIs('home') ? 'text-amber-400' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>
                                <span>Panel General</span>
                            </a>
                        </div>
                    </div>

                    <!-- 2. MÓDULO ACADÉMICO: NOTAS Y CALIFICACIONES -->
                    <div>
                        <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Calificaciones</p>
                        <div class="space-y-0.5">
                            @if(auth()->user()?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
                                <a href="{{ route('notas.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium {{ request()->routeIs('notas.*') ? 'bg-amber-500/15 text-amber-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }} transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                    <span>Gestión de Notas</span>
                                </a>
                            @endif

                            @if(auth()->user()?->esEstudiante())
                                <a href="{{ route('notas.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium {{ request()->routeIs('notas.*') ? 'bg-amber-500/15 text-amber-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }} transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342" />
                                    </svg>
                                    <span>Mis Calificaciones</span>
                                </a>
                            @endif

                            @if(auth()->user()?->esApoderado())
                                <a href="{{ route('notas.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium {{ request()->routeIs('notas.*') ? 'bg-amber-500/15 text-amber-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }} transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                    </svg>
                                    <span>Boletín de Pupilos</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- 3. MÓDULO ASISTENCIAS (DÍA A DÍA) -->
                    <div>
                        <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Asistencias</p>
                        <div class="space-y-0.5">
                            @if(auth()->user()?->tieneRol(\App\Enums\RolUsuario::Docente, \App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
                                <a href="{{ route('asistencias.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium {{ request()->routeIs('asistencias.*') ? 'bg-amber-500/15 text-amber-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }} transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Control de Asistencia</span>
                                </a>
                            @endif

                            @if(auth()->user()?->esEstudiante())
                                <a href="{{ route('asistencias.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium {{ request()->routeIs('asistencias.*') ? 'bg-amber-500/15 text-amber-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }} transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                    </svg>
                                    <span>Mi Asistencia</span>
                                </a>
                            @endif

                            @if(auth()->user()?->esApoderado())
                                <a href="{{ route('asistencias.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium {{ request()->routeIs('asistencias.*') ? 'bg-amber-500/15 text-amber-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }} transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Asistencia y Atrasos</span>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- 4. GESTIÓN ESCOLAR (Cursos y Asignaturas Asociadas) -->
                    @if(auth()->user()?->tieneRol(\App\Enums\RolUsuario::Administrador, \App\Enums\RolUsuario::SuperUsuario))
                        <div>
                            <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Gestión Curricular</p>
                            <div class="space-y-0.5">
                                <a href="{{ route('cursos.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium {{ request()->routeIs('cursos.*') ? 'bg-amber-500/15 text-amber-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }} transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
                                    </svg>
                                    <span>Cursos y Asignaturas</span>
                                </a>
                                <a href="{{ route('matriculas.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium {{ request()->routeIs('matriculas.*') ? 'bg-amber-500/15 text-amber-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }} transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                    </svg>
                                    <span>Matrículas y Estudiantes</span>
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- 5. CONFIGURACIÓN TI (Solo SuperUsuario) -->
                    @if(auth()->user()?->esSuperUsuario())
                        <div>
                            <p class="px-3 text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Administración TI</p>
                            <div class="space-y-0.5">
                                <a href="{{ route('usuarios.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg font-medium {{ request()->routeIs('usuarios.*') ? 'bg-amber-500/15 text-amber-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }} transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75" />
                                    </svg>
                                    <span>Usuarios y Roles</span>
                                </a>
                            </div>
                        </div>
                    @endif
                </nav>
            </div>

            <!-- Pie del Sidebar: Cerrar Sesión -->
            <div class="p-4 border-t border-slate-800 bg-[#0d0f14]">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 px-3 rounded-lg text-xs font-medium text-slate-300 hover:text-red-400 hover:bg-red-950/30 border border-transparent hover:border-red-800/40 transition-colors cursor-pointer">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                        <span>Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- CONTENIDO PRINCIPAL (Fondo Claro Escolar) -->
        <div class="flex-1 flex flex-col min-w-0 bg-slate-50">
            
            <!-- Barra superior blanca con acento sutil -->
            <header class="h-16 bg-white border-b border-slate-200 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <div class="flex items-center gap-3">
                    <!-- Botón hamburguesa móvil -->
                    <button id="boton-abrir-sidebar" type="button" class="lg:hidden p-2 rounded-lg text-slate-600 hover:text-slate-900 hover:bg-slate-100 focus:outline-none" aria-label="Abrir menú">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-base sm:text-lg font-bold text-slate-900 truncate">
                            @yield('encabezado', 'Portal Académico')
                        </h1>
                    </div>
                </div>

                <!-- Estado del usuario y fecha escolar -->
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 text-xs text-slate-700 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 font-medium">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Ciclo Escolar 2026</span>
                    </span>
                </div>
            </header>

            <!-- Contenedor de la vista en blanco y grises suaves -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
                @yield('contenido')
            </main>
        </div>
    </div>
</body>
</html>
