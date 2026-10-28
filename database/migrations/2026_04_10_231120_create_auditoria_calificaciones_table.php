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
        Schema::create('auditoria_calificaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('calificacion_id')->nullable();
            $table->unsignedBigInteger('inscripcion_id')->nullable();
            $table->decimal('nota_anterior', 5, 2)->default(0);
            $table->decimal('nota_nueva', 5, 2)->default(0);
            $table->unsignedBigInteger('modificado_por')->nullable();
            $table->string('motivo', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditoria_calificaciones');
    }
};

