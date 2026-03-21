<?php
// app/Http/Controllers/Api/DoctorController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function getDoctorsBySpecialization($specializationId)
    {
        try {
            $doctors = Doctor::with(['user', 'specialization'])
                ->where('specialization_id', $specializationId)
                ->get();
                
            return response()->json([
                'success' => true,
                'data' => $doctors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load doctors',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}