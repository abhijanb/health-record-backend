<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthRecordHistory extends Model
{
    //

    protected $table = 'health_record_history';

    protected $fillable = [
        'user_id',
        'name',
        'value',
        'visibility',
        'record_file',
        'record_type',
        'record_details',
        'changed_at'
    ];
    public $timestamps = false;

}
