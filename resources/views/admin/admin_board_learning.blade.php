@extends('layout.layout')

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<div class="col-12 pe-3 ps-3 position-relative">
    {{-- 게시판 이름--}}
    @section('board_name', 'learning')
    {{-- @section('writer', '학부모 이름') --}}
    @section('board_page_max', '14')
    @section('write', '자료등록')
    @section('is_use', '사용')
    @section('is_use_Y', '활성화')
    @section('is_use_M', '비활성화')

    @section('major_unit', $codes_all->where('function_code', 'major_unit')->first()['code_name'] ?? '대단원')
    @section('medium_unit', $codes_all->where('function_code', 'medium_unit')->first()['code_name'] ?? '중단원')
    {{-- 게시판 타입지정 --}}
    @include('layout.board_list')
</div>
@endsection

