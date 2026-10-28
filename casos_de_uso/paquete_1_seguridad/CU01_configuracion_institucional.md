# CU-01: Gestión de Configuración Institucional
## Paquete 1: Administración y Seguridad

---

### 1. Información General
* **Identificador:** CU-01
* **Nombre:** Configuración de Datos Institucionales del Instituto (CEI)
* **Paquete en Menú AdminLTE:** `Paquete 1: Seguridad y Config. -> CU-01: Config. Institución`
* **Actor Principal:** `ADMINISTRADOR`
* **Tipo de Vista:** Vista Dedicada (`admin/configuracion/index.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** El usuario debe estar autenticado y tener el permiso Spatie `admin.configuracion.index`.
* **Regla 1:** Solo puede existir un único registro institucional activo (o edición sobre el registro ID 1).
* **Regla 2 (Logo):** El logo institucional debe almacenarse en `public/storage/logos/` y debe optimizarse para cabeceras de reportes y Fichas de Matrícula PDF.
* **Regla 3:** Los datos institucionales (Nombre, Sigla, Rector/Director, Teléfonos, Correo oficial) se utilizarán dinámicamente en los membretes de todas las Actas y Fichas PDF.

---

### 3. Especificación Técnica de Implementación

#### Rutas y Permisos (`routes/web.php`)
```php
Route::get('/admin/configuracion', [App\Http\Controllers\ConfiguracionController::class, 'index'])
    ->name('admin.configuracion.index')
    ->middleware('auth', 'can:admin.configuracion.index');

Route::post('/admin/configuracion', [App\Http\Controllers\ConfiguracionController::class, 'storeOrUpdate'])
    ->name('admin.configuracion.store')
    ->middleware('auth', 'can:admin.configuracion.crear');
```

#### Modelo Eloquent (`App\Models\Configuracion`)
```php
class Configuracion extends Model {
    protected $table = 'configuracions';
    protected $fillable = [
        'nombre_instituto',
        'sigla',
        'director',
        'direccion',
        'telefono',
        'correo_contacto',
        'logo',
    ];
}
```

#### FormRequest (`App\Http\Requests\UpdateConfiguracionRequest`)
* `nombre_instituto`: `required|string|max:255`
* `sigla`: `required|string|max:20`
* `director`: `required|string|max:255`
* `telefono`: `nullable|string|max:30`
* `correo_contacto`: `required|email|max:255`
* `logo`: `nullable|image|mimes:jpeg,png,jpg,webp|max:2048`

#### Controlador (`App\Http\Controllers\ConfiguracionController`)
* `index()`: Consulta el primer registro con `Configuracion::first()` y envía a la vista.
* `storeOrUpdate(UpdateConfiguracionRequest $request)`:
  1. Si se envía archivo de imagen `logo`, eliminar el anterior si existe y guardar en disco con nombre único sanitizado.
  2. Actualizar o crear registro mediante `Configuracion::updateOrCreate(['id' => 1], $datos)`.
  3. Redireccionar con SweetAlert2 (`mensaje`, `icono: success`).

#### Vista Blade Responsiva (`resources/views/admin/configuracion/index.blade.php`)
* Formulario estructurado en Bootstrap Card de AdminLTE con preview dinámico de la imagen del logo cargada.
* Campos organizados en grid responsivo (`col-12 col-md-6`).
