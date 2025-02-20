<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MyStudyMtController extends Controller
{
    //
    public function list(Request $request)
    {
        $login_type = session()->get('login_type');
        $student_seq = session()->get('student_seq');
        $type = $request->input('type');
        if ($request->input('student_seq') != null) {
            $student_seq = $request->input('student_seq');
        }
        $main_code = \App\Student::where('id', $student_seq)->value('main_code');
        // 과목
        $subject_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'subject')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        // 월~일 배열
        $week = array("일", "월", "화", "수", "목", "금", "토");
        // 08:00 ~ 23:00 배열
        $time_array = array("08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00");
        // 1~6월
        $month_array = array("1", "2", "3", "4", "5", "6");

        // 학생 정보
        $student = \App\Student::find($student_seq);

        // 수강 강좌.
        $student_lectures = \App\StudentLecture::where('student_seq', $student_seq)->whereNull('lecture_type')->get();

        // 관심 강의 갯수.
        $lecture_of_interest_cnt = \App\StudentLectureDetail::where('student_seq', $student_seq)
            ->whereNull('lecture_type') // 학교공부제외.
            ->where('is_like', 'Y')
            ->count();
        // 신청 강의중 status 가 complete 가 아니면서 오늘 이전인 강의갯수
        $not_complete_cnt = \App\StudentLectureDetail::where('student_seq', $student_seq)
            ->whereNull('lecture_type') // 학교공부제외.
            -> where('status', '!=', 'complete')
            -> where('sel_date', '<', date('Y-m-d'))
            ->whereIn('student_lecture_seq', function ($query) {
                $query->select('id')
                    ->from('student_lectures')
                    ->where('start_date', '<=', date('Y-m-d 23:59:59'))
                    ->where('end_date', '>=', date('Y-m-d'));
            })->count();
        // 재수강 갯수
        $re_do_cnt = \App\StudentLectureDetail::where('student_seq', $student_seq)
            ->whereNull('lecture_type') // 학교공부제외.
            ->where('copy_pt_seq', '!=', null)
            ->count();
        return view(
            'student.student_my_study',
            [
                'week' => $week,
                'subject_codes' => $subject_codes,
                'time_array' => $time_array,
                'month_array' => $month_array,
                'student' => $student,
                'login_type' => $login_type,
                'student_lectures' => $student_lectures,
                'lecture_of_interest_cnt' => $lecture_of_interest_cnt,
                'not_complete_cnt' => $not_complete_cnt,
                're_do_cnt' => $re_do_cnt,
                'type' => $type
            ]
        );
    }

    // 요일별 학습 시간
    public function weeklyStudyTimeSelect(Request $request)
    {
        $student_seq = session()->get('student_seq');
        if(session()->get('login_type') == 'teacher') {
            $student_seq = $request->input('student_seq');
        }
        $is_not_group_by = $request->input('is_not_group_by');
        // 값을 가져오고, 만약 없다면 이번주 시작의 일요일부터 시작일로 진행.
        // $sunday_date = $request->input('sunday_date');
        $year = $request->input('year');
        $month = $request->input('month');
        $week_cnt = $request->input('week_cnt');

        $dates = $this->getWeekDates($year, $month, $week_cnt);

        $sunday_date = $dates['sunday'];
        $saturday_date = $dates['saturday'];

        // if(!$sunday_date)
        // $sunday_date = date('Y-m-d', strtotime('last sunday'));

        $student_lecture_details = \App\StudentLectureDetail::where('student_seq', $student_seq)
            ->whereNull('lecture_type') // 학교공부제외.
            ->where('sel_date', '>=', $sunday_date)
            ->where('sel_date', '<=', $saturday_date)
            ->get();
        if($is_not_group_by != 'Y') {
            $student_lecture_details =
            $student_lecture_details ->groupBy('sel_day');
        }

        // 결과
        $result['resultCode'] = 'success';
        $result['student_lecture_details'] = $student_lecture_details;
        // date 반환. 확인.
        // $result['sunday_date'] = $sunday_date;
        // $result['saturday_date'] = $saturday_date;
        return response()->json($result, 200);
    }

    // 과목별 학습시간.
    public function weeklySubjectTimeSelect(Request $request)
    {
        $student_seq = session()->get('student_seq');
        if(session()->get('login_type') == 'teacher') {
            $student_seq = $request->input('student_seq');
        }

        // 값을 가져오고, 만약 없다면 이번주 시작의 일요일부터 시작일로 진행.
        // $sunday_date = $request->input('sunday_date');
        $year = $request->input('year');
        $month = $request->input('month');
        $week_cnt = $request->input('week_cnt');

        $dates = $this->getWeekDates($year, $month, $week_cnt);

        $sunday_date = $dates['sunday'];
        $saturday_date = $dates['saturday'];

        // 기준은 studnet_lecture_detail 이며, 여기에 과목을 더해서 가져온다.
        // 단 쿼리는 lecture_codes 에서 subject가 단일이라고 가정한다.

        $student_lecture_details = \App\StudentLectureDetail::select(
            'student_lecture_details.*',
            'lecture_codes.code_seq as subject_seq'
        )
            ->leftJoin('lecture_details', 'student_lecture_details.lecture_detail_seq', '=', 'lecture_details.id')
            ->leftJoin('lecture_codes', function ($join) {
                $join->on('lecture_details.lecture_seq', '=', 'lecture_codes.lecture_seq')
                    ->where('lecture_codes.code_category', '=', 'subject');
            })
            ->whereNull('student_lecture_details.lecture_type') // 학교공부제외.
            ->where('student_seq', $student_seq)
            ->where('sel_date', '>=', $sunday_date)
            ->where('sel_date', '<=', $saturday_date)
            ->get()
            ->groupBy('subject_seq');

        // 결과
        $result['resultCode'] = 'success';
        $result['student_lecture_details'] = $student_lecture_details;
        // date 반환. 확인.
        // $result['sunday_date'] = $sunday_date;
        // $result['saturday_date'] = $saturday_date;
        return response()->json($result, 200);
    }

    // 주간 학습 상세
    public function weeklyLearningDetailSelect(Request $request)
    {
        $student_seq = session()->get('student_seq');

        // 값을 가져오고, 만약 없다면 이번주 시작의 일요일부터 시작일로 진행.
        // $sunday_date = $request->input('sunday_date');
        $year = $request->input('year');
        $month = $request->input('month');
        $week_cnt = $request->input('week_cnt');

        $dates = $this->getWeekDates($year, $month, $week_cnt);

        $sunday_date = $dates['sunday'];
        $saturday_date = $dates['saturday'];

        // if(!$sunday_date)
        // $sunday_date = date('Y-m-d', strtotime('last sunday'));

        $student_lecture_details = \App\StudentLectureDetail::leftJoin('lecture_details', 'student_lecture_details.lecture_detail_seq', '=', 'lecture_details.id')
            ->select(
                'student_lecture_details.*',
                'lecture_details.lecture_detail_name',
                'lecture_details.lecture_detail_description',
                'lecture_details.lecture_detail_time'
            )
            ->whereNull('student_lecture_details.lecture_type') // 학교공부제외.
            ->where('student_seq', $student_seq)
            ->where('sel_date', '>=', $sunday_date)
            ->where('sel_date', '<=', $saturday_date)
            ->get()
            ->groupBy('sel_day');

        // 결과
        $result['resultCode'] = 'success';
        $result['student_lecture_details'] = $student_lecture_details;
        // date 반환. 확인.
        // $result['sunday_date'] = $sunday_date;
        // $result['saturday_date'] = $saturday_date;
        return response()->json($result, 200);

    }
    // 주간 출결 현황.
    public function weeklyAttendanceStatusSelct(Request $request)
    {
        $student_seq = session()->get('student_seq');
        if(session()->get('login_type') == 'teacher') {
            $student_seq = $request->input('student_seq');
        }

        // 값을 가져오고, 만약 없다면 이번주 시작의 일요일부터 시작일로 진행.
        // $sunday_date = date('Y-m-d', strtotime('last sunday'));
        $year = $request->input('year');
        $month = $request->input('month');
        $week_cnt = $request->input('week_cnt');

        $dates = $this->getWeekDates($year, $month, $week_cnt);

        $sunday_date = $dates['sunday'];
        $saturday_date = $dates['saturday'];

        // 클래스는 제외하고 목표시간만 가져오도록 추후 요청하면 추가진행.


        $study_times = \App\StudyTime::select(
            'study_times.*',
            DB::raw("CASE DAYOFWEEK(select_date)
                WHEN 1 THEN '일'
                WHEN 2 THEN '월'
                WHEN 3 THEN '화'
                WHEN 4 THEN '수'
                WHEN 5 THEN '목'
                WHEN 6 THEN '금'
                WHEN 7 THEN '토'
                END as day_of_week")
        )
            ->where('student_seq', $student_seq)
            ->where('select_date', '>=', $sunday_date)
            ->where('select_date', '<=', $saturday_date)
            ->orderBy('select_date', 'asc')
            ->get();

        $attends = \App\Attend::where('student_seq', $student_seq)
            ->where('attend_date', '>=', $sunday_date)
            ->get()
            ->groupBy('attend_date');

        // 결과
        $result['resultCode'] = 'success';
        $result['study_times'] = $study_times;
        $result['attends'] = $attends;
        // date 반환.
        $result['sunday_date'] = $sunday_date;
        $result['saturday_date'] = $saturday_date;

        return response()->json($result, 200);
    }

    // 수강중 강좌, 수강완료 강좌, 관심 강좌, 미수강
    public function lectureSelect(Request $request)
    {
        $type = $request->input('type');
        $student_seq = session()->get('student_seq');
        $search_str = $request->input('search_str');

        // 페이징
        $page = $request->input('page');
        $page = $page ?? 1;
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 5;

        $student_lecture_details = \App\StudentLectureDetail::
        where('student_lecture_details.student_seq', $student_seq)
        ->whereNull('student_lecture_details.lecture_type') // 학교공부제외.
        ->where('student_lecture_details.status', '!=', 'delete');
        $student_lectures = \App\StudentLecture::where('student_seq', $student_seq);

        // NOTE: TEYP 으로 묶어 놨으나, function 분리로 깔금하게 정리하는 것도 추천.
        //
        // 수강중인 강좌, 완료 강좌
        if($type == 'doing' || $type == 'complete') {
            $student_lectures = $student_lectures
                ->leftJoin('lecture_codes', function ($join) {
                    $join->on('student_lectures.lecture_seq', '=', 'lecture_codes.lecture_seq')
                        ->where('lecture_codes.code_category', '=', 'subject');
                })
                ->leftJoin('codes as subject_codes', 'subject_codes.id', '=', 'lecture_codes.code_seq')
                ->leftJoin('lectures', 'lectures.id', '=', 'student_lectures.lecture_seq');

            if($type == 'doing') {
                $student_lectures = $student_lectures
                    ->where('start_date', '<=', date('Y-m-d 23:59:59'))
                    ->where('end_date', '>=', date('Y-m-d'));
            } elseif($type == 'complete') {
                $student_lectures = $student_lectures
                    ->where('end_date', '<', date('Y-m-d'));
            }

            $student_lecture_details = $student_lecture_details
                ->whereIn('student_lecture_seq', $student_lectures->select('student_lectures.id'));

            $student_lectures = $student_lectures->select(
                'student_lectures.*',
                'subject_codes.id as sbuject_seq',
                'subject_codes.function_code',
                'lectures.teacher_name',
                'lectures.teach_seq',
                'lectures.lecture_name'
            );

            // $search_str 가 있으면 검색.
            if($search_str){
                $student_lectures = $student_lectures
                ->where(function ($q) use ($search_str) {
                    $q->where('lectures.lecture_name', 'like', '%'.$search_str.'%')
                        ->orWhere('lectures.teacher_name', 'like', '%'.$search_str.'%')
                        ->orWhere("subject_codes.code_name", "like", "%".$search_str."%");
                });
            }

            $student_lectures = $student_lectures
            ->paginate($page_max, ['*'], 'page', $page);

            $student_lecture_details = $student_lecture_details->get()->groupBy('student_lecture_seq');

            $result['student_lectures'] = $student_lectures;
            $result['student_lecture_details'] = $student_lecture_details;
        }
        // 관심 강의/강좌
        elseif($type == 'islike') {
            // 찜/관심 강의 를 가져와서 lecture_seq 까지 가져온다.
            $student_lecture_details =
            $student_lecture_details
                ->where('is_like', 'Y')
                // ->groupBy('lecture_detail_seq')
                ->select(
                'student_lecture_details.id',
                'lectures.lecture_name',
                'lectures.lecture_description',
                'lecture_details.lecture_detail_name',
                'lecture_details.lecture_detail_description',
                'subject_codes.code_name as subject_name',
                'lecture_uploadfiles.file_path',
                'lectures.teacher_name'
            )
                ->leftJoin('lecture_details', 'lecture_details.id', '=', 'student_lecture_details.lecture_detail_seq')
                ->leftJoin('lectures', 'lectures.id', '=', 'lecture_details.lecture_seq')
                ->leftJoin('lecture_codes', function ($join) {
                    $join->on('lectures.id', '=', 'lecture_codes.lecture_seq')
                        ->where('lecture_codes.code_category', '=', 'subject');
                })
                ->leftJoin('codes as subject_codes', 'subject_codes.id', '=', 'lecture_codes.code_seq')
                ->leftJoin('lecture_uploadfiles', 'lecture_uploadfiles.lecture_seq', '=', 'lectures.id');

            if($search_str){
                $student_lecture_details = $student_lecture_details
                    ->where(function($q) use ($search_str){
                        $q->where('lecture_name', 'like', '%'.$search_str.'%')
                            ->orWhere('teacher_name', 'like', '%'.$search_str.'%')
                            ->orWhere("subject_codes.code_name", "like", "%".$search_str."%");
                    });
            }
                $student_lecture_details = $student_lecture_details
                ->paginate($page_max, ['*'], 'page', $page);

            $result['lectures'] = $student_lecture_details;
        }
        // 최근 많이 본강좌.
        elseif($type == 'often') {
            // 최근 많이 본 강좌의 기준 = 정해 주지 않으므로 다음과 같이 정리.
            // 1. 일주일 내로 본 강좌.
            // 2. 일주일내에 가장 많이 본 강좌 order by
            // 3. view_count 가 0보다는 큰 강좌

            $student_lectures = $student_lectures
                ->select(
                    'student_lectures.id',
                    'student_lectures.lecture_seq',
                    'student_lectures.view_count',
                    'student_lectures.updated_at',
                    'lectures.lecture_name',
                    'lectures.teacher_name',
                    'lectures.teach_seq',
                    'lecture_uploadfiles.file_path'
                )
                ->leftJoin('lectures', 'student_lectures.lecture_seq', '=', 'lectures.id')
                ->leftJoin('lecture_uploadfiles', 'lecture_uploadfiles.lecture_seq', '=', 'lectures.id')
                ->where('student_lectures.updated_at', '>=', date('Y-m-d', strtotime('-7 days')))
                ->where('view_count', '>', 0)
                ->orderBy('student_lectures.view_count', 'desc');

            // $result['sql'] = $student_lectures->toSql();
            // $result['bind'] = $student_lectures->getBindings();
            $student_lecture_seqs = $student_lectures->get()->pluck('id')->toArray();

            $student_lectures = $student_lectures
                ->paginate($page_max, ['*'], 'page', $page);

            $student_lecture_details = $student_lecture_details
                ->whereIn('student_lecture_seq', $student_lecture_seqs)
                ->get()
                ->groupBy('student_lecture_seq');


            $result['student_lectures'] = $student_lectures;
            $result['student_lecture_details'] = $student_lecture_details;
        }
        // 미수강
        elseif($type == 'not_complete' || $type == 're_do') {
            // TODO: 년 월 주차 로 변수를 받아서 sel_date 기간 설정 필요.

            $student_lecture_details = $student_lecture_details
                ->select(
                    "student_lecture_details.id",
                    "student_lecture_details.status",
                    "student_lecture_details.sel_date",
                    "student_lecture_details.updated_at",
                    "student_lecture_details.sel_day",
                    "lectures.lecture_name",
                    "lecture_details.lecture_detail_name",
                    'lectures.lecture_description',
                    "subject_codes.code_name as subject_name",
                    "lecture_codes.code_seq as subject_seq",
                    "subject_codes.function_code",
                    "student_lecture_details.last_video_time",
                    "lecture_details.lecture_detail_time"
                )
                ->leftJoin('student_lectures', 'student_lectures.id', '=', 'student_lecture_details.student_lecture_seq')
                ->leftJoin('lectures', 'lectures.id', '=', 'student_lectures.lecture_seq')
                ->leftJoin('lecture_details', 'lecture_details.id', '=', 'student_lecture_details.lecture_detail_seq')
                ->leftJoin('lecture_codes', function ($join) {
                    $join->on('lectures.id', '=', 'lecture_codes.lecture_seq')
                        ->where('lecture_codes.code_category', '=', 'subject');
                })
                ->leftJoin('codes as subject_codes', 'subject_codes.id', '=', 'lecture_codes.code_seq');

            if($type == 'not_complete') {
                $student_lecture_details = $student_lecture_details
                    ->where('student_lecture_details.status', '!=', 'complete')
                    ->where('student_lectures.start_date', '<=', date('Y-m-d 23:59:59'))
                    ->where('student_lectures.end_date', '>=', date('Y-m-d'))
                    ->where('student_lecture_details.sel_date', '<', date('Y-m-d'));
            } elseif($type == 're_do') {
                $student_lecture_details = $student_lecture_details
                    ->where('student_lecture_details.sel_date', '<', date('Y-m-d'))
                    ->where('student_lecture_details.copy_pt_seq', '!=', null);
            }

            // 날짜 검색.
            if($search_str){
                // | 구분자로 나눈다.
                $start_date = explode("|", $search_str)[0];
                $end_date = explode("|", $search_str)[1];
                $student_lecture_details = $student_lecture_details
                    ->where('student_lecture_details.sel_date', '>=', $start_date)
                    ->where('student_lecture_details.sel_date', '<=', $end_date);
            }

            $result['bind'] = $student_lecture_details->getBindings();
            $result['sql'] = $student_lecture_details->toSql();

            $student_lecture_details = $student_lecture_details
                ->paginate($page_max, ['*'], 'page', $page);

            $result['student_lecture_details'] = $student_lecture_details;

        }

        // 결과
        $result['resultCode'] = 'success';
        return response()->json($result, 200);

    }

    // 찜한 강좌에서 하트 cnlth.
    public function lectureLikeCancel(Request $request)
    {
        // 현재 정확히 관심강좌의 범위를 알수 없으므로, student_lecutres, student_lecutre_details 의 is_like를 없앤다.
        $student_seq = session()->get('student_seq');
        $student_lecture_detail_seq = $request->input('student_lecture_detail_seq');

        //update is_like
        // \App\StudentLecture::where('lecture_seq', $lecture_seq)
        //     ->where('student_seq', $student_seq)
        //     ->where('is_like', 'Y')
        //     ->update(['is_like' => 'N']);


        \App\StudentLectureDetail::where('id', $student_lecture_detail_seq)
            ->whereNull('lecture_type') // 학교공부제외.
            ->where('student_seq', $student_seq)
            ->where('is_like', 'Y')
            ->update(['is_like' => 'N']);

        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }

    // 학습플래너 추가.
    public function lecturePlanInsert(Request $request)
    {
        // TODO: 여기서 부터 진행
        $student_seq = session()->get('student_seq');
        $sel_date = $request->input('sel_date');
        $student_lecture_detail_seqs = $request->input('student_lecture_detail_seqs');


        $day_of_week = DB::select(DB::raw("
            SELECT CASE DAYOFWEEK(:sel_date)
                WHEN 1 THEN '일'
                WHEN 2 THEN '월'
                WHEN 3 THEN '화'
                WHEN 4 THEN '수'
                WHEN 5 THEN '목'
                WHEN 6 THEN '금'
                WHEN 7 THEN '토'
                END AS day_of_week
        "), ['sel_date' => $sel_date]);

        $day_of_week = $day_of_week[0]->day_of_week;

        $is_commit = false;
        DB::beginTransaction();
        try {
            foreach($student_lecture_detail_seqs as $details_seq) {
                if($details_seq == "") {
                    continue;
                }
                $find_sld = \App\StudentLectureDetail::find($details_seq);
                $new_sld = new \App\StudentLectureDetail();
                $new_sld->student_seq = $student_seq;
                $new_sld->student_lecture_seq = $find_sld->student_lecture_seq;
                $new_sld->lecture_detail_seq = $find_sld->lecture_detail_seq;
                $new_sld->sel_date = $sel_date;
                $new_sld->sel_day = $day_of_week;
                $new_sld->status = 'ready';
                // pt_seq가 있으면, 넣어주고, 없으면 details_seq를 넣어준다.
                if(strlen($find_sld->copy_pt_seq) > 0) {
                    $new_sld->copy_pt_seq = $find_sld->copy_pt_seq;
                } else {
                    $new_sld->copy_pt_seq = $details_seq;
                }
                $new_sld->save();
            }
            DB::commit();
            $is_commit = true;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        // 결과
        $result['resultCode'] = $is_commit ? 'success' : 'fail';
        return response()->json($result, 200);
    }

    // 강좌 상세보기 화면 정보 불러오기.
    public function lectrureDetailPageInfoSelect(Request $request)
    {
        $student_lecture_seq = $request->input('student_lecture_seq');

        // 학생 등록 강좌 정보.
        $student_lectures = \App\StudentLecture::find($student_lecture_seq);
        // 학생 등록 강좌 상세 정보. 모두 불러오기.
        $student_lecture_details = \App\StudentLectureDetail::
            where('student_lecture_seq', $student_lecture_seq)
            ->whereNull('student_lecture_details.lecture_type') // 학교공부제외.
            ->where('status', '!=', 'delete')
            ->leftJoin('lecture_details', 'student_lecture_details.lecture_detail_seq', '=', 'lecture_details.id')
            ->select(
                'student_lecture_details.*',
                'lecture_details.lecture_detail_name',
                'lecture_details.lecture_detail_description',
                'lecture_details.lecture_detail_time'
            )
            ->get();
        // 강좌 정보
        $lecture_seq = $student_lectures->lecture_seq;
        $lectures = \App\Lecture::where('lectures.id', $lecture_seq)
            ->leftJoin('lecture_uploadfiles', function ($join) {
                $join->on('lecture_uploadfiles.lecture_seq', '=', 'lectures.id')
                    ->where('lecture_uploadfiles.file_type', '=', 'thumbnail');
            })
            ->leftJoin('lecture_codes as subject_code', function ($join) {
                $join->on('lectures.id', '=', 'subject_code.lecture_seq')
                ->where('subject_code.code_category', '=', 'subject');
            })
            ->leftJoin('codes as subject', 'subject.id', '=', 'subject_code.code_seq')
            ->leftJoin('lecture_codes as grade_code', function ($join) {
                $join->on('lectures.id', '=', 'grade_code.lecture_seq')
                    ->where('grade_code.code_category', '=', 'grade');
            })
            ->leftJoin('codes as grade', 'grade.id', '=', 'grade_code.code_seq')
            ->leftJoin('lecture_codes as level_code', function ($join) {
                $join->on('lectures.id', '=', 'level_code.lecture_seq')
                    ->where('level_code.code_category', '=', 'level');
            })
            ->leftJoin('codes as level', 'level.id', '=', 'level_code.code_seq')
            ->leftJoin('lecture_codes as series_code', function ($join) {
                $join->on('lectures.id', '=', 'series_code.lecture_seq')
                    ->where('series_code.code_category', '=', 'series');
            })
            ->leftJoin('codes as series', 'series.id', '=', 'series_code.code_seq')
            ->select(
                'lectures.*',
                'subject.code_name as subject_name',
                'grade.code_name as grade_name',
                'level.code_name as level_name',
                'series.code_name as series_name',
                'lecture_uploadfiles.file_path'
            )->get();
        // 강좌 상세 정보.
        // $lecture_details = \App\LectureDetail::where('lecture_seq', $lecture_seq)->get();
        // ====
        // 강좌 학습 결과 / 총 문제.
        $all_exams = \App\StudentLecture::where('student_seq', session()->get('student_seq'))
            ->where('student_lectures.id', $student_lecture_seq)
            ->whereIn('exam_details.exam_type', ['normal', 'similar', 'challenge', 'challenge_similar'])
            ->leftJoin('lectures', 'student_lectures.lecture_seq', '=', 'lectures.id')
            ->leftJoin('lecture_details', function ($join) {
                $join->on('lectures.id', '=', 'lecture_details.lecture_seq')
                    ->where('lecture_details.lecture_detail_type', '=', 'exam_solving');
            })
            ->leftJoin('exam_details', 'lecture_details.lecture_exam_seq', '=', 'exam_details.exam_seq')
            ->select(
                'student_lectures.lecture_seq',
                'lecture_details.lecture_exam_seq',
                'exam_details.exam_type',
                'lecture_details.lecture_detail_group as lecture_detail_seq'
            )
            ->get()->groupBy('lecture_detail_seq');
        $student_lecture_detail_seqs = $student_lecture_details->pluck('id')->toArray();
        $student_exam_results = \App\StudentExam::whereIn('student_exams.student_lecture_detail_seq', $student_lecture_detail_seqs)
            ->where('student_exam_results.exam_type', '!=', 'easy')
            ->where('lecture_detail_type', 'exam_solving')
            ->leftJoin('student_exam_results', 'student_exams.id', '=', 'student_exam_results.student_exam_seq')
            ->select(
                'student_exams.id',
                'student_exams.student_seq',
                'student_exams.exam_seq',
                'student_exams.student_lecture_detail_seq',
                'student_exam_results.exam_type',
                'student_exam_results.exam_status',
                'student_exam_results.exam_num'
            )
            ->get()->groupBy('student_lecture_detail_seq');



        // 결과.
        $result['resultCode'] = 'success';
        $result['student_lectures'] = $student_lectures;
        $result['student_lecture_details'] = $student_lecture_details;
        $result['lectures'] = $lectures;
        $result['all_exams'] = $all_exams;
        $result['student_exam_results'] = $student_exam_results;
        // $result['lecture_details'] = $lecture_details;
        return response()->json($result, 200);
    }

    //
    public function getWeekDates($year, $month, $week_cnt)
    {
        // 해당 월의 1일 생성
        $firstDayOfMonth = Carbon::create($year, $month, 1);

        // 해당 월의 첫 번째 토요일 찾기
        $firstSaturday = $firstDayOfMonth->copy()->modify('first saturday of this month');

        // 첫 주의 일요일 찾기 (토요일로부터 6일전)
        $firstSunday = $firstSaturday->copy()->subDays(6);

        // 주차에 따른 일요일 계산
        $targetSunday = $firstSunday->copy()->addWeeks($week_cnt - 1);

        // 해당 주의 토요일 계산
        $targetSaturday = $targetSunday->copy()->addDays(6);

        return [
            'sunday' => $targetSunday->format('Y-m-d'),
            'saturday' => $targetSaturday->format('Y-m-d')
        ];
    }
}
