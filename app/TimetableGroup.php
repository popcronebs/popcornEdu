<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimetableGroup extends Model
{
    // updateOrCreate 변수 추가
    protected $fillable = ['timetable_group_subject', 'timetable_group_title', 'timetable_group_days', 'grade_seq', 'main_code'];
}
