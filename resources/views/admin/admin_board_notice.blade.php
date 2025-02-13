@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title', '공지사항')

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<div class="col-12 pe-3 ps-3 position-relative">
    {{-- 공지사항 / 자주묻는 질문 버튼 --}}
    <div {{ $login_type == 'admin' ? '':'hidden' }}>
        <div id="" class="flex-wrap btn-group col-4">
            <button class="btn btn-primary rounded-0"> 공지사항 </button>
            <button class="btn btn-outline-primary rounded-0" onclick="openWindow('boardfaq')"> 자주 묻는 질문</button>
            <button class="btn btn-outline-primary rounded-0" onclick="openWindow('boardqna')"> 시스템 / 사용문의 </button>
        </div>
    </div>
    <div class="sub-title row mx-0 justify-content-between" data-board-main {{ $login_type == 'admin' ? 'hidden':'' }}>
        <h2 class="text-sb-42px px-0">
            <img src="{{ asset('images/big_bell_icon.svg') }}" width="72">
            <span class="me-2">공지사항</span>
        </h2>
    </div>

    {{-- 게시판 이름--}}
    @section('board_name', 'notice')
    @section('board_page_max', '6')
    @section('write_date', '등록일')
    @section('search_word', '공지사항을 검색해보세요.')
    @section('write', '게시글 작성')
    {{-- 게시판 / 공지사항 --}}
    @include('layout.board_list')


</div>
@endsection

