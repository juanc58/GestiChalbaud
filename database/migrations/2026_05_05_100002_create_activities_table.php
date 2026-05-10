<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Activities catalog
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('category')->nullable(); // e.g. 'Deporte', 'Arte', 'Música'
            $table->timestamps();
        });

        // Student ↔ Activity pivot
        Schema::create('activity_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('activity_id')->constrained()->onDelete('cascade');
            $table->unique(['student_id', 'activity_id']);
        });

        // Custom activity note per student
        Schema::table('students', function (Blueprint $table) {
            $table->text('other_activities')->nullable()->after('interests');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('other_activities');
        });
        Schema::dropIfExists('activity_student');
        Schema::dropIfExists('activities');
    }
};
