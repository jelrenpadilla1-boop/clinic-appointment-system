<?php
// app/Http/Controllers/Admin/ScheduleController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with(['user', 'schedules'])->get();
        return view('admin.schedules.index', compact('doctors'));
    }

    public function create(Request $request)
    {
        $doctors = Doctor::with('user')->get();
        $doctor = null;
        
        if ($request->has('doctor_id')) {
            $doctor = Doctor::with('user')->find($request->doctor_id);
        }
        
        return view('admin.schedules.create', compact('doctors', 'doctor'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_patients' => 'required|integer|min:1|max:50',
        ]);

        // Check if schedule already exists for this doctor and day
        $exists = Schedule::where('doctor_id', $request->doctor_id)
            ->where('day_of_week', $request->day_of_week)
            ->exists();

        if ($exists) {
            return back()->withInput()
                ->with('error', 'Schedule already exists for this doctor on the selected day.');
        }

        Schedule::create($request->all());

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedule created successfully.');
    }

    public function edit(Schedule $schedule)
    {
        $doctors = Doctor::with('user')->get();
        return view('admin.schedules.edit', compact('schedule', 'doctors'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'max_patients' => 'required|integer|min:1|max:50',
        ]);

        // Check if schedule already exists for this doctor and day (excluding current)
        $exists = Schedule::where('doctor_id', $request->doctor_id)
            ->where('day_of_week', $request->day_of_week)
            ->where('id', '!=', $schedule->id)
            ->exists();

        if ($exists) {
            return back()->withInput()
                ->with('error', 'Schedule already exists for this doctor on the selected day.');
        }

        $schedule->update($request->all());

        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedule updated successfully.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.schedules.index')
            ->with('success', 'Schedule deleted successfully.');
    }
}