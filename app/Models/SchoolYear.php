<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    protected $fillable = ['year', 'is_active'];

    public static function current()
    {
        $active = self::where('is_active', true)->first();
        if ($active) {
            return $active->year;
        }

        // Fallback to max enrollment year or system clock
        $maxEnrollment = Enrollment::max('school_year');
        if ($maxEnrollment) {
            return $maxEnrollment;
        }

        return date('Y') . '-' . (date('Y') + 1);
    }
}
