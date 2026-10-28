# CU-13: Inscripción / Matrícula Modular (ACID)
## Paquete 5: Inscripciones y Matrícula

---

### 1. Información General
* **Identificador:** CU-13
* **Nombre:** Proceso de Inscripción Modular con Validación de Boleta y Correlativas
* **Paquete en Menú AdminLTE:** `Paquete 5: Inscripción Modular -> CU-13: Nueva Inscripción`
* **Actor Principal:** `ADMINISTRATIVO`
* **Tipo de Vista:** Vista Dedicada tipo Wizard / Paso a Paso (`admin/inscripciones/create.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones (Garantía ACID)
* **Precondición 1 (Boleta Bancaria Válida):** El estudiante debe tener una boleta en estado `DISPONIBLE` asociada a su cuenta (CU-11).
* **Precondición 2 (Validación de Prerrequisitos):**
  * Para inscribirse al **Módulo 1:** Abierto sin prerrequisito.
  * Para inscribirse al **Módulo $N$ ($N \ge 2$):** El estudiante debe tener obligatoriamente el Módulo $N-1$ en estado `APROBADO` ($\ge 51$) en el mismo idioma.
* **Precondición 3 (Control de Cupos por Modalidad):** El paralelo seleccionado debe tener `cupo_ocupado < cupo_maximo`.
* **Regla de Atomicidad (ACID):** Toda la operación se ejecuta en un bloque `DB::transaction()` con `lockForUpdate()`.

---

### 3. Especificación Técnica de Implementación

#### Migración de Base de Datos (`create_inscripcions_table.php`)
```php
Schema::create('inscripciones', function (Blueprint $table) {
    $table->id();
    $table->foreignId('estudiante_id')->constrained('estudiantes')->cascadeOnDelete();
    $table->foreignId('modulo_id')->constrained('modulos')->cascadeOnDelete();
    $table->foreignId('paralelo_id')->constrained('paralelos')->cascadeOnDelete();
    $table->foreignId('boleta_pago_id')->unique()->constrained('boletas_pago')->restrictOnDelete();
    $table->date('fecha_inscripcion');
    $table->enum('estado', ['ACTIVA', 'ANULADA'])->default('ACTIVA');
    $table->foreignId('inscrito_por')->constrained('users');
    $table->timestamps();

    $table->unique(['estudiante_id', 'paralelo_id']);
    $table->index(['estudiante_id', 'modulo_id', 'estado']);
});
```

#### Transacción ACID en `InscripcionController.php`
```php
public function store(StoreInscripcionRequest $request)
{
    return DB::transaction(function () use ($request) {
        // 1. Bloquear y verificar la boleta bancaria
        $boleta = BoletaPago::where('id', $request->boleta_pago_id)
            ->where('estado', 'DISPONIBLE')
            ->lockForUpdate()
            ->firstOrFail();

        // 2. Verificar cupo disponible en el paralelo
        $paralelo = Paralelo::where('id', $request->paralelo_id)
            ->lockForUpdate()
            ->firstOrFail();

        if ($paralelo->cupo_ocupado >= $paralelo->cupo_maximo) {
            throw ValidationException::withMessages(['paralelo_id' => 'El grupo seleccionado ya no tiene cupos disponibles.']);
        }

        // 3. Validar prerrequisito del módulo
        $modulo = Modulo::findOrFail($request->modulo_id);
        if ($modulo->prerrequisito_id) {
            $aprobado = Inscripcion::where('estudiante_id', $request->estudiante_id)
                ->where('modulo_id', $modulo->prerrequisito_id)
                ->whereHas('calificacion', fn($q) => $q->where('estado', 'APROBADO'))
                ->exists();

            if (!$aprobado) {
                throw ValidationException::withMessages(['modulo_id' => 'El estudiante no ha aprobado el módulo prerrequisito necesario.']);
            }
        }

        // 4. Crear la inscripción
        $inscripcion = Inscripcion::create([
            'estudiante_id' => $request->estudiante_id,
            'modulo_id' => $request->modulo_id,
            'paralelo_id' => $request->paralelo_id,
            'boleta_pago_id' => $boleta->id,
            'fecha_inscripcion' => now()->toDateString(),
            'estado' => 'ACTIVA',
            'inscrito_por' => auth()->id(),
        ]);

        // 5. Marcar boleta como UTILIZADA
        $boleta->update(['estado' => 'UTILIZADA']);

        // 6. Crear registro inicial de calificación
        Calificacion::create([
            'inscripcion_id' => $inscripcion->id,
            'nota_final' => 0,
            'estado' => 'PENDIENTE',
            'updated_by' => auth()->id(),
        ]);

        return redirect()->route('admin.inscripciones.pdf', $inscripcion->id)
            ->with('mensaje', 'Inscripción completada exitosamente')
            ->with('icono', 'success');
    });
}
```

#### Vista Blade Responsiva (`resources/views/admin/inscripciones/create.blade.php`)
* Búsqueda reactiva de estudiante vía Select2 AJAX.
* Filtros interactivos en cascada: Idioma $\rightarrow$ Módulo habilitado $\rightarrow$ **Modalidad (Presencial / Virtual)** $\rightarrow$ Turno y Paralelo.
* Selector de boletas disponibles del estudiante con resumen de monto y fecha.
