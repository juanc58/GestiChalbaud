<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('student_attendances')) {
            Schema::create('student_attendances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
                $table->foreignId('section_id')->constrained('sections')->cascadeOnDelete();
                $table->string('school_year', 20)->index();
                $table->date('date')->index();
                $table->string('status', 30)->default('Presente');
                $table->time('check_in_time')->nullable();
                $table->time('check_out_time')->nullable();
                $table->string('observations', 500)->nullable();
                $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->unique(['student_id', 'date']);
            });
        }

        if (!Schema::hasTable('teacher_attendances')) {
            Schema::create('teacher_attendances', function (Blueprint $table) {
                $table->id();
                $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
                $table->date('date')->index();
                $table->string('status', 30)->default('Presente');
                $table->time('check_in_time')->nullable();
                $table->time('check_out_time')->nullable();
                $table->string('observations', 500)->nullable();
                $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();

                $table->unique(['teacher_id', 'date']);
            });
        }

        if (!Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key', 100)->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('enrollments') && !Schema::hasColumn('enrollments', 'pedagogical_summary')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->text('pedagogical_summary')->nullable()->after('recommendations');
            });
        }

        // Seed default Mailpit & Institutional settings
        $now = now();
        $defaults = [
            'mail_mailer'       => 'smtp',
            'mail_host'         => '127.0.0.1',
            'mail_port'         => '1025',
            'mail_encryption'   => '',
            'mail_username'     => '',
            'mail_password'     => '',
            'mail_from_address' => 'controlestudios@infochalbaud.edu.ve',
            'mail_from_name'    => 'Infochalbaud - U.E.B. Coronel Carlos Delgado Chalbaud',
            'director_name'     => 'DIRECCIÓN DEL PLANTEL',
            'director_cedula'   => '',
        ];

        foreach ($defaults as $key => $val) {
            DB::table('system_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $val, 'created_at' => $now, 'updated_at' => $now]
            );
        }

        // Normalize existing names to UTF-8 UPPERCASE in users, students, representatives
        foreach (['users', 'students', 'representatives'] as $tableName) {
            if (!Schema::hasTable($tableName)) {
                continue;
            }
            $rows = DB::table($tableName)->select(['id', 'first_name', 'second_name', 'last_name', 'second_last_name'])->get();
            foreach ($rows as $row) {
                $updates = [];
                foreach (['first_name', 'second_name', 'last_name', 'second_last_name'] as $col) {
                    if ($row->$col !== null) {
                        $upper = mb_strtoupper(trim($row->$col), 'UTF-8');
                        if ($upper !== $row->$col) {
                            $updates[$col] = $upper;
                        }
                    }
                }
                if (!empty($updates)) {
                    DB::table($tableName)->where('id', $row->id)->update($updates);
                }
            }
        }

        // Rebrand legacy @gestichalbaud.* user emails to @infochalbaud.edu.ve
        if (Schema::hasTable('users')) {
            $legacyUsers = DB::table('users')
                ->where('email', 'like', '%@gestichalbaud.%')
                ->get(['id', 'email']);
            foreach ($legacyUsers as $u) {
                $newEmail = preg_replace('/@gestichalbaud\.[a-z.]+$/i', '@infochalbaud.edu.ve', $u->email);
                if (!DB::table('users')->where('email', $newEmail)->where('id', '!=', $u->id)->exists()) {
                    DB::table('users')->where('id', $u->id)->update(['email' => $newEmail]);
                }
            }
        }

        // Backfill historical graduates missing from graduates table
        if (Schema::hasTable('students') && Schema::hasTable('graduates')) {
            $activeYear = Schema::hasTable('school_years')
                ? (DB::table('school_years')->where('is_active', true)->value('year') ?? '2025-2026')
                : '2025-2026';

            $missingGrads = DB::table('students')
                ->where('status', 'Egresado')
                ->whereNotExists(function ($q) {
                    $q->select(DB::raw(1))
                      ->from('graduates')
                      ->whereColumn('graduates.student_id', 'students.id');
                })
                ->get(['id']);

            foreach ($missingGrads as $st) {
                $lastYear = DB::table('enrollments')
                    ->where('student_id', $st->id)
                    ->orderByDesc('school_year')
                    ->value('school_year') ?? $activeYear;

                DB::table('graduates')->insert([
                    'student_id'     => $st->id,
                    'promotion_year' => $lastYear,
                    'notes'          => 'Registro histórico sincronizado automáticamente.',
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('enrollments') && Schema::hasColumn('enrollments', 'pedagogical_summary')) {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->dropColumn('pedagogical_summary');
            });
        }
        Schema::dropIfExists('system_settings');
        Schema::dropIfExists('teacher_attendances');
        Schema::dropIfExists('student_attendances');
    }
};

