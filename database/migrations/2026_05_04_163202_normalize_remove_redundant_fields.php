<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove redundant fields from teachers (now all in users)
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropUnique(['cedula']);
            $table->dropColumn(['cedula', 'first_name', 'last_name', 'email', 'phone']);
        });

        // Remove redundant fields from representatives (now all in users)
        Schema::table('representatives', function (Blueprint $table) {
            $table->dropUnique(['cedula']);
            $table->dropColumn(['cedula', 'first_name', 'middle_name', 'last_name', 'second_last_name', 'email']);
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->integer('cedula')->unique()->nullable()->after('user_id');
            $table->string('first_name')->nullable()->after('cedula');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
        });

        Schema::table('representatives', function (Blueprint $table) {
            $table->integer('cedula')->unique()->nullable()->after('user_id');
            $table->string('first_name')->nullable()->after('cedula');
            $table->string('middle_name')->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('middle_name');
            $table->string('second_last_name')->nullable()->after('last_name');
            $table->string('email')->nullable();
        });
    }
};
