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

        // Eliminar todas las relaciones actuales con usuarios
        $task->users()->detach();

        $task->delete();

        return $this->sendResponse(null, 'Tarea eliminada exitosamente.');
    }
 
    /**
     * Display a listing of the resource.
     */
    public function get_tasks_today(Request $request)
    {
        $today = Carbon::today();
        $user = $request->user(); // Usuario logeado

        // Obtiene tareas relacionadas con el usuario logeado
        $tasks = Task::whereDate('start_date', '<=', $today) //Aseguro que sea solo fechas anteriores a la seleccionada
                    ->whereHas('users', function ($q) use ($user) {
                        $q->where('users.id', $user->id); 
                    });
        $tasks = $tasks->get();
        
        $filteredTasks = $tasks->filter(function ($task) use ($request, $today) {

            if ($task->repeat) { //Si tiene para repetirse
                // Verifica si hoy es un día válido para la tarea repetitiva
                return $this->is_task_today($task, $today);
            } else { 
                // Si la tarea no se repite, incluirla solo si la fecha de inicio coincide con la fecha solicitada
                return $task->start_date == $today->toDateString(); //Cambio el formato de datetime y solo dejo date
            }
        });

        // Devolver las tareas filtradas
        return $filteredTasks->values();
    }

    /**
     * Check if a task should repeat on the given day.
     */
    private function is_task_today($task, $today)
    {
        $daysOfWeek = json_decode($task->days, true);
        $period = $task->period;
        $endDate = null;
    
        switch ($period) {
            case 'week':
                $endDate = Carbon::today()->addDays(7);
                break;
            case '2weeks':
                $endDate = Carbon::today()->addDays(15);
                break;
            case 'month':
                $endDate = Carbon::today()->addMonth();
                break;
            case 'year':
                $endDate = Carbon::today()->addYear();
                break;
            default:
                $endDate = Carbon::today();
                break;
        }
    
        // Iterate over the date range from today to endDate
        for ($date = $today->copy(); $date->lte($endDate); $date->addDay()) {
            $dayName = $date->format('l'); // Day name in English: 'Monday', 'Tuesday', etc.
    
            if (!empty($daysOfWeek[$dayName])) {
                if ($daysOfWeek[$dayName]) {
                    // Check if today is one of the valid days
                    if ($date->isSameDay($today)) {
                        return true;
                    }
                }
            }
        }
    
        return false;
    }

    /**
     * Display a listing of the resource.
     */
    public function get_tasks_week(Request $request)
    {
        // Usuario logeado
        $user = $request->user();

        // Obtiene tareas relacionadas con el usuario logeado
        $tasks = Task::whereHas('users', function ($q) use ($user) {
                        $q->where('users.id', $user->id); 
                    })->get();

        $tomorrow = Carbon::tomorrow();
        $week =  Carbon::today()->addWeek();
                
        $filteredTasks = $tasks->filter(function ($task) use ($tomorrow, $week) {

            if ($task->repeat) { //Si tiene para repetirse
                return $this->its_in_range($task, $tomorrow, $week);
            } else { 
                $taskStartDate = Carbon::parse($task->start_date);
                return $taskStartDate->between($tomorrow, $week); //Entre mañana y una semana 
            }
        });

        // Devolver las tareas filtradas
        return $filteredTasks->values();
    }

    /**
     * Display a listing of the resource.
     */
    public function get_tasks_two_weeks(Request $request)
    {
        // Usuario logeado
        $user = $request->user(); 

        // Obtiene tareas relacionadas con el usuario logeado
        $tasks = Task::whereHas('users', function ($q) use ($user) {
                        $q->where('users.id', $user->id); 
                    })->get();
             
        $week =  Carbon::today()->addWeek();
        $two_week =  Carbon::today()->addWeeks(2);

        $filteredTasks = $tasks->filter(function ($task) use ($week, $two_week) {
            if ($task->repeat) { //Si tiene para repetirse
                return $this->its_in_range($task, $week, $two_week); //Se envia la tarea y la fecha final que se recorrera
            } else { 
                $taskStartDate = Carbon::parse($task->start_date);
                return $taskStartDate->between($week, $two_week) ? true : false; //Entre mañana y 2 semanas
            }
        });

        // Devolver las tareas filtradas
        return $filteredTasks->values();
    }

    /**
     * Display a listing of the resource.
     */
    public function get_tasks_month(Request $request)
    {
        // Usuario logeado
        $user = $request->user(); 

        // Obtiene tareas relacionadas con el usuario logeado
        $tasks = Task::whereHas('users', function ($q) use ($user) {
                        $q->where('users.id', $user->id); 
                    })->get();
             
        $two_week =  Carbon::today()->addWeeks(2);
        $month =  Carbon::today()->addMonth();

        $filteredTasks = $tasks->filter(function ($task) use ($two_week,  $month) {
            if ($task->repeat) { //Si tiene para repetirse
                return $this->its_in_range($task, $two_week,  $month); //Se envia la tarea y la fecha final que se recorrera
            } else { 
                $taskStartDate = Carbon::parse($task->start_date);
                return $taskStartDate->between($two_week,  $month) ? true : false; //Entre mañana y 2 semanas
            }
        });

        // Devolver las tareas filtradas
        return $filteredTasks->values();
    }

    /**
     * Display a listing of the resource.
     */
    public function get_tasks_year(Request $request)
    {
        // Usuario logeado
        $user = $request->user(); 

        // Obtiene tareas relacionadas con el usuario logeado
        $tasks = Task::whereHas('users', function ($q) use ($user) {
                        $q->where('users.id', $user->id); 
                    })->get();
             
        $month =  Carbon::today()->addMonth();
        $year =  Carbon::today()->addYear();

        $filteredTasks = $tasks->filter(function ($task) use ($month,  $year) {
            if ($task->repeat) { //Si tiene para repetirse
                return $this->its_in_range($task, $month,  $year); //Se envia la tarea y la fecha final que se recorrera
            } else { 
                $taskStartDate = Carbon::parse($task->start_date);
                return $taskStartDate->between($month,  $year) ? true : false; //Entre mañana y 2 semanas
            }
        });

        // Devolver las tareas filtradas
        return $filteredTasks->values();
    }


    /**
     * Check if a task should repeat on the given day.
     */
    private function its_in_range($task, $start_date, $out_date)
    {
        $period = $task->period;
        $period_start_date = Carbon::parse($task->start_date);
        $period_end_date = null;
    
        // Sumar el periodo y obtener la fecha fin
        switch ($period) {
            case 'week':
                $period_end_date = $period_start_date->copy()->addWeek();
                break;
    
            case '2weeks':
                $period_end_date = $period_start_date->copy()->addWeeks(2); // addWeeks en plural para 2 semanas
                break;
    
            case 'month':
                $period_end_date = $period_start_date->copy()->addMonth();
                break;
    
            case 'year':
                $period_end_date = $period_start_date->copy()->addYear();
                break;
    
            default:
                $period_end_date = $period_start_date;
                break;
        }
    
        $in_range = false; // Variable que retornara si esta en TRUE y sino esta en false
        $day = $start_date->copy(); // Fecha de donde comienza

        // Recorrer la fecha hasta que llegue a la fecha $out_date
        while ($day->lte($out_date)) {
    
            // Verificar si el día está dentro del rango del periodo
            if ($day->between($period_start_date, $period_end_date)) {
                $in_range = true;
                break; // Romper el ciclo si ya se encontró que está en el rango
            }
        
            $day->addDay(); // Avanzar al próximo día
        }
    
        return $in_range; 
    }
}