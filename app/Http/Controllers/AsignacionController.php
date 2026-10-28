<?php

namespace App\Http\Controllers;

use App\Models\Asignacion;
use App\Models\CicloBimestral;
use App\Models\Paralelo;
use App\Models\Personal;
use Illuminate\Http\Request;

class AsignacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asignaciones = Asignacion::with([
            'personal',
            'paralelo.modulo.idioma',
            'paralelo.ciclo',
            'turno',
            'gestion',
            'nivel',
            'grado',
            'materia'
        ])->orderBy('id', 'desc')->get();

        return view('admin.asignaciones.index', compact('asignaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $docentes = Personal::where('tipo', 'docente')->get();
        $ciclos = CicloBimestral::where('estado', '!=', 'CERRADO')->get();
        $paralelos = Paralelo::with(['modulo.idioma', 'ciclo'])
            ->orderBy('ciclo_id', 'desc')
            ->orderBy('modulo_id')
            ->get();

        return view('admin.asignaciones.create', compact('docentes', 'ciclos', 'paralelos'));
    }

    public function buscar_docente($id)
    {
        $docente = Personal::with(['usuario', 'formaciones'])->find($id);

        if (!$docente) {
            return response()->json(['error' => 'Docente no encontrado'], 404);
        }

        $docente->foto_url = ($docente->foto && file_exists(public_path($docente->foto)))
            ? asset($docente->foto)
            : asset('img/default-avatar.png');

        return response()->json($docente);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'personal_id'      => 'required|exists:personals,id',
            'paralelo_id'      => 'required|exists:paralelos,id',
            'fecha_asignacion' => 'required|date',
        ]);

        $paralelo = Paralelo::with('ciclo')->findOrFail($request->paralelo_id);

        // Regla 1: Un grupo solo puede tener un docente titular asignado
        $grupoYaAsignado = Asignacion::where('paralelo_id', $paralelo->id)
            ->where('estado', 'activo')
            ->exists();

        if ($grupoYaAsignado) {
            return redirect()->back()
                ->withInput()
                ->with('mensaje', 'El grupo seleccionado ya tiene un docente titular asignado.')
                ->with('icono', 'error');
        }

        // Regla 2: Prevención de choque de horario para el mismo docente
        if ($paralelo->ciclo_id && $paralelo->hora_inicio && $paralelo->hora_fin) {
            $choqueHorario = Asignacion::where('personal_id', $request->personal_id)
                ->where('estado', 'activo')
                ->whereHas('paralelo', function ($query) use ($paralelo) {
                    $query->where('ciclo_id', $paralelo->ciclo_id)
                        ->where('turno', $paralelo->turno)
                        ->where('hora_inicio', '<', $paralelo->hora_fin)
                        ->where('hora_fin', '>', $paralelo->hora_inicio);
                })->exists();

            if ($choqueHorario) {
                return redirect()->back()
                    ->withInput()
                    ->with('mensaje', 'Conflicto de Horario: El docente seleccionado ya tiene otro grupo asignado en ese mismo ciclo, turno y rango de horas.')
                    ->with('icono', 'error');
            }
        }

        Asignacion::create([
            'personal_id'      => $request->personal_id,
            'paralelo_id'      => $request->paralelo_id,
            'fecha_asignacion' => $request->fecha_asignacion,
            'estado'           => 'activo',
            'gestion_id'       => $request->gestion_id ?? null,
            'nivel_id'         => $request->nivel_id ?? null,
            'grado_id'         => $request->grado_id ?? null,
            'materia_id'       => $request->materia_id ?? null,
            'turno_id'         => $request->turno_id ?? null,
        ]);

        return redirect()->route('admin.asignaciones.index')
            ->with('mensaje', 'Asignación académica registrada exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $asignacion = Asignacion::with([
            'personal.usuario',
            'personal.formaciones',
            'paralelo.modulo.idioma',
            'paralelo.ciclo'
        ])->findOrFail($id);

        return view('admin.asignaciones.show', compact('asignacion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $asignacion = Asignacion::with(['personal', 'paralelo'])->findOrFail($id);
        $docentes = Personal::where('tipo', 'docente')->get();
        $paralelos = Paralelo::with(['modulo.idioma', 'ciclo'])->get();

        return view('admin.asignaciones.edit', compact('asignacion', 'docentes', 'paralelos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $asignacion = Asignacion::findOrFail($id);

        $request->validate([
            'personal_id'      => 'required|exists:personals,id',
            'paralelo_id'      => 'required|exists:paralelos,id',
            'fecha_asignacion' => 'required|date',
            'estado'           => 'required|in:activo,inactivo',
        ]);

        $paralelo = Paralelo::findOrFail($request->paralelo_id);

        // Verificar choque de grupo con otro registro
        $grupoYaAsignado = Asignacion::where('paralelo_id', $paralelo->id)
            ->where('id', '!=', $asignacion->id)
            ->where('estado', 'activo')
            ->exists();

        if ($grupoYaAsignado) {
            return redirect()->back()
                ->withInput()
                ->with('mensaje', 'El grupo seleccionado ya está asignado a otro docente.')
                ->with('icono', 'error');
        }

        if ($request->estado === 'activo' && $paralelo->hora_inicio && $paralelo->hora_fin) {
            $choque = Asignacion::where('personal_id', $request->personal_id)
                ->where('id', '!=', $asignacion->id)->where('estado', 'activo')
                ->whereHas('paralelo', fn ($q) => $q->where('ciclo_id', $paralelo->ciclo_id)
                    ->where('hora_inicio', '<', $paralelo->hora_fin)
                    ->where('hora_fin', '>', $paralelo->hora_inicio))->exists();
            if ($choque) {
                return back()->withInput()->with('mensaje', 'El docente ya tiene un grupo en ese horario.')->with('icono', 'error');
            }
        }

        $asignacion->update([
            'personal_id'      => $request->personal_id,
            'paralelo_id'      => $request->paralelo_id,
            'fecha_asignacion' => $request->fecha_asignacion,
            'estado'           => $request->estado,
        ]);

        return redirect()->route('admin.asignaciones.index')
            ->with('mensaje', 'Asignación académica actualizada correctamente.')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $asignacion = Asignacion::findOrFail($id);
        $asignacion->delete();

        return redirect()->route('admin.asignaciones.index')
            ->with('mensaje', 'Asignación académica eliminada exitosamente.')
            ->with('icono', 'success');
    }
}
