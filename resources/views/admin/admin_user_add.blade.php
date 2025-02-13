@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
    사용자 등록
@endsection

{{-- 네브바 체크 --}}
@section('user_management')
@endsection
@section('useradd')
    active
@endsection

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    @include('admin.admin_user_add_detail')
@endsection
