<?php

namespace App\Http\Controllers;

use App\Models\Ppff;
use Illuminate\Http\Request;

class PpffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validación de los campos obligatorios
        $request->validate([
            'nombres'          => 'required',
            'apellidos'        => 'required',
            'ci'               => 'required',
            'fecha_nacimiento' => 'required',
            'telefono'         => 'required',
            'parentesco'       => 'required',
            'ocupacion'        => 'required',
            'direccion'        => 'required',
        ]);

        // 2. Creación del nuevo objeto y asignación de datos
        $ppff = new Ppff();
        $ppff->nombres          = $request->nombres;
        $ppff->apellidos        = $request->apellidos;
        $ppff->ci               = $request->ci;
        $ppff->fecha_nacimiento = $request->fecha_nacimiento;
        $ppff->telefono         = $request->telefono;
        $ppff->parentesco       = $request->parentesco;
        $ppff->ocupacion        = $request->ocupacion;
        $ppff->direccion        = $request->direccion;

        // 3. Guardar en la base de datos
        $ppff->save();

        // 4. Redirección con mensaje de éxito (usando SweetAlert o notificaciones de AdminLTE)
        return redirect()->back()
            ->with('mensaje', 'Se registro al padre de familia correctamente')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ppff $ppff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ppff $ppff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ppff $ppff)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ppff $ppff)
    {
        //
    }
}
