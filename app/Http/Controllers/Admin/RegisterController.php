<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends BaseController
{
    /*
    * Mesagges Validator
    */
    protected function messages()
    {
        return [
            'name.required' => 'El Nombre es obligatorio.',
            'email.required' => 'El Correo es obligatorio.',
            'email.email' => 'El campo correo tiene que ser uno valido.',
            'email.exists' => 'El campo correo no se encuentra en nuestra base de datos.',
            'password.required' => 'La contraseña es obligatoria.',
        ];
    }

    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request): JsonResponse
    {
        $messages = $this->messages();// Mensajes del validadors

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
        ], $messages);

        if($validator->fails()){
            return $this->sendError('Error de validaciones.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'Usuario registrado con exito');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request): JsonResponse
    {
        $messages = $this->messages();// Mensajes del validadors

        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ], $messages);


        if($validator->fails()){
            return $this->sendError('Error de validaciones.', $validator->errors());
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->plainTextToken;
            $success['name'] =  $user->name;

            return $this->sendResponse($success, 'Usuario logeado con exito');
        }
        else{
            return $this->sendError('No autorizado.', ['error'=>'Estas credenciales no coinciden con nuestros registros.']);
        }
    }
}
