
{{--
         //월~일 배열
         $week = array("일", "월", "화", "수", "목", "금", "토");
        // 08:00 ~ 23:00 배열
        $time_array = array("08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00", "19:00", "20:00", "21:00", "22:00", "23:00");
        // 1~6월
        $month_array = array("1", "2", "3", "4", "5", "6");

--}}

<style>
    :root {
        --color-subject1: #AB4DE5;
        --color-subject2: #6F91F7;
        --color-subject3: #EC46D1;
        --color-subject4: #FFD077;
        --color-subject5: #63D8EC;
        --color-subject6: #222222;
        --attendanceTime: #FFBD19;
        --goalTime: #2FCD94;
        --studyComplete: #FF5065;
    }
    .bg-blue-hover:hover,
    .bg-blue-hover.active,
    .active .bg-blue-hover {
        background-color: #5057FF;
    }
    .bg-blue-hover:hover svg circle,
    .bg-blue-hover.active svg circle,
    .active .bg-blue-hover svg circle{
        stroke:#fff;
    }
    /* 필터 그레이*/
    .noactive{
        filter: grayscale(100%);
    }
</style>


{{-- 나의 학습 섹션 --}}
<article class="col-lg px-0 px-md-2 px-lg-2 px-xxl-2">
    <section class="mt-4">
        {{-- 학생의 출석률, 목표 수행율, 스스로 학습 / 카드 --}}
        <div class="d-flex h-100 flex-column  flex-lg-row">
            <div class="col-lg-12">
                <div class="d-flex flex-row justify-content-between reverse modal-shadow-style rounded-3 p-4">
                    <div class="col-auto row mx-0">
                        <span class="col-lg text-sb-24px h-center px-0 w-auto">{{!empty($student)?$student->student_name:''}} 학생의 출석율</span>
                        <button class="col-auto btn p-0 d-none">
                            <img src="{{ asset('images/gray_cir_manus.svg') }}" width="32">
                        </button>
                    </div>
                <div class="col-auto pt-2 d-none">
                    <span class="text-sb-18px scale-text-gray_05">
                        <span data-main-now-week>3주차</span>
                         목표일 중
                    </span>
                    <span class="text-sb-18px text-danger" data-main-total-late-cnt>총 4일 지각</span>
                        <span class="text-sb-18px scale-text-gray_05">하지 않았습니다.</span>
                    </div>
                    <div class="">
                        <div class="px-0">
                            <span class="text-b-52px" data-main-attend-per>0</span>
                            <span class="text-b-20px">%</span>
                        </div>
                        <div class="col-auto d-none d-xxl-block">
                            <img src="{{ asset('images/mystudy_attendance_charactera.svg') }}" width="80">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 px-4 d-none">
                <div class="d-flex flex-column modal-shadow-style rounded-3 p-4">
                    <div class="col-auto row mx-0">
                        <span class="col-lg text-sb-24px h-center px-0 w-auto">목표 수행율</span>
                        <button class="col-auto btn p-0">
                            <img src="{{ asset('images/gray_cir_plus.svg') }}" width="32">
                        </button>
                    </div>
                    <div class="col row pt-3 mx-0 align-items-end">
                        {{-- 36 --}}
                        <div class="col-lg pt-4 px-0">
                            <span class="text-b-52px" data-main-complete-per>0</span>
                            <span class="text-b-20px">%</span>
                        </div>
                        <div class="col-auto d-none d-xxl-block">
                            <img src="{{ asset('images/mystudy_goal_character2.svg') }}" width="80">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 d-none">
                <div class="d-flex flex-column modal-shadow-style rounded-3 p-4">
                    <div class="col-auto row mx-0">
                        <span class="col-lg text-sb-24px h-center px-0 w-auto">스스로 학습</span>
                        <button class="col-auto btn p-0">
                            <img src="{{ asset('images/gray_cir_manus.svg') }}" width="32">
                        </button>
                    </div>
                    <div class="col row pt-3 mx-0 align-items-end">
                        {{-- 36 --}}
                        <div class="col-lg pt-4 px-0">
                            <span class="text-b-52px" data-main-self-study-cnt>0</span>
                            <span class="text-b-20px">강</span>
                        </div>
                        <div class="col-auto d-none d-xxl-block">
                            <img src="{{ asset('images/mystudy_self_character.svg') }}" width="80">
                        </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    {{-- 주간 묶음 SECTION --}}
    <div data-div-my-study-main-bundle="week">
        {{-- 요일별 학습시간 --}}
        <section class="mt-4">
            <div class=" modal-shadow-style rounded-3 p-4">
                <div class="h-center">
                    <div class="col h-center gap-2">
                        <img src="{{ asset('images/study_time_icon.svg') }}" width="32">
                        <span class="text-b-24px">요일별 학습시간</span>
                    </div>
                    <div class="col-auto">
                        <button class="btn h-center p-0" data-btn-my-study-week-section="1"
                            onclick="myStudyWeekSection(this);">
                            <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32">
                        </button>
                    </div>
                </div>
                {{-- 숨김/보이기 --}}
                <div data-div-my-study-week-section-sub="1" class="pt-4 mt-2 mb-5 pb-1" hidden>
                    <div class="row mx-0 align-items-center justify-content-end">
                        <span class="col-auto text-r-16px scale-text-gray_05 px-0">하루평균</span>
                        <span data-today-average data-ex="0시간 0분"
                            class="col-auto text-sb-24px text-danger ps-2"></span>
                        <span class="col-auto text-r-16px scale-text-gray_05 ps-4">주간합계</span>
                        <span data-week-total data-ex="0시간 0분"
                            class="col-auto text-sb-24px text-danger ps-2"></span>
                    </div>
                    {{-- 40 --}}
                    <div>
                        <div class="py-lg-5"></div>
                        <div class="py-lg-3"></div>
                        <div class="pt-lg-1"></div>
                    </div>

                    <!-- ------------------------------------------------------------------------------  -->
                    <!-- 시간단위 -->
                    <div class="m-1 row" style="height:250px;">
                        <div data-bundle="week_time"
                            class="col-auto m-0 d-flex flex-column position-relative" style="width:60px;">
                            <div
                                class="col mb-2 position-absolute d-flex flex-column"
                                style="bottom:4px;height:250px;">
                                <div data-row="4" class="col text-sb-18px scale-text-gray_05">4시간</div>
                                <div data-row="3" class="col text-sb-18px scale-text-gray_05">3시간</div>
                                <div data-row="2" class="col text-sb-18px scale-text-gray_05">2시간</div>
                                <div data-row="1" class="col text-sb-18px scale-text-gray_05">1시간</div>
                            </div>
                            <div data-row="0" class="position-absolute text-sb-18px scale-text-gray_05"
                                style="bottom:-10px">
                                0시간</div>
                        </div>
                        <div class="col position-relative">
                            <div class=" d-flex flex-column ms-4 h-100">
                                <div class="col"
                                    style="border-bottom:1px solid #E5E5E5;border-top:1px solid #E5E5E5"></div>
                                {{-- <div class="col" style="border-bottom:1px solid #E5E5E5"></div> --}}
                                <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                                <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                                <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                            </div>

                            <!-- ------------------------------------------------------------------------------  -->
                            <!-- 일~토 요일별 학습시간 입력 목표학습 / 스스로 학습 -->
                            <div data-bundle="study_time_by_day"
                                class="row mx-0 position-absolute top-0 bottom-0 start-0 end-0 ms-4 px-5">
                                {{-- <div class="col-1"></div> --}}
                                @if (!empty($week))
                                    @foreach ($week as $idx => $wk)
                                        <div data-row="{{ $wk }}"
                                            class="col row gap-2 align-items-end justify-content-center position-relative">

                                            <!-- 마우스오버 상단 시간 표기 -->
                                            <div data-div-self-time hidden
                                                class="position-absolute text-center row mx-0 justify-content-center" style="top: -73px;min-width: 160px;" >
                                                <span class="text-white text-b-20px rounded-3"
                                                    style="background: #FFC747;padding:12px 20px;">
                                                <span data-top-self-time data-ex="3시간 10분"
                                                class="text-white">0초</span>
                                                </span>
                                                <div class="position-relative">
                                                    <img src="{{ asset('images/yellow_arrow_down_icon.svg') }}"
                                                        width="18" class="position-absolute"
                                                        style="left: 43%;bottom:-13xpx">
                                                </div>

                                            </div>
                                            <!-- 목표 학습 BAR -->
                                            <div data-goal-bar
                                                class="col-auto rounded-top-3 scale-bg-gray_02 px-3"
                                                style="height:0%"> </div>
                                            <!-- 스스로 학습 BAR -->
                                            <div  data-self-bar
                                                class="col-auto rounded-top-3 px-3 ms-1"
                                                style="height:0%;background:#FFC747"> </div>
                                            <div class="position-absolute text-center" style="bottom:-62px;">
                                                <button data-btn-my-study-week-btm onclick="myStudyShowWeeklyStudyTimeBtmBtnClick(this)"
                                                    class="btn btn-outline-primary-y scale-text-white-hover border-0 rounded-pill text-sb-20px scale-text-gray_05"
                                                    style="padding:4px 16px">
                                                    {{ $wk }}
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                {{-- <div class="col-1"></div> --}}
                            </div>
                        </div>
                    </div>

                    {{-- padding 125px --}}
                    <div style="height: 125px;"></div>

                    {{-- 지난달, 현재 --}}
                    <div class="gap-4 all-center">
                        <div class="col-auto all-center">
                            <span class="rounded-pill pt-3 ps-3" style="border:4px solid #f1f1f1;"></span>
                            <span class="text-sb-20px scale-text-gray_05 ms-2">목표 학습</span>
                        </div>
                        <div class="col-auto all-center">
                            <span class="rounded-pill pt-3 ps-3" style="border:4px solid #FFC747;"></span>
                            <span class="text-sb-20px scale-text-gray_05 ms-2">스스로 학습</span>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        {{-- 과목별 학습시간 --}}
        <section>
            <div class="modal-shadow-style rounded-3 p-4 mt-4">
                <div class="h-center">
                    <div class="col h-center gap-2">
                        <img src="{{ asset('images/study_time_icon.svg') }}" width="32">
                        <span class="text-b-24px">과목별 학습시간</span>
                    </div>
                    <div class="col-auto">
                        <button class="btn h-center p-0" data-btn-my-study-week-section="2"
                            onclick="myStudyWeekSection(this);">
                            <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32">
                        </button>
                    </div>
                </div>
                {{-- 숨김/보이기 --}}
                <div data-div-my-study-week-section-sub="2" class="pt-4 mt-2 mb-5 pb-1" hidden>
                    <div class="row mx-0 align-items-center justify-content-end">
                        <span class="col-auto text-r-16px scale-text-gray_05 px-0">하루평균</span>
                        <span data-today-average data-ex="0시간 0분"
                            class="col-auto text-sb-24px text-danger ps-2">0시간 0분</span>
                        <span class="col-auto text-r-16px scale-text-gray_05 ps-4">주간합계</span>
                        <span data-week-total data-ex="0시간 0분"
                            class="col-auto text-sb-24px text-danger ps-2">0시간 0분</span>
                    </div>
                    {{-- 40 --}}
                    <div>
                        <div class="py-lg-5"></div>
                        <div class="py-lg-3"></div>
                        <div class="pt-lg-1"></div>
                    </div>

                    <!-- ------------------------------------------------------------------------------  -->
                    <!-- 시간단위 -->
                    <div class="m-1 row" style="height:250px;">
                        <div data-bundle="subject_time"
                            class="col-auto m-0 d-flex flex-column position-relative" style="width:60px;">
                            <div class="col mb-2 position-absolute d-flex flex-column"
                                style="bottom:4px;height:250px;">
                                <div data-row="4" class="col text-sb-18px scale-text-gray_05">4시간</div>
                                <div data-row="3" class="col text-sb-18px scale-text-gray_05">3시간</div>
                                <div data-row="2" class="col text-sb-18px scale-text-gray_05">2시간</div>
                                <div data-row="1" class="col text-sb-18px scale-text-gray_05">1시간</div>
                            </div>
                            <div data-row="0" class="position-absolute text-sb-18px scale-text-gray_05"
                                style="bottom:-10px">
                                0시간</div>
                        </div>
                        <div class="col position-relative">
                            <div class=" d-flex flex-column ms-4 h-100">
                                <div class="col"
                                    style="border-bottom:1px solid #E5E5E5;border-top:1px solid #E5E5E5"></div>
                                {{-- <div class="col" style="border-bottom:1px solid #E5E5E5"></div> --}}
                                <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                                <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                                <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                            </div>

                            <!-- ------------------------------------------------------------------------------  -->
                            <!-- 과목별 학습시간 수업 그래프 시작. -->
                            <div data-bundle="study_time_by_subject"
                                class="row mx-0 position-absolute top-0 bottom-0 start-0 end-0 ms-4 px-5">
                                @if (!empty($subject_codes))
                                    @foreach ($subject_codes as $idx => $subject_code)
                                        <div data-row="{{ $subject_code->id }}"
                                            class="col row gap-2 align-items-end justify-content-center position-relative">

                                            <!-- 마우스오버 상단 시간 표기 -->
                                            <div data-div-self-time hidden
                                                class="position-absolute text-center row mx-0 justify-content-center" style="top: -73px;min-width: 160px;" >
                                                <span class="text-white text-b-20px rounded-3"
                                                    style="background: #FFC747;padding:12px 20px;">
                                                <span data-top-self-time data-ex="3시간 10분"
                                                class="text-white">0초</span>
                                                </span>
                                                <div class="position-relative">
                                                    <img src="{{ asset('images/yellow_arrow_down_icon.svg') }}"
                                                        width="18" class="position-absolute"
                                                        style="left: 43%;bottom:-13px">
                                                </div>

                                            </div>
                                            <!-- 목표 학습 BAR -->
                                            <div data-goal-bar
                                                class="col-auto rounded-top-3 scale-bg-gray_02 px-3"
                                                style="height:0%"> </div>
                                            <!-- 스스로 학습 BAR -->
                                            <div  data-self-bar
                                                class="col-auto rounded-top-3 px-3 ms-1"
                                                style="height:0%;background:#FFC747"> </div>
                                            <div class="position-absolute text-center" style="bottom:-62px;">
                                                <button data-btn-my-study-week-subject-btm onclick="myStudyWeekSubjectTimeBtmClick(this)"
                                                    class="btn btn-outline-primary-y border-0 rounded-pill scale-text-white-hover text-sb-20px scale-text-gray_05 {{ $idx == 0 ? 'active' : '' }}"
                                                    style="padding:4px 16px">
                                                    {{ $subject_code->code_name }}
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                {{-- <div class="col-1"></div> --}}
                            </div>
                        </div>
                    </div>

                    {{-- padding 125px --}}
                    <div style="height: 125px;"></div>

                    {{-- 지난달, 현재 --}}
                    <div class="gap-4 all-center">
                        <div class="col-auto all-center">
                            <span class="rounded-pill pt-3 ps-3" style="border:4px solid #f1f1f1;"></span>
                            <span class="text-sb-20px scale-text-gray_05 ms-2">목표 학습</span>
                        </div>
                        <div class="col-auto all-center">
                            <span class="rounded-pill pt-3 ps-3" style="border:4px solid #FFC747;"></span>
                            <span class="text-sb-20px scale-text-gray_05 ms-2">스스로 학습</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- 주간 학습 상세 --}}
        <section class="d-none">
            <div class="modal-shadow-style rounded-3 p-4 mt-4">
                <div class="h-center">
                    <div class="col h-center gap-2">
                        <img src="{{ asset('images/weekly_study_detail_icon.svg') }}" width="32">
                        <span class="text-b-24px">주간 학습 상세</span>
                    </div>
                    <div class="col-auto">
                        <button class="btn h-center p-0" data-btn-my-study-week-section="3"
                            onclick="myStudyWeekSection(this);">
                            <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32">
                        </button>
                    </div>
                </div>
                <div data-div-my-study-week-section-sub="3" class="pt-4 mt-2 mb-5 pb-1" hidden>
                    <div class="h-center gap-4 justify-content-end pe-2">
                        @if (!empty($subject_codes))
                            @foreach ($subject_codes as $idx => $subject_code)
                                <div class="col-auto all-center">
                                    <span class="rounded-pill pt-3 ps-3"
                                        style="border:4px solid var(--color-subject{{ ($idx % 5) + 1 }})"></span>
                                    <span class="text-sb-20px scale-text-gray_05 ms-2"
                                        data-id="{{ $subject_code->id }}" data-color="{{ ($idx % 5) + 1 }}"
                                        data-function-code="{{ $subject_code->function_code }}">
                                        {{ $subject_code->code_name }}
                                    </span>
                                </div>
                                @php $subject_code->color = $idx%5+1; @endphp
                            @endforeach
                        @endif
                    </div>
                    {{-- $subject_codes->find(87)->color --}}
                    <div>
                        <div class="py-lg-5"></div>
                        <div class="pt-lg-2"></div>
                    </div>
                    <div class="m-1 row" style="height:800px;">
                        <div class="col-auto m-0 d-flex flex-column position-relative" style="width:60px;">
                            <div class="col mb-2 position-absolute d-flex flex-column"
                                style="bottom:4px;height:795px;">
                                @if (!empty($time_array))
                                    @foreach ($time_array as $time)
                                        {{-- 마지막은 빼기 --}}
                                        @if ($loop->last)
                                            @continue
                                        @endif
                                        <div class="col text-sb-18px scale-text-gray_05"
                                            data-hour="{{ substr($time, 0, 2) }}">{{ $time }}</div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="position-absolute text-sb-18px scale-text-gray_05"
                                data-hour="{{ !empty($time_array) ? substr($time_array[count($time_array) - 1], 0, 2) : '' }}"
                                style="bottom:-10px">
                                {{ !empty($time_array) ? $time_array[count($time_array) - 1] : '' }}</div>
                        </div>
                        <div class="col position-relative">
                            <div class=" d-flex flex-column ms-4 h-100">
                                @if (!empty($time_array))
                                    @foreach ($time_array as $idx => $time)
                                        @if ($loop->last)
                                            @continue
                                        @endif
                                        <div class="col"
                                            style="border-bottom:1px solid #E5E5E5;{{ $idx == 0 ? 'border-top:1px solid #E5E5E5' : '' }}">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div
                                class="d-flex mx-0 position-absolute top-0 bottom-0 start-0 end-0 ms-4 px-3 gap-2">
                                {{-- <div class="col-1"></div> --}}
                                @if (!empty($week))
                                    @foreach ($week as $idx => $wk)
                                        <div data-div-weeky-study-day="{{ $wk }}"
                                            class="col d-flex gap-1 align-items-start justify-content-center position-relative">
                                            {{-- 여기 시간표 데이터 넣기 --}}
                                            {{-- 100%로 봤을때 1칸(1시간) 6.766% --}}

                                                <div data-div-weeky-study-head="copy" hidden
                                                    class="col-auto h-100 cursor-pointer px-0"
                                                    onclick="myStudyWeekyStudyDetailContent(this)">
                                                    {{-- padding top --}}
                                                    <div data-start-cnt style="height:{{ 2 * 6.766 }}%"></div>
                                                    <div data-div-weeky-study-detail
                                                        class="rounded-4 p-1 row flex-column mx-0"
                                                        style="background:var(--color-subject3);min-height:{{ 2 * 6.766 }}%">
                                                        {{-- 글자가 다음라인을 넘어가면 안되는 div 만들기 --}}
                                                        <div class="col-auto text-white text-sb-16px mt-2 mb-1 px-0"
                                                            style="overflow:hidden;white-space:nowrap;text-overflow:ellipsis;"
                                                            data-lecture-description hidden>
                                                            의견이 있어요.
                                                        </div>
                                                        <div class="col-auto text-white text-sb-16px mb-1 px-0"
                                                            style="overflow:hidden;white-space:nowrap;text-overflow:ellipsis;"
                                                            data-lecture-name hidden>
                                                            8단원
                                                        </div>
                                                        <div class="col row text-white text-sb-16px align-items-end mb-2 mx-0 px-0"
                                                            style="overflow:hidden;white-space:nowrap;text-overflow:ellipsis;"
                                                            data-last-video-time hidden>
                                                            00:24:30
                                                        </div>
                                                    </div>
                                                </div>

                                            <div class="position-absolute text-center" style="top:-62px;">
                                                <button
                                                    class="btn border-0 p-0 scale-text-white-hover text-sb-20px basic-text-error-hover {{ $idx == 0 ? 'active' : '' }}"
                                                    style="padding:4px 16px">
                                                    {{ $wk }}요일
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                {{-- <div class="col-1"></div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        {{-- 주간 출결 현황 --}}
        <section>
            <div class="modal-shadow-style rounded-3 p-4 mt-4">
                <div class="h-center">
                    <div class="col h-center gap-2">
                        <img src="{{ asset('images/weekly_outing_status_icon.svg') }}" width="32">
                        <span class="text-b-24px">주간 출결 현황</span>
                    </div>
                    <div class="col-auto">
                        <button class="btn h-center p-0" data-btn-my-study-week-section="4"
                            onclick="myStudyWeekSection(this);">
                            <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32">
                        </button>
                    </div>
                </div>
                {{-- --}}
                <div data-div-my-study-week-section-sub="4" class="pt-4 mt-2 mb-5 pb-1" hidden>
                    {{-- 출석횟수, 6일 /지각횟수 총 3회 --}}
                    <div class="row mx-0 align-items-center justify-content-end">
                        <span class="col-auto text-r-16px scale-text-gray_05 px-0">출석횟수</span>
                        <span data-total-attend-cnt data-explain="6일"
                            class="col-auto text-sb-24px text-danger ps-2"></span>
                        <span class="col-auto text-r-16px scale-text-gray_05 ps-4">지각횟수</span>
                        <span data-total-late-cnt data-explain="총 3회"
                            class="col-auto text-sb-24px text-danger ps-2"></span>
                    </div>
                    {{-- 52 --}}
                    <div class="pt-5 mt-1">
                        {{-- bundle --}}
                        <div class="" data-bundle="week_attend_status">
                            <div data-row="copy" hidden
                                class="row border-bottom mx-0 p-4">
                                <div class="col-auto">
                                    <span data-day data-explain="일요일"
                                        class="btn btn-outline-danger scale-bg-gray_02 scale-text-gray_05 border-0 active  rounded-pill text-b-20px px-3"></span>
                                </div>
                                <div class="col-auto h-center px-0">
                                    <img src="{{ asset('images/bar_icon.svg') }}" width="2"
                                        height="12">
                                </div>
                                <div class="col-auto h-center">
                                    <span class="col-auto text-sb-20px scale-text-gray_05 px-0">목표 학습시작 시간</span>
                                    <span data-select-time data-explain="18:00"
                                        class="col-auto text-sb-24px ps-2" style="min-width: 68px;"></span>
                                    <span class="col-auto text-sb-20px scale-text-gray_05 ps-4">학습시작 시간</span>
                                    <span data-start_time data-explain="17:50"
                                        class="col-auto text-sb-24px ps-2"></span>
                                </div>
                                <div class="col h-center justify-content-end">
                                    <span data-late-min data-explain="20분 지각 or 정상 출결"
                                        class="text-danger text-b-20px"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @if(session()->get('login_type') == 'parent' || session()->get('login_type') == 'teacher')
        <section>
            <div class="modal-shadow-style rounded-3 p-4 mt-4">
                <div class="h-center">
                    <div class="col h-center gap-2">
                        <img src="{{ asset('images/window_chk_icon.svg') }}" width="32">
                        <span class="text-b-24px">요일별 목표 학습 현황</span>
                    </div>
                    <div class="col-auto">
                        <button class="btn h-center p-0" data-btn-my-study-week-section="5"
                            onclick="myStudyWeekSection(this);">
                            <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32">
                        </button>
                    </div>
                </div>
                {{-- --}}
                <div data-div-my-study-week-section-sub="5" class="pt-4 mt-2 mb-5 pb-1" hidden>
                    <div class="container row mx-0 mt-4 px-0">
                        <div class="col-2 px-0 row flex-column">
                            <div class="col-auto bg-primary-y text-white text-sb-24px text-center py-3 rounded-top-3"> 총계 </div>
                            <div class="col border-top-0 border rounded-bottom-3 text-center all-center">
                                <div>
                                    <span class="text-sb-32px" data-all-complete-learning>0</span>
                                    <span class="text-sb-32px scale-text-gray_05" >/</span>
                                    <span class="text-sb-32px scale-text-gray_05" data-all-weeksum-learning>0</span>
                                    <span class="text-sb-32px scale-text-gray_05">강</span>
                                </div>
                            </div>
                        </div>
                        <div class="px-0 row row-cols-4 col">
                            @if (!empty($week))
                            @foreach ($week as $idx => $wk)
                            <div class="px-0" data-dayweek-target-div="{{$wk}}" onclick="clickTargetLearningStatusByDayWeek(this)">
                                <div class="row mx-0 justify-content-center p-4 rounded-3 scale-bg-gray_01 align-items-center primary-bg-mian-hover mb-2 ms-2">
                                    <div class="col-auto scale-text-gray_05 text-sb-20px px-0 scale-text-white-hover">{{$wk}}</div>
                                    <div class="col text-sb-28px px-0 text-end">
                                        <span class="scale-text-white-hover text-black" data-cnt-complete-learning>0</span>
                                        <span class="scale-text-white-hover scale-text-gray_05">/</span>
                                        <span class="scale-text-white-hover scale-text-gray_05" data-cnt-weeksum-learning>0</span><span class="scale-text-white-hover scale-text-gray_05">강</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="modal-shadow-style rounded-3 p-4 mt-4">
                <div class="h-center">
                    <div class="col h-center gap-2">
                        <img src="{{ asset('images/goal_icon.svg') }}" width="32">
                        <span class="text-b-24px">과목별 목표 학습 현황</span>
                    </div>
                    <div class="col-auto">
                        <button class="btn h-center p-0" data-btn-my-study-week-section="6"
                            onclick="myStudyWeekSection(this);">
                            <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32">
                        </button>
                    </div>
                </div>
                {{-- --}}
                <div data-div-my-study-week-section-sub="6" class="pt-4 mt-2 mb-5 pb-1" hidden>
                    <div class="container row mx-0 mt-4 px-0">
                        <div class="col-2 px-0 row flex-column">
                            <div class="col-auto bg-primary-y text-white text-sb-24px text-center py-3 rounded-top-3"> 총계 </div>
                            <div class="col border-top-0 border rounded-bottom-3 text-center all-center">
                                <div>
                                    <span class="text-sb-32px" data-all-complete-learning>0</span>
                                    <span class="text-sb-32px scale-text-gray_05">/</span>
                                    <span class="text-sb-32px scale-text-gray_05" data-all-weeksum-learning>0</span>
                                    <span class="text-sb-32px scale-text-gray_05">강</span>
                                </div>
                            </div>
                        </div>
                        <div class="px-0 row row-cols-4 col">
                        @if (!empty($subject_codes))
                        @foreach ($subject_codes as $idx => $subject_code)
                            <div class="px-0" data-sbuejct-target-div="{{$subject_code->id}}" onclick="clickTargetLearningStatusBySubject(this)">
                                <div class="row mx-0 justify-content-center p-4 rounded-3 scale-bg-gray_01 align-items-center primary-bg-mian-hover mb-2 ms-2">
                                    <div class="col-auto scale-text-gray_05 text-sb-20px px-0 scale-text-white-hover">{{$subject_code->code_name}}</div>
                                    <div class="col text-sb-28px px-0 text-end">
                                        <span class="scale-text-white-hover text-black" data-cnt-complete-learning>0</span>
                                        <span class="scale-text-white-hover scale-text-gray_05">/</span>
                                        <span class="scale-text-white-hover scale-text-gray_05" data-cnt-weeksum-learning>0</span><span class="scale-text-white-hover scale-text-gray_05">강</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="modal-shadow-style rounded-3 p-4 mt-4">
                <div class="h-center">
                    <div class="col h-center gap-2">
                        <img src="{{ asset('images/window_memo_icon.svg') }}" width="32">
                        <span class="text-b-24px">학습 수행 내역</span>
                    </div>
                    <div class="col-auto">
                        <button class="btn h-center p-0" data-btn-my-study-week-section="7"
                            onclick="myStudyWeekSection(this);">
                            <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32">
                        </button>
                    </div>
                </div>
                {{-- --}}
                <div data-div-my-study-week-section-sub="7" class="pt-4 mt-2 mb-5 pb-1" hidden>
                    {{-- 상단 6개 --}}
                    <div class="row row-cols-3 gx-3 gy-3">
                        <div>
                            <div class="scale-bg-gray_01 p-4 rounded-2 row">
                                <div class="col px-0">
                                    <span class="text-sb-20px">주간 수행률</span>
                                </div>
                                <div class="col text-end px-0">
                                    <span class="text-sb-28px" data-complete-per>0%</span>
                                </div>
                            </div>
                        </div>

                        <div data-learning-performance-details onclick="dataLearningPerformanceDetailsClick(this);">
                            <div class="border p-4 rounded-2 row bg-blue-hover">
                                <div class="col px-0 h-center gap-2">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="10" cy="10" r="8" stroke="#18DB72" stroke-width="4"/>
                                    </svg>
                                    <span class="text-sb-20px">계획대로 했어요.</span>
                                </div>
                                <div class="col-auto text-end px-0 text-sb-28px row justify-content-end">
                                    <span class="col-auto scale-text-white-hover text-black px-0" data-plan-complete-cnt>0</span>
                                    <div class="scale-text-white-hover scale-text-gray_05 col-auto px-0 ms-2" >
                                        <span>/</span>
                                        <span data-all-weeksum-learning>0</span><span>강</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div data-learning-performance-details onclick="dataLearningPerformanceDetailsClick(this);">
                            <div class="border p-4 rounded-2 row bg-blue-hover">
                                <div class="col px-0 h-center gap-2 cursor-pointer" onclick="myStudtyAfterDone();">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="10" cy="10" r="8" stroke="#5057FF" stroke-width="4"/>
                                    </svg>
                                    <span class="text-sb-20px">나중에 했어요.</span>
                                </div>
                                <div class="col-auto text-end px-0 text-sb-28px row justify-content-end">
                                    <span class="col-auto scale-text-white-hover text-black px-0" data-after-complete-cnt>0</span>
                                    <div class="scale-text-white-hover scale-text-gray_05 col-auto px-0 ms-2" >
                                        <span>/</span>
                                        <span data-all-weeksum-learning>0</span><span>강</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <div class="scale-bg-gray_01 p-4 rounded-2 row">
                                <div class="col px-0">
                                    <span class="text-sb-20px">학습 완료</span>
                                </div>
                                <div class="col-auto text-end px-0 text-sb-28px row justify-content-end">
                                    <span class="col-auto scale-text-white-hover text-black px-0" data-all-complete-cnt>0</span>
                                    <div class="scale-text-white-hover scale-text-gray_05 col-auto px-0 ms-2" >
                                        <span>/</span>
                                        <span data-all-weeksum-learning>0</span><span>강</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div data-learning-performance-details onclick="dataLearningPerformanceDetailsClick(this);">
                            <div class="border p-4 rounded-2 row bg-blue-hover">
                                <div class="col px-0 h-center gap-2">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="10" cy="10" r="8" stroke="#FF5065" stroke-width="4"/>
                                    </svg>
                                    <span class="text-sb-20px">안 했어요.</span>
                                </div>
                                <div class="col-auto text-end px-0 text-sb-28px row justify-content-end">
                                    <span class="col-auto scale-text-white-hover text-black px-0" data-not-complete-cnt>0</span>
                                    <div class="scale-text-white-hover scale-text-gray_05 col-auto px-0 ms-2" >
                                        <span>/</span>
                                        <span data-all-weeksum-learning>0</span><span>강</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div data-learning-performance-details onclick="dataLearningPerformanceDetailsClick(this);">
                            <div class="border p-4 rounded-2 row bg-blue-hover">
                                <div class="col px-0 h-center gap-2">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="10" cy="10" r="8" stroke="#FFC747" stroke-width="4"/>
                                    </svg>
                                    <span class="text-sb-20px">스스로 학습</span>
                                </div>
                                <div class="col-auto text-end px-0 text-sb-28px row justify-content-end">
                                    <span class="col-auto scale-text-white-hover text-black px-0" data-all-self-learning-cnt>0</span>
                                    <!-- <div class="scale-text-white-hover scale-text-gray_05 col-auto px-0 ms-2" > -->
                                    <!--     <span>/</span> -->
                                    <!--     <span>24</span><span>강</span> -->
                                    <!-- </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    {{--그래프 --}}
                    <div class="mt-5 pt-2 pb-5">
                        <!-- 시간단위 -->
                        <div class="m-1 row" style="height:250px;">
                            <div data-bundle="performance_time"
                                class="col-auto m-0 d-flex flex-column position-relative" style="width:60px;">
                                <div class="col mb-2 position-absolute d-flex flex-column"
                                    style="bottom:4px;height:250px;">
                                    <div data-row="4" class="col text-sb-18px scale-text-gray_05">15강</div>
                                    <div data-row="4" class="col text-sb-18px scale-text-gray_05">12강</div>
                                    <div data-row="3" class="col text-sb-18px scale-text-gray_05">9강</div>
                                    <div data-row="2" class="col text-sb-18px scale-text-gray_05">6강</div>
                                    <div data-row="1" class="col text-sb-18px scale-text-gray_05">3강</div>
                                </div>
                                <div data-row="0" class="position-absolute text-sb-18px scale-text-gray_05"
                                    style="bottom:-10px">
                                    0강</div>
                            </div>
                            <div class="col position-relative">
                                <div class=" d-flex flex-column ms-4 h-100">
                                    <div class="col"
                                        style="border-bottom:1px solid #E5E5E5;border-top:1px solid #E5E5E5"></div>
                                    <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                                    <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                                    <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                                    <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                                </div>

                                <!-- ------------------------------------------------------------------------------  -->
                                <!-- 과목별 학습시간 수업 그래프 시작. -->
                                <div data-bundle="learning_performance"
                                    class="row mx-0 position-absolute top-0 bottom-0 start-0 end-0 ms-4 px-3">
                                    @if (!empty($subject_codes))
                                    @foreach ($subject_codes as $idx => $subject_code)
                                    <div data-row="{{ $subject_code->id }}"
                                        class="col row gap-1 align-items-end justify-content-center position-relative me-0 px-0">
                                        <!-- 계획 -->
                                        <div data-plan-bar
                                            class="col-auto rounded-top-3 px-3"
                                            style="height:0%;background:#18DB72;width:24px"> </div>
                                        <!-- 나중에 -->
                                        <div data-later-bar
                                            class="col-auto rounded-top-3 px-3 ms-1"
                                            style="height:0%;background:#5057FF;width:24px"> </div>
                                        <!-- 안햇어요 -->
                                        <div data-not-bar
                                            class="col-auto rounded-top-3 px-3 ms-1"
                                            style="height:0%;background:#FF5065;width:24px"> </div>
                                        <!-- 스스로 학습 BAR -->
                                        <div  data-self-bar
                                            class="col-auto rounded-top-3 px-3 ms-1"
                                            style="height:0%;background:#FFC747;width:24px"> </div>
                                        <div class="position-absolute text-center" style="bottom:-62px;">
                                            <button data-btn-my-study-week-subject-btm onclick="myStudyWeekSubjectTimeBtmClick(this)"
                                                class="btn btn-outline-primary-y border-0 rounded-pill scale-text-white-hover text-sb-20px scale-text-gray_05 {{ $idx == 0 ? 'active' : '' }}"
                                                style="padding:4px 16px">
                                                {{ $subject_code->code_name }}
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        @endif
    </div>
    {{-- 월간 묶음 SECTION --}}
    <div data-div-my-study-main-bundle="month" hidden>
        {{-- 과목별 목표 달성 현황 --}}
        <section class="pt-4">
            <div class=" modal-shadow-style rounded-3 p-4">
                <div class="h-center">
                    <div class="col h-center gap-2">
                        <img src="{{ asset('images/goal_icon.svg') }}" width="32">
                        <span class="text-b-24px">과목별 목표 달성 현황</span>
                    </div>
                    <div class="col-auto">
                        <button class="btn h-center p-0" data-btn-my-study-month-section="1"
                            onclick="myStudyMonthSection(this);">
                            <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32">
                        </button>
                    </div>
                </div>
                <div data-div-my-study-month-section-sub="1" class="pt-4 mt-2 mb-5 pb-1" hidden>
                    {{-- bundle --}}
                    <div class="row mx-0 row-cols-lg-3 gx-4 gy-4">
                        {{-- row --}}
                        @if (!empty($subject_codes))
                            @foreach ($subject_codes as $idx => $subject_code)
                                <div
                                    class="{{ ($idx + 1) % 4 == 0 || $idx == 0 ? 'ps-0' : (($idx + 1) % 3 == 0 ? 'pe-0' : '') }}">
                                    <div class="row mx-0 p-4 rounded-3 scale-bg-gray_01">
                                        <div class="col-auto px-0">
                                            <img src="{{ asset('images/' . $subject_code->function_code . '.svg') }}"
                                                width="72">
                                        </div>
                                        <div class="col">
                                            <span class="col-auto text-sb-20px scale-text-gray_05">
                                                {{ $subject_code->code_name }}
                                            </span>
                                            <div class="col pt-2">
                                                <span class="text-b-32px">10강</span>
                                                <span class="text-b-32px scale-text-gray_05">/</span>
                                                <span class="text-b-32px scale-text-gray_05">20강</span>
                                            </div>
                                        </div>
                                        {{-- 퍼센테이지 --}}
                                        <div class="pt-4 px-0">
                                            <div class="bg-white rounded-pill">
                                                <div class="primary-bg-mian rounded-pill"
                                                    style="width:50%;height:12px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>
            </div>
        </section>
        {{-- N월 출결 현황 --}}
        <section>
            <div class="modal-shadow-style rounded-3 p-4 mt-4">
                <div class="h-center">
                    <div class="col h-center gap-2">
                        <img src="{{ asset('images/weekly_outing_status_icon.svg') }}" width="32">
                        <span class="text-b-24px"><span data-sp-month>N</span>월 출결 현황</span>
                    </div>
                    <div class="col-auto">
                        <button class="btn h-center p-0" data-btn-my-study-month-section="2"
                            onclick="myStudyMonthSection(this);">
                            <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32">
                        </button>
                    </div>
                </div>
                <div data-div-my-study-month-section-sub="2" class="pt-4 mt-2 mb-5 pb-1" hidden>
                    {{-- 출석횟수, 6일 /지각횟수 총 3회 --}}
                    <div class="row mx-0 align-items-center justify-content-end">
                        <span class="col-auto text-r-16px scale-text-gray_05 px-0">출석횟수</span>
                        <span class="col-auto text-sb-24px text-danger ps-2">20일</span>
                        <span class="col-auto text-r-16px scale-text-gray_05 ps-4">지각/미접속</span>
                        <span class="col-auto text-sb-24px text-danger ps-2">2회 / 5회</span>
                    </div>
                    {{-- 52 --}}
                    <div class="pt-5 mt-1">
                        {{-- bundle --}}
                        <div class="">
                            <div class="row border-bottom mx-0 p-4">
                                <div class="col-auto">
                                    <span
                                        class="btn btn-outline-danger scale-bg-gray_02 scale-text-gray_05 border-0 active  rounded-pill text-b-20px px-3">1주차</span>
                                </div>
                                <div class="col-auto h-center px-0">
                                    <img src="{{ asset('images/bar_icon.svg') }}" width="2"
                                        height="12">
                                </div>
                                <div class="col-auto h-center">
                                    <span class="col-auto text-sb-20px scale-text-gray_05 px-0">주차별 출결
                                        현황</span>
                                    <span class="col-auto text-sb-24px ps-2">4일</span>
                                    <span class="col-auto text-sb-24px ps-2">/ 6일</span>
                                </div>
                                <div class="col h-center justify-content-end">
                                    <span class="text-danger text-b-20px">7월 2일 지각 1회 / 미접속 2회</span>
                                </div>
                            </div>

                            <div class="row border-bottom mx-0 p-4">
                                <div class="col-auto">
                                    <span
                                        class="btn btn-outline-danger scale-bg-gray_02 scale-text-gray_05 border-0  rounded-pill text-b-20px px-3">2주차</span>
                                </div>
                                <div class="col-auto h-center px-0">
                                    <img src="{{ asset('images/bar_icon.svg') }}" width="2"
                                        height="12">
                                </div>
                                <div class="col-auto h-center">
                                    <span class="col-auto text-sb-20px scale-text-gray_05 px-0">주차별 출결
                                        현황</span>
                                    <span class="col-auto text-sb-24px ps-2">7일</span>
                                    <span class="col-auto text-sb-24px ps-2">/ 7일</span>
                                </div>
                                <div class="col h-center justify-content-end">
                                    <span class="text-b-20px">목표 달성</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        {{-- 월별 출결 현황 --}}
        <section>
            <div class="modal-shadow-style rounded-3 p-4 mt-4">
                <div class="h-center">
                    <div class="col h-center gap-2">
                        <img src="{{ asset('images/weekly_outing_status_icon.svg') }}" width="32">
                        <span class="text-b-24px">월별 출결 현황</span>
                    </div>
                    <div class="col-auto">
                        <button class="btn h-center p-0" data-btn-my-study-month-section="3"
                            onclick="myStudyMonthSection(this);">
                            <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32">
                        </button>
                    </div>
                </div>
                <div data-div-my-study-month-section-sub="3" class="pt-4 mt-2 mb-5 pb-1" hidden>
                    <div>
                        <div class="py-lg-5"></div>
                    </div>
                    <div class="m-1 row" style="height:300px;">
                        <div class="col-auto m-0 d-flex flex-column position-relative" style="width:60px;">
                            <div class="col mb-2 position-absolute d-flex flex-column"
                                style="bottom:4px;height:300px;">
                                <div class="col text-sb-18px scale-text-gray_05">30</div>
                                <div class="col text-sb-18px scale-text-gray_05">25</div>
                                <div class="col text-sb-18px scale-text-gray_05">20</div>
                                <div class="col text-sb-18px scale-text-gray_05">15</div>
                                <div class="col text-sb-18px scale-text-gray_05">10</div>
                                <div class="col text-sb-18px scale-text-gray_05">5</div>
                            </div>
                            <div class="position-absolute text-sb-18px scale-text-gray_05"
                                style="bottom:-10px">
                                0</div>
                        </div>
                        <div class="col position-relative">
                            <div class=" d-flex flex-column ms-4 h-100">
                                <div class="col"
                                    style="border-bottom:1px solid #E5E5E5;border-top:1px solid #E5E5E5"></div>
                                {{-- <div class="col" style="border-bottom:1px solid #E5E5E5"></div> --}}
                                <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                                <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                                <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                                <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                                <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                            </div>
                            <div class="row mx-0 position-absolute top-0 bottom-0 start-0 end-0 ms-4 px-5">
                                {{-- <div class="col-1"></div> --}}
                                @if (!empty($month_array))
                                    @foreach ($month_array as $idx => $mh)
                                        <div
                                            class="col row gap-2 align-items-end justify-content-center position-relative">
                                            {{-- 상단 --}}
                                            <div class="position-absolute text-center" style="top: -73px"
                                                {{ $idx == 0 ? '' : 'hidden' }}>
                                                <span class="text-white text-b-20px rounded-3"
                                                    style="background: #FFC747;padding:12px 20px;">
                                                    <span class="text-white">22일</span>
                                                </span>
                                                <div class="position-relative">
                                                    <img src="{{ asset('images/yellow_arrow_down_icon.svg') }}"
                                                        width="18" class="position-absolute"
                                                        style="left: 43%;bottom:-18px">
                                                </div>
                                            </div>

                                            {{-- 중단 --}}
                                            <div class="col-auto rounded-top-3 px-3 ms-1"
                                                style="height:80%;background:#FFC747"> </div>

                                            {{-- 하단 --}}
                                            <div class="position-absolute text-center" style="bottom:-62px;">
                                                <button
                                                    class="btn btn-outline-primary-y border-0 rounded-pill text-sb-20px scale-text-gray_05 {{ $idx == 0 ? 'active' : '' }}"
                                                    style="padding:4px 16px">
                                                    {{ $mh }}월
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                {{-- <div class="col-1"></div> --}}
                            </div>
                        </div>
                    </div>

                    {{-- padding 125px --}}
                    <div style="height: 125px;"></div>

                </div>
            </div>
        </section>
    </div>

{{--  모달 / 나중에 완료한 수강목록 --}}
<div class="modal fade " id="modal_after_done_learning" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog rounded modal-lg modal-center" style="min-width:1004px;">
    <div class="modal-content border-none rounded p-3 modal-shadow-style">
      <div class="modal-header border-bottom-0">
        <h1 class="modal-title fs-5 text-b-24px h-center" id="">
            <img src="{{asset('images/weekly_outing_status_icon.svg')}}" width="32">
            <span> 나중에 완료한 수강 목록</span>
        </h1>
        <button type="button" style="width:32px;height:32px"
        class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div data-bundle="after_done_learning" class="row row-cols-2">
            <div data-row="copy" class="p-2">
                <div class="row mx-0 div-shadow-style p-xxl-4 p-xl-2 p-md-2 rounded-1">
                    <div class="col-auto d-xl-block d-lg-none">
                        <img src="{{ asset('images/subject_social_icon.svg')}}" style="width:72px;height:72px;" class="w-md-75 w-xl-100">
                    </div>
                    <div class="col d-flex flex-column justify-content-center">
                        <div class="text-sb-18px scale-text-gray_05 text-start mb-2">학교공부예습</div>
                        <div class="text-sb-20px text-start" data-lecture-name>[1단원] 우리 고장의 모습</div>
                    </div>
                    <div class="col-auto">
                        <img src="{{asset('images/yellow_bookmark_icon.svg')}}" width="16" class="noactive" onclick="myStudyPrestudyReview(this)">
                    </div>
                </div>
            </div>
            <div data-row="copy" class="p-2">
                <div class="row mx-0 div-shadow-style p-xxl-4 p-xl-2 p-md-2 rounded-1">
                    <div class="col-auto d-xl-block d-lg-none">
                        <img src="{{ asset('images/subject_kor_icon.svg')}}" style="width:72px;height:72px;" class="w-md-75 w-xl-100">
                    </div>
                    <div class="col d-flex flex-column justify-content-center">
                        <div class="text-sb-18px scale-text-gray_05 text-start mb-2">학교공부예습</div>
                        <div class="text-sb-20px text-start" data-lecture-name>[1단원] 우리 고장의 모습</div>
                    </div>
                    <div class="col-auto">
                        <img src="{{asset('images/yellow_bookmark_icon.svg')}}" width="16" class="noactive" onclick="myStudyPrestudyReview(this)">
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

</article>


<script>

document.addEventListener("DOMContentLoaded", function() {
});
document.addEventListener("DOMContentLoaded", function() {
    myStudyWeekTotalSelect();
});


// 통합 SELECT
function myStudyWeekTotalSelect(){
    // 요일별 학습시간.
    myStudyShowWeeklyStudyTimeSelect(function(result){

        // 요일별 목표 학습 현황
        myStudyGoalLearningStatus(result);
    });
    // 과목별 학습시간.
    myStudyWeekSubjectTimeSelect(function(result){

        myStudyTargetLearningStatusBySubject(result);

        // 학습 수행 내역
        myStudyLearningPerformanceDetails(result);
    });

    // 주간 학습 상세.
    myStudyWeeklyLearningDetails();

    // 주간 출결 현황.
    myStudyShowWeeklyAttendanceStatusSelct();

}

        // 주간 > 주차 > SECTION 상세 확인
        function myStudyWeekSection(vthis) {
            if (vthis.classList.contains('active')) {
                vthis.classList.remove('active');
                vthis.classList.remove('rotate-180');
            } else {
                // 일단은 모두 가능하게.
                // document.querySelectorAll('[data-btn-my-study-week-section]').forEach(function(item){
                //     item.classList.remove('active');
                // });
                vthis.classList.add('active');
                vthis.classList.add('rotate-180');
            }
            const num = vthis.getAttribute('data-btn-my-study-week-section');
            myStudyWeekSectionSub(num);

        }

        // 주간 > 주차 > SECTION 상세 확인
        function myStudyWeekSectionSub(type) {
            const div_sub = document.querySelector('[data-div-my-study-week-section-sub="' + type + '"]');
            if (div_sub.hidden) {
                div_sub.hidden = false;
            } else {
                div_sub.hidden = true;
            }
        }


// 요일별 학습시간.
function myStudyShowWeeklyStudyTimeSelect(callback){
    const span_month = document.querySelector('#my_study_span_month');
    const sel_date = span_month.getAttribute('data');
    const date = new Date(sel_date);
    const year = date.getFullYear();
    const month = (date.getMonth()+1);
    let student_seq = '';
    if(document.querySelectorAll('[data-main-student-seq]').length > 0)
       student_seq = document.querySelector('[data-main-student-seq]').value;

    const week_cnt = document.querySelector('.div_week_row button.active span').innerText;
    const page = "/student/my/study/weekly/time/select";
    const paramter = {
        year:year,
        month:month,
        week_cnt: week_cnt,
        student_seq:student_seq
    };
    queryFetch(page, paramter, function(result){
        if((result.resultCode||'') == 'success'){
            const bundle_week = document.querySelector('[data-bundle="study_time_by_day"]');
            const bundle_time = document.querySelector('[data-bundle="week_time"]');
            const student_lecture_details = result.student_lecture_details;
            const keys_details = Object.keys(student_lecture_details);
            if(student_lecture_details && keys_details.length > 0){
                const details = student_lecture_details;

                const sun_infos = details['일']; //일요일
                const mon_infos = details['월']; //월요일
                const tue_infos = details['화']; //화요일
                const wed_infos = details['수']; //수요일
                const thu_infos = details['목']; //목요일
                const fri_infos = details['금']; //금요일
                const sat_infos = details['토']; //토요일

                let max_sec = 0;
                // TODO: data-goal-bar 넣기 현재 없음.
                const sun_sec = myStudyShowWeekInDayTime(sun_infos, '일');
                const mon_sec = myStudyShowWeekInDayTime(mon_infos, '월');
                const tue_sec = myStudyShowWeekInDayTime(tue_infos, '화');
                const wed_sec = myStudyShowWeekInDayTime(wed_infos, '수');
                const thu_sec = myStudyShowWeekInDayTime(thu_infos, '목');
                const fri_sec = myStudyShowWeekInDayTime(fri_infos, '금');
                const sat_sec = myStudyShowWeekInDayTime(sat_infos, '토');

                // 퍼센테이지 계산.
                let sun_per = 0;
                let mon_per = 0;
                let tue_per = 0;
                let wed_per = 0;
                let thu_per = 0;
                let fri_per = 0;
                let sat_per = 0;


                // 제일 큰 시간을 가려내서 max_sec에 넣는다.
                const sec_array = [sun_sec, mon_sec, tue_sec, wed_sec, thu_sec, fri_sec, sat_sec];
                sec_array.forEach(function(sec){
                    if(sec > max_sec){
                        max_sec = sec;
                    }
                });

                let max_line_sec = 3600;
                let time_zone = 15; // 처음은 분
                let time_zone_after = '분';

                // max_sec 가 1시간 보다 작으면  1시간이 100%
                if(max_sec < 3600){
                    max_line_sec = 3600;
                    time_zone = 15; // 처음은 분
                    time_zone_after = '분';
                }
                // 4시간 보다 작으면
                else if( max_sec < 14400){
                    max_line_sec = 14400;
                    time_zone = 1; // 처음은 분
                    time_zone_after = '시간';
                }
                // 8시간 보다 작으면
                else if(max_sec < 28800){
                    max_line_sec = 28800;
                    time_zone = 2;
                    time_zone_after = '시간';
                }
                // 16시간 보다 작으면
                else if(max_sec < 57600){
                    max_line_sec = 57600;
                    time_zone = 4;
                    time_zone_after = '시간';
                }

                sun_per = (sun_sec / max_line_sec) * 100;
                mon_per = (mon_sec / max_line_sec) * 100;
                tue_per = (tue_sec / max_line_sec) * 100;
                wed_per = (wed_sec / max_line_sec) * 100;
                thu_per = (thu_sec / max_line_sec) * 100;
                fri_per = (fri_sec / max_line_sec) * 100;
                sat_per = (sat_sec / max_line_sec) * 100;

                bundle_time.querySelector('[data-row="0"]').innerText = "0"+time_zone_after;
                bundle_time.querySelector('[data-row="1"]').innerText = time_zone+time_zone_after;
                bundle_time.querySelector('[data-row="2"]').innerText = (time_zone*2)+time_zone_after;
                bundle_time.querySelector('[data-row="3"]').innerText = (time_zone*3)+time_zone_after;
                bundle_time.querySelector('[data-row="4"]').innerText = (time_zone*4)+time_zone_after;


                bundle_week.querySelector('[data-row="일"] [data-top-self-time]').innerText = formatSecToTime(sun_sec);
                bundle_week.querySelector('[data-row="일"] [data-self-bar]').style.height = sun_per+'%';
                bundle_week.querySelector('[data-row="월"] [data-top-self-time]').innerText = formatSecToTime(mon_sec);
                bundle_week.querySelector('[data-row="월"] [data-self-bar]').style.height = mon_per+'%';
                bundle_week.querySelector('[data-row="화"] [data-top-self-time]').innerText = formatSecToTime(tue_sec);
                bundle_week.querySelector('[data-row="화"] [data-self-bar]').style.height = tue_per+'%';
                bundle_week.querySelector('[data-row="수"] [data-top-self-time]').innerText = formatSecToTime(wed_sec);
                bundle_week.querySelector('[data-row="수"] [data-self-bar]').style.height = wed_per+'%';
                bundle_week.querySelector('[data-row="목"] [data-top-self-time]').innerText = formatSecToTime(thu_sec);
                bundle_week.querySelector('[data-row="목"] [data-self-bar]').style.height = thu_per+'%';
                bundle_week.querySelector('[data-row="금"] [data-top-self-time]').innerText = formatSecToTime(fri_sec);
                bundle_week.querySelector('[data-row="금"] [data-self-bar]').style.height = fri_per+'%';
                bundle_week.querySelector('[data-row="토"] [data-top-self-time]').innerText = formatSecToTime(sat_sec);
                bundle_week.querySelector('[data-row="토"] [data-self-bar]').style.height = sat_per+'%';



                const bundle_main = document.querySelector('[data-div-my-study-week-section-sub="1"]');

                // 주간합계 구하기.
                const week_total = sun_sec+mon_sec+tue_sec+wed_sec+thu_sec+fri_sec+sat_sec;
                bundle_main.querySelector('[data-week-total]').innerText = formatSecToTime(week_total);

                // 하루평균 구하기. 반올림
                const week_average = Math.floor(week_total/7);
                document.querySelectorAll('[data-today-average]')[0].innerText = formatSecToTime(week_average);
                document.querySelectorAll('[data-today-average]')[1].innerText = formatSecToTime(week_average);


                // 오늘 요일 선택 클릭.
                const today_week = new Date().format('e');
                bundle_week.querySelector(`[data-row="${today_week}"] [data-btn-my-study-week-btm]`).click();
            }
            if(callback != undefined){
                callback(result);
            }
        }else{}
    });
}
//초를 넣으면 분으로 바꿔준다.
//60분이 넘으면 1시간 1분으로 표기한다.
function formatSecToTime(time) {
    // 초를 분으로 변환
    let minutes = Math.floor(time / 60);
    let seconds = time % 60;

    // 60분 이상인 경우 시간으로 변환
    if (minutes >= 60) {
        let hours = Math.floor(minutes / 60);
        minutes = minutes % 60;

        // 시간, 분, 초 형식으로 반환
        return `${hours}시간 ${minutes}분 ${seconds}초`;
    } else {
        // 분, 초 형식으로 반환
        return `${minutes}분 ${seconds}초`;
    }
}
// 요일별 학습시간 하단 요일 클릭.
function myStudyShowWeeklyStudyTimeBtmBtnClick(vthis){
    const row = vthis.closest('[data-row]');
    const bundle = document.querySelector('[data-bundle="study_time_by_day"]');
    // data-btn-my-study-week-btm 의 모두 비활성화.
    document.querySelectorAll('[data-btn-my-study-week-btm]').forEach(function(el){
        el.classList.remove('active');
    });
    vthis.classList.add('active');

    bundle.querySelectorAll('[data-div-self-time]').forEach(function(el){
        el.hidden = true;
    });
    row.querySelector('[data-div-self-time]').hidden = false;

}

function myStudyShowWeekInDayTime (infos, weekday) {
    let total_sec = 0;
    if(infos && infos.length > 0){
        infos.forEach(function(info){
            total_sec += (info.last_video_time||0);
        });
    }
    return total_sec;
}

// 주간출결 현황.
function myStudyShowWeeklyAttendanceStatusSelct() {
    const params = getAttendanceParams();
    queryFetch("/student/my/study/weekly/attendance/status/select", params, handleAttendanceResult);
}

// 파라미터 수집 함수
function getAttendanceParams() {
    const span_month = document.querySelector('#my_study_span_month');
    const date = new Date(span_month.getAttribute('data'));
    const week_cnt = document.querySelector('.div_week_row button.active span').innerText;
    const student_seq = document.querySelector('[data-main-student-seq]')?.value || '';

    return {
        year: date.getFullYear(),
        month: date.getMonth() + 1,
        week_cnt: week_cnt,
        student_seq: student_seq,
    };
}

// 결과 처리 함수
function handleAttendanceResult(result) {
    if (result.resultCode !== 'success') return;

    const { study_times, attends } = result;
    const stats = initializeStats();
    const bundle = initializeBundle();
    const today = new Date().format('yyyy-MM-dd');

    study_times.forEach(study_time => {
        const row = createAttendanceRow(study_time, attends, today, stats);
        if (row) bundle.appendChild(row);
    });
    const attend_keys = Object.keys(attends);
    attend_keys.forEach(attend_key => {
        const rows = bundle.querySelectorAll(`[data-row="clone"][data-sel-date="${attend_key}"]`);
        if(rows.length < 1){
            const study_time_make = {
                select_date: attend_key,
                select_time:'',
                is_repeat:'N',
                student_seq:'',
                day_of_week:new Date(attend_key).format('e').replace('요일', ''),
            };
            const row = createAttendanceRow(study_time_make, attends, today, stats);
            if (row) bundle.appendChild(row);
        }
    });

    updateAttendanceDisplay(stats, study_times.length);
}

// 통계 초기화
function initializeStats() {
    return {
        total_attend_cnt: 0,
        total_late_cnt: 0,
        total_absent_cnt: 0
    };
}

// 번들 초기화
function initializeBundle() {
    const bundle = document.querySelector('[data-bundle="week_attend_status"]');
    const row_copy = bundle.querySelector('[data-row="copy"]');
    bundle.innerText = '';
    bundle.appendChild(row_copy);
    return bundle;
}

// 출석 행 생성
function createAttendanceRow(study_time, attends, today, stats) {
    const date_key = study_time.select_date;
    const pt_div = document.querySelector('[data-div-my-study-week-section-sub="4"]');
    const row = pt_div.querySelector('[data-row="copy"]').cloneNode(true);

    row.hidden = false;
    row.setAttribute('data-row', 'clone');

    setRowBasicInfo(row, study_time, attends[date_key]);

    const attendanceStatus = calculateAttendanceStatus(study_time, attends[date_key], today);
    updateStatsAndRowDisplay(row, attendanceStatus, stats);

    return row;
}

// 행 기본 정보 설정
function setRowBasicInfo(row, study_time, attend_data) {
    row.querySelector('[data-day]').innerText = `${study_time.day_of_week}요일`;
    row.querySelector('[data-select-time]').innerText = (study_time.select_time || '').substr(0, 5);
    row.querySelector('[data-start_time]').innerText = attend_data ?
        (attend_data[0]?.start_time || '').substr(0, 5) : '';
    row.dataset.selDate = study_time.select_date;
}

// 출석 상태 계산
function calculateAttendanceStatus(study_time, attend_data, today) {
    if (( !attend_data )) return null;

    if (attend_data.length === 0) {
        if (study_time.select_date < today) return 'absent';
        if (study_time.select_date >= today) return 'future';
        return null;
    }

    const select_time = study_time.select_time.split(':');
    const start_time = attend_data[0].start_time.split(':');
    const time_diff = calculateTimeDifference(select_time, start_time);

    if(!study_time.select_time) return 'undecided';
    return time_diff < 0 ? 'ontime' : 'late';
}

// 시간 차이 계산
function calculateTimeDifference(select_time, start_time) {
    const select_min = parseInt(select_time[0]) * 60 + parseInt(select_time[1]);
    const start_min = parseInt(start_time[0]) * 60 + parseInt(start_time[1]);
    return start_min - select_min;
}

// 통계 및 행 표시 업데이트
function updateStatsAndRowDisplay(row, status, stats) {
    const statusDisplay = {
        'ontime': { text: '정상 출결', isLate: false },
        'late': { text: '지각', isLate: true },
        'absent': { text: '미출결', isLate: false },
        'future': { text: '', isLate: false },
        'undecided':{ text: '미정', isLate: false },
    };

    if (!status) return;

    const display = statusDisplay[status];
    const lateMinElement = row.querySelector('[data-late-min]');

    if (!display.isLate) {
        lateMinElement.classList.remove('text-danger');
    }

    lateMinElement.innerText = display.text;
    updateStats(stats, status);
}

// 통계 업데이트
function updateStats(stats, status) {
    if (status === 'ontime' || status === 'late' || status === 'undecided') stats.total_attend_cnt++;
    if (status === 'late') stats.total_late_cnt++;
    if (status === 'absent') stats.total_absent_cnt++;
}

// 출석 표시 업데이트
function updateAttendanceDisplay(stats, total_days) {
    const { total_attend_cnt, total_late_cnt } = stats;
    const none_late_cnt = total_attend_cnt - total_late_cnt;
    const attend_percent = total_days > 0 ?
        ((total_attend_cnt / total_days) * 100).toFixed(0) : 0;

    document.querySelector('[data-total-attend-cnt]').innerText = `${total_attend_cnt}일`;
    document.querySelector('[data-total-late-cnt]').innerText = `총 ${total_late_cnt}회`;
    document.querySelector('[data-main-total-late-cnt]').innerText = `총 ${none_late_cnt}일 지각`;
    document.querySelector('[data-main-attend-per]').innerText = attend_percent;
}


// 과목별 학습시간
function myStudyWeekSubjectTimeSelect(callback){
    const span_month = document.querySelector('#my_study_span_month');
    const sel_date = span_month.getAttribute('data');
    const date = new Date(sel_date);
    const year = date.getFullYear();
    const month = (date.getMonth()+1);
    const week_cnt = document.querySelector('.div_week_row button.active span').innerText;
    let student_seq = '';
    if(document.querySelectorAll('[data-main-student-seq]').length > 0)
       student_seq = document.querySelector('[data-main-student-seq]').value;
    const page = "/student/my/study/weekly/subject/time/select";
    const paramter = {
        year:year,
        month:month,
        week_cnt: week_cnt,
        student_seq: student_seq,
    };
    queryFetch(page, paramter, function(result){
        if((result.resultCode||'') == 'success'){
            const bundle_subject = document.querySelector('[data-bundle="study_time_by_subject"]');
            const bundle_time = document.querySelector('[data-bundle="subject_time"]');
            const student_lecture_details = result.student_lecture_details;
            const keys_details = Object.keys(student_lecture_details);
            if(student_lecture_details && keys_details.length > 0){
                const details = student_lecture_details;

                let idx = 0;
                const array_infos = new Array();
                let max_sec = 0;
                // TODO: data-goal-bar 넣기 현재 없음.
                let array_sec = [];
                let array_per = [];
                keys_details.forEach(function(key){
                    array_infos.push(details[key]);
                    array_sec.push(myStudyShowWeekInDayTime(details[key], key));
                // 퍼센테이지 계산.
                    array_per.push(0);
                    idx++;
                })

                // 제일 큰 시간을 가려내서 max_sec에 넣는다.
                array_sec.forEach(function(sec){
                    if(sec > max_sec){
                        max_sec = sec;
                    }
                });

                let max_line_sec = 3600;
                let time_zone = 15; // 처음은 분
                let time_zone_after = '분';

                // max_sec 가 1시간 보다 작으면  1시간이 100%
                if(max_sec < 3600){
                    max_line_sec = 3600;
                    time_zone = 15; // 처음은 분
                    time_zone_after = '분';
                }
                // 4시간 보다 작으면
                else if( max_sec < 14400){
                    max_line_sec = 14400;
                    time_zone = 1; // 처음은 분
                    time_zone_after = '시간';
                }
                // 8시간 보다 작으면
                else if(max_sec < 28800){
                    max_line_sec = 28800;
                    time_zone = 2;
                    time_zone_after = '시간';
                }
                // 16시간 보다 작으면
                else if(max_sec < 57600){
                    max_line_sec = 57600;
                    time_zone = 4;
                    time_zone_after = '시간';
                }

                bundle_time.querySelector('[data-row="0"]').innerText = "0"+time_zone_after;
                bundle_time.querySelector('[data-row="1"]').innerText = time_zone+time_zone_after;
                bundle_time.querySelector('[data-row="2"]').innerText = (time_zone*2)+time_zone_after;
                bundle_time.querySelector('[data-row="3"]').innerText = (time_zone*3)+time_zone_after;
                bundle_time.querySelector('[data-row="4"]').innerText = (time_zone*4)+time_zone_after;


                array_sec.forEach(function(sec, idx){
                    const subject_seq = array_infos[idx][0].subject_seq;
                    const per = (sec / max_line_sec) * 100;
                    if(subject_seq == null) return;
                    bundle_subject.querySelector(`[data-row="${subject_seq}"] [data-top-self-time]`).innerText = formatSecToTime(sec);
                    bundle_subject.querySelector(`[data-row="${subject_seq}"] [data-self-bar]`).style.height = per+'%';
                });


                const bundle_main = document.querySelector('[data-div-my-study-week-section-sub="2"]');

                // 주간합계 구하기. array_sec 의 합산
                const week_total = array_sec.reduce((acc, cur) => acc + cur, 0);
                bundle_main.querySelector('[data-week-total]').innerText = formatSecToTime(week_total);

                // 하루평균 구하기. 반올림
                const week_average = Math.floor(week_total/7);
                bundle_main.querySelector('[data-today-average]').innerText = formatSecToTime(week_average);


                //첫번째 과목 선택.
                const today_week = new Date().format('e');
                bundle_subject.querySelector(`[data-row] [data-btn-my-study-week-subject-btm]`).click();
            }
            if(callback != undefined){
                callback(result);
            }
        }else{}
    });
}
// 과목별 학습시간 하단 과목 클릭.
function myStudyWeekSubjectTimeBtmClick(vthis){
    const row = vthis.closest('[data-row]');
    const bundle = document.querySelector('[data-bundle="study_time_by_subject"]');
    // data-btn-my-study-week-btm 의 모두 비활성화.
    document.querySelectorAll('[data-btn-my-study-week-subject-btm]').forEach(function(el){
        el.classList.remove('active');
    });
    vthis.classList.add('active');

    bundle.querySelectorAll('[data-div-self-time]').forEach(function(el){
        el.hidden = true;
    });
    row.querySelector('[data-div-self-time]').hidden = false;

}

// 과목별 목표 학습 현황 클릭
function clickTargetLearningStatusBySubject(vthis){
    // data-sbuejct-target-div  모두 active
    document.querySelectorAll('[data-sbuejct-target-div]').forEach(function(el){
        el.classList.remove('active');
    });
    vthis.classList.add('active');
}
// 요일별 목표 학습 현황 클릭
function clickTargetLearningStatusByDayWeek(vthis){
    // data-dayweek-target-div
    document.querySelectorAll('[data-dayweek-target-div]').forEach(function(el){
        el.classList.remove('active');
    });
    vthis.classList.add('active');
}

// 학습 수행 내역 클릭.
function dataLearningPerformanceDetailsClick(vthis){
    // data-learning-performance-details
    document.querySelectorAll('[data-learning-performance-details]').forEach(function(el){
        el.classList.remove('active');
    });
    vthis.classList.add('active');
    const type = vthis.getAttribute('data-learning-performance-details');
}

// 나중에 했어요.
function myStudtyAfterDone(){
    const myModal = new bootstrap.Modal(document.getElementById('modal_after_done_learning'), {
        keyboard: false,
        backdrop: 'static'
    });
    myModal.show();
}

// 예습복습 아이콘 클릭.
function myStudyPrestudyReview(vthis){
    if(vthis.classList.contains('active')){
        vthis.classList.add('noactive');
        vthis.classList.remove('active');
    }else{
        vthis.classList.add('active');
        vthis.classList.remove('noactive');
    }
}

// 주간 학습 상세
function myStudyWeeklyLearningDetails(){
    const span_month = document.querySelector('#my_study_span_month');
    const sel_date = span_month.getAttribute('data');
    const date = new Date(sel_date);
    const year = date.getFullYear();
    const month = (date.getMonth()+1);
    const week_cnt = document.querySelector('.div_week_row button.active span').innerText;
    const page = "/student/my/study/weekly/learning/detail/select";
    const paramter = {
        year:year,
        month:month,
        week_cnt: week_cnt,
    };
    queryFetch(page, paramter, function(result){
        // 초기화
        clearWeeklyStudyHeads();

        if((result.resultCode||'') == 'success'){
            const keys = Object.keys(result.student_lecture_details);
            const rowTemplate = document.querySelector('[data-div-weeky-study-head="copy"]').cloneNode(true);

            keys.forEach(function(key){
                const dataWeeks = result.student_lecture_details[key];
                renderWeeklyStudyRows(key, dataWeeks, rowTemplate);
            });
        }
    });

    function clearWeeklyStudyHeads() {
        document.querySelectorAll('[data-div-weeky-study-head="clone"]').forEach(el => el.remove());
    }

    function renderWeeklyStudyRows(key, dataWeeks, rowTemplate) {
        dataWeeks.forEach((data, idx) => {
            const bundle = document.querySelector(`[data-div-weeky-study-day="${key}"]`);
            const row = createWeeklyStudyRow(data, rowTemplate, idx);

            if(data.last_video_time) {
                setRowHeight(row, data.last_video_time);
                bundle.appendChild(row);
            }
        });
    }

    function createWeeklyStudyRow(data, template, idx) {
        const row = template.cloneNode(true);
        row.hidden = false;

        setRowContent(row, data);
        setStartTime(row, data.updated_at);
        setRandomSubjectColor(row);

        if(idx === 0) {
            formatFirstRow(row);
        }

        return row;
    }

    function setRowContent(row, data) {
        row.querySelector('[data-lecture-name]').innerHTML = data.lecture_detail_name;
        row.querySelector('[data-lecture-description]').innerHTML = data.lecture_detail_description;
        row.querySelector('[data-last-video-time]').innerText = formatSecToTime(data.last_video_time);
    }

    function setStartTime(row, updatedAt) {
        if (!updatedAt) return;

        const timeParts = updatedAt.split(' ');
        if (timeParts.length < 2) return;

        const timeStart = timeParts[1].substring(0,2);
        if(timeStart*1) {
            const startNum = timeStart - 8;
            row.querySelector('[data-start-cnt]').style.height = (startNum * 6.766) + '%';
        }
    }

    function setRandomSubjectColor(row) {
        const randomSubject = Math.floor(Math.random()*3)+1;
        row.querySelector('[data-div-weeky-study-detail]').style.background = `var(--color-subject${randomSubject})`;
    }

    function formatFirstRow(row) {
        row.classList.remove('col-auto');
        row.classList.add('col', 'w-100');

        row.childNodes.forEach(child => child.hidden = false);

        const detail = row.querySelector('[data-div-weeky-study-detail]');
        detail.childNodes.forEach(child => child.hidden = false);
        detail.classList.remove('p-1');
        detail.classList.add('p-2');
    }

    function setRowHeight(row, lastVideoTime) {
        const lastVideoTimeHour = Math.floor(lastVideoTime/60/60);
        const hour = lastVideoTimeHour < 1 ? 1 : lastVideoTimeHour;
        row.querySelector('[data-div-weeky-study-detail]').style.height = hour;
    }
}

// 요일별 목표 학습 현황
// = 요일별 학습시간 데이터와 같으므로 데이터 그대로 사용.
function myStudyGoalLearningStatus(result) {
    const section_tab = document.querySelector('[data-div-my-study-week-section-sub="5"]');
    if (!section_tab || result.resultCode !== 'success') return;

    const student_lecture_details = result.student_lecture_details;
    if (!student_lecture_details) return;

    const keys_details = Object.keys(student_lecture_details);
    if (keys_details.length === 0) return;

    const totals = calculateTotals(student_lecture_details, keys_details, section_tab);
    updateTotalCounts(section_tab, totals);
}

function calculateTotals(details, keys, section_tab) {
    let all_complete_cnt = 0;
    let all_sum_cnt = 0;

    keys.forEach(key => {
        const counts = countCompletedLectures(details[key]);
        updateRowCounts(section_tab, key, counts);

        all_complete_cnt += counts.complete;
        all_sum_cnt += counts.total;
    });

    return {complete: all_complete_cnt, total: all_sum_cnt};
}

function countCompletedLectures(details) {
    return details.reduce((acc, detail) => {
        acc.total++;
        if (detail.status === 'complete') {
            acc.complete++;
        }
        return acc;
    }, {complete: 0, total: 0});
}

function updateRowCounts(section_tab, key, counts) {
    const row = section_tab.querySelector(`[data-dayweek-target-div="${key}"]`);
    row.querySelector('[data-cnt-weeksum-learning]').innerText = counts.total;
    row.querySelector('[data-cnt-complete-learning]').innerText = counts.complete;
}

function updateTotalCounts(section_tab, totals) {
    section_tab.querySelector('[data-all-weeksum-learning]').innerText = totals.total;
    section_tab.querySelector('[data-all-complete-learning]').innerText = totals.complete;
}
// 과목별 목표 학습 현황
// = 과목별 학습시간 데이터와 같으므로 데이터 그대로 사용.
// 과목별 목표달성 현황도 같이 진행
function myStudyTargetLearningStatusBySubject(result){
    const section_tab = document.querySelector('[data-div-my-study-week-section-sub="6"]');
    if(section_tab == undefined) return;
    if((result.resultCode||'') == 'success'){
        const student_lecture_details = result.student_lecture_details;
        const keys_details = Object.keys(student_lecture_details);
        if(student_lecture_details && keys_details.length > 0){
            let all_complete_cnt = 0;
            let all_sum_cnt = 0;
            keys_details.forEach(function(key){
                const details = student_lecture_details[key];
                let complete_cnt = 0;
                let sum_cnt = 0;
                details.forEach(function(detail){
                    sum_cnt++;
                    if(detail.status == 'complete'){
                        complete_cnt++;
                    }
                });

                // 학습현황 > 과목별 목표 달성
                const row =  section_tab.querySelector(`[data-sbuejct-target-div="${key}"]`);
                if (row && row.querySelector('[data-cnt-weeksum-learning]')) {
                    row.querySelector('[data-cnt-weeksum-learning]').innerText = sum_cnt;
                }
                if (row && row.querySelector('[data-cnt-complete-learning]')) {
                    row.querySelector('[data-cnt-complete-learning]').innerText = complete_cnt;
                }
                all_complete_cnt += complete_cnt;
                all_sum_cnt += sum_cnt;

                // 학부모 > 메인 > 하단 과목별 목표달성 현황
                const row_2 = document.querySelector(`[data-bundle="subject_goal"] [data-row="${key}"]`);
                if(row_2){
                    row_2.querySelector('[data-cnt-weeksum-learning]').innerText = sum_cnt;
                    row_2.querySelector('[data-cnt-complete-learning]').innerText = complete_cnt;
                    row_2.querySelector('[data-progress-in-bar]').style.width = (complete_cnt / sum_cnt) * 100 + '%';
                }
            });
            section_tab.querySelector('[data-all-weeksum-learning]').innerText = all_sum_cnt;
            section_tab.querySelector('[data-all-complete-learning]').innerText = all_complete_cnt;
        }
    }
}
// 학습 수행 내역
function myStudyLearningPerformanceDetails(result){
    const section_tab = document.querySelector('[data-div-my-study-week-section-sub="7"]');
    if(section_tab == undefined) return;
    if((result.resultCode||'') == 'success'){
        const bundle = document.querySelector('[data-bundle="learning_performance"]');
        const student_lecture_details = result.student_lecture_details;
        const keys_details = Object.keys(student_lecture_details);
        if(student_lecture_details && keys_details.length > 0){
            let all_sum_cnt = 0;
            let all_complete_cnt = 0;
            let complete_per = 0;
            let plan_complete_cnt = 0;
            let after_complete_cnt = 0;
            let not_complete_cnt = 0;
            let self_study_cnt = 0;
            keys_details.forEach(function(key){
                let complete_per_part = 0;
                let plan_complete_cnt_part = 0;
                let after_complete_cnt_part = 0;
                let not_complete_cnt_part = 0;
                let self_study_cnt_part = 0;

                const details = student_lecture_details[key];
                details.forEach(function(detail){

                    // 총 학습강의수
                    all_sum_cnt++;
                    // 완료 학습강의수 로 주간 수행률을 백분율로 구한다.
                    if(detail.status == 'complete'){
                        all_complete_cnt++;
                        self_study_cnt_part++;
                    }

                    // 계획대로 했어요.
                    if(detail.status == 'complete'
                        && detail.sel_date.substr(0,10) >= detail.complete_datetime.substr(0,10)){
                        plan_complete_cnt++;
                        plan_complete_cnt_part++;
                    }
                    // 나중에 했어요.
                    if(detail.status == 'complete'
                        && detail.sel_date.substr(0,10) < detail.complete_datetime.substr(0,10)){
                        after_complete_cnt++;
                        after_complete_cnt_part++;
                    }
                    // 안했어요.
                    if(detail.status != 'complete'){
                        not_complete_cnt++;
                        not_complete_cnt_part++;
                    }
                    // TODO: 스스로 학습 ? 설명이 부족해서 나중에 다시 확인 필요.
                    // complete 중에 오늘이전에 완료 한 것으로 진행.
                    if(detail.status == 'complete' && detail.complete_datetime.substr(0,10) < new Date().format('yyyy-MM-dd')){
                        self_study_cnt++;
                        self_study_cnt_part++;
                    }
                });

                //그래프 채우기
                const row = bundle.querySelector(`[data-row="${key}"]`);
                if (row) {
                    const planBar = row.querySelector('[data-plan-bar]');
                    const laterBar = row.querySelector('[data-later-bar]');
                    const notBar = row.querySelector('[data-not-bar]');
                    const selfBar = row.querySelector('[data-self-bar]');

                    if (planBar) planBar.dataset.barCnt = plan_complete_cnt_part;
                    if (laterBar) laterBar.dataset.barCnt = after_complete_cnt_part;
                    if (notBar) notBar.dataset.barCnt = not_complete_cnt_part;
                    if (selfBar) selfBar.dataset.barCnt = self_study_cnt_part;
                }
            });
            complete_per = (all_complete_cnt / all_sum_cnt) * 100;
            complete_per = Math.floor(complete_per);
            section_tab.querySelector('[data-complete-per]').innerHTML = complete_per+'%';
            document.querySelector('[data-main-complete-per]').innerHTML = complete_per;

            section_tab.querySelectorAll('[data-all-weeksum-learning]').forEach(function(el){
                el.innerHTML = all_sum_cnt;
            });
            section_tab.querySelector('[data-all-complete-cnt]').innerHTML = all_complete_cnt;
            section_tab.querySelector('[data-plan-complete-cnt]').innerHTML = plan_complete_cnt;
            section_tab.querySelector('[data-after-complete-cnt]').innerHTML = after_complete_cnt;
            section_tab.querySelector('[data-not-complete-cnt]').innerHTML = not_complete_cnt;
            section_tab.querySelector('[data-all-self-learning-cnt]').innerHTML = self_study_cnt;
            // 상단 스스로 학습.
            document.querySelector('[data-main-self-study-cnt]').innerText = self_study_cnt;

            section_tab.querySelectorAll('[data-bar-cnt]').forEach(function(el){
                el.style.height = (el.dataset.barCnt / all_sum_cnt) * 100 + '%';
            });
        }
    }else{}
}

// data-main-now-week
function myStudyWeekBtnClick(vthis){
    // 모든 주차 버튼 비활성화.
    const div_week_row = document.querySelectorAll('.div_week_row button');
    div_week_row.forEach(function(item){
        item.classList.remove('active');
    });
    vthis.classList.add('active');
    document.querySelector('[data-main-now-week]').innerText = vthis.querySelector('.week_cnt').innerText + '주차';
    myStudyWeekTotalSelect();
}
</script>

