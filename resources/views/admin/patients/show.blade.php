{{-- resources/views/admin/patients/show.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Patient Details: {{ $patient->name }}</h2>
        <a href="{{ route('admin.patients.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Patients
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Personal Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th>Name:</th>
                            <td>{{ $patient->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $patient->email }}</td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>{{ $patient->phone }}</td>
                        </tr>
                        <tr>
                            <th>Address:</th>
                            <td>{{ $patient->address }}</td>
                        </tr>
                        <tr>
                            <th>Registered:</th>
                            <td>{{ $patient->created_at->format('M d, Y h:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($patient->is_active ?? true)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5>Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h3>{{ $appointments->total() }}</h3>
                            <small>Total Appointments</small>
                        </div>
                        <div class="col-6">
                            <h3>{{ $appointments->where('status', 'completed')->count() }}</h3>
                            <small>Completed</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Appointment History</h5>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Doctor</th>
                                <th>Specialization</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                <td>{{ $appointment->appointment_time->format('h:i A') }}</td>
                                <td>Dr. {{ $appointment->doctor->user->name }}</td>
                                <td>{{ $appointment->doctor->specialization->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : ($appointment->status == 'pending' ? 'warning' : ($appointment->status == 'completed' ? 'info' : 'danger')) }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.appointments.show', $appointment) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No appointments found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-3">
                        {{ $appointments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection