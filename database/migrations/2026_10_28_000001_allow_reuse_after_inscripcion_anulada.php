<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            // Mantener índices normales para las llaves foráneas antes de quitar los índices UNIQUE,
            // permitiendo reutilizar una boleta liberada o reinscribir tras una anulación.
            $table->index('boleta_pago_id');
            $table->index(['estudiante_id', 'paralelo_id']);
            $table->dropUnique(['boleta_pago_id']);
            $table->dropUnique(['estudiante_id', 'paralelo_id']);
        });
    }

    public function down(): void
    {
        Schema::table('inscripciones', function (Blueprint $table) {
            $table->dropIndex(['boleta_pago_id']);
            $table->dropIndex(['estudiante_id', 'paralelo_id']);
        });
    }
};
