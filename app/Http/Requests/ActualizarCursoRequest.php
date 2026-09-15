<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ActualizarCursoRequest extends FormRequest
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
        return [
            'nombre' => ['required', 'string', 'max:100'],
            'nivel' => ['required', 'string', 'max:100'],
            'anio' => ['required', 'integer', 'min:2020', 'max:2035'],
            'profesor_jefe_id' => ['nullable', 'exists:users,id'],
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
            'nombre.required' => 'El nombre del curso es obligatorio.',
            'nivel.required' => 'El nivel educativo es obligatorio.',
            'profesor_jefe_id.exists' => 'El profesor jefe seleccionado no es válido.',
        ];
    }
}
