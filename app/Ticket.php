<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    //
    protected $primaryKey = 'seq';
    protected $fillable = ['ticket_name'];
}
