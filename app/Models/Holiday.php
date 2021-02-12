<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = [
        "leave_name",
        "employee_id",
        "status",
        "charge",
        "date_start",
        "date_end",
        "is_approved"
    ];

    public function employee() {
        return $this->belongsTo('App\Models\Employee', 'employee_id');
    }

    public function leave_date(){
        return $this->hasOne("App\Models\LeaveDate", "leave_id");
    }

    public function leave_date_all(){
        return $this->hasMany("App\Models\LeaveDate", "leave_id");
    }

}
