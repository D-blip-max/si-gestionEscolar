# CU-03: Gestión de Roles, Permisos y Matriz de Acceso
## Paquete 1: Administración y Seguridad

---

### 1. Información General
* **Identificador:** CU-03
* **Nombre:** Matriz de Roles, Permisos RBAC y Registro de Auditoría
* **Paquete en Menú AdminLTE:** `Paquete 1: Seguridad y Config. -> CU-03: Roles y Permisos`
* **Actor Principal:** `ADMINISTRADOR`
* **Tipo de Vista:** Vistas Dedicadas (`admin/roles/index.blade.php`, `permisos.blade.php`, `auditoria.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** Usuario autenticado con rol `ADMINISTRADOR`.
* **Regla 1 (Roles Fijos del Instituto):** Se gestionan 4 roles centrales:
  1. `ADMINISTRADOR`: Acceso a la totalidad de paquetes.
  2. `ADMINISTRATIVO`: Acceso a Paquetes 4, 5 y 8 (Estudiantes, Boletas, Inscripciones, Reportes).
  3. `DOCENTE`: Acceso exclusivo a Paquete 6 (Mis Grupos, Cargar Notas Finales, Actas).
  4. `ESTUDIANTE`: Acceso exclusivo a Paquete 7 (Mi Módulo, Clases Meet/Aula, Ruta de Graduación).
* **Regla 2 (Agrupación Completa de Permisos):** Se corrige el agrupador en `RoleController::permisos()` para que agrupe todos los permisos por paquetes sin dejar encabezados vacíos.
* **Regla 3 (Auditoría Inmutable de Notas):** Vista de supervisión donde se leen los registros generados por el Trigger MySQL `tr_auditoria_notas_after_update` (quién cambió qué nota, nota previa, nueva y fecha).

---

### 3. Especificación Técnica de Implementación

#### Rutas y Permisos (`routes/web.php`)
```php
Route::get('/admin/roles', [App\Http\Controllers\RoleController::class, 'index'])
    ->name('admin.roles.index')->middleware('auth', 'can:admin.roles.index');

Route::get('/admin/roles/{id}/permisos', [App\Http\Controllers\RoleController::class, 'permisos'])
    ->name('admin.roles.permisos')->middleware('auth', 'can:admin.roles.permisos');

Route::post('/admin/roles/{id}/permisos', [App\Http\Controllers\RoleController::class, 'update_permisos'])
    ->name('admin.roles.update_permisos')->middleware('auth', 'can:admin.roles.update_permisos');

Route::get('/admin/roles/auditoria-notas', [App\Http\Controllers\RoleController::class, 'auditoria_notas'])
    ->name('admin.roles.auditoria')->middleware('auth', 'can:admin.roles.index');
```

#### Agrupador Corregido en `RoleController.php`
```php
public function permisos($id)
{
    $rol = Role::findOrFail($id);
    $permisos = Permission::all()->groupBy(function ($permiso) {
        if (str_contains($permiso->name, 'configuracion') || str_contains($permiso->name, 'usuarios') || str_contains($permiso->name, 'roles')) {
            return 'Paquete 1: Administración y Seguridad';
        }
        if (str_contains($permiso->name, 'idiomas') || str_contains($permiso->name, 'modulos') || str_contains($permiso->name, 'paralelos')) {
            return 'Paquete 2: Catálogos Académicos';
        }
        if (str_contains($permiso->name, 'personal') || str_contains($permiso->name, 'asignaciones') || str_contains($permiso->name, 'formaciones')) {
            return 'Paquete 3: Personal y Docencia';
        }
        if (str_contains($permiso->name, 'estudiantes') || str_contains($permiso->name, 'boletas')) {
            return 'Paquete 4: Estudiantes y Boletas';
        }
        if (str_contains($permiso->name, 'inscripciones')) {
            return 'Paquete 5: Inscripciones y Matrícula';
        }
        if (str_contains($permiso->name, 'docente') || str_contains($permiso->name, 'calificaciones') || str_contains($permiso->name, 'actas')) {
            return 'Paquete 6: Control de Notas';
        }
        if (str_contains($permiso->name, 'estudiante')) {
            return 'Paquete 7: Portal del Estudiante';
        }
        if (str_contains($permiso->name, 'reportes')) {
            return 'Paquete 8: Reportes y Finanzas';
        }
        return 'Otros Permisos';
    });

    return view('admin.roles.permisos', compact('rol', 'permisos'));
}
```

#### Vista Blade Responsiva (`resources/views/admin/roles/permisos.blade.php`)
* Checkboxes organizados por acordeón de tarjetas (Cards colapsables por paquete) con botón para "Marcar Todos / Desmarcar Todos" dentro de cada paquete.
