@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
    상담일지 등록
@endsection

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    <style>
        .editable-div:empty:before {
            content: attr(data-placeholder);
            color: #ccc;
        }
    </style>
    <input type="hidden" data-inp-student-seq value="{{ $student_seq }}">
    <input type="hidden" data-counsel-start-date  value="{{ $counsel->start_date }}">

    <div class="col-12 pe-3 ps-3 position-relative">
        <div class="sub-title d-flex justify-content-between">
            <h2 class="text-sb-42px">
                <button data-btn-back-page class="btn p-0 row mx-0" onclick="counselAddBack();">
                    <img src="{{ asset('images/black_arrow_left_tail.svg') }}" width="52" class="px-0">
                </button>
                <span class="me-2">상담일지 등록</span>
                <span
                    class="ht-make-title on text-r-20px py-1 px-3 ms-1 h-42 d-flex align-items-center">{{ $student->goods_name }}</span>
            </h2>
            <div class="d-flex align-items-center">
                <label class="d-inline-block select-wrap select-icon">
                    <select data-select-search
                        class="date-change rounded-pill border-gray sm-select text-sb-20px me-2 h-52">
                        <option value="counsel_seq">상담관리번호</option>
                    </select>
                </label>
                <label class="label-search-wrap h-center">
                    <input type="text" onkeyup="if(event.keyCode == 13) counselDtSearch();" data-inp-search
                        class="select2 smart-hb-input border-gray rounded-pill text-m-20px gray-color text-start ps-4"
                        value="{{ strlen($counsel_seq) > 0 ? $counsel_seq : '' }}">
                </label>
                <input type="hidden" data-post-counsel-seq value={{ $counsel_seq }}>
                <input type="hidden" data-post-counsel-next-seq value={{ $counsel_next ? $counsel_next->id:'' }}>
            </div>
        </div>

        <div class="row">
            <div class="col-3 ">
                <div class="">
                    <ul class="tab py-4 px-3 div-shadow-style rounded-3">
                        <li class="mb-2">
                            <button
                                class="btn w-100 text-start text-b-20px py-4 px-4 scale-text-white primary-bg-mian primary-bg-mian-hover scale-text-white-hover">
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M24.4229 6.44604H7.57843C6.33843 6.44604 5.33398 7.44011 5.33398 8.66729V23.112C5.33398 24.3392 6.33843 25.3332 7.57843 25.3332H24.4229C25.6629 25.3332 26.6673 24.3392 26.6673 23.112V8.66729C26.6673 7.44011 25.6629 6.44604 24.4229 6.44604Z"
                                        fill="#FFF6E0"></path>
                                    <path
                                        d="M17.3858 14.0598L11.8125 19.5755L13.8036 21.5417L19.3769 16.0259L17.3858 14.0598Z"
                                        fill="#FFE39E"></path>
                                    <path
                                        d="M11.3675 20.0153L10.9941 21.1237L12.2253 22.3421L13.3586 21.9814L13.803 21.5416L11.8119 19.5754L11.3675 20.0153Z"
                                        fill="#F0F0F0"></path>
                                    <path d="M10.3867 22.9316L12.2312 22.3422L11.0001 21.1238L10.3867 22.936V22.9316Z"
                                        fill="#999999"></path>
                                    <path
                                        d="M20.1739 13.6642L19.7739 13.2728C19.565 13.066 19.2761 12.9473 18.9784 12.9473C18.6806 12.9473 18.3961 13.0616 18.1828 13.2728L17.3828 14.0601L19.3739 16.0306L20.1695 15.2389C20.2761 15.1333 20.3561 15.0146 20.4139 14.8782C20.4717 14.7419 20.4984 14.5967 20.4984 14.4516C20.4984 14.3064 20.4673 14.1613 20.4139 14.0249C20.3561 13.8885 20.2761 13.7654 20.1695 13.6642H20.1739Z"
                                        fill="#FFB3BC"></path>
                                    <path
                                        d="M5.33398 8.42537C5.33398 7.33014 6.22732 6.44604 7.33398 6.44604H24.6673C25.774 6.44604 26.6673 7.33014 26.6673 8.42537V11.0645H5.33398V8.42537Z"
                                        fill="#FFC747"></path>
                                    <path
                                        d="M12.0489 5.33325H10.9467C10.6356 5.33325 10.3867 5.58397 10.3867 5.88746V8.10871C10.3867 8.4166 10.6401 8.66292 10.9467 8.66292H12.0489C12.3601 8.66292 12.6089 8.4122 12.6089 8.10871V5.88746C12.6089 5.57957 12.3556 5.33325 12.0489 5.33325Z"
                                        fill="#F3B527"></path>
                                    <path
                                        d="M21.0536 5.33325H19.9291C19.618 5.33325 19.3691 5.58397 19.3691 5.88746V8.10871C19.3691 8.4166 19.6225 8.66292 19.9291 8.66292H21.0536C21.3647 8.66292 21.6136 8.4122 21.6136 8.10871V5.88746C21.6136 5.57957 21.3603 5.33325 21.0536 5.33325Z"
                                        fill="#F3B527"></path>
                                </svg>
                                상담일지
                            </button>
                        </li>

                        <li class="">
                            <button
                                class="btn w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M24.4229 6.44604H7.57843C6.33843 6.44604 5.33398 7.44011 5.33398 8.66729V23.112C5.33398 24.3392 6.33843 25.3332 7.57843 25.3332H24.4229C25.6629 25.3332 26.6673 24.3392 26.6673 23.112V8.66729C26.6673 7.44011 25.6629 6.44604 24.4229 6.44604Z"
                                        fill="#FFF6E0"></path>
                                    <path
                                        d="M17.3858 14.0598L11.8125 19.5755L13.8036 21.5417L19.3769 16.0259L17.3858 14.0598Z"
                                        fill="#FFE39E"></path>
                                    <path
                                        d="M11.3675 20.0153L10.9941 21.1237L12.2253 22.3421L13.3586 21.9814L13.803 21.5416L11.8119 19.5754L11.3675 20.0153Z"
                                        fill="#F0F0F0"></path>
                                    <path d="M10.3867 22.9316L12.2312 22.3422L11.0001 21.1238L10.3867 22.936V22.9316Z"
                                        fill="#999999"></path>
                                    <path
                                        d="M20.1739 13.6642L19.7739 13.2728C19.565 13.066 19.2761 12.9473 18.9784 12.9473C18.6806 12.9473 18.3961 13.0616 18.1828 13.2728L17.3828 14.0601L19.3739 16.0306L20.1695 15.2389C20.2761 15.1333 20.3561 15.0146 20.4139 14.8782C20.4717 14.7419 20.4984 14.5967 20.4984 14.4516C20.4984 14.3064 20.4673 14.1613 20.4139 14.0249C20.3561 13.8885 20.2761 13.7654 20.1695 13.6642H20.1739Z"
                                        fill="#FFB3BC"></path>
                                    <path
                                        d="M5.33398 8.42537C5.33398 7.33014 6.22732 6.44604 7.33398 6.44604H24.6673C25.774 6.44604 26.6673 7.33014 26.6673 8.42537V11.0645H5.33398V8.42537Z"
                                        fill="#FFC747"></path>
                                    <path
                                        d="M12.0489 5.33325H10.9467C10.6356 5.33325 10.3867 5.58397 10.3867 5.88746V8.10871C10.3867 8.4166 10.6401 8.66292 10.9467 8.66292H12.0489C12.3601 8.66292 12.6089 8.4122 12.6089 8.10871V5.88746C12.6089 5.57957 12.3556 5.33325 12.0489 5.33325Z"
                                        fill="#F3B527"></path>
                                    <path
                                        d="M21.0536 5.33325H19.9291C19.618 5.33325 19.3691 5.58397 19.3691 5.88746V8.10871C19.3691 8.4166 19.6225 8.66292 19.9291 8.66292H21.0536C21.3647 8.66292 21.6136 8.4122 21.6136 8.10871V5.88746C21.6136 5.57957 21.3603 5.33325 21.0536 5.33325Z"
                                        fill="#F3B527"></path>
                                </svg>
                                학습현황
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-9" data-main-div-type="counsel_add">
                <p class="text-b-28px mb-4">회원정보</p>
                <table class="w-100 table-list-style table-border-xless table-h-92">
                    <colgroup>
                        <col style="width: 15%;">
                        <col style="width: 35%;">
                        <col style="width: 15%;">
                        <col style="width: 35%;">
                    </colgroup>
                    <thead></thead>
                    <tbody>
                        <tr class="text-start">
                            <td class="text-start ps-4">회원명</td>
                            <td class="text-start px-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="">
                                        <span data-student-name>{{ $student->student_name }}</span>
                                        <span data-student-id>({{ $student->student_id }})</span>
                                    </div>
                                    <div>
                                        <button type="button"
                                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black p-3">회원정보관리</button>
                                    </div>
                                </div>
                            </td>
                            <td class="text-start ps-4">휴대전화</td>
                            <td class="text-start px-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div data-student-phone class="">
                                        {{ $student->student_phone }}
                                    </div>
                                    <div>
                                        <button type="button"
                                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black p-3">문자발송</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start">
                            <td class="text-start ps-4">포인트</td>
                            <td class="text-start px-4">
                                <div class="primary-text-mian">
                                    {{ $student->point_now }}p
                                </div>
                            </td>
                            <td class="text-start ps-4">형제 및 자매</td>
                            <td class="text-start px-4">
                                <div class="">
                                    {{ $student->brother }}
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start">
                            <td class="text-start ps-4">이용권 기간</td>
                            <td colspan="3" class="text-start px-4">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        {{-- goods_end_date --}}
                                        <span>{{ $student->goods_start_date }} - {{ $student->goods_end_date }}</span>
                                        @if ($remain_date_cnt < 30)
                                            <button type="button"
                                                class="btn-line-xss-secondary text-sb-20px border-gray rounded studyColor-bg-studyComplete scale-text-white p-3">
                                                {{ $remain_date_cnt }}일/만료임박
                                            </button>
                                        @endif
                                    </div>
                                    <div>
                                        <button type="button"
                                            class="btn-line-xss-secondary text-sb-20px border-gray rounded primary-bg-mian scale-text-white p-3">결제하기</button>
                                    </div>
                                </div>
                            </td>

                        </tr>
                        <tr class="text-start">
                            <td class="text-start ps-4">회원구분</td>
                            <td colspan="3" class="text-start px-4">
                                <div class="">
                                    학생 / {{ $student->school_name }} / {{ $student->grade_name }}
                                    <input data-grade-name type="hidden" value=" {{ $student->grade_name }}">
                                </div>
                            </td>

                        </tr>

                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-52">
                    <button type="button"
                        class="btn-line-ms-secondary text-sb-20px rounded-pill border-gray scale-bg-white scale-text-black">학습플래너
                        이동하기</button>
                </div>
                <p class="text-b-28px mt-80 mb-4">상담일지</p>

                <table class="w-100 table-list-style table-border-xless table-h-92">
                    <colgroup>
                        <col style="width: 25%;">
                        <col style="width: 75%;">
                    </colgroup>
                    <thead></thead>
                    <tbody>
                        <tr class="text-start">
                            <td class="text-start ps-4">상담관리번호/유형</td>
                            <td class="text-start px-4 scale-text-black py-4">
                                <div class="d-inline-block select-wrap select-icon w-100">
                                    <select data-counsel-type
                                        class="border-gray lg-select text-b-24px border-none w-100 h-52 p-0" disabled>
                                        <option value="regular" {{ ($counsel_detail->counsel_type ?? $counsel->counsel_type) == 'regular' ? 'selected' : '' }}>
                                            정기상담</option>
                                        <option value="no_regular"
                                            {{ ($counsel_detail->counsel_type ?? $counsel->counsel_type) == 'no_regular' ? 'selected' : '' }}>수기상담</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start">
                            <td class="text-start ps-4">상담 대상</td>
                            <td class="text-start px-4 scale-text-black py-4">
                                <div class="d-inline-block select-wrap select-icon w-100 ">
                                    <select data-counsel-target-type
                                        class="border-gray lg-select text-b-24px border-none w-100 h-52 p-0">
                                        <option value="student" {{ $counsel_detail && $counsel_detail->target_type == 'student' ? 'selected':''}}>학생</option>
                                        <option value="parent" {{ $counsel_detail && $counsel_detail->target_type == 'parent' ? 'selected':''}} >학부모</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start">
                            <td class="text-start ps-4 align-top">상담 시작일시</td>
                            <td class="text-start px-4 scale-text-black py-4">
                                {{-- input type check 상담실패 input type date --}}
                                <div class="h-center justify-content-between">
                                    <div class="h-center gap-4">
                                        <div class="h-center gap-3">
                                            <label class="checkbox mt-1">
                                                <input data-is-fail="1" {{ $counsel_detail && $counsel_detail->is_fail == 'Y' ? 'checked':'' }}
                                                type="checkbox" class=""
                                                    onclick="event.stopPropagation();">
                                                <span class="" onclick="event.stopPropagation();">
                                                </span>
                                            </label>
                                            <label onclick="this.previousElementSibling.querySelector('input').click()"
                                                class="col-auto">
                                                상담실패
                                            </label>
                                        </div>
                                        <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12">
                                        <div>
                                            {{-- 날짜시간 PICKER --}}
                                            <label class="label-date-wrap overflow-hidden" style="height: 52px">
                                                <input data-inp-start-datetime 
                                                value="{{ $counsel_detail && $counsel_detail->start_datetime ? str_replace('-', '.', substr($counsel_detail->start_datetime, 0, 16)):'' }}"
                                                
                                                    onclick="this.nextElementSibling.showPicker()" type="text"
                                                    class="select2 smart-hb-input border-gray rounded text-m-20px gray-color text-center scale-bg-gray_01"
                                                    readonly="" placeholder="">
                                                <input type="datetime-local" style="width:312px;height: 0.5px;"
                                                    oninput="counselAddDateTimeSel(this)">
                                            </label>
                                        </div>
                                    </div>
                                    <button type="button" onclick="counselAddStartTimeClick()"
                                        class="col-auto btn-line-xss-secondary text-sb-20px border-gray rounded primary-bg-mian scale-text-white p-3">시작하기</button>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start">
                            <td class="text-start ps-4 align-top">상담 종료일시</td>
                            <td class="text-start px-4 scale-text-black">
                                <div class="h-center justify-content-between">
                                    <div class="h-center gap-4">
                                        <div class="h-center gap-3">
                                            <label class="checkbox mt-1">
                                                <input data-is-fail="2" type="checkbox" class=""
                                                    onclick="event.stopPropagation();" {{ $counsel_detail && $counsel_detail->is_fail == 'Y' ? 'checked':'' }}>
                                                <span class="" onclick="event.stopPropagation();">
                                                </span>
                                            </label>
                                            <label onclick="this.previousElementSibling.querySelector('input').click()"
                                                class="col-auto">
                                                상담실패
                                            </label>
                                        </div>
                                        <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12">
                                        <div>
                                            <label class="label-date-wrap overflow-hidden" style="height: 52px">
                                                <input data-inp-end-datetime onclick="this.nextElementSibling.showPicker()"
                                                value="{{ $counsel_detail && $counsel_detail->end_datetime ? str_replace('-', '.', substr($counsel_detail->end_datetime, 0, 16)):'' }}"
                                                    type="text"
                                                    class="select2 smart-hb-input border-gray rounded text-m-20px gray-color text-center scale-bg-gray_01"
                                                    readonly="" placeholder="">
                                                <input type="datetime-local" style="width:312px;height: 0.5px;"
                                                    oninput="counselAddDateTimeSel(this)">
                                            </label>
                                        </div>
                                    </div>
                                    <button type="button" onclick="counselAddEndTimeClick()"
                                        class="col-auto btn-line-xss-secondary text-sb-20px border-gray rounded bg-danger scale-text-white p-3">종료하기</button>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start">
                            <td class="text-start ps-4 align-top">상담내용/실패사유</td>
                            <td class="text-start px-4 scale-text-black">
                                <div class="h-center justify-content-between">
                                    {{-- div = textarea 속성 --}}
                                    <div data-counsel-content="content" style="max-width: 750px"
                                        data-placeholder-1="상담내용을 입력해주세요." data-placeholder-2="(입력된내용은 해당 학부모에게 자동전송됩니다.)"
                                        contenteditable="true" class="editable-div col"
                                        onfocus="counselAddPlaceHolder(this, 'in')"
                                        onblur="counselAddPlaceHolder(this, 'out')">
                                        @if($counsel_detail && $counsel_detail->content)
                                            {{ $counsel_detail->content }}
                                        @else
                                            <span class="text-b-24px scale-text-gray_05">상담내용을 입력해주세요.</span>
                                            <span class="text-b-20px text-danger">(입력된내용은 해당 학부모에게 자동전송됩니다.)</span>
                                        @endif
                                    </div>

                                    <button type="button"
                                        class="col-auto btn-line-xss-secondary text-sb-20px border-gray rounded primary-bg-mian scale-text-white p-3">문자발송</button>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start">
                            <td class="text-start ps-4 align-top">학부모 전달 내용</td>
                            <td class="text-start px-4 scale-text-black">
                                <div class="h-center justify-content-between">
                                    {{-- div = textarea 속성 --}}
                                    <div data-counsel-content="parent_send" style="max-width: 850px"
                                        data-placeholder-1="학부모에게 전달하고자 하는 내용을 입력해주세요."
                                        data-placeholder-2="(입력된내용은 해당 학부모에게 자동전송됩니다.)" contenteditable="true"
                                        class="editable-div col" onfocus="counselAddPlaceHolder(this, 'in')"
                                        onblur="counselAddPlaceHolder(this, 'out')">
                                        @if($counsel_detail && $counsel_detail->pt_send_content)
                                            {{ $counsel_detail->pt_send_content }}
                                        @else
                                            <span class="text-b-24px scale-text-gray_05">학부모에게 전달하고자 하는 내용을 입력해주세요.</span>
                                            <span class="text-b-20px text-danger">(입력된내용은 해당 학부모에게 자동전송됩니다.)</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start">
                            <td class="text-start ps-4 align-top">정기 상담(예정)일시</td>
                            <td class="text-start px-4 scale-text-black py-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div data-div-show-start-datetime-day
                                    class="">
                                        @php
                                            $dayOfWeek = \Carbon\Carbon::parse($counsel_next ? $counsel_next->start_date:'')->dayOfWeek;
                                            $dayOfWeek2 = \Carbon\Carbon::parse($counsel_detail ? $counsel_detail->regular_start_date:'')->dayOfWeek;
                                            $days = [
                                                '일요일',
                                                '월요일',
                                                '화요일',
                                                '수요일',
                                                '목요일',
                                                '금요일',
                                                '토요일'
                                            ];
                                            $is_regular = $counsel->counsel_type == 'regular' ? true : false;
                                        @endphp
                                        @if($counsel_detail && $counsel_detail->is_temp == 'Y' && $counsel_detail->regular_start_date)
                                            {{  str_replace('-', '.', substr($counsel_detail->regular_start_date, 0, 10)) }} 
                                            {{ $days[$dayOfWeek2] }} 
                                            {{ substr($counsel_detail->regular_start_time, 0, 5) }}
                                        @elseif ($is_regular || $counsel_next)
                                            {{  str_replace('-', '.', substr($counsel_next->start_date, 0, 10)) }} 
                                            {{ $days[$dayOfWeek] }} 
                                            {{ substr($counsel_next->start_time, 0, 5) }}
                                        @endif
                                        {{-- 2023.09.10 목요일 14:00 --}}
                                    </div>
                                    <div>
                                        <div class="d-inline-block select-wrap select-icon w-100 ">
                                            <select data-select-next-regular-datetime onclick="counselAddModalShow();"
                                                class="border-gray lg-select text-sb-20px w-100 h-52 py-0">
                                                @if($counsel_detail && $counsel_detail->is_temp == 'Y' && $counsel_detail->regular_start_date)
                                                    <option value="">매주 {{ $days[$dayOfWeek2] }} {{ substr($counsel_detail->regular_start_time, 0, 5) }}</option>
                                                @elseif ($is_regular || $counsel_next)
                                                    <option value="">매주 {{ $days[$dayOfWeek] }} {{ substr($counsel_next->start_time, 0, 5) }}</option>
                                                @endif
                                            </select>
                                            <input type="hidden" data-input-next-regular-datetime="start_date"
                                            {{ $counsel_detail && $counsel_detail->is_temp == 'Y' ? 'value=' .substr($counsel_detail->regular_start_date, 0, 10) : ''}}
                                            {{ $counsel_next ? 'value=' .substr($counsel_next->start_date, 0, 10) : ''}}
                                            >
                                            <input type="hidden" data-input-next-regular-datetime="start_time"
                                            {{ $counsel_detail && $counsel_detail->is_temp == 'Y' ? 'value=' .substr($counsel_detail->regular_start_time, 0, 5) : ''}}
                                            {{ $counsel_next ? 'value=' .substr($counsel_next->start_time, 0, 5) : ''}}
                                            >
                                            <input type="hidden" data-input-next-regular-datetime="end_time"
                                            {{ $counsel_detail && $counsel_detail->is_temp == 'Y' ? 'value=' .substr($counsel_detail->regular_end_time, 0, 5) : ''}}
                                            {{ $counsel_next ? 'value=' .substr($counsel_next->end_time, 0, 5) : ''}}
                                            >
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start">
                            <td class="text-start ps-4 align-top">상담 보고(결제) 설정</td>
                            <td class="text-start px-4 scale-text-black">
                                <div class="d-inline-block select-wrap select-icon w-100 ">
                                    <select data-report-target-seq
                                        class="border-gray lg-select text-b-24px border-none w-100 h-52 p-0">
                                        <option value="{{ $leader_info->id }}">
                                            {{ $leader_info->teach_name . ' / ' . $leader_info->region_name . ' ' . $leader_info->team_name }}
                                        </option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start">
                            <td class="text-start ps-4 align-top">상담예정 특이사항</td>
                            <td class="text-start px-4 scale-text-black">
                                <div class="h-center justify-content-between">
                                    {{-- div = textarea 속성 --}}
                                    <div data-counsel-content="special" style="max-width: 850px"
                                        data-placeholder-1="다음 상담 시 주안점 또는 특이사항을 입력해주세요." data-placeholder-2=""
                                        contenteditable="true" class="editable-div col"
                                        onfocus="counselAddPlaceHolder(this, 'in')"
                                        onblur="counselAddPlaceHolder(this, 'out')">
                                        @if($counsel_detail && $counsel_detail->counsel_special)
                                            {{ $counsel_detail->counsel_special }}
                                        @else
                                            <span class="text-b-24px scale-text-gray_05">다음 상담 시 주안점 또는 특이사항을 입력해주세요.</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start">
                            <td rowspan="4" class="text-start ps-4 align-top">다음 상담 일정 변경</td>
                            <td class="text-start px-4 scale-text-black">
                                <label class="checkbox d-flex align-items-center">
                                    <input type="checkbox" class="" data-is-change-date onchange="counselAddChkIsChageDate(this)" 
                                    {{ $counsel_detail && $counsel_detail->is_chage_regular_date == 'Y' ? 'checked' : ''}}
                                    >
                                    <span class=""></span>
                                    <p class="text-b-24px scale-text-black ms-2">일정변경하기</p>
                                </label>
                            </td>
                        </tr>
                        <tr class="text-start">
                            <td class="text-start px-4 scale-text-black py-4">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="">
                                        변경 일자를 선택해주세요.
                                    </div>
                                    <div class="">
                                        <label class="label-date-wrap overflow-hidden" style="height: 52px;width:312px">
                                            <input data-inp-change-datetime onclick="counselAddModalShow('no_regular');"
                                                type="text"
                                                class="select2 smart-hb-input border-gray rounded text-m-20px gray-color text-center scale-bg-gray_01 cursor-pointer"
                                                readonly="" placeholder="" 
                                                value="{{ $counsel_detail && $counsel_detail->is_chage_regular_date == 'Y' ? str_replace('-', '.', $counsel_detail->change_regular_date).' '.substr($counsel_detail->change_regular_start_time, 0,5) : '' }}"
                                                >
                                            <input type="datetime-local" style="width:312px;height: 0.5px;"
                                                oninput="counselAddDateTimeSel(this)">
                                        </label>
                                        <input type="hidden" data-inp-change-date value="{{ $counsel_detail && $counsel_detail->is_chage_regular_date == 'Y' ? $counsel_detail->change_regular_date:''}}">
                                        <input type="hidden" data-inp-change-start-time value="{{ $counsel_detail && $counsel_detail->is_chage_regular_date == 'Y' ? substr($counsel_detail->change_regular_start_time, 0,5):'' }}">
                                        <input type="hidden" data-inp-change-end-time value="{{ $counsel_detail && $counsel_detail->is_chage_regular_date == 'Y' ? substr($counsel_detail->change_regular_end_time, 0,5):'' }}">
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start">
                            <td class="text-start px-4 scale-text-black">
                                <div class="d-inline-block select-wrap select-icon w-100 ">
                                    <select data-select-chg-reason-target onchange="counselAddChageReasonTarget(this)"
                                        class="border-gray lg-select text-b-24px border-none w-100 h-52 p-0">
                                        <option value="">대상을 선택해주세요.</option>
                                        <option value="teacher" {{ $counsel_detail && $counsel_detail->change_regular_target == 'teacher'? 'selected':'' }}>선생님</option>
                                        <option value="student" {{ $counsel_detail && $counsel_detail->change_regular_target == 'student'?'selected':'' }} >학생/학부모</option>
                                    </select>
                                    <script>
                                        //ready
                                        document.addEventListener('DOMContentLoaded', function() {
                                            document.querySelector('[data-select-chg-reason-target]').onchange();
                                        });
                                    </script>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start">
                            <td class="text-start px-4 scale-text-black">
                                <div class="d-inline-block select-wrap select-icon w-100 ">
                                    <select data-select-chg-reason onchange="counselAddChageReason(this)"
                                        class="border-gray lg-select text-b-24px border-none w-100 h-52 p-0">
                                        <option value="">사유를 선택해주세요.</option>
                                    </select>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const cr_reason = ('{{ $counsel_detail ? $counsel_detail->change_regular_reason:'' }}');
                                            document.querySelector('[data-select-chg-reason]').value = cr_reason.split('//')[0];
                                            document.querySelector('[data-select-chg-reason]').onchange();
                                            if(cr_reason.split('//')[1]){
                                                document.querySelector('[data-input-chg-reason]').value = cr_reason.split('//')[1];
                                            }
                                        });
                                    </script>
                                    <select data-sau-bundle hidden>
                                        <optgroup label="teacher">
                                            <option value="병가">병가</option>
                                            <option value="연차">연차</option>
                                            <option value="경조사">경조사</option>
                                            <option value="직접입력">기타(직접입력)</option>
                                        </optgroup>
                                        <optgroup label="student">
                                            <option value="병결">병결</option>
                                            <option value="가족모임">가족 모임</option>
                                            <option value="직접입력">기타(직접입력)</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <input data-input-chg-reason hidden
                                class="w-100" placeholder="직접입력(사유)">
                            </td>
                        </tr>
                        <tr class="text-start">
                            <td class="text-start px-4">
                                학부모 불편사항
                            </td>
                            <td class="text-start px-4 scale-text-black">
                                <div data-counsel-content="parent_complaint" style="max-width: 850px"
                                    data-placeholder-1="학부모 불편사항을 입력해주세요." data-placeholder-2="" contenteditable="true"
                                    class="editable-div col" onfocus="counselAddPlaceHolder(this, 'in')"
                                    onblur="counselAddPlaceHolder(this, 'out')">
                                    @if($counsel_detail && $counsel_detail->parent_complaint)
                                        {{ $counsel_detail->parent_complaint }}
                                    @else
                                        <span class="text-b-24px scale-text-gray_05">학부모 불편사항을 입력해주세요.</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="d-flex justify-content-between mt-52">
                    <div class="">
                        <!-- <button type="button" class="btn-line-ms-secondary text-sb-20px rounded-pill border-gray scale-bg-white scale-text-gray_05">목록으로 가기</button> -->
                    </div>
                    <div class="w-100 d-flex justify-content-between">
                        <button type="button" onclick="counselAddDelete()"
                        class="btn-line-ms-secondary text-sb-20px rounded-pill border-gray basic-bg-error text-white float-start">삭제</button>
                        <div>
                            <button type="button" onclick="counselAddInsert('temp')"
                                class="btn-line-ms-secondary text-sb-20px rounded-pill border-gray scale-bg-white scale-text-gray_05">임시저장</button>
                            <button type="button" onclick="counselAddInsert()"
                                class="btn-ms-primary text-b-24px rounded-pill scale-text-white">등록하기</button>
                        </div>
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
        {{-- 모달 / 정기상담일 등록 --}}
        @include('teacher.modal_counsel_add')
    </div>
    <script>
        @if($counsel_detail && $counsel_detail->is_temp == 'Y')
            toast('임시저장이 있어 자동으로 불러옵니다.');
        @endif
        var main_counsel = document.querySelector('[data-main-div-type="counsel_add"]');
        var counsel_modal_calendar = fullcalendarInit2();
        // div contenteditable 속성 placeholder
        function counselAddPlaceHolder(vthis, type) {
            const placeholder1 = vthis.getAttribute('data-placeholder-1');
            const placeholder2 = vthis.getAttribute('data-placeholder-2');
            if (type == 'in') {
                const text = vthis.innerText.trim().replace(/ /g, '');
                const text1 = (placeholder1 + placeholder2).trim().replace(/ /g, '');
                if (text == text1) {
                    vthis.innerHTML = '';
                    vthis.focus();
                }
            } else {
                //out
                if (vthis.innerText.length < 1) {
                    vthis.innerHTML =
                        `   <span class="text-b-24px scale-text-gray_05">${placeholder1}</span>
                        <span class="text-b-20px text-danger">${placeholder2}</span>
                    `;
                }
            }
        }
        function counselAddPlaceHolderDelGetValue(vthis) {
            const placeholder1 = vthis.getAttribute('data-placeholder-1');
            const placeholder2 = vthis.getAttribute('data-placeholder-2');
            const text = vthis.innerText.trim().replace(/ /g, '');
            const text1 = (placeholder1 + placeholder2).trim().replace(/ /g, '');
            if (text == text1) {
                return '';
            }else{
                return vthis.innerText;
            }
        }

        // 만든날짜 선택
        function counselAddDateTimeSel(vthis) {
            //datetime-local format yyyy.MM.dd HH:mm 변경
            const date = new Date(vthis.value);
            vthis.previousElementSibling.value = date.format('yyyy.MM.dd HH:mm');
        }

        // 시작하기 버튼 클릭
        function counselAddStartTimeClick() {
            //data-inp-start-datetime 에 지금시간 넣어주기.
            const start_datetime_el = main_counsel.querySelector('[data-inp-start-datetime]');
            const date = new Date().format('yyyy.MM.dd HH:mm');
            start_datetime_el.value = date;
        }
        // 종료하기 버튼 클릭
        function counselAddEndTimeClick() {
            //date-inp-end-time에 지금 시간 넣어주기.
            const end_datetime_el = main_counsel.querySelector('[data-inp-end-datetime]');
            const date = new Date().format('yyyy.MM.dd HH:mm');
            end_datetime_el.value = date;
        }

        // 상담 등록
        function counselAddInsert(type) {
            const parameter = counselAddInsertCheck(type);
            if (!parameter.code) {
                toast(parameter.msg);
                return;
            }
            const student_seq = document.querySelector('[data-inp-student-seq]').value;
            const counsel_seq = document.querySelector('[data-post-counsel-seq]').value;
            const counsel_next_seq = document.querySelector('[data-post-counsel-next-seq]').value;

            parameter.is_temp = (type == 'temp' ? 'Y' : 'N');
            parameter.student_seq = student_seq;
            parameter.counsel_seq = counsel_seq;
            parameter.counsel_next_seq = counsel_next_seq;

            const page = "/manage/counsel/detail/insert";
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    let msg = '상담이 등록 되었습니다.';
                   if(parameter.is_temp == 'Y') msg = '상담이 임시저장 되었습니다.';
                   sAlert('', `<span class="text-sb-28px">${msg}</span>`, 4);
                }else{

                }
            });
        }

        // 상담 등록 체크
        function counselAddInsertCheck(type) {
            const is_temp = type == 'temp' ? true : false;
            let result = {};
            result.code = true;
            let is_fail = false;
            // 아래사항 없을시 false
            //상담실패 체크시 시작종료는 필요없음.
            const data_is_fail1 = main_counsel.querySelector('[data-is-fail="1"]').checked;
            const data_is_fail2 = main_counsel.querySelector('[data-is-fail="2"]').checked;
            is_fail = data_is_fail1 || data_is_fail2;
            let start_datetime = '';
            let end_datetime = '';
            if (!is_fail ) {
                start_datetime = document.querySelector('[data-inp-start-datetime]').value;
                end_datetime = document.querySelector('[data-inp-end-datetime]').value;
                // 시작일시 종료일시 없으면 result.code = false
                if ((start_datetime == '' || end_datetime == '') && !is_temp) {
                    result.code = false;
                    result.msg = '시작일시와 종료일시를 입력해주세요.';
                }
                // 시작시간보다 끝시간이 빠르면 false
                if (start_datetime > end_datetime && !is_temp) {
                    result.code = false;
                    result.msg = '시작일시가 종료일시보다 빠릅니다.';
                }
            }
            // 상담내용 data-counsel-content="content"
            const content = counselAddPlaceHolderDelGetValue(main_counsel.querySelector('[data-counsel-content="content"]')).trim();
            if (content == '' && !is_temp) {
                result.code = false;
                result.msg = '상담내용을 입력해주세요.';
            }
            // 학부모 전달 내용
            const parent_send = counselAddPlaceHolderDelGetValue(main_counsel.querySelector('[data-counsel-content="parent_send"]')).trim();

            // 정기 상담 예정 일시 data-input-next-regular-datetime="start_date"
            const regular_start_date = main_counsel.querySelector('[data-input-next-regular-datetime="start_date"]').value;
            const regular_start_time = main_counsel.querySelector('[data-input-next-regular-datetime="start_time"]').value;
            const regular_end_time = main_counsel.querySelector('[data-input-next-regular-datetime="end_time"]').value;

            // 상담보고(결제) 설정 data-is-change-date
            const is_chage_regular_date = main_counsel.querySelector('[data-is-change-date]').checked;
            let change_regular_date = '';
            let change_regular_start_time = ''; 
            let change_regular_end_time = '';
            let change_regular_target = '';
            let change_regular_reason = '';

            // 일정변경하기 체크시
            if (is_chage_regular_date) {
                const change_start_date_el = main_counsel.querySelector('[data-inp-change-date]');
                const change_start_time_el = main_counsel.querySelector('[data-inp-change-start-time]');
                const change_end_time_el = main_counsel.querySelector('[data-inp-change-end-time]');

                change_regular_date = change_start_date_el.value;
                change_regular_start_time = change_start_time_el.value;
                change_regular_end_time = change_end_time_el.value;
                if (change_regular_date == '' && !is_temp) {
                    result.code = false;
                    result.msg = '변경일자를 선택해주세요.';
                } else {
                    //format 변경   
                    // change_regular_date = change_regular_date.replace(/\./gi, '-');
                }
                change_regular_target = main_counsel.querySelector('[data-select-chg-reason-target]').value;
                if (change_regular_target == '' && !is_temp) {
                    result.code = false;
                    result.msg = '대상을 선택해주세요.';
                }
                change_regular_reason = main_counsel.querySelector('[data-select-chg-reason]').value;
                if (change_regular_reason == '' && !is_temp) {
                    result.code = false;
                    result.msg = '사유를 선택해주세요.';
                }
                const change_regular_reason2 = main_counsel.querySelector('[data-input-chg-reason]').value;
                change_regular_reason += change_regular_reason2.length > 0 ? '//' + change_regular_reason2:'';
            }
            // 학부모 불편사항 data-counsel-content="parent_complaint"
            const parent_complaint = counselAddPlaceHolderDelGetValue(main_counsel.querySelector('[data-counsel-content="parent_complaint"]')).trim();
            // 상담 보고 결제 설정
            const report_target_seq = main_counsel.querySelector('[data-report-target-seq]').value;
            // 상담 예정 특이사항
            const special = counselAddPlaceHolderDelGetValue(main_counsel.querySelector('[data-counsel-content="special"]')).trim();
            // 상담유형
            const counsel_type = main_counsel.querySelector('[data-counsel-type]').value;
            // 상담 대상
            const target_type = main_counsel.querySelector('[data-counsel-target-type]').value;

            result.start_datetime = start_datetime.replace(/\./gi, '-');
            result.end_datetime = end_datetime.replace(/\./gi, '-');
            result.content = content;
            result.parent_send = parent_send;
            result.regular_start_date = regular_start_date;
            result.regular_start_time = regular_start_time;
            result.regular_end_time = regular_end_time;
            result.is_chage_regular_date = is_chage_regular_date ? 'Y' : 'N';
            result.change_regular_date = change_regular_date;
            result.change_regular_start_time = change_regular_start_time;
            result.change_regular_end_time = change_regular_end_time;
            result.change_regular_target = change_regular_target;
            result.change_regular_reason = change_regular_reason;
            result.parent_complaint = parent_complaint;
            result.report_target_seq = report_target_seq;
            result.counsel_special = special;
            result.counsel_type = counsel_type;
            result.target_type = target_type;
            result.is_fail = is_fail ? 'Y' : 'N';

            return result;

        }

        // 다음상담일정 변경 > 대상변경시
        function counselAddChageReasonTarget(vthis) {
            const target = vthis.value;
            const sau_bundle = main_counsel.querySelector('[data-sau-bundle]');
            const select_chg_reason = main_counsel.querySelector('[data-select-chg-reason]');
            if (target == '') {
                // sau_bundle.hidden = true;
                select_chg_reason.innerHTML = '<option value="">사유를 선택해주세요.</option>';
            } else {
                // sau_bundle.hidden = false;
                select_chg_reason.innerHTML = sau_bundle.querySelector('optgroup[label="' + target + '"]').innerHTML;
            }
        }

        // 정기 상담(예정) 일시 클릭시.
        function counselAddModalShow(type) {
            const students = [];
            const student_seq = document.querySelector('[data-inp-student-seq]').value;
            const student_name = document.querySelector('[data-student-name]').innerText;
            const grade_name = document.querySelector('[data-grade-name]').value;
            const student = {
                student_seq: student_seq,
                student_name: student_name,
                grade_name: grade_name
            }
            students.push(student);
            let sel_date = undefined;
            if(type == 'no_regular'){
                sel_date = main_counsel.querySelector('[data-inp-change-date]').value;
            }else{
                sel_date = main_counsel.querySelector('[data-input-next-regular-datetime]').value;
            }
            teachCounselOpenRegularCounselModal(students, sel_date);
            //정기로 고정. data-select-modal-title
            document.querySelector('[data-select-modal-title]').disabled = true;
            document.querySelector('[data-select-counsel-week-time]').disabled = true;

            if(type == 'no_regular'){
                document.querySelector('[data-select-modal-title]').value = 'no_regular';
            }else{
                document.querySelector('[data-select-modal-title]').value = 'regular';
            }
            document.querySelector('[data-select-modal-title]').onchange();
            if((sel_date||'') != ''){

            }
            
        }

        // 상담일정 등록 버튼 클릭
        // 화면에 추가한 내용을 데이터로 저장.
        function teachCounselInsert(){
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

            // 여기서 부터 데이터 저장 넘김.
            const week_day = modal.querySelector('[data-span-week-time]').innerText; 
            const regular_start_date_el = main_counsel.querySelector('[data-input-next-regular-datetime="start_date"]');
            const regular_start_time_el = main_counsel.querySelector('[data-input-next-regular-datetime="start_time"]');
            const regular_end_time_el = main_counsel.querySelector('[data-input-next-regular-datetime="end_time"]');
            //---
            const change_datetime_el = main_counsel.querySelector('[data-inp-change-datetime]');
            const change_start_date_el = main_counsel.querySelector('[data-inp-change-date]');
            const change_start_time_el = main_counsel.querySelector('[data-inp-change-start-time]');
            const change_end_time_el = main_counsel.querySelector('[data-inp-change-end-time]');
            
            //2023.09.10 목요일 14:00
            const div_show_start_datetime_day = main_counsel.querySelector('[data-div-show-start-datetime-day]');
            //매주 월요일 14:00 option add
            const select_next_regular_datetime = main_counsel.querySelector('[data-select-next-regular-datetime]');
            //정규일때
            if(counsel_type == 'regular'){
                regular_start_date_el.value = sel_date;
                regular_start_time_el.value = start_time;
                regular_end_time_el.value = end_time,
                div_show_start_datetime_day.innerHTML = `${sel_date.replace(/-/gi, '.')} ${week_day}`;
                select_next_regular_datetime.innerHTML = `<option value="">매주 ${week_day}</option>`;
            }
            else if(counsel_type == 'no_regular'){
                change_start_date_el.value = sel_date;
                change_start_time_el.value = start_time;
                change_end_time_el.value = end_time;
                change_datetime_el.value = `${sel_date.replace(/-/gi, '.')} ${start_time}`;
            }
            modal.querySelector('.btn-close').click();
        }

        // 모달 / 닫기
        function teachCounselModalBack() {
            const modal = document.querySelector('#counsel_modal_add');
            const counsel_type = modal.querySelector('[data-select-modal-title]').value;

            const regular_start_date_el = main_counsel.querySelector('[data-input-next-regular-datetime="start_date"]');
            const regular_start_time_el = main_counsel.querySelector('[data-input-next-regular-datetime="start_time"]');
            const regular_end_time_el = main_counsel.querySelector('[data-input-next-regular-datetime="end_time"]');
            const div_show_start_datetime_day = main_counsel.querySelector('[data-div-show-start-datetime-day]');
            const select_next_regular_datetime = main_counsel.querySelector('[data-select-next-regular-datetime]');

            const change_start_date_el = main_counsel.querySelector('[data-inp-change-date]');
            const change_start_time_el = main_counsel.querySelector('[data-inp-change-start-time]');
            const change_end_time_el = main_counsel.querySelector('[data-inp-change-end-time]');
            const change_datetime_el = main_counsel.querySelector('[data-inp-change-datetime]');

            //초기화
            if(counsel_type == 'regular'){
                regular_start_date_el.value = '';
                regular_start_time_el.value = '';
                regular_end_time_el.value = '';
                div_show_start_datetime_day.innerHTML = '';
                select_next_regular_datetime.innerHTML = '';
            }else{
                change_start_date_el.value = '';
                change_start_time_el.value = '';
                change_end_time_el.value = '';
                change_datetime_el.value = '';
            }

            modal.querySelector('.btn-close').click();
        }

        // 사유 select 변경시
        function counselAddChageReason(vthis){
            //직접입력일때 data-input-chg-reason 보이기, 아닐때 숨기기.
            const value = vthis.value;
            const input_chg_reason = main_counsel.querySelector('[data-input-chg-reason]');
            input_chg_reason.value = '';
            if(value == '직접입력'){
                input_chg_reason.hidden = false;
            }else{
                input_chg_reason.hidden = true;
            }
        }
        function counselAddBack(){
            sessionStorage.setItem('isBackNavigation', 'true');
            window.history.back();
        }

        // 일정변경하기 체크시 확인.
        function counselAddChkIsChageDate(vthis){
            if(vthis.checked){
                //만약 이 상담이 수기이면서 /
                // 정기상담예정일시가 없으면서
                // 체크를 했으면 체크 해제
                if(main_counsel.querySelector('[data-counsel-type]').value == 'no_regular' && 
                    main_counsel.querySelector('[data-input-next-regular-datetime="start_date"]').value == ''){
                    vthis.checked = false;
                    toast('수기상담시 일정을 변경하려면 우선 정기상담 (예정)일시를 선택하세요.');
                }
            }
        }
        // 상담 삭제
        function counselAddDelete(){
           // 상담을 삭제하시겟습니까?
              const msg = 
              `
                <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
                    <p class="modal-title text-center text-sb-28px alert-top-m-20" id="">상담을 삭제하시겠습니까?</p>
                    <p class="modal-title text-center text-sb-28px alert-bottom-m studyColor-text-studyComplete" id="">삭제된 상담은 복구할 수 없습니다.</p>
                </div>
                `;
            sAlert('', msg, 3, function(){
                const counsel_seq = document.querySelector('[data-post-counsel-seq]').value;
                const student_seq = document.querySelector('[data-inp-student-seq]').value;
                const page = "/manage/counsel/delete";
                const parameter = {
                    counsel_seq: counsel_seq,
                    student_seq: student_seq
                };
                queryFetch(page, parameter, function(result){
                    if((result.resultCode||'') == 'success'){
                        sAlert('', `<span class="text-sb-28px">상담이 삭제 되었습니다.</span>`, 4);
                        counselAddBack();
                    }else{
                        sAlert('', `<span class="text-sb-28px">상담 삭제에 실패하였습니다.</span>`, 4);
                    }
                });
            });
        }
    </script>
@endsection
