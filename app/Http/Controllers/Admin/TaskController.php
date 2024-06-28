<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::query();
        $pagination = 10;
        $sortBy = $request->input('column');

        $query->with('users');

        //Paginacion para la tabla
        if ($request->has('pagination')) {
            $pagination = $request->input('pagination');
        }

        // Aplicar la búsqueda si se proporciona un término de búsqueda
        if ($request->has('search')) {
            $searchQuery = $request->input('search');
            $query->where('name', 'like', "%{$searchQuery}%")
                  ->orWhere('description', 'like', "%{$searchQuery}%");
        }

        if ($request->has('date')) {
            $dateQuery = $request->input('date');
            $query->whereDate('created_at', $dateQuery);
        }

        if($sortBy) {
            $query->orderBy($sortBy, $request->input('order'));
        }

        // Obtener los resultados paginados
        $tasks = $query->latest()->paginate($pagination);

        return $tasks;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $task = new Task([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'period' => $request->input('period'),
            'repeat' => $request->input('repeat'),
            'date' => $request->input('date'),
            'hour' => $request->input('hour'),
            'days' => json_encode($request->input('days')),
            'status' => $request->input('status') ?? 0,
        ]);
        $task->save();

        foreach ($request['users'] as $user) {
            $task->users()->attach($user['id']);
        }

        return $this->sendResponse($task, 'Tarea creada exitosamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, string $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['error' => 'Tarea no encontrada'], 404);
        }

        $task->name = $request->input('name');
        $task->description = $request->input('description');
        $task->period = $request->input('period');
        $task->repeat = $request->input('repeat');
        $task->date = $request->input('date');
        $task->hour = $request->input('hour');
        $task->days = json_encode($request->input('days'));
        $task->status = $request->input('status') ?? 0;
        $task->save();

        return $this->sendResponse($task, 'Tarea modificada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return $this->sendError('Tarea no encontrada');
        }

        $task->delete();

        return $this->sendResponse(null, 'Tarea eliminada exitosamente.');
    }
}