@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
    관리자 계정 관리
@endsection

{{-- 네브바 체크 --}}
@section('system_management')
@endsection
@section('systemauthority')
    active
@endsection

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    <div class="col-12 pe-3 ps-3 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
            <h4>권한 관리</h4>
            <div>

            </div>
        </div>

        {{-- 상단 조회 기능 --}}
        <div class="row gap-3 px-3">
            <select id="authority_inp_search_type" class="hpx-40 col-1">
                {{-- 관리자ID, 관리자명, 관리자권한 [추가 코드] --}}
                <option value="group_name">그룹명</option>
                <option value="is_use">사용</option>
                <option value="created_id">등록자ID</option>

            </select>
            <input id="authority_inp_search_str" type="text" placeholder="검색어를 입력해주세요." class="input col"
                onkeyup="if(event.keyCode == 13) systemauthorityList();">
            <button class="btn btn-outline-primary rounded col-1" onclick="systemauthorityList();">조회</button>
        </div>

        {{-- 권한 등록 버튼 --}}
        <div class="row mt-2 mb-2 justify-content-end pe-3">
            <button class="btn btn-primary float-end col-1" onclick="systemauthorityEditOpen()">새그룹등록</button>
        </div>

        {{-- 권한?/그룹 리스트 --}}
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>그룹명</th>
                            <th>사용</th>
                            <th>등록자</th>
                            <th>등록일</th>
                            <th>수정자</th>
                            <th>수정일</th>
                            <th>기능</th>
                        </tr>
                    </thead>
                    <tbody id="systemauthority_tby_list">
                        <tr class="copy_tr_authority_list" hidden>
                            <td class="group_name">#그룹명</td>
                            <td class="is_use">#사용</td>
                            <td class="created_id">#등록자</td>
                            <td class="created_at">#등록일</td>
                            <td class="updated_id">#수정자</td>
                            <td class="updated_at">#수정일</td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm"
                                    onclick="systemauthorityEditOpen(true);">수정</button>
                                <button class="btn_del btn btn-outline-danger btn-sm" onclick="systemauthorityDelete(this);">삭제</button>
                            </td>
                            <input type="hidden" class="group_seq">
                            <input type="hidden" class="first_page">
                            <input type="hidden" class="remark">
                            <input type="hidden" class="group_type">
                            <input type="hidden" class="group_type2">
                        </tr>


                        @foreach ($user_groups as $user_group)
                            <tr class="tr_authority_list">
                                <td class="group_name">{{ $user_group->group_name }}</td>
                                <td class="is_use"> {{ $user_group->is_use }} </td>
                                <td class="created_id"> {{ $user_group->created_teach_id }} </td>
                                <td class="created_at"> {{ $user_group->created_at }} </td>
                                <td class="updated_id"> {{ $user_group->updated_teach_id }} </td>
                                <td class="updated_at"> {{ $user_group->updated_at }}</td>
                                <td>
                                    <button class="btn btn-outline-primary btn-sm"
                                        onclick="systemauthorityEditOpen(true);">수정</button>
                                    @if($user_group->is_not_del != 'Y')
                                    <button class="btn btn-outline-danger btn-sm"
                                        onclick="systemauthorityDelete(this);">삭제</button>
                                    @endif
                                </td>
                                <input type="hidden" class="group_seq" value="{{ $user_group->id }}">
                                <input type="hidden" class="first_page" value="{{ $user_group->first_page }}">
                                <input type="hidden" class="remark" value="{{ $user_group->remark }}">
                                <input type="hidden" class="group_type" value="{{ $user_group->group_type }}">
                                <input type="hidden" class="group_type2" value="{{ $user_group->group_type2 }}">
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            {{-- 등록 / 수정 window --}}
            {{-- 정중앙에 오도록 / 배경 흰색 --}}
            <div class="position-absolute start-50 translate-middle border rounded bg-white col-5 p-5"
                style="z-index: 1;margin-top: 10%" id="systemauthority_div_edit" hidden>
                <h5 class="p-2">
                    <span>그룹</span>
                    <span class="edit_str">등록</span>
                </h5>
                {{-- 수정시 그룹 group_seq --}}
                <input type="hidden" id="systemauthority_inp_group_seq">
                <table class="table table-bordered">
                    <tr>
                        <th class="table-light" style="width: 20%">*그룹명</th>
                        <td><input type="text" class="group_name input col border border-secondary w-100"
                                placeholder="그룹명을 입력해주세요."></td>
                    </tr>
                    <tr>
                        <th class="table-light">그룹설명</th>
                        <td><input type="text" class="remark input col border border-secondary w-100"
                                placeholder="그룹설명을 입력해주세요."></td>
                    </tr>
                    {{-- 그룹유형2 --}}
                    <tr>
                        <th class="table-light">그룹유형</th>
                        <td>
                            <select class="group_type2 hpx-30 col border border-secondary w-100"
                                onchange="systemauthorityGroupType2(this);">
                                <option value="">선택</option>
                                <option value="normal">일반</option>
                                <option value="general">운영(총괄)</option>
                                <option value="run">운영</option>
                                <option value="manage">관리자</option>
                            </select>
                        </td>
                    </tr>
                    {{-- 그룹유형1 --}}
                    <tr class="tr_group_type" hidden>
                        <th class="table-light">세부유형</th>
                        <td>
                            <select class="group_type hpx-30 col border border-secondary w-100">
                                <option value="student">학생</option>
                                <option value="parent">학부모</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="table-light">*사용여부</th>
                        <td>
                            <select class="is_use hpx-30 co border border-secondary w-100">
                                <option value="">선택</option>
                                <option value="Y">사용</option>
                                <option value="N">미사용</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="table-light">첫화면</th>
                        <td>
                            <select id="systemauthority_inp_first_page" class="hpx-30 col border border-secondary w-100">
                                <option value="">선택</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="ps-3 table-light"> 권한 관리 </th>
                        <td>
                            <button class="btn btn-outline-secondary w-100 btn-sm" onclick="systemauthorityMenuAuth()">메뉴 권한 설정</button>
                        </td>
                    </tr>

                </table>

                {{-- 닫기, 저장, 삭제 --}}
                <div class="text-end">
                    <button class="btn btn-outline-secondary mt-2" onclick=" systemauthorityEditClose();">닫기</button>
                    <button class="btn btn-outline-primary mt-2 me-2" onclick="systemauthoritySave();">저장</button>
                </div>
            </div>
        </div>
        {{-- 메뉴 권한  설정--}}
        <div id="systeamauthority_div_menu_auth" class="position-absolute justify-content-center vh-80 bg-white border rounded" hidden
        style="top:-20px;margin:auto;left:0px;right:0px; min-width:400px;width:40%;overflow: auto;min-height:calc(100vh - 100px);z-index:2">
            <div class="col-12 ">
                @include('admin.admin_site_menu_detail', ['is_part' => 'Y'])
            </div>
            <div class="col position-relative" style="width:0px;">
                <button class="btn  mt-2 position-absolute rounded-0" id="systeamauthority_btn_edit_close"
                    style="left: -60px;top: 8px;color: blue;background: white;" onclick="systeamauthorityMenuClose();" >
                        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
            <script>
                window.onload = function() {
                    //메뉴 설정
                    menuDropDrag();
                    const v_pills_current_tab = document.querySelector('#v-pills-current-tab');
                    v_pills_current_tab.click();
                }

                // 권한 / 그룹 리스트 조회
                function systemauthorityList(){
                    const search_type = document.querySelector('#authority_inp_search_type').value;
                    const search_str = document.querySelector('#authority_inp_search_str').value;
                    const page = '/manage/systemauthority/select';
                    const parameter = {
                        search_type: search_type,
                        search_str: search_str,
                    };

                    queryFetch(page, parameter, function(result){
                        //권한 / 그룹 리스트 초기화.
                        systemauthorityListClear();

                        if(result == null || result.resultCode == null){
                            sAlert('', '실패하였습니다. 다시 시도해주세요.');
                            return false;
                        }
                        if(result.resultCode == 'success'){
                            const systemauthority_tby_list = document.getElementById('systemauthority_tby_list');
                            const copy_tr_authority_list = systemauthority_tby_list.querySelector('.copy_tr_authority_list');

                            const user_groups = result.user_groups;
                            for(let i = 0; i < user_groups.length; i++){
                                const user_group = user_groups[i];
                                const tr_authority_list = systemauthority_tby_list.querySelector('.tr_authority_list');
                                const clone_tr_authority_list = copy_tr_authority_list.cloneNode(true);
                                clone_tr_authority_list.hidden = false;
                                clone_tr_authority_list.querySelector('.group_name').innerText = user_group.group_name;
                                clone_tr_authority_list.querySelector('.is_use').innerText = user_group.is_use;
                                clone_tr_authority_list.querySelector('.created_id').innerText = user_group.created_teach_id;
                                clone_tr_authority_list.querySelector('.created_at').innerText = user_group.created_at;
                                clone_tr_authority_list.querySelector('.updated_id').innerText = user_group.updated_teach_id;
                                clone_tr_authority_list.querySelector('.updated_at').innerText = user_group.updated_at;
                                clone_tr_authority_list.querySelector('.group_seq').value = user_group.id;
                                clone_tr_authority_list.querySelector('.first_page').value = user_group.first_page;
                                clone_tr_authority_list.querySelector('.remark').value = user_group.remark;
                                // group_type
                                clone_tr_authority_list.querySelector('.group_type').value = user_group.group_type
                                // group_type2
                                clone_tr_authority_list.querySelector('.group_type2').value = user_group.group_type2

                                if((user_group.is_not_del||'') == 'Y')
                                    clone_tr_authority_list.querySelector('.btn_del').remove();
                                systemauthority_tby_list.appendChild(clone_tr_authority_list);
                            }
                        }
                    });
                }
                // 권한 / 그룹 목록 리스트 초기화.
                function systemauthorityListClear(){
                    const systemauthority_tby_list = document.querySelector('#systemauthority_tby_list');
                    const copy_tr_authority_list = systemauthority_tby_list.querySelector('.copy_tr_authority_list');
                    systemauthority_tby_list.innerHTML = '';
                    systemauthority_tby_list.appendChild(copy_tr_authority_list);
                }
                // 권한 / 그룹 등록
                function systemauthorityEditOpen(is_edit) {
                    // 수정일 경우 정보 불러오기
                    if(is_edit){
                        const target_tag = event.target;
                        systemauthorityEditInfo(target_tag);
                    }
                    const systemauthority_div_edit = document.getElementById('systemauthority_div_edit');
                    systemauthority_div_edit.hidden = false;
                }

                // 권한 / 그룹 등록 닫기
                function systemauthorityEditClose() {
                    const systemauthority_div_edit = document.getElementById('systemauthority_div_edit');
                    systemauthority_div_edit.hidden = true;
                    // 등록으로 글자 변경.
                    const edit_str = systemauthority_div_edit.querySelector('.edit_str');
                    edit_str.innerText = '등록';

                    //그룹명, 그룹설명, 사용여부, 첫화면. seq 초기화
                    systemauthorityEditClear();
                }

                // 그룹명, 그룹설명, 사용여부, 첫화면. seq 초기화
                function systemauthorityEditClear(){
                    const systemauthority_div_edit = document.getElementById('systemauthority_div_edit');
                    systemauthority_div_edit.querySelector('.group_name').value = '';
                    systemauthority_div_edit.querySelector('.remark').value = '';
                    systemauthority_div_edit.querySelector('.group_type2').value = '';
                    systemauthority_div_edit.querySelector('.group_type').value = '';
                    systemauthority_div_edit.querySelector('.is_use').value = '';
                    systemauthority_div_edit.querySelector('#systemauthority_inp_group_seq').value = '';
                    const first_page = document.querySelector('#systemauthority_inp_first_page');
                    first_page.innerHTML = '<option value="">선택</option>';

                }

                // 그룹유형2 선택시 / noraml 일때만 그룹유형1 보이기
                function systemauthorityGroupType2(vthis) {
                    const this_value = vthis.value;
                    const systemauthority_div_edit = document.getElementById('systemauthority_div_edit');
                    const tr_group_type = systemauthority_div_edit.querySelector('.tr_group_type');
                    if (this_value == 'normal') {
                        // 그룹유형1 보이기
                        tr_group_type.hidden = false;
                    } else {
                        // 그룹유형1 숨기기
                        tr_group_type.hidden = true;
                    }
                }

                // 권한 / 그룹 저장
                function systemauthoritySave(){
                    const systemauthority_div_edit = document.getElementById('systemauthority_div_edit');
                    const group_seq = systemauthority_div_edit.querySelector('#systemauthority_inp_group_seq').value;
                    const group_name = systemauthority_div_edit.querySelector('.group_name').value;
                    const remark = systemauthority_div_edit.querySelector('.remark').value;
                    const group_type2 = systemauthority_div_edit.querySelector('.group_type2').value;
                    let group_type = systemauthority_div_edit.querySelector('.group_type').value;
                    const is_use = systemauthority_div_edit.querySelector('.is_use').value;
                    const first_page = document.querySelector('#systemauthority_inp_first_page').value;

                    // 그룹명, 사용여부, 첫화면 필수 입력
                    if(group_name == '' || is_use == '' ){
                        sAlert('','그룹명, 사용여부 필수 입력입니다.');
                        return false;
                    }

                    // 그룹유형2가 normal 이 아니면 그룹유형1은 각각 그룹유형2에 맞게 입력
                    if(group_type2 == 'run'){ group_type = 'teacher';}
                    if(group_type2 == 'manage'){ group_type = 'admin';}
                    if(group_type2 == 'general'){ group_type = 'teacher';}
                    if(group_type2 == 'normal' && group_type == ''){ sAlert('', '일반 선택시 세부유형을 선택해주세요.'); return;}

                    //
                    const page = '/manage/systemauthority/insert';
                    const parameter = {
                        group_seq: group_seq,
                        group_name: group_name,
                        remark: remark,
                        group_type2: group_type2,
                        group_type: group_type,
                        is_use: is_use,
                        first_page: first_page,
                    };
                    queryFetch(page, parameter, function(result){
                        if(result == null || result.resultCode == null){
                            sAlert('','실패하였습니다. 다시 시도해주세요.');
                            return false;
                        }

                        if(result.resultCode == 'success'){
                            let msg = '';
                            if(group_seq == '') msg = '등록';
                            else msg = '수정';
                            sAlert(msg, msg+'이 완료되었습니다.');
                            systemauthorityEditClose();
                            systemauthorityList();
                        }

                    });
                }

                // 권한 / 그룹 삭제
                function systemauthorityDelete(vthis){
                    const tag_tr = vthis.closest('tr');
                    const group_seq = tag_tr.querySelector('.group_seq').value;
                    const group_name = tag_tr.querySelector('.group_name').innerText;

                    sAlert('삭제', '정말로 해당 그룹을 삭제진행하시겠습니까?', 2, function(){
                        const page = '/manage/systemauthority/delete';
                        const parameter = {
                            group_seq: group_seq,
                            group_name: group_name,
                        };
                        queryFetch(page, parameter, function(result){
                            if(result == null || result.resultCode == null){
                                sAlert('', '실패하였습니다. 다시 시도해주세요.');
                                return false;
                            }
                            if(result.resultCode == 'success'){
                                sAlert('삭제', '삭제가 완료되었습니다.');
                                systemauthorityList();
                            }
                        });
                    });
                }

                // 수정 클릭시 > 수정창에 정보 불러오기
                function systemauthorityEditInfo(vthis){
                    const tag_tr = vthis.closest('tr');
                    const group_seq = tag_tr.querySelector('.group_seq').value;
                    const group_name = tag_tr.querySelector('.group_name').innerText;
                    const remark = tag_tr.querySelector('.remark').value;
                    const group_type2 = tag_tr.querySelector('.group_type2').value;
                    const group_type = tag_tr.querySelector('.group_type').value;
                    const is_use = tag_tr.querySelector('.is_use').innerText;
                    const first_page = tag_tr.querySelector('.first_page').value;

                    // 첫화면 가져와서 선택
                    systemauthorityGetMenuList(group_seq, first_page);

                    const systemauthority_div_edit = document.getElementById('systemauthority_div_edit');
                    systemauthority_div_edit.querySelector('.group_name').value = group_name;
                    systemauthority_div_edit.querySelector('.remark').value = remark;
                    systemauthority_div_edit.querySelector('.group_type2').value = group_type2;
                    systemauthority_div_edit.querySelector('.group_type').value = group_type;
                    systemauthority_div_edit.querySelector('.is_use').value = is_use;
                    systemauthority_div_edit.querySelector('#systemauthority_inp_group_seq').value = group_seq;
                }

                //그룹관리 선택 > 그룹에 속한 페이지 정보 가져오기 > 첫페이지 select option에 넣기.
                function systemauthorityGetMenuList(group_seq, menu_seq) {
                    const page = '/manage/systemadmin/menu/select';
                    const parameter = {
                        group_seq: group_seq
                    };
                    queryFetch(page, parameter, function(result) {
                        if (result == null || result.resultCode == null) {
                            toast('조회에 실패하였습니다.');
                            return;
                        }
                        // 첫페이지 select option 초기화.
                        const first_page = document.querySelector('#systemauthority_inp_first_page');
                        first_page.innerHTML = '<option value="">선택</option>';

                        // 첫페이지 select option 넣기.
                        for (let i = 0; i < result.menus.length; i++) {
                            const menu = result.menus[i];
                            const option = document.createElement('option');
                            option.value = menu.id;
                            option.innerText = menu.menu_name;
                            first_page.appendChild(option);
                        }
                        if (menu_seq != null) {
                            first_page.value = menu_seq;
                        }
                    });
                }

                // 메뉴 권한 설정
                function systemauthorityMenuAuth(){
                    const systemauthority_div_edit = document.getElementById('systemauthority_div_edit');
                    const group_seq = systemauthority_div_edit.querySelector('#systemauthority_inp_group_seq').value;
                    if(group_seq == ''){
                        sAlert('', '수정시에만 그룹관리를 진행 할 수 있습니다.');
                        return;
                    }

                    systeamauthorityMenuOpen(group_seq);
                }

                 // 메뉴 권한 설정 창 닫기
                function systeamauthorityMenuClose(){
                    const systeamauthority_div_menu_auth = document.querySelector('#systeamauthority_div_menu_auth');
                    systeamauthority_div_menu_auth.classList.remove('d-flex');
                }

                // 메뉴 권한 설정 창 열기
                function systeamauthorityMenuOpen(group_seq){
                    // 관리자 그룹 선택 후 메뉴 권한 설정 창 열기.
                    const menu_sel_group_seq = document.querySelector('#menu_sel_group_seq');
                    menu_sel_group_seq.value = group_seq;
                    menu_sel_group_seq.onchange();

                    //v-pills-current-tab
                    // const btn_group_name = document.querySelector('#v-pills-current-tab .sp_text');
                    // btn_group_name.innerText = menu_sel_group_seq.options[menu_sel_group_seq.selectedIndex].text;

                    //
                    const systeamauthority_div_menu_auth = document.querySelector('#systeamauthority_div_menu_auth');
                    systeamauthority_div_menu_auth.classList.add('d-flex');
                }
            </script>
        @endsection
