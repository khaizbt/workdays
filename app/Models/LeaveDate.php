<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeaveDate extends Model
{
        protected $fillable = [
            "leave_id",
            "date"
        ];

        public function leave() {
            return $this->belongsTo("App\Models\Holiday");
        }
}
