
@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title')
    학습관리
@endsection

@section('add_css_js')
    <!-- <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script> -->
@endsection

{{-- 컨텐트 --}}
@section('layout_coutent')
<!-- TODO: 1.저장후 알림톡 발송 알림톡 아직 안됨.  -->
<!-- TODO: 학습일지 > 수강정보를 가져올때, 선생님의 seq조건이 주석처리 되어있음. 이부분 관리자에서 학습만들때, 선생님을 선택해서 진행하도록. -->
<!-- WARN: 4.  -->

<style>
[data-section-learning-log] ul
 {
  list-style: disc;
}

</style>
<div class="row pt-2 zoom_sm" data-div-main="after_learning_management_detail">
    <input type="hidden" data-main-teach-seq value="{{ $teach_seq }}">
    <input type="hidden" data-main-team-code  value="{{ $team_code }}">
    <input type="hidden" data-main-student-seq value="{{ $student->id }}">
    <input type="hidden" data-main-class-start-time value="{{ $class_start_time }}">

    {{-- :뒤로가기 타이틀  --}}
    <div class="sub-title" data-sub-title="back" >
        <h2 class="text-sb-42px">
            <button data-btn-back-page class="btn p-0 row mx-0 all-center" onclick="afterLenDetailDetailBack();">
                <img src="{{ asset('images/black_arrow_left_tail.svg') }}" width="52" class="px-0">
            </button>
            <span class="me-2" data-title-student-name>학습관리</span>

        </h2>
    </div>

    <div class="row mx-0 col-12" >
        <!-- ==================================================================================================== -->
         {{-- 왼쪽 사이드 리스트 --}}
        <aside class="col-3" data-aside='main_aside'>
            <div class="rounded-4 modal-shadow-style">
                <ul class="tab py-4 px-3 ">
                    <li class="mb-2">
                        <button onclick="afterLenDetailAsideTab(this)" data-btn-main-tab="1"
                            class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover active">
                            <img src="{{ asset('images/window_pen_icon2.svg') }}" width="32" class="me-2">
                           상담 일지
                        </button>
                    </li>
                    <li class="mb-2">
                        <button onclick="afterLenDetailAsideTab(this)" data-btn-main-tab="2"
                            class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                            <img src="{{ asset('images/window_w_icon.svg') }}" width="32" class="me-2">
                            주간 현황
                        </button>
                    </li>
                    <li class="">
                        <button onclick="afterLenDetailAsideTab(this)" data-btn-main-tab="3"
                            class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                            <img src="{{ asset('images/window_scope_icon.svg?1') }}" width="32" class="me-2">
                            평가 현황
                        </button>
                    </li>
                    <li class="">
                        <button onclick="afterLenDetailAsideTab(this)" data-btn-main-tab="4"
                            class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                            <img src="{{ asset('images/window_chk_icon.svg') }}" width="32" class="me-2">
                            출결 현황
                        </button>
                    </li>
                </ul>
            </div>

            {{-- 하단 년월/주차 --}}
            <div class="shadow-sm-2 p-2 p-xl-4 mt-4 rounded-2" data-div-my-study-aside-sub="week" hidden>
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
                <div class="row row-cols-2 row-cols-lg-2 row-cols-xl-2 row-cols-xxl-3 div_week_bundle mt-4">
                    <div class="col div_week_row p-0 pe-1" hidden>
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
            <div class="modal-shadow-style p-4 mt-4" data-div-aside-sub="month" hidden>
                <div class="row align-items-center justify-content-between mb-2">
                    <button class="btn col-auto" onclick="attendYearChange('prev')">
                        <img src="{{ asset('images/calendar_arrow_left.svg') }}" width="32">
                    </button>
                    <span id="my_study_span_year" class="align-middle col text-center cfs-5 fw-semibold"
                        data="{{ date('Y-m-d') }}">
                        {{ date('Y년') }}
                    </span>
                    <button class="btn col-auto" onclick="attendYearChange('next')">
                        <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="32">
                    </button>
                </div>
                <div class="row row-cols-3 div_month_bundle mt-4">
                    <div class="col div_month_row p-0 pe-1" hidden>
                        <button class="btn btn-outline-primary-y ctext-gc1 border-0 rounded-pill cfs-5 p-2 px-4"
                            onclick="attendMonthBtnClick(this)">
                            <div class="p-1">
                                <span class="month_cnt">n</span>
                                <span>월</span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <div data-main-div="1" class="col">
            {{-- 학생정보 --}}
            <section class="col">
                <div class="text-b-28px mb-4 row mx-0">
                    <div class="col-auto">
                        <span> 학생 정보 </span>
                    </div>

                    <div class="d-flex ms-3 col justify-content-end">
                        <div class="d-inline-block select-wrap select-icon me-12">
                            <select data-select-search-type="1"
                                class="rounded-pill border-gray lg-select text-sb-20px">
                                <option value="id">아이디</option>
                            </select>
                        </div>
                        <label class="label-search-wrap">
                            <input type="text" data-inp-search-str="1"
                                onkeyup="if(event.keyCode == 13) afterLenDetailStudentSelect();"
                                class="ms-search border-gray rounded-pill text-m-20px w-100" placeholder="아이디를 검색해보세요.">
                        </label>
                    </div>
                </div>
                <div style="border-top: solid 2px #222;" class="w-100"></div>
                <table data-tb="0" class="w-100 table-list-style table-border-xless table-h-92" style="border-top:0px;"> <colgroup> <col style="width: 15%;">
                        <col style="width: 35%;">
                        <col style="width: 15%;">
                        <col style="width: 35%;">
                    </colgroup>
                    <tbody>
                        <tr class="text-start ">

                            <td class="text-start ps-4 scale-text-gray_06">학교</td>
                            <td class="text-start px-4">
                                <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                                    {{ $student->school_name }}
                                </div>
                            </td>

                            <td class="text-start ps-4 scale-text-gray_06">학년 / 반</td>
                            <td class="text-start px-4">
                                <div class="h-center justify-content-between gap-2">
                                    <div data-ori="student_grade"
                                        class="row select-wrap py-0 w-100 position-relative">
                                        {{ $student->grade_name }} / {{ $student->class_name }}
                                    </div>
                                    <select data-modify-editor="student_grade" hidden
                                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3 col">
                                        @if(!empty($grade_codes))
                                        @foreach($grade_codes as $grade_code)
                                        <option value="{{ $grade_code->id }}" {{ $grade_code->code_name == $student->grade_name ? 'selected':'' }}>
                                            {{ $grade_code->code_name}}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </td>
                        </tr>

                        <tr class="text-start ">

                            <td class="text-start text-start ps-4 scale-text-gray_06">이름</td>
                            <td class="text-start text-start p-4" colspan="3">
                                <div class="h-center justify-content-between gap-2">
                                    <div data-text="" data-ori="student_name"
                                        class="row align-items-center scale-text-gray_05 is_content">
                                        {{ $student->student_name }} ({{ $student->student_id }})
                                    </div>
                                    <div class="col-auto">
                                        <!-- 출석하기, 학습플래너 수정 -->
                                    <button type="button" onclick="afterLenDetailDoAttend()"
                                        class="btn-line-xss-secondary text-sb-20px  rounded studyColor-bg-goalTime border-none text-white px-3 col-auto">출석하기</button>
                                    <button type="button" onclick="afterLenDetailMovePage();"
                                        class="btn-line-xss-secondary text-sb-20px border rounded scale-bg-white scale-text-black px-3 col-auto">학습플래너 수정</button>
                                    </div>
                                </div>
                            </td>

                        </tr>

                        <tr>

                            {{-- 휴대전화 --}}
                            <td class="text-start text-start ps-4 scale-text-gray_06">휴대전화</td>
                            <td class="text-start text-start p-4">
                                <div class="h-center justify-content-between gap-2">
                                    <div data-student-phone data-text="" data-ori="student_phone"
                                        class="row align-items-center scale-text-gray_05 is_content">
                                        {{ $student->student_phone }}
                                    </div>
                                    <input data-modify-editor="student_phone" hidden
                                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3 col" value="{{ $student->student_phone }}">
                                    <button type="button" onclick="teachStAfDtailModifyToggle(this)" hidden data-modify
                                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 col-auto">연락처 수정</button>
                                </div>
                            </td>

                            {{-- 학부모 연락처(1) --}}
                            <td class=" text-start ps-4 scale-text-gray_06">학부모 전화</td>
                            <td class=" text-start px-4 py-4">
                                <div class="h-center justify-content-between">
                                    <div data-text=""
                                        class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3">
                                        <span data-parent-phone> {{ $parent ? $parent->parent_phone : ''}} </span>
                                        <span class="text-sb-24px text-primary" {{ !empty($parent) && $parent->is_auth_phone == 'Y' ? '':'hidden' }} >(인증됨)</span>
                                    </div>
                                    <div {{!empty($parent) && $parent->is_auth_phone == 'Y' ? '':'hidden'}}>
                                        <button type="button" onclick="afterLenDetailSmsModalOpen()" style="width:110px" onclick="afterLenDetailSmsModalOpen();"
                                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 all-center">문자발송</button>
                                    </div>
                                </div>
                            </td>

                        </tr>

                        <tr>

                            <!-- <td class=" text-start ps-4 scale-text-gray_06">수업명/기간</td> -->
                            <td class=" text-start ps-4 scale-text-gray_06">수업명</td>
                            <td class=" text-start px-4 py-4">
                                @if(!empty($class_infos))
                                    @foreach( $class_infos as $info)
                                    <div>
                                        <span>
                                            {{ $info->class_name }}
                                        </span>
                                        <input type="hidden" data-main-class-seq value="{{ $info->class_seq }}">
                                        <input type="hidden" data-main-class-name value="{{ $info->class_name }}">
                                    </div>
                                    <div hidden>
                                        <span>{{ $info->start_date}}</span> ~
                                        <span>{{ $info->end_date}}</span>
                                    </div>
                                    @endforeach
                                @endif
                            </td>

                            <td class=" text-start ps-4 scale-text-gray_06">요일</td>
                            <td class=" text-start px-4 py-4">
                                @if(!empty($class_infos))
                                    @foreach( $class_infos as $info)
                                        <div>
                                            <span>
                                                매주 {{ $info->class_days}}
                                            </span>
                                        </div>
                                    @endforeach
                                @endif
                            </td>
                        </tr>

                    </tbody>
                </table>
            </section>
            {{--학습일지  --}}
            <section class="col mt-5" data-section-learning-log>
                <div class="text-b-28px mb-4 row mx-0 justify-content-between">
                    <div class="col-auto" >
                        <span>학습일지</span>
                    </div>

                    <div class="col-auto">
                        <div class="btn-line-ms-secondary bg-white scale-text-gray_05 border rounded-pill h-center px-2">
                            <button class="btn h-center" onclick="afterLenDetailSetSearchDate('down')">
                                <img src="{{asset('images/calendar_arrow_right.svg')}}" width="32" class="rotate-180">
                            </button>

                            <div data-bundle-date class="overflow-hidden col-auto cursor-pointer text-start"
                                style="height: 20px;">
                                <div class="h-center justify-content-between">
                                    <div data-date
                                        onclick="this.closest('[data-bundle-date]').querySelector('input').showPicker()"
                                        type="text" class="text-m-20px text-start scale-text-gray_05" readonly=""
                                        placeholder="">
                                        {{-- 상담시작일시 --}}
                                        오늘 {{ date('y.m.d') }}
                                    </div>
                                    <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                                </div>
                                <input type="date" style="width: 80px;height: 0.5px;" data-search-start-date
                                    oninput="afterLenDetailDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                            </div>

                            <button class="btn h-center" onclick="afterLenDetailSetSearchDate('up')">
                                <img src="{{asset('images/calendar_arrow_right.svg')}}" width="32">
                            </button>
                        </div>
                    </div>
                </div>
                <div style="border-top: solid 2px #222;" class="w-100"></div>
                <input type="hidden" data-learning-log-seq  >
                <input type="hidden" data-is-temp  >
                <table data-tb="0" class="w-100 table-list-style table-border-xless table-h-92" style="border-top:0px;">
                    <colgroup>
                        <col style="width: 20%;">
                        <col style="width: 80%;">
                    </colgroup>
                    <tbody>
                        <tr class="text-start ">

                            <td class="text-start ps-4 scale-text-gray_06">학습 일지</td>
                            <td class="text-start px-4">
                                <div class="h-center justify-content-between gap-2">
                                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative text-sb-24px">
                                        <span data-last-lecture-date> </span>
                                        <span data-last-lecture-day class="me-2"> </span>
                                         -
                                        <span data-yesterday-date class="ms-2"> </span>
                                        <span data-yesterday-day> </span>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" data-modify onclick="afterLenDetailModalLectureList('incomplete');";
                                            class="btn-line-xss-secondary text-sb-20px border rounded scale-bg-white scale-text-black px-3 col-auto">미수강 <span class="ms-2" data-incomplete-cnt>0</span>강</button>
                                        <button type="button" data-modify onclick="afterLenDetailModalLectureList('inwrong');";
                                            class="btn-line-xss-secondary text-sb-20px border rounded scale-bg-white scale-text-black px-3 col-auto">오답 미완료 <span class="ms-2" data-inwrong-cnt>0</span>개</button>
                                        <button type="button" data-modify onclick="afterLenDetailModalLectureList('exam');";
                                            class="btn-line-xss-secondary text-sb-20px border rounded scale-bg-white scale-text-black px-3 col-auto">문제 풀이</button>
                                    </div>
                                </div>
                            </td    const page = "/teacher/after/learning/management/detail/select";
                        </tr>
                        <!-- : 수업내용 내용 / 데이터 잇기.  -->
                        <tr class="text-start ">
                            <td class="text-start ps-4 scale-text-gray_06">수업내용</td>
                            <td class="text-start px-4">
                                <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative px-4">
                                    <ul data-file-att-bundle >
                                        <li data-row="copy" class="text-sb-20px scale-text-gray_06 mt-3" hidden>
                                            <input type="hidden" data-student-lecture-detail-seq>
                                            <span data-lecture-info> </span>
                                                <span data-hidden-exam-info hidden>
                                                    <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                                                    <span data-exam-cnt-info> </span>
                                                </span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mt-3">
                                    <input type="text" class="form-control fs-5" data-class-contents
                                        placeholder="수업 내용을 입력해주세요." style="height: 60px;">
                                </div>
                            </td>
                        </tr>
                        <!-- 목표 학습 / 체크박스 완료(수강 완료)  -->
                        <!--             체크박스 수업 내 완료(미수강 > 수강완료) -->
                        <!--             체크박스 정학습 요함(미수강) -->
                        <tr class="text-start ">
                            <td class="text-start ps-4 scale-text-gray_06">목표 학습</td>
                            <td class="text-start px-4 scale-text-black py-4">
                                <div>
                                    <label class="checkbox mt-1">
                                        <input type="checkbox" class="chk" onchange="" data-goal-learning="complete">
                                        <span class="">
                                        </span>
                                    </label>
                                    <span>완료(수강 완료)</span>
                                </div>
                                <div>
                                    <label class="checkbox mt-1">
                                        <input type="checkbox" class="chk" onchange="" data-goal-learning="completed_in_class">
                                        <span class="">
                                        </span>
                                    </label>
                                    <span>수업내 완료(미수강 > 수강완료)</span>
                                </div>
                                <div>
                                    <label class="checkbox mt-1">
                                        <input type="checkbox" class="chk" onchange="" data-goal-learning="home_study_required">
                                        <span class="">
                                        </span>
                                    </label>
                                    <span>가정 학습 요함(미수강)</span>
                                </div>
                            </td>
                        </tr>
                        <!-- 오답 노트 / 체크박스 완료 , 체크박스 수업 내 완료, 체크박스 가정학습 요함 -->
                        <tr class="text-start ">
                            <td class="text-start ps-4 scale-text-gray_06">오답 노트</td>
                            <td class="text-start px-4 scale-text-black">
                                <div class="h-center gap-4">
                                    <div>
                                        <label class="checkbox mt-1">
                                            <input type="checkbox" class="chk" onchange="" data-error-note="complete">
                                            <span class="">
                                            </span>
                                        </label>
                                        <span>완료(수강 완료)</span>
                                    </div>
                                    <div>
                                        <label class="checkbox mt-1">
                                            <input type="checkbox" class="chk" onchange="" data-error-note="completed_in_class">
                                            <span class="">
                                            </span>
                                        </label>
                                        <span>수업내 완료(미수강 > 수강완료)</span>
                                    </div>
                                    <div>
                                        <label class="checkbox mt-1">
                                            <input type="checkbox" class="chk" onchange="" data-error-note="home_study_required">
                                            <span class="">
                                            </span>
                                        </label>
                                        <span>가정 학습 요함</span>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- 문제풀이 / 체크박스 완료, 체크박스 수업 내 완료, 체크박스 가정학습 요함  -->
                        <tr class="text-start ">
                            <td class="text-start ps-4 scale-text-gray_06">문제 풀이</td>
                            <td class="text-start px-4 scale-text-black">
                                <div class="h-center gap-4">
                                    <div>
                                        <label class="checkbox mt-1">
                                            <input type="checkbox" class="chk" onchange="" data-question-solving="complete">
                                            <span class="">
                                            </span>
                                        </label>
                                        <span>완료(수강 완료)</span>
                                    </div>
                                    <div>
                                        <label class="checkbox mt-1">
                                            <input type="checkbox" class="chk" onchange="" data-question-solving="completed_in_class">
                                            <span class="">
                                            </span>
                                        </label>
                                        <span>수업내 완료(미수강 > 수강완료)</span>
                                    </div>
                                    <div>
                                        <label class="checkbox mt-1">
                                            <input type="checkbox" class="chk" onchange="" data-question-solving="home_study_required">
                                            <span class="">
                                            </span>
                                        </label>
                                        <span>가정 학습 요함</span>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- 기타 특이사항 / 여기에 추가로 학부모님께 전하는 말을 입력해 주세요. -->
                        <tr class="text-start ">
                            <td class="text-start ps-4 scale-text-gray_06">기타 특이사항</td>
                            <td class="text-start px-4">
                                <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                                        <textarea data-etc-contents
                                            class="form-control text-sb-20px"  placeholder="여기에 추가로 학부모님께 전하는 말을 입력해 주세요."></textarea>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>
            <section class="row mx-0 justify-content-end mt-4">
                <button type="button" onclick="afterLenDetailLearningLogInsert(true)" style="width:170px" data-btn-temp
                    class="btn-line-ms-secondary text-sb-20px rounded-pill border-gray scale-bg-white scale-text-gray_05 border w-center">임시저장</button>

                <button data-btn-save type="button" onclick="afterLenDetailLearningLogInsert(false)" data-btn-save
                    class="col-auto btn-line-ms-secondary text-sb-20px rounded-pill bg-primary-y text-white border w-center ms-3">저장 후 알림톡 발송</button>
            </section>
        </div>

        <div data-main-div="2" class="col" hidden>
            {{-- 주간 현황 / (월) --}}
            @include('student.student_my_study_week_learning')
        </div>
        <div data-main-div="3" class="col" hidden>
            {{-- 평가 현황 --}}
            @include('teacher.teacher_evaluation_status')
        </div>
        <div data-main-div="4" class="col" hidden>
            {{-- 출결 현황 --}}
            @include('teacher.teacher_attendance_status')
        </div>

    </div>


    <div data-explain="160px">
        <div class="py-lg-5"></div>
        <div class="py-lg-4"></div>
        <div class="pt-lg-3"></div>
    </div>

</div>

{{--  모달 / 미수강, 오답 미완료, 문제풀이 --}}
<div class="modal fade zoom_sm" id="modal_lecture_chk" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog rounded modal-lg" >
        <div class="modal-content border-none rounded p-3 modal-shadow-style">
            <div class="modal-header border-bottom-0">
                <h1 class="modal-title fs-5 text-b-24px" id="">
                    미수강
                </h1>
                <button type="button" style="width:32px;height:32px"
                    class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div data-bundle="lecture_list">
                    <div data-row="copy" class="h-center p-3 border rounded-3 mb-3" hidden onclick="event.stopPropagation();this.querySelector('input[type=checkbox]').click();">
                        <input type="hidden" data-student-lecture-detail-seq>
                        <label class="checkbox mt-1" onclick="event.stopPropagation();">
                                <input type="checkbox" class="chk" onchange="" data-goal-learning="complete" onclick="event.stopPropagation();">
                            <span class="">
                            </span>
                        </label>
                        <span class="text-sb-20px ms-2">
                            <span data-lecture-name data-explain="국어 3-2"></span>
                        </span>
                        <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                        <span class="text-sb-20px" data-lecture-detail-description data-explain="[8단원]"></span>
                        <span class="text-sb-20px" data-lecture-detail-name data-explain="의견이 있어요."></span>
                    </div>
                </div>
                <button data-btn-save type="button" onclick="afterLenDetailLectureAddToLog();"
                    class="col-auto btn-line-ms-secondary text-sb-20px rounded-3 mt-5 bg-primary-y text-white border w-center w-100">수업 내용 등록</button>
            </div>
        </div>
  </div>
</div>

{{-- 모달 / 상담일정 알림 발송 / 여기 안에 select_member 배열 있으므로, 확인 --}}
@include('admin.admin_alarm_detail')

<!-- 학습플래너이동  -->
<form action="/manage/learning" method="post" data-form-learningplan  target="_self">
    @csrf
    <input type="hidden" name="student_seq" data-form-student-seq>
</form>


<!-- 학습관리 상세페이지 이동 -->
<form action="/teacher/after/learning/management/detail" method="post" data-form-learningdetail target="_self">
    @csrf
    <input type="hidden" name="student_seq" data-form-student-seq>
</form>


<script>
//ready
myStudyMakeWeekList();
document.addEventListener("DOMContentLoaded", function() {
    // data-learning-log-seq
    const log_seq = document.querySelector('[data-learning-log-seq]').value;
    if(log_seq){
        afterLenDetailStatusBtn(false)
    }
    // 학습일지 불러오기.
    afterLenDetailGetLearningLogSelect();

    // 주간현황 > 주차 만들기.
    // myStudyMakeWeekList();
});

// 뒤로가기
function afterLenDetailDetailBack(){
    sessionStorage.setItem('isBackNavigation', 'true');
    window.history.back();
}

//선택 학생 일괄 춣석
function afterLenDetailDoAttend(is_pass) {
    const student_seq = document.querySelector('[data-main-student-seq]').value;
    const team_code = document.querySelector('[data-main-team-code]').value;
    // 중복대처를 해서 불러왔지만 1=1로 class = student 하는것으로
    // 우선은 첫번째 태그 값을 가져오는 것으로 진행.
    const class_seq = document.querySelector('[data-main-class-seq]')?.value||'';
    const class_name = document.querySelector('[data-main-class-name]')?.value||'';
    const class_start_time = document.querySelector('[data-main-class-start-time]').value;

    //class_seq가 없으면 리턴.
    if( !class_seq ){
        toast("수업정보가 없습니다.");
        return;
    }

    const users = [];
    users.push({
        student_seq: student_seq,
        class_seq: class_seq,
        team_code: team_code,
        class_name: class_name,
        class_start_time: class_start_time
    })

    const page = '/teacher/main/after/class/student/attend';
    const parameter = {
        users:users
    };
    // 선택 학생 출석 처리 하시겠습니까?
    const msg =
        ` <div class="text-sb-28px">선택 학생 출석 처리 하시겠습니까?</div>`;
    sAlert('', msg, 3, function() {
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                toast('출석처리 되었습니다.');
            }
        });
    }, null, '네', '아니오');
}


// 페이지 이동 함수
function afterLenDetailMovePage(){
    const student_seq = document.querySelector('[data-main-student-seq]').value;

    // 학습플래너로 이동.
    const form2 = document.querySelector('[data-form-learningplan]');
    form2.querySelector('[data-form-student-seq]').value = student_seq;
    form2.submit();
}

// 상단 학생정보에서 검색기능.
function afterLenDetailStudentSelect(){
    const search_type = document.querySelector('[data-select-search-type]').value;
    const search_str = document.querySelector('[data-inp-search-str]').value;

    const page = "/manage/userlist/student/select";
    const parameter = {
        'search_type': search_type,
        'search_keyword': search_str
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            const students = result.resultData;
            if(students.length > 0){
                // 학생이 있는 것으로 간주하고 이동
                const student_seq = students[0].id;
                const form = document.querySelector('[data-form-learningdetail]');
                form.querySelector('[data-form-student-seq]').value = student_seq;
                form.submit();
            }else{
                toast("학생이 존재하지 않습니다.");
            }
        }
    });

}


// 만든날짜 선택
function afterLenDetailDateTimeSel(vthis) {
    //datetime-local format yyyy.MM.dd HH:mm 변경
    const date = new Date(vthis.value);
    //date가 오늘이면 앞에 오늘 표기.
    if(date.format('yyyy-MM-dd') == new Date().format('yyyy-MM-dd')){
        vthis.closest('[data-bundle-date]').querySelector('[data-date]').innerText = '오늘 '+date.format('yy.MM.dd')
    }else{
        vthis.closest('[data-bundle-date]').querySelector('[data-date]').innerText = date.format('yy.MM.dd')
    }
    // 날짜가 변경이 되면 학습일지 불러오기.
    afterLenDetailGetLearningLogSelect();
}

// 학습일지 날짜 변경
function afterLenDetailSetSearchDate(type){
    const search_date_el = document.querySelector('[data-search-start-date]');
    const search_date = new Date(search_date_el.value);
    if(type == 'up'){
        // +1일
        search_date.setDate(search_date.getDate() + 1);
    }
    else if(type == 'down'){
        // -1일
        search_date.setDate(search_date.getDate() - 1);
    }
    search_date_el.value = search_date.format('yyyy-MM-dd');
    search_date_el.oninput();
}

//  학습일지 날짜에 따라 불러오기. / 수업내용 정보 잇기.
let student_lecture_details_data = null;
let student_exam_result_data = null;
let incorrect_answer_completed = []; // 오답 미완료
let not_taking_class = []; // 미수강
let exam_solving = [];  // 문제풀이
function afterLenDetailGetLearningLogSelect(){
    afterLenDetailLearningLogClear();
    const student_seq = document.querySelector('[data-main-student-seq]').value;
    const search_date = document.querySelector('[data-search-start-date]').value;
    const class_seqs = document.querySelector('[data-main-class-seq]').value;

    const log_seq_el = document.querySelector('[data-learning-log-seq]');
    const is_temp_el = document.querySelector('[data-is-temp]');
    log_seq_el.value = '';
    is_temp_el.value = '';

    const page = "/teacher/after/learning/management/detail/select";
    const parameter = {
        student_seq: student_seq,
        log_date: search_date,
        class_seqs: class_seqs

    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            const  learning_logs = result.learning_logs;
            const learning_log_details = result.learning_log_details;
            const yesterday_date = result.yesterday_date;
            const yesterday_day = result.yesterday_day;
            const last_lecture_date = result.last_lecture_date;
            const last_lecture_day = result.last_lecture_day;
            const sl_details = result.student_lecture_details;
            student_lecture_details_data = sl_details;
            const inwrongs = result.inwrongs;
            student_exam_result_data = inwrongs;

            // 학습일지 일자 넣기
            if(last_lecture_date){
                document.querySelector('[data-last-lecture-date]').innerText = last_lecture_date.substr(2,8).replace(/-/g, '.');
                document.querySelector('[data-last-lecture-day]').innerText = `(${last_lecture_day})`;
            }
            if(yesterday_day){
                document.querySelector('[data-yesterday-date]').innerText = yesterday_date.substr(2,8).replace(/-/g, '.');
                document.querySelector('[data-yesterday-day]').innerText = `(${yesterday_day})`;
            }

            let incomplete_cnt = 0;
            let inwrong_cnt = 0;
            if(sl_details){
                sl_details.forEach(function(sl){
                    if(sl.status != 'complete'){
                        incomplete_cnt++;
                        not_taking_class.push(sl.id);
                    }
                });
                document.querySelector('[data-incomplete-cnt]').innerText = incomplete_cnt;
            }
            if(inwrongs){
                const keys = Object.keys(inwrongs);
                keys.forEach(function(key){
                    const inwrong = inwrongs[key];
                    if(inwrong.is_complete =='Y' && inwrong.inwrong_cnt*1 > 0) {
                        inwrong_cnt++;
                        incorrect_answer_completed.push(inwrong.student_lecture_detail_seq);
                    }
                    exam_solving.push(inwrong.student_lecture_detail_seq);
                });

                document.querySelector('[data-inwrong-cnt]').innerText = inwrong_cnt;
            }

            if(learning_logs.length == 0) return;

            document.querySelector('[data-learning-log-seq]').value = learning_logs[0].id;
            document.querySelector('[data-is-temp]').value = learning_logs[0].is_temp;
            learning_log_details.forEach(function(detail){
                if(detail.log_type == 'class_exams'){
                    const class_exam_array = JSON.parse(detail.log_content);
                    const bundle = document.querySelector('[data-file-att-bundle]');
                    const row_copy = bundle.querySelector('[data-row]').cloneNode(true);
                    bundle.innerHTML = '';
                    bundle.appendChild(row_copy);

                    class_exam_array.forEach(function(exam){
                        const row = row_copy.cloneNode(true);
                        row.hidden = false;
                        row.dataset.row = 'clone';
                        row.querySelector('[data-student-lecture-detail-seq]').value = exam.student_lecture_detail_seq;
                        row.querySelector('[data-lecture-info]').innerText = exam.lecture_info;
                        if(exam.exam_cnt_info){
                            row.querySelector('[data-exam-cnt-info]').innerText = exam.exam_cnt_info;
                            row.querySelector('[data-hidden-exam-info]').hidden = false;
                        }
                        bundle.appendChild(row);
                    });

                }
                if(detail.log_type == 'class_contents'){
                    document.querySelector(`[data-class-contents]`).value = detail.log_content;
                }
                if(detail.log_type == 'goal_learnings'){
                    document.querySelector(`[data-goal-learning=${detail.log_content}]`).checked = true;
                }
                if(detail.log_type == 'error_notes'){
                    document.querySelector(`[data-error-note=${detail.log_content}]`).checked = true;
                }
                if(detail.log_type == 'question_solvings'){
                    document.querySelector(`[data-question-solving=${detail.log_content}]`).checked = true;
                }
                if(detail.log_type == 'etc_contents'){
                    document.querySelector(`[data-etc-contents]`).value = detail.log_content;
                }
            });

            if(learning_log_details.length > 0){
                afterLenDetailStatusBtn(false);
            }


        }else{}
    });
}


// 학습일지 저장.
function afterLenDetailLearningLogInsert(is_temp_){
    const student_seq = document.querySelector('[data-main-student-seq]').value;
    const log_seq = document.querySelector('[data-learning-log-seq]').value;
    const class_exams = document.querySelectorAll('[data-file-att-bundle] [data-row="clone"]');
    const class_contents = document.querySelector('[data-class-contents]').value;
    const is_temp = is_temp_ ? 'Y':'N';
    const log_date = document.querySelector('[data-search-start-date]').value;

    // log_form , log_type, log_content
    const learning_log_details = [];

    //수업 내용

    let class_exam_array = [];
    class_exams.forEach(function(tag){
        const exam_array = {};
        exam_array['student_lecture_detail_seq'] = tag.querySelector('[data-student-lecture-detail-seq]').value;
        exam_array['lecture_info'] = tag.querySelector('[data-lecture-info]').innerText;
        exam_array['exam_cnt_info'] = tag.querySelector('[data-exam-cnt-info]').innerText;
        class_exam_array.push(exam_array);
    });

    const insert0 = {log_form:'text', log_type:'class_exams', log_content:JSON.stringify(class_exam_array)};
    learning_log_details.push(insert0);
    const insert1 = {log_form:'text', log_type:'class_contents', log_content:class_contents};
    learning_log_details.push(insert1);

    // 목표 학습
    const goal_learnings = document.querySelectorAll('[data-goal-learning]');
    goal_learnings.forEach(function(el){
        if(el.checked){
            const insert = {log_form:'checkbox', log_type:'goal_learnings', log_content:el.dataset.goalLearning};
            learning_log_details.push(insert);
        }
    })

    // 오답 노트
    const error_notes = document.querySelectorAll('[data-error-note]');
    error_notes.forEach(function(el){
        if(el.checked){
            const insert = {log_form:'checkbox', log_type:'error_notes', log_content:el.dataset.errorNote};
            learning_log_details.push(insert);
        }
    })

    // 문제 풀이
    const question_solvings = document.querySelectorAll('[data-question-solving]');
    question_solvings.forEach(function(el){
        if(el.checked){
            const insert = {log_form:'checkbox', log_type:'question_solvings', log_content:el.dataset.questionSolving};
            learning_log_details.push(insert);
        }
    })

    // 기타 특이사항
    const etc_contents = document.querySelector('[data-etc-contents]').value;
    const insert2 = {log_form:'text', log_type:'etc_contents', log_content:etc_contents};
    learning_log_details.push(insert2);

    const page = "/teacher/after/learning/management/detail/insert"
    const parameter = {
        student_seq: student_seq,
        log_seq: log_seq,
        log_date: log_date,
        learning_log_details: learning_log_details,
        is_temp:is_temp
    }
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            toast('저장되었습니다.');
            // TODO: 저장 후 알림톡 발송

            document.querySelector('[data-is-temp]').value = is_temp;
            afterLenDetailStatusBtn(false);
        }
    });
}

// 임시저장, 저장 후 알림톡 발송 <> 임시저장 숨김처라, 수정 후 알림톡 발송문구 수정.
function afterLenDetailStatusBtn(is_temp){
    const btn_save_el = document.querySelector('[data-btn-save]');
    const btn_temp_el = document.querySelector('[data-btn-temp]');
    const is_temp_el = document.querySelector('[data-is-temp]');

    if(is_temp_el.value == 'Y') is_temp = true;

    if(is_temp){
        btn_save_el.innerHTML = '저장 후 알림톡 발송';
        btn_temp_el.hidden = false;
    }else{
        btn_save_el.innerHTML = '수정 후 알림톡 발송';
        btn_temp_el.hidden = true;
    }
}


// 학습일지의 내용 초기화.
function afterLenDetailLearningLogClear(){
    document.querySelector('[data-class-contents]').value = '';
    document.querySelector('[data-etc-contents]').value = '';
    document.querySelectorAll('input[type="checkbox"]').forEach(function(el){
        el.checked = false;
    });
    afterLenDetailStatusBtn(true);
}


// 학습관리의 옆칸
function afterLenDetailAsideTab(vthis){
    // 현재 누른 data-btn-main-tab의 값을 가져온다.
    const tabs = document.querySelectorAll('[data-btn-main-tab]');
    tabs.forEach(function(tab){
        tab.classList.remove('active');
    });
    vthis.classList.add('active');

    const tab_active = vthis.dataset.btnMainTab;
    if(tab_active){

        div_mains = document.querySelectorAll('[data-main-div]');
        div_mains.forEach(function(div_main){
            div_main.hidden = true;
        });
        const div_main = document.querySelector(`[data-main-div="${tab_active}"]`)
        div_main.hidden = false;

        if(tab_active == 4){
            document.querySelector('[data-div-aside-sub="month"]').hidden = false;
        }else
            document.querySelector('[data-div-aside-sub="month"]').hidden = true;
    }
}

// 주차 만들기.
function myStudyMakeWeekList() {
    const aside = document.querySelector('[data-aside="main_aside"]');
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

// 미수강, 오답미완료, 문제풀이 버튼 클릭시
function afterLenDetailModalLectureList(type){
    const sl_details = student_lecture_details_data;

    // 초기화
    const bundle = document.querySelector('[data-bundle="lecture_list"]');
    const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
    const modal = document.getElementById('modal_lecture_chk');
    const modal_title = modal.querySelector('.modal-title');
    bundle.innerHTML = '';
    bundle.appendChild(row_copy);

    sl_details.forEach(function(detail){
        const row = row_copy.cloneNode(true);
        row.hidden = false;
        row.dataset.row = 'clone';
        row.querySelector('[data-lecture-name]').innerText = detail.lecture_name;
        row.querySelector('[data-lecture-detail-description]').innerText = detail.lecture_detail_description;
        row.querySelector('[data-lecture-detail-name]').innerText = detail.lecture_detail_name;
        row.querySelector('[data-student-lecture-detail-seq]').value = detail.id;


        if(type == 'incomplete'){
            modal_title.innerText = '미수강';
            if(not_taking_class && not_taking_class.includes(detail.id)){
                bundle.appendChild(row);
            }
        }
        else if(type == 'inwrong'){
            modal_title.innerText = '오답 미완료';
            if(incorrect_answer_completed && incorrect_answer_completed.includes(detail.id)){
                bundle.appendChild(row);
            }
        }
        else if(type == 'exam'){
            modal_title.innerText = '문제 풀이';
            if(exam_solving && exam_solving.includes(detail.id)){
                bundle.appendChild(row);
            }
        }
    });
    const myModal = new bootstrap.Modal(document.getElementById('modal_lecture_chk'), {
        keyboard: false
    });
    myModal.show();
}

// 학습일지 수업내용 등록.
function afterLenDetailLectureAddToLog(){
    const sl_detail_seqs = [];
    const chk_sl_details = document.querySelectorAll('[data-bundle="lecture_list"] [data-row="clone"] input[type="checkbox"]:checked');
    // 초기화
    const bundle = document.querySelector('[data-file-att-bundle]');
    const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
    bundle.innerHTML = '';
    bundle.appendChild(row_copy);

    chk_sl_details.forEach(function(chk){
        const tr = chk.closest('[data-row="clone"]');
        const sld_seq = tr.querySelector('[data-student-lecture-detail-seq]').value;
        const lecture_name = tr.querySelector('[data-lecture-name]').innerText;
        const lecture_detail_description = tr.querySelector('[data-lecture-detail-description]').innerText;
        const lecture_detail_name = tr.querySelector('[data-lecture-detail-name]').innerText;

        const row = row_copy.cloneNode(true);
        row.hidden = false;
        row.dataset.row = 'clone';

        row.querySelector('[data-student-lecture-detail-seq]').value = sld_seq;
        row.querySelector('[data-lecture-info]').innerText = '['+lecture_name + '] ' + lecture_detail_description + ' ' + lecture_detail_name;

        if(student_exam_result_data[sld_seq]){
            const ser_data = student_exam_result_data[sld_seq];
            const select = document.querySelector('[data-select-evaluation-seq]');
            // select option value 와 ser_data.evaluation_seq 가 같으면 text를 가져온다.
            const option = select.querySelector(`option[value="${ser_data.evaluation_seq}"]`);
            let after_text = '';
            if(option){
                after_text += option.innerText+' ';
            }
            row.querySelector('[data-hidden-exam-info]').hidden = false;

            // inwrong_cnt / total_cnt
            after_text += `${ser_data.inwrong_cnt} / ${ser_data.total_cnt}`

            row.querySelector('[data-exam-cnt-info]').innerText = after_text;
        }
        bundle.appendChild(row);

    });
    // modal 끄기.
    const modal = document.querySelector('#modal_lecture_chk');
    modal.querySelector('.btn-close').click();
}


async function afterLenDetailSmsModalOpen(){
    const student_seq = document.querySelector('[data-main-student-seq]').value;
    const student_seqs = [];
    student_seqs.push(student_seq);
    if(await alarmSendGetSmsInfo(student_seqs)){
        alarmSendSmsModalOpen();
        alarmSelectUser();
        // modal alarm_modal_div_member_select
        const modal = document.querySelector('#alarm_modal_div_member_select');
        modal.querySelector('[data-target="parent"]').checked = true;
    }
}

</script>
@endsection
