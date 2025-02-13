<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemAdminMTController extends Controller
{
    //관리자 계정 관리 / 목록
    public function admin()
    {
        $result1 = $this->adminlist(new Request());
        $teachers = $result1->getData()->teachers;
        $result2 = $this->usergroup();
        $usergroups = $result2->getData()->user_groups;
        return view('admin.admin_system_admin', compact('teachers', 'usergroups'));
    }

    //관리자 계정 리스트 가져오기.
    public function adminlist(Request $request)
    {
        $search_type = $request->search_type;
        $search_str = $request->search_str;

        //관리자 계정 리스트 가져오기.
        $teachers = \App\Teacher::select('teachers.*', 'user_groups.group_name', 'user_groups.first_page')
            ->leftJoin('user_groups', 'teachers.group_seq', '=', 'user_groups.id')
            ->where('teachers.team_code', 'maincd');

        //검색 조건에 따른 검색
        if ($search_type == 'teach_id') {
            $teachers = $teachers->where('teachers.teach_id', 'like', '%' . $search_str . '%');
        } else if ($search_type == 'teach_name') {
            $teachers = $teachers->where('teachers.teach_name', 'like', '%' . $search_str . '%');
        } else if ($search_type == 'group_name') {
            $teachers = $teachers->where('user_groups.group_name', 'like', '%' . $search_str . '%');
        }

        $teachers = $teachers->orderBy('teachers.id', 'asc')->get();
        $result = array();
        $result['teachers'] = $teachers;
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }

    //유저그룹 가져오기.
    public function usergroup()
    {
        // 전체 가져오기
        $user_groups = \App\UserGroup::select('user_groups.*')->where('group_type', 'admin')->get();
        $result = array();
        $result['user_groups'] = $user_groups;
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }

    //관리자 계정 등록 / 수정
    public function insert(Request $request){
        $teach_seq = $request->teach_seq;
        $teach_id = $request->teach_id;
        $teach_name = $request->teach_name;
        $teach_pw1 = $request->teach_pw1;
        $teach_pw2 = $request->teach_pw2;
        $teach_phone = $request->teach_phone;
        $teach_phone2 = $request->teach_phone2;
        $teach_status = $request->teach_status;
        $group_seq = $request->group_seq;
        $first_page = $request->first_page;
        //아이디 중복 체크 // 공백 체크
        $idchk = $this->teachidchk($request);
        if($idchk->getData()->resultCode == 'fail' && $teach_seq == ''){
            return $idchk;
        }

        //teach_seq 있으면 수정. 없으면 생성.
        if($teach_seq == '') $teacher = new \App\Teacher;
        else $teacher = \App\Teacher::find($teach_seq);
        //생성일때만 ID저장
        if($teach_seq == ''){
            $teacher->teach_id = $teach_id;
            $teacher->created_id = session()->get('id');
        }else{
            $teacher->updated_id = session()->get('id');
        }

        $teacher->teach_name = $teach_name;
        $teacher->teach_phone = $teach_phone;
        $teacher->teach_phone2 = $teach_phone2;

        if($teach_pw1 != '') $teacher->teach_pw = DB::raw("SHA1('".$teach_pw1."')");
        $teacher->teach_status = $teach_status;
        $teacher->is_use = $teach_status == 'Y' ? 'Y' : 'N';
        if($group_seq != '') $teacher->group_seq = $group_seq;
        $teacher->team_code = 'maincd';
        $teacher->teach_type = 'admin';
        $teacher->save();

        if(strlen($group_seq) > 0){
            $user_group = \App\UserGroup::find($group_seq);
            $user_group->first_page = $first_page;
            $user_group->updated_id = session()->get('id');
            $user_group->save();
        }

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }

    //아이디 중복 체크
    public function teachidchk(Request $request){
        $teach_id = $request->teach_id;
        //아이디 공백일때
        if($teach_id == ''){
            $result = array();
            $result['resultCode'] = 'fail';
            $result['resultMsg'] = '아이디를 입력해주세요.';
            return response()->json($result, 200);
        }
        //아이디 중복 체크
        $teach_id_chk = \App\Teacher::select('teachers.*')->where('teach_id', $teach_id)->get();
        if(count($teach_id_chk) > 0){
            $result = array();
            $result['resultCode'] = 'fail';
            $result['resultMsg'] = '이미 등록된 아이디입니다.';
            return response()->json($result, 200);
        }else{
            $result = array();
            $result['resultCode'] = 'success';
            return response()->json($result, 200);
        }
    }

    //관리자 계정 삭제
    public function delete(Request $request){
        $teach_seq = $request->teach_seq;
        //teach_seq = '$teach_seq', team_code = 'maincd' 삭제
        $teacher = \App\Teacher::where('id', $teach_seq)->where('team_code', 'maincd')->first();
        $teacher->delete();

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }

    //그룹에 속한 페이지(메뉴) 정보 가져오기
    public function menuSelect(Request $request){
        $group_seq = $request->group_seq;
        $menus = \App\Menu::select('menus.*', 'menu_groups.group_seq', 'menu_groups.menu_type', 'menu_groups.menu_idx', 'menu_groups.menu_pt_seq', 'menu_groups.is_use as group_is_use')
        ->leftJoin('menu_groups', 'menus.id', '=', 'menu_groups.menu_seq')
        ->where('menu_groups.group_seq', $group_seq)
        ->where(function($query){
            $query->where('menu_groups.menu_type', '<>', 'top')
            ->Where('menus.is_folder', '<>', 'Y');
        });
        //폴더메뉴와, 상위메뉴는 제외

        $menus = $menus
            ->orderBy('menu_type', 'desc')
            ->orderBy('menu_idx', 'asc')
            ->orderBy('menus.id', 'asc')
            ->get();

        $result = array();
        $result['menus'] = $menus;
        $result['resultCode'] = 'success';
        return response()->json($result, 200);
    }

    //관리자 그룹 추가.
    public function groupinsert(Request $request){
        $group_name = $request->group_name;
        $group_type = 'admin';
        $group_type2 = 'manage';
        $created_id = session()->get('teach_id');

        //그룹명 중복 체크
        $group_name_chk = \App\UserGroup::select('user_groups.*')->where('group_name', $group_name)->where('group_type', $group_type)->get();
        if(count($group_name_chk) > 0){
            $result = array();
            $result['resultCode'] = 'fail';
            $result['resultMsg'] = '이미 등록된 그룹명입니다.';
            return response()->json($result, 200);
        }

        //그룹 생성
        $user_group = new \App\UserGroup;
        $user_group->group_name = $group_name;
        $user_group->group_type = $group_type;
        $user_group->created_id = $created_id;
        $user_group->is_use = 'Y';
        $user_group->group_type2 = $group_type2;
        $user_group->save();

        $group_seq = $user_group->id;

        $result = array();
        $result['resultCode'] = 'success';
        $result['group_seq'] = $group_seq;
        return response()->json($result, 200);
    }
}
