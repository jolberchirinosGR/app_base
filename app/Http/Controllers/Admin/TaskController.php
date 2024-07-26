<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\TaskRequest;
use App\Models\Task;
use Carbon\Carbon;
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
                  ->orWhere('description', 'like', "%{$searchQuery}%")
                  ->orWhere('start_date', 'like', "%{$searchQuery}%")
                  ->orWhereHas('users', function ($q) use ($searchQuery) {
                    $q->where('name', 'like', "%{$searchQuery}%");
                  });
        }

        if($sortBy) {
            $query->orderBy($sortBy, $request->input('order'));
        }

        // Obtener los resultados paginados
        $tasks = $query->latest()->paginate($pagination);

        return $tasks;
    }

    /**
     * Display a listing of the resource.
     */
    public function get_task_by_date(Request $request)
    {
        $user = $request->user(); // Usuario logeado
    
        // Obtiene tareas relacionadas con el usuario logeado
        $tasks = Task::whereHas('users', function ($q) use ($user) {
            $q->where('users.id', $user->id); 
        });
        
        // if ($tasks->where('repeat', true)) {
        //     // Verifica si la tarea se repite y si corresponde al día de hoy
        //     $today = Carbon::today();
        //     $tasks = $tasks->get()->filter(function ($task) use ($today) {
        //         if ($task->repeat) {
        //             $repeatDays = json_decode($task->repeat_days, true); // Asumiendo que 'repeat_days' es el nombre del campo
        //             $dayName = $today->format('l'); // Día de la semana en inglés
    
        //             if (isset($repeatDays[$dayName]) && $repeatDays[$dayName]) {
        //                 return true;
        //             }
        //         }
        //         return false;
        //     });
        // } else {
            // Filtra por fecha de inicio si está presente en la solicitud
            if ($request->has('start_date')) {
                $dateQuery = $request->input('start_date');
                $formattedDate = date('Y-m-d', strtotime($dateQuery));
                $tasks->whereDate('start_date', $formattedDate);
            }
        // }
    
        // Aplica filtro de periodo de tiempo
        if ($request->has('period')) {
            $period = $request->input('period');
            $endDate = null;
    
            switch ($period) {
                case 'week':
                    $endDate = Carbon::today()->addDays(7);
                    break;
                case '2week':
                    $endDate = Carbon::today()->addDays(15);
                    break;
                case 'month':
                    $endDate = Carbon::today()->addMonth();
                    break;
                case 'year':
                    $endDate = Carbon::today()->endOfYear();
                    break;
                default:
                    $endDate = Carbon::today()->endOfYear();
                    break;
            }
    
            if ($endDate) {
                $tasks->whereBetween('start_date', [Carbon::today(), $endDate]);
            }
        }
    
        // Carga la relación con los usuarios y obtiene los resultados
        $tasks = $tasks->with('users')->get();
    
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
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'hour' => $request->input('hour'),
            'days' => json_encode($request->input('days')),
            'status' => $request->input('status') ?? 0,
        ]);
        $task->save();

        //Actualizar relaciones con los usuarios
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
        $task->start_date = $request->input('start_date');
        $task->end_date = $request->input('end_date');
        $task->hour = $request->input('hour');
        $task->days = json_encode($request->input('days'));
        $task->status = $request->input('status') ?? 0;
        $task->save();

        // Eliminar todas las relaciones actuales con usuarios
        $task->users()->detach();

        //Actualizar nuevas relaciones
        foreach ($request['users'] as $user) {
            $task->users()->attach($user['id']);
        }

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