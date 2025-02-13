  // 오늘 날짜 객체 생성
  var today = new Date();

  // 오늘 날짜를 원하는 형식으로 변환
  var todayFormatted = formatDate(today);

  // 1주일 전 날짜 계산
  var oneWeekAgo = new Date(today);
  oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
  var oneWeekAgoFormatted = formatDate(oneWeekAgo);

  // 1개월 전 날짜 계산
  var oneMonthAgo = new Date(today);
  oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1);
  var oneMonthAgoFormatted = formatDate(oneMonthAgo);

  // 3개월 전 날짜 계산
  var threeMonthsAgo = new Date(today);
  threeMonthsAgo.setMonth(threeMonthsAgo.getMonth() - 3);
  var threeMonthsAgoFormatted = formatDate(threeMonthsAgo);

  document.querySelectorAll('.date-change').forEach(function(element) {
    element.addEventListener('change', function(event) {
    var value = this.value;
    var selectedDate;
    var id = event.target.id;
    console.log(id);

    if (value === '0') {
      selectedDate = oneWeekAgoFormatted;
    } else if (value === '1') {
      selectedDate = oneMonthAgoFormatted;
    } else if (value === '2') {
      selectedDate = threeMonthsAgoFormatted;
    } else {
      selectedDate = todayFormatted; // 기본값은 오늘 날짜
    }

    document.querySelector(`.${id}`).value = selectedDate + "~" + todayFormatted;
  });
});

  // 날짜를 'YYYY-MM-DD' 형식으로 변환하는 함수
  function formatDate(date) {
    var year = date.getFullYear();
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var day = String(date.getDate()).padStart(2, '0');
    return year + '.' + month + '.' + day;
  }

document.addEventListener('DOMContentLoaded', function() {
var todayCalendarEl = document.getElementById('todayCalendar');
var todayWeekCalendarEl = document.getElementById('todayWeekCalendar');
var todayCalendar = new FullCalendar.Calendar(todayCalendarEl, {
  locale : "ko",
  firstDay: 1, //요일순서바꾸기 기본값 0
  weekNumbers: false, //주차표시
  weekNumberCalculation: "ISO", //주차표기형식 없어도무관
  contentHeight: 344,
  dayCellContent: function (info) {
    var number = document.createElement("a");
    number.classList.add("fc-daygrid-day-number");
    number.innerHTML = info.dayNumberText.replace("일", '');
    if (info.view.type === "dayGridMonth") {
      return {
        html: number.outerHTML
      };
    }
    return {
      domNodes: []
    };
  },
  titleFormat: { year: 'numeric', month: 'short', week: 'week' },
  customButtons: {
    myCustomPrev: {
      text: '',
      click: function() {
        todayCalendar.prev();
      }
    },
    myCustomNext: {
      text: '',
      click: function() {
        todayCalendar.next();
      }
    }
  },
  headerToolbar: {
    left: 'myCustomPrev',
    center: 'title',
    right: 'myCustomNext',
  },
  titleFormat: function (date) {
    // 주어진 날짜를 기준으로 오늘의 날짜를 생성합니다.
    var today = new Date(date.date.year, date.date.month, date.date.day);

    // 오늘의 일요일을 찾습니다. (주의 시작일을 일요일로 설정)
    var firstDayOfWeek = new Date(today);
    firstDayOfWeek.setDate(today.getDate() - today.getDay());

    // 주의 시작일로부터 오늘까지의 경과 일 수를 계산합니다.
    var elapsedDays = Math.floor((today - firstDayOfWeek) / (1000 * 60 * 60 * 24));
    Date.prototype.getWeek = function (dowOffset) {
        dowOffset = typeof(dowOffset) == 'number' ? dowOffset : 0; //default dowOffset to zero
        var newYear = new Date(this.getFullYear(),0,1);
        var day = newYear.getDay() - dowOffset; //the day of week the year begins on
        day = (day >= 0 ? day : day + 7);
        var daynum = Math.floor((this.getTime() - newYear.getTime() -
        (this.getTimezoneOffset()-newYear.getTimezoneOffset())*60000)/86400000) + 1;
        var weeknum;
        if(day < 4) {
            weeknum = Math.floor((daynum+day-1)/7) + 1;
            if(weeknum > 52) {
                nYear = new Date(this.getFullYear() + 1,0,1);
                nday = nYear.getDay() - dowOffset;
                nday = nday >= 0 ? nday : nday + 7;
                weeknum = nday < 4 ? 1 : 53;
            }
        }
        else {
            weeknum = Math.floor((daynum+day-1)/7);
        }
        return weeknum;
    };
    const getWeek = (date) => {
      const currentDate = date.getDate();
      const firstDay = new Date(date.setDate(1)).getDay();

      return Math.ceil((currentDate + firstDay) / 7);
    };
    const week = getWeek(new Date()); // week 이번달내에 주차
    const mydate = new Date(); //mydate.getWeek() 지금까지의 이어진 주차
  return `${date.date.year}년 ${date.date.month + 1}월 ${mydate.getWeek()}주차`;
  },
});
var todayWeekCalendar = new FullCalendar.Calendar(todayWeekCalendarEl, {
  locale : "ko",
  firstDay: 1,
  initialView: 'dayGridWeek',
  contentHeight: 100,
  customButtons: {
    myCustomPrev: {
      text: '',
      click: function() {
        todayWeekCalendar.prev();
      }
    },
    myCustomNext: {
      text: '',
      click: function() {
        todayWeekCalendar.next();
      }
    }
  },
  headerToolbar: {
    left: 'myCustomPrev',
    center: 'title',
    right: 'myCustomNext',
  },
  dayHeaderContent: function(info) {

      return  info.date.toLocaleDateString('ko-KR', {weekday: 'short'});
    },
  dayCellContent: function(dateInfo) {
    return {
      html: '<div class="fc-daygrid-day-number">' + dateInfo.date.getDate() + '</div>'
    };
  },
})

var rangeCalendarEl = document.getElementById('rangeCalendar');
var rangeCalendar = new FullCalendar.Calendar(rangeCalendarEl, {
  locale: 'ko',
  firstDay: 1,
  dayCellContent: function (info) {
    var number = document.createElement("a");
    number.classList.add("fc-daygrid-day-number");
    number.innerHTML = info.dayNumberText.replace("일", '');
    if (info.view.type === "dayGridMonth") {
      return {
        html: number.outerHTML
      };
    }
    return {
      domNodes: []
    };
  },
  customButtons: {
    myCustomPrev: {
      text: '',
      click: function() {
        rangeCalendar.prev();
      }
    },
    myCustomNext: {
      text: '',
      click: function() {
        rangeCalendar.next();
      }
    }
  },
  headerToolbar: {
    left: 'myCustomPrev',
    center: 'title',
    right: 'myCustomNext',
  },
})


var todoListCalendarEl = document.getElementById('todoListCalendar');
var todoListCalendar = new FullCalendar.Calendar(todoListCalendarEl, {
  locale: 'ko',
  firstDay: 1,
  editable: true,
  selectable: true, // false 설정하면 드래그 막음

  customButtons: {
    myCustomPrev: {
      text: '',
      click: function() {
        todoListCalendar.prev();
      }
    },
    myCustomNext: {
      text: '',
      click: function() {
        todoListCalendar.next();
      }
    }
  },
  headerToolbar: {
    left: '',
    center: 'myCustomPrev title myCustomNext',
    right: '',
  },
  dayCellContent: function (info) {
    var number = document.createElement("a");
    number.classList.add("fc-daygrid-day-number");
    number.innerHTML = info.dayNumberText.replace("일", '');
    if (info.view.type === "dayGridMonth") {
      return {
        html: number.outerHTML
      };
    }
    return {
      domNodes: []
    };
  },
  events: [
    {
      title: '수강상담',
      start: '2024-03-01',
    },
    {
      title: '정기상담',
      start: '2024-03-05',
    },
    {
      title: '수강상담',
      start: '2024-03-06',
    },
    {
      title: '정기상담',
      start: '2024-03-06',
    },


  ],
  eventClassNames: function(arg) { //class 부여하는곳입니다 사실상 스타일관여하는곳..
    if (arg.event.title === "수강상담") {
      return 'class-counseling';
    } else if(arg.event.title === "정기상담"){
      return 'regular-counseling';
    }
  },
  dateClick: function(date){
    console.log(date)
  },

});

var todoListCalendarTypeTwoEl = document.getElementById('todoListCalendarTypeTwo');
var todoListCalendarTypeTwo = new FullCalendar.Calendar(todoListCalendarTypeTwoEl, {
  locale: 'ko',
  firstDay: 1,
  editable: true,
  selectable: true,
  dayMaxEvents: true, // 더보기 이벤트
  dayMaxEvents: 2, // 보여질꺼갯수
  customButtons: {
    myCustomPrev: {
      text: '',
      click: function() {
        todoListCalendar.prev();
      }
    },
    myCustomNext: {
      text: '',
      click: function() {
        todoListCalendar.next();
      }
    }
  },
  headerToolbar: {
    left: '',
    center: 'myCustomPrev title myCustomNext',
    right: '',
  },
  dayCellContent: function (info) {
    var number = document.createElement("a");
    number.classList.add("fc-daygrid-day-number");
    number.innerHTML = info.dayNumberText.replace("일", '');
    if (info.view.type === "dayGridMonth") {
      return {
        html: number.outerHTML
      };
    }
    return {
      domNodes: []
    };
  },
  events: [
    {
      title: '수학 1주차 2회 강좌',
      start: '2024-03-01',
    },
    {
      title: '국어 1주차 2회 강좌',
      start: '2024-03-05',
    },
    {
      title: '수학 1주차 2회 강좌 수학 1주차 2회 강좌 수학 1주차 2회 강좌 수학 1주차 2회 강좌',
      start: '2024-03-06',
    },
    {
      title: '수학 1주차 2회 강좌 수학 1주차 2회 강좌 수학 1주차 2회 강좌 수학 1주차 2회 강좌',
      start: '2024-03-06',
    },
    {
      title: '수학 1주차 2회 강좌 수학 1주차 2회 강좌 수학 1주차 2회 강좌 수학 1주차 2회 강좌',
      start: '2024-03-06',
    },
    {
      title: '수학 1주차 2회 강좌 수학 1주차 2회 강좌 수학 1주차 2회 강좌 수학 1주차 2회 강좌',
      start: '2024-03-06',
    },
    {
      title: '수학 1주차 2회 강좌 수학 1주차 2회 강좌 수학 1주차 2회 강좌 수학 1주차 2회 강좌',
      start: '2024-03-06',
    },
    {
      title: '수학 1주차 2회 강좌 수학 1주차 2회 강좌 수학 1주차 2회 강좌 수학 1주차 2회 강좌',
      start: '2024-03-06',
    },
    {
      title: '수학 1주차 2회 강좌 수학 1주차 2회 강좌 수학 1주차 2회 강좌 수학 1주차 2회 강좌',
      start: '2024-03-06',
    },
    {
      title: '수학 1주차 2회 강좌 수학 1주차 2회 강좌 수학 1주차 2회 강좌 수학 1주차 2회 강좌',
      start: '2024-03-06',
    },
  ],
  eventClassNames: function(arg) {
    if(arg.event.title.includes('수학')){
      return 'schedule-calendar mathematics';
    }else if(arg.event.title.includes('국어')){
      return 'schedule-calendar k-language';
    }
  },
})

todayCalendar.render();
todayWeekCalendar.render();
rangeCalendar.render();
todoListCalendar.render();
todoListCalendarTypeTwo.render();

var windowWidth = window.innerWidth; // 현재 창의 넓이 확인
// 브라우저 넓이에 따라 다르게 contentHeight 설정
if (windowWidth < 768) { // 모바일 디바이스
  todayWeekCalendar.setOption('contentHeight', 80);
  todoListCalendar.setOption('contentHeight', 400);
  rangeCalendar.setOption('contentHeight', 400);
}else{
  todoListCalendar.setOption('contentHeight', 1240);
  todoListCalendarTypeTwo.setOption('contentHeight', 1240);
}

// 윈도우 리사이즈 이벤트 처리
window.addEventListener('resize', function() {
    var windowWidth = window.innerWidth;
    var contentHeight = calculateContentHeight(windowWidth);
    // 풀캘린더 객체를 찾아 contentHeight 속성을 업데이트
    todoListCalendar.setOption('contentHeight', contentHeight);
    todoListCalendarTypeTwo.setOption('contentHeight', contentHeight);
});

// 너비에 따른 콘텐츠 높이 계산
function calculateContentHeight(windowWidth) {
    // 적절한 높이 계산 로직을 구현하여 windowWidth에 따라 contentHeight를 조정
    // 예: windowWidth가 작을수록 contentHeight를 작게 설정
    var contentHeight;
    if (windowWidth < 768) {
        contentHeight = 400; // 작은 화면
    } else {
        contentHeight = 1240; // 큰 화면
    }
    return contentHeight;
}

$('#date-range53').dateRangePicker({
  container: '#date-range12-container',
  alwaysOpen:true,
  singleMonth: true,
  inline:true,
  language:'ko',
  startOfWeek: 'monday',
  showTopbar: false,
  getValue : function(date) //날짜는 주의 첫 번째 날이 됩니다.
  {

    $($(this).next().find('.next')).on('click', function(e){
      datechange($(this))
    });
    $($(this).next().find('.prev')).on('click', function(e){
      datechange($(this))
    });
    var datechange = (value) => {
      var hasFirstDateSelectedClass = value.parents('table').find('tbody tr td div.first-date-selected').length > 0;
      var haslastDateSelectedClass = value.parents('table').find('tbody tr td div.last-date-selected').length > 0;
      if(hasFirstDateSelectedClass || haslastDateSelectedClass){
        var firstday = value.parents('table').find('tbody tr td div.first-date-selected');
        var lastday = value.parents('table').find('tbody tr td div.last-date-selected');
          firstday.attr('data-day', firstday.text());
          lastday.attr('data-day', lastday.text());
        }
      }
    },
    }).on('datepicker-first-date-selected', function(event, obj){
      additionalFunction(obj.date1.getTime() / 1000 * 1000, obj.date1.getDate());
      $("#startDate").val(new Date(obj.date1).toLocaleDateString('ko-KR', { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\./g, '').replace(/\s/g, '.'));
    }).on('datepicker-change',function(event,obj) {
      additionalFunction(obj.date1.getTime() / 1000 * 1000, obj.date1.getDate());
      additionalFunction(obj.date2.getTime() / 1000 * 1000, obj.date2.getDate());
      $("#lastDate").val(new Date(obj.date2).toLocaleDateString('ko-KR', { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\./g, '').replace(/\s/g, '.'));
    })

    function additionalFunction(time, day) {
      $(`[time="${time}"]`).attr('data-day', day)
    }
    // api 사용법 날짜만넣으면 됩니다.
    function addDateUpdate(first, last){ //임시적인 함수입니다. 추후 개발진행예정입니다.
      $("#date-range53").data('dateRangePicker').setDateRange('2024-03-10','2024-03-25');
      $("#startDate").val('2024-03-10');
      $("#startDate").val('2024-03-25');
    }

});
