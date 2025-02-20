@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '오답노트')

@section('add_css_js')
    <link href="{{ asset('css/reset.css?5') }}" rel="stylesheet">
@endsection

{{-- 학생 컨텐츠 --}}
@section('layout_coutent')
<div class="col pe-3 ps-3 mb-3 pt-5 row position-relative">
        {{-- 상단 배너 : 오답노트 유무--}}
        <article id="wrong_note_article_banner"
        class="row justify-content-center pt-3 mb-5">
            <div class="bg-primary-bg rounded-3 d-flex justify-content-between " style="width: 750px;height: 98px;padding: 0px 30px">
                <div class="col-auto position-relative" style="width:122px">
                    <img class="position-absolute bottom-0" src="{{ asset('images/top_logo.png') }}" width="122">
                </div>
                <div class="d-flex align-items-center fw-bold">
                    <span>
                        <span class="cfs-5">오늘까지 완료해야할</span>
                        <span class="cfs-5" style="color:#f3b527">오답노트가 {{$complete_exams->whereIn('id', $wrong_sld_seqs)->count()}}</span>
                        <span class="cfs-5">개 있습니다🔥🔥</span>
                    </span>
                    </div>
                    <div class="col-auto d-flex align-items-center pe-4">
                        <button class="btn p-0" onclick="wrongNoteCloseBanner();">
                            <img src="{{ asset('images/black_x_icon.svg')}}" style="width:32px;">
                        </button>
                    </div>
            </div>
        </article>

        {{-- 오답노트 시작 --}}
        <article class="pt-5 mt-4">
            {{-- 상단 --}}
            <section>
                <div class="row">
                    <div class="col-auto">
                        <div class="h-center">
                            <img src="{{ asset('images/wrong_note_icon.svg')}}" width="52" class="m-2">
                            <span class="cfs-1 fw-semibold align-middle">오답노트</span>
                        </div>
                        <div class="pt-2">
                            <span class="cfs-3 fw-medium">오답노트에 관한 텍스트</span>
                        </div>
                    </div>
                    <div class="col text-end">
                        <div class="row mx-0 justify-content-end">
                            <button id="wrong_note_btn_tab_today"
                            class="btn btn-outline-primary-y rounded-pill cbtn-p-i fw-medium cfs-5 ctext-gc1 border-0 h-center gap-2 col-auto active"
                            onclick="wrongNoteTab('today');">
                                <img src="{{ asset('images/calendar_chk_icon.svg') }}" width="24">
                                <span class="align-middle">이번주까지</span>
                            </button>
                            <button id="wrong_note_btn_tab_week"
                            class="btn btn-outline-primary-y rounded-pill cbtn-p-i fw-medium cfs-5 ctext-gc1 h-center gap-2 col-auto border-0"
                            onclick="wrongNoteTab('week');wrongNoteSelect();">
                                <img src="{{ asset('images/calendar_chk_icon.svg') }}" width="24">
                                <span class="align-middle">저번주까지</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <div class="py-5"></div>
            <div class="pt-4"></div>

            <main class="row">
                <aside id="wrong_note_side_month" class="col-3 is-week" hidden>
                    <div class="shadow-sm-2 p-4">
                        <div class="row align-items-center justify-content-between mb-2">
                            <button class="btn col-auto" onclick="wrongNoteMonthChange('prev')">
                                <img src="{{ asset('images/calendar_arrow_left.svg') }}" width="32">
                            </button>
                            <span id="wrong_note_span_month"
                            class="align-middle col text-center cfs-5 fw-semibold" data="{{ date('Y-m-d', strtotime(date('Y-m-d').'-7 days')) }}">
                                {{ date('Y년 n월', strtotime(date('Y-m-d').'-7 days')) }}
                            </span>
                            <button class="btn col-auto" onclick="wrongNoteMonthChange('next')">
                                <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="32">
                            </button>
                        </div>
                        <div class="row row-cols-3 div_week_bundle mt-4">
                            <div class="col div_week_row p-0 pe-1" hidden>
                                <button class="btn btn-outline-primary-y ctext-gc1 border-0 rounded-pill cfs-5 p-2 px-2"
                                onclick="wrongNoteWeekBtnClick(this)">
                                    <div class="p-1">
                                        <span class="week_cnt">n</span>
                                        <span>주차</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </aside>
            <!-- TEST:   -->
                <article class="col">
                    {{-- 오늘까지 section --}}
                    <section class="is-today" >
                        <div class="row gap-2">
                            @if(!empty($subject_codes))
                                @foreach($subject_codes as $index => $subject_code)
                                    <div class="{{ $index != 0 ? 'col':'col-lg-4 active' }} shadow-sm-2 p-4 cursor-pointer rounded-3" data-top-subject-tab onclick="wrongClickSubjectTab(this);">
                                        <input type="hidden" data-top-subject-seq value="{{ $subject_code->id }}">
                                        <div class="col">
                                            <div>
                                                <span class="ctext-bc0 cfs-3 fw-medium">{{ $subject_code->code_name }}</span>
                                            </div>
                                            <div class="row">
                                                <div data-top-subject-tab-middle class="col-auto cfs-6 py-2" {{ $index != 0 ? 'hidden':''}}>
                                                    <span class="ctext-gc1">완료해야할
                                                    <span class="text-danger"> 오답노트가 <span> {{$complete_exams->whereIn('id', $wrong_sld_seqs)->where('subject_seq', $subject_code->id)->count()}} </span> 개 </span>
                                                    있습니다.
                                                    </span>
                                                </div>
                                                <div class="col text-end">
                                                    <img src="{{ asset('images/'.$subject_code->function_code.'.svg') }}" alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </section>
                    {{-- 이번주까지 section --}}
                    <section id="wrong_note_section_week" class="col-12 is-week" hidden>
                        <div class="shadow-sm-2 rounded-bottom-4">
                            {{-- 요일 넣기 --}}
                            <div class="row fw-medium text-center cfs-6 py-2 mx-0">
                                <div class="col text-danger py-1">일요일</div>
                                <div class="col ctext-gc1 py-1">월요일</div>
                                <div class="col ctext-gc1 py-1">화요일</div>
                                <div class="col ctext-gc1 py-1">수요일</div>
                                <div class="col ctext-gc1 py-1">목요일</div>
                                <div class="col ctext-gc1 py-1">금요일</div>
                                <div class="col ctext-gc1 py-1">토요일</div>
                            </div>
                            {{-- 날자 및 완료, 미완, 학습유무 --}}
                            <div class="div_week_complete_bundle row row-cols-7 mx-0">
                                {{-- 미완료 : bg-gc5, 완료 : bg-primary-bg, 노학습:없음 --}}
                                <div class="div_week_complete_row col" hidden>
                                    <div class="text-center py-1">
                                        <span class="sp_date_str ctext-gc1 cfs-6" date="">12</span>
                                    </div>
                                    <div class="py-4 text-center">
                                        <img class="img_complete" src="{{ asset('images/wrong_note_complete_icon.svg') }}" hidden>
                                        <img class="img_incomplete" src="{{ asset('images/wrong_note_incomplete_icon.svg') }}" hidden>
                                        <div class="div_none_complete text-center py-2 my-1" hidden>
                                            <div class="p-1">
                                                <img src="{{ asset('images/exclamation_icon.svg') }}" alt="">
                                            </div>
                                            <span class="ctext-gc1 cfs-7 pt-2">학습이 없어요</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="py-1"></div>
                </article>
            {{-- 오답 노트 목록 --}}
            <section id="wrong_note_section_list" class="mt-5 pt-4">
                <div class="row div_wrong_note_head shadow-sm-2 text-center ctext-gc1 cfs-6 py-4">
                    <div class="col">과목</div>
                    <div class="col-lg-4">평가</div>
                    <!--TODO: 틀린문제? 남은문제? 뭐가맞는지? 확인필요 -->
                    <div class="col">남은 문제</div>
                    <div class="col">완료 기간</div>
                    <div class="col">-</div>
                </div>
                <div class="div_wrong_note_bundle cfs-6" data-bundle="tdoay_worng">
                    <div class="div_wrong_note_row row ctext-gc1 text-center" data-row="copy" hidden>
                        <input type="hidden" data-student-exam-seq="">
                        <input type="hidden" data-lecture-detail-type="">

                        <div class="col py-4 my-2">
                            <span data-subject-name>국어</span>
                            <span data-unit-name>1단원</span>
                        </div>
                        <div class="col-lg-4 py-4 my-2">
                            <span class="ctext-bc0 align-middle"><span data-lecture-name></span></span>
                            <span class="align-middle" data-lecture-detail-name>1장. '억과 조를 알아볼까요'</span>
                        </div>
                        <div class="col py-4 my-2">
                            <span data-wrong-cnt>3</span> 문제
                        </div>
                        <div class="col py-4 my-2 text-start">
                            <div class="completion_date text-danger" data-datetime-3></div>
                            <div class="completion_date text-danger" data-datetime-5></div>
                        </div>
                        <div class="col d-flex align-items-center justify-content-center" >
                            <button data-btn-again-exam class="btn btn-light bg-study-2 text-white rounded-pill cbtn-p h-center gap-2 ps-3" onclick="wrongAgainExam(this);">
                                <img src="{{ asset('images/pencil_icon.svg') }}" width="24">
                                <span class="align-middle cfs-6">다시풀기</span>
                            </button>
                        </div>
                    </div>
                </div>

            </section>
            </main>
            <div class="py-5"></div>
            <div class="py-4"></div>
            <div class="py-2"></div>
            <div class="pt-1"></div>
        </article>

</div>
<form action="/student/wrong/note/again/exam" method="post" target="_self" data-form-again-exam hidden>
    @csrf
    <input name="student_exam_seq">
    <input name="lecture_detail_type">
</form>
<script>
    const complete_exams = @json($complete_exams);
    const subject_codes = @json($subject_codes->pluck('code_name', 'id'));
    wrongNoteSelect(true);
    wrongNoteMakeWeekList();

    document.addEventListener('visibilitychange', function(event) {
        if (sessionStorage.getItem('isBackNavigation') === 'true') {
            console.log('뒤로 가기 버튼을 클릭한 후 페이지가 로드되었습니다.');
            // 여기에 뒤로 가기 버튼을 클릭한 후 페이지가 로드되었을 때 실행할 코드를 작성합니다.
            sessionStorage.removeItem('isBackNavigation'); // 상태를 초기화합니다.

            if(document.querySelector('#wrong_note_btn_tab_week').classList.contains('active')){
                wrongNoteSelect();
            }else{
                wrongNoteSelect(true);
            }
        }
    });
    // 오답노트 탭
    function wrongNoteTab(tab){
        const btn_today = document.querySelector('#wrong_note_btn_tab_today');
        const btn_week = document.querySelector('#wrong_note_btn_tab_week');
        if(tab == 'today'){
            btn_today.classList.add('active');
            btn_week.classList.remove('active');
            document.querySelectorAll('.is-today').forEach(function(item){
                item.hidden = false;
            });
            document.querySelectorAll('.is-week').forEach(function(item){
                item.hidden = true;
            });
            wrongNoteSelect(true);
        }else{
            btn_today.classList.remove('active');
            btn_week.classList.add('active');
            document.querySelectorAll('.is-today').forEach(function(item){
                item.hidden = true;
            });
            document.querySelectorAll('.is-week').forEach(function(item){
                item.hidden = false;
            });
        }
    }


    // 이번주까지 > 선택달 > 다음달, 이전달 버튼
    function wrongNoteMonthChange(type){
        let after_sum_num = 0;
        if (type == 'next') {
            after_sum_num = 1;
        } else if (type == 'prev') {
            after_sum_num = -1;
        }

        if (type == 'next' || type == 'prev') {
            const span_month = document.querySelector('#wrong_note_span_month');
            const sel_date = span_month.getAttribute('data');
            const date = new Date(sel_date);
            date.setMonth(date.getMonth() + after_sum_num);
            span_month.textContent = date.getFullYear() + '년 ' + (date.getMonth() + 1) + '월';
            span_month.setAttribute('data', date.getFullYear() + '-' + (date.getMonth() + 1) + '-1');
            wrongNoteMakeWeekList();
        }
    }
    // 저번주까지 > 선택 달 > 주차 버튼 생성
    function wrongNoteMakeWeekList(){
        const aside = document.querySelector('#wrong_note_side_month');
        const div_week_bundle = aside.querySelector('.div_week_bundle');
        const copy_div_week_row = div_week_bundle.querySelector('.div_week_row').cloneNode(true);
        const sel_date = aside.querySelector('#wrong_note_span_month').getAttribute('data');
        // 초기화
        div_week_bundle.innerHTML = '';
        div_week_bundle.appendChild(copy_div_week_row);

        // sel_date의 달이 몇번째 주까지 있는지 계산
        // 단 단순히 7로 나누는게 아니라 1일이 무슨 요일인지 계산해서 그에 맞게 계산해야함.
        const date = new Date(sel_date);
        const first_day = new Date(date.getFullYear(), date.getMonth(), 1);
        const last_day = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        const first_week = first_day.getDay();
        const last_week = last_day.getDate();
        const week_cnt = Math.ceil((last_week + first_week) / 7);

        // 만약 현재달과 선택 달이 같으면
        // 현재가 몇주차인지도 계산해야함.
        const now_date = new Date();
        // 저번주로 변경
        const before_date = new Date(now_date.getFullYear(), now_date.getMonth(), now_date.getDate() - 7);
        let now_week_cnt = -1;
        if(before_date.getFullYear() == date.getFullYear() && before_date.getMonth() == date.getMonth()){
            const now_week = before_date.getDate();
            now_week_cnt = Math.ceil((now_week + first_week) / 7);
        }

        for(let i = 0; i < week_cnt; i++){
            const copy_div_week_row = div_week_bundle.querySelector('.div_week_row').cloneNode(true);
            const div_week_row = copy_div_week_row.cloneNode(true);
            div_week_row.hidden = false;
            if(i > 2){
                // 3번째부터 pt-1 add
                div_week_row.classList.add('pt-1');
            }
            div_week_row.querySelector('.week_cnt').textContent = i + 1;
            div_week_bundle.appendChild(div_week_row);
            if((i + 1) == now_week_cnt){
                div_week_row.querySelector('button').classList.add('active');
                wrongNoteSelDayWeekSetting();
            }
        }
    }

    // 이번주까지 > 주차 선택 > 요일
    function wrongNoteSelDayWeekSetting(){
        const div_week = document.querySelector('#wrong_note_section_week');
        const span_month = document.querySelector('#wrong_note_span_month');
        const sel_month = span_month.getAttribute('data').substr(0, 7)
        const btn_week_row = document.querySelector('.div_week_row .active');
        const now_week_cnt = btn_week_row.querySelector('.week_cnt').textContent;
        // const now_week_cnt = 4; //test

        // sel_month 의 달의 now_week_cnt 주차의 일요일부터 토요일까지 날짜를 가져와서 배열에 넣기.
        const date = new Date(sel_month);
        const first_day = new Date(date.getFullYear(), date.getMonth(), 1);
        const first_week = first_day.getDay();
        const last_day = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        const last_week = last_day.getDate();
        const start_date = 1 - first_week + (now_week_cnt - 1) * 7;
        const end_date = start_date + 6;
        const week_date = [];

        const prev_month = new Date(date.getFullYear(), date.getMonth() - 1, 1).format('yyyy-MM');
        const prev_month_last_day = new Date(date.getFullYear(), date.getMonth(), 0).getDate();
        const next_month = new Date(date.getFullYear(), date.getMonth() + 1, 1).format('yyyy-MM');
        const next_month_first_day = new Date(date.getFullYear(), date.getMonth() + 1, 1).getDay();

        //
        for(let i = start_date; i <= end_date; i++){
            let put_date = '';
            if(i < 1){
                put_date = prev_month_last_day + i;
                put_date = prev_month + '-' + put_date;
                week_date.push(put_date);
            }else if(i > last_week) {
                put_date = i - last_week;
                put_date = next_month + '-' + put_date;
                week_date.push(put_date);
            }
            else{
                // yyyy-mm-dd 형태로 저장
                put_date = date.format('yyyy-MM-')+i;
                week_date.push(put_date);
            }
        }

        //초기화
        const div_week_complete_bundle = div_week.querySelector('.div_week_complete_bundle');
        const copy_div_week_complete_row = div_week.querySelector('.div_week_complete_row').cloneNode(true);
        div_week_complete_bundle.innerHTML = '';
        div_week_complete_bundle.appendChild(copy_div_week_complete_row);

        // {{-- 미완료 : bg-gc5, 완료 : bg-primary-bg, 노학습:없음 --}}
        // img_complete
        // img_incomplete
        // div_none_complete
        week_date.forEach(function(date, idx){
            const div_week_row = copy_div_week_complete_row.cloneNode(true);
            div_week_row.hidden = false;
            //처음과 끝에 라운드
            if(idx == 0 || idx == 6){
                div_week_row.classList.add('rounded-bottom-3');
            }
            // test
            if(idx == 0 || idx == 1 || idx == 2){
                // div_week_row.classList.add('bg-gc5');
                // div_week_row.querySelector('.img_incomplete').hidden = false;
            }
            if(idx == 3 || idx == 4 ){
                // div_week_row.classList.add('bg-primary-bg');
                // div_week_row.querySelector('.img_complete').hidden = false;
            }
            if(idx == 5 || idx == 6){
                // div_week_row.querySelector('.div_none_complete').hidden = false;
            }
            div_week_row.querySelector('.sp_date_str').textContent = date.substr(8);
            div_week_row.querySelector('.sp_date_str').dataset.date = date;
            div_week_complete_bundle.appendChild(div_week_row);
        });

        // 상단 요일에, 미완료, 완료, 학습이 없어요 넣어주기.
        wrongNoteSelDayWeekSelect();


    }

    // 이번주까지 > 주차 선택
    function wrongNoteWeekBtnClick(btn){
        const aside = document.querySelector('#wrong_note_side_month');
        const btn_week_row = aside.querySelectorAll('.div_week_row button');
        btn_week_row.forEach(function(item){
            item.classList.remove('active');
        });
        btn.classList.add('active');

        // 선택한 주차의 날짜를 가져와서 완료, 미완료, 학습유무를 표시
        wrongNoteSelDayWeekSetting();

        // 주단위 오답노트 가져오기.
        wrongNoteSelect();
    }

    // 배너 닫기
    function wrongNoteCloseBanner(){
        const article_banner = document.querySelector('#wrong_note_article_banner');
        article_banner.hidden = true;
    }

    // 상단 과목 tab 클릭
    function wrongClickSubjectTab(vthis){
        // 활성화시 col-lg-4 active 비활성화시에는 col
        // data-top-subject-tab 모두 비활성화
        document.querySelectorAll('[data-top-subject-tab].active').forEach(function(el){
            el.classList.remove('active');
            el.classList.remove('col-lg-4');
            el.classList.add('col');
            el.querySelector('[data-top-subject-tab-middle]').hidden = true;
        });
        vthis.classList.add('active');
        vthis.classList.add('col-lg-4');
        vthis.querySelector('[data-top-subject-tab-middle]').hidden = false;

        // 오늘 /과목 관련 오답노트 리스트 불러오기.
        wrongNoteSelect(true);
    }

    // 선택 과목별 / 오늘 오답노트 리스트 불러오기.
    function wrongNoteSelect(is_subject = false){
        const subject_active = document.querySelector('[data-top-subject-tab].active');
        const subject_seq = subject_active.querySelector('[data-top-subject-seq]').value;
        let start_date = '';
        let end_date = '';
        if(!is_subject){
            start_date = document.querySelectorAll('.sp_date_str')[1].dataset.date;
            end_date = document.querySelectorAll('.sp_date_str')[7].dataset.date;
        }

        const page = "/student/wrong/note/select";
        const parameter = {};
        if(is_subject){
            parameter.subject_seq = subject_seq;
            const dates = wronggetsumstartdate();
            parameter.start_date = dates[0];
            parameter.end_date = dates[1];
        }
        if(!is_subject){
            parameter.start_date = start_date;
            parameter.end_date = end_date;
        }

        const section_list = document.querySelector('#wrong_note_section_list');
        const div_bundle = section_list.querySelector('.div_wrong_note_bundle');
        const copy_div_row = div_bundle.querySelector('.div_wrong_note_row').cloneNode(true);
        div_bundle.innerHTML = '';
        div_bundle.appendChild(copy_div_row);
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                // 초기화.
                div_bundle.innerHTML = '';
                div_bundle.appendChild(copy_div_row);

                const wrongs = result.complete_exams;
                const wrong_cnts = result.wrong_cnts;
                wrongs.forEach(function(wrong, i){
                    // 더이상 풀 문제가 없을때,
                    // 요청으로 완료되도 목록에 나오도록 요청.
                    let is_completed = false;
                    if((wrong_cnts[wrong.id+'']||'0') == '0'){
                        // return;
                        is_completed = true;
                    }
                    const copy_div_row = div_bundle.querySelector('.div_wrong_note_row').cloneNode(true);
                    const row = copy_div_row.cloneNode(true);
                    if(i != 0){
                        row.classList.add('border-top');
                    }
                    row.hidden = false;

                    row.querySelector('[data-subject-name]').textContent = subject_codes[wrong.subject_seq]||'';
                    row.querySelector('[data-unit-name]').textContent = (wrong.lecture_name||'').replace((subject_codes[wrong.subject_seq]||''), '');
                    row.querySelector('[data-lecture-name]').textContent = wrong.exam_title||'';
                    row.querySelector('[data-lecture-detail-name]').textContent = wrong.lecture_detail_name||'';
                    row.querySelector('[data-wrong-cnt]').textContent = (wrong_cnts[wrong.id+'']||'0') ||'0';
                    let datetime = '';
                    let is_again_del = false;
                    if(is_completed){
                        datetime = '완료';
                        row.querySelector('[data-datetime-3]').classList.add('text-center');
                        row.querySelector('[data-btn-again-exam]').remove();
                    }else{

                        if(wrong.lecture_detail_type == 'exam_solving') datetime = `문제풀이:${(wrong.datetime||'').substr(0, 10) == wrong.today ? '오늘까지': wrong.datetime.substr(0,10).replace(/-/gi, '.')+' 까지'}`;
                        else if(wrong.lecture_detail_type == 'unit_test') datetime = `단원평가:${(wrong.datetime||'').substr(0, 10) == wrong.today ? '오늘까지': wrong.datetime.substr(0,10).replace(/-/gi, '.')+' 까지'}`;
                        else{
                            datetime = `학습:${(wrong.datetime||'').substr(0, 10) == wrong.today ? '오늘까지': wrong.datetime.substr(0,10).replace(/-/gi, '.')+' 까지'}`;
                        }
                        if(wrong.datetime && wrong.datetime <= wrong.today){
                            is_again_del = true;
                            row.querySelector('[data-datetime-3]').classList.remove('text-danger');
                        }
                        if(is_again_del){
                            row.querySelector('[data-btn-again-exam]').remove();
                        }
                    }
                    row.querySelector('[data-datetime-3]').textContent = datetime;


                    // row.querySelector('[data-exam-seq]').value = wrong.exam_seq||'';
                    // row.querySelector('[data-lecture-detail-seq]').value = wrong.lecture_detail_seq||'';
                    // row.querySelector('[data-exam-lecture-detail-seq]').value = wrong.exam_lecture_detail_seq||'';
                    row.querySelector('[data-student-exam-seq]').value = wrong.id;
                    row.querySelector('[data-lecture-detail-type]').value = wrong.lecture_detail_type||'';

                    // row.querySelector('[data-student-lecture-detail-seq]').value = wrong.id||'';

                    div_bundle.appendChild(row);
                });
            }
        });
    }


    // 상단 요일에, 미완료, 완료, 학습이 없어요 넣어주기.
    function wrongNoteSelDayWeekSelect(){
        // TODO: 우선은 오답 완료, 미완료가 아니라, 그날의 학습이 완료, 미완료 인지 추후 오답노트이면 수정 바람.
        const start_daet = document.querySelectorAll('.sp_date_str')[1].dataset.date;
        const end_date = document.querySelectorAll('.sp_date_str')[7].dataset.date;

        const page = "/student/wrong/complete/lectures/select";
        const parameter = {
            start_date: start_daet,
            end_date: end_date
        };
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                const details = result.student_lecture_details;
                details.forEach(function(detail){
                    const sel_date = (detail.sel_date||'').substr(0, 10);
                    const row = document.querySelector(`[data-date="${sel_date}"]`)?.closest('.div_week_complete_row');
                    // {{-- 미완료 : bg-gc5, 완료 : bg-primary-bg, 노학습:없음 --}}
                    if(detail.status == 'complete'){
                        if(row)row.dataset.isComplete = 'Y'
                    }else{
                        if(row)row.dataset.isIncomplete = 'Y'
                    }
                });
                document.querySelectorAll('.div_week_complete_row').forEach(function(row){
                    row.querySelector('.img_incomplete').hidden = true;
                    row.querySelector('.img_complete').hidden = true;
                    row.querySelector('.div_none_complete').hidden = true;

                    row.classList.remove('bg-gc5');
                    row.classList.remove('bg-primary-bg');

                    if(row.dataset.isIncomplete == 'Y'){
                        row.classList.add('bg-gc5');
                        row.querySelector('.img_incomplete').hidden = false;
                    }
                    else if(row.dataset.isComplete == 'Y'){
                        row.classList.add('bg-primary-bg');
                        row.querySelector('.img_complete').hidden = false;
                    }else{
                        row.querySelector('.div_none_complete').hidden = false;
                    }
                });

            }else{}
        });
    }
    // 오답 문제 다실풀기.
    function wrongAgainExam(vthis){
        const row = vthis.closest('[data-row]');
        const student_exam_seq = row.querySelector('[data-student-exam-seq]').value;
        const lecture_detail_type = row.querySelector('[data-lecture-detail-type]').value;

        const msg = "<div class='text-sb-24px'>오답문제를 다시 푸시겠습니까?</div>";
        sAlert('', msg, 3, function(){
            const form = document.querySelector('[data-form-again-exam]');
            form.querySelector('[name="student_exam_seq"]').value = student_exam_seq;
            form.querySelector('[name="lecture_detail_type"]').value = lecture_detail_type;
            form.submit();
        });
    }

    // 오늘 기준으로 요일을 구하고, 요일에 따라서 - 를 해서 일요일의 날짜와, + 해서 토요일의 날짜를 구한다.
    function wronggetsumstartdate(){
        const today = new Date();
        const day = today.getDay();
        const sun_date = new Date(today.getFullYear(), today.getMonth(), today.getDate() - day);
        const sat_date = new Date(today.getFullYear(), today.getMonth(), today.getDate() + (6 - day));
        return [sun_date.format('yyyy-MM-dd'), sat_date.format('yyyy-MM-dd')];
    }
</script>
@endsection
