<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApiEmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return response()->json([
            'success' => true,
            'data' => $employees
        ], 200);
    }

    public function show($id)
    {
        $employee = Employee::with(['attendances', 'loans.deductions'])->findOrFail($id);
        $loan_details = $employee->loans->map(function ($loan) {
            return [
                'loan_amount' => $loan->amount,
                'monthly_repayment' => $loan->monthly_repayment,
                'remaining_balance' => $loan->amount - $loan->deductions->sum('amount')
            ];
        });
        $attendance_summary = $this->calculateAttendanceSummary($employee);
        $salary_details = $this->calculateSalaryDetails($employee);

        $data = [
            'employee' => $employee,
            'attendance_summary' => $attendance_summary,
            'loan_details' => $loan_details,
            'salary_details' => $salary_details
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);
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


    public function filter(Request $request)
    {
        $query = Employee::with(['attendances', 'loans']);

        // Filter by employee_id
        if ($request->has('employee_id')) {
            $query->where('id', $request->employee_id);
        }

        // Filter by date range
        if ($request->has(['fromDate', 'toDate'])) {
            $fromDate = Carbon::parse($request->fromDate)->startOfDay();
            $toDate = Carbon::parse($request->toDate)->endOfDay();

            $query->whereHas('attendances', function ($q) use ($fromDate, $toDate) {
                $q->whereBetween('date', [$fromDate, $toDate]);
            });
        }

        $employees = $query->get();

        return response()->json([
            'success' => true,
            'data' => $employees
        ], 200);
    }


}
