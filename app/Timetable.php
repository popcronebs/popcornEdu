<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    //
    protected $fillable = [
        'subject_seq',
        'series_seq',
        'publisher_seq',
        'lecture_seq',
        'lecture_name',
        'start_lecture_detail_seq',
        'timetable_days',
        'timetable_start_date',
        'timetable_group_seq',
        'main_code',
    ];
}
