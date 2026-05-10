<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Allergy extends Model
{
    protected $fillable = ['name', 'category'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'allergy_student');
    }
}
