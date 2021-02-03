<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ovense extends Model
{
    protected $fillable = [
        "ovense_name",
        "pinalty_type",
        "date",
        "punishment",
        "employee_id"
    ];

    public function employee() {
        return $this->belongsTo("App\Models\Employee", "employee_id");
    }
}
