# CU-21: Historial de Notas y Certificados del Estudiante (PDF)
## Paquete 7: Portal del Estudiante

---

### 1. Información General
* **Identificador:** CU-21
* **Nombre:** Consulta de Historial y Descarga de Documentos Oficiales en PDF
* **Paquete en Menú AdminLTE:** `Paquete 7: Mi Aula y Avance -> CU-21: Historial y Ficha (PDF)`
* **Actor Principal:** `ESTUDIANTE`
* **Tipo de Salida:** Descarga de Fichas y Certificados PDF (`barryvdh/laravel-dompdf`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** Usuario autenticado con rol `ESTUDIANTE`.
* **Regla 1 (Autogestión de Documentos):** El estudiante puede descargar en cualquier momento:
  1. Su **Ficha de Matrícula Oficial** del ciclo activo.
  2. Su **Historial Académico / Kárdex Personal**.
  3. Su **Certificado de Nivel Intermedio (Módulo 8)** una vez aprobado.
  4. Su **Certificado de Nivel Avanzado (Módulo 9)** una vez aprobado.
* **Regla 2 (Validación de Aprobación):** El botón de descarga del Certificado de Graduación solo se desbloquea si la nota del Módulo 8 o 9 está en estado `APROBADO`.

---

### 3. Especificación Técnica de Implementación

#### Rutas y Permisos (`routes/web.php`)
```php
Route::get('/estudiante/mis-documentos', [App\Http\Controllers\EstudiantePortalController::class, 'mis_documentos'])
    ->name('estudiante.documentos.index')
    ->middleware('auth', 'role:ESTUDIANTE');

Route::get('/estudiante/certificado-intermedio/pdf', [App\Http\Controllers\EstudiantePortalController::class, 'certificado_intermedio_pdf'])
    ->name('estudiante.certificado.intermedio')
    ->middleware('auth', 'role:ESTUDIANTE');

Route::get('/estudiante/certificado-avanzado/pdf', [App\Http\Controllers\EstudiantePortalController::class, 'certificado_avanzado_pdf'])
    ->name('estudiante.certificado.avanzado')
    ->middleware('auth', 'role:ESTUDIANTE');
```

#### Controlador (`EstudiantePortalController@mis_documentos`)
```php
public function mis_documentos()
{
    $estudiante = auth()->user()->estudiante;

    $inscripciones = Inscripcion::with(['modulo.idioma', 'paralelo.ciclo', 'calificacion'])
        ->where('estudiante_id', $estudiante->id)
        ->orderByDesc('id')
        ->get();

    $tieneIntermedioAprobado = $inscripciones->where('modulo.numero_orden', 8)->where('calificacion.estado', 'APROBADO')->isNotEmpty();
    $tieneAvanzadoAprobado = $inscripciones->where('modulo.numero_orden', 9)->where('calificacion.estado', 'APROBADO')->isNotEmpty();

    return view('estudiante.documentos', compact('estudiante', 'inscripciones', 'tieneIntermedioAprobado', 'tieneAvanzadoAprobado'));
}
```

#### Plantilla Blade de Certificado en PDF (`resources/views/estudiante/pdf/certificado_intermedio.blade.php`)
* Diseño apaisado (`landscape`) tipo Diploma Oficial con membrete de la Universidad e Instituto de Idiomas.
* Texto oficial: *"Por cuanto el/la estudiante [Nombres Apellidos] con C.I. [CI] ha cumplido satisfactoriamente con la aprobación de los 8 módulos correspondientes al plan curricular de [Idioma], se le confiere la certificación de NIVEL INTERMEDIO..."*.
* Espacios para sellos y firmas oficiales.
