<?php

namespace App\Http\Controllers;

use App\Mail\StudentDocumentMail;
use App\Models\Enrollment;
use App\Models\IssuedDocument;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Rules\StrictEmail;
use App\Services\DocumentGeneratorService;
use App\Services\SmtpMailService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AcademicRecordController extends Controller
{
    private function authorizeStudentAccess(Student $student): void
    {
        $user = auth()->user();
        if ($user->isDocente()) {
            $currentYear = SchoolYear::current();
            $assignedId = $user->assignedSectionId($currentYear);
            $belongs = $student->enrollments()
                ->where('section_id', $assignedId ?: 0)
                ->exists();
            if (!$belongs) {
                abort(403, 'Solo puedes consultar el récord académico de estudiantes de tu aula asignada.');
            }
        }
    }

    public function index(Request $request)
    {
        $user        = auth()->user();
        $currentYear = SchoolYear::current();
        $tab         = $request->input('tab', 'directory');
        $years       = SchoolYear::orderByDesc('year')->pluck('year')->toArray();
        if (!in_array($currentYear, $years)) {
            array_unshift($years, $currentYear);
        }

        $assignedId = $user->isDocente() ? $user->assignedSectionId($currentYear) : null;

        $sectionsQuery = Section::with('grade')->orderBy('grade_id')->orderBy('name');
        if ($user->isDocente()) {
            $sectionsQuery->where('id', $assignedId ?: 0);
        }
        $sections = $sectionsQuery->get();

        // TAB 2: SÁBANA DE NOTAS POR SECCIÓN
        $matrixData = null;
        if ($tab === 'matrix') {
            $matrixYear      = $request->input('school_year', $currentYear);
            $matrixSectionId = $user->isDocente()
                ? $assignedId
                : $request->input('section_id', $sections->first()?->id);
            $matrixTerm      = $request->input('term', 'final'); // '1', '2', '3', 'final'

            $matrixSection = $matrixSectionId ? Section::with('grade')->find($matrixSectionId) : null;
            if ($matrixSection) {
                $enrollments = Enrollment::with(['student', 'assessments.subject'])
                    ->where('section_id', $matrixSection->id)
                    ->where('school_year', $matrixYear)
                    ->join('students', 'enrollments.student_id', '=', 'students.id')
                    ->orderBy('students.last_name')
                    ->orderBy('students.first_name')
                    ->select('enrollments.*')
                    ->get();

                $gradeSubjects = $matrixSection->grade->subjects()->where('is_active', true)->orderBy('name')->get();
                $assessmentSubjects = $enrollments->flatMap->assessments->pluck('subject')->filter()->unique('id');
                $subjects = $gradeSubjects->merge($assessmentSubjects)->unique('id')->sortBy('name')->values();

                $matrixData = compact('matrixSection', 'matrixYear', 'matrixTerm', 'subjects', 'enrollments');
            }
        }

        // TAB 1: DIRECTORIO LONGITUDINAL DE ESTUDIANTES Y EGRESADOS
        $query = Student::with([
            'graduate',
            'representative',
            'enrollments' => fn ($q) => $q->with(['section.grade', 'assessments'])->orderByDesc('school_year'),
        ]);

        if ($user->isDocente()) {
            $query->whereHas('enrollments', fn ($q) => $q->where('section_id', $assignedId ?: 0));
        }

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('second_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('second_last_name', 'like', "%{$search}%")
                  ->orWhere('cedula', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('section_id') && $tab === 'directory') {
            $secId = $user->isDocente() ? ($assignedId ?: 0) : $request->section_id;
            $yr = $request->input('school_year', $currentYear);
            $query->whereHas('enrollments', function ($q) use ($secId, $yr) {
                $q->where('section_id', $secId)->where('school_year', $yr);
            });
        } elseif ($request->filled('school_year') && $tab === 'directory') {
            $yr = $request->school_year;
            $query->where(function ($outer) use ($yr) {
                $outer->whereHas('enrollments', fn ($q) => $q->where('school_year', $yr))
                      ->orWhereHas('graduate', fn ($q) => $q->where('promotion_year', $yr));
            });
        }

        $students = $query
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(25)
            ->withQueryString();

        return view('academic-records.index', compact(
            'students',
            'sections',
            'years',
            'currentYear',
            'tab',
            'matrixData'
        ));
    }

    public function show(Student $student)
    {
        $this->authorizeStudentAccess($student);
        return view('academic-records.show', DocumentGeneratorService::buildAcademicRecordData($student, false));
    }

    public function downloadPdf(Student $student)
    {
        $this->authorizeStudentAccess($student);
        if (!DocumentGeneratorService::hasAcademicRecordData($student)) {
            return back()->with('error', 'El estudiante aún no posee historial académico o de egreso registrado.');
        }

        $doc = DocumentGeneratorService::generatePdfAttachment($student, 'record_academico', 'pdf');
        return response($doc['binary'], 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $doc['filename'] . '"',
        ]);
    }

    public function sendEmail(Request $request, Student $student)
    {
        $this->authorizeStudentAccess($student);
        if (!DocumentGeneratorService::hasAcademicRecordData($student)) {
            return back()->with('error', 'El estudiante aún no posee historial académico o de egreso registrado.');
        }

        $validated = $request->validate([
            'recipient_email' => ['required', 'string', 'max:255', new StrictEmail()],
            'custom_message'  => 'nullable|string|max:500',
            'save_rep_email'  => 'nullable|boolean',
        ]);

        $email = mb_strtolower(trim($validated['recipient_email']), 'UTF-8');
        $rep = $student->representative ?? $student->enrollments()->latest()->first()?->representative;

        if ($request->boolean('save_rep_email') && $rep) {
            $rep->update(['email' => $email]);
        }

        $doc = DocumentGeneratorService::generatePdfAttachment($student, 'record_academico', 'email', $email);

        SmtpMailService::applyConfig();

        try {
            Mail::to($email)->send(
                new StudentDocumentMail(
                    $student,
                    $rep?->full_name ?? 'Representante Legal',
                    'RÉCORD ACADÉMICO HISTÓRICO ESTUDIANTIL',
                    $doc['schoolYear'],
                    $doc['binary'],
                    $doc['filename'],
                    $validated['custom_message'] ?? null
                )
            );

            return back()->with('success', "Récord académico enviado en PDF ({$doc['folio']}) a {$email}.");
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo enviar el correo: ' . $e->getMessage());
        }
    }
}
