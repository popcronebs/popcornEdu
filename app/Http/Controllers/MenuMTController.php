<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MenuMTController extends Controller
{
    //
    public function menulist(){
        // //그룹 리스트 User_groups
        // $user_group = \App\UserGroup::select('*')->get();
        // $user_group = $user_group->toArray();

        // //메뉴 url 리스트 가져오기.
        // $menu_url = \App\MenuUrl::select('*')->get();

        // return view('admin.admin_site_menu', ['user_groups' => $user_group, 'menu_urls' => $menu_url]);
        return view('admin.admin_site_menu');
    }
    // 메뉴 등록
    public function insert(Request $request){
        $menu_name = $request->input('menu_name');
        $group_seq = $request->input('group_seq');
        // 메뉴 등록
        $menu = new \App\Menu;
        $menu->menu_name = $menu_name;
        $menu->save();

        $menu_seq = $menu->id;
        $menu_group = new \App\MenuGroup;
        $menu_group->menu_seq = $menu_seq;
        $menu_group->group_seq = $group_seq;

        // 첫 삽입은 무조건 nav로 진행.
        $menu_group->menu_type = 'nav';
        $menu_group->menu_idx = 0;

        //save
        $menu_group->save();

        return response()->json(['result' => 'success']);
    }
    // 메뉴 수정
    public function update(Request $request){
        $menu_seq = $request->input('menu_seq'); 
        $menu_name = $request->input('menu_name');
        $menu_url_code = $request->input('menu_url_code');
        $menu_url = $request->input('menu_url');
        $is_blank = $request->input('is_blank');
        $is_use = $request->input('is_use');
        $group_seq = $request->input('group_seq');
        $group_seqs = explode(',', $group_seq);

        // 메뉴 수정
        $menu = \App\Menu::find($menu_seq);
        $menu->menu_name = $menu_name;
        // folder, folder2 포함
        if($menu_url_code == 'folder' || $menu_url_code == 'folder2') $menu->is_folder = 'Y';
        else $menu->is_folder = 'N';
        $menu->menu_url_code = $menu_url_code;
        $menu->menu_url = $menu_url;
        $menu->is_blank = $is_blank;
        $menu->is_use = $is_use;
        $menu->save();

        // 메뉴 그룹이 $group_seqs 로 찾아서 없으면 만들고, 있으면 수정.
        if(strlen($group_seq) > 0){
            foreach($group_seqs as $key => $value){
                $menu_group = \App\MenuGroup::where('menu_seq', $menu_seq)->where('group_seq', $value)->first();
                if($menu_group == null){
                    $menu_group = new \App\MenuGroup;
                    $menu_group->menu_type = 'nav';
                    $menu_group->group_seq = $value;
                    $menu_group->menu_seq = $menu_seq;
                    $menu_group->menu_idx = 0;
                }
                $menu_group->is_use = $is_use;
                $menu_group->save();
            }
        }

        // 메뉴 그룹이 $group_seqs 에 없으면 삭제. 단 -1은 제외
        if(strlen($group_seq) > 0)
            $menu_group = \App\MenuGroup::where('menu_seq', $menu_seq)->whereNotIn('group_seq', $group_seqs)->where('group_seq', '!=', -1)->delete();
        // 권한 그룹이 아예 없으면 -1 제외 모두 삭제
        else
            $menu_group = \App\MenuGroup::where('menu_seq', $menu_seq)->where('group_seq', '!=', -1)->delete();

        return response()->json(['result' => 'success']);
    }
    
    // 메뉴 순서 변경
    public function idxupdate(Request $request){
        $group_seq = $request->input('group_seq'); 
        $menu_seqs = $request->input('menu_seqs');
        $idxs = $request->input('idxs');
        $menu_pt_seqs = $request->input('menu_pt_seqs');
        $menu_types = $request->input('menu_types');

        $menu_seqs = explode(',', $menu_seqs);
        $idxs = explode(',', $idxs);
        $menu_pt_seqs = explode(',', $menu_pt_seqs);
        $menu_types = explode(',', $menu_types);

        foreach($menu_seqs as $key => $value){
            $menu_group = \App\MenuGroup::where('menu_seq', $value)->where('group_seq', $group_seq)->first();
            //있으면 수정
            if($menu_group != null){
                $menu_group->menu_idx = $idxs[$key];
                $menu_group->menu_pt_seq = $menu_pt_seqs[$key]*1;
                $menu_group->menu_type = $menu_types[$key];
                $menu_group->save();
            }
        }

        return response()->json(['resultCode' => 'success']);
    }
    
    // 메뉴 삭제
    public function delete(Request $request){
        $menu_seq = $request->input('menu_seq'); 

        //삭제는 전체 메뉴에서만 되므로 group_seq 는 -1로 고정.

        //메뉴 하위 그룹 찾기 //group_seq = -1 and menu_pt_seq = menu_seq
        $under_menu_group = \App\MenuGroup::where('group_seq', -1)->where('menu_pt_seq', $menu_seq)->get();

        //찾은 하위 그룹 menu_seq를 가져와서 그룹 및 메뉴 모두 삭제.
        foreach($under_menu_group as $key => $value){
            $menu_group = \App\MenuGroup::where('menu_seq', $value->menu_seq)->delete();
            $menu = \App\Menu::find($value->menu_seq);
            $menu->delete();
        }

        // 선택한 메뉴 그룹 삭제
        $menu_group = \App\MenuGroup::where('menu_seq', $menu_seq)->delete();

        // 선택한 메뉴 삭제
        $menu = \App\Menu::find($menu_seq);
        $menu->delete();

        return response()->json(['resultCode' => 'success']);
    }

    // 메뉴 리스트 가져오기
    public function select(Request $request){
        $group_seq = $request->input('group_seq'); 
        $menu = \App\Menu::select('menus.*', 'menu_groups.group_seq', 'menu_groups.menu_type', 'menu_groups.menu_idx', 'menu_groups.menu_pt_seq', 'menu_groups.is_use as group_is_use')
        ->leftJoin('menu_groups', 'menus.id', '=', 'menu_groups.menu_seq')
        ->where('menu_groups.group_seq', $group_seq);
        $menu = $menu->orderBy('menu_idx', 'asc')->orderBy('menus.id', 'asc')->get();
        return response()->json(['resultCode' => 'success', 'menus' => $menu]);
    }

    // 해당 메뉴에속한 그룹 정보 가져오기. // 메뉴 정보 가져오기.
    public function groupselect(Request $request){
        $menu_seq = $request->input('menu_seq');
        $menu_group = \App\MenuGroup::select('*')->where('menu_seq', $menu_seq)->get();

        $menu = \App\Menu::find($menu_seq);

        return response()->json(['resultCode' => 'success', 'menu_groups' => $menu_group, 'menu' => $menu]);
    }

    // 메뉴 그룹 노출 변경
    public function groupupdate(Request $request)
    {
        $group_seq = $request->input('group_seq');
        $menu_seq = $request->input('menu_seq');
        $is_use = $request->input('is_use');

        $menu_group = \App\MenuGroup::where('group_seq', $group_seq)->where('menu_seq', $menu_seq)->first();
        $menu_group->is_use = $is_use;
        $menu_group->save();

        return response()->json(['resultCode' => 'success']);
    }
}
