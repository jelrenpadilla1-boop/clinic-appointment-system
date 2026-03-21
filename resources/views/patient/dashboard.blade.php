@extends('layouts.patient')

@section('title', 'Patient Dashboard')

@section('content')
<div class="py-4">
    <!-- Welcome Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">WELCOME BACK, {{ strtoupper(auth()->user()->name) }}</h2>
            <p class="text-muted mb-0">{{ now()->format('l, F d, Y') }}</p>
        </div>
        <div class="header-actions">
            <span class="badge bg-light text-dark p-3">
                <i class="fas fa-clock me-2"></i>{{ now()->format('h:i A') }}
            </span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e8f0fe;">
                    <i class="fas fa-calendar-check" style="color: #1976d2;"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TOTAL APPOINTMENTS</span>
                    <span class="stat-value">{{ $totalAppointments ?? 0 }}</span>
                    <span class="stat-trend">lifetime</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e8f5e9;">
                    <i class="fas fa-clock" style="color: #2e7d32;"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">UPCOMING</span>
                    <span class="stat-value">{{ $upcomingAppointments->count() }}</span>
                    <span class="stat-trend">next 30 days</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #f3e5f5;">
                    <i class="fas fa-check-circle" style="color: #7b1fa2;"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">COMPLETED</span>
                    <span class="stat-value">{{ $completedAppointments ?? 0 }}</span>
                    <span class="stat-trend">total</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #ffebee;">
                    <i class="fas fa-times-circle" style="color: #c62828;"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">CANCELLED</span>
                    <span class="stat-value">{{ $cancelledAppointments ?? 0 }}</span>
                    <span class="stat-trend">total</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <a href="{{ route('patient.book-appointment') }}" class="action-card">
                <div class="action-icon" style="background: #e3f2fd;">
                    <i class="fas fa-plus-circle" style="color: #1976d2;"></i>
                </div>
                <div class="action-content">
                    <h3 class="action-title">BOOK APPOINTMENT</h3>
                    <p class="action-description">Schedule a new appointment with your doctor</p>
                </div>
                <i class="fas fa-arrow-right action-arrow"></i>
            </a>
        </div>
        
        <div class="col-md-4">
            <a href="{{ route('patient.appointments.index') }}" class="action-card">
                <div class="action-icon" style="background: #e8f5e9;">
                    <i class="fas fa-calendar-alt" style="color: #2e7d32;"></i>
                </div>
                <div class="action-content">
                    <h3 class="action-title">MY APPOINTMENTS</h3>
                    <p class="action-description">View and manage your appointments</p>
                </div>
                <i class="fas fa-arrow-right action-arrow"></i>
            </a>
        </div>
        
        <div class="col-md-4">
            <a href="{{ route('patient.medical-history') }}" class="action-card">
                <div class="action-icon" style="background: #f3e5f5;">
                    <i class="fas fa-notes-medical" style="color: #7b1fa2;"></i>
                </div>
                <div class="action-content">
                    <h3 class="action-title">MEDICAL HISTORY</h3>
                    <p class="action-description">Access your medical records and prescriptions</p>
                </div>
                <i class="fas fa-arrow-right action-arrow"></i>
            </a>
        </div>
    </div>

    <!-- Upcoming Appointments Section -->
    <div class="dashboard-card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <div class="header-icon" style="background: #e3f2fd;">
                    <i class="fas fa-calendar-day" style="color: #1976d2;"></i>
                </div>
                <div>
                    <h5 class="card-title">UPCOMING APPOINTMENTS</h5>
                    <p class="card-subtitle">Your scheduled appointments</p>
                </div>
            </div>
            <a href="{{ route('patient.appointments.index') }}" class="btn-link">
                VIEW ALL <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        
        <div class="card-body p-0">
            @if($upcomingAppointments->count() > 0)
                <div class="appointments-list">
                    @foreach($upcomingAppointments as $appointment)
                        <div class="appointment-item">
                            <div class="appointment-time">
                                <span class="date">{{ $appointment->appointment_date->format('M d, Y') }}</span>
                                <span class="time">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</span>
                            </div>
                            
                            <div class="appointment-doctor">
                                <div class="doctor-avatar">
                                    {{ substr($appointment->doctor->user->name, 0, 1) }}
                                </div>
                                <div class="doctor-info">
                                    <span class="doctor-name">Dr. {{ $appointment->doctor->user->name }}</span>
                                    <span class="doctor-specialty">{{ $appointment->doctor->specialization->name }}</span>
                                </div>
                            </div>
                            
                            <div class="appointment-status">
                                @if($appointment->status == 'confirmed')
                                    <span class="status-badge status-confirmed">Confirmed</span>
                                @elseif($appointment->status == 'pending')
                                    <span class="status-badge status-pending">Pending</span>
                                @endif
                            </div>
                            
                            <div class="appointment-actions">
                                @if(in_array($appointment->status, ['pending', 'confirmed']))
                                    <a href="{{ route('patient.appointments.reschedule-form', $appointment) }}" class="action-btn" title="Reschedule">
                                        <i class="fas fa-calendar-alt"></i>
                                    </a>
                                    <form action="{{ route('patient.appointments.cancel', $appointment) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="action-btn text-danger" title="Cancel" onclick="return confirm('Cancel this appointment?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h6>No Upcoming Appointments</h6>
                    <p class="text-muted mb-4">You don't have any scheduled appointments.</p>
                    <a href="{{ route('patient.book-appointment') }}" class="btn-primary">
                        <i class="fas fa-plus-circle me-2"></i>BOOK APPOINTMENT
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Activity & Quick Tips -->
    <div class="row g-4 mt-4">
        <div class="col-md-6">
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="header-icon" style="background: #fff3e0;">
                            <i class="fas fa-history" style="color: #ed6c02;"></i>
                        </div>
                        <div>
                            <h5 class="card-title">RECENT ACTIVITY</h5>
                            <p class="card-subtitle">Your latest interactions</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $recentActivity = App\Models\Appointment::where('patient_id', auth()->id())
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
                    @endphp
                    
                    @if($recentActivity->count() > 0)
                        <div class="activity-list">
                            @foreach($recentActivity as $activity)
                                <div class="activity-item">
                                    <div class="activity-icon">
                                        @if($activity->status == 'completed')
                                            <i class="fas fa-check-circle text-success"></i>
                                        @elseif($activity->status == 'pending')
                                            <i class="fas fa-clock text-warning"></i>
                                        @elseif($activity->status == 'confirmed')
                                            <i class="fas fa-check text-info"></i>
                                        @else
                                            <i class="fas fa-times-circle text-danger"></i>
                                        @endif
                                    </div>
                                    <div class="activity-content">
                                        <span class="activity-title">
                                            Appointment with Dr. {{ $activity->doctor->user->name }}
                                        </span>
                                        <span class="activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                                    </div>
                                    <span class="activity-status status-{{ $activity->status }}">
                                        {{ ucfirst($activity->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No recent activity</p>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="header-icon" style="background: #e8f5e9;">
                            <i class="fas fa-lightbulb" style="color: #2e7d32;"></i>
                        </div>
                        <div>
                            <h5 class="card-title">QUICK TIPS</h5>
                            <p class="card-subtitle">Helpful information</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tips-list">
                        <div class="tip-item">
                            <span class="tip-number">1</span>
                            <span class="tip-text">Arrive 10 minutes before your appointment</span>
                        </div>
                        <div class="tip-item">
                            <span class="tip-number">2</span>
                            <span class="tip-text">Bring your ID and insurance card</span>
                        </div>
                        <div class="tip-item">
                            <span class="tip-number">3</span>
                            <span class="tip-text">Cancel or reschedule at least 24 hours in advance</span>
                        </div>
                        <div class="tip-item">
                            <span class="tip-number">4</span>
                            <span class="tip-text">Check your email for appointment reminders</span>
                        </div>
                        <div class="tip-item">
                            <span class="tip-number">5</span>
                            <span class="tip-text">Update your contact information regularly</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Dashboard Cards */
    .dashboard-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        border-radius: 0;
        overflow: hidden;
    }

    .dark-theme .dashboard-card {
        background: #1a1a1a;
        border-color: #2a2a2a;
    }

    .card-header {
        padding: 1.25rem 1.5rem;
        background: transparent;
        border-bottom: 1px solid #eaeaea;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .dark-theme .card-header {
        border-bottom-color: #2a2a2a;
    }

    .header-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .card-title {
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0;
        letter-spacing: 0.5px;
    }

    .card-subtitle {
        font-size: 0.75rem;
        color: #666;
        margin: 0;
    }

    .dark-theme .card-subtitle {
        color: #999;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Stat Cards */
    .stat-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
    }

    .dark-theme .stat-card {
        background: #1a1a1a;
        border-color: #2a2a2a;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px -10px rgba(0, 0, 0, 0.2);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .stat-content {
        flex: 1;
    }

    .stat-label {
        display: block;
        font-size: 0.7rem;
        font-weight: 600;
        color: #666;
        margin-bottom: 0.25rem;
        letter-spacing: 0.5px;
    }

    .dark-theme .stat-label {
        color: #999;
    }

    .stat-value {
        display: block;
        font-size: 1.8rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-trend {
        font-size: 0.7rem;
        color: #999;
    }

    /* Action Cards */
    .action-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        padding: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        text-decoration: none;
        color: inherit;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .dark-theme .action-card {
        background: #1a1a1a;
        border-color: #2a2a2a;
        color: #fff;
    }

    .action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px -10px rgba(0, 0, 0, 0.2);
    }

    .action-icon {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        transition: all 0.3s ease;
    }

    .action-card:hover .action-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .action-content {
        flex: 1;
    }

    .action-title {
        font-size: 1rem;
        font-weight: 600;
        margin: 0 0 0.25rem 0;
        letter-spacing: 0.5px;
    }

    .action-description {
        font-size: 0.8rem;
        color: #666;
        margin: 0;
    }

    .dark-theme .action-description {
        color: #999;
    }

    .action-arrow {
        color: #999;
        transition: all 0.3s ease;
    }

    .action-card:hover .action-arrow {
        color: #111;
        transform: translateX(5px);
    }

    .dark-theme .action-card:hover .action-arrow {
        color: #fff;
    }

    /* Appointments List */
    .appointments-list {
        padding: 0.5rem 0;
    }

    .appointment-item {
        display: flex;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #eaeaea;
        transition: all 0.3s ease;
    }

    .dark-theme .appointment-item {
        border-bottom-color: #2a2a2a;
    }

    .appointment-item:hover {
        background: #f8f8f8;
    }

    .dark-theme .appointment-item:hover {
        background: #2a2a2a;
    }

    .appointment-time {
        min-width: 120px;
    }

    .appointment-time .date {
        display: block;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .appointment-time .time {
        display: block;
        font-size: 0.8rem;
        color: #999;
    }

    .appointment-doctor {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }

    .doctor-avatar {
        width: 40px;
        height: 40px;
        background: #f0f0f0;
        border: 1px solid #eaeaea;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .appointment-item:hover .doctor-avatar {
        background: #111;
        color: #fff;
    }

    .doctor-info {
        line-height: 1.3;
    }

    .doctor-name {
        display: block;
        font-weight: 600;
        font-size: 0.95rem;
    }

    .doctor-specialty {
        display: block;
        font-size: 0.75rem;
        color: #999;
    }

    .appointment-status {
        min-width: 100px;
        text-align: center;
    }

    .appointment-actions {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        width: 32px;
        height: 32px;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: inherit;
        text-decoration: none;
        transition: all 0.3s ease;
        cursor: pointer;
        padding: 0;
    }

    .dark-theme .action-btn {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #fff;
    }

    .action-btn:hover {
        background: #111;
        color: #fff;
        transform: rotate(360deg);
    }

    .dark-theme .action-btn:hover {
        background: #fff;
        color: #111;
    }

    /* Status Badges */
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 600;
        border: 1px solid transparent;
    }

    .status-confirmed {
        background: #e8f5e9;
        color: #2e7d32;
        border-color: #c8e6c9;
    }

    .status-pending {
        background: #fff3e0;
        color: #ed6c02;
        border-color: #ffe0b2;
    }

    .dark-theme .status-confirmed {
        background: #1e4a2a;
        color: #9bdf9b;
        border-color: #2d6a3d;
    }

    .dark-theme .status-pending {
        background: #665c1c;
        color: #ffd966;
        border-color: #857b3c;
    }

    /* Activity List */
    .activity-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.5rem 0;
        border-bottom: 1px dashed #eaeaea;
    }

    .dark-theme .activity-item {
        border-bottom-color: #2a2a2a;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-icon {
        width: 30px;
        text-align: center;
    }

    .activity-content {
        flex: 1;
    }

    .activity-title {
        display: block;
        font-size: 0.9rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .activity-time {
        display: block;
        font-size: 0.7rem;
        color: #999;
    }

    .activity-status {
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
    }

    /* Tips List */
    .tips-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .tip-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.5rem 0;
        border-bottom: 1px dashed #eaeaea;
    }

    .dark-theme .tip-item {
        border-bottom-color: #2a2a2a;
    }

    .tip-item:last-child {
        border-bottom: none;
    }

    .tip-number {
        width: 24px;
        height: 24px;
        background: #111;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .dark-theme .tip-number {
        background: #fff;
        color: #111;
    }

    .tip-text {
        flex: 1;
        font-size: 0.9rem;
    }

    /* Empty State */
    .empty-state {
        padding: 3rem;
        text-align: center;
    }

    /* Button Primary */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        padding: 0.75rem 1.5rem;
        background: #111;
        color: #fff;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .dark-theme .btn-primary {
        background: #fff;
        color: #111;
    }

    .btn-primary:hover {
        background: #fff;
        color: #111;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .dark-theme .btn-primary:hover {
        background: #111;
        color: #fff;
    }

    /* Links */
    .btn-link {
        color: #111;
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-link:hover {
        color: #666;
    }

    .btn-link i {
        transition: transform 0.3s ease;
    }

    .btn-link:hover i {
        transform: translateX(5px);
    }

    /* Badge */
    .badge {
        border-radius: 0;
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .appointment-item {
            flex-wrap: wrap;
            gap: 1rem;
        }

        .appointment-doctor {
            width: 100%;
        }

        .appointment-status {
            text-align: left;
        }

        .action-card {
            flex-wrap: wrap;
            text-align: center;
        }

        .action-icon {
            margin: 0 auto;
        }

        .action-content {
            width: 100%;
        }

        .action-arrow {
            margin: 0 auto;
        }
    }
</style>
@endpush