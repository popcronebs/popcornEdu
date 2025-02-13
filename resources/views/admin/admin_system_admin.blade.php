@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title', '관리자 계정 관리')

{{-- 네브바 체크 --}}
@section('systemadmin', 'active')

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    {{-- 관리자 계정 관리 --}}

    <div class="col-12 pe-3 ps-3 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
            <h4>관리자 계정 관리</h4>
            <div>

            </div>
        </div>

        {{-- 상단 조회 기능 --}}
        <div class="row gap-3 px-3">
            <select id="systemadmin_inp_search_type" class="hpx-40 col-1">
                {{-- 관리자ID, 관리자명, 관리자권한 --}}
                <option value="teach_id">관리자ID</option>
                <option value="teach_name">관리자명</option>
                <option value="group_seq">관리자권한</option>
            </select>
            <input id="systemadmin_inp_search_str" type="text" placeholder="검색어를 입력해주세요." class="input col"
                onkeyup="if(event.keyCode == 13) systemadminList();">
            <button class="btn btn-outline-primary rounded col-1" onclick="systemadminList();">조회</button>
        </div>

        {{-- 관리자 계정 등록 버튼 --}}
        <div class="row mt-2 mb-2 justify-content-end pe-3">
            <button class="btn btn-primary float-end col-1" onclick="systemadminEditOpen()">등록</button>
        </div>

        {{-- 관리자 리스트 --}}
        <div>
            <table class="table table-bordered text-center">
                <thead>
                    <tr class="table-light">
                        <th>관리자ID</th>
                        <th>관리자명</th>
                        <th>연락처 / 비상</th>
                        <th>관리자그룹명</th>
                        <th>사용</th>
                        <th>등록자</th>
                        <th>등록일</th>
                        <th>수정자</th>
                        <th>수정일</th>
                        <th>기능</th>
                    </tr>
                </thead>
                <tbody id="systemadmin_tby_list">
                    <tr class="copy_tr_admin_list" hidden>
                        <td class="teach_id">#관리자ID</td>
                        <td class="teach_name">#관리자명</td>
                        <td>
                            <span class="teach_phone"></span>
                            <span>/</span>
                            <span class="teach_phone2"></span>
                        </td>
                        <td class="group_name">#관리자그룹명</td>
                        <td class="teach_status">#사용</td>
                        <td class="created_id">#등록자</td>
                        <td class="created_at">#등록일</td>
                        <td class="updated_id">#수정자</td>
                        <td class="updated_at">#수정일</td>
                        <td class="">
                            <button class="btn btn-sm btn-outline-danger" onclick="systemadminEditModify(this)">수정</button>
                        </td>
                        <input type="hidden" class="teach_seq">
                        <input type="hidden" class="group_seq">
                        <input type="hidden" class="first_page">
                    </tr>
                    {{-- 선생님(관리자) 리스트 보여주기. --}}
                    @foreach ($teachers as $teacher)
                        <tr class="tr_admin_list">
                            <td class="teach_id">{{ $teacher->teach_id }}</td>
                            <td class="teach_name">{{ $teacher->teach_name }}</td>
                            <td>
                                <span class="teach_phone">
                                    {{ $teacher->teach_phone }}
                                    {{-- {{ Str::substr($teacher->teach_phone, 0, 3).' -'}}
                                    {{ Str::substr($teacher->teach_phone, 3, 4).' -'}}
                                    {{ Str::substr($teacher->teach_phone, 7, 4) }} --}}
                                </span>
                                <span>/</span>
                                <span class="teach_phone2">
                                    {{ $teacher->teach_phone2 }}
                                    {{-- {{ Str::substr($teacher->teach_phone2, 0, 3).' -'}}
                                    {{ Str::substr($teacher->teach_phone2, 3, 4).' -'}}
                                    {{ Str::substr($teacher->teach_phone2, 7, 4) }} --}}
                                </span>
                            </td>
                            <td class="group_name">{{ $teacher->group_name }}</td>
                            <td class="teach_status">{{ $teacher->teach_status}}</td>
                            <td class="created_id">{{ $teacher->created_id }}</td>
                            <td class="created_at">{{ Str::substr($teacher->created_at, 0, 10) }}</td>
                            <td class="updated_id">{{ $teacher->updated_id }}</td>
                            <td class="updated_at">{{ Str::substr($teacher->updated_at, 0, 10) }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-danger"
                                    onclick="systemadminEditModify(this)">수정</button>
                            </td>
                            <input type="hidden" class="teach_seq" value="{{ $teacher->id }}">
                            <input type="hidden" class="group_seq" value="{{ $teacher->group_seq }}">
                            <input type="hidden" class="first_page" value="{{ $teacher->first_page }}">
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- 등록 / 수정 window --}}
        {{-- 정중앙에 오도록 / 배경 흰색 --}}
        <div class="position-absolute start-50 translate-middle border rounded bg-white col-8 p-5" style="z-index: 1"
            id="systemadmin_div_edit" hidden>
            <h5 class="p-2">
                <span>관리자</span>
                <span class="edit_str">등록</span>
            </h5>

            {{-- 수정시 선생님(관리자) teach_seq --}}
            <input type="hidden" id="systemadmin_teach_seq" value="">
            <table class="table table-bordered">
                <tr>
                    <th class="ps-3 table-light w-25">*관리자ID</th>
                    <td class="w-25">
                        <input class="teach_id w-100 border border-secondary">
                    </td>
                    <th class="ps-3 w-25 table-light">관리자명</th>
                    <td class="w-25">
                        <input class="teach_name w-100 border border-secondary">
                    </td>
                </tr>
                <tr>
                    <th class="ps-3 table-light">*비밀번호</th>
                    <td class="">
                        <input class="teach_pw1 w-100 border border-secondary" type="password">
                    </td>
                    <th class="ps-3 table-light">*비밀번호 확인</th>
                    <td class="">
                        <input class="teach_pw2 w-100 border border-secondary" type="password">
                    </td>
                </tr>
                <tr>
                    <th class="ps-3 table-light">연락처</th>
                    <td class="">
                        <input class="teach_phone w-100 border border-secondary">
                    </td>
                    <th class="ps-3 table-light">비상연락처</th>
                    <td class="">
                        <input class="teach_phone2 w-100 border border-secondary">
                    </td>
                </tr>
                <tr>

                    <th class="ps-3 table-light">*사용여부</th>
                    <td class="">
                        <select class="w-100 hpx-30 border border-secondary teach_status">
                            <option value="">선택</option>
                            <option value="Y">사용</option>
                            <option value="N">미사용</option>
                        </select>
                    </td>
                    <th class="ps-3 table-light">관리자그룹</th>
                    <td class="">
                        <input type="hidden" class="group_seq">
                        <div class="dropdown">
                            <div class="btn-group">
                                <input class="group_name dropdown-toggle border border-secondary col"
                                    onkeyup="systemadminClearGroup(this);" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                <button class="btn_group_insert btn btn-sm btn-secondary"
                                    onclick="systemadminGroupSave();">등록</button>
                                <input type="hidden" class="group_seq">
                                <ul class="dropdown-menu w-100">
                                    @if ($usergroups)
                                        @foreach ($usergroups as $usergroup)
                                            <li><a class="dropdown-item" group_seq="{{ $usergroup->id }}"
                                                    onclick="systemadminClickGroup(this);">{{ $usergroup->group_name }}</a>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <th class="ps-3 table-light"> 권한 관리 </th>
                    <td>
                        <button class="btn btn-outline-secondary w-100 btn-sm" onclick="systemadminMenuAuth();">메뉴 권한
                            설정</button>
                    </td>
                    <th class="ps-3 table-light"> 첫페이지 </th>
                    <td>
                        <select class="first_page w-100 hpx-30 border border-secondary ">
                            <option value="">먼저그룹선택</option>
                        </select>
                    </td>
                </tr>
            </table>
            {{-- 닫기, 저장, 삭제 --}}
            <div class="text-end">
                <button class="btn btn-outline-secondary mt-2" id="systemadmin_btn_edit_close"
                    onclick=" systemadminEditClose();">닫기</button>
                <button class="btn btn-outline-primary mt-2 me-2" id="systemadmin_btn_edit_save"
                    onclick="systemadminSave();">저장</button>
                <button class="btn btn-outline-danger mt-2 me-2" id="systemadmin_btn_edit_delete" hidden
                    onclick="systemadminDelete();">삭제</button>
            </div>
        </div>


        {{-- 메뉴 권한  설정--}}
        <div id="systeamadmin_div_menu_auth" class="position-absolute justify-content-center vh-80 bg-white border rounded" hidden
        style="top:-20px;margin:auto;left:0px;right:0px; min-width:400px;width:40%;height:100%;overflow: auto;min-height:calc(100vh - 100px);z-index:2">
            <div class="col-12">
                @include('admin.admin_site_menu_detail', ['is_part' => 'Y'])
            </div>
            <div class="col position-relative" style="width:0px;">
                <button class="btn  mt-2 position-absolute rounded-0" id="systemadmin_btn_edit_close"
                    style="left: -60px;top: 8px;color: blue;background: white;" onclick="systemadminMenuClose();" >
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
        // 관리자 관리 리스트 조회
        function systemadminList() {
            const search_type = document.querySelector('#systemadmin_inp_search_type').value;
            const search_str = document.querySelector('#systemadmin_inp_search_str').value;
            const parameter = {
                search_type: search_type,
                search_str: search_str
            };
            const page = '/manage/systemadmin/select';
            queryFetch(page, parameter, function(result) {
                // 관리자 목록 리스트 초기화.
                systemadminListClear();
                if (result == null || result.resultCode == null) {
                    sAlert('', '조회에 실패하였습니다.');
                    return;
                }
                const systemadmin_tby_list = document.querySelector('#systemadmin_tby_list');
                const copy_tr_admin_list = systemadmin_tby_list.querySelector('.copy_tr_admin_list');
                for (let i = 0; i < result.teachers.length; i++) {
                    const teacher = result.teachers[i];
                    const tr_admin_list = copy_tr_admin_list.cloneNode(true);
                    tr_admin_list.hidden = false;
                    tr_admin_list.querySelector('.teach_id').innerText = teacher.teach_id;
                    tr_admin_list.querySelector('.teach_name').innerText = teacher.teach_name;
                    tr_admin_list.querySelector('.teach_phone').innerText = teacher.teach_phone;
                    tr_admin_list.querySelector('.teach_phone2').innerText = teacher.teach_phone2;
                    tr_admin_list.querySelector('.group_name').innerText = teacher.group_name;
                    tr_admin_list.querySelector('.teach_status').innerText = teacher.teach_status == 1 ? 'Y' : 'N';
                    tr_admin_list.querySelector('.created_id').innerText = teacher.created_id;
                    tr_admin_list.querySelector('.created_at').innerText = (teacher.created_at || '').substr(0, 10);
                    tr_admin_list.querySelector('.updated_id').innerText = teacher.updated_id;
                    tr_admin_list.querySelector('.updated_at').innerText = (teacher.updated_at || '').substr(0, 10);
                    tr_admin_list.querySelector('.teach_seq').value = teacher.id;
                    tr_admin_list.querySelector('.group_seq').value = teacher.group_seq;
                    tr_admin_list.querySelector('.first_page').value = teacher.first_page;
                    systemadmin_tby_list.appendChild(tr_admin_list);
                }
            });
        }

        // 관리자 목록 리스트 초기화.
        function systemadminListClear() {
            const systemadmin_tby_list = document.querySelector('#systemadmin_tby_list');
            const copy_tr_admin_list = systemadmin_tby_list.querySelector('.copy_tr_admin_list');
            systemadmin_tby_list.innerHTML = '';
            systemadmin_tby_list.appendChild(copy_tr_admin_list);
        }
        // 관리자 관리 등록 / 수정 창 열기
        function systemadminEditOpen() {
            const systemadmin_div_edit = document.querySelector('#systemadmin_div_edit');
            systemadmin_div_edit.hidden = false;
        }

        // 관리자 관리 등록 / 수정 창 닫기
        function systemadminEditClose() {
            const systemadmin_div_edit = document.querySelector('#systemadmin_div_edit');
            systemadmin_div_edit.hidden = true;
            // 등록으로 글자 변경.
            // 삭제버튼 숨기기.
            const systemadmin_btn_edit_delete = document.getElementById('systemadmin_btn_edit_delete');
            const edit_str = systemadmin_div_edit.querySelector('.edit_str');
            edit_str.innerText = '등록';
            systemadmin_btn_edit_delete.hidden = true;

            // 등록 / 수정 정보 초기화
            systemadminEditClear();
        }

        // 등록 / 수정 정보 초기화
        function systemadminEditClear() {
            const systemadmin_div_edit = document.querySelector('#systemadmin_div_edit');
            const teach_id = systemadmin_div_edit.querySelector('.teach_id');
            const teach_name = systemadmin_div_edit.querySelector('.teach_name');
            const teach_pw1 = systemadmin_div_edit.querySelector('.teach_pw1');
            const teach_pw2 = systemadmin_div_edit.querySelector('.teach_pw2');
            const teach_phone = systemadmin_div_edit.querySelector('.teach_phone');
            const teach_phone2 = systemadmin_div_edit.querySelector('.teach_phone2');
            const teach_status = systemadmin_div_edit.querySelector('.teach_status');
            const group_name = systemadmin_div_edit.querySelector('.group_name');
            const group_seq = systemadmin_div_edit.querySelector('.group_seq');
            const teach_seq = systemadmin_div_edit.querySelector('#systemadmin_teach_seq');
            const first_page = systemadmin_div_edit.querySelector('.first_page');
            const btn_group_insert = systemadmin_div_edit.querySelector('.btn_group_insert');

            teach_id.disabled = false;
            teach_seq.value = '';
            teach_id.value = '';
            teach_name.value = '';
            teach_pw1.value = '';
            teach_pw2.value = '';
            teach_phone.value = '';
            teach_phone2.value = '';
            teach_status.value = '';

            group_name.value = '';
            group_name.classList.remove('text-primary');
            btn_group_insert.disabled = false;
            group_seq.value = '';

            first_page.innerHTML = '<option value="">먼저그룹선택</option>';
        }

        // 관리자 관리 등록 / 수정 창 수정 모드
        function systemadminEditModify(vthis) {
            //const seq
            const systemadmin_btn_edit_save = document.getElementById('systemadmin_btn_edit_save');
            const systemadmin_btn_edit_delete = document.getElementById('systemadmin_btn_edit_delete');
            const systemadmin_div_edit = document.querySelector('#systemadmin_div_edit');
            const edit_str = systemadmin_div_edit.querySelector('.edit_str');
            systemadmin_btn_edit_save.innerText = '수정';
            systemadmin_btn_edit_delete.hidden = false;
            edit_str.innerText = '수정';

            //아이디 비활성화
            const teach_id = systemadmin_div_edit.querySelector('.teach_id');
            teach_id.disabled = true;

            // 관리자 관리 등록 / 수정 창 정보 넣기.
            systemadminEditInfo(vthis);
            // 관리자 관리 등록 / 수정 창 열기.
            systemadminEditOpen();
        }

        // 관리자 관리 등록 / 수정 창 정보 넣기.
        function systemadminEditInfo(vthis) {
            const tag_tr = vthis.closest('tr');
            const teach_seq = tag_tr.querySelector('.teach_seq').value;
            const teach_id = tag_tr.querySelector('.teach_id').innerText;
            const teach_name = tag_tr.querySelector('.teach_name').innerText;
            const teach_phone = tag_tr.querySelector('.teach_phone').innerText;
            const teach_phone2 = tag_tr.querySelector('.teach_phone2').innerText;
            const teach_status = tag_tr.querySelector('.teach_status').innerText;
            const group_name = tag_tr.querySelector('.group_name').innerText;
            const group_seq = tag_tr.querySelector('.group_seq').value;
            const menu_seq = tag_tr.querySelector('.first_page').value;
            //[추가 코드]
            //권한관리
            //첫페이지

            const systemadmin_div_edit = document.querySelector('#systemadmin_div_edit');
            systemadmin_div_edit.querySelector('#systemadmin_teach_seq').value = teach_seq;
            systemadmin_div_edit.querySelector('.teach_id').value = teach_id;
            systemadmin_div_edit.querySelector('.teach_name').value = teach_name;
            systemadmin_div_edit.querySelector('.teach_phone').value = teach_phone;
            systemadmin_div_edit.querySelector('.teach_phone2').value = teach_phone2;
            // 추후 teach_status 값이 더 있을 경우 수정 필요.[추가 코드]
            systemadmin_div_edit.querySelector('.teach_status').value = teach_status == 'Y' ? "Y" : "N";
            systemadmin_div_edit.querySelector('.group_name').value = group_name;
            systemadmin_div_edit.querySelector('.group_seq').value = group_seq;

            if ((group_seq || '') != '') {
                //등록 비활성화
                const btn_group_insert = systemadmin_div_edit.querySelector('.btn_group_insert');
                btn_group_insert.disabled = true;
                const tag_group_name = systemadmin_div_edit.querySelector('.group_name');
                tag_group_name.classList.add('text-primary');
                //첫페이지 가져오기.
                systemadminGetMenuList(menu_seq);
            }
            //[추가 코드]
            //권한관리
            //첫페이지
        }

        // 관리자 관리 등록 / 수정 창 저장
        function systemadminSave() {
            const systemadmin_div_edit = document.querySelector('#systemadmin_div_edit');
            const teach_seq = systemadmin_div_edit.querySelector('#systemadmin_teach_seq').value;
            const teach_id = systemadmin_div_edit.querySelector('.teach_id').value;
            const teach_name = systemadmin_div_edit.querySelector('.teach_name').value;
            const teach_pw1 = systemadmin_div_edit.querySelector('.teach_pw1').value;
            const teach_pw2 = systemadmin_div_edit.querySelector('.teach_pw2').value;
            const teach_phone = systemadmin_div_edit.querySelector('.teach_phone').value;
            const teach_phone2 = systemadmin_div_edit.querySelector('.teach_phone2').value;
            const teach_status = systemadmin_div_edit.querySelector('.teach_status').value;
            const group_name = systemadmin_div_edit.querySelector('.group_name').value;
            const group_seq = systemadmin_div_edit.querySelector('.group_seq').value;
            const first_page = systemadmin_div_edit.querySelector('.first_page').value;


            //[추가 코드]
            //권한 관리
            //첫페이지

            const page = '/manage/systemadmin/insert'
            const parameter = {
                teach_seq: teach_seq,
                teach_id: teach_id,
                teach_name: teach_name,
                teach_pw1: teach_pw1,
                teach_pw2: teach_pw2,
                teach_phone: teach_phone,
                teach_phone2: teach_phone2,
                teach_status: teach_status,
                group_seq: group_seq,
                group_name: group_name,
                first_page: first_page
            };
            //필수 정보 공백 체크.
            const is_chk = systemadminIdChk(parameter);
            if (!is_chk) return;

            //관리자 관리 등록 / 수정 창 저장
            queryFetch(page, parameter, function(result) {
                if (result == null || result.resultCode == null) {
                    sAlert('', '저장에 실패하였습니다.');
                    return;
                }
                if (result.resultCode == 'success') {
                    //등록시 저장되었습니다.
                    //수정시 수정되었습니다.
                    let msg = '';
                    if (teach_seq == '') {
                        msg = '저장되었습니다.';
                    } else {
                        msg = '수정되었습니다.';
                    }
                    sAlert('', msg);
                    systemadminList();
                    systemadminEditClose();

                } else {
                    sAlert('저장실패', result.resultMsg);
                    systemadminList();
                }
            });
        }

        // 관리자 관리 등록 / 수정 창 삭제
        function systemadminDelete() {
            const systemadmin_div_edit = document.querySelector('#systemadmin_div_edit');
            const teach_seq = systemadmin_div_edit.querySelector('#systemadmin_teach_seq').value;
            sAlert('삭제', '정말로 해당 관리자를 삭제진행하시겠습니까?', 2, function() {
                const page = '/manage/systemadmin/delete'
                const parameter = {
                    teach_seq: teach_seq
                };
                //관리자 관리 등록 / 수정 창 삭제
                queryFetch(page, parameter, function(result) {
                    if (result == null || result.resultCode == null) {
                        sAlert('', '삭제에 실패하였습니다.');
                        return;
                    }
                    if (result.resultCode == 'success') {
                        //삭제되었습니다.
                        sAlert('', '삭제되었습니다.');
                        systemadminList();
                        systemadminEditClose();
                    } else {
                        sAlert('삭제실패', result.resultMsg);
                        systemadminList();
                    }
                });
            });
        }

        //저장 / 수정시 필수 정보 공백 체크.
        function systemadminIdChk(parameter) {

            if (parameter.teach_id == '') {
                sAlert('', '관리자ID를 입력해주세요.');
                return false;
            }
            //teach_name 공백 체크
            if (parameter.teach_name == '') {
                sAlert('', '관리자명을 입력해주세요.');
                return false;
            }
            //teach_pw1 공백 체크 // 수정이 아닐때만 체크
            if (parameter.teach_pw1 == '' && parameter.teach_seq == '') {
                sAlert('', '비밀번호를 입력해주세요.');
                return false;
            }
            //teach_pw2 공백 체크 // 수정이 아닐때만 체크
            if (parameter.teach_pw2 == '' && parameter.teach_seq == '') {
                sAlert('', '비밀번호 확인을 입력해주세요.');
                return false;
            }
            //teach_pw1, teach_pw2 비밀번호 일치 체크 // 수정이 아닐때만 체크
            if (parameter.teach_pw1 != parameter.teach_pw2 && parameter.teach_seq == '') {
                sAlert('', '비밀번호가 일치하지 않습니다.');
                return false;
            }
            //사용여부 공백 체크
            if (parameter.teach_status == '') {
                sAlert('', '사용여부를 선택해주세요.');
                return false;
            }
            return true;
        }

        //관리자그룹 글자 수정시
        function systemadminClearGroup(vthis) {
            const systemadmin_div_edit = document.querySelector('#systemadmin_div_edit');
            const tag_group_seq = systemadmin_div_edit.querySelector('.group_seq');
            vthis.classList.remove('text-primary');
            tag_group_seq.value = '';
            //첫페이지 초기화
            const first_page = systemadmin_div_edit.querySelector('.first_page');
            first_page.innerHTML = '<option value="">먼저그룹선택</option>';
            //그룹 등록 버튼 활성화
            const btn_group_insert = systemadmin_div_edit.querySelector('.btn_group_insert');
            btn_group_insert.disabled = false;
        }

        //수정 / 저장시 관리자 그룹 클릭시
        function systemadminClickGroup(vthis) {
            const systemadmin_div_edit = document.querySelector('#systemadmin_div_edit');
            const group_seq = vthis.getAttribute('group_seq');
            const group_name = vthis.innerText;
            const group_name_input = systemadmin_div_edit.querySelector('.group_name');
            const group_seq_input = systemadmin_div_edit.querySelector('.group_seq');
            group_name_input.value = group_name;
            group_seq_input.value = group_seq;
            if (group_seq != '') {
                const tag_group_name = systemadmin_div_edit.querySelector('.group_name');
                tag_group_name.classList.add('text-primary');
                systemadminGetMenuList();

                //그룹 등록 버튼 비활성화
                const btn_group_insert = systemadmin_div_edit.querySelector('.btn_group_insert');
                btn_group_insert.disabled = true;
            } else {
                const first_page = systemadmin_div_edit.querySelector('.first_page');
                first_page.innerHTML = '<option value="">먼저그룹선택</option>';
                //그룹 등록 버튼 활성화
                const btn_group_insert = systemadmin_div_edit.querySelector('.btn_group_insert');
                btn_group_insert.disabled = false;
            }
        }

        //그룹관리 선택 > 그룹에 속한 페이지 정보 가져오기 > 첫페이지 select option에 넣기.
        function systemadminGetMenuList(menu_seq) {
            const systemadmin_div_edit = document.querySelector('#systemadmin_div_edit');
            const group_seq = systemadmin_div_edit.querySelector('.group_seq').value;
            const page = '/manage/systemadmin/menu/select';
            const parameter = {
                group_seq: group_seq
            };
            queryFetch(page, parameter, function(result) {
                if (result == null || result.resultCode == null) {
                    sAlert('', '조회에 실패하였습니다.');
                    return;
                }
                // 첫페이지 select option 초기화.
                const first_page = systemadmin_div_edit.querySelector('.first_page');
                first_page.innerHTML = '<option value="">먼저그룹선택</option>';

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

        //관리자 그룹 등록
        function systemadminGroupSave() {
            const systemadmin_div_edit = document.querySelector('#systemadmin_div_edit');
            const group_name = systemadmin_div_edit.querySelector('.group_name').value;

            //그룹명 공백 체크
            if (group_name == '') {
                sAlert('', '관리자그룹명을 입력해주세요.');
                return false;
            }

            const page = '/manage/systemadmin/groupinsert';
            const parameter = {
                group_name: group_name
            };
            queryFetch(page, parameter, function(result) {
                if (result == null || result.resultCode == null) {
                    sAlert('', '저장에 실패하였습니다.');
                    return;
                }
                if (result.resultCode == 'success') {
                    let msg = '저장되었습니다.';
                    sAlert('', msg);

                    // 그룹 선택으로 변경.
                    const group_seq = result.group_seq;
                    systemadmin_div_edit.querySelector('.group_seq').value = group_seq;
                    systemadmin_div_edit.querySelector('.group_name').classList.add('text-primary');
                    const btn_group_insert = systemadmin_div_edit.querySelector('.btn_group_insert');
                    btn_group_insert.disabled = true;

                    // 관리자 그룹 가져오기.
                    systemadminGetUserGroup();
                } else {
                    sAlert('저장실패', result.resultMsg);
                }
            });
        }

        //유저그룹 가져오기.
        function systemadminGetUserGroup() {
            const systemadmin_div_edit = document.querySelector('#systemadmin_div_edit');
            const page = '/manage/systemadmin/usergroup';
            const parameter = {};
            queryFetch(page, parameter, function(result) {
                if (result == null || result.resultCode == null) {
                    return;
                }
                // 첫페이지 select option 초기화.
                const dropdown_menu = systemadmin_div_edit.querySelector('.dropdown-menu');
                dropdown_menu.innerHTML = '';

                // 첫페이지 select option 넣기.
                for (let i = 0; i < result.user_groups.length; i++) {
                    const user_group = result.user_groups[i];
                    const a = document.createElement('a');
                    a.classList.add('dropdown-item');
                    a.setAttribute('group_seq', user_group.id);
                    a.innerText = user_group.group_name;
                    a.onclick = function() {
                        systemadminClickGroup(this);
                    }
                    dropdown_menu.appendChild(a);
                }
            });
        }

        // 메뉴 권한 설정
        function systemadminMenuAuth() {
            const systemadmin_div_edit = document.querySelector('#systemadmin_div_edit');
            //등록이 활성화이면 관리자 그룹 선택 요청 후 리턴
            const btn_group_insert = systemadmin_div_edit.querySelector('.btn_group_insert');
            if (btn_group_insert.disabled == false) {
                sAlert('', '관리자 그룹을 먼저 선택해주세요.');
                return;
            }
            const group_seq = systemadmin_div_edit.querySelector('.group_seq').value;
            systemadminMenuOpen(group_seq)
        }

        // 메뉴 권한 설정 창 닫기
        function systemadminMenuClose(){
            const systeamadmin_div_menu_auth = document.querySelector('#systeamadmin_div_menu_auth');
            systeamadmin_div_menu_auth.classList.remove('d-flex');
        }

        // 메뉴 권한 설정 창 열기
        function systemadminMenuOpen(group_seq){
            // 관리자 그룹 선택 후 메뉴 권한 설정 창 열기.
            const menu_sel_group_seq = document.querySelector('#menu_sel_group_seq');
            menu_sel_group_seq.value = group_seq;
            menu_sel_group_seq.onchange();

            const btn_group_name = document.querySelector('#v-pills-current-tab .sp_text');
            btn_group_name.innerText = menu_sel_group_seq.options[menu_sel_group_seq.selectedIndex].text;

            //
            const systeamadmin_div_menu_auth = document.querySelector('#systeamadmin_div_menu_auth');
            systeamadmin_div_menu_auth.classList.add('d-flex');
        }
    </script>
@endsection
