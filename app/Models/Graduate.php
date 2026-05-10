<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Graduate extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'promotion_year',
        'notes'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
