<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    // protected $primaryKey = 'region_seq';
    //
    protected $fillable = [
        'main_code',
        'region_name',
        'general_teach_seq',
        'general_group_seq',
        'created_at',
        'updated_at',
        'area'
    ];
}
