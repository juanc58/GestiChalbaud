<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        $subjects = Subject::with('grades')->orderBy('name')->get();
        return view('subjects.index', compact('subjects'));
    }

    public function create()
    {
        $grades = \App\Models\Grade::with('level')->get();
        return view('subjects.create', compact('grades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:subjects',
            'description' => 'nullable|string',
            'grade_ids' => 'required|array',
            'grade_ids.*' => 'exists:grades,id',
        ], [
            'name.unique' => 'Ya existe una materia registrada con este nombre.',
            'name.required' => 'El nombre de la materia es obligatorio.',
            'grade_ids.required' => 'Debes asignar la materia a al menos un grado/nivel.'
        ]);

        $subject = Subject::create($request->all());
        $subject->grades()->sync($request->grade_ids);

        return redirect()->route('subjects.index')->with('success', 'Materia creada y asignada exitosamente.');
    }

    public function edit(Subject $subject)
    {
        $grades = \App\Models\Grade::with('level')->get();
        $subject->load('grades');
        return view('subjects.edit', compact('subject', 'grades'));
    }

    public function update(Request $request, Subject $subject)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:subjects,name,' . $subject->id,
            'description' => 'nullable|string',
            'grade_ids' => 'required|array',
            'grade_ids.*' => 'exists:grades,id',
        ], [
            'name.unique' => 'Ya existe otra materia registrada con este nombre.',
            'grade_ids.required' => 'Debes asignar la materia a al menos un grado/nivel.'
        ]);

        $subject->update($request->all());
        $subject->grades()->sync($request->grade_ids);

        return redirect()->route('subjects.index')->with('success', 'Materia actualizada correctamente.');
    }

    public function destroy(Subject $subject)
    {
        if ($subject->assessments()->count() > 0) {
            $subject->update(['is_active' => false]);
            return redirect()->route('subjects.index')->with('warning', 'La materia tiene calificaciones asociadas. Se ha marcado como inactiva en lugar de eliminarla.');
        }

        $subject->delete();
        return redirect()->route('subjects.index')->with('success', 'Materia eliminada exitosamente.');
    }
}
