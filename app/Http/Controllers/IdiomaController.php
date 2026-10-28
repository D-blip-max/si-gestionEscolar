<?php

namespace App\Http\Controllers;

use App\Models\Idioma;
use Illuminate\Http\Request;

class IdiomaController extends Controller
{
    public function index()
    {
        $idiomas = Idioma::withCount('modulos')->get();
        return view('admin.idiomas.index', compact('idiomas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:100|unique:idiomas,nombre',
            'codigo'      => 'required|string|max:10|unique:idiomas,codigo',
            'descripcion' => 'nullable|string|max:500',
            'estado'      => 'required|in:ACTIVO,INACTIVO',
        ]);

        Idioma::create([
            'nombre'      => trim($request->nombre),
            'codigo'      => strtoupper(trim($request->codigo)),
            'descripcion' => $request->descripcion,
            'estado'      => $request->estado,
        ]);

        return redirect()->route('admin.idiomas.index')
            ->with('mensaje', 'Idioma registrado exitosamente.')
            ->with('icono', 'success');
    }

    public function update(Request $request, string $id)
    {
        $idioma = Idioma::findOrFail($id);

        $request->validate([
            'nombre'      => 'required|string|max:100|unique:idiomas,nombre,' . $idioma->id,
            'codigo'      => 'required|string|max:10|unique:idiomas,codigo,' . $idioma->id,
            'descripcion' => 'nullable|string|max:500',
            'estado'      => 'required|in:ACTIVO,INACTIVO',
        ]);

        $idioma->update([
            'nombre'      => trim($request->nombre),
            'codigo'      => strtoupper(trim($request->codigo)),
            'descripcion' => $request->descripcion,
            'estado'      => $request->estado,
        ]);

        return redirect()->route('admin.idiomas.index')
            ->with('mensaje', 'Idioma actualizado correctamente.')
            ->with('icono', 'success');
    }

    public function destroy(string $id)
    {
        $idioma = Idioma::withCount('modulos')->findOrFail($id);

        if ($idioma->modulos_count > 0) {
            return redirect()->route('admin.idiomas.index')
                ->with('mensaje', 'No se puede eliminar el idioma porque tiene módulos asociados. Desactívelo en su lugar.')
                ->with('icono', 'error');
        }

        $idioma->delete();

        return redirect()->route('admin.idiomas.index')
            ->with('mensaje', 'Idioma eliminado exitosamente.')
            ->with('icono', 'success');
    }
}
