<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InteractiveMTController extends Controller
{
    public function list(Request $request){
        $main_code = session()->get('main_code') ?? $_COOKIE['main_code'];

        // 학년
        $grade_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'grade')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        // 과목
        $subject_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'subject')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        // 학기
        $semester_codes = \App\Code::where('main_code', $main_code)->where('code_category', 'semester')->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        return view('admin.admin_interactive', [
            'grade_codes' => $grade_codes,
            'subject_codes' => $subject_codes,
            'semester_codes' => $semester_codes
        ]);
    }

    // 인터렉티브 등록
    public function insert(Request $request){
        $main_code = session()->get('main_code') ?? $_COOKIE['main_code'];
        $teach_seq = session()->get('teach_seq');

        $interactive_seq = $request->input('interactive_seq');
        $subject_seq = $request->input('subject_seq');
        $grade_seq = $request->input('grade_seq');
        $semester_seq = $request->input('semester_seq');
        $title = $request->input('title');
        $type = $request->input('type');
        $json_data = $request->input('json_data');

        $interactive = \App\Interactive::updateOrCreate(
            [
                'id' => $interactive_seq
            ],
            [
                'main_code' => $main_code,
                'subject_seq' => $subject_seq,
                'grade_seq' => $grade_seq,
                'semester_seq' => $semester_seq,
                'title' => $title,
                'type' => $type,
                'json_data' => $json_data,
                'created_id' => $teach_seq,

            ]
        );

        if (!$interactive->wasRecentlyCreated) {
            $interactive->updated_id = $teach_seq;
            $interactive->save();
        }

        // 결과
        if($interactive->id > 0){
            return response()->json([
                'resultCode' => 'success',
                'interactive_seq' => $interactive->id
            ]);
        }else{
            return response()->json([
                'resultCode' => 'fail'
            ]);
        }
    }

    // 인터렉티브 조회
    public function select(Request $request){
        $main_code = session()->get('main_code') ?? $_COOKIE['main_code'];

        $subject_seq = $request->input('subject_seq');
        $grade_seq = $request->input('grade_seq');
        $semester_seq = $request->input('semester_seq');
        $exam_title =  $request->input('title');

        // 페이징 쿼리
        $page = $request->input('page');
        $page = $page ?? 1;
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 15;
        $is_not_page = $request->input('is_not_page');

        $interactives = \App\Interactive::
            select(
                'interactives.*',
                'subject.code_name as subject_name',
                'grade.code_name as grade_name',
                'semester.code_name as semester_name',
                'ct.teach_name as created_name',
                'ut.teach_name as updated_name'
            )
            ->leftJoin('codes as subject', 'subject.id', '=', 'interactives.subject_seq')
            ->leftJoin('codes as grade', 'grade.id', '=', 'interactives.grade_seq')
            ->leftJoin('codes as semester', 'semester.id', '=', 'interactives.semester_seq')
            ->leftJoin('teachers as ct', 'ct.id', '=', 'interactives.created_id')
            ->leftJoin('teachers as ut', 'ut.id', '=', 'interactives.updated_id')
            ->where('interactives.main_code', $main_code);

        // 과목이 있을 경우.
        if(!empty($subject_seq)){
            $interactives = $interactives->where('interactives.subject_seq', $subject_seq);
        }
        // 학년이 있을 경우.
        if(!empty($grade_seq)){
            $interactives = $interactives->where('interactives.grade_seq', $grade_seq);
        }
        // 학기가 있을 경우.
        if(!empty($semester_seq)){
            $interactives = $interactives->where('interactives.semester_seq', $semester_seq);
        }
        // 제목이 있을 경우.
        if(!empty($exam_title)){
            $interactives = $interactives->where('interactives.title', 'like', '%'.$exam_title.'%');
        }

        if(empty($is_not_page)){
            // 페이징
            $interactives = $interactives->paginate($page_max, ['*'], 'page', $page);
        }else{
            $interactives = $interactives->get();
        }

        // 결과
        $result['resultCode'] = 'success';
        $result['interactives'] = $interactives;

        return response()->json($result);
    }
}
