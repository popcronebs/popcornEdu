<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function main(){
        //세션 촐괄매니저이면 이면 teacher_main_general
        if(session()->get('group_type2') == 'general'){
            $teach_seq = session()->get('teach_seq');
            //
            // regions 가져오기.
            $regions = \App\Region::where('general_teach_seq', $teach_seq)->get();
            // teams
            $teams = \App\Team::
            select(
                'teams.region_seq',
                'team_areas.team_code',
                'team_areas.tarea_sido',
                'team_areas.tarea_gu',
                'team_areas.tarea_dong'
            )
            ->leftJoin('team_areas', 'teams.team_code', '=', 'team_areas.team_code')
            ->whereIn('teams.region_seq', $regions->pluck('id'))->get();


            // 학생(만료) 가져오기.
            $today = date('Y-m-d');
            $students = \App\Student::
            select(
                'students.*',
                'grade_codes.code_name as grade_name',
                'gd.start_date as goods_start_date',
                'gd.end_date as goods_end_date',
                'gd.goods_name',
                'gd.goods_period'
            )
            ->leftJoin('goods_details as gd', 'gd.id', '=', 'students.goods_detail_seq')
            ->whereIn('students.team_code', $teams->pluck('team_code'));

            // 학생(신규등록, 재등록) 가져오기.
            $students2 = \App\Student::
            select(
                'students.id',
                DB::raw('count(gd.id) as cnt'),
                DB::raw('max(gd.end_date) as end_date')
            )
            ->leftJoin('goods_details as gd', function($join){
                $join->on('gd.student_seq', '=', 'students.id');
                $join->where('gd.is_use', '=', 'Y');
            })
            ->groupBy('students.id')
            ->whereIn('students.team_code', $teams->pluck('team_code'));

            // :만료 학생 수
            $expire_cnt = (clone $students)->where('gd.end_date', '<', $today)->count();

            // :재등록
            $readd_cnt = (clone $students2)->having(DB::raw('count(gd.id)'), '>', 1)->having(DB::raw('max(gd.end_date)'), '>', $today)->count();

            // :신규등록
            $new_cnt = (clone $students2)->having(DB::raw('count(gd.id)'), '=', 1)->having(DB::raw('max(gd.end_date)'), '>', $today)->count();

            // :신규상담
            $new_counsel_cnt = \App\Counsel::
            where('student_type', 'new')
            ->where('counsel_category', 'goods')
            ->where('start_date', $today)
            ->whereIn('team_code', $teams->pluck('team_code'))
            ->count();

            // team_area
            return view('teacher.teacher_main_general',
            [
                'regions' => $regions,
                'teams' => $teams,
                'expire_cnt' => $expire_cnt,
                'readd_cnt' => $readd_cnt,
                'new_cnt' => $new_cnt,
                'new_counsel_cnt' => $new_counsel_cnt
            ]);
        }else
            return view('teacher.teacher_main');
    }

    //
    public function login(Request $request){
        // $team_code = $request->input('team_code');
        $teach_id = $request->input('id');
        $password = $request->input('password');
        $is_not_login = $request->input('is_not_login');

        // select * from teachers where teach_id = 'teach1' and teach_pw = SHA1('1234') and team_code <> 'maincd'
        //post 값이 있으면
        // $team_code != null &&
        if($teach_id != null && $password != null){
            //password 를 SHA1()로 암호화
            $users = \App\Teacher::
            // where('team_code', $team_code)
            // ->
            where('teachers.team_code', '<>', 'maincd')
            ->where('teach_id', $teach_id)
            ->whereRaw('teach_pw = SHA1(?)',[$password])
            ->leftJoin('teams', 'teachers.team_code', '=', 'teams.team_code')
            ->select('teachers.*', 'teams.team_type', 'teams.team_name')
            ->get();
            // select *From teachers where team_code <> 'maincd' and teach_id = 'teach1'

            $team_code = $users[0]->team_code ?? '';
            //선생님이 있으면 세션 체우기.
            if(count($users) == 1 && $team_code != ''){
                // return view('teacher');
                $request->session()->put('teach_seq', $users[0]->id);
                $request->session()->put('team_code', $team_code);
                $request->session()->put('teach_id', $teach_id);
                $request->session()->put('teach_pw', $password);
                $request->session()->put('teach_type', $users[0]->teach_type);
                $request->session()->put('teach_name', $users[0]->teach_name);
                $request->session()->put('group_seq', $users[0]->group_seq);
                $request->session()->put('region_seq', $users[0]->region_seq);
                $request->session()->put('team_code', $users[0]->team_code);
                $request->session()->put('login_type', 'teacher');
                $request->session()->put('team_type', $users[0]->team_type);
                $request->session()->put('is_first_login', $users[0]->is_first_login);
                $request->session()->put('team_name', $users[0]->team_name);
                $request->session()->put('teach_phone', $users[0]->teach_phone);
                $request->session()->put('teach_email', $users[0]->teach_email);
                $request->session()->put('teach_address', $users[0]->teach_address);

                setcookie('login_type', 'teacher', time() + (86400 * 30), "/");
                $request->session()->put('main_code', $users[0]->main_code);
                setcookie('main_code', $users[0]->main_code, time() + (86400 * 30), "/");


                $team_area = \App\TeamArea::where('team_code', $team_code)->first();
                $request->session()->put('team_area_sido', $team_area->tarea_sido);
                $request->session()->put('team_area_gu', $team_area->tarea_gu);
                $request->session()->put('team_area_dong', $team_area->tarea_dong);

                $regions = \App\Region::where('id', $users[0]->region_seq)->first();
                $request->session()->put('region_name', $regions->region_name);

                // 방과후인지 아닌지 체크.
                if($users[0]->team_type == 'after_school'){
                    //방과후이면서 첫 로그인인지 확인.
                    if($users[0]->is_first_login == 'Y'){
                        //첫 로그인이면서 방과후이면서 지정 페이지로 이동.
                        $request->session()->put('is_first_login', 'Y');
                        return redirect('/teacher/after/first/login');
                    }
                }
                // 방과후가 아닌 학원일경우.
                else{
                    if($users[0]->is_first_login == 'Y'){
                        //첫 로그인이면서 방과후가 아니면서 지정 페이지로 이동.
                        $request->session()->put('is_first_login', 'Y');
                        return redirect('/teacher/first/login');
                    }
                }

                //첫페이지가 있는 그룹일경우 지정 페이지로 이동.
                if(strlen($users[0]->group_seq) > 0){
                    $user_group = \App\UserGroup::where('id', $users[0]->group_seq)->first();
                    $group_name = $user_group->group_name;
                    $request->session()->put('group_name', $group_name);
                    $request->session()->put('group_type2', $user_group->group_type2);
                    $request->session()->put('group_type3', $user_group->group_type3);
                    $first_page = $user_group->first_page;
                    $menu = \App\Menu::where('id', $first_page)->first();
                    $menu_url = $menu->menu_url ?? '';
                    if(strlen($menu_url) > 0){
                        $request->session()->put('menu_url', $menu_url);
                        return redirect($menu_url);
                    }else{
                        return redirect('/teacher/main');
                    }
                }
                //아니면 기존 메인 페이지로 이동.
                else{
                    return redirect('/teacher/main');
                }
            }else{
                //비우기
                $request->session()->flush();
                //결과값을 is_not_login = true 을 teacher_login에 넘겨주기.
                // return view('teacher.teacher_login', ['is_not_login' => true]);
                return redirect('/teacher/login?is_not_login=true');
            }
        }
        //session 의 teach_id 에 값이 있고, session의 login_type 이 admin이면
        else if($request->session()->has('teach_id') && $request->session()->get('login_type') == 'teacher'){
            if($request->session()->get('is_first_login') == 'Y'){
                return redirect('/teacher/after/first/login');
            }
            //세션에 menu_url 이 있으면
            if($request->session()->has('menu_url')){
                //menu_url로 이동
                return redirect($request->session()->get('menu_url'));
            }
            return redirect('/teacher/main'); // 임시 메인.
        }
        if($is_not_login == 'true'){
            return view('teacher.teacher_login', ['is_not_login' => true]);
        }else
            return view('teacher.teacher_login');
    }
    //logout
    public function logout(Request $request){

        $login_type = session('login_type');
        $user_id = null;

        switch($login_type) {
            case 'parent':
                $user_id = session('parent_seq');
                break;
            case 'student':
                $user_id = session('student_seq');
                break;
            case 'teacher':
                $user_id = session('teach_seq');
                break;
        }

        if($user_id) {
            // 세션 테이블에서 해당 사용자의 세션 삭제
            \App\Sessions::where('user_id', $user_id)
                ->where('login_type', $login_type)
                ->delete();
        }

        $team_code = session()->get('team_code');
        $request->session()->flush();
        // return view('teacher_login');
        // view가 아니라 페이지 이동을 해야함.
        if($team_code == '8888888'){
            return redirect('/login/neulbom');
        }else
            return redirect('/login');
    }

    // 첫 로그인 페이지.(방과후)
    public function afterFirstLogin(){
        // session team_code 가져오기
        $team_code = session()->get('team_code');
        $teachers = \App\Teacher::
        select(
                'id',
                'teach_name',
                'teach_phone',
                'teach_address',
                'teach_email'
        )
        ->where('team_code', $team_code)
        ->where('is_first_login', 'Y')
        ->get();
        //방과후 첫 로그인 페이지.
        return view('teacher.teacher_after_first_login', ['teachers' => $teachers]);
    }

    // 첫로그인 페이지.(비방과)
    public function firstLogin(){
        $teach_seq = session()->get('teach_seq');
        $teachers = \App\Teacher::find($teach_seq);
        return view('teacher.teacher_first_login', ['teachers' => $teachers]);
    }

    // 첫 로그인 페이지 > 선생님 정보 저장.
    public function afterFirstLoginInsert(Request $request){
        $teach_seq = $request->input('teach_seq');
        $teach_name = $request->input('teach_name');
        $teach_phone = $request->input('teach_phone');
        $teach_address = $request->input('teach_address');
        $teach_email = $request->input('teach_email');

        $teach_id = $request->input('teach_id');
        $teach_pw = $request->input('teach_pw');
        $is_auth_phone = $request->input('is_auth_phone');
        $is_auth_email = $request->input('is_auth_email');

        // 수업관련
        $study_start_date = $request->input('study_start_date');
        $study_end_date = $request->input('study_end_date');
        $study_time_bundle = $request->input('study_time_bundle'); //배열

        // 아이디 체크. 혹시 억지로 스크립트를 넘어올경우.

        //teach_pw가 있는 경우 password 로 암호화 해준다.
        if($teach_pw){
            $pw = DB::table('menus')
                ->select(DB::raw('SHA1(?) as pw'))
                ->setBindings([$teach_pw])
                ->first();
            $password = $pw->pw;
            $teach_pw = $password;
        }


        $teacher = \App\Teacher::find($teach_seq);
        $teacher->teach_name = $teach_name;
        $teacher->teach_phone = $teach_phone;
        $teacher->teach_address = $teach_address;
        $teacher->teach_email = $teach_email;
        $teacher->is_first_login = 'N';

        if($teach_id) $teacher->teach_id = $teach_id;
        if($teach_pw) $teacher->teach_pw = $teach_pw;
        if($is_auth_phone) $teacher->is_auth_phone = $is_auth_phone;
        if($is_auth_email) $teacher->is_auth_email = $is_auth_email;

        $teacher->save();

        // 세션 수정
        session()->put('is_first_login', 'N');
        session()->put('teach_name', $teach_name);
        session()->put('teach_phone', $teach_phone);
        session()->put('teach_address', $teach_address);
        session()->put('teach_email', $teach_email);

        if($teach_id) session()->put('teach_id', $teach_id);

        // select *from teachers
        // [추가 코드]
        // 선생님의 방과후 시간을 저장.? 일단 기획자와 이야기.

        // 결과
        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }
}
