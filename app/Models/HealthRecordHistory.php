<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthRecordHistory extends Model
{
    //
    protected $fillable = [
        'record_id',
        'user_id',
        'comment',
        'created_at',
        'updated_at',
    ];

}
