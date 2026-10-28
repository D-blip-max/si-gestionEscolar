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
        if (!Schema::hasTable('calificaciones')) {
            Schema::create('calificaciones', function (Blueprint $table) {
                $table->id();
                $table->foreignId('inscripcion_id')->unique()->constrained('inscripciones')->cascadeOnDelete();
                $table->decimal('nota_final', 5, 2)->default(0);
                $table->enum('estado', ['PENDIENTE', 'APROBADO', 'REPROBADO', 'ANULADA'])->default('PENDIENTE');
                $table->text('observaciones')->nullable();
                $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->index(['inscripcion_id', 'estado']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};

