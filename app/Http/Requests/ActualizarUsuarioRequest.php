<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\RolUsuario;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ActualizarUsuarioRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->esSuperUsuario();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        /** @var User $usuario */
        $usuario = $this->route('usuario');
        $usuarioId = $usuario->id ?? $this->input('id');

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuarioId)],
            'rut' => ['nullable', 'string', 'max:15', Rule::unique('users', 'rut')->ignore($usuarioId)],
            'rol' => ['required', new Enum(RolUsuario::class)],
            'password' => ['nullable', 'string', 'min:6'],
        ];
    }

    /**
     * Mensajes de validación en español.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre completo del usuario es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Ya existe un usuario registrado con este correo.',
            'rut.unique' => 'Ya existe un usuario con este RUT.',
            'rol.required' => 'Debe asignar un rol válido al usuario.',
            'password.min' => 'La nueva contraseña debe tener al menos 6 caracteres.',
        ];
    }
}
