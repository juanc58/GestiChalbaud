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
        $teachers = Teacher::with(['user', 'assignments.section.grade'])->get()
            ->sortBy('first_name')
            ->values();

        return view('teachers.index', compact('teachers'));
    }

    public function create()
    {
        $currentYear = $this->getCurrentSchoolYear();
        $sections = Section::whereDoesntHave('teacherAssignments', function ($q) use ($currentYear) {
            $q->where('school_year', $currentYear);
        })->with('grade')->get();

        return view('teachers.create', compact('sections', 'currentYear'));
    }

    public function store(Request $request)
    {
        if ($request->filled('cedula')) {
            $request->merge(['cedula' => preg_replace('/\D/', '', (string) $request->cedula)]);
        }

        $request->validate([
            'photo'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cedula'          => 'required|digits_between:6,10|unique:users,cedula',
            'first_name'      => ['required', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'second_name'     => ['nullable', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'last_name'       => ['required', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'second_last_name'=> ['nullable', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'gender'          => 'nullable|in:M,F',
            'birth_date'      => ['required', new \App\Rules\SpanishDate(minAge: 18, mustBeBeforeToday: true)],
            'phone'           => ['required', 'string', 'max:50', new \App\Rules\VenezuelanPhone()],
            'email'           => ['required', 'string', 'max:255', new \App\Rules\StrictEmail(), 'unique:users,email'],
            'entry_date'      => ['required', new \App\Rules\SpanishDate()],
            'academic_degree' => 'required|string|max:255',
            'section_id'      => 'nullable|exists:sections,id',
        ]);

        $docenteRole = Role::firstOrCreate(['name' => 'docente']);

        // 1. Create User (User::saved ensures Teacher profile exists; updateOrCreate below populates extended fields without duplicating)
        $user = User::updateOrCreate(
            ['cedula' => $request->cedula],
            [
                'first_name'       => $request->first_name,
                'second_name'      => $request->second_name,
                'last_name'        => $request->last_name,
                'second_last_name' => $request->second_last_name,
                'phone'            => \App\Rules\VenezuelanPhone::normalize($request->phone),
                'email'            => mb_strtolower(trim($request->email), 'UTF-8'),
                'password'         => Hash::make($request->cedula),
                'role_id'          => $docenteRole->id,
                'is_active'        => true,
            ]
        );

        $photoPath = $request->hasFile('photo')
            ? $request->file('photo')->store('teachers', 'public')
            : null;

        // 2. Sync Teacher profile with full labor details
        $teacher = Teacher::updateOrCreate(
            ['user_id' => $user->id],
            [
                'photo_path'      => $photoPath,
                'gender'          => $request->gender,
                'birth_date'      => \App\Rules\SpanishDate::toDatabase($request->birth_date),
                'entry_date'      => \App\Rules\SpanishDate::toDatabase($request->entry_date),
                'academic_degree' => $request->academic_degree,
                'status'          => 'Activo',
            ]
        );

        // 3. Optional Initial Classroom Assignment
        if ($request->section_id) {
            $currentYear = $this->getCurrentSchoolYear();
            TeacherAssignment::updateOrCreate(
                [
                    'section_id'  => $request->section_id,
                    'school_year' => $currentYear,
                ],
                [
                    'teacher_id' => $teacher->id,
                ]
            );
        }

        return redirect()->route('teachers.index')->with('success', 'Docente y usuario activos registrados. Clave inicial: ' . $request->cedula);
    }

    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'assignments.section.grade']);
        $currentYear = $this->getCurrentSchoolYear();
        $sections = Section::with('grade')->get();
        return view('teachers.show', compact('teacher', 'currentYear', 'sections'));
    }

    public function assignSection(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'section_id'  => 'required|exists:sections,id',
            'school_year' => 'required|string',
        ]);

        TeacherAssignment::updateOrCreate(
            [
                'section_id'  => $validated['section_id'],
                'school_year' => $validated['school_year'],
            ],
            [
                'teacher_id' => $teacher->id,
            ]
        );

        TeacherAssignment::where('teacher_id', $teacher->id)
            ->where('school_year', $validated['school_year'])
            ->where('section_id', '!=', $validated['section_id'])
            ->delete();

        return back()->with('success', 'Aula asignada exitosamente.');
    }

    public function unassignSection(Teacher $teacher, $assignmentId)
    {
        if (!auth()->user()->isAdmin()) {
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
        $teacher->load('user');
        return view('teachers.edit', compact('teacher'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        if ($request->filled('cedula')) {
            $request->merge(['cedula' => preg_replace('/\D/', '', (string) $request->cedula)]);
        }

        $userId = $teacher->user?->id ?? 0;

        $request->validate([
            'photo'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_photo'    => 'nullable|boolean',
            'cedula'          => 'required|digits_between:6,10|unique:users,cedula,' . $userId,
            'first_name'      => ['required', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'second_name'     => ['nullable', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'last_name'       => ['required', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'second_last_name'=> ['nullable', 'string', 'max:255', new \App\Rules\LettersOnlyName()],
            'gender'          => 'nullable|in:M,F',
            'birth_date'      => ['required', new \App\Rules\SpanishDate(minAge: 18, mustBeBeforeToday: true)],
            'phone'           => ['required', 'string', 'max:50', new \App\Rules\VenezuelanPhone()],
            'email'           => ['required', 'string', 'max:255', new \App\Rules\StrictEmail(), 'unique:users,email,' . $userId],
            'entry_date'      => ['required', new \App\Rules\SpanishDate()],
            'academic_degree' => 'nullable|string|max:255',
            'status'          => 'required|in:Activo,Inactivo',
        ]);

        if ($teacher->user) {
            $teacher->user->update([
                'cedula'           => $request->cedula,
                'first_name'       => $request->first_name,
                'second_name'      => $request->second_name,
                'last_name'        => $request->last_name,
                'second_last_name' => $request->second_last_name,
                'phone'            => \App\Rules\VenezuelanPhone::normalize($request->phone),
                'email'            => mb_strtolower(trim($request->email), 'UTF-8'),
            ]);
        }

        $photoPath = $teacher->photo_path;
        if ($request->boolean('remove_photo') && $photoPath) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($photoPath);
            $photoPath = null;
        }
        if ($request->hasFile('photo')) {
            if ($photoPath) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('teachers', 'public');
        }

        $teacher->update([
            'photo_path'      => $photoPath,
            'gender'          => $request->gender ?: null,
            'birth_date'      => \App\Rules\SpanishDate::toDatabase($request->birth_date),
            'entry_date'      => \App\Rules\SpanishDate::toDatabase($request->entry_date) ?: $teacher->entry_date,
            'academic_degree' => $request->academic_degree ?: 'Docente Institucional',
            'status'          => $request->status,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Datos del docente actualizados.');
    }

    public function destroy(Teacher $teacher)
    {
        if ($teacher->photo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($teacher->photo_path);
        }
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'Ficha docente eliminada.');
    }
}
