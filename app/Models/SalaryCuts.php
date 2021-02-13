<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryCuts extends Model
{
    protected $fillable = [
        "employee_id",
        "cuts_name",
        "notes",
        "image",
        "value",
        "status"
    ];

    public function employee() {
        return $this->belongsTo("App\Models\Employee", "employee_id");
    }
}
