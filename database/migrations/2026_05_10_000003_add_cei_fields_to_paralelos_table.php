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
        Schema::table('paralelos', function (Blueprint $table) {
            $table->unsignedBigInteger('grado_id')->nullable()->change();
            $table->foreignId('ciclo_id')->nullable()->after('id')->constrained('ciclos_bimestrales')->nullOnDelete();
            $table->foreignId('modulo_id')->nullable()->after('ciclo_id')->constrained('modulos')->nullOnDelete();
            $table->enum('modalidad', ['PRESENCIAL', 'VIRTUAL'])->default('PRESENCIAL')->after('nombre');
            $table->string('aula_o_enlace', 255)->default('Por definir')->after('modalidad');
            $table->enum('turno', ['MAÑANA', 'TARDE', 'NOCHE'])->default('MAÑANA')->after('aula_o_enlace');
            $table->time('hora_inicio')->nullable()->after('turno');
            $table->time('hora_fin')->nullable()->after('hora_inicio');
            $table->integer('cupo_maximo')->default(30)->after('hora_fin');
            $table->integer('cupo_ocupado')->default(0)->after('cupo_maximo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paralelos', function (Blueprint $table) {
            $table->dropForeign(['ciclo_id']);
            $table->dropForeign(['modulo_id']);
            $table->dropColumn([
                'ciclo_id',
                'modulo_id',
                'modalidad',
                'aula_o_enlace',
                'turno',
                'hora_inicio',
                'hora_fin',
                'cupo_maximo',
                'cupo_ocupado',
            ]);
        });
    }
};

