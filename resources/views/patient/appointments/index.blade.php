{{-- resources/views/patient/appointments/index.blade.php --}}
@extends('layouts.patient')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">My Appointments</h2>
        <a href="{{ route('patient.book-appointment') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Book New Appointment
        </a>
    </div>

    <!-- Status Tabs -->
    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link {{ !request('status') ? 'active' : '' }}" 
               href="{{ route('patient.appointments.index') }}">
                All
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" 
               href="{{ route('patient.appointments.index', ['status' => 'pending']) }}">
                Pending
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'confirmed' ? 'active' : '' }}" 
               href="{{ route('patient.appointments.index', ['status' => 'confirmed']) }}">
                Confirmed
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'completed' ? 'active' : '' }}" 
               href="{{ route('patient.appointments.index', ['status' => 'completed']) }}">
                Completed
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'cancelled' ? 'active' : '' }}" 
               href="{{ route('patient.appointments.index', ['status' => 'cancelled']) }}">
                Cancelled
            </a>
        </li>
    </ul>

    <!-- Appointments List -->
    <div class="card">
        <div class="card-body">
            @if($appointments->count() > 0)
                <div class="table-responsive">
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
                            @foreach($appointments as $appointment)
                            <tr>
                                <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                <td>Dr. {{ $appointment->doctor->user->name }}</td>
                                <td>{{ $appointment->doctor->specialization->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : 
                                        ($appointment->status == 'pending' ? 'warning' : 
                                        ($appointment->status == 'completed' ? 'info' : 'danger')) }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('patient.appointments.show', $appointment) }}" 
                                       class="btn btn-sm btn-info" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if(in_array($appointment->status, ['pending', 'confirmed']))
                                        <a href="{{ route('patient.appointments.reschedule-form', $appointment) }}" 
                                           class="btn btn-sm btn-warning" title="Reschedule">
                                            <i class="fas fa-calendar-alt"></i>
                                        </a>
                                        
                                        <form action="{{ route('patient.appointments.cancel', $appointment) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Are you sure you want to cancel this appointment?')"
                                                    title="Cancel">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-3">
                    {{ $appointments->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <h5>No Appointments Found</h5>
                    <p class="text-muted">You haven't booked any appointments yet.</p>
                    <a href="{{ route('patient.book-appointment') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Book Your First Appointment
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection