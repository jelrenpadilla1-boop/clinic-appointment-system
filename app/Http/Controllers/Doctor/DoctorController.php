<?php
// app/Http/Controllers/Doctor/DoctorController.php
namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\User; // Add this import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function dashboard()
    {
        $doctor = Auth::user()->doctor;
        
        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->whereIn('status', ['confirmed', 'completed'])
            ->count();
            
        $upcomingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', '>=', today())
            ->whereIn('status', ['confirmed'])
            ->count();
            
        $totalPatients = Appointment::where('doctor_id', $doctor->id)
            ->distinct('patient_id')
            ->count('patient_id');
            
        $completedAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->count();
            
        $recentAppointments = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->take(5)
            ->get();
            
        return view('doctor.dashboard', compact(
            'todayAppointments',
            'upcomingAppointments',
            'totalPatients',
            'completedAppointments',
            'recentAppointments'
        ));
    }

    public function schedule()
    {
        $doctor = Auth::user()->doctor;
        $schedules = Schedule::where('doctor_id', $doctor->id)
            ->orderBy('day_of_week')
            ->get();
            
        return view('doctor.schedule', compact('schedules'));
    }

    public function updateSchedule(Request $request)
    {
        $doctor = Auth::user()->doctor;
        
        $request->validate([
            'schedules' => 'required|array',
            'schedules.*.day_of_week' => 'required|integer|between:0,6',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.max_patients' => 'required|integer|min:1|max:50',
        ]);
        
        // Delete existing schedules
        Schedule::where('doctor_id', $doctor->id)->delete();
        
        // Create new schedules
        foreach ($request->schedules as $scheduleData) {
            Schedule::create([
                'doctor_id' => $doctor->id,
                'day_of_week' => $scheduleData['day_of_week'],
                'start_time' => $scheduleData['start_time'],
                'end_time' => $scheduleData['end_time'],
                'max_patients' => $scheduleData['max_patients'],
            ]);
        }
        
        return redirect()->route('doctor.schedule')
            ->with('success', 'Schedule updated successfully.');
    }

    public function patients()
    {
        $doctor = Auth::user()->doctor;
        
        $patients = User::where('role', 'patient')
            ->whereHas('appointmentsAsPatient', function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            })
            ->withCount(['appointmentsAsPatient' => function($query) use ($doctor) {
                $query->where('doctor_id', $doctor->id);
            }])
            ->paginate(15);
            
        return view('doctor.patients.index', compact('patients'));
    }

    public function patientDetails(User $patient)
    {
        $doctor = Auth::user()->doctor;
        
        // Check if doctor has treated this patient
        $hasTreated = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $patient->id)
            ->exists();
            
        if (!$hasTreated) {
            abort(403, 'You have not treated this patient.');
        }
        
        $appointments = Appointment::with('medicalRecord')
            ->where('doctor_id', $doctor->id)
            ->where('patient_id', $patient->id)
            ->orderBy('appointment_date', 'desc')
            ->paginate(10);
            
        return view('doctor.patients.show', compact('patient', 'appointments'));
    }

    public function patientMedicalHistory(User $patient)
    {
        $doctor = Auth::user()->doctor;
        
        $appointments = Appointment::with('medicalRecord')
            ->where('doctor_id', $doctor->id)
            ->where('patient_id', $patient->id)
            ->orderBy('appointment_date', 'desc')
            ->get();
            
        return view('doctor.medical-history', compact('patient', 'appointments'));
    }
}