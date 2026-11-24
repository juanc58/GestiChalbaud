<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Representative;
use App\Models\SchoolYear;
use App\Rules\LettersOnlyName;
use App\Rules\SpanishDate;
use App\Rules\StrictEmail;
use App\Rules\VenezuelanPhone;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class StudentRegistrationService
{
    public static function validationRules($isAdmin = false, $isHistoricalGraduate = false, ?int $ignoreStudentId = null)
    {
        $repReq = $isHistoricalGraduate ? 'nullable' : 'required';
        $uniqueCedula = 'unique:students,cedula' . ($ignoreStudentId ? ',' . $ignoreStudentId : '');

        $rules = [
            'photo'                   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'remove_photo'            => 'nullable|boolean',
            'first_name'              => ['required', 'string', 'max:255', new LettersOnlyName()],
            'second_name'             => ['nullable', 'string', 'max:255', new LettersOnlyName()],
            'last_name'               => ['required', 'string', 'max:255', new LettersOnlyName()],
            'second_last_name'        => ['nullable', 'string', 'max:255', new LettersOnlyName()],
            'cedula'                  => ['nullable', 'digits_between:6,12', $uniqueCedula],
            'birth_date'              => [
                'required',
                $isHistoricalGraduate
                    ? new SpanishDate(mustBeBeforeToday: true)
                    : new SpanishDate(minAge: 3, maxAge: 18, mustBeBeforeToday: true),
            ],
            'birth_place_state'       => 'required|string|max:255',
            'birth_place_locality'    => 'required|string|max:255',
            'gender'                  => 'required|in:M,F',
            'municipality'            => 'required|string|max:255',
            'parish'                  => 'required|string|max:255',
            'sector'                  => 'required|string|max:255',
            'house_apt_number'        => 'required|string|max:255',
            'weight'                  => 'nullable|numeric|min:1|max:200',
            'height'                  => 'nullable|numeric|min:0.3|max:2.5',
            'shirt_size'              => 'nullable|string|max:50',
            'pants_size'              => 'nullable|string|max:50',
            'shoes_size'              => 'nullable|string|max:50',
            'health_conditions'       => 'nullable|string',
            'other_allergies'         => 'nullable|string|max:500',
            'allergies'               => 'nullable|array',
            'allergies.*'             => 'exists:allergies,id',
            'activities'              => 'nullable|array',
            'activities.*'            => 'exists:activities,id',
            'other_activities'        => 'nullable|string|max:500',
            'previous_school_name'    => 'nullable|string|max:255',
            'previous_school_dea_code'=> 'nullable|string|max:255',
            'lives_with'              => 'nullable|string|max:255',
            'siblings_count'          => 'nullable|integer|min:0',
            'interests'               => 'nullable|string',
            'estado_id'               => 'nullable|integer',
            'municipio_id'            => 'nullable|integer',
            'parroquia_id'            => 'nullable|integer',

            // Datos del Representante integrados en la ficha del estudiante
            'rep_cedula'              => [$repReq, 'digits_between:6,10'],
            'rep_relationship'        => [$repReq, 'string', 'max:100'],
            'rep_first_name'          => [$repReq, 'string', 'max:255', new LettersOnlyName()],
            'rep_second_name'         => ['nullable', 'string', 'max:255', new LettersOnlyName()],
            'rep_last_name'           => [$repReq, 'string', 'max:255', new LettersOnlyName()],
            'rep_second_last_name'    => ['nullable', 'string', 'max:255', new LettersOnlyName()],
            'rep_phone_whatsapp'      => [$repReq, 'string', 'max:50', new VenezuelanPhone()],
            'rep_phone_local'         => ['nullable', 'string', 'max:50', new VenezuelanPhone()],
            'rep_email'               => [$repReq, 'string', 'max:255', new StrictEmail()],
            'rep_job_title'           => 'nullable|string|max:255',
            'rep_workplace_address'   => 'nullable|string',
            'rep_facebook'            => 'nullable|string|max:255',
            'promotion_year'          => [$isHistoricalGraduate ? 'required' : 'nullable', 'string', 'max:20'],
            'graduate_notes'          => 'nullable|string|max:500',
        ];

        if ($isAdmin) {
            $rules['section_id'] = 'nullable|exists:sections,id';
        }

        return $rules;
    }

    /**
     * Creates or updates the Representative contact group from student form data.
     */
    public static function saveRepresentativeData(array $data, ?Student $student = null): ?int
    {
        if (empty($data['rep_first_name']) && empty($data['rep_last_name'])) {
            return $student?->representative_id;
        }

        $repCedula = !empty($data['rep_cedula']) ? (int) preg_replace('/\D/', '', (string) $data['rep_cedula']) : null;

        $payload = [
            'cedula'            => $repCedula,
            'first_name'        => $data['rep_first_name'],
            'second_name'       => $data['rep_second_name'] ?? null,
            'last_name'         => $data['rep_last_name'],
            'second_last_name'  => $data['rep_second_last_name'] ?? null,
            'email'             => !empty($data['rep_email']) ? mb_strtolower(trim((string) $data['rep_email']), 'UTF-8') : ('representante_' . ($repCedula ?? uniqid()) . '@infochalbaud.edu.ve'),
            'phone_whatsapp'    => VenezuelanPhone::normalize($data['rep_phone_whatsapp'] ?? ''),
            'phone_local'       => !empty($data['rep_phone_local']) ? VenezuelanPhone::normalize($data['rep_phone_local']) : null,
            'facebook'          => $data['rep_facebook'] ?? null,
            'job_title'         => $data['rep_job_title'] ?? null,
            'workplace_address' => $data['rep_workplace_address'] ?? null,
            'relationship'      => $data['rep_relationship'] ?? 'Representante Legal',
        ];

        $existingRep = $student?->representative;

        if ($repCedula) {
            $matchedByCedula = Representative::where('cedula', $repCedula)->first();
            if ($matchedByCedula) {
                $matchedByCedula->update($payload);
                return $matchedByCedula->id;
            }
        }

        if ($existingRep && (!$student || $existingRep->students()->where('id', '!=', $student->id)->doesntExist())) {
            $existingRep->update($payload);
            return $existingRep->id;
        }

        $created = Representative::create($payload);
        return $created->id;
    }

    public static function register($data, $representativeId = null, $sectionId = null, $isHistoricalGraduate = false, ?UploadedFile $photoFile = null)
    {
        return DB::transaction(function () use ($data, $representativeId, $sectionId, $isHistoricalGraduate, $photoFile) {
            $status   = $isHistoricalGraduate ? 'Egresado' : 'Activo';
            $isActive = !$isHistoricalGraduate;

            $resolvedRepId = $representativeId ?? self::saveRepresentativeData($data);
            $photoPath = $photoFile ? $photoFile->store('students', 'public') : null;

            // 1. Create Student
            $student = Student::create([
                'representative_id'    => $resolvedRepId,
                'photo_path'           => $photoPath,
                'cedula'               => $data['cedula'] ?? null,
                'first_name'           => $data['first_name'],
                'second_name'          => $data['second_name'] ?? null,
                'last_name'            => $data['last_name'],
                'second_last_name'     => $data['second_last_name'] ?? null,
                'birth_date'           => SpanishDate::toDatabase($data['birth_date']),
                'birth_place_state'    => $data['birth_place_state'],
                'birth_place_locality' => $data['birth_place_locality'],
                'gender'               => $data['gender'],
                'shirt_size'           => $data['shirt_size'] ?? null,
                'pants_size'           => $data['pants_size'] ?? null,
                'shoes_size'           => $data['shoes_size'] ?? null,
                'weight'               => $data['weight'] ?? null,
                'height'               => $data['height'] ?? null,
                'health_conditions'    => $data['health_conditions'] ?? null,
                'other_allergies'      => $data['other_allergies'] ?? null,
                'lives_with'           => $data['lives_with'] ?? null,
                'siblings_count'       => $data['siblings_count'] ?? 0,
                'interests'            => $data['interests'] ?? null,
                'other_activities'     => $data['other_activities'] ?? null,
                'status'               => $status,
                'is_active'            => $isActive,
            ]);

            // 2. Sync Allergies
            if (!empty($data['allergies'])) {
                $student->allergies()->sync($data['allergies']);
            }

            // 3. Sync Activities
            if (!empty($data['activities'])) {
                $student->activities()->sync($data['activities']);
            }

            // 4. Create Address
            $student->address()->create([
                'municipality'    => $data['municipality'],
                'parish'          => $data['parish'],
                'sector'          => $data['sector'],
                'house_apt_number'=> $data['house_apt_number'],
                'state'           => $data['estado_name'] ?? null,
                'estado_id'       => $data['estado_id'] ?? null,
                'municipio_id'    => $data['municipio_id'] ?? null,
                'parroquia_id'    => $data['parroquia_id'] ?? null,
            ]);

            // 5. Create Academic Background
            if (!empty($data['previous_school_name'])) {
                $student->academicBackground()->create([
                    'previous_school_name'     => $data['previous_school_name'],
                    'previous_school_dea_code' => $data['previous_school_dea_code'] ?? null,
                ]);
            }

            // 6. Create Enrollment (only for non-historical and if sectionId provided) OR Graduate record
            if ($isHistoricalGraduate) {
                \App\Models\Graduate::updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'promotion_year' => !empty($data['promotion_year']) ? trim($data['promotion_year']) : SchoolYear::current(),
                        'notes'          => !empty($data['graduate_notes']) ? trim($data['graduate_notes']) : 'Egresado histórico registrado en archivo institucional.',
                    ]
                );
            } elseif ($sectionId) {
                Enrollment::create([
                    'student_id'       => $student->id,
                    'representative_id'=> $resolvedRepId,
                    'section_id'       => $sectionId,
                    'school_year'      => SchoolYear::current(),
                    'status'           => 'Inscrito',
                ]);
            }

            return $student;
        });
    }

    public static function update($student, $data, ?UploadedFile $photoFile = null)
    {
        return DB::transaction(function () use ($student, $data, $photoFile) {
            $resolvedRepId = self::saveRepresentativeData($data, $student);

            $photoPath = $student->photo_path;
            if (!empty($data['remove_photo']) && $photoPath) {
                Storage::disk('public')->delete($photoPath);
                $photoPath = null;
            }
            if ($photoFile) {
                if ($photoPath) {
                    Storage::disk('public')->delete($photoPath);
                }
                $photoPath = $photoFile->store('students', 'public');
            }

            // 1. Update Student
            $student->update([
                'representative_id'    => $resolvedRepId,
                'photo_path'           => $photoPath,
                'cedula'               => $data['cedula'] ?? $student->cedula,
                'first_name'           => $data['first_name'],
                'second_name'          => $data['second_name'] ?? null,
                'last_name'            => $data['last_name'],
                'second_last_name'     => $data['second_last_name'] ?? null,
                'birth_date'           => SpanishDate::toDatabase($data['birth_date']),
                'birth_place_state'    => $data['birth_place_state'],
                'birth_place_locality' => $data['birth_place_locality'],
                'gender'               => $data['gender'],
                'shirt_size'           => $data['shirt_size'] ?? $student->shirt_size,
                'pants_size'           => $data['pants_size'] ?? $student->pants_size,
                'shoes_size'           => $data['shoes_size'] ?? $student->shoes_size,
                'weight'               => $data['weight'] ?? $student->weight,
                'height'               => $data['height'] ?? $student->height,
                'health_conditions'    => $data['health_conditions'] ?? $student->health_conditions,
                'other_allergies'      => $data['other_allergies'] ?? $student->other_allergies,
                'lives_with'           => $data['lives_with'] ?? $student->lives_with,
                'siblings_count'       => $data['siblings_count'] ?? $student->siblings_count,
                'interests'            => $data['interests'] ?? $student->interests,
                'other_activities'     => $data['other_activities'] ?? $student->other_activities,
            ]);

            if ($resolvedRepId) {
                $student->currentEnrollment()?->update([
                    'representative_id' => $resolvedRepId,
                ]);
            }

            // 2. Sync Allergies
            $student->allergies()->sync($data['allergies'] ?? []);

            // 3. Sync Activities
            $student->activities()->sync($data['activities'] ?? []);

            // 4. Update Address
            $student->address()->updateOrCreate([], [
                'municipality'    => $data['municipality'],
                'parish'          => $data['parish'],
                'sector'          => $data['sector'],
                'house_apt_number'=> $data['house_apt_number'],
                'state'           => $data['estado_name'] ?? $student->address->state ?? null,
                'estado_id'       => $data['estado_id'] ?? $student->address->estado_id ?? null,
                'municipio_id'    => $data['municipio_id'] ?? $student->address->municipio_id ?? null,
                'parroquia_id'    => $data['parroquia_id'] ?? $student->address->parroquia_id ?? null,
            ]);

            // 5. Update Academic Background
            if (!empty($data['previous_school_name'])) {
                $student->academicBackground()->updateOrCreate([], [
                    'previous_school_name'     => $data['previous_school_name'],
                    'previous_school_dea_code' => $data['previous_school_dea_code'] ?? null,
                ]);
            }

            return $student;
        });
    }
}
