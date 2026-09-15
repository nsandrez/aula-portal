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
        Schema::create('matriculas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->foreignId('apoderado_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedSmallInteger('numero_lista')->default(1);
            $table->unsignedSmallInteger('anio')->default(2026);
            $table->string('estado')->default('regular'); // 'regular', 'retirado'
            $table->timestamps();

            $table->unique(['estudiante_id', 'anio']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
