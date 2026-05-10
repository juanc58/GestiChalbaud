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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->foreignId('representative_id')->constrained()->onDelete('cascade');
            $table->string('school_year'); // e.g., "2025-2026"
            // Pedagogical Control
            $table->text('diagnostic_1')->nullable();
            $table->text('diagnostic_2')->nullable();
            $table->text('diagnostic_3')->nullable();
            $table->text('learning_projects')->nullable();
            $table->text('recommendations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
