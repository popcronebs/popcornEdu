{{-- 확인 모달 --}}
<div id="system_alert" hidden>
    <div class="modal modal-sheet position-fixed d-block top-50 start-50 translate-middle" tabindex="-1" role="dialog"
        style="width:27%;height:auto;z-index: 9999;">
        <div class="modal-dialog m-0 modal-lg" role="document">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header border-bottom-0">
                    <h1 class="modal-title cfs-5 msg_title"></h1>
                    <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close" onclick="this.closest('#system_alert').hidden = true;">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M22 10L10.0003 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round"/>
                            <path d="M10 10L21.9997 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round"/>
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
<footer id="layout_div_foot" class="container-fluid bg-light">
<div class="row foot_top border-bottom">
    <div class="col row align-items-center">
        <a class="col-auto link-secondary text-decoration-none cursor-pointer cfs-7 p-0 d-none">회사소개</a>
        <a class="col-auto link-secondary text-decoration-none cursor-pointer cfs-7 ms-5 px-0 d-none">선생님 지원</a>
        <a class="col-auto link-secondary text-decoration-none cursor-pointer cfs-7 ms-5 px-0 d-none">제휴문의</a>
        <a class="col-auto link-secondary text-decoration-none cursor-pointer cfs-7 ms-5 px-0">이용약관</a>
        <a class="col-auto link-secondary text-decoration-none cursor-pointer cfs-7 ms-5 px-0">개인정보처리방침</a>
    </div>
    <div class="col row align-items-center justify-content-end">
        <span class="col-auto cfs-7 ctext-gc1 p-0">공지사항</span>
        <span class="col-lg-6 cfs-7 ctext-gc0 ms-5">#공지사항의 내용.</span>
        <span class="col-auto cfs-7 ctext-gc1 me-5">#2024.01.01</span>
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
          <div class="py-3 mt-4">
            <span class="ctext-gc1">주식회사 도원이앤아이</span>
          </div>
          <div class="py-2">
            {{-- 대표 --}}
            <span class="ctext-gc1">대표</span>
            <img src="{{ asset('images/bar_icon.svg') }}" alt="">
            <span class="ctext-gc1" data="#대표이름">#대표이름</span>
            {{-- 주소 --}}
            <span class="ctext-gc1">주소</span>
            <img src="{{ asset('images/bar_icon.svg') }}" alt="">
            <span class="ctext-gc1" data="#주소">#주소</span>
            {{-- 사업자등록번호 --}}
            <span class="ctext-gc1">사업자등록번호</span>
            <img src="{{ asset('images/bar_icon.svg') }}" alt="">
            <span class="ctext-gc1" data="#사업자등록번호">#사업자등록번호</span>
          </div>
          <div>
            {{-- 대표번호 --}}
            <span class="ctext-gc1">대표번호</span>
            <img src="{{ asset('images/bar_icon.svg') }}" alt="">
            <span class="ctext-gc1">
            {{-- 제휴문의 --}}
            <span class="ctext-gc1">제휴문의</span>
            <img src="{{ asset('images/bar_icon.svg') }}" alt="">
            <span class="ctext-gc1" data="#제휴문의">#제휴문의</span>
          </div>
          <div class="pt-4">
            <span class="ctext-gc1">Copyright© 도원이앤아이 Corp. Al Rights Reserved.</span>
          </div>
    </div>
    <div class="col row text-end">
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

    </div>
</div>
</footer>
<script>

</script>
</body>

</html>
