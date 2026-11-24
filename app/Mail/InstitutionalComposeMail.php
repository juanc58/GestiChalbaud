<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InstitutionalComposeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $customSubject;
    public string $bodyText;
    /** @var array<int, array{binary: string, filename: string, title: string, folio: string}> */
    public array $attachedDocs;

    public function __construct(string $customSubject, string $bodyText, array $attachedDocs = [])
    {
        $this->customSubject = $customSubject;
        $this->bodyText      = $bodyText;
        $this->attachedDocs  = $attachedDocs;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->customSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.institutional_compose',
        );
    }

    public function attachments(): array
    {
        return array_map(
            fn (array $doc) => Attachment::fromData(fn () => $doc['binary'], $doc['filename'])
                ->withMime('application/pdf'),
            $this->attachedDocs
        );
    }
}
