<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection; // Collection 클래스 임포트

class AfterLearningMTController extends Controller
{
    //
    public function list()
    {
        $teach_seq = session()->get('teach_seq');
        $team_code = session()->get('team_code');
        $main_code = session()->get('main_code') ?? $_COOKIE['main_code'] ?? '';

        $class_study_dates = \App\ClassStudyDate::whereIn('class_seq', function ($query) use ($teach_seq) {
            $query->select('id')->from('classes')->where('teach_seq', $teach_seq);
        })
            ->whereIn('class_day', function ($query) {
                $query->selectRaw("case dayname(now())
            when 'Monday' then '월'
            when 'Tuesday' then '화'
            when 'Wednesday' then '수'
            when 'Thursday' then '목'
            when 'Friday' then '금'
            when 'Saturday' then '토'
            when 'Sunday' then '일'
            end");
            })->get();
        // 위 변수를 다시 class_seq를 키로 배열로 만들어라.
        $class_study_dates = $class_study_dates->keyBy('class_seq');


        // 담당하고 있는 클래스 리스트 가져오기
        $classes = \App\ClassTb::select('classes.*')
        ->where('team_code', $team_code)
        ->where('teach_seq', $teach_seq)
        ->get();


        $subject_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'subject')->where('code_step', '=', 1)
        ->orderBy('code_idx', 'asc')
        ->get();

        // 평가분류
        $evaluation_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'evaluation')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        return view(
            'teacher.teacher_after_learning_management',
            [
                'teach_seq' => $teach_seq,
                'team_code' => $team_code,
                'class_study_dates' => $class_study_dates,
                'classes' => $classes,
                'subject_codes' => $subject_codes,
                'evaluation_codes' => $evaluation_codes
            ]
        );
    }

    // 학습관리 상세
    public function detail(Request $request)
    {
        $student_seq = $request->input('student_seq');

        $teach_seq = session()->get('teach_seq');
        $team_code = session()->get('team_code');
        $main_code = session()->get('main_code') ?? $_COOKIE['main_code'] ?? '';

        $student = \App\Student::select(
            'students.*',
            'grade_codes.code_name as grade_name'
        )
            ->where('students.id', $student_seq)
            ->leftJoin('codes as grade_codes', 'grade_codes.id', '=', 'students.grade')
            ->first();

        $parent_seq = $student->parent_seq;

        $parent = null;
        if($parent_seq) {
            $parent = \App\ParentTb::find($parent_seq);
        }


        // 학생 수업명, 기간

        $class_infos = \App\ClassMate::select(
            'class_mates.student_seq',
            'class_mates.class_seq',
            'classes.class_name',
            'class_mates.start_date',
            'class_mates.end_date',
            DB::raw('group_concat(class_day order by FIELD(class_day, "월", "화", "수", "목", "금", "토", "일")) as class_days')
        )
            ->leftJoin('classes', 'classes.id', '=', 'class_mates.class_seq')
            ->leftJoin('class_study_dates', 'class_study_dates.class_seq', '=', 'classes.id')
            ->where('class_mates.student_seq', $student_seq)
            ->groupBy(
                'class_mates.student_seq',
                'class_mates.class_seq',
                'classes.class_name',
                'class_mates.start_date',
                'class_mates.end_date'
            )
            ->get();


        //선택 학생이 듣고 있는 첫번째 클래스정보를 가져온다.
        $class_mates = \App\ClassMate::
            where('student_seq', $student_seq)
            ->where('is_use', 'Y')
            ->first();

        // 오늘자 클래스 start_time 시간을 가져온다.
        $date = date('Y-m-d');
        $class_study_dates = \App\ClassStudyDate::
        where('class_study_dates.class_day', function ($query) use ($date) {
            $query->select(DB::raw("case DAYOFWEEK('" . $date . "')
            when 1 then '일'
            when 2 then '월'
            when 3 then '화'
            when 4 then '수'
            when 5 then '목'
            when 6 then '금'
            when 7 then '토'
            end as day_of_week"));
        })
        ->where('class_seq', $class_mates->class_seq??'')
        ->first();
        $class_start_time = $class_study_dates->start_time??'';


        //주간 학습 변수
         //월~일 배열
         $week = array("일", "월", "화", "수", "목", "금", "토");
        // 08:00 ~ 23:00 배열
        $time_array = array("08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00");
        // 1~6월
        $month_array = array("1", "2", "3", "4", "5", "6");

        $subject_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'subject')->where('code_step', '=', 1)
        ->orderBy('code_idx', 'asc')
        ->get();

        // 평가분류
        $evaluation_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'evaluation')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();


        return view(
            'teacher.teacher_after_learning_management_detail',
            [
                'student' => $student,
                'teach_seq' => $teach_seq,
                'team_code' => $team_code,
                'parent' => $parent,
                'class_infos' => $class_infos,
                'class_start_time' => $class_start_time,
                'week' => $week,
                'month_array' => $month_array,
                'time_array' => $time_array,
                'subject_codes' => $subject_codes,
                'evaluation_codes' => $evaluation_codes
            ]
        );
    }

    // 클래스 학생 출석 종료 하기.
    public function classStudentAttendEnd(Request $request)
    {
        $users = $request->input('users');
        $attend_date = $request->input('attend_date');

        $is_transaction_suc = true;
        DB::beginTransaction();
        try {
            $no_attend_students = array();
            foreach($users as $user) {
                $class_seq = $user['class_seq'];
                $student_seq = $user['student_seq'];

                // 학생의 클래스가 오늘 출석을 하는 날인지 확인.
                // 아니라면 no_attend_students 에 추가.
                $class_study_dates = \App\ClassStudyDate::where('class_seq', $class_seq)
                ->whereIn('class_day', function ($query) {
                    $query->select('day')->from('dates')->where('date', '=', date('Y-m-d'));
                })
                ->get();
                if(!$class_study_dates->count()){
                    $no_attend_students[] = $student_seq;
                    continue;
                }

                // 일자별 출결한 학생들 상태 변경
                $attend_details = \App\AttendDetail::where('attend_date', $attend_date)
                    ->where('class_seq', $class_seq)
                    ->where('student_seq', $student_seq)
                    ->update(['is_complete' => 'Y', 'end_datetime' => date('Y-m-d H:i:s')]);

                // 한개의 수업일때만 유효함으로 대체적으로 detail의 end_datetime을 사용한다.
                $attends = \App\Attend::where('attend_date', $attend_date)
                    ->where('student_seq', $student_seq)
                    ->update(['end_time' => date('H:i:s')]);

                // TODO: 부모님에게 알림 보내기
                //
                //
            }

            DB::commit();
        } catch (\Exception $e) {
            $is_transaction_suc = false;
            DB::rollback();
            throw $e;
        }

        // 결과
        $result = array();
        if ($is_transaction_suc) {
            $result['resultCode'] = 'success';
        } else {
            $result['resultCode'] = 'fail';
        }
        $result['no_attend_students'] = $no_attend_students;
        return response()->json($result);
    }

    // 학습일지 INSERT
    public function detailInsert(Request $request){
        $team_code = session()->get('team_code');
        $teach_seq = session()->get('teach_seq');
        $student_seq = $request->input('student_seq');
        $log_seq = $request->input('log_seq');
        $is_temp = $request->input('is_temp');
        $log_date = $request->input('log_date');
        $learnging_log_details_reqs = $request->input('learning_log_details');


        // :트랙잭션 시작
        $is_transaction_suc = true;
        DB::beginTransaction();
        try {

            $learning_log = new \App\LearningLog;
            if($log_seq) {
                $learning_log = \App\LearningLog::find($log_seq);
                $learning_log->is_temp = $is_temp;
            }else{
                $learning_log->team_code = $team_code;
                $learning_log->teach_seq = $teach_seq;
                $learning_log->student_seq = $student_seq;
                $learning_log->log_date = $log_date;
                $learning_log->is_temp = $is_temp;
            }

            $learning_log->save();

            $log_seq = $learning_log->id;

            \App\LearningLogDetail::where('log_seq', $log_seq)->delete();

            foreach($learnging_log_details_reqs as $learnging_log_detail_req){
                $learning_log_detail = new \App\LearningLogDetail;
                $learning_log_detail->log_seq = $log_seq;
                $learning_log_detail->student_seq = $student_seq;
                $learning_log_detail->log_form = $learnging_log_detail_req['log_form'];
                $learning_log_detail->log_type = $learnging_log_detail_req['log_type'];
                $learning_log_detail->log_content = $learnging_log_detail_req['log_content'];
                $learning_log_detail->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            $is_transaction_suc = false;
            DB::rollback();
            throw $e;
        }


        //결과
        // $result = array();
        $result['resultCode'] = $is_transaction_suc ? 'success' : 'fail';
        $result['log_seq'] = $log_seq;
        return response()->json($result);
    }

    // 학습일지 상세 불러오기.
    public function detailSelect(Request $request){
        $student_seq = $request->input('student_seq');
        $teach_seq =  session()->get('teach_seq');
        $log_date = $request->input('log_date');
        $class_seqs = $request->input('class_seqs');
        // , 를 배열로.
        $class_seqs = explode(',', $class_seqs);


        $last_lecture_date = '';
        $last_lecture_day = '';
        $yesterday_date = date('Y-m-d', strtotime($log_date . ' -1 day'));
        $yesterday_day = date('w', strtotime($yesterday_date));
        // 한글
        $week = array("일", "월", "화", "수", "목", "금", "토");
        $yesterday_day = $week[$yesterday_day];

        //트랜잭션 시작
        $is_transaction_suc = true;
        DB::beginTransaction();
        try{
            // 학습일지 불러오기.
            // 일지가 선생님별로 나눌지. 클래스별로 나눌지. 우선은 선생님으로 나눴으나
            // 혹시 분리 요청시 클래스seq 컬럼 추가후 분리.
            $learning_logs = \App\LearningLog::
                where('student_seq', $student_seq)
                ->where('log_date', $log_date)
                ->where('teach_seq', $teach_seq)
                ->get();

            $log_seq = $learning_logs->first()->id??'';
            $learning_log_details = \App\LearningLogDetail::
                where('log_seq', $log_seq)
                ->get();

            //  오늘로부터 마지막 방과후 수업을 한 날짜를 불러온다. 오늘의 전날을 불러온다. ?

            // select * from class_mates where student_seq = 2714 and class_seq in (3) and is_use = 'Y';
            $class_mates = \App\ClassMate::where('student_seq', $student_seq)
                ->whereIn('class_seq', $class_seqs)
                ->where('is_use', 'Y')
                ->get();
            $start_date = $class_mates->min('start_date');
            $end_date = $class_mates->max('end_date');
            if($start_date == null){
                // log_date 의 첫 날짜를 가져온다.
                $start_date = date('Y-m-01', strtotime($log_date));
            }
            if($end_date == null){
                // log_date 의 마지막 날짜를 가져온다.
                $end_date = date('Y-m-t', strtotime($log_date));

            }
            // select group_concat(class_day) class_day from class_study_dates where class_seq in (3);
            $class_study_dates = \App\ClassStudyDate::whereIn('class_seq', $class_seqs)
                ->select(DB::raw('group_concat(class_day) as class_day'))
                ->first();
            $class_days = explode(',', $class_study_dates->class_day);

            $dates  = \App\Date::select('date', 'day')
                ->where('date', '>=', $start_date)
                ->where('date', '<=', $end_date)
                ->where('date', '<', $log_date)
                ->where('date', '>', date('Y-m-d', strtotime($log_date . ' -1 month')))
                ->whereIn('day', $class_days)
                ->get();
            $last_lecture_date = $dates->max('date');
            $last_lecture_day = $dates->where('date', $last_lecture_date)->first()->day??'';


            // 미수강 cnt
            $student_lecture_details = \App\StudentLectureDetail::
                select(
                    'lectures.teach_seq',
                    'lectures.lecture_name',
                    'lectures.lecture_description',
                    'lecture_details.lecture_detail_name',
                    'lecture_details.lecture_detail_description',
                    'student_lecture_details.*'
                )
                ->leftJoin('lecture_details', 'lecture_details.id', '=', 'student_lecture_details.lecture_detail_seq')
                ->leftJoin('lectures', 'lectures.id', '=', 'lecture_details.lecture_seq')
                ->whereNull('student_lecture_details.lecture_type') // 학교공부 제외
                ->where('student_seq', $student_seq)
                ->whereBetween('sel_date', [$last_lecture_date, $yesterday_date])
                ->where('student_lecture_details.status', '<>', 'delete')
                // ->where('teach_seq', $teach_seq) // TODO: 관리자 업데이트 후에 풀어준다.
                ->get();

            $student_lecture_detail_seqs = $student_lecture_details->pluck('id')->toArray();

            // 오답 미완료 cnt
            $inwrongs = \App\StudentExamResult::
                select(
                    'student_exam_results.student_seq',
                    'student_exam_seq',
                    'student_exams.student_lecture_detail_seq',
                    'student_exams.lecture_detail_seq',
                    'student_exams.is_complete',
                    'exams.evaluation_seq',
                    DB::raw(
                        "sum(case when
                            student_exam_results.exam_status = 'wrong' and student_exam_results.wrong_status is null
                        then 1 else 0 end)  as inwrong_cnt"
                    ),
                    DB::raw('count(*) as total_cnt')
                )
                ->leftJoin('student_exams', 'student_exams.id', '=', 'student_exam_results.student_exam_seq')
                ->leftJoin('exams', 'exams.id', '=', 'student_exams.exam_seq')
                ->where('student_exam_results.student_seq', $student_seq)
                // ->where('student_exam_results.exam_status', 'wrong')
                // ->whereNull('student_exam_results.wrong_status')
                ->whereIn('student_exams.student_lecture_detail_seq', $student_lecture_detail_seqs)
                ->groupBy('student_exam_results.student_seq', 'student_exam_seq')
                ->get()
                // student_exams.student_lecture_detail_seq 를 키로 배열로 만든다.
                ->keyBy('student_lecture_detail_seq');


            DB::commit();
        } catch (\Exception $e) {
            $is_transaction_suc = false;
            DB::rollback();
            throw $e;
        }

        //결과
        $result = array();
        $result['resultCode'] = $is_transaction_suc ? 'success' : 'fail';
        $result['log_seq'] = $log_seq;
        $result['learning_logs'] = $learning_logs;
        $result['learning_log_details'] = $learning_log_details;
        $result['last_lecture_date'] = $last_lecture_date;
        $result['last_lecture_day'] = $last_lecture_day;
        $result['yesterday_date'] = $yesterday_date;
        $result['yesterday_day'] = $yesterday_day;
        $result['student_lecture_details'] = $student_lecture_details;
        $result['inwrongs'] = $inwrongs;
        $result['sldseqs'] = $student_lecture_detail_seqs;

        return response()->json($result);
    }

    // 출결현황 > 출결리스트 불러오기.
    public function detailAttendSelect(Request $request){
        $student_seq = $request->input('student_seq');
        $sel_date = $request->input('sel_date'); // month
        $page = $request->input('page');
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 4;
        $is_page = $request->input('is_page');
        $search_start_date = $sel_date . '-01';


    // +-----------+-----------+------------+------------+---------------------+
    // | team_code | class_seq | start_date | end_date   | class_day           |
    // +-----------+-----------+------------+------------+---------------------+
    // | A00006    |         3 | 2024-09-10 | 2024-12-10 | 수,월,화,목,금      |
    // +-----------+-----------+------------+------------+---------------------+
        $attend_day_info = \App\ClassMate::select(
            'class_mates.team_code',
            'class_mates.class_seq',
            DB::raw('min(class_mates.start_date) as start_date'),
            DB::raw('max(class_mates.end_date) as end_date'),
            DB::raw('group_concat(class_day) as class_day')
        )
            ->leftJoin('class_study_dates', function ($join) {
                $join->on('class_study_dates.class_seq', '=', 'class_mates.class_seq')
                    ->on('class_study_dates.team_code', '=', 'class_mates.team_code');
            })
            ->where('class_mates.student_seq', $student_seq)
            ->groupBy('team_code', 'class_seq')
            ->get();

        $attend_lists = collect();
        foreach($attend_day_info as $attend_day){
            $team_code = $attend_day->team_code;
            $start_date = $attend_day->start_date;
            $end_date = $attend_day->end_date;
            $class_days = explode(',', $attend_day->class_day);

            $attend_list = \App\Date::
            select(
                'dates.date',
                'attends.*',
                'absents.absent_reason',
                'absents.is_ref_complete',
                'absents.ref_date',
                'absents.absent_reason',
                'absents.id as absent_seq',
                'absents.absent_start_time'
            )
            ->leftJoin('attends', function ($join) use ($student_seq, $team_code) {
                $join->on('dates.date', '=', 'attends.attend_date')
                    ->where('attends.student_seq', $student_seq)
                    ->where('attends.team_code', $team_code);
                })
            ->leftJoin('absents', function ($join) use ($student_seq, $team_code) {
                $join->on('dates.date', '=', 'absents.absent_date')
                    ->where('absents.student_seq', $student_seq)
                    ->where('absents.team_code', $team_code);
                })
            ->where('dates.date', '>=', $search_start_date)
            ->where('dates.date', '<=', DB::raw('last_day("' . $search_start_date .'")'))
            ->where('dates.date', '>=', $start_date)
            ->where('dates.date', '<=', $end_date)
            ->where('dates.date', '<=', date('Y-m-d'))
            ->whereIn('dates.day', $class_days)
            ->get();

            // attend_lists 에 attend_list를 추가한다.
            $attend_lists = $attend_lists->merge($attend_list);
        }


        $lecture_status = \App\StudentLectureDetail::
            select(
                'dates.date as date',
                DB::raw('min(status) as status')
            )
            ->join('dates', 'dates.date', '=', 'student_lecture_details.sel_date')
            ->where('student_seq', $student_seq)
            ->whereBetween('sel_date', [$search_start_date, $end_date])
            ->where('sel_date', '<=', date('Y-m-d'))
            ->whereIn('dates.day', $class_days)
            ->groupBy('dates.date')
            ->get()
            ->keyBy('date');


        // 출석갯수 id 가 null이 아니면 카운트
        $attend_cnt = $attend_lists->where('id', '!=', null)->count();
        //  데이터 총 갯수
        $total_cnt = $attend_lists->count();


        // date를 기준으로 역순정렬한다.
        $attend_lists = $attend_lists->sortBy('date')->reverse();


        // !!페이지 아닌경우 페이지 하지 않고 리턴.
        // 결석 리스트임으로 변수명 변경.
        if($is_page == 'N'){
            $result = array();
            $result['resultCode'] = 'success';
            $result['absent_lists'] = $attend_lists->values();
            return response()->json($result);
        }

        // 페이징 작업. 역순정렬
        $attend_lists = $attend_lists->forPage($page, $page_max)->values();

        // paginate 형태처럼 만들어준다.
        $last_page = ceil($total_cnt / $page_max);
        // if($total_cnt / $page_max != 0) {
        //     $last_page = $last_page + 1;
        // }
        $current_page = $page;
        $return_data = [];
        $return_data['last_page'] = $last_page;
        $return_data['current_page'] = $current_page;
        $return_data['data'] = $attend_lists;


        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        $result['attend_lists'] = $return_data;
        $result['attend_cnt'] = $attend_cnt;
        $result['total_cnt'] = $total_cnt;
        $result['lecture_status'] = $lecture_status;
        return response()->json($result);
    }

    // 보강완료
    public function classStudentReinforcementEnd(Request $request){
        $student_seq = $request->input('student_seq');
        $absent_seq = $request->input('absent_seq');
        $sel_date = $request->input('sel_date');
        $ref_date = $request->input('ref_date');

        $update_date = [
            'is_ref_complete' => 'Y',
        ];
        if($ref_date){
            $update_date['ref_date'] = $ref_date;
        }
        $update = \App\Absent::where('id', $absent_seq)->where('student_seq', $student_seq)
            ->update($update_date);

        // 결과
        if($update){
            $result['resultCode'] = 'success';
        }else{
            $result['resultCode'] = 'fail';
        }
        return response()->json($result);
    }
}


