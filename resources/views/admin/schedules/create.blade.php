{{-- resources/views/admin/schedules/create.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Create Doctor Schedule</h2>
        <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Schedules
        </a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.schedules.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="doctor_id" class="form-label">Doctor</label>
                            <select class="form-control @error('doctor_id') is-invalid @enderror" 
                                    id="doctor_id" name="doctor_id" required>
                                <option value="">Select Doctor</option>
                                @foreach($doctors as $doc)
                                    <option value="{{ $doc->id }}" {{ (old('doctor_id') == $doc->id || (isset($doctor) && $doctor->id == $doc->id)) ? 'selected' : '' }}>
                                        Dr. {{ $doc->user->name }} - {{ $doc->specialization->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('doctor_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="day_of_week" class="form-label">Day of Week</label>
                            <select class="form-control @error('day_of_week') is-invalid @enderror" 
                                    id="day_of_week" name="day_of_week" required>
                                <option value="">Select Day</option>
                                <option value="0" {{ old('day_of_week') == '0' ? 'selected' : '' }}>Sunday</option>
                                <option value="1" {{ old('day_of_week') == '1' ? 'selected' : '' }}>Monday</option>
                                <option value="2" {{ old('day_of_week') == '2' ? 'selected' : '' }}>Tuesday</option>
                                <option value="3" {{ old('day_of_week') == '3' ? 'selected' : '' }}>Wednesday</option>
                                <option value="4" {{ old('day_of_week') == '4' ? 'selected' : '' }}>Thursday</option>
                                <option value="5" {{ old('day_of_week') == '5' ? 'selected' : '' }}>Friday</option>
                                <option value="6" {{ old('day_of_week') == '6' ? 'selected' : '' }}>Saturday</option>
                            </select>
                            @error('day_of_week')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_time" class="form-label">Start Time</label>
                                    <input type="time" class="form-control @error('start_time') is-invalid @enderror" 
                                           id="start_time" name="start_time" value="{{ old('start_time') }}" required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_time" class="form-label">End Time</label>
                                    <input type="time" class="form-control @error('end_time') is-invalid @enderror" 
                                           id="end_time" name="end_time" value="{{ old('end_time') }}" required>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="max_patients" class="form-label">Maximum Patients Per Day</label>
                            <input type="number" class="form-control @error('max_patients') is-invalid @enderror" 
                                   id="max_patients" name="max_patients" value="{{ old('max_patients', 10) }}" 
                                   min="1" max="50" required>
                            @error('max_patients')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Create Schedule</button>
                        <a href="{{ route('admin.schedules.index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Important Notes</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Each doctor can have only one schedule per day
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock text-primary me-2"></i>
                            Appointment slots are 30 minutes each
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-users text-success me-2"></i>
                            Max patients per day determines available slots
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection