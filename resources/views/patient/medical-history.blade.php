{{-- resources/views/patient/medical-history.blade.php --}}
@extends('layouts.patient')

@section('content')
<div class="py-4">
    <h2 class="mb-4">My Medical History</h2>

    <div class="card">
        <div class="card-body">
            @forelse($medicalRecords as $record)
                <div class="card mb-3">
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Dr. {{ $record->appointment->doctor->user->name }}</strong>
                                <span class="text-muted ms-2">({{ $record->appointment->doctor->specialization->name }})</span>
                            </div>
                            <div>
                                <span class="badge bg-info">{{ $record->appointment->appointment_date->format('F d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h6 class="fw-bold">Diagnosis:</h6>
                                <p>{{ $record->diagnosis ?: 'No diagnosis recorded' }}</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <h6 class="fw-bold">Prescription:</h6>
                                <p>{{ $record->prescription ?: 'No prescription recorded' }}</p>
                            </div>
                            @if($record->remarks)
                                <div class="col-md-12">
                                    <h6 class="fw-bold">Additional Remarks:</h6>
                                    <p>{{ $record->remarks }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-notes-medical fa-4x text-muted mb-3"></i>
                    <h5>No Medical Records Found</h5>
                    <p class="text-muted">Your medical history will appear here after your appointments.</p>
                </div>
            @endforelse

            <div class="mt-3">
                {{ $medicalRecords->links() }}
            </div>
        </div>
    </div>
</div>
@endsection