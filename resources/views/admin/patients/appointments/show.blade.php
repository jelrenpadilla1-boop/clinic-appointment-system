{{-- resources/views/patient/appointments/show.blade.php --}}
@extends('layouts.patient')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Appointment Details</h2>
        <a href="{{ route('patient.appointments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Appointments
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Appointment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Doctor:</label>
                            <p class="mb-0">Dr. {{ $appointment->doctor->user->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Specialization:</label>
                            <p class="mb-0">{{ $appointment->doctor->specialization->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Date:</label>
                            <p class="mb-0">{{ $appointment->appointment_date->format('l, F d, Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Time:</label>
                            <p class="mb-0">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Status:</label>
                            <p class="mb-0">
                                <span class="badge bg-{{ $appointment->status == 'confirmed' ? 'success' : 
                                    ($appointment->status == 'pending' ? 'warning' : 
                                    ($appointment->status == 'completed' ? 'info' : 'danger')) }}">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Booked On:</label>
                            <p class="mb-0">{{ $appointment->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        @if($appointment->notes)
                        <div class="col-12 mb-3">
                            <label class="fw-bold">Your Notes:</label>
                            <p class="mb-0">{{ $appointment->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($appointment->medicalRecord)
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-notes-medical me-2"></i>Medical Record</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="fw-bold">Diagnosis:</label>
                            <p class="mb-0">{{ $appointment->medicalRecord->diagnosis ?: 'No diagnosis recorded' }}</p>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="fw-bold">Prescription:</label>
                            <p class="mb-0">{{ $appointment->medicalRecord->prescription ?: 'No prescription recorded' }}</p>
                        </div>
                        @if($appointment->medicalRecord->remarks)
                        <div class="col-12">
                            <label class="fw-bold">Remarks:</label>
                            <p class="mb-0">{{ $appointment->medicalRecord->remarks }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>Actions</h5>
                </div>
                <div class="card-body">
                    @if(in_array($appointment->status, ['pending', 'confirmed']))
                        <a href="{{ route('patient.appointments.reschedule-form', $appointment) }}" 
                           class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-calendar-alt me-2"></i>Reschedule Appointment
                        </a>
                        
                        <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100" 
                                    onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                <i class="fas fa-times me-2"></i>Cancel Appointment
                            </button>
                        </form>
                    @elseif($appointment->status == 'completed')
                        <div class="alert alert-info">
                            <i class="fas fa-check-circle me-2"></i>This appointment has been completed.
                        </div>
                    @elseif($appointment->status == 'cancelled')
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>This appointment was cancelled.
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Doctor Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> Dr. {{ $appointment->doctor->user->name }}</p>
                    <p><strong>Specialization:</strong> {{ $appointment->doctor->specialization->name }}</p>
                    <p><strong>License:</strong> {{ $appointment->doctor->license_number }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection