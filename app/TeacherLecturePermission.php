<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeacherLecturePermission extends Model
{
    protected $table = 'teacher_lectures_permissions';
    
    protected $fillable = [
        'id',
        'teacher_id',
        'code_id',
        'lectures_permissions'
    ];
} 