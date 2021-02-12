<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    protected $fillable = [
        "employee_id",
        "month",
        "year",
        "salary"
    ];
}