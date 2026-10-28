# CU-17: Registro de Nota Final del Módulo
## Paquete 6: Evaluación y Calificaciones

---

### 1. Información General
* **Identificador:** CU-17
* **Nombre:** Registro y Asentamiento de la Nota Final del Módulo
* **Paquete en Menú AdminLTE:** `Paquete 6: Control de Notas -> CU-17: Cargar Nota Final`
* **Actor Principal:** `DOCENTE`
* **Tipo de Vista:** Planilla Interactiva Editable (`docente/calificaciones/create.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones (Nota Final Única)
* **Regla 1 (Sin Complicaciones - Nota Final Única):** El docente no carga parciales ni desglose de tareas; carga **exclusivamente la Nota Final Definitiva** del módulo bimestral.
* **Regla 2 (Escala de Calificación):** Entero entre `0` y `100`.
* **Regla 3 (Aprobación Automática):**
  * Si $\text{Nota Final} \ge 51 \rightarrow$ Estado: `APROBADO`.
  * Si $\text{Nota Final} < 51 \rightarrow$ Estado: `REPROBADO`.
* **Regla 4 (Auditoría por Trigger MySQL):** Cualquier modificación posterior de la nota dispara el Trigger `tr_auditoria_notas_after_update`, registrando la nota anterior, la nueva, el ID del docente y la fecha en `auditoria_calificaciones`.

---

### 3. Especificación Técnica de Implementación

#### Migración de Base de Datos (`create_calificacions_table.php`)
```php
Schema::create('calificaciones', function (Blueprint $table) {
    $table->id();
    $table->foreignId('inscripcion_id')->unique()->constrained('inscripciones')->cascadeOnDelete();
    $table->decimal('nota_final', 5, 2)->default(0);
    $table->enum('estado', ['PENDIENTE', 'APROBADO', 'REPROBADO', 'ANULADA'])->default('PENDIENTE');
    $table->text('observaciones')->nullable();
    $table->foreignId('updated_by')->nullable()->constrained('users');
    $table->timestamps();

    $table->index(['inscripcion_id', 'estado']);
});

Schema::create('auditoria_calificaciones', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('calificacion_id');
    $table->unsignedBigInteger('inscripcion_id');
    $table->decimal('nota_anterior', 5, 2);
    $table->decimal('nota_nueva', 5, 2);
    $table->unsignedBigInteger('modificado_por')->nullable();
    $table->timestamp('created_at')->useCurrent();
});
```

#### Rutas y Permisos (`routes/web.php`)
```php
Route::get('/docente/calificaciones/grupo/{paralelo_id}', [App\Http\Controllers\CalificacionController::class, 'cargar_notas'])
    ->name('docente.calificaciones.cargar')->middleware('auth', 'role:DOCENTE');

Route::post('/docente/calificaciones/guardar-lote', [App\Http\Controllers\CalificacionController::class, 'guardar_lote'])
    ->name('docente.calificaciones.guardar_lote')->middleware('auth', 'role:DOCENTE');
```

#### Controlador (`CalificacionController@guardar_lote`)
```php
public function guardar_lote(Request $request)
{
    $request->validate([
        'notas' => 'required|array',
        'notas.*.inscripcion_id' => 'required|exists:inscripciones,id',
        'notas.*.nota_final' => 'required|numeric|min:0|max:100',
    ]);

    DB::transaction(function () use ($request) {
        foreach ($request->notas as $item) {
            $nota = floatval($item['nota_final']);
            $estado = ($nota >= 51) ? 'APROBADO' : 'REPROBADO';

            $calificacion = Calificacion::where('inscripcion_id', $item['inscripcion_id'])->first();
            if ($calificacion) {
                $calificacion->update([
                    'nota_final' => $nota,
                    'estado' => $estado,
                    'updated_by' => auth()->id(),
                ]);
            }
        }
    });

    return redirect()->back()
        ->with('mensaje', 'Notas finales guardadas correctamente')
        ->with('icono', 'success');
}
```

#### Vista Blade Responsiva (`resources/views/docente/calificaciones/create.blade.php`)
* Tabla responsiva con lista de estudiantes del grupo (CI, Apellidos, Nombres).
* Input numérico directo con validación inmediata en JavaScript (si escribe $< 51$ el badge se pone rojo `Reprobado`; si escribe $\ge 51$ se pone verde `Aprobado`).
* Botón grande flotante o inferior: `Guardar Todas las Notas` con confirmación SweetAlert2.
