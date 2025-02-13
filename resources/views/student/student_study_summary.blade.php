@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '학습하기')

@section('add_css_js')
<link href="{{ asset('css/reset.css?5') }}" rel="stylesheet">
@endsection

{{-- 학생 컨텐츠 --}}
@section('layout_coutent')
<input type="hidden" data-main-student-lecutre-detail-seq value="{{ $st_lecture_detail_seq}}">
<style>
    header,
    footer {
        display: none;
    }

    #layout_div_content {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #FFDED9;
        background-image: url('/images/summary_bg.png');
        background-position: center;
        background-size: cover;
    }

    .summary-wrap {
        max-height: 762px;
    }

    #lectureVideo {
        aspect-ratio: 2.02;
    }

    /* 반대 */
    [data-btn-study-video-main-tab] img:nth-child(1) {
        display: none;
    }

    [data-btn-study-video-main-tab] img:nth-child(2) {
        display: inline;
    }

    /* 첫번째 이미지 */
    [data-btn-study-video-main-tab].active img:nth-child(1) {
        display: inline;
    }

    /* 두번재 이미지 */
    [data-btn-study-video-main-tab].active img:nth-child(2) {
        display: none;
    }

    .summary-container {
        position: relative;
        width: 100%;
        border-radius: 0px 12px 12px 12px;
    }

    .video-container .video-controls {
        display: flex;
        align-items: center;
    }

    .video-container .video-controls-pt {
        background-color: rgba(0, 0, 0, 0.5);
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 10px;
    }

    .video-container button {
        background-color: transparent;
        border: none;
        color: white;
        cursor: pointer;
    }

    .video-container #progressBar {
        flex-grow: 1;
        margin: 0 10px;
        color: #FFC747;
    }

    .video-container span {
        color: white;
        margin-right: 10px;
    }

    .video-container .video-cont-tab {
        border-radius: 12px 12px 0 0;
        overflow: hidden;
        display: inline-flex;
    }

    .video-container .video-cont-tab li {
        padding: 20px 0;
        width: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        font-size: 24px;
        font-weight: 600;
        color: #fff;
        opacity: 0.5;
    }

    .video-container .video-cont-tab li span {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0;
        font-size: 18px;
        border-radius: 50%;
        background: #fff ;
    }

    .video-container .video-cont-tab li.beginning {
        background: #1FA1E9;
    }

    .video-container .video-cont-tab li.beginning span {
        color: #1FA1E9;
    }


    .video-container .video-cont-tab li.concept {
        background: #1FE9E9;
    }

    .video-container .video-cont-tab li.concept span {
        color: #1FE9E9;
    }

    .video-container .video-cont-tab li.questions {
        background: #99DD42;
    }

    .video-container .video-cont-tab li.questions span {
        color: #99DD42;
    }

    .video-container .video-cont-tab li.review {
        background: #F46363;
    }

    .video-container .video-cont-tab li.review span {
        color: #DD4242;
    }

    .video-container .video-cont-tab li.assessment {
        background: #FFC747;
    }

    .video-container .video-cont-tab li.assessment span {
        color: #FFC747;
    }



    .video-container .video-cont-tab li.active,
    .video-container .video-cont-tab li.active span {
        color: #222;
        opacity: 1;
        font-weight: 700;
    }


    .video-title {
        background: #F46363;
        padding: 10px 32px;
        border-radius: 0px 12px 0px 0px;
    }

    .video-title .subject-name {
        font-weight: 600;
        color: #6F91F7;
        background: #fff;
        padding: 8px 20px;
        border-radius: 30px;
    }

    .hart-btn-img {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #fff;
    }

    .fullscreen {
        position: absolute;
        z-index: 9999;
        top: 10px;
        left: 10px;
        right: 10px;
        bottom: 10px;
        height: 90vh !important;
    }

    #volumeBar {
        -webkit-appearance: none;
        background: #ddd;
        /* 트랙의 기본 색상 */
        opacity: 0.9;
        outline: none;
        transition: all 0.2s ease;
        border-radius: 10px;
        width: 8px;
        height: 8px;
    }

    #volumeBar:hover {
        opacity: 1;
    }

    #volumeBar::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 8px;
        height: 8px;
        background: #FFC747;
        /* 손잡이의 색상 */
        cursor: pointer;
        border-radius: 50%;
        /* 손잡이를 둥글게 */
        box-shadow: 0 0 2px 0 rgba(0, 0, 0, 0.5);
        /* 손잡이의 그림자 */
        transform: scale(1.5);
    }

    #volumeBar::-moz-range-thumb {
        width: 8px;
        height: 8px;
        background: #FFC747;
        /* 손잡이의 색상 */
        cursor: pointer;
        border-radius: 50%;
        /* 손잡이를 둥글게 */
        box-shadow: 0 0 2px 0 rgba(0, 0, 0, 0.5);
        /* 손잡이의 그림자 */
        transform: scale(1.5);

    }

    #volumeBar::-webkit-slider-runnable-track {
        width: 100%;
        height: 8px;
        cursor: pointer;
        background: linear-gradient(to right, #FFC747 0%, #FFC747 var(--value), #ddd var(--value), #ddd 100%);
        border-radius: 5px;
        /* 트랙의 모서리를 둥글게 */
    }

    #volumeBar::-moz-range-track {
        width: 100%;
        height: 8px;
        cursor: pointer;
        background: linear-gradient(to right, #FFC747 0%, #FFC747 var(--value), #ddd var(--value), #ddd 100%);
        border-radius: 5px;
        /* 트랙의 모서리를 둥글게 */
    }

    #speedButton span {
        margin: 0;
        font-size: 20px;
        width: 26px;
    }

    #speedSelect {
        position: absolute;
        bottom: 60%;
        /* 요소의 기준점을 아래로 설정 */
        left: 0;
        right: 0;
        background-color: #FFC747;
        width: 60px;
        text-align: center;
        border-radius: 6px;
        height: 0px;
        overflow: hidden;
        transition: height 0.3s ease;
        /* 높이의 변화를 부드럽게 */
        transform-origin: bottom center;
        /* 변환 기준점을 아래로 설정 */
        text-align: center;
    }

    #speedSelect.show {
        height: 96px;
    }

    #speedSelect li {
        padding: 4px 0;
        color: #473300;
        transition: all 0.2s ease;
        border-radius: 6px;
    }

    #speedSelect li:hover {
        background-color: #ffd15f;
    }

    .aside-right {
        padding-top: 72px;
    }

    .aside-right .learning-wrap {
        border-radius: 12px;
        overflow: hidden;
    }

    .aside-right .learning-list-header {
        text-align: center;
        font-size: 24px;
        font-weight: 700;
        color: #fff;
        background: #F46363;
    }

    .complete-learning {}

    .aside-right .learning-list-body ul li:not(:last-child) {
        border-bottom: 1px solid #E5E5E5;
    }

    .aside-right .learning-list-body {
        overflow-y: scroll;
        position: relative;
    }

    .aside-right .learning-list-body ul li {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 24px 24px;
        background: #fff;
    }

    .aside-right .learning-list-body ul li .subject-name {
        font-size: 18px;
        font-weight: 700;
        padding-bottom: 8px;
        color: #999999;
    }

    .aside-right .learning-list-body ul li .lecture-name {
        font-size: 16px;
        font-weight: 600;
        color: #999999;
    }

    .aside-right .learning-list-body ul li .complete-learning .subject-name {
        font-size: 18px;
        font-weight: 600;
        padding-bottom: 8px;
        color: #222222;
    }

    .aside-right .learning-list-body ul li .status-current {
        display: inline-block;
        border-radius: 16px;
        color: #fff;
        font-size: 18px;
        font-weight: 600;
        padding: 6px 16px;
        background: #DCDCDC;
        margin-top: 12px;
    }

    .aside-right .learning-list-body ul li .learning-current .status-current {
        background: #FF5065;
    }

    .aside-right .learning-list-body ul li img {
        width: 72px;
    }

    .video-cont-tab {
        display: flex;
        align-items: center;
    }

    .aside-right .learning-list-body::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari, Opera */
    }

    .custom-scrollbar {
        width: 12px;
        height: 98%;
        position: absolute;
        right: 12px;
        top: 1%;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .custom-scrollbar .scrollbar-track {
        background-color: #FFF6E0;
        border-radius: 10px;
        height: 100%;
    }

    .custom-scrollbar .scrollbar-thumb {
        background-color: #FFC747;
        border-radius: 10px;
        width: 100%;
        position: absolute;
        top: 0;
        cursor: pointer;
    }

    @media (max-width: 1400px) {
        .video-title {
            padding: 12px 16px;
        }

        .aside-right {
            padding-top: 56px;
        }

        .video-container .video-cont-tab li {
            padding: 12px 16px;
            width: auto;
            font-size: 16px;
        }

        .aside-right .learning-list-body ul li img {
            display: none;
        }

        .aside-right .learning-list-body ul li .subject-name {
            font-size: 16px !important;
        }

        .aside-right .learning-list-body ul li .lecture-name {
            font-size: 14px;
        }

        .aside-right .learning-list-body ul li .status-current {
            font-size: 16px;
        }

        .video-container button {
            transform: scale(0.9);
        }

        #lectureVideo {
            aspect-ratio: auto;
        }
    }
</style>
<input type="hidden" data-main-student-lecture-detail-seq value="{{ $st_lecture_detail_seq}}">
<input type="hidden" data-main-lecture-seq value="{{$lecture_seq}}">
<input type="hidden" data-main-lecture-detail-seq value="{{$lecture_detail_seq}}">
<input type="hidden" data-main-lecture-type value="summarizing">
<input type="hidden" data-prev-page value="{{ $prev_page }}">
<input type="hidden" data-main-student-lecture-detail-status value="{{$lecture_detail_info->status}}">

<div class="col mx-0 row position-relative">
    <div class="col-lg col-lg-9 px-0 summary-wrap">
        {{-- 동영상 시청 --}}
        <section data-section-study-video="top" class="">
            {{-- video 자리 --}}
            <div class="w-100 overflow-hidden video-container" style="border-bottom-left-radius: 0px;" id="videoPtDiv">
                <ul class="video-cont-tab cursor-pointer">
                    <li onclick="studyVideoClickTopTab(this)" class="beginning" data-type=""
                        data-complete="{{$lecture_detail_info->is_complete}}"><span>1</span> 준비하기</li>
                    <li onclick="studyVideoClickTopTab(this)" class="concept" data-type="concept_building"
                        data-complete="{{$lecture_detail_info->is_complete2}}"
                        {{$top_menutabs->where('lecture_detail_type', 'concept_building')->count() > 0 ? '':'hidden' }} ><span>2</span> 개념다지기</li>
                    <!-- summarizing -->
                    <li onclick="studyVideoClickTopTab(this)" class="review active" data-type="summarizing"
                        data-complete="{{$lecture_detail_info->is_complete3}}"
                        {{$top_menutabs->where('lecture_detail_type', 'summarizing')->count() > 0 ? '':'hidden' }}><span>3</span> 정리학습</li>
                    <li onclick="studyVideoClickTopTab(this)" class="questions" data-type="exam_solving"
                        data-complete="{{$lecture_detail_info->is_complete4}}"
                        {{$top_menutabs->where('lecture_detail_type', 'exam_solving')->count() > 0 ? '':'hidden' }}><span>4</span> 문제풀기</li>
                    <li onclick="studyVideoClickTopTab(this)" class="assessment" data-type="unit_test"
                        data-complete="{{$lecture_detail_info->is_complete5}}"
                        {{$top_menutabs->where('lecture_detail_type', 'unit_test')->count() > 0 ? '':'hidden' }}><span>5</span> 단원평가</li>
                </ul>
            </div>
        </section>
        <section class="summary-container">
            <div class="video-title d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <span class="subject-name">{{ $lecture_detail_info->lecture_name }}</span>
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
        </section>
        <section class="quiz-container position-relative img-section overflow-hidden rounded-bottom-4">
            @if($lecture_detail_info->last_video_time2 != '')
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const video = document.getElementById('lectureVideo');
                video.currentTime = '{{ $lecture_detail_info->last_video_time2 }}';
            });
            </script>
            @endif
            <div id="div_interactive" style="height:clamp(75vh, calc(3.125rem + 4.167vw), 90vh); max-height: 616px;">
                <iframe id="ifrm_interactive" src="http://interactive.popcorn-edu.com" frameborder="0" style="width: 100%; height:100%;"></iframe>
            </div>
        </section>
    </div>
    <div class="aside-right learning-list col-lg-3 d-flex flex-column justify-content-between ps-4 pe-2">
        <div class="learning-wrap">
            <div class="learning-list-header">
                <div class="p-4">
                    <div class="text-b-24px">오늘의 학습</div>
                </div>
            </div>
            <div class="learning-list-body position-relative">
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
    {{-- const concept_building = @json($concept_building); --}}
    const JSONDataStr = @json($interactive_json);
    // 홈페이지 나가려고 할때 감지해서 studyVideoTimeUpdate펑션 실행.
    document.addEventListener('DOMContentLoaded', function() {
        studyVideoGetTodayStudy();
        setTimeout(() => {
            getConcept();
        }, 2000);
        document.querySelectorAll('.video-cont-tab.cursor-pointer li:not([hidden])').forEach((span, index) => {
            span.querySelector('span').textContent = index + 1;
        });
        window.addEventListener('beforeunload', function(e) {
            studyVideoPause();
            studyVideoTimeUpdate();
        });
        asideRight();
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
    // 학습 그만하기 / 뒤로가기
    function studyVideoExit() {
        // 뒤로가기
        let msg1 = '<div class="text-b-28px">아직 학습이 끝나지 않았어요.</div>';
        let msg2 = '<div class="text-b-28px text-danger pb-4 pt-3">정말 학습을 그만할건가요?</div>';

        // 학습이 완전히 끝낫는지 확인.
        const is_all_complete = document.querySelector('[data-main-student-lecture-detail-status]').value;
        if(is_all_complete == 'complete'){
            msg1 = '<div class="text-b-28px">학습이 완료되어요.</div>';
            msg2 = '<div class="text-b-28px text-danger pb-4 pt-3">학습을 그만할건가요?</div>';
        }
        sAlert('', msg1 + msg2, 3, function() {
                // 배경(음영) 삭제.
                document.querySelector('.modal-backdrop').remove();
            }, function() {

                //그만둘경우 저장 지점 저장.

                // studyVideoPause();
                // studyVideoTimeUpdate();

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
                else if(prev_page == 'my_study_like_lecture'){
                  location.href = '/student/my/study?type=like';
                }
                else{
                    location.href = '/student/main';
                }
            },
            '더 해볼게요.',
            '네,그만할게요.');
        // 배경음영.
        const myModal = new bootstrap.Modal(document.querySelector('#system_alert .modal'), {});
        myModal.show();

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
            // window.location.href='/student/study/quiz'
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
                    bundle.appendChild(row);
                });
                asideRight();
            }
        })
    }

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
        }
    }

    // 동영상(인터렉티브) 완료시
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
                let msg = "정리학습을 완료하셨습니다.";
                const next_info = getIsNextLecture();
                const is_next = next_info[0];
                const next_data_type = next_info[1];
                // 다음수업이 있으면.
                if(is_next){
                    msg += "<br>확인을 누르시면 다음 강의로 이동합니다.<br>이동하시겠습니까?";
                }else{
                    // 모두 완료.종료
                    msg += "<br>모든 강의를 완료하셨습니다.<br>종료하시겠습니까?";
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

    let is_complete_interactive = false;
    let is_interactive_ready = false;
    window.addEventListener("message", function(event) {
        if (event.data === "interactiveComplete" || event.data === "The practice has ended.") {
            if(!is_complete_interactive){
                is_complete_interactive = true;
                studyVideoComplete();
            }
        }else if(event.data === "interactiveReady"){
            // 인터렉티브쪽에서 DOMContentLoaded 일때 값을 넘겨준다.
            is_interactive_ready = true;
        }
    }, false);


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
