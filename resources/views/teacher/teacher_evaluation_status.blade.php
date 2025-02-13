<!-- TODO: 성적표 바로가기 클릭시 어디로 이동하는지? -->

{{-- 성적 상세 --}}
<section class="col">
    <div class="d-flex justify-content-end">
        <div class="d-inline-block select-wrap select-icon" style="min-width:100px">
            {{-- : select 드롭다운--}}
            <select data-select-evaluation-seq onchange="evalSubjectSelect()"
                class="rounded-pill border-gray lg-select text-sb-24px ps-4"
                style="min-width:100px;padding-top:18px;padding-bottom:18px;" onchage="">
                <option value="">평가분류</option>
                @if(!empty($evaluation_codes))
                @foreach($evaluation_codes as $evaluation_code)
                    <option value="{{ $evaluation_code->id }}">{{ $evaluation_code->code_name }}</option>
                @endforeach
                @endif
            </select>
        </div>
    </div>
    {{-- 성적 상세 --}}
    <div class=" mt-4">
        <div class=" mt-1 row row-cols-3" data-bundle="bottom_subjects">
            <div class="col" data-row="copy" hidden onclick="evalSubjectsClick(this);">
                <input type="hidden" data-subject-seq="">
                <div class="modal-shadow-style p-4 rounded-3" data-border>
                    <h4 class="text-r-24px scale-text-black row mx-0">
                        <div class="col">
                            <span data-subject-name>#과목</span>
                        </div>
                        <div class="col-auto">
                            <span data-exam-rate>00</span>점
                        </div>
                    </h4>
                    <div class="row pt-4 pb-2">
                        <div class="col">
                            <span class="text-sb-20px scale-text-gray_05">지난달보다</span>
                        </div>
                        <div class="col-auto">
                            <div class="h-center">
                                <span class="test-b-20px text-danger">
                                    <span data-prev-exam-comparison>00</span>점
                                </span>
                                <img data-up-img="prev" src="{{ asset('images/red_arrow_up_icon.svg') }}" alt="" hidden>
                                <img data-down-img="prev" src="{{ asset('images/blue_arrow_down_icon.svg') }}" alt="" hidden>
                            </div>
                        </div>
                    </div>
                    <div class="row pt-1">
                        <div class="col">
                            <span class="text-sb-20px scale-text-gray_05">또래들보다</span>
                        </div>
                        <div class="col-auto">
                            <div class="h-center">
                                <span class="test-b-20px text-danger">
                                    <span data-my-age-exam-comparison>00</span>점
                                </span>
                                <img data-up-img="myage" src="{{ asset('images/red_arrow_up_icon.svg') }}" alt="" hidden>
                                <img data-down-img="myage" src="{{ asset('images/blue_arrow_down_icon.svg') }}" alt="" hidden>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="col mt-4">
    {{-- 성적 그래프 --}}
    <div class="modal-shadow-style p-5">
        {{-- padding 130px --}}
        <div style="height: 82px;"></div>

        <div class="m-1 row" style="height:250px;">
            <div class="col-auto m-0 d-flex flex-column position-relative">
                <div class="col mb-2 position-absolute d-flex flex-column" style="bottom:4px;height:250px;">
                    <div class="col" style="">100</div>
                    <div class="col" style="">80</div>
                    <div class="col" style="">60</div>
                    <div class="col" style="">40</div>
                    <div class="col" style="">20</div>
                </div>
                <div class="position-absolute " style="bottom:-10px">0</div>
            </div>
            <div class="col position-relative">
                <div class=" d-flex flex-column ms-4 h-100">
                    <div class="col" style="border-bottom:1px solid #E5E5E5;border-top:1px solid #E5E5E5"></div>
                    <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                    <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                    <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                    <div class="col" style="border-bottom:1px solid #E5E5E5"></div>
                </div>
                <div data-bundle="graph" class="row mx-0 position-absolute top-0 bottom-0 start-0 end-0 ms-4 px-5">

                    {{--   --}}
                    <div class="col row gap-2 align-items-end justify-content-center position-relative" data-row="copy" hidden>
                        <div data-div-graph-top class="position-absolute text-center px-0" style="top: -73px" hidden>
                            <span class="text-white text-b-20px rounded-3" style="background: #FFC747;padding:12px 20px;">
                                <span data-prev-month-up-rate> - </span>점 상승
                            </span>
                            <div class="position-relative">
                                <img src="{{ asset('images/yellow_arrow_down_icon.svg') }}" width="18" class="position-absolute" style="left: 43%;bottom:-18px">
                            </div>

                        </div>
                        <div data-age-month class="col-auto rounded-top-3 scale-bg-gray_02 px-3" style="height:0%"> </div>
                        <div data-my-month class="col-auto rounded-top-3 px-3 ms-1" style="height:0%;background:#FFC747"> </div>
                        <div class="position-absolute text-center px-0" style="bottom:-62px;">
                            <button onclick="scoreClickGraphOne(this)" data-btn-juhcha-name class="btn btn-outline-primary-y border-0 rounded-pill text-sb-20px scale-text-gray_05 scale-text-white-hover " style="padding:4px 16px">

                            </button>
                        </div>
                    </div>
                    {{--   --}}

                </div>
            </div>
        </div>

        {{-- padding 125px --}}
        <div style="height: 125px;"></div>

        {{-- 지난달, 현재 --}}
        <div class="gap-4 all-center">
            <div class="col-auto all-center">
                <span class="rounded-pill pt-3 ps-3" style="border:4px solid #f1f1f1;"></span>
                <span class="text-sb-20px scale-text-gray_05 ms-2">지난달</span>
            </div>
            <div class="col-auto all-center">
                <span class="rounded-pill pt-3 ps-3" style="border:4px solid #FFC747;"></span>
                <span class="text-sb-20px scale-text-gray_05 ms-2">현재</span>
            </div>
        </div>
    </div>

    {{-- 하단 / 평가 종류  --}}
    <div class="row row-cols-3 mt-4">
        <table class="w-100 table-style table-h-82">
            <thead class="modal-shadow-style rounded">
                <tr class="text-sb-20px ">
                    <th>평가종류</th>
                    <th>평가(단원)</th>
                    <th>점수</th>
                    <th>성적표 바로가기</th>
                </tr>
            </thead>
            <tbody data-bundle="exam_evaluation_list">
                <tr class="text-m-20px" data-row="copy" hidden>
                    <td data-evaluation-name=""></td>
                    <td data-exam-title>
                    </td>
                    <td class="text-black">
                        <span data-rate> </span>점
                    </td>
                    <td >

                        <button type="button" onclick="evalMoveCheckTranscript(this);"
                            class="btn-line-xss-secondary text-sb-20px border rounded scale-bg-white scale-text-gray_05 px-3 col-auto">성적표 확인하기</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<!-- TODO: 평가등록 기능. -->
<script>

evalSubjectSelect();

// 과목별 성적 그래프
function evalSubjectSelect(){
    // TODO: 뭔가 단원이 있는데 어디서 설정하는지 알수없음.
    const unit = '';
    const evaluation_seq = document.querySelector('[data-select-evaluation-seq]').value;
    // 현재년월
    const year = new Date().format('yyyy');
    const month = new Date().format('MM');
    const month_date = year + '-' + month;
    const student_seq = document.querySelector('[data-main-student-seq]').value;

    const page = "/student/my/score/subject/select";
    const parameter = {
        'unit': unit,
        'evaluation_seq':evaluation_seq,
        'month':month_date,
        'student_seq':student_seq
    };
    queryFetch(page, parameter,function(result){
        if((result.resultCode||'') == 'success'){
            // 하단 과목 리스트
            evalSubjectList(result.exam_results);

            // 그래프 초기화
            const bundle = document.querySelector('[data-bundle="graph"]');
            const row_copy = bundle.querySelector('[data-row]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

            // 하단 테이블 초기화
        }else{}
    });
}

// 과목별 하단 과목 점수
function evalSubjectList(exam_results){
    // 초기화
    const bundle = document.querySelector('[data-bundle="bottom_subjects"]');
    const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
    bundle.innerHTML = '';
    bundle.appendChild(row_copy);

    let idx = 0;
    exam_results.forEach(function(exam_result){
        const row = row_copy.cloneNode(true);
        const this_month = exam_result.correct_count / exam_result.total_count * 100;
        const prev_month = exam_result.prev_correct_count / exam_result.prev_total_count * 100;
        const my_age = exam_result.my_age_correct_count / exam_result.my_age_total_count * 100;
        const up_scroe = getNaNZero(this_month) - getNaNZero(prev_month) ;
        const my_age_up_scroe = getNaNZero(this_month) - getNaNZero(my_age) ;
        // const my_age_up_scroe = -10;

        row.hidden = false;
        row.dataset.row = "clone";
        row.querySelector('[data-subject-name]').textContent = exam_result.subject_name;
        row.querySelector('[data-exam-rate]').textContent = isNaN(this_month) ? ' - ' : this_month.toFixed(0);
        row.querySelector('[data-prev-exam-comparison]').textContent = isNaN(up_scroe) ? ' - ' : up_scroe.toFixed(0);
        row.querySelector('[data-my-age-exam-comparison]').textContent = isNaN(my_age_up_scroe) ? ' - ' : my_age_up_scroe.toFixed(0);
        row.querySelector('[data-subject-seq]').value = exam_result.subject_seq;

        if(idx > 2){
            row.classList.add('mt-4');
        }
        // 지난달보다
        if(getNaNZero(up_scroe) > 0){
            row.querySelector('[data-up-img="prev"]').hidden = false;
            row.querySelector('[data-down-img="prev"]').hidden = true;
        }else if(getNaNZero(up_scroe) < 0){
            row.querySelector('[data-up-img="prev"]').hidden = true;
            row.querySelector('[data-down-img="prev"]').hidden = false;
            row.querySelector('[data-prev-exam-comparison]').parentElement.classList.remove('text-danger');
            row.querySelector('[data-prev-exam-comparison]').parentElement.classList.add('secondary-text-mian');
        }

        // 또래들보다
        if(getNaNZero(my_age_up_scroe) > 0){
            row.querySelector('[data-up-img="myage"]').hidden = false;
            row.querySelector('[data-down-img="myage"]').hidden = true;
        }else if(getNaNZero(my_age_up_scroe) < 0){
            row.querySelector('[data-up-img="myage"]').hidden = true;
            row.querySelector('[data-down-img="myage"]').hidden = false;
            row.querySelector('[data-my-age-exam-comparison]').parentElement.classList.remove('text-danger');
            row.querySelector('[data-my-age-exam-comparison]').parentElement.classList.add('secondary-text-mian');
        }

        bundle.appendChild(row);
        idx++;
    });
}

// 그래프 정보 가져오기
function evalSubjectJuchaSelect(){
    const student_seq = document.querySelector('[data-main-student-seq]').value;
    const evaluation_seq = document.querySelector('[data-select-evaluation-seq]').value;
    const month = new Date().format('yyyy-MM');
    const subject_seq = document.querySelector('[data-bundle="bottom_subjects"] [data-row="clone"].active').querySelector('[data-subject-seq]').value;

    const page = "/student/my/score/subject/jucha/select";
    const parameter = {
        'student_seq':student_seq,
        'evaluation_seq':evaluation_seq,
        'month':month,
        'subject_seq':subject_seq
    };

    const jucha_head = new Date().format('MM')*1;
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            // 그래프 초기화
            const bundle = document.querySelector('[data-bundle="graph"]');
            const row_copy = bundle.querySelector('[data-row]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

            const exam_results = result.exam_results;
            const age_exam_results = result.age_exam_results;
            const keys = Object.keys(exam_results);
            let idx = 0;
            keys.forEach(function(idx){
                const row = row_copy.cloneNode(true);
                const exam_result = exam_results[idx][0];
                const age_exam_result = age_exam_results[idx][0];
                row.hidden = false;
                row.dataset.row = "clone";

                const my_rate = getNaNZero(exam_result?.correct_count) / getNaNZero(exam_result?.total_count) * 100;
                const age_rate = getNaNZero(age_exam_result?.correct_count)  / getNaNZero(age_exam_result?.total_count) * 100;

                row.querySelector('[data-age-month]').style.height = age_rate + '%';
                row.querySelector('[data-my-month]').style.height = my_rate + '%';
                row.querySelector('[data-btn-juhcha-name]').textContent = jucha_head+'월 '+(idx)+'주차';
                bundle.appendChild(row);
            });



        }else{}
    });

}

function getNaNZero(value) {
    return isNaN(value)?0 : value;
}

function evalExamSearchSelect(){
    const student_seq = document.querySelector('[data-main-student-seq]').value;
    const subject_seq = document.querySelector('[data-bundle="bottom_subjects"] [data-row="clone"].active').querySelector('[data-subject-seq]').value;
    const evaluation_seq = document.querySelector('[data-select-evaluation-seq]').value;
    const month = new Date().format('yyyy-MM');

    const page = "/student/my/score/subject/exam/search/select";
    const parameter = {
        student_seq:student_seq,
        subject_seq:subject_seq,
        evaluation_seq:evaluation_seq,
        month:month
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
           // 초기화
            const bundle = document.querySelector('[data-bundle="exam_evaluation_list"]');
            const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

            const student_exams = result.student_exams;
            const evaluation_codes = result.evaluation_codes;
            student_exams.forEach(function(exam){
                const row = row_copy.cloneNode(true);
                row.hidden = false;
                row.dataset.row = "clone";
                const my_rate = getNaNZero(exam?.correct_count) / getNaNZero(exam?.total_count) * 100;
                row.querySelector('[data-evaluation-name]').textContent = evaluation_codes[exam.evaluation_seq]?.code_name||'미배정';
                row.querySelector('[data-exam-title]').textContent = exam.exam_title;
                row.querySelector('[data-rate]').textContent = my_rate.toFixed(0);
                bundle.appendChild(row);
            });
        }else{}
    });
}
// 성적표확인
function evalMoveCheckTranscript(vthis){

}

// 상단 과목 박스 클릭.
function evalSubjectsClick(vthis){
    // 나머지 모두 비활성화.
    const bundle = document.querySelector('[data-bundle="bottom_subjects"]');
    const borders = bundle.querySelectorAll('[data-row="clone"] [data-border]');
    borders.forEach(function(border){
        border.closest('[data-row]').classList.remove('active');
        border.classList.remove('border');
        border.classList.remove('border-warning');
    });
    vthis.classList.add('active');
    vthis.querySelector('[data-border]').classList.add('border');
    vthis.querySelector('[data-border]').classList.add('border-warning');

    // 그래프 정보 가져오기
    evalSubjectJuchaSelect();

    // 하단 테이블 정보가져오기.
    evalExamSearchSelect();

}

</script>
