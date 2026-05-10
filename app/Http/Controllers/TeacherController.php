<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\User;
use App\Models\Role;
use App\Models\Section;
use App\Models\TeacherAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    private function getCurrentSchoolYear()
    {
        return \App\Models\SchoolYear::current();
    }

    public function index()
    {
        $teachers = Teacher::with('assignments.section')->get();
        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        $currentYear = $this->getCurrentSchoolYear();
        // Available sections (those without an assigned teacher for $currentYear)
        $sections = Section::whereDoesntHave('teacherAssignments', function($q) use ($currentYear) {
            $q->where('school_year', $currentYear);
        })->with('grade')->get();

        return view('teachers.create', compact('sections', 'currentYear'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cedula'          => 'required|integer|unique:users,cedula',
            'first_name'      => 'required|string|max:255',
            'second_name'     => 'nullable|string|max:255',
            'last_name'       => 'required|string|max:255',
            'second_last_name'=> 'nullable|string|max:255',
            'gender'          => 'required|in:M,F',
            'birth_date'      => 'required|date',
            'phone'           => 'nullable|string|max:50',
            'email'           => 'nullable|email|unique:users,email',
            'entry_date'      => 'required|date',
            'academic_degree' => 'required|string',
            'section_id'      => 'nullable|exists:sections,id',
        ]);

        $docenteRole = Role::where('name', 'docente')->first();

        // 1. Create/Update User with all shared identity fields
        $user = User::updateOrCreate(
            ['cedula' => $request->cedula],
            [
                'first_name'       => $request->first_name,
                'second_name'      => $request->second_name,
                'last_name'        => $request->last_name,
                'second_last_name' => $request->second_last_name,
                'phone'            => $request->phone,
                'email'            => $request->email ?? $request->cedula . '@sepaez.edu.ve',
                'password'         => Hash::make($request->cedula),
                'role_id'          => $docenteRole->id,
            ]
        );

        // 2. Create Teacher profile (specific data only)
        $teacher = Teacher::create([
            'user_id'         => $user->id,
            'gender'          => $request->gender,
            'birth_date'      => $request->birth_date,
            'entry_date'      => $request->entry_date,
            'academic_degree' => $request->academic_degree,
            'status'          => 'Activo',
        ]);

        // 3. Optional Initial Assignment
        if ($request->section_id) {
            $currentYear = $this->getCurrentSchoolYear();
            TeacherAssignment::create([
                'teacher_id'  => $teacher->id,
                'section_id'  => $request->section_id,
                'school_year' => $currentYear,
            ]);
        }

        return redirect()->route('teachers.index')->with('success', 'Docente registrado. Clave inicial: ' . $request->cedula);
    }

    public function show(Teacher $teacher)
    {
        $currentYear = $this->getCurrentSchoolYear();
        $sections = Section::with('grade')->get();
        return view('teachers.show', compact('teacher', 'currentYear', 'sections'));
    }

    public function assignSection(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'school_year' => 'required|string',
        ]);

        // Enforce uniqueness constraints (handled by unique index but good to check)
        TeacherAssignment::updateOrCreate(
            [
                'section_id' => $validated['section_id'],
                'school_year' => $validated['school_year'],
            ],
            [
                'teacher_id' => $teacher->id,
            ]
        );

        // Ensure teacher doesn't have multiple sections the same year
        TeacherAssignment::where('teacher_id', $teacher->id)
            ->where('school_year', $validated['school_year'])
            ->where('section_id', '!=', $validated['section_id'])
            ->delete();

        return back()->with('success', 'Aula asignada exitosamente.');
    }

    public function unassignSection(Teacher $teacher, $assignmentId)
    {
        // Admin only check
        if (auth()->user()->role->name !== 'admin') {
            return back()->with('error', 'No tienes permisos para esta acción.');
        }

        $assignment = TeacherAssignment::where('teacher_id', $teacher->id)
            ->where('id', $assignmentId)
            ->firstOrFail();
            
        $assignment->delete();

        return back()->with('success', 'Asignación de aula removida.');
    }

    public function edit(Teacher $teacher)
    {
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'cedula'          => 'required|integer|unique:users,cedula,' . ($teacher->user?->id ?? 0),
            'first_name'      => 'required|string|max:255',
            'second_name'     => 'nullable|string|max:255',
            'last_name'       => 'required|string|max:255',
            'second_last_name'=> 'nullable|string|max:255',
            'gender'          => 'required|in:M,F',
            'birth_date'      => 'required|date',
            'phone'           => 'nullable|string|max:50',
            'email'           => 'nullable|email',
            'entry_date'      => 'required|date',
            'academic_degree' => 'required|string',
            'status'          => 'required|in:Activo,Inactivo',
        ]);

        // Update user shared fields
        if ($teacher->user) {
            $teacher->user->update([
                'cedula'           => $request->cedula,
                'first_name'       => $request->first_name,
                'second_name'      => $request->second_name,
                'last_name'        => $request->last_name,
                'second_last_name' => $request->second_last_name,
                'phone'            => $request->phone,
                'email'            => $request->email ?? $teacher->user->email,
            ]);
        }

        // Update teacher-specific fields
        $teacher->update([
            'gender'          => $request->gender,
            'birth_date'      => $request->birth_date,
            'entry_date'      => $request->entry_date,
            'academic_degree' => $request->academic_degree,
            'status'          => $request->status,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Datos del docente actualizados.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'Docente eliminado.');
    }
}
