<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AllStudyMtController extends Controller
{
    public function list(Request $request){
        $student_seq = $request->session()->get('student_seq');
        if($request->input('student_seq') != null){
            $student_seq = $request->input('student_seq');
        }
        $main_code = \App\Student::where('id', $student_seq)->value('main_code');
        $grade_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'grade')->where('code_step', '=', 1)
        ->orderBy('code_idx', 'asc')
        ->get();


        return view('student.student_all_study', ['grade_codes' => $grade_codes]);
    }
}
