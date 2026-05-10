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
        Schema::create('representatives', function (Blueprint $table) {
            $table->id();
            $table->integer('cedula')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('photo_path')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_whatsapp');
            $table->string('phone_local')->nullable();
            $table->string('facebook')->nullable();
            $table->string('job_title')->nullable();
            $table->text('workplace_address')->nullable();
            $table->string('relationship'); // Mother, Father, Guardian, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('representatives');
    }
};
