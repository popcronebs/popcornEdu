@php
//그룹 리스트 User_groups
$user_groups = \App\UserGroup::select('*')->get();
$user_groups = $user_groups->toArray();

//메뉴 url 리스트 가져오기.
$menu_urls = \App\MenuUrl::select('*')->get();

$p_display = '';
$col = '';
if(isset($is_part)){
    $p_display = 'hidden';
    $col = '1';
}
@endphp

<link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="//code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
    .drag{
        padding:10px;
    }
    .drag.drop-container-in{
        padding:10px;
        color:blue;
    }
    .drag.drop-container-in2{
        padding:10px;
        color:green !important;
    }
    .drag.drop-container-in div,
    .drag.drop-container-in2 div{
        text-align: right;
        padding-right: 50px;
        min-width:100px;
        color:black;
    }
    .drag.drop-container-in .drag.drop-container-in2{
        text-align: left;
        padding-left: 50px
    }
    .drag.drop-container-in .drag.drop-container-in2 div {
        padding-right: 0px;
        color: #6c757d;
    }
    .itemBoxHighlight{
        border: 1px solid #0000001f;
        background: #0000001f;
        border-radius: 5px;
        height:44px;
    }
</style>
<div class="col-12 pe-3 ps-3 position-relative">
    <div class="row">
        <div class="col-lg-{{ $col }}2 h-100 p-2 border-end">
            {{-- select 관리자, 운영자, 학부모, 학생 --}}
            <select id="menu_sel_group_seq" class="form-select w-100 " aria-label="Default select example" onchange="menuGetList();" disabled {{ $p_display }}>
                <option value="">그룹 선택</option>
                @foreach ($user_groups as $user_group)
                <option value="{{ $user_group['id'] }}">{{ $user_group['group_name'] }}</option>
                @endforeach 
            </select>
            <ul id="v-pills-tab" class="nav nav-tabs mt-2 text-center">
                <li class="nav-item cursor-pointer col">
                    <a id="v-pills-home-tab" class="nav-link active" onclick="menuTabAll(this);" {{ $p_display }} type="all">
                        <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden></span>
                        전체 메뉴
                    </a>
                </li>
                <li class="nav-item cursor-pointer col">
                    <a id="v-pills-current-tab" class="nav-link" onclick="menuTabCurrent(this);" type="current">
                        <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden></span>
                        현재 메뉴</a>
                </li>
            </ul>
            {{-- 메뉴추가, 현재 메뉴 content --}}
            <div class="tab-content" id="v-pills-tabContent">
                {{-- 메뉴추가 --}}
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                    aria-labelledby="v-pills-home-tab" tabindex="0">
                </div>


                {{-- 현재 메뉴 --}}
                <div class="tab-pane fade" id="v-pills-current" role="tabpanel" aria-labelledby="v-pills-current-tab"
                    tabindex="0">
                </div>
                {{-- 메뉴 추가 버튼 --}}
                <div class="gap-2 row p-2">
                    {{-- 메뉴추가 --}}
                    <button id="menu_btn_add_toggle" class="col btn btn-sm btn-outline-primary" onclick="menuAddShowToggle(this);" {{ $p_display }}>메뉴 추가</button>
                    <button id="menu_btn_edit_toggle" class="col btn btn-sm btn-light border" onclick="menuDragToggle(this);">편집</button>
                </div>

                {{-- 메뉴명을 입력하세요. --}}
                <div id="menu_div_menu_name" class="input-group mb-3" hidden>
                    <span class="input-group-text p-1"></span>
                    <input id="menu_inp_menu_name" type="text" class="form-control"
                        placeholder="메뉴명을 입력하세요.">
                    <button class="btn btn-sm btn-outline-secondary" onclick="menuSaveName();">등록</button>
                </div>

                {{-- 드래그1, 드래그2 서로의 위치를 드래그앤 드롭으로 바꾸는 소스코드 --}}
                <div id="drop_menu" class="overflow-auto" style="height:calc(100vh - 230px);">
                    
                    <div id="menu_div_drop_top" class="drop-container-top">
                        <span class="text-secondary not-draggable">TOP</span>
                        {{-- <div id="d0" class="drag">로그인/로그아웃</div>
                        <div id="d1" class="drag">시스템 사용 문의</div>
                        <div id="d2" class="drag">공지사항</div> --}}
                    </div>

                    <span class="text-secondary not-draggable">NAV</span>
                    <div id="menu_div_drop_top" class="drop-container-nav">
                        {{-- <div id="d3" class="drag drop-container-in" 
                        onmousedown="menuSortOnOffNav(false)"
                        onmouseup="menuSortOnOffNav(true)">
                            <span>학습관리</span>
                            <div id="d4" class="drag">학습현황</div>
                            <div id="d5" class="drag">수강현황</div>
                        </div> --}}
                    </div>
                    <div id="menu_div_copy_tag" hidden>
                        <div class="drag no_pt cursor-pointer" onclick="menuClick(this);">#메뉴명</div>
                        <div class="drag drop-container-in cursor-pointer" 
                        onmousedown="menuSortOnOffNav(false, 'in')"
                        onmouseup="menuSortOnOffNav(true, 'in')" onclick="menuClick(this);"></div>
                        <div class="drag drop-container-in2 cursor-pointer"
                        onmousedown="menuSortOnOffNav(false, 'in2', this)"
                        onmouseup="menuSortOnOffNav(true, 'in2', this)" onclick="menuClick(this);"></div>
                        <span class="form-switch copy_sp_is_use">
                            <input class="is_use form-check-input" type="checkbox" role="switch" onclick="menuGroupIsUse(this)">
                        </span>
                    </div>
                </div>
            </div>

        </div>
        <div class="col" {{ $p_display }}>
            {{-- TAb 메뉴설정 , 레이아웃, SEO --}}
            {{-- /**
             * This code displays a container div with Bootstrap classes.
             * The available container classes in Bootstrap are:
             * - container
             * - container-sm
             * - container-md
             * - container-lg
             * - container-xl
             * - container-xxl
             *
             * Example usage:
             * --}}
            <div class="nav nav-pills mt-2 mb-2 justify-content-center" 
            id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="col-2 nav-link active" id="v-menu-setting-tab" data-bs-toggle="pill"
                    data-bs-target="#v-menu-setting" type="button" role="tab" aria-controls="v-menu-setting"
                    aria-selected="true">메뉴 설정</button>
                <button class="col-2 nav-link" id="v-layout-tab" data-bs-toggle="pill"
                    data-bs-target="#v-layout" type="button" role="tab" aria-controls="v-layout"
                    aria-selected="false">레이아웃</button>
                <button class="col-2 nav-link" id="v-seo-tab" data-bs-toggle="pill"
                    data-bs-target="#v-seo" type="button" role="tab" aria-controls="v-seo"
                    aria-selected="false">SEO</button>
            </div>
            {{-- 메뉴 설정, 레이아웃, SEO content --}}
            <div class="tab-content row justify-content-center" id="v-pills-tabContent">
                {{-- 메뉴 설정 --}}
                <div class="tab-pane fade show active  col-11" id="v-menu-setting" role="tabpanel"
                    aria-labelledby="v-pills-home-tab" tabindex="0" hidden>
                    <input class="menu_seq" type="hidden">
                    <h5 class="border-bottom mt-3 pb-2">메뉴명</h5>
                    <input class="menu_name border rounded w-100 hpx-40 ps-2" type="text" placeholder="메뉴명을 입력하세요.">

                    <h5 class="border-bottom mt-5 pb-2">연결</h5>
                    <div class="link dropdown">
                        <input class="murl_name dropdown-toggle ps-2 border rounded hpx-40 w-100" data-bs-toggle="dropdown" aria-expanded="false" onkeyup="if(event.keyCode == 13)menuUrlSearch();">
                        <input class="menu_url_code" type="hidden" >
                        <input class="menu_url" type="hidden" >
                        <ul id="menu_url_list" class="dropdown-menu w-100" style="max-height:500px;overflow: auto;">
                            <li class="code_"><a class="dropdown-item" murl_code="" onclick="menuUrlClick(this);">링크없음</a></li>
                            <li class="code_out_link"><a class="dropdown-item" murl_code="out_link" onclick="menuUrlClick(this);">외부주소</a></li>
                            <li class="code_folder"><a class="dropdown-item" murl_code="folder" onclick="menuUrlClick(this);">폴더(카테고리)</a></li>
                            <li class="code_folder2"><a class="dropdown-item" murl_code="folder2" onclick="menuUrlClick(this);">폴더2(카테고리)</a></li>
                            <li class="code_logout"><a class="dropdown-item" murl_code="logout" onclick="menuUrlClick(this);">로그아웃</a></li>
                            <li class="code_"><a class="dropdown-item" murl_code="" onclick="menuUrlClick(this);"> ---- </a></li>
                            {{-- menu_urls --}}
                            @foreach ($menu_urls as $menu_url)
                                <li class="code_{{ str_replace('/', '_', $menu_url['murl_code']) }}">
                                    <a class="dropdown-item" murl_seq="{{ $menu_url['id'] }}" murl_code="{{ $menu_url['murl_code'] }}" url="{{ $menu_url['url'] }}"
                                    onclick="menuUrlClick(this);">
                                        {{ $menu_url['murl_name'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                      </div>

                    <h5 class="border-bottom mt-5 pb-2">페이지</h5>
                    <select class="is_blank hpx-40 w-100 ps-2 border rounded">
                        <option value="N">현재 창에서 열기</option>
                        <option value="Y">새 창으로 열기</option>
                    </select>

                    <h5 class="border-bottom mt-5 pb-2">권한</h5>
                    <select class="sel_group_seq hpx-40 w-100 ps-2 border rounded" onchange="menuSettingGroupSel(this);">
                        <option value="">그룹명</option>
                        {{-- 그룹명 $user_groups --}}
                        @foreach ($user_groups as $user_group)
                            <option value="{{ $user_group['id'] }}">{{ $user_group['group_name'] }}</option>
                        @endforeach 
                        
                    </select>
                    <div id="menu_div_group_sel" class="d-flex gap-2 py-2">
                        {{-- 그룹 선택 태그 $user_groups --}}
                        @foreach ($user_groups as $user_group)
                        <span class="badge p-2 align-items-center text-primary-emphasis bg-primary-subtle rounded-pill seq{{ $user_group['id'] }}" hidden>
                            <input type="hidden" class="group_seq" value="{{ $user_group['id'] }}">
                            <span class="px-1">{{ $user_group['group_name'] }} </span>
                            <a href="javascript:menuSettingGroupDel('{{ $user_group['id'] }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                                  </svg>
                            </a>
                          </span>
                        @endforeach 
                    </div>

                    {{-- 메뉴 노출 --}}
                    <h5 class="cls_is_use border-bottom mt-5 pb-2">메뉴 노출</h5>
                    <div class="cls_is_use form-check form-switch">
                        <input class="is_use form-check-input" type="checkbox" role="switch" id="menu_chk_show" checked>
                        <label class="form-check-label" for="menu_chk_show">노출</label>
                    </div>

                    <hr class="mt-5 border border-secondary">
                    {{-- 저장 / 삭제 버튼 --}}
                    <div class="d-flex justify-content-end">
                        <button id="menu_btn_delete" class="btn btn-outline-danger col-2 me-2" onclick="menuAskDelete();">삭제</button>
                        <button class="btn btn-outline-primary col-2" onclick="menuSave();" ">저장</button>
                    </div>


                    
                </div>
                {{-- 레이아웃 --}}
                <div class="tab-pane fade col-11" id="v-layout" role="tabpanel"
                    aria-labelledby="v-pills-current-tab" tabindex="0">
                    layout
                </div>
                {{-- SEO --}}
                <div class="tab-pane fade col-11" id="v-seo" role="tabpanel"
                    aria-labelledby="v-seo-tab" tabindex="0">
                    seo
                </div>
        </div>
    </div>
</div>
</div>

<script>
    window.onload = function() {
        menuGetList();
        menuDropDrag(); // 새로은 메뉴가 나올때마다 실행해야하기 때문에 getlist에서 실행.
        // menuSortOnOff(false); // 새로은 메뉴가 나올때마다 실행해야하기 때문에 getlist에서 실행.
        
    };
    // 메뉴 편집 버튼 클릭시 드래그 기능 on/off
    function menuDragToggle(vthis, is_bool){
        if(is_bool == null) is_bool = vthis.classList.contains('active');
        if(is_bool){
            //menu_btn_edit_toggle
            document.getElementById('menu_btn_edit_toggle').classList.remove('active');
            menuSortOnOff(false);
            menuIdxSave();
            menuGroupIsUseDisable(true);
        }else{
            vthis.classList.add('active');
            menuSortOnOff(true);

            //메뉴 활성화 제거.
            document.querySelectorAll('.drag').forEach(function(item){
                item.classList.remove('active');
                item.classList.remove('border');
                item.classList.remove('border-secondary');
                item.classList.remove('bg-light');
            });
            menuGroupIsUseDisable(false);
        }
    }

    // 메뉴 드래그 기능 초기화
    function menuDropDrag(is_container_in) {
        if(is_container_in == null) is_container_in = false;
        if(!is_container_in){
            $('.drop-container-top').sortable({
                connectWith: '.drop-container-nav',
                cancel: '.not-draggable',
                items: "> .drag", // 바로 밑의 .drag 클래스를 가진 자식만 이동 가능
                placeholder:"itemBoxHighlight",
            });
            $('.drop-container-nav').sortable({
                connectWith: '.drop-container-top, .drop-container-in',
                cancel: '.not-draggable',
                items: "> .drag",
                placeholder:"itemBoxHighlight",
            });
        }else{
            $(".drop-container-in").sortable({
                connectWith: ".drop-container-nav, .drop-container-in2", // 서로 연결
                items: "> .drag",
                placeholder:"itemBoxHighlight",
            }).disableSelection();

            $(".drop-container-in2").sortable({
                connectWith: ".drop-container-nav", // 서로 연결
                items: "> .drag",
                placeholder:"itemBoxHighlight",
            }).disableSelection();
        }
    }

    //편집 버튼 클릭시 드래그 기능 on/off
    function menuSortOnOff(is_on) {
        if (is_on){
            try{$('.drop-container-top').sortable('enable');}catch(e){e.message;}
            try{$('.drop-container-nav').sortable('enable');}catch(e){e.message;}
            if($('.drop-container-in').length > 0)
                try{$('.drop-container-in').sortable('enable');}catch(e){e.message;}
            if($('.drop-container-in2').length > 0)
                try{$('.drop-container-in2').sortable('enable');}catch(e){e.message;}
        } 
        else{
            try{$('.drop-container-top').sortable('disable');}catch(e){e.message;}
            try{$('.drop-container-nav').sortable('disable');}catch(e){e.message;}
            if($('.drop-container-in').length > 0)
                try{$('.drop-container-in').sortable('disable');}catch(e){e.message;}
            if($('.drop-container-in2').length > 0)
                try{$('.drop-container-in2').sortable('disable');}catch(e){e.message;}
        }
    }

    //Nav드래그 가능 박스 안에 이동가능한 드래그 박스가 있을때 오류 방지위해 사용
    function menuSortOnOffNav(is_bool, type, vthis){
        if(type == 'in'){
            if(is_bool) $('.drop-container-nav').sortable('option', 'connectWith', '.drop-container-top, .drop-container-in');
            else $('.drop-container-nav').sortable('option', 'connectWith', '');
        }else if(type == 'in2'){
            if(is_bool) {
                $('.drop-container-nav').sortable('option', 'connectWith', '.drop-container-top, .drop-container-in');
                $('.drop-container-in').sortable('option', 'connectWith', '.drop-container-nav, .drop-container-in2');
            }
            else {
                //vthis 안에 div.drag 가 있으면 .drop-container-in 없으면 ''
                if(vthis.querySelectorAll('.drag').length > 0){
                    $('.drop-container-nav').sortable('option', 'connectWith', '');
                    $('.drop-container-in').sortable('option', 'connectWith', '');
                }
                else{
                    $('.drop-container-nav').sortable('option', 'connectWith', '.drop-container-in');
                    $('.drop-container-in').sortable('option', 'connectWith', '.drop-container-nav');
                }
                    
            }
        }
    }

    // 메뉴 추가 버튼 클릭시 메뉴명 입력창 토글
    function menuAddShowToggle(vthis){
        const div_menu_name = document.getElementById('menu_div_menu_name');
        const inp_menu_name = document.getElementById('menu_inp_menu_name');
        if(vthis.classList.contains('active')){
            vthis.classList.remove('active');
            div_menu_name.hidden = true;
            inp_menu_name.value = '';
        }else{
            vthis.classList.add('active');
            div_menu_name.hidden = false;
        }
    }

    //메뉴이름 등록
    function menuSaveName(){
        const add_menu_name = document.getElementById('menu_inp_menu_name');
        let group_seq = document.getElementById('menu_sel_group_seq').value;
        const menu_name = add_menu_name.value;

        if(menu_name == ''){
            sAlert('','메뉴명을 입력하세요.');
            return;
        }
        if(group_seq == ''){
            group_seq = -1; //전체 메뉴
        }

        const page = "/manage/menu/insert";
        const parameter = {
            menu_name: menu_name,
            group_seq: group_seq,
        };
        queryFetch(page, parameter, function(result){
            if(result.result == 'success'){
                sAlert('','메뉴가 추가되었습니다.');

                // 메뉴추가 토글버튼 클릭.
                document.getElementById('menu_btn_add_toggle').click();

                // 메뉴 리스트 가져오기.
                menuGetList();
            }else{
                sAlert('','메뉴 추가에 실패하였습니다.');
            }
        });
    }

    //메뉴 저장
    function menuSave(){
        const menu_setting = document.getElementById('v-menu-setting');
        const menu_seq = menu_setting.querySelector('.menu_seq').value;
        const menu_name = menu_setting.querySelector('.menu_name').value;
        const menu_url_code = menu_setting.querySelector('.menu_url_code').value;
        const menu_url = menu_setting.querySelector('.menu_url').value;
        const is_blank = menu_setting.querySelector('.is_blank').value;
        const is_use = menu_setting.querySelector('.is_use').checked?'Y':'N';

        const menu_div_group_sel = document.getElementById('menu_div_group_sel');
        const tag_group_seq = menu_div_group_sel.querySelectorAll('.badge.d-flex');
        let group_seq = '';
        for(let i=0; i<tag_group_seq.length; i++){
            const tag = tag_group_seq[i];
            const group_seq_tag = tag.querySelector('.group_seq');
            if(group_seq_tag == null) continue;
            if(group_seq != '')
                group_seq += ',';
            group_seq += group_seq_tag.value;
        }

        if(menu_name == ''){
            sAlert('','메뉴명을 입력하세요.');
            return;
        }

        //상위 부모 태그에 폴더가 있는데 폴더로 변경하려고 할때.
        if(menu_url_code == 'folder'){
            const menu_active = document.querySelector('.drag.active');
            if(menu_active != null && menu_active.parentElement.classList.contains('drop-container-in')){
                sAlert('','상위 폴더가 있으면 폴더로 변환 할 수 없습니다.');
                return;
            }
        }
        
        const page = "/manage/menu/update";
        const parameter = {
            menu_seq: menu_seq,
            menu_name: menu_name,
            menu_url_code: menu_url_code,
            menu_url: menu_url,
            is_blank: is_blank,
            is_use: is_use,
            group_seq: group_seq,
        };
        queryFetch(page, parameter, function(result){
            if(result.result == 'success'){
                sAlert('','메뉴가 저장되었습니다.');
                // 메뉴 리스트 가져오기.
                menuGetList(function(){
                    const sel_menu = document.querySelector('.drag[menu_seq="'+menu_seq+'"]');
                    menuClick(sel_menu, true);
                }, true);

                
            }else{
                sAlert('','메뉴 저장에 실패하였습니다.');
            }
        });
    }

    //메뉴 리스트 가져오기
    function menuGetList(callback, is_after_save){
        is_after_save = is_after_save||false;
        let group_seq = document.getElementById('menu_sel_group_seq').value;
        const page = "/manage/menu/select"
        //그룹선택 = 전체메뉴
        if(group_seq == ''){ 
            group_seq = -1;
            document.getElementById('v-pills-home-tab').classList.add('active');
            document.getElementById('v-pills-current-tab').classList.remove('active');
        }
        const parameter = {
            group_seq:group_seq
        };
        // 메뉴 초기화
        if(!is_after_save){
            menuClear();
        }
        queryFetch(page, parameter, function(result){
            // 저장후에는 사라지는 현상을 안보이게 처리.
            if(is_after_save){
                menuClear();
            }
            if(result == null || result.resultCode == null) return;
            if(result.resultCode == 'success'){
                const div_copy= document.getElementById('menu_div_copy_tag');
                const copy_drag = div_copy.querySelector('.drag.no_pt').cloneNode(true);
                const copy_drop = div_copy.querySelector('.drag.drop-container-in').cloneNode(true);
                const copy_drop2 = div_copy.querySelector('.drag.drop-container-in2').cloneNode(true);
                //현재 메뉴 활성화인지 확인.
                let is_current = false;
                const current_tab = document.getElementById('v-pills-current-tab');
                if(current_tab.classList.contains('active')){
                    is_current = true;
                }
                for(let i=0; i<result.menus.length; i++){
                    const menu = result.menus[i];
                    let insert_tag = null;
                    //폴더인지, 메뉴인지 확인. is_folder Y
                    if(menu.is_folder == 'Y'){
                        //형태변환 2뎁스 폴더로
                        insert_tag = menu.menu_url_code == 'folder2' ? copy_drop2.cloneNode(true):copy_drop.cloneNode(true);
                        insert_tag.id = 'folder'+menu.id;
                        insert_tag.innerHTML = '<span>'+menu.menu_name+'</span>';
                        insert_tag.setAttribute('idx', i); // 순서 지정
                        insert_tag.setAttribute('menu_seq', menu.id); 
                        
                        //현재메뉴일때 체크박스 보이기.
                        if(is_current){
                            const copy_sp_is_use = div_copy.querySelector('.copy_sp_is_use').cloneNode(true);
                            copy_sp_is_use.classList.remove('copy_sp_is_use');
                            copy_sp_is_use.classList.add('sp_is_use');
                            copy_sp_is_use.classList.add('ms-2');

                            copy_sp_is_use.querySelector('input').checked = menu.group_is_use == 'Y'?true:false;
                            insert_tag.classList.add('pe-2');
                            insert_tag.appendChild(copy_sp_is_use);
                        }

                        if((menu.menu_pt_seq||'') != ''){
                            //폴더 안에 있는 메뉴이면 폴더 안에 넣어준다.
                            document.querySelector('#folder'+menu.menu_pt_seq).appendChild(insert_tag);
                            continue;
                        }


                    }else{
                        //폴더 안에 있는 메뉴인지 확인 / menu_pt_seq 가 비어있는지 확인
                        insert_tag = copy_drag.cloneNode(true);
                        insert_tag.setAttribute('idx', i); // 순서 P지정
                        insert_tag.innerHTML = menu.menu_name;
                        insert_tag.setAttribute('menu_seq', menu.id); 

                        //현재메뉴일때 체크박스 보이기.
                        if(is_current){
                            // 체크박스 넣기. / 패딩 줄임.
                            const copy_sp_is_use = div_copy.querySelector('.copy_sp_is_use').cloneNode(true);
                            copy_sp_is_use.classList.remove('copy_sp_is_use');
                            copy_sp_is_use.classList.add('sp_is_use');
                            copy_sp_is_use.classList.add('ms-2');

                            copy_sp_is_use.querySelector('input').checked = menu.group_is_use == 'Y'?true:false;
                            copy_sp_is_use.classList.add('float-end');
                            insert_tag.classList.add('pe-2');
                            insert_tag.appendChild(copy_sp_is_use);
                        }
                        if((menu.menu_pt_seq||'') != ''){
                            //폴더 안에 있는 메뉴이면 폴더 안에 넣어준다.
                            document.querySelector('#folder'+menu.menu_pt_seq).appendChild(insert_tag);
                            continue;
                        }
                        
                    }
                    document.querySelector('.drop-container-'+menu.menu_type).appendChild(insert_tag);
                }
            }
            if(callback != undefined) callback();
            menuDropDrag(true); // true is_container_in 만 재정의
            menuSortOnOff(false);
        });
    }

    //메뉴 초기화
    function menuClear(){
        document.querySelectorAll('.drop-container-top .drag').forEach(function(item){
            item.remove();
        });
        document.querySelectorAll('.drop-container-nav .drag').forEach(function(item){
            item.remove();
        });
    }
    //메뉴 클릭시
    function menuClick(vthis, is_save_after){
        if(event != undefined) event.stopPropagation();
        is_save_after = is_save_after||false;
        //편집 활성화시 return;
        const menu_btn_edit_toggle = document.getElementById('menu_btn_edit_toggle');
        if(menu_btn_edit_toggle.classList.contains('active')){
            return;
        }

        if(vthis == null || vthis.classList.contains('active')){
            document.getElementById('v-menu-setting').hidden = true;
            if(vthis == null){return;}
            vthis.classList.remove('active');
            vthis.classList.remove('border');
            vthis.classList.remove('border-secondary');
            vthis.classList.remove('bg-light');
        }else{
            //하나만 활성화

            //저장후에는 숨김을 하지않게.
            if(!is_save_after)
                document.getElementById('v-menu-setting').hidden = true;
            document.querySelectorAll('.drag').forEach(function(item){
                item.classList.remove('active');
                item.classList.remove('border');
                item.classList.remove('border-secondary');
                item.classList.remove('bg-light');
            });
            vthis.classList.add('active');
            vthis.classList.add('border');
            vthis.classList.add('border-secondary');
            vthis.classList.add('bg-light');

            //저장후에는 초기화 하지않게. 이외는 초기화.
            if(!is_save_after)
                menuSettingInit();

            //현재 메뉴가 활성화 되어 있으면.
            const current_tab = document.getElementById('v-pills-current-tab');
            if(current_tab.classList.contains('active')){
                const menu_setting = document.getElementById('v-menu-setting')
                //메뉴명 비활성화
                menu_setting.querySelector('.menu_name').disabled = true;
                //권한 select 안보이게.
                menu_setting.querySelector('.sel_group_seq').hidden = true;
                //메뉴노출 안보이게.
                menu_setting.querySelectorAll('.cls_is_use').forEach(function(item){
                    item.hidden = true;
                });
            }
            //그룹 정보 가져오기
            const menu_seq = vthis.getAttribute('menu_seq');
            menuGroupGet(menu_seq, function(){
                document.getElementById('v-menu-setting').hidden = false;
            });
        }
    }

    //메뉴설정 초기화
    function menuSettingInit(){
        const menu_setting = document.getElementById('v-menu-setting')
        menu_setting.querySelector('.menu_seq').value = '';
        menu_setting.querySelector('.menu_name').value = '';
        menu_setting.querySelector('.murl_name').value = '';
        menu_setting.querySelector('.menu_url_code').value = '';
        menu_setting.querySelector('.menu_url').value = '';
        menu_setting.querySelector('.is_blank').value = 'N';
        //메뉴명 활성화
        menu_setting.querySelector('.menu_name').disabled = false;
        //권한 select 보이게.
        menu_setting.querySelector('.sel_group_seq').value = '';
        menu_setting.querySelector('.sel_group_seq').hidden = false;
        //메뉴노출 보이게.
        menu_setting.querySelectorAll('.cls_is_use').forEach(function(item){
            item.hidden = false;
        });
    }

    // 연결 리스트 URL 클릭시
    function menuUrlClick(vthis){
        const menu_setting = document.getElementById('v-menu-setting');
        const murl_code = vthis.getAttribute('murl_code');
        let url = vthis.getAttribute('url');
        if(murl_code == ""){
            //링크없음
            menu_setting.querySelector('.murl_name').value = "";
            menu_setting.querySelector('.menu_url_code').value = "";
            menu_setting.querySelector('.menu_url').value = "";
        }else{
            let murl_name = vthis.innerText.trim();
            if(murl_code == 'out_link')  murl_name = 'http://';
            if(murl_code == 'logout') { murl_name = '로그아웃'; url = '/manage/logout'; }
            menu_setting.querySelector('.murl_name').value = murl_name; 
            menu_setting.querySelector('.menu_url_code').value = murl_code;
            menu_setting.querySelector('.menu_url').value = url;
        }
    }

    //해당 메뉴의 그룹정보 가져오기. 
    //추가 / 메뉴 정보도 같이 가져온다.
    function menuGroupGet(menu_seq, callback){
        const page = "/manage/menu/groupselect";
        const parameter = {
            menu_seq:menu_seq
        };
        queryFetch(page, parameter, function(result){
            const menu_setting = document.getElementById('v-menu-setting');
            //초기화
            const menu_div_group_sel = document.getElementById('menu_div_group_sel');
            menu_div_group_sel.querySelectorAll('.badge').forEach(function(item){
                item.classList.remove('d-flex');
            });
            if(result == null || result.resultCode == null) return;
            if(result.resultCode == 'success'){

                //현제 메뉴 활성화인지 확인
                let is_current = false;
                const current_tab = document.getElementById('v-pills-current-tab');
                if(current_tab.classList.contains('active')){
                    is_current = true;
                }
                //선택 group_seq 가져오기
                const menu_sel_group_seq = document.getElementById('menu_sel_group_seq');
                const sel_group_seq = menu_sel_group_seq.value;

                //권한 하단 (그룹명)태그 추가.
                for(let i=0; i<result.menu_groups.length; i++){
                    const menu_group = result.menu_groups[i];
                    const tag_group_seq = menu_div_group_sel.querySelector('.seq'+menu_group.group_seq);
                    if(tag_group_seq == null) continue;
                    tag_group_seq.classList.add('d-flex');

                    //현재 메뉴 활성화이면서 선택된 그룹이 아니면 X 버튼 숨김.
                    if(is_current && sel_group_seq != menu_group.group_seq){
                        tag_group_seq.querySelector('a').hidden = true;
                    }else
                        tag_group_seq.querySelector('a').hidden = false;
                }

                //메뉴명, 연결 페이지, 노출 
                const menu = result.menu;
                //메뉴가 메뉴관리 일경우 삭제 버튼 숨김처리.
                //전체메뉴 쪽이 아닐경우에는 삭제는 숨김처리.
                 if(menu.menu_url_code == 'menu' || current_tab.classList.contains('active')){
                    document.getElementById('menu_btn_delete').hidden = true;
                }else{
                    document.getElementById('menu_btn_delete').hidden = false;
                }
                menu_setting.querySelector('.menu_seq').value = menu.id;
                menu_setting.querySelector('.menu_name').value = menu.menu_name;
                const menu_url_code = (menu.menu_url_code||'').replace('/', '_');
                menu_setting.querySelectorAll('.link .code_'+menu_url_code+' a').forEach(function(item){
                    item.click();
                });
                menu_setting.querySelector('.is_blank').value = menu.is_blank||'N';
                
            }

            if(callback != undefined) callback();
        });
    }
    //전체 메뉴 버튼 클릭
    function menuTabAll(vthis){
        const menu_sel_group_seq = document.getElementById('menu_sel_group_seq');
        menu_sel_group_seq.disabled = true;
        menu_sel_group_seq.value = '';

        //활성화 변경.
        document.getElementById('v-pills-home-tab').classList.add('active');
        document.getElementById('v-pills-current-tab').classList.remove('active');

        // 메뉴 설정 숨기기.
        document.getElementById('v-menu-setting').hidden = true;

        //로딩 보이게
        vthis.querySelector('.sp_loding').hidden = false;

        //삭제 보이게
        document.getElementById('menu_btn_delete').hidden = false;

        //메뉴추가 보이게
        document.getElementById('menu_btn_add_toggle').hidden = false;

        // 메뉴 리스트 가져오기.
        menuGetList(function(){
            //로딩 숨기기
            vthis.querySelector('.sp_loding').hidden = true;
        });            
    }

    //현재 메뉴 버튼 클릭
    function menuTabCurrent(vthis){
        const menu_sel_group_seq = document.getElementById('menu_sel_group_seq');
        menu_sel_group_seq.disabled = false;
        //2번째 option 선택
        menu_sel_group_seq.options[1].selected = true;

        //활성화 변경.
        document.getElementById('v-pills-home-tab').classList.remove('active');
        document.getElementById('v-pills-current-tab').classList.add('active');

        // 메뉴 설정 숨기기.
        document.getElementById('v-menu-setting').hidden = true;

        //로딩 보이게
        vthis.querySelector('.sp_loding').hidden = false;

        //삭제 숨기기
        document.getElementById('menu_btn_delete').hidden = true;

        //메뉴추가 숨기기
        document.getElementById('menu_btn_add_toggle').hidden = true;

        //편집 비활성화
        document.getElementById('menu_btn_edit_toggle').classList.remove('active');
        menuSortOnOff(false);

        // 메뉴 리스트 가져오기.
        menuGetList(function(){
            //로딩 숨기기
            vthis.querySelector('.sp_loding').hidden = true;
        });


    }

    //메뉴 설정에서 권한의 그룹 선택시
    function menuSettingGroupSel(vthis){
        const group_seq = vthis.value;
        const menu_div_group_sel = document.getElementById('menu_div_group_sel');
        const tag_group_seq = menu_div_group_sel.querySelector('.seq'+group_seq);
        if(tag_group_seq == null) return;
        tag_group_seq.classList.add('d-flex');
    }

    //메뉴 설정에서 권한의 그룹 삭제시
    function menuSettingGroupDel(group_seq){
        const menu_div_group_sel = document.getElementById('menu_div_group_sel');
        const tag_group_seq = menu_div_group_sel.querySelector('.seq'+group_seq);
        if(tag_group_seq == null) return;
        tag_group_seq.classList.remove('d-flex');
    }

    //메뉴 삭제 확인 
    function menuAskDelete(){
        //정말로 삭제하시겟습니까?
        //하위메뉴가 있습니다. 하위 메뉴를 이동해주세요. = 현재 선택 메뉴가 폴더이면서 하위 메뉴가 있을때.
        let msg = '정말로 삭제하시겠습니까?';
        // .drag.active contain .drop-container-in and 안에 .drag가 하나라도 있으면.
        const menu_active = document.querySelector('.drag.active');
        if(menu_active != null && menu_active.querySelector('.drop-container-in') != null){
            const menu_active_in = menu_active.querySelector('.drop-container-in');
            if(menu_active_in.querySelector('.drag') != null){
                msg += '<br>하위메뉴가 있습니다. 하위 메뉴를 이동해주세요. 모두 삭제하시겠습니까?';
            }
        }
        sAlert('삭제안내', msg, 2, 
        function(){
            //삭제
            menuDelete();
        }, 
        function(){
            //취소
        }, 
        '삭제', 
        '취소');
    }

    //메뉴 삭제
    function menuDelete(){
        //삭제시 메뉴는 복구 할 수 없습니다. 삭제하시겠습니까?
        const msg = '삭제시 메뉴는 복구 할 수 없습니다. 삭제하시겠습니까?';
        setTimeout(function(){
            sAlert('삭제안내', msg, 2, 
            function (){
                const menu_seq = document.querySelector('.drag.active').getAttribute('menu_seq');
                const page = "/manage/menu/delete";
                const parameter = {
                    menu_seq:menu_seq
                };
                queryFetch(page, parameter, function(result){
                    if(result.resultCode == 'success'){
                        sAlert('','메뉴가 삭제되었습니다.');
                        // 메뉴 리스트 가져오기.
                        menuGetList();

                        // 메뉴 설정 숨기기.
                        document.getElementById('v-menu-setting').hidden = true;
                    }else{
                        sAlert('','메뉴 삭제에 실패하였습니다.');
                    }
                });
            });
        }, 200);
        
    }

    //편집 버튼 끌때 idx를 저장.
    function menuIdxSave(){
        let group_seq = document.getElementById('menu_sel_group_seq').value;
        if(group_seq == '') group_seq = -1;

        //메뉴들을 순서대로 가져온다.
        const menus = document.querySelectorAll('.drop-container-top .drag, .drop-container-nav .drag');

        //각각의 menu_seq와 순서를 가져온다.
        let menu_seqs = '';
        let idxs = '';
        let menu_pt_seqs = '';
        let menu_types = '';
        for(let i=0; i<menus.length; i++){
            const menu = menus[i];
            
            //부모태그가 drop-container-in 클래스 가지고 있으면 menu_pt_seq에 부모의 menu_seq를 넣기.
            if(i != 0) {
                menu_seqs += ',';
                idxs += ',';
                menu_pt_seqs += ',';
                menu_types += ',';
            }
            if(menu.parentElement.classList.contains('drop-container-in') || 
            menu.parentElement.classList.contains('drop-container-in2')){
                menu_pt_seqs += menu.parentElement.getAttribute('menu_seq');
            }
            else
                menu_pt_seqs += '0';
            menu_seqs += menu.getAttribute('menu_seq');
            idxs += i;
            //menu 상위에 drop-container-top 클래스가 있는지 확인.
            if(menu.parentElement.classList.contains('drop-container-top'))
                menu_types += 'top';
            else
                menu_types += 'nav';
        }
        
        const page = "/manage/menu/idxupdate";
        const parameter = {
            group_seq:group_seq,
            menu_seqs:menu_seqs,
            idxs:idxs,
            menu_pt_seqs:menu_pt_seqs,
            menu_types:menu_types,
        };  
        queryFetch(page, parameter, function(result){
            if(result.resultCode == 'success'){
                sAlert('','메뉴 순서가 저장되었습니다.');
            }else{
                sAlert('','메뉴 순서 저장에 실패하였습니다.');
            }
        });
    }
    
    // 연결 리스트 스크립트로 URL 검색
    function menuUrlSearch(){
        const menu_setting = document.getElementById('v-menu-setting');
        const murl_name = menu_setting.querySelector('.murl_name').value;
        const menu_url_list = document.getElementById('menu_url_list');

        //공백일때는 전체 노출.
        if(murl_name == ''){
            menu_url_list.querySelectorAll('li').forEach(function(item){
                item.hidden = false;
            });
            return;
        }
        //li a 중 innerText에 murl_name이 포함되어 있는지 확인.
        menu_url_list.querySelectorAll('li').forEach(function(item){
            const a = item.querySelector('a');
            if(a.innerText.indexOf(murl_name) != -1){
                item.hidden = false;
            }else{
                item.hidden = true;
            }
        });
    }

    // 그룹별 / 메뉴별 노출 여부 
    function menuGroupIsUse(vthis){
        event.stopPropagation();
        const is_use = vthis.checked?'Y':'N';
        const group_seq = document.querySelector('#menu_sel_group_seq').value;
        const menu_seq = vthis.closest('.drag').getAttribute('menu_seq');
        const page = "/manage/menu/groupupdate";
        const parameter = {
            group_seq:group_seq,
            menu_seq:menu_seq,
            is_use:is_use,
        };
        queryFetch(page, parameter, function(result){
            if(result.resultCode == 'success'){
                toast('메뉴 노출 여부가 저장되었습니다.');
            }else{
                toast('','메뉴 노출 여부 저장에 실패하였습니다.');
            }
        });
    }

    // 그룹별 / 메뉴별 노출 여부 비활성화
    function menuGroupIsUseDisable(is_bool){
        const menu_sp_is_use = document.querySelectorAll('.sp_is_use');
        menu_sp_is_use.forEach(function(item){
            item.querySelector('input').disabled = !is_bool;
        });
    }
</script>