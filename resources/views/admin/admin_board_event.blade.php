@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title', '이벤트')

{{-- 네브바 체크 --}}
@section('board_management', '')
@section('boardevent', 'active')

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<div class="col-12 pe-3 ps-3 position-relative">
    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <h4>이벤트</h4>
    </div>
    {{-- 이벤트 --}}
    {{-- <div>
        <div id="" class="flex-wrap btn-group col-4">
            <button class="btn btn-primary rounded-0"> 이벤트 </button>
        </div>
    </div> --}}
    {{-- 게시판 이름--}}
    @section('board_name', 'event')
    @section('board_page_max', '6')
    
    {{-- 게시판 타입지정 --}}
    {{-- 게시판 / 이벤트 --}}
    @include('layout.board_list', ['board_type' => 'gallery'])
    

</div>
@endsection

