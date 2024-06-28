<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Cambiar según tus necesidades de autorización
    }

    /**
     * Obtiene las reglas de validación que se aplican a la solicitud.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255', // Cambiado 'text' a 'string'
            'period' => 'nullable|string|max:255',
            'repeat' => 'nullable|boolean',
            'date' => 'nullable|date',
            'hour' => 'nullable|date_format:H:i',
            'days' => 'nullable|array|max:255',
            'status' => 'nullable|integer',
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados para las reglas de validación.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.string' => 'El campo nombre debe ser una cadena de texto.',
            'name.max' => 'El campo nombre no debe exceder los 255 caracteres.',
            'description.string' => 'El campo descripción debe ser una cadena de texto.',
            'description.max' => 'El campo descripción no debe exceder los 255 caracteres.',
            'period.string' => 'El campo período debe ser una cadena de texto.',
            'period.max' => 'El campo período no debe exceder los 255 caracteres.',
            'repeat.boolean' => 'El campo repetir debe ser verdadero o falso.',
            'date.date' => 'El campo fecha debe ser una fecha válida.',
            'hour.date_format' => 'El campo hora debe tener el formato HH:MM.',
            'days.array' => 'El campo días debe ser un array.',
            'days.*.boolean' => 'Cada día debe ser verdadero o falso.',
            'status.integer' => 'El campo estado debe ser un número entero.',
        ];
    }
}