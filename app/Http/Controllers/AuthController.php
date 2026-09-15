<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Muestra el formulario de inicio de sesión escolar.
     */
    public function mostrarFormularioLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->to('/');
        }

        return view('auth.login');
    }

    /**
     * Procesa la solicitud de inicio de sesión delegando la lógica en AuthService.
     */
    public function iniciarSesion(LoginRequest $request, AuthService $authService): RedirectResponse
    {
        $identificador = (string) $request->input('identificador');
        $password = (string) $request->input('password');
        $recordar = (bool) $request->boolean('remember');

        if ($authService->autenticar($identificador, $password, $recordar)) {
            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()
            ->withInput($request->only('identificador', 'remember'))
            ->withErrors([
                'identificador' => 'Las credenciales ingresadas no coinciden con nuestros registros escolares.',
            ]);
    }

    /**
     * Cierra la sesión activa del usuario.
     */
    public function cerrarSesion(Request $request, AuthService $authService): RedirectResponse
    {
        $authService->cerrarSesion($request);

        return redirect()->route('login');
    }
}
