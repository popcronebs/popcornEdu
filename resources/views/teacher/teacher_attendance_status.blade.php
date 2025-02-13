<!-- TODO: 해당없음은 선택을 하는건지 확인필요. -->
<!-- TODO: ?? 기준이 없음 -->

<section>
    <div class="row mx-0 gap-3 justify-content-end mb-4">
        <div class="scale-bg-gray_01 scale-text-gray_05 text-sb-20px rounded p-3 col-auto">
            <span class="">출석현황</span>
            </span>
            <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12" class="mx-2">
            <span>
                <span class="scale-text-black" data-attend-in-cnt></span>
                <span>/<span data-attend-total-cnt> </span>일</span>
            </span>
        </div>

        <div class="scale-bg-gray_01 scale-text-gray_05 text-sb-20px rounded p-3 col-auto">
            <span class="">학습현황</span>
            <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12" class="mx-2">
            <!-- TODO: ?? 기준이 없음 -->
            <span class="scale-text-black "></span>
        </div>
    </div>
</section>

<!-- 테이블1 -->
<section>

    <div class="col-12">
        <table class="w-100 table-style table-h-82">
            <thead class="modal-shadow-style rounded">
                <tr class="text-sb-20px ">
                    <th>주차</th>
                    <th>날짜</th>
                    <th>출결현황</th>
                    <th>학습현황</th>
                </tr>
            </thead>
            <tbody data-bundle="attend_lists">
                <tr class="text-m-20px" data-row="copy" hidden>
                    <input type="hidden" data-absent-seq>
                    <input type="hidden" data-attend-seq>
                    <input type="hidden" data-sel-date>
                    <td data-ju></td>
                    <td>
                        <span data-date> </span>
                        <span data-day> </span>
                    </td>
                    <td>
                        <span data-content="1"> </span>
                        <span hidden>
                            <img src="{{asset('images/bar_icon.svg')}}" width="2" height="12" class="ms-2 me-2">
                            <span data-content="2"> </span>
                        </span>
                    </td>
                    <td class="scale-text-black" data-lecture-status>목표 학습 완료</td>
                </tr>

            </tbody>
        </table>
    </div>
    <!-- 페이징  -->
    <div class="all-center mt-52">
        <div class=""></div>
        <div class="col-auto">
            {{-- 페이징 --}}
            <div class="col d-flex justify-content-center">
                <ul class="pagination col-auto" data-page="1" hidden>
                    <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="1"
                        onclick="attendPageFunc('1', 'prev')">
                        <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                    </button>
                    <li class="page-item" hidden>
                        <a class="page-link" onclick="">0</a>
                    </li>
                    <span class="page" data-page-first="1" hidden onclick="attendPageFunc('1', this.innerText);"
                        disabled>0</span>
                    <button href="javascript:void(0)" class="btn p-0 next" data-page-next="1"
                        onclick="attendPageFunc('1', 'next')" data-is-next="0">
                        <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                    </button>
                </ul>
            </div>
        </div>
    </div>

</section>

<!-- 테이블2 -->
<section class="mt-5">
    <table class="w-100 table-style table-h-82">
        <thead class="modal-shadow-style rounded">
            <tr class="text-sb-20px ">
                <th>날짜</th>
                <th>결석 사유</th>
                <th>보강일자</th>
                <th>완료 여부</th>
            </tr>
        </thead>
        <tbody data-bundle="absent_lists">
            <tr class="text-m-20px" data-row="copy" hidden>
                <input type="hidden" data-absent-seq>
                <input type="hidden" data-sel-date>
                <td data-date></td>
                <td data-absent-reason></td>
                <td data-ref-date-td></td>
                <td data-is-ref-complete></td>
            </tr>
        </tbody>
    </table>

</section>

<!-- TODO: 기능 필요. -->
<script>

attendMakeMonthList();

// 월간 만들기
function attendMakeMonthList(is_click) {
    if(is_click == undefined) is_click = false;
    const aside = document.querySelector('[data-aside="main_aside"]');
    const div_month_bundle = aside.querySelector('.div_month_bundle');
    const copy_div_month_row = div_month_bundle.querySelector('.div_month_row').cloneNode(true);
    const sel_date = aside.querySelector('#my_study_span_year').getAttribute('data');
    // 초기화
    div_month_bundle.innerHTML = '';
    div_month_bundle.appendChild(copy_div_month_row);

    // 12개월 만들기 / 현재 월 선택
    const now_date = new Date();
    const now_month = now_date.getMonth() + 1;
    const sel_month = new Date(sel_date).getMonth() + 1;

    for (let i = 0; i < 12; i++) {
        const copy_div_month_row = div_month_bundle.querySelector('.div_month_row').cloneNode(true);
        const div_month_row = copy_div_month_row.cloneNode(true);
        div_month_row.hidden = false;
        if (i > 2) {
            div_month_row.classList.add('pt-1');
        }
        div_month_row.querySelector('.month_cnt').textContent = i + 1;
        div_month_bundle.appendChild(div_month_row);
        if ((i + 1 == now_month && !is_click) || (is_click && sel_month == i + 1)) {
            div_month_row.querySelector('button').classList.add('active');
            // click
            attendMonthBtnClick(div_month_row.querySelector('button'));
        }
    }
}

// 출결현황 월 선택. > 월같 출결 불러오기.
function attendMonthBtnClick(vthis){
    // 나머지 비활성화
    const div_month_bundle = document.querySelector('.div_month_bundle');
    const div_month_rows = div_month_bundle.querySelectorAll('.div_month_row');
    div_month_rows.forEach((div_month_row) => {
        div_month_row.querySelector('button').classList.remove('active');
    });
    vthis.classList.add('active');
    // 상단 테이블 정보 불러오기.
    atendDetailSelect();
    // 하단 테이블 정보 불러오기.
    attendAbsentSelect();

}

// 상단 테이블 불러오기.
function atendDetailSelect(page_num){
    const student_seq = document.querySelector('[data-main-student-seq]').value;
    const year = document.querySelector('#my_study_span_year').getAttribute('data').substr(0,4);
    const month = document.querySelector('.div_month_row button.active').querySelector('.month_cnt').textContent;
    const sel_date = year + '-' + month;
    document.querySelector('#my_study_span_year').setAttribute('data', sel_date+'-1');

    const page = '/teacher/after/learning/management/detail/attend/select';
    const parameter = {
        student_seq: student_seq,
        sel_date: sel_date,
        page:(page_num||1)
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            // 초기화
            const bundle = document.querySelector('[data-bundle="attend_lists"]');
            const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

            // 페이징 선언.
            attendTablePaging(result.attend_lists, '1')
            const attend_lists = result.attend_lists.data;
            const lecture_status = result.lecture_status;
            document.querySelector('[data-attend-in-cnt]').textContent = result.attend_cnt||0;
            document.querySelector('[data-attend-total-cnt]').textContent = result.total_cnt||0;

            attend_lists.forEach(function(list){
                const row = row_copy.cloneNode(true);
                row.hidden = false;
                row.dataset.row = 'clone';
                row.querySelector('[data-ju]').textContent = attendGetWeekOfMonth(new Date(list.date));
                row.querySelector('[data-date]').textContent = list.date.substr(2, 8).replace(/-/g, '.');
                row.querySelector('[data-day]').textContent = list.day;
                row.querySelector('[data-lecture-status]').textContent = lecture_status[list.date]?.status == 'complete'?'목표 학습 완료':'미완료';

                // 결석
                if((list.id||'') == ''){
                    const reason = list.absent_reason||'';

                    // 결석사유 미등록
                    if(reason == ''){
                        row.querySelector('[data-content="1"]').textContent = `결석(사유미등록)`;
                        row.querySelector('[data-content="1"]').classList.add('text-danger');
                    }
                    // 결석사유 등록
                    else{
                        row.querySelector('[data-content="1"]').textContent = `결석(${reason})`;
                        row.querySelector('[data-content="1"]').classList.add('text-danger');
                        // 보강완료
                        if((list.start_time|'') == '' && (list.is_ref_complete||'') == 'Y'){
                            const ref_date = list.ref_date.substr(2, 8).replace(/-/gi, '.')||'';
                            row.querySelector('[data-content="2"]').parentElement.hidden = false;
                            row.querySelector('[data-content="2"]').textContent = `보강완료 ${ref_date}`;
                            row.querySelector('[data-content="2"]').classList.add('text-primary');
                        }
                        // 보강미완료
                        else{
                            // WARN: 일단 보강완료처리하는 것으로 진행.
                            // row.querySelector('[data-content="2"]') 안에 date input 넣기. 오늘 날짜로.
                            // row.querySelector('[data-content="2"]') 안에 보강완료 버튼 넣기.

                            const input = document.createElement('input');
                            input.classList.add('form-control', 'w-auto', 'd-inline-flex', 'border-none', 'px-0', 'text-sb-20px', 'scale-text-gray_05');
                            input.type = 'date';
                            input.value = list.ref_date||'';
                            input.setAttribute('data-ref-date', '');
                            const button = document.createElement('button');
                            button.textContent = '보강완료';
                            button.classList.add('btn', 'btn-outline-primary', 'rounded-4', 'ms-2');
                            button.onclick = function(){
                                attendChgRefComplete(this);
                            }
                            row.querySelector('[data-content="2"]').parentElement.hidden = false;
                            row.querySelector('[data-content="2"]').appendChild(input);
                            row.querySelector('[data-content="2"]').appendChild(button);
                        }
                    }

                }
                // 출석
                else{
                    row.querySelector('[data-content="1"]').textContent = `출석 ${list.start_time.substr(0, 5)}`;

                    if((list.end_time||'') != ''){
                        row.querySelector('[data-content="2"]').parentElement.hidden = false;
                        row.querySelector('[data-content="2"]').textContent = `하교 ${list.end_time.substr(0, 5)}`;
                    }
                }

                row.querySelector('[data-attend-seq]').value = list.id;
                row.querySelector('[data-absent-seq]').value = list.absent_seq;
                row.querySelector('[data-sel-date]').value = list.date;

                bundle.appendChild(row);
            });

        }else{}
    });
}



// 주간 출결 상세리스트 불러오기.
function attendTrClickDetail(){

}

function attendYearChange(type){

    let after_sum_num = 0;
    if (type == 'next') {
        after_sum_num = 1;
    } else if (type == 'prev') {
        after_sum_num = -1;
    }

    if (type == 'next' || type == 'prev') {
        const span_month = document.querySelector('#my_study_span_year');
        const sel_date = span_month.getAttribute('data');
        const date = new Date(sel_date);
        date.setYear(date.getFullYear() + after_sum_num);
        span_month.textContent = date.getFullYear() + '년';
        span_month.setAttribute('data', date.getFullYear() + '-' + (date.getMonth() + 1) + '-1');
        attendMakeMonthList(true);
    }
}
// 페이징  함수
function attendTablePaging(rData, target){
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

// :페이징 클릭시 펑션
function attendPageFunc(target, type){
        if(type == 'next'){
            const page_next = document.querySelector(`[data-page-next="${target}"]`);
            if(page_next.getAttribute("data-is-next") == '0') return;
            // data-page 의 마지막 page_num 의 innerText를 가져온다
            const last_page = document.querySelector(`[data-page="${target}"] .page_num:last-of-type`).innerText;
            const page = parseInt(last_page) + 1;
            if(target == "1")
                 atendDetailSelect(page);
        }
        else if(type == 'prev'){
            // [data-page-first]  next tag 의 innerText를 가져온다
            const page_first = document.querySelector(`[data-page-first="${target}"]`);
            const page = page_first.innerText;
            if(page == 1) return;
            const page_num = page*1 -1;
            if(target == "1")
                 atendDetailSelect(page);
        }
        else{
            if(target == "1")
                 atendDetailSelect(type);
        }
}

// 년월일을 넣으면 현재 몇월 몇주차라고 리턴하는 함수
function attendGetWeekOfMonth(date) {
    let week = 0;
    let month = date.getMonth();
    let year = date.getFullYear();
    let firstWeekday = new Date(year, month, 1).getDay();
    let lastDateOfMonth = new Date(year, month + 1, 0).getDate();
    let offsetDate = date.getDate() + firstWeekday - 1;
    week = Math.floor(offsetDate / 7) + 1;
    if (week == 0) {
        week = attendGetWeekOfMonth(new Date(year, month, 0));
    }
    return (month+1)+'월 '+week+'주차';
}

// 보강완료로 변경 하는 버튼 클릭.
function attendChgRefComplete(vthis){
    const tr = vthis.closest('tr');
    const studnet_seq = document.querySelector('[data-main-student-seq]').value;
    const absent_seq = tr.querySelector('[data-absent-seq]').value;
    const sel_date = tr.querySelector('[data-sel-date]').value;
    const ref_date = tr.querySelector('[data-ref-date]').value;

    const page = "/teacher/after/learning/management/class/student/reinforcement/end";
    const parameter = {
        student_seq: studnet_seq,
        absent_seq: absent_seq,
        sel_date: sel_date,
        ref_date: ref_date
    };

    const msg = `
    <div class="text-sb-24px">보강완료 처리 하시겠습니까?</div>
    `;
    sAlert('', msg, 3, function(){
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                // 상단 테이블 정보 불러오기.
                const page_num = document.querySelector('.page_num,active').innerText;
                atendDetailSelect(page_num);
                // 하단 테이블 정보 불러오기.
                attendAbsentSelect();
                toast('보강완료 처리되었습니다.');
            }else{}
        });
    });


}

// 결석의 보강리스트 불러오기.
function attendAbsentSelect(){
    const student_seq = document.querySelector('[data-main-student-seq]').value;
    const year = document.querySelector('#my_study_span_year').getAttribute('data').substr(0,4);
    const month = document.querySelector('.div_month_row button.active').querySelector('.month_cnt').textContent;
    const sel_date = year + '-' + month;
    document.querySelector('#my_study_span_year').setAttribute('data', sel_date+'-1');

    const page = '/teacher/after/learning/management/detail/attend/select';
    const parameter = {
        student_seq: student_seq,
        sel_date: sel_date,
        is_page:'N'
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            // 초기화
            const bundle = document.querySelector('[data-bundle="absent_lists"]');
            const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

            const absent_lists = result.absent_lists;
            absent_lists.forEach(function(list){
                if((list.start_time||'') != ''){
                    return;
                }
                const row = row_copy.cloneNode(true);
                row.hidden = false;
                row.dataset.row = 'clone';
                row.querySelector('[data-date]').textContent = list.date.substr(2, 8).replace(/-/g, '.');
                row.querySelector('[data-absent-reason]').textContent = list.absent_reason||'';

                if(list.is_ref_complete == 'Y'){
                    row.querySelector('[data-ref-date-td]').textContent = list.ref_date.substr(2, 8).replace(/-/g, '.')||'' + list.absent_steart_time.subster(0, 5)||'';
                    row.querySelector('[data-is-ref-complete]').textContent = '보강완료';
                    row.querySelector('[data-is-ref-complete]').classList.add('text-black');
                }else{
                    if((list.absent_seq||'') == ''){
                        row.querySelector('[data-absent-reason]').textContent = '보강미등록';
                        row.querySelector('[data-absent-reason]').classList.add('text-success');
                        row.querySelector('[data-ref-date-td]').textContent = '해당없음';
                        row.querySelector('[data-is-ref-complete]').textContent = '해당없음';
                        row.querySelector('[data-is-ref-complete]').classList.add('text-black');
                    }else{
                        // TODO: 해당없음은 선택을 하는건지 확인필요.
                        const input = document.createElement('input');
                        input.classList.add('form-control', 'w-auto', 'd-inline-flex', 'border-none', 'px-0', 'text-sb-20px', 'scale-text-gray_05');
                        input.type = 'date';
                        input.value = list.ref_date||'';
                        input.setAttribute('data-ref-date', '');
                        const button = document.createElement('button');
                        button.classList.add('btn', 'btn-outline-primary', 'rounded-4', 'ms-2');
                        button.textContent = '보강완료';
                        button.onclick = function(){
                            attendChgRefComplete(this);
                        }
                        row.querySelector('[data-ref-date-td]').appendChild(input);
                        row.querySelector('[data-is-ref-complete]').appendChild(button);
                    }
                }
                row.querySelector('[data-absent-seq]').value = list.absent_seq;
                row.querySelector('[data-sel-date]').value = list.date;
                bundle.appendChild(row);
            });
        }else{}
    });
}

</script>
