const quizData = [
  @if(!empty($normals))
  @foreach($normals as $normal)
  {
      questionType: "기본문제",
      examSeq: "{{$normal->exam_seq}}",
      examType: "{{$normal->exam_type}}",
      questionNumber: "{{$normal->exam_num}}",
      question: "{!! $normal->questions !!}",
      question2: `{!! nl2br(e($normal->questions2)) !!}`,
      image: "{{ optional($exam_uploadfiles->where('exam_detail_seq', $normal->id)->where('file_type', 'question')->first())->file_path }}",
      choices: [
          @php $choices = explode(';', $normal->samples); @endphp
          @foreach($choices as $choice)
          "{{$choice}}",
          @endforeach
      ],
      answer: [
          @php $answers = explode(';', $normal->answer); @endphp
          @foreach($answers as $answer)
          {{$answer}},
          @endforeach
      ],
      explanation: `{{$normal->commentary}}`,
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
      question: "{!! $similar->questions !!}",
      question2: `{!! nl2br(e($similar->questions2)) !!}`,
      image: "{{ optional($exam_uploadfiles->where('exam_detail_seq', $similar->id)->where('file_type', 'question')->first())->file_path }}",
      choices: [
          @php $choices = explode(';', $similar->samples); @endphp
          @foreach($choices as $choice)
          "{{$choice}}",
          @endforeach
      ],
      answer: [
          @php $answers = explode(';', $similar->answer); @endphp
          @foreach($answers as $answer)
          {{$answer}},
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
      question: "{!! $challenge->questions !!}",
      question2: `{!! nl2br(e($challenge->questions2)) !!}`,
      image: "{{ optional($exam_uploadfiles->where('exam_detail_seq', $challenge->id)->where('file_type', 'question')->first())->file_path }}",
      choices: [
          @php $choices = explode(';', $challenge->samples); @endphp
          @foreach($choices as $choice)
          "{{$choice}}",
          @endforeach
      ],
      answer: [
          @php $answers = explode(';', $challenge->answer); @endphp
          @foreach($answers as $answer)
          {{$answer}},
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
      question: "{{$challenge_similar->questions}}",
      question2: `{!! nl2br(e($challenge_similar->questions2)) !!}`,
      image: "{{ optional($exam_uploadfiles->where('exam_detail_seq', $challenge_similar->id)->where('file_type', 'question')->first())->file_path }}",
      choices: [
          @php $choices = explode(';', $challenge_similar->samples); @endphp
          @foreach($choices as $choice)
          "{{$choice}}",
          @endforeach
      ],
      answer: [
          @php $answers = explode(';', $challenge_similar->answer); @endphp
          @foreach($answers as $answer)
          {{$answer}},
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

/* prettier-ignore-end */

const userAnswers = Array(quizData.length).fill(null).map(() => [null, []]);
const semiUserAnswers = Array(semiQuizData.length).fill(null).map(() => [null]);
const challengeUserAnswers = Array(challengeQuizData.length).fill(null).map(() => [null]);
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
let wrongQuestionIndex = [];
let resultArrowNext = true;
let resultAllClear = false;
let resultStart = 0;

// -------------------------------------------------------------------------------------------------------
let is_grading = false; // 채점상태
if((quizData[0].exam_status1||'') == 'correct' || (quizData[0].exam_status1||'') == 'wrong') is_grading = true;
let is_end = false;

// 공통 문제 만들기.
function makeExam(index = 0, type = 0, data = quizData){
  const exam_seq_el = document.querySelector('[data-exam-seq]');
  const exam_num_el = document.querySelector('[data-exam-num]');
  const exam_type_el = document.querySelector('[data-exam-type]');
  const currentQuestion = data[index];
  const tryText = document.createElement('div');
  const tryText2 = document.createElement('div');
  const tryImg = document.createElement('img');
  exam_seq_el.value = currentQuestion?.examSeq;
  exam_num_el.value = currentQuestion?.questionNumber;
  exam_type_el.value = currentQuestion?.examType;
  tryText.classList.add('tquiz-questionry-text');
  tryText.textContent = `${currentQuestion?.questionNumber}번. ${currentQuestion?.question}`;
  tryText2.classList.add('text-sb-20px');
  {{-- tryText2.classList.add('mt-4');
  tryText2.style.lineHeight = '1.9rem'; --}}
  tryText2.innerHTML = `${currentQuestion?.question2.replace(/\[(.*?)\]/g, '<u>$1</u>')}`;
  tryImg.src = currentQuestion.image ? currentQuestion.image : '';
  tryImg.style.maxWidth = '100%';
  tryImg.style.maxHeight = '200px';
  tryImg.classList.add('mt-4');

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
      if(currentQuestion.question2.replace(/\[(.*?)\]/g, '').length < 6){
          questionContainer.style.fontSize = '80px';
          questionContainer.style.textAlign = 'center';
          questionContainer.querySelector('.text-sb-20px')?.classList.remove('text-sb-20px');
          quizQuestionView.children[0].style.height = `94%`;
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
      }
          answerContainer.style.paddingRight = `20%`;
  }else{
      quizQuestionView.classList.remove('block');
      quizQuestionView.classList.add('d-none');
      middleLine.classList.add('d-none');
      quizViewAnswerWrap.classList.add('justify-content-center');
      quizQuestionView.style.maxHeight = ``;
          answerContainer.style.paddingRight = ``;

      console.log(quizQuestionView.style.maxHeight);

  }
  quizAnswerQuestion.innerHTML = '';
  quizAnswerQuestion.prepend(tryText);
  // ------------------------------------------------------------------------------
  // 문제에 맞는 유틸의 유무 설정.
  afterUtilsShow(currentQuestion, answerData, index);

}

// 각종 유틸 유무.
function afterUtilsShow(currentQuestion, answerData, index){
  // 화살표 유무
  arrowUpdateNew(index, currentQuestion, answerData);

  // 채점후 정답확인을 했을때. 정답을 체크
  // 정답을 현재 체크했거나, 기본문제인데 첫 시도가 맞았을때,
  if(is_grading &&
      (answerData != undefined ||
          (currentQuestion.examType == 'normal' && currentQuestion.student_answer1.join(';') == currentQuestion.answer.join(';'))
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
  if((student_answer||'') == '' || getCntActiveSample() == 0){
      const msg = "<div class='text-sb-20px'>문제를 모두 체크해주세요.</div>";
      sAlert('', msg, 4);
      return;
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
  <li class="learning-grid-item ${question.student_answer1?.length > 0 ? "complete" : ""}${idx === currentIndex ? "active" : ""}">
  <div class="learning-grid-item-title">
  <p class="subject-name">${question.questionNumber}번 문제</p>
  </div>
  <div class="learning-grid-item-img">
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
  const scoreGrid = document.createElement("div");
  scoreGrid.classList.add("score-grid");
  const circle = quizData
  const exam_type = document.querySelector('[data-exam-type]').value;

  const exam_list_title_el = document.querySelector('[data-exam-list-str]');
  exam_list_title_el.innerText = '결과';
  scoreGrid.innerHTML = quizData.map((question, idx) =>{
      let rtn_str = `
      <div class="score-grid-row" data-row="${idx}">
          <div class="score-grid-item normal">
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
          <div class="score-grid-item d-flex nonenormal">
              <span class="after_exam col"> `
          // 유사문제가 있으면, 유사
          // 도전무네가 잇으면 도전
          if(semiQuizData[idx]?.student_answer?.join(';') != undefined){
               rtn_str += `유사</span>
                          <div class="col all-center px-3 border-start border-bottom">
                              ${
                                  semiQuizData[idx]?.student_answer?.join(';') == semiQuizData[idx]?.answer.join(';')
                                  ?   `<span onclick="examListMove('similar')" class="col pe-2 all-center marking2 score-circle "><span class="d-inline-block"></span></span>`
                                  :   `<span onclick="examListMove('similar')" class="col pe-2 all-center marking2 score-stars"><span class="d-inline-block"></span></span>`
                              }
                              {{-- <span class="col pe-2 all-center marking2"><span class="d-inline-block"></span></span> --}}
                          </div>
                      </div>
                  </div>
              `;
          }
          else if(challengeQuizData[idx]?.student_answer?.join(';') != undefined){
               rtn_str += `도전</span>
                          <div class="col all-center px-3">
                              ${
                                  challengeQuizData[idx]?.student_answer?.join(';') == challengeQuizData[idx]?.answer.join(';')
                                  ?   `<span onclick="examListMove('challenge')" class="col pe-2 all-center marking2 score-circle"><span class="d-inline-block"></span></span>`
                                  :   `<span onclick="examListMove('challenge')" class="col pe-2 all-center marking2 score-stars"><span class="d-inline-block"></span></span>`
                              }
                              ${
                                  challengeSemiQuizData[idx]?.student_answer?.join(';') != undefined &&
                                  challengeSemiQuizData[idx]?.student_answer?.join(';') == challengeSemiQuizData[idx]?.answer.join(';')
                                  ?   `<span onclick="examListMove('challenge_similar')" class="col pe-2 all-center marking2 score-circle"><span class="d-inline-block"></span></span>`
                                  :   challengeSemiQuizData[idx]?.student_answer?.join(';') != undefined
                                      ? `<span onclick="examListMove('challenge_similar')" class="col pe-2 all-center marking2 score-stars"><span class="d-inline-block"></span></span>`
                                      : ``
                              }
                          </div>
                      </div>
                  </div>
              `;
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
  commentary.appendChild(button);
  container.appendChild(commentary);

  document.querySelector(".check-question-button").addEventListener("click", () => {
      examAnswerInsert(true, true);
      console.log('teszt');
      document.querySelector(".check-question-button").remove();

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
  commentary.appendChild(button);
  container.appendChild(commentary);

  button.addEventListener("click", () => {
      updateCommentary(currentQuestion, quizContent);
  });
}

function resultAnswerContainerUpdate(currentQuestion) {
  console.log(currentQuestion);
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
          button.textContent = "유사문제";
          button.className = "similar-question-button text-b-24px btn rounded-4 mt-2 btn-primary-y w-100 h-72";
          document.querySelector(".learning-wrap").appendChild(button);
          button.addEventListener("click", () => {
              // 같은 번호 유사문제로 이동.
              makeExam(index, 0, semiQuizData);
              document.querySelector('.check-question-button').hidden = true;
          });
      }
      // 맞았을때,
      else{
          
          // 도전문제 버튼 생성
          const button = document.createElement('button');
          if (challengeQuizData.length > 0) {

              button.textContent = "도전 문제";
              button.className = "challenge-question-button text-b-24px btn rounded-4 mt-2 btn-primary-y w-100 h-72";
              document.querySelector(".learning-wrap").appendChild(button);
          }

          if (button) {
              button.addEventListener("click", () => {
                  // 같은 번호 도전문제로 이동.
                  makeExam(index, 0, challengeQuizData);
              });
          }
      }
  }
  // 도전문제
  else if(currentQuestion.examType == 'challenge' && answerData != undefined){
      // 틀렸을때,
      if(currentQuestion.answer.join(';') != answerData.join(';')){
          // 도전유사문제 버튼 생성
          const button = document.createElement('button');
          button.textContent = "도전 유사문제";
          button.className = "similar-question-button text-b-24px btn rounded-4 mt-2 btn-primary-y w-100 h-72";
          document.querySelector(".learning-wrap").appendChild(button);
          button.addEventListener("click", () => {
              // 같은 번호 도전유사문제로 이동.
              makeExam(index, 0, challengeSemiQuizData);
          });
      }
  }
}

// 문제목록에서 클릭해서 이동.
function examListMove(exam_type){
  const nextArrow = document.querySelector('.quiz-answer-arrow-next');
  if (nextArrow.disabled) {
      return;
  }
  const gridRow = event.target.closest('.score-grid-row');
  const idx = gridRow.dataset.row;
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
          && quizData[idx].student_answer2?.join(';') == quizData[idx].answer?.join(';')
          ){
          is_end = true;
          isEnd = true;

      }
      else if(exam_type == 'similar'
          && semiQuizData[idx].student_answer?.join(';') != undefined){
          is_end = true;
          isEnd = true;
      }
  }
  console.log(isEnd)
  // 마지막 문제
  if(isEnd){
      examResultModalShow();
      examCompleteUpdate();
  }
}

// 학습결과 모달 열기
function examResultModalShow(){
  const myModal = new bootstrap.Modal(document.getElementById('modal_lecture_result'), {
      keyboard: false
  });
  // 설정.
  const modal = document.querySelector('#modal_lecture_result');

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
  if(challenge_wrong)
      modal.querySelector('[data-challenge-cnt="wrong"]').innerText = challenge_wrong + ' 문제';
  myModal.show();
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
  // TEST:
  // loadQuestion();
  // arrowPrev.addEventListener("click", handlePrevClick);
  // arrowNext.addEventListener("click", handleNextClick);
})

// 준비하기, 개념다지기, 문제풀기, 정리하기, 단원평가 탭 클릭
function studyVideoClickTopTab(vthis){
  const detail_type = vthis.dataset.type;
  const form = document.querySelector('#form_post');
  form.method = 'post';
  form.querySelector('[name="st_lecture_detail_seq"]').value = document.querySelector('[data-main-student-lecture-detail-seq]').value;
  form.querySelector('[name="lecture_detail_seq"]').value = document.querySelector('[data-main-lecture-detail-seq]').value;
  form.querySelector('[name="lecture_seq"]').value = document.querySelector('[data-main-lecture-seq]').value;

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
function examCompleteUpdate(){
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
      }else{}
  });
}

// 학습 그만하기 / 뒤로가기
function studyVideoExit() {
  // 뒤로가기
  const msg1 = '<div class="text-b-28px">아직 학습이 끝나지 않았어요.</div>';
  const msg2 = '<div class="text-b-28px text-danger pb-4 pt-3">정말 학습을 그만할건가요?</div>';
  sAlert('', msg1 + msg2, 3, function() {
          // 배경(음영) 삭제.
          document.querySelector('.modal-backdrop').remove();
      }, function() {

          //그만둘경우 저장 지점 저장.

          // studyVideoPause();
          // studyVideoTimeUpdate();

          sessionStorage.setItem('isBackNavigation', 'true');
          goToRememberedScreen();
          // history.back();
      },
      '더 해볼게요.',
      '네,그만할게요.');
  // 배경음영.
  const myModal = new bootstrap.Modal(document.querySelector('#system_alert .modal'), {});
  myModal.show();
}
