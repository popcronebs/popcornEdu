@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '개념다지기')

@section('add_css_js')
<link href="{{ asset('css/reset.css?5') }}" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
@endsection

@section('layout_coutent')
<input type="hidden" data-main-student-lecutre-detail-seq value="{{ $st_lecture_detail_seq}}">
<link rel="stylesheet" href="{{ asset('css/video.css') }}">

<div class="col mx-0 row position-relative">

<input type="hidden" data-main-student-lecture-detail-seq value="{{ $st_lecture_detail_seq}}">
<input type="hidden" data-main-lecture-seq value="{{$lecture_seq}}">
<input type="hidden" data-main-lecture-detail-seq value="{{$lecture_detail_seq}}">
<input type="hidden" data-main-lecture-type value="concept_building">
<input type="hidden" data-prev-page value="{{ $prev_page }}">
<input type="hidden" data-main-student-lecture-detail-status value="{{$lecture_detail_info->status}}">

    <div class="col-lg col-lg-9 px-0">
        {{-- 동영상 시청 --}}
        <section data-section-study-video="top" class="p-0">
            {{-- video 자리 --}}
            <div class="w-100 overflow-hidden video-container" style="" id="videoPtDiv">
                <ul class="video-cont-tab cursor-pointer">
                    <li onclick="studyVideoClickTopTab(this)" class="beginning" data-type=""
                        data-complete="{{$lecture_detail_info->is_complete}}"><span>1</span> 준비하기</li>
                    <li onclick="studyVideoClickTopTab(this)" class="concept active" data-type="concept_building"
                        data-complete="{{$lecture_detail_info->is_complete2}}"
                        {{$top_menutabs->where('lecture_detail_type', 'concept_building')->count() > 0 ? '':'hidden' }} ><span>2</span> 개념다지기</li>
                    <!-- summarizing -->
                    <li onclick="studyVideoClickTopTab(this)" class="review" data-type="summarizing"
                        data-complete="{{$lecture_detail_info->is_complete3}}"
                        {{$top_menutabs->where('lecture_detail_type', 'summarizing')->count() > 0 ? '':'hidden' }}><span>3</span> 정리학습</li>
                    <li onclick="studyVideoClickTopTab(this)" class="questions" data-type="exam_solving"
                        data-complete="{{$lecture_detail_info->is_complete4}}"
                        {{$top_menutabs->where('lecture_detail_type', 'exam_solving')->count() > 0 ? '':'hidden' }}><span>4</span> 문제풀기</li>
                    <li onclick="studyVideoClickTopTab(this)" class="assessment" data-type="unit_test"
                        data-complete="{{$lecture_detail_info->is_complete5}}"
                        {{$top_menutabs->where('lecture_detail_type', 'unit_test')->count() > 0 ? '':'hidden' }}><span>5</span> 단원평가</li>
                </ul>
                <div class="video-title d-flex justify-content-between align-items-center" style="background-color:#1FE9E9">
                    <div class="d-flex align-items-center">
                        <span class="subject-name">{{ $lecture_subject->lecture_name }}</span>
                        <span class="lecture-name text-b-24px">{{ $top_menutabs->where('id', $lecture_detail_info["lecture_detail_seq"])->first()["lecture_detail_description"]}} {{$lecture_detail_info["lecture_detail_name"] }}</span>
                    </div>
                    <button class="btn p-0 hart-btn" onclick="studyVideoClickLike();" data-btn-is-like>
                        <div class="hart-btn-img">
                          <img src="{{ asset('images/hart_icon.svg') }}" width="28" data-is-like="red" {{ $lecture_detail_info->is_like == 'Y'?'':'hidden' }}>
                          <img src="{{ asset('images/gray_hart_icon.svg') }}" width="28" data-is-like="gray" {{ $lecture_detail_info->is_like == 'Y'?'hidden':'' }}>
                            <input type="hidden" data-inp-is-like value="{{ $lecture_detail_info->is_like }}" />
                        </div>
                    </button>
                </div>
                <div id="div_video" {{$concept_building->is_first_interactive == 'Y' ? 'hidden':'' }}>
                    <video id="lectureVideo" style="width:100%;" src="{{ $concept_building->lecture_detail_link }}" onerror="errorVideo();"></video>
                    <div class="video-play-icon">
                        <img class="video-pause-icon-img" src="{{ asset('images/pause_icon_w.svg') }}">
                        <img class="video-play-icon-img" src="{{ asset('images/video_play_icon2.svg') }}">
                    </div>
                    <div class="video-controls-pt">
                        <div class="video-controls">
                            <div id="progressBar" class="progress" role="progressbar" style="height:12px" data-progress-bar>
                                <div class="progress-bar bg-primary-y rounded-0" style="width: 0%"></div>
                            </div>
                        </div>
                        <div class="video-controls mt-2">
                            <div class="position-relatives">
                                <button id="speedButton" class="d-flex align-items-center">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.24989 6.25L13.7497 13.7498" stroke="white" stroke-width="3" stroke-linecap="round" />
                                        <path d="M13.7499 6.25L6.25009 13.7498" stroke="white" stroke-width="3" stroke-linecap="round" />
                                    </svg>
                                    <span>1</span>
                                </button>
                                <ul id="speedSelect" class="ms-2">
                                    <li value="0.5">0.5</li>
                                    <li value="1" class="active">1</li>
                                    <li value="1.5">1.5</li>
                                    <li value="2">2</li>
                                </ul>
                            </div>
                            <button id="playPauseButton" class="d-flex align-items-center">
                                <img src="{{ asset('images/video_play_icon2.svg') }}" width="32">
                            </button>
                            <button id="playVolume" class="d-flex align-items-center">
                                <img src="{{ asset('images/volume_icon.svg') }}" width="32">
                            </button>
                            <input type="range" id="volumeBar" min="0" max="1" step="0.1" value="0.5" style="width: 112px;">
                            <div class="h-center justify-content-end w-100">
                                <span id="currentTime">0:00</span> / <span id="duration" class="ms-2">0:00</span>
                                <button id="fullscreenButton">⛶</button>
                            </div>
                        </div>
                    </div>
                    <button type="submit"></button>
                    <input type="hidden" id="playacc_time" data-acc-video-time>
                    <input type="hidden" id="playlast_time" data-last-video-time>
                    @if($lecture_detail_info->last_video_time2 != '')
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const video = document.getElementById('lectureVideo');
                        video.currentTime = '{{ $lecture_detail_info->last_video_time2 }}';
                    });
                    </script>
                    @endif
                    <script type="text/javascript" src="{{ asset('js/video.js') }}"></script>
                </div>
                <div id="div_interactive" style="height:clamp(75vh, calc(3.125rem + 4.167vw), 90vh); max-height: 616px;">
                    <iframe id="ifrm_interactive" src="http://interactive.popcorn-edu.com" frameborder="0" style="width: 100%; height:100%;"></iframe>
                </div>
            </div>
        </section>
    </div>
    {{-- 하단 / 오늘의학습, 현재학습중 TAB --}}
    <div class="aside-right learning-list col-lg-3 d-flex flex-column justify-content-between ps-4 pe-2">
        <div class="learning-wrap">
            <div class="learning-list-header" style="background-color:#1FE9E9">
                <div class="p-4">
                    <div class="text-b-24px">오늘의 학습</div>
                </div>
            </div>
            <div class="learning-list-body position-relative video-container">
                <ul data-bundle="todays_learning">
                    <li class="learning-list-item" data-row="copy" hidden>
                        <div class="learning-list-item-title complete-learning">
                            <p class="subject-name" data-lecture-name></p>
                            <p class="lecture-name" data-lectdure-detail-name></p>
                            <span class="status-current" data-status>학습완료</span>
                        </div>
                        <div class="learning-list-item-img" data-subject-code>
                            <img src="{{ asset('images/subject_kor_icon.svg') }}">
                        </div>
                    </li>
                </ul>
                <div class="custom-scrollbar d-none">
                    <div class="scrollbar-track">
                        <div class="scrollbar-thumb"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="">
            <button class="text-b-24px btn btn-danger w-100 rounded-4" style="height:72px" onclick="studyVideoExit();">
                종료하기
            </button>
        </div>
    </div>
</div>
{{-- padding 160px --}}
<!-- <div>
    <div class="py-lg-5"></div>
    <div class="py-lg-4"></div>
    <div class="pt-lg-3"></div>
</div> -->
<form id="form_post" action="" target="_self" hidden>
    @csrf
    <input type="hidden" name="st_lecture_detail_seq" value="">
    <input type="hidden" name="lecture_detail_seq" value="">
    <input type="hidden" name="lecture_seq" value="">
    <input type="hidden" name="prev_page" value="">
</form>
<script>
    const concept_building = @json($concept_building);
    const JSONDataStr = @json($interactive_json);
    // 홈페이지 나가려고 할때 감지해서 studyVideoTimeUpdate펑션 실행.
    document.addEventListener('DOMContentLoaded', function() {
        window.addEventListener('beforeunload', function(e) {
            studyVideoPause();
            document.querySelectorAll('.video-cont-tab.cursor-pointer li:not([hidden])').forEach((span, index) => {
                span.querySelector('span').textContent = index + 1;
            });
            //studyVideoTimeUpdate();
        });
        asideRight();

        {{--
        const listBody = document.querySelector('.aside-right .learning-list-body');
        const customScrollbar = document.querySelector('.custom-scrollbar');
        const scrollbarThumb = document.querySelector('.custom-scrollbar .scrollbar-thumb');
        const scrollbarTrack = document.querySelector('.custom-scrollbar .scrollbar-track');

        function updateScrollbarThumb() {
            const thumbHeight = listBody.clientHeight * (listBody.clientHeight / listBody.scrollHeight);
            scrollbarThumb.style.height = `${thumbHeight}px`;
        }
        --}}

        setTimeout(() => {
            getConcept();
        }, 2000);
    });
    function asideRight() {
        const learning_list_item = document.querySelectorAll('[data-row="clone"].learning-list-item');
        if(learning_list_item.length > 3) {
            document.querySelector('.learning-list-body').style.height = `${learning_list_item[0].clientHeight * 3}px`;
        }
    }

    window.addEventListener('resize', function() {
        asideRight();
    });


    function getConcept(){
        if(JSONDataStr){
            const iframe = document.getElementById('ifrm_interactive');
            const JSONData = JSON.parse(JSONDataStr);
            if (iframe) {
                // 최대 10번동안 시도한다. interactiveReady 의 상태가 true 인지에 대해서.
                // 단 10번시도까지 안되면 알림으로 알려준다.
                let is_break = false;
                for(let i = 0; i < 10; i++){
                    // interactiveReady 가 true인지 확인.
                    setTimeout(function(i){
                        if(is_interactive_ready && !is_break){
                            iframe.contentWindow.postMessage(JSON.stringify(JSONData), '*');
                            // 중단.
                            is_break = true;
                            return;
                        }else{
                            // 10모두 시도후 실패시.
                            if(i == 9 && !is_break){
                                sAlert('', '<div class="text-b-28px">인터렉티브 작동중에 오류가 발생했습니다. 다시 시도해주세요.</div>', 4, function(){
                                    location.reload();
                                });
                            }
                        }
                    }.bind(null,i), 1000*i);
                }
            }
        }else{
            is_complete_interactive = true;
            // 만약 인터렉트가 먼저 나온거라면, 비디오로 변경.
            if(concept_building.is_first_interactive == 'Y'){
                document.getElementById('div_interactive').hidden = true;
                document.getElementById('div_video').hidden = false;
            }
        }
    }


    //시작
    document.addEventListener('DOMContentLoaded', function() {
        studyVideoGetTodayStudy();
    });

    function studyVideoAsideTab(vthis) {
        // 나머지 버튼 비활성화
        document.querySelectorAll('[data-btn-study-video-main-tab]').forEach(function(item) {
            item.classList.remove('active');
        });
        // 활성화
        vthis.classList.add('active');
    }

    // 학습 그만하기 / 뒤로가기
    function studyVideoExit() {
        // 뒤로가기
        let msg1 = '<div class="text-b-28px">아직 학습이 끝나지 않았어요.</div>';
        let msg2 = '<div class="text-b-28px text-danger pb-4 pt-3">정말 학습을 그만할건가요?</div>';

        // 학습이 완전히 끝났는지 확인.
        const is_all_complete = document.querySelector('[data-main-student-lecture-detail-status]').value;

        if(is_all_complete == 'complete'){
            msg1 = '<div class="text-b-28px">학습이 완료되어요.</div>';
            msg2 = '<div class="text-b-28px text-danger pb-4 pt-3">학습을 그만할건가요?</div>';
        }
        sAlert('', msg1 + msg2, 3, function() {
                // 배경(음영) 삭제.
                document.querySelector('.modal-backdrop').remove();
            }, function() {
                // TODO: 현재 뒤로가기로 돌아가는데 들어온 지점을 저장해서, 그 url로 이동하는게 것을 추천.
                // 새로고침이나, iframe에 의한 페이지 이동이 명확한 이동을 방지.

                //그만둘경우 저장 지점 저장.
                studyVideoPause();
                //studyVideoTimeUpdate();

                sessionStorage.setItem('isBackNavigation', 'true');
                //goToRememberedScreen();
                // history.back();
                const prev_page = document.querySelector('[data-prev-page]').value;
                if(prev_page == 'my_score'){
                    location.href = '/student/my/score';
                }
                else if(prev_page == 'school_study'){
                    location.href = '/student/school/study';
                }
                else{
                    location.href = '/student/main';
                }
                // 배경(음영) 삭제.
                document.querySelector('.modal-backdrop').remove();
            },
            '더 해볼게요.',
            '네,그만할게요.');
        // 배경음영.
        const myModal = new bootstrap.Modal(document.querySelector('#system_alert .modal'), {});
        myModal.show();

    }

    // 하단 섹션 탭 버튼 클릭 / 오늘의 학습 / 현재 학습 중
    function studyVideoSectionBtmTab(vthis) {
        // data-btn-study-video-btm-tab 모두 비활성화
        document.querySelectorAll('[data-btn-study-video-btm-tab]').forEach(function(item) {
            item.classList.remove('active');
        });
        // 활성화
        vthis.classList.add('active');

        // 리스트 불러오기.
        studyVideoGetTodayStudy();
    }

    // 좋아요 클릭.
    function studyVideoClickLike() {
        const inp_is_like = document.querySelector('[data-inp-is-like]').value;
        const st_lecture_detail_seq = document.querySelector('[data-main-student-lecutre-detail-seq]').value;
        let is_like = 'Y';
        if (inp_is_like == 'Y') is_like = 'N';

        const page = "/student/study/video/like/update";
        const parameter = {
            'st_lecture_detail_seq': st_lecture_detail_seq,
            'is_like': is_like
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                toast('좋아요 설정이 변경되었습니다.');
                // 좋아요 반대로.
                // data-btn-is-like 버튼 안에 2개의 img hidden 반대로 변경.
                document.querySelector('[data-inp-is-like]').value = is_like;
                const btn_like = document.querySelector('[data-btn-is-like]');
                const img_like_els = btn_like.querySelectorAll('img');
                img_like_els.forEach(function(item) {
                    item.hidden = !item.hidden;
                });
            } else {}
        })

    }
    // 오늘의 학습 가져오기.
    function studyVideoGetTodayStudy() {
        const page = "/student/study/video/today/select";
        // const btm_active_tab = document.querySelector('[data-btn-study-video-btm-tab].active').dataset.btnStudyVideoBtmTab;
        // let status = btm_active_tab == '1' ? '' : 'study'
        const status = '';
        const parameter = {
            search_status: status
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                // 초기화
                const bundle = document.querySelector('[data-bundle="todays_learning"]');
                const row_copy = bundle.querySelector('[data-row="copy"]');
                bundle.innerHTML = '';
                bundle.appendChild(row_copy);

                // 오늘의 학습 정보 가져오기.
                const study_infos = result.student_lecture_details;
                study_infos.forEach(function(study_info) {
                    const row = row_copy.cloneNode(true);
                    row.hidden = false;
                    row.setAttribute('data-row', 'clone');

                    // const video = row.querySelector('[data-lecture-video]');
                    // video.src = study_info.lecture_detail_link;
                    row.querySelector('[data-lecture-name]').textContent = study_info.lecture_name;
                    row.querySelector('[data-lectdure-detail-name]').textContent = study_info.lecture_detail_name;
                    // row.querySelector('[data-lecture-detail-time]').textContent = study_info.lecture_detail_time;

                    // 과목 이미지
                    if (study_info.subject_function_code) {
                        const src = `/images/${study_info.subject_function_code}.svg`;
                        row.querySelector('[data-subject-code] img').src = src;
                    }

                    if (study_info.status == 'complete') {
                        row.querySelector('[data-status]').innerText = '학습완료';
                        row.querySelector('[data-status]').classList.add('completion');
                    } else if (study_info.status == 'ready') {
                        row.querySelector('[data-status]').innerText = '학습전';
                        row.querySelector('[data-status]').classList.add('before');
                    } else if (study_info.status == 'study') {
                        row.querySelector('[data-status]').innerText = '학습중';
                        row.querySelector('[data-status]').classList.add('doing');
                    }

                    {{-- video.addEventListener('loadedmetadata', function() {
                        row.querySelector('[data-lecture-detail-time]').textContent = formatTime(video.duration);
                    }); --}}

                    bundle.appendChild(row);
                    asideRight();
                });
            } else {}
        })
    }
    // 비디오 멈춤.
    function studyVideoPause() {
        const video = document.querySelector('#lectureVideo');
        video.pause();
    }

    // 동영상 시청 시간 업데이트
    function studyVideoTimeUpdate() {
        const st_lecture_detail_seq = document.querySelector('[data-main-student-lecutre-detail-seq]').value;
        const last_video_time = document.querySelector('[data-last-video-time]').value;
        const acc_video_time = document.querySelector('[data-acc-video-time]').value;
        const duration = document.querySelector('#duration').textContent;
        const lecture_detail_type = document.querySelector('[data-main-lecture-type]').value;

        const page = "/student/study/video/time/update";
        const parameter = {
            st_lecture_detail_seq: st_lecture_detail_seq,
            last_video_time: last_video_time,
            acc_video_time: acc_video_time,
            lecture_detail_time: duration,
            lecture_detail_type:lecture_detail_type
        }
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {}
        })
    }

    // 출선(학습시작)
    function attendInsert(callback) {
        const sel_date = new Date().format('yyyy-MM-dd');
        const page = "/student/study/start/attend";
        const parameter = {
            sel_date: sel_date
        };

        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                // alert('출석이 완료되었습니다.');
                if (callback != undefined) callback();
            } else {
                // toast('다시 시도해주세요');
            }
        })
    }

    // 동영상 완료시
    function studyVideoComplete() {
        //인터렉티브가 끝나야 완료해준다.
        if(!is_complete_interactive){
            return;
        }
        const st_lecture_detail_seq = document.querySelector('[data-main-student-lecutre-detail-seq]').value;
        const lecture_detail_type = document.querySelector('[data-main-lecture-type]').value;

        const page = "/student/study/video/complete/update";
        const parameter = {
            st_lecture_detail_seq: st_lecture_detail_seq,
            lecture_detail_type:lecture_detail_type
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                // alert('학습이 완료되었습니다.');
              let msg = "개념다지기를 완료하셨습니다.";
              const next_info = getIsNextLecture();
              const is_next = next_info[0];
              const next_data_type = next_info[1];
              // 다음수업이 있으면.
              if(is_next){
                  msg += "<br>확인을 누르시면 다음 강의로 이동합니다. 이동하시겠습니까?";
              }else{
                  // 모두 완료.종료
                  msg += "<br>모든 강의를 완료하셨습니다. 종료하시겠습니까?";
              }
              sAlert('', '<div class="text-b-28px">'+msg+'</div>', 3, function(){
                  if(is_next){
                      document.querySelector('[data-type="'+next_data_type+'"]').click();
                  }else{
                    setTimeout(function(){
                        studyVideoExit();
                    }, 1000);
                  }
              })
            } else {}
        });
    }

    // 준비하기, 개념다지기, 문제풀기, 정리하기, 단원평가 탭 클릭
    function studyVideoClickTopTab(vthis){
        const detail_type = vthis.dataset.type;
        const form = document.querySelector('#form_post');
        form.method = 'post';
        form.querySelector('[name="st_lecture_detail_seq"]').value = document.querySelector('[data-main-student-lecture-detail-seq]').value;
        form.querySelector('[name="lecture_detail_seq"]').value = document.querySelector('[data-main-lecture-detail-seq]').value;
        form.querySelector('[name="lecture_seq"]').value = document.querySelector('[data-main-lecture-seq]').value;
        form.querySelector('[name="prev_page"]').value = document.querySelector('[data-prev-page]').value;

        if(detail_type == ''){
            form.action = "/student/study/video";
            // window.location.href='/student/study/video'
        }
        else if(detail_type == 'concept_building'){
            form.action = "/student/study/concept";
            // window.location.href='/student/study/concept';
        }
        else if(detail_type == 'exam_solving'){
            form.action = "/student/study/quiz";
        }
        else if(detail_type == 'summarizing'){
            form.action = "/student/study/summary";
            // window.location.href='/student/study/summary'
        }
        else if(detail_type == 'unit_test'){
            form.action = "/student/study/unitQuiz";
            // window.location.href='/student/study/unitQuiz'
        }
        rememberScreenOnSubmit();
        form.submit();
    }

let is_complete_interactive = false;
let is_video_error = false;
let is_interactive_ready = false;
window.addEventListener("message", function(event) {
        if (event.data === "interactiveComplete") {
            if(!is_complete_interactive){
                is_complete_interactive = true;
                if(concept_building.is_first_interactive == 'Y'){
                    // 인터렉티브가 먼저이면, 동영상으로 변환해준다.
                    // TODO: 약간 위험하긴한데 요청이므로,
                    // 만약 동영상이 없으면 먼저 complete해준다.
                    if(is_video_error){
                        studyVideoComplete();
                    }else{
                        setTimeout(function(){
                            document.getElementById('div_interactive').hidden = true;
                            document.getElementById('div_video').hidden = false;
                        }, 3000);
                    }
                }else{
                    // 인터렉티브가 뒤이면 완료 처리해준다.
                    studyVideoComplete();
                }
            }
        }else if(event.data === "interactiveReady" ){
            // 인터렉티브쪽에서 DOMContentLoaded 일때 값을 넘겨준다.
            is_interactive_ready = true;
        }
}, false);

    // 비디오 에러
    function errorVideo(){
        is_video_error = true;
        // 인터렉티브가 나중에 나오게 했을 경우 바로 이동시킴.
        if(concept_building.is_first_interactive != 'Y'){
            document.getElementById('div_interactive').hidden = true;
            document.getElementById('div_video').hidden = false;
        }
    }

  // 다음 탭이 있는지 없는지 확인.
  // 있으면 (true) 와 data-type 이름으로 받아오기.
  function getIsNextLecture() {
      // 숨겨진(hidden) 탭을 제외한 모든 탭 요소를 가져온다.
      const tabs = Array.from(document.querySelectorAll('.video-cont-tab li:not([hidden])'));
      const activeTab = document.querySelector('.video-cont-tab .active');
      // 활성화된 탭이 없으면 false를 반환합니다.
      if (!activeTab) {
          return false;
      }
      const activeIndex = tabs.indexOf(activeTab);
      const is_next = activeIndex >= 0 && activeIndex < tabs.length - 1;
      let next_data_type = "";
      if(is_next){
          next_data_type = tabs[activeIndex + 1].dataset.type;
      }
      return [is_next, next_data_type];
  }

</script>
@endsection
