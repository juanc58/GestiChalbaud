<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Graduate;
use App\Models\SchoolYear;
use App\Models\Teacher;
use App\Models\Section;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $currentYear = SchoolYear::current();

        $stats = [
            'total_students'  => Student::where('status', 'Activo')->count(),
            'total_graduates' => Graduate::count(),
            'total_teachers'  => Teacher::count(),
            'total_sections'  => Section::count(),
        ];

        $pendingStudentsCount = 0;
        $pendingUsersCount = 0;

        if (auth()->user()->isAdmin()) {
            $pendingStudentsCount = Student::where('status', 'Activo')
                ->whereDoesntHave('enrollments', function ($q) use ($currentYear) {
                    $q->where('school_year', $currentYear);
                })->count();

            $pendingUsersCount = User::whereNull('role_id')->count();
        }

        return view('dashboard', compact('stats', 'pendingStudentsCount', 'pendingUsersCount', 'currentYear'));
    }
}
