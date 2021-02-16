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

    public function holiday(){
        return $this->hasMany('App\Models\Holiday', 'employee_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function ovense() {
        return $this->hasMany("App\Models\Ovense", "employee_id");
    }

    public function company() {
        return $this->belongsTo("App\Models\Company", "company_id");
    }

    public function holiday_paid() {
        return $this->hasMany('App\Models\Holiday', 'employee_id')->whereNotNull("charge");
    }

    public function salary_cut(){
        return $this->hasMany('App\Models\SalaryCuts', "employee_id")->where("status", 0);
    }
}
