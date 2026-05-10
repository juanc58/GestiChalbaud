<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicBackground extends Model
{
    protected $table = 'academic_background';

    protected $fillable = [
        'student_id', 'previous_school_name', 
        'previous_school_dea_code', 'previous_school_contact'
    ];

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
