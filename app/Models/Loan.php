<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $fillable =[
        'employee_id',
        'amount',
        'remaining',
        'monthly_repayment',
        'start_date',
        'end_date'
    ];
    public function deductions()
    {
        return $this->hasMany(LoanDeduction::class); // Define the relationship
    }
}
