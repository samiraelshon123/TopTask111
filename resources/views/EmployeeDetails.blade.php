<h2>{{ $employee->name }}</h2>
<h3>Attendance Details</h3>
<table>
    <thead>
        <tr>
            <th>Month</th>
            <th>Attendances</th>
            <th>Absences</th>
            <th>Leaves</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($attendance_summary['monthly_summary'] as $month => $summary)
            <tr>
                <td>{{ $month }}  :</td>
                <td>{{ $summary['present'] }}</td>
                <td>{{ $summary['absent'] }}</td>
                <td>{{ $summary['leaves'] }}</td>
            </tr>
        @endforeach
        <tr>
                <th></th>
                <th>{{ $attendance_summary['total_present'] }}</th>
                <th>{{ $attendance_summary['total_absent'] }}</th>
                <th>{{ $attendance_summary['total_leaves'] }}</th>
            </tr>
    </tbody>
</table>

<h3>Loan Details</h3>
<table>
    <thead>
        <tr>
            <th>Amount</th>
            <th>Remaining</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($loans as $loan)
            <tr>
                <td>{{ $loan->amount }}</td>
                <td>{{ $loan->remaining }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<h3>Salary Details</h3>
<p>Total Salary: {{ $salary_details['base_salary'] }}</p>
<p>Total Deductions: {{ $salary_details['total_deductions'] }}</p>
<p>Net Salary: {{ $salary_details['net_salary'] }}</p>
