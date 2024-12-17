<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Loan;
use App\Models\LoanDeduction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        foreach ($employees as $employee) {
            $monthly_repayment = 1000;

            for ($i = 0; $i < 5; $i++) {
                $loan = Loan::create([
                    'employee_id' => $employee->id,
                    'amount' => rand(5000, 30000),
                    'remaining' => rand(1000, 10000),
                    'monthly_repayment' => $monthly_repayment,
                    'start_date' => now()->subMonths(rand(1, 6)),
                    'end_date' => now()->addMonths(rand(1, 6)),
                ]);
                $monthly_repayment +=1000;

                for ($j = 0; $j < rand(2, 6); $j++) {
                    LoanDeduction::create([
                        'loan_id' => $loan->id,
                        'employee_id' => $employee->id,
                        'amount' => $loan->amount / rand(5, 9),
                    ]);
                }
            }
        }

    }
}
