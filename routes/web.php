<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterUserController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SchoolYearController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RepresentativeController;
use App\Http\Controllers\GraduateController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AcademicRecordController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SmtpSettingController;
use App\Http\Controllers\MailComposerController;
use App\Services\CaptchaService;

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::get('/captcha/image', function () {
    $code = CaptchaService::generate();
    return CaptchaService::render($code);
})->name('captcha.image');

Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');

// Guest Routes: Password Recovery & Institutional User Registration Request
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterUserController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterUserController::class, 'register'])->name('register.post');

    Route::post('/password/recovery/question', [PasswordResetController::class, 'getQuestion'])->name('password.recovery.question')->middleware('throttle:5,1');
    Route::post('/password/recovery/verify', [PasswordResetController::class, 'verifyAnswer'])->name('password.recovery.verify')->middleware('throttle:5,1');
    Route::post('/password/recovery/reset', [PasswordResetController::class, 'resetPassword'])->name('password.recovery.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('students', StudentController::class);
    Route::post('/students/{student}/toggle-status', [StudentController::class, 'toggleStatus'])->name('students.toggle-status');
    Route::get('/api/representatives/by-cedula/{cedula}', [StudentController::class, 'findRepresentativeByCedula'])->name('api.representatives.by-cedula');

    Route::resource('sections', SectionController::class);

    // Academic Promotion Routes
    Route::get('/sections/{section}/promote', [SectionController::class, 'promotionView'])->name('sections.promote.view');
    Route::post('/sections/{section}/promote', [SectionController::class, 'promote'])->name('sections.promote.store');

    // Student Assignment Routes
    Route::get('/sections/{section}/assign', [SectionController::class, 'assignView'])->name('sections.assign.view');
    Route::post('/sections/{section}/assign', [SectionController::class, 'assignStore'])->name('sections.assign.store');
    Route::delete('/enrollments/{enrollment}', [SectionController::class, 'destroyEnrollment'])->name('enrollments.destroy');

    // Graduates Route
    Route::get('/graduates', [GraduateController::class, 'index'])->name('graduates.index');

    // School Years & Subjects
    Route::resource('school-years', SchoolYearController::class);
    Route::post('/school-years/{schoolYear}/activate', [SchoolYearController::class, 'activate'])->name('school-years.activate');
    Route::resource('subjects', SubjectController::class);

    // Grades & Boletin
    Route::get('/sections/{section}/grades', [SectionController::class, 'gradesView'])->name('sections.grades');
    Route::post('/sections/{section}/grades', [SectionController::class, 'gradesStore'])->name('sections.grades.store');
    Route::get('/reports/boletin/{enrollment}', [ReportController::class, 'showBoletin'])->name('reports.boletin');
    Route::get('/reports/boletin/{enrollment}/pdf', [ReportController::class, 'downloadBoletinPdf'])->name('reports.boletin.pdf');
    Route::post('/reports/boletin/{enrollment}/email', [ReportController::class, 'emailBoletin'])->name('reports.boletin.email');

    // Control de Asistencia (Estudiantes y Docentes)
    Route::get('/attendance/students', [AttendanceController::class, 'students'])->name('attendance.students');
    Route::post('/attendance/students', [AttendanceController::class, 'storeStudents'])->name('attendance.students.store');
    Route::get('/attendance/teachers', [AttendanceController::class, 'teachers'])->name('attendance.teachers');
    Route::post('/attendance/teachers', [AttendanceController::class, 'storeTeachers'])->name('attendance.teachers.store');

    // Récord Académico (Estudiantes Activos, Egresados y Sábana de Notas)
    Route::get('/academic-records', [AcademicRecordController::class, 'index'])->name('academic-records.index');
    Route::get('/academic-records/{student}', [AcademicRecordController::class, 'show'])->name('academic-records.show');
    Route::get('/academic-records/{student}/pdf', [AcademicRecordController::class, 'downloadPdf'])->name('academic-records.pdf');
    Route::post('/academic-records/{student}/email', [AcademicRecordController::class, 'sendEmail'])->name('academic-records.email');

    // Constancias de Estudio, Prosecución y Justificativos Institucionales
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{student}/{type}', [DocumentController::class, 'preview'])->name('documents.preview');
    Route::get('/documents/{student}/{type}/pdf', [DocumentController::class, 'downloadPdf'])->name('documents.pdf');
    Route::post('/documents/{student}/{type}/email', [DocumentController::class, 'sendEmail'])->name('documents.email');

    // Correo Institucional Limpio (Envío de Correo y Adjuntos Directos)
    Route::get('/mail/compose', [MailComposerController::class, 'index'])->name('mail.compose');
    Route::post('/mail/send', [MailComposerController::class, 'send'])->name('mail.send');
    Route::get('/api/mail/search-students', [MailComposerController::class, 'searchStudents'])->name('api.mail.search-students');
    Route::get('/api/mail/students/{student}/documents', [MailComposerController::class, 'studentDocuments'])->name('api.mail.student-documents');

    // Configuración Institucional y Soporte SMTP (Admin Principal)
    Route::get('/settings/smtp', [SmtpSettingController::class, 'edit'])->name('settings.smtp.edit');
    Route::put('/settings/smtp', [SmtpSettingController::class, 'update'])->name('settings.smtp.update');
    Route::post('/settings/smtp/test', [SmtpSettingController::class, 'test'])->name('settings.smtp.test');

    // Teachers
    Route::resource('teachers', TeacherController::class);
    Route::post('/teachers/{teacher}/assign-section', [TeacherController::class, 'assignSection'])->name('teachers.assign-section');
    Route::post('/teachers/{teacher}/unassign-section/{assignment}', [TeacherController::class, 'unassignSection'])->name('teachers.unassign-section');

    // Global Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // User Management & Approval Workflow (Admin restricted inside Controller)
    Route::resource('users', UserController::class)->except(['show']);
    Route::post('/users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Representative Contact Directory (Admin & Teacher restricted inside Controller)
    Route::resource('representatives', RepresentativeController::class)->except(['create', 'store', 'show', 'destroy']);

    // Location API – States / Municipalities / Parishes (for AJAX dropdowns)
    Route::prefix('api/locations')->name('api.locations.')->group(function () {
        Route::get('/states',                       [LocationController::class, 'states'])->name('states');
        Route::get('/states/{id}/municipalities',   [LocationController::class, 'municipalities'])->name('municipalities');
        Route::get('/municipalities/{id}/parishes', [LocationController::class, 'parishes'])->name('parishes');
    });
});
