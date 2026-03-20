<?php

namespace App\Http\Controllers;

use App\Models\Formacion;
use Illuminate\Http\Request;
use App\Models\Personal;


class FormacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $personal = Personal::find($id);
        $formaciones = Formacion::where('personal_id', $id)->get();
        return view('admin.formaciones.index', compact('formaciones', 'personal'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        return view('admin.formaciones.create', compact('id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //$return response()->json($datos);
        $request->validate([
            'personal_id' => 'required',
            'titulo' => 'required',
            'institucion' => 'required',
            'nivel' => 'required',
            'fecha_graduacion' => 'required',
            'archivo' => 'required',
        ]);

        $formacion = new Formacion();
        $formacion->personal_id = $request->personal_id;
        $formacion->titulo = $request->titulo;
        $formacion->institucion = $request->institucion;
        $formacion->nivel = $request->nivel;
        $formacion->fecha_graduacion = $request->fecha_graduacion;

        $fotoPath = $request->file('archivo');
        $nombreArchivo = time() . '_' . $fotoPath->getClientOriginalName();
        $rutaDestino = public_path('uploads/formaciones');
        $fotoPath->move($rutaDestino, $nombreArchivo);
        $formacion->archivo = 'uploads/formaciones/' . $nombreArchivo;

        $formacion->save();

        return redirect()->route('admin.personal.formaciones.index', $request->personal_id)
            ->with('mensaje', 'La formacion se ha creado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show(Formacion $formacion)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $formacion = Formacion::find($id);
        return view('admin.formaciones.edit', compact('formacion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $request->validate([
            'titulo' => 'required',
            'institucion' => 'required',
            'nivel' => 'required',
            'fecha_graduacion' => 'required',
        ]);

        $formacion = Formacion::findOrFail($id);
        $formacion->titulo = $request->titulo;
        $formacion->institucion = $request->institucion;
        $formacion->nivel = $request->nivel;
        $formacion->fecha_graduacion = $request->fecha_graduacion;

        if ($request->hasFile('archivo')) {
            // Eliminar foto anterior
            if ($formacion->archivo && file_exists(public_path($formacion->archivo))) {
                unlink(public_path($formacion->archivo));
            }

            $fotoPath = $request->file('archivo');
            $nombreArchivo = time() . '_' . $fotoPath->getClientOriginalName();
            $rutaDestino = public_path('uploads/formaciones');
            $fotoPath->move($rutaDestino, $nombreArchivo);
            $formacion->archivo = 'uploads/formaciones/' . $nombreArchivo;
        }

        $formacion->save();

        return redirect()->route('admin.personal.formaciones.index', $formacion->personal_id)
            ->with('mensaje', 'La formacion se ha actualizado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $formacion = Formacion::findOrFail($id);

        if ($formacion->archivo && file_exists(public_path($formacion->archivo))) {
            unlink(public_path($formacion->archivo));
        }

        $formacion->delete();

        return redirect()->route('admin.personal.formaciones.index', $formacion->personal_id)
            ->with('mensaje', 'La formacion se ha eliminado correctamente')
            ->with('icono', 'success');
    }
}
