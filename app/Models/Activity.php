<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['name', 'category'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'activity_student');
    }
}
