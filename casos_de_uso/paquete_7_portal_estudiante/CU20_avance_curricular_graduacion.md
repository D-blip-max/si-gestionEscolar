# CU-20: Avance Curricular y Ruta de Graduación
## Paquete 7: Portal del Estudiante

---

### 1. Información General
* **Identificador:** CU-20
* **Nombre:** Visualizador Gráfico de la Ruta de Aprendizaje y Avance hacia la Graduación
* **Paquete en Menú AdminLTE:** `Paquete 7: Mi Aula y Avance -> CU-20: Avance a Graduación`
* **Actor Principal:** `ESTUDIANTE`
* **Tipo de Vista:** Vista Gráfica Interactiva (`estudiante/avance_curricular.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** Usuario autenticado con rol `ESTUDIANTE`.
* **Regla 1 (Ruta de Aprendizaje):** Presenta de forma visual los 9 módulos estructurados en sus 3 niveles:
  * 🟡 **Nivel Básico (Módulos 1 al 4):** Base comunicativa.
  * 🔵 **Nivel Intermedio (Módulos 5 al 8):** Dominio funcional. Al aprobar el Módulo 8, se activa el distintivo de **Graduación / Titulación Intermedia**.
  * 🟣 **Nivel Avanzado (Módulo 9):** Especialización y fluidez profesional.
* **Regla 2 (Estados Visuales de cada Módulo):**
  * `APROBADO`: Tarjeta verde con check ✓ y la nota final obtenida.
  * `CURSANDO`: Tarjeta azul palpitante con icono de reloj ⏳.
  * `PENDIENTE`: Tarjeta gris con candado 🔒.
* **Regla 3 (Barra Porcentual):** Muestra el porcentaje de avance hacia la meta intermedia (ej. 6 de 8 módulos = 75% hacia el título).

---

### 3. Especificación Técnica de Implementación

#### Rutas y Permisos (`routes/web.php`)
```php
Route::get('/estudiante/avance-curricular', [App\Http\Controllers\EstudiantePortalController::class, 'avance_curricular'])
    ->name('estudiante.avance.index')
    ->middleware('auth', 'role:ESTUDIANTE');
```

#### Controlador (`EstudiantePortalController@avance_curricular`)
```php
public function avance_curricular()
{
    $estudiante = auth()->user()->estudiante;

    // Obtener todas las inscripciones históricas con calificaciones
    $historial = Inscripcion::with(['modulo.idioma', 'calificacion'])
        ->where('estudiante_id', $estudiante->id)
        ->get();

    // Módulos base (1 al 9) para Inglés (o el idioma que curse el alumno)
    $idiomaId = optional($historial->first())->modulo->idioma_id ?? 1;
    $modulos = Modulo::where('idioma_id', $idiomaId)->orderBy('numero_orden')->get();

    return view('estudiante.avance_curricular', compact('estudiante', 'modulos', 'historial'));
}
```

#### Vista Blade Responsiva (`resources/views/estudiante/avance_curricular.blade.php`)
* Timeline visual o Stepper responsivo con Bootstrap:
  * **Hito Intermedio (Módulo 8):** Insignia dorada `🎓 Meta: Titulación Intermedia`.
  * **Hito Avanzado (Módulo 9):** Insignia púrpura `📜 Certificación Avanzada`.
* Cada nodo del stepper permite hacer clic para ver la fecha y ciclo en que fue aprobado.
