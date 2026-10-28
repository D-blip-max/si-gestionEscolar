# CU-06: Ciclos Bimestrales, Modalidades y Paralelos
## Paquete 2: Catálogos Académicos

---

### 1. Información General
* **Identificador:** CU-06
* **Nombre:** Configuración de Ciclos Bimestrales, Turnos y Paralelos (Presencial y Virtual)
* **Paquete en Menú AdminLTE:** `Paquete 2: Catálogos Académicos -> CU-06: Ciclos y Paralelos`
* **Actor Principal:** `ADMINISTRADOR`
* **Tipo de Vista:** Vistas con Modals CRUD (`admin/paralelos/index.blade.php`, `admin/ciclos/index.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones
* **Regla 1 (Ciclos Bimestrales de 2 Meses):** La gestión anual se divide en 6 ciclos bimestrales (Ciclo I: Ene-Feb, Ciclo II: Mar-Abr, etc.). Solo un ciclo está en estado `ACTIVO` para inscripciones vigentes.
* **Regla 2 (Modalidades Diferenciadas):**
  * **Modalidad Presencial:** Obligatorio indicar el **Aula Física** (Ej. *Aula 104 - Pabellón A*) y **Cupo Máximo Físico** (Ej. 30 alumnos).
  * **Modalidad Virtual:** Obligatorio indicar el **Enlace de Videoconferencia** (URL de Google Meet / Zoom) y **Cupo Máximo Digital** (Ej. 40 alumnos).
* **Regla 3 (Turnos y Horarios):** Cada paralelo se asocia a un turno (`MAÑANA`, `TARDE`, `NOCHE`) con hora de inicio y fin.
* **Regla 4 (Control Atómico de Cupos):** El campo `cupo_ocupado` se actualiza automáticamente mediante el Trigger `tr_actualizar_cupo_after_insert`. Si `cupo_ocupado >= cupo_maximo`, el sistema bloquea nuevas inscripciones en ese grupo.

---

### 3. Especificación Técnica de Implementación

#### Migración de Base de Datos (`create_paralelos_table.php`)
```php
Schema::create('ciclos_bimestrales', function (Blueprint $table) {
    $table->id();
    $table->string('nombre', 50); // Ej: "Bimestre I - 2026 (Ene-Feb)"
    $table->integer('gestion'); // 2026
    $table->date('fecha_inicio');
    $table->date('fecha_fin');
    $table->enum('estado', ['ACTIVO', 'CERRADO', 'FUTURO'])->default('FUTURO');
    $table->timestamps();
});

Schema::create('paralelos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('ciclo_id')->constrained('ciclos_bimestrales')->cascadeOnDelete();
    $table->foreignId('modulo_id')->constrained('modulos')->cascadeOnDelete();
    $table->string('nombre', 10); // Ej: "A", "B", "C"
    $table->enum('modalidad', ['PRESENCIAL', 'VIRTUAL']);
    $table->string('aula_o_enlace', 255); // Aula física o Link de Meet/Zoom
    $table->enum('turno', ['MAÑANA', 'TARDE', 'NOCHE']);
    $table->time('hora_inicio');
    $table->time('hora_fin');
    $table->integer('cupo_maximo')->default(30);
    $table->integer('cupo_ocupado')->default(0);
    $table->timestamps();

    $table->index(['ciclo_id', 'modulo_id', 'modalidad']);
});
```

#### Modelo Eloquent (`App\Models\Paralelo`)
```php
class Paralelo extends Model {
    protected $fillable = [
        'ciclo_id', 'modulo_id', 'nombre', 'modalidad',
        'aula_o_enlace', 'turno', 'hora_inicio', 'hora_fin',
        'cupo_maximo', 'cupo_ocupado'
    ];

    public function ciclo() { return $this->belongsTo(CicloBimestral::class, 'ciclo_id'); }
    public function modulo() { return $this->belongsTo(Modulo::class); }
    public function inscripciones() { return $this->hasMany(Inscripcion::class); }
    public function asignacion() { return $this->hasOne(Asignacion::class); }
}
```

#### FormRequest (`App\Http\Requests\StoreParaleloRequest`)
* `ciclo_id`: `required|exists:ciclos_bimestrales,id`
* `modulo_id`: `required|exists:modulos,id`
* `nombre`: `required|string|max:10`
* `modalidad`: `required|in:PRESENCIAL,VIRTUAL`
* `aula_o_enlace`: `required|string|max:255`
* `turno`: `required|in:MAÑANA,TARDE,NOCHE`
* `cupo_maximo`: `required|integer|min:1|max:100`

#### Vista y Modal en AdminLTE (`resources/views/admin/paralelos/index.blade.php`)
* Tarjetas diferenciadas por color para grupos Presenciales (icono 🏫) y Virtuales (icono 💻).
* Barra de progreso de cupos en tiempo real: `Verde (< 70%)`, `Amarillo (70% - 95%)`, `Rojo (Lleno 100%)`.
* Modal con validación condicional en JS: Si selecciona `PRESENCIAL`, la etiqueta pide "Nombre del Aula / Pabellón"; si selecciona `VIRTUAL`, pide "Enlace de Reunión (Meet/Zoom)".
