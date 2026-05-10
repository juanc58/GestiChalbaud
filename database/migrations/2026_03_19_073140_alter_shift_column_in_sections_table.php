<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE sections MODIFY COLUMN shift VARCHAR(255) NOT NULL DEFAULT 'Mañana'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE sections MODIFY COLUMN shift ENUM('morning', 'afternoon') NOT NULL");
    }
};
