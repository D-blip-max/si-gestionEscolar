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
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Configuracion;
    

class MatriculacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matriculaciones = Matriculacion::with('estudiante', 'turno', 'gestion', 'nivel', 'grado', 'paralelo')->get();
        return view('admin.matriculaciones.index', compact('matriculaciones'));
    }

    /**
     * Show the form for creating a new resouce
     * ca
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
    public function buscar_grados($id)
    {
        $grados = Grado::where('nivel_id', $id)->pluck('nombre', 'id');

        if (!$grados) {
            return response()->json(['error', 'Grados no encontrados']);
        }

        return response()->json($grados);
    }
    public function buscar_paralelos($id)
    {
        $paralelos = Paralelo::where('grado_id', $id)->pluck('nombre', 'id');

        if (!$paralelos) {
            return response()->json(['error', 'Paralelos no encontrados']);
        }

        return response()->json($paralelos);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //  $datos= request()->all();
        //  return response()->json($datos);
        $request->validate([
            'estudiante_id'      => 'required',
            'turno_id'           => 'required',
            'gestion_id'         => 'required',
            'nivel_id'           => 'required',
            'grado_id'           => 'required',
            'paralelo_id'        => 'required',
            'fecha_matriculacion' => 'required',
        ]);

        //validacion para estudiantes ya matriculados
        $estudiante_duplicado = Matriculacion::where('estudiante_id', $request->estudiante_id)
            ->where('turno_id', $request->turno_id)
            ->where('gestion_id', $request->gestion_id)
            ->where('nivel_id', $request->nivel_id)
            ->where('grado_id', $request->grado_id)
            ->where('paralelo_id', $request->paralelo_id)
            ->exists();

        if ($estudiante_duplicado) {
            return redirect()->back()->with([
                'mensaje' => 'El estudiante ya esta matriculado',
                'icono' => 'error',
            ]);
        }

        $matriculacion = new Matriculacion();
        $matriculacion->estudiante_id = $request->estudiante_id;
        $matriculacion->turno_id = $request->turno_id;
        $matriculacion->gestion_id = $request->gestion_id;
        $matriculacion->nivel_id = $request->nivel_id;
        $matriculacion->grado_id = $request->grado_id;
        $matriculacion->paralelo_id = $request->paralelo_id;
        $matriculacion->fecha_matriculacion = $request->fecha_matriculacion;
        $matriculacion->save();

        return redirect()->route('admin.matriculaciones.index')
            ->with('mensaje', 'La matriculación se ha creado correctamente')
            ->with('icono', 'success');
    }




    public function pdf_matricula($id)
    {
        $configuracion = Configuracion::first();
        $matricula = Matriculacion::with('estudiante', 'turno', 'gestion', 'nivel', 'grado', 'paralelo')->find($id);
     //  return response()->json($configuracion);
        $pdf = PDF::loadView('admin.matriculaciones.pdf', compact('configuracion', 'matricula'));
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions(['defaultFont' => 'sans-serif']);
        $pdf->setOptions(['isHtml5ParserEnabled' => true]);
        $pdf->setOptions(['isRemoteEnabled' => true]);
        return $pdf->stream('matriculas.pdf');
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
