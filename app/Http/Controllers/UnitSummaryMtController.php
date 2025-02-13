<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UnitSummaryMtController extends Controller
{
    //
    public function list(Request $request)
    {
        $student_seq = $request->session()->get('student_seq');
        if ($request->input('student_seq') != null) {
            $student_seq = $request->input('student_seq');
        }
        $main_code = \App\Student::where('id', $student_seq)->value('main_code');

        // 학년
        $grade_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'grade')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        //과목
        $subject_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'subject')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        //학기
        $semester_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'semester')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        return view('student.student_unit_summary', [
            'grade_codes' => $grade_codes,
            'subject_codes' => $subject_codes,
            'semester_codes' => $semester_codes
        ]);
    }
}
