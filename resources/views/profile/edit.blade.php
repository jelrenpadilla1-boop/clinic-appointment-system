{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.patient')

@section('title', 'Profile Settings')

@section('content')
<div class="py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">PROFILE SETTINGS</h2>
            <p class="text-muted mb-0">Manage your account information and preferences</p>
        </div>
        <div class="header-actions">
            <span class="badge bg-light text-dark p-3">
                <i class="fas fa-user-circle me-2"></i>{{ auth()->user()->role }} ACCOUNT
            </span>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column - Profile Navigation -->
        <div class="col-md-3">
            <div class="profile-sidebar">
                <div class="profile-avatar-large mb-4">
                    <div class="avatar-circle">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>
                
                <div class="user-info text-center mb-4">
                    <h4 class="user-name">{{ auth()->user()->name }}</h4>
                    <p class="user-role">{{ ucfirst(auth()->user()->role) }}</p>
                    <p class="user-email">{{ auth()->user()->email }}</p>
                </div>

                <div class="profile-nav">
                    <a href="#profile-info" class="profile-nav-item active" data-bs-toggle="list">
                        <i class="fas fa-user me-3"></i>
                        Profile Information
                    </a>
                    <a href="#change-password" class="profile-nav-item" data-bs-toggle="list">
                        <i class="fas fa-lock me-3"></i>
                        Change Password
                    </a>
                    <a href="#notifications" class="profile-nav-item" data-bs-toggle="list">
                        <i class="fas fa-bell me-3"></i>
                        Notifications
                    </a>
                    <a href="#danger-zone" class="profile-nav-item text-danger" data-bs-toggle="list">
                        <i class="fas fa-exclamation-triangle me-3"></i>
                        Danger Zone
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Column - Tab Content -->
        <div class="col-md-9">
            <div class="tab-content">
                <!-- Profile Information Tab -->
                <div class="tab-pane fade show active" id="profile-info">
                    <div class="profile-card">
                        <div class="profile-card-header">
                            <h5 class="profile-card-title">
                                <i class="fas fa-user-edit me-2"></i>
                                PROFILE INFORMATION
                            </h5>
                            <p class="profile-card-subtitle">Update your personal information</p>
                        </div>
                        
                        <div class="profile-card-body">
                            @if(session('status') === 'profile-updated')
                                <div class="alert-success-custom">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Profile updated successfully.
                                </div>
                            @endif

                            <form method="POST" action="{{ route('profile.update') }}" id="profileForm">
                                @csrf
                                @method('PATCH')

                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-user me-2"></i>FULL NAME
                                            </label>
                                            <input type="text" 
                                                   name="name" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   value="{{ old('name', $user->name) }}"
                                                   placeholder="Enter your full name"
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
                                                   value="{{ old('email', $user->email) }}"
                                                   placeholder="your@email.com"
                                                   required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-phone-alt me-2"></i>PHONE NUMBER
                                            </label>
                                            <input type="text" 
                                                   name="phone" 
                                                   class="form-control @error('phone') is-invalid @enderror" 
                                                   value="{{ old('phone', $user->phone) }}"
                                                   placeholder="+1 234 567 8900">
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
                                                <option value="male" {{ old('gender', $user->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                                <option value="female" {{ old('gender', $user->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                                <option value="other" {{ old('gender', $user->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
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
                                                   value="{{ old('dob', $user->dob ?? '') }}">
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-map-marker-alt me-2"></i>ADDRESS
                                            </label>
                                            <textarea name="address" 
                                                      class="form-control @error('address') is-invalid @enderror" 
                                                      rows="3"
                                                      placeholder="Enter your full address">{{ old('address', $user->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="button" class="btn-reset" onclick="resetForm()">
                                        <i class="fas fa-undo me-2"></i>RESET
                                    </button>
                                    <button type="submit" class="btn-save">
                                        <i class="fas fa-save me-2"></i>SAVE CHANGES
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Change Password Tab -->
                <div class="tab-pane fade" id="change-password">
                    <div class="profile-card">
                        <div class="profile-card-header">
                            <h5 class="profile-card-title">
                                <i class="fas fa-lock me-2"></i>
                                CHANGE PASSWORD
                            </h5>
                            <p class="profile-card-subtitle">Update your password to keep your account secure</p>
                        </div>
                        
                        <div class="profile-card-body">
                            @if(session('status') === 'password-updated')
                                <div class="alert-success-custom">
                                    <i class="fas fa-check-circle me-2"></i>
                                    Password changed successfully.
                                </div>
                            @endif

                            <form method="POST" action="{{ route('profile.password') }}">
                                @csrf
                                @method('PATCH')

                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-lock me-2"></i>CURRENT PASSWORD
                                            </label>
                                            <input type="password" 
                                                   name="current_password" 
                                                   class="form-control @error('current_password') is-invalid @enderror" 
                                                   placeholder="Enter current password"
                                                   required>
                                            @error('current_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-key me-2"></i>NEW PASSWORD
                                            </label>
                                            <input type="password" 
                                                   name="new_password" 
                                                   class="form-control @error('new_password') is-invalid @enderror" 
                                                   placeholder="Enter new password"
                                                   required>
                                            @error('new_password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-check-circle me-2"></i>CONFIRM PASSWORD
                                            </label>
                                            <input type="password" 
                                                   name="new_password_confirmation" 
                                                   class="form-control" 
                                                   placeholder="Confirm new password"
                                                   required>
                                        </div>
                                    </div>
                                </div>

                                <div class="password-requirements mt-4">
                                    <h6 class="requirements-title">PASSWORD REQUIREMENTS:</h6>
                                    <ul class="requirements-list">
                                        <li><i class="fas fa-circle me-2"></i>Minimum 8 characters</li>
                                        <li><i class="fas fa-circle me-2"></i>At least one uppercase letter</li>
                                        <li><i class="fas fa-circle me-2"></i>At least one number</li>
                                        <li><i class="fas fa-circle me-2"></i>At least one special character</li>
                                    </ul>
                                </div>

                                <div class="form-actions">
                                    <button type="reset" class="btn-reset">
                                        <i class="fas fa-undo me-2"></i>CLEAR
                                    </button>
                                    <button type="submit" class="btn-save">
                                        <i class="fas fa-key me-2"></i>UPDATE PASSWORD
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Notifications Tab -->
                <div class="tab-pane fade" id="notifications">
                    <div class="profile-card">
                        <div class="profile-card-header">
                            <h5 class="profile-card-title">
                                <i class="fas fa-bell me-2"></i>
                                NOTIFICATION PREFERENCES
                            </h5>
                            <p class="profile-card-subtitle">Choose how you want to receive notifications</p>
                        </div>
                        
                        <div class="profile-card-body">
                            <form method="POST" action="{{ route('profile.notifications') }}">
                                @csrf
                                @method('PATCH')

                                <div class="notifications-list">
                                    <div class="notification-item">
                                        <div class="notification-info">
                                            <h6 class="notification-title">Email Notifications</h6>
                                            <p class="notification-description">Receive updates via email</p>
                                        </div>
                                        <label class="switch">
                                            <input type="checkbox" name="email_notifications" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </div>

                                    <div class="notification-item">
                                        <div class="notification-info">
                                            <h6 class="notification-title">SMS Notifications</h6>
                                            <p class="notification-description">Receive text messages for appointment reminders</p>
                                        </div>
                                        <label class="switch">
                                            <input type="checkbox" name="sms_notifications">
                                            <span class="slider"></span>
                                        </label>
                                    </div>

                                    <div class="notification-item">
                                        <div class="notification-info">
                                            <h6 class="notification-title">Appointment Reminders</h6>
                                            <p class="notification-description">Get reminded about upcoming appointments</p>
                                        </div>
                                        <label class="switch">
                                            <input type="checkbox" name="appointment_reminders" checked>
                                            <span class="slider"></span>
                                        </label>
                                    </div>

                                    <div class="notification-item">
                                        <div class="notification-info">
                                            <h6 class="notification-title">Marketing Updates</h6>
                                            <p class="notification-description">Receive news and special offers</p>
                                        </div>
                                        <label class="switch">
                                            <input type="checkbox" name="marketing_updates">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn-save">
                                        <i class="fas fa-save me-2"></i>SAVE PREFERENCES
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Danger Zone Tab -->
                <div class="tab-pane fade" id="danger-zone">
                    <div class="profile-card border-danger">
                        <div class="profile-card-header bg-danger text-white">
                            <h5 class="profile-card-title text-white">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                DANGER ZONE
                            </h5>
                            <p class="profile-card-subtitle text-white-50">Irreversible account actions</p>
                        </div>
                        
                        <div class="profile-card-body">
                            <div class="danger-actions">
                                <div class="danger-item">
                                    <div class="danger-info">
                                        <h6 class="danger-title">Delete Account</h6>
                                        <p class="danger-description">Permanently delete your account and all associated data. This action cannot be undone.</p>
                                    </div>
                                    <button type="button" class="btn-danger-custom" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                        <i class="fas fa-trash-alt me-2"></i>DELETE ACCOUNT
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    DELETE ACCOUNT
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('profile.destroy') }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p class="mb-4">This action is <strong class="text-danger">permanent</strong> and cannot be undone. All your data will be lost.</p>
                    
                    <div class="form-group">
                        <label class="form-label">ENTER YOUR PASSWORD TO CONFIRM</label>
                        <input type="password" name="password" class="form-control" required placeholder="Enter your password">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-2"></i>PERMANENTLY DELETE
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Profile Sidebar */
    .profile-sidebar {
        background: #ffffff;
        border: 1px solid #eaeaea;
        padding: 2rem 1.5rem;
    }

    .dark-theme .profile-sidebar {
        background: #1a1a1a;
        border-color: #2a2a2a;
    }

    .profile-avatar-large {
        display: flex;
        justify-content: center;
    }

    .avatar-circle {
        width: 120px;
        height: 120px;
        background: #f8f8f8;
        border: 2px solid #eaeaea;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .dark-theme .avatar-circle {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .avatar-circle:hover {
        transform: scale(1.05);
        border-color: #111;
    }

    .user-name {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .user-role {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 0.25rem;
    }

    .dark-theme .user-role {
        color: #999;
    }

    .user-email {
        font-size: 0.85rem;
        color: #999;
    }

    /* Profile Navigation */
    .profile-nav {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .profile-nav-item {
        padding: 0.75rem 1rem;
        color: #111;
        text-decoration: none;
        transition: all 0.3s ease;
        border-left: 2px solid transparent;
    }

    .dark-theme .profile-nav-item {
        color: #fff;
    }

    .profile-nav-item:hover {
        background: #f8f8f8;
        border-left-color: #111;
        padding-left: 1.5rem;
    }

    .dark-theme .profile-nav-item:hover {
        background: #2a2a2a;
    }

    .profile-nav-item.active {
        background: #f8f8f8;
        border-left-color: #111;
        font-weight: 600;
    }

    .dark-theme .profile-nav-item.active {
        background: #2a2a2a;
    }

    .profile-nav-item.text-danger {
        color: #dc3545;
    }

    .profile-nav-item.text-danger:hover {
        background: #ffebee;
    }

    /* Profile Card */
    .profile-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
    }

    .dark-theme .profile-card {
        background: #1a1a1a;
        border-color: #2a2a2a;
    }

    .profile-card.border-danger {
        border-color: #dc3545;
    }

    .profile-card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #eaeaea;
    }

    .dark-theme .profile-card-header {
        border-bottom-color: #2a2a2a;
    }

    .profile-card-header.bg-danger {
        background: #dc3545;
        border-bottom: none;
    }

    .profile-card-title {
        font-size: 1rem;
        font-weight: 600;
        margin: 0;
        letter-spacing: 0.5px;
    }

    .profile-card-subtitle {
        font-size: 0.8rem;
        color: #666;
        margin: 0.25rem 0 0 0;
    }

    .dark-theme .profile-card-subtitle {
        color: #999;
    }

    .text-white-50 {
        color: rgba(255, 255, 255, 0.7);
    }

    .profile-card-body {
        padding: 1.5rem;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: #666;
        margin-bottom: 0.5rem;
        letter-spacing: 0.5px;
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

    /* Alert Success */
    .alert-success-custom {
        padding: 1rem;
        background: #e8f5e9;
        border: 1px solid #c8e6c9;
        color: #2e7d32;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
    }

    .dark-theme .alert-success-custom {
        background: #1e4a2a;
        border-color: #2d6a3d;
        color: #9bdf9b;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #eaeaea;
    }

    .dark-theme .form-actions {
        border-top-color: #2a2a2a;
    }

    .btn-reset {
        padding: 0.75rem 1.5rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        color: #111;
        font-weight: 600;
        transition: all 0.3s ease;
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
        padding: 0.75rem 1.5rem;
        background: #111;
        border: 1px solid #111;
        color: #fff;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .dark-theme .btn-save {
        background: #fff;
        border-color: #fff;
        color: #111;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* Password Requirements */
    .password-requirements {
        padding: 1rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
    }

    .dark-theme .password-requirements {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .requirements-title {
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: #666;
    }

    .dark-theme .requirements-title {
        color: #999;
    }

    .requirements-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.5rem;
    }

    .requirements-list li {
        font-size: 0.8rem;
        color: #666;
    }

    .requirements-list i {
        font-size: 0.5rem;
        color: #999;
    }

    .dark-theme .requirements-list li {
        color: #999;
    }

    /* Notifications List */
    .notifications-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .notification-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        transition: all 0.3s ease;
    }

    .dark-theme .notification-item {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .notification-item:hover {
        transform: translateX(5px);
    }

    .notification-info {
        flex: 1;
    }

    .notification-title {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .notification-description {
        font-size: 0.8rem;
        color: #666;
        margin: 0;
    }

    .dark-theme .notification-description {
        color: #999;
    }

    /* Toggle Switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #111;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #111;
    }

    input:checked + .slider:before {
        transform: translateX(26px);
    }

    /* Danger Zone */
    .danger-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .danger-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #ffebee;
        border: 1px solid #ffcdd2;
    }

    .dark-theme .danger-item {
        background: #64262e;
        border-color: #853a44;
    }

    .danger-info {
        flex: 1;
    }

    .danger-title {
        font-size: 1rem;
        font-weight: 600;
        color: #c62828;
        margin-bottom: 0.25rem;
    }

    .dark-theme .danger-title {
        color: #f4acb7;
    }

    .danger-description {
        font-size: 0.85rem;
        color: #666;
        margin: 0;
    }

    .dark-theme .danger-description {
        color: #999;
    }

    .btn-danger-custom {
        padding: 0.5rem 1rem;
        background: #dc3545;
        border: 1px solid #dc3545;
        color: #fff;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-danger-custom:hover {
        background: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }

    /* Modal */
    .modal-content {
        border-radius: 0;
        border: 1px solid #eaeaea;
    }

    .dark-theme .modal-content {
        background: #1a1a1a;
        border-color: #2a2a2a;
    }

    .modal-header.bg-danger {
        background: #dc3545;
    }

    .btn-close-white {
        filter: brightness(0) invert(1);
    }

    .modal-footer {
        border-top: 1px solid #eaeaea;
    }

    .dark-theme .modal-footer {
        border-top-color: #2a2a2a;
    }

    .btn-secondary {
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        color: #111;
        border-radius: 0;
        transition: all 0.3s ease;
    }

    .dark-theme .btn-secondary {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #fff;
    }

    .btn-secondary:hover {
        background: #111;
        color: #fff;
    }

    .btn-danger {
        background: #dc3545;
        border: 1px solid #dc3545;
        color: #fff;
        border-radius: 0;
        transition: all 0.3s ease;
    }

    .btn-danger:hover {
        background: #c82333;
        transform: translateY(-2px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .form-actions {
            flex-direction: column;
        }

        .btn-reset, .btn-save {
            width: 100%;
        }

        .notification-item {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .danger-item {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .requirements-list {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Form reset functionality
    function resetForm() {
        if (confirm('Reset all changes?')) {
            document.getElementById('profileForm').reset();
        }
    }

    // Password validation
    document.querySelector('form[action*="password"]')?.addEventListener('submit', function(e) {
        const newPass = document.querySelector('input[name="new_password"]').value;
        const confirmPass = document.querySelector('input[name="new_password_confirmation"]').value;
        
        if (newPass !== confirmPass) {
            e.preventDefault();
            alert('Passwords do not match!');
        }
    });

    // Tab navigation
    document.querySelectorAll('.profile-nav-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all items
            document.querySelectorAll('.profile-nav-item').forEach(nav => {
                nav.classList.remove('active');
            });
            
            // Add active class to clicked item
            this.classList.add('active');
            
            // Get target tab
            const target = this.getAttribute('href').substring(1);
            
            // Hide all tabs
            document.querySelectorAll('.tab-pane').forEach(tab => {
                tab.classList.remove('show', 'active');
            });
            
            // Show target tab
            document.getElementById(target).classList.add('show', 'active');
        });
    });
</script>
@endpush