<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $keyType = 'integer';

    protected $fillable = ['id_user', 'name', 'logo'];

    public function user(){
        return $this->belongsTo('App\User', 'id_user');
    }

    public function employee(){
        return $this->hasOne('App\Models\Employee', 'company_id');
    }
}
