<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
class LoginController extends Controller
{
    public function login(Request $request){
        $is_not_login = $request->input('is_not_login');
        $rt_code = $request->input('rt_code')??'';
        if($is_not_login == 'true'){
            return view('layout.login', ['is_not_login' => true, 'rt_code' => ($rt_code)]);
        }else
            return view('layout.login');
    }
    public function loginCheck(Request $request){
        $id = $request->input('id');
        $password = $request->input('password');
        $is_neulbom = $request->input('is_neulbom');
        $login_type = '';
        //우선 세션 초기화.
        $request->session()->flush();

        $user = null;

        // 늘봄 따로 로그인 체크.
        if($is_neulbom == 'Y'){
            $user = \App\Teacher::where('teach_id', $id)
                ->whereRaw('teach_pw = SHA1(?)', [$password])
                ->where('team_code', '8888888')
                ->first();
            $login_type = 'teacher';
        }else{
            // 중요순이 낮은 것부터 체크.
            // 학부모체크
            $user = \App\ParentTb::where('parent_id', $id)
                ->whereRaw('parent_pw = SHA1(?)', [$password])
                ->first();
            // 없을때, 학생 체크
            if($user == null && $login_type == ''){
                $user =  \App\Student::where('student_id', $id)
                    ->whereRaw('student_pw = SHA1(?)', [$password])
                    ->first();
                if($user != null){
                    $login_type = 'student';
                }
            }else{
                $login_type = 'parent';
            }

            // 없을때, 선생님 체크
            if($user == null && $login_type == ''){
                $user = \App\Teacher::where('teach_id', $id)
                    ->whereRaw('teach_pw = SHA1(?)', [$password])
                    ->first();
                if($user != null){
                    if($user['team_code'] == 'maincd'){
                        $login_type = '';
                    }else
                    $login_type = 'teacher';
                }
            }
        }


        $login_url = '/login';
        if($is_neulbom == 'Y'){
            $login_url = '/login/neulbom';
        }

        if($user == null){
            return redirect($login_url.'?is_not_login=true');
        }else{
            // 만약 사용안함 이면,
            // 리턴.
            if($user->is_use == 'N'){
                return redirect($login_url.'?is_not_login=true&rt_code=no_use');
            }

            // 선생님이면서, 소속이 없을겨우
            if($login_type == 'teacher' && !$user->region_seq){
                return redirect($login_url.'?is_not_login=true&rt_code=no_region');
            }
            // 기존 세션이 있다면 삭제
            \App\Sessions::where('user_id', $user->id)
                ->where('login_type', $login_type)
                ->delete();

            // 새로운 세션 생성
            // $session = new \App\Sessions();
            // $session->user_id = $user->id;
            // $session->login_type = $login_type;
            // $session->session_id = session()->getId();
            // $session->ip_address = $request->ip();
            // $session->user_agent = $request->header('User-Agent');
            // session()->put('user_id', $user->id);
            // session()->put('login_type', $login_type);
            // $session->payload = json_encode(session()->all());
            // $session->last_activity = now()->timestamp; // 정수형 timestamp로 저장
            // $session->save();

            // 기존 세션 설정
            $redirect_url = $this->setLoginSession($login_type, $user, $is_neulbom);
            return redirect($redirect_url);
        }

    }

    private function setLoginSession($login_type, $user, $is_neulbom){
        $redirect_url = '';
        if($login_type == 'parent'){
            session()->put('parent_seq', $user->id);
            session()->put('team_code', $user->team_code);
            session()->put('parent_id', $user->parent_id);
            session()->put('parent_name', $user->parent_name);
            session()->put('group_seq', $user->group_seq);
            session()->put('region_seq', $user->region_seq);
            session()->put('login_type', 'parent');
            setcookie('login_type', 'parent', time() + (86400 * 30), "/");
            session()->put('main_code', $user->main_code);
            setcookie('main_code', $user->main_code, time() + (86400 * 30), "/");
            $redirect_url = '/parent/index';
        }
        else if($login_type == 'student'){
            session()->put('student_seq', $user->id);
            session()->put('team_code', $user->team_code);
            session()->put('student_id', $user->student_id);
            // session()->put('student_pw', $password);
            session()->put('student_name', $user->student_name);
            session()->put('group_seq', $user->group_seq);
            session()->put('region_seq', $user->region_seq);
            session()->put('login_type', 'student');
            setcookie('login_type', 'student', time() + (86400 * 30), "/");
            session()->put('main_code', $user->main_code);
            setcookie('main_code', $user->main_code, time() + (86400 * 30), "/");

            $user->last_login_date = date('Y-m-d H:i:s');
            $user->login_cnt = $user->login_cnt + 1;
            $user->save();

            // TODO: 추후 로그인 점수 확인필요.
            // 하루에 로그인시 계속 주는지에 대해서도 확인 필요.
            \App\PointHistory::create([
                'student_seq' => $user->id,
                'point' => 5,
                'remark' => '로그인',
                'point_type' => 'login',
                'point_category' => 'activity',
            ]);
            $redirect_url = '/student/main';
        }
        else if($login_type == 'teacher'){
            $team_code = $user->team_code;
            session()->put('teach_seq', $user->id);
            session()->put('team_code', $team_code);
            session()->put('teach_id', $user->teach_id);
            // session()->put('teach_pw', $password);
            session()->put('teach_type', $user->teach_type);
            session()->put('teach_name', $user->teach_name);
            session()->put('group_seq', $user->group_seq);
            session()->put('region_seq', $user->region_seq);
            session()->put('team_code', $user->team_code);
            session()->put('login_type', 'teacher');
            session()->put('team_type', $user->team_type);
            session()->put('is_first_login', $user->is_first_login);
            session()->put('team_name', $user->team_name);
            session()->put('teach_phone', $user->teach_phone);
            session()->put('teach_email', $user->teach_email);
            session()->put('teach_address', $user->teach_address);

            setcookie('login_type', 'teacher', time() + (86400 * 30), "/");
            session()->put('main_code', $user->main_code);
            setcookie('main_code', $user->main_code, time() + (86400 * 30), "/");


            $team_area = \App\TeamArea::where('team_code', $team_code)->first();
            session()->put('team_area_sido', $team_area->tarea_sido ?? '');
            session()->put('team_area_gu', $team_area->tarea_gu ?? '');
            session()->put('team_area_dong', $team_area->tarea_dong ?? '');

            $regions = \App\Region::where('id', $user->region_seq)->first();
            session()->put('region_name', $regions->region_name);

            $is_teach_first_login = false;
            // 방과후인지 아닌지 체크.
            if($user->team_type == 'after_school'){
                //방과후이면서 첫 로그인인지 확인.
                if($user->is_first_login == 'Y'){
                    //첫 로그인이면서 방과후이면서 지정 페이지로 이동.
                    session()->put('is_first_login', 'Y');
                    // return redirect('/teacher/after/first/login');
                    $redirect_url = '/teacher/after/first/login';
                    $is_teach_first_login = true;
                }
            }
            // 방과후가 아닌 학원일경우.
            else{
                if($user->is_first_login == 'Y'){
                    //첫 로그인이면서 방과후가 아니면서 지정 페이지로 이동.
                    session()->put('is_first_login', 'Y');
                    // return redirect('/teacher/first/login');
                    $redirect_url = '/teacher/first/login';
                    $is_teach_first_login = true;
                }
            }

            //첫페이지가 있는 그룹일경우 지정 페이지로 이동.
            if(strlen($user->group_seq) > 0 && !$is_teach_first_login){
                $user_group = \App\UserGroup::where('id', $user->group_seq)->first();
                $group_name = $user_group->group_name;
                session()->put('group_name', $group_name);
                session()->put('group_type2', $user_group->group_type2);
                session()->put('group_type3', $user_group->group_type3);
                $first_page = $user_group->first_page;
                $menu = \App\Menu::where('id', $first_page)->first();
                $menu_url = $menu->menu_url ?? '';
                // 지정 첫페이지가 있을경우 지정페이지로 이동.
                if(strlen($menu_url) > 0){
                    session()->put('menu_url', $menu_url);
                    $redirect_url = $menu_url;
                }else{
                    $redirect_url = '/teacher/main';
                }
            }
            //아니면 기존 메인 페이지로 이동.
            else{
                // 선생님 첫 로그인이 아닐경우에만.
                if(!$is_teach_first_login) $redirect_url = '/teacher/main';
            }
        }
    //     else if($login_type == 'admin'){
    //         $team_code = $user->team_code;
    //         session()->put('teach_seq', $user->id);
    //         session()->put('team_code', $team_code);
    //         session()->put('teach_id', $user->teach_id);
    //         // session()->put('teach_pw', $password);
    //         session()->put('teach_type', $user->teach_type);
    //         session()->put('teach_name', $user->teach_name);
    //         session()->put('group_seq', $user->group_seq);
    //         session()->put('region_seq', $user->region_seq);
    //         session()->put('team_code', $user->team_code);
    //         session()->put('login_type', 'admin');
    //         // cookie('login_type', 'admin', 60*24);
    //         setcookie('login_type', 'admin', time() + (86400 * 30), "/");
    //
    //         //첫페이지가 있는 그룹일경우 지정 페이지로 이동.
    //         if(strlen($user->group_seq) > 0){
    //             $user_group = \App\UserGroup::where('id', $user->group_seq)->first();
    //             $group_name = $user_group->group_name;
    //             session()->put('group_name', $group_name);
    //             session()->put('group_type2', $user_group->group_type2);
    //             $first_page = $user_group->first_page;
    //             $menu = \App\Menu::where('id', $first_page)->first();
    //             $menu_url = $menu->menu_url ?? '';
    //             if(strlen($menu_url) > 0){
    //                 session()->put('menu_url', $menu_url);
    //                 // return redirect($menu_url);
    //                 $redirect_url = $menu_url;
    //             }else{
    //                 // return redirect('/manage/main');
    //                 $redirect_url = '/manage/main';
    //             }
    //         }
    //         //아니면 기존 메인 페이지로 이동.
    //         else{
    //             // return redirect('/manage/main');
    //             $redirect_url = '/manage/main';
    //         }
    //     }

        // 세션 테이블에 데이터 저장
        try {
            $session_id = session()->getId();
            $existing_session = \App\Sessions::where('session_id', $session_id)->first();

            if (!$existing_session) {
                $session = new \App\Sessions();
                $session->user_id = $user->id;
                $session->login_type = $login_type;
                $session->session_id = $session_id;
                $session->ip_address = request()->ip();
                $session->user_agent = request()->header('User-Agent');
                $session->payload = Crypt::encrypt(json_encode(session()->all()));
                $session->last_activity = now()->timestamp;
                $session->save();
            }
        } catch (\Exception $e) {
            // 예외 처리 로직
            Log::error('세션 저장 중 오류 발생: ' . $e->getMessage());
            session()->flush();
            return redirect('/logout')->with('error', '세션 저장 중 오류 발생: ' . $e->getMessage());
        }
        return $redirect_url;
    }

    // 로그아웃 메소드 추가
    public function logout(Request $request)
    {
        // 현재 세션 정보 가져오기
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

        // 쿠키 삭제
        setcookie('login_type', '', time() - 3600, "/");
        setcookie('main_code', '', time() - 3600, "/");

        $team_code = session()->get('team_code');
        $login_type = session()->get('login_type');

        // 세션 파기
        $request->session()->flush();

        if($team_code == '8888888' && $login_type == 'teacher'){
            return redirect('/login/neulbom');
        }
        return redirect('/login');
    }


    public function registerNeulbom(Request $request){
        return view('layout.register_neulbom');
    }
    public function insertNeulbom(Request $request){
            $id = $request->input('id');
            $password = $request->input('password');
            $password_check = $request->input('password_check');
            $name = $request->input('name');
            $phone = $request->input('phone');
            $email = $request->input('email');
            $agree_terms = $request->input('agree_terms') ? 'Y' : 'N';
            $agree_privacy = $request->input('agree_privacy') ? 'Y' : 'N';
            $marketing_agree = $request->input('marketing_agree') ? 'Y' : 'N';

            $teacher = new \App\Teacher;
            $teacher->main_code = 'elementary';
            $teacher->group_seq = 36;
            $teacher->area = '서울특별시';
            $teacher->region_seq = 43;
            $teacher->team_code = '8888888';
            $teacher->attend_key = 'NULL';
            $teacher->teach_id = $id;
            $teacher->teach_name = $name;
            $teacher->teach_pw = sha1($password);
            $teacher->teach_email = $email;
            $teacher->teach_phone = $phone;
            $teacher->teach_attend = '14:00:00';
            $teacher->teach_attend_end = '14:00:00';
            $teacher->teach_status = 'Y';
            $teacher->teach_status_str = '재직';
            $teacher->is_use = 'Y';
            $teacher->is_first_login = 'N';
            $teacher->is_auth_phone = 'Y';
            $teacher->is_auth_email = 'Y';
            $teacher->is_service_agree = $agree_terms;
            $teacher->is_personal_agree = $agree_privacy;
            $teacher->is_advertising_agree = $marketing_agree;
            $teacher->service_agree_date = date('Y-m-d');
            $teacher->personal_agree_date = date('Y-m-d');
            $teacher->advertising_agree_date = date('Y-m-d');
            $teacher->save();
            // 리다이렉트 login_neulbom
            return redirect('/login/neulbom?is_join=Y');
    }

    public function loginNeulbom(Request $request){
        $is_join = $request->input('is_join');
        $is_not_login = $request->input('is_not_login');
        $rt_code = $request->input('rt_code')??'';


        if($is_not_login == 'true'){
            return view('layout.login_neulbom', ['is_not_login' => true, 'rt_code' => ($rt_code), 'is_join' => $is_join]);
        }else
            return view('layout.login_neulbom', ['is_join' => $is_join]);
    }

    // public function registerNeulbomCheck(Request $request){
    //     $validator = Validator::make($request->all(), [
    //         'teach_id' => 'required|string|max:255|unique:teachers,teach_id',
    //         'teach_pw' => 'required|string|min:6',
    //         'teach_name' => 'required|string|max:255',
    //         'teach_phone' => 'required|string|max:20',
    //         'teach_email' => 'required|email|max:255|unique:teachers,teach_email',
    //     ]);

    //         // 유효성 검사 실패 시 응답
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'errors' => $validator->errors()
    //         ], 422);
    //     }
    //     $teach_pw = $request->input('teach_pw');
    //     $teach_id = $request->input('teach_id');
    //     $teach_name = $request->input('teach_name');
    //     $teach_phone = $request->input('teach_phone');
    //     $teach_email = $request->input('teach_email');
    //     $is_auth_phone = "Y";

    //     $user = \App\Teacher::create([
    //         'teach_id' => $teach_id,
    //         'teach_pw' => SHA1($teach_pw),
    //         'teach_name' => $teach_name,
    //         'teach_phone' => $teach_phone,
    //         'teach_email' => $teach_email,
    //         'main_code' => "elementary",
    //         'team_code' => '8888888',
    //         'group_seq' => 36,
    //         'region_seq' => 43,
    //         'is_first_login' => "N",
    //     ]);

    //     $user->save();

    //     return redirect('/login')->with('status', '회원가입이 완료되었습니다.');
    // }

}
