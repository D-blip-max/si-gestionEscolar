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
        Schema::create('ciclos_bimestrales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50); // Ej: "Bimestre I - 2026 (Ene-Feb)"
            $table->integer('gestion')->default(2026);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->enum('estado', ['ACTIVO', 'CERRADO', 'FUTURO'])->default('FUTURO');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciclos_bimestrales');
    }
};

