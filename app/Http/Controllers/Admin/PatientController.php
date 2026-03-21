<?php
// app/Http/Controllers/Admin/PatientController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        $patients = User::where('role', 'patient')->paginate(10);
        return view('admin.patients.index', compact('patients'));
    }

    public function show(User $patient)
    {
        if ($patient->role !== 'patient') {
            abort(404);
        }
        
        $appointments = Appointment::where('patient_id', $patient->id)
            ->with(['doctor.user', 'doctor.specialization'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.patients.show', compact('patient', 'appointments'));
    }

    public function toggleStatus(User $patient)
    {
        if ($patient->role !== 'patient') {
            abort(404);
        }
        
        $patient->update([
            'is_active' => !$patient->is_active
        ]);
        
        $status = $patient->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Patient account {$status} successfully.");
    }

    public function destroy(User $patient)
    {
        if ($patient->role !== 'patient') {
            abort(404);
        }
        
        $patient->delete();
        return redirect()->route('admin.patients.index')
            ->with('success', 'Patient deleted successfully.');
    }
}