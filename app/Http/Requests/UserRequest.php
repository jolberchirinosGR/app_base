<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $data = $this->all();

        $rules = [
            'name' => 'required|max:190',
            'id_role' => 'nullable|exists:roles,id',
        ];
    
        // Si es una solicitud de creación, se requiere la contraseña
        if ($this->isMethod('post')) {
            $rules['password'] = 'required|min:8';
            $rules['email'] = 'required|unique:users,email';
        }

        // Si es una solicitud de creación, se requiere la contraseña
        if ($this->isMethod('put')) {
            $rules['email'] = 'required|unique:users,email,'.$data['id'] ?? null;
        }
    
        return $rules;
    }

    /**
     * Return messages validated
     *
     * @return array<string>
     */
    public function messages()
    {
        return [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.max' => 'El campo nombre no puede ser mayor a :min caracteres.',
            'email.required' => 'El campo correo es obligatorio.',
            'email.max' => 'El campo correo no puede ser mayor a :min caracteres.',
            'email.unique' => 'Este correo ya está en uso.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'role_id.required' => 'El campo rol es obligatorio.',
            'role_id.exists' => 'El rol proporcionado no es válido.',
        ];
    }
}
