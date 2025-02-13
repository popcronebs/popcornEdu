@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '공지사항')
{{-- 학생 컨텐츠 --}}
@section('layout_coutent')
<style>
ol,
ul {
  list-style: none;
}
</style>
<input type="hidden" id="nc" value="{{ $nc }}">
<div class="col mx-0 mb-3 pt-5 row position-relative">
    {{-- 공지사항 리스트 --}}
    <main data-main-notice-list>
        {{-- 상단 --}}
       <article class="pt-5 px-0">
            <div class="row">
                <div class="col-auto pb-2 mb-1">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('images/notice_bell_icon.svg')}}" width="72" class="pt-2">
                        <span class="cfs-1 fw-semibold align-middle" id="sp_title">공지사항</span>
                    </div>
                    <div class="pt-4 pb-2">
                        <span class="cfs-3 fw-medium" hidden>여러가지 학습을 한 눈에 쉽게 알아볼 수 있어요.</span>
                    </div>
                </div>
                <div class="col position-relative">
                    <img src="{{ asset('images/notice_character.svg') }}" class="bottom-0 end-0 position-absolute">
                 </div>
           </div>
       </article>

       {{-- padding 120 --}}
        <div>
              <div class="py-5"></div>
              <div class="pt-4"></div>
        </div>

        {{-- 공지사항 리스트 --}}
        <article>
            {{-- tab, search --}}
            <section class="pb-5 mb-1">
                <div class="row justify-content-between">
                    <ul class="col-auto d-inline-flex gap-2 mb-3">
                        @if(!empty($notice))
                            <li>
                                <button class="btn_notice_tab btn-ms-primary text-sb-24px rounded-pill scale-text-white px-32 {{$nc == 'faq'?'':'active'}}"
                                data="{{ $notice->function_code }}" onclick="noticeTab('{{ $notice->function_code }}',this)">{{ $notice->code_name }}</button>
                            </li>
                        @endif
                        @if(!empty($faq))
                            <li>
                                <button class="btn_notice_tab btn-ms-primary text-sb-24px rounded-pill scale-bg-gray_01 scale-text-gray_05 scale-text-white-hover px-32 {{$nc == 'faq'?'active':''}}"
                                data="{{ $faq->function_code }}" onclick="noticeTab('{{ $faq->function_code  }}',this)">{{ $faq->code_name }}</button>
                            </li>
                        @endif
                    </ul>
                    <div class="col-auto">
                        <label class="label-search-wrap" style="height:auto;">
                            <input id="notice_inp_search" type="text" class="lg-search border-gray rounded-pill text-m-20px"
                            placeholder="공지사항을 검색해보세요." onkeyup="if(event.keyCode == 13) noticeBoardSelect()" {{$nc == 'faq'?'hidden':''}}>
                            <input id="notice_inp_faq_search" type="text" class="lg-search border-gray rounded-pill text-m-20px"
                            placeholder="질문을 검색해보세요." onkeyup="if(event.keyCode == 13) noticeBoardFaqSelect()" {{$nc == 'faq'?'':'hidden'}}>

                        </label>
                    </div>
                </div>
            </section>

            {{-- 공지사항 목록 --}}
            <section id="notice_section_notice_list">
                <div style="border-top: 2px solid #222;">
                    <table class="table text-center">
                        <thead>
                            <tr class="text-r-24px">
                                <td class="fw-medium py-4 col-1">번호</td>
                                <td class="fw-medium py-4 col-1">구분</td>
                                <td class="fw-medium py-4 col">제목</td>
                                <td class="fw-medium py-4 col-2">등록일</td>
                                <td class="fw-medium py-4 col-1">조회수</td>
                            </tr>
                        </thead>
                        <tbody id="notice_tby_board">
                            <tr class="copy_tr_board text-r-24px cursor-pointer"
                            onclick="noticeBoardDetail(this)" hidden>
                                <td class="index py-4">
                                    <!-- <img src="{{ asset('images/bell_icon.svg') }}" width="32"> -->
                                </td>
                                <td class="py-4">
                                    <span class="board_name scale-text-gray_05">공지</span>
                                </td>
                                <td class="py-4">
                                    <span class="title scale-text-black">제목</span>
                                </td>
                                <td class="py-4">
                                    <span class="created_at scale-text-gray_05">2024.00.00</span>
                                </td>
                                <td class="py-4">
                                    <span class="views scale-text-gray_05">3</span>
                                </td>
                                <input type="hidden" class="board_seq">
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- padding 80px 48-32 --}}
                <div>
                    <div class="py-4"></div>
                    <div class="pt-3"></div>
                </div>

                {{-- 페이징  --}}
                <div class="d-flex justify-content-center">
                    <ul class="pagination col-auto" id="notice_ul_page">
                        <button href="javascript:void(0)" class="btn p-0 prev" id="page_prev" onclick="noticeBoardSelect()">
                            <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                        </button>
                        <li class="page-item"  hidden>
                            <a class="page-link" onclick="">0</a>
                        </li>
                        <span class="page" id="page_first" hidden
                        onclick="noticeBoardSelect(this.innerText)">0</span>
                        <span class="page_num page active">1</span>
                        <button href="javascript:void(0)" class="btn p-0 next" id="page_next">
                            <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                        </button>
                      </ul>
                </div>

            </section>

            {{-- 자주묻는 질문 --}}
            <section id="notice_section_faq_list" {{ $nc == 'faq'?'':'hidden' }}>
                <div style="border-top: 2px solid #222;">
                    @if(!empty($board_faqs))
                        @php
                            $grouped = $board_faqs->groupBy('category');
                            $in_idx = 0;
                            $idx = 0;
                        @endphp
                        @foreach($grouped as $key => $group)
                            <div class="div_category_row row py-4 border-top">
                                <div class="col-lg-3 ps-1">
                                    <div class="py-3"></div>
                                    <span class="text-sb-24px ps-5">
                                        {{ $group[0]->category_name}}
                                    </span>
                                    <div class="py-3"></div>
                                </div>
                                <div class="col-lg pt-1">
                                    @foreach($board_faqs->where('category', $group[0]->category) as $faq)
                                    <div class="row div_board_row">
                                        <div class="col-2">
                                            <div class="py-3"></div>
                                            <span class="text-sb-24px scale-text-gray_05">{{ sprintf('%02d', $in_idx + 1) }}</span>
                                            <div class="py-3"></div>
                                        </div>
                                        <div class="col">
                                            <div class="d-flex">
                                                <div class="col">
                                                    <div class="py-3"></div>
                                                    <span class="board_title text-sb-24px">{{ $faq->title }}</span>
                                                    <div class="py-3"></div>
                                                </div>
                                                <div class="col-auto d-flex align-items-center">
                                                    <button class="btn" onclick="noticeOpenFaq(this)" data="close">
                                                        <img class="arrow_down" src="{{ asset('images/dropdown_arrow_down.svg') }}" width="42">
                                                        <img class="arrow_up" src="{{ asset('images/dropdown_arrow_up.svg') }}" width="42" hidden>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="board_content text-r-20px scale-text-gray_05" hidden>
                                                {!! $faq->content !!}
                                            </div>
                                        </div>
                                    </div>
                                    @php $in_idx++; @endphp
                                    @endforeach
                                </div>
                            </div>
                            @php
                                $in_idx = 0;
                                $idx++;
                            @endphp
                        @endforeach
                    @endif

                </div>
            </section>
        </article>
    </main>

    @include('layout.board_detail')
</div>

<script>
    noticeBoardSelect();
    if('{{$nc}}' == 'faq' ){
        document.querySelector('[data="faq"]').click();
    }

    // 공지사항 탭
    function noticeTab(code, vthis) {
        // 탭 활성화
        const btn_notice_tab = document.querySelectorAll('.btn_notice_tab');
        btn_notice_tab.forEach(function(el, idx){
            el.classList.add('scale-bg-gray_01');
            el.classList.add('scale-text-gray_05');
            el.classList.add('scale-text-white-hover');
            el.classList.remove('scale-text-white');
            el.classList.remove('active');
        });
        vthis.classList.remove('scale-bg-gray_01');
        vthis.classList.remove('scale-text-gray_05');
        vthis.classList.remove('scale-text-white-hover');
        vthis.classList.add('scale-text-white');
        vthis.classList.add('active');


        if(code == 'notice'){
            document.querySelector("#sp_title").innerText = '공지사항';
            // 공지사항 목록
            document.querySelector('#notice_inp_search').value = '';
            noticeBoardSelect();
            // 1 초뒤
            setTimeout(function(){
                document.querySelector("#notice_section_notice_list").hidden = false;
                document.querySelector("#notice_section_faq_list").hidden = true;
                document.querySelector("#notice_inp_search").hidden = false;
                document.querySelector("#notice_inp_faq_search").hidden = true;
            }, 400);
        }else{
            document.querySelector("#sp_title").innerText = '자주묻는질문';
            document.querySelector('#notice_inp_faq_search').value = '';
            noticeBoardFaqSelect();
            document.querySelector("#notice_section_notice_list").hidden = true;
            document.querySelector("#notice_section_faq_list").hidden = false;
            document.querySelector("#notice_inp_search").hidden = true;
            document.querySelector("#notice_inp_faq_search").hidden = false;
        }


    }

    // 공지사항 목록 불러오기.
    var mindex = 0;
    function noticeBoardSelect(page_num){
        const board_name = document.querySelector(".btn_notice_tab.active").getAttribute("data");
        const search_word = document.querySelector("#notice_inp_search").value;
        if(page_num == undefined)
            page_num = 1;

        if(page_num == 1){
           mindex = 0;
        }
        // 전송
        const page = "/manage/board/select";
        const parameter = {
            board_name: board_name,
            board_page_max: 5,
            search_word: search_word,
            search_type:"title",
            page:page_num,
        };

        queryFetch(page, parameter, function(result) {
            const board_info = result.board;
            if(result.resultCode == 'success'){
                // 페이징 처리
                boardPaging(board_info);
                //게시판 로우 리스트(리스트 형식과 갤러리 분리)
                boardlistRows(board_info);
            }
        });
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
        const notice_ul_page = document.querySelector("#notice_ul_page");
        //prev button, next_button
        const page_prev = notice_ul_page.querySelector("#page_prev");
        const page_next = notice_ul_page.querySelector("#page_next");
        //페이징 처리를 위해 기존 페이지 삭제
        document.querySelectorAll("#notice_ul_page .page_num").forEach(element => {
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
            if(page_end <= 10)
                page_start = 1;
        }


        let is_next = false;
        for(let i = page_start; i <= page_end; i++){
            const copy_page_first = page_first.cloneNode(true);
            copy_page_first.innerText = i;
            copy_page_first.removeAttribute("id");
            copy_page_first.classList.add("page_num");
            copy_page_first.hidden = false;
            //현재 페이지면 active
            if(i == current_page){
                copy_page_first.classList.add("active");
            }
            //#page_first 뒤에 붙인다.
            notice_ul_page.insertBefore(copy_page_first, notice_ul_page.querySelector("#page_next"));
            //시작 페이지 보다 11보다 큰 i 이면 page_prev disabled 을 제거
            if(i > 11){
                page_prev.classList.remove("disabled");
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
        // if(page_next.getAttribute("onclick") == null){
        // 같은 게시판이면 null일때 이벤트 처리 하면 되지만, 게시판이 바뀌면 어차피 수정처리.
            page_next.setAttribute("onclick", "noticeBoardSelect("+(last_page)+")");
        // }
        // .page_loding_place 숨김처리
        document.querySelectorAll(".loding_place").forEach(element => {
            element.remove();
        });
        // #notice_ul_page 숨김처리 해제
        if(data.length != 0)
            notice_ul_page.hidden = false;
    }

    // 게시판 리스트 데이터 정렬
    function boardlistRows(board_info){
        const tby_board = document.querySelector("#notice_tby_board");
        const copy_tr_board = tby_board.querySelector(".copy_tr_board").cloneNode(true);
        //테이블 로우를 초기화
        document.querySelectorAll("#notice_tby_board tr").forEach(element => {
            element.remove();
        });
        copy_tr_board.hidden = true;
        tby_board.appendChild(copy_tr_board);
        //게시판 로우 리스트
        const data = board_info.data;
        const from = board_info.from*1;
        const to = board_info.to*1;
        const total = board_info.total*1;
        let first = total - (from - 1);
        for(let i = 0; i < data.length; i++){
            const tr_board = tby_board.querySelector(".copy_tr_board").cloneNode(true);

            tr_board.classList.remove("copy_tr_board");
            tr_board.classList.add("tr_board");
            tr_board.hidden = false;
            tr_board.querySelector(".index").innerText = data[i].is_important == 'Y' ? '중요':from + (i) - mindex;
            if(data[i].is_important == 'Y') {
                tr_board.querySelector(".index").classList.add('text-danger');
                mindex++; // 중요일 경우, 순서가 밀리는 것을 방지.
            }
            tr_board.querySelector(".board_seq").value = data[i].id;
            tr_board.querySelector(".board_name").innerText = data[i].board_name == 'notice' ? '공지' : 'FAQ';
            tr_board.querySelector(".title").innerText = data[i].title;
            tr_board.querySelector(".created_at").innerText = new Date((data[i].created_at||'')).format('yyyy.MM.dd');
            tr_board.querySelector(".views").innerText = data[i].views||'0';

            tby_board.appendChild(tr_board);
            first--;
        }
    }

    // 자주묻는질문 > 열기
    function noticeOpenFaq(vthis){
        const board_content = vthis.parentElement.parentElement.parentElement.querySelector(".board_content");
        if(vthis.getAttribute("data") == 'close'){
            vthis.setAttribute("data", "open");
            vthis.querySelector(".arrow_down").hidden = true;
            vthis.querySelector(".arrow_up").hidden = false;
            board_content.hidden = false;
        }else{
            vthis.setAttribute("data", "close");
            vthis.querySelector(".arrow_down").hidden = false;
            vthis.querySelector(".arrow_up").hidden = true;
            board_content.hidden = true;
        }
    }

    // 자주묻는질문 > 질문검색
    function noticeBoardFaqSelect(){
        const search_word = document.querySelector("#notice_inp_faq_search").value;
        // div_board_row 안에 board_content 와 board_title 검색
        // 그리고 상위 div_category_row 도 보이고 안보이게 처리.
        const div_category_row = document.querySelectorAll(".div_category_row");
        div_category_row.forEach(function(el, idx){
            const div_board_row = el.querySelectorAll(".div_board_row");
            let is_show = false;
            div_board_row.forEach(function(el, idx){
                const board_title = el.querySelector(".board_title").innerText;
                const board_content = el.querySelector(".board_content").innerText;
                if(board_title.indexOf(search_word) != -1 || board_content.indexOf(search_word) != -1){
                    el.hidden = false;
                    is_show = true;
                }else{
                    el.hidden = true;
                }
            });
            if(is_show){
                el.hidden = false;
            }else{
                el.hidden = true;
            }
        });
    }

    // 공지사항 > 상세보기
    function noticeBoardDetail(vthis){
        const board_seq = vthis.querySelector(".board_seq").value;

        // 전송
        const page = "/manage/board/select";
        const parameter = {
            board_seq: board_seq,
            is_view:1,
            board_name: 'notice',
            board_page_max: 1,
            page: 1
        };

        queryFetch(page, parameter, function(result) {
            const board_info = result.board;
            if(result.resultCode == 'success'){
                // main-notice-list 숨김처리
                // main-notice-detail 보이게 처리
                document.querySelector("[data-main-notice-list]").hidden = true;
                const main_detail = document.querySelector("[data-main-notice-detail]");
                main_detail.hidden = false;
                // result.bdupfile 첨부파일
                const data = board_info.data[0];
                main_detail.querySelector("[data-board-category]").innerText = data.category_name;
                main_detail.querySelector("[data-board-created_at]").innerText = new Date((data.created_at||'')).format('yyyy.MM.dd');
                main_detail.querySelector("[data-board-title]").innerText = data.title;
                main_detail.querySelector("[data-board-content]").innerHTML = data.content;
                vthis.querySelector(".views").innerText = data.views;

                //첨부파일이 있으면
                if(result.bdupfile.length > 0){
                    main_detail.querySelector("[data-file-att-bundle]").closest("section").hidden = false;
                    const file_att_bundle = main_detail.querySelector("[data-file-att-bundle]");
                    file_att_bundle.innerHTML = '';
                    result.bdupfile.forEach(function(r_data, idx){
                        const li = document.createElement("li");
                        li.classList.add("text-sb-20px");
                        li.classList.add("scale-text-gray_06");
                        li.classList.add("cursor-pointer");

                        let bdupfile_path = r_data.bdupfile_path;
                        // 앞 경로 제거 , 마지막 [_*.확장자] 에서 확장자 남기고 앞가지 제거
                        bdupfile_path = bdupfile_path.replace('uploads/boardfiles/', '');
                        bdupfile_path = bdupfile_path.replace(/_[0-9a-zA-Z가-힣ㄱ-ㅎㅏ-ㅣ]*\./, '.');
                        li.innerText = bdupfile_path;
                        li.setAttribute("path", r_data.bdupfile_path);

                        //클릭시 새창 다운로드
                        li.addEventListener("click", function() {
                            const path = this.getAttribute("path");
                            window.open('/storage/'+path);
                        });
                        file_att_bundle.appendChild(li);

                    });
                }
            }
        });
    }

    // 공지사항 > 상세보기 > 닫기
    function noticeBoardCloseDetail(){
        // 초기화
        noticeBoardCloseDetailClear();
        // main-notice-list 보이게 처리
        // main-notice-detail 숨김처리
        document.querySelector("[data-main-notice-list]").hidden = false;
        document.querySelector("[data-main-notice-detail]").hidden = true;
    }

    // 공지사항 > 상세보기 > 닫기 > 초기화
    function noticeBoardCloseDetailClear(){
        // 초기화
        const main_detail = document.querySelector("[data-main-notice-detail]");
        main_detail.querySelector("[data-board-category]").innerText = '';
        main_detail.querySelector("[data-board-created_at]").innerText = '';
        main_detail.querySelector("[data-board-title]").innerText = '';
        main_detail.querySelector("[data-board-content]").innerHTML = '';
        main_detail.querySelector("[data-file-att-bundle]").innerHTML = '';
        main_detail.querySelector("[data-file-att-bundle]").closest("section").hidden = true;
    }
</script>
@endsection
