<?php
// app/Http/Controllers/Admin/ReportController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function daily(Request $request)
    {
        $date = $request->get('date') ? Carbon::parse($request->date) : Carbon::today();
        
        $appointments = Appointment::with(['patient', 'doctor.user'])
            ->whereDate('appointment_date', $date)
            ->orderBy('appointment_time')
            ->get();
            
        $summary = [
            'total' => $appointments->count(),
            'confirmed' => $appointments->where('status', 'confirmed')->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
            'pending' => $appointments->where('status', 'pending')->count(),
        ];
        
        if ($request->has('export')) {
            $pdf = Pdf::loadView('admin.reports.daily-pdf', compact('appointments', 'summary', 'date'));
            return $pdf->download('daily-report-' . $date->format('Y-m-d') . '.pdf');
        }
        
        return view('admin.reports.daily', compact('appointments', 'summary', 'date'));
    }

    public function monthly(Request $request)
    {
        $month = $request->get('month') ? Carbon::parse($request->month) : Carbon::now();
        
        $appointments = Appointment::with(['patient', 'doctor.user'])
            ->whereYear('appointment_date', $month->year)
            ->whereMonth('appointment_date', $month->month)
            ->orderBy('appointment_date')
            ->get();
            
        $summary = [
            'total' => $appointments->count(),
            'confirmed' => $appointments->where('status', 'confirmed')->count(),
            'completed' => $appointments->where('status', 'completed')->count(),
            'cancelled' => $appointments->where('status', 'cancelled')->count(),
            'pending' => $appointments->where('status', 'pending')->count(),
        ];
        
        // Daily breakdown
        $dailyBreakdown = $appointments->groupBy(function($appointment) {
            return $appointment->appointment_date->format('Y-m-d');
        })->map(function($dayAppointments) {
            return [
                'total' => $dayAppointments->count(),
                'confirmed' => $dayAppointments->where('status', 'confirmed')->count(),
                'completed' => $dayAppointments->where('status', 'completed')->count(),
            ];
        });
        
        // Doctor performance
        $doctorPerformance = $appointments->groupBy('doctor.user.name')
            ->map(function($doctorAppointments) {
                return [
                    'total' => $doctorAppointments->count(),
                    'completed' => $doctorAppointments->where('status', 'completed')->count(),
                ];
            });
        
        if ($request->has('export')) {
            $pdf = Pdf::loadView('admin.reports.monthly-pdf', compact(
                'appointments', 'summary', 'month', 'dailyBreakdown', 'doctorPerformance'
            ));
            return $pdf->download('monthly-report-' . $month->format('Y-m') . '.pdf');
        }
        
        return view('admin.reports.monthly', compact(
            'appointments', 'summary', 'month', 'dailyBreakdown', 'doctorPerformance'
        ));
    }
}