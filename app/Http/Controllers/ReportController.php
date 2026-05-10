<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function showBoletin(Enrollment $enrollment)
    {
        $enrollment->load(['student', 'section.grade', 'representative']);
        
        // Get all subjects for this grade
        $subjects = $enrollment->section->grade->subjects()->where('is_active', true)->orderBy('name')->get();
        
        // Get assessments for this enrollment
        $assessments = $enrollment->assessments()->with('subject')->get()->groupBy('subject_id');

        return view('reports.boletin', compact('enrollment', 'subjects', 'assessments'));
    }
}
