@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
강좌 목록
@endsection

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<style>
[data-bundle="exam_list"] tr.active td,
[data-bundle="inter_list"] tr.active td
{
    background-color: #e9ecef;
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<div class="col-12 pe-3 ps-3 position-relative">
    <div id="lecture_add_div_wr_form">
        <input type="hidden" id="lecture_add_inp_seq">
        <div class="row flex-wrap w-100">
            {{-- 분류 / radio --}}
            <div class="col-6 row div_course ">
                <div
                    class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">
                    분류</div>
                <div class="p-2 text-center col border">
                    @if (!empty($course_codes))
                    @foreach ($course_codes as $course_code)
                    <input type="radio" name="board_radio_course" class="btn-check subject"
                        id="board_radio_lecture_{{ $course_code->id }}" autocomplete="off"
                        code_seq="{{ $course_code->id }}">
                    <label class="btn btn-outline-primary hpx-40 rounded-0"
                        for="board_radio_lecture_{{ $course_code->id }}">
                        {{ $course_code->code_name }}
                    </label>
                    @endforeach
                    @endif
                </div>
            </div>
            {{-- 학년 /checkbox --}}
            <div class="col-6 row div_grade">
                <div
                    class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">
                    학년 (중복)</div>
                <div class="p-2 text-center col border">
                    @if (!empty($grade_codes))
                    @foreach ($grade_codes as $grade_code)
                    <input type="checkbox" name="board_chk_grade" class="btn-check subject"
                        id="board_checkbox_lecture_{{ $grade_code->id }}" autocomplete="off"
                        code_seq="{{ $grade_code->id }}" onclick="lectureAddSeiresSelect()">
                    <label class="btn btn-outline-success hpx-40 rounded-0"
                        for="board_checkbox_lecture_{{ $grade_code->id }}">
                        {{ $grade_code->code_name }}
                    </label>
                    @endforeach
                    @endif
                </div>
            </div>
            {{-- 학기 / checkbox --}}
            <div class="col-6 row div_semester ">
                <div
                    class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">
                    학기 (중복)</div>
                <div class="p-2 text-center col border">
                    @if (!empty($semester_codes))
                    @foreach ($semester_codes as $semester_code)
                    <input type="checkbox" name="board_chk_semester" class="btn-check subject"
                        id="board_checkbox_lecture_{{ $semester_code->id }}" autocomplete="off"
                        code_seq="{{ $semester_code->id }}">
                    <label class="btn btn-outline-success hpx-40 rounded-0"
                        for="board_checkbox_lecture_{{ $semester_code->id }}">
                        {{ $semester_code->code_name }}
                    </label>
                    @endforeach
                    @endif
                </div>
            </div>


            {{-- 과목  / radio --}}
            <div class="col-6 row div_subject ">
                <div
                    class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">
                    과목</div>
                <div class="p-2 text-center col border">
                    {{-- Undefined variable: subject_codes 체크 피할려면 --}}
                    @if (!empty($subject_codes))
                    @foreach ($subject_codes as $subject_code)
                    <input type="radio" name="board_radio_subject" class="btn-check subject"
                        id="board_radio_lecture_{{ $subject_code->id }}" autocomplete="off"
                        code_seq="{{ $subject_code->id }}" code_pt="{{ $subject_code->code_pt }}"
                        onclick="lectureAddSeiresSelect()">
                    <label class="btn btn-outline-primary hpx-40 rounded-0"
                        for="board_radio_lecture_{{ $subject_code->id }}">
                        {{ $subject_code->code_name }}
                    </label>
                    @endforeach
                    @endif
                </div>
            </div>
            {{-- 시리즈 / radio / where code_step = 1 --}}
            <div class="col-6 row div_series ">
                <div
                    class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">
                    시리즈
                    <button class="btn btn-sm btn-outline-secondary series_in ms-1" onclick="lectureAddSeriesInsertModal();">추가</button>
                </div>
                <div class="p-2 text-center col border">
                    <div class="div_series_in col">
                        @if (!empty($series_codes->where('code_step', 1)))
                        @foreach ($series_codes->where('code_step', 1) as $series_code)
                        <input type="radio" name="board_radio_series" class="btn-check subject"
                            id="board_radio_lecture_{{ $series_code->id }}" autocomplete="off"
                            code_seq="{{ $series_code->id }}" onclick="lectureAddSeiresNextSelect(this)">
                        <label class="btn btn-outline-primary hpx-40 rounded-0"
                            for="board_radio_lecture_{{ $series_code->id }}" hidden>
                            {{ $series_code->code_name }}
                        </label>
                        @endforeach
                        @endif
                    </div>
                    <div class="alert alert-warning p-2 mb-0 div_toast" role="alert">
                        학년, 과목을 먼저 선택해주세요.
                    </div>
                </div>
            </div>
            {{-- 시리즈 하위 / radio / where code_step = 2 --}}
            <div class="col-6 row div_series_sub ">
                <div
                    class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">
                    강좌명(시리즈 하위)
                    <button class="btn btn-sm btn-outline-secondary series_sub_in ms-1" onclick="lectureAddSeriesSubInsertModal();">추가</button>
                </div>
                <div class="p-2 text-center col border">
                    {{-- Undefined variable: series_codes 체크 피할려면 --}}
                    <div class="div_series_sub_in col">
                        @if (!empty($series_codes->where('code_step', 2)))
                        @foreach ($series_codes->where('code_step', 2) as $series_code)
                        <input type="radio" name="board_radio_series_sub" class="btn-check subject"
                            id="board_radio_lecture_{{ $series_code->id }}" autocomplete="off"
                            code_seq="{{ $series_code->id }}" code_pt="{{ $series_code->code_pt }}">
                        <label class="btn btn-outline-primary hpx-40 rounded-0"
                            for="board_radio_lecture_{{ $series_code->id }}" hidden>
                            {{ $series_code->code_name }}
                        </label>
                        @endforeach
                        @endif
                    </div>
                    <div class="alert alert-warning p-2 mb-0 div_toast" role="alert">
                        학년, 과목, 시리즈를 먼저 선택해주세요.
                    </div>
                </div>
            </div>
            {{-- 선생님 / input --}}
            <div class="col-6 row div_teacher ">
                <div
                    class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">
                    선생님</div>
                <div class="p-2 text-center col border">
                    <input type="text" class="form-control" id="lecture_add_inp_teacher" placeholder="선생님">
                </div>
            </div>
            {{-- 강좌수준(중복) / checkbox --}}
            <div class="col-6 row div_level ">
                <div
                    class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">
                    강좌수준(중복)</div>
                <div class="p-2 text-center col border">
                    @if (!empty($level_codes))
                    @foreach ($level_codes as $level_code)
                    <input type="checkbox" name="board_chk_level" class="btn-check subject"
                        id="board_checkbox_lecture_{{ $level_code->id }}" autocomplete="off"
                        code_seq="{{ $level_code->id }}">
                    <label class="btn btn-outline-success hpx-40 rounded-0"
                        for="board_checkbox_lecture_{{ $level_code->id }}">
                        {{ $level_code->code_name }}
                    </label>
                    @endforeach
                    @endif
                </div>
            </div>
            {{-- 수강일 / input 일 --}}
            <div class="col-6 row div_day ">
                <div
                    class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">
                    수강일</div>
                <div class="p-2 text-center col border d-flex align-items-center gap-2">
                    <input type="number" class="form-control" id="lecture_add_inp_day" placeholder="수강일">일
                </div>
            </div>
            {{-- 교재 / checkbox 있음 --}}
            <div class="col-6 row div_book ">
                <div
                    class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">
                    교재</div>
                <div class="p-2 text-center col border">
                    <div class="d-flex gap-2">
                        {{-- checkbox / 있음 --}}
                        <input type="checkbox" name="board_chk_book" class="btn-check subject"
                            id="board_checkbox_lecture_book" autocomplete="off" onchange="lectureAddBookCheck(this);">
                        <label class="btn btn-outline-primary hpx-40 rounded-0 col-auto"
                            for="board_checkbox_lecture_book">
                            있음
                        </label>
                        {{-- 교재명을 입력해주세요. --}}
                        <input type="text" class="form-control" id="lecture_add_inp_book" placeholder="교재명을 입력해주세요." disabled>
                    </div>
                    <div class="mt-1">
                        {{-- 구매링크 , input --}}
                        <input type="text" class="form-control" id="lecture_add_inp_book_link" placeholder="구매링크" disabled>
                    </div>
                </div>
            </div>
            {{-- 출판사 / radio --}}
            <div class="col-6 row div_publisher ">
                <div
                    class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">
                    출판사</div>
                <div class="p-2 text-center col border">
                    @if (!empty($publisher_codes))
                    @foreach ($publisher_codes as $publisher_code)
                    <input type="radio" name="board_radio_publisher" class="btn-check subject"
                        id="board_radio_lecture_{{ $publisher_code->id }}" autocomplete="off"
                        code_seq="{{ $publisher_code->id }}">
                    <label class="btn btn-outline-primary hpx-40 rounded-0"
                        for="board_radio_lecture_{{ $publisher_code->id }}">
                        {{ $publisher_code->code_name }}
                    </label>
                    @endforeach
                    @endif
                </div>
            </div>
            {{-- 사용활성화 / checkbox --}}
            <div class="col-6 row div_use_yn ">
                <div
                    class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">
                    사용활성화</div>
                <div class="d-flex p-2 text-center col border align-items-center justify-content-center gap-2">
                    <input type="checkbox" name="board_chk_use_yn" class="btn-check subject"
                        id="lecture_checkbox_lecture_use_yn" autocomplete="off">
                    <label class="btn btn-outline-primary hpx-40 rounded-0"
                        for="lecture_checkbox_lecture_use_yn">
                        사용
                    </label>
                    <span>강의리스트, 강의 검색에서도 나타나지 않습니다.</span>
                </div>
            </div>
            {{-- 섬네일 / file input --}}
            <div class="col-6 row div_thumbnail ">
                <div
                    class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">
                    섬네일</div>
                <div class="p-2 text-center col border">
                    <div class="div_number_file input-group">
                        <input type="file" class="academy_number_file form-control ps-2" id="lecture_add_inp_thumbnail" aria-describedby="design_btn_findfile2" aria-label="Upload"
                            onchange="lectureAddThumbnailSetting(this);" accept="image/*" >
                        <button class="btn btn-primary btn-sm" type="button" onclick="document.querySelector('#lecture_add_inp_thumbnail').click();">찾아보기</button>
                    </div>
                    <div class="mt-1">
                        <span class="span_file_name me-2" id="lecture_add_span_thumbnail">선택파일 없음</span>
                        <button class="btn btn-sm btn-outline-secondary" type="button" onclick="lectureAddThumbnailPreview()">미리보기</button>
                        <button class="btn btn-sm btn-outline-danger" type="button" onclick="lectureAddThumbnailDelete(this)"
                            id="lecture_add_btn_thumbnail_delete" hidden>
                            <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true" hidden></span>
                            삭제</button>
                    </div>
                </div>
            </div>
            {{-- 강좌등록 / input--}}
            <div class="col-6 row div_register ">
                <div
                    class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">
                    강좌등록</div>
                <div class="p-2 text-center col border">
                    <input type="text" class="form-control" id="lecture_add_inp_register" placeholder="강좌등록">
                </div>
            </div>
            {{-- 강좌설명 /textarea --}}
            <div class="col-6 row div_content ">
                <div
                    class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">
                    강좌설명</div>
                <div class="p-2 text-center col border">
                    <textarea class="form-control" id="lecture_add_inp_content" rows="3"></textarea>
                </div>
            </div>
            {{-- 총 강의수 / input--}}
            <div class="col-12 row div_total_lecture " style="padding-right: 2.2rem;">
                <div
                    class="bg-light p-2 text-center col-2 border fw-bold d-flex align-items-center justify-content-center">
                    총 강의수</div>
                <div class="d-flex p-2 text-center col border">
                    <div class="d-flex col">
                        <button class="btn btn-outline-primary rounded btn-sm p-0" onclick="lectureAddTotalCount(this);" data="down">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"></path>
                            </svg>
                        </button>
                        <input type="number" class="form-control col text-center" onchange="lectureAddTotalLectureTrSetting();"
                            id="lecture_add_inp_total_lecture" placeholder="숫자">
                        <button class="btn btn-outline-primary rounded btn-sm p-0" onclick="lectureAddTotalCount(this);" data="up">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-up-fill" viewBox="0 0 16 16">
                                <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="d-flex col-11">
                        <span class="col d-flex align-items-center justify-content-center">
                            총 강의수(맛보기영상, OT 영상 포함한 수)를 입력하면 강의명, 강의설명, 강의시간들 입력하는 창이 뜹니다.
                        </span>
                        {{-- 아래 펼치기위한 아래화살표 버튼 추가--}}
                        <button class="btn btn-primary rounded btn-sm btn_total_open" onclick="lectureAddTotalLectureOpen();" data="open">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16">
                                <path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"></path>
                            </svg>
                        </button>
                        {{-- 닫기 위한 위 화살표 버튼 추가 --}}
                        <button class="btn btn-primary rounded btn-sm btn_total_close" onclick="lectureAddTotalLectureClose();" data="close" hidden>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-up-fill" viewBox="0 0 16 16">
                                <path d="m7.247 4.86-4.796 5.481c-.566.647-.106 1.659.753 1.659h9.592a1 1 0 0 0 .753-1.659l-4.796-5.48a1 1 0 0 0-1.506 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-12 row div_total_lecture " style="padding-right: 2.2rem;">
                <div class="d-flex justify-content-start p-2">
                    <button class="btn btn-outline-success" onclick="lectureAddExamBatchInsert()">문제풀기 일괄등록</button>
                </div>
            </div>
            {{-- 강의수 detail col-12 --}}
            <div class="col-12 ps-0 div_total_lecture_detail" style="padding-right: 2.9rem" hidden>
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">순서</th>
                            {{-- 강의설명 => 단원명 수정 2024-09-04  --}}
                            <th scope="col">단원명</th>
                            <th scope="col">강의명
                                <button class="btn btn-outline-primary btn-sm" onclick="lectureAddExamInsert(this);">문제일괄등록</button>
                            </th>
                            <th scope="col">타입</th>
                            <th scope="col">강의시간(분:초)</th>
                            <th scope="col" hidden>강의일수</th>
                            <th scope="col">링크연결(영상,파일,문제연결)</th>
                            <th scope="col">사용</th>
                            <th scope="col">+</th>
                        </tr>
                    </thead>
                    <tbody id="lecture_add_tby_total_lecture_top" class="align-middle">
                    </tbody>
                    <tbody id="lecture_add_tby_total_lecture" class="align-middle">
                        <tr class="copy_tr_total_lecture" hidden>
                            <td class="idx" data="#순서" rowspan="1"></td>
                            <td data="#강의설명">
                                <input type="text" class="form-control lecture_detail_description" placeholder="강의설명" onblur="lectureAddTrChk(this)">
                            </td>
                            <td data="#강의명">
                                <input type="text" class="form-control lecture_detail_name" placeholder="강의명" onblur="lectureAddTrChk(this)">
                            </td>
                            <td>
                                <span class="lecture_detail_type_str">준비하기</span>
                                <input type="hidden" class="lecture_detail_type" value="">
                            </td>
                            <td data="#강의시간">
                                <input type="number" min="0" max="23" onchange="maxMinHour(this)" class="lecture_detail_hour" onblur="lectureAddTrChk(this)">
                                <span class="colon">:</span>
                                <input type="number" min="0" max="59" onchange="maxMinTime(this)" class="lecture_detail_minute" onblur="lectureAddTrChk(this)">
                                <button class="btn btn-outline-success" data-btn-exam-connect data-exam-seq onclick="lectureAddExamConnectCancel(this)" hidden>문제끊기</button>
                                <div class="mt-2" hidden>
                                    <button class="btn btn-outline-success" data-btn-interactive-connect data-interactive-seq onclick="lectureAddInterConnectCancel(this)" >인터끊기</button>
                                    <!-- 인터렉트 먼저 -->
                                    <input type="checkbox" data-is-first-interactive onchange="toast('체크시 인터렉티브 먼저 나오게 됩니다.')">
                                </div>

                            </td>
                            {{-- 강의일수 input number --}}
                            <td data="#강의일수" hidden>
                                <input type="number" class="lecture_detail_count_day" placeholder="강의일수" onblur="lectureAddTrChk(this)" style="width: 100px">
                            </td>
                            <td data="#파일 업로드">
                                {{-- 파일 업로드 태그 추가 --}}
                                <div class="div_number_file input-group">
                                    <button data-btn-upload
                                        class="btn btn-outline-primary" onclick="lectureAddUpload(this)">업로드</button>
                                    <input type="text" class="academy_number_file form-control ps-2 lecture_add_inp_link"
                                        onchange="lectureAddTrChk(this);" placeholder="https:// 까지 URL을 삽입해주세요.">
                                    <button class="btn btn-primary btn-sm" type="button" onclick="lecturePreview(this);">
                                        미리보기</button>
                                </div>
                                <input type="file" class="academy_number_file form-control ps-2 lecture_add_inp_file"  aria-label="Upload"
                                    onchange="lectureAddUploadLinkChange(this)" accept="video/*,application/pdf" hidden>

                                {{--  인터렉티브 추가분 --}}
                                <div class="div_number_file input-group mt-2" hidden>
                                    <button data-btn-interactive
                                         class="btn btn-outline-primary" onclick="lectureAddInterModalShow(this)">INACT</button>
                                    <input type="text" class="academy_number_file form-control ps-2 inp_interactive_title" placeholder="인터렉티브 TITLE" disabled >
                                    <button class="btn btn-primary btn-sm" type="button" onclick="lectureInteractivePreview(this)">
                                        미리보기</button>
                                </div>
                            </td>
                            <td data="#사용">
                                <input type="checkbox" name="lecture_chk_use_yn" class="btn-check lecture_checkbox_use_yn"
                                    id="lecture_checkbox_use_yn" autocomplete="off">
                                <label class="btn btn-outline-primary hpx-40 rounded-0"
                                    for="lecture_checkbox_use_yn">
                                    사용
                                </label>
                            </td>
                            <td data="#추가">
                                <button class="btn btn-primary btn_add_type" onclick="lectureAddTypeModalShow(this);">+</button>
                            </td>
                            <input type="hidden" class="lecture_detail_seq">
                            <input type="hidden" class="lecture_detail_group" data-num="0" value="0">
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
        <div class="row justify-content-center mt-3 gap-2">
            <button class="btn btn-outline-secondary col-1" onclick="lectureAddCancel()"
                id="lecture_add_btn_cancel" >취소</button>
            <button class="btn btn-danger col-1" onclick="lectureAddDelete(this)"
                id="lecture_add_btn_delete" hidden disabled>
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true" hidden></span>
                삭제</button>
            <button class="btn btn-primary col-1" onclick="lectureAddInsert(this)">
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true" hidden></span>
                저장</button>

        </div>
    </div>
    {{-- 모달 / 섬네일 미리보기 --}}
    <div class="modal fade" id="lecutre_add_modal_thumbnail_preview" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">이미지 미리보기</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick=""></button>
                </div>
                <div class="modal-body">
                    <img src="" alt="" id="lecture_add_modal_thumbnail_preview_img" style="width: 100%;" hidden>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                        onclick="">닫기</button>
                    {{-- <button type="button" class="btn btn-primary" onclick=";">저장</button> --}}
                </div>
            </div>
        </div>
    </div>

    {{-- 모달 / 시리즈 추가  --}}
    <div class="modal fade" id="lecture_modal_series_insert" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5"> 시리즈 추가 </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick=""></button>
                </div>
                <div class="modal-body">
                    <span>추가할 시리즈 이름을 입력 하세요.</span>
                    <input type="text" class="form-control" id="lecture_modal_series_insert_inp" placeholder="시리즈 이름">
                    <div class="px-2 text-secondary mt-2" style="font-size: 15px; ">
                        같은 시리즈 이름이 있을 경우, 추가는 가능하나 혼동이 올수 있으므로 되도록 같은 이름은 피해주세요.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                        onclick="">닫기</button>
                    <button type="button" class="btn btn-primary" onclick="lectureModalSeriesInsert();">저장</button>
                </div>
            </div>
        </div>
    </div>

    {{-- 모달 / 강좌명(시리즈 하위) 추가 --}}
    <div class="modal fade" id="lecture_modal_series_sub_insert" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">강좌명(시리즈 하위) 추가</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick=""></button>
                </div>
                <div class="modal-body">
                    <span>추가할 강좌명(시리즈 하위) 이름을 입력 하세요.</span>
                    <input type="text" class="form-control" id="lecture_modal_series_sub_insert_inp" placeholder="강좌명(시리즈 하위) 이름">
                    <div class="px-2 text-secondary mt-2" style="font-size: 15px;">
                        같은 강좌명(시리즈 하위) 이름이 있을 경우, 추가는 가능하나, 혼동이 올수 있으므로 되도록 같은 이름은 피해주세요.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                        onclick="">닫기</button>
                    <button type="button" class="btn btn-primary" onclick="lectureModalSeriesSubInsert();">저장</button>
                </div>
            </div>
        </div>
    </div>
{{-- 모달 / 개념, 문제, 정리학습, 단원평가 추가하기. --}}
    <div class="modal fade" id="modal_add_type" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">타입 추가하기</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick=""></button>
                </div>
                <div class="modal-body">
                    <button class="btn btn-primary btn_type1 btn_type" onclick="lectureAddType('concept_building',this)">개념다지기</button>
                    <button class="btn btn-primary btn_type2 btn_type" onclick="lectureAddType('exam_solving',this)">문제풀기</button>
                    <button class="btn btn-primary btn_type3 btn_type" onclick="lectureAddType('summarizing',this)">정리학습</button>
                    <button class="btn btn-primary btn_type4 btn_type" onclick="lectureAddType('unit_test',this)">단원평가</button>
                    <button class="btn btn-primary btn_type5 btn_type" onclick="lectureAddType('5',this)">한번에추가</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                        onclick="">닫기</button>
                </div>
            </div>
        </div>
    </div>

</div>

{{--  모달 / 문제 연결하기 --}}
<div class="modal fade" id="modal_exam_list" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog  modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">연결할 문제를 선택하세요.</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick=""></button>
            </div>
            <div class="modal-body">
                <div class="row mx-0">
                    <input class="col form-control w-50 me-3" placeholder="조회할 제목을 입력해주세요." data-search-title>
                    <div class="col-auto row mx-0 col-auto gap-1 me-3">
                        <select class="col form-select" data-search-subject-code>
                            <option value="">과목전체</option>
                            @if(!empty($subject_codes))
                            @foreach($subject_codes as $subject_code)
                            <option value="{{$subject_code->id}}">{{$subject_code->code_name}}</option>
                            @endforeach
                            @endif
                        </select>
                        <select class="col form-select" data-search-grade-code >
                            <option value="">학년전체</option>
                            @if(!empty($grade_codes))
                            @foreach($grade_codes as $grade_code)
                            <option value="{{$grade_code->id}}">{{$grade_code->code_name}}</option>
                            @endforeach
                            @endif
                        </select>
                        <select class="col form-select" data-search-semester-code >
                            <option value="">학기전체</option>
                            @if(!empty($semester_codes))
                            @foreach($semester_codes as $semester_code)
                            <option value="{{$semester_code->id}}">{{$semester_code->code_name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <button class="col-auto btn btn-primary col-auto me-1" type="submit" onclick="lectureAddExamSelect();">조회</button>
                    <script>
                        document.querySelector('[data-search-title]').addEventListener('keypress', function (e) {
                            if (e.key === 'Enter') {
                                lectureAddExamSelect();
                            }
                        });
                    </script>
                </div>

                <div class="row mx-0 overflow-auto" style="max-height: 60vh;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>과목</th>
                                <th>학년</th>
                                <th>학기</th>
                                <th>제목</th>
                            </tr>
                        </thead>
                        <tbody data-bundle="exam_list">
                            <tr data-row="copy" onclick="lectureAddExamListTrClick(this);" hidden>
                                <input type="hidden" data-exam-seq>
                                <input type="hidden" data-subject-seq>
                                <input type="hidden" data-grade-seq>
                                <input type="hidden" data-semester-seq>

                                <td data-subject-name></td>
                                <td data-grade-name></td>
                                <td data-semester-name></td>
                                <td data-exam-title></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="lectureAddExamConnect();">선택연결</button>
                <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                    onclick="">닫기</button>
            </div>
        </div>
    </div>
</div>

{{--  모달 / 인터렉티브 연결하기 --}}
<div class="modal fade" id="modal_inter_list" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog  modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">연결할 인터렉티브를 선택하세요.</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick=""></button>
            </div>
            <div class="modal-body">
                <div class="row mx-0">
                    <input class="col form-control w-50 me-3" placeholder="조회할 제목을 입력해주세요." data-search-title>
                    <div class="col-auto row mx-0 col-auto gap-1 me-3">
                        <select class="col form-select" data-search-subject-code>
                            <option value="">과목전체</option>
                            @if(!empty($subject_codes))
                            @foreach($subject_codes as $subject_code)
                            <option value="{{$subject_code->id}}">{{$subject_code->code_name}}</option>
                            @endforeach
                            @endif
                        </select>
                        <select class="col form-select" data-search-grade-code >
                            <option value="">학년전체</option>
                            @if(!empty($grade_codes))
                            @foreach($grade_codes as $grade_code)
                            <option value="{{$grade_code->id}}">{{$grade_code->code_name}}</option>
                            @endforeach
                            @endif
                        </select>
                        <select class="col form-select" data-search-semester-code >
                            <option value="">학기전체</option>
                            @if(!empty($semester_codes))
                            @foreach($semester_codes as $semester_code)
                            <option value="{{$semester_code->id}}">{{$semester_code->code_name}}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                    <button class="col-auto btn btn-primary col-auto me-1" onclick="lectureAddInterSelect();">조회</button>
                </div>

                <div class="row mx-0 overflow-auto" style="max-height: 60vh;">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>과목</th>
                                <th>학년</th>
                                <th>학기</th>
                                <th>제목</th>
                            </tr>
                        </thead>
                        <tbody data-bundle="inter_list">
                            <tr data-row="copy" onclick="lectureAddInterListTrClick(this);" hidden>
                                <input type="hidden" data-interactive-seq>
                                <input type="hidden" data-subject-seq>
                                <input type="hidden" data-grade-seq>
                                <input type="hidden" data-semester-seq>

                                <td data-subject-name></td>
                                <td data-grade-name></td>
                                <td data-semester-name></td>
                                <td data-title></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="lectureAddInterConnect();">선택연결</button>
                <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                    onclick="">닫기</button>
            </div>
        </div>
    </div>
</div>
{{-- 모달 / 인터렉티브 미리보기 --}}
<div class="modal fade" id="modal_interactive_preview" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog  modal-dialog-centered modal-lg">
        <div class="modal-content border-none rounded modal-shadow-style rounded-4">
            <div class="modal-header border-bottom-0 primary-bg-light rounded-top-4 position-relative">
                <h1 class="modal-title text-b-24px" id="" >인터렉트 미리보기 </h1>
                <button type="button" class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close" style="width: 28px; height: 28px;"></button>
            </div>
            <div class="modal-body p-4">
                 <!-- 결과  -->
                <div class="text-sb-20px rounded-4">
                    <div id="div_interactive" style="height:clamp(75vh, calc(3.125rem + 4.167vw), 90vh); max-height: 616px;">
                        <iframe id="ifrm_interactive" src="http://interactive.popcorn-edu.com" frameborder="1" style="width: 100%; height:100%;"></iframe>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                    onclick="">닫기</button>
            </div>
        </div>
    </div>
</div>
<!-- HLS CDN 추가 -->
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

<script>

//드래그 앤 드롭
// TODO: 개념, 문제, 정리, 단원 이 추가되면서 드래그 할때, 같이 드래그가 되도록 추가 수정해야함.
$( "#lecture_add_tby_total_lecture" ).sortable({
    update: function(event, ui) {
        document.querySelectorAll("#lecture_add_tby_total_lecture [data-num='0']").forEach((item, idx) => {
            const tr = item.closest("tr");
            tr.querySelector('.idx').innerText = idx;
        });
    }
});
$( "#lecture_add_tby_total_lecture" ).disableSelection();
var connect_seqs = [];
// 학년, 과목 선택시 시리즈 보여줄 seq가져오기.
function lectureAddSeiresSelect() {
    //학년 seq 가져오기.
    let grade_code_seqs = "";
    const grade_codes = document.querySelectorAll("input[name=board_chk_grade]:checked");
    grade_codes.forEach(item => {
        if (grade_code_seqs != "") grade_code_seqs += ",";
        grade_code_seqs += item.getAttribute("code_seq");
    });

    //과목 seq 가져오기.
    let subject_code_seqs = "";
    const subject_codes = document.querySelectorAll("input[name=board_radio_subject]:checked");
    subject_codes.forEach(item => {
        if (subject_code_seqs != "") subject_code_seqs += ",";
        subject_code_seqs += item.getAttribute("code_seq");
    });

    //시리즈 하위 초기화.
    const div_serise_sub = document.querySelector(".div_series_sub");
    div_serise_sub.querySelector(".div_series_sub_in").querySelectorAll("input").forEach(item => {
        item.checked = false;
        item.nextElementSibling.hidden = true;
    });

    //시리즈 가져오기.
    const page = "/manage/lecture/add/code/connect/select";
    const parameter = {
        "grade_code_seqs": grade_code_seqs,
        "subject_code_seqs": subject_code_seqs,
    };
    connect_seqs = [];
    queryFetch(page, parameter, function(result) {
        if ((result.resultCode || '') == 'success') {
            const div_series = document.querySelector(".div_series");
            const series_in = div_series.querySelector(".div_series_in");
            //시리즈 초기화
            series_in.querySelectorAll("input").forEach(item => {
                item.checked = false;
                item.nextElementSibling.hidden = true;
            });
            // 학년, 과목 둘중 하나라도 선택 안되어있으면
            if (grade_code_seqs == "" || subject_code_seqs == "") {
                div_series.querySelector(".div_toast").hidden = false;
                div_serise_sub.querySelector(".div_toast").hidden = false;
            }

            connect_seqs = result.code_connects;
            // 값이 있을때 처리.
            if (result.code_connects.length > 0) {
                div_series.querySelector(".div_toast").hidden = true;
            }
            for (let i = 0; i < result.code_connects.length; i++) {
                const code_connect = result.code_connects[i];
                const series_tags = series_in.querySelectorAll('#board_radio_lecture_' + code_connect
                    .code_seq);
                //존재하는지
                const is_exist = series_tags.length > 0;
                if (is_exist) {
                    series_tags[0].nextElementSibling.hidden = false;
                }
            }
        }
    });
}
//
function lectureAddSeiresNextSelect(vthis) {
    const code_seq = vthis.getAttribute("code_seq");
    const div_serise = document.querySelector(".div_series");
    const div_serise_sub = document.querySelector(".div_series_sub");

    //시리즈 하위 초기화
    div_serise_sub.querySelector(".div_series_sub_in").querySelectorAll("input").forEach(item => {
        item.checked = false;
        item.nextElementSibling.hidden = true;
    });

    //시리즈 안에 input:checked 있는지 확인.
    if (vthis.checked) {
        //connect_seqs에 저장된 code_seq를 배열로 삽입.
        const code_seqs = [];
        connect_seqs.forEach(item => {
            code_seqs.push(item.code_seq);
        });

        //div_toast 숨기기
        div_serise_sub.querySelector(".div_toast").hidden = true;
        //시리즈 선택 하위 메뉴 가져오기. && 학년/과목에 따른 시리즈 하위 메뉴 가져오기.
        div_serise_sub.querySelectorAll('input[name=board_radio_series_sub]').forEach(el => {
            if (el.getAttribute("code_pt") == code_seq
                && code_seqs.includes(parseInt(el.getAttribute( "code_seq")))) {
                el.nextElementSibling.hidden = false;
            }
        });
    }
}

// 교재 있음 체크시 disabled 해제
function lectureAddBookCheck(){
    const div_book = document.querySelector(".div_book");
    const inp_book = div_book.querySelector("#lecture_add_inp_book");
    const inp_book_link = div_book.querySelector("#lecture_add_inp_book_link");
    const chk_book = div_book.querySelector("#board_checkbox_lecture_book");
    if(chk_book.checked){
        inp_book.disabled = false;
        inp_book_link.disabled = false;
    }else{
        inp_book.disabled = true;
        inp_book_link.disabled = true;
    }
}

// 섬네일 미리보기 버튼 클릭.
function lectureAddThumbnailPreview(){
    const myModal = new bootstrap.Modal(document.getElementById('lecutre_add_modal_thumbnail_preview'), {
        keyboard: false
    });
    myModal.show();
}

// 파일 선택시 모달안에 섬네일 이미지에 이미지 넣어주기. / 선택파일없음에 파일이름 넣어주기.
function lectureAddThumbnailSetting(vthis){
    const div_thumbnail = document.querySelector(".div_thumbnail");
    const span_thumbnail = div_thumbnail.querySelector("#lecture_add_span_thumbnail");
    const img_thumbnail = document.querySelector("#lecture_add_modal_thumbnail_preview_img");
    const file = vthis.files[0];
    if(file){
        span_thumbnail.innerText = truncateFileName(file.name);
        img_thumbnail.src = URL.createObjectURL(file);
        img_thumbnail.hidden = false;
    }else{
        span_thumbnail.innerText = "선택파일 없음";
        img_thumbnail.hidden = true;
    }
}

// 총 강의수 펼치기
function lectureAddTotalLectureOpen(vthis){
    //먼저 총 강의수 숫자가 입력이 되어 있는지 확인
    const div_total_lecture = document.querySelector(".div_total_lecture");
    const inp_total_lecture = div_total_lecture.querySelector("#lecture_add_inp_total_lecture");
    const div_total_lecture_detail = document.querySelector(".div_total_lecture_detail");
    const btn_total_open = div_total_lecture.querySelector(".btn_total_open");
    const btn_total_close = div_total_lecture.querySelector(".btn_total_close");
    if(inp_total_lecture.value == ""){
        toast("총 강의수를 입력해주세요.");
        return;
    }
    div_total_lecture_detail.hidden = false;
    btn_total_open.hidden = true;
    btn_total_close.hidden = false;
}

// 총 강의수 닫기
function lectureAddTotalLectureClose(vthis){
    const div_total_lecture = document.querySelector(".div_total_lecture");
    const div_total_lecture_detail = document.querySelector(".div_total_lecture_detail");
    const btn_total_open = div_total_lecture.querySelector(".btn_total_open");
    const btn_total_close = div_total_lecture.querySelector(".btn_total_close");
    div_total_lecture_detail.hidden = true;
    btn_total_open.hidden = false;
    btn_total_close.hidden = true;

    // 테이블 초기화
    // const tby_total_lecture = document.querySelector("#lecture_add_tby_total_lecture");
    // const tr_total_lectures = tby_total_lecture.querySelectorAll(".tr_total_lecture");
    // tr_total_lectures.forEach(item => {
    //     tby_total_lecture.removeChild(item);
    // });
}

// 총 강의수 숫자만큼 강의영상 tr 추가
function lectureAddTotalLectureTrSetting(){
    //숫자 가져오기
    const div_total_lecture = document.querySelector(".div_total_lecture");
    const inp_total_lecture = div_total_lecture.querySelector("#lecture_add_inp_total_lecture");

    //숫자 없으면 리턴
    if(inp_total_lecture.value == ""){
        return;
    }

    //0보다 작으면 0으로 초기화
    if(inp_total_lecture.value < 0){
        inp_total_lecture.value = 0;
    }

    const tby_total_lecture_top = document.querySelector("#lecture_add_tby_total_lecture_top");
    const tby_total_lecture = document.querySelector("#lecture_add_tby_total_lecture");
    const copy_tr_total_lecture = tby_total_lecture.querySelector(".copy_tr_total_lecture").cloneNode(true);
    copy_tr_total_lecture.hidden = false;


    //가져온 숫자와 tr 갯수 비교 많으면 추가, 적으면 삭제.
    const prepare_el = document.querySelectorAll(".tr_total_lecture [data-num='0']");
    if(prepare_el.length < inp_total_lecture.value){
        //추가
        for(let i = prepare_el.length; i < inp_total_lecture.value; i++){
            const tr_total_lecture = copy_tr_total_lecture.cloneNode(true);
            tr_total_lecture.classList.remove("copy_tr_total_lecture");
            tr_total_lecture.classList.add("tr_total_lecture");
            //순서는 세번째 부터 1로 시작한다.
            tr_total_lecture.querySelector(".idx").innerText = i - 1;
            tr_total_lecture.querySelector(".lecture_checkbox_use_yn").id = "lecture_checkbox_use_yn" + i;
            tr_total_lecture.querySelector(".lecture_checkbox_use_yn").nextElementSibling.setAttribute("for", "lecture_checkbox_use_yn" + i);

            //첫 tr의 idx는 맛보기
            if(i == 0 || i == 1){
                let txt1 = "맛보기";
                if(i == 1) txt1 = "OT";
                tr_total_lecture.querySelector('.lecture_detail_name').classList.add('lecture_detail_description');
                tr_total_lecture.querySelector('.lecture_detail_name').classList.remove('lecture_detail_name');
                tr_total_lecture.querySelector(".idx").innerText = txt1;
                tr_total_lecture.querySelector(".idx").classList.add("table-light");
                tr_total_lecture.querySelectorAll("td")[1].innerText = txt1 + ' 영상을 업로드 해주세요.';
                tr_total_lecture.querySelectorAll("td")[1].setAttribute("colspan", "2");
                tr_total_lecture.querySelectorAll("td")[2].hidden = true;
;
                tby_total_lecture_top.appendChild(tr_total_lecture);
            }else{
                tby_total_lecture.appendChild(tr_total_lecture);
            }
        }
    }else if(prepare_el.length > inp_total_lecture.value){
        //삭제
        for(let i = prepare_el.length; i > inp_total_lecture.value; i--){
            const tbody = prepare_el[i - 1].closest('tr').closest('tbody');
            const lecture_detail_seq = prepare_el[i - 1].closest('tr').querySelector('.lecture_detail_seq').value;
            tbody.querySelectorAll('.tr_total_lecture').forEach(function(tr_el){
                if(lecture_detail_seq != '' && (tr_el.querySelector('.lecture_detail_seq').value == lecture_detail_seq
                    ||tr_el.querySelector('.lecture_detail_group').value == lecture_detail_seq)){
                    tr_el.remove();
                }
            });
            prepare_el[i - 1].closest('tr')?.remove();
        }
    }
}

// 총 강의수 숫자 증가, 감소
function lectureAddTotalCount(vthis){
    //수치없으면 0으로 초기화
    const div_total_lecture = document.querySelector(".div_total_lecture");
    const inp_total_lecture = div_total_lecture.querySelector("#lecture_add_inp_total_lecture");
    if(inp_total_lecture.value == ""){
        inp_total_lecture.value = 0;
    }

    //증가, 감소
    if(vthis.getAttribute("data") == "up"){
        inp_total_lecture.value = parseInt(inp_total_lecture.value) + 1;
    }else if(vthis.getAttribute("data") == "down"){
        if(inp_total_lecture.value > 0){
            inp_total_lecture.value = parseInt(inp_total_lecture.value) - 1;
        }
    }
    lectureAddTotalLectureTrSetting();
}

// 시리즈 추가 모달 열기
function lectureAddSeriesInsertModal(){
    //초기화
    const modal_series_insert = document.querySelector("#lecture_modal_series_insert");
    modal_series_insert.querySelector("#lecture_modal_series_insert_inp").value = "";
    const myModal = new bootstrap.Modal(document.getElementById('lecture_modal_series_insert'), {
        keyboard: false
    });
    myModal.show();
    //포커스 이동 0.2초뒤
    setTimeout(function(){
        modal_series_insert.querySelector("#lecture_modal_series_insert_inp").focus();
    }, 200);
}

// 강좌명(시리즈 하위) 추가 모달 열기
function lectureAddSeriesSubInsertModal(){
    //초기화
    const modal_series_sub_insert = document.querySelector("#lecture_modal_series_sub_insert");
    modal_series_sub_insert.querySelector("#lecture_modal_series_sub_insert_inp").value = "";
    const myModal = new bootstrap.Modal(document.getElementById('lecture_modal_series_sub_insert'), {
        keyboard: false
    });
    myModal.show();
    //포커스 이동 0.2초뒤
    setTimeout(function(){
        modal_series_sub_insert.querySelector("#lecture_modal_series_sub_insert_inp").focus();
    }, 200);
}

// 시리즈 추가 저장.
function lectureModalSeriesInsert(){
    //학년 중 checkbox 가 선택된게 있는지 확인, 과목중 radio 가 선택된게 있는지 확인. 모두 선택이 되어 있어야 시리즈 추가 가능.
    const div_grade = document.querySelector(".div_grade");
    const div_subject = document.querySelector(".div_subject");
    const grade_checked = div_grade.querySelectorAll("input[name=board_chk_grade]:checked");
    const subject_checked = div_subject.querySelectorAll("input[name=board_radio_subject]:checked");
    if(grade_checked.length == 0 || subject_checked.length == 0){
        toast("학년, 과목을 먼저 선택해주세요.");
        return;
    }

    //시리즈 이름 가져오기.
    const modal_series_insert = document.querySelector("#lecture_modal_series_insert");
    const series_name = modal_series_insert.querySelector("#lecture_modal_series_insert_inp").value;
    if(series_name == ""){
        toast("시리즈 이름을 입력해주세요.");
        return;
    }

    //시리즈 추가.
    let grade_code_seqs = "";
    grade_checked.forEach(item => {
        if (grade_code_seqs != "") grade_code_seqs += ",";
        grade_code_seqs += item.getAttribute("code_seq");
    });
    const subject_code_seq = subject_checked[0].getAttribute("code_seq");

    const page = "/manage/lecture/add/code/insert";
    const parameter = {
        code_category: "series",
        code_name: series_name,
        code_step: 1,
        grade_code_seqs:grade_code_seqs,
        subject_code_seq:subject_code_seq,
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            sAlert('', '저장되었습니다.');
            //시리즈 추가
            lectureAddAppendSeries(result.code_seq, series_name);
            //시리즈 다시 불러오기.
            lectureAddSeiresSelect();
            //모달 닫기
            modal_series_insert.querySelector(".modal_close").click();

        }
    })
}
// 시리즈 태그 추가.
function lectureAddAppendSeries(code_seq, code_name){
    const div_series = document.querySelector(".div_series");
    const series_in = div_series.querySelector(".div_series_in");
    const series_tags = series_in.querySelectorAll('#board_radio_lecture_' + code_seq);
    //존재하지 않으면 추가.
    const is_exist = series_tags.length > 0;
    if (!is_exist) {
        const input = document.createElement("input");
        input.type = "radio";
        input.name = "board_radio_series";
        input.classList.add("btn-check");
        input.classList.add("subject");
        input.id = "board_radio_lecture_" + code_seq;
        input.setAttribute("code_seq", code_seq);
        input.setAttribute("onclick", "lectureAddSeiresNextSelect(this)");
        input.autocomplete = "off";
        input.hidden = true;
        const label = document.createElement("label");
        label.classList.add("btn");
        label.classList.add("btn-outline-primary");
        label.classList.add("hpx-40");
        label.classList.add("rounded-0");
        label.htmlFor = "board_radio_lecture_" + code_seq;
        label.innerText = code_name;
        series_in.appendChild(input);
        series_in.appendChild(label);
    }
}

// 강좌명(시리즈 하위) 추가 저장.
function lectureModalSeriesSubInsert(){
    //학년 중 checkbox 가 선택된게 있는지 확인, 과목중 radio 가 선택된게 있는지 확인. 모두 선택이 되어 있어야 시리즈 추가 가능.
    const div_grade = document.querySelector(".div_grade");
    const div_subject = document.querySelector(".div_subject");
    const div_series = document.querySelector(".div_series");
    const grade_checked = div_grade.querySelectorAll("input[name=board_chk_grade]:checked");
    const subject_checked = div_subject.querySelectorAll("input[name=board_radio_subject]:checked");
    const series_checked = div_series.querySelectorAll("input[name=board_radio_series]:checked");
    if(grade_checked.length == 0 || subject_checked.length == 0 || series_checked.length == 0){
        toast("학년, 과목, 시리즈를 먼저 선택해주세요.");
        return;
    }

    //강좌명(시리즈 하위) 이름 가져오기.
    const modal_series_sub_insert = document.querySelector("#lecture_modal_series_sub_insert");
    const series_sub_name = modal_series_sub_insert.querySelector("#lecture_modal_series_sub_insert_inp").value;
    if(series_sub_name == ""){
        toast("강좌명(시리즈 하위) 이름을 입력해주세요.");
        return;
    }

    //강좌명(시리즈 하위) 추가.
    let grade_code_seqs = "";
    grade_checked.forEach(item => {
        if (grade_code_seqs != "") grade_code_seqs += ",";
        grade_code_seqs += item.getAttribute("code_seq");
    });
    const subject_code_seq = subject_checked[0].getAttribute("code_seq");
    const series_code_seq = series_checked[0].getAttribute("code_seq");

    const page = "/manage/lecture/add/code/insert";
    const parameter = {
        code_category: "series",
        code_name: series_sub_name,
        code_step: 2,
        grade_code_seqs:grade_code_seqs,
        subject_code_seq:subject_code_seq,
        series_code_seq:series_code_seq,
    };
    queryFetch(page, parameter, function(reuslt){
        if((reuslt.resultCode||'') == 'success'){
            sAlert('', '저장되었습니다.');
            //강좌명(시리즈 하위) 추가
            lectureAddAppendSeriesSub(reuslt.code_seq, series_sub_name, series_code_seq);
            connect_seqs.push({ code_seq: reuslt.code_seq, });
            //시리즈 하위 다시 불러오기.
            //선택되어 있는 시리즈 재 클릭
            series_checked[0].click();
            //모달 닫기
            modal_series_sub_insert.querySelector(".modal_close").click();
        }
    });
}
// 강좌명(시리즈 하위) 태그 추가.
function lectureAddAppendSeriesSub(code_seq, code_name, code_pt){
    const div_series_sub = document.querySelector(".div_series_sub");
    const series_sub_in = div_series_sub.querySelector(".div_series_sub_in");
    const series_sub_tags = series_sub_in.querySelectorAll('#board_radio_lecture_' + code_seq);
    //존재하지 않으면 추가.
    const is_exist = series_sub_tags.length > 0;
    if (!is_exist) {
        const input = document.createElement("input");
        input.type = "radio";
        input.name = "board_radio_series_sub";
        input.classList.add("btn-check");
        input.classList.add("subject");
        input.id = "board_radio_lecture_" + code_seq;
        input.setAttribute("code_seq", code_seq);
        input.autocomplete = "off";
        input.setAttribute("code_pt", code_pt);
        input.hidden = false;
        const label = document.createElement("label");
        label.classList.add("btn");
        label.classList.add("btn-outline-primary");
        label.classList.add("hpx-40");
        label.classList.add("rounded-0");
        label.htmlFor = "board_radio_lecture_" + code_seq;
        label.innerText = code_name;
        series_sub_in.appendChild(input);
        series_sub_in.appendChild(label);
    }
}

// 시간 설정.
function maxMinHour(vthis){
    // vthis의 value 값이 23위면 23, 0이하면 0으로 초기화
    vthis.value = vthis.value*1;
    if(vthis.value*1 > 23){
        vthis.value = 23;
    }else if(vthis.value*1 < 0){
        vthis.value = 0;
    }
    // 자리수가 2 이하면 0을 붙여준다.
    if((vthis.value*1) < 10){
        vthis.value = "0" + vthis.value*1;
    }
}

// 분 설정
function maxMinTime(vthis){
    // vthis의 value 값이 59위면 59, 0이하면 0으로 초기화
    vthis.value = vthis.value*1;
    if(vthis.value*1 > 59){
        vthis.value = 59;
    }else if(vthis.value*1 < 0){
        vthis.value = 0;
    }
    // 10이하면 0을 붙여준다.
    if((vthis.value*1) < 10){
        vthis.value = "0" + vthis.value*1;
    }
}

// 학습 영상 등록 저장
function lectureAddInsert(vthis){
    //수치가 있으면 수정.
    const lecture_seq = document.querySelector("#lecture_add_inp_seq").value;
    //분류(강좌구분분류)
    const course_seqs = lectureAddGetCodeSeqs("course", "radio");
    //분류(학년)
    const grade_seqs = lectureAddGetCodeSeqs("grade", "chk");
    //분류(학기)
    const semester_seqs = lectureAddGetCodeSeqs("semester", "chk");
    //분류(과목)
    const subject_seqs = lectureAddGetCodeSeqs("subject", "radio");
    //분류(시리즈)
    const series_seqs = lectureAddGetCodeSeqs("series", "radio");
    //분류(강좌명(시리즈 하위))
    const series_sub_seqs = lectureAddGetCodeSeqs("series_sub", "radio");
    // 선생님 이름
    const teacher_name = document.querySelector("#lecture_add_inp_teacher").value;
    //분류(강좌수준)
    const level_seqs = lectureAddGetCodeSeqs("level", "chk");
    //수강일
    const course_date_count = document.querySelector("#lecture_add_inp_day").value;
    //출판사
    const publisher_seqs = lectureAddGetCodeSeqs("publisher", "radio");
    // 교재 있음 체크 되어 있으면 교재명, 교재 링크 가져오기.
    //board_checkbox_lecture_book checked
    const div_book = document.querySelector(".div_book");
    const chk_book = div_book.querySelector("#board_checkbox_lecture_book");
    let book_name = "";
    let book_link = "";
    if(chk_book.checked){
        book_name = div_book.querySelector("#lecture_add_inp_book").value;
        book_link = div_book.querySelector("#lecture_add_inp_book_link").value;
    }
    //사용활성화
    const is_use = document.querySelector("#lecture_checkbox_lecture_use_yn").checked;
    //썸네일 파일
    const thumbnail_file = document.querySelector("#lecture_add_inp_thumbnail").files[0];
    //강좌이름
    const lecture_name = document.querySelector("#lecture_add_inp_register").value;
    //강좌설명
    const lecture_description = document.querySelector("#lecture_add_inp_content").value;
    // 강의 영상 정보 저장(lecture_detail)
    const lecture_details = lectureAddGetLectureDetailForTr();

    //값이 없으면 리턴
    if(lecture_name == ""){ toast("강좌등록(명)을 입력해주세요."); return; }
    // else if(lecture_description == ""){ toast("강좌설명을 입력해주세요."); return; }
    else if(course_seqs == ""){ toast("분류(강좌구분)를 선택해주세요."); return; }
    else if(grade_seqs == ""){ toast("학년을 선택해주세요."); return; }
    else if(semester_seqs == ""){ toast("학기를 선택해주세요."); return; }
    else if(subject_seqs == ""){ toast("과목을 선택해주세요."); return; }
    else if(series_seqs == ""){ toast("시리즈를 선택해주세요."); return; }
    else if(series_sub_seqs == ""){ toast("강좌명(시리즈 하위)를 선택해주세요."); return; }
    else if(teacher_name == ""){ toast("선생님 이름을 입력해주세요."); return; }
    else if(level_seqs == ""){ toast("강좌수준을 선택해주세요."); return; }
    else if(course_date_count == ""){ toast("수강일을 입력해주세요."); return; }

    //학습 영상 등록 저장
    const page = "/manage/lecture/add/insert";
    let formData = new FormData();

    //수정시 사용 변수
    formData.append("lecture_seq", lecture_seq);

    //분류 변수
    formData.append("course_seqs", course_seqs);
    formData.append("grade_seqs", grade_seqs);
    formData.append("semester_seqs", semester_seqs);
    formData.append("subject_seqs", subject_seqs);
    formData.append("series_seqs", series_seqs);
    formData.append("series_sub_seqs", series_sub_seqs);
    formData.append("publisher_seqs", publisher_seqs);

    //강좌 변수
    formData.append("teacher_name", teacher_name);
    formData.append("book_name", book_name);
    formData.append("book_link", book_link);
    formData.append("is_use", is_use ? "Y" : "N");
    formData.append("thumbnail_file", thumbnail_file);
    formData.append("lecture_name", lecture_name);
    formData.append("lecture_description", lecture_description);
    formData.append("level_seqs", level_seqs);
    formData.append("course_date_count", course_date_count);

    //강의 영상 정보 배열
    formData.append("lecture_details", JSON.stringify(lecture_details));
    //링크로 진행하므로 아래는 주석처리. / 다시 업로드로 변경.
    lecture_details.forEach((detail, index) => {
        formData.append(`lecture_details_files[${index}]`, detail.lecture_detail_file);
    });

    sAlert('저장', '저장을 계속 진행하시겠습니까?(동영상이 많을 경우에 오래 걸리수 있습니다.)', 2, function(){
        //로딩 시작
        vthis.querySelector(".spinner-border").hidden = false;

        queryFormFetch(page, formData, function(result){
            //로딩 끝
            vthis.querySelector(".spinner-border").hidden = true;

            if((result.resultCode||'') == 'success'){
                const lecture_seq = result.lecture_seq;
                sAlert('', '저장되었습니다.', 1, function(){
                    //window open 으로 왔을 때
                    if(window.opener){
                        window.opener.lectureSelect(lecture_seq);
                        window.close();
                    }else{
                        // 페이지이동
                        location.href = "/manage/lecture/add?lecture_seq=" + lecture_seq;
                    }
                });
                //timeout 6초
                setTimeout(function(){
                    //window open 으로 왔을 때
                    if(window.opener){
                        window.opener.lectureSelect(lecture_seq);
                        window.close();
                    }else{
                        // 페이지이동
                        location.href = "/manage/lecture/add?lecture_seq=" + lecture_seq;
                    }
                }, 5000);


            }
        });
    })
}

// 학습 영상 등록 화면 초기화(클리어)
function lectureAddClear(){

}

// 분류 정보 가져오기.
function lectureAddGetCodeSeqs(code_type, tag_type){
    const code_tags = document.querySelectorAll("input[name=board_"+tag_type+"_" + code_type + "]:checked");
    if(code_tags.length > 0){
        let code_seqs = "";
        code_tags.forEach(item => {
            if(code_seqs != "") code_seqs += ",";
            code_seqs += item.getAttribute("code_seq");
        });
        return code_seqs;
    }
    return "";
}

// 총 강의 수 하위 table > tr에서 정보 가져오기.
function lectureAddGetLectureDetailForTr(){
    const lecture_details = [];
    const tr_total_lectures = document.querySelectorAll(".tr_total_lecture");
    tr_total_lectures.forEach((item, index) => {
        const is_name = item.querySelectorAll(".lecture_detail_name").length > 0;
        const lecture_detail_name = is_name ? item.querySelector(".lecture_detail_name").value : (index == 0 ? "맛보기" : "OT");
        const lecture_detail_description = item.querySelector(".lecture_detail_description").value;
        const lecture_detail_hour = item.querySelector(".lecture_detail_hour").value||'00';
        const lecture_detail_minute = item.querySelector(".lecture_detail_minute").value||'00';
        const lecture_detail_time = lecture_detail_hour + ":" + lecture_detail_minute;
        const lecture_detail_link = item.querySelector(".lecture_add_inp_link").value;
        const lecture_detail_file = item.querySelector(".lecture_add_inp_file").files[0];
        const lecture_detail_type = item.querySelector(".lecture_detail_type").value;
        const lecture_detail_group = item.querySelector(".lecture_detail_group").value;
        const lecture_detail_exam_seq = item.querySelector("[data-exam-seq]").dataset.examSeq;
        const lecture_detail_interactive_seq = item.querySelector("[data-interactive-seq]").dataset.interactiveSeq;
        const is_first_interactive = item.querySelector("[data-is-first-interactive]").checked ? 'Y' : 'N';

        const is_link = lecture_detail_link ? 'Y' : 'N';
        const is_file = lecture_detail_file ? 'Y' : 'N';
        const is_use = item.querySelector(".lecture_checkbox_use_yn").checked;
        const idx = index;
        const lecture_detail_seq = item.querySelector(".lecture_detail_seq").value;
        const lecture_detail_count_day = item.querySelector(".lecture_detail_count_day").value;

        if(lecture_detail_name == ""){
            if(idx == 0 || idx == 1){
                const tr = document.querySelectorAll('#lecture_add_tby_total_lecture_top tr');
                tr.forEach(item => {
                    item.querySelector('.lecture_detail_name').value = "맛보기";

                });
            }else{
                toast("강의명 모두 입력해주세요.");
                return;
            }

        }
        const lecture_detail = {
            lecture_detail_name: lecture_detail_name,
            lecture_detail_description: lecture_detail_description,
            lecture_detail_time: lecture_detail_time,
            lecture_detail_link: lecture_detail_link,
            lecture_detail_file: lecture_detail_file,
            is_link:is_link,
            is_file:is_file,
            is_use: is_use ? "Y" : "N",
            idx: idx,
            lecture_detail_seq: lecture_detail_seq,
            lecture_detail_count_day:lecture_detail_count_day,
            lecture_detail_type:lecture_detail_type,
            lecture_detail_group:lecture_detail_group,
            lecture_detail_exam_seq:lecture_detail_exam_seq,
            lecture_detail_interactive_seq:lecture_detail_interactive_seq,
            is_first_interactive:is_first_interactive,
        };
        lecture_details.push(lecture_detail);
    });
    console.log(lecture_details)
    return lecture_details;

}

//강의 모두 체크 후 사용 자동 체크
function lectureAddTrChk(vthis){
    const tr = vthis.closest("tr");
    const lecture_detail_hour = tr.querySelector(".lecture_detail_hour").value;
    const lecture_detail_minute = tr.querySelector(".lecture_detail_minute").value;
    const lecture_detail_link = tr.querySelector(".lecture_add_inp_link").value;
    const lecture_detail_count_day = tr.querySelector(".lecture_detail_count_day").value;

    // 만약에 idx 가 맛보기, OT이면 강의시간, 파일 업로드만 체크 되어 있으면 사용 체크
    const idx = tr.querySelector(".idx").innerText;
    if(idx == "맛보기" || idx == "OT"){
        if(lecture_detail_hour != "" && lecture_detail_minute != "" && lecture_detail_link != ""){
            tr.querySelector(".lecture_checkbox_use_yn").checked = true;
        }
        return;
    }

    //강의명, 강의설명, 강의시간, 파일 업로드이 모두 입력되어 있으면 사용 체크
    const lecture_detail_name = tr.querySelector(".lecture_detail_name").value;
    const lecture_detail_description = tr.querySelector(".lecture_detail_description").value;

    if (
        lecture_detail_name != "" &&
            lecture_detail_description != "" &&
            lecture_detail_hour != "" &&
            lecture_detail_minute != "" &&
            lecture_detail_link != "" &&
            lecture_detail_count_day != ""
    ) {
        tr.querySelector(".lecture_checkbox_use_yn").checked = true;
    }
}

//썸네일 파일 삭제
function lectureAddThumbnailDelete(vthis){
    const page = "/manage/lecture/add/thumbnail/delete";
    const parameter = {
        lecture_seq: document.querySelector("#lecture_add_inp_seq").value,
    };
    //정말로 삭제하시겠습니까? 저장과 상관없이 삭제됩니다.
    sAlert('썸네일 삭제', '정말로 삭제하시겠습니까? 저장과 상관없이 삭제됩니다.', 2, function(){
        //로딩 시작
        vthis.querySelector(".spinner-border").hidden = false;
        queryFetch(page, parameter, function(result){
            //로딩 끝
            vthis.querySelector(".spinner-border").hidden = true;
            if((result.resultCode||'') == 'success'){
                sAlert('', '삭제되었습니다.');
                //썸네일 삭제 버튼 숨기기.
                vthis.hidden = true;
                // 썸네일 파일 이름 초기화
                // 미리보기 이미지 초기화
                const span_thumbnail = document.querySelector("#lecture_add_span_thumbnail");
                const img_thumbnail = document.querySelector("#lecture_add_modal_thumbnail_preview_img");
                span_thumbnail.innerText = "선택파일 없음";
                img_thumbnail.src = "";
                img_thumbnail.hidden = true;
            }
        });
    });
}

// 강의 영상 취소
function lectureAddCancel(){
    //정말로 취소하시겠습니까? 입력한 내용이 모두 초기화 됩니다.
    sAlert('취소', '정말로 취소하시겠습니까? 입력한 내용이 모두 초기화 됩니다.', 2, function(){
        //페이지 이동
        location.href = "/manage/lecture/add";
    });
}
// 강의 영상 삭제
function lectureAddDelete(vthis){
    const page = "/manage/lecture/add/delete";
    const parameter = {
        lecture_seq: document.querySelector("#lecture_add_inp_seq").value,
    };
    //정말로 삭제하시겠습니까? 관련된 모든 내용이 삭제됩니다.
    sAlert('삭제', '정말로 삭제하시겠습니까? 관련된 모든 내용이 삭제됩니다.', 2, function(){
        //로딩 시작
        vthis.querySelector(".spinner-border").hidden = false;
        queryFetch(page, parameter, function(result){
            //로딩 끝
            vthis.querySelector(".spinner-border").hidden = true;
            if((result.resultCode||'') == 'success'){
                sAlert('', '삭제되었습니다.', 1, function(){
                    //window open 으로 왔을 때
                    if(window.opener){
                        window.opener.lectureListTrRemove(parameter.lecture_seq);
                        window.close();
                    }else{
                        //삭제 후 페이지 이동
                        location.href = "/manage/lecture/add";
                    }
                });
                //timeout 6초
                setTimeout(function(){
                    //window open 으로 왔을 때
                    if(window.opener){
                        window.opener.lectureListTrRemove(parameter.lecture_seq);
                        window.close();
                    }else{
                        //삭제 후 페이지 이동
                        location.href = "/manage/lecture/add";
                    }
                }, 5000);
            }
        });
    });
}

// 개념다지기, 문제풀기, 정리학습, 단원평가 추가하기
let sel_tr = null;
function lectureAddTypeModalShow(vthis){
   const myModal = new bootstrap.Modal(document.getElementById('modal_add_type'), {
        keyboard: false
    });
    myModal.show();
    sel_tr = vthis.closest("tr");
    const modal = document.querySelector('#modal_add_type');
    const group_num = sel_tr.querySelector(".lecture_detail_seq").value;
    let block_cnt = 0;
    const max_cnt = 4;
    modal.querySelectorAll(".btn_type").forEach(function(el){
        el.disabled = false;
    });
    document.querySelectorAll(`.lecture_detail_group[data-num="${group_num}"]`).forEach(function(el){
        const tr = el.closest("tr");
        const lecture_detail_type = tr.querySelector(".lecture_detail_type").value;
        if(lecture_detail_type == 'concept_building'){
            modal.querySelector(".btn_type1").disabled = true;
            block_cnt++;
        }else if(lecture_detail_type == 'exam_solving'){
            modal.querySelector(".btn_type2").disabled = true;
            block_cnt++;
        }else if(lecture_detail_type == 'summarizing'){
            modal.querySelector(".btn_type3").disabled = true;
            block_cnt++;
        }else if(lecture_detail_type == 'unit_test'){
            modal.querySelector(".btn_type4").disabled = true;
            block_cnt++;
        }
        if(block_cnt == max_cnt){
            modal.querySelector(".btn_type5").disabled = true;
        }
    });
}

function lectureAddType(type,vthis){
    const tr = sel_tr.cloneNode(true);
    tr.querySelector('td').setAttribute('rowspan', 1);
    tr.querySelector('.lecture_detail_type').value = type;
    tr.querySelector('.btn_add_type').classList.remove('btn-primary');
    tr.querySelector('.btn_add_type').classList.add('btn-danger');
    tr.querySelector('.btn_add_type').innerText = "-";
    tr.querySelector('.btn_add_type').setAttribute('onclick', 'lectureAddTrRemove(this)');
    tr.querySelector('.lecture_detail_seq').value = "";
    tr.querySelector('.lecture_detail_group').value = sel_tr.querySelector('.lecture_detail_seq').value;
    tr.querySelector('.lecture_detail_group').dataset.num = sel_tr.querySelector('.lecture_detail_seq').value;

    if(vthis.disabled) return;
    tr.querySelector(".lecture_detail_type_str").classList.add("text-success");
    switch(type){
        case 'concept_building': //개념다지기
            tr.querySelector(".lecture_detail_type_str").innerText = "개념다지기";
            tr.querySelector('[data-btn-interactive-connect]').parentNode.hidden = false;
            tr.querySelector('[data-btn-interactive]').parentNode.hidden = false;

            lectureAddTr(sel_tr, tr);
            break;
        case 'unit_test': // 단원평가
        case 'exam_solving': // 문제풀기
            tr.querySelector(".lecture_detail_type_str").innerText = type == 'exam_solving' ? "문제풀기":"단원평가";

            tr.querySelector('.lecture_detail_hour').hidden = true;
            tr.querySelector('.lecture_detail_minute').hidden = true;
            tr.querySelector('.colon').hidden = true;
            tr.querySelector('[data-btn-upload]').innerText = "문제연결";
            tr.querySelector('.academy_number_file').placeholder = " 문제가 연결되면 타이틀이 표시됩니다.";
            tr.querySelector('.lecture_detail_count_day').hidden = true;
            tr.querySelector('[data-btn-exam-connect]').hidden = false;
            tr.querySelector('.lecture_add_inp_link').value = '';
            tr.querySelector('.lecture_add_inp_link').disabled = true;
            lectureAddTr(sel_tr, tr);
            break;
        case 'summarizing': // 정리학습
            tr.querySelector(".lecture_detail_type_str").innerText = "정리학습";
            tr.querySelector('[data-btn-interactive-connect]').parentNode.hidden = false;
            tr.querySelector('[data-btn-interactive]').parentNode.hidden = false;
            lectureAddTr(sel_tr, tr);
            break;
        case '5': // 전체
            const modal = document.querySelector('#modal_add_type');
            lectureAddType('concept_building', modal.querySelector('.btn_type1'));
            lectureAddType('exam_solving', modal.querySelector('.btn_type2'));
            lectureAddType('summarizing', modal.querySelector('.btn_type3'));
            lectureAddType('unit_test', modal.querySelector('.btn_type4'));
            break;
    }

    // 모달 닫기.
    const modal = document.querySelector('#modal_add_type');
    modal.querySelector('.btn-close').click();
}

// 준비하기 하단에 TR 추가시에 rowspan 처리.
function lectureAddTr(target_tr, add_tr){

    if(target_tr.querySelector('td').getAttribute('rowspan') * 1 < 1){
        target_tr.after(add_tr);
    }else{
        // rowspan 만큼 뒤에 추가
        let rowspan = target_tr.querySelector('td').getAttribute('rowspan') * 1 -1;
        let next_tr = target_tr;
        for(let i = 0; i < rowspan; i++){
            next_tr = next_tr.nextElementSibling;
        }
        next_tr.after(add_tr);
    }
    let add_rowspan = target_tr.querySelector('td').getAttribute('rowspan') * 1;
    add_rowspan++ ;
    target_tr.querySelector('td').setAttribute('rowspan', add_rowspan);
    target_tr.querySelectorAll('td')[1].setAttribute('rowspan', add_rowspan);
    target_tr.querySelectorAll('td')[2].setAttribute('rowspan', add_rowspan);
    add_tr.querySelectorAll('td')[0].hidden = true;
    add_tr.querySelectorAll('td')[1].hidden = true;
    add_tr.querySelectorAll('td')[2].hidden = true;

    add_tr.querySelector('.lecture_detail_hour').value = '0';
    add_tr.querySelector('.lecture_detail_minute').value = '0';
    add_tr.querySelector('.lecture_add_inp_link').value = '';

}


// 준비 하기 하단에 TR 삭제시에 rowspan 처리.
function lectureAddTrRemove(vthis){
    const tr = vthis.closest('tr');
    // 최대 5번까지 rowspan이 있는지 확인. previousElementSibling 으로 넘어간다.
    let main_tr = vthis.closest('tr');
    let rowspan = 0;

    for(let i = 0; i < 5; i++){
        rowspan = main_tr.querySelector('td').getAttribute('rowspan')*1;
        if(rowspan != 1){
           break;
        }
        main_tr = main_tr.previousElementSibling;
    }
    if(rowspan > 1){
        main_tr.querySelector('td').setAttribute('rowspan', rowspan - 1);
        main_tr.querySelectorAll('td')[1].setAttribute('rowspan', rowspan - 1);
        main_tr.querySelectorAll('td')[2].setAttribute('rowspan', rowspan - 1);
    }
    tr.remove();
}

// 업로드 변튼 클릭
// 업로드시 각 타입에 따라서 변형.
function lectureAddUpload(vthis){
    const tr = vthis.closest("tr");
    const ld_type = tr.querySelector(".lecture_detail_type").value;
    switch(ld_type){
        case '':
            // 파일 업로드
            // tr.querySelector('.lecture_add_inp_file').click();
            lectureAddMultipleVideos(vthis);
        break;
        case 'concept_building':
            // 파일 업로드
            // tr.querySelector('.lecture_add_inp_file').click();
            lectureAddMultipleVideos(vthis);
        break;
        case 'exam_solving':
            // 문제 선택.
            // modal 열기
            lectureAddExamModalShow(vthis);
        break;
        case 'summarizing':
            // 파일 업로드.
            // tr.querySelector('.lecture_add_inp_file').click();
            lectureAddMultipleVideos(vthis);
        break;
        case 'unit_test':
            // 문제 선택
            lectureAddExamModalShow(vthis);
        break;
    }
}

// 업로드 링크 변경시
function lectureAddUploadLinkChange(vthis){
    const file = vthis.files[0];
    const tr = vthis.closest("tr");
    const lecture_detail_link = tr.querySelector(".lecture_add_inp_link");
    if(file){
        lecture_detail_link.value = URL.createObjectURL(file);
        lecture_detail_link.disabled = true;
    }else{
        lecture_detail_link.value = "";
        lecture_detail_link.disabled = false;
    }
}

// type 에 따른 이름 변경.
function getStringDetailType(type){
    if(type == 'concept_building'){
        return '개념다지기';
    }else if(type == 'exam_solving'){
        return '문제풀기';
    }else if(type == 'summarizing'){
        return '정리학습';
    }else if(type == 'unit_test'){
        return '단원평가';
    }
}


// TODO: 나중에 문제가 많아지면, 페이징을 하던지, 연속 스크롤 기능을 구현하던지 해야 할듯.
// 문제 조회
function lectureAddExamSelect(){
    const title = document.querySelector('[data-search-title]').value;
    const subject_code = document.querySelector('[data-search-subject-code]').value;
    const grade_code = document.querySelector('[data-search-grade-code]').value;
    const semester_code = document.querySelector('[data-search-semester-code]').value;

    const page = "/manage/exam/select";
    const parameter = {
        title: title,
        subject_code: subject_code,
        grade_code: grade_code,
        semester_code: semester_code,
        is_not_page:'Y',
    };

    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            // 초기화
            const bundle = document.querySelector('[data-bundle="exam_list"]');
            const row_copy = bundle.querySelector('[data-row="copy"]');
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

            const exams = result.exams;
            // 페이징
            // userPaymentTablePaging(result.exams, '1');

            // foreach
            exams.forEach(function(result){
                const row = row_copy.cloneNode(true);
                row.hidden = false;
                row.dataset.row='clone';
                row.querySelector('[data-subject-name]').innerText = result.subject_name;
                row.querySelector('[data-grade-name]').innerText = result.grade_name;
                row.querySelector('[data-semester-name]').innerText = result.semester_name;
                row.querySelector('[data-exam-title]').innerText = result.exam_title;
                row.querySelector('[data-subject-seq]').value = result.subject_seq;
                row.querySelector('[data-grade-seq]').value = result.grade_seq;
                row.querySelector('[data-semester-seq]').value = result.semester_seq;
                row.querySelector('[data-exam-seq]').value = result.id;
                bundle.appendChild(row);
            });
        }
    });
}


// 문제 연결 시 TR 저장.
let sel_exam_tr = null;
// 문제 선택 modal 열기
function lectureAddExamModalShow(vthis){
    const myModal = new bootstrap.Modal(document.getElementById('modal_exam_list'), {
        keyboard: false
    });
    myModal.show();
    sel_exam_tr = vthis.closest("tr");
}


// 문제 리스트 선택
function lectureAddExamListTrClick(vthis){
    const bundle = document.querySelector('[data-bundle="exam_list"]');
    const trs = bundle.querySelectorAll('[data-row="clone"]');
    trs.forEach(function(tr){
        tr.classList.remove('active');
    });
    if(vthis.classList.contains('active')){
        vthis.classList.remove('active');
    }else{
        vthis.classList.add('active');
    }
}

// 문제 연결 모달 > 문제연결 버튼 클릭.
function lectureAddExamConnect(){
    const modal = document.querySelector('#modal_exam_list');
    const tr =  modal.querySelector('[data-row="clone"].active');
    const exam_seq  = tr.querySelector('[data-exam-seq]').value;
    const exam_title = tr.querySelector('[data-exam-title]').innerText;
    if(!tr){
        toast('문제를 선택해주세요.');
        return;
    }
    if(!sel_exam_tr){
        toast('강의가 선택되어 있지 않습니다.');
        return;
    }
    sel_exam_tr.querySelector('[data-btn-exam-connect]').classList.add('active');
    sel_exam_tr.querySelector('[data-btn-exam-connect]').dataset.examSeq = exam_seq;
    sel_exam_tr.querySelector('[data-btn-exam-connect]').hidden = false;
    sel_exam_tr.querySelector('.lecture_add_inp_link').value = exam_title;
    sel_exam_tr.querySelector('.lecture_add_inp_link').disabled = true;
    toast('연결 되었습니다.');

    // modal close
    modal.querySelector('.btn-close').click();
}

// 문제 연결 끊기 클릭
function lectureAddExamConnectCancel(vthis){
    vthis.classList.remove('active');
    vthis.dataset.examSeq = '';
    toast('연결이 해제되었습니다. 저장을 누르면 적용됩니다.');
}

let sel_inter_tr = null;
// 인터렉티브 선택 modal 열기
function lectureAddInterModalShow(vthis){
    const myModal = new bootstrap.Modal(document.getElementById('modal_inter_list'), {
        keyboard: false
    });
    myModal.show();
    sel_inter_tr = vthis.closest("tr");
}

// 인터렉티브 조회
function lectureAddInterSelect(){
    const modal = document.querySelector('#modal_inter_list');
    const title = modal.querySelector('[data-search-title]').value;
    const subject_code = modal.querySelector('[data-search-subject-code]').value;
    const grade_code = modal.querySelector('[data-search-grade-code]').value;
    const semester_code = modal.querySelector('[data-search-semester-code]').value;
    const page = "/manage/interactive/select";
    const parameter = {
        title: title,
        subject_code: subject_code,
        grade_code: grade_code,
        semester_code: semester_code,
        is_not_page:'Y',
    };

    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            // 초기화
            const bundle = document.querySelector('[data-bundle="inter_list"]');
            const row_copy = bundle.querySelector('[data-row="copy"]');
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);

            const interactives = result.interactives;
            // 페이징
            // userPaymentTablePaging(result.interactives, '1');

            // foreach
            interactives.forEach(function(result){
                const row = row_copy.cloneNode(true);
                row.hidden = false;
                row.dataset.row='clone';
                row.querySelector('[data-subject-name]').innerText = result.subject_name;
                row.querySelector('[data-grade-name]').innerText = result.grade_name;
                row.querySelector('[data-semester-name]').innerText = result.semester_name;
                row.querySelector('[data-title]').innerText = result.title;
                row.querySelector('[data-subject-seq]').value = result.subject_seq;
                row.querySelector('[data-grade-seq]').value = result.grade_seq;
                row.querySelector('[data-semester-seq]').value = result.semester_seq;
                row.querySelector('[data-interactive-seq]').value = result.id;
                bundle.appendChild(row);
            });
        }
    });
}

// 인터렉티브 리스트 선택
function lectureAddInterListTrClick(vthis){
    const bundle = document.querySelector('[data-bundle="inter_list"]');
    const trs = bundle.querySelectorAll('[data-row="clone"]');
    trs.forEach(function(tr){
        tr.classList.remove('active');
    });
    if(vthis.classList.contains('active')){
        vthis.classList.remove('active');
    }else{
        vthis.classList.add('active');
    }
}

// 인터렉티브 연결 버튼 클릭
function lectureAddInterConnect(){
    const modal = document.querySelector('#modal_inter_list');
    const tr =  modal.querySelector('[data-row="clone"].active');
    const interactive_seq  = tr.querySelector('[data-interactive-seq]').value;
    const title = tr.querySelector('[data-title]').innerText;
    if(!tr){
        toast('인터렉티브를 선택해주세요.');
        return;
    }
    if(!sel_inter_tr){
        toast('강의가 선택되어 있지 않습니다.');
        return;
    }
    sel_inter_tr.querySelector('[data-btn-interactive-connect]').classList.add('active');
    sel_inter_tr.querySelector('[data-btn-interactive-connect]').dataset.interactiveSeq = interactive_seq;
    sel_inter_tr.querySelector('[data-btn-interactive-connect]').hidden = false;
    sel_inter_tr.querySelector('.inp_interactive_title').value = title;
    sel_inter_tr.querySelector('.inp_interactive_title').disabled = true;
    toast('연결 되었습니다.');

    // modal close
    modal.querySelector('.btn-close').click();
}
// 인터렉티브 연결 끊기 클릭
function lectureAddInterConnectCancel(vthis){
    const tr = vthis.closest("tr");
    vthis.classList.remove('active');
    vthis.dataset.interactiveSeq = '';
    tr.querySelector('.inp_interactive_title').value = '';
    toast('연결이 해제되었습니다. 저장을 누르면 적용됩니다.');
}
</script>

{{-- js 만들시 위까지만 아래는 라라벨 코드 때문에 js로 사용이 안됨. --}}
@if(!empty($lectures) > 0)
<script>
// ready 바닐라
document.addEventListener("DOMContentLoaded", function(){
    // 취소버튼 숨기기
    document.querySelector("#lecture_add_btn_cancel").hidden = true;
    // 삭제버튼 보여주기
    document.querySelector("#lecture_add_btn_delete").hidden = false;
    setTimeout(function(){
        lectureAddInit1();
        lectureAddInit2();
    }, 100);
});

function lectureAddInit1(){
    //id
    document.querySelector("#lecture_add_inp_seq").value = "{{ $lectures->id }}";
    //분류1
    @foreach($lecture_codes->where('code_category', 'course') as $course)
    lectureAddTagExistSetValue("#board_radio_lecture_{{ $course->code_seq }}");
    @endforeach
            @foreach($lecture_codes->where('code_category', 'grade') as $grade)
    lectureAddTagExistSetValue("#board_checkbox_lecture_{{ $grade->code_seq }}");
    @endforeach
            @foreach($lecture_codes->where('code_category', 'semester') as $semester)
    lectureAddTagExistSetValue("#board_checkbox_lecture_{{ $semester->code_seq }}");
    @endforeach
            @foreach($lecture_codes->where('code_category', 'subject') as $subject)
    lectureAddTagExistSetValue("#board_radio_lecture_{{ $subject->code_seq }}");
    @endforeach
            @foreach($lecture_codes->where('code_category', 'level') as $level)
    lectureAddTagExistSetValue("#board_checkbox_lecture_{{ $level->code_seq }}");
    @endforeach
            @foreach($lecture_codes->where('code_category', 'series') as $series)
    lectureAddTagExistSetValue("#board_radio_lecture_{{ $series->code_seq }}");
    @endforeach
            @foreach($lecture_codes->where('code_category', 'series_sub') as $series_sub)
    lectureAddTagExistSetValue("#board_radio_lecture_{{ $series_sub->code_seq }}");
    @endforeach
            @foreach($lecture_codes->where('code_category', 'publisher') as $publisher)
    lectureAddTagExistSetValue("#board_radio_lecture_{{ $publisher->code_seq }}");
    @endforeach
    // 선택해주세요. 숨김처리.
    document.querySelectorAll(".div_toast").forEach(item => {
        item.hidden = true;
    });

    // 선생님 이름
    document.querySelector("#lecture_add_inp_teacher").value = "{{ $lectures->teacher_name }}";
    //교재
    @if($lectures->book_name != "")
    document.querySelector("#board_checkbox_lecture_book").click();
    document.querySelector("#lecture_add_inp_book").value = "{{ $lectures->book_name }}";
    document.querySelector("#lecture_add_inp_book_link").value = "{{ $lectures->book_link }}";
    @endif
    //사용활성화
    document.querySelector("#lecture_checkbox_lecture_use_yn").checked = "{{ $lectures->is_use == 'Y' ? 'checked' : '' }}";
    //썸네일 파일
    @if($lecture_uploadfiles->count() > 0)
    const span_thumbnail = document.querySelector("#lecture_add_span_thumbnail");
    //선택파일 이름 구하기.
    const file_path = "{{ asset('storage') }}/{{ $lecture_uploadfiles[0]->file_path }}";
    const file_name = file_path.split("/")[file_path.split("/").length - 1];
    span_thumbnail.innerText = truncateFileName(file_name);
    //선택파일 이미지 보여주기.
    const img_thumbnail = document.querySelector("#lecture_add_modal_thumbnail_preview_img");
    img_thumbnail.src = file_path;
    img_thumbnail.hidden = false;
    //썸네일 삭제 버튼 보여주기.
    document.querySelector("#lecture_add_btn_thumbnail_delete").hidden = false;
    @endif
    //강좌이름
    document.querySelector("#lecture_add_inp_register").value = "{{ $lectures->lecture_name }}";
    //강좌설명
    document.querySelector("#lecture_add_inp_content").value = `{{ $lectures->lecture_description }}`;
    //수강일
    document.querySelector("#lecture_add_inp_day").value = "{{ $lectures->course_date_count }}";
}
function lectureAddInit2(){
    //강의 영상 정보
    @if(!empty($lecture_details))
    //총 강의수
    document.querySelector("#lecture_add_inp_total_lecture").value = "{{ $lecture_details->count() }}";
    lectureAddTotalLectureTrSetting();

    //$lecture_details 를 배열로 만들고 json 으로 변환하여 저장.
    const lecture_details = '@json($lecture_details->toArray())';
    lectureAddDetailTrSetting(lecture_details);
    lectureAddTotalLectureOpen();
    @endif

}

//
function lectureAddTagExistSetValue(id){
    const tags = document.querySelectorAll(id);
    if(tags.length > 0){
        tags.forEach(item => {
            item.checked = true;
            item.nextElementSibling.hidden = false;
        });
    }
}

// 강의 세팅
function lectureAddDetailTrSetting(lecture_details){
    const safeString = lecture_details.replace(/\r\n/g, '\\r\\n')
    lecture_details = JSON.parse(safeString);

    const tr_total_lecture = document.querySelectorAll(".tr_total_lecture");
    const bundle_top = document.querySelector('#lecture_add_tby_total_lecture_top');
    let main_tr = null;
    let prepare_cnt = 0;
    for(let i = 0; i < lecture_details.length; i++){
        const lecture_detail = lecture_details[i];
        const tr = tr_total_lecture[i];
        //강의 seq
        tr.querySelector(".lecture_detail_seq").value = lecture_detail.id;
        // 준비하기이면 ++
        if(lecture_detail.lecture_detail_type == ""){
            prepare_cnt++;
            main_tr = tr;
            tr.querySelector('.idx').innerText = prepare_cnt-2;
        }else{
            const rowspan = main_tr.querySelector('td').getAttribute('rowspan') * 1;
            main_tr.querySelectorAll('td')[0].setAttribute('rowspan', rowspan + 1);
            main_tr.querySelectorAll('td')[1].setAttribute('rowspan', rowspan + 1);
            main_tr.querySelectorAll('td')[2].setAttribute('rowspan', rowspan + 1);
            tr.querySelectorAll('td')[0].hidden = true;
            tr.querySelectorAll('td')[1].hidden = true;
            tr.querySelectorAll('td')[2].hidden = true;

            tr.querySelector('.lecture_detail_type_str').innerText = getStringDetailType(lecture_detail.lecture_detail_type);
            tr.querySelector('.lecture_detail_type_str').classList.add('text-success');
            tr.querySelector('.lecture_detail_type').value = lecture_detail.lecture_detail_type;

            tr.querySelector('.btn_add_type').classList.remove('btn-primary');
            tr.querySelector('.btn_add_type').classList.add('btn-danger');
            tr.querySelector('.btn_add_type').innerText = "-";
            tr.querySelector('.btn_add_type').setAttribute('onclick', 'lectureAddTrRemove(this)');
            // 만약에 문제풀기라면 몇가지 변경
            if(lecture_detail.lecture_detail_type == 'exam_solving' ||
                lecture_detail.lecture_detail_type == 'unit_test'){
                tr.querySelector('.lecture_detail_hour').hidden = true;
                tr.querySelector('.lecture_detail_minute').hidden = true;
                tr.querySelector('.colon').hidden = true;
                tr.querySelector('[data-btn-upload]').innerText = "문제연결";
                tr.querySelector('.academy_number_file').placeholder = " 문제가 연결되면 타이틀이 표시됩니다.";
                tr.querySelector('.lecture_detail_count_day').hidden = true;
                tr.querySelector('[data-btn-exam-connect]').hidden = false;
                if((lecture_detail.lecture_exam_seq || '') != ''){
                    tr.querySelector('[data-btn-exam-connect]').classList.add('active');
                    tr.querySelector('[data-btn-exam-connect]').dataset.examSeq = lecture_detail.lecture_exam_seq;
                    // tr.querySelector('.lecture_add_inp_link').value = lecture_detail.exam_title;// 어차피 link 로 저장되기 때문에 굳이 할필요없음.
                    tr.querySelector('.lecture_add_inp_link').disabled = true;
                }
            }
            // 만약에 개념다지기, 정리학습이면 인터렉티브 추가.
            if(lecture_detail.lecture_detail_type == 'concept_building' ||
                lecture_detail.lecture_detail_type == 'summarizing'){
                tr.querySelector('[data-btn-interactive-connect]').parentNode.hidden = false;
                tr.querySelector('[data-btn-interactive]').parentNode.hidden = false;
                if((lecture_detail.interactive_seq||'') != ''){
                    tr.querySelector('[data-btn-interactive-connect]').classList.add('active');
                    tr.querySelector('[data-btn-interactive-connect]').dataset.interactiveSeq = lecture_detail.interactive_seq;
                    tr.querySelector('.inp_interactive_title').value = lecture_detail.interactive_title;
                    if(lecture_detail.is_first_interactive == 'Y')
                        tr.querySelector('[data-is-first-interactive]').checked = true;
                    tr.querySelector('.inp_interactive_title').disabled = true;
                }
            }
        }
        if(prepare_cnt > 2){
            //강의명
            tr.querySelector(".lecture_detail_name").value = lecture_detail.lecture_detail_name;
            //강의설명
            tr.querySelector(".lecture_detail_description").value = lecture_detail.lecture_detail_description;
        }else{
            if(prepare_cnt == 1){
                //.idx 에 table-light 추가.
                //.idx 에 맛보기 수정.
                tr.querySelector(".idx").innerText = "맛보기";
                tr.classList.add("table-light");
                tr.querySelectorAll('td')[1].innerText = '맛보기 영상을 업로드해주세요.';
            }else if(prepare_cnt == 2){
                tr.querySelector(".idx").innerText = "OT";
                tr.classList.add("table-light");
                tr.querySelectorAll('td')[1].innerText = 'OT 영상을 업로드 해주세요.';
            }
            tr.querySelectorAll('td')[1].setAttribute('colspan', 2);
            tr.querySelectorAll('td')[2].hidden = true;
            bundle_top.appendChild(tr);
        }
        //강의 링크
        tr.querySelector(".lecture_add_inp_link").value = lecture_detail.lecture_detail_link;
        // 업로드 파일이 있을 경우 disabled 처리.
        // TODO: 추후에 업로드 취소시 삭제 처리를 즉각 처리하는 코드를 추가해야 할듯.
        const file_path = lecture_detail.file_path;
        if(file_path) {
            tr.querySelector(".lecture_add_inp_link").disabled = true;
        }
        //강의시간
        const lecture_detail_time = lecture_detail.lecture_detail_time;
        const lecture_detail_hour = Math.floor(lecture_detail.lecture_detail_time/60);
        const lecture_detail_minute = lecture_detail.lecture_detail_time%60;
        tr.querySelector(".lecture_detail_hour").value = lecture_detail_hour;
        tr.querySelector(".lecture_detail_minute").value = lecture_detail_minute;
        // group 번호.
        const lecture_detail_group = lecture_detail.lecture_detail_group;
        tr.querySelector(".lecture_detail_group").value = lecture_detail_group;
        tr.querySelector('.lecture_detail_group').dataset.num = lecture_detail_group;
        //파일업로드 추후 코드 추가
        //사용여부
        tr.querySelector(".lecture_checkbox_use_yn").checked = lecture_detail.is_use == "Y" ? true : false;
        //강의 일수
        tr.querySelector(".lecture_detail_count_day").value = lecture_detail.lecture_detail_count_day;

    }
    document.querySelector('#lecture_add_inp_total_lecture').value = prepare_cnt;
}

function truncateFileName(fileName, maxLength = 30) {
    if (fileName.length <= maxLength) return fileName;

    const extension = fileName.split('.').pop();
    const nameWithoutExtension = fileName.slice(0, fileName.lastIndexOf('.'));

    const truncatedLength = maxLength - extension.length - 6; // 6은 '...' + '.' + 공백의 길이

    if (truncatedLength < 5) return fileName; // 너무 짧으면 원래 이름 반환

    const start = nameWithoutExtension.slice(0, Math.ceil(truncatedLength / 2));
    const end = nameWithoutExtension.slice(-Math.floor(truncatedLength / 2));

    return `${start}...${end}.${extension}`;
}

function showUploadModal(files, folderName) {
    // 모달 생성
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'uploadModal';
    modal.setAttribute('tabindex', '-1');
    modal.setAttribute('aria-labelledby', 'uploadModalLabel');
    modal.setAttribute('aria-hidden', 'true');

    // 모달 내용 구성
    modal.innerHTML = `
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">업로드할 파일</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <div class="count_file mb-2">
                        ${files.length}개의 파일을 업로드 합니다.
                    </div>
                    <ul class="list-group" id="fileList"></ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                    <button id="uploadButton" type="button" class="btn btn-primary">업로드</button>
                </div>
            </div>
        </div>`

    // 모달을 body에 추가
    document.body.appendChild(modal);

    // 파일 목록 표시
    const fileList = modal.querySelector('#fileList');
    Array.from(files).forEach((file, index) => {
        const li = document.createElement('li');
        li.className = 'list-group-item';
        const fileName = file.name;
        li.textContent = fileName;
        fileList.appendChild(li);
    });

    // 모달 표시
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();

    // 모달 닫힐 때 초기화
    modal.addEventListener('hidden.bs.modal', function () {
        document.body.removeChild(modal);
    });
}


async function uploadFile(file, folderName, lectureDetailSeq, last_time) {
    const formData = new FormData();
    const fileName = file.name;

    formData.append('video', file, fileName);
    formData.append('lecture_detail_seq', lectureDetailSeq);
    if(last_time == "Y"){
        formData.append('last_time', "Y");
    }else{
        formData.append('last_time', "N");
    }
    try {
        const response = await fetch('/manage/lecture/video/multiple/upload', {
            method: 'POST',
            body: formData,
            headers: {
                "X-CSRF-TOKEN": '{{ csrf_token() }}',
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        console.log(`파일 업로드 완료:`, data);
        return data;
    } catch (error) {
        console.error(`파일 업로드 실패:`, error);
        throw error;
    }
}

async function uploadFilesSequentially(files, folderName, vthis, chgInputFile) {
    const results = [];
    toast('업로드 시작');
    const fileList = document.getElementById('fileList');
    const uploadButton = document.getElementById('uploadButton');
    uploadButton.disabled = true;
    for (let i = 0; i < files.length; i++) {
        const listItem = fileList.children[i];
        try {
            const result = await uploadFile(files[i], folderName, vthis, "N");
            results.push(result);
            listItem.classList.add('list-group-item-success');
            listItem.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            toast(`파일 ${i + 1} 업로드 성공`);
            console.log(i, (files.length -1));
             if (i == files.length - 1) {
                await uploadFile(files[0], folderName, vthis, "Y");
                const completionMessage = document.createElement('p');
                completionMessage.textContent = '모든 파일 업로드가 완료되었습니다.';
                completionMessage.className = 'text-success mt-3';
                fileList.appendChild(completionMessage);
                completionMessage.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                uploadButton.classList.add('d-none');
                console.log(`전체 파일 업로드 성공`);
                chgInputFile.value = '/storage/'+result.path;
                chgInputFile.disabled = true;
            }
        } catch (error) {
            toast(`파일 ${i + 1} 업로드 중 오류 발생`,error);
             listItem.classList.add('list-group-item-danger');
            break; // 업로드 실패 시 반복문 멈춤
        }

    }
    return results;
}

function lectureAddMultipleVideos(vthis) {
    const fileInput = document.createElement('input');
    fileInput.type = 'file';
    fileInput.multiple = true;
    sAlert('', '폴더를 선택시 폴더(m3u8 선택시) 폴더버튼을, 파일(mp4 선택시) 파일버튼을 선택해주세요.', 3, function(){
        fileInput.webkitdirectory = true; // 폴더 선택 활성화
        fileInput.directory = true; // 폴더 선택 활성화
        fileInput.click();
    }, function(){
        fileInput.accept = 'video/*,.mp4,.m3u8';
        fileInput.click();
    }, '폴더','파일');

    const lectureDetailSeq = vthis.closest('.tr_total_lecture').querySelector('.lecture_detail_seq').value;
    const chgInputFile = vthis.closest('.div_number_file').querySelector('.lecture_add_inp_link');
    console.log(lectureDetailSeq);
    fileInput.onchange = function(e) {
        e.preventDefault();
        const files = e.target.files;
        const videoFiles = Array.from(files).filter(file => file.type.startsWith('video/') || file.name.endsWith('.m3u8'));

        if (videoFiles.length === 0) {
            alert('선택된 비디오 파일이 없습니다.');
            return;
        }

        const folderName = files[0].webkitRelativePath.split('/')[0];
        console.log(`선택된 폴더명: ${folderName}`);

        // 파일 목록을 모달에 표시
        showUploadModal(videoFiles, folderName);

        // 업로드 버튼 클릭 시 파일 업로드 시작
        document.getElementById('uploadButton').addEventListener('click', function() {
            uploadFilesSequentially(videoFiles, folderName, lectureDetailSeq, chgInputFile);
        });
    };
}

// HLS 플레이어 초기화 함수
function initHLSPlayer(videoElement, hlsUrl) {
  if (Hls.isSupported()) {
    var hls = new Hls();
    hls.loadSource(hlsUrl);
    hls.attachMedia(videoElement);
    hls.on(Hls.Events.MANIFEST_PARSED, function() {
      videoElement.play();
    });
  }
  // HLS를 네이티브로 지원하는 브라우저 처리 (예: Safari)
  else if (videoElement.canPlayType('application/vnd.apple.mpegurl')) {
    videoElement.src = hlsUrl;
    videoElement.addEventListener('loadedmetadata', function() {
      videoElement.play();
    });
  }
}

// 비디오 요소에 HLS 플레이어 적용 예시
// document.addEventListener('DOMContentLoaded', function() {
//   var videoElement = document.getElementById('hls-video');
//   var hlsUrl = 'https://example.com/path/to/your/playlist.m3u8';
//   initHLSPlayer(videoElement, hlsUrl);
// });

// 전체 미리보기
function lecturePreview(vthis) {
    const tr = vthis.closest("tr");
    const ld_type = tr.querySelector(".lecture_detail_type").value;
    switch(ld_type){
        case '':
            // 비디오 미리보기
            lectureVideoPreview(vthis);
        break;
        case 'concept_building':
            // 비디오 미리보기
            lectureVideoPreview(vthis);
        break;
        case 'exam_solving':
            // 문제 미리보기.
            lectureExamPreview(vthis);
        break;
        case 'summarizing':
            // 비디오 미리보기
            lectureVideoPreview(vthis);
        break;
        case 'unit_test':
            // 문제 선택
            lectureExamPreview(vthis);
        break;
    }
}
// 문제 미리보기
function lectureExamPreview(vthis) {
    const tr = vthis.closest("tr");
    const exam_seq = tr.querySelector('[data-exam-seq]').dataset.examSeq;
    const page = "/manage/exam/preview";
    const parameter = {
        "exam_seq": exam_seq,
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            lectureExamPreviewDetail(result);
        }else{}
    });

}
// 비디오 미리보기 모달 표시 함수
function lectureVideoPreview(button) {
    const videoUrl = button.closest('.tr_total_lecture').querySelector('.lecture_add_inp_link').value;


    if (!videoUrl) {
        toast('비디오 URL이 입력되지 않았습니다.');
        return;
    }

    // 모달 생성
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'videoPreviewModal';
    modal.setAttribute('tabindex', '-1');
    modal.setAttribute('aria-hidden', 'true');

    modal.innerHTML = `
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">비디오 미리보기</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <video id="previewVideo" controls style="width: 100%;">
                        <source src="${videoUrl}" type="video/mp4">
                        브라우저가 비디오 태그를 지원하지 않습니다.
                    </video>
                </div>
            </div>
        </div>
    `;

    // 기존 모달 제거 후 새 모달 추가
    const existingModal = document.getElementById('videoPreviewModal');
    if (existingModal) {
        existingModal.remove();
    }
    document.body.appendChild(modal);

    // 모달 표시
    const videoModal = new bootstrap.Modal(document.getElementById('videoPreviewModal'));
    videoModal.show();
    // 모달이 닫힐 때 모달 요소 삭제
    document.getElementById('videoPreviewModal').addEventListener('hidden.bs.modal', function (event) {
        this.remove();
    });

    // HLS 지원 확인 및 적용
    const videoElement = document.getElementById('previewVideo');
    if (videoUrl.endsWith('.m3u8')) {
        initHLSPlayer(videoElement, videoUrl);
    } else {
        videoElement.src = videoUrl;
    }
}


//  문제 미리보기2.
function lectureExamPreviewDetail(result){
    var winpopup1 = window.open();
    winpopup1.document.open();
    lectureSetPreviewStyle(winpopup1);
    const sel_exams = result.sel_exams;
        let str = '<h1>[[exam_title]]</h1>';
    const normal_str = lectureGetPreviewStr(sel_exams.normal );
    const similar_str = lectureGetPreviewStr(sel_exams.similar);
    const chlge_str = lectureGetPreviewStr(sel_exams.challenge);
    const chlge_sim_str = lectureGetPreviewStr(sel_exams.challenge_similar);
    if(normal_str[0]){str += normal_str[0]; str = str.replace('[[exam_title]]', normal_str[1]);}
    if(similar_str[0]){str += similar_str[0]; str = str.replace('[[exam_title]]', similar_str[1]);}
    if(chlge_str[0]){str += chlge_str[0]; str = str.replace('[[exam_title]]', chlge_str[1]);}
    if(chlge_sim_str[0]){str += chlge_sim_str[0]; str = str.replace('[[exam_title]]', chlge_sim_str[1]);}
    winpopup1.document.write(str);

    winpopup1.document.write('</body></html>');
    winpopup1.document.close();
}

//  문제 미리보기3
function lectureGetPreviewStr(datas){
    if(!datas) return ['', ''];
    let all_str = '';
    let exam_title = '';
    datas.forEach(function(item){
        console.log(item);
        exam_title = item.exam_title;
        str = `<div class="quiz-question">[[exam_type]] ) [[exam_num]].[[questions]]</div>
                    <div>[[question_img_list]]</div>
                    <div>[[question_file_path]]</div>
                    <div class="quiz-answer-item">[[samples1]]</div>
                    <div>[[sample_file_path1]]</div>
                    <div class="quiz-answer-item">[[samples2]]</div>
                    <div>[[sample_file_path2]]</div>
                    <div class="quiz-answer-item">[[samples3]]</div>
                    <div>[[sample_file_path3]]</div>
                    <div class="quiz-answer-item">[[samples4]]</div>
                    <div>[[sample_file_path4]]</div>
                    <div class="quiz-answer-item">[[samples5]]</div>
                    <div>[[sample_file_path5]]</div>
                    <div class="quiz-commentary-answer">[[answer]]</div>
                `;
        let exam_type = '';
        if(item.exam_type == 'normal'){
            exam_type = '기본';
        }else if(item.exam_type == 'similar'){
            exam_type = '유사';
        }else if(item.exam_type == 'challenge'){
            exam_type = '도전';
        }else if(item.exam_type == 'challenge_similar'){
            exam_type = '도전유사';
        }
        str = str.replace('[[exam_type]]', exam_type);
        str = str.replace('[[exam_num]]', item.exam_num);
        if(item.questions) {
            // <br>을 실제 줄바꿈으로 변환
            const questions = item.questions.replace(/<br>/gi, '<br>');
            str = str.replace('[[questions]]', questions);
        }
        else str = str.replace('[[questions]]', '');

        // Add question_img_list
        let question_img_list = '';
        if(item.question_img_list){
            question_img_list = `<img src="${item.question_img_list}" width="100">`;
        }
        str = str.replace('[[question_img_list]]', question_img_list);

        let question_img = '';
        if(item.question_file_path){
            question_img = `<img src="${item.question_file_path}" width="100">`;
        }

        let commentary = '';
        if(item.commentary){
            commentary = `<div>${item.commentary}</div>`;
        }
        str = str.replace('[[commentary]]', commentary);

        str = str.replace('[[question_file_path]]', question_img);
        if(item.samples.split(';')[0]) str = str.replace('[[samples1]]', `<span class="quiz-answer-item-num ${item.answer == 1 ? 'correct' : ''}">1</span> ${item.samples.split(';')[0]}`);
        else str = str.replace('[[samples1]]', '');
        if(item.samples.split(';')[1]) str = str.replace('[[samples2]]', `<span class="quiz-answer-item-num ${item.answer == 2 ? 'correct' : ''}">2</span> ${item.samples.split(';')[1]}`);
        else str = str.replace('[[samples2]]', '');
        if(item.samples.split(';')[2]) str = str.replace('[[samples3]]', `<span class="quiz-answer-item-num ${item.answer == 3 ? 'correct' : ''}">3</span> ${item.samples.split(';')[2]}`);
        else str = str.replace('[[samples3]]', '');
        if(item.samples.split(';')[3]) str = str.replace('[[samples4]]', `<span class="quiz-answer-item-num ${item.answer == 4 ? 'correct' : ''}">4</span> ${item.samples.split(';')[3]}`);
        else str = str.replace('[[samples4]]', '');
        if(item.samples.split(';')[4]) str = str.replace('[[samples5]]', `<span class="quiz-answer-item-num ${item.answer == 5 ? 'correct' : ''}">5</span> ${item.samples.split(';')[4]}`);
        else str = str.replace('[[samples5]]', '');

        if(item.sample_file_path1){
            str = str.replace('[[sample_file_path1]]', `<img src="${item.sample_file_path1}" width="100">`);
        } else{
            str = str.replace('[[sample_file_path1]]', '');
        }
        if(item.sample_file_path2){
            str = str.replace('[[sample_file_path2]]', `<img src="${item.sample_file_path2}" width="100">`);
        } else{
            str = str.replace('[[sample_file_path2]]', '');
        }
        if(item.sample_file_path3){
            str = str.replace('[[sample_file_path3]]', `<img src="${item.sample_file_path3}" width="100">`);
        } else{
            str = str.replace('[[sample_file_path3]]', '');
        }
        if(item.sample_file_path4){
            str = str.replace('[[sample_file_path4]]', `<img src="${item.sample_file_path4}" width="100">`);
        } else{
            str = str.replace('[[sample_file_path4]]', '');
        }
        if(item.sample_file_path5){
            str = str.replace('[[sample_file_path5]]', `<img src="${item.sample_file_path5}" width="100">`);
        } else{
            str = str.replace('[[sample_file_path5]]', '');
        }

        if(item.answer) str = str.replace('[[answer]]',
            `
                <div class="answer-badge">
                    <span class="answer-text">정답</span>
                    <strong class="answer-number">${item.answer}번</strong>
                </div>
                <div class="answer-commentary">${item.commentary || ''}</div>`);
        else str = str.replace('[[answer]]', '');

        all_str += str;
    });
    return [all_str, exam_title];
}

// 미리보기 style 적용.
function lectureSetPreviewStyle(winpopup1){
    winpopup1.document.write('<style>');
    winpopup1.document.write(`
        body {
            font-family: 'Noto Sans KR', sans-serif;
            background: #f8f9fa;
            padding: 2rem;
            line-height: 1.6;
            color: #343a40;
        }

        h1 {
            color: #1a73e8;
            font-size: 2rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
        }

        .quiz-question {
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            white-space: pre-wrap;
            line-height: 1.8;
        }

        .quiz-question br {
            content: "";
            display: block;
            margin: 0.8em 0;
        }

        .quiz-answer-item {
            background: #fff;
            padding: 1rem 1.5rem;
            margin: 0.5rem 0;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .quiz-answer-item:hover {
            background: #f8f9fa;
        }

        .quiz-answer-item-num {
            display: inline-flex;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #fff;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #495057;
            border: 2px solid #dee2e6;
            flex-shrink: 0;
        }

        .quiz-answer-item-num.correct {
            background: #4CAF50;
            color: white;
            border-color: #4CAF50;
            box-shadow: 0 2px 4px rgba(76,175,80,0.3);
        }

        .quiz-answer-item:hover {
            background: #f8f9fa;
        }

        .quiz-answer-item:has(.correct):hover {
            background: #E8F5E9;
        }

        .quiz-commentary-answer {
            position: relative;
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            margin: 3rem 0 2rem;
            border: 2px solid #4CAF50;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .quiz-commentary-answer::before {
            content: '정답 해설';
            position: absolute;
            top: -15px;
            left: 20px;
            background: #4CAF50;
            color: white;
            padding: 4px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }

        .answer-badge {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .answer-text {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #4CAF50;
            color: white;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            font-weight: 700;
            font-size: 1.2rem;
            box-shadow: 0 2px 4px rgba(76,175,80,0.3);
        }

        .answer-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2E7D32;
            display: flex;
            align-items: center;
        }

        .answer-commentary {
            background: #F1F8E9;
            padding: 1.5rem;
            border-radius: 8px;
            color: #33691E;
            font-size: 1.1rem;
            line-height: 1.8;
            border-left: 4px solid #4CAF50;
        }

        img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1rem 0;
        }

        [class^="quiz-"] img {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
    `);
    winpopup1.document.write('</style>');
}

// 인터렉트 미리보기
function lectureInteractivePreview(vthis) {
    const tr = vthis.closest("tr");
    const interactive_seq = tr.querySelector('[data-interactive-seq]').dataset.interactiveSeq;
    const page = "/manage/lecture/interactive/preview";

    const modal = document.querySelector('#modal_interactive_preview');
    const iframe = modal.querySelector('#ifrm_interactive');
    iframe.src = 'http://interactive.popcorn-edu.com';
    const parameter = {
        'interactive_seq': interactive_seq,
    };
    queryFetch(page, parameter, function(result) {
        if((result.resultCode||'') == 'success'){
            lectureInteractivePreviewDetail(result);
        }else{}
    });
}
function lectureInteractivePreviewDetail(result) {
    const interactive_json = result.interactive_json;
    const modal = document.querySelector('#modal_interactive_preview');
    const iframe = modal.querySelector('#ifrm_interactive');
    setTimeout(function(){
            const JSONData = JSON.parse(interactive_json);
            if (iframe) {
                iframe.contentWindow.postMessage(JSON.stringify(JSONData), '*');
            }
        const myModal = new bootstrap.Modal(document.getElementById('modal_interactive_preview'), {
            keyboard: false
        });
        myModal.show();
    }, 2000);

}

function lectureAddExamInsert(vthis) {
    const lecture_detail_name = document.querySelectorAll('[data="#강의명"]:not([hidden])');

    // 부트스트랩 모달 생성
    const modalHtml = `
        <div class="modal fade" id="examListModal" tabindex="-1" aria-labelledby="examListModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="examListModalLabel">문제 일괄 등록</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div>
                            <p>임시 기능입니다. 중복해서 들어가기때문에 무조건 한번만 클릭해주세요. 문제 등록 후 모달을 닫아주세요.</p>
                        </div>
                        <div class="row m-2 gap-1">
                            <select class="col form-select" data-subject-code="">
                                <option value="">과목전체</option>
                                <option value="87">국어</option>
                                <option value="139">영어</option>
                                <option value="88">수학</option>
                                <option value="89">과학</option>
                                <option value="90">사회</option>
                                <option value="268">한자</option>
                            </select>
                            <select class="col form-select" data-grade-code="">
                                <option value="">학년전체</option>
                                <option value="92">1학년</option>
                                <option value="93">2학년</option>
                                <option value="94">3학년</option>
                                <option value="95">4학년</option>
                                <option value="96">5학년</option>
                                <option value="97">6학년</option>
                                <option value="239">공통</option>
                            </select>
                            <select class="col form-select" data-semester-code="">
                                <option value="">학기전체</option>
                                <option value="98">1학기</option>
                                <option value="99">2학기</option>
                                <option value="100">여름방학</option>
                                <option value="101">겨울방학</option>
                                <option value="240">공통</option>
                            </select>
                            <select class="col form-select" data-evaluation-code="">
                                <option value="">평가분류</option>
                                <option value="41">단원평가</option>
                                <option value="42">한자급수시험</option>
                                <option value="269">기본문제</option>
                            </select>
                        </div>
                        ${
                            Array.from(lecture_detail_name).map(item => {
                                return `<div class="lecture-detail-item d-flex align-items-center justify-content-between p-2 mb-2 border rounded">
                                            <input type="text" class="rounded lecture-detail-name w-50 p-2 border border-primary shadow-sm" value="${item.querySelector('.lecture_detail_name').value}" placeholder="Enter lecture detail name">
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="lectureAddExamListDelete(this)">삭제</button>
                                        </div>`;
                            }).join('')
                        }
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">닫기</button>
                        <button type="button" class="btn btn-primary" id="btn_exam_insert" onclick="lectureAddBatchExamInsert()">등록</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // 기존 모달이 있다면 제거
    const existingModal = document.getElementById('examListModal');
    if (existingModal) {
        existingModal.remove();
    }

    // 모달 추가 및 표시
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('examListModal'));
    modal.show();
}

function lectureAddBatchExamInsert(){
    const lecture_detail_name = document.querySelectorAll('.lecture-detail-item');
    const subject_seq = document.querySelector('[data-subject-code]')?.value;
    const grade_seq = document.querySelector('[data-grade-code]')?.value;
    const semester_seq = document.querySelector('[data-semester-code]')?.value;
    const evaluation_seq = document.querySelector('[data-evaluation-code]')?.value;
    const title = document.querySelector('[data-exam-title]')?.value;
    const lecture_detail_name_value = Array.from(lecture_detail_name).map(item => item.querySelector('.lecture-detail-name').value);
    const page = "/manage/exam/insert";

    if (!subject_seq) {
        alert('과목을 선택해주세요.');
        return;
    }
    if (!grade_seq) {
        alert('학년을 선택해주세요.');
        return;
    }
    if (!semester_seq) {
        alert('학기를 선택해주세요.');
        return;
    }
    if (!evaluation_seq) {
        alert('평가분류를 선택해주세요.');
        return;
    }
    if (lecture_detail_name_value.length === 0) {
        alert('강의 세부 항목을 입력해주세요.');
        return;
    }

    // Confirm before proceeding with registration
    if (confirm('문제 일괄 등록 하시겠습니까?')) {
        lecture_detail_name_value.forEach((name, index) => {
            const parameter = {
                subject_seq: subject_seq,
                grade_seq: grade_seq,
                semester_seq: semester_seq,
                evaluation_seq: evaluation_seq,
                title: name
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    toast('문제가 등록 되었습니다.');
                    document.querySelectorAll('.lecture-detail-item')[index].style.backgroundColor = '#a3cfbb';
                }else{
                    toast('문제 등록 실패');
                }
            });
        });
    }
}
function lectureAddExamListDelete(vthis){
    const div = vthis.closest("div");
    if (div) {
        div.remove();
    } else {
        console.error("Cannot read properties of null (reading 'remove')");
    }
}

function lectureAddExamBatchInsert(){
    if (confirm('문제 일괄 등록을 진행하시겠습니까?\n문제일괄등록은 초기작업시에만 사용해주세요.')) {
        const lecture_detail_seqs = document.querySelectorAll('#lecture_add_tby_total_lecture .lecture_detail_seq');
        lecture_detail_seqs.forEach(item => {
            const tr = item.closest('.tr_total_lecture');
            if (tr) { // tr이 null이 아닌지 확인
                const newTr = tr.cloneNode(true); // 기존 tr을 복제
                newTr.querySelector('.lecture_detail_seq').value = ""; // 새 tr의 seq 초기화
                newTr.querySelector('.lecture_detail_type').value = "exam_solving"; // 유형 설정
                newTr.querySelector('.lecture_detail_type_str').innerText = "문제풀기"; // 유형 텍스트 설정
                newTr.querySelector('.lecture_add_inp_link').value = ""; // 링크 초기화
                newTr.querySelector('.lecture_add_inp_link').disabled = true; // 링크 비활성화
                newTr.querySelector('.lecture_detail_hour').hidden = true; // 시간 숨기기
                newTr.querySelector('.lecture_detail_minute').hidden = true; // 분 숨기기
                newTr.querySelector('.colon').hidden = true; // 콜론 숨기기
                newTr.querySelector('[data-btn-upload]').innerText = "문제연결"; // 버튼 텍스트 변경
                newTr.querySelector('.academy_number_file').placeholder = " 문제가 연결되면 타이틀이 표시됩니다."; // 플레이스홀더 설정
                newTr.querySelector('.lecture_detail_count_day').hidden = true; // 일수 숨기기
                newTr.querySelector('[data-btn-exam-connect]').hidden = false; // 문제 연결 버튼 표시

                lectureAddTr(tr, newTr); // 기존 tr 뒤에 새 tr 추가
            } else {
                console.error("Cannot read properties of null (reading 'cloneNode')");
            }
        });
    }
}
</script>
@endif
@endsection
