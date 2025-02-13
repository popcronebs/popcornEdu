<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NoticeMtController extends Controller
{
    public function list(Request $request){
        $nc = $request->input('nc');
        $main_code = $request->session()->get('main_code');
        // select *from codes where code_category = 'board' and function_code in ('notice', 'faq')
        $codes = \App\Code::where('code_category', 'board')
                ->whereIn('function_code', ['notice', 'faq'])
                ->where('code_step', '1')
                ->where('main_code', $main_code)
                ->get();

        $notice = $codes->where('function_code', 'notice')->first();
        $faq = $codes->where('function_code', 'faq')->first();

        $board_faqs = \App\Board::where('board_name', 'faq')
        ->addSelect('boards.*')
        ->addSelect('category.code_name as category_name')
        ->leftJoin('codes as category', function ($join) {
            $join->on('category.id', '=', 'boards.category');
        })
        ->orderBy('category.code_idx', 'asc')
        ->get();

        return view('student.student_notice', [
            'notice'=>$notice,
            'faq'=>$faq,
            'board_faqs'=>$board_faqs,
            'nc'=>$nc
        ]);
    }
}
