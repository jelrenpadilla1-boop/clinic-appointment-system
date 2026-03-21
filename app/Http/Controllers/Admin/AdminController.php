<?php
// app/Http/Controllers/Admin/AdminController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalDoctors = Doctor::count();
        $totalPatients = User::where('role', 'patient')->count();
        $totalAppointments = Appointment::count();
        $todayAppointments = Appointment::today()->count();

        return view('admin.dashboard', compact(
            'totalDoctors', 'totalPatients', 'totalAppointments', 'todayAppointments'
        ));
    }
}