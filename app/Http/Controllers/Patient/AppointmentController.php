<?php
// app/Http/Controllers/Patient/AppointmentController.php
namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->appointmentsAsPatient()
            ->with(['doctor.user', 'doctor.specialization']);
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $appointments = $query->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate(10);
            
        return view('patient.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $specializations = Specialization::with('doctors.user')->get();
        return view('patient.appointments.create', compact('specializations'));
    }

    public function show(Appointment $appointment)
    {
        // Check if appointment belongs to authenticated patient
        if ($appointment->patient_id !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }
        
        $appointment->load(['doctor.user', 'doctor.specialization', 'medicalRecord']);
        
        return view('patient.appointments.show', compact('appointment'));
    }

    public function getAvailableSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after:today',
        ]);

        $doctor = Doctor::with('schedules')->find($request->doctor_id);
        $date = Carbon::parse($request->date);
        $dayOfWeek = $date->dayOfWeek;

        // Get doctor's schedule for this day
        $schedule = $doctor->schedules->where('day_of_week', $dayOfWeek)->first();

        if (!$schedule) {
            return response()->json(['slots' => []]);
        }

        // Get booked appointments for this date
        $bookedAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->pluck('appointment_time')
            ->map(function ($time) {
                return Carbon::parse($time)->format('H:i');
            })
            ->toArray();

        // Generate available time slots
        $start = Carbon::parse($schedule->start_time);
        $end = Carbon::parse($schedule->end_time);
        $interval = 30; // 30 minutes interval
        $slots = [];

        while ($start < $end) {
            $timeSlot = $start->format('H:i');
            if (!in_array($timeSlot, $bookedAppointments)) {
                $slots[] = $timeSlot;
            }
            $start->addMinutes($interval);
        }

        // Check if max patients reached
        $bookedCount = count($bookedAppointments);
        if ($bookedCount >= $schedule->max_patients) {
            $slots = [];
        }

        return response()->json(['slots' => $slots]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
            'notes' => 'nullable|string',
        ]);

        // Check if slot is still available
        $existingAppointment = Appointment::where('doctor_id', $request->doctor_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->whereTime('appointment_time', $request->appointment_time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if ($existingAppointment) {
            return back()->with('error', 'This time slot is no longer available.');
        }

        // Check doctor's schedule and max patients
        $doctor = Doctor::find($request->doctor_id);
        $date = Carbon::parse($request->appointment_date);
        $schedule = $doctor->schedules->where('day_of_week', $date->dayOfWeek)->first();

        if (!$schedule) {
            return back()->with('error', 'Doctor is not available on this day.');
        }

        $appointmentsCount = Appointment::where('doctor_id', $request->doctor_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        if ($appointmentsCount >= $schedule->max_patients) {
            return back()->with('error', 'Doctor has reached maximum patients for this day.');
        }

        // Create appointment
        Appointment::create([
            'patient_id' => auth()->id(),
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'notes' => $request->notes,
            'status' => 'pending',
        ]);

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Appointment booked successfully. Waiting for admin confirmation.');
    }

    public function rescheduleForm(Appointment $appointment)
    {
        // Check if appointment belongs to authenticated patient
        if ($appointment->patient_id !== auth()->id()) {
            abort(403);
        }

        // Only allow rescheduling of pending or confirmed appointments
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return redirect()->route('patient.appointments.index')
                ->with('error', 'This appointment cannot be rescheduled.');
        }

        $specializations = Specialization::with('doctors.user')->get();
        
        return view('patient.appointments.reschedule', compact('appointment', 'specializations'));
    }

    public function cancel(Appointment $appointment)
    {
        // Check if appointment belongs to authenticated patient
        if ($appointment->patient_id !== auth()->id()) {
            abort(403);
        }

        // Only allow cancellation of pending or confirmed appointments
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This appointment cannot be cancelled.');
        }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Appointment cancelled successfully.');
    }

    public function reschedule(Request $request, Appointment $appointment)
    {
        // Check if appointment belongs to authenticated patient
        if ($appointment->patient_id !== auth()->id()) {
            abort(403);
        }

        // Only allow rescheduling of pending or confirmed appointments
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This appointment cannot be rescheduled.');
        }

        $request->validate([
            'appointment_date' => 'required|date|after:today',
            'appointment_time' => 'required',
        ]);

        // Check availability
        $existingAppointment = Appointment::where('doctor_id', $appointment->doctor_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->whereTime('appointment_time', $request->appointment_time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('id', '!=', $appointment->id)
            ->first();

        if ($existingAppointment) {
            return back()->with('error', 'This time slot is no longer available.');
        }

        $appointment->update([
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'status' => 'pending', // Reset to pending for admin approval
        ]);

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Appointment rescheduled successfully. Waiting for admin confirmation.');
    }
}