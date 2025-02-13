<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlarmMtController extends Controller
{
    //alarm
    public function alarm()
    {
        $main_code = $_COOKIE['main_code'];

        //소속
        $region = \App\Region::orderBy('region_name')->get();
        $region = $region->toArray();

        //팀 가져오기
        $team = \App\Team::orderBy('team_code')->get();
        $team = $team->toArray();

        // 학년 분류 가져오기
        $grade_codes = \App\Code::where('code_category', 'grade')->where('code_step', '=', 1)->where('main_code', '=', $main_code)->get();
        $grade_codes = $grade_codes->toArray();

        return view('admin.admin_alarm', ['region' => $region, 'team' => $team, 'grade_codes' => $grade_codes]);
    }

    //alarm
    public function alarmStat()
    {
        //선생 정보 가져오기
        $teachers = \App\Teacher::orderBy('teach_name')->get();
        $teachers = $teachers->toArray();
        return view('admin.admin_alarmstat', ['teachers' => $teachers]);
    }

    // 메시지 목록 가져오기
    public function messagelist(Request $request)
    {
        $mform_type = $request->input('mform_type');
        $team_code = $request->session()->get('team_code');

        $page = $request->input('page');
        $page = $page ?? 1;
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 10;
        // $sql->paginate($page_max, ['*'], 'page', $page);

        $message_form = \App\MessageForm::
            where('team_code', $team_code)
                ->where('mform_type', 'like', $mform_type . '%');

        $message_form = $message_form->paginate($page_max, ['*'], 'page', $page);

        $result = array();
        $result['resultCode'] = 'success';
        $result['message_form_info'] = $message_form;
        return response()->json($result);
    }

    // 메시지 목록 저장
    public function messageinsert(Request $request)
    {
        $mform_title = $request->input('mform_title');
        $mform_content = $request->input('mform_content');
        $mform_type = $request->input('mform_type');
        $team_code = $request->session()->get('team_code');
        $img_data = $request->input('img_data');
        $img_size = $request->input('img_size');
        $url_str = $request->input('url_str');
        $kko_code = $request->input('kko_code');

        $message_form = new \App\MessageForm;
        $message_form->mform_title = $mform_title;
        $message_form->mform_content = $mform_content;
        $message_form->mform_type = $mform_type;
        $message_form->team_code = $team_code;
        $message_form->img_data = $img_data;
        $message_form->img_size = $img_size;
        $message_form->url = $url_str;
        $message_form->kko_code = $kko_code;
        $message_form->save();

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 메시지 목록 수정
    public function messageupdate(Request $request)
    {
        $mform_seq = $request->input('mform_seq');
        $mform_title = $request->input('mform_title');
        $mform_content = $request->input('mform_content');
        $img_data = $request->input('img_data');
        $kko_code = $request->input('kko_code');
        $url_str = $request->input('url_str');

        $message_form = \App\MessageForm::find($mform_seq);
        $message_form->mform_title = $mform_title;
        $message_form->mform_content = $mform_content;
        $message_form->img_data = $img_data;
        $message_form->kko_code = $kko_code;
        $message_form->url = $url_str;
        $message_form->save();

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 메시지 목록 삭제
    public function messagedelete(Request $request)
    {
        $mform_seqs = $request->input('mform_seqs');
        $mform_seqs = explode(',', $mform_seqs);

        $message_form = \App\MessageForm::whereIn('id', $mform_seqs)->delete();

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 회원 목록 가져오기
    public function studentlist(Request $request)
    {
        $search_type = $request->input('search_type');
        $search_text = $request->input('search_text');
        $region_seq = $request->input('region_seq');
        $team_code = $request->input('team_code');
        $user_type = $request->input('user_type');
        $grade = $request->input('grade');

        $page = $request->input('page');
        $page = $page ?? 1;
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 10;
        // $sql->paginate($page_max, ['*'], 'page', $page);

        // 회원 목록 가져오기
        $students = \App\Student::select(
            'st.*',
            'grade_codes.code_name as grade_name',
            'pt.parent_name',
            'pt.parent_phone',
            'pt.parent_id',
            'tr.teach_name',
            'ug.group_name',
            'ug.group_type'
        )
            ->from('students as st');
        $students = $students->leftJoin('parents as pt', 'pt.id', '=', 'st.parent_seq');
        $students = $students->leftJoin('teachers as tr', 'tr.id', '=', 'st.teach_seq');
        $students = $students->leftJoin('user_groups as ug', 'ug.id', '=', 'st.group_seq');
        $students = $students->leftJoin('codes as grade_codes', 'st.grade', '=', 'grade_codes.id');

        //티켓도 추후 추가 [추가 코드]

        // 검색 조건 / id, phone, school, grade, name, ticket, parent, teacher
        if(strlen($search_text) > 0 || $search_type == 'grade'){
            if($search_type == 'id'){
                $students = $students->where(function ($query) use ($search_text) {
                    $query->where('st.student_id', 'like', '%' . $search_text . '%')
                        ->orWhere('pt.parent_id', 'like', '%' . $search_text . '%');
                });
            }else if($search_type == 'phone'){
                $students = $students->where(function ($query) use ($search_text) {
                    $query->where('st.student_phone', 'like', '%' . $search_text . '%')
                        ->orWhere('pt.parent_phone', 'like', '%' . $search_text . '%');
                });
            }else if($search_type == 'school'){
                $students = $students->where('st.school_name', 'like', '%' . $search_text . '%');
            }else if($search_type == 'grade'){
                $students = $students->where('st.grade', 'like', '%' . $grade . '%');
            }else if($search_type == 'name'){
                $students = $students->where(function ($query) use ($search_text) {
                    $query->where('st.student_name', 'like', '%' . $search_text . '%')
                        ->orWhere('pt.parent_name', 'like', '%' . $search_text . '%');
                });
            }else if($search_type == 'ticket'){
                // [추가 코드]
            }else if($search_type == 'parent'){
                $students = $students->where('pt.parent_name', 'like', '%' . $search_text . '%');
            }else if($search_type == 'teacher'){
                $students = $students->where('tr.teach_name', 'like', '%' . $search_text . '%');
            }
        }
        // 로그인 타입이 amdin이 아니라 teacher일경우 자신의 team_code 학생만 가져오기
        $login_type = session()->get('login_type');
        if($login_type == 'teacher'){
            $team_code = session()->get('team_code');
            $students = $students->where('st.team_code', $team_code);
        }

        // $students = $students->get();
        $result['sql'] = $students->toSql();
        $result['bindings'] = $students->getBindings();
        $students = $students->paginate($page_max, ['*'], 'page', $page);
        $result['resultCode'] = 'success';
        $result['student_info'] = $students;
        return response()->json($result);
    }

    // 학생 seq로 학생과, 학부모 전화번호 가져오기.
    public function sendUserInfo(Request $request){
        $student_seqs = $request->input('student_seqs');
        // 중복 제거
        $student_seqs = array_unique($student_seqs);

        // TODO: 현재 문제 확인 컨트롤러에서는
        // push는 push_key하나만 넘기도록 되어있는데, 여기서는 학부모와, 학생 모두 가져오도록 추가.
        // 또 학부모의 push_key가 스테이크 형식이 아니라, pushKey형식인 카멜로 되어있어서 혼란 수정요함.

        $user_infos = \App\Student::select(
            'students.id as student_seq',
            'students.student_id',
            'students.student_name',
            'students.student_phone',
            'parents.id as parent_seq',
            'parents.parent_id',
            'parents.parent_name',
            'parents.parent_phone',
            'grade_codes.code_name as grade',
            'parents.pushKey as parent_push_key',
            'students.push_key as student_push_key'
        )
        ->leftJoin('parents', 'students.parent_seq', '=', 'parents.id')
        ->leftJoin('codes as grade_codes', 'students.grade', '=', 'grade_codes.id')
        ->whereIn('students.id', $student_seqs)
        ->get()
        ->keyBy('student_seq');

        // 결과
        $result['student_seqs'] = $student_seqs;
        $result['resultCode'] = 'success';
        $result['user_infos'] = $user_infos;
        return response()->json($result);
    }
}
