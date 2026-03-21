{{-- resources/views/admin/doctors/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">DOCTORS MANAGEMENT</h2>
            <p class="text-muted mb-0">Manage all doctors in the system</p>
        </div>
        <div class="d-flex gap-2">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="doctorSearch" class="form-control" placeholder="Search doctors...">
            </div>
            <a href="{{ route('admin.doctors.create') }}" class="btn-add">
                <i class="fas fa-plus me-2"></i>ADD NEW DOCTOR
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-user-md"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TOTAL DOCTORS</span>
                    <span class="stat-value">{{ $doctors->count() }}</span>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i> Active
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-stethoscope"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">SPECIALIZATIONS</span>
                    <span class="stat-value">{{ \App\Models\Specialization::count() }}</span>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-tag"></i> Available
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TODAY'S APPOINTMENTS</span>
                    <span class="stat-value">{{ \App\Models\Appointment::whereDate('appointment_date', today())->count() }}</span>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-clock"></i> Scheduled
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">ON LEAVE</span>
                    <span class="stat-value">0</span>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-user-clock"></i> Today
                </div>
            </div>
        </div>
    </div>

    <!-- Doctors Table -->
    <div class="table-card light">
        <div class="table-header">
            <h5 class="table-title">ALL DOCTORS</h5>
            <div class="table-actions">
                <span class="badge bg-light text-dark me-2">Total: {{ $doctors->count() }}</span>
                <a href="#" class="export-link" onclick="exportToCSV()">
                    <i class="fas fa-download me-1"></i> Export
                </a>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="admin-table" id="doctorsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>DOCTOR</th>
                        <th>SPECIALIZATION</th>
                        <th>LICENSE</th>
                        <th>CONTACT</th>
                        <th>APPOINTMENTS</th>
                        <th>STATUS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($doctors as $doctor)
                    <tr class="doctor-row" data-doctor-id="{{ $doctor->id }}">
                        <td>#{{ $doctor->id }}</td>
                        <td>
                            <div class="doctor-info">
                                <div class="doctor-avatar" data-initial="{{ substr($doctor->user->name, 0, 1) }}">
                                    {{ substr($doctor->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="doctor-name">Dr. {{ $doctor->user->name }}</div>
                                    <div class="doctor-email">{{ $doctor->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="specialization-badge">
                                <i class="fas fa-stethoscope me-1"></i>
                                {{ $doctor->specialization->name }}
                            </span>
                        </td>
                        <td>
                            <span class="license-text">{{ $doctor->license_number }}</span>
                        </td>
                        <td>
                            <div class="contact-info">
                                <div><i class="fas fa-phone-alt"></i> {{ $doctor->user->phone }}</div>
                            </div>
                        </td>
                        <td>
                            @php
                                $appointmentCount = \App\Models\Appointment::where('doctor_id', $doctor->id)->count();
                                $todayAppointments = \App\Models\Appointment::where('doctor_id', $doctor->id)
                                    ->whereDate('appointment_date', today())
                                    ->count();
                            @endphp
                            <div class="appointment-stats">
                                <span class="badge bg-light text-dark">{{ $appointmentCount }} total</span>
                                @if($todayAppointments > 0)
                                    <small class="text-muted d-block mt-1">{{ $todayAppointments }} today</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-active">
                                <i class="fas fa-circle me-1"></i> Active
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.doctors.show', $doctor) }}" class="action-btn" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.doctors.edit', $doctor) }}" class="action-btn" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.schedules.create', ['doctor_id' => $doctor->id]) }}" class="action-btn" title="Schedule">
                                    <i class="fas fa-clock"></i>
                                </a>
                                <form action="{{ route('admin.doctors.destroy', $doctor) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn text-danger" title="Delete"
                                            onclick="return confirm('Are you sure you want to delete Dr. {{ $doctor->user->name }}? This action cannot be undone.')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-user-md fa-4x mb-3 opacity-50"></i>
                                <h5>No Doctors Found</h5>
                                <p class="text-muted">There are no doctors registered in the system yet.</p>
                                <a href="{{ route('admin.doctors.create') }}" class="btn-add mt-3">
                                    <i class="fas fa-plus me-2"></i>ADD YOUR FIRST DOCTOR
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Search Box */
    .search-box {
        position: relative;
        width: 300px;
    }

    .search-box .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 0.9rem;
        z-index: 1;
    }

    .search-box input {
        padding-left: 35px;
        border: 1px solid #eaeaea;
        border-radius: 0;
        height: 45px;
        font-size: 0.9rem;
    }

    .dark-theme .search-box input {
        background: #1a1a1a;
        border-color: #2a2a2a;
        color: #fff;
    }

    .search-box input:focus {
        outline: none;
        border-color: #111;
        box-shadow: none;
    }

    /* Add Button */
    .btn-add {
        padding: 10px 20px;
        background: #111;
        border: 1px solid #111;
        color: #fff;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }

    .dark-theme .btn-add {
        background: #fff;
        border-color: #fff;
        color: #111;
    }

    .btn-add:hover {
        background: #fff;
        color: #111;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .dark-theme .btn-add:hover {
        background: #111;
        color: #fff;
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.1);
    }

    /* Stat Cards */
    .stat-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        padding: 1.5rem;
        position: relative;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .dark-theme .stat-card {
        background: #1a1a1a;
        border-color: #2a2a2a;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .dark-theme .stat-card:hover {
        box-shadow: 0 10px 25px -5px rgba(255, 255, 255, 0.1);
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: #111;
        transition: all 0.3s ease;
    }

    .dark-theme .stat-icon {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #fff;
    }

    .stat-card:hover .stat-icon {
        transform: rotate(360deg);
        background: #111;
        color: #fff;
    }

    .dark-theme .stat-card:hover .stat-icon {
        background: #fff;
        color: #111;
    }

    .stat-content {
        flex: 1;
    }

    .stat-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        color: #666;
        margin-bottom: 0.25rem;
    }

    .dark-theme .stat-label {
        color: #999;
    }

    .stat-value {
        display: block;
        font-size: 1.75rem;
        font-weight: 700;
        color: #111;
        line-height: 1;
    }

    .dark-theme .stat-value {
        color: #fff;
    }

    .stat-trend {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        color: #666;
    }

    .dark-theme .stat-trend {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #999;
    }

    /* Table Card */
    .table-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        padding: 1.5rem;
    }

    .dark-theme .table-card {
        background: #1a1a1a;
        border-color: #2a2a2a;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .table-title {
        font-size: 0.9rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin: 0;
    }

    .table-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .export-link {
        text-decoration: none;
        color: inherit;
        font-size: 0.8rem;
        opacity: 0.7;
        transition: all 0.3s ease;
    }

    .export-link:hover {
        opacity: 1;
        transform: translateX(5px);
    }

    /* Admin Table */
    .admin-table {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-table th {
        text-align: left;
        padding: 1rem;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        color: #666;
        border-bottom: 2px solid #eaeaea;
    }

    .dark-theme .admin-table th {
        color: #999;
        border-bottom-color: #2a2a2a;
    }

    .admin-table td {
        padding: 1rem;
        border-bottom: 1px solid #eaeaea;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .dark-theme .admin-table td {
        border-bottom-color: #2a2a2a;
    }

    .admin-table tbody tr {
        transition: all 0.3s ease;
    }

    .admin-table tbody tr:hover {
        background: #f8f8f8;
    }

    .dark-theme .admin-table tbody tr:hover {
        background: #2a2a2a;
    }

    /* Doctor Info */
    .doctor-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .doctor-avatar {
        width: 40px;
        height: 40px;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
    }

    .dark-theme .doctor-avatar {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    tr:hover .doctor-avatar {
        transform: scale(1.1);
        background: #111;
        color: #fff;
    }

    .dark-theme tr:hover .doctor-avatar {
        background: #fff;
        color: #111;
    }

    .doctor-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .doctor-email {
        font-size: 0.8rem;
        color: #666;
    }

    .dark-theme .doctor-email {
        color: #999;
    }

    /* Specialization Badge */
    .specialization-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }

    .dark-theme .specialization-badge {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    tr:hover .specialization-badge {
        background: #111;
        color: #fff;
    }

    .dark-theme tr:hover .specialization-badge {
        background: #fff;
        color: #111;
    }

    /* License Text */
    .license-text {
        font-family: monospace;
        font-size: 0.85rem;
        padding: 0.25rem 0.5rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
    }

    .dark-theme .license-text {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    /* Contact Info */
    .contact-info {
        font-size: 0.85rem;
    }

    .contact-info i {
        width: 16px;
        color: #999;
    }

    /* Appointment Stats */
    .appointment-stats {
        font-size: 0.85rem;
    }

    .appointment-stats .badge {
        font-size: 0.7rem;
        font-weight: normal;
        padding: 0.25rem 0.5rem;
        border: 1px solid #eaeaea;
    }

    .dark-theme .appointment-stats .badge {
        background: #2a2a2a !important;
        color: #fff !important;
        border-color: #3a3a3a;
    }

    /* Status Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 500;
        border: 1px solid transparent;
        transition: all 0.3s ease;
    }

    .status-badge i {
        font-size: 0.5rem;
    }

    .status-badge:hover {
        transform: scale(1.05);
    }

    .status-active {
        background: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
    }

    .dark-theme .status-active {
        background: #1e4a2a;
        color: #9bdf9b;
        border-color: #2d6a3d;
    }

    /* Action Buttons */
    .action-buttons {
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

    /* Empty State */
    .empty-state {
        padding: 3rem;
        text-align: center;
    }

    .empty-state i {
        color: #999;
    }

    .empty-state .btn-add {
        display: inline-flex;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .d-flex {
            flex-direction: column;
            gap: 1rem;
        }

        .search-box {
            width: 100%;
        }

        .btn-add {
            width: 100%;
            justify-content: center;
        }

        .table-header {
            flex-direction: column;
            gap: 1rem;
        }

        .action-buttons {
            flex-wrap: wrap;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Live search functionality
    document.getElementById('doctorSearch').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('.doctor-row');
        
        rows.forEach(row => {
            const doctorName = row.querySelector('.doctor-name')?.textContent.toLowerCase() || '';
            const doctorEmail = row.querySelector('.doctor-email')?.textContent.toLowerCase() || '';
            const specialization = row.querySelector('.specialization-badge')?.textContent.toLowerCase() || '';
            const license = row.querySelector('.license-text')?.textContent.toLowerCase() || '';
            
            if (doctorName.includes(searchValue) || 
                doctorEmail.includes(searchValue) || 
                specialization.includes(searchValue) || 
                license.includes(searchValue)) {
                row.style.display = '';
                row.style.animation = 'fadeIn 0.3s ease';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Export to CSV function
    function exportToCSV() {
        const rows = document.querySelectorAll('.doctor-row');
        const data = [];
        
        // Headers
        data.push(['ID', 'Name', 'Email', 'Specialization', 'License', 'Phone', 'Total Appointments']);
        
        // Data rows
        rows.forEach(row => {
            const rowData = [
                row.cells[0]?.textContent.trim() || '',
                row.querySelector('.doctor-name')?.textContent.trim() || '',
                row.querySelector('.doctor-email')?.textContent.trim() || '',
                row.querySelector('.specialization-badge')?.textContent.trim() || '',
                row.querySelector('.license-text')?.textContent.trim() || '',
                row.querySelector('.contact-info')?.textContent.replace(/\s+/g, ' ').trim() || '',
                row.querySelector('.appointment-stats .badge')?.textContent.trim() || ''
            ];
            data.push(rowData);
        });
        
        // Convert to CSV
        const csv = data.map(row => row.map(cell => `"${cell}"`).join(',')).join('\n');
        
        // Download
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'doctors_export.csv';
        a.click();
    }

    // Add animation keyframes
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
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