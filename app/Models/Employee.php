<?php

namespace App\Models;

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

    public function employee(){
        return $this->belongsTo('App\Models\Holiday', 'employee_id');
    }

    public function company() {
        return $this->belongsTo("App\Models\Company", "company_id");
    }
}
