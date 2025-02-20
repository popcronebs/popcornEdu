<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnitTestMtController extends Controller
{
    public function list(Request $request){
        $student_seq = $request->session()->get('student_seq');
        if($request->input('student_seq') != null){
            $student_seq = $request->input('student_seq');
        }
        $student = \App\Student::where('id', $student_seq)->first();
        $student_grade = $student->grade;
        $main_code = \App\Student::where('id', $student_seq)->value('main_code');
        // 과목 불러오기.
        $subject_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'subject')->where('code_step', '=', 1)
        ->orderBy('code_idx', 'asc')
        ->get();

        // 한자 과목 불러오기
        $hanja_subject_codes = \App\Code::where('main_code', $main_code)
            ->whereIn('code_pt',  function($query){
                $query->select('id')->from('codes')->where('code_category', 'subject')->where('function_code', 'subject_hanja');
            })
            ->where('code_step', '=', 2)
            ->orderBy('code_idx', 'asc')
            ->get();


        // 학년
        $grade_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'grade')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        //학기
        $semester_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'semester')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        // 평가분류
        $evaluation_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'evaluation')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        $exam_seq = \App\ExamDetail::distinct()->where('exam_type', 'exam')->pluck('exam_seq');
        $exam_seq = \App\Exam::whereIn('id', $exam_seq)->get();

        return view('student.student_unit_test', [
            'subject_codes' => $subject_codes,
            "grade_codes" => $grade_codes,
            "semester_codes" => $semester_codes,
            'evaluation_codes' => $evaluation_codes,
            'student_grade' => $student_grade,
            'exam_seq' => $exam_seq,
            'hanja_subject_codes' => $hanja_subject_codes
        ]);
    }

    // 평가 시험 불러오기.
    public function examSelect(Request $request){
        $main_code = session()->get('main_code');
        $student_seq = session()->get('student_seq');
        $subject_seq = $request->input('subject_seq');
        $grade_seq = $request->input('grade_seq');
        $semester_seq = $request->input('semester_seq');
        $evaluation_seq = $request->input('evaluation_seq');
        $subject_type = $request->input('subject_type');

        $exam_seq = \App\ExamDetail::distinct()->where('exam_type', 'exam')->pluck('exam_seq');
        $exam_seq = \App\Exam::whereIn('exams.id', $exam_seq)
        ->leftJoin('codes', 'exams.subject_seq', '=', 'codes.id')
        ->leftJoin('codes as grade', 'exams.grade_seq', '=', 'grade.id')
        ->leftJoin('codes as semester', 'exams.semester_seq', '=', 'semester.id')
        ->leftJoin('codes as evaluation', 'exams.evaluation_seq', '=', 'evaluation.id')
        ->select('exams.*', 'codes.code_name as subject_name', 'grade.code_name as grade_name', 'semester.code_name as semester_name', 'evaluation.code_name as evaluation_name');
        if($subject_type == 'subject'){
            $exam_seq = $exam_seq->where('exams.subject_seq', $subject_seq);
        }else if($subject_type == 'subject2'){
            $exam_seq = $exam_seq->where('exams.subject_seq2', $subject_seq);
        }
        $exam_seq = $exam_seq->get();

        // 조건에 시험 불러오기.
        $exams = \App\Exam::where('exams.main_code', $main_code)
            ->where('grade_seq', $grade_seq)
            ->where('evaluation_seq', $evaluation_seq)
            ->join('lecture_details', function($join){
                $join->on('exams.id', '=', 'lecture_details.lecture_exam_seq')
                ->where('lecture_details.lecture_detail_group', '>', 0)
                ->where('lecture_details.lecture_detail_type', 'unit_test');
            });
            if($subject_type == 'subject'){
                $exams = $exams->where('subject_seq', $subject_seq);
            }else if($subject_type == 'subject2'){
                $exams = $exams->where('subject_seq2', $subject_seq);
        }

        if($semester_seq != null && $semester_seq){
            $exams = $exams->where('semester_seq', $semester_seq);
        }
        $exams = $exams->get();

        // 위 조건 시험을 첬다면 점수 불러오기.
        $exam_seqs = $exams->pluck('id');
        $student_exams = \App\StudentExam::
            select (
                'student_exams.id',
                'student_exam_results.exam_seq',
                DB::raw("(sum(if(student_exam_results.exam_status = 'correct', 1, 0)) / sum(1)) * 100 as rate")
            )
            ->leftJoin('student_exam_results', 'student_exams.id', '=', 'student_exam_results.student_exam_seq')
            ->where('student_exams.student_seq', $student_seq)
            ->whereIn('student_exams.exam_seq', $exam_seqs)
            ->where('student_exams.exam_status', 'complete')
            ->where('student_exam_results.exam_type', 'normal') // 일반시험 만 체크.
            ->where('student_exams.lecture_detail_type', 'unitpage')

            ->groupBy('student_exams.id', 'student_exam_results.exam_seq');

        // $result['sql'] = $student_exams->toSql();
        // $result['bind'] = $student_exams->getBindings();
         $student_exams = $student_exams->get();

        // groupBy exam_seq, 높은 rate 순으로 정렬.
        $student_exams = $student_exams->sortByDesc('rate')->groupBy('exam_seq');

        // 결과
        $result['resultCode'] = 'success';
        $result['exams'] = $exams;
        $result['student_exams'] = $student_exams;
        $result['exam_seq'] = $exam_seq;
        return response()->json($result);
    }

    // 시험 중 null인 시험 삭제.
    public function examNullDelete(Request $request){
        $student_seq = session()->get('student_seq');
        $exam_seq = $request->input('exam_seq');

        $student_exams = \App\StudentExam::where('student_seq', $student_seq)
            ->where('exam_status', 'study')
            ->where('lecture_detail_type', 'unitpage')
            ->where('student_lecture_detail_seq', null)
            ->where('student_seq', $student_seq)
            ->where('exam_seq', $exam_seq)
            ->get();

        $student_exam_seqs = $student_exams->pluck('id');
        $student_exam_results = \App\StudentExam::whereIn('id', $student_exam_seqs)->delete();
        // 결과
        $result['resultCode'] = 'success';
        return response()->json($result);
    }
    //한자 급수시험 조회
    public function nationalExamSelect(Request $request){

        $exams = \App\CodeConnect::where('code_pt', 268)->get();
    }

    // 자유퀴즈 시작하기.
    public function freeQuiz(Request $request){
        $student_seq = session()->get('student_seq');
        $exam_seq = $request->input('exam_seq');

        $exam = \App\Exam::where('exams.id', $exam_seq)->leftJoin('codes', 'exams.subject_seq', '=', 'codes.id')->first();
        // $exam_detail = \App\ExamDetail::where('exam_seq', $exam_seq)->where('exam_type', 'exam')->get();
        $title = $request->input('title');

        $normals = \App\ExamDetail::where('exam_seq', $exam_seq)
            ->where('exam_type', 'normal')
            ->orderBy('exam_num')
            ->get()
            ->map(function($item) {
                $item->questions2 = str_replace('<br>', "\n", $item->questions2);
                return $item;
            });
        $similars = \App\ExamDetail::where('exam_seq', $exam_seq)
            ->where('exam_type', 'similar')
            ->orderBy('exam_num')
            ->get()
            ->map(function($item) {
                $item->questions2 = str_replace('<br>', "\n", $item->questions2);
                return $item;
            });
        $challenges = \App\ExamDetail::where('exam_seq', $exam_seq)
            ->where('exam_type', 'challenge')
            ->orderBy('exam_num')
            ->get()
            ->map(function($item) {
                $item->questions2 = str_replace('<br>', "\n", $item->questions2);
                return $item;
            });
        $challenge_similars = \App\ExamDetail::where('exam_seq', $exam_seq)
            ->where('exam_type', 'challenge_similar')
            ->orderBy('exam_num')
            ->get()
            ->map(function($item) {
                $item->questions2 = str_replace('<br>', "\n", $item->questions2);
                return $item;
            });

        // 이미지 및 동영상 파일 경로.
        $exam_uploadfiles = \App\ExamUploadfile::where('exam_seq', $exam_seq)
            ->get();

        return view('student.student_study_testQuiz')->with([
            'exam' => $exam,
            'title' => $title,
            'normals' => $normals,
            'similars' => $similars,
            'challenges' => $challenges,
            'challenge_similars' => $challenge_similars,
            'exam_uploadfiles' => $exam_uploadfiles,
        ]);
    }
}
