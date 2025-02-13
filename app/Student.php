<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['area', 'teach_seq', 'school_code', 'team_code', 'parent_seq', 'main_code', 'class_name', 'student_id', 'student_name', 'student_pw', 'student_phone', 'student_email', 'school_name', 'school_name', 'grade', 'group_seq', 'rrn'];
}
