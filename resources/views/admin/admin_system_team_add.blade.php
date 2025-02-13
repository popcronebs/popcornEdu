<style>
</style>
<div class="sub-title d-flex justify-content-between">
    <h2 class="text-sb-42px">
        <button data-btn-learncal-back="" class="btn p-0 row mx-0 h-center" onclick="systemteamAddClose();">
            <img src="{{ asset('images/black_arrow_left_tail.svg') }}" width="65" class="px-0 h-center">
        </button>
      <span class="me-2">팀 상세<span class="none_edit_tag">등록</span><span class="add_edit_tag" hidden>수정</span></span>
    </h2>
  </div>

{{-- 팀 코드가 있는지 없는지로 수정인지 등록인지 확인 가능. --}}
<input type="hidden" id="systemteamadd_inp_team_code" value="">

{{-- 담당지역 copy --}}
<table class="w-100 table-list-style table-border-xless table-h-92">
    <colgroup>
      <col style="width: 15%;">
      <col style="width: 35%;">
      <col style="width: 15%;">
      <col style="width: 35%;">
    </colgroup>
    <thead></thead>
    <tbody>
      <tr class="text-start">
        <td class="text-start ps-4 scale-text-gray_06">담당지역(대분류)</td>
        <td class="text-start px-4">
          <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
            <select name="" id="systemteamadd_sel_sido" data-select-team-add-sel-sido="1"  class="border-none lg-select rounded-0 text-sb-24px scale-text-gray_05 p-0 w-100"
            onchange="systemteamAddSelectGu(this);systemteamAddGetRegionList();systemteamAddGetGmList();">
            <option value="">담당지역선택</option>
            @foreach ($address_sido as $item)
                <option value="{{ $item['sido'] }}">{{ $item['sido'] }}</option>
            @endforeach
        </select>
            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0" alt="" width="32" height="32">
          </div>
        </td>
        <td class="text-start ps-4 scale-text-gray_06">담당구역(대분류)</td>
        <td class="text-start px-4">
          <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
            <select name="" id="systemteamadd_sel_gu1" class="border-none lg-select rounded-0 text-sb-24px scale-text-gray_05 p-0 w-100"
                onchange="systemteamAddSelectDong(this)">
                <option value="">담당구역선택</option>
            </select>
            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0" alt="" width="32" height="32">
          </div>
        </td>
      </tr>
      <tr class="text-start">
        <td class="text-start ps-4 scale-text-gray_06">담당구역(소분류)</td>
        <td colspan="3" class="text-start p-4">
          <div class="d-flex align-items-center gap-4">
            <label class="checkbox d-flex align-items-center col-auto">
                <input type="checkbox" class="" id="systemteamadd_btncheck1" autocomplete="off"
                onchange="systemteamAddAllClickDong();">
                <span class=""></span>
                <p class="text-m-20px ms-1">전체</p>
              </label>
              
            <div id="systemteamadd_div_dong" class="d-flex gap-4 flex-wrap">
            </div>
            
            <label id="systemteamadd_div_dong_clone" class="checkbox row mx-0 px-0 align-items-center" hidden>
              <input type="checkbox"  class="copy_inp_dong1" onchange="systemteamAddClickDong(this);">
              <span class="col-auto px-0"></span>
              <p class="copy_inp_dong2 col-auto px-0 text-m-20px ms-1"></p>
            </label>
          </div>
        </td>
      </tr>
      <tr class="text-start">
        <td colspan="4" class="text-start ps-4 scale-bg-gray_01 border-none">
          <div class="d-flex">
            <ul id="systemteamadd_div_sel_dong"class="d-flex flex-wrap col">
              <li class="copy_btn_sel_dong" onclick="systemteamAddRemoveBtn(this)" hidden>
                <span class="text-m-20px d-flex align-items-center scale-text-gray_05">
                  <span class="dong_text">#동</span>
                  <button type="button" class="btn ms-1 p-0 h-center border-none-hover">
                    <img src="{{ asset('images/gray2_x_icon.svg') }}" width="24">
                  </button>
                  <input type="hidden" class="sido">
                    <input type="hidden" class="gu">
                    <input type="hidden" class="dong">
                </span>
              </li>
            </ul>
            <div class="d-flex align-items-center justify-content-end w-25 col-auto">
              <button type="button" class="text-m-20px btn scale-text-gray_05 d-flex border-none-hover align-items-center justify-content-end  gap-1">
                <img src="{{ asset('images/gray_refresh_icon.svg') }}" width="24">
                선택 초기화
              </button>
            </div>
          </div>
        </td>
      </tr>
    </tbody>
  </table>


{{-- 담당지역 중분류 3개 / 디자인전 부분 일단 숨김처리. 기능적으로 사용되기 때문에.--}}
<div class="xd-flex w-100 mt-4" hidden>
    <div class="pe-5 wpx-120">
        담당구역<br>(중분류)
    </div>
    <select name="" id="systemteamadd_sel_gu2" class="col-2 rounded form-select-sm hpx-40 me-2"
        onchange="systemteamAddSelectDong(this)">
        <option value="">담당구역선택</option>
    </select>
    <select name="" id="systemteamadd_sel_gu3" class="col-2 rounded form-select-sm hpx-40 me-2"
        onchange="systemteamAddSelectDong(this)">
        <option value="">담당구역선택</option>
    </select>
    {{-- 구 배열을 미리 넣어넣고 클론으로 잘라서 구 select에 넣기위해 구현. --}}
    <select id="systemteamadd_sel_gu_clone" hidden>
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

<div class="d-flex justify-content-center mt-52">
    <button type="button" class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black scale-text-gray_05 px-4 h-52">
      <svg class="me-1" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M18.3638 12L5.63616 12" stroke="#999999" stroke-width="2.5" stroke-linecap="round"></path>
        <path d="M12 18.3633L12 5.63567" stroke="#999999" stroke-width="2.5" stroke-linecap="round"></path>
      </svg>
        
      담당구역 추가하기
    </button>
  </div>

{{-- 소속명 : 퍼블 --}}
<table class="w-100 table-list-style table-border-xless table-h-92 mt-52">
    <colgroup>
      <col style="width: 15%;">
      <col style="width: 35%;">
      <col style="width: 15%;">
      <col style="width: 35%;">
    </colgroup>
    <thead></thead>
    <tbody>
      <tr class="text-start scale-bg-gray_01">
        <td class="text-start ps-4 scale-text-gray_06">소속명</td>
        <td class="text-start px-4">
            {{-- //---------------------------------------------- --}}
          {{-- <div class="d-inline-block select-wrap py-0 w-100 d-flex scale-text-gray_05">
            부산남부지역본부
          </div> --}}
          <div class="d-flex gap-2 w-100" id="systemteamadd_div_region" style=" max-width: 1340px; ">
            {{-- 라디오 추가 기존, 신규 --}}
            
            <div class="form-check align-middle" hidden>
                <input class="form-check-input" type="radio" name="systemteamadd_rdio" id="systemteamadd_rdio_existing"
                    value="option1" checked onchange="systemteamAddChgAcaAreaRadio(this)">
                <label class="form-check-label" for="systemteamadd_rdio_existing">
                    기존
                </label>
            </div>
            @if(session()->get('group_type2') == 'admin')
            <div class="form-check align-middle">
                <input class="form-check-input" type="radio" name="systemteamadd_rdio" id="systemteamadd_rdio_new"
                    value="option2" onchange="systemteamAddChgAcaAreaRadio(this)">
                <label class="form-check-label" for="systemteamadd_rdio_new">
                    신규
                </label>
            </div>
            @endif
            {{-- 기존일때는 select 태그 --}}
            <div class="col" id="systemteamadd_div_region_name1">
                <select name="" id="" class=" lg-select text-sb-24px scale-text-gray_05 p-0 w-100 border-0"
                    onchange="systemteamAddGetGmList(1)" disabled>
                    <option value="">소속명선택</option>
                </select>
            </div>
            {{-- 신규 일때는 input태그 [소속명 확인] 버튼 추가 추가 --}}
            <div id="systemteamadd_div_region_name2" class="col" hidden>
                <div class="d-flex gap-3">
                    <input type="text" class="col form-control region_name" placeholder="소속명"
                        onkeyup="this.classList.remove('text-primary')">
                    <button class="btn btn-primary" style="width:170px;" onclick="systemteamAddRegionNameChk();">소속명
                        확인</button>
                </div>
            </div>
        </div>    
          {{-- //---------------------------------------------- --}}
        </td>
        <td class="text-start ps-4 scale-text-gray_06">총괄 매니저</td>
        <td class="text-start px-4">
          {{-- <div class="d-inline-block select-wrap py-0 w-100 d-flex scale-text-gray_05">
            부산남부지역본부
          </div> --}}
          <div class="h-center gap-2 w-100" id="systemteamadd_div_region">
            <div class="form-check align-middle" hidden>
                <input class="form-check-input" type="radio" name="rdio_general_manager"
                    id="systemteamadd_rdio_general_manager1" value="option1" checked
                    onchange="systemteamAddChgGeneralManagerRadio(this);">
                <label class="form-check-label" for="systemteamadd_rdio_general_manager1">
                    기존
                </label>
            </div>
            @if(session()->get('group_type2') == 'admin')
            <div class="form-check align-middle">
                <input class="form-check-input" type="radio" name="rdio_general_manager"
                    id="systemteamadd_rdio_general_manager2" value="option2"
                    onchange="systemteamAddChgGeneralManagerRadio(this);">
                <label class="form-check-label" for="systemteamadd_rdio_general_manager2">
                    신규
                </label>
            </div>
            @endif
            {{-- 기존일때는 select 태그 option 고정 / 같은 지역본부 총괄매니저 가져오기. --}}
            {{-- 신규 고정을 풀고 선택이 가능하도록 --}}
            <select name="" id="systemteamadd_sel_general_manager"
                class="lg-select text-sb-24px scale-text-gray_05 p-0 w-100 border-0" disabled style="max-width:223px">
                <option value="">총괄 매니저 선택</option>
            </select>
        </div>
    
        </td>
      </tr>
      <tr class="text-start">
        <td class="text-start ps-4 scale-text-gray_06">팀명</td>
        <td colspan="3" class="text-start p-4">
          <div class="d-flex justify-content-between align-items-center">
            <input type="text" class="scale-text-gray_05 border-0 bg-transparent" placeholder="팀명" style="max-width:223px"
            id="systemteamadd_inp_team_name" onchange="this.classList.remove('text-primary')">

            <button type="button" onclick="systemteamAddTeamNameChk('#systemteamadd_inp_team_name');"
            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black scale-text-gray_05 px-4 primary-bg-mian-hover scale-text-white-hover">중복확인</button>
          </div>

        </td>
      </tr>
      <tr class="text-start">
        <td class="text-start ps-4 scale-text-gray_06">팀 구성</td>
        <td colspan="3" class="text-start px-4">
          <div class="d-flex justify-content-between align-items-center" id="systemteamadd_div_team_list">
            <ul class="d-flex gap-4 flex-wrap">
              <li class="copy_btn_team_list cursor-pointer" onclick="this.remove()" hidden>
                <div class="h-center gap-2">
                    <img src="{{ asset('images/red_minus_btn_icon.svg') }}" width="24">
                    <span class="scale-text-black text-m-20px">
                    <span class="teach_name">#팀원</span>(<span class="group_name">#타입</span>) 
                    </span>
                  <input type="hidden" class="teach_seq">
                <input type="hidden" class="teach_id">
                <input type="hidden" class="group_seq">
                </div>
              </li>
            </ul>
            {{-- 추가 버튼 추가 --}}
            <button type="button" onclick="systemteamAddWindowOpen();"
            class="btn-line-xss-secondary rounded border-gray text-sb-20px px-4 scale-bg-white scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">추가하기</button>
          </div>
        </td>
      </tr>
    </tbody>
  </table>


{{-- 하위 버튼 2개 --}}
<div class="d-flex w-100 mt-5 justify-content-center align-items-center gap-4">
    {{-- <button class="btn btn-outline-secondary" onclick="systemteamAddClose();">목록으로 돌아가기</button> --}}
    {{-- 삭제하기 --}}
    <button type="button"  onclick="systemteamAddDelete();" hidden
    class="add_edit_tag btn-line-ms-secondary text-sb-24px rounded-pill border-gray scale-bg-white scale-text-white-hover primary-bg-mian-hover scale-text-gray_05 justify-content-center me-12" 
    style="min-width: 192px;">삭제하기</button>
    {{-- 변경사항 저장 --}}
    <button type="button" id="systemteamadd_btn_add" onclick="systemteamAdd();"
    class="btn-line-ms-secondary text-sb-24px rounded-pill border-gray scale-bg-white scale-text-white-hover primary-bg-mian-hover scale-text-gray_05 justify-content-center" 
    style="min-width: 192px;">등록하기</button>
</div>
{{-- 팀원 추가 window --}}
<div id="systemteamadd_div_team_add_window2"
    class="border row position-fixed top-50 start-50 translate-middle bg-white"
    style="height: 70vh;width:80vw;z-index:2" hidden>
    {{-- 왼쪽 등록 화면. --}}
    <div class="row col-md-6 col-lg-4">
        <div class="container p-5 border-end pt-4">
            <h4>
                
            </h4>
            <table class="table">
                <tr>
                    <td style="width:90px;">총괄매니저</td>
                    <td style="width:200px;">
                        
                    </td>
                    <td style="width:30px;"></td>
                </tr>
                <tr>
                    <td>선생님</td>
                    <td>
                        
                    </td>
                    <td>
                       
                    </td>
                </tr>
            </table>
            <div class="d-flex justify-content-center gap-3">
               
            </div>
        </div>
    </div>
    
</div>
{{-- 팀원추가 window : --}}
<div class="modal fade" id="systemteamadd_div_team_add_window" tabindex="-1" aria-labelledby="exampleModalLabel" 
style="display:none;background:#00000096" aria-modal="true" role="dialog">
    <div class="modal-dialog rounded modal-xl" style="max-width: 1426px;">
      <div class="modal-content border-none rounded modal-shadow-style p-0 overflow-hidden">
        <div class="modal-header border-bottom-0 d-none">
        </div>
        <div class="modal-body p-0">
          <div class="d-flex half-modal">
            <div class="half-left">
              <div class="half-left-content-title px-4 pt-4 mb-52">
                <p class="modal-title fs-5 text-b-24px mb-3" id="">
                    <span class="region_name">#지역본부이름</span>
                    <span class="team_name">#팀명</span>
                </p>
                <input type="hidden" id="systemteamadd_inp_teamwindow_group_key" value="">
              </div>
              <div class="half-date">
                <div class="px-4">
                  <div class="d-flex">
                    <div class="d-inline-block select-wrap select-icon h-62 pe-6 col-auto">
                      <select id="systemteamadd_sel_teamwindow_search_type" class="border-gray lg-select text-sb-20px h-62">
                        <option value="teach_name">이름</option>
                      </select>
                    </div>
                    <label class="label-search-wrap ps-6 col">
                      <input type="text" class="lg-search border-gray rounded text-m-20px w-100" id="systemteamadd_inp_teamwindow_search" 
                        onkeyup="if(event.keyCode == 13) systemteamAddGetTeachWidthTypeForTeamWindow('teacher') "
                        placeholder="이름을 검색해주세요.">
                        {{-- <button class="btn btn-outline-secondary col-lg-1 hpx-30 btn-sm" 
                        onclick="systemteamAddGetTeachWidthTypeForTeamWindow('teacher')">검색</button> --}}
                    </label>
                  </div>
                  <div class="d-flex justify-content-between align-items-center mt-52">
                    <label class="toggle d-flex align-items-center">
                      <input type="checkbox" class="" checked="" id="systemteamadd_chk_unassigned">
                      <span class=""></span>
                      <p class="text-sb-20px ms-2">미배정 사용자만 보기</p>
                    </label>
                    <div class="d-flex">
                      <div class="d-inline-block select-wrap select-icon scale-bg-white h-52 me-12">
                        <select class="border-gray lg-select text-sb-20px h-52 py-1 rounded" id="systemteamadd_sel_teamwindow_group">
                            <option value="">그룹 선택</option>
                            @foreach ($user_groups as $group)
                                <option value="{{ $group->id }}">{{ $group->group_name }}</option>
                            @endforeach
                        </select>
                      </div>
                      <button type="button" class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-black px-4 h-52"
                      onclick="systemteamAddAddTeamWindow();">팀 목록에 추가</button>
                    </div>
                  </div>
                  <div class="table-box-wrap">

                {{-- 직원(선생님) 테이블 리스트 --}}
                {{-- <div class="tableFixedHead overflow-auto" style="max-height: calc(70vh - 130px);"> --}}
                    <div class="d-flex mt-32 table-x-scroll table-scroll w-100 mb-52">
                    <table class="table-style w-100" style="min-width: 100%;">
                        <thead> 
                            <tr class="text-sb-20px modal-shadow-style rounded">
                                <th class="text-center" onclick="event.stopPropagation();this.querySelector('span').click();">
                                    <label class="checkbox mt-1">
                                        <input id="systemteamadd_chk_all" type="checkbox" class="" onclick="systemteamAddChkAll(this);">
                                        <span class="" onclick="event.stopPropagation();">
                                        </span>
                                    </label>
                                </th>
                                <th class="text-center">구분</th>
                                <th class="text-center">회원명/아이디</th>
                                <th class="text-center" hidden>연락처</th>
                                <th class="text-center">지역</th>
                                <th class="text-center">소속</th>
                                <th class="text-center">팀</th>
                                <th class="text-center">입사일구분</th>
                                <th class="text-center">재직상태</th>
                            </tr>
                        </thead>
                        <tbody id="systemteamadd_tby_teamwindow_teacher_list">
                            <tr class="copy_tr_teacher_list text-m-20px" hidden onclick="event.stopPropagation();this.querySelector('.teacher_list_chk').click();">
                                <td class="py-2 text-center align-middle" onclick="event.stopPropagation();this.querySelector('span').click();">
                                    <label class="checkbox mt-1">
                                        <input type="checkbox" class="teacher_list_chk" onclick="event.stopPropagation();">
                                        <span class="" onclick="event.stopPropagation();">
                                        </span>
                                      </label>
                                </td>
                                <td class=" py-2 text-center align-middle group_name">#구분</td>
                                <td class=" py-2 text-center align-middle teach_name">#회원명/아이디</td>
                                <td class=" py-2 text-center align-middle teach_phone" hidden>#연락처</td>
                                <td class=" py-2 text-center align-middle area">#지역</td>
                                <td class=" py-2 text-center align-middle region_name">#소속</td>
                                <td class=" py-2 text-center align-middle team_name">#팀</td>
                                <td class=" py-2 text-center align-middle created_at">#입사일구분</td>
                                <td class=" py-2 text-center align-middle teach_status_str">#재직상태</td>
                                <input type="hidden" class="teach_seq">
                                <input type="hidden" class="group_seq">
                           </tr>
                        </tbody>
                    </table>
                </div>
                
                  </div>
                </div>

              </div>
        
            </div>
            <div id="sticker" class="half-right px-32 overflow-hidden rounded-end-3" style="top: 0px; position: relative;">
              <div class="half-right-content-title mt-4 mb-52">
                <button type="button" class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close" onclick="systemteamAddWindowClose();"></button>
              </div>
              <div class="half-wrap ">
                
        <p class="text-sb-20px mb-3">총괄매니저</p>
        <div class="w-100">
        <div class="d-flex justify-content-between align-items-center px-4 rounded scale-bg-white w-100 mb-12 h-62">
          <input class="general_manager text-sb-20px scale-text-gray_05 border-0 bg-transparent" readonly value="#총괄이름"disabled>

        </div>
    
        <p class="text-sb-20px mb-3 mt-52">팀 구성</p>
        <div class="w-100">            
            <div class="copy_btn_teacher_list row mx-0 justify-content-between align-items-center rounded h-62 px-4 mb-12 scale-bg-white"
            hidden onclick="this.remove();">
                <span class="col-auto text-sb-20px scale-text-black ">
                    <span class="teach_name">#팀원</span>(<span class="teach_id">ID</span>) / <span class="group_name_str">#그룹명</span>
                </span>
                <img class="col-auto" src="{{ asset('/images/red_minus_btn_icon.svg') }}" width="22"> 
                <input type="hidden" class="teach_seq">
                <input type="hidden" class="group_name">
                <input type="hidden" class="group_seq">
            </div>
      </div>
    {{-- <button class="btn btn-primary btn-sm mb-3 hpx-30 mt-1"
        onclick="systemteamAddGetTeachWidthTypeForTeamWindow('teacher')">
        +
    </button> --}}
    
    
      <button type="button" class="list-add-btn-2 w-100 d-flex justify-content-center align-items-center p-0 h-62 text-m-20px scale-bg-gray_01 scale-text-gray_05" data-bs-toggle="modal" data-bs-target="#modal-2">
        <svg width="24" height="24" class="me-1" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12 5.63672V18.3643" stroke="#999999" stroke-width="2.5" stroke-linecap="round"></path>
          <path d="M18.3633 12H5.63567" stroke="#999999" stroke-width="2.5" stroke-linecap="round"></path>
        </svg>
        추가하기
      </button>
      <div class="w-100 mt-80 pb-4">

        <div class="col-12 ps-6 p-0 ">
          <button type="button" onclick="systemteamAddTeamWindwInsert();"
          class="btn-lg-primary text-b-24px rounded scale-text-white w-100 text-center justify-content-center">등록하기</button>
        </div>
      </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer border-top-0 py-0 px-3 pb-2 mt-52 d-none">
        </div>
      </div>
    </div>
  </div>
</div>

<script>
    const login_region = @json($region);
    //담당지역 대분류 선택시 
    function systemteamAddSelectGu(vthis) {
        const sel_sido = vthis.value;
        //담당구역 중분류 선택 초기화
        systemteamAddGuReset();
        //담당구역 소분류 선택 초기화
        systemteamAddDongReset();

        if (sel_sido == '') {
            //담당구역 소분류 선택 초기화
            return;
        }
        //클론으로 잘라서 구 select에 넣기위해 구현.
        const optgroup = document.querySelector('#systemteamadd_sel_gu_clone optgroup[label="' + sel_sido + '"]');
        //1,2,3에 모두 똑같이 추가
        const systemteamadd_sel_gu1 = document.querySelector('#systemteamadd_sel_gu1');
        const systemteamadd_sel_gu2 = document.querySelector('#systemteamadd_sel_gu2');
        const systemteamadd_sel_gu3 = document.querySelector('#systemteamadd_sel_gu3');

        //담당구역 중분류 option 추가
        systemteamadd_sel_gu1.appendChild(optgroup.cloneNode(true));
        systemteamadd_sel_gu2.appendChild(optgroup.cloneNode(true));
        systemteamadd_sel_gu3.appendChild(optgroup.cloneNode(true));
    }
    //담당구역 중분류 초기화
    function systemteamAddGuReset() {
        const systemteamadd_sel_gu1 = document.querySelector('#systemteamadd_sel_gu1');
        const systemteamadd_sel_gu2 = document.querySelector('#systemteamadd_sel_gu2');
        const systemteamadd_sel_gu3 = document.querySelector('#systemteamadd_sel_gu3');

        //담당구역 중분류 선택 초기화
        systemteamadd_sel_gu1.innerHTML = '';
        systemteamadd_sel_gu2.innerHTML = '';
        systemteamadd_sel_gu3.innerHTML = '';

        //담당구역선택 option 추가
        const option = document.createElement('option');
        option.value = '';
        option.innerText = '담당구역선택';
        systemteamadd_sel_gu1.appendChild(option.cloneNode(true));
        systemteamadd_sel_gu2.appendChild(option.cloneNode(true));
        systemteamadd_sel_gu3.appendChild(option.cloneNode(true));
    }
    //
    function systemteamAddSelectDong(vthis) {
        const sel_sido = document.querySelector('#systemteamadd_sel_sido').value;
        const sel_gu = vthis.value;
        //전체 버튼 정의
        const systemteamadd_btncheck1 = document.querySelector('#systemteamadd_btncheck1');
        systemteamadd_btncheck1.checked = false;
        //담당구역 소분류 선택 초기화
        systemteamAddDongReset();

        //address_dong 에서 sido, gu 가 일치하는 dong 가져오기
        const dong = address_dong.filter(function(item) {
            return item.sido == sel_sido && item.gu == sel_gu;
        });
        const systemteamadd_div_dong = document.querySelector('#systemteamadd_div_dong');
        const systemteamadd_div_dong_clone = document.querySelector('#systemteamadd_div_dong_clone');
        //담당구역 소분류 복사 추가
        for (let i = 0; i < dong.length; i++) {
            const clone_dong_bundle = systemteamadd_div_dong_clone.cloneNode(true);
            const clone_dong1 = clone_dong_bundle.querySelector('.copy_inp_dong1');
            const clone_dong2 = clone_dong_bundle.querySelector('.copy_inp_dong2');
            //클래스 삭제후 copy 없앤 클르스 추가
            clone_dong1.classList.remove('copy_inp_dong1');
            clone_dong2.classList.remove('copy_inp_dong2');
            clone_dong1.classList.add('inp_dong1');
            clone_dong2.classList.add('inp_dong2');

            //id, for, value, text, hidden 추가
            clone_dong1.id = 'systemteamadd_inp_dong1_' + i;
            clone_dong2.id = 'systemteamadd_inp_dong2_' + i;
            clone_dong2.setAttribute('for', 'systemteamadd_inp_dong1_' + i);
            clone_dong1.value = dong[i].dong;
            clone_dong1.setAttribute('sido', sel_sido);
            clone_dong1.setAttribute('gu', sel_gu);
            // clone_dong2.innerText = sel_sido+' '+sel_gu + ' ' + dong[i].dong;
            clone_dong2.innerText = dong[i].dong;
            clone_dong1.hidden = false;
            clone_dong2.hidden = false;
            // systemteamadd_div_dong.appendChild(clone_dong1);
            // systemteamadd_div_dong.appendChild(clone_dong2);
            clone_dong_bundle.hidden = false;
            clone_dong_bundle.id = '';
            systemteamadd_div_dong.appendChild(clone_dong_bundle);
        }
        //전체 버튼 클릭
        systemteamadd_btncheck1.nextElementSibling.nextElementSibling.innerHTML = sel_gu + ' ' + '전체';
        systemteamadd_btncheck1.click();
    }
    //
    function systemteamAddAllClickDong() {
        //내부 모든 input checkbox 선택
        const systemteamadd_div_dong_input = document.querySelectorAll('#systemteamadd_div_dong .inp_dong1');
        for (let i = 0; i < systemteamadd_div_dong_input.length; i++) {
            systemteamadd_div_dong_input[i].checked = systemteamadd_btncheck1.checked;

            //systemteamAddClickDong 실행
            systemteamadd_div_dong_input[i].onchange();
        }
    }
    //
    function systemteamAddClickDong(vthis) {
        const systemteamadd_div_sel_dong = document.querySelector('#systemteamadd_div_sel_dong');
        //vthis 와 연결된 label 의 text 가져오기
        const sido = vthis.getAttribute('sido');
        const gu = vthis.getAttribute('gu');
        const dong = vthis.value;
        let sel_dong = `${sido} ${gu} ${dong}`;
        //vthis 이 체크 되었는지 확인
        if (vthis.checked) {
            //담당구역 선택 지역 추가 
            const btn_sel_dong = systemteamadd_div_sel_dong.querySelector('.copy_btn_sel_dong').cloneNode(true);
            btn_sel_dong.classList.remove('copy_btn_sel_dong');
            btn_sel_dong.classList.add('btn_sel_dong');
            btn_sel_dong.querySelector('.dong_text').innerText = sel_dong;
            btn_sel_dong.querySelector('.sido').value = sido;
            btn_sel_dong.querySelector('.gu').value = gu;
            btn_sel_dong.querySelector('.dong').value = dong;
            btn_sel_dong.hidden = false;
            //중복은 추가하지 않는다.
            let isAdd = true;
            const btn_sel_dong_text = systemteamadd_div_sel_dong.querySelectorAll('.btn_sel_dong .dong_text');
            for (let i = 0; i < btn_sel_dong_text.length; i++) {
                if (btn_sel_dong_text[i].innerText == sel_dong) {
                    isAdd = false;
                }
            }
            if (isAdd) systemteamadd_div_sel_dong.appendChild(btn_sel_dong);
        } else {
            //선택지역 중에 sel_dong와 같은 text를 가진 요소를 가져와서 삭제
            const btn_sel_dong = systemteamadd_div_sel_dong.querySelectorAll('.btn_sel_dong .dong_text');
            for (let i = 0; i < btn_sel_dong.length; i++) {
                if (btn_sel_dong[i].innerText == sel_dong) {
                    btn_sel_dong[i].parentElement.remove();
                }
            }

        }
    }
    //담당구역 소분류 선택 초기화
    function systemteamAddDongReset() {
        const systemteamadd_div_dong = document.querySelector('#systemteamadd_div_dong');
        systemteamadd_div_dong.innerHTML = '';
    }
    //
    function systemteamAddRemoveBtn(vthis) {
        //담당구역 중에 삭제하는 요소의 text를 가진 요소를 가져와서 체크 해제
        let dong_text = vthis.querySelector('.dong_text').innerText;
        //전체가 들어가있으면 '전체' 로 dong_text 수정
        //단 전체 앞 단어가 담당구역의 value와 같아야한다.
        //전체 버튼을 체크해제해주기위함.
        if (dong_text.indexOf('전체') != -1) {
            const systemteamadd_btncheck1 = document.querySelector('#systemteamadd_btncheck1');
            all_text = systemteamadd_btncheck1.nextElementSibling.innerText;
            if (dong_text == all_text) {
                systemteamadd_btncheck1.checked = false;
            }
        }
        const systemteamadd_div_dong = document.querySelectorAll('#systemteamadd_div_dong .inp_dong1');
        for (let i = 0; i < systemteamadd_div_dong.length; i++) {
            if (systemteamadd_div_dong[i].nextElementSibling.innerText == dong_text) {
                systemteamadd_div_dong[i].checked = false;
            }
        }
        vthis.remove();
    }
    //소속명 기존 / 신규
    function systemteamAddChgAcaAreaRadio(vthis) {
        const systemteamadd_div_region_name1 = document.querySelector('#systemteamadd_div_region_name1');
        const systemteamadd_div_region_name2 = document.querySelector('#systemteamadd_div_region_name2');
        const systemteamadd_sel_general_manager = document.querySelector('#systemteamadd_sel_general_manager');
        if (vthis.id == 'systemteamadd_rdio_existing') {
            systemteamadd_div_region_name1.hidden = false;
            systemteamadd_div_region_name2.hidden = true;
        } else {
            systemteamadd_div_region_name1.hidden = true;
            systemteamadd_div_region_name2.hidden = false;
        }
    }
    //총괄 매니저 기존 / 신규
    function systemteamAddChgGeneralManagerRadio(vthis) {
        //총괄 매니저 가져오기.
        systemteamAddGetGmList();
        //
        const systemteamadd_sel_general_manager = document.querySelector('#systemteamadd_sel_general_manager');
        if (vthis.id == 'systemteamadd_rdio_general_manager1') {
            systemteamadd_sel_general_manager.disabled = true;
        } else {
            systemteamadd_sel_general_manager.disabled = false;
        }
    }
    //목록으로 돌아가기.
    function systemteamAddClose() {
        //목록으로 돌아가기.
        const systemteam_div_team_add = document.querySelector('#systemteam_div_team_add');
        systemteam_div_team_add.hidden = true;
        systemteamAddReset();
    }
    //모든 내용 초기화하기.
    //담당 지역에 소속명 가져오기
    // 
    //팀원 등록 윈도우 창 열기
    function systemteamAddWindowOpen() {
        const systemteamadd_div_team_add_window = document.querySelector('#systemteamadd_div_team_add_window');
        // 먼저 지역본부이름, 팀명을, 총괄매니저이름 가져오고 비워져 있으면 선택하라는 알림창을 띄운다.
        const r_data = systemteamAddGetTeamWindowInfo();
        if (!r_data.resultCode) {
            return;
        }
        //팀원등록 창에 #지역본부이름 #팀명 #총괄매니저 이름을 넣어준다.
        const tag_gm = systemteamadd_div_team_add_window.querySelector('.general_manager');
        const tag_region_name = systemteamadd_div_team_add_window.querySelector('.region_name');
        const tag_team_name = systemteamadd_div_team_add_window.querySelector('.team_name');

        tag_gm.value = r_data.general_manager;
        tag_region_name.innerText = r_data.region_name;
        tag_team_name.innerText = r_data.team_name;

        //팀구성의 팀원들을 팀원 등록 창에 팀장/상담/팀원 으로 나누어서 넣어준다.
        const systemteamadd_div_team_list = document.querySelectorAll('#systemteamadd_div_team_list .btn_team_list');
        //팀구성 창에서 팀원들 모두 초기화
        systemteamAddTeamWindowReset();

        for (let i = 0; i < systemteamadd_div_team_list.length; i++) {
            const teach_seq = systemteamadd_div_team_list[i].querySelector('.teach_seq').value;
            const group_name = systemteamadd_div_team_list[i].querySelector('.group_name').innerText;
            const teach_name = systemteamadd_div_team_list[i].querySelector('.teach_name').innerText;
            const teach_id = systemteamadd_div_team_list[i].querySelector('.teach_id').value;
            const group_seq = systemteamadd_div_team_list[i].querySelector('.group_seq').value;
            let group_key = '';
            //DB고정이므로 추후 확인 필요. 통합으로 굳이 UI 에서 나누지 않음.
            // if(group_seq == 6) group_key = 'team_leader';
            // else if(group_seq == 7) group_key = 'teacher';
            // else if(group_seq == 8) group_key = 'counselor';
            group_key = 'teacher'; //통일
            
            const copy_btn_list = systemteamadd_div_team_add_window.querySelector('.copy_btn_' + group_key + '_list');
            const copy_btn_list_parent = copy_btn_list.parentElement;
            const copy_btn = copy_btn_list.cloneNode(true);
            copy_btn.classList.remove('copy_btn_' + group_key + '_list');
            copy_btn.classList.add('btn_' + group_key + '_list');
            copy_btn.hidden = false;
            copy_btn.classList.add('t' + teach_seq);
            if (copy_btn_list_parent.querySelectorAll('.t' + teach_seq).length > 0) {
                continue;
            }
            copy_btn.querySelector('.teach_seq').value = teach_seq;
            copy_btn.querySelector('.teach_name').innerText = teach_name;
            copy_btn.querySelector('.teach_id').innerText = teach_id;
            copy_btn.querySelector('.group_name').value = group_name;
            copy_btn_list_parent.appendChild(copy_btn);
        }

        

        //팀원등록 창 열기
        // systemteamadd_div_team_add_window.hidden = false;
        const myModalw = new bootstrap.Modal(document.getElementById('systemteamadd_div_team_add_window'), {});
        myModalw.show();
        document.querySelector('.modal-backdrop').remove();



    }
    //팀원 등록 윈도우 창 닫기
    function systemteamAddWindowClose() {
        const systemteamadd_div_team_add_window = document.querySelector('#systemteamadd_div_team_add_window');
        systemteamadd_div_team_add_window.querySelector('.btn-close').click();
    }
    //소속명 확인 / 있는지 없는지
    function systemteamAddRegionNameChk() {
        const page = '/manage/systemteam/region/name/chk';
        const region_name = document.querySelector('#systemteamadd_div_region_name2 .region_name').value;
        const inp_region_name = document.querySelector('#systemteamadd_div_region_name2 .region_name');
        if (region_name == '') {
            sAlert('', '소속명을 입력해주세요.');
            return;
        }
        const parameter = {
            region_name: document.querySelector('#systemteamadd_div_region_name2 .region_name').value
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                sAlert('', '사용가능한 소속명입니다.');
                inp_region_name.classList.add('text-primary');
            } else {
                //"region_name"<br>소속이 이미 존재합니다.
                //region_name + 총괄매니저 : #이름
                //(지역 + 구)
                sAlert('', '이미 사용중인 소속명입니다.');
                //비활성화 해주고 비운다.
                inp_region_name.classList.remove('text-primary');
                inp_region_name.value = '';
            }
        });
    }
    //팀명 확인 / 있는지 없는지
    function systemteamAddTeamNameChk(tag_selector) {
        const page = '/manage/systemteam/team/name/chk';
        const inp_team_name = document.querySelector(tag_selector);
        const team_name = inp_team_name.value;
        const team_code = document.querySelector('#systemteamadd_inp_team_code').value;

        if (team_name == '') {
            sAlert('', '팀명을 입력해주세요.');
            return;
        }
        const parameter = {
            team_name: team_name
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                const msg = '사용가능한 팀명입니다.';
                sAlert('', `<span class="text-b-28px">${msg}</span>`,4);
                inp_team_name.classList.add('text-primary');
            } else {
                //"team_name"<br>팀명이 이미 존재합니다.
                if(inp_team_name.classList.contains('text-primary')){
                    toast('먼저 팀명 변경후 확인해주세요.');
                    return;
                }
                if((result.team_code||'') != '' && team_code == result.team_code){
                    toast('기존 팀명입니다.');
                    return;
                }

                const msg = `
                <div class="modal-header border-bottom-0 justify-content-center flex-column p-0 mb-3">
                    <p class="modal-title text-center text-b-28px alert-top-m-20" id="">‘${team_name}' 이름의</p>
                    <p class="modal-title text-center text-b-28px alert-bottom-m studyColor-text-studyComplete" id="">소속(팀) 명이 이미 존재합니다.</p>
                    <p class="modal-title text-center text-m-20px scale-text-gray_05 mt-12" id="">${team_name} 총괄매니저 : ${result.region.teach_name}</p>
                    <p class="modal-title text-center text-m-20px scale-text-gray_05" id="">(${result.team_area.sido} ${result.team_area.gu})</p>
                </div>
                `;
                sAlert('', msg, 4);
                //비활성화 해주고 비운다.
                inp_team_name.classList.remove('text-primary');
                inp_team_name.value = '';
            }
        });
    }
    //담당지역(대분류)에 맞는 소속 리스트 가져오기.
    function systemteamAddGetRegionList() {
        const team_code = document.querySelector('#systemteamadd_inp_team_code').value;
        const area = document.querySelector('#systemteamadd_sel_sido').value;
        //소속선택 초기화 systemteamadd_div_region_name1 select
        const systemteamadd_div_region_name1 = document.querySelector('#systemteamadd_div_region_name1 select');
        const prev_sel_value = systemteamadd_div_region_name1.value;
        //수정일때는 소속의 선택을 사용자가 선택하기 전까지 유지.
        if(team_code.length == 0) systemteamadd_div_region_name1.innerHTML = '';
        const option = document.createElement('option');
        option.value = '';
        option.innerText = '소속명선택';
        systemteamadd_div_region_name1.appendChild(option);

        if (area == '') {
            // return;
        }

        const page = '/manage/systemteam/region/select';
        const parameter = {
            area: area
        };
        queryFetch(page, parameter, function(result) {
            //빈 option 추가
            if(team_code.length != 0) systemteamadd_div_region_name1.innerHTML = '';

            //option 추가
            if ((result.resultCode || '') == 'success') {

                for (let i = 0; i < result.resultData.length; i++) {
                    const r_data = result.resultData[i];
                    const option = document.createElement('option');
                    option.value = r_data.id;
                    option.innerText = r_data.region_name;
                    systemteamadd_div_region_name1.appendChild(option);
                }
                //수정일때는 소속의 선택을 사용자가 선택하기 전까지 유지.
                if(team_code.length > 0 && prev_sel_value.length > 0){
                    systemteamadd_div_region_name1.value = prev_sel_value;
                }
            }
        });
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
        const area = document.querySelector('#systemteamadd_sel_sido').value;

        // const group_seq = 5; //총괄은 매니저는 5로 데이터베이스 고정.
        let region_seq = '';

        //초기화 //빈 option 추가
        const systemteamadd_sel_general_manager = document.querySelector('#systemteamadd_sel_general_manager');
        systemteamadd_sel_general_manager.innerHTML = '';
        const option = document.createElement('option');
        option.value = '';
        option.innerText = '총괄 매니저 선택';
        systemteamadd_sel_general_manager.appendChild(option);

        //기존이 선택되어 있을 경우
        if (region_radio.checked) {
            region_seq = document.querySelector('#systemteamadd_div_region_name1 select').value;
            systemteamadd_sel_general_manager.disabled = true;
        } else {
            systemteamadd_sel_general_manager.disabled = false;
        }
        const page = '/manage/systemteam/teacher/select';
        const parameter = {
            region_id: area,
            group_key: 'general_manager'
        };
        queryFetch(page, parameter, function(result) {
            //option 추가
            if ((result.resultCode || '') == 'success') {
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
                        systemteamadd_sel_general_manager.appendChild(option);
                    }
                    //신규이면 r_data.region_seq가 비워져 있어야 한다. 미소속 
                    else if (region_radio.checked == false && (r_data.region_seq || '') == '') {
                        systemteamadd_sel_general_manager.appendChild(option);
                    }
                }
            }
        });
    }

    //팀원 등록 지역본부이름, 팀명을, 총괄매니저이름 가져오고 비워져 있으면 선택하라는 알림창
    //아니면 총괄매니저 이름과 지역본부이름, 팀명을 가져온다.
    function systemteamAddGetTeamWindowInfo() {
        const systemteamadd_sel_general_manager = document.querySelector('#systemteamadd_sel_general_manager');
        const systemteamadd_div_region_name1 = document.querySelector('#systemteamadd_div_region_name1 select');
        const systemteamadd_div_region_name2 = document.querySelector('#systemteamadd_div_region_name2 .region_name');

        const systemteamadd_inp_team_name = document.querySelector('#systemteamadd_inp_team_name');
        const systemteamadd_rdio_existing = document.querySelector('#systemteamadd_rdio_existing');
        let r_data = {
            resultCode: false,
        };
        if (systemteamadd_sel_general_manager.value == '') {
            const msg = '총괄매니저를 선택해주세요.';
            sAlert('', `<span class="text-b-28px">${msg}</span>`, 4);
            return r_data;
        }
        if (systemteamadd_inp_team_name.value == '') {
            const msg = '팀명을 입력해주세요.';
            sAlert('', `<span class="text-b-28px">${msg}</span>`, 4);
            return r_data;
        }
        //소속명 기존일때는
        if (systemteamadd_rdio_existing.checked &&
            systemteamadd_div_region_name1.value == '') {
            const msg = '소속명을 선택해주세요.';
            sAlert('', `<span class="text-b-28px">${msg}</span>`, 4);
            return r_data;
        }
        //소속명 신규일때는
        if (systemteamadd_rdio_existing.checked == false &&
            systemteamadd_div_region_name2.value == '') {
            const msg = '소속명을 입력해주세요.';
            sAlert('', `<span class="text-b-28px">${msg}</span>`, 4);
            return r_data;
        }

        // 총괄매니저 이름과 지역본부이름, 팀명을 가져온다.
        const general_manager = systemteamadd_sel_general_manager.options[systemteamadd_sel_general_manager
                .selectedIndex]
            .innerText;
        //소속명 신규와 기존에 따라 다르게 가져온다.
        const region_name = systemteamadd_rdio_existing.checked ? systemteamadd_div_region_name1.options[
                systemteamadd_div_region_name1.selectedIndex].innerText :
            systemteamadd_div_region_name2.value;
        const team_name = document.querySelector('#systemteamadd_inp_team_name').value;

        //배열에 담아서 리턴
        r_data.resultCode = true;
        r_data.general_manager = general_manager;
        r_data.region_name = region_name;
        r_data.team_name = team_name;
        return r_data;
    }

    //
    function systemteamAddGetTeachWidthTypeForTeamWindow(type) {
        const tag_group_key = document.querySelector('#systemteamadd_inp_teamwindow_group_key');
        if (type == null) {
            type = tag_group_key.value;
        } else
            tag_group_key.value = type;
        
        const area = document.querySelector('#systemteamadd_sel_sido').value;

        //검색이 있을시.
        const search_type = document.querySelector('#systemteamadd_sel_teamwindow_search_type').value;
        const search_str = document.querySelector('#systemteamadd_inp_teamwindow_search').value;
        //미배정인지 아닌지 체크
        const chk_unassigned = document.querySelector('#systemteamadd_chk_unassigned').checked;
        // 유저그룹 
        const group_seq = document.querySelector('#systemteamadd_sel_teamwindow_group').value;
        
        const parameter = {
            region_id: area,
            group_key: type,
            is_unassigned: chk_unassigned ? 'Y' : 'N',
            search_type: search_type,
            search_str: search_str,
            group_seq:group_seq
        };
        const page = "/manage/systemteam/teacher/select";
        queryFetch(page, parameter, function(result) {
            //window table 초기화 클론 재 추가
            const systemteamadd_tby_teamwindow_teacher_list = document.querySelector(
                '#systemteamadd_tby_teamwindow_teacher_list');
            const copy_tr_teacher_list = systemteamadd_tby_teamwindow_teacher_list.querySelector(
                '.copy_tr_teacher_list');
            systemteamadd_tby_teamwindow_teacher_list.innerHTML = '';
            systemteamadd_tby_teamwindow_teacher_list.appendChild(copy_tr_teacher_list);

            //팀원 구분.
            if ((result.resultCode || '') == 'success') {
                for (let i = 0; i < result.resultData.length; i++) {
                    const r_data = result.resultData[i];
                    const tr = copy_tr_teacher_list.cloneNode(true);
                    tr.classList.remove('copy_tr_teacher_list');
                    tr.classList.add('tr_teacher_list');
                    tr.hidden = false;
                    tr.querySelector('.group_name').innerText = r_data.group_name;
                    tr.querySelector('.teach_name').innerHTML = r_data.teach_name + '<br>(' + r_data.teach_id +
                        ')';
                    tr.querySelector('.teach_phone').innerText = r_data.teach_phone;
                    tr.querySelector('.area').innerText = r_data.area;
                    tr.querySelector('.region_name').innerText = r_data.region_name || '미배정';
                    tr.querySelector('.team_name').innerText = r_data.team_name || '미배정';
                    tr.querySelector('.created_at').innerText = r_data.created_at.substr(2,8).replace(/-/gi, '.');
                    tr.querySelector('.teach_status_str').innerText = r_data.teach_status_str;
                    tr.querySelector('.teach_seq').value = r_data.id;
                    tr.querySelector('.group_seq').value = r_data.group_seq;
                    tr.querySelector('.teacher_list_chk').setAttribute('teach_seq', r_data.id);
                    tr.querySelector('.teacher_list_chk').setAttribute('teach_name', r_data.teach_name);
                    tr.querySelector('.teacher_list_chk').setAttribute('teach_id', r_data.teach_id);
                    tr.querySelector('.teacher_list_chk').setAttribute('group_name', r_data.group_name);
                    tr.querySelector('.teacher_list_chk').setAttribute('area', r_data.area);
                    tr.querySelector('.teacher_list_chk').setAttribute('region_name', r_data.region_name);
                    tr.querySelector('.teacher_list_chk').setAttribute('team_name', r_data.team_name);
                    tr.querySelector('.teacher_list_chk').setAttribute('created_at', r_data.created_at);
                    tr.querySelector('.teacher_list_chk').setAttribute('teach_status_str', r_data
                        .teach_status_str);
                    tr.querySelector('.teacher_list_chk').setAttribute('group_seq', r_data.group_seq);
                    systemteamadd_tby_teamwindow_teacher_list.appendChild(tr);
                }
            }
        });
    }

    //팀 목록에 추가
    function systemteamAddAddTeamWindow() {
        //체크박스 체크 되어있는 직원(선생님) 가져오기.
        const systemteamadd_tby_teamwindow_teacher_list = document.querySelectorAll('.tr_teacher_list .teacher_list_chk:checked');
        const systemteamadd_div_team_add_window = document.querySelector('#systemteamadd_div_team_add_window');
        //group_key 는 마지막에 선택된 직급을 가져온다.
        const group_key = document.querySelector('#systemteamadd_inp_teamwindow_group_key').value;
        if ((group_key || '') == '') {
            sAlert('', '선택된 직급이 없습니다.');
            return;
        }
        //한명도 선택이 되지 않았을 경우
        if (systemteamadd_tby_teamwindow_teacher_list.length == 0) {
            sAlert('', '선택된 직원이 없습니다.');
            return;
        }

        //copy_btn_list 부모태그를 가져온다.
        const copy_btn_list = systemteamadd_div_team_add_window.querySelector('.copy_btn_' + group_key + '_list');
        const copy_btn_list_parent = copy_btn_list.parentElement;
        //선택된 직원(선생님) seq와 이름을 teach_id, group_name을 가져와 넣어준다.
        //같은 teach_seq가 있을경우 추가하지 않는다.
        //team_leader안 한명만 추가할 수 있도록 한다.
        if (group_key == 'team_leader') {
            if (copy_btn_list_parent.querySelectorAll('.btn_' + group_key + '_list').length > 0) {
                sAlert('', '팀장은 한명만 추가할 수 있습니다.');
                return;
            }
        }
        for (let i = 0; i < systemteamadd_tby_teamwindow_teacher_list.length; i++) {
            const teach_seq = systemteamadd_tby_teamwindow_teacher_list[i].getAttribute('teach_seq');
            const teach_name = systemteamadd_tby_teamwindow_teacher_list[i].getAttribute('teach_name');
            const teach_id = systemteamadd_tby_teamwindow_teacher_list[i].getAttribute('teach_id');
            const group_name = systemteamadd_tby_teamwindow_teacher_list[i].getAttribute('group_name');
            const group_seq = systemteamadd_tby_teamwindow_teacher_list[i].getAttribute('group_seq');
            const copy_btn = copy_btn_list.cloneNode(true);
            copy_btn.classList.remove('copy_btn_' + group_key + '_list');
            copy_btn.classList.add('btn_' + group_key + '_list');
            copy_btn.hidden = false;
            copy_btn.classList.add('t' + teach_seq);
            if (copy_btn_list_parent.querySelectorAll('.t' + teach_seq).length > 0) {
                continue;
            }
            copy_btn.querySelector('.teach_seq').value = teach_seq;
            copy_btn.querySelector('.teach_name').innerText = teach_name;
            copy_btn.querySelector('.teach_id').innerText = teach_id;
            copy_btn.querySelector('.group_name').value = group_name;
            copy_btn.querySelector('.group_name_str').innerText = group_name;
            copy_btn.querySelector('.group_seq').value = group_seq; 
            copy_btn_list_parent.appendChild(copy_btn);
        }
    }
    //팀구성 
    function systemteamAddTeamWindwInsert() {
        //팀장, 상담, 담당 선생님을 모두 선택했는지 확인한다.
        const systemteamadd_div_team_add_window = document.querySelector('#systemteamadd_div_team_add_window');
        const btn_team_leader_list = systemteamadd_div_team_add_window.querySelectorAll('.btn_team_leader_list');
        const btn_counselor_list = systemteamadd_div_team_add_window.querySelectorAll('.btn_counselor_list');
        const btn_teacher_list = systemteamadd_div_team_add_window.querySelectorAll('.btn_teacher_list');
        // if (btn_team_leader_list.length == 0  btn_counselor_list.length == 0 || btn_teacher_list.length == 0) {
        //하나로 통합
        if (btn_teacher_list.length == 0) {
            const msg = '팀원을 모두 선택해주세요.';
            sAlert('', `<span class="text-b-28px">${msg}</span>`, 4);
            return;
        }
        //각각 foreach 로 정보값을 가져온다.
        const team_list = systemteamAddGetTeamList(btn_team_leader_list, btn_counselor_list, btn_teacher_list);
        //team_list
        //가져온 팀리스트를 
        //systemteamadd_div_team_list 내부
        //copy_btn_team_list 에 넣어서 팀구성을 만든다.
        const systemteamadd_div_team_list = document.querySelector('#systemteamadd_div_team_list');
        const copy_btn_team_list = systemteamadd_div_team_list.querySelector('.copy_btn_team_list');
        const copy_btn_team_list_clone = copy_btn_team_list.cloneNode(true);
        const copy_btn_team_list_parent = copy_btn_team_list.parentElement;
        copy_btn_team_list_parent.innerHTML = '';
        copy_btn_team_list_parent.appendChild(copy_btn_team_list_clone);
        //팀구성을 만든다.
        for (let i = 0; i < team_list.length; i++) {
            const team_one = team_list[i];
            const teach_seq = team_one.teach_seq;
            const teach_name = team_one.teach_name;
            const teach_id = team_one.teach_id;
            const group_name = team_one.group_name;
            const group_seq = team_one.group_seq;

            const copy_btn_team_list = systemteamadd_div_team_list.querySelector('.copy_btn_team_list');
            const copy_btn_team_list_clone = copy_btn_team_list.cloneNode(true);
            copy_btn_team_list_clone.classList.remove('copy_btn_team_list');
            copy_btn_team_list_clone.classList.add('btn_team_list');
            copy_btn_team_list_clone.hidden = false;

            copy_btn_team_list_clone.querySelector('.teach_seq').value = teach_seq;
            copy_btn_team_list_clone.querySelector('.teach_name').innerText = teach_name;
            copy_btn_team_list_clone.querySelector('.teach_id').value = teach_id;
            copy_btn_team_list_clone.querySelector('.group_name').innerText = group_name;
            copy_btn_team_list_clone.querySelector('.group_seq').value = group_seq;
            copy_btn_team_list_parent.appendChild(copy_btn_team_list_clone);
        }
        //window close
        systemteamAddWindowClose();
    }

    //각각의 팀목록에 추가된 직원(선생님)의 정보를 가져온다.
    function systemteamAddGetTeamList(btn_team_leader_list, btn_counselor_list, btn_teacher_list) {
        let team_list = [];
        btn_team_leader_list.forEach(function(item) {
            const teach_seq = item.querySelector('.teach_seq').value;
            const teach_name = item.querySelector('.teach_name').innerText;
            const teach_id = item.querySelector('.teach_id').innerText;
            const group_name = item.querySelector('.group_name').value;
            const group_seq = item.querySelector('.group_seq').value;
            team_list.push({
                teach_seq: teach_seq,
                teach_name: teach_name,
                teach_id: teach_id,
                group_name: group_name,
                group_seq: group_seq||6
            });
        });
        btn_counselor_list.forEach(function(item) {
            const teach_seq = item.querySelector('.teach_seq').value;
            const teach_name = item.querySelector('.teach_name').innerText;
            const teach_id = item.querySelector('.teach_id').innerText;
            const group_name = item.querySelector('.group_name').value;
            const group_seq = item.querySelector('.group_seq').value;
            team_list.push({
                teach_seq: teach_seq,
                teach_name: teach_name,
                teach_id: teach_id,
                group_name: group_name,
                group_seq: group_seq||8
            });
        });
        btn_teacher_list.forEach(function(item) {
            const teach_seq = item.querySelector('.teach_seq').value;
            const teach_name = item.querySelector('.teach_name').innerText;
            const teach_id = item.querySelector('.teach_id').innerText;
            const group_name = item.querySelector('.group_name').value;
            const group_seq = item.querySelector('.group_seq').value;
            team_list.push({
                teach_seq: teach_seq,
                teach_name: teach_name,
                teach_id: teach_id,
                group_name: group_name,
                group_seq: group_seq||7
            });
        });
        return team_list;
    }

    //사용자 소속 등록 마지막 확인
    function systemteamAdd() {
        let area_list = "";
        //시 / 도 / 구 를 선택지역에서 배열로 져와서 변수를 만든다.
        const area = document.querySelector('#systemteamadd_sel_sido').value;
        const systemteamadd_div_sel_dong = document.querySelector('#systemteamadd_div_sel_dong');
        const systemteamadd_div_sel_dong_btn = systemteamadd_div_sel_dong.querySelectorAll('.btn_sel_dong');
        for (let i = 0; i < systemteamadd_div_sel_dong_btn.length; i++) {
            const sido = systemteamadd_div_sel_dong_btn[i].querySelector('.sido').value;
            const gu = systemteamadd_div_sel_dong_btn[i].querySelector('.gu').value;
            const dong = systemteamadd_div_sel_dong_btn[i].querySelector('.dong').value;
            //큰 구분자'|', 작은 구분자 ',' 로 구분해서 string으로 만든다.
            if (i != 0) area_list += '|';
            area_list += sido + ',' + gu + ',' + dong;
        }
        // console.log('area_list : ' + area_list);

        //신규일때 소속명(region_name)을 가져온다. 단 소속명을 확인 했는지 확인. 단 기존이면 region_seq를 가져온다.
        const systemteamadd_rdio_existing = document.querySelector('#systemteamadd_rdio_existing');
        const systemteamadd_div_region_name1 = document.querySelector('#systemteamadd_div_region_name1 select');
        const systemteamadd_div_region_name2 = document.querySelector('#systemteamadd_div_region_name2 .region_name');
        const region_name = systemteamadd_rdio_existing.checked ? systemteamadd_div_region_name1.options[
                systemteamadd_div_region_name1.selectedIndex].innerText :
            systemteamadd_div_region_name2.value;
        const region_seq = systemteamadd_rdio_existing.checked ? systemteamadd_div_region_name1.value : '';
        //신규일때 소속명 확인을 했는지 확인.
        if (systemteamadd_rdio_existing.checked == false) {
            if (systemteamadd_div_region_name2.classList.contains('text-primary') == false) {
                sAlert('', '소속명을 확인해주세요.');
                return;
            }
        }
        // console.log('region_name : ' + region_name);

        //팀명을 가져온다 팀명을 확인했는지 확인 systemteamAddTeamNameChk / text-primary
        const systemteamadd_inp_team_name = document.querySelector('#systemteamadd_inp_team_name');
        if (systemteamadd_inp_team_name.classList.contains('text-primary') == false) {
            sAlert('', '팀명을 확인해주세요.');
            return;
        }
        const team_name = systemteamadd_inp_team_name.value;
        // console.log('team_name : ' + team_name);

        //총괄매니저를 가져온다. 
        const systemteamadd_sel_general_manager = document.querySelector('#systemteamadd_sel_general_manager');
        const general_manager = systemteamadd_sel_general_manager.value;
        if (general_manager == '') {
            sAlert('', '총괄매니저를 선택해주세요.');
            return;
        }
        // console.log('general_manager : ' + general_manager);

        //팀원을 가져온다. teach_seq 만 가져온다.
        //팀원은 단 팀원은 없을 수 있으며 없으면 알림(계속 진행할지에 대한)을 띄워준다. 
        //마찬가지로 구분자로 구분해서 string으로 만든다.
        let team_list = "";
        const systemteamadd_div_team_list = document.querySelectorAll('#systemteamadd_div_team_list .btn_team_list');
        for (let i = 0; i < systemteamadd_div_team_list.length; i++) {
            const teach_seq = systemteamadd_div_team_list[i].querySelector('.teach_seq').value;
            if (i != 0) team_list += '|';
            team_list += teach_seq;
        }
        console.log('team_list : ' + team_list);
        if (team_list == '') {
            sAlert('', '팀원이 없습니다. 그래도 계속 진행하시겠습니까?', '2', function() {
                systemteamAddInsert(area_list, region_seq, region_name, team_name, general_manager, team_list);
            });
            return;
        }else{
            systemteamAddInsert(area_list, region_seq, region_name, team_name, general_manager, team_list);
        }
    }

    //소속 등록
    //팀 등록
    //사용자 소속 등록
    function systemteamAddInsert(area_list, region_seq, region_name, team_name, general_manager, team_list) {
        const area = document.querySelector('#systemteamadd_sel_sido').value;
        const team_code = document.querySelector('#systemteamadd_inp_team_code').value;
        const parameter = {
            team_code:team_code,
            area:area,
            area_list: area_list,
            region_seq: region_seq,
            region_name: region_name,
            team_name: team_name,
            general_manager: general_manager,
            team_list: team_list
        };
        const page = '/manage/systemteam/teamgroup/insert';
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                const title = team_code.length > 0 ? '수정완료' : '등록완료';
                const msg = team_code.length > 0 ? '수정이 완료되었습니다.' : '등록이 완료되었습니다.';
                sAlert(title, msg, '1', function() {
                    //[목록으로 돌아가기] 창 닫기 / 초기화
                    systemteamAddClose();
                    //부모창 소속 리스트 새로고침
                    systemteamGroupList();
                });
            } else {
                sAlert('등록실패', '등록에 실패하였습니다.');
            }
        });

    }

    //사용자 소속 초기화
    function systemteamAddReset() {
        //담당지역 초기화
        systemteamAddAreaReset();
        //소속명 초기화
        systemteamAddRegionNameReset();
        //팀명 초기화
        systemteamAddTeamNameReset();
        //총괄매니저 초기화
        systemteamAddGeneralManagerReset();
        //팀원 초기화
        systemteamAddTeamReset();

        const systemteamadd_btn_add = document.querySelector('#systemteamadd_btn_add');
        systemteamadd_btn_add.innerText = '등록';

        const systemteam_div_team_add = document.querySelector('#systemteam_div_team_add');
        systemteam_div_team_add.querySelectorAll('.add_edit_tag').forEach(function(item){
            item.hidden = true;
        });
        systemteam_div_team_add.querySelectorAll('.none_edit_tag').forEach(function(item){
            item.hidden = false;
        });

    }

    //담당지역 초기화
    function systemteamAddAreaReset(){
        //수정일경우 team_code 초기화
        const systemteamadd_inp_team_code = document.querySelector('#systemteamadd_inp_team_code');
        systemteamadd_inp_team_code.value = '';

        //당담지역 (대분류)
        const systemteamadd_sel_sido = document.querySelector('#systemteamadd_sel_sido');
        systemteamadd_sel_sido.value = '';

        //담당지역 (중분류)
        const systemteamadd_sel_gu1 = document.querySelector('#systemteamadd_sel_gu1');
        systemteamadd_sel_gu1.value = '';
        const systemteamadd_sel_gu2 = document.querySelector('#systemteamadd_sel_gu2');
        systemteamadd_sel_gu2.value = '';
        const systemteamadd_sel_gu3 = document.querySelector('#systemteamadd_sel_gu3');
        systemteamadd_sel_gu3.value = '';

        //담당지역 (소분류)
        const systemteamadd_btncheck1 = document.querySelector('#systemteamadd_btncheck1');
        systemteamadd_btncheck1.checked = false;
        systemteamadd_btncheck1.nextElementSibling.nextElementSibling.innerText = '전체';
        systemteamAddDongReset();

        //선택지역
        const systemteamadd_div_sel_dong = document.querySelector('#systemteamadd_div_sel_dong');
        const btn_sel_dong = systemteamadd_div_sel_dong.querySelectorAll('.btn_sel_dong');
        for (let i = 0; i < btn_sel_dong.length; i++) {
            btn_sel_dong[i].remove();
        }
    }

    //소속명 초기화
    function systemteamAddRegionNameReset(){
        const systemteamadd_rdio_existing = document.querySelector('#systemteamadd_rdio_existing');
        systemteamadd_rdio_existing.checked = true;
        //systemteamadd_div_region_name2 안에 text-primary 를 지운다.
        const systemteamadd_div_region_name2 = document.querySelector('#systemteamadd_div_region_name2 .region_name');
        systemteamadd_div_region_name2.classList.remove('text-primary');
        systemteamadd_div_region_name2.value = '';
    }

    //팀명 초기화
    function systemteamAddTeamNameReset(){
        const systemteamadd_inp_team_name = document.querySelector('#systemteamadd_inp_team_name');
        systemteamadd_inp_team_name.classList.remove('text-primary');
        systemteamadd_inp_team_name.value = '';
    }

    //총괄매니저 초기화
    function systemteamAddGeneralManagerReset(){
        //기존 체크.
        const systemteamadd_rdio_general_manager1 = document.querySelector('#systemteamadd_rdio_general_manager1');
        systemteamadd_rdio_general_manager1.checked = true;
        systemteamadd_rdio_general_manager1.onchange();
    }

    //팀원 초기화
    function systemteamAddTeamReset(){
        const systemteamadd_div_team_list = document.querySelector('#systemteamadd_div_team_list');
        const btn_team_list = systemteamadd_div_team_list.querySelectorAll('.btn_team_list');
        for (let i = 0; i < btn_team_list.length; i++) {
            btn_team_list[i].remove();
        }
    }
    //팀원 추가 창 팀구성원 초기화.
    function systemteamAddTeamWindowReset(){
        const btn_list1 = systemteamadd_div_team_add_window.querySelectorAll('.btn_team_leader_list');
        const btn_list2 = systemteamadd_div_team_add_window.querySelectorAll('.btn_counselor_list');
        const btn_list3 = systemteamadd_div_team_add_window.querySelectorAll('.btn_teacher_list');
        //모두 클리어
        btn_list1.forEach(function(item) {
            item.remove();
        });
        btn_list2.forEach(function(item) {
            item.remove();
        });
        btn_list3.forEach(function(item) {
            item.remove();
        });
    }

    //소속 / 팀 목록 수정시 모든 정보 불러오기.
    function systemteamAddGetEditInfo(team_code, tag_tr){
        // 기존 소속명 채우기.
        systemteamAddGetRegionList();
        
        const region_seq = tag_tr.querySelector('.region_seq').value;
        const team_name = tag_tr.querySelector('.team_name').innerText;

        const systemteam_div_team_add = document.querySelector('#systemteam_div_team_add');
        systemteam_div_team_add.querySelectorAll('.add_edit_tag').forEach(function(item){
            item.hidden = false;
        });
        systemteam_div_team_add.querySelectorAll('.none_edit_tag').forEach(function(item){
            item.hidden = true;
        });
        const systemteamadd_btn_add = document.querySelector('#systemteamadd_btn_add');
        systemteamadd_btn_add.innerText = '변경사항 저장';

        //담당지역 정보 넣기. 
        systemteamAddGetTeamArea(team_code, tag_tr);

        //소속명 선택 
        const systemteamadd_div_region_name1 = document.querySelector('#systemteamadd_div_region_name1 select');
        setTimeout(() => {
            systemteamadd_div_region_name1.value = region_seq;
            systemteamadd_div_region_name1.onchange();    
        }, 500);
        
        //팀명
        const systemteamadd_inp_team_name = document.querySelector('#systemteamadd_inp_team_name');
        systemteamadd_inp_team_name.value = team_name;
        systemteamadd_inp_team_name.classList.add('text-primary');

        //팀 구성
        systemteamAddGetTeamListForEdit(team_code);
        
    }

    //담당지역 가져와서 넣기
    function systemteamAddGetTeamArea(team_code, tag_tr){
        let tr_sido = tag_tr.querySelector('.sido').innerText;
        if(tr_sido.indexOf(',') > -1) tr_sido = tr_sido.split(',')[0];
        const area_sido = tr_sido;
        let array_gu = tag_tr.querySelector('.gu').innerText.split(',');

        //담당지역 (대분류)
        // const systemteamadd_sel_sido = document.querySelector('#systemteamadd_sel_sido');
        // if(systemteamadd_sel_sido.value == '')  {
        //     systemteamadd_sel_sido.value = area_sido;
        //     systemteamadd_sel_sido.onchange()
        // }

        //담당지역 (중분류) 
        // for(let i = 0; i < array_gu.length; i++){
        //     if(i > 2) break;
        //     const gu = array_gu[i];
        //     const systemteamadd_sel_gu = document.querySelector('#systemteamadd_sel_gu' + (i + 1));
        //     systemteamadd_sel_gu.value = gu;
        //     // systemteamadd_sel_gu.value = gu; 를 했지만 없는 option 이면 ''으로 다시 선택 한다.
        //     if(systemteamadd_sel_gu.value != gu) systemteamadd_sel_gu.value = '';
        // }
        
        //선택지역
        const parameter = {
            team_code:team_code
        };
        const page = '/manage/systemteam/team/area/select';
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                const systemteamadd_div_sel_dong = document.querySelector('#systemteamadd_div_sel_dong');

                for(let i = 0; i < result.resultData.length; i++){
                    const r_data = result.resultData[i];
                    const sido = r_data.tarea_sido;
                    const gu = r_data.tarea_gu;
                    const dong = r_data.tarea_dong;

                    const btn_sel_dong = systemteamadd_div_sel_dong.querySelector('.copy_btn_sel_dong').cloneNode(true);
                    btn_sel_dong.classList.remove('copy_btn_sel_dong');
                    btn_sel_dong.classList.add('btn_sel_dong');
                    btn_sel_dong.querySelector('.dong_text').innerText =sido+' '+gu+' '+dong;
                    btn_sel_dong.querySelector('.sido').value = sido;
                    btn_sel_dong.querySelector('.gu').value = gu;
                    btn_sel_dong.querySelector('.dong').value = dong;
                    btn_sel_dong.hidden = false;   
                    systemteamadd_div_sel_dong.appendChild(btn_sel_dong);
                }

                
            }
        });
    }

    //팀구성 가져와서 적용.
    function systemteamAddGetTeamListForEdit(team_code){
        const parameter = {
            team_code:team_code,
            is_not_gm:'Y'
        };
        const page = '/manage/systemteam/teacher/select';
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                const systemteamadd_div_team_list = document.querySelector('#systemteamadd_div_team_list');
                const copy_btn_team_list = systemteamadd_div_team_list.querySelector('.copy_btn_team_list');
                const copy_btn_team_list_clone = copy_btn_team_list.cloneNode(true);
                const copy_btn_team_list_parent = copy_btn_team_list.parentElement;
                copy_btn_team_list_parent.innerHTML = '';
                copy_btn_team_list_parent.appendChild(copy_btn_team_list_clone);
                //팀구성을 만든다.
                for (let i = 0; i < result.resultData.length; i++) {
                    const r_data = result.resultData[i];
                    const teach_seq = r_data.id;
                    const teach_name = r_data.teach_name;
                    const teach_id = r_data.teach_id;
                    const group_name = r_data.group_name;
                    const group_seq = r_data.group_seq;

                    const copy_btn_team_list = systemteamadd_div_team_list.querySelector('.copy_btn_team_list');
                    const copy_btn_team_list_clone = copy_btn_team_list.cloneNode(true);
                    copy_btn_team_list_clone.classList.remove('copy_btn_team_list');
                    copy_btn_team_list_clone.classList.add('btn_team_list');
                    copy_btn_team_list_clone.hidden = false;    
                    copy_btn_team_list_clone.querySelector('.teach_seq').value = teach_seq;
                    copy_btn_team_list_clone.querySelector('.teach_name').innerText = teach_name;
                    copy_btn_team_list_clone.querySelector('.teach_id').value = teach_id;
                    copy_btn_team_list_clone.querySelector('.group_name').innerText = group_name;
                    copy_btn_team_list_clone.querySelector('.group_seq').value = group_seq;
                    copy_btn_team_list_parent.appendChild(copy_btn_team_list_clone);
                }
            }
        });
    }

    //소속 / 팀 목록 삭제
    function systemteamAddDelete(){
        const team_code = document.querySelector('#systemteamadd_inp_team_code').value;
        if(team_code == ''){
            sAlert('', '삭제할 팀이 없습니다. 다시 시도해주세요.');
            return;
        }
        sAlert('DELETE', '팀을 삭제하시겠습니까?', '2', function(){
            const parameter = {
                team_code:team_code
            };
            const page = '/manage/systemteam/team/delete';
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    sAlert('삭제완료', '삭제가 완료되었습니다.', '1', function() {
                        //[목록으로 돌아가기] 창 닫기 / 초기화
                        systemteamAddClose();
                        //부모창 소속 리스트 새로고침       
                        systemteamGroupList();
                    });
                } else {
                    sAlert('삭제실패', '삭제에 실패하였습니다.');
                }
            });
        });
    }

    //
    function systemteamAddChkAll(vthis){
        event.stopPropagation();
        const systemteamadd_div_team_add_window = document.querySelector('#systemteamadd_div_team_add_window');
        const teacher_list_chk = systemteamadd_div_team_add_window.querySelectorAll('.teacher_list_chk');
        teacher_list_chk.forEach(function(item){
            item.checked = vthis.checked;
        });
    }
</script>
