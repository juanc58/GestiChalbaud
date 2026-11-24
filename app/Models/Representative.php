<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Representative extends Model
{
    use HasFactory;

    protected $fillable = [
        'cedula',
        'first_name',
        'second_name',
        'last_name',
        'second_last_name',
        'email',
        'photo_path',
        'phone_whatsapp',
        'phone_local',
        'facebook',
        'job_title',
        'workplace_address',
        'relationship',
    ];

    public function setFirstNameAttribute($value): void
    {
        $this->attributes['first_name'] = $value !== null ? mb_strtoupper(trim((string) $value), 'UTF-8') : null;
    }

    public function setSecondNameAttribute($value): void
    {
        $this->attributes['second_name'] = ($value !== null && trim((string) $value) !== '') ? mb_strtoupper(trim((string) $value), 'UTF-8') : null;
    }

    public function setLastNameAttribute($value): void
    {
        $this->attributes['last_name'] = $value !== null ? mb_strtoupper(trim((string) $value), 'UTF-8') : null;
    }

    public function setSecondLastNameAttribute($value): void
    {
        $this->attributes['second_last_name'] = ($value !== null && trim((string) $value) !== '') ? mb_strtoupper(trim((string) $value), 'UTF-8') : null;
    }

    /**
     * Full name accessor — concatenates all name parts, skipping empty ones.
     */
    public function getFullNameAttribute(): string
    {
        return collect([
            $this->first_name,
            $this->second_name,
            $this->last_name,
            $this->second_last_name,
        ])->filter()->implode(' ');
    }

    public function students(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function enrollments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
}
