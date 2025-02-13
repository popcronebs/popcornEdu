<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudyTime extends Model
{
    protected $fillable = [
        'student_seq', 'select_date', 'select_time', 'is_repeat'
    ];
}
