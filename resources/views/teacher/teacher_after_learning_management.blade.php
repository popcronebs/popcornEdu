
@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title')
    학습관리
@endsection

@section('add_css_js')
    <!-- <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script> -->
@endsection

{{-- 컨텐트 --}}
@section('layout_coutent')
<!-- : 1. 학습관리 -->
<!-- : 5. 검색 단어 -->
<!-- : 6. 전체클래스 검색.[ok] -->
<!-- : 일괄 출석 -->
<!-- : 2. 미수강, 오답 미완료 -->
<!-- : 3. 학습플래너 -->
<!-- : 4. 기간설정 ? 어디에 쓰나? - 삭제됨 -->
<!-- : 문자/알림톡 = 알림톡은 아이디가 없으므로 불가. -->
<!--: 일괄 수업종료 -->

<!-- TODO: 일괄 수업종료 시 학부모 알림 전송 -->
<!-- TODO: 선택 학생 보강 등록  -->
<!-- TODO: 학습관리 DETAIL 화면 제작-->


<div class="row pt-2 zoom_sm" data-div-main="after_learning_management">
    <input type="hidden" data-main-teach-seq value="{{ $teach_seq }}">
    <input type="hidden" data-main-team-code  value="{{ $team_code }}">

    <div class="sub-title" data-sub-title="basic">
        <h2 class="text-sb-42px">
            <img src="{{ asset('images/my_study_icon2.svg?1') }}" width="72">
            학습관리
        </h2>
    </div>

    <section>
       <div class="d-flex justify-content-between align-items-end mb-32">
        <div>
            <label class="d-inline-block select-wrap select-icon">
                <select data-select-classes onchange="chks = {};afterLearningStudentSelect();"
                    class="date-change rounded-pill border-gray sm-select text-sb-20px me-2 h-52">
                    <option value="">전체 클래스</option>
                    @if (!empty($classes))
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                        @endforeach
                    @endif
                </select>
            </label>
        </div>
        <div class="h-center">
            <label class="row select-wrap select-icon" hidden>
                <select id="select2"
                    onchange="teachManSelectDateType(this, '[data-search-start-date]','[data-search-end-date]'); chks = {};afterLearningStudentSelect();"
                    class="date-change rounded-pill border-gray sm-select text-sb-20px me-2 h-52">
                    <option value="">기간설정</option>
                    <option value="-1">오늘로보기</option>
                    <option value="0">1주일전</option>
                    <option value="1">1개월전</option>
                    <option value="2">3개월전</option>
                </select>
            </label>
            <div class="h-center p-3 border rounded-pill px-3">
                <img src="{{ asset('images/calendar_gray_icon.svg') }}" class="me-2">
                <div data-bundle-date class="overflow-hidden col-auto cursor-pointer text-start"
                    style="height: 20px;">
                    <div class="h-center justify-content-between">
                        <div data-date
                            onclick="this.closest('[data-bundle-date]').querySelector('input').showPicker()"
                            type="text" class="text-m-20px text-start scale-text-gray_05" readonly=""
                            placeholder="">
                            {{-- 상담시작일시 --}}
                            {{ date('Y.m.d') }}
                        </div>
                        <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                    </div>
                    <input type="date" style="width: 80px;height: 0.5px;" data-search-start-date
                        oninput="teachManDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                </div>
                <div data-bundle-date class="overflow-hidden col-auto cursor-pointer text-start"
                    style="height: 20px;" hidden>
                    <div class="h-center justify-content-between">
                        <div data-date
                            onclick="this.closest('[data-bundle-date]').querySelector('input').showPicker()"
                            type="text" class="text-m-20px text-start scale-text-gray_05" readonly=""
                            placeholder="">
                            {{-- 상담시작일시 --}}
                            {{ date('Y.m.d') }}
                        </div>
                        <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" data-edit="hidden" hidden>
                    </div>
                    <input type="date" style="width: 80px;height: 0.5px;" data-search-end-date
                        oninput="teachManDateTimeSel(this)" value="{{ date('Y-m-d') }}">
                </div>

            </div>
            <div class="d-flex ms-3">
                <div class="d-inline-block select-wrap select-icon me-12">
                    <select data-select-after-learin-search-type="1"
                        class="rounded-pill border-gray lg-select text-sb-20px">
                        <option value="">검색기준</option>
                        <option value="grade_name">학년</option>
                        <option value="name">이름</option>
                        <option value="student_phone">전화번호</option>
                        <option value="student_id">아이디</option>
                        <option value="class_name">방과후 클래스</option>
                        <option value="parent_name">학부모 이름</option>
                    </select>
                </div>
                <label class="label-search-wrap">
                    <input type="text" data-inp-teach-st-after-search-str="1"
                        onkeyup="if(event.keyCode == 13){chks = {};afterLearningStudentSelect();} "
                        class="ms-search border-gray rounded-pill text-m-20px w-100" placeholder="단어를 검색해보세요.">
                </label>
            </div>
        </div>
    </div>
    </section>

    <section>
        <div class="col-12 ">
            <table class="table-style w-100" style="min-width: 100%;">
                <colgroup>
                    <col style="width: 80px;">
                </colgroup>
                <thead class="">
                    <tr class="text-sb-20px modal-shadow-style rounded">
                        <th style="width: 80px" class="td_checkbox">
                            <label class="checkbox mt-1">
                                <input type="checkbox" id="inp_all_chk"  onchange="afterLearningAllChk(this)">
                                <span class="">
                                </span>
                            </label>
                        </th>
                        <th class="text-center">학년/반</th>
                        <th class="text-center">방과후 클래스</th>
                        <th class="text-center">이름/아이디</th>
                        <th class="text-center">휴대전화</th>
                        <th class="text-center">출석 및 보강</th>
                        <th class="text-center">보강</th>
                        <th class="text-center">학습상태</th>
                        <th class="text-center td_checkbox">학습플래너</th>
                        <th class="text-center td_checkbox">학습관리</th>
                    </tr>
                </thead>
                <tbody data-bundle="tby_students">
                    <tr class="text-m-20px h-104" data-row="copy" hidden>
                        <input type="hidden" data-student-seq>
                        <input type="hidden" data-parent-seq>
                        <td class="td_checkbox">
                            <label class="checkbox mt-1">
                                <input type="checkbox" class="chk" onchange="afterLearningChkInput(this);">
                                <span class="">
                                </span>
                            </label>
                        </td>
                        <td class="text-sb-20px">
                            <span data-grade-name data-explain="#학년"></span>/
                            <span data-st-class-name data-explain="#반"></span>
                        </td>
                        <td class="text-sb-20px">
                            <span data-class-name data-explain="#방과후 클래스"></span>
                        </td>
                        <td class="text-sb-20px text-dark">
                            <span data-student-name data-explain="#이름"></span>
                            <span data-student-id data-explain="#아이디"></span>
                        </td>
                        <td class="text-sb-20px">
                            <span data-student-phone data-explain="#휴대전화"></span>
                        </td>
                        <td class="text-sb-20px">
                            <div data-is-attend hidden>
                                <span data-explain="#출석">출석</span>
                                <span data-attend-datetime data-explain="#23.09.13 15:23"></span>
                            </div>
                            <div data-is-now hidden>
                                <span data-is-continue>수업중</span>
                                <span data-is-end class="rounded-pill bg-danger text-white text-r-15px p-2">수업종료</span>
                            </div>
                            <div data-is-absent class="row mx-0 justify-content-center align-items-center" hidden>
                                <span class="text-danger col-auto">결석</span>
                                <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12" class="mx-2 col-auto">
                                <div data-div-absent-reason="" class="h-center select-wrap select-icon h-62 pe-6 col-auto">
                                    <select data-absent-reason="" class="border-none lg-select text-sb-20px h-52 py-1 rounded" onchange="classStartAbsentReson(this)" disabled>
                                        <option value="">결석사유</option>
                                        <option value="개인사유">개인사유</option>
                                        <option value="교내행사">교내행사</option>
                                        <option value="공휴일">공휴일</option>
                                        <optoin vlaue="자연재해">자연재해</optoin>
                                        <option vlaue="기타">기타</option>
                                    </select>
                                </div>
                            </div>
                        </td>

                        <td class="text-sb-20px">
                            <div class="row row-cols-1 v-center">
                                <span data-reinforcement-date data-explain="#보강"></span>
                            </div>
                        </td>
                        <td class="text-sb-20px">
                            <div> 미수강 <span data-incomplete-cnt>0</span>개 </div>
                            <div> 오답 미완료 <span data-inwrong-cnt>0</span>개 </div>
                        </td>
                        <td class="text-sb-20px td_checkbox">
                            <button onclick="afterLearningMovePage(this, 'lplan')";
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom">학습플래너</button>
                        </td>
                        <td class="text-sb-20px td_checkbox">
                            <button onclick="afterLearningMovePage(this, 'lmanage')";
                                class="btn-line-xss-secondary text-sb-20px border-dark rounded scale-bg-white scale-text-black px-3 me-2 align-bottom">학습관리</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-52">
            <div class=""></div>

            {{-- 페이징 기능. --}}
            <div class="my-custom-pagination">
                <div class="col d-flex justify-content-center">
                    <ul class="pagination col-auto" data-page="1" hidden>
                        <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="1"
                            onclick="afterLearningPageFunc('1', 'prev')">
                            <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                        </button>
                        <li class="page-item" hidden>
                            <a class="page-link" onclick="">0</a>
                        </li>
                        <span class="page" data-page-first="1" hidden
                            onclick="afterLearningPageFunc('1', this.innerText);" disabled>0</span>
                        <button href="javascript:void(0)" class="btn p-0 next" data-page-next="1"
                            onclick="afterLearningPageFunc('1', 'next')" data-is-next="0">
                            <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                        </button>
                    </ul>
                </div>
            </div>
            <div>
                <button type="button" onclick="afterLearningExcelDownload()"
                    class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg" class="me-1">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M10.8649 16.6265C10.2489 16.6265 9.74959 16.1271 9.74959 15.5111V10.8574L6.87216 10.8574C5.84408 10.8574 5.33501 9.60924 6.06985 8.89023L11.1707 3.89928C11.6166 3.46299 12.3294 3.46299 12.7753 3.89928L17.8762 8.89024C18.611 9.60924 18.1019 10.8574 17.0739 10.8574L14.166 10.8574V15.5111C14.166 16.1271 13.6667 16.6265 13.0507 16.6265H10.8649Z"
                            fill="#DCDCDC"></path>
                        <rect x="5.57031" y="17.8208" width="12.8027" height="1.75074" rx="0.875369"
                            fill="#DCDCDC"></rect>
                    </svg>
                    Excel 내보내기
                </button>
            </div>
        </div>


    </section>


    <div class="mt-5 scale-bg-gray_01 h-center justify-content-between p-4 rounded">
        <div class="col">
           <button type="button" onclick="afterLearningSmsModalOpen();"
           class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2"> SMS 문자/알림톡 </button>
           <button type="button" onclick=""
           class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2"> 선택 학생 보강등록 </button>
        </div>
        <div class="col text-end">
            <button type="button" onclick="afterLearningMovePlaner()"
            class="btn-line-xss-secondary text-sb-20px border-dark rounded scale-bg-white text-dark px-3 me-2 align-bottom ">일괄 학습플래너 수정</button>
            <button type="button" onclick="afterLearningDoAttend()"
            class="btn-line-xss-secondary text-sb-20px border-dark rounded scale-bg-white text-dark px-3 me-2 align-bottom ">일괄 출석</button>
            <button type="button" onclick="afterLearningEndClassBtnClick()"
            class="btn-line-xss-secondary text-sb-20px border-dark rounded scale-bg-white text-dark px-3 me-2 align-bottom">일괄 수업종료</button>
        </div>

    </div>

    <div data-explain="160px">
        <div class="py-lg-5"></div>
        <div class="py-lg-4"></div>
        <div class="pt-lg-3"></div>
    </div>

</div>


{{-- 모달 / 상담일정 알림 발송 / 여기 안에 select_member 배열 있으므로, 확인 --}}
@include('admin.admin_alarm_detail')

<!-- manage/learning?student_seq=2714 -->
<!-- 학습플래너 페이지 이동. -->
<form action="/manage/learning" method="post" data-form-learningplan  target="_self">
    @csrf
    <input type="hidden" name="student_seq" data-form-student-seq>
</form>

<!-- 학습관리 상세페이지 이동 -->
<form action="/teacher/after/learning/management/detail" method="post" data-form-learningdetail target="_self">
    @csrf
    <input type="hidden" name="student_seq" data-form-student-seq>
</form>



<script>
const today_study_dates = <?php echo json_encode($class_study_dates); ?>;
// const today_study_dates = [];
let student_info = {};

afterLearningStudentSelect();
// 기간설정 select onchange
function teachManSelectDateType(vthis, start_date_tag, end_date_tag) {
    const inp_start = document.querySelector(start_date_tag);
    const inp_end = document.querySelector(end_date_tag);

    // 0 = 기간설정 지난1주일 // end_date 에서 -7일을 start_date에 넣어준다.
    if (vthis.value == 0) {
        const end_date = new Date(inp_end.value);
        end_date.setDate(end_date.getDate() - 7);
        inp_start.value = end_date.toISOString().substr(0, 10);
    }
    // 1 = 1개월
    else if (vthis.value == 1) {
        const end_date = new Date(inp_end.value);
        end_date.setMonth(end_date.getMonth() - 1);
        inp_start.value = end_date.toISOString().substr(0, 10);
    }
    // 2 = 3개월
    else if (vthis.value == 2) {
        const end_date = new Date(inp_end.value);
        end_date.setMonth(end_date.getMonth() - 3);
        inp_start.value = end_date.toISOString().substr(0, 10);
    }
    //-1 오늘
    else if (vthis.value == -1) {
        inp_start.value = "{{ date('Y-m-d') }}";
        inp_end.value = "{{ date('Y-m-d') }}";
    }
    // onchage()
    // onchange 이벤트가 있으면 실행
    if (inp_start.oninput)
    inp_start.oninput();
    if (inp_end.oninput)
    inp_end.oninput();
}

// 만든날짜 선택
function teachManDateTimeSel(vthis) {
    //datetime-local format yyyy.MM.dd HH:mm 변경
    const date = new Date(vthis.value);
    vthis.closest('[data-bundle-date]').querySelector('[data-date]').innerText = date.format('yyyy.MM.dd')
}



function afterLearningStudentSelect(page_num){
    const teach_seq = document.querySelector('[data-main-teach-seq]').value;
    const page = "/teacher/main/after/class/student/select";
    const search_type = document.querySelector('[data-select-after-learin-search-type="1"]').value;
    const search_str = document.querySelector('[data-inp-teach-st-after-search-str="1"]').value;
    const attend_date = document.querySelector('[data-search-start-date]').value;

    //클래스
    const class_seq = document.querySelector('[data-select-classes]').value;

    const parameter = {
        is_add_coulmn:"Y",
        teach_seq_post:teach_seq,
        search_type:search_type,
        search_str:search_str,
        attend_date:attend_date,
        is_page:"Y",
        page:page_num||1,
    }
    if(class_seq) parameter.class_seq = class_seq;
    else parameter.no_class="Y";

    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            document.querySelector('#inp_all_chk').checked = false;
            //* 학생정보 data-bundle="tby_students" data-row="copy"
            // 테이블 초기화 후 클론 row 넣어주기
            const bundle = document.querySelector('[data-bundle="tby_students"]');
            const copy_row = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(copy_row);

            const students = result.class_mates ;

            const now_time = new Date().format('HH:mm:00');
            const five_time = new Date().setMinutes(new Date().getMinutes() - 5);

            afterLearningTablePaging(students, '1');

            const incomplete_cnts = result.incomplete_cnts;
            const inwrong_cnts = result.inwrong_cnts;

            students.data.forEach(function(student){
                student_info[student.student_seq] = student;
                const row = copy_row.cloneNode(true);
                // const ts_time = today_study_dates[student.class_seq]?.start_time || '00:00:00';
                // TEST:
                // const ts_time = new Date().format('HH:mm:ss');
                const ts_time = '17:40:00' ;
                // today_study_dates[student.class_seq].end_time = '19:40:00' ;
                const is_not_class_today = today_study_dates[student.class_seq]?.start_time ? false : true;
                const class_end_time = today_study_dates[student.class_seq]?.end_time || '00:00:00';
                student_info[student.student_seq].class_start_time = ts_time;

                row.setAttribute('data-row', 'clone');
                row.hidden = false;
                row.querySelector('[data-student-seq]').value = student.student_seq;
                row.querySelector('[data-parent-seq]').value = student.parent_seq;
                // 코딩 삽입 시작.
                row.querySelector('[data-grade-name]').innerText = student.grade_name || '';
                row.querySelector('[data-class-name]').innerText = student.cl_class_name || '';
                row.querySelector('[data-st-class-name]').innerText = student.class_name || '';
                row.querySelector('[data-student-name]').innerText = student.student_name || '';
                row.querySelector('[data-student-id]').innerText = `(${student.student_id || ''})`;
                row.querySelector('[data-student-phone]').innerText = student.student_phone || '';
                row.querySelector('[data-incomplete-cnt]').innerText = incomplete_cnts[student.student_seq]?.incomplete_cnt || 0;
                row.querySelector('[data-inwrong-cnt]').innerText = getWrongCnt(inwrong_cnts, student.student_seq) || 0;
                // 오늘 출석일 경우.
                if(student.attend_datetime) {
                    //출석 div 보이게
                    row.querySelector('[data-is-attend]').hidden = false;
                    row.querySelector('[data-attend-datetime]').innerText = (student.attend_datetime||'').substr(2, 14).replace(/-/g, '.');

                    // data-is-continue 인지 data-is-end 인지 확인
                    row.querySelector('[data-is-now]').hidden = false;
                    if(/* class_end_time < now_time || */ student.is_complete == 'Y'){
                        row.querySelector('[data-is-continue]').hidden = true;
                        row.querySelector('[data-is-end]').hidden = false;
                    }else{
                        row.querySelector('[data-is-continue]').hidden = false;
                        row.querySelector('[data-is-end]').hidden = true;
                    }

                    //나머지 숨김처리.
                    row.querySelector('[data-is-absent]').hidden = true;
                } else {
                    //TODO: 추후 30분 이전가지는 지각으로!? 추가 예정.
                    if (ts_time < new Date(five_time).format('HH:mm:00')) {
                        // 정각에서 5분 이후.
                        // 결석처리.
                        // 결석사유
                        row.querySelector('[data-is-absent]').hidden = false;
                        row.querySelector('[data-absent-reason]').value = student.absent_reason || '';

                        row.querySelector('[data-is-attend]').hidden = true;
                        row.querySelector('[data-is-now]').hidden = true;
                    }else {
                        // 0~5분까지 늦을때
                        row.querySelector('[data-is-now]').hidden = false;
                        row.querySelector('[data-is-continue]').innerText = '미출석';
                        row.querySelector('[data-is-end]').hidden = true;
                    }
                }
                // 보강일
                if(student.ref_date)
                    row.querySelector('[data-reinforcement-date]').value = ref_date;

                if(is_not_class_today){
                    // 출석 및 보강 td 내부 모두 숨김처리.
                    row.querySelector('[data-is-attend]').hidden = true;
                    row.querySelector('[data-is-now]').hidden = true;
                    row.querySelector('[data-is-absent]').hidden = true;
                }
                // 코딩 삽입 종료
                bundle.appendChild(row);
                if(chks[student.student_seq]){
                    row.querySelector('.chk').checked = true;
                }
            });

        }else{}
    });
}

// 페이징  함수
function afterLearningTablePaging(rData, target){
    if(!rData) return;
    const from = rData.from;
    const last_page = rData.last_page;
    const per_page = rData.per_page;
    const total = rData.total;
    const to = rData.to;
    const current_page = rData.current_page;
    const data = rData.data;
    //페이징 처리
    const notice_ul_page = document.querySelector(`[data-page='${target}']`);
    //prev button, next_button
    const page_prev = notice_ul_page.querySelector(`[data-page-prev='${target}']`);
    const page_next = notice_ul_page.querySelector(`[data-page-next='${target}']`);
    //페이징 처리를 위해 기존 페이지 삭제
    notice_ul_page.querySelectorAll(".page_num").forEach(element => {
        element.remove();
    });
    //#page_first 클론
    const page_first = document.querySelector(`[data-page-first='${target}']`);
    //페이지는 1~10개 까지만 보여준다.
    let page_start = 1;
    let page_end = 10;
    if(current_page > 5){
        page_start = current_page - 4;
        page_end = current_page + 5;
    }
    if(page_end > last_page){
        page_end = last_page;
        if(page_end <= 10)
        page_start = 1;
    }


    let is_next = false;
    for(let i = page_start; i <= page_end; i++){
        const copy_page_first = page_first.cloneNode(true);
        copy_page_first.innerText = i;
        copy_page_first.removeAttribute("data-page-first");
        copy_page_first.classList.add("page_num");
        copy_page_first.hidden = false;
        //현재 페이지면 active
        if(i == current_page){
            copy_page_first.classList.add("active");
        }
        //#page_first 뒤에 붙인다.
        notice_ul_page.insertBefore(copy_page_first, page_next);
        //시작 페이지 보다 11보다 큰 i 이면 page_prev disabled 을 제거
        if(i > 11){
            page_next.setAttribute("data-is-next", "1");
            page_prev.classList.remove("disabled");
        }else{
            page_next.setAttribute("data-is-next", "0");
        }
        if(i == 1){
            // page_prev.classList.add("disabled");
        }
        if(last_page == i){
            // page_next.classList.add("disabled");
            is_next = true;
        }
    }
    if(!is_next){
        page_next.classList.remove("disabled");
    }

    if(data.length != 0)
    notice_ul_page.hidden = false;
    else
    notice_ul_page.hidden = true;
}

// 페이지 이동 함수
function afterLearningMovePage(vthis, type){
    const tr = vthis.closest('tr');
    const student_seq = tr.querySelector('[data-student-seq]').value;

    switch(type){
        // 학습관리로 이동.
        case 'lmanage':
            if(tr.querySelector('[data-parent-seq]').value == ''){
                toast('학부모 정보가 없습니다.');
                return;
            }
            const form = document.querySelector('[data-form-learningdetail]');
            form.querySelector('[data-form-student-seq]').value = student_seq;
            form.submit();
        break;
        // 학습플래너로 이동.
        case 'lplan':
            const form2 = document.querySelector('[data-form-learningplan]');
            form2.querySelector('[data-form-student-seq]').value = student_seq;
            form2.submit();
        break;
    }
}


//페이지 펑션
function afterLearningPageFunc(target, type){
    if(type == 'next'){
        const page_next = document.querySelector(`[data-page-next="${target}"]`);
        if(page_next.getAttribute("data-is-next") == '0') return;
        // data-page 의 마지막 page_num 의 innerText를 가져온다
        const last_page = document.querySelector(`[data-page="${target}"] .page_num:last-of-type`).innerText;
        const page = parseInt(last_page) + 1;
        afterLearningStudentSelect(page);
    }
    else if(type == 'prev'){
        // [data-page-first]  next tag 의 innerText를 가져온다
        const page_first = document.querySelector(`[data-page-first="${target}"]`);
        const page = page_first.innerText;
        if(page == 1) return;
        const page_num = page*1 -1;
        afterLearningStudentSelect(page);
    }
    else{
        afterLearningStudentSelect(type);
    }
}

//선택 학생 일괄 춣석
function afterLearningDoAttend(is_pass) {
    const users = [];
    //chks 의 키를 가져와서 배열로 만듬.
    const keys = Object.keys(chks);

    keys.forEach(key  => {
        const  student = student_info[key];
        users.push({
            student_seq: student.student_seq,
            class_seq: student.class_seq,
            team_code: student.team_code,
            class_name: student.class_name,
            class_start_time: student.class_start_time
        })
    });

    if (users.length < 1) {
        toast("선택된 학생이 없습니다.");
        return;
    }

    const page = '/teacher/main/after/class/student/attend';
    const parameter = {
        users:users
    };
    // 선택 학생 출석 처리 하시겠습니까?
    const msg =
        ` <div class="text-sb-28px">선택 학생 출석 처리 하시겠습니까?</div>`;
    sAlert('', msg, 3, function() {
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                if(result.no_attend_students.length > 0){
                    // 모두 클래스 수업이 없으므로, 알려줌.
                    if(users.length == result.no_attend_students.length){
                        const msgf1 = `<div class="text-sb-24px">선택학생 중 오늘 수업인 학생이 없습니다.</div>`;
                        setTimeout(function(){
                            sAlert('', msgf1, 4);
                        }, 1000);
                    }else{
                        let student_names = '';
                        result.no_attend_students.forEach(st_seq => {
                            const student_name = student_info[st_seq].student_name;
                            delete chks[st_seq];
                            if(student_names != '') student_names += ', ';
                            student_names += student_name;
                        });
                        const msgf2 = `<div class="text-sb-20px">${student_names} 는(은) 오늘 수업이 없습니다. <br>위 학생 제외 다른 학생은 출석처리 되었습니다.</div>`;
                        setTimeout(function(){
                            sAlert('', msgf2, 4);
                        }, 1000);
                        afterLearningStudentSelect();
                    }
                }else{
                    toast('출석처리 되었습니다.');
                    afterLearningStudentSelect();
                }
            }
        });
    }, null, '네', '아니오');
}


// 수업종료 버튼 클릭.
function afterLearningEndClassBtnClick() {
    // 현재 출석 중인 학생 모두 text-sb-28px
    // 수엊봉료 상태로 변경하시겠습니까? text-danter
    // (종료시 학부모님께 알림톡이 전송됩니다.) m-20px scale-text-gray_05
    const msg =
        `
        <div class="text-sb-28px">현재 출석 중인 학생 모두</div>
        <div class="text-sb-28px text-danger mt-2">수업종료 상태로 변경하시겠습니까?</div>
        <div class="text-m-20px scale-text-gray_05 mt-3">(종료시 학부모님께 알림톡이 전송됩니다.)</div>
        `;
    sAlert('', msg, 3, function() {
        const users = [];
        const attend_date = document.querySelector('[data-search-start-date]').value;

        const keys = Object.keys(chks);
        keys.forEach(key  => {
            const  student = student_info[key];
            users.push({
                student_seq: student.student_seq,
                class_seq: student.class_seq,
            })
        });
        if(users.length < 1){
            toast('학생이 없습니다. 다시한번 확인해주세요.');
            return;
        }

        const page = "/teacher/after/learning/management/class/student/attend/end";
        const parameter = {
            users:users,
            attend_date:attend_date
        };
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                if(result.no_attend_students.length > 0){
                    if(users.length == result.no_attend_students.length){
                        const msgf1 = `<div class="text-sb-24px">선택학생 중 오늘 수업인 학생이 없습니다.</div>`;
                        setTimeout(function(){
                            sAlert('', msgf1, 4);
                        }, 1000);
                    }else{
                        let student_names = '';
                        result.no_attend_students.forEach(st_seq => {
                            const student_name = student_info[st_seq].student_name;
                            delete chks[st_seq];
                            if(student_names != '') student_names += ', ';
                            student_names += student_name;
                        });
                        const msgf2 = `<div class="text-sb-20px">${student_names} 는(은) 오늘 수업이 없습니다. <br>위 학생 제외 다른 학생은 수업종료처리 되었습니다.</div>`;
                        setTimeout(function(){
                            sAlert('', msgf2, 4);
                        }, 1000);
                        afterLearningStudentSelect();
                    }
                }else{
                    toast('수업종료 처리 되었습니다.');
                    afterLearningStudentSelect();
                }
            }
        });

    }, null, '네', '아니오');
}

// 선택 보강 등록
function afterLearningAddReinforcementDate(vthis) {
    const tr = vthis.closest('tr');
    const student_seq = tr.querySelector('[data-student-seq="modal"]').value;
    const ref_date = vthis.value;
    const class_seq = document.querySelector('input[data-main-class-seq]').value;
    const team_code = document.querySelector('input[data-main-team-code]').value;

    //오늘 이전날은 선택할수 없습니다.
    if (new Date(ref_date) < new Date().setHours(0, 0, 0, 0)) {
        toast('오늘 이전날은 선택할수 없습니다.');
        vthis.value = '0000-00-00';
        return;
    }

    const page = '/teacher/main/after/class/student/reinforcement/date/insert';
    const parameter = {
        class_seq: class_seq,
        team_code: team_code,
        student_seq: student_seq,
        ref_date: ref_date
    };
    // 선택하신 날짜로 보강일을 등록하시겠습니까?
    const msg =
        `
<div class="text-sb-28px">선택하신 날짜로 보강일을 등록하시겠습니까?</div>
`;
    sAlert('', msg, 3, function() {
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                // vthis.closest('[data-bundle-date]').querySelector('[data-date]').innerText = ref_date;
                toast('보강일 등록 되었습니다.');
                //select function
            } else {
                toast('다시 시도 해주세요.');
            }
        });
    }, function() {
            vthis.value = '0000-00-00';
        });
}

// 오답 미완료 가져오기.
function getWrongCnt(datas, student_seq){
    let cnt = 0;

    // TODO: 둘줄 하나 선택
        datas[student_seq]?.forEach(function(data){
            // 추후 둘중하나 선택.
            // 오답 문제 갯수:
            cnt += data.wrong_count*1;
            // 오답 강의 갯수:
            // cnt ++;

        });
    return cnt;
}
let chks = {};
// 체크박스 체크
function afterLearningChkInput(vthis){
    const tr = vthis.closest('tr');
    const student_seq = tr.querySelector('[data-student-seq]').value;
    if(vthis.checked){
        chks[student_seq] = {
            student_seq:student_seq,
            outer_html:tr.outerHTML
        };
    }else{
        delete chks[student_seq];
    }
}

// 엑셀로 내보내기
function afterLearningExcelDownload(){
    // 체크가 있는지 확인
    const keys = Object.keys(chks);
    if(keys.length < 1){
        toast('선택된 회원이 없습니다.');
        return;
    }

    const table = document.querySelector('[data-bundle="tby_students"]').closest('table').cloneNode(true);
    const bundle = table.querySelector('[data-bundle="tby_students"]');
    bundle.innerHTML = '';

    // keys 반대 정렬.
    keys.reverse();
    keys.forEach(function(key){
        bundle.innerHTML += chks[key].outer_html;
    });

    // td 체크박스 +(불필요한) 삭제.
    table.querySelectorAll('.td_checkbox').forEach(function(td){
        td.remove();
    });

    bundle.querySelectorAll('tr').forEach((item)=>{
        if(item.hidden)
        item.remove();
        //tr 안에 태그중 style display:none인 태그는 제거
        //tr 안에 태그중 type=hidden 인 태그 제거
        //tr 안에 태그중 hidden = true인 태그는 제거
        //checkbox 제거
        item.querySelectorAll('[style*="display:none"], button, [type="hidden"], [hidden], input[type=checkbox]').forEach((item2)=>{
            item2.remove();
        });
        //radio 는 Y,N 으로 span 글자로 변경 후 삭제
        item.querySelectorAll('input[type=checkbox]').forEach((item2)=>{
            if(item2.checked)
            item2.insertAdjacentHTML('afterend', '<span>Y</span>');
            else
            item2.insertAdjacentHTML('afterend', '<span>N</span>');
            item2.remove();
        });
    });

    const html = table.outerHTML;
    _excelDown('학생목록.xls', '학생목록', html);
}

async function afterLearningSmsModalOpen(){
    const keys = Object.keys(chks);
    if(keys.length < 1){
        toast('선택된 학생이 없습니다.');
        return;
    }
    const student_seqs = [];
    keys.forEach(function(key){
        student_seqs.push(chks[key].student_seq);
    });
    if(await alarmSendGetSmsInfo(student_seqs)){
        alarmSendSmsModalOpen();
        alarmSelectUser();
    }
}

// 일괄 학습플래너 수정.
function afterLearningMovePlaner(){
    // 체크된 학생 가져오기.
    const keys = Object.keys(chks);
    const student_seqs = [];
    if(keys.length < 1){
        toast('선택된 학생이 없습니다.');
        return;
    }
    keys.forEach(function(key){
        student_seqs.push(chks[key].student_seq);
    });
    const form = document.querySelector('[data-form-learningplan]');
    form.querySelector('[data-form-student-seq]').value = student_seqs.join(',');
    form.submit();
}

// 현제 패이지 전체 체크 박스 체크
function afterLearningAllChk(vthis){
    const bundle = document.querySelector('[data-bundle="tby_students"]');
    const rows = bundle.querySelectorAll('[data-row="clone"]');
    const checked = vthis.checked;
    rows.forEach(function(row){
        row.querySelector('.chk').checked = checked;
        row.querySelector('.chk').onchange();
    });
}
</script>
@endsection
