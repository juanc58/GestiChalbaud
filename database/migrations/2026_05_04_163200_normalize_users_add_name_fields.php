<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add split name columns + phone to users
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('cedula');
            $table->string('second_name')->nullable()->after('first_name');
            $table->string('last_name')->nullable()->after('second_name');
            $table->string('second_last_name')->nullable()->after('last_name');
            $table->string('phone')->nullable()->after('second_last_name');
        });

        // 2. Populate new fields from existing data
        // For teachers: copy from teachers table
        DB::statement("
            UPDATE users u
            INNER JOIN teachers t ON t.user_id = u.id
            SET
                u.first_name = t.first_name,
                u.last_name  = t.last_name,
                u.phone      = t.phone
        ");

        // For representatives: copy from representatives table
        DB::statement("
            UPDATE users u
            INNER JOIN representatives r ON r.user_id = u.id
            SET
                u.first_name       = r.first_name,
                u.second_name      = r.middle_name,
                u.last_name        = r.last_name,
                u.second_last_name = r.second_last_name,
                u.phone            = r.phone_whatsapp
        ");

        // For admin/other users: split the existing `name` field
        DB::statement("
            UPDATE users u
            SET
                u.first_name = TRIM(SUBSTRING_INDEX(u.name, ' ', 1)),
                u.last_name  = TRIM(SUBSTRING(u.name FROM INSTR(u.name, ' ') + 1))
            WHERE u.first_name IS NULL OR u.first_name = ''
        ");

        // 3. Remove old composite name column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->after('cedula')->default('');
        });

        // Re-assemble name from parts
        DB::statement("UPDATE users SET name = CONCAT_WS(' ', first_name, last_name)");

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'second_name', 'last_name', 'second_last_name', 'phone']);
        });
    }
};
