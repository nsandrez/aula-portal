<!DOCTYPE html>
<html lang="es" class="h-full bg-black">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar Sesión | Aula Portal</title>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-zinc-950 text-zinc-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8 selection:bg-amber-400 selection:text-black">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <!-- Logo del establecimiento escolar -->
        <div class="flex justify-center mb-4">
            <div class="relative w-24 h-24 rounded-2xl bg-zinc-900 border-2 border-amber-400/60 p-2 shadow-lg shadow-amber-400/10 flex items-center justify-center">
                <img src="{{ asset('images/school-logo.svg') }}" alt="Insignia del Establecimiento" class="w-20 h-20 object-contain drop-shadow" id="school-logo">
            </div>
        </div>

        <!-- Encabezado escolar -->
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-400/10 text-amber-300 border border-amber-400/30 uppercase tracking-widest mb-2">
            Portal Académico
        </span>
        <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white">
            Aula Portal
        </h1>
        <p class="mt-1 text-sm text-zinc-400">
            Estudiantes, Docentes y Comunidad Escolar
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <div class="bg-zinc-900/90 border border-zinc-800 shadow-2xl rounded-2xl p-6 sm:p-8 backdrop-blur-sm relative overflow-hidden">
            <!-- Barra superior decorativa amarilla institucional -->
            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-500 via-yellow-400 to-amber-500"></div>

            <!-- Contenedor dinámico para errores de validación JS (Lado Cliente) -->
            <div id="client-error-container" class="hidden mb-5 p-3.5 rounded-xl bg-amber-400/10 border border-amber-400/30 text-amber-200 text-sm flex items-start gap-3" role="alert">
                <svg class="w-5 h-5 text-amber-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <div id="client-error-message" class="font-medium"></div>
            </div>

            <!-- Contenedor para errores devueltos por el servidor (Laravel) -->
            @if ($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-red-950/50 border border-red-500/40 text-red-200 text-sm flex items-start gap-3" role="alert">
                    <svg class="w-5 h-5 text-red-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <div>
                        <p class="font-semibold">No fue posible acceder:</p>
                        <ul class="mt-1 list-disc list-inside space-y-0.5 text-xs text-red-300">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Formulario de acceso -->
            <form id="login-form" action="{{ route('login.post') }}" method="POST" novalidate class="space-y-5">
                @csrf

                <!-- Campo Correo o RUT -->
                <div>
                    <label for="identificador" class="block text-sm font-semibold text-zinc-200 mb-1.5">
                        Correo Institucional o RUT Chileno
                    </label>
                    <div class="relative">
                        <input
                            type="text"
                            id="identificador"
                            name="identificador"
                            value="{{ old('identificador') }}"
                            placeholder="ej. alumno@colegio.cl o 12.345.678-9"
                            autocomplete="username"
                            required
                            class="w-full px-4 py-3 rounded-xl bg-zinc-950 border border-zinc-700 text-white placeholder-zinc-500 focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-400/30 transition duration-150 ease-in-out text-sm"
                        >
                        <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-zinc-500">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-zinc-400">
                        Ingresa tu email escolar o tu RUT con o sin puntos y guión.
                    </p>
                </div>

                <!-- Campo Contraseña con botón mostrar/ocultar -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-semibold text-zinc-200">
                            Contraseña
                        </label>
                    </div>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="••••••••••••"
                            autocomplete="current-password"
                            required
                            class="w-full pl-4 pr-12 py-3 rounded-xl bg-zinc-950 border border-zinc-700 text-white placeholder-zinc-500 focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-400/30 transition duration-150 ease-in-out text-sm"
                        >
                        <!-- Botón para alternar visibilidad de contraseña -->
                        <button
                            type="button"
                            id="toggle-password"
                            aria-label="Mostrar u ocultar contraseña"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-zinc-400 hover:text-amber-400 focus:outline-none transition-colors"
                        >
                            <!-- Icono ojo abierto -->
                            <svg id="eye-icon-open" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <!-- Icono ojo cerrado (oculto por defecto) -->
                            <svg id="eye-icon-closed" class="w-5 h-5 hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Opciones secundarias: Recordar sesión -->
                <div class="flex items-center justify-between text-sm pt-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                            class="w-4 h-4 rounded bg-zinc-950 border-zinc-700 text-amber-400 focus:ring-amber-400/40 accent-amber-400"
                        >
                        <span class="text-xs text-zinc-300">Recordar en este equipo</span>
                    </label>

                    <a href="#ayuda" class="text-xs font-medium text-amber-400 hover:text-yellow-300 transition-colors">
                        ¿Olvidaste tu clave?
                    </a>
                </div>

                <!-- Botón de Envío Amarillo Escolar -->
                <div class="pt-2">
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center gap-2 py-3 px-4 rounded-xl font-bold text-black bg-gradient-to-r from-amber-400 via-yellow-400 to-amber-500 hover:from-amber-300 hover:to-yellow-300 shadow-lg shadow-amber-400/20 active:scale-[0.99] transition duration-150 ease-in-out cursor-pointer text-sm"
                    >
                        <span>Ingresar al Portal Aula</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </button>
                </div>
            </form>

            <!-- Pie de soporte escolar -->
            <div class="mt-6 pt-5 border-t border-zinc-800 text-center text-xs text-zinc-500">
                <p>¿Problemas para acceder a tu cuenta?</p>
                <p class="mt-1 text-zinc-400">
                    Contacta a secretaría académica o al equipo de soporte de tu establecimiento.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
