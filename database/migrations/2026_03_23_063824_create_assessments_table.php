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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->integer('term'); // 1, 2, 3 (Momentos/Lapsos)
            $table->integer('score'); // 1-20
            $table->text('observations')->nullable();
            $table->timestamps();
            
            // Un estudiante solo puede tener una nota por materia por lapso en la misma inscripción
            $table->unique(['enrollment_id', 'subject_id', 'term']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
