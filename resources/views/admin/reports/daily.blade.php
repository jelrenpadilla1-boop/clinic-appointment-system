{{-- resources/views/admin/reports/daily.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Daily Report - {{ $date->format('F d, Y') }}</h2>
        <div>
            <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i>Back to Reports
            </a>
            <a href="{{ route('admin.reports.daily', ['date' => $date->format('Y-m-d'), 'export' => 'pdf']) }}" 
               class="btn btn-success">
                <i class="fas fa-file-pdf me-2"></i>Export PDF
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Appointments</h5>
                    <h2 class="mb-0">{{ $summary['total'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Confirmed</h5>
                    <h2 class="mb-0">{{ $summary['confirmed'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h5 class="card-title">Completed</h5>
                    <h2 class="mb-0">{{ $summary['completed'] }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Pending</h5>
                    <h2 class="mb-0">{{ $summary['pending'] }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointments List -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Appointments for {{ $date->format('F d, Y') }}</h5>
        </div>
        <div class="card-body">
            @if($appointments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Time</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Specialization</th>
                                <th>Status</th>
                                <th>Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appointments as $appointment)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                <td>{{ $appointment->patient->name }}</td>
                                <td>Dr. {{ $appointment->doctor->user->name }}</td>
                                <td>{{ $appointment->doctor->specialization->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : 
                                        ($appointment->status == 'pending' ? 'warning' : 
                                        ($appointment->status == 'completed' ? 'info' : 'danger')) }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td>{{ Str::limit($appointment->notes, 30) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">No appointments found for this date.</p>
            @endif
        </div>
    </div>
</div>
@endsection