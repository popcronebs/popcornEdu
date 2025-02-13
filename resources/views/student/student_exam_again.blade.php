
@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '오답노트 풀이')

@section('add_css_js')
    <link href="{{ asset('css/reset.css?5') }}" rel="stylesheet">
@endsection

{{-- 학생 컨텐츠 --}}
@section('layout_coutent')

<style>
    /* 퀴즈 컨테이너 */
    .quiz-container {
        display: flex;
        align-items: center;
        flex-direction: column;
        background: #fff;
        aspect-ratio: 2;
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
        max-height: 638px;
        height: 100%;
        width: 100%;
        display: flex;
        align-items: center;
        flex-direction: column;
        padding-top: clamp(24px, 3vw, 120px);
        overflow-y: auto;
    }

    .quiz-container .quiz-cont::-webkit-scrollbar{
        width: 10px;
        background-color: #F9F9F9;
    }
    .quiz-container .quiz-cont::-webkit-scrollbar-thumb{
        background: #FFC747;
        border-radius: 12px;
    }


    .quiz-wrap .quiz-img {
        background: #F6C5C5;
        aspect-ratio: 4;
        width: 70%;
        border-radius: 12px;
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
    .quiz-wrap .quiz-question {
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
        height: 72px;
        width: 72px;
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
    .quiz-question-view {
        position: relative;
    }

    .quiz-question-view .quiz-question .tquiz-questionry-text{
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 24px;
        line-height: 1.4;
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
    .quiz-answer-view .quiz-answer-item {
        padding: 10px 12px;
        border-radius: 12px;
    }

    .quiz-answer-view .quiz-answer-item span {
        color: #999;
        font-size: 18px;
    }
    .quiz-answer-view .quiz-answer-item span img{
        width: 50%;
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
        padding: clamp(1px, 2vw, 32px) 0;
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
        justify-content: center;
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
    .quiz-view-answer-wrap .quiz-question-view{
        flex: 1;
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
        padding: 0 5%;
    }
    .quiz-view-answer-wrap{
        display: flex;
        flex-direction: row;
        gap: 6px;
        width: 100%;
    }
    .quiz-answer-question .tquiz-questionry-text{
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 24px;
        line-height: 1.4;
    }

    .quiz-question-wrap{
        width: 90%;
    }
    .quiz-question {
        font-size: 1.1em;
        line-height: 1.6;
    }
    .quiz-question img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 1em auto;
    }
</style>

<input type="hidden" data-main-student-lecture-detail-seq value="{{ $student_lecture_detail_seqi ?? ''}}">
<input type="hidden" data-main-exam-lecture-detail-seq value="{{$exam_lecture_detail_seq ?? ''}}">
<input type="hidden" data-main-student-exam-seq="" id="" value="{{$student_exam_seq}}">
<div class="col row position-relative">
    {{-- 뒤로가기 타이틀  --}}
    <div class="sub-title" data-sub-title="back" >
        <h2 class="text-sb-42px">
            <button data-btn-back-page class="btn p-0 row mx-0 all-center" onclick="wrongAgainBack();">
                <img src="{{ asset('images/black_arrow_left_tail.svg') }}" width="52" class="px-0">
            </button>
            <span class="me-2" data-title-student-name>오답노트 풀이</span>
            @if($lecture_detail_type == 'exam_solving')
            <span data-title-school-grade
                class="ht-make-title on text-r-20px py-1 px-3 ms-1 h-42 d-flex align-items-center">문제풀기</span>
            @else
            <span data-title-school-grade
                class="ht-make-title  text-r-20px py-1 px-3 ms-1 h-42 d-flex align-items-center">단원평가</span>
            @endif
        </h2>
    </div>

    <section class="row mx-0">
        {{-- 오답 문제 리스트  --}}
        <aside class="col-3">
            <div class="modal-shadow-style p-4 rounded-4">
                <div>
                    <span class="text-sb-24px">나의 점수</span>
                </div>
                <div class="mt-3 text-end">
                    <span class="text-sb-52px mt-1">{{$correct_rate}}</span>
                    <span class="text-sb-20px">점</span>
                </div>
            </div>
            <div class="modal-shadow-style rounded-4 mt-4 p-4 overflow-auto quiz-wrong-list" style="">
                @if(!empty($normal_wrongs))
                @foreach($normal_wrongs as $normal_wrong)
                <div onclick="wrongExamMake(this);" data-row="wrong_list"
                     class="d-flex justify-content-between align-items-center p-3 scale-bg-gray_01 rounded-2 p-4 mb-2 {{ $normal_wrong->wrong_status == 'wrong' ? 'text-danger' : ($normal_wrong->wrong_status == 'correct' ? 'text-primary' : '') }}">
                        <span class="text-sb-20px" data-exam-type='normal'>기본문제</span>
                        <span class="text-sb-20px" data-exam-num="{{$normal_wrong->exam_num}}">{{$normal_wrong->exam_num}}번</span>
                    </div>
                @endforeach
                @endif
                @if(!empty($similar_wrongs))
                @foreach($similar_wrongs as $similar_wrong)
                <div onclick="wrongExamMake(this);" data-row="wrong_list"
                    class="d-flex justify-content-between align-items-center p-3 scale-bg-gray_01 rounded-2 p-4 mb-2 {{  $similar_wrong->wrong_status == 'wrong' ? 'text-danger': ($similar_wrong->wrong_status == 'correct' ? 'text-primary': '') }}">
                        <span class="text-sb-20px" data-exam-type="similar">유사문제</span>
                        <span class="text-sb-20px" data-exam-num="{{$similar_wrong->exam_num}}">{{$similar_wrong->exam_num}}번</span>
                    </div>
                @endforeach
                @endif
                @if(!empty($challenge_wrongs))
                @foreach($challenge_wrongs as $challenge_wrong)
                <div onclick="wrongExamMake(this);" data-row="wrong_list"
                    class="d-flex justify-content-between align-items-center p-3 scale-bg-gray_01 rounded-2 p-4 mb-2 {{  $challenge_wrong->wrong_status == 'wrong' ? 'text-danger': ($challenge_wrong->wrong_status == 'correct' ? 'text-primary': '') }}">
                        <span class="text-sb-20px" data-exam-type="challenge">도전문제</span>
                        <span class="text-sb-20px" data-exam-num="{{$challenge_wrong->exam_num}}">{{$challenge_wrong->exam_num}}번</span>
                    </div>
                @endforeach
                @endif
                @if(!empty($challenge_similar_wrongs))
                @foreach($challenge_similar_wrongs as $challenge_similar_wrong)
                <div onclick="wrongExamMake(this);" data-row="wrong_list"
                    class="d-flex justify-content-between align-items-center p-3 scale-bg-gray_01 rounded-2 p-4 mb-2 {{  $challenge_similar_wrong->wrong_status == 'wrong' ? 'text-danger': ($challenge_similar_wrong->wrong_status == 'correct' ? 'text-primary': '') }}">
                        <span class="text-sb-20px" data-exam-type="challenge_similar">도전유사</span>
                        <span class="text-sb-20px" data-exam-num="{{$challenge_similar_wrong->exam_num}}">{{$challenge_similar_wrong->exam_num}}번</span>
                    </div>
                @endforeach
                @endif
            </div>
        </aside>
        {{--  오답 다시풀기 --}}
        <div class="col pe-0">
            <section class="video-container bg-primary-y rounded-top-4">
                <div class="video-title d-flex justify-content-between align-items-center text-sb-20px p-4 text-white">
                    <div class="d-flex align-items-center">
                        <span class="">{{$student_exam->grade_name .' '. $student_exam->semester_name}}</span>
                        @if($lecture_detail_type == 'exam_solving')
                            <span class="ms-2">문제풀기</span>
                        @else
                            <span class="ms-2">단원평가</span>
                        @endif
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="memo-text">{{$student_exam->exam_title}}</span>
                    </div>
                </div>
            </section>
            <section class="quiz-container position-relative rounded-botttom-4 modal-shadow-style">
                <div class="quiz-cont">
                    <div class="quiz-question-wrap">
                        <input type="hidden" data-act-exam-seq>
                        <input type="hidden" data-act-exam-num>
                        <input type="hidden" data-act-exam-type>

                        <div class="quiz-view-answer-wrap col">
                            <div class="quiz-question-view">
                                <div class="div-shadow-style rounded-3 overflow-hidden">
                                    <div class="quiz-question-view-title">보기</div>
                                    <div class="quiz-question"></div>
                                </div>
                            </div>
                            <div class="quiz-answer-wrap col">
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
                    <button class="quiz-answer-arrow-next" data-arrow-next data-arrow="1" disabled>
                        <svg class="ms-1" width="15" height="26" viewBox="0 0 15 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.11971 22.8534L11.3433 14.6916C12.1892 13.852 12.1944 12.4857 11.3548 11.6398L3.11971 3.34277" stroke="#FFC747" stroke-width="4.95238" stroke-miterlimit="10" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
            </section>
        </div>
    </section>

</div>
<!-- 160px -->
<div>
    <div class="py-lg-5"></div>
    <div class="py-lg-4"></div>
    <div class="pt-lg-3"></div>
</div>

<script>
const similar_wrongs = @json($similar_wrongs);
const st_answers = @json($st_answers);
const quizData = [
    @if(!empty($normals))
    @foreach($normals as $normal)
    {
        questionType: "기본문제",
        examSeq: "{{$normal->exam_seq}}",
        examType: "{{$normal->exam_type}}",
        questionNumber: "{{$normal->exam_num}}",
        question: `{{$normal->questions}}`,
        @php
            // questions2 텍스트
            $questions2 = nl2br(e($normal->questions2)); // 줄바꿈 적용
            $is_question = $exam_uploadfiles->where('exam_detail_seq', $normal->id)->where('file_type', 'question')->count() > 0;

            // 이미지 경로를 배열로 수집
            $questionImages = [];
            for ($i = 1; $i <= 10; $i++) {
                $filePath = optional($exam_uploadfiles->where('exam_detail_seq', $normal->id)
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
        question2: `{!! nl2br(($questions2)) !!}`,
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
                //${1} 패턴이 있는 경우
                @foreach($choices as $index => $choice)
                    @php
                        $processedChoice = $choice;
                        if (strpos($choice, '${1}') !== false && isset($images[$index])) {
                            $imageUrl = asset($images[$index]); // asset() 함수를 먼저 실행
                            $processedChoice = str_replace('${1}', "<img src='{$imageUrl}'>", $processedChoice);
                        }
                    @endphp
                    {!! json_encode($processedChoice) !!},
                @endforeach
            @elseif(count($images) > 0)
                //패턴은 없지만 이미지가 있는 경우
                @foreach($images as $imagePath)
                    "<img src='{{ asset($imagePath) }}'>",
                @endforeach
            @else
                //패턴도 없고 이미지도 없는 경우 텍스트만 출력
                @foreach($choices as $choice)
                    "{{ $choice }}",
                @endforeach
            @endif
        ],
        answer: [
            @php $answers = explode(';', $normal->answer); @endphp
            @foreach($answers as $answer)
            {{$answer}},
            @endforeach
        ],
        explanation: `{{$normal->commentary}}`,
        explanationLecture: "{{ optional($exam_uploadfiles->where('exam_detail_seq', $normal->id)->where('file_type', 'commentary')->first())->file_path }}",
        @if(!empty($st_answers->where('exam_type', 'normal')->where('exam_num', $normal->exam_num)->first()->wrong_note_answer))
        student_answer:[
            @php $answer2 = explode(';', $st_answers->where('exam_type', 'normal')->where('exam_num', $normal->exam_num)->first()->wrong_note_answer) @endphp
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
        examSeq: "{{$similar->exam_seq}}",
        examType: "{{$similar->exam_type}}",
        questionNumber: "{{$similar->exam_num}}",
        question: `{{$similar->questions}}`,
        @php
        // questions2 텍스트
        $questions2 = nl2br(e($similar->questions2)); // 줄바꿈 적용
        $is_question = $exam_uploadfiles->where('exam_detail_seq', $similar->id)->where('file_type', 'question')->count() > 0;

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
        question2: `{{ $similar->questions2 }}`,
        questionImg: [
            @foreach($questionImages as $imagePath)
                "{{ $imagePath }}",
            @endforeach
        ],
        image: "{{ optional($exam_uploadfiles->where('exam_detail_seq', $similar->id)->where('file_type', 'question')->first())->file_path }}",
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
                            $imageUrl = asset($images[$index]); // asset() 함수를 먼저 실행
                            $processedChoice = str_replace('${1}', "<img src='{$imageUrl}'>", $processedChoice);
                        }
                    @endphp
                    {!! json_encode($processedChoice) !!},
                @endforeach
            @elseif(count($images) > 0)
                //패턴은 없지만 이미지가 있는 경우
                @foreach($images as $imagePath)
                    "<img src='{{ asset($imagePath) }}'>",
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
            {{$answer}},
            @endforeach
        ],
        explanation: `{{$similar->commentary}}`,
        explanationLecture: "{{ optional($exam_uploadfiles->where('exam_detail_seq', $similar->id)->where('file_type', 'commentary')->first())->file_path }}",
        @if(!empty($st_answers->where('exam_type', 'similar')->where('exam_num', $similar->exam_num)->first()->wrong_note_answer))
        student_answer:[
            @php $answers = explode(';', $st_answers->where('exam_type', 'similar')->where('exam_num', $similar->exam_num)->first()->wrong_note_answer) @endphp
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
        examSeq: "{{$challenge->exam_seq}}",
        examType: "{{$challenge->exam_type}}",
        questionNumber: "{{$challenge->exam_num}}",
        question: `{{$challenge->questions}}`,
        @php
        // questions2 텍스트
        $questions2 = nl2br(e($challenge->questions2)); // 줄바꿈 적용
        $is_question = $exam_uploadfiles->where('exam_detail_seq', $challenge->id)->where('file_type', 'question')->count() > 0;

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
        question2: `{{ $challenge->questions2 }}`,
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
                            $imageUrl = asset($images[$index]); // asset() 함수를 먼저 실행
                            $processedChoice = str_replace('${1}', "<img src='{$imageUrl}'>", $processedChoice);
                        }
                    @endphp
                    {!! json_encode($processedChoice) !!},
                @endforeach
            @elseif(count($images) > 0)
                //패턴은 없지만 이미지가 있는 경우
                @foreach($images as $imagePath)
                    "<img src='{{ asset($imagePath) }}'>",
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
            {{$answer}},
            @endforeach
        ],
        explanation: `{{$challenge->commentary}}`,
        explanationLecture: "{{ optional($exam_uploadfiles->where('exam_detail_seq', $challenge->id)->where('file_type', 'commentary')->first())->file_path }}",
        @if(!empty($st_answers->where('exam_type', 'challenge')->where('exam_num', $challenge->exam_num)->first()->wrong_note_answer))
        student_answer:[
            @php $answers = explode(';', $st_answers->where('exam_type', 'challenge')->where('exam_num', $challenge->exam_num)->first()->wrong_note_answer) @endphp
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
        question: `{{$challenge_similar->questions}}`,
        question2: `{{ $challenge_similar->questions2 }}`,
        @php
            // questions2 텍스트
            $questions2 = nl2br(e($challenge_similar->questions2)); // 줄바꿈 적용
            $is_question = $exam_uploadfiles->where('exam_detail_seq', $challenge_similar->id)->where('file_type', 'question')->count() > 0;
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
                            $imageUrl = asset($images[$index]); // asset() 함수를 먼저 실행
                            $processedChoice = str_replace('${1}', "<img src='{$imageUrl}'>", $processedChoice);
                        }
                    @endphp
                    {!! json_encode($processedChoice) !!},
                @endforeach
            @elseif(count($images) > 0)
                //패턴은 없지만 이미지가 있는 경우
                @foreach($images as $imagePath)
                    "<img src='{{ asset($imagePath) }}'>",
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
            {{$answer}},
            @endforeach
        ],
        explanation: `{{$challenge_similar->commentary}}`,
        explanationLecture: "{{ optional($exam_uploadfiles->where('exam_detail_seq', $challenge_similar->id)->where('file_type', 'commentary')->first())->file_path }}",
        @if(!empty($st_answers->where('exam_type', 'challenge_similar')->where('exam_num', $challenge_similar->exam_num)->first()->wrong_note_answer))
        student_answer:[
            @php $answers = explode(';', $st_answers->where('exam_type', 'challenge_similar')->where('exam_num', $challenge_similar->exam_num)->first()->wrong_note_answer) @endphp
            @foreach($answers as $a)
            {{$a}},
            @endforeach
        ] ,
        @endif
    },
    @endforeach
    @endif
];

// 이미 채점은 되어있는 상태.
const is_grading = true;
</script>

<script>
const answerContainer = document.querySelector(".quiz-answer-view");
const questionContainer = document.querySelector(".quiz-question-view .quiz-question");
const quizAnswerQuestion = document.querySelector(".quiz-answer-question");
const quizQuestionWrap = document.querySelector(".quiz-question-wrap");
const quizContent = document.querySelector(".quiz-cont");
const quizQuestionView = document.querySelector(".quiz-question-view");
const quizViewAnswerWrap = document.querySelector('.quiz-view-answer-wrap');
const quizContainer = document.querySelector(".quiz-container");

document.addEventListener("DOMContentLoaded", function(){
    document.querySelector('[data-row="wrong_list"]').click();
});

function setQuizWrongListHeight() {
    const wrongList = document.querySelector('aside:first-child');
    const quizWrongListHeight = document.querySelector('.quiz-wrong-list');
    const quizContainerHeight = document.querySelector('.quiz-container');
    const quizAnswerViewHeight = document.querySelector('.video-container ');
    let heights;
    if(window.innerWidth > 1400){
        heights = quizContainerHeight.offsetHeight - quizAnswerViewHeight.offsetHeight - 24;
    }else{
        heights = quizContainerHeight.offsetHeight - quizAnswerViewHeight.offsetHeight - 16;
    }
    quizWrongListHeight.style.height = heights+'px';
    console.log(heights);
}

// DOM이 로드된 후 실행
document.addEventListener('DOMContentLoaded', setQuizWrongListHeight);

// 창 크기가 변경될 때마다 실행
window.addEventListener('resize', setQuizWrongListHeight);


// 뒤로가기
function wrongAgainBack(){
    sessionStorage.setItem('isBackNavigation', 'true');
    window.history.back();
}

function wrongExamMake(vthis){
    const exam_type = vthis.querySelector('[data-exam-type]').dataset.examType;
    const exam_num = vthis.querySelector('[data-exam-num]').dataset.examNum
    const idx = (exam_num*1 - 1);
    document.querySelectorAll('[data-row="wrong_list"]').forEach(function(el){
        el.classList.remove('active');
        el.classList.remove('border');
        el.classList.remove('border-success');
    });
    vthis.classList.add('active');
    vthis.classList.add('border');
    vthis.classList.add('border-success');

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
    makeExam(idx, data);
}

// 공통 문제 만들기.
function makeExam(index = 0, data = quizData){
    const exam_seq_el = document.querySelector('[data-act-exam-seq]');
    const exam_num_el = document.querySelector('[data-act-exam-num]');
    const exam_type_el = document.querySelector('[data-act-exam-type]');
    const currentQuestion = data[index];
    const tryText = document.createElement('div');
    const tryText2 = document.createElement('div');
    const tryImg = document.createElement('img');
    exam_seq_el.value = currentQuestion?.examSeq;
    exam_num_el.value = currentQuestion?.questionNumber;
    exam_type_el.value = currentQuestion?.examType;
    tryText.classList.add('tquiz-questionry-text');
    tryText.innerHTML = `${currentQuestion?.questionNumber}번. ${currentQuestion?.question}`;
    tryText2.classList.add('text-sb-20px');
    tryText2.classList.add('my-3');
    tryText2.style.lineHeight = '1.9rem';
    tryText2.innerHTML = `${currentQuestion?.question2}`;
    let before_str = '/storage/';
    if(currentQuestion.image && currentQuestion.image.indexOf(before_str) != -1){
        before_str = '';
    }
    tryImg.src = currentQuestion.image ? before_str+currentQuestion.image : '';
    tryImg.style.maxWidth = '100%';
    // tryImg.style.maxHeight = '200px';
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
    answerData = currentQuestion.student_answer;
    answerContainer.innerHTML = currentQuestion?.choices?.map((choice, idx) => `
    <div class="quiz-answer-item ${ answerData?.includes(idx + 1) ? "active" : ""}" onclick="samplesClick(this)">
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
    }else{
        quizQuestionView.classList.remove('block');
        quizQuestionView.classList.add('d-none');
    }

    if(currentQuestion.image || currentQuestion.question2){
        quizQuestionView.classList.add('block');
        quizQuestionView.classList.remove('d-none');
        // middleLine.classList.remove('d-none');
        quizViewAnswerWrap.classList.remove('justify-content-center');
        quizQuestionView.style.maxHeight = `${quizContainer.clientHeight - 12}px`;
        // quizAnswerWrap.style.maxHeight = `${quizContainer.clientHeight}px`;
        if(currentQuestion.question2.replace(/\[(.*?)\]/g, '').length < 6){
            questionContainer.style.fontSize = '80px';
            questionContainer.style.textAlign = 'center';
            questionContainer.querySelector('.text-sb-20px')?.classList.remove('text-sb-20px');
            // quizQuestionView.children[0].style.height = `94%`;
            questionContainer.style.height = `80%`;
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
        // middleLine.classList.add('d-none');
        quizViewAnswerWrap.classList.add('justify-content-center');
        quizQuestionView.style.maxHeight = ``;
        answerContainer.style.paddingRight = ``;
        quizAnswerWrap.style.height = ``;
    }

    // questionContainer.prepend(tryText);
    quizAnswerQuestion.innerHTML = '';
    quizAnswerQuestion.prepend(tryText);

    // ------------------------------------------------------------------------------
    // 문제에 맞는 유틸의 유무 설정.
    afterUtilsShow(currentQuestion, answerData, index);

}


// 각종 유틸 유무.
function afterUtilsShow(currentQuestion, answerData, index){
    // 화살표 유무
    // arrowUpdateNew(index, currentQuestion, answerData);

    // 채점후 정답확인을 했을때. 정답을 체크
    // 정답을 현재 체크했거나, 기본문제인데 첫 시도가 맞았을때,
    if(is_grading && (answerData != undefined))
        resultAnswerContainerUpdate(currentQuestion, index);

    // 문제목록 채점과 전후에 따라 변경.
    // updateRightList(index);

    // 정답 / 풀이 버튼 표기.
    document.querySelector(".check-answer-button")?.remove();
    makeBtnAnswer(currentQuestion);

    // 유사문제, 도전문제 버튼 표기.
    // makeBtnSimilarChallenge(currentQuestion, answerData, index);

    // 틀렸을때, 아쉬워요 버튼표기.
    // normal 이면서 채점이 되었으며, 2두번재 답을 체크 하지 않았고,
    // 첫답이 틀렸을때 표기.
    // if(currentQuestion.examType == 'normal'
    //     && is_grading
    //     && answerData == undefined){
    //         tryAlert(document.querySelector(".quiz-container"));
    // }

    // 마지막 문제인지 확인
    // lastExamResult();

}

// 보기 클릭
function samplesClick(vthis ){
    // return;
    const exam_num = document.querySelector('[data-act-exam-num]').value;
    const exam_type = document.querySelector('[data-act-exam-type]').value;
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
         if( data.student_answer != undefined){
            toast('답을 이미 체크하셨습니다.');
            return;
        }
    }


    // 선택 보기가 활성화 되어있을때 해제
    if(vthis.classList.contains('active')){
        vthis.classList.remove('active');
        document.querySelectorAll(".learning-grid-item")[idx]?.classList.remove("complete");

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

            // 답 전송
            examAnswerInsert();
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
            if(data.answer.length == activeCount){

                // 답 전송
                examAnswerInsert();
                document.querySelectorAll(".learning-grid-item")[idx]?.classList.add("complete");
            }
        }
    }
}

// 정답확인, 정답 및 풀이 버튼 노출 여부.
function makeBtnAnswer(currentQuestion){
    if(!is_grading){ return; }
    const exam_type = document.querySelector('[data-act-exam-type]').value;
    const exam_num = document.querySelector('[data-act-exam-num]').value;
    const idx = (exam_num * 1 - 1);

    let is_answer = false;
    let is_correct = false;
    if(exam_type == 'normal'){
        is_answer = quizData[idx].student_answer?.join(';') != undefined;
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
    document.querySelectorAll('.div-answer-pt-btn').forEach(function(el){
        el.remove();
    });
    const commentary = document.createElement("div");
    const button = document.createElement('button');
    commentary.className = "d-flex justify-content-end align-items-center div-answer-pt-btn";
    button.className = 'check-question-button text-b-24px btn btn-danger rounded-4 mt-2';
    button.setAttribute('style', ' position: absolute; bottom: 4%; right: 4%;');
    button.textContent = "정답 확인";
    commentary.appendChild(button);
    container.appendChild(commentary);

    document.querySelector(".check-question-button").addEventListener("click", () => {
        // 먼저 답이 활성화 되어있는지 확인.
        if(document.querySelector(".quiz-answer-view .quiz-answer-item.active") == null){
            toast('먼저 답을 선택해주세요.');
            return;
        }
        examAnswerInsert(true, true);
        document.querySelector(".check-question-button").remove();
        // 문제 리플레쉬
        currenExamRefresh();


        let none_check = 0;
        document.querySelectorAll('[data-row="wrong_list"]').forEach(function(el){
            if(!el.classList.contains('text-danger') && !el.classList.contains('text-success') && !el.classList.contains('text-primary')){
                none_check++;
            }
        });
        if(none_check == 0){
            sAlert('', '오답을 모두 체크했습니다. 목록으로 돌아가시겠습니까?', 3, function(){
                wrongAgainBack();
            });
        }
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
    commentary.appendChild(button);
    container.appendChild(commentary);

    button.addEventListener("click", () => {
        updateCommentary(currentQuestion, quizContent);
    });
}

// 문제 답입력.
function examAnswerInsert(is_last = false, is_pass = false, callback){
    const exam_seq = document.querySelector('[data-act-exam-seq]').value;
    let exam_num = document.querySelector('[data-act-exam-num]').value;
    let exam_type = document.querySelector('[data-act-exam-type]').value;
    const lecture_detail_seq = document.querySelector('[data-main-exam-lecture-detail-seq]').value;
    const student_lecture_detail_seq = document.querySelector('[data-main-student-lecture-detail-seq]').value;
    const student_exam_seq = document.querySelector('[data-main-student-exam-seq]').value;

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
        data.student_answer = student_answer;
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
        student_exam_seq:student_exam_seq,
        exam_type: exam_type,
        student_answer: student_answer.join(';'),
        is_last: is_last ? 'Y':'N',
        is_wrong: 'Y',
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            // 답입력.
            console.log('success');
        }else{}
        if(callback != undefined){
            callback();
        }
    });
}

function resultAnswerContainerUpdate(currentQuestion ) {
    currentQuestion['answer'].forEach(function(num){
        const sample_el = document.querySelectorAll('.quiz-answer-item')[(num-1)];
        sample_el.querySelector('.quiz-answer-item-num').innerHTML = `
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 8.36977L8.16769 12.502C8.49364 12.9272 9.13035 12.9388 9.47154 12.5257L15 5.83301" stroke="#FF5065" stroke-width="2" stroke-linecap="round"/>
            </svg>
        `;
        sample_el.querySelector('.quiz-answer-item-text').classList.add('text-answer');
    });
}
function updateCommentary(question, container) {
    const existingCommentary = container.querySelector(".quiz-answer-commentary");
    if (existingCommentary) existingCommentary.remove();
    const commentary = document.createElement("div");
    commentary.className = "quiz-answer-commentary d-flex flex-column justify-content-between";
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
                    <div class="quiz-commentary-explanation-text">
                        <p>${question.explanation}</p>
                    </div>
                </div>
                <div class="quiz-commentary-btn gap-2" style="height:auto">
                    <button class="commentary-video text-b-24px btn rounded-full" onclick="commentaryVideo()">
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

// 해설강의
function commentaryVideo(){
    const exam_type = document.querySelector('[data-act-exam-type]').value;
    const exma_num = document.querySelector('[data-act-exam-num]').value;
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
        // video_el.setAttribute('width', '100%');
        video_el.setAttribute('height', '330');
        let before_str = '/storage/';
        if(explanationLecture.indexOf(before_str) != -1 || explanationLecture.indexOf('http') != -1){
            before_str = '';
        }
        video_el.setAttribute('src', before_str+explanationLecture);
        document.querySelector('[data-video-hieen="video"]').appendChild(video_el);
        document.querySelector('[data-video-hieen="video"]').style.height = '80vh';
    }else{
        toast('해설강의가 없습니다.');
    }


}

// 문제 리프레쉬
function currenExamRefresh(){
    const exam_num = document.querySelector('[data-act-exam-num]').value;
    const exam_type = document.querySelector('[data-act-exam-type]').value;
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
    makeExam(idx, data);
    activeListUpdate(idx, data);

}

//
function activeListUpdate(idx, data){
    const data_row = document.querySelector('[data-row].active');
    if(data_row == undefined){
        return;
    }
    if(data[idx].student_answer != undefined && data[idx].student_answer.join(';') == data[idx].answer.join(';')){
        data_row.classList.add('text-success');
    }else if(data[idx].student_answer != undefined){
        data_row.classList.add('text-danger');
    }
}
</script>
@endsection
