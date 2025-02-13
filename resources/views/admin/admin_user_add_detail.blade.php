{{-- @include('admin.admin_user_add_detail', $user_group) 로 넘겨서 여기서  $group로 넣어주기. --}}
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
@csrf
<div class="col-12 pe-3 ps-3">
    {{---사용자 등록 DIV 시작--}}
    <div id="useradd_div_user_add_main">
        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
            <h3>사용자 등록 / 수정</h3>
            <div>
                <button class="btn btn-outline-primary me-2" onclick="useraddExcelPop('open');">일괄등록</button>
                <button id="useradd_btn_user_add" class="btn btn-outline-secondary" onclick="useraddShowAddDiv()">추가등록</button>
            </div>
        </div>
        <div id="useradd_div_userInfo">
            <div class="useradd_div_userInfoSub position-relative">
                <button id="useradd_btn_div_del" hidden class="btn btn-ouline-danger btn-sm position-absolute" style="top: 0; right:0;color: red" onclick="useraddAddDivRemove(this);">삭제</button>
                <div class="add_title" hidden>
                    <h4>추가 등록</h4>
                </div>
                <table class="table table-bordered">
                    <tr>
                        <th class="bg-light align-middle col-3">아이디</th>
                        <td class="d-flex align-items-center">
                            <input type="text" id="useradd_inp_userId" class="form-control me-2" placeholder="아이디" onkeyup="this.classList.remove('text-primary')">
                            <button id="useradd_btn_user_id_check" class="btn btn-outline-secondary" style="width:150px;" onclick="useraddUserIdCheck(this);">중복확인</button>
                            <input type="hidden" id="useradd_inp_user_key" value="">
                            <input type="hidden" id="useradd_inp_user_type">
                        </td>
                        <th class="bg-light align-middle col-3">*사용자그룹</th>
                        <td class="col-3">
                            <select id="useradd_sel_group_name" class="form-select" onchange="useraddSelectGroup(this);">
                                <option value="" selected>그룹선택</option>
                                @if(isset($user_group))
                                    @foreach ($user_group as $group)
                                        <option value="{{ $group['id'] }}" grouptype="{{ $group['group_type'] }}">{{ $group['group_name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light align-middle">*지역</th>
                        <td colspan="3">
                            <select name="" id="useradd_sel_sido" class="form-select" onchange="useraddSelectSido(this);">
                                <option value='' selected >지역선택</option>
                                @if(isset($address_sido) && count($address_sido) > 0)
                                    @foreach ($address_sido as $item)
                                        <option value="{{ $item['sido'] }}">{{ $item['sido'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                    </tr>
                    <tr class="team_view">
                        <th class="bg-light align-middle">소속</th>
                        <td>
                            <select id="useradd_sel_region" class="form-select" onchange="useraddSelectRegion(this);">
                                <option selected value="" area="">미배정</option>
                                @if(isset($region) && count($region) > 0)
                                    @foreach ($region as $reg)
                                        <option value="{{ $reg['id'] }}" area="{{ $reg['area'] }}" hidden>{{ $reg['region_name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                        <th class="bg-light align-middle team_view" hidden>팀<br>(유형이 직원인 경우 항목 표시)</th>
                        <td class="team_view" hidden>
                            <select id="useradd_sel_team" class="form-select">
                                <option selected value="" area="">미배정</option>
                            </select>
                        </td>
                    </tr>
                    <tr class="student_view" hidden>
                        <th class="bg-light align-middle">학부모검색</th>
                        <td class="d-flex align-items-center">
                            <input type="text" class="form-control me-2 useradd_inp_sch" onkeyup="if(event.keyCode == '13'){useraddSearchUser(this, 'parent');}" placeholder="학부모이름">
                            <button class="btn btn-outline-secondary useradd_btn_sch_parent" style="width:100px;" onclick="useraddSearchUser(this, 'parent');">검색</button>
                        </td>
                        <th class="bg-light align-middle">연동 학부모</th>
                        <td>
                            <input type="hidden" class="useradd_inp_sch_parent_key">
                            <input type="text" class="form-control useradd_inp_sel_parent" readonly>
                        </td>
                    </tr>
                    <tr class="parent_view" hidden>
                        <th class="bg-light align-middle">학생검색</th>
                        <td class="d-flex align-items-center">
                            <input type="text" class="form-control me-2 useradd_inp_sch" onkeyup="if(event.keyCode == '13'){useraddSearchUser(this, 'student');}" placeholder="학생이름">
                            <button class="btn btn-outline-secondary useradd_btn_sch_student" style="width:100px;" onclick="useraddSearchUser(this, 'student');">검색</button>
                        </td>
                        <th class="bg-light align-middle">연동 학생</th>
                        <td>
                            <input type="hidden" class="useradd_inp_sch_student_key">
                            <input type="text" class="form-control useradd_inp_sel_student" readonly>
                        </td>
                    </tr>
                    <tr class="student_view" hidden>
                        <th class="bg-light align-middle">학교<br>(유형이 학생인 경우 항목 표시)</th>
                        <td><input type="text" id="useradd_inp_schoolName" class="form-control" placeholder="학교"></td>
                        <th class="bg-light align-middle">학년<br>(유형이 학생인 경우 항목 표시)</th>
                        <td>
                            <select id="useradd_sel_grade" class="form-select" onchange="">
                                <option selected value="" area="">미배정</option>
                                @if(!empty($grade_codes))
                                    @foreach ($grade_codes as $item)
                                        <option value="{{ $item['id'] }}">{{ $item['code_name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </td>
                    </tr>
                    <tr class="student_view" hidden>
                        <th class="bg-light align-middle">이용권<br>(유형이 학생인 경우 항목 표시)</th>
                        <td>
                            <div class="dropdown">
                                {{-- <input id="useradd_inp_ticket" class="form-control dropdown-toggle" onkeyup="" data-bs-toggle="dropdown" aria-expanded="false"> --}}
                                {{-- <input type="hidden" class="inp_ticket_seq"> --}}
                                <select id="useradd_sel_goods" class="form-select" onchange="useraddSelectTicket(this);">
                                    <option selected value="">미배정</option>
                                    @if(isset($goods) && count($goods) > 0)
                                        @foreach ($goods as $gds)
                                            <option value="{{ $gds['id'] }}" goods_period="{{ $gds['goods_period'] }}">{{ $gds['goods_name'].' '.$gds['goods_period'].'개월' }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </td>
                        <th class="bg-light align-middle">이용기간<br>(유형이 학생인 경우 항목 표시)</th>
                        <td>
                            @php ($startDate = date('Y-m-d'))
                            @php ($endDate = strtotime("+6 month"))
                            @php ($endDate = date('Y-m-d', $endDate))

                            <div class="d-flex">
                                <input type="date" id="useradd_inp_start_date" class="form-control text-center" style="width:auto;" value="{{ $startDate }}" onchange="useraddCalDiff();">
                                <span style="padding-top:5px;">~</span>
                                <input type="date" id="useradd_inp_end_date" class="form-control text-center" style="width:auto;" value="{{ $endDate }}" onchange="useraddCalDiff();">
                                <span style="padding-top:7px;padding-left:5px;" id="useradd_sp_date_diff"></span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="bg-light align-middle">비밀번호</th>
                        <td><input type="password" id="useradd_inp_pass" class="form-control" placeholder="비밀번호" autocomplete="off"></td>
                        <th class="bg-light align-middle">비밀번호 확인</th>
                        <td><input type="password" id="useradd_inp_passChk" class="form-control" placeholder="비밀번호 확인" autocomplete="off"></td>
                    </tr>
                    <tr>
                        <th class="bg-light align-middle">*이름</th>
                        <td><input type="text" id="useradd_inp_userName" class="form-control" placeholder="이름" required></td>
                        <th class="bg-light align-middle">주민등록번호</th>
                        <td><input type="text" id="useradd_inp_userRrn" class="form-control" placeholder="주민등록번호"></td>
                    </tr>
                    <tr>
                        <th class="bg-light align-middle">*휴대폰 번호</th>
                        <td>
                            <div class="d-flex align-items-center">
                                <input type="tel" id="useradd_inp_userPhone" class="form-control me-2" placeholder="휴대폰 번호" required>
                                <button class="btn btn-outline-secondary" style="width:220px">본인 인증 필요</button>
                            </div>
                        </td>
                        <th class="bg-light align-middle">이메일 주소</th>
                        <td><input type="email" id="useradd_inp_userEmail" class="form-control" placeholder="이메일 주소"></td>
                    </tr>
                    <tr>
                        <th class="bg-light align-middle">자택주소</th>
                        <td colspan="3">
                            <div class="d-flex gap-2 align-middle">
                                <input type="text" id="useradd_inp_userAddr" class="form-control" placeholder="자택주소">
                                <a href="javascript:execDaumPostcode();" class="pt-1" style="width:100px">주소 찾기</a>
                            </div>
                            <div id="address_wrap" style="display:none;border:1px solid;width:500px;height:300px;margin:5px 0;position:relative">
                                <img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode()" alt="접기 버튼">
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        {{-- 하단 취소 저장 버튼 --}}
        <div class="text-center">
            <button id="useradd_btn_cancel" class="btn btn-outline-secondary col-2">취소</button>
            <button id="useradd_btn_save" class="btn btn-outline-primary ms-5 col-2" onclick="useraddUserSave()">저장</button>
        </div>
    </div>
    {{---사용자 등록 DIV 종료--}}
    {{--사용자 일괄 등록 DIV 시작--}}
    <div id="useradd_div_excel_pop" hidden>
        <h3 class="pt-3 px-3 useradd_user_search_title">사용자 일괄 등록</h3>
        {{--사용자 일괄등록 버튼 DIV--}}
        <div class="d-flex justify-content-between align-items-end" style="margin:2rem 1rem 1rem 1rem;">
            <div>
                <span>선택된 파일</span>
                <span class="sp_select_excel_name text-decoration-underline" style="color:blue;"></span>
            </div>
            <div>
                <a href="/manage/useradd/filedown?file_name=test.xlsx" style="text-decoration: none;" target="_blank">
                    <button class="btn btn-outline-primary btn-sm" style="margin-right:0.5rem;">excel 형식 다운로드</button>
                </a>
                <button class="btn btn-outline-primary btn-sm" style="margin-right:0.5rem;" onclick="useraddFileDelete();document.querySelector('#useradd_inp_excelfile').click();">excel 불러오기</button>
                <button class="btn btn-outline-primary btn-sm" style="margin-right:0.5rem;" onclick="useraddTempID();">임시아이디 발급</button>
                <input id="useradd_inp_excelfile" type="file" onchange="useraddReadExcel();" hidden>
            </div>
        </div>
        {{--사용자 일괄등록 리스트 DIV--}}
        <div id="useradd_div_excel_list" class="overflow-auto" style="min-height:500px;">
            <table id="useradd_tb_excel_list" class="table table-bordered text-center">
                <thead>
                    <tr>
                        <th>순번</th>
                        <th>사용자그룹</th>
                        <th>소속</th>
                        <th>아이디</th>
                        <th>비밀번호</th>
                        <th>이름</th>
                        <th>휴대폰번호</th>
                        <th class="noprint">결과</th>
                    </tr>
                </thead>
                <tbody id="useradd_tby_excel_list">
                    <tr class="useradd_tr_excel_list_copy" hidden>
                        <td class="td_excel_seq">#순번</td>
                        <td class="td_excel_user_group">#사용자그룹</td>
                        <td class="td_excel_region">#소속</td>
                        <td class="td_excel_user_id">#아이디</td>
                        <td class="td_excel_user_pw">#비밀번호</td>
                        <td class="td_excel_user_name">#이름</td>
                        <td class="td_excel_user_phone">#휴대폰번호</td>
                        <td class="td_excel_status noprint"></td>
                        <input type="hidden" class="td_excel_user_group_seq">
                        <input type="hidden" class="td_excel_user_group_type">
                        <input type="hidden" class="td_excel_user_region_code">
                        <input type="hidden" class="td_excel_user_area">
                    </tr>
                </tbody>
            </table>
        </div>
        {{--사용자 일괄등록 그룹별 수량 및 인쇄 버튼 DIV--}}
        <div  class="d-flex justify-content-between align-items-end" style="margin:2rem 1rem 1rem 1rem;">
            <span class="useradd_sp_group_cnt"></span>
            <button class="btn btn-outline-secondary btn-sm" onclick="useraddExcelListPrint();">인쇄</button>
        </div>
        {{--사용자 일괄등록 취소/저장 버튼 DIV--}}
        <div class="text-center">
            <button id="useradd_btn_excel_cancel" class="btn btn-outline-secondary col-2" onclick="useraddExcelPop('close');useraddInfoClear();">취소</button>
            <button id="useradd_btn_excel_save" class="btn btn-outline-secondary ms-5 col-2" onclick="useraddExcelListCheck();">저장</button>
        </div>
    </div>
    {{--사용자 일괄 등록 DIV 종료--}}
</div>

{{--학생/학부모 검색 팝업 DIV--}}
<div id="useradd_div_user_search" class="position-absolute bg-white border border-dark col-3" style="top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 3; min-height: 500px;" hidden>
    <div class="d-flex justify-content-between">
        <h5 class="pt-3 px-3 useradd_user_search_title">학생/학부모 선택</h5>
        <button class="btn btn-primary btn-sm" style="margin: 0.5rem;" onclick="useraddUserSearchClose();">닫기</button>
    </div>
    <input type="hidden" class="type">
    <div class="col-12 pt-2 pb-3">
        {{--팝업창 내에서 검색--}}
        <div></div>
        {{--검색 결과 리스트--}}
        <div>
            {{--학부모 리스트--}}
            <table id="useradd_tb_parentlist" class="table" hidden>
                <thead>
                    <tr>
                        <th>학부모명</th>
                        <th>전화번호</th>
                    </tr>
                </thead>
                <tbody id="useradd_tby_parentlist">
                    <tr class="useradd_tr_parentlist" hidden>
                        <input type="hidden" class="inp_parent_key">
                        <td class="td_parent_name">#학부모명</td>
                        <td class="td_parent_tel">#전화번호</td>
                    </tr>
                </tbody>
            </table>
            {{--학생 리스트--}}
            <table id="useradd_tb_studentlist" class="table" hidden>
                <thead>
                    <tr>
                        <th>학생명</th>
                        <th>전화번호</th>
                        <th>학교</th>
                        <th>학년</th>
                    </tr>
                </thead>
                <tbody id="useradd_tby_studentlist">
                    <tr class="useradd_tr_studentlist" hidden>
                        <input type="hidden" class="inp_student_key">
                        <td class="td_student_name">#학생명</td>
                        <td class="td_student_tel">#전화번호</td>
                        <td class="td_school">#학교</td>
                        <td class="td_grade">#학년</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const regionlist = @json($region); //소속
    const usergrouplist = @json($user_group); //그룹
    useraddCalDiff();

    //사용자 그룹 변경 시
    function useraddSelectGroup(nThis){
        const sel_op = nThis.options[nThis.selectedIndex];
        let grouptype = sel_op.getAttribute('grouptype');
        if(useraddisEdit()){
            //수정이면서 유저의 타입이 다르면
            if(grouptype != document.querySelector('#useradd_inp_user_type').value){
                const user_type = document.querySelector('#useradd_inp_user_type').value;
                for(let i = 0; i < nThis.options.length; i++){
                    if(nThis.options[i].getAttribute('grouptype') == user_type){
                        nThis.options[i].selected = true;
                        break;
                    }
                }
                // 알림창 띄우기
                sAlert('', '사용자의 타입과 다른 그룹을 변경할 수 없습니다.');
                return;
            }
            grouptype = document.querySelector('#useradd_inp_user_type').value;
        }

        const divUserInfo = nThis.closest('div');
        const trTeam = divUserInfo.querySelectorAll('.team_view');
        const trStudent = divUserInfo.querySelectorAll('.student_view');
        const trParent = divUserInfo.querySelectorAll('.parent_view');

        trTeam.forEach((t) => {t.hidden = true;});
        trStudent.forEach((s) => {s.hidden = true;});
        trParent.forEach((p) => {p.hidden = true;});
        if(grouptype == 'teacher'){
            trTeam.forEach((t) => {t.hidden = false;});
        }
        else if(grouptype == 'parent'){
            // trTeam.forEach((t) => {t.hidden = false;});
            trParent.forEach((p) => {p.hidden = false;});
        }
        else if(grouptype.indexOf('student') > -1 ){
            trStudent.forEach((s) => {s.hidden = false;});
        }

        divUserInfo.querySelector('#useradd_inp_userId').classList.remove('text-primary');
    }

    //사용자 지역 변경 시
    function useraddSelectSido(nThis, callback){
        const sel_val = nThis.value;
        document.querySelector('#useradd_sel_team').selectedIndex = 0;

        const page = "/manage/useradd/region/select";
        const parameter = {
            sido:sel_val
        };
        queryFetch(page, parameter, function(result){
            //초기화 useradd_sel_region
            const sel_region = document.querySelector('#useradd_sel_region');
            sel_region.innerHTML = '<option selected value="" area="">미배정</option>';

            if(result.resultCode == 'success'){
                for(let i = 0; i < result.resultData.length; i++){
                    const r_data = result.resultData[i];
                    const option = document.createElement('option');
                    option.value = r_data.id;
                    option.setAttribute('area', r_data.area);
                    option.innerHTML = r_data.region_name;
                    sel_region.appendChild(option);
                }
                if(callback != undefined){
                    callback();
                }
            }
        });
    }

    //추가등록 버튼 클릭 시
    function useraddShowAddDiv(){
        const div_user_add = document.querySelector('#useradd_div_userInfo .useradd_div_userInfoSub').cloneNode(true);
        div_user_add.querySelector('.add_title').hidden = false;
        useraddUserInfoResetValue(div_user_add);
        div_user_add.querySelector('#useradd_btn_div_del').hidden = false;
        document.querySelector('#useradd_div_userInfo').appendChild(div_user_add);
    }

    function useraddUserSave(){
        const div_user_group = document.querySelectorAll('#useradd_div_userInfo .useradd_div_userInfoSub');

        useraddChkInfo(div_user_group).then(function(result){
            if(!result)
                return;

            const totalCnt = div_user_group.length;

            let count = 0;
            let success = 0;
            let fail = 0;
            div_user_group.forEach(function(item){
                const user_key = item.querySelector('#useradd_inp_user_key').value;
                const user_id = item.querySelector('#useradd_inp_userId').value;
                const sel_group = item.querySelector('#useradd_sel_group_name');
                const group_seq = sel_group.value;
                const grouptype = sel_group.options[sel_group.selectedIndex].getAttribute('grouptype');
                const sido = item.querySelector('#useradd_sel_sido').value;
                const region = item.querySelector('#useradd_sel_region').value;
                const team = item.querySelector('#useradd_sel_team').value;
                const school_name = item.querySelector('#useradd_inp_schoolName').value;
                const grade = item.querySelector('#useradd_sel_grade').value;
                // const ticket = item.querySelector('#useradd_inp_ticket').value;
                // const ticket_seq = item.querySelector('.inp_ticket_seq').value.toString();
                const ticket_start_date = item.querySelector('#useradd_inp_start_date').value;
                const ticket_end_date = item.querySelector('#useradd_inp_end_date').value;
                const user_pw = item.querySelector('#useradd_inp_pass').value;
                const user_pw_chk = item.querySelector('#useradd_inp_passChk').value;
                const user_name = item.querySelector('#useradd_inp_userName').value;
                const user_rrn = item.querySelector('#useradd_inp_userRrn').value;
                const user_phone = item.querySelector('#useradd_inp_userPhone').value;
                const user_email = item.querySelector('#useradd_inp_userEmail').value;
                const user_addr = item.querySelector('#useradd_inp_userAddr').value;

                let conn_user_id = "";
                if(grouptype.indexOf('student') > -1){
                    conn_user_id = item.querySelector('.useradd_inp_sch_parent_key').value;
                }
                else if(grouptype == 'parent'){
                    conn_user_id = item.querySelector('.useradd_inp_sch_student_key').value;
                }

                //이전 코드는 삭제 / goods로 변경됨.
                const goods_seq = item.querySelector('#useradd_sel_goods').value;
                const goods_start_date = item.querySelector('#useradd_inp_start_date').value;
                const goods_end_date = item.querySelector('#useradd_inp_end_date').value;

                //이용권 선택 후 이용권명칭 수정했는지 체크
                // if(ticket.length > 0){
                //     let ticket_list = document.querySelector('#useradd_ul_ticket').querySelectorAll('li');
                //     ticket_list.forEach((item)=>{
                //         let chk_ticket = item.querySelector('a').innerHTML;
                //         let chk_ticket_seq = item.querySelector('a').getAttribute('ticket_seq');
                //         if(ticket != chk_ticket && ticket_seq == chk_ticket_seq){
                //             ticket_seq = "";
                //         }
                //     });
                // }

                const parameter = {
                    user_key:user_key,
                    user_id:user_id,
                    group_seq:group_seq,
                    grouptype:grouptype,
                    sido:sido,
                    region:region,
                    team_code:team,
                    school_name:school_name,
                    grade:grade,
                    goods_seq:goods_seq,
                    goods_start_date:goods_start_date,
                    goods_end_date:goods_end_date,
                    user_pw:user_pw,
                    user_pw_chk:user_pw_chk,
                    user_name:user_name,
                    user_rrn:user_rrn,
                    user_phone:user_phone,
                    user_email:user_email,
                    user_addr:user_addr,
                    num:count,
                    conn_user_id:conn_user_id
                };

                const page = 'user/insert';
                queryUserAdd(page, parameter, function(result){
                    count += 1;
                    if(result == null || result.resultCode == null){
                        return;
                    }
                    if(result.resultCode == 'success'){
                        success += 1;
                    }else{
                        fail += 1;
                    }

                    if(totalCnt == count){
                        useraddUserInfoReset();
                        if(grouptype.indexOf('student') > -1 && result.ticket != undefined){
                            let sel_ul = document.querySelector('#useradd_ul_ticket li').cloneNode(true);
                            document.querySelector('#useradd_ul_ticket').innerHTML = '';

                            result.ticket.forEach((item)=>{
                                let cloneUl = sel_ul.cloneNode(true);
                                cloneUl.querySelector('a').setAttribute('ticket_seq', item.seq);
                                cloneUl.querySelector('a').innerHTML = item.ticket_name;
                                document.querySelector('#useradd_ul_ticket').appendChild(cloneUl);
                            });
                        }
                        sAlert('', '저장되었습니다.\n 성공 : ' + success + '실패 : ' + fail);
                        if(useraddisEdit()){
                            userlistSelectUser();
                            userlistUserAddCancel();
                        }
                    }
                });
            });
        });
    }

    function useraddChkInfo(nThis){
        return new Promise(function(resolve, reject){
            let result = true;

            for(let i = 0; i<nThis.length; i++){
                const item = nThis[i];
                //아이디 체크
                if(item.querySelector('#useradd_inp_userId').value != ''){
                    //수정쪽이면 넘어간다.
                    if(item.querySelector('#useradd_inp_userId').classList.contains('text-primary') == false && useraddisEdit() == false) {
                        sAlert('','아이디 중복 체크를 해주세요');
                        item.querySelector('#useradd_inp_userId').focus();
                        result = false;
                        return;
                    }
                }

                const sel_group = item.querySelector('#useradd_sel_group_name');
                const group_seq = sel_group.value;
                const grouptype = sel_group.options[sel_group.selectedIndex].getAttribute('grouptype');
                //사용자그룹
                if(!(grouptype == 'teacher' || grouptype == 'parent' || grouptype.indexOf('student') > -1)){
                    sAlert('','사용자그룹을 선택해주세요');
                    result = false;
                    return;
                }

                if(grouptype.indexOf('student') > -1 ){
                    if(item.querySelector('.useradd_inp_sch_parent_key').value.length < 1){
                        alert('학생의 경우 부모님을 선택해야 저장이 가능합니다.');
                        result = false;
                        return;
                    }
                }

                //지역
                if(item.querySelector('#useradd_sel_sido').value == ''){
                    alert('지역을 선택해주세요');
                    result = false;
                    return;
                }

                const user_pw = item.querySelector('#useradd_inp_pass').value;
                const user_pw_chk = item.querySelector('#useradd_inp_passChk').value;
                // //비밀번호 체크
                // if(user_pw == ''){
                //     alert('비밀번호를 입력해주세요');
                //     result = false;
                //     return;
                // }
                //비밀번호 확인
                if(user_pw != '' && user_pw_chk == ''){
                    alert('비밀번호 확인을 입력해주세요');
                    result = false;
                    return;
                }
                //비밀번호 확인
                if(user_pw != '' && user_pw_chk != '' && user_pw != user_pw_chk){
                    alert('비밀번호 확인에 입력하신 내용이 비밀번호와 다릅니다.');
                    document.querySelector('#useradd_inp_passChk').focus();
                    result = false;
                    return;
                }
                //이름 체크
                if(item.querySelector('#useradd_inp_userName').value == ''){
                    alert('이름을 입력해주세요');
                    result = false;
                    return;
                }
                //휴대폰 번호 체크
                {{-- if(item.querySelector('#useradd_inp_userPhone').value == ''){
                    alert('휴대폰 번호를 입력해주세요');
                    result = false;
                    return;
                } --}}
            }
            resolve (result);
        });
    }
    //사용자 등록화면 DB 연결
    function queryUserAdd(page,parameter, callback) {
        const xtken = document.querySelector('#csrf_token').value;
        fetch(`/manage/useradd/${page}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN':  xtken
                },
                body: JSON.stringify(parameter)
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(result) {
                if (callback != undefined) {
                    callback(result);
                }
            })
            .catch(function(error) {
                console.log(error);
            });
    }
    //사용자 아이디 중복 체크
    function useraddUserIdCheck(nThis){
        const div_user_group = nThis.closest('div');
        const user_id = div_user_group.querySelector('#useradd_inp_userId').value;
        const sel_group = div_user_group.querySelector('#useradd_sel_group_name');
        const sel_group_idx = sel_group.selectedIndex;
        const grouptype = sel_group.options[sel_group.selectedIndex].getAttribute('grouptype');

        if(user_id.length < 1){
            alert('사용자 이이디 입력 후 중복 확인이 가능합니다.');
            return;
        }

        if(sel_group_idx == 0){
            alert('사용자 그룹을 선택 후 아이디 중복 체크 해주세요');
            return;
        }

        const parameter = {
            user_id:user_id,
            grouptype:grouptype,
        };

        const page = 'user/id/check';
        queryUserAdd(page,parameter,function(result){
            if(result == null || result.resultCode == null){
                return '';
            }

            if(result.resultCode == 'success'){
                div_user_group.querySelector('#useradd_inp_userId').classList.add('text-primary');
                alert('사용 가능한 아이디입니다.');
                return;
            }
            else{
                alert('이미 사용중인 아이디입니다.');
                return;
            }
        });
    }
    //사용자 정보 입력란 모두 초기화
    function useraddUserInfoReset(){
        const div_user_group = document.querySelector('#useradd_div_userInfo');

        const div_sub = div_user_group.querySelector('.useradd_div_userInfoSub');
        useraddUserInfoResetValue(div_sub);
        div_user_group.innerHTML = '';
        div_user_group.appendChild(div_sub);
    }

    function useraddUserInfoResetValue(val){
        val.querySelector('#useradd_inp_userId').value = '';
        val.querySelector('#useradd_sel_group_name').selectedIndex = 0;
        val.querySelector('#useradd_sel_sido').selectedIndex = 0;
        val.querySelector('#useradd_sel_region').selectedIndex = 0;
        val.querySelector('#useradd_sel_team').selectedIndex = 0;
        val.querySelector('#useradd_inp_schoolName').value = '';
        val.querySelector('#useradd_sel_grade').value = '';
        // val.querySelector('#useradd_inp_ticket').value = '';
        val.querySelector('#useradd_inp_pass').value = '';
        val.querySelector('#useradd_inp_passChk').value = '';
        val.querySelector('#useradd_inp_userName').value = '';
        val.querySelector('#useradd_inp_userRrn').value = '';
        val.querySelector('#useradd_inp_userPhone').value = '';
        val.querySelector('#useradd_inp_userEmail').value = '';
        val.querySelector('#useradd_inp_userAddr').value = '';
        val.querySelector('.useradd_inp_sch_student_key').value = '';
        val.querySelector('.useradd_inp_sch_parent_key').value = '';
        val.querySelector('.useradd_inp_sel_student').value = '';
        val.querySelector('.useradd_inp_sel_parent').value = '';

        const trTeam = val.querySelectorAll('.team_view');
        const trStudent = val.querySelectorAll('.student_view');
        const trParent = val.querySelectorAll('.parent_view');

        trTeam.forEach((t) => {t.hidden = true;});
        trStudent.forEach((s) => {s.hidden = true;});
        trParent.forEach((p) => {p.hidden = true;});
    }
    //사용자 소속 선택 시 팀 불러오기
    function useraddSelectRegion(nThis, team_code){
        const sel_val = nThis.value;
        document.querySelector('#useradd_sel_team').selectedIndex = 0;

        const page = "/manage/useradd/team/select";
        const parameter = {
            region_seq:sel_val
        };
        queryFetch(page, parameter, function(result){
            //초기화 useradd_sel_region
            const sel_team = document.querySelector('#useradd_sel_team');
            sel_team.innerHTML = '<option selected value="" area="">미배정</option>';

            if(result.resultCode == 'success'){
                for(let i = 0; i < result.resultData.length; i++){
                    const r_data = result.resultData[i];
                    const option = document.createElement('option');
                    option.value = r_data.team_code;
                    option.innerHTML = r_data.team_name;
                    sel_team.appendChild(option);
                }
                if(team_code != undefined){
                    sel_team.value = team_code;
                }
            }
        });
    }
    //학생-부모 검색하는 함수
    function useraddSearchUser(nThis, type){
        nThis.classList.add('searchUser');
        const sel_tr = nThis.closest('tr');
        const sch_val = sel_tr.querySelector('.useradd_inp_sch').value;

        const page = 'user/select';
        const parameter = {
            keyword:sch_val,
            searchtype:type
        };
        queryUserAdd(page,parameter,function(result){
            if(result == null || result.resultCode == null){
                alert('등록된 회원이 없습니다.');
                return;
            }

            if(result.resultCode == 'success'){
                const rData = result.resultData;
                if(rData == undefined || rData == null || rData.length == 0){
                    alert('등록된 회원이 없습니다.');
                    sel_tr.querySelector('.useradd_inp_sch').value = '';
                    return;
                }
                else{
                    const sch_div = document.querySelector('#useradd_div_user_search');
                    //학부모
                    if(type == 'parent'){
                        const sch_tr = sch_div.querySelector('#useradd_tby_parentlist .useradd_tr_parentlist').cloneNode(true);
                        sch_div.querySelector('#useradd_tby_parentlist').innerHTML = '';
                        sch_div.querySelector('#useradd_tby_parentlist').appendChild(sch_tr);

                        //검색어가 있는데 결과가 하나면 바로 넣어주기
                        if(rData.length == 1 && sch_val != ''){
                            sel_tr.querySelector('.useradd_inp_sch').value = "";
                            sel_tr.querySelector('.useradd_inp_sch_parent_key').value = rData[0].parent_id;
                            sel_tr.querySelector('.useradd_inp_sel_parent').value = rData[0].parent_name;
                        }
                        else{
                            //결과가 여러개이면 루프 돌려서 넣어주기
                            for(let i=0; i<rData.length; i++){
                                const row = rData[i];
                                const clone_tr = sch_tr.cloneNode(true);
                                clone_tr.hidden = false;
                                clone_tr.querySelector('.inp_parent_key').value = row.parent_id;
                                clone_tr.querySelector('.td_parent_name').innerText = row.parent_name;
                                clone_tr.querySelector('.td_parent_tel').innerText = row.parent_phone;
                                clone_tr.setAttribute('onclick', 'useraddSelectParent(this)');
                                sch_div.querySelector('#useradd_tby_parentlist').appendChild(clone_tr);
                            }
                            sch_div.querySelector('#useradd_tb_studentlist').hidden = true;
                            sch_div.querySelector('#useradd_tb_parentlist').hidden = false;
                            sch_div.hidden = false;
                        }
                    }
                    //학생
                    else if(type.indexOf('student') !== false){
                        const sch_tr = sch_div.querySelector('#useradd_tby_studentlist .useradd_tr_studentlist').cloneNode(true);
                        sch_div.querySelector('#useradd_tby_studentlist').innerHTML = '';
                        sch_div.querySelector('#useradd_tby_studentlist').appendChild(sch_tr);

                        //검색어가 있는데 결과가 하나면 바로 넣어주기
                        if(rData.length == 1 && sch_val != ''){
                            sel_tr.querySelector('.useradd_inp_sch').value = "";
                            sel_tr.querySelector('.useradd_inp_sch_student_key').value = rData[0].id;
                            sel_tr.querySelector('.useradd_inp_sel_student').value = rData[0].student_name;
                        }
                        else{
                            //결과가 여러개이면 루프 돌려서 넣어주기
                            for(let i=0; i<rData.length; i++){
                                const row = rData[i];
                                const clone_tr = sch_tr.cloneNode(true);
                                clone_tr.hidden = false;
                                clone_tr.querySelector('.inp_student_key').value = row.id;
                                clone_tr.querySelector('.td_student_name').innerText = row.student_name;
                                clone_tr.querySelector('.td_student_tel').innerText = row.student_phone;
                                clone_tr.querySelector('.td_school').innerText = row.school_name;
                                clone_tr.querySelector('.td_grade').innerText = row.grade;
                                clone_tr.setAttribute('onclick', 'useraddSelectStudent(this)');
                                sch_div.querySelector('#useradd_tby_studentlist').appendChild(clone_tr);
                            }
                            sch_div.querySelector('#useradd_tb_studentlist').hidden = false;
                            sch_div.querySelector('#useradd_tb_parentlist').hidden = true;
                            sch_div.hidden = false;
                        }
                    }
                }
            }
            else{
                alert('등록된 회원이 없습니다.');
                return;
            }
        });
    }

    //학생-학부모 검색 창 닫는 부분
    function useraddUserSearchClose(){
        document.querySelector('#useradd_div_user_search').hidden = true;
    }

    //검색 후 선택한 학생 정보 넣기
    function useraddSelectStudent(nThis){
        const sel_btn = document.querySelector('#useradd_div_userInfo .searchUser');
        const sel_tr = sel_btn.closest('tr');

        // console.log(nThis);

        sel_tr.querySelector('.useradd_inp_sch_student_key').value = nThis.querySelector('.inp_student_key').value;
        sel_tr.querySelector('.useradd_inp_sel_student').value = nThis.querySelector('.td_student_name').innerText;
        sel_tr.querySelector('.useradd_inp_sch').value = "";

        sel_btn.classList.remove('searchUser');
        useraddUserSearchClose();
    }

    //검색 후 선택한 부모 정보 넣기
    function useraddSelectParent(nThis){
        const sel_btn = document.querySelector('#useradd_div_userInfo .searchUser');
        const sel_tr = sel_btn.closest('tr');

        sel_tr.querySelector('.useradd_inp_sch_parent_key').value = nThis.querySelector('.inp_parent_key').value;
        sel_tr.querySelector('.useradd_inp_sel_parent').value = nThis.querySelector('.td_parent_name').innerText;
        sel_tr.querySelector('.useradd_inp_sch').value = "";

        sel_btn.classList.remove('searchUser');
        useraddUserSearchClose();

    }

    //추가 등록 div 삭제
    function useraddAddDivRemove(nThis){
        const sel_div = nThis.closest('div').closest('div');
        sel_div.remove();
    }

    //엑셀 업로드 팝업 닫기
    function useraddExcelPop(type){
        useraddExcelPopReset();
        if(type == 'close'){
            document.querySelector('#useradd_div_excel_pop').hidden = true;
            document.querySelector('#useradd_div_user_add_main').hidden = false;
        }
        else if(type == 'open'){
            document.querySelector('#useradd_div_excel_pop').hidden = false;
            document.querySelector('#useradd_div_user_add_main').hidden = true;
        }
    }
    //엑셀불러오기 input 파일 및 리스트 초기화
    function useraddFileDelete(){
        const sel_inp = document.querySelector('#useradd_inp_excelfile');
        sel_inp.value = '';
        sel_inp.files.remove;

        document.querySelector('.sp_select_excel_name').innerHTML = '';

        //리스트 테이블 초기화
        let sel_table = document.querySelector('#useradd_tby_excel_list');
        let cloneTr = document.querySelector('#useradd_tby_excel_list .useradd_tr_excel_list_copy').cloneNode(true);
        sel_table.innerHTML = '';
        sel_table.appendChild(cloneTr);
    }

    //엑셀파일 불러오기
    function useraddReadExcel(){

        //파일 경로 가져오기
        let filePath = document.querySelector('#useradd_inp_excelfile').value;
        //경로가 없으면 input 초기화 후 종료
        if(filePath == null || filePath == undefined || filePath.length < 1){
            useraddFileDelete();
            document.querySelector('.sp_select_excel_name').innerHTML = '';
            return;
        }

        //파일 경로로 파일명 가져오기
        let filePathSplit = filePath.split('\\');
        let fileName = filePathSplit[filePathSplit.length - 1];
        document.querySelector('.sp_select_excel_name').innerHTML = fileName;

        //리스트 테이블 초기화
        let sel_table = document.querySelector('#useradd_tby_excel_list');
        let cloneTr = document.querySelector('#useradd_tby_excel_list .useradd_tr_excel_list_copy').cloneNode(true);
        sel_table.innerHTML = '';
        sel_table.appendChild(cloneTr);

        //소속 그룹별로 카운트 넣는 배열
        let groupArr = {};

        //엑셀에서 파일 읽는 부분
        let input = event.target;
        let reader = new FileReader();
        reader.onload = function () {
            let data = reader.result;
            let workBook = XLSX.read(data, { type: 'binary' });

            var sheet1 = workBook.SheetNames[0];
            let rows = XLSX.utils.sheet_to_json(workBook.Sheets[sheet1]);
            if(rows.length > 0){
                for(let i=0; i<rows.length; i++){
                    let excelTr = cloneTr.cloneNode(true);
                    excelTr.classList.remove('useradd_tr_excel_list_copy');
                    excelTr.classList.add('useradd_tr_excel_list');
                    excelTr.querySelector('.td_excel_seq').innerHTML = i+1;

                    let inRegion = false;
                    for(let r=0; r<regionlist.length; r++){
                        if(regionlist[r].region_name == rows[i]['소속']){
                            inRegion = true;
                            excelTr.querySelector('.td_excel_user_region_code').value = regionlist[r].id;
                            excelTr.querySelector('.td_excel_user_area').value = regionlist[r].area;
                            break;
                        }
                    }
                    if(!inRegion){
                        excelTr.querySelector('.td_excel_user_group').style.color = 'red';
                    }else{
                        excelTr.querySelector('.td_excel_user_group').style.color = 'black';
                    }

                    let inGroup = false;
                    for(let g=0; g<usergrouplist.length; g++){
                        if(usergrouplist[g].group_name == (rows[i]['사용자그룹'] || '')){
                            inGroup = true;
                            excelTr.querySelector('.td_excel_user_group_seq').value = usergrouplist[g].id;
                            excelTr.querySelector('.td_excel_user_group_type').value = usergrouplist[g].group_type;
                            break;
                        }
                    }
                    if(!inGroup){
                        excelTr.querySelector('.td_excel_user_group').style.color = 'red';
                    }else{
                        excelTr.querySelector('.td_excel_user_group').style.color = 'black';
                    }

                    excelTr.querySelector('.td_excel_user_group').innerHTML = (rows[i]['사용자그룹'] || '');
                    excelTr.querySelector('.td_excel_region').innerHTML = (rows[i]['소속'] || '');
                    excelTr.querySelector('.td_excel_user_id').innerHTML = (rows[i]['아이디'] || '');
                    excelTr.querySelector('.td_excel_user_pw').innerHTML = (rows[i]['비밀번호'] || '');
                    excelTr.querySelector('.td_excel_user_name').innerHTML = (rows[i]['이름'] || '');
                    excelTr.querySelector('.td_excel_user_phone').innerHTML = (rows[i]['휴대폰번호'] || '');
                    excelTr.hidden = false;
                    sel_table.appendChild(excelTr);

                    if((rows[i]['사용자그룹'] || '') != ''){
                        if(groupArr[rows[i]['사용자그룹']] != undefined){
                            groupArr[rows[i]['사용자그룹']] += 1;
                        }
                        else{
                            groupArr[rows[i]['사용자그룹']] = 1;
                        }
                    }
                }

                let group_cnt_text = "";
                for (var key in groupArr) {
                    group_cnt_text = group_cnt_text + key + ' : <span style="color:blue">' + groupArr[key] + "명</span>";
                }

                document.querySelector('.useradd_sp_group_cnt').innerHTML = group_cnt_text;
            }
        };
        reader.readAsBinaryString(input.files[0]);
    }
    //임시아이디 발급
    function useraddTempID(){
        let sel_tr = document.querySelectorAll('#useradd_tby_excel_list .useradd_tr_excel_list');
        if(sel_tr == null || sel_tr == undefined || sel_tr.length < 1){
            alert('임시 아이디를 발급할 리스트가 없습니다.');
            return;
        }

        let dTime = '';

        let idx = 0;
        sel_tr.forEach(function(item){
            idx++;
            dTime = new Date();
            let req_time = dTime.getTime();
            req_time = (req_time.toString()).substr(-9);
            req_time += (Math.floor(window.performance.now())+"").substr(-2);
            let tempID = 'a'+req_time.toString()+idx.toString().padStart(4, "0");

            if(item.querySelector('.td_excel_user_id').innerHTML.length < 1 ){
                item.querySelector('.td_excel_user_id').innerHTML = tempID;
            }
        });
    }
    //엑셀로 불러온 리스트 값 체크
    function useraddExcelListCheck(){
        let sel_tr = document.querySelectorAll('#useradd_tby_excel_list .useradd_tr_excel_list');
        if(sel_tr == null || sel_tr == undefined || sel_tr.length < 1){
            alert('저장할 리스트가 없습니다.');
            return;
        }

        let isGroup = true;
        let idnullcnt = 0;
        //아이디가 없는 갯수 체크
        sel_tr.forEach(function(item){
            let grouptext = item.querySelector('.td_excel_user_group').innerHTML;
            let groupcolor = item.querySelector('.td_excel_user_group').style.color;
            if(grouptext != '' && groupcolor == 'red'){
                isGroup = false;
            }

            let idtext = item.querySelector('.td_excel_user_id').innerHTML;
            if(idtext == '' || idtext.replace(' ', '').length < 1){
                idnullcnt++;
            }
        });

        if(!isGroup){
            sAlert('','사용자 그룹이 없으면 등록이 불가합니다. 사용자 그룹을 확인해주세요');
            return;
        }

        const excelName = document.querySelector('#useradd_div_excel_pop .sp_select_excel_name').innerHTML;
        if(idnullcnt > 0){
            const msg = "<span style='color:blue'>"+excelName+"</span>의 <span style='color:blue'>"+idnullcnt+"</span>명에게 임시아이디가 발급됩니다. 저장하시겠습니까?";
            sAlert('', msg, '2', function() {
                useraddExcelListSave(sel_tr);
            });
        }
        else{
            useraddExcelListSave(sel_tr);
        }
    }
    //엑셀로 불러온 리스트 저장
    function useraddExcelListSave(sel_tr){
        console.log(sel_tr);
        const totalCnt = sel_tr.length;
        let count = 0;
        let success = 0;
        let fail = 0;
        let conn_user_id = '';
        sel_tr.forEach(function(item){
            const user_id = (item.querySelector('.td_excel_user_id').innerHTML || '');
            const group_seq = (item.querySelector('.td_excel_user_group_seq').value || '');
            const grouptype = (item.querySelector('.td_excel_user_group_type').value || '');
            const sido = (item.querySelector('.td_excel_user_area').value || '');
            const region = (item.querySelector('.td_excel_user_region_code').value || '');
            const team = '';
            const school_name = '';
            const grade = '';
            const ticket = '';
            const period_use = '';
            const user_pw = (item.querySelector('.td_excel_user_pw').innerHTML || '');
            const user_name = (item.querySelector('.td_excel_user_name').innerHTML || '');
            const user_rrn = '';
            const user_phone = (item.querySelector('.td_excel_user_phone').innerHTML || '');
            const user_email = '';
            const user_addr = '';

            const parameter = {
                user_id:user_id,
                group_seq:group_seq,
                grouptype:grouptype,
                sido:sido,
                region:region,
                team_code:team,
                school_name:school_name,
                grade:grade,
                ticket:ticket,
                period_use:period_use,
                user_pw:user_pw,
                user_name:user_name,
                user_rrn:user_rrn,
                user_phone:user_phone,
                user_email:user_email,
                user_addr:user_addr,
                num:count
            };

            const page = 'user/insert';
            queryUserAdd(page,parameter,function(result){
                count += 1;
                if(result == null || result.resultCode == null){
                    return;
                }
                if(result.resultCode == 'success'){
                    success += 1;
                    item.querySelector('.td_excel_status').innerHTML = '성공';
                     item.querySelector('.td_excel_status').style.color = 'black';
                }else{
                    fail += 1;
                    item.querySelector('.td_excel_status').innerHTML = '실패';
                    item.querySelector('.td_excel_status').style.color = 'red';
                }

                if(totalCnt == count){
                    sAlert("", '저장되었습니다.\n 성공 : ' + success + '실패 : ' + fail);
                }
            });
        });
    }
    //저장한 엑셀 리스트 출력 기능
    function useraddExcelListPrint(){
        var printContent = document.getElementById("useradd_div_excel_list").cloneNode(true);
        var winpopup1 = window.open();
        winpopup1.document.open();
        winpopup1.document.write('<style>');
        winpopup1.document.write('body, td {font-falmily: Verdana; font-size: 10pt;} tr{border:1px solid black;}');
        winpopup1.document.write('</style>');
        winpopup1.document.write(printContent.innerHTML);
        winpopup1.document.write('</body></html>');
        winpopup1.document.close();
        winpopup1.print();
        winpopup1.close();
    }
    //일괄저장 div 초기화
    function useraddExcelPopReset(){
        const copy_div = document.querySelector('#useradd_tby_excel_list .useradd_tr_excel_list_copy').cloneNode(true);
        document.querySelector('#useradd_tby_excel_list').innerHTML = '';
        document.querySelector('#useradd_tby_excel_list').appendChild(copy_div);
        document.querySelector('.sp_select_excel_name').innerHTML = '';
        document.querySelector('.useradd_sp_group_cnt').innerHTML = '';
    }
    //일수 구하기
    function useraddCalDiff(){
        const date1 = new Date(document.querySelector('#useradd_inp_start_date').value);
        const date2 = new Date(document.querySelector('#useradd_inp_end_date').value);
        const diffDate = date1.getTime() - date2.getTime();
        const diff_date_result = String(Math.abs(diffDate / (1000 * 60 * 60 * 24)));
        if(isNaN(diff_date_result)) document.querySelector('#useradd_sp_date_diff').innerHTML = '';
        else document.querySelector('#useradd_sp_date_diff').innerHTML = diff_date_result+'일';

    }
    //이용권 선택 시
    function useraddClickTicket(nThis){
        const ticket_seq = nThis.getAttribute('ticket_seq');
        const ticket_name = nThis.innerText;

        const sel_div = nThis.closest('div');
        // sel_div.querySelector('#useradd_inp_ticket').value = ticket_name;
        // sel_div.querySelector('.inp_ticket_seq').value = ticket_seq;
    }

    // 상용자 등록 닫기시 클리어
    function useraddInfoClear(){
        const div_userInfoSub = document.querySelectorAll('.useradd_div_userInfoSub');

        document.querySelector('#useradd_inp_user_key').value = '';
        document.querySelector('#useradd_inp_userId').value = '';
        document.querySelector('#useradd_sel_group_name').selectedIndex = 0;
        document.querySelector('#useradd_sel_sido').selectedIndex = 0;
        document.querySelector('#useradd_sel_region').selectedIndex = 0;
        document.querySelector('#useradd_sel_team').selectedIndex = 0;
        document.querySelector('#useradd_inp_schoolName').value = '';
        document.querySelector('#useradd_sel_grade').value = '';
        // document.querySelector('#useradd_inp_ticket').value = '';
        document.querySelector('#useradd_inp_pass').value = '';
        document.querySelector('#useradd_inp_passChk').value = '';
        document.querySelector('#useradd_inp_userName').value = '';
        document.querySelector('#useradd_inp_userRrn').value = '';
        document.querySelector('#useradd_inp_userPhone').value = '';
        document.querySelector('#useradd_inp_userEmail').value = '';
        document.querySelector('#useradd_inp_userAddr').value = '';
        document.querySelector('.useradd_inp_sch_student_key').value = '';
        document.querySelector('.useradd_inp_sch_parent_key').value = '';
        document.querySelector('.useradd_inp_sel_student').value = '';
        document.querySelector('.useradd_inp_sel_parent').value = '';
        document.querySelector('#useradd_inp_start_date').value = '';
        document.querySelector('#useradd_inp_end_date').value = '';
        document.querySelector('#useradd_sel_goods').selectedIndex = 0;
        document.querySelector('#useradd_sp_date_diff').innerHTML = '';
        document.querySelector('#useradd_btn_user_id_check').disabled = false;
        document.querySelector('#useradd_inp_userId').disabled = false;
        document.querySelector('#useradd_inp_user_type').value = '';
        document.querySelector('#useradd_btn_user_add').hidden = false;

    }

    // 사용자 상세 수정으로 들어왔을 때
    function useraddUserDetailSelect(tr){
        const user_key = tr.querySelector('.inp_user_key').value;
        let group_seq = [tr.querySelector('.group_seq').value];
        document.querySelector('#useradd_sel_group_name').value = group_seq||'';
        if((group_seq||'') != ''){
            document.querySelector('#useradd_sel_group_name').onchange();
        }
            // const useradd_sel_group_name = document.querySelector('#useradd_sel_group_name');
            // const selectedOption = useradd_sel_group_name.options[useradd_sel_group_name.selectedIndex];
        const grouptype = tr.querySelector('.inp_user_type').value;
        document.querySelector('#useradd_btn_user_add').hidden = true;

        // 수정시 사용자 그룹 변경 불가를 위해 타입 저장
        document.querySelector('#useradd_inp_user_type').value = grouptype;

        const page = "/manage/userlist/"+grouptype+"/select";
        if(group_seq.length == 1 && group_seq[0] == '') group_seq = '';
        const parameter = {
            main_code:'',
            group_seq:group_seq,
            id:user_key
        }
        queryFetch(page, parameter, function(result){
            if(result.resultCode == 'success'){
                const r_data = result.resultData[0];
                const div_userInfo = document.querySelector('#useradd_div_userInfo');
                document.querySelector('#useradd_btn_user_id_check').disabled = true;
                document.querySelector('#useradd_inp_userId').disabled = true;

                //학생과 학부모만 같은 변수이름이 있을수 있으므로 둘만 비교.
                document.querySelector('#useradd_inp_userId').value =
                    grouptype == 'student' ? r_data.student_id : r_data.parent_id || r_data.teach_id;
                document.querySelector('#useradd_sel_sido').value = r_data.area || '';


                document.querySelector('#useradd_inp_userName').value =
                    grouptype == 'student' ? r_data.student_name : r_data.parent_name || r_data.teach_name;
                document.querySelector('#useradd_inp_user_key').value = user_key || '';
                document.querySelector('#useradd_sel_grade').value = r_data.grade || '';
                document.querySelector('#useradd_inp_userRrn').value = r_data.rrn || '';

                document.querySelector('#useradd_inp_userPhone').value =
                    grouptype == 'student' ?  r_data.student_phone : r_data.parent_phone || r_data.teach_phone;

                document.querySelector('#useradd_inp_userEmail').value =
                    r_data.student_email || r_data.parent_email || r_data.teach_email || '';

                document.querySelector('#useradd_inp_userAddr').value = r_data.student_address || r_data.parent_address || r_data.teach_address || '';

                if(grouptype == 'student'){
                    document.querySelector('#useradd_sel_sido').onchange();
                    div_userInfo.querySelector('.useradd_inp_sel_parent').value = r_data.parent_name;
                    div_userInfo.querySelector('.useradd_inp_sch_parent_key').value = r_data.parent_id;
                    document.querySelector('#useradd_inp_schoolName').value = r_data.school_name;
                    document.querySelector('#useradd_sel_goods').value = r_data.goods_seq;
                    document.querySelector('#useradd_inp_start_date').value = r_data.goods_start_date;
                    document.querySelector('#useradd_inp_start_date').onchange();
                    document.querySelector('#useradd_inp_end_date').value = r_data.goods_end_date;
                }
                else if(grouptype == 'parent'){
                    document.querySelector('#useradd_sel_sido').onchange();
                    div_userInfo.querySelector('.useradd_inp_sel_student').value = r_data.student_name;
                    div_userInfo.querySelector('.useradd_inp_sch_student_key').value = r_data.student_seq;
                }
                else if(grouptype == 'teacher'){
                    useraddSelectSido(document.querySelector('#useradd_sel_sido'), function(){
                        document.querySelector('#useradd_sel_region').value = r_data.region_seq || '';
                        useraddSelectRegion(document.querySelector('#useradd_sel_region'), r_data.team_code || '');
                    });
                }
            }
        });
    }

    function useraddisEdit(){
        if(document.querySelector('#useradd_inp_user_key').value != ''){
            return true;
        }
        else{
            return false;
        }
    }

    //주소 찾기
    var element_wrap = document.getElementById('address_wrap');
    function execDaumPostcode() {
        // 현재 scroll 위치를 저장해놓는다.
            var currentScroll = Math.max(document.body.scrollTop, document.documentElement.scrollTop);
            new daum.Postcode({
                oncomplete: function(data) {
                    var addr = ''; // 주소 변수
                    var extraAddr = ''; // 참고항목 변수
                    //사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                    if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                        addr = data.roadAddress;
                    } else { // 사용자가 지번 주소를 선택했을 경우(J)
                        addr = data.jibunAddress;
                    }

                    if(data.userSelectedType === 'R'){
                        // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                        // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                        if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                            extraAddr += data.bname;
                        }
                        // 건물명이 있고, 공동주택일 경우 추가한다.
                        if(data.buildingName !== '' && data.apartment === 'Y'){
                            extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                        }
                        // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                        if(extraAddr !== ''){
                            extraAddr = ' (' + extraAddr + ')';
                        }
                        // 조합된 참고항목을 해당 필드에 넣는다.
                        // extraAddr;
                    } else {
                    }

                    // 우편번호와 주소 정보를 해당 필드에 넣는다.
                    // data.zonecode;
                    // addr;
                    // 커서를 상세주소 필드로 이동한다.
                    document.querySelector("#useradd_inp_userAddr").value = addr;
                    document.querySelector("#useradd_inp_userAddr").focus();

                    // iframe을 넣은 element를 안보이게 한다.
                    // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                    element_wrap.style.display = 'none';

                    // 우편번호 찾기 화면이 보이기 이전으로 scroll 위치를 되돌린다.
                    document.body.scrollTop = currentScroll;
                },
                // 우편번호 찾기 화면 크기가 조정되었을때 실행할 코드를 작성하는 부분. iframe을 넣은 element의 높이값을 조정한다.
                onresize : function(size) {
                    let sheight = size.height;
                    if(size.height > 400)
                        sheight = 400;
                    element_wrap.style.height = sheight+'px';
                },
                width : '100%',
                height : '100%'
            }).embed(element_wrap);

            // iframe을 넣은 element를 보이게 한다.
            element_wrap.style.display = 'block';
        }
        //주소 찾기 접기.
        function foldDaumPostcode() {
        // iframe을 넣은 element를 안보이게 한다.
            element_wrap.style.display = 'none';
        }

</script>
