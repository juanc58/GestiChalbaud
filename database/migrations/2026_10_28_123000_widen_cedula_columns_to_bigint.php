<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Permitir Cédula Escolar venezolana de 11 dígitos (ej. 11618456789) sin desbordamiento de INT
        DB::statement('ALTER TABLE `students` MODIFY `cedula` BIGINT NULL');
        DB::statement('ALTER TABLE `representatives` MODIFY `cedula` BIGINT NULL');
        DB::statement('ALTER TABLE `users` MODIFY `cedula` BIGINT NOT NULL');
    }

    public function down(): void
    {
        // Mantener BIGINT para evitar pérdida de datos si existen cédulas escolares de 11 dígitos
    }
};

