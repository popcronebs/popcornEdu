<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    // public $timestamps = false;
    // const CREATED_AT = 'reg_date';
    // const UPDATED_AT = 'modify_date';
    // protected $primaryKey = 'seq';
    protected $fillable = ['id', 'group_type', 'group_name', 'sq', 'is_use', 'remark', 'first_page', 'group_code', 'main_code', 'group_type2', 'created_id', 'updated_id'];
}
