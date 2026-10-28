# CU-04: Gestión de Idiomas Ofertados
## Paquete 2: Catálogos Académicos

---

### 1. Información General
* **Identificador:** CU-04
* **Nombre:** Catálogo de Idiomas (Inglés, Guaraní y futuros idiomas)
* **Paquete en Menú AdminLTE:** `Paquete 2: Catálogos Académicos -> CU-04: Gestión de Idiomas`
* **Actor Principal:** `ADMINISTRADOR`
* **Tipo de Vista:** Modal CRUD en Vista Principal (`admin/idiomas/index.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** Usuario autenticado con permiso `admin.idiomas.index`.
* **Regla 1 (Idiomas Iniciales):** El sistema inicializa con `Inglés` (Código: `ENG`) y `Guaraní` (Código: `GUA`).
* **Regla 2 (Unicidad y Código):** El nombre del idioma y su código ISO/interno deben ser únicos en la base de datos.
* **Regla 3 (Estado Activo/Inactivo):** Un idioma puede deshabilitarse temporalmente para no permitir nuevas inscripciones, sin afectar a los estudiantes que ya lo cursaron o tienen historial.
* **Regla 4 (Integridad):** Si un idioma tiene inscripciones históricas registradas, no se permite su eliminación física.

---

### 3. Especificación Técnica de Implementación

#### Rutas y Permisos (`routes/web.php`)
```php
Route::get('/admin/idiomas', [App\Http\Controllers\IdiomaController::class, 'index'])
    ->name('admin.idiomas.index')->middleware('auth', 'can:admin.idiomas.index');

Route::post('/admin/idiomas', [App\Http\Controllers\IdiomaController::class, 'store'])
    ->name('admin.idiomas.store')->middleware('auth', 'can:admin.idiomas.create');

Route::put('/admin/idiomas/{id}', [App\Http\Controllers\IdiomaController::class, 'update'])
    ->name('admin.idiomas.update')->middleware('auth', 'can:admin.idiomas.edit');

Route::delete('/admin/idiomas/{id}', [App\Http\Controllers\IdiomaController::class, 'destroy'])
    ->name('admin.idiomas.destroy')->middleware('auth', 'can:admin.idiomas.destroy');
```

#### Migración de Base de Datos (`create_idiomas_table.php`)
```php
Schema::create('idiomas', function (Blueprint $table) {
    $table->id();
    $table->string('nombre', 100)->unique();
    $table->string('codigo', 10)->unique(); // Ej: ENG, GUA
    $table->text('descripcion')->nullable();
    $table->enum('estado', ['ACTIVO', 'INACTIVO'])->default('ACTIVO');
    $table->timestamps();
});
```

#### Modelo Eloquent (`App\Models\Idioma`)
```php
class Idioma extends Model {
    protected $fillable = ['nombre', 'codigo', 'descripcion', 'estado'];

    public function modulos() {
        return $this->hasMany(Modulo::class);
    }
    public function inscripciones() {
        return $this->hasManyThrough(Inscripcion::class, Modulo::class);
    }
}
```

#### FormRequest (`App\Http\Requests\StoreIdiomaRequest`)
* `nombre`: `required|string|max:100|unique:idiomas,nombre`
* `codigo`: `required|string|max:10|uppercase|unique:idiomas,codigo`
* `estado`: `required|in:ACTIVO,INACTIVO`

#### Vista y Modal en AdminLTE (`resources/views/admin/idiomas/index.blade.php`)
* **Tabla DataTable:** Columnas: ID, Nombre, Código, Estado (Badge verde/gris), Acciones.
* **Modal `#modal-crear-idioma`:** Formulario emergente rápido con campos `nombre`, `codigo`, `descripcion` y switch de estado.
* **Modal `#modal-editar-idioma`:** Carga dinámica vía data-attributes o AJAX para edición rápida.
* **Feedback:** Confirmación de eliminación con SweetAlert2.
