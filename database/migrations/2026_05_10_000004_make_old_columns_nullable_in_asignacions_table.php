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
        Schema::table('asignacions', function (Blueprint $table) {
            $table->unsignedBigInteger('gestion_id')->nullable()->change();
            $table->unsignedBigInteger('nivel_id')->nullable()->change();
            $table->unsignedBigInteger('grado_id')->nullable()->change();
            $table->unsignedBigInteger('materia_id')->nullable()->change();
            $table->unsignedBigInteger('turno_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asignacions', function (Blueprint $table) {
            //
        });
    }
};

