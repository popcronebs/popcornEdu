<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SchoolStudyMtController extends Controller
{
    //
    public function list(Request $request){
        $student_seq = $request->session()->get('student_seq');
        $teach_seq = $request->session()->get('teach_seq');
        $login_type = $request->session()->get('login_type');

        if($login_type == 'student'){
            if ($request->input('student_seq') != null) {
                $student_seq = $request->input('student_seq');
            }
            $main_code = \App\Student::where('id', $student_seq)->value('main_code');

        }else if($login_type == 'teacher'){
            if ($request->input('student_seq') != null) {
                $student_seq = $request->input('student_seq');
            }
            $main_code = \App\Teacher::where('id', $teach_seq)->value('main_code');
        }
        // 학년
        $grade_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'grade')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        //과목
        $subject_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'subject')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        //학기
        $semester_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'semester')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();



        return view('student.student_school_study', [
            'grade_codes' => $grade_codes,
            'subject_codes' => $subject_codes,
            'semester_codes' => $semester_codes,
            'login_type' => $login_type,
        ]);
    }

    //  학교공부 - 학습 리스트 가져오기.
    public function select(Request $request){
        $grade = $request->get('grade');
        $semester = $request->get('semester');
        $subject = $request->get('subject');
        $student_seq = session()->get('student_seq');
        $teach_seq = $request->session()->get('teach_seq');
        $login_type = $request->session()->get('login_type');

        if($grade == '' && $login_type != 'teacher'){
            // $result['resultCode'] = 'fail';
            // return response()->json($result);
        }

        $teach_seqs = [];

        if($login_type == 'student'){
            // 가져오는 내용이 모두 변경.
            // 클래스의 선생님이 누구인가 가져오기.
            $classes = \App\ClassTb::select('teach_seq')->where('id', function($query) use ($student_seq){
                $query->select('class_seq')->from('class_mates')->where('student_seq', $student_seq);
            })->get();
            $teach_seqs = $classes->pluck('teach_seq')->toArray();
        }else if($login_type == 'teacher'){
            array_push($teach_seqs, $teach_seq);
        }

        // 필터 코드 가져오기.
        $grade_lecture_seqs = \App\LectureCode::where('code_category', 'grade')->where('code_seq', $grade)->select('lecture_seq')->get();
        $semester_lecture_seqs = \App\LectureCode::where('code_category', 'semester')->where('code_seq', $semester)->select('lecture_seq')->get();
        $subject_lecture_seqs = \App\LectureCode::where('code_category', 'subject')->where('code_seq', $subject)->select('lecture_seq')->get();

        // 학생이 듣는 클래스 선생님의 시리즈 권한을 가져와서
        // 학습 코드대입.
        $lecture_codes = \App\LectureCode::select('lecture_seq')->whereIn('code_seq', function($query) use ($teach_seqs){
            $query->select('code_seq')->from('teacher_lectures_permissions')->whereIn('teach_seq', $teach_seqs);
        })->groupBy('lecture_seq');

        if(count($grade_lecture_seqs) > 0){
           $lecture_codes = $lecture_codes->whereIn('lecture_seq', $grade_lecture_seqs);
        }
        if(count($semester_lecture_seqs) > 0){
            $lecture_codes = $lecture_codes->whereIn('lecture_seq', $semester_lecture_seqs);
        }
        if(count($subject_lecture_seqs) > 0){
            $lecture_codes = $lecture_codes->whereIn('lecture_seq', $subject_lecture_seqs);
        }
        $lecture_codes = $lecture_codes->get();

        $lecture_seqs = $lecture_codes->pluck('lecture_seq')->toArray();


        $st_lectures = \App\StudentLecture::
            select(
                'id as st_lecture_seq',
                // DB::raw('max(id) as st_lecture_seq'), // 이부분을 이용해서, 중복을 없앤다.
                'lecture_seq'
            )
            ->where('status','<>', 'delete')
            ->where('lecture_type', 'school');
            // ->groupBy('lecture_seq')
        if($login_type == 'student'){
            $st_lecture_seqs = $st_lectures->where('student_seq', $student_seq);
        }else if($login_type == 'teacher'){
            $st_lecture_seqs = $st_lectures->where('teach_seq', $teach_seq)->where('member_type', 'teacher');
        }
        $st_lectures=$st_lectures->get();

        $st_lecture_seqs = $st_lectures->pluck('st_lecture_seq')->toArray(); // 중복을 없애기위한 키값.

        // lectures 가져오기.
        $lectures = \App\Lecture::
        select(
                'lectures.*',
                'teachers.teach_name',
                'teachers.profile_img_path',
                'subject_codes.code_name as subject_name'
        )
        ->leftJoin('teachers' , 'lectures.teach_seq', '=', 'teachers.id')
        ->leftJoin('lecture_codes as subjects', function($join){
            $join->on('lectures.id', '=', 'subjects.lecture_seq')
            ->where('subjects.code_category', 'subject');
        })
        ->leftJoin('codes as subject_codes', 'subjects.code_seq', '=', 'subject_codes.id')
        ->whereIn('lectures.id', $lecture_seqs);

        // lecture_details 가져오기.
        $lecture_details = \App\LectureDetail::select(
            'lecture_details.*',
            'student_lecture_details.status',
            'student_lecture_details.is_like',
            'student_lecture_details.id as st_lecture_detail_seq',
            'student_lecture_details.sel_date'
        )
            ->leftJoin('student_lecture_details', function ($join) use ($student_seq, $st_lecture_seqs, $login_type, $teach_seq) {
                    $join->on('lecture_details.id', '=', 'student_lecture_details.lecture_detail_seq')
                        ->where('student_lecture_details.lecture_type', 'school');
                if($login_type == 'student'){
                    $join = $join->where('student_lecture_details.student_seq', '=', $student_seq);
                }else if($login_type == 'teacher'){
                    $join = $join->where('student_lecture_details.teach_seq', '=', $teach_seq);
                }
                if($st_lecture_seqs)
                    $join = $join->whereIn('student_lecture_details.student_lecture_seq', $st_lecture_seqs);
            })
            ->whereIn('lecture_seq', $lecture_seqs)
            ->where('lecture_detail_type', '')
            ->orderBy('lecture_seq', 'asc')
            ->orderBy('idx', 'asc')
            ->get()
            ->groupBy('lecture_seq');


        $result['resultCode'] = 'success';
        $result['lectures'] = $lectures->get();
        $result['lecture_details'] = $lecture_details;
        return response()->json($result);


        // 폐기--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        // 학생이 듣는 학습 내용의 키를 가져온다.
        // NOTE: 일단 학교공부라서 조건없이 모두 가져오는 것으로 진행.
        $st_lectures = \App\StudentLecture::
            select(
                DB::raw('max(id) as st_lecture_seq'), // 이부분을 이용해서, 중복을 없앤다.
                'lecture_seq'
            )
            ->where('student_seq', $student_seq)
            ->where('status','<>', 'delete')
            ->groupBy('lecture_seq')
            ->get();
        $lecture_seqs = $st_lectures->pluck('lecture_seq')->toArray();
        $st_lecture_seqs = $st_lectures->pluck('st_lecture_seq')->toArray(); // 중복을 없애기위한 키값.

        $grade_lecture_seqs = \App\LectureCode::where('code_category', 'grade')->where('code_seq', $grade)->select('lecture_seq')->get();
        $semester_lecture_seqs = \App\LectureCode::where('code_category', 'semester')->where('code_seq', $semester)->select('lecture_seq')->get();
        $subject_lecture_seqs = \App\LectureCode::where('code_category', 'subject')->where('code_seq', $subject)->select('lecture_seq')->get();

        $lectures = \App\Lecture::
        select(
                'lectures.*',
                'teachers.teach_name',
                'teachers.profile_img_path',
                'subject_codes.code_name as subject_name'
        )
        ->leftJoin('teachers' , 'lectures.teach_seq', '=', 'teachers.id')
        ->leftJoin('lecture_codes as subjects', function($join){
            $join->on('lectures.id', '=', 'subjects.lecture_seq')
            ->where('subjects.code_category', 'subject');
        })
        ->leftJoin('codes as subject_codes', 'subjects.code_seq', '=', 'subject_codes.id')
        ->whereIn('lectures.id', $lecture_seqs);

        if(count($grade_lecture_seqs) > 0){
           $lectures = $lectures->whereIn('lectures.id', $grade_lecture_seqs);
        }
        if(count($semester_lecture_seqs) > 0){
            $lectures = $lectures->whereIn('lectures.id', $semester_lecture_seqs);
        }
        if(count($subject_lecture_seqs) > 0){
            $lectures = $lectures->whereIn('lectures.id', $subject_lecture_seqs);
        }

        //라라벨로 키만 가져와서 배열로 만든다.
        $last_lecture_seqs =  $lectures->pluck('id')->toArray();


        $lecture_details = \App\LectureDetail::select(
            'lecture_details.*',
            'student_lecture_details.status',
            'student_lecture_details.is_like',
            'student_lecture_details.id as st_lecture_detail_seq',
            'student_lecture_details.sel_date'
        )
            ->join('student_lecture_details', function ($join) use ($student_seq, $st_lecture_seqs) {
                $join->on('lecture_details.id', '=', 'student_lecture_details.lecture_detail_seq')
                    ->where('student_lecture_details.student_seq', '=', $student_seq)
                    ->whereIn('student_lecture_details.student_lecture_seq', $st_lecture_seqs);
            })
            ->whereIn('lecture_seq', $last_lecture_seqs)
            ->orderBy('lecture_seq', 'asc')
            ->orderBy('idx', 'asc')
            ->get()
            ->groupBy('lecture_seq');

        // 결과
        $result['resultCode'] = 'success';
        // $result['sql'] = $lectures->toSql();
        // $result['bainding'] = $lectures->getBindings();
        $result['st_lecture_seqs'] = $st_lecture_seqs;
        $result['lectures'] = $lectures->get();
        $result['lecture_details'] = $lecture_details;
        return response()->json($result);
    }

    // 학교공부 > 학습하기 > insert
    function insert(Request $request){
        $lecture_seq = $request->input('lecture_seq');
        $lecture_detail_seq = $request->input('lecture_detail_seq');
        $student_seq = session()->get('student_seq');
        $teach_seq = $request->session()->get('teach_seq');
        $login_type = $request->session()->get('login_type');

        // 트랜잭션 시작
        $is_transaction_suc = true;
        DB::beginTransaction();
        try {

            $student_lecture_seq = '';
            //student_lecture 없으면 생성.
            $st_lecture = \App\StudentLecture::
                where('lecture_seq', $lecture_seq)
                ->where('status','<>', 'delete')
                ->where('lecture_type', 'school');
                if($login_type == 'student'){
                    $st_lecture=$st_lecture->where('student_seq', $student_seq);
                }else if($login_type == 'teacher'){
                    $st_lecture=$st_lecture->where('teach_seq', $teach_seq)->where('member_type', 'teacher');
                }
                $st_lecture=$st_lecture->first();

            if(!$st_lecture){
                $st_lecture = new \App\StudentLecture;
                $st_lecture->lecture_seq = $lecture_seq;
                $st_lecture->start_date = date('Y-m-d');
                $st_lecture->end_date = date('Y-m-d');
                $st_lecture->lecture_type = 'school';
                $st_lecture->view_count = 0;
                $st_lecture->is_sun = 'N';
                $st_lecture->is_mon = 'N';
                $st_lecture->is_tue = 'N';
                $st_lecture->is_wed = 'N';
                $st_lecture->is_thu = 'N';
                $st_lecture->is_fri = 'N';
                $st_lecture->is_sat = 'N';
                $st_lecture->status = 'study';
                if($login_type == 'student'){
                    $st_lecture->student_seq = $student_seq;
                }else if($login_type == 'teacher'){
                    $st_lecture->teach_seq = $teach_seq;
                    $st_lecture->member_type = 'teacher';
                }
                $st_lecture->save();
            }
            $student_lecture_seq = $st_lecture->id;

            //student_lecture_details 생성.
            $st_lectue_detail = \App\StudentLectureDetail::
                where('student_lecture_seq', $student_lecture_seq)
                ->where('status','<>', 'delete')
                ->where('lecture_type', 'school')
                ->where('lecture_detail_seq', $lecture_detail_seq)->first();
            if(!$st_lectue_detail){
                $st_lectue_detail = new \App\StudentLectureDetail;
                $st_lectue_detail->student_lecture_seq = $student_lecture_seq;
                $st_lectue_detail->lecture_detail_seq = $lecture_detail_seq;
                $st_lectue_detail->lecture_type = 'school';
                $st_lectue_detail->sel_date = date('Y-m-d');
                $days = ['일', '월', '화', '수', '목', '금', '토'];
                $st_lectue_detail->sel_day = $days[date('w')];
                $st_lectue_detail->status = 'study';
                if($login_type == 'student'){
                    $st_lectue_detail->student_seq = $student_seq;
                }else if($login_type == 'teacher'){
                    $st_lectue_detail->teach_seq = $teach_seq;
                }
                $st_lectue_detail->save();
            }
            $student_lecture_detail_seq = $st_lectue_detail->id;

            $result['resultCode'] = 'success';
            $result['student_lecture_seq'] = $student_lecture_seq;
            $result['student_lecture_detail_seq'] = $student_lecture_detail_seq;
            DB::commit();
        } catch (\Exception $e) {
            $is_transaction_succ = false;
            DB::rollback();
            throw $e;
        }

        return response()->json($result);
    }
}
