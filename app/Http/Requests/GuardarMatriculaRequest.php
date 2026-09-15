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
        return [
            'estudiante_id' => ['required', 'exists:users,id'],
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
            'estudiante_id.required' => 'Debe seleccionar un estudiante para matricular.',
            'curso_id.required' => 'Debe seleccionar el curso de destino.',
            'numero_lista.required' => 'El número de lista es obligatorio.',
        ];
    }
}
