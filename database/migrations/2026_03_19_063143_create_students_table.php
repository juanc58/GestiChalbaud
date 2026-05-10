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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->integer('cedula')->unique()->nullable(); // Some kids don't have one yet
            $table->string('first_name');
            $table->string('last_name');
            $table->string('photo_path')->nullable();
            $table->date('birth_date');
            $table->string('birth_place_state');
            $table->string('birth_place_locality');
            $table->enum('gender', ['M', 'F']);
            // Sizes & Anthropometrics
            $table->string('shirt_size')->nullable();
            $table->string('pants_size')->nullable();
            $table->string('shoes_size')->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('height', 5, 2)->nullable();
            // Health
            $table->text('health_conditions')->nullable();
            $table->text('cognitive_diversity')->nullable();
            // Context
            $table->string('lives_with')->nullable();
            $table->integer('siblings_count')->default(0);
            $table->text('interests')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
