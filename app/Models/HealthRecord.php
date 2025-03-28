<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    //
    protected $fillable = [
        'user_id',
       
            "record_type",
            "record_details",
            "record_file",
            "date_recorded",
            "visibility",
"value"          
          
    ];
}
