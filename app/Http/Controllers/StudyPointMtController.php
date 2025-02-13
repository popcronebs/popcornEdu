<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudyPointMtController extends Controller
{
    //
    public function list(){
        // 학생의 정보를 가져오기.
        $student_seq = session()->get('student_seq');
        $student = \App\Student::find($student_seq);

        // 일단 범위를 (개인랭킹) 자신의 학교/학원 안으로 진행
        $team_code = $student->team_code;

        // 순위 계산을 위한 초기화
        DB::statement(DB::raw('SET @rank := 0, @prev_point := NULL, @count := 1'));

        // 순위 히스토리는 point_rank_histories 에서 가저오고, 일주일 단위로 갱신.
        // TODO: 전체를 가져오는 것이라서 추후 쿼리 수정이 필요해보임.
        // (특정 시간에 등수를 저장하는 방식이라던지.)

        // 순위 계산 쿼리
        $rank = DB::table(DB::raw('(SELECT point_now, students.id, student_name, prev_rank  FROM students left join point_rank_histories on point_rank_histories.student_seq = students.id  WHERE team_code = ? ORDER BY point_now DESC) as point_histories'))
            ->select(DB::raw('@rank := IF(@prev_point = point_now, @rank, @rank + @count) as rank, @prev_point := point_now as point, @count := 1 as count, id, student_name, prev_rank'))
            ->addBinding($team_code, 'select')
            ->get();
        $my_rank = $rank->where('id', $student_seq)->first()->rank;
        $prev_rank = $rank->where('id', $student_seq)->first()->prev_rank;

        // 학습 포인트 순위 1위부터 10등까지 rank limit 10
        $top_ten = $rank->take(10);

        // 학생의 등급.
        // p < 3000  = 0 등급
        // p < 6000 = 1 등급
        // p < 9000 = 2 등급
        // p < 12000 = 3 등급
        // p < 15000 = 4 등급
        // p >= 15000 = 5 등급

        $point_now = $student->point_now;
        $point_grade = $this->getRank($point_now);
        $student_lecture_details = \App\StudentLectureDetail::where('student_seq', $student_seq)->get();
        // 수강 시간. // 초로 저장되므로 분, 초로 변경해준다.
        $total_study_time = $student_lecture_details->sum('last_video_time');
        $total_study_time = floor($total_study_time / 60) . '분 ' . $total_study_time % 60 . '초';

        // 수강 완료 횟수.
        $total_study_count = $student_lecture_details->where('status', 'complete')->count();
        // WARN: 학습방 담기? 뭔지 확인 필요

        // 로그인횟수->students 테이블에 있음.

        // TODO: 학생이 게시글과, 댓그릉ㄹ 달수 있는 화면 확인후 진행할것.
        // 게시글 작성
        // 댓글작성

        // 이번달 학습 포인트
        // 이번달 수강시간
        $month_study_time = $student_lecture_details->where('sel_date', '>=', date('Y-m-01 00:00:00'))->sum('last_video_time');
        $month_study_time = floor($month_study_time / 60) . '분 ' . $month_study_time % 60 . '초';
        // 이번달 수강완료
        $month_study_count = $student_lecture_details->where('sel_date', '>=', date('Y-m-01 00:00:00'))->where('status', 'complete')->count();
        // WARN: 이번달 학습방 담기
        // 이번달 로그인
        $month_login_cnt = \App\PointHistory::where('student_seq', $student_seq)
            ->where('created_at', '>=', date('Y-m-01 00:00:00'))
            ->where('point_type', 'login')
            ->count();
        // 게시글, 댓글



        return view('student.student_study_point', [
            'student' => $student,
            'my_rank' => $my_rank,
            'prev_rank' => $prev_rank,
            'point_grade' => $point_grade,
            'total_study_time' => $total_study_time,
            'total_study_count' => $total_study_count,
            'month_login_cnt' => $month_login_cnt,
            'month_study_time' => $month_study_time,
            'month_study_count' => $month_study_count,
            'top_ten' => $top_ten,
            'mt_this' => $this,
        ]);
    }

    // 학습포인트 히스토리 가져오기.
    public function historySelect(Request $request){
        $student_seq = session()->get('student_seq');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $point_histories = \App\PointHistory::where('student_seq', $student_seq)
            ->where('created_at', '>=', $start_date)
            ->where('created_at', '<=', $end_date)
            ->get();

        // 결과
        $result['resultCode'] = 'success';
        $result['point_histories'] = $point_histories;
        return response()->json($result);
    }

    // 랭크 가져오기.
    public function getRank($point_now){
        $point_grade = 0;
        if($point_now < 3000) $point_grade = 1;
        else if($point_now < 6000) $point_grade = 2;
        else if($point_now < 9000) $point_grade = 3;
        else if($point_now < 12000) $point_grade = 4;
        else if($point_now < 15000) $point_grade = 5;
        else $point_grade = 6;

        return $point_grade;
    }
}
