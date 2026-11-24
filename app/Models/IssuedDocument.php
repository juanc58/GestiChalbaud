<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssuedDocument extends Model
{
    protected $fillable = [
        'folio_code',
        'student_id',
        'enrollment_id',
        'document_type',
        'school_year',
        'delivery_channel',
        'recipient_email',
        'metadata',
        'issued_by',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function issuer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    /**
     * Registra en base de datos cada emisión o envío de documento oficial para trazabilidad.
     */
    public static function recordEmission(
        Student $student,
        string $documentType,
        string $schoolYear,
        string $deliveryChannel = 'pdf',
        ?int $enrollmentId = null,
        ?string $recipientEmail = null,
        array $metadata = []
    ): self {
        $yearPrefix = now()->format('Y');
        $nextSeq = (int) (self::max('id') ?? 0) + 1;
        $folio = sprintf('INF-%s-%06d', $yearPrefix, $nextSeq);

        return self::create([
            'folio_code'       => $folio,
            'student_id'       => $student->id,
            'enrollment_id'    => $enrollmentId,
            'document_type'    => $documentType,
            'school_year'      => $schoolYear,
            'delivery_channel' => $deliveryChannel,
            'recipient_email'  => $recipientEmail,
            'metadata'         => $metadata,
            'issued_by'        => auth()->id(),
        ]);
    }
}
