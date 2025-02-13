<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentMTController extends Controller
{
    //
    public function list(){
        return view('admin.admin_payment');
    }

    // 학부모 결제리스트
    public function parentList(Request $request){
        return view('parent.parent_payment');
    }
}

