@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title', '메뉴 관리')

{{-- 네브바 체크 --}}
@section('site_management', '')
@section('menu', 'active')
    
{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    {{-- user_groups, menu_urls 넘기기 --}}
    @include('admin.admin_site_menu_detail')
@endsection
