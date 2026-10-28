# CU-24: Reporte de Estudiantes Graduados (Intermedio y Avanzado)
## Paquete 8: Reportes y Conciliación

---

### 1. Información General
* **Identificador:** CU-24
* **Nombre:** Nómina y Padrón de Estudiantes Aptos para Graduación y Certificación
* **Paquete en Menú AdminLTE:** `Paquete 8: Reportes y Finanzas -> CU-24: Graduados Intermedio/Avanzado`
* **Actor Principal:** `ADMINISTRADOR` / `ADMINISTRATIVO`
* **Tipo de Vista:** Vista Dedicada con Exportación PDF y Excel (`admin/reportes/graduados.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** Usuario con permiso `admin.reportes.graduados`.
* **Regla 1 (Criterio de Graduación Intermedia):** El estudiante debe haber cursado y aprobado con nota $\ge 51$ los **Módulos 1, 2, 3, 4, 5, 6, 7 y 8** de un mismo idioma.
* **Regla 2 (Criterio de Certificación Avanzada):** El estudiante debe haber aprobado adicionalmente el **Módulo 9**.
* **Regla 3 (Padrón Oficial para Autoridades):** Este reporte sirve de base legal para que el Instituto tramite los diplomas ante el Rectorado o Vicerrectorado de la Universidad.
* **Regla 4:** Incluye promedio ponderado general de egreso de los 8 o 9 módulos.

---

### 3. Especificación Técnica de Implementación

#### Rutas y Permisos (`routes/web.php`)
```php
Route::get('/admin/reportes/estudiantes-graduados', [App\Http\Controllers\ReporteController::class, 'estudiantes_graduados'])
    ->name('admin.reportes.graduados')->middleware('auth', 'can:admin.reportes.index');

Route::get('/admin/reportes/estudiantes-graduados/pdf', [App\Http\Controllers\ReporteController::class, 'estudiantes_graduados_pdf'])
    ->name('admin.reportes.graduados_pdf')->middleware('auth', 'can:admin.reportes.index');
```

#### Consulta de Graduados Eficiente
```php
public function estudiantes_graduados(Request $request)
{
    $idiomaId = $request->input('idioma_id', 1);
    $tipoGraduacion = $request->input('tipo', 'INTERMEDIO'); // 'INTERMEDIO' (Modulo 8) o 'AVANZADO' (Modulo 9)

    $moduloObjetivo = ($tipoGraduacion === 'AVANZADO') ? 9 : 8;

    // Buscar estudiantes que tengan aprobado el módulo objetivo
    $estudiantes = Estudiante::whereHas('inscripciones', function ($q) use ($idiomaId, $moduloObjetivo) {
        $q->whereHas('modulo', fn($m) => $m->where('idioma_id', $idiomaId)->where('numero_orden', $moduloObjetivo))
          ->whereHas('calificacion', fn($c) => $c->where('estado', 'APROBADO'));
    })
    ->with([
        'inscripciones.modulo',
        'inscripciones.calificacion'
    ])
    ->get()
    ->map(function ($est) {
        // Calcular promedio de egreso
        $est->promedio_egreso = round($est->inscripciones->avg(fn($i) => optional($i->calificacion)->nota_final ?? 0), 2);
        return $est;
    });

    return view('admin.reportes.graduados', compact('estudiantes', 'idiomaId', 'tipoGraduacion'));
}
```

#### Vista Blade Responsiva (`resources/views/admin/reportes/graduados.blade.php`)
* Pestañas para alternar entre:
  * 🎓 **Egresados Nivel Intermedio (Módulos 1 al 8)**
  * 📜 **Egresados Nivel Avanzado (Módulo 9)**
* Tabla con: N° Correlativo, CI, Código CEI, Apellidos y Nombres, Carrera de Origen, Promedio Ponderado, Fecha de Finalización.
* Botón de descarga de la **Nómina Oficial de Graduados en PDF**.
