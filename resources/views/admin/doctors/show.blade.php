{{-- resources/views/admin/doctors/show.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">DOCTOR DETAILS</h2>
            <p class="text-muted mb-0">View complete information about Dr. {{ $doctor->user->name }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.doctors.index') }}" class="btn-back">
                <i class="fas fa-arrow-left me-2"></i>
                BACK TO DOCTORS
            </a>
            <a href="{{ route('admin.doctors.edit', $doctor) }}" class="btn-edit">
                <i class="fas fa-edit me-2"></i>
                EDIT DOCTOR
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TOTAL APPOINTMENTS</span>
                    <span class="stat-value">{{ $totalAppointments }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TODAY'S APPOINTMENTS</span>
                    <span class="stat-value">{{ $todayAppointments }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">COMPLETED</span>
                    <span class="stat-value">{{ $completedAppointments }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">PENDING</span>
                    <span class="stat-value">{{ $pendingAppointments }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Doctor Information -->
        <div class="col-md-4">
            <div class="info-card light">
                <div class="info-card-header">
                    <h5 class="info-card-title">PROFILE INFORMATION</h5>
                </div>
                <div class="info-card-body text-center">
                    <div class="profile-avatar mb-3">
                        <div class="avatar-circle" data-initial="{{ substr($doctor->user->name, 0, 1) }}">
                            {{ substr($doctor->user->name, 0, 1) }}
                        </div>
                    </div>
                    <h4 class="doctor-full-name">Dr. {{ $doctor->user->name }}</h4>
                    <span class="specialization-tag">{{ $doctor->specialization->name }}</span>
                    
                    <div class="profile-info mt-4">
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <span>{{ $doctor->user->email }}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-phone-alt"></i>
                            <span>{{ $doctor->user->phone }}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $doctor->user->address }}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-id-card"></i>
                            <span>License: {{ $doctor->license_number }}</span>
                        </div>
                        @if($doctor->user->gender)
                        <div class="info-item">
                            <i class="fas fa-venus-mars"></i>
                            <span>{{ ucfirst($doctor->user->gender) }}</span>
                        </div>
                        @endif
                        @if($doctor->user->dob)
                        <div class="info-item">
                            <i class="fas fa-birthday-cake"></i>
                            <span>{{ \Carbon\Carbon::parse($doctor->user->dob)->format('M d, Y') }} ({{ \Carbon\Carbon::parse($doctor->user->dob)->age }} years)</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Professional Details -->
            <div class="info-card light mt-4">
                <div class="info-card-header">
                    <h5 class="info-card-title">PROFESSIONAL DETAILS</h5>
                </div>
                <div class="info-card-body">
                    <div class="detail-item">
                        <span class="detail-label">Qualification:</span>
                        <span class="detail-value">{{ $doctor->qualification ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Experience:</span>
                        <span class="detail-value">{{ $doctor->experience ?? 0 }} years</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Consultation Fee:</span>
                        <span class="detail-value">${{ $doctor->fee ?? '100' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Max Patients/Day:</span>
                        <span class="detail-value">{{ $doctor->max_patients ?? 20 }}</span>
                    </div>
                    @if($doctor->bio)
                    <div class="detail-item">
                        <span class="detail-label">Biography:</span>
                        <p class="bio-text mt-2">{{ $doctor->bio }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Working Schedule & Recent Appointments -->
        <div class="col-md-8">
            <!-- Working Schedule -->
            <div class="info-card light mb-4">
                <div class="info-card-header">
                    <h5 class="info-card-title">WORKING SCHEDULE</h5>
                    <a href="{{ route('admin.schedules.create', ['doctor_id' => $doctor->id]) }}" class="btn-small">
                        <i class="fas fa-plus me-1"></i> Add Schedule
                    </a>
                </div>
                <div class="info-card-body">
                    @if($schedules->count() > 0)
                        <div class="schedule-grid">
                            @foreach($schedules as $schedule)
                            <div class="schedule-card">
                                <div class="schedule-day">{{ $schedule->day_name }}</div>
                                <div class="schedule-time">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - 
                                    {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                </div>
                                <div class="schedule-patients">
                                    <i class="fas fa-users me-1"></i> Max: {{ $schedule->max_patients }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No schedule set for this doctor.</p>
                    @endif
                </div>
            </div>

            <!-- Recent Appointments -->
            <div class="info-card light">
                <div class="info-card-header">
                    <h5 class="info-card-title">RECENT APPOINTMENTS</h5>
                    <a href="{{ route('admin.appointments.index', ['doctor_id' => $doctor->id]) }}" class="btn-small">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="info-card-body">
                    @if($recentAppointments->count() > 0)
                        <div class="appointments-list">
                            @foreach($recentAppointments as $appointment)
                            <div class="appointment-item">
                                <div class="appointment-patient">
                                    <div class="patient-avatar-small">
                                        {{ substr($appointment->patient->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="patient-name">{{ $appointment->patient->name }}</div>
                                        <div class="appointment-datetime">
                                            {{ $appointment->appointment_date->format('M d, Y') }} at 
                                            {{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="appointment-status">
                                    <span class="status-badge status-{{ $appointment->status }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No recent appointments.</p>
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

    /* Edit Button */
    .btn-edit {
        padding: 10px 20px;
        background: #111;
        border: 1px solid #111;
        color: #fff;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }

    .dark-theme .btn-edit {
        background: #fff;
        border-color: #fff;
        color: #111;
    }

    .btn-edit:hover {
        background: #fff;
        color: #111;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    /* Small Button */
    .btn-small {
        padding: 5px 12px;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        color: #111;
        text-decoration: none;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }

    .dark-theme .btn-small {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #fff;
    }

    .btn-small:hover {
        background: #111;
        color: #fff;
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

    /* Profile Avatar */
    .profile-avatar {
        display: flex;
        justify-content: center;
    }

    .avatar-circle {
        width: 120px;
        height: 120px;
        background: #f8f8f8;
        border: 2px solid #eaeaea;
        border-radius: 50%;
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

    .doctor-full-name {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .specialization-tag {
        display: inline-block;
        padding: 0.35rem 1rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        font-size: 0.9rem;
    }

    .dark-theme .specialization-tag {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    /* Profile Info */
    .profile-info {
        text-align: left;
    }

    .info-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #eaeaea;
    }

    .dark-theme .info-item {
        border-bottom-color: #2a2a2a;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-item i {
        width: 20px;
        color: #999;
    }

    .info-item span {
        font-size: 0.9rem;
    }

    /* Detail Items */
    .detail-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #eaeaea;
    }

    .dark-theme .detail-item {
        border-bottom-color: #2a2a2a;
    }

    .detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: #666;
        margin-bottom: 0.25rem;
    }

    .dark-theme .detail-label {
        color: #999;
    }

    .detail-value {
        font-size: 0.95rem;
    }

    .bio-text {
        font-size: 0.9rem;
        line-height: 1.6;
        opacity: 0.8;
    }

    /* Schedule Grid */
    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
    }

    .schedule-card {
        padding: 1rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        transition: all 0.3s ease;
    }

    .dark-theme .schedule-card {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .schedule-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .schedule-day {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .schedule-time {
        font-size: 0.85rem;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .dark-theme .schedule-time {
        color: #999;
    }

    .schedule-patients {
        font-size: 0.8rem;
    }

    /* Appointments List */
    .appointments-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .appointment-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        transition: all 0.3s ease;
    }

    .dark-theme .appointment-item {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .appointment-item:hover {
        transform: translateX(5px);
    }

    .appointment-patient {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .patient-avatar-small {
        width: 35px;
        height: 35px;
        background: #fff;
        border: 1px solid #eaeaea;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .dark-theme .patient-avatar-small {
        background: #1a1a1a;
        border-color: #3a3a3a;
    }

    .patient-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .appointment-datetime {
        font-size: 0.8rem;
        color: #666;
    }

    .dark-theme .appointment-datetime {
        color: #999;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        border: 1px solid transparent;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
        border-color: #ffeeba;
    }

    .status-confirmed {
        background: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }

    .status-completed {
        background: #d1ecf1;
        color: #0c5460;
        border-color: #bee5eb;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .d-flex {
            flex-direction: column;
            gap: 1rem;
        }

        .btn-back, .btn-edit {
            width: 100%;
            justify-content: center;
        }

        .schedule-grid {
            grid-template-columns: 1fr;
        }

        .appointment-item {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }
    }
</style>
@endpush