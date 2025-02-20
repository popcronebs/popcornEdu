@extends('layout.layout')
{{-- 타이틀 --}}


@section('add_css_js')
    <script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="{{ asset('js/owl.js') }}"></script>
    <script src="{{ asset('js/mainpage.js?4') }}" defer></script>
    <link href="{{ asset('css/owl1.css') }}" rel="stylesheet">
    <link href="{{ asset('css/owl2.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main_page.css') }}" rel="stylesheet">
@endsection

{{-- 학생 컨텐츠 --}}

<!-- NOTE: 학습현황. -->

<!-- : 상단 출석율 -->
<!-- : 목표 수행율 -->
<!-- : 스스로 학습. -->

<!-- : 과목 별 학습시간. -->
<!-- : 주간 학습 상세. -->

<!-- : 주간에서 시작날짜 끝날짜 검색에 넘기기. -->
<!-- : 요일별 학습시간  -->
<!-- :주차 css 형태 변환 -->
<!-- :주간 출결 현황. -->

<!-- TODO: 과목별 목표달성 현황. -->
<!-- TODO: n월 출결 현황. -->
<!-- TODO: 월별 출결현황. -->

<!-- NOTE: 수강현황. -->

<!-- : 상단 합산 추치. -->
<!-- : 수강중 강좌, 수강완료 강좌 리스트 -->
<!-- : 관심 강좌 리스트 -->
<!-- : 미수강 리스트 -->

<!-- : 수강중인 강좌 -->
<!-- : 수강완료 수강중인 강좌 -->
<!-- : 관심강좌 -->
<!-- : 최근 자주본 강좌  -->
<!-- : 미수강 강좌(강의) -->
<!-- : 검색 기능. -->
<!-- : 미수강, 재수강 주차 select box 기능 추가. -->
<!-- : 미수강, 재수강 목표 목표학습일 변경된 내용 수정. -->
<!-- TODO: 재수강 > 학습결과 -->
<!-- TODO: 과목별 수강현황 (갑자기 나온 화면이라, 일단은 뒤로 미룸.) -->

@section('layout_coutent')

<style>
.div_week_bundle button span,
.div_month_bundle button span {
    color: #999999;
}

.div_week_bundle button.active span,
.div_week_bundle button:hover span,
.div_month_bundle button.active span,
.div_month_bundle button:hover span {
    color: white;
}

[data-div-weeky-study-head] {
    transition: all .2s ease 0s;
}

/* owl active 와 겹치는 현상으로인해서 css 수정. */
[data-main-section-my-study-course-status] .active .primary-bg-mian-hover{
    background-color:#f9f9f9 !important;
}
[data-main-section-my-study-course-status] .active .primary-text-light-hover.scale-text-gray_05,
[data-main-section-my-study-course-status] .active .text-b-24px.scale-text-white-hover{
    color:#999999 !important;
}
[data-main-section-my-study-course-status] .active .text-b-42px.scale-text-white-hover{
    color:#222 !important
}

[data-main-section-my-study-course-status] [data-div-my-study-course-status-main-tab].active.primary-bg-mian-hover{
    background-color: #FFBD19 !important;
}
[data-main-section-my-study-course-status] [data-div-my-study-course-status-main-tab].active .primary-text-light-hover{
    color: #FFE39E !important;
}

[data-main-section-my-study-course-status] [data-div-my-study-course-status-main-tab].active .scale-text-white-hover{
    color:white !important;
}
[data-btn-my-study-aside-tab="month"] {
    margin-top: 10px;
}
.table-style.table-h-96 td{
    height: 76px;
}
.scale-text-white-hover.active{
    color: #fff !important;
}
@media all and (max-width: 1400px) {
    [data-btn-my-study-aside-tab="month"] {
        margin-top: 0px;
    }
    .owl-carousel-arrow-wrap{
        position: absolute;
        top: 16px;
        width: 104%;
        left: 50%;
        transform: translateX(-50%);
    }
    .table-style thead tr th{
        padding: 16px 0px;
        vertical-align: middle;
    }
}

</style>
<input type="hidden" id="inpage_type" value="{{$type}}">
<div class="col pe-3 ps-3 mb-3 pt-5 row position-relative zoom_sm">
    <input type="hidden" data-main-login-type="" value="{{$login_type}}">

    {{-- 나의 학습 시작 --}}
    <article class="pt-5 mt-4">
        <section>
            <div class="row">
                <div class="col-lg">
                    <div class="h-center" data-page="lecture">
                        <img src="{{ asset('images/my_study_icon2.svg') }}" width="72">
                        <span class="cfs-1 fw-semibold align-middle">{{$login_type == 'parent'?'학습관리':'나의 학습'}}</span>
                    </div>

                    <div class="h-center" hidden data-page="lecture_detail">
                        <button data-btn-back-page class="btn p-0 row mx-0 all-center" onclick="myStudyMainDivChagne('2');">
                            <img src="{{ asset('images/black_arrow_left_tail.svg') }}" width="52" class="px-0">
                        </button>
                        <span class="cfs-1 fw-semibold align-middle" data-title-student-name>강좌 상세</span>
                    </div>
                    <div class="pt-2" {{$login_type == 'parent'?'hidden':''}}>
                        <span class="cfs-3 fw-medium">평가문제로 내 실력을 확인해보세요.</span>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="d-flex pt-2 gap-3">
                        @if($login_type == 'parent')
                        <button onclick="myStudyTopTab(this)" data-btn-my-study-top-tab="0"
                            class="btn all-center px-4 py-2 rounded-pill primary-bg-mian-hover scale-text-gray_05 scale-text-white-hover active">
                            <img src="{{asset('images/note_pencil_icon.svg')}}" width="32">
                            <span class="text-sb-24px">학습관리</span>
                        </button>
                        @endif
                        <button data-btn-my-study-top-tab="1" onclick="myStudyTopTab(this);"
                            class="btn btn-outline-primary-y rounded-pill cbtn-p-i fw-medium h-center cfs-5 ctext-gc1 border-0 {{$login_type == 'parent'?'':( $type == 'misu'?'':'active' )}}">
                            <img src="{{ asset('images/note_pencil_icon.svg') }}" width="24">
                            학습현황
                        </button>
                        <button data-btn-my-study-top-tab="2" onclick="myStudyTopTab(this)"
                            class="btn btn-outline-primary-y rounded-pill cbtn-p-i fw-medium h-center cfs-5 ctext-gc1 border-0 {{$type == 'misu'?'active':''}}">
                            <img src="{{ asset('images/note_pencil_icon.svg') }}" width="24">
                            수강현황
                        </button>
                        @if($login_type == 'parent')
                        <button onclick="myStudyTopTab(this)" data-btn-my-study-top-tab="4"
                            class="btn all-center px-4 py-2 rounded-pill primary-bg-mian-hover scale-text-gray_05 scale-text-white-hover">
                            <img src="{{asset('images/note_pencil_icon.svg')}}" width="32">
                            <span class="text-sb-24px">학습자료</span>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </article>

    {{-- 사이 padding 120px --}}
    <div>
        <div class="py-0 py-lg-0 py-xxl-5"></div>
        <div class="pt-0 pt-lg-0 pt-xxl-4"></div>
    </div>

    {{-- 학습현황. //----------------------------------------------//----------------------------------------------//---------------------------------------------- --}}
    <div data-div-my-study-main="1" class="row" {{$login_type=='parent'?'hidden':($type=='misu'?'hidden':'')}}>
        {{-- 사이드 --}}
        <aside data-aside-my-study class="col-lg-12 ps-0 pe-0 pe-lg-2 pe-xxl-2">
            {{-- 주간현황, 월간현황 tab --}}
            <div class="shadow-sm-2 p-4 rounded-2 mt-4 d-none">
                <div class="pt-0">
                    <button data-btn-my-study-aside-tab="week" onclick="myStudyAsideTab(this);"
                        class="btn btn-outline-primary-y ctext-gc1 w-100 border-0 rounded-4 p-3 d-flex justify-content-center align-items-center h-center cfs-5 gap-1 active">
                        <img src="{{ asset('images/calendar_week_icon.svg') }}">
                        주간현황
                    </button>
                </div>
                <div class="pt-0">
                    <button data-btn-my-study-aside-tab="month" onclick="myStudyAsideTab(this)" hidden
                        class="btn btn-outline-primary-y ctext-gc1 w-100 border-0 rounded-4 h-center cfs-5 p-3">
                        <img src="{{ asset('images/calendar_month_icon.svg') }}" alt="">
                        월간현황
                    </button>
                </div>
            </div>

            {{-- 하단 년월/주차 --}}
            <div class="shadow-sm-2 p-2 p-xl-4 mt-0 mt-xxl-4 rounded-2" data-div-my-study-aside-sub="week">
                <div class="row align-items-center justify-content-between mb-2">
                    <button class="btn col-auto" onclick="myStudyMonthChange('prev')">
                        <img src="{{ asset('images/calendar_arrow_left.svg') }}" width="32">
                    </button>
                    <span id="my_study_span_month" class="align-middle col text-center cfs-5 fw-semibold"
                        data="{{ date('Y-m-d') }}">
                        {{ date('Y년 n월') }}
                    </span>
                    <button class="btn col-auto" onclick="myStudyMonthChange('next')">
                        <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="32">
                    </button>
                </div>
                <div class="row row-cols-5 row-cols-lg-5 row-cols-xl-5 row-cols-xxl-5 div_week_bundle mt-4">
                    <div class="col div_week_row p-0" hidden>
                        <button class="btn btn-outline-primary-y ctext-gc1 border-0 rounded-pill cfs-5 p-2"
                            onclick="myStudyWeekBtnClick(this)">
                            <div class="p-1">
                                <span class="week_cnt">n</span>
                                <span>주차</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            {{-- 년 --}}
            <div class="modal-shadow-style p-4 mt-4" data-div-my-study-aside-sub="month" hidden>
                <div class="row align-items-center justify-content-between mb-2">
                    <button class="btn col-auto" onclick="myStudyYearChange('prev')">
                        <img src="{{ asset('images/calendar_arrow_left.svg') }}" width="32">
                    </button>
                    <span id="my_study_span_year" class="align-middle col text-center cfs-5 fw-semibold"
                        data="{{ date('Y-m-d') }}">
                        {{ date('Y년') }}
                    </span>
                    <button class="btn col-auto" onclick="myStudyYearChange('next')">
                        <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="32">
                    </button>
                </div>
                <div class="row row-cols-3 div_month_bundle mt-4">
                    <div class="col div_month_row p-0 pe-1" hidden>
                        <button class="btn btn-outline-primary-y ctext-gc1 border-0 rounded-pill cfs-5 p-2 px-4"
                            onclick="myStudyMonthBtnClick(this)">
                            <div class="p-1">
                                <span class="month_cnt">n</span>
                                <span>월</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

        </aside>

        {{-- 나의 학습 섹션 --}}
        @include('student.student_my_study_week_learning')

    </div>

    {{-- 수강현황 //----------------------------------------------//----------------------------------------------//---------------------------------------------- --}}
    <div data-div-my-study-main="2" class="px-0 position-relative" {{$type=='misu'?'':'hidden'}}>

        <div class="row justify-content-between gap-2 px-0 px-xxl-2 mx-0 pb-4 owl-carousel-arrow-wrap">
            <button class="btn p-0 rounded-circle all-center"
                data-btn-course-prev
                style="width:22px;height:42px">
                <img src="{{ asset('images/calendar_arrow_left.svg') }}" width="24">
            </button>
            <button class="btn p-0 rounded-circle all-center"
                data-btn-course-next
                style="width:22px;height:42px">
                <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="24">
            </button>
        </div>

        {{-- bundle --}}
        <section class="owl-carousel owl-theme-none" data-main-section-my-study-course-status>
            {{-- row --}}
                <div data-div-my-study-course-status-main-tab="1" onclick="myStudyCourseStatusMainTab(this);"
                class="rounded-3 scale-bg-gray_01 primary-bg-mian-hover cursor-pointer h-100 p-3 p-xxl-4 active">
                    <div class="row mx-0">
                        <div class="col px-0">
                            <div class="text-sb-24px scale-text-gray_05 primary-text-light-hover">{{$student->student_name??''}} 학생의</div>
                            <div class="text-r-24px scale-text-gray_05 primary-text-light-hover pt-2">
                                <span class="text-b-24px scale-text-white-hover">수강중인 강좌</span> 입니다.
                            </div>
                        </div>
                        <div class="col-auto">
                            <span class="text-b-42px scale-text-white-hover">{{$student_lectures->where('start_date', '<=', date('Y-m-d 23:59:59'))->where('end_date', '>=', date('Y-m-d'))->count()}}</span>
                            <span class="text-b-20px scale-text-gray_05 primary-text-light-hover">강</span>
                        </div>

                    </div>
                    {{-- 56 --}}
                    <div class="pt-3 pt-xxl-5 mt-2 d-none">
                        <span class="text-sb-18px scale-text-gray_05 primary-text-light-hover">신청한 강좌 중 수강 중인 강좌
                            개수</span>
                    </div>
                </div>
            <div class="">
                <div data-div-my-study-course-status-main-tab="2" onclick="myStudyCourseStatusMainTab(this);"
                    class="rounded-3 scale-bg-gray_01 primary-bg-mian-hover cursor-pointer h-100 p-3 p-xxl-4 ">
                    <div class="row mx-0">
                        <div class="col px-0">
                            <div class="text-sb-24px scale-text-gray_05 primary-text-light-hover">{{$student->student_name??''}} 학생의</div>
                            <div class="text-r-24px scale-text-gray_05 primary-text-light-hover pt-2">
                                <span class="text-b-24px scale-text-white-hover">수강완료 강좌</span> 입니다.
                            </div>
                        </div>
                        <div class="col-auto">
                            <span class="text-b-42px scale-text-white-hover">
                                {{ $student_lectures->where('end_date', '<', date('Y-m-d'))->count() }}
                            </span>
                            <span class="text-b-20px scale-text-gray_05 primary-text-light-hover">강</span>
                        </div>

                    </div>
                    {{-- 56 --}}
                    <div class="pt-3 pt-xxl-5 mt-2 d-none">
                        <span class="text-sb-18px scale-text-gray_05 primary-text-light-hover">신청한 강좌 중 완료된 강좌
                            개수</span>
                    </div>
                </div>
            </div>
            <div class="">
                <div data-div-my-study-course-status-main-tab="3" onclick="myStudyCourseStatusMainTab(this);"
                    class="rounded-3 scale-bg-gray_01 primary-bg-mian-hover cursor-pointer h-100 p-3 p-xxl-4 ">
                    <div class="row mx-0">
                        <div class="col px-0">
                            <div class="text-sb-24px scale-text-gray_05 primary-text-light-hover">{{$student->student_name??''}} 학생의</div>
                            <div class="text-r-24px scale-text-gray_05 primary-text-light-hover pt-2">
                                <span class="text-b-24px scale-text-white-hover">관심 강좌</span> 입니다.
                            </div>
                        </div>
                        <div class="col-auto">
                            <span data-is-like-cnt class="text-b-42px scale-text-white-hover">{{$lecture_of_interest_cnt}}</span>
                            <span class="text-b-20px scale-text-gray_05 primary-text-light-hover">강</span>
                        </div>

                    </div>
                    {{-- 56 --}}
                    <div class="pt-3 pt-xxl-5 mt-2 d-none">
                        <span class="text-sb-18px scale-text-gray_05 primary-text-light-hover">관심 강좌로 지정한 강의 개수</span>
                    </div>
                </div>
            </div>
            <div class="">
                <div data-div-my-study-course-status-main-tab="4" onclick="myStudyCourseStatusMainTab(this);"
                    class="rounded-3 scale-bg-gray_01 primary-bg-mian-hover cursor-pointer h-100 p-3 p-xxl-4 ">
                    <div class="row mx-0">
                        <div class="col px-0">
                            <div class="text-sb-24px scale-text-gray_05 primary-text-light-hover">{{$student->student_name??''}} 학생의</div>
                            <div class="text-r-24px scale-text-gray_05 primary-text-light-hover pt-2">
                                <span class="text-b-24px scale-text-white-hover">미수강</span> 입니다.
                            </div>
                        </div>
                        <div class="col-auto">
                            <span class="text-b-42px scale-text-white-hover">{{$not_complete_cnt}}</span>
                            <span class="text-b-20px scale-text-gray_05 primary-text-light-hover">강</span>
                        </div>

                    </div>
                    {{-- 56 --}}
                    <div class="pt-3 pt-xxl-5 mt-2 d-none">
                        <span class="text-sb-18px scale-text-gray_05 primary-text-light-hover">수강중인 강의 중 기간안에 만료 된 강의
                            갯수</span>
                    </div>
                </div>
            </div>
            <div class="">
                <div data-div-my-study-course-status-main-tab="5" onclick="myStudyCourseStatusMainTab(this);"
                    class="rounded-3 scale-bg-gray_01 primary-bg-mian-hover cursor-pointer h-100 p-3 p-xxl-4 ">
                    <div class="row mx-0">
                        <div class="col px-0">
                            <div class="text-sb-24px scale-text-gray_05 primary-text-light-hover">{{$student->student_name??''}} 학생의</div>
                            <div class="text-r-24px scale-text-gray_05 primary-text-light-hover pt-2">
                                <span class="text-b-24px scale-text-white-hover">재수강</span> 입니다.
                            </div>
                        </div>
                        <div class="col-auto">
                            <span class="text-b-42px scale-text-white-hover">{{$re_do_cnt}}</span>
                            <span class="text-b-20px scale-text-gray_05 primary-text-light-hover">강</span>
                        </div>
                    </div>
                    {{-- 56 --}}
                    <div class="pt-3 pt-xxl-5 mt-2 d-none">
                        <span class="text-sb-18px scale-text-gray_05 primary-text-light-hover">완료 된 강의 중 다시 들으려고 담아놓은 강의 갯수</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- padding 80px --}}
        <div>
            <div class="py-xxl-4 py-1"></div>
        </div>


        {{--  수강중인 강좌 --}}
        <section data-section-my-study-course-status="1">
            <div class="row mx-0">
                <div class="col row align-items-center">
                    <span class="col-auto  text-sb-20px bg-danger rounded-pill text-white py-2 px-3">
                        <span data-all-lectures-cnt>{{$student_lectures->where('start_date', '<=', date('Y-m-d 23:59:59'))->where('end_date', '>=', date('Y-m-d'))->count()}}</span>강좌
                        -
                        <span data-all-lecture-details-cnt>0</span>강의
                    </span>
                    <span class="col-auto text-sb-20px scale-text-gray_05 px-1"> 가 있습니다.</span>
                </div>
                <div class="col-auto row mx-0 p-0 gap-2">
                    <div class="col-auto d-inline-block select-wrap me-1 select-icon px-0" style="min-width:200px">
                        <select class="rounded-pill border-gray lg-select text-m-20px ps-4"
                            style="min-width:200px;padding-top:12px;padding-bottom:12px;">
                            <option value="">최근 학습 순</option>
                        </select>
                    </div>

                    <div class="col-auto px-0">
                        <label class="label-search-wrap w-100">
                            <input id="event_inp_search" type="text" onkeyup="if(event.keyCode == 13){myStudyTypeLectureSelect()}"
                                class="smart-ht-search border-gray rounded-pill text-m-20px h-42"
                                placeholder="강의명, 과목, 선생님 이름을 검색해보세요." style="width: 410px;">
                        </label>
                    </div>
                </div>
            </div>
            {{-- 52 --}}
            <div class="pt-2 pt-xxl-5 mt-1">
                <div>
                    <table class="w-100 table-style table-h-96 ">
                        <colgroup>
                            <col style="width: 80px;">
                        </colgroup>
                        <thead class="modal-shadow-style rounded">
                            <tr class="text-sb-20px ">
                                <th colspan="2" class="">강좌정보</th>
                                <th class="" style="min-width:60px">수강현황</th>
                                <th>수강요일</th>
                                <th>학습 예정일</th>
                                <th>최종 학습일</th>
                                <th>강좌 상세보기</th>
                                <!-- <th>남은 수강일</th>
                                <th>수강기간</th>
                                <th>강좌 수강기간(일수)</th>
                                <th>최종 학습일</th> -->
                            </tr>
                        </thead>
                        <tbody data-bundle="lectures">
                            <tr class="text-m-20px cursor-pointer" data-row="copy1" hidden>
                                <input type="hidden" data-student-lecture-seq>
                                <td class="">
                                    <div class="text-start h-center h-100 ps-0 ps-xxl-5 position-relative">
                                        <img src="{{ asset('images/subject_kor_icon.svg') }}" width="72" data-lecture-img>
                                        <div
                                            class=" position-absolute top-0 start-0 bottom-0 primary-bg-text-hover rounded-4"
                                            style="width:6px"></divc>
                                        </div>
                                </td>
                                <td class="text-start">
                                    <b class="black-color text-sb-20px" data-lecture-name data-explain="만점왕 국어3-2 (2023)"></b>
                                    <br><span data-teach-name> </span> 선생님
                                </td>
                                <td>
                                    <span class="text-sb-20px" data-complete-cnt data-explain="1"></span>
                                    <span class="text-sb-20px scale-text-gray_05" data-lecture-details-cnt data-explain="/ 13"></span>
                                </td>
                                <td>
                                    <!-- <span class="fw-medium" data-remain-cnt-day>26일</span> -->
                                    <div class="d-flex gap-1 pt-2 px-2 mx-1 justify-content-center">
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill" data-day="mon" style="height:28px;width:36px;line-height:1">월</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill" data-day="tue" style="height:28px;width:36px;line-height:1">화</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill" data-day="wed" style="height:28px;width:36px;line-height:1">수</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill" data-day="thu" style="height:28px;width:36px;line-height:1">목</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill" data-day="fri" style="height:28px;width:36px;line-height:1">금</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill" data-day="sat" style="height:28px;width:36px;line-height:1">토</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill" data-day="sun" style="height:28px;width:36px;line-height:1">일</span>
                                    </div>
                                </td>
                                <!-- <td>
                                    <span class="fw-medium scale-text-gray_05" data-start-end-date data-explain="23.07.30-23.11.24"></span>
                                </td> -->
                                <td>
                                    <span class="fw-medium scale-text-gray_05" data-details-start-end-date data-explain="23.07.26-23.10.24"></span>
                                </td>
                                <td>
                                    <span class="fw-medium scale-text-gray_05" data-last-do-datetime data-explain="23.08.01 12:30"></span>
                                </td>
                                <td>
                                    <button type="button" class="btn-xss-primary text-sb-20px rounded-3 scale-text-white" style="height: 36px;" onclick="myStudyShowVidoDetail(this);">
                                        상세보기
                                    </button>
                                </td>
                            </tr>
                            {{-- 만료예정 --}}
                            <!-- <tr class="text-m-20px primary-bg-bg" data-row="copy2" hidden>
                                <input type="hidden" data-student-lecture-seq>
                                <td class="text-start ps-lg-5 text-black" colspan="2">
                                    <b>
                                        현재 진도율로 <span data-expected-completion-date data-explain="23.11.24"> </span> 일 수강 완료 예정입니다.<br>
                                        <span class="text-danger"> 매주 3강</span> 씩 학습을 추천합니다.(<span data-expected-completion-date data-explain="23.11.24"> </span> 완료)
                                    </b>
                                </td>
                                <td>
                                    <span class="text-sb-20px" data-complete-cnt data-explain="1"></span>
                                    <span class="text-sb-20px scale-text-gray_05" data-lecture-details-cnt data-explain="/ 13"></span>
                                </td>
                                <td>
                                    <div>
                                        <span class="fw-medium">현재 <span data-now-per>0</span>% 완료</span>
                                    </div>
                                    <div>
                                        <span class="fw-medium text-danger">권장 <span data-recommend-per> </span> % 완료</span>
                                    </div>
                                </td>
                                <td colspan="2" class="py-4 px-4">
                                    <div class="d-flex gap-1 pt-2 px-2 mx-1">
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill" data-day="mon"
                                            style="height:28px;width:36px;line-height:1">월</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill" data-day="tue"
                                            style="height:28px;width:36px;line-height:1">화</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill" data-day="wed"
                                            style="height:28px;width:36px;line-height:1">수</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill" data-day="thu"
                                            style="height:28px;width:36px;line-height:1">목</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill" data-day="fri"
                                            style="height:28px;width:36px;line-height:1">금</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill" data-day="sat"
                                            style="height:28px;width:36px;line-height:1">토</span>
                                        <span class="all-center text-white bg-primary-y text-sb-16px rounded-pill" data-day="sun"
                                            style="height:28px;width:36px;line-height:1">일</span>
                                    </div>
                                    <div class="h-center pt-3 pb-2 px-2 mx-1">
                                        <svg width="100%" height="12" viewBox="0 0 100% 12" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect width="100%" height="12" rx="6" fill="white"  />
                                            <rect width="20%" height="12" rx="6" fill="#FFE39E" data-recommend-bar-per/>
                                            <rect width="20%" height="12" rx="6" fill="#FFC747" data-bar-per/>
                                        </svg>
                                    </div>
                                </td>
                                <td>
                                    <button type="button"
                                        class="btn-xss-primary text-sb-20px rounded-3 scale-text-white"
                                        onclick="myStudyShowVidoDetail(this);">
                                        강좌 상세보기
                                    </button>
                                </td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
                <div class="all-center mt-52">
                    <div class=""></div>
                    <div class="col-auto">
                        {{-- 페이징 --}}
                        <div class="col d-flex justify-content-center">
                            <ul class="pagination col-auto" data-page="1" hidden>
                                <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="1"
                                    onclick="myStudyPageFunc('1', 'prev')">
                                    <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                                </button>
                                <li class="page-item" hidden>
                                    <a class="page-link" onclick="">0</a>
                                </li>
                                <span class="page" data-page-first="1" hidden onclick="myStudyPageFunc('1', this.innerText);"
                                    disabled>0</span>
                                <button href="javascript:void(0)" class="btn p-0 next" data-page-next="1"
                                    onclick="myStudyPageFunc('1', 'next')" data-is-next="0">
                                    <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                                </button>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- 수강완료 강좌 --}}
        <section data-section-my-study-course-status="2" hidden></section>
        {{--  관심 강좌 --}}
        <section data-section-my-study-course-status="3" hidden>
            <div class="row mx-0">
                <aside class="col-lg-12 px-0">
                    <div class="shadow-sm-2 p-2 p-xxl-4 d-flex gap-2">

                            <button data-btn-like-aside-tab="like" onclick="userlectAsideTab(this);" class="btn btn-outline-primary-y text-sb-20px ctext-gc1 w-100 border-0 rounded-4 p-2 h-center cfs-5 active">
                                <img src="{{ asset('images/window_hart_icon.svg') }}">
                                찜한 강좌
                            </button>

                            <button data-btn-like-aside-tab="often" onclick="userlectAsideTab(this)" class="btn btn-outline-primary-y text-sb-20px ctext-gc1 w-100 border-0 rounded-4 h-center cfs-5 p-2">
                                <img src="{{ asset('images/eye_icon.svg') }}" alt="">
                                최근 많이 본 강좌
                            </button>

                    </div>
                </aside>
                <div class="col-lg-12 px-0">
                    <section class="d-flex gap-1 justify-content-end pb-2 mt-2">
                        <div class="col-auto d-inline-block select-wrap me-1 select-icon px-0" style="min-width:200px">
                            <select class="rounded-pill border-gray lg-select text-sb-20px ps-4"
                                style="min-width:200px;padding-top:16px;padding-bottom:16px;">
                                <option value="">과목순</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <label class="label-search-wrap">
                                <input id="userlect_input_search_str" onkeyup="if(event.keyCode == 13){myStudyTypeLectureSelect()}"
                                    type="text" class="lg-search border-gray rounded-pill text-m-20px h-52" placeholder="강의명, 과목, 선생님 이름을 검색해보세요."
                                    style="width: 400px;"
                                    >
                            </label>
                        </div>
                    </section>

                    <section class="" data-section-like-aside-sub="like">
                        <div class="row row-cols-lg-3 mx-0 mt-2 gx-4" data-bundle="like_lectures">
                            <div class="col ps-0" data-row="copy" hidden>
                                <input type="hidden" data-student-lecture-detail-seq>
                                <div>
                                    <div style="width:100%;height:240px;height: fit-content;" class="bg-gc5 rounded-3 overflow-hidden p-3" onclick="myStudyVideoPlay(this);">
                                        <img src="" width="100%" data-file-path>
                                    </div>
                                </div>
                                <div class="mt-3 pt-1">
                                    <div class="pb-2 d-flex justify-content-between">
                                        <span class="text-b-20px scale-text-black" data-lecture-name data-explain="만점왕 국어 3-1"></span>
                                        <button class="btn p-0 hart-btn" onclick="myStudyVideoClickLike(this);" data-btn-is-like>
                                            <div class="hart-btn-img">
                                                <img src="{{ asset('images/hart_icon.svg') }}" width="28" data-is-like="red" >
                                                <img src="{{ asset('images/gray_hart_icon.svg') }}" width="28" data-is-like="gray" hidden>
                                                <input type="hidden" data-inp-is-like value="Y">
                                            </div>
                                        </button>
                                    </div>
                                        <div class="text-b-20px scale-text-black mb-2" data-lecture-detail-name data-explain="만점왕 국어 3-1"></div>
                                        <div class="text-b-20px scale-text-black mb-2" data-lecture-description data-explain="만점왕 국어 3-1"></div>
                                    <div>
                                        <span class="text-b-20px scale-text-gray_05" data-teacher-name data-explain="이선희 선생님"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="all-center mt-52">
                            <div class="col-auto">
                                {{-- 페이징 --}}
                                <div class="col d-flex justify-content-center">
                                    <ul class="pagination col-auto" data-page="2" hidden>
                                        <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="2"
                                            onclick="myStudyPageFunc('2', 'prev')">
                                            <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                                        </button>
                                        <li class="page-item" hidden>
                                            <a class="page-link" onclick="">0</a>
                                        </li>
                                        <span class="page" data-page-first="2" hidden onclick="myStudyPageFunc('2', this.innerText);"
                                            disabled>0</span>
                                        <button href="javascript:void(0)" class="btn p-0 next" data-page-next="2"
                                            onclick="myStudyPageFunc('2', 'next')" data-is-next="0">
                                            <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                                        </button>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="pt-2 pt-xxl-4" data-section-like-aside-sub="often" hidden>
                        <div>
                            <table class="w-100 table-style table-h">
                                <thead class="modal-shadow-style rounded">
                                    <tr class="text-sb-20px ">
                                        <th>강의정보</th>
                                        <th>수강현황</th>
                                        <th>최종학습일</th>
                                    </tr>
                                </thead>
                                <tbody data-bundle="often_lectures">
                                    <tr class="text-m-20px" data-row="copy" hidden>
                                        <td class="px-4">
                                            <div class="row mx-0 ps-2 py-2">
                                                <div class="col-auto  px-0 rounded-4 position-relative overflow-hidden" style="max-height: 70px;">
                                                    <img src="" width="120px" class="rounded-4" data-file-path="" style="height: 100%;">
                                                </div>
                                                <div class="col px-0 ps-3 w-center flex-column">
                                                    <div class="text-start">
                                                        <span class="test-b-20px" data-lecture-name data-explain="만점왕 국어 3-2(2023)"></span>
                                                    </div>
                                                    <div class="text-start">
                                                        <span class="text-m-18px scale-text-gray_05" data-teacher-name data-explain="김팝콘 선생님"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="fw-medium" data-complete-cnt data-explain="1"></span>
                                            <span class="fw-medium scale-text-gray_05">/</span>
                                            <span class="fw-medium scale-text-gray_05" data-lecture-details-cnt data-explain="13"></span>
                                        </td>
                                        <td>
                                            <span class="text-b-20px scale-text-gray_05" data-updated-at></span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- 페이징 --}}
                        <div class="all-center mt-52">
                            <div class=""></div>
                            <div class="col-auto">
                                {{-- 페이징 --}}
                                <div class="col d-flex justify-content-center">
                                    <ul class="pagination col-auto" data-page="3" hidden>
                                        <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="3"
                                            onclick="myStudyPageFunc('3', 'prev')">
                                            <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                                        </button>
                                        <li class="page-item" hidden>
                                            <a class="page-link" onclick="">0</a>
                                        </li>
                                        <span class="page" data-page-first="3" hidden onclick="myStudyPageFunc('3', this.innerText);"
                                            disabled>0</span>
                                        <button href="javascript:void(0)" class="btn p-0 next" data-page-next="3"
                                            onclick="myStudyPageFunc('3', 'next')" data-is-next="0">
                                            <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                                        </button>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </section>
                </div>
            </div>
        </section>
        {{-- 미수강 / 재수강 같은 화면 --}}
        <section data-section-my-study-course-status="4" hidden>
            <div class="row mx-0">
                <div class="col row align-items-center ">
                    <span data-all-lectures-cnt
                        class="col-auto  text-sb-20px bg-danger rounded-pill text-white py-2 px-3" data-explain="총 0개의 강좌"> </span>
                    <span class="col-auto  text-sb-24px scale-text-gray_05 px-1"> 가 있습니다.</span>
                </div>
                <div class="col-auto row mx-0 p-0 gap-2">
                    <div class="col-auto d-inline-block select-wrap me-1 select-icon px-0" style="min-width:200px">
                        <select data-select-now-week="not_complete" onchange="myStudyTypeLectureSelect()"
                            class="rounded-pill border-gray lg-select text-sb-24px ps-4"
                            style="min-width:200px;padding-top:18px;padding-bottom:18px;">
                        </select>
                    </div>
                </div>
            </div>
            {{-- 52 --}}
            <div class="pt-3 pt-xxl-5 mt-1">
                <div>
                    <table class="w-100 table-style table-h-96 ">
                        <thead class="modal-shadow-style rounded">
                            <tr class="text-sb-20px ">
                                <th style="min-width: 40px;" {{$login_type == 'parent' ? '':'hidden'}}>
                                    <label class="checkbox mt-1">
                                        <input type="checkbox" class="" onchange="">
                                        <span class="">
                                        </span>
                                    </label>
                                </th>
                                <th class="" style="min-width: 60px;">
                                    <span class="primary-text-text-hover active">과목</span>
                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="d-none"
                                        >
                                        <path
                                            d="M7.95117 9.74756L11.7181 13.5431C12.1056 13.9335 12.7363 13.9359 13.1267 13.5484L16.9561 9.74756"
                                            stroke="#FFC747" stroke-width="2.5" stroke-miterlimit="10"
                                            stroke-linecap="round"></path>
                                    </svg>
                                </th>
                                <th class="text-end pe-4">강좌정보</th>
                                <th></th>
                                <th>목표 학습일</th>
                                <th>최근 학습일</th>
                                <th data-in="not_complete" hidden>학습 시간</th>
                                <th data-in="re_do" hidden>학습 결과</th>
                                <th>학습 상태</th>
                            </tr>
                        </thead>
                        <tbody data-bundle="not_complete_lectures">
                            <tr class="text-m-20px" data-row="copy" hidden>
                                <input type="hidden" data-student-lecture-detail-seq>
                                <td style="width:40px" {{$login_type == 'parent' ? '':'hidden'}}>
                                    <label class="checkbox mt-1">
                                        <input type="checkbox" class="" onchange="myStudyCheckLecture(this)">
                                        <span class="">
                                        </span>
                                    </label>
                                </td>
                                <td>
                                    <span data-subject-name
                                    class="primary-bg-text text-m-20px scale-text-white rounded-pill px-2 py-1">국어</span>
                                </td>
                                <td class="text-end pe-4">
                                    <img src="" width="72" data-subject-img>
                                </td>
                                <td class="text-start">
                                    <b class="black-color text-sb-20px" data-lecture-name data-explain="[8단원] 의견이 있어요."></b>
                                    <br>
                                    <span data-lecture-description data-explain="글을 읽고 인물의 의견과 그 까닭 알기"> </span>
                                     </td>
                                <td data-sel-date></td>
                                <td class="text-center" data-last-do-datetime>
                                </td>
                                <td data-in="not_complete" hidden>
                                    <div>
                                        <svg width="100%" height="12px" viewBox="0 0 100% 12" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" style="max-width:300px;">
                                            <rect width="100%" height="12" rx="6" fill="#F1F1F1">
                                            </rect>
                                            <rect width="0%" height="12" rx="6" fill="#FFC747" data-bar-per>
                                            </rect>
                                        </svg>
                                        <span data-time-complete>00:00 / 00:00</span>
                                    </div>
                                </td>
                                <td data-in="re_do" hidden>
                                    <div class="row">
                                        <div class="px-0 col-auto"> </div>
                                        <div class="px-0 col text-end">
                                            <span class="text-primary" data-complete-question-cnt> </span>
                                            /
                                            <span data-all-question-cnt> </span> 문제
                                        </div>
                                    </div>
                                </td>
                                <td><span class="" data-status></span></td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- padding 80px 48-32 --}}
                    <div>
                        <div class="py-4"></div>
                        <div class="pt-3"></div>
                    </div>

                    <div class="all-center mt-52">
                        <div class="col-auto"></div>
                        <div class="col">
                            {{-- 페이징 --}}
                            <div class="col d-flex justify-content-center">
                                <ul class="pagination col-auto" data-page="4" hidden>
                                    <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="4"
                                        onclick="myStudyPageFunc('4', 'prev')">
                                        <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                                    </button>
                                    <li class="page-item" hidden>
                                        <a class="page-link" onclick="">0</a>
                                    </li>
                                    <span class="page" data-page-first="4" hidden onclick="myStudyPageFunc('4', this.innerText);"
                                        disabled>0</span>
                                    <button href="javascript:void(0)" class="btn p-0 next" data-page-next="4"
                                        onclick="myStudyPageFunc('4', 'next')" data-is-next="0">
                                        <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                                    </button>
                                </ul>
                            </div>
                        </div>
                        <div class="col-auto">
                            {{--  학부모만 보이게 --}}
                            @if($login_type == 'parent')
                            <button type="button" onclick="myStudyLearningPlainAdd()"
                                class="btn-line-xss-secondary text-sb-20px border-dark rounded scale-bg-white text-black p-4 me-2 align-bottom">학습플래너에 추가</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade " id="modal_learning_plan_add" tabindex="-1" style="display: none;" aria-modal="true" role="dialog">
                <div class="modal-dialog  modal-dialog-centered" style="max-width:592px">
                    <div class="modal-content">
                        <div class="modal-header border-0 p-4">
                            <h1 class="modal-title text-b-24px h-center">
                                <img src="{{asset('images/yellow_calendar_icon.svg')}}" width="32">
                                학습플래너에 다시 추가
                            </h1>
                            <button type="button" class="btn p-0 d-inline-flex" data-bs-dismiss="modal" aria-label="Close" onclick="">
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M22 10L10.0003 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round"></path>
                                    <path d="M10 10L21.9997 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="modal-body border-0 px-4">
                            <div class="px-2">
                            <div class="text-sb-20px mb-2 pb-1">학습일을 선택해주세요.</div>
                            <input type="date" class="col form-control px-2 text-sb-20px p-4 px-4 scale-text-gray_05" data-sel-date="" value="{{date('Y-m-d')}}">

                            <div class="text-sb-20px text-sb-20px mt-5 pt-1">선택한 강의목록.</div>
                            </div>
                            <article>
                                <section class="py-3 div_lectures" data-bundle="learning_plan_add">
                                    <div class="row gap-2 p-4" data-row="copy" hidden>
                                        <input type="hidden" data-student-lecture-detail-seq>
                                        <div class="col-auto h-center">
                                            <label class="checkbox">
                                                <input type="checkbox" class="" name="lecture-step2-modal-radio" onchange="" checked>
                                                <span class=""></span>
                                            </label>
                                        </div>
                                        <div class="col-auto">
                                            <img class="lecture_icon" src="" width="72" data-subject-img>
                                        </div>
                                        <div class="cfs-6 col-auto">
                                            <div class="ctext-gc1">
                                                <span class="teacher_name text-sb-20px scale-text-gray_05" data-course-name data-explain="학교공부예복습"></span>
                                            </div>
                                            <div class="ctext-bc0">
                                                <span class="lecture_name text-sb-20px" data-lecture-name data-explain="7단원 국어 공부"></span>
                                            </div>
                                        </div>
                                        <div class="col text-end">

                                        </div>

                                        <input type="hidden" class="lecture_seq" value="21">
                                        <input type="hidden" class="start_lecture_detail_seq" value="">
                                    </div>
                                </section>
                            </article>
                        </div>
                        <div class="modal-footer border-0 row">
                            <button class="btn btn-lg btn-light mt-2 py-3 text-sb-24px scale-text-gray_05 w-100 col" onclick="myStudyPlanAddClear();">초기화하기</button>
                            <button class="btn btn-primary-y btn-lg col py-3 col" onclick="myStudyPlanAdd();">
                                <span class="ps-2 text-b-24px text-white">플래너에 추가</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        {{-- 재수강 / 미수강과 같은 구성이다. --}}
        <section data-section-my-study-course-status="" hidden>
            <div class="row mx-0">
                <div class="col row align-items-center ">
                    <button type="button"
                        class="col-auto btn-line-ss-primary text-sb-20px rounded-3 scale-bg-white btn btn-outline-light scale-text-gray_05 border">
                        선택 삭제하기
                    </button>

                    <span class="col-auto  text-sb-24px text-danger px-0 ps-2 ms-1">총 6개</span>
                    <span class="col-auto  text-sb-24px scale-text-gray_05 px-1">의 강좌가 있습니다.</span>
                </div>
                <div class="col-auto row mx-0 p-0 gap-2">
                    <div class="col-auto d-inline-block select-wrap me-1 select-icon px-0" style="min-width:200px">
                        <select
                            class="rounded-pill border-gray lg-select text-sb-24px ps-4"
                            style="min-width:200px;padding-top:18px;padding-bottom:18px;">
                        </select>
                    </div>
                </div>
            </div>
            {{-- 52 --}}
            <div class="pt-3 pt-xxl-5 mt-1">
                <div>
                    <table class="w-100 table-style table-h-96 ">
                        <colgroup>
                            <col style="width: 80px;">
                        </colgroup>
                        <thead class="modal-shadow-style rounded">
                            <tr class="text-sb-20px ">
                                <th class="text-end pe-4">강좌정보</th>
                                <th></th>
                                <th class="">
                                    <span class="primary-text-text-hover active">과목</span>
                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.95117 9.74756L11.7181 13.5431C12.1056 13.9335 12.7363 13.9359 13.1267 13.5484L16.9561 9.74756"
                                            stroke="#FFC747" stroke-width="2.5" stroke-miterlimit="10"
                                            stroke-linecap="round"></path>
                                    </svg>
                                </th>
                                <th>남은 수강일</th>
                                <th>수강기간</th>
                                <th>최종 학습일</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="text-m-20px">
                                <td class="text-end pe-4">
                                    <img src="{{ asset('images/subject_kor_icon.svg') }}" width="72">
                                </td>
                                <td class="text-start"><b class="black-color text-sb-20px">[8단원] 의견이 있어요.</b><br>글을 읽고 인물의 의견과 그 까닭 알기 </td>
                                <td><span
                                    class="primary-bg-text text-m-20px scale-text-white rounded-pill px-2 py-1">국어</span>
                                </td>
                                <td>학교공부예복습</td>
                                <td>
                                    <div>
                                        <svg width="100%" height="12px" viewBox="0 0 100% 12" fill="none"
                                            xmlns="http://www.w3.org/2000/svg" style="max-width:400px;">
                                            <rect width="100%" height="12" rx="6" fill="#F1F1F1">
                                            </rect>
                                            <rect width="14%" height="12" rx="6" fill="#FFC747">
                                            </rect>
                                        </svg>
                                    </div>
                                </td>
                                <td><span class="studey-completion">다시풀기</span></td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- padding 80px 48-32 --}}
                    <div>
                        <div class="py-4"></div>
                        <div class="pt-3"></div>
                    </div>

                    {{-- 페이징  --}}
                    <div class="d-flex justify-content-center">
                        <ul class="pagination col-auto" data-ul-my-study-page="4">
                            <button href="javascript:void(0)" class="btn p-0 prev" data-btn-my-study-page-prev="4"
                                onclick="noticeBoardSelect()">
                                <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                            </button>
                            <li class="page-item" hidden>
                                <a class="page-link" onclick="">0</a>
                            </li>
                            <span class="page" data-span-my-study-page-first="4" hidden
                                onclick="noticeBoardSelect(this.innerText)">0</span>
                            <span class="page_num page active">1</span>
                            <button href="javascript:void(0)" class="btn p-0 next" data-btn-my-study-page-next="4">
                                <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                            </button>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

{{-- 강좌 상세 보기//----------------------------------------------//----------------------------------------------//---------------------------------------------- --}}
{{-- 강좌상세들어올때 로딩(프로세스바) 같은 기능 필요해보임. --}}
<div data-div-my-study-main="3" class="row px-0" hidden data-section-lectrure-details>
    <aside class="col-lg-3 ps-0">
        <div class="modal-shadow-style rounded-4">
            <div style="height:240px" class="overflow-hidden rounded-3">
                <img src="" width="100%" data-img-lecture-file_path>
            </div>
            <div class="p-4">
                <div class="row mx-0">
                    <div class="col px-0">
                        <span class="text-b-24px" data-lecture-name data-explain="만점왕 수학 3-2 (2023)"></span>
                    </div>
                    <div class="col-auto px-0">
                        <button class="btn p-0" onclick="myStudyShowVideoTeachInfoToggle(this);">
                            <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32">
                        </button>
                    </div>
                </div>
                <div class="px-0">
                    <span class="text-m-20px scale-text-gray_05" data-teacher-name data-explain=" 나소은"></span>
                    <span class="text-m-20px scale-text-gray_05">선생님</span>
                </div>
                <div class="pt-4 mt-2" data-div-my-study-video-teach-info hidden>
                    <div class="row mx-0 rounded-3 scale-bg-gray_01 px-4 py-3">
                        <div class="col-auto text-sb-20px scale-text-gray_05 p-0 my-2 me-2" style="width:80px;">학습자 수</div>
                        <div class="col text-sb-20px p-0 my-2 ps-1" data-number-learners data-explain="26,900명"></div>
                    </div>
                    <div class="row mx-0 rounded-3 scale-bg-gray_01 px-4 py-3 mt-2">
                        <div class="col-auto text-sb-20px scale-text-gray_05 p-0 my-2 me-2" style="width:80px;">수강대상</div>
                        <div class="col text-sb-20px p-0 my-2 ps-1" data-lecture-grade data-explain="초등학교 3학년"></div>
                    </div>
                    <div class="row mx-0 rounded-3 scale-bg-gray_01 px-4 py-3 mt-2">
                        <div class="col-auto text-sb-20px scale-text-gray_05 p-0 my-2 me-2" style="width:80px;">강좌수준</div>
                        <div class="col text-sb-20px p-0 my-2 ps-1" data-lecture-level >중급</div>
                    </div>
                    <div class="row mx-0 rounded-3 scale-bg-gray_01 px-4 py-3 mt-2">
                        <div class="col-auto text-sb-20px scale-text-gray_05 p-0 my-2 me-2" style="width:80px;">시리즈</div>
                        <div class="col text-sb-20px p-0 my-2 ps-1" data-lecture-series >만점왕</div>
                    </div>
                    <div class="row mx-0 rounded-3 scale-bg-gray_01 px-4 py-3 mt-2">
                        <div class="col-auto text-sb-20px scale-text-gray_05 p-0 my-2 me-2" style="width:80px;">수강기간</div>
                        <div class="col text-sb-20px p-0 my-2 ps-1" data-course-date-count>105일</div>
                    </div>
                </div>
            </div>
        </div>
    </aside>
    <article class="col-lg pe-0">
        <div class="pb-4 text-end">
            <span class="text-sb-20px text-danger" data-all-lecdture-complete-cnt></span>
            <span class="text-sb-20px ">의 강의가 완강되었습니다.</span>
        </div>
        <div>
            <table class="w-100 table-style table-h-82">
                <thead class="modal-shadow-style rounded">
                    <tr class="text-sb-20px ">
                        <th>강의정보</th>
                        <th>키워드</th>
                        <th>교제 페이지</th>
                        <th>강의 시간</th>
                        <th>학습 결과</th>
                        <th>학습 상태</th>
                    </tr>
                </thead>
                <tbody data-bundle="lecture_detail_list">
                    <tr class="text-m-20px cursor-pointer" data-row="copy" hidden>
                        <td class="px-4">
                            {{-- 썸네일이 들어갈지? 확인필요. --}}
                            <div class="row mx-0 ps-2 py-2 my-1" >
                                <div class="col-auto h-center px-0 rounded-4 position-relative">
                                    <img src="" width="120px" height="71" class="rounded-4 overflow-hidden" data-img-lecture-file_path>
                                    <div class="position-absolute top-0 bottom-0 start-0 end-0 z-1 bg-dark opacity-50 rounded-4 all-center">
                                    </div>
                                    <button style="width:32px;height:32px;" onclick="" data-btn-play
                                        class="btn p-0 bg-white rounded-circle opacity-100 all-center position-absolute m-auto z-2 start-0 end-0">
                                        <img src="{{ asset('images/black_arrow_right_notail.svg') }}" width="32">
                                    </button>
                                </div>
                                <div class="col px-0 ps-3 w-center flex-column">
                                    <div class="text-start">
                                        <span class="test-b-20px text-black" data-lecture-detail-name data-ex="오리엔테이션"></span>
                                    </div>
                                    <div class="text-start">
                                        <span class="text-m-18px scale-text-gray_05" data-lecture-detail-description data-explain="세자리수 x 한 자리수"></span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <!-- TODO: 키워드?  -->
                            <span class="fw-medium scale-text-gray_05"></span>
                        </td>
                        <td class="text-black">
                            <span class="text-b-20px">0</span>
                            <span class="text-b-20px">p</span>
                        </td>
                        <td class="text-black">
                            <span class="text-b-20px" data-lecture-detail-time >00:00</span>
                        </td>
                        <td onclick="myStudyViewLearningResults(this);">
                            <div class="row" hidden>
                                <div class="px-0 col-auto">기본</div>
                                <div class="px-0 col text-end">
                                    <span class="text-primary" data-complete-question-cnt="normal">0</span>
                                    <span data-all-question-cnt="normal"></span> 문제
                                </div>
                            </div>
                            <div class="row" hidden>
                                <div class="px-0 col-auto">유사</div>
                                <div class="px-0 col text-end">
                                    <span class="text-primary" data-complete-question-cnt="similar">0</span>
                                    <span data-all-question-cnt="similar"></span> 문제
                                </div>
                            </div>
                            <div class="row" hidden>
                                <div class="px-0 col-auto">도전</div>
                                <div class="px-0 col text-end">
                                    <span class="text-primary" data-complete-question-cnt="challenge">0</span>
                                    <span data-all-question-cnt="challenge"></span> 문제
                                </div>
                            </div>
                            <div class="row" hidden>
                                <div class="px-0 col-auto">도전유사</div>
                                <div class="px-0 col text-end">
                                    <span class="text-primary" data-complete-question-cnt="challenge_similar">0</span>
                                    <span data-all-question-cnt="challenge_similar"></span> 문제
                                </div>
                            </div>
                        </td>
                        <td><span class="" data-status onclick="">다시풀기</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </article>

    {{--  모달 / 학습 결과 --}}
    <div class="modal fade " id="modal_lecture_result" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-modal="true" role="dialog">
        <img src="{{asset('images/character_lecture_result.svg')}}" width="380" style="margin-bottom: -14.7%;margin-left: 40%;">
        <div class="modal-dialog rounded   modal-dialog-centered" style="max-width: 544px;">
            <div class="modal-content border-none rounded modal-shadow-style rounded-4">
                <div class="modal-header border-bottom-0 p-4 primary-bg-light rounded-top-4">
                    <h1 class="modal-title text-b-28px" id=""> 학습 결과 </h1>
                    <button type="button" style="width:52px;height:52px"
                        class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 my-2">
                    <div class="text-sb-20px rounded-4">
                        <div class="bg-danger all-center p-3 rounded-top-4">
                            <span class="text-white"> 기본 문제</span>
                        </div>
                        <div class="row scale-bg-gray_01 p-4 rounded-bottom-4">
                            <div class="px-0 scale-text-gray_05 h-center justify-content-end col">
                                <span>맞힌 문제</span>
                                <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                                <span class="text-black" data-suc="normal">0</span>
                                <span class="text-black ms-2">문제</span>
                            </div>
                            <div class="px-0 scale-text-gray_05 h-center justify-content-start col ps-3">
                                <span>틀린문제</span>
                                <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                                <span class="text-black" data-all="normal">0</span>
                                <span class="text-black ms-2">문제</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-sb-20px rounded-4 mt-4">
                        <div class="bg-danger all-center p-3 rounded-top-4">
                            <span class="text-white">유사 문제</span>
                        </div>
                        <div class="row scale-bg-gray_01 p-4 rounded-bottom-4">
                            <div class="px-0 scale-text-gray_05 h-center justify-content-end col">
                                <span>맞힌 문제</span>
                                <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                                <span class="text-black" data-suc="similar">0</span>
                                <span class="text-black ms-2">문제</span>

                            </div>
                            <div class="px-0 scale-text-gray_05 h-center justify-content-start col ps-3">
                                <span>틀린문제</span>
                                <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                                <span class="text-black" data-all="similar">0</span>
                                <span class="text-black ms-2">문제</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-sb-20px rounded-4 mt-4">
                        <div class="bg-danger all-center p-3 rounded-top-4">
                            <span class="text-white">도전 문제</span>
                        </div>
                        <div class="row scale-bg-gray_01 p-4 rounded-bottom-4">
                            <div class="px-0 scale-text-gray_05 h-center justify-content-end col">
                                <span>맞힌 문제</span>
                                <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                                <span class="text-black" data-suc="challenge">0</span>
                                <span class="text-black ms-2">문제</span>
                            </div>
                            <div class="px-0 scale-text-gray_05 h-center justify-content-start col ps-3">
                                <span>틀린문제</span>
                                <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                                <span class="text-black" data-all="challenge">0</span>
                                <span class="text-black ms-2">문제</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-sb-20px rounded-4 mt-4">
                        <div class="bg-danger all-center p-3 rounded-top-4">
                            <span class="text-white">도전 유사 문제</span>
                        </div>
                        <div class="row scale-bg-gray_01 p-4 rounded-bottom-4">
                            <div class="px-0 scale-text-gray_05 h-center justify-content-end col">
                                <span>맞힌 문제</span>
                                <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                                <span class="text-black" data-suc="challenge_similar">0</span>
                                <span class="text-black ms-2">문제</span>
                            </div>
                            <div class="px-0 scale-text-gray_05 h-center justify-content-start col ps-3">
                                <span>틀린문제</span>
                                <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                                <span class="text-black" data-all="challenge_similar">0</span>
                                <span class="text-black ms-2">문제</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form method="POST" action="/student/study/video" data-form="study_video" hidden>
        @csrf
        <input name="st_lecture_detail_seq" />
        <input name="prev_page" value="my_study_like_lecture"/>
    </form>
</div>
@if($login_type == 'parent')
{{-- 학부모 학습관리 메인과, 학습자료 --}}
@include('parent.parent_learning_management')
@endif

{{-- padding 160px --}}
<div>
    <div class="py-lg-5"></div>
    <div class="py-lg-4"></div>
    <div class="pt-lg-3"></div>
</div>
</div>


<script>
// TEST:
// myStudyMainDivChagne('3')
// const myModal = new bootstrap.Modal(document.getElementById('modal_lecture_result'), {
//     keyboard: false
// });
// myModal.show();
// document.querySelector('[data-btn-my-study-top-tab="2"]').click();
// document.querySelector('[data-div-my-study-course-status-main-tab="4"]').click();

<?php if(($student->student_name??'') == '') { ?>
alert('학생이 선택되지 않았습니다. 확인해주시기 바랍니다.');
<?php } ?>
// 주차 만들기.
myStudyMakeWeekList();
// 월간 만들기
myStudyMakeMonthList();
// 수강현황 > 수강완료 강좌.
myStudyTypeLectureSelect();

document.addEventListener('DOMContentLoaded', function(){
    let browserWidth = function() {
        return window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    }
    let initializeOwlCarousel = function() {
        let item = 3;
        if (browserWidth() < 768) {
            item = 2;
        } else if (browserWidth() < 1024) {
            item = 3;
        }
        const owl_goal = $('[data-main-section-my-study-course-status]');
        let startPosition = 0;
        if(document.querySelector('#inpage_type').value == 'misu'){
            // startPosition 2 가되게.
            startPosition = 2;
            const click_el = document.querySelector('[data-div-my-study-course-status-main-tab="4"]');
            click_el.click();
        }
        owl_goal.owlCarousel('destroy');
        owl_goal.owlCarousel({
            items: item,
            loop:false,
            margin:12,
            nav:false,
            dots:false,
            startPosition:startPosition
        });
    }
    initializeOwlCarousel();
    window.addEventListener('resize', initializeOwlCarousel);
    const owl_goal = $('[data-main-section-my-study-course-status]');
    $('[data-btn-course-prev]').click(function(){
        owl_goal.trigger('prev.owl.carousel');
    });
    $('[data-btn-course-next]').click(function(){
        owl_goal.trigger('next.owl.carousel');
    });
    // 현재 년월의 주차를 select
    myStudySelectTagMakeNowWeek();
});

// 주간현황 / 월간현황 TAB CLICK
function myStudyAsideTab(vthis) {
    //data-btn-my-study-aside-tab 모두 비활성화
    document.querySelectorAll('[data-btn-my-study-aside-tab]').forEach(function(item) {
        item.classList.remove('active');
    });
    // 활성화
    vthis.classList.add('active');
    const type = vthis.getAttribute('data-btn-my-study-aside-tab');
    // 주/월  ASIDE
    myStudyAsideSubChange(type);
    // 주/월 SECTION
    myStudySectionSubChange(type);
    // 주/월 INFO
    myStudyInfoChange(type);
}

// 학습현황, 수강현황 TAB CLICK
function myStudyTopTab(vthis) {
    const login_type = document.querySelector('[data-main-login-type]').value;
    if(login_type == 'parent' && vthis.classList.contains('active')){
        vthis.classList.remove('active');
        const type = "0";
        myStudyMainDivChagne(type);
        return;
    }
    // data-btn-my-study-top-tab 모두 비활성화
    document.querySelectorAll('[data-btn-my-study-top-tab]').forEach(function(item) {
        item.classList.remove('active');
    });
    // 활성화
    vthis.classList.add('active');
    const type = vthis.getAttribute('data-btn-my-study-top-tab');
    let text = vthis.textContent.replace(/\s+/g, '');
    document.querySelector('article .h-center span').innerText = text;
    myStudyMainDivChagne(type);
}

// 주간현황, 월간형황에 맞게 main / data-div-my-study-main 변경
function myStudyMainDivChagne(type) {
    document.querySelectorAll('[data-div-my-study-main]').forEach(function(item) {
        item.hidden = true;
    });
    document.querySelector('[data-div-my-study-main="' + type + '"]').hidden = false;

    if(type != '3'){
        document.querySelector('[data-page="lecture"]').hidden = false;
        document.querySelector('[data-page="lecture_detail"]').hidden = true;
    }
}

// 주차 만들기.
function myStudyMakeWeekList() {
    const aside = document.querySelector('[data-aside-my-study]');
    const div_week_bundle = aside.querySelector('.div_week_bundle');
    const copy_div_week_row = div_week_bundle.querySelector('.div_week_row').cloneNode(true);
    const sel_date = aside.querySelector('#my_study_span_month').getAttribute('data');
    // 초기화
    div_week_bundle.innerHTML = '';
    div_week_bundle.appendChild(copy_div_week_row);

    // sel_date의 달이 몇번째 주까지 있는지 계산
    // 단 단순히 7로 나누는게 아니라 1일이 무슨 요일인지 계산해서 그에 맞게 계산해야함.
    const date = new Date(sel_date);
    const first_day = new Date(date.getFullYear(), date.getMonth(), 1);
    const last_day = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    const first_week = first_day.getDay();
    const last_week = last_day.getDate();
    const week_cnt = Math.ceil((last_week + first_week) / 7);

    // 만약 현재달과 선택 달이 같으면
    // 현재가 몇주차인지도 계산해야함.
    const now_date = new Date();
    let now_week_cnt = -1;
    if (now_date.getFullYear() == date.getFullYear() && now_date.getMonth() == date.getMonth()) {
        const now_week = now_date.getDate();
        now_week_cnt = Math.ceil((now_week + first_week) / 7);
    }

    for (let i = 0; i < week_cnt; i++) {
        const copy_div_week_row = div_week_bundle.querySelector('.div_week_row').cloneNode(true);
        const div_week_row = copy_div_week_row.cloneNode(true);
        div_week_row.hidden = false;
        if (i > 2) {
            // 3번째부터 pt-1 add
            div_week_row.classList.add('pt-1');
        }
        div_week_row.querySelector('.week_cnt').textContent = i + 1;
        div_week_bundle.appendChild(div_week_row);
        if (i + 1 == now_week_cnt) {
            div_week_row.querySelector('button').classList.add('active');
            //
            document.querySelector('[data-main-now-week]').innerText = now_week_cnt + '주차';
        }
    }
}

// 월간 만들기
function myStudyMakeMonthList() {
    const aside = document.querySelector('[data-aside-my-study]');
    const div_month_bundle = aside.querySelector('.div_month_bundle');
    const copy_div_month_row = div_month_bundle.querySelector('.div_month_row').cloneNode(true);
    const sel_date = aside.querySelector('#my_study_span_month').getAttribute('data');
    // 초기화
    div_month_bundle.innerHTML = '';
    div_month_bundle.appendChild(copy_div_month_row);

    // 12개월 만들기 / 현재 월 선택
    const now_date = new Date();
    const now_month = now_date.getMonth() + 1;

    for (let i = 0; i < 12; i++) {
        const copy_div_month_row = div_month_bundle.querySelector('.div_month_row').cloneNode(true);
        const div_month_row = copy_div_month_row.cloneNode(true);
        div_month_row.hidden = false;
        if (i > 2) {
            div_month_row.classList.add('pt-1');
        }
        div_month_row.querySelector('.month_cnt').textContent = i + 1;
        div_month_bundle.appendChild(div_month_row);
        if (i + 1 == now_month) {
            div_month_row.querySelector('button').classList.add('active');
        }
    }
}

//  주간 현황 > 다음달, 이전달 버튼
function myStudyMonthChange(type) {
    let after_sum_num = 0;
    if (type == 'next') {
        after_sum_num = 1;
    } else if (type == 'prev') {
        after_sum_num = -1;
    }

    if (type == 'next' || type == 'prev') {
        const span_month = document.querySelector('#my_study_span_month');
        const sel_date = span_month.getAttribute('data');
        const date = new Date(sel_date);
        date.setMonth(date.getMonth() + after_sum_num);
        span_month.textContent = date.getFullYear() + '년 ' + (date.getMonth() + 1) + '월';
        span_month.setAttribute('data', date.getFullYear() + '-' + (date.getMonth() + 1) + '-1');
        myStudyMakeWeekList();
    }
}

// 주간현황, 월간형황에 맞게 aside sub /  data-div-my-study-aside-sub 변경
function myStudyAsideSubChange(type) {
    const aside = document.querySelector('[data-aside-my-study]');
    const div_my_study_aside_sub = aside.querySelector('[data-div-my-study-aside-sub]');
    if (type == 'week') {
        div_my_study_aside_sub.hidden = false;
        aside.querySelector('[data-div-my-study-aside-sub="month"]').hidden = true;
    } else if (type == 'month') {
        div_my_study_aside_sub.hidden = true;
        aside.querySelector('[data-div-my-study-aside-sub="month"]').hidden = false;
    }
}

// 주간현황, 월간형황에 맞게 SECTION 변경
function myStudySectionSubChange(type) {
    const week_section = document.querySelector('[data-div-my-study-main-bundle="week"]');
    const month_section = document.querySelector('[data-div-my-study-main-bundle="month"]');
    if (type == 'week') {
        week_section.hidden = false;
        month_section.hidden = true;
    } else if (type == 'month') {
        week_section.hidden = true;
        month_section.hidden = false;
    }
}

// 주간현황, 월간현황에 맞게 정보 변경.
function myStudyInfoChange(type) {

}

// 월간 > 월 > SECTION 상세 확인
function myStudyMonthSection(vthis) {
    if (vthis.classList.contains('active')) {
        vthis.classList.remove('active');
        vthis.classList.remove('rotate-180');
    } else {
        // 일단은 모두 가능하게.
        // document.querySelectorAll('[data-btn-my-study-week-section]').forEach(function(item){
        //     item.classList.remove('active');
        // });
        vthis.classList.add('active');
        vthis.classList.add('rotate-180');
    }
    const num = vthis.getAttribute('data-btn-my-study-month-section');
    myStudyMonthSectionSub(num);
}

// 월간 > 월 > SECTION 상세 확인
function myStudyMonthSectionSub(type) {
    const div_sub = document.querySelector('[data-div-my-study-month-section-sub="' + type + '"]');
    if (div_sub.hidden) {
        div_sub.hidden = false;
    } else {
        div_sub.hidden = true;
    }
}

// 주간 학습 상세 > 요일 안 내용 클릭시
function myStudyWeekyStudyDetailContent(vthis) {
    // vthis 만 남기고 나머지 hidden
    const sel_week_day = vthis.closest('[data-div-weeky-study-day]');


    const div_weeky_study_detail = sel_week_day.querySelectorAll('[data-div-weeky-study-detail]');
    div_weeky_study_detail.forEach(function(item) {
        // 내용을 같은 요일에 모두 숨기기
        // items 자식 요소들을 모두 숨기기
        item.childNodes.forEach(function(child) {
            child.hidden = true;
        });

        // p-2를 모두 p-1으로 변경
        item.classList.remove('p-2');
        item.classList.add('p-1');

        // head의 active 를 모두 제거, col을 col-auto를 변경
        item.closest('[data-div-weeky-study-head]').classList.remove('active');
        item.closest('[data-div-weeky-study-head]').classList.add('col-auto');
        item.closest('[data-div-weeky-study-head]').classList.remove('col');
        item.closest('[data-div-weeky-study-head]').classList.remove('w-100');
    });

    // 선택한 요일의 div_weeky_study_detail 보이기
    const sel_detail = vthis.querySelector('[data-div-weeky-study-detail]');
    sel_detail.childNodes.forEach(function(child) {
        child.hidden = false;
    });

    // 선택한 요일의 p-1을 p-2로 변경
    sel_detail.classList.remove('p-1');
    sel_detail.classList.add('p-2');

    // 선택한 요일의 head active 추가
    vthis.classList.add('active');
    vthis.classList.add('w-100');
    vthis.classList.remove('col-auto');
    vthis.classList.add('col');
}

// 수강현황 > 메인(강좌[수강,완료,관심,미수강]]별) TAB
function myStudyCourseStatusMainTab(vthis) {
    // 우선 data-div-my-study-course-status-main-tab 모두 비활성화, 후 vthis 활성화
    document.querySelectorAll('[data-div-my-study-course-status-main-tab]').forEach(function(item) {
        item.classList.remove('active');
    });
    vthis.classList.add('active');
    // 탭에따른 펑션 실행
    const type = vthis.getAttribute('data-div-my-study-course-status-main-tab');
    myStudyCourseStatusMainTabSub(type);
}

// 수강현황 > 메인(강좌[수강,완료,관심,미수강]]별) TAB SUB
function myStudyCourseStatusMainTabSub(type) {
    // data-section-my-study-course-status 모두 숨김처리.
    document.querySelectorAll('[data-section-my-study-course-status]').forEach(function(item) {
        item.hidden = true;
    });
    if(type == 2){
        document.querySelector('[data-section-my-study-course-status="1"]').hidden = false;
    }
    if(type == 5){
        document.querySelector('[data-section-my-study-course-status="4"]').hidden = false;
    }else{
        document.querySelector('[data-section-my-study-course-status="' + type + '"]').hidden = false;
    }
    // 알아서 분기처리.
    myStudyTypeLectureSelect();
}

// 강좌 상세보기 버튼 클릭
function myStudyShowVidoDetail(vthis) {
    // data-student-lecture-seq
    const student_lecture_seq = vthis.closest('[data-row]').querySelector('[data-student-lecture-seq]').value;
    myStudyMainDivChagne('3');
    // 강좌 상세 INFO 가져오기
    myStudyStudentLectureInfoSelect(student_lecture_seq);
}

// 강좌 상세 > 강좌 상세보기 > 강좌 상세 INFO 가져오기
function myStudyShowVideoTeachInfoToggle(vthis) {
    // data-div-my-study-video-teach-info hidden toggle
    // vthis rotate-180 class toggle
    const div_teach_info = document.querySelector('[data-div-my-study-video-teach-info]');
    if (div_teach_info.hidden) {
        div_teach_info.hidden = false;
        vthis.classList.add('rotate-180');
    } else {
        div_teach_info.hidden = true;
        vthis.classList.remove('rotate-180');
    }
    // [추가 코드]
    // 이부분에서 정보를 버튼을 클릭할때 가져오면 하단부에 정보를 가져오는 코드 추가.
}

// 수강현황 페이지 리스트 가져오기.
function myStudyTypeLectureSelect(page_num){
    let type = 'doing';
    const status_num = document.querySelector('[data-div-my-study-course-status-main-tab].active').dataset.divMyStudyCourseStatusMainTab;
    let search_str = '';
    if(status_num == 1) {
        type = 'doing';
        search_str = document.querySelector('#event_inp_search').value;
    }
    else if(status_num == 2) {
        type = 'complete';
        search_str = document.querySelector('#event_inp_search').value;
    }
    else if(status_num == 3){
        if(document.querySelector('[data-btn-like-aside-tab].active').dataset.btnLikeAsideTab == 'like') type = 'islike';
        else type = 'often';
        search_str = document.querySelector('#userlect_input_search_str').value;
    }
    else if(status_num == 4){
        type = 'not_complete';
        search_str = document.querySelector('[data-select-now-week]').value;
    }
    else if(status_num == 5){
        type = 're_do';
        search_str = document.querySelector('[data-select-now-week]').value;
    }

    const page = "/student/my/study/lecture/select";
    const parameter = {
        type:type,
        page:page_num,
        search_str:search_str
    };
    queryFetch(page, parameter, function(result){
        console.log(result);
        if((result.resultCode||'') == 'success'){
            if(type == 'doing' || type == 'complete'){
                myStudyDCLectureRowSetting(result);
            }
            else if(type == 'islike'){
                myStudyLikeRowSetting(result);
            }
            else if(type == 'often'){
                myStudyOftenRowSetting(result);
            }
            else if(type == 'not_complete'){
                if(page_num == undefined) check_lectures = {};
                myStudyNRCLectureRowSetting(result,type);
            }
            else if(type == 're_do'){
                if(page_num == undefined) check_lectures = {};
                myStudyNRCLectureRowSetting(result,type);
            }
        }
    });
}

// 수강중인 강좌,  완료강좌 테이블 세팅
function myStudyDCLectureRowSetting(result){
    // 초기화
    const section_tab = document.querySelector('[data-section-my-study-course-status="1"]');
    const bundle = section_tab.querySelector('[data-bundle="lectures"]');
    const row_copy1 = bundle.querySelector('[data-row="copy1"]').cloneNode(true)
    // const row_copy2 = bundle.querySelector('[data-row="copy2"]').cloneNode(true)
    bundle.innerHTML = '';
    bundle.appendChild(row_copy1);
    // bundle.appendChild(row_copy2);

    const lecutres = result.student_lectures.data;
    const details = result.student_lecture_details;
    myStudyAllDetailsLenth(details);
    // 페이징
    myStudyTablePaging(result.student_lectures, '1');
    lecutres.forEach(function(lecture){

        //row1

        const row1 = row_copy1.cloneNode(true);
        // const row2 = row_copy2.cloneNode(true);
        const id = lecture.id;
        row1.hidden = false;
        row1.querySelector('[data-student-lecture-seq]').value = id;
        row1.querySelector('[data-lecture-img]').src = `/images/${lecture.function_code}.svg`;
        row1.querySelector('[data-lecture-name]').innerHTML = lecture.lecture_name;
        row1.querySelector('[data-teach-name]').innerHTML = lecture.teacher_name;
        row1.querySelector('[data-complete-cnt]').innerHTML = myStudyGetCompleteCnt(details[id]);
        row1.querySelector('[data-lecture-details-cnt]').innerHTML = ' / ' + (details[id]?.length != undefined ? details[id].length : 0);
        // lecture.end_date 에서 현재 일을 빼서 일수 계산.
        const remain_cnt = new Date(lecture.end_date) - new Date();
        const remain_day = Math.ceil(remain_cnt / (1000*60*60*24));
        // row1.querySelector('[data-remain-cnt-day]').innerHTML = remain_day
        // TODO: 정확히 수강기간이 뭔지 확인. goods 의 기간인지.
        // row1.querySelector('[data-start-end-date]').innerHTML =
        //     `${lecture.start_date.replace(/-/gi, '.').substr(2,8)} ~ ${lecture.end_date.replace(/-/gi, '.').substr(2,8)}`;
        row1.querySelector('[data-details-start-end-date]').innerHTML =
            `${lecture.start_date.replace(/-/gi, '.').substr(2,8)} ~ ${lecture.end_date.substr(2,8)}`;
        row1.querySelector('[data-last-do-datetime]').innerHTML = myStudyGetLastDoDateTime(details[id]).substr(0, 10).replace(/-/gi, '.');
        // row2
        // row2.querySelector('[data-student-lecture-seq]').value = id;
        // row2.querySelector('[data-expected-completion-date]').innerHTML = lecture.end_date.replace(/-/gi, '.').substr(2,8);
        // row2.querySelector('[data-complete-cnt]').innerHTML = myStudyGetCompleteCnt(details[id]);
        // row2.querySelector('[data-lecture-details-cnt]').innerHTML = ' / '+details[id]?.length;
        // row2.querySelector('[data-now-per]').innerHTML = myStudyGetNowPer(details[id]);
        // row2.querySelector('[data-recommend-per]').innerHTML = myStudyGetRecommendPer(details[id]);
        // myStudySetDay(row2, lecture);
        // myStudySetBarPer(row2, details[id]);
        myStudySetDay(row1, lecture);
        bundle.appendChild(row1);
            // bundle.appendChild(row2);

    });

}

function myStudyAllDetailsLenth(details){
    let all_cnt = 0;
    const keys = Object.keys(details);
    keys.forEach(function(key){
        const detail = details[key];
        all_cnt += detail.length;
    });
    document.querySelector('[data-all-lecture-details-cnt]').innerHTML = all_cnt;
}
// 수강중인 강좌, 완료강좌 테이블 세팅 > 완료 강의 수
function myStudyGetCompleteCnt(detail){
    let complete_cnt = 0;
    if(detail == undefined) return complete_cnt;
    detail.forEach(function(d){
        if(d.status == 'complete')
            complete_cnt++;
    });
    return complete_cnt;
}
// 수강중인 강좌, 완료강좌 테이블 세팅 > 날짜 세팅
function myStudyGetDetailsStartEndDate(detail){}
// 수강중인 강좌, 완료강좌 테이블 세팅 > 최종 학습일
function myStudyGetLastDoDateTime(detail){
    let last_datetime = '';
    detail?.forEach(function(d){
    if(d.status == 'complete')
        // updated_at
        if(d.updated_at > last_datetime)
            last_datetime = d.updated_at;
    });
    return last_datetime;
}
// 수강중인 강좌, 완료강좌 테이블 세팅 > 현재 진행률
function myStudyGetNowPer(detail){
    let complete_cnt = 0;
    let all_cnt = 0;
    detail.forEach(function(d){
        if(d.status == 'complete') complete_cnt++;
        all_cnt++;
    });
    return Math.floor(complete_cnt / all_cnt * 100);
}
// 수강중인 강좌, 완료강좌 테이블 세팅 > 추천 진행률
function myStudyGetRecommendPer(detail){
    // 시작일과 마지막일 그리고 현재일을 구해서 현재일이 몇퍼센트인지 계산.
    let start_date = '';
    let end_date = '';
    detail.forEach(function(d){
        if(start_date == '' || d.sel_date < start_date) start_date = d.sel_date;
        if(end_date == '' || d.sel_date > end_date) end_date = d.sel_date;
    });
    const now_date = new Date();
    const start = new Date(start_date);
    const end = new Date(end_date);
    let recommend_per = Math.floor((now_date - start) / (end - start) * 100);
    if(recommend_per > 100) recommend_per = 100;
    return recommend_per;
}
// 수강중인 강좌, 완료강좌 테이블 세팅 > 요일 체크
function myStudySetDay(row, lecture){
    if(lecture.is_sun != 'Y') row.querySelector('[data-day="sun"]').hidden = true;
    if(lecture.is_mon != 'Y') row.querySelector('[data-day="mon"]').hidden = true;
    if(lecture.is_tue != 'Y') row.querySelector('[data-day="tue"]').hidden = true;
    if(lecture.is_wed != 'Y') row.querySelector('[data-day="wed"]').hidden = true;
    if(lecture.is_thu != 'Y') row.querySelector('[data-day="thu"]').hidden = true;
    if(lecture.is_fri != 'Y') row.querySelector('[data-day="fri"]').hidden = true;
    if(lecture.is_sat != 'Y') row.querySelector('[data-day="sat"]').hidden = true;
}
// 현재진행, 추천진행 Bar 에 넣기.
function myStudySetBarPer(row, detail){
    const per = myStudyGetNowPer(detail);
    const recommend_per = myStudyGetRecommendPer(detail);
    row.querySelector('[data-bar-per]').style.width = per + '%';
    row.querySelector('[data-recommend-bar-per]').style.width = recommend_per + '%';
}
// 수강, 완료 TR 강좌 클릭.
function myStudyClickTrLectures(vthis){
    if(vthis.classList.contains('active')){
        vthis.classList.remove('active');
        vthis.nextElementSibling.hidden = true;
    }else{
        vthis.classList.add('active');
        vthis.nextElementSibling.hidden = false;
    }
}
// 페이징  함수
function myStudyTablePaging(rData, target){
    if(!rData) return;
    const from = rData.from;
    const last_page = rData.last_page;
    const per_page = rData.per_page;
    const total = rData.total;
    const to = rData.to;
    const current_page = rData.current_page;
    const data = rData.data;
    //페이징 처리
    const notice_ul_page = document.querySelector(`[data-page='${target}']`);
    //prev button, next_button
    const page_prev = notice_ul_page.querySelector(`[data-page-prev='${target}']`);
    const page_next = notice_ul_page.querySelector(`[data-page-next='${target}']`);
    //페이징 처리를 위해 기존 페이지 삭제
    notice_ul_page.querySelectorAll(".page_num").forEach(element => {
        element.remove();
    });
    //#page_first 클론
    const page_first = document.querySelector(`[data-page-first='${target}']`);
    //페이지는 1~10개 까지만 보여준다.
    let page_start = 1;
    let page_end = 10;
    if(current_page > 5){
        page_start = current_page - 4;
        page_end = current_page + 5;
    }
    if(page_end > last_page){
        page_end = last_page;
        if(page_end <= 10)
            page_start = 1;
    }


    let is_next = false;
    for(let i = page_start; i <= page_end; i++){
        const copy_page_first = page_first.cloneNode(true);
        copy_page_first.innerText = i;
        copy_page_first.removeAttribute("data-page-first");
        copy_page_first.classList.add("page_num");
        copy_page_first.hidden = false;
        //현재 페이지면 active
        if(i == current_page){
            copy_page_first.classList.add("active");
        }
        //#page_first 뒤에 붙인다.
        notice_ul_page.insertBefore(copy_page_first, page_next);
        //시작 페이지 보다 11보다 큰 i 이면 page_prev disabled 을 제거
        if(i > 11){
            page_next.setAttribute("data-is-next", "1");
            page_prev.classList.remove("disabled");
        }else{
            page_next.setAttribute("data-is-next", "0");
        }
        if(i == 1){
            // page_prev.classList.add("disabled");
        }
        if(last_page == i){
            // page_next.classList.add("disabled");
            is_next = true;
        }
    }
    if(!is_next){
        page_next.classList.remove("disabled");
    }

    if(data.length != 0)
        notice_ul_page.hidden = false;
        else
        notice_ul_page.hidden = true;
}

// 페이징 클릭시 펑션
function myStudyPageFunc(target, type){
    if(type == 'next'){
        const page_next = document.querySelector(`[data-page-next="${target}"]`);
        if(page_next.getAttribute("data-is-next") == '0') return;
        // data-page 의 마지막 page_num 의 innerText를 가져온다
        const last_page = document.querySelector(`[data-page="${target}"] .page_num:last-of-type`).innerText;
        const page = parseInt(last_page) + 1;
        myStudyTypeLectureSelect(page);
    }
    else if(type == 'prev'){
        // [data-page-first]  next tag 의 innerText를 가져온다
        const page_first = document.querySelector(`[data-page-first="${target}"]`);
        const page = page_first.innerText;
        if(page == 1) return;
        const page_num = page*1 -1;
        myStudyTypeLectureSelect(page);
    }
    else{
        myStudyTypeLectureSelect(type);
    }
}

// 관심강좌 가져오기.
function myStudyLikeRowSetting(result){
    // 초기화
    const bundle = document.querySelector('[data-bundle="like_lectures"]');
    const row_copy = bundle.querySelector('[data-row="copy"]');
    bundle.innerHTML = '';
    bundle.appendChild(row_copy);
    //페이징
    myStudyTablePaging(result.lectures, '2');

    document.querySelector('[data-is-like-cnt]').innerText = result.lectures.total;
    const lectures = result.lectures.data;
        lectures.forEach(function(lecture){
        const row = row_copy.cloneNode(true);
        row.hidden = false;
        row.querySelector('[data-student-lecture-detail-seq]').value = lecture.id;
        row.querySelector('[data-file-path]').src = `${lecture.file_path ? `/storage/${lecture.file_path}` : '/images/svg/all_Character.svg'}`;

        row.querySelector('[data-lecture-name]').innerHTML = lecture.lecture_name;
        row.querySelector('[data-lecture-detail-name]').innerHTML = lecture.lecture_detail_name +' '+ lecture.lecture_detail_description;
        row.querySelector('[data-lecture-description]').innerHTML = lecture.lecture_description;
        row.querySelector('[data-teacher-name]').innerHTML = lecture.teacher_name;
        bundle.appendChild(row);
    });
}

// 관심강좌 > 찜한 강좌, 최근 많이 본 강좌 TAB
function userlectAsideTab(vthis){
    document.querySelectorAll('[data-btn-like-aside-tab]').forEach(function(el){
        el.classList.remove('active');
    });
    vthis.classList.add('active');

    const type = vthis.getAttribute('data-btn-like-aside-tab');
    // data-section-like-aside-sub 모두 숨김처리.
    document.querySelectorAll('[data-section-like-aside-sub]').forEach(function(item) {
        item.hidden = true;
    });
    document.querySelector('[data-section-like-aside-sub="' + type + '"]').hidden = false;
    myStudyTypeLectureSelect();
}
// 찜한 강좌 하트 클릭
function myStudyVideoClickLike(vthis){
    const row = vthis.closest('[data-row]');
    const student_lecture_detail_seq = row.querySelector('[data-student-lecture-detail-seq]').value;
    const is_like = row.querySelector('[data-inp-is-like]').value == 'Y' ? 'N':'Y';

    const page = "/student/my/study/lecture/like/cancel";
    const parameter = {
        student_lecture_detail_seq:student_lecture_detail_seq,
    };
    const content =
        " <div class='text-sb-24px'>관심 강좌를 취소하시겠습니까?</div> ";
    sAlert('', content, 3, function(){
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            // is_like 가 Y 이면 버튼안에 붉은색 하트, N이면 그레이
            // if(is_like == 'Y'){
            //     vthis.querySelector('[data-btn-is-like="red]').hidden = true;
            //     vthis.querySelector('[data-btn-is-like="gray]').hidden = false;
            // }else{
            //     vthis.querySelector('[data-btn-is-like="red]').hidden = false;
            //     vthis.querySelector('[data-btn-is-like="gray]').hidden = true;
            // }
            // vthis.querySelector('[data-inp-is-like]').value = is_like;
            myStudyTypeLectureSelect();
        }

    });
    });
}

// 최근 많이 본 강좌
function myStudyOftenRowSetting(result){
    // 초기화
    const bundle = document.querySelector('[data-bundle="often_lectures"]');
    const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
    bundle.innerHTML = '';
    bundle.appendChild(row_copy);

    // 페이징
    myStudyTablePaging(result.student_lectures, '3');
    const student_lectures = result.student_lectures.data;
    const details = result.student_lecture_details;
    let lectureName;
    student_lectures.forEach(function(lecture){

        if(lecture.lecture_name.includes('영어')){
            lectureName = '/images/subject_eng_icon.svg';
        }else if(lecture.lecture_name.includes('국어')){
            lectureName = '/images/subject_kor_icon.svg';
        }else if(lecture.lecture_name.includes('수학')){
            lectureName = '/images/subject_math_icon.svg';
        }else if(lecture.lecture_name.includes('사회')){
            lectureName = '/images/subject_social_icon.svg';
        }else if(lecture.lecture_name.includes('과학')){
            lectureName = '/images/subject_other_icon.svg';
        }else{
            lectureName = '/images/subject_kor_icon.svg';
        }

        const key = lecture.id;
        const detail = details[key];
        const row = row_copy.cloneNode(true);
        row.hidden = false;
        row.querySelector('[data-file-path]').src = lectureName;
        row.querySelector('[data-lecture-name]').innerHTML = lecture.lecture_name;
        row.querySelector('[data-teacher-name]').innerHTML = lecture.teacher_name + '선생님';
        row.querySelector('[data-complete-cnt]').innerHTML = myStudyGetCompleteCnt(detail);
        row.querySelector('[data-lecture-details-cnt]').innerHTML = detail.length;
        row.querySelector('[data-updated-at]').innerHTML = lecture.updated_at.replace(/-/gi, '.').substr(2, 14);
        bundle.appendChild(row);
    });
}

// 재수강.
function myStudyNRCLectureRowSetting(result,type){
    const section_tab = document.querySelector('[data-section-my-study-course-status="4"]');
    // 초기화.
    const bundle = section_tab.querySelector('[data-bundle="not_complete_lectures"]');
    const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
    bundle.innerHTML = '';
    bundle.appendChild(row_copy);
    if(type == 'not_complete'){
        section_tab.querySelectorAll('[data-in="not_complete"]').forEach(function(el){
            el.hidden = false;
        });
        section_tab.querySelectorAll('[data-in="re_do"]').forEach(function(el){
            el.hidden = true;
        });
    }else if(type == 're_do'){
        section_tab.querySelectorAll('[data-in="not_complete"]').forEach(function(el){
            el.hidden = true;
        });
        section_tab.querySelectorAll('[data-in="re_do"]').forEach(function(el){
            el.hidden = false;
        });
    }
    //페이징
    myStudyTablePaging(result.student_lecture_details, '4');
    const all_cnt_el = section_tab.querySelector('[data-all-lectures-cnt]');
    all_cnt_el.innerHTML = `총 ${result.student_lecture_details.total} 개의 강좌`;
    const details = result.student_lecture_details.data;
    details.forEach(function(detail){
        const row = row_copy.cloneNode(true);
        row.hidden = false;
        row.querySelector('[data-student-lecture-detail-seq]').value = detail.id;
        row.querySelector('[data-subject-img]').src = `/images/${detail.function_code}.svg`;
        row.querySelector('[data-lecture-name]').innerHTML = detail.lecture_name +`[${detail.lecture_detail_name}]`;
        row.querySelector('[data-lecture-description]').innerHTML = detail.lecture_description;
        row.querySelector('[data-subject-name]').innerHTML = detail.subject_name;
        row.querySelector('[data-sel-date]').innerHTML = (detail.sel_date||'').substr(0, 10).replace(/-/gi, '.')+'<br>'+detail.sel_day+'요일';
        // 학습전이면 나오면 안되므로.
        if(detail.status != 'ready') row.querySelector('[data-last-do-datetime]').innerHTML = detail.updated_at.substr(0, 10).replace(/-/gi, '.');

        // TODO: 학습시간이 첫 준비하기의 비디오 시청 시간이 아니므로, 어떻게 해야할지 기획자 의견 필요.
        // "last_video_time/lecture_detail_time" 의 백분율
        // row.querySelector('[data-time-complete]').setAttribute('width', detail.last_video_time / detail.lecture_detail_time * 100 + '%');
        row.querySelector('[data-bar-per]').setAttribute('width', detail.last_video_time / detail.lecture_detail_time * 100 + '%')
        if(detail.last_video_time){
            row.querySelector('[data-time-complete]').innerText = formatTime(detail.last_video_time) + ' / ' + formatTime(detail.lecture_detail_time);
        }
        if(detail.status == 'complete'){
            //class add studey-completion
            total_complete++;
            row.querySelector('[data-status]').innerText = '학습 완료';
            row.querySelector('[data-status]').classList.add('studey-completion');
        }else if(detail.status == 'ready'){
            row.querySelector('[data-status]').innerText = '학습 전';
            row.querySelector('[data-status]').classList.add('studey-before');
        }else if(detail.status == 'study'){
            row.querySelector('[data-status]').innerText = '학습 중';
            row.querySelector('[data-status]').classList.add('studey-doing');
        }
        bundle.appendChild(row);
    });
}

// :check배열
var check_lectures = {};
// 체크할때, 체크한 강좌(강의) 배열에 넣기.
function myStudyCheckLecture(vthis){
    const row = vthis.closest('[data-row]');
    const detail_seq = row.querySelector('[data-student-lecture-detail-seq]').value;
    const lecture_name = row.querySelector('[data-lecture-name]').innerText;
    const subject_img = row.querySelector('[data-subject-img]').src;
    const course_name = row.querySelector('[data-course-name]').innerText;

    if(vthis.checked){
        check_lectures[detail_seq] = {
            lecture_name:lecture_name,
            subject_img:subject_img,
            course_name:course_name,
        };
    }else{
        delete check_lectures[detail_seq];
    }
}
// 학습플래너에 추가 버튼 클릭.
function myStudyLearningPlainAdd(){
    // 체크할때 배열에 넣기.
    // 배열에 있는지 확인.
    const keys = Object.keys(check_lectures);
    if(keys.length == 0){
        toast('선택된 강좌가 없습니다.');
        return;
    }
    // 초기화.
    const bundle = document.querySelector('[data-bundle="learning_plan_add"]');
    const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
    bundle.innerHTML = '';
    bundle.appendChild(row_copy);

    keys.forEach(function(key){
        const data = check_lectures[key];
        const row = row_copy.cloneNode(true);
        row.hidden = false;
        row.querySelector('[data-student-lecture-detail-seq]').value = key;
        row.querySelector('[data-subject-img]').src = data.subject_img;
        row.querySelector('[data-course-name]').innerHTML = data.course_name;
        row.querySelector('[data-lecture-name]').innerHTML = data.lecture_name;
        bundle.appendChild(row);
    });

    const myModal = new bootstrap.Modal(document.getElementById('modal_learning_plan_add'), {
        keyboard: false,
        backdrop: 'static'
    });
    myModal.show();
}

// 모달안에 있는 체크박스 해제
function myStudyPlanAdd(){
    const modal = document.getElementById('modal_learning_plan_add');
    const sel_date = modal.querySelector('[data-sel-date]').value;
    const student_lecture_detail_seqs = [];
    const bundle = modal.querySelector('[data-bundle="learning_plan_add"]');
    const checks = bundle.querySelectorAll('input[type="checkbox"]');
    checks.forEach(function(check){
        const row = check.closest('[data-row]');
        const seq = row.querySelector('[data-student-lecture-detail-seq]').value;
        if(check.checked && seq != ""){
            student_lecture_detail_seqs.push(row.querySelector('[data-student-lecture-detail-seq]').value);
        }
    });
    //오늘보다 과거면 넣을수 없다.
    if(sel_date < new Date().format('yyyy-MM-dd')){
        toast('과거일은 추가할 수 없습니다.');
        return;
    }
    // TODO: Plan add
    const page = "/student/my/study/lecture/plan/insert";
    const parameter = {
        sel_date:sel_date,
        student_lecture_detail_seqs:student_lecture_detail_seqs,
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            toast('학습플래너에 추가되었습니다.');
            modal.querySelector('button[data-bs-dismiss]').click();
        }
    });

}

// 학습플래너 추가 모달 체크 박스 클리어
function myStudyPlanAddClear(){
    const bundle = document.querySelector('[data-bundle="learning_plan_add"]');
    const checks = bundle.querySelectorAll('input[type="checkbox"]');
    checks.forEach(function(check){
        check.checked = false;
    });
}

// 강좌 상세보기
function myStudyStudentLectureInfoSelect(student_lecture_seq){
    document.querySelector('[data-page="lecture"]').hidden = true;
    document.querySelector('[data-page="lecture_detail"]').hidden = false;
    const page = "/student/my/study/lecture/detail/page/info/select";
    const parameter = {
        student_lecture_seq:student_lecture_seq,
    };
    const section_tab = document.querySelector('[data-section-lectrure-details]');
    myStudyStudentLectureInfoClear();
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            const lectures = result.lectures[0];
            const st_lec_details = result.student_lecture_details;

            section_tab.querySelector('[data-lecture-name]').innerHTML = lectures.lecture_name;
            section_tab.querySelector('[data-teacher-name]').innerHTML = lectures.teacher_name
            // TODO: 학습자수 체크.
            section_tab.querySelector('[data-number-learners]').innerHTML = 2,432;
            section_tab.querySelector('[data-lecture-grade]').innerHTML = lectures.grade_name;
            section_tab.querySelector('[data-lecture-level]').innerHTML = lectures.level_name;
            section_tab.querySelector('[data-lecture-series]').innerHTML = lectures.series_name;
            section_tab.querySelector('[data-course-date-count]').innerHTML = lectures.course_date_count;
            //
            const bundle = section_tab.querySelector('[data-bundle="lecture_detail_list"]');
            const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

            let all_cnt = st_lec_details.length;
            let complete_cnt = 0;
            const all_exams = result.all_exams;
            const student_exam_results = result.student_exam_results;
            st_lec_details.forEach(function(detail){
                const row = row_copy.cloneNode(true);
                row.setAttribute('data-row', 'clone');
                row.hidden = false;
                row.querySelector('[data-lecture-detail-name]').innerHTML = detail.lecture_detail_name;
                row.querySelector('[data-lecture-detail-description]').innerHTML = detail.lecture_detail_description;
                row.querySelector('[data-lecture-detail-time]').innerHTML = detail.lecture_detail_time;
                if(all_exams[detail.lecture_detail_seq]){
                   let normal_cnt = 0;
                    let similar_cnt = 0;
                    let challenge_cnt = 0;
                    let challenge_similar_cnt = 0;
                    all_exams[detail.lecture_detail_seq].forEach(function(exam){
                        if(exam.exam_type == 'normal') normal_cnt++;
                        if(exam.exam_type == 'similar') similar_cnt++;
                        if(exam.exam_type == 'challenge') challenge_cnt++;
                        if(exam.exam_type == 'challenge_similar') challenge_similar_cnt++;
                    });
                    if(normal_cnt > 0){
                        row.querySelector('[data-all-question-cnt="normal"]').innerText = '/ '+normal_cnt;
                        row.querySelector('[data-all-question-cnt="normal"]').closest('.row').hidden = false;
                    }
                    if(similar_cnt > 0){
                        row.querySelector('[data-all-question-cnt="similar"]').innerText = '/ '+similar_cnt;
                        row.querySelector('[data-all-question-cnt="similar"]').closest('.row').hidden = false;
                    }
                    if(challenge_cnt > 0){
                        row.querySelector('[data-all-question-cnt="challenge"]').innerText = '/ '+challenge_cnt;
                        row.querySelector('[data-all-question-cnt="challenge"]').closest('.row').hidden = false;
                    }
                    if(challenge_similar_cnt > 0){
                        row.querySelector('[data-all-question-cnt="challenge_similar"]').innerText = '/ '+challenge_similar_cnt;
                        row.querySelector('[data-all-question-cnt="challenge_similar"]').closest('.row').hidden = false;
                    }
                }
                if(student_exam_results[detail.id]){
                    const exam_type = student_exam_results[detail.id].exam_type;
                    let normal_cnt = 0;
                    let similar_cnt = 0;
                    let challenge_cnt = 0;
                    let challenge_similar_cnt = 0;
                    student_exam_results[detail.id].forEach(function(exam){
                        if(exam.exam_type == 'normal' && exam.exam_status == 'correct') normal_cnt++;
                        if(exam.exam_type == 'similar' && exam.exam_status == 'correct') similar_cnt++;
                        if(exam.exam_type == 'challenge' && exam.exam_status == 'correct') challenge_cnt++;
                        if(exam.exam_type == 'challenge_similar' && exam.exam_status == 'correct') challenge_similar_cnt++;
                    });
                    if(normal_cnt > 0){ row.querySelector('[data-complete-question-cnt="normal"]').innerText = normal_cnt; }
                    if(similar_cnt > 0){ row.querySelector('[data-complete-question-cnt="similar"]').innerText = similar_cnt; }
                    if(challenge_cnt > 0){ row.querySelector('[data-complete-question-cnt="challenge"]').innerText = challenge_cnt; }
                    if(challenge_similar_cnt > 0){ row.querySelector('[data-complete-question-cnt="challenge_similar"]').innerText = challenge_similar_cnt; }
                }
                //:학습span
                if(detail.status == 'complete'){
                    complete_cnt++;
                    row.querySelector('[data-status]').innerText = '학습 완료';
                    row.querySelector('[data-status]').classList.add('studey-completion');
                }else if(detail.status == 'ready'){
                    row.querySelector('[data-status]').innerText = '학습 전';
                    row.querySelector('[data-status]').classList.add('studey-before');
                }else if(detail.status == 'study'){
                    row.querySelector('[data-status]').innerText = '학습 중';
                    row.querySelector('[data-status]').classList.add('studey-doing');
                }
                row.querySelector('[data-btn-play]').setAttribute('onclick', 'myStudyPlayVido('+detail.id+')');
            bundle.appendChild(row);
            });
            section_tab.querySelectorAll('[data-img-lecture-file_path]').forEach(function(el){
                el.src = '/storage/' + lectures.file_path;
            });
            section_tab.querySelector('[data-all-lecdture-complete-cnt]').innerHTML = `총 ${complete_cnt}/${all_cnt} 개`;
        }
    });
}

// 강좌 상세보기 클리어
function myStudyStudentLectureInfoClear(){
    const section_tab = document.querySelector('[data-section-lectrure-details]');
    section_tab.querySelectorAll('[data-img-lecture-file_path]').forEach(function(el){
        el.src = '';
    });
    section_tab.querySelector('[data-lecture-name]').innerHTML = '';
    section_tab.querySelector('[data-teacher-name]').innerHTML = '';
    section_tab.querySelector('[data-number-learners]').innerHTML = '';
    section_tab.querySelector('[data-lecture-grade]').innerHTML = '';
    section_tab.querySelector('[data-lecture-level]').innerHTML = '';
    section_tab.querySelector('[data-lecture-series]').innerHTML = '';
    section_tab.querySelector('[data-course-date-count]').innerHTML = '';

    // data-bundle="lecture_detail_list"
    const bundle = section_tab.querySelector('[data-bundle="lecture_detail_list"]');
    bundle.querySelectorAll('[data-row="clone"]').forEach(function(el){
        el.remove();
    });
}

function myStudyPlayVido(st_lecture_detail_seq){
    const form = document.querySelector('[data-form="study_video"]');
    form.querySelector('input[name="st_lecture_detail_seq"]').value = st_lecture_detail_seq;
    form.submit();
}

// :현재 년월의 주차를 select에 추가.
function myStudySelectTagMakeNowWeek(){
    // all [data-select-now-week] 에 만들어서 넣기.
    // 오늘을 포함한 이번주의 월요일부터 일요일까지의 날짜를 구한다.
    const today = new Date();
    const option1 = myStudySelectWeekDate(today);
    const option2 = myStudySelectWeekDate(new Date(today.setDate(today.getDate() - 7)));
    const option3 = myStudySelectWeekDate(new Date(today.setDate(today.getDate() - 7)));
    const option4 = myStudySelectWeekDate(new Date(today.setDate(today.getDate() - 7)));
    const option5 = myStudySelectWeekDate(new Date(today.setDate(today.getDate() - 7)));

    const select = document.querySelectorAll("[data-select-now-week]");
    select.forEach(function(item){
        item.innerHTML = `
        <option value="${option1.date}">${option1.month} ${option1.week}주차</option>
        <option value="${option2.date}">${option2.month} ${option2.week}주차</option>
        <option value="${option3.date}">${option3.month} ${option3.week}주차</option>
        <option value="${option4.date}">${option4.month} ${option4.week}주차</option>
        <option value="${option5.date}">${option5.month} ${option5.week}주차</option>
        `;
    });
}

function myStudySelectWeekDate(sel_date){
    const today = sel_date;
    const start_date = new Date(today.setDate(today.getDate() - today.getDay()));
    const end_date = new Date(today.setDate(today.getDate() + 6));

    // 오늘 월의 몇주차인지 구한다.
    const now_week = Math.ceil((today.getDate() + 6 - today.getDay()) / 7);

    const rtn_date = {
        month: end_date.format('yyyy년 MsM월'),
        date :`${start_date.format('yyyy-MM-dd')}|${end_date.format('yyyy-MM-dd')}`,
        week : now_week
    };
    return rtn_date;
}

 const formatTime = (time) => {
    const minutes = Math.floor(time / 60);
    const seconds = Math.floor(time % 60);
    return `${minutes}:${seconds.toString().padStart(2, "0")}`;
};

// TODO: 학습결과 클릭
function myStudyViewLearningResults(vthis){
    let all_normat_cnt = 0;
    let all_similar_cnt = 0;
    let all_challenge_cnt = 0;
    let all_challenge_similar_cnt = 0;
    let suc_normal_cnt = 0;
    let suc_similar_cnt = 0;
    let suc_challenge_cnt = 0;
    let suc_challenge_similar_cnt = 0;

   const td = vthis.closest('td');
   all_normat_cnt = td.querySelector('[data-all-question-cnt="normal"]').innerText;
   all_similar_cnt = td.querySelector('[data-all-question-cnt="similar"]').innerText;
   all_challenge_cnt = td.querySelector('[data-all-question-cnt="challenge"]').innerText;
   all_challenge_similar_cnt = td.querySelector('[data-all-question-cnt="challenge_similar"]').innerText;
   suc_normal_cnt = td.querySelector('[data-complete-question-cnt="normal"]').innerText;
   suc_similar_cnt = td.querySelector('[data-complete-question-cnt="similar"]').innerText;
   suc_challenge_cnt = td.querySelector('[data-complete-question-cnt="challenge"]').innerText;
   suc_challenge_similar_cnt = td.querySelector('[data-complete-question-cnt="challenge_similar"]').innerText;
    modalClearCnt();
    const modal = document.querySelector('#modal_lecture_result');
    modal.querySelector('[data-all="normal"]').innerText = all_normat_cnt.replace('\/ ', '');
    modal.querySelector('[data-all="similar"]').innerText = all_similar_cnt.replace('\/ ', '');
    modal.querySelector('[data-all="challenge"]').innerText = all_challenge_cnt.replace('\/ ', '');
    modal.querySelector('[data-all="challenge_similar"]').innerText = all_challenge_similar_cnt.replace('\/ ', '');
    modal.querySelector('[data-suc="normal"]').innerText = suc_normal_cnt;
    modal.querySelector('[data-suc="similar"]').innerText = suc_similar_cnt;
    modal.querySelector('[data-suc="challenge"]').innerText = suc_challenge_cnt;
    modal.querySelector('[data-suc="challenge_similar"]').innerText = suc_challenge_similar_cnt;

    const myModal = new bootstrap.Modal(document.getElementById('modal_lecture_result'), {
        keyboard: false
    });
    myModal.show();
}
function modalClearCnt(){
    const modal = document.querySelector('#modal_lecture_result');
    modal.querySelector('[data-all="normal"]').innerText = '0';
    modal.querySelector('[data-all="similar"]').innerText = '0';
    modal.querySelector('[data-all="challenge"]').innerText = '0';
    modal.querySelector('[data-all="challenge_similar"]').innerText = '0';
    modal.querySelector('[data-suc="normal"]').innerText = '0';
    modal.querySelector('[data-suc="similar"]').innerText = '0';
    modal.querySelector('[data-suc="challenge"]').innerText = '0';
    modal.querySelector('[data-suc="challenge_similar"]').innerText = '0';
}
//학습하기.
function myStudyVideoPlay(vthis){
    const pt_row = vthis.closest('[data-row]');
    const st_lecture_detail_seq = pt_row.querySelector('[data-student-lecture-detail-seq]').value;
    myStudyPlayVido(st_lecture_detail_seq);
}
</script>
@endsection
