# CU-08: Formación Académica y Certificaciones del Personal
## Paquete 3: Personal y Asignaciones

---

### 1. Información General
* **Identificador:** CU-08
* **Nombre:** Registro de Títulos, Grados y Certificaciones de Idiomas del Personal
* **Paquete en Menú AdminLTE:** `Paquete 3: Personal y Docencia -> CU-08: Formación Académica`
* **Actor Principal:** `ADMINISTRADOR`
* **Tipo de Vista:** Modal CRUD y Vista de Historial (`admin/personal/formaciones/index.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** El personal debe existir previamente en la tabla `personals` (CU-07).
* **Regla 1 (Certificaciones de Idioma):** Para docentes de idiomas, permite registrar certificaciones internacionales (Ej: *TOEFL, IELTS, Cambridge C1/C2, Certificado de Lingüística Guaraní*).
* **Regla 2 (Respaldo Digital):** Se permite adjuntar archivo PDF o imagen del certificado o título profesional.
* **Regla 3:** Un personal puede tener múltiples formaciones y certificaciones registradas.

---

### 3. Especificación Técnica de Implementación

#### Migración de Base de Datos (`create_formacions_table.php`)
```php
Schema::create('formacions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('personal_id')->constrained('personals')->cascadeOnDelete();
    $table->string('titulo', 150); // Ej: "Licenciatura en Idiomas", "Certificación C2 Cambridge"
    $table->string('institucion', 150); // Universidad o Instituto emisor
    $table->integer('anio_egreso');
    $table->string('archivo_adjunto', 255)->nullable(); // PDF del título/certificado
    $table->timestamps();
});
```

#### Modelo Eloquent (`App\Models\Formacion`)
```php
class Formacion extends Model {
    protected $fillable = ['personal_id', 'titulo', 'institucion', 'anio_egreso', 'archivo_adjunto'];

    public function personal() {
        return $this->belongsTo(Personal::class);
    }
}
```

#### Rutas y Permisos (`routes/web.php`)
```php
Route::get('/admin/personal/{id}/formaciones', [App\Http\Controllers\FormacionController::class, 'index'])
    ->name('admin.formaciones.index')->middleware('auth', 'can:admin.formaciones.index');

Route::post('/admin/personal/{id}/formaciones', [App\Http\Controllers\FormacionController::class, 'store'])
    ->name('admin.formaciones.store')->middleware('auth', 'can:admin.formaciones.create');

Route::delete('/admin/personal/formaciones/{id}', [App\Http\Controllers\FormacionController::class, 'destroy'])
    ->name('admin.formaciones.destroy')->middleware('auth', 'can:admin.formaciones.destroy');
```

#### FormRequest (`App\Http\Requests\StoreFormacionRequest`)
* `titulo`: `required|string|max:150`
* `institucion`: `required|string|max:150`
* `anio_egreso`: `required|integer|min:1970|max:2099`
* `archivo_adjunto`: `nullable|file|mimes:pdf,jpg,png|max:4096`

#### Vista y Modal en AdminLTE (`resources/views/admin/formaciones/index.blade.php`)
* Modal emergente `#modal-crear-formacion` que permite subir el archivo y registrar la titulación rápidamente sin salir de la ficha del docente.
* Lista de certificados con enlace de descarga directa en PDF.
