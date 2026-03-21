{{-- resources/views/doctor/appointments/index.blade.php --}}
@extends('layouts.doctor')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">MY APPOINTMENTS</h2>
            <p class="text-muted mb-0">Manage and view all your appointments</p>
        </div>
        <div class="d-flex gap-2">
            <div class="search-box">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="appointmentSearch" class="form-control" placeholder="Search appointments...">
            </div>
            <a href="{{ route('doctor.schedule') }}" class="btn-schedule">
                <i class="fas fa-clock me-2"></i>MY SCHEDULE
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">TODAY</span>
                    <span class="stat-value">{{ $appointments->where('appointment_date', today()->format('Y-m-d'))->count() }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">PENDING</span>
                    <span class="stat-value">{{ $appointments->where('status', 'pending')->count() }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">CONFIRMED</span>
                    <span class="stat-value">{{ $appointments->where('status', 'confirmed')->count() }}</span>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card light">
                <div class="stat-icon">
                    <i class="fas fa-history"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-label">COMPLETED</span>
                    <span class="stat-value">{{ $appointments->where('status', 'completed')->count() }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="filter-tabs mb-4">
        <a href="{{ route('doctor.appointments.index') }}" class="filter-tab {{ !request('status') ? 'active' : '' }}">
            ALL
        </a>
        <a href="{{ route('doctor.appointments.index', ['status' => 'pending']) }}" class="filter-tab {{ request('status') == 'pending' ? 'active' : '' }}">
            PENDING
        </a>
        <a href="{{ route('doctor.appointments.index', ['status' => 'confirmed']) }}" class="filter-tab {{ request('status') == 'confirmed' ? 'active' : '' }}">
            CONFIRMED
        </a>
        <a href="{{ route('doctor.appointments.index', ['status' => 'completed']) }}" class="filter-tab {{ request('status') == 'completed' ? 'active' : '' }}">
            COMPLETED
        </a>
        <a href="{{ route('doctor.appointments.index', ['status' => 'cancelled']) }}" class="filter-tab {{ request('status') == 'cancelled' ? 'active' : '' }}">
            CANCELLED
        </a>
    </div>

    <!-- Appointments Table -->
    <div class="table-card light">
        <div class="table-header">
            <h5 class="table-title">
                @if(request('status'))
                    {{ strtoupper(request('status')) }} APPOINTMENTS
                @else
                    ALL APPOINTMENTS
                @endif
            </h5>
            <div class="table-actions">
                <span class="badge bg-light text-dark me-2">Total: {{ $appointments->total() }}</span>
                <a href="#" class="export-link" onclick="exportToCSV()">
                    <i class="fas fa-download me-1"></i> EXPORT
                </a>
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="doctor-table" id="appointmentsTable">
                <thead>
                    <tr>
                        <th>DATE & TIME</th>
                        <th>PATIENT</th>
                        <th>CONTACT</th>
                        <th>NOTES</th>
                        <th>STATUS</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($appointments as $appointment)
                    <tr class="appointment-row" data-status="{{ $appointment->status }}">
                        <td>
                            <div class="datetime-info">
                                <div class="date">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                <div class="time">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</div>
                                @if($appointment->appointment_date->isToday())
                                    <span class="today-badge">TODAY</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="patient-info">
                                <div class="patient-avatar" data-initial="{{ substr($appointment->patient->name, 0, 1) }}">
                                    {{ substr($appointment->patient->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="patient-name">{{ $appointment->patient->name }}</div>
                                    <div class="patient-age">
                                        @if($appointment->patient->dob)
                                            {{ \Carbon\Carbon::parse($appointment->patient->dob)->age }} years
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="contact-info">
                                <div><i class="fas fa-phone-alt"></i> {{ $appointment->patient->phone ?? 'N/A' }}</div>
                                <div><i class="fas fa-envelope"></i> {{ $appointment->patient->email }}</div>
                            </div>
                        </td>
                        <td>
                            @if($appointment->notes)
                                <span class="notes-indicator" title="{{ $appointment->notes }}">
                                    <i class="fas fa-file-alt"></i> Notes
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="status-badge status-{{ $appointment->status }}">
                                {{ ucfirst($appointment->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <a href="{{ route('doctor.appointments.show', $appointment) }}" class="action-btn" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                
                                @if($appointment->status === 'pending' || $appointment->status === 'confirmed')
                                    <button type="button" class="action-btn" title="Update Status" 
                                            onclick="openStatusModal({{ $appointment->id }}, '{{ $appointment->status }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                @endif
                                
                                @if($appointment->status === 'confirmed')
                                    <button type="button" class="action-btn text-success" title="Add Medical Notes"
                                            onclick="openNotesModal({{ $appointment->id }})">
                                        <i class="fas fa-notes-medical"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-calendar-times fa-4x mb-3 opacity-50"></i>
                                <h5>No Appointments Found</h5>
                                <p class="text-muted">There are no appointments matching your criteria.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="pagination-wrapper mt-4">
            {{ $appointments->withQueryString()->links() }}
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">UPDATE APPOINTMENT STATUS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">NEW STATUS</label>
                        <select name="status" class="form-select" required>
                            <option value="confirmed">Confirm</option>
                            <option value="completed">Mark as Completed</option>
                            <option value="cancelled">Cancel</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CLOSE</button>
                    <button type="submit" class="btn btn-primary">UPDATE STATUS</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Notes Modal -->
<div class="modal fade" id="notesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ADD MEDICAL NOTES</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="notesForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">DIAGNOSIS</label>
                                <textarea name="diagnosis" class="form-control" rows="3" 
                                          placeholder="Enter diagnosis..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">PRESCRIPTION</label>
                                <textarea name="prescription" class="form-control" rows="3" 
                                          placeholder="Enter prescription..."></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">REMARKS</label>
                                <textarea name="remarks" class="form-control" rows="3" 
                                          placeholder="Additional remarks..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-primary">SAVE NOTES</button>
                </div>
            </form>
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

    /* Schedule Button */
    .btn-schedule {
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

    .dark-theme .btn-schedule {
        background: #fff;
        border-color: #fff;
        color: #111;
    }

    .btn-schedule:hover {
        background: #fff;
        color: #111;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .dark-theme .btn-schedule:hover {
        background: #111;
        color: #fff;
        box-shadow: 0 5px 15px rgba(255, 255, 255, 0.1);
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

    /* Filter Tabs */
    .filter-tabs {
        display: flex;
        gap: 0.5rem;
        border-bottom: 2px solid #eaeaea;
        padding-bottom: 0.5rem;
    }

    .dark-theme .filter-tabs {
        border-bottom-color: #2a2a2a;
    }

    .filter-tab {
        padding: 0.5rem 1rem;
        text-decoration: none;
        color: #666;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        position: relative;
    }

    .dark-theme .filter-tab {
        color: #999;
    }

    .filter-tab:hover {
        color: #111;
    }

    .dark-theme .filter-tab:hover {
        color: #fff;
    }

    .filter-tab.active {
        color: #111;
    }

    .filter-tab.active::after {
        content: '';
        position: absolute;
        bottom: -9px;
        left: 0;
        width: 100%;
        height: 2px;
        background: #111;
        animation: slideIn 0.3s ease;
    }

    .dark-theme .filter-tab.active::after {
        background: #fff;
    }

    @keyframes slideIn {
        from { width: 0; }
        to { width: 100%; }
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

    /* DateTime Info */
    .datetime-info {
        position: relative;
    }

    .date {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .time {
        font-size: 0.8rem;
        color: #666;
    }

    .dark-theme .time {
        color: #999;
    }

    .today-badge {
        display: inline-block;
        margin-top: 0.5rem;
        padding: 0.2rem 0.5rem;
        background: #111;
        color: #fff;
        font-size: 0.6rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    .dark-theme .today-badge {
        background: #fff;
        color: #111;
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

    /* Notes Indicator */
    .notes-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.25rem 0.75rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        font-size: 0.8rem;
        cursor: help;
        transition: all 0.3s ease;
    }

    .dark-theme .notes-indicator {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .notes-indicator:hover {
        background: #111;
        color: #fff;
    }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        border: 1px solid transparent;
        transition: all 0.3s ease;
    }

    .status-badge:hover {
        transform: scale(1.05);
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

    .dark-theme .status-pending {
        background: #665c1c;
        color: #ffd966;
        border-color: #857b3c;
    }

    .dark-theme .status-confirmed {
        background: #1e4a2a;
        color: #9bdf9b;
        border-color: #2d6a3d;
    }

    .dark-theme .status-completed {
        background: #1e5460;
        color: #9fd9e6;
        border-color: #2d6f7a;
    }

    .dark-theme .status-cancelled {
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

    /* Modal Styles */
    .modal-content {
        border-radius: 0;
        border: 1px solid #eaeaea;
    }

    .dark-theme .modal-content {
        background: #1a1a1a;
        border-color: #2a2a2a;
        color: #fff;
    }

    .modal-header {
        border-bottom: 1px solid #eaeaea;
        padding: 1rem 1.5rem;
    }

    .dark-theme .modal-header {
        border-bottom-color: #2a2a2a;
    }

    .modal-footer {
        border-top: 1px solid #eaeaea;
        padding: 1rem 1.5rem;
    }

    .dark-theme .modal-footer {
        border-top-color: #2a2a2a;
    }

    .modal-title {
        font-size: 1rem;
        font-weight: 600;
    }

    .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    .dark-theme .btn-close {
        filter: invert(0) grayscale(100%) brightness(200%);
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

        .btn-schedule {
            width: 100%;
            justify-content: center;
        }

        .filter-tabs {
            flex-wrap: wrap;
        }

        .filter-tab {
            flex: 1;
            text-align: center;
        }

        .table-header {
            flex-direction: column;
            gap: 1rem;
        }

        .action-buttons {
            flex-wrap: wrap;
        }

        .doctor-table {
            font-size: 0.8rem;
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
    document.getElementById('appointmentSearch')?.addEventListener('keyup', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('.appointment-row');
        
        rows.forEach(row => {
            const patientName = row.querySelector('.patient-name')?.textContent.toLowerCase() || '';
            const patientEmail = row.querySelector('.contact-info')?.textContent.toLowerCase() || '';
            const date = row.querySelector('.date')?.textContent.toLowerCase() || '';
            
            if (patientName.includes(searchValue) || patientEmail.includes(searchValue) || date.includes(searchValue)) {
                row.style.display = '';
                row.style.animation = 'fadeIn 0.3s ease';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Status Modal
    function openStatusModal(appointmentId, currentStatus) {
        const modal = new bootstrap.Modal(document.getElementById('statusModal'));
        const form = document.getElementById('statusForm');
        form.action = `/doctor/appointments/${appointmentId}/update-status`;
        modal.show();
    }

    // Notes Modal
    function openNotesModal(appointmentId) {
        const modal = new bootstrap.Modal(document.getElementById('notesModal'));
        const form = document.getElementById('notesForm');
        form.action = `/doctor/appointments/${appointmentId}/add-notes`;
        modal.show();
    }

    // Export to CSV
    function exportToCSV() {
        const rows = document.querySelectorAll('.appointment-row');
        const data = [];
        
        // Headers
        data.push(['Date', 'Time', 'Patient', 'Email', 'Phone', 'Status', 'Notes']);
        
        // Data rows
        rows.forEach(row => {
            if (row.style.display !== 'none') {
                const rowData = [
                    row.querySelector('.date')?.textContent.trim() || '',
                    row.querySelector('.time')?.textContent.trim() || '',
                    row.querySelector('.patient-name')?.textContent.trim() || '',
                    row.querySelector('.contact-info .fa-envelope')?.parentNode?.textContent.trim() || '',
                    row.querySelector('.contact-info .fa-phone-alt')?.parentNode?.textContent.trim() || '',
                    row.querySelector('.status-badge')?.textContent.trim() || '',
                    row.querySelector('.notes-indicator') ? 'Has Notes' : 'No Notes'
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
        a.download = 'appointments_export.csv';
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