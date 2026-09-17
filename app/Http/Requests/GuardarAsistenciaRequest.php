<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\RolUsuario;
use App\Utils\PeriodoEscolar;
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
            'fecha' => ['nullable', 'date', 'date_equals:'.PeriodoEscolar::fechaDeHoy()],
            'asistencias' => ['required', 'array'],
            'asistencias.*.estudiante_id' => ['required', 'exists:users,id'],
            'asistencias.*.estado' => ['required', 'in:presente,ausente,atraso,justificado'],
            'asistencias.*.hora_llegada' => ['nullable', 'required_if:asistencias.*.estado,atraso', 'date_format:H:i'],
            'asistencias.*.observacion' => ['nullable', 'required_if:asistencias.*.estado,justificado', 'string', 'max:255'],
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
            'fecha.date_equals' => 'Solo se puede pasar asistencia del día de hoy.',
            'asistencias.required' => 'Debe registrar la nómina de asistencia.',
            'asistencias.*.estado.in' => 'El estado debe ser presente, ausente, atraso o justificado.',
            'asistencias.*.hora_llegada.required_if' => 'Indica la hora de llegada de cada estudiante con atraso.',
            'asistencias.*.hora_llegada.date_format' => 'La hora de llegada debe tener el formato HH:MM.',
            'asistencias.*.observacion.required_if' => 'Escribe el motivo de cada inasistencia justificada.',
        ];
    }
}
