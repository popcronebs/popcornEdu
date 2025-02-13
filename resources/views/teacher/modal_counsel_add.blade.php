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
{{-- 모달 / 정기상담일 등록 --}}
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
                                <img src="{{ asset('images/mess_icon.svg') }}" width="32">
                                <div class="d-inline-block select-wrap select-icon">
                                    <select data-select-modal-title onchange="teachCounselModalTitleChange(this);"
                                        class="rounded-pill border-0 sm-select text-sb-24px py-0 ps-0 pe-5"
                                        style="outline:none">
                                        <option value="regular">정기상담일 등록</option>
                                        <option value="no_regular">수시상담 등록</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="half-date">
                            <div class="half-wrap px-4">
                                <div id="counsel_modal_calendar"
                                    class="todo-list-date date-type-1 modal-date modal-shadow-style rounded-4 mb-5">
                                </div>

                                <div data-hidden="regular">
                                    <div class="row justify-content-between pt-4 pb-2">
                                        <p class="col-auto text-sb-20px">반복 일정 설정</p>
                                        <p class="col-auto text-sb-20px studyColor-text-studyComplete">※ 현재 매주
                                            <span data-span-week-time></span>
                                        </p>
                                    </div>
                                    <div class="d-inline-block select-wrap select-icon w-100">
                                        <select class="border-gray lg-select text-sb-20px w-100"
                                            data-select-counsel-week-time onchange="teachCounselSelectSchedule()">
                                            <option data-week="" value="">반복 일정 선택</option>
                                            <option data-week="일요일" value="0">매주 일요일 반복 설정</option>
                                            <option data-week="월요일" value="1">매주 월요일 반복 설정</option>
                                            <option data-week="화요일" value="2">매주 화요일 반복 설정</option>
                                            <option data-week="수요일" value="3">매주 수요일 반복 설정</option>
                                            <option data-week="목요일" value="4">매주 목요일 반복 설정</option>
                                            <option data-week="금요일" value="5">매주 금요일 반복 설정</option>
                                            <option data-week="토요일" value="6">매주 토요일 반복 설정</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-52 pb-2">
                                    <p class="text-sb-20px">상담 시간 선택</p>
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

                                <div class="d-inline-block select-wrap select-icon w-100 pt-2">
                                    <select class="border-gray lg-select text-sb-20px w-100"
                                        onchange="teachCounselSelectSchedule()" data-select-counsel-time-between
                                        disabled>
                                        <option value="">0분</option>
                                    </select>
                                </div>
                                <p data-hidden="no_regular_onemore"
                                    class="col-auto text-sb-20px studyColor-text-studyComplete text-center pt-4"
                                    hidden>
                                    ※ 학생이 여러명일 경우 상담시간 미정으로 자동 설정됩니다.
                                </p>
                                <div class="row w-100 mt-80 mb-52">
                                    <div class="col-6 ps-0">
                                        <button type="button" onclick="teachCounselModalBack();"
                                            class="btn-lg-secondary text-sb-20px rounded scale-bg-gray_01 scale-text-gray_05 w-100 justify-content-center">뒤로가기</button>
                                    </div>
                                    <div class="col-6 pe-0">
                                        <button type="button" data-btn-event-exit onclick="teachCounselInsert();"
                                            class="btn-lg-primary text-b-24px rounded scale-text-white w-100 text-center justify-content-center">상담일정
                                            등록하기</button>
                                    </div>
                                </div>
                            </div>
                            <div
                                class="modal-sub-footer h-62 d-flex justify-content-center align-items-center primary-bg-bg overflow-hidden rounded-3 rounded-top-0">
                                <p class="text-m-18px primary-text-text">희망 상담일자를 캘린더에서 선택 후, 상담을 등록해 주세요.</p>
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
                            <ul data-ul-counsel-list-bundle>
                                <li data-li-counsel-list-row="copy" hidden
                                    class="row justify-content-between scale-bg-white p-4 mb-2 rounded-3">
                                    <input type="hidden" data-student-seq>
                                    <p class="col-auto d-flex text-sb-20px">
                                        <span data-student-name>김팝콘</span>
                                        <span data-grade-name>(초3)</span>
                                        <span class="d-block border-gray mx-2 my-1"></span>
                                        <b class="gray-color" data-counsel-type>수시상담</b>
                                    </p>
                                    <span class="col-auto gray-color text-b-20px"
                                        data-counsel-start-end-time>15:00-15:20</span>
                                </li>
                            </ul>
                            <p class="text-b-20px mt-80 pb-3">선택학생</p>
                            <ul>
                                <li class="scale-bg-white p-4 mb-2 rounded-3">
                                    <div class="d-flex justify-content-between align-items-center ">
                                        <p class="text-b-20px">
                                            <span data-student-name="fix">최상담 학생</span>
                                            <span data-student-grade-name="fix">(초5)</span>
                                            <span data-select-outer-str="fix" hidden>외 00명</span>
                                        </p>
                                        <button class="btn p-0 " type="button" data-btn-updown
                                            onclick="teachCounselSelectStudentUpDown(this)">
                                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" alt=""
                                                class="align-middle">
                                        </button>
                                    </div>
                                    <div class="mt-4" data-hidden="onemore" hidden>
                                        <ul data-student-select-bundle class="li-lineblock">
                                            <li class="cursor-pointer" data-student-select-row="copy" hidden
                                                onclick="teachCounselDeleteSelectedStudent(this)">
                                                <span span class="text-m-20px gray-color align-middle">
                                                    <span data-student-name>김팝콘</span>
                                                    <span data-grade-name>(초2)</span>
                                                </span>
                                                <button class="btn p-0 " type="button">
                                                    <img src="{{ asset('images/svg/close-gray.svg') }}"
                                                        width="24" height="24" alt=""
                                                        class="align-middle">
                                                </button>
                                                <input type="hidden" data-student-seq>
                                                <input type="hidden" data-student-type>
                                            </li>
                                        </ul>
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
<script>
    function fullcalendarInit2() {
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
                    teachCounselClear();
                } else {
                    $(".fc-daygrid-day-top > a.active").removeClass("active");
                    $clickedElement.addClass("active");
                    const select_date_str = eventClickInfo.date.format('yyyy.MM.dd E');
                    document.querySelector('[data-p-counsel-seldate-modal]').innerText = select_date_str;
                    document.querySelector('[data-select-counsel-week-time]').value = eventClickInfo.date
                        .getDay();
                    document.querySelector('[data-select-counsel-week-time]').onchange();
                    teachCounselSelect();
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
                if (arg.event.title.includes('정기상담') || arg.event.title.includes('재등록상담')) {
                    return 'management-calendar type-1';
                } else if (arg.event.title.includes('수시상담') || arg.event.title.includes('신규상담')) {
                    return 'management-calendar type-2';
                }
            },
        });
        return counsel_modal_calendar;
    }
    // 모달 / 모달 타이틀 변경. (정기상담, 수시상담)
    function teachCounselModalTitleChange(vthis) {
        const modal = document.querySelector('#counsel_modal_add');
        const modal_title = vthis;
        if (vthis.value == 'regular') {
            // 정기상담 = 반복 일정 설정 보이기   
            // 상담시간 미정 = 체크박스 숨기기
            modal.querySelectorAll('[data-hidden="regular"]').forEach(function(el) {
                el.hidden = false;
            });
            modal.querySelectorAll('[data-hidden="no_regular"]').forEach(function(el) {
                el.hidden = true;
            });
            //시간미정체크 해제
            modal.querySelector('[data-chk-counsel-time-unknow]').checked = false;
            modal.querySelector('[data-chk-counsel-time-unknow]').onchange();

        } else if (vthis.value == 'no_regular') {
            // 수기상담 = 반복 일정 설정 숨기기
            // 상담시간 미정 = 체크박스 보이기
            modal.querySelectorAll('[data-hidden="regular"]').forEach(function(el) {
                el.hidden = true;
            });
            modal.querySelectorAll('[data-hidden="no_regular"]').forEach(function(el) {
                el.hidden = false;
            });

        }
    }

    // 모달 / 모달안에 시간설정을 하나라도 바꾸면  
    function teachCounselSelectSchedule() {
        let week_str = '';
        const modal = document.getElementById('counsel_modal_add');
        // 반복 일정 설정시. data-select-counsel-week-time
        const week_time_el = modal.querySelector('[data-select-counsel-week-time]');
        if (week_time_el.value != '') {
            const s_idx = week_time_el.options.selectedIndex;
            week_str = week_time_el.options[s_idx].getAttribute('data-week');
        }

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
                time_between_el.disabled = false;
            }

        } else {
            modal.querySelectorAll('[data-start-end-time]').forEach(function(el) {
                el.innerText = '시작시간분 선택';
            });
            time_between_el.disabled = true;
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

        //현재 매주 '요일' '시간' 넣기.
        modal.querySelector('[data-span-week-time]').innerText = `${week_str} ${start_time}`;

    }

    // 상담 시간 미정일때
    function teachCounselTimeUnknow(vthis) {
        const modal = document.querySelector('#counsel_modal_add');
        const seltor_str =
            `[data-select-counsel-start-time], 
          [data-select-counsel-end-time],
          [data-select-counsel-time-between]
          `;
        if (vthis.checked) {
            // 시간 관련 select 모두 disabled
            // 시간 관련 select 모두 초기화
            modal.querySelectorAll(seltor_str).forEach(function(el) {
                el.disabled = true;
                el.value = '';
                el.classList.add('scale-bg-gray_01');
            });

        } else {
            // 시간 관련 select 모두 disabled 해제
            modal.querySelectorAll(seltor_str).forEach(function(el) {
                el.disabled = false;
                el.classList.remove('scale-bg-gray_01');
            });
        }

    }

    // 모달 / 선택학생 updown버튼 클릭
    function teachCounselSelectStudentUpDown(vthis) {
        const modal_el = document.getElementById('counsel_modal_add');
        if (vthis.classList.contains('rotate-180')) {
            vthis.classList.remove('rotate-180');
            modal_el.querySelector('[data-hidden="onemore"]').hidden = true;
        } else {
            vthis.classList.add('rotate-180');
            modal_el.querySelector('[data-hidden="onemore"]').hidden = false;
        }
    }

    // 모달 / 선택학생 삭제
    function teachCounselDeleteSelectedStudent(vthis) {
        const modal_el = document.getElementById('counsel_modal_add');
        const bundle = vthis.closest('[data-student-select-bundle]');
        vthis.remove();

        //번들안에 row="clone" 개수를 외 몇명으로 변경
        const cnt = bundle.querySelectorAll('[data-student-select-row="clone"]').length;
        if (cnt == 0) {
            //data-student-name="fix", data-student-grade-name="fix", data-select-outer-str="fix" 초기화
            modal_el.querySelector('[data-student-name="fix"]').innerText = '선택학생';
            modal_el.querySelector('[data-student-grade-name="fix"]').innerText = '';
            modal_el.querySelector('[data-select-outer-str="fix"]').hidden = true;
            modal_el.querySelector('[data-btn-updown]').classList.remove('rotate-180');
            bundle.closest('[data-hidden="onemore"]').hidden = true;
        } else if (cnt == 1) {
            modal_el.querySelector('[data-select-outer-str="fix"]').hidden = true;

            //1명일 경우에 상담 시간 미정등을 풀어준다.
            modal_el.querySelector('[data-hidden="onemore"]').hidden = true;
            modal_el.querySelector('[data-select-modal-title]').disabled = false;
            modal_el.querySelector('[data-chk-counsel-time-unknow]').closest('label').hidden = false;
            modal_el.querySelector('[data-hidden="no_regular_onemore"]').hidden = true;
        } else {
            modal_el.querySelector('[data-select-outer-str="fix"]').innerText = `외 ${cnt-1}명`;
        }
    }

    // 상담 목록의 달력 표시 정보 불러오기.
    function teachCounselSelectCalendar(is_main) {
        let main_div = document.querySelector('#counsel_modal_add');
        if (is_main) main_div = document.querySelector('#teach_counsel_div_main');

        let calendar_obj = counsel_modal_calendar;
        if (is_main) calendar_obj = todoListCalendarTypeTwo;

        const start_date = calendar_obj.view.activeStart.format('yyyy-MM-dd');
        const end_date = calendar_obj.view.activeEnd.format('yyyy-MM-dd');
        const counsel_types = ['regular', 'no_regular'];

        const search_type_el = main_div.querySelector('[data-select-teach-counsel-search-type]');
        const search_str_el = main_div.querySelector('[data-inp-teach-counsel-search-str]');
        const search_type = search_type_el ? search_type_el.value : '';
        const search_str = search_str_el ? search_str_el.value : '';
        const page = "/teacher/counsel/calendar/select";

        // 이용권 상담 일때.
        const counsel_category_el = document.querySelector('[data-main-counsel-category]');
        const counsel_category = counsel_category_el ? counsel_category_el.value : '';

        const parameter = {
            start_date: start_date,
            end_date: end_date,
            counsel_types: counsel_types,
            search_type: search_type,
            search_str: search_str,
            counsel_category: counsel_category
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                const events = [];
                result.counsels.forEach(counsel => {
                    // { start: event.start, title: event.title, count: 0 }
                    const event = {
                        title: teachCounselType(counsel.counsel_type),
                        start: counsel.start_date,
                        count: counsel.cnt
                    };
                    events.push(event);
                });
                calendar_obj.removeAllEvents();
                calendar_obj.addEventSource(events);
            }
        });

    }

    // 모달 / 정기상담일 등록
    function teachCounselOpenRegularCounselModal(students, sel_date_in) {
        const modal_el = document.getElementById('counsel_modal_add');
        // 학생정보를 불러온다. 
        // 학생정보는 무조건 1명 이상 이므로 바로 진행.
        modal_el.querySelector('[data-student-name="fix"]').innerText = `${students[0].student_name} 학생`;
        modal_el.querySelector('[data-student-grade-name="fix"]').innerText = `(${students[0].grade_name})`;
        modal_el.querySelector('[data-btn-event-exit]').setAttribute('onclick', 'teachCounselInsert();');
        modal_el.querySelector('[data-btn-event-exit]').innerText = '상담일정 등록하기';
        // 2명 이상일 경우에는 뒤에 외 00명을 추가.
        if (students.length > 1) {
            modal_el.querySelector('[data-select-outer-str="fix"]').hidden = false;
            modal_el.querySelector('[data-select-outer-str="fix"]').innerText = `외 ${students.length - 1}명`;
            modal_el.querySelector('[data-btn-updown]').classList.add('rotate-180');
            modal_el.querySelector('[data-select-modal-title]').value = 'no_regular';
            modal_el.querySelector('[data-select-modal-title]').onchange();
            modal_el.querySelector('[data-select-modal-title]').disabled = true;
            modal_el.querySelector('[data-hidden="no_regular_onemore"]').hidden = false;
            modal_el.querySelector('[data-chk-counsel-time-unknow]').checked = true;
            modal_el.querySelector('[data-chk-counsel-time-unknow]').onchange();
            modal_el.querySelector('[data-chk-counsel-time-unknow]').closest('label').hidden = true;

        } else {
            modal_el.querySelector('[data-select-outer-str="fix"]').hidden = true;
            modal_el.querySelector('[data-btn-updown]').classList.remove('rotate-180');
            modal_el.querySelector('[data-hidden="onemore"]').hidden = true;
            modal_el.querySelectorAll('[data-student-select-row="clone"]').forEach(function(el) {
                el.remove();
            });
            modal_el.querySelector('[data-select-modal-title]').disabled = false;
            modal_el.querySelector('[data-chk-counsel-time-unknow]').closest('label').hidden = false;
            modal_el.querySelector('[data-hidden="no_regular_onemore"]').hidden = true;

        }

        // 상세 학생 넣어주기. 및 초기화
        const bundle = modal_el.querySelector('[data-student-select-bundle]');
        bundle.closest('[data-hidden="onemore"]').hidden = false;
        bundle.querySelectorAll('[data-student-select-row="clone"]').forEach(function(el) {
            el.remove();
        });

        students.forEach(function(student) {
            const row = bundle.querySelector('[data-student-select-row="copy"]').cloneNode(true);
            row.setAttribute('data-student-select-row', 'clone');
            row.hidden = false;
            row.querySelector('[data-student-name]').innerText = student.student_name;
            row.querySelector('[data-grade-name]').innerText = student.grade_name;
            row.querySelector('[data-student-seq]').value = student.student_seq;
            row.querySelector('[data-student-type]').value = student.student_type;
            bundle.appendChild(row);
        });



        const myModal = new bootstrap.Modal(modal_el, {
            backdrop: 'static',
            keyboard: false
        });
        myModal.show();
        // 캘린더 랜더
        setTimeout(() => {
            counsel_modal_calendar.render();
            teachCounselSelectCalendar();
            // todoListCalendarTypeTwo의 선택된 날짜를 가지고 온다.
            let select_date = new Date();
            try {
                select_date = todoListCalendarTypeTwo.getDate();
            } catch (e) {}
            try {
                const sel_date_str0 = document.querySelector('[data-counsel-start-date]').value;
                select_date = new Date(sel_date_str0);
            } catch (e) {}
            //앞에 달력에서 날짜를 선택했으면, 그 날짜로 변경
            if (document.querySelectorAll('#todoListCalendarTypeTwo .fc-daygrid-day-number.active').length) {
                const sel_el = document.querySelector('#todoListCalendarTypeTwo .fc-daygrid-day-number.active');
                const sel_date_str = sel_el.closest('td').getAttribute('data-date');
                select_date = new Date(sel_date_str);
            } else {
                document.querySelectorAll('#counsel_modal_add .fc-daygrid-day-number.active').forEach(function(
                    el) {
                    el.classList.remove('active');
                });
            }
            if (sel_date_in != undefined) {
                select_date = new Date(sel_date_in);
                document.querySelectorAll('#counsel_modal_add .fc-daygrid-day-number.active').forEach(function(
                    el) {
                    el.classList.remove('active');
                });
            }
            // counsel_modal_calendar.select(select_date);
            const click_el = document.querySelector(
                `#counsel_modal_add [data-date="${select_date.format('yyyy-MM-dd')}"]`);
            click_el.querySelector('.fc-daygrid-day-top .fc-daygrid-day-number').classList.add('active');
            document.querySelector('[data-p-counsel-seldate-modal]').innerText = select_date.format(
                'yyyy.MM.dd E');
            document.querySelector('[data-select-counsel-week-time]').value = select_date.getDay();
            document.querySelector('[data-select-counsel-week-time]').onchange();
            teachCounselSelect();
        }, 200);
    }

    // 상담 목록 불러오기.
    function teachCounselSelect(is_main) {
        const counsel_category_el = document.querySelector('[data-main-counsel-category]');
        const counsel_category = counsel_category_el ? counsel_category_el.value : '';
        let main_div = document.querySelector('#counsel_modal_add');
        const today = new Date().format('yyyy-MM-dd');
        if (is_main) main_div = document.querySelector('#teach_counsel_div_main');;

        const sel_date = main_div.querySelector('[data-date] .active').closest('td').getAttribute('data-date');
        const page = "/teacher/counsel/select";
        const counsel_types = ['regular', 'no_regular'];
        const parameter = {
            counsel_category: counsel_category,
            sel_date: sel_date,
            counsel_types: counsel_types
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
                    main_div.querySelector('[data-counsel-total]').innerText = `총 ${result.counsels.length}건`;
                }
                result.counsels.forEach(counsel => {
                    const row = row_copy.cloneNode(true);
                    row.hidden = false;
                    row.setAttribute('data-li-counsel-list-row', 'clone');
                    row.classList.add(teachCounselTypeIcon(counsel.counsel_type, counsel.student_type));
                    row.querySelector('[data-counsel-type]').innerText = teachCounselType(counsel
                        .counsel_type);
                    row.querySelector('[data-student-name]').innerText = `${counsel.student_name} `;
                    row.querySelector('[data-grade-name]').innerText = `(${counsel.grade_name})`;
                    row.querySelector('[data-counsel-start-end-time]').innerText =
                        ((counsel.start_time || '') != '' ?
                            `${counsel.start_time.substr(0,5)} ~ ${counsel.end_time.substr(0,5)}` : '미정'
                            );
                    row.querySelector('[data-counsel-start-end-time]').setAttribute('data-start-time',
                        counsel.start_time || '');
                    row.querySelector('[data-counsel-start-end-time]').setAttribute('data-end-time',
                        counsel.end_time || '');
                    if (is_main) {
                        const goods_name_el = row.querySelector('[data-goods-name]');
                        if (goods_name_el) goods_name_el.innerText = counsel.goods_name;
                        const student_type_el = row.querySelector('[data-student-type]');
                        if (student_type_el) student_type_el.innerText = (counsel.student_type == 'new' ?
                            '신규' : '재등록');
                        const counsel_cnt_el = row.querySelector('[data-counsel-cnt]');
                        if ((counsel.counsel_cnt || 0) > 0 && counsel_cnt_el) counsel_cnt_el.innerText =
                            `(${counsel.counsel_cnt}차)`;
                        row.querySelector('[data-group-name]').innerText = counsel.group_name;
                        row.querySelector('[data-student-phone]').innerText = counsel.student_phone ||
                            '';
                        row.querySelector('[data-parent-phone]').innerText = counsel.pt_parent_phone ||
                            '';
                        let last_counsel_date = result.last_counsel_date_arr[counsel.student_seq] || '';
                        row.querySelector('[data-counsel-last-date]').innerText = last_counsel_date
                            .substr(0, 16).replace(/-/g, '.');
                        let next_counsel_date = result.next_counsel_date_arr[counsel.student_seq] || '';
                        row.querySelector('[data-counsel-next-date]').innerText = next_counsel_date
                            .substr(0, 25).replace(/-/g, '.').replace('00:00:00', '');
                        // 이용권을 사용 했을 경우에,
                        if ((counsel.goods_start_date || '') != '') {
                            // 2023-12-06 ~ 2024-07-21 형태를 2023.10.01-11.01 형태로 변경
                            let start_end_date =
                                `${counsel.goods_start_date.substr(0,10)}~${counsel.goods_end_date.substr(5)}`;
                            start_end_date = start_end_date.replace(/-/g, '.');
                            start_end_date = start_end_date.replace('~', '-');
                            row.querySelector('[data-goods-start-end-date]').innerText = start_end_date;
                            // 오늘 날짜와 끝날짜 비교해서 남은 일수 계산
                            const goods_end_date = new Date(counsel.goods_end_date);
                            const today = new Date();
                            const remain_date = Math.ceil((goods_end_date - today) / (1000 * 60 * 60 *
                                24));
                            row.querySelector('[data-goods-remain-date]').innerText =
                                `(${remain_date}일 남음)`;
                        }

                        // 오늘 날짜와 상담일이 같으면 상담일 표시
                        if (is_today) row.querySelector('[data-div-counsel-expire]').hidden = false;
                        else row.querySelector('[data-div-counsel-expire]').hidden = true;
                        row.querySelector('[data-counsel-seq]').value = counsel.id;
                        row.querySelector('[data-is-counsel]').value = counsel.is_counsel;
                        if (counsel.is_counsel == 'Y') {
                            row.querySelector('[data-btn-counsel-go]').innerText = '상담일지 확인'
                        } else {
                            // 작성하지 않았고, 선택한 날짜와 오늘 날짜가 같으면
                            if (today == sel_date) {
                                row.querySelector('[data-btn-counsel-go]').innerText = '상담 바로가기';
                            }
                            // 작성하지 않았지만 오늘이 아닌경우.
                            else {
                                // 그런데 선택한 날이 첫 상담일이면 이전 상담을 보여줄수 없음.
                                if (result.first_counsel_date_arr[counsel.student_seq] == counsel.start_date && counsel_category != 'goods') {
                                    //data-div-counsel-expire
                                    row.querySelector('[data-div-counsel-expire]').hidden = false;
                                    row.querySelector('[data-div-counsel-expire] p').innerText =
                                        '첫번째 상담입니다.';
                                    row.querySelector('[data-btn-counsel-go]').hidden = true;
                                }
                                // 이전 상담을 보여준다.
                                else {
                                    const msg = counsel_category == 'goods' ? '상담 상세보기': '이전 상담일지 확인';
                                    row.querySelector('[data-btn-counsel-go]').innerText = msg;
                                    // 바로 직전 상담을 보여줘야 한다.
                                    row.querySelector('[data-is-counsel]').value = 'N_BEFORE';
                                }

                            }
                        }

                    }
                    row.querySelector('[data-student-seq]').value = counsel.student_seq;


                    bundle.appendChild(row);
                });
            }
        });
    }

    function teachCounselTypeIcon(type1, type2) {
        // return type-1, type-2
        if (type2 == 'new') return 'type-2';
        else if (type2 == 'readd') return 'type-1';
        else if (type1 == 'regular') return 'type-1';
        else if (type1 == 'no_regular') return 'type-2';
        else return '';
    }

    function teachCounselType(type) {
        let type_str = ''
        if (type == 'regular') type_str = '정기상담';
        else if (type == 'no_regular') type_str = '수시상담';
        else if (type == 'new') type_str = '신규상담';
        else if (type == 'readd') type_str = '재등록상담';
        else type_str = '미정';
        return type_str;
    }

    // 상담 추가전에 같은 학생이 있는지, 시간 중복이 있는지 확인.
    function teachCounselInsertCheck() {
        let chk = true;
        const modal = document.querySelector('#counsel_modal_add');
        // data-student-select-bundle 의 data-li-counsel-list-row="clone" 을 student_seq가 키값이고 아래 학생정보를 입력.
        const students = {};
        const students_time = [];
        modal.querySelectorAll('[data-li-counsel-list-row="clone"]').forEach(function(student_el) {
            const student_seq = student_el.querySelector('[data-student-seq]').value;
            const start_time = student_el.querySelector('[data-start-time]').getAttribute('data-start-time');
            const end_time = student_el.querySelector('[data-end-time]').getAttribute('data-end-time');
            students[student_seq] = {
                student_seq: student_seq,
                student_name: student_el.querySelector('[data-student-name]').innerText,
            };
            students_time.push({
                start_time: (start_time || '').substr(0, 5),
                end_time: (end_time || '').substr(0, 5)
            });

        });
        // data-ul-counsel-list-bundle 의 data-student-select-row="clone" 를 foreach
        modal.querySelectorAll('[data-student-select-row="clone"]').forEach(function(counsel_el) {
            const student_seq = counsel_el.querySelector('[data-student-seq]').value;
            // 배열에 같은 학생이 있는지 확인.
            if (students[student_seq]) {
                chk = false;
            }
        });

        const start_time_hour = modal.querySelector('[data-select-counsel-start-time="hour"]');
        const start_time_min = modal.querySelector('[data-select-counsel-start-time="min"]');
        const end_time_hour = modal.querySelector('[data-select-counsel-end-time="hour"]');
        const end_time_min = modal.querySelector('[data-select-counsel-end-time="min"]');

        const start_time = `${start_time_hour.value}${start_time_min.value}` * 1;
        const end_time = `${end_time_hour.value}${end_time_min.value}` * 1;

        // 시간의 시작과 끝이 등록되어있는 학생들의 시간과 겹치는지 확인
        // 미정 체크시에는 확인하지 않음.
        const chk_time_unknow = modal.querySelector('[data-chk-counsel-time-unknow]').checked;
        if (chk_time_unknow) return chk;

        students_time.forEach(function(time) {
            if (time.end_time.replace(':', '') * 1 > start_time && time.start_time.replace(':', '') * 1 <
                end_time) {
                chk = false;
            }
        });

        // 상담시간의 시작시간과 끝시간이 같은지, 또는 시작시간이 끝시간보다 빠른지 확인.
        if (start_time >= end_time) {
            chk = false;
        }

        return chk;
    }


    // teachCounselModalBack
    // teachCounselInsert

    //----------------------------------------------
    // 모달 
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
