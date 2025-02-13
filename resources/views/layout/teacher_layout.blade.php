@include('layout.teacher_head')
@php
// 현재 세션의 id를 가져와서 group_seq를 가져온다.
$group_seq = session()->get('group_seq');
$menus = \App\Menu::
select('menus.*', 'menu_groups.group_seq', 'menu_groups.menu_type', 'menu_groups.menu_idx', 'menu_groups.menu_pt_seq', DB::raw('menu_groups.is_use as group_is_use'))
->leftJoin('menu_groups', 'menus.id', '=', 'menu_groups.menu_seq')
->where('menu_groups.group_seq', $group_seq);
$menus = $menus
->orderBy('menu_type', 'desc')
->orderBy('menu_idx', 'asc')
->orderBy('menus.id', 'asc')
->get();
// $menus = $menus->toArray();

// 현제 페이지의 url을 가져온다.
$current_url = url()->current();
// 도메인 이후의 url을 가져온다.
$current_url = str_replace(url('/'), '', $current_url);
$current_menu_pt_seq = '';
$current_menu_seq = '';

// 시스템 / 사용문의 답변대기 수치 가져오기.
// 추후 index 때문이라도 답변대기/완료를 column으로 빼는게 좋을듯.
//where board_name in qna, sdqna
$board_qna_cnt = \App\Board::whereIn('board_name', ['qna', 'sdqna'])
->where(function ($query) {
$query ->whereNull('comment_wr_seq')
->orWhere('comment_wr_seq', '=', '0');
})
->groupBy('board_name')
->selectRaw('board_name, count(*) as cnt')
->pluck('cnt', 'board_name');

$is_menu_gray = false;
$now_menu_seq = "";
$now_menu_pt_seq = "";
foreach ($menus as $idx => $menu) {
if ($menu['menu_url'] == $current_url) {
$now_menu_seq = $menu['id'];
$now_menu_pt_seq = $menu['menu_pt_seq'];
$is_menu_gray = true;
break;
}
}
$teacher = \App\Teacher::find(session()->get('teach_seq'));
@endphp
{{-- 부트스탭을 이용해서 왼쪽에 사이드 메뉴 --}}
{{-- 1 줄은 Popcon-Edu --}}
{{-- 2 줄은 -님 환영합니다. --}}
{{-- 3 줄은 사용자 관리 --}}
{{-- 4 줄은 게시판관리, 다음줄은 컨텐츠관리 , 다음줄은 사이트관리, 다음줄은 시스템관리, 다음줄은 문자/알림관리 다음줄은 메출관리, 다음줄은 통계분석 --}}
<style>
    #layout_div_navbar_left {
        min-height: calc(100vh - 96px);
    }

    #layout_div_navbar_left .dropdown-toggle {
        outline: 0;
    }

    #layout_div_navbar_left .btn-toggle {
        padding: .25rem .5rem;
        font-weight: 600;
        color: var(--bs-emphasis-color);
        background-color: transparent;
    }

    #layout_div_navbar_left .btn-toggle:hover,
    #layout_div_navbar_left .btn-toggle:focus {
        color: rgba(var(--bs-emphasis-color-rgb), .85);
        background-color: var(--bs-tertiary-bg);
    }

    #layout_div_navbar_left .btn-toggle::before {
        width: 1.25em;
        line-height: 0;
        content: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%280,0,0,.5%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='1' d='M5 14l6-6-6-6'/%3e%3c/svg%3e");
        transition: transform .35s ease;
        transform-origin: .5em 50%;
    }

    #layout_div_navbar_left [data-bs-theme="dark"] .btn-toggle::before {
        content: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%28255,255,255,.5%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='1' d='M5 14l6-6-6-6'/%3e%3c/svg%3e");
    }

    #layout_div_navbar_left .btn-toggle[aria-expanded="true"] {
        color: rgba(var(--bs-emphasis-color-rgb), .85);
    }

    #layout_div_navbar_left .btn-toggle[aria-expanded="true"]::before {
        transform: rotate(90deg);
    }

    #layout_div_navbar_left .btn-toggle-nav a {
        padding: .1875rem .5rem;
        margin-top: .125rem;
        margin-left: 1.25rem;
    }

    #layout_div_navbar_left .btn-toggle-nav a:hover,
    #layout_div_navbar_left .btn-toggle-nav a:focus {
        background-color: var(--bs-tertiary-bg);
    }

    #layout_div_navbar_left .scrollarea {
        overflow-y: auto;
    }

    .tag_is_delete {
        display: none !important;
    }

    /* navbar-nav 안에  nav-item 는 오른쪽 보더 단 마지막은 보더 없음. */
    #layout_main_top {
        height: 55px;
    }

    #layout_main_top .navbar-nav .nav-item {
        border-right: 1px solid #dee2e6;
    }

    #layout_main_top .navbar-nav .nav-item:last-child {
        border-right: none;
    }

    .menu_fd .menu_list button {
        font-weight: 500 !important;
        font-size: 0.9rem !important;
        padding-left: 23px !important;
    }

    .main_top_p {
        padding-left: 110px;
        padding-right: 110px;
    }

    .btn_top_icon img {
        width: 42px;
        height: 42px;
    }

    #layout_main_top2 {
        height: 110px;
    }

    #layout_div_foot .foot_top {
        height: 130px;
        padding: 0 115px;
    }

    #layout_div_foot .foot_btm {
        padding: 45px 115px;
    }

    #layout_div_content {
        padding: 0px 120px;
    }

    @media (max-width: 767.98px) {
        .main_top_p {
            padding-left: 15px;
            padding-right: 15px;
        }

        #layout_div_content {
            padding: 12px 11px;
        }
    }
</style>

<div class="content row m-0 p-0 h-100">
    <header class="p-0">
        {{-- 상단 네브바 --}}
        <nav id="layout_main_top" class="navbar navbar-expand-sm bg-primary-y navbar-light d-none">
            <div class="container-fluid main_top_p">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="col-auto">
                    <span class="text-white">EBS 팝콘 바로가기</span>
                </div>
                <div class="col collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
                    <ul class="navbar-nav">
                        {{-- 상단 / 메뉴 가져오기. --}}
                        @foreach ($menus->where('menu_type', 'top')->where('is_use', 'Y')->where('group_is_use','<>','N') as $idx => $menu)
                        <li class="py-0 nav-item border-dark">
                            <a class="py-0 nav-link text-white" href="{{ $menu['menu_url'] }}">
                                {{ $menu['menu_name'] }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @if(!$menus->where('menu_type', 'top')->where('is_use', 'Y')->where('group_is_use','<>','N'))
                    <a class="btn btn-primary" href="/teacher/logout">로그아웃</a>
                    @endif
                </div>
            </div>
        </nav>
        <nav id="layout_main_top2" class="row border align-items-center main_top_p mx-0">
            {{-- 메뉴 쪽 로고 --}}
            <a class="col-auto" href="/teacher">
                <img src="{{ asset('/images/popcorn_logo.svg') }}" alt="">
            </a>
            {{-- <span class="fs-6 col-auto p-0" style="color:#ffb357">For Teacher</span> --}}

            {{-- 디자인으로 인해 삭제 --}}
            {{-- <span class="col-auto ps-5">{{ session()->get('teach_name') }}</span> /
            <span class="col-auto">{{ session()->get('team_area_sido') }}</span> /
            <span class="col-auto">{{ session()->get('team_area_gu') }}</span> /
            <span class="col-auto">{{ session()->get('team_area_dong') }}</span> --}}


            {{-- 햄버거 메뉴 아이콘 --}}
            <button class="navbar-toggler col-auto d-md-none" type="button" onclick="toggleMenu()">
                <img src="{{ asset('/images/hamburger_icon.svg') }}" alt="menu" width="30">
            </button>

            {{-- 기존 메뉴 영역 --}}
            <div class="col d-none d-md-flex justify-content-end gap-5" id="main-menu">
                <div class="nav-wrap d-flex justify-content-end">
                    @if(!empty($menus))
                        @foreach ($menus->where('menu_type', 'nav')->where('is_use', 'Y')->where('menu_pt_seq', 0)->where('group_is_use','<>','N') as $menu)
                        <button class="col-auto btn cfs-6 fw-semibold primary-text-mian-hover

                        {{-- 메뉴가 폴더일때는 첫번째 자식을 가져와 대체 --}}
                        @if($menus->where('menu_pt_seq', $menu['id'])->where('is_use', 'Y')->count() > 0)
                            @php $child_first = $menus->where('menu_pt_seq', $menu['id'])->where('is_use', 'Y')->first(); @endphp
                            {{ $is_menu_gray ?
                                ($now_menu_pt_seq == $menu['id'] ? 'primary-text-mian' : 'scale-text-black'):'' }}" menu_seq="{{ $child_first['id'] }}" pt_seq="{{ $child_first['menu_pt_seq'] }}" id="menu-{{ $child_first['id'] }}" data-menu-url="{{ $child_first['menu_url'] }}" onclick="openWindow('{{ $child_first['menu_url'] }}', '{{ $child_first['menu_url_code'] }}', '{{ $child_first['is_blank'] }}')">
                            {{ $menu['menu_name'] }}
                            @else
                            {{-- 폴더아 아닌 메뉴 --}}
                            {{ $is_menu_gray ?
                                ($now_menu_seq == $menu['id'] ? 'primary-text-mian' : 'scale-text-black'):'' }}"
                            menu_seq="{{ $menu['id'] }}" pt_seq="{{ $menu['menu_pt_seq'] }}" id="menu-{{ $menu['id'] }}"
                            data-menu-url="{{ $menu['menu_url'] }}"
                            onclick="openWindow('{{ $menu['menu_url'] }}', '{{ $menu['menu_url_code'] }}', '{{ $menu['is_blank'] }}')">
                            {{ $menu['menu_name'] }}
                            @endif

                        </button>
                        @endforeach
                    @endif
                </div>
                <div class="info-btn-wrap">
                    @include('component.nav.profile')
                </div>
            </div>

            {{-- 모바일 메뉴 영역 --}}
            <div class="mobile-menu d-md-none" id="mobile-menu" hidden>
                <div class="w-100 d-flex justify-content-end mt-1 mb-4">
                    <button class="navbar-toggler col-auto d-md-none" type="button" onclick="toggleMenu()">
                        <img src="{{ asset('/images/hamburger_icon.svg') }}" alt="menu" width="30">
                    </button>
                </div>

                <div class="info-btn-wrap">
                    @include('component.nav.profile')
                </div>

                @foreach ($menus->where('menu_type', 'nav')->where('is_use', 'Y')->where('menu_pt_seq', 0)->where('group_is_use','<>','N') as $menu)
                    <a href="#" class="mobile-menu-item" onclick="openWindow('{{ $menu['menu_url'] }}', '{{ $menu['menu_url_code'] }}', '{{ $menu['is_blank'] }}')">
                        {{ $menu['menu_name'] }}
                    </a>
                @endforeach

                <a class="mobile-menu-item d-flex cursor-pointer" onclick="logout();">
                    <p class="mt-1">로그아웃하기</p>
                    <img src="{{ asset('images/gray_arrow_right.svg') }}" width="24px">
                </a>
            </div>


        </nav>
        {{-- 메뉴가 폴더형태일경우, 두번째  --}}
        <div class="main_top_p" data-div-middle-menu hidden>
            @if(!empty($menus))
            @if($now_menu_pt_seq != '')
            @foreach ($menus->where('menu_type', 'nav')->where('is_use', 'Y')->where('menu_pt_seq', $now_menu_pt_seq) as $idx => $menu)
            <button class="btn text-sb-20px {{ ($now_menu_seq == $menu['id'] ? 'scale-text-black' : 'scale-text-gray_05') }}"
                data-middle-button
                onclick="openWindow('{{ $menu['menu_url'] }}', '{{ $menu['menu_url_code'] }}', '{{ $menu['is_blank'] }}')">
                {{ $menu['menu_name'] }}
            </button>
            @endforeach
            @endif
            @endif
        </div>

        <div data-main-top-user-menu hidden class="popup-menu modal-shadow-style position-fixed bg-white rounded-3 right-0 px-0 " style="width:300px;right:5%;top:195px;z-index: 1000;">
                    <div class="p-4">
                        <ul class="menu-items" data-parent-layout-profile>
                            <li class="menu-item">
                                <div class="h-center gap-2">
                                    <div class="col-auto">
                                        <img src="{{asset('images/yellow_human_icon.svg')}}" style="filter: grayscale(1);" width="32">
                                    </div>
                                    <div class="col text-sb-18px cursor-pointer" data-layout-menu="/student/member/info" onclick="parentLayoutPageMove(this)">마이페이지</div>
                                    <!-- <div class="col-auto">
                                        <button class="btn p-0 h-center" onclick="parentLayoutProfileMenuOpen(this);">
                                            <img src="{{ asset('images/dropdown_arrow_down.svg') }}" style="filter: grayscale(1);" width="24px">
                                        </button>
                                    </div> -->
                                </div>
                                <ul class="submenu text-sb-16px scale-text-gray_05" hidden>
                                    <li class="mt-2 ps-4 ms-3 cursor-pointer" data-layout-menu="/parent/member/info" onclick="parentLayoutPageMove(this)">학부모 정보 관리</li>
                                    <li class="mt-2 ps-4 ms-3 cursor-pointer" data-layout-menu="/parent/member/info" onclick="parentLayoutPageMove(this)">자녀 정보 관리</li>
                                </ul>
                            </li>
                            <li class="menu-item mt-3" hidden>
                                <div class="h-center gap-2">
                                    <div class="col-auto">
                                        <img src="{{asset('images/ticket_icon.svg')}}" style="filter: grayscale(1);" width="32">
                                    </div>
                                    <div class="col text-sb-18px">결제(이용권) 관리</div>
                                    <div class="col-auto">
                                        <button class="btn p-0 h-center" onclick="parentLayoutProfileMenuOpen(this);">
                                            <img src="{{ asset('images/dropdown_arrow_down.svg') }}" style="filter: grayscale(1);" width="24px">
                                        </button>
                                    </div>
                                </div>
                                <ul class="submenu text-sb-16px scale-text-gray_05" hidden>
                                    <li class="mt-2 ps-4 ms-3">결제 내역 리스트</li>
                                    <li class="mt-2 ps-4 ms-3">자녀 정보 관리</li>
                                </ul>
                            </li>
                            <li class="menu-item mt-3">
                                <div class="h-center gap-2">
                                    <div class="col-auto">
                                        <img src="{{asset('images/bell_icon.svg')}}" style="filter: grayscale(1);" width="32">
                                    </div>
                                    <div class="col text-sb-18px cursor-pointer" data-layout-menu="/teacher/main/after" onclick="parentLayoutPageMove(this);">반 선택</div>
                                    <div class="col-auto" hidden>
                                        <img src="{{ asset('images/dropdown_arrow_down.svg') }}" style="filter: grayscale(1);" width="24px">
                                    </div>
                                </div>
                            </li>
                            <li class="menu-item mt-3" hidden>
                                <div class="h-center gap-2">
                                    <div class="col-auto">
                                        <img src="{{asset('images/sms_icon.svg')}}" style="filter: grayscale(1);" width="32">
                                    </div>
                                    <div class="col text-sb-18px cursor-pointer" data-layout-menu="/teacher/main/after/class/start" onclick="parentLayoutPageMove(this)">출석체크</div>
                                    <div class="col-auto" hidden>
                                        <img src="{{ asset('images/dropdown_arrow_down.svg') }}" style="filter: grayscale(1);" width="24px">
                                    </div>
                                </div>
                            </li>
                            <li class="menu-item mt-3">
                                <div class="h-center gap-2">
                                    <div class="col-auto">
                                        <img src="{{asset('images/one_and_one_icon.svg')}}" style="filter: grayscale(1);" width="32">
                                    </div>
                                    <div class="col text-sb-18px cursor-pointer" data-layout-menu="/teacher/messenger" onclick="parentLayoutPageMove(this)">쪽지함</div>
                                    <div class="col-auto" hidden>
                                        <img src="{{ asset('images/dropdown_arrow_down.svg') }}" style="filter: grayscale(1);" width="24px">
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="scale-bg-gray_04 w-100" style="height:1px;"></div>
                    <div class="footer h-center p-4 gap-1 cursor-pointer" onclick="logout();">
                        <div class="scale-text-gray_05 text-sb-18px">
                            로그아웃하기
                        </div>
                        <div>
                            <img src="{{ asset('images/gray_arrow_right.svg') }}" style="filter: grayscale(1);" width="24px">
                        </div>
                    </div>
                </div>
</div>
<input type="hidden" id="layout_menu_pt_seq" value="{{ $current_menu_pt_seq }}">
<input type="hidden" id="layout_menu_seq" value="{{ $current_menu_seq }}">
<main id="layout_div_content" class="col">
    <div class="p-2 border-bottom px-3" style="margin-left: -12px;" hidden>
        <h6 id="h6_title" class="m-0" data="#TITLE"></h6>
    </div>
    @yield('layout_coutent')

</main>
</div>
<script>
    function toggleMenu() {
        let menu = document.getElementById('mobile-menu');
        menu.hidden = !menu.hidden;
    }

    function openWindow(page_url, page_code, is_blank) {
        if (is_blank == null) is_blank = 'N';
        if (is_blank == 'N') {
            window.location.href = page_url;
        } else {
            const one = window.open(page_url, page_code);
            if (one && !one.closed) {
                // one 창이 이미 열려 있음
                one.focus();
            }
            return;

        }
    }

    function layoutLeftNavbarToggle(is_open) {
        const layout_div_navbar_left = document.querySelector("#layout_div_navbar_left");
        // const navbar_admin_left = document.querySelector("#navbar_admin_left");
        const layout_div_content = document.querySelector("#layout_div_content");
        const layout_div_toggle_bottom = document.querySelector("#layout_div_toggle_bottom");
        const layout_div_navbar_left_top = document.querySelector("#layout_div_navbar_left_top");
        const layout_sp_logout = document.querySelector("#layout_sp_logout");

        //navbar_admin_left 가 hidden이면 보여주고 아니면 숨긴다.
        let isOpen = null;
        if (is_open == null) {
            // isOpen = navbar_admin_left.hidden;
        } else {
            isOpen = !is_open;
        }
        if (isOpen) {
            navbar_admin_left.hidden = false;
            //width를 없앤다.
            layout_div_navbar_left.style.width = "";
            layout_div_content.style.width = "";
            layout_div_toggle_bottom.hidden = true;
            layout_sp_logout.hidden = false;
            layout_div_navbar_left_top.hidden = false;
        } else {
            // navbar_admin_left.hidden = true;
            //width:4% 로 변경.
            layout_div_navbar_left.style.width = "4%";
            layout_div_content.style.width = "95%";
            layout_div_toggle_bottom.hidden = false;
            layout_sp_logout.hidden = true;
            layout_div_navbar_left_top.hidden = true;
        }
    }

    //
    function openFolder() {
        //첫 버튼 메뉴이름과, 하위 메뉴 이름이 다르면 보이게.
        //TODO:만약 추후 똑같은데 폴더가 아닐때, 첫이름과 끝이름같이 비교해준다.
        const first_top_menu = document.querySelectorAll('[data-menu-url]')[0].textContent.trim();
        const first_middle_menu = document.querySelectorAll('[data-middle-button]')?.[0]?.textContent?.trim() ?? '';
        if(first_top_menu != first_middle_menu){
            document.querySelector('[data-div-middle-menu]').hidden = false;
        };
        try {
            const layout_menu_pt_seq = docment.querySelector("#layout_menu_pt_seq").value;
            if (layout_menu_pt_seq == '') return;
            const btn_div = document.querySelector("#menu-" + layout_menu_pt_seq);
            const btn_toggle = btn_div.querySelector('button');
            if (btn_toggle == null) return;
            btn_toggle.setAttribute('aria-expanded', 'true');
            if (btn_div.previousElementSibling != null) {
                const btn_div_pt = btn_div.previousElementSibling.closest('.menu_fd');
                btn_div_pt.querySelector('button').setAttribute('aria-expanded', 'true');
            }
        } catch (e) {}
    }

    //
    function mainCategory(vthis) {
        const main_category_type = document.querySelectorAll('.main_category_type');
        main_category_type.forEach(function(el) {
            el.classList.remove('active');
        });
        vthis.classList.add('active');
        let url = location.href;
        let after_url = '?main_category_type=' + vthis.getAttribute('data');
        if (location.href.indexOf('?') > -1) {
            // ?부터 뒤를 자른다.
            url = location.href.substring(0, location.href.indexOf('?'));
        }
        //cookie 에 저장
        setCookie('main_code', vthis.getAttribute('data'), 9999);
        window.location.href = url + after_url;
    }

    openFolder();
    document.querySelectorAll('.tag_is_delete').forEach(function(el) {
        el.remove()
    });

    const logoutLink = document.querySelector('a[href="/manage/logout"]');
    if (logoutLink) {
        logoutLink.href = 'javascript:logout();';
    }

    function logout() {

        const msg = "<span class='text-sb-28px pb-4 mb-1 d-block'>로그아웃 하시겠습니까?</span>";
        sAlert('', msg, 3, function() {
            location.href = '/teacher/logout';
            document.querySelector('.modal-backdrop').remove();
        }, function() {
            document.querySelector('.modal-backdrop').remove();
        }, '네', '아니오');
        const myModal = new bootstrap.Modal(document.querySelector('#system_alert .modal'), {});
        myModal.show();
    }
    // layoutLeftNavbarToggle();
</script>


@include('layout.student_foot')
