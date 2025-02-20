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
        background-color: #fff;
    }


    {{-- .video-container .video-cont-tab li[data-complete="Y"] span{
        background-color: #2FCD94 !important;
    } --}}


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
        overflow-y: auto;
        border-radius: 0 0 12px 12px;
    }

    .learning-list-body::-webkit-scrollbar {
        width: 8px;
    }
    .learning-list-body::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .learning-list-body::-webkit-scrollbar-thumb {
        background: #99DD42;
        border-radius: 4px;
    }
    .learning-list-body::-webkit-scrollbar-thumb:hover {
        background: #7fb835;
    }

    .learning-list-body .learning-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 12px;
        height: 40vh;
    }

    .learning-list-body .learning-grid .learning-grid-item {
        background: #fff;
        border-radius: 12px;
        padding: clamp(0.3rem, 1vw, 0.6rem);
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
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        {{-- margin-top: clamp(0.8rem, 1vw, 1.6rem); --}}
    }
    .learning-grid-item .learning-grid-item-img p{
        font-size: 18px;
        font-weight: 600;
        color: #333;
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
    .quiz-question img{
        max-height:350px;
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
    }

    .quiz-answer-view {
        display: flex;
        gap: 8px;
        flex-direction: column;
    }

    .quiz-answer-view-img{
        display: flex;
        gap: 8px;
        width: 100%;
        flex-wrap: wrap;
        flex-direction: row;

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
        color: #565656;
        font-size: 18px;
    }

    .quiz-answer-view-img .quiz-answer-item span.quiz-answer-item-text img {
        width: clamp(6.25rem, calc(3.125rem + 8.333vw), 9.375rem);
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

    .quiz-view-answer-wrap{
        display: flex;
        flex-direction: row;
        width: 100%;
    }
    .quiz-view-answer-wrap .quiz-question-view{
        flex: 1;
        padding: 0 4% 1% 10%;
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
        font-size: 18px;
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
        width: 50%;
        padding: 0 5%;
        overflow: auto;
        justify-content: center;
    }
    .quiz-view-answer-wrap .quiz-answer-wrap::-webkit-scrollbar{
        width: 10px;
        background-color: #F9F9F9;
    }
    .quiz-view-answer-wrap .quiz-answer-wrap::-webkit-scrollbar-thumb{
        background: #FFC747;
        border-radius: 12px;
    }
    .quiz-answer-question .tquiz-questionry-text{
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 24px;
        line-height: 1.4;
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
        .quiz-container .quiz-cont {
            justify-content: flex-start;
        }
        .quiz-view-answer-wrap.justify-content-center .quiz-answer-wrap{
            width: 70%;
        }
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
        .quiz-answer-view{
            gap: 4px;
        }
        .quiz-view-answer-wrap .quiz-question-view,
        .quiz-view-answer-wrap .quiz-answer-wrap{
            padding-top: 30px;
        }
    }
    @media (max-width: 992px) {
        .quiz-container{
            height: auto !important;
            aspect-ratio: auto;
        }

        #layout_div_content{
            height: 100%;
        }
    }
    .space-box {
        color: #666;
        letter-spacing: 1px;
        background-color: #f5f5f5;
        border-radius: 2px;
        padding: 0 2px;
        border-radius: 2px;
        border: 2px solid #666;
    }

    .underline-text {
        border-bottom: 2px solid #666;
        padding-bottom: 2px;
        display: inline-block;
        min-width: 2em;
    }

    .space-box {
        display: inline-block;
        vertical-align: middle;
        border: 2px solid #666;
        background-color: #f5f5f5;
        border-radius: 4px;
        margin: 0 4px;
        height: 1.5em;
        min-width: 2em;
    }

    .long-text-box {
        line-height: 2;
        word-break: break-word;
    }

    .quiz-question img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 1em auto;
    }

    .quiz-question {
        font-size: 1.1em;
        line-height: 1.6;
    }

    .inline-question-image {
        display: inline-block;
        vertical-align: middle;
        max-height: 2em;
        margin: 0 4px;
        object-fit: contain;
    }

    .quiz-question br {
        width: 100%;
        content: "";
        display: block;
        margin: 8px 0;
    }
</style>

<input type="hidden" data-main-student-lecture-detail-seq value="{{ $st_lecture_detail_seq}}">
<input type="hidden" data-main-lecture-seq value="{{$lecture_seq}}">
<input type="hidden" data-main-lecture-detail-seq value="{{$lecture_detail_seq}}">
<input type="hidden" data-main-exam-lecture-detail-seq value="{{$exam_lecdture_detail_seq}}">
<input type="hidden" data-main-student-exam-seq="">
<input type="hidden" data-prev-page value="{{ $prev_page }}">
<input type="hidden" data-main-student-lecture-detail-status value="{{$lecture_detail_info->status}}">
<input type="hidden" data-login-type value="{{ $login_type }}">

<div class="col mx-0 row position-relative">

    <div class="col-lg col-lg-10 pe-0 ps-0">
        {{-- 동영상 시청 --}}
        <section data-section-study-video="top" class="">
            {{-- video 자리 --}}
            <div class="w-100 overflow-hidden video-container" style="" id="videoPtDiv">
                <ul class="video-cont-tab cursor-pointer">
                    <li onclick="studyVideoClickTopTab(this)" class="beginning" data-type=""
                        data-complete="{{$lecture_detail_info->is_complete}}"><span>1</span> 준비하기</li>
                    <li onclick="studyVideoClickTopTab(this)" class="concept" data-type="concept_building"
                        data-complete="{{$lecture_detail_info->is_complete2}}"
                        {{$top_menutabs->where('lecture_detail_type', 'concept_building')->count() > 0 ? '':'hidden' }} ><span>2</span> 개념다지기</li>
                    <!-- summarizing -->
                    <li onclick="studyVideoClickTopTab(this)" class="review" data-type="summarizing"
                        data-complete="{{$lecture_detail_info->is_complete3}}"
                        {{$top_menutabs->where('lecture_detail_type', 'summarizing')->count() > 0 ? '':'hidden' }}><span>3</span> 정리학습</li>
                    <li onclick="studyVideoClickTopTab(this)" class="questions active" data-type="exam_solving"
                        data-complete="{{$lecture_detail_info->is_complete4}}"
                        {{$top_menutabs->where('lecture_detail_type', 'exam_solving')->count() > 0 ? '':'hidden' }}><span>4</span> 문제풀기</li>
                    <li onclick="studyVideoClickTopTab(this)" class="assessment" data-type="unit_test"
                        data-complete="{{$lecture_detail_info->is_complete5}}"
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
                      <button class="btn p-0 hart-btn" onclick="studyVideoClickLike();" data-btn-is-like>
                        <div class="hart-btn-img">
                          <img src="{{ asset('images/hart_icon.svg') }}" width="28" data-is-like="red" {{ $lecture_detail_info->is_like == 'Y'?'':'hidden' }}>
                          <img src="{{ asset('images/gray_hart_icon.svg') }}" width="28" data-is-like="gray" {{ $lecture_detail_info->is_like == 'Y'?'hidden':'' }}>
                          <input type="hidden" data-inp-is-like value="{{ $lecture_detail_info->is_like }}" />
                        </div>
                    </button>
                </div>
            </div>
        </section>
        <section class="quiz-container position-relative">
            <div class="quiz-cont zoom_sm">
                <div class="quiz-question-wrap">
                    <input type="hidden" data-exam-seq>
                    <input type="hidden" data-exam-num>
                    <input type="hidden" data-exam-type>
                    <div class="quiz-view-answer-wrap">
                        <div class="quiz-question-view">
                            <div class="div-shadow-style rounded-3">
                                <div class="quiz-question-view-title">보기</div>
                                <div class="quiz-question"></div>
                            </div>
                        </div>
                        <div class="quiz-answer-wrap">
                            <div class="quiz-answer-question"></div>
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
    <div class="aside-right learning-list col-lg-2 d-flex flex-column justify-content-between ps-3 pe-0">
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
<div class="modal fade" id="modal_lecture_result" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-modal="true" role="dialog">
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
                                <span class="text-black" data-challenge-cnt="wrong"> 0 문제 </span>
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
                    <button class="col text-b-24px btn rounded-4 btn-primary-y h-72" id="btn_exit_lecture" onclick="finishLearning()">
                        학습끝내기
                    </button>
                    @if($login_type != 'teacher')
                    <button class="col text-b-24px btn rounded-4 btn-primary-y h-72" onclick="goWrongNote();">
                        오답노트 풀러가기
                    </button>
                    @endif
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
    <input type="hidden" name="prev_page" value="">
</form>

<script>
// TODO: 컴퓨터 접속시 만약 콘솔을 열게 되었을때, 답을 학생이 직접 볼수 있어 확인요함.
//
// 기본문제
/* prettier-ignore-start */

const quizData = [

    @if(!empty($normals))
    @foreach($normals as $normal)
    {
        questionType: "기본문제",
        examSeq: "{{$normal->exam_seq}}",
        examType: "{{$normal->exam_type}}",
        questionNumber: "{{$normal->exam_num}}",
        question: `{!! $normal->questions !!}`,
        @php
            // questions2 텍스트
            $questions2 = nl2br(e($normal->questions2)); // 줄바꿈 적용
            $is_question = $exam_uploadfiles->where('exam_detail_seq', $normal->id)->where('file_type', 'question')->count() > 0;

            // 이미지 경로를 배열로 수집
            $questionImages = [];
            $filePath = optional($exam_uploadfiles->where('exam_detail_seq', $normal->id)
                ->where('file_type', 'questions')
                ->where('file_type', 'question_img_list_')
                ->first())->file_path;
            if ($filePath) {
                $questionImages[] = $filePath;
            } else {
                for ($i = 1; $i <= 10; $i++) {
                    $filePath = optional($exam_uploadfiles->where('exam_detail_seq', $normal->id)
                        ->where('file_type', 'like', 'question_img_list_'.$i)
                        ->first())->file_path;
                    if ($filePath) {
                        $questionImages[] = $filePath;
                    }
                }
            }
            // 이미지가 하나일 때만 questions2에 적용
            if (count($questionImages) == 1) {
                $questions2 = str_replace('${1}', "<img src='{$questionImages[0]}' alt='Question Image'>", $questions2);
            }

            // 플레이스홀더를 이미지 태그로 대체
            foreach ($questionImages as $index => $imagePath) {
                $placeholder = '${' . ($index + 1) . '}';
                $questions2 = str_replace($placeholder, "<img src='{$imagePath}' alt='Question Image'>", $questions2);
            }
            if($is_question){
                for($i = 1; $i < 10; $i++){
                    $placeholder = '${' . ($i) . '}';
                    $questions2 = str_replace($placeholder, "", $questions2);
                }
            }
        @endphp
        question2: `{!! $is_question ? $questions2:$normal->questions2 !!}`,
        question2html: `{!! $questions2 !!}`,
        questionImg: [
            @foreach($questionImages as $imagePath)
                "{{ $imagePath }}",
            @endforeach
        ],
        image: "{{ optional($exam_uploadfiles->where('exam_detail_seq', $normal->id)->where('file_type', 'question')->first())->file_path }}",
        choices: [
            @php
                $choices = explode(';', $normal->samples);
                $images = [];
                $hasImagePattern = strpos($normal->samples, '${') !== false;

                // 이미지 경로 수집
                for ($i = 1; $i <= 5; $i++) {
                    $filePath = optional($exam_uploadfiles->where('exam_detail_seq', $normal->id)
                        ->where('file_type', 'sample'.$i)
                        ->first())->file_path;
                    if($filePath) {
                        $images[] = $filePath;
                    }
                }
            @endphp

            @if($hasImagePattern)
                {{-- ${1} 패턴이 있는 경우 --}}
                @foreach($choices as $index => $choice)
                    @php
                        $processedChoice = $choice;
                        if (strpos($choice, '${1}') !== false && isset($images[$index])) {
                            $processedChoice = str_replace('${1}', "<img src='".$images[$index]."'>", $processedChoice);
                        }
                    @endphp
                    {!! json_encode($processedChoice) !!},
                @endforeach
            @elseif(count($images) > 0)
                {{-- 패턴은 없지만 이미지가 있는 경우 --}}
                @php
                    $processedChoice = [];
                    for ($i = 0; $i < 5; $i++) {
                        $choice_str = (isset($choices[$i]) ? $choices[$i] : '') . (isset($images[$i]) ? "<br><img src='" . $images[$i] . "'>" : '');
                        array_push($processedChoice, $choice_str);
                    }
                @endphp
                @foreach($processedChoice as $choice)
                    `{!! $choice !!}` ,
                @endforeach

            @else
                {{-- 패턴도 없고 이미지도 없는 경우 텍스트만 출력 --}}
                @foreach($choices as $choice)
                    `{{ $choice }}` ,
                @endforeach
            @endif
        ],
        answer: [
            @php $answers = explode(';', $normal->answer); @endphp
            @foreach($answers as $answer)
            `{{$answer}}`,
            @endforeach
        ],
        explanation: `{{$normal->commentary}}`,
        explanationImg: `{{ optional($exam_uploadfiles->where('exam_detail_seq', $normal->id)->where('file_type', 'commentary_img')->first())->file_path }}`,
        explanationLecture: "{{ optional($exam_uploadfiles->where('exam_detail_seq', $normal->id)->where('file_type', 'commentary')->first())->file_path }}",
        @if(!empty($st_answers->where('exam_type', 'easy')->where('exam_num', $normal->exam_num)->first()->student_answer))
        student_answer1:[
            @php $answer1 = explode(';', $st_answers->where('exam_type', 'easy')->where('exam_num', $normal->exam_num)->first()->student_answer) @endphp
            @foreach($answer1 as $a)
            {{$a}},
            @endforeach
        ] ,
        exam_status1: "{{$st_answers->where('exam_type', 'easy')->where('exam_num', $normal->exam_num)->first()->exam_status}}",
        @endif
        @if(!empty($st_answers->where('exam_type', 'normal')->where('exam_num', $normal->exam_num)->first()->student_answer))
        student_answer2:[
            @php $answer2 = explode(';', $st_answers->where('exam_type', 'normal')->where('exam_num', $normal->exam_num)->first()->student_answer) @endphp
            @foreach($answer2 as $a)
            {{$a}},
            @endforeach
        ]
        @endif
    },
    @endforeach
    @endif
];

// 유사문제
const semiQuizData = [
    @if(!empty($similars))
    @foreach($similars as $similar)
    {
        questionType: "유사문제",
        examSeq: "{{ $similar->exam_seq}}",
        examType: "{{$similar->exam_type}}",
        questionNumber: "{{$similar->exam_num}}",
        question: `{!! $similar->questions !!}`,
        @php
            // questions2 텍스트
            $questions2 = nl2br(e($similar->questions2)); // 줄바꿈 적용
            $is_question = $exam_uploadfiles->where('exam_detail_seq', $similar->id)->whereIn('file_type', ['question'])->count() > 0;

            // 이미지 경로를 배열로 수집
            $questionImages = [];
            for ($i = 1; $i <= 10; $i++) {
                $filePath = optional($exam_uploadfiles->where('exam_detail_seq', $similar->id)
                                                    ->where('file_type', 'like', 'question_img_list_'.$i)
                                                    ->first())->file_path;
                if ($filePath) {
                    $questionImages[] = $filePath;
                }
            }

            // 플레이스홀더를 이미지 태그로 대체
            foreach ($questionImages as $index => $imagePath) {
                $placeholder = '${' . ($index + 1) . '}';
                $questions2 = str_replace($placeholder, "<img src='{$imagePath}' alt='Question Image'>", $questions2);
            }
            if($is_question){
                for($i = 1; $i < 10; $i++){
                    $placeholder = '${' . ($i) . '}';
                    $questions2 = str_replace($placeholder, "", $questions2);
                }
            }
        @endphp
        question2: `{!! $is_question ? $questions2:$similar->questions2 !!}`,
        question2html: `{!! $questions2 !!}`,
        questionImg: [
            @foreach($questionImages as $imagePath)
                "{{ $imagePath }}",
            @endforeach
        ],
        image: "{{ optional($exam_uploadfiles->where('exam_detail_seq', $similar->id)->whereIn('file_type', ['question', 'question_img_list_'])->first())->file_path }}",
        choices: [
            @php
                $choices = explode(';', $similar->samples);
                $images = [];
                $hasImagePattern = strpos($similar->samples, '${') !== false;

                // 이미지 경로 수집
                for ($i = 1; $i <= 5; $i++) {
                    $filePath = optional($exam_uploadfiles->where('exam_detail_seq', $similar->id)
                        ->where('file_type', 'sample'.$i)
                        ->first())->file_path;
                    if($filePath) {
                        $images[] = $filePath;
                    }
                }
            @endphp

            @if($hasImagePattern)
                //${1} 패턴이 있는 경우
                @foreach($choices as $index => $choice)
                    @php
                        $processedChoice = $choice;
                        if (strpos($choice, '${1}') !== false && isset($images[$index])) {
                            $processedChoice = str_replace('${1}', "<img src='".$images[$index]."'>", $processedChoice);
                        }
                    @endphp
                    {!! json_encode($processedChoice) !!},
                @endforeach
            @elseif(count($images) > 0)
                //패턴은 없지만 이미지가 있는 경우
                @php
                    $processedChoice = [];
                    for ($i = 0; $i < 5; $i++) {
                        $choice_str = (isset($choices[$i]) ? $choices[$i] : '') . (isset($images[$i]) ? "<br><img src='" . $images[$i] . "'>" : '');
                        array_push($processedChoice, $choice_str);
                    }
                @endphp
                @foreach($processedChoice as $choice)
                    `{!! $choice !!}` ,
                @endforeach
            @else
                //패턴도 없고 이미지도 없는 경우 텍스트만 출력
                @foreach($choices as $choice)
                    "{{ $choice }}",
                @endforeach
            @endif
        ],
        answer: [
            @php $answers = explode(';', $similar->answer); @endphp
            @foreach($answers as $answer)
            `{{$answer}}`,
            @endforeach
        ],
        explanation: `{{$similar->commentary}}`,
        explanationLecture: "{{ optional($exam_uploadfiles->where('exam_detail_seq', $similar->id)->where('file_type', 'commentary')->first())->file_path }}",
        @if(!empty($st_answers->where('exam_type', 'similar')->where('exam_num', $similar->exam_num)->first()->student_answer))
        student_answer:[
            @php $answers = explode(';', $st_answers->where('exam_type', 'similar')->where('exam_num', $similar->exam_num)->first()->student_answer) @endphp
            @foreach($answers as $a)
            {{$a}},
            @endforeach
        ] ,
        @endif
    },
    @endforeach
    @endif
];

// 도전문제
const challengeQuizData = [
    @if(!empty($challenges))
    @foreach($challenges as $challenge)
    {
        questionType: "도전문제",
        examSeq: "{{ $challenge->exam_seq }}",
        examType: "{{$challenge->exam_type}}",
        questionNumber: "{{$challenge->exam_num}}",
        question: `{!! $challenge->questions !!}`,
        @php
            // questions2 텍스트
            $questions2 = nl2br(e($challenge->questions2)); // 줄바꿈 적용
            $is_question = $exam_uploadfiles->where('exam_detail_seq', $challenge->id)->whereIn('file_type', ['question'])->count() > 0;

            // 이미지 경로를 배열로 수집
            $questionImages = [];
            for ($i = 1; $i <= 10; $i++) {
                $filePath = optional($exam_uploadfiles->where('exam_detail_seq', $challenge->id)
                                                    ->where('file_type', 'like', 'question_img_list_'.$i)
                                                    ->first())->file_path;
                if ($filePath) {
                    $questionImages[] = $filePath;
                }
            }

            // 플레이스홀더를 이미지 태그로 대체
            foreach ($questionImages as $index => $imagePath) {
                $placeholder = '${' . ($index + 1) . '}';
                $questions2 = str_replace($placeholder, "<img src='{$imagePath}' alt='Question Image'>", $questions2);
            }
            if($is_question){
                for($i = 1; $i < 10; $i++){
                    $placeholder = '${' . ($i) . '}';
                    $questions2 = str_replace($placeholder, "", $questions2);
                }
            }
        @endphp
        question2: `{!! $is_question ? $questions2:$challenge->questions2 !!}`,
        question2html: `{!! $questions2 !!}`,
        questionImg: [
            @foreach($questionImages as $imagePath)
                "{{ $imagePath }}",
            @endforeach
        ],
        image: "{{ optional($exam_uploadfiles->where('exam_detail_seq', $challenge->id)->where('file_type', 'question')->first())->file_path }}",
        choices: [
            @php
                $choices = explode(';', $challenge->samples);
                $images = [];
                $hasImagePattern = strpos($challenge->samples, '${') !== false;

                // 이미지 경로 수집
                for ($i = 1; $i <= 5; $i++) {
                    $filePath = optional($exam_uploadfiles->where('exam_detail_seq', $challenge->id)
                        ->where('file_type', 'sample'.$i)
                        ->first())->file_path;
                    if($filePath) {
                        $images[] = $filePath;
                    }
                }
            @endphp

            @if($hasImagePattern)
                //${1} 패턴이 있는 경우
                @foreach($choices as $index => $choice)
                    @php
                        $processedChoice = $choice;
                        if (strpos($choice, '${1}') !== false && isset($images[$index])) {
                            $processedChoice = str_replace('${1}', "<img src='".$images[$index]."'>", $processedChoice);
                        }
                    @endphp
                    {!! json_encode($processedChoice) !!},
                @endforeach
            @elseif(count($images) > 0)
                //패턴은 없지만 이미지가 있는 경우
                @php
                    $processedChoice = [];
                    for ($i = 0; $i < 5; $i++) {
                        $choice_str = (isset($choices[$i]) ? $choices[$i] : '') . (isset($images[$i]) ? "<br><img src='" . $images[$i] . "'>" : '');
                        array_push($processedChoice, $choice_str);
                    }
                @endphp
                @foreach($processedChoice as $choice)
                    `{!! $choice !!}` ,
                @endforeach
            @else
                //패턴도 없고 이미지도 없는 경우 텍스트만 출력
                @foreach($choices as $choice)
                    "{{ $choice }}",
                @endforeach
            @endif
        ],
        answer: [
            @php $answers = explode(';', $challenge->answer); @endphp
            @foreach($answers as $answer)
            `{{$answer}}`,
            @endforeach
        ],
        explanation: `{{$challenge->commentary}}`,
        explanationLecture: "{{ optional($exam_uploadfiles->where('exam_detail_seq', $challenge->id)->where('file_type', 'commentary')->first())->file_path }}",
        @if(!empty($st_answers->where('exam_type', 'challenge')->where('exam_num', $challenge->exam_num)->first()->student_answer))
        student_answer:[
            @php $answers = explode(';', $st_answers->where('exam_type', 'challenge')->where('exam_num', $challenge->exam_num)->first()->student_answer) @endphp
            @foreach($answers as $a)
            {{$a}},
            @endforeach
        ] ,
        @endif
    },
    @endforeach
    @endif
];

// 도전유사문제
const challengeSemiQuizData = [
    @if(!empty($challenge_similars))
    @foreach($challenge_similars as $challenge_similar)
    {
        questionType: "도전유사문제",
        examSeq: "{{$challenge_similar->exam_seq}}",
        examType: "{{$challenge_similar->exam_type}}",
        questionNumber: "{{$challenge_similar->exam_num}}",
        question: `{!! $challenge_similar->questions !!}`,

        @php
            // questions2 텍스트
            $questions2 = nl2br(e($challenge_similar->questions2)); // 줄바꿈 적용
            $is_question = $exam_uploadfiles->where('exam_detail_seq', $challenge_similar->id)->whereIn('file_type', ['question'])->count() > 0;
            // 이미지 경로를 배열로 수집
            $questionImages = [];
            for ($i = 1; $i <= 10; $i++) {
                $filePath = optional($exam_uploadfiles->where('exam_detail_seq', $challenge_similar->id)
                                                    ->where('file_type', 'like', 'question_img_list_'.$i)
                                                    ->first())->file_path;
                if ($filePath) {
                    $questionImages[] = $filePath;
                }
            }

            // 플레이스홀더를 이미지 태그로 대체
            foreach ($questionImages as $index => $imagePath) {
                $placeholder = '${' . ($index + 1) . '}';
                $questions2 = str_replace($placeholder, "<img src='{$imagePath}' alt='Question Image'>", $questions2);
            }
            if($is_question){
                for($i = 1; $i < 10; $i++){
                    $placeholder = '${' . ($i) . '}';
                    $questions2 = str_replace($placeholder, "", $questions2);
                }
            }
        @endphp

        question2: `{!! $is_question ? $questions2:$challenge_similar->questions2 !!}`,
        question2html: `{!! $questions2 !!}`,
        questionImg: [
            @foreach($questionImages as $imagePath)
                "{{ $imagePath }}",
            @endforeach
        ],
        image: "{{ optional($exam_uploadfiles->where('exam_detail_seq', $challenge_similar->id)->where('file_type', 'question')->first())->file_path }}",
        choices: [
            @php
                $choices = explode(';', $challenge_similar->samples);
                $images = [];
                $hasImagePattern = strpos($challenge_similar->samples, '${') !== false;

                // 이미지 경로 수집
                for ($i = 1; $i <= 5; $i++) {
                    $filePath = optional($exam_uploadfiles->where('exam_detail_seq', $challenge_similar->id)
                        ->where('file_type', 'sample'.$i)
                        ->first())->file_path;
                    if($filePath) {
                        $images[] = $filePath;
                    }
                }
            @endphp

            @if($hasImagePattern)
                //${1} 패턴이 있는 경우
                @foreach($choices as $index => $choice)
                    @php
                        $processedChoice = $choice;
                        if (strpos($choice, '${1}') !== false && isset($images[$index])) {
                            $processedChoice = str_replace('${1}', "<img src='".$images[$index]."'>", $processedChoice);
                        }
                    @endphp
                    {!! json_encode($processedChoice) !!},
                @endforeach
            @elseif(count($images) > 0)
                //패턴은 없지만 이미지가 있는 경우
                @php
                    $processedChoice = [];
                    for ($i = 0; $i < 5; $i++) {
                        $choice_str = (isset($choices[$i]) ? $choices[$i] : '') . (isset($images[$i]) ? "<br><img src='" . $images[$i] . "'>" : '');
                        array_push($processedChoice, $choice_str);
                    }
                @endphp
                @foreach($processedChoice as $choice)
                    `{!! $choice !!}` ,
                @endforeach
            @else
                //패턴도 없고 이미지도 없는 경우 텍스트만 출력
                @foreach($choices as $choice)
                    "{{ $choice }}",
                @endforeach
            @endif
        ],
        answer: [
            @php $answers = explode(';', $challenge_similar->answer); @endphp
            @foreach($answers as $answer)
            `{{$answer}}`,
            @endforeach
        ],
        explanation: `{{$challenge_similar->commentary}}`,
        explanationLecture: "{{ optional($exam_uploadfiles->where('exam_detail_seq', $challenge_similar->id)->where('file_type', 'commentary')->first())->file_path }}",
        @if(!empty($st_answers->where('exam_type', 'challenge_similar')->where('exam_num', $challenge_similar->exam_num)->first()->student_answer))
        student_answer:[
            @php $answers = explode(';', $st_answers->where('exam_type', 'challenge_similar')->where('exam_num', $challenge_similar->exam_num)->first()->student_answer) @endphp
            @foreach($answers as $a)
            {{$a}},
            @endforeach
        ] ,
        @endif
    },
    @endforeach
    @endif
];

let quiz_status = "{{ $lecture_detail_info->is_complete4 }}";

/* prettier-ignore-end */

const userAnswers = Array(quizData.length).fill(null).map(() => [null, []]);
const semiUserAnswers = Array(semiQuizData.length).fill(null).map(() => [null]);
const challengeUserAnswers = Array(challengeQuizData.length).fill(null).map(() => [null]);
const challengeSemiUserAnswers = Array(challengeSemiQuizData.length).fill(null).map(() => [null]);
const questionContainer = document.querySelector(".quiz-question-view .quiz-question");
const quizQuestionView = document.querySelector(".quiz-question-view");
const quizViewAnswerWrap = document.querySelector('.quiz-view-answer-wrap');
const answerContainer = document.querySelector(".quiz-answer-view");
const quizAnswerQuestion = document.querySelector(".quiz-answer-question");
const learningGrid = document.querySelector(".learning-grid");
const quizAnswerArrow = document.querySelector(".quiz-answer-arrow");
const arrowPrev = document.querySelector(".quiz-answer-arrow-prev");
const arrowNext = document.querySelector(".quiz-answer-arrow-next");
const btnWrap = document.querySelector(".btn-wrap"); // ing
const quizContent = document.querySelector(".quiz-cont");
const quizQuestionWrap = document.querySelector(".quiz-question-wrap");
const middleLine = document.querySelector(".middle-line");
const quizContainer = document.querySelector(".quiz-container");
const quizAnswerWrap = document.querySelector(".quiz-answer-wrap");
let wrongQuestionIndex = [];
let resultArrowNext = true;
let resultAllClear = false;
let resultStart = 0;
let statusExam = '';
let currentExamType = null;
// -------------------------------------------------------------------------------------------------------
let is_grading = false; // 채점상태
if(!quizData[0]){
    console.log('문제가없어요.');
    document.querySelector(".video-error-message").style.display = "flex";
}else if((quizData[0].exam_status1 || '') == 'correct' || (quizData[0].exam_status1||'') == 'wrong') is_grading = true;
let is_end = false;

function studentExamInsertOrUpdate(){
    const st_lecture_detail_seq = document.querySelector('[data-main-student-lecture-detail-seq]').value;
    const lecture_seq = document.querySelector('[data-main-lecture-seq]').value;
    const lecture_detail_group = document.querySelector('[data-main-lecture-detail-seq]').value;
    const page = "/student/study/quiz/start";
    const parameter = {
        lecture_seq: lecture_seq,
        lecture_detail_group: lecture_detail_group,
        student_lecture_detail_seq: st_lecture_detail_seq,
    };
    queryFetch(page, parameter, function(result){
        try{
            if(result.resultCode == 'success'){
                statusExam = result.student_exam?.exam_status || '';
                console.log(statusExam)
            }
        }catch(e){
            console.log(e);
        }
    });
}

// 공통 문제 만들기.
function makeExam(index = 0, type = 0, data = quizData){
    console.log(data);
    const exam_seq_el = document.querySelector('[data-exam-seq]');
    const exam_num_el = document.querySelector('[data-exam-num]');
    const exam_type_el = document.querySelector('[data-exam-type]');
    const currentQuestion = data[index];
    const tryText = document.createElement('div');
    const tryText2 = document.createElement('div');
    const tryImg = document.createElement('img');
    let question2text = currentQuestion?.question2;
    let question2html = currentQuestion?.question2html;
    let question = currentQuestion?.question;
    let questionView;

    {{-- if(/\s{3,}/.test(question2text)) {
        const longTextBox = document.createElement('div');
        longTextBox.classList.add('long-text-box');

        // 연속된 공백을 네모 박스로 변환하고 크기 조절 가능하도록 수정
        const textWithBoxes = question2text.replace(/(\s{3,})/g, (match) => {
            const spaceCount = match.length;
            return `<span class="space-box mx-2" style="display: inline-block; min-width: ${spaceCount * 8}px; height: 1.5em; vertical-align: middle; border: 2px solid #666; background-color: #f5f5f5;"></span>`;
        });

        longTextBox.innerHTML = textWithBoxes;
        question2text = longTextBox.outerHTML;
    } --}}



    if(currentQuestion != undefined){
        exam_seq_el.value = currentQuestion?.examSeq;
        exam_num_el.value = currentQuestion?.questionNumber;
        exam_type_el.value = currentQuestion?.examType;
        tryText.classList.add('tquiz-questionry-text');
        tryText.innerHTML = `${currentQuestion?.questionNumber}. ${question}`;
        tryText2.classList.add('text-sb-20px');
        // questionImg 배열이 1개 값이면 image
        if(!currentQuestion.image && currentQuestion.questionImg.length == 1){
            currentQuestion.image = currentQuestion.questionImg[0]+'';
        }

        if(Array.isArray(currentQuestion.questionImg) && currentQuestion.questionImg.length > 0){
            console.log(question2html, "있어요");
            questionView = question2html;
            tryText2.innerHTML = ` ${questionView}`;
        }else{
            console.log(question2text, "없어요");
            questionView = question2text;
            tryText2.innerHTML = ` ${questionView}`;
        }

        tryImg.src = currentQuestion?.image ? currentQuestion?.image : '';
        tryImg.style.maxWidth = '100%';
        tryImg.classList.add('mt-5');

        const answerContainer = document.querySelector('.quiz-answer-view');
        const hasImageInChoices = currentQuestion?.choices?.some(choice => /<img\s+[^>]*src=/.test(choice));
        const quizAnswerWrap = document.querySelector('.quiz-answer-wrap');
        if (hasImageInChoices) {
            answerContainer.classList.add('quiz-answer-view-img');
            if(currentQuestion.image ||currentQuestion.question2){
                quizAnswerWrap.style.width = '';
            }else{
                quizAnswerWrap.style.width = 'clamp(70%, calc(1.25rem + 5vw), 90%)';
            }
        } else {
            answerContainer.classList.remove('quiz-answer-view-img');
            quizAnswerWrap.style.width = '';
        }

        let answerData = null;
        // 기본문제만 2번풀기 때문에 분기
        if (exam_type_el.value == 'normal') {
            answerData = (!is_grading ?currentQuestion.student_answer1 : currentQuestion.student_answer2);
        }else {
            answerData = currentQuestion.student_answer;
        }
            answerContainer.innerHTML = currentQuestion?.choices?.filter(choice => choice).map((choice, idx) => `
            <div class="quiz-answer-item ${ answerData?.includes(idx + 1) ? "active" : ""}" onclick="samplesClick(this, ${type})">
            <span class="quiz-answer-item-num">${idx + 1}</span>
            <span class="quiz-answer-item-text">${choice}</span>
            </div>
        `).join("");

        questionContainer.innerHTML = '';
        questionContainer.querySelector(".try-text")?.remove();
        // 이미지 있으면 넣기
        if(currentQuestion.image){
            questionContainer.prepend(tryImg);
        }
        if(currentQuestion.question2){
            questionContainer.prepend(tryText2);
        }
        if(currentQuestion.image || currentQuestion.question2){
            quizQuestionView.classList.add('block');
            quizQuestionView.classList.remove('d-none');
            middleLine.classList.remove('d-none');
            quizViewAnswerWrap.classList.remove('justify-content-center');
            quizQuestionView.style.maxHeight = `${quizContainer.clientHeight - 12}px`;
            quizAnswerWrap.style.maxHeight = `${quizContainer.clientHeight}px`;
            if(currentQuestion.question2.replace(/\[(.*?)\]/g, '').length < 6){
                questionContainer.style.fontSize = '80px';
                questionContainer.style.textAlign = 'center';
                questionContainer.querySelector('.text-sb-20px')?.classList.remove('text-sb-20px');
                quizQuestionView.children[0].style.height = `94%`;
                // questionContainer.style.height = `80%`;
                answerContainer.style.paddingRight = `20%`;
                tryText.style.lineHeight = `1.5`;
            }else{
                questionContainer.style.fontSize = '';
                questionContainer.style.textAlign = '';
                questionContainer.querySelector('.text-sb-20px').classList.add('text-sb-20px');
                quizQuestionView.children[0].style.height = ``;
                questionContainer.style.height = ``;
                answerContainer.style.paddingRight = ``;
                if(currentQuestion.question2.replace(/\[(.*?)\]/g, '').length < 30){
                    questionContainer.style.textAlign = 'center';
                }
            }

            if(hasImageInChoices){
                answerContainer.style.paddingRight = `10%`;
            }else{
                answerContainer.style.paddingRight = `20%`;
            }

        }else{
            quizQuestionView.classList.remove('block');
            quizQuestionView.classList.add('d-none');
            middleLine.classList.add('d-none');
            quizViewAnswerWrap.classList.add('justify-content-center');
            quizQuestionView.style.maxHeight = ``;
            answerContainer.style.paddingRight = ``;
            quizAnswerWrap.style.height = ``;
        }
        quizAnswerQuestion.innerHTML = '';
        quizAnswerQuestion.prepend(tryText);
        // ------------------------------------------------------------------------------
        // 문제에 맞는 유틸의 유무 설정.
        afterUtilsShow(currentQuestion, answerData, index);
    }
}

// 각종 유틸 유무.
function afterUtilsShow(currentQuestion, answerData, index){
    // 화살표 유무
    arrowUpdateNew(index, currentQuestion, answerData);

    // 채점후 정답확인을 했을때. 정답을 체크
    // 정답을 현재 체크했거나, 기본문제인데 첫 시도가 맞았을때,
    if(is_grading &&
        (answerData != undefined ||
            (currentQuestion.examType == 'normal' && currentQuestion.student_answer1?.join(';') == currentQuestion.answer?.join(';'))
        )
    )
    resultAnswerContainerUpdate(currentQuestion, index);

    // 문제목록 채점과 전후에 따라 변경.
    updateRightList(index);

    // 정답 / 풀이 버튼 표기.
    document.querySelector(".check-answer-button")?.remove();
    makeBtnAnswer(currentQuestion);

    // 유사문제, 도전문제 버튼 표기.
    makeBtnSimilarChallenge(currentQuestion, answerData, index);

    // 틀렸을때, 아쉬워요 버튼표기.
    // normal 이면서 채점이 되었으며, 2두번재 답을 체크 하지 않았고,
    // 첫답이 틀렸을때 표기.
    if(currentQuestion.examType == 'normal'
        && is_grading
        && answerData == undefined
        && currentQuestion.student_answer1.join(';') != currentQuestion.answer.join(';')){
            tryAlert(document.querySelector(".quiz-container"));
    }

    // 마지막 문제인지 확인
    lastExamResult();

}

// 문제 리프레쉬
function currenExamRefresh(){
    const exam_num = document.querySelector('[data-exam-num]').value;
    const exam_type = document.querySelector('[data-exam-type]').value;
    const idx = (exam_num*1 - 1);

    let data = null;

    //   데이터 설정.
    if(exam_type == 'normal'){
        data = quizData;
    }else if(exam_type == 'similar'){
        data = semiQuizData;
    }else if(exam_type == 'challenge'){
        data = challengeQuizData;
    }else if(exam_type == 'challenge_similar'){
        data = challengeSemiQuizData;
    }

    // 선택된 문제를 다시 만들어준다.
    makeExam(idx, 0, data);

}


// 보기 클릭
function samplesClick(vthis, depth){
    // return;
    const exam_num = document.querySelector('[data-exam-num]').value;
    const exam_type = document.querySelector('[data-exam-type]').value;
    const idx = (exam_num*1 - 1);

    let data = null;


    //   데이터 설정.
    if(exam_type == 'normal'){
        data = quizData[idx];
    }else if(exam_type == 'similar'){
        data = semiQuizData[idx];
    }else if(exam_type == 'challenge'){
        data = challengeQuizData[idx];
    }else if(exam_type == 'challenge_similar'){
        data = challengeSemiQuizData[idx];
    }

    // 채점후에는 답을 체크하면 보기선택 불가.
    if(is_grading){
        if(exam_type == 'normal' && data.student_answer2 != undefined){
            toast('답을 이미 체크하셨습니다.');
            return;
        }else if(exam_type != 'normal' && data.student_answer != undefined){
            toast('답을 이미 체크하셨습니다.');
            return;
        }
    }


    // 선택 보기가 활성화 되어있을때 해제
    if(vthis.classList.contains('active')){
        vthis.classList.remove('active');
        document.querySelectorAll(".learning-grid-item")[idx]?.classList.remove("complete");
        if (exam_type == 'similar') {
            // 유사문제일 경우에만 활성화되는 분기문
            const checkQuestionButton = document.querySelector('.check-question-button');
            if (checkQuestionButton) {
                checkQuestionButton.hidden = true;
            }
        }
        // 채점하기 버튼 삭제
        makeBtnGrading(true);
    }else{
       // 선택 답이 비활성화 되어 있을때 활성화
        // 답이 1개 일때
        if(data.answer.length == 1){
            //활성화 1개만.
            const quizAnswerItems = answerContainer.querySelectorAll(".quiz-answer-item");
            quizAnswerItems.forEach((item, idx) => {
                item.classList.remove("active");
            });
            vthis.classList.add("active");
            const checkQuestionButton = document.querySelector('.check-question-button');
            if (checkQuestionButton) {
                checkQuestionButton.hidden = false;
            }
            // 답 전송
            examAnswerInsert();
            // 채점하기 버튼 유무
            makeBtnGrading();
            document.querySelectorAll(".learning-grid-item")[idx]?.classList.add("complete");
        }
        // 2개 이상일때
        else{
            // 답 data.answer.length 에 맞게 활성화
            // active 가 몇개 인지 확인하고, active 를 활성화
            const quizAnswerItems = answerContainer.querySelectorAll(".quiz-answer-item");
            let activeCount = 0;
            quizAnswerItems.forEach((item, idx) => {
                if(item.classList.contains("active")){
                    activeCount++;
                }
            });
            // length 와 activeCount 가 같으면 첫 active 제거하고 vthis active 추가.
            if(data.answer.length == activeCount){
                let is_del = false;
                quizAnswerItems.forEach((item, idx) => {
                    if(item.classList.contains("active") && !is_del){
                        item.classList.remove("active");
                        is_del = true;
                    }
                });
            }
            vthis.classList.add("active");
            const checkQuestionButton = document.querySelector('.check-question-button');
            if (checkQuestionButton) {
                checkQuestionButton.hidden = false;
            }
            if(data.answer.length == activeCount){

                // 답 전송
                examAnswerInsert();
                // 채점하기 버튼 유무
                makeBtnGrading();
                document.querySelectorAll(".learning-grid-item")[idx]?.classList.add("complete");
            }
        }
    }
}

// 문제 답입력.
function examAnswerInsert(is_last = false, is_pass = false, callback){
    const exam_seq = document.querySelector('[data-exam-seq]').value;
    let exam_num = document.querySelector('[data-exam-num]').value;
    let exam_type = document.querySelector('[data-exam-type]').value;
    const lecture_detail_seq = document.querySelector('[data-main-exam-lecture-detail-seq]').value;
    const student_lecture_detail_seq = document.querySelector('[data-main-student-lecture-detail-seq]').value;
    // 기본 채점 인데 1번 채점확인을 안했을때
    if(exam_type == 'normal' && !is_grading){
        exam_type = 'easy'; //
    }
    // easy 가 아닐때는 무조건 채점처리.
    if(exam_type != 'easy'){
        is_last = true;
        // 보기 클릭시에 전송되는것을 막음.
        // 정답 확인을 통해서 전송.
        if(!is_pass){
            return;
        }
    }
    // 활성화 되어있는 보기를 가져와서 순서대로 x;x 식으로 변환 해준다.
    const quizAnswerItems = answerContainer.querySelectorAll(".quiz-answer-item");
    let student_answer = [];
    quizAnswerItems.forEach((item, idx) => {
        if(item.classList.contains("active")){
            student_answer.push(idx + 1);
        }
    });

    const idx = (exam_num*1 - 1);
    let data = null;
    //  local 답 저장.
    if(exam_type == 'normal' || exam_type == 'easy'){
        data = quizData[idx];
        if(!is_grading){
            data.student_answer1 = student_answer;
            // length 저장
            if(is_last) exam_num = quizData.length;
        }else{
            data.student_answer2 = student_answer;
        }
    }else if(exam_type == 'similar'){
        data = semiQuizData[idx];
        data.student_answer = student_answer;
    }else if(exam_type == 'challenge'){
        data = challengeQuizData[idx];
        data.student_answer = student_answer;
    }else if(exam_type == 'challenge_similar'){
        data = challengeSemiQuizData[idx];
        data.student_answer = student_answer;
    }


    const page = "/student/exam/student/exam/result/insert";
    // last 가 ture 이면 문제를 채점해주고 결과를 저장하도록 컨트롤에서.
    const parameter = {
        exam_seq: exam_seq,
        exam_num: exam_num,
        lecture_detail_seq: lecture_detail_seq,
        student_lecture_detail_seq: student_lecture_detail_seq,
        exam_type: exam_type,
        student_answer: student_answer.join(';'),
        is_last: is_last ? 'Y':'N',
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            // 답입력.
            console.log('success');
            document.querySelector('[data-main-student-exam-seq]').value = result.student_exam_seq;
        }else{}
        if(callback != undefined){
            callback();
        }
    });
}


// 무조건 기본문제에 번호를 맞춘다는 전제가 필요.기본 5개면, 유사, 도전, 도전유사 모두5개씩
// 양쪽
function arrowUpdateNew(index, currentQuestion, answerData) {
    let prev = document.querySelector(".quiz-answer-arrow-prev");
    let next = document.querySelector(".quiz-answer-arrow-next");
    next.disabled = true;
    prev.disabled = true;

    let is_pass = false;
    //  채점 후,
    if(is_grading ){
        // 답이 있을때
        if(answerData != undefined){
            // 답이 맞았을때
            if(currentQuestion.answer.join(';') == answerData.join(';')){
                is_pass = true;
            }
            // 답이 틀렸는데, 유사문제를 했을때
            else if(currentQuestion.answer.join(';') != answerData.join(';') && semiQuizData[index]?.student_answer != undefined){
                is_pass = true;
            }
            // 도전문제일때, 도전유사문제일때
            if(currentQuestion.examType == 'challenge' || currentQuestion.examType == 'challenge_similar'){
                is_pass = true;
            }
        }
    }

    // 채점 전 or 위 패스
    if(!is_grading || is_pass){
        prev.dataset.arrowPrev = index - 1;
        next.dataset.arrowNext = index;
        next.dataset.arrowNext++
        next.disabled = next.dataset.arrowNext == quizData.length;
        prev.disabled = prev.dataset.arrowPrev == -1;
    }
}

// 이전문제, 다음문제
function handleArrowClick() {
    let myself = event.target;
    if(myself.tagName != 'button'){
        myself = myself.closest('button');
    }
    const chg_number = myself.dataset.arrow * 1;
    const exam_num = document.querySelector('[data-exam-num]').value;
    const exam_type = document.querySelector('[data-exam-type]').value;

    const current_index = exam_num*1-1;
    const chg_index = current_index + chg_number;
    let data = null;
    if(exam_type == 'normal'){
        data = quizData;
    }else if(exam_type == 'similar'){
        data = semiQuizData;
    }else if(exam_type == 'challenge'){
        data = challengeQuizData;
    }else if(exam_type == 'challenge_similar'){
        data = challengeSemiQuizData;
    }

    let student_answer = '';
    // 답없으면 return
    if(exam_type == 'normal'){
        if(!is_grading){
            student_answer = data[current_index].student_answer1;
        }
        else{
            student_answer = data[current_index].student_answer2;
        }
    }
    else{
        student_answer = data[current_index].student_answer;
    }
    if((student_answer||'' || statusExam != 'complete') == '' || getCntActiveSample() == 0){
        // 정답을 선택하지 않았지만, 정답체크 했을때.
        if(document.querySelectorAll('.text-answer').length > 0
        && document.querySelectorAll('[data-btn-answer="true"]').length > 0){

        }else{
            const msg = "<div class='text-sb-20px'>문제를 모두 체크해주세요.</div>";
            sAlert('', msg, 4);
            return;
        }
    }

    makeExam(chg_index, 0, quizData);
}

// 선택된 보기 숫자
function getCntActiveSample(){
    return document.querySelectorAll('.quiz-answer-item.active').length;
}

// 기본문제일경우
// 채점하기 버튼 생성.
function makeBtnGrading(is_del){
    // 마지막 번호인지 확인.
    const max_exam_length = quizData.length;
    const exam_num = document.querySelector('[data-exam-num]').value;
    const exam_type = document.querySelector('[data-exam-type]').value;

    if(is_del){
        document.querySelector(".check-button")?.remove();
        return;
    }
    if(exam_type == 'normal' && !is_grading && exam_num == max_exam_length){
        document.querySelector(".check-button")?.remove();
        const button = document.createElement('button');
        button.className = 'check-button text-b-24px btn btn-danger w-100 rounded-4 mb-2';
        button.textContent = "채점하기";
        button.style.height = "72px";
        button.addEventListener("click", () => {
            clickBtnGrading();
        });
        btnWrap.prepend(button);
    }
}

// 문제 채점하기 버튼 클릭
function clickBtnGrading(){
    // 답 전송.
    examAnswerInsert(true);
    is_grading = true;
    makeExam(0, 0, quizData);

}

// 문제목록
function updateRightList(currentIndex){
    const exam_type = document.querySelector('[data-exam-type]').value;
    if(exam_type == 'normal' && !is_grading){
        // 채점전 문제목록
        updateLearningGridUpdate(currentIndex);
    }else{
        // 채점후 문제목록
        updateScoreContainerUpdate(currentIndex)
    }
}
// 채점전 문제목록
function updateLearningGridUpdate(currentIndex) {
    const exam_list_title_el = document.querySelector('[data-exam-list-str]');
    exam_list_title_el.innerText = '문제 목록';
    learningGrid.innerHTML = quizData.map((question, idx) => `
    <li class="learning-grid-item ${question.student_answer1?.length > 0 ? "complete" : ""}${idx === currentIndex ? " active" : ""}">
    <div class="learning-grid-item-img">
        <p class="subject-name">${question.questionNumber}번</p>
    <svg width="52" height="52" viewBox="0 0 52 52" fill="none" xmlns="http://www.w3.org/2000/svg">
    <circle cx="26" cy="26" r="26" fill="#FFF6E0" />
    <path d="M18 23.3918L23.0683 30.0034C23.5898 30.6837 24.6086 30.7023 25.1545 30.0414L34 19.333" stroke="white" stroke-width="3" stroke-linecap="round" />
    </svg>
    </div>
    </li>`).join("");
}

// 채점후 문제목록
function updateScoreContainerUpdate(currentIndex){
    const learningWrap = document.querySelector(".learning-wrap");
    const existingScoreBody = document.querySelector(".score-container");
    if (existingScoreBody) existingScoreBody.remove();
    const scoreContainer = document.createElement("div");
    scoreContainer.classList.add("score-container");
    const scoreBody = document.createElement("div");
    scoreBody.classList.add("score-body");
    scoreBody.classList.add("zoom_sm");
    const scoreGrid = document.createElement("div");
    scoreGrid.classList.add("score-grid");
    const circle = quizData
    const exam_type = document.querySelector('[data-exam-type]').value;

    const exam_list_title_el = document.querySelector('[data-exam-list-str]');
    exam_list_title_el.innerText = '결과';
    scoreGrid.innerHTML = quizData.map((question, idx) =>{
        let rtn_str = `
        <div class="score-grid-row" data-row="${idx}">
            <div class="score-grid-item normal col">
                <span>${question.questionNumber}번</span>
                <span onclick="examListMove('normal')" class="
                ${
                    question.student_answer1?.join(';') == question.answer.join(';')
                    ?   "score-circle" :
                        (question.student_answer2?.join(';') != undefined && question.student_answer2?.join(';') == question.answer.join(';'))
                            ? "score-triangle"
                            : "score-stars"
                }">
                    <span></span>
                </span>
            </div>
        `;
        rtn_str += `
            <div class="score-grid-item nonenormal col row">
                <span class="after_exam col p-0"> `
            // 유사문제가 있으면, 유사
            // 도전무네가 잇으면 도전
            if(semiQuizData[idx]?.student_answer?.join(';') != undefined){
                 rtn_str += `유사</span>
                            <div class="col border-start border-bottom all-center">
                                ${
                                    semiQuizData[idx]?.student_answer?.join(';') == semiQuizData[idx]?.answer.join(';')
                                    ?   `<span onclick="examListMove('similar')" class="col all-center marking2 score-circle "><span class="d-inline-block"></span></span>`
                                    :   `<span onclick="examListMove('similar')" class="col all-center marking2 score-stars"><span class="d-inline-block"></span></span>`
                                }
                                {{-- <span class="col pe-2 all-center marking2"><span class="d-inline-block"></span></span> --}}
                            </div>
                        </div>
                    </div>
                `;
            }
            else if(challengeQuizData[idx]?.student_answer?.join(';') != undefined){
                 let in_rtn_str = `도전</span>
                            <div class="col border-start border-bottom row" style="">
                                ${
                                    challengeQuizData[idx]?.student_answer?.join(';') == challengeQuizData[idx]?.answer.join(';')
                                    ?   `<span onclick="examListMove('challenge')"  class="col all-center marking2 p-0 score-circle"><span class="d-inline-block"></span></span>`
                                    :   `<span onclick="examListMove('challenge')"  class="col all-center marking2 p-0 score-stars"><span class="d-inline-block"></span></span>`
                                }
                                ${
                                    challengeSemiQuizData[idx]?.student_answer?.join(';') != undefined &&
                                    challengeSemiQuizData[idx]?.student_answer?.join(';') == challengeSemiQuizData[idx]?.answer.join(';')
                                    ?   `<span onclick="examListMove('challenge_similar')"  class="col all-center marking2 p-0 score-circle"><span class="d-inline-block"></span></span>`
                                    :   challengeSemiQuizData[idx]?.student_answer?.join(';') != undefined
                                        ? `<span onclick="examListMove('challenge_similar')"  class="col all-center marking2 p-0 score-stars"><span class="d-inline-block"></span></span>`
                                        : ``
                                }
                            </div>
                        </div>
                    </div>
                `;
                if( challengeSemiQuizData[idx]?.student_answer?.join(';') != undefined &&
                    challengeSemiQuizData[idx]?.student_answer?.join(';') == challengeSemiQuizData[idx]?.answer.join(';')
                    || challengeSemiQuizData[idx]?.student_answer?.join(';') != undefined
                ){
                    in_rtn_str = in_rtn_str.replace(/style=""/gi, 'style="zoom:0.6"');
                }
                rtn_str += in_rtn_str;
            }else{
                 rtn_str += `-</span>
                            <div class="col all-center border-start border-bottom px-3">
                                <span class="col pe-2 all-center marking2"><span class="d-inline-block"></span></span>
                                <span class="col border-start all-center ps-2 marking3"><span class="d-inline-block"></span></span>
                            </div>
                        </div>
                    </div>
                `;
            }
        return rtn_str;
    } ).join("");

    scoreBody.appendChild(scoreGrid);
    scoreContainer.appendChild(scoreBody);
    learningWrap.querySelector(".learning-list-body").classList.add("p-0");
    learningWrap.querySelector(".learning-list-body").innerHTML = "";
    learningWrap.querySelector(".learning-list-body").appendChild(scoreContainer);

    const correct_cnt = document.querySelectorAll('.score-grid-item.normal .score-triangle').length + document.querySelectorAll('.score-grid-item.normal .score-circle').length;

    const scoreResultBody = document.createElement("div");
    scoreResultBody.classList.add("score-result-body");
    scoreResultBody.innerHTML = `
        <div class="score-result-title">
            <p class="subject-name">맞힌 문제</p>
            <p class="subject-score">${correct_cnt}개</p>
        </div>
        <div class="score-result-title">
            <p class="subject-name">틀린 문제</p>
            <p class="subject-score">${quizData.length - correct_cnt}개</p>
        </div>`;

    scoreBody.appendChild(scoreResultBody);
    const btnWrap = document.querySelector(".btn-wrap");
    const nextButton = document.createElement("button");
    nextButton.className = "text-b-24px btn btn-danger w-100 rounded-4";
    nextButton.style.height = "72px";
    nextButton.textContent = "종료하기";
    nextButton.onclick = () => (studyVideoExit());
    btnWrap.innerHTML = "";
    btnWrap.appendChild(nextButton);

    // 현재 위치의 문제 목록 활성화.
     activeNowCurrentExmaList();
}


// 현재 위치의 문제 목록 활성화. active
function activeNowCurrentExmaList(){
    const exam_type = document.querySelector('[data-exam-type]').value;
    const exam_num = document.querySelector('[data-exam-num]').value;
    const idx = (exam_num * 1 - 1);

    // 모두 비활성화
    document.querySelectorAll(".score-grid-item").forEach((item, idx) => {
        item.classList.remove("active");
    });
    if(exam_type == 'normal'){
        document.querySelectorAll(".score-grid-item.normal")[idx].classList.add("active");
    }else{
        document.querySelectorAll(".score-grid-item.nonenormal")[idx].classList.add("active");
    }

}

// 정답확인, 정답 및 풀이 버튼 노출 여부.
function makeBtnAnswer(currentQuestion){
    if(!is_grading){ return; }
    const exam_type = document.querySelector('[data-exam-type]').value;
    const exam_num = document.querySelector('[data-exam-num]').value;
    const idx = (exam_num * 1 - 1);

    const exam_list_active_el = document.querySelector('.score-grid-item.active');
    let is_answer = false;
    let is_correct = false;
    if(exam_type == 'normal'){
        is_answer = quizData[idx].student_answer2?.join(';') != undefined;
        is_correct = quizData[idx].student_answer1?.join(';') == quizData[idx].answer?.join(';')
    }
    else if(exam_type == 'similar'){
        is_answer = semiQuizData[idx].student_answer?.join(';') != undefined;
    }
    else if(exam_type == 'challenge'){
        is_answer = challengeQuizData[idx].student_answer?.join(';') != undefined;
    }
    else if(exam_type == 'challenge_similar'){
        is_answer = challengeSemiQuizData[idx].student_answer?.join(';') != undefined;
    }

    // 각각 답을 적었는지 확인.
    // 답을 정했으면, 정답 풀이보기
    if(is_answer || is_correct){
        // 정답 및 풀이
        correctCommentaryUpdate(quizQuestionWrap, currentQuestion);
    }else{
        // 정답 확인
        answerCommentaryUpdate(quizQuestionWrap)
    }
}

// 정답 확인 버튼 보이게
function answerCommentaryUpdate(container) {
    const existingCommentary = container.querySelector(".quiz-answer-commentary");
    if (existingCommentary) existingCommentary.remove();
    const commentary = document.createElement("div");
    const button = document.createElement('button');
    commentary.className = "d-flex justify-content-end align-items-center";
    button.className = 'check-question-button text-b-24px btn btn-danger rounded-4 mt-2';
    button.setAttribute('style', ' position: absolute; bottom: 4%; right: 4%;');
    button.textContent = "정답 확인";
    document.querySelectorAll(".check-question-button").forEach(function(el){
        el.remove();
    });
    document.querySelectorAll('.d-flex.justify-content-end.align-items-center').forEach(function(el){el.remove()});
    commentary.appendChild(button);
    container.appendChild(commentary);


    document.querySelector(".check-question-button").addEventListener("click", () => {
        examAnswerInsert(true, true);

        // 문제 리플레쉬
        currenExamRefresh();
    });
}

// 정답 및 풀이 버튼 보이게
function correctCommentaryUpdate(container, currentQuestion) {
    const commentary = document.createElement("div");
    const button = document.createElement('button');
    commentary.className = "d-flex justify-content-end align-items-center";
    button.className = 'check-answer-button text-b-24px btn btn-danger rounded-4 mt-2';
    button.setAttribute('style', ' position: absolute; bottom: 4%; right: 4%;');
    button.textContent = "정답 및 풀이";
    button.dataset.btnAnswer = "true";
    document.querySelectorAll('.d-flex.justify-content-end.align-items-center').forEach(function(el){el.remove()});
    commentary.appendChild(button);
    container.appendChild(commentary);

    button.addEventListener("click", () => {
        updateCommentary(currentQuestion, quizContent);
    });
}

function resultAnswerContainerUpdate(currentQuestion) {
    console.log(currentQuestion['answer']);
    currentQuestion['answer'].forEach(function(num){
        const sample_el = document.querySelectorAll('.quiz-answer-item')[(num-1)];
        if(sample_el){
            sample_el.querySelector('.quiz-answer-item-num').innerHTML = `
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 8.36977L8.16769 12.502C8.49364 12.9272 9.13035 12.9388 9.47154 12.5257L15 5.83301" stroke="#FF5065" stroke-width="2" stroke-linecap="round"/>
            </svg>
            `;
            sample_el.querySelector('.quiz-answer-item-text').classList.add('text-answer');
        }else{
            console.error(error);
        }
    });

    if(currentQuestion.examType == 'normal' &&
        currentQuestion.answer.join(';') == currentQuestion.student_answer1.join(';') &&
        currentQuestion.student_answer2 == undefined
    ){
        currentQuestion.student_answer1.forEach(function(num){
            const sample_el = document.querySelectorAll('.quiz-answer-item')[(num-1)];
            sample_el.classList.add('active');
        });
        examAnswerInsert(true,true, function(){
            currenExamRefresh();
        });
    }
}

function makeBtnSimilarChallenge(currentQuestion, answerData, index){
    // similar-question-button 삭제
    // challenge-question-button 삭제
    document.querySelector(".similar-question-button")?.remove();
    document.querySelector(".challenge-question-button")?.remove();
    // 채점을 한 상태에서.
    if(!is_grading){
        return;
    }
    // 기본문제
    if(currentQuestion.examType == 'normal' && answerData != undefined){
        // 틀렸을때,
        if(currentQuestion.answer.join(';') != answerData.join(';')){
            // 유사문제 버튼 생성
            const button = document.createElement('button');
            // 유사문제가 있다면 생성
            if (semiQuizData[Number(index)] != undefined) {
                button.textContent = "유사문제";
                button.className = "similar-question-button text-b-24px btn rounded-4 mt-2 btn-primary-y w-100 h-72";
                document.querySelector(".learning-wrap").appendChild(button);
                if (button) {
                    button.addEventListener("click", () => {
                    // 같은 번호 유사문제로 이동.
                    makeExam(index, 0, semiQuizData);
                    document.querySelector('.check-question-button').hidden = true;
                    });
                }
            }else{
                setArrowButton(true);
            }
        }
        // 맞았을때,
        else{
            // 도전문제 버튼 생성
            const button = document.createElement('button');
            // 도전문제가 있다면 생성
            if (challengeQuizData[Number(index)] != undefined) {
                button.textContent = "도전 문제";
                button.className = "challenge-question-button text-b-24px btn rounded-4 mt-2 btn-primary-y w-100 h-72";
                document.querySelector(".learning-wrap").appendChild(button);
                if (button) {
                    button.addEventListener("click", () => {
                        // 같은 번호 도전문제로 이동.
                        makeExam(index, 0, challengeQuizData);
                    });
                }
            }else{
                setArrowButton(true);
            }
        }
    }
    // 도전문제
    else if(currentQuestion.examType == 'challenge' && answerData != undefined){
        // 틀렸을때,
        if(currentQuestion.answer.join(';') != answerData.join(';')){
            // 도전유사문제 버튼 생성
            // 도전유사문제가 있다면 생성
            if (challengeSemiQuizData[Number(index)] != undefined) {
                const button = document.createElement('button');
                button.textContent = "도전 유사문제";
                button.className = "similar-question-button text-b-24px btn rounded-4 mt-2 btn-primary-y w-100 h-72";
                document.querySelector(".learning-wrap").appendChild(button);

                if (button) {
                button.addEventListener("click", () => {
                    // 같은 번호 도전유사문제로 이동.
                        makeExam(index, 0, challengeSemiQuizData);
                    });
                }
            }else{
                setArrowButton(true);
            }
        }
    }
}

// 문제목록에서 클릭해서 이동
function examListMove(exam_type){
    if(quiz_status == ""){
        const msg = "<div class='text-sb-20px'>문제를 풀이하는 도중에는 이동 할 수 없어요.</div>";
        sAlert('', msg, 4);
        return false;
    }
    const nextArrow = document.querySelector('.quiz-answer-arrow-next');
    const gridRow = event.target.closest('.score-grid-row');
    const idx = gridRow.dataset.row;

    // 데이터 타입에 따른 할당
    const dataMap = {
        'normal': quizData,
        'similar': semiQuizData,
        'challenge': challengeQuizData,
        'challenge_similar': challengeSemiQuizData
    };

    const data = dataMap[exam_type];
    makeExam(idx, 0, data);
}




// 해설강의
function commentaryVideo(){
    const exam_type = document.querySelector('[data-exam-type]').value;
    const exma_num = document.querySelector('[data-exam-num]').value;
    const idx = (exma_num*1 - 1);

    let data = null;
    if(exam_type == 'normal'){
        data = quizData;
    }else if(exam_type == 'similar'){
        data = semiQuizData;
    }else if(exam_type == 'challenge'){
        data = challengeQuizData;
    }else if(exam_type == 'challenge_similar'){
        data = challengeSemiQuizData;
    }
    const explanationLecture = data[idx].explanationLecture;
    if(explanationLecture){
        document.querySelector('.video_tag')?.remove();
        document.querySelectorAll('[data-video-hieen="novideo"]').forEach(function(el){
            el.hidden = true;
        });
        const video_el = document.createElement('video');
        video_el.classList.add('video_tag');
        video_el.setAttribute('controls', 'controls');
        video_el.setAttribute('autoplay', 'autoplay');
        video_el.setAttribute('preload', 'auto');
        video_el.setAttribute('width', '100%');
        video_el.setAttribute('height', '100%');
        video_el.setAttribute('src', explanationLecture);
        document.querySelector('[data-video-hieen="video"]').appendChild(video_el);
        document.querySelector('[data-video-hieen="video"]').style.height = '70%';
    }else{
        toast('해설강의가 없습니다.');
    }
}

// 마지막 정답 확인인지 체크.
function lastExamResult(){
    const exam_type = document.querySelector('[data-exam-type]').value;
    const exam_num = document.querySelector('[data-exam-num]').value;
    const idx = (exam_num*1 - 1);
    const last_num = quizData.length;
    let isEnd = false;
    // 채점후 > 마지막 번호 문제
    if(is_grading && last_num == exam_num){
        if(exam_type == 'normal'
            && quizData[idx].student_answer2?.join(';') != undefined
            && (
                    quizData[idx].student_answer2?.join(';') == quizData[idx].answer?.join(';') ||
                    semiQuizData[idx] == null
                )
            ){
            is_end = true;
            isEnd = true;
            quiz_status = "Y";
        }
        else if(exam_type == 'similar'
            && semiQuizData[idx].student_answer?.join(';') != undefined){
            is_end = true;
            isEnd = true;
            quiz_status = "Y";
        }
    }
    console.log(isEnd)
    // 마지막 문제
    if(isEnd){
        examCompleteUpdate(true);
        // examResultModalShow();
        makeBtnScore();
    }
}

// 학습결과 모달 열기
function examResultModalShow(is_next,next_data_type){
    const myModal = new bootstrap.Modal(document.getElementById('modal_lecture_result'), {
        keyboard: false
    });
    // 설정.
    const modal = document.querySelector('#modal_lecture_result');

    modal.addEventListener('hidden.bs.modal', function () {
        document.querySelector('.modal-backdrop')?.remove();
    });

    let noraml_correct = 0;
    let normal_wrong = 0;
    quizData.forEach(function(data){
        if(data.student_answer2?.join(';') == data.answer?.join(';')){
            noraml_correct++;
        }else{
            normal_wrong++;
        }
    });
    modal.querySelector('[data-normal-cnt="correct"]').innerText = noraml_correct + ' 문제';
    modal.querySelector('[data-normal-cnt="wrong"]').innerText = normal_wrong + ' 문제';

    let similar_correct = 0;
    let similar_wrong = 0;
    semiQuizData.forEach(function(data){
        if(data.student_answer != undefined){
            if(data.student_answer.join(';') == data.answer.join(';')){
                similar_correct++;
            }else{
                similar_wrong++;
            }
        }
    });
    if(similar_correct)
        modal.querySelector('[data-similar-cnt="correct"]').innerText = similar_correct + ' 문제';
    if(similar_wrong)
        modal.querySelector('[data-similar-cnt="wrong"]').innerText = similar_wrong + ' 문제';

    let challenge_correct = 0;
    let challenge_wrong = 0;
    challengeQuizData.forEach(function(data){
        if(data.student_answer != undefined){
            if(data.student_answer.join(';') == data.answer.join(';')){
                challenge_correct++;
            }else{
                challenge_wrong++;
            }
        }
    });
    if(challenge_correct)
        modal.querySelector('[data-challenge-cnt="correct"]').innerText = challenge_correct + ' 문제';
    myModal.show();

    if(challenge_wrong)
        modal.querySelector('[data-challenge-cnt="wrong"]').innerText = challenge_wrong + ' 문제';
    myModal.show();

    const btn_exit = document.querySelector('#btn_exit_lecture');
    if(is_next){
        btn_exit.innerText = '다음 강의로 이동하기';
        btn_exit.setAttribute('onclick', ' document.querySelector(\'[data-type="'+next_data_type+'"]\').click();');
    }else{

        btn_exit.innerText = '학습끝내기';
        btn_exit.setAttribute('onclick', 'finishLearning()');
    }

}

// 학습끝내기
function finishLearning(){
    const modal = document.querySelector('#modal_lecture_result');
    // 모달닫기
    modal.querySelector('.btn-close').click();
    // 들어온곳으로 이동하기.
    studyVideoExit();
}

// 오답노트 풀러가기
function goWrongNote(){
    const login_type = document.querySelector('[data-login-type]').value;
    if(login_type == 'teacher'){
        toast('선생님은 이용할수 없습니다.')
        return;
    }
    // 오답노트로 이동하기.
    location.href = '/student/wrong/note';
}

document.addEventListener("DOMContentLoaded", function() {
    makeExam();
    arrowPrev.addEventListener("click", handleArrowClick);
    arrowNext.addEventListener("click", handleArrowClick);
    document.querySelectorAll('.video-cont-tab.cursor-pointer li:not([hidden])').forEach((span, index) => {
        span.querySelector('span').textContent = index + 1;
    });
    studentExamInsertOrUpdate()
    // TEST:
    // loadQuestion();
    // arrowPrev.addEventListener("click", handlePrevClick);
    // arrowNext.addEventListener("click", handleNextClick);

});

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

function tryAlert(el) {
    const tryMsg = el;
    const tryMsgHtml = document.createElement('div');
    tryMsgHtml.classList.add('try-msg');
    tryMsgHtml.innerHTML = `
        <img src="{{ asset('images/alert_fire_character.png') }}" alt="">
        <div class="try-msg-text">아쉬워요.</div>
        <div class="try-msg-text">다시 한 번 풀어볼까요?</div>
    `;
    tryMsg.appendChild(tryMsgHtml);
    setTimeout(() => {
        tryMsgHtml.style.transition = 'opacity 0.7s';
        tryMsgHtml.style.opacity = '0';
        setTimeout(() => {
            document.querySelector(".try-msg").remove();
        }, 700);
    }, 1500);
    return;
}

function updateCommentary(question, container) {
    const existingCommentary = container.querySelector(".quiz-answer-commentary");
    if (existingCommentary) existingCommentary.remove();
    const commentary = document.createElement("div");
    commentary.className = "quiz-answer-commentary d-flex flex-column justify-content-between";
    const explanationImg = `
        <div class="quiz-commentary-img quiz-commentary-explanation-text d-flex justify-content-evenly">
            <img src="${question.explanationImg}" alt="">
            <p>${question.explanation}</p>
        </div>
    `
    const explanationText = `
        <div class="quiz-commentary-explanation-text">
            <p>${question.explanation}</p>
        </div>
    `
    let explanation;
    if(question.explanationImg){
        explanation = explanationImg;
    }else{
        explanation = explanationText;
    }
    const commentaryWrap = `
            <div class="quiz-commentary-title text-center">
                <span class="text-b-28px">정답과 풀이</span>
            </div>
            <div data-video-hieen="video">

            </div>
            <div class="quiz-commentary-answer" data-video-hieen="novideo">
                <span>정답</span>
                <p>${question.answer.join(", ")}번</p>
            </div>
            <div class="quiz-commentary-wrap">
                <div class="quiz-commentary-explanation" data-video-hieen="novideo">
                    ${explanation}
                </div>
                <div class="quiz-commentary-btn gap-2" style="height:auto">
                    <button class="commentary-video text-b-24px btn rounded-full ${question.explanationLecture ? '' : 'd-none disabled'}" onclick="commentaryVideo()">
                        <svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="16.5055" cy="15.5026" r="10.6667" fill="white"/>
                            <path d="M19.8548 16.0229C20.1884 15.756 20.1884 15.2486 19.8548 14.9817L14.922 11.0354C14.4855 10.6862 13.8389 10.997 13.8389 11.556V19.4485C13.8389 20.0075 14.4855 20.3183 14.922 19.9691L19.8548 16.0229Z" fill="#FFC747"/>
                        </svg>
                        해설 강의
                    </button>
                    <button class="back-btn text-b-24px btn rounded-full">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8 13.3918L13.0683 20.0034C13.5898 20.6837 14.6086 20.7023 15.1545 20.0414L24 9.33301" stroke="white" stroke-width="3.33333" stroke-linecap="round"/>
                        </svg>
                        돌아가기
                    </button>
                </div>
            </div>
    `
    commentary.innerHTML = commentaryWrap;
    container.appendChild(commentary);
    document.querySelector(".back-btn").addEventListener("click", () => {
        document.querySelector(".quiz-answer-commentary").remove();
        document.querySelector('.video_tag')?.remove();
        document.querySelectorAll('[data-video-hieen="novideo"]').forEach(function(el){
            el.hidden = false;
        });

    });
}

// 시험완료 상태 업데이트
function examCompleteUpdate(is_pass){
    const student_lecture_detail_seq = document.querySelector('[data-main-student-lecture-detail-seq]').value;
    const student_exam_seq = document.querySelector('[data-main-student-exam-seq]').value;

    const page = "/student/study/video/complete/update";
    const parameter = {
        st_lecture_detail_seq: student_lecture_detail_seq,
        student_exam_seq:student_exam_seq,
        lecture_detail_type:'exam_solving'
    };

    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
             if(!is_pass) {
              let msg = "시험을 완료하셨습니다.";
              const next_info = getIsNextLecture();
              const is_next = next_info[0];
              const next_data_type = next_info[1];
              setTopMenuComplete();
              setTopMenuAllCompleteChk();
              // 다음수업이 있으면.
              if(is_next){
                  // msg += "<br>확인을 누르시면 다음 강의로 이동합니다. 이동하시겠습니까?";
                examResultModalShow(is_next,next_data_type);
              }else{
                examResultModalShow();
                  // 모두 완료.종료
                  // msg += "<br>모든 강의를 완료하셨습니다. 종료하시겠습니까?";
              }
              // sAlert('', '<div class="text-b-28px">'+msg+'</div>', 3, function(){
              //     if(is_next){
              //         document.querySelector('[data-type="'+next_data_type+'"]').click();
              //     }else{
              //       setTimeout(function(){
              //           studyVideoExit();
              //       }, 1000);
              //     }
              // })
            }
        }else{}
    });
}

// 학습 그만하기 / 뒤로가기
function studyVideoExit() {
    // 뒤로가기
    let msg1 = '<div class="text-b-28px">아직 학습이 끝나지 않았어요.</div>';
    let msg2 = '<div class="text-b-28px text-danger pb-4 pt-3">정말 학습을 그만할건가요?</div>';

    // 학습이 완전히 끝낫는지 확인.
    const is_all_complete = document.querySelector('[data-main-student-lecture-detail-status]').value;
    if(is_all_complete == 'complete'){
        msg1 = '<div class="text-b-28px">학습을 완료합니다.</div>';
        msg2 = '<div class="text-b-28px text-danger pb-4 pt-3">학습을 그만하시겠습니까?</div>';
    }
    sAlert('', msg1 + msg2, 3, function() {
            // 배경(음영) 삭제.
            document.querySelector('.modal-backdrop')?.remove();
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
        '네, 그만할게요.');
    // 배경음영.
    const myModal = new bootstrap.Modal(document.querySelector('#system_alert .modal'), {});
    myModal.show();
// 모달이 닫힐 때 이벤트 추가

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
//--------------------------------------------------- 그림판 ---------------------------------------------------

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
function setArrowButton(bool){
    let prev = document.querySelector(".quiz-answer-arrow-prev");
    let next = document.querySelector(".quiz-answer-arrow-next");
    next.disabled = !bool;
    prev.disabled = !bool;
}
// 채점표 버튼 생성.
let scoreInterval = null;
let scoreCount = 5;
function makeBtnScore(){
        document.querySelectorAll('[data-btn-sorecard="true"]').forEach(button => {
            button.remove();
        });
        const button = document.createElement('button');
        button.innerHTML = "채점표(<span id='btn_score_count'>5</span>)";
        button.className = "similar-question-button text-b-24px btn rounded-4 mt-2 btn-primary-y w-100 h-72";
        button.dataset.btnSorecard = "true";
        document.querySelector(".learning-wrap").appendChild(button);
        button.addEventListener("click", () => {
            // examResultModalShow();
            clearInterval(scoreInterval);
            scoreCount = 0;
            try{document.querySelector('#btn_score_count').innerText = scoreCount;}catch(e){}
            examCompleteUpdate();
        });
        scoreCount = 5;
        if (scoreInterval) {
            clearInterval(scoreInterval);
        }
        scoreInterval = setInterval(() => {
            const btn_score_count = document.querySelector('#btn_score_count');
            const is_show_answer = document.querySelectorAll('.quiz-answer-commentary').length > 0;
            if(scoreCount < 1 && document.querySelectorAll('[data-btn-sorecard="true"]').length > 0){
                clearInterval(scoreInterval);
                // examResultModalShow();
                examCompleteUpdate();
            }else{
                if(!is_show_answer){
                    scoreCount--;
                    btn_score_count.innerText = scoreCount;
                }
            }
        }, 1000);
}
// 활성화 된 상단메뉴의 data-complete 속성 변경.
function setTopMenuComplete(){
    const top_div = document.querySelector('[data-section-study-video="top"]');
    const active_top_menu = top_div.querySelector('.active');
    active_top_menu.dataset.complete = 'Y';
}
//
function setTopMenuAllCompleteChk(){
    const top_div = document.querySelector('[data-section-study-video="top"]');
    const top_menus = top_div.querySelectorAll('[data-type]');
    let all_cnt = 0;
    let complete_cnt = 0;
    top_menus.forEach(function(item){
        if(item.hidden == false ){
            all_cnt++;
            if(item.dataset.complete == 'Y'){
                complete_cnt++;
            }
        }
    });
    if(all_cnt == complete_cnt){
        document.querySelector('[data-main-student-lecture-detail-status]').value = 'complete';
    }
}
</script>
@endsection
