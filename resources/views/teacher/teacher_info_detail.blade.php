@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
    선생님 정보 상세
@endsection

{{-- 컨텐트 --}}
@section('layout_coutent')
    <div class="row pt-2" data-div-main="user_add">
        <input type="hidden" data-main-teach-seq value="{{ $teacher->id }}">
        <input type="hidden" data-main-team-code value="{{ $teacher->team_code }}">
        <div class="sub-title d-flex justify-content-between">
            <h2 class="text-sb-42px">
                <button data-btn-back-page="" class="btn p-0 row mx-0 all-center" onclick="teachInfoBack();">
                    <img src="{{ asset('images/black_arrow_left_tail.svg') }}" width="52" class="px-0">
                </button>
                <span class="me-2">선생님 정보 상세</span>
            </h2>
        </div>

        <div style="border-top: solid 2px #222;" class="w-100"></div>
        <table data-tb="0" class="w-100 table-list-style table-border-xless table-h-92" style="border-top:0px;">
            <colgroup>
                <col style="width: 15%;">
                <col style="width: 35%;">
                <col style="width: 15%;">
                <col style="width: 35%;">
            </colgroup>
            <tbody>
                <tr class="text-start">
                    <td class="text-start ps-4 scale-text-gray_06">이름 </td>
                    <td class="text-start px-4 py-4">
                        <div data-user-name data-text="{{ $teacher->teach_name }}"
                            class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3 is_content">
                            {{-- border --}}
                            {{ $teacher->teach_name }}
                        </div>
                    </td>
                    <td class="text-start ps-4 scale-text-gray_06">사용자그룹</td>
                    <td class="text-start px-4">
                        <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                            <select data-group-seq data-value="{{ $teacher->group_seq }}"
                                class="border-none lg-select rounded-0 text-sb-24px p-0 w-100" disabled>
                                <option value="">그룹을 선택해주세요.</option>
                                @if (!empty($groups))
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}" data-group-type="{{ $group->group_type }}"
                                            {{ $teacher->group_seq == $group->id ? 'selected' : '' }}>
                                            {{ $group->group_name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0"
                                alt="" width="32" height="32">
                        </div>
                    </td>
                </tr>
                <tr class="text-start">
                    <td class="text-start ps-4 scale-text-gray_06">소속</td>
                    <td class="text-start px-4">
                        <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                            <select data-select="region" data-value="{{ $teacher->region_seq }}"
                                onchange="teachUserAddSelectChange(this, 'region')"
                                class="border-none lg-select rounded-0 text-sb-24px p-0 w-100" disabled>
                                @if ($group_type2 == 'general')
                                    @if (!empty($regions))
                                        @foreach ($regions as $region)
                                            <option value="{{ $region->id }}"
                                                {{ $teacher->region_seq == $region->id ? 'selected' : '' }}>
                                                {{ $region->region_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                @else
                                    @if (!empty($teach_region))
                                        <option value="{{ $teach_region->id }}"
                                            {{ $teacher->region_seq == $teach_region->id ? 'selected' : '' }}>
                                            {{ $teach_region->region_name }}
                                        </option>
                                    @endif
                                @endif
                            </select>
                            {{-- <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    document.querySelector('[data-select="region"]').onchange();
                                });
                            </script> --}}
                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0"
                                alt="" width="32" height="32">
                        </div>
                    </td>
                    <td class="text-start ps-4 scale-text-gray_06">팀</td>
                    <td class="text-start px-4">
                        <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                            <select data-select="team" data-value="{{ $teacher->team_code }}"
                                class="border-none lg-select rounded-0 text-sb-24px p-0 w-100" disabled>
                                <option value="">미배정</option>
                                @if ($group_type2 == 'general')
                                    @if (!empty($teams))
                                        @foreach ($teams as $team)
                                            <option value="{{ $team->team_code }}"
                                                {{ $teacher->team_code == $team->team_code ? 'selected' : '' }}>
                                                {{ $team->team_name }}</option>
                                        @endforeach
                                    @endif
                                @else
                                    @if (!empty($team_code))
                                        <option value="{{ $team_code }}">{{ $team_name }}</option>
                                    @endif
                                @endif
                            </select>
                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0"
                                alt="" width="32" height="32">
                        </div>
                    </td>
                </tr>
                <tr class="text-start">
                    {{-- 아이디 --}}
                    <td class="text-start ps-4 scale-text-gray_06">아이디 </td>
                    <td class="text-start px-4 py-4">
                        <div data-user-id data-text="{{ $teacher->teach_id }}"
                            class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3">
                            {{-- border --}}
                            {{ $teacher->teach_id }}
                        </div>
                    </td>
                    {{-- 휴대전화 --}}
                    <td class="text-start text-start ps-4 scale-text-gray_06">휴대전화</td>
                    <td class="text-start text-start ps-4">
                        <div data-user-phone data-text="{{ $teacher->teach_phone }}"
                            class="d-flex align-items-center scale-text-gray_05 is_content">
                            {{ $teacher->teach_phone }}
                        </div>
                    </td>
                </tr>
                <tr class="text-start">
                    {{-- 이메일 --}}
                    <td class="text-start text-start ps-4 scale-text-gray_06">이메일</td>
                    <td class="text-start text-start ps-4" colspan="3">
                        <div data-user-email data-text="{{ $teacher->teach_email }}"
                            class="d-flex align-items-center scale-text-gray_05 is_content">
                            {{ $teacher->teach_email }}
                        </div>
                    </td>
                </tr>
                <tr class="text-start">
                    {{-- 자택주소 --}}
                    <td class="text-start text-start ps-4 scale-text-gray_06">자택주소</td>
                    <td data-user-address data-text="{{ $teacher->teach_address }}"
                        class="text-start text-start ps-4 scale-text-gray_05 is_content" colspan="3">
                        {{ $teacher->teach_address }}
                    </td>
                </tr>
                <tr class="text-start">
                    <td colspan="4" class="text-end p-4">
                        <div class="d-flex justify-content-between">
                            {{-- 수정내역 확인 --}}
                            <button type="button" onclick="teachManUserHistoryModal();" data-btn-edit="chkupdate"
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black scale-text-gray_05">수정내역확인</button>

                            <div class="d-flex gap-2">

                                <button type="button" onclick="teachInfoUpdateToggle(true);" data-btn-edit="edit"
                                    class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black">수정하기</button>

                                <button type="button" onclick="teachInfoUpdateToggle(false);" data-btn-edit="cancel"
                                    hidden
                                    class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black scale-text-gray_05">취소하기</button>

                                <button type="button" onclick="teachInfoUpdate()" data-btn-edit="save" hidden
                                    class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black">저장하기</button>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="scale-bg-gray_01 rounded px-52 py-32 d-flex flex-column row-gap-3 text-sb-24px mt-52 mb-52">
            <label class="checkbox d-flex align-items-center">
                <input type="checkbox" class="" disabled>
                <span class=""></span>
                <p class="ms-2">개인정보 수집 및 이용 동의여부</p>
                <p class="ms-1 scale-text-gray_05">(동의일자 : 2023.01.01)</p>
            </label>
            <label class="checkbox d-flex align-items-center">
                <input type="checkbox" class="" disabled>
                <span class=""></span>
                <p class="ms-2">이용약관 동의여부</p>
                <p class="ms-1 scale-text-gray_05">(동의일자 : 2023.01.01)</p>
            </label>
            <label class="checkbox d-flex align-items-center">
                <input type="checkbox" class="" disabled>
                <span class=""></span>
                <p class="ms-2">제3자 정보제공 동의여부</p>
                <p class="ms-1 scale-text-gray_05">(동의일자 : 2023.01.01)</p>
            </label>
            <label class="checkbox d-flex align-items-center">
                <input type="checkbox" class="" disabled>
                <span class=""></span>
                <p class="ms-2">마케팅 등 수집이용 동의여부</p>
                <p class="ms-1 scale-text-gray_05">(동의일자 : 2023.01.01)</p>
            </label>
        </div>

        <p class="text-b-28px mt-80 mb-4">계약정보</p>
        <table class="w-100 table-list-style table-border-xless table-h-92">
            <colgroup>
                <col style="width: 25%;">
                <col style="width: 75%;">
            </colgroup>
            <thead></thead>
            <tbody>
                <tr class="text-start">
                    <td class="text-start ps-4">계약 유무</td>
                    <td class="text-start px-4 scale-text-black">계약 완료 / 유효 상태</td>
                </tr>
                <tr class="text-start">
                    <td class="text-start ps-4">계약명</td>
                    <td class="text-start px-4 scale-text-black">(주)팝콘에듀-교육관리교사위탁용역계약</td>
                </tr>
                <tr class="text-start">
                    <td class="text-start ps-4 align-top">계약채결일자</td>
                    <td class="text-start px-4 scale-text-black">
                        2023.01.01 17:23:58
                    </td>
                </tr>
                <tr class="text-start">
                    <td class="text-start ps-4 align-top">계약만료일자</td>
                    <td class="text-start px-4 scale-text-black">2023.12.31</td>
                </tr>
                <tr class="text-start">
                    <td class="text-start ps-4 align-top">약정형태</td>
                    <td class="text-start px-4 scale-text-gray_05">
                        비대면 전자계약
                    </td>
                </tr>
                <tr class="text-start">
                    <td class="text-start ps-4 align-top">약정형태</td>
                    <td class="text-start px-4 ">
                        <ul>
                            <li class="scale-text-gray_05 h-center gap-2">
                                <img src="{{ asset('images/link_icon.svg') }}" width="32">
                                주민등록증 사본(김선생) 1부
                            </li>
                            <li class="scale-text-gray_05 h-center gap-2">
                                <img src="{{ asset('images/link_icon.svg') }}" width="32">
                                통장사본 (김선생 토스뱅크) 1부
                            </li>
                            <li class="scale-text-gray_05 h-center gap-2">
                                <img src="{{ asset('images/link_icon.svg') }}" width="32">
                                범죄경력조회 회보서 (김선생 경찰청) 1부
                            </li>
                            <li class="scale-text-gray_05 h-center gap-2">
                                <img src="{{ asset('images/link_icon.svg') }}" width="32">
                                전자계약서(약관+수수료동의)
                            </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>



        {{-- 160px --}}
        <div>
            <div class="py-lg-5"></div>
            <div class="py-lg-4"></div>
            <div class="pt-lg-3"></div>
        </div>

        {{-- 모달 / 수정 내역 리스트 --}}
        <div class="modal fade" id="teach_info_modal_edit_history" tabindex="-1" aria-hidden="true"
            style="display: none;">
            <div class="modal-dialog modal-shadow-style rounded" style="max-width: 593px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5 text-b-24px h-center" id="">
                            <img src="{{ asset('images/edit_list_icon.svg') }}" width="32">
                            수정내역 상세
                        </h1>
                        <button type="button" class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close"
                            style="width:32px;height:32px"></button>
                    </div>
                    <div class="modal-body">
                        <div class="overflow-auto tableFixedHead" style="height: auto;">
                            <table class="table">
                                {{-- 굳이 id할 필요 없을듯 modal 안에 .으로 가져오기. --}}
                                <thead class="thd_edit_history">
                                </thead>
                                <tbody class="tby_edit_history">
                                    <tr class="copy_tr_edit_history" hidden>
                                        <td class="border-bottom-0">
                                            <div>
                                                <p class="text-sb-20px mb-3 title_str">수정 내역 입력</p>
                                                <table class="w-100 table-list-style table-border-xless mb-3">
                                                    <colgroup>
                                                        <col style="width: 15%;">
                                                        <col style="width: 35%;">
                                                    </colgroup>
                                                    <thead></thead>
                                                    <tbody>
                                                        <tr class="text-start h-80">
                                                            <td class="text-start ps-4 scale-text-gray_06">
                                                                <p class="text-sb-20px">수정 날짜</p>
                                                            </td>
                                                            <td colspan="3" class="text-start">
                                                                <p class="text-sb-20px ps-4 created_at"></p>
                                                            </td>
                                                        </tr>
                                                        <tr class="text-start h-80">
                                                            <td class="text-start ps-4 scale-text-gray_06">
                                                                <p class="text-sb-20px">수정 구분</p>
                                                            </td>
                                                            <td colspan="3" class="text-start">
                                                                <p class="text-sb-20px ps-4 scale-text-black log_subject">
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr class="text-start h-80">
                                                            <td class="text-start ps-4 scale-text-gray_06">
                                                                <p class="text-sb-20px">수정 내역</p>
                                                            </td>
                                                            <td colspan="3" class="text-start ">
                                                                <p
                                                                    class="text-sb-20px ps-4 align-items-center log_content flex-wrap py-3">
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr class="text-start h-80">
                                                            <td class="text-start ps-4 scale-text-gray_06">
                                                                <p class="text-sb-20px">사유</p>
                                                            </td>
                                                            <td colspan="3" class="text-start">
                                                                <p class="text-sb-20px ps-4 row mx-0 align-items-center log_remark"
                                                                    onclick="teachInfoLogRemarkEdit(this);"> </p>
                                                                <div>
                                                                    <textarea class="txt_log_remark" cols="30" rows="4" hidden></textarea>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                        <input type="hidden" class="log_seq">
                                    </tr>
                                </tbody>
                            </table>
                            <div class="text-center none_edit_history mb-3" hidden>
                                <span>수정 내역이 없습니다.</span>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="teachInfoLogRemarkUpdate(this)"
                        class="btn-lg-primary text-b-24px rounded scale-text-white w-100 justify-content-center">
                            <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden></span>
                            저장하기</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function teachInfoBack() {
            sessionStorage.setItem('isBackNavigation', 'true');
            window.history.back();
        }

        function teachManUserHistoryModal() {
            teachMantModalEditHistoryClear();
            const user_type = 'teacher';
            const user_key = document.querySelector('[data-main-teach-seq]').value;

            const page = "/manage/log/select";
            const parameter = {
                select_type: user_type,
                select_seq: user_key,
                max_count: 10
            };
            const modal = document.querySelector('#teach_info_modal_edit_history');

            // 로딩 시작
            queryFetch(page, parameter, function(result) {
                // 로딩 끝
                modal.querySelector('.sp_loding').hidden = true;
                if (result.resultCode == 'success') {
                    //초기화
                    const thd_edit_history = modal.querySelector('.thd_edit_history');
                    const tby_edit_history = modal.querySelector('.tby_edit_history');
                    const copy_tr = tby_edit_history.querySelector('.copy_tr_edit_history').cloneNode(true);
                    tby_edit_history.innerHTML = '';
                    thd_edit_history.innerHTML = '';
                    tby_edit_history.appendChild(copy_tr);
                    copy_tr.hidden = true;

                    // 내역 리스트
                    for (let i = 0; i < result.logs.length; i++) {
                        const log = result.logs[i];
                        const tr = copy_tr.cloneNode(true);
                        tr.classList.remove('copy_tr_edit_history');
                        tr.classList.add('tr_edit_history');
                        tr.hidden = false;

                        tr.querySelector('.log_content').innerHTML =
                            (log.log_content||'').trim().replace(/->/gi,
                                '<i class="icon-arrow-right icon-size scale-text-gray_04 mx-2 align-middle"></i>').replace(
                                /\n/gi, '<br>');
                        tr.querySelector('.created_at').innerText = (log.created_at || '').replace(/-/gi, '.')
                            .substr(0, 16);
                        tr.querySelector('.log_subject').innerText = log.log_subject || '';
                        tr.querySelector('.log_remark').innerText = log.log_remark || '사유를 입력해주세요.';
                        tr.querySelector('.txt_log_remark').value = log.log_remark || '';
                        tr.querySelector('.log_seq').value = log.id;
                        tr.querySelector('.title_str').hidden = true;
                        
                        if((log.log_remark || '') == ''){
                            tr.querySelector('.title_str').innerText = '수정 내역 입력';
                            thd_edit_history.appendChild(tr);
                        }
                        else{
                            tr.querySelector('.title_str').innerText = '상세 내역';
                            tr.querySelector('table').classList.add('scale-bg-gray_01');
                            tr.querySelector('.log_remark').removeAttribute('onclick');
                            tby_edit_history.appendChild(tr);
                        }   
                    }
                    // thd_edit_history, tby_edit_history 의 첫 tr 각각 .title_str hidden = false;
                    thd_edit_history.querySelector('.tr_edit_history .title_str').hidden = false;
                    tby_edit_history.querySelector('.tr_edit_history .title_str').hidden = false;


                }
                // tr_edit_history 없으면 내역 없음 표시
                if (modal.querySelectorAll('.tr_edit_history').length == 0) {
                    const btm = modal.querySelector('.none_edit_history');
                    btm.hidden = false;
                } else {
                    const btm = modal.querySelector('.none_edit_history');
                    btm.hidden = true;
                }
            });

            //모달 열기
            const myModal = new bootstrap.Modal(document.getElementById('teach_info_modal_edit_history'), {});
            myModal.show();
        }

        // 수정내역 모달 비우기.
        function teachMantModalEditHistoryClear() {
            const modal = document.querySelector('#teach_info_modal_edit_history');
            const tby_edit_history = modal.querySelector('.tby_edit_history');
            const copy_tr = tby_edit_history.querySelector('.copy_tr_edit_history').cloneNode(true);
            tby_edit_history.innerHTML = '';
            tby_edit_history.appendChild(copy_tr);
            copy_tr.hidden = true;
            //내역 없음 숨김.
            const btm = modal.querySelector('.none_edit_history');
            btm.hidden = false;

        }

        // 수정하기 버튼 클릭
        function teachInfoUpdateToggle(is_bool, type) {
            const btn_update = document.querySelector('[data-btn-edit="edit"]');
            const btn_cancel = document.querySelector('[data-btn-edit="cancel"]');
            const btn_save = document.querySelector('[data-btn-edit="save"]');

            if (is_bool) {
                // 수정하기 버튼 숨김, 취소학, 저장하기 버튼 보이기.
                btn_update.hidden = true;
                btn_cancel.hidden = false;
                btn_save.hidden = false;
            } else {
                // 수정하기 버튼 보이기, 취소학, 저장하기 버튼 숨김.
                btn_update.hidden = false;
                btn_cancel.hidden = true;
                btn_save.hidden = true;
            }
            if(type != 'is_update')
                teachInfoUpdateTagToggle(is_bool);
            else{
                teachInfoDataSetting();
            }
        }

        // 뒤로가기를 위해서 data-text, data-value 를 변경.
        function teachInfoDataSetting(){
            // 현재의 값을 data-text, data-value로 변경.
            const tb = document.querySelector('[data-tb]');
            const divs = tb.querySelectorAll('.is_content');
            for (let i = 0; i < divs.length; i++) {
                const div = divs[i];
                const data_text = div.innerText;
                div.setAttribute('data-text', data_text);
            }
            const selects = tb.querySelectorAll('select');
            for (let i = 0; i < selects.length; i++) {
                const select = selects[i];
                const data_value = select.value;
                select.setAttribute('data-value', data_value);
            }
        }

        // 수정하기 버튼 클릭시 > div, select 변환
        let before_option = {};

        function teachInfoUpdateTagToggle(is_bool) {
            const tb = document.querySelector('[data-tb]');
            //tb 안에 있는 div 중 .is_content is_content 에 따라서 contenteditable 속성 추가.
            const divs = tb.querySelectorAll('.is_content');
            for (let i = 0; i < divs.length; i++) {
                const div = divs[i];
                if (is_bool) {
                    div.setAttribute('contenteditable', 'true');
                    div.classList.remove('scale-text-gray_05');
                    div.classList.add('scale-text-black');
                } else {
                    div.removeAttribute('contenteditable');
                    const data_text = div.getAttribute('data-text');
                    div.innerText = data_text;
                    div.classList.add('scale-text-gray_05');
                    div.classList.remove('scale-text-black');
                }
            }

            // tb SELECT 태그중 disabled 속성 제거.
            if (is_bool) {
                before_option = {};
            }
            const selects = tb.querySelectorAll('select');
            for (let i = 0; i < selects.length; i++) {
                const select = selects[i];
                if (is_bool) {
                    select.removeAttribute('disabled');
                    const sel_type = select.getAttribute('data-select');
                    before_option[sel_type] = select.selectedOptions[0];
                } else {
                    select.setAttribute('disabled', 'true');
                    const data_value = select.getAttribute('data-value');
                    const sel_type = select.getAttribute('data-select');
                    select.value = data_value;
                    if (select.value == '') {
                        //없으므로 before_option에 있는 값으로 변경.
                        select.appendChild(before_option[sel_type]);
                        select.value = data_value;
                    }
                }
            }
            if (is_bool) {
                document.querySelector('[data-select="region"]').onchange();
            }
        }

        // 수정 저장하기 
        function teachInfoUpdate() {
            const teach_seq = document.querySelector('[data-main-teach-seq]').value;
            const teach_name = document.querySelector('[data-user-name]').innerText;
            const group_seq = document.querySelector('[data-group-seq]').value;
            const region_seq = document.querySelector('[data-select="region"]').value;
            const team_code = document.querySelector('[data-select="team"]').value;
            const teach_id = document.querySelector('[data-user-id]').innerText;
            const teach_phone = document.querySelector('[data-user-phone]').innerText;
            const teach_email = document.querySelector('[data-user-email]').innerText;
            const teach_address = document.querySelector('[data-user-address]').innerText;

            const page = "/manage/useradd/user/insert";
            const parameter = {
                user_key: teach_seq,
                user_id: teach_id,
                group_seq: group_seq,
                grouptype: 'teacher',
                region: region_seq,
                team_code: team_code,
                user_name: teach_name,
                user_phone: teach_phone,
                user_email: teach_email,
                user_addr: teach_address
            };
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    const msg =
                        `
                    <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
                    <p class="modal-title text-center text-sb-28px alert-top-m-20" id="">저장되었습니다.</p>
                    </div>
                    `;
                    sAlert('', msg, 4, function() {
                        // location.reload();
                        teachInfoUpdateToggle(false, 'is_update');
                    });
                } else {
                    toast('저장에 실패하였습니다. 다시 시도해주세요.');
                }
            });

        }

        //
        function teachUserAddSelectChange(vthis, type) {
            if (type == 'region') {
                const region_seq = vthis.value;
                teachUserAddTeamSelect(region_seq);
            }
        }

        // 본부 선택시 팀 SELECT
        function teachUserAddTeamSelect(region_seq) {
            const page = '/manage/useradd/team/select';
            const parameter = {
                region_seq: region_seq
            };
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    let select_team = document.querySelector('[data-select="team"]');
                    select_team.innerHTML = '';
                    const option = document.createElement('option');
                    option.value = '';
                    option.innerText = '미배정';
                    select_team.appendChild(option);
                    const teams = result.resultData;
                    teams.forEach(function(team) {
                        const option = document.createElement('option');
                        option.value = team.team_code;
                        option.innerText = team.team_name;
                        select_team.appendChild(option);
                    });
                    const team_code = document.querySelector('[data-main-team-code]').value;
                    select_team.value = team_code;
                    if (select_team.value == '')
                        select_team.value = ''
                }
            });
        }

        // 수정 내역 리스트 상세내역 수정
        function teachInfoLogRemarkEdit(vthis){
            if(vthis.hidden){
                vthis.hidden = false;
                vthis.closest('tr').querySelector('.txt_log_remark').hidden = true;
            }else{
                vthis.hidden = true;
                vthis.closest('tr').querySelector('.txt_log_remark').hidden = false;
            }
        }

        // 수정내역 확인 비고 저장.
        function teachInfoLogRemarkUpdate(vthis){
            const modal = document.querySelector('#teach_info_modal_edit_history');

            // hidden이 false인 textarea의 value를 가져와서 remark에 저장
            const txt_log_remark = modal.querySelectorAll('.txt_log_remark');
            let log_seqs = '';
            let log_remarks = '';
            txt_log_remark.forEach(function(el){
                if(!el.hidden){
                    if(log_seqs != '')
                        log_seqs += ',';
                    log_seqs += el.closest('.tr_edit_history').querySelector('.log_seq').value;
                    if(log_remarks != '')
                        log_remarks += ',';
                    log_remarks += el.value;
                }
            });

            const page = "/manage/log/remark/update";
            const parameter = {
                log_seqs:log_seqs,
                log_remarks:log_remarks
            };
            const msg =
            `
            <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
                <p class="modal-title text-center text-sb-28px alert-top-m-20" id="">열린 텍스트를 저장하시겠습니까?</p>
            </div>
            `;
            sAlert('', msg, 3, function(){
                //로딩 시작
                vthis.querySelector('.sp_loding').hidden = false;
                queryFetch(page, parameter, function(result){
                    // 로딩 끝
                    vthis.querySelector('.sp_loding').hidden = true;
                    if(result.resultCode == 'success'){
                        const msg =
                        `
                        <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4 mt-4">
                            <p class="modal-title text-center text-sb-28px alert-top-m-20" id="">저장되었습니다.</p>
                        </div>
                        `;
                        sAlert('', msg, 3);
                        //변경된 수치를 a에 넣어주기.
                        //log_seqs의 수치인 tr을 가져와서 a에 log_remarks를 넣어준다.
                        // 모달 닫기.
                        modal.querySelector('.btn-close').click();


                    }else{
                        sAlert('', '저장에 실패하였습니다.');
                    }
                });
            });
        }
    </script>
@endsection
