@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
    소속 / 팀 관리
@endsection

{{-- 추가 코드 
    1. 페이징 처리. ok
    2. 선택팀 통합 팝업 퍼블 
    3. sms/push 전송
    4. 선택 팀 공지 사항 등록.
    5. 조회시 디자인 변경에 따른 퍼블 및 기능변경.
--}}
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
    <input type="hidden" id="login_group_type2" value="{{ session()->get('group_type2') }}">
    <input type="hidden" id="post_region_seq" value="{{ $post_region_seq }}">

    <div class="col-12 pe-3 ps-3 position-relative">
        <div class="sub-title d-flex justify-content-between">
            <h2 class="text-sb-42px">
            @if($post_region_seq)
            {{-- 뒤로가기 --}}
            <button data-btn-learncal-back="" class="btn p-0 row mx-0 h-center" onclick=" window.history.back();">
                <img src="{{ asset('images/black_arrow_left_tail.svg') }}" width="65" class="px-0 h-center">
            </button>
            @else 
            <img src="{{ asset('images/team_title_icon.svg') }}" width="76">
            @endif
            <span class="me-2">소속 및 팀 관리</span>
            </h2>
        </div>
        <div class="d-flex justify-content-end align-items-end mt-4 mb-4">
            <div class="position-relative">
                <label class="d-inline-block select-wrap select-icon">
                    <select id="school_search_type" class="date-change rounded-pill border-gray sm-select text-sb-20px me-2 h-52">
                        <option value="">검색기준</option>
                        <option value="school_name">학교 이름</option>
                        <option value="school_region">지역</option>
                    </select>
                </label>
                <label class="label-search-wrap">
                    <input type="text" onkeyup="if(event.keyCode == 13) searchSchool();" id="school_search_input"
                    class="ms-search border-gray rounded-pill text-m-20px" placeholder="검색어를 입력해주세요.">
                </label>
                <button class="btn btn-outline-primary hpx-40" onclick="searchSchool();">검색</button>
            </div>
        </div>
        <div>
            <table class="table table-striped table-bordered w-100">
                <thead>
                    <tr class="text-sb-20px">
                        <th class="text-center align-middle">학교 이름</th>
                        <th class="text-center align-middle">지역</th>
                        <th class="text-center align-middle">학교 코드</th>
                        <th class="text-center align-middle">버튼</th>
                    </tr>
                </thead>
                <tbody id="school_list">
                    <!-- 학교 목록이 여기에 동적으로 추가됩니다 -->
                </tbody>
            </table>
        </div>
        {{-- 담당지역 선택 --}}
        <div class="d-flex justify-content-center mt-52 d-none">
            <div class="d-flex gap-3">
              <button type="button" onclick="systemteamTeamAdd('insert')"
              class="btn-line-ms-secondary text-sb-24px rounded-pill border-none scale-bg-white scale-text-white primary-bg-mian scale-text-gray_05 me-1">신규 팀 등록하기</button>
            </div>
        </div>
        {{-- 입력 검색 기능 --}}
        <div class="d-flex justify-content-end align-items-end mt-120 mb-32 d-none">
            <div class="position-relative">
              <label class="d-inline-block select-wrap select-icon">
                <select id="systemteam_sel_search_type" class="date-change rounded-pill border-gray sm-select text-sb-20px me-2 h-52">
                    {{-- 총괄매니저 이름, 소속명, 팀명 --}}
                    <option value="">검색기준</option>
                    <option value="general_manager_name">총괄매니저 이름</option>
                    <option value="region_name">소속명</option>
                    <option value="team_name">팀명</option>
                </select>
              </label>
              <label class="label-search-wrap">
                <input type="text" onkeyup="if(event.keyCode == 13) systemteamGroupList();" id="systemteam_inp_search_name"
                class="ms-search border-gray rounded-pill text-m-20px" placeholder="검색어를 입력해주세요.">
              </label>
              <button hidden class="btn btn-outline-primary hpx-40" onclick="systemteamGroupList();"></button>
            </div>
          </div>
        {{-- 소속 / 팀 리스트 테이블 --}}
        <div class="d-none">
            <table class="table-style w-100">
                <thead>
                    <tr class="text-sb-20px modal-shadow-style rounded">
                        <th class="text-center align-middle" style="width: 80px" onclick="event.stopPropagation();this.querySelector('span').click()">
                            <label class="checkbox mt-1">
                                <input type="checkbox" class="checkbox" id="btncheck2" autocomplete="off" onclick="systemteamAllChkClick(this);">
                                <span class="" onclick="event.stopPropagation()"></span>
                            </label>
                        </th>
                        <th class="text-center align-middle">소속</th>
                        <th class="text-center align-middle" hidden>총괄</th>
                        <th class="text-center align-middle">팀명</th>
                        <th class="text-center align-middle">팀장</th>
                        <th class="text-center align-middle">담당지역</th>
                        <th class="text-center align-middle">담당구역(중분류)</th>
                        <th class="text-center align-middle">담당구역(소분류)</th>
                        <th class="text-center align-middle">소속인원</th>
                        <th class="text-center align-middle">학생수</th>
                        <th class="text-center align-middle">생성일</th>
                        <th class="text-center align-middle">-</th>
                    </tr>
                </thead>
                <tbody id="systemteam_tby_team">
                    <tr class="copy_tr_system_team text-m-20px h-104" hidden onclick="event.stopPropagation();this.querySelector('span').click()">
                        <td class="text-center align-middle" onclick="event.stopPropagation();this.querySelector('span').click()">
                            <label class="checkbox mt-1">
                                <input type="checkbox" class="checkbox" autocomplete="off" onclick="event.stopPropagation()">
                                <span class="" onclick="event.stopPropagation()"></span>
                            </label>
                        </td>
                        <td class="scale-text-black region_name">#소속</td>
                        <td class="scale-text-black general_manager_name" hidden>#총괄</td>
                        <td class="scale-text-black team_name">#팀명</td>
                        <td class="scale-text-gray_05 leader_name">#팀장</td>
                        <td class="text-center align-middle sido">#담당지역</td>
                        <td class="scale-text-black gu">#담당구역(중분류)</td>
                        <td class="scale-text-black dong">#담당구역(소분류)</td>
                        <td class="scale-text-gray_05 team_tr_cnt">#소속인원</td>
                        <td class="scale-text-gray_05 team_st_cnt">#학생수</td>
                        <td class="scale-text-gray_05 created_at">#생성일</td>
                        <td class="">
                            <button type="button" onclick="systemteamTeamAdd('edit', this)"
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">수정하기</button>
                        </td>
                        <input type="hidden" class="team_seq">
                        <input type="hidden" class="team_code">
                        <input type="hidden" class="region_seq">
                    </tr>

                </tbody>
            </table>
        </div>
        {{-- 하단 기능 버튼 --}}
        {{-- SMS / PUSH 전송 , 선택 팀 공지사항 등록, 선택 팀 통합 --}}
        <div class="d-flex justify-content-between align-items-center mt-52 d-none">
            <div class="">
    
              <button type="button" class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2">
                SMS/PUSH 전송
              </button>
              <button type="button" class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">
                선택 팀 공지사항 등록
              </button>
            </div>
            {{-- 페이징  --}}
            <div class="col d-flex justify-content-center">
                <ul class="pagination col-auto" data-ul-team-page="1" hidden>
                    <button href="javascript:void(0)" class="btn p-0 prev" data-btn-team-page-prev="1"
                        onclick="teamPageFunc('1', 'prev')">
                        <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                    </button>
                    <li class="page-item" hidden>
                        <a class="page-link" onclick="">0</a>
                    </li>
                    <span class="page" data-span-team-page-first="1" hidden
                        onclick="teamPageFunc('1', this.innerText);" disabled>0</span>
                    <button href="javascript:void(0)" class="btn p-0 next" data-btn-team-page-next="1"
                    onclick="teamPageFunc('1', 'next')" data-is-next="0">
                        <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                    </button>
                </ul>
            </div>
            <div>
              <button type="button" onclick="systemteamTeamMerge();"
              class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom">
                선택 팀 통합
              </button>
            </div>
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
        <div id="systemteam_div_team_add" class="position-absolute w-100 h-100 bg-white ps-3" style="top: 0; left: 0; z-index:3"
        hidden>
            {{-- $address_sido, $address_gu를 include에 넘거준다. --}}
            @include('admin.admin_system_team_add', ['address_sido' => $address_sido, 'address_gu' => $address_gu])
        </div>
        {{-- 160px --}}
        <div class="d-none">
            <div class="py-lg-5"></div>
            <div class="py-lg-4"></div>
            <div class="pt-lg-3"></div>
        </div>
        <div id="school_registration" class="mt-5">
            <h3 class="mb-4">학교 등록</h3>
            <input type="hidden" id="school_code" name="school_code">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="school_name" class="form-label">학교 이름</label>
                    <input type="text" class="form-control" id="school_name" name="school_name" required>
                </div>
                <div class="col-md-6">
                    <label for="school_type" class="form-label">소속명</label>
                    <input type="text" class="form-control" id="school_type" name="school_type">
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="area" class="form-label">지역</label>
                    <input type="text" class="form-control" id="area" name="area">
                </div>
                <div class="col-md-6">
                    <label for="school_manager" class="form-label">총괄 매니저</label>
                    <select class="form-select" id="school_manager" name="school_manager">
                        <option selected disabled>선택하세요</option>
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="team_composition" class="form-label">팀 구성</label>
                    <input type="text" class="form-control" id="team_composition" name="team_composition">
                </div>
            </div>
            <div class="d-flex justify-content-between">
                <button type="button" class="btn btn-secondary" id="cancel_btn">취소하기</button>
                <button type="button" class="btn btn-primary" id="submit_btn">등록하기</button>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function(){
            const region_seq = document.querySelector('#post_region_seq').value;
            if(region_seq) systemteamGroupList(null, region_seq);
            systemteamAddGetGmList();
        });
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
            //담당구역 소분류 선택 초기화
            systemteamDongReset();

            //전체 버튼 정의
            const systemteam_btncheck1 = document.querySelector('#systemteam_btncheck1');
            systemteam_btncheck1.checked = false;
            systemteam_btncheck1.closest('.col-auto').hidden = false;
            

            //address_dong 에서 sido, gu 가 일치하는 dong 가져오기
            const dong = address_dong.filter(function(item){
                return item.sido == sel_sido && item.gu == sel_gu;
            });
            const systemteam_div_dong = document.querySelector('#systemteam_div_dong');
            const systemteam_div_dong_clone = document.querySelector('#systemteam_div_dong_clone');
            //담당구역 소분류 복사 추가
            for(let i = 0; i < dong.length; i++){
                const clone_bundle = systemteam_div_dong_clone.cloneNode(true);
                const clone_dong1 = clone_bundle.querySelector('.copy_inp_dong1');
                const clone_dong2 = clone_bundle.querySelector('.copy_inp_dong2');
                clone_bundle.hidden = false;
                clone_bundle.setAttribute('id', '');
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
                // systemteam_div_dong.appendChild(clone_dong1);
                // systemteam_div_dong.appendChild(clone_dong2);
                systemteam_div_dong.appendChild(clone_bundle);
                
            } 
            //전체 버튼 클릭
            systemteam_btncheck1.click();
        }

        //담당구역 소분류 초기화
        function systemteamDongReset(){
            const systemteam_div_dong = document.querySelector('#systemteam_div_dong');
            systemteam_div_dong.querySelectorAll('.div_dong_clone').forEach(function(item){
                item.remove();
            });
            const systemteam_btncheck1 = document.querySelector('#systemteam_btncheck1');
            systemteam_btncheck1.hidden = true;
            systemteam_btncheck1.closest('.col-auto').hidden = true;

        }

        //담당구역 소분류 클릭 선택
        function systemteamClickDong(vthis){
            const systemteam_div_sel_dong = document.querySelector('#systemteam_div_sel_dong');
            //vthis 와 연결된 label 의 text 가져오기
            let sel_dong = vthis.nextElementSibling.nextElementSibling.innerText;
            const sel_dong2 = sel_dong + "";
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
                        btn_sel_dong[i].closest('.btn_sel_dong').remove();
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
                if(systemteam_div_dong[i].nextElementSibling.nextElementSibling.innerText == dong_text){
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
            else{
                const login_group_type2 = document.querySelector('#login_group_type2').value;
                if(login_group_type2 != 'admin'){
                    // #systemteamadd_div_region_name1 select 에 option을 날리고, 
                    // <option value="10">부산북구지역본부</option>
                    const select1 = document.querySelector('#systemteamadd_div_region_name1 select');
                    select1.innerHTML = `<option value="${login_region.id}">${login_region.region_name}</option>`;
                    
                    // #systemteamadd_sel_general_manager option을 날리고,
                    // <option value="2" region_seq="10">테스트총괄1</option>
                    const select2 = document.querySelector('#systemteamadd_sel_general_manager');
                    select2.innerHTML = `<option value="${login_region.teach_id}" region_seq="${login_region.id}">${login_region.teach_name}</option>`;
                }
            }
            const systemteam_div_team_add = document.querySelector('#systemteam_div_team_add');
            systemteam_div_team_add.hidden = false;
            
        }

        //소속 팀 목록 불러오기.
        function systemteamGroupList(page_num, post_region_seq){
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
            if(!post_region_seq){
                if(btn_sel_dong.length == 0 ){
                    if(sel_sido.value == ''){
                        const msg =
                        `
                            <div class="text-sb-28px">담당구역(대,중) 선택해주세요.</div>
                        `;
                        sAlert('',msg, 4);
                        return;
                    }else{
                        area_list = sel_sido.value+',,';
                    }
                }
            }
            
            const search_type = document.querySelector('#systemteam_sel_search_type').value;
            const search_name = document.querySelector('#systemteam_inp_search_name').value;

            const page = "/manage/systemteam/teamgroup/select";
            const parameter = {
                area_list : area_list,
                search_type : search_type,
                search_name : search_name,
                page:page_num,
                region_seq:post_region_seq
            };
            queryFetch(page, parameter, function(result) {
                const systemteam_tby_team = document.querySelector('#systemteam_tby_team');
                const copy_tr_system_team = systemteam_tby_team.querySelector('.copy_tr_system_team').cloneNode(true);
                systemteam_tby_team.innerHTML = '';
                systemteam_tby_team.appendChild(copy_tr_system_team);

                if ((result.resultCode || '') == 'success') {
                    teamTablePaging(result.resultData, 1);
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
                        clone_tr.querySelector('.created_at').innerText = r_data.created_at.substr(2,8).replace(/-/gi, '.');
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

    // 페이징 클릭시 펑션
    function teamPageFunc(target, type){
            if(type == 'next'){
                const page_next = document.querySelector(`[data-btn-team-page-next="${target}"]`);
                if(page_next.getAttribute("data-is-next") == '0') return;
                // data-ul-team-page 의 마지막 page_num 의 innerText를 가져온다
                const last_page = document.querySelector(`[data-ul-team-page="${target}"] .page_num:last-of-type`).innerText;
                const page = parseInt(last_page) + 1;
                if(target == "1")
                    systemteamGroupList(page);
            }
            else if(type == 'prev'){
                // [data-span-team-page-first]  next tag 의 innerText를 가져온다
                const page_first = document.querySelector(`[data-span-team-page-first="${target}"]`);
                const page = page_first.innerText;
                if(page == 1) return;
                const page_num = page*1 -1;
                if(target == "1")
                    systemteamGroupList(page);
            }
            else{
                if(target == "1")
                    systemteamGroupList(type);                
            }        
    }

    function teamTablePaging(rData, target){
            const from = rData.from;
            const last_page = rData.last_page;
            const per_page = rData.per_page;
            const total = rData.total;
            const to = rData.to;
            const current_page = rData.current_page;
            const data = rData.data;
            //페이징 처리
            const notice_ul_page = document.querySelector(`[data-ul-team-page='${target}']`);
            //prev button, next_button
            const page_prev = notice_ul_page.querySelector(`[data-btn-team-page-prev='${target}']`);
            const page_next = notice_ul_page.querySelector(`[data-btn-team-page-next='${target}']`);
            //페이징 처리를 위해 기존 페이지 삭제
            notice_ul_page.querySelectorAll(".page_num").forEach(element => {
                element.remove();
            });
            //#page_first 클론
            const page_first = document.querySelector(`[data-span-team-page-first='${target}']`);
            //페이지는 1~10개 까지만 보여준다.
            let page_start = 1;
            let page_end = 10;
            if(current_page > 5){
                page_start = current_page - 4;
                page_end = current_page + 5;
            }
            if(page_end > last_page){
                page_end = last_page;
                if(page_end <= 10)
                    page_start = 1;
            }

            let is_next = false;
            for(let i = page_start; i <= page_end; i++){
                const copy_page_first = page_first.cloneNode(true);
                copy_page_first.innerText = i;
                copy_page_first.removeAttribute("data-span-team-page-first");
                copy_page_first.classList.add("page_num");
                copy_page_first.hidden = false;
                //현재 페이지면 active
                if(i == current_page){
                    copy_page_first.classList.add("active");
                }
                //#page_first 뒤에 붙인다.
                notice_ul_page.insertBefore(copy_page_first, page_next);
                //시작 페이지 보다 11보다 큰 i 이면 page_prev disabled 을 제거
                if(i > 11){
                    page_next.setAttribute("data-is-next", "1");
                    page_prev.classList.remove("disabled");
                }else{
                    page_next.setAttribute("data-is-next", "0");
                }
                if(i == 1){
                    // page_prev.classList.add("disabled");
                }
                if(last_page == i){
                    // page_next.classList.add("disabled");
                    is_next = true;
                }
            }
            if(!is_next){
                page_next.classList.remove("disabled");   
            }

            if(data.length != 0)
                notice_ul_page.hidden = false;
    }
    //학교검색
    function searchSchool(){
        const search_name = document.querySelector('#school_search_input').value;
        const page = "/manage/systemteam/school/search";
        const parameter = {
            search_name : search_name,
        };
        queryFetch(page, parameter, function(result) {

        const school_list = document.querySelector('#school_list');
            school_list.innerHTML = '';
            if (result.resultCode == 'success') {
                result.data.forEach(r_data => {
                    console.log(r_data);
                    const tr = document.createElement('tr');
                    tr.classList.add('text-m-20px', 'h-104');

                    const td_name = document.createElement('td');
                    td_name.classList.add('text-center', 'align-middle');
                    td_name.innerText = r_data.SCHUL_NM;
                    tr.appendChild(td_name);

                    const td_region = document.createElement('td');
                    td_region.classList.add('text-center', 'align-middle');
                    td_region.innerText = r_data.LCTN_SC_NM;
                    tr.appendChild(td_region);

                    const td_code = document.createElement('td');
                    td_code.classList.add('text-center', 'align-middle');
                    td_code.innerText = r_data.SD_SCHUL_CODE;
                    tr.appendChild(td_code);

                    const td_btn = document.createElement('td');
                    td_btn.classList.add('text-center', 'align-middle');
                    const btn = document.createElement('button');
                    btn.classList.add('btn', 'btn-primary');
                    btn.innerText = '선택';
                    btn.addEventListener('click', function() {
                        selectSchool(r_data);
                    });
                    td_btn.appendChild(btn);
                    tr.appendChild(td_btn);

                    school_list.appendChild(tr);
                });
            }
        });
    }

    function selectSchool(r_data){
        const school_registration_form = document.querySelector('#school_registration_form');
        // 학교 이름, 소속명, 팀 구성, 총괄 매니저 값 설정
        document.querySelector('#school_code').value = r_data.SD_SCHUL_CODE || '';
        document.querySelector('#school_name').value = r_data.SCHUL_NM || '';
        document.querySelector('#school_type').value = r_data.SCHUL_NM || '';
        document.querySelector('#area').value = r_data.LCTN_SC_NM || '';
    }

        //총괄 매니저 가져오기.
    function systemteamAddGetGmList(type) {
        const region_radio = document.querySelector('#systemteamadd_rdio_general_manager1');
        //강제 기존.
        if (type == 1) {
            region_radio.checked = true;
        }
        //우선은 해당 담당지역 총괄 매니저를 가져온다.
        //만약 소속명에 기존이 선택되어 있을 경우 systemteamadd_div_region_name1 select value를 가져온다.
        //value 를 가져와서 해당 소속의 총괄매니저를 선택한다.

        // const group_seq = 5; //총괄은 매니저는 5로 데이터베이스 고정.
        let region_seq = '';

        //초기화 //빈 option 추가
        const school_manager = document.querySelector('#school_manager');
        school_manager.innerHTML = '';
        const option = document.createElement('option');
        option.value = '';
        option.innerText = '총괄 매니저 선택';
        school_manager.appendChild(option);

        //기존이 선택되어 있을 경우
        {{-- if (region_radio.checked) {
            region_seq = document.querySelector('#systemteamadd_div_region_name1 select').value;
            school_manager.disabled = true;
        } else {
            school_manager.disabled = false;
        } --}}
        const page = '/manage/systemteam/teacher/select';
        const parameter = {
            group_key: 'general_manager'
        };
        queryFetch(page, parameter, function(result) {
            //option 추가
            if ((result.resultCode || '') == 'success') {
                console.log(result.resultData);
                for (let i = 0; i < result.resultData.length; i++) {
                    const r_data = result.resultData[i];
                    const option = document.createElement('option');
                    option.value = r_data.id;
                    option.innerText = r_data.teach_name;
                    option.setAttribute('region_seq', r_data.region_seq);
                    //기존과 신규를 구분
                    //기존이면 맞는 소속(regin) seq가 맞을때만
                    if (region_radio.checked && region_seq != '' && region_seq == r_data.region_seq) {
                        option.selected = true;
                        school_manager.appendChild(option);
                    }
                    //신규이면 r_data.region_seq가 비워져 있어야 한다. 미소속 
                    else if (region_radio.checked == false && (r_data.region_seq || '') == '') {
                        school_manager.appendChild(option);
                    }
                    school_manager.appendChild(option);
                }
            }
        });
    }

    submit_btn.addEventListener('click', function() {
        const school_code = document.querySelector('#school_code').value;
        const school_name = document.querySelector('#school_name').value;
        const school_type = document.querySelector('#school_type').value;
        const school_manager = document.querySelector('#school_manager').value;
        const region_seq = document.querySelector('#school_manager').selectedOptions[0].getAttribute('region_seq');
        const area = document.querySelector('#area').value;
        const page = '/manage/learning/school/insert';
        const parameter = {
            school_code: school_code,
            school_name: school_name,
            school_type: school_type,
            school_manager: school_manager,
            school_seq: region_seq,
            area: area
        };
        queryFetch(page, parameter, function(result) {
            if (result.resultCode == 'success') {
                alert('학교 등록이 완료되었습니다.');
            }
        });
    });

    </script>
@endsection