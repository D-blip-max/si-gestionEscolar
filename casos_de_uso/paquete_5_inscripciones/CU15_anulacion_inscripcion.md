# CU-15: Anulación y Reversión de Inscripciones
## Paquete 5: Inscripciones y Matrícula

---

### 1. Información General
* **Identificador:** CU-15
* **Nombre:** Anulación y Reversión Controlada de Inscripción Modular
* **Paquete en Menú AdminLTE:** `Paquete 5: Inscripción Modular -> CU-15: Historial y Anulaciones`
* **Actor Principal:** `ADMINISTRATIVO` / `ADMINISTRADOR`
* **Tipo de Vista:** Modal de Justificación con confirmación SweetAlert2

---

### 2. Reglas de Negocio y Precondiciones (Reversión ACID)
* **Precondición:** La inscripción debe estar en estado `ACTIVA` y pertenecer al ciclo bimestral vigente.
* **Regla 1 (Bloqueo por Calificación Emitida):** Si el docente ya cerró o registró una nota final definitiva distinta de 0 en el módulo, la inscripción no puede anularse desde ventanilla sin autorización especial de Dirección.
* **Regla 2 (Restitución de Boleta):** Al anularse la inscripción, la boleta de pago bancario vuelve automáticamente al estado `DISPONIBLE` para que pueda usarse en otro grupo o turno.
* **Regla 3 (Liberación de Cupo):** Se decrementa el `cupo_ocupado` en el paralelo para devolver el cupo al sistema.
* **Regla 4 (Justificación Obligatoria):** Debe registrarse el motivo de la anulación (cambio de turno, error de tipeo, etc.).

---

### 3. Especificación Técnica de Implementación

#### Rutas y Permisos (`routes/web.php`)
```php
Route::post('/admin/inscripciones/{id}/anular', [App\Http\Controllers\InscripcionController::class, 'anular'])
    ->name('admin.inscripciones.anular')
    ->middleware('auth', 'can:admin.inscripciones.destroy');
```

#### Transacción Atómica de Reversión (`InscripcionController@anular`)
```php
public function anular(Request $request, $id)
{
    $request->validate([
        'motivo_anulacion' => 'required|string|min:10|max:255',
    ]);

    return DB::transaction(function () use ($request, $id) {
        $inscripcion = Inscripcion::lockForUpdate()->findOrFail($id);

        // 1. Validar que no tenga nota final calificada
        if ($inscripcion->calificacion && $inscripcion->calificacion->nota_final > 0) {
            return back()->withErrors(['error' => 'No se puede anular: El estudiante ya tiene calificación asentada en este módulo.']);
        }

        // 2. Liberar boleta bancaria
        if ($inscripcion->boletaPago) {
            $inscripcion->boletaPago->update(['estado' => 'DISPONIBLE']);
        }

        // 3. Decrementar cupo en paralelo
        $paralelo = Paralelo::lockForUpdate()->find($inscripcion->paralelo_id);
        if ($paralelo && $paralelo->cupo_ocupado > 0) {
            $paralelo->decrement('cupo_ocupado');
        }

        // 4. Cambiar estado de inscripción y calificación
        $inscripcion->update([
            'estado' => 'ANULADA',
            'motivo_anulacion' => $request->motivo_anulacion
        ]);

        if ($inscripcion->calificacion) {
            $inscripcion->calificacion->update(['estado' => 'ANULADA']);
        }

        return redirect()->route('admin.inscripciones.index')
            ->with('mensaje', 'Inscripción anulada y boleta liberada correctamente')
            ->with('icono', 'success');
    });
}
```

#### Modal con SweetAlert2 (`resources/views/admin/inscripciones/index.blade.php`)
* Botón rojo `Anular` que despliega modal solicitando confirmación y campo de texto para el motivo de anulación.
