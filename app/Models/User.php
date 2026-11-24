<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'requested_role',
        'is_active',
    ];

    protected static function booted(): void
    {
        static::saved(function (User $user) {
            if ($user->wasChanged('role_id')) {
                $user->unsetRelation('role');
            }
            $user->syncTeacherProfile();
        });
    }

    /**
     * Automatically ensures a Teacher profile exists whenever this user holds the `docente` role.
     */
    public function syncTeacherProfile(): ?Teacher
    {
        if (!$this->role_id) {
            return null;
        }

        $roleName = Role::whereKey($this->role_id)->value('name');
        if ($roleName !== 'docente') {
            return null;
        }

        return Teacher::firstOrCreate(
            ['user_id' => $this->id],
            [
                'birth_date'      => '1990-01-01',
                'entry_date'      => now()->toDateString(),
                'academic_degree' => 'Docente Institucional',
                'status'          => ($this->is_active ?? true) ? 'Activo' : 'Inactivo',
            ]
        );
    }

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

    /**
     * Short display name (first + first last).
     */
    public function getDisplayNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }

    /**
     * Localized Spanish role label.
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role?->name) {
            'admin'   => 'Administrador',
            'docente' => 'Docente',
            'worker'  => 'Personal Administrativo',
            default   => $this->isPending() ? 'Pendiente de Aprobación' : 'Usuario',
        };
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

    public function securityAnswer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(UserSecurityAnswer::class);
    }

    public function isAdmin(): bool
    {
        $roleName = $this->role?->name;
        return $roleName === 'admin' || $roleName === 'worker';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role?->name === 'admin';
    }

    public function isDocente(): bool
    {
        return $this->role?->name === 'docente';
    }

    public function isWorker(): bool
    {
        return $this->role?->name === 'worker';
    }

    public function isPending(): bool
    {
        return !$this->is_active && is_null($this->role_id);
    }

    public function assignedSectionId($year): ?int
    {
        if (!$this->isDocente() || !$this->teacher) {
            return null;
        }

        return TeacherAssignment::where('teacher_id', $this->teacher->id)
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
