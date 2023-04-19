<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return $this->isMethod('POST') ? $this->createRules() : $this->updateRules();
    }

    protected function createRules()
    {
        return [
            'solicitud_id'=>'required|uuid|exists:solicitudes_firmas,id',
            'usuario_registra'=>'required|uuid|exists:usuarios,id',
            'nombre_accion'=>'required|string',
            'descripcion'=>'required|string',
            // 'fecha'=>'required|date'
        ];
    }
    protected function updateRules()
    {
        return [
            'solicitud_id'=>'uuid|exists:solicitudes_firmas,id',
            'usuario_registra'=>'uuid|exists:usuarios,id',
            'nombre_accion'=>'string',
            'descripcion'=>'string',
            // 'fecha'=>'required|date'
        ];
    }
}
