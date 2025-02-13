@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '문제풀기')

@section('add_css_js')
<link href="{{ asset('css/reset.css?5') }}" rel="stylesheet">
@endsection

<!-- TODO: 학습끝내기 -->
<!-- TODO: 오답노트 이동하기 -->

{{-- 학생 컨텐츠 --}}
@section('layout_coutent')
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
        background: #E5FEC6;
        background-image: url('/images/quiz-bg.png');
        background-position: center;
        background-size: cover;
    }

    .video-container {
        max-height: 762px;
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

    .video-container {
        position: relative;
        width: 100%;
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

    .video-container span.subject-name {
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
        background: #fff;
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
        background: #DD4242;
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
        background: #99DD42;
        padding: 10px 32px;
        border-radius: 0px 12px 0px 0px;
    }

    .video-title span.subject-name {
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
        background: #99DD42;
    }

    .learning-list-body {
        padding: clamp(0.8rem, 1vw, 1.6rem);
        background: #fff;
        height: 50%;
        overflow-y: scroll;
        border-radius: 12px;
    }

    .learning-list-body ul {
        overflow-y: scroll;
    }

    .learning-list-body ul::-webkit-scrollbar {
        display: none;
    }

    .learning-list-body .learning-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .learning-list-body .learning-grid .learning-grid-item {
        background: #fff;
        border-radius: 12px;
        padding: clamp(0.4rem, 1vw, 0.8rem);
        border: 1px solid #E5E5E5;
        border-radius: 12px;
    }

    .learning-list-body .learning-grid .learning-grid-item .learning-grid-item-title p {
        font-size: clamp(1rem, 1vw, 1.2rem);
        font-weight: 600;
        color: #999999;
    }

    .learning-grid-item .learning-grid-item-img {
        text-align: end;
        margin-top: clamp(0.8rem, 1vw, 1.6rem);
    }
    .learning-grid-item .learning-grid-item-img svg{
        width: clamp(36px, 3vw, 52px);
        height: clamp(36px, 3vw, 52px);
    }
    .learning-list-body .learning-grid .learning-grid-item.complete .learning-grid-item-img svg circle {
        fill: #FF5065;
    }

    .learning-list-body .learning-grid .learning-grid-item.active {
        border: 1px solid #F6D584;
        background: #FFF6E0;
    }

    .video-cont-tab {
        display: flex;
        align-items: center;
    }

    /* 퀴즈 컨테이너 */
    .quiz-container {
        display: flex;
        align-items: center;
        flex-direction: column;
        background: #fff;
        height: 67vh;
        border-radius: 0px 0px 12px 12px;
        overflow: hidden;
    }

    .quiz-wrap {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .quiz-container .quiz-cont {
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        flex-direction: column;
        justify-content: center;
    }

    .quiz-wrap .quiz-img {
        background: #F6C5C5;
        aspect-ratio: 4;
        width: 70%;
        border-radius: 12px;
    }

    .quiz-wrap .quiz-question {
        padding: 34px 0;
        width: 70%;
        font-size: 28px;
        font-weight: 600;
        text-align: center;
    }

    .quiz-answer ul.quiz-answer-list {
        display: flex;
        align-items: center;
        gap: 24px;
    }


    .quiz-answer ul.quiz-answer-list li.quiz-answer-item .quiz-answer-item-img {
        width: 160px;
        height: 160px;
        border-radius: 12px;
        box-shadow: inset 0px 0px 0px 4px transparent;
        background: #f9f9f9;

    }

    .quiz-answer ul.quiz-answer-list li.quiz-answer-item.active .quiz-answer-item-img {
        box-shadow: inset 0px 0px 0px 4px #FFC747;
    }

    .quiz-answer ul.quiz-answer-list li.quiz-answer-item span {
        margin-top: 12px;
        font-size: 20px;
        font-weight: 600;
        text-align: center;
        display: block;

    }

    .quiz-answer-arrow button {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #FFF6E0;
        border: none;
        outline: none;
        transition: all 0.3s ease;
    }

    .quiz-answer-arrow button:disabled svg path {
        opacity: 0.5;
    }

    .quiz-answer-arrow button:hover {
        background: #F6D584;
    }

    .quiz-answer-arrow button:hover svg path {
        stroke: #fff;
    }

    .quiz-answer-arrow button.quiz-answer-arrow-prev {
        position: absolute;
        left: 2%;
        top: 50%;
        transform: translateY(-50%);
    }

    .quiz-answer-arrow button.quiz-answer-arrow-next {
        position: absolute;
        right: 2%;
        top: 50%;
        transform: translateY(-50%);
    }

    .quiz-answer-arrow button.quiz-answer-arrow-next:disabled,
    .quiz-answer-arrow button.quiz-answer-arrow-prev:disabled {
        display: none;
    }

    .circle-effect {
        position: absolute;
        top: 50%;
        left: 25px;
        transform: translate(-50%, -50%) scale(1);
    }

    .star-effect {
        position: absolute;
        top: 50%;
        left: 25px;
        transform: translate(-50%, -50%);
        width: 120px;
    }

    .triangle-effect {
        position: absolute;
        top: 30%;
        left: 30px;
        transform: translate(-50%, -50%);
        width: 120px;
    }

    .quiz-answer-view .quiz-answer-item {
        display: flex;
        align-items: center;
        gap: 12px;
        cursor: pointer;
    }

    .quiz-answer-view {
        display: flex;
        gap: 8px;
        flex-direction: column;
    }

    .quiz-question-view {
        position: relative;
    }

    .quiz-question-view .quiz-question {
        font-size: 28px;
        font-weight: 600;
        padding: 10px 12px;
        /* border: solid 1px; */
        min-height: 100px;
        display: flex;
        justify-content: center;
        flex-direction: column;
        line-height: 1.4;
    }

    .quiz-answer-view .quiz-answer-item {
        padding: 10px 12px;
        border-radius: 12px;
    }

    .quiz-answer-view .quiz-answer-item span {
        color: #999;
        font-size: 18px;
    }

    .quiz-answer-view div.quiz-answer-item span.text-answer {
        color: #FF5065;
    }

    .quiz-answer-view .quiz-answer-item .quiz-answer-item-num {
        display: flex;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #F9F9F9;
        align-items: center;
        justify-content: center;
        padding-right: 2px;
        padding-top: 2px;
    }

    .quiz-answer-view .quiz-answer-item.active {
        background: #FFC747;
    }

    .quiz-answer-view .quiz-answer-item.active .quiz-answer-item-num {
        color: #FFC747;
    }

    .quiz-answer-view .quiz-answer-item.active span {
        color: #fff;
    }

    .quiz-answer-view .quiz-answer-item.wrong {
        background: #999999;
    }

    .quiz-answer-view .quiz-answer-item.wrong .quiz-answer-item-num {
        color: #222;
    }

    .quiz-answer-view .quiz-answer-item.wrong span {
        color: #222;
    }


    .quiz-answer-commentary {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 70%;
        height: 80%;
        text-align: left;
        border: 1px solid #E5E5E5;
        background: #FFF6E0;
        border-radius: 12px;
        font-size: 18px;
        z-index: 100;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .quiz-answer-commentary .quiz-commentary-title {
        background: #FFC747;
        padding: 22px 0;
        width: 100%;
    }

    .quiz-answer-commentary .quiz-commentary-answer {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: clamp(1px, 2vw, 20px) 0;
    }

    .quiz-answer-commentary .quiz-commentary-answer span {
        display: inline-block;
        background: #FFF;
        padding: 8px 20px;
        border-radius: 50px;
        font-size: 20px;
        font-weight: 600;
    }

    .quiz-answer-commentary .quiz-commentary-answer p {
        font-size: 20px;
        font-weight: 600;
    }

    .quiz-answer-commentary .quiz-commentary-wrap {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .quiz-answer-commentary .quiz-commentary-wrap .quiz-commentary-explanation {
        background: #FFF;
        width: 90%;
        height: 70%;
        border-radius: 12px;
    }

    .quiz-answer-commentary .quiz-commentary-wrap .quiz-commentary-explanation .quiz-commentary-explanation-text {
        padding: 24px;
        height: 100%;
        overflow-y: auto;
    }

    .quiz-answer-commentary .quiz-commentary-wrap .quiz-commentary-explanation .quiz-commentary-explanation-text::-webkit-scrollbar {
        width: 10px;
    }

    .quiz-answer-commentary .quiz-commentary-wrap .quiz-commentary-explanation .quiz-commentary-explanation-text::-webkit-scrollbar-thumb {
        background: #FFC747;
        border-radius: 12px;
    }

    .quiz-answer-commentary .quiz-commentary-wrap .quiz-commentary-btn {
        height: 30%;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 12px 0;
    }

    .quiz-answer-commentary .quiz-commentary-wrap .quiz-commentary-btn button {
        padding: 12px;
        width: 20%;
    }

    .quiz-answer-commentary .quiz-commentary-wrap .quiz-commentary-btn .commentary-video {
        background: #FFC747;
    }

    .quiz-answer-commentary .quiz-commentary-wrap .quiz-commentary-btn .back-btn {
        background: #FF5065;
        color: #fff;
    }



    .score-result-body {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: clamp(1rem, 1vw, 1.5rem);
        background: #fff;
    }

    .score-result-body .score-result-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #F9F9F9;
        padding: 12px 20px;
        border-radius: 8px;
    }

    .score-result-body .score-result-title .subject-name {
        font-size: clamp(1rem, 1vw, 1.5rem);
        color: #999999;
        font-weight: 600;
    }

    .score-result-body .score-result-title .subject-score {
        font-size: 18px;
        font-weight: 600;
    }

    .try-text {
        position: relative;
        z-index: 100;
    }

    .try-msg {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 40%;
        transform: translate(-50%, -50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #FFF6E0;
        padding: 24px;
        border-radius: 12px;
        font-size: 18px;
        margin-top: 24px;
        z-index: 100;
    }

    .try-msg .try-msg-text {
        font-size: 20px;
        font-weight: 600;
        padding-top: 12px;
    }
    button.memo-toggle-btn{
        background-color: #FFF6E0;
        border-radius: 50px;
        padding: 2px;
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
    }
    button.memo-toggle-btn span.memo-img-wrap{
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #FFFFFF;
        padding: 8px;
        border-radius: 50px;
    }
    button.memo-toggle-btn span.memo-text{
        font-size: 20px;
        font-weight: 600;
        padding-left: 12px;
        padding-right: 20px;
        color: #222222;
    }
    canvas {
        position: fixed;
        top: 0;
        left: 0;
        height:100%;
        width:100%;
        border: 6px solid #99dd42;
        background-color: #0000007a;
        z-index: 100;
        border-radius: 0 0 12px 12px;
    }
    .canvas-eraser {
        display: flex;
        flex-direction: column;
        gap: 12px;
        position: relative;
    }
    .canvas-eraser li{
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        opacity: 0;
    }
    .canvas-eraser.active li{
        opacity: 1;
        transform: translateY(0) !important;
    }
    .canvas-eraser .canvas-close.tool-box-btn span.img-wrap{
        background-color: #FF5065;
    }
    .canvas-eraser .tool-box-btn{
        all: unset;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        color: #fff;
        font-size: 24px;
        font-weight: 600;
    }
    .canvas-eraser .tool-box-btn.active span.img-wrap{
        background-color: #FFC747;
    }
    .canvas-eraser .tool-box-btn span.img-wrap{
        background-color: #FFFFFF;
        width: 72px;
        height: 72px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .quiz-question-wrap{
        width: 100%;
    }
    .quiz-view-answer-wrap .quiz-question-view{
        flex: 1;
        padding: 0 4% 0 10%;
        overflow-y: auto;
    }
    .quiz-view-answer-wrap .quiz-question-view::-webkit-scrollbar{
        width: 10px;
        background-color: #F9F9F9;
    }
    .quiz-view-answer-wrap .quiz-question-view::-webkit-scrollbar-thumb{
        background: #FFC747;
        border-radius: 12px;
    }
    .quiz-view-answer-wrap .quiz-question-view .quiz-question-view-title{
        font-size: 20px;
        font-weight: 600;
        /* border-left: 1px solid #222; */
        /* border-right: 1px solid #222; */
        /* border-top: 1px solid #222; */
        text-align: center;
        padding: 12px 0;
        background-color: #FFC747;
    }
    .quiz-view-answer-wrap .quiz-answer-wrap{
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 50%;
        padding: 0 5%;
    }
    .quiz-answer-question .tquiz-questionry-text{
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 24px;
        line-height: 1.4;
    }
    .quiz-view-answer-wrap{
        display: flex;
        flex-direction: row;
        gap: 6px;
        width: 100%;
    }
    .middle-line{
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 1px;
        height: 100%;
        background-color: #E5E5E5;
    }

    .video-error-message {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        background: #FFC747;
        flex-direction: column;
        z-index: 7;
    }

    .video-error-message img{
        width: 50%;
    }

    @media (max-width: 1400px) {
        .video-title {
            padding: 6px 16px;
        }

        .aside-right {
            padding-top: 56px;
        }

        .video-container .video-cont-tab li {
            padding: 12px 16px;
            width: auto;
            font-size: 16px;
        }

        .video-container button {
            transform: scale(0.9);
        }

        #lectureVideo {
            aspect-ratio: auto;
        }

        .quiz-wrap .quiz-question {
            font-size: 20px;
        }

        .quiz-answer ul.quiz-answer-list li.quiz-answer-item .quiz-answer-item-img {
            width: 90px;
            height: 90px;
        }
        .canvas-eraser{
            transform: scale(0.8);
        }
        .btn-wrap{
            display: flex;
            flex-direction: row;
            gap: 12px;
        }
        .btn-wrap button{
            margin-bottom: 0 !important;
            height: 56px !important;
        }
        .h-72{
            height: 56px !important;
        }
        .quiz-answer-commentary .quiz-commentary-wrap .quiz-commentary-btn button{
            width: 30% !important;
        }
        .quiz-answer-commentary{
            height: 90% !important;
        }
        .quiz-container {
                height: calc(100dvh - 150px);
        }
    }
    @media (max-width: 992px) {
        .quiz-container{
            height: auto !important;
            aspect-ratio: auto;
        }
    }
</style>

<input type="hidden" data-main-student-lecture-detail-seq value="{{ $st_lecture_detail_seq}}">
<input type="hidden" data-main-lecture-seq value="{{$lecture_seq}}">
<input type="hidden" data-main-lecture-detail-seq value="{{$lecture_detail_seq}}">
<input type="hidden" data-main-exam-lecture-detail-seq value="{{$exam_lecdture_detail_seq}}">
<input type="hidden" data-main-student-exam-seq="">
<div class="col mx-0 row position-relative">
    <div class="col-lg col-lg-9 pe-0 ps-0">
        {{-- 동영상 시청 --}}
        <section data-section-study-video="top" class="">
            {{-- video 자리 --}}
            <div class="w-100 overflow-hidden video-container" style="" id="videoPtDiv">
                <ul class="video-cont-tab cursor-pointer">
                    <li onclick="studyVideoClickTopTab(this)" data-type=""
                        class="beginning"><span>1</span> 준비하기</li>
                    <li onclick="studyVideoClickTopTab(this)"
                        class="concept " data-type="concept_building"
                        {{$top_menutabs->where('lecture_detail_type', 'concept_building')->count() > 0 ? '':'hidden' }}><span>2</span> 개념다지기</li>
                    <!-- summarizing -->
                    <li onclick="studyVideoClickTopTab(this)"
                        class="review" data-type="summarizing"
                        {{$top_menutabs->where('lecture_detail_type', 'summarizing')->count() > 0 ? '':'hidden' }} ><span>3</span> 정리학습</li>
                    <li onclick="studyVideoClickTopTab(this)"
                        class="questions active" data-type="exam_solving"
                        {{$top_menutabs->where('lecture_detail_type', 'exam_solving')->count() > 0 ? '':'hidden' }}><span>4</span> 문제풀기</li>
                    <li onclick="studyVideoClickTopTab(this)"
                        class="assessment" data-type="unit_test"
                        {{$top_menutabs->where('lecture_detail_type', 'unit_test')->count() > 0 ? '':'hidden' }}><span>5</span> 단원평가</li>
                </ul>
            </div>
        </section>
        <section class="video-container">
            <div class="video-title d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <span class="subject-name">{{$lecture_subject["lecture_name"]}}</span>
                    <span class="lecture-name text-b-24px">{{$top_menutabs->where('id', $lecture_detail_info["lecture_detail_seq"])->first()["lecture_detail_description"]}} {{$lecture_detail_info["lecture_detail_name"]}}</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button class="memo-toggle-btn" onclick="oepnPaintBoardToggle(canvasSetting);">
                        <span class="memo-img-wrap">
                            <img src="{{asset('images/메모하기.svg')}}" width="32">
                        </span>
                        <span class="memo-text">메모하기</span>
                    </button>
                    <button class="btn p-0 hart-btn" data-btn-is-like>
                        <div class="hart-btn-img">
                            <!-- <img src="{{ asset('images/hart_icon.svg') }}" width="28" data-is-like="red"> -->
                            <img src="{{ asset('images/gray_hart_icon.svg') }}" width="28" data-is-like="gray">
                        </div>
                    </button>
                </div>
            </div>
        </section>
        <section class="quiz-container position-relative">
            <div class="quiz-cont">
                <div class="quiz-question-wrap">
                    <input type="hidden" data-exam-seq>
                    <input type="hidden" data-exam-num>
                    <input type="hidden" data-exam-type>
                    <div class="quiz-view-answer-wrap">
                        <div class="quiz-question-view d-none">
                            <div class="div-shadow-style rounded-3 overflow-hidden ">
                                <div class="quiz-question-view-title">보기</div>
                                <div class="quiz-question"></div>
                            </div>
                        </div>
                        <div class="quiz-answer-wrap">
                            <div class="quiz-answer-question">
                                <div class="tquiz-questionry-text"></div>
                            </div>
                            <div class="quiz-answer-view"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="quiz-answer-arrow">
                <button class="quiz-answer-arrow-prev" data-arrow-prev data-arrow="-1" disabled>
                    <svg class="me-1" width="15" height="26" viewBox="0 0 15 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M11.8803 22.8534L3.65669 14.6916C2.81077 13.852 2.80563 12.4857 3.64522 11.6398L11.8803 3.34277" stroke="#FFC747" stroke-width="4.95238" stroke-miterlimit="10" stroke-linecap="round" />
                    </svg>
                </button>
                <button class="quiz-answer-arrow-next" data-arrow-next data-arrow="1">
                    <svg class="ms-1" width="15" height="26" viewBox="0 0 15 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.11971 22.8534L11.3433 14.6916C12.1892 13.852 12.1944 12.4857 11.3548 11.6398L3.11971 3.34277" stroke="#FFC747" stroke-width="4.95238" stroke-miterlimit="10" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
            <div class="memo-canvas-wrap" hidden>
                <canvas id="memoCanvas" height="600px" width="1200px"></canvas>
                <div class="canvas-panel position-absolute" style="left:20px;top: 50%;z-index: 101;transform: translateY(-50%);">
                    <ul class="canvas-eraser">
                        <li>
                            <button href="#" class="selected tool-box-btn active" onclick="pen(event, this)">
                                <span class="img-wrap">
                                    <img src="{{asset('images/pen.svg')}}" width="42">
                                </span>
                                <span> 쓰기</span>
                            </button>
                        </li>
                        <li>
                            <button href="#" class="selected tool-box-btn" onclick="del(event, this)">
                                <span class="img-wrap">
                                    <img src="{{asset('images/eraser.svg')}}" width="42">
                                </span>
                                <span> 지우개</span>
                            </button>
                        </li>
                        <!-- <li><button href="#" onclick="prevCanvas(event)"><i class="fas fa-undo-alt"></i>되돌리기</button></li> -->
                        <li>
                            <button href="#" class="tool-box-btn" onclick="clearCanvas(event)">
                                <span class="img-wrap">
                                    <img src="{{asset('images/refresh.svg')}}" width="42">
                                </span>
                                <span> 다시쓰기</span>
                            </button>
                        </li>
                        <li>
                            <button type="button" onclick="canvasCloseBtn(event)" class="canvas-close tool-box-btn">
                                <span class="img-wrap">
                                    <img src="{{asset('images/svg/close-whtie.svg')}}" width="42">
                                </span>
                                <span> 닫기</span>
                            </button>
                        </li>
                    </ul>
                    <ul class="canvas-width">
                        <li><a href="#" class="selected" onclick="setCavasWidth(event,this,1)"><span></span></a></li>
                        <li><a href="#" onclick="setCavasWidth(event,this,5)"><span></span></a></li>
                        <li><a href="#" onclick="setCavasWidth(event,this,10)"><span></span></a></li>
                        <li><a href="#" onclick="setCavasWidth(event,this,30)"><span></span></a></li>
                    </ul>
                    <a href="#" class="canvas-hide-btn" onclick="hideCanvas(event)"><i class="fas fa-times"></i></a>
                    <ul class="canvas-color">
                        {{-- <li><button href="#" class="btn bg-black selected" onclick="setCavasColor(event,this)"><i class="fas fa-palette" style="color: black"></i></button></li>
                        <li><button href="#" class="btn bg-danger"onclick="setCavasColor(event,this)"><i class="fas fa-palette" style="color:red"></i></button></li> --}}
                        <li><a href="#" onclick="setCavasColor(event,this)"><i class="fas fa-palette"></i></a></li>
                        <li><a href="#" onclick="setCavasColor(event,this)"><i class="fas fa-palette"></i></a></li>
                        <li><a href="#" onclick="setCavasColor(event,this)"><i class="fas fa-palette"></i></a></li>
                        <li><a href="#" onclick="setCavasColor(event,this)"><i class="fas fa-palette"></i></a></li>
                        <li><a href="#" onclick="setCavasColor(event,this)"><i class="fas fa-palette"></i></a></li>
                        <li><a href="#" onclick="setCavasColor(event,this)"><i class="fas fa-palette"></i></a></li>
                        <li><a href="#" onclick="setCavasColor(event,this)"><i class="fas fa-palette"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="middle-line"></div>
            <div class="video-error-message" style="display: none;">
                <img src="{{asset('images/svg/all_Character.svg')}}">
                <div class="text-b-24px">선생님 문제가 없어요.</div>
            </div>
        </section>
    </div>
    <div class="quiz-result" style="display: none;">
        <!-- 결과가 동적으로 추가됩니다 -->
    </div>
    <div class="aside-right learning-list col-lg-3 d-flex flex-column justify-content-between ps-4 pe-2">
        <div class="learning-wrap">
            <div class="learning-list-header">
                <div class="p-4">
                    <div class="text-b-24px" data-exam-list-str>문제 목록</div>
                </div>
            </div>
            <div class="learning-list-body">
                <ul class="learning-grid">
                </ul>
            </div>
        </div>
        <div class="btn-wrap">
            <button class="text-b-24px btn btn-danger w-100 rounded-4" style="height:72px" onclick="studyVideoExit();">
                종료하기
            </button>
        </div>

    </div>

</div>

{{--  모달 / 학습 결과 --}}
<div class="modal fade " id="modal_lecture_result" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-modal="true" role="dialog">
    {{-- <div class="modal-character-wrap position-absolute w-100 d-flex justify-content-center align-items-center"></div> --}}
    <div class="modal-dialog rounded modal-dialog-centered">
        <div class="modal-content border-none rounded modal-shadow-style rounded-4">
            <div class="modal-header border-bottom-0 primary-bg-light rounded-top-4 position-relative">
                <h1 class="modal-title text-b-24px" id="" > 학습 결과 </h1>
                <button type="button" class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close" style="width: 28px; height: 28px;"></button>
                <img class="modal-character position-absolute top-0 start-50 translate-middle" src="{{asset('images/character_lecture_result.svg')}}" width="150">
            </div>
            <div class="modal-body p-4">
                 <!-- 결과  -->
                <div class="text-sb-20px rounded-4">
                    <div class="bg-danger all-center p-3 rounded-top-4">
                        <span class="text-white w-50 text-center">기본 문제</span>
                        <span class="text-white w-50 text-center">유사 문제</span>
                    </div>
                    <div class="d-flex scale-bg-gray_01 rounded-bottom-4">
                        <div class="d-flex p-3 p-xxl-4 flex-column flex-grow-1 w-50 border-end">
                            <div class="px-0 scale-text-gray_05 h-center justify-content-center mb-1">
                                <span>맞힌 문제</span>
                                <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                                <span class="text-black" data-normal-cnt="correct">0 문제</span>
                            </div>
                            <div class="px-0 scale-text-gray_05 h-center justify-content-center">
                                <span>틀린 문제</span>
                                <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                                <span class="text-black" data-normal-cnt="wrong">0 문제</span>
                            </div>
                        </div>
                        <div class="d-flex p-3 p-xxl-4 flex-column flex-grow-1 w-50">
                            <div class="px-0 scale-text-gray_05 h-center justify-content-center mb-1">
                                <span>맞힌 문제</span>
                                <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                                <span class="text-black" data-similar-cnt="correct">0 문제</span>
                            </div>
                            <div class="px-0 scale-text-gray_05 h-center justify-content-center">
                                <span>틀린 문제</span>
                                <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                                <span class="text-black" data-similar-cnt="wrong">0 문제</span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="text-sb-20px rounded-4 mt-4">
                    <div class="bg-danger all-center p-3 rounded-top-4">
                        <span class="text-white">유사 문제</span>
                    </div>
                    <div class="row scale-bg-gray_01 p-4 rounded-bottom-4">
                        <div class="px-0 scale-text-gray_05 h-center justify-content-end col">
                            <span>맞힌 문제</span>
                            <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                            <span class="text-black" data-similar-cnt="correct"> - </span>
                        </div>
                        <div class="px-0 scale-text-gray_05 h-center justify-content-start col ps-3">
                            <span>틀린문제</span>
                            <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                            <span class="text-black" data-similar-cnt="wrong"> - </span>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="text-sb-20px rounded-4 mt-3">
                    <div class="bg-danger all-center p-3 rounded-top-4">
                        <span class="text-white">도전 문제</span>
                    </div>
                    <div class="row scale-bg-gray_01 p-4 rounded-bottom-4">
                        <div class="px-0 scale-text-gray_05 h-center justify-content-end col">
                            <span>맞힌 문제</span>
                            <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                            <span class="text-black" data-challenge-cnt="correct"> - </span>
                        </div>
                        <div class="px-0 scale-text-gray_05 h-center justify-content-start col ps-3">
                            <span>틀린문제</span>
                            <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                            <span class="text-black" data-challenge-cnt="wrong"> - </span>
                        </div>
                    </div>
                </div> --}}

                <div class="text-sb-20px rounded-4 mt-3">
                    <div class="bg-danger all-center p-3 rounded-top-4">
                        <span class="text-white w-50 text-center">도전 문제</span>
                        <span class="text-white w-50 text-center">도전 유사</span>
                    </div>
                    <div class="d-flex scale-bg-gray_01 rounded-bottom-4">
                        <div class="d-flex p-3 p-xxl-4 flex-column flex-grow-1 w-50 border-end">
                            <div class="px-0 scale-text-gray_05 h-center justify-content-center mb-1">
                                <span>맞힌 문제</span>
                                <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                                <span class="text-black" data-challenge-cnt="correct">0 문제</span>
                            </div>
                            <div class="px-0 scale-text-gray_05 h-center justify-content-center">
                                <span>틀린 문제</span>
                                <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                                <span class="text-black" data-challenge-cnt="correct"> 0 문제 </span>
                            </div>
                        </div>
                        <div class="d-flex p-3 p-xxl-4 flex-column flex-grow-1 w-50">
                            <div class="px-0 scale-text-gray_05 h-center justify-content-center mb-1">
                                <span>맞힌 문제</span>
                                <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                                <span class="text-black" data-similar-cnt="correct">0 문제</span>
                            </div>
                            <div class="px-0 scale-text-gray_05 h-center justify-content-center">
                                <span>틀린 문제</span>
                                <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="mx-2">
                                <span class="text-black" data-similar-cnt="wrong">0 문제</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row gap-2 mt-3">
                    <button class="col text-b-24px btn rounded-4 btn-primary-y h-72" onclick="finishLearning()">
                        학습끝내기
                    </button>
                    <button class="col text-b-24px btn rounded-4 btn-primary-y h-72" onclick="goWrongNote();">
                        오답노트 풀러가기
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="form_post" action="" target="_self" hidden>
    @csrf
    <input type="hidden" name="st_lecture_detail_seq" value="">
    <input type="hidden" name="lecture_detail_seq" value="">
    <input type="hidden" name="lecture_seq" value="">
</form>

<script>
// TODO: 컴퓨터 접속시 만약 콘솔을 열게 되었을때, 답을 학생이 직접 볼수 있어 확인요함.
//
// 기본문제
/* prettier-ignore-start */
const questionContainer = document.querySelector(".quiz-question-view .quiz-question");
const quizQuestionView = document.querySelector(".quiz-question-view");
const quizViewAnswerWrap = document.querySelector('.quiz-view-answer-wrap');
const answerContainer = document.querySelector(".quiz-answer-view");
const quizAnswerQuestion = document.querySelector(".quiz-answer-question");
const learningGrid = document.querySelector(".learning-grid");
const quizAnswerArrow = document.querySelector(".quiz-answer-arrow");
const arrowPrev = document.querySelector(".quiz-answer-arrow-prev");
const arrowNext = document.querySelector(".quiz-answer-arrow-next");
const btnWrap = document.querySelector(".btn-wrap");
const quizContent = document.querySelector(".quiz-cont");
const quizQuestionWrap = document.querySelector(".quiz-question-wrap");
const middleLine = document.querySelector(".middle-line");
const quizContainer = document.querySelector(".quiz-container");
const tquizQuestionryText = document.querySelector('.tquiz-questionry-text');
let maxNext = 0;
let status;

document.addEventListener('DOMContentLoaded', function(){

    quizResult();
    studentExamInsertOrUpdate();
    arrowPrev.addEventListener('click', async function() {
        await getQuizDetail(arrowPrev.dataset.arrow);
        updateArrow.prev(arrowPrev.dataset.arrow);
    });
    arrowNext.addEventListener('click', async function() {
        await getQuizDetail(arrowNext.dataset.arrow);
        updateArrow.next(arrowNext.dataset.arrow);
    });
});

const updateArrow = {
    prev: function(examNum) {
        arrowPrev.disabled = Number(examNum) - 1 == 0 ? true : false;
        arrowPrev.dataset.arrow = Number(examNum) - 1;
        arrowNext.dataset.arrow = Number(examNum) + 1;
        arrowNext.disabled = arrowNext.dataset.arrow == maxNext + 1 ? true : false;
    },
    next: function(examNum) {
        arrowPrev.disabled = Number(examNum) == 1;
        arrowPrev.dataset.arrow = Number(examNum) - 1;
        arrowNext.dataset.arrow = Number(examNum) + 1;
        arrowNext.disabled = arrowNext.dataset.arrow >= maxNext + 1 ? true : false;
    }
};

//문제풀기 시작
function studentExamInsertOrUpdate(){
    const st_lecture_detail_seq = document.querySelector('[data-main-student-lecture-detail-seq]').value;
    const lecture_seq = document.querySelector('[data-main-lecture-seq]').value;
    const lecture_detail_group = document.querySelector('[data-main-lecture-detail-seq]').value;
    const exam_num = document.querySelector('[data-exam-num]').value;
    const page = "/student/study/quiz/start";
    const parameter = {
        lecture_seq: lecture_seq,
        lecture_detail_group: lecture_detail_group,
        student_lecture_detail_seq: st_lecture_detail_seq,
        exam_num: exam_num,
    };
    queryFetch(page, parameter, function(result){
        try{
            if(result.resultCode == 'success'){
                status = result.student_exam.exam_status;
                console.log(status)
            }
        }catch(e){
            console.log(e);
        }
    });
}

//문제 최종단계
function getQuizDetail(number = 1, examType = 'normal'){
    const st_lecture_detail_seq = document.querySelector('[data-main-student-lecture-detail-seq]').value;
    const lecture_seq = document.querySelector('[data-main-lecture-seq]').value;
    const lecture_detail_group = document.querySelector('[data-main-lecture-detail-seq]').value;
    const currentIndex = document.querySelectorAll('.learning-grid li');
    const exam_num = number;
    const page = "/student/study/quiz/detail/select";
    const parameter = {
        st_lecture_detail_seq: st_lecture_detail_seq,
        lecture_detail_group: lecture_detail_group,
        exam_num: exam_num,
        lecture_seq: lecture_seq,
        exam_type: examType,
    };

    queryFetch(page, parameter, function(result){
        try{
            if(result.resultCode == 'success'){
                const exam_detail = result.exam_detail;
                const student_answers = result.student_answers;
                console.log(result);
                document.querySelector('[data-exam-seq]').value = exam_detail.exam_seq;
                document.querySelector('[data-exam-num]').value = exam_detail.exam_num;
                document.querySelector('[data-exam-type]').value = examType;
                tquizQuestionryText.textContent = `${exam_detail.exam_num}번 ${exam_detail.questions}`;
                getSamplasQuiz(exam_detail.samples, student_answers?.student_answer);
                commentry(exam_detail.questions2);
                currentIndex.forEach((item, index) => {
                    item.classList.remove('active');
                });
                currentIndex[exam_detail.exam_num - 1]?.classList.add('active');
            }
        }catch(e){
            console.log(e);
        }
    });
}
//문제 리스트 생성
function getSamplasQuiz(list, student_answer){
    let samples = list.split(';');
    answerContainer.innerHTML = ''; // Clear previous answers
    samples.forEach((item, index) => {
    const quizAnswer = document.createElement('div');
    const quizAnswerItemNum = document.createElement('span');
    const quizAnswerItemContent = document.createElement('span');
    quizAnswer.classList.add('quiz-answer-item');
    quizAnswerItemNum.classList.add('quiz-answer-item-num');
    quizAnswerItemContent.classList.add('quiz-answer-item-text');
    quizAnswerItemNum.textContent = `${index + 1}`;
    quizAnswerItemContent.textContent = item;
    quizAnswer.appendChild(quizAnswerItemNum);
    quizAnswer.appendChild(quizAnswerItemContent);
        if (student_answer == index + 1) {
            quizAnswer.classList.add('active');
            quizSubmit(arrowNext.dataset.arrow == maxNext + 1, status);
        }else{
            quizAnswer.classList.remove('active');
        }
        quizAnswer.addEventListener('click', function(student_answer) {
            const learningGridItem = document.querySelectorAll('.learning-grid-item');
            const exam_num = document.querySelector('[data-exam-num]').value;
            if (quizAnswer.classList.contains('active')) {
                quizAnswer.classList.remove('active');
                learningGridItem[exam_num - 1].classList.remove('complete');
                quizInsertOrUpdate(null);
                if(exam_num == maxNext){
                    quizSubmit(false, status);
                }
            } else {
                const allAnswers = document.querySelectorAll('.quiz-answer-item');
                allAnswers.forEach(answer => answer.classList.remove('active'));
                quizAnswer.classList.add('active');
                learningGridItem[exam_num - 1].classList.add('complete');
                quizInsertOrUpdate(index + 1);
                quizSubmit(arrowNext.dataset.arrow == maxNext + 1, status);
            }
        });
        answerContainer.appendChild(quizAnswer);
    });
}

function firstQuizSelect(currentIndex = 1, statusExam){
    const st_lecture_detail_seq = document.querySelector('[data-main-student-lecture-detail-seq]').value;
    const lecture_seq = document.querySelector('[data-main-lecture-seq]').value;
    const lecture_detail_group = document.querySelector('[data-main-lecture-detail-seq]').value;
    const page = "/student/study/quiz/select";
    const parameter = {
        st_lecture_detail_seq: st_lecture_detail_seq,
        lecture_detail_group: lecture_detail_group,
        lecture_seq: lecture_seq,
    };
    queryFetch(page, parameter, function(result){
        try{
            if(result.resultCode == 'success'){
                let results_exams = [];
                if(result.exam_detail.length > 0 && result.results_exam){
                    results_exams = result.results_exam
                        .filter(item => item.student_answer !== null)
                        .map((item, index) => {
                            return item.student_answer;
                        });
                }
                updateLearningGrid(result.exam_detail, currentIndex, results_exams.length > 0 ? results_exams : [], statusExam);
                maxNext = result.exam_detail.length;
                console.log(result);
                console.log(result?.results_exam);
                console.log(result.exam_detail[currentIndex]);

                if(results_exams.length == 0){
                    getQuizDetail();
                }else{
                    getQuizDetail(results_exams.length);
                }
            }
        }catch(e){
            console.log(e);
        }
    });
}

//채점하기
function quizSubmit(boolen, status){
    const btnWrap = document.querySelector(".btn-wrap");
    const checkButton = document.createElement("button");
    checkButton.className = "check-button text-b-24px btn btn-danger w-100 rounded-4 mb-2";
    checkButton.style.height = "72px";
    checkButton.innerText = "채점하기";
    checkButton.addEventListener('click', function(){
        studentExamInsertOrUpdate('submit');
    });
    if(boolen && status == 'study'){
        btnWrap.prepend(checkButton);
    }else{
        document.querySelector('.check-button')?.remove();
    }

}

function quizSubmitEvent(status){
    if(status == 'study'){
        document.querySelector('.check-button').remove();
    }
}

// 보기 출력
function commentry(currentQuestion) {
    const tryText = document.createElement('div');
    const tryText2 = document.createElement('div');
    const tryImg = document.createElement('img');
    questionContainer.innerHTML = '';

    const questionLengt = currentQuestion?.replace(/\[(.*?)\]/g, '').length == null ? 0 : currentQuestion.replace(/\[(.*?)\]/g, '').length;

    if(questionLengt == 0){
        quizViewAnswerWrap.classList.add('justify-content-center');
        quizQuestionView.classList.add('d-none');
        middleLine.classList.add('d-none');
        answerContainer.style.paddingRight = '';
        return;
    }else{
        quizViewAnswerWrap.classList.remove('justify-content-center');
        quizQuestionView.classList.remove('d-none');
        middleLine.classList.remove('d-none');
        if (questionLengt < 6) {
            questionContainer.style.fontSize = '80px';
            questionContainer.style.textAlign = 'center';
            quizQuestionView.children[0].style.height = '94%';
            questionContainer.style.height = '80%';
            answerContainer.style.paddingRight = '20%';
            tryText.style.lineHeight = '1.5';
            tryText.textContent = `${currentQuestion}`;
        } else {
            questionContainer.style = '';
            questionContainer.classList.add('text-sb-18px');
            quizQuestionView.children[0].style.height = '';
            answerContainer.style.paddingRight = '';
            if (questionLengt < 30) {
                questionContainer.style.textAlign = 'center';
            } else {
                console.log(questionLengt);
            }
            answerContainer.style.paddingRight = '20%';
            tryText.style.lineHeight = '1.5';
            tryText.innerHTML = currentQuestion.replace(/\n/g, '<br>').replace(/^<br>/, '');
        }
    }
    questionContainer.prepend(tryText);
}

function quizResult(){
    const st_lecture_detail_seq = document.querySelector('[data-main-student-lecture-detail-seq]').value;
    const lecture_seq = document.querySelector('[data-main-lecture-seq]').value;
    const lecture_detail_group = document.querySelector('[data-main-lecture-detail-seq]').value;
    const page = "/student/study/quiz/select";
    const parameter = {
        st_lecture_detail_seq: st_lecture_detail_seq,
        lecture_detail_group: lecture_detail_group,
        lecture_seq: lecture_seq,
    };
    queryFetch(page, parameter, function(result){
        try{
            if(result.resultCode == 'success'){
                let results_exams = [];
                if(result.exam_detail && result.exam_detail.length > 0 && result.results_exam){
                    results_exams = result.results_exam
                        .filter(item => item.student_answer !== null)
                        .map((item, index) => {
                        return item.student_answer;
                    });
                    updateArrow.next(results_exams.length == 0 ? 1 : results_exams.length);
                    console.log(results_exams);
                }else{
                    updateArrow.next(1);
                    console.log("문제가 없습니다.");
                }
            }
        }catch(e){
            console.log(e);
        }
    });
}

// 채점전 문제목록
function updateLearningGrid(index, currentIndex, complete, status) {
    const exam_list_title_el = document.querySelector('[data-exam-list-str]');
    exam_list_title_el.innerText = '문제 목록';
    if(status == 'study'){
        learningGrid.innerHTML = index.map((item, index) => {
            return `
        <li class="learning-grid-item ${complete[index] != undefined ? 'complete' : ''} ${currentIndex == index + 1 ? 'active' : ''}">
            <div class="learning-grid-item-title">
                <p class="subject-name">${index + 1}번 문제</p>
            </div>
            <div class="learning-grid-item-img">
                <svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="26" cy="26" r="26" fill="#FFF6E0" />
                    <path d="M18 23.3918L23.0683 30.0034C23.5898 30.6837 24.6086 30.7023 25.1545 30.0414L34 19.333" stroke="white" stroke-width="3" stroke-linecap="round" />
                </svg>
            </div>
        </li>`
        }).join("");
    }else{
        learningGrid.innerHTML = index.map((item, idx) =>{
            return `
            <li class="learning-grid-item ${complete[idx] != undefined ? 'complete' : ''} ${currentIndex == idx + 1 ? 'active' : ''}">
                <div class="learning-grid-item-title">
                    <p class="subject-name">${idx + 1}번 문제</p>
                </div>
            </li>`
        } ).join("");
    }
}
//문제풀기 데이터 추가 또는 변경.
function quizInsertOrUpdate(answer){
    const st_lecture_detail_seq = document.querySelector('[data-main-student-lecture-detail-seq]').value;
    const lecture_seq = document.querySelector('[data-main-lecture-seq]').value;
    const lecture_detail_group = document.querySelector('[data-main-lecture-detail-seq]').value;
    const exam_num = document.querySelector('[data-exam-num]').value;
    const page = "/student/study/quiz/insert/or/update";
    const parameter = {
        st_lecture_detail_seq: st_lecture_detail_seq,
        lecture_detail_group: lecture_detail_group,
        lecture_seq: lecture_seq,
        exam_num: Number(exam_num),
        student_answer: answer,
    };
    queryFetch(page, parameter, function(result){
        try{
            if(result.resultCode == 'success'){
                console.log(result);
            }
        }catch(e){
            console.log(e);
        }
    });
}


//--------------------------------------------------- 그림판 ---------------------------------------------------
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll('.video-cont-tab.cursor-pointer li:not([hidden])').forEach((span, index) => {
        span.querySelector('span').textContent = index + 1;
    });
})

function canvasToolToggle(){
    const eraserItems = document.querySelectorAll('.canvas-eraser li');
    const itemHeight = eraserItems[0].clientHeight; // 각 li의 높이값 (픽셀 단위로 설정)
    const gap = 12; // 간격 (픽셀 단위)
    console.log(eraserItems);
    eraserItems.forEach((item, index) => {
        console.log();
        const reverseIndex = (eraserItems.length - 1) - index;
        const translateY = reverseIndex * (itemHeight + gap);
        item.style.transform = `translateY(${translateY}px)`;
    });

}
var pos = {
	drawable: false,
	del: 0,
	x: -1,
	y: -1,
};
var canvas, ctx;
var drawBackup = new Array();

let is_first_canvas = false;
function oepnPaintBoardToggle(callback){
    const div_canvas = document.querySelector('.memo-canvas-wrap');
    if(div_canvas.hidden){
        div_canvas.hidden = false;
        canvasToolToggle();
        if(!is_first_canvas){
            is_first_canvas = true;
            const rect = canvas.getBoundingClientRect();
            const width = rect.width;
            const height = rect.height;
            canvas.width = width;
            canvas.height = height;
        }
        callback();
    }else{
        div_canvas.hidden = true;
    }
}

// 화면 로드시 canvas 이벤트 적용
window.onload = function() {
    const div_canvas = document.querySelector('.memo-canvas-wrap');
    canvas = div_canvas.querySelector('canvas');
	// canvas가 지원되는 브라우저에서만 적용
	if (canvas && canvas.getContext) {
		ctx = canvas.getContext("2d");
	    ctx.lineCap = "round";
		canvas.addEventListener("mousedown", listener);
		canvas.addEventListener("mousemove", listener);
		canvas.addEventListener("mouseup", listener);
		canvas.addEventListener("mouseout", listener);

		canvas.addEventListener("touchstart", listener);
		canvas.addEventListener("touchmove", listener);
		canvas.addEventListener("touchend", listener);
		canvas.addEventListener("touchcancel", listener);
    }
}

function listener(event) {
	switch (event.type) {
		case "mousedown":
		case "touchstart":
			initDraw(event);
			break;

		case "mousemove":
		case "touchmove":
			if (pos.drawable) {
				draw(event);
			}
			break;

		case "mouseout":
		case "mouseup":
		case "touchend":
		case "touchcancel":
			finishDraw();
			break;
	}
}

// 그리기 시작
function initDraw(event) {
	saveCanvas();
	event.preventDefault();
	ctx.beginPath();
	pos.drawable = true;
	var coors = getPosition(event);
	pos.x = coors.X;
	pos.y = coors.Y;
	ctx.moveTo(pos.X, pos.Y);
}

// 그리기 & 지우기 (펜과 지우개 선택에 따라 달라짐)
function draw(event) {
	event.preventDefault();
	var coors = getPosition(event);

    // 그리기
	if (pos.del == 0) {
		ctx.lineTo(coors.X, coors.Y);

    // 지우기
	} else if (pos.del == 1) {
		var lineWidth = (ctx.lineWidth + 50);
 		ctx.strokeStyle = 'white'; // 지우는 영역을 빨간색 테두리로 표시
 		ctx.lineWidth = 1; // 테두리 두께 설정
		ctx.clearRect(coors.X-(lineWidth/2) , coors.Y-(lineWidth/2), lineWidth, lineWidth);
	}

	pos.x = coors.X;
	pos.y = coors.Y;
	ctx.stroke();
}

// 그리기 종료
function finishDraw() {
	pos.drawable = false;
	pos.x = -1;
	pos.y = -1;
}

// 마우스,터치 위치 반환
function getPosition(event) {
	var x = -1;
	var y = -1;
	if (event.type.startsWith("touch")) { // IE는 startsWith 미지원이므로 따로 정의필요
		 x = event.touches[0].pageX;
		 y = event.touches[0].pageY;
	} else {
		x = event.pageX;
		y = event.pageY;
	}
	// return {X: (x -$(canvas).offset().left), Y: (y - $(canvas).offset().top)};
    return {
        X: (x - canvas.getBoundingClientRect().left),
        Y: (y - canvas.getBoundingClientRect().top)
    };
}

// 현재 상태 저장
function saveCanvas() {
	drawBackup.push(ctx.getImageData(0, 0, canvas.width, canvas.height));
}

// 펜 선택
function pen(event, thisObj) {
	event.preventDefault();
	pos.del = 0;
}

// 지우개 선택
function del(event, thisObj) {
	event.preventDefault();
	pos.del = 1;
}

// 되돌리기
function prevCanvas(event) {
	event.preventDefault();
	if (drawBackup.length > 0) {
		ctx.putImageData(drawBackup.pop(), 0, 0);
	}
}

//닫기
function canvasCloseBtn(){
    const div_canvas = document.querySelector('.memo-canvas-wrap');
    const canvas_eraser = document.querySelector('.canvas-eraser');
        document.querySelectorAll('.tool-box-btn').forEach(button => {
        button.classList.remove('active');
    });
    canvas_eraser.classList.remove('active');
    canvas_eraser.querySelector('.tool-box-btn').classList.add('active');
    setTimeout(() => {
        div_canvas.hidden = true;
    }, 300);
}

// 모두 지우기
function clearCanvas(event) {
	event.preventDefault();
	ctx.clearRect(0, 0, canvas.width, canvas.height);
	ctx.beginPath();
}

// 두께 설정
function setCavasWidth(event, thisObj, width) {
	event.preventDefault();
	ctx.lineWidth = width;
}

// 색깔 설정
function setCavasColor(event, thisObj) {
	event.preventDefault();
	ctx.strokeStyle = thisObj.querySelector('i').style.color;
}

function canvasSetting() {
	event.preventDefault();
    const canvas_eraser = document.querySelector('.canvas-eraser');
    ctx.strokeStyle = 'white';
    canvas.style.cursor = 'url("{{ asset('images/pen.svg') }}") 0 32, auto';
    pos.del = 0;
    setTimeout(() => {
        canvas_eraser.classList.add('active');
    }, 100);
}

document.querySelectorAll('.selected.tool-box-btn').forEach(button => {
    button.addEventListener('click', function(event) {
        activeCanvasEraser(event.currentTarget);
    });
});

function activeCanvasEraser(thisObj){
    // 모든 .tool-box-btn 요소에서 active 클래스 제거
    document.querySelectorAll('.tool-box-btn').forEach(button => {
        button.classList.remove('active');
    });
    // 클릭된 요소에만 active 클래스 추가
    thisObj.classList.add('active');

    // 클릭하면 커서를 images/pen.svg 이미지로 설정
    const imgSrc = thisObj.querySelector('img').src;
    canvas.style.cursor = `url("${imgSrc}") 0 32, auto`;
}
</script>
@endsection
