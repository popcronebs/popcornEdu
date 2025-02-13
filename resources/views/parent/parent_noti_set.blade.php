
@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '알림설정')

@section('add_css_js')
@endsection
{{-- 학부모 인덱스  --}}

@section('layout_coutent')
@php

// 자녀 로그인 알림
$child_login = $alarm_settings->where('alarm_type', 'child_login')->first();
// 자녀 로그아웃 알림
$child_logout = $alarm_settings->where('alarm_type', 'child_logout')->first();

// 학습 예정 시작 시간 알림 시작시간
$child_study_plan_start = $alarm_settings->where('alarm_type', 'child_study_plan_start')->first();
// 학습 예정 시작 시간 알림 0분전
$child_study_plan_start_prev_0 = $alarm_settings->where('alarm_type', 'child_study_plan_start_prev_0')->first();
// 학습 예정 시작 시간 알림 10분전
$child_study_plan_start_prev_10 = $alarm_settings->where('alarm_type', 'child_study_plan_start_prev_10')->first();
// 학습 예정 시작 시간 알림 30분전
$child_study_plan_start_prev_30 = $alarm_settings->where('alarm_type', 'child_study_plan_start_prev_30')->first();
// 학습 예정 시작 시간 알림 1시간전
$child_study_plan_start_prev_60 = $alarm_settings->where('alarm_type', 'child_study_plan_start_prev_60')->first();

// 학습 예정 시작 시간 미접속 알림
$child_study_plan_start_no_access = $alarm_settings->where('alarm_type', 'child_study_plan_start_no_access')->first();
// 학습 예정 시작 시간 0분 후 알림
$child_study_plan_start_no_access_0 = $alarm_settings->where('alarm_type', 'child_study_plan_start_no_access_0')->first();
// 학습 예정 시작 시간 미접속 알림 10분후
$child_study_plan_start_no_access_10 = $alarm_settings->where('alarm_type', 'child_study_plan_start_no_access_10')->first();
// 학습 예정 시작 시간 미접속 알림 30분후
$child_study_plan_start_no_access_30 = $alarm_settings->where('alarm_type', 'child_study_plan_start_no_access_30')->first();
// 학습 예정 시작 시간 미접속 알림 1시간후
$child_study_plan_start_no_access_60 = $alarm_settings->where('alarm_type', 'child_study_plan_start_no_access_60')->first();

// 학습 시작 시간 알림
$child_study_start = $alarm_settings->where('alarm_type', 'child_study_start')->first();

// 학습 완료 알림
$child_study_complete = $alarm_settings->where('alarm_type', 'child_study_complete')->first();

// 평가일 알림
$child_eval_day = $alarm_settings->where('alarm_type', 'child_eval_day')->first();
// 평가일 알림 하루전
$child_eval_day_before = $alarm_settings->where('alarm_type', 'child_eval_day_before')->first();
// 평가일 알림 당일
$child_eval_day_today = $alarm_settings->where('alarm_type', 'child_eval_day_today')->first();

// 자녀가 평가를 종료 했을 때 알림
$child_eval_end = $alarm_settings->where('alarm_type', 'child_eval_end')->first();

@endphp
<style>
aside .active svg path{
    fill:white;
}
.checkbox input[type=checkbox]:checked + span {
    background-color: #FFBD19;
    border: #ffc746 solid 2px;
}
</style>
<div class="col pe-3 ps-3 mb-3 row position-relative">

    <div class="sub-title row mx-0 justify-content-between" data-board-main>
        <h2 class="text-sb-42px px-0">
            <img src="{{ asset('images/big_bell_icon.svg') }}" width="72">
            <span class="me-2">알림설정</span>
        </h2>
    </div>

    <div class="row mx-0">
        <aside class="col-3">
            <div class="rounded-4 modal-shadow-style">
                <ul class="tab py-4 px-3 ">
                    <li class="mb-2">
                        <button onclick="ptNotiAsideTab(this)" data-pt-noti-tab="1"
                            class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover active">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.942 15.4431V10.96C17.942 8.58769 16.3598 6.47692 14.04 5.68C13.8976 4.73231 13.037 4 12.0016 4C10.9663 4 10.1056 4.73231 9.96325 5.68C7.64017 6.47692 6.05801 8.58769 6.05801 10.96V15.4431C5.45297 15.5723 5 16.0862 5 16.6985C5 17.4092 5.60827 17.9846 6.35244 17.9846H9.31292C9.60411 19.1385 10.7009 20 12.0016 20C13.3023 20 14.3991 19.1385 14.6903 17.9846H17.6508C18.3982 17.9846 19.0032 17.4062 19.0032 16.6985C19.0032 16.0862 18.5503 15.5692 17.9452 15.4431H17.942Z" fill="#FFC747"/>
                            </svg>
                            이용 알림
                        </button>
                    </li>
                    <li class="mb-2">
                        <button onclick="ptNotiAsideTab(this)" data-pt-noti-tab="2"
                            class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.942 15.4431V10.96C17.942 8.58769 16.3598 6.47692 14.04 5.68C13.8976 4.73231 13.037 4 12.0016 4C10.9663 4 10.1056 4.73231 9.96325 5.68C7.64017 6.47692 6.05801 8.58769 6.05801 10.96V15.4431C5.45297 15.5723 5 16.0862 5 16.6985C5 17.4092 5.60827 17.9846 6.35244 17.9846H9.31292C9.60411 19.1385 10.7009 20 12.0016 20C13.3023 20 14.3991 19.1385 14.6903 17.9846H17.6508C18.3982 17.9846 19.0032 17.4062 19.0032 16.6985C19.0032 16.0862 18.5503 15.5692 17.9452 15.4431H17.942Z" fill="#FFC747"/>
                            </svg>
                            학습 관련 알림
                        </button>
                    </li>
                    <li class="">
                        <button onclick="ptNotiAsideTab(this)" data-pt-noti-tab="3"
                            class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.942 15.4431V10.96C17.942 8.58769 16.3598 6.47692 14.04 5.68C13.8976 4.73231 13.037 4 12.0016 4C10.9663 4 10.1056 4.73231 9.96325 5.68C7.64017 6.47692 6.05801 8.58769 6.05801 10.96V15.4431C5.45297 15.5723 5 16.0862 5 16.6985C5 17.4092 5.60827 17.9846 6.35244 17.9846H9.31292C9.60411 19.1385 10.7009 20 12.0016 20C13.3023 20 14.3991 19.1385 14.6903 17.9846H17.6508C18.3982 17.9846 19.0032 17.4062 19.0032 16.6985C19.0032 16.0862 18.5503 15.5692 17.9452 15.4431H17.942Z" fill="#FFC747"/>
                            </svg>
                            평가 관련 알림
                        </button>
                    </li>
                </ul>
            </div>
        </aside>
        <div class="col">
            {{-- 이용알림 --}}
            <section data-pt-noti-section="1">
                <div class="modal-shadow-style rounded row mx-0 p-4 mb-4" data-row>
                    <div class="col py-2 scale-text-gray_05">
                        <span class="text-sb-24px " data-name>자녀 로그인 시 알림</span>
                    </div>
                    <div class="col-auto py-2">
                        <label class="toggle">
                            <input onchange="ptNotiCheckboxChange(this)" type="checkbox" data-alarm-type="child_login" data-alarm-group="child_login" data-alarm-name="자녀 로그인 시 알림"
                                class=""  {{ $child_login && $child_login->alarm_value == 'Y' ? 'checked' : '' }} >
                            <span class=""></span>
                        </label>
                    </div>
                </div>

                <div class="modal-shadow-style rounded row mx-0 p-4 mb-4" data-row>
                    <div class="col py-2 scale-text-gray_05">
                        <span class="text-sb-24px " data-name>자녀 로그아웃 시 알림</span>
                    </div>
                    <div class="col-auto py-2">
                        <label class="toggle">
                            <input onchange="ptNotiCheckboxChange(this)" type="checkbox" data-alarm-type="child_logout" data-alarm-group="child_logout" data-alarm-name="자녀 로그아웃 시 알림"
                                class="" {{ $child_logout && $child_logout->alarm_value == 'Y' ? 'checked' : '' }}>
                            <span class=""></span>
                        </label>
                    </div>
                </div>
            </section>

            {{-- 학습 관련 알림 --}}
            <section data-pt-noti-section="2" hidden>

                <div class="modal-shadow-style rounded row mx-0 p-4 mb-4" data-row>
                    <div class="col py-2 scale-text-gray_05 h-center gap-3" >
                        <span class="text-sb-24px " data-name>학습 예정 시간 알림</span>
                        <span class="h-center gap-2 text-sb-20px">
                            <label class="checkbox mt-1">
                                <input onchange="ptNotiCheckboxChange(this)" type="checkbox" data-alarm-type="child_study_plan_start_prev_0" data-alarm-group="child_study_plan_start" data-alarm-name="학습 시작 시간 0 분전 알림"
                                    class="" onchange=""  {{ $child_study_plan_start_prev_0 && $child_study_plan_start_prev_0->alarm_value == 'Y' ? 'checked' : '' }} >
                                <span class="">
                                </span>
                            </label>
                            학습 시작 시간
                        </span>
                        <span class="h-center gap-2 text-sb-20px">
                            <label class="checkbox mt-1">
                                <input onchange="ptNotiCheckboxChange(this)" type="checkbox"  data-alarm-type="child_study_plan_start_prev_10" data-alarm-group="child_study_plan_start_prev" data-alarm-name="학습 시작 시간 10 분전 알림"
                                    class="" onchange="" {{ $child_study_plan_start_prev_10 && $child_study_plan_start_prev_10->alarm_value == 'Y' ? 'checked' : '' }} >
                                <span class="">
                                </span>
                            </label>
                            10분 전
                        </span>
                        <span class="h-center gap-2 text-sb-20px">
                            <label class="checkbox mt-1">
                                <input onchange="ptNotiCheckboxChange(this)" type="checkbox"  data-alarm-type="child_study_plan_start_prev_30" data-alarm-group="child_study_plan_start_prev" data-alarm-name="학습 시작 시간 30 분전 알림"
                                    class="" onchange="" {{ $child_study_plan_start_prev_30 && $child_study_plan_start_prev_30->alarm_value == 'Y' ? 'checked' : '' }} >
                                <span class="">
                                </span>
                            </label>
                            30분 전
                        </span>
                        <span class="h-center gap-2 text-sb-20px">
                            <label class="checkbox mt-1">
                                <input onchange="ptNotiCheckboxChange(this)" type="checkbox" data-alarm-type="child_study_plan_start_prev_60" data-alarm-group="child_study_plan_start_prev" data-alarm-name="학습 시작 시간 1 시간전 알림"
                                    class="" onchange="" {{ $child_study_plan_start_prev_60 && $child_study_plan_start_prev_60->alarm_value == 'Y' ? 'checked' : '' }} >
                                <span class="">
                                </span>
                            </label>
                            1시간 전
                        </span>
                    </div>
                    <div class="col-auto py-2">
                        <label class="toggle">
                            <input onchange="ptNotiCheckboxChange(this)" type="checkbox" data-alarm-type="child_study_plan_start" data-alarm-group="child_study_plan_start" data-alarm-name="학습 시작 시간 알림"
                                class="" {{ $child_study_plan_start && $child_study_plan_start->alarm_value == 'Y' ? 'checked' : '' }} >
                            <span class=""></span>
                        </label>
                    </div>
                </div>
                <div class="modal-shadow-style rounded row mx-0 p-4 mb-4" data-row>
                    <div class="col py-2 scale-text-gray_05 h-center gap-3">
                        <span class="text-sb-24px " data-name>학습 예정 시간 미접속 시 알림</span>

                        <span class="h-center gap-2 text-sb-20px">
                            <label class="checkbox mt-1">
                                <input onchange="ptNotiCheckboxChange(this)" type="checkbox" data-alarm-type="child_study_plan_start_no_access_0" data-alarm-group="child_study_plan_start_no_access" data-alarm-name="학습 시작 시간 미접속 0 분후 알림"
                                    class="" onchange="" {{ $child_study_plan_start_no_access_0 && $child_study_plan_start_no_access_0->alarm_value == 'Y' ? 'checked' : '' }} >
                                <span class="">
                                </span>
                            </label>
                            학습 시작 시간
                        </span>
                        <span class="h-center gap-2 text-sb-20px">
                            <label class="checkbox mt-1">
                                <input onchange="ptNotiCheckboxChange(this)" type="checkbox" data-alarm-type="child_study_plan_start_no_access_10" data-alarm-group="child_study_plan_start_no_access" data-alarm-name="학습 시작 시간 미접속 10 분후 알림"
                                    class="" onchange="" {{ $child_study_plan_start_no_access_10 && $child_study_plan_start_no_access_10->alarm_value == 'Y' ? 'checked' : '' }} >
                                <span class="">
                                </span>
                            </label>
                            10분 후
                        </span>
                        <span class="h-center gap-2 text-sb-20px">
                            <label class="checkbox mt-1">
                                <input onchange="ptNotiCheckboxChange(this)" type="checkbox" data-alarm-type="child_study_plan_start_no_access_30" data-alarm-group="child_study_plan_start_no_access" data-alarm-name="학습 시작 시간 미접속 30 분후 알림"
                                    class="" onchange="" {{ $child_study_plan_start_no_access_30 && $child_study_plan_start_no_access_30->alarm_value == 'Y' ? 'checked' : '' }} >
                                <span class="">
                                </span>
                            </label>
                            30분 후
                        </span>
                        <span class="h-center gap-2 text-sb-20px">
                            <label class="checkbox mt-1">
                                <input onchange="ptNotiCheckboxChange(this)" type="checkbox" data-alarm-type="child_study_plan_start_no_access_60" data-alarm-group="child_study_plan_start_no_access" data-alarm-name="학습 시작 시간 미접속 1 시간후 알림"
                                    class="" onchange="" {{ $child_study_plan_start_no_access_60 && $child_study_plan_start_no_access_60->alarm_value == 'Y' ? 'checked' : '' }} >
                                <span class="">
                                </span>
                            </label>
                            1시간 후
                        </span>
                    </div>
                    <div class="col-auto py-2">
                        <label class="toggle">
                            <input onchange="ptNotiCheckboxChange(this)" type="checkbox" data-alarm-type="child_study_plan_start_no_access" data-alarm-group="child_study_plan_start_no_access" data-alarm-name="학습 시작 시간 미접속 알림"
                                class="" {{ $child_study_plan_start_no_access && $child_study_plan_start_no_access->alarm_value == 'Y' ? 'checked' : '' }} >
                            <span class=""></span>
                        </label>
                    </div>
                </div>
                <div class="modal-shadow-style rounded row mx-0 p-4 mb-4" data-row="">
                    <div class="col py-2 scale-text-gray_05" >
                        <span class="text-sb-24px " data-name="">학습 시작 시간 알림</span>
                    </div>
                    <div class="col-auto py-2">
                        <label class="toggle">
                            <input onchange="ptNotiCheckboxChange(this)" type="checkbox" data-alarm-type="child_study_start" data-alarm-group="child_study_start" data-alarm-name="학습 시작 시간 알림"
                                class="" {{ $child_study_start && $child_study_start->alarm_value == 'Y' ? 'checked' : '' }} >
                            <span class=""></span>
                        </label>
                    </div>
                </div>
                <div class="modal-shadow-style rounded row mx-0 p-4 mb-4" data-row="">
                    <div class="col py-2 scale-text-gray_05">
                        <span class="text-sb-24px " data-name="">학습 완료 했을 때 알림</span>
                    </div>
                    <div class="col-auto py-2">
                        <label class="toggle">
                            <input onchange="ptNotiCheckboxChange(this)" type="checkbox" data-alarm-type="child_study_complete" data-alarm-group="child_study_complete" data-alarm-name="학습 완료 했을 때 알림"
                                class="" {{ $child_study_complete && $child_study_complete->alarm_value == 'Y' ? 'checked' : '' }} >
                            <span class=""></span>
                        </label>
                    </div>
                </div>
            </section>

            {{-- 평가 관련 알림 --}}
            <section data-pt-noti-section="3" hidden>

                <div class="modal-shadow-style rounded row mx-0 p-4 mb-4" data-row="">
                    <div class="col py-2 scale-text-gray_05 h-center gap-3">
                        <span class="text-sb-24px " data-name>평가일 알림</span>
                        <span class="h-center gap-2 text-sb-20px">
                            <label class="checkbox mt-1">
                                <input onchange="ptNotiCheckboxChange(this)" type="checkbox" data-alarm-type="child_eval_day_before" data-alarm-group="child_eval_day" data-alarm-name="평가일 알림 - 하루전"
                                    class="" onchange="" {{ $child_eval_day_before && $child_eval_day_before->alarm_value == 'Y' ? 'checked' : '' }} >
                                <span class="">
                                </span>
                            </label>
                            평가 하루 전
                        </span>
                        <span class="h-center gap-2 text-sb-20px">
                            <label class="checkbox mt-1">
                                <input onchange="ptNotiCheckboxChange(this)" type="checkbox" data-alarm-type="child_eval_day_today" data-alarm-group="child_eval_day" data-alarm-name="평가일 알림 - 당일"
                                    class="" onchange="" {{ $child_eval_day_today && $child_eval_day_today->alarm_value == 'Y' ? 'checked' : '' }} >
                                <span class="">
                                </span>
                            </label>
                            평가 당일
                        </span>
                    </div>
                    <div class="col-auto py-2">
                        <label class="toggle">
                            <input onchange="ptNotiCheckboxChange(this)" type="checkbox" data-alarm-type="child_eval_day" data-alarm-group="child_eval_day" data-alarm-name="평가일 알림"
                                class="" {{ $child_eval_day && $child_eval_day->alarm_value == 'Y' ? 'checked' : '' }} >
                            <span class=""></span>
                        </label>
                    </div>
                </div>
                <div class="modal-shadow-style rounded row mx-0 p-4 mb-4" data-row>
                    <div class="col py-2 scale-text-gray_05">
                        <span class="text-sb-24px " data-name>자녀가 평가를 종료 했을 때 알림</span>
                    </div>
                    <div class="col-auto py-2">
                        <label class="toggle">
                            <input onchange="ptNotiCheckboxChange(this)" type="checkbox" data-alarm-type="child_eval_end" data-alarm-group="child_eval_end" data-alarm-name="자녀가 평가를 종료 했을 때 알림"
                                class="" {{ $child_eval_end && $child_eval_end->alarm_value == 'Y' ? 'checked' : '' }} >
                            <span class=""></span>
                        </label>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>


<div data-explain="160">
    <div class="py-lg-5"></div>
    <div class="py-lg-4"></div>
    <div class="pt-lg-3"></div>
</div>

<script>

document.addEventListener("DOMContentLoaded", function(){
    ptNotiDataNameChk();
});

// 알림 이름 색 활성화.
function ptNotiDataNameChk(){
    document.querySelectorAll('input[data-alarm-type]').forEach(function(el){
        if(el.dataset.alarmType == el.dataset.alarmGroup) {
            if(el.checked)
                el.closest('[data-row]').querySelector('[data-name]').classList.add('text-black');
            else
                el.closest('[data-row]').querySelector('[data-name]').classList.remove('text-black');
        }
    });
}
// Aside Tab Click
function ptNotiAsideTab(vthis){
    // data-pt-noti-point-main-tab 모두 해제
    document.querySelectorAll('[data-pt-noti-tab]').forEach(function(el){
        el.classList.remove('active');
    });
    vthis.classList.add('active');
    const type = vthis.dataset.ptNotiTab;
    // tab에 맞는 section 보여주기
    ptNotiSectionShow(type)
}

// section hidden
function ptNotiSectionShow(type){
    document.querySelectorAll('[data-pt-noti-section]').forEach(function(el){
        el.hidden = true;
    });
    document.querySelector(`[data-pt-noti-section="${type}"]`).hidden = false;
}

// 체크 박스 체크시
function ptNotiCheckboxChange(vthis){
    const alarm_value = vthis.checked ? 'Y':'N';
    const alarm_group = vthis.dataset.alarmGroup;
    const alarm_type = vthis.dataset.alarmType;
    const alarm_name = vthis.dataset.alarmName;

    const page = '/parent/noti/settings/alarm/insert';
    const parameter = {
        alarm_value: alarm_value,
        alarm_group: alarm_group,
        alarm_type: alarm_type,
        alarm_name:alarm_name,
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            ptNotiDataNameChk();
            toast('알림 설정이 변경되었습니다.');
        }else{}
    });
}
</script>
@endsection
