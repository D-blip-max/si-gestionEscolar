# CU-09: Asignación Académica a Grupos
## Paquete 3: Personal y Asignaciones

---

### 1. Información General
* **Identificador:** CU-09
* **Nombre:** Asignación de Docentes a Grupos (Paralelos Presenciales y Virtuales)
* **Paquete en Menú AdminLTE:** `Paquete 3: Personal y Docencia -> CU-09: Asignación a Grupos`
* **Actor Principal:** `ADMINISTRADOR`
* **Tipo de Vista:** Vistas con Modals CRUD (`admin/asignaciones/index.blade.php`, `create.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición 1:** El personal debe estar registrado con tipo `docente` y tener estado `ACTIVO`.
* **Precondición 2:** El ciclo bimestral debe estar en estado `ACTIVO` o `FUTURO`.
* **Regla 1 (Unicidad por Grupo):** Un paralelo/grupo solo puede tener **un docente titular** asignado en un ciclo bimestral.
* **Regla 2 (Prevención de Choque de Horarios):** Un docente no puede ser asignado a dos paralelos en el mismo ciclo, turno y rango de horas (incluso si uno es virtual y otro presencial).
* **Regla 3 (Visibilidad Docente):** Esta asignación es la que habilita al docente para ver a sus alumnos y cargar la Nota Final en su propio dashboard (Paquete 6).

---

### 3. Especificación Técnica de Implementación

#### Migración de Base de Datos (`create_asignacions_table.php`)
```php
Schema::create('asignacions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('personal_id')->constrained('personals')->cascadeOnDelete(); // Tipo docente
    $table->foreignId('paralelo_id')->unique()->constrained('paralelos')->cascadeOnDelete();
    $table->date('fecha_asignacion');
    $table->enum('estado', ['ACTIVO', 'FINALIZADO'])->default('ACTIVO');
    $table->timestamps();
});
```

#### Modelo Eloquent (`App\Models\Asignacion`)
```php
class Asignacion extends Model {
    protected $fillable = ['personal_id', 'paralelo_id', 'fecha_asignacion', 'estado'];

    public function docente() { return $this->belongsTo(Personal::class, 'personal_id'); }
    public function paralelo() { return $this->belongsTo(Paralelo::class); }
}
```

#### Rutas y Permisos (`routes/web.php`)
```php
Route::resource('/admin/asignaciones', App\Http\Controllers\AsignacionController::class)
    ->names('admin.asignaciones')
    ->middleware(['auth', 'can:admin.asignaciones.index']);
```

#### Validación de Choque de Horarios (`StoreAsignacionRequest`)
```php
public function withValidator($validator) {
    $validator->after(function ($validator) {
        $paraleloNuevo = Paralelo::find($this->paralelo_id);
        
        $cruce = Asignacion::where('personal_id', $this->personal_id)
            ->where('estado', 'ACTIVO')
            ->whereHas('paralelo', function ($query) use ($paraleloNuevo) {
                $query->where('ciclo_id', $paraleloNuevo->ciclo_id)
                      ->where('turno', $paraleloNuevo->turno)
                      ->where(function ($q) use ($paraleloNuevo) {
                          $q->whereBetween('hora_inicio', [$paraleloNuevo->hora_inicio, $paraleloNuevo->hora_fin])
                            ->orWhereBetween('hora_fin', [$paraleloNuevo->hora_inicio, $paraleloNuevo->hora_fin]);
                      });
            })->exists();

        if ($cruce) {
            $validator->errors()->add('personal_id', 'El docente seleccionado ya tiene otro grupo asignado en ese mismo horario y turno.');
        }
    });
}
```

#### Vista Blade Responsiva (`resources/views/admin/asignaciones/index.blade.php`)
* Tarjetas filtrables por Ciclo Bimestral y Modalidad (`Presencial` vs `Virtual`).
* Selector interactivo con Select2 para búsqueda rápida de docentes.
