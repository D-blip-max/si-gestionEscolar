<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use App\Models\Estudiante;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $estudiantes = Estudiante::with(['usuario', 'boletas', 'inscripciones'])
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.estudiantes.index', compact('estudiantes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.estudiantes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombres'          => 'required|string|max:100',
            'apellidos'        => 'required|string|max:100',
            'ci'               => 'required|string|max:20|unique:estudiantes,ci',
            'email'            => 'required|email|max:255|unique:users,email',
            'telefono'         => 'required|string|max:30',
            'fecha_nacimiento' => 'required|date',
            'carrera_facultad' => 'nullable|string|max:150',
            'genero'           => 'required|in:masculino,femenino',
            'direccion'        => 'nullable|string|max:255',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        return DB::transaction(function () use ($request) {
            // 1. Crear el usuario para el estudiante
            $usuario = User::create([
                'name'     => trim($request->nombres . ' ' . $request->apellidos),
                'email'    => $request->email,
                'password' => Hash::make($request->ci), // Contraseña inicial: CI
            ]);

            // Asignar rol ESTUDIANTE
            $rol = Role::firstOrCreate(['name' => 'ESTUDIANTE']);
            $usuario->assignRole($rol);

            // 2. Generar Código CEI correlativo
            $year = date('Y');
            $count = Estudiante::whereYear('created_at', $year)->count() + 1;
            $codigo = 'CEI-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

            // Asegurar unicidad si ya existe
            while (Estudiante::where('codigo_estudiante', $codigo)->exists()) {
                $count++;
                $codigo = 'CEI-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }

            // 3. Procesar fotografía
            $rutaFoto = null;
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $nombreArchivo = 'est_' . time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $destino = public_path('uploads/fotos/estudiantes');
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }
                $foto->move($destino, $nombreArchivo);
                $rutaFoto = 'uploads/fotos/estudiantes/' . $nombreArchivo;
            }

            // 4. Crear registro en estudiantes
            $estudiante = Estudiante::create([
                'usuario_id'        => $usuario->id,
                'user_id'           => $usuario->id,
                'codigo_estudiante' => $codigo,
                'nombres'           => trim($request->nombres),
                'apellidos'         => trim($request->apellidos),
                'ci'                => trim($request->ci),
                'fecha_nacimiento'  => $request->fecha_nacimiento,
                'telefono'          => trim($request->telefono),
                'carrera_facultad'  => $request->carrera_facultad ? trim($request->carrera_facultad) : 'Particular / Externo',
                'direccion'         => $request->direccion ? trim($request->direccion) : 'Sin especificar',
                'genero'            => $request->genero,
                'foto'              => $rutaFoto,
                'estado'            => 'activo',
                'ppff_id'           => null,
            ]);

            return redirect()->route('admin.estudiantes.index')
                ->with('mensaje', "Estudiante matriculado exitosamente con código {$codigo}.")
                ->with('icono', 'success');
        });
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $estudiante = Estudiante::with([
            'usuario',
            'boletas' => function ($q) {
                $q->orderBy('id', 'desc');
            },
            'inscripciones.modulo.idioma',
            'inscripciones.paralelo.ciclo'
        ])->findOrFail($id);

        return view('admin.estudiantes.show', compact('estudiante'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $estudiante = Estudiante::with('usuario')->findOrFail($id);
        return view('admin.estudiantes.edit', compact('estudiante'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $estudiante = Estudiante::findOrFail($id);
        $usuario = User::find($estudiante->usuario_id);

        $request->validate([
            'nombres'          => 'required|string|max:100',
            'apellidos'        => 'required|string|max:100',
            'ci'               => 'required|string|max:20|unique:estudiantes,ci,' . $id,
            'email'            => 'required|email|max:255|unique:users,email,' . ($usuario ? $usuario->id : 0),
            'telefono'         => 'required|string|max:30',
            'fecha_nacimiento' => 'required|date',
            'carrera_facultad' => 'nullable|string|max:150',
            'genero'           => 'required|in:masculino,femenino',
            'estado'           => 'required|in:activo,inactivo',
            'direccion'        => 'nullable|string|max:255',
            'foto'             => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        return DB::transaction(function () use ($request, $estudiante, $usuario) {
            if ($usuario) {
                $usuario->name = trim($request->nombres . ' ' . $request->apellidos);
                $usuario->email = $request->email;
                if ($request->filled('password')) {
                    $usuario->password = Hash::make($request->password);
                }
                $usuario->save();
            }

            $datos = [
                'nombres'          => trim($request->nombres),
                'apellidos'        => trim($request->apellidos),
                'ci'               => trim($request->ci),
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'telefono'         => trim($request->telefono),
                'carrera_facultad' => $request->carrera_facultad ? trim($request->carrera_facultad) : 'Particular / Externo',
                'direccion'        => $request->direccion ? trim($request->direccion) : $estudiante->direccion,
                'genero'           => $request->genero,
                'estado'           => $request->estado,
            ];

            if ($request->hasFile('foto')) {
                if ($estudiante->foto && file_exists(public_path($estudiante->foto))) {
                    @unlink(public_path($estudiante->foto));
                }
                $foto = $request->file('foto');
                $nombreArchivo = 'est_' . time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $destino = public_path('uploads/fotos/estudiantes');
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }
                $foto->move($destino, $nombreArchivo);
                $datos['foto'] = 'uploads/fotos/estudiantes/' . $nombreArchivo;
            }

            $estudiante->update($datos);

            return redirect()->route('admin.estudiantes.index')
                ->with('mensaje', 'Datos del estudiante actualizados correctamente.')
                ->with('icono', 'success');
        });
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $estudiante = Estudiante::findOrFail($id);
        $usuario = User::find($estudiante->usuario_id);

        if ($estudiante->foto && file_exists(public_path($estudiante->foto))) {
            @unlink(public_path($estudiante->foto));
        }

        $estudiante->delete();

        if ($usuario) {
            $usuario->delete();
        }

        return redirect()->route('admin.estudiantes.index')
            ->with('mensaje', 'Estudiante y usuario vinculados eliminados exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * CU-12: Kárdex Académico Modular
     */
    public function kardex($id)
    {
        $estudiante = Estudiante::with([
            'usuario',
            'inscripciones' => function ($q) {
                $q->orderBy('created_at', 'asc');
            },
            'inscripciones.modulo.idioma',
            'inscripciones.paralelo.ciclo',
            'inscripciones.paralelo.asignacion.personal',
            'inscripciones.boletaPago'
        ])->findOrFail($id);

        return view('admin.estudiantes.kardex', compact('estudiante'));
    }

    /**
     * CU-12: Descarga de Kárdex en PDF
     */
    public function kardex_pdf($id)
    {
        $configuracion = Configuracion::first();
        $estudiante = Estudiante::with([
            'usuario',
            'inscripciones' => function ($q) {
                $q->orderBy('created_at', 'asc');
            },
            'inscripciones.modulo.idioma',
            'inscripciones.paralelo.ciclo',
            'inscripciones.paralelo.asignacion.personal',
            'inscripciones.boletaPago'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('admin.estudiantes.pdf_kardex', compact('configuracion', 'estudiante'));
        $pdf->setPaper('letter', 'portrait');
        $pdf->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->stream('kardex_' . ($estudiante->codigo_estudiante ?? $estudiante->ci) . '.pdf');
    }

    /**
     * API/AJAX búsqueda rápida por CI o Código
     */
    public function buscar_estudiante_ajax(Request $request)
    {
        $termino = $request->get('q');
        if (!$termino) {
            return response()->json([]);
        }

        $estudiantes = Estudiante::where('ci', 'LIKE', "%{$termino}%")
            ->orWhere('codigo_estudiante', 'LIKE', "%{$termino}%")
            ->orWhere('nombres', 'LIKE', "%{$termino}%")
            ->orWhere('apellidos', 'LIKE', "%{$termino}%")
            ->limit(10)
            ->get();

        return response()->json($estudiantes);
    }
}
