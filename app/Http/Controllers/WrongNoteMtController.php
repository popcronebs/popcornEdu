<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class WrongNoteMtController extends Controller
{
    public function list(Request $request){
        $student_seq = $request->session()->get('student_seq');
        if($request->input('student_seq') != null){
            $student_seq = $request->input('student_seq');
        }
        $main_code = \App\Student::where('id', $student_seq)->value('main_code');
        $subject_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'subject')->where('code_step', '=', 1)
        ->orderBy('code_idx', 'asc')
        ->get();

        // 완료 3일 이내 강의와, 오답노트 카운트 가져오기.
        $data['student_seq'] = $student_seq;
        // 현재 날짜와, 요일을 가져와서 이번주의 시작 일요일 날짜와, 종료 토요일의 날짜를 구한다.
        $dates = $this->getSumStartDate();
        $data['start_date'] = $dates[0];
        $data['end_date'] = $dates[1];
        $wrongs_array = $this->getWrongCnt($data);


        return view('student.student_wrong_note', [
            'subject_codes' => $subject_codes,
            'wrong_cnts' => $wrongs_array['wrong_cnts'],
            'complete_exams' => $wrongs_array['complete_exams'],
            'wrong_sld_seqs' => $wrongs_array['wrong_sld_seqs'],
        ]);
    }

    // 오답노트 다시풀기화면
    public function againExam(Request $request){
        $student_seq = session()->get('student_seq');
        $student_exam_seq = $request->input('student_exam_seq');
        $lecture_detail_type = $request->input('lecture_detail_type');

        $student_exam = \App\StudentExam::
            select(
                'student_exams.*',
                'exams.exam_title',
                'grade_code.code_name as grade_name',
                'semester_code.code_name as semester_name'
            )
            ->leftJoin('exams', 'student_exams.exam_seq', '=', 'exams.id')
            ->leftJoin('codes as grade_code', 'exams.grade_seq', '=', 'grade_code.id')
            ->leftJoin('codes as semester_code','exams.semester_seq', '=', 'semester_code.id')
            ->where('student_exams.id', $student_exam_seq)
            -> first();
        // $lecture_details = \App\LectureDetail::where('id', $lecture_detail_seq)->first();
        // $lecture_seq = $lecture_details->lecture_seq;
        // $lectures = \App\Lecture::where('id', $lecture_seq)->first();

        // 오답 가져오기.
        $student_exam_results = \App\StudentExamResult::select('*')
            ->where('student_seq', $student_seq)
            ->where('student_exam_seq', $student_exam_seq)
            ->orderBy('exam_num')
            ->get();

        $normal_wrongs = $student_exam_results->filter(function($item){
            return ($item->exam_type == 'normal' || $item->exam_status == 'wrong' && $item->exam_type === 'ready') &&
                    ($item->exam_status === 'wrong' || $item->student_answer === null);
        })->values();

        $similar_wrongs = $student_exam_results->where('exam_type', 'similar')->where('exam_status', 'wrong')->values();
        $challenge_wrongs = $student_exam_results->where('exam_type', 'challenge')->where('exam_status', 'wrong')->values();
        $challenge_similar_wrongs = $student_exam_results->where('exam_type', 'challenge_similar')->where('exam_status', 'wrong')->values();
        $all_cnt = $student_exam_results->where('exam_type', 'normal')->count();
        $correct_cnt = $student_exam_results->where('exam_type', 'normal')->where('exam_status', 'correct')->values()->count();
        // 백분률
        $correct_rate = round(($correct_cnt / $all_cnt) * 100);

        $exam_seq = $student_exam_results[0]->exam_seq;
        $normals = \App\ExamDetail::where('exam_seq', $exam_seq)
            ->where('exam_type', 'normal')
            ->orderBy('exam_num')
            ->get();
        $similars = \App\ExamDetail::where('exam_seq', $exam_seq)
            ->where('exam_type', 'similar')
            ->orderBy('exam_num')
            ->get();
        $challenges = \App\ExamDetail::where('exam_seq', $exam_seq)
            ->where('exam_type', 'challenge')
            ->orderBy('exam_num')
            ->get();
        $challenge_similars = \App\ExamDetail::where('exam_seq', $exam_seq)
            ->where('exam_type', 'challenge_similar')
            ->orderBy('exam_num')
            ->get();

        // 이미지 및 동영상 파일 경로.
        $exam_uploadfiles = \App\ExamUploadfile::where('exam_seq', $exam_seq)
            ->get();



       // 학생이 답한 답 가져오기.

        $student_answers = \App\StudentExamResult::select('*')
            ->where('student_seq', $student_seq)
            ->where('exam_seq', $exam_seq)
            ->where('student_exam_seq', $student_exam_seq)
            ->orderBy('exam_type')
            ->orderBy('exam_num')
            ->get();


        return view('student.student_exam_again', [
            'lecture_detail_type' => $lecture_detail_type,
            'normals' => $normals,
            'similars' => $similars,
            'challenges' => $challenges,
            'challenge_similars' => $challenge_similars,

            'normal_wrongs' => $normal_wrongs,
            'similar_wrongs' => $similar_wrongs,
            'challenge_wrongs' => $challenge_wrongs,
            'challenge_similar_wrongs' => $challenge_similar_wrongs,

            'correct_rate' => $correct_rate,
            'exam_uploadfiles' => $exam_uploadfiles,

            'st_answers' => $student_answers,
            'student_exam_seq' => $student_exam_seq,
            'student_exam' => $student_exam,
        ]);
    }


    // 오답 노트 전체 카운트 가져오기.
    private function getWrongCnt($data){
        $student_seq = $data['student_seq'];
        $subject_seq = $data['subject_seq'] ?? null;
        $start_date = $data['start_date'] ?? null;
        $end_date = $data['end_date'] ?? null;
        $search_standard = $data['search_standard'] ?? null;

        // 10우선 10일 내로 완료한 학습
        // 을 가져온다.
        $complete_exams = \App\StudentExam::
            select(
                'student_exams.*',
                'exams.exam_title',
                'subject_codes.id as subject_seq',
                'subject_codes.code_name as subject_name',
                // 오늘과 complete_datetime3  + 2일 날짜 가져오기
                DB::raw('DATE_ADD(complete_datetime, INTERVAL 2 DAY) as datetime'),
                DB::raw('date(now()) as today')
            )
            ->leftJoin('exams', 'student_exams.exam_seq', '=', 'exams.id')
            ->leftJoin('codes as subject_codes', 'exams.subject_seq', '=', 'subject_codes.id')
            ->where('student_exams.student_seq', $student_seq)
            ->where(function($query) use($start_date, $end_date){
                if($start_date && $end_date){
                    $start_date = \Carbon\Carbon::parse($start_date)->subDays(2)->format('Y-m-d H:i:s');
                    $end_date = \Carbon\Carbon::parse($end_date)->subDays(2)->endOfDay()->format('Y-m-d H:i:s');

                    $query->whereBetween('complete_datetime', [$start_date, $end_date]);
                }else{
                    // 기본적으로 start_date 가 없으면 오늘까지를 기준으로 가져온다.
                    $start_today = \Carbon\Carbon::now()->subDays(2)->startOfDay()->format('Y-m-d H:i:s');
                    $end_today = \Carbon\Carbon::now()->subDays(2)->endOfDay()->format('Y-m-d H:i:s');

                    $query->whereBetween('complete_datetime', [$start_today, $end_today]);
                }
            })
            // 과목별
            ->where(function($query) use ($subject_seq){
                if($subject_seq){
                    $query->where('exams.subject_seq', $subject_seq);
                }
                else{
                    $query->where(DB::raw(1), 1);
                }
            });
            if($search_standard){
                if($search_standard == 'date_asc'){
                    $complete_exams->orderBy('complete_datetime', 'asc');
                }
                else if($search_standard == 'date_desc'){
                    $complete_exams->orderBy('complete_datetime', 'desc');
                }
            }
            $result['sql'] = $complete_exams->toSql();
            $result['bindings'] = $complete_exams->getBindings();
            $complete_exams = $complete_exams->get();

        // 학생의 전체 틀린 문제 수를 가져온다.
        // 학생의 몇강의 수업인지를 묶어서 가져온다.
        $wrong_cnts = \App\StudentExamResult::
            select(
                'student_seq',
                'student_exam_seq',
                DB::raw('count(*) as cnt')
            )
            ->where('student_seq', $student_seq)
            ->where('exam_type', '<>', 'easy')
            ->where('exam_status', 'wrong')
            ->where('wrong_status', null)
            ->groupBy('student_seq', 'student_exam_seq')
            ->get();

        // $wrong_cnts 를 함수로 [studetn_lecture_seq] => cnt 형태로 변환 pluck 사용
        // $wrong_sld_seqs = $wrong_cnts->pluck('student_lecture_detail_seq' . '|' .'lecture_detail_seq');
        $wrong_sld_seqs = $wrong_cnts->map(function ($item) {
            return $item['student_exam_seq'];
        });
        // $wrong_cnts = $wrong_cnts->pluck('cnt', 'student_lecture_detail_seq');
        $wrong_cnts = $wrong_cnts->mapWithKeys(function ($item) {
            return [$item['student_exam_seq'] => $item['cnt']];
        });

        // 결과
        $result['wrong_cnts'] = $wrong_cnts;
        $result['complete_exams'] = $complete_exams;
        $result['wrong_sld_seqs'] = $wrong_sld_seqs;

        return $result;
    }

    // 오답노트 불러오기.
    public function wrongSelect(Request $request){
        $student_seq = session()->get('student_seq');
        $subject_seq = $request->input('subject_seq');
        $remain_date_cnt = $request->input('remain_date_cnt');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $search_standard = $request->input('serach_standard');

        $is_page = $request->input('is_page');
        $page = $request->input('page');
        $page = $page ?? 1;
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 10;

        $data['student_seq'] = $student_seq;
        $data['subject_seq'] = $subject_seq;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['search_standard'] = $search_standard;

        $wrongs_array = $this->getWrongCnt($data);
        // 완료한 학습중 시험이 틀린 학습만 가져온다.
        // $wrong_keys = $wrongs_array['wrong_cnts']->keys();
        // $wrongs_array['complete_exams']->whereIn('id', $wrong_keys)->values();
        if($is_page == 'Y'){
            // :페이징 처리
            $filtered = $wrongs_array['complete_exams']->whereIn('id', $wrongs_array['wrong_sld_seqs'])->values();
            $sliced = $filtered->slice(($page - 1) * $page_max, $page_max)->values();
            $paginated = new LengthAwarePaginator(
                $sliced,
                $filtered->count(),
                $page_max,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            $result['no_complete_exams_page'] = $paginated;

            $filtered = $wrongs_array['complete_exams']->whereNotIn('id', $wrongs_array['wrong_sld_seqs'])->values();
            $sliced = $filtered->slice(($page - 1) * $page_max, $page_max)->values();
            $paginated = new LengthAwarePaginator(
                $sliced,
                $filtered->count(),
                $page_max,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            $result['complete_exams_page'] = $paginated;
        }
        // 결과.
        $result['resultCode'] = 'success';
        $result['wrong_cnts'] = $wrongs_array['wrong_cnts'];
        $result['complete_exams'] = $wrongs_array['complete_exams'];
        $result['wrong_sld_seqs'] = $wrongs_array['wrong_sld_seqs'];
        $result['sql'] = $wrongs_array['sql'];
        $result['bindings'] = $wrongs_array['bindings'];
        return response()->json($result);
    }

    // 오답노트 - 학습 날짜에 따른 데이터 가져오기.
    public function completeLecturesSelect(Request $request){
        $student_seq = session()->get('student_seq');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

//         select
//   student_lecture_details.*, lecture_details.lecture_detail_link
// from
//   `student_lecture_details`
// left join lecture_details on lecture_details.id = student_lecture_details.lecture_detail_seq
// where
//   `student_seq` = 2787
//   and `sel_date` between '2024-11-17'
//   and '2024-11-23'
// and lecture_detail_link <> ''
//
        $student_lecture_details = \App\StudentLectureDetail::
            select('student_lecture_details.*', 'lecture_details.lecture_detail_link')
            ->leftJoin('lecture_details', 'lecture_details.id', '=', 'student_lecture_details.lecture_detail_seq')
            ->where('student_seq', $student_seq)
            ->where('lecture_details.lecture_detail_link', '<>', '')
            ->whereBetween('sel_date', [$start_date, $end_date])
            ->whereNull('lecture_type') // 학교공부제외.
            ->get();

        // 결과
        $result['resultCode'] = 'success';
        $result['student_lecture_details'] = $student_lecture_details;

        return response()->json($result);
    }

    // 오답노트 출력하기 전 정보 전송.
    public function wrongNotePrint(Request $request){
        // $student_seq = session()->get('student_seq');
        $sel_data = $request->input('sel_data');
        $is_complete = $request->input('is_complete');

        $student_seqs = [];
        $student_exam_seqs = [];
        foreach($sel_data as $data){
            $student_seqs[] = $data['student_seq'];
            $student_exam_seqs[] = $data['student_exam_seq'];
        }
        // 오답노트의 정보를 가져온다.
        $student_exam_results = \App\StudentExamResult::
            select(
                'student_exam_results.*',
                'exams.exam_title',
                'exam_details.*',
                'qimg.file_path as question_file_path',
                'simg1.file_path as sample_file_path1',
                'simg2.file_path as sample_file_path2',
                'simg3.file_path as sample_file_path3',
                'simg4.file_path as sample_file_path4',
                'simg5.file_path as sample_file_path5',
                'cvideo.file_path as commentary_file_path'
            )
            ->leftJoin('exams', 'student_exam_results.exam_seq', '=', 'exams.id')
            ->leftJoin('exam_details', function($join) {
                $join->on('student_exam_results.exam_seq', '=', 'exam_details.exam_seq')
                    ->on('student_exam_results.exam_num', '=', 'exam_details.exam_num')
                    ->on('student_exam_results.exam_type', '=', 'exam_details.exam_type');
            })
            ->leftJoin('exam_uploadfiles as qimg', function($join) {
                $join->on('qimg.exam_detail_seq', '=', 'exam_details.id')
                    ->where('qimg.file_type', '=', 'question');
            })
            ->leftJoin('exam_uploadfiles as simg1', function($join) {
                $join->on('simg1.exam_detail_seq', '=', 'exam_details.id')
                    ->where('simg1.file_type', '=', 'sample1');
            })
            ->leftJoin('exam_uploadfiles as simg2', function($join) {
                $join->on('simg2.exam_detail_seq', '=', 'exam_details.id')
                    ->where('simg2.file_type', '=', 'sample2');
            })
            ->leftJoin('exam_uploadfiles as simg3', function($join) {
                $join->on('simg3.exam_detail_seq', '=', 'exam_details.id')
                    ->where('simg3.file_type', '=', 'sample3');
            })
            ->leftJoin('exam_uploadfiles as simg4', function($join) {
                $join->on('simg4.exam_detail_seq', '=', 'exam_details.id')
                    ->where('simg4.file_type', '=', 'sample4');
            })
            ->leftJoin('exam_uploadfiles as simg5', function($join) {
                $join->on('simg5.exam_detail_seq', '=', 'exam_details.id')
                    ->where('simg5.file_type', '=', 'sample5');
            })
            ->leftJoin('exam_uploadfiles as cvideo', function($join) {
                $join->on('cvideo.exam_detail_seq', '=', 'exam_details.id')
                    ->where('cvideo.file_type', '=', 'commentary');
            })
            ->whereIn('student_seq', $student_seqs)
            ->whereIn('student_exam_seq', $student_exam_seqs)
            ->where('student_exam_results.exam_type', '<>', 'easy')
            ->where('student_exam_results.exam_status', 'wrong')
            ->whereNull('student_exam_results.wrong_status')
            ->orderBy('student_exam_results.exam_num')
            ->get();

        $normals = (clone $student_exam_results)->where('exam_type', 'normal')->groupBy('student_exam_seq');
        $similars = (clone $student_exam_results)->where('exam_type', 'similar')->groupBy('student_exam_seq');
        $challenges = (clone $student_exam_results)->where('exam_type', 'challenge')->groupBy('student_exam_seq');
        $challenge_similars = (clone $student_exam_results)->where('exam_type', 'challenge_similar')->groupBy('student_exam_seq');

        // 결과
        $result['resultCode'] = 'success';
        $result['student_exam_results'] = $student_exam_results;
        $result['normals'] = $normals;
        $result['similars'] = $similars;
        $result['challenges'] = $challenges;
        $result['challenge_similars'] = $challenge_similars;

        return response()->json($result);
    }

    function getSumStartDate(){
        $today = date('Y-m-d');
        $week = date('w', strtotime($today));
        $start = date('Y-m-d', strtotime($today.'-'.($week).' days'));
        $end = date('Y-m-d', strtotime($today.'+'.(6-$week).' days'));

        return [$start, $end];
    }
}
