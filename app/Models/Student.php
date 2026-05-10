<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\SchoolYear;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'representative_id', 'cedula', 
        'first_name', 'second_name', 'last_name', 'second_last_name',
        'photo_path', 'birth_date', 
        'birth_place_state', 'birth_place_locality', 'gender', 
        'shirt_size', 'pants_size', 'shoes_size', 'weight', 'height', 
        'health_conditions', 'other_allergies',
        'cognitive_diversity', 'lives_with', 
        'siblings_count', 'interests', 'other_activities',
        'is_active', 'status'
    ];

    public function getFullNameAttribute(): string
    {
        return collect([
            $this->first_name, $this->second_name,
            $this->last_name,  $this->second_last_name,
        ])->filter()->implode(' ');
    }

    public function address(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function representative(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Representative::class);
    }

    public function academicBackground(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AcademicBackground::class);
    }

    public function enrollments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function allergies(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Allergy::class, 'allergy_student');
    }

    public function activities(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Activity::class, 'activity_student');
    }

    public function currentEnrollment()
    {
        return $this->enrollments()->where('school_year', SchoolYear::current())->first();
    }
}
