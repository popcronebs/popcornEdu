@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
    사용자 등록(개별)
@endsection

{{-- 컨텐트 --}}
@section('layout_coutent')
    <div class="row pt-2" data-div-main="user_add">
        <input type="hidden" data-main-group-type2 value={{ $group_type2 }}>
        <input type="hidden" data-main-user-type value={{ $user_type ?? '' }}>
        <input type="hidden" data-main-sido value="{{ $tarea_sido ?? ''}}">
        <input type="hidden" data-main-region-seq value="{{ $region_seq }}">
        <div class="sub-title d-flex justify-content-between">
            <h2 class="text-sb-42px">
                <button data-btn-back-page="" class="btn p-0 row mx-0 all-center" onclick="teachUserAddBack();">
                    <img src="https://sdang.acaunion.com/images/black_arrow_left_tail.svg" width="52" class="px-0">
                </button>
                <span class="me-2">사용자 등록(개별)</span>
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
            <thead>
            <tr class="text-start">
                    <td class="text-start ps-4 scale-text-gray_06">등록 정보</td>
                    <td colspan="3" class="text-start px-4">
                        <div class="h-center select-wrap py-0 w-100 position-relative justify-content-between">
                            <span class="scale-text-black">
                                <span data-idx-num="">1</span>번 사용자 등록.
                            </span>
                            <button data-btn-toggle-table class="btn p-0 m-0 h-center" onclick="teachUserAddOpenTbody(this);">
                                <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" width="32" height="32">
                            </button>
                        </div>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr class="text-start">
                    <td class="text-start ps-4 scale-text-gray_06">아이디</td>
                    <td colspan="3" class="text-start px-4 py-4">
                        <input data-user-id placeholder="임시 아이디를 입력해주세요."
                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3">
                    </td>
                    {{-- <td class="text-start ps-4 scale-text-gray_06">사용자그룹</td>
                    <td class="text-start px-4">
                        <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                            <select data-group-seq
                            class="border-none lg-select rounded-0 text-sb-24px p-0 w-100">
                                <option value="">그룹을 선택해주세요.</option>
                                @if (!empty($groups))
                                    @foreach ($groups as $group)
                                        <option value="{{ $group->id }}" data-group-type="{{ $group->group_type }}">{{ $group->group_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0"
                                alt="" width="32" height="32">
                        </div>
                    </td> --}}
                </tr>
                <tr class="text-start d-none">
                    <td class="text-start ps-4 scale-text-gray_06">지역</td>
                    <td colspan="3" class="text-start px-4">
                        <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                            <select data-area
                                class="border-none lg-select rounded-0 text-sb-24px p-0 w-100 scale-text-black">
                                <option value='' selected>지역선택</option>
                                @if (isset($address_sido) && count($address_sido) > 0)
                                    @foreach ($address_sido as $item)
                                        <option value="{{ $item['sido'] }}" {{ $tarea_sido == $item['sido'] ? 'selected' : ''}} >{{ $item['sido'] }}</option>
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
                            {{-- <select data-select="region" onchange="teachUserAddSelectChange(this, 'region')"
                                class="border-none lg-select rounded-0 text-sb-24px scale-text-black p-0 w-100">
                                @if ($group_type2 == 'general')
                                    <option value="">소속을 선택해주세요.</option>
                                @endif
                                @if (!empty($regions))
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}" {{ $region_seq == $region->id ? 'selected' : '' }}>{{ $region->region_name }}</option>
                                    @endforeach
                                @endif
                            </select> --}}

                            <select data-select="region" class="border-none lg-select rounded-0 text-sb-24px scale-text-black p-0 w-100" {{ $team_name ? 'disabled' : '' }}>
                                @if ($group_type2 == 'general')
                                    <option value="">소속을 선택해주세요.</option>
                                @endif
                                @if (!empty($team_group))
                                    <option value="{{ $team_group->id }}">{{ $team_group->area }}</option>
                                @endif
                            </select>
                            <script>
                                document.addEventListener('DOMContentLoaded', function(){
                                    //document.querySelector('[data-select="region"]')?.onchange();
                                });
                             </script>
                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0"
                                alt="" width="32" height="32">
                        </div>
                    </td>
                    <td class="text-start ps-4 scale-text-gray_06">팀</td>
                    <td class="text-start px-4">
                        <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                            <select data-select="team" class="border-none lg-select rounded-0 text-sb-24px scale-text-black p-0 w-100" {{ $team_name ? 'disabled' : '' }}>
                                @if (!empty($team_code))
                                    <option value="{{ $team_code }}" selected>{{ $team_name }}</option>
                                @else
                                    <option value="">미배정</option>
                                @endif
                            </select>
                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0"
                                alt="" width="32" height="32">
                        </div>
                    </td>
                </tr>
                <tr class="text-start">
                    <td class="text-start ps-4 scale-text-gray_06">학교</td>
                    <td class="text-start p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="scale-text-gray_05">회원가입 시 입력</div>
                            <div>

                            </div>
                        </div>
                    </td>
                    <td class="text-start ps-4 scale-text-gray_06">학년</td>
                    <td class="text-start p-4 scale-text-gray_05">회원가입 시 입력</td>
                </tr>
                <tr class="text-start">
                    <td class="text-start ps-4 scale-text-gray_06">이용권</td>
                    <td class="text-start px-4">
                        <div class="d-inline-block select-wrap py-0 w-100 d-flex scale-text-gray_05">
                            미등록
                        </div>
                    </td>
                    <td class="text-start ps-4 scale-text-gray_06">이용기간</td>
                    <td class="text-start px-4">
                        <div class="d-flex align-items-center scale-text-gray_05">미등록</div>
                    </td>
                </tr>
                <tr class="text-start">
                    <td class="text-start ps-4 scale-text-gray_06">비밀번호</td>
                    <td class="text-start px-4 py-4">

                        <input data-user-pw="1"
                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3"
                            placeholder="임시 비밀번호를 입력해주세요." type="password">
                    </td>
                    <td class="text-start ps-4 scale-text-gray_06">비밀번호 확인</td>
                    <td class="text-start px-4 py-4">

                        <input data-user-pw="2"
                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3"
                            placeholder="임시 비밀번호를 확인해주세요." type="password">
                    </td>
                </tr>
                <tr class="text-start">
                    <td class="text-start ps-4 scale-text-gray_06">이름<b class="studyColor-text-studyComplete">(필수)</b>
                    </td>
                    <td class="text-start px-4 py-4">

                        <input data-user-name
                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3"
                            placeholder="이름을 입력해주세요.">
                    </td>
                    <td class="text-start ps-4 scale-text-gray_06">주민등록번호</td>
                    <td class="text-start px-4">
                        <div class="d-flex align-items-center scale-text-gray_05">회원가입 시 입력</div>
                    </td>
                </tr>
                {{-- <tr class="text-start">
                    <td class="text-start ps-4 scale-text-gray_06">휴대전화<b class="studyColor-text-studyComplete">(필수)</b>
                    </td>
                    <td class="text-start px-4 py-4">
                        <div class="row mx-0 gap-2">
                            <input data-user-phone placeholder="전화번호를 입력해주세요." onkeyup="this.nextElementSibling.classList.remove('active');"
                            class="text-m-20px px-4 col scale-text-gray_05 border p-2 w-100 rounded-3 text-m-20px">
                            <button type="button" onclick="teachUserAddModalPhoneAuth(this)" data-auth-phone-btn
                                class="col-auto h-auto btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black scale-text-gray_05 primary-bg-mian-hover">
                                인증요청
                            </button>
                        </div>
                    </td>
                    <td class="text-start ps-4 scale-text-gray_06">이메일</td>
                    <td class="text-start px-4">
                        <div class="d-flex align-items-center scale-text-gray_05">회원가입 시 입력</div>
                    </td>
                </tr> --}}
                <tr class="text-start">
                    <td class="text-start p-4 scale-text-gray_06">자택 주소</td>
                    <td class="text-start px-4">
                        <div class="d-flex align-items-center scale-text-gray_05">회원가입 시 입력</div>
                    </td>
                </tr>
                <tr class="text-start">
                    <td colspan="4" class="text-end p-4">
                        <button type="button" onclick="teachUserAddTempIdPwButton()" data-btn-insert
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black scale-text-gray_05">임시아이디 및 비밀번호 발급</button>
                        <button type="button" onclick="teachUserAddUserInsert(this)" data-btn-insert
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black scale-text-gray_05">저장하기</button>
                    </td>
                </tr>

            </tbody>
        </table>
        <div class="scale-bg-gray_01 d-flex flex-column row-gap-3 text-sb-24px mt-52 mb-52">
            <button onclick="teachUserAddUserInputAdd();"
            class="d-flex border-none align-items-center justify-content-center h-120">
                <svg class="me-1" width="32" height="32" viewBox="0 0 32 32" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M24.4849 16L7.51472 16" stroke="#DCDCDC" stroke-width="3" stroke-linecap="round"></path>
                    <path d="M15.9998 24.4844L15.9998 7.51423" stroke="#DCDCDC" stroke-width="3" stroke-linecap="round">
                    </path>
                </svg>
                <span class="text-sb-24px scale-text-gray_05">사용자 추가 등록하기</span>
            </button>
        </div>

        <p class="text-b-28px mt-80 mb-4">추가하기</p>
        <div style="border-top: solid 2px #222;" class="w-100"></div>
        <div data-div-add-table-bundle>

        </div>
        <div data-div-array-add-btn hidden
        class="row mx-0 justify-content-center mt-52">
            <div class="col all-center">
                <button type="button" onclick="teachUserAddUserInsert()"
                    class="btn-line-ms-secondary text-sb-24px rounded-pill border-gray scale-bg-white scale-text-white-hover primary-bg-mian-hover scale-text-gray_05 me-1">일괄
                    등록하기</button>
            </div>
        </div>

        {{-- 160px --}}
        <div>
            <div class="py-lg-5"></div>
            <div class="py-lg-4"></div>
            <div class="pt-lg-3"></div>
        </div>

        {{-- 모달 / 휴대폰 인증 --}}
        <div class="modal fade" id="teach_user_add_modal_phone_auth" tabindex="-1" aria-hidden="true"
            style="display: none;">
            <div class="modal-dialog  modal-dialog-centered modal-680">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">휴대폰 인증</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            onclick="" style="width:32;height:32px;"></button>
                    </div>
                    <div class="modal-body border-0">
                        {{-- 학생 메일  --}}
                        <div class="py-2">
                            <span class="text-r-24px">휴대폰 번호</span>
                        </div>
                        <div class="row m-0 pb-3">
                            <span class="col text-r-24px" data-auth-phone></span>
                            <button class="col-auto btn btn-primary-y btn-lg col py-2"
                                onclick="teachUserAddSendPhoneAuth();">
                                <div class="spinner-border spinner-border-sm" role="status" data-phone-spinner hidden>
                                </div>
                                <span class="ps-2 fs-5">인증번호 전송</span>
                            </button>
                        </div>
                        <div>
                            <input type="text" class="form-control fs-5" data-auth placeholder="인증번호를 입력해주세요."
                                style="height: 60px;">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light btn-lg col py-3 ctext-gc1 btn_close" data-bs-dismiss="modal"
                            aria-label="Close" onclick="">
                            <span class="ps-2 fs-4">닫기</span>
                        </button>
                        <button class="btn btn-primary-y btn-lg col py-3" onclick="teachUserAddPhoneAuth();">
                            <span class="ps-2 fs-4">인증하기</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        //뒤로가로
        function teachUserAddBack() {
            sessionStorage.setItem('isBackNavigation', 'true');
            window.history.back();
        }

        //
        function teachUserAddSelectChange(vthis, type) {
            if (type == 'region') {
                const region_seq = vthis.value;
                teachUserAddTeamSelect(region_seq);
            }
        }

        // 본부 선택시 팀 SELECT
        {{-- function teachUserAddTeamSelect(region_seq) {
            const page = '/manage/useradd/team/select';
            const parameter = {
                region_seq: region_seq
            };
            queryFetch(page, parameter, function(result) {
                console.log(result);
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
                }
            });
        } --}}

        // 휴대폰 인증 모달 오픈
        {{-- let select_auth_btn = null;
        function teachUserAddModalPhoneAuth(vthis){
            // data-auth-mail
            const tb = vthis.closest('[data-tb]');
            const user_phone = tb.querySelector("[data-user-phone]").value;

            // 휴대폰 번호가 없을경우
            if(user_phone == ''){
                toast('휴대폰 번호가 없습니다.');
                return;
            }
            document.querySelector("[data-auth-phone]").innerText = user_phone;

            select_auth_btn = vthis;
           const myModal = new bootstrap.Modal(document.getElementById('teach_user_add_modal_phone_auth'), {
                // keyboard: false
            });
            myModal.show();
        } --}}


        // 휴대폰 인증번호 전송.
        function teachUserAddSendPhoneAuth(){
            if(select_auth_btn == null){
                toast("정상적인 접근이 아닙니다.");
                return;
            }
            vthis = select_auth_btn;
            const tb = vthis.closest('[data-tb]');
            // 데이터
            let user_type = document.querySelector("[data-main-user-type]").value;
            const group_type = tb.querySelector('[data-group-seq]')?.selectedOptions[0]?.getAttribute('data-group-type');
            user_type = group_type||user_type;
            const user_phone = tb.querySelector("[data-user-phone]").value;
            const user_seq = '';
            const user_name = tb.querySelector("[data-user-name]").value;

            if(user_phone == ''){
                toast('휴대폰 번호가 없습니다.');
                return;
            }
            if(user_type == ''){
                toast('사용자 그룹을 선택해주세요.');
                return;
            }
            //user_seq가 없을경우에는 인증만 진행하고, 있는 번호인지 체크도 같이 진행.
            // 전송
            const page = '/phone/auth/send/number';
            const parameter = {
                user_phone: user_phone,
                user_seq: user_seq,
                user_type: user_type,
                user_name: user_name
            };
            const btn_spinner = document.querySelector("[data-phone-spinner]");
            btn_spinner.hidden = false;
            queryFetch(page, parameter, function(result){
                btn_spinner.hidden = true;
                if((result.resultCode||'') == 'success'){
                    toast('인증번호가 전송되었습니다. 인증번호는 3분동안 유효합니다.');
                    tb.querySelector("[data-auth-phone-btn]").classList.add('active');
                }
                //already
                else if((result.resultCode||'') == 'already'){
                    toast('이미 전송을 진행했습니다. 3분이 지난후 다시 전송해주세요.');
                }
                else if((result.resultCode||'') == 'already_phone'){
                    toast('이미 등록된 휴대폰 번호입니다. 다른 번호를 입력해주세요.');
                }
                else{
                    toast('인증번호 전송에 실패하였습니다. 다시 시도해주세요. 유효한 휴대폰 번호인지 확인해주세요.');
                }
            });
        }

        // 여기서부터 수정.
        function teachUserAddPhoneAuth(){
            const modal = document.querySelector('#teach_user_add_modal_phone_auth');
            //전역
            const user_seq = '';
            const user_type = 'student';
            const user_phone = document.querySelector("[data-user-phone]")?.value || '01012345678';
            //모달
            const user_auth = modal.querySelector("[data-auth]").value;

            const page = '/phone/auth/check/number';
            const parameter = {
                user_seq: user_seq,
                user_type: user_type,
                user_phone: user_phone,
                user_auth: user_auth,
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    toast('휴대폰이 인증되었습니다.');
                    modal.querySelector("[data-auth]").value = '';
                    document.querySelector("[data-user-phone]").nextElementSibling.hidden = false;
                    document.querySelector("[data-auth-phone-btn]").hidden = true;

                    // 모달 닫기 버튼 클릭
                    const btn_close = modal.querySelector('.btn_close');
                    btn_close.click();

                }else if((result.resultCode||'') == 'timeover'){
                    toast('인증번호가 시간이 만료되었습니다. 다시 인증번호를 받아주세요.');
                }
                else{
                    toast('인증번호가 일치하지 않습니다. 다시 확인해주세요.');
                }
            });
        }

        // 사용자 추가 등록하기
        function teachUserAddUserInputAdd() {
            const tb = document.querySelector('[data-tb="0"]').cloneNode(true);
            //data-tb 값 증가
            const tb_idx = document.querySelectorAll('[data-tb').length;
            tb.setAttribute('data-tb', tb_idx);

            // data-btn-toggle-table class add rotate-180
            const btn_toggle_table = tb.querySelector('[data-btn-toggle-table]');
            btn_toggle_table.classList.add('rotate-180');

            // tbody 숨기기
            const tbody = tb.querySelector('tbody');
            tbody.hidden = true;
            const bundle = document.querySelector('[data-div-add-table-bundle]');

            // 단 모든 active클래스 제거
            const active_btn = tb.querySelectorAll('.active');
            active_btn.forEach(function(btn){
                btn.classList.remove('active');
            });

            // input = '' select = '' 으로 변경
            const inputs = tb.querySelectorAll('input');
            const selects = tb.querySelectorAll('select');
            inputs.forEach(function(input){
                input.value = '';
            });
            selects.forEach(function(select){
                select.value = '';
            });
            // 지역은 data-main-sido 값으로 변경
            const area = tb.querySelector('[data-area]');
            area.value = document.querySelector('[data-main-sido]').value;

            // 소속도 있으면 data-main-region-seq 값으로 변경
            const region_seq = tb.querySelector('[data-select="region"]');
            region_seq.value = document.querySelector('[data-main-region-seq]').value;

            // 번호 증가
            const idx_num = tb.querySelector('[data-idx-num]');
            idx_num.innerText = tb_idx + 1;
            bundle.appendChild(tb);

            //개별 저장하기 버튼 삭제
            const btn_insert = tb.querySelector('[data-btn-insert]');
            btn_insert.closest('tr').remove();

            teachUserAddChkArrayAddBtn();
        }

        // 오픈/클로즈 /토글 테이블
        function teachUserAddOpenTbody(vthis){
            const tb = vthis.closest('[data-tb]');
            const tbody = tb.querySelector('tbody');
            if(vthis.classList.contains('rotate-180')){
                vthis.classList.remove('rotate-180');
                tbody.hidden = false;
            }else{
                vthis.classList.add('rotate-180');
                tbody.hidden = true;
            }
        }

        // 일괄등록 버튼 보이기 여부
        function teachUserAddChkArrayAddBtn(){
            const tb = document.querySelectorAll('[data-tb]');
            const div_array_add_btn = document.querySelector('[data-div-array-add-btn]');
            if(tb.length > 1){
                div_array_add_btn.hidden = false;
            }else{
                div_array_add_btn.hidden = true;
            }
        }

        // 사용자 저장.
        function teachUserAddUserInsert(vthis){
            //vthis가 있으면 개별 저장, 없으면 일괄 저장.
            const arr_user = [];
            let max_len = 0;
            const tbs = document.querySelectorAll('[data-tb]');
            if(vthis){
                max_len = 1;
            }else{
                max_len = document.querySelectorAll('[data-tb]').length;
            }
            let temp_id_str = '';
            let temp_pw_str = '';
            let tmep_id_idx = 1;
            for(let i = 0; i < max_len; i++){
                const tb = tbs[i];
                let user_id = tb.querySelector('[data-user-id]')?.value;
                const group_seq = tb.querySelector('[data-group-seq]')?.value;
                const area = tb.querySelector('[data-area]')?.value;
                const region_seq = tb.querySelector('[data-select="region"]')?.value;
                const team_code = tb.querySelector('[data-select="team"]')?.value;
                const user_name = tb.querySelector('[data-user-name]')?.value;
                const user_phone = tb.querySelector('[data-user-phone]')?.value;
                let user_pw = tb.querySelector('[data-user-pw="1"]')?.value;
                let user_pw_chk = tb.querySelector('[data-user-pw="2"]')?.value;
                const is_auth = tb.querySelector('[data-auth-phone-btn]')?.classList.contains('active') ? 'Y' : 'N';
                let user_type = document.querySelector("[data-main-user-type]")?.value;
                const group_type = tb.querySelector('[data-group-seq]')?.selectedOptions[0]?.getAttribute('data-group-type');
                user_type = group_type||user_type;

                if(user_id == ''){
                    temp_id_str = '아이디가 비워져 있으면 임시 아이디를 생성합니다.';
                    user_id = teachUserAddCreateId();
                    tmep_id_idx++;
                }
                if(user_pw == '' && user_pw_chk == ''){
                    temp_pw_str = '비밀번호가 비워져 있으면 임시 비밀번호는 1234입니다.';
                    user_pw = '1234';
                    user_pw_chk = '1234';
                }
                if(user_pw != user_pw_chk){
                    toast((i+1)+'번 사용자 등록의  비밀번호가 일치하지 않습니다.');
                    return;
                }
                if(user_name == ''){
                    toast((i+1)+'번 사용자 등록의 이름을 입력해주세요.');
                    return;
                }
                {{-- if(user_phone == ''){
                    toast((i+1)+'번 사용자 등록의 휴대전화를 입력해주세요.');
                    return;
                }
                if(is_auth == 'N'){
                    toast((i+1)+'번 사용자 등록의 휴대전화 인증을 진행해주세요.');
                    return;
                } --}}
                // if(group_seq == ''){
                //     toast((i+1)+'번 사용자 등록의 사용자 그룹을 선택해주세요.');
                //     return;
                // }
                if(area == ''){
                    toast((i+1)+'번 사용자 등록의 지역을 선택해주세요.');
                    return;
                }
                if(region_seq == ''){
                    toast((i+1)+'번 사용자 등록의 소속을 선택해주세요.');
                    return;
                }
                arr_user.push({
                    user_id: user_id,
                    group_seq: group_seq || 1,
                    area: area,
                    region_seq: region_seq,
                    team_code: team_code,
                    user_name: user_name,
                    user_phone: user_phone || '01012345678',
                    user_pw: user_pw,
                    is_auth: is_auth,
                    user_type:user_type
                });
            }
            const page = "/teacher/users/add/excel/insert";
            const parameter = {
                users: arr_user
            };
            const msg =
            `
            <div class="text-m-28px">사용자 등록을 진행하시겠습니까?</div>
            `;
            sAlert('', msg, 3, function(){
                queryFetch(page, parameter, function(result) {
                    if ((result.resultCode || '') == 'success') {
                        const cnt_ap_student = result.cnt_already_phone_student;
                        const cnt_ap_parent = result.cnt_already_phone_parent;
                        const cnt_ap_teacher = result.cnt_already_phone_teacher;
                        const arr_phone = result.arr_phone;

                        const msg_in =
                        `<div class="text-m-28px">사용자 등록이 완료되었습니다.</div>
                        `;

                        sAlert('', msg_in, 4, function(){
                            // 뒤로가기
                            teachUserAddBack();
                        });
                    }else{
                        toast('다시 시도 해주시기 바랍니다.');
                    }
                });
            });
        }
        // 임시 아이디 생성
        let idx = 0;
        function teachUserAddCreateId(){
            idx++;
            let tempID = '';
            dTime = new Date();
            let req_time = dTime.getTime();
            req_time = (req_time.toString()).substr(-9);
            //랜덤값 무조건 4자리
            req_time += Math.floor(Math.random() * 10000).toString().padStart(4, "0");
            tempID = 'temP' + req_time.toString() + idx.toString().padStart(4, "0");
            return tempID;
        }

        // 임시아이디 및 비밀번호 발급
        function teachUserAddTempIdPwButton(){
            const msg =
            `
            <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-4">
            <p class="modal-title text-center text-sb-28px alert-bottom-m studyColor-text-studyComplete" id="">임시아이디/비밀번호(1234)를 발급하시겠습니까?</p>
            </div>
            `;
            sAlert('', msg, 3, function() {
                document.querySelector('[data-user-id]').value = teachUserAddCreateId();
                document.querySelector('[data-user-pw="1"]').value = '1234';
                document.querySelector('[data-user-pw="2"]').value = '1234';
            }, null, '발급하기', '취소');
        }
    </script>
@endsection
