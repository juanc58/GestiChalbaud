<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\IssuedDocument;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudentAttendance;
use App\Models\SystemSetting;
use App\Rules\SpanishDate;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

/**
 * Servicio centralizado para construir, validar elegibilidad, generar PDFs
 * y registrar en base de datos la emisión de reportes y constancias.
 */
class DocumentGeneratorService
{
    public const VALID_TYPES = ['estudio', 'inscripcion', 'prosecucion', 'justificativo'];

    /**
     * Valida si un estudiante cumple las condiciones reales de BD para emitir un tipo de constancia.
     */
    public static function checkDocumentEligibility(Student $student, string $type): ?string
    {
        $student->loadMissing(['graduate', 'enrollments.assessments']);
        $currentYear = SchoolYear::current();
        $currentEnrollment = $student->enrollments->firstWhere('school_year', $currentYear);

        if (in_array($type, ['estudio', 'inscripcion'], true)) {
            if ($student->status !== 'Activo' || !$currentEnrollment || !in_array($currentEnrollment->status, ['Inscrito', 'Repitiente'], true)) {
                return 'Solo se puede emitir Constancia de Estudio / Inscripción a estudiantes activos con matrícula vigente en el período escolar actual.';
            }
        }

        if ($type === 'prosecucion') {
            $hasPromotedEnrollment = $student->enrollments->contains(fn ($e) => $e->status === 'Promovido');
            if ($student->status !== 'Egresado' && !$hasPromotedEnrollment) {
                return 'La Constancia de Prosecución requiere que el estudiante tenga una matrícula en estado Promovido o figure como Egresado.';
            }
        }

        return null;
    }

    /**
     * Verifica si el estudiante posee historial académico o egreso válido para Récord Académico.
     */
    public static function hasAcademicRecordData(Student $student): bool
    {
        $student->loadMissing(['graduate', 'enrollments.assessments']);
        if ($student->graduate !== null) {
            return true;
        }
        return $student->enrollments->isNotEmpty();
    }

    public static function buildBoletinData(Enrollment $enrollment, bool $isPdf = false): array
    {
        $enrollment->load([
            'student.representative',
            'section.grade.level',
            'representative',
            'assessments.subject',
        ]);

        $gradeSubjects = $enrollment->section->grade->subjects()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Unión con materias que tengan evaluaciones reales en esta matrícula (integridad histórica)
        $assessmentSubjects = $enrollment->assessments->pluck('subject')->filter()->unique('id');
        $subjects = $gradeSubjects->merge($assessmentSubjects)->unique('id')->sortBy('name')->values();

        $assessments = $enrollment->assessments->groupBy('subject_id');

        $sectionTeacher = $enrollment->section
            ->currentTeacherAssignment($enrollment->school_year)
            ->with('teacher.user')
            ->first()?->teacher;

        $representative = $enrollment->representative ?? $enrollment->student->representative;

        $attendanceSummary = [
            'Presente'    => StudentAttendance::where('student_id', $enrollment->student_id)->where('school_year', $enrollment->school_year)->where('status', 'Presente')->count(),
            'Ausente'     => StudentAttendance::where('student_id', $enrollment->student_id)->where('school_year', $enrollment->school_year)->where('status', 'Ausente')->count(),
            'Justificado' => StudentAttendance::where('student_id', $enrollment->student_id)->where('school_year', $enrollment->school_year)->where('status', 'Justificado')->count(),
            'Tardanza'    => StudentAttendance::where('student_id', $enrollment->student_id)->where('school_year', $enrollment->school_year)->where('status', 'Tardanza')->count(),
        ];

        $directorName   = SystemSetting::getValue('director_name', 'DIRECCIÓN DEL PLANTEL');
        $directorCedula = SystemSetting::getValue('director_cedula', '');

        return compact(
            'enrollment',
            'subjects',
            'assessments',
            'sectionTeacher',
            'representative',
            'attendanceSummary',
            'directorName',
            'directorCedula',
            'isPdf'
        );
    }

    public static function buildAcademicRecordData(Student $student, bool $isPdf = false): array
    {
        $student->load([
            'representative',
            'graduate',
            'academicBackground',
            'enrollments' => function ($q) {
                $q->with(['section.grade.level', 'section.grade.subjects', 'assessments.subject', 'representative'])
                  ->orderBy('school_year');
            },
        ]);

        $representative = $student->representative ?? $student->enrollments->last()?->representative;
        $directorName   = SystemSetting::getValue('director_name', 'DIRECCIÓN DEL PLANTEL');
        $directorCedula = SystemSetting::getValue('director_cedula', '');
        $currentYear    = SchoolYear::current();

        return compact(
            'student',
            'representative',
            'directorName',
            'directorCedula',
            'currentYear',
            'isPdf'
        );
    }

    public static function buildInstitutionalDocumentData(Student $student, string $type, array $params = [], bool $isPdf = false): array
    {
        $student->load([
            'representative',
            'graduate',
            'enrollments' => fn ($q) => $q->with(['section.grade.level', 'assessments', 'representative'])->orderByDesc('school_year'),
        ]);

        $currentYear = SchoolYear::current();
        if ($type === 'prosecucion') {
            $enrollment = $student->enrollments->firstWhere('status', 'Promovido')
                ?? $student->enrollments->firstWhere('school_year', $currentYear)
                ?? $student->enrollments->first();
        } else {
            $enrollment = $student->enrollments->firstWhere('school_year', $currentYear)
                ?? $student->enrollments->first();
        }

        $representative = $student->representative ?? $enrollment?->representative;

        $gradeName   = $enrollment?->section?->grade?->name ?? ($student->status === 'Egresado' ? '6to Grado' : 'Educación Primaria');
        $levelName   = $enrollment?->section?->grade?->level?->name ?? 'Educación Primaria Bolivariana';
        $sectionName = $enrollment?->section?->name ?? ($student->graduate ? 'Promoción Histórica' : 'Única');
        $shift       = $enrollment?->section?->shift ?? 'Mañana';
        $schoolYear  = $enrollment?->school_year ?? ($student->graduate?->promotion_year ?? $currentYear);

        // Calcular literal real a partir del 3er momento o notas aprobatorias (excluyendo 'E')
        $m3Scores = $enrollment?->assessments?->where('term', 3)->pluck('score')->filter(fn ($s) => in_array($s, ['A', 'B', 'C', 'D'], true))->values() ?? collect();
        $allPassingScores = $enrollment?->assessments?->pluck('score')->filter(fn ($s) => in_array($s, ['A', 'B', 'C', 'D'], true))->values() ?? collect();
        $sourceScores = $m3Scores->isNotEmpty() ? $m3Scores : $allPassingScores;
        $defaultLiteral = $sourceScores->isNotEmpty()
            ? $sourceScores->countBy()->sortDesc()->keys()->first()
            : 'A';

        $gradeNumber = $enrollment?->section?->grade?->number ?? 6;
        $levelId     = $enrollment?->section?->grade?->educational_level_id ?? 2;
        if ($student->status === 'Egresado' || ($levelId == 2 && $gradeNumber >= 6)) {
            $defaultNextGrade = '1er AÑO DE EDUCACIÓN MEDIA GENERAL';
        } elseif ($levelId == 1) {
            $defaultNextGrade = $gradeNumber >= 3 ? '1er GRADO DE EDUCACIÓN PRIMARIA' : (($gradeNumber + 1) . 'er NIVEL DE EDUCACIÓN INICIAL');
        } else {
            $ordinals = [1 => '1er', 2 => '2do', 3 => '3er', 4 => '4to', 5 => '5to', 6 => '6to'];
            $nextNum = $gradeNumber + 1;
            $defaultNextGrade = ($ordinals[$nextNum] ?? ($nextNum . 'to')) . ' GRADO DE EDUCACIÓN PRIMARIA';
        }

        $literal   = mb_strtoupper(trim((string) ($params['literal'] ?? $defaultLiteral)), 'UTF-8');
        $nextGrade = mb_strtoupper(trim((string) ($params['next_grade'] ?? $defaultNextGrade)), 'UTF-8');
        $target    = $params['target'] ?? 'ambos';
        $rawDate   = $params['event_date'] ?? now()->format('d-m-Y');
        $parsedDt  = SpanishDate::parse((string) $rawDate) ?? now();
        $eventDate = $parsedDt->format('d-m-Y');
        $eventDateFormatted = $parsedDt->format('d/m/Y');

        $timeFrom  = $params['time_from'] ?? '07:30';
        $timeTo    = $params['time_to'] ?? '11:30';
        $reason    = trim((string) ($params['reason'] ?? 'atender citación académica y retiro de informe pedagógico estudiantil'));
        $purpose   = trim((string) ($params['purpose'] ?? 'fines legales y administrativos que la parte interesada estime convenientes'));

        $titles = [
            'estudio'       => 'CONSTANCIA DE ESTUDIO',
            'inscripcion'   => 'CONSTANCIA DE INSCRIPCIÓN',
            'prosecucion'   => 'CONSTANCIA DE PROSECUCIÓN DE ESTUDIOS',
            'justificativo' => 'CONSTANCIA DE COMPARECENCIA Y JUSTIFICATIVO INSTITUCIONAL',
        ];
        $documentTitle = $titles[$type] ?? 'CONSTANCIA INSTITUCIONAL';

        $directorName   = SystemSetting::getValue('director_name', 'DIRECCIÓN DEL PLANTEL');
        $directorCedula = SystemSetting::getValue('director_cedula', '');

        $monthsEs = [
            1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril',
            5 => 'mayo', 6 => 'junio', 7 => 'julio', 8 => 'agosto',
            9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre',
        ];
        $now = now();
        $issueDateLong = "a los {$now->format('d')} días del mes de " . $monthsEs[(int) $now->format('n')] . " del año {$now->format('Y')}";

        return compact(
            'student',
            'type',
            'documentTitle',
            'enrollment',
            'representative',
            'gradeName',
            'levelName',
            'sectionName',
            'shift',
            'schoolYear',
            'literal',
            'nextGrade',
            'target',
            'eventDate',
            'eventDateFormatted',
            'timeFrom',
            'timeTo',
            'reason',
            'purpose',
            'directorName',
            'directorCedula',
            'issueDateLong',
            'isPdf'
        );
    }

    /**
     * Genera binario PDF + nombre de archivo para adjuntar o descargar, y registra auditoría.
     *
     * @return array{binary: string, filename: string, title: string, folio: string, schoolYear: string}
     */
    public static function generatePdfAttachment(
        Student $student,
        string $docType,
        string $channel = 'email',
        ?string $recipientEmail = null,
        ?Enrollment $specificEnrollment = null,
        array $params = []
    ): array {
        $slug = Str::slug($student->full_name, '_');

        if ($docType === 'boletin') {
            $enrollment = $specificEnrollment ?? $student->enrollments()->orderByDesc('school_year')->first();
            if (!$enrollment) {
                throw new \RuntimeException('El estudiante no posee una inscripción registrada para generar el Boletín.');
            }
            $data = self::buildBoletinData($enrollment, true);
            $issued = IssuedDocument::recordEmission($student, 'boletin', $enrollment->school_year, $channel, $enrollment->id, $recipientEmail);
            $data['folioCode'] = $issued->folio_code;
            $binary = Pdf::loadView('reports.boletin', $data)->setPaper('letter', 'portrait')->output();

            return [
                'binary'     => $binary,
                'filename'   => "boletin_{$slug}_{$enrollment->school_year}.pdf",
                'title'      => "Boletín Informativo ({$enrollment->school_year})",
                'folio'      => $issued->folio_code,
                'schoolYear' => $enrollment->school_year,
            ];
        }

        if ($docType === 'record_academico') {
            $data = self::buildAcademicRecordData($student, true);
            $issued = IssuedDocument::recordEmission($student, 'record_academico', $data['currentYear'], $channel, null, $recipientEmail);
            $data['folioCode'] = $issued->folio_code;
            $binary = Pdf::loadView('academic-records.show', $data)->setPaper('letter', 'portrait')->output();

            return [
                'binary'     => $binary,
                'filename'   => "record_academico_{$slug}.pdf",
                'title'      => 'Récord Académico Histórico',
                'folio'      => $issued->folio_code,
                'schoolYear' => $data['currentYear'],
            ];
        }

        if (!in_array($docType, self::VALID_TYPES, true)) {
            throw new \InvalidArgumentException('Tipo de documento no válido.');
        }

        $error = self::checkDocumentEligibility($student, $docType);
        if ($error !== null) {
            throw new \RuntimeException($error);
        }

        $data = self::buildInstitutionalDocumentData($student, $docType, $params, true);
        $issued = IssuedDocument::recordEmission(
            $student,
            $docType,
            $data['schoolYear'],
            $channel,
            $data['enrollment']?->id,
            $recipientEmail,
            ['literal' => $data['literal'], 'next_grade' => $data['nextGrade'], 'target' => $data['target']]
        );
        $data['folioCode'] = $issued->folio_code;
        $binary = Pdf::loadView('documents.print', $data)->setPaper('letter', 'portrait')->output();

        return [
            'binary'     => $binary,
            'filename'   => "constancia_{$docType}_{$slug}.pdf",
            'title'      => $data['documentTitle'],
            'folio'      => $issued->folio_code,
            'schoolYear' => $data['schoolYear'],
        ];
    }
}
