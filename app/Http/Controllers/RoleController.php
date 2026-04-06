<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /*$datos= request()->all();
        return response()->json($datos);
*/
        $request->validate([
            'name' => 'required|max:255|unique:roles',
        ]);
        $rol = new Role();
        $rol->name = $request->name;
        $rol->guard_name = 'web';
        $rol->save();
        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'El rol se ha creado correctamente')
            ->with('icono', 'success');
    }


    public function permisos($id)
    {
        $rol = Role::findOrFail($id);
        $permisos = Permission::all()->groupBy(function ($permiso) {
            if (stripos($permiso->name, 'configuracion') !== false) {
                return 'Configuración del sistema';
            }
            if (stripos($permiso->name, 'gestion') !== false) {
                return 'Gestiones';
            }
            if (stripos($permiso->name, 'turnos') !== false) {
                return 'Turnos';
            }
            if (stripos($permiso->name, 'periodos') !== false) {
                return 'Periodos';
            }
            if (stripos($permiso->name, 'niveles') !== false) {
                return 'Niveles';
            }
            if (stripos($permiso->name, 'grados') !== false) {
                return 'Grados';
            }
            if (stripos($permiso->name, 'paralelos') !== false) {
                return 'Paralelos';
            }
            if (stripos($permiso->name, 'materias') !== false) {
                return 'Materias';
            }
            if (stripos($permiso->name, 'roles') !== false) {
                return 'Roles';
            }
            if (stripos($permiso->name, 'personal') !== false) {
                return 'Personal docente y administrativo';
            }
            if (stripos($permiso->name, 'formaciones') !== false) {
                return 'Formaciones del personal';
            }
            if (stripos($permiso->name, 'estudiantes') !== false) {
                return 'Estudiantes';
            }
            if (stripos($permiso->name, 'ppffs') !== false) {
                return 'Padres de familia';
            }
            if (stripos($permiso->name, 'matriculaciones') !== false) {
                return 'Matriculaciones';
            }
            if (stripos($permiso->name, 'asignaciones') !== false) {
                return 'Asignaciones';
            }
        });

        return view('admin.roles.permisos', compact('rol', 'permisos'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $rol = Role::findOrFail($id);
        return view('admin.roles.edit', compact('rol'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|max:255|unique:roles,name,' . $id,
        ]);
        $rol = Role::findOrFail($id);
        $rol->name = $request->name;
        $rol->guard_name = 'web';
        $rol->save();
        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'El rol se ha actualizado correctamente')
            ->with('icono', 'success');
    }

    public function update_permisos(Request $request, $id)
    {
        $rol = Role::findOrFail($id);
        $rol->permissions()->sync($request->input('permisos'));

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Los permisos se han actualizado correctamente')
            ->with('icono', 'success');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rol = Role::findOrFail($id);
        $rol->delete();
        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'El rol se ha eliminado correctamente')
            ->with('icono', 'success');
    }
}
