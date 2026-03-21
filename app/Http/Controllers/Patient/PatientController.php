<?php
// app/Http/Controllers/Patient/PatientController.php
namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function dashboard()
    {
        $patient = Auth::user();
        
        // Get upcoming appointments (pending and confirmed)
        $upcomingAppointments = Appointment::where('patient_id', $patient->id)
            ->where('appointment_date', '>=', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->with(['doctor.user', 'doctor.specialization'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get();
            
        // Get past appointments (completed and cancelled)
        $pastAppointments = Appointment::where('patient_id', $patient->id)
            ->where(function($query) {
                $query->where('appointment_date', '<', today())
                    ->orWhereIn('status', ['completed', 'cancelled']);
            })
            ->with(['doctor.user', 'doctor.specialization'])
            ->orderBy('appointment_date', 'desc')
            ->take(5)
            ->get();
            
        $totalAppointments = Appointment::where('patient_id', $patient->id)->count();
        $completedAppointments = Appointment::where('patient_id', $patient->id)
            ->where('status', 'completed')
            ->count();
        $cancelledAppointments = Appointment::where('patient_id', $patient->id)
            ->where('status', 'cancelled')
            ->count();
            
        return view('patient.dashboard', compact(
            'upcomingAppointments',
            'pastAppointments',
            'totalAppointments',
            'completedAppointments',
            'cancelledAppointments'
        ));
    }

    public function medicalHistory()
    {
        $patient = Auth::user();
        
        $medicalRecords = MedicalRecord::whereHas('appointment', function($query) use ($patient) {
                $query->where('patient_id', $patient->id)
                    ->where('status', 'completed');
            })
            ->with(['appointment.doctor.user', 'appointment.doctor.specialization'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('patient.medical-history', compact('medicalRecords'));
    }
}