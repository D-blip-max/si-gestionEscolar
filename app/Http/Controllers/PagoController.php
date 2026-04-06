<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use Illuminate\Http\Request;
use App\Models\Estudiante;
use App\Models\Matriculacion;

class PagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pagos = Pago::all();
        $estudiantes = Estudiante::all();
        return view('admin.pagos.index', compact('pagos', 'estudiantes'));
    }

    public function ver_pagos($id)
    {
        $estudiante = Estudiante::find($id);
        $matriculas = Matriculacion::where('estudiante_id', $id)->get();
        return view('admin.pagos.ver_pagos', compact('matriculas', 'estudiante'));
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
        $datos= request()->all();
        return response()->json($datos);

    }

    /**
     * Display the specified resource.
     */
    public function show(Pago $pago)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pago $pago)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pago $pago)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pago $pago)
    {
        //
    }
}
