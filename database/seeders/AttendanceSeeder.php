<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = Employee::all();
        foreach ($employees as $employee) {
            for ($i = 0; $i < 100; $i++) {
                Attendance::create([
                    'employee_id' => $employee->id,
                    'date' => now()->subDays(rand(1, 100)),
                    'status' => ['present', 'absent', 'leave'][rand(0, 2)],
                ]);
            }
        }

    }
}
