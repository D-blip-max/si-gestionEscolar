# CU-14: Emisión de Ficha Oficial de Matrícula (PDF)
## Paquete 5: Inscripciones y Matrícula

---

### 1. Información General
* **Identificador:** CU-14
* **Nombre:** Generación de Ficha / Comprobante Oficial de Inscripción en PDF
* **Paquete en Menú AdminLTE:** `Paquete 5: Inscripción Modular -> CU-14: Listado de Matrículas (Acción Imprimir PDF)`
* **Actor Principal:** `ADMINISTRATIVO` / `ESTUDIANTE`
* **Tipo de Salida:** Generación y Descarga de Documento PDF con DomPDF (`barryvdh/laravel-dompdf`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** La inscripción debe estar en estado `ACTIVA`.
* **Regla 1 (Membrete Institucional):** El PDF debe tomar dinámicamente el logo, nombre y datos oficiales desde el registro institucional (CU-01).
* **Regla 2 (Datos Explícitos de Modalidad):**
  * Si es **Presencial:** El comprobante imprime en negrita el **Pabellón y Aula Física Asignada**.
  * Si es **Virtual:** El comprobante imprime la **Plataforma (Google Meet / Zoom)** y el enlace o instrucciones oficiales de conexión.
* **Regla 3 (Respaldo Financiero):** Imprime el Número de Boleta de Depósito Universitario y el monto registrado.
* **Regla 4:** Espacio oficial para firma del administrativo responsable y sello del Instituto.

---

### 3. Especificación Técnica de Implementación

#### Rutas y Permisos (`routes/web.php`)
```php
Route::get('/admin/inscripciones/{id}/pdf', [App\Http\Controllers\InscripcionController::class, 'pdf_matricula'])
    ->name('admin.inscripciones.pdf')
    ->middleware('auth');
```

#### Controlador (`InscripcionController@pdf_matricula`)
```php
public function pdf_matricula($id)
{
    $inscripcion = Inscripcion::with([
        'estudiante',
        'modulo.idioma',
        'paralelo.ciclo',
        'paralelo.asignacion.docente',
        'boletaPago'
    ])->findOrFail($id);

    $configuracion = Configuracion::first();

    $pdf = Pdf::loadView('admin.inscripciones.pdf_matricula', compact('inscripcion', 'configuracion'))
        ->setPaper('letter', 'portrait');

    return $pdf->stream('Ficha_Matricula_' . $inscripcion->estudiante->ci . '.pdf');
}
```

#### Plantilla Blade para PDF (`resources/views/admin/inscripciones/pdf_matricula.blade.php`)
* Estilos CSS limpios compatibles con DomPDF (fuente `Helvetica`, tablas con bordes colapsados, cero JavaScript).
* Recuadro de datos del estudiante: CI, Código CEI, Nombre completo, Carrera universitaria.
* Recuadro académico: Idioma, Módulo, Nivel, Ciclo Bimestral, Turno y Horario.
* **Recuadro destacado de Modalidad y Ubicación:**
  * `[X] MODALIDAD PRESENCIAL - AULA: Aula 102 (Pabellón A)`
  * `[X] MODALIDAD VIRTUAL - ENLACE: https://meet.google.com/xxx-xxxx-xxx`
* Código de verificación y datos del comprobante bancario.
