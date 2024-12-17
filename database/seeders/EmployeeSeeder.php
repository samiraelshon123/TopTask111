<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $salary = 1000;
        for($i =1; $i<=10; $i++){
            Employee::create([
                'name' => 'employee'.$i,
                'email' => 'employee'.$i.'@gmail.com',
                'phone' => '0109901356'.$i,
                'salary' => $salary
            ]);
            $salary+=1000;
        }

    }
}
