{{-- resources/views/patient/appointments/show.blade.php --}}
@extends('layouts.patient')

@section('title', 'Appointment Details')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">APPOINTMENT DETAILS</h2>
            <p class="text-muted mb-0">View complete information about your appointment</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('patient.appointments.index') }}" class="btn-back">
                <i class="fas fa-arrow-left me-2"></i>
                BACK TO APPOINTMENTS
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="status-banner mb-4 status-{{ $appointment->status }}">
        <div class="status-icon">
            @switch($appointment->status)
                @case('pending')
                    <i class="fas fa-clock"></i>
                    @break
                @case('confirmed')
                    <i class="fas fa-check-circle"></i>
                    @break
                @case('completed')
                    <i class="fas fa-check-double"></i>
                    @break
                @case('cancelled')
                    <i class="fas fa-times-circle"></i>
                    @break
                @default
                    <i class="fas fa-calendar-alt"></i>
            @endswitch
        </div>
        <div class="status-content">
            <span class="status-label">CURRENT STATUS</span>
            <span class="status-value">{{ ucfirst($appointment->status) }}</span>
        </div>
        <div class="status-time">
            Last updated: {{ $appointment->updated_at->format('M d, Y h:i A') }}
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column - Appointment Info -->
        <div class="col-md-5">
            <div class="info-card">
                <div class="info-card-header">
                    <h5 class="info-card-title">
                        <i class="fas fa-calendar-alt me-2"></i>
                        APPOINTMENT INFO
                    </h5>
                    <span class="appointment-id">#{{ $appointment->id }}</span>
                </div>
                <div class="info-card-body">
                    <div class="info-grid">
                        <div class="info-row">
                            <span class="info-label">Date:</span>
                            <span class="info-value">{{ $appointment->appointment_date->format('l, F d, Y') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Time:</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Duration:</span>
                            <span class="info-value">30 minutes</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Booked on:</span>
                            <span class="info-value">{{ $appointment->created_at->format('M d, Y h:i A') }}</span>
                        </div>
                    </div>

                    @if($appointment->notes)
                    <div class="notes-section mt-4">
                        <h6 class="notes-title">YOUR NOTES</h6>
                        <div class="notes-content">
                            {{ $appointment->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions Card -->
            @if(in_array($appointment->status, ['pending', 'confirmed']))
            <div class="info-card mt-4">
                <div class="info-card-header">
                    <h5 class="info-card-title">
                        <i class="fas fa-bolt me-2"></i>
                        QUICK ACTIONS
                    </h5>
                </div>
                <div class="info-card-body">
                    <div class="quick-actions">
                        <a href="{{ route('patient.appointments.reschedule-form', $appointment) }}" class="quick-action">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Reschedule</span>
                        </a>
                        <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="quick-action text-danger" onclick="return confirm('Are you sure you want to cancel this appointment?')">
                                <i class="fas fa-times-circle"></i>
                                <span>Cancel</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Doctor & Medical Info -->
        <div class="col-md-7">
            <!-- Doctor Information Card -->
            <div class="info-card mb-4">
                <div class="info-card-header">
                    <h5 class="info-card-title">
                        <i class="fas fa-user-md me-2"></i>
                        DOCTOR INFORMATION
                    </h5>
                </div>
                <div class="info-card-body">
                    <div class="profile-section">
                        <div class="profile-avatar doctor-avatar">
                            {{ substr($appointment->doctor->user->name, 0, 1) }}
                        </div>
                        <div class="profile-details">
                            <h4 class="profile-name">Dr. {{ $appointment->doctor->user->name }}</h4>
                            <div class="profile-specialty">{{ $appointment->doctor->specialization->name }}</div>
                            <div class="profile-contact">
                                <span><i class="fas fa-id-card"></i> License: {{ $appointment->doctor->license_number }}</span>
                            </div>
                            @if($appointment->doctor->user->phone)
                            <div class="profile-contact">
                                <span><i class="fas fa-phone-alt"></i> {{ $appointment->doctor->user->phone }}</span>
                            </div>
                            @endif
                            @if($appointment->doctor->user->email)
                            <div class="profile-contact">
                                <span><i class="fas fa-envelope"></i> {{ $appointment->doctor->user->email }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($appointment->doctor->qualification || $appointment->doctor->experience)
                    <div class="doctor-qualifications mt-3">
                        @if($appointment->doctor->qualification)
                        <span class="qualification-badge">{{ $appointment->doctor->qualification }}</span>
                        @endif
                        @if($appointment->doctor->experience)
                        <span class="qualification-badge">{{ $appointment->doctor->experience }} years experience</span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Medical Records Card -->
            <div class="info-card">
                <div class="info-card-header">
                    <h5 class="info-card-title">
                        <i class="fas fa-notes-medical me-2"></i>
                        MEDICAL RECORDS
                    </h5>
                </div>
                <div class="info-card-body">
                    @if($appointment->medicalRecord)
                        <div class="medical-records">
                            <div class="record-item">
                                <div class="record-label">Diagnosis:</div>
                                <div class="record-value">{{ $appointment->medicalRecord->diagnosis ?? 'Not provided' }}</div>
                            </div>
                            <div class="record-item">
                                <div class="record-label">Prescription:</div>
                                <div class="record-value">{{ $appointment->medicalRecord->prescription ?? 'Not provided' }}</div>
                            </div>
                            @if($appointment->medicalRecord->remarks)
                            <div class="record-item">
                                <div class="record-label">Remarks:</div>
                                <div class="record-value">{{ $appointment->medicalRecord->remarks }}</div>
                            </div>
                            @endif
                            <div class="record-meta">
                                <i class="fas fa-clock me-1"></i> Added on: {{ $appointment->medicalRecord->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                    @else
                        <div class="empty-medical">
                            <i class="fas fa-notes-medical fa-3x mb-3 opacity-50"></i>
                            <p class="text-muted mb-0">No medical records available for this appointment.</p>
                            @if($appointment->status == 'pending' || $appointment->status == 'confirmed')
                            <p class="text-muted small mt-2">Records will be added after your appointment.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
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

    /* Status Banner */
    .status-banner {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        border: 1px solid #eaeaea;
        gap: 1.5rem;
    }

    .dark-theme .status-banner {
        border-color: #2a2a2a;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-confirmed {
        background: #d4edda;
        color: #155724;
    }

    .status-completed {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .dark-theme .status-pending {
        background: #665c1c;
        color: #ffd966;
    }

    .dark-theme .status-confirmed {
        background: #1e4a2a;
        color: #9bdf9b;
    }

    .dark-theme .status-completed {
        background: #1e5460;
        color: #9fd9e6;
    }

    .dark-theme .status-cancelled {
        background: #64262e;
        color: #f4acb7;
    }

    .status-icon {
        width: 50px;
        height: 50px;
        background: rgba(0, 0, 0, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .status-content {
        flex: 1;
    }

    .status-label {
        display: block;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        opacity: 0.7;
        margin-bottom: 0.25rem;
    }

    .status-value {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
    }

    .status-time {
        font-size: 0.8rem;
        opacity: 0.7;
    }

    /* Info Card */
    .info-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        overflow: hidden;
    }

    .dark-theme .info-card {
        background: #1a1a1a;
        border-color: #2a2a2a;
    }

    .info-card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #eaeaea;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dark-theme .info-card-header {
        border-bottom-color: #2a2a2a;
    }

    .info-card-title {
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin: 0;
    }

    .info-card-body {
        padding: 1.5rem;
    }

    .appointment-id {
        font-size: 0.9rem;
        font-weight: 600;
        color: #666;
    }

    .dark-theme .appointment-id {
        color: #999;
    }

    /* Info Grid */
    .info-grid {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .info-row {
        display: flex;
        padding: 0.5rem 0;
        border-bottom: 1px dashed #eaeaea;
    }

    .dark-theme .info-row {
        border-bottom-color: #2a2a2a;
    }

    .info-label {
        width: 100px;
        font-size: 0.8rem;
        color: #666;
    }

    .dark-theme .info-label {
        color: #999;
    }

    .info-value {
        flex: 1;
        font-weight: 500;
    }

    /* Notes Section */
    .notes-section {
        padding: 1rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
    }

    .dark-theme .notes-section {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .notes-title {
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #666;
    }

    .dark-theme .notes-title {
        color: #999;
    }

    .notes-content {
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* Quick Actions */
    .quick-actions {
        display: flex;
        gap: 1rem;
    }

    .quick-action {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        text-decoration: none;
        color: #111;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .dark-theme .quick-action {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #fff;
    }

    .quick-action:hover {
        transform: translateY(-5px);
        background: #111;
        color: #fff;
    }

    .dark-theme .quick-action:hover {
        background: #fff;
        color: #111;
    }

    .quick-action i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .quick-action span {
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* Profile Section */
    .profile-section {
        display: flex;
        gap: 1.5rem;
        align-items: center;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        background: #f8f8f8;
        border: 2px solid #eaeaea;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .dark-theme .profile-avatar {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .doctor-avatar {
        background: #111;
        color: #fff;
    }

    .profile-details {
        flex: 1;
    }

    .profile-name {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .profile-specialty {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        font-size: 0.8rem;
        margin-bottom: 0.5rem;
    }

    .dark-theme .profile-specialty {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .profile-contact {
        font-size: 0.9rem;
        margin-bottom: 0.25rem;
    }

    .profile-contact i {
        width: 20px;
        color: #666;
        margin-right: 0.5rem;
    }

    .dark-theme .profile-contact i {
        color: #999;
    }

    /* Qualifications */
    .doctor-qualifications {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .qualification-badge {
        padding: 0.25rem 0.75rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        font-size: 0.8rem;
    }

    .dark-theme .qualification-badge {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    /* Medical Records */
    .medical-records {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .record-item {
        padding: 1rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
    }

    .dark-theme .record-item {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .record-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .dark-theme .record-label {
        color: #999;
    }

    .record-value {
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .record-meta {
        font-size: 0.7rem;
        color: #666;
        text-align: right;
    }

    .dark-theme .record-meta {
        color: #999;
    }

    .empty-medical {
        text-align: center;
        padding: 2rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .status-banner {
            flex-direction: column;
            text-align: center;
        }

        .profile-section {
            flex-direction: column;
            text-align: center;
        }

        .quick-actions {
            flex-direction: column;
        }

        .info-row {
            flex-direction: column;
            gap: 0.25rem;
        }

        .info-label {
            width: auto;
        }
    }
</style>
@endpush