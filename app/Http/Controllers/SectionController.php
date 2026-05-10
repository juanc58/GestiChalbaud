<?php

namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Grade;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = $this->getCurrentSchoolYear();
        $selectedYear = $request->query('school_year', $currentYear);
        $availableYears = \App\Models\SchoolYear::orderBy('year', 'desc')->pluck('year')->toArray();

        $gradesQuery = Grade::query();

        if (auth()->user()->isDocente()) {
            $assignedId = auth()->user()->assignedSectionId($selectedYear);
            if (!$assignedId) {
                // Si el docente no tiene aula asignada este año, no mostramos nada
                $grades = collect();
                return view('sections.index', compact('grades', 'currentYear', 'selectedYear', 'availableYears'));
            }
            // Cargamos solo el grado y la sección del docente
            $gradesQuery->whereHas('sections', function($q) use ($assignedId) {
                $q->where('id', $assignedId);
            })->with(['sections' => function($q) use ($assignedId, $selectedYear) {
                $q->where('id', $assignedId)->with(['enrollments' => function($sq) use ($selectedYear) {
                    $sq->where('school_year', $selectedYear);
                }]);
            }]);
        } else {
            // Group sections by their grades and eager load relationships for the selected year
            $gradesQuery->with(['sections.enrollments' => function($q) use ($selectedYear) {
                $q->where('school_year', $selectedYear);
            }]);
        }

        $grades = $gradesQuery->get();
        
        return view('sections.index', compact('grades', 'currentYear', 'selectedYear', 'availableYears'));
    }

    public function create()
    {
        $grades = Grade::all();
        return view('sections.create', compact('grades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'name' => [
                'required',
                'string',
                'max:2',
                \Illuminate\Validation\Rule::unique('sections')->where(function ($query) use ($request) {
                    return $query->where('grade_id', $request->grade_id);
                })
            ],
            'shift' => 'required|string|in:Mañana,Tarde,Integral',
        ], [
            'name.unique' => 'Ya existe una sección con este nombre ("' . strtoupper($request->name) . '") para el grado seleccionado.',
        ]);

        $data = $request->all();
        $data['name'] = strtoupper($data['name']);

        Section::create($data);

        return redirect()->route('sections.index')->with('success', 'Sección creada exitosamente.');
    }

    protected function getCurrentSchoolYear()
    {
        return \App\Models\SchoolYear::current();
    }

    public function show(Request $request, Section $section)
    {
        $currentYear = $this->getCurrentSchoolYear();
        $selectedYear = $request->query('school_year', $currentYear);

        // Get all unique school years present in enrollments for this section to build the filter
        $availableYears = \App\Models\Enrollment::where('section_id', $section->id)
            ->distinct()
            ->pluck('school_year')
            ->toArray();
        
        // Ensure the current year is always an option even if no enrollments exist yet
        if (!in_array($currentYear, $availableYears)) {
            $availableYears[] = $currentYear;
        }
        sort($availableYears);

        // Logic for editability: Latest year OR Admin role
        $isLatestYear = ($selectedYear == $currentYear);
        $isAdmin = auth()->user()->role->name === 'admin';

        // Load enrollments filtered by selected year and sorted alphabetically by student name
        $enrollments = $section->enrollments()
            ->where('enrollments.school_year', $selectedYear)
            ->join('students', 'enrollments.student_id', '=', 'students.id')
            ->orderBy('students.first_name')
            ->orderBy('students.last_name')
            ->select('enrollments.*')
            ->with('student')
            ->get();
        $section->setRelation('enrollments', $enrollments);
        $section->load('grade.level');
        $currentTeacher = $section->currentTeacherAssignment($selectedYear)->first()?->teacher;

        return view('sections.show', compact('section', 'availableYears', 'selectedYear', 'currentYear', 'isLatestYear', 'isAdmin', 'currentTeacher'));
    }

    public function edit(Section $section)
    {
        $currentYear = $this->getCurrentSchoolYear();
        $grades = Grade::all();
        
        // Teachers without an assignment this year OR the one already assigned here
        $teachers = \App\Models\Teacher::whereDoesntHave('assignments', function($q) use ($currentYear) {
            $q->where('school_year', $currentYear);
        })->orWhereHas('assignments', function($q) use ($currentYear, $section) {
            $q->where('school_year', $currentYear)->where('section_id', $section->id);
        })->orderBy('first_name')->get();

        return view('sections.edit', compact('section', 'grades', 'teachers', 'currentYear'));
    }

    public function update(Request $request, Section $section)
    {
        $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'name' => [
                'required',
                'string',
                'max:2',
                \Illuminate\Validation\Rule::unique('sections')->where(function ($query) use ($request) {
                    return $query->where('grade_id', $request->grade_id);
                })->ignore($section->id)
            ],
            'shift' => 'required|string|in:Mañana,Tarde,Integral',
            'teacher_id' => 'nullable|exists:teachers,id',
        ], [
            'name.unique' => 'Ya existe una sección con este nombre ("' . strtoupper($request->name) . '") para el grado seleccionado.',
        ]);

        $data = $request->all();
        $data['name'] = strtoupper($data['name']);
        $section->update($data);

        // Teacher Assignment logic (Admin only)
        if (auth()->user()->role->name === 'admin') {
            $currentYear = $this->getCurrentSchoolYear();
            if ($request->filled('teacher_id')) {
                // Assign new teacher (updateOrCreate handles the unique constraint per year)
                \App\Models\TeacherAssignment::updateOrCreate(
                    ['section_id' => $section->id, 'school_year' => $currentYear],
                    ['teacher_id' => $request->teacher_id]
                );
                
                // Ensure this teacher isn't assigned elsewhere this same year
                \App\Models\TeacherAssignment::where('teacher_id', $request->teacher_id)
                    ->where('school_year', $currentYear)
                    ->where('section_id', '!=', $section->id)
                    ->delete();

            } else {
                // Remove assignment if empty
                \App\Models\TeacherAssignment::where('section_id', $section->id)
                    ->where('school_year', $currentYear)
                    ->delete();
            }
        }

        return redirect()->route('sections.index')->with('success', 'Sección actualizada correctamente.');
    }

    public function promotionView(Section $section)
    {
        $currentYear = $this->getCurrentSchoolYear();
        
        $enrollments = $section->enrollments()
            ->where('enrollments.school_year', $currentYear)
            ->whereIn('enrollments.status', ['Inscrito', 'Repitiente'])
            ->join('students', 'enrollments.student_id', '=', 'students.id')
            ->orderBy('students.first_name')
            ->orderBy('students.last_name')
            ->select('enrollments.*')
            ->with('student')
            ->get();
        $section->setRelation('enrollments', $enrollments);
        $section->load('grade.level');
        
        // Group sections by grade with rank calculation
        $allGrades = Grade::with('sections')->get()->map(function($g) {
            $g->rank = ($g->educational_level_id * 10) + $g->number;
            return $g;
        });

        $currentRank = ($section->grade->educational_level_id * 10) + $section->grade->number;
        
        // Calculate next academic year
        $parts = explode('-', $currentYear);
        $nextYear = ((int)$parts[0] + 1) . '-' . ((int)$parts[1] + 1);
        
        return view('sections.promote', compact('section', 'allGrades', 'currentYear', 'nextYear', 'currentRank'));
    }

    public function assignView(Section $section)
    {
        // Get students who are NOT already enrolled in this specific section/year
        $currentYear = $this->getCurrentSchoolYear();
        
        // We list students who are ENABLED and ACTIVO, and NOT already enrolled in ANY section for the current year
        $students = \App\Models\Student::where('is_active', true)
            ->where('status', 'Activo')
            ->whereDoesntHave('enrollments', function($q) use ($currentYear) {
                $q->where('school_year', $currentYear);
            })
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('sections.assign', compact('section', 'students', 'currentYear'));
    }

    public function assignStore(Request $request, Section $section)
    {
        $validated = $request->validate([
            'student_ids' => 'required|array',
            'student_ids.*' => 'exists:students,id',
            'school_year' => 'required|string',
        ]);

        $currentYear = $validated['school_year'];
        $count = 0;

        foreach ($validated['student_ids'] as $studentId) {
            $student = \App\Models\Student::findOrFail($studentId);
            
            $lastEnrollment = $student->enrollments()->latest()->first();
            $repId = $lastEnrollment ? $lastEnrollment->representative_id : \App\Models\Representative::first()->id;

            \App\Models\Enrollment::create([
                'student_id' => $student->id,
                'representative_id' => $repId,
                'section_id' => $section->id,
                'school_year' => $currentYear,
                'status' => 'Inscrito',
            ]);
            $count++;
        }

        return redirect()->route('sections.show', $section->id)->with('success', "$count estudiantes asignados correctamente al aula.");
    }

    public function promote(Request $request, Section $section)
    {
        $data = $request->validate([
            'students' => 'required|array',
            'students.*.status' => 'required|in:Aprobado,Reprobado',
            'students.*.target_section_id' => 'nullable|exists:sections,id',
            'next_school_year' => 'required|string',
        ]);

        foreach ($data['students'] as $enrollmentId => $studentData) {
            $enrollment = \App\Models\Enrollment::find($enrollmentId);
            if (!$enrollment) continue;

            $student = $enrollment->student;

            // Mark current enrollment as completed/promoted
            $enrollment->update(['status' => $studentData['status'] == 'Aprobado' ? 'Promovido' : 'Repitiente']);

            // Create new enrollment for the next academic year if a target section is provided
            if (!empty($studentData['target_section_id'])) {
                \App\Models\Enrollment::create([
                    'student_id' => $enrollment->student_id,
                    'representative_id' => $enrollment->representative_id,
                    'section_id' => $studentData['target_section_id'],
                    'school_year' => $data['next_school_year'],
                    'status' => 'Inscrito',
                ]);
                // Ensure student remains Active if they have a target section
                $student->update(['status' => 'Activo', 'is_active' => true]);
            } else if ($studentData['status'] == 'Aprobado') {
                // Determine if they are Graduate (from 6th grade) or just Withdrawn
                // Primaria Level 2, Grade 6 -> Rank 26
                $currentGradeRank = ($section->grade->educational_level_id * 10) + $section->grade->number;
                
                if ($currentGradeRank === 26) {
                    // Graduate from the institution
                    $student->update(['status' => 'Egresado', 'is_active' => false]);
                    \App\Models\Graduate::create([
                        'student_id' => $enrollment->student_id,
                        'promotion_year' => $data['next_school_year'],
                        'notes' => 'Promovido y egresado oficialmente de 6to Grado.'
                    ]);
                } else {
                    // Withdrawn (Retirado) - approved but not continuing here
                    $student->update(['status' => 'Retirado', 'is_active' => false]);
                }
            } else {
                // If they failed and no target section was provided, they are also Retirados
                $student->update(['status' => 'Retirado', 'is_active' => false]);
            }
        }

        return redirect()->route('sections.show', $section->id)->with('success', 'Cierre de año escolar procesado exitosamente.');
    }

    public function gradesView(Request $request, Section $section)
    {
        $currentYear = $this->getCurrentSchoolYear();

        // RBAC Check
        if (auth()->user()->isDocente()) {
            $assignedId = auth()->user()->assignedSectionId($currentYear);
            if ($assignedId != $section->id) {
                return redirect()->route('dashboard')->with('error', 'No tienes permiso para cargar notas en esta aula.');
            }
        }
        
        // Only subjects assigned to this specific grade/level
        $subjects = $section->grade->subjects()->where('is_active', true)->orderBy('name')->get();
        
        if ($subjects->isEmpty()) {
            return redirect()->route('sections.show', $section->id)->with('warning', 'No hay materias asignadas a este grado. Por favor, configura las materias primero.');
        }
        
        $subjectId = $request->query('subject_id', $subjects->first()?->id);
        $term = $request->query('term', 1);

        $enrollments = $section->enrollments()
            ->where('enrollments.school_year', $currentYear)
            ->join('students', 'enrollments.student_id', '=', 'students.id')
            ->orderBy('students.first_name')
            ->orderBy('students.last_name')
            ->select('enrollments.*')
            ->with(['student', 'assessments' => function($q) use ($subjectId, $term) {
                $q->where('subject_id', $subjectId)->where('term', $term);
            }])
            ->get();

        return view('sections.grades', compact('section', 'subjects', 'enrollments', 'subjectId', 'term', 'currentYear'));
    }

    public function gradesStore(Request $request, Section $section)
    {
        $currentYear = $this->getCurrentSchoolYear();

        // RBAC Check
        if (auth()->user()->isDocente()) {
            $assignedId = auth()->user()->assignedSectionId($currentYear);
            if ($assignedId != $section->id) {
                return redirect()->route('dashboard')->with('error', 'No tienes permiso para guardar notas en esta aula.');
            }
        }

        $validated = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'term' => 'required|integer|min:1|max:3',
            'grades' => 'required|array',
            'grades.*.score' => 'nullable|in:A,B,C,D,E',
            'grades.*.observations' => 'nullable|string',
        ]);

        foreach ($validated['grades'] as $enrollmentId => $data) {
            if ($data['score'] === null) {
                // Remove if score is empty? Maybe better just not update.
                continue;
            }

            \App\Models\Assessment::updateOrCreate(
                [
                    'enrollment_id' => $enrollmentId,
                    'subject_id' => $validated['subject_id'],
                    'term' => $validated['term'],
                ],
                [
                    'score' => $data['score'],
                    'observations' => $data['observations'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Calificaciones actualizadas exitosamente.');
    }

    public function destroyEnrollment(\App\Models\Enrollment $enrollment)
    {
        $sectionId = $enrollment->section_id;
        $enrollment->delete();
        return redirect()->route('sections.show', $sectionId)->with('success', 'Estudiante removido del aula correctamente.');
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return redirect()->route('sections.index')->with('success', 'Sección eliminada.');
    }
}
