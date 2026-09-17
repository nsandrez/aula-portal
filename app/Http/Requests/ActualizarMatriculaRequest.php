<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ActualizarMatriculaRequest extends FormRequest
{
    /**
     * Solo el SuperUsuario puede modificar una matrícula.
     */
    public function authorize(): bool
    {
        return $this->user()?->esSuperUsuario() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'numero_lista' => ['required', 'integer', 'min:1', 'max:60'],
            'estado' => ['required', 'in:regular,retirado'],
            'apoderado_id' => ['nullable', 'exists:users,id'],
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
            'numero_lista.required' => 'El número de lista es obligatorio.',
            'estado.in' => 'El estado debe ser regular o retirado.',
        ];
    }
}
