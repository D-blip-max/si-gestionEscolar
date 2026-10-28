# CU-05: Módulos, Niveles y Reglas de Graduación
## Paquete 2: Catálogos Académicos

---

### 1. Información General
* **Identificador:** CU-05
* **Nombre:** Configuración de Módulos (1 al 9), Niveles y Prerrequisitos Correlativos
* **Paquete en Menú AdminLTE:** `Paquete 2: Catálogos Académicos -> CU-05: Módulos y Niveles`
* **Actor Principal:** `ADMINISTRADOR`
* **Tipo de Vista:** Modal CRUD en Vista Principal (`admin/modulos/index.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** Deben existir Idiomas registrados previamente (CU-04).
* **Regla 1 (Estructura Modular Obligatoria):**
  * **Nivel BÁSICO:** Módulos 1, 2, 3 y 4 (duración 2 meses cada uno).
  * **Nivel INTERMEDIO:** Módulos 5, 6, 7 y 8 (duración 2 meses cada uno).
    * 🎓 **Hito de Graduación:** El Módulo 8 es el módulo final del ciclo intermedio. Al aprobarlo, se emite el Título/Certificado Intermedio.
  * **Nivel AVANZADO:** Módulo 9 (módulo de especialización/perfeccionamiento).
    * 📜 **Certificación Avanzada:** Al aprobar el Módulo 9.
* **Regla 2 (Prerrequisito Correlativo):**
  * Módulo 1: Sin prerrequisito.
  * Módulo $N$: Requiere obligatoriamente tener aprobado el Módulo $N-1$ del mismo idioma.
* **Regla 3 (Duración):** Campo informativo fijo de `duracion_meses` = 2 meses por módulo.

---

### 3. Especificación Técnica de Implementación

#### Migración de Base de Datos (`create_modulos_table.php`)
```php
Schema::create('modulos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('idioma_id')->constrained('idiomas')->cascadeOnDelete();
    $table->string('nombre', 100); // Ej: "Módulo 1", "Módulo 2"...
    $table->integer('numero_orden'); // 1 al 9
    $table->enum('nivel', ['BASICO', 'INTERMEDIO', 'AVANZADO']);
    $table->integer('duracion_meses')->default(2);
    $table->foreignId('prerrequisito_id')->nullable()->constrained('modulos')->nullOnDelete();
    $table->boolean('es_modulo_graduacion')->default(false); // true para módulo 8 y módulo 9
    $table->timestamps();

    $table->unique(['idioma_id', 'numero_orden']);
});
```

#### Modelo Eloquent (`App\Models\Modulo`)
```php
class Modulo extends Model {
    protected $fillable = [
        'idioma_id', 'nombre', 'numero_orden', 'nivel',
        'duracion_meses', 'prerrequisito_id', 'es_modulo_graduacion'
    ];

    public function idioma() { return $this->belongsTo(Idioma::class); }
    public function prerrequisito() { return $this->belongsTo(Modulo::class, 'prerrequisito_id'); }
    public function inscripciones() { return $this->hasMany(Inscripcion::class); }
}
```

#### Rutas y Permisos (`routes/web.php`)
```php
Route::resource('/admin/modulos', App\Http\Controllers\ModuloController::class)
    ->names('admin.modulos')
    ->middleware(['auth', 'can:admin.modulos.index']);
```

#### Controlador (`App\Http\Controllers\ModuloController`)
* `index()`: `Modulo::with(['idioma', 'prerrequisito'])->orderBy('idioma_id')->orderBy('numero_orden')->get();`
* `store(StoreModuloRequest $request)`: Validación y almacenamiento. Asigna automáticamente `es_modulo_graduacion = true` si el `numero_orden` es 8 o 9.

#### Vista y Modal en AdminLTE (`resources/views/admin/modulos/index.blade.php`)
* DataTable agrupado por idioma con badges distintivos de nivel:
  * `badge-warning` para Básico (Módulos 1-4).
  * `badge-primary` para Intermedio (Módulos 5-8).
  * `badge-purple` para Avanzado (Módulo 9).
* Selector dinámico en Modal para elegir el idioma y el módulo prerrequisito.
