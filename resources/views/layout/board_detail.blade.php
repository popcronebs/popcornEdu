{{-- 공지사항 상세 화면 --}}
<main class="container-lg" style="max-width:828px" data-main-notice-detail hidden>
    <input type="hidden" data-board-detail-login-type value="{{session()->get('login_type')}}">
    <input type="hidden" data-board-detail-teach-seq value="{{session()->get('teach_seq')}}">

    {{-- 제목 --}}
    <section>
        <div class="pb-2 mb-1">
            <span class="text-b-20px scale-text-gray_05 pe-1" data-board-category>#카테</span>
            <img src="{{ asset('images/bar_icon.svg') }}" width="2" height="12" class="mx-2">
            <span class="text-b-20px scale-text-gray_05 ps-1" data-board-created_at>#날짜</span>
        </div>
        <div class="pb-5 mb-1">
            <h1 class="text-b-42px scale-text-black" data-board-title>#제목</h1>
        </div>
    </section>
    <div class="scale-bg-gray_03" style="height:1px"></div>
    @if(session()->get('login_type') == 'teacher' || session()->get('login_type') == 'admin')
    <div class="">
        <button onclick="layoutBoardDelete();" class="btn text-sb-20px text-danger px-2">삭제하기</button>
        <button onclick="layoutBoardEdit();" class="btn text-sb-20px text-secondary px-2">수정하기</button>
    </div>
    @endif
    {{-- padding 80px --}}
    <div style="height:80px"></div>

    {{-- 내용 --}}
    <section class="text-sb-20px scale-text-gray_06" data-board-content>

    </section>

    {{-- padding 80px --}}
    <div style="height:80px"></div>

    {{-- 첨부파일 --}}
    <section class="border-start border-2 border-dark ps-4" hidden>
        <div>
            <img src="{{ asset('images/file-attachment_icon.svg') }}" alt="">
            <span class="scale-text-black text-b-20px">첨부파일</span>
        </div>
        <div class="pt-2 mt-1">
            <ul data-file-att-bundle>
                <li class="text-sb-20px scale-text-gray_06">첨부파일.png</li>
            </ul>
        </div>
    </section>

    {{-- padding 80px --}}
    <div style="height:80px"></div>

    <section class="text-center">
        <button data-btn-board-close
        class="btn btn-primary-y cbtn-p-i py-3 rounded-pill text-sb-24px" onclick="noticeBoardCloseDetail();">
            목록으로 돌아가기 </button>
    </section>

    {{-- padding 160px --}}
    <div style="height:160px"></div>
</main>

<script>
    // data-btn-board-close 에 onclick = layoutBoardCloseDetail(callback) 넣어주기.
    // :게시판 상세보기
    let detail_data = null;
    function layoutBoardDetail(data, callback){
        const board_seq = data.board_seq;
        const board_name = data.board_name;
        detail_data = data;
        // 전송
        const page = "/manage/board/select";
        const parameter = {
            board_seq: board_seq,
            is_view:1,
            board_name: board_name,
            board_page_max: 1,
            page: 1
        };

        queryFetch(page, parameter, function(result) {
            const board_info = result.board;
            if(result.resultCode == 'success'){
                // main-notice-list 숨김처리
                // main-notice-detail 보이게 처리
                // document.querySelector(tag_name).hidden = true;
                if(callback) callback();
                const main_detail = document.querySelector("[data-main-notice-detail]");
                main_detail.hidden = false;
                // result.bdupfile 첨부파일
                const data = board_info.data[0];
                detail_data.writer_seq = data.writer_seq;
                detail_data.writer_type = data.writer_type;
                main_detail.querySelector("[data-board-category]").innerText = data.category_name;
                main_detail.querySelector("[data-board-created_at]").innerText = new Date((data.created_at||'')).format('yyyy.MM.dd');
                main_detail.querySelector("[data-board-title]").innerText = data.title;
                main_detail.querySelector("[data-board-content]").innerHTML = data.content;

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

    // :게시판 상세보기 > 닫기
    function layoutBoardCloseDetail(callback){
        // 초기화
        layoutBoardCloseDetailClear();
        // main-notice-list 보이게 처리
        // main-notice-detail 숨김처리
        // document.querySelector(tag_name).hidden = false;
        if(callback) callback();
        document.querySelector("[data-main-notice-detail]").hidden = true;
    }

    // :게시판 초기화
    function layoutBoardCloseDetailClear(){
        // 초기화
        const main_detail = document.querySelector("[data-main-notice-detail]");
        main_detail.querySelector("[data-board-category]").innerText = '';
        main_detail.querySelector("[data-board-created_at]").innerText = '';
        main_detail.querySelector("[data-board-title]").innerText = '';
        main_detail.querySelector("[data-board-content]").innerHTML = '';
        main_detail.querySelector("[data-file-att-bundle]").innerHTML = '';
        main_detail.querySelector("[data-file-att-bundle]").closest("section").hidden = true;
    }

    // 삭제하기 버튼 클릭
    function layoutBoardDelete(){
        const msg = `
            <div class="text-black text-sb-28px mb-2">본인확인을 위해</div>
            <div class="text-danger text-sb-28px pb-2">비밀번호를 입력해주세요.</div>
            <input class="form-control w-100 mt-4 p-4 text-sb-20px" type="password" data-inp-board-chk-password style="height:68px">
        `;
        sAlert('', msg, 3, function(){
            const inp_password = document.querySelector("[data-inp-board-chk-password]").value;
            if(inp_password){
                layoutBoardChkPassword(inp_password, function(){
                    // 삭제.
                    const mine_seq = document.querySelector("[data-board-detail-teach-seq]").value;
                    const mine_type = 'admin'?'teacher':document.querySelector("[data-board-detail-login-type]").value;

                    if(mine_seq != detail_data.writer_seq || mine_type != detail_data.writer_type){
                        toast('본인이 작성한 글만 삭제할 수 있습니다.');
                        return;
                    }

                    document.querySelector("#boardadd_inp_board_seq").value = detail_data.board_seq;
                    boardAddDelete('in_teacher', function(){
                        setTimeout(function(){
                            toast('삭제되었습니다.');
                            document.querySelector("#boardadd_inp_board_seq").value = '';
                            boardBoardClose();
                        }, 200);
                    });
                });
            }else{
                toast('비밀번호를 입력해주세요.');
            }
        });
    }
    // 수정하기 버튼 클릭
    function layoutBoardEdit(){
        const msg = `
            <div class="text-black text-sb-28px mb-2">본인확인을 위해</div>
            <div class="text-danger text-sb-28px pb-2">비밀번호를 입력해주세요.</div>
            <input class="form-control w-100 mt-4 p-4 text-sb-20px" type="password" data-inp-board-chk-password style="height:68px">
        `;
        sAlert('', msg, 3, function(){
            const inp_password = document.querySelector("[data-inp-board-chk-password]").value;
            if(inp_password){
                layoutBoardChkPassword(inp_password, function(){
                    // 수정.
                    const mine_seq = document.querySelector("[data-board-detail-teach-seq]").value;
                    const mine_type = document.querySelector("[data-board-detail-login-type]").value;

                    if(mine_seq != detail_data.writer_seq || mine_type != detail_data.writer_type){
                        toast('본인이 작성한 글만 삭제할 수 있습니다.');
                        return;
                    }
                    boardBoardClose();
                    click_tr.querySelector('[data-btn-edit]').click();
                });
            }else{
                toast('비밀번호를 입력해주세요.');
            }
        });
    }

    // 비밀번호 체크.
    function layoutBoardChkPassword(user_pw, callback){
        const page = "/student/member/info/check/pw";
        const parameter = {
            user_pw: user_pw
        };
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
               toast('비밀번호가 일치합니다.');
                if(callback != undefined)
                    callback();
            }else{
                toast('비밀번호가 일치하지 않습니다.');
            }
        });
    }

</script>
