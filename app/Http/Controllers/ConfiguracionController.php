<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $configuracion = Configuracion::first();
        return view('admin.configuracion.index', compact('configuracion'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'             => 'required|string|max:255',
            'descripcion'        => 'required|string|max:255',
            'direccion'          => 'required|string|max:255',
            'telefono'           => 'required|string|max:50',
            'divisa'             => 'required|string|max:10',
            'correo_electronico' => 'required|email|max:255',
            'web'                => 'nullable|string|max:255',
            'logo'               => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:2048',
        ]);

        $configuracion = Configuracion::first() ?? new Configuracion();

        $configuracion->nombre = $request->nombre;
        $configuracion->descripcion = $request->descripcion;
        $configuracion->direccion = $request->direccion;
        $configuracion->telefono = $request->telefono;
        $configuracion->divisa = $request->divisa;
        $configuracion->web = $request->web;
        $configuracion->correo_electronico = $request->correo_electronico;

        if ($request->hasFile('logo')) {
            // Eliminar logo anterior si existe
            if ($configuracion->logo && File::exists(public_path($configuracion->logo))) {
                File::delete(public_path($configuracion->logo));
            }

            $directorio = public_path('uploads/logos');
            if (!File::isDirectory($directorio)) {
                File::makeDirectory($directorio, 0755, true, true);
            }

            $file = $request->file('logo');
            $nombreArchivo = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
            $file->move($directorio, $nombreArchivo);
            $configuracion->logo = 'uploads/logos/' . $nombreArchivo;
        }

        $configuracion->save();

        return redirect()->route('admin.configuracion.index')
            ->with('mensaje', 'Configuración institucional guardada exitosamente.')
            ->with('icono', 'success');
    }
}
