<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreativeExperienceMtController extends Controller
{
    //
    public function list(Request $request){
        return view('student.student_creative_experience');
    }
}
