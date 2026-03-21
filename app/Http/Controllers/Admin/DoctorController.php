<?php
// app/Http/Controllers/Admin/DoctorController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Specialization;
use App\Models\Appointment;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with(['user', 'specialization'])->get();
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        $specializations = Specialization::all();
        return view('admin.doctors.create', compact('specializations'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone' => 'required|string',
            'address' => 'required|string',
            'specialization_id' => 'required|exists:specializations,id',
            'license_number' => 'required|unique:doctors',
            'gender' => 'nullable|string',
            'dob' => 'nullable|date',
            'qualification' => 'nullable|string',
            'experience' => 'nullable|integer',
            'bio' => 'nullable|string',
            'fee' => 'nullable|numeric',
            'max_patients' => 'nullable|integer',
            'services' => 'nullable|array',
            'services.*' => 'string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'doctor',
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'dob' => $request->dob,
        ]);

        $doctor = Doctor::create([
            'user_id' => $user->id,
            'specialization_id' => $request->specialization_id,
            'license_number' => $request->license_number,
            'qualification' => $request->qualification,
            'experience' => $request->experience,
            'bio' => $request->bio,
            'fee' => $request->fee,
            'max_patients' => $request->max_patients ?? 20,
            'services' => $request->has('services') ? $request->services : null,
        ]);

        // Send notification if checked
        if ($request->has('send_notification')) {
            // TODO: Send welcome email
        }

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor created successfully.');
    }

    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'specialization', 'schedules']);
        
        // Get appointment statistics
        $totalAppointments = Appointment::where('doctor_id', $doctor->id)->count();
        $todayAppointments = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->count();
        $completedAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'completed')
            ->count();
        $pendingAppointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->count();
        
        // Get recent appointments
        $recentAppointments = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->take(5)
            ->get();
        
        // Get working schedule
        $schedules = Schedule::where('doctor_id', $doctor->id)
            ->orderBy('day_of_week')
            ->get();
        
        return view('admin.doctors.show', compact(
            'doctor', 
            'totalAppointments', 
            'todayAppointments', 
            'completedAppointments', 
            'pendingAppointments',
            'recentAppointments',
            'schedules'
        ));
    }

    public function edit(Doctor $doctor)
    {
        $specializations = Specialization::all();
        $doctor->load('user');
        return view('admin.doctors.edit', compact('doctor', 'specializations'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $doctor->user_id,
            'phone' => 'required|string',
            'address' => 'required|string',
            'specialization_id' => 'required|exists:specializations,id',
            'license_number' => 'required|unique:doctors,license_number,' . $doctor->id,
            'gender' => 'nullable|string',
            'dob' => 'nullable|date',
            'qualification' => 'nullable|string',
            'experience' => 'nullable|integer',
            'bio' => 'nullable|string',
            'fee' => 'nullable|numeric',
            'max_patients' => 'nullable|integer',
            'services' => 'nullable|array',
            'services.*' => 'string',
        ]);

        // Update user information
        $doctor->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'gender' => $request->gender,
            'dob' => $request->dob,
        ]);

        // Update doctor information
        $doctor->update([
            'specialization_id' => $request->specialization_id,
            'license_number' => $request->license_number,
            'qualification' => $request->qualification,
            'experience' => $request->experience,
            'bio' => $request->bio,
            'fee' => $request->fee,
            'max_patients' => $request->max_patients,
            'services' => $request->has('services') ? $request->services : null,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $doctor->user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctorName = $doctor->user->name;
        $doctor->user->delete();
        return redirect()->route('admin.doctors.index')
            ->with('success', "Doctor {$doctorName} deleted successfully.");
    }
}