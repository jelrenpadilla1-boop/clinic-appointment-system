<?php
// app/Http/Controllers/Doctor/AppointmentController.php
namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $doctor = Auth::user()->doctor;
        
        $query = Appointment::with('patient')
            ->where('doctor_id', $doctor->id);
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by date
        if ($request->has('date') && $request->date != '') {
            $query->whereDate('appointment_date', $request->date);
        }
        
        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15);
            
        return view('doctor.appointments.index', compact('appointments'));
    }

    public function show(Appointment $appointment)
    {
        $doctor = Auth::user()->doctor;
        
        // Check if appointment belongs to this doctor
        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }
        
        $appointment->load(['patient', 'medicalRecord']);
        
        return view('doctor.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $doctor = Auth::user()->doctor;
        
        // Check if appointment belongs to this doctor
        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }
        
        $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled',
        ]);
        
        // Doctors can only update to confirmed, completed, or cancelled
        // (cannot change to pending)
        
        $appointment->update(['status' => $request->status]);
        
        return back()->with('success', 'Appointment status updated successfully.');
    }

    public function addNotes(Request $request, Appointment $appointment)
    {
        $doctor = Auth::user()->doctor;
        
        // Check if appointment belongs to this doctor
        if ($appointment->doctor_id !== $doctor->id) {
            abort(403);
        }
        
        $request->validate([
            'diagnosis' => 'required|string',
            'prescription' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);
        
        // Update or create medical record
        MedicalRecord::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'diagnosis' => $request->diagnosis,
                'prescription' => $request->prescription,
                'remarks' => $request->remarks,
            ]
        );
        
        // Mark appointment as completed if not already
        if ($appointment->status !== 'completed') {
            $appointment->update(['status' => 'completed']);
        }
        
        return back()->with('success', 'Medical notes added successfully.');
    }
}