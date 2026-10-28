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
        Schema::create('modulos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idioma_id')->constrained('idiomas')->cascadeOnDelete();
            $table->string('nombre', 100);
            $table->integer('numero_orden'); // 1 al 9
            $table->enum('nivel', ['BASICO', 'INTERMEDIO', 'AVANZADO']);
            $table->integer('duracion_meses')->default(2);
            $table->foreignId('prerrequisito_id')->nullable()->constrained('modulos')->nullOnDelete();
            $table->boolean('es_modulo_graduacion')->default(false);
            $table->timestamps();

            $table->unique(['idioma_id', 'numero_orden']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modulos');
    }
};

