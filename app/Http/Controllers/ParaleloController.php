<?php

namespace App\Http\Controllers;

use App\Models\CicloBimestral;
use App\Models\Modulo;
use App\Models\Paralelo;
use Illuminate\Http\Request;

class ParaleloController extends Controller
{
    public function index(Request $request)
    {
        $ciclos = CicloBimestral::orderBy('gestion', 'desc')->orderBy('id', 'desc')->get();

        // Determinar ciclo activo o seleccionado
        $cicloSeleccionadoId = $request->input('ciclo_id');
        if (!$cicloSeleccionadoId) {
            $cicloActivo = $ciclos->firstWhere('estado', 'ACTIVO') ?? $ciclos->first();
            $cicloSeleccionadoId = $cicloActivo ? $cicloActivo->id : null;
        }

        $query = Paralelo::with(['modulo.idioma', 'ciclo', 'asignacion']);
        if ($cicloSeleccionadoId) {
            $query->where('ciclo_id', $cicloSeleccionadoId);
        }
        $paralelos = $query->get();

        $modulos = Modulo::with('idioma')->orderBy('idioma_id')->orderBy('numero_orden')->get();

        return view('admin.paralelos.index', compact('paralelos', 'ciclos', 'cicloSeleccionadoId', 'modulos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'        => 'required|string|max:10',
            'ciclo_id'      => 'required|exists:ciclos_bimestrales,id',
            'modulo_id'     => 'required|exists:modulos,id',
            'modalidad'     => 'required|in:PRESENCIAL,VIRTUAL',
            'aula_o_enlace' => 'required|string|max:255',
            'turno'         => 'required|in:MAÑANA,TARDE,NOCHE',
            'hora_inicio'   => 'nullable',
            'hora_fin'      => 'nullable',
            'cupo_maximo'   => 'required|integer|min:1|max:150',
        ]);

        Paralelo::create([
            'nombre'        => strtoupper(trim($request->nombre)),
            'ciclo_id'      => $request->ciclo_id,
            'modulo_id'     => $request->modulo_id,
            'modalidad'     => $request->modalidad,
            'aula_o_enlace' => trim($request->aula_o_enlace),
            'turno'         => $request->turno,
            'hora_inicio'   => $request->hora_inicio,
            'hora_fin'      => $request->hora_fin,
            'cupo_maximo'   => (int)$request->cupo_maximo,
            'cupo_ocupado'  => 0,
        ]);

        return redirect()->route('admin.paralelos.index', ['ciclo_id' => $request->ciclo_id])
            ->with('mensaje', 'Grupo/Paralelo creado exitosamente.')
            ->with('icono', 'success');
    }

    public function update(Request $request, string $id)
    {
        $paralelo = Paralelo::findOrFail($id);

        $request->validate([
            'nombre'        => 'required|string|max:10',
            'ciclo_id'      => 'required|exists:ciclos_bimestrales,id',
            'modulo_id'     => 'required|exists:modulos,id',
            'modalidad'     => 'required|in:PRESENCIAL,VIRTUAL',
            'aula_o_enlace' => 'required|string|max:255',
            'turno'         => 'required|in:MAÑANA,TARDE,NOCHE',
            'hora_inicio'   => 'nullable',
            'hora_fin'      => 'nullable',
            'cupo_maximo'   => 'required|integer|min:1|max:150',
        ]);

        $paralelo->update([
            'nombre'        => strtoupper(trim($request->nombre)),
            'ciclo_id'      => $request->ciclo_id,
            'modulo_id'     => $request->modulo_id,
            'modalidad'     => $request->modalidad,
            'aula_o_enlace' => trim($request->aula_o_enlace),
            'turno'         => $request->turno,
            'hora_inicio'   => $request->hora_inicio,
            'hora_fin'      => $request->hora_fin,
            'cupo_maximo'   => (int)$request->cupo_maximo,
        ]);

        return redirect()->route('admin.paralelos.index', ['ciclo_id' => $request->ciclo_id])
            ->with('mensaje', 'Grupo/Paralelo actualizado correctamente.')
            ->with('icono', 'success');
    }

    public function destroy(string $id)
    {
        $paralelo = Paralelo::findOrFail($id);

        if ($paralelo->cupo_ocupado > 0) {
            return redirect()->back()
                ->with('mensaje', 'No se puede eliminar el grupo porque ya tiene alumnos inscritos.')
                ->with('icono', 'error');
        }

        $cicloId = $paralelo->ciclo_id;
        $paralelo->delete();

        return redirect()->route('admin.paralelos.index', ['ciclo_id' => $cicloId])
            ->with('mensaje', 'Grupo/Paralelo eliminado correctamente.')
            ->with('icono', 'success');
    }
}
