# CU-16: Mis Grupos Asignados (Panel Docente)
## Paquete 6: Evaluación y Calificaciones

---

### 1. Información General
* **Identificador:** CU-16
* **Nombre:** Consulta de Grupos y Alumnos Asignados al Docente
* **Paquete en Menú AdminLTE:** `Paquete 6: Control de Notas -> CU-16: Mis Grupos Asignados`
* **Actor Principal:** `DOCENTE`
* **Tipo de Vista:** Vista Dedicada con Cards Responsivas (`docente/grupos/index.blade.php`, `show.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** El usuario debe tener rol `DOCENTE` y estar vinculado a un registro en `personals`.
* **Regla 1 (Aislamiento de Información):** Un docente solo puede ver los grupos que le fueron formalmente asignados (CU-09). Jamás puede ver alumnos o notas de otros docentes.
* **Regla 2 (Desglose por Modalidad):**
  * Para grupos **Presenciales:** Muestra el Aula y Pabellón en el campus.
  * Para grupos **Virtuales:** Muestra el Enlace oficial de Meet/Zoom y botón directo para iniciar sesión de clase.
* **Regla 3 (Acción Rápida):** Cada tarjeta de grupo cuenta con un botón directo a **Cargar Notas Finales** (CU-17) y **Descargar Lista de Asistencia** (CU-18).

---

### 3. Especificación Técnica de Implementación

#### Rutas y Permisos (`routes/web.php`)
```php
Route::get('/docente/grupos', [App\Http\Controllers\DocenteGrupoController::class, 'index'])
    ->name('docente.grupos.index')
    ->middleware('auth', 'role:DOCENTE');

Route::get('/docente/grupos/{id}', [App\Http\Controllers\DocenteGrupoController::class, 'show'])
    ->name('docente.grupos.show')
    ->middleware('auth', 'role:DOCENTE');
```

#### Controlador (`DocenteGrupoController@index`)
```php
public function index()
{
    $docente = auth()->user()->personal;
    if (!$docente) {
        abort(403, 'El usuario no tiene una ficha de docente vinculada.');
    }

    $asignaciones = Asignacion::with([
        'paralelo.modulo.idioma',
        'paralelo.ciclo',
        'paralelo.inscripciones.estudiante'
    ])
    ->where('personal_id', $docente->id)
    ->where('estado', 'ACTIVO')
    ->get();

    return view('docente.grupos.index', compact('asignaciones'));
}
```

#### Vista Blade Responsiva (`resources/views/docente/grupos/index.blade.php`)
* Grid de tarjetas adaptadas a móviles (`col-12 col-md-6 col-lg-4`):
  * Encabezado con badge distintivo: `PRESENCIAL 🏫` o `VIRTUAL 💻`.
  * Título: Idioma + Módulo (Ej. *Inglés - Módulo 2*).
  * Contador de estudiantes inscritos.
  * Botón primario: `Cargar Notas Finales`.
  * Botón secundario: `Ver Alumnos / Lista`.
