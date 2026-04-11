<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use Illuminate\Http\Request;
use App\Models\Personal;
use App\Models\Estudiante;
use App\Models\Matriculacion;
use App\Models\Asignacion;
use App\Models\Periodo;
use App\Models\DetalleCalificacion;

use Illuminate\Support\Facades\Auth; //este siosi para los auth

class CalificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rol = Auth::user()->roles->pluck('name')->implode(', ');
        $id_usuario = Auth::user()->id;

        if (($rol == 'ADMINISTRADOR') || ($rol == 'DIRECTOR/A GENERAL') || ($rol == 'DIRECTOR/A ACADÉMICO') || ($rol == 'SECRETARIO/A') || ($rol == 'REGENTE')) {
            //   $asignaciones = Asignacion::all();
            //   return view('admin.asistencias.index', compact('asignaciones'));
        }

        if ($rol == 'DOCENTE') {
            $docente = Personal::where('user_id', $id_usuario)->first();
            $asignaciones = Asignacion::where('personal_id', $docente->id)->get();
            return view('admin.calificaciones.index_docente', compact('docente', 'asignaciones'));
        }

        /*  if ($rol == 'ESTUDIANTE') {
            $estudiante = Estudiante::where('usuario_id', $id_usuario)->first();
            $matriculas = Matriculacion::where('estudiante_id', $estudiante->id)->get();

            $asignaciones = collect();

            foreach ($matriculas as $matricula) {
                $datos = Asignacion::with('personal', 'materia')
                    ->where('turno_id', $matricula->turno_id)
                    ->where('gestion_id', $matricula->gestion_id)
                    ->where('nivel_id', $matricula->nivel_id)
                    ->where('grado_id', $matricula->grado_id)
                    ->where('paralelo_id', $matricula->paralelo_id)
                    ->get();
                $asignaciones = $asignaciones->merge($datos);
            }

            return view('admin.asistencias.index_estudiante', compact('estudiante', 'matriculas', 'asignaciones'));
        }*/
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $asignacion = Asignacion::find($id);
        $docente = Personal::where('user_id', Auth::user()->id)->first();

        $periodos = Periodo::where('gestion_id', $asignacion->gestion_id)
            ->orderBy('id')
            ->get();

        $matriculados = Matriculacion::with('estudiante')
            ->where('turno_id', $asignacion->turno_id)
            ->where('gestion_id', $asignacion->gestion_id)
            ->where('nivel_id', $asignacion->nivel_id)
            ->where('grado_id', $asignacion->grado_id)
            ->where('paralelo_id', $asignacion->paralelo_id)
            ->get()
            ->sortBy('estudiante.apellidos');

        $calificaciones = Calificacion::with('detalleCalificaciones')->where('asignacion_id', $asignacion->id)->get();




        return view('admin.calificaciones.create', compact('asignacion', 'docente', 'periodos', 'matriculados', 'calificaciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'asignacion_id' => 'required',
            'periodo_id' => 'required',
            'tipo' => 'required',
            'fecha' => 'required',
            'nota' => 'required',
        ]);

        $calificacion = new Calificacion();
        $calificacion->asignacion_id = $request->asignacion_id;
        $calificacion->periodo_id = $request->periodo_id;
        $calificacion->tipo = $request->tipo;
        $calificacion->fecha = $request->fecha;
        $calificacion->descripcion = $request->descripcion;
        $calificacion->save();

        foreach ($request->nota as $estudiante_id => $nota) {
            DetalleCalificacion::create([
                'calificacion_id' => $calificacion->id,
                'estudiante_id' => $estudiante_id,
                'nota' => $nota,
            ]);
        }

        return redirect()->back()
            ->with('mensaje', 'Se registro la calificación correctamente')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Calificacion $calificacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Calificacion $calificacion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Calificacion $calificacion)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Calificacion $calificacion)
    {
        //
    }
}
