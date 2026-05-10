<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add second_name and second_last_name to students
        Schema::table('students', function (Blueprint $table) {
            $table->string('second_name')->nullable()->after('first_name');
            $table->string('second_last_name')->nullable()->after('last_name');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['second_name', 'second_last_name']);
        });
    }
};
