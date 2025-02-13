<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GoodsMTController extends Controller
{
    //
    public function goods(){
        $main_code = $_COOKIE['main_code'];
        $user_groups = \App\UserGroup::where('group_type', 'like', '%student%')
        ->where('main_code', 'like', '%'.$main_code.'%')
        ->get();
        return view('admin.admin_goods', ['user_groups' => $user_groups]);
    }

    // 상품(이용권) 리스트
    public function goodsSelect(Request $request){
        $search_str = $request->input('search_str');
        $main_code = $_COOKIE['main_code'];
        //main_code 를 따라 조회 나뉨조건.
        $goods = \App\Goods::where('goods_name', 'like', '%'.$search_str.'%');
        $goods = $goods->where('goods.main_code', $main_code);
        //left join user_groups on goods.group_seq = user_groups.id
        $goods = $goods->select('goods.*', 'user_groups.group_name as group_name');
        $goods = $goods->leftJoin('user_groups', 'goods.group_seq', '=', 'user_groups.id');
        $goods = $goods->get();

        $result = array();
        $result['resultCode'] = 'success';
        $result['goods'] = $goods;
        return response()->json($result);
    }

    // 상품(이용권) 등록
    public function goodsInsert(Request $request){
        $id = $request->input('id');
        $goods_name = $request->input('goods_name');
        $remark = $request->input('remark');
        $goods_period = $request->input('goods_period');
        $goods_grade = $request->input('goods_grade');
        $group_seq = $request->input('group_seq');
        $goods_price = $request->input('goods_price');
        $is_use = $request->input('is_use');
        $is_auto_pay = $request->input('is_auto_pay');
        $main_code = $_COOKIE['main_code'] ?? $request->input('main_code');

        // id 값이 있으면 수정, 없으면 등록
        if($id){ $goods = \App\Goods::find($id); }
        else{ $goods = new \App\Goods; }

        $goods->goods_name = $goods_name;
        $goods->goods_period = $goods_period;
        $goods->goods_grade = $goods_grade;
        $goods->goods_price = $goods_price;
        $goods->group_seq = $group_seq;
        $goods->remark = $remark;
        $goods->is_use = $is_use;
        $goods->is_auto_pay = $is_auto_pay;
        $goods->main_code = $main_code;
        $goods->save();

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

    // 상품(이용권) 삭제
    public function goodsDelete(Request $request){
        $id = $request->input('id');

        $goods = \App\Goods::find($id);
        $goods->delete();

        $result = array();
        $result['resultCode'] = 'success';
        return response()->json($result);
    }

}
