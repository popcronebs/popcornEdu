<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sessions extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'login_type',
        'ip_address',
        'user_agent',
        'payload',
        'session_id',
        'last_activity'
    ];

    protected $casts = [
        'last_activity' => 'integer',
    ];
} 