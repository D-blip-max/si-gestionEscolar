<?php

namespace App\Http\Controllers;

use App\Models\Formacion;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FormacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $personal = Personal::findOrFail($id);
        $formaciones = Formacion::where('personal_id', $id)->orderBy('id', 'desc')->get();
        return view('admin.formaciones.index', compact('formaciones', 'personal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $personal = Personal::findOrFail($id);
        return view('admin.formaciones.create', compact('personal', 'id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'personal_id'      => 'required|exists:personals,id',
            'titulo'           => 'required|string|max:200',
            'institucion'      => 'required|string|max:200',
            'nivel'            => 'required|string|max:100',
            'fecha_graduacion' => 'required',
            'archivo'          => 'required|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        $directorio = public_path('uploads/formaciones');
        if (!File::isDirectory($directorio)) {
            File::makeDirectory($directorio, 0755, true, true);
        }

        $file = $request->file('archivo');
        $nombreArchivo = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
        $file->move($directorio, $nombreArchivo);

        Formacion::create([
            'personal_id'      => $request->personal_id,
            'titulo'           => trim($request->titulo),
            'institucion'      => trim($request->institucion),
            'nivel'            => trim($request->nivel),
            'fecha_graduacion' => $request->fecha_graduacion,
            'archivo'          => 'uploads/formaciones/' . $nombreArchivo,
        ]);

        return redirect()->route('admin.personal.formaciones.index', $request->personal_id)
            ->with('mensaje', 'Formación/Certificación académica registrada correctamente.')
            ->with('icono', 'success');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $formacion = Formacion::with('personal')->findOrFail($id);
        return view('admin.formaciones.edit', compact('formacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'titulo'           => 'required|string|max:200',
            'institucion'      => 'required|string|max:200',
            'nivel'            => 'required|string|max:100',
            'fecha_graduacion' => 'required',
            'archivo'          => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        $formacion = Formacion::findOrFail($id);

        $rutaArchivo = $formacion->archivo;
        if ($request->hasFile('archivo')) {
            if ($formacion->archivo && File::exists(public_path($formacion->archivo))) {
                File::delete(public_path($formacion->archivo));
            }

            $directorio = public_path('uploads/formaciones');
            if (!File::isDirectory($directorio)) {
                File::makeDirectory($directorio, 0755, true, true);
            }

            $file = $request->file('archivo');
            $nombreArchivo = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move($directorio, $nombreArchivo);
            $rutaArchivo = 'uploads/formaciones/' . $nombreArchivo;
        }

        $formacion->update([
            'titulo'           => trim($request->titulo),
            'institucion'      => trim($request->institucion),
            'nivel'            => trim($request->nivel),
            'fecha_graduacion' => $request->fecha_graduacion,
            'archivo'          => $rutaArchivo,
        ]);

        return redirect()->route('admin.personal.formaciones.index', $formacion->personal_id)
            ->with('mensaje', 'Formación/Certificación actualizada exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $formacion = Formacion::findOrFail($id);
        $personalId = $formacion->personal_id;

        if ($formacion->archivo && File::exists(public_path($formacion->archivo))) {
            File::delete(public_path($formacion->archivo));
        }

        $formacion->delete();

        return redirect()->route('admin.personal.formaciones.index', $personalId)
            ->with('mensaje', 'Certificación/Formación eliminada correctamente.')
            ->with('icono', 'success');
    }
}
