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

    public function company() {
        return $this->belongsTo("App\Models\Company", "company_id");
    }
}
