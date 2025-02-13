<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Console\Presets\React;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EvaluationMtController extends Controller
{
    // 평가관리
    public function list(){
        $parent_seq = session()->get('parent_seq');
        $student = \App\Student::where('parent_seq', $parent_seq)->first();
        $main_code = $student->main_code;

        // 과목
        $subject_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'subject')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();


        // :내용영역
        $content_area_codes = \App\Code::
            select(
                'codes.*',
                'code_pt.function_code as pt_function_code'
            )
            ->leftJoin('codes as code_pt', 'code_pt.id', '=', 'codes.code_pt')
            ->where('codes.code_category', 'content_area')
            ->where('codes.code_step', 2)
            ->get()->groupBy('pt_function_code');

        // 인지영역
        $cognitive_area_codes = \App\Code::
            select(
                'codes.*',
                'code_pt.function_code as pt_function_code'
            )
            ->leftJoin('codes as code_pt', 'code_pt.id', '=', 'codes.code_pt')
            ->where('codes.code_category', 'cognitive_area')
            ->where('codes.code_step', 2)
            ->get()->groupBy('pt_function_code');

        // 평가분류
        $evaluation_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'evaluation')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        return view('parent.parent_evaluation_manage', [
            'student' => $student,
            'subject_codes' => $subject_codes,
            'content_area_codes' => $content_area_codes,
            'cognitive_area_codes' => $cognitive_area_codes,
            'evaluation_codes' => $evaluation_codes
        ]);
    }

    // 영역별 성적통계
    public function evalSubjectDetailSelect(Request $request){
        $student_seq = session()->get('student_seq');
        $subject_seq = $request->input('subject_seq');
        $month = $request->input('month');
        $start_date = '';
        $end_date = '';
        if($month){
            $start_date = date('Y-m-01', strtotime($month));
            $end_date = date('Y-m-t', strtotime($month));
        }

        // base query change laravel

        $base_query = \App\StudentExam::
        select(
            'student_exams.id as student_exam_seq'
        )
        ->leftJoin('exams', 'exams.id', '=', 'student_exams.exam_seq')
        // 학생조건
        ->where('student_exams.student_seq', $student_seq)
        // 완료날짜 조건
        ->where(function($query) use ($start_date, $end_date){
            $query->whereBetween('student_exams.complete_datetime', [$start_date.' 00:00:00', $end_date.' 23:59:59']);
            })
        // 과목 조건
        ->where('exams.subject_seq', $subject_seq);


        // 자녀 데이터
        $mine_data = \App\StudentExamResult::
        select(
            'content_area_seq',
            'cognitive_area_seq',
            DB::raw('count(*) as total_cnt'),
            DB::raw('sum(if(exam_status = "correct", 1, 0)) as correct_cnt'),
            DB::raw('sum(if(exam_status = "wrong", 1, 0)) as wrong_cnt')
        )
        ->leftJoin('exam_details', function($join){
            $join->on('exam_details.exam_seq', '=', 'student_exam_results.exam_seq')
                ->on('exam_details.exam_num', '=', 'student_exam_results.exam_num')
                ->on('exam_details.exam_type', '=', 'student_exam_results.exam_type');
            })
        ->where('student_seq', $student_seq)
        ->where('student_exam_results.exam_type', '<>', 'easy')
        ->whereIn('student_exam_seq', $base_query)
        ->groupBy('content_area_seq', 'cognitive_area_seq')
        ->get();

        // 또래 데이터
        $myage_data = \App\StudentExamResult::
        select(
            'content_area_seq',
            'cognitive_area_seq',
            DB::raw('count(*) as total_cnt'),
            DB::raw('sum(if(exam_status = "correct", 1, 0)) as correct_cnt'),
            DB::raw('sum(if(exam_status = "wrong", 1, 0)) as wrong_cnt')
        )
        ->leftJoin('exam_details', function($join){
            $join->on('exam_details.exam_seq', '=', 'student_exam_results.exam_seq')
                ->on('exam_details.exam_num', '=', 'student_exam_results.exam_num')
                ->on('exam_details.exam_type', '=', 'student_exam_results.exam_type');
            })
        ->whereIn('student_exam_seq', $base_query)
        ->where('student_exam_results.exam_type', '<>', 'easy')
        ->groupBy('content_area_seq', 'cognitive_area_seq')
        ->get();

        // 상단 데이터
        // NOTE: 만약 여러개일때,이중에 무엇을써야하나? 알수없음.
        // 뭔가 평가분류, 단원이 추가되면 이부분을 더 추가.
        $base_data = $base_query->addSelect('exams.exam_title')->get();

        // 결과
        $result['resultCode'] = 'success';
        $result['base_data'] = $base_data;
        $result['mine_data'] = $mine_data;
        $result['myage_data'] = $myage_data;
        return response()->json($result);
    }

    // 해달 월에 각기 수치 가져오기.
    public function evalExamTotalCntSelect(Request $request){
        $student_seq = session()->get('student_seq');
        $month = $request->input('month');
        $start_date = '';
        $end_date = '';
        if($month){
            $start_date = date('Y-m-01', strtotime($month));
            $end_date = date('Y-m-t', strtotime($month));
        }

        // 자녀의 나이(또래정보 가져오는데 필요)
        $my_grade = \App\Student::where('id', $student_seq)->value('grade');
        $base_query = $this->getBaseQuery($student_seq, $start_date, $end_date);

        // 해당 일수에 문제개수와, 정답 개수 가져오기.
        $total_data = \App\StudentExamResult::
        select(
            DB::raw('count(*) as total_cnt'),
            DB::raw('sum(if(exam_status = "correct", 1, 0)) as correct_cnt'),
            DB::raw('sum(if(exam_status = "wrong", 1, 0)) as wrong_cnt')
        )
        ->whereIn('student_exam_seq', $base_query)
        ->where('student_seq', $student_seq)
        ->first();

        // NOTE: 학습관리의 단원평가가 아닌 메뉴에서 단원평가를 통해
        // 직접적으로 시험을 치면 student-lecture-details 등에 남지 않기 때문에 수정.
        // student_exams 테이블을 추가해서 확인하는 방법으로 변경됨.

        // 자녀의 전체 평가 응시 횟수
        $query1 = \App\StudentExam::
        select(
            DB::raw('count(*) as complete_sum')
        )
        ->where('student_seq', $student_seq)
        ->whereBetween('complete_datetime', [$start_date.' 00:00:00', $end_date.' 23:59:59'])
        ->where('is_complete', 'Y')
        ->first();


        $query2 = \App\StudentExam::
        select(
            DB::raw('count(*) as complete_sum')
        )
        ->leftJoin('students', 'students.id', '=', 'student_exams.student_seq')
        ->whereBetween('complete_datetime', [$start_date.' 00:00:00', $end_date.' 23:59:59'])
        ->where('is_complete', 'Y')
        ->first();


        // 지난달 내자녀
        $prev_start_date = date('Y-m-01', strtotime($month.' -1 month'));
        $prev_end_date = date('Y-m-t', strtotime($month.' -1 month'));
        $query3 = \App\StudentExam::
        select(
            DB::raw('count(*) as complete_sum')
        )
        ->where('student_seq', $student_seq)
        ->whereBetween('complete_datetime', [$prev_start_date.' 00:00:00', $prev_end_date.' 23:59:59'])
        ->where('is_complete', 'Y')
        ->first();

        $my_total_cnt = $query1->complete_sum;
        $myage_total_cnt = $query2->complete_sum;
        $prev_my_total_cnt = $query3->complete_sum;

        // NOTE: 만약 문제타입 normal 만이 아니라 다른 것도 한다면 조건을 풀것.
        // 자녀의 전체 점수 평균(normal 만 계산한다.)
        $total_average = \App\StudentExamResult::
        select(
            DB::raw('count(*) as total_cnt'),
            DB::raw('sum(if(exam_status = "correct", 1, 0)) as correct_cnt'),
            DB::raw('sum(if(exam_status = "wrong", 1, 0)) as wrong_cnt')
        )
        ->whereIn('student_exam_seq', $base_query)
        ->where('student_seq', $student_seq)
        ->where('exam_type', 'normal')
        ->first();

        $myage_base_query = $this->getBaseQuery('', $start_date, $end_date);
        $myage_average = \App\StudentExamResult::
        select(
            DB::raw('count(*) as total_cnt'),
            DB::raw('sum(if(exam_status = "correct", 1, 0)) as correct_cnt'),
            DB::raw('sum(if(exam_status = "wrong", 1, 0)) as wrong_cnt')
        )
        ->leftJoin('students', 'students.id', '=', 'student_exam_results.student_seq')
        ->whereIn('student_exam_seq', $myage_base_query)
        ->where('exam_type', 'normal')
        ->where('students.grade', $my_grade)
        ->first();

        $prev_base_query = $this->getBaseQuery($student_seq, $prev_start_date, $prev_end_date);
        $prev_my_average = \App\StudentExamResult::
        select(
            DB::raw('count(*) as total_cnt'),
            DB::raw('sum(if(exam_status = "correct", 1, 0)) as correct_cnt'),
            DB::raw('sum(if(exam_status = "wrong", 1, 0)) as wrong_cnt')
        )
        ->leftJoin('students', 'students.id', '=', 'student_exam_results.student_seq')
        ->whereIn('student_exam_seq', $prev_base_query)
        ->where('student_seq', $student_seq)
        ->where('exam_type', 'normal')
        ->first();



        // 결과
        $result['resultCode'] = 'success';
        $result['total_data'] = $total_data;
        $result['my_total_cnt'] = $my_total_cnt;
        $result['myage_total_cnt'] = $myage_total_cnt;
        $result['prev_my_total_cnt'] = $prev_my_total_cnt;
        $result['total_average'] = $total_average;
        $result['myage_average'] = $myage_average;
        $result['prev_my_average'] = $prev_my_average;
        $result['a'] = $prev_start_date;
        $result['b'] = $prev_end_date;

        return response()->json($result);
    }

    // 베이스 쿼리.
    private function getBaseQuery($student_seq, $start_date, $end_date){
        $base_query = \App\StudentExam::
        select(
            'id as student_exam_seq'
        )
        ->where('is_complete', 'Y');

        // 학생조건
        if($student_seq && $student_seq != '')
            $base_query = $base_query->where('student_seq', $student_seq);

        // 완료날짜 조건 만약 날짜 조건이 아니라, 전체이면 조건 삭제 조치
        if($start_date && $end_date){
            $base_query = $base_query-> whereBetween('complete_datetime', [$start_date.' 00:00:00', $end_date.' 23:59:59']);
        }
        return $base_query;
    }

    // 선생님 평가 불러오기.
    public function evalTeacherEvaluationSelect(Request $request){
        $student_seq = session()->get('student_seq');
        $month = $request->input('month');
        $start_date = date('Y-m-01', strtotime($month));
        $end_date = date('Y-m-t', strtotime($month));

        $teacher_evaluation = \App\LearningLog::select(
            'learning_logs.*',
            'teachers.profile_img_path',
            'learning_log_details.log_content'
        )
            ->leftJoin('learning_log_details', 'learning_logs.id', '=', 'learning_log_details.log_seq')
            ->leftJoin('teachers', 'teachers.id', '=', 'learning_logs.teach_seq')
            ->whereBetween('log_date', [$start_date, $end_date])
            ->where('learning_log_details.log_type', 'etc_contents')
            ->where('learning_logs.student_seq', $student_seq)->get();
        // 결과
        $result['resultCode'] = 'success';
        $result['teacher_evaluation'] = $teacher_evaluation;

        return response()->json($result);
    }
}
