<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ParentIndexMtController extends Controller
{
    //
    public function list(){
        $student_seq = session()->get('student_seq');
        $student_last_url = '학습 중'; // 기본값 설정

        try {
            // 학생 세션 정보 조회
            $session = \App\Sessions::where('user_id', $student_seq)->orderBy('last_activity', 'desc')->first();

            if ($session) {
                $sessionFilePath = storage_path('framework/sessions/' . $session->session_id);

                if (file_exists($sessionFilePath)) {
                    $sessionData = $session->session_id;

                    if ($sessionData === false) {
                        return view('parent.parent_index', [
                            'student_last_url' => '오프라인'
                        ])->with('error', '세션 데이터 역직렬화 실패');
                    } else {
                        $sessionData = unserialize(file_get_contents($sessionFilePath));
                        $path = $sessionData['_previous']['url'];
                        $path = parse_url($path, PHP_URL_PATH);
                        $path = ltrim($path, '/');
                        // URL 매핑 정의
                        $title_map = [
                            'student/main' => '학생 메인',
                            'student/study/video' => '학습 영상 시청 중',
                            'student/study/quiz' => '문제 풀이 중',
                            'student/study/unitQuiz' => '단원 평가',
                            'student/wrong/note/again/exam' => '오답 풀이 중',
                            'student/wrong/note' => '오답 노트',
                            'student/school/study' => '학교공부',
                            'student/my/study' => '나의학습',
                            'student/unit/test' => '단원평가',
                            'student/my/score' => '나의점수',
                            'teacher/messenger' => '쪽지함',
                        ];
                        // 매핑된 타이틀이 있으면 사용
                        if (isset($path) && array_key_exists($path, $title_map)) {
                            $student_last_url = $title_map[$path];
                        }
                    }
                } else {
                    return view('parent.parent_index', [
                        'student_last_url' => '오프라인'
                    ])->with('error', '세션 파일을 찾을 수 없음');
                }
            }else{
                $student_last_url = '오프라인';
            }

            return view('parent.parent_index', [
                'student_last_url' => $student_last_url
            ]);

        } catch (\Exception $e) {
            return view('parent.parent_index', [
                'student_last_url' => '오프라인'
            ])->with('error', '학생 상태 조회 중 오류가 발생했습니다.');
        }
    }

    // public function getSelStudent(){
    //     $student_seq = session()->get('student_seq');
    //     $sel_student = \App\Student::find($student_seq);
    //     return $sel_student;
    // }

    // 오늘 학습 현황.
    public function studyLectureDateSelect(Request $request){
        $student_seq = session()->get('student_seq');
        $sel_date = $request->input('sel_date');
        // 만약 없으면, 오늘 날짜로 넣기.
        if(!$sel_date) $sel_date = date('Y-m-d');

        $student_lecture_details = \App\StudentLectureDetail::
            select(
                'student_lecture_details.*',
                'lectures.lecture_name',
                'course.code_name as course_name',
                'subject.code_name as subject_name',
                'subject.function_code'
            )
            ->leftJoin('student_lectures', 'student_lectures.id', '=', 'student_lecture_details.student_lecture_seq')
            ->leftJoin('lectures', 'lectures.id', '=', 'student_lectures.lecture_seq')
            ->leftJoin('lecture_codes as course_code', function($join){
                $join->on('course_code.lecture_seq', '=', 'lectures.id')
                    ->where('course_code.code_category', 'course');
            })
            ->leftJoin('codes as course', 'course.id', '=', 'course_code.code_seq')
            ->leftJoin('lecture_codes as subject_code', function($join){
                $join->on('subject_code.lecture_seq', '=', 'lectures.id')
                    ->where('subject_code.code_category', 'subject');
            })
            ->leftjoin('codes as subject', 'subject.id', '=', 'subject_code.code_seq')
            ->where('student_lecture_details.status', '!=', 'delete')
            ->where('sel_date', $sel_date)
            ->whereNull('student_lecture_details.lecture_type') // 학교공부제외.
            ->where('student_lecture_details.student_seq', $student_seq)
            ->get();


        $last_login_date = \App\Student::find($student_seq)->last_login_date;

        // 결과
        $result['resultCode'] = 'success';
        $result['student_lecture_details'] = $student_lecture_details;
        $result['last_login_date'] = $last_login_date;

        return response()->json($result);
    }

    // 월별 학습현황
    public function studyLectureMonthSelect(Request $request){
        $student_seq = session()->get('student_seq');
        $sel_month = $request->input('sel_month');
        // 만약 없으면, 오늘날짜의 달 넣기.
        if(!$sel_month) $sel_month = date('Y-m');

        // $sel_month 가 상반기인지 , 하반기인지 확인하기.
        $half_year = 1;
        if(date('m', strtotime($sel_month)) > 6) $half_year = 2;

        // 상반기 이면 1~6월 / 하반기 이면 7~12월
        $lecture_month_count = \App\StudentLectureDetail::where('student_seq', $student_seq)->whereNull('lecture_type'); // 학교공부제외.
        if($half_year == 1){
            $lecture_month_count->whereBetween('sel_date', [date('Y', strtotime($sel_month)).'-01-01', date('Y', strtotime($sel_month)).'-06-30']);
        }else{
            $lecture_month_count->whereBetween('sel_date', [date('Y', strtotime($sel_month)).'-07-01', date('Y', strtotime($sel_month)).'-12-31']);
        }

        $lecture_month_count = $lecture_month_count->
        select(
            DB::raw('left(sel_date, 7) as sel_month'),
            DB::raw('sum(1) as total_cnt'),
            DB::raw('sum(if(status = "complete", 1, 0)) as complete_cnt')
        )
            ->groupBy(DB::raw('left(sel_date, 7)'))->orderBy(DB::raw('left(sel_date, 7)'));
        // $result['sql'] = $lecture_month_count->toSql();
        // $result['bind'] = $lecture_month_count->getBindings();

        $lecture_month_count = $lecture_month_count ->get();

        //결과
        $result['resultCode'] = 'success';
        $result['lecture_month_count'] = $lecture_month_count;
        return response()->json($result);
    }

    // 성적표 점수 불러오기.
    function studyLectureDetailSelect(Request $request){
        $student_seq = session()->get('student_seq');
        $student_lecture_detail_seq = $request->input('student_lecture_detail_seq');

        $student_exams = \App\StudentExam::select(
            'student_exams.exam_status as student_exam_status',
            'exams.evaluation_seq',
            'codes.code_name',
            'student_exam_results.*'
        )
            ->leftJoin('exams', 'exams.id', '=', 'student_exams.exam_seq')
            ->leftJoin('codes', 'codes.id', '=', 'exams.evaluation_seq')
            ->leftJoin('student_exam_results', function($join) use($student_seq){
                $join->on('student_exam_results.exam_seq', '=', 'exams.id')
                    ->where('student_exam_results.student_seq', $student_seq);
            })
            ->where('student_exams.student_seq', $student_seq)
            ->where('student_exams.student_lecture_detail_seq', $student_lecture_detail_seq)
            ->where('student_exam_results.exam_type', '<>', 'easy')
            ->get()->groupBy('evaluation_seq');

        $result['resultCode'] = 'success';
        $result['student_exams'] = $student_exams;
        return response()->json($result);

    }
}
