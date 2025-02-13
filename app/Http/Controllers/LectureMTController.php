<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use PDO;

class LectureMTController extends Controller
{
    // 강좌(학습영상) 관리/목록 VIEW
    public function list(){
        //과목 분류
        $subject_codes = \App\Code::where('code_category', 'subject')->where('code_step', '=', 1)->get();
        //시리즈 분류
        $series_codes = \App\Code::where('code_category', 'series')->where('code_step', '=', 1)->get();

        return view('admin.admin_lecture_list',
            [
                'subject_codes'=>$subject_codes,
                'series_codes'=>$series_codes
            ]);
    }

    // 강좌(학습영상) 등록 VIEW
    public function add(Request $request){
        $main_code = $_COOKIE['main_code'];
        $lecture_seq = $request->input('lecture_seq');
        $lectures = null;
        $lecture_codes = null;
        $lecture_uploadfiles = null;
        $lecture_details = null;
        if(isset($lecture_seq)){
            // lectures id = lecture_seq 테이블 가져오기
            // lecture_codes에서 lecture_seq에 테이블 가져오기.
            // lecture_uploadfiles 에서 lecture_seq에 해당하는 파일 가져오기.
            // lecture_details 에서 lecture_seq에 해당하는 테이블 가져오기.

            $lectures = \App\Lecture::where('id', $lecture_seq)->first();
            $lecture_codes = \App\LectureCode::where('lecture_seq', $lecture_seq)->get();
            $lecture_uploadfiles = \App\LectureUploadfile::where('lecture_seq', $lecture_seq)->get();
            $lecture_details =
            \App\LectureDetail::where('lecture_details.lecture_seq', $lecture_seq)
                ->leftJoin('exams', 'lecture_details.lecture_exam_seq', '=', 'exams.id')
                ->leftJoin('lecture_uploadfiles', 'lecture_details.id', '=', 'lecture_uploadfiles.lecture_detail_seq')
                ->leftJoin('interactives', 'lecture_details.interactive_seq', '=', 'interactives.id')
                ->select(
                    'lecture_details.*',
                    'lecture_uploadfiles.file_path as file_path',
                    'exams.exam_title',
                    'interactives.title as interactive_title'
                )
                ->orderBy('idx', 'asc')->get();
        }

        $codes_all = \App\Code::where('main_code', $main_code)->get();
        //학년 분류
        $grade_codes = $codes_all->where('code_category', 'grade')->where('code_step', '=', 1);
        //학기
        $semester_codes = $codes_all->where('code_category', 'semester')->where('code_step', '=', 1);
        //강좌구분 분류
        $course_codes = $codes_all->where('code_category', 'course')->where('code_step', '=', 1);
        //과목 분류
        $subject_codes = $codes_all->where('code_category', 'subject')->where('code_step', '=', 1);
        //시리즈 분류
        $series_codes = $codes_all->where('code_category', 'series')->where('code_step', '!=', 0);
        //수준 분류
        $level_codes = $codes_all->where('code_category', 'level')->where('code_step', '!=', 0);
        //출판사 분류
        $publisher_codes = $codes_all->where('code_category', 'publisher')->where('code_step', '=', 1);
        return view('admin.admin_lecture_add',
        [
            'codes_all'=>$codes_all,
            'grade_codes'=>$grade_codes,
            'semester_codes'=>$semester_codes,
            'course_codes'=>$course_codes,
            'subject_codes'=>$subject_codes,
            'series_codes'=>$series_codes,
            'level_codes'=>$level_codes,
            'lectures'=>$lectures,
            'lecture_codes'=>$lecture_codes,
            'lecture_uploadfiles'=>$lecture_uploadfiles,
            'lecture_details'=>$lecture_details,
            'publisher_codes'=>$publisher_codes
        ]);
    }

    public function list_v2(){
        //과목 분류
        $subject_codes = \App\Code::where('code_category', 'subject')->where('code_step', '=', 1)->get();
        //시리즈 분류
        $series_codes = \App\Code::where('code_category', 'series')->where('code_step', '=', 1)->get();
        return view('admin.admin_lecture_list_v2',
            [
                'subject_codes'=>$subject_codes,
                'series_codes'=>$series_codes
            ]);
    }

    public function add_v2(Request $request){
        $main_code = $_COOKIE['main_code'];
        $lecture_seq = $request->input('lecture_seq');
        $lectures = null;
        $lecture_codes = null;
        $lecture_uploadfiles = null;
        $lecture_details = null;
        if(isset($lecture_seq)){
            // lectures id = lecture_seq 테이블 가져오기
            // lecture_codes에서 lecture_seq에 테이블 가져오기.
            // lecture_uploadfiles 에서 lecture_seq에 해당하는 파일 가져오기.
            // lecture_details 에서 lecture_seq에 해당하는 테이블 가져오기.

            $lectures = \App\Lecture::where('id', $lecture_seq)->first();
            $lecture_codes = \App\LectureCode::where('lecture_seq', $lecture_seq)->get();
            $lecture_uploadfiles = \App\LectureUploadfile::where('lecture_seq', $lecture_seq)->get();
            $lecture_details =
            \App\LectureDetail::where('lecture_details.lecture_seq', $lecture_seq)
                ->leftJoin('exams', 'lecture_details.lecture_exam_seq', '=', 'exams.id')
                ->leftJoin('lecture_uploadfiles', 'lecture_details.id', '=', 'lecture_uploadfiles.lecture_detail_seq')
                ->leftJoin('interactives', 'lecture_details.interactive_seq', '=', 'interactives.id')
                ->select(
                    'lecture_details.*',
                    'lecture_uploadfiles.file_path as file_path',
                    'exams.exam_title',
                    'interactives.title as interactive_title'
                )
                ->orderBy('idx', 'asc')->get();
        }

        $codes_all = \App\Code::where('main_code', $main_code)->get();
        //학년 분류
        $grade_codes = $codes_all->where('code_category', 'grade')->where('code_step', '=', 1);
        //학기
        $semester_codes = $codes_all->where('code_category', 'semester')->where('code_step', '=', 1);
        //강좌구분 분류
        $course_codes = $codes_all->where('code_category', 'course')->where('code_step', '=', 1);
        //과목 분류
        $subject_codes = $codes_all->where('code_category', 'subject')->where('code_step', '=', 1);
        //시리즈 분류
        $series_codes = $codes_all->where('code_category', 'series')->where('code_step', '!=', 0);
        //수준 분류
        $level_codes = $codes_all->where('code_category', 'level')->where('code_step', '!=', 0);
        //출판사 분류
        $publisher_codes = $codes_all->where('code_category', 'publisher')->where('code_step', '=', 1);
        return view('admin.admin_lecture_add_v2',
        [
            'codes_all'=>$codes_all,
            'grade_codes'=>$grade_codes,
            'semester_codes'=>$semester_codes,
            'course_codes'=>$course_codes,
            'subject_codes'=>$subject_codes,
            'series_codes'=>$series_codes,
            'level_codes'=>$level_codes,
            'lectures'=>$lectures,
            'lecture_codes'=>$lecture_codes,
            'lecture_uploadfiles'=>$lecture_uploadfiles,
            'lecture_details'=>$lecture_details,
            'publisher_codes'=>$publisher_codes
        ]);
    }

    // 강좌 리스트 SELECT
    public function lectureSelect(Request $request){
        $main_code = $_COOKIE['main_code'];
        $lecture_seq = $request->input('lecture_seq');
        $search_str = $request->input('search_str');
        $subject_seq = $request->input('subject_seq');
        $serise_seq = $request->input('serise_seq');
        $course_seq = $request->input('course_seq');
        $publisher_seq = $request->input('publisher_seq');

        // select *from lecture_codes
        $lectures = \App\Lecture::
        select(
            'lectures.id',
            'lectures.lecture_name',
            'lectures.teacher_name',
            'lectures.book_name',
            'lectures.book_link',
            'lectures.lecture_detail_count',
            'lectures.course_date_count',
            'lectures.is_use',
            
            // group_concat 의 중복 제거
            DB::raw('group_concat(DISTINCT course_codes.code_name) as course_names'),
            DB::raw('group_concat(DISTINCT grade_codes.code_name) as grade_names'),
            DB::raw('group_concat(DISTINCT semester_codes.code_name) as semester_names'),
            DB::raw('group_concat(DISTINCT subject_codes.code_name) as subject_names'),
            DB::raw('min(subject_codes.function_code) as subject_function_code'),
            DB::raw('group_concat(DISTINCT series_codes.code_name) as series_names'),
            DB::raw('group_concat(DISTINCT series_sub_codes.code_name) as series_sub_names'),
            DB::raw('group_concat(DISTINCT level_codes.code_name) as level_names'),
            DB::raw('group_concat(DISTINCT publisher_codes.code_name) as publisher_names'),
            'lecture_uploadfiles.file_path as thumbnail_file_path'
        )
        ->leftJoin('lecture_codes as courses', function($join){
            $join->on('lectures.id', '=', 'courses.lecture_seq')
            ->where('courses.code_category', 'course');
        })
        ->leftJoin('codes as course_codes', 'courses.code_seq', '=', 'course_codes.id')

        ->leftJoin('lecture_codes as grades', function($join){
            $join->on('lectures.id', '=', 'grades.lecture_seq')
            ->where('grades.code_category','grade');
        })
        ->leftJoin('codes as grade_codes', 'grades.code_seq', '=', 'grade_codes.id')

        ->leftJoin('lecture_codes as semesters', function($join){
            $join->on('lectures.id', '=', 'semesters.lecture_seq')
            ->where('semesters.code_category', 'semester');
        })
        ->leftJoin('codes as semester_codes', 'semesters.code_seq', '=', 'semester_codes.id')

        ->leftJoin('lecture_codes as subjects', function($join){
            $join->on('lectures.id', '=', 'subjects.lecture_seq')
            ->where('subjects.code_category', 'subject');
        })
        ->leftJoin('codes as subject_codes', 'subjects.code_seq', '=', 'subject_codes.id')

        ->leftJoin('lecture_codes as series', function($join){
            $join->on('lectures.id', '=', 'series.lecture_seq')
            ->where('series.code_category', 'series');
        })
        ->leftJoin('codes as series_codes', 'series.code_seq', '=', 'series_codes.id')

        ->leftJoin('lecture_codes as series_subs', function($join){
            $join->on('lectures.id', '=', 'series_subs.lecture_seq')
            ->where('series_subs.code_category', 'series_sub');
        })
        ->leftJoin('codes as series_sub_codes', 'series_subs.code_seq', '=', 'series_sub_codes.id')

        ->leftJoin('lecture_codes as levels', function($join){
            $join->on('lectures.id', '=', 'levels.lecture_seq')
            ->where('levels.code_category', 'level');
        })
        ->leftJoin('codes as level_codes', 'levels.code_seq', '=', 'level_codes.id')

        ->leftJoin('lecture_codes as publishers', function($join){
            $join->on('lectures.id', '=', 'publishers.lecture_seq')
            ->where('publishers.code_category', 'publisher');
        })
        ->leftJoin('codes as publisher_codes', 'publishers.code_seq', '=', 'publisher_codes.id')

        ->leftJoin('lecture_uploadfiles', 'lectures.id', '=', 'lecture_uploadfiles.lecture_seq')

        //강좌 명
        ->groupBy(
                'lectures.id',
                'lectures.lecture_name',
                'lectures.teacher_name',
                'lectures.book_name',
                'lectures.book_link',
                'lectures.lecture_detail_count',
                'lectures.course_date_count',
                'lectures.is_use',
                'lecture_uploadfiles.file_path'
            );

        //조건 검색
        if(isset($lecture_seq)){
            $lectures = $lectures->where('lectures.id', $lecture_seq);
        }
        if(isset($search_str)){
            $lectures = $lectures->where('lectures.lecture_name', 'like', '%'.$search_str.'%');
        }
        if(isset($subject_seq)){
            $lectures = $lectures->where('subject_codes.id', $subject_seq);
        }
        if(isset($serise_seq)){
            $lectures = $lectures->where('series_codes.id', $serise_seq);
        }
        if(isset($course_seq)){
            $lectures = $lectures->where('course_codes.id', $course_seq);
        }
        if(isset($publisher_seq)){
            $lectures = $lectures->where('publisher_codes.id', $publisher_seq);
        }

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        //sql
        // $result['sql'] = $lectures->toSql();
        $result['lectures'] = $lectures->get();
        return response()->json($result);
    }

    // 강좌 등록에 사용되는 CDOE_CONNECT 가져오기
    public function codeConnectSelect(Request $request){
        $main_code = $_COOKIE['main_code'];
        $grade_code_seqs = $request->input('grade_code_seqs');
        $subject_code_seqs = $request->input('subject_code_seqs');

        // 배열 변경
        $grade_code_seqs = explode(',', $grade_code_seqs);
        $subject_code_seqs = explode(',', $subject_code_seqs);

        //두 배열중 하나라도 count 0이면 is_pass = true
        if(count($grade_code_seqs) == 0 || count($subject_code_seqs) == 0){
            $result = array();
            $result['resultCode'] = 'success';
            $result['code_connects'] = array();
            return response()->json($result);
        }


        //합치기
        // $grade_code_seqs = array_merge($grade_code_seqs, $subject_code_seqs);

        // sql
        $code_cont1 = \App\CodeConnect::
            select('code_seq')
            ->where('main_code', $main_code)
            ->whereIn('code_pt', $subject_code_seqs)
            ->groupBy('code_seq')->get();
        //code_seq로 배열 만들기
        $code_seqs = array();
        foreach($code_cont1 as $code_cont){
            array_push($code_seqs, $code_cont->code_seq);
        }

        $code_connects = \App\CodeConnect::
            select('code_seq')
            ->where('main_code', $main_code)
            ->whereIn('code_pt', $grade_code_seqs)
            ->whereIn('code_seq', $code_seqs)
            ->groupBy('code_seq')->get();

        // $code_connects = $code_connects->whereIn('code_pt', $grade_code_seqs);

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        $result['code_connects'] = $code_connects;
        return response()->json($result);
    }

    // 강좌 등록에서 시리즈, 시리즈 하위 등록.
    public function codeInsert(Request $request){
        $main_code = $_COOKIE['main_code'];
        $code_category = $request->input('code_category');
        $code_name = $request->input('code_name');
        $code_step = $request->input('code_step');
        $grade_code_seqs = $request->input('grade_code_seqs');
        $subject_code_seq = $request->input('subject_code_seq');
        $series_code_seq = $request->input('series_code_seq');

        //배열
        $grade_code_seqs = explode(',', $grade_code_seqs);

        //분류(codes) 에 정보 INSERT
        // 트랝잭션 시작
        //$code_seq 를 밖으로 빼서 사용할 수 있게 함.
        $code_seq = "";
        DB::transaction(function() use(&$code_seq, $main_code, $code_category, $code_name, $code_step, $grade_code_seqs, $subject_code_seq, $series_code_seq){
            //code_step 1 일때 0 시리즈 seq(1의 code_pt) 가져오기
            $code_pt = "";
            if($code_step == 1){
                $codes_code_pt = \App\Code::
                    select('id')
                    ->where('code_category', 'series')
                    ->where('code_step', 0)
                    ->first();
                $code_pt = $codes_code_pt->id;
            }
            $codes = new \App\Code;
            $codes->main_code = $main_code;
            $codes->code_category = $code_category;
            $codes->code_name = $code_name;
            $codes->code_step = $code_step;
            $codes->is_use = 'Y';

            if($code_step == 1){
                $codes->code_pt = $code_pt;
            }
            else if($code_step == 2){
                $codes->code_pt = $series_code_seq;
            }
            $codes->save();

            $code_seq = $codes->id;

            // code_connects 에 정보 INSERT
            foreach($grade_code_seqs as $grade_code_seq){
                $code_connects = new \App\CodeConnect;
                $code_connects->main_code = $main_code;
                $code_connects->code_seq = $code_seq;
                $code_connects->code_pt = $grade_code_seq;
                $code_connects->save();
            }
            // foreach($subject_code_seq as $subject_code_seq){
                $code_connects = new \App\CodeConnect;
                $code_connects->main_code = $main_code;
                $code_connects->code_seq = $code_seq;
                $code_connects->code_pt = $subject_code_seq;
                $code_connects->save();
            // }
        });

        // 결과
        $result = array();
        $result['code_seq'] = $code_seq;
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 강좌 등록
    public function lectureInsert(Request $request){
        $result = array();
        $result['resultCode'] = 'fail';
        //트랜잭션 시작
        DB::transaction(function() use($request, &$result){
        $main_code = $_COOKIE['main_code'];
        $lecture_seq = $request->input('lecture_seq');
        $course_seqs = $request->input('course_seqs');
        $grade_seqs = $request->input('grade_seqs');
        $semester_seqs = $request->input('semester_seqs');
        $subject_seqs = $request->input('subject_seqs');
        $series_seqs = $request->input('series_seqs');
        $level_seqs = $request->input('level_seqs');
        $series_sub_seqs = $request->input('series_sub_seqs');
        $publisher_seqs = $request->input('publisher_seqs');
        $teacher_name = $request->input('teacher_name');
        $course_date_count = $request->input('course_date_count');
        $book_name = $request->input('book_name');
        $book_link = $request->input('book_link');
        $is_use = $request->input('is_use');
        $thumbnail_file = $request->file('thumbnail_file');
        $lecture_name = $request->input('lecture_name');
        $lecture_description = $request->input('lecture_description');
        $lecture_details = $request->input('lecture_details');
        $lecture_detail_count = 0;

        //배열 변경
        $course_seqs = explode(',', $course_seqs);
        $grade_seqs = explode(',', $grade_seqs);
        $semester_seqs = explode(',', $semester_seqs);
        $subject_seqs = explode(',', $subject_seqs);
        $series_seqs = explode(',', $series_seqs);
        $series_sub_seqs = explode(',', $series_sub_seqs);
        $level_seqs = explode(',', $level_seqs);
        $publisher_seqs = explode(',', $publisher_seqs);

        //JSON.stringify 변경 lecture_details
        $lecture_details = json_decode($lecture_details, true);

        //강좌 정보 INSERT
        if(strlen($lecture_seq) > 0){ $lectures = \App\Lecture::where('id', $lecture_seq)->first(); }
        else{ $lectures = new \App\Lecture; }

        $lectures->main_code = $main_code;
        $lectures->teacher_name = $teacher_name;
        $lectures->book_name = $book_name;
        $lectures->book_link = $book_link;
        $lectures->is_use = $is_use;
        $lectures->lecture_name = $lecture_name;
        $lectures->lecture_description = $lecture_description;
        $lectures->course_date_count = $course_date_count;
        $lectures->save();
        $lecture_seq = $lectures->id;
        $result['lecture_seq'] = $lecture_seq;

        //섬네일 파일 업로드 트렌젝션 밖에서 진행.
        // $lecture_details_files = $request->file('lecture_details_files');

        //강좌의 분류 정보 INSERT, UPDATE
        $lecture_codes = \App\LectureCode::where('lecture_seq', $lecture_seq)->get();

        //del 후 insert
        $course_codes = $lecture_codes->where('code_category', 'course');
        $grade_codes = $lecture_codes->where('code_category', 'grade');
        $semester_codes = $lecture_codes->where('code_category', 'semester');
        $subject_codes = $lecture_codes->where('code_category', 'subject');
        $series_codes = $lecture_codes->where('code_category', 'series');
        $series_sub_codes = $lecture_codes->where('code_category', 'series_sub');
        $level_codes = $lecture_codes->where('code_category', 'level');
        $publisher_codes = $lecture_codes->where('code_category', 'publisher');

        $this->LectureCodeInsert($lecture_seq, $course_codes, $course_seqs, 'course');
        $this->LectureCodeInsert($lecture_seq, $grade_codes, $grade_seqs, 'grade');
        $this->LectureCodeInsert($lecture_seq, $semester_codes, $semester_seqs, 'semester');
        $this->LectureCodeInsert($lecture_seq, $subject_codes, $subject_seqs, 'subject');
        $this->LectureCodeInsert($lecture_seq, $series_codes, $series_seqs, 'series');
        $this->LectureCodeInsert($lecture_seq, $series_sub_codes, $series_sub_seqs, 'series_sub');
        $this->LectureCodeInsert($lecture_seq, $level_codes, $level_seqs, 'level');
        $this->LectureCodeInsert($lecture_seq, $publisher_codes, $publisher_seqs, 'publisher');

        // 강좌 상세정보 (강의) INSERT
        if(count($lecture_details) > 0){
            $all_count_day = 0;
            $insert_seqs = array();
            foreach($lecture_details as $lecture_detail){
                // lecture_detail_seq 있으면 UPDATE 없으면 INSERT
                $lecture_detail_seq = $lecture_detail['lecture_detail_seq'];
                if(strlen($lecture_detail_seq) > 0){ $lecture_details_seq = \App\LectureDetail::where('id', $lecture_detail_seq)->first(); }
                else{ $lecture_details_seq = new \App\LectureDetail; }

                $lecture_details_seq->main_code = $main_code;
                $lecture_details_seq->lecture_seq = $lecture_seq;
                $lecture_details_seq->lecture_detail_name = $lecture_detail['lecture_detail_name'];
                $lecture_details_seq->lecture_detail_description = $lecture_detail['lecture_detail_description'];
                $lecture_details_seq->lecture_detail_time = $this->timeToSecond($lecture_detail['lecture_detail_time'])."";
                $lecture_details_seq->is_use = $lecture_detail['is_use'];
                $lecture_details_seq->idx = $lecture_detail['idx'];
                $lecture_details_seq->lecture_detail_link = $lecture_detail['lecture_detail_link'];
                $lecture_details_seq->lecture_detail_count_day = 1;//$lecture_detail['lecture_detail_count_day']??1;
                $lecture_details_seq->lecture_detail_type = $lecture_detail['lecture_detail_type'];
                $lecture_details_seq->lecture_detail_group = !empty($lecture_detail['lecture_detail_group']) ? $lecture_detail['lecture_detail_group']:'0';
                $lecture_details_seq->is_first_interactive = $lecture_detail['is_first_interactive'];

                if(strlen($lecture_detail['lecture_detail_interactive_seq']) > 0){
                    $lecture_details_seq->interactive_seq = $lecture_detail['lecture_detail_interactive_seq'];
                }
                else{
                    $lecture_details_seq->interactive_seq = null;
                }

                if(strlen($lecture_detail['lecture_detail_exam_seq']) > 0){
                    $lecture_details_seq->lecture_exam_seq = $lecture_detail['lecture_detail_exam_seq'];
                }else{
                    $lecture_details_seq->lecture_exam_seq = null;
                }
                if(strlen($lecture_detail['lecture_detail_type']) < 1){
                    $lecture_detail_count++;
                }
                $all_count_day += 1;
                // $all_count_day += $lecture_detail['lecture_detail_count_day']??1;
                $lecture_details_seq->save();
                array_push($insert_seqs, $lecture_details_seq->id);
            }
            // insert_seqs 를 제외한 나머지 삭제.
            \App\LectureDetail::where('lecture_seq', $lecture_seq)->whereNotIn('id', $insert_seqs)->delete();

            $lectures->lecture_detail_count = $lecture_detail_count; //count($lecture_details);
            $lectures->lecture_detail_count_all_day = $all_count_day;
            $lectures->save();
        }

        // 결과
        $result['resultCode'] = 'success';
        });

        $main_code = $_COOKIE['main_code'];
        $thumbnail_file = $request->file('thumbnail_file');

        // 섬네일 파일 업로드
        if($result['resultCode'] == 'success'){
            $lecture_seq = $result['lecture_seq'];
            if($thumbnail_file != null){
                $originalName = $thumbnail_file->getClientOriginalName();
                $fileName = pathinfo($originalName, PATHINFO_FILENAME);
                $extension = $thumbnail_file->getClientOriginalExtension();
                $originalName = $fileName . '_' . time() . '.' . $extension;
                $originalName = str_replace('.php', '', $originalName);
                $originalName = str_replace(' ', '_', $originalName);
                $thumbnail_file->storeAs('public/uploads/lecture_files', $originalName);

                // Lecture_uploadfile에 lecture_seq가 있으면 UPDATE 없으면 INSERT
                $lecture_uploadfile = \App\LectureUploadfile::where('lecture_seq', $lecture_seq)->first();
                if($lecture_uploadfile == null){ $lecture_uploadfile = new \App\LectureUploadfile; }
                else{
                    //기존 파일 삭제
                    $file_path = $lecture_uploadfile->file_path;
                    File::delete(storage_path('app/public/'.$file_path));
                }

                $lecture_uploadfile->lecture_seq = $lecture_seq;
                $lecture_uploadfile->main_code = $main_code;
                $lecture_uploadfile->file_path = "uploads/lecture_files/".$originalName;
                $lecture_uploadfile->file_type = "thumbnail";
                $lecture_uploadfile->save();
            }

            $lecture_details_files = $request->file('lecture_details_files');
            // NOTE: 만약 업로드 없이, 링크만 바꿔서 저장할 경우 추후, 영상 삭제에 관련된 기능 이 필요.
            if(isset($lecture_details_files) && count($lecture_details_files) > 0){
                $lecture_details = \App\LectureDetail::where('lecture_seq', $lecture_seq)->orderBy('idx', 'asc')->get();

                foreach ($lecture_details_files as $key => $dfile) {
                    $result['key'.$key] = $key;
                    if($dfile != null){
                        $result['key'] = $key;
                        $originalName = $dfile->getClientOriginalName();
                        $fileName = pathinfo($originalName, PATHINFO_FILENAME);
                        $extension = $dfile->getClientOriginalExtension();
                        $originalName = $fileName . '_' . time() . '.' . $extension;
                        $originalName = str_replace('.php', '', $originalName);
                        $originalName = str_replace(' ', '_', $originalName);
                        $dfile->storeAs('public/uploads/lecture_files', $originalName);

                        $lecture_detail_seq = $lecture_details[$key]->id;
                        // Lecture_uploadfile에 lecture_detail_seq가 있으면 UPDATE 없으면 INSERT
                        $lecture_uploadfile = \App\LectureUploadfile::where('lecture_detail_seq', $lecture_detail_seq)->first();
                        if($lecture_uploadfile == null){ $lecture_uploadfile = new \App\LectureUploadfile; }
                        else{
                            //기존 파일 삭제
                            $file_path = $lecture_uploadfile->file_path;
                            File::delete(storage_path('app/public/'.$file_path));
                        }

                        $lecture_uploadfile->lecture_detail_seq = $lecture_detail_seq;
                        $lecture_uploadfile->main_code = $main_code;
                        $lecture_uploadfile->file_path = "uploads/lecture_files/".$originalName;
                        $lecture_uploadfile->file_type = "detail_file:".$extension;
                        $lecture_uploadfile->save();

                        $lecture_details[$key]->lecture_detail_link = "/storage/uploads/lecture_files/".$originalName;
                        $lecture_details[$key]->save();
                    }
                }
            }
        }
        return response()->json($result);
    }

    public function LectureExamInsert(Request $request){
        $main_code = session()->get('main_code') ?? $_COOKIE['main_code'];
        $teach_seq = session()->get('teach_seq');
        $data = $request->only([
            'lecture_seq',
            'lecture_detail_seq', 
            'exam_title',
            'evaluation_seq',
            'subject_seq',
            'grade_seq',
            'semester_seq'
        ]);
        // 이미 존재하는지 확인
        foreach($data as $key => $value){
            $existingExam = \App\Exam::where([
                'main_code' => $main_code,
                'exam_title' => $value['exam_title'],
                'subject_seq' => $value['subject_seq'],
                'grade_seq' => $value['grade_seq'],
                'semester_seq' => $value['semester_seq']
            ])->first();

            if (!$existingExam) {
                $exam = \App\Exam::create([
                    'main_code' => $main_code,
                    'exam_title' => $value['exam_title'], 
                    'exam_status' => 'Y',
                    'subject_seq' => $value['subject_seq'],
                    'grade_seq' => $value['grade_seq'],
                    'semester_seq' => $value['semester_seq'],
                    'evaluation_seq' => $value['evaluation_seq'],
                    'created_id' => $teach_seq
                ]);
                $exam->save();
            }
        }
    }




    // 강좌 분류를 테이블에 넣기 위한 함수.
    private function LectureCodeInsert($lecture_seq, $db_codes, $insert_seqs, $code_category){
        //course_codes의 모든 code_seq를 delete후 추가.
        if($db_codes != null){
            foreach($db_codes as $db_code){
                $db_code->delete();
            }
        }
        // 강좌 분류 INSERT
        foreach($insert_seqs as $insert_seq){
            $lecture_codes = new \App\LectureCode;
            $lecture_codes->lecture_seq = $lecture_seq;
            $lecture_codes->code_category = $code_category;
            $lecture_codes->code_seq = $insert_seq;
            $lecture_codes->save();
        }
    }

    // 강좌의 썸네일 삭제기능.
    public function thumbnailDelete(Request $request){
        $lecture_seq = $request->input('lecture_seq');
        $lecture_uploadfile = \App\LectureUploadfile::where('lecture_seq', $lecture_seq)->first();
        // 기존 파일 삭제
        $file_path = $lecture_uploadfile->file_path;
        File::delete(storage_path('app/public/'.$file_path));
        // DB
        $lecture_uploadfile->delete();
        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 강좌 삭제
    public function lectureDelete(Request $request){
        $lecture_seq = $request->input('lecture_seq');
        //섬네일 삭제
        $this->thumbnailDelete($request);

        //강좌 상제
        $lecture = \App\Lecture::where('id', $lecture_seq)->first();
        $lecture->delete();

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    //
    public function lectureUseUpdate(Request $request){
        $lecture_seq = $request->input('lecture_seq');
        $is_use = $request->input('is_use');
        $lecture = \App\Lecture::where('id', $lecture_seq)->first();
        $lecture->is_use = $is_use;
        $lecture->save();

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 분:초 를 초로 변경.
    private function timeToSecond($time){
        $time = explode(':', $time);
        $time = $time[0]*60 + $time[1];
        return $time;
    }

    // 인터렉트 미리보기
    public function interactivePreview(Request $request){
        $interactive_seq = $request->input('interactive_seq');
        $interactive = \App\Interactive::find($interactive_seq);
        $json_data = $interactive->json_data;
        $interactive_json = $json_data;

        return response()->json(['resultCode' => 'success', 'interactive_json' => $interactive_json]);

    }

}
