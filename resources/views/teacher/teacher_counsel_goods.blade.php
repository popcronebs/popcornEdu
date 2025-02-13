@extends('layout.layout')

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
{{-- 추가 코드
    1. 상담추가 > 관리학생목록 > 만료임박회원 보기.
    2. 1주일 사이에 상태가 결제 완료가 되면 결제 완료 이후 예정된 이용권 상담은 다 사라짐
    3. 상담 생성 규칙
    (
        결제를 하지 않으면
        1주일 뒤 다음 상담 진행
        결제를 또 하지 않으면
        1주일 뒤 다음 상담 진행
        2회차 까지 반복하고
        3회차는 3개월 뒤로 잡힘
        4회차 3개월 뒤로 잡힘
        (4회차 안에 결제 거절이 되든, 결정 날 것으로 예상되므로 그 이후는 자동 x )
    )
--}}
@section('layout_coutent')
<style>
    select:focus {
        outline: none;
    }
</style>
<input type="hidden" data-group-type2 value="{{ $group_type2 }}">
    <div class="row pt-2">

        <div class="sub-title d-flex justify-content-between">
            <h2 class="text-sb-42px">
                <img src="{{ asset('images/ticket_icon.svg') }}" width="75">
                이용권 상담관리
            </h2>
            <div class="title-tab-button-wrap">
                <ul class="d-flex">
                    <li>
                        <button type="button" onclick="teachCounselTab(this)" data-teach-counsel-tab="calendar"
                            class="btn-ss-primary text-sb-20px rounded-pill scale-text-white active">캘린더보기</button>
                    </li>
                    <li class="ms-2">
                        <button type="button" onclick="teachCounselTab(this)" data-teach-counsel-tab="list"
                            class="btn-ss-primary text-sb-20px rounded-pill scale-bg-white scale-text-gray_05 scale-bg-gray_05-hover scale-text-white-hover">전체목록보기</button>
                    </li>
                </ul>
            </div>
        </div>
        {{-- 달력 FORM --}}
        <div class="row" id="teach_counsel_div_main" data-teach-counsel-main="calendar">
            <div class="col">
                {{-- 검색 --}}
                <div class="mb-52 d-flex">
                    <div class="d-inline-block select-wrap select-icon me-12">
                        <select data-select-teach-counsel-search-type="1"
                            class="rounded-pill border-gray lg-select text-sb-20px">
                            <option value="">검색기준</option>
                            <option value="student_name">학생이름</option>
                            <option value="student_phone">휴대폰 번호</option>
                            <option value="grade">학년</option>
                        </select>
                    </div>
                    <label class="label-search-wrap">
                        <input type="text" data-inp-teach-counsel-search-str="1"
                            onkeyup="if(event.keyCode == 13) teachCounselSelectCalendar(true);"
                            class="ms-search border-gray rounded-pill text-m-20px w-100" placeholder="단어를 검색해보세요.">
                    </label>
                </div>
                {{-- 달력 --}}
                <div id="todoListCalendarTypeTwo" class="todo-list-date date-type-1 modal-shadow-style rounded-4 mb-5">
                </div>
            </div>
            <div class="col-lg-4 col-md-12">
                {{-- 날짜, 총 건수 --}}
                <div class="d-flex justify-content-between align-items-center mb-52 h-62">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/yellow_calendar_icon.svg') }}" width="32">
                        <p class="text-b-24px" data-p-counsel-seldate></p>
                    </div>
                    <p class="text-m-24px">
                        <b data-counsel-total class="basic-text-error">총 0건</b>의 상담이 있습니다.
                    </p>
                </div>
                {{--  --}}
                <div>
                    <ul data-ul-counsel-list-bundle class="list-wrap type-1">
                        <li data-li-counsel-list-row="copy" class="div-shadow-style rounded-3 " hidden>
                            <input type="hidden" data-student-seq>
                            <input type="hidden" data-counsel-seq>
                            <input type="hidden" data-is-counsel>
                            <div class="d-flex justify-content-between px-4 py-5 dorp-click"
                                onclick="$(this).next('.drop-effect').slideToggle();">
                                <div class="">
                                    <p class="label-icon text-b-24px mb-2 h-center">
                                        <span data-student-name>#학생이름</span> <span class="ps-2">학생</span>
                                        <span data-grade-name>(#학년)</span>
                                    </p>
                                    <p class="text-m-20px gray-color d-flex">
                                        <span data-student-type>#신규/재등록</span>
                                        <span data-counsel-cnt></span>
                                         <span
                                            class="d-block border-gray mx-2 my-1"></span>
                                        <span data-goods-name>#마지막상품</span>
                                    </p>
                                    <p class="text-m-20px gray-color" hidden>
                                        <span data-group-name>#그룹이름</span> <span
                                            class="d-block border-gray mx-2 my-1"></span>
                                        <span data-counsel-type>#상담종류</span>
                                    </p>
                                </div>
                                <span data-counsel-start-end-time
                                    class="h-center ht-make-title studyColor-bg-studyComplete on text-sb-20px py-1 px-3 ms-1">#15:00-15:20</span>
                            </div>
                            <div class="px-4 pb-3 drop-effect" style="display: none;">
                                {{-- 이용권 기간 --}}
                                <div class="row w-100">
                                    <div class="col-4 p-0">
                                        <p
                                            class="ps-3 d-flex justify-content-start align-items-center scale-bg-gray_01 rounded-start text-sb-20px gray-color w-100 text-center h-62">
                                            이용권 기간</p>
                                    </div>
                                    <div class="col p-0 mb-2">
                                        <p
                                            class="d-flex justify-content-start align-items-center scale-bg-gray_01 black-color rounded-start text-sb-20px w-100 text-center h-62">
                                            <span data-goods-start-end-date></span>
                                            <b data-goods-remain-date class="studyColor-text-studyComplete ms-2">
                                            </b>
                                        </p>
                                    </div>
                                </div>
                                {{-- 학생 번호 --}}
                                <div class="row w-100">
                                    <div class="col-4 p-0">
                                        <p
                                            class="ps-3 d-flex justify-content-start align-items-center scale-bg-gray_01 rounded-start text-sb-20px gray-color w-100 text-center h-62">
                                            학생 번호</p>
                                    </div>
                                    <div class="col p-0 mb-2">
                                        <p data-student-phone
                                            class="d-flex justify-content-start align-items-center scale-bg-gray_01 black-color rounded-start text-sb-20px w-100 text-center h-62">
                                            #번호</p>
                                    </div>
                                </div>
                                {{-- 학부모 번호 --}}
                                <div class="row w-100">
                                    <div class="col-4 p-0">
                                        <p
                                            class="ps-3 d-flex justify-content-start align-items-center scale-bg-gray_01 rounded-start text-sb-20px gray-color w-100 text-center h-62">
                                            학부모 번호</p>
                                    </div>
                                    <div class="col p-0 mb-2">
                                        <p data-parent-phone
                                            class="d-flex justify-content-start align-items-center scale-bg-gray_01 black-color rounded-start text-sb-20px w-100 text-center h-62">
                                            #번호</p>
                                    </div>
                                </div>
                                {{-- 최근 상담일자 --}}
                                <div class="row w-100">
                                    <div class="col-4 p-0">
                                        <p
                                            class="ps-3 d-flex justify-content-start align-items-center scale-bg-gray_01 rounded-start text-sb-20px gray-color w-100 text-center h-62">
                                            최근 상담일자</p>
                                    </div>
                                    <div class="col p-0 mb-2">
                                        <p data-counsel-last-date
                                            class="d-flex justify-content-start align-items-center scale-bg-gray_01 black-color rounded-start text-sb-20px w-100 text-center h-62">
                                            #날짜</p>
                                    </div>
                                </div>
                                {{-- 다음 상담예정일 --}}
                                <div class="row w-100">
                                    <div class="col-4 p-0">
                                        <p
                                            class="ps-3 d-flex justify-content-start align-items-center scale-bg-gray_01 rounded-start text-sb-20px gray-color w-100 text-center h-62">
                                            다음 상담예정일</p>
                                    </div>
                                    <div class="col p-0 mb-2">
                                        <p data-counsel-next-date
                                            class="d-flex justify-content-start align-items-center scale-bg-gray_01 black-color rounded-start text-sb-20px w-100 text-center h-62">
                                            #날짜</p>
                                    </div>
                                </div>
                                <div class="d-flex mt-3 pt-3 justify-content-center">
                                    <div class="d-flex align-items-center" onclick="">
                                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M8.10905 12.6672V20.4036C8.10905 20.6862 8.33813 20.9153 8.62076 20.9153H23.3811C23.6638 20.9153 23.8928 20.6862 23.8928 20.4036V12.6692L16.8208 18.1696C16.3393 18.5441 15.6651 18.5441 15.1836 18.1696L8.10905 12.6672ZM22.124 10.6667H9.88043L16.0022 15.428L22.124 10.6667ZM8.62076 8C6.86539 8 5.44238 9.423 5.44238 11.1784V20.4036C5.44238 22.159 6.86541 23.5819 8.62076 23.5819H23.3811C25.1365 23.5819 26.5595 22.159 26.5595 20.4036V11.1784C26.5595 9.42301 25.1365 8 23.3811 8H8.62076Z"
                                                fill="#DCDCDC"></path>
                                        </svg>
                                        <p class="text-m-20px black-color ms-1">문자/알림톡 보내기</p>
                                    </div>
                                    <div class="d-flex align-items-center ms-2" onclick="">
                                        <img src="{{ asset('images/gray_gear_icon.svg') }}" width="32">
                                        <p class="text-m-20px black-color ms-1">일정 변경하기</p>
                                    </div>
                                </div>
                                <div data-div-counsel-expire>
                                    <div class="d-flex justify-content-center align-items-center mt-80">
                                        <img src="{{ asset('images/red_exclamation_icon.svg') }}" width="24">
                                        <p class="text-b-20px basic-text-error ">상담예정일이 오늘입니다.</p>
                                    </div>
                                </div>
                                <button type="button" onclick="teachCounselGoToStudentCounselGoods(this);" data-btn-counsel-go
                                    class="btn-line-lg-secondary scale-bg-white text-sb-24px rounded-3 scale-text-black justify-content-center w-100 mt-3 mb-1 primary-bg-mian-hover scale-text-white-hover">상담
                                    바로가기</button>
                            </div>
                        </li>
                    </ul>
                    {{-- <button type="button" class="" onclick="teachCounselStListModal();"
                  data-bs-toggle="modal" data-bs-target="#counsel_modal_add"> --}}
                    <button class="list-add-btn d-flex justify-content-center" onclick="teachCounselStListModal()">
                        <img src="{{ asset('images/gray_plus_icon.svg') }}" width="24">
                        상담일정 추가하기
                    </button>
                    {{-- <button type="button"
                  class="btn-lg-primary text-b-24px rounded-3 w-100 justify-content-center border-none scale-text-white mt-80"
                  data-bs-toggle="modal" data-bs-target="#modal-1">상담일정 등록하기</button> --}}
                </div>
            </div>
            {{-- 160px --}}
            <div>
                <div class="py-lg-5"></div>
                <div class="py-lg-4"></div>
                <div class="pt-lg-3"></div>
            </div>
        </div>
        <div class="row" id="teach_counsel_div_main2" data-teach-counsel-main="list" hidden>
            <table class="w-100 table-serach-style table-border-xless table-h-92 div-shadow-style rounded-3">
                <colgroup>
                    <col style="width: 33.33%;">
                    <col style="width: 33.33%;">
                    <col style="width: 33.33%;">
                </colgroup>
                <thead></thead>
                <tbody>
                    {{-- 소속을 선택해주세요. --}}
                    <tr class="text-start">
                        <td class="text-start p-4 scale-text-gray_06 py-4">
                            <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative align-items-center">
                                <select data-select-top="region" {{ $group_type2 != 'general' ? 'disabled':''}}
                                onchange="teachClGoodsSelectTop(this, 'region')"
                                class="border-none lg-select rounded-0 text-sb-20px {{ $group_type2 != 'general' ? 'scale-text-gray_05':''}} p-0 w-100 ">
                                    @if($group_type2 == 'general')
                                        <option value="">소속을 선택해주세요.</option>
                                    @endif
                                    @if(!empty($regions))
                                        @foreach($regions as $region)
                                            <option value="{{ $region->id }}">{{ $region->region_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0"
                                    alt="" width="32" height="32">
                            </div>
                        </td>
                        <td class="text-start px-4">
                            <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative align-items-center">
                                <select data-select-top="team" {{ $group_type2 != 'general' ? 'disabled':''}}
                                onchange="teachClGoodsSelectTop(this, 'team')"
                                class="border-none lg-select rounded-0 text-sb-20px {{  $group_type2 != 'general' && $group_type2 != 'leader' ? 'scale-text-gray_05':'' }} p-0 w-100">
                                    @if($group_type2 == 'general')
                                        <option value="">소속 팀을 선택해주세요.</option>
                                    @else
                                        @if(!empty($team))
                                            <option value="{{ $team->team_code }}">{{ $team->team_name }}</option>
                                        @endif
                                    @endif

                                </select>
                                <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0"
                                    alt="" width="32" height="32">
                            </div>
                        </td>
                        <td class="text-start p-4 scale-text-gray_06">
                            <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative align-items-center">
                                <select data-select-top="teacher" {{ $group_type2 != 'general' && $group_type2 != 'leader' ? 'disabled':''}}
                                onchange="teachClGoodsSelectTop(this, 'teacher')"
                                class="border-none lg-select rounded-0 text-sb-20px {{ $group_type2 != 'general' && $group_type2 != 'leader' ? 'scale-text-gray_05':''}} p-0 w-100">
                                    @if($group_type2 == 'general' || $group_type2 == 'team')
                                        <option value="">소속 선생님을 선택해주세요.</option>
                                    @else
                                        <option value="{{ session()->get('teach_seq') }}" selected>{{ session()->get('teach_name') }}</option>
                                    @endif
                                </select>
                                <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0"
                                    alt="" width="32" height="32">
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="row content-block mt-120">
                <div class="col-4">
                    <div data-tab-counsel-list="stay" onclick="teachClGoodsClickListTab(this)"
                        class="rounded-3 h-100 div-shadow-style d-flex justify-content-between align-items-center p-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover cursor-pointer active">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('images/mic_icon.svg') }}" width="32">
                            <p class="text-sb-24px  ms-1">상담 대기</p>
                        </div>
                        <div class="">
                            <p class="text-sb-24px "><b class="text-sb-42px" data-counsel-tab-cnt="stay">0</b>건</p>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div data-tab-counsel-list="complete" onclick="teachClGoodsClickListTab(this)"
                        class="rounded-3 h-100 div-shadow-style d-flex justify-content-between align-items-center p-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover cursor-pointer">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('images/mic_icon.svg') }}" width="32">
                            <p class="text-sb-24px  ms-1">상담 완료</p>
                        </div>
                        <div class="">
                            <p class="text-sb-24px "><b class="text-sb-42px " data-counsel-tab-cnt="complete">0</b>건</p>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div data-tab-counsel-list="transfer" onclick="teachClGoodsClickListTab(this)"
                        class="rounded-3 h-100 div-shadow-style d-flex justify-content-between align-items-center p-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover cursor-pointer">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('images/mic_icon.svg') }}" width="32">
                            <p class="text-sb-24px  ms-1">이관 요청</p>
                        </div>
                        <div class="">
                            <p class="text-sb-24px "><b class="text-sb-42px " data-counsel-tab-cnt="transfer">0</b>건</p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="d-flex justify-content-between align-items-end mt-120 mb-32">
                <div>
                    <label class="d-inline-block select-wrap select-icon h-62">
                        <select data-select-search-student-type onchange="teachClGoodsListCounselSelect();"
                            class="date-change rounded-pill  ps-4 border-gray sm-select text-sb-20px me-2 h-62">
                            <option value="">전체</option>
                            <option value="new">신규 회원 상담</option>
                            <option value="expire">만료 회원 상담</option>
                            <option value="dormant">휴면해제 회원 상담</option>
                        </select>
                    </label>
                    <span class="text-m-24px"><b class="studyColor-text-studyComplete" data-counsel-page-total>총 0건</b>의 조회 결과가 있습니다.</span>
                </div>

                <div class="h-center">
                    <label class="d-inline-block select-wrap select-icon h-62">
                        {{-- :기간설정  --}}
                        <select id="select2" onchange="teachClGoodsSelectDateType(this, '[data-search-start-date]','[data-search-end-date]');teachClGoodsListCounselSelect();"
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
                            oninput="teachClGoodsDateTimeSel(this)" value="{{ date('Y-m-d') }}">
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
                            oninput="teachClGoodsDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                        </div>

                    </div>
                </div>
            </div>

            <div class="dt-container dt-bootstrap5 dt-empty-footer">
                <div class="row justify-content-between">
                    <div class="col-md-auto me-auto "></div>
                    <div class="col-md-auto ms-auto "></div>
                </div>
                <div class="row justify-content-md-center">
                    <div class="col-12 ">
                        <table class="table-style w-100" style="min-width: 100%;" data-table="tb_counsel">
                            <colgroup>
                                <col style="width: 80px;">

                            </colgroup>
                            <thead class="">
                                <tr class="text-sb-20px modal-shadow-style rounded">
                                    <th style="width: 80px">
                                        {{-- :checkbox --}}
                                        <label class="checkbox mt-1">
                                            <input type="checkbox" class="" onchange="teachClGoodsCounselListCheckAll(this)">
                                            <span class="">
                                            </span>
                                        </label>
                                    </th>
                                    <th>구분</th>
                                    <th>상담횟수</th>
                                    <th>상태</th>
                                    <th>학생</th>
                                    <th class="is_complete is_transfer" hidden>학부모</th>
                                    <th class="is_stay is_transfer">지역</th>
                                    <th colspan="2" class="is_complete is_transfer" hidden>이용권 정보</th>
                                    <th class="is_stay">상담신청일시</th>
                                    <th class="is_stay">상담희망시간</th>
                                    <th colspan="2">배정정보</th>
                                    <th class="is_complete is_stay" hidden>상담내역</th>
                                    <th class="is_transfer" hidden>상태변경</th>
                                    @if($group_type2 != 'run')
                                    <th class="is_admin is_stay">상태변경</th>
                                    <th class="is_admin is_transfer" hidden>이관요청</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody data-bundle="tby_counsel">
                                <tr class="text-m-20px h-104" data-row="copy" hidden>
                                    <input type="hidden" data-counsel-seq>
                                    <input type="hidden" data-student-seq>
                                    <input type="hidden" data-transfer-reason>
                                    <input type="hidden" data-transfer-reg-date>
                                    <input type="hidden" data-region-seq>
                                    <input type="hidden" data-team-code>
                                    <input type="hidden" data-teach-seq>
                                    <td class="">
                                        <label class="checkbox mt-1">
                                            <input type="checkbox" class="chk" onchange="teachCounselChkInput(this)">
                                            <span class="">
                                            </span>
                                        </label>
                                    </td>
                                    <td class="scale-text-gray_05">
                                        <span data-student-type-detail data-value="구분"
                                            class="rounded-pill basic-bg-positie text-sb-16px ps-12 pe-12 py-1 scale-text-white">신규</span>
                                    </td>
                                    <td data-counsel-cnt data-value="#상담횟수#1회" class="scale-text-black"></td>
                                    <td class="scale-text-gray_05">
                                        <p class="scale-text-black" data-is-counsel>상담대기</p>
                                    </td>
                                    <td class="scale-text-gray_05">
                                        <span data-student-name></span>(<span data-grade-name></span>)
                                    </td>
                                    {{-- 완료시에만 보이게. --}}
                                    <td class="scale-text-gray_05 is_complete is_transfer" data-value="#김부모" data-parent-name hidden></td>
                                    <td class="scale-text-gray_05 is_stay is_transfer" data-student-address data-value="#부산광역시 해운대구"></td>
                                    <td class="scale-text-gray_05 is_complete is_transfer" hidden>
                                        <div data-goods-name></div>
                                        <div data-goods-period></div>
                                    </td>
                                    <td class="scale-text-gray_05 is_complete is_transfer" hidden>
                                        <div data-goods-start-date data-value="~부터"></div>
                                        <div data-goods-end-date data-value="~까지"></div>
                                    </td>
                                    <td class="scale-text-gray_05 is_stay" data-created-at data-value="#상담신청일시"> </td>
                                    <td class="scale-text-gray_05 is_stay">
                                        <p class="studyColor-text-studyComplete" data-start-end-date data-value="오전 09:00-11:00 ASAP"></p>
                                    </td>
                                    <td class="scale-text-gray_05">
                                        <span data-region-name></span><br class="is_transfer" hidden>
                                        <span data-team-name></span>
                                    </td>
                                    <td class="scale-text-gray_05" data-value="#박선생(상담)">
                                        <span data-teach-name></span><br class="is_transfer" hidden>(<span data-teach-group-name></span>)
                                    </td>
                                    <td class="scale-text-gray_05 is_complete is_stay">
                                        <button type="button" data-btn-counsel-update onclick="teachCounselGoToStudentCounselGoods2(this)"
                                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">
                                            {{-- 관리자/총괄/팀장 = 수정하기, 그외 상담진행 --}}
                                            @if($group_type2 == 'run')
                                                상담진행
                                            @else
                                                수정하기
                                            @endif
                                        </button>
                                    </td>
                                    {{-- 관리자/총괄/팀장만 보이게. --}}
                                    <td class="is_transfer" hidden data-transfer-is-move></td>
                                    @if($group_type2 != 'run')
                                    <td class="is_admin is_stay">
                                            <div class="d-inline-block select-wrap select-icon">
                                                <select onchange="teachClGoodsisCounselUpdate(this)"
                                                class="border-gray sm-select text-sb-20px h-42 py-0">
                                                <option value="">상태변경</option>
                                                <option value="N">상담대기</option>
                                                <option value="Y">상담완료</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td class="is_transfer" hidden>
                                        <button type="button" onclick="teachClGoodsModalTransferChkShow(this)"
                                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">
                                            내용확인
                                        </button>
                                    </td>
                                    @endif
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-52">
                <div class="">
                    <button type="button" onclick="teachClGoodsCounselMoveUpdateModalShow()" data-btn-move-counsel
                        class="btn-line-xss-secondary text-sb-20px border-dark rounded scale-bg-white scale-text-black px-3 me-2">
                        선택 이관요청
                    </button>
                    <button type="button" onclick="this.querySelector('select').click()" data-btn-chg-is-counsel
                        class="btn-line-xss-secondary  border-drak rounded scale-bg-white  px-3">
                        <select data-select-chg-is-counsel="main"
                        class="border-none text-sb-20px" onchange="teachClGoodsisCounselUpdate(this);">
                            <option value="">선택 상태변경</option>
                            <option value="N">상담대기</option>
                            <option value="Y">상담완료</option>
                        </select>
                    </button>
                </div>
                <div class="my-custom-pagination">
                    <div class="dt-paging paging_simple_numbers">
                        <ul class="pagination col-auto" data-ul-teach-counsel-page="1" hidden>
                            <button href="javascript:void(0)" class="btn p-0 prev" data-btn-teach-counsel-page-prev="1"
                                onclick="teachClGoodsPageFunc('1', 'prev')">
                                <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                            </button>
                            <li class="page-item" hidden>
                                <a class="page-link" onclick="">0</a>
                            </li>
                            <span class="page" data-span-teach-counsel-page-first="1" hidden
                                onclick="teachClGoodsPageFunc('1', this.innerText);" disabled>0</span>
                            <button href="javascript:void(0)" class="btn p-0 next" data-btn-teach-counsel-page-next="1"
                                onclick="teachClGoodsPageFunc('1', 'next')" data-is-next="0">
                                <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                            </button>
                        </ul>
                    </div>
                </div>
                <div class="h-center">
                    <button type="button" onclick="teachCounselExcelDownload()"
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M10.8688 16.625C10.2528 16.625 9.7535 16.1256 9.7535 15.5097V10.8559L6.87607 10.8559C5.84799 10.8559 5.33892 9.60777 6.07376 8.88877L11.1746 3.89782C11.6205 3.46152 12.3333 3.46152 12.7792 3.89782L17.8801 8.88877C18.6149 9.60777 18.1059 10.8559 17.0778 10.8559L14.1699 10.8559V15.5097C14.1699 16.1256 13.6706 16.625 13.0546 16.625H10.8688Z"
                                fill="#DCDCDC" />
                            <rect x="5.57031" y="17.8203" width="12.8027" height="1.75074" rx="0.875369"
                                fill="#DCDCDC" />
                        </svg>
                        Excel 내보내기
                    </button>
                    <button type="button" onclick="teachCounselSmsModalOpen();"
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">
                        문자(SMS) 전송
                    </button>
                </div>
            </div>
            {{-- 160px --}}
            <div>
                <div class="py-lg-5"></div>
                <div class="py-lg-4"></div>
                <div class="pt-lg-3"></div>
            </div>
        </div>
        {{-- 모달 / 정기상담일 등록 --}}
        @include('teacher.modal_counsel_add')

        {{-- 모달 / 담당 학생 목록 --}}
        <div class="modal fade" id="teach_counsel_modal_student_list" tabindex="-1" aria-hidden="true"
            style="display: none;">
            <div class="modal-dialog rounded modal-xl ">
                <div class="modal-content border-none rounded p-3 modal-shadow-style">
                    <div class="modal-header border-bottom-0">
                        <div class="d-flex justify-content-between w-100">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('images/yellow_calendar2_icon.svg') }}" alt="32">
                                <p class="text-b-24px ms-1">관리학생 목록</p>
                            </div>
                            <div>
                                <button type="button" for="teach_consel_chk_expire"
                                    class="btn-line-xss-secondary text-m-18px border-gray rounded scale-bg-white scale-text-black px-3">만료임박회원
                                    보기</button>
                                <input type="checkbox" class="form-check-input is_goods_expire"
                                    id="teach_consel_chk_expire" hidden>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row w-100 py-52">
                            <div class="col-2 ps-0">
                                <div class="d-inline-block select-wrap select-icon w-100">
                                    <select class="search_type border-gray lg-select text-ㅡ-20px w-100 h-62">
                                        <option value="">검색기준</option>
                                        <option value="student_name">학생이름</option>
                                        <option value="student_phone">휴대폰 번호</option>
                                        <option value="grade">학년</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-10 pe-0 ">
                                <label class="label-search-wrap h-62 w-100">
                                    <input type="text"
                                        class="search_str lg-search border-gray rounded rounded-3text-m-20px w-100"
                                        placeholder="원하시는 학년을 검색해주세요."
                                        onkeyup="if(event.keyCode == 13) teachCounselStListModalStudentSelect();">
                                </label>
                                <button hidden class="btn btn-sm btn-outline-secondary col-auto px-3"
                                    id="teach_counsel_btn_search" onclick=" teachCounselStListModalStudentSelect();">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"
                                        hidden=""></span>
                                    검색</button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center w-100">
                            <div>
                                <p class="text-sb-20px">다음 상담 예정일</p>
                            </div>
                            <div class="d-flex">
                                <label class="d-inline-block select-wrap select-icon">
                                    <select id="select2"
                                        onchange="teachCounselSelectDateType(this, '.next_counsel_date1', '.next_counsel_date2')"
                                        class="date-change rounded border-gray sm-select text-sb-20px me-2 h-52">
                                        <option value="">기간 설정</option>
                                        <option value="0">1주일전</option>
                                        <option value="1">1개월전</option>
                                        <option value="2">3개월전</option>
                                    </select>
                                </label>
                                <label style="width:340px;"
                                    class="smart-hb-input border-gray rounded text-m-20px gray-color text-center h-center px-3">
                                    <input type="date" class="next_counsel_date1 border-0 text-m-20px gray-color"
                                        value="{{ date('Y-m-d') }}" style="width:190px">~
                                    <input type="date" class="next_counsel_date2 border-0 text-m-20px gray-color"
                                        value="{{ date('Y-m-d') }}" style="width:190px">
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
                                        <th>이름/아이디</th>
                                        <th>학생 전화번호</th>
                                        <th>최근상담일자</th>
                                        <th>다음상담예정일</th>
                                    </tr>
                                </thead>
                                <tbody id="teach_counsel_tby_student_list">
                                    <tr class="copy_tr_student_list text-m-20px" hidden>
                                        <td class="py-2"
                                            onclick="event.stopPropagation();this.querySelector('input').click();">
                                            <label class="checkbox mt-1">
                                                <input type="checkbox" class="chk" onclick="event.stopPropagation();">
                                                <span class="" onclick="event.stopPropagation();">
                                                </span>
                                            </label>
                                        </td>
                                        <td class="py-2" data="#학년/반">
                                            <div class="school_name"></div>
                                            <span class="grade_name"></span>
                                        </td>
                                        <td class="py-2" data="#이름/아이디">
                                            <div class="student_name"></div>
                                            (<span class="student_id"></span>)
                                        </td>
                                        <td data="#학생 전화번호" class="py-2 student_phone"></td>
                                        <td data="#최근상담일자" class="py-2 recnt_counsel_date"></td>
                                        <td data="#다음상담예정일" class="py-2">
                                            <p class="next_counsel_date">23.09.01 14:20</p>
                                            <b class="secondary-text-mian is_change_target" hidden>(학부모 변경)</b>
                                        </td>
                                        <input type="hidden" class="student_seq">
                                        <input type="hidden" class="student_type">
                                    </tr>
                            </table>
                            {{-- 담당 학생 목록이 없습니다. id 추가 --}}
                            {{-- <div class="text-center" id="teach_counsel_div_no_student_list" hidden>
                            담당 학생 목록이 없습니다.
                        </div> --}}
                        </div>
                    </div>
                    <div class="modal-footer border-top-0">
                        <div class="col ps-0">
                            <button type="button"
                                class="modal_close btn-lg-secondary text-sb-20px rounded scale-bg-gray_01 scale-text-gray_05 w-100 justify-content-center"
                                data-bs-dismiss="modal">닫기</button>
                        </div>
                        <div class="col ps-0">
                            <button type="button" onclick="teachConselStudentSelect()"
                                class="btn-lg-primary text-b-24px rounded scale-text-white w-100 text-center justify-content-center">
                                선택 학생 추가</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- 모달/end --}}
        {{-- 모달 / 이관요청 --}}
      <div class="modal fade" id="teach_counsel_modal_move" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog rounded" style="max-width: 592px;">
        <div class="modal-content border-none rounded p-3 modal-shadow-style">
          <div class="modal-header border-bottom-0">
            <h1 class="modal-title fs-5 text-b-24px h-center" id="">
                <img src="{{ asset('images/window_none_icon.svg') }}" width="32">
                이관요청
            </h1>
            <button type="button" style="width:32px;height:32px;"
            class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p class="text-sb-20px mb-3">상담정보</p>
            <input type="hidden" data-counsel-seq>
            <table class="w-100 table-list-style table-border-xless table-h-92 mb-52">
              <colgroup>
                <col style="width: 33.33%;">
              </colgroup>
              <thead>
              </thead>
              <tbody>
                <tr class="">
                  <td class="text-start p-0 h-80">
                    <p class="text-sb-20px px-4">회원 정보</p>
                  </td>
                  <td class="text-start h-80 p-0">
                    <p data-transfer-student-info
                    class="text-sb-20px scale-text-black px-2">
                        {{-- <span class="rounded-pill basic-bg-positie text-sb-16px ps-12 pe-12 py-1 scale-text-white">만료</span>김팝콘(초3) --}}
                      </p>
                  </td>
                </tr>
                <tr class="">
                  <td class="text-start p-0 h-80">
                    <p class="text-sb-20px px-4">상담일자</p>
                  </td>
                  <td class="text-start h-80 p-0">
                    <p data-transfer-start-date
                    class="text-sb-20px px-2"></p>
                  </td>
                </tr>
              </tbody>
            </table>
            <table class="w-100 table-list-style table-border-xless table-h-92 mb-52">
              <colgroup>
                <col style="width: 33.33%;">
              </colgroup>
              <thead>
              </thead>
              <tbody>
                <tr class="">
                  <td class="text-start p-0 h-80">
                    <p class="text-sb-20px px-4">요청자 정보</p>
                  </td>
                  <td class="text-start h-80 p-0">
                    <p data-transfer-req-info
                    class="text-sb-20px scale-text-black px-2"></p>
                  </td>
                </tr>
                <tr class="">
                  <td class="text-start p-0 h-80">
                    <p class="text-sb-20px px-4">요청일시</p>
                  </td>
                  <td class="text-start h-80 p-0">
                    <p data-transfer-reg-date
                    class="text-sb-20px px-2"></p>
                  </td>
                </tr>
                <tr class="">
                  <td class="text-start p-0 h-80">
                    <p class="text-sb-20px px-4">요청사유</p>
                  </td>
                  <td class="text-start h-80 p-0 all-center">
                    <p data-transfer-reason
                    class="text-sb-20px px-0 w-100" style="max-width:370px" contenteditable="true"></p>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="h-62 px-4 px-3 border-gray rounded d-flex align-items-center">
            <label class="checkbox">
                <input type="checkbox" class="" data-chk-is-transfer-agree>
                <span class="rounded-pill"></span>
              </label>
              <p class="text-sb-20px ms-2">이관 절차에 관한 동의<b class="studyColor-text-studyComplete">(필수)</b></p>
            </div>
            <p class="text-center scale-text-gray_05 text-m-20px mt-12">※ 이관 요청은 즉시 실행되기 때문에 취소할 수 없습니다.</p>
          </div>

          <div class="modal-footer border-top-0 p-0 pb-2 mt-52">
            <div class="row w-100 ">

              <div class="col-12 ">
                <button type="button" onclick="teachClGoodsCounselMoveUpdate()"
                class="btn-lg-primary text-sb-24px rounded scale-text-white w-100 text-center justify-content-center">이관 요청하기</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- 모달 / 이관요청 확인 [총괄/팀장] --}}
    <div class="modal fade" id="teach_counsel_modal_move_chk" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-modal="true" role="dialog">
        <div class="modal-dialog rounded" style="max-width: 592px;">
        <div class="modal-content border-none rounded p-3 modal-shadow-style">
          <div class="modal-header border-bottom-0">
            <h1 class="h-center modal-title fs-5 text-b-24px" id="">
             <img src="{{ asset('images/window_none_icon.svg') }}" width="32">
              이관요청
            </h1>
            <button type="button" style="width:32px;height:32px"
            class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p class="text-sb-20px mb-3">상담정보</p>
            <input type="hidden" data-counsel-seq>
           <input type="hidden" data-teach-seq>
            <table class="w-100 table-list-style table-border-xless table-h-92 mb-52">
              <colgroup>
                <col style="width: 33.33%;">
              </colgroup>
              <thead>
              </thead>
              <tbody>
                <tr class="">
                  <td class="text-start p-0 h-80">
                    <p class="text-sb-20px px-4">회원 정보</p>
                  </td>
                  <td class="text-start h-80 p-0">
                    <p data-transfer-student-info
                    class="text-sb-20px scale-text-black px-2">
                        {{-- <span class="rounded-pill basic-bg-positie text-sb-16px ps-12 pe-12 py-1 scale-text-white">만료</span>김팝콘(초3) --}}
                      </p>
                  </td>
                </tr>
                <tr class="">
                  <td class="text-start p-0 h-80">
                    <p class="text-sb-20px px-4">상담일자</p>
                  </td>
                  <td class="text-start h-80 p-0">
                    <p data-transfer-start-date
                    class="text-sb-20px px-2"></p>
                  </td>
                </tr>
              </tbody>
            </table>
            <table class="w-100 table-list-style table-border-xless table-h-92 mb-52">
              <colgroup>
                <col style="width: 33.33%;">
              </colgroup>
              <thead>
              </thead>
              <tbody>
                <tr class="">
                  <td class="text-start p-0 h-80">
                    <p class="text-sb-20px px-4">요청자 정보</p>
                  </td>
                  <td class="text-start h-80 p-0">
                    <p data-transfer-req-info
                    class="text-sb-20px scale-text-black px-2"></p>
                  </td>
                </tr>
                <tr class="">
                  <td class="text-start p-0 h-80">
                    <p class="text-sb-20px px-4">요청일시</p>
                  </td>
                  <td class="text-start h-80 p-0">
                    <p data-transfer-reg-date
                    class="text-sb-20px px-2"></p>
                  </td>
                </tr>
                <tr class="">
                  <td class="text-start p-0 h-80">
                    <p class="text-sb-20px px-4">요청사유</p>
                  </td>
                  <td class="text-start h-80 p-0">
                    <p data-transfer-reason
                    class="text-sb-20px px-2 w-100" style="max-width:370px"></p>
                  </td>
                </tr>
              </tbody>
            </table>


            <p class="text-sb-20px mb-3">이관할 소속을 선택해주세요.</p>
            <div class="d-inline-block select-wrap {{ $group_type2 == 'general' ? 'select-icon':'scale-bg-gray_01' }} w-100 mb-52">
                <select data-select-modal="region" onchange="teachClGoodsSelectTop(this, 'region_modal');"
                class="border-gray sm-select text-sb-20px w-100 h-62 ps-4" {{ $group_type2 == 'general' ? '':'disabled' }}>
                    @if($group_type2 == 'general')
                        <option value="">소속 팀을 선택해주세요.</option>
                    @endif
                    @if(!empty($regions))
                        @foreach($regions as $region)
                            <option value="{{ $region->id }}">{{ $region->region_name }}</option>
                        @endforeach
                    @endif
                </select>
              </div>
            <p class="text-sb-20px mb-3">이관할 소속 팀을 선택해주세요.</p>
            <div class="d-inline-block select-wrap w-100 mb-52 {{  $group_type2 == 'general' ? 'select-icon':'scale-bg-gray_01'  }}">
                <select data-select-modal="team" onchange="teachClGoodsSelectTop(this, 'team_modal');"
                class="border-gray sm-select text-sb-20px w-100 h-62 ps-4" {{  $group_type2 == 'general' ? '':'disabled'  }}>
                    @if($group_type2 == 'general')
                        <option value="">소속 팀을 선택해주세요.</option>
                    @else
                        @if(!empty($team))
                            <option value="{{ $team->id }}">{{ $team->team_name }}</option>
                        @endif
                    @endif
                </select>
              </div>
            <p class="text-sb-20px mb-3">이관할 선생님을 선택해주세요.</p>
            <div class="d-inline-block select-wrap select-icon w-100 mb-52">
              <select data-select-modal="teacher"
              class="border-gray sm-select text-sb-20px w-100 h-62 ps-4">
                @if($group_type2 == 'general' || $group_type2 == 'team')
                    <option value="">소속 선생님을 선택해주세요.</option>
                @else
                    <option value="{{ session()->get('teach_seq') }}" selected>{{ session()->get('teach_name') }}</option>
                @endif
              </select>
            </div>
            <div class="h-62 px-4 px-3 border-gray rounded d-flex align-items-center">
                <label class="checkbox me-2">
                    <input type="checkbox" class="" data-chk-is-transfer-agree>
                    <span class="rounded-pill"></span>
                  </label>
              <p class="text-sb-20px">배정 변경 절차에 관한 동의 <b class="studyColor-text-studyComplete">(필수)</b></p>
            </div>
            <p class="text-center scale-text-gray_05 text-m-20px mt-12">※ 배정 변경은 즉시 실행되기 때문에 취소할 수 없습니다.</p>
          </div>

          <div class="modal-footer border-top-0 p-0 pb-2 mt-52">
            <div class="row w-100 ">

              <div class="col-12 ">
                <button type="button" onclick="teachClGoodsCounselMoveConfirmUpdate()"
                class="btn-lg-primary text-sb-24px rounded scale-text-white w-100 text-center justify-content-center">이관하기</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- 모달 / 상담일정 알림 발송 / 여기 안에 select_member 배열 있으므로, 확인 --}}
    @include('admin.admin_alarm_detail')
    </div>
    {{-- data-counsel-category 이부분으로 이용권을할지, 학습상담을 불러올지 구분. --}}
    <input type="hidden" data-main-counsel-category value="goods">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if(document.querySelector("[data-group-type2]").value == 'run')
                document.querySelector('[data-select-top="teacher"]').onchange();
        });
        // 풀캘린더 초기화
        var todoListCalendarTypeTwo = fullcalendarInit1();
        var counsel_modal_calendar = fullcalendarInit2();
        // 캘린더 상담 목록 가져오기.
        teachCounselSelectCalendar(true);

        function fullcalendarInit1() {

            document.querySelector('[data-p-counsel-seldate]').innerText = new Date().format('yyyy.MM.dd E');

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
                            teachCounselSelectCalendar(true);
                        }
                    },
                    myCustomNext: {
                        text: '',
                        click: function() {
                            todoListCalendarTypeTwo.next();
                            teachCounselSelectCalendar(true);
                        }
                    }
                },
                dateClick: function(eventClickInfo) {
                    this.select(eventClickInfo.dateStr);
                    var $clickedElement = $(eventClickInfo.dayEl).find(".fc-daygrid-day-top > a");
                    if ($clickedElement.hasClass("active")) {
                        $clickedElement.removeClass("active");
                        teachCounselClear(true);
                    } else {
                        $(".fc-daygrid-day-top > a.active").removeClass("active");
                        $clickedElement.addClass("active");
                        const select_date_str = eventClickInfo.date.format('yyyy.MM.dd E');
                        document.querySelector('[data-p-counsel-seldate]').innerText = select_date_str;
                        teachCounselSelect(true);
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
                    if (arg.event.title.includes('재등록상담')) {
                        return 'management-calendar type-1';
                    } else if (arg.event.title.includes('신규상담')) {
                        return 'management-calendar type-2';
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

            teachCounselSelect(true);
            return todoListCalendarTypeTwo;
        }

        // 캘린더보기, 전체목록보기 클릭.
        function teachCounselTab(vthis) {
            //클래스 대체.
            const tabs = document.querySelectorAll('[data-teach-counsel-tab]');
            tabs.forEach(function(tab) {
                tab.className =
                    'btn-ss-primary text-sb-20px rounded-pill scale-bg-white scale-text-gray_05 scale-bg-gray_05-hover scale-text-white-hover';
            });
            vthis.className = 'btn-ss-primary text-sb-20px rounded-pill scale-text-white active';

            const tab_type = vthis.getAttribute('data-teach-counsel-tab');
            // 표기 변경.
            const divs = document.querySelectorAll('[data-teach-counsel-main]');
            divs.forEach(function(div) {
                div.hidden = true;
            });
            document.querySelector(`[data-teach-counsel-main=${tab_type}]`).hidden = false;

            //관련 이벤트 필요하면 추가.
            if (tab_type == 'calendar') {
                todoListCalendarTypeTwo.render();
                teachCounselSelectCalendar(true);
            } else {
                //  teachClGoodsListCounselSelect('1');
            }
        }

        // 상담 추가 버튼 클릭
        // 데이터를 저장하기전 화면에만 내용을 추가.
        function teachCounselStListModal() {
            //clear function
            teachCounselStListModalClear();
            teachCounselStListModalStudentSelect();
            const myModal = new bootstrap.Modal(document.getElementById('teach_counsel_modal_student_list'), {});
            myModal.show();
        }

        // 담당 학생 목록 모달 초기화
        function teachCounselStListModalClear() {
            const modal = document.querySelector('#teach_counsel_modal_student_list');

            // 조건 검색
            modal.querySelector('.search_type').value = '';
            modal.querySelector('.search_str').value = '';
            modal.querySelector('.is_goods_expire').checked = false;
            modal.querySelector('.next_counsel_date1').value = new Date().format('yyyy-MM-dd');
            modal.querySelector('.next_counsel_date2').value = new Date().format('yyyy-MM-dd');

            // 학생 목록 테이블 초기화
            const copy_tr_student_list = modal.querySelector('.copy_tr_student_list').cloneNode(true);
            const tby_student_list = modal.querySelector('#teach_counsel_tby_student_list');
            tby_student_list.innerHTML = '';
            tby_student_list.appendChild(copy_tr_student_list);

            // 목록 없음 div
            // modal.querySelector('#teach_counsel_div_no_student_list').hidden = true;
        }

        // 담당 학생 목록 모달 학생 선택
        function teachCounselStListModalStudentSelect() {
            const modal = document.querySelector('#teach_counsel_modal_student_list');

            // 검색조건
            const search_type = modal.querySelector('.search_type').value;
            const search_str = modal.querySelector('.search_str').value;
            const is_goods_expire = modal.querySelector('.is_goods_expire').checked ? 'Y' : 'N';
            const next_counsel_date1 = modal.querySelector('.next_counsel_date1').value;
            const next_counsel_date2 = modal.querySelector('.next_counsel_date2').value;

            // 전송
            const page = "/teacher/counsel/student/select";
            const parameter = {
                search_type: search_type,
                search_str: search_str,
                is_goods_expire: is_goods_expire,
                next_counsel_date1: next_counsel_date1,
                next_counsel_date2: next_counsel_date2
            };
            // 로딩
            document.querySelector('#teach_counsel_btn_search > span').hidden = false;
            teachCounselStListDetail(false, modal, page, parameter);
        }
        //
        function teachCounselStListDetail(is_main, main_div, page, parameter) {
            queryFetch(page, parameter, function(result) {
                // 로딩 해제
                document.querySelector('#teach_counsel_btn_search > span').hidden = true;
                if ((result.resultCode || '') == 'success') {
                    //초기화
                    let copy_tr_student_list = null;
                    let tby_student_list = null;
                    if (is_main) {
                        tby_student_list = main_div.querySelector('#teach_counsel_tby_student_list2');
                        copy_tr_student_list = tby_student_list.querySelector('.copy_tr_student_list').cloneNode(
                            true);
                    } else {
                        copy_tr_student_list = main_div.querySelector('.copy_tr_student_list').cloneNode(true);
                        tby_student_list = main_div.querySelector('#teach_counsel_tby_student_list');
                    }
                    tby_student_list.innerHTML = '';
                    tby_student_list.appendChild(copy_tr_student_list);
                    const today_date = new Date().format('yyyy-MM-dd');
                    // 데이터 추가 result.students
                    result.students.forEach(function(student) {
                        const tr = copy_tr_student_list.cloneNode(true);
                        // tr class clone 해제 and add none copy
                        tr.classList.remove('copy_tr_student_list');
                        tr.classList.add('tr_student_list');
                        tr.querySelector('.school_name').innerText = student.school_name;
                        tr.querySelector('.grade_name').innerText = student.grade_name;
                        tr.querySelector('.student_name').innerText = student.student_name;
                        tr.querySelector('.student_id').innerText = student.student_id;
                        tr.querySelector('.student_phone').innerText = student.student_phone;
                        tr.querySelector('.recnt_counsel_date').innerText = student.recnt_counsel_date ||
                            '';
                        tr.querySelector('.next_counsel_date').innerText = student.next_counsel_date || '';
                        tr.querySelector('.student_seq').value = student.id;

                        let last_counsel_date = result.last_counsel_date_arr[student.id] || '';
                        tr.querySelector('.recnt_counsel_date').innerText = last_counsel_date.substr(0, 16)
                            .replace(/-/g, '.');
                        // next_counsel_date_arr
                        let next_counsel_date = result.next_counsel_date_arr[student.id] || '';
                        tr.querySelector('.next_counsel_date').innerText = next_counsel_date.substr(0, 25)
                            .replace(/-/g, '.').replace('00:00:00', '');;
                        const next_split = next_counsel_date.split('|')
                        if (next_split.length > 2 && next_split[1] == 'Y') {
                            //is_change_target
                            tr.querySelector('.is_change_target').hidden = false;
                            tr.querySelector('.is_change_target').innerText = next_split[2] == 'teacher' ?
                                '(선생님 변경)' : '(학부모 변경)';
                        }
                        // student_type 결제를 한번
                        if(student.payment_cnt < 1){
                            tr.querySelector('.student_type').value = 'new';
                        }else{
                            if(student.payment_cnt == 1 && (student.goods_end_date||'') < today_date){
                                tr.querySelector('.student_type').value = 'new';
                            }else{
                                tr.querySelector('.student_type').value = 'readd';
                            }
                        }

                        tr.hidden = false;
                        tby_student_list.appendChild(tr);
                    });
                }
            });
        }

        // 모달 / 선택 학생 추가.
        function teachConselStudentSelect(is_main) {
            // 우선 선택학생이 없으면 리턴
            let modal = document.querySelector('#teach_counsel_modal_student_list');
            if (is_main) {
                modal = document.querySelector('#teach_counsel_div_main2');
            }

            const chk = modal.querySelectorAll('.tr_student_list .chk:checked');
            if (chk.length == 0) {
                toast('선택된 학생이 없습니다.');
                return;
            }
            // 선택된 학생의 정보를 가져와서 배열로 저장
            const students = [];
            chk.forEach(function(chk) {
                const tr = chk.closest('tr');
                const student = {
                    school_name: tr.querySelector('.school_name').innerText,
                    grade_name: tr.querySelector('.grade_name').innerText,
                    student_name: tr.querySelector('.student_name').innerText,
                    student_id: tr.querySelector('.student_id').innerText,
                    student_phone: tr.querySelector('.student_phone').innerText,
                    recnt_counsel_date: tr.querySelector('.recnt_counsel_date').innerText,
                    next_counsel_date: tr.querySelector('.next_counsel_date').innerText,
                    student_seq: tr.querySelector('.student_seq').value,
                    student_type: tr.querySelector('.student_type').value
                };
                students.push(student);
            });
            if (is_main) {

            } else {
                //모달 닫기
                modal.querySelector('.modal_close').click();
            }
            teachCounselOpenRegularCounselModal(students);
            const modal_el = document.getElementById('counsel_modal_add');
            modal_el.querySelector('[data-select-modal-title]').value = 'no_regular';
            modal_el.querySelector('[data-select-modal-title]').onchange();
            modal_el.querySelector('[data-select-modal-title]').disabled = true;
            modal_el.querySelector('[data-select-modal-title]').selectedOptions[0].innerHTML = '이용권 상담 등록'
            const option_regular = modal_el.querySelector('[data-select-modal-title] [value="regular"]')
            option_regular ? option_regular.remove() : null;
        }

        // 상담일정 등록 버튼 클릭
        // 화면에 추가한 내용을 데이터로 저장.
        function teachCounselInsert(post_data) {
            const modal = document.querySelector('#counsel_modal_add');
            // 상담시간에서 시작시간과 끝시간이 선택이 되어있는지 확인 없으면 리턴. 단 미정이 체크되어있으면 통과.
            const start_time_hour = modal.querySelector('[data-select-counsel-start-time="hour"]').value;
            const start_time_min = modal.querySelector('[data-select-counsel-start-time="min"]').value;
            const end_time_hour = modal.querySelector('[data-select-counsel-end-time="hour"]').value;
            const end_time_min = modal.querySelector('[data-select-counsel-end-time="min"]').value;

            if (start_time_hour == '' || start_time_min == '' || end_time_hour == '' || end_time_min == '') {
                if (modal.querySelector('[data-chk-counsel-time-unknow]').checked == false) {
                    toast('상담시간을 선택해주세요.');
                    return;
                }
            }
            const start_time = `${start_time_hour}:${start_time_min}`;
            const end_time = `${end_time_hour}:${end_time_min}`;

            const sel_date_els = modal.querySelectorAll('.fc-daygrid-day-number.active');
            if (sel_date_els.length == 0) {
                toast('상담일을 선택해주세요.');
                return;
            }
            const student_els = modal.querySelectorAll('[data-student-select-row="clone"]');
            if (student_els.length == 0) {
                toast('학생을 선택해주세요.');
                return;
            }
            const student_seqs = [];
            const student_types = [];
            student_els.forEach(function(student_el) {
                const student_seq = student_el.querySelector('[data-student-seq]').value;
                const student_type = student_el.querySelector('[data-student-type]').value;
                student_seqs.push(student_seq);
                student_types.push(student_type);
            });

            const sel_date_el = modal.querySelector('.fc-daygrid-day-number.active');
            const sel_date = sel_date_el.closest('td').getAttribute('data-date');
            const counsel_type = modal.querySelector('[data-select-modal-title]').value;

            if (student_els.length > 1 && counsel_type == 'regular') {
                toast('다중학생은 수기상담만 가능합니다.');
                return;
            }

            if (!teachCounselInsertCheck()) {
                const msg =
                    `
                <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
                  <p class="modal-title text-center text-sb-28px alert-top-m-20" id="">이미 상담 일정이 존재합니다.</p>
                  <p class="modal-title text-center text-sb-28px alert-bottom-m studyColor-text-studyComplete" id="">다른 시간을 선택해주세요.</p>
                </div>
                `;
                sAlert('', msg, 4, null, null, '네 알겠습니다.');
                return;
            }
            // 상담일정변경으로 진입 했을때.
            // if (post_data != undefined) {
            //     teachCounselCounselChangeDateUpdate(post_data, sel_date, start_time, end_time);
            //     return;
            // }
            // 상담일정변경으로 진입하지 않았을때.
            const page = "/teacher/counsel/insert";
            const parameter = {
                student_seqs: student_seqs,
                student_types: student_types,
                counsel_category: 'goods',
                counsel_type: counsel_type,
                sel_date: sel_date,
                start_time: start_time,
                end_time: end_time,
            };

            let msg = '';
            const sel_student_name = student_els[0].querySelector('[data-student-name]').innerText;
            const sel_grade_name = student_els[0].querySelector('[data-grade-name]').innerText;

            const sel_count = student_seqs.length == 1 ? '' : ' 외 ' + (student_seqs.length - 1) + '명';
            const unkwon_chk = modal.querySelector('[data-chk-counsel-time-unknow]').checked;
            const start_date_str = sel_date.substr(5).replace('-', '월 ');
            const sel_time = unkwon_chk ? '시간미정' : `${start_time}분`;

            let msg1 = '';
            let msg2 = '';
            if (student_seqs.length != 1) {
                msg1 = `${sel_student_name}${sel_count} `;
                msg2 = `(여러명의 학생을 등록할 경우 수시상담만 가능합니다.)`;
            }

            msg =
                `
            <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
              <p class="modal-title text-center text-sb-20px gray-color" id="">${sel_student_name}(${sel_grade_name}) ${sel_count} 이용권상담일 등록</p>
              <p class="modal-title text-center text-sb-28px alert-top-m-20 mt-4"> ${msg1} ${start_date_str}일 ${sel_time}으로</p>
              <p class="modal-title text-center text-sb-28px alert-bottom-m studyColor-text-studyComplete" id="">상담을 등록하시겠습니까 ?</p>
              <p class="modal-title text-center text-sb-20px mt-2 pt-1 gray-color" id="">${msg2}</p>
            </div>
            `;

            sAlert('', msg, 3, function() {
                queryFetch(page, parameter, function(result) {
                        if ((result.resultCode || '') == 'success') {
                            // toast('상담이 등록되었습니다.');
                            const msg =
                                `
                 <span class="text-sb-28px">상담이 등록되었습니다.</span>
                `;
                            sAlert('', msg, 4);
                            modal.querySelector('.btn-close').click();
                            teachCounselSelect(true);
                            teachCounselSelectCalendar(true);
                            const page_num = document.querySelector('.page_num.active').innerText;
                             teachClGoodsListCounselSelect(page_num);

                        } else if ((result.resultCode || '') == 'already') {

                        }
                    }, function() {},
                    '네', '아니오');
            });
        }

        // 모달 / 정기상담 등록에서 뒤로가기 버튼 클릭.
        function teachCounselModalBack() {
            const openModal = document.querySelector('#teach_counsel_modal_student_list');
            const closeModal = document.querySelector('#counsel_modal_add');
            closeModal.querySelector('.btn-close').click();

            const myModal = new bootstrap.Modal(openModal, {
                backdrop: 'static',
                keyboard: false
            });
            myModal.show();
        }

        // 모달 / 정기상담 등록에서 학생 선택 버튼 클릭.
        function teachCounselClear(is_main) {
            let main_div = document.querySelector('#counsel_modal_add');
            if (is_main) main_div = document.querySelector('#teach_counsel_div_main');
            const bundle = main_div.querySelector('[data-ul-counsel-list-bundle]');
            const row_copy = bundle.querySelector('[data-li-counsel-list-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

        }

        // 상단 select_tag(el) 선택시
        function teachClGoodsSelectTop(vthis, type){
            if(type == 'region' || type == 'region_modal'){
                const region_seq = vthis.value;
                let is_modal = false;
                if(type == 'region_modal'){ is_modal = true; }
                teachClGoodsTeamSelect(region_seq, is_modal);
            }
            else if(type == 'team' || type == 'team_modal'){
                const team_code = vthis.value;
                let is_modal = false;
                if(type == 'team_modal'){ is_modal = true; }
                teachClGoodsTeacherSelect(team_code, is_modal);
            }
            else if(type == 'teacher'){
                teachClGoodsListCounselSelect();
            }
        }

        // 본부 선택시 팀 SELECT
        function teachClGoodsTeamSelect(region_seq, is_modal){
            const page = '/manage/useradd/team/select';
            const parameter = {
                region_seq: region_seq
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    let select_team = document.querySelector('[data-select-top="team"]');
                    if(is_modal){
                        select_team = document.querySelector('[data-select-modal="team"]');
                    }
                    select_team.innerHTML = '';
                    const option = document.createElement('option');
                    option.value = '';
                    option.innerText = '소속 팀을 선택해주세요.';
                    select_team.appendChild(option);
                    const teams = result.resultData;
                    teams.forEach(function(team){
                        const option = document.createElement('option');
                        option.value = team.team_code;
                        option.innerText = team.team_name;
                        select_team.appendChild(option);
                    });
                }
            });
        }

        // 팀 선택시 선생님 SELECT
        function teachClGoodsTeacherSelect(team_code, is_modal){
            const page = '/manage/userlist/teacher/select';
            const parameter = {
                serach_team: team_code
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    let select_teacher = document.querySelector('[data-select-top="teacher"]');
                    if(is_modal){
                        select_teacher = document.querySelector('[data-select-modal="teacher"]');
                    }
                    select_teacher.innerHTML = '';
                    const option = document.createElement('option');
                    option.value = '';
                    option.innerText = '소속 선생님을 선택해주세요.';
                    select_teacher.appendChild(option);
                    const teachers = result.resultData;
                    teachers.forEach(function(teacher){
                        const option = document.createElement('option');
                        option.value = teacher.id;
                        option.innerText = teacher.teach_name;
                        select_teacher.appendChild(option);
                    });
                }
            });
        }

        // 선생님 선택시 상담목록 가져오기.
        function teachClGoodsListCounselSelect(page_num){
            const teach_seq = document.querySelector('[data-select-top="teacher"]').value;
            // 검색 제한
            if(teach_seq == '') {
                toast('선생님을 선택해주세요.');
                return;
            }
            teachClGoodsListTabCntSelect(teach_seq);
            // 어차피 이용권 상담은 regular가 없지만 넣어는 둠.
            const counsel_types = ['regular', 'no_regular'];
            const counsel_category = 'goods';
            const get_type = 'page';
            const stay_el = document.querySelectorAll('[data-tab-counsel-list="stay"].active');
            let is_counsel = stay_el.length > 0 ? 'N':'Y';
            let is_transfer = 'N';
            const transfer_el = document.querySelectorAll('[data-tab-counsel-list="transfer"].active');
            if(transfer_el.length > 0){
                is_transfer = 'Y';
                is_counsel = '';
            }
            const search_student_type = document.querySelector('[data-select-search-student-type]').value;
            const search_start_date = document.querySelector('[data-search-start-date]').value;
            const search_end_date = document.querySelector('[data-search-end-date]').value;

            const page = '/teacher/counsel/select';
            const parameter = {
                counsel_types: counsel_types,
                counsel_category: counsel_category,
                is_counsel: is_counsel,
                get_type: get_type,
                page: page_num,
                teach_seq:teach_seq,
                is_transfer:is_transfer,
                page_max: 6,
                search_student_type:search_student_type,
                search_start_date:search_start_date,
                search_end_date:search_end_date
            };

            if(is_transfer == 'Y'){
                teachClGoodsListChangeUI('transfer')
            }else{
                if(is_counsel == 'Y'){
                    teachClGoodsListChangeUI('complete');
                }else{
                    teachClGoodsListChangeUI('stay')
                }
            }
            //초기화
            const bundle = document.querySelector('[data-bundle="tby_counsel"]');
            const copy_tr = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(copy_tr);
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    teachCounselTablePaging(result.counsels, '1')
                    teachClGoodsListCounselDetail(result, is_counsel, is_transfer);
                }
            });
        }

        // 이용권 상담목록 상단 탭 카운트 가져오기.
        function teachClGoodsListTabCntSelect(teach_seq){
            const page = '/teacher/counsel/goods/count/select';
            const parameter = {
                teach_seq: teach_seq
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    const stay_el = document.querySelector('[data-counsel-tab-cnt="stay"]');
                    stay_el.innerText = result.stay_cnt||'0';
                    const complete_el = document.querySelector('[data-counsel-tab-cnt="complete"]');
                    complete_el.innerText = result.complete_cnt||'0';
                    const transfer_el = document.querySelector('[data-counsel-tab-cnt="transfer"]');
                    transfer_el.innerText = result.transfer_cnt||'0';
                }
            });
        }

        // 이용권 상담목록 리스트 가져오기.
        function teachClGoodsListCounselDetail(result){
            const bundle = document.querySelector('[data-bundle="tby_counsel"]');
            const copy_tr = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(copy_tr);

            const today_str = new Date().format('yyyy-MM-dd');
            const total_el = document.querySelector('[data-counsel-page-total]');
            total_el.innerText = `총 ${result.counsels.total}건`;
            result.counsels.data.forEach(function(counsel) {
                const tr = copy_tr.cloneNode(true);
                tr.setAttribute('data-row', 'clone');
                tr.hidden = false;

                const is_goods = counsel.goods_name ? true:false;
                teachClGoodsGetTypeDetail(counsel, tr.querySelector('[data-student-type-detail]'));
                tr.querySelector('[data-counsel-seq]').value = counsel.id;
                tr.querySelector('[data-student-seq]').value = counsel.student_seq;
                tr.querySelector('[data-counsel-cnt]').innerText = counsel.counsel_cnt ? `${counsel.counsel_cnt}회`:'';
                tr.querySelector('[data-is-counsel]').innerText = counsel.is_counsel == 'Y' ? '상담완료' : '상담대기';
                tr.querySelector('[data-student-name]').innerText = counsel.student_name;
                tr.querySelector('[data-grade-name]').innerText = counsel.grade_name;
                tr.querySelector('[data-parent-name]').innerText = counsel.parent_name;
                tr.querySelector('[data-student-address]').innerText = teachClGoodsGetAddress(counsel.student_address);
                tr.querySelector('[data-goods-name]').innerText =  is_goods ? counsel.goods_name:'';
                tr.querySelector('[data-goods-period]').innerText = is_goods ? `${counsel.goods_period}개월 ${counsel.is_auto_pay == 'Y' ? '분납' : '완납'}`:'';
                tr.querySelector('[data-goods-start-date ]').innerText = is_goods ? `${counsel.goods_start_date} 부터`:'';
                tr.querySelector('[data-goods-end-date]').innerText = is_goods ? `${counsel.goods_end_date} 까지`:'';
                tr.querySelector('[data-created-at ]').innerText = counsel.created_at.substr(0, 16).replace(/-/g, '.');
                tr.querySelector('[data-start-end-date]').innerText = teachClGoodsGetStartEndDate(counsel);
                tr.querySelector('[data-start-end-date]').setAttribute('data-date', counsel.start_date.substr(2,8).replace(/-/g, '.')+' '+counsel.start_time.substr(0,5));
                tr.querySelector('[data-region-name]').innerText = counsel.region_name;
                tr.querySelector('[data-team-name]').innerText = counsel.team_name;
                tr.querySelector('[data-teach-name]').innerText = counsel.teach_name;
                tr.querySelector('[data-teach-group-name]').innerText = counsel.teach_group_name;
                tr.querySelector('[data-transfer-is-move]').innerText = (counsel.transfer_is_move||'') == 'Y' ? '이관완료':'이관 처리 중';
                tr.querySelector('[data-transfer-reg-date]').value = (counsel.transfer_reg_date||'').substr(2, 14).replace(/-/g, '.');
                tr.querySelector('[data-transfer-reason]').value = counsel.transfer_reason||'';
                tr.querySelector('[data-teach-seq]').value = counsel.teach_seq||'';
                tr.querySelector('[data-region-seq]').value = counsel.region_seq||'';
                tr.querySelector('[data-team-code]').value = counsel.team_code||'';


                bundle.appendChild(tr);
                if(chks[counsel.id]){
                    tr.querySelector('.chk').checked = true;
                }
            });
        }
          // :이용권상태 신규/만료임박/만료/재등록/휴먼회원
          function teachClGoodsGetTypeDetail(data, tag){
            const today_date = new Date().format('yyyy-MM-dd');
            if(data.student_type == 'new'){
                if(tag){
                    tag.innerText = '신규';
                }
                return '신규';
            }
            else if(data.student_type == 'readd'){
                //이용권 등록후 만료 1개월전
                if(data.goods_end_date < today_date){
                    if(tag) tag.innerText = '만료';
                    tag.classList.add('studyColor-bg-studyComplete');
                    tag.classList.remove('basic-bg-positie');
                    return '만료';
                }else if(data.goods_end_date >= today_date
                //data.goods_end_date에서 30일 뺀 날짜보다 오늘이 더 크면 만료임박
                && new Date(new Date(data.goods_end_date).getTime() - (30 * 24 * 60 * 60 * 1000)).format('yyyy-MM-dd') < today_date){
                    if(tag) tag.innerText = '만료임박';
                    return '만료임박';
                }
                // goods_end_date 가 오늘과 차이가 1년 이상일때
                else if(new Date(data.goods_end_date).getTime() - new Date(today_date).getTime() > (365 * 24 * 60 * 60 * 1000)){
                    if(tag){
                        tag.innerText = '휴면해제';
                        tag.classList.add('scale-bg-gray_01');
                        tag.classList.add('scale-text-gray_05');
                        tag.classList.remove('basic-bg-positie');
                        tag.classList.remove('scale-text-white');

                    }
                    return '휴면해제';
                }
                else{
                    if(tag) tag.innerText = '재등록';
                    return '재등록';
                }
            }
        }

        function teachClGoodsGetAddress(address){
            if(address){
                const address_arr = address.split(' ');
                return address_arr[0]||'' + ' ' + address_arr[1]||'';
            }
            else{
                return '';
            }
        }

        function teachClGoodsGetStartEndDate(counsel){
            const start_date_str = counsel.start_date.substr(2,8).replace(/-/g, '.');
            const start_time = counsel.start_time.substr(0,5);
            const end_time = counsel.end_time.substr(0,5);
            //오전/오후 + 시간
            const start_time_str = (start_time.substr(0,2) > 12 ? '오후 ' : '오전 ') + start_time.substr(0,5); ;
            let after = `${start_time_str} ~ ${end_time}`;
            if((counsel.start_time||'') == '') after = "ASAP";
            //날짜 + 시간
            const result = `${start_date_str} ${after}`;
            return result;

        }

        // 페이징 함수.
        function teachCounselTablePaging(rData, target) {
            const from = rData.from;
            const last_page = rData.last_page;
            const per_page = rData.per_page;
            const total = rData.total;
            const to = rData.to;
            const current_page = rData.current_page;
            const data = rData.data;
            //페이징 처리
            const notice_ul_page = document.querySelector(`[data-ul-teach-counsel-page='${target}']`);
            //prev button, next_button
            const page_prev = notice_ul_page.querySelector(`[data-btn-teach-counsel-page-prev='${target}']`);
            const page_next = notice_ul_page.querySelector(`[data-btn-teach-counsel-page-next='${target}']`);
            //페이징 처리를 위해 기존 페이지 삭제
            notice_ul_page.querySelectorAll(".page_num").forEach(element => {
                element.remove();
            });
            //#page_first 클론
            const page_first = document.querySelector(`[data-span-teach-counsel-page-first='${target}']`);
            //페이지는 1~10개 까지만 보여준다.
            let page_start = 1;
            let page_end = 10;
            if (current_page > 5) {
                page_start = current_page - 4;
                page_end = current_page + 5;
            }
            if (page_end > last_page) {
                page_end = last_page;
                if (page_end <= 10)
                    page_start = 1;
            }


            let is_next = false;
            for (let i = page_start; i <= page_end; i++) {
                const copy_page_first = page_first.cloneNode(true);
                copy_page_first.innerText = i;
                copy_page_first.removeAttribute("data-span-teach-counsel-page-first");
                copy_page_first.classList.add("page_num");
                copy_page_first.hidden = false;
                //현재 페이지면 active
                if (i == current_page) {
                    copy_page_first.classList.add("active");
                }
                //#page_first 뒤에 붙인다.
                notice_ul_page.insertBefore(copy_page_first, page_next);
                //시작 페이지 보다 11보다 큰 i 이면 page_prev disabled 을 제거
                if (i > 11) {
                    page_next.setAttribute("data-is-next", "1");
                    page_prev.classList.remove("disabled");
                } else {
                    page_next.setAttribute("data-is-next", "0");
                }
                if (i == 1) {
                    // page_prev.classList.add("disabled");
                }
                if (last_page == i) {
                    // page_next.classList.add("disabled");
                    is_next = true;
                }
            }
            if (!is_next) {
                page_next.classList.remove("disabled");
            }
            if (data.length != 0)
                notice_ul_page.hidden = false;
        }

        // 전체목록 > 상단 3개 탭 클릭
        function teachClGoodsClickListTab(vthis){
            //data-tab-counsel-list 모두 비활성화
            const tabs = document.querySelectorAll('[data-tab-counsel-list]');
            tabs.forEach(function(tab){
                tab.classList.remove('active');
            });
            //클릭한 탭 활성화
            vthis.classList.add('active');

            const list_type = vthis.getAttribute('data-tab-counsel-list');
            chks = {};
            teachClGoodsListCounselSelect();
        }

        // 선택 이관요청 모달 열기.
        function teachClGoodsCounselMoveUpdateModalShow(){
            const bundle = document.querySelector('[data-bundle="tby_counsel"]');
            const chks = bundle.querySelectorAll('[data-row="clone"] .chk:checked');
            if(chks.length == 0){
                toast('선택된 상담이 없습니다. 먼저 체크 후 진행해주세요.');
                return;
            }
            // 첫번째를 제외하고 모두 체크 해제
            // 복수는 안되고, 하나만 가능.
            chks.forEach(function(chk, idx){
                if(idx != 0) chk.checked = false;
            });
            const tr = chks[0].closest('tr');
            const counsel_seq = tr.querySelector('[data-counsel-seq]').value;

            const student_type = tr.querySelector('[data-student-type-detail]').outerHTML;
            const student_name = tr.querySelector('[data-student-name]').innerText;
            const grade_name = `(${tr.querySelector('[data-grade-name]').innerText})`;
            const start_date = tr.querySelector('[data-start-end-date]').innerText;

            const region_name = tr.querySelector('[data-region-name]').innerText;
            const team_name = tr.querySelector('[data-team-name]').innerText;
            const teach_name = tr.querySelector('[data-teach-name]').innerText;
            const teach_group_name = tr.querySelector('[data-teach-group-name]').innerText;

            const student_info =  `${student_type} ${student_name}${grade_name}`;
            const transfer_req_info = `${teach_name} / ${region_name} ${team_name} / ${teach_group_name}`;
            const transfer_reg_date = new Date().format('yy-MM-dd HH:mm').replace(/-/g, '.');
            const transfer_reason = '';

            const modal_el = document.getElementById('teach_counsel_modal_move');
            modal_el.querySelector('[data-counsel-seq]').value = counsel_seq;
            modal_el.querySelector('[data-transfer-student-info]').innerHTML = student_info;
            modal_el.querySelector('[data-transfer-req-info]').innerText = transfer_req_info;
            modal_el.querySelector('[data-transfer-reg-date ]').innerText = transfer_reg_date;
            modal_el.querySelector('[data-transfer-reason]').innerText = transfer_reason;
            modal_el.querySelector('[data-chk-is-transfer-agree]').checked = false;

            const myModal = new bootstrap.Modal(document.getElementById('teach_counsel_modal_move'), {
                backdrop: 'static',
                keyboard: false
            });
            myModal.show();
        }

        // 선택 이관 요청.
        function teachClGoodsCounselMoveUpdate(){
            const modal_el = document.getElementById('teach_counsel_modal_move');
            const counsel_seq = modal_el.querySelector('[data-counsel-seq]').value;
            const transfer_reason = modal_el.querySelector('[data-transfer-reason]').innerText;
            const transfer_reg_date = modal_el.querySelector('[data-transfer-reg-date]').innerText.replace(/\./g, '-');

            if(modal_el.querySelector('[data-chk-is-transfer-agree]').checked == false){
                toast('이관동의를 체크해주세요.');
                return;
            }
            if(transfer_reason == ''){
                toast('이관사유를 입력해주세요.');
                return;
            }

            const page = '/teacher/counsel/goods/transfer/insert';
            const parameter = {
                counsel_seq: counsel_seq,
                transfer_reason: transfer_reason,
                transfer_reg_date: transfer_reg_date
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    toast('이관요청이 완료되었습니다.');
                    modal_el.querySelector('.btn-close').click();
                    teachClGoodsListCounselSelect();
                }
            });
        }

        // 전체목록 > 상단 체크박스 클릭.
        function teachClGoodsCounselListCheckAll(vthis){
            const checked = vthis.checked;
            const bundle = document.querySelector('[data-bundle="tby_counsel"]');
            const chks = bundle.querySelectorAll('.chk');
            chks.forEach(function(chk){
                chk.checked = checked;
                teachCounselChkInput(chk);
            });
        }

        // 상담 바로가기
        function teachCounselGoToStudentCounselGoods(vthis) {
            const is_counsel = vthis.closest('[data-li-counsel-list-row]').querySelector('[data-is-counsel]').value;
            const li = vthis.closest('[data-li-counsel-list-row]');
            const student_seq = li.querySelector('[data-student-seq]').value;
            const counsel_seq = li.querySelector('[data-counsel-seq]').value;
            const xtken = document.querySelector('#csrf_token').value;

            let action = '';
            action = '/manage/counsel/goods/detail';
            const form = document.createElement('form');
            form.method = 'post';
            // form.action = '/manage/counsel/detail';
            form.action = action;
            form.target = '_self';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'student_seq';
            input.value = student_seq;
            form.appendChild(input);
            const input2 = document.createElement('input');
            input2.type = 'hidden';
            input2.name = 'counsel_seq';
            input2.value = counsel_seq;
            form.appendChild(input2);
            const input99 = document.createElement('input');
            input99.type = 'hidden';
            input99.name = '_token';
            input99.value = xtken;
            form.appendChild(input99);
            //is_before
            document.body.appendChild(form);
            // span class text-sb-28px> text <
            sAlert('', '<span class="text-sb-28px">이용권 상담 상세 페이지로 이동하시겠습니까?</span>', 3, function() {
                form.submit();
            });
        }
        // 상담바로가기2
        function teachCounselGoToStudentCounselGoods2(vthis){
            let tr = null;
            const bundle = document.querySelector('[data-bundle="tby_counsel"]');
            const chks = bundle.querySelectorAll('[data-row="clone"] .chk:checked');
            if(vthis){
                chks.forEach(function(chk, idx){
                    if(idx != 0) chk.checked = false;
                });
                tr = vthis.closest('tr');
            }else{
                if(chks.length == 0){
                    toast('선택된 상담이 없습니다. 먼저 체크 후 진행해주세요.');
                    return;
                }
                // 첫번째를 제외하고 모두 체크 해제
                // 복수는 안되고, 하나만 가능.
                chks.forEach(function(chk, idx){
                    if(idx != 0) chk.checked = false;
                });
                tr = chks[0].closest('tr');
            }

            const student_seq = tr.querySelector('[data-student-seq]').value;
            const counsel_seq = tr.querySelector('[data-counsel-seq]').value;
            const xtken = document.querySelector('#csrf_token').value;

            let action = '';
            action = '/manage/counsel/goods/detail';
            const form = document.createElement('form');
            form.method = 'post';
            // form.action = '/manage/counsel/detail';
            form.action = action;
            form.target = '_self';
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'student_seq';
            input.value = student_seq;
            form.appendChild(input);
            const input2 = document.createElement('input');
            input2.type = 'hidden';
            input2.name = 'counsel_seq';
            input2.value = counsel_seq;
            form.appendChild(input2);
            const input99 = document.createElement('input');
            input99.type = 'hidden';
            input99.name = '_token';
            input99.value = xtken;
            form.appendChild(input99);
            //is_before
            document.body.appendChild(form);
            // span class text-sb-28px> text <
            sAlert('', '<span class="text-sb-28px">이용권 상담 상세 페이지로 이동하시겠습니까?</span>', 3, function() {
                form.submit();
            });
        }

        // 대기, 완료, 이관요청에 따른 UI 변경.
        function teachClGoodsListChangeUI(type){
            const table = document.querySelector('[data-table="tb_counsel"]');
            const group_tye2 = document.querySelector('[data-group-type2]').value;
            table.querySelectorAll('.is_complete, .is_stay, .is_transfer').forEach(function(el){
               el.hidden = true;
            });
            // 선택 이관요청, 선택 상태변경
            const btn_move = document.querySelector('[data-btn-move-counsel]');
            const btn_chg = document.querySelector('[data-btn-chg-is-counsel]');
            //이관요청//----------------------------------------------
            if(type == 'transfer'){
                table.querySelectorAll('.is_transfer').forEach(function(el){
                    el.hidden = false;
                });
                btn_move.hidden = true;
                btn_chg.hidden = true;
            }
            // 상담완료//----------------------------------------------
            else if(type == 'complete'){
                table.querySelectorAll('[data-btn-counsel-update]').forEach(function(el){
                    //선생님일 경우
                    if(group_tye2 == 'run'){
                        el.innerText = '상담상세';
                    }else if(group_tye2 == 'general' || group_tye2 == 'leader'){
                        el.innerText = '상세보기';
                    }
                });
                table.querySelectorAll('.is_complete').forEach(function(el){
                    el.hidden = false;
                });
                btn_move.hidden = true;
                btn_chg.hidden = false;
            }
            // 상담대기//----------------------------------------------
            else if(type == 'stay'){
                //선생님일 경우
                table.querySelectorAll('[data-btn-counsel-update]').forEach(function(el){
                    if(group_tye2 == 'run'){
                        el.innerText = '상담진행';
                    }else if(group_tye2 == 'general' || group_tye2 == 'leader'){
                        el.innerText = '상세보기';
                    }
                });
                table.querySelectorAll('.is_stay').forEach(function(el){
                    el.hidden = false;
                });
                btn_move.hidden = false;
                btn_chg.hidden = false;
            }

        }

        // 기간설정 select onchange
        function teachClGoodsSelectDateType(vthis, start_date_tag, end_date_tag) {
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

        // 만든날짜 선택
        function teachClGoodsDateTimeSel(vthis) {
            //datetime-local format yyyy.MM.dd HH:mm 변경
            const date = new Date(vthis.value);
            vthis.closest('[data-bundle-date]').querySelector('[data-date]').innerText = date.format('yyyy.MM.dd')
        }

        //페이지 펑션
        function teachClGoodsPageFunc(target, type) {
            if (type == 'next') {
                const page_next = document.querySelector(`[data-btn-teach-counsel-page-next="${target}"]`);
                if (page_next.getAttribute("data-is-next") == '0') return;
                // data-ul-teach-counsel-page 의 마지막 page_num 의 innerText를 가져온다
                const last_page = document.querySelector(`[data-ul-teach-counsel-page="${target}"] .page_num:last-of-type`)
                    .innerText;
                const page = parseInt(last_page) + 1;
                if (target == "1")
                     teachClGoodsListCounselSelect(page)
            } else if (type == 'prev') {
                // [data-span-teach-counsel-page-first]  next tag 의 innerText를 가져온다
                const page_first = document.querySelector(`[data-span-teach-counsel-page-first="${target}"]`);
                const page = page_first.innerText;
                if (page == 1) return;
                const page_num = page * 1 - 1;
                if (target == "1")
                     teachClGoodsListCounselSelect(page)
            } else {
                if (target == "1")
                     teachClGoodsListCounselSelect(type)
            }
        }

        // 선택 상태변경
        function teachClGoodsisCounselUpdate(vthis){
            const bundle = document.querySelector('[data-bundle="tby_counsel"]');
            let chks = bundle.querySelectorAll('[data-row="clone"] .chk:checked');

            const is_counsel = vthis.value;
            let counsel_seqs = [];
            const teach_seq = document.querySelector('[data-select-top="teacher"]').value;

            // 선택없을시 리턴.
            if(is_counsel == '') return;

            // [테이블 하단 버튼] 에서 진입했을 때는 체크된 모든 상담을 변경한다.
            if(vthis.getAttribute('data-select-chg-is-counsel') == 'main'){
                if(chks.length == 0){
                    toast('선택된 상담이 없습니다. 먼저 체크 후 진행해주세요.');
                    return;
                }
            }
            // [테이블내부에서 진입] 했을 경우에는 모두 취소후 선택 row만 체크로 변경.
            else{
                chks.forEach(function(chk){
                    chk.checked = false;
                });
                vthis.closest('tr').querySelector('.chk').checked = true;
                chks = bundle.querySelectorAll('[data-row="clone"] .chk:checked');
            }
            chks.forEach(function(chk){
                counsel_seqs.push(chk.closest('tr').querySelector('[data-counsel-seq]').value);
            });

            const page = "/teacher/counsel/goods/is/counsel/update";
            const parameter = {
                counsel_seqs: counsel_seqs,
                is_counsel: is_counsel,
                teach_seq: teach_seq
            };
            const msg =
            `
                <span class="text-sb-28px">상담상태를 변경하시겠습니까?</span>
            `;
            sAlert('', msg, 3, function() {
                queryFetch(page, parameter, function(result){
                    if((result.resultCode||'') == 'success'){
                        toast('상담상태가 변경되었습니다.');
                        const page_num = document.querySelector('.page_num.active').innerText;
                        teachClGoodsListCounselSelect(page_num);
                    }
                });
            });
        }

        // 이관요청 확인 [총괄/팀장]
        function teachClGoodsModalTransferChkShow(vthis){
            const tr = vthis.closest('tr');
            const counsel_seq = tr.querySelector('[data-counsel-seq]').value;

            const student_type = tr.querySelector('[data-student-type-detail]').outerHTML;
            const student_name = tr.querySelector('[data-student-name]').innerText;
            const grade_name = `(${tr.querySelector('[data-grade-name]').innerText})`;
            const start_date = tr.querySelector('[data-start-end-date]').getAttribute('data-date');

            const region_name = tr.querySelector('[data-region-name]').innerText;
            const team_name = tr.querySelector('[data-team-name]').innerText;
            const teach_name = tr.querySelector('[data-teach-name]').innerText;
            const teach_group_name = tr.querySelector('[data-teach-group-name]').innerText;

            const student_info =  `${student_type} ${student_name}${grade_name}`;
            const transfer_req_info = `${teach_name} / ${region_name} ${team_name} / ${teach_group_name}`;
            const transfer_reg_date = tr.querySelector('[data-transfer-reg-date]').value;
            const transfer_reason = tr.querySelector('[data-transfer-reason]').value;
            const team_code = tr.querySelector('[data-team-code]').value;
            const region_seq = tr.querySelector('[data-region-seq]').value;
            const teach_seq = tr.querySelector('[data-teach-seq]').value;

            const modal_el = document.getElementById('teach_counsel_modal_move_chk');
            modal_el.querySelector('[data-counsel-seq]').value = counsel_seq;
            modal_el.querySelector('[data-transfer-student-info]').innerHTML = student_info;
            modal_el.querySelector('[data-transfer-req-info]').innerText = transfer_req_info;
            modal_el.querySelector('[data-transfer-reg-date ]').innerText = transfer_reg_date;
            modal_el.querySelector('[data-teach-seq]').value = teach_seq;
            modal_el.querySelector('[data-transfer-reason]').innerText = transfer_reason;
            modal_el.querySelector('[data-transfer-start-date]').innerText = start_date;
            modal_el.querySelector('[data-select-modal="region"]').value = region_seq;
            modal_el.querySelector('[data-select-modal="region"]').onchange();
            setTimeout(() => {
                modal_el.querySelector('[data-select-modal="team"]').value = team_code;
                modal_el.querySelector('[data-select-modal="team"]').onchange();
            }, 400);


            const myModal = new bootstrap.Modal(document.getElementById('teach_counsel_modal_move_chk'), {
                backdrop: 'static',
                keyboard: false
            });
            myModal.show();
        }

        // 이관하기.
        function teachClGoodsCounselMoveConfirmUpdate(){
            const modal_el = document.getElementById('teach_counsel_modal_move_chk');
            const counsel_seq = modal_el.querySelector('[data-counsel-seq]').value;
            const change_teach_seq = modal_el.querySelector('[data-select-modal="teacher"]').value;
            const before_teach_seq = modal_el.querySelector('[data-teach-seq]').value;

            if(modal_el.querySelector('[data-chk-is-transfer-agree]').checked == false){
                toast('배정 변경 절차에 관한 동의 체크해주세요.');
                return;
            }
            if(change_teach_seq == ''){
                toast('배정할 선생님을 선택해주세요.');
                return;
            }

            const page = '/teacher/counsel/goods/transfer/confirm/update';
            const parameter = {
                counsel_seq: counsel_seq,
                change_teach_seq: change_teach_seq,
                before_teach_seq: before_teach_seq
            };
            const msg =
            `
                <span class="text-sb-28px">이관 요청을 승인하시겠습니까?</span>
            `
            sAlert('', msg, 3, function(){
                queryFetch(page, parameter, function(result){
                    if((result.resultCode||'') == 'success'){
                        toast('이관이 완료되었습니다.');
                        modal_el.querySelector('.btn-close').click();
                        teachClGoodsListCounselSelect();
                    }else{
                        toast('다시 시도해주세요.');
                    }
                });
            });
        }

        let chks = {};
        // 체크박스 체크
        function teachCounselChkInput(vthis){
            const tr = vthis.closest('tr');
            const counsel_seq = tr.querySelector('[data-counsel-seq]').value;
            const student_seq = tr.querySelector('[data-student-seq]').value;
            if(vthis.checked){
                chks[counsel_seq] = {
                    counsel_seq:counsel_seq,
                    outer_html:tr.outerHTML,
                    student_seq:student_seq
                };
            }else{
                delete chks[counsel_seq];
            }
        }
        // 엑셀로 내보내기
        function teachCounselExcelDownload(){
            // 체크가 있는지 확인
            const keys = Object.keys(chks);
            if(keys.length < 1){
                toast('선택된 회원이 없습니다.');
                return;
            }

            const table = document.querySelector('[data-bundle="tby_counsel"]').closest('table').cloneNode(true);
            const bundle = table.querySelector('[data-bundle="tby_counsel"]');
            bundle.innerHTML = '';

            keys.forEach(function(key){
                bundle.innerHTML += chks[key].outer_html;
            });

            const html = table.outerHTML;
            _excelDown('상담목록.xls', '상담목록', html);
        }

        // 문자 모달 열기.
        async function teachCounselSmsModalOpen(){
            const keys = Object.keys(chks);
            if(keys.length < 1){
                toast('선택된 상담 리스트의 학생이 없습니다.');
                return;
            }
            // include 문자 안에 있는 함수이름.
            // yourFunction().then((success) => {
            //     console.log('Success:', success);
            // }).catch((error) => {
            //     console.log('Error:', error); // 여기서 error는 false가 됨
            // });
            // chks의 키를 student_seqs 에 넣어준다.
            const student_seqs = [];
            keys.forEach(function(key){
                student_seqs.push(chks[key].student_seq);
            });
            if(await alarmSendGetSmsInfo(student_seqs)){
                alarmSendSmsModalOpen();
                alarmSelectUser();
            }
        }
        //----------------------------------------------
        document.addEventListener('visibilitychange', function(event) {
            if (sessionStorage.getItem('isBackNavigation') === 'true') {
                console.log('뒤로 가기 버튼을 클릭한 후 페이지가 로드되었습니다.');
                // 여기에 뒤로 가기 버튼을 클릭한 후 페이지가 로드되었을 때 실행할 코드를 작성합니다.
                sessionStorage.removeItem('isBackNavigation'); // 상태를 초기화합니다.

                // 캘린더 상담 목록 가져오기.
                teachCounselSelectCalendar(true);
                teachCounselSelect(true);
                const teach_seq = document.querySelector('[data-select-top="teacher"]').value;
                teachClGoodsListCounselSelect();
            }
        });

    </script>

@endsection
