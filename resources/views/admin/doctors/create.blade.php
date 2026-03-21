{{-- resources/views/admin/doctors/create.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">ADD NEW DOCTOR</h2>
            <p class="text-muted mb-0">Create a new doctor profile in the system</p>
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
                    <button class="nav-link" id="credentials-tab" data-bs-toggle="tab" data-bs-target="#credentials" type="button" role="tab">
                        <i class="fas fa-lock me-2"></i>CREDENTIALS
                    </button>
                </li>
            </ul>
        </div>

        <div class="form-body">
            <form action="{{ route('admin.doctors.store') }}" method="POST" id="createDoctorForm">
                @csrf

                <div class="tab-content" id="doctorTabsContent">
                    <!-- Personal Information Tab -->
                    <div class="tab-pane fade show active" id="personal" role="tabpanel">
                        <div class="row">
                            <div class="col-md-4 text-center mb-4">
                                <div class="doctor-avatar-edit">
                                    <div class="avatar-large" data-initial="+">
                                        <i class="fas fa-user-md"></i>
                                    </div>
                                    <div class="avatar-upload mt-3">
                                        <label for="avatar" class="btn-upload">
                                            <i class="fas fa-camera me-2"></i>Upload Photo
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
                                                <i class="fas fa-user me-2"></i>FULL NAME <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   name="name" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   value="{{ old('name') }}"
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
                                                <i class="fas fa-phone me-2"></i>PHONE NUMBER <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   name="phone" 
                                                   class="form-control @error('phone') is-invalid @enderror" 
                                                   value="{{ old('phone') }}"
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
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-calendar-alt me-2"></i>DATE OF BIRTH
                                            </label>
                                            <input type="date" 
                                                   name="dob" 
                                                   class="form-control" 
                                                   value="{{ old('dob') }}"
                                                   max="{{ date('Y-m-d', strtotime('-18 years')) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-map-marker-alt me-2"></i>ADDRESS <span class="text-danger">*</span>
                                            </label>
                                            <textarea name="address" 
                                                      class="form-control @error('address') is-invalid @enderror" 
                                                      rows="3"
                                                      placeholder="Enter full address">{{ old('address') }}</textarea>
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
                                        <i class="fas fa-stethoscope me-2"></i>SPECIALIZATION <span class="text-danger">*</span>
                                    </label>
                                    <select name="specialization_id" 
                                            class="form-control @error('specialization_id') is-invalid @enderror" 
                                            required>
                                        <option value="">Select Specialization</option>
                                        @foreach($specializations as $spec)
                                            <option value="{{ $spec->id }}" 
                                                {{ old('specialization_id') == $spec->id ? 'selected' : '' }}>
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
                                        <i class="fas fa-id-card me-2"></i>LICENSE NUMBER <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           name="license_number" 
                                           class="form-control @error('license_number') is-invalid @enderror" 
                                           value="{{ old('license_number') }}"
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
                                           value="{{ old('qualification') }}"
                                           placeholder="e.g., MBBS, MD">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-calendar-alt me-2"></i>YEARS OF EXPERIENCE
                                    </label>
                                    <select name="experience" class="form-select">
                                        <option value="">Select Experience</option>
                                        @for($i = 0; $i <= 30; $i++)
                                            <option value="{{ $i }}" {{ old('experience') == $i ? 'selected' : '' }}>
                                                {{ $i }} {{ $i == 1 ? 'year' : 'years' }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-dollar-sign me-2"></i>CONSULTATION FEE
                                    </label>
                                    <input type="number" 
                                           name="fee" 
                                           class="form-control" 
                                           value="{{ old('fee', '100') }}"
                                           min="0"
                                           step="10">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-users me-2"></i>MAX PATIENTS PER DAY
                                    </label>
                                    <input type="number" 
                                           name="max_patients" 
                                           class="form-control" 
                                           value="{{ old('max_patients', '20') }}"
                                           min="1"
                                           max="100">
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
                                              placeholder="Write a brief biography...">{{ old('bio') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-tags me-2"></i>SERVICES OFFERED
                                    </label>
                                    <div class="services-grid">
                                        @php
                                            $services = ['General Consultation', 'Surgery', 'Check-up', 'Emergency Care', 'Follow-up', 'Vaccination', 'Health Screening', 'Specialist Referral'];
                                        @endphp
                                        @foreach($services as $service)
                                            <div class="service-checkbox">
                                                <input type="checkbox" 
                                                       name="services[]" 
                                                       value="{{ $service }}"
                                                       id="service_{{ Str::slug($service) }}"
                                                       {{ in_array($service, old('services', [])) ? 'checked' : '' }}>
                                                <label for="service_{{ Str::slug($service) }}">{{ $service }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Credentials Tab -->
                    <div class="tab-pane fade" id="credentials" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-envelope me-2"></i>EMAIL ADDRESS <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           name="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email') }}"
                                           placeholder="doctor@example.com"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Will be used for login</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-lock me-2"></i>PASSWORD <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" 
                                           name="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           placeholder="Enter password"
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-lock me-2"></i>CONFIRM PASSWORD <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           class="form-control" 
                                           placeholder="Confirm password"
                                           required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">
                                        <i class="fas fa-bell me-2"></i>NOTIFICATION PREFERENCE
                                    </label>
                                    <select name="notification_preference" class="form-select">
                                        <option value="email">Email</option>
                                        <option value="sms">SMS</option>
                                        <option value="both">Both</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    The doctor will receive their login credentials via email after account creation.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input type="checkbox" 
                                   class="form-check-input" 
                                   id="send_notification" 
                                   name="send_notification" 
                                   value="1" 
                                   checked>
                            <label class="form-check-label" for="send_notification">
                                Send welcome email with login details
                            </label>
                        </div>
                        <div class="action-buttons">
                            <button type="reset" class="btn-reset">
                                <i class="fas fa-undo me-2"></i>RESET
                            </button>
                            <button type="submit" class="btn-save">
                                <i class="fas fa-save me-2"></i>CREATE DOCTOR
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
        display: inline-flex;
        align-items: center;
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
        background: #f8f8f8;
    }

    .dark-theme .form-actions {
        border-top-color: #2a2a2a;
        background: #2a2a2a;
    }

    /* Tabs */
    .nav-tabs {
        border-bottom: 2px solid #eaeaea;
        margin-bottom: 2rem;
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
        position: relative;
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
    }

    .nav-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        width: 100%;
        height: 2px;
        background: #111;
        animation: slideIn 0.3s ease;
    }

    .dark-theme .nav-tabs .nav-link.active::after {
        background: #fff;
    }

    @keyframes slideIn {
        from {
            width: 0;
        }
        to {
            width: 100%;
        }
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
        border: 2px dashed #eaeaea;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: #999;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .dark-theme .avatar-large {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #666;
    }

    .avatar-large:hover {
        border-color: #111;
        transform: scale(1.02);
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
        border-radius: 0;
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

    .text-danger {
        color: #dc3545;
    }

    /* Services Grid */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
        padding: 1rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        max-height: 200px;
        overflow-y: auto;
    }

    .dark-theme .services-grid {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .service-checkbox {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem;
        transition: all 0.3s ease;
    }

    .service-checkbox:hover {
        background: rgba(0, 0, 0, 0.05);
        transform: translateX(5px);
    }

    .dark-theme .service-checkbox:hover {
        background: rgba(255, 255, 255, 0.05);
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

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
    }

    .btn-reset,
    .btn-save {
        padding: 12px 24px;
        border: 1px solid transparent;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        cursor: pointer;
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

    /* Alert */
    .alert-info {
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        color: #111;
        padding: 1rem;
        border-left: 3px solid #111;
    }

    .dark-theme .alert-info {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #fff;
        border-left-color: #fff;
    }

    /* Form Check */
    .form-check-input {
        width: 18px;
        height: 18px;
        margin-right: 0.5rem;
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #111;
        border-color: #111;
    }

    .form-check-label {
        cursor: pointer;
        font-size: 0.9rem;
    }

    /* Invalid Feedback */
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-body {
            padding: 1rem;
        }

        .action-buttons {
            flex-direction: column;
            width: 100%;
        }

        .btn-reset,
        .btn-save {
            width: 100%;
        }

        .nav-tabs .nav-link {
            padding: 0.75rem 1rem;
            font-size: 0.8rem;
        }

        .services-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Form validation
    document.getElementById('createDoctorForm').addEventListener('submit', function(e) {
        const password = document.querySelector('input[name="password"]').value;
        const confirm = document.querySelector('input[name="password_confirmation"]').value;
        
        if (password !== confirm) {
            e.preventDefault();
            alert('Passwords do not match!');
        }
    });

    // Avatar upload preview
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const avatar = document.querySelector('.avatar-large');
                avatar.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
                avatar.style.border = '2px solid #111';
            }
            reader.readAsDataURL(file);
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

    // Form reset with confirmation
    document.querySelector('.btn-reset').addEventListener('click', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to reset all form fields?')) {
            document.getElementById('createDoctorForm').reset();
            
            // Reset avatar
            const avatar = document.querySelector('.avatar-large');
            avatar.innerHTML = '<i class="fas fa-user-md"></i>';
            avatar.style.border = '2px dashed #eaeaea';
        }
    });

    // Auto-format phone number
    const phoneInput = document.querySelector('input[name="phone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', function(e) {
            let x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
            e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
        });
    }

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