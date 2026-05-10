<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationalLevel extends Model
{
    protected $fillable = ['name'];

    public function grades(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
