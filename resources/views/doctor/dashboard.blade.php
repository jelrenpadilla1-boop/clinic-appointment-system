{{-- resources/views/doctor/dashboard.blade.php --}}
@extends('layouts.doctor')

@section('content')
<div class="py-4">
    <!-- Welcome Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">WELCOME BACK, DR. {{ strtoupper(Auth::user()->name) }}</h2>
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
                <div class="stat-icon" style="background: #e3f2fd;">
                    <i class="fas fa-calendar-day" style="color: #1976d2;"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TODAY'S APPOINTMENTS</span>
                    <span class="stat-value">{{ $todayAppointments }}</span>
                    <span class="stat-trend">scheduled</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #e8f5e9;">
                    <i class="fas fa-calendar-alt" style="color: #2e7d32;"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">UPCOMING</span>
                    <span class="stat-value">{{ $upcomingAppointments }}</span>
                    <span class="stat-trend">next 7 days</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon" style="background: #fff3e0;">
                    <i class="fas fa-users" style="color: #ed6c02;"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TOTAL PATIENTS</span>
                    <span class="stat-value">{{ $totalPatients }}</span>
                    <span class="stat-trend">unique patients</span>
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
                    <span class="stat-value">{{ $completedAppointments }}</span>
                    <span class="stat-trend">total</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="row g-4">
        <!-- Left Column - Today's Schedule -->
        <div class="col-lg-5">
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="header-icon bg-primary-soft">
                            <i class="fas fa-clock text-primary"></i>
                        </div>
                        <div>
                            <h5 class="card-title">TODAY'S SCHEDULE</h5>
                            <p class="card-subtitle">Your appointments for today</p>
                        </div>
                    </div>
                    <span class="badge bg-primary">{{ $todayAppointments }} appointments</span>
                </div>
                <div class="card-body p-0">
                    @php
                        $todayAppointmentsList = App\Models\Appointment::with('patient')
                            ->where('doctor_id', Auth::user()->doctor->id)
                            ->whereDate('appointment_date', today())
                            ->orderBy('appointment_time')
                            ->get();
                    @endphp
                    
                    @if($todayAppointmentsList->count() > 0)
                        <div class="schedule-list">
                            @foreach($todayAppointmentsList as $appointment)
                                <div class="schedule-item">
                                    <div class="schedule-time">
                                        <span class="time">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</span>
                                        <span class="duration">30 min</span>
                                    </div>
                                    <div class="schedule-patient">
                                        <div class="patient-avatar">
                                            {{ substr($appointment->patient->name, 0, 1) }}
                                        </div>
                                        <div class="patient-info">
                                            <span class="patient-name">{{ $appointment->patient->name }}</span>
                                            <span class="patient-contact">{{ $appointment->patient->phone ?? 'No phone' }}</span>
                                        </div>
                                    </div>
                                    <div class="schedule-status">
                                        @if($appointment->status == 'confirmed')
                                            <span class="status-badge status-confirmed">Confirmed</span>
                                        @elseif($appointment->status == 'pending')
                                            <span class="status-badge status-pending">Pending</span>
                                        @elseif($appointment->status == 'completed')
                                            <span class="status-badge status-completed">Completed</span>
                                        @else
                                            <span class="status-badge status-cancelled">Cancelled</span>
                                        @endif
                                    </div>
                                    <a href="{{ route('doctor.appointments.show', $appointment) }}" class="schedule-action">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                            <h6>No Appointments Today</h6>
                            <p class="text-muted">You have no appointments scheduled for today.</p>
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('doctor.schedule') }}" class="btn-link">
                        Manage Schedule <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="dashboard-card mt-4">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="header-icon bg-secondary-soft">
                            <i class="fas fa-bolt text-secondary"></i>
                        </div>
                        <div>
                            <h5 class="card-title">QUICK ACTIONS</h5>
                            <p class="card-subtitle">Frequently used tasks</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="quick-actions-grid">
                        <a href="{{ route('doctor.appointments.index') }}" class="quick-action-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>View All Appointments</span>
                        </a>
                        <a href="{{ route('doctor.patients.index') }}" class="quick-action-item">
                            <i class="fas fa-users"></i>
                            <span>My Patients</span>
                        </a>
                        <a href="{{ route('doctor.schedule') }}" class="quick-action-item">
                            <i class="fas fa-clock"></i>
                            <span>Update Schedule</span>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="quick-action-item">
                            <i class="fas fa-user-cog"></i>
                            <span>Profile Settings</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Recent Appointments & Stats -->
        <div class="col-lg-7">
            <!-- Recent Appointments -->
            <div class="dashboard-card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <div class="header-icon bg-info-soft">
                            <i class="fas fa-history text-info"></i>
                        </div>
                        <div>
                            <h5 class="card-title">RECENT APPOINTMENTS</h5>
                            <p class="card-subtitle">Latest appointment activity</p>
                        </div>
                    </div>
                    <a href="{{ route('doctor.appointments.index') }}" class="btn-link">
                        View All <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentAppointments->count() > 0)
                        <div class="table-responsive">
                            <table class="dashboard-table">
                                <thead>
                                    <tr>
                                        <th>Patient</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentAppointments as $appointment)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="table-avatar">
                                                    {{ substr($appointment->patient->name, 0, 1) }}
                                                </div>
                                                <span class="fw-medium">{{ $appointment->patient->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                        <td>
                                            @if($appointment->status == 'confirmed')
                                                <span class="badge-light badge-confirmed">Confirmed</span>
                                            @elseif($appointment->status == 'pending')
                                                <span class="badge-light badge-pending">Pending</span>
                                            @elseif($appointment->status == 'completed')
                                                <span class="badge-light badge-completed">Completed</span>
                                            @else
                                                <span class="badge-light badge-cancelled">Cancelled</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('doctor.appointments.show', $appointment) }}" class="table-action">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <h6>No Recent Appointments</h6>
                            <p class="text-muted">Your recent appointments will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Weekly Overview & Patient Distribution -->
            <div class="row g-4 mt-2">
                <div class="col-md-6">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div class="header-icon bg-success-soft">
                                    <i class="fas fa-chart-line text-success"></i>
                                </div>
                                <div>
                                    <h5 class="card-title">WEEKLY OVERVIEW</h5>
                                    <p class="card-subtitle">Last 7 days</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @php
                                $weeklyStats = [];
                                for ($i = 6; $i >= 0; $i--) {
                                    $date = now()->subDays($i);
                                    $count = App\Models\Appointment::where('doctor_id', Auth::user()->doctor->id)
                                        ->whereDate('appointment_date', $date)
                                        ->count();
                                    $weeklyStats[$date->format('D')] = $count;
                                }
                            @endphp
                            <div class="weekly-chart">
                                @foreach($weeklyStats as $day => $count)
                                    <div class="chart-bar">
                                        <span class="bar-label">{{ $day }}</span>
                                        <div class="bar-container">
                                            <div class="bar-fill" style="height: {{ $count * 8 }}px; width: 100%;"></div>
                                        </div>
                                        <span class="bar-value">{{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="dashboard-card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <div class="header-icon bg-warning-soft">
                                    <i class="fas fa-pie-chart text-warning"></i>
                                </div>
                                <div>
                                    <h5 class="card-title">APPOINTMENT STATUS</h5>
                                    <p class="card-subtitle">Current distribution</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            @php
                                $total = $todayAppointments + $upcomingAppointments + $completedAppointments;
                                $confirmedCount = App\Models\Appointment::where('doctor_id', Auth::user()->doctor->id)
                                    ->where('status', 'confirmed')
                                    ->count();
                                $pendingCount = App\Models\Appointment::where('doctor_id', Auth::user()->doctor->id)
                                    ->where('status', 'pending')
                                    ->count();
                                $completedCount = App\Models\Appointment::where('doctor_id', Auth::user()->doctor->id)
                                    ->where('status', 'completed')
                                    ->count();
                            @endphp
                            <div class="status-distribution">
                                <div class="distribution-item">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><span class="status-dot confirmed"></span> Confirmed</span>
                                        <span class="fw-bold">{{ $confirmedCount }}</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill confirmed" style="width: {{ $total > 0 ? ($confirmedCount / $total) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                                <div class="distribution-item">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><span class="status-dot pending"></span> Pending</span>
                                        <span class="fw-bold">{{ $pendingCount }}</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill pending" style="width: {{ $total > 0 ? ($pendingCount / $total) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                                <div class="distribution-item">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span><span class="status-dot completed"></span> Completed</span>
                                        <span class="fw-bold">{{ $completedCount }}</span>
                                    </div>
                                    <div class="progress-bar">
                                        <div class="progress-fill completed" style="width: {{ $total > 0 ? ($completedCount / $total) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
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
        border-radius: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }

    .bg-primary-soft { background: #e8f0fe; }
    .bg-secondary-soft { background: #f5f5f5; }
    .bg-info-soft { background: #e1f5fe; }
    .bg-success-soft { background: #e8f5e9; }
    .bg-warning-soft { background: #fff3e0; }

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

    .card-footer {
        padding: 1rem 1.5rem;
        background: #f8f8f8;
        border-top: 1px solid #eaeaea;
    }

    .dark-theme .card-footer {
        background: #2a2a2a;
        border-top-color: #3a3a3a;
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

    /* Schedule List */
    .schedule-list {
        padding: 0.5rem 0;
    }

    .schedule-item {
        display: flex;
        align-items: center;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #eaeaea;
        transition: all 0.3s ease;
    }

    .dark-theme .schedule-item {
        border-bottom-color: #2a2a2a;
    }

    .schedule-item:hover {
        background: #f8f8f8;
    }

    .dark-theme .schedule-item:hover {
        background: #2a2a2a;
    }

    .schedule-time {
        min-width: 100px;
    }

    .schedule-time .time {
        display: block;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .schedule-time .duration {
        display: block;
        font-size: 0.7rem;
        color: #999;
    }

    .schedule-patient {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex: 1;
    }

    .patient-avatar {
        width: 35px;
        height: 35px;
        background: #f0f0f0;
        border: 1px solid #eaeaea;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .schedule-item:hover .patient-avatar {
        background: #111;
        color: #fff;
    }

    .patient-info {
        line-height: 1.3;
    }

    .patient-name {
        display: block;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .patient-contact {
        display: block;
        font-size: 0.7rem;
        color: #999;
    }

    .schedule-status {
        margin: 0 1rem;
    }

    .schedule-action {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #999;
        transition: all 0.3s ease;
    }

    .schedule-action:hover {
        color: #111;
        transform: translateX(5px);
    }

    /* Quick Actions Grid */
    .quick-actions-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .quick-action-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1.5rem 1rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        text-decoration: none;
        color: #111;
        transition: all 0.3s ease;
    }

    .dark-theme .quick-action-item {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #fff;
    }

    .quick-action-item:hover {
        background: #111;
        color: #fff;
        transform: translateY(-3px);
    }

    .quick-action-item i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .quick-action-item span {
        font-size: 0.8rem;
        font-weight: 500;
        text-align: center;
    }

    /* Dashboard Table */
    .dashboard-table {
        width: 100%;
    }

    .dashboard-table th {
        padding: 1rem 1.5rem;
        font-size: 0.7rem;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #eaeaea;
    }

    .dark-theme .dashboard-table th {
        color: #999;
        border-bottom-color: #2a2a2a;
    }

    .dashboard-table td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #eaeaea;
        font-size: 0.9rem;
    }

    .dark-theme .dashboard-table td {
        border-bottom-color: #2a2a2a;
    }

    .dashboard-table tr:hover td {
        background: #f8f8f8;
    }

    .dark-theme .dashboard-table tr:hover td {
        background: #2a2a2a;
    }

    .table-avatar {
        width: 30px;
        height: 30px;
        background: #f0f0f0;
        border: 1px solid #eaeaea;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.8rem;
        margin-right: 0.75rem;
    }

    .table-action {
        color: #999;
        transition: all 0.3s ease;
    }

    .table-action:hover {
        color: #111;
    }

    /* Badge Styles */
    .badge-light {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 500;
        border: 1px solid transparent;
    }

    .badge-confirmed {
        background: #e8f5e9;
        color: #2e7d32;
        border-color: #c8e6c9;
    }

    .badge-pending {
        background: #fff3e0;
        color: #ed6c02;
        border-color: #ffe0b2;
    }

    .badge-completed {
        background: #e1f5fe;
        color: #0288d1;
        border-color: #b3e5fc;
    }

    .badge-cancelled {
        background: #ffebee;
        color: #c62828;
        border-color: #ffcdd2;
    }

    .dark-theme .badge-confirmed {
        background: #1e4a2a;
        color: #9bdf9b;
        border-color: #2d6a3d;
    }

    .dark-theme .badge-pending {
        background: #665c1c;
        color: #ffd966;
        border-color: #857b3c;
    }

    .dark-theme .badge-completed {
        background: #1e5460;
        color: #9fd9e6;
        border-color: #2d6f7a;
    }

    .dark-theme .badge-cancelled {
        background: #64262e;
        color: #f4acb7;
        border-color: #853a44;
    }

    /* Status Badges (for schedule items) */
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        font-size: 0.65rem;
        font-weight: 600;
        border: 1px solid transparent;
    }

    .status-confirmed {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .status-pending {
        background: #fff3e0;
        color: #ed6c02;
    }

    .status-completed {
        background: #e1f5fe;
        color: #0288d1;
    }

    .status-cancelled {
        background: #ffebee;
        color: #c62828;
    }

    .dark-theme .status-confirmed {
        background: #1e4a2a;
        color: #9bdf9b;
    }

    .dark-theme .status-pending {
        background: #665c1c;
        color: #ffd966;
    }

    .dark-theme .status-completed {
        background: #1e5460;
        color: #9fd9e6;
    }

    .dark-theme .status-cancelled {
        background: #64262e;
        color: #f4acb7;
    }

    /* Weekly Chart */
    .weekly-chart {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        height: 200px;
        padding: 1rem 0;
    }

    .chart-bar {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }

    .bar-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: #666;
        margin-bottom: 0.5rem;
    }

    .dark-theme .bar-label {
        color: #999;
    }

    .bar-container {
        width: 100%;
        max-width: 30px;
        height: 100px;
        background: #f0f0f0;
        position: relative;
        margin-bottom: 0.5rem;
    }

    .dark-theme .bar-container {
        background: #2a2a2a;
    }

    .bar-fill {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #111;
        transition: height 0.3s ease;
    }

    .dark-theme .bar-fill {
        background: #fff;
    }

    .bar-value {
        font-size: 0.8rem;
        font-weight: 600;
    }

    /* Status Distribution */
    .status-distribution {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .distribution-item {
        margin-bottom: 0.5rem;
    }

    .status-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 0.5rem;
    }

    .status-dot.confirmed { background: #2e7d32; }
    .status-dot.pending { background: #ed6c02; }
    .status-dot.completed { background: #0288d1; }

    .progress-bar {
        height: 6px;
        background: #f0f0f0;
        overflow: hidden;
    }

    .dark-theme .progress-bar {
        background: #2a2a2a;
    }

    .progress-fill {
        height: 100%;
        transition: width 0.3s ease;
    }

    .progress-fill.confirmed { background: #2e7d32; }
    .progress-fill.pending { background: #ed6c02; }
    .progress-fill.completed { background: #0288d1; }

    .dark-theme .progress-fill.confirmed { background: #9bdf9b; }
    .dark-theme .progress-fill.pending { background: #ffd966; }
    .dark-theme .progress-fill.completed { background: #9fd9e6; }

    /* Empty State */
    .empty-state {
        padding: 3rem;
        text-align: center;
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

    /* Responsive */
    @media (max-width: 768px) {
        .schedule-item {
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .schedule-patient {
            width: 100%;
        }

        .quick-actions-grid {
            grid-template-columns: 1fr;
        }

        .weekly-chart {
            height: 150px;
        }
    }
</style>
@endpush