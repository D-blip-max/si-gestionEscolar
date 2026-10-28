<?php

namespace App\Http\Controllers;

use App\Models\Idioma;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ModuloController extends Controller
{
    public function index()
    {
        $modulos = Modulo::with(['idioma', 'prerrequisito'])
            ->orderBy('idioma_id')
            ->orderBy('numero_orden')
            ->get();

        $idiomas = Idioma::where('estado', 'ACTIVO')->get();

        return view('admin.modulos.index', compact('modulos', 'idiomas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'idioma_id'        => 'required|exists:idiomas,id',
            'nombre'           => 'required|string|max:100',
            'numero_orden'     => 'required|integer|min:1|max:20',
            'nivel'            => 'required|in:BASICO,INTERMEDIO,AVANZADO',
            'duracion_meses'   => 'nullable|integer|min:1|max:12',
            'prerrequisito_id' => ['nullable', Rule::exists('modulos', 'id')->where('idioma_id', $request->idioma_id)],
        ]);

        // Evitar duplicado de orden por idioma
        $existe = Modulo::where('idioma_id', $request->idioma_id)
            ->where('numero_orden', $request->numero_orden)
            ->exists();

        if ($existe) {
            return redirect()->back()
                ->withInput()
                ->with('mensaje', 'Ya existe un módulo con ese número de orden para el idioma seleccionado.')
                ->with('icono', 'error');
        }

        $esGraduacion = in_array((int)$request->numero_orden, [8, 9]);

        Modulo::create([
            'idioma_id'            => $request->idioma_id,
            'nombre'               => trim($request->nombre),
            'numero_orden'         => (int)$request->numero_orden,
            'nivel'                => $request->nivel,
            'duracion_meses'       => $request->duracion_meses ?? 2,
            'prerrequisito_id'     => $request->prerrequisito_id ?: null,
            'es_modulo_graduacion' => $esGraduacion,
        ]);

        return redirect()->route('admin.modulos.index')
            ->with('mensaje', 'Módulo registrado exitosamente.')
            ->with('icono', 'success');
    }

    public function update(Request $request, string $id)
    {
        $modulo = Modulo::findOrFail($id);

        $request->validate([
            'idioma_id'        => 'required|exists:idiomas,id',
            'nombre'           => 'required|string|max:100',
            'numero_orden'     => 'required|integer|min:1|max:20',
            'nivel'            => 'required|in:BASICO,INTERMEDIO,AVANZADO',
            'duracion_meses'   => 'nullable|integer|min:1|max:12',
            'prerrequisito_id' => ['nullable', Rule::exists('modulos', 'id')->where('idioma_id', $request->idioma_id), Rule::notIn([$modulo->id])],
        ]);

        // Evitar duplicado en otro registro
        $existe = Modulo::where('idioma_id', $request->idioma_id)
            ->where('numero_orden', $request->numero_orden)
            ->where('id', '!=', $modulo->id)
            ->exists();

        if ($existe) {
            return redirect()->back()
                ->withInput()
                ->with('mensaje', 'Ya existe otro módulo con ese número de orden para el idioma seleccionado.')
                ->with('icono', 'error');
        }

        $esGraduacion = in_array((int)$request->numero_orden, [8, 9]);

        $modulo->update([
            'idioma_id'            => $request->idioma_id,
            'nombre'               => trim($request->nombre),
            'numero_orden'         => (int)$request->numero_orden,
            'nivel'                => $request->nivel,
            'duracion_meses'       => $request->duracion_meses ?? 2,
            'prerrequisito_id'     => $request->prerrequisito_id ?: null,
            'es_modulo_graduacion' => $esGraduacion,
        ]);

        return redirect()->route('admin.modulos.index')
            ->with('mensaje', 'Módulo actualizado correctamente.')
            ->with('icono', 'success');
    }

    public function destroy(string $id)
    {
        $modulo = Modulo::findOrFail($id);

        $esPrerrequisito = Modulo::where('prerrequisito_id', $modulo->id)->exists();
        if ($esPrerrequisito) {
            return redirect()->route('admin.modulos.index')
                ->with('mensaje', 'No se puede eliminar el módulo porque es prerrequisito de otro módulo posterior.')
                ->with('icono', 'error');
        }

        if ($modulo->inscripciones()->exists() || $modulo->paralelos()->exists()) {
            return back()->with('mensaje', 'No se puede eliminar un módulo con grupos o historial académico.')->with('icono', 'error');
        }

        $modulo->delete();

        return redirect()->route('admin.modulos.index')
            ->with('mensaje', 'Módulo eliminado exitosamente.')
            ->with('icono', 'success');
    }
}

