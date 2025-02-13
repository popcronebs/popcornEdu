{{-- 세션에 teach_id와 teach_type 이 없으면 로그인 페이지로 이동 -- 아니면 메인 페이지로 이동. --}}
@if (!session()->has('teach_id'))
    @php
    if (strpos($_SERVER['REQUEST_URI'], 'login') === false) {
        if(isset($_COOKIE['login_type']) &&  $_COOKIE['login_type'] == 'admin')
            header('Location: /login');
        else
            header('Location: /login');
        exit;
    }
    @endphp
@endif

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('css/bootstrap.css?8') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

    <link href="{{ asset('css/admin_style.css?43') }}" rel="stylesheet">
    <link href="{{ asset('css/mainstyle.css?15') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('font/font.css') }}">
    <link rel="stylesheet" href="{{ asset('css/iconfont.css') }}">
    <link rel="stylesheet" href="{{ asset('css/colors-system.css?2') }}">
    <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/static/pretendard.min.css" />
    <!-- 추가한스타일 -->

    <link href="{{ asset('css/reset.css?5') }}" rel="stylesheet">

    <script src="{{ asset('js/admin_script.js?27') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.5/xlsx.full.min.js"></script>
    <script src="{{ asset('js/common.js') }}"></script>
    {{-- 각페이지에서만 필요한 css,js.--}}
    @yield('add_css_js')

    <title>@yield('head_title', '서당 EBS')</title>
</head>

<body>
    <input type="hidden" id="csrf_token" value="{{ csrf_token() }}" />
    <div id="toast" style=" font-size: 1.5rem; padding: 1.1rem; "></div>
