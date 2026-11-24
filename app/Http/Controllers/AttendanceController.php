<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\Teacher;
use App\Models\TeacherAttendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    /**
     * Asistencia de Estudiantes:
     * Lectura y Escritura permitida a Admin, Personal Administrativo (worker) y Docente (su aula asignada).
     */
    public function students(Request $request)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isDocente()) {
            abort(403, 'No tienes permisos para acceder al módulo de asistencia.');
        }

        $currentYear = SchoolYear::current();
        $date = \App\Rules\SpanishDate::toDatabase($request->input('date')) ?? now()->format('Y-m-d');

        if ($user->isDocente()) {
            $assignedId = $user->assignedSectionId($currentYear);
            $sections = Section::with('grade')
                ->where('id', $assignedId ?: 0)
                ->get();
            $selectedSectionId = $assignedId;
        } else {
            $sections = Section::with('grade')->orderBy('grade_id')->orderBy('name')->get();
            $selectedSectionId = $request->input('section_id', $sections->first()?->id);
        }

        $selectedSection = $selectedSectionId
            ? Section::with('grade')->find($selectedSectionId)
            : null;

        // Security check if teacher tries to pass another section_id
        if ($user->isDocente() && $selectedSection && $selectedSection->id != $user->assignedSectionId($currentYear)) {
            abort(403, 'Solo puedes gestionar la asistencia de tu aula asignada.');
        }

        $enrollments = collect();
        $attendanceMap = collect();
        $stats = [
            'Presente'    => 0,
            'Ausente'     => 0,
            'Justificado' => 0,
            'Tardanza'    => 0,
        ];

        if ($selectedSection) {
            $enrollments = $selectedSection->enrollments()
                ->where('enrollments.school_year', $currentYear)
                ->join('students', 'enrollments.student_id', '=', 'students.id')
                ->orderBy('students.last_name')
                ->orderBy('students.first_name')
                ->select('enrollments.*')
                ->with('student')
                ->get();

            $studentIds = $enrollments->pluck('student_id');
            $attendanceMap = StudentAttendance::with('recorder')
                ->whereIn('student_id', $studentIds)
                ->whereDate('date', $date)
                ->get()
                ->keyBy('student_id');

            foreach ($attendanceMap as $att) {
                if (isset($stats[$att->status])) {
                    $stats[$att->status]++;
                }
            }
        }

        return view('attendance.students', compact(
            'sections',
            'selectedSection',
            'enrollments',
            'attendanceMap',
            'date',
            'currentYear',
            'stats'
        ));
    }

    /**
     * Guardar Asistencia Diaria de Estudiantes.
     */
    public function storeStudents(Request $request)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isDocente()) {
            abort(403, 'No tienes permisos para registrar asistencia de estudiantes.');
        }

        $currentYear = SchoolYear::current();

        // Normalize HH:MM:SS -> HH:MM before validation
        $rawAttendance = $request->input('attendance', []);
        foreach ($rawAttendance as $sid => $row) {
            if (!empty($row['check_in_time'])) {
                $rawAttendance[$sid]['check_in_time'] = substr($row['check_in_time'], 0, 5);
            }
            if (!empty($row['check_out_time'])) {
                $rawAttendance[$sid]['check_out_time'] = substr($row['check_out_time'], 0, 5);
            }
        }
        $request->merge(['attendance' => $rawAttendance]);

        $validated = $request->validate([
            'section_id'                  => 'required|exists:sections,id',
            'date'                        => ['required', new \App\Rules\SpanishDate()],
            'attendance'                  => 'required|array',
            'attendance.*.status'         => 'required|in:Presente,Ausente,Justificado,Tardanza',
            'attendance.*.check_in_time'  => 'nullable|date_format:H:i',
            'attendance.*.check_out_time' => 'nullable|date_format:H:i',
            'attendance.*.observations'   => 'nullable|string|max:500',
        ]);
        $dbDate = \App\Rules\SpanishDate::toDatabase($validated['date']);

        if ($user->isDocente() && (int) $validated['section_id'] !== (int) $user->assignedSectionId($currentYear)) {
            abort(403, 'No tienes permiso para modificar la asistencia de otra aula.');
        }

        $count = 0;
        foreach ($validated['attendance'] as $studentId => $row) {
            if (!Student::where('id', $studentId)->exists()) {
                continue;
            }
            StudentAttendance::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'date'       => $dbDate,
                ],
                [
                    'section_id'     => $validated['section_id'],
                    'school_year'    => $currentYear,
                    'status'         => $row['status'],
                    'check_in_time'  => !empty($row['check_in_time']) ? $row['check_in_time'] : null,
                    'check_out_time' => !empty($row['check_out_time']) ? $row['check_out_time'] : null,
                    'observations'   => $row['observations'] ?? null,
                    'recorded_by'    => $user->id,
                ]
            );
            $count++;
        }

        return redirect()
            ->route('attendance.students', [
                'section_id' => $validated['section_id'],
                'date'       => $dbDate,
            ])
            ->with('success', "Asistencia de {$count} estudiante(s) registrada correctamente para el " . Carbon::parse($dbDate)->format('d/m/Y') . '.');
    }

    /**
     * Asistencia de Docentes:
     * - Admin y Personal Administrativo (worker): Lectura y Escritura de todos los docentes.
     * - Docente: SOLO LECTURA de su propio historial de asistencia (no puede editar su asistencia).
     */
    public function teachers(Request $request)
    {
        $user = auth()->user();
        $currentYear = SchoolYear::current();

        // MODO SOLO LECTURA PARA DOCENTES
        if ($user->isDocente()) {
            $teacher = $user->teacher;
            $month = $request->input('month', now()->format('Y-m'));
            try {
                $startOfMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
                $endOfMonth   = Carbon::createFromFormat('Y-m', $month)->endOfMonth();
            } catch (\Throwable $e) {
                $month        = now()->format('Y-m');
                $startOfMonth = now()->startOfMonth();
                $endOfMonth   = now()->endOfMonth();
            }

            $myAttendances = $teacher
                ? TeacherAttendance::with('recorder')
                    ->where('teacher_id', $teacher->id)
                    ->whereBetween('date', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
                    ->orderByDesc('date')
                    ->get()
                : collect();

            $stats = [
                'Presente'    => $myAttendances->where('status', 'Presente')->count(),
                'Ausente'     => $myAttendances->where('status', 'Ausente')->count(),
                'Justificado' => $myAttendances->where('status', 'Justificado')->count(),
                'Permiso'     => $myAttendances->where('status', 'Permiso')->count(),
                'Reposo'      => $myAttendances->where('status', 'Reposo')->count(),
            ];

            return view('attendance.teachers', [
                'canEdit'       => false,
                'teacher'       => $teacher,
                'myAttendances' => $myAttendances,
                'month'         => $month,
                'stats'         => $stats,
                'currentYear'   => $currentYear,
            ]);
        }

        // MODO LECTURA Y ESCRITURA PARA ADMIN Y PERSONAL (WORKER)
        if (!$user->isAdmin()) {
            abort(403, 'No tienes permisos para acceder al control de asistencia docente.');
        }

        $date = \App\Rules\SpanishDate::toDatabase($request->input('date')) ?? now()->format('Y-m-d');
        $search = trim((string) $request->input('search', ''));

        $teachersQuery = Teacher::with(['user', 'assignments' => function ($q) use ($currentYear) {
            $q->where('school_year', $currentYear)->with('section.grade');
        }])->where('status', 'Activo');

        if ($search !== '') {
            $teachersQuery->whereHas('user', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('cedula', 'like', "%{$search}%");
            });
        }

        $teachers = $teachersQuery->get()->sortBy(fn ($t) => $t->last_name . ' ' . $t->first_name)->values();

        $attendanceMap = TeacherAttendance::with('recorder')
            ->whereIn('teacher_id', $teachers->pluck('id'))
            ->whereDate('date', $date)
            ->get()
            ->keyBy('teacher_id');

        $stats = [
            'Presente'    => $attendanceMap->where('status', 'Presente')->count(),
            'Ausente'     => $attendanceMap->where('status', 'Ausente')->count(),
            'Justificado' => $attendanceMap->where('status', 'Justificado')->count(),
            'Permiso'     => $attendanceMap->where('status', 'Permiso')->count(),
            'Reposo'      => $attendanceMap->where('status', 'Reposo')->count(),
        ];

        return view('attendance.teachers', [
            'canEdit'       => true,
            'teachers'      => $teachers,
            'attendanceMap' => $attendanceMap,
            'date'          => $date,
            'search'        => $search,
            'stats'         => $stats,
            'currentYear'   => $currentYear,
        ]);
    }

    /**
     * Guardar Asistencia Diaria de Docentes (SOLO Admin y Personal Administrativo / Worker).
     */
    public function storeTeachers(Request $request)
    {
        $user = auth()->user();

        if ($user->isDocente() || !$user->isAdmin()) {
            abort(403, 'Acceso denegado: Los docentes tienen permiso únicamente de lectura sobre su asistencia.');
        }

        // Normalize HH:MM:SS -> HH:MM before validation
        $rawAttendance = $request->input('attendance', []);
        foreach ($rawAttendance as $tid => $row) {
            if (!empty($row['check_in_time'])) {
                $rawAttendance[$tid]['check_in_time'] = substr($row['check_in_time'], 0, 5);
            }
            if (!empty($row['check_out_time'])) {
                $rawAttendance[$tid]['check_out_time'] = substr($row['check_out_time'], 0, 5);
            }
        }
        $request->merge(['attendance' => $rawAttendance]);

        $validated = $request->validate([
            'date'                        => ['required', new \App\Rules\SpanishDate()],
            'attendance'                  => 'required|array',
            'attendance.*.status'         => 'required|in:Presente,Ausente,Justificado,Permiso,Reposo',
            'attendance.*.check_in_time'  => 'nullable|date_format:H:i',
            'attendance.*.check_out_time' => 'nullable|date_format:H:i',
            'attendance.*.observations'   => 'nullable|string|max:500',
        ]);
        $dbDate = \App\Rules\SpanishDate::toDatabase($validated['date']);

        $count = 0;
        foreach ($validated['attendance'] as $teacherId => $row) {
            if (!Teacher::where('id', $teacherId)->exists()) {
                continue;
            }
            TeacherAttendance::updateOrCreate(
                [
                    'teacher_id' => $teacherId,
                    'date'       => $dbDate,
                ],
                [
                    'status'         => $row['status'],
                    'check_in_time'  => !empty($row['check_in_time']) ? $row['check_in_time'] : null,
                    'check_out_time' => !empty($row['check_out_time']) ? $row['check_out_time'] : null,
                    'observations'   => $row['observations'] ?? null,
                    'recorded_by'    => $user->id,
                ]
            );
            $count++;
        }

        return redirect()
            ->route('attendance.teachers', ['date' => $dbDate])
            ->with('success', "Asistencia de {$count} docente(s) guardada exitosamente para el " . Carbon::parse($dbDate)->format('d/m/Y') . '.');
    }
}

