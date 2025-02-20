@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '준비하기')

@section('add_css_js')
<link href="{{ asset('css/reset.css?5') }}" rel="stylesheet">
@endsection

{{-- 학생 컨텐츠 --}}
@section('layout_coutent')

<!-- : 선택 학습 정보 붙여넣기. -->
<!-- : 현재 학습중 -->
<!-- : 오늘의 학습. -->
<!-- : 프로그래스 바 색 변경. -->
<!-- : 시간 업데이트시에 자동으로 동영상시간 업데이트 되도록 수정. -->
<!-- : 드래그로 시간 이동 불가 : 중간에 멈췄다면 멈춘 시점부터 이어보기 -->
<!-- : 측정 시간 : 처음 재생 눌렀을 때부터 문제 다 풀고 제출했을 때 까지 > 본 횟수 상관없고 , 재생-문제 제출 시간 1회 고정입니다 -->
<!-- : 재생 눌렀을때 시작입니다 학습 그만할래요 클릭 시 그 시점부터 이어보기 가능 실력 문제까지 다 풀었을 때 까지 시간 측정 -->
<!-- : 동영상 시청 시간 / 마지막 시간  업데이트. -->
<!-- : IS_LIKE 기능 구현. -->
<!--  : 시청시 시청 시간 카운트.(어떻게 할지) / 우선은 누적시간 마지막 시간 업데이트 -->
<!-- NOTE: 동영상 붙이기. / 우선은 내장되어잇는 비디오로 붙임. -->
<!-- TODO: 준비하기(is_complete) Y 일때, 개념다지기로 넘어가기 다른페이지도 마찬가지로 진행. -->

<input type="hidden" data-main-student-lecture-detail-seq value="{{ $st_lecture_detail_seq}}">
<input type="hidden" data-main-lecture-seq value="{{$lecture_seq}}">
<input type="hidden" data-main-lecture-detail-seq value="{{$lecture_detail_seq}}">
<input type="hidden" data-main-lecture-type value="">
<input type="hidden" data-main-student-lecture-detail-status value="{{$lecture_detail_info->status}}">
<input type="hidden" data-prev-page value="{{ $prev_page }}">

<link rel="stylesheet" href="{{ asset('css/video.css?4') }}">
<div class="col mx-0 row position-relative">
  <div class="col-lg col-lg-9 pe-0 ps-sm-2 ps-md-2 ps-lg-0">
    {{-- 동영상 시청 --}}
    <section data-section-study-video="top" class="">
      {{-- video 자리 --}}
      <div class="w-100 overflow-hidden video-container" style="" id="videoPtDiv">
        <ul class="video-cont-tab cursor-pointer">
        <li onclick="studyVideoClickTopTab(this)" class="beginning active" data-type=""
            data-complete="{{$lecture_detail_info->is_complete}}"><span>1</span> 준비하기</li>
        <li onclick="studyVideoClickTopTab(this)" class="concept" data-type="concept_building"
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
        <div class="video-title d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center">
            <span class="subject-name">{{$lecture_subject["lecture_name"]}}</span>
            <span class="lecture-name text-b-24px">{{$top_menutabs->where('id', $lecture_detail_info["lecture_detail_seq"])->first()["lecture_detail_description"]}} {{$lecture_detail_info["lecture_detail_name"]}}</span>
          </div>
          <button class="btn p-0 hart-btn" onclick="studyVideoClickLike();" data-btn-is-like>
            <div class="hart-btn-img">
              <img src="{{ asset('images/hart_icon.svg') }}" width="28" data-is-like="red" {{ $lecture_detail_info->is_like == 'Y'?'':'hidden' }}>
              <img src="{{ asset('images/gray_hart_icon.svg') }}" width="28" data-is-like="gray" {{ $lecture_detail_info->is_like == 'Y'?'hidden':'' }}>
              <input type="hidden" data-inp-is-like value="{{ $lecture_detail_info->is_like }}" />
            </div>
          </button>
        </div>
        <div class="video-wrap position-relative">
          <video id="lectureVideo">
            <source src="{{ $lecture_detail_info->lecture_detail_link }}" type="video/mp4">
          </video>
          <div class="video-play-icon">
            <img class="video-pause-icon-img" src="{{ asset('images/pause_icon_w.svg') }}">
            <img class="video-play-icon-img" src="{{ asset('images/video_play_icon2.svg') }}">
          </div>
          <div class="video-progress-spinner" style="">
            <div class="spinner-border" role="status"></div>
          </div>
          <div class="video-error-message" style="display: none;">
            <img src="{{ asset('images/svg/all_Character.svg') }}">
            <div class="text-b-24px">선생님 강의가 없어요.</div>
          </div>
        </div>

        <div class="video-controls-pt">
          <div class="video-controls">
            <div id="progressBar" class="progress position-relative" role="progressbar" style="height:12px" data-progress-bar>
              <div class="progress-bar bg-primary-y rounded-0" style="width: 0%"></div>
              <div class="progress-bar-buffer bg-primary-y opacity-25 rounded-0" style="width: 0%"></div>
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
              <button id="fullscreenButton">
                <img src="{{ asset('images/full-screen-icon.svg') }}" width="32">
              </button>
            </div>
          </div>
        </div>
        <button type="submit"></button>
        <input type="hidden" id="playacc_time" data-acc-video-time>
        <input type="hidden" id="playlast_time" data-last-video-time>
        <!-- last_video_time(초) 가 잇으면, currenTime 조절. -->
        @if($lecture_detail_info->last_video_time != '')
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('lectureVideo');
            video.currentTime = '{{ $lecture_detail_info->last_video_time }}';
          });

        </script>
        @endif
        <script type="text/javascript" src="{{ asset('js/video.js?1') }}"></script>
      </div>
    </section>
  </div>
  <div class="aside-right learning-list col-lg-3 d-flex flex-column justify-content-between ps-2 ps-xl-4 ps-xxl-4 pe-0 ">
    <div class="learning-wrap">
      <div class="learning-list-header">
        <div class="p-4">
          <div class="text-b-24px">오늘의 학습</div>
        </div>
      </div>
      <div class="learning-list-body position-relative">
        <ul data-bundle="todays_learning">
          <li class="learning-list-item" data-row="copy" hidden>
            <input type="hidden" data-row-student-lecture-detail-seq value="">
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
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
@if($all_is_complete??'' == 'Y')
<script>
setTimeout(function(){
    studyVideoExit();
}, 1000);
</script>
@endif
<script>

  const learningListItem = document.querySelector('.learning-list-item');
  const playIcon = document.querySelector('.video-play-icon');

  // 홈페이지 나가려고 할때 감지해서 studyVideoTimeUpdate펑션 실행.

  document.addEventListener('DOMContentLoaded', async function() {
    const video = document.querySelector('#lectureVideo');
    const testVideo = document.querySelector('#lectureTestVideo');
    document.querySelectorAll('.video-cont-tab.cursor-pointer li:not([hidden])').forEach((span, index) => {
      span.querySelector('span').textContent = index + 1;
    });

    window.addEventListener('beforeunload', function(e) {
      studyVideoPause();
      studyVideoTimeUpdate();
    });

    video.addEventListener('loadstart', () => {
      document.querySelector('.video-progress-spinner').style.display = 'block';
      playIcon.style.display = 'none';
      video.style.background = '#FFF6E0';
    });

    video.addEventListener('waiting', () => {
      document.querySelector('.video-progress-spinner').style.display = 'block';
      playIcon.style.display = 'none';
      video.style.background = '#FFF6E0';
    });


    video.addEventListener('canplay', () => {
      document.querySelector('.video-progress-spinner').style.display = 'none';
      playIcon.style.display = 'block';
      video.style.background = '';
    });

    video.addEventListener('progress', () => {
      if (video.buffered.length > 0) {
        const bufferedEnd = video.buffered.end(video.buffered.length - 1);
        const duration = video.duration;
        const bufferedPercent = (bufferedEnd / duration) * 100;
        const progressBarBuffer = document.querySelector('.progress-bar-buffer');
        progressBarBuffer.style.width = `${bufferedPercent}%`;
      }
    });
    video.addEventListener('error', (e) => {
      console.error('비디오 로딩 에러:', e);
      document.querySelector('.video-error-message').style.display = 'flex';
    });
    initHLSPlayer(video, "{{ $lecture_detail_info->lecture_detail_link }}");
  });

  // HLS 플레이어 초기화 함수
  function initHLSPlayer(videoElement, hlsUrl) {
    if (hlsUrl.endsWith('.m3u8')) {
      if (Hls.isSupported()) {
        var hls = new Hls();
        hls.loadSource(hlsUrl);
        hls.attachMedia(videoElement);
        hls.on(Hls.Events.MANIFEST_PARSED, function() {
          showPlayButton();
        });
        console.log('HLS 지원');
      } else if (videoElement.canPlayType('application/vnd.apple.mpegurl')) {
        videoElement.src = hlsUrl;
        videoElement.addEventListener('loadedmetadata', function() {
          showPlayButton();
        });
      }
    }
  }

  //시작
  document.addEventListener('DOMContentLoaded', async function() {
    await studyVideoGetTodayStudy();
  });

  function asideRight() {
    const learning_list_item = document.querySelectorAll('[data-row="clone"].learning-list-item');
    if (learning_list_item.length > 3) {
      document.querySelector('.learning-list-body').style.height = `${learning_list_item[0].clientHeight * 3}px`;
    }
  }

  window.addEventListener('resize', function() {
    asideRight();
  });

  function studyVideoAsideTab(vthis) {
    // 나머지 버튼 비활성화
    document.querySelectorAll('[data-btn-study-video-main-tab]').forEach(function(item) {
      item.classList.remove('active');
    });
    // 활성화
    vthis.classList.add('active');
  }

  function openModal() {
    const modalElement = document.querySelector('#system_alert .modal');
    modalElement.removeAttribute('aria-hidden');
    modalElement.removeAttribute('inert');
    modalElement.style.display = 'block';
    modalElement.focus();
  }

  // 모달 닫기
  function closeModal() {
    const modalElement = document.querySelector('#system_alert .modal');
    modalElement.setAttribute('aria-hidden', 'true');
    modalElement.setAttribute('inert', '');
    modalElement.style.display = 'none';
  }

  // 학습 그만하기 / 뒤로가기
  function studyVideoExit() {
    // 학습이 완전히 끝낫는지 확인.
    const is_all_complete = document.querySelector('[data-main-student-lecture-detail-status]').value;
    // 뒤로가기
    const img1 = `<img class="mb-4" src="{{ asset('images/alert_fire_character.jpg') }}">`;
    let msg1 = '<div class="text-b-28px">아직 학습이 끝나지 않았어요.</div>';
    let msg2 = '<div class="text-b-28px text-danger pb-4 pt-3">정말 학습을 그만할건가요?</div>';
    if(is_all_complete == 'complete'){
        msg1 = '<div class="text-b-28px">학습을 완료합니다.</div>';
        msg2 = '<div class="text-b-28px text-danger pb-4 pt-3">학습을 그만하시겠습니까?</div>';
    }
    sAlert('', img1 + msg1 + msg2, 3, function() {
        // 배경(음영) 삭제.
        closeModal();
      }, function() {
        //그만둘경우 저장 지점 저장.
        studyVideoPause();
        studyVideoTimeUpdate();
        sessionStorage.setItem('isBackNavigation', 'true');
        // goToRememberedScreen();
        // history.back();
        const prev_page = document.querySelector('[data-prev-page]').value;
        if(prev_page == 'my_score'){
            location.href = '/student/my/score';
        }
        else if(prev_page == 'school_study'){
            location.href = '/student/school/study';
        }
        else if(prev_page == 'my_study_like_lecture'){
          location.href = '/student/my/study?type=like';
        }
        else{
            location.href = '/student/main';
        }
      }
      , '더 해볼게요.'
      , '네, 그만할게요.');

    // 닫힐때 애니메이션
    document.querySelector('.modal-title').hidden = true;
    document.querySelector('.modal').classList.add('fade-in-animation');
    document.querySelector('.modal').classList.add('fade');
    document.querySelector('.modal').style.zIndex = '9999';
    let btnClose = document.querySelector('.msg_btn1');
    btnClose.setAttribute('data-bs-dismiss', 'modal');
    openModal();

    const modalElement = document.querySelector('#system_alert .modal');
    const myModal = new bootstrap.Modal(modalElement, {});
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
    const st_lecture_detail_seq = document.querySelector('[data-main-student-lecture-detail-seq]').value;
    let is_like = 'Y';
    if (inp_is_like == 'Y') is_like = 'N';

    const page = "/student/study/video/like/update";
    const parameter = {
      'st_lecture_detail_seq': st_lecture_detail_seq
      , 'is_like': is_like
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
      }
    })
  }
  // 비디오 멈춤.
  function studyVideoPause() {
    video.pause();
  }

  // 동영상 시청 시간 업데이트
  function studyVideoTimeUpdate() {
    const st_lecture_detail_seq = document.querySelector('[data-main-student-lecture-detail-seq]').value;
    const last_video_time = document.querySelector('[data-last-video-time]').value;
    const acc_video_time = document.querySelector('[data-acc-video-time]').value;
    const duration = document.querySelector('#duration').textContent;
    const page = "/student/study/video/time/update";
    const parameter = {
      st_lecture_detail_seq: st_lecture_detail_seq
      , last_video_time: last_video_time
      , acc_video_time: acc_video_time
      , lecture_detail_time: duration
    }
    queryFetch(page, parameter, function(result) {
      if ((result.resultCode || '') == 'success') {
      }
      studyVideoGetTodayStudy();
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
          row.querySelector('[data-row-student-lecture-detail-seq]').value = study_info.id;
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

          video.addEventListener('loadedmetadata', function() {
            const lectureDetailTimeElement = row?.querySelector('[data-lecture-detail-time]');
            if (lectureDetailTimeElement) {
              lectureDetailTimeElement.textContent = formatTime(video.duration);
            }
          });

          bundle.appendChild(row);
        });
        asideRight();
      }
    })
  }

  // 동영상 완료시
  function studyVideoComplete() {
      const st_lecture_detail_seq = document.querySelector('[data-main-student-lecture-detail-seq]').value;
      const lecture_detail_type = document.querySelector('[data-main-lecture-type]').value;
      const page = "/student/study/video/complete/update";
      const parameter = {
          st_lecture_detail_seq: st_lecture_detail_seq
          , lecture_detail_type: lecture_detail_type
      };
      queryFetch(page, parameter, function(result) {
          if ((result.resultCode || '') == 'success') {
              // alert('학습이 완료되었습니다.');
              studyVideoGetTodayStudy();
              let msg = "영상강의 시청을 완료했습니다.";
              let msg2 = "<div class='text-b-23px text-danger'>취소하면 영상을 다시 볼 수 있습니다.</div>";
              const next_info = getIsNextLecture();
              const is_next = next_info[0];
              const next_data_type = next_info[1];
              // 다음수업이 있으면.
              if(is_next){
                  msg += "<br> 확인을 누르면 다음 강의로 이동합니다. 이동하겠습니까?";
              }else{
                  // 모두 완료.종료
                  msg += "<br>모든 강의를 완료했습니다. 종료하겠습니까?";
              }

              sAlert('', '<div class="text-b-28px" style="line-height:2.5rem">'+msg+'</div>'+msg2, 3, function(){
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

  // :준비하기, 개념다지기, 문제풀기, 정리하기, 단원평가 탭 클릭
  function studyVideoClickTopTab(vthis) {
    const detail_type = vthis.dataset.type;
    const form = document.querySelector('#form_post');
    form.method = 'post';
    form.querySelector('[name="st_lecture_detail_seq"]').value = document.querySelector('[data-main-student-lecture-detail-seq]').value;
    form.querySelector('[name="lecture_detail_seq"]').value = document.querySelector('[data-main-lecture-detail-seq]').value;
    form.querySelector('[name="lecture_seq"]').value = document.querySelector('[data-main-lecture-seq]').value;
    form.querySelector('[name="prev_page"]').value = document.querySelector('[data-prev-page]').value;

    if (detail_type == '') {
      form.action = "/student/study/video";
      // window.location.href='/student/study/video'
    } else if (detail_type == 'concept_building') {
      form.action = "/student/study/concept";
      // window.location.href='/student/study/concept';
    } else if (detail_type == 'exam_solving') {
      form.action = "/student/study/quiz";
      // window.location.href='/student/study/quiz'
    } else if (detail_type == 'summarizing') {
      form.action = "/student/study/summary";
      // window.location.href='/student/study/summary'
    } else if (detail_type == 'unit_test') {
      form.action = "/student/study/unitQuiz";
      // window.location.href='/student/study/unitQuiz'
    }
    rememberScreenOnSubmit();
    form.submit();
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
