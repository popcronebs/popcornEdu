<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlarmSetting extends Model
{
    //
    protected $fillable = [
        'user_type',
        'user_seq',
        'alarm_name',
        'alarm_type',
        'alarm_value',
        'alarm_group'
    ];
}
