<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class 
Team extends Model
{
  protected $fillable = [
      'main_code',
      'team_code',
      'region_seq',
      'main_store',
      'team_name',
      'team_phone',
      'team_address',
      'team_type',
      'created_at',
      'updated_at',
      'team_pl_date',
      'team_status',
      'device_type',
      'team_tel',
      'sms_id',
      'team_business',
      'team_ledger',
      'team_st_cnt',
      'team_tr_cnt',
      'team_pt_cnt',
      'team_kind'
  ];
}
