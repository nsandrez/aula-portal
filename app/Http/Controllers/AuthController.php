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
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->to('/');
        }

        return view('auth.login');
    }

    /**
     * Procesa la solicitud de inicio de sesión delegando la lógica en AuthService.
     */
    public function login(LoginRequest $request, AuthService $authService): RedirectResponse
    {
        $identificador = (string) $request->input('identificador');
        $password = (string) $request->input('password');
        $remember = (bool) $request->boolean('remember');

        if ($authService->authenticate($identificador, $password, $remember)) {
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
     * Cierra la sesión del usuario.
     */
    public function logout(Request $request, AuthService $authService): RedirectResponse
    {
        $authService->logout($request);

        return redirect()->route('login');
    }
}
