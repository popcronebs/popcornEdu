<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookMtController extends Controller
{
    public function list(Request $request){
        $main_code = $request->session()->get('main_code');

        // 학년 분류 가져오기
        $grade_codes = 
        \App\Code::where('code_category', 'grade')
        ->where('code_step', '=', 1)
        ->where('main_code', '=', $main_code)->get();

        return view('student.student_book', ['grade_codes' => $grade_codes]);
    }
}
