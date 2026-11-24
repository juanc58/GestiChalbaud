<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('teachers', 'photo_path')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->string('photo_path')->nullable()->after('user_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('teachers', 'photo_path')) {
            Schema::table('teachers', function (Blueprint $table) {
                $table->dropColumn('photo_path');
            });
        }
    }
};
