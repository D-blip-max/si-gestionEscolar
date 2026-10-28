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
        Schema::table('estudiantes', function (Blueprint $table) {
            if (!Schema::hasColumn('estudiantes', 'codigo_estudiante')) {
                $table->string('codigo_estudiante', 30)->nullable()->unique()->after('id');
            }
            if (!Schema::hasColumn('estudiantes', 'carrera_facultad')) {
                $table->string('carrera_facultad', 150)->nullable()->after('telefono');
            }
            // Make legacy columns nullable for independent institute students
            if (Schema::hasColumn('estudiantes', 'ppff_id')) {
                $table->unsignedBigInteger('ppff_id')->nullable()->change();
            }
            if (Schema::hasColumn('estudiantes', 'foto')) {
                $table->string('foto')->nullable()->change();
            }
            if (Schema::hasColumn('estudiantes', 'direccion')) {
                $table->string('direccion')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estudiantes', function (Blueprint $table) {
            if (Schema::hasColumn('estudiantes', 'codigo_estudiante')) {
                $table->dropColumn('codigo_estudiante');
            }
            if (Schema::hasColumn('estudiantes', 'carrera_facultad')) {
                $table->dropColumn('carrera_facultad');
            }
        });
    }
};

