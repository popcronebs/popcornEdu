@php
    //codes_all 컨트롤러에서 가져옴.
    if(!empty($codes_all)){
        $grade_codes = $codes_all->where('code_category', 'grade')->where('code_step', '=', 1);
        $subject_codes = $codes_all->where('code_category', 'subject')->where('code_step', '=', 1);
        $series_codes = $codes_all->where('code_category', 'series')->where('code_step', '=', 1);
        $publisher_codes = $codes_all->where('code_category', 'publisher')->where('code_step', '=', 1);
        $course_codes = $codes_all->where('code_category', 'course')->where('code_step', '=', 1);
    }
@endphp

<style>
.modal-backdrop{
    display: none;
}
</style>

<div id="timetable_add_div_main">
    {{-- input, btn 제목편집, select grade_codes --}}
    <input type="hidden" class="timetable_group_seq" id="timetable_add_inp_timetable_group_seq">
    <div class="row p-0 m-0 mb-3">
        <div class="col">
            <input type="text" class="form-control timetable_group_title" placeholder="제목"
                id="timetable_add_inp_timetable_group_title">
        </div>
        <div class="col-1">
            <select class="form-select grade_code" id="timetable_add_grade_code"
                onchange="timetableAddGradeCodeChange();">
                @foreach ($grade_codes as $i => $grade_code)
                    <option value="{{ $grade_code->id }}" {{ $i == 0 ? 'selected' : '' }}> {{ $grade_code->code_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col text-end">
            <button class="btn btn-outline-secondary" onclick="timetableAddClose();"> 샘플목록으로 돌아가기 </button>
            <button class="btn btn-outline-primary btn_group_insert" onclick="timetableAddGroupInsert();"> 샘플/제목/학년/등록
            </button>
        </div>
    </div>

    <div class="row p-0 m-0 px-3 div_timetables" hidden>
        <table class="table table-bordered mb-0">
            <thead class="table-light text-center">
                <tr>
                    <th> 강좌명 </th>
                    <th> 학습시작일 </th>
                    <th> 시작강의 </th>
                    <th> 학습요일 </th>
                    <th> 기능 </th>
                </tr>
            </thead>
            <tbody id="timetable_add_tby_list">
                <tr class="copy_tr_list text-center" >
                    <td data="#강좌명" class="col lecture_name">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span
                                class="placeholder col-12"></span> </p>
                    </td>
                    <td data="#학습시작일" class="col timetable_start_date">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span
                                class="placeholder col-12"></span> </p>
                    </td>
                    {{-- 시작강의 --}}
                    <td data="#시작강의" class="col-auto w-auto">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span
                                class="placeholder col-12"></span> </p>
                        <div class="div_hidden" hidden>
                            <select class="form-select form-select-sm start_lecture_detail" 
                            onclick="timetableAddLectureDetailSelect(this.closest('tr').querySelector('.lecture_seq').value, this, this.getAttribute('data'))">
                            </select>
                        </div>
                    </td>
                    <td data="#학습요일" class="col">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span
                                class="placeholder col-12"></span> </p>
                        <div class="row p-0 m-0 div_timetable_days gap-2 div_hidden justify-content-center" hidden>
                            <span class="col-auto" data="월">월</span>
                            <span class="col-auto" data="화">화</span>
                            <span class="col-auto" data="수">수</span>
                            <span class="col-auto" data="목">목</span>
                            <span class="col-auto" data="금">금</span>
                            <span class="col-auto" data="토">토</span>
                            <span class="col-auto" data="일">일</span>
                        </div>
                    </td>
                    <td data="#기능" class="col-auto w-auto">
                        <p class="card-text placeholder-glow loding_place mb-0"> <span
                                class="placeholder col-12"></span> </p>
                        <div class="div_hidden" hidden>
                            <button class="btn btn-outline-danger btn-sm px-3"
                            onclick="timetableAddLectureDelete(this)"
                            > 삭제 </button>
                        </div>
                    </td>
                    <input type="hidden" class="timetable_seq">
                    <input type="hidden" class="timetable_group_seq">
                    <input type="hidden" class="lecture_seq">
                </tr>
            </tbody>
        </table>
        {{-- 강좌 목록이 없습니다. --}}
        <div class="text-center mt-4 mb-5" id="timetable_add_div_empty" hidden>
            <p class="fs-6 text-muted"> 강좌 목록이 없습니다. </p>
        </div>
        {{-- 강좌 추가 하기 --}}
        <div class="text-center mt-3">
            <button class="btn btn-outline-primary" onclick="timetableAddLectureInsertOpen();"> 강좌 추가 하기 </button>
        </div>
</div>
</div>

        {{-- 강좌추가 div --}}
        <div id="timetable_add_div_lecture_insert" class="position-absolute w-100 h-100 bg-white" style="top: 0; left: 0; z-index:3" hidden>
        <div class="text-center bg-white">
            <hr>
            <h5 class="text-start mt-5">강좌 추가</h5>
            {{-- 강좌 검색 란 --}}
            <div class="row p-0 m-0 mb-3 align-items-center gap-3">
                <div class="col-4">
                    <div class="input-group col-atuo">
                        <input type="search" class="form-control" placeholder="강좌명을 입력하세요">
                        <button class="btn btn-outline-secondary" type="button" id="button-addon2" onclick="timetableAddLectureSelectModal();">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                class="bi bi-search" viewBox="0 0 20 20">
                                <path
                                    d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                            </svg>
                        </button>
                    </div>
                </div>
                {{-- 강좌분류 label, 특강 select --}}
                <div class="col row p-0 m-0 mt-2">
                    <div class="col-auto fs-5">
                        <label class="col-form-label">강좌분류</label>
                    </div>
                    <div class="col-4">
                        <select class="form-select col-auto" id="timetable_add_sel_course_seq" onchange="timetableAddChkOptionForLecture()">
                            @if(!empty($course_codes))
                            @foreach ($course_codes as $course_code)
                                <option value="{{ $course_code->id }}"> {{ $course_code->code_name }} </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                {{-- 강좌 상세 설정 --}}
                <div class="row p-0 m-0">
                    {{-- 과목 --}}
                    <div class="row col-12 border-top">
                        <div class="col-1 p-3">과목</div>
                        <div class="col p-3">
                            @if(!empty($subject_codes))
                            @foreach ($subject_codes as $subject_code)
                                <input type="radio" name="radio_subject" class="btn-check subject" id="timetable_add_radio_code_{{ $subject_code->id }}"
                                    autocomplete="off" code_seq="{{ $subject_code->id }}" onclick="timetableAddChkOptionForLecture();">
                                <label class="btn btn-outline-secondary hpx-40 rounded" for="timetable_add_radio_code_{{ $subject_code->id }}">
                                    {{ $subject_code->code_name }}
                                </label>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    {{-- 시리즈 --}}
                    <div class="row col-12 border-top">
                        <div class="col-1 p-3">시리즈</div>
                        <div class="col p-3">
                            @if(!empty($series_codes))
                            @foreach ($series_codes as $series_code)
                                <input type="radio" name="radio_series" class="btn-check series" id="timetable_add_radio_code_{{ $series_code->id }}"
                                    autocomplete="off" code_seq="{{ $series_code->id }}" onclick="timetableAddChkOptionForLecture();">
                                <label class="btn btn-outline-secondary hpx-40 rounded" for="timetable_add_radio_code_{{ $series_code->id }}">
                                    {{ $series_code->code_name }}
                                </label>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    {{-- 출판사 --}}
                    <div class="row col-12 border-top">
                        <div class="col-1 p-3">출판사</div>
                        <div class="col p-3">
                            @if(!empty($publisher_codes))
                            @foreach ($publisher_codes as $publisher_code)
                                <input type="radio" name="radio_publisher" class="btn-check publisher" id="timetable_add_radio_code_{{ $publisher_code->id }}"
                                    autocomplete="off" code_seq="{{ $publisher_code->id }}" onclick="timetableAddChkOptionForLecture();">
                                <label class="btn btn-outline-secondary hpx-40 rounded" for="timetable_add_radio_code_{{ $publisher_code->id }}">
                                    {{ $publisher_code->code_name }}
                                </label>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    {{-- 선택한 강의 --}}
                    <div class="row col-12 border-top">
                        <div class="col-1 p-3">선택한 강의</div>
                        <div class="col p-3 row justify-content-center">
                            <div class="col-lg-8" id="timetable_add_div_lecture_info" hidden>
                                <input type="hidden" class="lecture_seq">
                                <input type="hidden" class="lecture_name">
                                <div class="row m-0 p-0 mb-3">
                                    <div class="col-4 p-0 m-0">
                                        <div class="col-12 p-0 m-0">
                                            <label class="col-form-label">시작강의</label>
                                        </div>
                                    </div>
                                    <div class="col p-0 ms-2">
                                        <select class="form-select start_lecture_detail">
                                            <option value=""> 전체 </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row p-0 m-0">
                                    <div class="col-5">
                                        <div class="text-center p-1 border">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-book" viewBox="0 0 20 20">
                                                <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"></path>
                                            </svg>
                                            <span class="subject_name">#영어</span>
                                        </div>
                                        <img src="" alt="" class="file_path border w-100">
                                    </div>
                                    <div class="col row">
                                        <div class="col-12 m-0 p-0 border-bottom row">
                                            <div class="col-2 p-1 bg-light d-flex justify-content-center align-items-center">선생님</div>
                                            <div class="col p-1 text-secondary text-start ps-3 d-flex align-items-center teacher_name"></div>
                                        </div>
                                        <div class="col-6 m-0 p-0 border-bottom row">
                                            <div class="col-4 p-1 bg-light d-flex justify-content-center align-items-center">수강대상</div>
                                            <div class="col p-1 text-secondary text-start ps-3 d-flex align-items-center grade_name"></div>
                                        </div>
                                        <div class="col-6 m-0 p-0 border-bottom row">
                                            <div class="col-4 p-1 bg-light d-flex justify-content-center align-items-center">강좌수준</div>
                                            <div class="col p-1 text-secondary d-flex align-items-center text-start ps-3 level_name"></div>
                                        </div>
                                        <div class="col-6 m-0 p-0 border-bottom row">
                                            <div class="col-4 p-1 bg-light d-flex justify-content-center align-items-center">학습단계</div>
                                            <div class="col p-1" ></div>
                                        </div>
                                        <div class="col-6 m-0 p-0 border-bottom row">
                                            <div class="col-4 p-1 bg-light d-flex justify-content-center align-items-center">수강기간</div>
                                            <div class="col p-1 lecture_detail_count_all_day"></div>
                                        </div>
                                        <div class="col-12 m-0 p-0 border-bottom row">
                                            <div class="col-2 p-1 bg-light d-flex justify-content-center align-items-center">강좌구성</div>
                                            <div class="col p-1 text-secondary text-start ps-3 d-flex align-items-center text-start ps-3 lecture_detail_count"></div>
                                        </div>
                                        <div class="col-12 m-0 p-0 border-bottom row">
                                            <div class="col-2 p-1 bg-light d-flex justify-content-center align-items-center">강의교재</div>
                                            <div class="col p-1 text-secondary text-start ps-3 d-flex align-items-center text-start ps-3 book_name"></div>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                        </div>
                    </div>
                    {{-- 요일 선택 --}}
                    <div class="row col-12 border-top">
                        <div class="col-1 p-3">요일 선택</div>
                        <div class="col p-3">
                            <input type="checkbox" class="btn-check " id="timetable_add_check_mon" name="timetable_add_check_week" autocomplete="off" data="월">
                            <label class="btn btn-outline-secondary rounded-circle border" for="timetable_add_check_mon">월</label>

                            <input type="checkbox" class="btn-check " id="timetable_add_check_tue" name="timetable_add_check_week" autocomplete="off" data="화">
                            <label class="btn btn-outline-secondary rounded-circle border" for="timetable_add_check_tue">화</label>

                            <input type="checkbox" class="btn-check " id="timetable_add_check_wed" name="timetable_add_check_week" autocomplete="off" data="수">
                            <label class="btn btn-outline-secondary rounded-circle border" for="timetable_add_check_wed">수</label>

                            <input type="checkbox" class="btn-check " id="timetable_add_check_thu" name="timetable_add_check_week" autocomplete="off" data="목">
                            <label class="btn btn-outline-secondary rounded-circle border" for="timetable_add_check_thu">목</label>

                            <input type="checkbox" class="btn-check " id="timetable_add_check_fri" name="timetable_add_check_week" autocomplete="off" data="금">
                            <label class="btn btn-outline-secondary rounded-circle border" for="timetable_add_check_fri">금</label>

                            <input type="checkbox" class="btn-check " id="timetable_add_check_sat" name="timetable_add_check_week" autocomplete="off" data="토">
                            <label class="btn btn-outline-secondary rounded-circle border" for="timetable_add_check_sat">토</label>

                            <input type="checkbox" class="btn-check " id="timetable_add_check_sun" name="timetable_add_check_week" autocomplete="off" data="일">
                            <label class="btn btn-outline-secondary rounded-circle border" for="timetable_add_check_sun">일</label>
                        </div>
                    </div>
                    {{-- 학습시작일 --}}
                    <div class="row col-12 border-top">
                        <div class="col-1 p-3">학습시작일</div>
                        <div class="col p-3">
                            <input type="date" class="form-control w-auto" value="{{ date('Y-m-d') }}" 
                            id="timetable_add_inp_timetable_start_date">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row gap-2 justify-content-center pb-4">
                <button class="btn btn-outline-secondary col-2" onclick="timetableAddLectureInsertClose();"> 강좌목록으로 돌아가기 </button>
                <button class="btn btn-outline-danger col-2" onclick="timetableAddLectureInsertClear(true);" > 초기화 </button>
                <button class="btn btn-outline-primary col-2" onclick="timetableAddLectureInsert();"> 강좌 목록에 추가 </button>
            </div>
        </div>
        </div>

        {{-- 모달 /  선택 강의 선택 리스트--}}
        <div class="modal fade" id="timetable_add_modal_sel_lecture" tabindex="-1" aria-hidden="true" 
        style="display: none;background:#00000096;--bs-modal-width:700px;">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">
                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" hidden></span>
                            선택 강의</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            onclick=""></button>
                    </div>
                    <div class="modal-body">
                        {{-- 묶음 div_번들 --}}
                        <div class="div_bundle">
                            <div class="row copy_div_unit p-0 m-0" hidden>
                                <input type="hidden" class="lecture_seq">
                                <input type="hidden" class="lecture_detail_count">
                                <input type="hidden" class="lecture_detail_count_all_day">
                                <input type="hidden" class="grade_name">
                                <input type="hidden" class="level_name">

                                <div class="col-auto p-0 align-items-center row px-3" onclick="event.stopPropagation();this.querySelector('input').click();">
                                    <input type="radio" name="radio_bundle_lecture" class="" autocomplete="off" code_seq="1" onclick="event.stopPropagation();"">
                                </div>
                                <div class="col-5">
                                    <div class=" text-center border p-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-book" viewBox="0 0 20 20">
                                            <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z"></path>
                                        </svg>
                                        <span class="subject_name">#영어</span>
                                    </div>
                                    <img src="" alt="" class="w-100 border file_path">
                                </div>
                                <div class="col">
                                        <div class="col-12 row p-0 m-0 gap-1 mb-2">
                                            <button type="button" class="col-auto btn btn-outline-secondary btn-sm">다운로드</button>
                                            <button type="button" class="col-auto btn btn-outline-secondary btn-sm">자막</button>
                                            <button type="button" class="col-auto btn btn-outline-secondary btn-sm">MP4</button>
                                            <button type="button" class="col-auto btn btn-outline-secondary btn-sm">무료</button>
                                            <button type="button" class="col-auto btn btn-outline-secondary btn-sm">교재</button>
                                            <button type="button" class="col-auto btn btn-outline-secondary btn-sm">eBook</button>
                                        </div>
                                        <div class="col-12">
                                            <h5 class="lecture_name">#EBS 기초 영문법 2</h5>
                                        </div>
                                        <div class="col-12">
                                            <span class="teacher_name">(#EBS 기초 영문법)</span>
                                        </div>
                                        <div class="col-12 mb-2 mt-2">
                                            교재 : <span class="book_name">#EBS 기초 영문법2</span>
                                        </div>
                                        <div class="col-12">
                                            <button type="button" class="btn btn-sm btn-outline-secondary">교재 미리보기</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary">종이책 구입</button>
                                            <button type="button" class="btn btn-sm btn-outline-secondary">eBook 구입</button>
                                        </div>
                                </div>
                            </div> 
                        </div>
                        {{-- 검색된 강의가 없습니다. --}}
                        <div class="text-center mt-4 mb-5" id="div_empty" hidden>
                            <p class="fs-6 text-muted"> 검색된 강의가 없습니다. </p>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                            onclick="">닫기</button>
                        <button type="button" class="btn btn-primary" onclick="timetableAddModalSelect(); ">선택</button>
                    </div>
                </div>
            </div>
        </div>
        

</div>

<script>
    const timetable_add_div_main = document.querySelector('#timetable_add_div_main');
    // 시간표 편집으로 들어왔을 경우
    function timetableAddEditSetting(timetable_group_seq, timetable_group_title) {
        document.querySelector('#timetable_add_inp_timetable_group_seq').value = timetable_group_seq;
        document.querySelector('#timetable_add_inp_timetable_group_title').value = timetable_group_title;

        timetableAddSelect();
        document.querySelector('.btn_group_insert').innerText = '샘플/제목/학년/편집';
    }

    // 시간표 등록 클리어
    function timetableAddClear() {
        document.querySelector('#timetable_add_inp_timetable_group_seq').value = '';
        document.querySelector('#timetable_add_inp_timetable_group_title').value = '';
        document.querySelector('.btn_group_insert').innerText = '샘플/제목/학년/등록';

        //테이블 초기화
    }

    // 시간표 그룹 먼저 등록.
    function timetableAddGroupInsert() {
        const main_div = timetable_add_div_main;
        let timetable_group_seq = main_div.querySelector('.timetable_group_seq').value;
        const timetable_group_title = main_div.querySelector('.timetable_group_title').value;
        const grade_code = main_div.querySelector('.grade_code').value;

        // 제목 없을시 리턴
        if (timetable_group_title == '') {
            sAlert('', '제목을 입력해주세요.');
            return;
        }
        let msg = '등록';
        if(timetable_group_seq != ''){
            msg = '편집';
        }

        sAlert('', 'Sample 시간표의 제목과 학년을 ' + msg + '하시겠습니까?', 2, function() {
            const page = "/manage/timetable/group/insert";
            const parameter = {
                timetable_group_seq: timetable_group_seq,
                timetable_group_title: timetable_group_title,
                grade_code: grade_code,
            };
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    const timetable_group_seq = result.timetable_group_seq;
                    main_div.querySelector('.timetable_group_seq').value = timetable_group_seq;
    
                    //시간표안에 강좌 불러오기.
                    timetableAddSelect();
                }
            });
        });
    }

    // 시간표 내 강좌 목록 불러오기.
    function timetableAddSelect() {
        const main_div = timetable_add_div_main;
        const timetable_group_seq = main_div.querySelector('.timetable_group_seq').value;

        const page = "/manage/timetable/select";
        const parameter = {
            timetable_group_seq: timetable_group_seq,
        };
        //초기화
        main_div.querySelector('.div_timetables').hidden = false;
        const tby_list = main_div.querySelector('#timetable_add_tby_list');
        const copy_tr_list = main_div.querySelector('.copy_tr_list').cloneNode(true);
        tby_list.innerHTML = '';
        tby_list.appendChild(copy_tr_list);
        copy_tr_list.hidden = false;

        queryFetch(page, parameter, function(result) {
            copy_tr_list.hidden = true;
            if ((result.resultCode || '') == 'success') {
                const timetables = result.timetables;
                if (timetables.length > 0) {
                    for (const timetable of timetables) {
                        const tr = copy_tr_list.cloneNode(true);
                        tr.hidden = false;
                        // 로딩 모두 삭제
                        tr.querySelectorAll('.loding_place').forEach(function(vthis){
                            vthis.remove();
                        });
                        // 숨김 div 모두 보이기
                        tr.querySelectorAll('.div_hidden').forEach(function(vthis){
                            vthis.hidden = false;
                        });
                        tr.querySelector('.lecture_name').innerText = timetable.lecture_name;
                        tr.querySelector('.timetable_start_date').innerText = (timetable.timetable_start_date||'').substr(0,10);
                        tr.querySelector('.start_lecture_detail').innerHTML = '';
                        tr.querySelector('.start_lecture_detail').setAttribute('data', timetable.start_lecture_detail_seq||'');

                        const option = document.createElement('option');
                        option.value = timetable.start_lecture_detail_seq;
                        const idx = timetable.idx;
                        const before_txt = idx >= 2 ? (idx-1) + '강.':'';
                        option.innerText = before_txt+timetable.lecture_detail_name;

                        tr.querySelector('.start_lecture_detail').appendChild(option);

                        const timetable_days = timetable.timetable_days.split(',');
                        for (const timetable_day of timetable_days) {
                            tr.querySelector('.div_timetable_days').querySelector(`span[data=${timetable_day}]`).classList.add('text-primary');
                        }
                        tr.querySelector('.lecture_seq').value = timetable.lecture_seq;
                        tr.querySelector('.timetable_seq').value = timetable.id;
                        tr.querySelector('.timetable_group_seq').value = timetable.timetable_group_seq;
                        
                        tby_list.appendChild(tr);
                    }
                } else {
                    main_div.querySelector('#timetable_add_div_empty').hidden = false;
                }
            }
        })
    }

    // 시간표 등록
    function timetableAddInsert() {

    }

    // 시간표 등록 > 목록으로 돌아가기.
    function timetableAddClose() {
        // 초기화 후
        timetableAddClear();
        // 목록으로 돌아가기
        document.querySelector('#timetable_div_add').hidden = true;
    }

    // 시간표 등록 > 강좌 추가
    function timetableAddLectureInsertOpen(){
        document.querySelector('#timetable_add_div_lecture_insert').hidden = false;
    }

    // 시간표 등록 > 강좌 추가 > 목록으로 돌아가기
    function timetableAddLectureInsertClose(){
        //초기화 후
        timetableAddLectureInsertClear();

        //목록으로 돌아가기
        document.querySelector('#timetable_add_div_lecture_insert').hidden = true;
    }

    // 시간표 등록 > 강좌 추가 > 강좌 초기화
    function timetableAddLectureInsertClear(is_alert){
        if(is_alert){
            sAlert('', '강좌를 초기화 하시겠습니까?', 2, function(){
                timetableAddLectureInsertClear();
            });
            return;
        }
        const func_main_div = document.querySelector('#timetable_add_div_lecture_insert');
        //func_main_div의 모든 라디오, 체크박스 해제
        func_main_div.querySelectorAll('input[type=radio]').forEach(function(vthis){
            vthis.checked = false;
        });
        func_main_div.querySelectorAll('input[type=checkbox]').forEach(function(vthis){
            vthis.checked = false;
        });
        func_main_div.querySelectorAll('select').forEach(function(vthis){
            vthis.selectedIndex = 0;
        });
        func_main_div.querySelector('input[type=search]').value = '';

        // 선택강의 초기화
        const div_lectinfo = document.querySelector('#timetable_add_div_lecture_info');
        div_lectinfo.hidden = true;
        div_lectinfo.querySelector('.lecture_seq').value = '';
        div_lectinfo.querySelector('.lecture_name').value = '';
        div_lectinfo.querySelector('.teacher_name').innerText = '';
        div_lectinfo.querySelector('.subject_name').innerText = '';
        div_lectinfo.querySelector('.book_name').innerText = '';
        div_lectinfo.querySelector('.lecture_detail_count').innerText = '';
        div_lectinfo.querySelector('.grade_name').innerText = '';
        div_lectinfo.querySelector('.level_name').innerText = '';
        div_lectinfo.querySelector('.file_path').src = '';

    }
    // 옵션에 해당하는 강좌 가져오기. // 강좌 디테일도 같이 가져온다.
    function timetableAddLectureSelect(){
        const modal = document.querySelector('#timetable_add_modal_sel_lecture');
        const func_main_div = document.querySelector('#timetable_add_div_lecture_insert');
        const subject_len = func_main_div.querySelectorAll('input[name=radio_subject]:checked').length
        const subject_seq = subject_len > 0 ? func_main_div.querySelector('input[name=radio_subject]:checked').getAttribute('code_seq') : '';
        const series_len = func_main_div.querySelectorAll('input[name=radio_series]:checked').length
        const series_seq = series_len > 0 ? func_main_div.querySelector('input[name=radio_series]:checked').getAttribute('code_seq') : '';
        const publisher_len = func_main_div.querySelectorAll('input[name=radio_publisher]:checked').length
        const publisher_seq = publisher_len > 0 ? func_main_div.querySelector('input[name=radio_publisher]:checked').getAttribute('code_seq') : '';
        
        const search_str = func_main_div.querySelector('input[type=search]').value;
        const grade_seq = document.querySelector('#timetable_add_grade_code').value;
        const course_seq = document.querySelector('#timetable_add_sel_course_seq').value;

        const page = '/manage/timetable/lecture/select';
        const parameter = {
            grade_seq : grade_seq,
            subject_seq : subject_seq,
            series_seq : series_seq,
            publisher_seq : publisher_seq,
            course_seq : course_seq,
            search_str : search_str
        };
        //모달 로딩 표시
        modal.querySelector('.spinner-border').hidden = false;
        queryFetch(page, parameter, function(result){
            // 모달 로딩 표시 숨김
            modal.querySelector('.spinner-border').hidden = true;
            if((result.resultCode||'') == 'success'){
                //초기화
                const div_bundle = modal.querySelector('.div_bundle');
                const copy_div_unit = modal.querySelector('.copy_div_unit').cloneNode(true);
                div_bundle.innerHTML = '';
                div_bundle.appendChild(copy_div_unit);
                copy_div_unit.hidden = true;

                const lectures = result.lectures;
                for(const lecture of lectures){
                    const div_unit = copy_div_unit.cloneNode(true);
                    div_unit.classList.remove('copy_div_unit');
                    div_unit.classList.add('div_unit');
                    div_unit.hidden = false;
                    div_unit.querySelector('.subject_name').innerText = lecture.subject_name;
                    div_unit.querySelector('.book_name').innerText = lecture.book_name;
                    div_unit.querySelector('.teacher_name').innerText = lecture.teacher_name;
                    div_unit.querySelector('.lecture_name').innerText = lecture.lecture_name;                    
                    div_unit.querySelector('.lecture_seq').value = lecture.id; 
                    div_unit.querySelector('.lecture_detail_count').value = lecture.lecture_detail_count;
                    div_unit.querySelector('.lecture_detail_count_all_day').value = lecture.lecture_detail_count_all_day;
                    div_unit.querySelector('.grade_name').value = lecture.grade_name;
                    div_unit.querySelector('.level_name').value = lecture.level_name;

                    if((lecture.file_path||'') != '') div_unit.querySelector('.file_path').src = '/storage/'+lecture.file_path;
                    div_bundle.appendChild(div_unit);
                }
                if(lectures.length == 0){
                    modal.querySelector('#div_empty').hidden = false;
                }else{
                    modal.querySelector('#div_empty').hidden = true;
                }
            }
        });
    }

    // 선택 강의 
    function timetableAddLectureSelectModal(){
        // 과목, 시리즈, 출판사 중 하나라도 선택이 되어 있지 않으면 리턴
        const func_main_div = document.querySelector('#timetable_add_div_lecture_insert');
        const subject_len = func_main_div.querySelectorAll('input[name=radio_subject]:checked').length
        const series_len = func_main_div.querySelectorAll('input[name=radio_series]:checked').length
        const publisher_len = func_main_div.querySelectorAll('input[name=radio_publisher]:checked').length
        if(subject_len == 0 || series_len == 0 || publisher_len == 0){
            sAlert('', '과목, 시리즈, 출판사를 모두 선택해주세요.');
            return;
        }

        timetableAddLectureSelect();
        // 모달 열기
        const myModal = new bootstrap.Modal(document.getElementById('timetable_add_modal_sel_lecture'), {});
        myModal.show();
    }

    // 모달에서 선택했을 선택 강의
    function timetableAddModalSelect(){
        const modal = document.querySelector('#timetable_add_modal_sel_lecture');
        // 라디오 선택이 있는지 확인 없으면 리턴
        const div_unit_len = modal.querySelectorAll('.div_unit').length;
        if(div_unit_len == 0){
            sAlert('', '선택된 강의가 없습니다.');
            return;
        }

        // 
        const sel_unit = modal.querySelector('.div_unit input[name=radio_bundle_lecture]:checked').closest('.div_unit');
        const div_lectinfo = document.querySelector('#timetable_add_div_lecture_info');
        const lecture_seq = sel_unit.querySelector('.lecture_seq').value;
        div_lectinfo.hidden = false;
        div_lectinfo.querySelector('.lecture_seq').value = lecture_seq;
        div_lectinfo.querySelector('.lecture_name').value = sel_unit.querySelector('.lecture_name').innerText;
        div_lectinfo.querySelector('.subject_name').innerText = sel_unit.querySelector('.subject_name').innerText;
        div_lectinfo.querySelector('.book_name').innerText = sel_unit.querySelector('.book_name').innerText;
        div_lectinfo.querySelector('.teacher_name').innerText = sel_unit.querySelector('.teacher_name').innerText;
        div_lectinfo.querySelector('.file_path').src = sel_unit.querySelector('.file_path').src;
        div_lectinfo.querySelector('.lecture_detail_count').innerText = '총' + sel_unit.querySelector('.lecture_detail_count').value + '강 (완강)';
        div_lectinfo.querySelector('.lecture_detail_count_all_day').innerText = sel_unit.querySelector('.lecture_detail_count_all_day').value + '일';
        div_lectinfo.querySelector('.grade_name').innerText = sel_unit.querySelector('.grade_name').value;
        div_lectinfo.querySelector('.level_name').innerText = sel_unit.querySelector('.level_name').value;

        // 모달 닫기
        modal.querySelector('.btn-close').click();
        timetableAddLectureDetailSelect(lecture_seq, div_lectinfo.querySelector('.start_lecture_detail'));
    }

    // 선택 강의의 강좌 디테일 가져오기
    function timetableAddLectureDetailSelect(lecture_seq, target_tag, sel_value){
        // start_lecture_detail
        const page = '/manage/timetable/lecture/detail/select';
        const parameter = {
            lecture_seq : lecture_seq
        };
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                const lecture_details = result.lecture_details;
                const select_start_lecture_detail = target_tag;
                select_start_lecture_detail.innerHTML = '';
                for(const start_lecture_detail of lecture_details){
                    const option = document.createElement('option');
                    const idx = start_lecture_detail.idx;
                    option.value = start_lecture_detail.id;
                    const before_txt = idx >= 2 ? (idx-1) + '강.':'';
                    option.innerText = before_txt + start_lecture_detail.lecture_detail_name;
                    select_start_lecture_detail.appendChild(option);
                }
                if(sel_value != undefined) select_start_lecture_detail.value = sel_value;
            }
        });
    }

    // 강좌 목록에 추가 / 저장
    function timetableAddLectureInsert(){
        const div_lectinfo = document.querySelector('#timetable_add_div_lecture_info');
        const func_main_div = document.querySelector('#timetable_add_div_lecture_insert');
        const subject_len = func_main_div.querySelectorAll('input[name=radio_subject]:checked').length
        const series_len = func_main_div.querySelectorAll('input[name=radio_series]:checked').length
        const publisher_len = func_main_div.querySelectorAll('input[name=radio_publisher]:checked').length

        //과목, 시리즈, 출판사
        const subject_seq = subject_len > 0 ? func_main_div.querySelector('input[name=radio_subject]:checked').getAttribute('code_seq') : '';
        const series_seq = series_len > 0 ? func_main_div.querySelector('input[name=radio_series]:checked').getAttribute('code_seq') : '';
        const publisher_seq = publisher_len > 0 ? func_main_div.querySelector('input[name=radio_publisher]:checked').getAttribute('code_seq') : '';

        //선택강의
        const lecture_seq = div_lectinfo.querySelector('.lecture_seq').value;
        const lecture_name = div_lectinfo.querySelector('.lecture_name').value;
        const start_lecture_detail_seq = div_lectinfo.querySelector('.start_lecture_detail').value;
        const timetable_days = [];
        func_main_div.querySelectorAll('input[name=timetable_add_check_week]:checked').forEach(function(vthis){
            timetable_days.push(vthis.getAttribute('data'));
        });
        
        //학습시작일
        const timetable_start_date = document.querySelector('#timetable_add_inp_timetable_start_date').value;

        // 값이 없으면 각각 문구를 띄워주고 리턴.
        if(subject_seq == ''){ sAlert('', '과목을 선택해주세요.'); return; }
        if(series_seq == ''){ sAlert('', '시리즈를 선택해주세요.'); return; }
        if(publisher_seq == ''){ sAlert('', '출판사를 선택해주세요.'); return; }
        if(lecture_seq == ''){ sAlert('', '강의를 선택해주세요.'); return; }
        if(start_lecture_detail_seq == ''){ sAlert('', '시작강의를 선택해주세요.'); return; }
        if(timetable_days.length == 0){ sAlert('', '요일을 선택해주세요.'); return; }
        if(timetable_start_date == ''){ sAlert('', '학습시작일을 선택해주세요.'); return; }

        // Sameple timetable group seq
        const timetable_group_seq = document.querySelector('#timetable_add_inp_timetable_group_seq').value;

        // 전송
        const page = '/manage/timetable/insert';
        const parameter = {
            timetable_group_seq : timetable_group_seq,
            subject_seq : subject_seq,
            series_seq : series_seq,
            publisher_seq : publisher_seq,
            lecture_seq : lecture_seq,
            lecture_name : lecture_name,
            start_lecture_detail_seq : start_lecture_detail_seq,
            timetable_days : timetable_days.join(','),
            timetable_start_date : timetable_start_date
        };

        //강좌 목록에 현재 설정한 갈좌를 추가하시겠습니까?
        sAlert('', '강좌 목록에 현재 설정한 갈좌를 추가하시겠습니까?', 2, function(){
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    sAlert('저장완료', '강좌가 추가되었습니다.', 1, function(){
                        // timetableAddLectureInsertClear();
                        timetableAddLectureInsertClose();
                        timetableAddSelect();
                    });
                }
            });
        });
    }

    // 시간표 목록 > 강좌 목록 삭제
    function timetableAddLectureDelete(vthis){
        const tr = vthis.closest('tr');
        const timetable_seq = tr.querySelector('.timetable_seq').value;
        const timetable_group_seq = tr.querySelector('.timetable_group_seq').value;

        // 전송
        const page = '/manage/timetable/delete';
        const parameter = {
            timetable_seq : timetable_seq,
            timetable_group_seq : timetable_group_seq
        };

        // 선택강좌를 정말로 목록에서 삭제하시겠습니까?
        sAlert('', '선택강좌를 정말로 목록에서 삭제하시겠습니까?', 2, function(){
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    sAlert('삭제완료', '강좌가 삭제되었습니다.');
                    timetableAddSelect();
                }
            });
        });
    }

    // 강좌분류, 과목, 시리즈, 출산사 변경시, 선택한 강의가 선택되어 있을경우 선택한 강의만 초기화
    function timetableAddChkOptionForLecture(){
        // 선택강의 초기화
        const div_lectinfo = document.querySelector('#timetable_add_div_lecture_info');
        div_lectinfo.hidden = true;
        const lecture_seq = div_lectinfo.querySelector('.lecture_seq').value;
        if(lecture_seq != ''){
            div_lectinfo.querySelector('.lecture_seq').value = '';
            div_lectinfo.querySelector('.lecture_name').value = '';
            div_lectinfo.querySelector('.teacher_name').innerText = '';
            div_lectinfo.querySelector('.subject_name').innerText = '';
            div_lectinfo.querySelector('.book_name').innerText = '';
            div_lectinfo.querySelector('.lecture_detail_count').innerText = '';
            div_lectinfo.querySelector('.grade_name').innerText = '';
            div_lectinfo.querySelector('.level_name').innerText = '';
            div_lectinfo.querySelector('.file_path').src = '';
    
            toast('선택한 강의가 취소 되었습니다. 다시 선택해주세요.');
        }
    }
</script>
