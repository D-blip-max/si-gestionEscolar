# CU-02: Gestión de Usuarios del Sistema
## Paquete 1: Administración y Seguridad

---

### 1. Información General
* **Identificador:** CU-02
* **Nombre:** Gestión Integral de Cuentas de Usuario y Credenciales
* **Paquete en Menú AdminLTE:** `Paquete 1: Seguridad y Config. -> CU-02: Gestión de Usuarios`
* **Actor Principal:** `ADMINISTRADOR`
* **Tipo de Vista:** Vistas Dedicadas (`admin/usuarios/index.blade.php`, `create.blade.php`, `edit.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** Usuario autenticado con rol `ADMINISTRADOR` y permiso `admin.usuarios.index`.
* **Regla 1 (Corrección de Bug):** El administrador puede editar y cambiar la contraseña de **cualquier usuario** sin que se sobrescriba su propia sesión de administrador (resolviendo el bug previo en `UserController`).
* **Regla 2 (Asignación de Roles):** Cada usuario debe tener asignado exactamente uno de los 4 roles principales: `ADMINISTRADOR`, `ADMINISTRATIVO`, `DOCENTE`, `ESTUDIANTE`.
* **Regla 3 (Integridad Referencial):** Si un usuario tiene registros asociados en `personals` o `estudiantes`, no puede ser eliminado directamente; debe aplicarse borrado lógico o advertencia visual estricta.

---

### 3. Especificación Técnica de Implementación

#### Rutas y Permisos (`routes/web.php`)
```php
Route::resource('/admin/usuarios', App\Http\Controllers\UserController::class)
    ->names('admin.usuarios')
    ->middleware(['auth', 'can:admin.usuarios.index']);
```

#### FormRequests Dedicados
1. **`StoreUserRequest`:**
   * `name`: `required|string|max:255`
   * `email`: `required|string|email|max:255|unique:users,email`
   * `password`: `required|string|min:8|confirmed`
   * `roles`: `required|array|min:1`
2. **`UpdateUserRequest`:**
   * `name`: `required|string|max:255`
   * `email`: `required|string|email|max:255|unique:users,email,' . $this->route('usuario')`
   * `password`: `nullable|string|min:8|confirmed`
   * `roles`: `required|array|min:1`

#### Controlador Corregido (`App\Http\Controllers\UserController`)
* `index()`: `User::with('roles')->paginate(20);` (evita N+1).
* `edit($id)`:
  ```php
  $user = User::findOrFail($id);
  $roles = Role::all();
  return view('admin.usuarios.edit', compact('user', 'roles'));
  ```
* `update(UpdateUserRequest $request, $id)`:
  ```php
  $user = User::findOrFail($id); // Corregido: busca el usuario del registro, NO auth()->user()
  $user->name = $request->name;
  $user->email = $request->email;
  if ($request->filled('password')) {
      $user->password = Hash::make($request->password);
  }
  $user->save();
  $user->syncRoles($request->roles);
  return redirect()->route('admin.usuarios.index')
      ->with('mensaje', 'Usuario actualizado correctamente')
      ->with('icono', 'success');
  ```
* `destroy($id)`: Elimina usuario y revoca roles en Spatie.

#### Vista Blade Responsiva (`resources/views/admin/usuarios/index.blade.php`)
* DataTable con botones de exportación, badges de colores según el rol asignado (`badge-danger` para Admin, `badge-primary` para Docente, etc.) y botón de confirmación SweetAlert2 para eliminación.
