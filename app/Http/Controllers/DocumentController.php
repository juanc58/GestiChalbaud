<?php

namespace App\Http\Controllers;

use App\Mail\StudentDocumentMail;
use App\Models\SchoolYear;
use App\Models\Section;
use App\Models\Student;
use App\Rules\SpanishDate;
use App\Rules\StrictEmail;
use App\Services\DocumentGeneratorService;
use App\Services\SmtpMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class DocumentController extends Controller
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
                abort(403, 'Solo puedes emitir constancias para estudiantes de tu aula asignada.');
            }
        }
    }

    public function index(Request $request)
    {
        $user        = auth()->user();
        $currentYear = SchoolYear::current();

        $query = Student::with([
            'representative',
            'graduate',
            'enrollments' => fn ($q) => $q->with('section.grade')->orderByDesc('school_year'),
        ]);

        if ($user->isDocente()) {
            $assignedId = $user->assignedSectionId($currentYear);
            $query->whereHas('enrollments', function ($q) use ($currentYear, $assignedId) {
                $q->where('school_year', $currentYear)->where('section_id', $assignedId ?: 0);
            });
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

        if ($request->filled('section_id')) {
            $secId = $request->section_id;
            $query->whereHas('enrollments', fn ($q) => $q->where('school_year', $currentYear)->where('section_id', $secId));
        }

        $students = $query->orderBy('last_name')->orderBy('first_name')->paginate(20)->withQueryString();
        $sections = Section::with('grade')->orderBy('grade_id')->orderBy('name')->get();

        return view('documents.index', compact('students', 'sections', 'currentYear'));
    }

    public function preview(Request $request, Student $student, string $type)
    {
        $this->authorizeStudentAccess($student);

        if (!in_array($type, DocumentGeneratorService::VALID_TYPES, true)) {
            abort(404, 'Tipo de documento institucional no válido.');
        }

        $eligibilityError = DocumentGeneratorService::checkDocumentEligibility($student, $type);
        if ($eligibilityError !== null) {
            return redirect()->route('documents.index')->with('error', $eligibilityError);
        }

        $data = DocumentGeneratorService::buildInstitutionalDocumentData($student, $type, $request->all(), false);
        return view('documents.print', $data);
    }

    public function downloadPdf(Request $request, Student $student, string $type)
    {
        $this->authorizeStudentAccess($student);

        if (!in_array($type, DocumentGeneratorService::VALID_TYPES, true)) {
            abort(404, 'Tipo de documento institucional no válido.');
        }

        try {
            $doc = DocumentGeneratorService::generatePdfAttachment(
                $student,
                $type,
                'pdf',
                null,
                null,
                $request->all()
            );
        } catch (\RuntimeException $e) {
            return redirect()->route('documents.index')->with('error', $e->getMessage());
        }

        return response($doc['binary'], 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $doc['filename'] . '"',
        ]);
    }

    public function sendEmail(Request $request, Student $student, string $type)
    {
        $this->authorizeStudentAccess($student);

        if (!in_array($type, DocumentGeneratorService::VALID_TYPES, true)) {
            abort(404, 'Tipo de documento institucional no válido.');
        }

        $validated = $request->validate([
            'recipient_email' => ['required', 'string', 'max:255', new StrictEmail()],
            'custom_message'  => 'nullable|string|max:500',
            'save_rep_email'  => 'nullable|boolean',
            'event_date'      => ['nullable', new SpanishDate()],
        ]);

        $email = mb_strtolower(trim($validated['recipient_email']), 'UTF-8');

        try {
            $doc = DocumentGeneratorService::generatePdfAttachment(
                $student,
                $type,
                'email',
                $email,
                null,
                $request->all()
            );
        } catch (\RuntimeException $e) {
            return redirect()->route('documents.index')->with('error', $e->getMessage());
        }

        $rep = $student->representative ?? $student->enrollments()->latest()->first()?->representative;
        if ($request->boolean('save_rep_email') && $rep) {
            $rep->update(['email' => $email]);
        }

        SmtpMailService::applyConfig();

        try {
            Mail::to($email)->send(
                new StudentDocumentMail(
                    $student,
                    $rep?->full_name ?? 'Representante Legal',
                    $doc['title'],
                    $doc['schoolYear'],
                    $doc['binary'],
                    $doc['filename'],
                    $validated['custom_message'] ?? null
                )
            );

            return back()->with('success', "{$doc['title']} enviada exitosamente en PDF ({$doc['folio']}) a {$email}.");
        } catch (\Throwable $e) {
            return back()->with('error', 'Error al enviar el documento por correo: ' . $e->getMessage());
        }
    }
}
