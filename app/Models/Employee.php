<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable =[
        'name',
        'email',
        'phone',
        'job_title',
        'salary'
    ];
    public function loans()
    {
        return $this->hasMany(Loan::class); // Each employee can have many loans
    }
    public function attendances()
    {
        return $this->hasMany(Attendance::class); // Define the relationship
    }
}
