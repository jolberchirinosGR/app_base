<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

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
        if ($request->has('search')) {
            $searchQuery = $request->input('search');
            $query->where('name', 'like', "%{$searchQuery}%")
                ->orWhere('email', 'like', "%{$searchQuery}%");
        }

        if ($request->has('role')) {
            $roleQuery = $request->input('role');
            $roleQuery == 'null' ? null : $query->where('id_role',  $roleQuery);
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
        return $request->user()->only(['name', 'email', 'id_role', 'theme']);
    }

    /**
     * Display a listing of the resource.
     */
    public function get_all_users(Request $request)
    {
        $query = User::query();

        $query->orderBy('id', 'desc');

        // Aplicar la búsqueda si se proporciona un término de búsqueda
        if ($request->has('search')) {
            $searchQuery = $request->input('search');
            $query->where('name', 'like', "%{$searchQuery}%");
        }

        // Obtener los resultados paginados
        $users = $query->get();

        return $this->sendResponse($users, 'Usuarios encontrados exitosamente.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $user = new User([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'id_role' => $request->input('id_role') ?? 2, //Haciendo referencia a que es empleado
        ]);

        $user->save();

        return $this->sendResponse($user, 'Usuario creado exitosamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        if ($request->input('password')) {
            $user->password = $request->input('password');
        }
        $user->id_role = $request->input('id_role');
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

    /**
     * Obtener todos los roles
     */
    public function get_roles()
    {
        return Role::orderBy('name', 'asc')->get();
    }
}
