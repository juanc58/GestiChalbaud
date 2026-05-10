<?php

namespace App\Http\Controllers\Representative;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\SchoolYear;
use App\Models\Section;
use Illuminate\Http\Request;

class MyStudentsController extends Controller
{
    public function index()
    {
        $representative = auth()->user()->representative;
        if (!$representative) abort(404, 'Perfil de representante no encontrado.');
        
        // Find students linked directly or via enrollments
        $students = Student::where('representative_id', $representative->id)
            ->orWhereHas('enrollments', function($q) use ($representative) {
                $q->where('representative_id', $representative->id);
            })
            ->with(['enrollments' => function($q) use ($representative) {
                $q->with('section.grade');
            }])->get();

        return view('representative.students.index', compact('students'));
    }

    public function create()
    {
        $sections = Section::with('grade')->get();
        $allergies  = \App\Models\Allergy::orderBy('category')->orderBy('name')->get();
        $activities = \App\Models\Activity::orderBy('category')->orderBy('name')->get();
        return view('representative.students.create', compact('sections', 'allergies', 'activities'));
    }

    public function store(Request $request)
    {
        if ($request->has('cedula') && $request->cedula) {
            $request->merge(['cedula' => str_replace('.', '', $request->cedula)]);
        }

        $rules = \App\Services\StudentRegistrationService::validationRules(false);
        $request->validate($rules);

        $representative = auth()->user()->representative;

        \App\Services\StudentRegistrationService::register(
            $request->all(),
            $representative->id
        );

        return redirect()->route('representative.students.index')->with('success', 'Tu hijo(a) ha sido registrado(a) correctamente. Un administrador o docente debe asignarlo(a) a un aula.');
    }

    public function edit(Student $student)
    {
        $representative = auth()->user()->representative;
        if ($student->representative_id !== $representative->id) {
            abort(403);
        }

        $sections = Section::with('grade')->get();
        $allergies  = \App\Models\Allergy::orderBy('category')->orderBy('name')->get();
        $activities = \App\Models\Activity::orderBy('category')->orderBy('name')->get();
        $studentAllergies = $student->allergies->pluck('id')->toArray();
        $studentActivities= $student->activities->pluck('id')->toArray();

        // Resolve missing geo IDs from old records using text names
        $address = $student->address;
        if ($address && !$address->estado_id && ($address->state || $address->municipality)) {
            $normalizeStr = fn($s) => mb_strtolower(trim($s ?? ''));

            $states = \Illuminate\Support\Facades\DB::table('estados')->get();
            $matchedEstado = $states->first(function($e) use ($normalizeStr, $address) {
                return $normalizeStr($e->estado) === $normalizeStr($address->state);
            });

            if (!$matchedEstado && $address->municipality) {
                $matchedMunicipio = \Illuminate\Support\Facades\DB::table('municipios')
                    ->whereRaw('LOWER(municipio) = ?', [mb_strtolower(trim($address->municipality))])
                    ->first();
                if ($matchedMunicipio) {
                    $matchedEstado = $states->firstWhere('id_estado', $matchedMunicipio->id_estado);
                }
            }

            if ($matchedEstado) {
                $address->estado_id = $matchedEstado->id_estado;
                $matchedMunicipio = \Illuminate\Support\Facades\DB::table('municipios')
                    ->where('id_estado', $matchedEstado->id_estado)
                    ->whereRaw('LOWER(municipio) = ?', [mb_strtolower(trim($address->municipality ?? ''))])
                    ->first();
                if ($matchedMunicipio) {
                    $address->municipio_id = $matchedMunicipio->id_municipio;
                    if (!$address->municipality) $address->municipality = $matchedMunicipio->municipio;
                    $matchedParroquia = \Illuminate\Support\Facades\DB::table('parroquias')
                        ->where('id_municipio', $matchedMunicipio->id_municipio)
                        ->whereRaw('LOWER(parroquia) = ?', [mb_strtolower(trim($address->parish ?? ''))])
                        ->first();
                    if ($matchedParroquia) {
                        $address->parroquia_id = $matchedParroquia->id_parroquia;
                        if (!$address->parish) $address->parish = $matchedParroquia->parroquia;
                    }
                }
            }
        }

        return view('representative.students.edit', compact('student', 'sections', 'allergies', 'activities', 'studentAllergies', 'studentActivities'));
    }

    public function update(Request $request, Student $student)
    {
        $representative = auth()->user()->representative;
        if ($student->representative_id !== $representative->id) {
            abort(403);
        }

        if ($request->has('cedula') && $request->cedula) {
            $request->merge(['cedula' => str_replace('.', '', $request->cedula)]);
        }

        $rules = \App\Services\StudentRegistrationService::validationRules(false);
        $request->validate($rules);

        \App\Services\StudentRegistrationService::update($student, $request->all());

        return redirect()->route('representative.students.index')->with('success', 'Datos del estudiante actualizados correctamente.');
    }

    public function academicRecord(Student $student, Request $request)
    {
        $representative = auth()->user()->representative;

        // Security check: Must be the representative or have an enrollment linked
        $isAssociated = ($student->representative_id === $representative->id) || 
            $student->enrollments()->where('representative_id', $representative->id)->exists();
            
        if (!$isAssociated) abort(403, 'No tienes permiso para ver este registro.');

        // Get all available school years for this student
        $availableYears = $student->enrollments()
            ->select('school_year')
            ->distinct()
            ->orderBy('school_year', 'desc')
            ->pluck('school_year');

        // Selected year (default to latest if not provided)
        $selectedYear = $request->get('school_year', $availableYears->first());

        // If no enrollments at all, show friendly "no records" page
        if ($availableYears->isEmpty()) {
            return view('representative.students.no_records', compact('student'));
        }

        // Load filtered enrollment with details
        $enrollment = $student->enrollments()
            ->where('school_year', $selectedYear)
            ->with(['section.grade', 'assessments.subject'])
            ->first();

        return view('representative.students.grades', compact('student', 'enrollment', 'availableYears', 'selectedYear'));
    }
}
