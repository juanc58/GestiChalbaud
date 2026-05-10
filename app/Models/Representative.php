<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Representative extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'photo_path',
        'phone_whatsapp',
        'phone_local',
        'facebook',
        'job_title',
        'workplace_address',
        'relationship',
    ];

    // --- Accessors that delegate to the linked user ---
    public function getFirstNameAttribute(): ?string      { return $this->user?->first_name;       }
    public function getSecondNameAttribute(): ?string     { return $this->user?->second_name;      }
    public function getLastNameAttribute(): ?string       { return $this->user?->last_name;        }
    public function getSecondLastNameAttribute(): ?string { return $this->user?->second_last_name; }
    public function getEmailAttribute(): ?string          { return $this->user?->email;            }
    public function getCedulaAttribute(): ?int            { return $this->user?->cedula;           }
    public function getFullNameAttribute(): string        { return $this->user?->full_name ?? '';  }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Enrollment::class);
    }
}

