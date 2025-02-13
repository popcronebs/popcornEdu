<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    protected $fillable = [
        'code_name',
        'code_idx',
        'code_pt',
        'code_step',
        'code_category',
        'open_size',
        'is_use',
        'main_code',
        'group_seq',
        'function_code'
    ];   
}
