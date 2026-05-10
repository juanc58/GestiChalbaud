<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Graduate;

class GraduateController extends Controller
{
    public function index(Request $request)
    {
        $query = Graduate::with('student');

        // Filter by Search (Student Name or Cedula)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('cedula', 'like', "%{$search}%");
            });
        }

        // Filter by Promotion Year
        if ($request->filled('year')) {
            $query->where('promotion_year', $request->year);
        }

        $graduates = $query->join('students', 'graduates.student_id', '=', 'students.id')
            ->select('graduates.*')
            ->orderByDesc('promotion_year')
            ->orderBy('students.first_name')
            ->orderBy('students.last_name')
            ->paginate(30)
            ->withQueryString();
        
        // Get unique promotion years for the filter
        $years = Graduate::select('promotion_year')->distinct()->orderBy('promotion_year', 'desc')->pluck('promotion_year');

        return view('graduates.index', compact('graduates', 'years'));
    }
}
