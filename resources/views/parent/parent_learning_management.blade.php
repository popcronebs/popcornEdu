@section('add_css_js')
<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="{{ asset('js/owl.js') }}"></script>
<link href="{{ asset('css/owl1.css') }}" rel="stylesheet">
<link href="{{ asset('css/owl2.css') }}" rel="stylesheet">
@endsection

<style>
    .card-item > .card-item-character{
        width: 25%
    }
    .btn.primary-bg-mian-hover.active{
        border: 2px solid #FFBD19;
        color: #FFBD19 !important;
    }
    .btn.primary-bg-mian-hover.active:hover{
        color: #fff !important;
    }
</style>

<!-- TODO: 학습자료 > DB setting -->
<!-- TODO: 학습자료 > 다운로드 기능 제작  -->

<!-- : 학부모 > 메인 > 자녀가 접속중인지에 대해서 체크 기능 추가. -->
<!-- : 주차별에서 주차 활성화. -->
<!-- : 주간 새로 추가된 섹션 추가.(목표 학습 현황[요일별, 과목별], 학습 수행 내역 ) -->

<!-- TODO: 위 내용 채우기. -->

<!-- : 학부모 > 메인에서 요일 선택 기능추가. -->
<!-- : 학부모일때만 위에 추가 목록이 보이도록 수정.  -->
<!-- : 과목별 목표달성 현황 -->

{{-- MAIN.  --}}
<input type="hidden" data-main-parent-seq value="{{ session()->get('parent_seq') }}">
<div data-div-my-study-main="0">
    <input type="hidden" data-main-tody-date value="{{ date('Y-m-d') }}">
    <div class="row mx-0 px-0">
        <!-- 왼쪽  -->
        <div class="col-10 ps-0 pe-md-3 pe-lg-3 pe-xl-4">
            @php
                $today = new DateTime();
                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                $koreanDays = ['월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'];
                $shortKoreanDays = ['월', '화', '수', '목', '금', '토', '일'];
                $currentDayIndex = (int)$today->format('N') -1;
            @endphp

            {{-- 요일 bar --}}
            <section class="shadow-sm-2 rounded-4 mt-sm-2 mt-md-2 mt-lg-2 mt-xxl-4 overflow-hidden week-bar">
                <div class="row align-items-center justify-content-between gap-3">
                    <button class="col-auto p-2 studyColor-bg-studyComplete" onclick="stMainChangeDate('prev')">
                        <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M24.9418 28.9597L18.2996 22.3675C17.6164 21.6894 17.6122 20.5858 18.2904 19.9026L24.9418 13.2012" stroke="white" stroke-width="4" stroke-miterlimit="10" stroke-linecap="round" />
                        </svg>
                    </button>
                    @foreach($days as $index => $day)
                    @php
                    $date = clone $today;
                    $diff = $index - $currentDayIndex;
                    $date->modify(($diff >= 0 ? '+' : '') . $diff . ' days');
                    $isToday = $diff === 0;
                    @endphp
                    <div data-week-div="{{ $day }}" onclick="stMainWeekDayClick(this)" class="row col-auto ctext-gc1 {{ $isToday ? 'rounded-3 active studyColor-bg-studyComplete textwhite' : '' }} cursor-pointer">
                        <div class="text-center">
                            <span class="day-full cfs-05 fw-semibold {{ $isToday ? 'text-white' : '' }}">{{ $koreanDays[$index] }}</span>
                            <span class="day-short cfs-05 fw-semibold {{ $isToday ? 'text-white' : '' }}">{{ $shortKoreanDays[$index] }}</span>
                            <div class="cfs-6 sp_date {{ $isToday ? 'd-block text-white' : '' }}" {{ !$isToday ? 'hidden' : '' }}>
                                {{ $date->format('m.d') }}
                            </div>
                            <input type="hidden" data-day-date value="{{ $date->format('Y-m-d') }}">
                        </div>
                    </div>
                    @endforeach
                    <button class="col-auto p-2 studyColor-bg-studyComplete" onclick="stMainChangeDate('next')">
                        <svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.0582 28.9597L23.7004 22.3675C24.3836 21.6894 24.3878 20.5858 23.7096 19.9026L17.0582 13.2012" stroke="white" stroke-width="4" stroke-miterlimit="10" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
            </section>

            {{-- 학습현황 --}}
            <section class="pt-sm-2 pt-md-2 pt-lg-4 pt-xl-4 pb-4 d-none">
                <!-- TODO: 영업이후에는 보이게 하거나, 화살표 버튼 살려서 기능 넣기.  -->
                <div class="row pt-1 pb-4" hidden>
                    <div class="col-auto">
                        <img src="{{ asset('images/pencil_icon.svg') }}" width="42">
                        <span class="ctext-bc0 cfs-4 fw-semibold">오늘의 학습</span>
                    </div>
                    <div class="col text-end">
                        <button class="btn p-0 me-2">
                            <img src="{{ asset('images/calendar_arrow_left.svg') }}" style="width:42px;">
                        </button>
                        <button class="btn p-0 ms-1">
                            <img src="{{ asset('images/calendar_arrow_right.svg') }}" style="width:42px;">
                        </button>
                    </div>
                </div>
                {{-- 오늘의 학습 카드형태 --}}
                <div class=" owl-carousel owl-theme d-none" data-bundle="todays_learning_none">
                    <div class=" " data-row="copy" style="display:none" hidden>
                        <input type="hidden" data-st-lecture-detail-seq>
                        <div class="card position-relative overflow-hidden">
                            <!-- <div data-subject-name data-explain="#국어" -->
                            <!--     class="bg-bc0 text-white cfs-5 fw-semibold position-absolute top-0 end-0 py-2 px-4 " style="border-radius: 0px 5px 0px 12px;"> -->
                            <!-- </div>  -->
                            <video data-lecture-video style="width:100%;height:100%;" preload="metadata"></video>
                            <div class="progress rounded-0" role="progressbar" style="height:12px" data-progress-bar>
                                <div class="progress-bar bg-study-1 rounded-0" style="width: 0%"></div>
                            </div>

                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-auto cfs-5 fw-bold">
                                        <span data-explain="#[8단원] 의견있어요." data-lecture-detail-name></span>
                                    </div>
                                    <div class="col text-end">
                                        <img src="{{ asset('images/folder_plus_icon.svg') }}" style="width:32px">
                                    </div>
                                </div>
                                <div class="ctext-gc0 cfs-6 mt-2 mb-2" data-description>
                                    <!-- 글을 읽고 인물의 의견과 그 까닭 알기 글을 읽고 인물의 의견과 그까닭... -->
                                </div>
                                <div class="mt-4 h-center gap-2">
                                    <span data-status></span>
                                    <div class="rounded-pill text-white py-2 px-3" data-subject-name> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            {{-- 오늘의 학습 변경된 디자인 --}}
            <section class="study-conteiner mt-sm-3 mt-md-3 mt-lg-3 mt-xl-4 overflow-hidden">
                <div class="title-wrap d-flex justify-content-between align-items-center p-20">
                    <div class="title-wrap-inner">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.1565 7.69629L8.4082 19.4446L12.6091 23.6336L24.3574 11.8853L20.1565 7.69629Z" fill="#FFD368" />
                            <path d="M7.47268 20.3823L6.68945 22.7438L9.28833 25.3427L11.6736 24.5714L12.6111 23.6339L8.41017 19.4448L7.47268 20.3823Z" fill="#F0F0F0" />
                            <path d="M5.39453 26.5999L9.28691 25.342L6.68804 22.7432L5.39453 26.5999Z" fill="#999999" />
                            <path d="M26.0324 6.85387L25.1898 6.0232C24.7452 5.58115 24.1436 5.33301 23.5166 5.33301C22.8896 5.33301 22.288 5.58115 21.8433 6.0232L20.1582 7.69641L24.3592 11.8973L26.0324 10.2122C26.2531 9.99182 26.4281 9.73003 26.5476 9.4419C26.667 9.15378 26.7285 8.84496 26.7285 8.53306C26.7285 8.22115 26.667 7.91228 26.5476 7.62417C26.4281 7.33604 26.2531 7.07429 26.0324 6.85387Z" fill="#FFAFB9" />
                        </svg>
                        <span class="text-sb-20px">오늘의 학습</span>
                    </div>
                    <div class="title-wrap-right">
                        <span class="text-sb-20px">
                            <span data-today-total-sum>0</span>강 중
                            <span class="studyColor-text-studyComplete">
                                <span data-today-total-complete>0</span> 강의 완료
                            </span>
                        </span>
                    </div>
                </div>
                <div class="content-x-wheels overflow-x-scroll d-flex gap-3 mx-3 pb-3" data-btn-study data-bundle="todays_learning">
                    <div class="card-wrap flex-row" data-row="copy" hidden onclick="stMainPlayVido(this)">
                        <div class="card-item " data-card-item>
                            <input type="hidden" data-st-lecture-detail-seq>
                            <div class="card-cont position-relative">
                                <div class="card-body-top d-flex flex-column">
                                    <div class="d-flex justify-content-between position-relative">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-b-28px text-white" data-subject-name data-explain="#국어">국어</span>
                                        </div>
                                        <span class="rounded-5 px-3 py-2 bg-white d-inline-block" data-status>학습 중</span>
                                    </div>
                                    <span class="text-sb-18px pt-4 text-white " data-explain="#[8단원] 의견있어요." data-lecture-detail-name>[8단원] 의견이 있어요.</span>
                                </div>
                                <a href="javascript:void(0)" data-btn-study class="">
                                    <svg class="play-icon" width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="26" cy="26" r="26" fill="#FF5065" />
                                        <path d="M32.2564 27.8727C33.5563 26.9627 33.5563 25.0376 32.2564 24.1276L23.2898 17.851C21.7749 16.7906 19.6934 17.8744 19.6934 19.7236V32.2768C19.6934 34.126 21.7749 35.2097 23.2898 34.1493L32.2564 27.8727Z" fill="white" />
                                    </svg>
                                </a>
                            </div>
                            <img class="card-item-character" src="" alt="" data-bg-subject-img>
                        </div>
                    </div>
                    <x-spinner class="mb-3" style="top: 50%;" item-style="transform: scale(0.5);height: 120px;" />
                </div>
                                  
                <div class="content-lesson-empty d-flex flex-column align-items-center justify-content-center h-100 gap-4 scale-bg-gray_01 d-none">
                    <img class="mt-2" src="{{asset('images/popcorn_symbol_logo.svg')}}" alt="">
                    <div class="d-flex text-center gap-2 flex-column">
                        <p class="text-b-24px">오늘의 학습이 없어요.</p>
                        <p class="text-b-24px">스스로 학습을 해볼까요?</p>
                    </div>
                    <div class="d-flex gap-3">
                        <button type="button" class="btn-line-ms-primary text-b-24px rounded-pill scale-bg-white primary-bg-mian-hover" onclick="">미수강 바로가기</button>
                        <button type="button" class="btn-line-ms-primary text-b-24px rounded-pill scale-bg-white primary-bg-mian-hover" onclick="location.href='/student/school/study'">학교공부 바로가기</button>
                    </div>
                </div>
            </section>

        </div>
        <!-- 오른쪽 사이드 -->
        <aside class="col-2 p-0 d-none">
            {{-- 현재 요일 / 시간 --}}
            <section class="shadow-sm-2 rounded-3 border mt-4 pb-1" data-section-side-right>
                <div class="row p-0 m-0 p-4 justify-content-between">
                    <div class="col d-flex flex-column justify-content-center pb-2">
                        <div class="text-center pb-3">
                            <span class="text-sb-32px" data-date>
                            </span>
                            <span data-week-day class="text-sb-32px"></span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 수강강의 현황 --}}
            <section class="bg-gc5 rounded-3 mt-sm-3 mt-md-3 mt-lg-3 mt-xl-4 p-4">
                <div class="row h-center">
                    <span class="col cfs-7 ctext-gc0">오늘의 수강강의</span>
                    <span class="col-auto cfs-8 text-white rounded-pill bg-danger px-2 py-1">
                        <span data-today-total-sum>8</span> 강의
                    </span>
                </div>
                <div class="text-center border bg-white py-2 mt-4 rounded-3">
                    <div class="row mt-2 cfs-1 fw-medium align-items-center justify-content-center px-0 px-0">
                        <span class="col-auto ctext-bc0" data-today-total-complete>0</span>
                        <span class="col-auto ctext-gc2 cfs-6 align-middle">/</span>
                        <span class="col-auto ctext-gc2" data-today-total-sum>0</span>
                    </div>
                    <div class="text-center mb-2" data-today-subscript-div hidden>
                        <div>
                            <svg width="12" height="10" viewBox="0 0 12 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.84 0.732759L0 10H12L7.16 0.732759C6.65333 -0.244253 5.34667 -0.244253 4.83333 0.732759H4.84Z" fill="#222222" />
                            </svg>
                        </div>
                        <span data-today-subscript class="bg-dark text-white cfs-8 rounded-pill py-2 px-3">
                            거의 다 왔어요!
                        </span>
                    </div>
                </div>

                <div class="mt-3">
                    <span class="col cfs-7 ctext-gc0">김팝콘 학생의 학습내용</span>
                </div>

                <div class="text-center border bg-white py-2 mt-3 rounded-3" style="min-height:86px">
                    <div class="mt-1">
                        <span class="cfs-8 ctext-gc1">학습 시작 시간</span>
                    </div>
                    <div class="mt-1 ">
                        <span class="text-sb-32px" data-explain="#08:09" data-study-start-time>00:00</span>
                    </div>
                    <div class="mt-1">
                        <span class="cfs-8 ctext-gc1 text-danger" data-explain="#(9분 지각)" data-study-start-late hidden>(0분 지각)</span>
                    </div>
                </div>

                <div class="text-center border bg-white py-2 mt-2 rounded-3">
                    <div class="mt-1">
                        <span class="cfs-8 ctext-gc1">총 학습 시간</span>
                    </div>
                    <div class="mt-1 ">
                        <span class="text-sb-32px">
                            <span data-today-all-study-hrs>00</span>:<span data-today-all-study-min>00</span>
                        </span>
                    </div>
                    <div class="mt-1 row align-items-center justify-content-center gap-3">
                        <span class="col-auto bg-dark text-white cfs-8 rounded-pill" style="padding:2px 8px;">hrs</span>
                        <span class="col-auto bg-dark text-white cfs-8 rounded-pill" style="padding:2px 8px;">min</span>
                    </div>
                </div>
            </section>
        </aside>
        <aside class="col-2 p-0">
            {{-- 현재 요일 / 시간 --}}
            <section class="shadow-sm-2 rounded-3 border mt-sm-2 mt-md-2 mt-lg-2 mt-xxl-4 today-time" data-section-side-right>
                <div class="row m-0 p-10 justify-content-between">
                    <div class="d-flex flex-row justify-content-center align-items-center px-0">
                        <div class="text-center ">
                            <span class="cfs-6 fw-semibold" data-date>
                                {{ date('m월 d일') }}
                            </span>
                        </div>
                        <div data-week-day class="cfs-6 fw-bold bg-white py-2 rounded-pill ms-2">목</div>
                    </div>
                    <!-- <div class="col-auto pe-0">
                        <img src="https://sdang.acaunion.com/images/main_side_character.svg" width="80">
                    </div> -->
                </div>
            </section>

            {{-- 수강강의 현황 --}}
            <section class="learning-status">
                <!-- 자녀 접속 중인지  -->
                <section class="today-status mt-sm-3 mt-md-3 mt-lg-3 mt-xl-4 py-2">
                    <div class="row mx-0 justify-content-center" data-now-connect>
                        <div data-is-now-connect-symbol class="col-auto px-0 rounded-pill studyColor-bg-goalTime" style="height:20px;width:20px;"></div>
                        <div class="col-auto">
                            <span class="text-sb-20px" data-is-now-connect-str>불러오는 중</span>
                        </div>
                    </div>
                </section>
                <div class="row h-center today-status mt-sm-3 mt-md-3 mt-lg-3 mt-xl-4">
                    <span class="col cfs-7 ctext-gc0 status-title">오늘의 수강강의</span>
                    <!-- <span class="col-auto cfs-8 text-white rounded-pill bg-danger px-2 py-1">
                        <span data-today-total-sum>8</span> 강의
                    </span> -->
                    <div class="row cfs-1 fw-medium align-items-center justify-content-center status-count px-0 px-0">
                        <span class="col-auto ctext-bc0" data-today-total-complete>0</span>
                        <span class="col-auto ctext-gc2 cfs-6 align-middle">/</span>
                        <span class="col-auto ctext-gc2" data-today-total-sum>0</span>
                    </div>
                    <div class="text-center mb-2" data-today-subscript-div hidden>
                        <div>
                            <svg width="12" height="10" viewBox="0 0 12 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.84 0.732759L0 10H12L7.16 0.732759C6.65333 -0.244253 5.34667 -0.244253 4.83333 0.732759H4.84Z" fill="#222222" />
                            </svg>
                        </div>
                        <span data-today-subscript class="bg-dark text-white cfs-8 rounded-pill py-2 px-3">
                            거의 다 왔어요!
                        </span>
                    </div>
                </div>

                <!-- <div class="mt-3">
                    <span class="col cfs-7 ctext-gc0">김팝콘 학생의 학습내용</span>
                </div> -->

                <div class="row text-center mt-sm-3 mt-md-3 mt-lg-3 mt-xl-4 rounded-3 today-status">
                    <span class="col cfs-8 ctext-gc1 status-title">학습 시작 시간</span>
                    <div class="status-count">
                        <span class="ctext-bc0 start-time" data-explain="#08:09" data-study-start-time>00:00</span>
                    </div>
                    <div class="">
                        <div class="cfs-8 ctext-gc1 mb-2" data-explain="#(9분 지각)" data-study-start-late hidden>(0분 지각)</div>
                    </div>
                </div>

                <div class="row mt-sm-3 mt-md-3 mt-lg-3 mt-xl-4 today-status d-none">
                    <span class="cfs-8 ctext-gc1 status-title">총 학습 시간</span>
                    <div class="status-count text-center">
                        <span class="ctext-bc0 progress-time">
                            <span data-today-all-study-hrs>00</span>:<span data-today-all-study-min>00</span>
                        </span>
                    </div>
                    <!-- <div class="mt-1 row align-items-center justify-content-center gap-3">
                        <span class="col-auto bg-dark text-white cfs-8 rounded-pill" style="padding:2px 8px;">hrs</span>
                        <span class="col-auto bg-dark text-white cfs-8 rounded-pill" style="padding:2px 8px;">min</span>
                    </div> -->
                </div>
            </section>
        </aside>
    </div>
    {{-- 과목별 목표달성 현황 --}}
    <section class="pt-sm-2 pt-md-2 pt-lg-2 pt-xxl-4 pb-4">
        <div class="row mx-0 my-sm-0 my-md-0 my-lg-0 my-xxl-4 align-items-center">
            <div class="col-auto">
                <img src="{{asset('images/goal_icon.svg')}}" width="32">
            </div>
            <div class="col">
                <span class="text-sb-28px"> 과목별 목표달성 현황 </span>
            </div>
            <div class="col-auto row mx-0">
                <button class="btn p-0 col-auto d-flex align-items-center justify-content-center" data-btn-owl-prev>
                    <img src="{{asset('images/calendar_arrow_left.svg')}}" width="32">
                </button>
                <button class="btn p-0 col-auto d-flex align-items-center justify-content-center" data-btn-owl-next>
                    <img src="{{asset('images/calendar_arrow_right.svg')}}" width="32">
                </button>
            </div>
        </div>
        <div data-bundle="subject_goal" class="owl-carousel owl-theme mt-sm-0 mt-md-0 mt-lg-0 mt-xl-0">
            @if(!empty($subject_codes))
            @foreach($subject_codes as $subject_code)
            <?php
                $rand_num = rand(5, 20);
                $persent = $rand_num / 20 * 100;
                $persent = floor($persent);
            ?>
            <div data-row="{{$subject_code->id}}" class="p-2">
                <div class="row mx-0 modal-shadow-style p-2 p-sm-2 p-md-2 p-lg-2 p-xl-4 p-xxl-4 rounded-3">
                    <div class="col-auto">
                        <img src="{{ asset('images/'.$subject_code->function_code.'.svg')}}" style="width:72px;height:72px">
                    </div>
                    <div class="col d-flex flex-row align-items-center justify-content-center gap-2">
                        <div class="text-sb-18px scale-text-gray_05 text-start text-sb-20px">{{$subject_code->code_name}}</div>
                        <div class="text-sb-20px text-start text-sb-32px mt-md-0 mt-lg-0 mt-xl-3">
                            <span class="text-black" data-cnt-complete-learning>0</span>
                            /
                            <span class="scale-text-gray_05" data-cnt-weeksum-learning>0</span>
                        </div>
                    </div>
                    <div class="progress rounded-0 rounded-pill px-0 mt-2" role="progressbar" style="height:12px" data-progress-bar>
                        <div class="progress-bar bg-primary-y rounded-0 rounded-pill" data-progress-in-bar></div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </section>
</div>

{{-- 학습자료 --}}
<div data-div-my-study-main="4" hidden>
    <div class="row max-0">
        <aside class="col-3 px-0">
            <div class="rounded-4 modal-shadow-style">
                <ul class="tab py-4 px-3 ">
                    <li class="mb-2">
                        <button onclick="ptLearnMsAsideTab(this)" data-pt-member-tab="1" class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover active">
                            <img src="{{asset('images/window_memo_icon.svg')}}" width="32">
                            요점 정리
                        </button>
                    </li>
                    <li class="">
                        <button onclick="ptLearnMsAsideTab(this)" data-pt-member-tab="2" class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                            <img src="{{asset('images/window_memo_icon.svg')}}" width="32">
                            자료 분류1
                        </button>
                    </li>
                    <li class="">
                        <button onclick="ptLearnMsAsideTab(this)" data-pt-member-tab="3" class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                            <img src="{{asset('images/window_memo_icon.svg')}}" width="32">
                            자료 분류2
                        </button>
                    </li>
                </ul>
            </div>
        </aside>
        <div class="col px-0 ms-4">
            <div>
                <table class="table-style w-100" style="min-width: 100%;">
                    <colgroup>
                        <col style="width: 80px;">
                    </colgroup>
                    <thead>
                        <tr class="text-sb-20px modal-shadow-style rounded">
                            <th>과목</th>
                            <th>학기</th>
                            <th>단원명</th>
                            <th>다운로드</th>
                        </tr>
                    </thead>
                    <tbody data-bundle="tby_wrong">
                        @for($i=0; $i<rand(3,6); $i++) <!-- <tr class="text-m-20px" data-row="copy" hidden> -->
                            <tr class="text-m-20px" data-row="clone">
                                <input type="hidden" data-student-seq>
                                <td class=" py-4 text-black h-104">
                                    <span data-subject data-text="#과목" class="rounded-pill bg-primary-y text-white p-2 px-3">국어</span>
                                </td>
                                <td class=" py-4 h-104">
                                    <span data-student-name data-text="#학기" class="text-black">1학기</span>
                                </td>
                                <td class=" py-4 h-104">
                                    <span data-text="#강좌이름">1. 재미가 톡톡</span>
                                </td>
                                <td class="py-4 text-black h-104">
                                    <div class="w-center">
                                        <button class="btn border border-dark h-center px-3 gap-2">
                                            <img src="{{ asset('images/download_icon.svg') }}">
                                            <span> 다운로드 </span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endfor
                    </tbody>
                </table>
            </div>
            {{-- 페이징 --}}
            <div class="">
                <div class="col d-flex justify-content-center">
                    <ul class="pagination col-auto" data-page="1" hidden>
                        <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="1" onclick="userPaymentPageFunc('1', 'prev')">
                            <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                        </button>
                        <li class="page-item" hidden>
                            <a class="page-link" onclick="">0</a>
                        </li>
                        <span class="page" data-page-first="1" hidden onclick="userPaymentPageFunc('1', this.innerText);" disabled>0</span>
                        <button href="javascript:void(0)" class="btn p-0 next" data-page-next="1" onclick="userPaymentPageFunc('1', 'next')" data-is-next="0">
                            <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                        </button>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>



<script>
    // ready
    document.addEventListener('DOMContentLoaded', function() {
        // 오늘의 학습 불러오기.
        //ptLearnLoadTodaysLearningSelect();
        // 학습 시작시간 불러오기.
        //ptLearnStudyTimeSelect();
        // 날짜 변경하기
        //ptLearnChageSideRight();

        let browserWidth = function() {
            return window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
        }

        let initializeOwlCarousel = function() {
            let item = 4;
            if (browserWidth() < 768) {
                item = 2;
            } else if (browserWidth() < 1024) {
                item = 3;
            }

            const owl_goal = $('[data-bundle="subject_goal"]');
            owl_goal.owlCarousel('destroy');
            owl_goal.owlCarousel({
                items: item,
                loop: false,
                margin: 4,
                nav: false,
                dots: false
            });
        }

        initializeOwlCarousel();
        window.addEventListener('resize', initializeOwlCarousel);
        const owl_goal = $('[data-bundle="subject_goal"]');
        $('[data-btn-owl-prev]').click(function() {
            owl_goal.trigger('prev.owl.carousel');
        });
        $('[data-btn-owl-next]').click(function() {
            owl_goal.trigger('next.owl.carousel');
        });

        // 5분 마다 자녀가 접속중인지 체크.
        ptLearnChkChildLogin();
        {{-- setInterval(function() {
            ptLearnChkChildLogin();
        }, 300000); --}}
    });

    // 자녀가 접속중인지 체크.
    function ptLearnChkChildLogin() {
        const parent_seq = document.querySelector('[data-main-parent-seq]').value;
        const page = "/parent/child/study/connect/check";
        const parameter = {
            parent_seq: parent_seq
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                const is_connect = result.is_connect;
                document.querySelector('[data-now-connect]').hidden = false;
                if (is_connect == 'true') {
                    document.querySelector('[data-is-now-connect-symbol]').classList.remove('bg-danger');
                    document.querySelector('[data-is-now-connect-symbol]').classList.add('studyColor-bg-goalTime');
                    document.querySelector('[data-is-now-connect-str]').textContent = '현재 접속 중';
                } else {
                    document.querySelector('[data-is-now-connect-symbol]').classList.remove('studyColor-bg-goalTime');
                    document.querySelector('[data-is-now-connect-symbol]').classList.add('bg-danger');
                    document.querySelector('[data-is-now-connect-str]').textContent = '현재 미접속';
                }
            } else {}
        })
    }


    // 상단 학습현황, 수강현황, 학습자료 탭 클릭.
    function ptLearnTopTabClick(vthis) {
        if (vthis.classList.contains('active')) {
            vthis.classList.remove('active');
        } else {
            document.querySelectorAll('[data-main-top-tab]').forEach(function(el) {
                el.classList.remove('active');
            });
            vthis.classList.add('active');
        }

        const top_tab_el = document.querySelector('[data-main-top-tab].active');
        const type = top_tab_el?.dataset.mainTopTab || ''; // 없으면 main 으로 처리.

        document.querySelectorAll('[data-main-section]').forEach(function(el) {
            el.hidden = true;
        });
        if (type == '') {
            document.querySelector(`[data-main-section="main"]`).hidden = false;
        } else {
            document.querySelector(`[data-main-section="${type}"]`).hidden = false;
        }
    }

    // 학습자료 > aside click
    function ptLearnMsAsideTab(vthis) {
        document.querySelector('[data-pt-member-tab].active').classList.remove('active');
        vthis.classList.add('active');
    }


    // // 날짜 변경하기
    // function ptLearnChageSideRight(date, day) {
    //     const sideRight = document.querySelector('[data-section-side-right]');
    //     const date_el = sideRight.querySelector('[data-date]');
    //     const day_el = sideRight.querySelector('[data-week-day]');

    //     //MM월dd일 형태로 변환
    //     if (date && day) {} else {
    //         date_el.textContent = new Date().format('MsM.dd');
    //         day_el.textContent = new Date().format('E');
    //     }
    // }


    // //(학습현황) 학생에선 / 오늘의 학습 불러오기.
    // let ori_table = null;

    // function ptLearnLoadTodaysLearningSelect(selectr_date) {
    //     const search_date = selectr_date || new Date().format('yyyy-MM-dd');
    //     const select_type = 'no_group';

    //     const page = "/parent/child/study/today/select";
    //     const parameter = {
    //         search_start_date: search_date,
    //         search_end_date: search_date,
    //         select_type: select_type
    //     };
    //     queryFetch(page, parameter, function(result) {
    //         if ((result.resultCode || '') == 'success') {
    //             //초기화
    //             let bundle = document.querySelector('[data-bundle="todays_learning"]');
    //             if (ori_table) {
    //                 // bundle 뒤에 ori_table 을 넣는다.
    //                 const clone_ori_table = ori_table.cloneNode(true);
    //                 $('[data-bundle="todays_learning"]').after(clone_ori_table)
    //                 bundle.remove();
    //                 bundle = clone_ori_table;
    //             }
    //             const row_copy = bundle.querySelector('[data-row]').cloneNode(true);
    //             bundle.classList.remove('owl-loaded');
    //             bundle.classList.remove('owl-drag');
    //             bundle.innerHTML = '';
    //             bundle.appendChild(row_copy);
    //             if (ori_table == null) ori_table = bundle.cloneNode(true);

    //             const st_lecture_details = result.student_lecture_details;
    //             const today = document.querySelector('[data-main-tody-date]').value;

    //             let total_sum = 0; //오늘  강의수
    //             let total_complete = 0; // 오늘  완료 강의
    //             //총 봐야할 학습시간
    //             let total_study_time = 0;
    //             st_lecture_details.forEach(function(detail) {
    //                 const row = row_copy.cloneNode(true);
    //                 row.classList.add('item');
    //                 const study_time = detail.lecture_detail_time;
    //                 //시간:분 = 초
    //                 const sec_study_time = parseInt(study_time.split(':')[0]) * 60 * 60 + parseInt(study_time.split(':')[1]) * 60;
    //                 row.setAttribute('data-row', 'clone');
    //                 row.style.display = '';
    //                 row.hidden = false;
    //                 row.querySelector('[data-st-lecture-detail-seq]').value = detail.id;
    //                 row.querySelector('[data-subject-name]').innerText = detail.subject_name;
    //                 let subject_code = detail.subject_function_code.split('_')[1];
    //                 if (subject_code == 'eng') subject_code += 'lish';
    //                 row.querySelector('[data-subject-name]').classList.add('studyColor-bg-' + subject_code);

    //                 row.querySelector('[data-lecture-detail-name]').innerText = detail.lecture_name + ' ' + detail.lecture_detail_name;
    //                 row.querySelector('[data-lecture-video]').src = detail.lecture_detail_link;
    //                 // TEST: 5~10초 랜덤
    //                 row.querySelector('[data-lecture-video]').currentTime = Math.floor(Math.random() * 60) + 1;

    //                 // sec_study_time (전체시간)  detail.last_video_time (본시간) 으로 %.
    //                 const persent_time = parseInt(detail.last_video_time || 0) / sec_study_time * 100;
    //                 row.querySelector('[data-progress-bar] .progress-bar').style.width = persent_time + '%';
    //                 let description = detail.lecture_detail_description;
    //                 //lecture_detail_description 가 없으면 lecture_description를 삽입.
    //                 if (detail.lecture_detail_description || description.length < 1) description = detail.lecture_description;
    //                 row.querySelector('[data-description]').innerText = description;

    //                 total_sum++;
    //                 //data-status
    //                 //프로그래스 바 색 같이 변경.
    //                 if (detail.status == 'complete') {
    //                     //class add studey-completion
    //                     total_complete++;
    //                     row.querySelector('[data-status]').innerText = '학습 완료';
    //                     row.querySelector('[data-status]').classList.add('studey-completion');
    //                     ptLearnChageProgressColor(row.querySelector('[data-progress-bar] .progress-bar'), 'bg-study-2');
    //                 } else if (detail.status == 'ready') {
    //                     row.querySelector('[data-status]').innerText = '학습 전';
    //                     row.querySelector('[data-status]').classList.add('studey-before');
    //                     ptLearnChageProgressColor(row.querySelector('[data-progress-bar] .progress-bar'), 'bg-study-0');
    //                 } else if (detail.status == 'study') {
    //                     row.querySelector('[data-status]').innerText = '학습 중';
    //                     row.querySelector('[data-status]').classList.add('studey-doing');
    //                     ptLearnChageProgressColor(row.querySelector('[data-progress-bar] .progress-bar'), 'bg-study-1');
    //                 }

    //                 //오늘이 아니면 학습하기 버튼 삭제
    //                 if (search_date != today) {
    //                     // 학부모 버전이라 원래 없음.
    //                     // row.querySelector('[data-btn-study]').remove();
    //                 }
    //                 total_study_time += parseInt(detail.last_video_time || 0);

    //                 bundle.appendChild(row);
    //             });
    //             // 총 학습 강의 수 넣기
    //             if (total_sum > 0) {
    //                 const total_sum_els = document.querySelectorAll('[data-today-total-sum]');
    //                 total_sum_els.forEach(function(el) {
    //                     el.innerText = total_sum;
    //                 });
    //             }
    //             // 오늘 완료 강의 수 넣기
    //             if (total_complete > 0) {
    //                 const total_complete_els = document.querySelectorAll('[data-today-total-complete]');
    //                 total_complete_els.forEach(function(el) {
    //                     el.innerText = total_complete;
    //                 });
    //             }
    //             // 2/3 정도 complete되었을때, 거의 다 왔어요! 표시.
    //             if (total_complete > 0 && total_sum > 0) {
    //                 if (total_complete / total_sum > 0.66) {
    //                     const today_subscript_div = document.querySelector('[data-today-subscript-div]');
    //                     today_subscript_div.hidden = false;
    //                 }
    //             }
    //             // 총 학습시간 넣기
    //             if (total_study_time > 0) {
    //                 const total_study_hrs = Math.floor(total_study_time / 60);
    //                 const total_study_min = total_study_time % 60;
    //                 const total_study_hrs_el = document.querySelector('[data-today-all-study-hrs]');
    //                 const total_study_min_el = document.querySelector('[data-today-all-study-min]');
    //                 // 시간 단위로 01:01 형태로 넣기.
    //                 total_study_hrs_el.innerText = total_study_hrs < 10 ? '0' + total_study_hrs : total_study_hrs
    //                 total_study_min_el.innerText = total_study_min < 10 ? '0' + total_study_min : total_study_min
    //             }


    //             const owl = $('[data-bundle="todays_learning"]');
    //             owl.owlCarousel({
    //                 items: 3,
    //                 loop: false,
    //                 margin: 10,
    //                 nav: true,
    //                 dots: false,
    //                 onInitialized: owlInit
    //             });
    //         } else {}

    //     })
    // }

    // 프로그래스 바 색 변경.
    function ptLearnChageProgressColor(tag, class_name) {
        tag.classList.remove('bg-study-0');
        tag.classList.remove('bg-study-1');
        tag.classList.remove('bg-study-2');

        tag.classList.add(class_name);
    }

    function owlInit() {
        document.querySelector('[data-bundle="todays_learning"] [data-row="copy"]').closest('.owl-item').hidden = true;
    }

    // 학습 시작 시간 가져오기.
    // function ptLearnStudyTimeSelect() {

    //     // data-week-div.bg-primary-y data-day-date
    //     const selectedDay = document.querySelector('[data-week-div].bg-primary-y');
    //     const selectr_date = selectedDay.querySelector('[data-day-date]').value;

    //     // const search_start_date = new Date().format('yyyy-MM-dd');
    //     // const search_end_date = new Date().format('yyyy-MM-dd');

    //     const page = "/parent/child/study/time/select";
    //     const parameter = {
    //         search_start_date: selectr_date,
    //         search_end_date: selectr_date
    //     };
    //     queryFetch(page, parameter, function(result) {
    //         if ((result.resultCode || '') == 'success') {
    //             const attend = result.attend;
    //             if (attend) {
    //                 document.querySelector('[data-study-start-time]').innerText = attend.start_time.substr(0, 5);
    //             }
    //             const study_times = result.study_times;
    //             if (study_times.length > 0 && attend) {
    //                 const study_time = study_times[0].select_time;
    //                 const start_time = attend.start_time;

    //                 // study_time 에서  start_time을 빼서 몇분이 지각인지 구해라.
    //                 const today_date = new Date().format('yyyy-MM-dd ');
    //                 const start_time_date = new Date(today_date + start_time);
    //                 const study_time_date = new Date(today_date + study_time);
    //                 const diff = study_time_date - start_time_date;
    //                 const diff_minutes = Math.floor(diff / 1000 / 60);

    //                 const late_el = document.querySelector('[data-study-start-late]');
    //                 if (diff_minutes < 0) {
    //                     late_el.innerText = '(' + diff_minutes + '분 지각)';
    //                     late_el.hidden = false;
    //                 } else {
    //                     late_el.hidden = true;
    //                 }
    //             }
    //         } else {}
    //     })
    // }


    // 상단에 요일 클릭.
    // function stMainWeekDayClick(element) {
    //     // 모든 요일 div 선택
    //     const allDays = document.querySelectorAll('[data-week-div]');

    //     // 모든 요일의 활성화 상태 제거
    //     allDays.forEach(day => {
    //         day.classList.remove('rounded-4', 'active', 'bg-primary-y');
    //         day.querySelector('span').classList.remove('text-white');
    //         day.querySelector('.sp_date').classList.remove('d-block', 'text-white');
    //         day.querySelector('.sp_date').hidden = true;
    //     });

    //     // 클릭된 요일 활성화
    //     element.classList.add('rounded-4', 'active', 'bg-primary-y');
    //     element.querySelector('span').classList.add('text-white');
    //     const spDate = element.querySelector('.sp_date');
    //     spDate.classList.add('d-block', 'text-white');
    //     spDate.hidden = false;

    //     // 선택된 날짜 가져오기
    //     const selectedDate = element.querySelector('[data-day-date]').value;

    //     // 오늘의 학습 불러오기.
    //     ptLearnLoadTodaysLearningSelect(selectedDate);

    //     // 오른쪽 날짜/요일 변경하기.
    //     ptLearnChageSideRight(selectedDate, element.querySelector('span').textContent);

    //     // 학습 시작 시간 가져오기.
    //     //ptLearnStudyTimeSelect();
    // }


</script>
