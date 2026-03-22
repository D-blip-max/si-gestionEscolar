<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Illuminate\Http\Request;
use App\Models\Ppff;
use Termwind\Components\Raw;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estudiantes = Estudiante::all();
        return view('admin.estudiantes.index', compact('estudiantes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ppffs = Ppff::all();
        $roles = Role::all();
        return view('admin.estudiantes.create', compact('ppffs', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'ppff_id'          => 'required',
            'rol'              => 'required',
            'nombres'          => 'required',
            'apellidos'        => 'required',
            'ci'               => 'required|unique:estudiantes',
            'fecha_nacimiento' => 'required',
            'telefono'         => 'required',
            'genero'           => 'required',
            'email'            => 'required|unique:users',
            'direccion'        => 'required',
            'foto'             => 'required',
        ]);

        $usuario = new User();
        $usuario->name     = $request->apellidos . ' ' . $request->nombres;
        $usuario->email    = $request->email;
        $usuario->password = Hash::make($request->ci);
        $usuario->save();

        $usuario->assignRole($request->rol);

        $estudiante = new Estudiante();
        $estudiante->usuario_id = $usuario->id;
        $estudiante->ppff_id = $request->ppff_id;
        $estudiante->nombres = $request->nombres;
        $estudiante->apellidos = $request->apellidos;
        $estudiante->ci = $request->ci;
        $estudiante->fecha_nacimiento = $request->fecha_nacimiento;
        $estudiante->direccion = $request->direccion;
        $estudiante->telefono = $request->telefono;
        $estudiante->genero = $request->genero;
        $estudiante->estado = "activo";

        $fotoPath = $request->file('foto');
        $nombreArchivo = time() . '_' . $fotoPath->getClientOriginalName();
        $rutaDestenio = public_path('uploads/fotos/estudiantes');
        $fotoPath->move($rutaDestenio, $nombreArchivo);
        $estudiante->foto = 'uploads/fotos/estudiantes/' . $nombreArchivo;

        $estudiante->save();

        return redirect()->route('admin.estudiantes.index')
            ->with('mensaje', 'El estudiante se ha creado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $estudiante = Estudiante::with('usuario', 'ppff')->find($id);
        return  view('admin.estudiantes.show', compact('estudiante'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $ppffs = Ppff::all();
        $roles = Role::all();
        $estudiante = Estudiante::with('usuario', 'ppff')->find($id);

        return view('admin.estudiantes.edit', compact('estudiante', 'roles', 'ppffs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $id)
    {
        $estudiante = Estudiante::find($id);
        $usuario = User::find($estudiante->usuario_id);

        $request->validate([
            'ppff_id'          => 'required',
            'rol'              => 'required',
            'nombres'          => 'required',
            'apellidos'        => 'required',
            'ci'               => 'required|unique:estudiantes,ci,' . $id,
            'fecha_nacimiento' => 'required',
            'telefono'         => 'required',
            'genero'           => 'required',
            'email'            => 'required|unique:users,email,' . $usuario->id,
            'direccion'        => 'required',
        ]);

        $usuario->name = $request->apellidos . ' ' . $request->nombres;
        $usuario->email = $request->email;
        $usuario->password = Hash::make($request->ci);
        $usuario->save();

        $usuario->syncRoles($request->rol);
        $estudiante->usuario_id = $usuario->id;
        $estudiante->ppff_id = $request->ppff_id;
        $estudiante->nombres = $request->nombres;
        $estudiante->apellidos = $request->apellidos;
        $estudiante->ci = $request->ci;
        $estudiante->fecha_nacimiento = $request->fecha_nacimiento;
        $estudiante->direccion = $request->direccion;
        $estudiante->telefono = $request->telefono;
        $estudiante->genero = $request->genero;
        $estudiante->estado = "activo";

        if ($request->hasFile('foto')) {
            //Eliminar foto anterior
            if ($estudiante->foto && file_exists(public_path($estudiante->foto))) {
                unlink(public_path($estudiante->foto));
            }
            $fotoPath = $request->file('foto');
            $nombreArchivo = time() . '_' . $fotoPath->getClientOriginalName();
            $rutaDestenio = public_path('uploads/fotos/estudiantes');
            $fotoPath->move($rutaDestenio, $nombreArchivo);
            $estudiante->foto = 'uploads/fotos/estudiantes/' . $nombreArchivo;
        }

        $estudiante->save();

        return redirect()->route('admin.estudiantes.index')
            ->with('mensaje', 'El estudiante se ha actualizado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        $usuario = User::findOrFail($estudiante->usuario_id);

        //Eliminar foto anterior
        if ($estudiante->foto && file_exists(public_path($estudiante->foto))) {
            unlink(public_path($estudiante->foto));
        }

        $usuario->delete();
        $estudiante->delete();

        return redirect()->route('admin.estudiantes.index')
            ->with('mensaje', 'El estudiante se ha eliminado correctamente')
            ->with('icono', 'success');
    }
}
