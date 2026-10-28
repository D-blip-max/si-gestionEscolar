<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('boletas_pago')) {
            Schema::create('boletas_pago', function (Blueprint $table) {
                $table->id();
                $table->foreignId('estudiante_id')->constrained('estudiantes')->cascadeOnDelete();
                $table->string('numero_boleta', 50)->unique();
                $table->decimal('monto', 8, 2);
                $table->date('fecha_deposito');
                $table->string('banco_entidad', 100);
                $table->string('concepto', 150);
                $table->string('archivo_comprobante', 255)->nullable();
                $table->enum('estado', ['DISPONIBLE', 'UTILIZADA', 'ANULADA'])->default('DISPONIBLE');
                $table->foreignId('registrado_por')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index(['numero_boleta', 'estado']);
                $table->index(['estudiante_id', 'estado']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boletas_pago');
    }
};

