<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\RolUsuario;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AsociarApoderadoRequest extends FormRequest
{
    /**
     * Solo Administrador y SuperUsuario pueden vincular apoderados.
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
            'apoderado_id' => ['required', 'exists:users,id'],
            'estudiante_ids' => ['required', 'array', 'min:1'],
            'estudiante_ids.*' => ['exists:users,id'],
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
            'apoderado_id.required' => 'Debe seleccionar un apoderado.',
            'estudiante_ids.required' => 'Debe seleccionar al menos un estudiante.',
        ];
    }
}
