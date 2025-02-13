<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\LearningMTController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ExamMTController;
use Dotenv\Parser\Value;
use Illuminate\Support\Facades\Validator;
class StudyVideoMtController extends Controller
{
    //
    public function list(Request $request)
    {
        $is_go_complete = $request->input('is_go_complete');
        $student_seq = session()->get('student_seq');
        $st_lecture_detail_seq = $request->input('st_lecture_detail_seq');
        $prev_page = $request->input('prev_page');
        // 학습 내부 이름 가져오기.
        // like 유무 가져오기.
        $st_lecture_details = $this->getMainSql($student_seq);

        // 지정 학습 정보 가져오기. $st_lecture_detail_seq
        $st_seq_detail = (clone $st_lecture_details)
            ->where('student_lecture_details.id', $st_lecture_detail_seq)
            ->first();

        // 상단 탭 메뉴 가져오기.
        $lecture_seq = $st_seq_detail->lecture_seq;
        $lecture_detail_seq = $st_seq_detail->lecture_detail_seq;
        $top_menutabs = \App\LectureDetail::where('lecture_seq', $lecture_seq)
            ->where(function($query) use ($lecture_detail_seq){
                $query->where('id', $lecture_detail_seq)
                    ->orWhere('lecture_detail_group', $lecture_detail_seq);
            })
            ->select(
                'id',
                'lecture_detail_type',
                'lecture_detail_description',
                'idx'
            )
            ->orderBy('idx')
            ->get();

        // 학생의 학습 중인 리스트 가져오기.
        $st_status_study = (clone $st_lecture_details)
            ->where('student_lecture_details.status', 'study')
            ->get();

        //강좌과목갖고오기
        $lecture_subject = \App\Lecture::where('id', $lecture_seq)->first();

        // 현재 라우터 경로를 세션에 저장
        session()->put('_previous', ['url' => url()->current()]);


        // 완료가 되었는지 확인.
        // 완료가 되지 않은 학습으로 바로 이동.
        // 준비하기 - 단 is_go_complete 가 비어있으면 무조건 준비하기로 이동.
        $request->merge([
            'lecture_detail_seq' => $lecture_detail_seq,
            'lecture_seq' => $lecture_seq,
        ]);
        if($st_seq_detail->is_complete != 'Y' || $is_go_complete == ''){
            return view('student.student_study_video', [
                'st_lecture_detail_seq' => $st_lecture_detail_seq,
                'lecture_detail_info' => $st_seq_detail,
                'st_status_study' => $st_status_study,
                'top_menutabs' => $top_menutabs,
                'lecture_seq' => $lecture_seq,
                'lecture_detail_seq' => $lecture_detail_seq,
                'lecture_subject' => $lecture_subject,
                'prev_page' => $prev_page
            ]);
        }
        // 개념다지기
        else if($st_seq_detail->is_complete2 != 'Y'
            && $top_menutabs->where('lecture_detail_type', 'concept_building')->count() > 0){
            return $this->concept($request);
        }
        // 정리학습
        else if($st_seq_detail->is_complete3 != 'Y'
            && $top_menutabs->where('lecture_detail_type', 'summarizing')->count() > 0){
            return $this->summary($request);
        }
        // 문제풀기
        else if($st_seq_detail->is_complete4 != 'Y'
            && $top_menutabs->where('lecture_detail_type', 'exam_solving')->count() > 0){
            return $this->quiz($request);
        }
        // 단원평가
        else if($st_seq_detail->is_complete5 != 'Y'
            && $top_menutabs->where('lecture_detail_type', 'unit_test')->count() > 0){
            return $this->unitQuiz($request);
        }
        // 준비하기
        else{
            return view('student.student_study_video', [
                'st_lecture_detail_seq' => $st_lecture_detail_seq,
                'lecture_detail_info' => $st_seq_detail,
                'st_status_study' => $st_status_study,
                'top_menutabs' => $top_menutabs,
                'lecture_seq' => $lecture_seq,
                'lecture_detail_seq' => $lecture_detail_seq,
                'lecture_subject' => $lecture_subject,
                'prev_page' => $prev_page,
                'all_is_complete' => 'Y'
            ]);
        }

    }
    // MAIN SQL
    private function getMainSql($seq, $seq_type = 'student_seq')
    {
        $main_sql = \App\StudentLectureDetail::select(
            'student_lecture_details.student_seq',
            'student_lecture_details.teach_seq',
            'student_lecture_details.status',
            'student_lecture_details.is_like',
            'student_lecture_details.lecture_detail_seq',
            'lectures.lecture_name',
            'lecture_details.lecture_seq',
            'lecture_details.idx',
            'lecture_details.lecture_detail_link',
            'lecture_details.lecture_detail_name',
            'student_lecture_details.last_video_time',
            'student_lecture_details.last_video_time2',
            'student_lecture_details.last_video_time4',
            'student_lecture_details.is_complete',
            'student_lecture_details.is_complete2',
            'student_lecture_details.is_complete3',
            'student_lecture_details.is_complete4',
            'student_lecture_details.is_complete5'
        )
            ->leftJoin('lecture_details', 'lecture_details.id', '=', 'student_lecture_details.lecture_detail_seq')
            ->leftJoin('lectures', 'lectures.id', '=', 'lecture_details.lecture_seq')
            ->where('student_lecture_details.'.$seq_type, $seq);
        return $main_sql;
    }

    // 오늘의 학습 가지고 오기.
    public function studyPlannerSelect(Request $request)
    {

        $student_seq = session()->get('student_seq');
        $search_start_date = date('Y-m-d'); // date('Y-m-d', strtotime('-1 days'
        $search_end_date = date('Y-m-d');
        $select_type = 'no_group';

        // $request 안에 student_seqs 넣어주기.
        $request->merge([
            'student_seqs' => $student_seq,
            'search_start_date' => $search_start_date,
            'search_end_date' => $search_end_date,
            'select_type' => $select_type
        ]);

        // LearningMTController 의 studyPlannerSelect사용
        $learningMTController = new LearningMTController();
        return $learningMTController->studyPlannerSelect($request);
    }

    // 현재 학습중

    // 좋아요 업데이트
    public function updateLike(Request $request)
    {
        $student_seq = session()->get('student_seq');
        $is_like = $request->input('is_like');
        $student_lecture_detail_seq = $request->input('st_lecture_detail_seq');

        //update
        $update = \App\StudentLectureDetail::where('id', $student_lecture_detail_seq)
            ->where('student_seq', $student_seq)
            ->update([
                'is_like' => $is_like
            ]);

        if ($update == 1) {
            return response()->json(['resultCode' => 'success']);
        } else {
            return response()->json(['resultCode' => 'fail']);
        }
    }

    // 동영상 마지막 시간, 동여상 누적시간 넣기.
    public function updateVideoTime(Request $request)
    {

        $student_seq = session()->get('student_seq');
        $student_lecture_detail_seq = $request->input('st_lecture_detail_seq');
        $last_video_time = $request->input('last_video_time');
        $acc_video_time = $request->input('acc_video_time');
        $lecture_detail_time = $request->input('lecture_detail_time'); // 실제 동여상 시간 업데이트를 위해서.
        $lecture_detail_type = $request->input('lecture_detail_type');
        $teach_seq = $request->session()->get('teach_seq');

        $login_type = $request->session()->get('login_type');

        $seq_type = 'student_seq';
        $seq = $student_seq;
        if($login_type == 'teacher'){
            $seq_type = 'teach_seq';
            $seq = $teach_seq;
        }


        // 마지막에 본 시간 업데이트.
        // javascirpt btoa로 왔기 때문에 decode 해준다.
        $last_video_time = base64_decode($last_video_time);
        $acc_video_time = base64_decode($acc_video_time);

        $update = \App\StudentLectureDetail::where('id', $student_lecture_detail_seq)
            ->where($seq_type, $seq)
            ->first();

        if ($update) {
            if ($update->status == 'ready') $update->status = 'study';

            // 넣으려고 하는 시간이 더 크면 넣지 않는다.
            $update_last_video_time = $update->last_video_time;
            // 타입에 따라서 마지막 시간 컬럼 변경.
            if($lecture_detail_type == 'concept_building')
                $update_last_video_time = $update->last_video_time2;
            else if($lecture_detail_type == 'summarizing')
                $update_last_video_time = $update->last_video_time4;

            if($update_last_video_time <= $last_video_time){
                // 타입에 따라서 마지막 시간 컬럼 변경.
                if($lecture_detail_type == 'concept_building'){
                    $update->last_video_time2 = $last_video_time;
                } else if($lecture_detail_type == 'summarizing'){
                    $update->last_video_time4 = $last_video_time;
                }else{
                    $update->last_video_time = $last_video_time;
                }
            }
            $update->view_count = $update->view_count + 1;

            // acc는 더이상 사용하지 않는다. 참고.
            //  null일 수 있으므로 체크해준다.
            $prev_acc_video_time = $update->acc_video_time == null ? 0 : $update->acc_video_time;
            $update->acc_video_time = ($prev_acc_video_time + $acc_video_time);
            $update->save();

            // TODO: 아래처럼 해도 되나, 합산처리 해서 넣는게 더 정확하므로 수정필요.
            // 기획에서 갑자기 영상이 여러개로 분리되어서 view_count 를 쪼개서 넣어야 할 수도 있음. 추후 확인.
            $student_lecture_seq = $update->student_lecture_seq;
            $update2 = \App\StudentLecture::where('id', $student_lecture_seq)->first();
            $update2->view_count = $update2->view_count + 1;
            if ($update2->status == 'ready') $update2->status = 'study';
            $update2->save();
        }
        // 동영상의 총시간 업데이트.편의기능.
        // $lecture_detail_time (분:초)를 (시간:분)으로 넣어준다.
        $chage_detail_time = '';
        if ($lecture_detail_time) {
            $chage_detail_time = $this->timeToSecond($lecture_detail_time)."";
        }

        if ($chage_detail_time) {
            $lecture_detail_seq = $update->lecture_detail_seq;
            $lecture_detail = \App\LectureDetail::select('*');
            if($lecture_detail_type != ''){
                $lecture_detail->where('lecture_detail_type', $lecture_detail_type)
                ->where('lecture_detail_group', $lecture_detail_seq);
            }else{
                // 준비하기
                $lecture_detail = $lecture_detail->where('id', $lecture_detail_seq);
            }
            $lecture_detail = $lecture_detail->first();

            $lecture_detail->lecture_detail_time = $chage_detail_time;

            $lecture_detail->save();
        }

        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 완료 업데이트.
    public function updateComplete(Request $request)
    {
        $examController = new ExamMTController();
        // $student_seq = session()->get('student_seq');
        $st_lecture_detail_seq = $request->input('st_lecture_detail_seq');
        $lecture_detail_type = $request->input('lecture_detail_type');
        $student_exam_seq = $request->input('student_exam_seq');

        $update = \App\StudentLectureDetail::find($st_lecture_detail_seq);
        // 학습하기에서 진행해서 들어왔을때.
        if($st_lecture_detail_seq){
            if($update->status != 'complete'){
                // 단 한번 업데이트를 했고, 현재시간보다 낮으면
                // 시간을 더이상 업데이트 하지 않음.
                // 계속 최신 시간을 업데이트 하고 싶으면 if 주석처리.
                if($update->status == 'ready'){
                    $update->status = 'study';
                }
                if($lecture_detail_type == 'concept_building'){
                    $update->is_complete2 = 'Y';
                    if(strlen($update->complete_datetime2) < 1){
                        $update->complete_datetime2 = date('Y-m-d H:i:s');
                    }
                }else if($lecture_detail_type == 'exam_solving'){
                    $update->is_complete4 = 'Y';
                    $update->complete_datetime4 = date('Y-m-d H:i:s');
                    $examController->examComplete($request);
                }else if($lecture_detail_type == 'summarizing'){
                    $update->is_complete3 = 'Y';
                    if(strlen($update->complete_datetime3) < 1){
                        $update->complete_datetime3 = date('Y-m-d H:i:s');
                    }
                }else if($lecture_detail_type == 'unit_test'){
                    $update->is_complete5 = 'Y';
                    if(strlen($update->complete_datetime5) < 1){
                        $update->complete_datetime5 = date('Y-m-d H:i:s');
                    }
                    $examController->examComplete($request);
                }else{
                    $update->is_complete = 'Y';
                    if(strlen($update->complete_datetime) < 1){
                        $update->complete_datetime = date('Y-m-d H:i:s');
                    }
                }
                $lecture_detail_group = $update->lecture_detail_seq;
                // 준비하기를 제외한 카운트
                $groupsCnt = \App\LectureDetail::where('lecture_detail_group', $lecture_detail_group)->count();


                // 모든 is_complete 가 'Y' 이면 status를 complete로 변경해준다.
                $completeSum = 0;
                if($update->is_complete == 'Y'){
                    if($update->is_complete2 == 'Y') $completeSum++;
                    if($update->is_complete3 == 'Y') $completeSum++;
                    if($update->is_complete4 == 'Y') $completeSum++;
                    if($update->is_complete5 == 'Y') $completeSum++;
                    if($groupsCnt == $completeSum){
                        $update->status = 'complete';
                    }
                }

                $update->save();
            }
        }
        // 그외에서 시험을 치려고 들어오면 진행.
        else {
            if($student_exam_seq) $examController->examComplete($request);
        }

        // : details 이 모두 complete 가 되면은 student_lectures 의 sta션 도 마찬가지로 complete 로 변경해준다.

        if ($update) {
            return response()->json(['resultCode' => 'success']);
        } else
        return response()->json(['resultCode' => 'fail']);
    }
    // 문제풀기
    public function quiz(Request $request)
    {
        $student_seq = session()->get('student_seq');
        $teach_seq = session()->get('teach_seq');
        $st_lecture_detail_seq = $request->input('st_lecture_detail_seq');
        $prev_page = $request->input('prev_page');
        $exam_seq = $request->input('exam_seq');
        $lecture_seq = $request->input('lecture_seq');
        $lecture_subject = \App\Lecture::where('id', $lecture_seq)->first();
        $login_type = session()->get('login_type');

        $seq_type = 'student_seq';
        $seq = $student_seq;
        if($login_type == 'teacher'){
            $seq_type = 'teach_seq';
            $seq = $teach_seq;
        }
        // 학습 이름 가져오기.
        // 학습 내부 이름 가져오기.
        // like 유무 가져오기.
        $st_lecture_details = $this->getMainSql($seq, $seq_type);

        // 지정 학습 정보 가져오기. $st_lecture_detail_seq
        $st_seq_detail = (clone $st_lecture_details)
            ->where('student_lecture_details.id', $st_lecture_detail_seq)
            ->first();

        // 학생의 학습 중인 리스트 가져오기.
        $st_status_study = (clone $st_lecture_details)
            ->where('student_lecture_details.status', 'study')
            ->get();

        // 상단 탭 메뉴 가져오기.
        if($st_seq_detail){
            $lecture_seq = $st_seq_detail->lecture_seq;
            $lecture_detail_seq = $st_seq_detail->lecture_detail_seq;
            $top_menutabs = \App\LectureDetail::where('lecture_seq', $lecture_seq)
                ->where(function($query) use ($lecture_detail_seq){
                    $query->where('id', $lecture_detail_seq)
                        ->orWhere('lecture_detail_group', $lecture_detail_seq);
                })
                ->select(
                    'id',
                    'lecture_detail_type',
                    'idx',
                    'lecture_exam_seq',
                    'lecture_detail_description'
                )
                ->orderBy('idx')
                ->get();
        }

        // 문제가져오기
        // 기본문제
        if($exam_seq){
            // 학습관리 없이 바로 시험을 치는 곳에서 왔을때.(추가요청)
            $top_menutabs = null;
            $lecture_seq = '';
            $lecture_detail_seq = '';
            $lecture_exam_seq = $exam_seq;
            $exam_lecdture_detail_seq = '';
        }
        else{
            // 정상적인 루트
            $lecture_exam_seq = $top_menutabs->where('lecture_detail_type', 'exam_solving')->first()->lecture_exam_seq;
            $exam_lecdture_detail_seq = $top_menutabs->where('lecture_detail_type', 'exam_solving')->first()->id;
        }

        $normals = \App\ExamDetail::where('exam_seq', $lecture_exam_seq)
            ->where('exam_type', 'normal')
            ->orderBy('exam_num')
            ->get()
            ->map(function($item) {
                $item->questions2 = str_replace('<br>', "\n", $item->questions2);
                return $item;
            });
        $similars = \App\ExamDetail::where('exam_seq', $lecture_exam_seq)
            ->where('exam_type', 'similar')
            ->orderBy('exam_num')
            ->get()
            ->map(function($item) {
                $item->questions2 = str_replace('<br>', "\n", $item->questions2);
                return $item;
            });
        $challenges = \App\ExamDetail::where('exam_seq', $lecture_exam_seq)
            ->where('exam_type', 'challenge')
            ->orderBy('exam_num')
            ->get()
            ->map(function($item) {
                $item->questions2 = str_replace('<br>', "\n", $item->questions2);
                return $item;
            });
        $challenge_similars = \App\ExamDetail::where('exam_seq', $lecture_exam_seq)
            ->where('exam_type', 'challenge_similar')
            ->orderBy('exam_num')
            ->get()
            ->map(function($item) {
                $item->questions2 = str_replace('<br>', "\n", $item->questions2);
                return $item;
            });

        // 이미지 및 동영상 파일 경로.
        $exam_uploadfiles = \App\ExamUploadfile::where('exam_seq', $lecture_exam_seq)
            ->get();

       // 학생이 답한 답 가져오기.

        $student_answers = \App\StudentExamResult::select('student_exam_results.*')
            ->leftJoin('student_exams', 'student_exams.id', '=', 'student_exam_results.student_exam_seq')
            ->where('student_exam_results.student_seq', $student_seq)
            ->where('student_exams.teach_seq', $teach_seq)
            ->where('student_exams.exam_seq', $lecture_exam_seq)
            ->where('student_exams.lecture_detail_seq', $exam_lecdture_detail_seq)
            ->where('student_exams.student_lecture_detail_seq', $st_lecture_detail_seq)
            ->orderBy('student_exam_results.exam_type')
            ->orderBy('student_exam_results.exam_num')
            ->get();

        session()->put('_previous', ['url' => url()->current()]);

        return view('student.student_study_quiz', [
            'st_lecture_detail_seq' => $st_lecture_detail_seq,
            'lecture_detail_info' => $st_seq_detail,
            'st_status_study' => $st_status_study,
            'top_menutabs' => $top_menutabs,
            'lecture_seq' => $lecture_seq,
            'lecture_detail_seq' => $lecture_detail_seq,
            'normals' => $normals,
            'similars' => $similars,
            'challenges' => $challenges,
            'challenge_similars' => $challenge_similars,
            'exam_uploadfiles' => $exam_uploadfiles,
            'st_answers' => $student_answers,
            'exam_lecdture_detail_seq' => $exam_lecdture_detail_seq,
            'lecture_subject' => $lecture_subject,
            'prev_page' => $prev_page,
            'login_type' => $login_type
        ]);
    }

    //문제풀기 화면접속시 student_exam_results 테이블에 데이터 추가 또는 변경.
    public function studentExamInsertOrUpdate(Request $request){
        $student_seq = session()->get('student_seq');
        $lecture_detail_group = $request->input('lecture_detail_group');
        $lecture_seq = $request->input('lecture_seq');
        $student_lecture_detail_seq = $request->input('student_lecture_detail_seq');
        $lecture_detail = \App\LectureDetail::where('lecture_seq', $lecture_seq)
        ->where('lecture_detail_group', $lecture_detail_group)
        ->where('lecture_detail_type', 'exam_solving')
        ->first();
        $lecture_exam_seq = $lecture_detail->lecture_exam_seq;
        $student_exam_seq = optional(\App\StudentExam::where('exam_seq', $lecture_exam_seq)
        ->where('student_seq', $student_seq)
        ->where('student_lecture_detail_seq', $student_lecture_detail_seq)
        ->first());

        // $student_exam = \App\StudentExam::updateOrCreate([
        //     'student_seq' => $student_seq,
        //     'exam_seq' => $lecture_exam_seq,
        //     'student_lecture_detail_seq' => $student_lecture_detail_seq,
        //     'lecture_detail_type' => 'exam_solving',
        // ], [
        //     'exam_status' => $student_exam_seq->exam_status == null ? 'study' : $status
        // ]);

        // $student_exam->save();
        return response()->json(['resultCode' => 'success', 'student_exam_seq' => $student_exam_seq->exam_status]);
    }


    public function getQuizSelect(Request $request){
        $student_seq = session()->get('student_seq');
        $lecture_detail_group = $request->input('lecture_detail_group');
        $lecture_seq = $request->input('lecture_seq');
        $st_lecture_detail_seq = $request->input('st_lecture_detail_seq');

        $lecture_detail = \App\LectureDetail::where('lecture_seq', $lecture_seq)
        ->where('lecture_detail_group', $lecture_detail_group)
        ->where('lecture_detail_type', 'exam_solving')
        ->first();

        //단일 문제
        $lecture_exam_seq = $lecture_detail->lecture_exam_seq;
        $exam_detail = \App\ExamDetail::where('exam_seq', $lecture_exam_seq)
        ->where('exam_type', 'normal')
        ->get();

        $student_exam = optional(\App\StudentExam::where('exam_seq', $lecture_exam_seq)
        ->where('student_seq', $student_seq)
        ->where('student_lecture_detail_seq', $st_lecture_detail_seq)
        ->first())->id;

        //풀어낸 문제
        $results_exam = \App\StudentExamResult::where('student_exam_seq', $student_exam)
        ->where('student_seq', $student_seq)
        ->get();

        if ($results_exam->isEmpty()) {
            $results_exam = null;
        }
        return response()->json(['resultCode' => 'success', 'exam_detail' => $exam_detail, 'student_exam' => $student_exam, 'results_exam' => $results_exam]);
    }

    public function getQuizResult(Request $request){
        $student_seq = session()->get('student_seq');
        $st_lecture_detail_seq = $request->input('st_lecture_detail_seq');
        $lecture_detail_group = $request->input('lecture_detail_group');
        $lecture_seq = $request->input('lecture_seq');

        $lecture_detail = \App\LectureDetail::where('lecture_seq', $lecture_seq)
            ->where('lecture_detail_group', $lecture_detail_group)
            ->where('lecture_detail_type', 'exam_solving')
            ->first();

        $lecture_exam_seq = $lecture_detail->lecture_exam_seq;

        $student_answers = \App\StudentExamResult::select('student_exam_results.*')
            ->leftJoin('student_exams', 'student_exams.id', '=', 'student_exam_results.student_exam_seq')
            ->where('student_exam_results.student_seq', $student_seq)
            ->where('student_exams.exam_seq', $lecture_exam_seq)
            ->where('student_exams.lecture_detail_seq', $lecture_detail_group)
            ->where('student_exams.student_lecture_detail_seq', $st_lecture_detail_seq)
            ->orderBy('student_exam_results.exam_type')
            ->orderBy('student_exam_results.exam_num')
            ->get();

        return response()->json(['resultCode' => 'success', 'student_answers' => $student_answers]);
    }

    public function getQuizDetail(Request $request){
        $student_seq = session()->get('student_seq');
        $st_lecture_detail_seq = $request->input('st_lecture_detail_seq');
        $lecture_detail_group = $request->input('lecture_detail_group');
        $exam_num = $request->input('exam_num');
        $lecture_seq = $request->input('lecture_seq');
        $exam_type = $request->input('exam_type');

        $lecture_detail = \App\LectureDetail::where('lecture_seq', $lecture_seq)
            ->where('lecture_detail_group', $lecture_detail_group)
            ->where('lecture_detail_type', 'exam_solving')
            ->first();

        $lecture_exam_seq = $lecture_detail->lecture_exam_seq;

        $exam_detail = \App\ExamDetail::where('exam_seq', $lecture_exam_seq)
            ->where('exam_type', $exam_type)
            ->where('exam_num', $exam_num)
            ->first();

        $student_exam = optional(\App\StudentExam::where('exam_seq', $lecture_exam_seq)
        ->where('student_seq', $student_seq)
        ->where('student_lecture_detail_seq', $st_lecture_detail_seq)
        ->first())->id;

        $student_answers = \App\StudentExamResult::where('exam_seq', $lecture_exam_seq)
            ->where('exam_num', $exam_num)
            ->where('student_seq', $student_seq)
            ->where('student_exam_seq', $student_exam)
            ->first();
        if (is_null($student_answers)) {
            $student_answers = null;
        }
        return response()->json(['resultCode' => 'success', 'exam_detail' => $exam_detail, 'student_answers' => $student_answers]);
    }

    public function quizInsertOrUpdate(Request $request){
        $student_seq = session()->get('student_seq');
        $st_lecture_detail_seq = $request->input('st_lecture_detail_seq');
        $exam_num = $request->input('exam_num');
        $lecture_detail_group = $request->input('lecture_detail_group');
        $lecture_seq = $request->input('lecture_seq');
        $student_answer = $request->input('student_answer');

        $validator = Validator::make($request->all(), [
            'student_answer' => 'nullable'
        ]);

        if($validator->fails()){
            return response()->json(['resultCode' => 'fail', 'errors' => $validator->errors()]);
        }

        // 학습 정보 가져오기.
        $lecture_detail = \App\LectureDetail::where('lecture_seq', $lecture_seq)
            ->where('lecture_detail_group', $lecture_detail_group)
            ->where('lecture_detail_type', 'exam_solving')
            ->first();

        $lecture_exam_seq = $lecture_detail->lecture_exam_seq;

        $student_exam = optional(\App\StudentExam::where('exam_seq', $lecture_exam_seq)
            ->where('student_seq', $student_seq)
            ->where('student_lecture_detail_seq', $st_lecture_detail_seq)
            ->first());

        $exam_detail = \App\ExamDetail::where('exam_seq', $lecture_exam_seq)
            ->where('exam_num', $exam_num)
            ->first();

        if($exam_detail->answer == $student_answer){
            $exam_status = 'correct';
        }else{
            $exam_status = 'wrong';
        }

        if($student_exam->exam_status == 'study'){
            $exam_result_data = [
                'student_exam_seq' => $student_exam->id,
                'student_seq' => $student_seq,
                'exam_seq' => $lecture_exam_seq,
                'exam_num' => $exam_num,
                'answer' => $exam_detail->answer,
                'exam_type' => "easy",
                'exam_status' => 'ready'
            ];
        }else if($student_exam->exam_status == 'submit'){
            $exam_result_data = [
                'student_exam_seq' => $student_exam->id,
                'student_seq' => $student_seq,
                'exam_seq' => $lecture_exam_seq,
                'exam_num' => $exam_num,
                'answer' => $exam_detail->answer,
                'exam_status' => $exam_status
            ];
        }

        $student_exam_result = \App\StudentExamResult::updateOrCreate($exam_result_data, [
            'student_answer' => $student_answer,
        ]);
        $student_exam_result->save();

        return response()->json(['resultCode' => 'success', 'exam_result_data' => $exam_result_data]);
    }

    // 개념다지기
    public function concept(Request $request)
    {
        $student_seq = session()->get('student_seq');
        $st_lecture_detail_seq = $request->input('st_lecture_detail_seq');
        $prev_page = $request->input('prev_page');

        // 학습 이름 가져오기.
        // 학습 내부 이름 가져오기.
        // like 유무 가져오기.
        $st_lecture_details = $this->getMainSql($student_seq);

        // 지정 학습 정보 가져오기. $st_lecture_detail_seq
        $st_seq_detail = (clone $st_lecture_details)
            ->where('student_lecture_details.id', $st_lecture_detail_seq)
            ->first();

        // 학생의 학습 중인 리스트 가져오기.
        $st_status_study = (clone $st_lecture_details)
            ->where('student_lecture_details.status', 'study')
            ->get();


        // 상단 탭 메뉴 가져오기.
        $lecture_seq = $st_seq_detail->lecture_seq;
        $lecture_detail_seq = $st_seq_detail->lecture_detail_seq;
        $top_menutabs = \App\LectureDetail::where('lecture_seq', $lecture_seq)
            ->where(function($query) use ($lecture_detail_seq){
                $query->where('id', $lecture_detail_seq)
                    ->orWhere('lecture_detail_group', $lecture_detail_seq);
            })
            ->select(
                'id',
                'lecture_detail_type',
                'idx',
                'lecture_exam_seq',
                'lecture_detail_description'
            )
            ->orderBy('idx')
            ->get();

        // select * from lecture_details where lecture_seq = 8 and lecture_detail_group = '6'
        $concept_building = \App\LectureDetail::where('lecture_seq', $lecture_seq)
            ->where('lecture_detail_group', $lecture_detail_seq)
            ->where('lecture_detail_type', 'concept_building')
            ->first();

        $interactive = null;
        $interactive_json= '';
        if($concept_building->interactive_seq){
            $interactive = \App\Interactive::find($concept_building->interactive_seq);
            if($interactive){
                $json_data = $interactive->json_data;
                $interactive_json = $json_data;
            }
        }
        $lecture_subject = \App\Lecture::where('id', $lecture_seq)->first();
        session()->put('_previous', ['url' => url()->current()]);
        return view('student.student_study_concept', [
            'st_lecture_detail_seq' => $st_lecture_detail_seq,
            'lecture_detail_info' => $st_seq_detail,
            'st_status_study' => $st_status_study,
            'top_menutabs' => $top_menutabs,
            'lecture_seq' => $lecture_seq,
            'lecture_detail_seq' => $lecture_detail_seq,
            'concept_building' => $concept_building,
            'interactive_json' => $interactive_json,
            'lecture_subject' => $lecture_subject,
            'prev_page' => $prev_page
        ]);
    }
    // 정리학습
    public function summary(Request $request)
    {
        $student_seq = session()->get('student_seq');
        $st_lecture_detail_seq = $request->input('st_lecture_detail_seq');
        $prev_page = $request->input('prev_page');

        // 학습 이름 가져오기.
        // 학습 내부 이름 가져오기.
        // like 유무 가져오기.
        $st_lecture_details = $this->getMainSql($student_seq);

        // 지정 학습 정보 가져오기. $st_lecture_detail_seq
        $st_seq_detail = (clone $st_lecture_details)
            ->where('student_lecture_details.id', $st_lecture_detail_seq)
            ->first();

        // 학생의 학습 중인 리스트 가져오기.
        $st_status_study = (clone $st_lecture_details)
            ->where('student_lecture_details.status', 'study')
            ->get();

        // 상단 탭 메뉴 가져오기.
        $lecture_seq = $st_seq_detail->lecture_seq;
        $lecture_detail_seq = $st_seq_detail->lecture_detail_seq;
        $top_menutabs = \App\LectureDetail::where('lecture_seq', $lecture_seq)
            ->where(function($query) use ($lecture_detail_seq){
                $query->where('id', $lecture_detail_seq)
                    ->orWhere('lecture_detail_group', $lecture_detail_seq);
            })
            ->select(
                'id',
                'lecture_detail_type',
                'idx',
                'lecture_exam_seq',
                'lecture_detail_description'
            )
            ->orderBy('idx')
            ->get();

        $lecture_detail_info = \App\LectureDetail::where('lecture_detail_group', $st_seq_detail->lecture_detail_seq)
        ->where('lecture_detail_type', 'summarizing')->first();
        $interactive = \App\Interactive::where('id', $lecture_detail_info->interactive_seq)->first();
        $interactive_json = $interactive->json_data;
        session()->put('_previous', ['url' => url()->current()]);
        return view('student.student_study_summary', [
            'st_lecture_detail_seq' => $st_lecture_detail_seq,
            'lecture_detail_info' => $st_seq_detail,
            'st_status_study' => $st_status_study,
            'top_menutabs' => $top_menutabs,
            'lecture_seq' => $lecture_seq,
            'lecture_detail_seq' => $lecture_detail_seq,
            'interactive_json' => $interactive_json,
            'prev_page' => $prev_page
        ]);
    }

    // 단원평가
    public function unitQuiz(Request $request)
    {
        $student_seq = session()->get('student_seq');
        $teach_seq = session()->get('teach_seq');
        $st_lecture_detail_seq = $request->input('st_lecture_detail_seq');
        $prev_page = $request->input('prev_page');
        $exam_seq = $request->input('exam_seq');
        $lecture_seq = $request->input('lecture_seq');
        $lecture_subject = \App\Lecture::where('id', $lecture_seq)->first();

        $login_type = session()->get('login_type');

        $seq_type = 'student_seq';
        $seq = $student_seq;
        if($login_type == 'teacher'){
            $seq_type = 'teach_seq';
            $seq = $teach_seq;
        }
        // 학습 이름 가져오기.
        // 학습 내부 이름 가져오기.
        // like 유무 가져오기.
        $st_lecture_details = $this->getMainSql($seq, $seq_type);

        // 지정 학습 정보 가져오기. $st_lecture_detail_seq
        $st_seq_detail = (clone $st_lecture_details)
            ->where('student_lecture_details.id', $st_lecture_detail_seq)
            ->first();

        // 학생의 학습 중인 리스트 가져오기.
        $st_status_study = (clone $st_lecture_details)
            ->where('student_lecture_details.status', 'study')
            ->get();

        // 상단 탭 메뉴 가져오기.
        if($st_seq_detail){
            $lecture_seq = $st_seq_detail->lecture_seq;
            $lecture_detail_seq = $st_seq_detail->lecture_detail_seq;
            $top_menutabs = \App\LectureDetail::where('lecture_seq', $lecture_seq)
                ->where(function($query) use ($lecture_detail_seq){
                    $query->where('id', $lecture_detail_seq)
                        ->orWhere('lecture_detail_group', $lecture_detail_seq);
                })
                ->select(
                    'id',
                    'lecture_detail_type',
                    'idx',
                    'lecture_exam_seq',
                    'lecture_detail_description'
                )
                ->orderBy('idx')
                ->get();
        }

        // 문제가져오기
        // 기본문제
        if($exam_seq){
            // 학습관리 없이 바로 시험을 치는 곳에서 왔을때.(추가요청)
            $top_menutabs = null;
            $lecture_seq = '';
            $lecture_detail_seq = '';
            $lecture_exam_seq = $exam_seq;
            $exam_lecdture_detail_seq = '';

        }
        else{
            // 정상적인 루트
            $lecture_exam_seq = $top_menutabs->where('lecture_detail_type', 'unit_test')->first()->lecture_exam_seq;
            $exam_lecdture_detail_seq = $top_menutabs->where('lecture_detail_type', 'unit_test')->first()->id;
        }

        $normals = \App\ExamDetail::where('exam_seq', $lecture_exam_seq)
            ->where('exam_type', 'normal')
            ->orderBy('exam_num')
            ->get()
            ->map(function($item) {
                $item->questions2 = str_replace('<br>', "\n", $item->questions2);
                return $item;
            });
        $similars = \App\ExamDetail::where('exam_seq', $lecture_exam_seq)
            ->where('exam_type', 'similar')
            ->orderBy('exam_num')
            ->get()
            ->map(function($item) {
                $item->questions2 = str_replace('<br>', "\n", $item->questions2);
                return $item;
            });
        $challenges = \App\ExamDetail::where('exam_seq', $lecture_exam_seq)
            ->where('exam_type', 'challenge')
            ->orderBy('exam_num')
            ->get()
            ->map(function($item) {
                $item->questions2 = str_replace('<br>', "\n", $item->questions2);
                return $item;
            });
        $challenge_similars = \App\ExamDetail::where('exam_seq', $lecture_exam_seq)
            ->where('exam_type', 'challenge_similar')
            ->orderBy('exam_num')
            ->get()
            ->map(function($item) {
                $item->questions2 = str_replace('<br>', "\n", $item->questions2);
                return $item;
            });

        // 이미지 및 동영상 파일 경로.
        $exam_uploadfiles = \App\ExamUploadfile::where('exam_seq', $lecture_exam_seq)
            ->get();

       // 학생이 답한 답 가져오기.
        $student_answers = \App\StudentExamResult::select('student_exam_results.*')
            ->leftJoin('student_exams', 'student_exams.id', '=', 'student_exam_results.student_exam_seq')
            ->where('student_exam_results.student_seq', $student_seq)
            ->where('student_exams.teach_seq', $teach_seq)
            ->where('student_exams.exam_seq', $lecture_exam_seq)
            ->where('student_exams.lecture_detail_seq', $exam_lecdture_detail_seq)
            ->where('student_exams.student_lecture_detail_seq', $st_lecture_detail_seq)
            ->orderBy('student_exam_results.exam_type')
            ->orderBy('student_exam_results.exam_num')
            ->get();

        session()->put('_previous', ['url' => url()->current()]);

        return view('student.student_study_unitQuiz', [
            'st_lecture_detail_seq' => $st_lecture_detail_seq,
            'lecture_detail_info' => $st_seq_detail,
            'st_status_study' => $st_status_study,
            'top_menutabs' => $top_menutabs,
            'lecture_seq' => $lecture_seq,
            'lecture_detail_seq' => $lecture_detail_seq,
            'normals' => $normals,
            'similars' => $similars,
            'challenges' => $challenges,
            'challenge_similars' => $challenge_similars,
            'exam_uploadfiles' => $exam_uploadfiles,
            'st_answers' => $student_answers,
            'exam_lecdture_detail_seq' => $exam_lecdture_detail_seq,
            'lecture_subject' => $lecture_subject,
            'prev_page' => $prev_page,
            'login_type' => $login_type
        ]);
    }

    // 채점표
    public function score(Request $request)
    {
        $student_seq = session()->get('student_seq');
        $st_lecture_detail_seq = $request->input('st_lecture_detail_seq');

        // 학습 이름 가져오기.
        // 학습 내부 이름 가져오기.
        // like 유무 가져오기.
        $st_lecture_details = $this->getMainSql($student_seq);

        // 지정 학습 정보 가져오기. $st_lecture_detail_seq
        $st_seq_detail = (clone $st_lecture_details)
            ->where('student_lecture_details.id', $st_lecture_detail_seq)
            ->first();

        // 학생의 학습 중인 리스트 가져오기.
        $st_status_study = (clone $st_lecture_details)
            ->where('student_lecture_details.status', 'study')
            ->get();

        // 상단 탭 메뉴 가져오기.
        $lecture_seq = $st_seq_detail->lecture_seq;
        $lecture_detail_seq = $st_seq_detail->lecture_detail_seq;
        $top_menutabs = \App\LectureDetail::where('lecture_seq', $lecture_seq)
            ->where(function($query) use ($lecture_detail_seq){
                $query->where('id', $lecture_detail_seq)
                    ->orWhere('lecture_detail_group', $lecture_detail_seq);
            })
            ->select(
                'lecture_detail_type',
                'idx'
            )
            ->orderBy('idx')
            ->get();

        return view('student.student_study_score', [
            'st_lecture_detail_seq' => $st_lecture_detail_seq,
            'lecture_detail_info' => $st_seq_detail,
            'st_status_study' => $st_status_study,
            'top_menutabs' => $top_menutabs,
            'lecture_seq' => $lecture_seq,
            'lecture_detail_seq' => $lecture_detail_seq
        ]);
    }

    // 분:초 를 초로 변경.
    private function timeToSecond($time){
        $time = explode(':', $time);
        $time = $time[0]*60 + $time[1];
        return $time;
    }
}
