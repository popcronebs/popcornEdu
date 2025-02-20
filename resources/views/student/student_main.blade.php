@extends('layout.layout')

@section('add_css_js')
<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="{{ asset('js/owl.js') }}"></script>
<link href="{{ asset('css/main_page.css') }}" rel="stylesheet">
<link href="{{ asset('css/owl1.css') }}" rel="stylesheet">
<link href="{{ asset('css/owl2.css') }}" rel="stylesheet">
@endsection

{{-- 타이틀 --}}
@section('head_title', '대쉬보드')
{{-- 학생 컨텐츠 --}}
@section('layout_coutent')
<!-- : 상단 요일 클릭.  -->
<!-- : 상단 전주 앞주 클릭 -->
<!-- : 오늘의 학습 불러오기.  -->
<!-- : 오늘의 수강강의 -->
<!-- : 실제 동영상 섬네일 -->
<!-- : 학습 시작 시간 -->
<!-- : 학습하기 버튼 클릭 -->
<!-- : 오답노트 ~ 학습랭킹 클릭  이동 -->
<!-- : 총 학습 시간  : 학생이 본시간이 아니라 일단은 학생이 봐야할 시간을 넣어놓음.-->
<!-- : 각 일별의 학습시작 시간 가져오기. -->
<!-- : 응원 정광판 -->
<!-- : time bar 적용. -->
<!-- TODO: 오늘이 아니면 학습하기 막기. -->
<!-- TODO: 현재 총학습시간이 아니라, 준비하기 시간이므로, 총학습시간으로 변경필요. -->

<style>
    @media all and (max-width: 1400px) {
        .day-full {
            display: none;
        }

        .day-short {
            display: inline;
        }
    }
    @media all and (min-width: 1400px) {
        .day-full {
            display: inline;
        }

        .day-short {
            display: none;
        }
    }
</style>
<div class="student-main-container col px-1 px-xxl-3 pt-4 mt-xl-3 mt-lg-2 row position-relative">
    <input type="hidden" data-main-student-seq value="{{ session()->get('student_seq') }}">
    <input type="hidden" data-main-tody-date value="{{ date('Y-m-d') }}">
    {{-- 상단 배너 : 오답노트 유무 일단은 숨김처리 --}}
    <article class="row justify-content-center pt-3" hidden>
        <div class="bg-primary-bg rounded-3 d-flex justify-content-between " style="width: 750px;height: 98px;padding: 0px 30px">
            <div class="col-auto position-relative" style="width:122px">
                <img class="position-absolute bottom-0" src="{{ asset('images/top_logo.png') }}" width="122">
            </div>
            <div class="d-flex align-items-center fw-bold">
                <span>
                    <span class="cfs-5">오늘까지 완료해야할</span>
                    <span class="cfs-5" style="color:#f3b527">오답노트가 3</span>
                    <span class="cfs-5">개 있습니다🔥🔥</span>
                </span>
            </div>
            <div class="col-auto d-flex align-items-center pe-4">
                <button class="btn p-0">
                    <img src="{{ asset('images/black_x_icon.svg')}}" style="width:32px;">
                </button>
            </div>
        </div>
    </article>

    <!-- pt-5 mt-5 -->
    <div class="row mx-0 px-0">
        <article class="col-10 ps-0 pe-4">
            {{-- 상단 응원 전광판 --}}
            <section class="bg-gc5 rounded-3 px-xl-5 px-lg-3 px-md-2 bulletin-board electronic-board">
                <div class="row col-12 align-items-center justify-content-between cfs-7 fw-semibold px-0 electronic-wrap">
                    <span class="col-xxl-auto col-xl-1 col-lg-1 col-md-1 text-nowrap">응원 전광판</span>
                    <div class="col-xxl-auto col-xl-2 col-lg-2 col-md-2 d-xxl-block position-relative p-0 electronic-img-wrap">
                        <img class="electronic-img" src="{{ asset('images/top_logo2.png') }}" width="97" height="76">
                    </div>
                    <div class="col-xl-7 col-lg-7 col-md-7 overflow-hidden text-nowrap cheer-message">
                        @if(!empty($messengers))
                        {{-- @foreach($messengers as $messenger)
                        <div class="cheer-message-inner">
                            <!-- <span class="d-inline-flex">최**(7777)</span> -->
                            <span class="d-inline-flex">{{$messenger->parent_name}}** ({{$messenger->parent_id}})</span>
                            <img class="px-1" src="{{ asset('images/bar_icon.svg') }}">
                            <span class="d-inline-flex">{{$messenger->message}}</span>
                        </div>
                        @endforeach --}}
                        <div class="cheer-message-inner">
                            <!-- <span class="d-inline-flex">최**(7777)</span> -->
                            <span class="d-inline-flex">{{$messengers->parent_name}}** ({{$messengers->parent_id}})</span>
                            <img class="px-1" src="{{ asset('images/bar_icon.svg') }}">
                            <span class="d-inline-flex">{!!str_replace('<script', '', $messengers->message)!!}</span>
                        </div>
                        @endif
                    </div>
                    <div class="col-xxl-auto col-xl-2 col-lg-2 col-md-2 d-flex justify-content-end">
                        {{-- <button class="btn p-0 d-flex align-items-center justify-content-md-center">
                            <img src="{{ asset('images/calendar_arrow_left.svg') }}" style="width:50px;">
                        </button> --}}
                        <button class="btn p-0 d-flex align-items-center justify-content-center pause">
                            <img src="{{ asset('images/pause_icon.svg') }}" style="width:50px;">
                        </button>
                        {{-- <button class="btn p-0 d-flex align-items-center justify-content-center">
                            <img src="{{ asset('images/calendar_arrow_right.svg') }}" style="width:50px;">
                        </button> --}}
                    </div>
                </div>
            </section>
             <!-- u -->
            @php
            $today = new DateTime();
            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
            $koreanDays = ['월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'];
            $shortKoreanDays = ['월', '화', '수', '목', '금', '토', '일'];
            $currentDayIndex = (int)$today->format('N') -1;
            @endphp
            {{-- 요일 bar --}}
            <section class="shadow-sm-2 rounded-4 mt-4 overflow-hidden week-bar">
                <div class="row align-items-center justify-content-between gap-3">
                    <button class="col-auto p-2 studyColor-bg-studyComplete" onclick="stMainChangeDate('prev')">
                        <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24.9418 28.9597L18.2996 22.3675C17.6164 21.6894 17.6122 20.5858 18.2904 19.9026L24.9418 13.2012" stroke="white" stroke-width="4" stroke-miterlimit="10" stroke-linecap="round" />
                        </svg>
                    </button>
                    @foreach($days as $index => $day)
                    @php
                    $date = clone $today;
                    $diff = $index - $currentDayIndex;
                    $date->modify(($diff >= 0 ? '+' : '') . $diff . ' days');
                    $isToday = $diff === 0;
                    @endphp
                    <div data-week-div="{{ $day }}" onclick="stMainWeekDayClick(this)" class="row col-auto ctext-gc1 {{ $isToday ? 'rounded-3 active studyColor-bg-studyComplete textwhite' : '' }} cursor-pointer">
                        <div class="text-center">
                            <span class="day-full cfs-05 fw-semibold {{ $isToday ? 'text-white' : '' }}">{{ $koreanDays[$index] }}</span>
                            <span class="day-short cfs-05 fw-semibold {{ $isToday ? 'text-white' : '' }}">{{ $shortKoreanDays[$index] }}</span>
                            <div class="sp_date {{ $isToday ? 'd-block text-white' : '' }}" {{ !$isToday ? 'hidden' : '' }}>
                                {{ $date->format('m.d') }}
                            </div>
                            <input type="hidden" data-day-date value="{{ $date->format('Y-m-d') }}">
                        </div>
                    </div>
                    @endforeach
                    <button class="col-auto p-2 studyColor-bg-studyComplete" onclick="stMainChangeDate('next')">
                        <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.0582 28.9597L23.7004 22.3675C24.3836 21.6894 24.3878 20.5858 23.7096 19.9026L17.0582 13.2012" stroke="white" stroke-width="4" stroke-miterlimit="10" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>

            </section>

            {{-- 오늘의 학습 --}}
            <section class="pt-4 pb-4 d-none">
                <!-- : 영업이후에는 보이게 하거나, 화살표 버튼 살려서 기능 넣기.(디자인변경.)  -->
                <div class="row pt-1 pb-4" hidden>
                    <div class="col-auto">
                        <img src="{{ asset('images/pencil_icon.svg') }}" width="42">
                        <span class="ctext-bc0 cfs-4 fw-semibold">오늘의 학습</span>
                    </div>
                    <div class="col text-end">
                        <button class="btn p-0 me-2">
                            <img src="{{ asset('images/calendar_arrow_left.svg') }}" style="width:42px;">
                        </button>
                        <button class="btn p-0 ms-1">
                            <img src="{{ asset('images/calendar_arrow_right.svg') }}" style="width:42px;">
                        </button>
                    </div>
                </div>
                {{-- 오늘의 학습 카드형태 --}}
                <div class=" owl-carousel owl-theme">
                    <div class="" style="display:none" hidden>
                        <div class="card position-relative overflow-hidden">
                            <div class="bg-bc0 text-white cfs-5 fw-semibold position-absolute top-0 end-0 py-2 px-4" style="border-radius: 0px 5px 0px 12px;">
                            </div>
                            <video data-lecture-video style="width:100%;height:100%;" preload="metadata"></video>
                            <div class="progress rounded-0" role="progressbar" style="height:12px" data-progress-bar>
                                <div class="progress-bar bg-study-1 rounded-0" style="width: 0%"></div>
                            </div>
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-auto cfs-5 fw-bold">
                                        <span></span>
                                    </div>
                                    <div class="col text-end">
                                        <img src="{{ asset('images/folder_plus_icon.svg') }}" style="width:32px">
                                    </div>
                                </div>
                                <div class="ctext-gc0 cfs-6 mt-2 mb-2" data-description>
                                    <!-- 글을 읽고 인물의 의견과 그 까닭 알기 글을 읽고 인물의 의견과 그까닭... -->
                                </div>
                                <div class="mt-4 h-center gap-2">
                                    <span></span>
                                    <button onclick="stMainPlayVido(this)" data-btn-study class="btn rounded-pill border cfs-6 ctext-bc0 text-center pe-3 h-center" style="width:134px">
                                        <img src="{{ asset('images/video_play_icon.svg') }}" style="width: 24px;">
                                        <span>학습하기</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
            </section>
            {{-- 오늘의 학습 변경된 디자인 --}}
            <section class="study-conteiner mt-sm-3 mt-md-3 mt-lg-3 mt-xl-4 overflow-hidden">
                <div class="title-wrap d-flex justify-content-between align-items-center p-20">
                    <div class="title-wrap-inner">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.1565 7.69629L8.4082 19.4446L12.6091 23.6336L24.3574 11.8853L20.1565 7.69629Z" fill="#FFD368" />
                            <path d="M7.47268 20.3823L6.68945 22.7438L9.28833 25.3427L11.6736 24.5714L12.6111 23.6339L8.41017 19.4448L7.47268 20.3823Z" fill="#F0F0F0" />
                            <path d="M5.39453 26.5999L9.28691 25.342L6.68804 22.7432L5.39453 26.5999Z" fill="#999999" />
                            <path d="M26.0324 6.85387L25.1898 6.0232C24.7452 5.58115 24.1436 5.33301 23.5166 5.33301C22.8896 5.33301 22.288 5.58115 21.8433 6.0232L20.1582 7.69641L24.3592 11.8973L26.0324 10.2122C26.2531 9.99182 26.4281 9.73003 26.5476 9.4419C26.667 9.15378 26.7285 8.84496 26.7285 8.53306C26.7285 8.22115 26.667 7.91228 26.5476 7.62417C26.4281 7.33604 26.2531 7.07429 26.0324 6.85387Z" fill="#FFAFB9" />
                        </svg>
                        <span class="text-sb-20px">오늘의 학습</span>
                    </div>
                    <div class="title-wrap-right">
                        <span class="text-sb-20px">
                            <span data-today-total-sum>0</span>강 중
                            <span class="studyColor-text-studyComplete">
                                <span data-today-total-complete>0</span> 강의 완료
                            </span>
                        </span>
                    </div>
                </div>
                <div class="content-x-wheels overflow-x-scroll d-flex gap-3 mx-3 pb-3" data-btn-study data-bundle="todays_learning">
                    <div class="card-wrap flex-row" data-row="copy" hidden onclick="stMainPlayVido(this)">
                        <div class="card-item " data-card-item>
                            <input type="hidden" data-st-lecture-detail-seq>
                            <div class="card-cont position-relative">
                                <div class="card-body-top d-flex flex-column">
                                    <div class="d-flex justify-content-between position-relative">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-b-28px text-white" data-subject-name data-explain="#국어">국어</span>
                                        </div>
                                        <span class="rounded-5 px-3 py-2 bg-white d-inline-block" data-status>학습 중</span>
                                    </div>
                                    <span class="text-sb-18px pt-4 text-white " data-explain="#[8단원] 의견있어요." data-lecture-detail-name>[8단원] 의견이 있어요.</span>
                                </div>
                                <a href="javascript:void(0)" data-btn-study class="play-buttom">
                                    <svg width="15" height="18" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M13.2558 10.8722C14.5557 9.96226 14.5557 8.03709 13.2558 7.12715L4.28919 0.850548C2.77427 -0.209897 0.692703 0.873883 0.692703 2.72308V15.2763C0.692703 17.1255 2.77427 18.2092 4.28919 17.1488L13.2558 10.8722Z" fill="white"/>
                                    </svg>
                                    <span class="play-text">학습하기</span>
                                </a>
                            </div>
                            <img class="card-item-character" src="" alt="" data-bg-subject-img>
                        </div>
                    </div>
                @include('component.spinner')

                </div>
                <div class="content-lesson-empty d-flex flex-column align-items-center justify-content-center h-100 gap-4 scale-bg-gray_01 d-none">
                    <img class="mt-2" src="{{asset('images/popcorn_symbol_logo.svg')}}" alt="">
                    <div class="d-flex text-center gap-2 flex-column">
                        <p class="text-b-24px">오늘의 학습이 없어요.</p>
                        <p class="text-b-24px">스스로 학습을 해볼까요?</p>
                    </div>
                    <div class="d-flex gap-3">
                        <button type="button" class="btn-line-ms-primary text-b-24px rounded-pill scale-bg-white primary-bg-mian-hover" onclick="location.href='/student/my/study?type=misu'">미수강 바로가기</button>
                        <button type="button" class="btn-line-ms-primary text-b-24px rounded-pill scale-bg-white primary-bg-mian-hover" onclick="location.href='/student/school/study'">학교공부 바로가기</button>
                    </div>
                </div>
            </section>

        </article>
        {{-- 사이드 --}}
        <aside class="col-2 p-0">
            {{-- 학습플래너 이동 버튼 --}}
            <section>
                <button onclick="stMainMovePage('learning')" class="btn btn-primary-y rounded-3 px-3 m-0 w-100 learning-planner-btn">
                    <div class="d-flex justify-content-center align-items-center flex-row h-100">
                        <div class="col-auto d-flex align-items-center justify-content-center ps-0 pe-2 calendar-img">
                            <img src="{{ asset('images/calendar_icon.svg') }}" alt="42">
                            <img src="{{ asset('images/calendar_icon_brown.svg') }}" alt="42">
                        </div>
                        <div class="col-auto ms-2 p-0">
                            <span class="cfs-6 study-planner">학습플래너</span>
                        </div>
                    </div>
                </button>
            </section>
            {{-- 현재 요일 / 시간 --}}
            <section class="shadow-sm-2 rounded-3 border mt-4 today-time" data-section-side-right>
                <div class="row m-0 p-10 justify-content-between">
                    <div class="d-flex flex-row justify-content-center align-items-center p-0">
                        <div class="text-center ">
                            <span class="cfs-6 fw-semibold" data-date>
                                {{ date('m월 d일') }}
                            </span>
                        </div>
                        <div data-week-day class="cfs-6 fw-bold bg-white py-2 rounded-pill ms-2">목</div>
                    </div>
                    <!-- <div class="col-auto pe-0">
                        <img src="https://sdang.acaunion.com/images/main_side_character.svg" width="80">
                    </div> -->
                </div>
            </section>

            {{-- 수강강의 현황 --}}
            <section class="learning-status">
                <div class="row h-center today-status mt-md-3 mt-lg-3 mt-xl-4">
                    <span class="col cfs-7 ctext-gc0 status-title">오늘의 수강강의</span>
                    <!-- <span class="col-auto cfs-8 text-white rounded-pill bg-danger px-2 py-1">
                        <span data-today-total-sum>8</span> 강의
                    </span> -->
                    <div class="row cfs-1 fw-medium align-items-center justify-content-center status-count px-0 px-0">
                        <span class="col-auto ctext-bc0" data-today-total-complete>0</span>
                        <span class="col-auto ctext-gc2 cfs-6 align-middle">/</span>
                        <span class="col-auto ctext-gc2" data-today-total-sum>0</span>
                    </div>
                    <div class="text-center mb-2" data-today-subscript-div hidden>
                        <div>
                            <svg width="12" height="10" viewBox="0 0 12 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.84 0.732759L0 10H12L7.16 0.732759C6.65333 -0.244253 5.34667 -0.244253 4.83333 0.732759H4.84Z" fill="#222222" />
                            </svg>
                        </div>
                        <span data-today-subscript class="bg-dark text-white cfs-8 rounded-pill py-2 px-3">
                            거의 다 왔어요!
                        </span>
                    </div>
                </div>

                <!-- <div class="mt-3">
                    <span class="col cfs-7 ctext-gc0">김팝콘 학생의 학습내용</span>
                </div> -->

                <div class="row text-center mt-3 rounded-3 today-status">
                    <span class="col cfs-8 ctext-gc1 status-title">학습 시작 시간</span>
                    <div class="status-count">
                        <span class="ctext-bc0 start-time" data-explain="#08:09" data-study-start-time>00:00</span>

                    </div>
                    <div class="">
                        <div class="cfs-8 ctext-gc1 mb-2" data-explain="#(9분 지각)" data-study-start-late hidden>(0분 지각)</div>
                    </div>
                </div>

                <div class="row mt-3 today-status">
                    <span class="cfs-8 ctext-gc1 status-title">총 학습 시간</span>
                    <div class="status-count text-center">
                        <span class="ctext-bc0 progress-time">
                            <div class="timer-style">
                                <span class="cal">0</span>
                                <span class="cal">0</span>
                                <span class="colon">:</span>
                                <span class="cal">0</span>
                                <span class="cal">0</span>
                            </div>
                        </span>
                    </div>
                    <!-- <div class="mt-1 row align-items-center justify-content-center gap-3">
                        <span class="col-auto bg-dark text-white cfs-8 rounded-pill" style="padding:2px 8px;">hrs</span>
                        <span class="col-auto bg-dark text-white cfs-8 rounded-pill" style="padding:2px 8px;">min</span>
                    </div> -->
                </div>
            </section>

        </aside>
    </div>
    <article class="mt-3 mt-xxl-4 py-2 shadow-sm-2 category-section">
        <div class="row row-cols-4">
            {{-- 1:1 학습질문 --}}
            <div class="col">
                <div class="div_move_page_btm rounded-4 py-2" onclick="stMainMovePage('one_on_one__disabled');">
                    <div class="position-relative d-flex align-items-center justify-content-center">
                        <img src="{{ asset('images/1대1학습질문.svg') }}">
                        <span class="cfs-5 fw-bold sm-title">1:1 학습질문</span>
                    </div>
                    <div class="arrow-icon"></div>
                </div>
            </div>
            {{-- 오답노트 --}}
            <div class="col">
                <div class="div_move_page_btm rounded-4 py-2" onclick="stMainMovePage('wrong_note');">
                    <div class="position-relative d-flex align-items-center justify-content-center">
                        <img src="{{ asset('images/오답노트.svg') }}" >
                        <span class="cfs-5 fw-bold sm-title">오답노트</span>
                    </div>
                    <div class="arrow-icon"></div>
                </div>
            </div>
            {{-- 나의 학습 --}}
            <div class="col">
                <div class="div_move_page_btm rounded-4 py-2" onclick="stMainMovePage('my_study');">
                    <div class="position-relative d-flex align-items-center justify-content-center">
                        <img src="{{ asset('images/나의학습.svg') }}">
                        <span class="cfs-5 fw-bold sm-title">나의 학습</span>
                    </div>
                    <div class="arrow-icon"></div>
                </div>
            </div>
            {{-- 내성적표 --}}
            <div class="col">
                <div class="div_move_page_btm rounded-4 py-2" onclick="stMainMovePage('my_score');">
                    <div class="position-relative d-flex align-items-center justify-content-center">
                        <img src="{{ asset('images/내성적표.svg') }}">
                        <span class="cfs-5 fw-bold sm-title">내성적표</span>
                    </div>
                    <div class="arrow-icon"></div>
                </div>
            </div>
            {{-- 학습랭킹 --}}
            {{-- <div class="col">
                <div class="div_move_page_btm rounded-4" onclick="stMainMovePage('my_rank__disabled');">
                    <div class="position-relative d-flex align-items-center justify-content-center">
                        <img src="{{ asset('images/학습랭킹.svg') }} ">
                        <span class="cfs-5 fw-bold sm-title">학습랭킹</span>
                    </div>
                    <div class="arrow-icon"></div>
                </div>
            </div> --}}
        </div>
    </article>
</div>

<!-- 학습하기  -->
<form method="POST" action="/student/study/video" data-form="study_video" hidden>
    @csrf
    <input name="st_lecture_detail_seq" />
    <input name="is_go_complete" value="Y"/>
</form>

<script>
    document.addEventListener('visibilitychange', function(event) {
        if (sessionStorage.getItem('isBackNavigation') === 'true') {
            sessionStorage.removeItem('isBackNavigation'); // 상태를 초기화합니다.
            stMainLoadTodaysLearningSelect();
            stMainStudyTimeSelect();
        }
    });

    document.addEventListener("DOMContentLoaded", function() {
        stMainLoadTodaysLearningSelect();
        stMainStudyTimeSelect();
        const selectedDate = document.querySelector('[data-week-div].active [data-day-date]').value;
        stMainChageSideRight(selectedDate, document.querySelector('[data-week-div].active span').textContent);
        containerHeight();
    });

    function containerHeight() {
        if ($(".study-conteiner").length) {
            let mainContH = $(".learning-status").height();
            $(".study-conteiner").css("height", mainContH + 2);
        }
    }

    window.addEventListener("resize", function() {
        containerHeight();
    });

    function stMainMovePage(type) {
        // 학습플래너
        if (type == 'learning') location.href = "/manage/learning";
        // 오답노트 /student/wrong/note
        else if (type == 'wrong_note') {
            location.href = "/student/wrong/note";
        }
        // 학습질문 /teacher/messenger
        else if (type == 'one_on_one') {
            location.href = "/teacher/messenger";
        }
        // 나의학습 /student/my/study
        else if (type == 'my_study') {
            location.href = "/student/my/study";
        }
        // 내성적표 /student/my/score
        else if (type == 'my_score') {
            location.href = "/student/my/score";
        }
        // 학습랭킹 /student/study/point
        else if (type == 'my_rank') {
            location.href = "/student/study/point";
        }
        else{
            return;
        }
    }

    //학습하기.
    function stMainPlayVido(vthis) {
        const pt_row = vthis.closest('[data-row]');
        const st_lecture_detail_seq = pt_row.querySelector('[data-st-lecture-detail-seq]').value;
        const form = document.querySelector('[data-form="study_video"]');
        form.querySelector('input[name="st_lecture_detail_seq"]').value = st_lecture_detail_seq;
        rememberScreenOnSubmit(true);
        form.submit();
    }

    //오늘의 학습 불러오기.
    let ori_table = null;

    function stMainLoadTodaysLearningSelect(selectr_date) {
        const search_date = selectr_date || document.querySelector('[data-week-div].active [data-day-date]')?.value || new Date().format('yyyy-MM-dd');
        const select_type = 'no_group';
        const page = "/student/study/today/select";
        const parameter = {
            search_start_date: search_date,
            search_end_date: search_date,
            select_type: select_type
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                //초기화
                let bundle = document.querySelector('[data-bundle="todays_learning"]');
                if (ori_table) {
                    // bundle 뒤에 ori_table 을 넣는다.
                    const clone_ori_table = ori_table.cloneNode(true);
                    $('[data-bundle="todays_learning"]').after(clone_ori_table)
                    bundle.remove();
                    bundle = clone_ori_table;
                }
                const row_copy = bundle.querySelector('[data-row]').cloneNode(true);
                bundle.classList.remove('owl-loaded');
                bundle.classList.remove('owl-drag');
                bundle.innerHTML = '';
                bundle.appendChild(row_copy);
                if (ori_table == null) ori_table = bundle.cloneNode(true);

                const st_lecture_details = result.student_lecture_details;
                const today = document.querySelector('[data-main-tody-date]').value;

                let total_sum = 0; //오늘  강의수
                let total_complete = 0; // 오늘  완료 강의
                //총 봐야할 학습시간
                let total_study_time = 0;
                st_lecture_details.forEach(function(detail) {
                    // if(detail.lecture_detail_link) {
                        const row = row_copy.cloneNode(true);
                        row.classList.add('item');
                        row.classList.add('d-inline-flex');
                        const study_time = detail.lecture_detail_time;
                        //시간:분 = 초
                        const sec_study_time = study_time;
                        const subject = detail.subject_function_code.replace('subject_', '').replace('_icon', '');
                        row.setAttribute('data-row', 'clone');
                        row.style.display = '';
                        row.hidden = false;
                        row.querySelector('[data-st-lecture-detail-seq]').value = detail.id;
                        row.querySelector('[data-subject-name]').innerText = detail.subject_name;
                        row.querySelector('[data-lecture-detail-name]').innerText = detail.lecture_name  + ' ' + detail.lecture_detail_name;
                        if (subject == 'math') {
                            row.querySelector('[data-card-item]').classList.add('mathematics');
                            row.querySelector('[data-bg-subject-img]').src = "/images/mathematics_character.svg";
                        } else if (subject == 'kor') {
                            row.querySelector('[data-card-item]').classList.add('ko-language');
                            row.querySelector('[data-bg-subject-img]').src = "/images/ko_language_character.svg";
                        } else if (subject == 'eng') {
                            row.querySelector('[data-card-item]').classList.add('english');
                            row.querySelector('[data-bg-subject-img]').src = "/images/english_character.svg";
                        } else if (subject == 'social') {
                            row.querySelector('[data-card-item]').classList.add('society');
                            row.querySelector('[data-bg-subject-img]').src = "/images/society_character.svg";
                        } else if (subject == 'other') {
                            row.querySelector('[data-card-item]').classList.add('science');
                            row.querySelector('[data-bg-subject-img]').src = "/images/science_character.svg";
                        }else if(subject == 'hanja'){
                            row.querySelector('[data-card-item]').classList.add('hanja');
                            row.querySelector('[data-bg-subject-img]').src = "/images/hanja_character.svg";
                        }
                        // row.querySelector('[data-lecture-video]').src = detail.lecture_detail_link;
                        // row.querySelector('[data-lecture-video]').currentTime = Math.floor(Math.random() * 60) + 1;

                        // sec_study_time (전체시간)  detail.last_video_time (본시간) 으로 %.
                        // const persent_time = parseInt(detail.last_video_time || 0) / sec_study_time * 100;
                        // row.querySelector('[data-progress-bar] .progress-bar').style.width = persent_time + '%';
                        // let description = detail.lecture_detail_description;
                        //lecture_detail_description 가 없으면 lecture_description를 삽입.
                        // if (detail.lecture_detail_description || description.length < 1) description = detail.lecture_description;
                        // row.querySelector('[data-description]').innerText = description;

                        total_sum++;
                        //data-status
                        //프로그래스 바 색 같이 변경.
                        if (detail.status == 'complete') {
                            //class add studey-completion
                            total_complete++;
                            row.querySelector('[data-btn-study] .play-text').innerText = '복습하기';
                            row.querySelector('[data-status]').innerText = '학습 완료';
                            row.querySelector('[data-status]').classList.add('completion-learning');
                            // stMainChageProgressColor(row.querySelector('[data-progress-bar] .progress-bar'), 'bg-study-2');
                        } else if (detail.status == 'ready') {
                            row.querySelector('[data-btn-study] .play-text').innerText = '학습하기';
                            row.querySelector('[data-status]').innerText = '학습 전';
                            row.querySelector('[data-status]').classList.add('before-learning');
                            // stMainChageProgressColor(row.querySelector('[data-progress-bar] .progress-bar'), 'bg-study-0');
                        } else if (detail.status == 'study') {
                            row.querySelector('[data-btn-study] .play-text').innerText = '이어서하기';
                            row.querySelector('[data-status]').innerText = '학습 중';
                            row.querySelector('[data-status]').classList.add('learning');
                            // stMainChageProgressColor(row.querySelector('[data-progress-bar] .progress-bar'), 'bg-study-1');
                        }
                        //오늘이 아니면 학습하기 버튼 삭제
                        //if (search_date > today) {
                            //row.querySelector('[data-btn-study]').remove();
                            //row.removeAttribute('onclick');
                        //}
                        total_study_time += parseInt(detail.last_video_time || 0);
                        bundle.appendChild(row);
                    // }
                });
                // 총 학습 강의 수 넣기
                if (total_sum > 0) {
                    const total_sum_els = document.querySelectorAll('[data-today-total-sum]');
                    total_sum_els.forEach(function(el) {
                        el.innerText = total_sum*1;
                    });
                }
                // 오늘 완료 강의 수 넣기
                if (total_complete >= 0) {
                    const total_complete_els = document.querySelectorAll('[data-today-total-complete]');
                    total_complete_els.forEach(function(el) {
                        el.innerText = total_complete != 0 ? total_complete*1 : '0';
                    });
                }
                // 2/3 정도 complete되었을때, 거의 다 왔어요! 표시.
                if (total_complete > 0 && total_sum > 0) {
                    const today_subscript_div = document.querySelector('[data-today-subscript-div]');
                    const today_subscript = document.querySelector('[data-today-subscript]');
                    if (total_complete / total_sum > 0.66) {
                        today_subscript_div.hidden = false;
                    }
                    if (total_complete / total_sum == 1) {
                        today_subscript.innerText = '학습 완료';
                    }else{
                        today_subscript_div.hidden = true;
                    }
                }else{
                    const today_subscript_div = document.querySelector('[data-today-subscript-div]');
                    today_subscript_div.hidden = true;
                }
                // TODO: 현재 총학습시간이 아니라, 준비하기 시간이므로, 총학습시간으로 변경필요.
                // 총 학습시간 넣기
                if (total_study_time > 0) {
                    const all_mins = Math.floor(total_study_time / 60);
                    const total_study_hrs = Math.floor(all_mins / 60);
                    const total_study_min = all_mins % 60;
                    const total_study_hrs_el = document.querySelector('[data-today-all-study-hrs]');
                    const total_study_min_el = document.querySelector('[data-today-all-study-min]');
                    // 시간 단위로 01:01 형태로 넣기.
                    let hrs = total_study_hrs < 10 ? '0' + total_study_hrs : total_study_hrs;
                    let mins =  total_study_min < 10 ? '0' + total_study_min : total_study_min;
                    $('.progress-time').html(`${hrs}:${mins}`);
                    $('.progress-time').each(function() {
                        const progressTime = $(this).text().replace(/ /g, '');
                        var progressArray = [...progressTime];
                        var html = `
                            <div class="timer-style">
                                <span class="cal">${progressArray[0]}</span>
                                <span class="cal">${progressArray[1]}</span>
                                <span class="colon">${progressArray[2]}</span>
                                <span class="cal">${progressArray[3]}</span>
                                <span class="cal">${progressArray[4]}</span>
                            </div>
                        `
                        $(this).html(html);
                    });
                }
                // const owl = $('[data-bundle="todays_learning"]');
                // owl.owlCarousel({
                //     items: 3,
                //     loop: false,
                //     margin: 10,
                //     nav: true,
                //     dots: false,
                //     onInitialized: owlInit
                // });
                if (result.student_lecture_details.length > 0) {
                    document.querySelector('.content-lesson-empty').classList.add('d-none');
                    document.querySelector('.content-x-wheels').classList.remove('d-none');
                    document.querySelector('.title-wrap').classList.remove('d-none');
                } else {

                    document.querySelector('.content-lesson-empty').classList.remove('d-none');
                    document.querySelector('.content-x-wheels').classList.add('d-none');
                    document.querySelector('.title-wrap').classList.add('d-none');
                }
            }
            todeyPrevDate();
            const contentXWheels = document.querySelector('.content-x-wheels');
            if(contentXWheels && !contentXWheels.querySelector('[data-row="clone"]')){
                document.querySelector('.content-lesson-empty').classList.remove('d-none');
                document.querySelector('.content-x-wheels').classList.add('d-none');
                document.querySelector('.title-wrap').classList.add('d-none');
            }else{
                document.querySelector('.content-lesson-empty').classList.add('d-none');
                document.querySelector('.content-x-wheels').classList.remove('d-none');
                document.querySelector('.title-wrap').classList.remove('d-none');
            }

            contentXWheels.addEventListener('wheel', function(event) {
                event.preventDefault();
                if (event.deltaY !== 0) {
                    // 수직 스크롤을 수평 스크롤로 변환합니다.
                    contentXWheels.scrollLeft += event.deltaY
                    // 기본 수직 스크롤 동작을 방지합니다.
                }
            });
        })
    }

    function todeyPrevDate() {
        const today = document.querySelector('[data-main-tody-date]').value;
        const date = document.querySelectorAll('[data-week-div]');
        date.forEach(function(item, index) {
            if (item.querySelector('[data-day-date]').value < today) {
                item.classList.remove('ctext-gc1');
                item.classList.add('ctext-gc2');
            } else {
                item.classList.remove('ctext-gc2');
                item.classList.add('ctext-gc1');
            }
        })
    }

    function owlInit() {
        document.querySelector('[data-bundle="todays_learning"] [data-row="copy"]').closest('.owl-item').hidden = true;
    }

    // 상단에 요일 클릭.
    function stMainWeekDayClick(element) {

        // 모든 요일 div 선택
        const allDays = document.querySelectorAll('[data-week-div]');

        // 모든 요일의 활성화 상태 제거
        allDays.forEach(day => {
            day.classList.remove('rounded-3', 'active', 'studyColor-bg-studyComplete');
            day.querySelector('span.day-full').classList.remove('text-white');
            day.querySelector('span.day-short').classList.remove('text-white');
            day.querySelector('.sp_date').classList.remove('d-block', 'text-white');
            day.querySelector('.sp_date').hidden = true;
        });

        // 클릭된 요일 활성화
        element.classList.add('rounded-3', 'active', 'studyColor-bg-studyComplete');
        element.querySelector('span.day-full').classList.add('text-white');
        element.querySelector('span.day-short').classList.add('text-white');
        const spDate = element.querySelector('.sp_date');
        spDate.classList.add('d-block', 'text-white');
        spDate.hidden = false;

        // 선택된 날짜 가져오기
        const selectedDate = element.querySelector('[data-day-date]').value;

        // 오늘의 학습 불러오기.
        stMainLoadTodaysLearningSelect(selectedDate);

        // 오른쪽 날짜/요일 변경하기.
        //stMainChageSideRight(selectedDate, element.querySelector('span').textContent);

        // 학습 시작 시간 가져오기.
        stMainStudyTimeSelect();
    }

    let currentWeekStart = new Date(); // 현재 주의 시작일 (일요일) x // 오늘은 넣긴하는데, 배교시에는 월요일을 넣어줌.
    currentWeekStart.setDate(currentWeekStart.getDate() - currentWeekStart.getDay());

    function stMainChangeDate(direction) {
        let currentWeekStart = new Date(document.querySelector('[data-week-div] [data-day-date]').value);
        // 주 변경
        if (direction === 'prev') {
            currentWeekStart.setDate(currentWeekStart.getDate() - 7);
        } else if (direction === 'next') {
            currentWeekStart.setDate(currentWeekStart.getDate() + 7);
            // if(isSunday) currentWeekStart.setDate(currentWeekStart.getDate());
        }
        todeyPrevDate();
        stMainStudyTimeSelect();
        // 모든 요일 div 선택
        const allDays = document.querySelectorAll('[data-week-div]');
        const koreanDays = ['월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'];

        // 각 요일 div 업데이트
        allDays.forEach((dayDiv, index) => {
            const date = new Date(currentWeekStart.format('yyyy-MM-dd'));
            date.setDate((date.getDate() + 0) + index);
            const spanElement = dayDiv.querySelector('span');
            const divElement = dayDiv.querySelector('.sp_date');
            const inputElement = dayDiv.querySelector('[data-day-date]');

            // 요일 텍스트 업데이트
            spanElement.textContent = koreanDays[index];

            // 날짜 텍스트 업데이트
            divElement.textContent = `${(date.getMonth() + 1).toString().padStart(2, '0')}.${date.getDate().toString().padStart(2, '0')}`;

            // data-day-date 값 업데이트
            inputElement.value = date.toISOString().split('T')[0]; // YYYY-MM-DD 형식

            // 오늘 날짜 확인 및 스타일 적용
            let isToday = date.toDateString() === new Date().toDateString();
            let isMonday = date.getDay() === 1;

            // 기존 스타일 제거
            dayDiv.classList.remove('rounded-3', 'active', 'studyColor-bg-studyComplete');
            spanElement.classList.remove('text-white');
            divElement.classList.remove('d-block', 'text-white');
            divElement.hidden = true;

            // 오늘 날짜에 스타일 적용
            if (isToday) {
                dayDiv.classList.add('rounded-3', 'active', 'studyColor-bg-studyComplete');
                spanElement.classList.add('text-white');
                divElement.classList.add('d-block', 'text-white');
                divElement.hidden = false;
                stMainWeekDayClick(dayDiv);
            }

        });
        // 이번 주가 아니라면 비교 연산문 추가
        const today = new Date();
        const startOfWeek = new Date(currentWeekStart);
        const endOfWeek = new Date(currentWeekStart);
        const monday = document.querySelector('[data-week-div="monday"]');
        endOfWeek.setDate(endOfWeek.getDate() + 6);

        if (today < startOfWeek || today > endOfWeek) {
            monday.classList.add('rounded-3', 'active', 'studyColor-bg-studyComplete');
            monday.querySelector('span').classList.add('text-white');
            monday.querySelector('.sp_date').classList.add('d-block', 'text-white');
            monday.querySelector('.sp_date').hidden = true;
            stMainWeekDayClick(monday);
        }
    }

    // 오른쪽 날짜/요일 변경하기.
    function stMainChageSideRight(date, day) {
        const sideRight = document.querySelector('[data-section-side-right]');
        const date_el = sideRight.querySelector('[data-date]');
        const day_el = sideRight.querySelector('[data-week-day]');

        //MM월dd일 형태로 변환
        date_el.textContent = new Date(date).format('MM월dd일');
        day_el.textContent = day.slice(0, 1);
    }

    // 학습 시작 시간 가져오기.
    function stMainStudyTimeSelect() {
        // data-week-div.bg-primary-y data-day-date
        const selectedDay = document.querySelector('[data-week-div].studyColor-bg-studyComplete');
        const selectr_date = selectedDay?.querySelector('[data-day-date]')?.value;
        // const search_start_date = new Date().format('yyyy-MM-dd');
        // const search_end_date = new Date().format('yyyy-MM-dd');
        const page = "/student/study/time/select";
        const parameter = {
            search_start_date: selectr_date,
            search_end_date: selectr_date
        };

        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                const attend = result.attend;
                if (attend) {
                    document.querySelector('[data-study-start-time]').innerText = attend.start_time.substr(0, 5);
                    stMainSetStartTime();
                }
                const study_times = result.study_times;
                if (study_times.length > 0 && attend) {
                    const study_time = study_times[0].select_time;
                    const start_time = attend.start_time;

                    // study_time 에서  start_time을 빼서 몇분이 지각인지 구해라.
                    const today_date = new Date().format('yyyy-MM-dd ');
                    const start_time_date = new Date(today_date + start_time);
                    const study_time_date = new Date(today_date + study_time);
                    const diff = study_time_date - start_time_date;
                    const diff_minutes = Math.floor(diff / 1000 / 60);

                    const late_el = document.querySelector('[data-study-start-late]');
                    if (diff_minutes < 0) {
                        late_el.innerText = '(' + diff_minutes + '분 지각)';
                        late_el.hidden = false;
                    } else {
                        late_el.hidden = true;
                    }

                }

            }
        })
    }

    // 학습 시간 시간 저장 출석하기.->학습동영상쪽으로 이동.
    function attendInsert(callback) {
        const sel_date = new Date().format('yyyy-MM-dd');
        const page = "/student/study/start/attend";
        const parameter = {
            sel_date: sel_date
        };

        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                // alert('출석이 완료되었습니다.');
                if (callback != undefined) callback();
            } else {
                // toast('다시 시도해주세요');
            }
        })
    }

    // 프로그래스 바 색 변경.
    function stMainChageProgressColor(tag, class_name) {
        tag.classList.remove('bg-study-0');
        tag.classList.remove('bg-study-1');
        tag.classList.remove('bg-study-2');

        tag.classList.add(class_name);
    }

    let currentPosition = 0;
    const itemsToShow = 3;
    const row = document.querySelector('[data-bundle="todays_learning"]');
    const itemWidth = row.children[0].offsetWidth;

    {{-- function updateCarousel() {
        row.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
    } --}}

    function moveLeft() {
        if (currentPosition > 0) {
            currentPosition -= itemsToShow;
            if (currentPosition < 0) currentPosition = 0;
            //updateCarousel();
        }
    }

    function moveRight() {
        const totalItems = row.children.length - 1;
        if (currentPosition < totalItems - itemsToShow) {
            currentPosition += itemsToShow;
            if (currentPosition > totalItems - itemsToShow) currentPosition = totalItems - itemsToShow;
            //updateCarousel();
        }
    }

    function stMainSetStartTime() {
        $('.start-time').each(function() {
            const startTime = $(this).text().trim().replace(/ /g, '');
            var startArray = [...startTime];
            var html = `
            <div class="timer-style">
                <span class="cal">${startArray[0]}</span>
                <span class="cal">${startArray[1]}</span>
                <span class="colon">${startArray[2]}</span>
                <span class="cal">${startArray[3]}</span>
                <span class="cal">${startArray[4]}</span>
            </div>
            `
            $(this).html(html);
        });
    }

    {{-- window.addEventListener('resize', () => {
        itemWidth = row.children[0].offsetWidth;
        updateCarousel();
    }); --}}

    // cheer-message 전광판처럼 애니메이션 추가
    $(document).ready(function() {
        var $cheerMessageContainer = $('.cheer-message');
        var $cheerMessage = $cheerMessageContainer.children();
        var cheerMessagePosition = $cheerMessageContainer.width();
        var animationSpeed = 1.5; // 애니메이션 속도 조절 변수
        var animationFrameId; // 애니메이션 프레임 ID 저장 변수
        var isPaused = false; // 애니메이션 일시정지 상태 변수

        function animateCheerMessage() {
            if (!isPaused) {
                cheerMessagePosition -= animationSpeed;
                if (cheerMessagePosition < -1 * $cheerMessage.width()) {
                    cheerMessagePosition = $cheerMessageContainer.width();
                }
                $cheerMessage.css('transform', 'translateX(' + cheerMessagePosition + 'px)');
            }
            animationFrameId = requestAnimationFrame(animateCheerMessage);
        }

        // 일시정지 버튼 클릭 이벤트 핸들러
        $('.pause').on('click', function() {
            isPaused = !isPaused; // 일시정지 상태 토글
        });

        animateCheerMessage();

        stMainSetStartTime();

        let isDragging = false;
        let startX;
        let scrollLeft;

        $(".content-x-wheels").on("mousedown", function(e) {
            isDragging = true;
            startX = e.pageX - $(this).offset().left;
            scrollLeft = $(this).scrollLeft();
            $(this).css("cursor", "grabbing");
        });

        $(".content-x-wheels").on("mouseleave mouseup", function() {
            isDragging = false;
            $(this).css("cursor", "grab");
        });

        $(".content-x-wheels").on("mousemove", function(e) {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.pageX - $(this).offset().left;
            const walk = (x - startX) * 2; // 스크롤 속도 조절
            $(this).scrollLeft(scrollLeft - walk);
        });
    });
</script>
@endsection
