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
}
