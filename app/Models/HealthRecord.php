<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    //
    protected $fillable = [
        'user_id',
        "name",
       
            "record_type",
            "record_details",
            "record_file",
            "date_recorded",
            "visibility",
"value"          
          
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
