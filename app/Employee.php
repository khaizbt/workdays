<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        "name",
        "company_id",
        "user_id",
        "position",
        "status", 
        "salary"
    ];
}
