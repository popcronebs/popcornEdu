<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MyScoreMtController extends Controller
{
    // 내 성적표
    public function list(Request $request){
        $student_seq = $request->session()->get('student_seq');
        if($request->input('student_seq') != null){
            $student_seq = $request->input('student_seq');
        }
        $main_code = \App\Student::where('id', $student_seq)->value('main_code');
        $subject_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'subject')->where('code_step', '=', 1)
        ->orderBy('code_idx', 'asc')
        ->get();

        // tofh request 를 만든다.
        $make_request = new Request();
        $make_request->merge([
            'month' => date('Y-m-d'),
        ]);
        $middle_data = $this->examMonthGoodOrNotSelect($make_request);


        // 평가분류
        $evaluation_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'evaluation')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();
        // 한자 키 가져오기.
        $hanja_code = \App\Code::where('main_code', $main_code)->where('code_category', 'subject')->where('function_code', 'subject_hanja')->first();

        return view('student.student_my_score', [
            'subject_codes' => $subject_codes,
            'middle_data' => $middle_data,
            'evaluation_codes' => $evaluation_codes,
            'hanja_code' => $hanja_code
        ]);
    }

    // 월별 , 과목 자잘못 과목 가져오기.
    public function examMonthGoodOrNotSelect(Request $request){
        // 내부에 student_seq 있음
        $exams1 = $this->examSelect($request);
        // month + 1 개월
        $request->merge(['month' => date('Y-m-d', strtotime($request->input('month').' -1 month'))]);
        $exams2 = $this->examSelect($request);
        $exams2 = $exams2->groupBy('subject_seq');

        $good_subject_exam = $exams1->sortByDesc(function($exam) {
            return $exam->total_count == 0 ? 0 : $exam->correct_count / $exam->total_count * 100;
        })->take(1);

        $notgood_subject_exam = $exams1->sortBy(function($exam) {
            return $exam->total_count == 0 ? 0 : $exam->correct_count / $exam->total_count * 100;
        })->take(1);

        foreach($exams1 as $exam){
            $subject_seq = $exam->subject_seq;
            $exam->prev_total_count = $exams2[$subject_seq][0]->total_count ?? 0;
            $exam->prev_correct_count = $exams2[$subject_seq][0]->correct_count ?? 0;
        }
        // 점수가 저번달 대비($exams2)제일 오른 과목 찾기
        $best_exam = null;
        $worst_exam = null;
        foreach($exams1 as $exam){
            if($best_exam === null ||
                ($best_exam->prev_total_count != 0 && $best_exam->prev_correct_count / $best_exam->prev_total_count < $exam->correct_count / $exam->total_count)){
                $best_exam = $exam;
            }
            if($worst_exam === null
                || ($worst_exam->prev_total_count != 0 && $worst_exam->prev_correct_count / $worst_exam->prev_total_count > $exam->correct_count / $exam->total_count)){
                $worst_exam = $exam;
            }
        }

        $result['resultCode'] = 'success';
        $result['good_subject_exam'] = $good_subject_exam;
        $result['notgood_subject_exam'] = $notgood_subject_exam;
        $result['best_exam'] = $best_exam;
        $result['worst_exam'] = $worst_exam;
        return $result;
    }
    // 성적표 불러오기
    private function examSelect(Request $request){
        $student_seq = session()->get('student_seq');
        if(session()->get('login_type') == 'teacher'){
            $student_seq = $request->input('student_seq');
        }
        $evaluation_seq = $request->input('evaluation_seq');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        // or
        $month = $request->input('month');
        $subject_seq = $request->input('subject_seq');

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        // 검색 시작, 끝 날짜가 없을때, 월로 검색
        if($month && strlen($start_date) < 1){
            $start_date = date('Y-m-01', strtotime($month));
            $end_date = date('Y-m-t', strtotime($month));
        }


        $student_exam_results = \App\StudentExamResult::select(
            'student_exam_results.student_seq',
            'subject_seq',
            'subject_codes.code_name as subject_name',
            'subject_codes.function_code',
            // 'exam_seq',
            // 'student_lecture_detail_seq',
            DB::raw('count(*) as total_count'),
            DB::raw('sum(if(student_exam_results.exam_status = "correct", 1, 0 )) as correct_count'),
            DB::raw('max(student_exams.complete_datetime) as complete_datetime')
        )
            ->leftJoin('exams', 'exams.id', '=', 'student_exam_results.exam_seq')
            ->leftJoin('codes as subject_codes', 'subject_codes.id', '=', 'exams.subject_seq')
            ->leftJoin('student_exams', 'student_exams.id', '=', 'student_exam_results.student_exam_seq')
            ->where('student_exam_results.student_seq', $student_seq)
            ->where('exam_type', 'normal')
            ->whereBetween('student_exams.complete_datetime', [$start_date.' 00:00:00', $end_date.' 23:59:59'])
            // ->groupBy('student_exam_results.student_seq', 'exam_seq', 'student_lecture_detail_seq')
            ->groupBy('student_exam_results.student_seq', 'subject_seq' );

            if($evaluation_seq)
                $student_exam_results = $student_exam_results->where('exams.evaluation_seq', $evaluation_seq);

            if($subject_seq)
                $student_exam_results = $student_exam_results->where('subject_seq', $subject_seq);

            $student_exam_results = $student_exam_results->get();

        return $student_exam_results;
    }

    // NOTE: 또래 성적 가져오기. 또래 = 같은 학년의 평균으로 한다.
    private function getMyAgeExamResult(Request $request){
        $evaluation_seq = $request->input('evaluation_seq');
        $student_seq = session()->get('student_seq');
        if(session()->get('login_type') == 'teacher'){
            $student_seq = $request->input('student_seq');
        }
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        // or
        $month = $request->input('month');

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        // 검색 시작, 끝 날짜가 없을때, 월로 검색
        if($month && strlen($start_date) < 1){
            $start_date = date('Y-m-01', strtotime($month));
            $end_date = date('Y-m-t', strtotime($month));
        }
        $subject_seq = $request->input('subject_seq');

        $my_grade = \App\Student::where('id', $student_seq)->value('grade');
        $student_exam_results = \App\StudentExamResult::select(
            'subject_seq',
            'subject_codes.code_name as subject_name',
            'subject_codes.function_code',
            // 'exam_seq',
            // 'student_lecture_detail_seq',
            DB::raw('count(*) as total_count'),
            DB::raw('sum(if(student_exam_results.exam_status = "correct", 1, 0 )) as correct_count'),
            DB::raw('max(student_exams.complete_datetime) as complete_datetime')
        )
            ->leftJoin('exams', 'exams.id', '=', 'student_exam_results.exam_seq')
            ->leftJoin('codes as subject_codes', 'subject_codes.id', '=', 'exams.subject_seq')
            ->leftJoin('student_exams', 'student_exams.id', '=', 'student_exam_results.student_exam_seq')
            ->leftJoin('students', 'students.id', '=', 'student_exam_results.student_seq')
            ->where('student_exam_results.student_seq', $student_seq)
            ->where('exam_type', 'normal')
            ->whereBetween('student_exams.complete_datetime', [$start_date.' 00:00:00', $end_date.' 23:59:59'])
            ->where('students.grade', $my_grade)
            ->groupBy('subject_seq' );

            if($evaluation_seq)
                $student_exam_results = $student_exam_results->where('exams.evaluation_seq', $evaluation_seq);

            if($subject_seq)
                $student_exam_results = $student_exam_results->where('subject_seq', $subject_seq);

            $student_exam_results = $student_exam_results->get();

        return $student_exam_results;
    }

    // 과목별 성적표 가져오기.
    public function subjectSelect(Request $request){
        $evaluation_seq = $request->input('evaluation_seq');

        // 선택 달 과목별 성적 가져오기
        $exams1 = $this->examSelect($request);
        // 선택 달 같은 학년 성적 평균 가져오기.
        $myAgeExamResults = $this->getMyAgeExamResult($request);
        $myAgeExamResults = $myAgeExamResults->groupBy('subject_seq');
        // 한달전 성적 가져오기
        $request->merge(['month' => date('Y-m-d', strtotime($request->input('month').' -1 month'))]);
        $exams2 = $this->examSelect($request);
        $exams2 = $exams2->groupBy('subject_seq');


        foreach($exams1 as $exam){
            $subject_seq = $exam->subject_seq;
            $exam->prev_total_count = $exams2[$subject_seq][0]->total_count ?? 0;
            $exam->prev_correct_count = $exams2[$subject_seq][0]->correct_count ?? 0;
            $exam->my_age_total_count = $myAgeExamResults[$subject_seq][0]->total_count ?? 0;
            $exam->my_age_correct_count = $myAgeExamResults[$subject_seq][0]->correct_count ?? 0;
        }

        $result['resultCode'] = 'success';
        $result['exam_results'] = $exams1;
        return response()->json($result);
    }

    // 월과 과목을을 받아서, 그월의 1주차부터, 막주차 까지의 과목별 성적을 가져온다.
    public function subjectJuchaSelect(Request $request){
        $student_seq = session()->get('student_seq');
        if(session()->get('login_type') == 'teacher'){
            $student_seq = $request->input('student_seq');
        }
        $month = $request->input('month');
        $subject_seq = $request->input('subject_seq');

        // 해당 월의 1주차 부터, 막주차의 날짜를 가져온다.
        // 1. 우선 몇주차까지 있는지 확인한다.
        $first_day_of_month = date('Y-m-01', strtotime($month));
        $last_day_of_month = date('Y-m-t', strtotime($month));

        $saturday_count = 0;
        $sunday_count = 0;

        $current_day = $first_day_of_month;

        while (strtotime($current_day) <= strtotime($last_day_of_month)) {
            $day_of_week = date('N', strtotime($current_day)); // 1 (월요일) ~ 7 (일요일)

            if ($day_of_week == 6) {
                $saturday_count++;
            } elseif ($day_of_week == 7) {
                $sunday_count++;
            }

            $current_day = date('Y-m-d', strtotime($current_day . ' +1 day'));
        }

        $week_count = max($saturday_count, $sunday_count);

        // for를 주차만큼 돌린다.
        $exam_results = [];
        $age_exam_results = [];
        $search_date = [];
        $end_date = null;
        for ($i = 1; $i <= $week_count; $i++) {
            if ($i == 1) {
                // 첫 번째 주
                $start_date = $first_day_of_month;
                $end_date = date('Y-m-d', strtotime('next Saturday', strtotime($start_date)));
            } else {
                // 나머지 주
                $start_date = date('Y-m-d', strtotime($end_date . ' +1 day'));
                $end_date = date('Y-m-d', strtotime($start_date . ' +6 days'));
            }

            // 마지막 주가 월의 마지막 날을 넘지 않도록 조정
            if (strtotime($end_date) > strtotime($last_day_of_month)) {
                $end_date = $last_day_of_month;
            }

            $request->merge([
                'start_date' => $start_date,
                'end_date' => $end_date,
            ]);

            $search_date[$i] = $start_date . ' ~ ' . $end_date;
            $exam_results[$i] = $this->examSelect($request);
            $age_exam_results[$i] = $this->getMyAgeExamResult($request);
        }

        // 결과
        $result['search_date'] = $search_date;
        $result['week_count'] = $week_count;
        $result['resultCode'] = 'success';
        $result['exam_results'] = $exam_results;
        $result['age_exam_results'] = $age_exam_results;
        return response()->json($result);
    }

    // 시험 리스트 불러오기.
    public function examSearchSelect(Request $request){
        $student_seq = session()->get('student_seq');
        if(session()->get('login_type') == 'teacher'){
            $student_seq = $request->input('student_seq');
        }
        $subject_seq = $request->input('subject_seq');
        $evaluation_seq = $request->input('evaluation_seq');
        $month = $request->input('month');

        if($month ){
            $start_date = date('Y-m-01', strtotime($month));
            $end_date = date('Y-m-t', strtotime($month));
        }


        $student_exams = \App\StudentExamResult::select(
            'student_exam_seq',
            'student_exam_results.student_seq',
            'subject_seq',
            'subject_codes.code_name as subject_name',
            'subject_codes.function_code',
            DB::raw('min(evaluation_seq) as evaluation_seq'),
            DB::raw('min(exam_title) as exam_title'),
            DB::raw('count(*) as total_count'),
            DB::raw('sum(if(student_exam_results.exam_status = "correct", 1, 0 )) as correct_count'),
            DB::raw('max(student_exams.complete_datetime) as complete_datetime')
        )
            ->leftJoin('exams', 'exams.id', '=', 'student_exam_results.exam_seq')
            ->leftJoin('codes as subject_codes', 'subject_codes.id', '=', 'exams.subject_seq')
            ->leftJoin('student_exams', 'student_exams.id', '=', 'student_exam_results.student_exam_seq')
            ->where('student_exam_results.student_seq', $student_seq)
            ->where('exam_type', 'normal')
            ->whereBetween('student_exams.complete_datetime', [$start_date.' 00:00:00', $end_date.' 23:59:59'])
            // ->groupBy('student_exam_results.student_seq', 'exam_seq', 'student_lecture_detail_seq')
            ->groupBy('student_exam_results.student_seq', 'subject_seq', 'student_exams.id' );

            if($evaluation_seq)
                $student_exams = $student_exams->where('exams.evaluation_seq', $evaluation_seq);

            if($subject_seq)
                $student_exams = $student_exams->where('subject_seq', $subject_seq);

            $student_exams = $student_exams->get();

        // 평가분류
        $main_code = \App\Student::where('id', $student_seq)->value('main_code');
        $evaluation_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'evaluation')->where('code_step', '=', 1)
        ->orderBy('code_idx', 'asc')
        ->get()
        ->pluck(null, 'id');

        // 결과
        $result['resultCode'] = 'success';
        $result['student_exams'] = $student_exams;
        $result['evaluation_codes'] = $evaluation_codes;
        return response()->json($result);
    }

    // 과목별 학습플랜 리스트 가져오기.
    function learnPlanSearchSelect(Request $request){
        $student_seq = session()->get('student_seq');
        $subject_seq = $request->input('subject_seq');
        $sel_month = $request->input('sel_month');
        $no_unittest = $request->input('no_unittest');
        $sel_month = date('Y-m-01', strtotime($sel_month));
        // 학습플랜 리스트 가져오기.
        $student_lecture_details = \App\StudentLectureDetail::
            select(
                'lecture_details.lecture_detail_name',
                'lecture_details.lecture_detail_description',
                'lecture_details.lecture_detail_type',
                'student_lecture_details.*'
            )
            ->whereBetween('sel_date', [
                $sel_month . ' 00:00:00',
                Carbon::parse($sel_month)->endOfMonth()->format('Y-m-d H:i:s')
            ])
            ->whereNull('student_lecture_details.lecture_type') // 학교공부제외.
            ->where('student_seq', $student_seq)
            ->whereIn('lecture_details.lecture_seq', \App\LectureCode::select('lecture_seq')->where('code_seq', $subject_seq))
            ->orderBy('sel_date', 'desc');

        if($no_unittest == 'Y'){
            $student_lecture_details = $student_lecture_details->leftJoin('lecture_details', 'lecture_details.id', '=', 'student_lecture_details.lecture_detail_seq');

            $student_lecture_details = $student_lecture_details->get();
        }else{
            $student_lecture_details = $student_lecture_details->leftJoin('lecture_details', 'lecture_details.lecture_detail_group', '=', 'student_lecture_details.lecture_detail_seq')
                ->where('lecture_details.lecture_detail_type', 'unit_test');

            $student_lecture_details = $student_lecture_details->get();
        }

        // 과목 코드 정보 가져오기.
        $code = \App\Code::where('id', $subject_seq)->first();

        // 시험 점수 가져오기
        $student_lecture_detail_seq = $student_lecture_details->pluck('id')->toArray();
        $student_exams = \App\StudentExam::
        select(
            'student_exams.id',
            'student_exams.student_lecture_detail_seq',
            DB::raw('sum(if(student_exam_results.exam_status = \'correct\', 1, 0)) as correct_cnt'),
            DB::raw('sum(if(exam_type <> \'esay\', 1, 0)) as all_cnt')
        )
        ->leftJoin('student_exam_results', 'student_exam_results.student_exam_seq', '=', 'student_exams.id')
        ->where('student_exams.exam_status', 'complete')
        ->where('student_exams.student_seq', $student_seq)
        ->whereIn('student_exams.student_lecture_detail_seq', $student_lecture_detail_seq)
        ->groupBy('student_exams.id', 'student_exams.student_lecture_detail_seq')
        ->get()->keyBy('student_lecture_detail_seq');

        // 한자 일경우 급수 가져오기.
        $lecture_detail_seqs = $student_lecture_details->pluck('lecture_detail_seq')->toArray();
        $subject2_codes = \App\LectureDetail::
            select(
                'lecture_details.lecture_detail_group',
                'lecture_exam_seq',
                'exams.subject_seq2',
                'subject2_codes.code_name'
            )
            ->leftJoin('exams', 'exams.id', '=', 'lecture_details.lecture_exam_seq')
            ->leftJoin('codes as subject2_codes', 'subject2_codes.id', '=', 'exams.subject_seq2')
            ->whereIn('lecture_detail_group', $lecture_detail_seqs)
            ->whereIn('lecture_details.lecture_detail_type', ['exam_solving', 'unit_test'])
            ->get()->groupBy('lecture_detail_group');


        $result['resultCode'] = 'success';
        $result['lecture_details'] = $student_lecture_details;
        $result['code'] = $code;
        $result['student_exams'] = $student_exams;
        $result['subject2_codes'] = $subject2_codes;
        return response()->json($result);
    }
}
