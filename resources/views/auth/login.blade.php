<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar sesión | Aula Portal</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/school-logo.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full items-center justify-center bg-slate-50 px-4 py-10">
    <main class="w-full max-w-md">
        <div class="mb-8 text-center">
            <img src="{{ asset('images/school-logo.svg') }}" alt="Insignia del colegio" id="school-logo" class="mx-auto size-20">
            <h1 class="mt-4 text-3xl font-bold text-slate-900">Aula Portal</h1>
            <p class="mt-1 texto-ayuda">Portal del colegio para familias, estudiantes y profesores</p>
        </div>

        <div class="tarjeta p-8 shadow-sm">
            <div id="client-error-container" class="mb-5 hidden items-start gap-3 rounded-xl border border-red-200 bg-red-50 p-4 text-base text-red-900" role="alert">
                <x-icono nombre="alerta" clase="size-6 shrink-0 text-red-700" />
                <div id="client-error-message"></div>
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-base text-red-900" role="alert">
                    <p class="font-semibold">No pudimos iniciar tu sesión</p>
                    <ul class="mt-1 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="login-form" action="{{ route('login.post') }}" method="POST" novalidate class="space-y-5">
                @csrf

                <div>
                    <label for="identificador" class="etiqueta">Correo Institucional o RUT</label>
                    <input type="text" id="identificador" name="identificador" value="{{ old('identificador') }}"
                           placeholder="ejemplo: 12345678-9" autocomplete="username" required class="campo">
                    <p class="mt-1.5 text-sm text-slate-500">Puedes escribir tu correo del colegio o tu RUT con guion.</p>
                </div>

                <div>
                    <label for="password" class="etiqueta">Contraseña</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" autocomplete="current-password" required class="campo pr-14">
                        <button type="button" id="toggle-password" aria-label="Mostrar u ocultar contraseña"
                                class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-slate-500 hover:text-marca-600">
                            <x-icono nombre="ojo" id="eye-icon-open" clase="size-6" />
                            <x-icono nombre="ojo-cerrado" id="eye-icon-closed" clase="size-6 hidden" />
                        </button>
                    </div>
                </div>

                <label for="remember" class="flex cursor-pointer items-center gap-3 text-base text-slate-700">
                    <input type="checkbox" name="remember" id="remember" class="size-5 rounded accent-marca-600">
                    <span>Mantener mi sesión abierta en este computador</span>
                </label>

                <button type="submit" class="boton-primario w-full">Entrar</button>
            </form>
        </div>

        <p id="ayuda" class="mt-6 text-center texto-ayuda">
            ¿No puedes entrar? Pide ayuda en la secretaría del colegio.
        </p>
    </main>
</body>
</html>
