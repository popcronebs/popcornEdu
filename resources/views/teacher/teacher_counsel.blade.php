@extends('layout.layout')

@section('add_css_js')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/fullcalendar/1.6.1/fullcalendar.css">
    {{-- <link rel="stylesheet" href="/assets/css/daterangepicker.css"> --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    {{-- <script src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.0.2/datatables.min.js"></script> --}}
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.11/index.global.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/@fullcalendar/web-component@6.1.11/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/1.6.4/fullcalendar.min.js"></script>
@endsection

{{-- 추가 코드
    2.  TODO: 문자/알림톡 보내기 > 관련 기능.
    99. TODO: 정기상담 계속 생성.
  --}}

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    {{-- <script src="{{ asset('js/custom_calendar.js?13') }}"></script> --}}
    <div class="row pt-2">

        <div class="sub-title d-flex justify-content-between">
            <h2 class="text-sb-42px">
                <img src="{{ asset('images/counsel_title_icon.svg') }}" width="75">
                학습상담관리
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
                        <input type="text" data-inp-teach-counsel-search-str="1" onkeyup="if(event.keyCode == 13) teachCounselSelectCalendar(true);"
                        class="ms-search border-gray rounded-pill text-m-20px w-100"
                            placeholder="단어를 검색해보세요.">
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
                                        <span data-group-name>#그룹이름</span> <span
                                            class="d-block border-gray mx-2 my-1"></span>
                                        <span data-counsel-type>#상담종류</span>
                                    </p>
                                </div>
                                <span data-counsel-start-end-time
                                    class="ht-make-title studyColor-bg-studyComplete on text-sb-20px py-1 px-3 ms-1">#15:00-15:20</span>
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
                                <button type="button" onclick="teachCounselGoToStudentCounsel(this);" data-btn-counsel-go
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
            <div class="d-flex justify-content-between modal-shadow-style px-5 py-4 rounded-3 mb-3">
                <div class="">
                    <div class="d-flex">
                        <div class="d-inline-block select-wrap select-icon me-12 h-62">
                            <select class="search_type rounded-pill border-gray lg-select text-sb-20px h-100">
                                <option value="">검색기준</option>
                                <option value="student_name">학생이름</option>
                                <option value="student_phone">휴대폰 번호</option>
                                <option value="grade">학년</option>
                            </select>
                        </div>
                        <label class="label-search-wrap">
                            <input type="text" onkeyup="if(event.keyCode == 13) teachCounselStListStudentSelect();"
                            class="search_str ms-search border-gray rounded-pill text-m-20px w-100"
                                placeholder="학년을 검색해보세요.">
                        </label>
                    </div>
                </div>
                <div>
                    <button type="button" onclick="teachConselStudentSelect(true)"
                        class="btn-line-ms-secondary text-sb-24px rounded-pill scale-bg-white scale-text-black">상담일정
                        등록하기</button>
                </div>
            </div>
            <div style="height:400px;" class="mb-120 overflow-auto tableFixedHead px-0">
                <table id="myTable-2" class="display table-style w-100">
                    <thead class="">
                      <tr class="text-sb-20px modal-shadow-style rounded">
                        <th style="width:80px">-</th>
                        <th>학교/학년</th>
                        <th>회원명/아이디</th>
                        <th>휴대전화</th>
                        <th>최근 결제일자</th>
                        <th>최근상담일자</th>
                        <th>상담예정일</th>
                      </tr>
                    </thead>
                    <tbody id="teach_counsel_tby_student_list2">
                        <tr class="h-104 text-m-20px copy_tr_student_list" hidden>
                            <td><label class="checkbox mt-1">
                                    <input type="checkbox" class="chk">
                                    <span class="">
                                    </span>
                                </label></td>
                            <td data="#학년/반">
                                <span class="school_name black-color"></span> /
                                <span class="grade_name black-color"></span>
                            </td>
                            <td  data="#이름/아이디" class="black-color">
                                <span class="student_name black-color"></span>
                                (<span class="student_id black-color"></span>)

                            </td>
                            <td>
                                <span class="student_phone"></span>
                                <span class="goods_name" hidden></span>
                                <span class="complete_str" hidden>(만료임박)</span>
                            </td>
                            <td class="payment_date"> </td>
                            <td class="recnt_counsel_date"></td>
                            <td data="#다음상담예정일">
                                <p class="next_counsel_date"></p>
                                <b class="secondary-text-mian is_change_target" hidden>(학부모 변경)</b>
                            </td>
                            <input type="hidden" class="student_seq">
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between">
                <ul class="d-inline-flex gap-2 mb-3">
                    <li>
                        <button type="button" data-teach-counsel-btn-tab="complete" onclick="teachCounselListTab(this)"
                            class="btn-ms-primary text-sb-24px rounded-pill scale-text-white px-32 active">상담완료 내역</button>
                    </li>
                    <li>
                        <button type="button" data-teach-counsel-btn-tab="schedule" onclick="teachCounselListTab(this)"
                            class="btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 scale-text-gray_05 scale-text-black-hover px-32">상담예정
                            내역</button>
                    </li>
                </ul>
                <div class="h-center">
                    <label class="d-inline-block select-wrap select-icon">
                        <select id="select2"
                            onchange="teachCounselSelectDateType(this,'[data-inp-teach-counsel-search-start-date]', '[data-inp-teach-counsel-search-end-date]')"
                            class="date-change rounded-pill border-gray sm-select text-sb-20px me-2 h-52">
                            <option value="">기간설정</option>
                            <option value="-1">오늘로보기</option>
                            <option value="0">1주일전</option>
                            <option value="1">1개월전</option>
                            <option value="2">3개월전</option>
                        </select>
                    </label>
                    <div class="border-gray rounded-pill h-52 px-32 h-center">
                        <input data-inp-teach-counsel-search-start-date type="date"
                            onchange="chks={};teachCounselAllListSelect();" class="border-0 text-m-20px gray-color"
                            value="{{ date('Y-m-d') }}">
                        ~
                        <input data-inp-teach-counsel-search-end-date type="date"
                            onchange="chks={};teachCounselAllListSelect();" class="border-0 text-m-20px gray-color"
                            value="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>
            <table class="display table-style mt-3">
                <thead class="">
                    <tr class="text-sb-20px div-shadow-style rounded">
                        <th style="width:80px">-</th>
                        <th>분류</th>
                        <th>상담번호</th>
                        <th>학교/학년</th>
                        <th>회원명/아이디</th>
                        <th>이용권/기간</th>
                        <th>휴대전화</th>
                        <th>상담예정일</th>
                        <th>최근상담일자</th>
                        <th>다음 상담예정일</th>
                        <th>상담일지</th>
                    </tr>
                </thead>
                <tbody data-tby-bundle="counsel_list">
                    <tr data-tr-row="copy" class="text-m-20px h-104" hidden>
                        <td>
                            <label class="checkbox mt-1 py-4">
                                <input data-chk type="checkbox" class="" onchange="teachCounselChkInput(this)"
                                    onclick="event.stopPropagation();">
                                <span class="" onclick="event.stopPropagation();">
                                </span>
                            </label>
                        </td>
                        <td data-counsel-type data-value="분류"></td>
                        <td data-counsel-seq data-value="상담번호"></td>
                        <td data-value="학교/학년" class="black-color">
                            <div data-school-name></div>
                            <span data-grade-name></span>
                        </td>
                        <td data-value="회원명/아이디">
                            <span data-student-name class="black-color"></span>
                            <span data-student-id class="black-color"></span>
                        </td>
                        <td data-value="이용권/기간">
                            <div>
                                <span data-goods-name></span>
                                <span data-come-complete calss="text-danger" hidden>(만료임박)</span>
                            </div>
                            <div>
                                <span data-goods-start-end-date></span>
                                <span data-goods-remain-date></span>
                            </div>
                        </td>
                        <td data-parent-phone data-value="휴대전화"></td>
                        <td data-counsel-start-date data-value="#상담예정일"></td>
                        <td data-counsel-last-date data-value="최근상담일자"></td>
                        <td data-value="다음 상담예정일">
                            <div>
                                <span data-counsel-next-date></span>
                                <span data-counsel-next-date-after class="text-danger"></span>
                            </div>
                            <span data-counsel-next-chage class="secondary-text-mian"></span>
                        </td>
                        <td data-value="상담일지">
                            <div>
                                <button data-teach-counsel-btn-detail onclick="teachCounselGoToStudentCounsel2(this);"
                                class="btn btn-primary-y rounded-3 px-2">상담일지</button>
                            </div>
                            <button class="btn" data-btn-change-date onclick="teachCounselChangeCounselDate(this);">상담일변경 ></button>
                        </td>
                        <input type="hidden" data-student-seq>
                        <input type="hidden" data-consel-seq>
                        <input type="hidden" data-point-now>
                    </tr>
                </tbody>
            </table>
            <div class="d-flex justify-content-between align-items-center mt-52">
                <div class="">
                    <button type="button" onclick="teachCounselExcelDownload()"
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 border-block-hover px-3 me-2 align-bottom">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" class="me-1">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M10.8649 16.6265C10.2489 16.6265 9.74959 16.1271 9.74959 15.5111V10.8574L6.87216 10.8574C5.84408 10.8574 5.33501 9.60924 6.06985 8.89023L11.1707 3.89928C11.6166 3.46299 12.3294 3.46299 12.7753 3.89928L17.8762 8.89024C18.611 9.60924 18.1019 10.8574 17.0739 10.8574L14.166 10.8574V15.5111C14.166 16.1271 13.6667 16.6265 13.0507 16.6265H10.8649Z"
                                fill="#DCDCDC" />
                            <rect x="5.57031" y="17.8208" width="12.8027" height="1.75074" rx="0.875369"
                                fill="#DCDCDC" />
                        </svg>
                        엑셀로 내보내기
                    </button>
                    <button type="button" onclick="teachCounselModalPointShow();"
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 border-block-hover px-3 me-2">
                        포인트 지급/차감
                    </button>
                    <button type="button"
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 border-block-hover px-3" onclick="teachCounselMovePlaner()">
                        학습플래너 수정
                    </button>
                </div>
                <div class="my-custom-pagination">
                    <ul class="pagination col-auto" data-ul-teach-counsel-page="1" hidden>
                        <button href="javascript:void(0)" class="btn p-0 prev" data-btn-teach-counsel-page-prev="1"
                            onclick="teachCounselPageFunc('1', 'prev')">
                            <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                        </button>
                        <li class="page-item" hidden>
                            <a class="page-link" onclick="">0</a>
                        </li>
                        <span class="page" data-span-teach-counsel-page-first="1" hidden
                            onclick="teachCounselPageFunc('1', this.innerText);" disabled>0</span>
                        <button href="javascript:void(0)" class="btn p-0 next" data-btn-teach-counsel-page-next="1"
                            onclick="teachCounselPageFunc('1', 'next')" data-is-next="0">
                            <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                        </button>
                    </ul>
                </div>
                <div>
                    <button type="button" onclick="teachCounselSmsModalOpen();"
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 border-block-hover px-3 me-2">SMS
                        문자 발송하기</button>
                    <button type="button"
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 border-block-hover px-3">상담일정
                        알림 발송</button>
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
                                <label
                                    class="smart-hb-input border-gray rounded text-m-20px gray-color text-center h-center px-3">
                                    <input type="date" class="next_counsel_date1 border-0 text-m-20px gray-color"
                                        value="{{ date('Y-m-d') }}">~
                                    <input type="date" class="next_counsel_date2 border-0 text-m-20px gray-color"
                                        value="{{ date('Y-m-d') }}">
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
        {{-- 모달 / 포인트 지급 --}}
        <div class="modal fade" id="teach_counsel_modal_point" tabindex="-1" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog rounded" style="max-width: 592px;">
              <div class="modal-content border-none rounded p-3 modal-shadow-style">
                <div class="modal-header border-bottom-0">
                  <h1 class="modal-title fs-5 text-b-24px" id="">
                    <img src="{{ asset('images/point_ranking_icon2.svg') }}" width="32">
                    <span class="">포인트 지급 및 차감</span>
                  </h1>
                  <button type="button" style="width:32px;height:32px"
                  class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {{-- 학생 1명일때. 포인트 보이게 --}}
                <div data-hidden="one_student" hidden>
                    <p class="text-b-20px mb-3 mt-52">현재 학생의 포인트 잔액</p>
                    <div class="row w-100">
                      <div class="col-12 p-0 mb-3">
                        <label class="label-input-wrap w-100">
                          <input type="text" data-point-now
                          class="smart-ht-search border-gray rounded border-none text-m-20px w-100" disabled=""
                            placeholder="">
                        </label>
                      </div>
                    </div>
                </div>
                {{-- 여러명 학생 선택시 보이게. --}}
                <div data-hidden="no_one_student" hidden>
                    <ul class="div-shadow-style rounded-3">
                      <li class="scale-bg-white p-4 mb-2 rounded-3 dorp-click">
                        <div class="d-flex justify-content-between align-items-center ">
                          <p class="text-b-20px" data-title-student-name></p>
                          <button class="btn p-0 active" type="button" onclick="teachCounselStudentListToggle(this);">
                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" alt=""
                              class="align-middle"></button>
                        </div>
                        <div class="drop-effect" data-drop-effect>
                          <ul data-bundle="point_student_list" class="li-lineblock pt-4">
                            <li data-row="copy" hidden>
                                <input type="hidden" data-student-seq>
                              <span data-student-grade-name data-value="#name"
                              class="text-m-20px gray-color align-middle"></span>
                              <button class="btn p-0 " type="button" onclick="this.closest('li').remove();">
                                <img src="{{ asset('images/svg/close-gray.svg') }}" width="24"
                                  height="24" alt="" class="align-middle"></button>
                            </li>
                          </ul>
                        </div>
                      </li>
                    </ul>
                </div>
                  <p class="text-b-20px mb-3 mt-52">유형을 선택해주세요.</p>
                  <div class="d-inline-block select-wrap select-icon w-100 mb-32">
                    <select data-point-plus-type
                    class="border-gray lg-select text-sb-20px w-100 h-62">
                      <option value="1">포인트 지급</option>
                      <option value="0">포인트 차감</option>
                    </select>
                  </div>
                  <div class="row w-100">
                    <div class="col-3 ps-0 pe-2">
                      <p class="text-b-20px mb-2">지금포인트</p>
                      <label class="label-input-wrap w-100">
                        <input type="number" data-point-add
                        class="smart-ht-search p-2 border-gray rounded text-m-20px w-100 text-start p-3"
                          placeholder="0p" value="" >
                      </label>
                    </div>
                    <div class="col-9 ps-2 pe-2">
                      <p class="text-b-20px mb-2">지급사유</p>
                      <div class="d-inline-block select-wrap select-icon w-100">
                        <select data-point-type="1" onchange="teachCounselPointType(this);"
                        class="border-gray lg-select text-sb-20px w-100 h-62">
                          <option value="0">학습활동 우수 리워드 지급</option>
                          <option value="1">직접입력</option>
                        </select>
                      </div>
                      <input type="text" data-point-type="2" hidden
                      class="smart-ht-search border-gray rounded border text-m-20px w-100 mt-2">
                    </div>
                  </div>
                </div>
                <div class="modal-footer border-top-0">
                  <div class="row w-100">
                    <div class="col-6 ps-0">
                      <button type="button"
                        class="btn-lg-secondary text-sb-24px rounded scale-bg-gray_01 scale-text-gray_05 w-100 justify-content-center"
                        data-bs-dismiss="modal">뒤로가기</button>
                    </div>
                    <div class="col-6 pe-0">
                      <button type="button" onclick="teachCounselPointInsert();"
                        class="btn-lg-primary text-b-24px rounded scale-text-white w-100 text-center justify-content-center">변경
                        사항 저장</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

    <!-- 학습관리 상세페이지 이동 -->
    <form action="/manage/learning" method="post" data-form-learningplan="" target="_self">
        @csrf
        <input type="hidden" name="student_seq" data-form-student-seq>
    </form>

        {{-- 모달 / 상담일정 알림 발송 / 여기 안에 select_member 배열 있으므로, 확인 --}}
        @include('admin.admin_alarm_detail')
    </div>
    <script>
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
                         </div>`
                    };
                },
                events: undefined,
                eventDisplay: 'auto', // 이벤트를 개별로 표시

                eventClassNames: function(arg) {
                    if (arg.event.title.includes('정기상담')) {
                        return 'management-calendar type-1';
                    } else if (arg.event.title.includes('수시상담')) {
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

        // 기간설정 select onchange
        function teachCounselSelectDateType(vthis, start_date_tag, end_date_tag) {
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
            if (inp_start.onchange)
                inp_start.onchange();
            if (inp_end.onchange)
                inp_end.onchange();
        }

        // 모달 / 선택 학생 추가.
        function teachConselStudentSelect(is_main) {
            // 우선 선택학생이 없으면 리턴
            let modal = document.querySelector('#teach_counsel_modal_student_list');
            if(is_main){
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
                    student_seq: tr.querySelector('.student_seq').value
                };
                students.push(student);
            });
            if(is_main){

            }else{
                //모달 닫기
                modal.querySelector('.modal_close').click();
            }
            teachCounselOpenRegularCounselModal(students);
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

        // 모달 / 포인트 지급/차감
        function teachCounselModalPointShow(){
            const openModal = document.querySelector('#teach_counsel_modal_point');
            const chk_rt = teachCounselChkModalPoint();
            if(!chk_rt.code){
                toast(chk_rt.msg);
                return;
            }
            const students = chk_rt.students;

            // 초기화
            const copy = openModal.querySelector('[data-bundle="point_student_list"] [data-row="copy"]');
            const bundle = openModal.querySelector('[data-bundle="point_student_list"]');
            bundle.innerHTML = '';
            bundle.appendChild(copy);

            students.forEach(function(student){
                const li = copy.cloneNode(true);
                li.setAttribute('data-row', 'clone');
                li.querySelector('[data-student-seq]').value = student.student_seq;
                li.querySelector('[data-student-grade-name]').innerText = `${student.student_name}(${student.grade_name})`;
                li.hidden = false;
                bundle.appendChild(li);
            });

            if(students.length == 1){
                openModal.querySelector('[data-hidden="one_student"]').hidden = false;
                openModal.querySelector('[data-hidden="no_one_student"]').hidden = true;
                openModal.querySelector('[data-point-now]').value = students[0].point_now;
            }else{
                openModal.querySelector('[data-hidden="one_student"]').hidden = true;
                openModal.querySelector('[data-hidden="no_one_student"]').hidden = false;
                openModal.querySelector('[data-point-now]').value = '';
                // data-title-student-name 에 외 00명 추가.
                const title_student_name = openModal.querySelector('[data-title-student-name]');
                title_student_name.innerText = `${students[0].student_name}(${students[0].grade_name})` + ' 외 ' + (students.length - 1) + '명';
            }

            const myModal = new bootstrap.Modal(openModal, {
                backdrop: 'static',
                keyboard: false
            });
            myModal.show();
        }

        // 포인트 지급 차감 모달 열기전 체크.
        function teachCounselChkModalPoint(){
            // data-tby-bundle="counsel_list" 안에 data-chk의 체크가 하나도 없으면 리턴.
            const tby_bundle = document.querySelector('[data-tby-bundle="counsel_list"]');
            const chk = tby_bundle.querySelectorAll('[data-chk]:checked');
            const result = {};
            if(chk.length == 0){
                result.code = false;
                result.msg = '선택된 학생이 없습니다.';
                return result;
            }
            // 학생을 가져오기. student_name , student_seq 가져오기.
            const students = [];
            chk.forEach(function(chk){
                const tr = chk.closest('tr');
                const student = {
                    student_name: tr.querySelector('[data-student-name]').innerText,
                    student_seq: tr.querySelector('[data-student-seq]').value,
                    grade_name: tr.querySelector('[data-grade-name]').innerText,
                    point_now: tr.querySelector('[data-point-now]').value
                };
                students.push(student);
            });

            result.code = true;
            result.students = students;

            return result;
        }

        //포인트 지급/차감 학생 리스트 보이기 토글
        function teachCounselStudentListToggle(vthis){
            const drop_effect = vthis.closest('li').querySelector('[data-drop-effect]');
            if(vthis.classList.contains('active')){
                // 숨기기
                vthis.classList.remove('active');
                vthis.classList.add('rotate-180');
                drop_effect.hidden = true;
            }else{
                // 보이기
                vthis.classList.add('active');
                vthis.classList.remove('rotate-180');
                drop_effect.hidden = false;
            }
        }

        // 포인트 지급, 차감
        function teachCounselPointInsert(){
            const modal = document.querySelector('#teach_counsel_modal_point');
            const point_el = modal.querySelector('[data-point-add]');
            const student_seq_els = modal.querySelectorAll('[data-bundle="point_student_list"] [data-row="clone"] [data-student-seq]');
            const point_plus_el = modal.querySelector('[data-point-plus-type]');

            let point = point_el.value;
            const student_seq = [];
            student_seq_els.forEach(function(student_seq_el){
                student_seq.push(student_seq_el.value);
            });
            const remark1_el = modal.querySelector('[data-point-type="1"]');
            const remark2_el = modal.querySelector('[data-point-type="2"]');
            let remark = '';
            if(remark1_el.value == 0) remark = remark1_el.selectedOptions[0].innerText;
            else remark = remark1_el.selectedOptions[0].innerText +' : '+ remark2_el.value;

            if(point.length < 1 || point == '0'){
                toast('포인트를 입력해주세요.');
                return;
            }
            if(point_plus_el.value == 0){
                point = point * -1;
            }

            const page = "/manage/userlist/point/insert";
            const parameter = {
                user_keys:student_seq.join(','),
                point:point,
                remark:remark,
                point_type:'teacher_give'
            };
            queryFetch(page,parameter,function(result){
                let msg = '';
                if(point*1 > 0) msg = '지급';
                else msg = '차감';
                if(result.resultCode == 'success'){
                    // no_insert_today_students
                    // no_insert_month_students
                    let today_name = '';
                    let month_name = '';
                    if(result.no_insert_day_students.length > 0){
                        today_name = result.no_insert_day_students.join(',');
                    }
                    if(result.no_insert_month_students.length > 0){
                        month_name = result.no_insert_month_students.join(',');
                    }
                    let msg2 = '';
                    if(today_name.length > 1 || month_name.length > 1){
                        if(today_name.length > 1) msg2 += ' 당일 포인트 지급 제한 학생 : '+today_name;
                        if(month_name.length > 1) msg2 += '<br> 당월 포인트 지급 제한 학생 : '+month_name;
                    }
                    let msg3 = '';
                    if(msg2.length > 0) msg3 = '아래 학생을 제외한 학생에게';
                    msg = `
                        <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
                            <p class="modal-title text-center text-sb-28px alert-top-m-20" id="">${msg3}포인트가 ${msg} 되었습니다.</p>
                            <p class="modal-title text-center text-sb-24px alert-bottom-m studyColor-text-studyComplete" id="">${msg2}</p>
                        </div>
                    `;
                    sAlert('', `${msg}`, 4);
                    if(result.point_nows.length == 1){
                        modal.querySelector('[data-point-now]').value = result.point_nows[0];
                    }
                    const page_num = document.querySelector('.page_num.active').innerText;
                    teachCounselAllListSelect(page_num);
                }else{
                    toast('포인트 지급에 실패하였습니다.');
                }
            });
        }

        // 포인트 지급사유 선택
        function teachCounselPointType(vthis){
            const type = vthis.value;
            const input = vthis.closest('.row').querySelector('input[data-point-type="2"]');
            if(type == 1){
                input.hidden = false;
            }else{
                input.hidden = true;
                input.value = '';
            }
        }

        //----------------------------------------------

        // 상담 추가 버튼 클릭
        // 데이터를 저장하기전 화면에만 내용을 추가.
        function teachCounselStListModal() {
            //clear function
            teachCounselStListModalClear();
            teachCounselStListModalStudentSelect();
            const myModal = new bootstrap.Modal(document.getElementById('teach_counsel_modal_student_list'), {});
            myModal.show();
        }

        //
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
        function teachCounselStListStudentSelect(){
            const main_div = document.querySelector('#teach_counsel_div_main2');

            //검색조건
            const search_type = main_div.querySelector('.search_type').value;
            const search_str = main_div.querySelector('.search_str').value;

            // 전송
            const page = "/teacher/counsel/student/select";
            const parameter = {
                search_type: search_type,
                search_str: search_str
            };

            teachCounselStListDetail(true, main_div, page, parameter);

        }
        //
        function teachCounselStListDetail(is_main, main_div, page, parameter){
            queryFetch(page, parameter, function(result) {
                // 로딩 해제
                document.querySelector('#teach_counsel_btn_search > span').hidden = true;
                if ((result.resultCode || '') == 'success') {
                    //초기화
                    let copy_tr_student_list = null;
                    let tby_student_list = null;
                    if(is_main){
                        tby_student_list = main_div.querySelector('#teach_counsel_tby_student_list2');
                        copy_tr_student_list = tby_student_list.querySelector('.copy_tr_student_list').cloneNode(true);
                    }
                    else{
                        copy_tr_student_list = main_div.querySelector('.copy_tr_student_list').cloneNode(true);
                        tby_student_list = main_div.querySelector('#teach_counsel_tby_student_list');
                    }
                    tby_student_list.innerHTML = '';
                    tby_student_list.appendChild(copy_tr_student_list);

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
                        tr.querySelector('.recnt_counsel_date').innerText = last_counsel_date.substr(0, 16).replace(/-/g, '.');
                        // next_counsel_date_arr
                        let next_counsel_date = result.next_counsel_date_arr[student.id] || '';
                        tr.querySelector('.next_counsel_date').innerText = next_counsel_date.substr(0, 25).replace(/-/g, '.').replace('00:00:00', '');;
                        const next_split = next_counsel_date.split('|')
                        if(next_split.length > 2 && next_split[1] == 'Y'){
                            //is_change_target
                            tr.querySelector('.is_change_target').hidden = false;
                            tr.querySelector('.is_change_target').innerText = next_split[2] == 'teacher' ? '(선생님 변경)': '(학부모 변경)';
                        }

                        tr.hidden = false;
                        tby_student_list.appendChild(tr);
                    });
                }
            });
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
            student_els.forEach(function(student_el) {
                const student_seq = student_el.querySelector('[data-student-seq]').value;
                student_seqs.push(student_seq);
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
            if(post_data != undefined){
                teachCounselCounselChangeDateUpdate(post_data, sel_date, start_time, end_time);
                return;
            }
            // 상담일정변경으로 진입하지 않았을때.
            const page = "/teacher/counsel/insert";
            const parameter = {
                student_seqs: student_seqs,
                counsel_type: counsel_type,
                sel_date: sel_date,
                start_time: start_time,
                end_time: end_time,
            };

            let msg = '';
            const sel_student_name = student_els[0].querySelector('[data-student-name]').innerText;
            const sel_grade_name = student_els[0].querySelector('[data-grade-name]').innerText;
            const sel_day = modal.querySelector("[data-span-week-time]").innerText;
            //정기 상담일때
            if (student_seqs.length == 1 && counsel_type == 'regular') {
                msg =
                    `
              <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
                <p class="modal-title text-center text-sb-20px scale-text-gray_05">${sel_student_name}(${sel_grade_name}) 정기상담일 등록</p>
                <p class="modal-title text-center text-sb-28px alert-top-m-20">매주 ${sel_day}분 상담이 반복됩니다.</p>
                <p class="modal-title text-center text-sb-28px alert-bottom-m studyColor-text-studyComplete" id="">상담을 등록하시겠습니까 ?</p>
              </div>
            `;
            }
            //수기 상담일때.
            else if (counsel_type == 'no_regular') {
                const sel_count = student_seqs.length == 1 ? '' : '외 ' + (student_seqs.length - 1) + '명';
                const unkwon_chk = modal.querySelector('[data-chk-counsel-time-unknow]').checked;
                const sel_time = unkwon_chk ? '시간미정' : `${start_time} ~ ${end_time}`;
                msg =
                    `
            <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
              <p class="modal-title text-center text-sb-20px gray-color" id="">${sel_student_name}(${sel_grade_name}) ${sel_count} 수기상담일 등록</p>
              <p class="modal-title text-center text-sb-28px alert-top-m-20">${sel_student_name}(${sel_grade_name}) ${sel_count}의 ${sel_time}으로</p>
              <p class="modal-title text-center text-sb-28px alert-bottom-m studyColor-text-studyComplete" id="">상담을 등록하시겠습니까 ?</p>
              <p class="modal-title text-center text-sb-20px gray-color" id="">(여러명의 학생을 등록할 경우 수시상담만 가능합니다.)</p>
            </div>
            `;
            }
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
                            teachCounselAllListSelect(page_num);

                        } else if ((result.resultCode || '') == 'already') {

                        }
                    }, function() {},
                    '네', '아니오');
            });
        }

        function teachCounselClear(is_main) {
            let main_div = document.querySelector('#counsel_modal_add');
            if (is_main) main_div = document.querySelector('#teach_counsel_div_main');
            const bundle = main_div.querySelector('[data-ul-counsel-list-bundle]');
            const row_copy = bundle.querySelector('[data-li-counsel-list-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

        }

        // 상담 바로가기
        function teachCounselGoToStudentCounsel(vthis) {
            const is_counsel = vthis.closest('[data-li-counsel-list-row]').querySelector('[data-is-counsel]').value;
            const li = vthis.closest('[data-li-counsel-list-row]');
            const student_seq = li.querySelector('[data-student-seq]').value;
            const counsel_seq = li.querySelector('[data-counsel-seq]').value;
            const xtken = document.querySelector('#csrf_token').value;

            let action = '';
            let is_before = 'N';
            // 등록하기로 이동
            if (is_counsel == 'Y') {
                action = '/manage/counsel/detail';
            }
            // 이상담의 바로 직전 상담 일지 확인페이지로 이동.
            else if (is_counsel == 'N_BEFORE') {
                is_before = 'Y';
                action = '/manage/counsel/detail';
            } else {
                action = '/manage/counsel/add';
            }
            // post 로 페이지 이동
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
            const input3 = document.createElement('input');
            input3.type = 'hidden';
            input3.name = 'is_before';
            input3.value = is_before;
            form.appendChild(input3);
            const input99 = document.createElement('input');
            input99.type = 'hidden';
            input99.name = '_token';
            input99.value = xtken;
            form.appendChild(input99);
            //is_before
            document.body.appendChild(form);
            // span class text-sb-28px> text <
            sAlert('', '<span class="text-sb-28px">상담 페이지로 이동하시겠습니까?</span>', 3, function() {
                form.submit();
            });
        }

        // 전체목록 > 상담내역 > 상담일지
        function teachCounselGoToStudentCounsel2(vthis) {
            const tr = vthis.closest('tr');
            const start_date = tr.querySelector('[data-counsel-start-date]').innerText.substr(0);
            const tab = document.querySelector('[data-teach-counsel-btn-tab].active');
            const tab_type = tab.getAttribute('data-teach-counsel-btn-tab');

            const student_seq = tr.querySelector('[data-student-seq]').value;
            const counsel_seq = tr.querySelector('[data-counsel-seq]').innerText;
            const xtken = document.querySelector('#csrf_token').value;

            let action = '';
            let is_before = false;
            if (tab_type == 'complete') {
                action = '/manage/counsel/detail';
            } else {
                if (start_date.substr(0,10) == new Date().format('yyyy.MM.dd')) {
                    action = '/manage/counsel/add';
                } else {
                    is_before = true;
                    action = '/manage/counsel/detail';
                }
            }

            // post 로 페이지 이동
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
            const input3 = document.createElement('input');
            input3.type = 'hidden';
            input3.name = '_token';
            input3.value = xtken;
            form.appendChild(input3);
            //is_before
            const input4 = document.createElement('input');
            input4.type = 'hidden';
            input4.name = 'is_before';
            input4.value = is_before ? 'Y' : 'N';
            document.body.appendChild(form);
            // span class text-sb-28px> text <
            sAlert('', '<span class="text-sb-28px">상담 페이지로 이동하시겠습니까?</span>', 3, function() {
                form.submit();
            });

        }
        document.addEventListener('visibilitychange', function(event) {
            if (sessionStorage.getItem('isBackNavigation') === 'true') {
                console.log('뒤로 가기 버튼을 클릭한 후 페이지가 로드되었습니다.');
                // 여기에 뒤로 가기 버튼을 클릭한 후 페이지가 로드되었을 때 실행할 코드를 작성합니다.
                sessionStorage.removeItem('isBackNavigation'); // 상태를 초기화합니다.

                // 캘린더 상담 목록 가져오기.
                teachCounselSelectCalendar(true);
                teachCounselSelect(true);
                const page_num = document.querySelector('.page_num.active').innerText;
                teachCounselAllListSelect(page_num);
            }
        });

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
                teachCounselAllListSelect('1');
            }
        }

        // 전체목록보기 > 상담내역 가져오기.
        function teachCounselAllListSelect(page_num) {
            //
            const tby_el = document.querySelector('#teach_counsel_tby_student_list2');
            const tr_chk = tby_el.querySelectorAll('.tr_student_list .chk:checked');
            // if(tr_chk.length < 1){
            //     return;
            // }
            // const student_seqs = [];
            // tr_chk.forEach(function(tr){
            //     const student_seq = tr.closest('.tr_student_list').querySelector('.student_seq').value;
            //     student_seqs.push(student_seq);
            // });
            const btn_tab = document.querySelector('[data-teach-counsel-btn-tab].active');
            const tab_type = btn_tab.getAttribute('data-teach-counsel-btn-tab');

            const is_counsel = (tab_type == 'complete' ? 'Y' : 'N');
            const counsel_types = ['regular', 'no_regular'];
            const get_type = 'page';

            const search_start_date = document.querySelector('[data-inp-teach-counsel-search-start-date]').value;
            const search_end_date = document.querySelector('[data-inp-teach-counsel-search-end-date]').value;

            const page = "/teacher/counsel/select";
            const parameter = {
                // student_seqs:student_seqs,
                counsel_types: counsel_types,
                search_start_date: search_start_date,
                search_end_date: search_end_date,
                is_counsel: is_counsel,
                get_type: get_type,
                page: page_num,
                page_max: 6
            };
            queryFetch(page, parameter, function(result) {
                //초기화
                // data-tby-bundle="counsel_list"
                // data-tr-row="copy"
                const bundle = document.querySelector('[data-tby-bundle="counsel_list"]');
                const copy_tr = bundle.querySelector('[data-tr-row="copy"]').cloneNode(true);
                bundle.innerHTML = '';
                bundle.appendChild(copy_tr);
                if ((result.resultCode || '') == 'success') {
                    teachCounselTablePaging(result.counsels, '1')
                    const today_str = new Date().format('yyyy-MM-dd');
                    result.counsels.data.forEach(function(counsel) {
                        const tr = copy_tr.cloneNode(true);
                        tr.classList.remove('copy');
                        tr.querySelector('[data-counsel-type]').innerText = teachCounselType(counsel
                            .counsel_type);
                        tr.querySelector('[data-counsel-seq]').innerText = counsel.id;
                        tr.querySelector('[data-school-name]').innerText = counsel.school_name || '미배정';
                        tr.querySelector('[data-grade-name]').innerText = counsel.grade_name;
                        tr.querySelector('[data-student-name]').innerText = counsel.student_name;
                        tr.querySelector('[data-student-id]').innerText = `(${counsel.student_id})`;
                        tr.querySelector('[data-parent-phone]').innerText = counsel.pt_parent_phone;
                        tr.querySelector('[data-counsel-start-date]').innerText = (counsel.start_date
                            .substr(0, 10) + ' ' + counsel.start_time).substr(0, 16).replace(/-/g, '.');
                        tr.querySelector('[data-counsel-start-date]').setAttribute('data-value', counsel.start_date.substr());
                        //data-counsel-last-date
                        let last_counsel_date = result.last_counsel_date_arr[counsel.student_seq] || '';
                        tr.querySelector('[data-counsel-last-date]').innerText = last_counsel_date.substr(0,
                            16).replace(/-/g, '.');
                        let next_counsel_date = result.next_counsel_date_arr[counsel.student_seq] || '';
                        tr.querySelector('[data-counsel-next-date]').innerText = next_counsel_date.substr(0,
                            25).replace(/-/g, '.').replace('00:00:00', '');
                        // next_counsel_date 이 오늘이면 data-counsel-next-date-after 에 '(오늘)' 넣기
                        if(next_counsel_date.substr(0,10) == new Date().format('yyyy-MM-dd')){
                            tr.querySelector('[data-counsel-next-date-after]').innerText = '(오늘)';
                        }
                        // 학부모 변경이라고 적혀있는데 뭔지 모르겟음.
                        if(counsel.is_change == 'Y'){
                            tr.querySelector('[data-counsel-next-chage]').innerText = counsel.pt_change_regular_target == 'teacher' ? '선생님 변경' : '학부모 변경';
                        }
                        tr.querySelector('[data-student-seq]').value = counsel.student_seq;
                        tr.querySelector('[data-point-now]').value = counsel.point_now;
                        tr.hidden = false;
                        // 이용권을 사용 했을 경우에,
                        if ((counsel.goods_start_date || '') != '') {
                            // 2023-12-06 ~ 2024-07-21 형태를 2023.10.01-11.01 형태로 변경
                            tr.querySelector('[data-goods-name]').innerText = counsel.goods_name;
                            let start_end_date =
                                `${counsel.goods_start_date.substr(2,8)}~${counsel.goods_end_date.substr(2,8)}`;
                            start_end_date = start_end_date.replace(/-/g, '.');
                            start_end_date = start_end_date.replace('~', '-');
                            tr.querySelector('[data-goods-start-end-date]').innerText = start_end_date;
                            // 오늘 날짜와 끝날짜 비교해서 남은 일수 계산
                            const goods_end_date = new Date(counsel.goods_end_date);
                            const today = new Date();
                            const remain_date = Math.ceil((goods_end_date - today) / (1000 * 60 * 60 * 24));
                            tr.querySelector('[data-goods-remain-date]').innerText = `(${remain_date}일)`;

                            //data-come-complete 30일 이하 만료임박
                            if (remain_date < 10) {
                                tr.querySelector('[data-come-complete]').hidden = false;
                                tr.querySelector('[data-goods-remain-date]').classList.add('text-danger');
                            } else if (remain_date <= 30) {
                                tr.querySelector('[data-goods-remain-date]').classList.add(
                                    'secondary-text-mian');
                            } else if (remain_date > 30) {
                                tr.querySelector('[data-goods-remain-date]').classList.add('black-color');
                            }
                        }
                        // 상담예정 내역일때 삼담일지 버튼 숨기기.
                        // 오늘이 아닐때
                        if(tab_type != 'complete' && counsel.start_date.substr(0, 10) != today_str){
                            tr.querySelector('[data-teach-counsel-btn-detail]').hidden = true;
                        }
                        // is_counsel != Y
                        if(counsel.is_counsel == 'Y'){
                            tr.querySelector('[data-btn-change-date]').hidden = true;
                        }
                        bundle.appendChild(tr);
                        if(chks[counsel.id]){
                            tr.querySelector('[data-chk]').checked = true;
                        }
                    });
                } else {

                }
            });

        }

        //각 페이징 처리.
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

        // 각 페이지 클릭
        function teachCounselPageFunc(target, type) {
            if (type == 'next') {
                const page_next = document.querySelector(`[data-btn-teach-counsel-page-next="${target}"]`);
                if (page_next.getAttribute("data-is-next") == '0') return;
                // data-ul-teach-counsel-page 의 마지막 page_num 의 innerText를 가져온다
                const last_page = document.querySelector(`[data-ul-teach-counsel-page="${target}"] .page_num:last-of-type`)
                    .innerText;
                const page = parseInt(last_page) + 1;
                if (target == "1")
                    teachCounselAllListSelect(page)
            } else if (type == 'prev') {
                // [data-span-teach-counsel-page-first]  next tag 의 innerText를 가져온다
                const page_first = document.querySelector(`[data-span-teach-counsel-page-first="${target}"]`);
                const page = page_first.innerText;
                if (page == 1) return;
                const page_num = page * 1 - 1;
                if (target == "1")
                    teachCounselAllListSelect(page)
            } else {
                if (target == "1")
                    teachCounselAllListSelect(type)
            }
        }

        // 전체목록보기 > 상담내역완료 / 예정 클릭
        function teachCounselListTab(vthis) {
            const tabs = document.querySelectorAll('[data-teach-counsel-btn-tab]');
            tabs.forEach(function(tab) {
                tab.className =
                    'btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 scale-text-gray_05 scale-text-black-hover px-32';
            });
            vthis.className = 'btn-ms-primary text-sb-24px rounded-pill scale-text-white px-32 active';
            teachCounselAllListSelect();
        }

        // 전체목록 > 상담내역 > 상담일변경
        function teachCounselChangeCounselDate(vthis){
            const tr = vthis.closest('tr');
            const students = [];
            const student = {
                grade_name: tr.querySelector('[data-grade-name]').innerText,
                student_name: tr.querySelector('[data-student-name]').innerText,
                student_seq: tr.querySelector('[data-student-seq]').value
            };
            students.push(student);
            const start_date = tr.querySelector('[data-counsel-start-date]').getAttribute('data-value');
            teachCounselOpenRegularCounselModal(students, start_date);
            const modal_el = document.getElementById('counsel_modal_add');
            modal_el.querySelector('[data-select-modal-title]').value = 'no_regular';
            modal_el.querySelector('[data-select-modal-title]').onchange();
            modal_el.querySelector('[data-select-modal-title]').disabled = true;
            const event_btn = modal_el.querySelector('[data-btn-event-exit]');
            const counsel_seq = tr.querySelector('[data-counsel-seq]').innerText;
            if(event_btn != null){
                event_btn.setAttribute('onclick', `teachCounselInsert("${counsel_seq}");`);
                //상담일정 변경하기.
                event_btn.innerText = '상담일정 변경하기';
            }
        }

        //
        function teachCounselCounselChangeDateUpdate(counsel_seq, start_date, start_time, end_time){
            const page = "/teacher/counsel/change/date/update";
            const parameter = {
                counsel_seq: counsel_seq,
                start_date: start_date,
                start_time: start_time,
                end_time: end_time
            };
            //상담일정을 변경하시겠습니까?
            const msg =
            `
                <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
                <p class="modal-title text-center text-sb-28px alert-top-m-20" id="">상담 일정을 변경하시겠습니까?</p>
                <p class="modal-title text-center text-sb-28px alert-bottom-m studyColor-text-studyComplete" id="">적용후 바로 확인이 가능합니다.</p>
                </div>
            `;
            sAlert('', msg, 3, function() {
                queryFetch(page, parameter, function(result) {
                    if ((result.resultCode || '') == 'success') {
                        const msg =
                        `
                            <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
                            <p class="modal-title text-center text-sb-28px alert-top-m-20" id="">상담 일정이 변경되었습니다.</p>
                            <p class="modal-title text-center text-sb-24px alert-bottom-m studyColor-text-studyComplete" id="">목록에서 확인하시기 바랍니다.</p>
                            </div>
                        `;
                        sAlert('', msg, 4);
                        // teachCounselSelect(true);
                        teachCounselSelectCalendar(true);
                        const page_num = document.querySelector('.page_num.active').innerText;
                        teachCounselAllListSelect(page_num);
                        const modal = document.getElementById('counsel_modal_add');
                        modal.querySelector('.btn-close').click();
                    } else {
                        toast('상담 일정 변경에 실패하였습니다.');
                    }
                });
            });
        }

        let chks = {};
        // 체크박스 체크
        function teachCounselChkInput(vthis){
            const tr = vthis.closest('tr');
            const counsel_seq = tr.querySelector('[data-counsel-seq]').innerText
            const student_seq = tr.querySelector('[data-student-seq]').value;
            if(vthis.checked){
                chks[counsel_seq] = {
                    counsel_seq:counsel_seq,
                    student_seq:student_seq,
                    outer_html:tr.outerHTML
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
                toast('선택된 상담 리스트가 없습니다.');
                return;
            }

            const table = document.querySelector('[data-tby-bundle="counsel_list"]').closest('table').cloneNode(true);
            const bundle = table.querySelector('[data-tby-bundle="counsel_list"]');
            bundle.innerHTML = '';

            keys.forEach(function(key){
                bundle.innerHTML += chks[key].outer_html;
            });

            const html = table.outerHTML;
            _excelDown('학생_상담_목록.xls', '학생목록', html);
        }

        // 문자 모달 열기.
        async function teachCounselSmsModalOpen(){
            const keys = Object.keys(chks);
            if(keys.length < 1){
                toast('선택된 상담 리스트의 학생이 없습니다.');
                return;
            }
            const student_seqs = [];
            keys.forEach(function(key){
                student_seqs.push(chks[key].student_seq);
            });
            if(await alarmSendGetSmsInfo(student_seqs)){
                alarmSendSmsModalOpen();
            }
        }
        // 학습플래너 수정.
        function teachCounselMovePlaner(){
            // 체크된 학생 가져오기.
            const keys = Object.keys(chks);
            const student_seqs = [];
            keys.forEach(function(key){
                student_seqs.push(chks[key].student_seq);
            });
            const form = document.querySelector('[data-form-learningplan]');
            form.querySelector('[data-form-student-seq]').value = student_seqs.join(',');
            form.submit();
        }

    </script>
@endsection
