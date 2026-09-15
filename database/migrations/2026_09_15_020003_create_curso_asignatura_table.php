<?php

declare(strict_types=1);

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
        Schema::create('curso_asignatura', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('asignatura_id')->constrained('asignaturas')->cascadeOnDelete();
            $table->foreignId('docente_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedSmallInteger('horas_semanales')->default(4);
            $table->timestamps();

            $table->unique(['curso_id', 'asignatura_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curso_asignatura');
    }
};
