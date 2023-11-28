<?php

namespace App\Http\Controllers;

use App\Models\LostObject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LostObjectController extends BaseController
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Obtiene todos los Objeto perdidos
        $lostobjects = LostObject::all();

        // Retorna una respuesta con los Objeto perdidos encontrados
        return $this->sendResponse($lostobjects, 'Objeto perdidos encontrados exitosamente');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $id la ID dla Objeto perdido.
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Busca un Objeto perdido por su ID
        $lostObjects = LostObject::find($id);

        if (!$lostObjects) {
            // Retorna un error si la Objeto perdido no se encuentra
            return $this->sendError('Error de validaciones.', 'Objeto perdido no encontrado');
        }
        // Retorna una respuesta con la Objeto perdido encontrado
        return $this->sendResponse($lostObjects, 'Objeto perdido encontrado exitosamente');
    }

    /*
    * Mesagges Validator
    */
    protected function messages()
    {
        return [
            'register.string' => 'El campo registrador debe ser una cadena de caracteres.',
            'register.max' => 'El campo registrador no puede tener más de 150 caracteres.',
            'register.required' => 'El Registrador es obligatorio.',
            'date.required' => 'La fecha es obligatoria.',
            'date.date' => 'El formato de fecha no es válido.',
            'bus.string' => 'El campo bus debe ser una cadena de caracteres.',
            'bus.max' => 'El campo bus no puede tener más de 100 caracteres.',
            'line.string' => 'El campo línea debe ser una cadena de caracteres.',
            'line.max' => 'El campo línea no puede tener más de 100 caracteres.',
            'driver.string' => 'El campo conductor debe ser una cadena de caracteres.',
            'driver.max' => 'El campo conductor no puede tener más de 100 caracteres.',
            'description.string' => 'El campo descripción debe ser una cadena de caracteres.',
            'description.max' => 'El campo descripción no puede tener más de 250 caracteres.',
            'delivered_by.string' => 'El campo entregado por debe ser una cadena de caracteres.',
            'delivered_by.max' => 'El campo entregado por no puede tener más de 150 caracteres.',
            'reception_by.string' => 'El campo recibido por debe ser una cadena de caracteres.',
            'reception_by.max' => 'El campo recibido por no puede tener más de 150 caracteres.',
            'reception_date.date' => 'El formato de fecha entregada no es válido.',
            'destination.string' => 'El campo destino debe ser una cadena de caracteres.',
            'destination.max' => 'El campo destino no puede tener más de 150 caracteres.',
            'destination_date.date' => 'El formato de fecha de destino no es válido.',
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
            'register' => 'required|string|max:150',
            'date' => 'required|date',
            'bus' => 'nullable|string|max:100',
            'line' => 'nullable|string|max:100',
            'driver' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:255',
            'delivered_by' => 'nullable|string|max:150',
            'reception_by' => 'nullable|string|max:150',
            'reception_date' => 'nullable|date',
            'destination' => 'nullable|string|max:150',
            'destination_date' => 'nullable|date',
        ], $messages);

        if ($validator->fails()) {
            // Retorna un error si la validación falla
            return $this->sendError('Error de validaciones.', $validator->errors());
        }

        $currentYear = Carbon::now()->year;
        $countObjects = LostObject::whereYear('created_at', $currentYear)->count() + 1; //Contador cuantos hay y sumarle 1 para aumentar
        $code = $countObjects .'-'.$currentYear;

        $data = $request->all();
        $data = array_map('strtoupper', $data); //Todos los strings en Mayuscula

        //Crear Isuue y asignarle valores
        $lostObjects = new LostObject($data);
        $lostObjects->code = $code;
        $lostObjects->reception_date = $request->reception_date ?? null;
        $lostObjects->destination_date = $request->destination_date ?? null;
        $lostObjects->save();

        // // Retorna una respuesta con la LostObject creado
        return $this->sendResponse($lostObjects, 'Objeto perdido creado exitosamente');
    }

	/**
	 * Update the specified resource in storage.
	 *
	* @param \Illuminate\Http\Request $request La solicitud HTTP.
	* @param int $id la ID de la LostObject a actualizar.
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

		// Busca la LostObject por su ID
		$lostObjects = LostObject::find($id);

		if ($validator->fails()) {
			// Retorna un error si la validación falla
			return $this->sendError('Error de validaciones.', $validator->errors());
		}

		if (!$lostObjects) {
			// Retorna un error si la LostObject no se encuentra
			return $this->sendError('Error de validaciones.', 'Objeto perdido no encontrado');
		}

		// Convierte los valores a mayúsculas antes de actualizar la LostObject
		$data = $request->all();
		$data = array_map('strtoupper', $data);

		// Obtener los tiempos de aviso y solución en formato Carbon
        $notice_time = Carbon::createFromFormat('H:i', $data['notice_time']);
        $solution_time = Carbon::createFromFormat('H:i', $data['solution_time']);
        
			// Calcular la diferencia de tiempo
        	if ($solution_time > $notice_time) {
				$timeDifference = $solution_time->diff($notice_time);
				$lostObjects->time = $timeDifference->format('%H:%I');    //Asignar la diferencia de hora
			} 

		// Actualiza la LostObject con los datos proporcionados
		$lostObjects->update($data);

		// Retorna una respuesta con la LostObject actualizada
		return $this->sendResponse($lostObjects->refresh(), 'Objeto perdido actualizado exitosamente');
	}

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id de la Objeto perdido a eliminar.
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Busca la Objeto perdido por su ID
        $lostObjects = LostObject::find($id);

        if (!$lostObjects) {
            // Retorna un error si la Objeto perdido no se encuentra
            return $this->sendError('Error de validaciones.', 'Objeto perdido no encontrado');
        }

        // Elimina la Objeto perdido de la base de datos
        $lostObjects->delete();

        // Retorna una respuesta con la Objeto perdido eliminado
        return $this->sendResponse($lostObjects, 'Objeto perdido eliminado exitosamente');
    }
}
