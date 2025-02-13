document.addEventListener('visibilitychange', function(event) {
  if (sessionStorage.getItem('isBackNavigation') === 'true') {
      sessionStorage.removeItem('isBackNavigation'); // 상태를 초기화합니다.
      stMainLoadTodaysLearningSelect();
      stMainStudyTimeSelect();
  }

});

document.addEventListener("DOMContentLoaded", function() {
  stMainLoadTodaysLearningSelect();
  stMainStudyTimeSelect();
  const selectedDate = document.querySelector('[data-week-div].active [data-day-date]')?.value;
  stMainChageSideRight(selectedDate, document.querySelector('[data-week-div].active span')?.textContent);
});

// function spinner(){
//   const studyConteiner = document.querySelector('.study-conteiner');
//   const div = document.createElement('div');
//   const spinner = document.createElement('div');
//   spinner.classList.add('spinner-border');
//   div.className = 'spinner-wrap d-flex flex-column-reverse justify-content-center align-items-center gap-3';
//   div.textContent = 'Loading...';
//   div.appendChild(spinner);
//   studyConteiner.appendChild(div);
// }

function stMainMovePage(type) {
  // 학습플래너
  if (type == 'learning') location.href = "/manage/learning";
  // 오답노트 /student/wrong/note
  else if (type == 'wrong_note') {
      location.href = "/student/wrong/note";
  }
  // 학습질문 /teacher/messenger
  else if (type == 'one_on_one') {
      location.href = "/teacher/messenger";
  }
  // 나의학습 /student/my/study
  else if (type == 'my_study') {
      location.href = "/student/my/study";
  }
  // 내성적표 /student/my/score
  else if (type == 'my_score') {
      location.href = "/student/my/score";
  }
  // 학습랭킹 /student/study/point
  else if (type == 'my_rank') {
      location.href = "/student/study/point";
  }
}

//학습하기.
function stMainPlayVido(vthis) {
    const pt_row = vthis.closest('[data-row]');
    const st_lecture_detail_seq = pt_row.querySelector('[data-st-lecture-detail-seq]').value;
    const form = document.querySelector('[data-form="study_video"]');
    form.querySelector('input[name="st_lecture_detail_seq"]').value = st_lecture_detail_seq;

    rememberScreenOnSubmit();
    form.submit();
}

//오늘의 학습 불러오기.
let ori_table = null;

function stMainLoadTodaysLearningSelect(selectr_date) {
  const search_date = selectr_date || new Date().format('yyyy-MM-dd');
  const select_type = 'no_group';
  const page = "/parent/child/study/today/select";
  const parameter = {
      search_start_date: search_date,
      search_end_date: search_date,
      select_type: select_type
  };
  queryFetch(page, parameter, function(result) {
      document.querySelector('.spinner-wrap')?.remove();
      if ((result.resultCode || '') == 'success') {
          //초기화
          let bundle = document.querySelector('[data-bundle="todays_learning"]');
          if (ori_table) {
              // bundle 뒤에 ori_table 을 넣는다.
              const clone_ori_table = ori_table.cloneNode(true);
              $('[data-bundle="todays_learning"]').after(clone_ori_table)
              bundle.remove();
              bundle = clone_ori_table;
          }
          const row_copy = bundle.querySelector('[data-row]').cloneNode(true);
          bundle.classList.remove('owl-loaded');
          bundle.classList.remove('owl-drag');
          bundle.innerHTML = '';
          bundle.appendChild(row_copy);
          if (ori_table == null) ori_table = bundle.cloneNode(true);

          const st_lecture_details = result.student_lecture_details;
          const today = document.querySelector('[data-main-tody-date]')?.value;

          let total_sum = 0; //오늘  강의수
          let total_complete = 0; // 오늘  완료 강의
          //총 봐야할 학습시간
          let total_study_time = 0;
          st_lecture_details.forEach(function(detail) {
              const row = row_copy.cloneNode(true);
              row.classList.add('item');
              row.classList.add('d-inline-flex');
              const study_time = detail.lecture_detail_time;
              //시간:분 = 초
              const sec_study_time = study_time;
              const subject = detail.subject_function_code.replace('subject_', '').replace('_icon', '');

              row.setAttribute('data-row', 'clone');
              row.style.display = '';
              row.hidden = false;
              row.querySelector('[data-st-lecture-detail-seq]').value = detail.id;
              row.querySelector('[data-subject-name]').innerText = detail.subject_name;
              row.querySelector('[data-lecture-detail-name]').innerText = detail.lecture_name + ' ' + detail.lecture_detail_name;
              if (subject == 'math') {
                  row.querySelector('[data-card-item]')?.classList.add('mathematics');
                  row.querySelector('[data-bg-subject-img]').src = "/images/mathematics_character.svg";
              } else if (subject == 'kor') {
                  row.querySelector('[data-card-item]')?.classList.add('ko-language');
                  row.querySelector('[data-bg-subject-img]').src = "/images/ko_language_character.svg";
              } else if (subject == 'eng') {
                  row.querySelector('[data-card-item]')?.classList.add('english');
                  row.querySelector('[data-bg-subject-img]').src = "/images/english_character.svg";
              } else if (subject == 'social') {
                  row.querySelector('[data-card-item]')?.classList.add('society');
                  row.querySelector('[data-bg-subject-img]').src = "/images/society_character.svg";
              } else if (subject == 'other') {
                  row.querySelector('[data-card-item]')?.classList.add('science');
                  row.querySelector('[data-bg-subject-img]').src = "/images/science_character.svg";
              }else if(subject == 'hanja'){
                  row.querySelector('[data-card-item]')?.classList.add('hanja');
                  row.querySelector('[data-bg-subject-img]').src = "/images/hanja_character.svg";
              }
              console.log(subject)
              // row.querySelector('[data-lecture-video]').src = detail.lecture_detail_link;
              // row.querySelector('[data-lecture-video]').currentTime = Math.floor(Math.random() * 60) + 1;

              // sec_study_time (전체시간)  detail.last_video_time (본시간) 으로 %.
              // const persent_time = parseInt(detail.last_video_time || 0) / sec_study_time * 100;
              // row.querySelector('[data-progress-bar] .progress-bar').style.width = persent_time + '%';
              // let description = detail.lecture_detail_description;
              //lecture_detail_description 가 없으면 lecture_description를 삽입.
              // if (detail.lecture_detail_description || description.length < 1) description = detail.lecture_description;
              // row.querySelector('[data-description]').innerText = description;

              total_sum++;
              //data-status
              //프로그래스 바 색 같이 변경.
              if (detail.status == 'complete') {
                  //class add studey-completion
                  total_complete++;
                  row.querySelector('[data-status]').innerText = '학습 완료';
                  row.querySelector('[data-status]').classList.add('completion-learning');
                  // stMainChageProgressColor(row.querySelector('[data-progress-bar] .progress-bar'), 'bg-study-2');
              } else if (detail.status == 'ready') {
                  row.querySelector('[data-status]').innerText = '학습 전';
                  row.querySelector('[data-status]').classList.add('before-learning');
                  // stMainChageProgressColor(row.querySelector('[data-progress-bar] .progress-bar'), 'bg-study-0');
              } else if (detail.status == 'study') {
                  row.querySelector('[data-status]').innerText = '학습 중';
                  row.querySelector('[data-status]').classList.add('learning');
                  // stMainChageProgressColor(row.querySelector('[data-progress-bar] .progress-bar'), 'bg-study-1');
              }
              //오늘이 아니면 학습하기 버튼 삭제
              if (search_date > today) {
                  row.querySelector('[data-btn-study]').remove();
                  row.removeAttribute('onclick');
              }
              total_study_time += parseInt(detail.last_video_time || 0);

              bundle.appendChild(row);
          });
          // 총 학습 강의 수 넣기
          if (total_sum > 0) {
              const total_sum_els = document.querySelectorAll('[data-today-total-sum]');
              total_sum_els.forEach(function(el) {
                  el.innerText = total_sum.toString().padStart(2, '0');
              });
          }
          // 오늘 완료 강의 수 넣기
          if (total_complete >= 0) {
              const total_complete_els = document.querySelectorAll('[data-today-total-complete]');
              total_complete_els.forEach(function(el) {
                  el.innerText = total_complete != 0 ? total_complete.toString().padStart(2, '0') : '00';

              });
          }
          // 2/3 정도 complete되었을때, 거의 다 왔어요! 표시.
          if (total_complete > 0 && total_sum > 0) {
              if (total_complete / total_sum > 0.66) {
                  const today_subscript_div = document.querySelector('[data-today-subscript-div]');
                  today_subscript_div.hidden = false;
              }
          }
          // 총 학습시간 넣기
          if (total_study_time > 0) {
              const total_study_hrs = Math.floor(total_study_time / 60);
              const total_study_min = total_study_time % 60;
              const total_study_hrs_el = document.querySelector('[data-today-all-study-hrs]');
              const total_study_min_el = document.querySelector('[data-today-all-study-min]');
              // 시간 단위로 01:01 형태로 넣기.
              total_study_hrs_el.innerText = total_study_hrs < 10 ? '0' + total_study_hrs : total_study_hrs
              total_study_min_el.innerText = total_study_min < 10 ? '0' + total_study_min : total_study_min
          }


          // const owl = $('[data-bundle="todays_learning"]');
          // owl.owlCarousel({
          //     items: 3,
          //     loop: false,
          //     margin: 10,
          //     nav: true,
          //     dots: false,
          //     onInitialized: owlInit
          // });
          if(result.student_lecture_details.length > 0){
              document.querySelector('.content-lesson-empty').classList.add('d-none');
              document.querySelector('.content-x-wheels').classList.remove('d-none');
              document.querySelector('.title-wrap').classList.remove('d-none');
          }else{
              document.querySelector('.content-lesson-empty').classList.remove('d-none');
              document.querySelector('.content-x-wheels').classList.add('d-none');
              document.querySelector('.title-wrap').classList.add('d-none');
          }
      }
      todeyPrevDate();
    //   const contentXWheels = document.querySelector('.content-x-wheels');

    //   contentXWheels.addEventListener('wheel', function(event) {
    //       event.preventDefault();
    //       if (event.deltaY !== 0) {
    //           // 수직 스크롤을 수평 스크롤로 변환합니다.
    //           contentXWheels.scrollLeft += event.deltaY
    //           // 기본 수직 스크롤 동작을 방지합니다.

    //       }
    //   });
  })
}

function todeyPrevDate() {
  const today = document.querySelector('[data-main-tody-date]')?.value;
  const date = document.querySelectorAll('[data-week-div]');
  date.forEach(function(item, index) {
      if (item.querySelector('[data-day-date]').value < today) {
          item.classList.remove('ctext-gc1');
          item.classList.add('ctext-gc2');
      } else {
          item.classList.remove('ctext-gc2');
          item.classList.add('ctext-gc1');
      }
  })
}

function owlInit() {
  document.querySelector('[data-bundle="todays_learning"] [data-row="copy"]').closest('.owl-item').hidden = true;
}

// 상단에 요일 클릭.
function stMainWeekDayClick(element) {
  // 모든 요일 div 선택
  const allDays = document.querySelectorAll('[data-week-div]');

  // 모든 요일의 활성화 상태 제거
  allDays.forEach(day => {
      day.classList.remove('rounded-3', 'active', 'studyColor-bg-studyComplete');
      day.querySelector('span.day-full').classList.remove('text-white');
      day.querySelector('span.day-short').classList.remove('text-white');
      day.querySelector('.sp_date').classList.remove('d-block', 'text-white');
      day.querySelector('.sp_date').hidden = true;
  });
  // 클릭된 요일 활성화
  element.classList.add('rounded-3', 'active', 'studyColor-bg-studyComplete');
  element.querySelector('span.day-full').classList.add('text-white');
  element.querySelector('span.day-short').classList.add('text-white');
  const spDate = element.querySelector('.sp_date');
  spDate.classList.add('d-block', 'text-white');
  spDate.hidden = false;

  // 선택된 날짜 가져오기
  const selectedDate = element.querySelector('[data-day-date]').value;

  // 오늘의 학습 불러오기.
  stMainLoadTodaysLearningSelect(selectedDate);

  // 오른쪽 날짜/요일 변경하기.
  //stMainChageSideRight(selectedDate, element.querySelector('span').textContent);

  // 학습 시작 시간 가져오기.
  stMainStudyTimeSelect();
}

let currentWeekStart = new Date(); // 현재 주의 시작일 (일요일)
currentWeekStart.setDate(currentWeekStart.getDate() - currentWeekStart.getDay());

function stMainChangeDate(direction) {
  // 주 변경
  if (direction === 'prev') {
      currentWeekStart.setDate(currentWeekStart.getDate() - 7);
  } else if (direction === 'next') {
      currentWeekStart.setDate(currentWeekStart.getDate() + 7);
  }
  todeyPrevDate();
  stMainStudyTimeSelect();
  // 모든 요일 div 선택
  const allDays = document.querySelectorAll('[data-week-div]');
  const koreanDays = ['월요일', '화요일', '수요일', '목요일', '금요일', '토요일', '일요일'];

  // 각 요일 div 업데이트
  allDays.forEach((dayDiv, index) => {
      const date = new Date(currentWeekStart);
      date.setDate((date.getDate() + 1) + index);
      const spanElement = dayDiv.querySelector('span');
      const divElement = dayDiv.querySelector('.sp_date');
      const inputElement = dayDiv.querySelector('[data-day-date]');

      // 요일 텍스트 업데이트
      spanElement.textContent = koreanDays[index];

      // 날짜 텍스트 업데이트
      divElement.textContent = `${(date.getMonth() + 1).toString().padStart(2, '0')}.${date.getDate().toString().padStart(2, '0')}`;

      // data-day-date 값 업데이트
      inputElement.value = date.toISOString().split('T')[0]; // YYYY-MM-DD 형식

      // 오늘 날짜 확인 및 스타일 적용
      let isToday = date.toDateString() === new Date().toDateString();
      let isMonday = date.getDay() === 1;

      // 기존 스타일 제거
      dayDiv.classList.remove('rounded-3', 'active', 'studyColor-bg-studyComplete');
      spanElement.classList.remove('text-white');
      divElement.classList.remove('d-block', 'text-white');
      divElement.hidden = true;

      // 오늘 날짜에 스타일 적용
      if (isToday) {
          dayDiv.classList.add('rounded-3', 'active', 'studyColor-bg-studyComplete');
          spanElement.classList.add('text-white');
          divElement.classList.add('d-block', 'text-white');
          divElement.hidden = false;
          stMainWeekDayClick(dayDiv);
      }

  });
  // 이번 주가 아니라면 비교 연산문 추가
  const today = new Date();
  const startOfWeek = new Date(currentWeekStart);
  const endOfWeek = new Date(currentWeekStart);
  const monday = document.querySelector('[data-week-div="monday"]');
  endOfWeek.setDate(endOfWeek.getDate() + 6);

  if (today < startOfWeek || today > endOfWeek) {
      monday.classList.add('rounded-3', 'active', 'studyColor-bg-studyComplete');
      monday.querySelector('span').classList.add('text-white');
      monday.querySelector('.sp_date').classList.add('d-block', 'text-white');
      monday.querySelector('.sp_date').hidden = true;
      stMainWeekDayClick(monday);
  }

}

// 오른쪽 날짜/요일 변경하기.
function stMainChageSideRight(date, day) {
  const sideRight = document.querySelector('[data-section-side-right]');
  const date_el = sideRight.querySelector('[data-date]');
  const day_el = sideRight.querySelector('[data-week-day]');

  //MM월dd일 형태로 변환
  date_el.textContent = new Date(date).format('MM월dd일');
  day_el.textContent = day.slice(0, 1);
}

// 학습 시작 시간 가져오기.
function stMainStudyTimeSelect() {
  // data-week-div.bg-primary-y data-day-date
  const selectedDay = document.querySelector('[data-week-div].studyColor-bg-studyComplete');
  const selectr_date = selectedDay?.querySelector('[data-day-date]')?.value;
  // const search_start_date = new Date().format('yyyy-MM-dd');
  // const search_end_date = new Date().format('yyyy-MM-dd');
  const page = "/student/study/time/select";
  const parameter = {
      search_start_date: selectr_date,
      search_end_date: selectr_date
  };

  queryFetch(page, parameter, function(result) {
      if ((result.resultCode || '') == 'success') {
          const attend = result.attend;
          if (attend) {
              document.querySelectorAll('[data-study-start-time]').forEach(function(el){
                  el.innerText = attend.start_time.substr(0, 5);
              });
              stMainSetStartTime();
          }
          const study_times = result.study_times;
          if (study_times.length > 0 && attend) {
              const study_time = study_times[0].select_time;
              const start_time = attend.start_time;

              // study_time 에서  start_time을 빼서 몇분이 지각인지 구해라.
              const today_date = new Date().format('yyyy-MM-dd ');
              const start_time_date = new Date(today_date + start_time);
              const study_time_date = new Date(today_date + study_time);
              const diff = study_time_date - start_time_date;
              const diff_minutes = Math.floor(diff / 1000 / 60);

              const late_el = document.querySelector('[data-study-start-late]');
              if (diff_minutes < 0) {
                  late_el.innerText = '(' + diff_minutes + '분 지각)';
                  late_el.hidden = false;
              } else {
                  late_el.hidden = true;
              }
          }
      }
  })
}

// 학습 시간 시간 저장 출석하기.->학습동영상쪽으로 이동.
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

// 프로그래스 바 색 변경.
function stMainChageProgressColor(tag, class_name) {
  tag.classList.remove('bg-study-0');
  tag.classList.remove('bg-study-1');
  tag.classList.remove('bg-study-2');

  tag.classList.add(class_name);
}


//
let currentPosition = 0;
const itemsToShow = 3;
const row = document.querySelector('[data-bundle="todays_learning"]');
const itemWidth = row ? row.children[0].offsetWidth : 0;

function updateCarousel() {
  row.style.transform = `translateX(-${currentPosition * itemWidth}px)`;
}

function moveLeft() {
  if (currentPosition > 0) {
      currentPosition -= itemsToShow;
      if (currentPosition < 0) currentPosition = 0;
      updateCarousel();
  }
}

function moveRight() {
  const totalItems = row.children.length - 1;
  if (currentPosition < totalItems - itemsToShow) {
      currentPosition += itemsToShow;
      if (currentPosition > totalItems - itemsToShow) currentPosition = totalItems - itemsToShow;
      updateCarousel();
  }
}

function stMainSetStartTime(){
  $('.start-time').each(function() {
      const startTime = $(this).text().replace(/ /g, '');
      var startArray = startTime.replace(/\n/g, '').split('');
      var html = `
      <div class="timer-style">
        <span class="cal">${startArray[0] ? startArray[0] : '0'}</span>
        <span class="cal">${startArray[1] ? startArray[1] : '0'}</span>
        <span class="colon">${startArray[2] ? startArray[2] : ':'}</span>
        <span class="cal">${startArray[3] ? startArray[3] : '0'}</span>
        <span class="cal">${startArray[4] ? startArray[4] : '0'}</span>
      </div>
      `
      $(this).html(html);
  });
}


window.addEventListener('resize', () => {
  if (row && row.children.length > 0) {
    let newItemWidth = row.children[0].offsetWidth;
    currentPosition = Math.min(currentPosition, row.children.length - itemsToShow);
    row.style.transform = `translateX(-${currentPosition * newItemWidth}px)`;
  }
});
// cheer-message 전광판처럼 애니메이션 추가
$(document).ready(function() {
  var $cheerMessageContainer = $('.cheer-message');
  var $cheerMessage = $cheerMessageContainer.children();
  var cheerMessagePosition = $cheerMessageContainer.width();
  var animationSpeed = 1.5; // 애니메이션 속도 조절 변수
  var animationFrameId; // 애니메이션 프레임 ID 저장 변수
  var isPaused = false; // 애니메이션 일시정지 상태 변수

  function animateCheerMessage() {
      if (!isPaused) {
          cheerMessagePosition -= animationSpeed;
          if (cheerMessagePosition < -1 * $cheerMessage.width()) {
              cheerMessagePosition = $cheerMessageContainer.width();
          }
          $cheerMessage.css('transform', 'translateX(' + cheerMessagePosition + 'px)');
      }
      animationFrameId = requestAnimationFrame(animateCheerMessage);
  }

  // 일시정지 버튼 클릭 이벤트 핸들러
  $('.pause').on('click', function() {
      isPaused = !isPaused; // 일시정지 상태 토글
  });

  animateCheerMessage();
  $('.progress-time').each(function() {
      const progressTime = $(this).text().replace(/ /g, '');
      var progressArray = [...progressTime].slice(1, -1);

      var html = `
          <div class="timer-style">
              <span class="cal">${progressArray[0] ? progressArray[0] : '0'}</span>
              <span class="cal">${progressArray[1] ? progressArray[1] : '0'}</span>
              <span class="colon">${progressArray[2] ? progressArray[2] : ':'}</span>
              <span class="cal">${progressArray[3] ? progressArray[3] : '0'}</span>
              <span class="cal">${progressArray[4] ? progressArray[4] : '0'}</span>
          </div>
      `
      $(this).html(html);
  });

  stMainSetStartTime();

  let isDragging = false;
  let startX;
  let scrollLeft;

  $(".content-x-wheels").on("mousedown", function(e) {
      isDragging = true;
      startX = e.pageX - $(this).offset().left;
      scrollLeft = $(this).scrollLeft();
      $(this).css("cursor", "grabbing");
  });

  $(".content-x-wheels").on("mouseleave mouseup", function() {
      isDragging = false;
      $(this).css("cursor", "grab");
  });

  $(".content-x-wheels").on("mousemove", function(e) {
      if (!isDragging) return;
      e.preventDefault();
      const x = e.pageX - $(this).offset().left;
      const walk = (x - startX) * 2; // 스크롤 속도 조절
      $(this).scrollLeft(scrollLeft - walk);
  });

  if ($(".study-conteiner").length) {
      let mainContH = $(".learning-status").height();

      $(".study-conteiner").css("height", mainContH + 2);
  }


});
