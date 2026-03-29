<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\Matriculacion;
use Illuminate\Http\Request;
use App\Models\Turno;
use App\Models\Gestion;
use App\Models\Nivel;
use App\Models\Grado;
use App\Models\Paralelo;
use App\Models\Ppff;

class MatriculacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matriculaciones = Matriculacion::all();
        return view('admin.matriculaciones.index', compact('matriculaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $turnos = Turno::all();
        $gestiones = Gestion::all();
        $niveles = Nivel::all();
        $grados = Grado::all();
        $paralelos = Paralelo::all();
        $estudiantes = Estudiante::all();

        return view('admin.matriculaciones.create', compact('estudiantes', 'turnos', 'gestiones', 'niveles', 'grados', 'paralelos'));
    }

    public function buscar_estudiante($id)
    {
        $estudiante = Estudiante::with('usuario', 'matriculaciones.turno', 'matriculaciones.gestion', 'matriculaciones.nivel', 'matriculaciones.grado', 'matriculaciones.paralelo')->find($id);

        if (!$estudiante) {
            return response()->json(['error', 'Estudiante no encontrado']);
        }


        $estudiante->foto_url = url($estudiante->foto);
        return response()->json($estudiante);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Matriculacion $matriculacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Matriculacion $matriculacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Matriculacion $matriculacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Matriculacion $matriculacion)
    {
        //
    }
}
