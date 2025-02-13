@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
소속 선택
@endsection
@section('add_css_js')
<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="{{ asset('js/owl.js') }}"></script>
<link href="{{ asset('css/owl1.css') }}" rel="stylesheet">
<link href="{{ asset('css/owl2.css') }}" rel="stylesheet">
@endsection
{{-- 추가 코드
    1. 오늘의 수업요약.
     --}}
{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<style>
    .modal-version {
        max-width: 592px;
    }

    .modal-version .half-right {
        background: #ffffff;
    }

    .modal-version .minus_icon {
        display: none
    }

    .modal-version.v2 {
        max-width: 1426px;
    }

    .modal-version.v2 .half-right {
        background: #F9F9F9;
    }

    .modal-version.v2 .p-title-right {
        display: none;
    }

    .modal-version.v2 .half-left {
        display: block !important;
    }

    .modal-version.v2 .plus_icon {
        display: none;
    }

    .modal-version.v2 .minus_icon {
        display: block;
    }


    a[data-tooltip] {
        position: relative;
        /* 말풍선 위치를 조정하기 위해 필요 */
        cursor: pointer;
        /* 마우스 커서를 포인터로 변경 */
        text-decoration: none;
    }

    a[data-tooltip]::after {
        content: attr(data-tooltip);
        /* data-tooltip 속성의 값을 말풍선에 표시 */
        position: absolute;
        bottom: 100%;
        /* 말풍선을 링크 위에 표시 */
        left: 50%;
        transform: translate(-50%, -60%);
        background-color: #333;
        /* 말풍선 배경색 */
        color: #fff;
        /* 말풍선 글자색 */
        padding: 5px 10px;
        border-radius: 4px;
        white-space: nowrap;
        /* 말풍선 텍스트가 줄바꿈되지 않도록 */
        opacity: 0;
        /* 초기 상태에서 말풍선 숨기기 */
        visibility: hidden;
        /* 초기 상태에서 말풍선 숨기기 */
        transition: opacity 0.2s ease-in-out;
        z-index: 10;
        /* 말풍선이 다른 요소 위에 표시되도록 */
    }

    a[data-tooltip]:hover::after {
        opacity: 1;
        /* 마우스를 올렸을 때 말풍선 표시 */
        visibility: visible;
        /* 마우스를 올렸을 때 말풍선 표시 */
    }

</style>
<div class="zoom_sm">
    <input type="hidden" data-main-teach-seq value="{{ session()->get('teach_seq') }}">
    <div class="sub-title">
        <h2 class="text-sb-42px">{{ session()->get('teach_name') }} 선생님 <span class="ht-make-title on text-r-20px py-2 px-3 ms-1">방과후</span></h2>
    </div>
    <div class="setion-block">
        <div class="sh-title-wrap align-items-sm-center justify-content-sm-between justify-content-start flex-column flex-sm-row">
            <div class="right-text">
                <img src="{{ asset('images/calendar_chk_icon.svg') }}" width="32">
                <p class="text-sb-28px">오늘의 수업 요약</p>
            </div>
            <div class="left-text">
                <p class="gray-color text-m-20px" data-main-now-time></p>
            </div>
        </div>

        <div class="row content-block row-gap-3">
            <div class="col-lg-3 col-sm-6 col-md-6">
                <div class="card-box h-100 px-4 py-3 mb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-row lh-base gray-color">
                            <p class="text-b-24px scale-text-gray_05" data-today-date></p>
                            <p class="text-b-24px scale-text-gray_05"><b class="black-color">신규 등록</b>입니다.</p>
                        </div>
                        <div>
                            <p class="gray-color text-m-20px"><span class="black-color text-sb-42px" data-main-new-cnt>{{ $new_cnt }}</span>명</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 col-md-6">
                <div class="card-box h-100 px-4 py-3 mb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-row lh-base gray-color">
                            <p class="text-b-24px scale-text-gray_05" data-today-date></p>
                            <p class="text-b-24px scale-text-gray_05"><b class="black-color">출석</b>입니다.</p>
                        </div>
                        <div>
                            <p class="gray-color text-m-20px"><span class="black-color text-sb-42px" data-main-attend-cnt>{{ $attend_cnt }}</span>명</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
                <div class="card-box h-100 px-4 py-3 mb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-row lh-base gray-color">
                            <p class="text-b-24px scale-text-gray_05" data-today-date></p>
                            <p class="text-b-24px scale-text-gray_05"><b class="black-color">결석</b>입니다.</p>
                        </div>
                        <div>
                            <p class="gray-color text-m-20px"><span class="black-color text-sb-42px" data-main-absent-cnt>{{ $absent_cnt }}</span>명</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6 mb-2">
                <div class="card-box h-100 px-4 py-3 mb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-row lh-base gray-color">
                            <p class="text-b-24px scale-text-gray_05" data-today-date></p>
                            <p class="text-b-24px scale-text-gray_05"><b class="black-color">보강</b>입니다.</p>
                        </div>
                        <div>
                            <p class="gray-color text-m-20px"><span class="black-color text-sb-42px" data-main-ref-cnt>{{ $ref_cnt }}</span>명</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="setion-block">
        <div class="sh-title-wrap justify-content-between">
            <div class="right-text">
                <img src="{{ asset('images/calendar_chk_icon.svg') }}" width="32">
                <p class="text-sb-28px">반 선택</p>
            </div>
            <div class="d-flex">
                <button class="btn p-0 col-auto border-0" data-btn-owl-prev="">
                    <img src="{{ asset('images/calendar_arrow_left.svg') }}" class="align-middle" width="32">
                </button>
                <button class="btn p-0 col-auto border-0" data-btn-owl-next="">
                    <img src="{{ asset('images/calendar_arrow_right.svg') }}" class="align-middle" width="32">
                </button>
            </div>
        </div>

        <div  data-bundle="div_class" class="row owl-carousel owl-theme">
            <div data-row="copy" hidden class="main-slider-card mb-3 w-100">
                <input type="hidden" data-class-seq>
                <input type="hidden" data-grade>
                <input type="hidden" data-team-code>
                <div class="card-box h-100 h-100 p-4 d-flex flex-column justify-content-between">
                    <div class="d-flex align-items-center justify-content-between">
                        <p class="d-flex align-items-center">
                            <span data-grade-name data-text="#5학년" class="ht-make-title text-r-18px py-1 px-3 me-1"></span>
                            <span class="text-b-20px" data-class-name data-text="#통합반"></span>
                        </p>
                        <div class="icon-wrap d-flex align-items-center">
                            <button type="button" class="qr-download-btn btn p-0" onClick="mainAfterQrCodeCreate(this);" data-tooltip="QR 코드 생성">
                                <img src="{{ asset('images/svg/qr-code.svg') }}" width="26">
                            </button>
                            {{-- <a href="javascript:void(0);" onclick="mainAfterModalClassSettingShow(this)" data-tooltip="정보 수정하기">
                                    <img src="{{ asset('images/svg/setting_grey.svg') }}" width="36">
                            </a> --}}
                        </div>
                    </div>
                    <div class="d-flex flex-column justify-content-between h-100">
                        <ul class="py-3 d-flex h-100 flex-column justify-content-between">
                            <li class="align-items-top py-4">
                                <div class="d-flex justify-content-between align-items-center mb-12">
                                    <p class="gray-color text-m-20px">매주</p>
                                    <p data-class-room data-text="#방과후 강의실 2" class="text-white text-m-16px rounded-pill scale-bg-black scale-text-white py-1 px-3">
                                    </p>
                                </div>
                                <div class="" data-bundle="class_time">
                                    <div data-row="in_copy" hidden class="justify-content-end align-items-center gap-2">
                                        <p data-class-day class="black-color text-sb-16px rounded-pill scale-bg-white border-gray scale-text-gray_05 py-1 px-3 d-none">
                                            월요일</p>
                                        <p data-class-start-end-time class="black-color text-sb-16px rounded-pill scale-bg-white border-gray scale-text-gray_05 py-1 px-3">
                                            15:00-15:30</p>
                                        <input type="hidden" data-class-start-time>
                                        <input type="hidden" data-class-end-time>
                                        <input type="hidden" data-class-interval>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex align-items-top justify-content-between">
                                <ul class="d-flex align-items-center flex-column w-100">
                                    <li class="d-flex align-items-center justify-content-between w-100 border-gray-bottom border-gray-top py-3">
                                        <p class="gray-color text-m-20px">접속 학생</p>
                                        <div class="text-sb-20px scale-text-gray_05">
                                            <b class="scale-text-black">
                                                <span data-class-now-cnt>0</span>명</b> / <span data-class-student-cnt>0</span>명
                                        </div>
                                    </li>
                                    <li class="d-flex align-items-top justify-content-between w-100 py-3">
                                        <p class="gray-color text-m-20px">오늘 보강</p>
                                        <div class="text-sb-20px scale-text-gray_05">
                                            <b class="scale-text-black">
                                                <span data-class-reinf-cnt data-text="#보강수">0</span>명</b>
                                        </div>
                                    </li>
                                </ul>
                            </li>

                        </ul>
                        <div class="button-wrap button-position">
                            <button onclick="mainAfterModalClassSettingShow(this)" class="gray-color text-b-24px mb-12 primary-bg-mian-hover scale-text-white-hover">정보
                                수정하기</button>
                            <button onclick="mainAfterClassStart(this)" class="gray-color text-b-24px primary-bg-mian-hover scale-text-white-hover scale-bg-gray_01">수업
                                시작하기</button>
                        </div>
                    </div>
                </div>
            </div>
            <div data-row="add_class" style="min-height:500px" class="main-slider-card mb-2 w-100">
                <div class="card-box h-100 h-100 p-4 scale-bg-gray_01 all-center" style="border: 2px dotted;border-style: dashed;border-color: #e5e5e5;">
                    <button class="btn h-center" onclick="mainAfterModalClassSettingShow();">
                        <img src="{{ asset('images/gray_plus_icon.svg') }}" style="width:32px;">
                        <span class="text-b-24px scale-text-gray_05 col-auto">반 추가하기</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- 모달 / 반 설정 --}}
<div class="modal fade" id="main_after_modal" tabindex="-1" aria-labelledby="exampleModalLabel" style="display:none;" aria-modal="true" role="dialog">
    <div class="modal-dialog rounded modal-xl modal-version">
        <div class="modal-content border-none rounded modal-shadow-style p-0 overflow-hidden">
            <div class="modal-header border-bottom-0 d-none">
            </div>
            <div class="modal-body p-0">
                <div class="d-flex half-modal">
                    <div class="half-left" style="flex: 0 0 auto;width: 58.33333333%;display:none;">
                        <div class="half-left-content-title px-4 pt-4 row mx-0 mb-4">
                            <p class="col modal-title fs-5 text-b-24px h-center p-title-left" id="">
                                <img src="{{ asset('images/gear_icon.svg') }}" width="32">
                                반 설정
                            </p>
                        </div>
                        <div class="half-date">
                            <div class="px-4">
                                <div class="d-flex">
                                    <div class="d-inline-block select-wrap select-icon h-62 pe-6">
                                        <select class="border-gray lg-select text-sb-20px h-62" data-search-grade>
                                            <option value="">전체 학년</option>
                                            {{-- grade --}}
                                            @if (!empty($grade_codes))
                                            @foreach ($grade_codes as $grade)
                                            <option value="{{ $grade->id }}">{{ $grade->code_name }}
                                            </option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <label class="label-search-wrap ps-6 col">
                                        <input type="text" data-search-student-name onkeyup="if(event.keyCode == 13) mainAfterStudentSelect();" class="lg-search border-gray rounded text-m-20px w-100" placeholder="학생 이름을 검색해주세요.">
                                    </label>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-52">
                                    <label class="toggle d-flex">
                                        <input type="checkbox" class="" checked="" data-no-class>
                                        <span class=""></span>
                                        <p class="text-sb-20px ms-2">반 미배정 보기</p>
                                    </label>
                                    <button type="button" onclick=" mainAfterAddChkStudents();" class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black px-4">학생
                                        목록에 추가</button>
                                </div>
                                <div class="table-box-wrap">
                                    <div class="d-flex mt-32 table-x-scroll table-scroll w-100">
                                        <table class="table-style w-100" style="min-width: 100%;">
                                            <colgroup>
                                                <col style="width: 80px;">

                                            </colgroup>
                                            <thead>
                                                <tr class="text-sb-20px modal-shadow-style rounded">
                                                    <th style="width: 80px">
                                                        <label class="checkbox mt-1">
                                                            <input type="checkbox" onchange="mainAfterAllChk(this)">
                                                            <span class="">
                                                            </span>
                                                        </label>
                                                    </th>
                                                    <th>구분</th>
                                                    <th>이름</th>
                                                    <th>학년/반</th>
                                                    <th>등록날짜</th>
                                                </tr>
                                            </thead>
                                            <tbody data-bundle="tby_students">
                                                <tr class="text-m-20px" data-row="copy" hidden>
                                                    <input type="hidden" data-student-seq>
                                                    <td class=" py-2">
                                                        <label class="checkbox mt-1">
                                                            <input type="checkbox" data-chk>
                                                            <span class="">
                                                            </span>
                                                        </label>
                                                    </td>
                                                    <td class=" py-2">
                                                        <p data-is-class data-text="#미배정"></p>
                                                    </td>
                                                    <td class=" py-2">
                                                        <p data-student-name data-text="#학생이름"></p>
                                                    </td>
                                                    <td class=" py-2">
                                                        <span data-grade-name data-text="#학년"></span>
                                                        <span data-class-name data-text="#반"></span>
                                                    </td>
                                                    <td class=" py-2" data-created-at data-text="#등록날짜"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                    <div id="sticker" class="half-right px-32 overflow-hidden rounded-end-3" style="top: 0px; position: relative;flex:100%">
                        <div class="half-right-content-title mb-4 mt-4">
                            <p class="col modal-title fs-5 text-b-24px h-center p-title-right" id="">
                                <img src="{{ asset('images/gear_icon.svg') }}" width="32">
                                반 설정
                            </p>
                            <div class="col-auto">
                                <button type="button" class="btn h-center p-0 btn-close-right" data-bs-dismiss="modal" aria-label="Close">
                                    <img src="{{ asset('images/black_x_icon.svg') }}" width="32">
                                </button>
                            </div>
                        </div>
                        <div class="half-wrap ">


                            <input type="hidden" data-class-seq>
                            <button type="button" onclick="mainAfterClassSetting();" class="btn-ms-secondary justify-content-between px-4 text-sb-20px rounded-3 scale-bg-white div-shadow-style scale-text-black w-100 mb-52">
                                <span>학생목록 추가 및 삭제</span>
                                <img src="{{ asset('images/black_plus_icon.svg?1') }}" width="24" class="plus_icon">
                                <img src="{{ asset('images/black_minus_icon.svg?1') }}" width="24" class="minus_icon">
                            </button>
                            <p class="text-sb-20px mb-3">반 이름</p>
                            <div class="row w-100 mx-0">
                                <div class="col-12 px-0 mb-32">
                                    <label class="label-input-wrap w-100">
                                        <input type="text" data-class-name class="smart-ht-search border-gray rounded text-m-20px w-100" placeholder="반 이름을 입력해주세요.">
                                    </label>
                                </div>
                            </div>
                            <p class="text-sb-20px mb-3">수업 장소</p>
                            <div class="row w-100 mx-0">
                                <div class="col-12 px-0 mb-32">
                                    <label class="label-input-wrap w-100">
                                        <input type="text" data-class-room class="smart-ht-search border-gray rounded text-m-20px w-100" placeholder="수업장소를 입력해주세요.">
                                    </label>
                                </div>
                            </div>
                            <p class="text-sb-20px mb-3">학년 선택</p>
                            <div class="row w-100 mx-0">
                                <div class="col-12 px-0 mb-32">
                                    <div class="d-inline-block select-wrap select-icon w-100 mb-12 scale-bg-white">
                                        <select class="border-gray lg-select text-sb-20px w-100" data-grade>
                                            <option value="">학년을 선택해주세요.</option>
                                            @if (!empty($grade_codes))
                                            {{-- value = grade id / text = grade code_name --}}
                                            @foreach ($grade_codes as $grade)
                                            <option value="{{ $grade->id }}">{{ $grade->code_name }}
                                            </option>
                                            @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <p class="text-sb-20px mb-3">수업 설정</p>
                            <div class="d-inline-block select-wrap select-icon w-100 mb-12 scale-bg-white">
                                <select class="border-gray lg-select text-sb-20px w-100" data-class-info="sel_days">
                                    <option value="">요일을 선택해주세요.</option>
                                    <option value="0">일요일</option>
                                    <option value="1">월요일</option>
                                    <option value="2">화요일</option>
                                    <option value="3">수요일</option>
                                    <option value="4">목요일</option>
                                    <option value="5">금요일</option>
                                    <option value="6">토요일</option>
                                </select>
                            </div>
                            <div class="d-flex align-items-center justify-content-between mb-32">
                                <div class="d-inline-block select-wrap select-icon scale-bg-white me-12 flex-fill rounded">
                                    <select class="border-gray lg-select text-sb-20px scale-text-gray_05 w-100" data-class-info="start_time_hour">
                                        <option value="0">00시</option>
                                        @foreach (range(6, 24) as $item)
                                        <option value="{{ $item }}">{{ $item }}시</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="d-inline-block select-wrap select-icon scale-bg-white flex-fill rounded">
                                    <select class="border-gray lg-select text-sb-20px scale-text-gray_05 w-100" data-class-info="start_time_min">
                                        <option value="0">00분</option>
                                        {{-- 10분단위 --}}
                                        @foreach (range(10, 50, 10) as $item)
                                        <option value="{{ $item }}">{{ $item }}분</option>
                                        @endforeach
                                    </select>
                                    </select>
                                </div>
                                <span class="ms-12 me-12">부터</span>
                                <div class="d-inline-block select-wrap select-icon flex-fill scale-bg-white rounded">
                                    <select class="border-gray lg-select text-sb-20px scale-text-gray_05 w-100" data-class-info="study_time_interval" onchange="mainAfterStudyTimeSelectEnd(this)">
                                        <option value="0">시간선택</option>
                                        <option value="50">50분</option>
                                        <option value="80">80분</option>
                                        {{-- 70 부터 10분단위로 120분 까지 --}}
                                        {{-- @foreach (range(70, 120, 10) as $item)
                                                 <option value="{{ $item }}">{{ $item }}분</option>
                                        @endforeach --}}
                                    </select>
                                    </select>
                                </div>
                            </div>

                            <p class="text-sb-20px mb-3">수업 일정</p>
                            <div class="text-sb-18px scale-text-gray_05 p-4" data-study-time-no-list>
                                수업을 추가해주세요.
                            </div>
                            <div data-div-main-after-study-time-bundle>
                                <div data-div-main_after-study-time-row="copy" hidden class="row scale-bg-gray_01 border rounded w-100 px-4 py-20 mx-0 mt-12">
                                    <span data-study-time-str class="col h-center text-sb-20px scale-text-gray_05"></span>
                                    <button class="col-auto btn p-0 h-center" onclick="mainAfterStudyTimeRemove(this);">
                                        <img src="{{ asset('/images/black_x_icon.svg') }}" width=24>
                                    </button>
                                    <input type="hidden" data-study-time-day>
                                    <input type="hidden" data-study-time-start>
                                    <input type="hidden" data-study-time-end>
                                    <input type="hidden" data-study-time-interval>
                                </div>
                            </div>

                            <p class="text-sb-20px mt-32 mb-3">학생 목록</p>
                            <div class="text-sb-18px scale-text-gray_05 p-4" data-student-no-list>
                                학생을 추가해주세요.
                            </div>
                            <div data-div-main-after-student-bundle>
                                <div data-div-main-after-student-row="copy" hidden class="row scale-bg-gray_01 border rounded w-100 px-4 py-20 mx-0 mt-12">
                                    <span data-student-info-str class="col h-center text-sb-20px scale-text-gray_05"></span>
                                    <button class="col-auto btn p-0 h-center" onclick="mainAfterStudentListRemove(this)">
                                        <img src="{{ asset('/images/black_x_icon.svg') }}" width=24>
                                    </button>
                                    <input type="hidden" data-student-seq>
                                </div>
                            </div>
                            <div class="row w-100 mt-80 pb-4">
                                <div class="col-6 ps-0 pe-6">
                                    <button type="button" onclick="this.closest('#sticker').querySelector('.btn-close-right').click();" class="btn-lg-secondary text-b-24px rounded scale-bg-white scale-text-gray_05 w-100 justify-content-center">취소하기</button>
                                </div>
                                <div class="col-6 ps-6 pe-0 ">
                                    <button type="button" data-btn-modal-save onclick="mainAfterClassInsert();" class="btn-lg-primary text-b-24px rounded scale-text-white w-100 text-center justify-content-center">
                                        변경 사항 저장</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 py-0 px-3 pb-2 mt-52 d-none">

            </div>
        </div>
    </div>

    {{-- :이동 폼form --}}
    <form action="/teacher/main/after/class/start" data-form-class-start hidden>
        @csrf
        <input name="class_seq">
        <input name="teach_seq">
        <input name="team_code">
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 반 가져오기 SELECT
        mainAfterClassSelect();
        // 현재 시간 넣어주기.
        const now_time_el = document.querySelector('[data-main-now-time]');
        now_time_el.innerText = new Date().format('yy년 MsM월 dsd일 e a/p hsh시 mm분') + ' 기준';

        const now_date = new Date().format('yy년 MsM월 dsd일');
        const today_date = document.querySelectorAll('[data-today-date]');
        today_date.forEach(el => {
            el.innerText = now_date;
        });

        {{-- let browserWidth = function() {
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
        }); --}}
    });

            // :뒤로간페이지
            document.addEventListener('visibilitychange', function(event) {
                if (sessionStorage.getItem('isBackNavigation') === 'true') {
                    // 여기에 뒤로 가기 버튼을 클릭한 후 페이지가 로드되었을 때 실행할 코드를 작성합니다.
                    sessionStorage.removeItem('isBackNavigation'); // 상태를 초기화합니다.

                    // 반 가져오기 SELECT
                    mainAfterClassSelect();
                }
            });

            // 모달 클리어.
            function mainAfterModalClear() {
                const modal = document.querySelector('#main_after_modal');
                modal.querySelector('[data-class-seq]').value = '';
                modal.querySelectorAll('[data-class-name]').forEach(el => el.value = '');
                modal.querySelector('[data-class-room]').value = '';
                modal.querySelector('[data-grade]').value = '';

                // 수업시간 초기화.
                mainAfterStudyTimeReset();
                // data-div-main_after-study-time-row="clone"
                const study_time_rows = modal.querySelectorAll('[data-div-main_after-study-time-row="clone"]');
                study_time_rows.forEach(row => {
                    row.remove();
                });
                document.querySelector('[data-study-time-no-list]').hidden = false;

                // 학생초기화.
                const student_rows = modal.querySelectorAll('[data-div-main-after-student-row="clone"]');
                student_rows.forEach(row => {
                    row.remove();
                });
                document.querySelector('[data-student-no-list]').hidden = false;

            }
            // 반 수정/추가 모달 오픈.
            function mainAfterModalClassSettingShow(vthis) {
                //모달 클리어.
                mainAfterModalClear();
                const modal = document.querySelector('#main_after_modal');
                const btn_save = modal.querySelector('[data-btn-modal-save]');
                // 수정 모드
                if (vthis != undefined) {
                    //정보 가져오기.
                    const row = vthis.closest('[data-row="clone"]');
                    const class_seq = row.querySelector('[data-class-seq]').value;
                    const class_name = row.querySelector('[data-class-name]').innerText;
                    const class_room = row.querySelector('[data-class-room]').innerText;
                    const grade = row.querySelector('[data-grade]').value;
                    const study_times = [];
                    const study_time_rows = row.querySelectorAll('[data-row="in_clone"]');
                    study_time_rows.forEach(row => {
                        const day = row.querySelector('[data-class-day]').innerText;
                        const start_end_time = row.querySelector('[data-class-start-end-time]').innerText;
                        const interval = row.querySelector('[data-class-interval]').value;
                        const start_time = row.querySelector('[data-class-start-time]').value;
                        const end_time = row.querySelector('[data-class-end-time]').value;
                        study_times.push({
                            day: day
                            , start_end_time: start_end_time
                            , interval: interval
                            , start_time: start_time
                            , end_time: end_time
                        });
                    });
                    let students = [];
                    if (main_class_mates[class_seq]) {
                        students = main_class_mates[class_seq];
                    }

                    const parameter = {
                        class_seq: class_seq
                        , class_name: class_name
                        , class_room: class_room
                        , grade: grade
                        , study_times: study_times
                        , students: students
                    };

                    // 정보 세팅.
                    mainAfterModalSetInfo(parameter);

                    btn_save.innerText = '변경 사항 저장';
                }
                // 삽입 모드
                else {
                    btn_save.innerText = '반 추가하기';
                }

                const myModal = new bootstrap.Modal(document.getElementById('main_after_modal'), {
                    keyboard: false
                    , backdrop: 'static'
                });
                myModal.show();
                if (modal.classList.contains('v2')) {
                    mainAfterStudentSelect();
                }
            }
            //
            function mainAfterClassSetting() {
                // [data-modal-main-after-class-setting] 의 .modal-version .v2를 토글
                const modal = document.querySelector('#main_after_modal .modal-version');
                modal.classList.toggle('v2');

                // 열리면 학생 자동 불러오기.
                if (modal.classList.contains('v2')) {
                    mainAfterStudentSelect();
                }
            }

            // 수업설정.
            function mainAfterStudyTimeSelectEnd(vthis) {
                // 선택된 요일, 시, 분, 부터 경과시간(interval)을 가져와서
                // bundle 의 row 를 복사해서 붙여넣는다.
                const sel_days = document.querySelector('[data-class-info="sel_days"]').value;
                const start_time_hour = document.querySelector('[data-class-info="start_time_hour"]').value;
                const start_time_min = document.querySelector('[data-class-info="start_time_min"]').value;
                const study_time_interval = document.querySelector('[data-class-info="study_time_interval"]').value;
                if (sel_days.length < 1 || start_time_hour.length < 1 || start_time_min.length < 1 || study_time_interval
                    .length < 1) {
                    toast('수업시간을 선택해주세요.');
                    document.querySelector('[data-class-info="study_time_interval"]').value = 0;
                    return;
                }
                const select_index = document.querySelector('[data-class-info="sel_days"]').options.selectedIndex;
                const sel_days_str = document.querySelector('[data-class-info="sel_days"]').options[select_index].text;
                // 시작시간 시:분 에서 interval 분을 더해서 종료시간을 계산한다.
                let end_time = new Date();
                end_time.setHours(start_time_hour);
                end_time.setMinutes(start_time_min);
                end_time.setMinutes(end_time.getMinutes() + parseInt(study_time_interval));
                const end_time_str = `${end_time.getHours()}:${end_time.getMinutes().toString().padStart(2, '0')}`;

                // 10단위 밑 분은 앞에 0을 붙여준다.
                const study_time_str =
                    `${sel_days_str} ${start_time_hour}:${start_time_min.padStart(2, '0')} - ${end_time_str} (${study_time_interval}분)`;
                const div_bundle = document.querySelector('[data-div-main-after-study-time-bundle]');
                const row_clone = document.querySelector('[data-div-main_after-study-time-row="copy"]').cloneNode(true);
                row_clone.setAttribute('data-div-main_after-study-time-row', 'clone');
                row_clone.hidden = false;
                row_clone.querySelector('[data-study-time-str]').innerText = study_time_str;
                row_clone.querySelector('[data-study-time-day]').value = sel_days;
                row_clone.querySelector('[data-study-time-start]').value = `${start_time_hour}:${start_time_min}`;
                row_clone.querySelector('[data-study-time-end]').value = end_time_str;
                row_clone.querySelector('[data-study-time-interval]').value = study_time_interval;
                // 이미 추가된 요일이 있는지 확인.
                is_already_added = false
                document.querySelectorAll('[data-div-main_after-study-time-row="clone"]').forEach(el => {
                    if (el.querySelector('[data-study-time-day]').value == sel_days_str ||
                        el.querySelector('[data-study-time-str]').innerText.indexOf(sel_days_str) != -1) {
                        is_already_added = true;
                    }
                });
                if (is_already_added) {
                    toast('이미 추가된 요일입니다.');
                } else
                    div_bundle.appendChild(row_clone);

                // 수업을 추가해주세요. 문구 숨김처리.
                document.querySelector('[data-study-time-no-list]').hidden = true;
                // 수업설정 초기화 / 다시 선택을 해주기 위해서.
                mainAfterStudyTimeReset();
            }

            // 수업시간 초기화.
            function mainAfterStudyTimeReset() {
                // 수업시간을 초기화 한다.
                document.querySelector('[data-class-info="sel_days"]').value = '';
                document.querySelector('[data-class-info="start_time_hour"]').value = 0;
                document.querySelector('[data-class-info="start_time_min"]').value = 0;
                document.querySelector('[data-class-info="study_time_interval"]').value = 0;
            }

            // 수업시간 삭제.
            function mainAfterStudyTimeRemove(vthis) {
                const row = vthis.closest('[data-div-main_after-study-time-row="clone"]');
                row.remove();

                // 만약 clone 이 한개도 없으면 수업을 추가해주세요. 문구를 보여준다.
                const div_bundle = document.querySelector('[data-div-main-after-study-time-bundle]');
                if (div_bundle.querySelectorAll('[data-div-main_after-study-time-row="clone"]').length < 1) {
                    document.querySelector('[data-study-time-no-list]').hidden = false;
                }
            }

            // 수업 저장.
            function mainAfterClassInsert() {
                const modal = document.querySelector('#main_after_modal');
                const rtn_data = mainAfterChkBeforeClassInsert();
                if (rtn_data.code != 'success') {
                    toast(rtn_data.msg);
                    return;
                }

                const page = "/teacher/main/after/class/insert";
                const parameter = rtn_data.parameter;
                queryFetch(page, parameter, function(result) {
                    if (result.class_seq) {
                        modal.querySelector('[data-class-seq]').value = result.class_seq;
                    }
                    if ((result.resultCode || '') == 'success') {
                        //div text-sm-28
                        const msg =
                            `
              <div class="text-sb-28px">
                <p>반이 저장되었습니다.</p>
              </div>
              `;
                        sAlert('', msg, 4);
                        // 모달 닫기.
                        modal.querySelector('.btn-close-right').click();
                        // 반 가져오기 SELECT
                        mainAfterClassSelect();
                    } else {
                        toast('다시 시도해주세요.');
                    }
                });
            }

            // 수업 저장 전 체크.
            function mainAfterChkBeforeClassInsert() {
                const modal = document.querySelector('#main_after_modal #sticker');
                const class_seq = modal.querySelector('[data-class-seq]').value;
                const class_name = modal.querySelector('[data-class-name]').value;
                const class_room = modal.querySelector('[data-class-room]').value;
                const grade = modal.querySelector('[data-grade]').value;
                const weeks = ['일', '월', '화', '수', '목', '금', '토'];
                const class_study_dates = [];
                const study_time_rows = modal.querySelectorAll('[data-div-main_after-study-time-row="clone"]');
                study_time_rows.forEach(row => {
                    const day_num = row.querySelector('[data-study-time-day]').value;
                    let day = weeks[day_num];
                    if (day == undefined) day = day_num.replace('요일', '');
                    const start = row.querySelector('[data-study-time-start]').value;
                    const end = row.querySelector('[data-study-time-end]').value;
                    const interval = row.querySelector('[data-study-time-interval]').value;
                    class_study_dates.push({
                        day: day
                        , start_time: start
                        , end_time: end
                        , interval: interval
                    });
                });
                const student_seqs = [];
                const student_rows = modal.querySelectorAll('[data-div-main-after-student-row="clone"]');
                student_rows.forEach(row => {
                    const student_seq = row.querySelector('[data-student-seq]').value;
                    student_seqs.push(student_seq);
                });

                // 반 이름 확인.
                if (class_name.length < 1) {
                    return {
                        code: 'error'
                        , msg: '반 이름을 입력해주세요.'
                    };
                }
                // 수업장소 확인.
                if (class_room.length < 1) {
                    return {
                        code: 'error'
                        , msg: '수업장소를 입력해주세요.'
                    };
                }
                // 학년 확인.
                if (grade.length < 1) {
                    return {
                        code: 'error'
                        , msg: '학년을 선택해주세요.'
                    };
                }
                // 수업일자 확인.
                if (class_study_dates.length < 1) {
                    return {
                        code: 'error'
                        , msg: '수업일자를 추가해주세요.'
                    };
                }
                // 학생 확인.
                // if(student_seqs.length < 1){
                //     return {code: 'error', msg: '학생을 추가해주세요.'};
                // }

                // 저장.
                const rtn = {};
                rtn.code = 'success';
                rtn.msg = '저장되었습니다.';
                rtn.parameter = {
                    class_seq: class_seq
                    , class_name: class_name
                    , class_room: class_room
                    , grade: grade
                    , class_study_dates: class_study_dates
                    , student_seqs: student_seqs
                };
                return rtn;
            }
            let main_class_mates = {};
            let main_bundle_clone = null;
            let main_after_row_clone = null;
            // 반 선택 리스트 불러오기.
            function mainAfterClassSelect() {
                const teach_seq = document.querySelector('[data-main-teach-seq]').value;
                const page = "/teacher/main/after/class/select";
                const parameter = {
                    teach_seq
                };

                queryFetch(page, parameter, function(result) {
            let bundle = document.querySelector('[data-bundle="div_class"]');
            if(main_bundle_clone){
                bundle.outerHTML =  main_bundle_clone.outerHTML;
                bundle = document.querySelector('[data-bundle="div_class"]');
            }else{
                main_bundle_clone = bundle.cloneNode(true);
            }
                    const copy_row = bundle.querySelector('[data-row="copy"]').cloneNode(true);

                    // 기존 clone rows 삭제
                    bundle.querySelectorAll('[data-row="copy"]').forEach(row => row.remove());
                    bundle.querySelectorAll('[data-row="clone"]').forEach(row => row.remove());

                    // add_class 앞에 copy_row 추가
                    // bundle.querySelector('[data-row="add_class"]').before(copy_row);

                    let idx = 0;
                    main_class_mates = {};

                    if (result.classes?.length) {
                        result.classes.forEach(cls => {
                            const class_seq = cls.id;
                            const row = copy_row.cloneNode(true);

                            row.hidden = false;
                            row.setAttribute('data-row', 'clone');
                            row.setAttribute('data-idx-group', Math.floor(idx / 4));

                            // 기본 정보 세팅
                            row.querySelector('[data-class-seq]').value = class_seq;
                            row.querySelector('[data-grade]').value = cls.grade;
                            row.querySelector('[data-team-code]').value = cls.team_code;
                            row.querySelector('[data-grade-name]').innerText = cls.grade_name;
                            row.querySelector('[data-class-name]').innerText = cls.class_name;
                            row.querySelector('[data-class-room]').innerText = cls.class_room;
                            row.querySelector('[data-class-day]').innerText = cls.class_day;

                            // 시간 정보 세팅
                            mainAfterClassTimeSet(
                                row.querySelector('[data-bundle="class_time"]')
                                , result.class_study_dates[class_seq]
                            );

                            // 카운트 정보 세팅
                            row.querySelector('[data-class-now-cnt]').innerText = cls.class_now_cnt || 0;
                            const class_mates = result.class_mates[class_seq];
                            row.querySelector('[data-class-student-cnt]').innerText = class_mates?.length || 0;
                            row.querySelector('[data-class-reinf-cnt]').innerText = cls.class_reinf_cnt || 0;
                            main_class_mates[class_seq] = class_mates;
                            bundle.querySelector('[data-row="add_class"]').before(row);
                            idx++;
                        });
                    }

                    bundle.querySelector('[data-row="add_class"]').setAttribute('data-idx-group', Math.floor(idx / 4));


            const owl = $('[data-bundle="div_class"]');
            owl.owlCarousel({
                items: 4,
                loop: false,
                nav: false,
                dots: false,
                onInitialized: function(event) {
                    const owlInstance = event.target;
                    const itemsCount = $(owlInstance).find('.owl-item').length;
                    console.log(itemsCount)
                    const width = itemsCount <= 4 ? '24.33%' : '24.33%';
                    $('.aside-left').css('width', width);
                    $('[data-bundle="div_class"] .owl-item').addClass('d-flex');
                }
            });

            const owlInstance = owl.data('owl.carousel');
            if (owlInstance.options.items != 4) {
                $('.owl-carousel .owl-stage').css('width', `${100}%`);
                $('.owl-carousel .owl-stage').find('.owl-item').css('width', `${100 / owlInstance.options.items}%`);
                $('.owl-carousel .owl-stage').addClass('overflow-visible');
                if($(owlInstance).find('.owl-item.active').length > 4){
                    $('.owl-btn-wrap').attr('hidden', false);
                }
            } else {
                $('.owl-carousel .owl-stage').find('.owl-item').css('width', `${$('.owl-stage-outer').width() / owlInstance.options.items -20}px`);
                if($('.owl-item').length > 4){
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
                    if(owl[0].querySelectorAll('[data-row]').length > 4){
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
                });

            }

            // 반 시간 설정.
            function mainAfterClassTimeSet(tag, data) {
                const copy_row = tag.querySelector('[data-row="in_copy"]');
                if (data) {
                    data.forEach(function(result) {
                        const row = copy_row.cloneNode(true);
                        row.hidden = false;
                        row.classList.add('d-flex');
                        row.classList.add('mb-1');
                        row.setAttribute('data-row', 'in_clone');
                        row.querySelector('[data-class-day]').innerText = result.class_day + '요일';
                        row.querySelector('[data-class-start-end-time]').innerHTML =
                            `${result.class_day}요일 <span class="scale-text-gray_05">${result.start_time?.substr(0,5) || ''} - ${result.end_time?.substr(0,5) || ''}</span>`;
                        row.querySelector('[data-class-start-time]').value = result.start_time || '';
                        row.querySelector('[data-class-end-time]').value = result.end_time || '';
                        row.querySelector('[data-class-interval]').value = result.time_interval || '';
                        tag.appendChild(row);
                    });
                } else {
                    const empty_row = copy_row.cloneNode(true);
                    empty_row.hidden = false;
                    empty_row.classList.add('d-flex', 'mt-4', 'justify-content-center');
                    empty_row.setAttribute('data-row', 'in_clone');
                    empty_row.querySelector('[data-class-day]').innerText = '';
                    empty_row.querySelector('[data-class-start-end-time]').innerHTML = '수업일자 없음';
                    empty_row.querySelector('[data-class-start-time]').value = '';
                    empty_row.querySelector('[data-class-end-time]').value = '';
                    empty_row.querySelector('[data-class-interval]').value = '';
                    tag.appendChild(empty_row);
                }
            }

            // 정보 세팅.
            function mainAfterModalSetInfo(parameter) {
                const modal = document.querySelector('#main_after_modal #sticker');
                modal.querySelector('[data-class-seq]').value = parameter.class_seq;
                modal.querySelector('[data-class-name]').value = parameter.class_name;
                modal.querySelector('[data-class-room]').value = parameter.class_room;
                modal.querySelector('[data-grade]').value = parameter.grade;

                // 수업시간 세팅.
                const div_bundle = modal.querySelector('[data-div-main-after-study-time-bundle]');
                const study_time_rows = div_bundle.querySelectorAll('[data-div-main_after-study-time-row="clone"]');
                study_time_rows.forEach(row => {
                    row.remove();
                });
                document.querySelector('[data-study-time-no-list]').hidden = false;
                parameter.study_times.forEach(study_time => {
                    const row_clone = document.querySelector('[data-div-main_after-study-time-row="copy"]').cloneNode(
                        true);
                    row_clone.setAttribute('data-div-main_after-study-time-row', 'clone');
                    row_clone.hidden = false;
                    row_clone.querySelector('[data-study-time-str]').innerText =
                        `${study_time.start_end_time} (${study_time.interval}분)`;
                    row_clone.querySelector('[data-study-time-day]').value = study_time.day;
                    row_clone.querySelector('[data-study-time-start]').value = study_time.start_time;
                    row_clone.querySelector('[data-study-time-end]').value = study_time.end_time;
                    row_clone.querySelector('[data-study-time-interval]').value = study_time.interval;
                    div_bundle.appendChild(row_clone);
                    document.querySelector('[data-study-time-no-list]').hidden = true;
                });

                // 학생 세팅.
                const div_student_bundle = modal.querySelector('[data-div-main-after-student-bundle]');
                const student_rows = div_student_bundle.querySelectorAll('[data-div-main-after-student-row="clone"]');
                student_rows.forEach(row => {
                    row.remove();
                });
                document.querySelector('[data-student-no-list]').hidden = false;
                parameter.students.forEach(student => {
                    const row_clone = document.querySelector('[data-div-main-after-student-row="copy"]').cloneNode(
                        true);
                    row_clone.setAttribute('data-div-main-after-student-row', 'clone');
                    row_clone.hidden = false;
                    row_clone.querySelector('[data-student-seq]').value = student.student_seq;
                    row_clone.querySelector('[data-student-seq]').dataset.studentSeq = student.student_seq;
                    row_clone.querySelector('[data-student-info-str]').innerText = `${student.student_name}(${student.grade_name||''}${student.class_name||''})`;
                    div_student_bundle.appendChild(row_clone);
                    document.querySelector('[data-student-no-list]').hidden = true;
                });
            }

            // 학생 리스트 불러오기.
            function mainAfterStudentSelect() {
                const teach_seq = document.querySelector('[data-main-teach-seq]').value;
                const no_class = document.querySelector('[data-no-class]').checked ? 'Y' : 'N';
                const search_grade = document.querySelector('[data-search-grade]').value;
                const search_name = document.querySelector('[data-search-student-name]').value;

                const page = "/teacher/main/after/student/select";
                const parameter = {
                    teach_seq: teach_seq
                    , no_class: no_class
                    , search_grade: search_grade
                    , search_name: search_name
                };

                //초기화
                const bundle = document.querySelector('[data-bundle="tby_students"]');
                const copy_row = bundle.querySelector('[data-row="copy"]').cloneNode(true);
                //bundle 에서 data-row="clone" 인것을 모두 삭제.
                queryFetch(page, parameter, function(result) {
                    bundle.innerHTML = '';
                    bundle.appendChild(copy_row);
                    if ((result.resultCode || '') == 'success') {
                        result.students.forEach(student => {
                            const row = copy_row.cloneNode(true);
                            row.hidden = false;
                            row.setAttribute('data-row', 'clone');
                            row.querySelector('[data-student-seq]').value = student.id;
                            row.querySelector('[data-is-class]').innerText = student.is_class == 'Y' ? '배정' : '미배정';
                            row.querySelector('[data-student-name]').innerText = student.student_name;
                            row.querySelector('[data-grade-name]').innerText = student.grade_name;
                            row.querySelector('[data-class-name]').innerText = student.class_name;
                            row.querySelector('[data-created-at]').innerText = (student.created_at || '').substr(0, 10).replace(/-/g, '.');
                            bundle.appendChild(row);
                        });
                    }
                });

            }


            // 반 수정 모달 > 학생 선택 > 전체 체크.
            function mainAfterAllChk(vthis) {
                const bundle = document.querySelector('[data-bundle="tby_students"]');
                const rows = bundle.querySelectorAll('[data-row="clone"]');
                rows.forEach(row => {
                    const chk = row.querySelector('input[type="checkbox"]');
                    chk.checked = vthis.checked;
                });
            }

            // 학생 목록에 추가 버튼 클릭.
            function mainAfterAddChkStudents() {
                // 학생이 한명도 선택(체크 박스 체크)이 되어 있지 않으면 리턴.
                const bundle = document.querySelector('[data-bundle="tby_students"]');
                const chk_els = bundle.querySelectorAll('[data-row="clone"] [data-chk][type="checkbox"]:checked');
                if (chk_els.length < 1) {
                    toast('학생을 선택해주세요.');
                    return;
                }
                const students = [];
                chk_els.forEach(chk => {
                    const row = chk.closest('[data-row="clone"]');
                    const student_seq = row.querySelector('[data-student-seq]').value;
                    const student_name = row.querySelector('[data-student-name]').innerText;
                    const grade_name = row.querySelector('[data-grade-name]').innerText;
                    const class_name = row.querySelector('[data-class-name]').innerText;

                    const student = {
                        student_seq: student_seq
                        , student_name: student_name
                        , grade_name: grade_name
                        , class_name: class_name
                    };
                    students.push(student);
                });

                // 학생 모달 오른쪽에 추가.
                mainAfterAddStudentList(students);
            }
            // 학생 모달 쪽에 추가.
            function mainAfterAddStudentList(students) {
                // 초기화
                const modal = document.querySelector('#main_after_modal');
                const bundle = modal.querySelector('[data-div-main-after-student-bundle]');
                const copy_row = bundle.querySelector('[data-div-main-after-student-row="copy"]').cloneNode(true);
                // bundle.innerHTML = '';
                bundle.appendChild(copy_row);

                if (students.length < 1) {
                    toast('학생을 체크해주시고, 다시 시도해주세요');
                    return;
                }
                let is_cancel = false;
                let cnt_cancel = 0;
                students.forEach(student => {
                    if (document.querySelectorAll('[data-student-seq="' + student.student_seq + '"]').length > 0) {
                        is_cancel = true;
                        cnt_cancel++;
                        return;
                    }
                    const row = copy_row.cloneNode(true);
                    row.hidden = false;
                    row.setAttribute('data-div-main-after-student-row', 'clone');
                    //학생이름(학년 반)
                    row.querySelector('[data-student-info-str]').innerText = `${student.student_name}(${student.grade_name} ${student.class_name})`;
                    row.querySelector('[data-student-seq]').value = student.student_seq;
                    row.querySelector('[data-student-seq]').dataset.studentSeq = student.student_seq;
                    bundle.appendChild(row);
                });
                let before_msg = '';
                if (is_cancel) {
                    before_msg = `이미 추가된 학생(${cnt_cancel}명) 제 외 후 선택 `;
                }
                const msg =
                    `
            <div class="text-sb-28px">
              <p>${before_msg}학생이 추가되었습니다.</p>
            </div>
            `;
                sAlert('', msg, 4)
            }

            // 학생 모달 쪽에 잇는 학생 선택시 삭제 기능.
            function mainAfterStudentListRemove(vthis) {
                const row = vthis.closest('[data-div-main-after-student-row="clone"]');
                row.remove();
            }

            // 수업 시작하기 버튼 클릭
            function mainAfterClassStart(vthis) {
                const row = vthis.closest('[data-row="clone"]');
                const class_name = row.querySelector('[data-class-name]').innerText;
                const class_seq = row.querySelector('[data-class-seq]').value;
                const teach_seq = document.querySelector('[data-main-teach-seq]').value;
                const team_code = row.querySelector('[data-team-code]').value;

                const form = document.querySelector('[data-form-class-start]');
                form.method = 'post';
                form.blank = '_self';
                form.querySelector('[name="class_seq"]').value = class_seq;
                form.querySelector('[name="teach_seq"]').value = teach_seq;
                form.querySelector('[name="team_code"]').value = team_code;

                const msg =
                    `<div class="text-sb-28px">
              <p>${class_name}<br> 수업을 시작하시겠습니까?</p>
            </div>`;
                sAlert('', msg, 3, function() {
                    form.submit();
                });
            }

            // QR 코드 생성.
            let clone_system_alert = '';
            function mainAfterQrCodeCreate(el) {
                if (clone_system_alert == '') {
                    clone_system_alert = document.querySelector('#system_alert').innerHTML;
                }
                const row = el.closest('[data-row="clone"]');
                console.log(row);
                const classSeq = row.querySelector('[data-class-seq]').value;
                const gradeCode = row.querySelector('[data-grade]').value;
                const teamCode = row.querySelector('[data-team-code]').value;
                const teachSeq = {{ session()->get('teach_seq') }};
                const qrCodeContainer = document.getElementById('qrcode');
                const downloadBtn = document.getElementById('downloadBtn');
                const qrUrl = `${window.location.origin}/parent/register/someMethod?classSeq=${classSeq}&schoolName=${teamCode}&schoolCode=${teamCode}&teachSeq=${teachSeq}&gradeCode=${gradeCode}`; // QR 코드에 포함할 URL 또는 텍스트

                console.log(qrUrl);
                QRCode.toDataURL(qrUrl, {
                    width: 200
                    , margin: 2
                }, function(err, url) {
                    if (err) {
                        console.error(err);
                        return;
                    }
                    const img = document.createElement('img');
                    img.src = url;

                    const msg = `
                <div class="d-flex justify-content-center align-items-center flex-column">
                    <img src="${url}" alt="QR 코드" class="w-50">
                    <button class="btn btn-primary w-50" id="downloadBtn" onclick="downloadQRCode('${url}')">QR 코드 다운로드</button>
                </div>
                `;

                    sAlert('QR 코드 다운로드', msg, 3, function() {
                        document.querySelector('.modal-backdrop').remove();
                        document.querySelector('body').style = '';
                        document.querySelector('#system_alert').innerHTML = clone_system_alert;
                    }, function() {

                    }, '닫기', '');

                    const msg_btn1 = document.querySelector('#system_alert .msg_btn2');
                    msg_btn1.classList.add('d-none');
                    const myModal = new bootstrap.Modal(document.querySelector('#system_alert .modal'), {});
                    myModal.show();
                    // 다운로드 버튼 클릭 시 이미지 다운로드
                });
            }

            function downloadQRCode(url) {
                const link = document.createElement('a');
                link.href = url;
                link.download = 'qrcode.png';
                link.click();
                document.querySelector('.msg_btn1').click();
            }

</script>

@endsection
