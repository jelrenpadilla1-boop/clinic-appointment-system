<?php
// routes/api.php
use Illuminate\Support\Facades\Route;
use App\Models\Doctor;
use App\Models\Specialization;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DoctorController;

// Public API routes (no authentication required for this specific endpoint)
Route::get('/doctors-by-specialization/{specializationId}', function($specializationId) {
    try {
        $doctors = Doctor::with(['user', 'specialization'])
            ->where('specialization_id', $specializationId)
            ->get();
            
        return response()->json($doctors);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to load doctors'], 500);
    }
    Route::get('/doctors-by-specialization/{specializationId}', [DoctorController::class, 'getDoctorsBySpecialization']);
});