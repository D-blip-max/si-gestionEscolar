<?php

namespace App\Http\Controllers;

use App\Models\AuditoriaCalificacion;
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
        $roles = Role::withCount('permissions')->get();
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
        $request->validate([
            'name' => 'required|string|max:100|unique:roles,name',
        ]);

        $rol = new Role();
        $rol->name = strtoupper(trim($request->name));
        $rol->guard_name = 'web';
        $rol->save();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'El rol se ha creado exitosamente.')
            ->with('icono', 'success');
    }

    /**
     * Matriz de permisos agrupados por los 8 paquetes de gestión
     */
    public function permisos($id)
    {
        $rol = Role::findOrFail($id);

        $permisos = Permission::all()->groupBy(function ($permiso) {
            $name = strtolower($permiso->name);

            if (str_contains($name, 'configuracion') || str_contains($name, 'usuarios') || str_contains($name, 'roles') || str_contains($name, 'seguridad')) {
                return 'Administración y Seguridad';
            }
            if (str_contains($name, 'idiomas') || str_contains($name, 'modulos') || str_contains($name, 'paralelos') || str_contains($name, 'ciclos') || str_contains($name, 'catalogos') || str_contains($name, 'gestion') || str_contains($name, 'periodos') || str_contains($name, 'niveles') || str_contains($name, 'grados') || str_contains($name, 'turnos') || str_contains($name, 'materias')) {
                return 'Catálogos Académicos';
            }
            if (str_contains($name, 'personal') || str_contains($name, 'asignaciones') || str_contains($name, 'formaciones')) {
                return 'Personal y Docencia';
            }
            if (str_contains($name, 'estudiantes') || str_contains($name, 'boletas') || str_contains($name, 'ppffs')) {
                return 'Estudiantes y Boletas';
            }
            if (str_contains($name, 'inscripciones') || str_contains($name, 'matriculaciones')) {
                return 'Inscripciones y Matrícula';
            }
            if (str_contains($name, 'docente') || str_contains($name, 'calificaciones') || str_contains($name, 'actas') || str_contains($name, 'asistencias')) {
                return 'Control de Notas';
            }
            if (str_contains($name, 'estudiante.portal') || str_contains($name, 'estudiante.clases') || str_contains($name, 'estudiante.avance') || str_contains($name, 'estudiante.documentos')) {
                return 'Portal del Estudiante';
            }
            if (str_contains($name, 'reportes') || str_contains($name, 'pagos')) {
                return 'Reportes y Finanzas';
            }

            return 'Otros Permisos Generales';
        });

        return view('admin.roles.permisos', compact('rol', 'permisos'));
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
            'name' => 'required|string|max:100|unique:roles,name,' . $id,
        ]);

        $rol = Role::findOrFail($id);
        $rol->name = strtoupper(trim($request->name));
        $rol->save();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'El rol se ha actualizado correctamente.')
            ->with('icono', 'success');
    }

    /**
     * Guardar asignación de permisos
     */
    public function update_permisos(Request $request, $id)
    {
        $rol = Role::findOrFail($id);
        $permisos = $request->input('permisos', []);

        // Sincronizar array de IDs o nombres
        $rol->syncPermissions($permisos);

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'Los permisos para el rol ' . $rol->name . ' se han actualizado correctamente.')
            ->with('icono', 'success');
    }

    /**
     * Auditoría inmutable de notas modificadas (Trigger / Registro)
     */
    public function auditoria_notas()
    {
        $auditorias = AuditoriaCalificacion::with(['usuario'])->orderBy('id', 'desc')->paginate(25);
        return view('admin.roles.auditoria', compact('auditorias'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rol = Role::findOrFail($id);

        if (in_array($rol->name, ['ADMINISTRADOR', 'ADMINISTRATIVO', 'DOCENTE', 'ESTUDIANTE'])) {
            return redirect()->route('admin.roles.index')
                ->with('mensaje', 'El rol del sistema "' . $rol->name . '" es fundamental y no puede ser eliminado.')
                ->with('icono', 'error');
        }

        $rol->delete();

        return redirect()->route('admin.roles.index')
            ->with('mensaje', 'El rol se ha eliminado correctamente.')
            ->with('icono', 'success');
    }
}
