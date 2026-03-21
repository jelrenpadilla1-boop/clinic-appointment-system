{{-- resources/views/admin/patients/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">PATIENT MANAGEMENT</h2>
            <p class="text-muted mb-0">Manage all patients in the system</p>
        </div>
        <div class="d-flex gap-2">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="patientSearch" class="form-control" placeholder="Search patients...">
            </div>
            <button class="btn-filter" onclick="window.location.reload()">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TOTAL PATIENTS</span>
                    <span class="stat-value">{{ $patients->total() }}</span>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-user-plus"></i> Active
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
                    <span class="stat-value">{{ \App\Models\Appointment::whereDate('created_at', today())->count() }}</span>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-clock"></i> Today
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">ACTIVE TODAY</span>
                    <span class="stat-value">{{ \App\Models\Appointment::whereDate('appointment_date', today())->distinct('patient_id')->count('patient_id') }}</span>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-arrow-up"></i> Active
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">NEW THIS MONTH</span>
                    <span class="stat-value">{{ \App\Models\User::where('role', 'patient')->whereMonth('created_at', now()->month)->count() }}</span>
                </div>
                <div class="stat-trend">
                    <i class="fas fa-calendar-alt"></i> {{ now()->format('F') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Patients Table -->
    <div class="table-card light">
        <div class="table-header">
            <h5 class="table-title">ALL PATIENTS</h5>
            <div class="table-actions">
                <span class="badge bg-light text-dark me-2">Total: {{ $patients->total() }}</span>
                <a href="#" class="export-link" onclick="exportToCSV()">
                    <i class="fas fa-download me-1"></i> Export
                </a>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="admin-table" id="patientsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>PATIENT</th>
                        <th>CONTACT</th>
                        <th>ADDRESS</th>
                        <th>REGISTERED</th>
                        <th>APPOINTMENTS</th>
                        <th>STATUS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr class="patient-row" data-patient-id="{{ $patient->id }}">
                        <td>#{{ $patient->id }}</td>
                        <td>
                            <div class="patient-info">
                                <div class="patient-avatar" data-initial="{{ substr($patient->name, 0, 1) }}">
                                    {{ substr($patient->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="patient-name">{{ $patient->name }}</div>
                                    <div class="patient-email">{{ $patient->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="contact-info">
                                <div><i class="fas fa-phone-alt me-1"></i> {{ $patient->phone ?? 'N/A' }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="address-text" title="{{ $patient->address }}">
                                {{ Str::limit($patient->address, 30) ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <div class="date-info">
                                <div>{{ $patient->created_at->format('M d, Y') }}</div>
                                <small class="text-muted">{{ $patient->created_at->diffForHumans() }}</small>
                            </div>
                        </td>
                        <td>
                            @php
                                $appointmentCount = \App\Models\Appointment::where('patient_id', $patient->id)->count();
                                $lastAppointment = \App\Models\Appointment::where('patient_id', $patient->id)
                                    ->latest('appointment_date')
                                    ->first();
                            @endphp
                            <div class="appointment-stats">
                                <span class="badge bg-light text-dark">{{ $appointmentCount }} total</span>
                                @if($lastAppointment)
                                    <small class="text-muted d-block mt-1">Last: {{ $lastAppointment->appointment_date->format('M d') }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            @if($patient->is_active ?? true)
                                <span class="status-badge status-active">
                                    <i class="fas fa-circle me-1"></i> Active
                                </span>
                            @else
                                <span class="status-badge status-inactive">
                                    <i class="fas fa-circle me-1"></i> Inactive
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('admin.patients.show', $patient) }}" class="action-btn" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.patients.toggle-status', $patient) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="action-btn {{ ($patient->is_active ?? true) ? 'text-warning' : 'text-success' }}" 
                                            title="{{ ($patient->is_active ?? true) ? 'Deactivate' : 'Activate' }}"
                                            onclick="return confirm('Are you sure you want to {{ ($patient->is_active ?? true) ? 'deactivate' : 'activate' }} this patient?')">
                                        <i class="fas fa-{{ ($patient->is_active ?? true) ? 'ban' : 'check' }}"></i>
                                    </button>
                                </form>
                                <form action="{{ route('admin.patients.destroy', $patient) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn text-danger" 
                                            title="Delete Patient"
                                            onclick="return confirm('Are you sure you want to delete this patient? This action cannot be undone.')">
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
                                <i class="fas fa-users fa-4x mb-3 opacity-50"></i>
                                <h5>No Patients Found</h5>
                                <p class="text-muted">There are no patients registered in the system yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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

    /* Filter Button */
    .btn-filter {
        width: 45px;
        height: 45px;
        background: #fff;
        border: 1px solid #eaeaea;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .dark-theme .btn-filter {
        background: #1a1a1a;
        border-color: #2a2a2a;
        color: #fff;
    }

    .btn-filter:hover {
        background: #111;
        color: #fff;
        transform: rotate(180deg);
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

    /* Patient Info */
    .patient-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .patient-avatar {
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

    .dark-theme .patient-avatar {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .patient-avatar::before {
        content: '';
        position: absolute;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: #111;
        transition: all 0.3s ease;
        z-index: -1;
    }

    tr:hover .patient-avatar {
        color: #fff;
        transform: scale(1.1);
    }

    tr:hover .patient-avatar::before {
        width: 100%;
        height: 100%;
        background: #111;
    }

    .dark-theme tr:hover .patient-avatar::before {
        background: #fff;
    }

    .dark-theme tr:hover .patient-avatar {
        color: #111;
    }

    .patient-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .patient-email {
        font-size: 0.8rem;
        color: #666;
    }

    .dark-theme .patient-email {
        color: #999;
    }

    /* Contact Info */
    .contact-info {
        font-size: 0.85rem;
    }

    .contact-info i {
        width: 16px;
        color: #999;
    }

    /* Address */
    .address-text {
        font-size: 0.85rem;
        cursor: help;
        border-bottom: 1px dashed #ccc;
    }

    /* Date Info */
    .date-info {
        font-size: 0.85rem;
    }

    .date-info small {
        font-size: 0.7rem;
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

    .status-inactive {
        background: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
    }

    .dark-theme .status-active {
        background: #1e4a2a;
        color: #9bdf9b;
        border-color: #2d6a3d;
    }

    .dark-theme .status-inactive {
        background: #64262e;
        color: #f4acb7;
        border-color: #853a44;
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

    /* Pagination */
    .pagination-wrapper {
        display: flex;
        justify-content: flex-end;
    }

    .pagination-wrapper .pagination {
        margin: 0;
    }

    .pagination-wrapper .page-link {
        border: 1px solid #eaeaea;
        color: #111;
        transition: all 0.3s ease;
    }

    .dark-theme .pagination-wrapper .page-link {
        background: #1a1a1a;
        border-color: #2a2a2a;
        color: #fff;
    }

    .pagination-wrapper .page-link:hover {
        background: #111;
        color: #fff;
        transform: scale(1.05);
    }

    .pagination-wrapper .active .page-link {
        background: #111;
        border-color: #111;
        color: #fff;
    }

    .dark-theme .pagination-wrapper .active .page-link {
        background: #fff;
        border-color: #fff;
        color: #111;
    }
</style>
@endpush

@push('scripts')
<script>
    // Live search functionality
    document.getElementById('patientSearch').addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('.patient-row');
        
        rows.forEach(row => {
            const patientName = row.querySelector('.patient-name')?.textContent.toLowerCase() || '';
            const patientEmail = row.querySelector('.patient-email')?.textContent.toLowerCase() || '';
            const patientId = row.dataset.patientId || '';
            
            if (patientName.includes(searchValue) || patientEmail.includes(searchValue) || patientId.includes(searchValue)) {
                row.style.display = '';
                row.style.animation = 'fadeIn 0.3s ease';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Export to CSV function
    function exportToCSV() {
        const rows = document.querySelectorAll('.admin-table tbody tr:not([style*="display: none"])');
        const data = [];
        
        // Headers
        data.push(['ID', 'Name', 'Email', 'Phone', 'Address', 'Registered', 'Appointments', 'Status']);
        
        // Data rows
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const rowData = [
                    row.cells[0]?.textContent.trim() || '',
                    row.querySelector('.patient-name')?.textContent.trim() || '',
                    row.querySelector('.patient-email')?.textContent.trim() || '',
                    row.querySelector('.contact-info')?.textContent.replace(/\s+/g, ' ').trim() || '',
                    row.querySelector('.address-text')?.textContent.trim() || '',
                    row.querySelector('.date-info')?.textContent.replace(/\s+/g, ' ').trim() || '',
                    row.querySelector('.appointment-stats .badge')?.textContent.trim() || '',
                    row.querySelector('.status-badge')?.textContent.trim() || ''
                ];
                data.push(rowData);
            }
        });
        
        // Convert to CSV
        const csv = data.map(row => row.map(cell => `"${cell}"`).join(',')).join('\n');
        
        // Download
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'patients_export.csv';
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