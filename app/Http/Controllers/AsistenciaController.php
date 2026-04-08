<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; //este siosi para los auth
use App\Models\Personal;
use App\Models\Asignacion;
use App\Models\Matriculacion;

class AsistenciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rol = Auth::user()->roles->pluck('name')->implode(', ');
        $id_usuario = Auth::user()->id;

        if (($rol == 'ADMINISTRADOR') || ($rol == 'DIRECTOR/A GENERAL') || ($rol == 'DIRECTOR/A ACADÉMICO') || ($rol == 'SECRETARIO/A') || ($rol == 'REGENTE')) {
            return view('admin.asistencias.index');
        }

        if ($rol == 'DOCENTE') {
            $docente = Personal::where('user_id', $id_usuario)->first();
            $asignaciones = Asignacion::where('personal_id', $docente->id)->get();
            return view('admin.asistencias.index_docente', compact('docente', 'asignaciones'));
        }

        if ($rol == 'ESTUDIANTE') {
            return view('admin.asistencias.index_estudiante');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $asignacion = Asignacion::findOrFail($id);
        $docente = Personal::where('user_id', Auth::user()->id)->first();
        $asistencias = Asistencia::where('asignacion_id', $id)->get();
        $matriculados = Matriculacion::with('estudiante')
            ->where('turno_id', $asignacion->turno_id)
            ->where('gestion_id', $asignacion->gestion_id)
            ->where('nivel_id', $asignacion->nivel_id)
            ->where('grado_id', $asignacion->grado_id)
            ->where('paralelo_id', $asignacion->paralelo_id)
            ->get()
            ->sortBy('estudiante.apellidos');

        return view('admin.asistencias.create', compact('asignacion', 'docente', 'asistencias', 'matriculados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $datos=request()->all();
        return response()->json($datos);
    }

    /**
     * Display the specified resource.
     */
    public function show(Asistencia $asistencia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Asistencia $asistencia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Asistencia $asistencia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Asistencia $asistencia)
    {
        //
    }
}
