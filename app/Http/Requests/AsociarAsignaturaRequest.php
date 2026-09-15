<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\RolUsuario;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AsociarAsignaturaRequest extends FormRequest
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
            'asignatura_id' => ['required', 'exists:asignaturas,id'],
            'docente_id' => ['nullable', 'exists:users,id'],
            'horas_semanales' => ['required', 'integer', 'min:1', 'max:20'],
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
            'asignatura_id.required' => 'Debe seleccionar una asignatura para asociar.',
            'asignatura_id.exists' => 'La asignatura seleccionada no existe en el catálogo.',
            'docente_id.exists' => 'El docente seleccionado no es válido.',
            'horas_semanales.required' => 'Indique la carga horaria semanal.',
            'horas_semanales.min' => 'La carga mínima es de 1 hora semanal.',
        ];
    }
}
