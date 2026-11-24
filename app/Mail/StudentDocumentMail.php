<?php

namespace App\Mail;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StudentDocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    public Student $student;
    public string $representativeName;
    public string $documentTitle;
    public string $schoolYear;
    public string $pdfBinary;
    public string $pdfFilename;
    public ?string $customMessage;

    public function __construct(
        Student $student,
        string $representativeName,
        string $documentTitle,
        string $schoolYear,
        string $pdfBinary,
        string $pdfFilename,
        ?string $customMessage = null
    ) {
        $this->student            = $student;
        $this->representativeName = $representativeName;
        $this->documentTitle      = $documentTitle;
        $this->schoolYear         = $schoolYear;
        $this->pdfBinary          = $pdfBinary;
        $this->pdfFilename        = $pdfFilename;
        $this->customMessage      = $customMessage;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "{$this->documentTitle} — {$this->student->full_name} (Infochalbaud)",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.student_document',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfBinary, $this->pdfFilename)
                ->withMime('application/pdf'),
        ];
    }
}

