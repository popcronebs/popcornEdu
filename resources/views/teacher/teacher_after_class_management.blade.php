@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
수업 관리
@endsection

@section('add_css_js')
    <link rel="stylesheet" as="style" crossorigin href="https://fastly.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/variable/pretendardvariable.min.css" />
    <link rel="stylesheet" href="https://fastly.jsdelivr.net/fullcalendar/1.6.1/fullcalendar.css">
    {{-- <link rel="stylesheet" href="/assets/css/daterangepicker.css"> --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- <script src="https://fastly.datatables.net/v/bs5/jq-3.7.0/dt-2.0.2/datatables.min.js"></script> --}}
    <script src='https://fastly.jsdelivr.net/npm/@fullcalendar/core@6.1.11/index.global.min.js'></script>
    <script src='https://fastly.jsdelivr.net/npm/@fullcalendar/web-component@6.1.11/index.global.min.js'></script>
    <script src="https://fastly.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/1.6.4/fullcalendar.min.js"></script>
@endsection
{{-- 추가 코드 --}}
{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<style>

.hover-svg .hover-svg1 {
    fill: #F9F9F9;
    transition: fill 0.3s ease;
}

.hover-svg:hover .hover-svg1 {
    fill: #FF5065; /* 원하는 색상으로 변경 */
}

.hover-svg .hover-svg2 {
    fill: #DCDCDC;
    transition: fil 0.3s ease;
}

.hover-svg:hover .hover-svg2 {
    fill: white; /* 원하는 색상으로 변경 */
}
.hover-svg .hover-svg3 {
    stroke: white;
    transition: stroke 0.3s ease;
}
.hover-svg:hover .hover-svg3 {
    stroke: #FF5065; /* 원하는 색상으로 변경 */
}
.primary-bg-mian-hover.active{
    background-color: #FFC747 !important;
    color:white !important;
}
</style>
<div class="p-0 position-relative">
    <div class="sub-title d-flex justify-content-between align-items-center">
        <h2 class="text-sb-42px">
            <img src="{{asset('images/graphic_schoolLearning_icon.png')}}" width="72">
            수업 관리
        </h2>
        <ul class="d-inline-flex gap-2">
            <li>
                <button type="button" onclick="classManTopTab(this)" data-btn-top="1" class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 scale-text-gray_05 scale-text-white-hover px-32 primary-bg-mian-hover active">
                    캘린더보기</button>
            </li>
            <li>
                <button type="button" onclick="classManTopTab(this);classManViewFullListRefSelect();" data-btn-top="2" class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 scale-text-gray_05 scale-text-white-hover px-32 primary-bg-mian-hover">
                    전체목록보기</button>
            </li>
        </ul>
    </div>
    {{--  캘린더 보기 --}}
    <div data-div-main="1" id="teach_counsel_div_main">
        <div class="row px-0 mx-0">
            {{--  상단 검색 필터영역 --}}
            <div class="row px-0 col-lg-8 px-0 mb-5">
                <div class="col-6 px-0">
                    <div class="d-inline-block select-wrap select-icon" style="min-width:100px">
                        <select data-select-class-seq onchange="classManSubjectSelect()"
                            class="rounded-pill border-gray lg-select text-sb-24px ps-4"
                            style="min-width:100px;padding-top:18px;padding-bottom:18px;" onchage="">
                            <option value="">전체클래스</option>
                            @if(!empty($classes))
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-6 text-end px-0 d-flex gap-2 align-items-baseline">
                    <div class="d-inline-block select-wrap select-icon" style="min-width:100px">
                        <select data-select-search-type onchange="classManSubjectSelect()"
                            class="rounded-pill border-gray lg-select text-sb-24px ps-4"
                            style="min-width:100px;padding-top:18px;padding-bottom:18px;" onchage="">
                            <option value="">전체</option>
                            <option value="grade">학년</option>
                        </select>
                    </div>
                    <label class="label-search-wrap col align-items-baseline">
                        <input type="text" data-inp-after-search-str="1"
                            onkeyup="if(event.keyCode == 13) classManClassCalendarSelect();"
                            class="ms-search border-gray rounded-pill text-m-20px w-100" placeholder="단어를 검색해보세요.">
                    </label>
                </div>
            </div>
            {{--  요일 / 몇 건의 수업 --}}
            <div class="col-lg-4 h-center">
                {{-- 날짜, 총 건수 --}}
                <div class="d-flex justify-content-between align-items-center h-62 w-100 mb-5">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/yellow_calendar_icon.svg') }}" width="32">
                        <p class="text-b-24px" data-p-class-seldate data-date=""></p>
                        <input type="hidden" data-sel-date>
                    </div>
                    <p class="text-m-24px">
                        <b data-counsel-total class="basic-text-error">총 0건</b>의 수업이 있습니다.
                    </p>
                </div>
            </div>
        </div>

        <div class="row px-0 mx-0">
            <div class="col-lg-8">
                {{-- 달력 --}}
                <div id="todoListCalendarTypeTwo" class="todo-list-date date-type-1 modal-shadow-style rounded-4 mb-5 ">
                </div>
            </div>
                {{--  수업 건수 리스트 --}}
                <div class="col-lg-4">

                    <ul data-ul-counsel-list-bundle class="list-wrap type-1">
                        <li data-li-counsel-list-row="copy" class="div-shadow-style rounded-3 " hidden>
                            <input type="hidden" data-student-seq>
                            <input type="hidden" data-absent-seq>
                            <div class="d-flex justify-content-between px-4 py-5 dorp-click"
                                onclick="">
                                <div class="">
                                    <p class="label-icon text-b-24px mb-2 h-center">
                                    <span data-student-name>#학생이름</span>
                                    <span hidden class="border-gray mx-2 my-1" data-bar="1"></span>
                                    <span data-class-type hidden class="scale-text-gray_05"></span>
                                    </p>
                                    <p class="text-m-20px gray-color d-flex">
                                        <span data-student-type>#신규/재등록</span>
                                        <span data-counsel-cnt></span>
                                         <span hidden class="border-gray mx-2 my-1" data-bar="2"></span>
                                        <span data-absent-reason hidden>#마지막상품</span>
                                    </p>
                                    <p class="text-m-20px gray-color" hidden>
                                        <span data-group-name>#그룹이름</span> <span
                                            class="d-block border-gray mx-2 my-1"></span>
                                        <span data-counsel-type>#상담종류</span>
                                    </p>
                                </div>
                                <span data-ref-delete-btn hidden
                                class="h-center py-1 px-3 ms-1 cursor-pointer" onclick="classManDeleteClass(this);">
                                    <svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg" class="hover-svg">
                                    <circle cx="26" cy="26" r="26" fill="#F9F9F9" class="hover-svg1"/>
                                    <rect x="15.332" y="18.001" width="21.3333" height="2.66667" rx="1.33333" fill="#DCDCDC" class="hover-svg2"/>
                                    <path d="M17.998 20.667H33.998L32.7666 35.4444C32.709 36.1354 32.1313 36.667 31.4379 36.667H20.5582C19.8648 36.667 19.2871 36.1354 19.2295 35.4444L17.998 20.667Z" fill="#DCDCDC" class="hover-svg2"/>
                                    <path d="M20.666 16.6673C20.666 15.9309 21.263 15.334 21.9993 15.334H29.9993C30.7357 15.334 31.3327 15.9309 31.3327 16.6673V18.0007H20.666V16.6673Z" fill="#DCDCDC" class="hover-svg2"/>
                                    <path d="M23.332 24.666L23.332 29.9993" stroke="white" stroke-width="1.33333" stroke-linecap="round" class="hover-svg3"/>
                                    <path d="M25.998 24.666L25.998 29.9993" stroke="white" stroke-width="1.33333" stroke-linecap="round" class="hover-svg3"/>
                                    <path d="M28.666 24.666L28.666 29.9993" stroke="white" stroke-width="1.33333" stroke-linecap="round" class="hover-svg3"/>
                                    </svg>
                                </span>
                            </div>
                        </li>
                    </ul>

                    <button class="list-add-btn d-flex justify-content-center" onclick="classManListAddModal()">
                    <img src="{{asset('images/gray_plus_icon.svg')}}" width="24">
                        보강일정 추가하기
                    </button>
                </div>
        </div>


    </div>


    {{--  전체 목록 목록 --}}
    <div data-div-main="2" hidden>
        <div class="row px-0 px-0 mb-5 h-center">
            <div class="d-inline-block select-wrap select-icon col-auto" style="min-width:100px">
                <select data-select-class-seq onchange="classManSubjectSelect()"
                    class="rounded-pill border-gray lg-select text-sb-20px ps-4"
                    style="min-width:100px;padding-top:18px;padding-bottom:18px;" onchage="">
                    <option value="">전체클래스</option>
                    @if(!empty($classes))
                    @foreach($classes as $class))
                    <option value="{{ $class->id }}">{{ $class->grade_name.' '.$class->class_name }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <div class="d-inline-block select-wrap select-icon col-auto" style="min-width:100px">
                <select data-order-by-ref onchange="classManSubjectSelect()"
                    class="rounded-pill border-gray lg-select text-sb-20px ps-4"
                    style="min-width:100px;padding-top:18px;padding-bottom:18px;" onchage="">
                    <option value="absent_abc">결석일 오래된 순</option>
                </select>
            </div>

            <div class="col-auto scale-bg-gray_01 scale-text-gray_05 text-sb-20px p-3 rounded-3">
                <span>
                    <label class="checkbox mt-1">
                        <input type="checkbox" class="chk" onclick="event.stopPropagation();" data-is-ref-complete checked>
                        <span class="" onclick="event.stopPropagation();">
                        </span>
                    </label>
                    <span>보강 완료</span>
                </span>
                <span>
                    <label class="checkbox mt-1">
                        <input type="checkbox" class="chk" onclick="event.stopPropagation();" data-is-ref-expected checked>
                        <span class="" onclick="event.stopPropagation();">
                        </span>
                    </label>
                    <span>보강 예정</span>
                </span>
                <span>
                    <label class="checkbox mt-1">
                        <input type="checkbox" class="chk" onclick="event.stopPropagation();" data-is-ref-notthing checked>
                        <span class="" onclick="event.stopPropagation();">
                        </span>
                    </label>
                    <span>보강 미등록</span>
                </span>
            </div>

            <div class="col d-flex justify-content-end">
                {{-- 기간설정  --}}
                <label class="d-inline-block select-wrap select-icon h-62">
                    <select id="select2" onchange="classManSelectDateType(this, '[data-search-start-date]','[data-search-end-date]');classManViewFullListRefSelect();"
                        class="date-change rounded-pill ps-4 border-gray sm-select text-sb-20px me-2 h-62">
                        <option value="">기간설정</option>
                        <option value="-1">오늘로보기</option>
                        <option value="0">1주일전</option>
                        <option value="1">1개월전</option>
                        <option value="2">3개월전</option>
                    </select>
                </label>
                <div class="h-center p-3 border rounded-pill">
                    <img src="{{ asset('images/calendar_gray_icon.svg') }}" class="me-2">
                    <div data-bundle-date class="overflow-hidden col-auto cursor-pointer text-start" style="height: 20px;">
                        <div class="h-center justify-content-between">
                            <div  data-date onclick="this.closest('[data-bundle-date]').querySelector('input').showPicker()" type="text"
                            class="text-m-20px text-start scale-text-gray_05" readonly="" placeholder="">
                                {{-- 상담시작일시 --}}
                                {{ date('Y.m.d') }}
                            </div>
                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                        </div>
                        <input type="date" style="width: 80px;height: 0.5px;" data-search-start-date
                        oninput="clssManDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                    </div>
                    ~
                    <div data-bundle-date class="overflow-hidden col-auto cursor-pointer text-start" style="height: 20px;">
                        <div class="h-center justify-content-between">
                            <div data-date onclick="this.closest('[data-bundle-date]').querySelector('input').showPicker()" type="text"
                            class="text-m-20px text-start scale-text-gray_05" readonly="" placeholder="">
                                {{-- 상담시작일시 --}}
                                {{ date('Y.m.d') }}
                            </div>
                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                        </div>
                        <input type="date" style="width: 80px;height: 0.5px;" data-search-end-date
                        oninput="clssManDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                    </div>

                </div>
            </div>
        </div>

        {{-- 결석 및 보강리스트 가져오기.  --}}
        <div class="row px-0 px-0 mb-5 h-center">
            <div class="row mt-2 tableFixedHead overflow-auto mt-3">
                <table class="display table-style mt-0 w-100">
                    <thead class="table-light">
                        <tr class="text-sb-20px div-shadow-style rounded w-100">
                            <th onclick="event.stopPropagation();this.querySelector('input').click();"
                                style="width: 80px">
                                {{-- <label class="checkbox mt-1">
                                    <input type="checkbox" onclick="event.stopPropagation();">
                                    <span class="" onclick="event.stopPropagation();">
                                    </span>
                                </label> --}}
                                -
                            </th>
                            <th>학년/반</th>
                            <th>방과후 클래스</th>
                            <th>이름/아이디</th>
                            <th>학생 전화번호</th>
                            <th>결석내역</th>
                            <th>출석일</th>
                            <th style="width:100%">보강내역</th>
                            <th>상세</th>
                        </tr>
                    </thead>
                    <tbody data-bundle="reflist_students">
                        <tr data-row="copy" class="text-m-20px" hidden>
                            <td class="py-2"
                                onclick="event.stopPropagation();this.querySelector('input').click();">
                                <label class="checkbox mt-1">
                                    <input type="checkbox" class="chk" onclick="event.stopPropagation();" onchange="classManChk(this)">
                                    <span class="" onclick="event.stopPropagation();">
                                    </span>
                                </label>
                            </td>
                            <td class="py-2" data="#학년/반">
                                <span class="grade_name" data-grade-name></span>
                            </td>
                            <td class="py-2" data="#방과후클래스">
                                <span data-class-name> </span>
                            </td>
                            <td class="py-2" data="#이름/아이디">
                                <div data-student-name ></div>
                                (<span data-student-id></span>)
                            </td>
                            <td data="#학생 전화번호" class="py-2" data-student-phone></td>
                            <td data="#결석내역" class="py-3" >
                                <div data-absent-date></div>
                                (<span data-absent-reason></span>)
                            </td>
                            <td>
                                <span data-attend-cnt> </span>
                                <span data-class-total-cnt> </span>
                            </td>
                            <td class="py-2">
                                <div class="row mx-0 px-0">
                                    <div class="col px-0 all-center">
                                        <span data-ref-date-time> </span>
                                    </div>
                                    <div class="col px-0 d-flex gap-2">
                                        <span data-ref-complete class="text-primary text-sb-20px" hidden>보강 완료</span>
                                        <span data-ref-incomplete class="scale-text-gray_05 text-sb-20px" hidden>보강을 등록해주세요.</span>
                                        <button data-btn-ref-complete class="btn btn-outline-primary rounded-pill text-sb-20px" hidden onclick="classManChgRefComplete(this)">보강완료</button>
                                        <button data-btn-chg-ref-date class="btn btn-outline-dark rounded-pill  text-sb-20px" hidden onclick="classManChgRefDate(this)">일정변경</button>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <button class="btn rounded-3 btn-outline-secondary text-sb-18px">상세보기</button>
                            </td>

                            <input type="hidden" data-absent-seq>
                            <input type="hidden" data-student-seq>
                        </tr>
                </table>
            </div>
        </div>
        {{--  페이징 --}}
        <div class="d-flex justify-content-between align-items-center mt-52">
            <div class="">
                <button type="button" onclick="" data-btn-ref-send-sms
                    class="btn-line-xss-secondary text-sb-20px border-dark rounded scale-bg-white scale-text-black px-3 me-2">
                    보강 일정 발송
                </button>
            </div>
            <div class="my-custom-pagination">
                <div class="col d-flex justify-content-center">
                    <ul class="pagination col-auto" data-page="1" hidden>
                        <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="1"
                            onclick="classManPageFunc('1', 'prev')">
                            <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                        </button>
                        <li class="page-item" hidden>
                            <a class="page-link" onclick="">0</a>
                        </li>
                        <span class="page" data-page-first="1" hidden onclick="classManPageFunc('1', this.innerText);"
                            disabled>0</span>
                        <button href="javascript:void(0)" class="btn p-0 next" data-page-next="1"
                            onclick="classManPageFunc('1', 'next')" data-is-next="0">
                            <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                        </button>
                    </ul>
                </div>
            </div>
            <div class="h-center">
                <button type="button" onclick="classManMoveScheduleManagement()"
                    class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2">
                    일정관리 돌아가기
                </button>
                <button type="button" onclick="classManRefAddModalOpen();"
                    class="btn btn-primary-y text-sb-20px  rounded  px-3">
                    선택 보강 등록 및 변경
                </button>
            </div>
        </div>
    </div>
</div>

{{-- 모달 / 담당 학생 목록 --}}
<div class="modal fade" id="class_man_modal_student_list" tabindex="-1" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog rounded modal-xl ">
        <div class="modal-content border-none rounded p-3 modal-shadow-style">
            <div class="modal-header border-bottom-0">
                <div class="d-flex justify-content-between w-100">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/yellow_calendar2_icon.svg') }}" alt="32">
                        <p class="text-b-24px ms-1">보강 등록</p>
                    </div>
                    <div>
                        <div class="d-inline-block select-wrap select-icon w-100">
                            <select data-class-seq class="search_type border-none lg-select text-sb-24px w-100 h-62">
                                <option value="">반선택</option>
                                @if(!empty($classes))
                                @foreach($classes as $class))
                                <option value="{{ $class->id }}">{{ $class->grade_name.' '.$class->class_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-between align-items-center w-100">
                    <div>
                        <p class="text-sb-20px">보강 학생 목록</p>
                    </div>
                    <div class="d-flex">
                        <label class="d-inline-block select-wrap select-icon">
                            <select data-order-by
                                onchange="teachCounselSelectDateType(this, '.next_counsel_date1', '.next_counsel_date2')"
                                class="date-change rounded border-gray sm-select text-sb-20px me-2 h-52">
                                <option value="absent_abc">결석일 오래된 순</option>
                            </select>
                        </label>
                    </div>
                </div>

                <div class="row mt-2 tableFixedHead overflow-auto mt-3" style="max-height: 400px;">
                    <table class="display table-style mt-0 w-100">
                        <thead class="table-light">
                            <tr class="text-sb-20px div-shadow-style rounded w-100">
                                <th onclick="event.stopPropagation();this.querySelector('input').click();"
                                    style="width: 80px">
                                    {{-- <label class="checkbox mt-1">
                                        <input type="checkbox" onclick="event.stopPropagation();">
                                        <span class="" onclick="event.stopPropagation();">
                                        </span>
                                    </label> --}}
                                    -
                                </th>
                                <th>학년/반</th>
                                <th>방과후 클래스</th>
                                <th>이름/아이디</th>
                                <th>학생 전화번호</th>
                                <th>결석내역</th>
                            </tr>
                        </thead>
                        <tbody data-bundle="ref_students">
                            <tr data-row="copy" class="text-m-20px" hidden>
                                <td class="py-2"
                                    onclick="event.stopPropagation();this.querySelector('input').click();">
                                    <label class="checkbox mt-1">
                                        <input type="checkbox" class="chk" onclick="event.stopPropagation();">
                                        <span class="" onclick="event.stopPropagation();">
                                        </span>
                                    </label>
                                </td>
                                <td class="py-2" data="#학년/반">
                                    <span class="grade_name" data-grade-name></span>
                                </td>
                                <td class="py-2" data="#방과후클래스">
                                    <span data-class-name> </span>
                                </td>
                                <td class="py-2" data="#이름/아이디">
                                    <div data-student-name ></div>
                                    (<span data-student-id></span>)
                                </td>
                                <td data="#학생 전화번호" class="py-2" data-student-phone></td>
                                <td data="#결석내역" class="py-2" >
                                    <div data-absent-date></div>
                                    (<span data-absent-reason></span>)
                                </td>

                                <input type="hidden" data-absent-seq>
                                <input type="hidden" data-student-seq>
                            </tr>
                    </table>
                </div>

                <div class="mt-4">
                    <p class="text-sb-20px">보강 시간 설정</p>
                </div>

                <div class="row w-100 mt-4">
                    <div class="col-5 ps-0">
                        <div class="row">
                            <div class="col-6 pe-1 ps-0">
                                <div class="d-inline-block select-wrap select-icon w-100">
                                    <select class="border-gray lg-select text-sb-20px w-100 pe-3"
                                        onchange="teachCounselSelectSchedule(true)"
                                        data-select-counsel-start-time="hour">
                                        <option value="">시간</option>
                                        {{-- 오전 7시 ~ 오후 12시 --}}
                                        @for ($i = 7; $i < 24; $i++)
                                            @if ($i < 12)
                                                <option value="{{ $i }}">오전
                                                    {{ $i }}시</option>
                                                @elseif ($i == 12)
                                                <option value="{{ $i }}">오후 12시</option>
                                                @else
                                                <option value="{{ $i }}">오후
                                                    {{ $i - 12 }}시</option>
                                                @endif
                                                @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 ps-1 pe-0">
                                <div class="d-inline-block select-wrap select-icon w-100">
                                    <select class="border-gray lg-select text-sb-20px w-100"
                                        onchange="teachCounselSelectSchedule(true)"
                                        data-select-counsel-start-time="min">
                                        <option value="">분</option>
                                        {{-- 10분단위 --}}
                                        @for ($i = 0; $i < 60; $i += 10)
                                            <option value="{{ $i == 0 ? '00' : $i }}">
                                                {{ $i == 0 ? '00' : $i }}분</option>
                                            @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-5 pe-0">
                        <div class="row">
                            <div class="col-6 pe-1 ps-0 ">
                                <div class="d-inline-block select-wrap select-icon w-100">
                                    <select class="border-gray lg-select text-sb-20px w-100 pe-3"
                                        onchange="teachCounselSelectSchedule(true)"
                                        data-select-counsel-end-time="hour">
                                        <option value="">시간</option>
                                        @for ($i = 7; $i < 24; $i++)
                                            @if ($i < 12)
                                                <option value="{{ $i }}">오전
                                                    {{ $i }}시</option>
                                                @elseif ($i == 12)
                                                <option value="{{ $i }}">오후 12시</option>
                                                @else
                                                <option value="{{ $i }}">오후
                                                    {{ $i - 12 }}시</option>
                                                @endif
                                                @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-6 ps-1 pe-0">
                                <div class="d-inline-block select-wrap select-icon w-100">
                                    <select class="border-gray lg-select text-sb-20px w-100"
                                        onchange="teachCounselSelectSchedule(true)"
                                        data-select-counsel-end-time="min">
                                        <option value="">분</option>
                                        {{-- 10분단위 --}}
                                        @for ($i = 0; $i < 60; $i += 10)
                                            <option value="{{ $i == 0 ? '00' : $i }}">
                                                {{ $i == 0 ? '00' : $i }}분</option>
                                            @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-inline-block select-wrap select-icon col ">
                        <select class="border-gray lg-select text-sb-20px w-100"
                            onchange="teachCounselSelectSchedule(true)" data-select-counsel-time-between
                        >
                            <option value="">0분</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="modal-footer border-top-0">
                <div class="col ps-0">
                    <button type="button"
                        class="modal_close btn-lg-secondary text-sb-20px rounded scale-bg-gray_01 scale-text-gray_05 w-100 justify-content-center"
                        data-bs-dismiss="modal">닫기</button>
                </div>
                <div class="col ps-0">
                    <button type="button" onclick="classManRefStudentInsert()"
                        class="btn-lg-primary text-b-24px rounded scale-text-white w-100 text-center justify-content-center">
                        선택 학생 추가</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 모달 / 보강일 등록 --}}
<div class="modal fade" id="counsel_modal_add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true"
    role="dialog">
    <div class="modal-dialog rounded modal-xl" style="max-width: 1110px;">
        <div class="modal-content border-none rounded modal-shadow-style p-0 overflow-hidden">
            <div class="modal-header border-bottom-0 d-none">
            </div>
            <div class="modal-body p-0">
                <div class="d-flex half-modal">
                    <div class="half-left">
                        <div class="half-left-content-title px-4 pt-4 ">
                            <div class="modal-title fs-5 text-b-24px mb-3 h-center gap-2" id="">
                                <img src="{{ asset('images/window_memo_icon.svg') }}" width="32">
                                <div class="d-inline-block select-wrap ">
                                    <span data-select-modal-title
                                        class="rounded-pill border-0 sm-select text-sb-24px py-0 ps-0 pe-5"
                                        style="outline:none">
                                        보강 등록
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="half-date">
                            <div class="half-wrap px-4">
                                <div id="counsel_modal_calendar"
                                    class="todo-list-date date-type-1 modal-date modal-shadow-style rounded-4 mb-5">
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-52 pb-2">
                                    <p class="text-sb-20px">보강 시간 선택</p>
                                    <label class="checkbox mt-1 row align-items-center" data-hidden="no_regular" hidden>
                                        <input type="checkbox" data-chk-counsel-time-unknow
                                            onchange="teachCounselTimeUnknow(this);">
                                        <span class="col-auto px-0" onclick="event.steopPropagation();"></span>
                                        <p class="col-auto text-sb-20px ms-2 px-0">상담 시간 미정</p>
                                    </label>
                                </div>

                                <div class="row w-100">
                                    <div class="col-6 ps-0">
                                        <div class="row">
                                            <div class="col-6 pe-1 ps-0">
                                                <div class="d-inline-block select-wrap select-icon w-100">
                                                    <select class="border-gray lg-select text-sb-20px w-100 pe-3"
                                                        onchange="teachCounselSelectSchedule()"
                                                        data-select-counsel-start-time="hour">
                                                        <option value="">시간</option>
                                                        {{-- 오전 7시 ~ 오후 12시 --}}
                                                        @for ($i = 7; $i < 24; $i++)
                                                            @if ($i < 12)
                                                                <option value="{{ $i }}">오전
                                                                    {{ $i }}시</option>
                                                            @elseif ($i == 12)
                                                                <option value="{{ $i }}">오후 12시</option>
                                                            @else
                                                                <option value="{{ $i }}">오후
                                                                    {{ $i - 12 }}시</option>
                                                            @endif
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6 ps-1 pe-0">
                                                <div class="d-inline-block select-wrap select-icon w-100">
                                                    <select class="border-gray lg-select text-sb-20px w-100"
                                                        onchange="teachCounselSelectSchedule()"
                                                        data-select-counsel-start-time="min">
                                                        <option value="">분</option>
                                                        {{-- 10분단위 --}}
                                                        @for ($i = 0; $i < 60; $i += 10)
                                                            <option value="{{ $i == 0 ? '00' : $i }}">
                                                                {{ $i == 0 ? '00' : $i }}분</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6 pe-0">
                                        <div class="row">
                                            <div class="col-6 pe-1 ps-0 ">
                                                <div class="d-inline-block select-wrap select-icon w-100">
                                                    <select class="border-gray lg-select text-sb-20px w-100 pe-3"
                                                        onchange="teachCounselSelectSchedule()"
                                                        data-select-counsel-end-time="hour">
                                                        <option value="">시간</option>
                                                        @for ($i = 7; $i < 24; $i++)
                                                            @if ($i < 12)
                                                                <option value="{{ $i }}">오전
                                                                    {{ $i }}시</option>
                                                            @elseif ($i == 12)
                                                                <option value="{{ $i }}">오후 12시</option>
                                                            @else
                                                                <option value="{{ $i }}">오후
                                                                    {{ $i - 12 }}시</option>
                                                            @endif
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6 ps-1 pe-0">
                                                <div class="d-inline-block select-wrap select-icon w-100">
                                                    <select class="border-gray lg-select text-sb-20px w-100"
                                                        onchange="teachCounselSelectSchedule()"
                                                        data-select-counsel-end-time="min">
                                                        <option value="">분</option>
                                                        {{-- 10분단위 --}}
                                                        @for ($i = 0; $i < 60; $i += 10)
                                                            <option value="{{ $i == 0 ? '00' : $i }}">
                                                                {{ $i == 0 ? '00' : $i }}분</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-inline-block select-wrap select-icon w-100 mt-2">
                                    <select class="border-gray lg-select text-sb-20px w-100"
                                        onchange="teachCounselSelectSchedule()" data-select-counsel-time-between
                                        >
                                        <option value="">0분</option>
                                    </select>
                                </div>
                                <div class="row w-100 mt-80 mb-52">
                                    <div class="col-6 ps-0">
                                        <button type="button"
                                            class="modal_close btn-lg-secondary text-sb-20px rounded scale-bg-gray_01 scale-text-gray_05 w-100 justify-content-center"
                                            data-bs-dismiss="modal">닫기</button>
                                    </div>
                                    <div class="col-6 pe-0">
                                        <button type="button" data-btn-event-exit onclick="classManRefStudentInsert2();"
                                            class="btn-lg-primary text-b-24px rounded scale-text-white w-100 text-center justify-content-center">보강일정 등록하기</button>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="modal-sub-footer h-62 d-flex justify-content-center align-items-center primary-bg-bg overflow-hidden rounded-3 rounded-top-0">
                                <p class="text-m-18px primary-text-text">희망 보강일자를 캘린더에서 선택 후, 보강을 등록해 주세요.</p>
                            </div>
                        </div>
                    </div>
                    <div id="sticker" class="half-right px-4 overflow-hidden rounded-3">
                        <div class="half-right-content-title mb-4 mt-4">
                            <button type="button" class="btn-close close-btn" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="half-wrap ">
                            <p class="text-b-24px pb-3" data-p-counsel-seldate-modal></p>
                            <input type="hidden" data-sel-date2>
                            <ul data-ul-counsel-list-bundle class="list-wrap type-1">
                                <li data-li-counsel-list-row="copy" class="row justify-content-between scale-bg-white p-4 mb-2 rounded-3" hidden>
                                    <input type="hidden" data-student-seq>
                                    <input type="hidden" data-absent-seq>
                                    <div class="d-flex justify-content-between px-0 dorp-click text-sb-20px"
                                        onclick="">
                                        <div class="">
                                            <p class="label-icon mb-2 h-center">
                                            <span data-student-name>#학생이름</span>
                                            <span hidden class="border-gray mx-2 my-1" data-bar="1"></span>
                                            <span data-class-type hidden class="scale-text-gray_05"></span>
                                            </p>
                                            <p class="text-m-20px gray-color d-flex">
                                                <span data-student-type>#신규/재등록</span>
                                                <span data-counsel-cnt></span>
                                                 <span hidden class="border-gray mx-2 my-1" data-bar="2"></span>
                                                <span data-absent-reason hidden>#마지막상품</span>
                                            </p>
                                            <p class="text-m-20px gray-color" hidden>
                                                <span data-group-name>#그룹이름</span> <span
                                                    class="d-block border-gray mx-2 my-1"></span>
                                                <span data-counsel-type>#상담종류</span>
                                            </p>
                                        </div>
                                        <span data-ref-delete-btn hidden
                                        class="h-center p-0 ms-1 cursor-pointer" onclick="classManDeleteClass(this);">
                                            <svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg" class="hover-svg">
                                            <circle cx="26" cy="26" r="26" fill="#F9F9F9" class="hover-svg1"/>
                                            <rect x="15.332" y="18.001" width="21.3333" height="2.66667" rx="1.33333" fill="#DCDCDC" class="hover-svg2"/>
                                            <path d="M17.998 20.667H33.998L32.7666 35.4444C32.709 36.1354 32.1313 36.667 31.4379 36.667H20.5582C19.8648 36.667 19.2871 36.1354 19.2295 35.4444L17.998 20.667Z" fill="#DCDCDC" class="hover-svg2"/>
                                            <path d="M20.666 16.6673C20.666 15.9309 21.263 15.334 21.9993 15.334H29.9993C30.7357 15.334 31.3327 15.9309 31.3327 16.6673V18.0007H20.666V16.6673Z" fill="#DCDCDC" class="hover-svg2"/>
                                            <path d="M23.332 24.666L23.332 29.9993" stroke="white" stroke-width="1.33333" stroke-linecap="round" class="hover-svg3"/>
                                            <path d="M25.998 24.666L25.998 29.9993" stroke="white" stroke-width="1.33333" stroke-linecap="round" class="hover-svg3"/>
                                            <path d="M28.666 24.666L28.666 29.9993" stroke="white" stroke-width="1.33333" stroke-linecap="round" class="hover-svg3"/>
                                            </svg>
                                        </span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 d-none">
            </div>
        </div>
    </div>
</div>
{{-- 160px --}}
<div>
    <div class="py-lg-5"></div>
    <div class="py-lg-4"></div>
    <div class="pt-lg-3"></div>
</div>

<script>
// 풀캘린더 초기화
var todoListCalendarTypeTwo = fullcalendarInit1();
var counsel_modal_calendar = fullcalendarInit2();
var chk_students = {}; // < key = absent_seq
document.addEventListener('DOMContentLoaded', function() {
    // 캘린더 상담 목록 가져오기.
    classManClassCalendarSelect(true);
    // 오른쪽 상세 목록 가져오기.
    classManRightSideSelect(true);
});

// 최상단 탭 클릭.
function classManTopTab(vthis){
    const tab_num = vthis.dataset.btnTop;
    document.querySelectorAll('[data-btn-top]').forEach(function(el){
        el.classList.remove('active');
    });
    // data-div-main 모두 숨기기
    document.querySelectorAll('[data-div-main]').forEach(function(el){
        el.hidden = true;
        if(el.dataset.divMain == tab_num){
            el.hidden = false;
        }
    });
    vthis.classList.add('active');

}

function fullcalendarInit1() {

    document.querySelector('[data-p-class-seldate]').innerText = new Date().format('yyyy.MM.dd E');
    document.querySelector('[data-sel-date]').value = new Date().format('yyyy-MM-dd');

    var todoListCalendarTypeTwoEl = document.getElementById('todoListCalendarTypeTwo');
    var todoListCalendarTypeTwo = new FullCalendar.Calendar(todoListCalendarTypeTwoEl, {
        locale: 'ko',
        firstDay: 1,
        // editable: true,
        // selectable: true,
        // dayMaxEvents: true, // 더보기 이벤트
        // dayMaxEvents: 2, // 보여질꺼갯수
        contentHeight: 980,
        customButtons: {
            myCustomPrev: {
                text: '',
                click: function() {
                    todoListCalendarTypeTwo.prev();
                    classManClassCalendarSelect(true);
                }
            },
            myCustomNext: {
                text: '',
                click: function() {
                    todoListCalendarTypeTwo.next();
                    classManClassCalendarSelect(true);
                }
            }
        },
        dateClick: function(eventClickInfo) {
            this.select(eventClickInfo.dateStr);
            var $clickedElement = $(eventClickInfo.dayEl).find(".fc-daygrid-day-top > a");
            if ($clickedElement.hasClass("active")) {
                $clickedElement.removeClass("active");
                classManRightSideClear(true);
            } else {
                $(".fc-daygrid-day-top > a.active").removeClass("active");
                $clickedElement.addClass("active");
                const select_date_str = eventClickInfo.date.format('yyyy.MM.dd E');
                const select_date_str2 = eventClickInfo.date.format('yyyy-MM-dd');
                document.querySelector('[data-p-class-seldate]').innerText = select_date_str;
                document.querySelector('[data-sel-date]').value = select_date_str2;

                classManRightSideSelect(true);
            }
        },
        headerToolbar: {
            left: '',
            center: 'myCustomPrev title myCustomNext',
            right: '',
        },
        dayCellContent: function(info) {
            if (window.innerWidth < 1024) {
                todoListCalendarTypeTwo.setOption('contentHeight', 390);
            } else {
                todoListCalendarTypeTwo.setOption('contentHeight', 980);
            }
            var number = document.createElement("a");
            number.classList.add("fc-daygrid-day-number");
            number.innerHTML = info.dayNumberText.replace("일", '');
            if (info.view.type === "dayGridMonth") {
                return {
                    html: number.outerHTML
                };
            }
            return {
                domNodes: []
            };

        },
        dayHeaderContent: function(info) {
            // info.date는 각 날짜의 Date 객체입니다.
            // getDay() 메서드를 사용하여 요일을 가져올 수 있습니다.
            var day = info.date.getDay();
            // 각 요일에 해당하는 텍스트를 배열에 저장합니다.
            var dayOfWeek = ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'];

            // 배열에서 해당 요일에 해당하는 텍스트를 가져와서 반환합니다.
            return dayOfWeek[day];
        },
        eventContent: function(arg) {
            return {
                html: `<div class="d-flex justify-content-between fc-event-main fc-event-title pt-2">
                <span class="black-color">${arg.event.title}</span>
                <span>${arg.event.extendedProps.count}건</span>
                </div>
                `
            };
        },
        events: undefined,
        eventDisplay: 'auto', // 이벤트를 개별로 표시

        eventClassNames: function(arg) {
            if (arg.event.title.includes('정규수업')) {
                return 'management-calendar type-2';
            } else if (arg.event.title.includes('보강수업')) {
                return 'management-calendar type-1';
            }
        },
        windowResize: function(arg) {
            if (window.innerWidth < 1024) {
                todoListCalendarTypeTwo.setOption('contentHeight', 390);
            } else {
                todoListCalendarTypeTwo.setOption('contentHeight', 980);
            }
        }
    });
    todoListCalendarTypeTwo.render();
    // 오늘의 날짜를 가져옵니다.
    var today = new Date();
    todoListCalendarTypeTwo.select(today);
    const click_el = document.querySelector(`[data-date="${today.format('yyyy-MM-dd')}"]`);
    click_el.querySelector('.fc-daygrid-day-number').classList.add('active');

    // classManRightSideSelect(true);
    return todoListCalendarTypeTwo;
}

function fullcalendarInit2() {
    document.querySelector('[data-sel-date2]').value = new Date().format('yyyy-MM-dd');

    var counsel_modal_calendar_el = document.getElementById('counsel_modal_calendar');
    var counsel_modal_calendar = new FullCalendar.Calendar(counsel_modal_calendar_el, {
        locale: 'ko',
        firstDay: 1,
        // editable: true,
        // selectable: true,
        // dayMaxEvents: true, // 더보기 이벤트
        // dayMaxEvents: 2, // 보여질꺼갯수
        contentHeight: 500,
        expandRows: true,
        customButtons: {
            myCustomPrev: {
                text: '',
                click: function() {
                    counsel_modal_calendar.prev();
                    teachCounselSelectCalendar();
                }
            },
            myCustomNext: {
                text: '',
                click: function() {
                    counsel_modal_calendar.next();
                    teachCounselSelectCalendar();
                }
            }
        },
        dateClick: function(eventClickInfo) {
            var $clickedElement = $(eventClickInfo.dayEl).find(".fc-daygrid-day-top > a");
            if ($clickedElement.hasClass("active")) {
                $clickedElement.removeClass("active");
                classManRightSideClear();
            } else {
                $(".fc-daygrid-day-top > a.active").removeClass("active");
                $clickedElement.addClass("active");
                const select_date_str = eventClickInfo.date.format('yyyy.MM.dd E');
                const select_date_str2 = eventClickInfo.date.format('yyyy-MM-dd');
                document.querySelector('[data-p-counsel-seldate-modal]').innerText = select_date_str;
                document.querySelector('[data-sel-date2]').value = select_date_str2;
                classManRightSideSelect();
            }
        },
        headerToolbar: {
            left: '',
            center: 'myCustomPrev title myCustomNext',
            right: '',
        },
        dayCellContent: function(info) {
            var number = document.createElement("a");
            number.classList.add("fc-daygrid-day-number");
            number.innerHTML = info.dayNumberText.replace("일", '');
            if (info.view.type === "dayGridMonth") {
                return {
                    html: number.outerHTML
                };
            }
            return {
                domNodes: []
            };
        },
        events: undefined,
        eventDisplay: 'auto', // 이벤트를 개별로 표시
        eventClassNames: function(arg) {
            if (arg.event.title.includes('정규수업')) {
                return 'management-calendar type-2';
            } else if (arg.event.title.includes('보강수업')) {
                return 'management-calendar type-1';
            }
        },
    });
    return counsel_modal_calendar;
}

//  TODO: 정규수업은 기본적으로 추가하지 않아도 달력에 추가되게 나온다
// 상담 목록의 달력 표시 정보 불러오기.
function classManClassCalendarSelect(is_main) {
    let main_div = document.querySelector('#counsel_modal_add');
    if (is_main) main_div = document.querySelector('#teach_counsel_div_main');

    let calendar_obj = counsel_modal_calendar;
    if (is_main) calendar_obj = todoListCalendarTypeTwo;

    const start_date = calendar_obj.view.activeStart.format('yyyy-MM-dd');
    const end_date = calendar_obj.view.activeEnd.format('yyyy-MM-dd');
    let class_seq = '';
    if(is_main) class_seq = main_div.querySelector('[data-select-class-seq]').value;
    else class_seq = document.querySelector('[data-div-main="2"] [data-select-class-seq]').value;

    const search_type_el = main_div.querySelector('[data-select-search-type]');
    const search_str_el = main_div.querySelector('[data-inp-after-search-str="1"]');
    const search_type = search_type_el ? search_type_el.value : '';
    const search_str = search_str_el ? search_str_el.value : '';
    const page = "/teacher/after/class/management/select";

    const parameter = {
        start_date: start_date,
        end_date: end_date,
        search_type: search_type,
        search_str: search_str,
        class_seq: class_seq,
    };
    queryFetch(page, parameter, function(result) {
        if ((result.resultCode || '') == 'success') {
            const events = [];
            result.class_study_dates.forEach(stdate=> {
                // { start: event.start, title: event.title, count: 0 }
                const event = {
                    title: '정규수업',
                    start: stdate.date,
                    count: stdate.cnt
                };
                events.push(event);
            });
            result.absent_refs.forEach(absent=> {
                const event = {
                    title: '보강수업',
                    start: absent.date,
                    count: absent.cnt
                };
                events.push(event);
            });
            calendar_obj.removeAllEvents();
            calendar_obj.addEventSource(events);
        }
    });
}

// 달력 클릭시 오른쪽 사이드 상세 리스트 불러오기. is_main = true
// 모달에서도 오른쪽 사이드 상세 리스트 불러오기. is_main = false
// 상담 목록 불러오기.
function classManRightSideSelect(is_main) {
    let main_div = document.querySelector('#counsel_modal_add');
    const today = new Date().format('yyyy-MM-dd');
    if (is_main) main_div = document.querySelector('#teach_counsel_div_main');

    const sel_date = main_div.querySelector('[data-date] .active').closest('td').getAttribute('data-date');
    const page = "/teacher/after/class/management/detail/select";
    const types = ['class', 'absent_ref'];
    let class_seq = '';
    if(is_main) class_seq = main_div.querySelector('[data-select-class-seq]').value;
    else class_seq = document.querySelector('[data-div-main="2"] [data-select-class-seq]').value;
    const parameter = {
        sel_date: sel_date,
        types: types,
        class_seq: class_seq,
    };
    queryFetch(page, parameter, function(result) {
        if ((result.resultCode || '') == 'success') {
            const bundle = main_div.querySelector('[data-ul-counsel-list-bundle]');
            const row_copy = bundle.querySelector('[data-li-counsel-list-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);
            //data-counsel-total
            const is_today = new Date().format('yyyy-MM-dd') == sel_date;

            if (is_main) {
                const cnt1 = result.class_study_dates.length;
                const cnt2 = result.absent_refs.length;
                main_div.querySelector('[data-counsel-total]').innerText = `총 ${cnt1+cnt2}건`;
            }

            // 정규수업.
            result.class_study_dates.forEach(data => {
                const row = row_copy.cloneNode(true);
                row.hidden = false;
                row.setAttribute('data-li-counsel-list-row', 'clone');
                row.classList.add('type-2');
                row.querySelector('[data-student-name]').innerText = `정규수업`;
                const student_type_el = row.querySelector('[data-student-type]');
                if (student_type_el) student_type_el.innerText = data.start_time.substr(0, 5) + ' ~ ' + data.end_time.substr(0, 5);
                bundle.appendChild(row);
            });
            // 보충수업.
            result.absent_refs.forEach(data =>{
                const row = row_copy.cloneNode(true);
                row.hidden = false;
                row.setAttribute('data-li-counsel-list-row', 'clone');
                row.classList.add('type-1');
                row.querySelector('[data-student-seq]').value = data.student_seq;
                row.querySelector('[data-absent-seq]').value = data.id;
                row.querySelector('[data-student-name]').innerText = data.student_name;
                row.querySelector('[data-class-type]').innerText = `보충수업`;
                row.querySelector('[data-class-type]').hidden = false;
                row.querySelector('[data-bar="1"]').hidden = false;
                row.querySelector('[data-bar="1"]').classList.add('d-block');

                const student_type_el = row.querySelector('[data-student-type]');
                if (student_type_el) student_type_el.innerText = data.ref_start_time && data.ref_end_time ? data.ref_start_time.substr(0, 5) + ' ~ ' + data.ref_end_time.substr(0,5): '';
                row.querySelector('[data-absent-reason]').innerText = data.absent_date.substr(2,8).replace(/-/g, '.') + ` 결석(${(data.absent_reason||'')})`;
                row.querySelector('[data-absent-reason]').hidden = false;
                row.querySelector('[data-bar="2"]').hidden = false;
                row.querySelector('[data-ref-delete-btn]').hidden = false;

                bundle.appendChild(row);

            });
        }
    });
}

// 보충 수업 삭제.
function classManDeleteClass(vthis, is_main){
    const row = vthis.closest('[data-li-counsel-list-row="clone"]');
    const absent_seq = row.querySelector('[data-absent-seq]').value;
    const student_seq = row.querySelector('[data-student-seq]').value;

    const page = "/teacher/after/class/management/absent/ref/delete";
    const parameter = {
        absent_seq: absent_seq,
        student_seq: student_seq,
    };
    const msg = '<div class="text-sb-24px">보충 수업을 정말로 삭제하시겠습니까?</div>';
    sAlert('', msg, 3, function(){
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                toast('보충수업이 삭제처리 되었습니다.')
                // 캘린더 상담 목록 가져오기.
                classManClassCalendarSelect(true);
                // 오른쪽 상세 목록 가져오기.
                classManRightSideSelect(true);
                // 캘린더 상담 목록 가져오기.
                classManClassCalendarSelect();
                // 오른쪽 상세 목록 가져오기.
                classManRightSideSelect();
            }else{}
        });
    });
}

// 보강(보충) 일정 추가하기.
function classManListAddModal(){
    classManAbsentList();
    const myModal = new bootstrap.Modal(document.getElementById('class_man_modal_student_list'), {
        keyboard: false
    });
    myModal.show();
}

// 모달 / 모달안에 시간설정을 하나라도 바꾸면
function teachCounselSelectSchedule(is_main) {
    let week_str = '';
    let modal = document.getElementById('counsel_modal_add');
    if(is_main) modal = document.getElementById('class_man_modal_student_list');

    // 시간 사이값 선택시.
    // 상담 시간 중 시작 시와 분 선택시 = 20분/30분/40분 단위 옵션에 시간 넣어주기.
    // data-select-counsel-start-time="hour"
    const time_between_el = modal.querySelector('[data-select-counsel-time-between]')
    const start_time_hour = modal.querySelector('[data-select-counsel-start-time="hour"]');
    const start_time_min = modal.querySelector('[data-select-counsel-start-time="min"]');
    const today_date = new Date().format('yyyy-MM-dd');
    let start_time = '';

    if (start_time_hour.value != '' && start_time_min.value != '') {
        start_time = `${start_time_hour.value}:${start_time_min.value}`;
        const s_idx = time_between_el.options.selectedIndex;
        if (start_time != time_between_el.options[s_idx].getAttribute('data-start-time')) {
            const add_mins = [20, 30, 40, 50];
            modal.querySelectorAll('.add_el').forEach(function(el) {
                el.remove();
            });
            add_mins.forEach(function(add_min) {
                const option = document.createElement('option');
                option.value = add_min;
                option.setAttribute('data-min', add_min);
                option.setAttribute('data-start-time', start_time);
                option.classList.add("add_el");
                const end_time = new Date(`${today_date} ${start_time_hour.value}:${start_time_min.value}`);
                end_time.setMinutes(end_time.getMinutes() + parseInt(add_min));
                option.innerText = `${add_min}분 (${start_time} ~ ${end_time.format('HH:mm')})`;
                time_between_el.appendChild(option);
            });
        }

    } else {
        modal.querySelectorAll('[data-start-end-time]').forEach(function(el) {
            el.innerText = '시작시간분 선택';
        });
        time_between_el.value = '';
    }

    const end_time_hour = modal.querySelector('[data-select-counsel-end-time="hour"]');
    const end_time_min = modal.querySelector('[data-select-counsel-end-time="min"]');
    //시간 사이값 선택시.
    if (time_between_el.value != "") {
        const s_idx = time_between_el.options.selectedIndex;
        const sel_time_between = time_between_el.options[s_idx].getAttribute('data-min');
        // start_time + sel_time_between
        const end_time = new Date(`${today_date} ${start_time_hour.value}:${start_time_min.value}`);
        end_time.setMinutes(end_time.getMinutes() + parseInt(sel_time_between));
        end_time_hour.value = end_time.format('HH') * 1;
        end_time_min.value = end_time.format('mm');
    }
}

// 보강 학생 목록 불러오기.
function classManAbsentList(){
    const modal = document.getElementById('class_man_modal_student_list');
    const class_seq = modal.querySelector('[data-class-seq]').value;
    const order_by = modal.querySelector('[data-order-by]').value;

    const page = "/teacher/after/class/management/absent/ref/select";
    const parameter = {
        class_seq: class_seq,
        order_by: order_by,
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            // teachConselStudentSelect()
            const bundle = modal.querySelector('[data-bundle="ref_students"]');
            const row_copy = modal.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

            const ref_students = result.ref_students;
            ref_students.forEach(student => {
                const row = row_copy.cloneNode(true);
                row.hidden = false;
                row.dataset.row = 'clone';
                row.querySelector('[data-grade-name]').innerText = student.grade_name + '' + (student.student_class_name||'미배정');
                row.querySelector('[data-class-name]').innerText = student.grade_name + '' + student.class_name;
                row.querySelector('[data-student-name]').innerText = student.student_name;
                row.querySelector('[data-student-id]').innerText = student.student_id;
                row.querySelector('[data-student-phone]').innerText = student.student_phone;
                row.querySelector('[data-absent-date]').innerText = student.absent_date.substr(2,8).replace(/-/g, '.') + ` 결석분`;
                row.querySelector('[data-absent-reason]').innerText = student.absent_reason;

                row.querySelector('[data-student-seq]').value = student.student_seq;
                row.querySelector('[data-absent-seq]').value = student.id;
                bundle.appendChild(row);
            });

        }else{}
    });
}

// 선택 학생 보강 추가.
function classManRefStudentInsert(){
    const modal = document.getElementById('class_man_modal_student_list');
    const bundle = modal.querySelector('[data-bundle="ref_students"]');
    const chks = bundle.querySelectorAll('[data-row="clone"] .chk:checked');

    // 학생이 한명도 선택이 안되어있으면 리턴.
    if(chks.length < 1){
        toast('선택된 학생이 없습니다.');
        return;
    }
    // 보강 시간 설정을 모두 선택 하지 않으면 리턴.
    const start_hour = modal.querySelector('[data-select-counsel-start-time="hour"]').value;
    const start_min = modal.querySelector('[data-select-counsel-start-time="min"]').value;
    const end_hour = modal.querySelector('[data-select-counsel-end-time="hour"]').value;
    const end_min = modal.querySelector('[data-select-counsel-end-time="min"]').value;

    if(start_hour == '' || start_min == '' || end_hour == '' || end_min == ''){
        toast('보강 시간을 설정해주세요.');
        return;
    }

    const absent_seqs = [];
    chks.forEach(function(chk){
        const row = chk.closest('[data-row="clone"]');
        const absent_seq = row.querySelector('[data-absent-seq]').value;
        absent_seqs.push(absent_seq);
    });

    const ref_start_time = start_hour + ':' + start_min;
    const ref_end_time = end_hour + ':' + end_min;
    const ref_date = document.querySelector('[data-sel-date]').value;

    const page = "/teacher/after/class/management/absent/ref/insert";
    const parameter = {
        absent_seqs: absent_seqs,
        ref_start_time:ref_start_time,
        ref_end_time: ref_end_time,
        ref_date:ref_date
    };

    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            toast('보강이 추가되었습니다.');
                // 캘린더 상담 목록 가져오기.
                classManClassCalendarSelect(true);
                // 오른쪽 상세 목록 가져오기.
                classManRightSideSelect(true);
            modal.querySelector('.modal_close').click();
        }else{}
    });
}


// 기간설정.
function clssManDateTimeSel(vthis) {
    //datetime-local format yyyy.MM.dd HH:mm 변경
    const date = new Date(vthis.value);
    vthis.closest('[data-bundle-date]').querySelector('[data-date]').innerText = date.format('yyyy.MM.dd')
}

// 기간설정 select onchange
function classManSelectDateType(vthis, start_date_tag, end_date_tag) {
    const inp_start = document.querySelector(start_date_tag);
    const inp_end = document.querySelector(end_date_tag);

    // 0 = 기간설정 지난1주일 // end_date 에서 -7일을 start_date에 넣어준다.
    if (vthis.value == 0) {
        const end_date = new Date(inp_end.value);
        end_date.setDate(end_date.getDate() - 7);
        inp_start.value = end_date.toISOString().substr(0, 10);
    }
    // 1 = 1개월
    else if (vthis.value == 1) {
        const end_date = new Date(inp_end.value);
        end_date.setMonth(end_date.getMonth() - 1);
        inp_start.value = end_date.toISOString().substr(0, 10);
    }
    // 2 = 3개월
    else if (vthis.value == 2) {
        const end_date = new Date(inp_end.value);
        end_date.setMonth(end_date.getMonth() - 3);
        inp_start.value = end_date.toISOString().substr(0, 10);
    }
    //-1 오늘
    else if (vthis.value == -1) {
        inp_start.value = '{{ date('Y-m-d') }}';
        inp_end.value = '{{ date('Y-m-d') }}';
    }
    // onchage()
    // onchange 이벤트가 있으면 실행
    if (inp_start.oninput)
        inp_start.oninput();
    if (inp_end.oninput)
        inp_end.oninput();
}

// 보강 리스트 전체목록보기 리스트 불러오기.
function classManViewFullListRefSelect(page_num){
    const main_div = document.querySelector('[data-div-main="2"]');
    const class_seq = main_div.querySelector('[data-select-class-seq]').value;
    const order_by = main_div.querySelector('[data-order-by-ref]').value;
    // 보강 완료, 예정, 미등록
    const is_ref_complete = main_div.querySelector('[data-is-ref-complete]').checked ? 'Y':'N';
    const is_ref_expected = main_div.querySelector('[data-is-ref-expected]').checked ? 'Y':'N';
    const is_ref_notthing = main_div.querySelector('[data-is-ref-notthing]').checked ? 'Y':'N';

    const start_date = main_div.querySelector('[data-search-start-date]').value;
    const end_date = main_div.querySelector('[data-search-end-date]').value;

    const page = "/teacher/after/class/management/absent/ref/all/select";
    const parameter = {
        class_seq: class_seq,
        order_by: order_by,
        is_ref_complete: is_ref_complete,
        is_ref_expected: is_ref_expected,
        is_ref_notthing: is_ref_notthing,
        start_date: start_date,
        end_date: end_date,
        page:page_num||1
    };

    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            // 초기화
            const bundle = main_div.querySelector('[data-bundle="reflist_students"]');
            const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

            // 페이징 선언.
            classManTablePaging(result.ref_students, '1');

            const ref_students = result.ref_students.data;
            const total_attend_cnts = result.total_attend_cnts;
            const attend_cnts = result.attend_cnts;
            ref_students.forEach(function(student){
                const row = row_copy.cloneNode(true);
                row.hidden = false;
                row.dataset.row = 'clone';

                row.querySelector('[data-grade-name]').innerText = student.grade_name + '' + (student.student_class_name||'');
                row.querySelector('[data-class-name]').innerText = student.grade_name + '' + student.class_name;

                row.querySelector('[data-student-name]').innerText = student.student_name;
                row.querySelector('[data-student-id]').innerText = student.student_id;
                row.querySelector('[data-student-phone]').innerText = student.student_phone;
                row.querySelector('[data-absent-date]').innerText = student.absent_date.substr(2,8).replace(/-/g, '.') + ` 결석분`;
                row.querySelector('[data-absent-reason]').innerText = student.absent_reason;
                if(attend_cnts)
                    row.querySelector('[data-attend-cnt]').innerText = getAttendCnt(attend_cnts[student.student_seq], student.class_seq);
                else
                    row.querySelector('[data-attend-cnt]').innerText = 0;
                if(total_attend_cnts)
                    row.querySelector('[data-class-total-cnt]').innerText = '/ '+getTotalAttendCnt(total_attend_cnts[student.student_seq], student.class_seq);
                else
                    row.querySelector('[data-class-total-cnt]').innerText = '/ '+0;
                let ref_datetime = '';
                if(student.ref_date) ref_datetime += student.ref_date.substr(2,8).replace(/-/g, '.');
                if(student.ref_start_time) ref_datetime += ' ' + student.ref_start_time.substr(0,5);
                row.querySelector('[data-ref-date-time]').innerText = ref_datetime;
                const is_ref_complete = student.is_ref_complete;
                if(is_ref_complete == 'Y'){
                    row.querySelector('[data-ref-complete]').hidden = false;
                }
                else if(!student.ref_date){
                    row.querySelector('[data-ref-incomplete]').hidden = false;
                    row.querySelector('[data-ref-date-time]').parentElement.hidden = true;
                    row.querySelector('[data-ref-incomplete]').parentElement.classList.add('all-center');
                }
                else{
                    row.querySelector('[data-btn-ref-complete]').hidden = false;
                    row.querySelector('[data-btn-chg-ref-date]').hidden = false;
                }

                row.querySelector('[data-student-seq]').value = student.student_seq;
                row.querySelector('[data-absent-seq]').value = student.id;
                bundle.appendChild(row);
                if(chk_students[student.id]){
                    row.querySelector('.chk').checked = true;
                }
            });
        }else{}
    });
}
// 출석횟수 가져오기.
function getAttendCnt(data, class_seq){
    let cnt = 0;
    if(!data) return cnt;
    data.forEach(function(d){
        if(class_seq == d.class_seq){
            cnt += d.cnt;
        }
    });
    return cnt;
}

// 총 출석해야할 횟수 가져오기.
function getTotalAttendCnt(data, class_seq){
    let cnt = 0;
    if(!data) return cnt;
    data.forEach(function(d){
        if(class_seq == d.class_seq){
            cnt += d.cnt;
        }
    });
    return cnt;
}

// 페이징  함수
function classManTablePaging(rData, target){
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
function classManPageFunc(target, type){
        if(type == 'next'){
            const page_next = document.querySelector(`[data-page-next="${target}"]`);
            if(page_next.getAttribute("data-is-next") == '0') return;
            // data-page 의 마지막 page_num 의 innerText를 가져온다
            const last_page = document.querySelector(`[data-page="${target}"] .page_num:last-of-type`).innerText;
            const page = parseInt(last_page) + 0;
            if(target == "1")
                 classManViewFullListRefSelect(page);
        }
        else if(type == 'prev'){
            // [data-page-first]  next tag 의 innerText를 가져온다
            const page_first = document.querySelector(`[data-page-first="${target}"]`);
            const page = page_first.innerText;
            if(page == 1) return;
            const page_num = page*1 -1;
            if(target == "1")
                 classManViewFullListRefSelect(page);
        }
        else{
            if(target == "1")
                 classManViewFullListRefSelect(type);
        }
}

// 보강완료로 변경 하는 버튼 클릭.
function classManChgRefComplete(vthis){
    const tr = vthis.closest('tr');
    const studnet_seq = tr.querySelector('[data-student-seq]').value;
    const absent_seq = tr.querySelector('[data-absent-seq]').value;

    const page = "/teacher/after/learning/management/class/student/reinforcement/end";
    const parameter = {
        student_seq: studnet_seq,
        absent_seq: absent_seq,
    };

    const msg = `
    <div class="text-sb-24px">보강완료 처리 하시겠습니까?</div>
    `;
    sAlert('', msg, 3, function(){
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                const page_num = document.querySelector('.page_num,active').innerText;
                classManViewFullListRefSelect(page_num);
                toast('보강완료 처리되었습니다.');
            }else{}
        });
    });
}
// 보강일전 변경.
function classManChgRefDate(vthis){
    const tr = vthis.closest('tr');
    const studnet_seq = tr.querySelector('[data-student-seq]').value;
    const absent_seq = tr.querySelector('[data-absent-seq]').value;

    const today = new Date().format('yyyy-MM-dd');
    const today_time = new Date().format('HH:mm');
    const msg = `
<div class="text-sb-24px"> 변경할 일정을 선택해주세요.</div>
<div class="all-center mt-3"> <input type="date" data-alert-date1 class="form-control text-sb-20px w-auto" value="${today}"> </div>
<div class="all-center mt-3"> <input type="time" data-alert-date2 class="form-control text-sb-20px w-auto" value="${today_time}"> </div>
<div class="all-center mt-3"> <input type="time" data-alert-date3 class="form-control text-sb-20px w-auto" value="${today_time}"> </div>
`;
    sAlert('', msg, 3, function(){
        const ref_date = document.querySelector('[data-alert-date1]').value;
        const ref_start_time = document.querySelector('[data-alert-date2]').value;
        const ref_end_time = document.querySelector('[data-alert-date3]').value;
        if(ref_date == '' || ref_start_time == '' || ref_end_time == ''){
            toast('변경할 일정을 선택해주세요.');
            return;
        }
        const page = '/teacher/after/class/management/absent/ref/date/update';
        const parameter = {
            absent_seq: absent_seq,
            student_seq: studnet_seq,
            ref_date: ref_date,
            ref_start_time: ref_start_time,
            ref_end_time: ref_end_time
        };
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                const page_num = document.querySelector('.page_num,active').innerText;
                classManViewFullListRefSelect(page_num);
                toast('보강일정이 변경되었습니다.');
            }else{}
        });

    });

}

// 체크 박스 클릭. //chk_students 배열 연결.
function classManChk(vthis){
    const tr = vthis.closest('tr');
    const absent_seq = tr.querySelector('[data-absent-seq]').value;
    const student_seq = tr.querySelector('[data-student-seq]').value;
    const student_name = tr.querySelector('[data-student-name]').innerText;
    // 체크 했으면, 배열
    if(vthis.checked){
        chk_students[absent_seq] = {
            student_seq:student_seq,
            absent_seq:absent_seq,
            student_name:student_name
        };
    }else{
        delete chk_students[absent_seq];
    }
}

// 선택 보강 등록 및 변경 모달 열기.
function classManRefAddModalOpen(){
    // 만약 선택한 학생이 없으면 리턴.
    if(Object.keys(chk_students).length < 1){
        toast('선택된 학생이 없습니다. 선택해주세요.');
        return;
    }
    // data-select-modal-title < 여기 전체목록 보기에서 선택한 학생 배열의 첫번째 학생 이름 + 00 명.
    const student_name = Object.values(chk_students)[0].student_name;
    const after_num = Object.keys(chk_students).length - 1 > 0 ? ' 외 ' + (Object.keys(chk_students).length - 1) + '명' : '';
    document.querySelector('[data-select-modal-title]').innerText = student_name + after_num;

    const myModal = new bootstrap.Modal(document.getElementById('counsel_modal_add'), {
        keyboard: false
    });
    myModal.show();
    setTimeout(() => {
        counsel_modal_calendar.render();
        const click_el = document.querySelector(
            `#counsel_modal_add [data-date="${new Date().format('yyyy-MM-dd')}"]`);
        click_el.querySelector('.fc-daygrid-day-top .fc-daygrid-day-number').classList.add('active');
        const select_date_str = new Date().format('yyyy.MM.dd E');
        document.querySelector('[data-p-counsel-seldate-modal]').innerText = select_date_str;

        classManClassCalendarSelect();
        // 오른쪽 상세 목록 가져오기.
        classManRightSideSelect();
    }, 200);
}

// 모달, 달력 오른쪽 사이드 목록 초기화.
function classManRightSideClear(is_main){
    let main_div = document.querySelector('#counsel_modal_add');
    if (is_main) main_div = document.querySelector('#teach_counsel_div_main');
    const bundle = main_div.querySelector('[data-ul-counsel-list-bundle]');
    const row_copy = bundle.querySelector('[data-li-counsel-list-row="copy"]').cloneNode(true);
    bundle.innerHTML = '';
    bundle.appendChild(row_copy);
}

// 전체목록 > 선택 보강 등록 및 변경 > 보강일정 등록하기
function classManRefStudentInsert2(){
    const modal = document.getElementById('counsel_modal_add');

    // 보강 시간 설정을 모두 선택 하지 않으면 리턴.
    const start_hour = modal.querySelector('[data-select-counsel-start-time="hour"]').value;
    const start_min = modal.querySelector('[data-select-counsel-start-time="min"]').value;
    const end_hour = modal.querySelector('[data-select-counsel-end-time="hour"]').value;
    const end_min = modal.querySelector('[data-select-counsel-end-time="min"]').value;

    if(start_hour == '' || start_min == '' || end_hour == '' || end_min == ''){
        toast('보강 시간을 설정해주세요.');
        return;
    }

    const absent_seqs = [];
    Object.values(chk_students).forEach(function(chk){
        absent_seqs.push(chk.absent_seq);
    })

    const ref_start_time = start_hour + ':' + start_min;
    const ref_end_time = end_hour + ':' + end_min;

    const ref_date = document.querySelector('[data-sel-date2]').value;

    const page = "/teacher/after/class/management/absent/ref/insert";
    const parameter = {
        absent_seqs: absent_seqs,
        ref_start_time:ref_start_time,
        ref_end_time: ref_end_time,
        ref_date:ref_date
    };

    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            toast('보강이 추가되었습니다.');
                // 캘린더 상담 목록 가져오기.
                classManClassCalendarSelect();
                // 오른쪽 상세 목록 가져오기.
                classManRightSideSelect();
                // 전체목록 보기
                const page_num = document.querySelector('.page_num,active').innerText;
                classManViewFullListRefSelect(page_num);
            modal.querySelector('.modal_close').click();
        }else{}
    });
}

// 일정관리로 돌아가기 ?
// TODO: 여기가 어딘지 알수가 없음. 추후 추가해야할듯. > 일단은 학습관리로 이동시킴.
function classManMoveScheduleManagement(){
    location.href = '/teacher/after/learning/management';
}

$(".modal").scroll(function(e) {
    if (window.innerWidth >= 1024) {
        var marginTop = $(this).find(".modal-dialog").css('margin-top');
        var marginTopValue = parseInt(marginTop, 10);
        console.log();
        if (marginTopValue <= $(this).scrollTop()) {
            $("#sticker").css("position", "relative");
            $("#sticker").css("top", $(this).scrollTop() - marginTopValue);
        } else {
            $("#sticker").css("top", "0");
        }
    } else {
        $("#sticker").css("position", "");
    }

});
</script>
@endsection
