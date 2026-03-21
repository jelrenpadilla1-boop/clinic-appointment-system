{{-- resources/views/admin/reports/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Reports Dashboard</h2>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-day me-2"></i>Daily Reports</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">View appointment reports for a specific day with detailed statistics and summaries.</p>
                    
                    <form action="{{ route('admin.reports.daily') }}" method="GET" class="mt-3">
                        <div class="mb-3">
                            <label for="date" class="form-label">Select Date</label>
                            <input type="date" class="form-control" id="date" name="date" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-eye me-2"></i>View Daily Report
                            </button>
                            <button type="submit" name="export" value="pdf" class="btn btn-success">
                                <i class="fas fa-file-pdf me-2"></i>Export as PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-calendar-alt me-2"></i>Monthly Reports</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Comprehensive monthly reports with daily breakdowns and doctor performance analysis.</p>
                    
                    <form action="{{ route('admin.reports.monthly') }}" method="GET" class="mt-3">
                        <div class="mb-3">
                            <label for="month" class="form-label">Select Month</label>
                            <input type="month" class="form-control" id="month" name="month" 
                                   value="{{ date('Y-m') }}" required>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-eye me-2"></i>View Monthly Report
                            </button>
                            <button type="submit" name="export" value="pdf" class="btn btn-success">
                                <i class="fas fa-file-pdf me-2"></i>Export as PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Quick Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $today = \Carbon\Carbon::today();
                            $thisMonth = \Carbon\Carbon::now();
                            
                            $todayAppointments = \App\Models\Appointment::whereDate('appointment_date', $today)->count();
                            $todayCompleted = \App\Models\Appointment::whereDate('appointment_date', $today)
                                ->where('status', 'completed')->count();
                            
                            $monthAppointments = \App\Models\Appointment::whereYear('appointment_date', $thisMonth->year)
                                ->whereMonth('appointment_date', $thisMonth->month)->count();
                            $monthCompleted = \App\Models\Appointment::whereYear('appointment_date', $thisMonth->year)
                                ->whereMonth('appointment_date', $thisMonth->month)
                                ->where('status', 'completed')->count();
                        @endphp
                        
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h3 class="text-primary">{{ $todayAppointments }}</h3>
                                    <p class="mb-0">Today's Total</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h3 class="text-success">{{ $todayCompleted }}</h3>
                                    <p class="mb-0">Today's Completed</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h3 class="text-info">{{ $monthAppointments }}</h3>
                                    <p class="mb-0">This Month Total</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h3 class="text-warning">{{ $monthCompleted }}</h3>
                                    <p class="mb-0">This Month Completed</p>
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