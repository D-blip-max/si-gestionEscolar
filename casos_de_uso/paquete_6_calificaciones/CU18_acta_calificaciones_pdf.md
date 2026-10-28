# CU-18: Cierre y Emisión de Actas Oficiales de Calificación (PDF)
## Paquete 6: Evaluación y Calificaciones

---

### 1. Información General
* **Identificador:** CU-18
* **Nombre:** Cierre Bimestral y Generación del Acta Oficial de Notas en PDF
* **Paquete en Menú AdminLTE:** `Paquete 6: Control de Notas -> CU-18: Actas Oficiales (PDF)`
* **Actor Principal:** `DOCENTE` / `ADMINISTRADOR`
* **Tipo de Salida:** Generación y Descarga de Documento Oficial en PDF (`barryvdh/laravel-dompdf`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** Todos los estudiantes del grupo deben tener una nota final asentada (CU-17).
* **Regla 1 (Cierre de Acta):** Una vez emitido el cierre de notas, el estado del grupo pasa a `BLOQUEADO` para evitar modificaciones extemporáneas sin aval administrativo.
* **Regla 2 (Estadísticas Oficiales):** El acta calcula automáticamente:
  * Total de estudiantes inscritos.
  * Cantidad y porcentaje de **Aprobados**.
  * Cantidad y porcentaje de **Reprobados**.
  * Promedio general del grupo.
* **Regla 3 (Firmas de Responsabilidad):** Espacio al pie para la firma del Docente titular y el Visto Bueno del Director del Instituto.

---

### 3. Especificación Técnica de Implementación

#### Rutas y Permisos (`routes/web.php`)
```php
Route::get('/docente/actas/grupo/{paralelo_id}/pdf', [App\Http\Controllers\CalificacionController::class, 'acta_pdf'])
    ->name('docente.actas.pdf')
    ->middleware('auth');
```

#### Controlador (`CalificacionController@acta_pdf`)
```php
public function acta_pdf($paralelo_id)
{
    $paralelo = Paralelo::with([
        'modulo.idioma',
        'ciclo',
        'asignacion.docente',
        'inscripciones' => function ($q) {
            $q->where('estado', 'ACTIVA')->orderBy('estudiantes.apellidos');
        },
        'inscripciones.estudiante',
        'inscripciones.calificacion'
    ])->findOrFail($paralelo_id);

    // Verificación de autorización: Solo el docente asignado o un admin pueden emitir el acta
    if (auth()->user()->hasRole('DOCENTE') && $paralelo->asignacion->personal_id !== auth()->user()->personal->id) {
        abort(403, 'No autorizado para emitir el acta de este grupo.');
    }

    $configuracion = Configuracion::first();

    // Cálculo de métricas
    $total = $paralelo->inscripciones->count();
    $aprobados = $paralelo->inscripciones->filter(fn($i) => optional($i->calificacion)->estado === 'APROBADO')->count();
    $reprobados = $total - $aprobados;
    $promedio = $total > 0 ? round($paralelo->inscripciones->avg(fn($i) => optional($i->calificacion)->nota_final ?? 0), 2) : 0;

    $pdf = Pdf::loadView('docente.calificaciones.acta_pdf', compact('paralelo', 'configuracion', 'total', 'aprobados', 'reprobados', 'promedio'))
        ->setPaper('letter', 'portrait');

    return $pdf->stream('Acta_Notas_' . $paralelo->modulo->nombre . '_' . $paralelo->nombre . '.pdf');
}
```

#### Plantilla Blade para PDF (`resources/views/docente/calificaciones/acta_pdf.blade.php`)
* Cabecera oficial: Membrete del Instituto CEI y escudo universitario.
* Tabla con: N° Correlativo, CI, Apellidos y Nombres, **Nota Numérica**, **Nota Literal** (Ej: *Ochenta y Cinco*), Resultado (`APROBADO` / `REPROBADO`).
* Resumen estadístico: Cuadro comparativo de rendimiento.
* Pies de firma con aclaración de nombre y CI del docente.
