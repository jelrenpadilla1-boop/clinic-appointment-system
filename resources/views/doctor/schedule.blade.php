{{-- resources/views/doctor/schedule.blade.php --}}
@extends('layouts.doctor')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">MY WORKING SCHEDULE</h2>
            <p class="text-muted mb-0">Set your availability for patient appointments</p>
        </div>
        <a href="{{ route('doctor.dashboard') }}" class="btn-back">
            <i class="fas fa-arrow-left me-2"></i>
            BACK TO DASHBOARD
        </a>
    </div>

    <!-- Schedule Form -->
    <div class="schedule-card">
        <div class="schedule-card-header">
            <h5 class="schedule-card-title">
                <i class="fas fa-clock me-2"></i>
                WEEKLY SCHEDULE
            </h5>
            <p class="schedule-card-subtitle">Set your available days and time slots</p>
        </div>
        
        <div class="schedule-card-body">
            <form action="{{ route('doctor.schedule.update') }}" method="POST" id="scheduleForm">
                @csrf
                
                <div class="schedule-grid">
                    @php
                        $days = [
                            ['id' => 0, 'name' => 'Sunday'],
                            ['id' => 1, 'name' => 'Monday'],
                            ['id' => 2, 'name' => 'Tuesday'],
                            ['id' => 3, 'name' => 'Wednesday'],
                            ['id' => 4, 'name' => 'Thursday'],
                            ['id' => 5, 'name' => 'Friday'],
                            ['id' => 6, 'name' => 'Saturday'],
                        ];
                        
                        $timeSlots = [
                            '09:00-12:00' => 'Morning (9 AM - 12 PM)',
                            '12:00-15:00' => 'Afternoon (12 PM - 3 PM)',
                            '15:00-18:00' => 'Evening (3 PM - 6 PM)',
                            '18:00-21:00' => 'Night (6 PM - 9 PM)'
                        ];
                    @endphp

                    @foreach($days as $index => $day)
                    <div class="schedule-day-card" data-day="{{ $day['id'] }}">
                        <div class="day-header">
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input day-toggle" 
                                       id="day_{{ $day['id'] }}"
                                       data-day-id="{{ $day['id'] }}"
                                       {{ $schedules->contains('day_of_week', $day['id']) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="day_{{ $day['id'] }}">
                                    {{ $day['name'] }}
                                </label>
                            </div>
                        </div>
                        
                        <div class="day-schedule-details">
                            @php
                                $daySchedule = $schedules->firstWhere('day_of_week', $day['id']);
                            @endphp
                            
                            <div class="time-inputs">
                                <div class="time-input-group">
                                    <label>Start Time</label>
                                    <input type="time" 
                                           name="schedules[{{ $day['id'] }}][start_time]" 
                                           class="form-control start-time"
                                           data-day="{{ $day['id'] }}"
                                           value="{{ $daySchedule ? $daySchedule->start_time : '09:00' }}"
                                           {{ !$schedules->contains('day_of_week', $day['id']) ? 'disabled' : '' }}>
                                </div>
                                <div class="time-input-group">
                                    <label>End Time</label>
                                    <input type="time" 
                                           name="schedules[{{ $day['id'] }}][end_time]" 
                                           class="form-control end-time"
                                           data-day="{{ $day['id'] }}"
                                           value="{{ $daySchedule ? $daySchedule->end_time : '17:00' }}"
                                           {{ !$schedules->contains('day_of_week', $day['id']) ? 'disabled' : '' }}>
                                </div>
                            </div>
                            
                            <div class="patients-input">
                                <label>Max Patients</label>
                                <input type="number" 
                                       name="schedules[{{ $day['id'] }}][max_patients]" 
                                       class="form-control max-patients"
                                       data-day="{{ $day['id'] }}"
                                       value="{{ $daySchedule ? $daySchedule->max_patients : '10' }}"
                                       min="1"
                                       max="50"
                                       {{ !$schedules->contains('day_of_week', $day['id']) ? 'disabled' : '' }}>
                            </div>
                            
                            <input type="hidden" 
                                   name="schedules[{{ $day['id'] }}][day_of_week]" 
                                   value="{{ $day['id'] }}"
                                   class="day-of-week">
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Schedule Summary -->
                <div class="schedule-summary mt-4">
                    <h6 class="summary-title">SCHEDULE SUMMARY</h6>
                    <div class="summary-stats">
                        <div class="summary-stat">
                            <span class="stat-label">Working Days</span>
                            <span class="stat-value" id="workingDaysCount">{{ $schedules->count() }}</span>
                        </div>
                        <div class="summary-stat">
                            <span class="stat-label">Total Hours/Week</span>
                            <span class="stat-value" id="totalHours">--</span>
                        </div>
                        <div class="summary-stat">
                            <span class="stat-label">Total Patients/Week</span>
                            <span class="stat-value" id="totalPatients">{{ $schedules->sum('max_patients') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="button" class="btn-reset" onclick="resetToCurrent()">
                        <i class="fas fa-undo me-2"></i>RESET TO CURRENT
                    </button>
                    <button type="submit" class="btn-save">
                        <i class="fas fa-save me-2"></i>SAVE SCHEDULE
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Current Schedule Preview -->
    <div class="schedule-preview-card mt-4">
        <div class="schedule-preview-header">
            <h5 class="schedule-preview-title">
                <i class="fas fa-eye me-2"></i>
                CURRENT SCHEDULE PREVIEW
            </h5>
        </div>
        <div class="schedule-preview-body">
            <div class="preview-grid">
                @foreach($days as $day)
                    @php $daySchedule = $schedules->firstWhere('day_of_week', $day['id']); @endphp
                    @if($daySchedule)
                    <div class="preview-item">
                        <span class="preview-day">{{ substr($day['name'], 0, 3) }}</span>
                        <span class="preview-time">
                            {{ \Carbon\Carbon::parse($daySchedule->start_time)->format('h:i A') }} - 
                            {{ \Carbon\Carbon::parse($daySchedule->end_time)->format('h:i A') }}
                        </span>
                        <span class="preview-patients">{{ $daySchedule->max_patients }} patients</span>
                    </div>
                    @endif
                @endforeach
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

    /* Schedule Card */
    .schedule-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
    }

    .dark-theme .schedule-card {
        background: #1a1a1a;
        border-color: #2a2a2a;
    }

    .schedule-card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #eaeaea;
    }

    .dark-theme .schedule-card-header {
        border-bottom-color: #2a2a2a;
    }

    .schedule-card-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .schedule-card-subtitle {
        font-size: 0.8rem;
        color: #666;
        margin: 0;
    }

    .dark-theme .schedule-card-subtitle {
        color: #999;
    }

    .schedule-card-body {
        padding: 1.5rem;
    }

    /* Schedule Grid */
    .schedule-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1rem;
    }

    /* Schedule Day Card */
    .schedule-day-card {
        padding: 1rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        transition: all 0.3s ease;
    }

    .dark-theme .schedule-day-card {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .schedule-day-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .day-header {
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #eaeaea;
    }

    .dark-theme .day-header {
        border-bottom-color: #3a3a3a;
    }

    .form-check-input {
        width: 1.2rem;
        height: 1.2rem;
        margin-right: 0.5rem;
        cursor: pointer;
        border: 1px solid #eaeaea;
    }

    .dark-theme .form-check-input {
        background: #1a1a1a;
        border-color: #3a3a3a;
    }

    .form-check-input:checked {
        background-color: #111;
        border-color: #111;
    }

    .form-check-label {
        font-size: 1rem;
        cursor: pointer;
    }

    /* Day Schedule Details */
    .day-schedule-details {
        transition: all 0.3s ease;
    }

    .day-schedule-details:has(.day-toggle:not(:checked)) {
        opacity: 0.5;
        pointer-events: none;
    }

    .time-inputs {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .time-input-group label,
    .patients-input label {
        display: block;
        font-size: 0.7rem;
        font-weight: 600;
        color: #666;
        margin-bottom: 0.25rem;
    }

    .dark-theme .time-input-group label,
    .dark-theme .patients-input label {
        color: #999;
    }

    .time-input-group input,
    .patients-input input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #eaeaea;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .dark-theme .time-input-group input,
    .dark-theme .patients-input input {
        background: #1a1a1a;
        border-color: #2a2a2a;
        color: #fff;
    }

    .time-input-group input:focus,
    .patients-input input:focus {
        outline: none;
        border-color: #111;
        box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.1);
    }

    .time-input-group input:disabled,
    .patients-input input:disabled {
        background: #f8f8f8;
        color: #999;
    }

    .dark-theme .time-input-group input:disabled,
    .dark-theme .patients-input input:disabled {
        background: #2a2a2a;
        color: #666;
    }

    /* Schedule Summary */
    .schedule-summary {
        padding: 1rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
    }

    .dark-theme .schedule-summary {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .summary-title {
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #666;
    }

    .dark-theme .summary-title {
        color: #999;
    }

    .summary-stats {
        display: flex;
        gap: 2rem;
    }

    .summary-stat {
        text-align: center;
    }

    .summary-stat .stat-label {
        display: block;
        font-size: 0.7rem;
        color: #666;
        margin-bottom: 0.25rem;
    }

    .dark-theme .summary-stat .stat-label {
        color: #999;
    }

    .summary-stat .stat-value {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        color: #111;
    }

    .dark-theme .summary-stat .stat-value {
        color: #fff;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 1px solid #eaeaea;
    }

    .dark-theme .form-actions {
        border-top-color: #2a2a2a;
    }

    .btn-reset {
        padding: 10px 20px;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        color: #111;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .dark-theme .btn-reset {
        background: #2a2a2a;
        border-color: #3a3a3a;
        color: #fff;
    }

    .btn-reset:hover {
        background: #dc3545;
        border-color: #dc3545;
        color: #fff;
        transform: translateY(-2px);
    }

    .btn-save {
        padding: 10px 20px;
        background: #111;
        border: 1px solid #111;
        color: #fff;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .dark-theme .btn-save {
        background: #fff;
        border-color: #fff;
        color: #111;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    /* Schedule Preview Card */
    .schedule-preview-card {
        background: #ffffff;
        border: 1px solid #eaeaea;
    }

    .dark-theme .schedule-preview-card {
        background: #1a1a1a;
        border-color: #2a2a2a;
    }

    .schedule-preview-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #eaeaea;
    }

    .dark-theme .schedule-preview-header {
        border-bottom-color: #2a2a2a;
    }

    .schedule-preview-title {
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0;
    }

    .schedule-preview-body {
        padding: 1.5rem;
    }

    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.5rem;
    }

    .preview-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem;
        background: #f8f8f8;
        border: 1px solid #eaeaea;
        transition: all 0.3s ease;
    }

    .dark-theme .preview-item {
        background: #2a2a2a;
        border-color: #3a3a3a;
    }

    .preview-item:hover {
        transform: translateX(5px);
    }

    .preview-day {
        font-weight: 600;
        min-width: 40px;
    }

    .preview-time {
        font-size: 0.8rem;
        color: #666;
    }

    .dark-theme .preview-time {
        color: #999;
    }

    .preview-patients {
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
        background: #fff;
        border: 1px solid #eaeaea;
    }

    .dark-theme .preview-patients {
        background: #1a1a1a;
        border-color: #3a3a3a;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .d-flex {
            flex-direction: column;
            gap: 1rem;
        }

        .btn-back {
            width: 100%;
            justify-content: center;
        }

        .summary-stats {
            flex-direction: column;
            gap: 1rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-reset, .btn-save {
            width: 100%;
        }

        .preview-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Toggle day schedule inputs
    document.querySelectorAll('.day-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const dayCard = this.closest('.schedule-day-card');
            const inputs = dayCard.querySelectorAll('input:not(.day-toggle)');
            
            inputs.forEach(input => {
                input.disabled = !this.checked;
            });

            updateSummary();
        });
    });

    // Update schedule summary
    function updateSummary() {
        const workingDays = document.querySelectorAll('.day-toggle:checked').length;
        document.getElementById('workingDaysCount').textContent = workingDays;

        let totalHours = 0;
        let totalPatients = 0;

        document.querySelectorAll('.schedule-day-card').forEach(card => {
            const toggle = card.querySelector('.day-toggle');
            if (toggle && toggle.checked) {
                const startTime = card.querySelector('.start-time').value;
                const endTime = card.querySelector('.end-time').value;
                const maxPatients = card.querySelector('.max-patients').value;

                if (startTime && endTime) {
                    const start = new Date(`2000-01-01T${startTime}`);
                    const end = new Date(`2000-01-01T${endTime}`);
                    const hours = (end - start) / (1000 * 60 * 60);
                    totalHours += hours;
                }

                totalPatients += parseInt(maxPatients) || 0;
            }
        });

        document.getElementById('totalHours').textContent = totalHours.toFixed(1);
        document.getElementById('totalPatients').textContent = totalPatients;
    }

    // Validate time inputs
    document.querySelectorAll('.start-time, .end-time').forEach(input => {
        input.addEventListener('change', function() {
            const dayCard = this.closest('.schedule-day-card');
            const start = dayCard.querySelector('.start-time').value;
            const end = dayCard.querySelector('.end-time').value;
            const dayName = dayCard.querySelector('.form-check-label').textContent;

            if (start && end && start >= end) {
                alert(`End time must be after start time for ${dayName}`);
                this.value = dayCard.querySelector('.start-time').dataset.lastValue || '09:00';
            } else {
                dayCard.querySelector('.start-time').dataset.lastValue = start;
            }

            updateSummary();
        });
    });

    // Reset to current schedule
    function resetToCurrent() {
        if (confirm('Reset all changes to your current schedule?')) {
            location.reload();
        }
    }

    // Form submission validation
    document.getElementById('scheduleForm').addEventListener('submit', function(e) {
        const checkedDays = document.querySelectorAll('.day-toggle:checked');
        
        if (checkedDays.length === 0) {
            e.preventDefault();
            alert('Please select at least one working day.');
            return;
        }

        let hasErrors = false;
        checkedDays.forEach(day => {
            const dayCard = day.closest('.schedule-day-card');
            const start = dayCard.querySelector('.start-time').value;
            const end = dayCard.querySelector('.end-time').value;
            
            if (!start || !end) {
                hasErrors = true;
                alert('Please set both start and end times for all selected days.');
            }
        });

        if (hasErrors) {
            e.preventDefault();
        }
    });

    // Initialize summary on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateSummary();
    });

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