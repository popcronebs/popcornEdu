@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '학습포인트')

@section('add_css_js')
    <link href="{{ asset('css/reset.css?5') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://fastly.jsdelivr.net/fullcalendar/1.6.1/fullcalendar.css">
    <script src='https://fastly.jsdelivr.net/npm/@fullcalendar/core@6.1.11/index.global.min.js'></script>
    <script src='https://fastly.jsdelivr.net/npm/@fullcalendar/web-component@6.1.11/index.global.min.js'></script>
    <script src="https://fastly.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/1.6.4/fullcalendar.min.js"></script>
    <link href="{{ asset('css/daterangepicker.css?1') }}" rel="stylesheet">
    <script src="{{ asset('js/momnet.js?1') }}"></script>
    <script src="{{ asset('js/jquery.daterangepicker.js?1') }}"></script>

@endsection

<!-- TODO: 수강지수, 활동지수 점수 어떻게 측정을 해야하는지 확인필요. -->
<!-- TODO: 게시판은 어디서 적고, 댓글을 다는지에 대해서도 확인이 필요하다. -->
<!-- TODO: 학습포인트 순위의 주기가 언제인지 확인필요. 1달? / 답변: 1주 / crontab 으로 저장 진행. -->
<!-- TODO: 사용포인트 > 포인트를 어디에 사용하는지 지금은 알수가 없음. -->
<!-- TODO: 소멸포인트 > 언제 소멸이 되는지에 대한 확인이 필요. -->

{{-- 학생 컨텐츠 --}}
@section('layout_coutent')
<style>
.div_stupoint_middle_board{
    height: 198px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.div_stupoint_middle_board.hpx-174{
    height: 174px;
}
</style>
<div class="col mx-0 mb-3 pt-5 row position-relative">
    {{-- 상단 --}}
    <article class="pt-5 px-0">
        <div class="row pt-4 pb-4">
            <div class="col-auto pb-3">
                <div class="h-center">
                    <img src="{{ asset('images/graphic_studypoint.svg?1') }}" width="72">
                    <span class="cfs-1 fw-semibold align-middle">학습포인트</span>
                </div>
                <div class="pt-2" hidden>
                    <span class="cfs-3 fw-medium">미정</span>
                </div>
            </div>
            <div class="col text-end position-relative">
                <img class="position-absolute bottom-0 end-0" src="{{ asset('images/character_pig_study_point.svg') }}"
                    width="206">
            </div>
        </div>
    </article>

    {{-- pdding 80px --}}
    <div>
        <div class="pt-lg-5"></div>
        <div class="py-lg-3"></div>
    </div>

    <article>
        <div class="row gx-4">
            <aside class="col-lg-3 ps-0">
                {{-- :aside in tab --}}
                <div class="rounded-4 modal-shadow-style">
                    <ul class="tab py-4 px-3 ">
                        <li class="mb-2">
                            <button onclick="studyPointAsideTab(this)" data-btn-study-point-main-tab="1"
                                class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                                <img src="{{ asset('images/ranking1_icon.svg') }}" width="32" class="me-2">
                                학습랭킹
                            </button>
                        </li>
                        <li class="mb-2">
                            <button onclick="studyPointAsideTab(this)" data-btn-study-point-main-tab="2"
                                class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                                <img src="{{ asset('images/ranking2_icon.svg') }}" width="32" class="me-2">
                                학습포인트 순위
                            </button>
                        </li>
                        <li class="">
                            <button onclick="studyPointAsideTab(this)" data-btn-study-point-main-tab="3"
                                class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                                <img src="{{ asset('images/point_ranking_icon.svg') }}" width="32" class="me-2">
                                학습포인트 소개
                            </button>
                        </li>
                    </ul>
                </div>

                {{-- aside in 달력 --}}
                <div class="modal-shadow-style p-4 mt-4" data-btn-study-point-main-aside="0">
                    <div class="modal d-block position-static">
                        <div class="modal-dialog rounded">
                            <div class="modal-content border-none rounded p-3 modal-shadow-style">
                                <div class="modal-header border-bottom-0">
                                    <h1 class="modal-title fs-5 text-b-24px" id="">
                                        범위선택 달력
                                    </h1>
                                    <button type="button" class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close" style="width:32px;height: 32px;"></button>
                                </div>
                                <div class="modal-body p-0">
                                    <input id="date-range53" class="d-none" size="30" type="" value=""> <!-- 꼭있어야됨 본체임 -->
                                    <div id="date-range12-container"></div> <!-- 달력 보이게하는겁니다. -->
                                    <div class="px-3 pb-3">
                                        <div class="border-bottom pt-4 mb-4"></div>
                                        <p class="text-sb-20px mb-2 gray-color">요일 선택</p>
                                        <div class="row w-100">
                                            <div class="col-6 ps-0 pe-1">
                                                <label class="label-input-wrap w-100">
                                                    <input type="text" class="modal-input border-gray rounded text-sb-18px w-100 px-2" placeholder="" id="startDate">
                                                </label>
                                            </div>
                                            <div class="col-6 ps-1 pe-0">
                                                <label class="label-input-wrap w-100">
                                                    <input type="text" class="modal-input border-gray rounded text-sb-18px w-100 px-2" placeholder="" id="lastDate">
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- aside in 현재 포인트 --}}
                <div class="modal-shadow-style p-4 mt-4" data-btn-study-point-main-aside="1" hidden>
                    <div class="row pt-2">
                        <div class="col h-center">
                            <span class="text-sb-24px">현재 포인트</span>
                        </div>
                        <div class="col-auto">
                            <button class="btn p-0 h-center">
                                <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32">
                            </button>
                        </div>
                    </div>
                    <div class="py-4 mb-1">
                        <span class="text-b-42px text-primary-y">{{$student ? number_format($student->point_now) : ''}}</span>
                        <span class="text-b-42px text-primary-y">p</span>
                    </div>
                    <div class="pt-4 text-center">
                        <button type="button" onclick="studyPointHistoryPage();"
                            class="btn-lg-primary text-b-24px rounded-2 scale-text-white w-100 w-center">내역 보러가기</button>
                    </div>
                </div>

                {{-- aside in 달력 --}}
                <div>

                </div>
            </aside>
            {{-- 기본:미선택 --}}
            <div class="col pe-0" data-btn-study-point-main-sub="0"  >
                <section>
                    {{-- 현재 포인트, 사용한 포인트, 소멸된 포인트 --}}
                    <div class="d-flex gap-4">
                        {{-- 현재 포인트 --}}
                        <div class="col-lg ">
                            <div class="div_stupoint_middle_board hpx-174 rounded-3 modal-shadow-style p-4">
                                <div class="d-flex">
                                    <div class="col">
                                        <span class="text-sb-24px">현재 포인트</span>
                                    </div>
                                </div>
                                {{-- padding 62/64 --}}
                                <div>
                                    <div class="py-lg-4"></div>
                                    <div class="pt-lg-3"></div>
                                </div>
                                <div class="d-flex">
                                    <div class="col">
                                        <span class="text-b-42px text-primary-y">{{$student ? number_format($student->point_now) : ''}}</span>
                                        <span class="text-r-20px text-primary-y">P</span>
                                    </div>
                                    <div class="col-auto position-relative">
                                        <img src="{{ asset('images/point_ranking_icon2.svg') }}" width="80" class="position-absolute bottom-0 end-0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- 사용한 포인트 --}}
                        <div class="col-lg ">
                            <div class="div_stupoint_middle_board hpx-174 rounded-3 modal-shadow-style p-4">
                                <div class="d-flex">
                                    <div class="col">
                                        <span class="text-sb-24px">사용한 포인트</span>
                                    </div>
                                </div>
                                {{-- padding 62/64 --}}
                                <div>
                                    <div class="py-lg-4"></div>
                                    <div class="pt-lg-3"></div>
                                </div>
                                <div class="d-flex">
                                    <div class="col">
                                        <!-- TODO: -->
                                        <span class="text-b-42px text-primary-y">0</span>
                                        <span class="text-r-20px text-primary-y">P</span>
                                    </div>
                                    <div class="col-auto position-relative">
                                        <img src="{{ asset('images/point_ranking_icon2.svg') }}" width="80" class="position-absolute bottom-0 end-0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- 소멸된 포인트 --}}
                        <div class="col-lg ">
                            <div class="div_stupoint_middle_board hpx-174 rounded-3 modal-shadow-style p-4">
                                <div class="d-flex">
                                    <div class="col">
                                        <span class="text-sb-24px">소멸된 포인트</span>
                                    </div>
                                </div>
                                {{-- padding 62/64 --}}
                                <div>
                                    <div class="py-lg-4"></div>
                                    <div class="pt-lg-3"></div>
                                </div>
                                <div class="d-flex">
                                    <div class="col">
                                        <!-- TODO: -->
                                        <span class="text-b-42px text-primary-y">0</span>
                                        <span class="text-r-20px text-primary-y">P</span>
                                    </div>
                                    <div class="col-auto position-relative">
                                        <img src="{{ asset('images/point_ranking_icon2.svg') }}" width="80" class="position-absolute bottom-0 end-0">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>
                {{-- 52 --}}
                <section class="pt-5 mt-1">
                    <div class="text-end">
                        <span class="text-sb-20px">적립 후 1년이 지난포인트</span>
                        <span class="text-sb-20px text-danger">는 자동 소멸됩니다.</span>
                        <span class="text-sb-20px">(매월 밤 12시 기준)</span>
                    </div>
                    {{-- 적용 리스트 테이블 --}}
                    <div class="mt-4">
                        <table class="table">
                            <colgroup>
                            </colgroup>
                            <thead class="modal-shadow-style text-b-20px scale-text-gray_05">
                                <tr class="">
                                    <td class="text-center ctext-gc1-imp">적용 날짜</td>
                                    <td class="text-center ctext-gc1-imp p-4">부여</td>
                                    <td class="text-center ctext-gc1-imp p-4">사용포인트</td>
                                </tr>
                            </thead>
                            <tbody class="text-b-20px" data-bundle="point_history">
                                <tr class="border-bottom" data-row="copy" hidden>
                                    <td class="text-center ctext-gc1-imp bg-transparent py-5" data-created-at></td>
                                    <td class="text-center ctext-gc1-imp bg-transparent py-5" data-create></td>
                                    <td class="text-center text-danger bg-transparent py-5">
                                        <span class="text-danger" data-point>0</span>포인트
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            {{-- 학습랭킹 --}}
            <div class="col pe-0" data-btn-study-point-main-sub="1" hidden>
                {{-- 현재등급, 다음등급, 내순위, 학교순위 --}}
                <section>
                    <div class="row gx-4 mx-0">
                        {{-- 현재등급 --}}
                        <div class="col-4 ps-0  ">
                            <div class="div_stupoint_middle_board modal-shadow-style rounded-3 p-4">
                                <div class="d-flex">
                                    <div class="col">
                                        <span class="text-sb-24px">현재 등급</span>
                                    </div>
                                    <div class="col-auto">
                                        <img src="{{ asset('images/gray_cir_manus.svg') }}" width="">
                                        <img src="{{ asset('images/gray_cir_plus.svg') }}" width="" hidden>
                                    </div>
                                </div>
                                <div class="mt-1">
                                    {{-- sp18 현재 등급은 [2단계] 입니다. --}}
                                    <span class="text-b-18px scale-text-gray_05">현재 등급은</span>
                                    <span class="text-b-18px text-danger">{{$point_grade ? $point_grade : '0'}}</span>
                                    <span class="text-b-18px text-danger">단계</span>
                                    <span class="text-b-18px scale-text-gray_05">입니다.</span>
                                </div>
                                <div class="text-end">
                                    {{-- img78 lv1 --}}
                                    <img src="{{ asset('images/rank_character_lv'.($point_grade ? $point_grade : '1').'.svg') }}" height="78">
                                </div>
                            </div>
                        </div>

                        {{-- 다음등급 --}}
                        <div class="col-lg">
                            <div class="div_stupoint_middle_board ounded-3 modal-shadow-style p-4">
                                <div class="d-flex">
                                    <div class="col">
                                        <span class="text-sb-24px">다음 등급</span>
                                    </div>
                                    <div class="col-auto">
                                        <img src="{{ asset('images/gray_cir_manus.svg') }}" width="" hidden>
                                        <img src="{{ asset('images/gray_cir_plus.svg') }}" width="" >
                                    </div>
                                </div>
                                <div>
                                    <div class="py-lg-3"></div>
                                </div>
                                <div class="text-end mt-2">
                                    {{-- img78 lv1 --}}
                                    <img src="{{ asset('images/rank_character_lv'.($point_grade ? $point_grade+1 : '1').'.svg') }}" height="78">
                                </div>
                            </div>
                        </div>

                        {{-- 내 순위 --}}
                        <div class="col-lg ">
                            <div class="div_stupoint_middle_board rounded-3 modal-shadow-style p-4">
                                <div class="d-flex">
                                    <div class="col">
                                        <span class="text-sb-24px">내 순위</span>
                                    </div>
                                    <div class="col-auto">
                                        <img src="{{ asset('images/gray_cir_manus.svg') }}" width="" hidden>
                                        <img src="{{ asset('images/gray_cir_plus.svg') }}" width="">
                                    </div>
                                </div>
                                {{-- padding 62/64 --}}
                                <div>
                                    <div class="py-lg-4"></div>
                                    <div class="pt-lg-3"></div>
                                </div>
                                <div class="text-end">
                                    <div>
                                        <span class="text-b-42px text-primary-y">{{$my_rank ? $my_rank : '0'}}</span>
                                        <span class="text-r-20px text-primary-y">등</span>
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{-- 우리 학교 순위? --}}
                        <div class="col-lg pe-0">
                            <div class="div_stupoint_middle_board rounded-3 modal-shadow-style p-4">
                                <div class="d-flex">
                                    <div class="col">
                                        <span class="text-sb-24px">우리 학교 순위</span>
                                    </div>
                                    <div class="col-auto">
                                        <img src="{{ asset('images/gray_cir_manus.svg') }}" width="" hidden>
                                        <img src="{{ asset('images/gray_cir_plus.svg') }}" width="">
                                    </div>
                                </div>
                                {{-- padding 62/64 --}}
                                <div>
                                    <div class="py-lg-4"></div>
                                    <div class="pt-lg-3"></div>
                                </div>
                                <div class="text-end">
                                    <div>
                                        <span class="text-b-42px text-primary-y">-</span>
                                        <span class="text-r-20px text-primary-y">등</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                {{-- 현재 학습 포인트 --}}
                <section class="mt-4">
                    <div class="modal-shadow-style rounded-3 p-4">
                        {{-- 32, 24 --}}
                        <div class="py-2 h-center">
                            <img src="{{ asset('images/point_ranking_icon.svg') }}" width="32">
                            <span class="text-sb-24px ms-2">현재 학습 포인트</span>
                        </div>

                        <div class="py-4" data-div-now-study-point-bundle >
                            {{-- 수강지수, 수강시간, 수강완료, 학습방 담기 --}}
                            <div class="d-flex gap-2 mb-1">
                                {{-- 수강지수 --}}
                                <div class="col-lg-3 d-flex p-4 rounded-3 scale-bg-gray_01">
                                    <div class="col">
                                        <span class="text-b-20px scale-text-gray_05">수강 지수</span>
                                    </div>
                                    <div class="col-auto">
                                        <!-- NOTE: 점수를 어덯게 측정을 하는지? 확인필요 -->
                                        <span class="bg-danger text-white rounded-pill px-4 py-2 text-sb-20px">?</span>
                                    </div>
                                </div>
                                {{-- 활동지수 --}}
                                <div class="col-lg d-flex p-4 rounded-3 scale-bg-gray_01 ms-1">
                                    <div class="col d-flex">
                                        <div class="col">
                                            <span class="text-b-20px scale-text-gray_05">목표 시간 출석</span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="text-b-20px">0회</span>
                                        </div>
                                    </div>
                                    <div class="px-4 h-center">
                                        <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12">
                                    </div>
                                    <div class="col d-flex">
                                        <div class="col">
                                            <span class="text-b-20px scale-text-gray_05">수강 완료</span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="text-b-20px">{{$total_study_count ? $total_study_count:'0'}}</span>
                                            <span class="text-b-20px">회</span>
                                        </div>
                                    </div>
                                    <div class="px-4 h-center">
                                        <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12">
                                    </div>
                                    <div class="col d-flex">
                                        <div class="col">
                                            <span class="text-b-20px scale-text-gray_05">오답노트 완료</span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="text-b-20px">?</span>
                                            <span class="text-b-20px">회</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- 활동지수, 로그인, 게시글작성, 댓글작성,  --}}
                            <div class="d-flex gap-2 mt-2">
                                {{-- 수강지수 --}}
                                <div class="col-lg-3 d-flex p-4 rounded-3 scale-bg-gray_01">
                                    <div class="col">
                                        <span class="text-b-20px scale-text-gray_05">활동 지수</span>
                                    </div>
                                    <div class="col-auto">
                                        <!-- NOTE: 활동지수 점수를 어덯게 처리하는지? -->
                                        <span class="bg-danger text-white rounded-pill px-4 py-2 text-sb-20px">?</span>
                                    </div>
                                </div>
                                {{-- 활동지수 --}}
                                <div class="col-lg d-flex p-4 rounded-3 scale-bg-gray_01 ms-1">
                                    <div class="col d-flex">
                                        <div class="col">
                                            <span class="text-b-20px scale-text-gray_05">로그인</span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="text-b-20px">{{$student ? $student->login_cnt:'0'}}회</span>
                                        </div>
                                    </div>
                                    <div class="px-4 h-center">
                                        <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12">
                                    </div>
                                    <div class="col d-flex">
                                        <div class="col">
                                            <span class="text-b-20px scale-text-gray_05">학부모 응원메시지</span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="text-b-20px">0</span>
                                            <span class="text-b-20px">회</span>
                                        </div>
                                    </div>
                                    <div class="px-4 h-center">
                                        <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12">
                                    </div>
                                    <div class="col d-flex">
                                        <div class="col">
                                            <span class="text-b-20px scale-text-gray_05">이벤트 참여</span>
                                        </div>
                                        <div class="col-auto">
                                            <span class="text-b-20px">0</span>
                                            <span class="text-b-20px">회</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- img32, sp24sb --}}
                    {{-- 32 --}}
                    {{-- div/row/--}}
                    {{-- div/col3/pe1/scale-text-gray_05 --}}
                    {{-- div/col/ps2 --}}
                </section>
                {{-- 이번달 학습 포인트 --}}
                <section class="mt-4">
                    <div class="modal-shadow-style rounded-3 p-4">
                        {{-- 32, 24 --}}
                        <div class="py-2 h-center">
                            <div class="col h-center">
                                <img src="{{ asset('images/point_ranking_icon.svg') }}" width="32">
                                <span class="text-sb-24px ms-2">이번달 학습 포인트</span>
                            </div>
                            <div class="col-auto scale-text-gray_05 text-sb-20px">
                                기간:
                                <span class="scale-text-gray_05 text-sb-20px">{{ date('Y.m.01', strtotime('first day of this month')) }}</span>
                                ~
                                <span class="scale-text-gray_05 text-sb-20px">
                                    {{ date('Y.m.d', strtotime('last day of this month')) }}</span>
                            </div>
                        </div>

                        <div class="d-flex gap-1">
                            <div class="col-2 pe-1">
                                <div class="rounded-3 py-4 h-100 d-flex flex-column">
                                    <div class="rounded-top-3 main-bg py-2">
                                        <span class="text-white text-b-20px py-1 d-block text-center">학습지수</span>
                                    </div>
                                    <div class="rounded-bottom-3 primary-bg-bg col all-center">
                                        <span class="text-b-42px text-primary-y" style="font-size:38px">?</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="py-4" data-div-now-study-point-bundle >
                                    {{-- 수강지수, 수강시간, 수강완료, 오답노트 완료 --}}
                                    <div class="d-flex gap-2 mb-1">
                                        {{-- 수강지수 --}}
                                        <div class="col-lg-3 d-flex p-4 rounded-3 scale-bg-gray_01">
                                            <div class="col">
                                                <span class="text-b-20px scale-text-gray_05">수강 지수</span>
                                            </div>
                                            <div class="col-auto">
                                                <span class="bg-danger text-white rounded-pill px-4 py-2 text-sb-20px">?</span>
                                            </div>
                                        </div>
                                        {{-- 활동지수 --}}
                                        <div class="col-lg d-flex p-4 rounded-3 scale-bg-gray_01 ms-1">
                                            <div class="col d-flex">
                                                <div class="col">
                                                    <span class="text-b-20px scale-text-gray_05">목표 시간 출석</span>
                                                </div>
                                                <div class="col-auto">
                                                    <span class="text-b-20px">0회</span>
                                                </div>
                                            </div>
                                            <div class="px-4 h-center">
                                                <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12">
                                            </div>
                                            <div class="col d-flex">
                                                <div class="col">
                                                    <span class="text-b-20px scale-text-gray_05">수강 완료</span>
                                                </div>
                                                <div class="col-auto">
                                                    <span class="text-b-20px">0</span>
                                                    <span class="text-b-20px">회</span>
                                                </div>
                                            </div>
                                            <div class="px-4 h-center">
                                                <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12">
                                            </div>
                                            <div class="col d-flex">
                                                <div class="col">
                                                    <span class="text-b-20px scale-text-gray_05">오답노트 완료</span>
                                                </div>
                                                <div class="col-auto">
                                                    <span class="text-b-20px">?</span>
                                                    <span class="text-b-20px">회</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- 활동지수, 로그인, 게시글작성, 댓글작성,  --}}
                                    <div class="d-flex gap-2 mt-2">
                                        {{-- 수강지수 --}}
                                        <div class="col-lg-3 d-flex p-4 rounded-3 scale-bg-gray_01">
                                            <div class="col">
                                                <span class="text-b-20px scale-text-gray_05">활동 지수</span>
                                            </div>
                                            <div class="col-auto">
                                                <span class="bg-danger text-white rounded-pill px-4 py-2 text-sb-20px">?</span>
                                            </div>
                                        </div>
                                        {{-- 활동지수 --}}
                                        <div class="col-lg d-flex p-4 rounded-3 scale-bg-gray_01 ms-1">
                                            <div class="col d-flex">
                                                <div class="col">
                                                    <span class="text-b-20px scale-text-gray_05">로그인</span>
                                                </div>
                                                <div class="col-auto">
                                                    <span class="text-b-20px">{{$month_login_cnt ? $month_login_cnt:'0'}}회</span>
                                                </div>
                                            </div>
                                            <div class="px-4 h-center">
                                                <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12">
                                            </div>
                                            <div class="col d-flex">
                                                <div class="col">
                                                    <span class="text-b-20px scale-text-gray_05">학부모 응원메시지</span>
                                                </div>
                                                <div class="col-auto">
                                                    <span class="text-b-20px">0</span>
                                                    <span class="text-b-20px">회</span>
                                                </div>
                                            </div>
                                            <div class="px-4 h-center">
                                                <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12">
                                            </div>
                                            <div class="col d-flex">
                                                <div class="col">
                                                    <span class="text-b-20px scale-text-gray_05">이벤트 참여</span>
                                                </div>
                                                <div class="col-auto">
                                                    <span class="text-b-20px">0</span>
                                                    <span class="text-b-20px">회</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
            {{-- 학습포인트 순위 --}}
            <div class="col pe-0" data-btn-study-point-main-sub="2" hidden>
                <section>
                    <div class="d-flex gap-2 mb-2">
                        <span class="text-sb-24px">학습 포인트 순위</span>
                        <button class="btn p-0 h-center">
                            <img src="https://sdang.acaunion.com/images/yellow_round_arrow.svg" width="24">
                        </button>
                    </div>
                    {{-- 나의학습순위, 등급, 이름, 학습지수, 순위변동 --}}
                    <div class="mt-4">
                        <table class="table">
                            {{-- 길이 조정 --}}
                            <colgroup>
                            </colgroup>
                            <thead class="modal-shadow-style text-b-20px scale-text-gray_05">
                                <tr class="">
                                    <td class="text-center ctext-gc1-imp">나의 학습 순위</td>
                                    <td class="text-center ctext-gc1-imp p-4">등급</td>
                                    <td class="text-center ctext-gc1-imp p-4">이름</td>
                                    <td class="text-center ctext-gc1-imp p-4">학습 지수</td>
                                    <td class="text-center ctext-gc1-imp p-4">순위 변동</td>
                                </tr>
                            </thead>
                            <tbody class="text-b-20px">
                                <tr class="">
                                    <td class="col-auto text-center bg-transparent py-5">{{$my_rank ? $my_rank : '0'}}등</td>
                                    <td class="text-center ctext-gc1-imp bg-transparent py-5">{{$point_grade ? $point_grade : '0'}}단계</td>
                                    <td class="text-center ctext-gc1-imp bg-transparent py-5">{{$student ? $student->student_name : ''}}</td>
                                    <td class="text-center ctext-gc1-imp bg-transparent py-5">{{$student ? number_format($student->point_now) : ''}}</td>
                                    <td class="text-center bg-transparent py-5">
                                        @if(($prev_rank - $my_rank) < 0)
                                        <div class="all-center secondary-text-text">
                                        @else
                                        <div class="all-center text-danger">
                                        @endif
                                            {{$prev_rank ? abs($prev_rank - $my_rank) : '' }}등
                                            @if(($prev_rank - $my_rank) < 0)
                                                <img src="{{ asset('images/blue_arrow_down_icon.svg') }}" class="ms-2">
                                            @else
                                                <img src="{{ asset('images/red_arrow_up_icon.svg') }}" class="ms-2">
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
                {{-- 전체학습순위 --}}
                <section class="pt-5">
                    <div class="mt-4">
                        <table class="table">
                            {{-- 길이 조정 --}}
                            <colgroup>
                            </colgroup>
                            <thead class="modal-shadow-style text-b-20px scale-text-gray_05">
                                <tr class="">
                                    <td class="text-center ctext-gc1-imp">클래스 학습 순위</td>
                                    <td class="text-center ctext-gc1-imp p-4">등급</td>
                                    <td class="text-center ctext-gc1-imp p-4">이름</td>
                                    <td class="text-center ctext-gc1-imp p-4">학습 지수</td>
                                    <td class="text-center ctext-gc1-imp p-4">순위 변동</td>
                                </tr>
                            </thead>
                            <tbody class="text-b-20px">
                                @if(!empty($top_ten))
                                @foreach($top_ten as $key => $top)
                                <tr class="border-bottom">
                                    <td class="col-auto text-center bg-transparent py-4">{{$top->rank}}등</td>
                                    <td class="text-center ctext-gc1-imp bg-transparent py-4">{{$mt_this->getRank($top->point) }}단계</td>
                                    <td class="text-center ctext-gc1-imp bg-transparent py-4">{{$top->student_name}}</td>
                                    <td class="text-center ctext-gc1-imp bg-transparent py-4">{{$top ? number_format($top->point) : ''}}</td>
                                    <td class="text-center bg-transparent py-4">
                                        @if(($top->prev_rank - $top->rank) < 0)
                                        <div class="all-center secondary-text-text">
                                        @else
                                        <div class="all-center text-danger">
                                        @endif
                                            {{$top->prev_rank ? abs($top->prev_rank - $top->rank) : '-' }}등
                                            @if(($top->prev_rank - $top->rank) < 0)
                                                <img src="{{ asset('images/blue_arrow_down_icon.svg') }}" class="ms-2">
                                            @else
                                                <img src="{{ asset('images/red_arrow_up_icon.svg') }}" class="ms-2">
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            {{-- 학습포인트 소개 --}}
            <div class="col pe-0" data-btn-study-point-main-sub="3" hidden>
                <section>
                    <div class="modal-shadow-style rounded-3 p-4">
                        <div class="pt-2">
                            <span class="text-sb-24px">학습포인트 단계 소개</span>
                        </div>
                        <div class="pt-4 mt-2">
                            {{-- 캐릭터 이미지들 --}}
                            <div class="d-flex py-1">
                                <div class="col-lg w-center align-items-end">
                                    <img src="{{ asset('images/rank_character_lv1.svg') }}" width="60">
                                </div>
                                <div class="col-lg w-center align-items-end">
                                    <img src="{{ asset('images/rank_character_lv2.svg') }}" width="98">
                                </div>
                                <div class="col-lg w-center align-items-end">
                                    <img src="{{ asset('images/rank_character_lv3.svg') }}" width="106">
                                </div>
                                <div class="col-lg w-center align-items-end">
                                    <img src="{{ asset('images/rank_character_lv4.svg') }}" width="162">
                                </div>
                                <div class="col-lg w-center align-items-end">
                                    <img src="{{ asset('images/rank_character_lv5.svg') }}" width="142">
                                </div>
                                <div class="col-lg w-center align-items-end">
                                    <img src="{{ asset('images/rank_character_lv6.svg') }}" width="130">
                                </div>
                            </div>
                            {{-- 중간 동그라미 라인 --}}
                            <div class="position-relative py-2">
                                <div class="d-flex">
                                    <div class="col-lg all-center">
                                        <div class="scale-bg-gray_01 rounded-circle pt-4 ps-4"></div>
                                    </div>
                                    <div class="col-lg all-center">
                                        <div class="scale-bg-gray_01 rounded-circle pt-4 ps-4"></div>
                                    </div>
                                    <div class="col-lg all-center">
                                        <div class="scale-bg-gray_01 rounded-circle pt-4 ps-4"></div>
                                    </div>
                                    <div class="col-lg all-center">
                                        <div class="scale-bg-gray_01 rounded-circle pt-4 ps-4"></div>
                                    </div>
                                    <div class="col-lg all-center">
                                        <div class="scale-bg-gray_01 rounded-circle pt-4 ps-4"></div>
                                    </div>
                                    <div class="col-lg all-center">
                                        <div class="scale-bg-gray_01 rounded-circle pt-4 ps-4"></div>
                                    </div>
                                </div>
                                <div class="position-absolute top-0 bottom-0 m-auto scale-bg-gray_01 rounded-pill w-100" style="height:8px"></div>
                            </div>
                            {{-- 하단 캐릭터별 포인트 점수 --}}
                            <div class="pt-1 mb-1 mb-4">
                                <div class="d-flex">
                                    <div class="col-lg all-center">
                                        <span class="text-b-18px">0</span>
                                    </div>
                                    {{-- 3000, 6000m 9000, 12000, 15000 --}}
                                    <div class="col-lg all-center">
                                        <span class="text-b-18px">3,000</span>
                                    </div>
                                    <div class="col-lg all-center">
                                        <span class="text-b-18px">6,000</span>
                                    </div>
                                    <div class="col-lg all-center">
                                        <span class="text-b-18px">9,000</span>
                                    </div>
                                    <div class="col-lg all-center">
                                        <span class="text-b-18px">12,000</span>
                                    </div>
                                    <div class="col-lg all-center">
                                        <span class="text-b-18px">15,000</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="mt-5">
                    <div>
                        <h4 class="text-sb-24px mt-1 mb-3">회원 등급 분류</h4>
                        <span class="text-sb-18px scale-text-gray_05">학습 수강 및 커뮤니티 활동 내역에 따라 적립되는 학습 지수를 기준으로 10개 등급으로 분류됩니다.</span>
                    </div>
                    <div class="pt-4 mt-2">
                        <div class="scale-bg-black" style="height:2px;"></div>
                        <div class="d-flex border border-bottom">
                            <div class="text-center col-lg text-b-20px scale-text-gray_05 py-4">단계</div>
                            <div class="text-center col-lg text-b-20px scale-text-gray_05 py-4">등급분류</div>
                            <div class="text-center col-lg text-b-20px scale-text-gray_05 py-4">점수</div>
                        </div>
                        <div class="d-flex border border-bottom">
                            <div class="text-center col-lg text-r-20px scale-text-gray_05 py-4">6단계</div>
                            <div class="all-center col-lg text-r-20px scale-text-gray_05">
                                <img src="{{ asset('images/rank_character_lv6.svg') }}" width="39">
                            </div>
                            <div class="text-center col-lg text-r-20px scale-text-gray_05 py-4">180,000점</div>
                        </div>
                        <div class="d-flex border border-bottom">
                            <div class="text-center col-lg text-r-20px scale-text-gray_05 py-4">5단계</div>
                            <div class="all-center col-lg text-r-20px scale-text-gray_05">
                                <img src="{{ asset('images/rank_character_lv5.svg') }}" width="48">
                            </div>
                            <div class="text-center col-lg text-r-20px scale-text-gray_05 py-4">120,000-179,999점</div>
                        </div>
                        <div class="d-flex border border-bottom">
                            <div class="text-center col-lg text-r-20px scale-text-gray_05 py-4">4단계</div>
                            <div class="all-center col-lg text-r-20px scale-text-gray_05">
                                <img src="{{ asset('images/rank_character_lv4.svg') }}" width="47">
                            </div>
                            <div class="text-center col-lg text-r-20px scale-text-gray_05 py-4">90,000-119,999점</div>
                        </div>
                        <div class="d-flex border border-bottom">
                            <div class="text-center col-lg text-r-20px scale-text-gray_05 py-4">3단계</div>
                            <div class="all-center col-lg text-r-20px scale-text-gray_05">
                                <img src="{{ asset('images/rank_character_lv3.svg') }}" width="36">
                            </div>
                            <div class="text-center col-lg text-r-20px scale-text-gray_05 py-4">60,000-89,999점</div>
                        </div>
                        <div class="d-flex border border-bottom">
                            <div class="text-center col-lg text-r-20px scale-text-gray_05 py-4">2단계</div>
                            <div class="all-center col-lg text-r-20px scale-text-gray_05">
                                <img src="{{ asset('images/rank_character_lv2.svg') }}" width="31">
                            </div>
                            <div class="text-center col-lg text-r-20px scale-text-gray_05 py-4">45,000-59,999점</div>
                        </div>
                        <div class="d-flex border border-bottom">
                            <div class="text-center col-lg text-r-20px scale-text-gray_05 py-4">1단계</div>
                            <div class="all-center col-lg text-r-20px scale-text-gray_05">
                                <img src="{{ asset('images/rank_character_lv1.svg') }}" width="24">
                            </div>
                            <div class="text-center col-lg text-r-20px scale-text-gray_05 py-4">30,000-44,999점</div>
                        </div>
                    </div>

                </section>
                <section class="mt-5">
                    <div>
                        <h4 class="text-sb-24px mt-1 mb-3">회원 지수 적립 기준</h4>
                        <span class="text-sb-18px scale-text-gray_05" style=" max-width: 656px; display: inline-block; line-height: 1.7rem;">
                            학습지수는 수강 지수와 활동 지수가 있습니다. 아래 표와 같이 수강 및 활동 내역 별로 학습지수가 적립/차감되며, 적립된 누적 학습 지수에 따라 회원 등급이 분류됩니다.
                        </span>
                    </div>
                    <div class="pt-4 mt-2">
                        <div class="scale-bg-black" style="height:2px;"></div>
                        <table class="table m-0">
                            <colgroup>
                                <col style="width: 20%">
                                <col style="width: 20%">
                                <col style="width: 20%">
                                <col>
                            </colgroup>
                            <thead>
                                <tr>
                                    <td class="text-b-20px ctext-gc1-imp text-center py-4">구분</td>
                                    <td class="text-b-20px ctext-gc1-imp text-center py-4">항목</td>
                                    <td class="text-b-20px ctext-gc1-imp text-center py-4">차수</td>
                                    <td class="text-b-20px ctext-gc1-imp text-center py-4">내용</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="border border-end-0 border-start-0">
                                    <td class="text-m-20px ctext-gc1-imp text-center py-4 border border-start-0 align-middle" rowspan="3">수강시간</td>
                                    <td class="text-m-20px ctext-gc1-imp text-center py-4">학습방 담기</td>
                                    <td class="text-m-20px ctext-gc1-imp text-center py-4">10</td>
                                    <td class="text-m-20px ctext-gc1-imp py-4">
                                        <div>
                                            <ul>
                                                <li class="ctext-gc1-imp gap-2 h-center pt-2">
                                                    <div class="col-auto scale-bg-gray_03 rounded-circle" style="width:8px;height:8px;"></div>
                                                    해체시 차감
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border border-end-0 border border-start-0">
                                    <td class="text-m-20px ctext-gc1-imp text-center py-4 align-middle">수강 시간</td>
                                    <td class="text-m-20px ctext-gc1-imp text-center py-4 align-middle">5</td>
                                    <td class="text-m-20px ctext-gc1-imp py-4">
                                        <div>
                                            <ul>
                                                <li class="ctext-gc1-imp gap-2 h-center pt-2">
                                                    <div class="col-auto scale-bg-gray_03 rounded-circle" style="width:8px;height:8px;"></div>
                                                    해체시 차감
                                                </li>
                                                <li class="ctext-gc1-imp gap-2 h-center pt-2">
                                                    <div class="col-auto scale-bg-gray_03 rounded-circle" style="width:8px;height:8px;"></div>
                                                    1일 최대 3,000점 적립
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border border-end-0 border border-start-0">
                                    <td class="text-m-20px ctext-gc1-imp text-center py-4">강좌 수강 완료</td>
                                    <td class="text-m-20px ctext-gc1-imp text-center py-4">200</td>
                                    <td class="text-m-20px ctext-gc1-imp py-4">
                                        <div>
                                            <ul>
                                                <li class="ctext-gc1-imp gap-2 h-center pt-2">
                                                    <div class="col-auto scale-bg-gray_03 rounded-circle" style="width:8px;height:8px;"></div>
                                                    강좌 진도율 100%시, 200점 적립
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border border-end-0 border-start-0">
                                    <td class="text-m-20px ctext-gc1-imp text-center py-4 border border border-start-0 align-middle" rowspan="2">수강 시간</td>
                                    <td class="text-m-20px ctext-gc1-imp text-center py-4 align-middle">로그인</td>
                                    <td class="text-m-20px ctext-gc1-imp text-center py-4 align-middle">10</td>
                                    <td class="text-m-20px ctext-gc1-imp py-4">
                                        <div>
                                            <ul>
                                                <li class="ctext-gc1-imp gap-2 h-center pt-2">
                                                    <div class="col-auto scale-bg-gray_03 rounded-circle" style="width:8px;height:8px;"></div>
                                                    해체시 차감
                                                </li>
                                                <li class="ctext-gc1-imp gap-2 h-center pt-2">
                                                    <div class="col-auto scale-bg-gray_03 rounded-circle" style="width:8px;height:8px;"></div>
                                                    1일 최대 3,000점 적립
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="border border-end-0 border border-start-0">
                                    <td class="text-m-20px ctext-gc1-imp text-center py-4 align-middle">게시물 작성</td>
                                    <td class="text-m-20px ctext-gc1-imp text-center py-4 align-middle">10</td>
                                    <td class="text-m-20px ctext-gc1-imp py-4 pe-3">
                                        <div>
                                            <ul>
                                                <li class="ctext-gc1-imp gap-2 h-center pt-2">
                                                    <div class="col-auto scale-bg-gray_03 rounded-circle" style="width:8px;height:8px;"></div>
                                                    1일 최대 1회
                                                </li>
                                                <li class="ctext-gc1-imp gap-2 h-center pt-2">
                                                    <div class="col-auto scale-bg-gray_03 rounded-circle" style="width:8px;height:8px;"></div>
                                                    수강후기, 학습 Q&A, 일부 게시판(나만의 노하우, 우리들의 이야기 등) 글 작성 시 1건당 10점 적립(이벤트 게시판 제외) ※우수 수강후기, 우수학습 Q&A 신청시 1건당 100점 적립
                                                </li>
                                                <li class="ctext-gc1-imp gap-2 h-center pt-2">
                                                    <div class="col-auto scale-bg-gray_03 rounded-circle" style="width:8px;height:8px;"></div>
                                                    삭제 또는 신고 삭제시 차감
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    {{-- 학습지수! --}}
                    <div class="scale-bg-gray_01 p-4">
                        <h4 class="text-b-20px py-2">학습 지수! Check Point!</h4>
                        <div class="pb-2">
                            <ul class="text-m-18px pt-1">
                                <li class="ctext-gc1-imp gap-2 h-center pt-2">
                                    <div class="col-auto scale-bg-gray_03 rounded-circle" style="width:8px;height:8px;"></div>
                                    회원 등급은 1년전부터 어제까지 회원님이 쌓은 학습 지수 기준입니다.
                                </li>
                                <li class="ctext-gc1-imp gap-2 h-center pt-2">
                                    <div class="col-auto scale-bg-gray_03 rounded-circle" style="width:8px;height:8px;"></div>
                                    운영상 문제가 발생한 경우에는 관련 정책을 일부 변경할 수 있습니다.
                                </li>
                                <li class="ctext-gc1-imp gap-2 h-center pt-2">
                                    <div class="col-auto scale-bg-gray_03 rounded-circle" style="width:8px;height:8px;"></div>
                                    편법을 통해 학습 지수를 적립한 경우, 학습 지수는 차감됩니다.
                                </li>
                                <li class="ctext-gc1-imp gap-2 h-center pt-2">
                                    <div class="col-auto scale-bg-gray_03 rounded-circle" style="width:8px;height:8px;"></div>
                                    회원 탈퇴한 경우에는 적립된 학습 지수가 자동 소멸됩니다.
                                </li>
                                <li class="ctext-gc1-imp gap-2 h-center pt-2">
                                    <div class="col-auto scale-bg-gray_03 rounded-circle" style="width:8px;height:8px;"></div>
                                    게시물/댓글 쓰기에 따른 학습 지수는 다음날 적립되며, 게시물/댓글 삭제시 학습 지수는 적립되지 않습니다. 이전 게시물/댓글 삭제시 학습 지수는 다음날 차감됩니다.
                                </li>
                            </ul>
                        </div>

                    </div>

                </section>
            </div>


        </div>
    </article>
    {{-- padding 160px --}}
    <div>
        <div class="py-lg-5"></div>
        <div class="py-lg-4"></div>
        <div class="pt-lg-3"></div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded',function(){
    // TEST:
    // studyPointHistoryPage();
    // 달력 초기화
})
// 학습랭킹 우선 선택.
document.querySelector('[data-btn-study-point-main-tab="1"]').click();

// 학습포인트 tab 선택.
function studyPointAsideTab(vthis){

    document.querySelectorAll('[data-btn-study-point-main-sub]').forEach(element => {
        element.hidden = true;
    });
    document.querySelectorAll('[data-btn-study-point-main-aside]').forEach(element => {
        element.hidden = true;
    });
    // if(vthis.classList.contains('active')){
    //   vthis.classList.remove('active');
    //   document.querySelector('[data-btn-study-point-main-sub="0"]').hidden = false;
    //   document.querySelector('[data-btn-study-point-main-aside="0"]').hidden = false;
    //   return;
    // }
    document.querySelectorAll('[data-btn-study-point-main-tab]').forEach(element => {
        element.classList.remove('active');
    });

    document.querySelector('[data-btn-study-point-main-aside="1"]').hidden = false;
    vthis.classList.add('active');
    const idx = vthis.getAttribute('data-btn-study-point-main-tab');
    switch(idx){
        case '1':
            document.querySelector('[data-btn-study-point-main-sub="1"]').hidden = false;
            break;
        case '2':
            document.querySelector('[data-btn-study-point-main-sub="2"]').hidden = false;
            break;
        case '3':
            document.querySelector('[data-btn-study-point-main-sub="3"]').hidden = false;
            break;
    }
}

// 내역 보러가기 버튼 클릭.
function studyPointHistoryPage(){
    document.querySelectorAll('[data-btn-study-point-main-tab]').forEach(element => {
        element.classList.remove('active');
    });
    document.querySelectorAll('[data-btn-study-point-main-sub]').forEach(element => {
        element.hidden = true;
    });
    document.querySelectorAll('[data-btn-study-point-main-aside]').forEach(element => {
        element.hidden = true;
    });

    document.querySelector('[data-btn-study-point-main-sub="0"]').hidden = false;
    document.querySelector('[data-btn-study-point-main-aside="0"]').hidden = false;

    // 포인트 히스토리 리스트 가져오기. 날짜 없을시에는 이번달 1일부터 오늘까지
    studyPointHistorySelect();
}


// 날짜 선택해서 포인트 히스토리 확인.
function studyPointHistorySelect(start_date, end_date){
    if(start_date == undefined){
        // this month
        start_date = new Date().format('yyyy-MM-01');
        end_date = new Date().format('yyyy-MM-dd');
        addDateUpdate(start_date, end_date);
    }
    const page = "/student/study/point/history/select";
    const parameter = {
        start_date:start_date,
        end_date:end_date,
    };
    queryFetch(page, parameter,function(result){
        if((result.resultCode||'') == 'success'){
            // 초기화
            const bundle = document.querySelector('[data-bundle="point_history"]');
            const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

            const points = result.point_histories;
            points.forEach(function(point){
                const row = row_copy.cloneNode(true);
                row.hidden = false;
                row.querySelector('[data-point]').textContent = point.point;
                row.querySelector('[data-create]').textContent = (point.created_id||'').length > 0 ? '선생님 부여':'스스로 획득';
                row.querySelector('[data-created-at]').textContent = point.created_at;
                bundle.appendChild(row);
            });

        }else{}
    });
}

// 달력 초기화.
     $('#date-range53').dateRangePicker({
        container: '#date-range12-container',
        alwaysOpen:true,
        singleMonth: true,
        inline:true,
        language:'ko',
        startOfWeek: 'monday',
        showTopbar: false,
        getValue : function(date) //날짜는 주의 첫 번째 날이 됩니다.
    {

            $($(this).next().find('.next')).on('click', function(e){
                datechange($(this))
            });
            $($(this).next().find('.prev')).on('click', function(e){
                datechange($(this))
            });
            var datechange = (value) => {
                var hasFirstDateSelectedClass = value.parents('table').find('tbody tr td div.first-date-selected').length > 0;
                var haslastDateSelectedClass = value.parents('table').find('tbody tr td div.last-date-selected').length > 0;
                if(hasFirstDateSelectedClass || haslastDateSelectedClass){
                    var firstday = value.parents('table').find('tbody tr td div.first-date-selected');
                    var lastday = value.parents('table').find('tbody tr td div.last-date-selected');
                    firstday.attr('data-day', firstday.text());
                    lastday.attr('data-day', lastday.text());
                }
            }
        },
    }).on('datepicker-first-date-selected', function(event, obj){
        additionalFunction(obj.date1.getTime() / 1000 * 1000, obj.date1.getDate());
        $("#startDate").val(new Date(obj.date1).toLocaleDateString('ko-KR', { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\./g, '').replace(/\s/g, '.'));
    }).on('datepicker-change',function(event,obj) {
        additionalFunction(obj.date1.getTime() / 1000 * 1000, obj.date1.getDate());
        additionalFunction(obj.date2.getTime() / 1000 * 1000, obj.date2.getDate());
        $("#lastDate").val(new Date(obj.date2).toLocaleDateString('ko-KR', { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\./g, '').replace(/\s/g, '.'));
            // 날자 선택
        studyPointSelDate(obj.date1, obj.date2);
    })

// 달력 > 선택시 함수
function additionalFunction(time, day) {
    $(`[time="${time}"]`).attr('data-day', day)
}

// 달력 > 날짜 선택시 함수 실행
function studyPointSelDate(start_date_time, end_date_time){
    const start_date = start_date_time.format('yyyy-MM-dd');
    const end_date = end_date_time.format('yyyy-MM-dd');
    studyPointHistorySelect(start_date, end_date);
}

function addDateUpdate(start_date, end_date){
    $("#date-range53").data('dateRangePicker').setDateRange(start_date, end_date);
    document.querySelector('#startDate').value = start_date.replace(/-/g, '.');
    document.querySelector('#lastDate').value = end_date.replace(/-/g, '.');
}
</script>
@endsection
