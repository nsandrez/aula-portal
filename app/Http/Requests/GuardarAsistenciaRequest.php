<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\RolUsuario;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GuardarAsistenciaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->tieneRol(
            RolUsuario::Docente,
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
            'curso_id' => ['required', 'exists:cursos,id'],
            'fecha' => ['required', 'date'],
            'asistencias' => ['required', 'array'],
            'asistencias.*.estudiante_id' => ['required', 'exists:users,id'],
            'asistencias.*.estado' => ['required', 'in:presente,ausente,atraso,justificado'],
            'asistencias.*.hora_llegada' => ['nullable', 'string'],
            'asistencias.*.observacion' => ['nullable', 'string', 'max:255'],
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
            'curso_id.required' => 'El curso es obligatorio.',
            'fecha.required' => 'La fecha de asistencia es obligatoria.',
            'asistencias.required' => 'Debe registrar la nómina de asistencia.',
            'asistencias.*.estado.in' => 'El estado debe ser presente, ausente, atraso o justificado.',
        ];
    }
}
