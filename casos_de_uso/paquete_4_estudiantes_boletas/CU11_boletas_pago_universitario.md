# CU-11: Registro y Fiscalización de Boletas de Pago
## Paquete 4: Padrón de Estudiantes y Boletas

---

### 1. Información General
* **Identificador:** CU-11
* **Nombre:** Registro y Verificación de Boletas de Depósito Universitario (Cero Efectivo)
* **Paquete en Menú AdminLTE:** `Paquete 4: Estudiantes y Boletas -> CU-11: Registro de Boletas`
* **Actor Principal:** `ADMINISTRATIVO`
* **Tipo de Vista:** Vistas con Modals CRUD (`admin/boletas/index.blade.php`, modal `#modal-nueva-boleta`)

---

### 2. Reglas de Negocio y Precondiciones
* **Regla 1 (Cero Manejo de Dinero):** El instituto no cobra dinero en caja. Los estudiantes depositan en cuentas bancarias autorizadas o en Tesorería Universitaria.
* **Regla 2 (Unicidad Estricta):** El `numero_boleta` (número de comprobante de depósito o transferencia) debe ser **único** en todo el sistema.
* **Regla 3 (Evidencia Digital):** Obligatorio o recomendado subir fotografía o escaneo del voucher bancario (`archivo_comprobante`).
* **Regla 4 (Estados de Boleta):**
  * `DISPONIBLE`: Boleta registrada y validada, lista para ser consumida en una inscripción.
  * `UTILIZADA`: Ya asociada a una matrícula modular.
  * `ANULADA`: Descartada por error de carga o rechazo de tesorería.
* **Regla 5 (Blindaje con Trigger MySQL):** El Trigger `tr_validar_boleta_before_insert` impide a nivel motor que una boleta `UTILIZADA` sea consumida dos veces.

---

### 3. Especificación Técnica de Implementación

#### Migración de Base de Datos (`create_boletas_pago_table.php`)
```php
Schema::create('boletas_pago', function (Blueprint $table) {
    $table->id();
    $table->foreignId('estudiante_id')->constrained('estudiantes')->cascadeOnDelete();
    $table->string('numero_boleta', 50)->unique();
    $table->decimal('monto', 8, 2);
    $table->date('fecha_deposito');
    $table->string('banco_entidad', 100); // Ej: "Banco Unión", "Caja Central Universidad"
    $table->string('concepto', 150); // Ej: "Matrícula Inglés Módulo 1"
    $table->string('archivo_comprobante', 255)->nullable(); // Foto/PDF del voucher
    $table->enum('estado', ['DISPONIBLE', 'UTILIZADA', 'ANULADA'])->default('DISPONIBLE');
    $table->foreignId('registrado_por')->constrained('users');
    $table->timestamps();

    $table->index(['numero_boleta', 'estado']);
});
```

#### Modelo Eloquent (`App\Models\BoletaPago`)
```php
class BoletaPago extends Model {
    protected $table = 'boletas_pago';
    protected $fillable = [
        'estudiante_id', 'numero_boleta', 'monto', 'fecha_deposito',
        'banco_entidad', 'concepto', 'archivo_comprobante', 'estado', 'registrado_por'
    ];

    public function estudiante() { return $this->belongsTo(Estudiante::class); }
    public function inscripcion() { return $this->hasOne(Inscripcion::class); }
    public function usuarioRegistrador() { return $this->belongsTo(User::class, 'registrado_por'); }
}
```

#### FormRequest (`StoreBoletaPagoRequest`)
* `estudiante_id`: `required|exists:estudiantes,id`
* `numero_boleta`: `required|string|max:50|unique:boletas_pago,numero_boleta`
* `monto`: `required|numeric|min:1`
* `fecha_deposito`: `required|date|before_or_equal:today`
* `banco_entidad`: `required|string|max:100`
* `archivo_comprobante`: `nullable|file|mimes:jpg,jpeg,png,pdf|max:3072`

#### Vista y Modal en AdminLTE (`resources/views/admin/boletas/index.blade.php`)
* Modal emergente rápido `#modal-registrar-boleta` con selector predictivo Select2 para buscar al estudiante por CI o nombre.
* Previsualización instantánea del voucher en modal con `Fancybox` o visor de imágenes Bootstrap.
* Badges de estado: `DISPONIBLE` (Verde), `UTILIZADA` (Azul), `ANULADA` (Rojo).
