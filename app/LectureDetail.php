<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LectureDetail extends Model
{
    protected $fillable = [
        'main_code', 'lecture_seq', 'lecture_detail_name', 'lecture_detail_description', 'lecture_detail_time', 'is_use', 'idx'
    ];

}
