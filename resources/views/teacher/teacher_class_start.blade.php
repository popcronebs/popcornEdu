@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
클래스 수업
@endsection
{{-- 추가 코드
1. 학습신호등 옆 일차, 총 일수 기능.
2. 수업종료시 알림전송 미 구현.
3. 오늘의 현황 결석 외 나머지 미구현.
--}}

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<style>
    .bg-attend {
        background: #18db72 !important;
    }

    .border-attend {
        border: 2px solid #18db72 !important;
    }

    .text-attend {
        color: #18db72 !important;
    }

    .border-black {
        border: 2px solid #222 !important;
    }
</style>
<div class="position-relative zoom_sm">
    <input type="hidden" data-main-class-seq value="{{ $class_seq }}">
    <input type="hidden" data-main-team-code value="{{ $team_code }}">
    <input type="hidden" data-main-class-name value="{{ $class->class_name }}">
    {{-- 클래스 반 이름 --}}
    <div class="sub-title">
        <h2 class="text-sb-42px">
            <button data-btn-back-page class="btn p-0 row mx-0 all-center" onclick="classStartDetailBack();">
                <img src="{{ asset('images/black_arrow_left_tail.svg') }}" width="52" class="px-0">
            </button>
            {{ $class->grade_name . ' ' . $class->class_name }}
            <span class="ht-make-title on text-r-20px py-2 px-3 ms-1">방과후</span>
        </h2>
    </div>
    {{-- 오늘의 현황 --}}
    <div class="setion-block">
        <div
            class="sh-title-wrap align-items-sm-center justify-content-sm-between justify-content-start flex-column flex-sm-row">
            <div class="right-text">
                <img src="{{ asset('images/calendar_chk_icon.svg') }}" width="32">
                <p class="text-sb-28px">오늘의 현황</p>
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
                            <p class="text-b-24px scale-text-gray_05">{{ date('y년 m월 d일') }}</p>
                            <p class="text-b-24px scale-text-gray_05"><b class="black-color">학습 완료</b>입니다.</p>
                        </div>
                        <div>
                            <p class="gray-color text-m-20px">
                                <span data-study-complete-cnt class="black-color text-sb-42px">0</span>명
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 col-md-6">
                <div class="card-box h-100 px-4 py-3 mb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-row lh-base gray-color">
                            <p class="text-b-24px scale-text-gray_05">{{ date('y년 m월 d일') }}</p>
                            <p class="text-b-24px scale-text-gray_05"><b class="black-color">미수강</b>입니다.</p>
                        </div>
                        <div>
                            <p class="gray-color text-m-20px"><span data-study-no-cnt
                                    class="black-color text-sb-42px">0</span>명</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6">
                <div class="card-box h-100 px-4 py-3 mb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-row lh-base gray-color">
                            <p class="text-b-24px scale-text-gray_05">{{ date('y년 m월 d일') }}</p>
                            <p class="text-b-24px scale-text-gray_05"><b class="black-color">오답노트 미완료</b>입니다.</p>
                        </div>
                        <div>
                            <p class="gray-color text-m-20px"><span data-no-answer-note-cnt
                                    class="black-color text-sb-42px">0</span>명</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6 col-md-6 mb-2">
                <div class="card-box h-100 px-4 py-3 mb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-row lh-base gray-color">
                            <p class="text-b-24px scale-text-gray_05">{{ date('y년 m월 d일') }}</p>
                            <p class="text-b-24px scale-text-gray_05"><b class="black-color">결석</b>입니다.</p>
                        </div>
                        <div>
                            <p class="gray-color text-m-20px"><span data-attend-no-cnt
                                    class="black-color text-sb-42px">0</span>명</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <article>
        <div class="row gx-4">
            <aside class="col-lg-4 ps-0">
                {{-- 금일 보강 일정 --}}
                <div class="modal-shadow-style rounded py-4 px-3">
                    <div class="col-auto row mx-1">
                        <div class="col text-sb-24px h-center px-0">금일 보강일정</div>
                        <div class="col-auto h-center px-0">
                            <span class="text-sb-20px cursor-pointer" onclick="classStartManagementPage();">더 보기</span>
                            <button class="btn p-0 h-center">
                                <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="24">
                            </button>
                        </div>
                    </div>
                    {{-- 보강일정 리스트 --}}
                    <div data-bundle="today_reinforcement">
                        <div data-row="copy" class="row mx-0 rounded scale-bg-gray_01 mb-2 p-3" hidden>
                            <input type="hidden" data-absent-seq>
                            <input type="hidden" data-student-seq>
                            <div class="col">
                                <div class="text-sb-20px mb-2">
                                    <span data-student-name></span>
                                    <span data-grade-name></span>
                                    <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12" class="mx-2">
                                    <span data-absent-reason></span>
                                </div>
                                <div class="text-sb-20px scale-text-gray_05">
                                    <span data-absent-date></span>
                                    <span data-absent-day></span>
                                    <span data-absent-start-time></span>
                                </div>
                            </div>
                            <div class="col-auto h-center">
                                <button onclick="classStartReinforcementDateComplete(this);" data-btn-ref-complete
                                    class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black px-3">보강완료</button>
                            </div>

                        </div>
                    </div>

                </div>
                {{-- 알림센터 --}}
                <div class="modal-shadow-style rounded py-4 px-3 mt-4">
                    <div class="col-auto row mx-1 mb-2">
                        <div class="col text-sb-24px h-center px-0">알림 센터</div>
                        <div class="col-auto h-center px-0">
                            <span class="text-sb-20px cursor-pointer">더 보기</span>
                            <button class="btn p-0 h-center" onclick="classStartMoveAlarm();">
                                <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="24">
                            </button>
                        </div>
                    </div>
                    {{-- 알림센터 리스트 --}}
                    <div data-bundle="today_alams">
                        <div data-row="copy" class="row mx-0 rounded scale-bg-gray_01 mb-2 p-3" hidden>
                            <input type="hidden" data-push-seq>
                            <div class="col">
                                <div class="text-sb-20px mb-2">
                                    <span data-student-name=""></span>
                                    <span data-grade-name=""></span>
                                    <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12" class="mx-2">
                                    <span data-new hidden class="text-danger">NEW</span>
                                </div>
                                <div class="text-sb-20px scale-text-gray_05">
                                    {{-- title을 할지, content를 할지 .. --}}
                                    <span data-push-title></span>
                                </div>
                            </div>
                            <div class="col-auto text-sb-20px scale-text-gray_05">
                                <span data-created-at></span>
                            </div>
                        </div>
                    </div>

                </div>
            </aside>

            {{-- 학습신호등 --}}
            <div class="col pe-0">
                {{-- TITLE --}}
                <div class="h-center">
                    <img src="{{ asset('images/traffic_light_icon.svg') }}" alt="">
                    <div class="text-b-24px">학습신호등</div>
                    <div class="scale-bg-gray_01 scale-text-gray_05 px-2 rounded-pill ms-2 py-1">
                        <span data-month-of-today>{{$today_attend_idx}}</span>일차 / 총 <span data-month-last-date>{{$total_attend_cnt}}</span>일
                    </div>
                    <div class="col w-end">
                        <button onclick="classStartAttendBtnClick();" class="btn h-center text-b-20px"
                            data-btn-more-show-attend-chk>
                            <span>더 보기</span>
                            <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="32">
                        </button>
                    </div>
                </div>

                {{-- 학습신호등 리스트 --}}
                <div class="mt-3">
                    <table class="w-100 table-style table-h-82">
                        <thead class="modal-shadow-style rounded">
                            <tr class="text-sb-20px ">
                                {{-- 학년/반, 이름, 출석, 수강, 오답노트, 출석률 --}}
                                <th>학년/반</th>
                                <th>이름</th>
                                <th>출석</th>
                                <th>수강</th>
                                <th>오답노트</th>
                                <th>출석률</th>
                            </tr>
                        </thead>
                        <tbody data-bundle="tby_traffic_light">
                            <tr data-row="copy" class="text-m-20px" hidden>
                                <td>
                                    <span data-grade-name></span>
                                    <span data-class-name></span>
                                </td>
                                <td data-student-name></td>
                                <td>
                                    <button data-btn-attend-status
                                        class="btn px-2 py-1 bg-danger text-white rounded-pill">미완료</button>
                                    {{-- secondary-bg-mian / 수강중 --}}
                                    {{-- bg-green / 완료 --}}
                                </td>
                                <td>
                                    <button data-btn-study-status
                                        class="btn px-2 py-1 bg-danger text-white rounded-pill">미완료</button>
                                </td>
                                <td>
                                    <button data-btn-no-answer-note-status
                                        class="btn px-2 py-1 bg-danger text-white rounded-pill">미완료</button>
                                </td>
                                <td>
                                    <span data-attend-cnt class="black-color">0</span> / <span
                                        data-all-day-cnt>{{$total_attend_cnt}}</span>일
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="d-flex">
                        <div class="col"></div>
                        <div class="col">
                            {{-- 페이징 --}}
                            <div class="col d-flex justify-content-center">
                                <ul class="pagination col-auto" data-page="1" hidden>
                                    <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="1"
                                        onclick="userPaymentPageFunc('1', 'prev')">
                                        <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                                    </button>
                                    <li class="page-item" hidden>
                                        <a class="page-link" onclick="">0</a>
                                    </li>
                                    <span class="page" data-page-first="1" hidden
                                        onclick="userPaymentPageFunc('1', this.innerText);" disabled>0</span>
                                    <button href="javascript:void(0)" class="btn p-0 next" data-page-next="1"
                                        onclick="userPaymentPageFunc('1', 'next')" data-is-next="0">
                                        <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                                    </button>
                                </ul>
                            </div>
                        </div>
                        <div class="col text-end mt-5">
                            <button onclick="classStartAttendBtnClick()" data-btn-attend-chk
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black px-3">출석체크</button>
                            <button onclick="classStartEndClassBtnClick()" data-btn-end-class
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black px-3">수업종료</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </article>

    {{-- 160px --}}
    <div>
        <div class="py-lg-5"></div>
        <div class="py-lg-4"></div>
        <div class="pt-lg-3"></div>
    </div>

    {{-- 모달 / 출석체크 --}}
    <div class="modal fade" id="class_start_modal_attend" tabindex="-1" aria-hidden="true"
        style="--bs-modal-width:60%">
        <div class="modal-dialog rounded modal-xl">
            <div class="modal-content border-none rounded modal-shadow-style p-0 overflow-hidden" style="width:1300px">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title fs-5 text-b-24px" id="">
                        {{ $class->team_name }}
                    </h1>
                    <button type="button" style="width: 32px;height: 32px;" class="btn-close close-btn"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0 px-3">
                    <div class="d-flex">
                        <div class="d-inline-block select-wrap select-icon h-62 pe-6">
                            <select class="border-gray lg-select text-sb-20px h-62" data-serach-type="modal">
                                <option selected="" value="name">이름</option>
                            </select>
                        </div>
                        <label class="label-search-wrap ps-6 w-100">
                            <input data-search-str="modal" type="text"
                                onkeyup="if(event.keyCode==13){classStartStudentSelect('modal'); }"
                                class="lg-search border-gray rounded text-m-20px w-100" placeholder="학생 이름을 검색해주세요.">
                        </label>
                    </div>
                    <div class="h-center jsutify-content-between py-3">
                        <span data-today-str="modal" class="text-sb-20px"></span>
                        <div class="col text-end">
                            <button type="button" onclick="classStartDoAttend();"
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black px-4 h-52">선택
                                학생 출석</button>
                            <div class="d-inline-block select-wrap select-icon h-62 pe-6">
                                <select data-top-absent-reason
                                    class="border-gray lg-select text-sb-20px h-52 py-1 rounded">
                                    <option value="">결석사유</option>
                                    <option value="개인사유">개인사유</option>
                                    <option value="교내행사">교내행사</option>
                                    <option value="공휴일">공휴일</option>
                                    <optoin vlaue="자연재해">자연재해</optoin>
                                    <option vlaue="기타">기타</option>
                                </select>
                            </div>
                            <button type="button" onclick="classStartAbsentReson();"
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-black text-white px-4 h-52">결석사유
                                일괄 등록</button>
                        </div>
                    </div>
                    <div class="mt-3">
                        <table class="w-100 table-style table-h-82">
                            <thead class="modal-shadow-style rounded">
                                <tr class="text-sb-20px ">
                                    {{-- 학년/반, 이름, 출석, 수강, 오답노트, 출석률 --}}
                                    <th>
                                        <label class="checkbox mt-1">
                                            <input type="checkbox" class="" onchange="classStartAllCheck(this)">
                                            <span class="">
                                            </span>
                                        </label>
                                    </th>

                                    <th>학년/반</th>
                                    <th>이름</th>
                                    <th>출석</th>
                                    <th>보강일</th>
                                    <th>수강</th>
                                    <th>오답노트</th>
                                    <th>출석률</th>
                                </tr>
                            </thead>
                            <tbody data-bundle="tby_attend_student_list">
                                <tr data-row="copy" class="text-m-20px" hidden>
                                    <input type="hidden" data-student-seq="modal">
                                    <td>
                                        <label class="checkbox mt-1">
                                            <input type="checkbox" class="chk" onchange="">
                                            <span class="">
                                            </span>
                                        </label>
                                    </td>
                                    <td>
                                        <span data-grade-name="modal"></span>
                                        <span data-class-name="modal"></span>
                                    </td>
                                    <td data-student-name="modal"></td>
                                    <td>
                                        <div class="h-center">
                                            {{-- 출석 신호등 표기 --}}
                                            <div class="col-auto h-center gap-1">
                                                <div data-attend-ring class="border-2 border border-black rounded-pill"
                                                    style="width:20px;height:20px;"></div>
                                                <span data-attend-time></span>
                                            </div>
                                            <div class="col w-end h-center gap-2">
                                                <button data-btn-do-attend onclick="classStartBtnDoAttendClick(this)"
                                                    hidden
                                                    class="btn px-3 py-1 border-attend text-attend rounded-pill text-sb-20px">출석</button>
                                                <button data-btn-cancel-attend
                                                    onclick="classStartBtnCancelAttendClick(this);" hidden
                                                    class="btn px-3 py-1 border text-sb-20px rounded-pill scale-text-gray_05">취소</button>
                                                <div data-div-absent-reason hidden
                                                    class="h-center select-wrap select-icon h-62 pe-6">
                                                    <select data-absent-reason
                                                        class="border-none lg-select text-sb-20px h-52 py-1 rounded"
                                                        onchange="classStartAbsentReson(this)">
                                                        <option value="">결석사유</option>
                                                        <option value="개인사유">개인사유</option>
                                                        <option value="교내행사">교내행사</option>
                                                        <option value="공휴일">공휴일</option>
                                                        <optoin vlaue="자연재해">자연재해</optoin>
                                                        <option vlaue="기타">기타</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- secondary-bg-mian / 수강중 --}}
                                        {{-- bg-green / 완료 --}}
                                    </td>
                                    <td>
                                        {{-- Reinforcement date --}}
                                        <div data-div-reinforcement-date hidden
                                            class="col-auto h-center p-2 justify-content-center">
                                            <img src="{{ asset('images/calendar_gray_icon.svg') }}" class="me-2">
                                            {{-- :날짜시간 PICKER 2 --}}
                                            <div data-bundle-date
                                                class="overflow-hidden col-auto cursor-pointer text-start"
                                                style="height: 25px;">
                                                <div class="h-center justify-content-between">
                                                    <div data-date
                                                        onclick="this.closest('[data-bundle-date]').querySelector('input').showPicker()"
                                                        type="text" class="text-m-20px text-start scale-text-gray_05"
                                                        readonly="" placeholder="">
                                                        {{-- 상담시작일시 --}}
                                                        00.00.00
                                                    </div>
                                                    <img src="{{ asset('images/svg/btn_arrow_down.svg') }}"
                                                        data-edit="hidden" hidden>
                                                </div>
                                                <input type="date" style="width: 80px;height: 0.5px;"
                                                    data-reinforcement-date oninput="classStartDateTimeSel(this)"
                                                    value="0000-00-00">
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <button data-btn-study-status="modal"
                                            class="btn px-2 py-1 bg-danger text-white rounded-pill">미완료</button>
                                    </td>
                                    <td>
                                        <button data-btn-no-answer-note-status="modal"
                                            class="btn px-2 py-1 bg-danger text-white rounded-pill">미완료</button>
                                    </td>
                                    <td>
                                        <span data-attend-cnt="modal" class="black-color">0</span> / <span
                                            data-all-day-cnt>{{$total_attend_cnt}}</span>일
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer border-top-0 py-0 px-3 pb-2 mt-52 d-none"></div>
            </div>

        </div>
    </div>
</div>
<script>
const ct_class_study_dates = @json($class_study_dates);
let today_study_date = null;
document.addEventListener('DOMContentLoaded', function() {
    // 현재 시간 넣어주기.
    const now_time_el = document.querySelector('[data-main-now-time]');
    now_time_el.innerText = new Date().format('yy년 MsM월 dsd일 e a/p hsh시 mm분') + ' 기준';
    // 상단 오늘의 현황
    classStartTodayStatusCount();
    // 학습신호등 불러오기.
    classStartStudentSelect();
    document.querySelector('[data-today-str="modal"]').innerText = new Date().format('yy.MM.dd(E)').replace(
        '요일', '') + ' 출석체크';
    // classStartAttendBtnClick(); // test
    const day_key = new Date().format('E').replace('요일', '');
    today_study_date = ct_class_study_dates[day_key][0];

    // 1분마다 리스트 반복 갱신
    const modal = document.querySelector('#class_start_modal_attend');
    setInterval(function() {
        classStartStudentSelect();
        // 모달이 열려 있으면 모달도 갱신
        if (modal.classList.contains('show')) {
            classStartStudentSelect('modal');
        }
    }, 60000);

    // 금일 보강일정 불러오기.
    classStartReinforcementDateSelect();
    // 알린세터 불러오기.
    calssStartAlarmListSelect();

    // 오늘이 수업날이 아니면 출석체크, 학습신호등 옆 더보기 버튼, 수업종료 버튼을 삭제처리.
    if(!today_study_date){
        document.querySelector('[data-btn-attend-chk]').remove();
        document.querySelector('[data-btn-more-show-attend-chk]').remove();
        document.querySelector('[data-btn-end-class]').remove();
    }

    // 오늘이 수업이 아니면. // 버튼 삭제
    classStartIsTodayClass(today_study_date);

    // 학습신호등 옆에 ~일차 / 총 ~일 수정필요. 코드필요.

    //test
    // today_study_date.start_time = '15:24:00';
});
// 클래스 학생들 리스트 가져오기.
function classStartStudentSelect(type, is_again) {
    const class_seq = document.querySelector('input[data-main-class-seq]').value;
    const team_code = document.querySelector('input[data-main-team-code]').value;
    let search_type = '';
    let search_str = '';
    if(type == 'modal'){
        search_type = document.querySelector('select[data-serach-type="modal"]').value;
        search_str = document.querySelector('input[data-search-str="modal"]').value;
    }


    const page = '/teacher/main/after/class/student/select';
    const parameter = {
        class_seq: class_seq,
        team_code: team_code,
        search_type: search_type,
        search_str: search_str,
        is_search_today:'Y',
    };
    queryFetch(page, parameter, function(result) {
        if (type == undefined) {
            classStartSetStudentSelect(result);
        } else if (type == 'modal') {
            classStartSetModalStudentSelect(result, is_again);
        }
    });

}

// 메인 페이지에서 학생 리스트
function classStartSetStudentSelect(result) {
    // 초기화
    const bundle = document.querySelector('[data-bundle="tby_traffic_light"]')
    const copy_row = bundle.querySelector('[data-row="copy"]').cloneNode(true);
    bundle.innerHTML = '';
    bundle.appendChild(copy_row);
    if ((result.resultCode || '') == 'success') {
        const class_mates = result.class_mates;

        const now_time = new Date().format('HH:mm:00');
        const five_time = new Date().setMinutes(new Date().getMinutes() - 5);
        const ts_time = today_study_date?.start_time || '00:00:00';
        const is_not_class_today = today_study_date?.start_time ? false : true;

        let last_date = '';
        const attend_cnts = result.attend_cnts;
        const complete_cnts = result.complete_cnts;
        const incomplete_cnts = result.incomplete_cnts;
        const inwrong_cnts = result.inwrong_cnts;
        const inwrong_only_cnts = result.inwrong_only_cnts;
        document.querySelector('[data-study-complete-cnt]').innerText = getEachCnt(complete_cnts);
        document.querySelector('[data-study-no-cnt]').innerText = getEachCnt(incomplete_cnts);
        document.querySelector('[data-no-answer-note-cnt]').innerText = inwrong_only_cnts;
        let absent_cnt = 0;
        // altnrkd,
        class_mates.forEach(class_mate => {
            const row = copy_row.cloneNode(true);
            row.hidden = false;
            row.setAttribute('data-row', 'clone');
            row.querySelector('[data-grade-name]').innerText = class_mate.grade_name||'미정';
            row.querySelector('[data-class-name]').innerText = (class_mate.class_name||'미정')+'반';
            row.querySelector('[data-student-name]').innerText = class_mate.student_name;

            //출석, 수강 오답노트.
            let attend_status = '';
            // 수업전, 출석, 결석
            // 수업시작 시간 이전과 이후로 나눔.
            if (ts_time > now_time && (!class_mate.absent_reason) ) {
                // 수업 전
                //미출석이면 수업전으로 표기.
                if (!class_mate.attend_datetime) {
                    attend_status = '수업전';
                    row.querySelector('[data-btn-attend-status]').classList.remove('bg-danger');
                    row.querySelector('[data-btn-attend-status]').classList.add('secondary-bg-mian');
                }

            } else {
                // 수업 시작 후
                // 수업시작후 미출석이면 결석
                if (!class_mate.attend_datetime) {
                    attend_status = '결석';
                    absent_cnt++;
                }
            }
            //시간 상관없이 출석이면 출석으로 표기.
            if (class_mate.attend_datetime) {
                attend_status = '출석';
                row.querySelector('[data-btn-attend-status]').classList.remove('bg-danger'); row.querySelector('[data-btn-attend-status]').classList.add('bg-attend');
            }
            if(is_not_class_today) row.querySelector('[data-btn-attend-status]').hidden = true;
            row.querySelector('[data-btn-attend-status]').innerText = attend_status;
            row.querySelector('[data-btn-study-status]').innerText = class_mate.study_status || '미완료';
            // row.querySelector('[data-btn-no-answer-note-status]').innerText = class_mate.no_answer_note_status || '미완료';
            // 출석률
            // row.querySelector('[data-attend-cnt]').innerText = class_mate.attend_cnt || '0';
            row.querySelector('[data-attend-cnt]').innerText = getAttendCnt(attend_cnts[class_mate.student_seq], class_mate.class_seq);

            // row.querySelector('[data-all-day-cnt]').innerText = class_mate.month_last_day;
            last_date = class_mate.month_last_day;

            // 수강 관련 완료
            // 수강이 있을때만
            if(complete_cnts[class_mate.student_seq] || incomplete_cnts[class_mate.student_seq]){
                // 한개라도 미 완료 수강이 있으면,
                if(incomplete_cnts[class_mate.student_seq]){
                    // 미완료
                }else{
                    // 미완료가 없으면서, 완료 수강이 있으면,
                    if(complete_cnts[class_mate.student_seq]){
                        // 완료
                        row.querySelector('[data-btn-study-status]').innerText = '완료';
                        row.querySelector('[data-btn-study-status]').classList.add('bg-success');
                        row.querySelector('[data-btn-study-status]').classList.remove('bg-danger');
                    }
                }
            }
            // 없으면
            else{
                row.querySelector('[data-btn-study-status]').remove();
            }

            // 오답노트 완료 있으면,
            if(inwrong_cnts[class_mate.student_seq]){
                const inwrong_cnt = inwrong_cnts[class_mate.student_seq][0];
                // 오답있지만 모두 점답처리 이 0인경우.(다했거나, 오답이 없을 경우)
                if(inwrong_cnt.wrong_count == 0){
                    row.querySelector('[data-btn-no-answer-note-status]').innerText = '완료';
                    row.querySelector('[data-btn-no-answer-note-status]').classList.add('bg-success');
                    row.querySelector('[data-btn-no-answer-note-status]').classList.remove('bg-danger');
                }else{
                    row.querySelector('[data-btn-no-answer-note-status]').innerText = '미완료';
                }
            }
            // 없으면
            else{
                row.querySelector('[data-btn-no-answer-note-status]').remove();
            }

            bundle.appendChild(row);
        });
        // if(document.querySelector('[data-attend-no-cnt]').innerText == 0)
        if(!is_not_class_today) document.querySelector('[data-attend-no-cnt]').innerText = absent_cnt;
        // document.querySelector('[data-month-last-date]').innerHTML = last_date;
        // document.querySelector('[data-month-of-today]').innerHTML = new Date().format('dd');
    }
}

// 모달의 학생리스트
function classStartSetModalStudentSelect(result, is_again) {
    const modal = document.getElementById('class_start_modal_attend');
    // 초기화
    const bundle = modal.querySelector('[data-bundle="tby_attend_student_list"]')
    const copy_row = bundle.querySelector('[data-row="copy"]').cloneNode(true);
    bundle.innerHTML = '';
    bundle.appendChild(copy_row);

    const class_mates = result.class_mates;
    const attend_cnts = result.attend_cnts;
    const complete_cnts = result.complete_cnts;
    const incomplete_cnts = result.incomplete_cnts;
    const inwrong_cnts = result.inwrong_cnts;
    class_mates.forEach(class_mate => {
        const row = copy_row.cloneNode(true);
        row.hidden = false;
        row.setAttribute('data-row', 'clone');
        row.querySelector('[data-student-seq="modal"]').value = class_mate.student_seq;
        row.querySelector('[data-grade-name="modal"]').innerText = class_mate.grade_name;
        row.querySelector('[data-class-name="modal"]').innerText = class_mate.class_name;
        row.querySelector('[data-student-name="modal"]').innerText = class_mate.student_name;

        // 출석 인지 아닌지
        const attend_ring = row.querySelector('[data-attend-ring]');
        const five_time = new Date().setMinutes(new Date().getMinutes() - 5);
        const ts_time = today_study_date.start_time;
        if ((class_mate.attend_datetime || '') != '') {
            //출석----------------
            if (ts_time >= new Date().format('HH:mm:00')) {
                // 아직 수업을 시작하지 않았을 때.
                attend_ring.classList.remove('border-black');
                attend_ring.classList.add('border-attend');
            }
            // 수업시작시간 시작 후 5분까지.
            //TODO: 추후 30분 이전가지는 지각으로!?
            else if (ts_time < new Date(five_time).format('HH:mm:00')) {
                // 5분이 지났을 경우.
                attend_ring.classList.remove('border-black');
                attend_ring.classList.add('bg-attend');
                attend_ring.classList.add('border-attend');
            } else {
                attend_ring.classList.remove('border-black');
                attend_ring.classList.add('border-attend');
                attend_ring.classList.add('bg-attend');
            }
            //취소버튼 보이기.
            row.querySelector('[data-btn-cancel-attend]').hidden = false;
            row.querySelector('[data-attend-time]').innerText = new Date(class_mate.attend_datetime || '')
                .format('yy.MM.dd HH:mm');

        } else {
            //미출----------------
            // 아직 수업을 시작하지 않았을 때.
            if (ts_time >= new Date().format('HH:mm:00') && (!class_mate.absent_reason)) {
                //출석 버튼 보이기.
                row.querySelector('[data-btn-do-attend]').hidden = false;
            }
            // 수업시작시간 시작 후 5분까지.
            else if (ts_time < new Date(five_time).format('HH:mm:00')) {
                // 5분이 지났을 경우.
                // 출석버튼 보이기.
                attend_ring.classList.remove('border-black');
                //완전한 결석만 빨간색 칠해주기.(결석사유, 보강일, 수업끝시간 이후.)나머진 보더
                if(class_mate.ref_date || class_mate.absent_reason)
                    attend_ring.classList.add('bg-danger');

                attend_ring.classList.add('border-danger');
                row.querySelector('[data-btn-do-attend]').hidden = false;
                // row.querySelector('[data-btn-cancel-attend]').setAttribute('onclick',
                //     'classStartBtnDoAttendClick(this)');
                //결석사유 보이기
                row.querySelector('[data-div-absent-reason]').hidden = false;
                // 보강일 날짜 선택 보이기.
                row.querySelector('[data-div-reinforcement-date]').hidden = false;
            } else {
                attend_ring.classList.remove('border-black');
                //완전한 결석만 빨간색 칠해주기.(결석사유, 보강일, 수업끝시간 이후.)나머진 보더
                if(class_mate.ref_date || class_mate.absent_reason || today_study_date.end_time < new Date().format('HH:mm:00'))
                    attend_ring.classList.add('bg-danger');

                attend_ring.classList.add('border-danger');
                //출석 버튼 보이기.
                row.querySelector('[data-btn-do-attend]').hidden = false;
                // 보강일 날짜 선택 보이기.
                row.querySelector('[data-div-reinforcement-date]').hidden = false;
            }
        }
        // 결석사유
        row.querySelector('[data-absent-reason]').value = class_mate.absent_reason || '';
        //수강 오답노트.
        row.querySelector('[data-btn-study-status]').innerText = class_mate.study_status || '미완료';
        row.querySelector('[data-btn-no-answer-note-status]').innerText = class_mate
            .no_answer_note_status || '미완료';

        // 출석률
        // row.querySelector('[data-attend-cnt="modal"]').innerText = class_mate.attend_cnt || '0';
        row.querySelector('[data-attend-cnt="modal"]').innerText = getAttendCnt(attend_cnts[class_mate.student_seq], class_mate.class_seq);
        // row.querySelector('[data-all-day-cnt]').innerText = class_mate.month_last_day;

        //반복시 보강일에는 날짜를 적용하지 않음.
        if (!is_again && class_mate.ref_date) {
            let ref_date = class_mate.ref_date || '0000-00-00';
            if (ref_date != '0000-00-00') {
                row.querySelector('[data-reinforcement-date]').value = ref_date;
                row.querySelector('[data-date]').innerText = new Date(ref_date).format('yy.MM.dd');
            }
        }

        // 수강 관련 완료
        // 수강이 있을때만
        if(complete_cnts[class_mate.student_seq] || incomplete_cnts[class_mate.student_seq]){
            // 한개라도 미 완료 수강이 있으면,
            if(incomplete_cnts[class_mate.student_seq]){
                // 미완료
            }else{
                // 미완료가 없으면서, 완료 수강이 있으면,
                if(complete_cnts[class_mate.student_seq]){
                    // 완료
                    row.querySelector('[data-btn-study-status]').innerText = '완료';
                    row.querySelector('[data-btn-study-status]').classList.add('bg-success');
                    row.querySelector('[data-btn-study-status]').classList.remove('bg-danger');
                }
            }
        }
        // 없으면
        else{
            row.querySelector('[data-btn-study-status]').remove();
        }

        // 오답노트 완료 있으면,
        if(inwrong_cnts[class_mate.student_seq]){
            const inwrong_cnt = inwrong_cnts[class_mate.student_seq][0];
            // 오답있지만 모두 점답처리 이 0인경우.(다했거나, 오답이 없을 경우)
            if(inwrong_cnt.wrong_count == 0){
                row.querySelector('[data-btn-no-answer-note-status]').innerText = '완료';
                row.querySelector('[data-btn-no-answer-note-status]').classList.add('bg-success');
                row.querySelector('[data-btn-no-answer-note-status]').classList.remove('bg-danger');
            }else{
                row.querySelector('[data-btn-no-answer-note-status]').innerText = '미완료';
            }
        }
        // 없으면
        else{
            row.querySelector('[data-btn-no-answer-note-status]').remove();
        }
        bundle.appendChild(row);
    });
}


// 출석체크 버튼 클릭.
function classStartAttendBtnClick() {
    // 모달 학생리스트 불러오기.
    classStartStudentSelect('modal');

    const myModal = new bootstrap.Modal(document.getElementById('class_start_modal_attend'), {
        keyboard: false,
        backdrop: 'static'
    });
    myModal.show();
}

// 수업종료 버튼 클릭.
function classStartEndClassBtnClick() {
    // 현재 출석 중인 학생 모두 text-sb-28px
    // 수엊봉료 상태로 변경하시겠습니까? text-danter
    // (종료시 학부모님께 알림톡이 전송됩니다.) m-20px scale-text-gray_05
    const msg =
        `
        <div class="text-sb-28px">현재 출석 중인 학생 모두</div>
        <div class="text-sb-28px text-danger mt-2">수업종료 상태로 변경하시겠습니까?</div>
        <div class="text-m-20px scale-text-gray_05 mt-3">(종료시 학부모님께 알림톡이 전송됩니다.)</div>
        `;
    sAlert('', msg, 3, function() {

    }, null, '네', '아니오');
}

// 만든날짜 선택
function classStartDateTimeSel(vthis) {
    //datetime-local format yyyy.MM.dd HH:mm 변경
    const date = new Date(vthis.value);
    if (vthis.getAttribute('data-reinforcement-date') == '') {
        //선택하신 날짜로 보강일을 등록하시겠습니까?
        classStartAddReinforcementDate(vthis);
    } else {
        vthis.closest('[data-bundle-date]').querySelector('[data-date]').innerText = date.format('yy.MM.dd')
    }
}

// 출석 버튼 클릭
function classStartBtnDoAttendClick(vthis) {
    const tr = vthis.closest('tr');
    tr.querySelector('.chk').checked = true;
    classStartDoAttend(true);
}

// 출석 체크 확인해서 출석 처리.
function classStartDoAttend(is_pass) {
    // 체크있는지 확인.
    const modal = document.getElementById('class_start_modal_attend');
    const bundle = modal.querySelector('[data-bundle="tby_attend_student_list"]');
    const chks = bundle.querySelectorAll('[data-row="clone"] .chk:checked');
    if (chks.length < 1) {
        toast("선택된 학생이 없습니다.");
        return;
    }

    let student_seqs = [];
    chks.forEach(chk => {
        const tr = chk.closest('tr');
        const student_seq = tr.querySelector('[data-student-seq]').value;
        student_seqs.push(student_seq);
    });
    const class_seq = document.querySelector('input[data-main-class-seq]').value;
    const team_code = document.querySelector('input[data-main-team-code]').value;
    const class_name = document.querySelector('input[data-main-class-name]').value;
    const class_start_time = today_study_date.start_time;

    const page = '/teacher/main/after/class/student/attend';
    const parameter = {
        class_seq: class_seq,
        class_name: class_name,
        team_code: team_code,
        student_seqs: student_seqs,
        class_start_time: class_start_time
    };
    if (!is_pass) {
        // 선택 학생 출석 처리 하시겠습니까?
        const msg =
            `
            <div class="text-sb-28px">선택 학생 출석 처리 하시겠습니까?</div>
            `;
        sAlert('', msg, 3, function() {
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    toast('출석처리 되었습니다.');
                    classStartStudentSelect('modal');
                    classStartStudentSelect();
                }
            });
        }, null, '네', '아니오');
    } else {
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                toast('출석처리 되었습니다.');
                classStartStudentSelect('modal');
                classStartStudentSelect();
            }
        });
    }
}

// 출석 취소 버튼 클릭.
function classStartBtnCancelAttendClick(vthis) {
    const tr = vthis.closest('tr');
    const student_seq = tr.querySelector('[data-student-seq="modal"]').value;
    const class_seq = document.querySelector('input[data-main-class-seq]').value;
    const team_code = document.querySelector('input[data-main-team-code]').value;

    const page = '/teacher/main/after/class/student/attend/cancel';
    const parameter = {
        class_seq: class_seq,
        team_code: team_code,
        student_seq: student_seq
    };
    // 선택 학생 출석 취소 하시겠습니까?
    const msg =
        `
        <div class="text-sb-28px">선택 학생 출석 취소 하시겠습니까?</div>
        `;
    sAlert('', msg, 3, function() {
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                toast('출석취소 되었습니다.');
                classStartStudentSelect('modal');
                classStartStudentSelect();
            }
        });
    }, null, '네', '아니오');
}

// 모두 체크 / 모두 체크 해제
function classStartAllCheck(vthis) {
    const checked = vthis.checked;
    const modal = document.getElementById('class_start_modal_attend');
    const bundle = modal.querySelector('[data-bundle="tby_attend_student_list"]');
    const chks = bundle.querySelectorAll('.chk');
    chks.forEach(chk => {
        chk.checked = checked;
    });
}


// 결석 사유 넣기.
function classStartAbsentReson(vthis) {
    //vthis 있으면 개별, 없으면 일괄.
    const modal = document.getElementById('class_start_modal_attend');
    const class_seq = document.querySelector('input[data-main-class-seq]').value;
    const team_code = document.querySelector('input[data-main-team-code]').value;
    const absent_start_time = today_study_date.start_time;
    const absent_day = today_study_date.class_day;

    const student_seqs = [];
    let absent_reason = '';
    let msg = '';
    if (vthis) {
        const tr = vthis.closest('tr');
        const student_seq = tr.querySelector('[data-student-seq="modal"]').value;
        student_seqs.push(student_seq);
        absent_reason = tr.querySelector('[data-absent-reason]').value;
        // 선택 학생의 결석사유를 등록 하시겠습니까?
        msg =
            `
            <div class="text-sb-28px">선택 학생의 결석사유를 등록 하시겠습니까?</div>
            `;
    } else {
        const bundle = modal.querySelector('[data-bundle="tby_attend_student_list"]');
        const chks = bundle.querySelectorAll('[data-row="clone"] .chk:checked');
        if (chks.length < 1) {
            toast("선택된 학생이 없습니다.");
            return;
        }
        let first_student_name = '';
        chks.forEach(chk => {
            const tr = chk.closest('tr');
            if (first_student_name == '') first_student_name = tr.querySelector('[data-student-name="modal"]').innerText;
            const student_seq = tr.querySelector('[data-student-seq="modal"]').value;
            student_seqs.push(student_seq);
        });
        absent_reason = modal.querySelector('[data-top-absent-reason]').value;

        // [첫번째 선택 학생이름] 학생 외 00명 //글자색 검은색
        // 결석사유를 등록하시겠습니까? // 글자색 빨간색
        const student_cnt = student_seqs.length - 1 == 0 ? '' : `외 ${student_seqs.length - 1}명`;
        msg =
            `
            <div class="text-sb-28px">${first_student_name} 학생 ${student_cnt}</div>
            <div class="text-sb-28px text-danger mt-2 mb-4">결석사유를 등록하시겠습니까?</div>
            `;
    }

    const page = '/teacher/main/after/class/student/attend/absent/reason';
    const parameter = {
        class_seq: class_seq,
        team_code: team_code,
        student_seqs: student_seqs,
        absent_reason: absent_reason,
        absent_start_time: absent_start_time,
        absent_day: absent_day
    };


    sAlert('', msg, 3, function() {
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                toast('결석사유 등록 되었습니다.');
                classStartStudentSelect('modal');
                classStartStudentSelect();
                if (!vthis) modal.querySelector('[data-top-absent-reason]').value = '';
            }
        });
    }, function() {
            if (vthis) {
                vthis.value = '';
            } else {
                modal.querySelector('[data-top-absent-reason]').value = '';
            }
        }, '네', '아니오');
}

//선택하신 날짜로 보강일을 등록
function classStartAddReinforcementDate(vthis) {
    const tr = vthis.closest('tr');
    const student_seq = tr.querySelector('[data-student-seq="modal"]').value;
    const ref_date = vthis.value;
    const class_seq = document.querySelector('input[data-main-class-seq]').value;
    const team_code = document.querySelector('input[data-main-team-code]').value;

    //오늘 이전날은 선택할수 없습니다.
    if (new Date(ref_date) < new Date().setHours(0, 0, 0, 0)) {
        toast('오늘 이전날은 선택할수 없습니다.');
        vthis.value = '0000-00-00';
        return;
    }

    const page = '/teacher/main/after/class/student/reinforcement/date/insert';
    const parameter = {
        class_seq: class_seq,
        team_code: team_code,
        student_seq: student_seq,
        ref_date: ref_date
    };
    // 선택하신 날짜로 보강일을 등록하시겠습니까?
    const msg =
        `
        <div class="text-sb-28px">선택하신 날짜로 보강일을 등록하시겠습니까?</div>
        `;
    sAlert('', msg, 3, function() {
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                // vthis.closest('[data-bundle-date]').querySelector('[data-date]').innerText = ref_date;
                toast('보강일 등록 되었습니다.');
                classStartStudentSelect('modal');
                classStartStudentSelect();
            } else {
                toast('다시 시도 해주세요.');
            }
        });
    }, function() {
            vthis.value = '0000-00-00';
        });
}

// 페이지 :뒤로가기.
function classStartDetailBack() {
    sessionStorage.setItem('isBackNavigation', 'true');
    window.history.back();
}



function classStartReinforcementDateSelect() { // 금일 보강일정 리스트 불러오기.
    const class_seq = document.querySelector('input[data-main-class-seq]').value;
    const team_code = document.querySelector('input[data-main-team-code]').value;
    // 우선은 오늘 날짜를 넘기도록.
    const today = new Date().format('yyyy-MM-dd');

    const page = '/teacher/main/after/class/reinforcement/date/select';
    const parameter = {
        class_seq: class_seq,
        team_code: team_code,
        today: today
    };

    queryFetch(page, parameter, function(result) {
        if ((result.resultCode || '') == 'success') {
            //초기화
            const bundle = document.querySelector('[data-bundle="today_reinforcement"]');
            const copy_row = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(copy_row);

            // 리스트 추가.
            const refs = result.refs;
            refs.forEach(function(ref){
                const row = copy_row.cloneNode(true);
                row.hidden = false;
                row.setAttribute('data-row', 'clone');
                row.querySelector('[data-absent-seq]').value = ref.id;
                row.querySelector('[data-student-seq]').value = ref.student_seq;
                row.querySelector('[data-student-name]').innerText = ref.student_name;
                row.querySelector('[data-grade-name]').innerText = `(${ref.grade_name})`;
                row.querySelector('[data-absent-reason]').innerText = ref.absent_reason;
                row.querySelector('[data-absent-date]').innerText = ref.absent_date.substr(0, 10).replace(/\./gi, '-');
                row.querySelector('[data-absent-day]').innerText = `(${ref.absent_day})`;
                row.querySelector('[data-absent-start-time]').innerText = (ref.absent_start_time||'').substr(0, 5);
                //is_ref_complete 가 Y이면 보강완료 버튼 disabled 처리.
                if(ref.is_ref_complete && ref.is_ref_complete == 'Y'){
                    row.querySelector('[data-btn-ref-complete]').disabled = true;
                    row.querySelector('[data-btn-ref-complete]').classList.add('scale-text-gray_05');
                    row.querySelector('[data-btn-ref-complete]').classList.add('border');
                }

                bundle.appendChild(row);
            });
        } else {}
    });
}

// 보강 완료 버튼 클릭.
function classStartReinforcementDateComplete(vthis){
    // get student_seq, class_seq
    const row = vthis.closest('[data-row]');
    const student_seq = row.querySelector('[data-student-seq]').value;
    const class_seq = document.querySelector('input[data-main-class-seq]').value;
    const team_code = document.querySelector('input[data-main-team-code]').value;
    const absent_seq = row.querySelector('[data-absent-seq]').value;

    const page = '/teacher/main/after/class/reinforcement/date/complete';
    const parameter = {
        class_seq: class_seq,
        team_code: team_code,
        student_seq: student_seq,
        absent_seq: absent_seq
    };

    // 선택학생을 보강완료 처리 하시겠습니까?
    const msg =
        `
        <div class="text-sb-28px">선택 학생을 보강완료 처리 하시겠습니까?</div>
        `;
    sAlert('', msg, 3, function() {
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                toast('보강일 완료 처리 되었습니다.');
                classStartReinforcementDateSelect();
            } else {
                toast('다시 시도 해주세요.');
            }
        });
    });
}

// 알림센터 더보기.
function classStartMoveAlarm(){
    //teacher/push/list 로 이동
    //알림센터로 이동하시겠습니까?
    const msg =
        `
        <div class="text-sb-28px">알림센터로 이동하시겠습니까?</div>
        `;
    sAlert('', msg, 3, function() {
        location.href = '/teacher/push/list';
    });
}

function calssStartAlarmListSelect(){
    const class_seq = document.querySelector('input[data-main-class-seq]').value;
    const team_code = document.querySelector('input[data-main-team-code]').value;
    const page = '/teacher/push/select';
    const parameter = {
        send_class_seq: class_seq,
        team_code: team_code,
        page_max: 5,
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            //초기화
            const bundle = document.querySelector('[data-bundle="today_alams"]');
            const copy_row = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(copy_row);

            const pushes = result.pushes.data;
            pushes.forEach(function(push){
                const row = copy_row.cloneNode(true);
                row.hidden = false;
                row.setAttribute('data-row', 'clone');
                row.querySelector('[data-push-seq]').value = push.id;
                row.querySelector('[data-student-name]').innerText = push.student_name;
                row.querySelector('[data-grade-name]').innerText = `(${push.grade_name})`;
                row.querySelector('[data-push-title]').innerText = push.push_title;
                // row.querySelector('[data-push-content]').innerText = push.;
                row.querySelector('[data-created-at]').innerText = push.created_at;
                // is_read 가 Y가 아니면 data-new 보이게.)
                row.querySelector('[data-new]').hidden = push.is_read == 'Y';
                bundle.appendChild(row);
            });
        }
    });
}
// 오늘이 수업이 아니면 출석, 수업관련 버튼 삭제.
function classStartIsTodayClass(class_info){
    //출석체크, 수업종료 버튼 삭제.
    // 학습신호등에 잇는 더보기 버튼 삭제.
    if(!class_info){
        document.querySelector('[data-btn-attend-chk]').remove();
        document.querySelector('[data-btn-end-class]').remove();
        document.querySelector('[data-btn-more-show-attend-chk]').remove();
    }
}

// 상단 오늘의 현황 카운트 가져오기.
// 학습완료, 미수강, 오답노트 미완료, 결석.
function classStartTodayStatusCount(){
    const class_seq = document.querySelector('input[data-main-class-seq]').value;
    const team_code = document.querySelector('input[data-main-team-code]').value;

    const page = '/teacher/main/after/class/today/status/count';
    const parameter = {
        class_seq: class_seq,
        team_code: team_code
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            const status = result.status;
            document.querySelector('[data-attend-no-cnt]').innerText = result.absent_cnt;
        }else{

        }
    });
}

// 수업관리 페이지로 이동.
function classStartManagementPage(){
    const msg =
        `
        <div class="text-sb-28px">수업관리로 이동하시겠습니까?</div>
        `;
    sAlert('', msg, 3, function() {
        location.href = '/teacher/after/class/management';
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
// 각각의 횟수 가져오기.(학습완료, 미수강, 오답노트 미완료)
function getEachCnt(data, student_seq){
    let cnt = 0;
    if(!data) return cnt;
    if(student_seq && data[student_seq]){
        cnt ++;
    }
    else{
        const data_keys = Object.keys(data);
        cnt += data_keys.length;
        return cnt;
    }
}
</script>
@endsection
