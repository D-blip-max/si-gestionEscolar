# CU-07: Padrón de Personal Docente y Administrativo
## Paquete 3: Personal y Asignaciones

---

### 1. Información General
* **Identificador:** CU-07
* **Nombre:** Gestión de Fichas de Personal (Docentes y Administrativos)
* **Paquete en Menú AdminLTE:** `Paquete 3: Personal y Docencia -> CU-07: Padrón de Personal`
* **Actor Principal:** `ADMINISTRADOR`
* **Tipo de Vista:** Vistas Dedicadas (`admin/personal/index.blade.php`, `create.blade.php`, `edit.blade.php`, `show.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** Usuario autenticado con permiso `admin.personal.index`.
* **Regla 1 (Clasificación de Tipo):** Cada registro de personal se clasifica estrictamente como `docente` o `administrativo`.
* **Regla 2 (Creación de Cuenta Automática):** Al registrar un personal, el sistema puede crear automáticamente su cuenta de usuario en `users` con su correo y contraseña inicial encriptada (asignando el rol Spatie `DOCENTE` o `ADMINISTRATIVO`).
* **Regla 3 (Carnet de Identidad Único):** El CI debe ser único en la tabla `personals`.
* **Regla 4:** La ficha de personal incluye datos de contacto, profesión, dirección, fecha de nacimiento y fotografía opcional.

---

### 3. Especificación Técnica de Implementación

#### Migración de Base de Datos (`create_personals_table.php`)
```php
Schema::create('personals', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
    $table->enum('tipo', ['docente', 'administrativo']);
    $table->string('nombres', 100);
    $table->string('apellidos', 100);
    $table->string('ci', 20)->unique();
    $table->date('fecha_nacimiento');
    $table->string('telefono', 30);
    $table->string('profesion', 100);
    $table->string('direccion', 255);
    $table->string('foto', 255)->nullable();
    $table->enum('estado', ['ACTIVO', 'INACTIVO'])->default('ACTIVO');
    $table->timestamps();

    $table->index(['tipo', 'estado']);
});
```

#### Modelo Eloquent (`App\Models\Personal`)
```php
class Personal extends Model {
    protected $fillable = [
        'user_id', 'tipo', 'nombres', 'apellidos', 'ci',
        'fecha_nacimiento', 'telefono', 'profesion', 'direccion', 'foto', 'estado'
    ];

    public function usuario() { return $this->belongsTo(User::class, 'user_id'); }
    public function formaciones() { return $this->hasMany(Formacion::class); }
    public function asignaciones() { return $this->hasMany(Asignacion::class); }
}
```

#### Rutas y Permisos (`routes/web.php`)
```php
Route::get('/admin/personal/{tipo}', [App\Http\Controllers\PersonalController::class, 'index'])
    ->name('admin.personal.index')->middleware('auth', 'can:admin.personal.index');

Route::get('/admin/personal/create/{tipo}', [App\Http\Controllers\PersonalController::class, 'create'])
    ->name('admin.personal.create')->middleware('auth', 'can:admin.personal.create');

Route::post('/admin/personal', [App\Http\Controllers\PersonalController::class, 'store'])
    ->name('admin.personal.store')->middleware('auth', 'can:admin.personal.store');

Route::get('/admin/personal/{id}', [App\Http\Controllers\PersonalController::class, 'show'])
    ->name('admin.personal.show')->middleware('auth', 'can:admin.personal.show');

Route::put('/admin/personal/{id}', [App\Http\Controllers\PersonalController::class, 'update'])
    ->name('admin.personal.update')->middleware('auth', 'can:admin.personal.update');
```

#### Transacción Atómica al Registrar Personal (`DB::transaction`)
```php
DB::transaction(function () use ($request) {
    // 1. Crear usuario si se seleccionó la opción
    $user = User::create([
        'name' => $request->nombres . ' ' . $request->apellidos,
        'email' => $request->email,
        'password' => Hash::make($request->password ?? $request->ci),
    ]);
    $user->assignRole(strtoupper($request->tipo)); // 'DOCENTE' o 'ADMINISTRATIVO'

    // 2. Crear ficha de personal vinculada
    Personal::create(array_merge($request->validated(), ['user_id' => $user->id]));
});
```

#### Vista Blade Responsiva (`resources/views/admin/personal/index.blade.php`)
* Pestañas para alternar entre "Personal Docente" y "Personal Administrativo".
* Tabla DataTable con fotos en avatar circular y exportación a Excel/PDF.
