<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class PersonalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($tipo = null)
    {
        $tipo = $tipo ? strtolower($tipo) : 'docente';
        $personals = Personal::with(['usuario.roles', 'formaciones'])
            ->where('tipo', $tipo)
            ->get();

        return view('admin.personal.index', compact('personals', 'tipo'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($tipo = 'docente')
    {
        $tipo = strtolower($tipo);
        $roles = Role::all();
        return view('admin.personal.create', compact('tipo', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
            'rol'              => 'required|string',
            'nombres'          => 'required|string|max:100',
            'apellidos'        => 'required|string|max:100',
            'ci'               => 'required|string|max:20|unique:personals,ci',
            'fecha_nacimiento' => 'required|date',
            'telefono'         => 'required|string|max:30',
            'profesion'        => 'required|string|max:150',
            'email'            => 'required|email|max:255|unique:users,email',
            'direccion'        => 'required|string|max:255',
            'tipo'             => 'required|in:docente,administrativo',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Crear usuario asociado
            $usuario = User::create([
                'name'     => trim($request->nombres) . ' ' . trim($request->apellidos),
                'email'    => trim($request->email),
                'password' => Hash::make($request->ci), // Contraseña inicial: CI
            ]);

            $usuario->assignRole($request->rol);

            // 2. Procesar fotografía
            $fotoPath = 'vendor/adminlte/dist/img/avatar5.png';
            if ($request->hasFile('foto')) {
                $directorio = public_path('uploads/fotos');
                if (!File::isDirectory($directorio)) {
                    File::makeDirectory($directorio, 0755, true, true);
                }

                $file = $request->file('foto');
                $nombreArchivo = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $file->move($directorio, $nombreArchivo);
                $fotoPath = 'uploads/fotos/' . $nombreArchivo;
            }

            // 3. Crear ficha de personal
            Personal::create([
                'user_id'          => $usuario->id,
                'tipo'             => $request->tipo,
                'nombres'          => trim($request->nombres),
                'apellidos'        => trim($request->apellidos),
                'ci'               => trim($request->ci),
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'direccion'        => trim($request->direccion),
                'telefono'         => trim($request->telefono),
                'profesion'        => trim($request->profesion),
                'foto'             => $fotoPath,
            ]);
        });

        return redirect()->route('admin.personal.index', $request->tipo)
            ->with('mensaje', 'Personal ' . ucfirst($request->tipo) . ' registrado exitosamente con su cuenta de usuario.')
            ->with('icono', 'success');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $personal = Personal::with(['usuario.roles', 'formaciones', 'asignaciones.paralelo.modulo.idioma'])->findOrFail($id);
        return view('admin.personal.show', compact('personal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $personal = Personal::with('usuario.roles')->findOrFail($id);
        $roles = Role::all();
        return view('admin.personal.edit', compact('personal', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $personal = Personal::findOrFail($id);
        $usuario = User::findOrFail($personal->user_id);

        $request->validate([
            'rol'              => 'required|string',
            'nombres'          => 'required|string|max:100',
            'apellidos'        => 'required|string|max:100',
            'ci'               => 'required|string|max:20|unique:personals,ci,' . $id,
            'fecha_nacimiento' => 'required|date',
            'telefono'         => 'required|string|max:30',
            'profesion'        => 'required|string|max:150',
            'email'            => 'required|email|max:255|unique:users,email,' . $personal->user_id,
            'direccion'        => 'required|string|max:255',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        DB::transaction(function () use ($request, $personal, $usuario) {
            $usuario->name = trim($request->nombres) . ' ' . trim($request->apellidos);
            $usuario->email = trim($request->email);
            if ($request->filled('password')) {
                $usuario->password = Hash::make($request->password);
            }
            $usuario->save();
            $usuario->syncRoles($request->rol);

            if ($request->hasFile('foto')) {
                if ($personal->foto && !str_contains($personal->foto, 'avatar') && File::exists(public_path($personal->foto))) {
                    File::delete(public_path($personal->foto));
                }

                $directorio = public_path('uploads/fotos');
                if (!File::isDirectory($directorio)) {
                    File::makeDirectory($directorio, 0755, true, true);
                }

                $file = $request->file('foto');
                $nombreArchivo = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $file->getClientOriginalName());
                $file->move($directorio, $nombreArchivo);
                $personal->foto = 'uploads/fotos/' . $nombreArchivo;
            }

            $personal->update([
                'nombres'          => trim($request->nombres),
                'apellidos'        => trim($request->apellidos),
                'ci'               => trim($request->ci),
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'direccion'        => trim($request->direccion),
                'telefono'         => trim($request->telefono),
                'profesion'        => trim($request->profesion),
            ]);
        });

        return redirect()->route('admin.personal.index', $personal->tipo)
            ->with('mensaje', 'Datos del personal actualizados correctamente.')
            ->with('icono', 'success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $personal = Personal::findOrFail($id);
        $tipo = $personal->tipo;

        DB::transaction(function () use ($personal) {
            if ($personal->foto && !str_contains($personal->foto, 'avatar') && File::exists(public_path($personal->foto))) {
                File::delete(public_path($personal->foto));
            }

            $user = $personal->usuario;
            $personal->delete();

            if ($user && $user->id !== auth()->id()) {
                $user->delete();
            }
        });

        return redirect()->route('admin.personal.index', $tipo)
            ->with('mensaje', 'El personal y su usuario asociado se han eliminado correctamente.')
            ->with('icono', 'success');
    }
}
