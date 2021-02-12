<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $keyType = 'integer';

    protected $fillable = ['id_user', 'name', 'logo', 'date_salary', 'number_leave', 'maximum_leave', 'date_salary', 'work_holiday'];

    public function user(){
        return $this->belongsTo('App\User', 'id_user');
    }

    public function employee(){
        return $this->hasOne('App\Models\Employee', 'company_id');
    }

    public function work_days() {
        return $this->hasMany("App\Models\WorkDay", 'id_company');
    }
}
