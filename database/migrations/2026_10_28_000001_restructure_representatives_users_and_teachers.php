<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Reincorporar campos propios de contacto en `representatives` y desvincular de `users`
        Schema::table('representatives', function (Blueprint $table) {
            if (!Schema::hasColumn('representatives', 'cedula')) {
                $table->integer('cedula')->nullable()->index()->after('id');
            }
            if (!Schema::hasColumn('representatives', 'first_name')) {
                $table->string('first_name')->default('')->after('cedula');
            }
            if (!Schema::hasColumn('representatives', 'second_name')) {
                $table->string('second_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('representatives', 'last_name')) {
                $table->string('last_name')->default('')->after('second_name');
            }
            if (!Schema::hasColumn('representatives', 'second_last_name')) {
                $table->string('second_last_name')->nullable()->after('last_name');
            }
            if (!Schema::hasColumn('representatives', 'email')) {
                $table->string('email')->nullable()->after('second_last_name');
            }
        });

        // Copiar datos de identidad desde `users` hacia `representatives` antes de eliminar `user_id`
        if (Schema::hasColumn('representatives', 'user_id')) {
            DB::statement("
                UPDATE representatives r
                INNER JOIN users u ON r.user_id = u.id
                SET r.cedula = u.cedula,
                    r.first_name = u.first_name,
                    r.second_name = u.second_name,
                    r.last_name = u.last_name,
                    r.second_last_name = u.second_last_name,
                    r.email = u.email
            ");

            // Vincular representative_id en students desde enrollments si faltaba
            DB::statement("
                UPDATE students s
                INNER JOIN enrollments e ON e.student_id = s.id
                SET s.representative_id = e.representative_id
                WHERE s.representative_id IS NULL AND e.representative_id IS NOT NULL
            ");

            Schema::table('representatives', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }

        // 2. Actualizar `users`: `role_id` nullable (para solicitudes pendientes) + `requested_role`
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->nullable()->change();
            if (!Schema::hasColumn('users', 'requested_role')) {
                $table->string('requested_role', 50)->nullable()->after('role_id');
            }
            $table->foreign('role_id')->references('id')->on('roles')->nullOnDelete();
        });

        // 3. Asegurar roles institucionales y eliminar cuentas/rol `representative`
        foreach (['admin', 'docente', 'worker'] as $roleName) {
            DB::table('roles')->updateOrInsert(
                ['name' => $roleName],
                ['updated_at' => now(), 'created_at' => now()]
            );
        }

        $repRoleId = DB::table('roles')->where('name', 'representative')->value('id');
        if ($repRoleId) {
            $repUserIds = DB::table('users')
                ->where('role_id', $repRoleId)
                ->whereNotIn('id', DB::table('teachers')->whereNotNull('user_id')->pluck('user_id'))
                ->pluck('id');

            if ($repUserIds->isNotEmpty()) {
                DB::table('user_security_answers')->whereIn('user_id', $repUserIds)->delete();
                DB::table('sessions')->whereIn('user_id', $repUserIds)->delete();
                DB::table('users')->whereIn('id', $repUserIds)->delete();
            }

            DB::table('roles')->where('id', $repRoleId)->delete();
        }

        // 4. Hacer opcionales los campos extendidos en `teachers` para permitir auto-registro al aprobar usuario docente
        DB::statement("
            ALTER TABLE teachers
                MODIFY gender ENUM('M', 'F') NULL,
                MODIFY birth_date DATE NULL,
                MODIFY entry_date DATE NULL,
                MODIFY academic_degree VARCHAR(255) NULL
        ");

        // Sincronizar usuarios vinculados a `teachers` para que tengan rol `docente` (excepto admins)
        $docenteRoleId = DB::table('roles')->where('name', 'docente')->value('id');
        $adminRoleId = DB::table('roles')->where('name', 'admin')->value('id');

        if ($docenteRoleId) {
            DB::table('users')
                ->whereIn('id', DB::table('teachers')->whereNotNull('user_id')->pluck('user_id'))
                ->where(function ($q) use ($adminRoleId) {
                    $q->whereNull('role_id')->orWhere('role_id', '!=', $adminRoleId);
                })
                ->update(['role_id' => $docenteRoleId]);

            // Crear perfil `Teacher` automáticamente para cualquier usuario con rol `docente` que no lo tenga
            $missingTeacherUsers = DB::table('users')
                ->where('role_id', $docenteRoleId)
                ->whereNotIn('id', DB::table('teachers')->whereNotNull('user_id')->pluck('user_id'))
                ->pluck('id');

            foreach ($missingTeacherUsers as $uid) {
                DB::table('teachers')->insert([
                    'user_id'         => $uid,
                    'entry_date'      => now()->toDateString(),
                    'academic_degree' => 'Docente Institucional',
                    'status'          => 'Activo',
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $repRoleId = DB::table('roles')->insertGetId([
            'name'       => 'representative',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Schema::table('representatives', function (Blueprint $table) {
            if (!Schema::hasColumn('representatives', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            }
            $table->dropColumn(['cedula', 'first_name', 'second_name', 'last_name', 'second_last_name', 'email']);
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'requested_role')) {
                $table->dropColumn('requested_role');
            }
        });
    }
};

