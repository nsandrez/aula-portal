<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\RolUsuario;
use App\Rules\BusquedaPersonaValida;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class BuscarEstudiantesRequest extends FormRequest
{
    /**
     * Solo Administrador y SuperUsuario pueden buscar estudiantes para vincular.
     */
    public function authorize(): bool
    {
        return $this->user()?->tieneRol(RolUsuario::Administrador, RolUsuario::SuperUsuario) ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'busqueda' => ['required', 'string', 'min:3', 'max:100', new BusquedaPersonaValida],
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
            'busqueda.required' => 'Escribe el RUT o el nombre del estudiante.',
            'busqueda.min' => 'Escribe al menos 3 caracteres para buscar.',
        ];
    }
}
