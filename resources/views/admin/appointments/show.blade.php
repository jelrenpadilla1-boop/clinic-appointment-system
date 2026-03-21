{{-- resources/views/admin/appointments/show.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">APPOINTMENT DETAILS</h2>
            <p class="text-muted mb-0">View complete information about appointment #{{ $appointment->id }}</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.appointments.index') }}" class="btn-back">
                <i class="fas fa-arrow-left me-2"></i>
                BACK TO APPOINTMENTS
            </a>
            <a href="{{ route('admin.appointments.index', ['status' => $appointment->status]) }}" class="btn-filter">
                <i class="fas fa-filter me-2"></i>
                VIEW ALL {{ strtoupper($appointment->status) }}
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
        @if($appointment->status === 'pending')
        <div class="status-actions">
            <form action="{{ route('admin.appointments.approve', $appointment) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn-approve" onclick="return confirm('Approve this appointment?')">
                    <i class="fas fa-check me-2"></i>APPROVE
                </button>
            </form>
            <button type="button" class="btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal">
                <i class="fas fa-times me-2"></i>REJECT
            </button>
        </div>
        @endif
    </div>

    <div class="row g-4">
        <!-- Appointment Information -->
        <div class="col-md-5">
            <div class="info-card light">
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
                        <div class="info-row">
                            <span class="info-label">Last updated:</span>
                            <span class="info-value">{{ $appointment->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>

                    @if($appointment->notes)
                    <div class="notes-section mt-4">
                        <h6 class="notes-title">PATIENT NOTES</h6>
                        <div class="notes-content">
                            {{ $appointment->notes }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="info-card light mt-4">
                <div class="info-card-header">
                    <h5 class="info-card-title">
                        <i class="fas fa-history me-2"></i>
                        TIMELINE
                    </h5>
                </div>
                <div class="info-card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <div class="timeline-content">
                                <span class="timeline-title">Appointment Created</span>
                                <span class="timeline-time">{{ $appointment->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                        
                        @if($appointment->status === 'confirmed' || $appointment->status === 'completed')
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="timeline-content">
                                <span class="timeline-title">Appointment Confirmed</span>
                                <span class="timeline-time">{{ $appointment->updated_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                        @endif
                        
                        @if($appointment->status === 'completed')
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-check-double"></i>
                            </div>
                            <div class="timeline-content">
                                <span class="timeline-title">Appointment Completed</span>
                                <span class="timeline-time">{{ $appointment->updated_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                        @endif
                        
                        @if($appointment->status === 'cancelled')
                        <div class="timeline-item">
                            <div class="timeline-icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                            <div class="timeline-content">
                                <span class="timeline-title">Appointment Cancelled</span>
                                <span class="timeline-time">{{ $appointment->updated_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient & Doctor Information -->
        <div class="col-md-7">
            <!-- Patient Information -->
            <div class="info-card light mb-4">
                <div class="info-card-header">
                    <h5 class="info-card-title">
                        <i class="fas fa-user me-2"></i>
                        PATIENT INFORMATION
                    </h5>
                    <a href="{{ route('admin.patients.show', $appointment->patient) }}" class="btn-small">
                        VIEW PROFILE <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
                <div class="info-card-body">
                    <div class="profile-section">
                        <div class="profile-avatar">
                            <div class="avatar-circle">
                                {{ substr($appointment->patient->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="profile-details">
                            <h4 class="profile-name">{{ $appointment->patient->name }}</h4>
                            <div class="profile-contact">
                                <span><i class="fas fa-envelope"></i> {{ $appointment->patient->email }}</span>
                                <span><i class="fas fa-phone-alt"></i> {{ $appointment->patient->phone ?? 'N/A' }}</span>
                            </div>
                            @if($appointment->patient->address)
                            <div class="profile-address">
                                <i class="fas fa-map-marker-alt"></i> {{ $appointment->patient->address }}
                            </div>
                            @endif
                        </div>
                    </div>

                    @php
                        $patientAppointments = \App\Models\Appointment::where('patient_id', $appointment->patient_id)
                            ->where('id', '!=', $appointment->id)
                            ->with('doctor.user')
                            ->latest()
                            ->take(3)
                            ->get();
                    @endphp

                    @if($patientAppointments->count() > 0)
                    <div class="patient-history mt-4">
                        <h6 class="history-title">PREVIOUS APPOINTMENTS</h6>
                        <div class="history-list">
                            @foreach($patientAppointments as $history)
                            <div class="history-item">
                                <div class="history-info">
                                    <span class="history-date">{{ $history->appointment_date->format('M d, Y') }}</span>
                                    <span class="history-doctor">Dr. {{ $history->doctor->user->name }}</span>
                                </div>
                                <span class="history-status status-{{ $history->status }}">
                                    {{ ucfirst($history->status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Doctor Information -->
            <div class="info-card light mb-4">
                <div class="info-card-header">
                    <h5 class="info-card-title">
                        <i class="fas fa-user-md me-2"></i>
                        DOCTOR INFORMATION
                    </h5>
                    <a href="{{ route('admin.doctors.show', $appointment->doctor) }}" class="btn-small">
                        VIEW PROFILE <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
                <div class="info-card-body">
                    <div class="profile-section">
                        <div class="profile-avatar">
                            <div class="avatar-circle doctor-avatar">
                                {{ substr($appointment->doctor->user->name, 0, 1) }}
                            </div>
                        </div>
                        <div class="profile-details">
                            <h4 class="profile-name">Dr. {{ $appointment->doctor->user->name }}</h4>
                            <div class="profile-specialization">{{ $appointment->doctor->specialization->name }}</div>
                            <div class="profile-contact">
                                <span><i class="fas fa-id-card"></i> License: {{ $appointment->doctor->license_number }}</span>
                            </div>
                        </div>
                    </div>

                    @php
                        $doctorSchedule = \App\Models\Schedule::where('doctor_id', $appointment->doctor_id)
                            ->orderBy('day_of_week')
                            ->get();
                    @endphp

                    @if($doctorSchedule->count() > 0)
                    <div class="doctor-schedule mt-4">
                        <h6 class="schedule-title">WORKING SCHEDULE</h6>
                        <div class="schedule-mini-grid">
                            @foreach($doctorSchedule as $schedule)
                            <div class="schedule-mini-item">
                                <span class="schedule-mini-day">{{ substr($schedule->day_name, 0, 3) }}</span>
                                <span class="schedule-mini-time">
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Medical Records -->
            @if($appointment->medicalRecord || $appointment->status === 'completed')
            <div class="info-card light">
                <div class="info-card-header">
                    <h5 class="info-card-title">
                        <i class="fas fa-notes-medical me-2"></i>
                        MEDICAL RECORDS
                    </h5>
                    @if($appointment->status === 'completed')
                    <button type="button" class="btn-small" data-bs-toggle="modal" data-bs-target="#addMedicalModal">
                        <i class="fas fa-plus me-2"></i>ADD RECORDS
                    </button>
                    @endif
                </div>
                <div class="info-card-body">
                    @if($appointment->medicalRecord)
                        <div class="medical-records">
                            <div class="record-item">
                                <div class="record-label">Diagnosis:</div>
                                <div class="record-value">{{ $appointment->medicalRecord->diagnosis ?? 'N/A' }}</div>
                            </div>
                            <div class="record-item">
                                <div class="record-label">Prescription:</div>
                                <div class="record-value">{{ $appointment->medicalRecord->prescription ?? 'N/A' }}</div>
                            </div>
                            @if($appointment->medicalRecord->remarks)
                            <div class="record-item">
                                <div class="record-label">Remarks:</div>
                                <div class="record-value">{{ $appointment->medicalRecord->remarks }}</div>
                            </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted text-center py-3">No medical records available for this appointment.</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">REJECT APPOINTMENT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.appointments.reject', $appointment) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label class="form-label">REASON FOR REJECTION</label>
                        <textarea name="rejection_reason" class="form-control" rows="4" required 
                                  placeholder="Please provide a reason for rejecting this appointment..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-danger">REJECT APPOINTMENT</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Medical Records Modal -->
@if($appointment->status === 'completed')
<div class="modal fade" id="addMedicalModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">ADD MEDICAL RECORDS</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('doctor.appointments.add-notes', $appointment) }}" method="POST">
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
                                <label class="form-label">ADDITIONAL REMARKS</label>
                                <textarea name="remarks" class="form-control" rows="3" 
                                          placeholder="Any additional notes..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">CANCEL</button>
                    <button type="submit" class="btn btn-primary">SAVE RECORDS</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
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

    /* Filter Button */
    .btn-filter {
        padding: 10px 20px;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        color: #111;
        text-decoration: none;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
    }

    .dark-theme .btn-filter {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #fff;
    }

    .btn-filter:hover {
        background: #111;
        color: #fff;
        transform: translateY(-2px);
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

    .status-actions {
        display: flex;
        gap: 0.5rem;
    }

    .btn-approve, .btn-reject {
        padding: 8px 16px;
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-approve {
        background: #28a745;
        color: #fff;
    }

    .btn-approve:hover {
        background: #218838;
        transform: translateY(-2px);
    }

    .btn-reject {
        background: #dc3545;
        color: #fff;
    }

    .btn-reject:hover {
        background: #c82333;
        transform: translateY(-2px);
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

    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 2rem;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 10px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #eaeaea;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 1.5rem;
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .timeline-item:last-child {
        padding-bottom: 0;
    }

    .timeline-icon {
        position: absolute;
        left: -2rem;
        width: 24px;
        height: 24px;
        background: #fff;
        border: 2px solid #eaeaea;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        transition: all 0.3s ease;
    }

    .dark-theme .timeline-icon {
        background: #1a1a1a;
        border-color: #3a3a3a;
    }

    .timeline-item:hover .timeline-icon {
        transform: scale(1.2);
        border-color: #111;
    }

    .timeline-content {
        flex: 1;
    }

    .timeline-title {
        display: block;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .timeline-time {
        display: block;
        font-size: 0.8rem;
        color: #666;
    }

    .dark-theme .timeline-time {
        color: #999;
    }

    /* Profile Section */
    .profile-section {
        display: flex;
        gap: 1.5rem;
        align-items: center;
    }

    .avatar-circle {
        width: 80px;
        height: 80px;
        background: #f8f8f8;
        border: 2px solid #eaeaea;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
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

    .profile-contact {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .profile-contact i {
        width: 16px;
        margin-right: 0.5rem;
        color: #666;
    }

    .profile-address {
        font-size: 0.9rem;
    }

    .profile-address i {
        width: 16px;
        margin-right: 0.5rem;
        color: #666;
    }

    .profile-specialization {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        font-size: 0.8rem;
        margin-bottom: 0.5rem;
    }

    .dark-theme .profile-specialization {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    /* History List */
    .patient-history {
        padding: 1rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
    }

    .dark-theme .patient-history {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .history-title {
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: #666;
    }

    .dark-theme .history-title {
        color: #999;
    }

    .history-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .history-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem;
        background: #fff;
        border: 1px solid #eaeaea;
        transition: all 0.3s ease;
    }

    .dark-theme .history-item {
        background: #1a1a1a;
        border-color: #3a3a3a;
    }

    .history-item:hover {
        transform: translateX(5px);
    }

    .history-info {
        display: flex;
        flex-direction: column;
    }

    .history-date {
        font-weight: 600;
        font-size: 0.9rem;
    }

    .history-doctor {
        font-size: 0.8rem;
        color: #666;
    }

    .dark-theme .history-doctor {
        color: #999;
    }

    .history-status {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
        border: 1px solid transparent;
    }

    /* Schedule Mini Grid */
    .doctor-schedule {
        padding: 1rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
    }

    .dark-theme .doctor-schedule {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .schedule-title {
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        color: #666;
    }

    .dark-theme .schedule-title {
        color: #999;
    }

    .schedule-mini-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 0.5rem;
    }

    .schedule-mini-item {
        padding: 0.5rem;
        background: #fff;
        border: 1px solid #eaeaea;
        text-align: center;
        transition: all 0.3s ease;
    }

    .dark-theme .schedule-mini-item {
        background: #1a1a1a;
        border-color: #3a3a3a;
    }

    .schedule-mini-item:hover {
        transform: translateY(-2px);
    }

    .schedule-mini-day {
        display: block;
        font-weight: 600;
        font-size: 0.7rem;
        margin-bottom: 0.25rem;
    }

    .schedule-mini-time {
        display: block;
        font-size: 0.65rem;
        color: #666;
    }

    .dark-theme .schedule-mini-time {
        color: #999;
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

        .btn-back, .btn-filter {
            width: 100%;
            justify-content: center;
        }

        .status-banner {
            flex-direction: column;
            text-align: center;
        }

        .profile-section {
            flex-direction: column;
            text-align: center;
        }

        .profile-contact {
            flex-direction: column;
            gap: 0.5rem;
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