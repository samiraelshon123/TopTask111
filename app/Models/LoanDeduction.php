<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanDeduction extends Model
{
    use HasFactory;
    protected $fillable =[
        'loan_id',
        'employee_id',
        'amount',
    ];
}
