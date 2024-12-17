<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Loan;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return view('EmployeeList', compact('employees'));
    }

    public function show($id)
    {
        $employee = Employee::with(['attendances', 'loans.deductions'])->findOrFail($id);
        $loans = Loan::where('employee_id', $id)->get();
        $attendance_summary = $this->calculateAttendanceSummary($employee);
        $salary_details = $this->calculateSalaryDetails($employee);
        // dd($attendance_Summary);
        return view('EmployeeDetails', compact('employee', 'attendanceSummary', 'salary_details', 'loans'));

    }
    private function calculateAttendanceSummary($employee)
    {
        $attendance_records = $employee->attendances;
        $summary = [
            'total_present' => $attendance_records->where('status', 'present')->count(),
            'total_absent' => $attendance_records->where('status', 'absent')->count(),
            'total_leaves' => $attendance_records->where('status', 'leave')->count(),
            'monthly_summary' => $attendance_records->groupBy(function ($record) {
                return $record->date->format('Y-m');
            })->map(function ($records) {
                return [
                    'present' => $records->where('status', 'present')->count(),
                    'absent' => $records->where('status', 'absent')->count(),
                    'leaves' => $records->where('status', 'leave')->count(),
                ];
            }),
        ];
        return $summary;
    }

    private function calculateSalaryDetails($employee)
    {
        $base_salary = $employee->salary;
        $attendance_summary = $this->calculateAttendanceSummary($employee);

        $loan_deductions = $employee->loans->sum('monthly_repayment');
        $absence_deductions = $attendance_summary['total_absent'] * 50;

        $total_deductions = $loan_deductions + $absence_deductions;

        $net_salary = $base_salary - $total_deductions;

        return [
            'base_salary' => $base_salary,
            'deductions' => [
                'loan_repayments' => $loan_deductions,
                'absences' => $absence_deductions,
            ],
            'total_deductions' => $total_deductions,
            'net_salary' => $net_salary,
        ];
    }



}
