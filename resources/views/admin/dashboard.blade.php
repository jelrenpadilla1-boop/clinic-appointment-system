{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="py-4">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">DASHBOARD</h2>
            <p class="text-muted mb-0">Welcome back, <span class="fw-semibold">{{ Auth::user()->name }}</span></p>
        </div>
        <div class="header-date">
            <i class="fas fa-calendar-alt me-2 text-muted"></i>
            <span class="fw-medium">{{ now()->format('l, F d, Y') }}</span>
        </div>
    </div>

    <!-- Stats Cards (Clickable) -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <a href="{{ route('admin.doctors.index') }}" class="text-decoration-none">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #e8f0fe;">
                        <i class="fas fa-user-md" style="color: #1976d2;"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">TOTAL DOCTORS</span>
                        <span class="stat-value">{{ $totalDoctors }}</span>
                    </div>
                    <div class="stat-hover-indicator">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-3">
            <a href="{{ route('admin.patients.index') }}" class="text-decoration-none">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #e8f5e9;">
                        <i class="fas fa-users" style="color: #2e7d32;"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">TOTAL PATIENTS</span>
                        <span class="stat-value">{{ $totalPatients }}</span>
                    </div>
                    <div class="stat-hover-indicator">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-3">
            <a href="{{ route('admin.appointments.index') }}" class="text-decoration-none">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #fff3e0;">
                        <i class="fas fa-calendar-check" style="color: #ed6c02;"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">TOTAL APPOINTMENTS</span>
                        <span class="stat-value">{{ $totalAppointments }}</span>
                    </div>
                    <div class="stat-hover-indicator">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-md-3">
            <a href="{{ route('admin.appointments.index', ['date' => now()->format('Y-m-d')]) }}" class="text-decoration-none">
                <div class="stat-card">
                    <div class="stat-icon" style="background: #f3e5f5;">
                        <i class="fas fa-calendar-day" style="color: #7b1fa2;"></i>
                    </div>
                    <div class="stat-content">
                        <span class="stat-label">TODAY'S APPOINTMENTS</span>
                        <span class="stat-value">{{ $todayAppointments }}</span>
                    </div>
                    <div class="stat-hover-indicator">
                        <i class="fas fa-arrow-right"></i>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions-section mb-4">
        <h6 class="section-label mb-3">QUICK ACTIONS</h6>
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('admin.doctors.create') }}" class="quick-action-card">
                    <i class="fas fa-user-plus"></i>
                    <span>Add Doctor</span>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.appointments.index') }}" class="quick-action-card">
                    <i class="fas fa-calendar-check"></i>
                    <span>Appointments</span>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.reports.index') }}" class="quick-action-card">
                    <i class="fas fa-chart-line"></i>
                    <span>Reports</span>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.schedules.index') }}" class="quick-action-card">
                    <i class="fas fa-clock"></i>
                    <span>Schedules</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="chart-card">
                <div class="chart-card-header">
                    <div>
                        <h5 class="chart-card-title">APPOINTMENT OVERVIEW</h5>
                        <p class="chart-card-subtitle">Last 7 days activity</p>
                    </div>
                    <div class="chart-actions">
                        <span class="badge bg-light text-dark">This Week</span>
                    </div>
                </div>
                <div class="chart-card-body">
                    <canvas id="appointmentsChart" height="80"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="chart-card">
                <div class="chart-card-header">
                    <div>
                        <h5 class="chart-card-title">STATUS DISTRIBUTION</h5>
                        <p class="chart-card-subtitle">Current appointments</p>
                    </div>
                </div>
                <div class="chart-card-body text-center">
                    <canvas id="statusChart" height="140"></canvas>
                    <div class="legend-grid mt-3">
                        <div class="legend-item">
                            <span class="legend-dot pending"></span>
                            <span>Pending</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot confirmed"></span>
                            <span>Confirmed</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot completed"></span>
                            <span>Completed</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-dot cancelled"></span>
                            <span>Cancelled</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="table-card">
                <div class="table-card-header">
                    <div>
                        <h5 class="table-card-title">RECENT APPOINTMENTS</h5>
                        <p class="table-card-subtitle">Latest 5 appointments</p>
                    </div>
                    <a href="{{ route('admin.appointments.index') }}" class="view-link">
                        View All <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>PATIENT</th>
                                <th>DOCTOR</th>
                                <th>DATE</th>
                                <th>TIME</th>
                                <th>STATUS</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(App\Models\Appointment::with(['patient', 'doctor.user'])->latest()->take(5)->get() as $appointment)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="table-avatar">{{ substr($appointment->patient->name, 0, 1) }}</span>
                                        <span class="ms-2">{{ $appointment->patient->name }}</span>
                                    </div>
                                </td>
                                <td>Dr. {{ $appointment->doctor->user->name }}</td>
                                <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                                <td>{{ $appointment->appointment_time->format('h:i A') }}</td>
                                <td>
                                    <span class="status-badge status-{{ $appointment->status }}">
                                        {{ ucfirst($appointment->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.appointments.show', $appointment) }}" class="table-action-btn">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="col-lg-5">
            <div class="table-card">
                <div class="table-card-header">
                    <div>
                        <h5 class="table-card-title">TOP DOCTORS</h5>
                        <p class="table-card-subtitle">By appointment count</p>
                    </div>
                    <a href="{{ route('admin.doctors.index') }}" class="view-link">
                        View All <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
                <div class="doctor-list">
                    @foreach(App\Models\Doctor::with('user')->withCount('appointments')->orderBy('appointments_count', 'desc')->take(5)->get() as $doctor)
                    <a href="{{ route('admin.doctors.show', $doctor) }}" class="text-decoration-none doctor-list-link">
                        <div class="doctor-list-item">
                            <div class="doctor-info">
                                <span class="doctor-avatar">{{ substr($doctor->user->name, 0, 1) }}</span>
                                <div>
                                    <h6 class="doctor-name">Dr. {{ $doctor->user->name }}</h6>
                                    <span class="doctor-specialty">{{ $doctor->specialization->name }}</span>
                                </div>
                            </div>
                            <div class="doctor-stats">
                                <span class="stat-number">{{ $doctor->appointments_count }}</span>
                                <span class="stat-label">appts</span>
                            </div>
                            <div class="doctor-hover-indicator">
                                <i class="fas fa-chevron-right"></i>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Header Date */
    .header-date {
        padding: 0.5rem 1rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        font-size: 0.9rem;
    }

    .dark-theme .header-date {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #fff;
    }

    /* Section Label */
    .section-label {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        color: #666;
        margin: 0;
    }

    .dark-theme .section-label {
        color: #999;
    }

    /* Stat Cards - Clickable */
    .stat-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .dark-theme .stat-card {
        background: #1a1a1a;
        border-color: #2a2a2a;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        border-color: #111;
    }

    .dark-theme .stat-card:hover {
        box-shadow: 0 10px 25px -5px rgba(255, 255, 255, 0.1);
        border-color: #fff;
    }

    .stat-hover-indicator {
        position: absolute;
        right: -20px;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0;
        transition: all 0.3s ease;
        color: #111;
    }

    .dark-theme .stat-hover-indicator {
        color: #fff;
    }

    .stat-card:hover .stat-hover-indicator {
        right: 15px;
        opacity: 1;
    }

    .stat-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        transition: all 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1);
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
        letter-spacing: 0.3px;
    }

    .dark-theme .stat-label {
        color: #999;
    }

    .stat-value {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
        color: #111;
        transition: color 0.3s ease;
    }

    .dark-theme .stat-value {
        color: #fff;
    }

    .stat-card:hover .stat-value {
        color: #1976d2;
    }

    .dark-theme .stat-card:hover .stat-value {
        color: #90caf9;
    }

    /* Quick Actions */
    .quick-action-card {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        background: #ffffff;
        border: 1px solid #eaeaea;
        text-decoration: none;
        color: #111;
        transition: all 0.3s ease;
    }

    .dark-theme .quick-action-card {
        background: #1a1a1a;
        border-color: #2a2a2a;
        color: #fff;
    }

    .quick-action-card:hover {
        background: #f8f8f8;
        transform: translateX(5px);
        border-color: #111;
    }

    .dark-theme .quick-action-card:hover {
        background: #2a2a2a;
        border-color: #fff;
    }

    .quick-action-card i {
        font-size: 1rem;
        color: #666;
        transition: transform 0.3s ease;
    }

    .quick-action-card:hover i {
        transform: scale(1.2);
        color: #111;
    }

    .dark-theme .quick-action-card:hover i {
        color: #fff;
    }

    .quick-action-card span {
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Chart Cards */
    .chart-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        padding: 1.25rem;
    }

    .dark-theme .chart-card {
        background: #1a1a1a;
        border-color: #2a2a2a;
    }

    .chart-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .chart-card-title {
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0;
        letter-spacing: 0.3px;
    }

    .chart-card-subtitle {
        font-size: 0.7rem;
        color: #666;
        margin: 0.25rem 0 0 0;
    }

    .dark-theme .chart-card-subtitle {
        color: #999;
    }

    .chart-card-body {
        position: relative;
        height: 160px;
    }

    /* Legend Grid */
    .legend-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: center;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.75rem;
    }

    .legend-dot {
        width: 8px;
        height: 8px;
        border-radius: 0;
    }

    .legend-dot.pending { background: #ffc107; }
    .legend-dot.confirmed { background: #28a745; }
    .legend-dot.completed { background: #17a2b8; }
    .legend-dot.cancelled { background: #dc3545; }

    /* Table Cards */
    .table-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        padding: 1.25rem;
    }

    .dark-theme .table-card {
        background: #1a1a1a;
        border-color: #2a2a2a;
    }

    .table-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .table-card-title {
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0;
        letter-spacing: 0.3px;
    }

    .table-card-subtitle {
        font-size: 0.7rem;
        color: #666;
        margin: 0.25rem 0 0 0;
    }

    .dark-theme .table-card-subtitle {
        color: #999;
    }

    .view-link {
        text-decoration: none;
        color: #666;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }

    .view-link:hover {
        color: #111;
        transform: translateX(5px);
    }

    /* Dashboard Table */
    .dashboard-table {
        width: 100%;
    }

    .dashboard-table th {
        text-align: left;
        padding: 0.75rem;
        font-size: 0.65rem;
        font-weight: 600;
        color: #666;
        border-bottom: 1px solid #eaeaea;
        letter-spacing: 0.3px;
    }

    .dark-theme .dashboard-table th {
        color: #999;
        border-bottom-color: #2a2a2a;
    }

    .dashboard-table td {
        padding: 0.75rem;
        font-size: 0.85rem;
        border-bottom: 1px solid #eaeaea;
    }

    .dark-theme .dashboard-table td {
        border-bottom-color: #2a2a2a;
    }

    .dashboard-table tbody tr {
        transition: background 0.2s ease;
    }

    .dashboard-table tbody tr:hover {
        background: #f8f8f8;
    }

    .dark-theme .dashboard-table tbody tr:hover {
        background: #2a2a2a;
    }

    /* Table Avatar */
    .table-avatar {
        width: 28px;
        height: 28px;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .dark-theme .table-avatar {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        font-size: 0.65rem;
        font-weight: 600;
        letter-spacing: 0.3px;
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

    /* Table Action Button */
    .table-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        color: #666;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .dark-theme .table-action-btn {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #999;
    }

    .table-action-btn:hover {
        background: #111;
        color: #fff;
        transform: rotate(360deg);
    }

    /* Doctor List - Clickable */
    .doctor-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .doctor-list-link {
        text-decoration: none;
        color: inherit;
        display: block;
    }

    .doctor-list-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .dark-theme .doctor-list-item {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .doctor-list-item:hover {
        transform: translateX(5px);
        border-color: #111;
        background: #ffffff;
    }

    .dark-theme .doctor-list-item:hover {
        border-color: #fff;
        background: #1a1a1a;
    }

    .doctor-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .doctor-avatar {
        width: 36px;
        height: 36px;
        background: #ffffff;
        border: 1px solid #eaeaea;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .dark-theme .doctor-avatar {
        background: #1a1a1a;
        border-color: #4a4a4a;
    }

    .doctor-list-item:hover .doctor-avatar {
        transform: scale(1.1);
        background: #111;
        color: #fff;
    }

    .dark-theme .doctor-list-item:hover .doctor-avatar {
        background: #fff;
        color: #111;
    }

    .doctor-name {
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.2rem;
        color: #111;
    }

    .dark-theme .doctor-name {
        color: #fff;
    }

    .doctor-specialty {
        font-size: 0.7rem;
        color: #666;
    }

    .dark-theme .doctor-specialty {
        color: #999;
    }

    .doctor-stats {
        text-align: right;
        margin-right: 1.5rem;
    }

    .doctor-stats .stat-number {
        font-size: 1rem;
        font-weight: 700;
        display: block;
        line-height: 1.2;
        color: #111;
    }

    .dark-theme .doctor-stats .stat-number {
        color: #fff;
    }

    .doctor-stats .stat-label {
        font-size: 0.6rem;
        color: #666;
    }

    .doctor-hover-indicator {
        opacity: 0;
        transition: all 0.3s ease;
        color: #111;
    }

    .dark-theme .doctor-hover-indicator {
        color: #fff;
    }

    .doctor-list-item:hover .doctor-hover-indicator {
        opacity: 1;
        transform: translateX(3px);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .header-date {
            display: none;
        }

        .quick-action-card {
            padding: 0.5rem;
            justify-content: center;
        }

        .quick-action-card span {
            display: none;
        }

        .quick-action-card i {
            font-size: 1.2rem;
            margin: 0;
        }

        .stat-hover-indicator {
            display: none;
        }

        .doctor-hover-indicator {
            display: none;
        }

        .doctor-stats {
            margin-right: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Appointments Chart
    const ctx1 = document.getElementById('appointmentsChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                data: [12, 19, 15, 17, 14, 13, 9],
                borderColor: '#111',
                borderWidth: 2,
                backgroundColor: 'transparent',
                tension: 0.3,
                pointBackgroundColor: '#111',
                pointBorderColor: '#fff',
                pointBorderWidth: 1,
                pointRadius: 3,
                pointHoverRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { color: '#f0f0f0' },
                    ticks: { font: { size: 10 } }
                },
                x: { 
                    grid: { display: false },
                    ticks: { font: { size: 10 } }
                }
            }
        }
    });

    // Status Distribution Chart
    const ctx2 = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Confirmed', 'Completed', 'Cancelled'],
            datasets: [{
                data: [30, 45, 20, 5],
                backgroundColor: ['#ffc107', '#28a745', '#17a2b8', '#dc3545'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: { enabled: true }
            },
            cutout: '65%'
        }
    });
});
</script>
@endpush