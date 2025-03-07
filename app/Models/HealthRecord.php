<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthRecord extends Model
{
    //
    protected $table = 'healthRecords';
    protected $fillable = ['user_id','record_type','value','file_path','recorded_at'];
}
