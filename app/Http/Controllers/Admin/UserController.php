<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();
        $pagination = 10;
        $sortBy = $request->input('column');

        //Paginacion para la tabla
        if ($request->has('pagination')) {
            $pagination = $request->input('pagination');
        }

        // Aplicar la búsqueda si se proporciona un término de búsqueda
        if ($request->has('name')) {
            $nameQuery = $request->input('name');
            $query->where('name', 'like', "%{$nameQuery}%");
        }

        if ($request->has('email')) {
            $emailQuery = $request->input('email');
            $query->where('email', 'like', "%{$emailQuery}%");
        }

        if ($request->has('date')) {
            $dateQuery = $request->input('date');
            $query->whereDate('created_at', $dateQuery);
        }

        if($sortBy) {
            $query->orderBy($sortBy, $request->input('order'));
        }

        // Obtener los resultados paginados
        $users = $query->latest()->paginate($pagination);

        return $users;
    }


    /**
     * Display a listing of the resource.
     */
    public function profile(Request $request)
    {
        return $request->user()->only(['name', 'email', 'theme']);
    }

    /**
     * Display a listing of the resource.
     */
    public function getAllUsers()
    {
        $users = User::all();
        return $this->sendResponse($users, 'Usuarios encontrados exitosamente.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error de validaciones.', $validator->errors());
        }

        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        $user->save();

        return $this->sendResponse($user, 'Usuario creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->sendError('Usuario no encontrado');
        }

        return $this->sendResponse($user, 'Usuario encontrado exitosamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error de validaciones.', $validator->errors());
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->input('password')) {
            $user->password = $request->input('password');
        }
        $user->save();

        return $this->sendResponse($user, 'Usuario modificado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->sendError('Usuario no encontrado');
        }

        $user->delete();

        return $this->sendResponse(null, 'Usuario eliminado exitosamente.');
    }

    /**
     * Change theme
     */
    public function change_theme(Request $request)
    {
        $userData = $request->user();

        if ($userData->theme == 'light' || $userData->theme == null) { //Si es ta en null o light pasa a dark
            $user = User::find($request->user()->id);
            $user->theme = 'dark';
            $user->save();

        }else{ //Si esta en dark pasa a light
            $user = User::find($request->user()->id);
            $user->theme = 'light';
            $user->save();
        }

       return $user;
    }
}
