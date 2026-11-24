<?php

namespace App\Http\Controllers;

use App\Mail\InstitutionalComposeMail;
use App\Models\Enrollment;
use App\Models\IssuedDocument;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Rules\StrictEmail;
use App\Services\DocumentGeneratorService;
use App\Services\SmtpMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailComposerController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isDocente()) {
            abort(403, 'No tienes permisos para acceder al módulo de correo institucional.');
        }

        $currentYear = SchoolYear::current();
        $preselectedStudentId = (int) old('student_id', $request->integer('student_id'));
        $preselectedDoc = trim((string) $request->query('doc', ''));
        $preselectedStudent = $preselectedStudentId
            ? Student::with(['representative', 'enrollments.section.grade'])->find($preselectedStudentId)
            : null;

        $recentSent = IssuedDocument::with(['student', 'issuer'])
            ->where('delivery_channel', 'email')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('mail.compose', compact('preselectedStudent', 'preselectedStudentId', 'preselectedDoc', 'currentYear', 'recentSent'));
    }

    public function searchStudents(Request $request)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isDocente()) {
            abort(403);
        }

        $q = trim((string) $request->query('q', ''));
        if (mb_strlen($q, 'UTF-8') < 2) {
            return response()->json([]);
        }

        $currentYear = SchoolYear::current();
        $query = Student::with([
            'representative',
            'graduate',
            'enrollments' => fn ($eq) => $eq->with('section.grade')->orderByDesc('school_year'),
        ]);

        if ($user->isDocente()) {
            $assignedId = $user->assignedSectionId($currentYear);
            $query->whereHas('enrollments', function ($eq) use ($currentYear, $assignedId) {
                $eq->where('school_year', $currentYear)->where('section_id', $assignedId ?: 0);
            });
        }

        $query->where(function ($outer) use ($q) {
            $outer->where('first_name', 'like', "%{$q}%")
                ->orWhere('second_name', 'like', "%{$q}%")
                ->orWhere('last_name', 'like', "%{$q}%")
                ->orWhere('second_last_name', 'like', "%{$q}%")
                ->orWhere('cedula', 'like', "%{$q}%")
                ->orWhereHas('representative', function ($rq) use ($q) {
                    $rq->where('first_name', 'like', "%{$q}%")
                       ->orWhere('last_name', 'like', "%{$q}%")
                       ->orWhere('cedula', 'like', "%{$q}%");
                });
        });

        $results = $query->orderBy('last_name')->orderBy('first_name')->limit(15)->get()->map(function (Student $stu) {
            $latestEnr = $stu->enrollments->first();
            $rep = $stu->representative ?? $latestEnr?->representative;
            $gradeLabel = $stu->status === 'Egresado'
                ? 'Egresado (' . ($stu->graduate?->promotion_year ?? 'Histórico') . ')'
                : ($latestEnr?->section ? ($latestEnr->section->grade->name . ' "' . $latestEnr->section->name . '"') : $stu->status);

            return [
                'id'          => $stu->id,
                'full_name'   => $stu->full_name,
                'cedula'      => $stu->cedula ? 'V-' . $stu->cedula : 'S/C',
                'status'      => $stu->status,
                'grade_label' => $gradeLabel,
                'rep_name'    => $rep?->full_name,
                'rep_email'   => $rep?->email,
            ];
        });

        return response()->json($results);
    }

    public function studentDocuments(Student $student)
    {
        $user = auth()->user();
        $currentYear = SchoolYear::current();

        if ($user->isDocente()) {
            $assignedId = $user->assignedSectionId($currentYear);
            $belongs = $student->enrollments()
                ->where('school_year', $currentYear)
                ->where('section_id', $assignedId ?: 0)
                ->exists();
            if (!$belongs) {
                abort(403);
            }
        }

        $student->load(['representative', 'graduate', 'enrollments.section.grade', 'enrollments.assessments']);
        $rep = $student->representative ?? $student->enrollments->first()?->representative;

        $docs = [];
        foreach ($student->enrollments->sortByDesc('school_year') as $enr) {
            $docs[] = [
                'key'           => 'boletin:' . $enr->id,
                'label'         => "Boletín Informativo — {$enr->section?->grade?->name} ({$enr->school_year})",
                'available'     => true,
                'reason'        => null,
            ];
        }

        $hasRecord = DocumentGeneratorService::hasAcademicRecordData($student);
        $docs[] = [
            'key'       => 'record_academico',
            'label'     => 'Récord Académico Histórico',
            'available' => $hasRecord,
            'reason'    => $hasRecord ? null : 'El estudiante no posee historial académico registrado.',
        ];

        $types = [
            'estudio'       => 'Constancia de Estudio',
            'inscripcion'   => 'Constancia de Inscripción',
            'prosecucion'   => 'Constancia de Prosecución de Estudios',
            'justificativo' => 'Justificativo Institucional',
        ];

        foreach ($types as $type => $label) {
            $err = DocumentGeneratorService::checkDocumentEligibility($student, $type);
            $docs[] = [
                'key'       => $type,
                'label'     => $label,
                'available' => $err === null,
                'reason'    => $err,
            ];
        }

        return response()->json([
            'student_name'  => $student->full_name,
            'student_status'=> $student->status,
            'rep_name'      => $rep?->full_name,
            'rep_email'     => $rep?->email,
            'documents'     => $docs,
        ]);
    }

    public function send(Request $request)
    {
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isDocente()) {
            abort(403);
        }

        $validated = $request->validate([
            'recipient_email' => ['required', 'string', 'max:255', new StrictEmail()],
            'subject'         => ['required', 'string', 'max:200'],
            'body'            => ['required', 'string', 'max:5000'],
            'student_id'      => ['nullable', 'exists:students,id'],
            'attachments'     => ['nullable', 'array'],
            'attachments.*'   => ['string', 'max:60'],
        ]);

        $recipientEmail = mb_strtolower(trim($validated['recipient_email']), 'UTF-8');
        $attachedDocs = [];

        if (!empty($validated['student_id']) && !empty($validated['attachments'])) {
            $student = Student::findOrFail($validated['student_id']);

            if ($user->isDocente()) {
                $currentYear = SchoolYear::current();
                $assignedId = $user->assignedSectionId($currentYear);
                $belongs = $student->enrollments()
                    ->where('school_year', $currentYear)
                    ->where('section_id', $assignedId ?: 0)
                    ->exists();
                if (!$belongs) {
                    abort(403, 'Solo puedes adjuntar documentos de estudiantes de tu aula asignada.');
                }
            }

            foreach ($validated['attachments'] as $key) {
                if (str_starts_with($key, 'boletin:')) {
                    $enrId = (int) substr($key, 8);
                    $enrollment = Enrollment::where('id', $enrId)->where('student_id', $student->id)->first();
                    if (!$enrollment) {
                        return back()->withInput()->with('error', 'La matrícula seleccionada para el boletín no pertenece al estudiante.');
                    }
                    $attachedDocs[] = DocumentGeneratorService::generatePdfAttachment($student, 'boletin', 'email', $recipientEmail, $enrollment);
                } else {
                    try {
                        $attachedDocs[] = DocumentGeneratorService::generatePdfAttachment($student, $key, 'email', $recipientEmail);
                    } catch (\Throwable $e) {
                        return back()->withInput()->with('error', $e->getMessage());
                    }
                }
            }
        }

        SmtpMailService::applyConfig();

        try {
            Mail::to($recipientEmail)->send(
                new InstitutionalComposeMail(
                    trim($validated['subject']),
                    trim($validated['body']),
                    $attachedDocs
                )
            );

            $countMsg = count($attachedDocs) > 0
                ? ' con ' . count($attachedDocs) . ' documento(s) PDF adjunto(s) del sistema'
                : '';

            return redirect()
                ->route('mail.compose')
                ->with('success', "Correo enviado exitosamente a {$recipientEmail}{$countMsg}.");
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'No se pudo enviar el correo: ' . $e->getMessage());
        }
    }
}
