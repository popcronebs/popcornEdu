<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserCounselMTController extends Controller
{
    //list
    public function list(){
        $main_code = $_COOKIE['main_code'];
        //소속 가져오기
        $regions = \App\Region::where('main_code', $main_code)->get();
        return view('admin.admin_user_counsel', ['regions' => $regions]);
    }
}
