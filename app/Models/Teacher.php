<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'photo_path',
        'gender',
        'birth_date',
        'entry_date',
        'academic_degree',
        'status',
    ];

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo_path ? asset('storage/' . $this->photo_path) : null;
    }

    // --- Accessors that delegate to the linked user ---
    public function getFirstNameAttribute(): ?string { return $this->user?->first_name; }
    public function getLastNameAttribute(): ?string  { return $this->user?->last_name;  }
    public function getEmailAttribute(): ?string     { return $this->user?->email;      }
    public function getPhoneAttribute(): ?string     { return $this->user?->phone;      }
    public function getCedulaAttribute(): ?int       { return $this->user?->cedula;     }
    public function getFullNameAttribute(): string   { return $this->user?->full_name ?? ''; }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignments()
    {
        return $this->hasMany(TeacherAssignment::class);
    }

    public function currentAssignment($year)
    {
        return $this->hasOne(TeacherAssignment::class)->where('school_year', $year);
    }

    public function attendances()
    {
        return $this->hasMany(TeacherAttendance::class);
    }
}

