<?php
// routes/web.php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\SpecializationController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Doctor\DoctorController as DoctorDashboardController;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Patient\PatientController as PatientDashboardController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Authentication routes are automatically included from auth.php
require __DIR__.'/auth.php';

// Protected routes (require authentication)
Route::middleware(['auth'])->group(function () {

    Route::get('/get-doctors-by-specialization/{specializationId}', function($specializationId) {
        $doctors = App\Models\Doctor::with(['user', 'specialization'])
            ->where('specialization_id', $specializationId)
            ->get();
        return response()->json($doctors);
    })->name('get.doctors.by.specialization');
    
    // Dashboard redirect based on role
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'doctor':
                return redirect()->route('doctor.dashboard');
            case 'patient':
                return redirect()->route('patient.dashboard');
            default:
                return redirect()->route('login');
        }
    })->name('dashboard');
    
    // Profile Routes (inside auth middleware)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::patch('/profile/password', [ProfileController::class, 'password'])->name('profile.password');
    Route::patch('/profile/notifications', [ProfileController::class, 'notifications'])->name('profile.notifications');
    
    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Doctor Management
        Route::resource('doctors', DoctorController::class);
        
        // Patient Management
        Route::resource('patients', PatientController::class);
        Route::patch('/patients/{patient}/toggle-status', [PatientController::class, 'toggleStatus'])->name('patients.toggle-status');
        
        // Specialization Management
        Route::resource('specializations', SpecializationController::class)->except(['show']);
        
        // Schedule Management
        Route::resource('schedules', ScheduleController::class);
        
        // Appointment Management
        Route::resource('appointments', AdminAppointmentController::class);
        Route::post('/appointments/{appointment}/approve', [AdminAppointmentController::class, 'approve'])->name('appointments.approve');
        Route::post('/appointments/{appointment}/reject', [AdminAppointmentController::class, 'reject'])->name('appointments.reject');
        Route::patch('/appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.update-status');
        
        // Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/daily', [ReportController::class, 'daily'])->name('reports.daily');
        Route::get('/reports/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
        Route::get('/reports/export-pdf', [ReportController::class, 'exportPDF'])->name('reports.export-pdf');
    });

    // Doctor Routes
    Route::middleware(['role:doctor'])->prefix('doctor')->name('doctor.')->group(function () {
        Route::get('/dashboard', [DoctorDashboardController::class, 'dashboard'])->name('dashboard');
        
        // Appointments
        Route::resource('appointments', DoctorAppointmentController::class);
        Route::post('/appointments/{appointment}/update-status', [DoctorAppointmentController::class, 'updateStatus'])->name('appointments.update-status');
        Route::post('/appointments/{appointment}/add-notes', [DoctorAppointmentController::class, 'addNotes'])->name('appointments.add-notes');
        
        // Schedule Management
        Route::get('/schedule', [DoctorDashboardController::class, 'schedule'])->name('schedule');
        Route::post('/schedule/update', [DoctorDashboardController::class, 'updateSchedule'])->name('schedule.update');
        
        // Patient Management
        Route::get('/patients', [DoctorDashboardController::class, 'patients'])->name('patients.index');
        Route::get('/patients/{patient}', [DoctorDashboardController::class, 'patientDetails'])->name('patients.show');
        
        // Medical Records
        Route::get('/medical-records/{patient}', [DoctorDashboardController::class, 'patientMedicalHistory'])->name('medical-records');
    });

    // Patient Routes
    Route::middleware(['role:patient'])->prefix('patient')->name('patient.')->group(function () {
        Route::get('/dashboard', [PatientDashboardController::class, 'dashboard'])->name('dashboard');
        
        // Appointments
        Route::resource('appointments', PatientAppointmentController::class);
        Route::get('/book-appointment', [PatientAppointmentController::class, 'create'])->name('book-appointment');
        Route::get('/appointments/{appointment}/reschedule', [PatientAppointmentController::class, 'rescheduleForm'])->name('appointments.reschedule-form');
        Route::post('/appointments/{appointment}/cancel', [PatientAppointmentController::class, 'cancel'])->name('appointments.cancel');
        Route::post('/appointments/{appointment}/reschedule', [PatientAppointmentController::class, 'reschedule'])->name('appointments.reschedule');
        
        // Get available slots for a doctor
        Route::get('/get-available-slots', [PatientAppointmentController::class, 'getAvailableSlots'])->name('get-available-slots');
        
        // Medical History
        Route::get('/medical-history', [PatientDashboardController::class, 'medicalHistory'])->name('medical-history');
    });
});