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
        if (!Schema::hasTable('inscripciones')) {
            Schema::create('inscripciones', function (Blueprint $table) {
                $table->id();
                $table->foreignId('estudiante_id')->constrained('estudiantes')->cascadeOnDelete();
                $table->foreignId('modulo_id')->constrained('modulos')->cascadeOnDelete();
                $table->foreignId('paralelo_id')->constrained('paralelos')->cascadeOnDelete();
                $table->foreignId('boleta_pago_id')->unique()->constrained('boletas_pago')->restrictOnDelete();
                $table->date('fecha_inscripcion');
                $table->enum('estado', ['ACTIVA', 'ANULADA'])->default('ACTIVA');
                $table->string('motivo_anulacion', 255)->nullable();
                $table->foreignId('inscrito_por')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->unique(['estudiante_id', 'paralelo_id']);
                $table->index(['estudiante_id', 'modulo_id', 'estado']);
                $table->index(['paralelo_id', 'estado']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};

