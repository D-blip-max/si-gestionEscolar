# CU-12: Kárdex Académico del Estudiante
## Paquete 4: Padrón de Estudiantes y Boletas

---

### 1. Información General
* **Identificador:** CU-12
* **Nombre:** Consulta y Emisión del Kárdex / Historial Académico Modular
* **Paquete en Menú AdminLTE:** `Paquete 4: Estudiantes y Boletas -> CU-12: Kárdex Académico`
* **Actor Principal:** `ADMINISTRATIVO` / `ADMINISTRADOR`
* **Tipo de Vista:** Vista Dedicada (`admin/estudiantes/kardex.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** El estudiante debe existir en el sistema.
* **Regla 1 (Trazabilidad Modular):** Muestra el historial cronológico de todos los módulos cursados, clasificados por idioma (Inglés o Guaraní).
* **Regla 2 (Detalle por Módulo):** Cada fila del kárdex detalla:
  * Ciclo Bimestral y Gestión.
  * Módulo (1 al 9) y Nivel (Básico, Intermedio, Avanzado).
  * **Modalidad en que cursó:** `Presencial` (con su aula física) o `Virtual` (con su enlace).
  * Docente a cargo.
  * **Nota Final Definitiva** [0 - 100].
  * Estado: `APROBADO` ($\ge 51$) o `REPROBADO` ($< 51$).
* **Regla 3 (Hito de Titulación):** Resalta visualmente si el alumno ya completó los módulos 1 al 8 para habilitar su emisión de **Título Intermedio**, o módulo 9 para **Avanzado**.
* **Regla 4 (Descarga PDF):** Botón para generar el Kárdex Oficial en PDF firmado con DomPDF.

---

### 3. Especificación Técnica de Implementación

#### Rutas y Permisos (`routes/web.php`)
```php
Route::get('/admin/estudiantes/{id}/kardex', [App\Http\Controllers\EstudianteController::class, 'kardex'])
    ->name('admin.estudiantes.kardex')->middleware('auth', 'can:admin.estudiantes.index');

Route::get('/admin/estudiantes/{id}/kardex-pdf', [App\Http\Controllers\EstudianteController::class, 'kardex_pdf'])
    ->name('admin.estudiantes.kardex_pdf')->middleware('auth', 'can:admin.estudiantes.index');
```

#### Consulta Optimizada contra N+1
```php
public function kardex($id)
{
    $estudiante = Estudiante::with([
        'inscripciones' => function ($q) {
            $q->orderBy('created_at', 'asc');
        },
        'inscripciones.modulo.idioma',
        'inscripciones.paralelo.ciclo',
        'inscripciones.paralelo.asignacion.docente',
        'inscripciones.calificacion',
        'inscripciones.boletaPago'
    ])->findOrFail($id);

    // Calcular estadísticas
    $modulosAprobados = $estudiante->inscripciones->filter(fn($i) => optional($i->calificacion)->estado === 'APROBADO')->count();
    $aptoGraduacionIntermedia = $estudiante->inscripciones->where('modulo.numero_orden', 8)->where('calificacion.estado', 'APROBADO')->isNotEmpty();

    return view('admin.estudiantes.kardex', compact('estudiante', 'modulosAprobados', 'aptoGraduacionIntermedia'));
}
```

#### Vista Blade Responsiva (`resources/views/admin/estudiantes/kardex.blade.php`)
* Cabecera con datos personales del estudiante y avatar.
* **Barra de Progreso Modular:** Indicador visual (1 al 9).
* Tabla con filas coloreadas: Verde (`APROBADO`), Rojo (`REPROBADO`), Azul (`CURSANDO`).
* Botón destacado `Imprimir Kárdex Oficial (PDF)`.
