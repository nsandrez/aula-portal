<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\RolUsuario;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GuardarMatriculaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->tieneRol(
            RolUsuario::Administrador,
            RolUsuario::SuperUsuario
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $esNuevo = $this->input('tipo_registro') === 'nuevo';

        return [
            'tipo_registro' => ['nullable', 'in:existente,nuevo'],
            'estudiante_id' => [$esNuevo ? 'nullable' : 'required', 'exists:users,id'],
            'nombre_estudiante' => [$esNuevo ? 'required' : 'nullable', 'string', 'max:255'],
            'email_estudiante' => [$esNuevo ? 'required' : 'nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'rut_estudiante' => ['nullable', 'string', 'max:15', 'unique:users,rut'],
            'password_estudiante' => ['nullable', 'string', 'min:6'],
            'curso_id' => ['required', 'exists:cursos,id'],
            'apoderado_id' => ['nullable', 'exists:users,id'],
            'numero_lista' => ['required', 'integer', 'min:1', 'max:60'],
            'anio' => ['required', 'integer', 'min:2020', 'max:2035'],
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
            'estudiante_id.required' => 'Debe seleccionar un estudiante existente para matricular.',
            'nombre_estudiante.required' => 'El nombre completo del nuevo estudiante es obligatorio.',
            'email_estudiante.required' => 'El correo del nuevo estudiante es obligatorio.',
            'email_estudiante.unique' => 'Ya existe una cuenta con este correo electrónico.',
            'rut_estudiante.unique' => 'Ya existe un usuario con este RUT.',
            'curso_id.required' => 'Debe seleccionar el curso de destino.',
            'numero_lista.required' => 'El número de lista es obligatorio.',
        ];
    }
}
