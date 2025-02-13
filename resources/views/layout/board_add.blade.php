<!-- include summernotecss/js -->
<style>
    .note-modal-backdrop {
        display: none !important;
    }

    .note-modal-footer {
        min-height: 50px;
    }

    .row{
        --bs-gutter-x: 0;
    }
    .modal-backdrop{
        display: none;
    }
    .h-98{
        height: 98px;
    }
    .note-placeholder{
        font-size:24px;
        padding-left: 18px;
    }
</style>

{{-- 게시판 에디터 / CDN or 외부 주소가 불안할시 내부 소스로 복사 --}}
<script src="//code.jquery.com/jquery-latest.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/lang/summernote-ko-KR.js"></script>

{{-- 드롭존 / CDN or 외부 주소가 불안할시 내부 소스로 복사 --}}
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />


<div>
    <div class="py-lg-5"></div>
    <div class="pt-lg-4"></div>
</div>
{{-- 상단 --}}
<div id="board_div_wr_top" class="d-flex">
    <h5 hidden>
        <span class="board_name"></span>
        <span> 글쓰기</span>
    </h5>
    <div class="d-flex gap-2 align-items-center div_title">
        <h1 id="learncal_h1_student_name text-sb-42px" class="m-0 cfs-1">
            <button data-btn--back="" class="btn p-0 row mx-0" onclick="boardAddClose();">
                <img src="https://sdang.acaunion.com/images/black_arrow_left_tail.svg" width="65" class="px-0">
            </button>
            <span class="text-sb-42px">@yield('write', '글쓰기')</span>
        </h1>
    </div>

    <input type="hidden" id="board_inp_wr_top_name" hidden>
    <div hidden>
    <div class="flex-wrap btn-group use_faq use_event" role="group"
        aria-label="Basic checkbox toggle button group" id="boardadd_div_radio_type_top" style=" max-width: 1340px; "
        hidden>

        <input type="radio" name="board_readio_top" class="btn-check" id="board_radio_notice" autocomplete="off"
            onchange="chgBoardName('notice')">
        <label class="btn btn-outline-primary hpx-40 rounded-0" for="board_radio_notice">공지사항</label>

        <input type="radio" name="board_readio_top" class="btn-check" id="board_radio_faq" autocomplete="off"
            onchange="chgBoardName('faq')">
        <label class="btn btn-outline-primary hpx-40 rounded-0" for="board_radio_faq">자주 묻는 질문</label>

        <input type="radio" name="board_readio_top" class="btn-check" id="board_radio_event" autocomplete="off"
            onchange="chgBoardName('event')">
        <label class="btn btn-outline-primary hpx-40 rounded-0" for="board_radio_event">이벤트</label>
    </div>
    </div>
</div>
{{-- 게시판 삽입 내용 --}}
<div id="board_div_wr_form">
    <input type="hidden" id="boardadd_inp_board_seq">
    {{-- 중요도 / 공지사항, 이벤트 --}}
    <div class="h-center gap-2 justify-content-end use_notice use_event mb-4 pb-2" hidden>
            <label class="checkbox">
                <input type="checkbox" class="board_idx" id="board_important">
                <span class="text-center"></span>
              </label>
                <label class="form-check-label" for="board_important">중요 게시물로 등록합니다.</label>
    </div>
    <div class="scale-bg-black w-100" style="height:2px"></div>
    <div class="row flex-wrap w-100 border-top-2 border-dark">

        {{-- 분류 / 공지사항, F&A, 이벤트--}}
        <div class="col-12 row use_notice use_faq use_event" hidden>
            <div class="h-98 text-m-24px p-4 text-center col-2 border h-center">분류</div>
            <div class="p-2 h-center col border p-4 h-98">
                <p class="card-text placeholder-glow loding_place2" hidden>
                    <span class="placeholder col-12"></span>
                </p>

                <div class="h-center gap-4">
                @if(!empty($board_top_codes))
                    @foreach ($board_top_codes as $board_top_code)
                    <div class="h-center gap-2">
                        <label class="radio">
                            <input type="radio" data-board-top-code="{{ $board_top_code->id }}"
                            name="inp_board_top_code" id="inp_board_top_code_{{ $board_top_code->id }}" >
                            <span class=""></span>
                          </label>
                          <label for="inp_board_top_code_{{ $board_top_code->id }}" class="text-m-24px">{{ $board_top_code->code_name }}</label>
                    </div>
                    @endforeach
                @endif
                </div>
            </div>
        </div>

        {{-- 작성자 / 공지사항, F&A, 이벤트 --}}
        <div class="col-6 row use_notice use_faq use_event use_support" hidden>
            <div class="h-98 text-m-24px p-4 text-center col-4 border h-center">
                <span class="use_notice use_faq use_event" hidden>작성자</span>
                <span class="use_support" hidden>학부모명(작성자)</span>
            </div>
            <div class="px-4 text-center col border d-flex gap-1 bg-white">
                <p class="card-text placeholder-glow loding_place2" hidden>
                    <span class="placeholder col-12"></span>
                </p>
                <input type="text" class="writer_name text-m-24px border-0 bg-white col" value="{{ session()->get('teach_name') }}" disabled>
                <input type="hidden" class="writer_seq" value="{{ session()->get('teach_seq') }}">
                <input type="hidden" class="writer_type" value="teacher">
                <button class="btn btn-sm btn-secondary use_support px-2" onclick="boardAddSearchParentModal();" hidden>검색</button>
            </div>
        </div>

        {{-- 자녀명 / 응원메시지--}}
        <div class="col-6 row use_support" hidden>
            <div class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">자녀명</div>
            <div class="p-2 text-center col border d-flex gap-1 bg-white">
                <p class="card-text placeholder-glow loding_place2" hidden>
                    <span class="placeholder col-12"></span>
                </p>
                <input type="text" class="student_name form-control col" value="">
                <input type="hidden" class="student_seq">
                <button class="btn btn-sm btn-secondary px-2" onclick="boardAddSearchStudentModal();">검색</button>
            </div>
        </div>

        {{-- 게시기간 / 공지사항, 이벤트 --}}
        <div class="col-6 row use_notice use_event use_support div_range_date" hidden>
            <div class="h-98 text-m-24px p-4 text-center col-4 border h-center">
                <span class="use_notice use_support" hidden>게시기간</span>
                <span class="use_event" hidden>이벤트기간</span>
            </div>
            <div class="px-4 h-center col border">
                <p class="card-text placeholder-glow loding_place2" hidden>
                    <span class="placeholder col-12"></span>
                </p>
                <div class="h-center gap-1">
                    <input type="date" class="use_event col form-control use_notice use_support start_date border-0 text-m-24px" value="{{ date('Y-m-d') }}">
                    <span class="use_notice use_event use_support">~</span>
                    <input type="date" class="use_event use_notice col form-control use_support end_date border-0 text-m-24px"
                        value="{{ date('Y-m-d') }}">
                </div>
            </div>
        </div>

        {{-- 대상자 2칸 / 공지사항, F&A, 이벤트 --}}
        <div class="col-12 row use_notice use_faq" hidden>
            <div class="h-98 text-m-24px p-4 text-center col-2 border h-center">대상자</div>
            <div class="px-4 col border h-center">
                <div class="h-center gap-4">
                    <div class="h-center gap-2">
                        <label class="radio">
                            <input type="radio" class="is_region"
                            name="radio_target_user" id="board_target_region" >
                            <span class=""></span>
                          </label>
                          <label for="board_target_region" class="text-m-24px">지역본부</label>
                    </div>
                    <div class="h-center gap-2">
                        <label class="radio">
                            <input type="radio" class="is_team"
                            name="radio_target_user" id="board_target_team" >
                            <span class=""></span>
                          </label>
                          <label for="board_target_team" class="text-m-24px">팀</label>
                    </div>
                    <div class="h-center gap-2">
                        <label class="radio">
                            <input type="radio" class="is_teacher"
                            name="radio_target_user" id="board_target_teacher" >
                            <span class=""></span>
                          </label>
                          <label for="board_target_teacher" class="text-m-24px">관리 선생님</label>
                    </div>
                    <div class="h-center gap-2">
                        <label class="radio">
                            <input type="radio" class="is_teacher2"
                            name="radio_target_user" id="board_target_teacher2" >
                            <span class=""></span>
                          </label>
                          <label for="board_target_teacher2" class="text-m-24px">상담 선생님</label>
                    </div>
                    <div class="h-center gap-2">
                        <label class="radio">
                            <input type="radio" class="is_pt_th"
                            name="radio_target_user" id="board_target_student" >
                            <span class=""></span>
                          </label>
                          <label for="board_target_student" class="text-m-24px">학부모/선생</label>
                    </div>

                    <div class="h-center gap-2">
                        <label class="radio">
                            <input type="radio" class="is_parent"
                            name="radio_target_user" id="board_target_student" >
                            <span class=""></span>
                          </label>
                          <label for="board_target_student" class="text-m-24px">학부모</label>
                    </div>

                    <div class="h-center gap-2">
                        <label class="radio">
                            <input type="radio" class="is_student"
                            name="radio_target_user" id="board_target_student" >
                            <span class=""></span>
                          </label>
                          <label for="board_target_student" class="text-m-24px">학생</label>
                    </div>
                </div>
            </div>
        </div>

        {{-- 제목 / 모두 --}}
        <div class="col-12 row use_notice use_faq use_event use_qna use_sdqna use_learning" hidden>
            <div class="h-98 text-m-24px px-4 text-center col-2 border h-center">제목</div>
            <div class="p-2 h-center col border">
                <p class="card-text placeholder-glow loding_place2" hidden>
                    <span class="placeholder col-12"></span>
                </p>
                <input type="text" class="form-control title border-0 text-m-24px" placeholder="제목을 입력하세요">
            </div>
        </div>

        {{-- 구분 / 학습자료 --}}
        <div class="col-6 row use_learning" hidden>
            <div class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">구분</div>
            <div class="p-2 text-center col border">
                {{-- label / input 초등 --}}
                <input type="radio" name="board_readio_main_code" class="btn-check main_code" id="board_radio_learning_elementary"
                    autocomplete="off" onchange="chgBoardName('elementary')"
                    {{ $_COOKIE['main_code'] != 'middle' ? 'checked' : '' }} disabled >
                <label class="btn btn-outline-primary hpx-40 rounded-0" for="board_radio_learning_elementary">초등</label>
                {{-- label / input 중등 --}}
                <input type="radio" name="board_readio_main_code" class="btn-check main_code" id="board_radio_learning_middle"
                    autocomplete="off" onchange="chgBoardName('middle')"
                    {{ $_COOKIE['main_code'] == 'middle' ? 'checked' : '' }} disabled >
                <label class="btn btn-outline-primary hpx-40 rounded-0" for="board_radio_learning_middle">중등</label>
            </div>
        </div>

        {{-- 과목 / 학습자료 --}}
        <div class="col-6 row use_learning" hidden>
            <div class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">과목</div>
            <div class="p-2 text-center col border">
                {{-- Undefined variable: subject_codes 체크 피할려면 --}}
                @if(!empty($subject_codes))
                @foreach ($subject_codes as $subject_code)
                    <input type="radio" name="board_readio_subject" class="btn-check subject" id="board_radio_learning_{{ $subject_code->id }}"
                        autocomplete="off" code_seq="{{ $subject_code->id }}">
                    <label class="btn btn-outline-primary hpx-40 rounded-0" for="board_radio_learning_{{ $subject_code->id }}">
                        {{ $subject_code->code_name }}
                    </label>
                @endforeach
                @endif
            </div>
        </div>

        {{-- 학년 / 학습자료 --}}
        <div class="col-6 row use_learning" hidden>
            <div class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">학년</div>
            <div class="p-2 text-center col border">
                @if(!empty($grade_codes))
                @foreach ($grade_codes as $grade_code)
                    <input type="radio" name="board_readio_grade" class="btn-check grade" id="board_radio_learning_{{ $grade_code->id }}"
                        autocomplete="off" code_seq="{{ $grade_code->id }}">
                    <label class="btn btn-outline-primary hpx-40 rounded-0" for="board_radio_learning_{{ $grade_code->id }}">
                        {{ $grade_code->code_name }}
                    </label>
                @endforeach
                @endif
            </div>
        </div>

        {{-- 학기 / 학습자료 --}}
        <div class="col-6 row use_learning" hidden>
            <div class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">학기</div>
            <div class="p-2 text-center col border">
                @if(!empty($semester_codes))
                @foreach ($semester_codes as $semester_code)
                    <input type="radio" name="board_readio_semester" class="btn-check semester" id="board_radio_learning_{{ $semester_code->id }}"
                        autocomplete="off" code_seq="{{ $semester_code->id }}">
                    <label class="btn btn-outline-primary hpx-40 rounded-0" for="board_radio_learning_{{ $semester_code->id }}">
                        {{ $semester_code->code_name }}
                    </label>
                @endforeach
                @endif
            </div>
        </div>

        {{-- 자료분류 / 학습자료 --}}
        <div class="col-6 row use_learning" hidden>
            <div class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">자료분류</div>
            <div class="p-2 text-center col border d-flex align-items-center justify-content-center gap-1">
                {{-- $board_top_codes --}}
                @if(!empty($board_top_codes))
                @foreach ($board_top_codes as $board_top_code)
                    <input type="radio" name="board_readio_top_code" class="btn-check top_code" id="board_radio_learning_{{ $board_top_code->id }}"
                        autocomplete="off" code_seq="{{ $board_top_code->id }}">
                    <label class="btn btn-outline-primary hpx-40 rounded-0" for="board_radio_learning_{{ $board_top_code->id }}">
                        {{ $board_top_code->code_name }}
                    </label>
                @endforeach
                @endif
            </div>
        </div>

        {{-- 대단원명, 중단원명 / 학습자료 / select  --}}
        <div class="col-6 row use_learning" hidden>
            <div>
                {{-- 대단원명 --}}
                <div class="row">
                    <div class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">@yield('major_unit', '대단원')명</div>
                    <div class="p-2 text-center col border">
                        <select class="w-100 hpx-30 major_unit">
                            <option value="">대단원을 선택하세요</option>
                            @if(!empty($major_unit_codes))
                            @foreach ($major_unit_codes as $major_unit_code)
                                <option value="{{ $major_unit_code->id }}">{{ $major_unit_code->code_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                {{-- 중단원명 --}}
                <div class="row">
                    <div class="bg-light p-2 text-center col-4 border fw-bold d-flex align-items-center justify-content-center">@yield('medium_unit', '대단원')명</div>
                    <div class="p-2 text-center col border">
                        <select class="w-100 hpx-30 medium_unit">
                            <option value="">중단원을 선택하세요</option>
                            @if(!empty($medium_unit_codes))
                            @foreach ($medium_unit_codes as $medium_unit_code)
                                <option value="{{ $medium_unit_code->id }}">{{ $medium_unit_code->code_name }}</option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- 썸네일 2칸 / 이벤트 --}}
        <div class="col-12 row use_event" hidden>
            <div class="col-2 bg-light p-2 text-center  border fw-bold d-flex align-items-center justify-content-center">리스트 썸네일</div>
            <div class="p-2 text-center col border">
                <div class="mt-2 " id="boardadd_div_thumbnails">
                    <div class="copy_btn_thumbnail btn-group align-middle div_bdupfile_delete thumbnail" hidden>
                        <div class="d-flex align-items-center px-2 border">썸네일1.jpg</div>
                        <button class="btn btn-outline-danger">X</button>
                    </div>
                </div>
                <span class="text-primary">아래쪽에 이미지를 드래그 하거나, 아래쪽을 마우스로 클릭해주세요.</span>
                <form action="/manage/boardwrite/fileupload" class="dropzone" id="my-dropzone2">
                </form>
            </div>
        </div>

        {{-- 작성일자 / Q&A --}}
        <div class="col-6 row use_qna use_sdqna" hidden>
            <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">작성자</div>
            <div class="p-2 text-center col border">
                <p class="card-text placeholder-glow loding_place2" hidden>
                    <span class="placeholder col-12"></span>
                </p>
                <input type="text" class="writer_name form-control" value="" disabled>
            </div>
        </div>

        {{-- 이메일 / Q&A --}}
        <div class="col-6 row use_qna use_sdqna" hidden>
            <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">이메일</div>
            <div class="p-2 text-center col border">
                <p class="card-text placeholder-glow loding_place2" hidden>
                    <span class="placeholder col-12"></span>
                </p>
                <input type="text" class="writer_email form-control" value="" disabled>
            </div>
        </div>

        {{-- 연락처 / Q&A, 응원메시지 --}}
        <div class="col-6 row use_qna use_sdqna use_support" hidden>
            <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">연락처</div>
            <div class="p-2 text-center col border">
                <p class="card-text placeholder-glow loding_place2" hidden>
                    <span class="placeholder col-12"></span>
                </p>
                <input type="text" class="writer_phone form-control" value="" disabled>
            </div>
        </div>

        {{-- 학교 - 학년 / 응원메시지--}}
        <div class="col-6 row use_support" hidden>
            <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">학교 / 학년</div>
            <div class="p-2 text-center col border row gap-2">
                <input type="text" class="school_name form-control col">
                <input type="text" class="grade form-control col">
            </div>
        </div>


        {{-- 작성일자 / Q&A --}}
        <div class="col-6 row use_qna use_sdqna use_support" hidden>
            <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                <span class="use_qna use_sdqna" hidden>작성일자</span>
                <span class="use_support" hidden>등록일자</span>
            </div>
            <div class="p-2 text-center col border d-flex gap-1 bg-white">
                <p class="card-text placeholder-glow loding_place2" hidden>
                    <span class="placeholder col-12"></span>
                </p>
                <input type="date" class="created_at form-control col" value="{{ date('Y-m-d') }}" disabled>
                <input type="time" class="created_at_time form-control col" value="" disabled>
            </div>
        </div>

        {{-- 첨부파일_학생용 / 학습 Q&A --}}
        <div class="col-12 row use_sdqna" hidden>
            <div class="h-98 text-m-24px col-2 bg-light p-2 text-center  border d-flex align-items-center justify-content-center">첨부파일</div>
            <div class="p-2 text-center col border">
                <div class="mt-2 " id="boardadd_div_uploadfiles_st">
                    <div class="copy_btn_uploadfile_st btn-group align-middle div_bdupfile_delete thumbnail" hidden>
                        <div class="d-flex align-items-center px-2 border text-primary cursor-pointer">#자료</div>
                        <button class="btn btn-outline-danger">X</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 문의내용 / Q&A --}}
        <div class="col-12 row use_qna use_sdqna" hidden>
            <div class="col-12 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">
                <span class="use_qna" hidden>문의내용</span>
                <span class="use_sdqna" hidden>질문내용</span>
            </div>
            <div class="p-2 text-center col border">
                <p class="card-text placeholder-glow loding_place2" hidden>
                    <span class="placeholder col-12"></span>
                </p>
                <div style="min-height: 100px;" class="content0"></div>
            </div>
        </div>

        {{-- 소속 / 응원메시지 --}}
        <div class="col-6 row use_support div_region" hidden>
            <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">소속</div>
            <div class="p-2 text-center col border">
                <p class="card-text placeholder-glow loding_place2" hidden>
                    <span class="placeholder col-12"></span>
                </p>
                <input type="text" class="region_name form-control" value="">
                <input type="hidden" class="region_seq">
                <input type="hidden" class="team_code">
            </div>
        </div>

        {{-- 게시상태 / 응원메시지 --}}
        <div class="col-6 row p-0 use_support use_learning div_is_use" hidden>
            <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">@yield('is_use', '게시상태')</div>
            <div class="p-2 text-center col border">
                <p class="card-text placeholder-glow loding_place2" hidden>
                    <span class="placeholder col-12"></span>
                </p>
                <select class="is_use w-100 hpx-30">
                    <option value="Y">@yield('is_use_Y', '게시중')</option>
                    <option value="N"> @yield('is_use_N', '게시종료') </option>
                </select>
            </div>
        </div>

        {{-- 답변하기 / Q&A --}}
        <div class="col-12 row use_qna use_sdqna" hidden>
            <div class="col-12 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">답변하기</div>
        </div>

        {{-- 게시내용 / 응원메시지 --}}
        <div class="col-12 row use_support" hidden>
            <div class="col-12 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">게시내용</div>
        </div>
        {{-- 에디터 게시판 내용 --}}
        <div class="col-12 row">
            <div class="text-m-24px col-2 p-4 border d-flex">내용</div>
            <div class="p-0 text-center col border">
                <form method="post">
                    <textarea id="board_content" name="editordata" class="text-m-24px"></textarea>
                </form>
            </div>
        </div>

        {{-- 첨부파일 2칸 / 공지사항, F&A, 이벤트 --}}
        <div class="col-12 row use_notice use_faq use_event use_sdqna use_learning" hidden>
            <div class="text-m-24px col-2 p-4 d-flex border">첨부파일</div>
            <div class="p-2 text-center col border">
                <div class="mt-2 " id="boardadd_div_uploadfiles">
                    <div class="copy_btn_uploadfile btn-group align-middle div_bdupfile_delete" hidden>
                        <div class="d-flex align-items-center px-2 border">첨부파일1.jpg</div>
                        <button class="btn btn-outline-danger">X</button>
                    </div>
                </div>
                <span class="text-primary">아래쪽에 파일을 드래그 하거나, 아래쪽을 마우스로 클릭해주세요.</span>
                <form action="/manage/boardwrite/fileupload" class="dropzone" id="my-dropzone1">
                </form>
            </div>
        </div>

        {{-- 사용자권한 / 학습자료 select --}}
        <div class="col-6 row p-0 use_learning" hidden>
            <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">사용자권한</div>
            <div class="p-2 text-center col border">
                @if(!empty($user_groups))
                <select class="w-100 hpx-30 " onchange="boardAddSettingGroupSel(this)">
                    <option value="">선택하세요</option>
                    @foreach ($user_groups as $user_group)
                        @if($user_group->group_type != 'parent' && $user_group->main_code != $_COOKIE['main_code'])
                            @continue
                        @endif
                        <option value="{{ $user_group->id }}">{{ $user_group->group_name }}</option>`
                    @endforeach
                </select>
                <div id="board_div_group_sel" class="d-flex gap-2 py-2">
                    {{-- 그룹 선택 태그 $user_groups --}}
                    @foreach ($user_groups as $user_group)
                    <span class="badge p-2 align-items-center text-primary-emphasis bg-primary-subtle rounded-pill seq{{ $user_group['id'] }}" hidden>
                        <input type="hidden" class="group_seq" value="{{ $user_group['id'] }}">
                        <span class="px-1">{{ $user_group['group_name'] }} </span>
                        <a href="javascript:boardAddSettingGroupDel('{{ $user_group['id'] }}')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                              </svg>
                        </a>
                      </span>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        {{-- 공개범위 / 학습자료 select --}}
        <div class="col-6 row p-0 use_learning div_open_size" hidden>
            <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">공개범위</div>
            <div class="p-2 text-center col border">
                <select class="w-100 hpx-30 open_size">
                    <option value="">전체공개</option>
                    <option value="part">부분공개</option>
                    <option value="private">비공개</option>
                </select>
            </div>
        </div>
        {{--
        <div class="col-6 row p-0">
            <div class="col-4 bg-light p-2 text-center border fw-bold d-flex align-items-center justify-content-center">이름</div>
            <div class="p-2 text-center col border">

            </div>
        </div>
        --}}
    </div>
</div>

{{-- 닫기, 저장/수정, 삭제,  --}}
<div>
    <div class="py-lg-4"></div>
    <div class="py-lg-3"></div>
</div>
<div class="d-flex justify-content-center align-items-center col-12 gap-2" style="bottom: 0">
    <button type="button" onclick="boardAddInsert();"
    class="btn-ms-primary text-b-24px rounded-pill scale-text-white">등록하기</button>
    <button class="col-1 btn btn-outline-danger" onclick="boardAddDelete();" id="boardadd_btn_boarddelte" hidden>삭제</button>
</div>

{{-- 모달 / 학부모 찾기 --}}
<div class="modal fade" id="boardadd_modal_search_parent" tabindex="-1" aria-hidden="true"
style="display: none;z-index:4;background:#00000096">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">학부모 찾기</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick=""></button>
            </div>
            <div class="modal-body">
                <div class="w-100 d-flex gap-2">
                    <input class="search_str col" onkeyup="if(event.keyCode == 13) this.nextElementSibling.click();">
                    <button class="btn btn-primary" onclick="boardAddSearchParent(this);">
                        <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden></span>
                        검색</button>
                </div>

                <table class="table">
                    <tbody class="tby_parents">
                        <tr class="copy_tr_parents cursor-pointer" onclick="boardAddClickTrParent(this);">
                            <td class="parent_name"></td>
                            <td class="parent_id"></td>
                            <td class="parent_phone"></td>
                            <input type="hidden" class="parent_seq">
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                    onclick="">닫기</button>
                <button type="button" class="btn btn-primary" onclick="boardAddSelectParent();">선택</button>
            </div>
        </div>
    </div>
</div>

{{-- 모달 / 자녀 정보 가져오기 / 학부모 찾기 모달과 같은 형식 --}}
<div class="modal fade" id="boardadd_modal_search_student" tabindex="-1" aria-hidden="true"
style="display: none;z-index:4;background:#00000096">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">자녀 선택</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick=""></button>
            </div>

            <div class="modal-body">

                <table class="table">
                    <tbody class="tby_students">
                        <tr class="copy_tr_students cursor-pointer" onclick="boardAddClickTrStudent(this);">
                            <td class="student_name"></td>
                            <td class="student_id"></td>
                            <td class="student_phone"></td>
                            <td class="school_name"></td>
                            <input type="hidden" class="student_seq">
                            <input type="hidden" class="grade">
                            <input type="hidden" class="region_seq">
                            <input type="hidden" class="region_name">
                            <input type="hidden" class="team_code">
                            <input type="hidden" class="team_name">
                        </tr>
                    </tbody>
                </table>
                {{-- 자녀 목록이 없습니다. --}}
                <div class="text-center mb-3" id="div_student_empty" hidden>
                    <span class="text-primary">자녀 목록이 없습니다.</span>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                    onclick="">닫기</button>
                <button type="button" class="btn btn-primary" onclick="boardAddSelectStudent();">
                    <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden></span>
                    선택</button>
            </div>
        </div>
    </div>
</div>


<script>
    var board_tag = null;
    $(document).ready(function() {
        //드롭존 정의.
        boardDropZoneInit();

        //에디터 정의.
        boardEditorInit();

        // 게시판 응원메시지 설정. // 응원메시지 타입이 아니면 내부에서 return
        boardAddSettingSupport();
        boardAddSettingLearning();
    });

    function boardAddClose() {
        const board_div_add = document.querySelector("#board_div_add");
        board_div_add.hidden = true;
        const board_main = document.querySelectorAll('[data-board-main]');
        board_main.forEach(element => {
            element.hidden = false;
        });
        //초기화
        boardAddClear();
    }
    //게시판 대상자 전체 클릭시
    function boardTargetAll() {
        const board_target_all = document.querySelector("#board_target_all");
        const board_target_teacher = document.querySelector("#board_target_teacher");
        const board_target_student = document.querySelector("#board_target_student");
        if (board_target_all.checked) {
            board_target_teacher.checked = true;
            board_target_student.checked = true;
        } else {
            board_target_teacher.checked = false;
            board_target_student.checked = false;
        }
    }
    //게시판 첨부파일 삭제
    function boardUploadFileDelete(vthis, seq, path) {
        const page = "/manage/boardwrite/filedelete";
        const parameter = {
            bdupfile_seq: seq,
            bdupfile_path: path
        };
        queryFetch(page, parameter, function(data) {
            if (data.resultCode == 'success') {
                sAlert('', '삭제되었습니다.');
                vthis.remove();
            }
        });
    }

    //드롭존 정의
    function boardDropZoneInit() {
        // 드롭존 한글로 변경
        Dropzone.options.myDropzone1 = {
            dictDefaultMessage: "여기에 파일을 드래그 하세요.",
            dictFallbackMessage: "이 브라우저는 드래그앤드롭을 지원하지 않습니다.",
            dictFallbackText: "Please use the fallback form below to upload your files like in the olden days.",
            dictFileTooBig: "파일이 너무 큽니다. 최대 파일 크기: 100MiB.",
            dictInvalidFileType: "이 파일 형식은 업로드할 수 없습니다.",
            dictResponseError: "서버에서 오류가 발생했습니다.",
            dictCancelUpload: "업로드를 취소했습니다.",
            dictCancelUploadConfirmation: "정말 업로드를 취소하시겠습니까?",
            dictRemoveFile: "파일 삭제",
            dictRemoveFileConfirmation: null,
            dictMaxFilesExceeded: "이 파일은 더 이상 업로드할 수 없습니다.",
            maxFilesize: 100,
            success: function(file, response) {
                // 성공했을때 만들어지는 attr seq 주기
                // 첨부파일 삭제 기능.
                boardAddUploadSet(file, response);
            },
            error: function(file, response) {
                console.log(response);
            },
            headers: {
                'X-CSRF-TOKEN': document.querySelector("#csrf_token").value
            }
        };

        Dropzone.options.myDropzone2 = {
            dictDefaultMessage: "여기에 이미지를 드래그 하세요.",
            dictFallbackMessage: "이 브라우저는 드래그앤드롭을 지원하지 않습니다.",
            dictFallbackText: "Please use the fallback form below to upload your files like in the olden days.",
            dictFileTooBig: "파일이 너무 큽니다. 최대 파일 크기: 100MiB.",
            dictInvalidFileType: "이 파일 형식은 업로드할 수 없습니다.",
            dictResponseError: "서버에서 오류가 발생했습니다.",
            dictCancelUpload: "업로드를 취소했습니다.",
            dictCancelUploadConfirmation: "정말 업로드를 취소하시겠습니까?",
            dictRemoveFile: "파일 삭제",
            dictRemoveFileConfirmation: null,
            dictMaxFilesExceeded: "이 파일은 더 이상 업로드할 수 없습니다.",
            maxFilesize: 100,
            acceptedFiles: 'image/*',
            maxFiles: 1,
            success: function(file, response) {
                // 성공했을때 만들어지는 attr seq 주기
                // 첨부파일 삭제 기능.
                // is_thumbnail = true;
                boardAddUploadSet(file, response, true);
            },
            error: function(file, response) {
                sAlert('', response);
                file.previewElement.remove();
            },
            headers: {
                'X-CSRF-TOKEN': document.querySelector("#csrf_token").value
            }
        };
    }

    //에디터 정의
    function boardEditorInit() {
        board_tag = $('#board_content').summernote({
            placeholder: '내용을 입력해주세요.',
            height: 420,
            lang: 'ko-KR',
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['codeview', 'help']]
            ]
        });
        //결과값 가져오기.
        //board_tag.summernote('code');
        //결과값 넣기.
        //board_tag.summernote('code', '변수'');
    }

    //게시판 글쓰기 저장
    function boardAddInsert() {
        const wr_form = document.querySelector("#board_div_wr_form");;
        //게시판 정의
        const board_seq = document.querySelector("#boardadd_inp_board_seq").value;
        const board_name = document.querySelector("#board_inp_wr_top_name").value;
        const board_type = board_name == 'event' ? 'gallery' : 'board';
        let category = document.querySelector("[data-board-top-code]:checked"); // 하위에서 다시 사용.
        const title = wr_form.querySelector(".title").value;
        const content = board_tag.summernote('code');
        const writer_seq = wr_form.querySelector(".writer_seq").value;
        const writer_type = wr_form.querySelector(".writer_type").value;
        const writer_name = wr_form.querySelector(".writer_name").value;
        const board_idx = wr_form.querySelector(".board_idx").checked ? '1' : '0';
        const start_date = wr_form.querySelector(".start_date").value;
        const end_date = wr_form.querySelector(".end_date").value;
        let is_teacher = wr_form.querySelector(".is_teacher").checked ? 'Y' : 'N';
        let is_teacher2 = wr_form.querySelector(".is_teacher2").checked ? 'Y' : 'N';
        let is_parent = wr_form.querySelector(".is_parent").checked ? 'Y' : 'N';
        const is_student = wr_form.querySelector(".is_student").checked ? 'Y' : 'N';
        const is_region = wr_form.querySelector(".is_region").checked ? 'Y' : 'N';
        const is_team = wr_form.querySelector(".is_team").checked ? 'Y' : 'N';
        if(wr_form.querySelector('.is_pt_th').checked){
            is_teacher = 'Y';
            is_teacher2 = 'Y';
            is_parent = 'Y';
        }
        const is_important = wr_form.querySelector('#board_important').checked ? 'Y' : 'N';

        //.board_uploadfiles를 모두 가져와서 attr seq를 변수에 구분자 |로 넣기.
        const board_uploadfiles = document.querySelectorAll("#my-dropzone1 .board_uploadfiles");
        const board_thumbnails = document.querySelectorAll("#my-dropzone2 .board_thumbnail");

        // 응원메시지 추가.
        const writer_phone = wr_form.querySelector(".writer_phone").value;
        const student_name = wr_form.querySelector(".student_name").value;
        const student_seq = wr_form.querySelector(".student_seq").value;
        const region_name = wr_form.querySelector(".region_name").value;
        const region_seq = wr_form.querySelector(".region_seq").value;
        const team_code = wr_form.querySelector(".team_code").value;
        const school_name = wr_form.querySelector(".school_name").value;
        const grade = wr_form.querySelector(".use_support .grade").value;
        const is_use = wr_form.querySelector(".is_use").value;

        //학습자료 추가.
        // name=board_readio_subject 라디오중 체크된 값 가져오기.
        const subject_code_tag = wr_form.querySelector("input[name=board_readio_subject]:checked");
        const grade_code_tag = wr_form.querySelector("input[name=board_readio_grade]:checked");
        const semester_code_tag = wr_form.querySelector("input[name=board_readio_semester]:checked");
        const category_tag = wr_form.querySelector("input[name=board_readio_top_code]:checked");

        const subject_code = subject_code_tag ? subject_code_tag.getAttribute("code_seq") : '';
        const grade_code = grade_code_tag ? grade_code_tag.getAttribute("code_seq") : '';
        const semester_code = semester_code_tag ? semester_code_tag.getAttribute("code_seq") : '';
        // 학습자료일때만 상위 에서 사용하는 카테고리에서 덮어쓰기
        if(board_name == 'learning')
            category = category_tag ? category_tag.getAttribute("code_seq") : '';
        else{
            category = category ? category.getAttribute("data-board-top-code") : '';
        }

        const major_unit = wr_form.querySelector(".major_unit").value;
        const medium_unit = wr_form.querySelector(".medium_unit").value;

        // 사용자권한 가져오기.
        const board_div_group_sel = document.querySelector('#board_div_group_sel');
        const tag_group_seq = board_div_group_sel ? board_div_group_sel.querySelectorAll('.badge.d-flex'):'';

        let group_seqs = '';
        for(let i=0; i<tag_group_seq.length; i++){
            const tag = tag_group_seq[i];
            const group_seq_tag = tag.querySelector('.group_seq');
            if(group_seq_tag == null) continue;
            if(group_seqs != '')
                group_seqs += ',';
            group_seqs += group_seq_tag.value;
        }
        const open_size = wr_form.querySelector(".open_size").value;



        let board_uploadfiles_seqs = '';
        board_uploadfiles.forEach(element => {
            // 처음이후에만 | 붙이기.
            if (board_uploadfiles_seqs != '') board_uploadfiles_seqs += '|';
            board_uploadfiles_seqs += element.getAttribute("seq");
        });
        let board_thumbnails_seqs = '';
        board_thumbnails.forEach(element => {
            // 처음이후에만 | 붙이기.
            if (board_thumbnails_seqs != '') board_thumbnails_seqs += '|';
            board_thumbnails_seqs += element.getAttribute("seq");
        });

        // 필수값 체크
        // 필수값 없으면 리턴
        if(!boardAddChkSaveBefore(board_name)) return;

        const page = "/manage/boardwrite";
        const parameter = {
            board_seq: board_seq,
            board_name: board_name,
            category: category,
            title: title,
            content: content,
            writer_seq: writer_seq,
            writer_type: writer_type,
            writer_name: writer_name,
            board_idx: board_idx,
            start_date: start_date,
            end_date: end_date,
            is_teacher: is_teacher,
            is_parent: is_parent,
            is_student: is_student,
            board_uploadfiles_seqs: board_uploadfiles_seqs,
            board_thumbnails_seqs: board_thumbnails_seqs,
            writer_phone:writer_phone,
            student_name:student_name,
            student_seq:student_seq,
            region_name:region_name,
            region_seq:region_seq,
            team_code:team_code,
            school_name:school_name,
            grade:grade,
            is_use:is_use,
            subject_code:subject_code,
            grade_code:grade_code,
            semester_code:semester_code,
            major_unit:major_unit,
            medium_unit:medium_unit,
            group_seqs:group_seqs,
            open_size:open_size,
            is_teacher2:is_teacher2,
            is_important:is_important,
            is_region:is_region,
            is_team:is_team
        };
        queryFetch(page, parameter, function(data) {
            if (data.resultCode == 'success') {
                sAlert('', '<span class="text-m-28px">저장되었습니다</span>.' , 4);
                boardAddClose();
                boardSelect(board_name);
                boardAddClear();
            }
        });

    }

    //게시판 글쓰기 삭제
    function boardAddDelete(type, callback) {
        const board_seq = document.querySelector("#boardadd_inp_board_seq").value;
        const board_name = document.querySelector("#board_inp_wr_top_name").value;
        //board_seq 없으면 리턴
        if((board_seq||'') == ''){
            sAlert('', '삭제할 게시판을 선택해주세요.');
            return;
        }
        const page = "/manage/boarddelete";
        const parameter = {
            board_seq: board_seq,
            board_name: board_name
        };
        //삭제 확인
        setTimeout(function(){
            sAlert('게시판 삭제', '정말 삭제하시겠습니까?', 2, function() {
                queryFetch(page, parameter, function(data) {
                    if (data.resultCode == 'success') {
                        if(type == 'in_teacher'){
                            if(callback != undefined)
                                callback();
                        }
                        else{
                            sAlert('', '삭제되었습니다.');
                            //삭제후 게시판 리스트로 이동.
                            boardAddClose();
                            //게시판 리스트 가져오기.
                            boardList(board_name);
                        }
                    }
                });
            });
        }, 200);

    }

    //게시판 글쓰기 초기화
    function boardAddClear() {
        //폼안에 모든 input, textarea, select, chexkbox 초기화
        const board_div_wr_form = document.querySelector("#board_div_wr_form");
        const today = new Date().format('yyyy-MM-dd');
        board_div_wr_form.querySelectorAll("input, textarea, select").forEach(element => {
            //input date는 오늘 날짜로 초기화
            if (element.type == 'date')
                element.value = today;
            // .writer_로 시작하는 input도 제외 / disabled 도 제외
            else if (element.classList.contains('writer_') || element.disabled == true) {} else{
                element.value = '';
                element.checked = false;
            }
        });
        //에디터 초기화
        board_tag.summernote('code', '');
        //드롭존 초기화
        const board_uploadfiles = document.querySelectorAll(".board_uploadfiles");
        board_uploadfiles.forEach(element => {
            element.remove();
        });
        const boardadd_inp_board_seq = document.querySelector("#boardadd_inp_board_seq");
        boardadd_inp_board_seq.value = '';
        // 문의내용 초기화
        const board_div_wr_form_content0 = document.querySelector("#board_div_wr_form .content0");
        board_div_wr_form_content0.innerHTML = '';

        //writer_type = teacher 초기화
        const board_div_wr_form_writer_type = document.querySelector("#board_div_wr_form .writer_type");
        board_div_wr_form_writer_type.value = 'teacher';

        // 사용자권한 초기화.
        const board_div_group_sel = document.getElementById('board_div_group_sel');
        const tag_group_seqs = board_div_group_sel ? board_div_group_sel.querySelectorAll('.badge') : '';
        if(tag_group_seqs){
            tag_group_seqs.forEach(element => {
                element.classList.remove('d-flex');
            });
        }

        //수정시 상단 타입 변경 라디오 숨김 해제
        // 응원메시지, 학습자료 는 제외
        const type = document.querySelector("#board_inp_wr_top_name").value;
        if(type != 'support' || type != 'learning') {
            boardAddRadioTypeTop(true);
        }

        //썸네일 첨부파일 초기화
        boardAddUploadClear();

        //썸네일 드롭존 보이게.
        boardAddThumbnailDropzone(true);

        //드롭존 초기화.
        boardDropZoneClear();

        //삭제버튼 숨김
        boardAddDeleteShow(false);

    }

    //첨부파일 seq 정의 및 클릭시 첨부파일 삭제 기능.
    function boardAddUploadSet(file, response, is_thumbnail) {
        const class_name = is_thumbnail ? 'board_thumbnail' : 'board_uploadfiles';
        file.previewElement.classList.add(class_name);
        file.previewElement.setAttribute("seq", response.bdupfile_seq);
        file.previewElement.setAttribute("path", response.bdupfile_path);
        //클릭시 삭제 하시겠습니까? 물어보기
        file.previewElement.addEventListener("click", function() {
            const tag_file = this;
            sAlert('', "정말 삭제하시겠습니까?", 2, function() {
                const seq = tag_file.getAttribute("seq");
                const path = tag_file.getAttribute("path");
                boardUploadFileDelete(tag_file, seq, path);
            });
        });
    }

    //상단 타입 변경 라디오 숨김, 보임 처리
    function boardAddRadioTypeTop(is_show) {
        const boardadd_div_radio_type_top = document.querySelector("#boardadd_div_radio_type_top");
        if (is_show) {
            boardadd_div_radio_type_top.hidden = false;
        } else {
            boardadd_div_radio_type_top.hidden = true;
        }
    }

    //글쓰기시 수정일경우 내용을 가져오기.
    function boardAddGetBoardInfo() {
        //수정시 상단 타입 변경 라디오 숨김
        boardAddRadioTypeTop(false);
        //로딩 표시(loding_place2)
        boardAddLoding();
        //삭제 버튼 숨김 해제
        boardAddDeleteShow(true);

        const board_seq = document.querySelector("#boardadd_inp_board_seq").value;
        const board_name = document.querySelector("#board_inp_wr_top_name").value;
        const page_num = 1;

        const page = "/manage/board/select";
        const parameter = {
            board_seq: board_seq,
            board_name: board_name,
            board_page_max: 1,
            page: page_num
        };
        queryFetch(page, parameter, function(result) {
            const board_info = result.board;
            if (result.resultCode == 'success') {
                // 가져온 보드 정보 넣기.
                boardAddInfoSet(board_info.data);
                //가져온 첨부파일 넣기.
                boardAddUploadSetList(result.bdupfile);
                //사용자 권한
                boardAddSettingGroupSelSet(result.board_groups);
            }
        });
    }

    //글쓰기시 수정일경우 가져온 내용을 넣기.
    function boardAddInfoSet(data) {
        const r_data = data[0];
        const board_name = r_data.board_name;
        const board_type = r_data.board_type;
        const title = r_data.title;
        const writer_name = r_data.writer_name;
        const writer_seq = r_data.writer_seq;
        const writer_type = r_data.writer_type;
        const writer_email = r_data.writer_email;
        const writer_phone = r_data.writer_phone;
        const content = r_data.content;
        const board_idx = r_data.board_idx;
        const start_date = r_data.start_date;
        const end_date = r_data.end_date;
        const is_teacher = r_data.is_teacher;
        const is_teacher2 = r_data.is_teacher2;
        const is_parent = r_data.is_parent;
        const is_student = r_data.is_student;
        const is_region = r_data.is_region;
        const is_important = r_data.is_important;
        const is_team = r_data.is_team;
        const is_pt_th = r_data.is_parent == 'Y' && r_data.is_teacher == 'Y' && r_data.is_teacher2 == 'Y' ? true : false;

        const comment_wr_seq = r_data.comment_wr_seq;
        const comment = r_data.comment;
        const created_at = r_data.created_at;

        // 응원메시지 추가.
        const region_name = r_data.region_name;
        const region_seq = r_data.region_seq;
        const team_code = r_data.team_code;
        const school_name = r_data.school_name;
        const grade = r_data.grade;
        const is_use = r_data.is_use;
        const student_seq = r_data.student_seq;
        const student_name = r_data.student_name;

        //학습자료 추가.
        const subject_code = r_data.subject;
        const grade_code = r_data.grade;
        const semester_code = r_data.semester;
        const category = r_data.category;
        const major_unit = r_data.major_unit;
        const medium_unit = r_data.medium_unit;
        const open_size = r_data.open_size;
        const group_seqs = r_data.group_seqs || '';


        //썸네일 / 첨부파일 부분

        const board_form = document.querySelector("#board_div_wr_form");
        board_form.querySelector(".title").value = title;
        board_form.querySelectorAll(".writer_name").forEach(element => {
            element.value = writer_name;
        });
        board_form.querySelector(".writer_seq").value = writer_seq;
        board_form.querySelector(".writer_type").value = writer_type;
        board_form.querySelector(".writer_email").value = writer_email;
        board_form.querySelector(".writer_phone").value = writer_phone;
        board_form.querySelector(".board_idx").checked = board_idx == '1' ? true : false;
        board_form.querySelector(".start_date").value = (start_date||'' ).substr(0, 10);
        board_form.querySelector(".end_date").value = (end_date || '').substr(0, 10);
        board_form.querySelector(".created_at").value = (created_at || '').substr(0, 10);
        board_form.querySelector(".created_at_time").value = (created_at || '').substr(11, 8);

        board_form.querySelector(".is_teacher").checked = is_teacher == 'Y' ? true : false;
        board_form.querySelector(".is_parent").checked = is_parent == 'Y' ? true : false;
        board_form.querySelector(".is_student").checked = is_student == 'Y' ? true : false;
        board_form.querySelector(".is_region").checked = is_region == 'Y' ? true : false;
        board_form.querySelector(".is_team").checked = is_team == 'Y' ? true : false;
        board_form.querySelector(".is_teacher2").checked = is_teacher2 == 'Y' ? true : false;
        board_form.querySelector(".is_pt_th").checked = is_pt_th == 'Y' ? true : false;
        if(is_pt_th == 'Y'){
            board_form.querySelector(".is_teacher").checked = false;
            board_form.querySelector(".is_parent").checked = false;
            board_form.querySelector(".is_teacher2").checked = false;
        }
        board_form.querySelector("#board_important").checked = is_important == 'Y' ? true : false;

        board_form.querySelector(".writer_phone").value = writer_phone;
        board_form.querySelector(".region_name").value = region_name;
        board_form.querySelector(".region_seq").value = region_seq;
        board_form.querySelector(".team_code").value = team_code;
        board_form.querySelector(".school_name").value = school_name;
        board_form.querySelector(".grade").value = grade;
        board_form.querySelector(".is_use").value = is_use;
        board_form.querySelector(".student_seq").value = student_seq;
        board_form.querySelector(".student_name").value = student_name;

        if(subject_code*1 > 0) board_form.querySelector("#board_radio_learning_"+subject_code).nextElementSibling.click();
        if(grade_code*1 > 0) board_form.querySelector("#board_radio_learning_"+grade_code).nextElementSibling.click();
        if(semester_code*1 > 0) board_form.querySelector("#board_radio_learning_"+semester_code).nextElementSibling.click();
        if(category*1 > 0){
            if(board_name == 'learning'){
                board_form.querySelector("#board_radio_learning_"+category).nextElementSibling.click();
                board_form.querySelector('.category').value = category;
            }
            else{
                board_form.querySelector("#inp_board_top_code_"+category).nextElementSibling.click();
            }
        }
        board_form.querySelector(".major_unit").value = major_unit;
        board_form.querySelector(".medium_unit").value = medium_unit;
        board_form.querySelector(".open_size").value = open_size;





        //시스템/사용문의 일경우 내용을 다른곳에 넣어줌.
        board_tag.summernote('code', board_name == 'qna'|| board_name == 'sdqna' ? comment : content);

        if (board_name == 'qna' || board_name == 'sdqna')
            board_form.querySelector(".content0").innerHTML = content;

        boardAddLodingClear();
    }

    // 사용자 권한 보이기.
    function boardAddSettingGroupSelSet(data){
        for(let i=0; i<data.length; i++){
            const r_data = data[i];
            const group_seq = r_data.group_seq;
            const board_div_group_sel = document.getElementById('board_div_group_sel');
            const tag_group_seq = board_div_group_sel.querySelector('.seq'+group_seq);
            if(tag_group_seq == null) continue;
            tag_group_seq.classList.add('d-flex');
        }
    }

    //글쓰기 로딩중 표시
    function boardAddLoding() {
        const board_form = document.querySelector("#board_div_wr_form");
        const loding_place2 = board_form.querySelectorAll(".loding_place2");
        loding_place2.forEach(element => {
            element.hidden = false;
            element.nextElementSibling.hidden = true;
        });
    }
    //글쓰기 로딩중 해제
    function boardAddLodingClear() {
        const board_form = document.querySelector("#board_div_wr_form");
        const loding_place2 = board_form.querySelectorAll(".loding_place2");
        loding_place2.forEach(element => {
            element.hidden = true;
            element.nextElementSibling.hidden = false;
        });
    }

    //글쓰기시 수정일경우 가져온 첨부파일 정보 넣기.
    function boardAddUploadSetList(data) {
        //데이터 없으면 리턴
        if (data == undefined) return;
        if (data.length == 0) return;

        //이벤트 썸네일
        const boardadd_div_thumbnails = document.querySelector("#boardadd_div_thumbnails");
        const btn_thumbnail = boardadd_div_thumbnails.querySelector(".copy_btn_thumbnail").cloneNode(true);

        //관리자 첨부파일
        const boardadd_div_uploadfiles = document.querySelector("#boardadd_div_uploadfiles");
        const btn_uploadfile = boardadd_div_uploadfiles.querySelector(".copy_btn_uploadfile").cloneNode(true);

        //학생 첨부파일 학습 Q&A
        const boardadd_div_uploadfiles_st = document.querySelector("#boardadd_div_uploadfiles_st");
        const btn_uploadfile_st = boardadd_div_uploadfiles_st.querySelector(".copy_btn_uploadfile_st").cloneNode(true);

        //썸네일 / 첨부파일 초기화
        boardAddUploadClear();

        for(let i = 0; i < data.length; i++){
            const r_data = data[i];
            let btn_file = undefined;
            let rmove_cname = '';
            let cname = '';
            if(r_data.bdupfile_type == 'thumbnail'){
                btn_file = btn_thumbnail.cloneNode(true);
                //섬네일 드롭존 숨김
                boardAddThumbnailDropzone(false);
                rmove_cname = 'copy_btn_thumbnail';
                cname = 'board_thumbnail'
            }
            //학생이 올린 첨부파일
            else if(r_data.bdupfile_type == 'student_upload'){
                btn_file = btn_uploadfile_st.cloneNode(true);
                rmove_cname = 'copy_btn_uploadfile_st';
                cname = 'board_uploadfiles_st';
            }
            else{
                btn_file = btn_uploadfile.cloneNode(true);
                rmove_cname = 'copy_btn_uploadfile';
                cname = 'board_uploadfiles';
            }
            btn_file.hidden = false;
            btn_file.classList.remove(rmove_cname);
            btn_file.classList.add(cname);
            let bdupfile_path = r_data.bdupfile_path;
            // 앞 경로 제거 , 마지막 [_*.확장자] 에서 확장자 남기고 앞가지 제거
            bdupfile_path = bdupfile_path.replace('uploads/boardfiles/', '');
            bdupfile_path = bdupfile_path.replace(/_[0-9a-zA-Z가-힣ㄱ-ㅎㅏ-ㅣ]*\./, '.');
            btn_file.querySelector('div').innerHTML = bdupfile_path;
            btn_file.querySelector('div').setAttribute("path", r_data.bdupfile_path);
            //클릭시 새창 다운로드
            btn_file.querySelector('div').addEventListener("click", function() {
                const path = this.getAttribute("path");
                window.open('/storage/'+path);
            });
            btn_file.querySelector('button').setAttribute("seq", r_data.bdupfile_seq);
            btn_file.querySelector('button').setAttribute("path", r_data.bdupfile_path);
            btn_file.querySelector('button').addEventListener("click", function() {
                const tag_file = this.closest(".div_bdupfile_delete");
                const vthis = this;
                sAlert('', "정말 삭제하시겠습니까?", 2, function() {
                    const seq = vthis.getAttribute("seq");
                    const path = vthis.getAttribute("path");

                    //썸네일일 경우 드롭존 숨김 해제
                    if(tag_file.classList.contains('thumbnail'))
                        boardAddThumbnailDropzone(true);

                    boardUploadFileDelete(tag_file, seq, path);
                });
            });
            if(r_data.bdupfile_type == 'thumbnail'){
                boardadd_div_thumbnails.appendChild(btn_file);
            }
            else if(r_data.bdupfile_type == 'student_upload'){
                boardadd_div_uploadfiles_st.appendChild(btn_file);
            }
            else{
                boardadd_div_uploadfiles.appendChild(btn_file);
            }
        }
    }
    //첨부파일 / 섬네일 초기화
    function boardAddUploadClear() {
        //썸네일 부분 초기화
        const boardadd_div_thumbnails = document.querySelector("#boardadd_div_thumbnails");
        const btn_thumbnail = boardadd_div_thumbnails.querySelector(".copy_btn_thumbnail").cloneNode(true);
        boardadd_div_thumbnails.innerHTML = '';
        boardadd_div_thumbnails.appendChild(btn_thumbnail);

        //첨부파일 부분 초기화.
        const boardadd_div_uploadfiles = document.querySelector("#boardadd_div_uploadfiles");
        const btn_uploadfile = boardadd_div_uploadfiles.querySelector(".copy_btn_uploadfile").cloneNode(true);
        boardadd_div_uploadfiles.innerHTML = '';
        boardadd_div_uploadfiles.appendChild(btn_uploadfile);

        //학생 첨부파일 부분 초기화.
        const boardadd_div_uploadfiles_st = document.querySelector("#boardadd_div_uploadfiles_st");
        const btn_uploadfile_st = boardadd_div_uploadfiles_st.querySelector(".copy_btn_uploadfile_st").cloneNode(true);
        boardadd_div_uploadfiles_st.innerHTML = '';
        boardadd_div_uploadfiles_st.appendChild(btn_uploadfile_st);
    }

    //썸네일에 드롭존 숨김 숨김해제
    function boardAddThumbnailDropzone(is_show) {
        const myDropzone2 = document.querySelector("#my-dropzone2");
        if (is_show) {
            myDropzone2.hidden = false;
            myDropzone2.previousElementSibling.hidden = false;
        } else {
            myDropzone2.hidden = true;
            myDropzone2.previousElementSibling.hidden = true;
        }
    }

    //드롭존 초기화
    function boardDropZoneClear(){
        const myDropzone1 = document.querySelector("#my-dropzone1");
        const myDropzone2 = document.querySelector("#my-dropzone2");
        myDropzone1.dropzone.removeAllFiles();
        myDropzone2.dropzone.removeAllFiles();
    }

    //삭제버튼 보임 숨김
    function boardAddDeleteShow(is_show) {
        const boardadd_btn_boarddelte = document.querySelector("#boardadd_btn_boarddelte");
        if (is_show) {
            // board_name qna 제외
            if(document.querySelector("#board_inp_wr_top_name").value == 'qna') return;
            boardadd_btn_boarddelte.hidden = false;
        } else {
            boardadd_btn_boarddelte.hidden = true;
        }
    }

    // 응원메시지 글쓰기 세팅
    function boardAddSettingSupport(){
        const type = document.querySelector("#board_inp_wr_top_name").value;
        if(type == 'support'){
            const board_add = document.querySelector("#board_div_add");
            const div_range_date = board_add.querySelector(".div_range_date");
            const div_region = board_add.querySelector(".div_region");

            //div_range_date를 div_region 앞으로 이동
            div_region.before(div_range_date);
            //연락처 disabled 풀기
            board_add.querySelector(".writer_phone").disabled = false;
            //등록일자 disabled 풀기 / created_at_time
            // board_add.querySelector(".created_at").disabled = false;
            // board_add.querySelector(".created_at_time").disabled = false;

            //게시상태 disabled 풀기

            // 작성자 초기화
            board_add.querySelector(".writer_name").value = '';
            board_add.querySelector(".writer_seq").value = '';
            board_add.querySelector(".writer_type").value = 'parent';
        }
    }

    // 학습자료 글쓰기 세팅
    function boardAddSettingLearning(){
        const type = document.querySelector("#board_inp_wr_top_name").value;
        if(type == 'learning'){
            //사용(게시상태) 공개범위 뒤로 이동
            const board_add = document.querySelector("#board_div_add");
            const div_is_use = board_add.querySelector(".div_is_use");
            const div_open_size = board_add.querySelector(".div_open_size");
            div_open_size.after(div_is_use);
            board_add.querySelector('.is_use').value = 'Y';
        }
    }

    // 학부모 찾기 모달 열기
    function boardAddSearchParentModal(){
        //초기화
        const modal = document.querySelector("#boardadd_modal_search_parent");
        const tby_parents = modal.querySelector(".tby_parents");
        const copy_tr_parents = modal.querySelector(".copy_tr_parents").cloneNode(true);
        tby_parents.innerHTML = '';
        tby_parents.appendChild(copy_tr_parents);
        copy_tr_parents.hidden = true;

        //학무모 찾기 input clear
        modal.querySelector(".search_str").value = '';

        // 모달 열기
        const myModal = new bootstrap.Modal(document.getElementById('boardadd_modal_search_parent'), {});
        myModal.show();
    }

    // 학부모 찾기 버튼 클릭
    function boardAddSearchParent(vthis){
        const modal = document.querySelector("#boardadd_modal_search_parent");
        const search_str = modal.querySelector(".search_str").value;
        if(search_str.length < 1){
            toast('검색어를 입력해주세요.');
            return;
        }
        //로딩 시작
        vthis.querySelector(".sp_loding").hidden = false;
        const page = "/manage/board/support/parent/select";
        const parameter = {
            search_str: search_str
        };
        queryFetch(page, parameter, function(result) {
            //로딩 끝
            vthis.querySelector(".sp_loding").hidden = true;
            if ((result.resultCode||'') == 'success') {
                //초기화
                const tby_parents = modal.querySelector(".tby_parents");
                const copy_tr_parents = modal.querySelector(".copy_tr_parents").cloneNode(true);
                tby_parents.innerHTML = '';
                tby_parents.appendChild(copy_tr_parents);
                copy_tr_parents.hidden = true;

                // 데이터 넣기
                for(let i = 0; i < result.parents.length; i++){
                    const r_data = result.parents[i];
                    const tr = copy_tr_parents.cloneNode(true);
                    tr.classList.remove("copy_tr_parents");
                    tr.classList.add("tr_parents");
                    tr.hidden = false;
                    tr.querySelector(".parent_name").innerHTML = r_data.parent_name;
                    tr.querySelector(".parent_id").innerHTML = r_data.parent_id;
                    tr.querySelector(".parent_phone").innerHTML = r_data.parent_phone;
                    tr.querySelector(".parent_seq").value = r_data.id;
                    tby_parents.appendChild(tr);
                }
            }
        });
    }

    // 학부모찾기 테이블 tr 선택
    function boardAddClickTrParent(vthis){
        if(vthis.classList.contains('table-active')){
            vthis.classList.remove('table-active');
        }
        else{
            const trs = vthis.closest("tbody").querySelectorAll(".tr_parents");
            trs.forEach(element => {
                element.classList.remove('table-active');
            });
            vthis.classList.add('table-active');
        }
    }

    // 학부모찾기 선택
    function boardAddSelectParent(){
        const modal = document.querySelector("#boardadd_modal_search_parent");
        const trs = modal.querySelectorAll(".tr_parents.table-active");
        // 선택된 tr이 없으면 리턴
        if(trs.length < 1){
            toast('선택된 학부모가 없습니다')
            return;
        }
        const tr_select = trs[0];
        const parent_name = tr_select.querySelector(".parent_name").innerHTML;
        const parent_id = tr_select.querySelector(".parent_id").innerHTML;
        const parent_phone = tr_select.querySelector(".parent_phone").innerHTML;
        const parent_seq = tr_select.querySelector(".parent_seq").value;

        const board_add = document.querySelector("#board_div_add");
        board_add.querySelector(".writer_name").value = parent_name;
        board_add.querySelector(".writer_name").disabled = false;
        board_add.querySelector(".writer_seq").value = parent_seq;
        board_add.querySelector(".writer_type").value = 'parent';
        board_add.querySelector(".writer_phone").value = parent_phone;

        modal.querySelector(".modal_close").click();

        //학생 초기화
        board_add.querySelector(".student_name").value = '';
        board_add.querySelector(".student_seq").value = '';
        board_add.querySelector(".school_name").value = '';
        board_add.querySelector(".grade").value = '';
    }

    // 학생 찾기 모달 열기
    function boardAddSearchStudentModal(){
        // 초기화
        const modal = document.querySelector("#boardadd_modal_search_student");
        const tby_students = modal.querySelector(".tby_students");
        const copy_tr_students = modal.querySelector(".copy_tr_students").cloneNode(true);
        tby_students.innerHTML = '';
        tby_students.appendChild(copy_tr_students);
        copy_tr_students.hidden = true;

        if(!boardAddSearchStudent()){
            toast('자녀가 없거나, 학부모가 선택되지 않았습니다.')
            return;
        }
        // 모달 열기
        const myModal = new bootstrap.Modal(document.getElementById('boardadd_modal_search_student'), {});
        myModal.show();
    }

    function boardAddSearchStudent(){
        const board_add = document.querySelector("#board_div_add");
        const modal = document.querySelector("#boardadd_modal_search_student");
        const parent_seq = board_add.querySelector(".writer_seq").value;

        if(parent_seq == ''){
            return false;
        }
        //로딩 시작
        modal.querySelector(".sp_loding").hidden = false;
        const page = "/manage/board/support/student/select";
        const parameter = {
            parent_seq: parent_seq
        };
        queryFetch(page, parameter, function(result) {
            //로딩 끝
            modal.querySelector(".sp_loding").hidden = true;
            if ((result.resultCode||'') == 'success') {
                //초기화
                const tby_students = modal.querySelector(".tby_students");
                const copy_tr_students = modal.querySelector(".copy_tr_students").cloneNode(true);
                tby_students.innerHTML = '';
                tby_students.appendChild(copy_tr_students);
                copy_tr_students.hidden = true;

                // 데이터 넣기
                for(let i = 0; i < result.students.length; i++){
                    const r_data = result.students[i];
                    const tr = copy_tr_students.cloneNode(true);
                    tr.classList.remove("copy_tr_students");
                    tr.classList.add("tr_students");
                    tr.hidden = false;
                    tr.querySelector(".student_name").innerHTML = r_data.student_name;
                    tr.querySelector(".student_id").innerHTML = r_data.student_id;
                    tr.querySelector(".student_phone").innerHTML = r_data.student_phone;
                    tr.querySelector(".school_name").innerHTML = r_data.school_name;
                    tr.querySelector(".grade").value = r_data.grade;
                    tr.querySelector(".student_seq").value = r_data.id;
                    tr.querySelector(".region_seq").value = r_data.region_seq;
                    tr.querySelector(".region_name").value = r_data.region_name;
                    tr.querySelector(".team_code").value = r_data.team_code;
                    tr.querySelector(".team_name").value = r_data.team_name;
                    tby_students.appendChild(tr);
                }

                if((result.students||0).length < 1){
                    modal.querySelector("#div_student_empty").hidden = false;
                }else{
                    modal.querySelector("#div_student_empty").hidden = true;
                }
            }
        });

        return true;
    }

    // 학생찾기 테이블 tr 선택
    function boardAddClickTrStudent(vthis){
        if(vthis.classList.contains('table-active')){
            vthis.classList.remove('table-active');
        }
        else{
            const trs = vthis.closest("tbody").querySelectorAll(".tr_students");
            trs.forEach(element => {
                element.classList.remove('table-active');
            });
            vthis.classList.add('table-active');
        }
    }

    // 학생찾기 선택
    function boardAddSelectStudent(){
        const modal = document.querySelector("#boardadd_modal_search_student");
        const trs = modal.querySelectorAll(".tr_students.table-active");
        // 선택된 tr이 없으면 리턴
        if(trs.length < 1){
            toast('선택된 학생이 없습니다')
            return;
        }
        const tr_select = trs[0];
        const student_name = tr_select.querySelector(".student_name").innerHTML;
        const student_id = tr_select.querySelector(".student_id").innerHTML;
        const student_phone = tr_select.querySelector(".student_phone").innerHTML;
        const school_name = tr_select.querySelector(".school_name").innerHTML;
        const grade = tr_select.querySelector(".grade").value;
        const student_seq = tr_select.querySelector(".student_seq").value;
        const region_seq = tr_select.querySelector(".region_seq").value;
        const region_name = tr_select.querySelector(".region_name").value;
        const team_code = tr_select.querySelector(".team_code").value;
        const team_name = tr_select.querySelector(".team_name").value;

        const board_add = document.querySelector("#board_div_add");
        board_add.querySelector(".student_name").value = student_name;
        board_add.querySelector(".student_seq").value = student_seq;
        board_add.querySelector(".school_name").value = school_name;
        board_add.querySelector(".grade").value = grade.replace('e', '초').replace('m', '중').replace('h', '고');
        board_add.querySelector(".region_seq").value = region_seq;
        board_add.querySelector(".region_name").value = region_name + ' ' +team_name;
        board_add.querySelector(".team_code").value = team_code;

        modal.querySelector(".modal_close").click();
    }

    // 게시판 글쓰기 저장 전 필수값 체크
    function boardAddChkSaveBefore(board_type){
        const board_div_wr_form = document.querySelector("#board_div_wr_form");
        //전체

        //작성자 체크
        const writer_seq = board_div_wr_form.querySelector(".writer_seq").value;
        if(writer_seq == ''){
            toast('작성자를 선택해주세요.');
            return false;
        }

        //응원메시지
        if(board_type == 'support'){
            //학생 체크
            const student_seq = board_div_wr_form.querySelector(".student_seq").value;
            if(student_seq == ''){
                toast('학생을 선택해주세요.');
                return false;
            }
            //게시상태
            const is_use = board_div_wr_form.querySelector(".is_use").value;
            if(is_use == ''){
                toast('게시상태를 선택해주세요.');
                return false;
            }
        }

        return true;
    }

    // 사용자 권한 선택시
    function boardAddSettingGroupSel(vthis){
        const group_seq = vthis.value;
        const board_div_group_sel = document.getElementById('board_div_group_sel');
        const tag_group_seq = board_div_group_sel.querySelector('.seq'+group_seq);
        if(tag_group_seq == null) return;
        tag_group_seq.classList.add('d-flex');
    }

    //
    //메뉴 설정에서 권한의 그룹 삭제시
    function boardAddSettingGroupDel(group_seq){
        const board_div_group_sel = document.getElementById('board_div_group_sel');
        const tag_group_seq = board_div_group_sel.querySelector('.seq'+group_seq);
        if(tag_group_seq == null) return;
        tag_group_seq.classList.remove('d-flex');
    }
</script>
