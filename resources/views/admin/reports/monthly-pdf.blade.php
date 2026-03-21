{{-- resources/views/admin/reports/monthly-pdf.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Report - {{ $month->format('Y-m') }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .summary { margin-bottom: 30px; }
        .summary table { width: 100%; border-collapse: collapse; }
        .summary td { padding: 10px; text-align: center; border: 1px solid #ddd; }
        .section { margin-top: 30px; }
        .section table { width: 100%; border-collapse: collapse; }
        .section th, .section td { padding: 8px; border: 1px solid #ddd; }
        .section th { background-color: #f2f2f2; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Monthly Appointment Report</h1>
        <h2>{{ $month->format('F Y') }}</h2>
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

    <div class="section">
        <h3>Daily Breakdown</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Confirmed</th>
                    <th>Completed</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dailyBreakdown as $date => $stats)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                    <td class="text-center">{{ $stats['total'] }}</td>
                    <td class="text-center">{{ $stats['confirmed'] }}</td>
                    <td class="text-center">{{ $stats['completed'] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>Doctor Performance</h3>
        <table>
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Total</th>
                    <th>Completed</th>
                    <th>Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($doctorPerformance as $doctorName => $stats)
                <tr>
                    <td>{{ $doctorName }}</td>
                    <td class="text-center">{{ $stats['total'] }}</td>
                    <td class="text-center">{{ $stats['completed'] }}</td>
                    <td class="text-center">
                        @if($stats['total'] > 0)
                            {{ round(($stats['completed'] / $stats['total']) * 100) }}%
                        @else
                            0%
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>All Appointments</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->appointment_date->format('M d, Y') }}</td>
                    <td>{{ $appointment->appointment_time->format('h:i A') }}</td>
                    <td>{{ $appointment->patient->name }}</td>
                    <td>Dr. {{ $appointment->doctor->user->name }}</td>
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