{{-- 확인 모달 --}}
<div id="system_alert" hidden>
    <div class="modal modal-sheet position-fixed d-block top-50 start-50 translate-middle" tabindex="-1" role="dialog" style="width:25%;height:auto;z-index: 9999;">
        <div class="modal-dialog m-0" role="document">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title cfs-5 msg_title"></h1>
                    <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close" onclick="this.closest('#system_alert').hidden = true;">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 10L10.0003 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round" />
                            <path d="M10 10L21.9997 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round" />
                        </svg>
                    </button>
                </div>
                <div class="modal-body py-0">
                    <p class="msg_content cfs-6"></p>
                </div>
                <div class="modal-footer flex-column align-items-stretch w-100 gap-2 pb-3 border-top-0">
                    <button type="button" class="msg_btn1 btn btn-lg btn-primary-y"></button>
                    <button type="button" class="msg_btn2 btn btn-lg btn-light ctext-gc1" hidden></button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 모달 / 게시판 상세보기. --}}
<div class="modal fade " id="modal_div_board_detail" tabindex="-1" aria-labelledby="exampleModalLabel"
    style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog rounded modal-lg">
        <div class="modal-content border-none rounded p-3 modal-shadow-style">
            <div class="modal-header border-bottom-0">
                <h1 class="modal-title fs-5 text-b-24px" id="">
                    게시판 상세보기
                </h1>
                <button type="button" style="width:32px;height:32px" class="btn-close close-btn"
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{-- :게시판 상세보기. --}}
                @include('layout.board_detail')
            </div>
        </div>
    </div>
</div>
{{--  서비스 이용약관 --}}
<div class="modal fade" id="mmodal_terms_foot" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog rounded modal-dialog-scrollable modal-dialog-centered">
      <div class="modal-content border-none rounded p-3 modal-shadow-style">
        <div class="modal-header border-bottom-0 ">
          <h1 class="modal-title fs-5 text-b-24px text-center text-b-24px" id="">
            서비스 이용약관
          </h1>
          <!-- <button type="button" class="btn-close close-btn"  hidden></button> -->
        </div>
        <div class="modal-body" style="max-height: 420px;">
          <p class="text-sb-20px mb-32">제 1장(총칙)</p>
          <p class="text-sb-18px mb-12">제 1장(목적)</p>
          <p class="gray-color">이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다.</p>
          <p class="text-sb-18px my-4">제 2장(정의)</p>
          <p>이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다.</p>
          <p>이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다.</p>
          <p>이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다.</p>

        </div>
        <div class="modal-footer border-top-0 mt-80 py-2">
          <div class="row w-100 ">
            <div class="col-12 p-0">
              <button
              type="button" class="btn-lg-primary text-b-24px rounded scale-text-white w-100 " data-bs-dismiss="modal" style="display:inline;">확인 했습니다.</button>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<footer id="layout_div_foot" class="container-fluid bg-light">
    <div class="row foot_top border-bottom">
        <div class="col row align-items-center">
            <a class="col-auto link-secondary text-decoration-none cursor-pointer cfs-7 p-0" hidden>회사소개</a>
            <a class="col-auto link-secondary text-decoration-none cursor-pointer cfs-7 ms-5 px-0" hidden>선생님 지원</a>
            <a class="col-auto link-secondary text-decoration-none cursor-pointer cfs-7 ms-5 px-0" hidden>제휴문의</a>
            <a class="col-auto link-secondary text-decoration-none cursor-pointer cfs-7 ms-5 px-0" href="javascript:footOpenModalTerms('terms');">이용약관</a>
            <a class="col-auto link-secondary text-decoration-none cursor-pointer cfs-7 ms-5 px-0" href="javascript:footOpenModalTerms('privacy');">개인정보처리방침</a>
        </div>
        <div class="col row align-items-center justify-content-end cursor-pointer" onclick="dashBoardBoardClick(this)">
            <span class="col-auto cfs-7 ctext-gc1 p-0">공지사항</span>
            <input type="hidden" id="notice_seq" data-board-seq>
            <span class="col-lg-6 cfs-7 ctext-gc0 ms-5" id="notice_title"></span>
            <span class="col-auto cfs-7 ctext-gc1 me-5" id="notice_date"></span>
        </div>
    </div>
    <div class="row foot_btm">
        <div class="col">
            <div class="dropdown">
                <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('/images/popcorn_logo.svg') }}">
                    <img src="{{ asset('/images/dropdown_arrow_down3.svg') }}" class="ms-2">
                </button>
                <ul class="dropdown-menu">
                    <li><button class="dropdown-item" type="button">#미정1</button></li>
                    <li><button class="dropdown-item" type="button">#미정2</button></li>
                </ul>
            </div>
            {{-- <div class="py-3 mt-4">
                <span class="ctext-gc1">주식회사 도원이앤아이</span>
            </div> --}}
            <div class="py-2">
                {{-- 대표 --}}
                <span class="ctext-gc1">주식회사 팝콘에듀</span>
                <img src="{{ asset('images/bar_icon.svg') }}" alt="">
                <span class="ctext-gc1" data="#대표이름">대표이사 박범신</span>
            </div>
            <div class="py-2">
                {{-- 주소 --}}
                <span class="ctext-gc1">주소</span>
                <img src="{{ asset('images/bar_icon.svg') }}" alt="">
                <span class="ctext-gc1" data="#주소">부산광역시 해운대구 세실로 45, 9층 901호(좌동, 대승프라자)</span>
                {{-- 사업자등록번호 --}}
                <span class="ctext-gc1">사업자등록번호</span>
                <img src="{{ asset('images/bar_icon.svg') }}" alt="">
                <span class="ctext-gc1" data="#사업자등록번호">662-86-01102</span>
            </div>
            <div>
                {{-- 대표번호 --}}
                {{-- <span class="ctext-gc1">대표번호</span>
                <img src="{{ asset('images/bar_icon.svg') }}" alt=""> --}}
                {{-- 제휴문의 --}}
                {{-- <span class="ctext-gc1" > <span class="ctext-gc1">제휴문의</span>
                <img src="{{ asset('images/bar_icon.svg') }}" alt="">
                <span class="ctext-gc1" data="#제휴문의">#제휴문의</span> --}}
            </div>
            <div class="pt-4">
                <span class="ctext-gc1">Copyright© 주식회사 팝콘에듀 Corp. Al Rights Reserved.</span>
            </div>
        </div>
        {{-- <div class="col row text-end">
            <div class="dropup-center dropup">
                <button class="btn border border-2 rounded-pill cbtn-p col-4 my-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="row">
                        <span class="col text-start ctext-gc1 fw-semibold">FAMILY STIE</span>
                        <img src="{{ asset('/images/dropdown_arrow_up3.svg') }}" class="col-auto">
    </div>
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="#">#미정1</a></li>
        <li><a class="dropdown-item" href="#">#미정2</a></li>
    </ul>
    </div>
    <div class="pt-5">
        <div>
            <span class="ctext-gc1">(주)도원이앤아이 대표번호</span>
        </div>
        <div class="py-2">
            <span class="ctext-bc0 cfs-1">1588-0000</span>
        </div>
    </div>
    </div> --}}
    </div>
</footer>
<script>
    const page = "/manage/board/select";
    const parameter = {
        board_name: "notice",
        board_page_max: 1,
        page: 1,
        search_type: "title",
        search_word: ""
    };
    queryFetch(page, parameter, function(result) {
        if ((result.resultCode || '') == 'success') {
            console.log(result.board.data[0]);
            document.querySelector('#notice_seq').value = result.board.data[0].id;
            document.querySelector('#notice_title').innerText = result.board.data[0].title;
            document.querySelector('#notice_date').innerText = new Date(result.board.data[0].created_at).toISOString().split('T')[0].replace(/-/g, '.');
        } else {
            sAlert('', '저장문구를 가져오는데 실패하였습니다.');
        }
    });

    // 공지사항 이벤트 클릭
    function dashBoardBoardClick(vthis){
        const modal = document.querySelector('#modal_div_board_detail');
        // 두번 클릭 안하게 하려면
        vthis.setAttribute('onclick', '');
        setTimeout(function(){
            vthis.setAttribute('onclick', 'dashBoardBoardClick(this)');
        },1000);
        const board_seq = vthis.querySelector('[data-board-seq]').value;
        const board_name = 'notice';
        // data-btn-board-close 에 onclick = layoutBoardCloseDetail(callback) 넣어주기.
        // :게시판 상세보기 양식1
        const btn_detail_close = modal.querySelector('[data-btn-board-close]');
        btn_detail_close.setAttribute('onclick', "dashBoardBoardClose();");
        const parameter = {
            board_seq: board_seq,
            board_name: board_name,
            modal_id:'#modal_div_board_detail'
        };
        layoutBoardDetail(parameter, function(){
            // 게시판 상세 페이지 열릴때
            const myModal = new bootstrap.Modal(document.getElementById('modal_div_board_detail'), {
                keyboard: false,
                backdrop: 'true'
            });
            myModal.show();
        });
    }

    // 공지사항 상세보기 모달 닫기.
    function dashBoardBoardClose(){
        layoutBoardCloseDetail(function(){
            // 게시판 상세 페이지 닫힐때
           const modal = document.getElementById('modal_div_board_detail');
            modal.querySelector('.btn-close').click();
        });
    }

    // 서비스 이용약관 모달 오픈
    function footOpenModalTerms(type){
        const modal = document.getElementById('mmodal_terms_foot');
        if(type == 'terms'){
            modal.querySelector('.modal-title').innerText = '서비스 이용약관';
            modal.querySelector('.modal-body').innerHTML = `
          <p class="text-sb-20px mb-32">제 1장(총칙)</p>
          <p class="text-sb-18px mb-12">제 1장(목적)</p>
          <p class="gray-color">이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다.</p>
          <p class="text-sb-18px my-4">제 2장(정의)</p>
          <p>이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다.</p>
          <p>이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다.</p>
          <p>이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다. 이용약관 내용이 들어갈 영역입니다.</p>
`;
        }
        else if(type == 'privacy'){
            modal.querySelector('.modal-title').innerText = '개인정보처리방침';
            modal.querySelector('.modal-body').innerHTML = `
            <p class="text-sb-20px mb-32">제 1장(총칙)</p>
            <p class="text-sb-18px mb-12">제 1장(목적)</p>
            <p class="gray-color">개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다.</p>
            <p class="text-sb-18px my-4">제 2장(정의)</p>
            <p>개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다.</p>
            <p>개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다.</p>
            <p>개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다. 개인정보처리방침 내용이 들어갈 영역입니다.</p>
`;
        }
        const myModal1 = new bootstrap.Modal(document.getElementById('mmodal_terms_foot'), {
            backdrop: 'static',
            keyboard: false,
        });
        myModal1.show();
    }
</script>
</body>

</html>
