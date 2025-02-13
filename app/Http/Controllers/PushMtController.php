<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PushMtController extends Controller
{
    //
    public function list(Request $request){
        $login_type = session()->get('login_type');
        // 알림센터 분류 가져오기.
        $notifi = \App\Code::
            where('main_code', $_COOKIE['main_code'])
            ->where('code_category', 'notifi')
            ->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();
        // 알림발송 분류 가져오기.
        $notifi_type = \App\Code::where('main_code', $_COOKIE['main_code'])
            ->where('code_category', 'notifi_type')
            ->where('code_step', '=', 1)
            ->orderBy('code_idx', 'asc')
            ->get();

        return view('teacher.teacher_push_list', [
            'notifi'=>$notifi,
            'notifi_type'=>$notifi_type,
            'login_type'=>$login_type
        ]);
    }

    public function select(Request $request){
        $login_type = session()->get('login_type');
        $notifi_seq = $request->input('notifi_seq');
        $send_student_seq = $request->input('send_student_seq');
        $send_class_seq = $request->input('send_class_seq');
        $team_code = $request->input('team_code');
        $page  = $request->input('page');
        $page = $page ?? 1;
        $page_max = $request->input('page_max');
        $page_max = $page_max ?? 6;
        $main_code = session()->get('main_code')??$_COOKIE['main_code'];
        // $sql->paginate($page_max, ['*'], 'page', $page);

        $pushes = \App\Push::
            select(
                'pushes.*',
                'students.student_name',
                'grade_codes.code_name as grade_name'
            )
            ->where('pushes.main_code', $_COOKIE['main_code'])
            ->leftJoin('students', 'pushes.send_student_seq', '=', 'students.id')
            ->leftJoin('codes as grade_codes', 'students.grade', '=', 'grade_codes.id')
            ->orderBy('pushes.id', 'desc');

        $notifi_seq = $notifi_seq == 'all' ? '':$notifi_seq;
        // 조건
        if($notifi_seq){
            $pushes = $pushes->where('notifi_seq', $notifi_seq);
        }
        // 선생님일 경우 학생쪽에서 발생한 알림
        if($send_student_seq){
            $pushes = $pushes->where('pushes.send_student_seq', $send_student_seq);
        }
        // 선생님일 경우 학생쪽의 클래스에서 발생한 알림
        if($send_class_seq){
            $pushes = $pushes->where('pushes.send_class_seq', $send_class_seq);
        }
        // 팀코드
        if($team_code){
            $pushes = $pushes->where('pushes.team_code', $team_code);
        }

        // 로그인한 사람
        if(session()->get('login_type') == 'teacher'){
            $pushes = $pushes->where('teacher_seq', session()->get('teach_seq'));
        }else if(session()->get('login_type') == 'student'){
            $pushes = $pushes->where('student_seq', session()->get('student_seq'));
        }else if(session()->get('login_type') == 'parent'){
            $pushes = $pushes->where('pushes.parent_seq', session()->get('parent_seq'));
        }else
            $pushes = $pushes->where('teacher_seq', 'AA');

        $result['sql'] = $pushes->toSql();
        $pushes = $pushes->paginate($page_max, ['*'], 'page', $page);

        // 결과
        // $result = array();
        $result['pushes'] = $pushes;
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 알림 모두 읽음 처리.
    public function allRead(Request $request){
        $login_type = session()->get('login_type');
        $notifi_seq = $request->input('notifi_seq');
        $main_code = session()->get('main_code');

        //$notifi_seq = all이면 전체 읽음 처리.
        $pushes = \App\Push::all();
        // $pushes = \App\Push::where('main_code', $main_code);

        if($notifi_seq != 'all'){
            $pushes = $pushes->where('notifi_seq', $notifi_seq);
        }
        if($login_type == 'teacher'){
            $pushes = $pushes->where('teacher_seq', session()->get('teach_seq'));
        }
        else if($login_type == 'student'){
            $pushes = $pushes->where('student_seq', session()->get('student_seq'));
        }
        else if($login_type == 'parent'){
            $pushes = $pushes->where('parent_seq', session()->get('parent_seq'));
        }
        else{
            $pushes = $pushes->where('teacher_seq', 'AA');
        }
        $pushes = $pushes->update(['is_read' => 'Y']);

        $result['resultCode'] = 'success';
        return response()->json($result);

    }
    // 알림 모두 삭제 처리.
    public function allDelete(Request $request){
        $login_type = session()->get('login_type');
        $notifi_seq = $request->input('notifi_seq');
        $push_seq = $request->input('push_seq');
        $main_code = session()->get('main_code');

        //$notifi_seq = all이면 전체 읽음 처리.
        $pushes = \App\Push::query();
        // where('main_code', $main_code);

        if($push_seq){
            $pushes = $pushes->where('id', $push_seq);
            $notifi_seq = 'all';
        }
        if($notifi_seq != 'all'){
            $pushes = $pushes->where('notifi_seq', $notifi_seq);
        }
        if($login_type == 'teacher'){
            $pushes = $pushes->where('teacher_seq', session()->get('teach_seq'));
        }
        else if($login_type == 'student'){
            $pushes = $pushes->where('student_seq', session()->get('student_seq'));
        }
        else if($login_type == 'parent'){
            $pushes = $pushes->where('parent_seq', session()->get('parent_seq'));
        }
        else{
            $pushes = $pushes->where('teacher_seq', 'AA');
        }
        $result['sql'] = $pushes->toSql();
        $result['bind'] = $pushes->getBindings();
        $pushes = $pushes->delete();

        $result['resultCode'] = 'success';
        return response()->json($result);
    }
}
