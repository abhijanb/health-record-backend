<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthRecordComment extends Model
{
    //
    protected $fillable = [
        'user_id',
        'record_id',
        'comment',
        'created_at',
        'updated_at',
    ];
}
