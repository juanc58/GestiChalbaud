<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Address;
use App\Models\AcademicBackground;
use App\Models\Enrollment;
use App\Models\Representative;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StudentRegistrationService
{
    public static function validationRules($isAdmin = false, $isHistoricalGraduate = false)
    {
        $rules = [
            'first_name'              => 'required|string|max:255',
            'second_name'             => 'nullable|string|max:255',
            'last_name'               => 'required|string|max:255',
            'second_last_name'        => 'nullable|string|max:255',
            'cedula'                  => 'nullable|integer',
            'birth_date'              => ['required', 'date', 'before:today', function ($attribute, $value, $fail) use ($isHistoricalGraduate) {
                if (!$isHistoricalGraduate) {
                    $age = Carbon::parse($value)->age;
                    if ($age >= 18) {
                        $fail('El estudiante no puede tener 18 años o más. Este sistema es para educación básica.');
                    }
                    if ($age < 3) {
                        $fail('La fecha de nacimiento no es válida para el nivel educativo.');
                    }
                }
            }],
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
        ];

        if ($isAdmin && !$isHistoricalGraduate) {
            $rules['representative_id'] = 'nullable|exists:representatives,id';
            $rules['section_id']        = 'nullable|exists:sections,id';
        } elseif ($isAdmin && $isHistoricalGraduate) {
            $rules['representative_id'] = 'nullable|exists:representatives,id';
            $rules['section_id']        = 'nullable|exists:sections,id';
        }

        return $rules;
    }

    public static function register($data, $representativeId = null, $sectionId = null, $isHistoricalGraduate = false)
    {
        return DB::transaction(function () use ($data, $representativeId, $sectionId, $isHistoricalGraduate) {
            $status   = $isHistoricalGraduate ? 'Egresado' : 'Activo';
            $isActive = !$isHistoricalGraduate;

            // 1. Create Student
            $student = Student::create([
                'representative_id'    => $representativeId,
                'cedula'               => $data['cedula'] ?? null,
                'first_name'           => $data['first_name'],
                'second_name'          => $data['second_name'] ?? null,
                'last_name'            => $data['last_name'],
                'second_last_name'     => $data['second_last_name'] ?? null,
                'birth_date'           => $data['birth_date'],
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

            // 6. Create Enrollment (only for non-historical and if sectionId provided)
            if ($sectionId && !$isHistoricalGraduate) {
                Enrollment::create([
                    'student_id'       => $student->id,
                    'representative_id'=> $representativeId,
                    'section_id'       => $sectionId,
                    'school_year'      => SchoolYear::current(),
                    'status'           => 'Inscrito',
                ]);
            }

            return $student;
        });
    }

    public static function update($student, $data)
    {
        return DB::transaction(function () use ($student, $data) {
            // 1. Update Student
            $student->update([
                'cedula'               => $data['cedula'] ?? $student->cedula,
                'first_name'           => $data['first_name'],
                'second_name'          => $data['second_name'] ?? $student->second_name,
                'last_name'            => $data['last_name'],
                'second_last_name'     => $data['second_last_name'] ?? $student->second_last_name,
                'birth_date'           => $data['birth_date'],
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
