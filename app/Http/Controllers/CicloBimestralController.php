<?php

namespace App\Http\Controllers;

use App\Models\CicloBimestral;
use Illuminate\Http\Request;

class CicloBimestralController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:50',
            'gestion'      => 'required|integer|min:2020|max:2099',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin'    => 'nullable|date|after_or_equal:fecha_inicio',
            'estado'       => 'required|in:ACTIVO,CERRADO,FUTURO',
        ]);

        if ($request->estado === 'ACTIVO') {
            CicloBimestral::where('estado', 'ACTIVO')->update(['estado' => 'CERRADO']);
        }

        CicloBimestral::create([
            'nombre'       => trim($request->nombre),
            'gestion'      => (int)$request->gestion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin'    => $request->fecha_fin,
            'estado'       => $request->estado,
        ]);

        return redirect()->back()
            ->with('mensaje', 'Ciclo bimestral creado exitosamente.')
            ->with('icono', 'success');
    }

    public function update(Request $request, string $id)
    {
        $ciclo = CicloBimestral::findOrFail($id);

        $request->validate([
            'nombre'       => 'required|string|max:50',
            'gestion'      => 'required|integer|min:2020|max:2099',
            'fecha_inicio' => 'nullable|date',
            'fecha_fin'    => 'nullable|date|after_or_equal:fecha_inicio',
            'estado'       => 'required|in:ACTIVO,CERRADO,FUTURO',
        ]);

        if ($request->estado === 'ACTIVO' && $ciclo->estado !== 'ACTIVO') {
            CicloBimestral::where('estado', 'ACTIVO')->update(['estado' => 'CERRADO']);
        }

        $ciclo->update([
            'nombre'       => trim($request->nombre),
            'gestion'      => (int)$request->gestion,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin'    => $request->fecha_fin,
            'estado'       => $request->estado,
        ]);

        return redirect()->back()
            ->with('mensaje', 'Ciclo bimestral actualizado correctamente.')
            ->with('icono', 'success');
    }

    public function activar(string $id)
    {
        CicloBimestral::where('estado', 'ACTIVO')->update(['estado' => 'CERRADO']);
        $ciclo = CicloBimestral::findOrFail($id);
        $ciclo->update(['estado' => 'ACTIVO']);

        return redirect()->back()
            ->with('mensaje', 'El ciclo ' . $ciclo->nombre . ' ha sido activado como vigente.')
            ->with('icono', 'success');
    }

    public function destroy(string $id)
    {
        $ciclo = CicloBimestral::withCount('paralelos')->findOrFail($id);

        if ($ciclo->paralelos_count > 0) {
            return redirect()->back()
                ->with('mensaje', 'No se puede eliminar el ciclo porque tiene paralelos/grupos registrados.')
                ->with('icono', 'error');
        }

        $ciclo->delete();

        return redirect()->back()
            ->with('mensaje', 'Ciclo bimestral eliminado correctamente.')
            ->with('icono', 'success');
    }
}

