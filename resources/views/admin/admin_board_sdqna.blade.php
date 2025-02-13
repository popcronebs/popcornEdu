@extends('layout.layout')

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<div class="col-12 pe-3 ps-3 position-relative">
    {{-- 게시판 이름--}}
    @section('board_name', 'sdqna')
    {{-- @section('writer', '학부모 이름') --}}
    @section('board_page_max', '14')
    
    {{-- 게시판 타입지정 --}}
    @include('layout.board_list')
</div>
@endsection

