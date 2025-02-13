{{-- 세션에 teach_id와 teach_type 이 없으면 로그인 페이지로 이동 -- 아니면 메인 페이지로 이동. --}}
@if (!session()->has('teach_id') || !session()->has('teach_type'))
    @php
    if (strpos($_SERVER['REQUEST_URI'], 'manage/login') === false) {

        if(isset($_COOKIE['login_type']) && $_COOKIE['login_type'] == 'teacher')
            header('Location: /teacher/login');
        else
            header('Location: /manage/login');
        
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.5/xlsx.full.min.js"></script>
    <link href="{{ asset('css/admin_style.css?5') }}" rel="stylesheet">
    <script src="{{ asset('js/admin_script.js?16') }}"></script>
    <title>@yield('head_title', '서당 EBS')</title>
</head>
<style>
@font-face {
    font-family: 'Pretendard';
    src: url('https://cdn.jsdelivr.net/gh/Project-Noonnu/noonfonts_2107@1.1/Pretendard-Regular.woff') format('woff');
    font-weight: 400;
    font-style: normal;
}

body {
    font-family: 'Pretendard', sans-serif;
}
</style>

<body>
    <input type="hidden" id="csrf_token" value="{{ csrf_token() }}" />
    <div id="toast" style=" font-size: 1.5rem; padding: 1.1rem; "></div>