<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Nette\Utils\Json;

class ConfiguracionController extends Controller
{
  public function index()
  {

    $configuracion = Configuracion::first(); //guarda los datos del Modelos en la DB
    return view('admin.configuracion.index', compact('configuracion')); //retorna en la vista la var configuracion   
  }

  public function store(Request $request)
  {
    /*$datos= request()->all();
      return response()->json($datos);
      */
    // 1. VALIDACIÓN DE LOS DATOS
    $request->validate([
      'nombre'             => 'required',
      'descripcion'        => 'required',
      'direccion'          => 'required',
      'telefono'           => 'required',
      'divisa'             => 'required',
      'correo_electronico' => 'required|email',
      'logo'               => 'image|mimes:jpeg,png,jpg,gif', // Soporte para el mono GIF
    ]);

    // 2. BUSCAR si ya existe una configuración previa
    $configuracion = Configuracion::first();

    if ($configuracion) {
      // --- CASO: ACTUALIZAR EXISTENTE ---
      $configuracion->nombre = $request->nombre;
      $configuracion->descripcion = $request->descripcion;
      $configuracion->direccion = $request->direccion;
      $configuracion->telefono = $request->telefono;
      $configuracion->divisa = $request->divisa;
      $configuracion->web = $request->web;
      $configuracion->correo_electronico = $request->correo_electronico;

      if ($request->hasFile('logo')) {
        // Eliminar el logo anterior físicamente si existe para no llenar el server de basura
        if ($configuracion->logo) {
          unlink(public_path($configuracion->logo));
        }

        $file = $request->file('logo');
        $nombreArchivo = time() . '_' . $file->getClientOriginalName();
        $rutaDestino = public_path('uploads/logos');

        $file->move($rutaDestino, $nombreArchivo);
        $configuracion->logo = 'uploads/logos/' . $nombreArchivo;
      }

      $configuracion->save();

      return redirect()->route('admin.configuracion.index')
        ->with('success', 'Configuración actualizada correctamente.');
    } else {
      // --- CASO: CREAR NUEVA ---
      $configuracion = new Configuracion();
      $configuracion->nombre = $request->nombre;
      $configuracion->descripcion = $request->descripcion;
      $configuracion->direccion = $request->direccion;
      $configuracion->telefono = $request->telefono;
      $configuracion->divisa = $request->divisa;
      $configuracion->web = $request->web;
      $configuracion->correo_electronico = $request->correo_electronico;

      if ($request->hasFile('logo')) {
        $file = $request->file('logo');
        $nombreArchivo = time() . '_' . $file->getClientOriginalName();
        $rutaDestino = public_path('uploads/logos');

        $file->move($rutaDestino, $nombreArchivo);
        $configuracion->logo = 'uploads/logos/' . $nombreArchivo;
      }

      $configuracion->save();

      return redirect()->route('admin.configuracion.index')
        ->with('success', 'Configuración creada correctamente.');
    }
  }
}
