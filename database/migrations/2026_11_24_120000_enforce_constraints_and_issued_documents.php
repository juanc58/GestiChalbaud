<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Saneamiento seguro de registros existentes antes de aplicar restricciones NOT NULL
        DB::table('users')
            ->whereNull('phone')
            ->orWhereRaw("TRIM(phone) = ''")
            ->update(['phone' => '04120000000']);

        $usersWithoutEmail = DB::table('users')
            ->whereNull('email')
            ->orWhereRaw("TRIM(email) = ''")
            ->get(['id']);

        foreach ($usersWithoutEmail as $u) {
            DB::table('users')->where('id', $u->id)->update([
                'email' => "usuario{$u->id}@infochalbaud.edu.ve",
            ]);
        }

        $repsWithoutEmail = DB::table('representatives')
            ->whereNull('email')
            ->orWhereRaw("TRIM(email) = ''")
            ->get(['id']);

        foreach ($repsWithoutEmail as $r) {
            DB::table('representatives')->where('id', $r->id)->update([
                'email' => "representante{$r->id}@infochalbaud.edu.ve",
            ]);
        }

        DB::table('teachers')
            ->whereNull('birth_date')
            ->update(['birth_date' => '1985-01-01']);

        // 2. Restricciones estrictas en base de datos para fecha, teléfono y correo
        DB::statement("ALTER TABLE `users` MODIFY `phone` VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE `users` MODIFY `email` VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE `representatives` MODIFY `email` VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE `representatives` MODIFY `phone_whatsapp` VARCHAR(255) NOT NULL");
        DB::statement("ALTER TABLE `teachers` MODIFY `birth_date` DATE NOT NULL");

        // 3. Tabla de auditoría de documentos oficiales emitidos / enviados
        if (!Schema::hasTable('issued_documents')) {
            Schema::create('issued_documents', function (Blueprint $table) {
                $table->id();
                $table->string('folio_code', 40)->unique();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->foreignId('enrollment_id')->nullable()->constrained('enrollments')->nullOnDelete();
                $table->string('document_type', 40); // boletin, record_academico, estudio, prosecucion, justificativo
                $table->string('school_year', 25);
                $table->string('delivery_channel', 30)->default('pdf'); // preview, pdf, email
                $table->string('recipient_email')->nullable();
                $table->json('metadata')->nullable();
                $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('issued_documents');
    }
};
