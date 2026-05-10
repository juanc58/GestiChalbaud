<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\Section;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $currentYear = SchoolYear::current();
        
        $stats = [
            'total_students' => Student::count(),
            'total_teachers' => Teacher::count(),
            'total_sections' => Section::count(),
        ];

        $pendingStudentsCount = 0;

        if (auth()->user()->isAdmin()) {
            $pendingStudentsCount = Student::whereNotNull('representative_id')
                ->whereDoesntHave('enrollments', function($q) use ($currentYear) {
                    $q->where('school_year', $currentYear);
                })->count();
        }

        return view('dashboard', compact('stats', 'pendingStudentsCount', 'currentYear'));
    }
}
