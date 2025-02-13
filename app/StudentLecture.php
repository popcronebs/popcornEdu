<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StudentLecture extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
        'student_seq',
        'lecture_seq',
        'start_lecture_detail_seq',
        'is_sun',
        'is_mon',
        'is_tue',
        'is_wed',
        'is_thu',
        'is_fri',
        'is_sat'
    ];
}
