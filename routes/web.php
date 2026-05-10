<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterRepresentativeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ProfileController;
use App\Services\CaptchaService;
use App\Http\Controllers\LocationController;

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::get('/captcha/image', function () {
    $code = CaptchaService::generate();
    return CaptchaService::render($code);
})->name('captcha.image');

Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

// Representative Self-Registration
Route::get('/register-representative', [RegisterRepresentativeController::class, 'showRegistrationForm'])->name('register.representative');
Route::post('/register-representative', [RegisterRepresentativeController::class, 'register'])->name('register.representative.post');

use App\Http\Controllers\StudentController;
use App\Http\Controllers\SectionController;

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('students', StudentController::class);
    Route::post('/students/{student}/toggle-status', [StudentController::class, 'toggleStatus'])->name('students.toggle-status');
    Route::resource('sections', SectionController::class);
    
    // Academic Promotion Routes
    Route::get('/sections/{section}/promote', [SectionController::class, 'promotionView'])->name('sections.promote.view');
    Route::post('/sections/{section}/promote', [SectionController::class, 'promote'])->name('sections.promote.store');

    // Student Assignment Routes
    Route::get('/sections/{section}/assign', [SectionController::class, 'assignView'])->name('sections.assign.view');
    Route::post('/sections/{section}/assign', [SectionController::class, 'assignStore'])->name('sections.assign.store');
    Route::delete('/enrollments/{enrollment}', [SectionController::class, 'destroyEnrollment'])->name('enrollments.destroy');
    
    // Graduates Route
    Route::get('/graduates', [\App\Http\Controllers\GraduateController::class, 'index'])->name('graduates.index');
    // School Years
    Route::resource('school-years', SchoolYearController::class);
    Route::resource('subjects', SubjectController::class);
    Route::get('/sections/{section}/grades', [SectionController::class, 'gradesView'])->name('sections.grades');
    Route::post('/sections/{section}/grades', [SectionController::class, 'gradesStore'])->name('sections.grades.store');
    Route::get('/reports/boletin/{enrollment}', [ReportController::class, 'showBoletin'])->name('reports.boletin');
    Route::resource('teachers', TeacherController::class);
    Route::post('/teachers/{teacher}/assign-section', [TeacherController::class, 'assignSection'])->name('teachers.assign-section');
    Route::post('/teachers/{teacher}/unassign-section/{assignment}', [TeacherController::class, 'unassignSection'])->name('teachers.unassign-section');
    Route::post('/school-years/{schoolYear}/activate', [SchoolYearController::class, 'activate'])->name('school-years.activate');

    // Global Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // User Management (Admin restricted inside Controller)
    Route::resource('users', \App\Http\Controllers\UserController::class)->except(['create', 'store', 'show', 'destroy']);
    Route::post('/users/{user}/toggle-status', [\App\Http\Controllers\UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Representative Directory (Admin & Teacher restricted inside Controller)
    Route::resource('representatives', \App\Http\Controllers\RepresentativeController::class)->except(['create', 'store', 'show', 'destroy']);

    // Location API – States / Municipalities / Parishes (for AJAX dropdowns)
    Route::prefix('api/locations')->name('api.locations.')->group(function () {
        Route::get('/states',                             [LocationController::class, 'states'])->name('states');
        Route::get('/states/{id}/municipalities',         [LocationController::class, 'municipalities'])->name('municipalities');
        Route::get('/municipalities/{id}/parishes',       [LocationController::class, 'parishes'])->name('parishes');
    });


    // Representative Portal
    Route::prefix('representative')->name('representative.')->group(function() {
        Route::get('/students', [\App\Http\Controllers\Representative\MyStudentsController::class, 'index'])->name('students.index');
        Route::get('/students/create', [\App\Http\Controllers\Representative\MyStudentsController::class, 'create'])->name('students.create');
        Route::post('/students', [\App\Http\Controllers\Representative\MyStudentsController::class, 'store'])->name('students.store');
        Route::get('/students/{student}/edit', [\App\Http\Controllers\Representative\MyStudentsController::class, 'edit'])->name('students.edit');
        Route::put('/students/{student}', [\App\Http\Controllers\Representative\MyStudentsController::class, 'update'])->name('students.update');
        Route::get('/students/{student}/grades', [\App\Http\Controllers\Representative\MyStudentsController::class, 'academicRecord'])->name('students.grades');
    });
});
