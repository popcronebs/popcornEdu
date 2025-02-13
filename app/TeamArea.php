<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamArea extends Model
{
    protected $fillable = [
        'main_code',
        'team_code',
        'tarea_sido',
        'tarea_gu',
        'tarea_dong',
    ];
}
