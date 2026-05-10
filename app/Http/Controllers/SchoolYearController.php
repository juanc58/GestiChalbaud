<?php

namespace App\Http\Controllers;

use App\Models\SchoolYear;
use Illuminate\Http\Request;

class SchoolYearController extends Controller
{
    public function index()
    {
        $years = SchoolYear::orderBy('year', 'desc')->get();
        return view('school-years.index', compact('years'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'year' => [
                'required',
                'string',
                'unique:school_years,year',
                'regex:/^\d{4}-\d{4}$/',
                function ($attribute, $value, $fail) {
                    $parts = explode('-', $value);
                    if (count($parts) === 2) {
                        $start = (int)$parts[0];
                        $end = (int)$parts[1];
                        if ($end !== $start + 1) {
                            $fail('El periodo debe ser continuo (ej: 2026-2027). El segundo año debe ser el siguiente al primero.');
                        }
                    }
                },
            ],
        ], [
            'year.unique' => 'Este año escolar ya ha sido registrado previamente.',
            'year.regex' => 'El formato debe ser YYYY-YYYY (ej. 2026-2027).',
        ]);

        SchoolYear::create([
            'year' => $request->year,
            'is_active' => false,
        ]);

        return redirect()->route('school-years.index')->with('success', 'Año escolar creado exitosamente.');
    }

    public function activate(SchoolYear $schoolYear)
    {
        SchoolYear::where('id', '!=', $schoolYear->id)->update(['is_active' => false]);
        $schoolYear->update(['is_active' => true]);

        return redirect()->route('school-years.index')->with('success', "El año escolar {$schoolYear->year} ahora es el periodo ACTIVO del sistema.");
    }

    public function destroy(SchoolYear $schoolYear)
    {
        if ($schoolYear->is_active) {
            return back()->with('error', 'No se puede eliminar el periodo activo.');
        }
        
        $schoolYear->delete();
        return redirect()->route('school-years.index')->with('success', 'Año escolar eliminado.');
    }
}
