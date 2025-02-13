<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //index
    public function main(){
        return view('admin.admin_main');
    }
    //post team_code, id, password 를 받아서 db에 있는지 확인
    //login
    public function login(Request $request){
        // main_code 쿠키 없으면 'elementary'를 넣어줌.
        if(!isset($_COOKIE['main_code'])){
            setcookie('main_code', 'elementary', time() + (86400 * 30), "/");
        }

        $team_code = $request->input('team_code');
        $teach_id = $request->input('id');
        $password = $request->input('password');
        $is_not_login = $request->input('is_not_login');

        //post 값이 있으면
        if($team_code != null && $teach_id != null && $password != null){
            //password 를 SHA1()로 암호화
            $password = DB::raw("SHA1('".$password."')");
            $users = \App\Teacher::where('team_code', $team_code)->where('teach_id', $teach_id)->where('teach_pw', $password)->get();
            //선생님이 있으면 세션 체우기.
            if(count($users) > 0 && $team_code == 'maincd'){
                // return view('admin');
                $request->session()->put('teach_seq', $users[0]->id);
                $request->session()->put('team_code', $team_code);
                $request->session()->put('teach_id', $teach_id);
                $request->session()->put('teach_pw', $password);
                $request->session()->put('teach_type', $users[0]->teach_type);
                $request->session()->put('teach_name', $users[0]->teach_name);
                $request->session()->put('group_seq', $users[0]->group_seq);
                $request->session()->put('region_seq', $users[0]->region_seq);
                $request->session()->put('team_code', $users[0]->team_code);
                $request->session()->put('login_type', 'admin');
                // cookie('login_type', 'admin', 60*24);
                setcookie('login_type', 'admin', time() + (86400 * 30), "/");

                //첫페이지가 있는 그룹일경우 지정 페이지로 이동.
                if(strlen($users[0]->group_seq) > 0){
                    $user_group = \App\UserGroup::where('id', $users[0]->group_seq)->first();
                    $group_name = $user_group->group_name;
                    $request->session()->put('group_name', $group_name);
                    $request->session()->put('group_type2', $user_group->group_type2);
                    $first_page = $user_group->first_page;
                    $menu = \App\Menu::where('id', $first_page)->first();
                    $menu_url = $menu->menu_url ?? '';
                    if(strlen($menu_url) > 0){
                        $request->session()->put('menu_url', $menu_url);
                        return redirect($menu_url);
                    }else{
                        return redirect('/manage/main');
                    }
                }
                //아니면 기존 메인 페이지로 이동.
                else{
                    return redirect('/manage/main');
                }
            }
            else{
                //비우기
                $request->session()->flush();
                //결과값을 is_not_login = true 을 admin_login에 넘겨주기.
                // return view('admin.admin_login', ['is_not_login' => true]);
                return redirect('/manage/login?is_not_login=true');
            }
        }
        //session 의 teach_id 에 값이 있고, session의 login_type 이 admin이면
        else if($request->session()->has('teach_id') && $request->session()->get('login_type') == 'admin'){
            //세션에 menu_url 이 있으면
            if($request->session()->has('menu_url')){
                //menu_url로 이동
                return redirect($request->session()->get('menu_url'));
            }
            return redirect('/manage/main');
        }
        if($is_not_login == 'true'){
            return view('admin.admin_login', ['is_not_login' => true]);
        }
        else
            return view('admin.admin_login');
    }
    //logout
    public function logout(Request $request){
        $request->session()->flush();
        return redirect('/manage');
    }
}
