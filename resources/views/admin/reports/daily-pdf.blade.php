{{-- resources/views/admin/reports/daily-pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Report - {{ $date->format('Y-m-d') }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .summary { margin-bottom: 30px; }
        .summary table { width: 100%; border-collapse: collapse; }
        .summary td { padding: 10px; text-align: center; border: 1px solid #ddd; }
        .appointments table { width: 100%; border-collapse: collapse; }
        .appointments th, .appointments td { padding: 8px; border: 1px solid #ddd; }
        .appointments th { background-color: #f2f2f2; }
        .status-badge { padding: 3px 8px; border-radius: 3px; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Daily Appointment Report</h1>
        <h2>{{ $date->format('F d, Y') }}</h2>
    </div>

    <div class="summary">
        <table>
            <tr>
                <td style="background-color: #007bff; color: white;">
                    <h3>Total</h3>
                    <p style="font-size: 24px;">{{ $summary['total'] }}</p>
                </td>
                <td style="background-color: #28a745; color: white;">
                    <h3>Confirmed</h3>
                    <p style="font-size: 24px;">{{ $summary['confirmed'] }}</p>
                </td>
                <td style="background-color: #17a2b8; color: white;">
                    <h3>Completed</h3>
                    <p style="font-size: 24px;">{{ $summary['completed'] }}</p>
                </td>
                <td style="background-color: #ffc107; color: white;">
                    <h3>Pending</h3>
                    <p style="font-size: 24px;">{{ $summary['pending'] }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="appointments">
        <h3>Appointments List</h3>
        <table>
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                    <td>{{ $appointment->patient->name }}</td>
                    <td>Dr. {{ $appointment->doctor->user->name }}</td>
                    <td>{{ $appointment->doctor->specialization->name }}</td>
                    <td class="text-center">{{ ucfirst($appointment->status) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="margin-top: 30px; text-align: center; color: #666;">
        <p>Generated on {{ now()->format('F d, Y h:i A') }}</p>
    </div>
</body>
</html>