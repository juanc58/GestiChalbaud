<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Allergies catalog
        Schema::create('allergies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('category')->nullable(); // e.g. 'Medicamento', 'Alimento', 'Ambiental'
            $table->timestamps();
        });

        // Student ↔ Allergy pivot
        Schema::create('allergy_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('allergy_id')->constrained()->onDelete('cascade');
            $table->unique(['student_id', 'allergy_id']);
        });

        // Custom allergy note per student
        Schema::table('students', function (Blueprint $table) {
            $table->text('other_allergies')->nullable()->after('health_conditions');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('other_allergies');
        });
        Schema::dropIfExists('allergy_student');
        Schema::dropIfExists('allergies');
    }
};
