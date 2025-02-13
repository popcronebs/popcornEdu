<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SampleTimeTableMTController extends Controller
{
    //
    public function list(){
        $main_code = $_COOKIE['main_code'];
        $codes_all = \App\Code::where('main_code', $main_code)->get();

        return view('admin.admin_sample_timetable', ['codes_all' => $codes_all]);
    }

    // 샘플 시간표 그룹 등록
    public function timetableGroupInsert(Request $request){
        $main_code = $_COOKIE['main_code'];
        $timetable_group_seq = $request->input('timetable_group_seq');
        $timetable_group_title = $request->input('timetable_group_title');
        $grade_code = $request->input('grade_code');


        $timetable_group = \App\TimetableGroup::updateOrCreate(
            ['id' => $timetable_group_seq],
            [
                'timetable_group_title' => $timetable_group_title,
                'grade_seq' => $grade_code,
                'main_code' => $main_code
            ]
        );

        $timetable_group_seq = $timetable_group->id;

        //결과
        $result = array(
            'resultCode' => 'success',
            'timetable_group_seq' => $timetable_group_seq
        );

        return response()->json($result, 200);
    }

    // 샘플 시간표 그룹 SELECT
    public function timetableGroupSelect(Request $request){
        $main_code = $_COOKIE['main_code'];
        $grade_seq = $request->input('grade_seq');

        $timetable_groups = \App\TimetableGroup::where('main_code', '=', $main_code)->where('grade_seq', '=', $grade_seq)->get();

        //결과
        $result = array(
            'resultCode' => 'success',
            'timetable_groups' => $timetable_groups
        );
        return response()->json($result, 200);
    }

    // 시간표 가져오기
    public function timetableSelect(Request $request){
        $timetable_group_seq = $request->input('timetable_group_seq');
        $timetables = \App\Timetable::
            select( 'timetables.*',
                    'lecture_details.lecture_detail_name',
                    'lecture_details.idx',
                    'lectures.teacher_name',
                    DB::raw('level_codes.code_name as level_names'),
                    'subject_codes.code_name as subject_name',
                    'subject_codes.function_code as subject_function_code'
                    )
            ->where('timetable_group_seq', '=', $timetable_group_seq)
            ->leftJoin('lecture_details', 'lecture_details.id', '=', 'timetables.start_lecture_detail_seq')
            ->leftJoin('lectures', 'lectures.id', '=', 'timetables.lecture_seq')
            ->leftJoin('lecture_codes as levels', function($join){
                $join->on('lectures.id', '=', 'levels.lecture_seq')
                ->where('levels.code_category', 'level');
            })
            ->leftJoin('codes as level_codes', 'levels.code_seq', '=', 'level_codes.id')
            ->leftJoin('codes as subject_codes', 'subject_codes.id', '=', 'timetables.subject_seq')
            ->get();

        //결과
        $result = array(
            'resultCode' => 'success',
            'timetables' => $timetables
        );
        return response()->json($result, 200);
    }

    // 강좌 목록 가져오기
    public function lectureSelect(Request $request){
        $main_code = $_COOKIE['main_code'];
        $grade_seq = $request->input('grade_seq');
        $subject_seq = $request->input('subject_seq');
        $series_seq = $request->input('series_seq');
        $publisher_seq = $request->input('publisher_seq');
        $course_seq = $request->input('course_seq');
        $lecture_name  = $request->input('search_str');

        // lecture_codes 에서 각각 조건에 을 넣어서 강좌의 id(다음 쿼리에서 사용될 조건)를 가져온다.
        $code_seqs = array();
        if(strlen($grade_seq) > 0) array_push($code_seqs, $grade_seq);
        if(strlen($subject_seq) > 0) array_push($code_seqs, $subject_seq);
        if(strlen($series_seq) > 0) array_push($code_seqs, $series_seq);
        if(strlen($publisher_seq) > 0) array_push($code_seqs, $publisher_seq);
        if(strlen($course_seq) > 0) array_push($code_seqs, $course_seq);

        $lecture_ids_sql = \App\LectureCode::select('lecture_seq');
        $lecture_ids_sql = $lecture_ids_sql->whereIn('code_seq', $code_seqs);
        $lecture_ids_sql = $lecture_ids_sql->groupBy('lecture_seq');
        $lecture_ids_sql = $lecture_ids_sql->havingRaw('count(*) = '.count($code_seqs));

        $lecture_ids = $lecture_ids_sql->get();
        //라라벨 함수로 배열 변환
        $lecture_ids = $lecture_ids->pluck('lecture_seq')->toArray();


        // "Field","Type","Null","Key","Default","Extra"
        // "id","int(11)","NO","PRI","","auto_increment"
        // "main_code","varchar(10)","YES","","",""
        // "teacher_name","varchar(50)","YES","","",""
        // "course_date_count","int(11)","YES","","",""
        // "book_name","varchar(255)","YES","","",""
        // "book_link","varchar(255)","YES","","",""
        // "is_use","varchar(5)","YES","","",""
        // "lecture_name","varchar(255)","YES","","",""
        // "lecture_description","varchar(255)","YES","","",""
        // "lecture_detail_count","int(11)","YES","","",""
        // "created_at","datetime","YES","","",""
        // "updated_at","datetime","YES","","",""

        $lectures = \App\Lecture::select(
            'lectures.id',
            'lectures.lecture_name',
            'lectures.lecture_description',
            'lectures.teacher_name',
            'lectures.course_date_count',
            'lectures.book_name',
            'lectures.book_link',
            'lectures.is_use',
            'lectures.lecture_detail_count',
            'lectures.lecture_detail_count_all_day',
            'lectures.created_at',
            'lectures.updated_at',
            'lecture_uploadfiles.file_path'
        );
        $lectures = $lectures->leftJoin('lecture_uploadfiles', 'lecture_uploadfiles.lecture_seq', '=', 'lectures.id');

        // grade
        $lectures = $lectures
            ->addSelect(DB::raw('group_concat(grade.code_name) as grade_name'))
            ->join('lecture_codes as grade_codes', function($join) use ($grade_seq){
            $join->on('grade_codes.lecture_seq', '=', 'lectures.id')
                ->where('grade_codes.code_category', '=', 'grade');
                // ->where('grade_codes.code_seq', '=', 'lecture_codes.code_seq');
        });
        $lectures = $lectures->leftJoin('codes as grade', 'grade.id', '=', 'grade_codes.code_seq');

        // subject
        $lectures = $lectures
            ->addSelect(DB::raw('group_concat(subject.code_name) as subject_name'))
            ->join('lecture_codes as subject_codes', function($join) use ($subject_seq){
            $join->on('subject_codes.lecture_seq', '=', 'lectures.id')
                ->where('subject_codes.code_category', '=', 'subject');
                // ->where('subject_codes.code_seq', '=', 'lecture_codes.code_seq');
        });
        $lectures = $lectures->leftJoin('codes as subject', 'subject.id', '=', 'subject_codes.code_seq');

        // course
        $lectures = $lectures
            ->addSelect(DB::raw('group_concat(course.code_name) as course_name'))
            ->join('lecture_codes as course_codes', function($join) use ($course_seq){
            $join->on('course_codes.lecture_seq', '=', 'lectures.id')
                ->where('course_codes.code_category', '=', 'course');
                // ->where('course_codes.code_seq', '=', 'lecture_codes.code_seq');
        });
        $lectures = $lectures->leftJoin('codes as course', 'course.id', '=', 'course_codes.code_seq');

        // publisher
        $lectures = $lectures
            ->addSelect(DB::raw('group_concat(publisher.code_name) as publisher_name'))
            ->join('lecture_codes as publisher_codes', function($join) use ($publisher_seq){
            $join->on('publisher_codes.lecture_seq', '=', 'lectures.id')
                ->where('publisher_codes.code_category', '=', 'publisher');
                // ->where('publisher_codes.code_seq', '=', 'lecture_codes.code_seq');
        });
        $lectures = $lectures->leftJoin('codes as publisher', 'publisher.id', '=', 'publisher_codes.code_seq');

        // series
        $lectures = $lectures
            ->addSelect(DB::raw('group_concat(series.code_name) as series_name'))
            ->join('lecture_codes as series_codes', function($join) use ($series_seq){
            $join->on('series_codes.lecture_seq', '=', 'lectures.id')
                ->where('series_codes.code_category', '=', 'series');
                // ->where('series_codes.code_seq', '=', 'lecture_codes.code_seq');
        });
        $lectures = $lectures->leftJoin('codes as series', 'series.id', '=', 'series_codes.code_seq');

        // level
        $lectures = $lectures
            ->addSelect(DB::raw('group_concat(level.code_name) as level_name'))
            ->join('lecture_codes as level_codes', function($join){
            $join->on('level_codes.lecture_seq', '=', 'lectures.id')
                ->where('level_codes.code_category', '=', 'level');
        });
        $lectures = $lectures->leftJoin('codes as level', 'level.id', '=', 'level_codes.code_seq');


        $lectures = $lectures->where('lectures.main_code', '=', $main_code);
        $lectures = $lectures->whereIn('lectures.id', $lecture_ids);


        if(strlen($lecture_name) > 0)
            $lectures = $lectures->where('lectures.lecture_name', 'like', '%'.$lecture_name.'%');

        $lectures = $lectures->groupBy('lectures.id', 'file_path');

        $lectures = $lectures->get();

        //결과
        $result = array(
            'resultCode' => 'success',
            'lectures' => $lectures,
            'lecture_ids' => $lecture_ids
        );
        return response()->json($result, 200);
    }

    // 강좌 추가 화면 > 시작강의 선택위한 리스트 불러오기.
    public function lectureDetailSelect(Request $request){
        $lecture_seq = $request->input('lecture_seq');
        $is_main_detail = $request->input('is_main_detail');

        $lecture_details = \App\LectureDetail::where('lecture_seq', '=', $lecture_seq)->orderBy('idx');

        if($is_main_detail == 'Y'){
            $lecture_details = $lecture_details->where(function($query){
                $query->where('lecture_detail_type', DB::raw("''"))
                    ->orWhere('lecture_detail_type', null);
            });
        }
        $sql = $lecture_details->toSql();
        $lecture_details = $lecture_details->get();

        //결과
        $result = array(
            'resultCode' => 'success',
            'lecture_details' => $lecture_details,
            'sql' => $sql
        );
        return response()->json($result, 200);
    }

    // 시간표 강좌목록 등록
    public function timetableInsert(Request $request){
        $main_code = $_COOKIE['main_code'];
        $timetable_group_seq = $request->input('timetable_group_seq');
        $subject_seq = $request->input('subject_seq');
        $series_seq = $request->input('series_seq');
        $publisher_seq = $request->input('publisher_seq');
        $lecture_seq = $request->input('lecture_seq');
        $lecture_name = $request->input('lecture_name');
        $start_lecture_detail_seq = $request->input('start_lecture_detail_seq');
        $timetable_days = $request->input('timetable_days');
        $timetable_start_date = $request->input('timetable_start_date');
        $timetable_seq = $request->input('timetable_seq');

        // 상단에 변수가 있으면 insert 할 DATA를 추가해준다.
        $insert_data = array();
        if(strlen($subject_seq) > 0) $insert_data['subject_seq'] = $subject_seq;
        if(strlen($series_seq) > 0) $insert_data['series_seq'] = $series_seq;
        if(strlen($publisher_seq) > 0) $insert_data['publisher_seq'] = $publisher_seq;
        if(strlen($lecture_seq) > 0) $insert_data['lecture_seq'] = $lecture_seq;
        if(strlen($lecture_name) > 0) $insert_data['lecture_name'] = $lecture_name;
        if(strlen($start_lecture_detail_seq) > 0) $insert_data['start_lecture_detail_seq'] = $start_lecture_detail_seq;
        if(strlen($timetable_days) > 0) $insert_data['timetable_days'] = $timetable_days;
        if(strlen($timetable_start_date) > 0) $insert_data['timetable_start_date'] = $timetable_start_date;
        if(strlen($timetable_group_seq) > 0) $insert_data['timetable_group_seq'] = $timetable_group_seq;
        $insert_data['main_code'] = $main_code;


        $timetable = \App\Timetable::updateOrCreate(
            ['id' => $timetable_seq],
            $insert_data
        );

        $timetable_seq = $timetable->id;

        //결과
        $result = array(
            'resultCode' => 'success',
            'timetable_seq' => $timetable_seq
        );
        return response()->json($result, 200);
    }

    // 시간표 강좌목록 삭제
    public function timetableDelete(Request $request){
        $timetable_seq = $request->input('timetable_seq');
        $timetable_group_seq = $request->input('timetable_group_seq');

        $timetable = \App\Timetable::where('id', '=', $timetable_seq)->where('timetable_group_seq', '=', $timetable_group_seq)->delete();

        //결과
        $result = array(
            'resultCode' => 'success'
        );
        return response()->json($result, 200);
    }
}
