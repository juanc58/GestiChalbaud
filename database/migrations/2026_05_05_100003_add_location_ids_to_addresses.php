<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add state column to addresses for AJAX location support
        Schema::table('addresses', function (Blueprint $table) {
            $table->string('state')->nullable()->after('student_id');
            $table->unsignedInteger('estado_id')->nullable()->after('state');
            $table->unsignedInteger('municipio_id')->nullable()->after('municipality');
            $table->unsignedInteger('parroquia_id')->nullable()->after('parish');
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn(['state', 'estado_id', 'municipio_id', 'parroquia_id']);
        });
    }
};
