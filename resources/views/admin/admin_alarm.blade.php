@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title', '문자 / 알림 관리')

{{-- 네브바 체크 --}}
@section('alarm', 'active')
{{-- [코드 추가] --}}
{{--
    . 저장문구목록 > 수정하기. ok
    . 최근 발송목록 > 사용하기 / 기간설정 ok
    . 리스트들 모두 페이징으로 변경. - ok
    . 날짜 - 에서 .으로 변경
    . 예약 발송 목록 > 수정. / 디자인없음
    . TODO: 알림톡 최근 발송목록 기능 만들기.
--}}
{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<!-- :노트북 줌 -->
    <div class="zoom_sm">
        <div class="sub-title d-flex justify-content-between flex-column flex-sm-column flex-lg-row flex-row">
            <h2 class="text-sb-42px">
                <img src="{{ asset('images/sms_icon.svg') }}" width="72">
                <span class="me-2">문자 및 알림</span>
            </h2>
        </div>
        <div class="row row-gap-3">
            <div class="col-lg-3 col-sm-12 ">
                <div class="">
                    <ul class="tab py-4 px-4 div-shadow-style rounded-3">
                        <li class="">
                            <button data-btn-alarm-aside-tab="1" onclick="alertAsideTab(this);"
                                class="btn w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover h-center gap-1 active">
                                <img src="{{ asset('images/window_pen_icon.svg') }}" width="32">
                                문자 보내기
                            </button>
                        </li>
                        <li class="">
                            <button data-btn-alarm-aside-tab="2" onclick="alertAsideTab(this);"
                                class="btn w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover gap-1 h-center">
                                <img src="{{ asset('images/window_pen_icon.svg') }}" width="32">
                                저장문구 목록
                            </button>
                        </li>
                        <li class="">
                            <button data-btn-alarm-aside-tab="3" onclick="alertAsideTab(this);"
                                class="btn w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover gap-1 h-center">
                                <img src="{{ asset('images/window_pen_icon.svg') }}" width="32">
                                최근 발송 목록
                            </button>
                        </li>
                        <li class="">
                            <button data-btn-alarm-aside-tab="4" onclick="alertAsideTab(this);"
                                class="btn w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover gap-1 h-center">
                                <img src="{{ asset('images/window_pen_icon.svg') }}" width="32">
                                예약 발송 목록
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="py-4 px-12 div-shadow-style rounded-3 mt-4">
                    <div class="d-flex justify-content-between align-items-center px-12">
                        <p class="text-sb-24px">선택 학생 목록</p>
                        <button class="btn p-0 m-0 h-center active border-0" onclick="alarmSelStToggleList(this,true);">
                            <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32">
                        </button>
                    </div>
                    <ul data-select-student-list-bundle class="row row-gap-1 pt-4 px-12">
                        <li data-select-student-list-row="clone" class="col-6 p-1" hidden>
                            <div
                                class="d-flex justify-content-between align-items-center scale-bg-gray_01 px-20 py-2 rounded-pill">
                                <span class="text-m-20px scale-text-gray_05" data-student-name>김팝콘</span>
                                <button type="button" class="btn p-0 h-center" onclick="alarmSelMemberDel(this,true)">
                                    <img src="{{ asset('images/gray_x_icon.svg') }}" width="24">
                                </button>
                            </div>
                        </li>
                    </ul>
                </div>
                <button type="button" onclick="alarmSendSmsModalOpen();"
                    class="btn-lg-primary justify-content-center text-b-24px rounded-3 scale-text-white w-100 mt-32 ">문자 보내기</button>
            </div>

            <section data-secion-alarm-tab-sub="1" class="col-lg-9 h-100">
                <div class="d-flex justify-content-end align-items-center mb-32">
                    <div class="d-none">
                        <div class="d-inline-block select-wrap select-icon h-62 me-12">
                            <select class="rounded-pill border-gray lg-select text-sb-20px h-62" onchange="alarmSelectDateType(this,'[data-inp-sms-send-start-date]', '[data-inp-sms-send-end-date]')">
                                <option value="">기간 설정</option>
                                <option value="0">지난1주일</option>
                            </select>
                        </div>
                        <div class="border-gray rounded-pill h-62 px-32 h-center">
                            <input data-inp-sms-send-start-date type="date" class="border-0 text-m-20px gray-color" onchange="alarmPostDetailInfo('date');" value="{{ date('Y-m-d') }}">
                            ~
                            <input data-inp-sms-send-end-date type="date" class="border-0 text-m-20px gray-color" onchange="alarmPostDetailInfo('date');" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="d-inline-block select-wrap select-icon h-62 me-12">
                            <select data-select-sms-send-search1
                            class="rounded-pill border-gray lg-select text-sb-20px h-62" onchange="alarmPostDetailInfo('select1')">
                                <option selected="" value="name">이름</option>
                                <option  value="id">아이디</option>
                                <option value="phone">휴대폰번호</option>
                                <option value="school">학교</option>
                                {{-- <option value="grade">학년</option> --}}
                                <option value="ticket">이용권</option>
                                <option value="parent">학부모</option>
                                <option value="teacher">담당선생님</option>
                            </select>
                        </div>
                        <label class="label-search-wrap">
                            <input type="text" class="ms-search border-gray rounded-pill text-m-20px" data-input-sms-send-search1
                                onkeyup="if(event.keyCode==13){ alarmPostDetailInfo('input1'); alarmSelectUser(true);alarmSelectUser(); }" placeholder="학생 이름을 검색해주세요.">
                        </label>
                    </div>

                </div>

                <table class="table-style w-100" style="min-width: 100%;">
                    <thead class="">
                        <tr class="text-sb-20px modal-shadow-style rounded">
                            <th style="width: 80px">
                                <label class="checkbox mt-1">
                                    <input type="checkbox" onchange="alarmSelectUserAllChkbox(this, true);"
                                        onclick="event.stopPropagation();">
                                    <span class="">
                                    </span>
                                </label>
                            </th>
                            <th>학교/학년</th>
                            <th>이름/아이디</th>
                            <th>학생 휴대전화</th>
                            <th>학부모</th>
                            <th>학부모 휴대전화</th>
                        </tr>
                    </thead>
                    <tbody class="tby_student">
                        <tr class="text-m-20px copy_tr_student" hidden>
                            <input type="hidden" class="student_seq">
                            <input type="hidden" class="parent_seq">
                            <input type="hidden" class="push_key">
                            <td class=" py-2" onclick="event.stopPropagation();this.querySelector('input').click();">
                                <label class="checkbox mt-1">
                                    <input type="checkbox" class="chk" onchange="alarmSelMemberAdd(this,true);"
                                        onclick="event.stopPropagation();">
                                    <span class="" onclick="event.stopPropagation();">
                                    </span>
                                </label>
                            </td>
                            <td hidden class="group_type">
                            </td>
                            <td class=" py-2">
                                <p class="card-text placeholder-glow loding_place mb-0">
                                    <span class="placeholder col-12"></span>
                                </p>
                                <span class="gray-color school_name" data="#학교"></span>
                                <span class="gray-color grade" data="#학년"></span>
                            </td>
                            <td class=" py-2 ">
                                <p class="card-text placeholder-glow loding_place mb-0">
                                    <span class="placeholder col-12"></span>
                                </p>
                                <span class="gray-color student_name" data="#학생이름"></span>
                                <span class="gray-color student_id" data="#학생아이디"></span>
                            </td>
                            <td class=" py-2 student_phone" data="#학생 휴대전화">
                                <p class="card-text placeholder-glow loding_place mb-0">
                                    <span class="placeholder col-12"></span>
                                </p>
                            </td>
                            <td class=" py-2">
                                <p class="card-text placeholder-glow loding_place mb-0">
                                    <span class="placeholder col-12"></span>
                                </p>
                                <span class="gray-color parent_name" data="#학부모 이름"></span>
                                <span class="gray-color parent_id" data="#학부모아이디"></span>
                            </td>
                            <td class=" py-2 parent_phone" data="#학부모 휴대전화">
                                <p class="card-text placeholder-glow loding_place mb-0">
                                    <span class="placeholder col-12"></span>
                                </p>
                            </td>
                            <td hidden class="teach_name"></td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-flex justify-content-between mt-52">
                    <div></div>
                    {{-- 페이징  --}}
                    <div class="col d-flex justify-content-center">
                        <ul class="pagination col-auto" data-ul-alarm-page="1" hidden>
                            <button href="javascript:void(0)" class="btn p-0 prev" data-btn-alarm-page-prev="1"
                                onclick="alarmPageFunc('1', 'prev')">
                                <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                            </button>
                            <li class="page-item" hidden>
                                <a class="page-link" onclick="">0</a>
                            </li>
                            <span class="page" data-span-alarm-page-first="1" hidden
                                onclick="alarmPageFunc('1', this.innerText);" disabled>0</span>
                            <button href="javascript:void(0)" class="btn p-0 next" data-btn-alarm-page-next="1"
                            onclick="alarmPageFunc('1', 'next')" data-is-next="0">
                                <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                            </button>
                        </ul>
                    </div>
                    <div hidden>
                        <button type="button"
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black">선택 학생 추가</button>
                    </div>
                </div>
            </section>
            <section data-secion-alarm-tab-sub="2" class="col-lg-9 h-100" hidden>
                <div class="d-flex justify-content-between align-items-center mb-32">
                    <div>
                        <button type="button" onclick="alarmSaveStrDelete(true);"
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black">선택
                            삭제하기</button>
                    </div>
                    <div class="d-flex">
                        <div class="d-inline-block select-wrap select-icon h-62 me-12">
                            <select data-select-alarm-save-list
                                class="rounded-pill border-gray lg-select text-sb-20px h-62 ps-32"
                                onchange="alarmSaveStrTab(this)">
                                <option value="sms">SMS</option>
                                <option value="kakao">알림톡</option>
                                <option value="push">PUSH</option>
                            </select>
                        </div>
                    </div>
                </div>
                <table class="table-style w-100" style="min-width: 100%;">
                    <thead class="">
                        <tr class="text-sb-20px modal-shadow-style rounded">
                            <th style="width: 80px">
                                <label class="checkbox mt-1">
                                    <input type="checkbox" class="" onclick="alarmSaveStrAllChkbox(this);">
                                    <span class=""></span>
                                </label>
                            </th>
                            <th class="align-middle">구분</th>
                            <th class="align-middle">제목</th>
                            <th hidden class="kko_column">알림톡 코드</th>
                            <th class="align-middle">내용</th>
                            <th class="align-middle">-</th>
                            <th class="align-middle">-</th>
                        </tr>
                    </thead>
                    <tbody id="alarm_tby_save_str">
                        <tr class="copy_tr_save_str text-m-20px h-92" hidden>
                            <input type="hidden" class="mform_seq">
                            <input type="hidden" class="img_data">
                            <input type="hidden" class="img_size">
                            <input type="hidden" class="loding_place">
                            <input type="hidden" class="url" >
                            <td class=" py-2">
                                <label class="checkbox mt-1">
                                    <input type="checkbox" class="chk">
                                    <span class="">
                                    </span>
                                </label>
                            </td>
                            <td class=" py-2">
                                <p class="mform_type">SNS</p>
                            </td>
                            <td class="mform_title py-2">
                                제목이 들어갈 영역입니다.
                            </td>
                            <td hidden class="py-2 kko_column">
                                <p class="kko_code">#KKOCODE</p>
                            </td>
                            <td class="py-2">
                                <p class="mform_content gray-color"></p>
                                <img class="preview" src="" alt="" hidden>
                            </td>
                            <td class=" py-2">
                                <button type="button" onclick="alarmSaveStrEdit(this);" data-bs-toggle="modal"
                                    data-bs-target="#alarm_div_modal_save_str_edit"
                                    class="btn btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black">수정하기</button>
                            </td>
                            <td class=" py-2">
                                <button type="button" onclick="alarmSaveStrGet(this);"
                                    class="btn btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black">사용하기</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-flex justify-content-between mt-52">
                    <div></div>
                    {{-- 페이징  --}}
                    <div class="col d-flex justify-content-center">
                        <ul class="pagination col-auto" data-ul-alarm-page="2" hidden>
                            <button href="javascript:void(0)" class="btn p-0 prev" data-btn-alarm-page-prev="2"
                                onclick="alarmPageFunc('2', 'prev')">
                                <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                            </button>
                            <li class="page-item" hidden>
                                <a class="page-link" onclick="">0</a>
                            </li>
                            <span class="page" data-span-alarm-page-first="2" hidden
                                onclick="alarmPageFunc('2', this.innerText);" disabled>0</span>
                            <button href="javascript:void(0)" class="btn p-0 next" data-btn-alarm-page-next="2"
                            onclick="alarmPageFunc('2', 'next')" data-is-next="0">
                                <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                            </button>
                        </ul>
                    </div>
                    <div></div>
                </div>
            </section>
            <section data-secion-alarm-tab-sub="3" class="col-lg-9 h-100" hidden>
                <div class="d-flex justify-content-between align-items-center mb-32">
                    <div class="d-flex">
                        <div class="d-inline-block select-wrap select-icon h-62 me-12">
                            <select class="rounded-pill border-gray lg-select text-sb-20px ps-32 h-62">
                                <option value="">기간 설정</option>
                                <option value="0">지난1주일</option>
                            </select>
                        </div>
                        <div class="border-gray rounded-pill h-62 px-32 h-center">
                            {{-- 오늘로부터 일주일전 --}}
                            <input id="alarm_inp_last_start_date" type="date" class="border-0 text-m-20px gray-color"
                                value="{{ now()->subDays(7)->format('Y-m-d') }}">
                            ~
                            {{-- 오늘 --}}
                            <input id="alarm_inp_last_end_date" type="date" class="border-0 text-m-20px gray-color"
                                value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="d-inline-block select-wrap select-icon h-62 me-12">
                            <select data-select-alarm-send-list
                                class="rounded-pill border-gray lg-select text-sb-20px h-62 ps-32"
                                onchange="alarmLastSendTab(this)">
                                <option value="sms">SMS</option>
                                <option value="kakao">알림톡</option>
                                <option value="push">PUSH</option>
                            </select>

                        </div>
                        <label class="label-search-wrap">
                            <input
                                onkeyup="if(event.keyCode == 13){
                                alarmLastSendSelect(document.querySelector('[data-select-alarm-send-list]').value);}"
                                type="text" class="ms-search border-gray rounded-pill text-m-20px"
                                placeholder="학생 이름을 검색해주세요.">
                        </label>
                    </div>

                </div>

                <table class="table-style w-100" style="min-width: 100%;">
                    <thead class="">
                        <tr class="text-sb-20px modal-shadow-style rounded">
                            <th style="width: 80px">구분</th>
                            <th data-last-th-title>제목</th>
                            <th>내용</th>
                            <th>받는 사람</th>
                            <th>발송일</th>
                            <th>전송 상태</th>
                            <th>-</th>
                        </tr>
                    </thead>
                    <tbody id="alarm_tby_last_send">
                        <tr class="copy_tr_last_send text-m-20px h-92" hidden>
                            <input type="hidden" class="alarm_seq">
                            <td class="type py-2" data="#SMS"> </td>
                            <td class="title py-2" data="#학습 지도 안내"> </td>
                            <td class="content py-2" data="#내용이 들어갈 영역입니다..."> </td>
                            <td class="receiver py-2">
                                {{-- 이학생(초4) 학부모 <b class="studyColor-text-studyComplete">외 10명</b> --}}
                            </td>
                            <td class="send_date py-2">
                                {{-- 23.08.01 10:00 --}}
                            </td>
                            <td class=" py-2">
                                <a class="send_status" href="javascript:void(0)" data-bs-toggle="modal"
                                    data-bs-target="#alarm_div_modal_last_status"
                                    onclick="alarmGetLastReportDetail(this)">성공(1)</a>
                            </td>
                            <td class="py-2">
                                <button type="button" onclick="alarmUseStrGet(this);"
                                    class="use_btn btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black">사용하기</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-flex justify-content-between mt-52">
                    <div></div>
                    {{-- 페이징  --}}
                    <div class="col d-flex justify-content-center">
                        <ul class="pagination col-auto" data-ul-alarm-page="3" hidden>
                            <button href="javascript:void(0)" class="btn p-0 prev" data-btn-alarm-page-prev="3"
                                onclick="alarmPageFunc('3', 'prev')">
                                <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                            </button>
                            <li class="page-item" hidden>
                                <a class="page-link" onclick="">0</a>
                            </li>
                            <span class="page" data-span-alarm-page-first="3" hidden
                                onclick="alarmPageFunc('3', this.innerText);" disabled>0</span>
                            <button href="javascript:void(0)" class="btn p-0 next" data-btn-alarm-page-next="3"
                            onclick="alarmPageFunc('3', 'next')" data-is-next="0">
                                <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                            </button>
                        </ul>
                    </div>
                    <div></div>
                </div>

            </section>
            <section data-secion-alarm-tab-sub="4" class="col-lg-9 h-100" hidden>
                    <div class="d-flex justify-content-between align-items-center mb-32">
                      <div class="d-flex">
                        <div class="d-inline-block select-wrap select-icon h-62 me-12">
                          <select class="rounded-pill border-gray lg-select text-sb-20px ps-32 h-62">
                            <option value="">기간 설정</option>
                            <option value="0">지난 1주일</option>
                            <option value="1">Option 3</option>
                            <option value="2">Option 4</option>
                          </select>
                        </div>
                        <div class="border-gray rounded-pill h-62 px-32 h-center">
                            {{-- 오늘로부터 일주일전 --}}
                            <input id="alarm_inp_reserv_start_date" type="date" class="border-0 text-m-20px gray-color"
                                value="{{ now()->subDays(7)->format('Y-m-d') }}">
                            ~
                            {{-- 오늘 --}}
                            <input id="alarm_inp_reserv_end_date" type="date" class="border-0 text-m-20px gray-color"
                                value="{{ date('Y-m-d') }}">
                        </div>
                      </div>
                      <div class="d-flex">
                        <div class="d-inline-block select-wrap select-icon h-62 me-12">
                          <select data-select-alarm-reserv-list
                          class="rounded-pill border-gray lg-select text-sb-20px ps-32 h-62"
                          onchange="alarmReservTab(this);">
                            <option value="sms">SMS</option>
                            <option value="kakao">알림톡</option>
                            <option value="push">PUSH</option>
                          </select>
                        </div>
                        <label class="label-search-wrap">
                          <input onkeyup="if(event.keyCode==13){ alarmReservSelect(); }"
                          type="text" class="ms-search border-gray rounded-pill text-m-20px" placeholder="학생 이름을 검색해주세요.">
                        </label>
                      </div>

                    </div>

                    <table class="table-style w-100" style="min-width: 100%;">
                      <thead class="">
                        <tr class="text-sb-20px modal-shadow-style rounded">
                          <th style="width: 80px">구분</th>
                          <th style="min-width:200px;">내용</th>
                          <th>받는 사람</th>
                          <th>예약일</th>
                          <th>-</th>
                        </tr>
                      </thead>
                      <tbody id="alarm_tby_reserv">
                        <tr class="copy_tr_reserv text-m-20px h-92" hidden>
                            <input class="alarm_seq" type="hidden">
                            <input class="title" type="hidden">
                          <td class="type py-2">
                            <p class="card-text placeholder-glow loding_place mb-0">
                                <span class="placeholder col-12"></span>
                            </p>
                          </td>
                          <td class="content py-2">
                            <p class="card-text placeholder-glow loding_place mb-0">
                                <span class="placeholder col-12"></span>
                            </p>
                          </td>
                          <td class=" py-2">
                            <p class="card-text placeholder-glow loding_place mb-0">
                                <span class="placeholder col-12"></span>
                            </p>
                            <span class="receiver"></span>
                          </td>
                          <td class="rev_date py-2">
                            <p class="card-text placeholder-glow loding_place mb-0">
                                <span class="placeholder col-12"></span>
                            </p>
                          </td>
                          <td class="py-2">
                            <p class="card-text placeholder-glow loding_place mb-0">
                                <span class="placeholder col-12"></span>
                            </p>
                            <div class="btn_div" hidden>
                                <button type="button" onclick="alarmReservCancel(this);"
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black">
                                    <span class="sp_loding spinner-border spinner-border-sm me-1" aria-hidden="true" style="width:13px;height:13px;" hidden=""></span>
                                    예약취소
                                </button>
                                <button  class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black"
                                    data-bs-toggle="modal" aria-hidden="true" data-bs-target="#alarm_div_modal_reserv_edit" onclick="alarmReservEdit(this);" hidden>수정</button>
                            </div>
                          </td>
                          <td class="img_data" data="첨부파일" hidden>
                            <p class="card-text placeholder-glow loding_place mb-0"> <span class="placeholder col-12"></span> </p>
                            <img class="preview" style="width:50px;" hidden="">
                            </td>
                        </tr>
                      </tbody>
                    </table>
                    <div class="d-flex justify-content-between mt-52">
                      <div></div>
                        {{-- 페이징  --}}
                        <div class="col d-flex justify-content-center">
                            <ul class="pagination col-auto" data-ul-alarm-page="4" hidden>
                                <button href="javascript:void(0)" class="btn p-0 prev" data-btn-alarm-page-prev="4"
                                    onclick="alarmPageFunc('4', 'prev')">
                                    <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                                </button>
                                <li class="page-item" hidden>
                                    <a class="page-link" onclick="">0</a>
                                </li>
                                <span class="page" data-span-alarm-page-first="4" hidden
                                    onclick="alarmPageFunc('4', this.innerText);" disabled>0</span>
                                <button href="javascript:void(0)" class="btn p-0 next" data-btn-alarm-page-next="4"
                                onclick="alarmPageFunc('4', 'next')" data-is-next="0">
                                    <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                                </button>
                            </ul>
                        </div>
                      <div></div>
                    </div>
            </section>
        </div>

        {{-- 저장문구 목록안 수정기능 모달 --}}
        <div class="modal fade" id="alarm_div_modal_save_str_edit" tabindex="-1" aria-hidden="true"
            style="display: none;">
            <div class="modal-dialog rounded modal-lg">
                <input type="hidden" class="mform_seq">
                <div class="modal-content border-none rounded p-3 modal-shadow-style">
                    <div class="modal-header border-bottom-0">
                        <h1 class="modal-title fs-5 text-b-24px h-center" id="">
                            <img src="{{ asset('images/sms_icon.svg') }}" width="32">
                            저장된 문자 수정하기
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-size: auto;"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <p class="text-sb-20px mb-12">미리보기</p>
                                <div class="w-100">
                                    <label class="label-input-wrap w-100 mb-2 row gap-2">
                                        <input type="text" id="alarm_recipient-name"
                                            class="smart-ht-search border-gray rounded text-m-20px w-100 col"
                                            placeholder="제목">
                                        <input type="text" id="alarm_recipient_kko_code"
                                            class="smart-ht-search border-gray rounded text-m-20px w-100 col"
                                            placeholder="알림톡 코드" hidden>
                                    </label>
                                    <textarea name="" id="alarm_message-text"
                                        class="border-gray rounded text-r-20px w-100 textarea-resize-none mb-2 p-4" cols="30" rows="10"
                                        placeholder="내용을 입력해주세요"></textarea>
                                    <label class="label-input-wrap w-100 mb-2">
                                        <input type="text" id="alarm_recipient_kko_btn_url"
                                            class="smart-ht-search border-gray rounded text-m-20px w-100"
                                            placeholder="알림톡 버튼 URL" hidden>
                                    </label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="alarm_message-text" class="col-form-label">이미지:</label>
                                <div class="d-flex gap-3">
                                    <img id="alarm_img_file_edit" src="" alt="" style="height:150px">
                                    <div class="d-flex flex-column gap-2">
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="alarmSelImgDelete();return false;">이미지
                                            삭제</button>
                                        <button class="btn btn-sm btn-outline-secondary"
                                            onclick="document.querySelector('#alarm_inp_imgfile_edit').click();return false;">
                                            이미지 첨부
                                        </button>
                                        <input type="file" id="alarm_inp_imgfile_edit" accept="image/*"
                                            onchange="alarmUpdateImgFileChange(this, 'alarm_img_file_edit');return false;"
                                            hidden>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="alarmMessageEditSave();"
                            class="btn-lg-primary text-sb-24px rounded scale-text-white w-100 text-center justify-content-center">적용하기</button>
                        <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal" hidden
                            onclick="alarmMessageEditModalClear();">닫기</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 최근 발송 내역 - 전송상태 성공/실패 확인 모달 --}}
        <div class="modal fade" id="alarm_div_modal_last_status" tabindex="-1" aria-hidden="true"
            style="display: ;--bs-modal-width:60%">
            <div class="modal-dialog rounded modal-xl">
                <input type="hidden" class="mform_seq">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="d-flex justify-content-between align-items-center w-100 ">
                            <h1 class="modal-title fs-5 text-b-24px h-center" id="">
                                <img src="{{ asset('images/sms_icon.svg') }}" width="32">
                                전송 내역 상세
                            </h1>
                            <div hidden>
                                <div class="mt-2 d-flex align-items-center gap-2 px-4 mt-3" >
                                    <div class="me-4">
                                        <label for="alarm_chk_last_status_success" class="form-check-label"
                                            style="cursor:pointer;">전송성공</label>
                                        <input id="alarm_chk_last_status_success" class="form-check-input"
                                            type="checkbox" checked>

                                        <label for="alarm_chk_last_status_fail" class="form-check-label"
                                            style="cursor:pointer;">전송실패</label>
                                        <input id="alarm_chk_last_status_fail" class="form-check-input" type="checkbox"
                                            checked>

                                    </div>
                                    <span>검색단어</span>
                                    <input id="" type="text" class="form-control"
                                        style="width:250px;" placeholder="회원명 또는 내용"
                                        >
                                    <button id="alarm_btn_last_status_search" class="btn btn-outline-secondary"
                                        onclick="alarmLastStatusSelect();">
                                        <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true"
                                            hidden></span>
                                        검색
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-size: auto;"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex mb-52">
                            <div class="d-inline-block select-wrap select-icon h-62 pe-6">
                                <select class="border-gray lg-select text-sb-20px h-62">
                                    <option value="">전체 학년</option>
                                    <option value="0">1</option>
                                    <option value="1">2</option>
                                    <option value="2">3</option>
                                </select>
                            </div>
                            <label class="label-search-wrap ps-6 w-100">
                                <input id="alarm_inp_last_status_search_str" type="text" class="lg-search border-gray rounded text-m-20px w-100"
                                    placeholder="학생 이름을 검색해주세요." onkeyup="if(event.keyCode == 13) alarmLastStatusSelect()">
                            </label>
                        </div>
                        <div class="d-flex justify-content-end mb-32">
                            <div class="d-inline-block select-wrap select-icon scale-bg-white h-52 me-12">
                                <select class="border-gray lg-select text-sb-20px h-52 py-1 rounded">
                                    <option value="">기간설정</option>
                                    <option value="0"> 2</option>
                                    <option value="1"> 3</option>
                                    <option value="2"> 4</option>
                                </select>
                            </div>
                            <label class="label-date-wrap ">
                                <input type="text"
                                    class="select1 smart-ht-input border-gray rounded text-m-20px gray-color text-center h-52"
                                    readonly="" placeholder="">
                            </label>
                        </div>

                        {{-- 스크롤 --}}
                        <div class="overflow-auto tableFixedHead border-top" style="max-height:calc(100vh - 420px)">
                            <table class="table-style w-100" style="min-width: 100%;">
                                <thead class="">
                                    <tr class="text-sb-20px modal-shadow-style rounded">
                                        <th style="width: 80px">
                                            {{-- <input type="checkbox" onchange="alarmLastStatusAllChkbox(this);"> --}}
                                            -
                                        </th>
                                        <th>구분</th>
                                        <th>받는 사람</th>
                                        <th>발송일</th>
                                        <th>전송 상태</th>
                                        <th>-</th>
                                    </tr>
                                </thead>
                                <tbody id="alarm_tby_last_report">
                                    <tr class="copy_tr_last_report text-m-20px h-92" hidden>
                                        <input type="hidden" class="title">
                                        <input type="hidden" class="img_data">
                                        <td class=" py-2">
                                            <label class="checkbox">
                                                <input class="chk" type="checkbox" onclick="event.stopPropagation();">
                                                <span class=""></span>
                                            </label>
                                        </td>
                                        <td class="type py-2"></td>
                                        <td class=" py-2">
                                            <span class="recr_name"></span>/
                                            <span class="recr_phone"></span>
                                            <span class="content" hidden></span>
                                        </td>
                                        <td class="send_date py-2"></td>
                                        <td class="send_status py-2">실패</td>
                                        <td class="py-2">
                                            <button
                                                class="use_btn btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black"
                                                onclick="alarmLastStatusResend(this);return false;" hidden>재전송</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row w-100 ">
                            <div class="col-6 ps-0 pe-6">
                              <button type="button" data-bs-dismiss="modal"
                              class="modal_close btn-lg-secondary text-sb-24px rounded scale-bg-gray_01 scale-text-gray_05 w-100 justify-content-center">닫기</button>
                            </div>
                            <div class="col-6 ps-6 pe-0">
                              <button onclick="alarmLastStatusResendChk();" id="alarm_btn_last_status_resend"
                              type="button" class="btn-lg-primary text-sb-24px rounded scale-text-white w-100 text-center justify-content-center">
                              <span class="sp_loding spinner-border spinner-border text-white me-2" aria-hidden="true" hidden></span>
                              선택 문자 재전송
                            </button>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>

            {{-- 모달 예약 전송 수정  --}}
            <div class="modal fade" id="alarm_div_modal_reserv_edit" tabindex="-1" aria-hidden="true"
            style="display: none;">
            <div class="modal-dialog  modal-dialog-centered">
                <input type="hidden" class="alarm_seq">
                <input type="hidden" class="type">
                <input type="hidden" class="rev_date">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">예약 전송 목록 내용 수정</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="mb-3">
                                <label for="alarm_rev_recipient-name" class="col-form-label">제목:</label>
                                <input type="text" class="title form-control" id="alarm_rev_recipient-name">
                            </div>
                            <div class="mb-3">
                                <label for="alarm_rev_message-text" class="col-form-label">내용:</label>
                                <textarea class="content form-control" id="alarm_rev_message-text" rows="5"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="alarm_message-text" class="col-form-label">이미지:</label>
                                <div class="d-flex gap-3">
                                    <img class="img_data" id="alarm_img_rev_file_edit" src=""
                                        alt="" style="height:150px">
                                    <div class="d-flex flex-column gap-2">
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="document.querySelector('#alarm_img_rev_file_edit').src='';return false;">이미지
                                            삭제</button>
                                        <button class="btn btn-sm btn-outline-secondary"
                                            onclick="document.querySelector('#alarm_inp_rev_imgfile_edit').click();return false;">
                                            이미지 첨부
                                        </button>
                                        <input type="file" id="alarm_inp_rev_imgfile_edit" accept="image/*"
                                            onchange="alarmUpdateImgFileChange(this, 'alarm_img_rev_file_edit');return false;"
                                            hidden>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                            onclick="alarmMessageEditModalClear();">닫기</button>
                        <button type="button" class="btn btn-primary" onclick="alarmRevEditSave();">
                            <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true"
                                hidden></span>
                            내용 수정
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @include('admin.admin_alarm_detail')

        {{-- 160px --}}
        <div>
            <div class="py-lg-5"></div>
            <div class="py-lg-4"></div>
            <div class="pt-lg-3"></div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 팝업 (문자보내기) 에서 학생리스트 바로 불러오기.
            alarmSelectUser();
        });
        //
        function alertAsideTab(vthis) {
            const type = vthis.getAttribute('data-btn-alarm-aside-tab');
            // data-btn-alarm-aside-tab 비활성화
            document.querySelectorAll('[data-btn-alarm-aside-tab]').forEach((v) => {
                v.classList.remove('active');
            });
            // 활성화
            vthis.classList.add('active');

            // data-secion-alarm-tab-sub 비활성화
            document.querySelectorAll('[data-secion-alarm-tab-sub]').forEach((v) => {
                v.hidden = true;
            });
            // 활성화
            document.querySelector(`[data-secion-alarm-tab-sub="${type}"]`).hidden = false;

            switch (type) {
                // 저장 문구 목록
                case '2':
                    alarmSaveStrTab(document.querySelector('[data-select-alarm-save-list]'));
                    break;
            }
        }
        // 목록에서 내용 가져오기  안 3가지 종류 탭 클릭시
        function alarmSaveStrTab(vthis, page_num) {
            const type = vthis.value;
            const kko_columns = document.querySelectorAll('.kko_column');
            const alarm_recipient_kko_code = document.querySelector('#alarm_recipient_kko_code');
            const alarm_recipient_kko_btn_url = document.querySelector('#alarm_recipient_kko_btn_url');
            if(type == 'kakao'){
                alarm_recipient_kko_code.hidden = false;
                alarm_recipient_kko_btn_url.hidden = false;
                kko_columns.forEach(function(el){
                    el.hidden = false;
                });
            }else{
                alarm_recipient_kko_code.hidden = true;
                alarm_recipient_kko_btn_url.hidden = true;
                kko_columns.forEach(function(el){
                    el.hidden = true;
                });
            }
            // 목록 가져오기
            alarmSaveStrSelect(vthis.value, true, page_num);
        }


        // 목록(저장문구) 수정
        function alarmSaveStrEdit(vthis) {
            const tr = vthis.closest('tr');
            const mform_seq = tr.querySelector('.mform_seq').value;
            const mform_title = tr.querySelector('.mform_title').innerText;
            const mform_content = tr.querySelector('.mform_content').innerText;
            const img_data = tr.querySelector('.img_data').value;
            const kko_code = tr.querySelector('.kko_code').innerText;
            const url_str = tr.querySelector('.url').value;

            const alarm_div_modal_save_str_edit = document.querySelector('#alarm_div_modal_save_str_edit');
            document.querySelector('#alarm_inp_imgfile_edit').value = '';
            alarm_div_modal_save_str_edit.querySelector('.mform_seq').value = mform_seq;
            alarm_div_modal_save_str_edit.querySelector('#alarm_recipient-name').value = mform_title;
            alarm_div_modal_save_str_edit.querySelector('#alarm_message-text').value = mform_content;
            alarm_div_modal_save_str_edit.querySelector('#alarm_img_file_edit').src = img_data;
            alarm_div_modal_save_str_edit.querySelector('#alarm_recipient_kko_code').value = kko_code;
            alarm_div_modal_save_str_edit.querySelector('#alarm_recipient_kko_btn_url').value = url_str;
        }

        // 문자 내용 수정 모달 저장
        function alarmMessageEditSave() {
            const alarm_div_modal_save_str_edit = document.querySelector('#alarm_div_modal_save_str_edit');
            const mform_seq = alarm_div_modal_save_str_edit.querySelector('.mform_seq').value;
            const mform_title = alarm_div_modal_save_str_edit.querySelector('#alarm_recipient-name').value;
            const mform_content = alarm_div_modal_save_str_edit.querySelector('#alarm_message-text').value;
            const img_data = alarm_div_modal_save_str_edit.querySelector('#alarm_img_file_edit').src;
            const kko_code = alarm_div_modal_save_str_edit.querySelector('#alarm_recipient_kko_code').value;
            const url_str = alarm_div_modal_save_str_edit.querySelector('#alarm_recipient_kko_btn_url').value;

            // 제목이나 내용이 없으면 저장 안되게
            if (mform_title == '' || mform_content == '') {
                sAlert('', '제목이나 내용을 입력해주세요.');
                return false;
            }

            const page = "/manage/messageupdate";
            const parameter = {
                mform_seq: mform_seq,
                mform_title: mform_title,
                mform_content: mform_content,
                img_data: img_data,
                kko_code: kko_code,
                url_str: url_str
            };
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    const msg = '<riv class="text-sb-24px">수정되었습니다.</div>';
                    sAlert('', msg, 4);

                    //목록(저장문구) 가져오기. / table
                    const type = document.querySelector('[data-select-alarm-save-list]').value;
                    alarmSaveStrSelect(type,true);
                    // 문자 내용 수정 모달 초기화
                    alarmMessageEditModalClear();
                    //모달 버튼 닫기클릭
                    alarm_div_modal_save_str_edit.querySelector('.modal_close').click();
                } else {
                    toast('수정에 실패하였습니다.');
                }
            });
        }

        // 최근 발송 내역 안 3가지 종류 탭 클릭시
        function alarmLastSendTab(vthis) {
            //문자이면 구분 보이게 / 아니면 숨기기
            if (vthis.value == 'sms') {
                document.querySelectorAll('.search_category').forEach(function(el) {
                    el.hidden = false;
                });
            } else {
                document.querySelectorAll('.search_category').forEach(function(el) {
                    el.hidden = true;
                });
            }

            //최근 발송 내역
            document.querySelectorAll('#alarm_tby_last_send .tr_last_send').forEach(function(el) {
                el.remove();
            });
            alarmLastSendSelect(vthis.value);
        }

        // 최근 발송 내역 가져오기
        function alarmLastSendSelect(mform_type, page_num) {
            // 타입 가져오기
            // const mform_type = document.querySelector('#alarm_ul_last_send .active').getAttribute('type');
            const page = "/manage/send/" + mform_type + "/last";
            const start_date = document.querySelector('#alarm_inp_last_start_date').value;
            const end_date = document.querySelector('#alarm_inp_last_end_date').value;
            const sms_type = document.querySelector('#alarm_sel_category_sms').value; //sms, lms, mms 각각 단문, 장문, 이미지(멀티)
            const search_str = document.querySelector('#alarm_inp_last_search_str').value;
            const none_list = document.querySelector('#alarm_div_last_send_none');
            const parameter = {
                start_date: start_date,
                end_date: end_date,
                sms_type: sms_type,
                search_str: search_str,
                page:page_num,
                page_max:5
            };
            //로딩 기능
            none_list.hidden = true;
            document.querySelector('#alarm_btn_last_search span').hidden = false;
            if (document.querySelectorAll('#alarm_tby_last_send tr').length == 1)
                document.querySelector('#alarm_tby_last_send tr').hidden = false;

            queryFetch(page, parameter, function(result) {
                document.querySelector('#alarm_btn_last_search span').hidden = true;
                //초기화
                const alarm_tby_last_send = document.querySelector('#alarm_tby_last_send');
                const copy_tr = alarm_tby_last_send.querySelector('.copy_tr_last_send').cloneNode(true);
                alarm_tby_last_send.innerHTML = '';
                alarm_tby_last_send.appendChild(copy_tr);
                copy_tr.hidden = true;

                if ((result.resultCode || '') == 'success') {
                    // 페이징
                    tablePaging(result.messages_info, '3');
                    if(mform_type == 'kakao'){
                        document.querySelector('[ data-last-th-title ]').hidden = true;
                    }else{
                        document.querySelector('[ data-last-th-title ]').hidden = false;
                    }
                    for (let i = 0; i < result.messages_info.data.length; i++) {
                        let messages = result.messages_info.data[i];

                        if(mform_type == 'kakao'){
                            messages = alarmKakaoResChagData(messages);
                        }
                        const tr = copy_tr.cloneNode(true);
                        tr.classList.remove('copy_tr_last_send');
                        tr.classList.add('tr_last_send');
                        tr.hidden = false;
                        tr.querySelectorAll('.loding_place').forEach(function(el) {
                            el.remove();
                        });
                        tr.querySelector('.type').innerText = alarmGetLastType(messages.type);
                        tr.querySelector('.type').setAttribute('type', messages.type);
                        tr.querySelector('.title').innerText = messages.title;
                        //내용이 만약 20자리 이상이면 스크롤
                        if (messages.content.length > 50) {
                            // div를 만들어서 넣어주기
                            const c_div = document.createElement('div');
                            c_div.innerText = messages.content;
                            c_div.style.overflow = 'auto';
                            c_div.style.maxHeight = '100px';
                            tr.querySelector('.content').appendChild(c_div);
                        } else {
                            tr.querySelector('.content').innerText = messages.content;
                        }
                        tr.querySelector('.receiver').innerText = alamGetLastReceiver(messages);
                        tr.querySelector('.send_date').innerText = alamGetLastSendDate(messages.send_date);
                        tr.querySelector('.send_status').innerText = alarmGetLastStatus(messages);
                        if(mform_type == 'kakao'){
                            tr.querySelector('.use_btn').hidden = true;
                            tr.querySelector('.title').hidden = true;
                        }else{
                            tr.querySelector('.use_btn').hidden = false;
                            tr.querySelector('.title').hidden = false;
                        }
                        tr.querySelector('.alarm_seq').value = messages.alarm_seq;
                        alarm_tby_last_send.appendChild(tr);
                    }
                    // 툴팁 활성화
                    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
                    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(
                        tooltipTriggerEl))
                    rev_disabled = false;
                }
                //tr 이 없으면 none_list 보여주기
                if (document.querySelectorAll('#alarm_tby_last_send .tr_last_send').length == 0)
                    none_list.hidden = false;
                else
                    none_list.hidden = true;
            });
        }

        // 최근 발송내역 상세보기
        function alarmGetLastReportDetail(vthis) {
            //탭에서 타입가져오기
            const last_type = document.querySelector('[data-select-alarm-send-list]').value;
            //검색 로딩
            const spin_loding = document.querySelector('#alarm_btn_last_status_search .sp_loding');

            const suc_chk = document.querySelector('#alarm_chk_last_status_success').checked;
            const fail_chk = document.querySelector('#alarm_chk_last_status_fail').checked;

            spin_loding.hidden = false;

            //테이블에 정보 받아서 넣기.
            const tr = vthis.closest('tr');
            const alarm_seq = tr.querySelector('.alarm_seq').value;
            const sms_type = tr.querySelector('.type').getAttribute('type');

            const page = "/manage/send/" + last_type + "/reportdetail";
            const parameter = {
                alarm_seq: alarm_seq,
                sms_type: sms_type
            };
            //초기화
            const alarm_tby_last_report = document.querySelector('#alarm_tby_last_report');
            const copy_tr = alarm_tby_last_report.querySelector('.copy_tr_last_report').cloneNode(true);
            alarm_tby_last_report.innerHTML = '';
            alarm_tby_last_report.appendChild(copy_tr);
            copy_tr.hidden = true;

            queryFetch(page, parameter, function(result) {
                //로딩 숨기기
                spin_loding.hidden = true;
                copy_tr.hidden = true;

                if ((result.resultCode || '') == 'success') {

                    for (let i = 0; i < result.reports.length; i++) {
                        const tr = copy_tr.cloneNode(true);
                        let report = result.reports[i];

                        tr.classList.remove('copy_tr_last_report');
                        tr.classList.add('tr_last_report');
                        // tr.hidden = false;
                        tr.querySelectorAll('.loding_place').forEach(function(el) {
                            el.remove();
                        });
                        tr.querySelector('.type').innerText = alarmGetLastType(report.type);
                        tr.querySelector('.type').setAttribute('data', report.type);
                        // content 40자리 이상이면 40자리만 보여주고 스크롤 처리
                        // tr.querySelector('.content').innerText = report.content;
                        if (report.content.length > 40) {
                            // div를 만들어서 넣어주기
                            const c_div = document.createElement('div');
                            c_div.innerText = report.content;
                            c_div.style.overflow = 'auto';
                            c_div.style.maxHeight = '100px';
                            tr.querySelector('.content').appendChild(c_div);
                        } else {
                            tr.querySelector('.content').innerText = report.content;
                        }
                        tr.querySelector('.title').value = report.title;
                        tr.querySelector('.img_data').value = report.img_data;
                        tr.querySelector('.recr_name').innerText = report.recr_name;
                        tr.querySelector('.recr_phone').innerText = report.recr_phone;
                        tr.querySelector('.send_date').innerText = alamGetLastSendDate(report.send_date);
                        tr.querySelector('.send_status').innerText = report.sms_status;
                        tr.querySelector('.use_btn').hidden = false;
                        tr.querySelector('.chk').hidden = false;

                        if (suc_chk && report.sms_status == '성공' ||
                            fail_chk && report.sms_status == '실패' ||
                            report.sms_status == '전송대기' ||
                            report.sms_status == '기타') {
                            tr.hidden = false;
                        }
                        //sms_status 에 따라 색 변환
                        if (report.sms_status == '성공') {
                            tr.querySelector('.send_status').classList.add('text-success');
                        } else if (report.sms_status == '실패') {
                            tr.querySelector('.send_status').classList.add('text-danger');
                        } else if (report.sms_status == '전송대기') {
                            tr.querySelector('.send_status').classList.add('text-warning');
                        } else if (report.sms_status == '기타') {
                            tr.querySelector('.send_status').classList.add('text-info');
                        } else {
                            tr.querySelector('.send_status').classList.add('text-secondary');
                        }
                        alarm_tby_last_report.appendChild(tr);
                    }
                } else {
                    toast('다시 시도해주세요.');
                }
            });

        }

        // 최근 발송내역 구분 형태
        function alarmGetLastType(str) {
            if (str == 'sms')
                return '단문';
            else if (str == 'lms')
                return '장문';
            else if (str == 'mms')
                return '이미지';
            else if (str == 'push')
                return '푸시';
            else if (str == 'kakao')
                return '알림톡';
            return str;
        }

    // 전송 내역 상세 > 재전송
    function alarmLastStatusResend(vthis) {
        //모든 체크박스 해제 후 선택된 체크박스만 체크
        const alarm_tby_last_report = document.querySelector('#alarm_tby_last_report');
        alarm_tby_last_report.querySelectorAll('.tr_last_report .chk').forEach(function(el) {
            el.checked = false;
        });
        const tr = vthis.closest('tr');
        tr.querySelector('.chk').checked = true;
        alarmLastStatusResendChk();
    }

    // 전송 내역 상세 > 체크 박스 > 재전송 확인
    function alarmLastStatusResendChk() {
        sAlert('', '선택 회원을 재전송 하시겠습니까?', 2, function() {
            alarmLastStatusResendAll();
        });
    }

        // 전송 내역 상세 > 체크 박스 > 전체 재전송
        function alarmLastStatusResendAll() {
        //이부분은 문자 전용. 추후 수정해야함
        //또는 POST 컨트롤러 쪽 수정또는 문자와 같은 형식형태로 받았을때 처리
        //[추가 코드]

        // 선택 체크박스 tr 가져오기
        const alarm_tby_last_report = document.querySelector('#alarm_tby_last_report');
        const tr_list = alarm_tby_last_report.querySelectorAll('.tr_last_report');
        const chk_cnt = alarm_tby_last_report.querySelectorAll('.tr_last_report .chk:checked').length;
        const sp_loding = document.querySelector('#alarm_btn_last_status_resend .sp_loding');
        //로딩
        sp_loding.hidden = false;

        if (chk_cnt == 0) {
            sAlert('', '선택된 회원이 없습니다.');
            return false;
        }

        let select_member = [];
        let member_length = 0;
        let mform_content = "";
        let mfrom_title = "";
        let img_data = "";
        let sms_type = "";
        tr_list.forEach(function(el) {
            const chk = el.querySelector('.chk:checked');
            if (chk == null) return false;

            if (mform_content == '') mform_content = el.querySelector('.content').innerText;
            if (mfrom_title == '') mfrom_title = el.querySelector('.title').value;
            if (img_data == '') img_data = el.querySelector('.img_data').value;
            if (sms_type == '') sms_type = el.querySelector('.type').getAttribute('data');

            const recr_name = el.querySelector('.recr_name').innerText;
            const recr_phone = el.querySelector('.recr_phone').innerText;
            const member = {
                member_name: recr_name,
                phone: recr_phone,
            };
            // member_id: member_id,
            // grade: grade,
            // push_key: push_key,
            select_member.push(member);
            // member_length++;
        });
        const type = document.querySelector('[data-select-alarm-send-list]').value;
        const page = "/manage/send/" + type;
        const parameter = {
            mform_title: mfrom_title,
            mform_content: mform_content,
            select_member: select_member,
            img_data: img_data,
            sms_type: sms_type,
            send_length: select_member.length
        };
        queryFetch(page, parameter, function(result) {
            //로딩
            sp_loding.hidden = true;

            if ((result.resultCode || '') == 'success') {
                sAlert('', '전송되었습니다.');
            } else {
                sAlert('', result.resultMsg || '');
            }
        });
    }

        //예약목록 가져오는 동안 다른 버튼 못누르게
        var rev_disabled = false;

        // 예약 목록 안 3가지 종류 탭 클릭시
        function alarmReservTab(vthis) {
        if (rev_disabled) {
            toast('예약목록을 가져오는 중입니다. 잠시만 기다려주세요.');
            return false;
        }

        //예약목록 비우기 초기화
        document.querySelectorAll('#alarm_tby_reserv .tr_reserv').forEach(function(el) {
            el.remove();
        });
        //예약목록 가져오기
        alarmReservSelect();
    }

        // 예약 목록 가져오기
        function alarmReservSelect(page_num) {
        rev_disabled = true;
        //툴팁 혹시나 있으면 삭제
        document.querySelector('#alarm_div_reserv_none').hidden = true;
        document.querySelectorAll('.tooltip').forEach(function(el) {
            el.remove()
        });
        // 타입 가져오기
        const mform_type = document.querySelector('[data-select-alarm-reserv-list]').value;
        const page = "/manage/send/" + mform_type + "/reserv";
        const start_date = document.querySelector('#alarm_inp_reserv_start_date').value;
        const end_date = document.querySelector('#alarm_inp_reserv_end_date').value;
        const alarm_tby_reserv = document.querySelector('#alarm_tby_reserv');

        const parameter = {
            start_date: start_date,
            end_date: end_date,
            page:page_num
        };

        //로딩 기능
        const alarm_btn_reserv_search = document.querySelector('#alarm_btn_reserv_search');
        alarm_btn_reserv_search.querySelector('.sp_loding').hidden = false;

        //tr_reserv 이 없으면 loding_place 보여주기
        if (alarm_tby_reserv.querySelectorAll('.tr_reserv').length == 0)
            alarm_tby_reserv.querySelector('.copy_tr_reserv').hidden = false;

        queryFetch(page, parameter, function(result) {
            //로딩 기능
            alarm_btn_reserv_search.querySelector('.sp_loding').hidden = true;

            //초기화
            const copy_tr = alarm_tby_reserv.querySelector('.copy_tr_reserv').cloneNode(true);
            alarm_tby_reserv.innerHTML = '';
            alarm_tby_reserv.appendChild(copy_tr);
            copy_tr.hidden = true;

            if ((result.resultCode || '') == 'success') {
                tablePaging(result.reserv_info, '4');
                for (let i = 0; i < result.reserv_info.data.length; i++) {
                    let reservs = result.reserv_info.data[i];
                    reservs.type = 'sms';
                    if(mform_type == 'kakao'){
                        reservs = alarmKakaoResChagData(reservs);
                    }

                    const tr = copy_tr.cloneNode(true);
                    tr.classList.remove('copy_tr_reserv');
                    tr.classList.add('tr_reserv');
                    tr.hidden = false;
                    tr.querySelector('.btn_div').hidden = false;
                    if(mform_type != 'kakao')
                        tr.querySelector('[data-bs-target="#alarm_div_modal_reserv_edit"]').hidden = false;
                    tr.querySelectorAll('.loding_place').forEach(function(el) {
                        el.remove();
                    });
                    tr.querySelector('.type').innerText = reservs.sms_type;
                    //내용이 만약 20자리 이상이면 10자리수만 보여주고 뒤는 ... 으로 표시
                    if (reservs.content.length > 20) {
                        tr.querySelector('.content').innerText = reservs.content.substr(0, 20) +
                        '...';
                        tr.querySelector('.content').setAttribute('data-bs-toggle', 'tooltip');
                        tr.querySelector('.content').setAttribute('data-bs-placement', 'right');
                    } else
                        tr.querySelector('.content').innerText = reservs.content;

                    tr.querySelector('.content').setAttribute('data-bs-title', reservs.content);

                    // img_data 에 http글자가 있으면  img.preview 를 보여주고 src 에 img_data를 넣어준다.
                    if (reservs.img_data.indexOf('http') > -1) {
                        tr.querySelector('.img_data .preview').hidden = false;
                        tr.querySelector('.img_data .preview').src = reservs.img_data.replace('^1^0',
                            '');
                    } else {
                        tr.querySelector('.img_data .preview').hidden = true;
                    }
                    tr.querySelector('.receiver').innerText = alamGetLastReceiver(reservs);
                    tr.querySelector('.rev_date').innerText = alamGetLastSendDate(reservs.rev_date);
                    tr.querySelector('.alarm_seq').value = reservs.alarm_seq;
                    tr.querySelector('.title').value = reservs.title;
                    // tr.querySelector('.use_btn').hidden = false;
                    alarm_tby_reserv.appendChild(tr);
                }
            }

            if (alarm_tby_reserv.querySelectorAll('.tr_reserv').length == 0) {
                //예약 목록이 없습니다. div 보여주기
                document.querySelector('#alarm_div_reserv_none').hidden = false;
            } else {
                alarm_tby_reserv.querySelector('.copy_tr_reserv').hidden = true;
                document.querySelector('#alarm_div_reserv_none').hidden = true;
            }
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(
                tooltipTriggerEl))
            rev_disabled = false;
        });
    }


    // 최근 발송내역 수신자 형태
    function alamGetLastReceiver(data) {
        const type = data.type;
        //배열에 넣고 비교
        const sms_type = ['sms', 'lms', 'mms', '단문', '장문', '이미지', 'kakao'];
        if (sms_type.indexOf(type) > -1) {
            const rir = data.receiver;
            const rir_arry = rir.split('|');
            let name = rir_arry[0].split('^');
            name = name[0];
            if (rir_arry.length > 1 && (rir_arry[rir_arry.length - 1] || '' != ''))
                name += '<span class="text-danger"> 외 ' + (rir_arry.length - 1) + '명</span>';
            return name;
        } else if (type == 'push') {
            return '';
        } else if (type == 'kakao') {
            return '';
        }
    }

    // 최근 발송내역 발송일
    function alamGetLastSendDate(send_date) {
        // 20231211114607 형태일때 2023-12-11 11:46:07 형태로 변경
        if (send_date.length == 14 || send_date.length == 16) {
            send_date = send_date.substr(0, 4) + '.' + send_date.substr(4, 2) + '.' + send_date.substr(6, 2) + ' ' +
                send_date.substr(8, 2) + ':' + send_date.substr(10, 2);
        }else if(send_date.length > 20){
            send_date = send_date.substr(0, 16);
        }
        return send_date;
    }

        // 예약 취소
    function alarmReservCancel(vthis) {
        const tr = vthis.closest('tr');
        const alarm_seq = tr.querySelector('.alarm_seq').value;
        const sms_type = tr.querySelector('.type').innerText == '단문' ? 'sms' : 'mms';
        const type = document.querySelector('[data-select-alarm-reserv-list]').value;

        const page = `/manage/send/${type}/reserv/cancel`;
        const parameter = {
            alarm_seq: alarm_seq,
            sms_type: sms_type
        };
            const msg = '<div class="text-sb-24px">정말로 예약을 취소하시겠습니까?</div>';
        sAlert('', msg, 3, function() {
            //로딩 기능
            vthis.querySelector('.sp_loding').hidden = false;
            queryFetch(page, parameter, function(result) {
                vthis.querySelector('.sp_loding').hidden = true;
                if ((result.resultCode || '') == 'success') {
                    const msg = '<div class="text-sb-24px">예약이 취소되었습니다.</div>';
                    sAlert('', msg, 4);
                    alarmReservSelect();
                } else {
                    if ((result.resultMsg || '') == '') result.resultMsg = "다시 시도해주세요.";
                    sAlert('', result.resultMsg || '');
                }
            });
        });
    }

    // 예약 수정 모달 열기
    function alarmReservEdit(vthis) {
        const tr = vthis.closest('tr');
        const alarm_seq = tr.querySelector('.alarm_seq').value;
        const type = tr.querySelector('.type').innerText == '단문' ? 'sms' : 'mms';
        const title = tr.querySelector('.title').value;
        const content = tr.querySelector('.content').getAttribute('data-bs-title') || '';
        const img_data = tr.querySelector('.img_data .preview').getAttribute('src');
        const rev_date = tr.querySelector('.rev_date').innerText;

        //모달창에 값 넣기
        const alarm_div_modal_reserv_edit = document.querySelector('#alarm_div_modal_reserv_edit');
        document.querySelector('#alarm_inp_rev_imgfile_edit').value = '';
        alarm_div_modal_reserv_edit.querySelector('.alarm_seq').value = alarm_seq;
        alarm_div_modal_reserv_edit.querySelector('.type').value = type;
        alarm_div_modal_reserv_edit.querySelector('.title').value = title;
        alarm_div_modal_reserv_edit.querySelector('.content').value = content;
        if ((img_data || '').indexOf('http') > -1)
            alarm_div_modal_reserv_edit.querySelector('.img_data').src = img_data;
        else
            alarm_div_modal_reserv_edit.querySelector('.img_data').src = '';
        alarm_div_modal_reserv_edit.querySelector('.rev_date').value = rev_date;
    }

    // 예약 수정
    function alarmRevEditSave() {
        //모달의 정보 가져오기.
        const alarm_div_modal_reserv_edit = document.querySelector('#alarm_div_modal_reserv_edit');
        const alarm_seq = alarm_div_modal_reserv_edit.querySelector('.alarm_seq').value;
        const type = alarm_div_modal_reserv_edit.querySelector('.type').value;
        const title = alarm_div_modal_reserv_edit.querySelector('.title').value;
        const content = alarm_div_modal_reserv_edit.querySelector('.content').value;
        let rev_date = alarm_div_modal_reserv_edit.querySelector('.rev_date').value;
        //-와 : 그리고 공백을 제외.
        rev_date = rev_date.replace(/-/g, '').replace(/:/g, '').replace(/ /g, '');

        let img_data = alarm_div_modal_reserv_edit.querySelector('.img_data').src;
        if (img_data.length > 0)
            img_data = img_data.substr(img_data.indexOf(',') + 1);

        //단문 예약에는 이미지를 첨부할 수 없습니다.
        if (type == 'sms' && img_data.length > 0) {
            sAlert('', '단문 예약에는 이미지를 첨부할 수 없습니다.');
            return false;
        }
        //로딩 show
        alarm_div_modal_reserv_edit.querySelector('.sp_loding').hidden = false;
        const page = "/manage/send/sms/reserv/update";
        const parameter = {
            alarm_seq: alarm_seq,
            type: type,
            title: title,
            content: content,
            img_data: img_data,
            rev_date: rev_date
        };
        queryFetch(page, parameter, function(result) {
            //로딩 hide
            alarm_div_modal_reserv_edit.querySelector('.sp_loding').hidden = true;
            if ((result.resultCode || '') == 'success') {
                sAlert('', '수정되었습니다.');
                alarmReservSelect();
                // alarm_div_modal_reserv_edit.querySelector('.modal_close').click();
            } else {
                if ((result.resultMsg || '') == '') result.resultMsg = "다시 시도해주세요.";
                sAlert('', result.resultMsg || '');
            }
        });
    }

    // 문자보내기 > 검색시 모달(detail) 똑같이 맞추기.
    function alarmPostDetailInfo(type){
        switch(type){
            case 'date':
                // data-inp-sms-send-start-date
                const start_date = document.querySelector('[data-inp-sms-send-start-date]').value;
                const end_date = document.querySelector('[data-inp-sms-send-end-date]').value;
                //data-inp-sms-send-start-date2
                document.querySelector('[data-inp-sms-send-start-date2]').value = start_date;
                document.querySelector('[data-inp-sms-send-end-date2]').value = end_date;
            break;
            case 'select1':
                //[data-select-sms-send-search1] to #alarm_sel_sch_type
                const sch_type = document.querySelector('[data-select-sms-send-search1]').value;
                document.querySelector('#alarm_sel_sch_type').value = sch_type;
            break;
            case 'input1':
                //[data-input-sms-send-search1] to #alarm_inp_sch_text
                const sch_text = document.querySelector('[data-input-sms-send-search1]').value;
                document.querySelector('#alarm_inp_sch_text').value = sch_text;
            break;
        }
    }

    // 모달을 닫을때 select_member 을 가지고 선택 학생 체크.
    function alarmModalCloseChkSelectMember(){
        //  [data-select-student-list-bundle]  안에 data-select-student-list-row="clone" 모두 삭제
        document.querySelectorAll('[data-select-student-list-bundle] [data-select-student-list-row="clone"].sp_sel_member').forEach(function(el){
            el.remove();
        });
        // [data-secion-alarm-tab-sub="1"] 안에 .chk모두 체크 박스 해제
        document.querySelectorAll('[data-secion-alarm-tab-sub="1"] .chk').forEach(function(el){
            el.checked = false;
        });
        const keys = Object.keys(select_member);
        keys.forEach(element => {
            const student_seq = select_member[element].student_seq;
            document.querySelectorAll('[data-secion-alarm-tab-sub="1"] .tr_student.student_seq_'+student_seq).forEach(function(el){
                el.querySelector('.chk').checked = true;
            });
        });
        alarmSelectUserAddList(true);

    }

    //각 페이징 처리.
    function tablePaging(rData, target){
        const from = rData.from;
        const last_page = rData.last_page;
        const per_page = rData.per_page;
        const total = rData.total;
        const to = rData.to;
        const current_page = rData.current_page;
        const data = rData.data;
        //페이징 처리
        const notice_ul_page = document.querySelector(`[data-ul-alarm-page='${target}']`);
        //prev button, next_button
        const page_prev = notice_ul_page.querySelector(`[data-btn-alarm-page-prev='${target}']`);
        const page_next = notice_ul_page.querySelector(`[data-btn-alarm-page-next='${target}']`);
        //페이징 처리를 위해 기존 페이지 삭제
        notice_ul_page.querySelectorAll(".page_num").forEach(element => {
            element.remove();
        });
        //#page_first 클론
        const page_first = document.querySelector(`[data-span-alarm-page-first='${target}']`);
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
            copy_page_first.removeAttribute("data-span-alarm-page-first");
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
        // if(page_next.getAttribute("onclick") == null){
        // 같은 게시판이면 null일때 이벤트 처리 하면 되지만, 게시판이 바뀌면 어차피 수정처리.
            // page_next.setAttribute("onclick", "noticeBoardSelect("+(last_page)+")");
        // }
        // .page_loding_place 숨김처리
        // document.querySelectorAll(".loding_place").forEach(element => {
        //     element.remove();
        // });
        // #notice_ul_page 숨김처리 해제
        if(data.length != 0)
            notice_ul_page.hidden = false;
    }

    function alarmPageFunc(target, type){
            if(type == 'next'){
                const page_next = document.querySelector(`[data-btn-alarm-page-next="${target}"]`);
                if(page_next.getAttribute("data-is-next") == '0') return;
                // data-ul-alarm-page 의 마지막 page_num 의 innerText를 가져온다
                const last_page = document.querySelector(`[data-ul-alarm-page="${target}"] .page_num:last-of-type`).innerText;
                const page = parseInt(last_page) + 1;
                // 문자보내기 페이징
                if(target == "1")
                    alarmSelectUser(false, page);
                // 저장문구목록
                else if(target == "2")
                    alarmSaveStrTab(document.querySelector('[data-select-alarm-save-list]'), page);
                // 최근발송목록
                else if(target == "3")
                    alarmLastSendSelect(document.querySelector('[data-select-alarm-send-list]').value, page);
            }
            else if(type == 'prev'){
                // [data-span-alarm-page-first]  next tag 의 innerText를 가져온다
                const page_first = document.querySelector(`[data-span-alarm-page-first="${target}"]`);
                const page = page_first.innerText;
                if(page == 1) return;
                const page_num = page*1 -1;
                // 문자보내기 페이징
                if(target == "1")
                    alarmSelectUser(true, page);
                // 저장문구목록
                else if(target == "2")
                    alarmSaveStrTab(document.querySelector('[data-select-alarm-save-list]'), page);
                // 최근발송목록
                else if(target == "3")
                    alarmLastSendSelect(document.querySelector('[data-select-alarm-send-list]').value, page);
            }
            else{
                // 문자보내기 페이징
                if(target == "1")
                    alarmSelectUser(true, type);
                // 저장문구목록
                else if(target == "2")
                    alarmSaveStrTab(document.querySelector('[data-select-alarm-save-list]'), type);
                // 최근발송목록
                else if(target == "3")
                    alarmLastSendSelect(document.querySelector('[data-select-alarm-send-list]').value, type);
            }

    }

    // 알림톡 변수 명을 문자 변수명으로 치환.
    function alarmKakaoResChagData(data){
        const chg = {};
        chg.send_type = '알림톡';
        chg.sms_type = '알림톡';
        chg.type = 'kakao';
        chg.content = data.SEND_MSG;
        switch(data.TMPL_CD){
            case 'sdangmsg001': chg.title = '회원가입'; break;
        }
        chg.img_data = '';
        chg.send_date = data.sendDate;
        chg.rev_date = data.sendDate;
        chg.receiver = data.ETC2;
        chg.succ_count = (data.RSLT_CODE == '0000' ? 1:0);
        chg.receiver_cnt = 1;
        chg.alarm_seq = data.IDX;

        return chg;
    }

    // 저장된 문자 수정하기 > 이미지 삭제.
    function alarmSelImgDelete(){
        const msg = "<div class='text-sb-24px'>이미지를 삭제하시겠습니까?</div>";
        sAlert('', msg, 3, function(){
            document.querySelector('#alarm_img_file_edit').src='';
        });
    }

    </script>
@endsection
