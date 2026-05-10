<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Role;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'second_name',
        'last_name',
        'second_last_name',
        'phone',
        'email',
        'password',
        'cedula',
        'role_id',
        'is_active',
    ];

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

    /**
     * Short display name (first + first last).
     */
    public function getDisplayNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }

    /**
     * Get the role associated with the user.
     */
    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function teacher(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Teacher::class);
    }

    public function representative(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Representative::class);
    }

    public function isAdmin(): bool
    {
        return $this->role->name === 'admin' || $this->role->name === 'worker';
    }

    public function isDocente(): bool
    {
        return $this->role->name === 'docente';
    }

    public function isRepresentative(): bool
    {
        return $this->role->name === 'representative';
    }

    public function assignedSectionId($year): ?int
    {
        if (!$this->isDocente() || !$this->teacher) return null;
        
        return \App\Models\TeacherAssignment::where('teacher_id', $this->teacher->id)
            ->where('school_year', $year)
            ->value('section_id');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
}


