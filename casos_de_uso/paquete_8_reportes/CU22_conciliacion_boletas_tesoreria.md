# CU-22: Conciliación de Boletas para Tesorería Universitaria
## Paquete 8: Reportes y Conciliación

---

### 1. Información General
* **Identificador:** CU-22
* **Nombre:** Reporte de Conciliación y Auditoría de Boletas Bancarias para Tesorería
* **Paquete en Menú AdminLTE:** `Paquete 8: Reportes y Finanzas -> CU-22: Conciliación de Boletas`
* **Actor Principal:** `ADMINISTRATIVO` / `ADMINISTRADOR`
* **Tipo de Vista:** Vista de Filtrado con Exportación a Excel y PDF (`admin/reportes/conciliacion_boletas.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** Usuario con permiso `admin.reportes.conciliacion`.
* **Regla 1 (Cruce con Tesorería Central):** Permite generar el reporte que el Instituto envía periódicamente a la Dirección Administrativa y Financiera (DAF) / Tesorería de la Universidad para conciliar los depósitos bancarios de los alumnos.
* **Regla 2 (Filtros Avanzados):**
  * Rango de fechas de depósito.
  * Entidad bancaria o caja receptora.
  * Estado de la boleta (`UTILIZADA`, `DISPONIBLE`, `ANULADA`).
  * Idioma o ciclo bimestral.
* **Regla 3 (Totales y Sumatorias):** El reporte calcula automáticamente el importe total recaudado ($X\text{ Bs/USD}$) y la cantidad total de boletas fiscalizadas.
* **Regla 4 (Exportabilidad):** Botones directos para exportar en formato **Excel (.xlsx)** y **PDF oficial**.

---

### 3. Especificación Técnica de Implementación

#### Rutas y Permisos (`routes/web.php`)
```php
Route::get('/admin/reportes/conciliacion-boletas', [App\Http\Controllers\ReporteController::class, 'conciliacion_boletas'])
    ->name('admin.reportes.conciliacion')->middleware('auth', 'can:admin.reportes.index');

Route::get('/admin/reportes/conciliacion-boletas/pdf', [App\Http\Controllers\ReporteController::class, 'conciliacion_boletas_pdf'])
    ->name('admin.reportes.conciliacion_pdf')->middleware('auth', 'can:admin.reportes.index');
```

#### Consulta de Conciliación Optimizada
```php
public function conciliacion_boletas(Request $request)
{
    $query = BoletaPago::with(['estudiante', 'inscripcion.modulo.idioma', 'usuarioRegistrador']);

    if ($request->filled('fecha_desde')) {
        $query->whereDate('fecha_deposito', '>=', $request->fecha_desde);
    }
    if ($request->filled('fecha_hasta')) {
        $query->whereDate('fecha_deposito', '<=', $request->fecha_hasta);
    }
    if ($request->filled('banco_entidad')) {
        $query->where('banco_entidad', $request->banco_entidad);
    }
    if ($request->filled('estado')) {
        $query->where('estado', $request->estado);
    }

    $boletas = $query->orderBy('fecha_deposito', 'desc')->get();
    $totalRecaudado = $boletas->sum('monto');
    $cantidadBoletas = $boletas->count();

    return view('admin.reportes.conciliacion_boletas', compact('boletas', 'totalRecaudado', 'cantidadBoletas'));
}
```

#### Vista Blade Responsiva (`resources/views/admin/reportes/conciliacion_boletas.blade.php`)
* Tarjetas superiores con KPIs: `Total Fiscalizado`, `Boletas Conciliadas`, `Monto Total`.
* Filtros interactivos colapsables en acordeón.
* Tabla DataTable con botones de exportación `Excel`, `PDF` e `Imprimir`.
