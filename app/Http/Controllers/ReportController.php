<?php

namespace App\Http\Controllers;

use App\Mail\StudentDocumentMail;
use App\Models\Enrollment;
use App\Rules\StrictEmail;
use App\Services\DocumentGeneratorService;
use App\Services\SmtpMailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{
    private function authorizeEnrollment(Enrollment $enrollment): void
    {
        if (auth()->check() && auth()->user()->isDocente()) {
            $assignedId = auth()->user()->assignedSectionId($enrollment->school_year);
            if ($assignedId && (int) $assignedId !== (int) $enrollment->section_id) {
                abort(403, 'Solo puedes consultar boletines de tu aula asignada.');
            }
        }
    }

    public function showBoletin(Enrollment $enrollment)
    {
        $this->authorizeEnrollment($enrollment);
        return view('reports.boletin', DocumentGeneratorService::buildBoletinData($enrollment, false));
    }

    public function downloadBoletinPdf(Enrollment $enrollment)
    {
        $this->authorizeEnrollment($enrollment);
        $doc = DocumentGeneratorService::generatePdfAttachment($enrollment->student, 'boletin', 'pdf', null, $enrollment);

        return response($doc['binary'], 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $doc['filename'] . '"',
        ]);
    }

    public function emailBoletin(Request $request, Enrollment $enrollment)
    {
        $this->authorizeEnrollment($enrollment);

        $validated = $request->validate([
            'recipient_email' => ['required', 'string', 'max:255', new StrictEmail()],
            'custom_message'  => 'nullable|string|max:500',
            'save_rep_email'  => 'nullable|boolean',
        ]);

        $email = mb_strtolower(trim($validated['recipient_email']), 'UTF-8');
        $rep   = $enrollment->representative ?? $enrollment->student->representative;

        if ($request->boolean('save_rep_email') && $rep) {
            $rep->update(['email' => $email]);
        }

        $doc = DocumentGeneratorService::generatePdfAttachment($enrollment->student, 'boletin', 'email', $email, $enrollment);

        SmtpMailService::applyConfig();

        try {
            Mail::to($email)->send(
                new StudentDocumentMail(
                    $enrollment->student,
                    $rep?->full_name ?? 'Representante Legal',
                    'BOLETÍN INFORMATIVO DE EVALUACIÓN',
                    $enrollment->school_year,
                    $doc['binary'],
                    $doc['filename'],
                    $validated['custom_message'] ?? null
                )
            );

            return back()->with('success', "Boletín enviado exitosamente en PDF ({$doc['folio']}) al correo {$email}.");
        } catch (\Throwable $e) {
            return back()->with('error', 'No se pudo enviar el correo: ' . $e->getMessage());
        }
    }
}
