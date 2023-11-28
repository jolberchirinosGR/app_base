<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IssueController extends BaseController
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtiene todos los Incidencias
        $issues = Issue::all();

        // Retorna una respuesta con los Incidencias encontrados
        return $this->sendResponse($issues, 'Incidencias encontrados exitosamente');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $id la ID dla Incidencia.
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Busca un Incidencia por su ID
        $issue = Issue::find($id);

        if (!$issue) {
            // Retorna un error si la Incidencia no se encuentra
            return $this->sendError('Error de validaciones.', 'Incidencia no encontrado');
        }
        // Retorna una respuesta con la Incidencia encontrado
        return $this->sendResponse($issue, 'Incidencia encontrado exitosamente');
    }

    /*
    * Mesagges Validator
    */
    protected function messages()
    {
        return [
            'date.required' => 'La fecha es obligatoria.',
            'date.date' => 'El formato de fecha no es válido.',
            'company.required' => 'El campo empresa es obligatorio.',
            'category.required' => 'El campo categoría es obligatorio.',
            'bus.required' => 'El campo bus es obligatorio.',
            'line.required' => 'El campo línea es obligatorio.',
            'driver.required' => 'El campo conductor es obligatorio.',
            'employee.required' => 'El campo empleado es obligatorio.',
            'employee.max' => 'El campo empleado no puede tener más de 5 caracteres.',
            'hour.required' => 'El campo hora es obligatorio.',
            'hour.date_format' => 'El formato de hora no es válido.',
            'action.required' => 'El campo acción es obligatorio.',
            'notice_time.required' => 'El campo hora de aviso es obligatorio.',
            'notice_time.date_format' => 'El formato de hora de aviso no es válido.',
            'change_bus.required' => 'El campo cambio de bus es obligatorio.',
            'solution_time.required' => 'El campo hora de solución es obligatorio.',
            'solution_time.date_format' => 'El formato de hira de solución no es válido.',
            'description.required' => 'El campo descripción es obligatorio.',
            'description.max' => 'La descripción no puede tener más de 255 caracteres.',
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request La solicitud HTTP.
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = $this->messages();// Mensajes del validadors

        //Validador
        $validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'company' => 'required|string|max:150',
            'category' => 'nullable|string|max:150',
            'bus' => 'nullable|string|max:100',
            'line' => 'nullable|string|max:100',
            'driver' => 'nullable|string|max:100',
            'employee' => 'nullable|string|max:150',
            'hour' => 'nullable|date_format:H:i', // Regla para validar formato de hora
            'action' => 'nullable|string|max:250',
            'notice_time' => 'nullable|date_format:H:i', // Regla para validar formato de tiempo
            'change_bus' => 'nullable|string|max:20',
            'solution_time' => 'nullable|date_format:H:i', // Regla para validar formato de tiempo
            'description' => 'nullable|string|max:255',
        ], $messages);

        if ($validator->fails()) {
            // Retorna un error si la validación falla
            return $this->sendError('Error de validaciones.', $validator->errors());
        }
        $data = $request->all();
        $data = array_map('strtoupper', $data); //Todos los strings en Mayuscula

        // Obtener los tiempos de aviso y solución en formato Carbon
        $notice_time = Carbon::createFromFormat('H:i', $data['notice_time']);
        $solution_time = Carbon::createFromFormat('H:i', $data['solution_time']);

    
        //Crear Isuue y asignarle valores
        $issue = new Issue($data);
		    // Calcular la diferencia de tiempo
        	if ($solution_time > $notice_time) {
				$timeDifference = $solution_time->diff($notice_time);
				$issue->time = $timeDifference->format('%H:%I');    //Asignar la diferencia de hora
			} 
        $issue->save();

        // // Retorna una respuesta con la Issue creado
        return $this->sendResponse($issue, 'Incidencia creado exitosamente');
    }

	/**
	 * Update the specified resource in storage.
	 *
	* @param \Illuminate\Http\Request $request La solicitud HTTP.
	* @param int $id la ID de la Issue a actualizar.
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, $id)
	{
		$messages = $this->messages();// Mensajes del validadors

		$validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'company' => 'required|string|max:150',
            'category' => 'nullable|string|max:150',
            'bus' => 'nullable|string|max:100',
            'line' => 'nullable|string|max:100',
            'driver' => 'nullable|string|max:100',
            'employee' => 'nullable|string|max:150',
            'hour' => 'nullable|date_format:H:i', // Regla para validar formato de hora
            'action' => 'nullable|string|max:250',
            'notice_time' => 'nullable|date_format:H:i', // Regla para validar formato de tiempo
            'change_bus' => 'nullable|string|max:100',
            'solution_time' => 'nullable|date_format:H:i', // Regla para validar formato de tiempo
            'description' => 'nullable|string|max:255',
        ], $messages);

		// Busca la Issue por su ID
		$issue = Issue::find($id);

		if ($validator->fails()) {
			// Retorna un error si la validación falla
			return $this->sendError('Error de validaciones.', $validator->errors());
		}

		if (!$issue) {
			// Retorna un error si la Issue no se encuentra
			return $this->sendError('Error de validaciones.', 'Incidencia no encontrado');
		}

		// Convierte los valores a mayúsculas antes de actualizar la Issue
		$data = $request->all();
		$data = array_map('strtoupper', $data);

		// Obtener los tiempos de aviso y solución en formato Carbon
        $notice_time = Carbon::createFromFormat('H:i', $data['notice_time']);
        $solution_time = Carbon::createFromFormat('H:i', $data['solution_time']);
        
			// Calcular la diferencia de tiempo
        	if ($solution_time > $notice_time) {
				$timeDifference = $solution_time->diff($notice_time);
				$issue->time = $timeDifference->format('%H:%I');    //Asignar la diferencia de hora
			} 

		// Actualiza la Issue con los datos proporcionados
		$issue->update($data);

		// Retorna una respuesta con la Issue actualizada
		return $this->sendResponse($issue->refresh(), 'Incidencia actualizado exitosamente');
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id de la Incidencia a eliminar.
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Busca la Incidencia por su ID
        $issue = Issue::find($id);

        if (!$issue) {
            // Retorna un error si la Incidencia no se encuentra
            return $this->sendError('Error de validaciones.', 'Incidencia no encontrado');
        }

        // Elimina la Incidencia de la base de datos
        $issue->delete();

        // Retorna una respuesta con la Incidencia eliminado
        return $this->sendResponse($issue, 'Incidencia eliminado exitosamente');
    }
}
