<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDO;
use App\Team;
use App\Region;
use App\TeamArea;
use App\Teacher;
// WARN: 여기 클래스의 funtion 중 다른 컨트롤러에서 상속하는 함수가 있기 때문에 수정시 주의.
class LearningMTController extends Controller
{
    public function calendar(Request $request)
    {
        // 선생님일 경우 login_type = teacher
        if (session()->get('login_type') == 'teacher') {
            return $this->teacherCalendar($request);
        }
        // 학생일 경우 login_type = student
        else if (session()->get('login_type') == 'student') {
            return $this->studentCalendar($request);
        }
        // 학부모일경우 login_type = parent
        else if (session()->get('login_type') == 'parent') {
            return $this->parentCalendar($request);
        }
    }
    //teacher calendar
    public function teacherCalendar(Request $request)
    {
        $main_code = $_COOKIE['main_code'];
        $team_code = session()->get('team_code');
        $login_type = $request->session()->get('login_type');

        //소속 가져오기
        if ($team_code != 'maincd') {
            // 관리자 아닐경우.
            $regions = \App\Region::whereIn('id', function ($query) use ($team_code) {
                $query->select('region_seq')
                    ->from('teams')
                    ->where('team_code', $team_code);
            })->get();

            $main_code = $_COOKIE['main_code'];
        } else {
            // 관리자
            $regions = \App\Region::where('main_code', $main_code)->get();
        }
        // 과목
        $subject_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'subject')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        // 시리즈
        $series_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'series')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        // 분류 ex 특강, 문해력, 학교공부
        $course_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'course')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        // 출판사
        $publisher_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'publisher')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();


        // 학생 가져오기
        $students = null;
        $student_seq = $request->input('student_seq');
        if ($student_seq != null) {
            $student_seq = explode(',', $student_seq);
            $students = \App\Student::whereIn('students.id', $student_seq)
                ->leftJoin('codes', 'students.grade', '=', 'codes.id')
                ->select(
                    'students.id',
                    'students.student_name',
                    'codes.code_name as grade_name'
                )
                ->get();
        }

        // 학년 가져오기
        // $grade_codes = \App\Code::where('code_category', 'grade')->where('code_step', '=', 1)->where('main_code', $main_code)->get();

        // 샘플 시간표가져오기.
        $timetable_groups = \App\TimetableGroup::where('main_code', '=', $main_code)->get();




        // return view('admin.admin_learning_calendar', ['regions'=>$regions]);
        return view('admin.admin_learning_calendar', [
            'regions' => $regions,
            'subject_codes' => $subject_codes,
            // 'grade_codes' => $grade_codes,
            'timetable_groups' => $timetable_groups,
            'main_code' => $main_code,
            'students' => $students,
            'login_type' => $login_type,
            'series_codes' => $series_codes,
            'course_codes' => $course_codes,
            'publisher_codes' => $publisher_codes
        ]);
    }

    //student calendar
    public function studentCalendar(Request $request)
    {
        $login_type = $request->session()->get('login_type');
        $student_seq = $request->session()->get('student_seq');
        $main_code = $request->session()->get('main_code');

        $students = \App\Student::where('id', $student_seq)->get();
        $subject_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'subject')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        return view('admin.admin_learning_calendar', [
            // 'regions' => $regions,
            'subject_codes' => $subject_codes,
            // 'grade_codes' => $grade_codes,
            // 'timetable_groups' => $timetable_groups,
            'main_code' => $main_code,
            'students' => $students,
            'login_type' => $login_type
        ]);
    }
    // parent calendar
    public function parentCalendar(Request $request)
    {
        $login_type = $request->session()->get('login_type');
        $parent_seq = $request->session()->get('parent_seq');
        $student_seq = session()->get('student_seq');
        // 자녀 정보 가져오기.
        // TODO: 일단은 첫 자녀를 가져오는데 추후 수정 필요
        $students = \App\Student::where('students.id', $student_seq)
            ->leftJoin('codes', 'students.grade', '=', 'codes.id')
            ->select(
                'students.id',
                'students.student_name',
                'codes.code_name as grade_name',
                'students.main_code'
            )
            ->get();
        if (count($students) > 0) {
            $student = $students[0];
            $student_seq = $student->id;
            $main_code = $student->main_code;
            $subject_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'subject')->where('code_step', '=', 1)
                ->orderBy('code_idx', 'asc')
                ->get();
            $timetable_groups = \App\TimetableGroup::where('main_code', '=', $main_code)->get();


            // 분류 ex 특강, 문해력, 학교공부
            $course_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'course')->where('code_step', '=', 1)
                ->orderBy('code_idx', 'asc')
                ->get();

            // 출판사
            $publisher_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'publisher')->where('code_step', '=', 1)
                ->orderBy('code_idx', 'asc')
                ->get();
        } else {
            //메인으로 보내기.
            return redirect('/parent');
        }


        return view('admin.admin_learning_calendar', [
            'subject_codes' => $subject_codes,
            'main_code' => $main_code,
            'students' => $students,
            'login_type' => $login_type,
            'timetable_groups' => $timetable_groups,
            'course_codes' => $course_codes,
            'publisher_codes' => $publisher_codes
        ]);
    }
    // 학습 시작 시간 저장하기
    public function studyTimeInsert(Request $request)
    {
        $student_seqs = $request->input('student_seqs');
        $select_dates = $request->input('select_dates');
        $select_time = $request->input('select_time');
        $is_repeat = $request->input('is_repeat');

        // 배열 변환 ','
        $student_seqs = explode(',', $student_seqs);
        $select_dates = explode(',', $select_dates);

        // 학생 별로 저장.
        // 여러 데이터를 저장할때는 트랜잭션 처리.
        DB::beginTransaction();
        try {
            foreach ($student_seqs as $student_seq) {
                foreach ($select_dates as $select_date) {
                    \App\StudyTime::updateOrCreate(
                        ['student_seq' => $student_seq, 'select_date' => $select_date],
                        ['select_time' => $select_time, 'is_repeat' => $is_repeat]
                    );
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 학습 시작 시간 가져오기
    public function studyTimeSelect(Request $request)
    {
        $student_seqs = $request->input('student_seqs');
        $search_start_date = $request->input('search_start_date');
        $search_end_date = $request->input('search_end_date');

        // 배열 변환
        $student_seqs = explode(',', $student_seqs);

        $study_times = \App\StudyTime::whereIn('student_seq', $student_seqs)
            ->whereBetween('select_date', [$search_start_date, $search_end_date])
            ->orderBy('student_seq', 'asc')
            ->orderBy('select_date', 'asc')
            ->get();

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        $result['study_times'] = $study_times;
        return response()->json($result);
    }

    // 학습 시작 시간 삭제
    function studyTimeDelete(Request $request)
    {
        $study_time_seqs = $request->input('study_time_seqs');

        // 배열 변환
        $study_time_seqs = explode(',', $study_time_seqs);

        // 삭제
        \App\StudyTime::whereIn('id', $study_time_seqs)->delete();

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 과목 > 시리즈 선택
    public function subjectSeriesSelect(Request $request)
    {
        $subject_seq = $request->input('subject_seq');
        $teacher_seq = session()->get('teach_seq');
        $student_seqs = $request->input('student_seqs');

        $series_codes = \App\Code::select(
            'codes.*'
        )
        // ->leftJoin('teacher_lectures_permissions', 'codes.id', '=', 'teacher_lectures_permissions.code_id')
        ->whereIn('codes.id', function ($query) use ($subject_seq) {
            $query->select('code_seq')
                ->from('code_connects')
                ->where('code_pt', $subject_seq);
        })
        ->where('codes.code_category', 'series')
        ->where('codes.code_step', '=', 1);

        // TODO:정확한 기획이나, 서술이 없어서 임의로 진행. (시리즈 선생님 권한으로 보이는 기능)
        // 문제점. 기획이 없음.
        // 1. 방과후와, 아닐때에 대한 분기 정보 없음.
        // 2. 학부모, 선생님일때 어떻게 다르게 보여줘야 하는지에 대한 분기 정보가 없음.
        // 3. 단일 학생이 아니라, 복수 학생일 때 어떻게 표기해야하는지.
        //
        // 무조건 방과후라고 치고, 단일학생일때.
        if(count($student_seqs) < 2){
            $teacher_seqs = \App\ClassTb::select('teach_seq')
                ->whereIn('id', function ($query) use ($student_seqs) {
                    $query->select('class_seq')
                        ->from('class_mates')
                        ->whereIn('student_seq', $student_seqs);
                })
                ->get()->toArray();

            $series_codes = $series_codes->whereIn('id', function ($query) use ($teacher_seqs) {
                $query->select('code_seq')
                    ->from('teacher_lectures_permissions')
                    ->whereIn('teach_seq', $teacher_seqs);
            });
        }

        $series_codes = $series_codes->get();
        // ->where('teacher_lectures_permissions.teacher_id', '=', $teacher_seq)->get();
        // TODO: 추후 수정 필요 선생님 퍼미션.
        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        $result['series_codes'] = $series_codes;
        return response()->json($result);
    }

    // 학생 강의 수업을 추가
    public function studentLectureInsert(Request $request)
    {
        $student_seqs = $request->input('student_seqs');
        $group_parameter = $request->input('group_parameter');

        // 추후 수정이 있을수 있으니 id변수 남김.
        $student_lecture_seq = $request->input('student_lecture_seq');

        // 배열 변환 / 학생만 스트링으로 들어옴.
        $student_seqs = explode(',', $student_seqs);

        // 학생 별로 저장.
        // 여러 데이터를 저장할때는 트랜잭션 처리.
        // 커밋이 되었느지 확인하기위해 변수 선언
        $is_commit = false;
        DB::beginTransaction();
        try {
            foreach ($student_seqs as $student_seq) {
                foreach ($group_parameter as $parameter) {
                    $start_date = $parameter['start_date'];
                    $end_date = $parameter['end_date'];
                    $lecture_seq = $parameter['lecture_seq'];
                    $start_lecture_detail_seq = $parameter['start_lecture_detail_seq'];
                    $days = $parameter['days'];
                    $lecture_detail_parts = $parameter['lecture_detail_parts'];

                    $st_lecture = \App\StudentLecture::updateOrCreate(
                        ['id' => $student_lecture_seq],
                        [
                            'start_date' => $start_date,
                            'end_date' => $end_date,
                            'student_seq' => $student_seq,
                            'lecture_seq' => $lecture_seq,
                            'start_lecture_detail_seq' => $start_lecture_detail_seq,
                            'is_sun' => $days['is_sun'],
                            'is_mon' => $days['is_mon'],
                            'is_tue' => $days['is_tue'],
                            'is_wed' => $days['is_wed'],
                            'is_thu' => $days['is_thu'],
                            'is_fri' => $days['is_fri'],
                            'is_sat' => $days['is_sat']
                        ]
                    );
                    $st_lecture_seq = $st_lecture->id;

                    if (count($lecture_detail_parts) > 0) {
                        // 강의 상세 부분
                        foreach ($lecture_detail_parts as $lecture_detail_part) {
                            $student_lecture_detail = new \App\StudentLectureDetail;
                            $student_lecture_detail->student_seq = $student_seq;
                            $student_lecture_detail->student_lecture_seq = $st_lecture_seq;
                            $student_lecture_detail->lecture_detail_seq = $lecture_detail_part['lecture_detail_seq'];
                            $student_lecture_detail->sel_date = $lecture_detail_part['date'];
                            $student_lecture_detail->sel_day = $lecture_detail_part['day'];
                            $student_lecture_detail->save();
                        }
                    }
                }
            }
            DB::commit();
            $is_commit = true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        // 결과
        $result = array();
        $result['resultCode'] = $is_commit ? 'success' : 'fail';
        // $result['student_lecture'] = $st_lecture;
        return response()->json($result);
    }

    // 학생 강의 수업을 가져오기 / 학생의 학습시간표 가져오기.
    public function studyPlannerSelect(Request $request)
    {
        $student_seqs =  $request->input('student_seqs');
        $search_start_date = $request->input('search_start_date');
        $search_end_date = $request->input('search_end_date');
        $select_type = $request->input('select_type');
        $search_status = $request->input('search_status');

        // 배열 변환
        $student_seqs = explode(',', $student_seqs);

        // 학생별 학습시간표 가져오기 / 기본 조건 검색 학생
        $student_lecture_details = \App\StudentLectureDetail::where('student_lecture_details.student_seq', $student_seqs[0])->whereNull('student_lecture_details.lecture_type'); // 학교공부제외.
        // 날짜검색.
        // status = delete 제외 한다. 삭제이므로.
        $student_lecture_details = $student_lecture_details
            ->whereBetween('sel_date', [$search_start_date, $search_end_date])
            ->whereNull('student_lecture_details.lecture_type') // 학교공부제외.
            ->where('student_lecture_details.status', '<>', 'delete');

        //select / gropu by
        if ($select_type == 'date_group') {
            // data_group일경우 일자로 과목에 따라 그룹
            $student_lecture_details = $student_lecture_details
                ->select(
                    'sel_date',
                    'codes.code_name as subject_name',
                    'codes.id as code_seq',
                    DB::raw('count(*) as cnt'),
                    DB::raw('sum(if(student_lecture_details.status = "complete", 1, 0)) as complete_cnt')
                )
                ->groupBy('sel_date', 'codes.code_name', 'codes.id');
        } else if ($select_type == 'no_group') {
            $student_lecture_details = $student_lecture_details
                ->select(
                    'student_lecture_details.*',
                    'lectures.lecture_name',
                    'lectures.lecture_description',
                    'lecture_details.lecture_detail_time',
                    'lecture_details.lecture_detail_link',
                    'lecture_details.idx',
                    'lecture_details.lecture_detail_description',
                    'lecture_details.lecture_detail_name',
                    'codes.code_name as subject_name',
                    'codes.function_code as subject_function_code',
                    'codes.id as code_seq'
                );

            if ($search_status) {
                $student_lecture_details = $student_lecture_details->where('student_lecture_details.status', $search_status);
            }

        }

        //left join
        $student_lecture_details = $student_lecture_details
            ->leftJoin('student_lectures', 'student_lectures.id', '=', 'student_lecture_details.student_lecture_seq')
            ->leftJoin('lecture_codes', function ($join) {
                $join->on('lecture_codes.lecture_seq', '=', 'student_lectures.lecture_seq')
                    ->where('lecture_codes.code_category', '=', 'subject');
            })
            ->leftJoin('codes', 'codes.id', '=', 'lecture_codes.code_seq')
            ->leftJoin('lecture_details', 'lecture_details.id', '=', 'student_lecture_details.lecture_detail_seq')
            ->leftJoin('lectures', 'lectures.id', '=', 'student_lectures.lecture_seq');

        //결과
        $result = array();
        $result['resultCode'] = 'success';
        // $result['sql'] = $student_lecture_details->toSql();
        // $result['bind'] = $student_lecture_details->getBindings();
        $result['student_lecture_details'] = $student_lecture_details->get();
        return response()->json($result);
    }

    //강의일정 범위검색
    public function studyPlannerDistinctSelect(Request $request)
    {
        $student_seqs =  $request->input('student_seqs');
        $search_start_date = $request->input('search_start_date');
        $search_end_date = $request->input('search_end_date');

        // 배열 변환
        $student_seqs = explode(',', $student_seqs);

        // 학생별 학습시간표 가져오기 / 기본 조건 검색 학생
        $student_lecture_details = \App\StudentLectureDetail::whereIn('student_lecture_details.student_seq', $student_seqs)->whereNull('student_lecture_details.lecture_type'); // 학교공부제외.
        // 날짜검색.
        // status = delete 제외 한다. 삭제이므로.
        $student_lecture_details = $student_lecture_details
            ->whereBetween('sel_date', [$search_start_date, $search_end_date])
            ->whereNull('student_lecture_details.lecture_type') // 학교공부제외.
            ->where('student_lecture_details.status', '<>', 'delete');

        $student_lecture_details = $student_lecture_details
            ->select(
                'student_lecture_details.*',
                'lectures.id as lecture_seq',
                'lectures.lecture_name',
                'lectures.lecture_description',
                'lecture_details.lecture_detail_time',
                'lecture_details.lecture_detail_link',
                'lecture_details.idx',
                'lecture_details.lecture_detail_description',
                'lecture_details.lecture_detail_name',
                'codes.code_name as subject_name',
                'codes.function_code as subject_function_code',
                'codes.id as code_seq'
            );
        //left join
        $student_lecture_details = $student_lecture_details
            ->leftJoin('student_lectures', 'student_lectures.id', '=', 'student_lecture_details.student_lecture_seq')
            ->leftJoin('lecture_codes', function ($join) {
                $join->on('lecture_codes.lecture_seq', '=', 'student_lectures.lecture_seq')
                    ->where('lecture_codes.code_category', '=', 'subject');
            })
            ->leftJoin('codes', 'codes.id', '=', 'lecture_codes.code_seq')
            ->leftJoin('lecture_details', 'lecture_details.id', '=', 'student_lecture_details.lecture_detail_seq')
            ->leftJoin('lectures', 'lectures.id', '=', 'student_lectures.lecture_seq')
            ->where('student_lecture_details.status', 'ready')
            ->distinct('student_lecture_details.lecture_detail_seq')
            ->orderBy('student_lecture_details.sel_date', 'asc');

        //결과
        $result = array();
        $result['resultCode'] = 'success';
        $result['student_lecture_details'] = $student_lecture_details->get();
        return response()->json($result);
    }

    public function studyPlannerDistinctDelete(Request $request)
    {
        try {
            $start_search_date = $request->input('start_search_date');
            $end_search_date = $request->input('end_search_date');
            $student_seqs = $request->input('student_seqs'); // Assuming this is passed in the request
            $lecture_seq = $request->input('lecture_seq');

            // Ensure $student_seqs is an array
            if (!is_array($student_seqs)) {
                $student_seqs = explode(',', $student_seqs);
            }

            // 학생별 학습시간표 가져오기 / 기본 조건 검색 학생
            $student_lecture_details = \App\StudentLectureDetail::whereIn('student_seq', $student_seqs)->whereNull('student_lecture_details.lecture_type'); // 학교공부제외.

            // 업데이트 쿼리
            DB::table('student_lecture_details')
            // TODO: 학습중인 학습도 delete 로 변경.
            // ->where('student_lecture_details.status', 'ready')
            ->whereBetween('student_lecture_details.sel_date', [$start_search_date, $end_search_date])
            ->whereIn('student_lecture_details.student_seq', $student_seqs)
            ->whereIn('student_lecture_details.lecture_detail_seq', function ($query) use ($lecture_seq) {
                $query->select('id')
                    ->from('lecture_details')
                    ->where('lecture_details.lecture_seq', $lecture_seq);
            })
            ->update(['student_lecture_details.status' => 'delete']);

            return response()->json([
            'resultCode' => 'success',
                'student_lecture_details' => $student_lecture_details
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'resultCode' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // 미수강, 재수강 SELECT
    public function studentDoLectureSelect(Request $request)
    {
        $student_seqs = $request->input('student_seqs');
        $step3_type = $request->input('step3_type');
        $subject_seq = $request->input('subject_seq');
        $start_search_date = $request->input('start_search_date');
        $end_search_date = $request->input('end_search_date');

        // 배열 변환 // 배열로 할지 1개만 할지 추후 결졍.
        $student_seqs = explode(',', $student_seqs);

        // 미수강 가져오기.
        if ($step3_type == 'nodo') {
            // 미수강
            $student_lecture_details = \App\StudentLectureDetail::whereIn('student_lecture_details.student_seq', $student_seqs)
                ->whereNull('student_lecture_details.lecture_type') // 학교공부제외.
                ->where('student_lecture_details.status', 'ready')
                ->whereBetween('student_lecture_details.sel_date', [$start_search_date, $end_search_date])
                ->whereNull('student_lecture_details.lecture_type') // 학교공부제외.

                ->leftJoin('student_lectures', 'student_lectures.id', '=', 'student_lecture_details.student_lecture_seq')
                ->leftJoin('lectures', 'lectures.id', '=', 'student_lectures.lecture_seq')
                ->leftJoin('lecture_codes', function ($join) {
                    $join->on('lecture_codes.lecture_seq', '=', 'student_lectures.lecture_seq')
                        ->where('lecture_codes.code_category', '=', 'subject');
                })
                ->leftJoin('lecture_details', 'lecture_details.id', '=', 'student_lecture_details.lecture_detail_seq')
                ->leftJoin('codes', 'codes.id', '=', 'lecture_codes.code_seq')

                ->select(
                    'student_lecture_details.*',
                    'lectures.lecture_name',
                    'lecture_details.idx',
                    'lecture_details.lecture_detail_name',
                    'lecture_details.lecture_detail_description',
                    'codes.code_name as subject_name',
                    'codes.function_code as subject_function_code'
                );
            if ($subject_seq != 'all')
                $student_lecture_details = $student_lecture_details->where('codes.id', $subject_seq);
        } else if ($step3_type == 'redo') {
            $student_lecture_details = \App\StudentLectureDetail::whereIn('student_lecture_details.student_seq', $student_seqs)
                ->whereNull('student_lecture_details.lecture_type') // 학교공부제외.
                ->where('student_lecture_details.status', 'complete')
                ->whereBetween('student_lecture_details.sel_date', [$start_search_date, $end_search_date])
                ->whereNull('student_lecture_details.lecture_type') // 학교공부제외.

                ->leftJoin('student_lectures', 'student_lectures.id', '=', 'student_lecture_details.student_lecture_seq')
                ->leftJoin('lectures', 'lectures.id', '=', 'student_lectures.lecture_seq')
                ->leftJoin('lecture_codes', function ($join) {
                    $join->on('lecture_codes.lecture_seq', '=', 'student_lectures.lecture_seq')
                        ->where('lecture_codes.code_category', '=', 'subject');
                })
                ->leftJoin('lecture_details', 'lecture_details.id', '=', 'student_lecture_details.lecture_detail_seq')
                ->leftJoin('codes', 'codes.id', '=', 'lecture_codes.code_seq')

                ->select(
                    'student_lecture_details.*',
                    'lectures.lecture_name',
                    'lecture_details.idx',
                    'lecture_details.lecture_detail_name',
                    'lecture_details.lecture_detail_description',
                    'codes.code_name as subject_name',
                    'codes.function_code as subject_function_code'
                );
                if ($subject_seq != 'all')
                    $student_lecture_details = $student_lecture_details->where('codes.id', $subject_seq);
        }

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        $result['student_lecture_details'] = $student_lecture_details->get();
        return response()->json($result);
    }

    public function studentLectureDetailInsert(Request $request)
    {
        $student_seqs = $request->input('student_seqs');
        $student_lecture_detail_seqs = $request->input('student_lecture_detail_seqs');
        $sel_date  = $request->input('sel_date');
        $step3_type = $request->input('step3_type');

        // 배열 변환
        $student_seqs = explode(',', $student_seqs);

        // 학생 별로 저장.
        // 여러 데이터를 저장할때는 트랜잭션 처리.
        // 커밋이 되었느지 확인하기위해 변수 선언
        $is_commit = false;
        DB::beginTransaction();
        try {
            $find_slds = \App\StudentLectureDetail::whereIn('id', $student_lecture_detail_seqs)->get();
            foreach ($student_seqs as $student_seq) {
                foreach($find_slds as $find_sld) {
                    $new_sld = new \App\StudentLectureDetail;
                    $new_sld->student_seq = $student_seq;
                    $new_sld->student_lecture_seq = $find_sld->student_lecture_seq;
                    $new_sld->lecture_detail_seq = $find_sld->lecture_detail_seq;
                    $new_sld->sel_date = $sel_date;
                    $new_sld->sel_day = $find_sld->sel_day;
                    $new_sld->status = 'ready';

                    //미수강 INSERT일 경우만 부모(키) 를 넣어준다. /=링크역할
                    if ($step3_type == 'nodo') $new_sld->copy_pt_seq = $find_sld->id;
                    $new_sld->save();
                }
            }
            DB::commit();
            $is_commit = true;
        } catch (\Exception $e) {
            DB::rollback();
            $result['error_str'] = $e;
            throw $e;
        }

        // 결과
        $result = array();
        $result['find_slds'] = $find_slds;
        $result['resultCode'] = $is_commit ? 'success' : 'fail';
        return response()->json($result);
    }

    // 강의 지정 날짜로 이동하기.
    public function studentLectureDetailMove(Request $request)
    {
        $student_lecture_detail_seq = $request->input('student_lecture_detail_seq');
        $chg_date = $request->input('chg_date');

        // 강의 날짜 변경.
        \App\StudentLectureDetail::where('id', $student_lecture_detail_seq)->update(['sel_date' => $chg_date]);

        // 추후에 데이터 날짜를 당겨야 할경우 관련 [추가 코드] 필요.

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }
    // 강의 삭제하기
    public function studentLectureDetailDelete(Request $request)
    {
        $student_lecture_detail_seq = $request->input('student_lecture_detail_seq');

        // 배열 변환
        $student_lecture_detail_seqs = explode(',', $student_lecture_detail_seq);

        // 삭제
        // \App\StudentLectureDetail::whereIn('id', $student_lecture_detail_seq)->delete();

        // 데이터를 남기면서 status->delete로 변경
        \App\StudentLectureDetail::whereIn('id', $student_lecture_detail_seqs)->update(['status' => 'delete']);

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 학교 등록
    public function schoolInsert(Request $request)
    {
        // 데이터 유효성 검사
        $validator = Validator::make($request->all(), [
            'school_code' => 'required|unique:teams,team_code|unique:regions,main_code',
            'school_name' => 'required',
            'school_manager' => 'required',
            'school_seq' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['resultCode' => 'error', 'errors' => $validator->errors()], 422);
        }

        try {

            DB::transaction(function () use ($request) {
                $schoolCode = $request->input('school_code');
                $schoolName = $request->input('school_name');
                $area = $request->input('area');
                $teachGroupSeq = Teacher::find($request->input('school_manager'))->group_seq;
                $region = Region::create([
                    'main_code' => 'elementary', //초등학교, 중학교 구분 우선은 초등학교만 등록.
                    'region_name' => $schoolName,
                    'general_teach_seq' => $request->input('school_manager'),
                    'general_group_seq' => $teachGroupSeq,
                    'area' => $area,
                ]);

                Team::create([
                    'main_code' => "elementary",
                    'team_code' => $schoolCode,
                    'team_name' => $schoolName,
                    'team_type' => 'after_school',
                    'region_seq' => $region->id,
                    'team_kind' => 'after_school' // 필수 값 추가
                ]);

                TeamArea::create([
                    'team_code' => $schoolCode,
                    'tarea_sido' => $area,
                ]);

                Teacher::where('id', $request->input('school_manager'))->update(['region_seq' => $region->id]);

            });

            return response()->json(['resultCode' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['resultCode' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
