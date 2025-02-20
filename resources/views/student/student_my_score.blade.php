@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '내성적표')

@section('add_css_js')
<link hrenf="{{ asset('css/reset.css?5') }}" rel="stylesheet">
@endsection

{{-- 학생 컨텐츠 --}}
@section('layout_coutent')
<style>
  ol,
  ul {
    list-style: none;
  }
  [data-bundle=] > div {
    border-radius: 10px;
    overflow: hidden;
  }

  [data-bundle="subject_grades"] [data-row].active > div {
    background: #FFC747;
  }
  [data-bundl="subject_grades"] [data-row].active h4{
    color: #fff;
  }
  [data-bundle="subject_grades"] [data-row].active .text-sb-20px.scale-text-gray_05{
    color: #fff;
  }

  [data-evaluation-seq].active .my-1.h-center{
    color: #fff;
  }

  [data-evaluation-seq].active .my-1.h-center{
    color: #fff;
  }

</style>
<div class="col mx-0 mb-3 pt-0 pt-xxl-5 row position-relative">
  <input type="hidden" value="{{ date('Y') }}" data-inp-year>
  <input type="hidden" value="{{ date('m') }}" data-inp-month>
    <input type="hidden" data-hanja-seq value="{{ $hanja_code->id }}">
  {{-- 상단 --}}
  <article class="py-1 py-xxl-5 px-0">
    <div class="row">
      <div class="col-auto pb-0 pb-xxl-4 mb-0 mb-xxl-3">
        <div class="h-center">
          <img src="{{ asset('images/my_score_icon.svg?1')}}" width="40">
          <span class="cfs-1 ps-2 fw-semibold align-middle">내 성적표</span>
        </div>
        <div class="pt-2" hidden>
          <span class="cfs-3 fw-medium">내 성적표에 관한 텍스트</span>
        </div>
      </div>
      {{-- <div class="col position-relative">
        <img src="{{ asset('images/character_my_score.svg') }}" class="bottom-0 end-0 position-absolute" width="232">
      </div> --}}
    </div>
  </article>
  {{-- 내 성적표 --}}
  <article>
    {{-- 년월 --}}
    {{-- 상단 날짜 선택 --}}
    <section class="row mx-0 modal-shadow-style p-2 p-xxl-3" data-top-date-tab>
      <span id="year_display" class="text-sb-28px col-3 all-center"></span>
      <div class="month-selector d-flex col pe-5">
        <button id="prev_btn" class="btn p-0" onclick="scoreManageMoveHalfYear(-1)">
          <img src="{{asset('images/calendar_arrow_left.svg')}}" width="32">
        </button>
        <div class="col">
          <div id="months_display" class="d-flex justify-content-between px-5"></div>
        </div>
        <button id="next_btn" class="btn p-0" onclick="scoreManageMoveHalfYear(1)">
          <img src="{{asset('images/calendar_arrow_right.svg')}}" width="32">
        </button>
      </div>
    </section>

    {{-- padding 52px --}}
    <div class="py-2 py-xxl-4"></div>

    {{-- 과목 --}}
    <section data-section="middle" class="d-none d-xxl-block">
      <div class="row mx-0">
        {{-- 잘하는 과목 --}}
        <div class="col-6 col-xxl-3 " style="background:#F3F6FF">
          <div class="row p-4 rounded-3">
            <div class="col">
              <span class="text-sb-20px scale-text-gray_05">잘하는 과목</span>
              <span class="d-block text-b-32px scale-text-black mt-2" data-subject-name="good">
                @php $function_code1 = ''; @endphp
                @if(!empty($middle_data['good_subject_exam']))
                @foreach($middle_data['good_subject_exam'] as $data)
                {{$data->subject_name}}
                @php $function_code1 = $data->function_code; @endphp
                @endforeach
                @endif
              </span>
            </div>
            <div class="col text-end">
              <img src="{{ asset('images/'.$function_code1.'.svg') }}" width="92" data-subject-img="good" onerror="this.onerror=null; this.src=''" alt="">
            </div>
          </div>
        </div>
        {{-- 못하는 과목 --}}
        <div class="col-6 col-xxl-3" style="background:#EAFCFF">
          <div class="row  p-4 rounded-3">
            <div class="col">
              <span class="text-sb-20px scale-text-gray_05">어려운 과목</span>
              <span class="d-block text-b-32px scale-text-black mt-2" data-subject-name="notgood">
                @php $function_code2 = ''; @endphp
                @if(!empty($middle_data['notgood_subject_exam']))
                @foreach($middle_data['notgood_subject_exam'] as $data)
                {{$data->subject_name}}
                @php $function_code2 = $data->function_code; @endphp
                @endforeach
                @endif
              </span>
            </div>
            <div class="col text-end">
              <img src="{{ asset('images/'.$function_code2.'.svg') }}" width="92" data-subject-img="notgood" onerror="this.onerror=null; this.src=''" alt="">
            </div>
          </div>
        </div>
        {{-- 점수가 오른 과목 --}}
        <div class="col-6 col-xxl-3 " style="background:#FFF8EA">
          <div class="row p-4 rounded-3">
            <div class="col">
              <span class="text-sb-20px scale-text-gray_05">점수가 오른 과목</span>
              <span class="d-block text-b-32px scale-text-black mt-2" data-subject-name="best">
                @php $function_code3 = ''; @endphp
                @if(!empty($middle_data['best_exam']))
                {{$middle_data['best_exam']->subject_name}}
                @php $function_code3 = $middle_data['best_exam']->function_code; @endphp
                @endif
              </span>
            </div>
            <div class="col text-end">
              <img src="{{ asset('images/'.$function_code3.'.svg') }}" width="92" data-subject-img="best" onerror="this.onerror=null; this.src=''" alt="">
            </div>
          </div>
        </div>
        {{-- 점수가 떨어진 과목 --}}
        <div class="col-6 col-xxl-3" style="background:#FFF8EA">
          <div class="row p-4 rounded-3">
            <div class="col">
              <span class="text-sb-20px scale-text-gray_05">점수가 떨어진 과목</span>
              <span class="d-block text-b-32px scale-text-black mt-2" data-subject-name="worst">
                @php $function_code4 = ''; @endphp
                @if(!empty($middle_data['worst_exam']))
                {{$middle_data['worst_exam']->subject_name}}
                @php $function_code4 = $middle_data['worst_exam']->function_code; @endphp
                @endif
              </span>
            </div>
            <div class="col text-end">
              <img src="{{ asset('images/'.$function_code4.'.svg') }}" width="92" data-subject-img="worst" onerror="this.onerror=null; this.src=''" alt="">
            </div>
          </div>
        </div>
      </div>
    </section>



    {{-- 과목별 성적 --}}
    <section>
      <h5 class="d-flex align-items-center gap-2 py-2 py-xxl-5">
        <img src="{{ asset('images/my_score_icon.svg') }}" width="32" class=" p-1">
        <span class="text-b-32px scale-text-black">과목별 성적</span>
      </h5>
      {{-- 단원 목록 --}}
      <main class="row">
        <aside class="col-lg-3">
          <ul class="tab py-4 px-3 modal-shadow-style pb-3 d-flex flex-column gap-2" id="my_score_ul_unit">
            @if(!empty($evaluation_codes))
            @foreach($evaluation_codes as $index => $evaluation_code)
            @if($evaluation_code->is_use == 'Y')

            <li class="mb-0">
                <button data-evaluation-seq="{{$evaluation_code->id}}" data-function-code="{{$evaluation_code->function_code}}"
                    class="btn w-100 btn-outline-primary-y border-0 text-start text-b-20px py-3 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover {{$index == 0 ? 'active' : ''}}"
                    onclick="myScoreUnitClick(this)">
                <div class="my-1 h-center">
                  <img src="{{ asset('images/danwon_test_icon.svg') }}" class="m-2">
                  {{$evaluation_code->code_name}}
                </div>
              </button>
            </li>
            @endif
            @endforeach
            @endif
          </ul>
        </aside>
        <section class="col" data-tab="0">
          {{-- 성적 그래프 --}}
          <div class="modal-shadow-style p-5 d-none">
            {{-- padding 130px --}}
            <div style="height: 82px;"></div>

            <div class="m-1 row" style="height:250px;">
              <div class="col-auto m-0 d-flex flex-column position-relative">
                <div class="col mb-2 position-absolute d-flex flex-column" style="bottom:4px;height:250px;">
                  <div class="col" style="">100</div>
                  <div class="col" style="">80</div>
                  <div class="col" style="">60</div>
                  <div class="col" style="">40</div>
                  <div class="col" style="">20</div>
                </div>
                <div class="position-absolute " style="bottom:-10px">0</div>
              </div>
              <div class="col position-relative">
                <div class=" d-flex flex-column ms-4 h-100">
                  <div class="col" style="border-bottom:1px solid #E5E5E5;border-top:1px solid #E5E5E5"></div>
                  <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                  <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                  <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                  <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                </div>
                <div data-bundle="graph" class="row mx-0 position-absolute top-0 bottom-0 start-0 end-0 ms-4">
                  <div class="col"></div>
                  @if(!empty($subject_codes))
                  @foreach($subject_codes as $idx => $subject_code)
                  <div class="col row gap-2 align-items-end justify-content-center position-relative" data-row="{{$subject_code->id}}">
                    <div data-div-graph-top class="position-absolute text-center px-0" style="top: -73px" {{ $idx == 0?'':'hidden' }}>
                      <span class="text-white text-b-20px rounded-3" style="background: #FFC747;padding:12px 20px;">
                        <span data-prev-month-up-rate> - </span>점 상승
                      </span>
                      <div class="position-relative">
                        <img src="{{ asset('images/yellow_arrow_down_icon.svg') }}" width="18" class="position-absolute" style="left: 43%;bottom:-18px">
                      </div>
                    </div>
                    <div data-prev-month class="col-auto rounded-top-3 scale-bg-gray_02 px-3" style="height:0%"> </div>
                    <div data-this-month class="col-auto rounded-top-3 px-3 ms-1" style="height:0%;background:#FFC747"> </div>
                    <div class="position-absolute text-center px-0" style="bottom:-62px;">
                      <button onclick="scoreClickGraphOne(this)" data-btn-subject-name class="btn btn-outline-primary-y border-0 rounded-pill text-sb-20px scale-text-gray_05 scale-text-white-hover {{ $idx == 0?'active':'' }}" style="padding:4px 16px">
                        {{ $subject_code->code_name}}
                      </button>
                    </div>
                  </div>
                  @endforeach
                  @endif
                  <div class="col"></div>
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

          {{-- 하단 성적 상세 --}}
            <div class="row row-cols-3 mt-3 mt-xxl-0" data-bundle="subject_grades">
                @if (!empty($subject_codes))
                @foreach ($subject_codes as $idx => $subject_code)
                    {{--  한자는 제외 이후 추가 사항 이므로 하드코딩으로 진행. --}}
                        @php
                        if(strpos($subject_code->code_name, '한자') !== false){
                            continue;
                        }
                        @endphp
                <div class="col mt-0 mb-3" data-row="{{$subject_code->id}}" >
                    <input type="hidden" value="{{$subject_code->id}}">
                    <div data-middel-scorecard
                        class="modal-shadow-style p-4 cursor-pointer primary-bg-mian-hover rounded" onclick="clickGradesBySubject(this)">
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
          <div class="col mt-2 mt-xxl-4">
            <div class="row col-12 rounded-4 shadow-sm-2 bg-white mx-0" id="teachmess_div_mes_head">
              <div class="row m-0 py-0 px-3 text-secondary" style="min-height: 80px">
                <div class="col-4 row m-0 p-0">
                  <div data-div-messge-sender="" class="col-6 d-flex align-items-center text-b-20px scale-text-gray_05 justify-content-center">학습일</div>
                  <div class="col-6 d-flex justify-content-center flex-column">
                    <div class="text-center text-b-20px scale-text-gray_05 primary-text-mian">
                      과목
                    </div>
                  </div>
                </div>
                <div class="col-8 row m-0 p-0 align-items-center">
                  <span class="col-8 text-center text-b-20px scale-text-gray_05">평가명</span>
                  <span class="col-2 text-center text-b-20px scale-text-gray_05">점수</span>
                  <span class="col-2 text-center text-b-20px scale-text-gray_05">현황</span>
                </div>
              </div>
            </div>
            <div data-bundle="subject_plan_list">
                <div class="row col-12 mx-0 border-bottom" id="" data-row="copy" hidden>
                    <div class="row m-0 py-0 px-3 cursor-pointer text-secondary mes_list" onclick="teachMessModalReadAndAnswerOpen(this)" style="min-height: 80px;">
                        <input type="hidden" data-st-lecture-detail-seq>
                        <div class="col-4 row m-0 p-0">
                            <div class="col-6 d-flex align-items-center justify-content-center gap-2">
                                <div class="col ps-1">
                                    <div class="fw-bold text-black pb-1 text-center">
                                        <span class="fw-bold user_name text-sb-20px scale-text-gray_05" data="#학습일" data-sel-date></span>
                                        <span class="user_type" data="#타입"></span>
                                    </div>
                                    <div><span class="student_grade text-sb-18px scale-text-gray_05" data="#3학년"></span></div>
                                </div>
                            </div>
                            <div class="col-6 p-1 d-flex align-items-center">
                                <div class="col text-center">
                                    <span class="text-white px-3 py-2 rounded-4 fs-6 status text-b-16px bg-primary-y" data="#과목" data-subject-name></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-8 row m-0 p-0 align-items-center">
                            <div class="col-8 text-sb-20px scale-text-gray_05 created_at" data="#등록날짜">
                                <div class="d-flex align-items-center">
                                    <img src="" alt="" width="77" data-subject-img onerror="this.onerror=null; this.src=''">
                                    <div class="text-sb-20px d-flex flex-column">
                                        <p data-lecture-detail-name class="text-b-20px mb-2"></p>
                                        <p data-lecture-detail-description class="text-sb-18px scale-text-gray_05"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 text-center">
                                <div class="message text-sb-20px scale-text-gray_05" data="#메시지" data-exam-complete-persent>-</div>
                            </div>
                            <div class="col-2 text-center">
                                <button data-button-exam type="button" class="text-white px-3 py-2 rounded-4 fs-6 status text-b-16px bg-primary-y border-0" hidden onclick="myScorePlayVido(this)">학습하기</button>
                                <button data-button-complete type="button" class="text-white px-3 py-2 rounded-4 fs-6 status text-b-16px studyColor-bg-goalTime border-0"  hidden>학습완료</button>
                                <button data-button-nocomplete type="button" class="scale-text-gray_05 px-3 py-2 rounded-4 fs-6 status text-b-16px border-0" hidden>미완료</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </section>

        <section class="col" data-tab="1" hidden>
            <div class="col">
            <div class="row col-12 rounded-4 shadow-sm-2 bg-white mx-0" id="teachmess_div_mes_head">
              <div class="row m-0 py-0 px-3 text-secondary" style="min-height: 80px">
                <div class="col-4 row m-0 p-0">
                  <div data-div-messge-sender="" class="col-6 d-flex align-items-center text-b-20px scale-text-gray_05 justify-content-center">학습일</div>
                  <div class="col-6 d-flex justify-content-center flex-column">
                    <div class="text-center text-b-20px scale-text-gray_05 primary-text-mian">
                      급수
                    </div>
                  </div>
                </div>
                <div class="col-8 row m-0 p-0 align-items-center">
                  <span class="col-8 text-center text-b-20px scale-text-gray_05">평가명</span>
                  <span class="col-2 text-center text-b-20px scale-text-gray_05">점수</span>
                  <span class="col-2 text-center text-b-20px scale-text-gray_05">현황</span>

                </div>
              </div>
            </div>

            <div data-bundle="subject_plan_list">
                <div class="row col-12 mx-0 border-bottom" id="" data-row="copy" hidden>
                    <div class="row m-0 py-0 px-3 cursor-pointer text-secondary mes_list" onclick="teachMessModalReadAndAnswerOpen(this)" style="min-height: 80px;">
                        <input type="hidden" data-st-lecture-detail-seq>
                        <div class="col-4 row m-0 p-0">
                            <div class="col-6 d-flex align-items-center justify-content-center gap-2">
                                <div class="col ps-1">
                                    <div class="fw-bold text-black pb-1 text-center">
                                        <span class="fw-bold user_name text-sb-20px scale-text-gray_05" data="#학습일" data-sel-date></span>
                                        <span class="user_type" data="#타입"></span>
                                    </div>
                                    <div><span class="student_grade text-sb-18px scale-text-gray_05" data="#3학년"></span></div>
                                </div>
                            </div>
                            <div class="col-6 p-1 d-flex align-items-center">
                                <div class="col text-center">
                                    <span class="text-white px-3 py-2 rounded-4 fs-6 status text-b-16px bg-primary-y" data="#과목" data-subject-name></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-8 row m-0 p-0 align-items-center">
                            <div class="col-8 text-sb-20px scale-text-gray_05 created_at" data="#등록날짜">
                                <div class="d-flex align-items-center">
                                    <img src="" alt="" width="77" data-subject-img onerror="this.onerror=null; this.src=''">
                                    <div class="text-sb-20px d-flex flex-column">
                                        <p data-lecture-detail-name class="text-b-20px mb-2"></p>
                                        <p data-lecture-detail-description class="text-sb-18px scale-text-gray_05"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-2 text-center">
                                <div class="message text-sb-20px scale-text-gray_05" data="#메시지" data-exam-complete-persent>-</div>
                            </div>
                            <div class="col-2 text-center">
                                <button data-button-exam type="button" class="text-white px-3 py-2 rounded-4 fs-6 status text-b-16px bg-primary-y border-0" hidden onclick="myScorePlayVido(this)">학습하기</button>
                                <button data-button-complete type="button" class="text-white px-3 py-2 rounded-4 fs-6 status text-b-16px studyColor-bg-goalTime border-0"  hidden>학습완료</button>
                                <button data-button-nocomplete type="button" class="scale-text-gray_05 px-3 py-2 rounded-4 fs-6 status text-b-16px border-0" hidden>미완료</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
            </section>
      </main>
</div>
</section>
</article>

{{-- padding 160px --}}
<div style="height: 160px;"></div>
</div>
<!-- 학습하기  -->
<form method="POST" action="/student/study/video" data-form="my_score_study_video" hidden>
    @csrf
    <input name="st_lecture_detail_seq" />
    <input name="prev_page" value="my_score" />
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
  const middle_data = @json($middle_data);
  document.addEventListener('DOMContentLoaded', function() {
    myScoreInit();
  });


  // init
  function myScoreInit() {
    // 상단 날짜 활성화
    scoreManageUpdateDisplay();
    // scoreMiddleSubjectSelect();
    // 과목별 성적 그래프
    // document.querySelector('[data-evaluation-seq]').click();
    // scoreSubjectSelect();
      //
    myScoreMiddleSubjectList();
  }


function scoreManageMoveHalfYear(direction) {
    // 화면 크기에 따라 이동 단위 결정
    let moveUnit = window.innerWidth >= 1400 ? 6 : 4;
    currentMonth += direction * moveUnit;

    if (currentMonth > 11) {
        currentMonth -= 12;
        currentYear++;
    } else if (currentMonth < 0) {
        currentMonth += 12;
        currentYear--;
    }

    // 화면 크기에 따라 단위 맞추기
    if (window.innerWidth >= 1400) {
        // 1400px 이상일 때는 6개월 단위
        if (currentMonth < 6) {
            currentMonth = 0;
        } else {
            currentMonth = 6;
        }
    } else {
        // 1400px 미만일 때는 4개월 단위
        if (currentMonth < 4) {
            currentMonth = 0;
        } else if (currentMonth < 8) {
            currentMonth = 4;
        } else {
            currentMonth = 8;
        }
    }

    scoreManageUpdateDisplay(direction);
}

  let currentDate = new Date();
  let currentYear = currentDate.getFullYear();
  let currentMonth = currentDate.getMonth();
  const months = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'];

function scoreManageUpdateDisplay(direction) {
    const year_display = document.getElementById('year_display');
    const months_display = document.getElementById('months_display');
    let startMonthPlus;
    year_display.textContent = `${currentYear}년`;
    months_display.innerHTML = '';

    // 화면 크기에 따라 4개월 또는 6개월씩 보이도록 수정
    let startMonth;
    if (window.innerWidth >= 1400) {
        // 1400px 이상일 때는 6개월씩
        startMonth = currentMonth < 6 ? 0 : 6;
        startMonthPlus = 6;
    } else {

        // 1400px 미만일 때는 4개월씩
        if (currentMonth < 4) {
            startMonth = 0; // 1~4월
            startMonthPlus = 4;
        } else if (currentMonth < 8) {
            startMonth = 4; // 5~8월
            startMonthPlus = 4;
        } else {
            startMonth = 8; // 9~12월
            startMonthPlus = 4;
        }
    }

    // 4개월만 표시
    for (let i = startMonth; i < startMonth + startMonthPlus; i++) {
        const monthElem = document.createElement('button');
        monthElem.textContent = months[i];
        monthElem.classList.add('month', 'btn', 'btn-outline-primary-y', 'ctext-gc1', 'border-0', 'rounded-pill', 'cfs-5', 'p-2', 'px-4');
        monthElem.setAttribute('onclick', 'scoreManageSelectMonth(this)');
        if (i === currentMonth) {
            monthElem.classList.add('active');
            scoreManageSelectMonth(monthElem);
        }
        months_display.appendChild(monthElem);
    }

    if (direction == -1) {
        months_display.querySelectorAll('button').forEach(function(el) {
            el.classList.remove('active');
        });
        months_display.querySelectorAll('button')[3].classList.add('active');
    } else if (direction == 1) {
        months_display.querySelectorAll('button').forEach(function(el) {
            el.classList.remove('active');
        });
        months_display.querySelectorAll('button')[0].classList.add('active');
        scoreManageSelectMonth(months_display.querySelectorAll('button')[0]);
    }
}
  // 달 선택
  function scoreManageSelectMonth(vthis) {
    const months_display = document.getElementById('months_display');
    months_display.querySelectorAll('button').forEach(function(el) {
      el.classList.remove('active');
    });
    vthis.classList.add('active');
    // inp 에 활성화 되어있는 년월 넣기
    const year = document.querySelector('#year_display').textContent.replace('년', '');
    const month = vthis.textContent.replace('월', '');
    document.querySelector('[data-inp-year]').value = year;
    document.querySelector('[data-inp-month]').value = month;
    //  현재 달의 내용들 가져오기
    scoreMiddleSubjectSelect();
    // 과목별 성적 그래프
    // scoreSubjectSelect();
    // 과목별 성적
    myScoreMiddleSubjectList();
  }

  // 중간 잘하는~ 점수가 떨어진 과목 비동기
  function scoreMiddleSubjectSelect() {
    const year = document.querySelector('[data-inp-year]').value;
    const month = document.querySelector('[data-inp-month]').value;
    const month_date = year + '-' + month;

    const page = "/student/my/score/subject/good/not/select";
    const parameter = {
      'month': month_date
    };
    queryFetch(page, parameter, function(result) {
      if ((result.resultCode || '') == 'success') {
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

        if (good_subject_exam?.subject_name)
          good_name_el.textContent = good_subject_exam.subject_name;
        else
          good_name_el.textContent = '-';
        if (good_subject_exam?.function_code) {
          good_img_el.src = '/images/' + good_subject_exam.function_code + '.svg';
          good_img_el.hidden = false;
        } else {
          good_img_el.hidden = true;
        }
        if (notgood_subject_exam?.subject_name)
          notgood_name_el.textContent = notgood_subject_exam.subject_name;
        else
          notgood_name_el.textContent = '-';
        if (notgood_subject_exam?.function_code) {
          notgood_img_el.src = '/images/' + notgood_subject_exam.function_code + '.svg';
          notgood_img_el.hidden = false;
        } else {
          notgood_img_el.hidden = true;
        }

        if (best_exam?.subject_name)
          best_name_el.textContent = best_exam.subject_name;
        else
          best_name_el.textContent = '-';
        if (best_exam?.function_code) {
          best_img_el.src = '/images/' + best_exam.function_code + '.svg';
          best_img_el.hidden = false;
        } else {
          best_img_el.hidden = true;
        }
        if (worst_exam?.subject_name)
          worst_name_el.textContent = worst_exam.subject_name;
        else
          worst_name_el.textContent = '-';
        if (worst_exam?.function_code) {
          worst_imb_el.src = '/images/' + worst_exam.function_code + '.svg';
          worst_imb_el.hidden = false;
        } else {
          worst_imb_el.hidden = true;
        }

      } else {}
    });
  }


  // 과목별 성적 그래프
  function scoreSubjectSelect() {
    // TODO: 뭔가 단원이 있는데 어디서 설정하는지 알수없음.
    const unit = '';
    const evaluation_seq = document.querySelector('[data-evaluation-seq].active').dataset.evaluationSeq;
    const year = document.querySelector('[data-inp-year]').value;
    const month = document.querySelector('[data-inp-month]').value;
    const month_date = year + '-' + month;

    const page = "/student/my/score/subject/select";
    const parameter = {
      'unit': unit
      , 'evaluation_seq': evaluation_seq
      , 'month': month_date
    };
    queryFetch(page, parameter, function(result) {
      if ((result.resultCode || '') == 'success') {
        // 하단 과목 리스트
        // scoreBottomSubjectList(result.exam_results);

        // 초기화
        scoreClearGraph();
        const exam_results = result.exam_results;
        const bundle = document.querySelector('[data-bundle="graph"]');
        exam_results.forEach(function(exam) {
          const subject_seq = exam.subject_seq;
          const row = bundle.querySelector('[data-row="' + subject_seq + '"]');
          if (row) {
            const this_month = exam.correct_count / exam.total_count * 100;
            const prev_month = exam.prev_correct_count / exam.prev_total_count * 100;
            const up_scroe = this_month - prev_month;
            row.querySelector('[data-prev-month-up-rate]').textContent = isNaN(up_scroe)?' - ' : up_scroe.toFixed(2);
            row.querySelector('[data-prev-month]').style.height = prev_month + '%';
            row.querySelector('[data-this-month]').style.height = this_month + '%';
          }
        });
      } else {}
    });
  }

  // 그래프 초기화
  function scoreClearGraph() {
    const bundle = document.querySelector('[data-bundle="graph"]');
    const rows = bundle.querySelectorAll('[data-row]');
    rows.forEach(function(row) {
      row.querySelector('[data-prev-month-up-rate]').textContent = '-';
      row.querySelector('[data-prev-month]').style.height = '0%';
      row.querySelector('[data-this-month]').style.height = '0%';
    });
  }


  // 그래프 과목 이름 클릭
  function scoreClickGraphOne(vthis) {
    const row = vthis.closest('[data-row]');
    const bundle = document.querySelector('[data-bundle="graph"]');
    bundle.querySelectorAll('[data-row]').forEach(function(el) {
      el.querySelector('[data-div-graph-top]').hidden = true;
      el.querySelector('[data-btn-subject-name]').classList.remove('active');
    });
    row.querySelector('[data-div-graph-top]').hidden = false;
    row.querySelector('[data-btn-subject-name]').classList.add('active');
  }


  // 그래프 하단 과목 점수
  function scoreBottomSubjectList(exam_results) {
    // 초기화
    const bundle = document.querySelector('[data-bundle="bottom_subjects"]');
    const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
    bundle.innerHTML = '';
    bundle.appendChild(row_copy);

    exam_results.forEach(function(exam_result) {
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
      row.querySelector('[data-exam-rate]').textContent = isNaN(this_month)?' - ' : this_month.toFixed(0);
      row.querySelector('[data-prev-exam-comparison]').textContent = isNaN(up_scroe)?' - ' : up_scroe.toFixed(0);
      row.querySelector('[data-my-age-exam-comparison]').textContent = isNaN(my_age_up_scroe)?' - ' : my_age_up_scroe.toFixed(0);


      // 지난달보다
      if (getNaNZero(up_scroe) > 0) {
        row.querySelector('[data-up-img="prev"]').hidden = false;
        row.querySelector('[data-down-img="prev"]').hidden = true;
      } else if (getNaNZero(up_scroe) < 0) {
        row.querySelector('[data-up-img="prev"]').hidden = true;
        row.querySelector('[data-down-img="prev"]').hidden = false;
        row.querySelector('[data-prev-exam-comparison]').parentElement.classList.remove('text-danger');
        row.querySelector('[data-prev-exam-comparison]').parentElement.classList.add('secondary-text-mian');
      }

      // 또래들보다
      if (getNaNZero(my_age_up_scroe) > 0) {
        row.querySelector('[data-up-img="myage"]').hidden = false;
        row.querySelector('[data-down-img="myage"]').hidden = true;
      } else if (getNaNZero(my_age_up_scroe) < 0) {
        row.querySelector('[data-up-img="myage"]').hidden = true;
        row.querySelector('[data-down-img="myage"]').hidden = false;
        row.querySelector('[data-my-age-exam-comparison]').parentElement.classList.remove('text-danger');
        row.querySelector('[data-my-age-exam-comparison]').parentElement.classList.add('secondary-text-mian');
      }

      bundle.appendChild(row);
    });
  }

  function getNaNZero(value) {
    return isNaN(value)?0 : value;
  }

  // 과목별 성적 aside 클릭.
  function myScoreUnitClick(vthis) {
    document.querySelectorAll('[data-evaluation-seq]').forEach(function(el) {
      el.classList.remove('active');
    });
    vthis.classList.add('active');
    const function_code = vthis.dataset.functionCode;
        const data_tabs = document.querySelectorAll('[data-tab]');
      data_tabs.forEach(function(item, index) {
        item.hidden = true;
      });

      if(function_code == 'evaluation'){
        // 단원평가
          document.querySelector('[data-tab="0"]').hidden = false;

      }
      else if(function_code == 'hanja_exam'){
        // 한자급수시험
          document.querySelector('[data-tab="1"]').hidden = false;
          const hanja_seq = document.querySelector('[data-hanja-seq]').value;
          myScoreGetSubjectLearnPlanList(hanja_seq, 1 );
      }

  }

// 과목별 점수
function myScoreMiddleSubjectList(callback){
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
            myScoreClearMiddleSubjectList();

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
        // 활성화 되어있는 과목 불러오기.
        myScoreActiveSubjectList();
        // 과목 점수가 없을때, 끝으로이동.
        myScoreNoneSubjectMoveLeft();
    });
}

// 과목별 성적 초기화
function myScoreClearMiddleSubjectList(){
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

// 과목별 성적 성적 클릭.
function clickGradesBySubject(vthis){
    const pt_bundle = vthis.closest('[data-bundle="subject_grades"]');
    pt_bundle.querySelectorAll('[data-row]').forEach(( row )=>{
        row.classList.remove('active');
    });
    const data_row = vthis.closest('[data-row]');
    data_row.classList.add('active');

      const subject_seq = data_row.dataset.row;
    myScoreGetSubjectLearnPlanList(subject_seq);

}

// 과목별 학습플랜 리스트 가져오기.
function myScoreGetSubjectLearnPlanList(subject_seq, tab_index, no_unittest){
  if(tab_index == undefined){
    tab_index = 0;
  }
    const year = document.querySelector('[data-inp-year]').value;
    const month = document.querySelector('[data-inp-month]').value;
    const sel_month = year + '-' + month;
    const page = "/student/my/score/subject/learn_plan/list";
    const parameter = {
          'subject_seq': subject_seq,
          'sel_month': sel_month,
          'no_unittest': no_unittest
    };
      queryFetch(page, parameter, function(result) {
        if((result.resultCode||'') == 'success'){
            const bundle = document.querySelector('[data-tab="'+tab_index+'"] [data-bundle="subject_plan_list"]');
            const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);
            const code = result.code;
            const student_exams = result.student_exams;
            const subject2_codes = result.subject2_codes;
            result.lecture_details.forEach(function(detail){
                  const row = bundle.querySelector('[data-row="copy"]').cloneNode(true);
                  const student_lecture_detail_seq = detail.id;

                  row.dataset.row = "clone";
                  row.hidden = false;
                  row.querySelector('[data-st-lecture-detail-seq]').value = student_lecture_detail_seq;
                  row.querySelector('[data-subject-name]').textContent = code.code_name;
                  // 1이면 한자이므로, 급수를 넣어준다.
                  if(tab_index == 1){
                    row.querySelector('[data-subject-name]').textContent = subject2_codes[detail.id]?.code_name||'';
                  }

                  row.querySelector('[data-sel-date]').textContent = detail.sel_date.substr(0,10).replace(/-/gi,'.');
                  row.querySelector('[data-subject-img]').src = '/images/' + code.function_code + '.svg';
                  row.querySelector('[data-lecture-detail-name]').textContent = detail.lecture_detail_name;
                  row.querySelector('[data-lecture-detail-description]').textContent = detail.lecture_detail_description;
                  if(detail.status == 'complete'){
                      row.querySelector('[data-button-exam]').remove();
                      row.querySelector('[data-button-complete]').hidden = false;
                      row.querySelector('[data-button-nocomplete]').remove();
                  }else{
                      // sel_date 가 오늘로 부터 3일 이하이면 미완료 버튼 보이기. 아니면 문제풀기 버튼으로 보이기.
                      const date3 = new Date();
                      date3.setDate(new Date().getDate() - 3);
                      if( detail.sel_date.substr(0,10) >= date3.format('yyyy-MM-dd')){
                                row.querySelector('[data-button-exam]').hidden = false;
                                row.querySelector('[data-button-complete]').remove();
                                row.querySelector('[data-button-nocomplete]').remove();
                      }else{
                                row.querySelector('[data-button-exam]').remove();
                                row.querySelector('[data-button-complete]').remove();
                                row.querySelector('[data-button-nocomplete]').hidden = false;
                      }
                  }
                  if(student_exams[student_lecture_detail_seq]){
                      const complete_persent = (student_exams[student_lecture_detail_seq].correct_cnt / student_exams[student_lecture_detail_seq].all_cnt) * 100;
                      row.querySelector('[data-exam-complete-persent]').textContent = Math.floor(complete_persent) + '점';
                  }

                bundle.appendChild(row);
            });
        }else{}
    });
}

//학습하기 버튼 클릭
function myScorePlayVido(vthis) {
    const pt_row = vthis.closest('[data-row]');
    const st_lecture_detail_seq = pt_row.querySelector('[data-st-lecture-detail-seq]').value;
    const form = document.querySelector('[data-form="my_score_study_video"]');
    form.querySelector('input[name="st_lecture_detail_seq"]').value = st_lecture_detail_seq;
    rememberScreenOnSubmit(true);
    form.submit();
}

// 활성화 되어있는 과목 불러오기.
  function myScoreActiveSubjectList(){
    const active_tag = document.querySelectorAll('[data-function-code].active');
      if(active_tag.length > 0){
          // 단원평가가 활성화 되어있는지.
          if(active_tag[0].dataset.functionCode == 'evaluation'){
              if(document.querySelectorAll('[data-bundle="subject_grades"] [data-row].active').length > 0){
                  document.querySelectorAll('[data-bundle="subject_grades"] [data-row].active [data-middel-scorecard]')[0].click();
              }
          }
          // 한자급수시험이 활성화 되어있는지.
          else if(active_tag[0].dataset.functionCode == 'hanja_exam'){
              active_tag[0].click();
          }
      }
  }

// 과목 점수가 없을때, 끝으로이동.
function myScoreNoneSubjectMoveLeft() {
  // 과목 카드들이 담긴 컨테이너 선택 (data-bundle="subject_grades")
  const container = document.querySelector('[data-bundle="subject_grades"]');
  if (!container) {
    console.error('컨테이너를 찾을 수 없습니다.');
    return;
  }

  // 컨테이너 내의 모든 과목 카드(.col)를 선택합니다.
  // querySelectorAll은 정적 NodeList를 반환하므로, 요소 이동 시 순서에 영향이 없습니다.
  const subjectCards = container.querySelectorAll('.col');

  subjectCards.forEach(card => {
    // 각 카드 내부의 exam score 값을 가진 요소 선택
    const scoreSpan = card.querySelector('[data-exam-rate]');
    if (scoreSpan && scoreSpan.textContent.trim() === '-') {
      // 점수가 "-"이면 해당 카드를 컨테이너의 마지막으로 이동합니다.
      container.appendChild(card);
    }
  });
}

</script>
@endsection
