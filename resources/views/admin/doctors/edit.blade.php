{{-- resources/views/admin/doctors/edit.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">EDIT DOCTOR</h2>
            <p class="text-muted mb-0">Update doctor information and details</p>
        </div>
        <a href="{{ route('admin.doctors.index') }}" class="btn-back">
            <i class="fas fa-arrow-left me-2"></i>
            BACK TO DOCTORS
        </a>
    </div>

    <div class="form-card">
        <div class="form-header">
            <ul class="nav nav-tabs" id="doctorTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                        <i class="fas fa-user-md me-2"></i>PERSONAL INFO
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="professional-tab" data-bs-toggle="tab" data-bs-target="#professional" type="button" role="tab">
                        <i class="fas fa-briefcase me-2"></i>PROFESSIONAL
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="schedule-tab" data-bs-toggle="tab" data-bs-target="#schedule" type="button" role="tab">
                        <i class="fas fa-clock me-2"></i>SCHEDULE
                    </button>
                </li>
            </ul>
        </div>

        <div class="form-body">
            <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST" id="editDoctorForm">
                @csrf
                @method('PUT')

                <div class="tab-content" id="doctorTabsContent">
                    <!-- Personal Information Tab -->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <div class="doctor-avatar-edit">
                                    <div class="avatar-large" data-initial="{{ substr($doctor->user->name, 0, 1) }}">
                                        {{ substr($doctor->user->name, 0, 1) }}
                                    </div>
                                    <div class="avatar-upload mt-3">
                                        <label for="avatar" class="btn-upload">
                                            <i class="fas fa-camera me-2"></i>Change Photo
                                        </label>
                                        <input type="file" id="avatar" class="d-none" accept="image/*">
                                        <small class="text-muted d-block mt-2">Optional</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-user me-2"></i>FULL NAME
                                            </label>
                                            <input type="text" 
                                                   name="name" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   value="{{ old('name', $doctor->user->name) }}"
                                                   placeholder="Enter full name"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-envelope me-2"></i>EMAIL ADDRESS
                                            </label>
                                            <input type="email" 
                                                   name="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   value="{{ old('email', $doctor->user->email) }}"
                                                   placeholder="doctor@example.com"
                                                   required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-phone me-2"></i>PHONE NUMBER
                                            </label>
                                            <input type="text" 
                                                   name="phone" 
                                                   class="form-control @error('phone') is-invalid @enderror" 
                                                   value="{{ old('phone', $doctor->user->phone) }}"
                                                   placeholder="+1 234 567 8900"
                                                   required>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-venus-mars me-2"></i>GENDER
                                            </label>
                                            <select name="gender" class="form-select">
                                                <option value="">Select Gender</option>
                                                <option value="male" {{ old('gender', $doctor->user->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender', $doctor->user->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ old('gender', $doctor->user->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-map-marker-alt me-2"></i>ADDRESS
                                            </label>
                                            <textarea name="address" 
                                                      class="form-control @error('address') is-invalid @enderror" 
                                                      rows="3"
                                                      placeholder="Enter full address">{{ old('address', $doctor->user->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Professional Information Tab -->
                    <div class="tab-pane fade" id="professional" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-stethoscope me-2"></i>SPECIALIZATION
                                    </label>
                                    <select name="specialization_id" 
                                            class="form-control @error('specialization_id') is-invalid @enderror" 
                                            required>
                                        <option value="">Select Specialization</option>
                                        @foreach($specializations as $spec)
                                            <option value="{{ $spec->id }}" 
                                                {{ old('specialization_id', $doctor->specialization_id) == $spec->id ? 'selected' : '' }}>
                                                {{ $spec->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('specialization_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-id-card me-2"></i>LICENSE NUMBER
                                    </label>
                                    <input type="text" 
                                           name="license_number" 
                                           class="form-control @error('license_number') is-invalid @enderror" 
                                           value="{{ old('license_number', $doctor->license_number) }}"
                                           placeholder="e.g., MED123456"
                                           required>
                                    @error('license_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-graduation-cap me-2"></i>QUALIFICATION
                                    </label>
                                    <input type="text" 
                                           name="qualification" 
                                           class="form-control" 
                                           value="{{ old('qualification', $doctor->qualification ?? '') }}"
                                           placeholder="e.g., MBBS, MD">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-calendar-alt me-2"></i>EXPERIENCE
                                    </label>
                                    <select name="experience" class="form-select">
                                        <option value="">Select Experience</option>
                                        @for($i = 1; $i <= 30; $i++)
                                            <option value="{{ $i }}" 
                                                {{ old('experience', $doctor->experience ?? '') == $i ? 'selected' : '' }}>
                                                {{ $i }} {{ $i == 1 ? 'year' : 'years' }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-file-alt me-2"></i>BIOGRAPHY
                                    </label>
                                    <textarea name="bio" 
                                              class="form-control" 
                                              rows="4"
                                              placeholder="Write a brief biography...">{{ old('bio', $doctor->bio ?? '') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-tags me-2"></i>SERVICES
                                    </label>
                                    <div class="services-grid">
                                        @php
                                            $services = ['Consultation', 'Surgery', 'Check-up', 'Emergency', 'Follow-up', 'Vaccination'];
                                        @endphp
                                        @foreach($services as $service)
                                            <div class="service-checkbox">
                                                <input type="checkbox" 
                                                       name="services[]" 
                                                       value="{{ $service }}"
                                                       id="service_{{ Str::slug($service) }}"
                                                       {{ in_array($service, old('services', $doctor->services ?? [])) ? 'checked' : '' }}>
                                                <label for="service_{{ Str::slug($service) }}">{{ $service }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Schedule Tab -->
                    <div class="tab-pane fade" id="schedule" role="tabpanel">
                        <div class="schedule-info mb-4">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                Set the doctor's working schedule. Leave unchecked for days off.
                            </div>
                        </div>

                        <div class="schedule-grid">
                            @php
                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                $timeSlots = ['09:00-12:00', '12:00-15:00', '15:00-18:00', '18:00-21:00'];
                            @endphp

                            @foreach($days as $day)
                            <div class="schedule-day-card">
                                <div class="day-header">
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               class="form-check-input day-toggle" 
                                               id="day_{{ $day }}"
                                               {{ in_array($day, old('working_days', $doctor->working_days ?? ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'])) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="day_{{ $day }}">
                                            {{ $day }}
                                        </label>
                                    </div>
                                </div>
                                <div class="day-slots">
                                    @foreach($timeSlots as $slot)
                                    <div class="slot-checkbox">
                                        <input type="checkbox" 
                                               name="slots[{{ $day }}][]" 
                                               value="{{ $slot }}"
                                               id="{{ $day }}_{{ Str::slug($slot) }}"
                                               class="time-slot"
                                               {{ in_array($slot, old('slots.' . $day, $doctor->slots[$day] ?? [])) ? 'checked' : '' }}>
                                        <label for="{{ $day }}_{{ Str::slug($slot) }}">{{ $slot }}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-users me-2"></i>MAX PATIENTS PER DAY
                                    </label>
                                    <input type="number" 
                                           name="max_patients" 
                                           class="form-control" 
                                           value="{{ old('max_patients', $doctor->max_patients ?? 20) }}"
                                           min="1"
                                           max="100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-clock me-2"></i>APPOINTMENT DURATION
                                    </label>
                                    <select name="appointment_duration" class="form-select">
                                        <option value="15" {{ old('appointment_duration', $doctor->appointment_duration ?? 30) == 15 ? 'selected' : '' }}>15 minutes</option>
                                        <option value="30" {{ old('appointment_duration', $doctor->appointment_duration ?? 30) == 30 ? 'selected' : '' }}>30 minutes</option>
                                        <option value="45" {{ old('appointment_duration', $doctor->appointment_duration ?? 30) == 45 ? 'selected' : '' }}>45 minutes</option>
                                        <option value="60" {{ old('appointment_duration', $doctor->appointment_duration ?? 30) == 60 ? 'selected' : '' }}>60 minutes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Last updated: {{ $doctor->updated_at->format('M d, Y h:i A') }}
                            </small>
                        </div>
                        <div class="action-buttons">
                            <button type="button" class="btn-preview" onclick="previewChanges()">
                                <i class="fas fa-eye me-2"></i>PREVIEW
                            </button>
                            <button type="reset" class="btn-reset">
                                <i class="fas fa-undo me-2"></i>RESET
                            </button>
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save me-2"></i>SAVE CHANGES
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Back Button */
    .btn-back {
        padding: 10px 20px;
        background: #ffffff;
        border: 1px solid #eaeaea;
        color: #111;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .dark-theme .btn-back {
        background: #1a1a1a;
        border-color: #2a2a2a;
        color: #fff;
    }

    .btn-back:hover {
        background: #111;
        color: #fff;
        transform: translateX(-5px);
    }

    .dark-theme .btn-back:hover {
        background: #fff;
        color: #111;
    }

    /* Form Card */
    .form-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        margin-top: 20px;
    }

    .dark-theme .form-card {
        background: #1a1a1a;
        border-color: #2a2a2a;
    }

    .form-header {
        padding: 1.5rem;
        border-bottom: 1px solid #eaeaea;
    }

    .dark-theme .form-header {
        border-bottom-color: #2a2a2a;
    }

    .form-body {
        padding: 2rem;
    }

    .form-actions {
        padding: 1.5rem;
        border-top: 1px solid #eaeaea;
    }

    .dark-theme .form-actions {
        border-top-color: #2a2a2a;
    }

    /* Tabs */
    .nav-tabs {
        border-bottom: 2px solid #eaeaea;
    }

    .dark-theme .nav-tabs {
        border-bottom-color: #2a2a2a;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #666;
        padding: 1rem 2rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        background: transparent;
    }

    .dark-theme .nav-tabs .nav-link {
        color: #999;
    }

    .nav-tabs .nav-link:hover {
        color: #111;
        transform: translateY(-2px);
    }

    .dark-theme .nav-tabs .nav-link:hover {
        color: #fff;
    }

    .nav-tabs .nav-link.active {
        color: #111;
        background: transparent;
        border-bottom: 2px solid #111;
    }

    .dark-theme .nav-tabs .nav-link.active {
        color: #fff;
        border-bottom-color: #fff;
    }

    /* Avatar */
    .doctor-avatar-edit {
        text-align: center;
    }

    .avatar-large {
        width: 150px;
        height: 150px;
        margin: 0 auto;
        background: #f8f8f8;
        border: 2px solid #eaeaea;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 600;
        color: #111;
        transition: all 0.3s ease;
        position: relative;
    }

    .dark-theme .avatar-large {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #fff;
    }

    .avatar-large:hover {
        transform: scale(1.05);
        border-color: #111;
    }

    .dark-theme .avatar-large:hover {
        border-color: #fff;
    }

    .btn-upload {
        display: inline-block;
        padding: 8px 16px;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        color: #111;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .dark-theme .btn-upload {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #fff;
    }

    .btn-upload:hover {
        background: #111;
        color: #fff;
        transform: translateY(-2px);
    }

    .dark-theme .btn-upload:hover {
        background: #fff;
        color: #111;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        color: #666;
    }

    .dark-theme .form-label {
        color: #999;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #eaeaea;
        transition: all 0.3s ease;
        background: #ffffff;
        color: #111;
    }

    .dark-theme .form-control,
    .dark-theme .form-select {
        background: #1a1a1a;
        border-color: #2a2a2a;
        color: #fff;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #111;
        box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
    }

    .form-control:hover, .form-select:hover {
        border-color: #111;
    }

    /* Services Grid */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        padding: 1rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
    }

    .dark-theme .services-grid {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .service-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .service-checkbox input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .service-checkbox input[type="checkbox"]:hover {
        transform: scale(1.1);
    }

    .service-checkbox label {
        cursor: pointer;
        font-size: 0.9rem;
    }

    /* Schedule Grid */
    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
    }

    .schedule-day-card {
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .dark-theme .schedule-day-card {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .schedule-day-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .day-header {
        padding: 1rem;
        background: #ffffff;
        border-bottom: 1px solid #eaeaea;
    }

    .dark-theme .day-header {
        background: #1a1a1a;
        border-bottom-color: #3a3a3a;
    }

    .day-slots {
        padding: 1rem;
    }

    .slot-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        transition: all 0.3s ease;
    }

    .slot-checkbox:hover {
        background: rgba(0, 0, 0, 0.05);
        transform: translateX(5px);
    }

    .dark-theme .slot-checkbox:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .slot-checkbox input[type="checkbox"] {
        width: 16px;
        height: 16px;
        cursor: pointer;
    }

    .slot-checkbox label {
        cursor: pointer;
        font-size: 0.9rem;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
    }

    .btn-preview,
    .btn-reset,
    .btn-save {
        padding: 12px 24px;
        border: 1px solid transparent;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-preview {
        background: #f8f8f8;
        border-color: #eaeaea;
        color: #111;
    }

    .dark-theme .btn-preview {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #fff;
    }

    .btn-preview:hover {
        background: #111;
        color: #fff;
        transform: translateY(-2px);
    }

    .btn-reset {
        background: #f8f8f8;
        border-color: #eaeaea;
        color: #111;
    }

    .dark-theme .btn-reset {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #fff;
    }

    .btn-reset:hover {
        background: #dc3545;
        border-color: #dc3545;
        color: #fff;
        transform: translateY(-2px);
    }

    .btn-save {
        background: #111;
        color: #fff;
    }

    .dark-theme .btn-save {
        background: #fff;
        color: #111;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .dark-theme .btn-save:hover {
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
    }

    /* Disabled state for slots */
    .day-toggle:not(:checked) ~ .day-slots .slot-checkbox {
        opacity: 0.5;
        pointer-events: none;
    }

    /* Alert */
    .alert-info {
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        color: #111;
        padding: 1rem;
    }

    .dark-theme .alert-info {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #fff;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-body {
            padding: 1rem;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-preview,
        .btn-reset,
        .btn-save {
            width: 100%;
        }

        .schedule-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Day toggle functionality
    document.querySelectorAll('.day-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const slots = this.closest('.schedule-day-card').querySelectorAll('.slot-checkbox input');
            slots.forEach(slot => {
                if (!this.checked) {
                    slot.checked = false;
                }
            });
        });
    });

    // Preview changes
    function previewChanges() {
        // Collect form data
        const formData = new FormData(document.getElementById('editDoctorForm'));
        const data = Object.fromEntries(formData.entries());
        
        // Show preview modal (simplified version)
        alert('Preview mode: All changes will be reviewed before saving.\n\n' + 
              'Doctor: ' + data.name + '\n' +
              'Email: ' + data.email + '\n' +
              'Specialization: ' + document.querySelector('select[name="specialization_id"] option:checked').text);
    }

    // Form reset with confirmation
    document.querySelector('.btn-reset').addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to reset all changes?')) {
            document.getElementById('editDoctorForm').reset();
        }
    });

    // Character counter for bio
    const bio = document.querySelector('textarea[name="bio"]');
    if (bio) {
        bio.addEventListener('input', function() {
            const count = this.value.length;
            const max = 500;
            if (count > max) {
                this.value = this.value.substring(0, max);
            }
        });
    }

    // Animate tabs on switch
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(e) {
            e.target.closest('.tab-pane').style.animation = 'fadeIn 0.5s ease';
        });
    });

    // Add animation keyframes
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
</script>
@endpush