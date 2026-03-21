{{-- resources/views/doctor/patients/show.blade.php --}}
@extends('layouts.doctor')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Patient History: {{ $patient->name }}</h2>
        <a href="{{ route('doctor.patients.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Patients
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Patient Information</h5>
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
                            <th>Total Visits:</th>
                            <td>{{ $appointments->total() }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Medical History</h5>
                </div>
                <div class="card-body">
                    @forelse($appointments as $appointment)
                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <strong>Visit Date: {{ $appointment->appointment_date->format('F d, Y') }}</strong>
                                <span class="badge bg-{{ $appointment->status == 'completed' ? 'success' : 'secondary' }} float-end">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                            <div class="card-body">
                                @if($appointment->medicalRecord)
                                    <div class="row">
                                        <div class="col-md-12 mb-2">
                                            <strong>Diagnosis:</strong>
                                            <p>{{ $appointment->medicalRecord->diagnosis ?: 'No diagnosis recorded' }}</p>
                                        </div>
                                        <div class="col-md-12 mb-2">
                                            <strong>Prescription:</strong>
                                            <p>{{ $appointment->medicalRecord->prescription ?: 'No prescription recorded' }}</p>
                                        </div>
                                        @if($appointment->medicalRecord->remarks)
                                            <div class="col-md-12">
                                                <strong>Remarks:</strong>
                                                <p>{{ $appointment->medicalRecord->remarks }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-muted">No medical records for this visit.</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No appointment history found.</p>
                    @endforelse
                    
                    <div class="mt-3">
                        {{ $appointments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection