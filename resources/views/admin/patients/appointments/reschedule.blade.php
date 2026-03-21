{{-- resources/views/patient/appointments/reschedule.blade.php --}}
@extends('layouts.patient')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Reschedule Appointment</h2>
        <a href="{{ route('patient.appointments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Appointments
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Reschedule Appointment</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Current Appointment: <strong>{{ $appointment->appointment_date->format('l, F d, Y') }} at {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</strong> with Dr. {{ $appointment->doctor->user->name }}
                    </div>

                    <form id="rescheduleForm" action="{{ route('patient.appointments.reschedule', $appointment) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="doctor_id" class="form-label fw-bold">Doctor <span class="text-danger">*</span></label>
                            <select class="form-control @error('doctor_id') is-invalid @enderror" 
                                    id="doctor_id" name="doctor_id" required>
                                <option value="{{ $appointment->doctor_id }}">Dr. {{ $appointment->doctor->user->name }} (Current)</option>
                                @foreach(\App\Models\Specialization::with('doctors.user')->get() as $specialization)
                                    <optgroup label="{{ $specialization->name }}">
                                        @foreach($specialization->doctors as $doctor)
                                            <option value="{{ $doctor->id }}" {{ $doctor->id == $appointment->doctor_id ? 'selected' : '' }}>
                                                Dr. {{ $doctor->user->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointment_date" class="form-label fw-bold">New Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                                           id="appointment_date" name="appointment_date" 
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                                           max="{{ date('Y-m-d', strtotime('+30 days')) }}" required>
                                    @error('appointment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointment_time" class="form-label fw-bold">New Time <span class="text-danger">*</span></label>
                                    <select class="form-control @error('appointment_time') is-invalid @enderror" 
                                            id="appointment_time" name="appointment_time" required disabled>
                                        <option value="">Select Time Slot</option>
                                    </select>
                                    @error('appointment_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-warning" id="submitBtn" disabled>
                                <i class="fas fa-calendar-check me-2"></i>Confirm Reschedule
                            </button>
                            <a href="{{ route('patient.appointments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Rescheduling Policy</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <strong>Reschedule up to 24 hours in advance</strong>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-calendar-check text-success me-2"></i>
                            <strong>Choose any available slot</strong>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-hourglass-half text-warning me-2"></i>
                            <strong>New appointment will be pending approval</strong>
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-envelope text-info me-2"></i>
                            <strong>You'll receive confirmation via email</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#appointment_date').change(function() {
        var doctorId = $('#doctor_id').val();
        var date = $(this).val();
        
        if (doctorId && date) {
            $('#appointment_time').prop('disabled', true).empty().append('<option value="">Loading available slots...</option>');
            
            $.ajax({
                url: '{{ route("patient.get-available-slots") }}',
                type: 'GET',
                data: {
                    doctor_id: doctorId,
                    date: date
                },
                success: function(data) {
                    $('#appointment_time').prop('disabled', false).empty().append('<option value="">Select Time Slot</option>');
                    
                    if (data.slots && data.slots.length > 0) {
                        $.each(data.slots, function(key, slot) {
                            $('#appointment_time').append('<option value="' + slot + '">' + slot + '</option>');
                        });
                        $('#submitBtn').prop('disabled', false);
                    } else {
                        $('#appointment_time').append('<option value="">No available slots</option>');
                        $('#submitBtn').prop('disabled', true);
                    }
                },
                error: function(xhr) {
                    console.log('Error:', xhr);
                    $('#appointment_time').empty().append('<option value="">Error loading slots</option>');
                    $('#submitBtn').prop('disabled', true);
                }
            });
        } else {
            $('#appointment_time').prop('disabled', true).empty().append('<option value="">Select Time Slot</option>');
            $('#submitBtn').prop('disabled', true);
        }
    });

    $('#appointment_time').change(function() {
        $('#submitBtn').prop('disabled', !$(this).val());
    });
});
</script>
@endpush