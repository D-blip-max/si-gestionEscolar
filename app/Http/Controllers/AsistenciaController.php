<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; //este siosi para los auth
use App\Models\Personal;
use App\Models\Asignacion;
use App\Models\Matriculacion;
use App\Models\DetalleAsistencia;
use App\Models\Estudiante;


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
            $asignaciones = Asignacion::all();
            return view('admin.asistencias.index', compact('asignaciones'));
        }

        if ($rol == 'DOCENTE') {
            $docente = Personal::where('user_id', $id_usuario)->first();
            $asignaciones = Asignacion::where('personal_id', $docente->id)->get();
            return view('admin.asistencias.index_docente', compact('docente', 'asignaciones'));
        }

        if ($rol == 'ESTUDIANTE') {
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
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $asignacion = Asignacion::findOrFail($id);
        $docente = Personal::where('user_id', Auth::user()->id)->first();
        $asistencias = Asistencia::with('detalleAsistencias')->where('asignacion_id', $id)->get();
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
        //return response()->json($datos);
        $request->validate([
            'asignacion_id' => 'required',
            'fecha' => 'required|date',
            'observacion' => 'nullable|string|max:255',
            'estado_asistencia' => 'required',
        ]);

        $asistencia = new Asistencia();
        $asistencia->asignacion_id = $request->asignacion_id;
        $asistencia->fecha = $request->fecha;
        $asistencia->observacion = $request->observacion;
        $asistencia->save();

        $estado_asistencia = $request->estado_asistencia;

        foreach ($estado_asistencia as $estudiante_id => $estado) {
            DetalleAsistencia::create([
                'asistencia_id' => $asistencia->id,
                'estudiante_id' => $estudiante_id,
                'estado_asistencia' => $estado,
            ]);
        }

        return redirect()->back()
            ->with('mensaje', 'Se registro la asistencia de la manera correctamente')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show_estudiante($id_asignacion, $id_estudiante)
    {
        $asignacion = Asignacion::findOrFail($id_asignacion);
        $estudiante = Estudiante::findOrFail($id_estudiante);

        $asistencias = Asistencia::with(['detalleAsistencias' => function ($query) use ($id_estudiante) {
            $query->where('estudiante_id', $id_estudiante);
        }])
            ->where('asignacion_id', $id_asignacion)
            ->orderBy('fecha', 'asc') // Ordenar por fecha de forma descendente
            ->get();

        return view('admin.asistencias.show_estudiante', compact('asistencias', 'asignacion', 'estudiante'));
    }

    public function show($id)
    {
        $asignacion = Asignacion::findOrFail($id);

        $asistencias = Asistencia::with('detalleAsistencias.estudiante')
            ->where('asignacion_id', $id)
            ->orderBy('fecha', 'desc')->get(); // Ordenar por fecha de forma descendente

        $estudiantes = $asistencias->flatMap(function ($asistencia) {
            return $asistencia->detalleAsistencias->pluck('estudiante')->filter();
        })->unique('id')->sortBy('apellidos');

        $fechas = $asistencias->pluck('fecha')->unique()->sort();

        return view('admin.asistencias.show', compact('asistencias', 'asignacion', 'estudiantes', 'fechas'));
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
    public function update(Request $request,  $id)
    {
        //return response()->json($datos);
        $request->validate([
            'asignacion_id' => 'required',
            'fecha' => 'required|date',
            'observacion' => 'nullable|string|max:255',
            'estado_asistencia' => 'required',
        ]);

        $asistencia = Asistencia::findOrFail($id);
        $asistencia->asignacion_id = $request->asignacion_id;
        $asistencia->fecha = $request->fecha;
        $asistencia->observacion = $request->observacion;
        $asistencia->save();

        $estado_asistencia = $request->estado_asistencia;

        foreach ($estado_asistencia as $estudiante_id => $estado) {
            DetalleAsistencia::where('asistencia_id', $asistencia->id)
                ->where('estudiante_id', $estudiante_id)
                ->update(['estado_asistencia' => $estado]);
        }

        return redirect()->back()
            ->with('mensaje', 'Se actualizo la asistencia de la manera correctamente')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $asistencia = Asistencia::findOrFail($id);
        $asistencia->detalleAsistencias()->delete(); // Eliminar los detalles de asistencia asociados
        $asistencia->delete(); // Eliminar la asistencia

        return redirect()->back()
            ->with('mensaje', 'Se elimino la asistencia de la manera correctamente')
            ->with('icono', 'success');
    }
}
