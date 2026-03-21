{{-- resources/views/doctor/patients/index.blade.php --}}
@extends('layouts.doctor')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">MY PATIENTS</h2>
            <p class="text-muted mb-0">View and manage your patient list</p>
        </div>
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="patientSearch" class="form-control" placeholder="Search patients...">
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TOTAL PATIENTS</span>
                    <span class="stat-value">{{ $patients->total() }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TOTAL APPOINTMENTS</span>
                    <span class="stat-value">{{ $patients->sum('appointments_as_patient_count') }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">NEW THIS MONTH</span>
                    <span class="stat-value">{{ $patients->where('created_at', '>=', now()->startOfMonth())->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Patients Table -->
    <div class="table-card light">
        <div class="table-header">
            <h5 class="table-title">PATIENT LIST</h5>
            <div class="table-actions">
                <span class="badge bg-light text-dark me-2">Total: {{ $patients->total() }}</span>
                <a href="#" class="export-link" onclick="exportToCSV()">
                    <i class="fas fa-download me-1"></i> EXPORT
                </a>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="doctor-table" id="patientsTable">
                <thead>
                    <tr>
                        <th>PATIENT</th>
                        <th>CONTACT</th>
                        <th>LAST VISIT</th>
                        <th>TOTAL VISITS</th>
                        <th>STATUS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr class="patient-row">
                        <td>
                            <div class="patient-info">
                                <div class="patient-avatar" data-initial="{{ substr($patient->name, 0, 1) }}">
                                    {{ substr($patient->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="patient-name">{{ $patient->name }}</div>
                                    <div class="patient-age">
                                        @if($patient->dob)
                                            {{ \Carbon\Carbon::parse($patient->dob)->age }} years
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="contact-info">
                                <div><i class="fas fa-phone-alt"></i> {{ $patient->phone ?? 'N/A' }}</div>
                                <div><i class="fas fa-envelope"></i> {{ $patient->email }}</div>
                            </div>
                        </td>
                        <td>
                            @php
                                $lastVisit = \App\Models\Appointment::where('doctor_id', Auth::user()->doctor->id)
                                    ->where('patient_id', $patient->id)
                                    ->where('status', 'completed')
                                    ->latest('appointment_date')
                                    ->first();
                            @endphp
                            @if($lastVisit)
                                <div class="last-visit">
                                    <div class="date">{{ $lastVisit->appointment_date->format('M d, Y') }}</div>
                                    <div class="time">{{ \Carbon\Carbon::parse($lastVisit->appointment_time)->format('h:i A') }}</div>
                                </div>
                            @else
                                <span class="text-muted">No visits yet</span>
                            @endif
                        </td>
                        <td>
                            <span class="visit-count">{{ $patient->appointments_as_patient_count }}</span>
                        </td>
                        <td>
                            <span class="status-badge status-active">
                                <i class="fas fa-circle me-1"></i> Active
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('doctor.patients.show', $patient) }}" class="action-btn" title="View History">
                                    <i class="fas fa-history"></i>
                                </a>
                                <a href="{{ route('doctor.appointments.index', ['patient_id' => $patient->id]) }}" class="action-btn" title="View Appointments">
                                    <i class="fas fa-calendar-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-users fa-4x mb-3 opacity-50"></i>
                                <h5>No Patients Found</h5>
                                <p class="text-muted">You haven't treated any patients yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-wrapper mt-4">
            {{ $patients->links() }}
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

    /* Stat Cards */
    .stat-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
        padding: 1.5rem;
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

    /* Doctor Table */
    .doctor-table {
        width: 100%;
        border-collapse: collapse;
    }

    .doctor-table th {
        text-align: left;
        padding: 1rem;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        color: #666;
        border-bottom: 2px solid #eaeaea;
    }

    .dark-theme .doctor-table th {
        color: #999;
        border-bottom-color: #2a2a2a;
    }

    .doctor-table td {
        padding: 1rem;
        border-bottom: 1px solid #eaeaea;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .dark-theme .doctor-table td {
        border-bottom-color: #2a2a2a;
    }

    .doctor-table tbody tr {
        transition: all 0.3s ease;
    }

    .doctor-table tbody tr:hover {
        background: #f8f8f8;
    }

    .dark-theme .doctor-table tbody tr:hover {
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
    }

    .dark-theme .patient-avatar {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    tr:hover .patient-avatar {
        transform: scale(1.1);
        background: #111;
        color: #fff;
    }

    .dark-theme tr:hover .patient-avatar {
        background: #fff;
        color: #111;
    }

    .patient-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .patient-age {
        font-size: 0.8rem;
        color: #666;
    }

    .dark-theme .patient-age {
        color: #999;
    }

    /* Contact Info */
    .contact-info {
        font-size: 0.8rem;
    }

    .contact-info i {
        width: 16px;
        margin-right: 0.5rem;
        color: #999;
    }

    /* Last Visit */
    .last-visit {
        font-size: 0.85rem;
    }

    .last-visit .date {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .last-visit .time {
        font-size: 0.75rem;
        color: #666;
    }

    .dark-theme .last-visit .time {
        color: #999;
    }

    /* Visit Count */
    .visit-count {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        font-weight: 600;
    }

    .dark-theme .visit-count {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        border: 1px solid transparent;
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

    /* Responsive */
    @media (max-width: 768px) {
        .d-flex {
            flex-direction: column;
            gap: 1rem;
        }

        .search-box {
            width: 100%;
        }

        .table-header {
            flex-direction: column;
            gap: 1rem;
        }

        .patient-info {
            flex-direction: column;
            text-align: center;
        }

        .contact-info div {
            white-space: nowrap;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Live search functionality
    document.getElementById('patientSearch')?.addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('.patient-row');
        
        rows.forEach(row => {
            const patientName = row.querySelector('.patient-name')?.textContent.toLowerCase() || '';
            const patientEmail = row.querySelector('.contact-info')?.textContent.toLowerCase() || '';
            
            if (patientName.includes(searchValue) || patientEmail.includes(searchValue)) {
                row.style.display = '';
                row.style.animation = 'fadeIn 0.3s ease';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Export to CSV
    function exportToCSV() {
        const rows = document.querySelectorAll('.patient-row');
        const data = [];
        
        // Headers
        data.push(['Name', 'Email', 'Phone', 'Last Visit', 'Total Visits', 'Status']);
        
        // Data rows
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const rowData = [
                    row.querySelector('.patient-name')?.textContent.trim() || '',
                    row.querySelector('.contact-info .fa-envelope')?.parentNode?.textContent.trim() || '',
                    row.querySelector('.contact-info .fa-phone-alt')?.parentNode?.textContent.trim() || '',
                    row.querySelector('.last-visit .date')?.textContent.trim() || 'No visits',
                    row.querySelector('.visit-count')?.textContent.trim() || '0',
                    'Active'
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
        a.download = 'my_patients_export.csv';
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