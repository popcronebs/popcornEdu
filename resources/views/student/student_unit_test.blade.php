@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '단원평가')
{{-- 학생 컨텐츠 --}}
<!-- TODO: 국어 영어 수학 과학 사회 반응 하도록 수정. / 데이터없이 화면만 보이도록 이라도 진행.-->

<!-- : 학년선택, 학기 선택 분리. 및 DB Connect -->
@section('layout_coutent')
<style>
    ol,
    ul {
        list-style: none;
    }
    #unit_test_ul_unit li:last-child{
        margin-bottom: 0 !important;
    }
</style>
<div class="unit-evaluation-container">
    {{-- 상단 --}}
    <article class="header-container">
        <div class="col-auto">
            <div class="h-center">
                <img src="{{ asset('images/graphic_schoolLearning_icon.png?1')}}" width="72" class="">
                <span class="cfs-1 fw-semibold align-middle">단원평가</span>
            </div>
            <div class="pt-2">
                <span class="cfs-3 fw-medium">평가문제로 내 실력을 확인해보세요.</span>
            </div>
        </div>
        <div class="h-center col-auto justify-content-end gap-3">
            <div class="col text-end position-relative">
                <div class="pt-5">
                    <div class="d-inline-block select-wrap select-icon" style="min-width:100px">
                        <select data-select-grade class="rounded-pill border-gray lg-select text-sb-24px ps-4 py-3" style="min-width:100px;" onchange="examSelect();">
                            <option value="">학년선택</option>
                            @if (!empty($grade_codes))
                            @foreach ($grade_codes as $grade_code)
                                <option value="{{ $grade_code->id }}" {{$student_grade == $grade_code->id ? 'selected':''}}>{{ $grade_code->code_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>

            <div class="col text-end position-relative">
                <div class="pt-5">
                    <div class="d-inline-block select-wrap select-icon" style="min-width:100px">
                        <select data-select-semester class="rounded-pill border-gray lg-select text-sb-24px ps-4 py-3" style="min-width:100px;" onchange="examSelect();">
                            <option value="">학기선택</option>
                            @if (!empty($semester_codes))
                            @foreach ($semester_codes as $semester_code)
                                <option value="{{ $semester_code->id }}">{{ $semester_code->code_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </article>

    {{-- padding 120px --}}
    <div>
        <div class="py-xxl-5 py-0"></div>
        <div class="pt-xxl-3 pt-2"></div>
    </div>

    {{-- 단원평가 목록 --}}
    <article class="p-0">
        {{-- 단원평가 카테고리 --}}
        <div class="aside-container">
            <aside class="list col-3">
                <div class="tab py-4 px-3 w-100 modal-shadow-style rounded-3">
                    <span class="list-title">평가목록</span>
                    <input type="checkbox" name="accordion" id="accordion"/>
                    <label class="arrow" for="accordion">
                        <img src="/images/svg/btn_arrow_down.svg" alt="">
                    </label>
                    <ul class="item" id="unit_test_ul_unit">
                        @if(!empty($evaluation_codes))
                            @foreach($evaluation_codes as $key => $evaluation_code)
                                @if($evaluation_code->is_use == 'Y')
                                    <li class="mb-2">
                                        <input type="hidden" data-evaluation-seq value="{{$evaluation_code->id}}">
                                        <button class="btn w-100 btn-outline-primary-y border-0 text-start text-b-20px py-3 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover " onclick="unitTestUnitClick(this)">
                                            <span class="py-1 h-center">
                                                <img src="{{ asset('images/danwon_test_icon.svg') }}" class="m-2">
                                                <span>{{$evaluation_code->code_name}}</span>
                                            </span>
                                        </button>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                        {{--  전국평가에는 등수가 들어간다. --}}
                        <li class="" hidden>
                            <button class="btn w-100 btn-outline-primary-y border-0 text-start text-b-20px py-3 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover" onclick="unitTestUnitClick(this)" data-is-national-evaluation>
                                <span class="py-1 h-center gap-xl-0 gap-lg-2">
                                    <img src="{{ asset('images/danwon_test_icon.svg') }}" class="m-2">
                                    <span>전국평가</span>
                                </span>
                            </button>
                        </li>
                    </ul>
                </div>
            </aside>

            {{-- 단원평가 상세 --}}
            <section class="w-100">
                <div class="modal-shadow-style p-4 contents rounded-3">
                    {{-- 과목 --}}
                    <div class="tap-cont py-2 subject" data-tab="0">
                        <ul class="d-inline-flex gap-2" id="unit_test_ul_subject">
                            @if(!empty($subject_codes))
                            @foreach($subject_codes as $idx => $subject_code)
                            @if($subject_code->is_use == 'Y' && $subject_code->code_name != "한자")

                            <li>
                                <button
                                    type="button"
                                    onclick="unitTestSubjectClick(this)"
                                    class="btn-ms-primary transition-none-button text-sb-24px rounded-pill scale-bg-gray_01 scale-text-gray_05 h-12 scale-text-white-hover {{ $idx == 0 ? 'active' : '' }}" data-code-seq="{{ $subject_code->id }}"
                                    data-type="subject"
                                    >
                                    {{ $subject_code->code_name }}
                                </button>
                            </li>
                            @endif
                            @endforeach
                            @endif
                        </ul>
                    </div>
                    <div class="tap-cont py-2" data-tab="1" hidden>
                        <ul class="d-inline-flex gap-2" id="unit_test_ul_subject_2">
                            @if(!empty($hanja_subject_codes))
                            @foreach($hanja_subject_codes as $subject_code)
                            <li>
                                <button
                                    type="button"
                                    onclick="unitTestSubjectClick(this)"
                                    class="btn-ms-primary transition-none-button text-sb-24px rounded-pill scale-bg-gray_01 scale-text-gray_05 h-12 scale-text-white-hover " data-code-seq="{{ $subject_code->id }}"
                                    data-type="subject2"
                                >
                                    {{ $subject_code->code_name }}
                                </button>
                            </li>
                            @endforeach
                            @endif
                        </ul>
                    </div>
                    {{-- 과목에 따른 단원평가 목록 --}}
                    {{-- <div class="pt-5">
                        <div class="text-center">
                            <img src="{{ asset('images/svg/noimg.svg') }}" class="text-center" width="100">
                        </div>
                        <p class="text-sb-20px text-center">현재 강의가 없어요.</p>
                    </div> --}}
                    <div class="unit-test-items" data-bundle="exams" >
                        <div class="unit-test-item" data-row="copy" hidden>
                            <input type="hidden" data-exam-seq="">
                            <div class="text-b-24px" data-exam-title>1단원</div>
                            <div class="is_national_evaluation" >
                                {{-- padding 70px --}}
                                <div style="height:100px"></div>
                                <div class="row" data-div-my-score hidden>
                                    {{-- 내점수 --}}
                                    <div class="col row px-2 rounded-4 bg-white m-0 me-2" style="">
                                        <div class="col-auto all-center p-0 pt-1 mx-1">
                                            <span class="text-sb-16px scale-text-gray_05 mt-2">내점수</span>
                                        </div>
                                        <div class="col-12 all-center justify-content-end pb-2 mx-1">
                                            <span class="text-b-32px" data-exam-rate>90</span>
                                            <span class="text-b-24px">점</span>
                                        </div>
                                    </div>
                                    {{-- 전국등수 --}}
                                    <div class="col row px-2 rounded-4 bg-white m-0 ms-1" style="" hidden>
                                        <div class="col-auto all-center p-0 pt-1 mx-1">
                                            <span class="text-sb-16px scale-text-gray_05 mt-2">전국 등수</span>
                                        </div>
                                        <div class="col-12 all-center justify-content-end pb-2 mx-1">
                                            <span class="text-b-32px">233</span>
                                            <span class="text-b-24px">등</span>
                                        </div>
                                    </div>
                                </div>
                                {{-- 신청하러가기 --}}
                                <div class="row px-4 rounded-4 bg-white m-0 cursor-pointer" style="height:68px;" data-div-apply onclick="unitTextGoExam(this);">
                                    <div class="col-auto all-center p-0">
                                        <img src="{{ asset('images/svg/uike_pen.svg') }}" width="24">
                                        <span class="text-b-20px">문제풀러가기</span>
                                    </div>
                                    <div class="col all-center justify-content-end">
                                        <button class="btn p-0 d-flex align-items-center gap-2">
                                            <img src="{{ asset('images/calendar_arrow_right.svg') }}" width="24">
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="unit-test-empty">
                        <span>평가 내용이 없습니다.</span>
                    </div>
                </div>
            </section>
        </div>
    </article>

    {{-- padding 160px --}}
    <div>
        <div class="py-lg-5"></div>
        <div class="py-lg-4"></div>
        <div class="pt-lg-3"></div>
    </div>

</div>


<!-- 단원평가에서는 알수가 없다. -->
<!-- lecture_ 관련된 값들은. -->
<form id="form_post" action="" target="_self" hidden>
    @csrf
    <input type="hidden" name="exam_seq" value="">
</form>
<script>
document.addEventListener("DOMContentLoaded", function(){
    // 평가분류  첫번째 클릭.
    document.querySelector('#unit_test_ul_unit button').click();
});

function hanjaTabList(){
    const main_code = document.querySelector('[data-main-code]').value;
    const page = "/student/unit/test/hanja/list";
    const parameter = {
        main_code: main_code
    };
    queryFetch(page, parameter, function(result){
        console.log(result);
    });
}

// 단원평가쪽 목록 클릭
function unitTestUnitClick(vthis) {
    const ul_unit = document.querySelector('#unit_test_ul_unit');
    const btn_units = ul_unit.querySelectorAll('button');
    const evaluation_seq = vthis.closest('li').querySelector('[data-evaluation-seq]').value;
    //모든 단원평가 버튼 비활성화
    btn_units.forEach(function(btn_unit) {
        btn_unit.classList.remove('active');
    });
    vthis.classList.add('active');
    // 이후 기능 구현 / 전국평가인 경우
    if(evaluation_seq == '41'){
        document.querySelector('[data-tab="0"]').hidden = false;
        document.querySelector('[data-tab="1"]').hidden = true;
        document.querySelector('#unit_test_ul_subject button').click();
    }else if(evaluation_seq == '42'){
        document.querySelector('[data-tab="0"]').hidden = true;
        document.querySelector('[data-tab="1"]').hidden = false;
        document.querySelector('#unit_test_ul_subject_2 button').click();
    }
    if (vthis.getAttribute('data-is-national-evaluation') != null) {
        // 전국평가
        // document.querySelectorAll('.is_national_evaluation').forEach(function(v) {
        //     v.removeAttribute('hidden');
        // });
        // document.querySelectorAll('.none_national_evaluation').forEach(function(v) {
        //     v.setAttribute('hidden', true);
        // });
    } else {
        // 전국평가 아닌경우
        examSelect();
    }
}

// 전국평가 > 신청하러가기 클릭
function unitTestApply(vthis) {
    // 신청하러가기 클릭시 이후 기능 구현
    const msg = document.createElement('div');
    const line1 = document.createElement('div');
    const line2 = document.createElement('div');
    const line3 = document.createElement('div');

    line1.classList.add('text-b-28px')
    line1.classList.add('pt-2');
    line2.classList.add('text-b-28px')
    line2.classList.add('pt-2');
    line2.classList.add('basic-text-error');
    line3.classList.add('text-r-20px')
    line3.classList.add('pt-3');
    line3.classList.add('scale-text-gray_05')

    line1.innerText = '신청비용은 5,000원입니다.';
    line2.innerText = '신청하시겠습니까?';
    line3.innerText = '(신청 후 취소나 환불은 불가입니다.)';

    msg.appendChild(line1);
    msg.appendChild(line2);
    msg.appendChild(line3);

    sAlert('2024년 상반기 전국평가', msg.outerHTML, 3, function() {
        // 확인 클릭시 이후 기능 구현
    }, null, '결제요청하기', '닫기');

}

// 썌얘: 우선은 학년, 학기가 모두 나오게 하고 추후에 조건에 맞게 안나오게 업데이트 해야할듯
// 평가불러오기
function examSelect(data){
    const subject_seq = data?.dataset.codeSeq || document.querySelector('#unit_test_ul_subject button.active')?.dataset?.codeSeq;
    const subject_type = data?.dataset.type||'';
    const grade_seq = document.querySelector('[data-select-grade]').value ;
    const semester_seq = document.querySelector('[data-select-semester]').value;
    const evaluation_seq = document.querySelector('#unit_test_ul_unit button.active').closest('li').querySelector('[data-evaluation-seq]').value;
    let subject_name = '';
    if(subject_seq == ''){
        alert('과목을 선택해주세요.');
        return;
    }
    if(grade_seq == ''){
        alert('학년을 선택해주세요.');
        return;
    }
    if(evaluation_seq == ''){
        alert('평가분류를 선택해주세요.');
        return;
    }
    const page = "/student/unit/test/exam/select";
    const parameter = {
        subject_seq: subject_seq,
        grade_seq: grade_seq,
        semester_seq: semester_seq,
        evaluation_seq: evaluation_seq,
        subject_type:subject_type
    };
    queryFetch(page, parameter, function(result){
        console.log(result);
        if((result.resultCode||'') == 'success'){
            // 초기화
            const bundle = document.querySelector('[data-bundle="exams"]');
            const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);
            const exams = result.exams;
            const student_exams = result.student_exams;
            exams.forEach(function(exam, index){
                const row = row_copy.cloneNode(true);
                row.dataset.row = "clone";
                row.hidden = false;
                row.querySelector('[data-exam-title]').innerText = `${index+1}단원 ${subject_name}`;
                row.querySelector('[data-exam-seq]').value = exam.id;
                // 시험을 쳤으면 점수 보여주기.
                if(student_exams[exam.id]?.length > 0){
                    row.querySelector('[data-div-my-score]').hidden = false;
                    row.querySelector('[data-exam-rate]').innerText = Math.floor(student_exams[exam.id][0].rate);
                    row.querySelector('[data-div-apply]').hidden = true;
                }
                // 시험을 안쳤으면, 신청하러가기 보여주기.
                else{
                    row.querySelector('[data-div-apply]').hidden = false;
                }
                bundle.appendChild(row);
            });
        }


        const empty = document.querySelector('.unit-test-empty')
        if(result.exams.length === 0) {
            empty.style.display = 'flex';
            return;
        } else {
            empty.style.display = 'none'
        }
    });
}

// 과목클릭
async function unitTestSubjectClick(vthis) {
    const ul_subject = document.querySelector(`#${vthis.parentNode.parentNode.id}`);
    const btn_subjects = ul_subject.querySelectorAll('button');

    //모든 과목 버튼 비활성화
    await btn_subjects.forEach(function(btn_subject) {
        btn_subject.classList.remove('active');
        btn_subject.classList.remove('scale-text-white');
        btn_subject.classList.add('scale-text-gray_05');
        btn_subject.classList.add('scale-bg-gray_01');
    });

    await vthis.classList.remove('scale-bg-gray_01');
    await vthis.classList.remove('scale-text-gray_05');
    await vthis.classList.add('active');
    await vthis.classList.add('scale-text-white');

    // 이후 기능 구현
    examSelect(vthis);
}

function unitTestSubjectClick2(vthis) {
    const ul_subject = document.querySelector('#unit_test_ul_subject_2');
    const btn_subjects = ul_subject.querySelectorAll('button');
    vthis.classList.remove('border-none');
    vthis.classList.remove('scale-text-gray_05');
    vthis.classList.add('active');
    //모든 과목 버튼 비활성화
    btn_subjects.forEach(function(btn_subject) {
        btn_subject.classList.remove('active');
        btn_subject.classList.add('border-none');
        btn_subject.classList.add('scale-text-gray_05');
    });
    // 이후 기능 구현
    examSelect(vthis);
}


// 시험치러 가기.
function unitTextGoExam(vthis){
    const msg = '<div class="text-sb-24px">시험을 시작 하시겠습니까? <br> 완료를 하셔야 저장이 됩니다.</div>';
    sAlert('', msg,3, function(){
        unitTextGoExamDetail(vthis);
    });
}

function unitTextGoExamDetail(vthis){
    const row = vthis.closest('[data-row="clone"]');
    const exam_seq = row.querySelector('[data-exam-seq]').value;
    const exam_title = row.querySelector('[data-exam-title]').innerText;
    const page = "/student/unit/test/exam/null/delete";
    const parameter = {
        exam_seq: exam_seq,
    };
    queryFetch(page, parameter, function(result){

    const formData = new FormData();
    formData.append('title', 'test');
        if((result.resultCode || '') == 'success'){
            const form = document.querySelector('#form_post');
            form.method = 'post';
            form.querySelector('[name="exam_seq"]').value = exam_seq;
            formData.append('title', exam_title);

            // FormData의 내용을 폼에 숨겨진 필드로 추가
            for (let [key, value] of formData.entries()) {
                const hiddenField = document.createElement('input');
                hiddenField.type = 'hidden';
                hiddenField.name = key;
                hiddenField.value = value;
                form.appendChild(hiddenField);
            }

            form.action = "/student/study/freeQuiz";
            rememberScreenOnSubmit();
            form.submit();
        }
    });
}


</script>
@endsection
