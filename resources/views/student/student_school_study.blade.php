@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '학교공부')

@section('add_css_js')
<link href="{{ asset('css/reset.css?5') }}" rel="stylesheet">
@endsection

{{-- 학생 컨텐츠 --}}
@section('layout_coutent')
<!-- : 선생님 정보 불러오기. -->
<!-- : 학교공부 / 학년 / 학기 / 과목 조건에 맞는 선생님 + 단원을 불러온다.-->
<!-- : 학습전이면 다시듣기 버튼 삭제. -->

<!-- NOTE:    -->
<!-- - 담임이 아니라, 학습영상등록시에 등록하는 선생님을 연결하는게 맞을까요? -->
<!-- 학교공부 / 3학년 / 1학기 / 국어 검색시 -->
<!-- 나오는 선생님이 선택되고 그 선생님에 따라서 오른쪽 학습리스트가 변경되는건지? -->
<!-- - 네 맞습니다. 영상 등록 시 등록된 강사입니다. -->
<!-- 그리고 관련 강좌가 없을 것으로 예상되어서 임시로 프로필을 중앙에 두도록 하겠습니다. -->

<!-- NOTE: like 버튼 기능 일단 숨김 처리.-->

<style>
    .h-center.gap-2.pe-5.grid-2 {
        display: grid;
        grid-template-columns: 1fr 128px;
    }
    .content.h-100{
        height: auto !important;
    }
    @media (max-width: 1400px) {
        .tablets-responsive{
            max-height: calc(100vh - 322px);
            overflow-y: auto;
        }
        .btn-ms-primary{
            height: 40px;
        }
    }
</style>
<input type="hidden" id="study_login_type" value="{{ $login_type }}">
<div class="col mx-0 mb-3 pt-0 pt-xxl-5 row position-relative">
    {{-- 상단 --}}
    <article class="pt-0 pt-xxl-5 px-0">
        <div class="row">
            <div class="col">
                <div class="h-center">
                    <img src="{{ asset('images/school_study_icon.svg?1') }}" width="72">
                    <span class="cfs-1 fw-semibold align-middle">학교공부</span>
                </div>
                <div class="pt-0 pt-xxl-2">
                    <span class="cfs-3 fw-medium">선생님과 함께 학교공부를 할 수 있어요.</span>
                </div>
            </div>
            <div class="h-center col-auto justify-content-end gap-3">
                <div class="col text-end position-relative">
                    <div class="pt-5">
                        <div class="d-inline-block select-wrap select-icon" style="min-width:100px">
                            <select data-select-grade class="rounded-pill border-gray lg-select text-sb-24px ps-3 py-2 ps-xxl-4 py-xxl-3" style="min-width:100px;" onchange="schoolStudyLectureSelect();">
                                <option value="">{{$login_type=='teacher'?'학년전체':'학년전체'}}</option>
                                @if (!empty($grade_codes))
                                @foreach ($grade_codes as $grade_code)
                                <option value="{{ $grade_code->id }}" {{ $state->getStudent()->grade??'' == $grade_code->id ? '' : '' }}>{{ $grade_code->code_name }}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col text-end position-relative">
                    <div class="pt-5">
                        <div class="d-inline-block select-wrap select-icon" style="min-width:100px">
                            <select data-select-semester class="rounded-pill border-gray lg-select text-sb-24px ps-3 py-2 ps-xxl-4 py-xxl-3" style="min-width:100px;" onchange="schoolStudyLectureSelect();">
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
        </div>
    </article>

    {{-- 120 --}}
    <div>
        <div class="py-0 py-xxl-5"></div>
        <div class="pt-2 pt-xxl-4"></div>
    </div>

    {{-- --}}
    <article>
        {{-- 과목 TAB --}}
        <section>
            <div class="row mx-0 mb-0 mb-xxl-5">
                <ul class="col d-inline-flex gap-2 mb-3 px-0">
                    @if(!empty($subject_codes))
                    {{-- 첫 변수는 활성화 --}}
                    @foreach($subject_codes as $idx => $subject_code)
                    <li>
                        <button onclick="schoolStudySubjectTab(this)" data-btn-study-subject-tab="{{ $subject_code->id }}" type="button" class="btn-ms-primary text-sb-24px rounded-pill px-3 px-sm-4
                            @if($idx == 0) scale-text-white active
                            @else scale-bg-gray_01 scale-text-gray_05 scale-text-white-hover
                            @endif">
                            {{ $subject_code->code_name }}
                        </button>
                    </li>
                    @endforeach
                    @endif
                </ul>
            </div>
        </section>
        <div class="row gap-4" data-parent-bundle="learning_list">
            <aside class="col-lg-3 mx-0 px-0 d-none" data-aside-teacher-info>
                <div class="col-12 rounded-3 modal-shadow-style p-3 d-flex align-items-center" style="height:400px;">
                    <div class=" col pb-3">
                        <div class="d-flex align-items-center justify-content-center position-relative mt-3">
                            <div class="border rounded-circle overflow-hidden cursor-pointer" style="width:160px;height:160px;" onclick="teachMessModalChgTeachImg() ">
                                <img src="" width="160" data-main-profile-img>
                            </div>
                            <div class="position-absolute top-0 end-0 me-2 mb-2">
                                <button class="btn btn-sm rounded-circle shadow all-center" style="width:42px;height:42px" hidden>
                                    <img src="{{ asset('images/hart_icon.svg') }}" width="24" data-is-like="red" hidden>
                                    <img src="{{ asset('images/gray_hart_icon.svg') }}" width="24" data-is-like="gray">
                                </button>
                            </div>
                        </div>
                        <input type="hidden" class="teach_seq" value="">
                        <div class="row mt-3">
                            <div class="col-auto">
                                <button class="btn px-0" hidden>
                                    <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="" data-pr>
                                </button>
                            </div>
                            <div class="col text-center">
                                <div class="text-b-24px">
                                    <span data-main-teacher-name data-explain="#김팝콘 선생님"></span>
                                </div>
                                <div class="text-r-18px scale-text-gray_05 mt-2">
                                    담당과목:
                                    <span class="scale-text-gray_05" data-main-responsibility-subject data-explain="#국어"></span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button class="btn px-0" hidden>
                                    <img src="{{ asset('images/calendar_arrow_right.svg') }}" alt="">
                                </button>
                            </div>
                        </div>
                        <div class="col d-flex align-items-center mt-4">
                            <div class="w-100 border-0 bg-light p-4 text-secondary fw-medium text-center fs-5" style="resize: none" rows="3" id="teach_mess_div_teach_intro" maxlength="200" contenteditable="true" hidden>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
            <div class="col-lg mx-0 px-0 modal-shadow-style rounded-3 overflow-hidden flex-column" data-bundle="group_bundle">
                <section class="col-lg mx-0 px-0 modal-shadow-style rounded-3 overflow-hidden mb-2" data-row="group_row" hidden>
                    <div class="col text-start d-flex gap-2 p-2 p-xxl-4 primary-bg-mian">
                        <h2 class="text-b-24px d-flex align-items-center col" data-main-lecture-name data-explain="1단원. 재미가 톡톡톡"></h2>
                        <button class="btn p-0 h-center" onclick="schoolStudyFoldDetail(this);" data-btn-rotate>
                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" width="32">
                        </button>
                    </div>
                    <div class="px-4 pt-0 pb-0 tablets-responsive">
                        <div class="d-flex-inline flex-column gap-3 pt-4 pb-4" data-bundle="learning_list">
                            <div class="row rounded-3 scale-bg-gray_01 mx-0 mb-2" data-row="copy" hidden>
                                <input type="hidden" data-st-lecture-detail-seq>
                                <input type="hidden" data-lecture-seq>
                                <input type="hidden" data-lecture-detail-seq>

                                <div class="col-auto text-b-20px scale-text-gray_06 p-4 align-items-center">
                                    <div class="p-2">
                                        <span class="text-b-20px scale-text-gray_06" data-explain="#1장" data-lecture-idx></span>
                                    </div>
                                </div>
                                <div class="col-auto h-center">
                                    <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12">
                                </div>
                                <div class="col h-center ps-5">
                                    <div class="scale-text-black text-b-20px ps-1" data-explain="#시나 이야기에 나타난 감각적 표현 알기" data-lecture-detail-name></div>
                                </div>
                                <div class="col-auto h-center gap-2 pe-5 grid-2">
                                    <span data-status data-explain="학습완료"></span>
                                    <div style="min-width: 165px;">
                                        <button onclick="schoolStudyPlayVido(this);" data-btn-replay class="btn rounded-pill text-sb-20px btn-light scale-bg-white border scale-bg-gray_01-hover py-1 px-3 all-center me-3">
                                            <img src="{{ asset( 'images/black_arrow_right_notail.svg' ) }}">
                                            <span data-btn-replay-text>다시듣기</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </article>
    <div data-explain="160">
        <div class="py-lg-5"> </div>
        <div class="py-lg-4"> </div>
        <div class="pt-lg-3"> </div>
    </div>
</div>


<form method="POST" action="/student/study/video" data-form="study_video" hidden>
    @csrf
    <input name="st_lecture_detail_seq" />
    <input name="prev_page" value="school_study" />
</form>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        schoolStudyLectureSelect();
    });
    //
    function schoolStudySubjectTab(vthis) {
        // data-btn-study-subject-tab의 active 제거
        let btns = document.querySelectorAll('[data-btn-study-subject-tab]');
        btns.forEach((btn) => {
            btn.classList.remove('active');
            btn.classList.add('scale-bg-gray_01');
            btn.classList.add('scale-text-gray_05');
            btn.classList.add('scale-text-white-hover');
            btn.classList.remove('scale-text-white');
        });
        vthis.classList.remove('scale-bg-gray_01');
        vthis.classList.remove('scale-text-gray_05');
        vthis.classList.remove('scale-text-white-hover');
        vthis.classList.add('active');
        vthis.classList.add('scale-text-white');
        schoolStudyLectureSelect();
    }

    // 학년, 학기, 학교공부, 과목 선택시
    let lectures = null;
    let lecture_details = null;

    function schoolStudyLectureSelect() {
        const grade = document.querySelector('[data-select-grade]').value;
        const semester = document.querySelector('[data-select-semester]').value;
        const subject = document.querySelector('[data-btn-study-subject-tab].active').dataset.btnStudySubjectTab;

        const page = "/student/school/study/select";
        const parameter = {
            grade: grade,
            semester: semester,
            subject: subject
        };
        queryFetch(page, parameter, function(result) {

            if ((result.resultCode || '') == 'success') {
                const group_bundle = document.querySelector('[data-bundle="group_bundle"]');
                const group_row = group_bundle.querySelector('[data-row="group_row"]').cloneNode(true);
                group_bundle.innerHTML = '';
                group_row.hidden = true;
                group_bundle.appendChild(group_row);

                if(!Array.isArray(result.lecture_details)){
                    const bundle = document.querySelector('[data-bundle=learning_list]');
                    const row_copy = document.querySelector('[data-row=copy]').cloneNode(true);
                    bundle.innerHTML = '';
                    bundle.appendChild(row_copy);
                    lectures = result.lectures;
                    lecture_details = result.lecture_details;
                    lectures.forEach(( lecture, idx ) => {
                        const group_clone_row = group_row.cloneNode(true);
                        group_clone_row.hidden = false;
                        schoolStudyOneLecture(idx, group_clone_row, group_bundle);
                    });
                    lectures.forEach(function(lecture) {});
                }else{
                    const bundle = document.querySelector('[data-bundle=learning_list]');
                    const row_copy = document.querySelector('[data-row=copy]').cloneNode(true);
                    bundle.innerHTML = '';
                    bundle.appendChild(row_copy);
                    lectures = result.lectures;
                    lecture_details = result.lecture_details;
                    schoolStudyOneLecture(0,group_row, group_bundle);
                    lectures.forEach(function(lecture) {});
                }
            }


        });
    }

    // 학습 1개 선택 시.
    function schoolStudyOneLecture(num, group_row, group_bundle) {
        let count = 1;
        if (lectures != undefined && lectures.length > 0 && lectures.length >= num) {


            const bundle = group_row.querySelector('[data-bundle=learning_list]');
            const row_copy = document.querySelector('[data-row=copy]').cloneNode(true);

            const lecture = lectures[num];
            const details = lecture_details[lecture.id];

            group_row.querySelector('[data-main-lecture-name]').innerText = lecture.lecture_name;
            document.querySelector('[data-main-teacher-name]').innerText = lecture.teach_name;
            document.querySelector('[data-main-responsibility-subject]').innerText = lecture.subject_name;
            if (lecture.profile_img_path)

                document.querySelector('[data-main-profile-img]').src = '/storage/uploads/user_profile/teacher/' + lecture.profile_img_path;
            if(details == undefined){
                schoolStudyNoneLecture();
                return;
            }
                details.forEach(function(detail, index) {

                    if(detail.lecture_detail_type != "exam_solving" && detail.is_use == "Y"){
                        // console.log(detail.lecture_detail_name, detail.status)
                        const row = row_copy.cloneNode(true);
                        row.hidden = false;
                        row.querySelector('[data-st-lecture-detail-seq]').value = detail.st_lecture_detail_seq;
                        row.querySelector('[data-lecture-seq]').value = detail.lecture_seq;
                        row.querySelector('[data-lecture-detail-seq]').value = detail.id;
                        row.querySelector('[data-lecture-detail-name]').innerText = detail.lecture_detail_name;
                        //idx는 2부터 1로 시작
                        row.querySelector('[data-lecture-idx]').innerText = `${count++}강`;
                        if (detail.status == 'complete') {
                            row.querySelector('[data-status]').innerText = '학습 완료';
                            row.querySelector('[data-status]').classList.add('studey-completion');
                            row.querySelector('[data-btn-replay] [data-btn-replay-text]').textContent = '복습하기';

                        } else if (detail.status == 'study') {
                            row.querySelector('[data-status]').innerText = '학습 중';
                            row.querySelector('[data-status]').classList.add('studey-doing');
                            row.querySelector('[data-btn-replay] [data-btn-replay-text]').textContent = '학습하기';
                        } else { //(detail.status == 'ready') {
                            row.querySelector('[data-status]').innerText = '학습 전';
                            row.querySelector('[data-status]').classList.add('studey-before');
                            row.querySelector('[data-btn-replay] [data-btn-replay-text]').textContent = '학습하기';
                        }

                            // row.querySelector('[data-status]').style.marginRight = '148px';
                            //: 오늘 날짜가 지났을 경우 에 하단 코드 실행.
                            // 오늘보다 날짜가 크면서, 학습전인 경우 다시듣기 버튼 삭제.
                            // 상태 삭제 = 버튼삭제
                            // if (detail.sel_date > new Date().format('yyyy-MM-dd') &&
                            //     detail.status == 'ready' || detail.status == 'delete')
                            //     row.querySelector('[data-btn-replay]').remove();
                            bundle.appendChild(row);
                    }

                });
            const bundle_pt = document.querySelector('[data-parent-bundle=learning_list]');
            bundle_pt.hidden = false;
            group_bundle.appendChild(group_row);
            if(num > 0){
                const btn_rotate = group_row.querySelector('[data-btn-rotate]');
                const bundle2 = group_row.querySelector('[data-bundle="learning_list"]');
                btn_rotate.classList.add('rotate-180');
                bundle2.hidden = true;
            }
        }else{
            schoolStudyNoneLecture(group_row);
        }
    }
    // 학습영상이 없으면 나타나게 하기.
    function schoolStudyNoneLecture(group_row){
        group_row.querySelector('[data-main-lecture-name]').innerHTML ='';
        const bundle = group_row.querySelector('[data-bundle="learning_list"]');
        const bundle_pt = document.querySelector('[data-parent-bundle=learning_list]');
        const div = document.createElement('div');
        const text = document.createElement('div');
        const img = document.createElement('img');
        const imgArr = ['/images/creative_experience_chick_character.svg', '/images/graphic_character_skillup.svg'];
        const randomIndex = Math.floor(Math.random() * imgArr.length);
        img.src = imgArr[randomIndex];
        img.style.width = '160px';
        img.style.height = '160px';
        div.className = 'd-flex flex-column align-items-center';
        text.innerHTML = `학습 영상이 없습니다.<br>학습 플래너의 수업을 추가해주세요.`;
        text.className = 'text-center text-sb-20px';
        div.appendChild(img);
        div.appendChild(text);
        bundle.appendChild(div);
        bundle_pt.hidden = false;
        group_row.hidden = false;
    }

    // 학습리스트 접기
    function schoolStudyFoldDetail(vthis) {
        //180도 회전
        const group_row = vthis.closest('[data-row="group_row"]');
        const bundle = group_row.querySelector('[data-bundle="learning_list"]');
        if (vthis.classList.contains('rotate-180')) {
            vthis.classList.remove('rotate-180');
            bundle.hidden = false;
        } else {
            vthis.classList.add('rotate-180');
            bundle.hidden = true;
        }

    }

    // 학습하기 - 학교공부 - 학습영상으로 이동
    // 다시 학습.
    function schoolStudyPlayVido(vthis) {
        const login_type = document.querySelector('#study_login_type').value;
        if(login_type == 'teacher'){
            // toast('선생님은 학습할수 없습니다.');
            // return;
        }
        const row = vthis.closest('[data-row]');
        const student_lecture_detail_seq = row.querySelector('[data-st-lecture-detail-seq]').value;
        // 학생-학습상세 seq가 있을 경우.
        if(student_lecture_detail_seq){
            const pt_row = vthis.closest('[data-row]');
            const st_lecture_detail_seq = pt_row.querySelector('[data-st-lecture-detail-seq]').value;
            const form = document.querySelector('[data-form="study_video"]');
            form.querySelector('input[name="st_lecture_detail_seq"]').value = st_lecture_detail_seq;
            form.submit();
        }
        // 없어서 생성해야할때.
        else{
            const lecture_seq = row.querySelector('[data-lecture-seq]').value;
            const lecture_detail_seq = row.querySelector('[data-lecture-detail-seq]').value;

            const page = "/student/school/study/insert";
            const parameter = {
                lecture_seq: lecture_seq,
                lecture_detail_seq: lecture_detail_seq
            };
            const msg = '<div class="text-sb-24px">학교공부의 학습을 시작하시겠습니까?</div>';
            sAlert('', msg, 3, function(){
                queryFetch(page, parameter, function(result) {
                    if((result.resultCode||'') == 'success'){
                        const student_lecture_detail_seq = result.student_lecture_detail_seq;
                        row.querySelector('[data-st-lecture-detail-seq]').value = student_lecture_detail_seq;
                        schoolStudyPlayVido(vthis);
                    }else{}
                });
            });
        }
    }
</script>

@endsection
