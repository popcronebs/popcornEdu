<div data-board-list-main>
<input type="hidden" id="board_writer_name" value="{{ session()->get('teach_name') }}">
<input type="hidden" id="board_writer_seq" value="{{ session()->get('teach_seq') }}">
<input type="hidden" id="board_writer_type" value="teacher">

<div class="text-end mb-4 pb-2" data-board-main>
    <div class="d-inline-block select-wrap select-icon h-62 me-12">
        <select class="rounded-pill border-gray lg-select text-sb-20px h-62" onchange="" id="board_search_type_main">
            <option selected="" value="">제목</option>
            <option value="writer_name">작성자</option>
        </select>
    </div>
    {{-- 검색어 / 검색버튼 --}}
    <label class="label-search-wrap">
        <input type="text" id="inp_search_word"
        class=" ms-search border-gray rounded-pill text-m-20px" data-input--search1=""
        onkeyup="if(event.keyCode==13){ boardSelect('@yield('board_name', '')'); }"
        placeholder="@yield('search_word', '검색어를 입력해주세요.') ">
    </label>
    <button class="btn btn-outline-primary" onclick="boardSelect('@yield('board_name', '')');" hidden>검색</button>

</div>
<div>
    @if(count($board_top_codes) > 0 )
    {{-- 탭 / CODES 에서 분류 추가시.--}}
    <ul id="board_ul_top_code" class="nav nav-tabs mt-2 text-center mb-3" {{ $login_type??'' == 'admin' ? '':'hidden' }}>
        <li class="nav-item col-auto cursor-pointer">
            <a class="code_tab nav-link active" onclick="boardCodeTab(this)" type="all">
                <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden=""></span>
                전체 메뉴
            </a>
        </li>
        @foreach($board_top_codes as $key => $value)
            <li class="nav-item col-auto cursor-pointer">
                <a class="code_tab nav-link" onclick="boardCodeTab(this)" type="{{ $value->id }}">
                    <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden=""></span>
                    {{ $value->code_name }}
                </a>
            </li>
        @endforeach
    </ul>
    @endif
</div>
<div id="@yield('board_name', '')_board_main" data-board-main>
    <input type="hidden" class="inp_board_name" value="@yield('board_name', '')">
    <input type="hidden" class="inp_board_type" value="{{ $board_type ?? '' }}">
    <input type="hidden" class="board_page_max" value="@yield('board_page_max', '15')">
    <div>
        {{-- 갤러리 형태일때 --}}
        @if($board_type ?? '' == 'gallery')
            <div class="container-fluid p-4">
                <p class="card-text placeholder-glow loding_place">
                    <span class="placeholder col-12"></span>
                </p>
                <div class="row g-2" id="boardlist_div_gallery">
                    <div class="copy_div_gallery col-4" hidden data-row>
                        <div class="text-center mb-2">
                            {{-- 섬네일 --}}
                            <img class="board_thumbnail" src="{{ asset('images/noimg.png?1') }}" style="height: 200px;max-width:100%">
                        </div>
                        <div class="text-center">
                            <button class="btn btn-outline-info btn-sm is_now">진행중</button>
                            <span class="title">#이벤트 제목</span>
                        </div>
                        <div class="text-center">
                            <span class="start_date">#2023.01.01</span>~
                            <span class="end_date">#2023.08.01</span>
                        </div>
                        <div>
                            <button class="btn btn-outline-primary btn-sm w-100" onclick="boardAddEdit(this)">자세히 보기 / 수정</button>
                            <input type="hidden" class="board_seq" value="">
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- 리스트 형태일때 --}}
            <table class="w-100 table-list-style table-h-92 {{ $login_type??'' == 'admin' ? 'table table-bordered':'' }}">
                <thead class="table-light">
                    <tr>
                        {{-- 번호, 분류, 제목, 대상 팀명, 대상자, 작성자, 작성일자 --}}
                        <th class="use_support use_learning cursor-pointer" style="width:50px;" onclick="event.stopPropagation(); this.querySelector('.all_chk').click();" hidden>
                            <input type="checkbox" class="all_chk" onchange="boardAllChk(this);" onclick="event.stopPropagation();">
                        </th>
                        <th style="width:50px;">번호</th>
                        <th class="use_qna use_sdqna" hidden>처리상태</th>
                        <th class="use_notice use_faq use_learning" hidden>구분</th>
                        <th class="use_learning" hidden>과목</th>
                        <th class="use_learning" hidden>학년</th>
                        <th class="use_learning" hidden>학기</th>
                        <th class="use_learning" hidden>@yield('major_unit', '대단원')</th>
                        <th class="use_learning" hidden>@yield('medium_unit', '중단원')</th>
                        <th class="use_notice use_faq use_qna use_sdqna" hidden>제목</th>
                        <th class=" use_faq" hidden>대상 팀명</th>
                        <th class=" use_faq" hidden>대상자</th>
                        <th class="use_support" hidden>내용</th>
                        <th class="not_learning" >@yield('writer', '작성자')</th>
                        <th class="use_support" hidden>학생이름</th>
                        <th class="use_qna use_sdqna" hidden>이메일</th>
                        <th class="use_qna use_sdqna" hidden>연락처</th>
                        <th class="use_notice use_faq use_qna use_sdqna" hidden>@yield('write_date', '작성일자')</th>
                        <th class="use_support" hidden>소속</th>
                        <th class="use_support" hidden>게시기간</th>
                        <th class="use_support use_learning" hidden>@yield('is_use', '게시상태')</th>
                        <th class="use_notice" hidden>조회수</th>
                        <th hidden>기능</th>
                    </tr>
                </thead>
                <tbody id="boardlist_tby_board">
                    <tr class="copy_tr_board cursor-pointer" onclick="boardOpenDetail(this)" data-row>
                        <td class="use_support use_learning cursor-pointer " onclick="event.stopPropagation(); this.querySelector('.chk').click();" hidden>
                            <input type="checkbox" class="chk" onclick="event.stopPropagation();">
                        </td>
                        <td class="num">
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="is_comment use_qna use_sdqna" data="#처리상태" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="category use_notice use_faq use_learning table-center" data="#분류" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="subject use_learning" data="#과목" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="grade use_learning" data="#학년" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="semester use_learning" data="#학기" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="major_unit use_learning" data="#대단원" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="medium_unit use_learning" data="#중단원" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="title use_notice use_faq use_qna use_sdqna text-start" data="#제목" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="team_name use_faq" data="#대상 팀명" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="target_person use_faq" data="#대상자" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="content use_support" data="#내용" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="writer_name not_learning" data="#작성자">
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="student_name use_support" data="#학생이름" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="writer_email use_qna use_sdqna" data="#이메일" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="writer_phone use_qna use_sdqna" data="#연락처" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="created_at use_notice use_faq use_qna use_sdqna" data="#작성일자" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="region_name use_support" data="#소속" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="range_date use_support" data="#게시기간" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="is_use use_support use_learning" data="#게시상태" hidden>
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td class="views use_notice">
                            <p class="card-text placeholder-glow loding_place">
                                <span class="placeholder col-12"></span>
                            </p>
                        </td>
                        <td hidden>
                            <button class="btn_edit btn btn-outline-primary btn-sm" data-btn-edit onclick="boardAddEdit(this);">수정</button>
                        </td>
                        <input type="hidden" class="board_seq" value="">

                    </tr>
                </tbody>
            </table>
        @endif
        {{-- 56 --}}
        <div class="row mx-0 px-0 mt-lg-5 pt-2">
            <div class="col-2"></div>
            <div class="col">
                <div class="col d-flex justify-content-center">
                    <nav aria-label="...">
                        <ul class="pagination" id="boardlist_ul_page" hidden>
                            <button href="javascript:void(0)" class="btn p-0 prev" id="page_prev"
                                onclick="boardSelect('@yield('board_name', '')')">
                                <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                            </button>


                            <li class="page-item" id="page_first" hidden>
                                <a class="page-link text-m-20px" onclick="boardSelect('@yield('board_name', '')', this.innerText)">0</a></li>
                            <li class="page-item page_num active">
                                <a class="page-link text-m-20px" href="#">1</a></li>

                            <button href="javascript:void(0)" class="btn p-0 next"
                            id="page_next">
                                <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                            </button>
                        </ul>
                    </nav>
                </div>
            </div>
            <div class="col-2 text-end">
                <button
                class="btn-line-xss-secondary text-sb-20px border-gray scale-bg-white scale-text-gray_05 rounded p-3 use_notice use_faq use_event use_support use_learning" onclick="boardAddOpen()"
                 style="height:52px" hidden>@yield('write', '글쓰기')</button>
            </div>
        </div>
        <div class="d-flex">
            <div class="row gap-2" style="width:150px;">
                <button class="btn btn-outline-danger w-100 use_support use_learning" onclick="boardSelDel();"
                style="height:38px" hidden>선택삭제</button>

            </div>

            <div class="d-flex flex-column align-items-end gap-2">
                {{-- QnA 에서 사용 답변에 따른 검색. --}}
                <div class="flex-wrap btn-group use_qna use_sdqna" hidden>
                    <input type="radio" name="board_comment_type" class="copy_inp_dong1 btn-check" id="board_comment_complete" autocomplete="off">
                    <label class="copy_inp_dong2 btn btn-outline-primary hpx-40" for="board_comment_complete" >답변완료</label>

                    <input type="radio" name="board_comment_type" class="copy_inp_dong1 btn-check" id="board_comment_wait" autocomplete="off">
                    <label class="copy_inp_dong2 btn btn-outline-primary hpx-40" for="board_comment_wait" >답변대기</label>
                </div>
                {{-- 응원메시지 에서 사용 날짜에 따른 검색. --}}
                <div class="flex-wrap btn-group use_support" hidden>
                    <input type="date" class="form-control copy_inp_dong1" id="board_start_date" value="{{ date('Y-m-d', strtotime('-2 week')) }}"
                    style="width:140px;height:38px">
                    ~
                    <input type="date" class="form-control copy_inp_dong1" id="board_end_date" value="{{ date('Y-m-d') }}"
                    style="width:140px;height:38px">
                </div>

                {{-- 학습자료에서 사용하는 조건 검색 select [ 과목, 학년, 학기, 대단원, 중단원 ]--}}
                <div class="flex-wrap btn-group use_learning" hidden>
                    <select class="form-select copy_inp_dong1" id="board_search_type" style="width:200px;height:38px">
                        <option value="">선택</option>
                        <option value="subject">과목</option>
                        <option value="grade">학년</option>
                        <option value="semester">학기</option>
                        <option value="major_unit">대단원</option>
                        <option value="medium_unit">중단원</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- 글쓰기  --}}
<div id="board_div_add" class="position-relative w-100 h-100 bg-white ps-3 pe-3" style="top: 0; left: 0; z-index:3"
hidden>
@include('layout.board_add')
</div>


</div>

{{-- 게시판 상세보기. --}}
@include('layout.board_detail')

{{-- 160px --}}
<div>
    <div class="py-lg-5"></div>
    <div class="py-lg-4"></div>
    <div class="pt-lg-3"></div>
</div>

<script>
    boardSelect(document.querySelector(".inp_board_name").value);
    chgBoardName(document.querySelector(".inp_board_name").value);
    //게시판 가져오기.
    var mindex = 0;
    function boardSelect(board_, page_num) {
        const code_tab = document.querySelectorAll("#board_ul_top_code .code_tab.active");
        const category = code_tab.length > 0 ? code_tab[0].getAttribute("type").replace('all', '') : '';
        const board_id = board_ + '_board_main';
        const div_board = document.querySelector("#" + board_id);
        const board_name = div_board.querySelector(".inp_board_name").value;
        const board_page_max = div_board.querySelector(".board_page_max").value;
        const team_code = '';
        const search_word = document.querySelector("#inp_search_word").value;
        const search_type = div_board.querySelector("#board_search_type").value||document.querySelector("#board_search_type_main").value;
        const board_type = div_board.querySelector(".inp_board_type").value;
        const start_date = div_board.querySelector("#board_start_date").value;
        const end_date = div_board.querySelector("#board_end_date").value;

        //답변 상태
        let comment_type = '';
        if(document.querySelector("#board_comment_complete") != null){
            if(document.querySelector("#board_comment_complete").checked)
                comment_type = 'complete';
            if(document.querySelector("#board_comment_wait").checked)
                comment_type = 'wait';
        }

        if(page_num == undefined)
            page_num = 1;

        if(page_num == 1){
            mindex = 0;
        }
        const page = "/manage/board/select";
        const parameter = {
            category:category,
            board_name: board_name,
            board_page_max: board_page_max,
            team_code: team_code,
            search_word: search_word,
            search_type:search_type,
            page:page_num,
            start_date:start_date,
            end_date:end_date,
            comment_type:comment_type
        };
        queryFetch(page, parameter, function(result) {
            const board_info = result.board;
            if(result.resultCode == 'success'){
                // 페이징 처리
                boardPaging(board_info);
                //게시판 로우 리스트(리스트 형식과 갤러리 분리)
                if(board_type == 'gallery')
                    boardlistGallery(board_info);
                else
                    boardlistRows(board_info);
            }
        });
    }

    //게시판 글쓰기
    function boardAddOpen() {
        const board_div_add = document.querySelector("#board_div_add");
        board_div_add.hidden = false;
        const board_main = document.querySelectorAll('[data-board-main]');
        board_main.forEach(element => {
            element.hidden = true;
        });
        // 작성인 현재 로그인한 선생님으로 넣어준다.
        const wr_name = document.querySelector("#board_writer_name").value;
        const wr_seq = document.querySelector("#board_writer_seq").value;
        const wr_type = document.querySelector("#board_writer_type").value;
        board_div_add.querySelector(".writer_name").value = wr_name;
        board_div_add.querySelector(".writer_seq").value = wr_seq;
        board_div_add.querySelector(".writer_type").value = wr_type;
        board_div_add.querySelector(".writer_name").disabled = true;

        boardAddSettingSupport();
        boardAddSettingLearning();

    }

    //유형에 따라 게시판 리스트 변환
    function chgBoardName(type){
        document.querySelector('#board_div_wr_top .board_name').innerText = boardGetBoardName(type);
        // 먼저 게시판 용도에 따라 사용되는 태그를 모두 숨김처리후
        document.querySelectorAll("[class^='use_']").forEach(element => {
            element.hidden = true;
        });
        // 사용되는 해당 태그만 보여준다.
        document.querySelectorAll(".use_"+type).forEach(element => {
            element.hidden = false;
        });
        document.querySelectorAll("#board_radio_"+type).forEach(element => {
            element.checked = true;
        });
        // not_ 은 반대로 숨김 처리
        document.querySelectorAll(".not_"+type).forEach(element => {
            element.hidden = true;
        });
        //게시판 글쓰기시 타입 정의
        document.querySelector("#board_inp_wr_top_name").value = type;
    }

    //게시판 이름 가져오기.
    function boardGetBoardName(type) {
        let board_name = '';
        switch(type){
            case 'notice': board_name = '공지사항'; break;
            case 'faq': board_name = '자주묻는질문'; break;
            case 'qna': board_name = '시스템사용문의'; break;
            case 'event': board_name = '이벤트'; break;
        }
        return board_name;
    }

    //게시판 페이징 처리.
    function boardPaging(board_info){
        const from = board_info.from;
        const last_page = board_info.last_page;
        const per_page = board_info.per_page;
        const total = board_info.total;
        const to = board_info.to;
        const current_page = board_info.current_page;
        const data = board_info.data;
        //페이징 처리
        const boardlist_ul_page = document.querySelector("#boardlist_ul_page");
        //prev button, next_button
        const page_prev = boardlist_ul_page.querySelector("#page_prev");
        const page_next = boardlist_ul_page.querySelector("#page_next");
        //페이징 처리를 위해 기존 페이지 삭제
        document.querySelectorAll("#boardlist_ul_page .page_num").forEach(element => {
            element.remove();
        });
        //#page_first 클론
        const page_first = document.querySelector("#page_first");
        //페이지는 1~10개 까지만 보여준다.
        let page_start = 1;
        let page_end = 10;
        if(current_page > 5){
            page_start = current_page - 4;
            page_end = current_page + 5;
        }
        if(page_end > last_page){
            page_end = last_page;
        }


        let is_next = false;
        for(let i = page_start; i <= page_end; i++){
            const copy_page_first = page_first.cloneNode(true);
            copy_page_first.querySelector("a").innerText = i;
            copy_page_first.removeAttribute("id");
            copy_page_first.classList.add("page_num");
            copy_page_first.hidden = false;
            //현재 페이지면 active
            if(i == current_page){
                copy_page_first.classList.add("active");
            }
            //#page_first 뒤에 붙인다.
            boardlist_ul_page.insertBefore(copy_page_first, boardlist_ul_page.querySelector("#page_next"));
            //시작 페이지 보다 11보다 큰 i 이면 page_prev disabled 을 제거
            if(i > 11){
                // page_prev.classList.remove("disabled");
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
        if(page_next.getAttribute("onclick") == null){
            const board_name = document.querySelector(".inp_board_name").value;
            page_next.setAttribute("onclick", "boardSelect('"+board_name+"', "+(last_page)+")");
        }
        // .page_loding_place 숨김처리
        document.querySelectorAll(".loding_place").forEach(element => {
            element.remove();
        });
        // #boardlist_ul_page 숨김처리 해제
        if(data.length != 0)
            boardlist_ul_page.hidden = false;
    }

    //게시판 로우 리스트
    function boardlistRows(board_info){
        const tby_board = document.querySelector("#boardlist_tby_board");
        const copy_tr_board = tby_board.querySelector(".copy_tr_board").cloneNode(true);
        //테이블 로우를 초기화
        document.querySelectorAll("#boardlist_tby_board tr").forEach(element => {
            element.remove();
        });
        copy_tr_board.hidden = true;
        tby_board.appendChild(copy_tr_board);
        //게시판 로우 리스트
        const data = board_info.data;
        const from = board_info.from*1;
        const to = board_info.to*1;
        const total = board_info.total*1;
        // let first = total - (from - 1); //desc
        let first = from; //asc
        for(let i = 0; i < data.length; i++){
            const tr_board = tby_board.querySelector(".copy_tr_board").cloneNode(true);
            //대상자
            let target_person = '';
            if(data[i].is_teacher == 'Y'){
                target_person += '선생님 ';
            }
            if(data[i].is_student == 'Y' && data[i].is_parent == 'Y'){
                target_person += '학생/학부모 ';
            }
            if(data[i].is_teacher == 'Y' && data[i].is_student == 'Y' && data[i].is_parent == 'Y'){
                target_person = '전체';
            }
            //처리상태
            let is_comment = '답변대기';
            if((data[i].comment_wr_seq||'') != ''){
            // && (data[i].comment_length||0) > 0){ // 이부분은 일단 주석처리.
                is_comment = '답변완료';
            }
            tr_board.classList.remove("copy_tr_board");
            tr_board.classList.add("tr_board");
            tr_board.hidden = false;
            tr_board.querySelector(".board_seq").value = data[i].id;
            tr_board.querySelector(".num").innerText = data[i].is_important == 'Y' ? '중요':from + (i) - mindex;
            if(data[i].is_important == 'Y') {
                tr_board.querySelector(".num").classList.add('text-danger');
                mindex++; // 중요일 경우, 순서가 밀리는 것을 방지.
            }
            tr_board.querySelector(".is_comment").innerText = is_comment;
            tr_board.querySelector(".category").innerText = data[i].category_name;//||data[i].category;
            tr_board.querySelector(".title").innerText = data[i].title;
            tr_board.querySelector(".team_name").innerText = data[i].team_name||'';
            tr_board.querySelector(".target_person").innerText = target_person;
            tr_board.querySelector(".writer_name").innerText = data[i].writer_name;
            tr_board.querySelector(".writer_email").innerText = data[i].writer_email;
            tr_board.querySelector(".writer_phone").innerText = data[i].writer_phone;
            tr_board.querySelector(".created_at").innerText = (data[i].created_at||'').substr(0, 10).replace(/-/gi, '.')
            tr_board.querySelector(".views").innerText = data[i].views||'0';

            //응원메시지 추가.
            tr_board.querySelector(".student_name").innerText = data[i].student_name||'';
            tr_board.querySelector(".content").innerHTML = data[i].content||'';
            tr_board.querySelector(".region_name").innerText = data[i].region_name||'';
            tr_board.querySelector(".range_date").innerText = (data[i].start_date||'').substr(2, 8) + '~' + (data[i].end_date||'').substr(2, 8);
            tr_board.querySelector(".is_use").innerText = data[i].is_use||'';

            //학습자료 추가.
            tr_board.querySelector(".subject").innerText = data[i].subject_name||'';
            tr_board.querySelector(".grade").innerText = data[i].grade_name||'';
            tr_board.querySelector(".semester").innerText = data[i].semester_name||'';
            tr_board.querySelector(".major_unit").innerText = data[i].major_unit_name||'';
            tr_board.querySelector(".medium_unit").innerText = data[i].medium_unit_name||'';

            tby_board.appendChild(tr_board);
            // first--; // desc
            first++; // asc
        }
        //게시판이 하나도 없을때
        //게시판 하단에 Div를 추가한다.
        //게시물이 없습니다.
        if(data.length == 0){
            //after_none_data 삭제 초기화
            document.querySelectorAll(".after_none_data").forEach(element => {
                element.remove();
            });
            //div생성
            const div = document.createElement("div");
            //고유 클래스 추가.
            div.classList.add("after_none_data");
            div.classList.add("text-center");
            div.classList.add("mt-5");
            div.classList.add("mb-5");
            div.innerText = "게시물이 없습니다.";
            //테이블 뒤에 붙인다.
            tby_board.closest('table').after(div);
        }
    }

    // 게시판 갤러리 리스트
    function boardlistGallery(board_info){
        const div_board_gallery = document.querySelector("#boardlist_div_gallery");
        const copy_div_gallery = div_board_gallery.querySelector(".copy_div_gallery").cloneNode(true);
        //게시판 갤러리 리스트 초기화
        document.querySelectorAll("#boardlist_div_gallery div").forEach(element => {
            element.remove();
        });
        div_board_gallery.appendChild(copy_div_gallery);

        const data = board_info.data;
        //게시판 갤러리 리스트
        for(let i = 0; i < data.length; i++){
            const div_gallery = copy_div_gallery.cloneNode(true);
            let is_now = '마감';
            //start_date > now > end_date
            if((data[i].start_date||'').substr(0,10) <= new Date().format('yyyy-MM-dd') &&
            new Date().format('yyyy-MM-dd') <= (data[i].end_date||'').substr(0,10)){
                is_now = '진행중';
            }
            div_gallery.classList.remove("copy_div_gallery");
            div_gallery.classList.add("div_gallery_row");
            div_gallery.hidden = false;
            div_gallery.querySelector(".board_thumbnail").src = '{{ asset('storage') }}/'+data[i].bdupfile_path;
            div_gallery.querySelector(".title").innerText = data[i].title;
            div_gallery.querySelector(".start_date").innerText = (data[i].start_date||'').substr(2, 8);
            div_gallery.querySelector(".end_date").innerText = (data[i].end_date||'').substr(2, 8);
            div_gallery.querySelector(".is_now").innerText = is_now;
            div_gallery.querySelector(".board_seq").value = data[i].id;
            div_board_gallery.appendChild(div_gallery);
        }
        div_board_gallery.hidden = false;

         //게시물이 없습니다.
         if(data.length == 0){
            //div생성
            const div = document.createElement("div");
            div.classList.add("text-center");
            div.classList.add("mt-5");
            div.classList.add("mb-5");
            div.innerText = "게시물이 없습니다.";
            //테이블 뒤에 붙인다.
            div_board_gallery.after(div);
        }
    }

    //수정하기 클릭시
    function boardAddEdit(vthis){
        event.stopPropagation();
        const board_seq = vthis.closest("tr, .div_gallery_row").querySelector(".board_seq").value;
        // boardadd_inp_board_seq
        const boardadd_inp_board_seq = document.querySelector("#boardadd_inp_board_seq");
        boardadd_inp_board_seq.value = board_seq;
        //정보 가져오기
        boardAddGetBoardInfo();
        //글쓰기 창을 띄운다.
        boardAddOpen();
    }

    // 선택 삭제 기능 추가
    function boardSelDel(){
        const board_name = document.querySelector(".inp_board_name").value;
        const tby_board = document.querySelector("#boardlist_tby_board");
        const chk = tby_board.querySelectorAll(".chk:checked");

        //선택(checkbox)이 없으면 리턴
        if(chk.length == 0){ sAlert('', "선택된 게시물이 없습니다."); return; }

        let board_seqs = '';
        for(let i = 0; i < chk.length; i++){
            if(i != 0) board_seqs += ',';
            board_seqs += chk[i].closest("tr").querySelector(".board_seq").value;
        }

        const page = "/manage/board/sel/delete";
        const parameter = {
            board_seqs: board_seqs
        };
        // 정말 삭제하시겠습니까?
        sAlert('삭제확인', '정말 삭제 진행 하시겠습니까?', 2, function(){
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    //삭제 성공
                    sAlert('삭제완료', '삭제가 완료되었습니다.', 1, function(){
                        boardSelect(board_name);
                    });
                }
            });
        });
    }

    // 체크박스 전체 선택
    function boardAllChk(vthis){
        const tby_board = document.querySelector("#boardlist_tby_board");
        const chk = tby_board.querySelectorAll(".chk");
        for(let i = 0; i < chk.length; i++){
            chk[i].checked = vthis.checked;
        }
    }

    // 게시판 상단 탭 클릭
    function boardCodeTab(vthis){
        document.querySelectorAll("#board_ul_top_code .code_tab").forEach(element => {
            element.classList.remove("active");
        });
        vthis.classList.add("active");

        boardSelect(document.querySelector(".inp_board_name").value)
    }

    // 게시판 상세보기.
    let click_tr = null;
    function boardOpenDetail(vthis){
        event.stopPropagation();
        click_tr = vthis.closest('tr');
        const board_seq = vthis.querySelector(".board_seq").value;
        const board_name = document.querySelector(".inp_board_name").value;
        // :게시판 상세보기 양식1
        const btn_detail_close = document.querySelector('[data-btn-board-close]');
        btn_detail_close.setAttribute('onclick', "boardBoardClose();");
        const parameter = {
            board_seq: board_seq,
            board_name: board_name
        };
        layoutBoardDetail(parameter, function(){
            // 게시판 상세 페이지 열릴때
            document.querySelector('[data-board-list-main]').hidden = true;
        });
    }
    // 게시판 상세보기 닫기
    function boardBoardClose(){
         layoutBoardCloseDetail(function(){
            // 게시판 상세 페이지 닫힐때
            document.querySelector('[data-board-list-main]').hidden = false;
            // 게시판 다시 불러오기.
            document.querySelector('.page_num.active a').click()
        });
    }

</script>
