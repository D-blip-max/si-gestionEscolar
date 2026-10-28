# CU-10: Padrón y Registro de Estudiantes
## Paquete 4: Padrón de Estudiantes y Boletas

---

### 1. Información General
* **Identificador:** CU-10
* **Nombre:** Registro y Administración del Padrón de Estudiantes Autónomos
* **Paquete en Menú AdminLTE:** `Paquete 4: Estudiantes y Boletas -> CU-10: Padrón de Estudiantes`
* **Actor Principal:** `ADMINISTRATIVO` / `ADMINISTRADOR`
* **Tipo de Vista:** Vistas Dedicadas (`admin/estudiantes/index.blade.php`, `create.blade.php`, `edit.blade.php`)

---

### 2. Reglas de Negocio y Precondiciones
* **Precondición:** Usuario autenticado con rol `ADMINISTRATIVO` o `ADMINISTRADOR`.
* **Regla 1 (Estudiante Autónomo - Sin Padres de Familia):** El estudiante es universitario o adulto; no se registran padres de familia ni apoderados.
* **Regla 2 (Datos Universitarios):** Se registra: CI único, Nombres, Apellidos, Teléfono/WhatsApp, Correo, Carrera o Facultad Universitaria de origen (o Particular).
* **Regla 3 (Código de Estudiante):** El sistema genera automáticamente un código único institucional (Ej: `CEI-2026-0045`).
* **Regla 4 (Cuenta de Usuario):** Se genera automáticamente una cuenta en `users` con el rol Spatie `ESTUDIANTE` para que pueda consultar su avance y entrar a clases virtuales.

---

### 3. Especificación Técnica de Implementación

#### Migración de Base de Datos (`create_estudiantes_table.php`)
```php
Schema::create('estudiantes', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
    $table->string('codigo_estudiante', 30)->unique();
    $table->string('nombres', 100);
    $table->string('apellidos', 100);
    $table->string('ci', 20)->unique();
    $table->date('fecha_nacimiento');
    $table->string('telefono', 30);
    $table->string('carrera_facultad', 150)->nullable(); // Ej: "Ingeniería de Sistemas" o "Particular"
    $table->string('direccion', 255)->nullable();
    $table->string('foto', 255)->nullable();
    $table->enum('estado', ['ACTIVO', 'INACTIVO'])->default('ACTIVO');
    $table->timestamps();

    $table->index(['ci', 'codigo_estudiante']);
});
```

#### Modelo Eloquent (`App\Models\Estudiante`)
```php
class Estudiante extends Model {
    protected $fillable = [
        'user_id', 'codigo_estudiante', 'nombres', 'apellidos',
        'ci', 'fecha_nacimiento', 'telefono', 'carrera_facultad',
        'direccion', 'foto', 'estado'
    ];

    public function usuario() { return $this->belongsTo(User::class, 'user_id'); }
    public function inscripciones() { return $this->hasMany(Inscripcion::class); }
    public function boletas() { return $this->hasMany(BoletaPago::class); }
}
```

#### FormRequest (`StoreEstudianteRequest`)
* `nombres`: `required|string|max:100`
* `apellidos`: `required|string|max:100`
* `ci`: `required|string|max:20|unique:estudiantes,ci`
* `email`: `required|email|max:255|unique:users,email`
* `telefono`: `required|string|max:30`
* `carrera_facultad`: `nullable|string|max:150`

#### Registro Atómico (`DB::transaction`)
```php
DB::transaction(function () use ($request) {
    // 1. Crear usuario para el estudiante
    $user = User::create([
        'name' => $request->nombres . ' ' . $request->apellidos,
        'email' => $request->email,
        'password' => Hash::make($request->ci), // Clave inicial: su CI
    ]);
    $user->assignRole('ESTUDIANTE');

    // 2. Generar código CEI correlativo
    $year = date('Y');
    $count = Estudiante::whereYear('created_at', $year)->count() + 1;
    $codigo = 'CEI-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

    // 3. Crear registro de estudiante
    Estudiante::create(array_merge($request->validated(), [
        'user_id' => $user->id,
        'codigo_estudiante' => $codigo
    ]));
});
```

#### Vista Blade Responsiva (`resources/views/admin/estudiantes/index.blade.php`)
* Búsqueda en tiempo real con DataTables Server-Side (CI, Nombre, Código).
* Botón de acción directo para ver Kárdex Académico (CU-12) y Nueva Inscripción (CU-13).
