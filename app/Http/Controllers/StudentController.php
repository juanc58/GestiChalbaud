<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Representative;
use App\Models\Enrollment;
use App\Services\StudentRegistrationService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $currentYear = \App\Models\SchoolYear::current();
        $user = auth()->user();

        $query = Student::query();

        // Scope to assigned classroom if logged-in user is Docente
        if ($user->isDocente()) {
            $assignedSectionId = $user->assignedSectionId($currentYear);
            $query->whereHas('enrollments', function ($q) use ($currentYear, $assignedSectionId) {
                $q->where('school_year', $currentYear)
                  ->where('section_id', $assignedSectionId ?: 0);
            });
        }

        // Filter by Search (Name or Cedula)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('second_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('second_last_name', 'like', "%{$search}%")
                  ->orWhere('cedula', 'like', "%{$search}%");
            });
        }

        // Filter by Section (Aula)
        if ($request->filled('section_id')) {
            $secId = $request->section_id;
            $query->whereHas('enrollments', function ($q) use ($currentYear, $secId) {
                $q->where('school_year', $currentYear)->where('section_id', $secId);
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Pending Classroom Assignment (Active students without enrollment in current year)
        if ($request->get('filter') === 'pending') {
            $query->where('status', 'Activo')
                ->whereDoesntHave('enrollments', function ($q) use ($currentYear) {
                    $q->where('school_year', $currentYear);
                });
        }

        $students = $query->with([
            'representative',
            'enrollments' => function ($q) use ($currentYear) {
                $q->where('school_year', $currentYear)->with('section.grade');
            },
        ])->orderByDesc('is_active')
           ->orderBy('first_name')
           ->orderBy('last_name')
           ->paginate(30)
           ->withQueryString();

        $sections = \App\Models\Section::with('grade')->get();

        return view('students.index', compact('students', 'currentYear', 'sections'));
    }

    public function create()
    {
        $sections   = \App\Models\Section::with('grade')->get();
        $allergies  = \App\Models\Allergy::orderBy('category')->orderBy('name')->get();
        $activities = \App\Models\Activity::orderBy('category')->orderBy('name')->get();

        return view('students.create', compact('sections', 'allergies', 'activities'));
    }

    public function store(Request $request)
    {
        if ($request->filled('cedula')) {
            $request->merge(['cedula' => preg_replace('/\D/', '', (string) $request->cedula)]);
        }
        if ($request->filled('rep_cedula')) {
            $request->merge(['rep_cedula' => preg_replace('/\D/', '', (string) $request->rep_cedula)]);
        }

        $isHistoricalGraduate = $request->boolean('is_historical_graduate');
        $rules = StudentRegistrationService::validationRules(true, $isHistoricalGraduate);
        $request->validate($rules);

        StudentRegistrationService::register(
            $request->all(),
            null,
            $request->section_id,
            $isHistoricalGraduate,
            $request->file('photo')
        );

        if ($isHistoricalGraduate) {
            return redirect()->route('graduates.index')->with('success', 'Egresado histórico registrado e incorporado al Libro de Egresados correctamente.');
        }

        return redirect()->route('students.index')->with('success', 'Estudiante y datos de representante registrados correctamente.');
    }

    public function edit(Student $student)
    {
        $student->load(['representative', 'enrollments.representative', 'address', 'academicBackground', 'allergies', 'activities']);

        $sections         = \App\Models\Section::with('grade')->get();
        $currentSectionId = $student->currentEnrollment()?->section_id;
        $allergies        = \App\Models\Allergy::orderBy('category')->orderBy('name')->get();
        $activities       = \App\Models\Activity::orderBy('category')->orderBy('name')->get();
        $studentAllergies = $student->allergies->pluck('id')->toArray();
        $studentActivities= $student->activities->pluck('id')->toArray();

        // Resolve missing geo IDs from old records using text names
        $address = $student->address;
        if ($address && !$address->estado_id && ($address->state || $address->municipality)) {
            $normalizeStr = fn ($s) => mb_strtolower(trim($s ?? ''));

            $states = \Illuminate\Support\Facades\DB::table('estados')->get();
            $matchedEstado = $states->first(function ($e) use ($normalizeStr, $address) {
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
                    if (!$address->municipality) {
                        $address->municipality = $matchedMunicipio->municipio;
                    }
                    $matchedParroquia = \Illuminate\Support\Facades\DB::table('parroquias')
                        ->where('id_municipio', $matchedMunicipio->id_municipio)
                        ->whereRaw('LOWER(parroquia) = ?', [mb_strtolower(trim($address->parish ?? ''))])
                        ->first();
                    if ($matchedParroquia) {
                        $address->parroquia_id = $matchedParroquia->id_parroquia;
                        if (!$address->parish) {
                            $address->parish = $matchedParroquia->parroquia;
                        }
                    }
                }
            }
        }

        return view('students.edit', compact(
            'student',
            'sections',
            'currentSectionId',
            'allergies',
            'activities',
            'studentAllergies',
            'studentActivities'
        ));
    }

    public function update(Request $request, Student $student)
    {
        if ($request->filled('cedula')) {
            $request->merge(['cedula' => preg_replace('/\D/', '', (string) $request->cedula)]);
        }
        if ($request->filled('rep_cedula')) {
            $request->merge(['rep_cedula' => preg_replace('/\D/', '', (string) $request->rep_cedula)]);
        }

        $isHistoricalGraduate = $student->status === 'Egresado';
        $rules = StudentRegistrationService::validationRules(auth()->user()->isAdmin(), $isHistoricalGraduate, $student->id);
        $request->validate($rules);

        StudentRegistrationService::update($student, $request->all(), $request->file('photo'));
        $student->refresh();

        // Handle Section Assignment (Classroom) for Admins
        if (auth()->user()->isAdmin() && $request->filled('section_id')) {
            $currentYear = \App\Models\SchoolYear::current();
            Enrollment::updateOrCreate(
                [
                    'student_id'  => $student->id,
                    'school_year' => $currentYear,
                ],
                [
                    'section_id'        => $request->section_id,
                    'representative_id' => $student->representative_id,
                    'status'            => 'Inscrito',
                ]
            );
        }

        return redirect()->route('students.index')->with('success', 'Datos del estudiante y representante actualizados correctamente.');
    }

    public function findRepresentativeByCedula($cedula)
    {
        $cleanCedula = (int) preg_replace('/\D/', '', (string) $cedula);
        if (!$cleanCedula) {
            return response()->json(['found' => false], 404);
        }

        $rep = Representative::where('cedula', $cleanCedula)->first();
        if (!$rep) {
            return response()->json(['found' => false], 404);
        }

        return response()->json([
            'found'             => true,
            'id'                => $rep->id,
            'cedula'            => $rep->cedula,
            'first_name'        => $rep->first_name,
            'second_name'       => $rep->second_name,
            'last_name'         => $rep->last_name,
            'second_last_name'  => $rep->second_last_name,
            'email'             => $rep->email,
            'phone_whatsapp'    => $rep->phone_whatsapp,
            'phone_local'       => $rep->phone_local,
            'facebook'          => $rep->facebook,
            'job_title'         => $rep->job_title,
            'workplace_address' => $rep->workplace_address,
            'relationship'      => $rep->relationship,
        ]);
    }

    public function toggleStatus(Student $student)
    {
        if ($student->status === 'Egresado') {
            return back()->with('error', 'No se puede reactivar a un estudiante egresado.');
        }

        $newActive = !$student->is_active;
        $student->update([
            'is_active' => $newActive,
            'status'    => $newActive ? 'Activo' : 'Retirado',
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
