@include('layout.admin_head')
@php
// 현재 세션의 id를 가져와서 group_seq를 가져온다.
$group_seq = session()->get('group_seq');
$menus = \App\Menu::select('menus.*', 'menu_groups.group_seq', 'menu_groups.menu_type', 'menu_groups.menu_idx', 'menu_groups.menu_pt_seq', 'menu_groups.is_use as group_is_use')
->leftJoin('menu_groups', 'menus.id', '=', 'menu_groups.menu_seq')
->where('menu_groups.group_seq', $group_seq);
$menus = $menus
->orderBy('menu_type', 'desc')
->orderBy('menu_idx', 'asc')
->orderBy('menus.id', 'asc')
->get();
$menus = $menus->toArray();

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

//main_code 쿠키 저장.
$main_category_type = $_GET['main_category_type'] ?? $_COOKIE['main_category_type'] ?? 'elementary';
setcookie('main_category_type', $main_category_type, time() + 60 * 60 * 24 * 9999, '/');
setcookie('main_code', $main_category_type, time() + 60 * 60 * 24 * 9999, '/');
@endphp
{{-- 부트스탭을 이용해서 왼쪽에 사이드 메뉴 --}}
{{-- 1 줄은 Popcon-Edu --}}
{{-- 2 줄은 -님 환영합니다. --}}
{{-- 3 줄은 사용자 관리 --}}
{{-- 4 줄은 게시판관리, 다음줄은 컨텐츠관리 , 다음줄은 사이트관리, 다음줄은 시스템관리, 다음줄은 문자/알림관리 다음줄은 메출관리, 다음줄은 통계분석 --}}
<style>
    #layout_div_navbar_left {
        min-height: calc(100vh - 56px);
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
    #navbar_admin_top .navbar-nav .nav-item {
        border-right: 1px solid #dee2e6;
    }

    #navbar_admin_top .navbar-nav .nav-item:last-child {
        border-right: none;
    }

    .menu_fd .menu_list button {
        font-weight: 500 !important;
        font-size: 0.9rem !important;
        padding-left: 23px !important;
    }
</style>

<div class="content row h-100">
    {{-- 상단 네브바 --}}
    <nav id="navbar_admin_top" class="navbar navbar-expand-sm bg-light navbar-light">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
                <ul class="navbar-nav">
                    {{-- @foreach $menus / 메뉴 가져오기. --}}
                    @foreach ($menus as $idx => $menu)
                    {{-- menu_type 이 top가 아니면 넘김처리 --}}
                    @if ($menu['menu_type'] != 'top')
                    @continue
                    @endif
                    <li class="py-0 nav-item border-dark">
                        <a class="py-0 nav-link" href="{{ $menu['menu_url'] }}">
                            {{ $menu['menu_name'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </nav>
    <div id="layout_div_navbar_left" class="col-2 border-end">
        <div id="layout_div_navbar_left_top">
            {{-- 메뉴 쪽 로고 --}}
            <a class="navbar-brand ps-4 pt-2 fw-bold text-primary fs-5" href="/manage">Popcorn-Edu</a>
            {{-- 메뉴 심플하게 변경 --}}
            <button id="layout_btn_toggle_top" class="btn btn-outline-secondary rounded-circle pb-2 float-end mt-2" onclick="layoutLeftNavbarToggle()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-left-fill" viewBox="0 0 16 16">
                    <path d="m3.86 8.753 5.482 4.796c.646.566 1.658.106 1.658-.753V3.204a1 1 0 0 0-1.659-.753l-5.48 4.796a1 1 0 0 0 0 1.506z" />
                </svg>
            </button>

            {{-- 관리자 이름 표기 --}}
            <a class="d-flex align-items-center pb-3 mb-3 link-body-emphasis text-decoration-none border-bottom">
                <svg class="bi pe-none me-2" width="30" height="24">
                    <use xlink:href="#bootstrap"></use>
                </svg>
                <span class="fs-6 fw-semibold">
                    {{ session()->get('teach_name') }}님 환영합니다.
                </span>
            </a>
        </div>
        {{-- 최상위 카테고리 추가 --}}
        {{-- 세션 팀코드 가져오기 --}}
        @if(session()->get('team_code') == 'maincd')
        <div class="d-flex text-center mb-2">
            <div class="main_category_type col p-2 btn btn-outline-primary rounded-0 {{ $main_category_type == 'elementary' || $main_category_type == '' ? 'active' : '' }}" data="elementary" onclick="mainCategory(this)">
                초등
            </div>
            <div class="main_category_type col p-2 btn btn-outline-primary rounded-0 {{ $main_category_type == 'middle' ? 'active' : '' }}" data="middle" onclick="mainCategory(this)">
                중등
            </div>
        </div>
        @endif
        {{-- 왼쪽 네브바 --}}
        <div id="navbar_admin_left" class="overflow-auto" style="height:calc(100vh - 230px)">
            <ul class="list-unstyled ps-0">
                {{-- @foreach $menus / 메뉴 가져오기. --}}
                @foreach ($menus as $idx => $menu)
                {{-- menu_type 이 nav가 아니면 넘김처리 --}}
                @if ($menu['menu_type'] != 'nav' || $menu['is_use'] != 'Y')
                @continue
                @endif
                {{-- 폴더(카테고리) --}}
                @if ($menu['is_folder'] == 'Y')
                <li class="menu_list menu_fd" menu_seq="{{ $menu['id'] }}" pt_seq="{{ $menu['menu_pt_seq'] }}" id="menu-{{ $menu['id'] }}">
                    <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed {{ $menu['group_is_use'] == 'Y' ? '' : 'tag_is_delete' }}" data-bs-toggle="collapse" data-bs-target=".menu-{{ $menu['id'] }}" aria-expanded="false">
                        {{ $menu['menu_name'] }}
                    </button>
                    <div class="collapse menu-{{ $menu['id'] }}" style="">
                        <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-2"></ul>
                    </div>
                </li>
                @else
                {{-- 하위 메뉴 --}}
                @if ($menu['menu_pt_seq'] * 1 > 0)
                @if ($current_url == $menu['menu_url'])
                @php
                $current_menu_pt_seq = $menu['menu_pt_seq'];
                $current_menu_seq = $menu['id'];
                @endphp
                @endif

                <li class="menu_list" menu_seq="{{ $menu['id'] }}" pt_seq="{{ $menu['menu_pt_seq'] }}" id="menu-{{ $menu['id'] }}">
                    <a href="javascript:openWindow('{{ $menu['menu_url'] }}', '{{ $menu['menu_url_code'] }}', '{{ $menu['is_blank'] }}')" class="link-body-emphasis d-inline-flex text-decoration-none rounded @yield($menu['menu_url_code']) {{ $menu['group_is_use'] == 'Y' ? '' : 'tag_is_delete' }}">
                        {{ $menu['menu_name'] }}
                    </a>
                    {{-- $menu['menu_url'] 에 boardqna가 있으면 --}}
                    @if(strpos($menu['menu_url'], 'qna') !== false)
                    @if(strpos($menu['menu_url'], 'sdqna') !== false)
                    <span class="badge bg-danger rounded-pill">{{ $board_qna_cnt['sdqna'] ?? '' }}</span>
                    @else
                    <span class="badge bg-danger rounded-pill">{{ $board_qna_cnt['qna'] ?? '' }}</span>
                    @endif
                    @endif
                </li>
                @else
                {{-- 상위에 메뉴가 없는 메뉴 --}}
                <div class="mb-2">
                    <button class="btn d-inline-flex align-items-center rounded border-0 collapsed {{ $menu['group_is_use'] == 'Y' ? '' : 'tag_is_delete' }}" onclick="openWindow('{{ $menu['menu_url'] }}', '{{ $menu['menu_url_code'] }}', '{{ $menu['is_blank'] }}')" data-bs-toggle="collapse" data-bs-target=".menu-{{ $menu['id'] }}" aria-expanded="false">
                        {{ $menu['menu_name'] }}
                    </button>
                </div>
                @endif
                @endif
                @if ($current_url == $menu['menu_url'])
                @endif
                @endforeach
            </ul>
            <script>
                // 각각 하위 메뉴를 상위메뉴 안으로 정렬.
                document.querySelectorAll('.menu_list').forEach(function(el) {
                    const vthis = el;
                    const pt_seq = el.getAttribute('pt_seq');
                    if ((pt_seq || '') != '') {
                        document.querySelectorAll('#menu-' + pt_seq + ' ul').forEach(function(elin) {
                            elin.appendChild(vthis);
                        });
                    }
                });

                //메뉴 활성화시 오픈과 열림 아이콘 변환.
                const layout_menu_pt_seq = '{{ $current_menu_pt_seq }}';
                const layout_menu_seq = '{{ $current_menu_seq }}';
                const btn_toggle_nav = document.querySelector("#menu-" + layout_menu_seq);
                if (btn_toggle_nav != null) {
                    btn_toggle_nav.classList.add('bg-secondary');
                    btn_toggle_nav.querySelector('a').classList.add('text-white');
                }
                document.querySelector('#menu-' + layout_menu_pt_seq).classList.add('show');
                const pt_seq = document.querySelector('#menu-' + layout_menu_pt_seq).getAttribute('pt_seq');
                if (pt_seq != null) {
                    document.querySelectorAll('.menu-' + pt_seq).forEach(function(el) {
                        el.classList.add('show');
                    });
                }
                document.querySelectorAll('.menu-' + layout_menu_pt_seq).forEach(function(el) {
                    el.classList.add('show');
                });
            </script>
        </div>

        <button type="button" class="btn btn-sm p-3 position-absolute bottom-0" onclick="location.href='/manage/logout'">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z" />
                <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
            </svg>
            <span id="layout_sp_logout">로그아웃</span>
        </button>
        <div id="layout_div_toggle_bottom" hidden>
            <button id="layout_btn_toggle" class="btn btn-outline-secondary rounded-circle pb-2 ms-3" onclick="layoutLeftNavbarToggle()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16">
                    <path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z" />
                </svg>
            </button>

            <img src="{{ asset('images/admin_logo.png?1') }}" class="ps-2 ms-1 mt-4">
        </div>
    </div>
    <input type="hidden" id="layout_menu_pt_seq" value="{{ $current_menu_pt_seq }}">
    <input type="hidden" id="layout_menu_seq" value="{{ $current_menu_seq }}">
    <div id="layout_div_content" class="col-10">
        <nav aria-label="breadcrumb">
            <ol id="layout_ol_menusel" class="breadcrumb p-3 rounded-3">
                <li class="breadcrumb-item">
                    <a class="link-body-emphasis" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door-fill" viewBox="0 0 16 16">
                            <path d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5Z" />
                        </svg>
                        <span class="visually-hidden"></span>
                    </a>
                </li>
                <li class="copy_li_menu breadcrumb-item" hidden>
                    <a class="link-body-emphasis fw-semibold text-decoration-none" href="#">#1</a>
                </li>
            </ol>
        </nav>
        @yield('layout_coutent')
        <script>
            const div_menu = document.querySelector('#navbar_admin_left');
            const menu_btns = div_menu.querySelector('div.show').closest('li.menu_list').querySelectorAll('button');
            let menus = [];
            let act_str = document.querySelector('.menu_list.bg-secondary').innerText;
            let act_pt_str = document.querySelector('.menu_list.bg-secondary').parentElement.closest('.menu_list').querySelector('button').innerText;
            menu_btns.forEach(function(btn) {
                menus.push(btn.innerText);
            });
            if (menus.length == 2 && menus[1] != act_pt_str) {
                // menu[1] 삭제
                menus.splice(1, 1);
            }
            menus.push(act_str);
            menus.forEach(function(menu, idx) {
                const li = document.querySelector('#layout_ol_menusel .copy_li_menu').cloneNode(true);
                li.hidden = false;
                if (menus.length == idx + 1) {
                    li.querySelector('a').innerText = menu;
                    li.querySelector('a').classList.add('text-primary');

                } else {
                    li.innerText = menu;
                }
                document.querySelector('#layout_ol_menusel').appendChild(li);
            });
        </script>
    </div>
</div>
<script>
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
        const navbar_admin_left = document.querySelector("#navbar_admin_left"); 
        const layout_div_content = document.querySelector("#layout_div_content");
        const layout_div_toggle_bottom = document.querySelector("#layout_div_toggle_bottom");
        const layout_div_navbar_left_top = document.querySelector("#layout_div_navbar_left_top");
        const layout_sp_logout = document.querySelector("#layout_sp_logout");

        // 트랜지션 추가
        layout_div_navbar_left.style.transition = "width 0.3s ease";
        layout_div_content.style.transition = "width 0.3s ease";
        navbar_admin_left.style.transition = "opacity 0.3s ease";

        let isOpen = null;
        if (is_open == null) {
            isOpen = navbar_admin_left.hidden;
        } else {
            isOpen = !is_open;
        }

        if (isOpen) {
            // 열기
            navbar_admin_left.style.opacity = "0";
            navbar_admin_left.hidden = false;
            
            setTimeout(() => {
                layout_div_navbar_left.style.width = "";
                layout_div_content.style.width = "";
                navbar_admin_left.style.opacity = "1";
                layout_div_toggle_bottom.hidden = true;
                layout_sp_logout.hidden = false;
                layout_div_navbar_left_top.hidden = false;
            }, 50);

        } else {
            // 닫기 
            navbar_admin_left.style.opacity = "0";
            
            setTimeout(() => {
                layout_div_navbar_left.style.width = "4%";
                layout_div_content.style.width = "95%";
                layout_div_toggle_bottom.hidden = false;
                layout_sp_logout.hidden = true;
                layout_div_navbar_left_top.hidden = true;
                
                setTimeout(() => {
                    navbar_admin_left.hidden = true;
                }, 300);
            }, 50);
        }
    }

    //
    function openFolder() {
        const layout_menu_pt_seq = document.querySelector("#layout_menu_pt_seq").value;
        if (layout_menu_pt_seq == '') return;
        const btn_div = document.querySelector("#menu-" + layout_menu_pt_seq);
        const btn_toggle = btn_div.querySelector('button');
        if (btn_toggle == null) return;
        btn_toggle.setAttribute('aria-expanded', 'true');
        if (btn_div.previousElementSibling != null) {
            const btn_div_pt = btn_div.previousElementSibling.closest('.menu_fd');
            btn_div_pt.querySelector('button').setAttribute('aria-expanded', 'true');
        }
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

    document.querySelector('a[href="/manage/logout"]').href = 'javascript:logout();';

    function logout() {
        sAlert('로그아웃', '로그아웃 하시겠습니까?', 2, function() {
            location.href = '/manage/logout';
        });
    }
</script>


@include('layout.admin_foot')