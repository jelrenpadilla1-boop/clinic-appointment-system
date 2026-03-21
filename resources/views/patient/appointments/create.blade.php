{{-- resources/views/patient/appointments/create.blade.php --}}
@extends('layouts.patient')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Book New Appointment</h2>
        <a href="{{ route('patient.dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-plus me-2"></i>Appointment Details</h5>
                </div>
                <div class="card-body">
                    <form id="bookingForm" action="{{ route('patient.appointments.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="specialization_id" class="form-label fw-bold">Specialization <span class="text-danger">*</span></label>
                            <select class="form-control @error('specialization_id') is-invalid @enderror" 
                                    id="specialization_id" name="specialization_id" required>
                                <option value="">Select Specialization</option>
                                @foreach($specializations as $specialization)
                                    <option value="{{ $specialization->id }}" {{ old('specialization_id') == $specialization->id ? 'selected' : '' }}>
                                        {{ $specialization->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('specialization_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="doctor_id" class="form-label fw-bold">Doctor <span class="text-danger">*</span></label>
                            <select class="form-control @error('doctor_id') is-invalid @enderror" 
                                    id="doctor_id" name="doctor_id" required disabled>
                                <option value="">Select Doctor</option>
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointment_date" class="form-label fw-bold">Appointment Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('appointment_date') is-invalid @enderror" 
                                           id="appointment_date" name="appointment_date" 
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                                           max="{{ date('Y-m-d', strtotime('+30 days')) }}" 
                                           value="{{ old('appointment_date') }}" required disabled>
                                    <small class="text-muted">You can book up to 30 days in advance</small>
                                    @error('appointment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="appointment_time" class="form-label fw-bold">Available Time Slots <span class="text-danger">*</span></label>
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

                        <div class="mb-3">
                            <label for="notes" class="form-label fw-bold">Additional Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Any specific concerns or information for the doctor">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                <i class="fas fa-calendar-check me-2"></i>Book Appointment
                            </button>
                            <a href="{{ route('patient.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Booking Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <i class="fas fa-clock text-primary me-2"></i>
                            <strong>Duration:</strong> 30 minutes per appointment
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-calendar-check text-success me-2"></i>
                            <strong>Advance Booking:</strong> Up to 30 days
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-ban text-danger me-2"></i>
                            <strong>Cancellation:</strong> 24 hours in advance
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-hourglass-half text-warning me-2"></i>
                            <strong>Status:</strong> Pending until admin confirmation
                        </li>
                        <li class="mb-3">
                            <i class="fas fa-bell text-info me-2"></i>
                            <strong>Notification:</strong> You'll receive email confirmation
                        </li>
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Important Notes</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Arrive 10 minutes before your appointment
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Bring your ID and insurance card
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Wear a mask to the clinic
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Notify us if you're feeling unwell
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    console.log('Document ready - Patient booking form initialized');
    
    // When specialization changes
    $('#specialization_id').change(function() {
        var specializationId = $(this).val();
        console.log('Specialization selected:', specializationId);
        
        if (specializationId) {
            // Show loading state
            $('#doctor_id').prop('disabled', true).empty().append('<option value="">Loading doctors...</option>');
            
            // Use the named route
            var url = '{{ route("get.doctors.by.specialization", ":id") }}';
            url = url.replace(':id', specializationId);
            console.log('Fetching doctors from:', url);
            
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                timeout: 10000,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    console.log('Doctors loaded:', response);
                    
                    $('#doctor_id').prop('disabled', false).empty().append('<option value="">Select Doctor</option>');
                    
                    if (response && response.length > 0) {
                        $.each(response, function(index, doctor) {
                            $('#doctor_id').append('<option value="' + doctor.id + '">Dr. ' + doctor.user.name + ' - ' + doctor.specialization.name + '</option>');
                        });
                    } else {
                        $('#doctor_id').append('<option value="">No doctors available for this specialization</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error Details:');
                    console.error('Status:', status);
                    console.error('Error:', error);
                    console.error('Response:', xhr.responseText);
                    console.error('Status Code:', xhr.status);
                    
                    $('#doctor_id').empty().append('<option value="">Error loading doctors</option>');
                    
                    // Show specific error message based on status
                    if (xhr.status === 404) {
                        alert('Doctor route not found. Please check your internet connection and try again.');
                    } else if (xhr.status === 500) {
                        alert('Server error. Please try again later.');
                    } else if (xhr.status === 0) {
                        alert('Network error. Please check your internet connection.');
                    } else {
                        alert('Unable to load doctors. Please try again. (Error: ' + error + ')');
                    }
                }
            });
        } else {
            $('#doctor_id').prop('disabled', true).empty().append('<option value="">Select Doctor</option>');
            $('#appointment_date').prop('disabled', true);
            $('#appointment_time').prop('disabled', true).empty().append('<option value="">Select Time Slot</option>');
            $('#submitBtn').prop('disabled', true);
        }
    });

    // When doctor changes
    $('#doctor_id').change(function() {
        if ($(this).val()) {
            $('#appointment_date').prop('disabled', false);
            $('#appointment_time').prop('disabled', true).empty().append('<option value="">Select Time Slot</option>');
            $('#submitBtn').prop('disabled', true);
        } else {
            $('#appointment_date').prop('disabled', true);
            $('#appointment_time').prop('disabled', true).empty().append('<option value="">Select Time Slot</option>');
            $('#submitBtn').prop('disabled', true);
        }
    });

    // When date changes
    $('#appointment_date').change(function() {
        var doctorId = $('#doctor_id').val();
        var date = $(this).val();
        
        if (doctorId && date) {
            // Show loading
            $('#appointment_time').prop('disabled', true).empty().append('<option value="">Loading available slots...</option>');
            
            $.ajax({
                url: '{{ route("patient.get-available-slots") }}',
                type: 'GET',
                data: {
                    doctor_id: doctorId,
                    date: date
                },
                dataType: 'json',
                timeout: 10000,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    console.log('Available slots:', response);
                    
                    $('#appointment_time').prop('disabled', false).empty().append('<option value="">Select Time Slot</option>');
                    
                    if (response.slots && response.slots.length > 0) {
                        $.each(response.slots, function(key, slot) {
                            $('#appointment_time').append('<option value="' + slot + '">' + slot + '</option>');
                        });
                        $('#submitBtn').prop('disabled', false);
                    } else {
                        $('#appointment_time').append('<option value="">No available slots for this date</option>');
                        $('#submitBtn').prop('disabled', true);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading slots:', error);
                    $('#appointment_time').empty().append('<option value="">Error loading slots</option>');
                    $('#submitBtn').prop('disabled', true);
                    alert('Unable to load available time slots. Please try again.');
                }
            });
        } else {
            $('#appointment_time').prop('disabled', true).empty().append('<option value="">Select Time Slot</option>');
            $('#submitBtn').prop('disabled', true);
        }
    });

    // Enable submit button when time is selected
    $('#appointment_time').change(function() {
        if ($(this).val()) {
            $('#submitBtn').prop('disabled', false);
        } else {
            $('#submitBtn').prop('disabled', true);
        }
    });

    @php
    if(old('specialization_id')) {
        echo "setTimeout(function() {";
        echo "$('#specialization_id').val('" . old('specialization_id') . "').trigger('change');";
        echo "}, 100);";
    }
    @endphp
});
</script>
@endpush