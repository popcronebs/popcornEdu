<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventMtController extends Controller
{
    public function list(){
        $main_code = session()->get('main_code') ?? $_COOKIE['main_code'];
;
        $boards = \App\Board::
        leftJoin('board_uploadfiles', function($join){
            $join->on('boards.id', '=', 'board_uploadfiles.board_seq')
            ->where('board_uploadfiles.bdupfile_type', '=', 'thumbnail');
        })
        ->select('boards.*', 'board_uploadfiles.bdupfile_path')
        ->where('boards.board_name', 'event')
        ->where('boards.main_code', $main_code)
        ->orderBy('boards.id', 'desc')
        ->get();

        return view('student.student_event', ['boards' => $boards]);
    }
}
