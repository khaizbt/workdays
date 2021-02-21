<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        "company_id",
        "note",
        "event_name",
        "time",
        "place",
        "maps"
    ];

    public function company(){
        return $this->belongsTo("App\Models\Company", "company_id");
    }
}
