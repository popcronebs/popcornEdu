@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
    소속 / 팀 관리
@endsection

{{-- 네브바 체크 --}}
@section('system_management')
@endsection
@section('systemteam')
    active
@endsection
{{-- 받은 변수 : 배열 $address_sido / json $address_gu, $address_dong --}}

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    <style>
        .hpx-40{
            height: 40px;
        }
        .hpx-30{
            height: 30px;
        }
        .wpx-120{
            width: 120px;
        }
    </style>
    <div class="col-12 pe-3 ps-3 position-relative">
        <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
            <h4>사용자 소속 / 팀 목록</h4>
            <div>
                <button class="btn btn-primary me-2" onclick="systemteamTeamAdd('insert')">신규 소속(팀) 등록</button>
            </div>
        </div>
        {{-- 담당지역 선택 --}}
        <div class="d-flex w-100">
            <div class="pe-5 wpx-120">
                담당지역<br>(대분류)
            </div>
            <select name="" id="systemteam_sel_sido" class="col-2 rounded form-select-sm hpx-40" onchange="systemteamSelectGu(this);">
                <option value="">담당지역선택</option>
                @foreach ($address_sido as $item)
                    <option value="{{ $item['sido'] }}">{{ $item['sido'] }}</option>
                @endforeach
            </select>

            <div class="pe-5 ps-5">
                담당구역<br>(중분류)
            </div>
            <select name="" id="systemteam_sel_gu" class="col-2 rounded form-select-sm hpx-40" onchange="systemteamSelectDong(this)">
                <option value="">담당구역선택</option>
            </select>
            {{-- 구 배열을 미리 넣어넣고 클론으로 잘라서 구 select에 넣기위해 구현. --}}
            <select id="systemteam_sel_gu_clone" hidden>
                @php
                    $currentSido = null;
                @endphp

                @foreach ($address_gu as $item)
                    @if ($currentSido != $item['sido'])
                        @if (!is_null($currentSido))
                            </optgroup>
                        @endif
                        @php
                            $currentSido = $item['sido'];
                        @endphp
                        <optgroup label="{{ $currentSido }}">
                    @endif
                    <option value="{{ $item['gu'] }}">{{ $item['gu'] }}</option>
                @endforeach

                @if (!is_null($currentSido))
                    </optgroup>
                @endif
            </select>
        </div>
        {{-- 담당구역 선택 --}}
        <div class="d-flex w-100 mt-2">
            <div class="pe-5 wpx-120">
                담당구역<br>(소분류)
            </div>
            <input type="checkbox" class="btn-check" id="systemteam_btncheck1" autocomplete="off" onchange="systemteamClickDong(this);">
            <label class="btn btn-outline-primary hpx-40 me-1" for="systemteam_btncheck1">전체</label>
            <div class="flex-wrap btn-group" role="group" aria-label="Basic checkbox toggle button group" id="systemteam_div_dong" style=" max-width: 1340px; ">
            </div>
            <div id="systemteam_div_dong_clone">
                <input type="checkbox" class="copy_inp_dong1 btn-check" id="" autocomplete="off" hidden onclick="systemteamClickDong(this);">
                <label class="copy_inp_dong2 btn btn-outline-primary hpx-40" for="" hidden>#</label>
            </div>
        </div>
        {{-- 선택된 지역 확인 --}}
        <div class="d-flex w-100 mt-2">
            <div class="pe-5 wpx-120">
                선택지역<br>(최대5개)
            </div>
            <div class="d-flex gap-2" id="systemteam_div_sel_dong">
                <button class="copy_btn_sel_dong btn btn-outline-success hpx-40" onclick="systemteamRemoveBtn(this)" hidden>
                    <span class="dong_text">#선택</span>
                    <span class="text-danger">X</span>
                    <input type="hidden" class="sido">
                    <input type="hidden" class="gu">
                    <input type="hidden" class="dong">
                </button>
            </div>
        </div>
        {{-- 입력 검색 기능 --}}
        <div class="d-flex mt-2 mb-2">
            <select name="" id="systemteam_sel_search_type" class="col-2 rounded form-select-sm hpx-40 wpx-120">
                {{-- 총괄매니저 이름, 소속명, 팀명 --}}
                <option value="general_manager_name">총괄매니저 이름</option>
                <option value="region_name">소속명</option>
                <option value="team_name">팀명</option>
            </select>
            {{-- 이름 검색 input 과 button 추가 --}}
            <div class="d-flex w-100 ms-3 gap-2">
                <input type="text" class="form-control form-control-sm hpx-40 w-25"
                placeholder="이름 검색" id="systemteam_inp_search_name" onkeyup="if(event.keyCode == 13) systemteamGroupList();">
                <button class="btn btn-outline-primary hpx-40" onclick="systemteamGroupList();">검색</button>
            </div>
        </div>
        {{-- 소속 / 팀 리스트 테이블 --}}
        <div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center align-middle" onclick="event.stopPropagation();this.querySelector('input').click()">
                            <input type="checkbox" class="checkbox" id="btncheck2" autocomplete="off"
                            onclick="systemteamAllChkClick(this);">
                        </th>
                        <th class="text-center align-middle">소속</th>
                        <th class="text-center align-middle">총괄</th>
                        <th class="text-center align-middle">팀명</th>
                        <th class="text-center align-middle">담당지역</th>
                        <th class="text-center align-middle">담당구역(중분류)</th>
                        <th class="text-center align-middle" style="width:35%">담당구역(소분류)</th>
                        <th class="text-center align-middle">소속인원</th>
                        <th class="text-center align-middle">학생수</th>
                        <th class="text-center align-middle">생성일</th>
                        <th class="text-center align-middle"></th>
                    </tr>
                </thead>
                <tbody id="systemteam_tby_team">
                    <tr class="copy_tr_system_team" hidden onclick="event.stopPropagation();this.querySelector('input').click()">
                        <td class="text-center align-middle" onclick="event.stopPropagation();this.querySelector('input').click()">
                            <input type="checkbox" class="checkbox" autocomplete="off" onclick="event.stopPropagation()">
                        </td>
                        <td class="text-center align-middle region_name">#소속</td>
                        <td class="text-center align-middle general_manager_name">#총괄</td>
                        <td class="text-center align-middle team_name">#팀명</td>
                        <td class="text-center align-middle sido">#담당지역</td>
                        <td class="text-center align-middle gu">#담당구역(중분류)</td>
                        <td class="text-center align-middle dong">#담당구역(소분류)</td>
                        <td class="text-center align-middle team_tr_cnt">#소속인원</td>
                        <td class="text-center align-middle team_st_cnt">#학생수</td>
                        <td class="text-center align-middle created_at">#생성일</td>
                        <td class="text-center align-middle">
                            <button class="btn btn-outline-primary btn-sm"
                            onclick="systemteamTeamAdd('edit', this)">수정</button>
                        </td>
                        <input type="hidden" class="team_seq">
                        <input type="hidden" class="team_code">
                        <input type="hidden" class="region_seq">
                    </tr>

                </tbody>
            </table>
        </div>
        {{-- 하단 기능 버튼 --}}
        <div class="d-flex mt-2 gap-4">
            {{-- SMS / PUSH 전송 , 선택 팀 공지사항 등록, 선택 팀 통합 --}}
            <button class="btn btn-outline-secondary col-2">SMS / PUSH 전송</button>
            <button class="btn btn-outline-secondary col-2">선택 팀 공지사항 등록</button>
            <button class="btn btn-outline-secondary col-2" onclick="systemteamTeamMerge();">선택 팀 통합</button>
        </div>

        <div id="systemteam_div_team_add" class="position-absolute w-100 h-100 bg-white ps-3" style="top: 0; left: 0; z-index:3"
        hidden>
            {{-- $address_sido, $address_gu를 include에 넘거준다. --}}
            @include('admin.admin_system_team_add_alayout', ['address_sido' => $address_sido, 'address_gu' => $address_gu])
        </div>
        <div id="systemteam_div_team_merge" hidden
        class="border p-2 col-lg-2 row position-fixed top-50 start-50 translate-middle bg-white"
        style="z-index:2;min-width:340px">
            <div class="div_team_name" style="font-weight:900">
            </div>
            <div class="mt-2">
                통합하시겠습니까? 담담구역정보와<br>소속인원이 통합된 팀으로 이관됩니다.
            </div>
            <div>
                <div class="mt-2 d-flex gap-2">
                    <input type="text" id="systemteam_inp_team_merge"
                    class="form-control border border-primary rounded"
                    placeholder="통합할 팀명 입력" onkeyup="this.classList.remove('text-primary')">
                    <button class="btn btn-primary rounded col-5" onclick="systemteamAddTeamNameChk('#systemteam_inp_team_merge')">팀명 확인</button>
                </div>
                <div class="mt-3 d-flex justify-content-center gap-3">
                    <button class="btn btn-outline-secondary" onclick="systemteamTeamMergeClose();">취소</button>
                    <button class="btn btn-primary" onclick="systemteamTeamMergeInsert();">확인</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        const address_dong = @json($address_dong);

        //담당자 지역 선택 후 구역 가져오기.
        function systemteamSelectGu(vthis){
            const sel_sido = vthis.value;
            //담당구역 중분류 선택 초기화
            systemteamGuReset();
            //담당구역 소분류 선택 초기화
            systemteamDongReset();

            if(sel_sido == ''){
                //담당구역 소분류 선택 초기화
                return;
            }
            //클론으로 잘라서 구 select에 넣기위해 구현.
            const optgroup = document.querySelector('#systemteam_sel_gu_clone optgroup[label="'+sel_sido+'"]').cloneNode(true);
            const systemteam_sel_gu = document.querySelector('#systemteam_sel_gu');
            //담당구역 중분류 option 추가
            systemteam_sel_gu.appendChild(optgroup);
        }

        //담당자 구역 중분류 초기화
        function systemteamGuReset(){
            const systemteam_sel_gu = document.querySelector('#systemteam_sel_gu');
            systemteam_sel_gu.innerHTML = '';
            //담당구역선택 option 추가
            const option = document.createElement('option');
            option.value = '';
            option.innerText = '담당구역선택';
            systemteam_sel_gu.appendChild(option);
        }

        //담당구역 중분류(구) 선택시 소분류(동면리) 가져오기
        function systemteamSelectDong(vthis){

            const sel_sido = document.querySelector('#systemteam_sel_sido').value;
            const sel_gu = vthis.value;
            //전체 버튼 정의
            const systemteam_btncheck1 = document.querySelector('#systemteam_btncheck1');
            systemteam_btncheck1.checked = false;
            //담당구역 소분류 선택 초기화
            systemteamDongReset();

            //address_dong 에서 sido, gu 가 일치하는 dong 가져오기
            const dong = address_dong.filter(function(item){
                return item.sido == sel_sido && item.gu == sel_gu;
            });
            const systemteam_div_dong = document.querySelector('#systemteam_div_dong');
            const systemteam_div_dong_clone = document.querySelector('#systemteam_div_dong_clone');
            //담당구역 소분류 복사 추가
            for(let i = 0; i < dong.length; i++){
                const clone_dong1 = systemteam_div_dong_clone.querySelector('.copy_inp_dong1').cloneNode(true);
                const clone_dong2 = systemteam_div_dong_clone.querySelector('.copy_inp_dong2').cloneNode(true);
                //클래스 삭제후 copy 없앤 클르스 추가
                clone_dong1.classList.remove('copy_inp_dong1');
                clone_dong2.classList.remove('copy_inp_dong2');
                clone_dong1.classList.add('inp_dong1');
                clone_dong2.classList.add('inp_dong2');

                //id, for, value, text, hidden 추가
                clone_dong1.id = 'systemteam_inp_dong1_'+i;
                clone_dong2.id = 'systemteam_inp_dong2_'+i;
                clone_dong2.setAttribute('for', 'systemteam_inp_dong1_'+i);
                clone_dong1.value = dong[i].dong;
                clone_dong2.innerText = dong[i].dong;
                clone_dong1.hidden = false;
                clone_dong2.hidden = false;
                systemteam_div_dong.appendChild(clone_dong1);
                systemteam_div_dong.appendChild(clone_dong2);
            }
            //전체 버튼 클릭
            systemteam_btncheck1.click();
        }

        //담당구역 소분류 초기화
        function systemteamDongReset(){
            const systemteam_div_dong = document.querySelector('#systemteam_div_dong');
            systemteam_div_dong.innerHTML = '';
        }

        //담당구역 소분류 클릭 선택
        function systemteamClickDong(vthis){
            const systemteam_div_sel_dong = document.querySelector('#systemteam_div_sel_dong');
            //vthis 와 연결된 label 의 text 가져오기
            let sel_dong = vthis.nextElementSibling.innerText;
            const sel_dong2 = sel_dong+"";
            if(sel_dong == '전체'){
                const sel_gu = document.querySelector('#systemteam_sel_gu').value;
                sel_dong = sel_gu + '전체';
            }

            //vthis 이 체크 되었는지 확인
            if(vthis.checked){
                //btn_sel_dong의 개수가 5개 이상이면 선택 불가
                if(systemteam_div_sel_dong.querySelectorAll('.btn_sel_dong').length >= 5){
                    alert('최대 5개까지 선택 가능합니다.');
                    vthis.checked = false;
                    return;
                }
                //담당구역 선택 지역 추가
                const btn_sel_dong = systemteam_div_sel_dong.querySelector('.copy_btn_sel_dong').cloneNode(true);
                btn_sel_dong.classList.remove('copy_btn_sel_dong');
                btn_sel_dong.classList.add('btn_sel_dong');
                btn_sel_dong.querySelector('.dong_text').innerText = sel_dong;
                btn_sel_dong.querySelector('.sido').value = document.querySelector('#systemteam_sel_sido').value;
                btn_sel_dong.querySelector('.gu').value = document.querySelector('#systemteam_sel_gu').value;
                btn_sel_dong.querySelector('.dong').value = sel_dong2;
                btn_sel_dong.hidden = false;
                systemteam_div_sel_dong.appendChild(btn_sel_dong);
            }else{
                //선택지역 중에 sel_dong와 같은 text를 가진 요소를 가져와서 삭제
                const btn_sel_dong = systemteam_div_sel_dong.querySelectorAll('.btn_sel_dong .dong_text');
                for(let i = 0; i < btn_sel_dong.length; i++){
                    if(btn_sel_dong[i].innerText == sel_dong){
                        btn_sel_dong[i].parentElement.remove();
                    }
                }

            }
        }

        //담당구역 선택 지역 삭제
        function systemteamRemoveBtn(vthis){
            //담당구역 중에 삭제하는 요소의 text를 가진 요소를 가져와서 체크 해제
            let dong_text = vthis.querySelector('.dong_text').innerText;
            //전체가 들어가있으면 '전체' 로 dong_text 수정
            //단 전체 앞 단어가 담당구역의 value와 같아야한다.
            //전체 버튼을 체크해제해주기위함.
            if(dong_text.indexOf('전체') != -1){
                const sel_gu = document.querySelector('#systemteam_sel_gu').value;
                if(dong_text == sel_gu + '전체'){
                    const systemteam_btncheck1 = document.querySelector('#systemteam_btncheck1');
                    systemteam_btncheck1.checked = false;
                }
            }
            const systemteam_div_dong = document.querySelectorAll('#systemteam_div_dong .inp_dong1');
            for(let i = 0; i < systemteam_div_dong.length; i++){
                if(systemteam_div_dong[i].nextElementSibling.innerText == dong_text){
                    systemteam_div_dong[i].checked = false;
                }
            }
            vthis.remove();
        }

        //신규 소속(팀) 등록 / 수정 창 열기
        function systemteamTeamAdd(type, vthis){
            //type에 따라 seq전달 진행 예정
            if(type == 'edit'){
                const tag_tr = vthis.closest('tr');
                const team_code = tag_tr.querySelector('.team_code').value;
                //수정일경우 team_code 코드 삽입.
                const systemteamadd_inp_team_code = document.querySelector('#systemteamadd_inp_team_code');
                systemteamadd_inp_team_code.value = team_code;

                //팀 코드로 수정할 정보를 불러와서 삽입.
                systemteamAddGetEditInfo(team_code, tag_tr);
            }
            const systemteam_div_team_add = document.querySelector('#systemteam_div_team_add');
            systemteam_div_team_add.hidden = false;

        }

        //소속 팀 목록 불러오기.
        function systemteamGroupList(){
            //선택지역 가져오기 btn_sel_dong 를 모두 가져와서 큰구분자 | 작은 구분자 , 로 구분
            const btn_sel_dong = document.querySelectorAll('#systemteam_div_sel_dong .btn_sel_dong');
            let area_list = "";
            for(let i = 0; i < btn_sel_dong.length; i++){
                const sido = btn_sel_dong[i].querySelector('.sido').value;
                const dong = btn_sel_dong[i].querySelector('.dong').value;
                const gu = btn_sel_dong[i].querySelector('.gu').value;
                if(i != 0) area_list += '|';
                area_list += sido + ',' + gu + ',' + dong;
            }
            //구(중분류) 선택이 안되어 있으면 알림 후 리턴
            const sel_sido = document.querySelector('#systemteam_sel_sido');
            // const sel_gu = document.querySelector('#systemteam_sel_gu');
            if(btn_sel_dong.length == 0){
                if(sel_sido.value == ''){
                    sAlert('','담당구역(대,중) 선택해주세요.');
                    return;
                }else{
                    area_list = sel_sido.value+',,';
                }
            }

            const search_type = document.querySelector('#systemteam_sel_search_type').value;
            const search_name = document.querySelector('#systemteam_inp_search_name').value;
            const page = "/manage/systemteam/teamgroup/select"
            const parameter = {
                area_list : area_list,
                search_type : search_type,
                search_name : search_name,
            };
            queryFetch(page, parameter, function(result) {
                const systemteam_tby_team = document.querySelector('#systemteam_tby_team');
                const copy_tr_system_team = systemteam_tby_team.querySelector('.copy_tr_system_team').cloneNode(true);
                systemteam_tby_team.innerHTML = '';
                systemteam_tby_team.appendChild(copy_tr_system_team);

                if ((result.resultCode || '') == 'success') {
                    for(let i = 0; i < result.resultData.data.length; i++){
                        const r_data = result.resultData.data[i];
                        const clone_tr = copy_tr_system_team.cloneNode(true);
                        clone_tr.querySelector('.region_name').innerText = r_data.region_name;
                        clone_tr.querySelector('.general_manager_name').innerHTML = r_data.general_manager_name+'<br>('+r_data.general_manager_id+')';
                        clone_tr.querySelector('.team_name').innerText = r_data.team_name;
                        clone_tr.querySelector('.sido').innerText = r_data.sido;
                        clone_tr.querySelector('.gu').innerText = r_data.gu;
                        clone_tr.querySelector('.dong').innerText = r_data.dong;
                        clone_tr.querySelector('.team_tr_cnt').innerText = r_data.team_tr_cnt;
                        clone_tr.querySelector('.team_st_cnt').innerText = r_data.team_st_cnt;
                        clone_tr.querySelector('.created_at').innerText = r_data.created_at.substr(2,8);
                        clone_tr.querySelector('.team_seq').value = r_data.team_seq;
                        clone_tr.querySelector('.team_code').value = r_data.team_code;
                        clone_tr.querySelector('.region_seq').value = r_data.region_seq;
                        clone_tr.hidden = false;
                        systemteam_tby_team.appendChild(clone_tr);
                    }
                }
            });
        }

        //소속/팀 목록 전체 체크 클릭 태그에 따라 변환
        function systemteamAllChkClick(vthis){
            event.stopPropagation();
            const is_checked = vthis.checked;
            const systemteam_tby_team = document.querySelector('#systemteam_tby_team');
            const checkbox = systemteam_tby_team.querySelectorAll('.checkbox');
            for(let i = 0; i < checkbox.length; i++){
                checkbox[i].checked = is_checked;
            }
        }

        //[선택 팀 통합] 창 열기
        function systemteamTeamMerge(){
            //통합할 팀들이 같은 소속인지 확인
            //아니면 return
            if(!systemteamChkSameRegion()) return;

            //체크된 팀들의 이름과 team_code 를 가지고온다.
            const team_info = systemteamChkTeamInfo();
            //가져온 팀 정보만큼 div_team_name안에 team_name의 Div를 만들어준다.
            div_team_name = document.querySelector('#systemteam_div_team_merge .div_team_name');
            div_team_name.innerHTML = '';
            for(let i = 0; i < team_info.length; i++){
                const div = document.createElement('div');
                div.innerText = team_info[i].region_name + ' ' + team_info[i].team_name;
                div_team_name.appendChild(div);
            }

            const systemteam_div_team_merge = document.querySelector('#systemteam_div_team_merge');
            systemteam_div_team_merge.hidden = false;
        }

        //[선택 팀 통합] 저장.
        function systemteamTeamMergeInsert(){
            const systemteam_inp_team_merge = document.querySelector('#systemteam_inp_team_merge');
            const team_name = systemteam_inp_team_merge.value;
            let team_code = '';

            //통합할 팀들이 같은 소속인지 확인
            //아니면 return
            if(!systemteamChkSameRegion()) return;
            //체크된 팀들의 이름과 team_code 를 가지고온다.
            const team_info = systemteamChkTeamInfo();

            for(let i = 0; i < team_info.length; i++){
                if(team_code == ''){
                    team_code = team_info[i].team_code;
                }else{
                    team_code += ','+team_info[i].team_code;
                }
            }

            //team_name 이 비어있으면 return
            if(team_name == ''){
                sAlert('','팀명을 입력해주세요.');
                return;
            }
            //팀명 확인 systemteam_inp_team_merge가 text-primary 인지 확인

            if(!systemteam_inp_team_merge.classList.contains('text-primary')){
                sAlert('','팀명확인 버튼을 클릭해주세요.');
                return;
            }

            const parameter = {
                team_name : team_name,
                team_code : team_code,
            };
            const page = "/manage/systemteam/team/merge/insert";
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    sAlert('','팀 통합이 완료되었습니다.');
                    systemteamTeamMergeClose();
                    systemteamGroupList();
                }
            });
        }

        //[선택 팀 통합] 창 닫기
        function systemteamTeamMergeClose(){
            const systemteam_div_team_merge = document.querySelector('#systemteam_div_team_merge');
            systemteam_div_team_merge.hidden = true;
            const div_team_name = systemteam_div_team_merge.querySelector('#systemteam_inp_team_merge');
            div_team_name.innerText = '';
            //팀명확인 해제
            const systemteam_inp_team_merge = document.querySelector('#systemteam_inp_team_merge');
            systemteam_inp_team_merge.classList.remove('text-primary');
        }

        //[선택 팀 통합]에서 합쳐질 팀 선택이 잘 되었는지 확인
        function systemteamChkSameRegion(){
            const systemteam_tby_team = document.querySelector('#systemteam_tby_team');
            const checkbox = systemteam_tby_team.querySelectorAll('.checkbox');

            //2개이상 팀을 체크 했는 지 확인
            //아니면 return
            let chk_cnt = systemteam_tby_team.querySelectorAll('.checkbox:checked').length;
            if(chk_cnt < 2){
                sAlert('','통합할 팀을 2개이상 선택해주세요.');
                return false;
            }


            let region_seq = '';
            for(let i = 0; i < checkbox.length; i++){
                if(checkbox[i].checked){
                    const tag_tr = checkbox[i].closest('tr');
                    const region_seq2 = tag_tr.querySelector('.region_seq').value;
                    if(region_seq == ''){
                        region_seq = region_seq2;
                    }else if(region_seq != region_seq2){
                        sAlert('','통합할 팀들은 같은 소속이여야 합니다.');
                        return false;
                    }
                }
            }
            return true;
        }

        //[선택 팀 통합]에서 합쳐질 팀의 정보(team_name, team_code)를 가져온다.
        function systemteamChkTeamInfo(){
            let team_info = null;
            const systemteam_tby_team = document.querySelector('#systemteam_tby_team');
            const checkbox = systemteam_tby_team.querySelectorAll('.checkbox:checked');
            for(let i = 0; i < checkbox.length; i++){
                const tag_tr = checkbox[i].closest('tr');
                const region_name = tag_tr.querySelector('.region_name').innerText;
                const team_name = tag_tr.querySelector('.team_name').innerText;
                const team_code = tag_tr.querySelector('.team_code').value;
                if(team_info == null){
                    team_info = [];
                }
                team_info.push({
                    region_name:region_name,
                    team_name : team_name,
                    team_code : team_code,
                });
            }
            return team_info;
        }

    </script>
@endsection
