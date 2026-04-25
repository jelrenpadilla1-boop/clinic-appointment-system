<?php
// app/Http/Controllers/Admin/AppointmentController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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
        
        // Get statistics for the dashboard
        $totalAppointments = Appointment::count();
        $pendingCount = Appointment::where('status', 'pending')->count();
        $confirmedCount = Appointment::where('status', 'confirmed')->count();
        $completedCount = Appointment::where('status', 'completed')->count();
        $cancelledCount = Appointment::where('status', 'cancelled')->count();
        
        return view('admin.appointments.index', compact(
            'appointments', 
            'doctors', 
            'statuses',
            'totalAppointments',
            'pendingCount',
            'confirmedCount',
            'completedCount',
            'cancelledCount'
        ));
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

    /**
     * Export appointments to CSV
     */
    public function export(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor.user', 'doctor.specialization']);
        
        // Apply same filters as in index
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('doctor_id') && $request->doctor_id != '') {
            $query->where('doctor_id', $request->doctor_id);
        }
        
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }
        
        $appointments = $query->orderBy('appointment_date', 'desc')
                              ->orderBy('appointment_time', 'desc')
                              ->get();
        
        // Prepare CSV headers
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="appointments_export_' . date('Y-m-d_His') . '.csv"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];
        
        $callback = function() use ($appointments) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper encoding
            fwrite($file, "\xEF\xBB\xBF");
            
            // Add headers row
            fputcsv($file, [
                'ID',
                'Patient Name',
                'Patient Email',
                'Patient Phone',
                'Doctor Name',
                'Specialization',
                'Appointment Date',
                'Appointment Time',
                'Status',
                'Notes',
                'Created At'
            ]);
            
            // Add data rows
            foreach ($appointments as $appointment) {
                fputcsv($file, [
                    $appointment->id,
                    $appointment->patient->name,
                    $appointment->patient->email,
                    $appointment->patient->phone ?? 'N/A',
                    'Dr. ' . $appointment->doctor->user->name,
                    $appointment->doctor->specialization->name,
                    $appointment->appointment_date->format('Y-m-d'),
                    $appointment->appointment_time->format('h:i A'),
                    ucfirst($appointment->status),
                    $appointment->notes ?? '',
                    $appointment->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return Response::stream($callback, 200, $headers);
    }
}