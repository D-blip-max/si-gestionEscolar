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
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();

            // Llaves Foráneas con eliminación en cascada
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ppff_id')->constrained('ppffs')->onDelete('cascade');

            // Campos de texto y datos
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('ci')->unique();
            $table->date('fecha_nacimiento');
            $table->string('telefono');
            $table->string('direccion');
            $table->string('foto');

            // Enums para opciones cerradas
            $table->enum('genero', ['masculino', 'femenino']);
            $table->enum('estado', ['activo', 'inactivo']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
