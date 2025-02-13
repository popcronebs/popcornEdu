<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeachPayListMtController extends Controller
{
    // list
    public function list(){
        return view('teacher.teacher_paylist');
    }
    
    // 결제 상세
    public function detail(){
        return view('teacher.teacher_paylist_detail');
    }
}
