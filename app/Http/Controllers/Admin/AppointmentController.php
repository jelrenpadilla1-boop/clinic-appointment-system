<?php
// app/Http/Controllers/Admin/AppointmentController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor.user', 'doctor.specialization']);
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by doctor
        if ($request->has('doctor_id') && $request->doctor_id != '') {
            $query->where('doctor_id', $request->doctor_id);
        }
        
        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }
        
        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(15);
            
        $doctors = Doctor::with('user')->get();
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];
        
        return view('admin.appointments.index', compact('appointments', 'doctors', 'statuses'));
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor.user', 'doctor.specialization', 'medicalRecord']);
        return view('admin.appointments.show', compact('appointment'));
    }

    public function approve(Appointment $appointment)
    {
        if ($appointment->status !== 'pending') {
            return back()->with('error', 'Only pending appointments can be approved.');
        }
        
        $appointment->update(['status' => 'confirmed']);
        
        // TODO: Send email notification to patient
        
        return back()->with('success', 'Appointment confirmed successfully.');
    }

    public function reject(Request $request, Appointment $appointment)
    {
        if ($appointment->status !== 'pending') {
            return back()->with('error', 'Only pending appointments can be rejected.');
        }
        
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);
        
        $appointment->update([
            'status' => 'cancelled',
            'notes' => ($appointment->notes ? $appointment->notes . "\n" : '') . 
                      "Rejection reason: " . $request->rejection_reason
        ]);
        
        // TODO: Send email notification to patient
        
        return back()->with('success', 'Appointment rejected successfully.');
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);
        
        $appointment->update(['status' => $request->status]);
        
        return back()->with('success', 'Appointment status updated successfully.');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('admin.appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }
}