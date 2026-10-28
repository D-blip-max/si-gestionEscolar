# CU-23: Reporte de Inscritos por Idioma y Modalidad
## Paquete 8: Reportes y Conciliación

---

### 1. Información General
* **Identificador:** CU-23
* **Nombre:** Estadísticas de Matrículas Desglosadas por Idioma, Nivel y Modalidad (Presencial vs Virtual)
* **Paquete en Menú AdminLTE:** `Paquete 8: Reportes y Finanzas -> CU-23: Inscritos por Modalidad`
* **Actor Principal:** `ADMINISTRADOR` / `ADMINISTRATIVO`
* **Tipo de Vista:** Dashboard Estadístico con Gráficos Chart.js y Exportación PDF (`admin/reportes/inscritos_modalidad.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** Usuario con permiso `admin.reportes.inscritos`.
* **Regla 1 (Comparativa de Modalidades):** Presenta el análisis comparativo de cuántos alumnos optan por la modalidad **Presencial** frente a la modalidad **Virtual**.
* **Regla 2 (Filtros Multidimensionales):**
  * Por Ciclo Bimestral y Gestión.
  * Por Idioma (Inglés vs Guaraní).
  * Por Nivel (Básico, Intermedio, Avanzado).
  * Por Turno (Mañana, Tarde, Noche).
* **Regla 3 (Gráficos Interactivos):** Gráfico de pastel (Doughnut Chart) de Modalidades y gráfico de barras de módulos con mayor afluencia.

---

### 3. Especificación Técnica de Implementación

#### Rutas y Permisos (`routes/web.php`)
```php
Route::get('/admin/reportes/inscritos-modalidad', [App\Http\Controllers\ReporteController::class, 'inscritos_modalidad'])
    ->name('admin.reportes.inscritos')->middleware('auth', 'can:admin.reportes.index');

Route::get('/admin/reportes/inscritos-modalidad/pdf', [App\Http\Controllers\ReporteController::class, 'inscritos_modalidad_pdf'])
    ->name('admin.reportes.inscritos_pdf')->middleware('auth', 'can:admin.reportes.index');
```

#### Agrupación de Datos en el Controlador
```php
public function inscritos_modalidad(Request $request)
{
    $cicloId = $request->input('ciclo_id', CicloBimestral::where('estado', 'ACTIVO')->value('id'));

    $inscripciones = Inscripcion::with(['modulo.idioma', 'paralelo'])
        ->where('estado', 'ACTIVA')
        ->when($cicloId, fn($q) => $q->whereHas('paralelo', fn($p) => $p->where('ciclo_id', $cicloId)))
        ->get();

    // Conteo por modalidad
    $totalPresencial = $inscripciones->where('paralelo.modalidad', 'PRESENCIAL')->count();
    $totalVirtual = $inscripciones->where('paralelo.modalidad', 'VIRTUAL')->count();

    // Conteo por idioma
    $porIdioma = $inscripciones->groupBy('modulo.idioma.nombre')->map->count();

    // Conteo por turno
    $porTurno = $inscripciones->groupBy('paralelo.turno')->map->count();

    return view('admin.reportes.inscritos_modalidad', compact('inscripciones', 'totalPresencial', 'totalVirtual', 'porIdioma', 'porTurno', 'cicloId'));
}
```

#### Vista Blade Responsiva (`resources/views/admin/reportes/inscritos_modalidad.blade.php`)
* Tarjetas comparativas:
  * 🏫 Presencial: `$totalPresencial` alumnos.
  * 💻 Virtual: `$totalVirtual` alumnos.
* Gráficos dinámicos con Chart.js integrados con AdminLTE.
* Tabla de detalle con opción de exportar a Excel y generar PDF oficial.
