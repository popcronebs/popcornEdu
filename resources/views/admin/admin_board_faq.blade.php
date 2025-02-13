@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title', '자주 묻는 질문')

{{-- 네브바 체크 --}}
@section('board_management', '')
@section('boardfaq', 'active')

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<div class="col-12 pe-3 ps-3 position-relative">
    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <h4>자주 묻는 질문</h4>
    </div>
    {{-- 공지사항 / 자주묻는 질문 버튼 --}}
    <div>
        <div id="" class="flex-wrap btn-group col-4">
            <button class="btn btn-outline-primary rounded-0" onclick="openWindow('boardnotice')"> 공지사항 </button>
            <button class="btn btn-primary rounded-0" > 자주 묻는 질문</button>
            <button class="btn btn-outline-primary rounded-0" onclick="openWindow('boardqna')"> 시스템 / 사용문의 </button>
        </div>
    </div>
    {{-- 게시판 이름--}}
    @section('board_name', 'faq')
    {{-- 게시판 / 공지사항 --}}
    @include('layout.board_list')
    

</div>
@endsection

