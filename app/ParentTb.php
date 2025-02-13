<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParentTb extends Model
{
    //
    protected $table = 'parents';
    protected $fillable = ['main_code', 'parent_id', 'parent_name', 'parent_pw', 'parent_phone', 'parent_email', 'rrn', 'group_seq'];
}
