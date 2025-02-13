<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketDetail extends Model
{
    //
    protected $primaryKey = 'seq';
    protected $fillable = ['ticket_seq', 'student_seq', 'start_date', 'end_date'];
}
