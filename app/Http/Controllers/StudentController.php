<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Representative;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = \App\Models\SchoolYear::current();
        
        $query = Student::query();

        // Filter by Search (Name or Cedula)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('cedula', 'like', "%{$search}%");
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Pending Assignment (Registered by Rep but no Enrollment)
        if ($request->get('filter') === 'pending') {
            $query->whereNotNull('representative_id')
                ->whereDoesntHave('enrollments', function($q) use ($currentYear) {
                    $q->where('school_year', $currentYear);
                });
        }

        $students = $query->with(['enrollments' => function($q) use ($currentYear) {
            $q->where('school_year', $currentYear)->with('section.grade');
        }])->orderByDesc('is_active')
           ->orderBy('first_name')
           ->orderBy('last_name')
           ->paginate(30)
           ->withQueryString();

        $sections = \App\Models\Section::with('grade')->get();
        
        return view('students.index', compact('students', 'currentYear', 'sections'));
    }

    public function create()
    {
        $sections = \App\Models\Section::with('grade')->get();
        $representatives = \App\Models\User::all()->map(function($user) {
            return (object)[
                'id' => $user->id, // This is now a user_id!
                'user' => (object)[
                    'first_name' => $user->first_name,
                    'last_name'  => $user->last_name,
                    'cedula'     => $user->cedula,
                ],
                'relationship' => $user->role->name ?? 'Usuario'
            ];
        })->sortBy(function($rep) {
            return $rep->user->first_name;
        })->values();
        $allergies  = \App\Models\Allergy::orderBy('category')->orderBy('name')->get();
        $activities = \App\Models\Activity::orderBy('category')->orderBy('name')->get();
        return view('students.create', compact('sections', 'representatives', 'allergies', 'activities'));
    }

    public function store(Request $request)
    {
        if ($request->has('cedula') && $request->cedula) {
            $request->merge(['cedula' => str_replace('.', '', $request->cedula)]);
        }

        if ($request->filled('representative_id')) {
            $user = \App\Models\User::find($request->representative_id);
            if ($user) {
                $rep = \App\Models\Representative::firstOrCreate(
                    ['user_id' => $user->id],
                    ['relationship' => 'Representante']
                );
                $request->merge(['representative_id' => $rep->id]);
            }
        }

        $isHistoricalGraduate = $request->boolean('is_historical_graduate');
        $rules = \App\Services\StudentRegistrationService::validationRules(true, $isHistoricalGraduate);
        $request->validate($rules);

        \App\Services\StudentRegistrationService::register(
            $request->all(),
            $request->representative_id,
            $request->section_id,
            $isHistoricalGraduate
        );

        $msg = $isHistoricalGraduate
            ? 'Egresado histórico registrado correctamente.'
            : 'Estudiante registrado e inscrito correctamente.';

        return redirect()->route('students.index')->with('success', $msg);
    }

    public function edit(Student $student)
    {
        $sections = \App\Models\Section::with('grade')->get();
        $representatives = \App\Models\User::all()->map(function($user) {
            return (object)[
                'id' => $user->id,
                'user' => (object)[
                    'first_name' => $user->first_name,
                    'last_name'  => $user->last_name,
                    'cedula'     => $user->cedula,
                ],
                'relationship' => $user->role->name ?? 'Usuario'
            ];
        })->sortBy(function($rep) {
            return $rep->user->first_name;
        })->values();

        $currentRepId     = $student->representative_id;
        $currentSectionId = $student->currentEnrollment()?->section_id;
        $allergies        = \App\Models\Allergy::orderBy('category')->orderBy('name')->get();
        $activities       = \App\Models\Activity::orderBy('category')->orderBy('name')->get();
        $studentAllergies = $student->allergies->pluck('id')->toArray();
        $studentActivities= $student->activities->pluck('id')->toArray();

        // Resolve missing geo IDs from old records using text names
        $address = $student->address;
        if ($address && !$address->estado_id && ($address->state || $address->municipality)) {
            $normalizeStr = fn($s) => mb_strtolower(trim($s ?? ''));

            // Try to find state by name
            $states = \Illuminate\Support\Facades\DB::table('estados')->get();
            $matchedEstado = $states->first(function($e) use ($normalizeStr, $address) {
                return $normalizeStr($e->estado) === $normalizeStr($address->state);
            });

            if (!$matchedEstado && $address->municipality) {
                // Try to find state via municipality name
                $matchedMunicipio = \Illuminate\Support\Facades\DB::table('municipios')
                    ->whereRaw('LOWER(municipio) = ?', [mb_strtolower(trim($address->municipality))])
                    ->first();
                if ($matchedMunicipio) {
                    $matchedEstado = $states->firstWhere('id_estado', $matchedMunicipio->id_estado);
                }
            }

            if ($matchedEstado) {
                $address->estado_id = $matchedEstado->id_estado;
                // Find municipality
                $matchedMunicipio = \Illuminate\Support\Facades\DB::table('municipios')
                    ->where('id_estado', $matchedEstado->id_estado)
                    ->whereRaw('LOWER(municipio) = ?', [mb_strtolower(trim($address->municipality ?? ''))])
                    ->first();
                if ($matchedMunicipio) {
                    $address->municipio_id = $matchedMunicipio->id_municipio;
                    if (!$address->municipality) $address->municipality = $matchedMunicipio->municipio;
                    // Find parish
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

        return view('students.edit', compact(
            'student', 'sections', 'representatives',
            'currentRepId', 'currentSectionId',
            'allergies', 'activities', 'studentAllergies', 'studentActivities'
        ));
    }

    public function update(Request $request, Student $student)
    {
        if ($request->has('cedula') && $request->cedula) {
            $request->merge(['cedula' => str_replace('.', '', $request->cedula)]);
        }

        if ($request->filled('representative_id')) {
            $user = \App\Models\User::find($request->representative_id);
            if ($user) {
                $rep = \App\Models\Representative::firstOrCreate(
                    ['user_id' => $user->id],
                    ['relationship' => 'Representante']
                );
                $request->merge(['representative_id' => $rep->id]);
            }
        }

        $isHistoricalGraduate = $student->status === 'Egresado';
        $rules = \App\Services\StudentRegistrationService::validationRules(true, $isHistoricalGraduate);
        $request->validate($rules);

        \App\Services\StudentRegistrationService::update($student, $request->all());

        // 1. Handle Representative Re-assignment
        if ($request->filled('representative_id')) {
            $student->update(['representative_id' => $request->representative_id]);
            $student->currentEnrollment()?->update([
                'representative_id' => $request->representative_id
            ]);
        }

        // 2. Handle Section Assignment (Classroom)
        if ($request->filled('section_id')) {
            $currentYear = \App\Models\SchoolYear::current();
            \App\Models\Enrollment::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'school_year' => $currentYear
                ],
                [
                    'section_id' => $request->section_id,
                    'representative_id' => $request->representative_id ?? $student->representative_id,
                    'status' => 'Inscrito'
                ]
            );
        }

        return redirect()->route('students.index')->with('success', 'Datos del estudiante actualizados correctamente.');
    }

    public function toggleStatus(Student $student)
    {
        if ($student->status === 'Egresado') {
            return back()->with('error', 'No se puede reactivar a un estudiante egresado.');
        }

        $newActive = !$student->is_active;
        $student->update([
            'is_active' => $newActive,
            'status' => $newActive ? 'Activo' : 'Retirado'
        ]);

        $statusMessage = $newActive ? 'habilitado' : 'deshabilitado/retirado';
        return back()->with('success', "Estudiante {$statusMessage} correctamente.");
    }

    public function show(Student $student)
    {
        $student->load(['address', 'academicBackground', 'enrollments.representative', 'representative']);
        return view('students.show', compact('student'));
    }
}
