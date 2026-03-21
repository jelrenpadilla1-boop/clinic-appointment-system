{{-- resources/views/admin/schedules/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Doctor Schedules</h2>
        <a href="{{ route('admin.schedules.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add Schedule
        </a>
    </div>

    @foreach($doctors as $doctor)
    <div class="card mb-4">
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Dr. {{ $doctor->user->name }} - {{ $doctor->specialization->name }}</h5>
                <a href="{{ route('admin.schedules.create', ['doctor_id' => $doctor->id]) }}" class="btn btn-sm btn-success">
                    <i class="fas fa-plus"></i> Add Schedule for this Doctor
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($doctor->schedules->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Max Patients</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($doctor->schedules as $schedule)
                            <tr>
                                <td>{{ $schedule->day_name }}</td>
                                <td>{{ $schedule->start_time->format('h:i A') }}</td>
                                <td>{{ $schedule->end_time->format('h:i A') }}</td>
                                <td>{{ $schedule->max_patients }}</td>
                                <td>
                                    <a href="{{ route('admin.schedules.edit', $schedule) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">No schedules set for this doctor.</p>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection