<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherMainMtController extends Controller
{
    // 선생님 메인 방과후 화면.
    public function mainAfter()
    {
        $teach_seq = session()->get('teach_seq');
        $main_code = \App\Teacher::find($teach_seq)->main_code;
        $grade_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'grade')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        $today = date('Y-m-d');

        //오늘 결석 한 학생수.
        $absent_cnt = $this->getStatusCnt(array( 'type' => 'absent_cnt', 'date' => $today));
        $attend_cnt = $this->getStatusCnt(array( 'type' => 'attend_cnt', 'date' => $today));
        $ref_cnt = $this->getStatusCnt(array( 'type' => 'reinforcement_cnt', 'date' => $today));
        $new_cnt = $this->getStatusCnt(array( 'type' => 'new_cnt', 'date' => $today));


        return view('teacher.teacher_main_after', [
            'grade_codes' => $grade_codes,
            'absent_cnt' => $absent_cnt,
            'attend_cnt' => $attend_cnt,
            'ref_cnt' => $ref_cnt,
            'new_cnt' => $new_cnt
        ]);
    }

    // 수업 시작하기
    public function classStart(Request $request)
    {
        $class_seq = $request->input('class_seq');
        $teach_seq = session()->get('teach_seq');

        $team_code = $request->input('team_code');
        $teach_seq_post = $request->input('teach_seq');

        // 우선은 다른 직급의 높은 선생님이 들어올 수 있으므로 $teach_seq_post를 우선시.
        if (!$teach_seq) {
            return;
        }
        $teach_seq = $teach_seq_post;

        $class = \App\ClassTb::select(
            'classes.*',
            'teams.team_name',
            'grade_codes.code_name as grade_name'
        )
            ->leftJoin('codes as grade_codes', 'classes.grade', '=', 'grade_codes.id')
            ->leftJoin('teams', 'classes.team_code', '=', 'teams.team_code')
            ->where('classes.id', $class_seq)
            ->where('classes.teach_seq', $teach_seq)
            ->where('classes.team_code', $team_code)
            ->first();

        $class_study_dates = \App\ClassStudyDate::where('class_seq', $class_seq)->get()->groupBy('class_day');

        // 오늘 출석해야할 일수 구하기

        // 요일을 가져온다.
        $total_days = \App\ClassStudyDate::where('class_seq', $class_seq)->get()->pluck('class_day')->toArray();
        // 이번달의 첫날과, 마지막날 가져오기.
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');
        // select * from dates where date between '2024-09-25' and '2024-09-25'
        $dates = \App\Date::whereBetween('date', [$start_date, $end_date])->whereIn('day', $total_days)->get();
        $total_attend_cnt = $dates->count();

        // 위에서 구한 날짜에서 오늘이 몇일째인지 구하기.
        $today = date('Y-m-d');
        $today_attend_idx = $dates->search(function ($item, $key) use ($today) {
            return $item->date >= $today;
        });
        // 딱 같은날이 떨어지지 않아도 몇일차인지 구해야 한다.

        return view('teacher.teacher_class_start', [
            'class_seq' => $class_seq,
            'team_code' => $team_code,
            'class' => $class,
            'class_study_dates' => $class_study_dates,
            'total_attend_cnt' => $total_attend_cnt,
            'today_attend_idx' => $today_attend_idx
        ]);
    }

    // 수업 저장.
    function classInsert(Request $request)
    {
        $class_seq = $request->input('class_seq');
        $class_name = $request->input('class_name');
        $class_room = $request->input('class_room');
        $grade = $request->input('grade');
        $class_study_dates = $request->input('class_study_dates');
        $student_seqs = $request->input('student_seqs');

        $teach_seq = session()->get('teach_seq');
        $main_code = \App\Teacher::find($teach_seq)->main_code;
        $team_code = session()->get('team_code');

        $class = null;
        // 트랙잭션 시작.
        $is_transaction_suc = true;
        DB::beginTransaction();
        try {
            if ($class_seq) {
                $class = \App\ClassTb::where('id', $class_seq)->where('teach_seq', $teach_seq)->first();
            } else {
                $class = new \App\ClassTb;
                $class->teach_seq = $teach_seq;
            }
            $class->team_code = $team_code;
            $class->class_name = $class_name;
            $class->class_room = $class_room;
            $class->grade = $grade;
            $class->save();

            $class_seq = $class->id;

            //$class_study_dates는 배열이고, 1보다 많으면 테이블 ClassStudyDate에 저장.
            if (count($class_study_dates) > 1) {
                //먼저 기존 데이터 삭제.
                \App\ClassStudyDate::where('class_seq', $class_seq)->delete();
                for ($i = 0; $i < count($class_study_dates); $i++) {
                    // 받은 데이터
                    // 0: {day: "월", start_time: "13:0", end_time: "14:00", interval: "60"}
                    // 실제 테이블 이름.
                    $class_study_date = $class_study_dates[$i];
                    $class_study_date_tb = new \App\ClassStudyDate;
                    $class_study_date_tb->class_seq = $class_seq;
                    $class_study_date_tb->team_code = $team_code;
                    $class_study_date_tb->class_day = $class_study_date['day'];
                    $class_study_date_tb->start_time = $class_study_date['start_time'];
                    $class_study_date_tb->end_time = $class_study_date['end_time'];
                    $class_study_date_tb->time_interval = $class_study_date['interval'];
                    $class_study_date_tb->save();
                }
            }

            //학생 저장.
            if (count($student_seqs) > 0) {
                //먼저 기존 데이터 삭제.
                \App\ClassMate::where('class_seq', $class_seq)->delete();
                for ($i = 0; $i < count($student_seqs); $i++) {
                    $student_seq = $student_seqs[$i];
                    $class_mate = new \App\ClassMate;
                    $class_mate->team_code = $team_code;
                    $class_mate->class_seq = $class_seq;
                    $class_mate->student_seq = $student_seq;
                    // $class_mate->end_date = ''; // 추후 혹시 필요할 경우.
                    $class_mate->save();
                }
            }else{
                \App\ClassMate::where('class_seq', $class_seq)->delete();
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
            $result['class_seq'] = $class_seq;
        }
        return response()->json($result);
    }

    // CLASS(반) 불러오기.
    public function classSelect(Request $request)
    {
        $teach_seq = session()->get('teach_seq');
        $team_code = session()->get('team_code');
        $teach_seq_post = $request->input('teach_seq');
        //틀리면 리턴
        if ($teach_seq != $teach_seq_post) {
            return response()->json(array('resultCode' => 'fail'));
        }

        $classes = \App\ClassTb::select(
            'classes.*',
            'grade_codes.code_name as grade_name'
        )
            ->leftJoin('codes as grade_codes', 'classes.grade', '=', 'grade_codes.id')
            ->where('classes.team_code', $team_code)
            ->where('teach_seq', $teach_seq)->get();

        //$classes 에서 id를 키로 하는 배열을 만들기.
        $class_seqs = $classes->pluck('id')->toArray();

        // 클래스별 수업시간 가져오기.
        // ORDER BY FIELD(class_day, '월', '화', '수', '목', '금', '토', '일');
        $class_study_dates = \App\ClassStudyDate::whereIn('class_seq', $class_seqs)
            ->orderByRaw("FIELD(class_day, '월', '화', '수', '목', '금', '토', '일')")
            ->get();
        $class_study_dates = $class_study_dates->groupBy('class_seq');

        // 클래스별 학생 수치 가져오기.
        $class_mates = \App\ClassMate::select(
            'class_mates.class_seq',
            'class_mates.student_seq',
            'students.student_name',
            DB::raw("grade_codes.code_name as grade_name"),
            'students.class_name',
            DB::raw("if(students.last_login_date >= date_sub(now(), interval 5 minute), 'Y', 'N') as is_login"),
            DB::raw("if(absents.ref_date = date(now()), 'Y', 'N') as is_ref")
        )
            ->leftJoin('students', 'class_mates.student_seq', '=', 'students.id')
            ->leftJoin('codes as grade_codes', 'students.grade', '=', 'grade_codes.id')
            ->leftJoin('absents', function ($join){
                $join->on('absents.student_seq', '=', 'class_mates.student_seq')
                    ->where('absents.ref_date', '=', date('Y-m-d'))
                    ->where(function($where){
                        $where
                            ->whereNull('absents.is_ref_complete')
                            ->orWhere('absents.is_ref_complete', 'N');
                    })
                    ->whereColumn('absents.class_seq', '=', 'class_mates.class_seq');
            })
            ->whereIn('class_mates.class_seq', $class_seqs)
            ->where('class_mates.is_use', 'Y')
            ->where('class_mates.student_seq', '!=', null)
            ->get()
            ->groupBy('class_seq');

        // 클래스별 접속학생 불러오기.[상위에 추가.]
        // 오늘 보강 [상위에 추가]

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        $result['classes'] = $classes;
        $result['class_study_dates'] = $class_study_dates;
        $result['class_mates'] = $class_mates;
        // $result['class_students'] = $class_students;
        return response()->json($result);
    }

    // 학생 불러오기. SELECT
    public function studentSelect(Request $request)
    {
        $teach_seq = session()->get('teach_seq');
        $team_code = session()->get('team_code');

        $teach_seq_post = $request->input('teach_seq');
        $no_class = $request->input('no_class');
        $search_grade = $request->input('search_grade');
        $search_name = $request->input('search_str');

        //틀리면 리턴
        if ($teach_seq != $teach_seq_post) {
            return response()->json(array('resultCode' => 'fail'));
        }
        $team_code = \App\Teacher::find($teach_seq)->team_code;

        $students = \App\Student::select(
            'students.id',
            'students.student_name',
            'students.grade',
            'students.class_name',
            'students.created_at',
            'grade_codes.code_name as grade_name',
            DB::raw("if(max(ifnull(class_mates.class_seq, 0)) > 0, 'Y', 'N' ) as is_class")
        )
            ->leftJoin('codes as grade_codes', 'students.grade', '=', 'grade_codes.id')
            ->leftJoin('class_mates', 'students.id', '=', 'class_mates.student_seq')
            ->where('students.team_code', $team_code)
            ->groupBy('students.id', 'students.student_name', 'grade_codes.code_name', 'students.grade', 'students.class_name', 'students.created_at')
            ->orderBy('students.student_name', 'asc');

        // 조건
        if ($no_class == 'Y') {
            $students = $students->whereNotIn('students.id', function ($query) use ($team_code) {
                $query->select('student_seq')->from('class_mates')->where('team_code', $team_code)->where('student_seq', '!=', 'null');
            });
        }
        if ($search_grade) {
            $students = $students->where('students.grade', $search_grade);
        }
        if ($search_name) {
            $students = $students->where('students.student_name', 'like', '%' . $search_name . '%');
        }

        // $result['sql'] = $students->toSql();
        // $result['bind'] = $students->getBindings();
        $students = $students->get();


        // 결과
        $result['resultCode'] = 'success';
        $result['students'] = $students;
        return response()->json($result);
    }

    // 클래스의 학생 정보를 불러오기 (출석, 수강, 오답노트, 출석률 등)
    public function classStudentSelect(Request $request)
    {
        $teach_seq = session()->get('teach_seq');
        $team_code = session()->get('team_code');
        $class_seq = $request->input('class_seq');
        $teach_seq_post = $request->input('teach_seq');

        $search_type = $request->input('search_type');
        $search_str = $request->input('search_str');
        // no_class = Y 이면 where class_seq 를 하지 않는다.
        $no_class = $request->input('no_class');
        $is_add_coulmn = $request->input('is_add_coulmn');

        //student_seq 있으면 해당 학생만 불러온다.
        $student_seq = $request->input('student_seq');

        $attend_date = $request->input('attend_date');

        // 오늘 기준으로 불러올때,
        $is_search_today = $request->input('is_search_today');
        // :페이징 쿼리
        $is_page = $request->input('is_page');
        $page = $request->input('page');
        $page = $page ?? 1;
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 6;
        // $sql->paginate($page_max, ['*'], 'page', $page);


        // 선생님으로 로그인 되어있는지 확인.
        if (strlen($teach_seq) < 1) {
            return;
        }
        // 우선은 post로 받은 teach_seq를 우선시.
        $teach_seq = $teach_seq_post;
        // 학생 정보 불러오기.
        $students = \App\ClassMate::select(
            'class_mates.class_seq',
            'class_mates.team_code',
            'class_mates.id',
            'class_mates.student_seq',
            'students.student_name',
            'grade_codes.code_name as grade_name',
            'students.class_name',
            DB::raw('min(attend_details.attend_datetime) as attend_datetime'),
            DB::raw('min(attend_details.is_complete) as is_complete'),
            DB::raw('min(absents.ref_date) as ref_date'),
            DB::raw('max(absents.absent_reason) as absent_reason'),
            //출석,
            //수강,
            //오답노트,
            DB::raw("max(day(last_day(date(now())))) as month_last_day")
        )
            //TODO: 여기서 학생과 클래스가 1:1이어야만 전체 검색이 가능한데. 이부분은 나중에 수정해야함.
            ->leftJoin('students', 'class_mates.student_seq', '=', 'students.id')
            ->leftJoin('codes as grade_codes', 'students.grade', '=', 'grade_codes.id')
            ->leftJoin('attends', function ($join) use ($attend_date) {
                $join->on('attends.student_seq', '=', 'class_mates.student_seq');
                if($attend_date){
                    $join = $join->where('attends.attend_date', '=', $attend_date);
                }else{
                    $join = $join->where('attends.attend_date', '=', DB::raw('date(now())'));
                }
            })
            ->leftJoin('attend_details', function ($join) use ($class_seq) {
                $join->on('attends.id', '=', 'attend_details.attend_seq')
                    ->whereColumn('attend_details.class_seq', '=', 'class_mates.class_seq');
            })
            ->leftJoin('absents', function ($join) use ($class_seq) {
                $join->on('absents.student_seq', '=', 'class_mates.student_seq')
                    ->where('absents.absent_date', '=', DB::raw('date(now())'))
                    ->whereColumn('absents.class_seq', '=',  'class_mates.class_seq');
            })
            ->where('class_mates.is_use', 'Y')
            ->where('class_mates.student_seq', '!=', 'null')
            ->where('class_mates.class_seq', '!=', 'null')
            ->where('class_mates.team_code', $team_code);



        // 조건
        if(strlen($no_class) < 1 || $no_class == 'N'){
            $students = $students->where('class_mates.class_seq', $class_seq);
        }
        if($student_seq??''){
            $students = $students->where('class_mates.student_seq', $student_seq);
        }

        if($is_add_coulmn  == 'Y'){
            //no_class 시 방과후 학생관리에서 진입
            $students = $students
                // ->addSelect('students.class_name as st_class_name')
                ->addSelect('students.student_id')
                ->addSelect('students.student_phone')
                ->addSelect('students.is_use')
                ->addSelect('parents.parent_name')
                ->addSelect('parents.id as parent_seq')
                ->addSelect('parents.parent_id')
                ->addSelect('parents.parent_phone')
                ->addSelect('classes.class_name as cl_class_name')
                ->addSelect('class_mates.start_date as class_start_date')
                ->addSelect('class_mates.end_date as class_end_date')
                ->leftJoin('classes', 'class_mates.class_seq', '=', 'classes.id')
                ->leftJoin('parents', 'students.parent_seq', '=', 'parents.id')
                ->groupBy(
                    'class_mates.id',
                    'class_mates.class_seq',
                    'class_mates.student_seq',
                    'students.student_name',
                    'grade_codes.code_name',
                    'students.class_name',
                    'students.student_id',
                    'students.student_phone',
                    'students.is_use',
                    'parents.parent_name',
                    'parents.parent_id',
                    'parents.parent_phone',
                    'classes.class_name'
                );
        }else{
            $students = $students
            ->groupBy(
                'class_mates.id',
                'class_mates.class_seq',
                'class_mates.student_seq',
                'students.student_name',
                'grade_codes.code_name',
                'students.class_name'
            );
        }
        $students = $students->orderBy('students.id', 'desc');

        if($search_type){
            if ($search_type == 'name') {
                $students = $students->where('students.student_name', 'like', '%' . $search_str . '%');
            }
            else if($search_type == 'class_name'){
                $students = $students->where('classess.class_name', 'like', '%' . $search_str . '%');
            }else if($search_type == 'parent_name'){
                $students = $students->where('parents.parent_name', 'like', '%' . $search_str . '%');
            }else if($search_type == 'student_id'){
                $students = $students->where('students.student_id', 'like', '%' . $search_str . '%');
            }else if($search_type == 'grade_name'){
                $students = $students->where('grade_codes.code_name', 'like', '%' . $search_str . '%');
            }else if($search_type == 'is_today'){
                $students = $students->where('students.created_at', '>=', date('Y-m-d 00:00:00'));
            }else if($search_type == 'student_phone'){
                $students = $students->where('students.student_phone', 'like', '%' . $search_str . '%');
            }
        }

        $result['sql'] = $students->toSql();
        $result['sql_bind'] = $students->getBindings();
        if(($is_page??'') == 'Y') $students = $students->paginate($page_max, ['*'], 'page', $page);
        else $students = $students->get();

        $student_seqs = $students->pluck('student_seq')->toArray();

        //  오늘로부터 마지막 방과후 수업을 한 날짜를 불러온다. 오늘의 전날을 불러온다.
        $each_dates = $this->getEachDate($class_seq);
        $yesterday_date = $each_dates['yesterday_date'];
        $last_lecture_date = $each_dates['last_lecture_date'];

        // 수강완료
        $complete_cnts = \App\StudentLectureDetail::select(
            'student_seq',
            DB::raw('count(*) as complete_cnt')
        )
            ->where('status', 'complete')
            ->whereIn('student_seq', $student_seqs)
            ->groupBy('student_seq');
            if($is_search_today == 'Y'){
                $complete_cnts = $complete_cnts
                ->whereBetween('sel_date', [$last_lecture_date, $yesterday_date]);
            }else{
                $complete_cnts = $complete_cnts
                ->where('sel_date', '<', date('Y-m-d'));
            }
            $complete_cnts = $complete_cnts->get()->keyBy('student_seq');

        // 미수강, 오답 미완료 cnt 가져오기.
        $incomplete_cnts = \App\StudentLectureDetail::select(
            'student_seq',
            DB::raw('count(*) as incomplete_cnt')
        )
            ->leftJoin('lecture_details', 'student_lecture_details.lecture_detail_seq', '=', 'lecture_details.id')
            ->where('status', '<>', 'complete')
            ->where('status', '<>', 'delete')
            ->whereIn('student_seq', $student_seqs)
            ->where('lecture_details.lecture_detail_link', '<>', '')
            ->groupBy('student_seq');
            if($is_search_today == 'Y'){
                $incomplete_cnts = $incomplete_cnts
                ->whereBetween('sel_date', [$last_lecture_date, $yesterday_date]);
            }else{
                $incomplete_cnts = $incomplete_cnts
                ->where('sel_date', '<', date('Y-m-d'));
            }
            $incomplete_cnts = $incomplete_cnts->get()->keyBy('student_seq');


        $inwrong_cnts = \App\StudentExam::select(
            'student_exams.student_seq',
            // 'student_exams.id as student_exam_seq',
            DB::raw("sum(if(student_exam_results.exam_status = 'wrong' and  student_exam_results.wrong_status is null, 1, 0)) as wrong_count")
            // DB::raw("count(*) as wrong_count2")
        )
            ->leftJoin('student_exam_results', 'student_exams.id', '=', 'student_exam_results.student_exam_seq')
            ->leftJoin('student_lecture_details', 'student_exams.student_lecture_detail_seq', '=', 'student_lecture_details.id')
            // ->where('student_exams.complete_datetime', '<', date('Y-m-d H:i:s'))
            ->where('student_exams.is_complete', 'Y')
            ->whereIn('student_exams.student_seq', $student_seqs)
            // ->groupBy('student_exams.student_seq', 'student_exams.id'); // 어차피 학생별로 체크하기때문에 굳이 학생의 시험으로 나눌필요가 없음.
            ->groupBy('student_exams.student_seq');
            if($is_search_today == 'Y'){
                $inwrong_cnts = $inwrong_cnts
                ->whereBetween('student_lecture_details.sel_date', [$last_lecture_date, $yesterday_date]);
            }else{
                $inwrong_cnts = $inwrong_cnts
                ->where('student_exam_results.exam_status', 'wrong')
                ->whereNull('student_exam_results.wrong_status');
            }
            $inwrong_cnts_rt = (clone $inwrong_cnts)->get()->groupBy('student_seq');
            $inwrong_only_cnts = (clone $inwrong_cnts)->get()->where('wrong_count', '>', '0')->count();


        // 클래스 출석한 일수.:
        // 요일/시작끝일을 가져온다.
        $total_days = \App\ClassStudyDate::where('class_seq', $class_seq)->get()->pluck('class_day')->toArray();
        $start_date = date('Y-m-01');
        $end_date = date('Y-m-t');

        $attend_cnt = \App\Date::
            selectRaw(
                'student_seq, class_seq, attend_date, max(1) cnt'
            )
            ->leftJoin('attend_details', function($join) use ($class_seq){
                $join->on('dates.date', '=', 'attend_details.attend_date')
                    ->where('attend_details.class_seq', $class_seq)
                    ->whereNotNull('attend_details.student_seq');
            })
            ->whereBetween('date', [$start_date, $end_date])
            ->whereIn('day', $total_days)
            ->where('attend_details.class_seq', $class_seq)
            ->groupBy('student_seq', 'class_seq', 'attend_date')
            ->get()
            ->groupBy('student_seq');



        // 결과
        /* $result = array(); */
        $result['resultCode'] = 'success';
        $result['class_mates'] = $students;
        $result['incomplete_cnts'] = $incomplete_cnts;
        $result['inwrong_only_cnts'] = $inwrong_only_cnts;
        $result['complete_cnts'] = $complete_cnts;
        $result['inwrong_cnts'] = $inwrong_cnts_rt;
        $result['attend_cnts'] = $attend_cnt;
        $result['last_lecture_date'] = $last_lecture_date;
        $result['yesterday_date'] = $yesterday_date;
        return response()->json($result);
    }

    // 클래스 학생 출석 하기.
    public function classStudentAttend(Request $request)
    {
        $class_seq = $request->input('class_seq');
        $team_code = $request->input('team_code');
        $student_seqs  = $request->input('student_seqs');
        $class_name = $request->input('class_name');
        $class_start_time = $request->input('class_start_time');
        $users = $request->input('users'); // 배열

        // $users 가 있으면, $student_seqs에 넣어준다.
        if($users && count($users) > 0 ) {
            foreach ($users as $user){
                $student_seqs[] = $user['student_seq'];
            }
        }

        // $student_seqs 배열.
        // 트랙잭션 시작.
        $is_transaction_suc = true;
        $no_attend_students = [];
        DB::beginTransaction();
        try {
            // foreach ($student_seqs as $student_seq) {
            foreach ($student_seqs as $idx => $student_seq) {
                // 만약 오늘이면서 student_seq, class_seq가 결석태이블에 있으면 삭제.
                //만약 users 가 있으면 나머지 변수들도 넣어준다.
                if($users && count($users) > 0){
                    $user = $users[$idx];
                    $class_seq= $user['class_seq'];
                    $team_code= $user['team_code'];
                    $class_name= $user['class_name'];
                    $class_start_time = $user['class_start_time'];
                }

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

                $absent = \App\Absent::where('student_seq', $student_seq)->where('absent_date', date('Y-m-d'))->where('class_seq', $class_seq)->first();
                if ($absent) {
                    $absent->delete();
                }
                // 오늘 이면서 student_seq를 가져온다.
                $attend = \App\Attend::where('student_seq', $student_seq)->where('attend_date', date('Y-m-d'))->first();
                //없으면 생성.
                if (!$attend) {
                    $attend = new \App\Attend;
                    $attend->team_code = $team_code;
                    $attend->student_seq = $student_seq;
                    $attend->attend_date = date('Y-m-d');
                    $attend->start_time = date('H:i:s');
                    $attend->save();
                } else {
                    // $attend->end_time = date('H:i:s');
                    // $attend->save();
                }
                $attend_seq = $attend->id;
                $attend_detail = \App\AttendDetail::where('student_seq', $student_seq)->where('attend_seq', $attend_seq)->where('class_seq', $class_seq)->first();
                // 없으면 생성.
                if (!$attend_detail) {
                    $attend_detail = new \App\AttendDetail;
                    $attend_detail->student_seq = $student_seq;
                    $attend_detail->attend_datetime = date('Y-m-d H:i:s');
                    $attend_detail->attend_date = date('Y-m-d');
                    $attend_detail->attend_time = date('H:i:s');
                    $attend_detail->class_start_time = $class_start_time;
                    $attend_detail->attend_seq = $attend_seq;
                    $attend_detail->class_seq = $class_seq;
                    $attend_detail->class_name = $class_name;
                    $attend_detail->team_code = $team_code;
                    $attend_detail->save();
                }
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

    // 출석 취소하기.
    public function classStudentAttendCancel(Request $request)
    {
        $class_seq = $request->input('class_seq');
        $team_code = $request->input('team_code');
        $student_seq = $request->input('student_seq');

        // 트랙잭션 시작.
        $is_transaction_suc = true;
        DB::beginTransaction();
        try {
            // 오늘 이면서 student_seq를 가져온다.
            $attend = \App\Attend::where('student_seq', $student_seq)->where('attend_date', date('Y-m-d'))->first();
            if (!$attend) {
                DB::commit();
                return;
            }
            $attend_seq = $attend->id;

            // 오늘날짜면서, 클래스 시퀀스, 학생 시퀀스 같은 것을 가져온다.
            $attend_details = \App\AttendDetail::where('student_seq', $student_seq)->where('class_seq', $class_seq)->where('attend_seq', $attend_seq)->get();
            foreach ($attend_details as $attend_detail) {
                $attend_detail->delete();
            }

            //카운트 가져와서 0이면 attend 삭제.
            $attend_details_cnt = \App\AttendDetail::where('attend_seq', $attend_seq)->count();
            if ($attend_details_cnt == 0) {
                $attend->delete();
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
        return response()->json($result);
    }

    // 결석 사유 넣기.
    public function classStudentAttendAbsentReason(Request $request)
    {
        $class_seq =  $request->input('class_seq');
        $team_code = $request->input('team_code');
        $student_seqs = $request->input('student_seqs');
        $absent_reason = $request->input('absent_reason');
        $absent_start_time = $request->input('absent_start_time');
        $absent_day = $request->input('absent_day');


        // 트랙잭션 시작.
        $is_transaction_suc = true;
        DB::beginTransaction();
        try {
            foreach ($student_seqs as $student_seq) {
                // 오늘 그학생 클래스로 출석이 되어있으면 넘어간다.
                $attend_detail = \App\AttendDetail::where('student_seq', $student_seq)->where('class_seq', $class_seq)->where('attend_date', date('Y-m-d'))->first();
                if ($attend_detail) {
                    // continue;
                    //끝나면 학생출석은 삭제 처리.
                    // $request 를 가져와서 복사본을 만들고 student_seq를 넣어준다.
                    $request_copy = $request->duplicate();
                    $request_copy->merge(['student_seq' => $student_seq]);
                    $this->classStudentAttendCancel($request_copy);
                }
                // 오늘 이면서 같은 class_seq , student_seq를 가져온다.
                $absent = \App\Absent::where('student_seq', $student_seq)->where('absent_date', date('Y-m-d'))->where('class_seq', $class_seq)->first();
                if ($absent) {
                    $absent->absent_reason = $absent_reason;
                    $absent->save();
                } else {
                    $absent = new \App\Absent;
                    $absent->team_code = $team_code;
                    $absent->student_seq = $student_seq;
                    $absent->absent_date = date('Y-m-d');
                    $absent->absent_reason = $absent_reason;
                    $absent->class_seq = $class_seq;
                    $absent->absent_start_time = $absent_start_time;
                    $absent->absent_day = $absent_day;
                    $absent->save();
                }
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
        return response()->json($result);
    }

    // 보강일 변경/지정.
    public function classStudentReinforcementDateInsert(Request $request)
    {
        $class_seq = $request->input('class_seq');
        $team_code = $request->input('team_code');
        $student_seq = $request->input('student_seq');
        $ref_date = $request->input('ref_date');

        // 트랙잭션 시작.
        $is_transaction_suc = true;
        DB::beginTransaction();
        try {
            // 보강일 등록시 학생
            $absnet = \App\Absent::where('student_seq', $student_seq)
                ->where('absent_date', date('Y-m-d'))
                ->where('class_seq', $class_seq)
                ->first();

            if ($absnet) {
                $absnet->ref_date = $ref_date;
                $absnet->save();
            } else {
                $absnet = new \App\Absent;
                $absnet->team_code = $team_code;
                $absnet->student_seq = $student_seq;
                $absnet->absent_date = date('Y-m-d');
                $absnet->ref_date = $ref_date;
                $absnet->class_seq = $class_seq;
                $absnet->save();
            }

            //끝나면 학생출석은 삭제 처리.
            $this->classStudentAttendCancel($request);
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
        return response()->json($result);
    }

    // 금일 보강일정 리스트 불러오기.
    function classReinforcementDateSelect(Request $request)
    {
        $class_seq = $request->input('class_seq');
        $team_code = $request->input('team_code');
        $today = $request->input('today');
        //우선 받아 오긴 하지만. 서버 시간으로 변경.
        $today = date('Y-m-d');

        // 결석(absents )테이블 중에 위 조건 지정 + ref_date가 있는 row를 가져온다.
        $absents = \App\Absent::select(
            'absents.id',
            'absents.student_seq',
            'absents.absent_reason',
            'absents.absent_start_time',
            'absents.absent_date',
            'absents.absent_day',
            'absents.ref_date',
            'students.student_name',
            'grade_codes.code_name as grade_name',
            'absents.is_ref_complete'
        )
            ->leftJoin('students', 'absents.student_seq', '=', 'students.id')
            ->leftJoin('codes as grade_codes', 'students.grade', '=', 'grade_codes.id')
            ->where('absents.class_seq', $class_seq)
            ->where('absents.ref_date', $today)
            ->whereNotNull('absents.ref_date')
            ->get();

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        $result['refs'] = $absents;
        return response()->json($result);
    }

    // 보강 완료 클릭
    public function classReinforcementDateComplete(Request $request)
    {
        $student_seq = $request->input('student_seq');
        $class_seq = $request->input('class_seq');
        $team_code = $request->input('team_code');
        $absent_seq = $request->input('absent_seq');

        // 트랙잭션 시작.
        $is_transaction_suc = true;
        DB::beginTransaction();
        try {
            $absent = \App\Absent::where('student_seq', $student_seq)
                ->where('id', $absent_seq)
                ->where('class_seq', $class_seq)
                ->first();

            if ($absent) {
                $absent->is_ref_complete = 'Y';
                $absent->save();
            } else {
                //위에서 사용된 $absent  sql문 가져오기


                $is_transaction_suc = false;
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
        return response()->json($result);
    }

    // 수업시작하기 > 상단 오늘의 현황 카운트 불러오기.
    public function classTodayStatusCount(Request $request)
    {
        $class_seq = $request->input('class_seq');
        $team_code = $request->input('team_code');
        $today = date('Y-m-d');

        //오늘 결석 한 학생수.
        $parameter = array(
            'type' => 'absent_cnt',
            'date' => $today,
            'class_seq' => $class_seq
        );
        $absent_cnt = $this->getStatusCnt($parameter);

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        $result['absent_cnt'] = $absent_cnt;
        return response()->json($result);
    }

    // 오늘의 수업요약 불러오기.
    public function classTodayStatusCountMain(Request $request)
    {
        $class_seq = $request->input('class_seq');
        $team_code = $request->input('team_code');
        $teach_seq = session()->get('teach_seq');
        $today = date('Y-m-d');

        //오늘 결석 한 학생수.
        $absent_cnt = $this->getStatusCnt(array( 'type' => 'absent_cnt', 'date' => $today));
        $attend_cnt = $this->getStatusCnt(array( 'type' => 'attend_cnt', 'date' => $today));
        $reinforcement_cnt = $this->getStatusCnt(array( 'type' => 'reinforcement_cnt', 'date' => $today));
        $new_cnt = $this->getStatusCnt(array( 'type' => 'new_cnt', 'date' => $today));

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        $result['absent_cnt'] = $absent_cnt;
        $result['attend_cnt'] = $attend_cnt;
        $result['ref_cnt'] = $reinforcement_cnt;
        $result['new_cnt'] = $new_cnt;
    }

    private function getStatusCnt($parameter)
    {
        $type = $parameter['type'];
        $date = $parameter['date'];
        $class_seq = $parameter['class_seq']??'';
        $teach_seq = session()->get('teach_seq');
        $team_code = session()->get('team_code');

        //:방과후 / :결석, :출석, :보강
        $clone_sql = \App\ClassStudyDate::select(
            'class_study_dates.class_seq',
            'class_study_dates.class_day',
            'class_study_dates.start_time',
            'class_study_dates.end_time',
            'class_mates.student_seq',
            'attend_details.attend_date',
            'absents.absent_date',
            'ab2.ref_date'
        )
        ->leftJoin('class_mates', function ($join) use ($class_seq) {
            $join->on('class_study_dates.class_seq', '=', 'class_mates.class_seq');
        })
        ->leftJoin('attend_details', function ($join) use ($class_seq, $date) {
            $join->on('class_study_dates.class_seq', '=', 'attend_details.class_seq')
                ->on('class_mates.student_seq', '=', 'attend_details.student_seq')
                ->where('attend_details.attend_date', $date);
        })
        ->leftJoin('absents', function ($join) use ($class_seq, $date) {
            $join->on('class_study_dates.class_seq', '=', 'absents.class_seq')
                ->on('class_mates.student_seq', '=', 'absents.student_seq')
                ->where('absents.absent_date', $date);
        })
        ->leftJoin('absents as ab2', function ($join) use ($class_seq, $date) {
            $join->on('class_study_dates.class_seq', '=', 'ab2.class_seq')
                ->on('class_mates.student_seq', '=', 'ab2.student_seq')
                ->where('ab2.ref_date', $date);
        })
        ->where('class_study_dates.class_day', function ($query) use ($date) {
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
        ->where('class_study_dates.team_code', $team_code)
        ->whereIn('class_study_dates.class_seq', function ($query) use ($class_seq, $teach_seq) {
            //     if($class_seq){
            //         $query->select('id')->from('classes')->where('id', $class_seq);
            //     }else{
                    $query->select('id')->from('classes')->where('teach_seq', $teach_seq);
            //     }
            })
        ->where('class_study_dates.team_code', $team_code);


        if ($type == 'new_cnt') {
            //신규등록 수치
            $new_cnt = \App\ClassMate::whereIn('class_seq', function ($query) use ($teach_seq, $team_code) {
                    $query->select('id')->from('classes')->where('teach_seq', $teach_seq)->where('team_code', $team_code);
                })
                ->whereDate('created_at', $date)->count();
            return $new_cnt;
        } else if ($type == 'absent_cnt') {

            $absent_cnt = (clone $clone_sql)
                ->where('absents.absent_date', $date)
                ->when($class_seq, function ($query) use ($class_seq) {
                    return $query->where('class_study_dates.class_seq', $class_seq);
                })
                ->count();
            // //attend_date = null, absent_date =null, end_time 이 현재 시간보다 이전이면 결석으로 처리.
            // $absent_cnt += (clone $clone_sql)
            // ->whereNull('attend_details.attend_date')
            // ->whereNull('absents.absent_date')
            // ->where('class_study_dates.end_time', '<', date('H:i:s'));
            // if($class_seq){
            //     $absent_cnt = $absent_cnt->where('class_study_dates.class_seq', $class_seq);
            // }
            // $absent_cnt = $absent_cnt->count();

            $absent_cnt += (clone $clone_sql)
                ->whereNull('attend_details.attend_date')
                ->whereNull('absents.absent_date')
                ->where('class_study_dates.end_time', '<', date('H:i:s'))
                ->when($class_seq, function ($query) use ($class_seq) {
                    return $query->where('class_study_dates.class_seq', $class_seq);
                })
                ->count();

            return $absent_cnt;
        } else if ($type == 'attend_cnt') {
            $attend_cnt = (clone $clone_sql)->where('attend_details.attend_date', $date)->count();
            return $attend_cnt;
        } else if ($type == 'reinforcement_cnt') {
            $reinforcement_cnt = (clone $clone_sql)->where('ab2.ref_date', $date)->count();
            return $reinforcement_cnt;
        }

    }

    function getEachDate($class_seq){

        $today = date('Y-m-d');
        $yesterday_date = date('Y-m-d', strtotime($today . ' -1 day'));
        // select group_concat(class_day) class_day from class_study_dates where class_seq in (3);
        $class_study_dates = \App\ClassStudyDate::where('class_seq', $class_seq)
            ->select(DB::raw('group_concat(class_day) as class_day'))
            ->first();
        $class_days = explode(',', $class_study_dates->class_day);

        $dates  = \App\Date::select('date', 'day')
            ->where('date', '<', $today)
            ->where('date', '>', date('Y-m-d', strtotime($today . ' -1 month')))
            ->whereIn('day', $class_days)
            ->get();
        // 마지막 수업일
        $last_lecture_date = $dates->max('date');
        $last_lecture_day = $dates->where('date', $last_lecture_date)->first()->day??'';



        // 결과
        $result['last_lecture_date'] = $last_lecture_date;
        $result['yesterday_date'] = $yesterday_date;
        return $result;
    }
}
