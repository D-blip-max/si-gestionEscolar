# CU-19: Mi Módulo Activo y Acceso a Clases
## Paquete 7: Portal del Estudiante

---

### 1. Información General
* **Identificador:** CU-19
* **Nombre:** Consulta del Módulo en Curso y Acceso a Clases (Presencial / Meet)
* **Paquete en Menú AdminLTE:** `Paquete 7: Mi Aula y Avance -> CU-19: Mi Módulo y Clases`
* **Actor Principal:** `ESTUDIANTE`
* **Tipo de Vista:** Dashboard Móvil / Tarjeta Principal (`estudiante/modulo_activo.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** Usuario autenticado con rol `ESTUDIANTE` y matrícula `ACTIVA` en el ciclo actual.
* **Regla 1 (Acceso Inmediato a Clases):**
  * Si cursa en **Modalidad Presencial:** Muestra tarjeta destacada con el Pabellón, Piso y Número de Aula física.
  * Si cursa en **Modalidad Virtual:** Muestra botón táctil grande **`Entrar a Clase en Google Meet / Zoom`**, que abre el enlace oficial en una nueva pestaña del navegador o en la app móvil.
* **Regla 2 (Datos del Curso):** Muestra el nombre del Docente, Horario de clases, Turno y fechas de inicio y fin del ciclo bimestral.
* **Regla 3 (Sin Distracciones):** El estudiante no tiene acceso a menús administrativos, de cobranza ni de docentes.

---

### 3. Especificación Técnica de Implementación

#### Rutas y Permisos (`routes/web.php`)
```php
Route::get('/estudiante/modulo-activo', [App\Http\Controllers\EstudiantePortalController::class, 'modulo_activo'])
    ->name('estudiante.clases.index')
    ->middleware('auth', 'role:ESTUDIANTE');
```

#### Controlador (`EstudiantePortalController@modulo_activo`)
```php
public function modulo_activo()
{
    $estudiante = auth()->user()->estudiante;
    if (!$estudiante) {
        abort(403, 'No tiene una ficha de estudiante asociada.');
    }

    // Obtener la inscripción del ciclo activo
    $inscripcionActual = Inscripcion::with([
        'modulo.idioma',
        'paralelo.ciclo',
        'paralelo.asignacion.docente',
        'calificacion'
    ])
    ->where('estudiante_id', $estudiante->id)
    ->where('estado', 'ACTIVA')
    ->whereHas('paralelo.ciclo', fn($q) => $q->where('estado', 'ACTIVO'))
    ->first();

    return view('estudiante.modulo_activo', compact('estudiante', 'inscripcionActual'));
}
```

#### Vista Blade Responsiva (`resources/views/estudiante/modulo_activo.blade.php`)
* Tarjeta principal diseñada con estilo App Móvil:
  * Encabezado con gradiente azul/verde: `Módulo Actual en Curso`.
  * Nombre del Idioma (con bandera o icono representativo) y Módulo (Ej. *Inglés - Módulo 3*).
  * Si es **Virtual**:
    ```html
    <a href="{{ $inscripcionActual->paralelo->aula_o_enlace }}" target="_blank" class="btn btn-success btn-lg btn-block shadow">
        <i class="fas fa-video"></i> ENTRAR A MI CLASE VIRTUAL
    </a>
    ```
  * Si es **Presencial**:
    ```html
    <div class="alert alert-info">
        <h5><i class="fas fa-map-marker-alt"></i> Aula Física Asignada:</h5>
        <b>{{ $inscripcionActual->paralelo->aula_o_enlace }}</b>
    </div>
    ```
