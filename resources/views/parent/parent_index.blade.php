@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '학부모 메인')

<!-- TODO:  -->

@section('add_css_js')
<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="{{ asset('js/owl.js') }}"></script>
<link href="{{ asset('css/owl1.css') }}" rel="stylesheet">
<link href="{{ asset('css/owl2.css') }}" rel="stylesheet">
@endsection
{{-- 학부모 인덱스  --}}

@section('layout_coutent')
<style>
    #svgContainer {
        width: 326px;
        height: 326px;
        position: relative;
    }

    #svgContainer svg {
        transform: rotate(90deg);
        /* 원의 180도 지점에서 시작하도록 회전 */
    }

    .pop-alert {
        position: absolute;
        top: -100%;
        opacity: 0;
        z-index: 99;
    }

    .bar-wrap {
        display: flex;
        height: 100%;
        align-items: flex-end;
        justify-content: center;
    }

    .bar-wrap .self-bar,
    .bar-wrap .goal-bar {
        width: 2rem;
    }

    .bar-month {
        bottom: -14px;
        position: relative;
    }

    .monthly-study-status {
        height: 410px;
    }

    .study-time-indicator {
        height: 410px;
    }

    .owl-item {
        margin-right: 0 !important;
    }

    .owl-stage::after {
        content: '';
        display: none !important;
    }

    [data-btn-owl-prev]:disabled {
        opacity: 0.3;
    }

    [data-btn-owl-next]:disabled {
        opacity: 0.3;
    }

    /* @media all and (max-width: 1440px) { */
    /*     .goal-bar { */
    /*         position: absolute; */
    /*         left: calc(50% - 35px); */
    /*     } */
    /**/
    /*     .self-bar { */
    /*         position: absolute; */
    /*     } */
    /**/
    /*     .bar-month { */
    /*         bottom: 0px; */
    /*     } */
    /**/
    /*     .studey-doing { */
    /*         font-size: 1rem; */
    /*         color: #18DB72; */
    /*         font-weight: 600; */
    /*     } */
    /**/
    /*     .studey-before { */
    /*         font-size: 1rem; */
    /*         color: #45C4E7; */
    /*         font-weight: 600; */
    /*     } */
    /**/
    /*     .studey-completion { */
    /*         font-size: 1rem; */
    /*         color: #FF5065; */
    /*         font-weight: 600; */
    /*     } */
    /**/
    /*     .subject_kor_icon>div { */
    /*         background: #FF8C39; */
    /*     } */
    /**/
    /*     .subject_math_icon>div { */
    /*         background: #6F91F7; */
    /*     } */
    /**/
    /*     .subject_eng_icon>div { */
    /*         background: #99DD42; */
    /*     } */
    /**/
    /*     .subject_social_icon>div { */
    /*         background: #FF92CD; */
    /*     } */
    /**/
    /*     .subject_other_icon>div { */
    /*         background: #63D8EC; */
    /*     } */
    /**/
    /*     .owl-item div[data-row="clone"] { */
    /*         color: #fff; */
    /*     } */
    /**/
    /*     .owl-item div[data-course-name] { */
    /*         color: #fff !important; */
    /*     } */
    /**/
    /*     .owl-item span[data-status] { */
    /*         padding: 6px 16px !important; */
    /*         background: #fff !important; */
    /*     } */
    /**/
    /*     .owl-item span[data-status]::before { */
    /*         content: '' !important; */
    /*         display: none !important; */
    /*     } */
    /* } */

    @media all and (max-width: 1400px) {

        #svgContainer {
            width: 240px;
            height: 240px;
        }

        .goal-bar {
            position: absolute;
            left: calc(50% - 35px);
        }

        .self-bar {
            position: absolute;
        }

        .monthly-study-status {
            height: 290px;
        }

        .study-time-indicator {
            height: 290px;
        }

    }
</style>
<div class="col row position-relative zoom_sm">
    <input type="hidden" data-main-student-seq value="{{ session()->get('student_seq') }}">

    {{-- 상단 배너 : 오답노트 유무 일단은 숨김처리 --}}
    <article class="row justify-content-center pt-3 pop-alert">
        <div class="bg-primary-bg rounded-3 d-flex justify-content-between cursor-pointer" style="width: 750px;height: 98px;padding: 0px 30px">
            <div class="col-auto position-relative" style="width:122px">
                <img class="position-absolute bottom-0" src="{{ asset('images/top_logo.png') }}" width="122">
            </div>
            <div class="d-flex align-items-center fw-bold">
                <span onclick="location.href=`/student/wrong/note`">
                    <span class="cfs-5">오늘까지 완료해야할</span>
                    <span class="cfs-5" style="color:#f3b527">오답노트가 {{$complete_exams->whereIn('id', $wrong_sld_seqs)->count()}}</span>
                    <span class="cfs-5">개 있습니다🔥🔥</span>
                </span>
            </div>
            <div class="col-auto d-flex align-items-center pe-4">
                <button class="btn p-0 close-pop-alert">
                    <img src="{{ asset('images/black_x_icon.svg')}}" style="width:32px;">
                </button>
            </div>
        </div>
    </article>

    <section class="row">
        {{-- 자녀 학생의 목표학습. --}}
        <div class="h-center my-0 my-xxl-5 mt-2 d-flex justify-content-between ">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/goal_icon.svg') }}" width="32">
                <span class="text-sb-28px ms-2">학생의 목표학습</span>
                <span class="text-sb-20px bg-danger rounded-pill text-white ms-3 px-3 py-2">
                    <span class="main_count" data-complete-cnt></span> / <span class="opacity-75 main_count" data-all-cnt></span><span class="opacity-75">강</span>
                </span>
            </div>
            <div class="col-auto row mx-0 owl-btn-wrap d-xxl-none" hidden>
                <button class="btn p-0 col-auto" data-btn-owl-prev="">
                    <img src="{{ asset('images/calendar_arrow_left.svg') }}" class="align-middle" width="32">
                </button>
                <button class="btn p-0 col-auto" data-btn-owl-next="">
                    <img src="{{ asset('images/calendar_arrow_right.svg') }}" class="align-middle" width="32">
                </button>
            </div>
        </div>
        <div class="d-flex justify-content-end d-none d-xxl-flex">
            <div class="col-auto row mx-0 owl-btn-wrap">
                <button class="btn p-0 col-auto border-0" data-btn-owl-prev="">
                    <img src="{{ asset('images/calendar_arrow_left.svg') }}" class="align-middle" width="32">
                </button>
                <button class="btn p-0 col-auto border-0" data-btn-owl-next="">
                    <img src="{{ asset('images/calendar_arrow_right.svg') }}" class="align-middle" width="32">
                </button>
            </div>
        </div>
        {{-- 학교공부 예습복습 3단 --}}
        <div data-bundle="top_prestudy_review" class="owl-carousel owl-theme px-0 mt-0">
            <div data-row="copy" class="" hidden>
                <div class="row mx-0 div-shadow-style p-xxl-4 p-xl-2 p-md-2 rounded-3 order-md-1 order-xl-1 justify-content-md-between">
                    <div class="col-auto d-flex flex-row justify-content-center gap-3">
                        <img data-function-code src="" style="width:72px;height:72px;" class="w-md-75 w-xl-100 d-xl-block d-lg-none d-sm-none">
                        <div class="col-auto d-flex flex-column justify-content-center order-md-1 order-xl-2 pb-xl-0 pb-md-0">
                            <div class="text-sb-18px scale-text-gray_05 text-start mb-2" data-course-name data-explain="학교공부예습"></div>
                            <div class="text-sb-20px text-start" data-lecture-name data-explain="[1단원] 우리 고장의 모습"></div>
                        </div>
                    </div>
                    <span class="col-auto h-center order-md-2 order-xl-3 p-md-0">
                        <span class="" data-status data-explain="학습 전"></span>
                    </span>
                </div>
                <input type="hidden" class="student_lecture_detail_seq">
            </div>
        </div>
    </section>
    {{-- div / aside, section--}}
    <section class="row mt-3 px-20 mb-5 gap-4">
        <div class="modal-shadow-style col-4 p-xxl-4 p-2 rounded d-flex flex-column justify-content-between aside-left" style="width:32%">
            <div class="row mx-0 mb-xl-3 mb-lg-3 mb-sm-1 align-items-center">
                <div class="col-auto h-center px-0">
                    <img src="{{ asset('images/calendar_chk_icon.svg') }}" width="32">
                </div>
                <div class="col">
                    <span class="text-b-24px">
                        {{ date('m월 d일')}} 학습현황
                    </span>
                </div>
            </div>
            <!-- 원형 프로그래스 바  -->
            <div class="w-center position-relative">
                <div id="svgContainer">
                    <svg class="w-100 h-100" width="326" height="326" viewBox="0 0 360 360">
                        <!-- 회색 원 -->
                        <circle cx="180" cy="180" r="150" stroke="#F9F9F9" stroke-width="30" fill="none" />
                        <!-- 노란색 원 -->
                        <circle id="progressCircle" cx="180" cy="180" r="150" stroke="#D60000" stroke-width="30" fill="none" stroke-dasharray="942" stroke-dashoffset="942" stroke-linecap="round" />
                    </svg>
                </div>
                <div class="position-absolute m-auto top-0 bottom-0 start-0 end-0 all-center flex-column gap-3">
                    <div class="scale-text-gray_05 text-sb-18px"> 오늘의 목표까지 </div>
                    <div data-num-progress-per class="text-b-52px" style="color:#D60000"> </div>
                </div>
            </div>
            <div class="text-sb-16px scale-text-gray_05 w-center pb-4">
                {{-- <span class="pe-1">현재 학습중</span> --}}
                <span class="pe-1">{{$student_last_url}}</span>
                <!-- 학생의 마지막 로그인 날짜 -->
                <span data-last-login-date>(yy.mm.dd HH:MM)로그인</span>
            </div>
            <!-- 수강시간 수강 -->
            <div class="text-sb-20px scale-text-gray_05 row mx-0 flex-xl-row gap-2">
                <div class="scale-bg-gray_01 rounded col me-xl-2 me-lg-1 p-xl-4 p-lg-3 p-sm-3 mb-xl-0 text-center text-xxl-start">
                    <div class="mb-xl-3 mb-lg-2 mb-sm-1">수강 시간</div>
                    <div>
                        <span class="scale-text-black text-b-28px" data-hour>2</span>시간
                        <span class="scale-text-black text-b-28px" data-min>37</span>분
                    </div>
                </div>

                <div class="scale-bg-gray_01 rounded col ms-xl-2 ms-lg-1 p-xl-4 p-lg-3 p-sm-3 text-center text-xxl-start">
                    <div class="mb-xl-3 mb-lg-2 mb-sm-1">수강 현황</div>
                    <div class="text-b-28px">
                        <span class="scale-text-black main_count" data-complete-cnt>0</span>
                        /
                        <span class="scale-text-black main_count" data-all-cnt>0</span>
                        강
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-shadow-style col rounded p-4">
            <div class="row mx-0 align-items-center">
                <div class="col-auto h-center px-0">
                    <img src="{{ asset('images/window_scope_icon.svg') }}" width="32" class="p-1">
                </div>
                <div class="col">
                    <span class="text-b-24px">
                        월별 학습현황
                    </span>
                </div>
                <div class="gap-4 h-center justify-content-end col-auto">
                    <div class="col-auto all-center">
                        <span class="rounded-pill pt-3 ps-3" style="border:4px solid #f1f1f1;"></span>
                        <span class="text-sb-20px scale-text-gray_05 ms-2">목표 학습</span>
                    </div>
                    <div class="col-auto all-center">
                        <span class="rounded-pill pt-3 ps-3" style="border:4px solid rgb(214, 0, 0);"></span>
                        <span class="text-sb-20px scale-text-gray_05 ms-2">스스로 학습</span>
                    </div>
                </div>
            </div>
            <!-- 그래프  -->
            <div data-div-my-study-week-section-sub="1" class="pt-4 mt-2 mb-4 mb-xxl-5 pb-1">
                <div>
                    <!-- <div class="py-lg-5"></div> -->
                </div>
                <!-- ------------------------------------------------------------------------------  -->
                <!-- 시간단위 -->
                <div class="m-1 row monthly-study-status">
                    <div data-bundle="week_time" class="col-auto m-0 d-flex flex-column position-relative" style="width:60px;">
                        <div class="col mb-2 position-absolute d-flex flex-column study-time-indicator" style="bottom:0px;">
                            <div data-row="6" class="col text-sb-18px scale-text-gray_05"><span data-chart-max-gang>110</span>강</div>
                            <div data-row="5" class="col text-sb-18px scale-text-gray_05">100강</div>
                            <div data-row="4" class="col text-sb-18px scale-text-gray_05">80강</div>
                            <div data-row="3" class="col text-sb-18px scale-text-gray_05">60강</div>
                            <div data-row="2" class="col text-sb-18px scale-text-gray_05">40강</div>
                            <div data-row="1" class="col text-sb-18px scale-text-gray_05">20강</div>
                        </div>
                        <div data-row="0" class="position-absolute text-sb-18px scale-text-gray_05" style="bottom:-10px">0강</div>
                    </div>
                    <div class="col position-relative">
                        <div class="d-flex flex-column ms-xl-4 ms-lg-3 ms-md-0 h-100">
                            <div class="col" style="border-bottom:1px solid #E5E5E5;border-top:1px solid #E5E5E5"></div>
                            <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                            <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                            <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                            <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                            <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                        </div>
                        <!-- ------------------------------------------------------------------------------  -->
                        <!-- 일~토 요일별 학습시간 입력 목표학습 / 스스로 학습 -->
                        <div data-bundle="study_time_by_day" class="row flex-nowrap mx-0 position-absolute top-0 bottom-0 start-0 end-0 ms-xl-4 ms-lg-0 ms-md-0 px-xl-5 ps-lg-0">

                            <div data-row="copy" class="col p-0 row gap-2 align-items-end justify-content-center position-relative" hidden>
                                <!-- 마우스오버 상단 시간 표기 -->
                                <div data-div-self-time="" class="position-absolute text-center mx-0 justify-content-center" style="top: -20px;display:none">
                                    <span class="text-white text-b-20px rounded-3 d-inline-flex align-items-center gap-2" style="background: #473300;padding:12px 12px;">
                                        <div class="col-auto all-center">
                                            <span class="rounded-pill pt-3 ps-3" style="border:4px solid #f1f1f1;"></span>
                                            <span class="text-sb-20px white-text ms-2" data-all-cnt></span>
                                        </div>
                                        <div class="col-auto all-center">
                                            <span class="rounded-pill pt-3 ps-3" style="border:4px solid #FFC747;"></span>
                                            <span class="text-sb-20px white-text ms-2" data-complete-cnt></span>
                                        </div>
                                    </span>
                                    <div class="position-relative">
                                        <svg class="position-absolute" style="width:18px;left: 43%;bottom:-13px" width="18" height="15" viewBox="0 0 18 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7.304 13.2864C8.08734 14.5397 9.91266 14.5397 10.696 13.2864L17.0875 3.06C17.9201 1.7279 16.9624 0 15.3915 0H2.6085C1.03763 0 0.0799387 1.7279 0.912499 3.06L7.304 13.2864Z" fill="#473300" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="bar-wrap p-0">
                                    <!-- 목표 학습 BAR -->
                                    <div data-goal-bar="" class="col-auto rounded-top-3 scale-bg-gray_02 goal-bar" style="height:0%"> </div>
                                    <!-- 스스로 학습 BAR -->
                                    <div data-self-bar="" class="col-auto rounded-top-3 ms-1 self-bar" style="height:0%;background:#D60000"> </div>
                                </div>
                                <div class="text-center bar-month">
                                    <button data-btn-my-study-week-btm="" onclick="myStudyShowWeeklyStudyTimeBtmBtnClick(this)" class="btn btn-outline-primary-r border-0 rounded-pill text-sb-20px scale-text-gray_05 text-nowrap" style="padding:4px 16px">
                                        <span data-month> </span>월
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{-- 팝콘의 새로운 소식 --}}
    <section class="d-none d-lg-none d-xxl-block">
        <div class="row mx-0 mb-4 align-items-center">
            <div class="col-auto h-center px-0">
                <img src="{{ asset('images/bell_icon.svg') }}" width="32">
            </div>
            <div class="col">
                <span class="text-sb-28px">팝콘의 새로운 소식</span>
            </div>
        </div>

        <!-- 공지사항  -->
        <div>
            <div class="">
                <table class="w-100 table-style table-h-82">
                    <thead class="modal-shadow-style rounded">
                        <tr class="text-sb-20px ">
                            <th>구분</th>
                            <th>내용</th>
                            <th>등록일</th>
                        </tr>
                    </thead>
                    <tbody data-bundle="board">
                        <tr data-row="copy" onclick="dashBoardBoardClick(this)" class="text-m-20px cursor-pointer" hidden>
                            <input type="hidden" data-board-seq>
                            <td data-gubun> </td>
                            <td data-content class="text-start black-color"> </td>
                            <td data-created-at> </td>
                        </tr>
                    </tbody>
                </table>
                <div class="w-center mt-5">
                    <button class="btn h-center text-b-20px btn-primary-y rounded-pill text-white ps-3 pe-1 py-2" onclick="dashBoardMovePage('notice')">
                        <span>더 보기</span>
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.9967 22.0645L18.0574 17.0418C18.578 16.5252 18.5811 15.6843 18.0645 15.1638L12.9967 10.0579" stroke="white" stroke-width="3" stroke-miterlimit="10" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="d-none d-lg-none d-xxl-block" data-explain="160">
    <div class="py-lg-5"></div>
    <div class="py-lg-4"></div>
    <div class="pt-lg-3"></div>
</div>

{{--  모달 / 학습결과 --}}
<div class="modal fade " id="modal_div_learning_results" tabindex="-1" aria-labelledby="exampleModalLabel"
    style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content rounded-4 shadow score-container" style="max-width: 100%;">
                <div class="modal-header border-bottom-0 score-head px-4">
                    <h1 class="modal-title cfs-5 msg_title text-start text-b-20px w-100">학습 결과</h1>
                    <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close" >
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 10L10.0003 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round"></path>
                            <path d="M10 10L21.9997 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round"></path>
                        </svg>
                    </button>
                </div>
                <div class="score-wrap row" data-bundle="score">
                    <div class="score-body p-0 col" data-row="copy" hidden>
                    <div class="score-grid mt-4 mx-4 text-b-20px" >
                        <span data-name="evaluation"> </span>
                        <span data-name="exam_status"> </span>
                    </div>
                    <div class="score-grid-final-result">
                        <div class="score-grid-final-result-title">
                            <span>기본 문제</span>
                        </div>
                        <div class="score-grid-final-result-score">
                            <div class="">
                                <span class="text-gray">맞힌 문제</span>
                                <span class="mx-2 border dividing"></span>
                                <span class="" data-name="suc1">0 </span>문제
                            </div>
                            <div class="">
                                <span class="text-gray">틀린 문제</span>
                                <span class="mx-2 border dividing"></span>
                                <span data-name="fail1">0 </span>문제
                            </div>
                        </div>
                    </div>
                    <div class="score-grid-final-result">
                        <div class="score-grid-final-result-title">
                            <span>유사 문제</span>
                        </div>
                        <div class="score-grid-final-result-score">
                            <div class="">
                                <span class="text-gray">맞힌 문제</span>
                                <span class="mx-2 border dividing"></span>
                                <span data-name="suc2">0 </span>문제
                            </div>
                            <div class="">
                                <span class="text-gray">틀린 문제</span>
                                <span class="mx-2 border dividing"></span>
                                <span data-name="fail2">0 </span>문제
                            </div>
                        </div>
                    </div>
                    <div class="score-grid-final-result">
                        <div class="score-grid-final-result-title">
                            <span>도전 문제</span>
                        </div>
                        <div class="score-grid-final-result-score">
                            <div class="">
                                <span class="text-gray">맞힌 문제</span>
                                <span class="mx-2 border dividing"></span>
                                <span data-name="suc3">0 </span>문제
                            </div>
                            <div class="">
                                <span class="text-gray">틀린 문제</span>
                                <span class="mx-2 border dividing"></span>
                                <span data-name="fail3">0 </span>문제
                            </div>
                        </div>
                    </div>
                </div>

            </div>

                <div class="modal-footer align-items-stretch w-100 gap-2 pb-3 border-top-0 flex-row-reverse pt-4" style="display: none;">


                <button type="button" class="msg_btn1 btn btn-lg btn-primary-y text-b-24px col py-3">확인</button><button type="button" class="msg_btn2 btn btn-lg btn-light ctext-gc1 text-b-24px col">취소</button></div>
            </div>
        </div>
    </div>
<script>
    const scoreData = {
        questions: [{
                number: 1,
                result: {
                    type: "incorrect",
                    symbol: "stars"
                },
                challenge: {
                    result: {
                        type: "correct",
                        symbol: "circle"
                    }
                }
            },
            {
                number: 2,
                result: {
                    type: "partiallyCorrect",
                    symbol: "triangle"
                },
                challenge: {
                    result: {
                        type: "correct",
                        symbol: "circle"
                    }
                },
                similar: {
                    result: {
                        type: "correct",
                        symbol: "circle"
                    }
                }
            },
            {
                number: 3,
                result: {
                    type: "partiallyCorrect",
                    symbol: "triangle"
                },
                challenge: {
                    result: {
                        type: "partiallyCorrect",
                        symbol: "triangle"
                    }
                }
            }
        ],
        finalResult: {
            title: "기본 문제",
            correctAnswers: 3,
            incorrectAnswers: 2
        }
    };
    $(document).ready(function() {
        if ($('.pop-alert').length) {
            $('.pop-alert').animate({
                top: '1%',
                opacity: '1'
            }, {
                duration: 700,
                easing: 'swing' // 'ease' 대신 'swing' 사용
            });
        }
        setTimeout(function() {
            if ($('.pop-alert').length) {
                $('.pop-alert').animate({
                    top: '-100%',
                    opacity: '1'
                }, {
                    duration: 700,
                    easing: 'swing' // 'ease' 대신 'swing' 사용
                });
            }
        }, 4500);
        $(document).on("click", ".close-pop-alert", function() {
            $(this).closest(".pop-alert").fadeOut(300);
        });
        var barHtml = `
            <div data-div-self-time="" class="position-absolute text-center row mx-0 justify-content-center" style="min-width: 210px; z-index: 99;">
                <span class="text-white text-b-20px rounded-3 w-center gap-2" style="background: #473300;padding:12px 12px;">
                    <div class="col-auto all-center">
                        <span class="rounded-pill pt-3 ps-3" style="border:4px solid #f1f1f1;"></span>
                        <span class="text-sb-20px scale-text-gray_05 ms-2">43강</span>
                    </div>
                    <div class="col-auto all-center">
                        <span class="rounded-pill pt-3 ps-3" style="border:4px solid #FFC747;"></span>
                        <span class="text-sb-20px scale-text-gray_05 ms-2">23강</span>
                    </div>
                </span>
                <div class="position-relative">
                    <svg class="position-absolute" style="width:18px;left: 43%;bottom:-13px" width="18" height="15" viewBox="0 0 18 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.304 13.2864C8.08734 14.5397 9.91266 14.5397 10.696 13.2864L17.0875 3.06C17.9201 1.7279 16.9624 0 15.3915 0H2.6085C1.03763 0 0.0799387 1.7279 0.912499 3.06L7.304 13.2864Z" fill="#473300"></path>
                    </svg>
                </div>
            </div>
        `
        $(document).on("mouseenter", ".bar-wrap .goal-bar, .bar-wrap .self-bar", function() {
            const self_bar = $(this).parent().find("[data-self-bar]");
            const goal_bar = $(this).parent().find("[data-goal-bar]");
            const bar_h = $(this).parent().height();
            const hover_active = $(this).next();
            var h = Math.max(self_bar.height(), goal_bar.height());
            const $barHtml = $(this).closest('[data-row]').find('[data-div-self-time]');
            $barHtml.stop(true, true).fadeIn(300);
            console.log(bar_h);
            $barHtml.css('top', (bar_h - h - $barHtml.height() - 20) + "px");
            hover_active.find('button').addClass('active');
        });

        $(document).on("mouseleave", ".bar-wrap .goal-bar, .bar-wrap .self-bar", function() {
            const hover_active = $(this).parent().next();
            $(this).closest('[data-row="copy"]').find("[data-div-self-time]").stop(true, true).fadeOut(300);
            hover_active.find('button').removeClass('active');
        });

        $(document).on("click", ".owl-stage .owl-item", function() {


            const myModal = new bootstrap.Modal(document.getElementById('modal_div_learning_results'), {
                keyboard: false,
                backdrop: 'static'
            });
            // 모달이 닫힐대 이벤트.

            document.getElementById('modal_div_learning_results').addEventListener('hidden.bs.modal', function () {
                document.querySelector('.modal-backdrop')?.remove();
            });

            const student_lecture_detail_seq = this.querySelector('.student_lecture_detail_seq').value;
            dashBoardLectureDetailSelect(student_lecture_detail_seq, myModal);
        });
    });

    function modalResize() {
        window.addEventListener('resize', function() {
            if (window.innerWidth > 1200) {
                $('.modal-dialog').addClass('modal-fullscreen');
            } else {
                $('.modal-dialog').removeClass('modal-fullscreen');
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        // 오늘 학습현황 & 상단 목표학습
        dashBoardLectureDateSelect();
        // 월별 학습현황
        dashBoardLectureMonthSelect();
        // 공지사항
        dashBoardBoardList();
    });

    function owlInit() {

    }


    // 프로그래스바 수치 넣기.
    function dashBoardSetProgress(percent) {
        const progressCircle = document.getElementById('progressCircle');
        const radius = progressCircle.r.baseVal.value;
        const circumference = 2 * Math.PI * radius;
        const num_progress = document.querySelector('[data-num-progress-per]');
        progressCircle.style.strokeDasharray = `${circumference}`;
        progressCircle.style.strokeDashoffset = `${circumference}`;
        const offset = circumference - (percent / 100) * circumference;
        progressCircle.style.strokeDashoffset = offset;
        num_progress.textContent = `${percent || 0}%`;
    }

    // 공지사항.
    function dashBoardBoardList() {
        const type = 'notice';
        const board_name = type;
        const board_page_max = 4;
        const is_content = 'Y';

        const page = "/manage/board/select";
        const parameter = {
            board_name: board_name,
            page: 1,
            board_page_max: board_page_max,
            is_content: is_content
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                // 초기화
                const bundle = document.querySelector('[data-bundle="board"]');
                const copy_row = bundle.querySelector('[data-row="copy"]').cloneNode(true);
                bundle.innerHTML = '';
                bundle.appendChild(copy_row);

                const boards = result.board;
                boards.data.forEach(board => {
                    const row = copy_row.cloneNode(true);
                    row.hidden = false;
                    row.setAttribute('data-row', 'clone');
                    row.querySelector('[data-board-seq]').value = board.id;
                    // 오늘로부터 3일 전까지면 gubun은 NEW 아니면
                    if (board.created_at > new Date(new Date().getTime() - 3 * 24 * 60 * 60 * 1000)) {
                        row.querySelector('[data-gubun]').innerText = 'NEW';
                        row.querySelector('[data-gubun]').classList.add('text-danger');
                    } else {
                        row.querySelector('[data-gubun]').innerText = board.category_name;
                    }
                    // row.querySelector('[data-content]').innerHTML = board.content;
                    row.querySelector('[data-content]').innerHTML = board.title;
                    row.querySelector('[data-created-at]').innerText = (board.created_at || '').substr(2, 8).replace(/-/g, '.');
                    bundle.appendChild(row);
                });
            }
        });
    }

    // 페이지 이동
    function dashBoardMovePage(type) {
        switch (type) {
            case 'notice':
                location.href = "/student/notice";
                break;
        }
    }

    // 오늘 학습현황.
    function dashBoardLectureDateSelect() {
        const page = "/parent/index/study/lecture/date/select";
        const parameter = {};
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                const details = result.student_lecture_details;
                const last_login_date = result.last_login_date;
                let all_cnt = 0;
                let complete_cnt = 0;
                let total_watching_hours = 0;

                // 초기화
                const bundle = document.querySelector('[data-bundle="top_prestudy_review"]');
                const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
                bundle.innerHTML = '';

                details.forEach(function(detail) {
                    all_cnt++;
                    total_watching_hours += detail.last_video_time * 1;
                    const row = row_copy.cloneNode(true)
                    row.setAttribute('data-row', 'clone');
                    row.hidden = false;
                    row.querySelector('.student_lecture_detail_seq').value = detail.id;
                    row.querySelector('[data-function-code]').src = `/images/${detail.function_code}.svg`;
                    row.querySelector('[data-course-name]').innerHTML = detail.course_name;
                    row.querySelector('[data-lecture-name').innerHTML = detail.lecture_name;
                    row.classList.add(detail.function_code);
                    if (detail.status == 'complete') {
                        complete_cnt++;
                        row.querySelector('[data-status]').innerText = '학습 완료';
                        row.querySelector('[data-status]').classList.add('studey-completion');
                    } else if (detail.status == 'ready') {
                        row.querySelector('[data-status]').innerText = '학습 전';
                        row.querySelector('[data-status]').classList.add('studey-before');
                    } else if (detail.status == 'study') {
                        row.querySelector('[data-status]').innerText = '학습 중';
                        row.querySelector('[data-status]').classList.add('studey-doing');
                    }

                    bundle.appendChild(row);
                });

                // 완료율을 구해서, dashBoardSetProgress() 에 넣는다.
                const percent = (complete_cnt / all_cnt) * 100;

                dashBoardSetProgress(Math.floor(percent));
                dashBoardFormatTime(total_watching_hours);
                document.querySelectorAll('[data-complete-cnt].main_count').forEach(function(el){
                    el.innerText = complete_cnt;
                });
                document.querySelectorAll('[data-all-cnt].main_count').forEach(function(el) {
                    el.innerText = `${all_cnt}`;
                });
                document.querySelectorAll('[data-last-login-date]').forEach(function(el) {
                    el.innerText = `(${last_login_date.slice(0, 16).slice(2, 16).replace(/-/g, '.')} 로그인)`;
                });

                const owl = $('[data-bundle="top_prestudy_review"]');
                owl.owlCarousel({
                    items: 3,
                    loop: false,
                    nav: false,
                    dots: false,
                    onInitialized: owlInit,
                    onInitialized: function(event) {
                        const owlInstance = event.target;
                        const itemsCount = $(owlInstance).find('.owl-item').length;
                        console.log(itemsCount)
                        const width = itemsCount <= 3 ? '32.33%' : '32.33%';
                        $('.aside-left').css('width', width);
                    }
                });
                // owlCarousel에서 item 값을 추출하는 코드
                const owlInstance = owl.data('owl.carousel');
                if (owlInstance.options.items != 3) {
                    $('.owl-carousel .owl-stage').css('width', `${100}%`);
                    $('.owl-carousel .owl-stage').find('.owl-item').css('width', `${100 / owlInstance.options.items}%`);
                    $('.owl-carousel .owl-stage').addClass('overflow-visible');
                    if($(owlInstance).find('.owl-item.active').length > 3){
                        $('.owl-btn-wrap').attr('hidden', false);
                    }
                } else {
                    $('.owl-carousel .owl-stage').find('.owl-item').css('width', `${$('.owl-stage-outer').width() / owlInstance.options.items -20}px`);
                    if($('.owl-item').length > 3){
                        $('.owl-btn-wrap').attr('hidden', false);
                    }
                }

                $('.owl-carousel .owl-stage-outer').addClass('overflow-visible d-flex gap-4');
                $('.owl-carousel .owl-stage').addClass('overflow-visible d-flex gap-4');
                $('.owl-carousel').addClass('overflow-hidden');
                $('.owl-carousel').attr('style', 'padding: 8px 8px !important');

                function updateOwlButtons() {
                    if (owlInstance.current() === 0) {
                        $('[data-btn-owl-prev]').prop('disabled', true);
                    } else {
                        $('[data-btn-owl-prev]').prop('disabled', false);
                    }
                    if (owlInstance.current() === 0) {
                        $('[data-btn-owl-next]').prop('disabled', true);
                        if(owl[0].querySelectorAll('[data-row]').length > 3){
                            $('[data-btn-owl-next]').prop('disabled', false);
                        }
                    } else {
                        $('[data-btn-owl-next]').prop('disabled', false);
                    }
                }


                $('[data-btn-owl-next]').click(function() {
                    owlInstance.next();
                    updateOwlButtons();
                });
                $('[data-btn-owl-prev]').click(function() {
                    owlInstance.prev();
                    updateOwlButtons();
                });

                updateOwlButtons();
            }
        });

    }
    // 월별 학습현황.
    function dashBoardLectureMonthSelect() {
        const page = "/parent/index/study/lecture/month/select";
        const parameter = {};
        queryFetch(page, parameter, function(result) {

            // 초기화
            const bundle = document.querySelector('[data-bundle="study_time_by_day"]');
            const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

            if ((result.resultCode || '') == 'success') {

                // | sel_month | total_cnt | complete_cnt |
                // +-----------+-----------+--------------+
                // | 2024-07   |        68 |            6 |
                // | 2024-08   |        38 |            0 |
                // | 2024-09   |         4 |            0 |
                //
                //
                const counts = result.lecture_month_count;
                let max_count = 0;
                let column_max = 0;
                counts.forEach(function(count) {

                    //50 160사이 값 랜덤.TEST:
                    // count.total_cnt = Math.floor(Math.random() * (160 - 50 + 1)) + 50;
                    if (count.total_cnt*1 > max_count*1) {
                        max_count = count.total_cnt*1;
                        //max_count 의 가장 가까운 위로 10의 자리수를 구한다.(무조건 올림처리.)
                        column_max = Math.ceil(max_count / 10) * 10;
                    }
                    if(count.complete_cnt*1 > max_count*1){
                        max_count = count.complete_cnt*1;
                        column_max = Math.ceil(max_count / 10) * 10;
                    }
                });
                counts.forEach(function(count) {
                    const row = row_copy.cloneNode(true);
                    row.hidden = false;
                    row.querySelector('[data-all-cnt]').innerHTML = `${count.total_cnt}강`;
                    row.querySelector('[data-complete-cnt]').innerHTML = `${count.complete_cnt}강`;
                    //110강이 100%
                    //단, 100강이 110강이 넘으면, 다르게 계산
                    if(max_count > 110){
                        // 14.2857*6
                        // 마지막 한칸의 수치.
                        // 한칸은 14.2857이므로,
                        const one_count = column_max - 100;
                        const hundred_persent = 16.66666667*5;
                        document.querySelector('[data-chart-max-gang]').innerText = column_max;
                        row.querySelector('[data-goal-bar]').style.height = count.total_cnt > 100 ? (hundred_persent + (16.66666667 * ((count.total_cnt-100) /one_count))) +'%':(hundred_persent * (count.total_cnt / 100 )) + '%';
                        row.querySelector('[data-self-bar]').style.height = count.complete_cnt > 100 ? (hundred_persent + (16.66666667 * ((count.complete_cnt-100) /one_count)))+'%':(hundred_persent * (count.complete_cnt / 100 )) + '%';
                        row.querySelector('[data-month]').innerHTML = count.sel_month.substr(5, 2);
                    }else{
                        row.querySelector('[data-goal-bar]').style.height = (count.total_cnt / 110 * 100) + '%';
                        row.querySelector('[data-self-bar]').style.height = (count.complete_cnt / 110 * 100) + '%';
                        row.querySelector('[data-month]').innerHTML = count.sel_month.substr(5, 2);
                    }
                    bundle.appendChild(row);
                });
            }
        });
    }

    function dashBoardFormatTime(time) {
        const hour = Math.floor(time / 360)
        const minutes = Math.floor((time % 360) / 60);
        const seconds = Math.floor(time % 60);
        const time_data = {
            hour: hour,
            minutes: minutes
        };
        document.querySelector('[data-hour]').innerText = time_data.hour;
        document.querySelector('[data-min]').innerText = time_data.minutes;
    }

    // student_foot.blade.php 로 이동.
    // 공지사항 이벤트 클릭
    // function dashBoardBoardClick(vthis){
    //     const board_seq = vthis.querySelector('[data-board-seq]').value;
    //     const board_name = 'notice';
    //     // data-btn-board-close 에 onclick = layoutBoardCloseDetail(callback) 넣어주기.
    //     // :게시판 상세보기 양식1
    //     const btn_detail_close = document.querySelector('[data-btn-board-close]');
    //     btn_detail_close.setAttribute('onclick', "dashBoardBoardClose();");
    //     const parameter = {
    //         board_seq: board_seq,
    //         board_name: board_name
    //     };
    //     layoutBoardDetail(parameter, function(){
    //         // 게시판 상세 페이지 열릴때
    //         const myModal = new bootstrap.Modal(document.getElementById('modal_div_board_detail'), {
    //             keyboard: false,
    //             backdrop: 'true'
    //         });
    //         myModal.show();
    //     });
    // }
    //
    // // 공지사항 상세보기 모달 닫기.
    // function dashBoardBoardClose(){
    //     layoutBoardCloseDetail(function(){
    //         // 게시판 상세 페이지 닫힐때
    //        const modal = document.getElementById('modal_div_board_detail');
    //         modal.querySelector('.btn-close').click();
    //     });
    // }

    function dashBoardLectureDetailSelect(seq, myModal){
        const page = "/parent/index/study/lecture/detail/select";
        const parameter = {
            student_lecture_detail_seq: seq
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                const student_exams = result.student_exams;
                const keys = Object.keys(student_exams);
                const bundle = document.querySelector('[data-bundle="score"]');
                const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
                bundle.innerHTML = '';
                bundle.appendChild(row_copy);
                const modal_dialog = bundle.closest('.modal-dialog');

                keys.forEach(key => {
                    const exams = student_exams[key];
                    const row = row_copy.cloneNode(true);
                    row.hidden = false;
                    row.dataset.row = 'clone';
                    let suc1 = 0; let fail1 = 0; let suc2 = 0; let fail2 = 0; let suc3 = 0; let fail3 = 0;
                    let evaluation = ''; let exam_status = '';
                    exams.forEach(exam => {
                        evaluation = exam.code_name;
                        exam_status = exam.student_exam_status == 'complete' ? '(완료)':'(미완료)';
                        if(exam.exam_type == 'normal'){
                            if(exam.exam_status == 'correct'){ suc1 += 1; }else{ fail1 += 1; }
                        }
                        if(exam.exam_type == 'similar'){
                            if(exam.exam_status == 'correct'){ suc2 += 1; }else{ fail2 += 1; }
                        }
                        if(exam.exam_type == 'challenge'){
                            if(exam.exam_status == 'correct'){ suc3 += 1; }else{ fail3 += 1; }
                        }
                        if(exam.exam_type == 'challenge_similar'){
                            if(exam.exam_status == 'correct'){ suc3 += 1; }else{ fail3 += 1;
                            }
                        }
                    });
                    if(keys.length > 1){
                        modal_dialog.classList.remove('modal-md');
                        modal_dialog.classList.add('modal-lg');
                    }else{
                        modal_dialog.classList.remove('modal-lg');
                        modal_dialog.classList.add('modal-md');
                    }
                    row.querySelector('[data-name="evaluation"]').innerText = evaluation.replace('기본문제', '문제풀기');
                    row.querySelector('[data-name="exam_status"]').innerText = exam_status;
                    row.querySelector('[data-name="suc1"]').innerText = suc1;
                    row.querySelector('[data-name="fail1"]').innerText = fail1;
                    row.querySelector('[data-name="suc2"]').innerText = suc2;
                    row.querySelector('[data-name="fail2"]').innerText = fail2;
                    row.querySelector('[data-name="suc3"]').innerText = suc3;
                    row.querySelector('[data-name="fail3"]').innerText = fail3;

                    bundle.appendChild(row);

                    myModal.show();
                });
            }
        });
    }
</script>
@endsection
