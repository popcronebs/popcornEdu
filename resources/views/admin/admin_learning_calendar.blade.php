@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
학습플래너 관리
@endsection
{{-- 추가 코드 --}}
{{--
     1. 지각, 출석 표기
     2. 필터 기능 - 필터시 안보이게 안보이게 처음엔 모두 체크. remain / {출석완료, 지각 미처리} [part_ok]
     3. 클릭시 강의 상세 내용 불러올때 로딩바 표기.

--}}
{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<!-- : SETP2 에서 과목 재설정시에 하단에 검색버튼 다시 나오게 수정. -->
<!-- : 강의 드래그시에 3번재 부터 안되는 현상 수정. -->
<link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}">
<script type="text/javascript" src="//code.jquery.com/jquery-3.7.1.min.js"></script>
<script type="text/javascript" src="{{ asset('js/moment.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery.daterangepicker.js') }}"></script>

<style>
    .cal_indiv {
        font-size: 0.8em;
    }

    .white {
        color: #fff
    }

    #learncal_div_main td,
    #learncal_div_main th,
    #learncal_div_main tr {
        border: 0px solid white;
        color: #6c757d;
        text-align: center;
    }

    #learncal_div_main .tby_cal td {
        padding: 4px;
        cursor: pointer;
        vertical-align: top;
        height: 180px;
        height: 180px;
    }

    #learncal_div_main .tby_cal td .calnum {
        width: 44px;
        height: 44px;
        margin-top: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #learncal_div_main .tby_cal td .calnum.text-white {
        color: #ffffff !important;
    }

    #learncal_div_main2 .tby_cal td .calnum {
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .hidden {
        display: none !important;
    }

    .form-check-input.zoom1 {
        zoom: 1.04;
    }

    .gray.form-check-input:checked {
        background-color: #DCDCDC;
        border-color: #DCDCDC;
    }

    .black.form-check-input:checked {
        background-color: #222;
        border-color: #222;
    }
    .modal-680 {
        --bs-modal-width: 680px;
    }

    .bg-goal-time1 {
        background-color: #FFC747;
        border-radius: 9px;
        text-align: left;
        padding-left: 12px;
    }

    .bg-attend-time1 {
        background-color: #2FCD94;
        color: white;
        border-radius: 9px;
        text-align: left;
        padding-left: 12px;
    }

    .btn_study_time:hover img.img_gray {
        display: none !important;
    }

    .btn_study_time:hover img.img_white {
        display: block !important;
    }

    .all-center {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-goal-time2 {
        background-color: #FFC747;
        color: white;
        display: flex;
    }

    .bg-attend-time2 {
        background: #2FCD94;
        color: white;
    }

    .bg-complte {
        background: #EC5E69;
        color: white;
    }

    .bg-filter-1 {
        background: #8525D2;
        color: white;
    }

    .bg-filter-2 {
        background: #2C58D3;
        color: white;
    }

    .bg-filter-3 {
        background: #C933B7;
        color: white;
    }

    .bg-filter-4 {
        background: #C933B7;
        color: white;
    }

    .bg-filter-5 {
        background: #C933B7;
        color: white;
    }

    .bg-filter-6 {
        background: #C933B7;
        color: white;
    }

    .border-top-dashed {
        border-top: 1px dashed #E6E6E6
    }

    .timetable_detail figure {
        background: #f9f9f9;
        border-radius: 8px
    }

    .div_timetable_bunlde section:nth-child(n+3) {
        border-top: 1px dashed #E6E6E6;
    }

    .radio input[type=radio]:checked+span::after {
        background-color: #222222;
    }

    .btn_day {
        width: 36px;
        height: 36px;
    }

    .btn_day_pill {
        width: 36px;
        height: 28px;
    }

    .ctext-gc2-imp.text-white {
        color: #ffffff !important;
    }

    :root {
        --color-subject1: #AB4DE5;
        --color-subject2: #6F91F7;
        --color-subject3: #EC46D1;
        --color-subject4: #FFD077;
        --color-subject5: #63D8EC;
        --color-subject6: #222222;
        --attendanceTime: #FFBD19;
        --goalTime: #2FCD94;
        --studyComplete: #FF5065;
    }

    /*
        case 'complete': status = '완료'; break;
                            case 'ready': status = '미수강'; break;
                            case 'redo': status = '재수강'; break; */
    [data-span-status].ready::after {
        content: '학습전';
        color: #999999;
    }

    [data-span-status].ready_old::after {
        content: '미수강';
        color: #FFC747;
    }

    [data-span-status].study::after {
        content: '학습중';
        color: #FF5065;
    }

    [data-span-status].complete::after {
        content: '학습완료';
        color: #2FCD94;
    }

    .highlight-not-current-month {
        background-color: #fffae7 !important;
    }

    td[onclick*="learncalDateClick"] {
        position: relative;
    }

    td.active[onclick*="learncalDateClick"]::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        box-shadow: 0px 0px 12px 2px #0000000A;
        z-index: 100;
    }

    [data-span-status].complete,
    [data-span-status].ready,
    [data-span-status].ready_old,
    [data-span-status].study {
        background: #fff;
    }

    [data-span-status].redo::after,
    [data-span-status].redo-compt::after {
        content: '재수강';
    }

    [data-span-status].redo-compt {
        background: #1ACEFF;
    }

    [data-article-learncal-student-lecture-detail] {
        transition: all 0.3s ease-in-out;
    }

    #learncal_inp_time {
        position: absolute;
        left: 0px;
        bottom: 0px;
    }

    @media (max-width: 1400px) {
        .div_title {
            margin-bottom: 20px;
        }
    }
    [data-span-lecture-detail-name]{
        width: auto;
        min-width: 210px;
        max-width: 190px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
.radio input[type=checkbox]+span {
    position: relative;
    display: block;
    width: 24px;
    height: 24px;
    border: #e5e5e5 solid 2px;
    border-radius: 50%;
}
.radio input[type=checkbox]:checked+span::after {
    width: 14px;
    height: 14px;
    background-color: #FFC747;
}
.radio input[type=checkbox]:checked+span::after {
    background-color: #222222;
}
.radio input[type=checkbox] + span::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 0px;
    height: 0px;
    border-radius: 50%;
    transition: all 0.3s ease;
}
.radio input[type=checkbox] {
    visibility: hidden;
    position: absolute;
    width: 0;
    height: 0;
}
[data-a-learncal-step3-tab].active{
    color:#222!important;
}
</style>

<input type="hidden" id="inp_login_type" value="{{$login_type}}">
<div class="p-0 zoom_sm">
    {{-- 회원검색 / 관리자만 보이게 수정.--}}
    <div class="row p-0 m-0 pe-4" @if(session()->get('team_code') != 'maincd'){{ 'hidden' }} @endif>
        <div class="row p-0 m-0 border">
            <div class="bg-light col-auto p-3">회원 검색</div>
            <div class="row  p-0 m-0 col gap-2 align-items-center justify-content-between">
                <span class="w-auto p-3">소속</span>
                {{-- region --}}
                <select class="form-select form-select-sm col hpx-40" id="learncal_sel_region" onchange="learncalTeamSelect(this)">
                    <option value="">소속</option>
                    @if (!empty($regions))
                    @foreach ($regions as $region)
                    <option value="{{ $region->id }}">{{ $region->region_name }}</option>
                    @endforeach
                    @endif
                </select>
                {{-- team --}}
                <select class="form-select form-select-sm col hpx-40" id="learncal_sel_team">
                    <option value="">팀</option>
                </select>
                {{-- name, id, 전화번호 --}}
                <select class="form-select form-select-sm col hpx-40" id="learncal_sel_search_type">
                    <option value="student_name">이름</option>
                    <option value="student_phone">전화번호</option>
                    <option value="parent_name">학부모</option>
                    <option value="parent_phone">학부모 전화번호</option>
                </select>
                {{-- input serach str --}}
                <input type="text" class="form-control form-control-sm col hpx-40" placeholder="검색어를 입력하세요." id="learncal_input_search_str">
                {{-- search btn --}}
                <button type="button" class="btn btn-primary col-1 me-3" onclick="learncalUserSelect();">검색</button>
            </div>
        </div>

        {{-- 회원 목록 --}}
        <div id="learncal_div_user" class="row p-0 m-0 tableFixedHead overflow-auto border" style="max-height:160px;" hidden>
            <table class="table table-bordered m-0">
                <thead class="table-light text-center">
                    <tr>
                        <th>학교/학년</th>
                        <th>회원명/아이디</th>
                        <th>휴대전화</th>
                        <th>최근 결제일자</th>
                        <th>학부모</th>
                        <th>학부모 연락처</th>
                    </tr>
                </thead>
                <tbody id="learncal_tby_user">
                    <tr class="copy_tr_user" hidden onclick="learncalUserTrClick(this)">
                        <td data="#학교/학년">
                            <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                            <span class="school_name"></span>
                            <span class="grade"></span>
                        </td>
                        <td data="#회원명/아이디">
                            <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                            <span class="student_name"></span>
                            <span class="student_id"></span>
                        </td>
                        <td data="#휴대전화">
                            <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                            <span class="student_phone"></span>
                        </td>
                        <td data="#최근 결제일자">
                            <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                            <span class="payment_last_date"></span>
                        </td>
                        <td data="#학부모">
                            <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                            <span class="parent_name"></span>
                        </td>
                        <td data="#학부모 연락처">
                            <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                            <span class="parent_phone"></span>
                        </td>
                        <input type="hidden" class="region_name">
                        <input type="hidden" class="team_name">
                    </tr>
                </tbody>
            </table>
            {{-- 회원 목록이 없습니다. --}}
            <div class="col-12 text-center p-3" id="learncal_div_user_empty">
                <span>회원 목록이 없습니다.</span>
            </div>
        </div>
    </div>
    {{-- padding 120px --}}
    <div>
        <div class="pt-xxl-5"></div>
    </div>
    {{-- 선택 학생 이름 선생님일 경우에만 보이기.--}}
    @if($login_type == 'teacher'|| $login_type == 'parent')
    <div class="d-inline-flex gap-2 align-items-center mb-1 mb-xxl-5">
        <h1 id="learncal_h1_student_name" class="m-0 cfs-1 text-sb-42px">
            <button data-btn-learncal-back hidden class="btn p-0 row mx-0" onclick="learncalFilterPlannerToggle();">
                <img src="{{ asset('images/black_arrow_left_tail.svg') }}" width="65" class="px-0">
            </button>
            <span class="student_name text-sb-42px">@if(!empty($students)){{ $students[0]->student_name}}@else{{ '미선택' }}@endif </span> 학생
        </h1>
        <div class="rounded-5 fs-5 bg-primary-y text-white cbtn-p cfs-6 py-2">
            <span class="text-white">@if(!empty($students)){{ $students[0]->grade_name}}@endif</span>
            {{-- 0학기?? --}}
        </div>
        <h1 class="m-0 ctext-gc1 cfs-1" @if(!empty($students) && count($students) !=1) @else hidden @endif>
            외 <span>@if(!empty($students)){{ count($students)-1 }}@endif</span>명 있습니다.
        </h1>
    </div>
    @endif
    {{-- 화면 분할  --}}
    <div class="row mx-0">
        <div class="col modal-shadow-style rounded-3 pt-4">
            {{-- 달력 시작 --}}
            <div id="learncal_div_main" class="mt-4 ">
                <div class="row p-0 m-0 pb-2 mb-5">
                    <h5 class="m-0 col-3 pt-2"></h5>
                    <div class="d-flex col align-items-center justify-content-center">
                        <button class="arrowbtn btn btn-sm me-5" onclick="
                        click_NextMon('#learncal_div_main','month', 'prev');
                        learncalMainCalendarStudyTimeDisplay();
                        learncalStudyPlannerSelect();
                    " style="text-align: right;">
                            <img src="{{ asset('images/calendar_arrow_left.svg')}}" alt="">
                        </button>
                        <span class="fs-4 fw-medium mx-5">
                            <span class="stp2_year">{{ date('Y') }}</span>년
                            <span class="stp2_month">{{ date('m') }}</span>월
                        </span>

                        <button class="arrowbtn btn btn-sm ms-5" onclick="
                        click_NextMon('#learncal_div_main','month', 'next');
                        learncalMainCalendarStudyTimeDisplay();
                        learncalStudyPlannerSelect();
                    " style="text-align: left;">
                            <img src="{{ asset('images/calendar_arrow_right.svg')}}" alt="">
                        </button>
                        <input type="month" id="learingcdar_inp_month" value="{{ date('Y-m') }}" onchange="learncalSelectDate(this)" hidden>
                    </div>
                    <div class="col-3 row flex-nowrap mx-0 gap-2 justify-content-end">
                        <button data-btn-learncal-reage-delete onclick="batchDeletePopup()" class="col-auto btn btn-light bg-danger text-white h-center rounded-pill gap-1 pe-4 ps-3 text-sb-20px" hidden>
                            <img src="{{ asset('images/calendar_icon.svg') }}" width="24">
                            일괄삭제
                        </button>
                        <button data-btn-learncal-delete ondragenter="learncalCalendarDragEnter(this);" ondragleave="learncalCalendarDragLeave(this);" ondragover="learncalCalendarDragOver(this);" ondrop="learncalCalendarDragDrop(this)" onclick="toast('강의를 드래그해서 버튼위로 올려주세요.')" class="col-auto btn btn-light bg-danger text-white h-center rounded-pill gap-1 cbtn-p-i text-sb-20px" hidden>
                            <img src="{{ asset('images/trash_icon.svg') }}" width="24">
                            강의 삭제하기
                        </button>
                    </div>
                </div>
                <div id="divStep2" class="divStep2_default position-relative">
                    <!-- <div style="padding:0 20px;"> -->
                    <input type="hidden" class="search_end_date">
                    <table class="table tb_calendar fs-5" style="margin-bottom: 0px;min-height: 70vh">
                        <colgroup>
                            <col width="14%">
                            <col width="14%">
                            <col width="14%">
                            <col width="14%">
                            <col width="14%">
                            <col width="14%">
                            <col width="14%">
                        </colgroup>
                        <thead style="background-color: #f8f9fc">
                            <td class="text-left p-2 text-danger" style="width:100px">일요일</td>
                            <td class="text-left p-2" style="width:80px">월요일</td>
                            <td class="text-left p-2" style="width:100px">화요일</td>
                            <td class="text-left p-2" style="width:85px">수요일</td>
                            <td class="text-left p-2" style="width:95px">목요일</td>
                            <td class="text-left p-2" style="width:115px">금요일</td>
                            <td class="text-left p-2 text-primary" style="width:100px">토요일</td>
                        </thead>
                        <tbody class="tby_cal">
                            @for ($i = 1; $i < 7; $i++) <tr class="caltr_{{ $i }}" weeks="{{ $i }}">
                                <td class="text-center day1" {{-- ondragenter="toast('in dragenter');" --}} ondragenter="learncalCalendarDragEnter(this);" ondragleave="learncalCalendarDragLeave(this);" ondragover="learncalCalendarDragOver(this);" ondrop="learncalCalendarDragDrop(this)" onclick="learncalDateClick(this);">
                                    <div class="d-flex justify-content-center mb-1">
                                        <span class="calnum rounded-circle text-danger"></span>
                                    </div>
                                    <div class="div_study_time mt-1 py-1" hidden>
                                        <span class="study_time cfs-7 text-white"></span>
                                    </div>

                                    <div data-div-calendar-lecture-bundle class="px-3 pt-0">
                                        {{-- 최초만 가져오고 이후에는 모두 이부분에서 클론대체. --}}
                                        @if($i == 1)
                                        <div class="h-center mx-0 mt-1" data-div-calendar-lecture-row="copy" hidden>
                                            <div class="col h-center gap-1">
                                                <div data-lecture-dot-color class="col-auto rounded-circle" style="width:8px;height:8px;"></div>
                                                <span class="col-auto text-m-16px" data-lecture-subject-name>국어</span>
                                            </div>
                                            <div class="col-auto">
                                                <span class="text-m-16px" data-lecture-subject-cnt>1</span>
                                                <span class="text-m-16px">강</span>
                                            </div>
                                        </div>
                                        <div data-div-calendar-lecture-row2="copy" draggable="true" ondragstart="learncalCalendarDragStart(this)" ondragend="learncalCalendarDragEnd(this);" hidden class="h-center mx-0 mt-1 overflow-hidden" onclick="learncalLectureCalendarLectureRow2(this)">
                                            <div data-lecture-dot-color class="col-auto rounded-circle" style="width:8px;height:8px;"></div>
                                            <span class="col-auto text-m-16px" data-lecture-subject-name>국어</span>
                                            <input type="hidden" data-student-lecture-detail-seq>
                                        </div>
                                        @endif
                                    </div>
                                    <div data-img-calendar-lecture-compt class="w-center pt-3" hidden>
                                        <img src="{{ asset('images/complete_icon.svg?1') }}" width="72">
                                    </div>
                                    <div class="cal_month hidden"></div>
                                </td>
                                <td class="text-center day2" ondragenter="learncalCalendarDragEnter(this);" ondragleave="learncalCalendarDragLeave(this);" ondragover="learncalCalendarDragOver(this);" ondrop="learncalCalendarDragDrop(this)" onclick="learncalDateClick(this);">
                                    <div class="d-flex justify-content-center mb-1">
                                        <span class="calnum rounded-circle scale-text-gray_05"></span>
                                    </div>
                                    <div class="div_study_time mt-1 py-1" hidden>
                                        <span class="study_time cfs-7 text-white"></span>
                                    </div>

                                    <div data-div-calendar-lecture-bundle class="px-3 pt-0">
                                    </div>
                                    <div data-img-calendar-lecture-compt class="w-center pt-3" hidden>
                                        <img src="{{ asset('images/complete_icon.svg?1') }}" width="72">
                                    </div>

                                    <div class="cal_month hidden"></div>
                                </td>
                                <td class="text-center day3" ondragenter="learncalCalendarDragEnter(this);" ondragleave="learncalCalendarDragLeave(this);" ondragover="learncalCalendarDragOver(this);" ondrop="learncalCalendarDragDrop(this)" onclick="learncalDateClick(this);">
                                    <div class="d-flex justify-content-center mb-1">
                                        <span class="calnum rounded-circle scale-text-gray_05"></span>
                                    </div>
                                    <div class="div_study_time mt-1 py-1" hidden>
                                        <span class="study_time cfs-7 text-white"></span>
                                    </div>

                                    <div data-div-calendar-lecture-bundle class="px-3 pt-0">
                                    </div>
                                    <div data-img-calendar-lecture-compt class="w-center pt-3" hidden>
                                        <img src="{{ asset('images/complete_icon.svg?1') }}" width="72">
                                    </div>

                                    <div class="cal_month hidden"></div>
                                </td>
                                <td class="text-center day4" ondragenter="learncalCalendarDragEnter(this);" ondragleave="learncalCalendarDragLeave(this);" ondragover="learncalCalendarDragOver(this);" ondrop="learncalCalendarDragDrop(this)" onclick="learncalDateClick(this);">
                                    <div class="d-flex justify-content-center mb-1">
                                        <span class="calnum rounded-circle scale-text-gray_05"></span>
                                    </div>
                                    <div class="div_study_time mt-1 py-1" hidden>
                                        <span class="study_time cfs-7 text-white"></span>
                                    </div>

                                    <div data-div-calendar-lecture-bundle class="px-3 pt-0">
                                    </div>
                                    <div data-img-calendar-lecture-compt class="w-center pt-3" hidden>
                                        <img src="{{ asset('images/complete_icon.svg?1') }}" width="72">
                                    </div>

                                    <div class="cal_month hidden"></div>
                                </td>
                                <td class="text-center day5" ondragenter="learncalCalendarDragEnter(this);" ondragleave="learncalCalendarDragLeave(this);" ondragover="learncalCalendarDragOver(this);" ondrop="learncalCalendarDragDrop(this)" onclick="learncalDateClick(this);">
                                    <div class="d-flex justify-content-center mb-1">
                                        <span class="calnum rounded-circle scale-text-gray_05"></span>
                                    </div>
                                    <div class="div_study_time mt-1 py-1" hidden>
                                        <span class="study_time cfs-7 text-white"></span>
                                    </div>

                                    <div data-div-calendar-lecture-bundle class="px-3 pt-0">
                                    </div>
                                    <div data-img-calendar-lecture-compt class="w-center pt-3" hidden>
                                        <img src="{{ asset('images/complete_icon.svg?1') }}" width="72">
                                    </div>

                                    <div class="cal_month hidden"></div>
                                </td>
                                <td class="text-center day6" ondragenter="learncalCalendarDragEnter(this);" ondragleave="learncalCalendarDragLeave(this);" ondragover="learncalCalendarDragOver(this);" ondrop="learncalCalendarDragDrop(this)" onclick="learncalDateClick(this);">
                                    <div class="d-flex justify-content-center mb-1">
                                        <span class="calnum rounded-circle scale-text-gray_05"></span>
                                    </div>
                                    <div class="div_study_time mt-1 py-1" hidden>
                                        <span class="study_time cfs-7 text-white"></span>
                                    </div>

                                    <div data-div-calendar-lecture-bundle class="px-3 pt-0">
                                    </div>
                                    <div data-img-calendar-lecture-compt class="w-center pt-3" hidden>
                                        <img src="{{ asset('images/complete_icon.svg?1') }}" width="72">
                                    </div>

                                    <div class="cal_month hidden"></div>
                                </td>
                                <td class="text-center day7" ondragenter="learncalCalendarDragEnter(this);" ondragleave="learncalCalendarDragLeave(this);" ondragover="learncalCalendarDragOver(this);" ondrop="learncalCalendarDragDrop(this)" onclick="learncalDateClick(this);">
                                    <div class="d-flex justify-content-center mb-1">
                                        <span class="calnum rounded-circle scale-text-gray_05"></span>
                                    </div>
                                    <div class="div_study_time mt-1 py-1" hidden>
                                        <span class="study_time cfs-7 text-white"></span>
                                    </div>

                                    <div data-div-calendar-lecture-bundle class="px-3 pt-0">
                                    </div>
                                    <div data-img-calendar-lecture-compt class="w-center pt-3" hidden>
                                        <img src="{{ asset('images/complete_icon.svg?1') }}" width="72">
                                    </div>

                                    <div class="cal_month hidden"></div>
                                </td>
                                </tr>
                                @endfor
                        </tbody>
                    </table>
                    <!-- </div> -->
                    <article data-article-learncal-student-lecture-detail hidden class="position-absolute modal-shadow-style rounded-4 bg-white p-4" style="width:420px;">
                        <div class="row mx-0">
                            <div class="col h-center">
                                <img src="{{ asset('images/class_icon.svg') }}" width="24">
                                <span class="text-sb-20px"></span>
                                <span class="text-sb-20px">학생의 학습 내역</span>
                            </div>
                            <div class="col-auto">
                                <button class="btn p-0 h-center" onclick="learncalPopStudentLectureDetailClose(this)">
                                    <img src="{{ asset('images/black_x_icon.svg') }}" width="24">
                                </button>
                            </div>
                        </div>
                        <div data-div-learncal-student-lecture-detail-bundle class="mt-4 pt-2 d-flex flex-column gap-2">
                            <div data-div-learncal-student-lecture-detail-row="copy" class="rounded-4 row mx-0 scale-bg-gray_01 py-1" hidden>
                                <div class="col row mx-0 align-items-center flex-nowrap">
                                    <img data-img-lecture-icon class="col-auto px-0" src="{{ asset('images/subject_kor_icon.svg') }}" style="width:52px; white-space: nowrap;">
                                    <span data-span-idx class="col-auto px-0 text-m-18px scale-text-gray_05" style="white-space: nowrap;"></span>
                                    <img class="col-auto" src="{{ asset('images/bar_icon.svg') }}" width="2" height="12" style="white-space: nowrap;">
                                    <span data-span-lecture-detail-name class="col-auto px-0 text-m-18px scale-text-gray_05" style="white-space: nowrap;">강의명 영역</span>
                                </div>
                                <div class="col-auto h-center px-0 mx-2">
                                    <span data-span-status class="text-sb-16px text-white rounded-pill" style="padding:6px 12px;"></span>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </div>
        {{-- 필터, 학습시작 시간 설정. --}}
        <div class="col-3" id="learncal_div_filter">
            <div class="p-3 ms-2 modal-shadow-style rounded-3">
                {{-- 필터, 전체삭제 --}}
                <div class="row justify-content-between align-items-center py-3">
                    <span class="col-auto">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mb-1">
                            <path d="M4 7.5H20" stroke="#222222" stroke-width="2.5" stroke-linecap="round" />
                            <circle cx="15.5" cy="7.5" r="2.5" fill="white" stroke="#222222" stroke-width="2" />
                            <path d="M20 16.5H4" stroke="#222222" stroke-width="2.5" stroke-linecap="round" />
                            <circle cx="3.5" cy="3.5" r="2.5" transform="matrix(-1 0 0 1 12 13)" fill="white" stroke="#222222" stroke-width="2" />
                        </svg>
                        <span class="cfs-6 fw-semibold ctext-bc0">필터</span>
                    </span>

                    <a class="col-auto link-secondary text-decoration-none" href="javascript:void(0)" onclick="learncalFilterReset();">
                        <span class="cfs-7 ctext-gc1">전체 삭제</span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" class="mb-1">
                            <path d="M6.25 6.25L13.7498 13.7498" stroke="#999999" stroke-width="2" stroke-linecap="round" />
                            <path d="M13.75 6.25L6.25018 13.7498" stroke="#999999" stroke-width="2" stroke-linecap="round" />
                        </svg>
                    </a>
                </div>
                {{-- 필터 태그란 --}}
                <div class="py-2 px-3">
                    <figure class="row gap-2" id="learncal_filter">
                        {{-- 출석시간, 목표시간, 학습완료 --}}
                        <figcaption class="col-auto rounded-pill bg-goal-time2 cfs-7 curosr-pointer all-center px-3 py-1" id="learncal_f_attend_time" target="learncal_check_attend_time" onclick="learncalFilterRemove(this)">
                            출석예정
                            <img src="{{ asset('images/white_x_icon.svg') }}" width="20">
                        </figcaption>
                        <figcaption class="col-auto rounded-pill bg-attend-time2 cfs-7 curosr-pointer all-center px-3 py-1" id="learncal_f_goal_time" target="learncal_check_time_goal" onclick="learncalFilterRemove(this)">
                            출석완료
                            <img src="{{ asset('images/white_x_icon.svg') }}" width="20">
                        </figcaption>
                        {{-- 지각 --}}
                        <figcaption class="col-auto rounded-pill bg-danger cfs-7 curosr-pointer all-center px-3 py-1 text-white" id="learncal_f_late" target="learncal_check_late" onclick="learncalFilterRemove(this)">
                            지각
                            <img src="{{ asset('images/white_x_icon.svg') }}" width="20">
                        </figcaption>
                        <figcaption class="col-auto rounded-pill bg-complte cfs-7 curosr-pointer all-center px-3 py-1" id="learncal_f_completed" target="learncal_check_lcompleted" onclick="learncalFilterRemove(this)">
                            학습완료
                            <img src="{{ asset('images/white_x_icon.svg') }}" width="20">
                        </figcaption>
                        {{-- 필터 태그 더보기 --}}
                        @if(!empty($subject_codes))
                        @foreach ($subject_codes as $key => $subject_code)
                        <figcaption class="col-auto rounded-pill cfs-7 curosr-pointer all-center px-3 py-1 text-white" style="background:var(--color-subject{{ $key+1 }})" key="{{ $key+1 }}" code_seq="{{ $subject_code->id }}" id="subject_seq_{{ $subject_code->id }}" target="learncal_check_code_{{ $subject_code->id }}" onclick="learncalFilterRemove(this)">
                            {{ $subject_code->code_name }}
                            <img src="{{ asset('images/white_x_icon.svg') }}" width="20">
                        </figcaption>
                        @endforeach
                        @endif

                    </figure>
                </div>
                {{-- 학습시간 --}}
                <div class="py-2 mt-3 border-top-dashed">
                    <div class="row m-0 p-0 justify-content-between">
                        <span class="col-auto fs-5 p-0" style="color:#A1A1A1">학습시간</span>
                        <button class="col-auto btn p-0" onclick="learncalFilterCollapse(this)">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 11.75H7" stroke="#999999" stroke-width="2.5" stroke-linecap="round" />
                            </svg>
                        </button>
                    </div>
                    <div class="div_collapse">
                        {{-- 출석예정 --}}
                        <div class="mt-3 pt-2 h-center gap-1">
                            <input type="checkbox" class="form-check-input gray zoom1 fs-5 inp_filter_tag" id="learncal_check_attend_time" onchange="learncalFilterCheck();" target="learncal_f_attend_time" checked>
                            <label for="learncal_check_attend_time" class="text-sb-20px ms-1 scale-text-gray_06">출석예정</label>
                        </div>
                        {{-- 출석완료 --}}
                        <div class="mt-2 h-center gap-1">
                            <input type="checkbox" class="form-check-input gray zoom1 fs-5 inp_filter_tag" id="learncal_check_time_goal" onchange="learncalFilterCheck()" target="learncal_f_goal_time" checked>
                            <label for="learncal_check_time_goal" class="text-sb-20px ms-1 scale-text-gray_06">출석완료</label>
                        </div>
                        {{-- 지각 --}}
                        <div class="mt-2 h-center gap-1">
                            <input type="checkbox" class="form-check-input gray zoom1 fs-5 inp_filter_tag" id="learncal_check_late" onchange="learncalFilterCheck()" target="learncal_f_late" checked>
                            <label for="learncal_check_late" class="text-sb-20px ms-1 scale-text-gray_06">지각</label>
                        </div>
                    </div>
                </div>
                {{-- 학습상태 --}}
                <div class="py-2 mt-3 border-top-dashed">
                    <div class="row m-0 p-0 justify-content-between">
                        <span class="col-auto fs-5 p-0" style="color:#A1A1A1">학습상태</span>
                        <button class="col-auto btn p-0" onclick="learncalFilterCollapse(this)">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 11.75H7" stroke="#999999" stroke-width="2.5" stroke-linecap="round" />
                            </svg>
                        </button>
                    </div>
                    <div class="div_collapse">
                        <div class="mt-3 pt-2 h-center gap-1">
                            <input type="checkbox" class="form-check-input gray zoom1 fs-5 inp_filter_tag" id="learncal_check_lcompleted" onchange="learncalFilterCheck();" target="learncal_f_completed" checked>
                            <label for="learncal_check_lcompleted" class="text-sb-20px ms-1 scale-text-gray_06">학습완료</label>
                        </div>
                    </div>
                </div>
                {{-- 학습과목 --}}
                <div class="py-2 mt-3 border-top-dashed">
                    <div class="row m-0 p-0 justify-content-between">
                        <span class="col-auto fs-5 p-0" style="color:#A1A1A1">학습시간</span>
                        <button class="col-auto btn p-0" onclick="learncalFilterCollapse(this)">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17 11.75H7" stroke="#999999" stroke-width="2.5" stroke-linecap="round" />
                            </svg>
                        </button>
                    </div>
                    <div class="div_collapse">
                        @if(!empty($subject_codes))
                        @foreach ($subject_codes as $key => $subject_code)
                        <div class="mt-{{ $key === 0 ? '3' : '0' }} pt-2 h-center gap-1">
                            <input type="checkbox" class="form-check-input gray zoom1 fs-5 inp_filter_tag" id="learncal_check_code_{{ $subject_code->id }}" onclick="learncalFilterCheck();" code_seq="{{ $subject_code->id }}" target="subject_seq_{{ $subject_code->id }}" checked>
                            <label for="learncal_check_code_{{ $subject_code->id }}" class="text-sb-20px ms-1 scale-text-gray_06">
                                {{ $subject_code->code_name }}
                            </label>
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="row m-0 mt-4 ps-2">
                <button class="btn btn-lg btn-primary-y py-3 text-sb-24px" onclick="learncalModalOpen()">
                    {{ $login_type == 'teacher' ? '학습 시작 시간 설정':'학습 목표 세우기' }}
                </button>
                {{-- 선생님만 학습플래너 수정. --}}
                @if($login_type == 'teacher'||$login_type == 'parent')
                <button class="btn btn-lg btn-outline-primary-y mt-2 py-3 text-sb-24px" onclick="learncalPlannerTransition()">학습 플래너 수정</button>
                @endif
            </div>
        </div>
        {{-- 학습플래너 수정, STEP1, STEP2, STEP3 --}}
        <div class="col-3" id="learncal_div_planner" hidden>
            <div class="p-3 rounded-3 modal-shadow-style ms-2">
                <div class="px-2">
                    <div class="py-3 my-2 cursor-pointer" data-div-learncal-aside-tab='step1' onclick="learncalAsideStepClick(this)">
                        <span class="bg-primary-y text-white rounded-pill cfs-7 px-3 cpy-1 ">STEP 1</span>
                        <span class="cfs-6 ctext-bc0 fw-semibold ms-2">추천 기본 시간표</span>
                    </div>
                    <div class="border-top-dashed mt-3" data-div-learncal-aside-tab-sub="step1" hidden>
                        {{-- 학년 숨기기. --}}
                        {{-- <nav class="row">
                            @if(!empty($grade_codes))
                                @foreach ($grade_codes as $grade_code)
                                    <a class="col-auto nav-link"
                                    href="javascript:void(0)" onclick="learncalPlannerStep1Grade(this)"
                                    grade_seq="{{ $grade_code->id }}">
                        {{ $grade_code->code_name }}
                        </a>
                        @endforeach
                        @endif
                        </nav> --}}
                        <nav class="row fw-semibold ctext-bc0 cfs-6 gap-2 m-0 py-4">
                            @if(!empty($timetable_groups))
                            @foreach ($timetable_groups as $timetable_group)
                            <a class="col-auto nav-link a_planner_timetable_group active ctext-bc0" href="javascript:void(0)" onclick="learncalPlannerSampleTimeGroupClick(this)" timetable_group_seq="{{ $timetable_group->id }}">
                                {{ $timetable_group->timetable_group_title }}
                            </a>
                            @endforeach
                            @endif
                        </nav>
                        <article class="div_timetable_bunlde">
                            <section class="copy_div_timetable py-3" hidden>
                                <div class="d-flex gap-2">
                                    <div class="col-auto h-center">
                                        <label class="radio">
                                            <input type="radio" class="" name="lecture-step1-in-radio">
                                            <span class=""></span>
                                        </label>
                                    </div>
                                    <div class="col-auto">
                                        <img class="timetable_icon" src="" width="72">
                                    </div>
                                    <div class="cfs-6 col-auto">
                                        <div class="ctext-bc0">
                                            <span class="lecture_name text-sb-20px" exp="만점왕 국어 3-2"></span>
                                        </div>
                                        <div class="ctext-gc1">
                                            <span class="teacher_name text-sb-20px scale-text-gray_05" exp="김팝콘"></span>
                                            <span class="text-sb-20px scale-text-gray_05"> 선생님</span>
                                        </div>
                                    </div>
                                    <div class="col text-end" onclick="learncalPlannerStep1TimetableDetail(this)">
                                        <button class="btn p-0 d-inline-flex">
                                            <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32"">
                                        </button>
                                    </div>

                                    <input type="hidden" class="timetable_seq">
                                            <input type="hidden" class="lecture_seq">
                                            <input type="hidden" class="start_lecture_detail_seq">
                                    </div>
                                    <div class="timetable_detail mt-4" hidden>
                                        <figure class="d-flex cfs-7 p-3 mt-3">
                                            <span class="col-4 ctext-gc1 py-2">강좌수준</span>
                                            <span class="col ctext-bc0 py-2 level_names"></span>
                                        </figure>
                                        <figure class="d-flex cfs-7 p-3 mt-2">
                                            <span class="col-4 ctext-gc1 py-2">학습 시작일</span>
                                            <span class="col ctext-bc0 py-2 timetable_start_date"></span>
                                        </figure>
                                        <figure class="d-flex cfs-7 p-3 mt-2">
                                            <span class="col-4 ctext-gc1 py-2">시작강의</span>
                                            <span class="col ctext-bc0 py-2">
                                                <span class="idx"></span>강.
                                                <span class="lecture_detail_name"></span>
                                            </span>
                                        </figure>
                                        <figure class="d-flex cfs-7 p-3 mt-2">
                                            <span class="col-4 ctext-gc1 py-2">학습 요일</span>
                                            <span class="col ctext-bc0 py-2 timetable_days"></span>
                                        </figure>
                                    </div>
                            </section>
                        </article>
                        <button class="btn btn-lg btn-primary-y py-3 w-100" onclick="learncalStep1AddTimetableClick();">시간표에 추가하기</button>
                    </div>
                </div>
            </div>
            <div class="p-3 rounded-3 modal-shadow-style ms-2 mt-3">
                <div class="px-2">
                    <div class="py-3 my-2 cursor-pointer" data-div-learncal-aside-tab='step2' onclick="learncalAsideStepClick(this)">
                        <span class="bg-primary-y text-white rounded-pill cfs-7 px-3 cpy-1 ">STEP 2</span>
                        <span class="cfs-6 ctext-bc0 fw-semibold ms-2">원하는 강좌 추가</span>
                    </div>
                    <div class="mt-3" data-div-learncal-aside-tab-sub="step2" hidden>
                        <div>
                            <label class="label-search-wrap w-100">
                                <input type="text" class="ss-search border-gray rounded-pill text-m-16px w-100" placeholder="강의명,과목,선생님 이름을 검색해보세요.">
                            </label>
                        </div>
                        <div class="h-center mx-0 py-4">
                            <div class="col-auto px-0">
                                <span class="scale-text-gray_05 text-sb-20px">강좌분류</span>
                            </div>
                            <div class="col px-0 text-end">
                                <div class="d-inline-block select-wrap select-icon">
                                    <select class="rounded-pill border-0 sm-select text-sb-20px py-0 pe-5" style="outline:none" data-course-code>
                                        <option value="">선택</option>
                                        @if(!empty($course_codes))
                                        @foreach ($course_codes as $course_code)
                                        <option value="{{ $course_code->id }}">{{ $course_code->code_name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        {{-- 과목 --}}
                        <div class="border-top-dashed pt-2">
                            <div class="text-sb-20px scale-text-gray_05 mt-1">과목</div>
                            <div class="row mx-0 py-4 gap-2">
                                @if(!empty($subject_codes))
                                @foreach ( $subject_codes as $subject_code )
                                <div class="h-center gap-2 col-auto gy-2 px-0 pe-1">
                                    <label class="radio">
                                        <input onchange="learncalSubjectCodeClick(this)" type="radio" class="" name="subject-code-radio" data-subject-code-seq="{{ $subject_code->id }}">
                                        <span class=""></span>
                                    </label>
                                    <span class="text-m-20px scale-text-gray_05">{{ $subject_code->code_name }}</span>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                        {{-- 시리즈 --}}
                        <div class="border-top-dashed pt-2">
                            <div class="text-sb-20px scale-text-gray_05 mt-1">시리즈</div>
                            <div class="row mx-0 py-4 gap-2" data-div-learncal-serise-bundle>
                                <div class="h-center gap-2 col-auto gy-2 px-0 pe-1" data-div-learncal-serise-row hidden>
                                    <label class="radio">
                                        <input type="radio" class="" name="series-code-radio" data-series-code-seq="">
                                        <span class=""></span>
                                    </label>
                                    <span class="text-m-20px scale-text-gray_05" data-series-name></span>
                                </div>
                            </div>
                        </div>
                        {{-- 출판사 --}}
                        <div class="border-top-dashed pt-2">
                            <div class="text-sb-20px scale-text-gray_05 mt-1">출판사</div>
                            <div class="row mx-0 py-4 gap-2" data-div-learncal-publisher-bundle>
                                @if(!empty($publisher_codes))
                                @foreach ( $publisher_codes as $publisher_code )
                                <div class="h-center gap-2 col-auto gy-2 px-0 pe-1" data-div-learncal-publisher-row>
                                    <label class="radio">
                                        <input type="radio" class="" name="publisher-code-radio" data-publisher-code-seq="{{ $publisher_code->id }}">
                                        <span class=""></span>
                                    </label>
                                    <span class="text-m-20px scale-text-gray_05" data-publisher-name>{{ $publisher_code->code_name }}</span>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                        <div data-div-learncal-aside-tab-sub-search-btn="">
                            {{-- 80 --}}
                            <div>
                                <div class="py-lg-4"></div>
                                <div class="py-lg-3"></div>
                            </div>
                            {{-- 강좌 검색하기 --}}
                            <button class="btn btn-lg btn-light mt-2 py-3 text-sb-24px scale-text-gray_05 w-100" onclick="learncalSearchCourseClick()">강좌 검색하기</button>
                        </div>
                        {{-- 선택한강좌 : 강좌를 선택했을때 --}}
                        <div data-div-learncal-aside-tab-sub-select-lecture="" class="border-top-dashed pt-2" hidden>
                            <div class="text-sb-20px scale-text-gray_05 mt-1 mb-3 pb-3">선택한 강좌</div>
                            {{-- 선택한 강좌 --}}
                            <div class="div_sel_lecture_bunlde">
                                <div class="copy_div_sel_lectures py-3" hidden>
                                    <div class="d-flex gap-2">
                                        <div class="col-auto h-center">
                                            <label class="radio">
                                                <input type="radio" class="" name="lecture-step2-in-radio">
                                                <span class=""></span>
                                            </label>
                                        </div>
                                        <div class="col-auto">
                                            <img class="lecture_icon" src="" width="72">
                                        </div>
                                        <div class="cfs-6 col-auto">
                                            <div class="ctext-bc0">
                                                <span class="lecture_name text-sb-20px" exp="만점왕 국어 3-2"></span>
                                            </div>
                                            <div class="ctext-gc1">
                                                <span class="teacher_name text-sb-20px scale-text-gray_05" exp="김팝콘"></span>
                                                <span class="text-sb-20px scale-text-gray_05"> 선생님</span>
                                            </div>
                                        </div>
                                        <div class="col text-end">

                                        </div>

                                        <input type="hidden" class="lecture_seq">
                                        <input type="hidden" class="start_lecture_detail_seq">
                                    </div>
                                </div>
                            </div>

                            {{-- 시작강의 선택 --}}
                            <div class="border-top-dashed pt-2">
                                <div class=" mt-1 row mx-0 align-items-center pb-2 mb-1">
                                    <span class="col text-sb-20px scale-text-gray_05 px-0 ">시작강의 선택</span>
                                    <span class="col-auto text-danger text-m-16px px-0 ">*시작강의를 선택해주세요.</span>
                                </div>
                                <div class="d-inline-block select-wrap select-icon mb-4 w-100">
                                    <select data-sel-learncal-step2-start-lecture-detail-seq onchange="learncalPlannerStep2EndDateSet()" class="rounded-pill border-gray lg-select text-sb-20px w-100 py-2" style="height:52px">
                                    </select>
                                </div>
                            </div>
                            {{-- 요일선택 --}}
                            <div class="border-top-dashed pt-2">
                                <div class="text-sb-20px scale-text-gray_05 mt-1 mb-4">요일 선택</div>
                                <div class="row mx-0 gap-2">
                                    <button onclick="learncalPlannerStep2SelectDay(this)" data-btn-learncal-day="sun" class="col-auto btn_day btn p-0 all-center btn-outline-primary-y border text-m-20px scale-text-white-hover scale-text-gray_05 rounded-circle"> 일 </button>
                                    <button onclick="learncalPlannerStep2SelectDay(this)" data-btn-learncal-day="mon" class="col-auto btn_day btn p-0 all-center btn-outline-primary-y border text-m-20px scale-text-white-hover scale-text-gray_05 rounded-circle"> 월 </button>
                                    <button onclick="learncalPlannerStep2SelectDay(this)" data-btn-learncal-day="tue" class="col-auto btn_day btn p-0 all-center btn-outline-primary-y border text-m-20px scale-text-white-hover scale-text-gray_05 rounded-circle"> 화 </button>
                                    <button onclick="learncalPlannerStep2SelectDay(this)" data-btn-learncal-day="wed" class="col-auto btn_day btn p-0 all-center btn-outline-primary-y border text-m-20px scale-text-white-hover scale-text-gray_05 rounded-circle"> 수 </button>
                                    <button onclick="learncalPlannerStep2SelectDay(this)" data-btn-learncal-day="thu" class="col-auto btn_day btn p-0 all-center btn-outline-primary-y border text-m-20px scale-text-white-hover scale-text-gray_05 rounded-circle"> 목 </button>
                                    <button onclick="learncalPlannerStep2SelectDay(this)" data-btn-learncal-day="fri" class="col-auto btn_day btn p-0 all-center btn-outline-primary-y border text-m-20px scale-text-white-hover scale-text-gray_05 rounded-circle"> 금 </button>
                                    <button onclick="learncalPlannerStep2SelectDay(this)" data-btn-learncal-day="sat" class="col-auto btn_day btn p-0 all-center btn-outline-primary-y border text-m-20px scale-text-white-hover scale-text-gray_05 rounded-circle"> 토 </button>
                                </div>
                            </div>
                            {{-- 학습시작일 --}}
                            <div class="border-top-dashed pt-2 mt-4">
                                <div class="text-sb-20px scale-text-gray_05 mt-1 mb-4">학습 시작일</div>
                                <div class="row mx-0 align-items-center">
                                    <input type="date" class="col form-control px-2" data-input-learncal-step2-start-date onchange="learncalPlannerStep2EndDateSet()"> ~
                                    <input type="date" class="col form-control px-2" data-input-learncal-step2-end-date disabled>
                                </div>
                            </div>
                            {{-- 학습예상끝일 hidden --}}


                            {{-- 80 --}}
                            <div>
                                <div class="py-lg-4"></div>
                                <div class="py-lg-3"></div>
                            </div>
                            {{-- 시간표에 추가하기 btn --}}
                            <button class="btn btn-lg btn-primary-y py-3 text-sb-24px text-white w-100" onclick="learncalStep2AddTimetableClick()">시간표에 추가하기</button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="p-3 rounded-3 modal-shadow-style ms-2 mt-3">
                <div class="px-2">
                    <div class="py-3 my-2 cursor-pointer" data-div-learncal-aside-tab='step3' onclick="learncalAsideStepClick(this)">
                        <span class="bg-primary-y text-white rounded-pill cfs-7 px-3 cpy-1 ">STEP 3</span>
                        <span class="cfs-6 ctext-bc0 fw-semibold ms-2">미수강/재수강 다시 추가</span>
                    </div>
                    <div class="border-top-dashed mt-3" data-div-learncal-aside-tab-sub="step3" hidden>
                        {{-- stpe3 tab --}}
                        <nav class="row fw-semibold ctext-bc0 cfs-6 gap-2 m-0 py-4">
                            <a data-a-learncal-step3-tab="nodo" class="col-auto scale-text-gray_05 studyColor-text-common-hover nav-link active" href="javascript:void(0)" onclick="learncalPlannerStep3TabClick(this)">
                                안했어요
                            </a>
                            <a data-a-learncal-step3-tab="redo" class="col-auto scale-text-gray_05 studyColor-text-common-hover nav-link" href="javascript:void(0)" onclick="learncalPlannerStep3TabClick(this)">
                                다시할래요
                            </a>
                        </nav>
                        {{-- 과목 --}}
                        <div class="pt-2">
                            <div class="text-sb-20px scale-text-gray_05 mt-1">과목</div>
                            <div class="row mx-0 py-4 gap-2">
                                @if(!empty($subject_codes))
                                @foreach ( $subject_codes as $subject_code )
                                <div class="h-center gap-2 col-auto gy-2 px-0 pe-1">
                                    <label class="radio">
                                        <input onchange="learncalPlannerStep3SubjectClick(this);" type="radio" class="" name="subject-code-radio" data-subject-code-seq="{{ $subject_code->id }}">
                                        <span class=""></span>
                                    </label>
                                    <span class="text-m-20px scale-text-gray_05">{{ $subject_code->code_name }}</span>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                        {{-- 강의 목록 --}}
                        <div class="border-top-dashed pt-2 mt-4">
                            <div class="text-sb-20px scale-text-gray_05 mt-1 mb-4">강의 목록</div>
                        </div>
                        <div class="div_step3_sel_lecture_bunlde overflow-auto" style="max-height:400px;">
                            <div class="copy_div_step3_sel_lectures py-3" hidden>
                                <div class="d-flex gap-2">
                                    <div class="col-auto h-center ps-1">
                                        <input type="checkbox" class="form-check-input black zoom1 fs-5 inp_filter_tag" name="lecture-step3-in-radio">
                                    </div>
                                    <div class="col-auto">
                                        <img class="lecture_icon" src="" width="72">
                                    </div>
                                    <div class="cfs-6 col-auto">
                                        <div class="ctext-bc0">
                                            <span class="lecture_name text-sb-20px" exp="만점왕 국어 3-2"></span>
                                        </div>
                                        <div class="ctext-gc1">
                                            <span class="lecture_detail_name text-sb-20px scale-text-gray_05" exp="2강.품사(1)"></span>
                                        </div>
                                    </div>
                                    <div class="col text-end">

                                    </div>

                                    <input type="hidden" class="student_lecture_seq">
                                    <input type="hidden" class="student_lecture_detail_seq">
                                </div>
                            </div>
                        </div>
                        {{-- 학습일 --}}
                        <div class="border-top-dashed pt-2">
                            <div class="text-sb-20px scale-text-gray_05 mt-1 mb-4">학습일</div>
                            <div class="row mx-0 align-items-center">
                                <input type="date" class="col form-control px-2" data-input-learncal-step3-sel-date>
                            </div>
                        </div>
                        {{-- 80 --}}
                        <div>
                            <div class="py-lg-4"></div>
                            <div class="py-lg-3"></div>
                        </div>
                        <div>
                            <button class="btn btn-lg btn-primary-y py-3 text-sb-24px text-white w-100" onclick="learncalStep3AddTimetableClick()">시간표에 추가하기</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 시간표 강좌 목록 --}}
    <div data-div-student-lectures-list hidden>
        {{-- 80 --}}
        <div>
            <div class="py-lg-4"></div>
            <div class="py-lg-3"></div>
        </div>

        <div class="row mx-0 mb-4">
            <div class="col h-center px-0">
                <img src="{{ asset('images/weekly_study_detail_icon.svg') }}" width="32">
                <span class="text-b-24px ps-1">시간표 강좌 목록</span>
            </div>
            <div class="col-auto px-0">
                <span class="text-m-24px text-danger">총 </span>
                <span class="text-m-24px text-danger" data-span-student-lectures-cnt>0</span>
                <span class="text-m-24px text-danger">개</span>
                <span class="text-m-24px ">의 강의가 있습니다.</span>
            </div>
        </div>
        <div>
            <div class="row col-12 rounded-4 modal-shadow-style bg-white mx-0" id="teachmess_div_mes_head">
                <div class="row m-0 py-0 px-3 text-secondary" style="min-height: 80px">
                    <div class="col row m-0 p-0">
                        <div class="col-auto p-2 d-flex align-items-center" onclick="event.stopPropagation();">
                            <input type="checkbox" class="form-check-input col-auto chk" onclick="event.stopPropagation();">
                        </div>
                        <div class="col d-flex align-items-center text-b-20px scale-text-gray_05 justify-content-center">강좌명</div>
                        <div class="col d-flex align-items-center text-b-20px scale-text-gray_05 justify-content-center">학습 시작일</div>
                        <div class="col d-flex align-items-center text-b-20px scale-text-gray_05 justify-content-center">시작 강의</div>
                        <div class="col d-flex align-items-center text-b-20px scale-text-gray_05 justify-content-center">학습 요일</div>
                    </div>
                </div>
            </div>
            <div class="row col-12 mx-0" data-div-learncal-timetable-bundle>
                <div data-div-learncal-timetable-row="copy" class="row copy_mes_list m-0 py-0 px-3 border-top cursor-pointer text-secondary" style="min-height: 80px;" hidden>
                    <input type="hidden" data-lecutre-seq>
                    <input type="hidden" data-end-date>
                    <input type="hidden" data-start-date>
                    <div class="col row m-0 p-0">
                        <div class="col-auto p-2 d-flex align-items-center" onclick="event.stopPropagation();">
                            <span class="text-danger text-b-20px cursor-pointer" onclick="learncalPlannerDelete(this)">ㅡ</span>
                        </div>
                        <div data-lecutre-name class="col d-flex align-items-center text-b-20px scale-text-gray_05 justify-content-center">만점왕 국어 3-2 (2023)</div>
                        <div class="col d-flex align-items-center text-b-20px scale-text-gray_05 justify-content-center">
                            <span class="h-center gap-1">
                                <img src="{{ asset('images/calendar_gray_icon.svg') }}" width="24">
                                <span data-start-date-str class="text-m-20px scale-text-gray_05">2023.07.23</span>
                            </span>
                        </div>
                        <div class="col d-flex align-items-center text-b-20px scale-text-gray_05 justify-content-center">
                            <div class="d-inline-block select-wrap select-icon">
                                <select class="rounded-pill border-0 sm-select text-sb-20px py-0 pe-5" style="outline:none" data-start-lecture-detail="">
                                </select>
                            </div>
                        </div>
                        <div class="col d-flex align-items-center text-b-20px scale-text-gray_05 justify-content-center gap-1">
                            <button data-days="sun" onclick="learncalPlannerTimeTableDayClick(this)" class="col-auto btn_day_pill btn p-0 all-center btn-outline-primary-y border text-m-16px scale-text-white-hover scale-text-gray_05 rounded-pill"> 일 </button>
                            <button data-days="mon" onclick="learncalPlannerTimeTableDayClick(this)" class="col-auto btn_day_pill btn p-0 all-center btn-outline-primary-y border text-m-16px scale-text-white-hover scale-text-gray_05 rounded-pill"> 월 </button>
                            <button data-days="tue" onclick="learncalPlannerTimeTableDayClick(this)" class="col-auto btn_day_pill btn p-0 all-center btn-outline-primary-y border text-m-16px scale-text-white-hover scale-text-gray_05 rounded-pill"> 화 </button>
                            <button data-days="wed" onclick="learncalPlannerTimeTableDayClick(this)" class="col-auto btn_day_pill btn p-0 all-center btn-outline-primary-y border text-m-16px scale-text-white-hover scale-text-gray_05 rounded-pill"> 수 </button>
                            <button data-days="thu" onclick="learncalPlannerTimeTableDayClick(this)" class="col-auto btn_day_pill btn p-0 all-center btn-outline-primary-y border text-m-16px scale-text-white-hover scale-text-gray_05 rounded-pill"> 목 </button>
                            <button data-days="fri" onclick="learncalPlannerTimeTableDayClick(this)" class="col-auto btn_day_pill btn p-0 all-center btn-outline-primary-y border text-m-16px scale-text-white-hover scale-text-gray_05 rounded-pill"> 금 </button>
                            <button data-days="sat" onclick="learncalPlannerTimeTableDayClick(this)" class="col-auto btn_day_pill btn p-0 all-center btn-outline-primary-y border text-m-16px scale-text-white-hover scale-text-gray_05 rounded-pill"> 토 </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 52 --}}
        <div class="pt-5 mt-1 w-center gap-1">
            {{-- btn m-24 초기화하기 --}}
            <button class="btn btn-lg btn-outline-light py-3 text-sb-24px border scale-text-gray_05 rounded-pill" style="width:166px" onclick="learncalPlannerReset()">초기화하기</button>
            {{-- btn m-24 저장하기 --}}
            <button class="btn btn-lg btn-primary-y py-3 text-sb-24px rounded-pill ms-2" style="width:166px;" onclick="learncalPlannerSave()">저장하기</button>
        </div>
    </div>
    <!-- <input id="date-range53" class="d-none" size="30" type="" value="">
    <div id="date-range12-container"></div> -->
    <div id="learncal_div_main2">
        <div class="modal fade" id="learncal_modal_calendar" tabindex="-1" aria-hidden="true" style="display: none;">
            <div class="modal-dialog  modal-dialog-centered modal-680">
                <div class="modal-content">
                    <div class="modal-header border-bottom-0 p-4">
                        <h1 class="modal-title cfs-5 fw-semibold h-center gap-1">
                            <img src="{{ asset('images/fpencil_icon.svg')}}" alt="">
                            학습시작 시간 정하기
                        </h1>
                        <div>
                            <a class="link-secondary text-decoration-none ctext-gc1-imp align-middle me-3" href="javascript:void(0)" onclick="learncalModalDateReset();">초기화하기</a>
                            <button type="button" class="btn btn-light btn-white border ctext-gc1 fs-6 py-1 btn_calendar_type" onclick="learncalModalToggleBtn(this)" data="week">달력으로보기</button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="week_cnt">
                        <div class="row justify-content-between mb-4 px-3" id="learncal_div_modal_select_date">
                            <button class="arrowbtn btn btn-sm col-auto" onclick="click_NextMon('#learncal_div_main2','month', 'prev'); learncalModalStudyTimeDisplay();" style="text-align: right;">
                                <img src="{{ asset('images/calendar_arrow_left.svg')}}" alt="">
                            </button>
                            <div class="d-flex col cfs-6 fw-medium align-items-center justify-content-center">
                                <span class="stp2_year">{{ date('Y') }}</span>
                                <span class="pe-2">년</span>
                                <span class="stp2_month">{{ date('m') }}</span>
                                <span class="pe-2">월</span>
                                <span id="learncal_sp_week_cnt" class=""></span>
                                <span class="pe-2"></span>
                            </div>
                            <button class="arrowbtn btn btn-sm col-auto" onclick="click_NextMon('#learncal_div_main2','month', 'next'); learncalModalStudyTimeDisplay();" style="text-align: left;">
                                <img src="{{ asset('images/calendar_arrow_right.svg')}}" alt="">
                            </button>
                        </div>
                        <div id="divStep2" class="divStep2_default px-3">
                            <input type="hidden" class="search_end_date">
                            <!-- <div style="padding:0 20px;"> -->
                            <table class="table tb_calendar align-middle cfs-7" style="margin-bottom: 0px;">
                                <colgroup>
                                    <col width="14%">
                                    <col width="14%">
                                    <col width="14%">
                                    <col width="14%">
                                    <col width="14%">
                                    <col width="14%">
                                    <col width="14%">
                                </colgroup>
                                <thead class="text-center">
                                    <td class="border-0 text-danger" style="width:100px">일</td>
                                    <td class="border-0" style="width:80px"><span class="ctext-gc1">월</span></td>
                                    <td class="border-0" style="width:100px"><span class="ctext-gc1">화</span></td>
                                    <td class="border-0" style="width:85px"><span class="ctext-gc1">수</span></td>
                                    <td class="border-0" style="width:95px"><span class="ctext-gc1">목</span></td>
                                    <td class="border-0" style="width:115px"><span class="ctext-gc1">금</span></td>
                                    <td class="border-0" style="width:100px"><span class="ctext-gc1">토</span></td>
                                </thead>
                                <tbody class="tby_cal">
                                    @for ($i = 1; $i < 7; $i++) <tr class="caltr_{{ $i }}" weeks="{{ $i }}">
                                        <td class="text-center day1 border-0" style="cursor: pointer;" onclick=" learncalModalDateClick(this);">
                                            <div class="d-flex justify-content-center">
                                                <span class="calnum text-danger fw-medium rounded-circle"></span>
                                            </div>
                                        </td>
                                        <td class="text-center day2 border-0" style="cursor: pointer;" onclick=" learncalModalDateClick(this);">
                                            <div class="d-flex justify-content-center">
                                                <span class="calnum ctext-gc1 fw-medium rounded-circle"></span>
                                            </div>
                                        </td>
                                        <td class="text-center day3 border-0" style="cursor: pointer;" onclick=" learncalModalDateClick(this);">
                                            <div class="d-flex justify-content-center">
                                                <span class="calnum ctext-gc1 fw-medium rounded-circle"></span>
                                            </div>
                                        </td>
                                        <td class="text-center day4 border-0" style="cursor: pointer;" onclick=" learncalModalDateClick(this);">
                                            <div class="d-flex justify-content-center">
                                                <span class="calnum ctext-gc1 fw-medium rounded-circle"></span>
                                            </div>
                                        </td>
                                        <td class="text-center day5 border-0" style="cursor: pointer;" onclick=" learncalModalDateClick(this);">
                                            <div class="d-flex justify-content-center">
                                                <span class="calnum ctext-gc1 fw-medium rounded-circle"></span>
                                            </div>
                                        </td>
                                        <td class="text-center day6 border-0" style="cursor: pointer;" onclick=" learncalModalDateClick(this);">
                                            <div class="d-flex justify-content-center">
                                                <span class="calnum ctext-gc1 fw-medium rounded-circle"></span>
                                            </div>
                                        </td>
                                        <td class="text-center day7 border-0" style="cursor: pointer;" onclick=" learncalModalDateClick(this);">
                                            <div class="d-flex justify-content-center">
                                                <span class="calnum ctext-gc1 fw-medium rounded-circle"></span>
                                            </div>
                                        </td>
                                        </tr>
                                        @endfor
                                </tbody>
                            </table>
                            <!-- </div> -->

                        </div>

                    </div>

                    {{-- 하단 반복요일 --}}
                    <div class="row p-2 gap-2 m-0 border-top mx-4 mt-4 pt-4" id="learncal_div_modal_study_time">
                        <div class="col-auto p-0 d-flex align-items-center" style="min-width:90px;">
                            <label class="cfs-6 ctext-gc1">반복 일정</label>
                        </div>
                        {{-- 월화수목금토일 --}}
                        {{-- 원형 + border  --}}
                        <div class="col">
                            <div class="row row-cols-3 gap-2 mx-0 div_study_time_bundle">
                                <button class="copy_btn_study_time btn btn-outline-primary-y border ctext-gc1 col-auto px-3 cfs-7" onclick="learncalModalStudyTimeDelete(this);" data="#MM월dd일 오전 HH:mm" hidden>
                                    <div class="d-flex align-items-center gap-2">
                                        <span></span>
                                        <img src="{{ asset('images/gray_x_icon.svg')}}" class="img_gray">
                                        <img src="{{ asset('images/white_x_icon.svg')}}" class="img_white" style="display: none;">
                                    </div>
                                    <input type="hidden" class="study_time_seq">
                                </button>
                            </div>
                        </div>
                    </div>
                    {{-- 하단 반복 일정 --}}
                    <div class="row p-2 gap-2 m-0 border-top mx-4 mt-4 pt-4" id="learncal_div_modal_sel_time" hidden>
                        <div class="col-auto p-0">
                            <label class="cfs-6 ctext-gc1">선택한 일정</label>
                        </div>
                        {{-- 월화수목금토일 --}}
                        {{-- 원형 + border  --}}
                        <div class="col">
                            <div class="row row-cols-3 gap-2 mx-0 div_sel_time_bundle">
                                <button class="copy_btn_sel_time btn btn-outline-primary-y border ctext-gc1 col-auto px-3 cfs-7" data="#MM월dd일 오전 HH:mm" hidden></button>
                            </div>
                        </div>
                    </div>

                    {{-- 하단 시간 선택 --}}
                    <div class="row align-items-center  gap-2 p-2 mx-4">
                        <div class="pt-0"></div>
                        <label class="col-auto cfs-6 ctext-gc1 px-0" id="learncal_label_modal_time">시간 선택</label>
                        {{-- span*2 / 현재 시:분 10분 단위 --}}
                        {{-- AM / PM --}}
                        <div class="btn-group btn-group-toggle col-auto d-flex align-items-center" data-bs-toggle="buttons">
                            <input type="radio" class="btn-check" id="learncal_radio_time_am" name="learncal_radio_time" autocomplete="off">
                            <label class="btn btn-outline-primary-y px-3 cfs-7 ctext-gc1 border rounded-start-3" style="padding:6px" for="learncal_radio_time_am">오전</label>

                            <input type="radio" class="btn-check " id="learncal_radio_time_pm" name="learncal_radio_time" autocomplete="off">
                            <label class="btn btn-outline-primary-y px-3 cfs-7 ctext-gc1 border rounded-end-3" style="padding:6px" for="learncal_radio_time_pm">오후</label>
                        </div>
                        {{-- 시간 --}}
                        <div class="border col-auto rounded row px-1 m-0 h-center position-relative" style="padding:1.5px;">
                            <button class="btn btn-sm col-auto px-0 h-center" onclick="learncalModalTimeChange(this, 'prev')">
                                <img src="{{ asset('images/calendar_arrow_left.svg')}}" width="24">
                            </button>
                            <span class="col-auto text-center pt-1 cursor-pointer" id="learncal_span_time" onclick="learncalModalTimeClick(this)"></span>
                            <!-- <span id="learncal_span_time" hidden></span>
                            <input type="time" name="" id="" class="border-0 col-12" step="60" value="12:00">
                            <input type="number" name="" id="learncal_inp_hour" max="12" min="0">
                            <input type="number" name="" id="learncal_inp_minute" max="59" min="0"> -->
                            <input type="time" id="learncal_inp_time" class="border-0 col-12" style="height:0px;background:black;" value="00:00" onchange="learncalModalInpTimeChange(this)" hidden>
                            <button class="btn btn-sm col-auto px-0 h-center" onclick="learncalModalTimeChange(this, 'next')">
                                <img src="{{ asset('images/calendar_arrow_right.svg')}}" width="24">
                            </button>

                        </div>
                    </div>

                    <div class="row" id="learncal_div_modal_repeat">
                        <div class="col text-center mt-5 pt-4 pb-3">
                            <input type="checkbox" id="learncal_check_repeat" class="form-check-input gray" style="zoom: 1.18;">
                            <label for="learncal_check_repeat" class="cfs-6 ctext-gc1">해당 시간 매주 반복하시겠습니까?</label>
                        </div>
                    </div>
                    <div class="modal-footer border-0">

                        <button class="btn btn-light btn-lg col py-3 ctext-gc1 btn_close" data-bs-dismiss="modal" aria-label="Close" onclick="">
                            <span class="ps-2 fs-4">닫기</span>
                        </button>
                        <button class="btn btn-primary-y btn-lg col py-3" onclick="learncalModalStudyTimesSave();">
                            <span class="ps-2 fs-4 text-white">저장하기</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 모달 / 강좌 선택하기 --}}
    <div class="modal fade" id="learncal_modal_course_search" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog  modal-dialog-centered" style="max-width:402px">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h1 class="modal-title text-b-24px h-center">
                        <img src="{{ asset('images/book2_icon.svg') }}" width="32">
                        강좌 선택하기
                    </h1>
                    <button type="button" class="btn p-0 d-inline-flex" data-bs-dismiss="modal" aria-label="Close" onclick="">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 10L10.0003 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round"></path>
                            <path d="M10 10L21.9997 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round"></path>
                        </svg>
                    </button>
                </div>
                <div class="modal-body border-0">
                    <article class="div_lecture_bunlde">
                        <section class="copy_div_lectures py-3" hidden>
                            <div class="d-flex gap-2">
                                <div class="col-auto h-center">
                                    <label class="radio">
                                        <input type="radio" class="" name="lecture-step2-modal-radio" onchange="learncalPlannerStep2LectureDetail(this)">
                                        <span class=""></span>
                                    </label>
                                </div>
                                <div class="col-auto">
                                    <img class="lecture_icon" src="" width="72">
                                </div>
                                <div class="cfs-6 col-auto">
                                    <div class="ctext-bc0">
                                        <span class="lecture_name text-sb-20px" exp="만점왕 국어 3-2"></span>
                                    </div>
                                    <div class="ctext-gc1">
                                        <span class="teacher_name text-sb-20px scale-text-gray_05" exp="김팝콘"></span>
                                        <span class="text-sb-20px scale-text-gray_05"> 선생님</span>
                                    </div>
                                </div>
                                <div class="col text-end">

                                </div>

                                <input type="hidden" class="lecture_seq">
                                <input type="hidden" class="start_lecture_detail_seq">
                            </div>
                            <div class="lecture_detail mt-4" hidden>
                                <figure class="d-flex scale-bg-gray_01 rounded-3 cfs-7 p-3 mt-3">
                                    <span class="col-4 ctext-gc1 py-2 text-m-20px">강좌수준</span>
                                    <span class="col ctext-bc0 py-2 text-m-20px level_names"></span>
                                </figure>
                                <figure class="d-flex scale-bg-gray_01 rounded-3 Pcfs-7 p-3 mt-2">
                                    <span class="col-4 ctext-gc1 text-m-20px py-2">강의 수</span>
                                    <span class="col-auto ctext-bc0 py-2 text-m-20px lecture_detail_count"></span>
                                    <span class="col ctext-bc0 py-2 text-m-20px">개</span>

                                </figure>
                            </div>
                        </section>
                    </article>
                </div>
                <div class="modal-footer border-0">
                    <button class="btn btn-primary-y btn-lg col py-3" onclick="learncalPlannerStep2LectureConfirm();">
                        <span class="ps-2 text-b-24px text-white">선택완료</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{--  모달 / 일괄삭제 --}}
    <div class="modal fade" tabindex="-1" role="dialog" style="display: none;" id="learncal_modal_match_deletion" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered" style="width:25%;height:auto;z-index: 9999;">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title cfs-5 msg_title">일괄삭제</h1>
                    <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close" >
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 10L10.0003 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round"></path>
                            <path d="M10 10L21.9997 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round"></path>
                        </svg>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <p class="msg_content cfs-6">

                    </p>
                </div>
                <div class="modal-footer align-items-stretch w-100 gap-2 pb-3 border-top-0 row">
                    <button type="button" class="msg_btn1 btn btn-lg btn-primary-y col" onclick="matchModalDelete();">일괄삭제</button>
                    <button type="button" class="msg_btn2 btn btn-lg btn-light ctext-gc1 col" onclick="matchModalCancel();">취소</button></div>
            </div>
        </div>
    </div>

    {{-- padding 160 --}}
    <div>
        <div class="py-lg-5"></div>
        <div class="py-lg-4"></div>
        <div class="pt-lg-3"></div>
    </div>
    <!-- <article data-article-learncal-student-lecture-detail hidden class="position-absolute modal-shadow-style rounded-4 bg-white p-4" style="width:420px;">
        <div class="row mx-0">
            <div class="col h-center">
                <img src="{{ asset('images/class_icon.svg') }}" width="24">
                <span class="text-sb-20px"></span>
                <span class="text-sb-20px">학생의 학습 내역</span>
            </div>
            <div class="col-auto">
                <button class="btn p-0 h-center" onclick="learncalPopStudentLectureDetailClose(this)">
                    <img src="{{ asset('images/black_x_icon.svg') }}" width="24">
                </button>
            </div>
        </div>
        <div data-div-learncal-student-lecture-detail-bundle
        class="mt-4 pt-2 d-flex flex-column gap-2">
            <div data-div-learncal-student-lecture-detail-row="copy"
            class="rounded-4 row mx-0 scale-bg-gray_01 py-1" hidden>
                <div class="col row mx-0 align-items-center">
                    <img data-img-lecture-icon class="col-auto px-0" src="{{ asset('images/subject_kor_icon.svg') }}" style="width:52px">
                    <span data-span-idx class="col-auto px-0 text-m-18px scale-text-gray_05"></span>
                    <img class="col-auto" src="{{ asset('images/bar_icon.svg') }}" width="2" height="12">
                    <span data-span-lecture-detail-name class="col-auto px-0 text-m-18px scale-text-gray_05">강의명 영역</span>
                </div>
                <div class="col-auto h-center">
                    <span data-span-status class="scale-bg-gray_04 text-sb-16px text-white rounded-pill" style="padding:6px 12px;"></span>
                </div>
            </div>
        </div>
    </article> -->
</div>
<script>
    //
    const gl_students = @json($students);
    // 팀 불러오기
    function learncalTeamSelect(vthis) {
        const region_seq = vthis.value;
        const team_tag = document.querySelector("#learncal_sel_team");
        // 팀비우기.
        team_tag.innerHTML = "<option value=''>팀</option>";
        // 공백 체크
        if (region_seq == "") {
            return;
        }
        // 팀 불러오기
        const page = "/manage/useradd/team/select";
        const parameter = {
            region_seq: region_seq
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                // result.teams option 추가
                const teams = result.resultData;
                teams.forEach(function(team) {
                    const option = document.createElement("option");
                    option.value = team.team_code;
                    option.innerText = team.team_name;
                    team_tag.appendChild(option);
                });
            }
        });
    }

    // 상단 회원 검색
    function learncalUserSelect() {
        const region_seq = document.querySelector("#learncal_sel_region").value;
        const team_code = document.querySelector("#learncal_sel_team").value;
        const search_type = document.querySelector("#learncal_sel_search_type").value;
        const search_str = document.querySelector("#learncal_input_search_str").value;

        //region_seq 가 없으면 return
        if (region_seq == "") {
            toast("소속을 선택해주세요.");
            return;
        }

        const page = "/manage/user/lecture/user/select";
        const parameter = {
            region_seq: region_seq,
            team_code: team_code,
            search_type: search_type,
            search_str: search_str
        };
        const learncal_div_user = document.querySelector("#learncal_div_user");
        learncal_div_user.hidden = false;
        const tby_user = document.querySelector("#learncal_tby_user");
        tby_user.querySelector(".copy_tr_user").hidden = false;
        document.querySelector("#learncal_div_user_empty").hidden = true;

        queryFetch(page, parameter, function(result) {
            //초기화
            const tr_user = tby_user.querySelector(".copy_tr_user").cloneNode(true);
            tby_user.innerHTML = "";
            tby_user.appendChild(tr_user);
            tr_user.hidden = true;

            if ((result.resultCode || '') == 'success') {
                result.students.forEach(function(user) {
                    const tr = tr_user.cloneNode(true);
                    tr.classList.remove("copy_tr_user");
                    tr.classList.add("tr_user");
                    tr.hidden = false;
                    //로딩바 제거
                    tr.querySelectorAll(".loding_place").forEach(function(place) {
                        place.remove();
                    });
                    tr.querySelector(".school_name").innerText = user.school_name;
                    tr.querySelector(".grade").innerText = user.grade;
                    tr.querySelector(".student_name").innerText = user.student_name;
                    tr.querySelector(".student_id").innerText = user.student_id;
                    tr.querySelector(".student_phone").innerText = user.student_phone;
                    tr.querySelector(".payment_last_date").innerText = user.payment_last_date || '';
                    tr.querySelector(".parent_name").innerText = user.parent_name;
                    tr.querySelector(".parent_phone").innerText = user.parent_phone;
                    tr.querySelector(".region_name").value = user.region_name;
                    tr.querySelector(".team_name").value = user.team_name;
                    tby_user.appendChild(tr);
                });
            }
            //if tr_user 없으면 회원 목록이 없습니다.
            if (tby_user.querySelectorAll(".tr_user").length == 0) {
                document.querySelector("#learncal_div_user_empty").hidden = false;
            } else {
                document.querySelector("#learncal_div_user_empty").hidden = true;
            }
        });
    }

    // 회원 tr 클릭
    function learncalUserTrClick(vthis) {
        // vthis 에 table-primary 토글
        const is_active = vthis.classList.contains("table-primary");
        const trs = document.querySelectorAll(".tr_user");
        trs.forEach(function(tr) {
            tr.classList.remove("table-primary");
        });
        if (!is_active) {
            vthis.classList.add("table-primary");
            const student_name = vthis.querySelector(".student_name").innerText;
            const msg = student_name + " 학생을 선택하시겠습니까?";
            sAlert('학생선택', msg, 2, function() {
                // 학생선택 후 함수 실행 // [추가 코드]

                // 학생 목록 숨기기
                document.querySelector("#learncal_div_user").hidden = true;
            });
        }
    }

    // inp date 날짜 변경시
    function learncalSelectDate(vthis) {
        const main_div = document.querySelector("#learncal_div_main");
        //stp2_year, stp2_month 변경 = vthis.value
        const year = vthis.value.substr(0, 4);
        const month = vthis.value.substr(5, 2);
        main_div.querySelector(".stp2_year").innerText = year;
        main_div.querySelector(".stp2_month").innerText = month;
        //달력 재설정
        set_Calendar('#learncal_div_main');

        //학생이 선택되어 있을경우 학습플래너 불러오는 함수 추가 // [추가 코드]
    }

    // 모달 - 하단 시간 선택
    function learncalModalTimeChange(vthis, type) {
        const inp_time = document.querySelector("#learncal_inp_time");
        const span_time = document.querySelector("#learncal_span_time");
        //10분단위로 -+ 처리.
        //00분이 되면 시간 -+ 처리.
        //시간은 12시간제로 처리.
        //시간이 1자리면 앞에 0을 붙여줌. 분도 마찬가지
        const time = span_time.innerText;
        const hour = time.substr(0, 2);
        let min = time.substr(3, 2);
        const ampm = time.substr(6, 2);
        let new_hour = hour;
        let new_min = min;
        let new_ampm = ampm;
        //분이 10분단위가 아니면 10분단위로 변경
        if (min % 10 != 0) {
            if (type == "prev") {
                min = (Number(min) - (min % 10)).toString();
            } else {
                min = (Number(min) + (10 - (min % 10)) - 10).toString();
                min = min < 0 ? "00" : min;
            }
        }

        if (type == "prev") {
            if (min == "00" || min == 0) {
                if (hour == "01") {
                    new_hour = "12";
                    new_ampm = ampm == "AM" ? "PM" : "AM";
                } else {
                    new_hour = (Number(hour) - 1).toString();
                }
                new_min = "50";
            } else {
                new_min = (Number(min) - 10).toString();
            }
        } else {
            if (min == "50") {
                if (hour == "12") {
                    new_hour = "01";
                    new_ampm = ampm == "AM" ? "PM" : "AM";
                } else {
                    new_hour = (Number(hour) + 1).toString();
                }
                new_min = "00";
            } else {
                new_min = (Number(min) + 10).toString();
            }
        }
        if (new_hour.length == 1) {
            new_hour = "0" + new_hour;
        }
        if (new_min.length == 1) {
            new_min = "0" + new_min;
        }
        span_time.innerText = new_hour + ":" + new_min; //+ " " + new_ampm;
        inp_time.value = new_hour + ":" + new_min;
    }

    // 모달 - 학습시간 정하기 하단 시간 클릭했을 때
    function learncalModalTimeClick(vthis) {

        const inp_time = document.querySelector("#learncal_inp_time");
        inp_time.hidden = false;

        // learncal_radio_time_am
        // learncal_radio_time_pm 중 체크 되어 있는 것을 판단해서 inp 시간을 span 의 시간을 24시간으로 변환
        // 둘다 체크 해제면 패스
        const radio_am = document.querySelector("#learncal_radio_time_am");
        const radio_pm = document.querySelector("#learncal_radio_time_pm");
        const span_time = document.querySelector("#learncal_span_time");
        const time = span_time.innerText;
        const hour = time.substr(0, 2);
        const min = time.substr(3, 2);
        const is_am = radio_am.checked;
        const is_pm = radio_pm.checked;
        let new_hour = hour;
        if (is_am) {
            if (hour == "12") {
                new_hour = "00";
            }
        } else if (is_pm) {
            if (hour != "12") {
                new_hour = (Number(hour) + 12).toString();
            }
        }
        inp_time.value = new_hour + ":" + min;
        setTimeout(function() {
            inp_time.showPicker();
        }, 200);

    }

    //
    function learncalModalInpTimeChange(vthis) {
        vthis.hidden = true;
        const span_time = document.querySelector("#learncal_span_time");
        const time = vthis.value;
        const hour = time.substr(0, 2);
        let min = time.substr(3, 2);
        const ampm = hour < 12 ? "AM" : "PM";
        let new_hour = hour < 12 ? hour : (Number(hour) - 12).toString();
        //시간, 분이 1자리 수일때 앞에 0을 붙여줌.
        if (new_hour.length == 1) {
            new_hour = "0" + new_hour;
        }
        if (min.length == 1) {
            min = "0" + min;
        }
        span_time.innerText = new_hour + ":" + min;
        if (ampm == "AM") {
            document.querySelector("#learncal_radio_time_am").checked = true;
        } else {
            document.querySelector("#learncal_radio_time_pm").checked = true;
        }
    }


    // 달력 날짜 클릭
    function learncalDateClick(vthis) {
        const tby_cal = vthis.closest(".tby_cal");
        const calnum = vthis.querySelector(".calnum");
        const isActive = calnum.classList.contains("active");

        // calnum active 상태 토글
        vthis.classList.toggle("active");
        calnum.classList.toggle("active", !isActive);
        calnum.classList.toggle("bg-primary-y", !isActive);
        calnum.classList.toggle("text-white", !isActive);

        if (!isActive) {
            // calnum.active를 모두 제거
            tby_cal.querySelectorAll(".calnum").forEach(function(calnum) {
                calnum.closest('td').classList.remove("active");
                calnum.classList.remove("active", "bg-primary-y", "text-white");
            });
            calnum.closest('td').classList.add("active");
            calnum.classList.add("active", "bg-primary-y", "text-white");
        }

        if (calnum.classList.contains('active')) {
            // step2 학습시작일 data-input-learncal-step2-start-date
            const input_start_date = document.querySelector("[data-input-learncal-step2-start-date]");
            input_start_date.value = calnum.getAttribute("date");
            input_start_date.onchange();
            const input_sel_date = document.querySelector("[data-input-learncal-step3-sel-date]");
            input_sel_date.value = calnum.getAttribute("date");
            const lct_row_cnt = calnum.closest('td').querySelectorAll('[data-div-calendar-lecture-row="clone"]').length;
            const cal_imgs = calnum.closest('td').querySelectorAll('[data-img-calendar-lecture-compt].active');
            const cal_bundle = calnum.closest('td').querySelector('[data-div-calendar-lecture-bundle]');

            if (lct_row_cnt > 0) {
                learncalPopStudentLectureDetailOpen(calnum);
                if (cal_imgs.length > 0 && !cal_imgs[0].hidden) {
                    cal_imgs[0].hidden = false;
                    cal_bundle.hidden = true;
                }
            } else {
                learncalPopStudentLectureDetailClose(vthis);
                calnum.classList.add("active", );
                calnum.classList.add("bg-primary-y", );
                calnum.classList.add("text-white", );
            }
            // 현재 활성화된 요소를 업데이트

        } else {
            learncalPopStudentLectureDetailClose(vthis);
            const lct_row_cnt = calnum.closest('td').querySelectorAll('[data-div-calendar-lecture-row="clone"]').length;
            const cal_imgs = calnum.closest('td').querySelectorAll('[data-img-calendar-lecture-compt].active');
            const cal_bundle = calnum.closest('td').querySelector('[data-div-calendar-lecture-bundle]');
            if (cal_imgs.length > 0 && cal_imgs[0].hidden) {
                cal_imgs[0].hidden = false;
                cal_bundle.hidden = true;
            }
            // 현재 활성화된 요소를 null로 설정
            activeCalnum = null;
        }
    }

    // 모달 / 학습시작 시간 정하기 / 달력 날짜 클릭
    function learncalModalDateClick(vthis) {
        const tby_cal = vthis.closest(".tby_cal");
        const calnum = vthis.querySelector(".calnum");
        // calnum active 가 있는지 확인
        if (calnum.classList.contains("active")) {
            calnum.classList.remove("active");
            calnum.classList.remove("bg-primary-y");
            calnum.classList.remove("text-white");
        } else {
            calnum.classList.add("active");
            calnum.classList.add("bg-primary-y");
            calnum.classList.add("text-white");
        }

        // data == week 이면 넘김처리.
        const btn_calendar_type = document.querySelector(".btn_calendar_type");
        if (btn_calendar_type.getAttribute("data") == "week") {
            return;
        }

        //tby_cal 안에 calnum.active 가 있는지 확인
        const is_active = tby_cal.querySelectorAll(".calnum.active").length > 0;

        // 있으면 시간 선택 표시
        if (is_active) {
            // 선택한 일정에 현재 선택한 날짜 추가.
            learncalModalClickDateAdd(vthis);
            // 반복일정 숨김처리.
            const div_study_time = document.querySelector("#learncal_div_modal_study_time");
            div_study_time.hidden = true;
            // 선택한 일정 표시.
            const div_sel_time = document.querySelector("#learncal_div_modal_sel_time");
            div_sel_time.hidden = false;
        } else {
            // 없으면 시간 선택 숨김
            const div_study_time = document.querySelector("#learncal_div_modal_study_time");
            div_study_time.hidden = false;
            // 선택한 일정 숨김
            const div_sel_time = document.querySelector("#learncal_div_modal_sel_time");
            div_sel_time.hidden = true;
        }

    }

    // 모달 / 학습시작 시간 정하기 / 선택한 일정에 현재 선택한 날짜 추가.
    function learncalModalClickDateAdd() {
        const modal = document.querySelector("#learncal_modal_calendar");
        const radio_am = document.querySelector("#learncal_radio_time_am");
        const radio_pm = document.querySelector("#learncal_radio_time_pm");
        const time = document.querySelector("#learncal_span_time").innerText;
        //    const date_str = new Date(select_date + ' 00:00:00').format('MM월dd일');

        // 오전/오후 + 시간
        const time_str = (radio_am.checked ? "오전" : "오후") + " " + time;

        // 선택한 일정에 현재 선택한 날짜 추가.
        // 우선 초기화
        const div_sel_time = modal.querySelector(".div_sel_time_bundle");
        const copy_btn_sel_time = div_sel_time.querySelector(".copy_btn_sel_time");
        div_sel_time.innerHTML = "";
        div_sel_time.appendChild(copy_btn_sel_time);

        //modal 에서 calnum.active 를 찾아서 날짜를 가져옴.
        const tby_cal = modal.querySelector(".tby_cal");
        tby_cal.querySelectorAll(".calnum").forEach(function(calnum) {
            if (calnum.classList.contains("active")) {
                const btn_sel_time = copy_btn_sel_time.cloneNode(true);
                btn_sel_time.hidden = false;
                btn_sel_time.classList.remove("copy_btn_sel_time");
                btn_sel_time.classList.add("btn_sel_time");
                const date_str = new Date(calnum.getAttribute("date") + ' 00:00:00').format('MM월 dd일');
                btn_sel_time.innerText = date_str + " " + time_str;
                div_sel_time.appendChild(btn_sel_time);
            }
        });
    }

    // 모달 / 학습시작 시간 정하기 / 달력 선택 초기화
    function learncalModalDateReset() {
        const tby_cal = document.querySelector("#learncal_div_main2 .tby_cal");
        tby_cal.querySelectorAll(".calnum").forEach(function(calnum) {
            calnum.classList.remove("active");
            calnum.classList.remove("bg-primary-y");
            calnum.classList.remove("text-white");
        });

        //이후 []코드 추가]
        learncalModalClickDateAdd();
        const div_study_time = document.querySelector("#learncal_div_modal_study_time");
        div_study_time.hidden = false;
        // 선택한 일정 숨김
        const div_sel_time = document.querySelector("#learncal_div_modal_sel_time");
        div_sel_time.hidden = true;

    }

    // 필터 하단 접기 / 펼치기
    function learncalFilterCollapse(vthis) {
        const div_collapse = vthis.parentElement.nextElementSibling;
        if (div_collapse.hidden) {
            div_collapse.hidden = false;
        } else {
            div_collapse.hidden = true;
        }
    }

    // 모달 / 학습 시작 시간 설정
    function learncalModalOpen() {
        // 모달 초기화
        learncalModalLeaningTimeClear();
        learncalModalNextWeekShow();
        const myModal = new bootstrap.Modal(document.getElementById('learncal_modal_calendar'), {});
        myModal.show();
    }

    // 모달 / 학습 시작 시간 정하기/ 초기화
    function learncalModalLeaningTimeClear() {
        const modal = document.querySelector("#learncal_modal_calendar");
        // 달력 선택 초기화
        modal.querySelectorAll(".calnum").forEach(function(calnum) {
            calnum.classList.remove("active");
            calnum.classList.remove("bg-primary-y");
            calnum.classList.remove("text-white");
            // 추후 추가.
        });
        // 주, 월 선택 data 초기화
        const btn_calendar_type = modal.querySelector(".btn_calendar_type");
        btn_calendar_type.setAttribute("data", "week");
        btn_calendar_type.innerText = "달력으로보기";

        // 시간 선택 초기화 현재시간으로 초기화 hh:mm으로 넣고 am, pm 체크
        const radio_am = modal.querySelector("#learncal_radio_time_am");
        const radio_pm = modal.querySelector("#learncal_radio_time_pm");
        radio_am.checked = true;
        radio_pm.checked = false;
        const inp_time = modal.querySelector('#learncal_inp_time');
        const span_time = modal.querySelector("#learncal_span_time");
        const currentTime = new Date();
        const minutes = Math.ceil(currentTime.getMinutes() / 10) * 10;
        currentTime.setMinutes(minutes);
        const time = currentTime.format('hh:mm');
        const am_pm = new Date().format('a/p'); // 오전/오후
        span_time.innerText = time;
        if (am_pm == "오전") {
            radio_am.checked = true;
            radio_pm.checked = false;
        } else {
            radio_am.checked = false;
            radio_pm.checked = true;
        }
        inp_time.value = time;
        span_time.innerText = time;

        // 반복 체크 해제
        const check_repeat = modal.querySelector("#learncal_check_repeat");
        check_repeat.checked = false;


    }

    // 모달 / 학습시간 정하기 다음주만 보기
    function learncalModalNextWeekShow() {
        const modal = document.querySelector("#learncal_modal_calendar");
        const next_week_date = new Date().setDate(new Date().getDate() + 7);
        const next_week = new Date(next_week_date);
        const year = next_week.getFullYear();
        const month = next_week.getMonth() + 1;
        const next_week_num = getWeekNo(next_week.format('yyyy-MM-dd'));

        const modal_year = modal.querySelector(".stp2_year");
        const modal_month = modal.querySelector(".stp2_month");
        const label_modal_time = modal.querySelector("#learncal_label_modal_time");
        label_modal_time.innerText = "시간 선택";

        //모달의 년, 월과 다음주의 년, 월이 다르면 모달의 년, 월을 다음주로 변경
        if (modal_year.innerText != year || modal_month.innerText != month) {
            modal_year.innerText = year;
            modal_month.innerText = month;

            // 달력 재설정
            set_Calendar('#learncal_div_main2');
            // 학습시간 표시
            learncalModalStudyTimeDisplay();
        }

        // next_week_num를 제외한 나머지 tr hidden
        const tby_cal = modal.querySelector(".tby_cal");
        tby_cal.querySelectorAll("tr").forEach(function(tr) {
            if (tr.getAttribute("weeks") != next_week_num) {
                tr.hidden = true;
            } else {
                tr.hidden = false;
            }
        });
        // 년월 선택 DIV 숨기기.
        const div_select_date = modal.querySelector("#learncal_div_modal_select_date");
        div_select_date.hidden = true;
        // 반복 DIV 숨기기.
        const div_study_time = modal.querySelector("#learncal_div_modal_study_time");
        div_study_time.hidden = true;
        // 선택한 일정 DIV 숨기기.
        const div_sel_time = modal.querySelector("#learncal_div_modal_sel_time");
        div_sel_time.hidden = true;
        // 반복 DIV 보이기.
        const div_repeat = modal.querySelector("#learncal_div_modal_repeat");
        div_repeat.hidden = false;
    }

    // 모달 / 학습시작 시간 정하기 / 달력으로 보기
    function learncalModalCalendarShow() {
        const modal = document.querySelector("#learncal_modal_calendar");
        const label_modal_time = modal.querySelector("#learncal_label_modal_time");
        label_modal_time.innerText = "변경할 시간";
        // tr hidden 제거
        const tby_cal = modal.querySelector(".tby_cal");
        tby_cal.querySelectorAll("tr").forEach(function(tr) {
            tr.hidden = false;
        });
        //tby_cal 안에 calnum.active 제거
        tby_cal.querySelectorAll(".calnum").forEach(function(calnum) {
            calnum.classList.remove("active");
            calnum.classList.remove("bg-primary-y");
            calnum.classList.remove("text-white");
        });
        // 년월 선택 DIV 보이기.
        const div_select_date = modal.querySelector("#learncal_div_modal_select_date");
        div_select_date.hidden = false;

        // 반복 DIV 숨기기.
        const div_study_time = modal.querySelector("#learncal_div_modal_study_time");
        div_study_time.hidden = false;

        // 선택한 일정 DIV 보이기.
        const div_sel_time = modal.querySelector("#learncal_div_modal_sel_time");
        div_sel_time.hidden = true;

        // 반복 DIV 숨기기.
        const div_repeat = modal.querySelector("#learncal_div_modal_repeat");
        div_repeat.hidden = true;
    }

    // 모달 / 학습시작 시간 정하기 / 달력, 주 토글
    function learncalModalToggleBtn(vthis) {
        const btn = vthis;
        //btn data = week, month
        const data = btn.getAttribute("data");
        if (data != "week") {
            btn.setAttribute("data", "week");
            btn.innerText = "달력으로보기";
            learncalModalNextWeekShow();
        } else {
            btn.setAttribute("data", "month");
            btn.innerText = "요일로 보기";
            learncalModalCalendarShow();
        }
    }

    // 모달 / 학습시작 시간 정하기 / 학습시작 시간 저장하기
    function learncalModalStudyTimesSave() {
        const modal = document.querySelector("#learncal_modal_calendar");

        // 학생 seq 가져오기.
        const student_seqs = gl_students.map(function(student) {
            return student.id;
        }).join(',');
        const student_names = gl_students.map(function(student) {
            return student.student_name;
        }).join(', ');

        // 선택 날짜 가져오기.(modal 안에 .calnum.active 의 attr date 가져오기)
        const selected_dates = [];
        modal.querySelectorAll(".calnum.active").forEach(function(calnum) {
            selected_dates.push(calnum.getAttribute("date"));
        });
        const select_dates = selected_dates.join(',');

        // 선택 시간 가져오기.
        // 오전, 오후 체크 확인 체크가 모두 안되어 있으면 리턴
        // 오후 체크 되어 있으면 시간에 12시간을 더해줌.
        const radio_am = document.querySelector("#learncal_radio_time_am");
        const radio_pm = document.querySelector("#learncal_radio_time_pm");
        if (!radio_am.checked && !radio_pm.checked) {
            toast("오전 오후 시간을 선택해주세요.");
            return;
        }
        const time = document.querySelector("#learncal_span_time").innerText;
        const select_time = (radio_pm.checked ? (Number(time.substr(0, 2)) + 12).toString() : time.substr(0, 2)) + ":" + time.substr(3, 2);

        // 반복 체크 모달의 캘린더 타입이 주단위이면 여부 가져오기.
        const calendar_type = modal.querySelector('.btn_calendar_type').getAttribute('date');
        let is_repeat = 'N';
        if (calendar_type == 'week') {
            is_repeat = modal.querySelector("#learncal_check_repeat").checked ? 'Y' : 'N';
        }
        // 날짜 없을시 리턴.
        if(select_dates == ""){
            toast('요일을 선택해주세요.');
            return;
        }

        // 전송
        const page = "/manage/learning/study/time/insert";
        const parameter = {
            student_seqs: student_seqs,
            select_dates: select_dates,
            select_time: select_time,
            is_repeat: is_repeat
        };

        const login_type = document.querySelector("#inp_login_type").value;
        let msg_str = '선택한 학생들의 학습시작 시간을 저장하시겠습니까?<br><span class="ctext-gc1">[' + student_names + ']</span>';
        if(login_type == 'student'){
            msg_str = '선택한 학습시작 시간을 저장하시겠습니까?';
        }
        sAlert('학습시작 시간 저장', msg_str, 2, function() {
            queryFetch(page, parameter, function(result) {
                if (result.resultCode == "success") {
                    sAlert('', "학습시작 시간이 저장되었습니다.");
                    learncalMainCalendarStudyTimeDisplay();
                    learncalModalStudyTimeDisplay();
                    // 모달 닫기
                    modal.querySelector(".btn_close").click();
                } else {
                    toast('다시 시도해주세요.');
                }
            });
        });
    }

    // 메인 달력 학습시간 표기
    function learncalMainCalendarStudyTimeDisplay() {
        //필터 부분이 아니면 리턴
        if (document.querySelector('#learncal_div_filter').hidden) {
            return;
        }
        // 학생이 선택이 되어 있지 않으면 리턴
        if (gl_students.length < 1) {
            return;
        }
        const main_div = document.querySelector("#learncal_div_main");
        const search_start_date = main_div.querySelector(".calnum").getAttribute('date');
        const search_end_date = main_div.querySelector(".search_end_date").value;
        // 우선은 복수형태로 했지만, 단수로 전송 진행.
        const student_seqs = gl_students[0].id;

        // 전송
        const page = "/manage/learning/study/time/select";
        const parameter = {
            student_seqs: student_seqs,
            search_start_date: search_start_date,
            search_end_date: search_end_date
        };
        queryFetch(page, parameter, function(result) {
            // console.log(result)
            if (result.resultCode == "success") {
                // 학습시간 표기
                const study_times = result.study_times;

                // 출석예정 필터
                const is_show_chk_atend_time = document.querySelector('#learncal_check_attend_time').checked;

                study_times.forEach(function(study_time) {
                    const tagt_td = main_div.querySelectorAll('.calnum[date="' + study_time.select_date + '"]')[0].closest('td');
                    const div_study_time = tagt_td.querySelector('.div_study_time');

                    div_study_time.classList.add('active');
                    tagt_td.querySelector('.study_time').innerText = (study_time.select_time || '').substr(0, 5);
                    // 출석을 했으면 배경색을 바꿔줌.
                    // [추가 코드]
                    // 지각, 출석 필요.

                    // div_study_time.classList.add('bg-attend-time1');
                    div_study_time.classList.add('bg-goal-time1');
                    if (!is_show_chk_atend_time) {
                        div_study_time.hidden = true;
                        return;
                    }
                    div_study_time.hidden = false;
                });
            }
        });
    }
    // 모달 / 작은 달력 / 학습시간 표기
    function learncalModalStudyTimeDisplay() {
        // 학생이 선택이 되어 있지 않으면 리턴
        if (gl_students.length < 1) {
            return;
        }
        const main_div = document.querySelector("#learncal_div_main2");
        const search_start_date = main_div.querySelector(".calnum").getAttribute('date');
        const search_end_date = main_div.querySelector(".search_end_date").value;

        // 우선은 복수형태로 했지만, 단수로 전송 진행.
        const student_seqs = gl_students[0].id;

        // 전송
        const page = "/manage/learning/study/time/select";
        const parameter = {
            student_seqs: student_seqs,
            search_start_date: search_start_date,
            search_end_date: search_end_date
        };

        queryFetch(page, parameter, function(result) {
            const div_study_time_bundle = main_div.querySelector('.div_study_time_bundle');
            const copy_btn_study_time = div_study_time_bundle.querySelector('.copy_btn_study_time').cloneNode(true);
            div_study_time_bundle.innerHTML = '';
            div_study_time_bundle.appendChild(copy_btn_study_time);

            if (result.resultCode == "success") {
                // 학습시간 표기
                const study_times = result.study_times;
                study_times.forEach(function(study_time) {
                    const tagt_td = main_div.querySelectorAll('.calnum[date="' + study_time.select_date + '"]')[0].closest('td');
                    // calnum class add ctext-gc2-imp, act_study_time
                    const calnum = tagt_td.querySelector('.calnum');
                    calnum.classList.add('ctext-gc2-imp');
                    calnum.classList.add('act_study_time');


                    // 반복일정 btn 표기
                    // 오늘보다 이후일때만 표기.
                    if (study_time.select_date >= new Date().format('yyyy-MM-dd')) {
                        const btn_study_time = copy_btn_study_time.cloneNode(true);
                        const span = btn_study_time.querySelector('span');
                        btn_study_time.classList.remove('copy_btn_study_time');
                        btn_study_time.classList.add('btn_study_time');
                        btn_study_time.hidden = false;
                        btn_study_time.querySelector('.study_time_seq').value = study_time.id;
                        btn_study_time.setAttribute('date', study_time.select_date);
                        // is_repeat 이 Y 이면 '매주'+ 요일 + 오전/오후 + 시간
                        // is_repeat 이 N 이면 요일 + 오전/오후 + 시간
                        // const study_time_str 최종적으로 넣기.
                        const date_str = study_time.select_date + " " + study_time.select_time;
                        const am_pm = new Date(date_str).format('a/p');
                        const day = new Date(date_str).format('E');
                        const time = new Date(date_str).format('hh:mm');
                        const study_time_str = (study_time.is_repeat == 'Y' ? '매주' : '') + day + ' ' + am_pm + ' ' + time;
                        span.innerText = study_time_str;

                        div_study_time_bundle.appendChild(btn_study_time);
                    }
                });
            }
        });
    }

    // 모달 / 학습시작시간 정하기 / 반복일정 삭제
    function learncalModalStudyTimeDelete(vthis) {
        //우선은 복수형태로 했지만, 단수로 전송 진행.
        const study_time_seqs = vthis.querySelector('.study_time_seq').value;
        const msg_str = '반복일정을 삭제하시겠습니까?';
        const page = "/manage/learning/study/time/delete";
        const parameter = {
            study_time_seqs: study_time_seqs
        };
        sAlert('반복일정 삭제', msg_str, 2, function() {
            queryFetch(page, parameter, function(result) {
                if (result.resultCode == "success") {
                    toast("반복일정이 삭제되었습니다.");
                    learncalStudyTimeDelete(vthis.getAttribute('date'));
                    vthis.remove();
                } else {
                    toast('다시 시도해주세요.');
                }
            });
        });
    }

    // 2개의 캘린더에서 학습시작시간 삭제
    function learncalStudyTimeDelete(date) {
        const main_div1 = document.querySelector("#learncal_div_main");
        const main_div2 = document.querySelector("#learncal_div_main2");

        // 메인달력에서 학습시간 삭제.
        main_div1.querySelectorAll('.calnum[date="' + date + '"]').forEach(function(calnum) {
            const td = calnum.closest('td');
            const element = td.querySelector('.div_study_time.active')
            element.hidden = true;
            element.classList.remove('.active');
            element.classList.remove('bg-attend-time1');
            element.classList.remove('bg-goal-time1');
            element.querySelector('.study_time').innerText = '';
        });


        // 모달 달력에서 학습시간 표시 삭제.
        main_div2.querySelectorAll('.calnum[date="' + date + '"]').forEach(function(calnum) {
            calnum.classList.remove('ctext-gc2-imp');
            calnum.classList.remove('act_study_time');
        });
    }

    // 필터 체크박스 클릭
    function learncalFilterCheck() {
        const inp_filter_tag = document.querySelectorAll('.div_collapse .inp_filter_tag');
        inp_filter_tag.forEach(function(inp) {
            // input check의 target을 가져와서 target의 hidden을 변경.
            const target = inp.getAttribute('target');
            const target_id = '#' + target;
            const code_seq = inp.getAttribute('code_seq');
            if (inp.checked) {
                // 표시
                document.querySelector(target_id).hidden = false;
                // 출석예정
                if (target == 'learncal_f_attend_time') {
                    const div_study_times = document.querySelectorAll('.div_study_time.bg-goal-time1');
                    div_study_times.forEach(function(div_study_time) {
                        div_study_time.hidden = false;
                    });
                }
                // 출석완료
                // 지각
                // 학습완료
                else if (target == 'learncal_f_completed') {
                    const cal_imgs = document.querySelectorAll('[data-img-calendar-lecture-compt].hidden');
                    cal_imgs.forEach(function(cal_img) {
                        cal_img.classList.remove('hidden');
                        cal_img.classList.add('active');
                        //단 calnum.active 가 있으면 hidden=true 처리.
                        const calnum = cal_img.closest('td').querySelector('.calnum.active');
                        if (calnum != null) {
                            return;
                        }
                        cal_img.hidden = false;
                    });
                }
                //과목 필터
                else if (code_seq != null) {
                    const lecture_rows = document.querySelectorAll('[data-lecture-row-subject-code-seq="' + code_seq + '"]');
                    lecture_rows.forEach(function(lecture_row) {
                        lecture_row.hidden = false;
                    });
                }
            } else {
                // 숨김
                document.querySelector(target_id).hidden = true;

                // 출석예정
                if (target == 'learncal_f_attend_time') {
                    const div_study_times = document.querySelectorAll('.div_study_time.bg-goal-time1');
                    div_study_times.forEach(function(div_study_time) {
                        div_study_time.hidden = true;
                    });
                }
                // 출석완료
                // 지각
                // 학습완료
                else if (target == 'learncal_f_completed') {
                    // data-img-calendar-lecture-compt].active
                    const cal_imgs = document.querySelectorAll('[data-img-calendar-lecture-compt].active');
                    cal_imgs.forEach(function(cal_img) {
                        cal_img.hidden = true;
                        cal_img.classList.remove('active');
                        cal_img.classList.add('hidden');
                    });
                }
                //과목 필터
                else if (code_seq != null) {
                    const lecture_rows = document.querySelectorAll('[data-lecture-row-subject-code-seq="' + code_seq + '"]');
                    lecture_rows.forEach(function(lecture_row) {
                        lecture_row.hidden = true;
                    });

                }
            }
        });
    }

    // 필터 초기화
    function learncalFilterReset() {
        const inp_filter_tag = document.querySelectorAll('.div_collapse .inp_filter_tag');
        inp_filter_tag.forEach(function(inp) {
            inp.checked = false;
            // input check의 target을 가져와서 target의 hidden을 변경.
            const target_id = '#' + inp.getAttribute('target');
            document.querySelector(target_id).hidden = true;
        });
        // 이후 달력에서 필터된 학습시간 / 학습플래너 표시
        learncalFilterCheck();
    }

    // 필터 삭제
    function learncalFilterRemove(vthis) {
        const target_id = '#' + vthis.getAttribute('target');
        document.querySelector(target_id).checked = false;
        learncalFilterCheck();
    }

    // 학습플래너 수정 버튼 클릭.
    function learncalPlannerTransition() {

        // 학습플래너 사이드 초기화.
        learncalPlannerSideClear();

        //필터, 학습플래너 토글
        learncalFilterPlannerToggle();

        // 추천 기본 시간표 첫번째 그룹 클릭
        document.querySelector('.a_planner_timetable_group').click();
    }

    //학습플래너 사이드 초기화.
    function learncalPlannerSideClear() {
        //학습플래너 사이드 초기화.

    }
    // 필터, 학습플레너 토글
    function learncalFilterPlannerToggle() {
        const div_filter = document.querySelector('#learncal_div_filter');
        const div_planner = document.querySelector('#learncal_div_planner');
        const div_bottom = document.querySelector('[data-div-student-lectures-list]');
        const btn_back = document.querySelector('[data-btn-learncal-back]');
        const btn_delete = document.querySelector('[data-btn-learncal-delete]');
        const btn_reage_delete = document.querySelector('[data-btn-learncal-reage-delete]');

        if (div_filter.hidden) {
            div_filter.hidden = false;
            div_planner.hidden = true;
            div_bottom.hidden = true;
            if (btn_back) btn_back.hidden = true;
            btn_delete.hidden = true;
            btn_reage_delete.hidden = true;
            // 달력 정보 초기화 수정 부분
            learncalCalendarDisplayClear('planner_edit');
        } else {
            div_filter.hidden = true;
            div_planner.hidden = false;
            div_bottom.hidden = false;
            if (btn_back) btn_back.hidden = false;
            btn_delete.hidden = false;
            btn_reage_delete.hidden = false;
            // 달력 정보 초기화 필터 부분
            learncalCalendarDisplayClear('student_planner');
        }

        // 학습플래너 캘린더 강의 표기.
        learncalStudyPlannerSelect('');

        // 학습플래너 캘린더 시간 표기.
        learncalMainCalendarStudyTimeDisplay()
    }

    // 학습플래너 그룹목록 클릭.
    function learncalPlannerSampleTimeGroupClick(vthis) {
        // active 제거 후 자신에게 active 추가.
        document.querySelectorAll('.a_planner_timetable_group').forEach(function(a) {
            a.classList.remove('active');
            a.classList.remove('ctext-bc0');
            a.classList.remove('ctext-gc1');
        });
        vthis.classList.add('active');

        const timetable_group_seq = vthis.getAttribute('timetable_group_seq');

        // 전송
        const page = "/manage/timetable/select";
        const parameter = {
            timetable_group_seq: timetable_group_seq
        };

        queryFetch(page, parameter, function(result) {
            //초기화
            // div_timetable_bunlde
            // copy_div_timetable
            const div_timetable_bunlde = document.querySelector('article.div_timetable_bunlde');
            const copy_div_timetable = div_timetable_bunlde.querySelector('.copy_div_timetable');
            div_timetable_bunlde.innerHTML = '';
            div_timetable_bunlde.appendChild(copy_div_timetable);

            if (result.resultCode == "success") {
                // 학습플래너 표시
                const timetables = result.timetables;
                timetables.forEach(function(timetable) {
                    const clone = copy_div_timetable.cloneNode(true);
                    clone.hidden = false;
                    clone.classList.remove('copy_div_timetable');
                    clone.classList.add('div_timetable');
                    clone.querySelector('.timetable_seq').value = timetable.id;
                    clone.querySelector('.lecture_seq').value = timetable.lecture_seq;
                    clone.querySelector('.start_lecture_detail_seq').value = timetable.start_lecture_detail_seq;
                    clone.querySelector('.idx').innerText = timetable.idx;
                    clone.querySelector('.lecture_detail_name').innerText = timetable.lecture_detail_name;
                    clone.querySelector('.lecture_name').innerText = timetable.lecture_name;
                    clone.querySelector('.teacher_name').innerText = timetable.teacher_name;
                    clone.querySelector('.timetable_start_date').innerText = new Date(timetable.timetable_start_date || '').format('yyyy.MM.dd');
                    clone.querySelector('.timetable_days').innerText = timetable.timetable_days;
                    clone.querySelector('.level_names').innerText = timetable.level_names;
                    clone.querySelector('.timetable_icon').src = '/images/' + timetable.subject_function_code + '.svg';
                    div_timetable_bunlde.appendChild(clone);

                });
            }
        });
    }

    // 학습플래너 시간표 오픈 클릭.
    function learncalPlannerStep1TimetableDetail(vthis) {
        const section = vthis.closest('section');
        const timetable_detail = section.querySelector('.timetable_detail');
        const lecture_seq = section.querySelector('.lecture_seq').value;
        const start_lecture_detail_seq = section.querySelector('.start_lecture_detail_seq').value;

        if (vthis.classList.contains('active')) {
            //현재 열린상태이면 닫기
            vthis.classList.remove('active');
            vthis.querySelector('img').classList.remove('rotate-180');
            timetable_detail.hidden = true;
        } else {
            // 현재 닫힌 상태이면 열기
            vthis.classList.add('active');
            vthis.querySelector('img').classList.add('rotate-180');
            timetable_detail.hidden = false;
        }
    }

    // 모달 강좌 선택하기 오픈 클릭.
    function learncalPlannerStep2LectureDetail(vthis) {
        const section = vthis.closest('section');
        const lecture_detail = section.querySelector('.lecture_detail');
        const lecture_seq = section.querySelector('.lecture_seq').value;
        const start_lecture_detail_seq = section.querySelector('.start_lecture_detail_seq').value;


        //.lecture_detail 모두 닫기
        document.querySelectorAll('.lecture_detail').forEach(function(lecture_detail) {
            lecture_detail.hidden = true;
        });
        if (vthis.checked) {
            lecture_detail.hidden = false;
        }
    }

    // 학습플래너수정 > step 클릭
    function learncalAsideStepClick(vthis) {
        // data-div-learncal-aside-tab 모두 active 제거
        document.querySelectorAll('[data-div-learncal-aside-tab]').forEach(function(div) {
            div.classList.remove('active');
        });
        vthis.classList.add('active');
        const step = vthis.getAttribute('data-div-learncal-aside-tab');

        // data-div-learncal-aside-tab-sub 모두  숨기기
        document.querySelectorAll('[data-div-learncal-aside-tab-sub]').forEach(function(div) {
            div.hidden = true;
        });
        document.querySelector('[data-div-learncal-aside-tab-sub="' + step + '"]').hidden = false;
    }
    let lectures_permissions = '';
    // 학습플래너수정 > step2 > 과목 라디오 클릭
    function learncalSubjectCodeClick(vthis) {
        // vthis 체크가 true이면
        if (vthis.checked) {
            const subject_seq = vthis.getAttribute('data-subject-code-seq');
            const page = "/manage/learning/subject/series/select";
            const student_seqs = gl_students.map(function(student) {
                return student.id;
            });
            const parameter = {
                subject_seq: subject_seq,
                student_seqs:student_seqs
            };

            queryFetch(page, parameter, function(result) {
                // 시리즈 번들 클리어
                const bundle = document.querySelector('[data-div-learncal-serise-bundle]');
                const copy_row = document.querySelector('[data-div-learncal-serise-row]').cloneNode(true);
                bundle.innerHTML = '';
                bundle.appendChild(copy_row);
                // console.log(result);
                // lectures_permissions = result.series_codes[0].lectures_permissions;
                if ((result.resultCode || '') == 'success') {
                    const series = result.series_codes;
                    series.forEach(function(series) {
                        const clone = copy_row.cloneNode(true);
                        clone.hidden = false;
                        clone.querySelector('[data-series-code-seq]').setAttribute('data-series-code-seq', series.code_id);
                        clone.querySelector('[data-series-name]').innerText = series.code_name;
                        bundle.appendChild(clone);
                    });

                } else {
                    //   toast('다시 시도해주세요.');
                }
            });
        }
        learncalPlannerStep2SearchBtnToggle('search');
    }

    // step 2 강좌검색하기
    function learncalSearchCourseClick() {
        // learncalSelectCourse();
        const div_stpe2 = document.querySelector('[data-div-learncal-aside-tab-sub="step2"]');
        const course = div_stpe2.querySelector('[data-course-code]')
        const course_seq = course.value || '';
        const serise = div_stpe2.querySelector('[data-series-code-seq]:checked');
        const serise_seq = serise ? serise.getAttribute('data-series-code-seq') : '';
        const publisher = div_stpe2.querySelector('[data-publisher-code-seq]:checked');
        const publisher_seq = publisher ? publisher.getAttribute('data-publisher-code-seq') : '';
        const subject = div_stpe2.querySelector('[data-subject-code-seq]:checked');
        const subject_seq = subject ? subject.getAttribute('data-subject-code-seq') : '';

        //4개중 한개라도 없으면 리턴
        if (!serise_seq || !publisher_seq || !subject_seq) {
            toast('조건을 모두 선택해주세요.');
            return;
        }

        const page = "/manage/lecture/list/select";
        const parameter = {
            serise_seq: serise_seq,
            subject_seq: subject_seq,
            publisher_seq: publisher_seq,
            course_seq: course_seq
        };
        queryFetch(page, parameter, function(result) {
            const div_lectures_bunlde = document.querySelector('.div_lecture_bunlde');
            const copy_div_lectures = div_lectures_bunlde.querySelector('.copy_div_lectures');
            div_lectures_bunlde.innerHTML = '';
            div_lectures_bunlde.appendChild(copy_div_lectures);

            if (result.resultCode == "success") {
                // 학습플래너 표시
                const lectures = result.lectures;
                // const lectures_json = JSON.parse(lectures_permissions);
                lectures.forEach(function(lecture, index) {
                    // TODO: 추후 수정 필요 선생님 퍼미션.
                    // if(Object.values(lectures_json)[index]){
                        const clone = copy_div_lectures.cloneNode(true);
                        clone.hidden = false;
                        clone.classList.remove('copy_div_lectures');
                        clone.classList.add('div_lectures');
                        clone.querySelector('.lecture_seq').value = lecture.id;
                        // clone.querySelector('.start_lecture_detail_seq').value = lecture.start_lecture_detail_seq;
                        clone.querySelector('.start_lecture_detail_seq').value = '';
                        clone.querySelector('.lecture_detail_count').innerText = lecture.lecture_detail_count;
                        clone.querySelector('.lecture_name').innerText = lecture.lecture_name;
                        clone.querySelector('.teacher_name').innerText = lecture.teacher_name;
                        clone.querySelector('.level_names').innerText = lecture.level_names;
                        clone.querySelector('.lecture_icon').src = '/images/' + lecture.subject_function_code + '.svg';
                        div_lectures_bunlde.appendChild(clone);
                    // }
                });
            }
        })
        // learncal_modal_course_search 모달
        const myModal = new bootstrap.Modal(document.getElementById('learncal_modal_course_search'), {});
        myModal.show();
    }

    // step2 강좌 선택하기 > 선택완료
    function learncalPlannerStep2LectureConfirm() {
        //  lecture-step2-modal-radio 라디오 선택했는지 확인.
        const modal = document.querySelector('#learncal_modal_course_search');
        const step2_chk_radios = modal.querySelectorAll('[name=lecture-step2-modal-radio]:checked');
        if (step2_chk_radios.length == 0) {
            toast('강좌를 선택해주세요.');
            return;
        }
        const lecture_tag = step2_chk_radios[0].closest('section');
        const lecture_seq = lecture_tag.querySelector('.lecture_seq').value;
        const lecture_name = lecture_tag.querySelector('.lecture_name').innerText;
        const teacher_name = lecture_tag.querySelector('.teacher_name').innerText;
        const lecture_icon_src = lecture_tag.querySelector('.lecture_icon').src;

        const start_lecture_detail_seq = lecture_tag.querySelector('.start_lecture_detail_seq').value;
        // select *from lecture_details

        const page = "/manage/timetable/lecture/detail/select";
        const parameter = {
            lecture_seq: lecture_seq,
            is_main_detail: 'Y'
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                // [data-div-learncal-aside-tab-sub-select-lecture] 안에
                // div_sel_lecture_bunlde 안에
                // copy_div_sel_lectures 를 클론 만듬
                const div_sel_lecture_bunlde = document.querySelector('[data-div-learncal-aside-tab-sub-select-lecture] .div_sel_lecture_bunlde');
                const copy_div_sel_lecture = div_sel_lecture_bunlde.querySelector('.copy_div_sel_lectures').cloneNode(true);
                div_sel_lecture_bunlde.innerHTML = '';
                div_sel_lecture_bunlde.appendChild(copy_div_sel_lecture);

                const sel_lectures_clone = copy_div_sel_lecture.cloneNode(true);
                sel_lectures_clone.classList.remove('copy_div_sel_lectures');
                sel_lectures_clone.classList.add('div_sel_lectures');
                sel_lectures_clone.hidden = false;
                sel_lectures_clone.querySelector('.lecture_seq').value = lecture_seq;
                sel_lectures_clone.querySelector('[name=lecture-step2-in-radio]').checked = true;
                sel_lectures_clone.querySelector('.start_lecture_detail_seq').value = start_lecture_detail_seq;
                sel_lectures_clone.querySelector('.lecture_name').innerText = lecture_name;
                sel_lectures_clone.querySelector('.teacher_name').innerText = teacher_name;
                sel_lectures_clone.querySelector('.lecture_icon').src = lecture_icon_src;
                div_sel_lecture_bunlde.appendChild(sel_lectures_clone);

                // 시작강의 선택 설정.
                const select_tag = document.querySelector('[data-sel-learncal-step2-start-lecture-detail-seq]');
                select_tag.innerHTML = '';
                lecture_details = result.lecture_details;
                let index = 0;
                lecture_details.forEach(function(detail) {
                    // detail.id
                    // detail.is_use
                    // detail.lecture_detail_name
                    const option = document.createElement('option');
                    const before_txt = index >= 2 ? (index - 1) + '강.' : '';
                    option.value = detail.id;
                    option.innerText = before_txt + detail.lecture_detail_name;

                    // 사용시에만 추가
                    // TODU: 요청으로 무조건 나오게 일단은 진행.
                    // if (detail.is_use == 'Y') {
                        select_tag.appendChild(option);
                    // }
                    index++;
                });
                if ((start_lecture_detail_seq || '') != '') {
                    select_tag.value = start_lecture_detail_seq;
                } else {
                    select_tag.value = select_tag.options[0].value;
                }
                learncalPlannerStep2SearchBtnToggle('select');
                modal.querySelector('[data-bs-dismiss="modal"]').click();
            } else {
                toast('다시 시도해주세요. 시작강의가 있는지 관리자에게 문의해주세요.');
                //  선택한 강좌 하단 숨기기처리.
            }
        });
    }

    //step2 search/select 버튼 토글
    function learncalPlannerStep2SearchBtnToggle(type) {
        if (type == 'search') {
            // data-div-learncal-aside-tab-sub-search-btn 숨기고
            document.querySelector('[data-div-learncal-aside-tab-sub-search-btn]').hidden = false;
            // data-div-learncal-aside-tab-sub-select-lecture 보이기
            document.querySelector('[data-div-learncal-aside-tab-sub-select-lecture]').hidden = true;
        } else if (type == 'select') {
            // data-div-learncal-aside-tab-sub-search-btn 보이기
            document.querySelector('[data-div-learncal-aside-tab-sub-search-btn]').hidden = true;
            // data-div-learncal-aside-tab-sub-select-lecture 숨기기
            document.querySelector('[data-div-learncal-aside-tab-sub-select-lecture]').hidden = false;
        }
    }

    // 시간표 강좌 목록에 요일 선택
    function learncalPlannerTimeTableDayClick(vthis) {
        if (vthis.classList.contains('active')) {
            vthis.classList.remove('active');
        } else {
            vthis.classList.add('active');
        }
    }

    // step2 선태한강좌의 요일 선택.
    function learncalPlannerStep2SelectDay(vthis) {
        if (vthis.classList.contains('active')) {
            vthis.classList.remove('active');
        } else {
            vthis.classList.add('active');
        }
        learncalPlannerStep2EndDateSet();
    }

    // 시간표 추가하기.
    function learncalTimeTableAdd(data) {
        // lecture_name, start_date, lecture_details, days
        const bundle = document.querySelector('[data-div-learncal-timetable-bundle]');
        const row = bundle.querySelector('[data-div-learncal-timetable-row="copy"]').cloneNode(true);
        row.hidden = false;
        row.setAttribute('data-div-learncal-timetable-row', 'clone');
        row.querySelector('[data-lecutre-name]').innerHTML = data.lecture_name;
        row.querySelector('[data-start-date]').innerHTML = data.start_date;
        row.querySelector('[data-end-date]').innerHTML = data.end_date;
        row.querySelector('[data-lecutre-seq]').value = data.lecture_seq;
        row.querySelector('[data-start-date-str]').innerHTML = data.start_date.replace(/-/g, '.');

        const page = "/manage/timetable/lecture/detail/select";
        const parameter = {
            lecture_seq: data.lecture_seq,
            is_main_detail: 'Y'
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                lecture_details = result.lecture_details;
                let index = 0;
                lecture_details.forEach(function(detail) {
                    const option = document.createElement('option');
                    const before_txt = index >= 2 ? (index - 1) + '강.' : '';
                    option.value = detail.id;
                    option.innerText = before_txt + detail.lecture_detail_name;

                    // 사용시에만 추가
                    if (detail.is_use == 'Y') {
                        row.querySelector('[data-start-lecture-detail]').appendChild(option);
                        if (data.start_lecture_detail_seq == detail.id) {
                            option.selected = true;
                        }
                    }
                    index++;
                });
            }
        });

        if (data.days != undefined) {
            data.days.forEach(function(day) {
                row.querySelector('[data-days="' + day + '"]').classList.add('active');
            });
        }
        bundle.appendChild(row);
        toast('시간표 강좌 목록에 추가되었습니다.');

        // data-span-student-lectures-cnt 여기에 갯수 넣기.
        document.querySelector('[data-span-student-lectures-cnt]').innerText = bundle.querySelectorAll('[data-div-learncal-timetable-row="clone"]').length;
        document.querySelector('[data-div-student-lectures-list]').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
    // 시간표 강좌 목록 초기화하기.
    function learncalPlannerReset() {
        sAlert('클리어', '추가한 시간표가 초기화됩니다. 계속 진행하시겠습니까?', 3, function() {
            const bundle = document.querySelector('[data-div-learncal-timetable-bundle]');
            bundle.querySelectorAll('[data-div-learncal-timetable-row="clone"]').forEach(function(row) {
                row.remove();
            });
            const row_cnt = document.querySelectorAll('[data-div-learncal-timetable-row="clone"]').length;
            document.querySelector('[data-span-student-lectures-cnt]').innerText = row_cnt;
        })
    }

    // 시간표 강좌 목록 한개 빼기
    function learncalPlannerDelete(vthis) {
        sAlert('삭제', '해당 시간표를 삭제하시겠습니까?', 3, function() {
            vthis.closest('[data-div-learncal-timetable-row]').remove();
            const row_cnt = document.querySelectorAll('[data-div-learncal-timetable-row="clone"]').length;
            document.querySelector('[data-span-student-lectures-cnt]').innerText = row_cnt;
        });
    }
    // step1 강좌선택 > 시간표에 추가하기 버튼 클릭.
    function learncalStep1AddTimetableClick() {
        const data = {}
        const main_div = document.querySelector("#learncal_div_main");
        // div_timetable_bunlde
        const bunlde = document.querySelector('article.div_timetable_bunlde');
        if (document.querySelectorAll('[name="lecture-step1-in-radio"]:checked').length < 1) {
            toast('강좌를 선택해주세요.');
            return;
        }
        const act_row = document.querySelector('[name="lecture-step1-in-radio"]:checked').closest('.div_timetable');

        // lecture_seq, lecture_name, start_date, end_date, days
        const lecture_seq = act_row.querySelector('.lecture_seq').value;
        const lecture_name = act_row.querySelector('.lecture_name').innerText;
        const start_lecture_detail_seq = act_row.querySelector('.start_lecture_detail_seq').value;
        const timetable_days = act_row.querySelector('.timetable_days').innerText;
        const timetable_start_date = act_row.querySelector('.timetable_start_date').innerText;
        const start_date = timetable_start_date.replace(/\./g, '-');
        // const start_date = main_div.querySelectorAll('.calnum.active')[0].getAttribute('date');

        data.lecture_seq = lecture_seq;
        data.lecture_name = lecture_name;
        data.start_date = start_date;
        data.start_lecture_detail_seq = start_lecture_detail_seq;
        const days = [];
        timetable_days.split(',').forEach(function(day) {
            switch (day) {
                case '월':
                    days.push('mon');
                    break;
                case '화':
                    days.push('tue');
                    break;
                case '수':
                    days.push('wed');
                    break;
                case '목':
                    days.push('thu');
                    break;
                case '금':
                    days.push('fri');
                    break;
                case '토':
                    days.push('sat');
                    break;
                case '일':
                    days.push('sun');
                    break;
            }
        });
        data.days = days;

        learncalTimeTableAdd(data);
    }
    // step2 강좌선택 > 시간표에 추가하기 버튼클릭.
    function learncalStep2AddTimetableClick() {
        const data = {};
        const div_sel_lecture = document.querySelector('[data-div-learncal-aside-tab-sub-select-lecture]');
        const lecture_seq = div_sel_lecture.querySelector('.div_sel_lectures .lecture_seq').value;
        const lecture_name = div_sel_lecture.querySelector('.div_sel_lectures .lecture_name').innerText;
        // 시작강의를 선택 /[data-sel-learncal-step2-start-lecture-detail-seq]
        const start_lecture_detail_seq = div_sel_lecture.querySelector('[data-sel-learncal-step2-start-lecture-detail-seq]').value;
        if ((start_lecture_detail_seq || '') == '') {
            toast('시작강의를 선택해주세요.');
            return;
        }
        // 요일 선택 확인 없으면 리턴 .active
        const div_sel_days = div_sel_lecture.querySelectorAll('[data-btn-learncal-day]');
        const days = [];
        let is_day_return = true;
        div_sel_days.forEach(function(day) {
            if (day.classList.contains('active')) {
                const day_str = day.getAttribute('data-btn-learncal-day');
                days.push(day_str);
                is_day_return = false;
            }
        });
        if (is_day_return) {
            toast('요일을 선택해주세요.');
            return;
        }

        // 학습시작일 확인 없으면 리턴
        const start_date = div_sel_lecture.querySelector('[data-input-learncal-step2-start-date]').value;
        const end_date = div_sel_lecture.querySelector('[data-input-learncal-step2-end-date]').value;
        if ((start_date || '') == '') {
            toast('학습시작일을 선택해주세요.');
            return;
        }

        // lecture_seq, lecture_name, start_date, end_date,  days

        data.lecture_seq = lecture_seq;
        data.lecture_name = lecture_name;
        data.start_lecture_detail_seq = start_lecture_detail_seq;
        data.start_date = start_date;
        data.end_date = end_date;
        data.days = days;

        learncalTimeTableAdd(data);
        // learncalStep2ReSet();
    }

    // step3 강좌선택 > 시간표에 추가하기 버튼클릭.
    function learncalStep3AddTimetableClick() {
        const student_seqs = gl_students.map(function(student) {
            return student.id;
        }).join(',');
        if(gl_students.length != 1){
            toast('미수강/재수강 추가는 학생 1명만 가능합니다.');
            return;
        }
        // 학습일이 선택이 되어있는지 확인.
        const sel_date = document.querySelector('[data-input-learncal-step3-sel-date]').value;
        // 학습일이 선택 안되어 있으면 리턴 / 오늘보다 작은경우에도 리턴
        if ((sel_date || '') == '') { // || new Date(sel_date) < new Date().setHours(0,0,0,0)){
            toast('학습일을 선택해주세요. 또는 오늘 이후날짜를 선택해주세요.');
            return;
        }

        // 강좌 선택이 되어있는지 확인.
        const radio_chk_cnt = document.querySelectorAll('[name="lecture-step3-in-radio"]:checked').length;
        if (radio_chk_cnt < 1) {
            toast('강좌를 선택해주세요.');
            return;
        }

        // const sel_lecture_tag = document.querySelectorAll('[name="lecture-step3-in-radio"]:checked')[0].closest('.div_step3_sel_lectures');
        // const student_lecture_detail_seq = sel_lecture_tag.querySelector('.student_lecture_detail_seq').value;
        const sel_lecture_tags = document.querySelectorAll('[name="lecture-step3-in-radio"]:checked');
        const student_lecture_detail_seqs = Array.from(sel_lecture_tags).map(tag => tag.closest('.div_step3_sel_lectures').querySelector('.student_lecture_detail_seq').value);
        const step3_type = document.querySelector('[data-a-learncal-step3-tab].active').getAttribute('data-a-learncal-step3-tab');
        // 전송
        const page = "/manage/learning/student/lecture/detail/insert";
        const parameter = {
            student_seqs: student_seqs,
            student_lecture_detail_seqs: student_lecture_detail_seqs,
            sel_date: sel_date,
            step3_type: step3_type
        };
        queryFetch(page, parameter, function(result) {
            if (result.resultCode == "success") {
                toast('학습일정이 추가되었습니다.');
                learncalStudyPlannerSelect();
                // 학습일정 추가후 초기화
                learncalStep3ReSet();
            } else {
                toast('다시 시도해주세요.');
            }
        });
    }

    function learncalPlannerSave() {
        const bundle = document.querySelector('[data-div-learncal-timetable-bundle]');
        const row_clone = bundle.querySelectorAll('[data-div-learncal-timetable-row="clone"]');

        if (row_clone.length == 0) {
            //toast('시간표 강좌 목록에 시간표를 추가해주세요.');
            return;
        }
        const group_parameter = [];
        for (let i = 0; i < row_clone.length; i++) {
            const row = row_clone[i];
            const lectrue_seq = row.querySelector('[data-lecutre-seq]').value;
            const start_lecture_detail_seq = row.querySelector('[data-start-lecture-detail]').value;
            const sel_days = row.querySelectorAll('[data-days]');
            const days = {
                is_sun: 'N',
                is_mon: 'N',
                is_tue: 'N',
                is_wed: 'N',
                is_thu: 'N',
                is_fri: 'N',
                is_sat: 'N'
            };
            let is_day_return = true;
            sel_days.forEach(function(day) {
                if (day.classList.contains('active')) {
                    const day_str = day.getAttribute('data-days');
                    days['is_' + day_str] = 'Y';
                    is_day_return = false;
                }
            });
            if (is_day_return) {
                toast('선택이 요일 선택이 안된 시간표가 있습니다. 먼저 선택해주세요.');
                return;
            }
            const start_date = row.querySelector('[data-start-date]').innerText;
            const all_idx = row.querySelectorAll('[data-start-lecture-detail] option').length;
            const start_idx = row.querySelector('[data-start-lecture-detail]').selectedIndex;
            const remain_idx = all_idx - start_idx;

            // 시작일로 부터 몇일뒤인지 요일을 카운트 해서 계산.
            const start_date_obj = new Date(start_date);
            const start_day = start_date_obj.getDay();
            let end_date = new Date(start_date);
            const lecture_detail_parts = [];
            let safe_count = 0;
            let count = 0;
            let is_first = false;
            //safe_count = 안전 장치
            while (count < remain_idx || safe_count > 1000) {
                let part_info = {};
                safe_count++;
                if (is_first)
                    end_date.setDate(end_date.getDate() + 1);
                is_first = true;
                const lecture_detail_seq = row.querySelectorAll('[data-start-lecture-detail] option')[start_idx + (count)]?.value;
                if(!lecture_detail_seq){
                    toast('학습안에 강의 내용이 없습니다. 관리자에게 문의 해주세요.');
                    return;
                }
                if (days['is_sun'] == 'Y' && end_date.getDay() == 0) {
                    part_info = {
                        lecture_detail_seq: lecture_detail_seq,
                        date: end_date.format('yyyy-MM-dd'),
                        day: '일'
                    }
                    lecture_detail_parts.push(part_info);
                    count++;
                } else if (days['is_mon'] == 'Y' && end_date.getDay() == 1) {
                    part_info = {
                        lecture_detail_seq: lecture_detail_seq,
                        date: end_date.format('yyyy-MM-dd'),
                        day: '월'
                    }
                    lecture_detail_parts.push(part_info);
                    count++;
                } else if (days['is_tue'] == 'Y' && end_date.getDay() == 2) {
                    part_info = {
                        lecture_detail_seq: lecture_detail_seq,
                        date: end_date.format('yyyy-MM-dd'),
                        day: '화'
                    }
                    lecture_detail_parts.push(part_info);
                    count++;
                } else if (days['is_wed'] == 'Y' && end_date.getDay() == 3) {
                    part_info = {
                        lecture_detail_seq: lecture_detail_seq,
                        date: end_date.format('yyyy-MM-dd'),
                        day: '수'
                    }
                    lecture_detail_parts.push(part_info);
                    count++;
                } else if (days['is_thu'] == 'Y' && end_date.getDay() == 4) {
                    part_info = {
                        lecture_detail_seq: lecture_detail_seq,
                        date: end_date.format('yyyy-MM-dd'),
                        day: '목'
                    }
                    lecture_detail_parts.push(part_info);
                    count++;
                } else if (days['is_fri'] == 'Y' && end_date.getDay() == 5) {
                    part_info = {
                        lecture_detail_seq: lecture_detail_seq,
                        date: end_date.format('yyyy-MM-dd'),
                        day: '금'
                    }
                    lecture_detail_parts.push(part_info);
                    count++;
                } else if (days['is_sat'] == 'Y' && end_date.getDay() == 6) {
                    part_info = {
                        lecture_detail_seq: lecture_detail_seq,
                        date: end_date.format('yyyy-MM-dd'),
                        day: '토'
                    }
                    lecture_detail_parts.push(part_info);
                    count++;
                }
            }
            group_parameter.push({
                lecture_seq: lectrue_seq,
                start_date: start_date,
                end_date: end_date.format('yyyy-MM-dd'),
                start_lecture_detail_seq: start_lecture_detail_seq,
                days: days,
                lecture_detail_parts: lecture_detail_parts
            });
        } // for end

        // 전송.
        const page = "/manage/learning/student/lecture/insert";
        const parameter = {
            student_seqs: gl_students.map(function(student) {
                return student.id;
            }).join(','),
            group_parameter: group_parameter
        };
        sAlert('SAVE', '추가한 시간표를 학습플래너로 저장하시겠습니까?', 3, function() {
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    sAlert('', '저장 완료되었습니다.');
                    learncalStudyPlannerSelect();
                }
            });
        }, function() {

        });
    }

    // 학습시작일과, 요일 선택시 끝일을 예측 해서 표기.
    function learncalPlannerStep2EndDateSet() {
        const div_sel_lecture = document.querySelector('[data-div-learncal-aside-tab-sub-select-lecture]');
        // 현재 선택 요일 가져오기.
        const div_sel_days = div_sel_lecture.querySelectorAll('[data-btn-learncal-day]');
        const days = {
            is_sun: 'N',
            is_mon: 'N',
            is_tue: 'N',
            is_wed: 'N',
            is_thu: 'N',
            is_fri: 'N',
            is_sat: 'N'
        };
        let is_day_return = true;
        div_sel_days.forEach(function(day) {
            if (day.classList.contains('active')) {
                const day_str = day.getAttribute('data-btn-learncal-day');
                days['is_' + day_str] = 'Y';
                is_day_return = false;
            }
        });
        // 요일이 선택되어 있지 않으면 리턴
        if (is_day_return) {
            return;
        }

        // 시작일 가져오기
        const start_date = div_sel_lecture.querySelector('[data-input-learncal-step2-start-date]').value;
        if ((start_date || '') == '') {
            return;
        }

        // 시작강의 뒤에 몇개의 강의가 있는지 수치 가져오기.
        const all_idx = document.querySelectorAll('[data-sel-learncal-step2-start-lecture-detail-seq] option').length;
        const start_idx = document.querySelector('[data-sel-learncal-step2-start-lecture-detail-seq]').selectedIndex;
        const remain_idx = all_idx - start_idx;
        if(all_idx == 0){
            toast('시작강의가 없습니다.');
            return;
        };
        // 시작일로 부터 몇일뒤인지 요일을 카운트 해서 계산.
        const start_date_obj = new Date(start_date);
        const start_day = start_date_obj.getDay();
        const end_date = new Date(start_date);
        let count = 1;
        while (count < remain_idx) {
            end_date.setDate(end_date.getDate() + 1);
            if (days['is_sun'] == 'Y' && end_date.getDay() == 0) {
                count++;
            } else if (days['is_mon'] == 'Y' && end_date.getDay() == 1) {
                count++;
            } else if (days['is_tue'] == 'Y' && end_date.getDay() == 2) {
                count++;
            } else if (days['is_wed'] == 'Y' && end_date.getDay() == 3) {
                count++;
            } else if (days['is_thu'] == 'Y' && end_date.getDay() == 4) {
                count++;
            } else if (days['is_fri'] == 'Y' && end_date.getDay() == 5) {
                count++;
            } else if (days['is_sat'] == 'Y' && end_date.getDay() == 6) {
                count++;
            }
        }
        div_sel_lecture.querySelector('[data-input-learncal-step2-end-date]').value = end_date.format('yyyy-MM-dd');
    }

    // STEP2 의 내용을 모두 리셋.
    function learncalStep2ReSet() {
        const div_sel_lecture = document.querySelector('[data-div-learncal-aside-tab-sub-select-lecture]');
        div_sel_lecture.querySelectorAll('.div_sel_lectures').forEach(function(lecture) {
            lecture.remove();
        });
        div_sel_lecture.querySelector('[data-sel-learncal-step2-start-lecture-detail-seq]').innerHTML = '';
        div_sel_lecture.querySelector('[data-input-learncal-step2-start-date]').value = '';
        div_sel_lecture.querySelector('[data-input-learncal-step2-end-date]').value = '';
        div_sel_lecture.querySelectorAll('[data-btn-learncal-day]').forEach(function(day) {
            day.classList.remove('active');
        });

        learncalPlannerStep2SearchBtnToggle('search')

        // data-div-learncal-aside-tab-sub="step2" 의 하위 모든 라디오 checked = false;
        const div_step2 = document.querySelector('[data-div-learncal-aside-tab-sub="step2"]');
        div_step2.querySelectorAll(' input[type=radio]').forEach(function(radio) {
            radio.checked = false;
        });
        div_step2.querySelectorAll(' input[type=text]').forEach(function(input) {
            input.value = '';
        });
        // data-div-learncal-aside-tab-sub="step2" 의 하위 모든 select.value = ''
        div_step2.querySelectorAll(' select').forEach(function(select) {
            select.value = '';
        });

    }

    // STEP3 의 내용을 모두 리셋.
    function learncalStep3ReSet() {
        const div_step3 = document.querySelector('[data-div-learncal-aside-tab-sub="step3"]');
        div_step3.querySelectorAll('.div_step3_sel_lectures').forEach(function(lecture) {
            lecture.remove();
        });
        div_step3.querySelector('[data-input-learncal-step3-sel-date]').value = '';
        div_step3.querySelectorAll('input[type="radio"]').forEach(function(radio) {
            radio.checked = false;
        });
    }

    function learncalCalendarDisplayClear(type) {
        //학생이 보는 화면(필터) 리셋/클리어
        const main_div = document.querySelector("#learncal_div_main");
        if (type == 'student_planner') {
            main_div.querySelectorAll('[data-div-calendar-lecture-row="clone"]').forEach(function(row) {
                row.remove();
            });
            main_div.querySelectorAll('[data-img-calendar-lecture-compt]').forEach(function(img) {
                img.hidden = true;
                img.classList.remove('active');
                img.classList.remove('hidden');
            });
            main_div.querySelectorAll('[data-div-calendar-lecture-bundle]').forEach(function(bundle) {
                bundle.hidden = false;
            });
            const elements = document.querySelectorAll('.div_study_time.active')
            elements.forEach(function(element) {
                element.hidden = true;
                element.classList.remove('.active');
                element.classList.remove('bg-attend-time1');
                element.classList.remove('bg-goal-time1');
                element.querySelector('.study_time').innerText = '';
            });
        }
        //학습플래너 수정 화면 리셋/클리어
        else if (type == 'planner_edit') {
            main_div.querySelectorAll('[data-div-calendar-lecture-row2="clone"]').forEach(function(row) {
                row.remove();
            });
        }
    }
    // 학생 달력 표기 학습플래너 SELECT
    function learncalStudyPlannerSelect() {
        // 학생이 선택이 되어 있지 않으면 리턴
        if (gl_students.length < 1) {
            return;
        }
        // 학습플래너 수정 화면인지 확인.
        // #learncal_div_filter가 hidden 이면 select_type = 'no_group'
        let select_type = '';
        if (document.querySelector('#learncal_div_filter').hidden) {
            select_type = 'no_group';
        } else {
            select_type = 'date_group';
        }

        const main_div = document.querySelector("#learncal_div_main");
        // 현재 날짜를 기준으로 한주의 시작 일요일의 날짜와, 끝 토요일의 날짜를 가져옴.
        const search_start_date = main_div.querySelector(".calnum").getAttribute('date');
        const search_end_date = main_div.querySelector(".search_end_date").value;
        // 우선은 복수형태로 했지만, 단수로 전송 진행.
        const student_seqs = gl_students.map(function(student) {
            return student.id;
        }).join(',');

        main_div.querySelectorAll('[data-div-calendar-lecture-row="clone"], [data-div-calendar-lecture-row2="clone"]').forEach(function(row) {
            row.remove();
        });
        main_div.querySelectorAll('[data-img-calendar-lecture-compt]').forEach(function(img) {
            img.hidden = true;
            img.classList.remove('active');
            img.classList.remove('hidden');
        });
        main_div.querySelectorAll('[data-div-calendar-lecture-bundle]').forEach(function(bundle) {
            bundle.hidden = false;
        });
        document.querySelectorAll('.modal-body .calnum').forEach(function(calnum) {
            const date = calnum.getAttribute('date');
            const currentMonth = new Date().toISOString().slice(0, 7);
            if (date && !date.startsWith(currentMonth)) {
                calnum.closest('td').classList.add('highlight-not-current-month');
            }

        });
        // 전송
        const page = "/manage/learning/study/planner/select";
        const parameter = {
            student_seqs: student_seqs,
            search_start_date: search_start_date,
            search_end_date: search_end_date,
            select_type: select_type
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                if (select_type == 'date_group') learncalMainCalendarStudyLectureDisplay(result.student_lecture_details);
                if (select_type == 'no_group') learncalMainCalendarStudyLectureEditDisplay(result.student_lecture_details);
            } else {

            }
        });
    }

    // 캘린더에 학습플래너 표기
    function learncalMainCalendarStudyLectureDisplay(details) {
        const main_div = document.querySelector("#learncal_div_main");
        const row = main_div.querySelector('[data-div-calendar-lecture-row="copy"]').cloneNode(true);
        row.setAttribute('data-div-calendar-lecture-row', 'clone');
        row.hidden = false;
        const today = new Date();
        const start_date = new Date(today.setDate(today.getDate() - today.getDay()));
        const end_date = new Date(today.setDate(today.getDate() + 6));
        const is_show_complete = document.querySelector('#learncal_check_lcompleted').checked;

        details.forEach(function(detail, index) {
            const calnum = main_div.querySelector('.calnum[date="' + detail.sel_date.substr(0, 10) + '"]');
            const td = calnum.closest('td');
            const bundle = td.querySelector('[data-div-calendar-lecture-bundle]');
            const row_clone = row.cloneNode(true);
            const key = document.querySelector('figcaption[code_seq="' + detail.code_seq + '"]').getAttribute('key');
            row_clone.querySelector('[data-lecture-subject-name]').innerText = detail.subject_name;
            row_clone.querySelector('[data-lecture-subject-cnt]').innerText = detail.cnt;
            row_clone.querySelector('[data-lecture-dot-color]').style.backgroundColor = 'var(--color-subject' + key + ')';
            row_clone.setAttribute('data-lecture-row-subject-code-seq', detail.code_seq);
            // 과목 필터
            if (!document.querySelector('#learncal_check_code_' + detail.code_seq).checked) {
                row_clone.hidden = true;
            }

            // 이번주 마지막일 이후 강의 숨김처리.
            if (new Date(detail.sel_date) > end_date) {
                return;
            }
            // 오늘 이전 날짜는 강의 숨김처리.
            if (new Date(detail.sel_date) <= today) {
                bundle.hidden = true;
                // 오늘 이전인데 그날짜 모두 완료시 완료 아이콘 표기.
                if (detail.cnt * 1 == detail.complete_cnt * 1 && detail.cnt != 0) {
                    if (is_show_complete) {
                        bundle.closest('td').querySelector('[data-img-calendar-lecture-compt]').hidden = false;
                        bundle.closest('td').querySelector('[data-img-calendar-lecture-compt]').classList.add('active');
                    } else {
                        bundle.closest('td').querySelector('[data-img-calendar-lecture-compt]').classList.add('hidden');
                    }
                }
            }

            // 오늘 포함 이번주는 강의 표기
            if (new Date(detail.sel_date) >= start_date && new Date(detail.sel_date) <= end_date) {
                // 단 완료일 경우에는 숨김.
                if (!bundle.closest('td').querySelector('[data-img-calendar-lecture-compt]').classList.contains('active')) {
                    bundle.hidden = false;
                }
                bundle.appendChild(row_clone);
            }

            // "외 1개" 처리
            const lectureRows = bundle.querySelectorAll('[data-div-calendar-lecture-row="clone"]');
            if (lectureRows.length > 4) {
                // 4개를 초과하는 경우
                const excessCount = lectureRows.length - 3;
                // 5개 이상의 강의를 숨김 처리
                lectureRows.forEach((row, index) => {
                    if (index >= 3) {
                        row.hidden = true;
                    }
                });
                // "외 1개" 표시 추가
                const ellipsisDiv = document.createElement('div');
                ellipsisDiv.className = 'lecture-row text-m-16px text-start mt-1 ellipsisDiv';
                ellipsisDiv.setAttribute('data-div-calendar-lecture-row', 'clone');
                ellipsisDiv.innerText = `외 ${excessCount}개...`;
                const existingEllipsisDiv = bundle.querySelector('.ellipsisDiv');
                if (existingEllipsisDiv) {
                    bundle.removeChild(existingEllipsisDiv);
                }
                bundle.appendChild(ellipsisDiv);
            }
        });
    }
    // 캘린더에 학습플래너 수정 화면 표기.
    function learncalMainCalendarStudyLectureEditDisplay(details) {
        const main_div = document.querySelector("#learncal_div_main");
        const row = main_div.querySelector('[data-div-calendar-lecture-row2="copy"]').cloneNode(true);
        row.setAttribute('data-div-calendar-lecture-row2', 'clone');
        row.hidden = false;
        let idx = 0;
        let no_idx = 0;
        let is_no_link = false;
        details.forEach(function(detail) {
            // if(detail.lecture_detail_link){
                idx++;
                //detail.sel_date 가 start-date 와 end-date 사이에 있는지 확인.
                const calnum = main_div.querySelector('.calnum[date="' + detail.sel_date.substr(0, 10) + '"]');
                const td = calnum.closest('td');
                const bundle = td.querySelector('[data-div-calendar-lecture-bundle]');
                const row_clone = row.cloneNode(true);
                const key = document.querySelector('figcaption[code_seq="' + detail.code_seq + '"]').getAttribute('key');
                // const before_txt = (idx - 1) >= 2 ? ((idx - 1) - 1) + '강.' : '';
                const before_txt = '';
                const name = detail.subject_name + ' [' + detail.lecture_name + '] ' + detail.lecture_detail_description; //before_txt + detail.lecture_detail_name;

                row_clone.setAttribute('key', idx);
                row_clone.querySelector('[data-lecture-subject-name]').innerText = name;
                row_clone.querySelector('[data-lecture-dot-color]').style.backgroundColor = 'var(--color-subject' + key + ')';
                row_clone.querySelector('[data-student-lecture-detail-seq]').value = detail.id;
                row_clone.hidden = false;
                bundle.appendChild(row_clone);
            // }else{
            //     no_idx++;
            //     is_no_link = true;
            // }
        });
        if(is_no_link){
            toast(`준비하기 - 링크연결이 없는 강의(${no_idx}개)는 제외되었습니다.`);
        }
    }
    // 학습플래너 STEP3 > 했어요 / 안했어요 TAB 클릭
    function learncalPlannerStep3TabClick(vthis) {
        // vthis 에 active 추가
        vthis.closest('nav').querySelectorAll('a').forEach(function(a) {
            a.classList.remove('active');
        });
        vthis.classList.add('active');
        learncalStep3ReSet();
    }

    // 학습플래너 STEP3 > 과목 클릭 / 강의 가져오기.
    function learncalPlannerStep3SubjectClick(vthis) {
        const div_step3 = document.querySelector('[data-div-learncal-aside-tab-sub="step3"]');
        // [추가 코드] // 중복으로 할지말지 체크필요.
        const student_seqs = gl_students.map(function(student) {
            return student.id;
        }).join(',');
        // nodo, redo
        const step3_type = document.querySelector('[data-a-learncal-step3-tab].active').getAttribute('data-a-learncal-step3-tab');
        const subject_seq = vthis.getAttribute('data-subject-code-seq');
        let end_search_date = new Date();
        end_search_date.setDate(end_search_date.getDate() - 1);
        end_search_date = end_search_date.format('yyyy-MM-dd');
        let start_search_date = new Date();
        start_search_date.setMonth(start_search_date.getMonth() - 1);
        start_search_date = start_search_date.format('yyyy-MM-01');

        const page = "/manage/learning/student/do/lecture/select";
        const parameter = {
            student_seqs: student_seqs,
            step3_type: step3_type,
            subject_seq: subject_seq,
            start_search_date: start_search_date,
            end_search_date: end_search_date
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                learncalPlannerStep3LectureDoSelect(result);
            } else {

            }
        });
    }

    // 학습플래너 STEP3 > 미수강/재수강 강의 보여주기.
    function learncalPlannerStep3LectureDoSelect(result) {
        const slds = result.student_lecture_details;
        // bunlde = .div_step3_sel_lecture_bunlde
        // row = .copy_div_step3_sel_lectures
        // 초기화
        const div_step3 = document.querySelector('[data-div-learncal-aside-tab-sub="step3"]');
        const bundle = div_step3.querySelector('.div_step3_sel_lecture_bunlde');
        const row_copy = bundle.querySelector('.copy_div_step3_sel_lectures').cloneNode(true);
        bundle.innerHTML = '';
        bundle.appendChild(row_copy);
        let index = 0;
        slds.forEach(function(sld) {
            const row_clone = row_copy.cloneNode(true);
            row_clone.hidden = false;
            row_clone.classList.remove('copy_div_step3_sel_lectures');
            row_clone.classList.add('div_step3_sel_lectures');
            // .lecture_icon
            row_clone.querySelector('.lecture_icon').src = '/images/' + sld.subject_function_code + '.svg';
            row_clone.querySelector('.student_lecture_detail_seq').value = sld.copy_pt_seq || sld.id
            row_clone.querySelector('.student_lecture_seq').value = sld.student_lecture_seq;
            row_clone.querySelector('.lecture_name').innerText = sld.lecture_name;
            // const before_txt = index >= 2 ? (index - 1) + '강.' : '';
            row_clone.querySelector('.lecture_detail_name').innerText = sld.lecture_detail_description;//before_txt + sld.lecture_detail_name;
            bundle.appendChild(row_clone);
            index++;
        });
    }

    // 캘린더 선택시 학습 학습내역 상세 닫기
    function learncalPopStudentLectureDetailClose(prams) {
        document.querySelectorAll(".tby_cal tr td .calnum").forEach(function(calnum) {
            calnum.classList.remove("active");
            calnum.classList.remove("bg-primary-y");
            calnum.classList.remove("text-white");
        });
        document.querySelector('[data-article-learncal-student-lecture-detail]').hidden = true;
    }

    function learncalPopStudentLectureBodyClose(event) {
        const specificArea = document.querySelector('#learncal_div_main');
        const article = document.querySelector('[data-article-learncal-student-lecture-detail]');
        if (!specificArea.contains(event.target) && !article.contains(event.target)) {
            document.querySelector('[data-article-learncal-student-lecture-detail]').hidden = true;
        }
    }

    // 캘린더 선택시 학습 학습내역 상세 보여주기.
    function learncalPopStudentLectureDetailOpen(vthis) {
        const article = document.querySelector('[data-article-learncal-student-lecture-detail]');
        // data-article-learncal-student-lecture-detail 의 내용을 채워준다.
        const student_seq = gl_students[0].id; //이부분은 단일만. 해야할듯.
        const sel_date = vthis.getAttribute('date');
        const page = "/manage/learning/study/planner/select";
        const parameter = {
            student_seqs: student_seq,
            search_start_date: sel_date,
            search_end_date: sel_date,
            select_type: 'no_group'
        };

        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                const details = result.student_lecture_details;
                const bundle = article.querySelector('[data-div-learncal-student-lecture-detail-bundle]');
                const row_copy = bundle.querySelector('[data-div-learncal-student-lecture-detail-row="copy"]').cloneNode(true);
                let status = "";
                bundle.innerHTML = '';
                bundle.appendChild(row_copy);
                let index = 0;
                details.forEach(function(detail) {
                    const today = new Date();
                    const selDate = new Date(detail.sel_date.split(' ')[0].replace(/-/g, ','));
                    if (detail.status == "ready") {
                        if (selDate < today && selDate.toDateString() !== today.toDateString()) {
                            status = "ready_old";
                        } else {
                            status = "ready";
                        }
                    } else if (detail.status == "complete") {
                        status = "complete";
                    } else if (detail.status == "study") {
                        if (selDate < today && selDate.toDateString() !== today.toDateString()) {
                            status = "ready_old";
                        } else {
                            status = "study";
                        }
                    }
                    const row_clone = row_copy.cloneNode(true);
                    row_clone.setAttribute('data-div-learncal-student-lecture-detail-row', 'clone');
                    row_clone.hidden = false;
                    row_clone.querySelector('[data-img-lecture-icon]').src = '/images/' + detail.subject_function_code + '.svg';
                    //const idx = index >= 2 ? (index-1) + '강.':'';
                    //row_clone.querySelector('[data-span-idx]').innerText = idx;
                    row_clone.querySelector('[data-span-lecture-detail-name]').innerText = detail.lecture_detail_name;
                    // let status = '미수강';
                    row_clone.querySelector('[data-span-status]').classList.add(status);
                    bundle.appendChild(row_clone);
                    index++;
                });
            } else {
                // console.log(result);
                // console.error(result.error);
            }
            // data-article-learncal-student-lecture-detail 를
            // vthis 의 위치 옆으로 이동

            article.style.top = vthis.closest('table').offsetTop + vthis.closest('td').offsetTop + 'px';
            article.style.left = vthis.closest('table').offsetLeft + vthis.closest('td').offsetLeft + vthis.closest('td').offsetWidth + 'px';
            article.hidden = false;
        });
    }

    document.addEventListener('click', learncalPopStudentLectureBodyClose);

    function learncalLectureCalendarLectureRow2(vthis) {
        event.stopPropagation();
        if (vthis.classList.contains('active')) {
            vthis.classList.remove('active');
        } else {
            vthis.classList.add('active');
        }
    }

    // 강의 드래그 앤 드롭
    var drag_target_tag = null;
    var drag_tag = null;

    function learncalCalendarDragStart(vthis) {
        //row
        vthis.querySelector('[data-lecture-subject-name]').classList.add('w-100');
        drag_tag = vthis;
        // vthis 를 제외한 모든 row2="clone" 숨김.
        vthis.closest('tbody').querySelectorAll('[data-div-calendar-lecture-row2="clone"]').forEach(function(row) {
            if (row != vthis) {
                // row.hidden = true;
                row.style.opacity = '0.3'; // 투명도를 줄여 시각적으로 구분
                row.style.pointerEvents = 'none'; // 드래그 중 다른 요소와의 상호작용 방지
            }
        });
        vthis.hidden = false;
    }

    function learncalCalendarDragEnd(vthis) {
        if (vthis != event.currentTarget) vthis = event.currentTarget;
        vthis.querySelector('[data-lecture-subject-name]').classList.remove('w-100');
        drag_tag = null;
        drag_target_tag = null;
        vthis.closest('tbody').querySelectorAll('[data-div-calendar-lecture-row2="clone"]').forEach(function(row) {
            // row.hidden = false;
            row.style.opacity = '1';
            row.style.pointerEvents = 'auto';
        });
        const btn_delete = document.querySelector('[data-btn-learncal-delete]');
        btn_delete.classList.remove('bg-secondary');
        btn_delete.classList.add('bg-danger');
    }

    function learncalCalendarDragEnter(vthis) {
        //vthis = td
        event.preventDefault();
        drag_target_tag = vthis;
        // 삭제버튼에 들어왔을때.
        if (vthis.getAttribute('data-btn-learncal-delete') != null) {
            vthis.classList.add('bg-secondary');
            vthis.classList.remove('bg-danger');
        } else {
            const bundle = drag_target_tag.querySelector('[data-div-calendar-lecture-bundle]');
            const drag_tag_clone = drag_tag.cloneNode(true);
            drag_tag_clone.classList.add('scale-bg-gray_03', 'rounded-4', 'clone_drag');
            drag_tag_clone.querySelector('[data-lecture-subject-name]').innerText = '';
            bundle.appendChild(drag_tag_clone);
        }
    }

    function learncalCalendarDragLeave(vthis) {
        event.preventDefault();
        // 삭제버튼에 들어왔을때.
        if (vthis.getAttribute('data-btn-learncal-delete') != null) {
            vthis.classList.remove('bg-secondary');
            vthis.classList.add('bg-danger');
        } else {
            vthis.querySelectorAll('.clone_drag').forEach(function(clone) {
                clone.remove();
            });
        }
    }

    function learncalCalendarDragOver(vthis) {
        event.preventDefault();
    }

    function learncalCalendarDragDrop(vthis) {
        event.preventDefault();
        const student_lecture_detail_seq = drag_tag.querySelector('[data-student-lecture-detail-seq]').value;
        const sel_date = drag_tag.closest('td').querySelector('.calnum').getAttribute('date').replace(/-/gi, '.');
        const lecture_name = drag_tag.querySelector('[data-lecture-subject-name]').innerText;
        const drag_tag2 = drag_tag;

        // 삭제버튼에 들어왔을때.
        if (vthis.getAttribute('data-btn-learncal-delete') != null) {
            sAlert('강의 삭제', sel_date + '의 (' + lecture_name + ') 을 삭제하시겠습니까?', 3, function() {
                learncalLectureDelete(student_lecture_detail_seq);
                drag_tag2.remove();
            });
        } else {
            if (drag_target_tag != null && vthis.querySelectorAll('.clone_drag').length > 0) {
                vthis.querySelectorAll('.clone_drag').forEach(function(clone) {
                    clone.remove();
                });
                const chg_date_val = vthis.querySelector('.calnum').getAttribute('date');
                const chg_date = chg_date_val.replace(/-/gi, '.');
                sAlert('강의 이동', sel_date + '의 (' + lecture_name + ') 을 ' + chg_date + ' 로 이동하시겠습니까?', 3, function() {
                    learncalLectureMoveDate(student_lecture_detail_seq, chg_date_val);
                    const bundle = vthis.querySelector('[data-div-calendar-lecture-bundle]');
                    bundle.appendChild(drag_tag2);
                });
            }
        }
    }

    // 강의 드래그로 이동.
    function learncalLectureMoveDate(student_lecture_detail_seq, chg_date) {
        const page = "/manage/learning/student/lecture/detail/move";
        const parameter = {
            student_lecture_detail_seq: student_lecture_detail_seq,
            chg_date: chg_date
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                toast('', '이동되었습니다.');
            } else {
                toast('', '이동에 실패하였습니다.');
            }
        });
    }
    // 강의 드래그로 삭제.
    function learncalLectureDelete(student_lecture_detail_seq) {
        const page = "/manage/learning/student/lecture/detail/delete";
        const parameter = {
            student_lecture_detail_seq: student_lecture_detail_seq
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                toast('', '삭제되었습니다.');
            } else {
                toast('', '삭제에 실패하였습니다.');
            }
        });
    }
    //script end
</script>

{{-- 달력 설정 --}}
<script>
    var selected_day = {
        year: '',
        month: '',
        week: '',
        day: '',
        date: ''
    };
    set_Calendar('#learncal_div_main');
    learncalMainCalendarStudyTimeDisplay();
    set_Calendar('#learncal_div_main2');
    learncalModalStudyTimeDisplay();
    learncalStudyPlannerSelect('date_group');
    // const myModal = new bootstrap.Modal(document.getElementById('learncal_modal_calendar'), {});
    // myModal.show();

    function set_Calendar(div_main_id) {
        var len = getWeekNo(getLastDay(div_main_id));
        const main_div = document.querySelector(div_main_id);

        main_div.querySelectorAll('.tby_cal td').forEach(function(element) {

            element.querySelectorAll('.div_study_time.active').forEach(function(element) {
                element.hidden = true;
                element.classList.remove('.active');
                element.classList.remove('bg-attend-time1');
                element.classList.remove('bg-goal-time1');
                element.querySelector('.study_time').innerText = '';
            });

            element.querySelectorAll('.calnum.act_study_time').forEach(function(element) {
                element.classList.remove('ctext-gc2-imp');
                element.classList.remove('act_study_time');
            });

            element.querySelectorAll('.calnum.active').forEach(function(element) {
                element.classList.remove('active');
                element.classList.remove('bg-primary-y');
                element.classList.remove('text-white');
            });

            element.querySelector('.calnum').innerHTML = '';
            element.querySelector('.calnum').removeAttribute('title');
            element.querySelector('.calnum').removeAttribute('data-original-title');

            // element.querySelector('.cal_month').className = 'cal_month';
            // element.querySelector('.cal_month').innerHTML = '';

            element.querySelectorAll('.cal_indiv').forEach(function(child1) {
                child1.innerHTML = '';
                child1.classList.add('hidden');
                child1.removeAttribute('title');
                child1.removeAttribute('data-original-title');
            });
        });


        var last_index = 0;
        var year = main_div.querySelector('.stp2_year').innerHTML;
        var month = main_div.querySelector('.stp2_month').innerHTML;

        for (var i = 1; i < len + 1; i++) {
            var num_date = get_SelWeesNoDate(div_main_id, i);
            var sel_date_str = num_date;
            var num_dateList = num_date.split('|');
            var indexDate = ["", "", "", "", "", "", ""];
            if (i == 1 && num_dateList.length - 7 != 0) {
                for (var ii = 0; ii < 7; ii++) {
                    var sumnum = 7 - num_dateList.length;
                    try {
                        main_div.querySelectorAll('.caltr_' + i + ' .day' + ((ii + 1) + sumnum) + ' .calnum').forEach(
                            function(element) {
                                element.innerHTML = num_dateList[ii].substr(8);
                                element.classList.add(num_dateList[ii].substr(8));
                            });

                        main_div.querySelectorAll('.caltr_' + i + ' .day' + ((ii + 1) + sumnum) + ' .cal_indiv')
                            .forEach(function(element) {
                                element.classList.remove('hidden');
                            });
                        var init_schedule = main_div.querySelectorAll('.caltr_' + i + ' .day' + ((ii + 1) + sumnum) +
                            ' .cal_schedule_list_wrapper > *');
                        for (let zz = 0; zz < init_schedule.length; zz++) {
                            if (init_schedule[zz].classList.contains('copy')) {
                                init_schedule[zz].remove();
                            }
                        }
                    } catch (e) {
                        // console.log(e.message);
                    }
                }
            } else {
                const tag_end_date = main_div.querySelector('.search_end_date');
                for (var ii = 0; ii < 7; ii++) {
                    try {
                        var num = '';
                        var dMonth = '';
                        datelist_date = num_dateList[ii];
                        num = num_dateList[ii].substr(8);
                        dMonth = num_dateList[ii].substr(5, 5);
                        // main_div.querySelectorAll('.caltr_' + i + ' .day' + (ii + 1) + ' .cal_month').forEach(function(
                        //     element) {
                        //     element.innerHTML = dMonth;
                        //     // element.classList.add(dMonth);
                        //     element.classList.add('hidden');
                        // });
                        main_div.querySelectorAll('.caltr_' + i + ' .day' + (ii + 1) + ' .calnum').forEach(function(
                            element) {
                            element.innerHTML = num;
                            element.setAttribute('date', datelist_date);
                            tag_end_date.value = datelist_date;
                            // element.classList.add(num);
                            element.style.color = '';
                            element.querySelectorAll('.cal_indiv').forEach(function(child) {
                                child.classList.remove('hidden');
                            });
                        });

                        var chk_holy_date = year + '-' + month + '-' + num;

                        if (month != num_dateList[ii].substr(5, 2)) {
                            main_div.querySelectorAll('.caltr_' + i + ' .day' + (ii + 1) + ' .calnum').forEach(function(
                                element) {
                                // element.closest('td').classList.add('bg-light');
                                element.classList.add('text-secondary');
                            });
                        } else {
                            main_div.querySelectorAll('.caltr_' + i + ' .day' + (ii + 1) + ' .calnum').forEach(function(
                                element) {
                                // element.closest('td').classList.remove('bg-light');
                                element.classList.remove('text-secondary');
                            });
                        }

                        var init_schedule = main_div.querySelectorAll('.caltr_' + i + ' .day' + (ii + 1) +
                            ' .cal_schedule_list_wrapper > *');
                        for (let z = 0; z < init_schedule.length; z++) {
                            if (init_schedule[z].classList.contains('copy')) {
                                init_schedule[z].remove();
                            }
                        }

                    } catch (e) {}
                }
            }
            last_index = i
        }
        if (last_index != 6) {
            main_div.querySelectorAll('.caltr_6').forEach(function(element) {
                element.classList.add('hidden');
            });
        } else {
            main_div.querySelectorAll('.caltr_6').forEach(function(element) {
                element.classList.remove('hidden');
            });
        }

        var now = new Date().format('yyyy-MM-dd');
        var nowyear = new Date().format('yyyy');
        var nowweek = getWeekNo(now);
        document.querySelector('#learncal_modal_calendar .week_cnt').innerText = nowweek + 1;
        var nowmonth = new Date().format('MM');
        var nowday = new Date().getDay() + 1;

        if (month == nowmonth && year == nowyear) {
            main_div.querySelectorAll('.tby_cal > tr:nth-child(' + nowweek + ') > td:nth-child(' + nowday + ') .calnum')
                .forEach(function(element) {
                    // element.style.borderRadius = '50%';
                    // element.classList.add('text-success');
                    // element.classList.add('fw-bold');
                });
        } else {
            main_div.querySelectorAll('.tby_cal > tr:nth-child(' + nowweek + ') > td:nth-child(' + nowday + ') .calnum')
                .forEach(function(element) {
                    element.style.border = '';
                    element.style.backgroundColor = '';
                    element.style.color = '';
                    element.style.textAlign = '';
                    element.style.padding = '';
                    element.style.borderRadius = '';
                    element.style.font = '';
                    element.style.marginRight = '';
                });
        }
        if (selected_day.month != month || selected_day.year != year) {
            main_div.querySelectorAll('.tby_cal > tr').forEach(function(row) {
                if (selected_day.week - 1 > 0)
                    row.children[selected_day.week - 1].children[selected_day.day - 1].querySelector(
                        '.cal_content').classList.add('hidden');
            });
        } else {
            main_div.querySelectorAll('.tby_cal > tr').forEach(function(row) {
                if (selected_day.week - 1 > 0) {
                    row.children[selected_day.week - 1].children[selected_day.day - 1].querySelector('.cal_content').classList.remove('hidden');
                }
            });
        }

        setTimeout(function() {
            td_Height(div_main_id);
            main_div.querySelector('#divStep2').style.display = '';
            main_div.querySelectorAll('#pc_main .divStep2_default').forEach(function(element) {
                element.style.display = '';
            });
        }, 100);
    }

    function getWeekNo(v_date_str) {

        var now = new Date();
        var firstDay = v_date_str.substr(0, 8) + '01';
        var weeno = getYearWeekNo(v_date_str) - getYearWeekNo(firstDay);
        return weeno + 1;
    }

    function getLastDay(div_main_id) {
        const main_div = document.querySelector(div_main_id);
        var year = main_div.querySelector('.stp2_year').innerHTML;
        var month = main_div.querySelector('.stp2_month').innerHTML;
        var now = new Date(year + '-' + month + '-01');
        var lastday = new Date(now.getYear() + 1900, now.getMonth() + 1, 0).format('yyyy-MM-dd');
        return lastday;
    }

    function getYearWeekNo(v_date_str) {
        var day = v_date_str;
        var splitDay = day.split("-");
        var startYearDay = '1/1/' + splitDay[0];
        var today = splitDay[1] + '/' + splitDay[2] + '/' + splitDay[0];
        var dt = new Date(startYearDay);
        var tDt = new Date(today);
        var diffDay = (tDt - dt) / 86400000; // Date 함수 기준 하루를 뭔 초로 나눴는지 모르겠음.
        // 1월 1일부터 현재날자까지 차이에서 7을 나눠서 몇주가 지났는지 확인을 함
        var weekDay = parseInt(diffDay / 7) + 1;
        // 요일을 기준으로 1월 1일보다 이전 요일이라면 1주가 더 늘었으므로 +1 시켜줌.
        if (tDt.getDay() < dt.getDay())
            weekDay += 1;

        return weekDay;
    }

    function get_SelWeesNoDate(div_main_id, num) {
        const main_div = document.querySelector(div_main_id);
        var yearElement = main_div.querySelector('.stp2_year');
        var monthElement = main_div.querySelector('.stp2_month');
        var year = yearElement.innerHTML;
        var month = monthElement.innerHTML;
        var now = new Date(year + '-' + month + '-01');
        var beforStr = new Date(now.getYear() + 1900, now.getMonth() + 1, 0).format('yyyy-MM-');
        var lastday = new Date(now.getYear() + 1900, now.getMonth() + 1, 0).format('dd') * 1;
        var renStr = '';
        var reStr = [];
        for (var i = 1; i <= lastday; i++) {
            if (num == getWeekNo(beforStr + ((i + 100) + '').substr(1))) {
                renStr += beforStr + ((i + 100) + '').substr(1) + '|';
            }
        }

        if (num == 1 && renStr.split('|').length - 1 != 7) {
            reStr = renStr.split('|');
            var len = 7 - (renStr.split('|').length - 1);
            var conStr = '';
            for (let i = len - 1; i > -1; i--) {
                conStr += new Date(year, month - 1, -i).format('yyyy-MM-dd') + '|'
            }
            reStr.forEach(d => {
                if (d != '') {
                    conStr += d + '|';
                }
            })
            renStr = conStr;
        } else if (renStr.split('|').length - 1 != 7) {
            reStr = renStr.split('|');
            var len = 7 - (renStr.split('|').length - 1);
            var conStr = '';
            reStr.forEach(d => {
                if (d != '') {
                    conStr += d + '|';
                }
            })
            for (let i = 1; i < len + 1; i++) {
                conStr += new Date(year, month, i).format('yyyy-MM-dd') + '|'
            }

            renStr = conStr;
        }


        renStr = renStr.substr(0, renStr.length - 1);
        return renStr;
    }

    function td_Height(div_main_id) {
        // const main_div = document.querySelector(div_main_id);
        // var chr = 0;
        // var tbyCals = main_div.querySelectorAll('.tby_cal tr');
        // if (tbyCals[5].classList.contains('hidden')) {
        //     chr = 4;
        //     var tbCalendars = main_div.querySelectorAll('.tb_calendar');
        //     tbCalendars.forEach(function(tbCalendar) {
        //         var tbyCals = tbCalendar.querySelectorAll('.tby_cal tr');
        //         tbyCals[5].style.display = 'none';
        //     });
        // } else {
        //     var trs = main_div.querySelectorAll('.tby_cal tr');
        //     trs[5].style.display = '';
        // }

        // var tds = main_div.querySelectorAll('.tb_calendar .tby_cal tr td');
        // var td_h1 = 600 / ((tds.length - chr) / 4);

        // var tbyCals = main_div.querySelectorAll('.tby_cal tr');
        // if (tbyCals.length == 6) {
        //     td_h1 = 600 / (tbyCals.length - (chr / 4));
        // }


        // //tbyCals의 가로사이즈가 600px가 안되면 td_h1 = 50px
        // if (tbyCals[0].offsetWidth < 600) {
        //     td_h1 = 40;
        // }
        // var tds = main_div.querySelectorAll('.tb_calendar .tby_cal tr td');
        // tds.forEach(function(td) {
        //     td.style.height = td_h1 + 'px';
        // });
    }

    function click_NextMon(div_main_id, type1, type2) {
        const main_div = document.querySelector(div_main_id);
        var first_type = ''
        if (type1 != 'today_key') {
            event.stopPropagation();
            first_type = type1;
        } else {
            first_type = 'today';
        }

        var selected_year = main_div.querySelectorAll('.stp2_year')[0].innerHTML;
        var selected_month = main_div.querySelectorAll('.stp2_month')[0].innerHTML;
        var current_year = new Date().format('yyyy');
        var current_month = new Date().format('MM');

        switch (first_type) {
            case "year":
                var year = parseInt(main_div.querySelector('.stp2_year').innerHTML);
                if (type2 == 'next') {
                    year++;
                } else if (type2 == 'prev') {
                    year--;
                } else {
                    // 그대로
                }
                main_div.querySelector('.stp2_year').innerHTML = year;
                break;
            case "month":
                var month = parseInt(main_div.querySelector('.stp2_month').innerHTML);
                if (type2 == 'next') {
                    if (month == 12) {
                        main_div.querySelector('.stp2_month').innerHTML = '01';
                        click_NextMon(div_main_id, 'year', 'next');
                        return;
                    }
                    month++;
                } else {
                    if (month == 1) {
                        main_div.querySelector('.stp2_month').innerHTML = '12';
                        click_NextMon(div_main_id, 'year', 'prev');
                        return;
                    }
                    month--;
                }
                month = (100 + month);
                main_div.querySelector('.stp2_month').innerHTML = (month + '').substr(1);
                break;
            case "today":
                var year = new Date().format('yyyy');
                var month = new Date().format('MM');

                main_div.querySelector('.stp2_year').innerHTML = year;
                main_div.querySelector('.stp2_month').innerHTML = month;
                break;
        }
        var weekno = main_div.querySelector('#tb_weeks .act .week_no')
        if (weekno != undefined)
            set_TimeTableDate(div_main_id, weekno.innerHTML);

        // selectTimeTable('all');
        // selectClass();
        var divClassSelActs = main_div.querySelectorAll('#divClassSel .act');
        if (divClassSelActs.lnegth > 0) {
            divClassSelActs.forEach(function(element) {
                element.classList.remove('act');
            });
        }
        var tbTimetableActs = main_div.querySelectorAll('#tb_timetableaca .act');
        if (tbTimetableActs.length > 0) {
            tbTimetableActs.forEach(function(element) {
                element.classList.remove('act');
            });
        }


        if (first_type == 'today' && selected_month != current_month) {
            set_Calendar(div_main_id);
            set_TbWeekCount(div_main_id);
            setTimeout(function() {
                main_div.querySelector('.tby_cal').style.display = '';
            }, 100);

            var year = main_div.querySelector('.stp2_year').innerHTML;
            var month = main_div.querySelector('.stp2_month').innerHTML;

            if (selected_day.week != '' && selected_day.day != '') {
                if (selected_day.month != month || selected_day.year != year) {
                    var calIndivs = main_div.querySelectorAll('.tby_cal > div:nth-child(' + selected_day.week +
                        ') > div:nth-child(' + selected_day.day + ') .cal_indiv');
                    calIndivs.forEach(function(calIndiv) {
                        calIndiv.classList.remove('hidden');
                    });

                    var ch = main_div.querySelectorAll('.tby_cal > div:nth-child(' + selected_day.week +
                        ') > div:nth-child(' + selected_day.day + ')');
                    ch[0].style.border = '1px solid #e3e6f0';
                } else {
                    var calIndivs = main_div.querySelectorAll('.tby_cal > div:nth-child(' + selected_day.week +
                        ') > div:nth-child(' + selected_day.day + ') .cal_indiv');
                    calIndivs.forEach(function(calIndiv) {
                        calIndiv.classList.add('hidden');
                    });

                    var ch = main_div.querySelectorAll('.tby_cal > div:nth-child(' + selected_day.week +
                        ') > div:nth-child(' + selected_day.day + ')');
                    ch[0].querySelector('.cal_content').classList.add('hidden');
                    ch[0].style.border = '2px solid #4e73df';
                }
            }
            var date = new Date().format('dd');
            var now = new Date().format('yyyy-MM-dd');
            var nowweek = getWeekNo(now);

            var today_dom = main_div.querySelectorAll('.cal_month.' + month + '-' + date);
            var today_parent = today_dom[0].parentNode.parentNode;
            var days = today_parent.className.replace('text-center day', '');
            if (days.indexOf('dAct') !== -1) {
                days = days.replace('dAct', '');
            }
            today_parent.classList.remove('dAct');

        } else if (first_type == 'today' && (selected_month == current_month) && (selected_year == current_year)) {
            var date = new Date().format('dd');
            var now = new Date().format('yyyy-MM-dd');
            var nowweek = getWeekNo(now);

            var today_dom = main_div.querySelectorAll('.cal_month.' + month + '-' + date);
            var today_parent = today_dom[0].parentNode.parentNode;
            var days = today_parent.className.replace('text-center day', '');
            if (days.indexOf('dAct') !== -1) {
                days = days.replace('dAct', '');
            }
            today_parent.classList.remove('dAct');

        } else {
            set_Calendar(div_main_id);
            set_TbWeekCount(div_main_id);

            var year = main_div.querySelector('.stp2_year').innerHTML;
            var month = main_div.querySelector('.stp2_month').innerHTML;

            if (selected_day.week != '' && selected_day.day != '') {
                if (selected_day.month != month || selected_day.year != year) {
                    var calIndivs = main_div.querySelectorAll('.tby_cal > div:nth-child(' + selected_day.week +
                        ') > div:nth-child(' + selected_day.day + ') .cal_indiv');
                    calIndivs.forEach(function(calIndiv) {
                        calIndiv.classList.remove('hidden');
                    });

                    var ch = main_div.querySelectorAll('.tby_cal > div:nth-child(' + selected_day.week +
                        ') > div:nth-child(' + selected_day.day + ')');
                    ch[0].style.border = '1px solid #e3e6f0';
                } else {
                    var calIndivs = main_div.querySelectorAll('.tby_cal > div:nth-child(' + selected_day.week +
                        ') > div:nth-child(' + selected_day.day + ') .cal_indiv');
                    calIndivs.forEach(function(calIndiv) {
                        calIndiv.classList.add('hidden');
                    });

                    var ch = main_div.querySelectorAll('.tby_cal > div:nth-child(' + selected_day.week +
                        ') > div:nth-child(' + selected_day.day + ')');
                    ch[0].querySelector('.cal_content').classList.add('hidden');
                    ch[0].style.border = '2px solid #2e59d9';
                }
            }
        }
    }

    function set_TbWeekCount(div_main_id) {
        const main_div = document.querySelector(div_main_id);
        var now = new Date().format('yyyy-MM-dd');
        var nowweek = getWeekNo(now);
        var is_act_len = main_div.querySelectorAll('#tb_weeks .act').length;

        var len = getWeekNo(getLastDay(div_main_id));
        for (var i = 1; i <= 6; i++) {
            if (i <= len) {
                var trElements = main_div.querySelectorAll('#tb_weeks .tr' + i);
                trElements.forEach(function(trElement) {
                    trElement.classList.remove('hidden');
                });
            } else {
                var trElements = main_div.querySelectorAll('#tb_weeks .tr' + i);
                trElements.forEach(function(trElement) {
                    trElement.classList.add('hidden');
                });
            }

            if (nowweek == i && is_act_len == 0) {
                var trElements = main_div.querySelectorAll('#tb_weeks .tr' + i);
                trElements.forEach(function(trElement) {
                    trElement.classList.add('act');
                });
                set_TimeTableDate(div_main_id, nowweek);
            }
        }
    }

    function set_TimeTableDate(div_main_id, weekno) {
        const main_div = document.querySelector(div_main_id);
        var num_date = get_SelWeesNoDate(div_main_id, weekno);
        var sel_date_str = num_date;
        var num_dateList = num_date.split('|');
        var indexDate = ["", "", "", "", "", "", ""];
        if (weekno == 1 && num_dateList.length - 7 != 0) {

            for (var ii = 0; ii < 7; ii++) {
                if ((ii + 1) + sumnum == 8)
                    break;
                var otThElements = main_div.querySelectorAll('.ot_th' + (ii + 1));
                otThElements.forEach(function(otThElement) {
                    otThElement.classList.add('hidden');
                });
                var sumnum = 7 - num_dateList.length;
                try {
                    var otThElements = main_div.querySelectorAll('.ot_th' + ((ii + 1) + sumnum));
                    otThElements.forEach(function(otThElement) {
                        otThElement.classList.remove('hidden');
                    });

                    var thElements = main_div.querySelectorAll('.th' + ((ii + 1) + sumnum));
                    thElements.forEach(function(thElement) {
                        thElement.innerHTML = num_dateList[ii].substr(8);
                    });
                } catch (e) {
                    // console.log(e.message);
                }
            }
        } else {
            for (var ii = 0; ii < 7; ii++) {
                try {
                    var num = '';
                    num = num_dateList[ii].substr(8);
                    var otThElements = main_div.querySelectorAll('.ot_th' + (ii + 1));
                    otThElements.forEach(function(otThElement) {
                        otThElement.classList.remove('hidden');
                    });

                    var thElements = main_div.querySelectorAll('.th' + (ii + 1));
                    thElements.forEach(function(thElement) {
                        thElement.innerHTML = num;
                    });
                } catch (e) {
                    var otThElements = main_div.querySelectorAll('.ot_th' + (ii + 1));
                    otThElements.forEach(function(otThElement) {
                        otThElement.classList.add('hidden');
                    });
                }
            }
        }
    }
    let ldIdx;
    let ldDescription;
    let ldName;

    function batchDeletePopup() {
        const calendar = `<input id="date-range53" class="d-none" size="30" type="" value="">
        <div id="date-range12-container"></div>
        <div class="px-3 pb-3">
        <div class="border-bottom pt-4 mb-4"></div>
        <p class="text-sb-20px mb-2 gray-color">요일 선택</p>
        <div class="row w-100">
        <div class="col-6 ps-0 pe-1">
            <label class="label-input-wrap w-100">
            <input type="text" class="modal-input border-gray rounded text-sb-18px w-100 px-2" placeholder="" id="startDate">
            </label>
        </div>
        <div class="col-6 ps-1 pe-0">
            <label class="label-input-wrap w-100">
            <input type="text" class="modal-input border-gray rounded text-sb-18px w-100 px-2" placeholder="" id="lastDate">
            </label>
        </div>
        `
        const modal = document.querySelector('#learncal_modal_match_deletion');
        modal.querySelector('.msg_content').innerHTML = calendar;

        const myModal = new bootstrap.Modal(document.getElementById('learncal_modal_match_deletion'), {});
        document.getElementById('learncal_modal_match_deletion').addEventListener('hidden.bs.modal', function (event) {
            matchModalCloseEv();
        });
        myModal.show();
        initializeDateRangePicker();
    }

    function matchModalDelete(){
        const modal = document.querySelector('#learncal_modal_match_deletion');
        const row = modal.querySelector('[data-row] .bg-goal-time2').closest('[data-row]');
        const start_search_date=$('#startDate').val().trim();
        const end_search_date = $('#lastDate').val().trim();
        const student_seq = gl_students[0].id;
        const lecture_seq = row.querySelector('.lecture_seq').value;
        const page = "/manage/learning/study/planner/distinct/delete";
        if(lecture_seq == ''){
            toast('댜시 선태해주세요.');
            return;
        }
        const parameter = {
            student_seqs: student_seq,
            start_search_date: start_search_date,
            end_search_date: end_search_date,
            lecture_seq:lecture_seq
        };

        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                learncalStudyPlannerSelect('');
                toast('삭제 완료.');
                modal.querySelector('[data-bs-dismiss="modal"]').click();
            } else {
                toast('삭제 실패.');
            }
        });
    }

    function matchModalCloseEv(){
        const modal = document.querySelector('#learncal_modal_match_deletion');
        modal.querySelector('.modal-content2')?.remove();
        modal.querySelector('.modal-dialog').style.width = '25%';
        modal.querySelector('.modal-dialog').style.maxWidth = '25%';
    }
    function matchModalCancel(){
        const modal = document.querySelector('#learncal_modal_match_deletion');
        const close_btn = modal.querySelector('[data-bs-dismiss="modal"]');
        close_btn.click();
    }

    var uniqueDetails;
    function initializeDateRangePicker() {
        $('#date-range53').dateRangePicker({
            container: '#date-range12-container',
            alwaysOpen: true,
            singleMonth: true,
            inline: true,
            language: 'ko',
            startOfWeek: 'monday',
            showTopbar: false,
            getValue: function(date) //날짜는 주의 첫 번째 날이 됩니다.
            {
                $($(this).next().find('.next')).on('click', function(e) {
                    datechange($(this))
                });
                $($(this).next().find('.prev')).on('click', function(e) {
                    datechange($(this))
                });
                var datechange = (value) => {
                    var hasFirstDateSelectedClass = value.parents('table').find('tbody tr td div.first-date-selected').length > 0;
                    var haslastDateSelectedClass = value.parents('table').find('tbody tr td div.last-date-selected').length > 0;
                    if (hasFirstDateSelectedClass || haslastDateSelectedClass) {
                        var firstday = value.parents('table').find('tbody tr td div.first-date-selected');
                        var lastday = value.parents('table').find('tbody tr td div.last-date-selected');
                        firstday.attr('data-day', firstday.text());
                        lastday.attr('data-day', lastday.text());
                    }
                }
            },
        }).on('datepicker-first-date-selected', function(event, obj) {
            additionalFunction(obj.date1.getTime() / 1000 * 1000, obj.date1.getDate());
            $("#startDate").val(new Date(obj.date1).toLocaleDateString('ko-KR', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit'
            }).replace(/\./g, '').replace(/\s/g, '.'));
        }).on('datepicker-change', function(event, obj) {
            additionalFunction(obj.date1.getTime() / 1000 * 1000, obj.date1.getDate());
            additionalFunction(obj.date2.getTime() / 1000 * 1000, obj.date2.getDate());
            $("#startDate").val(obj.value.split('to')[0].replace(/\-/g, '.'));
            $("#lastDate").val(obj.value.split('to')[1].replace(/\-/g, '.'));
            $("#learncal_modal_match_deletion .modal-dialog").css("max-width", "50%");
            $("#learncal_modal_match_deletion .modal-dialog").css("width", "50%");
            $(document).find('[role="document"]').removeClass('modal-dialog');
            const search_start_date = obj.value.split('to')[0].replace(/\-/g, '.');
            const search_end_date = obj.value.split('to')[1].replace(/\-/g, '.');
            const student_seq = gl_students[0].id;
            const page = "/manage/learning/study/planner/distinct/select";
            const parameter = {
                student_seqs: student_seq,
                search_start_date: search_start_date,
                search_end_date: search_end_date,
            };
            queryFetch(page, parameter, function(result) {
                // console.log(result.student_lecture_details)
                if ((result.resultCode || '') == 'success') {
                    uniqueDetails = result.student_lecture_details.reduce((acc, current) => {
                        const x = acc.find(item => item.lecture_detail_seq === current.lecture_detail_seq);
                        if (!x) {
                            return acc.concat([current]).filter(item => item.lecture_name !== null).sort((a, b) => a.lecture_name.localeCompare(b.lecture_name));
                        } else {
                            return acc;
                        }
                    }, []);
                    let html = ``;
                    uniqueDetails.forEach(element => {
                        html += `
                        <div class="row" data-row>
                        <input type="hidden" class="lecture_seq" value="${element.lecture_seq}">
                            <div class="col-12 rounded-4 mx-0 scale-bg-gray_01 py-1 d-flex align-items-center justify-content-between flex-nowrap">
                                <div class="d-flex align-items-center flex-nowrap gap-1">
                                    <img src="/images/${element.subject_function_code}.svg" alt="" style="width: 50px;">
                                    <span>${element.lecture_detail_name} ${element.lecture_detail_description}</span>
                                </div>
                                <span class="rounded-pill px-2" style="background-color: #F0F0F0; color: #000000;">${element.subject_name}</span>
                            </div>
                        </div>
                        `
                    });
                    const calendarHeight = $('#learncal_modal_match_deletion #date-range12-container').height();
                    $('#learncal_modal_match_deletion .cont-wrap').remove();
                    $("#learncal_modal_match_deletion .modal-body").addClass('d-flex');
                    $("#learncal_modal_match_deletion .modal-body").append(`<div class="modal-content2 cont-wrap ps-2 w-75 d-flex flex-column gap-2 overflow-auto" style="height: ${calendarHeight}px;">${html}</div>`);
                } else {
                    toast('', '조회실패.');
                }
            });

            $(document).on("click", '.cont-wrap > .row', function(event) {
                event.stopPropagation();
                let index = $(this).index();
                $('.cont-wrap .row div').removeClass('bg-goal-time2');
                $('.cont-wrap .row div').parent().addClass('scale-bg-gray_01');
                $(this).find('div').addClass('bg-goal-time2');
                $(this).find('div').parent().removeClass('scale-bg-gray_01');
                ldIdx = uniqueDetails[index].idx;
                ldDescription = uniqueDetails[index].lecture_detail_description;
                ldName = uniqueDetails[index].lecture_detail_name;
                // console.log(ldIdx, ldDescription, ldName)
            })

        });
    }

    function additionalFunction(time, day) {
        $(`[time="${time}"]`).attr('data-day', day)
    }

    // api 사용법 날짜만넣으면 됩니다.
    function addDateUpdate(first, last) { //임시적인 함수입니다. 추후 개발진행예정입니다.
        $("#date-range53").data('dateRangePicker').setDateRange('2024-03-10', '2024-03-25');
        $("#startDate").val('2024-03-10');
        $("#startDate").val('2024-03-25');
    }

    // Initialize the date range picker
    // initializeDateRangePicker();
    window.addEventListener('beforeunload', function (e) {
        if (document.querySelector('[data-div-learncal-timetable-row="clone"]')) {
            e.preventDefault();
            e.returnValue = '';
        }
    });
</script>
@endsection
