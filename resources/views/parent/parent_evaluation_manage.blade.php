@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '평가관리')

@section('add_css_js')
<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.min.js"></script>
@endsection

{{-- 학부모 평가관리  --}}
<!-- TODO: 점수 실 데이터로 변환 및 수점. -->
<!-- TODO: 체크 했을 때, 배열/데이터로 저장하는 기능.추후필요할듯.  -->

<!-- : 완료기간 임박 순 검색시 기능 적용. -->
<!-- : 폴리곤의 점수와 데이터 순서 맞추기. -->

@section('layout_coutent')
<style>
aside .active svg path{
    stroke:white;
}

.radio input[type="radio"]:checked + span::after {
    background-color: #222;
}

@media (max-width: 1400px) {
    .d-flex.justify-content-center.chart-wrap.angles-6 {
        transform: scale(0.7);
    }
}
.primary-bg-mian-hover.active{
    background-color: #FFC747 !important;
    color:white !important;
}
</style>

<div class="col pe-3 ps-3 mb-3 row position-relative">
    <input type="hidden" value="{{ date('Y') }}" data-inp-year>
    <input type="hidden" value="{{ date('m') }}" data-inp-month>

    <div class="sub-title row mx-0 justify-content-between">
        <h2 class="text-sb-42px px-0 col-auto ">
            <img src="{{ asset('images/my_score_icon.svg')}}" width="72" class="p-3">
            <span class="me-2">{{ session()->get('login_type') != 'student' ? '자녀 성적표' : '내 성적표' }}</span>
        </h2>
        @if(session()->get('login_type') != 'student')
        <div class="col-auto h-center">
            <button onclick="evalManageTopTabClick(this)" data-main-top-tab="1"
                class="btn all-center px-4 py-2 rounded-pill primary-bg-mian-hover scale-text-gray_05 scale-text-white-hover me-3 active" >
                <img src="{{asset('images/note_pencil_icon.svg')}}" width="32">
                <span class="text-sb-24px">{{ session()->get('login_type') != 'student' ? '자녀 성적표' : '성적표' }}</span>
            </button>
            <button onclick="evalManageTopTabClick(this)" data-main-top-tab="2"
                 class="btn all-center px-4 py-2 rounded-pill primary-bg-mian-hover scale-text-gray_05 scale-text-white-hover">
                <img src="{{asset('images/note_pencil_icon.svg')}}" width="32">
                <span class="text-sb-24px">오답노트</span>
            </button>
        </div>
        @else
        <div class="col-auto position-relative">
            <img src="{{ asset('/images/character_my_score.svg') }}" class="bottom-0 end-0 position-absolute" width="232" style="top: 50%; transform: translateY(-50%);">
        </div>
        @endif
    </div>


    {{-- 상단 날짜 선택 --}}
    <section class="row mx-0 modal-shadow-style p-3" data-top-date-tab>
        <span id="year_display" class="text-sb-28px col-3 all-center"></span>
        <div class="month-selector d-flex col pe-5">
            <button id="prev_btn" class="btn p-0" onclick="evalManageMoveHalfYear(-1)">
                <img src="{{asset('images/calendar_arrow_left.svg')}}" width="32">
            </button>
            <div class="col">
                <div id="months_display" class="d-flex justify-content-between px-5"></div>
            </div>
            <button id="next_btn" class="btn p-0" onclick="evalManageMoveHalfYear(1)">
                <img src="{{asset('images/calendar_arrow_right.svg')}}" width="32">
            </button>
        </div>
    </section>

    {{-- 잘하는 과목.  --}}
    <section data-top-subject-type data-section="middle">
        <div class="row mx-0">
            <div class="col-6 col-xxl-3 mt-4">
                <div class="row p-4 rounded-3" style="background:#F3F6FF">
                    <div class="col py-3">
                        <span class="text-sb-20px scale-text-gray_05 ">잘하는 과목</span>
                        <span class="d-block text-b-32px scale-text-black mt-2" data-subject-name="good"></span>
                    </div>
                    <div class="col text-end">
                        <img src="" width="92" data-subject-img="good">
                    </div>
                </div>
            </div>

            <div class="col-6 col-xxl-3 mt-4">
                <div class="row p-4 rounded-3" style="background:#EAFCFF">
                    <div class="col py-3">
                        <span class="text-sb-20px scale-text-gray_05 ">못하는 과목</span>
                        <span class="d-block text-b-32px scale-text-black mt-2" data-subject-name="notgood"></span>
                    </div>
                    <div class="col text-end">
                        <img src="" width="92" data-subject-img="notgood">
                    </div>
                </div>
            </div>

            <div class="col-6 col-xxl-3 mt-4">
                <div class="row p-4 rounded-3" style="background:#FFF8EA">
                    <div class="col py-3" >
                        <span class="text-sb-20px scale-text-gray_05 ">점수가 오른 과목</span>
                        <span class="d-block text-b-32px scale-text-black mt-2" data-subject-name="best"></span>
                    </div>
                    <div class="col text-end">
                        <img src="" width="92" data-subject-img="best">
                    </div>
                </div>
            </div>

            <div class="col-6 col-xxl-3 mt-4">
                <div class="row p-4 rounded-3 scale-bg-gray_01">
                    <div class="col-8 py-3">
                        <span class="text-sb-20px scale-text-gray_05 ">점수가 떨어진 과목</span>
                        <span class="d-block text-b-32px scale-text-black mt-2" data-subject-name="worst"></span>
                    </div>
                    <div class="col-4 text-end">
                        <img src="" width="92" data-subject-img="worst">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="px-0">
         {{-- 평가관리 1 --}}
        <article data-article="evaluation_management" hidden>

            <!-- ## 정답개수, 응시횟수, 평균점수 -->
            <div class="row mt-4 gap-4">
                <div class="modal-shadow-style p-4 mt-4 row col">
                    <div class=" scale-text-gray_05 text-sb-20px col">
                        <div class="px-0 col">
                            총 <span data-total-cnt ></span>개 문제중
                        </div>
                        <div class="px-0 col mt-2">
                            <span class="text-black">정답 개수</span> 입니다.
                        </div>
                    </div>
                    <div class="col-auto">
                        <span class="text-sb-42px text-black" data-total-correct-cnt></span>개
                    </div>
                </div>
                <div class="modal-shadow-style p-4 mt-4 row col">
                    <div class=" scale-text-gray_05 text-sb-20px col">
                        <div class="px-0 col">
                            <span>{{$student->student_name}}</span> 학생의
                        </div>
                        <div class="px-0 col mt-2">
                            <span class="text-black">전체 평가 응시 횟수</span> 입니다.
                        </div>
                        <div class="h-center mt-4 pt-3">
                            <div class="h-center col text-sb-20px">
                                <div class="col">
                                    <span class="text-sb-20px scale-text-gray_05">지난달보다</span>
                                </div>
                                <div class="col-auto pe-3">
                                    <div class="h-center">
                                        <span class="test-b-20px text-danger">
                                            <span data-prev-total-exam-cnt>-</span>회
                                        </span>
                                        <img data-img-up="prev_exam" src="https://sdang.acaunion.com/images/red_arrow_up_icon.svg" hidden>
                                        <img data-img-down="prev_exam" src="https://sdang.acaunion.com/images/blue_arrow_down_icon.svg"  hidden>
                                    </div>
                                </div>
                            </div>
                            <div class="h-center col text-sb-20px ms-4">
                                <div class="col">
                                    <span class="text-sb-20px scale-text-gray_05">또래들보다</span>
                                </div>
                                <div class="col-auto pe-3">
                                    <div class="h-center">
                                        <span class="test-b-20px text-danger">
                                            <span data-myage-total-exam-cnt>-</span>회
                                        </span>
                                        <img data-img-up="myage_exam" src="https://sdang.acaunion.com/images/red_arrow_up_icon.svg"  hidden>
                                        <img data-img-down="myage_exam" src="https://sdang.acaunion.com/images/blue_arrow_down_icon.svg" hidden>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <span class="text-sb-42px text-black" data-my-total-exam-cnt>-</span>회
                    </div>
                </div>
                <div class="modal-shadow-style p-4 mt-4 row col">
                    <div class=" scale-text-gray_05 text-sb-20px col">
                        <div class="px-0 col">
                            <span>{{$student->student_name}}</span> 학생의
                        </div>
                        <div class="px-0 col mt-2">
                            <span class="text-black">전체 평가 응시 </span> 입니다.
                        </div>
                        <div class="h-center mt-4 pt-3">
                            <div class="h-center col text-sb-20px">
                                <div class="col">
                                    <span class="text-sb-20px scale-text-gray_05">지난달보다</span>
                                </div>
                                <div class="col-auto pe-3">
                                    <div class="h-center">
                                        <span class="test-b-20px text-danger">
                                            <span data-prev-total-exam-aver></span>점
                                        </span>
                                        <img data-img-up="prev_aver" src="https://sdang.acaunion.com/images/red_arrow_up_icon.svg" hidden>
                                        <img data-img-down="prev_aver" src="https://sdang.acaunion.com/images/blue_arrow_down_icon.svg"  hidden>
                                    </div>
                                </div>
                            </div>
                            <div class="h-center col text-sb-20px ms-4">
                                <div class="col">
                                    <span class="text-sb-20px scale-text-gray_05">또래들보다</span>
                                </div>
                                <div class="col-auto pe-3">
                                    <div class="h-center">
                                        <span class="test-b-20px text-danger">
                                            <span data-myage-total-exam-aver>3</span>점
                                        </span>
                                        <img data-img-up="myage_aver" src="https://sdang.acaunion.com/images/red_arrow_up_icon.svg"  hidden>
                                        <img data-img-down="myage_aver" src="https://sdang.acaunion.com/images/blue_arrow_down_icon.svg" hidden>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <span class="text-sb-42px text-black" data-my-total-exam-aver>-</span>점
                    </div>
                </div>
            </div>

            <!-- div > aside , section -->
            <div class="row mt-5 pt-2">
                <!-- 평가 탭  -->
                <aside class="col-3">
                    <div class="rounded-4 modal-shadow-style">
                        <ul class="tab py-4 px-3 ">
                            @if(!empty($evaluation_codes))
                            @foreach($evaluation_codes as $evaluation_code)
                            <li class="mb-2">
                                <button onclick="evalManageAsdieTab(this)" data-btn-eval-main-tab data-evaluation-seq="{{$evaluation_code->id}}"
                                    class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                                    <img src="{{ asset('images/evaluation_icon.svg') }}" width="32" class="me-2">
                                    {{$evaluation_code->code_name}}
                                </button>
                            </li>
                            @endforeach
                            @endif
                        </ul>
                    </div>
                </aside>
                <!-- 단원평가 -->
                <section class="col pe-0">
                    {{-- TEST: 수치 테스트 가져오기. --}}
                    @php
                        $scope[0] = rand(0, 100);
                        $scope[1] = rand(0, 100);
                        $scope[2] = rand(0, 100);
                        $scope[3] = rand(0, 100);
                        $scope[4] = rand(0, 100);
                        $scope[5] = rand(0, 100);
                        $scope[6] = rand(0, 100);
                        $scope1[0] = rand(0, 100);
                        $scope1[1] = rand(0, 100);
                        $scope1[2] = rand(0, 100);
                        $scope1[3] = rand(0, 100);
                        $scope1[4] = rand(0, 100);
                        $scope1[5] = rand(0, 100);
                        $scope1[6] = rand(0, 100);
                    @endphp
                    <!-- 그래프 / 과목 -->
                    <div data-div-graph="subject" class="modal-shadow-style rounded px-4 pb-2">
                        {{-- 숨김/보이기 --}}
                        <div class="pt-4  mb-5 pb-1" >
                            {{-- 40 --}}
                            <div>
                                <div class="py-lg-5"></div>
                            </div>

                            <!-- ------------------------------------------------------------------------------  -->
                            <!-- 시간단위 -->
                            <div class="m-1 row" style="height:250px;">
                                <div data-bundle="subject_time"
                                    class="col-auto m-0 d-flex flex-column position-relative" style="width:60px;">
                                    <div class="col mb-2 position-absolute d-flex flex-column"
                                        style="bottom:4px;height:250px;">
                                        <div data-row="5" class="col text-sb-18px scale-text-gray_05">100</div>
                                        <div data-row="4" class="col text-sb-18px scale-text-gray_05">80</div>
                                        <div data-row="3" class="col text-sb-18px scale-text-gray_05">60</div>
                                        <div data-row="2" class="col text-sb-18px scale-text-gray_05">40</div>
                                        <div data-row="1" class="col text-sb-18px scale-text-gray_05">20</div>
                                    </div>
                                    <div data-row="0" class="position-absolute text-sb-18px scale-text-gray_05"
                                        style="bottom:-10px">
                                        0</div>
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
                                    <div data-bundle="study_time_by_subject"
                                        class="row mx-0 position-absolute top-0 bottom-0 start-0 end-0 ms-4 px-5">
                                        @if(!empty($subject_codes))
                                        @foreach($subject_codes as $idx => $subject_code)
                                        <div class="col row gap-2 align-items-end justify-content-center position-relative" data-row="{{$subject_code->id}}">
                                            <div data-div-graph-top class="position-absolute text-center px-0" style="top: -73px" {{ $idx == 0 ? '':'hidden' }}>
                                                <span class="text-white text-b-20px rounded-3" style="background: #FFC747;padding:12px 20px;">
                                                    <span data-prev-month-up-rate> - </span>점 상승
                                                </span>
                                                <div class="position-relative">
                                                    <img src="{{ asset('images/yellow_arrow_down_icon.svg') }}" width="18" class="position-absolute"
                                                        style="left: 43%;bottom:-18px">
                                                </div>

                                            </div>
                                            <div data-prev-month class="col-auto rounded-top-3 scale-bg-gray_02 px-3" style="height:0%"> </div>
                                            <div data-this-month class="col-auto rounded-top-3 px-3 ms-1" style="height:0%;background:#FFC747"> </div>
                                            <div class="position-absolute text-center px-0" style="bottom:-62px;">
                                                <button  onclick="scoreClickGraphOne(this)" data-btn-subject-name
                                                    class="btn btn-outline-primary-y border-0 rounded-pill text-sb-20px scale-text-gray_05 scale-text-white-hover {{ $idx == 0 ? 'active':'' }}" style="padding:4px 16px">
                                                    {{ $subject_code->code_name}}
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
                                    <span class="text-sb-20px scale-text-gray_05 ms-2">지난달</span>
                                </div>
                                <div class="col-auto all-center">
                                    <span class="rounded-pill pt-3 ps-3" style="border:4px solid #FFC747;"></span>
                                    <span class="text-sb-20px scale-text-gray_05 ms-2">현재</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 점수 차순 -->
                    <div data-div-order-score="subject" >
                        <div class="row row-cols-3 mt-4" data-bundle="bottom_subjects">
                        <div class="col modal-shadow-style p-4 mt-4"v data-row="copy" hidden>
                            <h4 class="text-r-24px scale-text-black row mx-0">
                                <div class="col">
                                    <span data-subject-name>#과목</span>
                                </div>
                                <div class="col-auto">
                                    <span data-exam-rate>00</span>점
                                </div>
                            </h4>
                            <div class="row pt-4 pb-2">
                                <div class="col">
                                    <span class="text-sb-20px scale-text-gray_05">지난달보다</span>
                                </div>
                                <div class="col-auto">
                                    <div class="h-center">
                                        <span class="test-b-20px text-danger">
                                            <span data-prev-exam-comparison>00</span>점
                                        </span>
                                        <img data-up-img="prev" src="{{ asset('images/red_arrow_up_icon.svg') }}" alt="" hidden>
                                        <img data-down-img="prev" src="{{ asset('images/blue_arrow_down_icon.svg') }}" alt="" hidden>
                                    </div>
                                </div>
                            </div>
                            <div class="row pt-1">
                                <div class="col">
                                    <span class="text-sb-20px scale-text-gray_05">또래들보다</span>
                                </div>
                                <div class="col-auto">
                                    <div class="h-center">
                                        <span class="test-b-20px text-danger">
                                            <span data-my-age-exam-comparison>00</span>점
                                        </span>
                                        <img data-up-img="myage" src="{{ asset('images/red_arrow_up_icon.svg') }}" alt="" hidden>
                                        <img data-down-img="myage" src="{{ asset('images/blue_arrow_down_icon.svg') }}" alt="" hidden>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <!-- <div class="row row-cols-3 mt-4"> -->
                        <!--     @if(!empty($subject_codes)) -->
                        <!--         @foreach($subject_codes as $idx => $subject_code) -->
                        <!--         <div class="col mt-1"> -->
                        <!--             <div class="modal-shadow-style p-4 mt-4"> -->
                        <!--                 <h4 class="text-r-24px scale-text-black row mx-0"> -->
                        <!--                     <div class="col"> -->
                        <!--                         <span>{{$subject_code->code_name}}</span> -->
                        <!--                     </div> -->
                        <!--                     <div class="col-auto"> -->
                        <!--                         <span>{{ $scope1[$idx] }}</span>점 -->
                        <!--                     </div> -->
                        <!--                 </h4> -->
                        <!--                 <div class="row pt-4 pb-2"> -->
                        <!--                     <div class="col"> -->
                        <!--                         <span class="text-sb-20px scale-text-gray_05">지난달보다</span> -->
                        <!--                     </div> -->
                        <!--                     <div class="col-auto"> -->
                        <!--                         <div> -->
                        <!--                             <span class="test-b-20px text-danger"> -->
                        <!--                                 <span>{{rand(5, 10)}}</span>점 -->
                        <!--                             </span> -->
                        <!--                             <img src="https://sdang.acaunion.com/images/red_arrow_up_icon.svg" > -->
                        <!--                             <img src="https://sdang.acaunion.com/images/blue_arrow_down_icon.svg" hidden> -->
                        <!--                         </div> -->
                        <!--                     </div> -->
                        <!--                 </div> -->
                        <!--                 <div class="row pt-1"> -->
                        <!--                     <div class="col"> -->
                        <!--                         <span class="text-sb-20px scale-text-gray_05">또래들보다</span> -->
                        <!--                     </div> -->
                        <!--                     <div class="col-auto"> -->
                        <!--                         <div> -->
                        <!--                             <span class="test-b-20px secondary-text-mian"> -->
                        <!--                                 <span>{{rand(0,10)}}</span>점 -->
                        <!--                             </span> -->
                        <!--                             <img src="https://sdang.acaunion.com/images/red_arrow_up_icon.svg" hidden> -->
                        <!--                             <img src="https://sdang.acaunion.com/images/blue_arrow_down_icon.svg" > -->
                        <!--                         </div> -->
                        <!--                     </div> -->
                        <!--                 </div> -->
                        <!--             </div> -->
                        <!--         </div> -->
                        <!--         @endforeach -->
                        <!--     @endif -->
                        <!-- </div> -->
                    </div>
                </section>
            </div>
        </article>

         {{-- 자녀 성적표 --}}
        <article data-article="child_report_card">
            <!-- 과목별 성적  -->
            <div class="h-center mt-4">
                <img src="{{asset('images/my_score_icon.svg')}}" width="32" class="p-1">
                <span class="text-sb-24px">과목별 성적</span>
            </div>
            <div class="row row-cols-3 mt-2 " data-bundle="subject_grades">
                @if (!empty($subject_codes))
                @foreach ($subject_codes as $idx => $subject_code)
                <div class="col-4 col-xxl-2 mt-1 " data-row="{{$subject_code->id}}" >
                    <input type="hidden" value="{{$subject_code->id}}">
                    <div data-middel-scorecard
                        class="modal-shadow-style p-4 mt-4 cursor-pointer primary-bg-mian-hover  rounded " onclick="clickGradesBySubject(this)">
                        <input type="hidden" data-subject-seq="" value="{{$subject_code->id}}">
                        <input type="hidden" data-img-src value="{{$subject_code->function_code}}">
                        <h4 class="text-r-24px scale-text-black row mx-0">
                            <div class="col scale-text-white-hover">
                                <span data-subject-name>{{$subject_code->code_name}}</span>
                            </div>
                            <div class="col-auto scale-text-white-hover">
                                <span data-exam-rate></span>점
                            </div>
                        </h4>
                        <div class="row pt-4 pb-2">
                            <div class="col ">
                                <span class="text-sb-20px scale-text-gray_05 scale-text-white-hover">지난달보다</span>
                            </div>
                            <div class="col-auto ">
                                <div class="h-center">
                                    <span class="test-b-20px  scale-text-white-hover">
                                        <span data-prev-exam-comparison></span>점
                                    </span>
                                    <img data-up-img="prev" src="{{ asset('images/red_arrow_up_icon.svg') }}" hidden>
                                    <img data-down-img="prev" src="{{ asset('images/blue_arrow_down_icon.svg') }}"  hidden>
                                </div>
                            </div>
                        </div>
                        <div class="row pt-1">
                            <div class="col ">
                                <span class="text-sb-20px scale-text-gray_05 scale-text-white-hover">또래들보다</span>
                            </div>
                            <div class="col-auto">
                                <div class="h-center">
                                    <span class="test-b-20px scale-text-white-hover">
                                        <span data-my-age-exam-comparison></span>점
                                    </span>
                                    <img data-up-img="myage" src="{{ asset('images/red_arrow_up_icon.svg') }}" hidden>
                                    <img data-down-img="myage" src="{{ asset('images/blue_arrow_down_icon.svg') }}" hidden>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
            <!-- 영역별 성적 통계 -->
            <div data-div="subejct_detail_bottom">
                <div class="h-center mt-4">
                    <img src="{{asset('images/my_score_icon.svg')}}" width="32" class="p-1">
                    <span class="text-sb-24px">영역별 성적 통계</span>
                </div>
                <div class="row mt-4">
                    <div class="col-6">
                        <!-- 과목별로 색깔이 변하도록 -->
                        <div class="studyColor-bg-korean row gx-3 p-4 align-items-center rounded-3 h-100">
                            <div class="col-auto">
                                <img src="" width="52" data-subject-img>
                            </div>
                            <div class="col text-white">
                                <span class="text-sb-28px" data-subject-lecture-name>국어 [대단원평가] 3단원. 자신의 경험을 글로 써요.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="rounded-3 scale-bg-gray_01 row p-4">
                            <div class="col px-0">
                                <div class="row pb-2">
                                    <div class="col-auto">
                                        <span class="text-sb-20px scale-text-gray_05">지난달보다</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="h-center">
                                            <span class="test-b-20px text-danger">
                                                <span data-prev-exam-comparison></span>점
                                            </span>
                                            <img data-up-img="prev" src="https://sdang.acaunion.com/images/red_arrow_up_icon.svg" hidden>
                                            <img data-down-img="prev" src="https://sdang.acaunion.com/images/blue_arrow_down_icon.svg" hidden>
                                        </div>
                                    </div>
                                </div>
                                <div class="row pt-1">
                                    <div class="col-auto">
                                        <span class="text-sb-20px scale-text-gray_05">또래들보다</span>
                                    </div>
                                    <div class="col-auto">
                                        <div class="h-center">
                                            <span class="test-b-20px text-danger">
                                                <span data-my-age-exam-comparison></span>점
                                            </span>
                                            <img data-up-img="myage" src="https://sdang.acaunion.com/images/red_arrow_up_icon.svg" hidden>
                                            <img data-down-img="myage" src="https://sdang.acaunion.com/images/blue_arrow_down_icon.svg" hidden>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto px-0 text-end">
                                <span class="text-sb-42px" data-exam-rate></span>
                                <span class="text-sb-24px scale-text-gray_05">점</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="rounded-3 scale-bg-gray_01 row p-4 h-100 h-center">
                            <div class="col px-0">
                                <span class="text-sb-20px scale-text-gray_05"> 내용영역 </span>
                            </div>
                            <div class="col-auto px-0 text-end">
                                <span class="text-sb-32px text-danger" data-content-area-name></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row gx-4 mt-5">
                    <!-- 그래프  -->
                    <div class="px-0 col me-4">
                        <div class="modal-shadow-style py-5 px-5" ata-div-graph="area_statistics">
                            <div class="py-lg-3"> </div>
                            <div class="m-1 row" style="height:250px;">
                                <div data-bundle="area_statistics_1"
                                    class="col-auto m-0 d-flex flex-column position-relative" style="width:60px;">
                                    <div class="col mb-2 position-absolute d-flex flex-column"
                                        style="bottom:4px;height:250px;">
                                        <div data-row="5" class="col text-sb-18px scale-text-gray_05">100</div>
                                        <div data-row="4" class="col text-sb-18px scale-text-gray_05">80</div>
                                        <div data-row="3" class="col text-sb-18px scale-text-gray_05">60</div>
                                        <div data-row="2" class="col text-sb-18px scale-text-gray_05">40</div>
                                        <div data-row="1" class="col text-sb-18px scale-text-gray_05">20</div>
                                    </div>
                                    <div data-row="0" class="position-absolute text-sb-18px scale-text-gray_05"
                                        style="bottom:-10px">
                                        0</div>
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
                                    <div data-bundle="area_statistics_2"
                                        class="row mx-0 position-absolute top-0 bottom-0 start-0 end-0 ms-4 px-2">
                                        <div data-row="copy"
                                            class="col row gap-2 align-items-end justify-content-center position-relative px-0" hidden>
                                            <!-- 마우스오버 상단 시간 표기 -->
                                            <div data-div-mouseover="" class="position-absolute text-center mx-0 justify-content-center" style="top: -20px;display:none">
                                                <span class="text-white text-b-20px rounded-3 d-inline-flex align-items-center gap-2" style="background: #473300;padding:12px 12px;">
                                                    <div class="col-auto all-center" hidden>
                                                        <span class="rounded-pill pt-3 ps-3" style="border:4px solid #f1f1f1;"></span>
                                                        <span class="text-sb-20px white-text ms-2" data-other-cnt></span>
                                                    </div>
                                                    <div class="col-auto all-center">
                                                        <span class="rounded-pill pt-3 ps-3" style="border:4px solid #FFC747;"></span>
                                                        <span class="text-sb-20px white-text ms-2" data-self-cnt></span>
                                                    </div>
                                                </span>
                                                <div class="position-relative">
                                                    <svg class="position-absolute" style="width:18px;left: 43%;bottom:-13px" width="18" height="15" viewBox="0 0 18 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M7.304 13.2864C8.08734 14.5397 9.91266 14.5397 10.696 13.2864L17.0875 3.06C17.9201 1.7279 16.9624 0 15.3915 0H2.6085C1.03763 0 0.0799387 1.7279 0.912499 3.06L7.304 13.2864Z" fill="#473300" />
                                                    </svg>
                                                </div>
                                            </div>
                                            <!-- 또래 평균 BAR -->
                                            <div data-other-bar hidden
                                                class="col-auto rounded-top-3 scale-bg-gray_02 px-3"
                                                style="height:0%"> </div>
                                            <!-- 자녀 BAR -->
                                            <div  data-self-bar
                                                class="col-auto rounded-top-3 px-3 ms-1"
                                                style="height:0%;background:#FFC747"> </div>
                                            <div class="position-absolute text-center px-0" style="bottom:-62px;">
                                                <button data-btn-area-statistics-btm onclick=""
                                                    class="btn btn-outline-primary-y border-0 rounded-pill scale-text-white-hover text-sb-18px scale-text-gray_05"
                                                    style="padding:4px 0px">

                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- padding 125px --}}
                            <div style="height: 125px;"></div>

                            {{-- 지난달, 현재 --}}
                            <div class="gap-4 all-center">
                                <div class="col-auto all-center" hidden>
                                    <span class="rounded-pill pt-3 ps-3" style="border:4px solid #f1f1f1;"></span>
                                    <span class="text-sb-20px scale-text-gray_05 ms-2">또래평균</span>
                                </div>
                                <div class="col-auto all-center">
                                    <span class="rounded-pill pt-3 ps-3" style="border:4px solid #FFC747;"></span>
                                    <span class="text-sb-20px scale-text-gray_05 ms-2">자녀</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- 다각형 -->
                    <div class="px-0 col-4">
                        <div class="modal-shadow-style h-100">
                            @include('utils.part_polygon_graph')
                        </div>
                    </div>
                </div>
                @if(session()->get('login_type') != 'student')
                <!-- 선생님 말씀 -->
                <div>
                    <div class="h-center gap-2 mt-5 mb-4">
                        <div >
                            <img src="{{asset('images/my_score_icon.svg')}}" width="32" class="p-1">
                        </div>
                        <div >
                            <span class="text-sb-24px"> 선생님 말씀</span>
                        </div>
                    </div>

                    <div class="modal-shadow-style rounded-2 d-flex p-4">
                        <div class="me-4 py-2">
                            <div class="btn btn_top_icon p-0 ms-2 border-primary-y rounded-circle border-2 h-center profile" onclick="">
                                <img src="https://sdang.acaunion.com/images/yellow_human_icon.svg" class="rounded-circle" style="width:72px;height:72px;" data-profile-img-path>
                            </div>
                        </div>
                        <div class="ms-2 py-2 w-100">
                            <div data-etc-contents class="p-4 scale-bg-gray_01 text-sb-24px scale-text-gray_05 rounded-4" style="line-height: 2rem;"></div>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </article>

        {{-- 오답노트 --}}
        <article data-article="wrong_note" hidden>
            <div class="row mt-5 pt-2">
                <aside class="col-3">
                    <div class="rounded-4 modal-shadow-style">
                        <ul class="tab py-4 px-3 ">
                            <li class="mb-2">
                                <button onclick="evalManagerWrongNoteAsideTab(this)" data-wrong-btn-aide-tab="wrong_incomplete"
                                    class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover active">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16.5 7.5L7.50022 16.4998" stroke="#FFC747" stroke-width="2.5" stroke-linecap="round"/>
                                        <path d="M16.5 16.5L7.50022 7.50022" stroke="#FFC747" stroke-width="2.5" stroke-linecap="round"/>
                                    </svg>
                                    미완료
                                </button>
                            </li>
                            <li class="mb-2">
                                <button onclick="evalManagerWrongNoteAsideTab(this)" data-wrong-btn-aide-tab="wrong_complete"
                                    class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8 13.3948L13.0683 20.0064C13.5898 20.6867 14.6086 20.7052 15.1545 20.0443L24 9.33594" stroke="#FFC747" stroke-width="3" stroke-linecap="round"/>
                                    </svg>
                                    완료
                                </button>
                            </li>
                        </ul>
                    </div>
                </aside>
                <section class="col">
                    <div class="text-sb-24px text-black row">
                        <div class="col text-black h-center">
                            <span data-wrong-total-cnt class="text-danger me-2">총 3개</span> <span data-hide="incomplete">미</span> 완료 오답노트가 <span data-chg-after-text class="ms-2"> 있습니다.</span>
                        </div>
                        <div class="col-auto">
                            <div class="d-inline-block select-wrap select-icon" style="min-width:100px">
                                {{-- : select 드롭다운--}}
                                <select data-select-standard onchange="evalWrongNoteSelect()"
                                    class="rounded-pill border-gray lg-select text-sb-24px ps-4"
                                    style="min-width:100px;padding-top:18px;padding-bottom:18px;" onchage="">
                                    <option value="date_desc">기준선택</option>
                                    <option value="date_asc">완료기간 임박 순</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 pt-2">
                        <table class="table-style w-100" style="min-width: 100%;">
                            <colgroup>
                                <col style="width: 80px;">
                            </colgroup>
                            <thead>
                                <tr class="text-sb-20px modal-shadow-style rounded">
                                    <th style="width: 80px">
                                        <label class="checkbox mt-1">
                                            <input type="checkbox" onchange="evalManageWrongAllChk(this)">
                                            <span class="">
                                            </span>
                                        </label>
                                    </th>
                                    <th>완료 기간</th>
                                    <th>과목</th>
                                    <th>평가(단원)</th>
                                    <th>남은 문제</th>
                                </tr>
                            </thead>
                            <tbody data-bundle="tby_wrong">
                                <tr class="text-m-20px" data-row="copy" hidden>
                                <!-- <tr class="text-m-20px" data-row="clone" hidden> -->
                                    <input type="hidden" data-student-seq >
                                    <input type="hidden" data-student-exam-seq >
                                    <input type="hidden" data-exam-seq >
                                    <td class=" py-4">
                                        <label class="checkbox mt-1">
                                            <input type="checkbox" class="chk">
                                            <span class="">
                                            </span>
                                        </label>
                                    </td>
                                    <td class=" py-4 text-black">
                                        <p data-datetim-limit data-text="#오늘까지">오늘까지</p>
                                    </td>
                                    <td class=" py-4">
                                        <p data-sbuject-name data-text="#수학 1단원"></p>
                                    </td>
                                    <td class=" py-4">
                                        <span data-lecture-name data-text="#단원 제목"></span>
                                    </td>
                                    <td class="py-4 text-black">
                                        <span data-wrong-cnt data-text="#3문제"></span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="all-center mt-52">
                        <div class="col"></div>
                        <div class="col">
                            <div class="col d-flex justify-content-center">
                                <ul class="pagination col-auto" data-page="1" hidden>
                                    <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="1"
                                        onclick="evalPageFunc('1', 'prev')">
                                        <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                                    </button>
                                    <li class="page-item" hidden>
                                        <a class="page-link" onclick="">0</a>
                                    </li>
                                    <span class="page" data-page-first="1" hidden onclick="evalPageFunc('1', this.innerText);"
                                        disabled>0</span>
                                    <button href="javascript:void(0)" class="btn p-0 next" data-page-next="1"
                                        onclick="evalPageFunc('1', 'next')" data-is-next="0">
                                        <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                                    </button>
                                </ul>
                            </div>
                        </div>
                        <div class="col text-end">
                            <button type="button" onclick="evalManageWrongPrintModalOpen();"
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 ">
                                <img src="{{ asset('images/print_icon.svg') }}" width="24">
                                출력하기
                            </button>
                        </div>
                    </div>


                </section>
            </div>
        </article>
    </div>


</div>
{{-- 160px  --}}
<div data-explain="160">
    <div class="py-lg-5"> </div>
    <div class="py-lg-4"> </div>
    <div class="pt-lg-3"> </div>
</div>

{{-- 모달 / 오답노트 출력하기 --}}
<div class="modal fade " id="modal_wrong_print" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered rounded" >
    <div class="modal-content border-none rounded p-3 modal-shadow-style">
      <div class="modal-header border-bottom-0">
        <h1 class="modal-title fs-5 text-b-24px h-center" id="">
            <img src="{{asset('images/book2_icon.svg')}}" width="32">
            오답노트 출력하기
        </h1>
        <button type="button" style="width:32px;height:32px"
        class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-sb-20px">
            <div class="h-center gap-3 mb-5">
                <label class="radio">
                    <input type="radio" id="only_exam_print" name="wrong_print_radio"  >
                    <span class=""></span>
                </label>
                <span onclick="this.closest('div').querySelector('input').click()" class="cursor-pointer">문제만 출력하기 </span>
            </div>

            <div class="h-center gap-3 mb-5">
                <label class="radio">
                    <input type="radio" id="only_answer_print" name="wrong_print_radio"  >
                    <span class=""></span>
                </label>
                <span onclick="this.closest('div').querySelector('input').click()" class="cursor-pointer">정답만 출력하기</span>
            </div>
            <div class="h-center gap-3 mb-5">
                <label class="radio">
                    <input type="radio" id="all_print" name="wrong_print_radio"   >
                    <span class=""></span>
                </label>
                <span onclick="this.closest('div').querySelector('input').click()" class="cursor-pointer">문제와 정답 모두 출력하기</span>
            </div>
                <div class="all-center p-3">
                    <button type="button" onclick="evalWrongPrintPost();"
                        class="btn-lg-primary text-b-24px rounded scale-text-white w-100 w-center">출력하기</button>
                </div>
      </div>
    </div>
  </div>
</div>

<script>
const content_area_codes = @json($content_area_codes);
const cognitive_area_codes = @json($cognitive_area_codes);

 document.addEventListener("DOMContentLoaded", async function() {
    // 총 몇문제중 정답 개수 ~ 전체평가응시 점수
    await evalExamTotalCntSelect();

    // 첫 평가탭을 클릭.
    document.querySelector('[data-btn-eval-main-tab]').click();
    // 첫화면 지난달, 현재 과목별 그레프.
    // await evalSubjectSelect();

    // 중간 잘하는~ 점수가 떨어진 과목 비동기
    await evalMiddleSubjectSelect();

    // 오답노트
    await evalWrongNoteSelect();

    await evalMiddleSubjectList();
    // 과목별 점수 첫과목별 성적 클릭.
    setTimeout(function(){
        document.querySelector('[data-middel-scorecard]').click();
    }, 500);

    // 선생님 말씀 불러오기
    evalMiddleTeacherTalk();
});

document.addEventListener('DOMContentLoaded', function () {
    // 상단 날짜 활성화
    evalManageUpdateDisplay();
});

let currentDate = new Date();
let currentYear = currentDate.getFullYear();
let currentMonth = currentDate.getMonth();
const months = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'];
function evalManageUpdateDisplay(direction) {
    const year_display = document.getElementById('year_display');
    const months_display = document.getElementById('months_display');
    year_display.textContent = `${currentYear}년`;
    months_display.innerHTML = '';

    const startMonth = currentMonth < 6 ? 0 : 6;
    for (let i = startMonth; i < startMonth + 6; i++) {
        const monthElem = document.createElement('button');
        monthElem.textContent = months[i];
        monthElem.classList.add('month', 'btn' ,'btn-outline-primary-y', 'ctext-gc1', 'border-0', 'rounded-pill', 'cfs-5', 'p-2', 'px-4');
        monthElem.setAttribute('onclick', 'evalManageSelectMonth(this)');
        if (i === currentMonth) {
            monthElem.classList.add('active');
        }
        months_display.appendChild(monthElem);
    }

    if(direction == -1){
        months_display.querySelectorAll('button').forEach(function(el){
            el.classList.remove('active');
        });
        months_display.querySelectorAll('button')[5].classList.add('active');
    }else if(direction == 1){
        months_display.querySelectorAll('button').forEach(function(el){
            el.classList.remove('active');
        });
        months_display.querySelectorAll('button')[0].classList.add('active');
    }

    // TODO: 날짜에 의한 면 select 기능 추가.

}

function evalManageMoveHalfYear(direction) {
    currentMonth += direction * 6;
    if (currentMonth > 11) {
        currentMonth -= 12;
        currentYear++;
    } else if (currentMonth < 0) {
        currentMonth += 12;
        currentYear--;
    }
    evalManageUpdateDisplay(direction);
}

// 달 선택
function evalManageSelectMonth(vthis){
    const months_display = document.getElementById('months_display');
    months_display.querySelectorAll('button').forEach(function(el){
        el.classList.remove('active');
    });
    vthis.classList.add('active');
    // inp 에 활성화 되어있는 년월 넣기
    const year = document.querySelector('#year_display').textContent.replace('년','');
    const month = vthis.textContent.replace('월','');
    document.querySelector('[data-inp-year]').value = year;
    document.querySelector('[data-inp-month]').value = month;
    // 현재 달의 내용들 가져오기

    // 평가관리 첫 중간 정보
    evalExamTotalCntSelect();

    // 중간 잘하는~ 점수가 떨어진 과목 비동기
    evalMiddleSubjectSelect();
    // 과목별 점수
    evalMiddleSubjectList();
    // 선생님 말씀 불러오기
    evalMiddleTeacherTalk();
}

// 최상단 탭 클릭(자녀 성적표, 오답노트)
function evalManageTopTabClick(vthis){
    if(vthis.classList.contains('active')){
        vthis.classList.remove('active');
    }else{
        document.querySelectorAll('[data-main-top-tab]').forEach(function(el){
            el.classList.remove('active');
        });
        vthis.classList.add('active');
    }
    const active_el = document.querySelector('[data-main-top-tab].active');
    let type = '';
    if(active_el != undefined) type = active_el.dataset.mainTopTab;

    // TODO: 평가관리='', 자녀성적표='1', 오답노트='2' 중 화면 보이기.
    document.querySelectorAll('[data-article]').forEach(function(el){
        el.hidden = true;
    });

    const top_date = document.querySelector('[data-top-date-tab]');
    const top_type_subject = document.querySelector('[data-top-subject-type]');
    switch(type){
        case '':
            document.querySelector('[data-article="evaluation_management"]').hidden = false;
            top_date.hidden = false;
            top_type_subject.hidden = false;
            break;
        case '1':
            document.querySelector('[data-article="child_report_card"]').hidden = false;
            top_date.hidden = false;
            top_type_subject.hidden = false;
            break;
        case '2':
            document.querySelector('[data-article="wrong_note"]').hidden = false;
            top_date.hidden = true;
            top_type_subject.hidden = true;
            break;
    }
}

// 평가관리 > 평가 탭 클릭.
function evalManageAsdieTab(vthis){
    document.querySelectorAll('[data-btn-eval-main-tab]').forEach(function(el){
        el.classList.remove('active');
    });
    vthis.classList.add('active');
    // : 선택 평가에 따라 데이터 변경되도록
    evalSubjectSelect();
}

// 오답노트 > 완료, 미완료 탭 클릭.
function evalManagerWrongNoteAsideTab(vthis){
    document.querySelectorAll('[data-wrong-btn-aide-tab]').forEach(function(el){
        el.classList.remove('active');
    });
    vthis.classList.add('active');
    const type = vthis.dataset.wrongBtnAideTab;
    // TODO: 완료, 미완 리스트 불러오기.
    const data_hide = document.querySelector('[data-hide="incomplete"]');
    if(type == 'wrong_complete'){
        data_hide.hidden = true;
    }else{
        data_hide.hidden = false;
    }
    evalWrongNoteSelect();
}

// 오답노트 > 체크 박스 전체 선택
function evalManageWrongAllChk(vthis){
    const checked = vthis.checked;
    const bundle = document.querySelector('[data-bundle="tby_wrong"]');
    const chks = bundle.querySelectorAll('[data-row="clone"] .chk');
    chks.forEach(function(el){
        el.checked = checked;
    });
}

// 오답노트 > 프린트 모달 불러오기.
function evalManageWrongPrintModalOpen(){
    const bundle = document.querySelector('[data-bundle="tby_wrong"]');
    const chks = bundle.querySelectorAll('[data-row="clone"] .chk:checked');
    const chk_cnt = chks.length;

    if(chk_cnt == 0){
        toast('선택된 항목이 없습니다.');
        return;
    }
    const myModal = new bootstrap.Modal(document.getElementById('modal_wrong_print'), {
        keyboard: false,
        backdrop: false
    });
    myModal.show();
}

// 과목별 성적 클릭
function clickGradesBySubject(vthis){
    // TODO: 데이터베이스 작업 진행.
    // 갑자기 성적별 통계 나와서 데이터기획이 없음.
    //
    //data-bundle="subject_grades" data-middel-scorecard
    document.querySelectorAll("[data-bundle='subject_grades'] [data-middel-scorecard]").forEach(function(el){
        el.classList.remove('active');
    });

    vthis.classList.add('active');




    evalSubjectExamDetailSelect();
}


// 중간 잘하는~ 점수가 떨어진 과목 비동기
function evalMiddleSubjectSelect(){
    const year = document.querySelector('[data-inp-year]').value;
    const month = document.querySelector('[data-inp-month]').value;
    const month_date = year + '-' + month;

    const page = "/student/my/score/subject/good/not/select";
    const parameter = {
        'month':month_date
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            // console.log(result);
            const good_subject_exam = result.good_subject_exam[0];
            const notgood_subject_exam = result.notgood_subject_exam[0];
            const best_exam = result.best_exam;
            const worst_exam = result.worst_exam;

            const section = document.querySelector('[data-section="middle"]');
            const good_name_el = section.querySelector('[data-subject-name="good"]');
            const good_img_el = section.querySelector('[data-subject-img="good"]');
            const notgood_name_el = section.querySelector('[data-subject-name="notgood"]');
            const notgood_img_el = section.querySelector('[data-subject-img="notgood"]');
            const best_name_el = section.querySelector('[data-subject-name="best"]');
            const best_img_el = section.querySelector('[data-subject-img="best"]');
            const worst_imb_el = section.querySelector('[data-subject-img="worst"]');
            const worst_name_el = section.querySelector('[data-subject-name="worst"]');

            if(good_subject_exam?.subject_name)
                good_name_el.textContent = good_subject_exam.subject_name;
            else
                good_name_el.textContent = '-';
            if(good_subject_exam?.function_code){
                good_img_el.src = '/images/'+good_subject_exam.function_code+'.svg';
                good_img_el.hidden = false;
            }else{
                good_img_el.hidden = true;
            }
            if(notgood_subject_exam?.subject_name)
                notgood_name_el.textContent = notgood_subject_exam.subject_name;
            else
                notgood_name_el.textContent = '-';
            if(notgood_subject_exam?.function_code){
                notgood_img_el.src = '/images/'+notgood_subject_exam.function_code+'.svg';
                notgood_img_el.hidden = false;
            }else{
                notgood_img_el.hidden = true;
            }

            if(best_exam?.subject_name)
                best_name_el.textContent = best_exam.subject_name;
            else
                best_name_el.textContent = '-';
            if(best_exam?.function_code){
                best_img_el.src = '/images/'+best_exam.function_code+'.svg';
                best_img_el.hidden = false;
            }else{
                best_img_el.hidden = true;
            }
            if(worst_exam?.subject_name)
                worst_name_el.textContent = worst_exam.subject_name;
            else
                worst_name_el.textContent = '-';
            if(worst_exam?.function_code){
                worst_imb_el.src = '/images/'+worst_exam.function_code+'.svg';
                worst_imb_el.hidden = false;
            }else{
                worst_imb_el.hidden = true;
            }

        }else{}
    });
}

// 과목별 점수
function evalMiddleSubjectList(callback){
    const unit = '';
    const year = document.querySelector('[data-inp-year]').value;
    const month = document.querySelector('[data-inp-month]').value;
    const month_date = year + '-' + month;

    const page = "/student/my/score/subject/select";
    const parameter = {
        'unit': unit,
        'month':month_date
    };
    queryFetch(page, parameter,function(result){
        if((result.resultCode||'') == 'success'){
           const exam_results = result.exam_results;

            // 초기화
            evalClearMiddleSubjectList();

            const bundle = document.querySelector('[data-bundle="subject_grades"]');
            exam_results.forEach(function(exam_result){
                const row = bundle.querySelector(`[data-row="${exam_result.subject_seq}"]`);
                if(row == null) return;
                const this_month = exam_result.correct_count / exam_result.total_count * 100;
                const prev_month = exam_result.prev_correct_count / exam_result.prev_total_count * 100;
                const my_age = exam_result.my_age_correct_count / exam_result.my_age_total_count * 100;
                const up_scroe = getNaNZero(this_month) - getNaNZero(prev_month);
                const my_age_up_scroe = getNaNZero(this_month) - getNaNZero(my_age);
                // const my_age_up_scroe = -10;

                // row.querySelector('[data-subject-name]').textContent = exam_result.subject_name;
                row.querySelector('[data-exam-rate]').textContent = isNaN(this_month) ? ' - ' : this_month.toFixed(0);
                row.querySelector('[data-prev-exam-comparison]').textContent = isNaN(up_scroe) ? ' - ' : up_scroe.toFixed(0);
                row.querySelector('[data-my-age-exam-comparison]').textContent = isNaN(my_age_up_scroe) ? ' - ' : my_age_up_scroe.toFixed(0);


                // 지난달보다
                if(getNaNZero(up_scroe) > 0){
                    row.querySelector('[data-up-img="prev"]').hidden = false;
                    row.querySelector('[data-down-img="prev"]').hidden = true;
                }else if(getNaNZero(up_scroe) < 0){
                    row.querySelector('[data-up-img="prev"]').hidden = true;
                    row.querySelector('[data-down-img="prev"]').hidden = false;
                    row.querySelector('[data-prev-exam-comparison]').parentElement.classList.remove('text-danger');
                    row.querySelector('[data-prev-exam-comparison]').parentElement.classList.add('secondary-text-mian');
                }

                // 또래들보다
                if(getNaNZero(my_age_up_scroe) > 0){
                    row.querySelector('[data-up-img="myage"]').hidden = false;
                    row.querySelector('[data-down-img="myage"]').hidden = true;
                }else if(getNaNZero(my_age_up_scroe) < 0){
                    row.querySelector('[data-up-img="myage"]').hidden = true;
                    row.querySelector('[data-down-img="myage"]').hidden = false;
                    row.querySelector('[data-my-age-exam-comparison]').parentElement.classList.remove('text-danger');
                    row.querySelector('[data-my-age-exam-comparison]').parentElement.classList.add('secondary-text-mian');
                }

            });
            if(callback != undefined) callback();
            // 만약 과목별 성적이 활성화 되어있으면 클릭.
            if(document.querySelectorAll('[data-middel-scorecard].active').length > 0){
                document.querySelectorAll('[data-middel-scorecard].active')[0].click();
            }
        }else{}
    });
}

// 과목별 성적 초기화
function evalClearMiddleSubjectList(){
    const bundle = document.querySelector('[data-bundle="subject_grades"]');
    const rows = bundle.querySelectorAll('[data-row]');
    rows.forEach(function(row){
        row.querySelector('[data-exam-rate]').textContent = '-';
        row.querySelector('[data-prev-exam-comparison]').textContent = '-';
        row.querySelector('[data-my-age-exam-comparison]').textContent = '-';
        row.querySelector('[data-up-img="prev"]').hidden = true;
        row.querySelector('[data-down-img="prev"]').hidden = true;
        row.querySelector('[data-up-img="myage"]').hidden = true;
        row.querySelector('[data-down-img="myage"]').hidden = true;
        row.querySelector('[data-prev-exam-comparison]').parentElement.classList.remove('secondary-text-mian');
        row.querySelector('[data-prev-exam-comparison]').parentElement.classList.add('text-danger');
        row.querySelector('[data-my-age-exam-comparison]').parentElement.classList.remove('secondary-text-mian');
        row.querySelector('[data-my-age-exam-comparison]').parentElement.classList.add('text-danger');
    });
}

function getNaNZero(value){
    return isNaN(value) ? 0 : value;
}

// NOTE: 의문 1. 월별로 가져오는데 국어의 단원이 한개 밖에 없을지.(상단은 마치 하나만 가져오는 것처럼 되어있음.)
// 그러나 그래프들은 하단은 합산을 가정해서 기획한 듯.

// 영역별 성적 통계
function evalSubjectExamDetailSelect(){
    const active_el = document.querySelector('[data-bundle="subject_grades"] [data-middel-scorecard].active');
    const subject_seq = active_el.closest('[data-row]').dataset.row;
    const year = document.querySelector('[data-inp-year]').value;
    const month = document.querySelector('[data-inp-month]').value;
    const month_date = year + '-' + month;

    const page = "/parent/evaluation/subject/detail/select";
    const parameter = {
        month:month_date,
        subject_seq:subject_seq
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            const base_datas = result.base_data;
            const mine_datas = result.mine_data; // 자녀
            const myage_datas = result.myage_data; // 또래

            // 내용영역은 첫번째 키를 가져오는것으로 진행.
            const content_area_seq = mine_datas[0]?.content_area_seq;
            // 초기화
            evalClearSubjectExamDetailSelect();

            // 영역별 성적 통계 상단 변경.
            evalTopSubjectExamDetailSetting(base_datas, subject_seq, content_area_seq);

            const database = cognitive_area_codes;
            const sel_data = database[subject_seq];

            if(!sel_data) return;
            // 그래프 변경.
            evalGraphSubjectExamDetailSelect(subject_seq, mine_datas, myage_datas, sel_data);


            // 이미지 변경.
            getPolygonSelectPrev(sel_data.length, sel_data, mine_datas);
        }else{}
    });

}

// 영역별 성적 통계 초기화
function evalClearSubjectExamDetailSelect(){
    const main_bundle = document.querySelector('[data-div="subejct_detail_bottom"]');
    main_bundle.querySelector('[data-subject-img]').src = '';
    main_bundle.querySelector('[data-subject-img]').hidden = true;
    main_bundle.querySelector('[data-subject-lecture-name]').textContent = '';
    main_bundle.querySelector('[data-prev-exam-comparison]').textContent = '';
    main_bundle.querySelector('[data-up-img="prev"]').hidden = true;
    main_bundle.querySelector('[data-down-img="prev"]').hidden = true;
    main_bundle.querySelector('[data-my-age-exam-comparison]').textContent = '';
    main_bundle.querySelector('[data-up-img="myage"]').hidden = true;
    main_bundle.querySelector('[data-down-img="myage"]').hidden = true;
    main_bundle.querySelector('[data-exam-rate]').textContent = '';
    main_bundle.querySelector('[data-content-area-name]').textContent = '';

    // 그래프 초기화
    const bundle = document.querySelector('[data-bundle="area_statistics_2"]');
    const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
    bundle.innerHTML = '';
    bundle.appendChild(row_copy);

    // 폴리곤 초기화
    document.querySelectorAll('[data-polygon]').forEach(function(el){
        el.hidden = true;
    });

}

// 영역별 성적 통계 상단 변경
function evalTopSubjectExamDetailSetting(base_datas, subject_seq, content_area_seq){
    const content_areas = content_area_codes[subject_seq];
    const main_bundle = document.querySelector('[data-div="subejct_detail_bottom"]');
    const act_el = document.querySelector('[data-middel-scorecard].active');
    const subject_name = act_el.querySelector('[data-subject-name]').textContent;
    // 국어 [대단원평가] 3단원. 자신의 경험을 글로 써요.
    if(base_datas[0]?.exam_title)
        main_bundle.querySelector('[data-subject-lecture-name]').textContent = subject_name + ' ' + base_datas[0]?.exam_title + ' '; //+ base_datas[0]?.lecture_description;
    // 과목 이미지 넣기
    const img_src = act_el.querySelector('[data-img-src]').value;
    if(img_src){
        main_bundle.querySelector('[data-subject-img]').src = '/images/'+img_src+'.svg';
        main_bundle.querySelector('[data-subject-img]').hidden = false;
    }
    // 내용영역 넣기
    let content_area_name = '';
    content_areas?.forEach(function(content_area){
        if(content_area.id == content_area_seq){
            content_area_name = content_area.code_name;
        }
    });
    main_bundle.querySelector('[data-content-area-name]').textContent = content_area_name;
    // 지난~또래 act_el 과 똑같이 데이터 맞춤.
    main_bundle.querySelector('[data-prev-exam-comparison]').textContent = act_el.querySelector('[data-prev-exam-comparison]').textContent;
    main_bundle.querySelector('[data-prev-exam-comparison]').parentElement.className = act_el.querySelector('[data-prev-exam-comparison]').parentElement.className;
    main_bundle.querySelector('[data-up-img="prev"]').hidden = act_el.querySelector('[data-up-img="prev"]').hidden;
    main_bundle.querySelector('[data-down-img="prev"]').hidden = act_el.querySelector('[data-down-img="prev"]').hidden;
    main_bundle.querySelector('[data-my-age-exam-comparison]').textContent = act_el.querySelector('[data-my-age-exam-comparison]').textContent;
    main_bundle.querySelector('[data-my-age-exam-comparison]').parentElement.className = act_el.querySelector('[data-my-age-exam-comparison]').parentElement.className;
    main_bundle.querySelector('[data-up-img="myage"]').hidden = act_el.querySelector('[data-up-img="myage"]').hidden;
    main_bundle.querySelector('[data-down-img="myage"]').hidden = act_el.querySelector('[data-down-img="myage"]').hidden;
    main_bundle.querySelector('[data-exam-rate]').textContent = act_el.querySelector('[data-exam-rate]').textContent;
}

// 영역별 성적 통계 그래프 변경
function evalGraphSubjectExamDetailSelect(subject_seq, mine_datas, myage_datas, sel_data){

    // 그래프 초기화
    const bundle = document.querySelector('[data-bundle="area_statistics_2"]');
    const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
    bundle.innerHTML = '';
    bundle.appendChild(row_copy);

    sel_data.forEach(function(data, idx){
        const row = row_copy.cloneNode(true);
        row.hidden = false;
        row.querySelector('[data-btn-area-statistics-btm]').textContent = data.code_name;
        row.querySelector('[data-other-bar]').dataset.codeSeq = data.id;
        row.querySelector('[data-self-bar]').dataset.codeSeq = data.id;
        row.querySelector('[data-other-bar]').style.height = `0%`;
        row.querySelector('[data-self-bar]').style.height = `0%`;
        row.querySelector('[data-self-cnt]').textContent = '';
        bundle.appendChild(row);
    });

    mine_datas.forEach(function(mine_data){
        const rage = mine_data.correct_cnt / mine_data.total_cnt * 100;
        const element = bundle.querySelector(`[data-other-bar][data-code-seq="${mine_data.cognitive_area_seq}"]`);
        if(element)
        element.style.height = `${rage}%`;
    });
    myage_datas.forEach(function(myage_data){
        const rage = myage_data.correct_cnt / myage_data.total_cnt * 100;
        const element = bundle.querySelector(`[data-self-bar][data-code-seq="${myage_data.cognitive_area_seq}"]`);
        if(element){
            element.style.height = `${rage}%`;
            const row = element.closest('[data-row]');
            row.querySelector('[data-self-cnt]').textContent = myage_data.correct_cnt+'점';
        }
    });
}

// 다각형 그래프 이전 데이터 가져오기
function getPolygonSelectPrev(num, sel_data, mine_datas){
    // mine_datas 의 데이터를 가져와서 cognitive_area_seq 를 키로 배열을 재배치한다.
    const data2 = {};
    mine_datas.forEach(function(data){
        data2[data.cognitive_area_seq] = data;
    });
    sel_data.forEach(function(data){
       let rate = data2[data.id]?.correct_cnt / data2[data.id]?.total_cnt * 100;
        rate = getNaNZero(rate);
        data.rate = rate;
    });
    getPolygonSelect(num, sel_data);
}

// 오답노트 완료 미완료 가져오기.
function evalWrongNoteSelect(page_num){
    const now_act_data = document.querySelector('[data-wrong-btn-aide-tab].active').dataset.wrongBtnAideTab;
    let is_complete = 'Y';
    if(now_act_data == 'wrong_incomplete')
        is_complete = 'N';

    const serach_standard = document.querySelector('[data-select-standard]').value;
    const year = new Date().format('yyyy');
    const page = "/student/wrong/note/select";
    const parameter = {
        start_date:year+'-01-01',
        end_date:year+'-12-31',
        serach_standard:serach_standard,
        is_page:'Y',
        page:page_num
    }
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            // 초기화
            const bundle = document.querySelector('[data-bundle="tby_wrong"]');
            const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

            // 데이터 정렬
            // let complete_exams = result.complete_exams;
            const wrong_sld_seqs = result.wrong_sld_seqs;
            const wrong_cnts = result.wrong_cnts;
            const complete_exams = result.complete_exams_page;
            const no_complete_exams = result.no_complete_exams_page;

            // let complete_wrongs = [];
            // let incomplete_wrongs = [];
            // complete_exams.data.forEach(function(comlecture){
            //     if(wrong_sld_seqs.includes(comlecture.id)){
            //         incomplete_wrongs.push(comlecture);
            //         // complete_exams 에서 comlecture 삭제
            //         complete_exams = complete_exams.filter(function(el){
            //             return el.id != comlecture.id;
            //         });
            //
            //     }
            // });
            // complete_wrongs = complete_exams;

            let datas = null;
            if(is_complete == 'Y'){
                datas = complete_exams;
                document.querySelector('[data-hide="incomplete"]').hidden = true;
            }else if(is_complete == 'N'){
                datas = no_complete_exams;
                document.querySelector('[data-hide="incomplete"]').hidden = false;
            }
            document.querySelector('[data-wrong-total-cnt]').textContent = '총 '+datas.total+'개';
            if(datas.total == 0){
                document.querySelector('[data-wrong-total-cnt]').hidden = true;
                document.querySelector('[data-chg-after-text]').textContent = '없습니다.';
            }else{
                document.querySelector('[data-wrong-total-cnt]').hidden = false
                document.querySelector('[data-chg-after-text]').textContent = '있습니다.';
            }

            evalTablePaging(datas, '1');
            datas.data.forEach(function(data){
                const row = row_copy.cloneNode(true);
                row.dataset.row = 'clone';
                row.hidden = false;
                row.querySelector('[data-wrong-cnt]').textContent = (wrong_cnts[data.id]||0)+'문제';
                row.querySelector('[data-lecture-name]').textContent = data.exam_title;
                row.querySelector('[data-sbuject-name]').textContent = data.subject_name;
                row.querySelector('[data-datetim-limit]').textContent = data[`datetime`].substr(0,10) == data.today ? '오늘까지' : data[`datetime`].substr(0,10);
                row.querySelector('[data-student-seq]').value = data.student_seq;
                row.querySelector('[data-student-exam-seq]').value = data.id;
                row.querySelector('[data-exam-seq]').value = data.exam_seq;
                bundle.appendChild(row);
            });

        }else{}
    });
}

// 평가관리 첫 중간 정보
function evalExamTotalCntSelect(){
    const year = document.querySelector('[data-inp-year]').value;
    const month = document.querySelector('[data-inp-month]').value;
    const month_date = year + '-' + month;

    const page = "/parent/evaluation/exam/total/cnt/select";
    const parameter = {
        month:month_date,
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            const total_data = result.total_data;
            document.querySelector('[data-total-cnt]').textContent = total_data.total_cnt;
            document.querySelector('[data-total-correct-cnt]').textContent = total_data.correct_cnt||0;

            const my_total_cnt = result.my_total_cnt;
            document.querySelector('[data-my-total-exam-cnt]').textContent = my_total_cnt;
            const prev_total_exam_cnt = my_total_cnt - result.prev_my_total_cnt;
            document.querySelector('[data-prev-total-exam-cnt]').textContent = Math.abs(prev_total_exam_cnt);
            if(prev_total_exam_cnt > 0){
                document.querySelector('[data-prev-total-exam-cnt]').parentElement.classList.add('text-danger');
                document.querySelector('[data-prev-total-exam-cnt]').parentElement.classList.remove('secondary-text-mian');
                document.querySelector('[data-img-up="prev_exam"]').hidden = false;
                document.querySelector('[data-img-down="prev_exam"]').hidden = true;
            }else if(prev_total_exam_cnt < 0){
                document.querySelector('[data-prev-total-exam-cnt]').parentElement.classList.add('secondary-text-mian');
                document.querySelector('[data-prev-total-exam-cnt]').parentElement.classList.remove('text-danger');
                document.querySelector('[data-img-up="prev_exam"]').hidden = true;
                document.querySelector('[data-img-down="prev_exam"]').hidden = false;
            }
            const myage_total_cnt = my_total_cnt - result.myage_total_cnt;
            document.querySelector('[data-myage-total-exam-cnt]').textContent = Math.abs(myage_total_cnt);
            if(myage_total_cnt > 0){
                document.querySelector('[data-myage-total-exam-cnt]').parentElement.classList.add('text-danger');
                document.querySelector('[data-myage-total-exam-cnt]').parentElement.classList.remove('secondary-text-mian');
                document.querySelector('[data-img-up="myage_exam"]').hidden = false;
                document.querySelector('[data-img-down="myage_exam"]').hidden = true;
            }else if(myage_total_cnt < 0){
                document.querySelector('[data-myage-total-exam-cnt]').parentElement.classList.add('secondary-text-mian');
                document.querySelector('[data-myage-total-exam-cnt]').parentElement.classList.remove('text-danger');
                document.querySelector('[data-img-up="myage_exam"]').hidden = true;
                document.querySelector('[data-img-down="myage_exam"]').hidden = false;
            }


            const total_average = result.total_average;
            let total_rate = total_average.correct_cnt / total_average.total_cnt * 100;
            total_rate = getNaNZero(total_rate);
            document.querySelector('[data-my-total-exam-aver]').textContent = (total_rate||0).toFixed(0);

            const myage_average = result.myage_average;
            const myage_rate = total_rate - getNaNZero(myage_average.correct_cnt / myage_average.total_cnt * 100);
            document.querySelector('[data-myage-total-exam-aver]').textContent = Math.abs(myage_rate.toFixed(0)||0);
            if(myage_rate > 0){
                document.querySelector('[data-myage-total-exam-aver]').parentElement.classList.add('text-danger');
                document.querySelector('[data-myage-total-exam-aver]').parentElement.classList.remove('secondary-text-mian');
                document.querySelector('[data-img-up="myage_aver"]').hidden = false;
                document.querySelector('[data-img-down="myage_aver"]').hidden = true;
            }else if(myage_rate < 0){
                document.querySelector('[data-myage-total-exam-aver]').parentElement.classList.add('secondary-text-mian');
                document.querySelector('[data-myage-total-exam-aver]').parentElement.classList.remove('text-danger');
                document.querySelector('[data-img-up="myage_aver"]').hidden = true;
                document.querySelector('[data-img-down="myage_aver"]').hidden = false;
            }

            const prev_my_average = result.prev_my_average;
            const prev_rate = total_rate - getNaNZero(prev_my_average.correct_cnt / prev_my_average.total_cnt * 100);
            document.querySelector('[data-prev-total-exam-aver]').textContent = Math.abs(prev_rate.toFixed(0)||0);
            if(prev_rate > 0){
                document.querySelector('[data-prev-total-exam-aver]').parentElement.classList.add('text-danger');
                document.querySelector('[data-prev-total-exam-aver]').parentElement.classList.remove('secondary-text-mian');
                document.querySelector('[data-img-up="prev_aver"]').hidden = false;
                document.querySelector('[data-img-down="prev_aver"]').hidden = true;
            }else if(prev_rate < 0){
                document.querySelector('[data-prev-total-exam-aver]').parentElement.classList.add('secondary-text-mian');
                document.querySelector('[data-prev-total-exam-aver]').parentElement.classList.remove('text-danger');
                document.querySelector('[data-img-up="prev_aver"]').hidden = true;
                document.querySelector('[data-img-down="prev_aver"]').hidden = false;
            }

        }else{}
    });
}
// 과목별 성적 그래프
function evalSubjectSelect(){
   // TODO: 뭔가 단원이 있는데 어디서 설정하는지 알수없음.
    const evaluation_seq = document.querySelector('[data-evaluation-seq].active').dataset.evaluationSeq;
    const year = document.querySelector('[data-inp-year]').value;
    const month = document.querySelector('[data-inp-month]').value;
    const month_date = year + '-' + month;

    const page = "/student/my/score/subject/select";
    const parameter = {
        'evaluation_seq': evaluation_seq,
        'month':month_date,
    };
    queryFetch(page, parameter,function(result){
        if((result.resultCode||'') == 'success'){
            // 하단 과목 리스트
            evalBottomSubjectList(result.exam_results);

            // 초기화
            scoreClearGraph();
            const exam_results = result.exam_results;
            const bundle = document.querySelector('[data-bundle="study_time_by_subject"]');
            exam_results.forEach(function(exam){
                const subject_seq = exam.subject_seq;
                const row = bundle.querySelector('[data-row="'+subject_seq+'"]');
                if(row){
                    const this_month = exam.correct_count / exam.total_count * 100;
                    const prev_month = exam.prev_correct_count / exam.prev_total_count * 100;
                    const up_scroe = this_month - prev_month;
                    row.querySelector('[data-prev-month-up-rate]').textContent = isNaN(up_scroe) ? ' - ' : up_scroe.toFixed(2);
                    row.querySelector('[data-prev-month]').style.height = prev_month + '%';
                    row.querySelector('[data-this-month]').style.height = this_month + '%';
                }
            });
        }else{}
    });
}
// 그래프 초기화
function scoreClearGraph(){
    const bundle = document.querySelector('[data-bundle="study_time_by_subject"]');
    const rows = bundle.querySelectorAll('[data-row]');
    rows.forEach(function(row){
        row.querySelector('[data-prev-month-up-rate]').textContent = '-';
        row.querySelector('[data-prev-month]').style.height = '0%';
        row.querySelector('[data-this-month]').style.height = '0%';
    });
}

// 그래프 하단 과목 점수
function evalBottomSubjectList(exam_results){
    // 초기화
    const bundle = document.querySelector('[data-bundle="bottom_subjects"]');
    const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
    bundle.innerHTML = '';
    bundle.appendChild(row_copy);

    exam_results.forEach(function(exam_result){
        const row = row_copy.cloneNode(true);
        const this_month = exam_result.correct_count / exam_result.total_count * 100;
        const prev_month = exam_result.prev_correct_count / exam_result.prev_total_count * 100;
        const my_age = exam_result.my_age_correct_count / exam_result.my_age_total_count * 100;
        const up_scroe = getNaNZero(this_month) - getNaNZero(prev_month);
        const my_age_up_scroe = getNaNZero(this_month) - getNaNZero(my_age);
        // const my_age_up_scroe = -10;

        row.hidden = false;
        row.dataset.row = "clone";
        row.querySelector('[data-subject-name]').textContent = exam_result.subject_name;
        row.querySelector('[data-exam-rate]').textContent = isNaN(this_month) ? ' - ' : this_month.toFixed(0);
        row.querySelector('[data-prev-exam-comparison]').textContent = isNaN(up_scroe) ? ' - ' : up_scroe.toFixed(0);
        row.querySelector('[data-my-age-exam-comparison]').textContent = isNaN(my_age_up_scroe) ? ' - ' : my_age_up_scroe.toFixed(0);


        // 지난달보다
        if(getNaNZero(up_scroe) > 0){
            row.querySelector('[data-up-img="prev"]').hidden = false;
            row.querySelector('[data-down-img="prev"]').hidden = true;
        }else if(getNaNZero(up_scroe) < 0){
            row.querySelector('[data-up-img="prev"]').hidden = true;
            row.querySelector('[data-down-img="prev"]').hidden = false;
            row.querySelector('[data-prev-exam-comparison]').parentElement.classList.remove('text-danger');
            row.querySelector('[data-prev-exam-comparison]').parentElement.classList.add('secondary-text-mian');
        }

        // 또래들보다
        if(getNaNZero(my_age_up_scroe) > 0){
            row.querySelector('[data-up-img="myage"]').hidden = false;
            row.querySelector('[data-down-img="myage"]').hidden = true;
        }else if(getNaNZero(my_age_up_scroe) < 0){
            row.querySelector('[data-up-img="myage"]').hidden = true;
            row.querySelector('[data-down-img="myage"]').hidden = false;
            row.querySelector('[data-my-age-exam-comparison]').parentElement.classList.remove('text-danger');
            row.querySelector('[data-my-age-exam-comparison]').parentElement.classList.add('secondary-text-mian');
        }

        bundle.appendChild(row);
    });
}

// 선생님 말씀 불러오기
function evalMiddleTeacherTalk(){
    const year = document.querySelector('[data-inp-year]').value;
    const month = document.querySelector('[data-inp-month]').value;
    const month_date = year + '-' + month;

    const page = "/parent/evaluation/learningg/teacher/evaluation/select";
    const parameter = {
        month:month_date,
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            const teacher_evaluation = result.teacher_evaluation;
            let content = '';
            // TODO: 여러 평가가 있을수 있는데 같은 선생님이 아닐 수 있음. 수정.필요.
            let profile_img_path = '';
            teacher_evaluation.forEach(function(data, idx){
                if(idx != 0)
                    content += '<hr>';
                // data.log_date  별로 <hr> 로 구분해서 넣어준다.
                const log_date = data.log_date;
                const log_content = data.log_content;
                content += log_date + '<br>' + log_content ;
                profile_img_path = data.profile_img_path;

            });
            document.querySelector('[data-etc-contents]').innerHTML = content||'아직 내용이 없습니다.';
            if(profile_img_path) {
                document.querySelector('[data-profile-img-path]').src = '/storage/uploads/user_profile/teacher/'+profile_img_path;
            }

        }else{}
    });
}

// 오답노트 출력하기 전 정보 전송.
function evalWrongPrintPost(){
    // 오답노트의 정보를 가져온다.
    const sel_data = [];
    const bundle = document.querySelector('[data-bundle="tby_wrong"]');
    const chks = bundle.querySelectorAll('[data-row="clone"] .chk:checked');
    chks.forEach(function(el){
        const student_seq = el.closest('[data-row]').querySelector('[data-student-seq]').value;
        const student_exam_seq = el.closest('[data-row]').querySelector('[data-student-exam-seq]').value;
        const exam_seq = el.closest('[data-row]').querySelector('[data-exam-seq]').value;

        sel_data.push({
            student_seq:student_seq,
            student_exam_seq:student_exam_seq,
            exam_seq:exam_seq,
        });
    });

    // 선택된 오답노트가 없는데 있으면 미완료 한번 더 출력하기.
    if(sel_data.length == 0) {
        toast('선택된 항목이 없습니다.');
        return;
    }

    //wrong_incomplete, wrong_complete
    // 현재 지금 미완료인지 완료인지 확인.
    const is_complete = document.querySelector('[data-wrong-btn-aide-tab].active').dataset.wrongBtnAideTab;

    const page = "/parent/wrong/note/print";
    const parameter = {
        'sel_data':sel_data,
        'is_complete':is_complete,
    };

    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            // 오답토느 출력하기.
            evalWrongPrint(result, sel_data);
        }else{}
    });

}

// 오답노트 출력하기.
function evalWrongPrint(result, sel_data){
    const is_only_exam = document.querySelector('#only_exam_print').checked;
    const is_only_answer = document.querySelector('#only_answer_print').checked;
    const is_all_print = document.querySelector('#all_print').checked;
    if(!is_only_exam && !is_only_answer && !is_all_print){
        toast('먼저 체크를 진행해주세요.');
        return;
    }
    const print_info = result;

    var winpopup1 = window.open();
    winpopup1.document.open();
    // 오답노트 출력하기 style 적용.
    evalSetPrintStyle(winpopup1);
    // 오답노트 출력하기 문제 적용.
    sel_data.forEach(function(idx){
        const key = idx.student_exam_seq
        let str = '<h1>[[exam_title]]</h1>';
        const normal_str = evalGetPrintStr(print_info.normals[key], is_only_exam, is_only_answer);
        const similar_str = evalGetPrintStr(print_info.similars[key], is_only_exam, is_only_answer);
        const chlge_str = evalGetPrintStr(print_info.challenges[key], is_only_exam, is_only_answer);
        const chlge_sim_str = evalGetPrintStr(print_info.challenge_similars[key], is_only_exam, is_only_answer);
        if(normal_str[0]){str += normal_str[0]; str = str.replace('[[exam_title]]', normal_str[1]);}
        if(similar_str[0]){str += similar_str[0]; str = str.replace('[[exam_title]]', similar_str[1]);}
        if(chlge_str[0]){str += chlge_str[0]; str = str.replace('[[exam_title]]', chlge_str[1]);}
        if(chlge_sim_str[0]){str += chlge_sim_str[0]; str = str.replace('[[exam_title]]', chlge_sim_str[1]);}
        winpopup1.document.write(str);
    });

    winpopup1.document.write('</body></html>');
    winpopup1.document.close();
    winpopup1.print();
    winpopup1.close();
}

// 오답노트 들어갈 str 생성.
function evalGetPrintStr(datas, is_only_exam, is_only_answer){
    if(!datas) return ['', ''];
    let all_str = '';
    let exam_title = '';
    datas.forEach(function(item){
        exam_title = item.exam_title;
        str = `<div class="quiz-question">[[exam_type]] ) [[exam_num]].[[questions]]</div>
                    <div>[[question_file_path]]</div>
                    <div class="quiz-answer-item">[[samples1]]</div>
                    <div>[[sample_file_path1]]</div>
                    <div class="quiz-answer-item">[[samples2]]</div>
                    <div>[[sample_file_path2]]</div>
                    <div class="quiz-answer-item">[[samples3]]</div>
                    <div>[[sample_file_path3]]</div>
                    <div class="quiz-answer-item">[[samples4]]</div>
                    <div>[[sample_file_path4]]</div>
                    <div class="quiz-answer-item">[[samples5]]</div>
                    <div>[[sample_file_path5]]</div>
                    <div class="quiz-commentary-answer">[[answer]]</div>
                `;
        let exam_type = '';
        if(item.exam_type == 'normal'){
            exam_type = '기본';
        }else if(item.exam_type == 'similar'){
            exam_type = '유사';
        }else if(item.exam_type == 'challenge'){
            exam_type = '도전';
        }else if(item.exam_type == 'challenge_similar'){
            exam_type = '도전유사';
        }
        str = str.replace('[[exam_type]]', exam_type);
        str = str.replace('[[exam_num]]', item.exam_num);
        if(item.questions && !is_only_answer) str = str.replace('[[questions]]', item.questions);
        else str = str.replace('[[questions]]', '');
        let question_img = '';
        if(item.question_file_path && !is_only_answer){
            question_img = `<img src="${item.question_file_path}" width="100">`;
        }
        str = str.replace('[[question_file_path]]', question_img);
        if(item.samples.split(';')[0] && !is_only_answer) str = str.replace('[[samples1]]', '<span class="quiz-answer-item-num">1</span> '+item.samples.split(';')[0]);
        else str = str.replace('[[samples1]]', '');
        if(item.samples.split(';')[1] && !is_only_answer) str = str.replace('[[samples2]]', '<span class="quiz-answer-item-num">2</span> '+item.samples.split(';')[1]);
        else str = str.replace('[[samples2]]', '');
        if(item.samples.split(';')[2] && !is_only_answer) str = str.replace('[[samples3]]', '<span class="quiz-answer-item-num">3</span> '+item.samples.split(';')[2]);
        else str = str.replace('[[samples3]]', '');
        if(item.samples.split(';')[3] && !is_only_answer) str = str.replace('[[samples4]]', '<span class="quiz-answer-item-num">4</span> '+item.samples.split(';')[3]);
        else str = str.replace('[[samples4]]', '');
        if(item.samples.split(';')[4] && !is_only_answer) str = str.replace('[[samples5]]', '<span class="quiz-answer-item-num">5</span> '+item.samples.split(';')[4]);
        else str = str.replace('[[samples5]]', '');

        if(item.sample_file_path1 && !is_only_answer){
            str = str.replace('[[sample_file_path1]]', `<img src="${item.sample_file_path1}" width="100">`);
        } else{
            str = str.replace('[[sample_file_path1]]', '');
        }
        if(item.sample_file_path2 && !is_only_answer){
            str = str.replace('[[sample_file_path2]]', `<img src="${item.sample_file_path2}" width="100">`);
        } else{
            str = str.replace('[[sample_file_path2]]', '');
        }
        if(item.sample_file_path3 && !is_only_answer){
            str = str.replace('[[sample_file_path3]]', `<img src="${item.sample_file_path3}" width="100">`);
        } else{
            str = str.replace('[[sample_file_path3]]', '');
        }
        if(item.sample_file_path4 && !is_only_answer){
            str = str.replace('[[sample_file_path4]]', `<img src="${item.sample_file_path4}" width="100">`);
        } else{
            str = str.replace('[[sample_file_path4]]', '');
        }
        if(item.sample_file_path5 && !is_only_answer){
            str = str.replace('[[sample_file_path5]]', `<img src="${item.sample_file_path5}" width="100">`);
        } else{
            str = str.replace('[[sample_file_path5]]', '');
        }

        if(item.answer && !is_only_exam) str = str.replace('[[answer]]',
            `
                <span>정답</span>
                <p>${item.answer}번</p>
            `);
        else str = str.replace('[[answer]]', '');

        all_str += str;
    });
    return [all_str, exam_title];
}

// 오답노트 출력하기 style 적용.
function evalSetPrintStyle(winpopup1){
    winpopup1.document.write('<style>');
    winpopup1.document.write(`
     .quiz-answer-item-num {
        display: inline-flex;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #F9F9F9;
        align-items: center;
        justify-content: center;
        padding-right: 2px;
        padding-top: 2px;
    }
    .quiz-answer-item  {
        color: #999;
        font-size: 18px;
    }

    .quiz-question {
        padding: 10px 0;
        font-size: 18px;
        font-weight: 600;
    }

    .quiz-commentary-answer {
        display: flex;
        gap: 8px;
        padding: clamp(1px, 2vw, 20px) 0;
    }

    .quiz-commentary-answer span {
        display: inline-block;
        background: #FFF;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 20px;
        font-weight: 600;
    }

    .quiz-commentary-answer p {
        font-size: 20px;
        font-weight: 600;
        padding: 8px 20px;
        margin: 0 !important;
    }
    `);
    winpopup1.document.write('</style>');
}

// 페이징 클릭 처리.
function evalPageFunc(target, type){
        if(type == 'next'){
            const page_next = document.querySelector(`[data-page-next="${target}"]`);
            if(page_next.getAttribute("data-is-next") == '0') return;
            // data-page 의 마지막 page_num 의 innerText를 가져온다
            const last_page = document.querySelector(`[data-page="${target}"] .page_num:last-of-type`).innerText;
            const page = parseInt(last_page) + 1;
            if(target == "1")
                 evalWrongNoteSelect(page);
        }
        else if(type == 'prev'){
            // [data-page-first]  next tag 의 innerText를 가져온다
            const page_first = document.querySelector(`[data-page-first="${target}"]`);
            const page = page_first.innerText;
            if(page == 1) return;
            const page_num = page*1 -1;
            if(target == "1")
                 evalWrongNoteSelect(page);
        }
        else{
            if(target == "1")
                 evalWrongNoteSelect(type);
        }
}

// 페이징
function evalTablePaging(rData, target){
        if(!rData) return;
        const from = rData.from;
        const last_page = rData.last_page;
        const per_page = rData.per_page;
        const total = rData.total;
        const to = rData.to;
        const current_page = rData.current_page;
        const data = rData.data;
        //페이징 처리
        const notice_ul_page = document.querySelector(`[data-page='${target}']`);
        //prev button, next_button
        const page_prev = notice_ul_page.querySelector(`[data-page-prev='${target}']`);
        const page_next = notice_ul_page.querySelector(`[data-page-next='${target}']`);
        //페이징 처리를 위해 기존 페이지 삭제
        notice_ul_page.querySelectorAll(".page_num").forEach(element => {
            element.remove();
        });
        //#page_first 클론
        const page_first = document.querySelector(`[data-page-first='${target}']`);
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
            copy_page_first.removeAttribute("data-page-first");
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

        if(data.length != 0)
            notice_ul_page.hidden = false;
        else
            notice_ul_page.hidden = true;
}
$(document).on("mouseenter", "[data-self-bar]", function() {
    const self_bar = $(this).parent().find("[data-self-bar]");
    const other_bar = $(this).parent().find("[data-other-bar]");
    const bar_h = $(this).parent().height();
    var h = Math.max(self_bar.height(), other_bar.height());
    const $barHtml = $(this).closest('[data-row]').find('[data-div-mouseover]');
    $barHtml.stop(true, true).fadeIn(300);
    console.log(bar_h);
    $barHtml.css('top', (bar_h - h - $barHtml.height() - 20) + "px");
});

$(document).on("mouseleave", "[data-self-bar]", function() {
    $(this).closest('[data-row="copy"]').find("[data-div-mouseover]").stop(true, true).fadeOut(300);
});
</script>
@endsection
