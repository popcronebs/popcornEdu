@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
    사용자 목록
@endsection

{{-- 네브바 체크 --}}
@section('user_management')
@endsection
@section('userlist')
    active
@endsection

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<style>
    .btn-open::after{
        content: '▼';
    }
    .btn-open.active::after{
        content: '▲';
    }
    .tr-disabled td{
        background-color: #cacaca;
    }
    
</style>
    {{-- 왼쪽 그룹 테이블 --}}
    <div class="col-12 pe-3 ps-3 mb-3 pt-3 row position-relative">
        <div id="userlist_div_user_select" class="col-2" hidden>
            <table id="userlist_tb_usergroup" class="table">
                <thead>
                    <tr class="tr_user_group cursor-pointer" onclick="clickTrUserGroup(this);">
                        <td>
                            <span>전체사용자</span>
                            (<span class="usergroup_all_cnt">{{$allCnt}}</span>)
                        </td>
                        <input type="hidden" class="group_type" value="all_user">
                    </tr>
                    <tr class="tr_user_group cursor-pointer" onclick="clickTrUserGroup(this);">
                        <td>
                            <span>그룹없음</span>
                            (<span class="usergroup_none_cnt">{{$notGroupCnt}}</span>)
                        </td>
                        <input type="hidden" class="group_type" value="none_group_user">
                    </tr>
                </thead>
                <tbody id="userlist_tby_usergroup">
                    <tr class="copy_tr_user_group cursor-pointer" hidden onclick="clickTrUserGroup(this);">
                        <td>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <span class="group_name">#그룹이름</span>
                                    (<span class="usergroup_list_cnt"></span>)
                                </div>
                                <div class="bg-white rounded">
                                    <button class="btn btn-outline-secondary" onclick="userlistOpenUserGroupUpdateWindow(this)">수정</button>
                                </div>
                            </div>
                        </td>
                        <input type="hidden" class="seq">
                        <input type="hidden" class="sq">
                        <input type="hidden" class="group_type">
                        <input type="hidden" class="group_type2">
                        <input type="hidden" class="remark">
                        <input type="hidden" class="first_page">
                        <input type="hidden" class="is_use">
                    </tr>
                    {{-- $user_group를 count()만큼 for를 진행한다. --}}
                    
                    @if (isset($user_group) && count($user_group) > 0)
                        @foreach ($user_group as $group)
                            @php ($count = 0)
                            @if($group['group_type'] == 'teacher')
                                @php ($count = $group['t_cnt'] == null ? 0 : $group['t_cnt'])
                            @elseif(strpos($group['group_type'], 'student') !== false)
                                @php ($count = $group['s_cnt'] == null ? 0 : $group['s_cnt'])
                            @elseif($group['group_type'] == 'parent')
                                @php ($count = $group['p_cnt'] == null ? 0 : $group['p_cnt'])
                            @endif

                            <tr class="tr_user_group cursor-pointer" onclick="clickTrUserGroup(this);">
                                <td>
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <span class="group_name">{{ $group['group_name'] }}</span>
                                            (<span class="usergroup_list_cnt">{{$count}}</span>)
                                        </div>
                                        <div class="bg-white rounded">
                                            <button class="btn btn-outline-secondary" onclick="userlistOpenUserGroupUpdateWindow(this)">수정</button>
                                        </div>
                                    </div>
                                </td>
                                <input type="hidden" class="seq" value="{{ $group['id'] }}">
                                <input type="hidden" class="sq" value="{{ $group['sq'] }}">
                                <input type="hidden" class="group_type" value="{{ $group['group_type'] }}">
                                <input type="hidden" class="group_type2" value="{{ $group['group_type2'] }}">
                                <input type="hidden" class="remark" value="{{ $group['remark'] }}">
                                <input type="hidden" class="first_page" value="{{ $group['first_page'] }}">
                                <input type="hidden" class="is_use" value="{{ $group['is_use'] }}">
                            </tr>
                        @endforeach
                    @endif
                </tbody>
                <tfoot>
                    <tr class="cursor-pointer">
                        <td colspan="2">
                           <span class="btn btn-outline-primary w-100" onclick="userlistOpenUserGroupWindow();">+새그룹추가</span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div id="userlist_div_user_list" class="col">
            {{-- 컨텐트 상단 버튼 --}}
            <div class="d-flex justify-content-between">
                <div>
                    <button type="button" class="btn btn-outline-primary" onclick="userlistSelectUserGroupView();">사용자 선택</button>
                </div>
                <div>
                    <button type="button" class="btn btn-outline-primary" onclick="userlistUserExcelDownload();">엑셀 내보내기</button>
                    <button type="button" class="btn btn-outline-success" onclick="userlistUserExcelAdd();">엑셀불러오기(일괄등록)</button>
                    <button type="button" class="btn btn-outline-primary" onclick="userlistUserAdd();">사용자 등록</button>
                </div>
            </div>
            {{-- 회원 검색 조건 --}}
            <div class="d-flex gap-2 mt-2 mb-2">
                <div class="border border d-flex col-8 gap-1">
                    <input type="hidden" id="userlist_inp_schtype">
                    <div class="border p-2 d-inline-block col-1" style="min-width:80px;">회원검색</div>
                    <select class="form-select d-inline-block" id="userlist_sel_sch_type">
                        <option selected value="id">아이디</option>
                        <option value="phone">휴대폰번호</option>
                        <option value="school">학교</option>
                        <option value="grade">학년</option>
                        <option value="name">회원명</option>
                        <option value="goods">이용권</option>
                        <option value="parent">학부모</option>
                        <option value="teacher">담당선생님</option>
                        <option value="group">직위</option>
                    </select>
                    <input type="text" id="userlist_sel_sch_text" class="form-control d-inline-block" onkeyup="if(event.keyCode == 13){userlistSelectUser();}">
                    <div class="border rounded p-2 d-inline-block col-1">소속</div>
                    <select id="userlist_sel_region" class="form-select d-inline-block" onchange="userlistSelectRegion(this, '#userlist_sel_team', '팀전체');">
                        <option value="">소속전체</option>
                        @if(isset($region) && count($region) > 0)
                            @foreach ($region as $reg)
                                <option value="{{ $reg['id'] }}" area="{{ $reg['area'] }}">{{ $reg['region_name'] }}</option>
                            @endforeach
                        @endif
                    </select>                    
                    <select id="userlist_sel_team" class="form-select d-inline-block team">
                        <option value="" >팀전체</option>
                    </select>
                    <button type="button" class="btn btn-outline-secondary d-inline-block col-1 border" onclick="userlistSelectUser();">검색</button>
                </div>
                <div class="d-flex col-4 gap-3">
                    <input id="userlist_inp_schstartdate" type="date" class="form-control text-center" style="line-height:12px;width: 250px;" value="<?=date('Y-m-d');?>">
                    <span style="padding-top: 5px;">~</span>
                    <input id="userlist_inp_schenddate" type="date" class="form-control text-center" style="line-height:12px;width: 250px;" value="<?=date('Y-m-d');?>">
                    <select class="form-select d-inline-block" onchange="userlistChangeSchUser(this);" style="width:auto;">
                        <option selected value="">전체회원</option>
                        <option value="expired">만료회원</option>
                        <option value="available">유효회원</option>
                        <option value="use">활성화</option>
                        <option value="notuse">비활성화</option>
                    </select>
                </div>
            </div>
            <div class="d-flex gap-3">
                <div class="w-100">
                    <div class="w-100 overflow-auto border-top border-bottom tableFixedHead" style="height: calc(100vh - 351px);">
                        {{-- 오른쪽 회원 테이블 --}}
                        <table id="userilst_tb_userinfo" class="table table-bordered text-center align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center all_show" scope="col" onclick="event.stopPropagation();this.querySelector('input').click();">
                                        <input type="checkbox" onclick="userlistCheckAll(this);">
                                    </th>
                                    <th class=" all_show" scope="col">구분</th>
                                    <th class=" all_show" scope="col">회원명/아이디</th>
                                    <th class=" none_group" scope="col" hidden>전화번호</th>
                                    <th class=" none_group" scope="col" hidden>가입일</th>
                                    <th class=" pt_show" scope="col" hidden>자녀</th>
                                    <th class=" not_teach_show" scope="col">지역</th>
                                    <th class=" not_teach_show" scope="col">포인트</th>
                                    <th class=" not_teach_show" scope="col">이용권</th>
                                    <th class=" not_teach_show" scope="col">이용권 기간</th>
                                    <th class=" not_teach_show" scope="col">유효구분</th>
                                    <th class=" st_show" scope="col">학부모</th>
                                    <th class=" not_teach_show" scope="col">담당 선생님</th>
                                    <th class=" teach_show" scope="col" hidden>소속</th>
                                    <th class=" teach_show" scope="col" hidden>관할</th>
                                    <th class=" teach_show" scope="col" hidden>입사일 구분</th>
                                    <th class=" teach_show" scope="col" hidden>재직상태</th>
                                    <th class=" none_group" scope="col" hidden>등록자</th>
                                    <th class="" scope="col">수정내역</th>
                                    <th class="" scope="col">이용 활성화</th>
                                    <th class="" scope="col"></th>
                                </tr>
                            </thead>
                            <tbody id="userilst_tby_userinfo">
                                <tr class="copy_tr_userinfo" hidden>
                                    <input type="hidden" class="inp_user_key">
                                    <input type="hidden" class="inp_user_type">
                                    <input type="hidden" class="group_seq">
                                    <input type="hidden" class="school_name">
                                    <input type="hidden" class="email">
                                    <td class="text-center td_chk" onclick="event.stopPropagation();this.querySelector('input').click();" rowspan="1">
                                        <input id="inp_cb_userinfo" class="cursor-pointer" type="checkbox" aria-label="Checkbox for following text input" name="inp_cb_userinfo" onclick="event.stopPropagation()">
                                    </td>
                                    <td class="text-center td_group_name" rowspan="1">#구분</td>
                                    <td class="text-center td_user_name" rowspan="1">
                                        <div class="div_user_name">
                                            <span class="sp_user_name fix">#회원명</span>
                                            <input class="text-center inp_user_name modi" type="text" 
                                            style="width:100px;"
                                            hidden>
                                        </div>
                                        <div class="div_user_id">#아이디</div>
                                    </td>
                                    <td class="text-center td_user_phone none_group" hidden>#전화번호</td>
                                    <td class="text-center td_user_join_date none_group" hidden>#가입일</td>
                                    <td class="text-center td_child pt_show" hidden>
                                        <div class="div_child_name">
                                            <span class="sp_child_name fix">#자녀명</span>
                                            <input type="text" class="text-center inp_child_name modi" 
                                            style="width:100px;"
                                            hidden>
                                        </div>
                                        <div class="div_child_id">#자녀아이디</div>
                                    </td>
                                    <td class="text-center td_area not_teach_show">#지역</td>
                                    <td class="text-center td_point not_teach_show">
                                        <span class="sp_point fix">#포인트</span>
                                        {{-- <input class="text-center inp_point modi border" style="width:50px;" hidden> --}}
                                    </td>
                                    <td class="text-center td_goods not_teach_show cursor-pointer" onclick="userlistGoodsDetail(this);">
                                        <span class="sp_goods fix" data="#이용권"></span>
                                        <a class="a_goods_status" href="javascript:void(0)" hidden></a>
                                        <input type="hidden" class="goods_period">
                                        <span clsss="sp_goods_modi modi" hidden></span>
                                    </td>
                                    <td class="text-center td_period not_teach_show cursor-pointer" onclick="userlistGoodsDetail(this);">
                                        <div>
                                            <span class="sp_goods_start_date" data="#이용권시작"></span>
                                            {{-- <input type="date" class="form-control text-center inp_start_date modi" hidden> --}}
                                        </div>
                                        <div>
                                            <span class="sp_goods_end_date" data="#이용권끝"></span>
                                            {{-- <input type="date" class="form-control text-center inp_end_date modi" hidden> --}}
                                        </div>
                                    </td>
                                    <td class="text-center td_goods_status not_teach_show" data="#유효구분"></td>
                                    <td class="text-center td_parent st_show">
                                        <div class="div_parent_name">
                                            <span class="sp_parent_name fix">#학부모이름</span>
                                            <input type="text" class="text-center inp_parent_name modi" 
                                            style="width: 100px;"
                                            hidden>
                                        </div>
                                        <div class="div_parent_id">#학부모아이디</div>
                                        <input type="hidden" class="parent_seq">
                                    </td>
                                    <td class="text-center td_teacher not_teach_show">
                                        <span class="sp_teacher_name fix">#담당 선생님</span>
                                        {{-- <input class="sp_teacher_name_modi modi" hidden></input> --}}
                                        <input type="hidden" class="teach_seq">
                                        <input type="hidden" class="teach_region_seq">
                                        <input type="hidden" class="teach_team_code">
                                    </td>
                                    <td class="text-center td_region teach_show" hidden>#소속</td>
                                    <td class="text-center td_team teach_show" hidden>#관할</td>
                                    <td class="text-center teach_show" hidden>
                                        <div class="join_date">#입사일 구분</div>
                                        <div class="resignation_date text-danger"></div>
                                    </td>
                                    <td class="text-center teach_show cursor-pointer" hidden onclick="userlistChangeUserStatusModal(this);">
                                        <span class="sp_user_status_str">#재직상태</span>
                                        <input type="hidden" class="inp_user_status">
                                    </td>
                                    <td class="text-center td_reg_name none_group" hidden>#등록자</td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-outline-secondary" onclick="userlistUserHistoryModal(this)">상세</button>
                                        <button class="btn btn-sm btn-outline-primary" onclick="userlistUserEdit(this);">수정</button>
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input chk_is_use" type="radio" name="exampleRadios"
                                                id="exampleRadios1" value="option1" checked>
                                            <label class="form-check-label" for="exampleRadios1">
                                                활성화
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input chk_is_use" type="radio" name="exampleRadios"
                                                id="exampleRadios2" value="option2">
                                            <label class="form-check-label" for="exampleRadios2">
                                                비활성화
                                            </label>
                                        </div>
                                        <input type="hidden" class="is_use">
                                    </td>
                                    <td class="text-center">
                                        <button id="userlist_btn_edit" class="btn btn-sm btn-outline-secondary" onclick="userlistTrEdit(this);">수정</button>
                                        <button id="userlist_btn_edit_save" class="btn btn-sm btn-outline-primary" onclick="userlistTrEditUpdate(this);" hidden>저장</button>
                                        <button id="userlist_btn_edit_cancel" class="btn btn-sm btn-outline-danger" onclick="userlistTrEditCancel(this);" hidden>취소</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    {{-- 하단 버튼 그룹 전체/그룹없음 --}}
                    <div id="userlist_div_bottom_button" class="input-group mb-3" >
                        <div class="d-flex w-100 gap-1 mt-1">
                            <button type="button" class="btn btn-outline-secondary col-2 btn-sm" onclick="userlistSendSms();">SMS 문자 / 알림톡 / 푸시 발송</button>
                            <button type="button" class="btn btn-outline-secondary col-2" onclick="userlistChangeUseStatus(true);">선택 회원 활성화</button>
                            <button type="button" class="btn btn-outline-secondary col-2" onclick="userlistChangeUseStatus(false);">선택 회원 비활성화</button>
                            <select id="userlist_sel_change_usergroup" class="change_usergroup" hidden>
                                <option value="">미배정</option>
                                    @if (isset($user_group) && count($user_group) > 0)
                                        @foreach ($user_group as $group)
                                            <option value="{{ $group['id'] }}" group_type="{{ $group['group_type'] }}">{{ $group['group_name'] }}</option>
                                        @endforeach
                                    @endif
                            </select>
                            <button id="userlist_btn_groupinsert" type="button" class="btn btn-outline-secondary col-2" 
                            onclick="userlistChangeUserGroup();"
                            hidden>선택 회원 그룹등록</button>
                        </div>
                    </div>
                    {{-- 하단 버튼 그룹 학생/학부모 --}}
                    <div id="userlist_div_bottom_user_button" class="input-group mb-3" hidden>
                        <div class="d-flex w-100 gap-1 mt-1">
                            <button type="button" class="btn btn-outline-secondary col-2 btn-sm" onclick="userlistSendSms();">SMS 문자 / 알림톡 / 푸시 발송</button>
                            <button type="button" class="btn btn-outline-secondary col-2">사이트 팝업</button>
                            <button type="button" class="btn btn-outline-secondary col-2" onclick="userlistChangeUseStatus(true);">선택 회원 활성화</button>
                            <div class="input-group col-2" style="width:10%">
                                <input type="number" class="form-control" placeholder="0" id="userlist_inp_day_manage"
                                    aria-label="Example text with button addon" aria-describedby="button-addon2">
                                <button class="btn btn-outline-secondary" type="button" id="button-addon2">일</button>
                            </div>
                            {{-- <button type="button" class="btn btn-outline-secondary col-1" onclick="userlistSelDayManage();">선택 회원 연장</button> --}}
                            <button type="button" class="btn btn-outline-secondary col-1" onclick="userlistGoodsDayPlusModal();">선택 회원 연장</button>
                            <button type="button" class="btn btn-outline-secondary col-1" onclick="userlistGoodsDayStopModal();">선택 회원 정지</button>

                            <button type="button" class="btn btn-outline-primary col-1" onclick="userlistSelDayManageAll()">일괄 기간 연장</button>
                            <button type="button" class="btn btn-outline-primary col-1" onclick="userlistSelDayStopAll()">일괄 기간 정지</button>

                        </div>
                        <div class="d-flex w-100 gap-1 mt-2">
                            <button type="button" class="btn btn-outline-secondary col-2">학습 플래너 수정</button>
                            <button type="button" class="btn btn-outline-secondary col-2" onclick="userlistChangeTeacherModal()">담당선생님 변경</button>
                            <button type="button" class="btn btn-outline-secondary col-2" onclick="userlistChangeUseStatus(false);">선택 회원 비활성화</button>
                            <div class="input-group col-2" style="width:10%">
                                <input type="number" class="form-control" placeholder="0" id="userlist_inp_point_manage"
                                    aria-label="Example text with button addon" aria-describedby="button-addon2">
                                <button class="btn btn-outline-secondary" type="button" id="button-addon2">점</button>
                            </div>
                            <button type="button" class="btn btn-outline-secondary col-2" onclick="userlistSelPointManage();">선택 회원 포인트 지급</button>
                            <button type="button" class="btn btn-outline-primary col-2" onclick="userlistSelPointManageAll();">일괄 포인트 지급</button>
                        </div>
                    </div>
                    {{-- 하단 버튼 그룹 운영 --}}
                    <div id="userlist_div_bottom_manage_button" class="input-group mb-3" hidden>
                        <div class="d-flex w-100 gap-1 mt-1" >
                            <button type="button" class="btn btn-outline-secondary col-2 btn-sm">SMS 문자 / 알림톡 / 푸시 발송</button>
                            <button type="button" class="btn btn-outline-secondary col-2" onclick="userlistChangeUseStatus(true)">선택 회원 활성화</button>
                            <div class="d-flex justify-content-between">
                                <select id="userlist_sel_change_region" onchange="userlistSelectRegion(this, '#userlist_sel_change_team');">
                                    <option value="">소속 선택</option>
                                    @if(isset($region) && count($region) > 0)
                                        @foreach ($region as $reg)
                                            <option value="{{ $reg['id'] }}" area="{{ $reg['area'] }}">{{ $reg['region_name'] }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <select id="userlist_sel_change_team" class="team">
                                    <option value="">미배정</option>
                                </select>
                            </div>
                            {{-- 선택 회원 소속/관할 변경 --}}
                            <button type="button" class="btn btn-outline-secondary col-2" onclick="userlistChgRegionTeam();">선택 회원 소속/관할 변경</button>
                        </div>
                        <div class="d-flex w-100 gap-1 mt-2">
                            <button type="button" class="btn btn-outline-secondary col-2">사이트 팝업</button>
                            <button type="button" class="btn btn-outline-secondary col-2" onclick="userlistChangeUseStatus(false)">선택 회원 비활성화</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="userlist_div_user_add" class="position-absolute w-100 h-100 bg-white" style="top: 0; left: 0; z-index:3"
            hidden>
            {{-- $user_group include에 넘거준다. --}}
            @include('admin.admin_user_add_detail', ['user_group'=>$user_group, 'address_sido' => $address_sido, 'region' => $region, 'team' => $team])
        </div>
        {{-- 새그룹추가 창 / 그룹이름, 그룹유형 --}}
        <div id="userlist_div_user_group"
            class="position-absolute bg-white border border col-3"
            style="top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 3;" hidden>
            <h5 class="pt-3">사용자 그룹 추가 / 수정</h5>
            <input type="hidden" class="seq">
            <div class="col-12 pt-2 pb-3">
                <div>
                    {{-- 그룹이름, 그룹유형 input추가 --}}
                    <div class="d-flex flex-column justify-content-between gap-2">
                        <div class="col-12">
                            <input type="text" class="group_name form-control" placeholder="그룹이름">
                        </div>
                        <div class="col-12">
                            <input type="text" class="remark form-control" placeholder="그룹설명">
                        </div>
                        <div class="col-12">
                            <select class="group_type1 form-select" onchange="userlistChkGroupTypeSelectTag(this)">
                                <option value="" selected>그룹유형1</option>
                                <option value="normal">일반</option>
                                <option value="teacher">운영</option>
                            </select>
                            {{-- 중분류 추가 / 일반 선택시 학생, 학부모--}}
                            <select  class="group_type2 form-select mt-2" hidden onchange="userlistChkFirstPageAllHidden()">
                                <option value="" selected>그룹유형2</option>
                                <option value="student">학생</option>
                                <option value="parent">학부모</option>
                            </select>
                            <div id="userlist_div_general" class="m-2" hidden>
                                <input type="checkbox" id="userlist_chk_general">
                                <label for="userlist_chk_general">'총괄매니저'면 체크</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <select class="is_use form-select">
                                <option value="" selected>사용여부 선택</option>
                                <option value="Y">사용</option>
                                <option value="N">미사용</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <select class="first_page form-select">
                                <option value="" selected>첫화면 선택</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-center pt-3">
                        <button class="btn btn-outline-secondary" onclick="userlistUserGroupAddWindowClose();">취소</button>
                        <button class="btn btn-outline-primary" onclick="userlistUserGroupInsert();">저장</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 모달 / 선택 회원 포인트 지금 --}}
        <div class="modal fade" id="userlist_modal_point_manage" tabindex="-1" aria-hidden="true"
        style="display: none;">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">회원 포인트 관리</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close" onclick="userlistModalPointManageClear();"></button>
                    </div>
                    <div class="modal-body d-flex gap-3" style="height: 250px">
                        {{-- 한명일때 히스토리 확인 --}}
                        <div class="div_point_history col-7">
                            <div class="overflow-auto" style="height:215px">
                                <table class="table table-bordered text-center align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            {{-- 포인트, 생성자, 날짜, 비고 --}}
                                            <td class="p-1" style="width:60px">포인트</td>
                                            <td class="p-1" style="width:100px;">생성자</td>
                                            <td class="p-1">날짜</td>
                                            <td class="p-1">비고</td>
                                        </tr>
                                    </thead>
                                    <tbody id="userlist_tby_point_history">
                                        <tr class="copy_tr_point_history" hidden>
                                            <td class="p-1 point" data=내역></td>
                                            <td class="p-1 created_name" data=생성자></td>
                                            <td class="p-1 created_at" data=날짜></td>
                                            <td class="p-1 remark" data=비고></td>
                                        </tr>
                                    </tbody>
                                </table>
                                {{-- 내역이 없습니다 --}}
                                <div id="userlist_div_point_manage_none" class="text-center" hidden>
                                    <span>내역이 없습니다.</span>
                                </div>
                                <div id="userlist_div_point_manage_delete" hidden>
                                    <button class="btn btn-sm btn-outline-danger">선택 내역 삭제</button>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            {{-- div add = label 포인트 , input type number / div add = label 비고 textarea --}}
                            <div>
                                <label for="userlist_inp_point">포인트</label>
                                <input type="number" class="form-control" id="userlist_inp_point" placeholder="포인트">
                            </div>
                            <div>
                                <label for="userlist_inp_point_remark">비고</label>
                                <textarea class="form-control" id="userlist_inp_point_remark" style="height:130px" placeholder="비고"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                            onclick="userlistModalPointManageClear();">닫기</button>
                        <button type="button" class="btn btn-primary" onclick="userlistPointInsert();">포인트 추가</button>
                    </div>
                </div>
            </div>
        </div>
        {{-- 모달 / 학생 이용권 상세 내역 --}}
        <div class="modal fade" id="userlist_modal_goods_detail" tabindex="-1" aria-hidden="true" 
        style="display: none;--bs-modal-width:1100px;
        ">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">
                            <span class="student_name"></span>
                            이용권 상세 내역
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            onclick="userlistModalGoodsDetailClear();"></button>
                    </div>
                    <div class="modal-body">
                        <div class="overflow-auto tableFixedHead borde r" style="height:auto;max-height: 400px">
                            <table class="table table-bordered text-center align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <td>순서</td>
                                        <td>이용권</td>
                                        <td>이용권시작일</td>
                                        <td>이용권만료일<br>(변견된 만료일)</td>
                                        <td>유효구분</td>
                                        <td>결제일</td>
                                        <td>자동결제일</td>
                                        <td>결제금액</td>
                                        <td>비고</td>
                                        <td>상세</td>
                                    </tr>
                                </thead>
                                <tbody id="userlist_tby_goods_detail">
                                    <tr class="copy_tr_goods_detail" hidden>
                                        <td class="idx" data="순서"></td>
                                        <td class="goods_name" data="이용권"></td>
                                        <td class="goods_start_date" data="이용권시작일"></td>
                                        <td  data="이용권만료일">
                                            <div class="goods_origin_end_date"></div>
                                            <div class="goods_end_date" hidden></div>
                                        </td>
                                        <td class="goods_status" data="유효구분"></td>
                                        <td class="goods_pay_date" data="결제일"></td>
                                        <td class="pay_auto_date" data="자동결제일"></td>
                                        <td class="goods_price" data="결제금액"></td>
                                        <td class="is_use" data="이용권정지"></td> {{-- 환불 처리 어떻게 할지 --}}
                                        <td class="td_func" data="기능">
                                            <button class="btn btn-sm btn-open" onclick="userlistOpenGoodsDetailLog(this);"></button>
                                        </td>
                                        <input type="hidden" class="student_seq">
                                        <input type="hidden" class="goods_detail_seq">
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                            onclick="userlistModalGoodsDetailClear();">닫기</button>
                        {{-- <button type="button" class="btn btn-primary" onclick=";">저장</button> --}}
                    </div>
                </div>
            </div>
        </div>
        {{-- 모달 / 담당 선생님 변경  --}}
        <div class="modal fade" id="userlist_modal_teacher_change" tabindex="-1" aria-hidden="true" 
        style="display: none;">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">
                            <span class="student_name"></span>
                            담당 선생님 변경
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            onclick="userlistChgTeachModalClear()"></button>
                    </div>
                    <div class="modal-body">
                        {{-- div = label 지사 / select --}}
                        <div class="mb-3">
                            <label>소속/지사</label>
                            <select class="sel_region form-select"  onchange="userlistSelectRegion(this, '#userlist_sel_team_chg_teach', '팀 선택');">
                                <option value="">소속/지사 선택</option>
                                @if(isset($region) && count($region) > 0)
                                    @foreach ($region as $reg)
                                        <option value="{{ $reg['id'] }}" area="{{ $reg['area'] }}">{{ $reg['region_name'] }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        {{-- 관할(팀) 선택 --}}
                        <div class="mb-3">
                            <label>관할(팀)</label>
                            <select class="sel_team form-select" id="userlist_sel_team_chg_teach" onchange="userlistForTeamTeacherSelect(this);">
                                <option value="sel_region form-select">팀 선택</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            {{-- 담임: span bold --}}
                            <label>담임</label>
                            <span class="charge_teach_name"></span>
                        </div>
                        <div>
                            <table class="table">
                                <tbody id="userlist_tby_teach_chg">
                                    <tr class="copy_tr_teach_chg" hidden>
                                        <td data="#선생님이름">
                                            <span class="teach_name"></span>
                                        </td>
                                        <td data="#기능">
                                            <a href="javascript:void(0)" onclick="userlistTeachChgUpdate(this);">변경</a>
                                        </td>
                                        <input type="hidden" class="teach_seq">
                                        <input type="hidden" class="region_seq">
                                        <input type="hidden" class="team_code">
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                            onclick="userlistChgTeachModalClear()">
                            <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden></span> 
                            닫기</button>
                        {{-- <button type="button" class="btn btn-primary" onclick=";">저장</button> --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- 모달 / 선생님 / 선택 회원 소속/관할 변경. --}}
        <div class="modal fade" id="userlist_modal_region_team_change" tabindex="-1" aria-hidden="true" 
        style="display: none;">
            <div class="modal-dialog  modal-dialog-centered">
                <input type="hidden" class="teach_seqs">
                <input type="hidden" class="chg_region_seq">
                <input type="hidden" class="chg_team_code">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">소속/관할 변경</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            onclick="userlistModalRegionTeamChangeClear();"></button>
                    </div>
                    <div class="modal-body">
                        <div class="overflow-auto tableFixedHead" style="height: auto; max-height: 300px;">
                            <table class="table border-white">
                                <tbody id="userlist_tby_student_cnt">
                                    <tr class="copy_tr_student_cnt">
                                        <td class="teach_name" data="#이름"></td>
                                        <td class="student_cnt" data="#담당학생 0명"></td>
                                        <input type="hidden" class="teach_seq">
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- 소속을 변경하시겠습니까? --}}
                        <div>
                            <h6>소속을 변경하시겠습니까?</h6>
                        </div>
                        <div class="text-center p-2 mb-2">
                            <span class="region_name"></span>
                            <span class="team_name"></span> 
                            &rarr;
                            <span class="chg_region_name"></span>
                            <span class="chg_team_name"></span>
                        </div>

                        {{-- 해당 선생님의 담당학생은 담당선생님(미배정)으로 변경됩니다. --}}
                        <div>
                            <h6>해당 선생님의 담당학생은 담당선생님(미배정)으로 변경됩니다.</h6>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                            onclick="userlistModalRegionTeamChangeClear()">닫기</button>
                        <button type="button" class="btn btn-primary" onclick="userlistChgRegionTeamInsert()">
                            <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden></span>
                            저장</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 모달 / 선생님 재직 상태 변경 --}}
        <div class="modal fade" id="userlist_modal_teacher_status_change" tabindex="-1" aria-hidden="true" 
        style="display: none;">
            <div class="modal-dialog  modal-dialog-centered">
                <input type="hidden" class="teach_seq">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">
                            <span class="teach_name"></span>
                            재직 상태 변경</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            onclick=""></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex gap-3 mb-3">
                            <button class="btn btn-outline-primary btn_teach_status" onclick="userlistChgTeachStusBtnClick(this)">재직</button>
                            <button class="btn btn-outline-danger btn_teach_status" onclick="userlistChgTeachStusBtnClick(this)">퇴직</button>
                        </div>
                        <div class="div_resignation_date mb-3" hidden>
                            <label for="">퇴직일자</label>
                            <input type="date" class="form-control resignation_date" value="{{ date('Y-m-d') }}">
                        </div>
                        <h6>상태를 변경 하시겠습니까?</h6>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                            onclick="">닫기</button>
                        <button type="button" class="btn btn-primary" onclick="userlistChgTeachStusInsert()">
                            <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden></span>
                            저장</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 모달 / 수정 내역 리스트 --}}
        <div class="modal fade" id="userlist_modal_edit_history" tabindex="-1" aria-hidden="true" style="display: none;">
            <div class="modal-dialog  modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">
                            <span class="spinner-border spinner-border-sm sp_loding" aria-hidden="true" hidden></span>
                            수정 내역 리스트</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            onclick=""></button>
                    </div>
                    <div class="modal-body">
                        <div class="overflow-auto tableFixedHead" style="height: auto; max-height: 400px;">
                            <table class="table">
                                {{-- 굳이 id할 필요 없을듯 modal 안에 .으로 가져오기.--}}
                                <tbody class="tby_edit_history">
                                        <tr class="copy_tr_edit_history" hidden>
                                            <td class="border-bottom-0">
                                                <div class="log_content"></div>
                                                <div class="created_at fs-7"></div>
                                                <div >
                                                    <a class="log_remark" href="javascript:void(0)" onclick="userlistLogRemarkEdit(this);"></a>
                                                    <div>
                                                        <textarea class="txt_log_remark" cols="30" rows="4" hidden></textarea>
                                                    </div>
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
                        <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                            onclick="">닫기</button>
                        <button type="button" class="btn btn-primary" onclick="userlistLogRemarkUpdate(this)">
                            <span class="sp_loding spinner-border spinner-border-sm" aria-hidden="true" hidden></span>
                            저장</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- 모달 / 선택 회원 연장 --}}
        <div class="modal fade" id="userlist_modal_goods_day_plus" tabindex="-1" aria-hidden="true" style="display: none;">
            <input type="hidden" class="student_seq">
            <div class="modal-dialog  modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">
                            
                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            onclick=""></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered text-center align-middle">
                            <tr class="copy_tr_log_list lenth_over_hidden">
                                <td>내역</td>
                                <td class="log_content"></td>
                                <td class="log_remark"></td>
                                <td class="log_created_at"></td>
                            </tr>
                            <tr>
                                <td>연장 기간</td>
                                <td>
                                    <div class="row m-0 p-0 align-items-center">
                                        <div class="col row m-0 p-0 align-items-center gap-2">
                                            {{-- -,+ 클릭시 input number step up --}}
                                            <button class="col-auto btn btn-sm btn-outline-secondary" onclick="userlistModalPlusDayCnt(this,'down');">-</button>
                                            <input type="number" class="form-control col plus_day_cnt" value="0" step="1" onchange="userlistModalPlusDayChange();">
                                            <button class="col-auto btn btn-sm btn-outline-secondary" onclick="userlistModalPlusDayCnt(this,'up');">+</button>
                                            (일)
                                        </div>
                                    </div>
                                </td>
                                <td colspan="2">
                                    <input type="text" class="form-control inp_log_remark" placeholder="신청사유 입력란">
                                </td>
                            </tr>
                            <tr class="lenth_over_hidden">
                                <td>연장 전 유효기간</td>
                                <td class="table-light" colspan="3">
                                    <span class="goods_start_date"></span>
                                    ~
                                    <span class="goods_end_date"></span>
                                </td>
                            </tr>
                            <tr class="lenth_over_hidden">
                                {{-- 연장 후 유효기간 --}}
                                <td>연장 후 유효기간</td>
                                <td class="" colspan="3">
                                    <span class="after_goods_start_date"></span>
                                    ~
                                    <span class="after_goods_end_date"></span>
                                </td>
                            </tr>
                            
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                            onclick="">닫기</button>
                        <button type="button" class="btn btn-primary" onclick="userlistGoodsDayPlusModalSave()">저장</button>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- 모달 / 선택 회원 정지 --}}
        <div class="modal fade" id="userlist_modal_goods_day_stop" tabindex="-1" aria-hidden="true" style="display: none;">
            <input type="hidden" class="student_seq">
            <div class="modal-dialog  modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">

                        </h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            onclick=""></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered text-center align-middle">
                            <tr class="lenth_over_hidden">
                                <td style="width:80px">잔여 정지 횟수</td>
                                <td colspan="3">
                                    <span class="stop_cnt">0</span>
                                    /
                                    <span class="stop_cnt_max">2</span>
                                    (회)
                                </td>
                            </tr>
                            <tr class="copy_tr_log_list lenth_over_hidden">
                                <td>내역</td>
                                <td class="log_content"></td>
                                <td class="log_remark"></td>
                                <td class="log_created_at"></td>
                            </tr>
                            <tr>
                                <td>정지 신청기간</td>
                                <td>
                                    <div class="row m-0 p-0 align-items-center">
                                        <div class="col row m-0 p-0 align-items-center gap-2">
                                            {{-- -,+ 클릭시 input number step up --}}
                                            <button class="col-auto btn btn-sm btn-outline-secondary" onclick="userlistModalStopDayCnt(this,'down');">-</button>
                                            <input type="number" class="form-control col stop_day_cnt" value="0" step="15" max="30" min="0"  disabled>
                                            <button class="col-auto btn btn-sm btn-outline-secondary" onclick="userlistModalStopDayCnt(this,'up');">+</button>
                                            (일)
                                        </div>
                                        <div class="col">
                                            <span class="ms-2 text-primary">정지신청 기간은 15 or 30일</span>
                                        </div>
                                    </div>
                                </td>
                                <td colspan="2">
                                    <input type="text" class="form-control inp_log_remark" placeholder="신청사유 입력란">
                                </td>
                            </tr>
                            <tr>
                                <td>정지 시작일</td>
                                <td colspan="3">
                                    <div class="d-flex align-items-center justify-content-center">
                                        <input type="date" class="form-control stop_start_date w-auto" value="{{ date('Y-m-d') }}" onchange="userlistStopStartDateChange();">
                                        ~
                                        <input type="date" class="form-control stop_end_date w-auto" value="{{ date('Y-m-d') }}" disabled>
                                    </div>
                                </td>
                            </tr>
                            <tr class="lenth_over_hidden">
                                <td>정지 전 유효기간</td>
                                <td class="table-light" colspan="3">
                                    <span class="goods_start_date"></span>
                                    ~
                                    <span class="goods_end_date"></span>
                                </td>
                            </tr>
                            <tr class="lenth_over_hidden">
                                {{-- 정지 후 유효기간 --}}
                                <td>정지 후 유효기간</td>
                                <td class="" colspan="3">
                                    <span class="after_goods_start_date"></span>
                                    ~
                                    <span class="after_goods_end_date"></span>
                                </td>
                            </tr>
                            
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="modal_close btn btn-secondary" data-bs-dismiss="modal"
                            onclick="">닫기</button>
                        <button type="button" class="btn btn-primary" onclick="userlistGoodsDayStopModalSave();">저장</button>
                    </div>
                </div>
            </div>
        </div>

        
        
        
        
        {{-- 알림톡 / SMS / PUSH  --}}
        <div id="userlist_div_alarm" class="position-absolute justify-content-center vh-80 bg-white border rounded border-dark p-3"
        style="z-index: 6;width:99%" hidden>
            <div class="text-end p-2">
                <button class="btn btn-close" onclick="userlistAlarmClose();"></button>
            </div>
            @include('admin.admin_alarm_detail')
        </div>
    </div>
    <script>
        //최초 전체사용자 리스트에 보이게 실행
        document.querySelector('.tr_user_group').click();
        // userlistSelectUser('all_user');
        //사용자 선택(그룹 보여주기)
        function userlistSelectUserGroupView() {
            const user_select = document.querySelector('#userlist_div_user_select');
            const user_list = document.querySelector('#userlist_div_user_list');
            //user_select 이 hidden이면 보이게 하고, 아니면 숨기기.
            if (user_select.hidden) {
                user_select.hidden = false;
                //레이아웃의 왼쪽 네브바 토글 = 보이기.
                layoutLeftNavbarToggle(true);
            } else {
                user_select.hidden = true;
                //레이아웃의 왼쪽 네브바 토글 = 숨기기.
                layoutLeftNavbarToggle(false);
            }
        }
        //사용자 등록
        function userlistUserAdd() {
            const div_user_add = document.querySelector('#userlist_div_user_add');
            const btn_user_add_cancel = document.querySelector('#useradd_btn_cancel');
            const btn_user_add_save = document.querySelector('#useradd_btn_save');
            div_user_add.hidden = false;
            //onclick 이벤트를 수정.
            btn_user_add_cancel.setAttribute('onclick', 'userlistUserAddCancel()');
            // btn_user_add_save.setAttribute('onclick', 'userlistUserAddSave()');
        }
        //사용자 등록 취소
        function userlistUserAddCancel() {
            useraddInfoClear();
            document.querySelectorAll('#useradd_btn_div_del').forEach(function(el, index){ if(index > 0) el.click();});
            const div_user_add = document.querySelector('#userlist_div_user_add');
            div_user_add.hidden = true;
        }
        //사용자 그룹 추가 창 열기
        function userlistOpenUserGroupWindow(){
            const div_user_group = document.querySelector('#userlist_div_user_group');
            div_user_group.hidden = false;
            div_user_group.querySelector('.group_name').focus();
        }
        //사용자 그룹 추가 창 닫기
        function userlistUserGroupAddWindowClose(){
            const div_user_group = document.querySelector('#userlist_div_user_group');
            div_user_group.hidden = true;
            //나머지 input, select 초기화.
            div_user_group.querySelector('.group_name').value = '';
            div_user_group.querySelector('.remark').value = ''; 
            div_user_group.querySelector('.group_type1').value = '';
            div_user_group.querySelector('.group_type2').value = '';
            div_user_group.querySelector('.group_type2').hidden = true;
            div_user_group.querySelector('.is_use').value = '';
            div_user_group.querySelector('.first_page').value = '';
            div_user_group.querySelector('.seq').value = '';
        }
        //사용자 그룹 추가 창에서 그룹유형1 normal선택시
        function userlistChkGroupTypeSelectTag(vthis){
            const group_type1 = vthis.value;
            //group_type2를 보여주거나 숨긴다.
            if(group_type1 == 'normal'){
                const div_user_group = document.querySelector('#userlist_div_user_group');
                div_user_group.querySelector('.group_type2').hidden = false;
                div_user_group.querySelector('.group_type2').value = '';

                //총괄매니저 인지 확인.
                const div_general = document.querySelector('#userlist_div_general');
                div_general.hidden = true;
            }else{
                const div_user_group = document.querySelector('#userlist_div_user_group');
                div_user_group.querySelector('.group_type2').hidden = true;
                div_user_group.querySelector('.group_type2').value = '';

                //총괄매니저 인지 확인.
                const div_general = document.querySelector('#userlist_div_general');
                div_general.hidden = false;

            }
            userlistChkFirstPageAllHidden();
        }
        //사용자 그룹 추가 창에서 first_page를 모두 숨김처리
        function userlistChkFirstPageAllHidden(){
            const div_user_group = document.querySelector('#userlist_div_user_group');
            const group_type1 = div_user_group.querySelector('.group_type1').value;
            const group_type2 = div_user_group.querySelector('.group_type2').value;
            //group_type1 의 값이 teacher이면 teacher를 보여주고, 아니면 teacher를 숨긴다.
            //group_type2 의 값이 student이면 student를 보여주고, 아니면 student를 숨긴다.
            //group_type2 의 값이 parent이면 parent를 보여주고, 아니면 parent를 숨긴다.

            // div_user_group.querySelector('.first_page.teacher').hidden = true;
            // div_user_group.querySelector('.first_page.student').hidden = true;
            // div_user_group.querySelector('.first_page.parent').hidden = true;

            // if(group_type1 == 'teacher'){
            //     div_user_group.querySelector('.first_page.teacher').hidden = false;
            // }else if(group_type2.indexOf('student') != -1){
            //     div_user_group.querySelector('.first_page.student').hidden = false;
            // }else if(group_type2 == 'parent'){
            //     div_user_group.querySelector('.first_page.parent').hidden = false;
            // }
        }
        
        //사용자 그룹 추가 창에서 저장/수정 버튼 클릭시
        function userlistUserGroupInsert(){
            const div_user_group = document.querySelector('#userlist_div_user_group');
            const group_name = div_user_group.querySelector('.group_name').value;
            const remark = div_user_group.querySelector('.remark').value;
            const group_type1 = div_user_group.querySelector('.group_type1').value;
            const group_type2 = div_user_group.querySelector('.group_type2').value;
            const is_use = div_user_group.querySelector('.is_use').value;
            const sel_type = group_type1 == 'normal' ? group_type2 : group_type1;
            // const first_page = sel_type != "" ? div_user_group.querySelector('.first_page.'+sel_type).value : '';
            const first_page = div_user_group.querySelector('.first_page').value;
            const seq = div_user_group.querySelector('.seq').value;
            const is_general = div_user_group.querySelector('#userlist_chk_general').checked ? 'Y' : 'N';

            const parameter = {
                group_name:group_name,
                remark:remark,
                group_type:group_type1 == 'normal' ? group_type2 : group_type1,
                is_use:is_use,
                first_page:first_page,
                seq:seq,
                is_general:is_general
            };
            const is_pass = userlistChkParameter(parameter);
            if(!is_pass){
                return;
            }
            const page = 'group/insert';
            queryUserList(page,parameter,function(result){
                if(result == null || result.resultCode == null){
                    return;
                }
                if(result.resultCode == 'success'){
                    sAlert('','저장되었습니다.');
                    userlistUserGroupAddWindowClose();
                    userlistGroupCntListSelect();
                }else{
                    sAlert('','저장에 실패하였습니다.');
                }
            });
        }

        //사용자 그룹 추가 창에서 저장/수정 버튼 클릭시 파라미터 체크
        function userlistChkParameter(parameter){
            if(parameter.group_name == ''){
                sAlert('','그룹이름을 입력해주세요.');
                return false;
            }
            if(parameter.group_type1 == ''){
                sAlert('','그룹유형1을 선택해주세요.');
                return false;
            }
            if(parameter.group_type1 == 'normal' && parameter.group_type2 == ''){
                sAlert('','그룹유형2를 선택해주세요.');
                return false;
            }
            if(parameter.is_use == ''){
                sAlert('','사용여부를 선택해주세요.');
                return false;
            }
            if(parameter.first_page == ''){
                sAlert('','첫화면을 선택하지 않으면 기본 화면이 첫화면으로 설정됩니다.');
                return true;
            }
            return true;
        }
        //사용자 그룹 리스트 가져오기.
        function userlistGroupCntListSelect(){
            //선택이 있으면 미리 선택되어있는 seq를 가져온다.
            let group_seqs = [];
            const sel_usergroup = document.querySelector('#userlist_tb_usergroup').querySelectorAll('.tr_user_group td.text-bg-primary');
            sel_usergroup.forEach((item) => {
                const tag_seq = item.closest('tr').querySelector('.seq');
                const seq = tag_seq != null ? tag_seq.value : '';
                if(seq != '') group_seqs.push(seq);
                // if(group_seqs != '')
                //     group_seqs += ',';
                // group_seqs += seq;
            });
            

            const page = 'group/cnt/select';
            const parameter = {};
            queryUserList(page,parameter,function(result){
                const tby_usergroup = document.querySelector('#userlist_tby_usergroup');
                const copy_tr_user_group = tby_usergroup.querySelector('.copy_tr_user_group');
                tby_usergroup.innerHTML = '';
                tby_usergroup.appendChild(copy_tr_user_group.cloneNode(true));
                if(result == null || result.resultData == null){
                    return;
                }
                for(let i=0;i<result.resultData.length;i++){
                    const data = result.resultData[i];
                    const tr = copy_tr_user_group.cloneNode(true);
                    tr.classList.remove('copy_tr_user_group');
                    tr.classList.add('tr_user_group');
                    tr.querySelector('.group_name').innerText = data.group_name;
                    // 아직 없음.
                    tr.querySelector('.usergroup_list_cnt').innerText = data.s_cnt || data.p_cnt || data.t_cnt || '0';
                    tr.querySelector('.seq').value = data.id;
                    tr.querySelector('.sq').value = data.sq;
                    tr.querySelector('.group_type').value = data.group_type;
                    tr.querySelector('.group_type2').value = data.group_type2;
                    tr.querySelector('.remark').value = data.remark;
                    tr.querySelector('.first_page').value = data.first_page;
                    tr.querySelector('.is_use').value = data.is_use;
                    tr.hidden = false;
                    if(group_seqs.indexOf(data.id+'') != -1){
                        tr.querySelector('td').classList.add('text-bg-primary');
                    }
                    tby_usergroup.appendChild(tr);
                }
            });
        }
        //사용자 그룹 선택 
        function clickTrUserGroup(vthis){
            const chk_td = vthis.querySelector('td');

            //선택에 따른 배경색 지정(이미 선택이 된건 선택 해제)
            if(chk_td.classList.contains('text-bg-primary')){
                chk_td.classList.remove('text-bg-primary');
            }
            else{
                chk_td.classList.add('text-bg-primary');
            }

            //선택한 타입 가져오기
            const group_type = vthis.querySelector('.group_type').value;
            document.querySelector('#userlist_inp_schtype').value = group_type;

            //그룹 타입이 다른건 선택 해제(그룹별로 보여지는 컬럼이 다르기 때문에)
            let sel_usergroup = document.querySelector('#userlist_tb_usergroup').querySelectorAll('.tr_user_group');
            sel_usergroup.forEach((item) => {
                const contain_type = item.querySelector('.group_type').value;

                if(contain_type != group_type){
                    item.querySelector('td').classList.remove('text-bg-primary');
                }
            });

            //선택한 그룹에 따른 리스트 가져오기
            userlistSelectUser(group_type);
        }
        //사용자 그룹 저장.
        function queryUserList(page,parameter, callback) {
            const xtken = document.querySelector('#csrf_token').value;
            fetch('/manage/userlist/'+page, {
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
        //사용자 그룹 수정 창 열기
        function userlistOpenUserGroupUpdateWindow(vthis){
            if (event.stopPropagation) event.stopPropagation();    
            else event.cancelBubble = true; // IE 대응

            const div_user_group = document.querySelector('#userlist_div_user_group');
            const sel_tr = vthis.closest('tr');

            div_user_group.querySelector('.group_name').value = sel_tr.querySelector('.group_name').innerHTML;
            div_user_group.querySelector('.remark').value = sel_tr.querySelector('.remark').value; 

            const group_type = sel_tr.querySelector('.group_type').value;
            if(group_type != 'teacher'){
                div_user_group.querySelector('.group_type2').value = group_type;
                div_user_group.querySelector('.group_type2').hidden = false;
                div_user_group.querySelector('.group_type1').value = 'normal';
                //총괄매니저 확인
                document.querySelector('#userlist_div_general').hidden = true;
            }
            else{
                div_user_group.querySelector('.group_type2').value = '';
                div_user_group.querySelector('.group_type2').hidden = true;
                div_user_group.querySelector('.group_type1').value = group_type;         
                //총괄매니저 확인
                document.querySelector('#userlist_div_general').hidden = false;
                const is_general = sel_tr.querySelector('.group_type2').value == 'general' ? true : false; 
                document.querySelector('#userlist_chk_general').checked = is_general;
            }
            const group_seq = sel_tr.querySelector('.seq').value;
            const first_page = sel_tr.querySelector('.first_page').value; // menu_seq
            div_user_group.querySelector('.is_use').value = sel_tr.querySelector('.is_use').value;
            div_user_group.querySelector('.seq').value = group_seq;
            div_user_group.querySelector('.first_page').value = sel_tr.querySelector('.first_page').value;
            // div_user_group.querySelector('.first_page').hidden = true;
            // try{
            //     div_user_group.querySelector('.first_page.'+group_type).hidden = false;
            // }catch(e){e.message;}
            userlistGetMenuList(group_seq, first_page, div_user_group);
            div_user_group.hidden = false;
            div_user_group.querySelector('.group_name').focus();
        }
        
        //수정시 > 그룹에 속한 페이지 정보 가져오기 > 첫페이지 select option에 넣기.
        function userlistGetMenuList(group_seq, menu_seq, div_user_group) {
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
                const first_page = div_user_group.querySelector('.first_page');
                first_page.innerHTML = '<option value="">첫화면 선택</option>';

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

        //사용자 엑셀 업로드(일괄등록)
        function userlistUserExcelAdd(){
            userlistUserAdd();
            useraddExcelPop('open');
            useraddFileDelete();
            // 파일 불러오는 창 열기 주석처리.
            // document.querySelector('#useradd_inp_excelfile').click();
            const div_user_excel_cancel = document.querySelector('#useradd_btn_excel_cancel');
            //onclick 이벤트를 수정.
            div_user_excel_cancel.setAttribute('onclick', 'userlistUserExcelAddClose()');
        }
        //사용자 엑셀 업로드 닫기
        function userlistUserExcelAddClose(){
            useraddExcelPop('close');
            const div_user_add = document.querySelector('#userlist_div_user_add');
            div_user_add.hidden = true;
        }
        //그룹 선택 변경 시 컬럼, 버튼 표기 변경
        function userlistContentChange(type) {
            let sel_tb = document.querySelector('#userilst_tb_userinfo');
            let st_show = sel_tb.querySelectorAll('.st_show');
            let pt_show = sel_tb.querySelectorAll('.pt_show');
            let teach_show = sel_tb.querySelectorAll('.teach_show');
            let not_teach_show = sel_tb.querySelectorAll('.not_teach_show');
            let none_group = sel_tb.querySelectorAll('.none_group');
            //하단 버튼 그룹 숨김 처리
            if(type.indexOf('student') != -1){
                document.querySelector('#userlist_div_bottom_user_button').hidden = false;
                document.querySelector('#userlist_div_bottom_manage_button').hidden = true;
                document.querySelector('#userlist_div_bottom_button').hidden = true;

                //타입별 컬럼 표기
                st_show.forEach((s) => {s.hidden = false;});
                not_teach_show.forEach((nt) => {nt.hidden = false;});
                pt_show.forEach((p) => {p.hidden = true;});
                teach_show.forEach((t) => {t.hidden = true;});
                none_group.forEach((n) => {n.hidden = true;});
            }
            else if(type == 'parent'){
                document.querySelector('#userlist_div_bottom_user_button').hidden = false;
                document.querySelector('#userlist_div_bottom_manage_button').hidden = true;
                document.querySelector('#userlist_div_bottom_button').hidden = true;

                //타입별 컬럼 표기
                st_show.forEach((s) => {s.hidden = true;});
                not_teach_show.forEach((nt) => {nt.hidden = false;});
                pt_show.forEach((p) => {p.hidden = false;});
                teach_show.forEach((t) => {t.hidden = true;});
                none_group.forEach((n) => {n.hidden = true;});
            }
            else if(type == 'teacher'){
                document.querySelector('#userlist_div_bottom_user_button').hidden = true;
                document.querySelector('#userlist_div_bottom_manage_button').hidden = false;
                document.querySelector('#userlist_div_bottom_button').hidden = true;

                //타입별 컬럼 표기
                st_show.forEach((s) => {s.hidden = true;});
                not_teach_show.forEach((nt) => {nt.hidden = true;});
                pt_show.forEach((p) => {p.hidden = true;});
                teach_show.forEach((t) => {t.hidden = false;});
                none_group.forEach((n) => {n.hidden = true;});
            }
            else{
                document.querySelector('#userlist_div_bottom_user_button').hidden = true;
                document.querySelector('#userlist_div_bottom_manage_button').hidden = true;
                document.querySelector('#userlist_div_bottom_button').hidden = false;

                if(type == 'none_group_user'){
                    document.querySelector('#userlist_sel_change_usergroup').hidden = false;
                    document.querySelector('#userlist_btn_groupinsert').hidden = false;
                }
                else if(type == 'all_user'){
                    document.querySelector('#userlist_sel_change_usergroup').hidden = true;
                    document.querySelector('#userlist_btn_groupinsert').hidden = true;
                }

                //타입별 컬럼 표기
                st_show.forEach((s) => {s.hidden = true;});
                not_teach_show.forEach((nt) => {nt.hidden = true;});
                pt_show.forEach((p) => {p.hidden = true;});
                teach_show.forEach((t) => {t.hidden = true;});
                none_group.forEach((n) => {n.hidden = false;});
            }
        }
        //소속 변경 시 팀 select box에 표기
        function userlistSelectRegion(vthis, result_id, first_str,team_code){
            if((first_str||'') == '') first_str = '미배정';
            const sel_val = vthis.value;
            document.querySelector(result_id).selectedIndex = 0;

            const page = "/manage/useradd/team/select";
            const parameter = {
                region_seq:sel_val
            };
            queryFetch(page, parameter, function(result){
                //초기화 useradd_sel_region
                const sel_team = document.querySelector(result_id);
                sel_team.innerHTML = '<option selected value="" area="">'+first_str+'</option>';

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
                        sel_team.onchange();
                    }
                }
            });
        }
        //사용자 리스트 테이블 초기화
        function userilstUserinfoListReset(){
            const sel_tr = document.querySelector('#userilst_tby_userinfo .copy_tr_userinfo').cloneNode(true);
            document.querySelector('#userilst_tby_userinfo').innerHTML = '';
            document.querySelector('#userilst_tby_userinfo').appendChild(sel_tr);
        }
        //사용자 리스트 한줄씩 수정
        function userlistTrEdit(vthis){
            var sel_tr = vthis.closest('tr');
            var sel_tr_fix = sel_tr.querySelectorAll('.fix');
            var sel_tr_modi = sel_tr.querySelectorAll('.modi');
            sel_tr.querySelector('#userlist_btn_edit').hidden = true;
            sel_tr.querySelector('#userlist_btn_edit_save').hidden = false;
            sel_tr.querySelector('#userlist_btn_edit_cancel').hidden = false;
            sel_tr.querySelector('#exampleRadios1').removeAttribute('disabled');
            sel_tr.querySelector('#exampleRadios2').removeAttribute('disabled');

            sel_tr_fix.forEach((item)=>{
                item.hidden = true;
            });

            sel_tr_modi.forEach((item)=>{
                item.hidden = false;
            });
        }
        //사용자 리스트 검색 [추가 코드] > 포인트, 이용권 관련 추가 필요
        function userlistSelectUser(type){
            let group_type = type;

            if((group_type || '') == '')
                group_type = document.querySelector('#userlist_inp_schtype').value;

            //선택된 그룹
            let sel_group_cnt = document.querySelector('#userlist_tb_usergroup').querySelectorAll('.text-bg-primary').length;
            
            if(sel_group_cnt == 0){
                userilstUserinfoListReset();
                return;
            }

            //선택된 그룹 모두 넘기기
            let seq = [];
            if(!(group_type == 'all_user' || group_type == 'none_group_user')){
                document.querySelectorAll('#userlist_tb_usergroup .text-bg-primary').forEach(function(item){
                    seq.push(item.closest('tr').querySelector('.seq').value);
                });
            }

            //검색 관련 keyword 변수
            const schType = document.querySelector('#userlist_sel_sch_type').value;
            const schTypeText = document.querySelector('#userlist_sel_sch_text').value;
            const schRegion = document.querySelector('#userlist_sel_region').value;
            const schTeam = document.querySelector('#userlist_sel_team').value;
            // const schData = document.querySelector('#userlist_inp_schdate').value; //[추가 코드]
            // const schData = document.querySelector('#userlist_inp_schdate').value; //[추가 코드]


            let page = 'user/select';
            if(group_type.indexOf('student') != -1)
                page = 'student/select';
            else if(group_type == 'parent')
                page = 'parent/select';
            else if(group_type == 'teacher')
                page = 'teacher/select';

            const parameter = {
                group_type:group_type,
                group_seq:seq,
                search_type:schType,
                search_keyword:schTypeText,
                search_region:schRegion,
                search_team:schTeam
            };
            queryUserList(page,parameter,function(result){
                //리스트 초기화
                userilstUserinfoListReset();
                //컬럼 및 버튼 초기화
                if(sel_group_cnt == 1)
                    userlistContentChange(group_type);

                const sel_tr = document.querySelector('#userilst_tby_userinfo .copy_tr_userinfo').cloneNode(true);

                if((result.resultData||'') != '' && (result.resultData||'').length > 0){
                    const rData = result.resultData;
                    const main_code = document.querySelector('.main_category_type.active').getAttribute('data');
                    const now_date = new Date();
                    var prev_tr = undefined;
                    var prev_pt_id = '';
                    for(let i=0; i<result.resultData.length; i++){
                        const row = rData[i];
                        let cloneTr = sel_tr.cloneNode(true);
                        cloneTr.classList.remove('copy_tr_userinfo');
                        cloneTr.classList.add('tr_userinfo');
                        cloneTr.hidden = false;
                        if(group_type.indexOf('student') != -1){
                            cloneTr.querySelector('.td_group_name').innerHTML = main_code == 'elementary' ? '초등':'중등';
                            cloneTr.querySelector('.sp_user_name').innerHTML = row.student_name;
                            cloneTr.querySelector('.inp_user_name').value = row.student_name;
                            cloneTr.querySelector('.div_user_id').innerHTML = '('+row.student_id+')';
                            cloneTr.querySelector('.div_user_id').setAttribute('data', row.student_id);
                            let pt_id = '';
                            if((row.parent_id||'') != '')
                                pt_id = '(' + (row.parent_id || '') + ')';
                            cloneTr.querySelector('.sp_parent_name').innerHTML = (row.parent_name || '');
                            cloneTr.querySelector('.inp_parent_name').value = (row.parent_name || '');
                            cloneTr.querySelector('.div_parent_id').innerHTML = pt_id;
                            cloneTr.querySelector('.parent_seq').value = row.parent_seq;
                            cloneTr.querySelector('.div_parent_id').setAttribute('data', row.parent_id);
                            cloneTr.querySelector('.sp_teacher_name').innerHTML = row.teach_name;     
                            // cloneTr.querySelector('.sp_teacher_name_modi').innerHTML = row.teach_name;
                            cloneTr.querySelector('.inp_user_key').value = row.id;
                            cloneTr.querySelector('.inp_user_type').value = group_type;
                            cloneTr.querySelector('.td_user_join_date').innerHTML = new Date(row.created_at).format('yyyy-MM-dd');
                            cloneTr.querySelector('.td_user_phone').innerHTML = (row.student_phone || '');
                            cloneTr.querySelector('.td_reg_name').innerHTML = (row.created_name || '');
                        }
                        else if(group_type == 'parent'){
                            cloneTr.querySelector('.td_group_name').innerHTML = row.group_name;
                            var parent_info = '<span>'+row.parent_name+'<br>('+row.parent_id+')';
                            cloneTr.querySelector('.sp_user_name').innerHTML = row.parent_name;
                            cloneTr.querySelector('.inp_user_name').value = row.parent_name;
                            cloneTr.querySelector('.div_user_id').innerHTML = '('+row.parent_id+')';
                            cloneTr.querySelector('.div_user_id').setAttribute('data', row.parent_id);
                            let st_id = '';
                            if((row.student_id||'') != '')
                                st_id = '(' + (row.student_id || '') + ')';
                            cloneTr.querySelector('.sp_child_name').innerHTML = (row.student_name||'');
                            cloneTr.querySelector('.sp_child_name').setAttribute('student_seq', row.student_seq);
                            cloneTr.querySelector('.inp_child_name').value = (row.student_name||'');
                            cloneTr.querySelector('.div_child_id').innerHTML = st_id;
                            cloneTr.querySelector('.sp_teacher_name').innerHTML = row.teach_name;     
                            // cloneTr.querySelector('.sp_teacher_name_modi').innerHTML = row.teach_name;
                            cloneTr.querySelector('.inp_user_key').value = row.id;
                            cloneTr.querySelector('.inp_user_type').value = group_type;
                            cloneTr.querySelector('.td_user_join_date').innerHTML = new Date(row.created_at).format('yyyy-MM-dd');
                            cloneTr.querySelector('.td_user_phone').innerHTML = (row.parent_phone || '');
                            cloneTr.querySelector('.td_reg_name').innerHTML = (row.created_name || '');

                            //같은 학부모 row 위아래 합치기
                            if(prev_pt_id == row.parent_id){
                                cloneTr.querySelector('.td_chk').hidden = true;
                                cloneTr.querySelector('.td_group_name').hidden = true;
                                cloneTr.querySelector('.td_user_name').hidden = true; 
                                const prevChk = prev_tr.querySelector('.td_chk');
                                const prevGroupName = prev_tr.querySelector('.td_group_name');
                                const prevUserName = prev_tr.querySelector('.td_user_name');
                                prevChk.setAttribute('rowspan', parseInt(prevChk.getAttribute('rowspan')*1) + 1);
                                prevGroupName.setAttribute('rowspan', parseInt(prevGroupName.getAttribute('rowspan')*1) + 1);
                                prevUserName.setAttribute('rowspan', parseInt(prevUserName.getAttribute('rowspan')*1) + 1);
                            }

                            if(prev_pt_id != row.parent_id)
                                prev_tr = cloneTr;
                            prev_pt_id = row.parent_id;
                        }
                        else if(group_type == 'teacher'){
                            cloneTr.querySelector('.td_group_name').innerHTML = row.group_name;
                            cloneTr.querySelector('.sp_user_name').innerHTML = row.teach_name;
                            cloneTr.querySelector('.inp_user_name').value = row.teach_name;
                            cloneTr.querySelector('.div_user_id').innerHTML = '('+row.teach_id+')';
                            cloneTr.querySelector('.div_user_id').setAttribute('data', row.teach_id);
                            cloneTr.querySelector('.inp_user_key').value = row.id;
                            cloneTr.querySelector('.inp_user_type').value = group_type;
                            cloneTr.querySelector('.td_user_join_date').value = new Date(row.created_at).format('yyyy-MM-dd');
                            cloneTr.querySelector('.td_user_phone').innerHTML = (row.teach_phone||'');
                            cloneTr.querySelector('.td_reg_name').innerHTML = (row.created_name || '');
                            cloneTr.querySelector('.join_date ').innerHTML = (row.created_at||'').substr(0,10);
                            cloneTr.querySelector('.resignation_date').innerHTML = row.teach_status == 'N' ? (row.resignation_date||'').substr(0,10):'';
                            cloneTr.querySelector('.sp_user_status_str').innerHTML = row.teach_status_str;
                            cloneTr.querySelector('.inp_user_status').value = row.teach_status;
                            if(row.teach_status == 'N') cloneTr.querySelector('.sp_user_status_str').classList.add('text-danger');
                            cloneTr.querySelector('.td_region').innerHTML = row.region_name;
                            cloneTr.querySelector('.td_team').innerHTML = row.team_name;
                        }
                        else{
                            let group_name = row.group_name;
                            if(row.group_name == '학생') group_name = (main_code == 'elementary' ? '초등':'중등'); 
                            cloneTr.querySelector('.td_group_name').innerHTML = group_name;
                            cloneTr.querySelector('.sp_user_name').innerHTML = (row.user_name || '');
                            cloneTr.querySelector('.inp_user_name').value = (row.user_name || '');
                            cloneTr.querySelector('.div_user_id').innerHTML = '('+row.user_id+')';
                            cloneTr.querySelector('.div_user_id').setAttribute('data', row.user_id);
                            // cloneTr.querySelector('.td_child').innerHTML = (row.student_name || '');
                            // cloneTr.querySelector('.td_parent').innerHTML = (row.parent_name || '');
                            cloneTr.querySelector('.sp_teacher_name').innerHTML = (row.teach_name || '');
                            cloneTr.querySelector('.inp_user_key').value = row.user_key;
                            cloneTr.querySelector('.inp_user_type').value = row.group_type;
                            cloneTr.querySelector('.td_user_join_date').innerHTML = new Date(row.created_at).format('yyyy-MM-dd');
                            cloneTr.querySelector('.td_user_phone').innerHTML = (row.user_phone || '');
                            cloneTr.querySelector('.td_reg_name').innerHTML = (row.created_name || '');
                        }

                        //학생이거나, 학부모일때 
                        //포인트 및 이용권 관련 추가.
                        if(group_type == 'student' || group_type == 'parent'){
                            cloneTr.querySelector('.teach_seq').value = row.teach_seq||'';
                            cloneTr.querySelector('.teach_region_seq').value = row.teach_region_seq||'';
                            cloneTr.querySelector('.teach_team_code').value = row.teach_team_code||'';
                            
                            cloneTr.querySelector('.sp_teacher_name').innerText = row.teach_name||'';
                            cloneTr.querySelector('.sp_point').innerHTML = row.point_now;
                            // cloneTr.querySelector('.inp_point').value = row.point_now;              
                            //이용권 관련.                             
                            if((row.goods_name||'') != ''){
                                cloneTr.querySelector('.sp_goods').innerHTML = row.goods_name+'<br>('+row.goods_period+'개월)';
                                cloneTr.querySelector('.goods_period').value = row.goods_period;
                                cloneTr.querySelector('.sp_goods_start_date').innerHTML = row.goods_start_date;
                                cloneTr.querySelector('.sp_goods_end_date').innerHTML = row.goods_end_date;
                                // if((row.goods_is_use||'') == 'Y') cloneTr.querySelector('.a_goods_stop').hidden = false;
                                // cloneTr.querySelector('.inp_start_date').value = row.goods_start_date;
                                // cloneTr.querySelector('.inp_end_date').value = row.goods_end_date;
                                if((row.goods_start_date||'') != '' && (row.goods_end_date||'') != ''){
                                    //현재 날짜와 이용권 만료일을 비교해서 (만료 또는 유효(남은일수 + '일')) 표기
                                    let goods_end_date = new Date(row.goods_end_date);
                                    let diff = goods_end_date.getTime() - now_date.getTime();
                                    let diffDays = Math.ceil(diff / (1000 * 3600 * 24));
                                    if(diffDays < 0){
                                        cloneTr.querySelector('.td_goods_status').innerHTML = '만료';
                                    }
                                    else{
                                        cloneTr.querySelector('.td_goods_status').innerHTML = '유효<br>('+diffDays+')일';
                                    }
    
                                }
                            }
                        }
                        cloneTr.querySelector('.email').value = row.email;
                        cloneTr.querySelector('.school_name').value = row.school_name;
                        cloneTr.querySelector('.td_area').innerHTML = row.area;
                        cloneTr.querySelector('.group_seq').value = row.group_seq;
                        //checkbox name 변경
                        cloneTr.querySelector('#exampleRadios1').setAttribute('name', 'rb'+i);
                        cloneTr.querySelector('#exampleRadios2').setAttribute('name', 'rb'+i);

                        if(row.is_use == 'Y'){
                            cloneTr.querySelector('#exampleRadios1').checked = true;
                            cloneTr.querySelector('#exampleRadios2').checked = false;
                        }
                        else{
                            cloneTr.querySelector('#exampleRadios1').checked = false;
                            cloneTr.querySelector('#exampleRadios2').checked = true;
                            cloneTr.classList.add('tr-disabled');
                        }
                        cloneTr.querySelector('.is_use').value = row.is_use;

                        cloneTr.querySelector('#exampleRadios1').setAttribute('disabled', true);
                        cloneTr.querySelector('#exampleRadios2').setAttribute('disabled', true);

                        document.querySelector('#userilst_tby_userinfo').appendChild(cloneTr);
                    }
                }
            });
        }
        //사용자 활성/비활성 상태 변경
        function userlistChangeUseStatus(type){
            var page = 'user/use/update';
            userlistChangeValue(page, type).then(function(result){
                if(result){
                    sAlert('', '변경되었습니다.');
                    userlistSelectUser();
                }else{
                    sAlert('', '변경에 실패하였습니다.');
                }
            });            
        }
        // 사용자 변경
        function userlistChangeValue(type, value){
            return new Promise(function(resolve, reject){
                const userinfo_check_list = document.querySelectorAll('input[type=checkbox][name=inp_cb_userinfo]:checked');
                const userinfo_check_cnt = userinfo_check_list.length;

                if(userinfo_check_cnt < 1){
                    sAlert('', '선택한 내역이 없습니다.');
                    return;
                }

                let chk_val = "";
                if(type == 'user/use/update'){
                    if(value)
                        chk_val = 'Y';
                    else
                        chk_val = 'N';
                }

                let success_cnt = 0;
                let sum_cnt = 0;
                for(let i=0; i<userinfo_check_cnt; i++){
                    let sel_tr = userinfo_check_list[i].closest('tr');
                    let user_key = sel_tr.querySelector('.inp_user_key').value;
                    let group_type = sel_tr.querySelector('.inp_user_type').value;

                    const parameter = {
                        user_key:user_key,
                        group_type:group_type,
                        chk_val:chk_val
                    };

                    const page = type;
                    queryUserList(page,parameter,function(result){
                        success_cnt += result.resultCode*1
                        sum_cnt++;
                        if((userinfo_check_cnt) == sum_cnt){
                            const bool = userinfo_check_cnt == success_cnt;
                            resolve (bool);
                        }
                    });
                }
            });
        }
        //사용자정보 수정 취소
        function userlistTrEditCancel(vthis){
            var sel_tr = vthis.closest('tr');
            var sel_tr_fix = sel_tr.querySelectorAll('.fix');
            var sel_tr_modi = sel_tr.querySelectorAll('.modi');
            sel_tr.querySelector('#userlist_btn_edit').hidden = false;
            sel_tr.querySelector('#userlist_btn_edit_save').hidden = true;
            sel_tr.querySelector('#userlist_btn_edit_cancel').hidden = true;
            sel_tr.querySelector('#exampleRadios1').setAttribute('disabled', true);
            sel_tr.querySelector('#exampleRadios2').setAttribute('disabled', true);

            sel_tr_fix.forEach((item)=>{
                item.hidden = false;
            });

            sel_tr_modi.forEach((item)=>{
                item.hidden = true;
            });

            //취소했기 때문에 취소시 원래 값으로 변경.
            sel_tr.querySelector('#exampleRadios1').checked = sel_tr.querySelector('.is_use').value == 'Y' ? true:false;
            sel_tr.querySelector('#exampleRadios2').checked = sel_tr.querySelector('.is_use').value == 'Y' ? false:true;
            sel_tr.querySelector('.inp_user_name').value = sel_tr.querySelector('.sp_user_name').innerHTML;
            sel_tr.querySelector('.inp_child_name').value = sel_tr.querySelector('.sp_child_name').innerHTML;
            sel_tr.querySelector('.inp_parent_name').value = sel_tr.querySelector('.sp_parent_name').innerHTML;

        }

        // 사용자정보 chkbox 전체 선택
        function userlistCheckAll(vthis){
            event.stopPropagation();
            const sel_tr = document.querySelectorAll('.tr_userinfo');
            sel_tr.forEach((item)=>{
                item.querySelector('input[type=checkbox][name=inp_cb_userinfo]').checked = vthis.checked;
            });
        }

        // 선택 회원 포인트 일괄 관리
        function userlistSelPointManageAll(){
            const tb_userinfo = document.querySelector('#userilst_tb_userinfo');
            const all_chk = tb_userinfo.querySelector('.all_show input[type=checkbox]');
            //all_chk 가 체크 되어 있지 않으면 클릭
            if(!all_chk.checked)
                all_chk.click();

            //선택 회원 포인트 관리
            userlistSelPointManage();
        }
        // 선택 회원 포인트 관리
        function userlistSelPointManage(){
            //모달 div 변수 생성
            //선택 회원중에 학생만 선택되어있는지 확인
            //학생만 선택이 되어 있지 않으면 학생만 선택.
            const modal = document.querySelector('#userlist_modal_point_manage');
            const sel_tr = document.querySelectorAll('.tr_userinfo');

            //0명 은 리턴
            if(userlistChkUser('zero')){
                //모달 닫기.
                document.querySelector('#userlist_modal_point_manage .btn-close').click();
                return;
            } 

            let is_student = false;
            for(let i=0; i<sel_tr.length; i++){
                const item = sel_tr[i];
                if(item.querySelector('input[type=checkbox][name=inp_cb_userinfo]').checked){
                    if(item.querySelector('.inp_user_type').value.indexOf('student') != -1){
                        is_student = true;
                        break;
                    }
                }
            }
            if(!is_student){
                sAlert('', '학생만 선택 가능합니다.');
                return;
            }

            //지정한 포인트가져오기
            const add_point = document.querySelector('#userlist_inp_point_manage').value;
            modal.querySelector('#userlist_inp_point').value = add_point;

            //1명인지 확인
            if(userlistChkUser('only_one')){
                // 내역 보여주기
                modal.querySelector('.div_point_history').hidden = false;
                // 모달 가로사이즈 조정
                modal.style.setProperty('--bs-modal-width', '800px');

                //포인트 히스토리 내역 가져오기.
                userlistPointHistorySelect();
            }
            //한명 이상
            else{
                // 내역 숨기기
                modal.querySelector('.div_point_history').hidden = true;
                // 모달 가로사이즈 조정
                modal.style.setProperty('--bs-modal-width', '400px');
            }
            
            const myModal = new bootstrap.Modal(document.getElementById('userlist_modal_point_manage'), {});
            myModal.show();
        }

        // 포인트 히스토리 내역 가져오기.
        function userlistPointHistorySelect(){
            const modal = document.querySelector('#userlist_modal_point_manage');
            const chk_tr = document.querySelector('.tr_userinfo input[type=checkbox]:checked').closest('tr');
            const user_key = chk_tr.querySelector('.inp_user_key').value;
            //로딩 시작

            //포인트 히스토리 내역 가져오기.
            const page = "/manage/userlist/point/history/select";
            const parameter = {
                user_key:user_key
            };
            queryFetch(page, parameter, function(result){
                //로딩 끝

                //초기화
                
                const tby_point_history = modal.querySelector('#userlist_tby_point_history');
                const copy_tr = tby_point_history.querySelector('.copy_tr_point_history').cloneNode(true);
                tby_point_history.innerHTML = '';
                tby_point_history.appendChild(copy_tr);
                copy_tr.hidden = true;
                if(result.resultCode == 'success'){
                    for(let i = 0; i < result.resultData.length; i++){
                        const r_data = result.resultData[i];
                        const tr = copy_tr.cloneNode(true);
                        copy_tr.classList.remove('copy_tr_point_history');
                        copy_tr.classList.add('tr_point_history');
                        tr.hidden = false;
                        tr.querySelector('.point').innerHTML = r_data.point;
                        //remark 20글자 이상일때 스크롤 처리
                        if((r_data.remark||'').length > 20){
                            tr.querySelector('.remark').innerHTML = '<div style="overflow:auto;max-height:50px;">'+r_data.remark+'</div>';
                        }
                        else
                            tr.querySelector('.remark').innerHTML = r_data.remark;
                        tr.querySelector('.remark').setAttribute('value', r_data.remark);
                        tr.querySelector('.created_at').innerHTML = r_data.created_at;
                        tr.querySelector('.created_name').innerHTML = r_data.created_name;
                        tby_point_history.appendChild(tr);
                    }
                }

                //내역이 없을때 표기
                if(document.querySelectorAll('.tr_point_history').length == 0){
                    modal.querySelector('#userlist_div_point_manage_none').hidden = false;
                }else{
                    modal.querySelector('#userlist_div_point_manage_none').hidden = true;
                }
            });
        }

        // 포인트 선택 회원에게 추가하기.
        function userlistPointInsert(){
            const point = document.querySelector('#userlist_inp_point').value;
            const remark = document.querySelector('#userlist_inp_point_remark').value;
            // 포인트 입력 체크
            if(point == ''){
                sAlert('', '포인트를 입력해주세요.');
                return;
            }

            // 체크 되어 있는 회원들의 seq 가져오기.
            let user_keys = "";
            const sel_tr = document.querySelectorAll('.tr_userinfo');
            for(let i = 0; i < sel_tr.length; i++){
                const tr = sel_tr[i];
                if(tr.querySelector('input[type=checkbox][name=inp_cb_userinfo]').checked){
                    const user_key = tr.querySelector('.inp_user_key').value;
                    const user_type = tr.querySelector('.inp_user_type').value;
                    if(user_type.indexOf('student') != -1){
                        if(user_keys != '')
                            user_keys += ',';
                        user_keys += user_key;
                    }
                }
            }

            const page = "/manage/userlist/point/insert";
            const parameter = {
                user_keys:user_keys,
                point:point,
                remark:remark
            };
            queryFetch(page,parameter,function(result){
                if(result == null || result.resultCode == null){
                    return;
                }
                if(result.resultCode == 'success'){
                    sAlert('', '저장되었습니다.');
                    // [추가 코드]
                    // 추후 태그만 변경 할지 
                    userlistSelectUser();
                    // 모달 닫기
                    document.querySelector('#userlist_modal_point_manage .btn-close').click();
                }else{
                    sAlert('', '저장에 실패하였습니다.');
                }
            });
        }

        // 모달 포인트 관리 클리어
        function userlistModalPointManageClear(){
            document.querySelector('#userlist_inp_point').value = '';
            document.querySelector('#userlist_inp_point_remark').value = '';
        }

        // 사용자 상세 수정.
        function userlistUserEdit(vthis){
            const tr = vthis.closest('tr');
            // const user_key = tr.querySelector('.inp_user_key').value;
            // const group_seq = tr.querySelector('.group_seq').value;

            // 사용자 상세 수정 창 열기
            userlistUserAdd();
            // 선택 사용자 정보 넘기기.
            useraddUserDetailSelect(tr);
        }

        // 사용자(학생) 이용권 내역 상세 모달 열기
        function userlistGoodsDetail(vthis){
            const tr = vthis.closest('tr');
            const user_type = tr.querySelector('.inp_user_type').value;
            const user_key = tr.querySelector('.inp_user_key').value;
            const user_name = tr.querySelector('.inp_user_name').value;
            const child_key = tr.querySelector('.sp_child_name').getAttribute('student_seq');
            const child_name = tr.querySelector('.sp_child_name').innerText;
            const student_seq = user_type == 'student' ? user_key:child_key; 
            const student_name = user_type == 'student' ? user_name:child_name;

            // 모달 열기
            const modal = document.querySelector('#userlist_modal_goods_detail');
            // 이름 넣기
            modal.querySelector('.student_name').innerHTML = user_name;
            
            //로딩 시작

            //이용권 내역 가져오기.
            const page = "/manage/userlist/goods/detail/select";
            const parameter = {
                student_seq:student_seq
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    //초기화
                    const tby_goods_detail = modal.querySelector('#userlist_tby_goods_detail');
                    const copy_tr = tby_goods_detail.querySelector('.copy_tr_goods_detail').cloneNode(true);
                    tby_goods_detail.innerHTML = '';
                    tby_goods_detail.appendChild(copy_tr);

                    // 내역 리스트
                    let now_date = new Date();
                    for(let i = 0; i < result.resultData.length; i++){
                        const r_data = result.resultData[i];
                        const tr = copy_tr.cloneNode(true);
                        tr.classList.remove('copy_tr_goods_detail');
                        tr.classList.add('tr_goods_detail');
                        tr.hidden = false;

                        const goods_status = userlistGoodsStatus(r_data.end_date, now_date);
                        tr.querySelector('.idx').innerHTML = i+1;
                        tr.querySelector('.goods_name').innerHTML = r_data.goods_name+'<br>('+r_data.goods_period+'개월)';
                        tr.querySelector('.goods_start_date').innerHTML = r_data.start_date;
                        tr.querySelector('.goods_origin_end_date').innerHTML = r_data.origin_end_date;
                        tr.querySelector('.goods_status').innerHTML = goods_status;
                        tr.querySelector('.goods_pay_date').innerHTML = r_data.goods_pay_date||'';
                        tr.querySelector('.pay_auto_date').innerHTML = r_data.pay_auto_date;
                        tr.querySelector('.goods_price').innerHTML = (r_data.goods_price*1).toLocaleString();
                        tr.querySelector('.student_seq').value = r_data.student_seq;
                        tr.querySelector('.goods_detail_seq').value = r_data.id;
                        //추가 코드 > 카드 등 뒤에 붙어야 함.
                        if(r_data.is_use == 'Y') 
                            tr.querySelector('.is_use').innerHTML = goods_status.length == 2 ? '이용만료':'유효' ;
                        else
                            tr.querySelector('.is_use').innerHTML = '이용권정지<br>이용권환불';

                        tby_goods_detail.appendChild(tr);
                    }
                }
                const myModal = new bootstrap.Modal(document.getElementById('userlist_modal_goods_detail'), {});
                myModal.show();
            });
        }

        // 만료, 유효 표기
        function userlistGoodsStatus(goods_end_date, now_date){
            // 현재 날짜와 이용권 만료일을 비교해서 (만료 또는 유효(남은일수 + '일')) 표기
            goods_end_date = new Date(goods_end_date);
            const diff = goods_end_date.getTime() - now_date.getTime();
            const diffDays = Math.ceil(diff / (1000 * 3600 * 24));
            if(diffDays < 0){
                return '만료';
            }
            else{
                return '유효<br>('+diffDays+')일';
            }
        }

        // 사용자(학생) 이용권 모달 클리어
        function userlistModalGoodsDetailClear(){
            const modal = document.querySelector('#userlist_modal_goods_detail');
            modal.querySelector('.student_name').innerHTML = '';
        }

        // 엑셀 내보내기(다운)
        function userlistUserExcelDownload(){
            const clone_tag = document.querySelector('#userilst_tb_userinfo').parentElement.cloneNode(true);
            //안에 hidden = true인 태그는 제거
            clone_tag.querySelectorAll('tr').forEach((item)=>{
                if(item.hidden)
                    item.remove();
                //tr 안에 태그중 style display:none인 태그는 제거 
                //tr 안에 태그중 type=hidden 인 태그 제거
                //tr 안에 태그중 hidden = true인 태그는 제거
                //checkbox 제거
                item.querySelectorAll('[style*="display:none"], button, [type="hidden"], [hidden], input[type=checkbox]').forEach((item2)=>{
                    item2.remove();
                });
                //radio 는 Y,N 으로 span 글자로 변경 후 삭제
                item.querySelectorAll('input[type=radio]').forEach((item2)=>{
                    if(item2.checked)
                        item2.insertAdjacentHTML('afterend', '<span>Y</span>');
                    else
                        item2.insertAdjacentHTML('afterend', '<span>N</span>');
                    item2.remove();
                });
            });
            const html = clone_tag.outerHTML;
            _excelDown('회원조회.xls', '회원조회', html); 
        }

        // 담당 선생님 변경.
        function userlistChangeTeacherModal(){
            userlistChgTeachModalClear();
            if(userlistChkUser('no_one', '먼저 변경할 학생 1명을 선택해주세요.')){return;}
            const tr = document.querySelector('.tr_userinfo input[type=checkbox]:checked').closest('tr');

            const user_type = tr.querySelector('.inp_user_type').value;
            const user_key = tr.querySelector('.inp_user_key').value;
            const user_name = tr.querySelector('.inp_user_name').value;
            const child_key = tr.querySelector('.sp_child_name').getAttribute('student_seq');
            const child_name = tr.querySelector('.sp_child_name').innerText;
            
            // 타입에 따라 키 변경.
            const student_seq = user_type == 'student' ? user_key:child_key; 
            const student_name = user_type == 'student' ? user_name:child_name;

            const teach_seq = tr.querySelector('.teach_seq').value;
            const teach_region_seq = tr.querySelector('.teach_region_seq').value;
            const teach_team_code = tr.querySelector('.teach_team_code').value;
            const teach_name = tr.querySelector('.sp_teacher_name').innerText;

            const modal = document.querySelector('#userlist_modal_teacher_change');

            //담임 이름넣기.
            modal.querySelector('.charge_teach_name').innerText = teach_name||' 현재 미배정';
            if(teach_seq != ''){
                //소속, 팀 선택
                const sel_region = modal.querySelector('.sel_region');
                sel_region.value = teach_region_seq;
                userlistSelectRegion(sel_region, '#userlist_sel_team_chg_teach', '팀 선택', teach_team_code);
            }

            //모달 열기
            const myModal = new bootstrap.Modal(document.getElementById('userlist_modal_teacher_change'), {});
            myModal.show();
        }
        
        // 담당선생님 모달 초기화
        function userlistChgTeachModalClear(){
            const modal = document.querySelector('#userlist_modal_teacher_change');
            modal.querySelector('.sel_region').selectedIndex = 0;
            modal.querySelector('.sel_team').selectedIndex = 0;
            modal.querySelector('.teach_name').innerText = '';

            const tby_teach_chg = modal.querySelector('#userlist_tby_teach_chg');
            const copy_tr = tby_teach_chg.querySelector('.copy_tr_teach_chg').cloneNode(true);
            tby_teach_chg.innerHTML = '';
            tby_teach_chg.appendChild(copy_tr);
            copy_tr.hidden = true;
        }

        // 
        function userlistForTeamTeacherSelect(vthis){
            const sel_value = vthis.value;
            const page = "/manage/userlist/teacher/select";
            const parameter = {
                search_team:sel_value
            }
            const modal = document.querySelector('#userlist_modal_teacher_change');
            //로딩 시작
            modal.querySelector('.sp_loding').hidden = false;
            queryFetch(page, parameter, function(result){
                //로딩 끝
                modal.querySelector('.sp_loding').hidden = true;
                //초기화
                const tby_teach_chg = modal.querySelector('#userlist_tby_teach_chg');
                const copy_tr = tby_teach_chg.querySelector('.copy_tr_teach_chg').cloneNode(true);
                tby_teach_chg.innerHTML = '';
                tby_teach_chg.appendChild(copy_tr);
                copy_tr.hidden = true;

                if((result.resultCode||'') == 'success'){
                    for(let i = 0; i < result.resultData.length; i++){
                        const r_data = result.resultData[i];
                        const tr = copy_tr.cloneNode(true);
                        tr.classList.remove('copy_tr_teach_chg');
                        tr.classList.add('tr_teach_chg');
                        tr.hidden = false;
                        tr.querySelector('.teach_name').innerHTML = r_data.teach_name+'('+r_data.group_name+')';
                        tr.querySelector('.teach_seq').value = r_data.id;
                        tr.querySelector('.region_seq').value = r_data.region_seq;
                        tr.querySelector('.team_code').value = r_data.team_code;
                        tby_teach_chg.appendChild(tr);
                    }        
                }
            });
        }

        // 담당 선생님 변경 모발에서 변경 클릭 클릭
        function userlistTeachChgUpdate(vthis){
            const modal = document.querySelector('#userlist_modal_teacher_change');
            const tr = vthis.closest('tr');
            const teach_seq = tr.querySelector('.teach_seq').value;
            const region_seq = tr.querySelector('.region_seq').value;
            const team_code = tr.querySelector('.team_code').value;
            
            //체크 학생 seq가져오기. 일단현 1명만 할 수있게 혹시 복수 수정시 수정필요.
            const chk_tr = document.querySelector('.tr_userinfo input[type=checkbox]:checked').closest('tr');
            const user_type = chk_tr.querySelector('.inp_user_type').value;
            const user_key = chk_tr.querySelector('.inp_user_key').value;
            const child_key = chk_tr.querySelector('.sp_child_name').getAttribute('student_seq');
            const student_seq = user_type == 'student' ? user_key:child_key;

            //전송 업데이트
            const page = "/manage/userlist/teacher/charge/update";
            const parameter = {
                student_seq:student_seq,
                teach_seq:teach_seq,
                region_seq:region_seq,
                team_code:team_code
            };
            //변경 하시겠습니까?
            sAlert('', '변경 하시겠습니까?', 2, function(){
                queryFetch(page, parameter, function(result){
                    if(result.resultCode == 'success'){
                        sAlert('', '변경되었습니다.');
                        //모달 닫기
                        modal.querySelector('.btn-close').click();
                        //리스트 다시 가져오기
                        userlistSelectUser();
                    }
                });
            });
        }

        // 선택 회원 소속/관할 변경
        function userlistChgRegionTeam(){
            userlistModalRegionTeamChangeClear();
            //먼저 소속을 선택해주세요.
            const modal = document.querySelector('#userlist_modal_region_team_change');
            const sel_region = document.querySelector('#userlist_sel_change_region');
            const sel_team = document.querySelector('#userlist_sel_change_team');
            if(sel_region.selectedIndex == 0){
                sAlert('', '먼저 소속을 선택해주세요.');
                return;
            }
            //선택된 회원
            const chkd = document.querySelectorAll('.tr_userinfo input[type=checkbox]:checked');

            //0명 은 리턴
            if(userlistChkUser('zero', '선택된 회원이 없습니다.')){
                return;
            }

            let teach_seqs = '';
            //선택된 회원의 user_key 가져오기
            chkd.forEach(function(el){
                const tr = el.closest('tr');
                const teach_seq = tr.querySelector('.inp_user_key').value;
                if(teach_seqs != '')
                    teach_seqs += ',';
                teach_seqs += teach_seq;
            });

            //담당학생 수치 가져오기.
            userlistTeachChargeStCntSelect(teach_seqs);

            //선택이 1명이면 그 선생님의 소속/관할을 modal에서 보여준다.
            if(userlistChkUser('only_one')){
                const teach_region_name = chkd[0].closest('tr').querySelector('.td_region').innerHTML;
                const teach_team_name = chkd[0].closest('tr').querySelector('.td_team').innerHTML;
                modal.querySelector('.region_name').innerHTML = teach_region_name;
                modal.querySelector('.team_name').innerHTML = teach_team_name;
            }
            const chg_region_name = sel_region.options[sel_region.selectedIndex].text;
            const chg_team_name = sel_team.options[sel_team.selectedIndex].text;
            modal.querySelector('.chg_region_name').innerHTML = chg_region_name;
            modal.querySelector('.chg_team_name').innerHTML = chg_team_name;

            //modal에 선택된 teach_seqs 넣기. / 변경할 region_seq, team_code 넣기.
            modal.querySelector('.teach_seqs').value = teach_seqs;
            modal.querySelector('.chg_region_seq').value = sel_region.value;
            modal.querySelector('.chg_team_code').value = sel_team.value;

            
            //모달 열기
            const myModal = new bootstrap.Modal(document.getElementById('userlist_modal_region_team_change'), {});
            myModal.show();
        }

        // 담당학생 수치 가져오기.
        function userlistTeachChargeStCntSelect(teach_seqs){
            const page = "/manage/userlist/teacher/charge/stcnt/select";
            const parameter = {
                teach_seqs:teach_seqs
            };
            const modal = document.querySelector('#userlist_modal_region_team_change');
            //로딩 시작
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    //로딩 끝
                    //초기화
                    const tby_student_cnt = modal.querySelector('#userlist_tby_student_cnt');
                    const copy_tr = tby_student_cnt.querySelector('.copy_tr_student_cnt').cloneNode(true);
                    tby_student_cnt.innerHTML = '';
                    tby_student_cnt.appendChild(copy_tr);
                    copy_tr.hidden = true;
                    for(let i = 0; i < result.resultData.length; i++){
                        const r_data = result.resultData[i];
                        const tr = copy_tr.cloneNode(true);
                        tr.classList.remove('copy_tr_student_cnt');
                        tr.classList.add('tr_student_cnt');
                        tr.hidden = false;
                        tr.querySelector('.teach_name').innerHTML = r_data.teach_name;
                        tr.querySelector('.student_cnt').innerHTML = '담당학생 ' + r_data.student_cnt+' 명';
                        tr.querySelector('.teach_seq').value = r_data.teach_seq;
                        tby_student_cnt.appendChild(tr);
                    }
                }
            });
        }

        // 소속/관할 변경 저장 클릭
        function userlistChgRegionTeamInsert(){
            const modal = document.querySelector('#userlist_modal_region_team_change');
            //로딩 시작
            modal.querySelector('.sp_loding').hidden = false;

            const teach_seqs = modal.querySelector('.teach_seqs').value;
            const chg_region_seq = modal.querySelector('.chg_region_seq').value;
            const chg_team_code = modal.querySelector('.chg_team_code').value;

            const page = "/manage/userlist/teacher/team/update";
            const parameter = {
                teach_seqs:teach_seqs,
                chg_region_seq:chg_region_seq,
                chg_team_code:chg_team_code
            };
            queryFetch(page, parameter, function(result){
                //로딩 끝
                modal.querySelector('.sp_loding').hidden = true;
                if((result.resultCode||'') == 'success'){
                    sAlert('', '저장되었습니다.');
                    //모달 닫기
                    modal.querySelector('.btn-close').click();
                    //리스트 다시 가져오기
                    userlistSelectUser();
                }
            });
        }

        // 소속/관할 변경 모달 클리어
        function userlistModalRegionTeamChangeClear(){
            const modal = document.querySelector('#userlist_modal_region_team_change');
            modal.querySelector('.chg_region_name').innerHTML = '';
            modal.querySelector('.chg_team_name').innerHTML = '';
            modal.querySelector('.region_name').innerHTML = '';
            modal.querySelector('.team_name').innerHTML = '';
            modal.querySelector('.teach_seqs').value = '';
            modal.querySelector('.chg_region_seq').value = '';
            modal.querySelector('.chg_team_code').value = '';

            //초기화
            const tby_student_cnt = modal.querySelector('#userlist_tby_student_cnt');
            const copy_tr = tby_student_cnt.querySelector('.copy_tr_student_cnt').cloneNode(true);
            tby_student_cnt.innerHTML = '';
            tby_student_cnt.appendChild(copy_tr);
            copy_tr.hidden = true;
        }

        // 선택 회원 이용기간 전체 회원 더하기
        function userlistSelDayManageAll(){
            const tb_userinfo = document.querySelector('#userilst_tb_userinfo');
            const all_chk = tb_userinfo.querySelector('.all_show input[type=checkbox]');
            //all_chk 가 체크 되어 있지 않으면 클릭
            if(!all_chk.checked)
                all_chk.click();

            //선택 회원 이용기간 더하기
            // userlistSelDayManage(); 예전 버전.
            // 연장 모달 열기
            userlistGoodsDayPlusModal();
        }

        // 선택 회원 이용기간 전체 회원 정지
        function userlistSelDayStopAll(){
            const tb_userinfo = document.querySelector('#userilst_tb_userinfo');
            const all_chk = tb_userinfo.querySelector('.all_show input[type=checkbox]');
            //all_chk 가 체크 되어 있지 않으면 클릭
            if(!all_chk.checked)
                all_chk.click();

            //선택 회원 이용기간 정지 모달
            userlistGoodsDayStopModal();
        }

        // 선택 회원 이용기간 끝일 더하기
        function userlistSelDayManage(){
            const day_addtag = document.querySelector('#userlist_inp_day_manage');
            const day_addnum = day_addtag.value*1;

            if(day_addnum == 0){
                sAlert('', '추가할 일수를 입력해주세요.',1,function(){
                    day_addtag.focus();
                });
                
                return;
            }

            // 체크 회원 없으면 리턴
            if(userlistChkUser('zero')) return;

            // 체크된 회원 상태에 따라 키 가져오기.
            let student_seqs = '';
            let student_name = '';
            let student_cnt = '';
            const chkbox = document.querySelectorAll('.tr_userinfo input[type=checkbox]:checked');
            chkbox.forEach(function(el){
                const tr = el.closest('tr');
                const user_type = tr.querySelector('.inp_user_type').value;
                const user_key = tr.querySelector('.inp_user_key').value;
                const user_name = tr.querySelector('.inp_user_name').value;
                const child_key = tr.querySelector('.sp_child_name').getAttribute('student_seq');
                const child_name = tr.querySelector('.sp_child_name').innerText;
            
                // 타입에 따라 키 변경.
                const student_seq = user_type == 'student' ? user_key:child_key;

                if(student_seqs != '')
                    student_seqs += ',';

                if(student_name == '')
                    student_name = user_type == 'student' ? user_name:child_name;
                
                student_seqs += student_seq;
                student_cnt++;
            });

            // xxx 외 xx명
            const msg = student_name+' 외 '+(student_cnt-1)+'명의 학생에게 <span class="text-primary">'+day_addnum+'</span>일을 추가하시겠습니까?'
            +'<br><span class="text-danger">단, 이용권을 사용한 학생만 가능합니다.</span>';
            sAlert('', msg, 2, function(){
                const page = "/manage/userlist/day/update";
                const parameter = {
                    student_seqs:student_seqs,
                    day_addnum:day_addnum
                };
                queryFetch(page, parameter, function(result){
                    if((result.resultCode||'') == 'success'){
                        sAlert('', '저장되었습니다.');
                        userlistSelectUser();
                    }else{
                        sAlert('', '저장에 실패하였습니다.');
                    }
                });
            });
        }

        // 선택된 회원 체크.
        function userlistChkUser(type, msg){
            const chkd = document.querySelectorAll('.tr_userinfo input[type=checkbox]:checked');
            //선택된 회원이 있는지 체크
            if(type == 'zero'){
                
                if(chkd.length == 0){
                    if(msg == undefined) msg = '선택된 회원이 없습니다.';
                    sAlert('', msg);
                    return true;
                }
            }
            //선택된 회원이 1명이 아닌경우 // 1명만 가능하게 할때
            else if(type == 'no_one'){
                if(chkd.length != 1){
                    if(msg != undefined) sAlert('', msg);
                    return true;
                }
            }
            //선택된 회원이 1명인 경우
            else if(type == 'only_one'){
                if(chkd.length == 1){
                    if(msg != undefined) sAlert('', msg);
                    return true;
                }
            }
            
            return false;
        } 

        // 선생님 재직상태 변경
        function userlistChangeUserStatusModal(vthis){
            userlistModalTeacherStatusChangeClear();
            const tr = vthis.closest('tr');

            //재직상태 / 이름 가져오기.
            const teach_seq = tr.querySelector('.inp_user_key').value;
            const teach_status = tr.querySelector('.inp_user_status').value;
            const teach_name = tr.querySelector('.inp_user_name').value;

            // 선택 선생님 이름 넣기 / seq 넣기
            const modal = document.querySelector('#userlist_modal_teacher_status_change');
            modal.querySelector('.teach_name').innerHTML = teach_name;
            modal.querySelector('.teach_seq').value = teach_seq;

            // 재직상태에  따른 버튼 클릭해주기. btn-outline-primary btn_teach_status
            if(teach_status == 'Y')
                modal.querySelector('.btn_teach_status.btn-outline-primary').click();
            else
                modal.querySelector('.btn_teach_status.btn-outline-danger').click();

            const myModal = new bootstrap.Modal(document.getElementById('userlist_modal_teacher_status_change'), {});
            myModal.show();
        }

        // 선생님 재직상태 클리어(초기화)
        function userlistModalTeacherStatusChangeClear(){
            const modal = document.querySelector('#userlist_modal_teacher_status_change');
            modal.querySelector('.teach_name').innerHTML = '';
            modal.querySelectorAll('.btn_teach_status').forEach((item)=>{
                item.classList.remove('active');
            });
            modal.querySelector('.div_resignation_date').hidden = true;
            modal.querySelector('.teach_seq').value = '';
        }

        // 선생님 재직상태 버튼 클릭
        function userlistChgTeachStusBtnClick(vthis){
            const modal = document.querySelector('#userlist_modal_teacher_status_change');
            modal.querySelectorAll('.btn_teach_status').forEach((item)=>{
                item.classList.remove('active');
            });
            vthis.classList.add('active');
            
            // vthis == btn-outline-danger div_resignation_date 보이기 반대면 숨기기
            if(vthis.classList.contains('btn-outline-danger')){
                modal.querySelector('.div_resignation_date').hidden = false;
            }else{
                modal.querySelector('.div_resignation_date').hidden = true;
            }
        }

        // 선생님 재직상태 변경 저장
        function userlistChgTeachStusInsert(){
            const modal = document.querySelector('#userlist_modal_teacher_status_change');
            const teach_seq = modal.querySelector('.teach_seq').value;
            const btn_teach_status = modal.querySelector('.btn_teach_status.active');
            const teach_status = btn_teach_status.classList.contains('btn-outline-danger') ? 'N':'Y';
            const resignation_date = modal.querySelector('.resignation_date').value;

            //로딩 시작
            modal.querySelector('.sp_loding').hidden = false;
            //선택된 선생님 재직상태 변경
            const page = "/manage/userlist/teacher/status/update";
            const parameter = {
                teach_seq:teach_seq,
                teach_status:teach_status,
                resignation_date:resignation_date
            };
            queryFetch(page, parameter, function(result){
                // 로딩 끝
                modal.querySelector('.sp_loding').hidden = true;
                if(result.resultCode == 'success'){
                    sAlert('', '변경되었습니다.');
                    //모달 닫기
                    modal.querySelector('.btn-close').click();
                    //리스트 다시 가져오기
                    userlistSelectUser();
                }
            });
        }

        // 그룹 없음 사용자 그룹 등록
        function userlistChangeUserGroup(){
            //선택 회원이 없으면 리턴
            if(userlistChkUser('zero')) return;

            //선택 그룹 키 / 타입 가져오기 userlist_sel_change_usergroup
            const sel_group = document.querySelector('#userlist_sel_change_usergroup');
            const group_seq = sel_group.value;
            const group_type = sel_group.options[sel_group.selectedIndex].getAttribute('group_type');

            //선택회원 가져오기
            const chkbox = document.querySelectorAll('.tr_userinfo input[type=checkbox]:checked');
            let user_keys = '';
            chkbox.forEach(function(el){
                const tr = el.closest('tr');
                const user_type = tr.querySelector('.inp_user_type').value;
                //타입이 group_type 이 아니면 체크 해제
                if(user_type != group_type){
                    el.checked = false;
                }else{
                    const user_key = tr.querySelector('.inp_user_key').value;
                    if(user_keys != '')
                        user_keys += ',';
                    user_keys += user_key;
                }
            });
            if(userlistChkUser('zero')) return;
            const page = "/manage/userlist/group/update";
            const parameter = {
                user_keys:user_keys,
                group_seq:group_seq,
                group_type:group_type
            };
            
            // 저장 확인 메세지
            const msg = '선택된 회원을 <span class="text-primary">'+sel_group.options[sel_group.selectedIndex].text+'</span> 그룹으로 변경하시겠습니까?'+
            '<br><span class="text-danger">그룹과 타입이 다른 회원은 체크가 자동 해제 되었습니다.</span>';
            sAlert('', msg, 2, function(){
                queryFetch(page, parameter, function(result){
                    if(result.resultCode == 'success'){
                        sAlert('', '저장되었습니다.');
                        userlistSelectUser();
                    }else{
                        sAlert('', '저장에 실패하였습니다.');
                    }
                });
            });

        }

        // 문자 / 알림톡 / 푸시 보내기
        function userlistSendSms(){
            // 선택 회원 체크
            if(userlistChkUser('zero')) return;
            
            // 회원들중 전화번호가 없거나 10자리 이하이면 체크 해제
            // 문자 쪽 전역 변수.
            select_member = [];
            const chkbox = document.querySelectorAll('.tr_userinfo input[type=checkbox]:checked');
            chkbox.forEach(function(el){
                const tr = el.closest('tr');
                const phone_num = tr.querySelector('.td_user_phone').innerText;
                const user_type_code = tr.querySelector('.inp_user_type').value;
                let user_type = '';
                if(user_type_code == 'student') user_type = '(학생)';
                else if(user_type_code == 'parent') user_type = '(학부모)';
                else if(user_type_code == 'teacher') user_type = '(선생님)';

                // 전화번호가 없거나 10자리 이하이면 체크 해제
                if(phone_num.length < 10){
                    el.checked = false;
                }else{
                    const member_id = tr.querySelector('.div_user_id').getAttribute('data');
                    const member_name = tr.querySelector('.sp_user_name').innerText;
                    const member = {
                        member_id: member_id,
                        member_name: member_name+user_type,
                        grade: '',
                        phone: phone_num,
                        push_key: '',
                    };
                    // 푸시키는 추후에 추가
                    // 추가 코드.
                    select_member.push(member);
                }
            });

            if(userlistChkUser('zero')) return;
            else{
                const msg = "선택 회원에게 문자를 보내시겠습니까?<br><span class='text-danger'>전화번호가 없거나 10자리 이하인 회원은 자동으로 체크가 해제됩니다.</span>";
                sAlert('', msg, 2, function(){
                    // 문자 div 보이기
                    const div_alarm = document.querySelector('#userlist_div_alarm');
                    div_alarm.hidden = false;
                    alarmSelectUserAddList();
                });
            }
        }

        // 문자 div 닫기 clear
        function userlistAlarmClose(){
            const div_alarm = document.querySelector('#userlist_div_alarm');
            div_alarm.hidden = true;
            alarmSelectMemberClear();
            alarmMessageFormClearIn();
        }

        //유저 리스트 수정버튼 클릭 후 저장 버튼
        function userlistTrEditUpdate(vthis){
            let chg_student_is_use = "";
            let chg_student_seq = "";
            let chg_student_name = "";
            let chg_parent_is_use = "";
            let chg_parent_seq = "";
            let chg_parent_name = "";
            let chg_teacher_is_use = "";
            let chg_teacher_seq = "";
            let chg_teacher_name = "";

            const tr = vthis.closest('tr');
            const is_use = tr.querySelector('.is_use').value;
            const radio_is_use = tr.querySelector('.chk_is_use:checked').value == 'option1' ? 'Y':'N';
            const user_type = tr.querySelector('.inp_user_type').value;

            //다르면 저장
            if(is_use != radio_is_use){
                if(user_type == 'student')
                    chg_student_is_use = radio_is_use;
                else if(user_type == 'parent')
                    chg_parent_is_use = radio_is_use;
                else if(user_type == 'teacher')
                    chg_teacher_is_use = radio_is_use;
            }

            //inp_user_name, sp_user_name 다르면 저장
            const inp_user_name = tr.querySelector('.inp_user_name').value;
            const sp_user_name = tr.querySelector('.sp_user_name').innerText;
            if(inp_user_name != sp_user_name){
                if(user_type == 'student')
                    chg_student_name = inp_user_name;
                else if(user_type == 'parent')
                    chg_parent_name = inp_user_name;
                else if(user_type == 'teacher')
                    chg_teacher_name = inp_user_name;
            }

            //sp_parent_name, sp_parent_name 다르면 저장 // 학생에서 학부모 이름변경.
            const sp_parent_name = tr.querySelector('.sp_parent_name').innerText;
            const inp_parent_name = tr.querySelector('.inp_parent_name').value;
            if(sp_parent_name != inp_parent_name && user_type == 'student'){
                chg_parent_name = inp_parent_name;
            }
            //sp_child_name, inp_child_name 다르면 저장 // 학부모에서 학생 이름변경.
            const sp_child_name = tr.querySelector('.sp_child_name').innerText;
            const inp_child_name = tr.querySelector('.inp_child_name').value;
            if(sp_child_name != inp_child_name && user_type == 'parent'){
                chg_student_name = inp_child_name;
            }

            //타입에 따라 seq(id) 가져오기
            if(user_type == 'student'){
                chg_student_seq = tr.querySelector('.inp_user_key').value;
                chg_parent_seq = tr.querySelector('.parent_seq').value;
            }
            else if(user_type == 'parent'){
                chg_parent_seq = tr.querySelector('.inp_user_key').value;
                chg_student_seq = tr.querySelector('.sp_child_name').getAttribute('student_seq');
            }
            else if(user_type == 'teacher'){
                chg_teacher_seq = tr.querySelector('.inp_user_key').value;
            }

            //변경사항이 없으면 리턴
            if (
                chg_student_is_use == "" &&
                chg_student_seq == "" &&
                chg_student_name == "" &&
                chg_parent_is_use == "" &&
                chg_parent_seq == "" &&
                chg_parent_name == "" &&
                chg_teacher_is_use == "" &&
                chg_teacher_seq == "" &&
                chg_teacher_name == ""
            ) {
                sAlert('', '변경사항이 없습니다.');
                return;
            }

            //변경사항 저장
            const page = "/manage/userlist/user/update";
            const parameter = {
                chg_student_is_use:chg_student_is_use,
                chg_student_seq:chg_student_seq,
                chg_student_name:chg_student_name,
                chg_parent_is_use:chg_parent_is_use,
                chg_parent_seq:chg_parent_seq,
                chg_parent_name:chg_parent_name,
                chg_teacher_is_use:chg_teacher_is_use,
                chg_teacher_seq:chg_teacher_seq,
                chg_teacher_name:chg_teacher_name
            };

            queryFetch(page, parameter, function(result){
                if(result.resultCode == 'success'){
                    sAlert('', '저장되었습니다.');
                    //리스트 다시 가져오기
                    userlistSelectUser();
                }else{
                    sAlert('', '저장에 실패하였습니다.');
                }
            });
        }

        // 사용자 리스트 수정버튼 클릭
        function userlistUserHistoryModal(vthis){
            userlistModalEditHistoryClear();
            const tr = vthis.closest('tr');
            const user_type = tr.querySelector('.inp_user_type').value;
            const user_key = tr.querySelector('.inp_user_key').value;

            const page = "/manage/log/select";
            const parameter = {
                select_type:user_type,
                select_seq:user_key,
                max_count:10
            };
            const modal = document.querySelector('#userlist_modal_edit_history');
            
            //로딩 시작
            modal.querySelector('.sp_loding').hidden = false;
            queryFetch(page, parameter, function(result){
                // 로딩 끝
                modal.querySelector('.sp_loding').hidden = true;
                if(result.resultCode == 'success'){
                    //초기화
                    const tby_edit_history = modal.querySelector('.tby_edit_history');
                    const copy_tr = tby_edit_history.querySelector('.copy_tr_edit_history').cloneNode(true);
                    tby_edit_history.innerHTML = '';
                    tby_edit_history.appendChild(copy_tr);
                    copy_tr.hidden = true;

                    // 내역 리스트
                    for(let i = 0; i < result.logs.length; i++){
                        const log = result.logs[i];
                        const tr = copy_tr.cloneNode(true);
                        tr.classList.remove('copy_tr_edit_history');
                        tr.classList.add('tr_edit_history');
                        tr.hidden = false;

                        tr.querySelector('.log_content').innerText = log.log_content;
                        tr.querySelector('.created_at').innerText = '('+log.created_at+')';
                        tr.querySelector('.log_remark').innerText = log.log_remark || '설명을 입력해주세요.';
                        tr.querySelector('.txt_log_remark').value = log.log_remark || '';
                        tr.querySelector('.log_seq').value = log.id;
                        tby_edit_history.appendChild(tr);
                    }


                }
                // tr_edit_history 없으면 내역 없음 표시
                if(modal.querySelectorAll('.tr_edit_history').length == 0){
                        const btm = modal.querySelector('.none_edit_history');
                        btm.hidden = false;
                }else{
                    const btm = modal.querySelector('.none_edit_history');
                    btm.hidden = true;
                }
            });

            //모달 열기
            const myModal = new bootstrap.Modal(document.getElementById('userlist_modal_edit_history'), {});
            myModal.show();
        }

        // 사용자 리스트 수정버튼 클리어
        function userlistModalEditHistoryClear(){
            const modal = document.querySelector('#userlist_modal_edit_history');
            const tby_edit_history = modal.querySelector('.tby_edit_history');
            const copy_tr = tby_edit_history.querySelector('.copy_tr_edit_history').cloneNode(true);
            tby_edit_history.innerHTML = '';
            tby_edit_history.appendChild(copy_tr);
            copy_tr.hidden = true;
            //내역 없음 숨김.
            const btm = modal.querySelector('.none_edit_history');
            btm.hidden = false;
        }

        // 수정 내역 리스트 상세내역 수정
        function userlistLogRemarkEdit(vthis){
            //vthis 의 다음 태그 hidden 토글
            const txt_log_remark = vthis.closest('tr').querySelector('.txt_log_remark');
            txt_log_remark.hidden = !txt_log_remark.hidden;
        }

        // 수정 내역 리스트 상세내역:remark 저장
        function userlistLogRemarkUpdate(vthis){
            const modal = document.querySelector('#userlist_modal_edit_history');

            // hidden이 false인 textarea의 value를 가져와서 remark에 저장
            const txt_log_remark = modal.querySelectorAll('.txt_log_remark');
            let log_seqs = '';
            let log_remarks = '';
            txt_log_remark.forEach(function(el){
                if(!el.hidden){
                    if(log_seqs != '')
                        log_seqs += ',';
                    log_seqs += el.closest('tr').querySelector('.log_seq').value;
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
            sAlert('', '열린 텍스트를 저장하시겠습니까?', 2, function(){
                //로딩 시작
                vthis.querySelector('.sp_loding').hidden = false;
                queryFetch(page, parameter, function(result){
                    // 로딩 끝
                    vthis.querySelector('.sp_loding').hidden = true;
                    if(result.resultCode == 'success'){
                        sAlert('', '저장되었습니다.');
                        //리스트 다시 가져오기
                        userlistSelectUser();
                        //변경된 수치를 a에 넣어주기.
                        //log_seqs의 수치인 tr을 가져와서 a에 log_remarks를 넣어준다.
                        const log_seqs_arr = log_seqs.split(',');
                        //있으면
                        if(log_seqs_arr.length > 0){
                            log_seqs_arr.forEach(function(el){
                                const tr = modal.querySelector('.log_seq[value="'+el+'"]').closest('tr');
                                const a = tr.querySelector('.log_remark');
                                const txt_log_remark = tr.querySelector('.txt_log_remark');
                                a.innerText = txt_log_remark.value;
                                //txt_log_remark 숨기기
                                txt_log_remark.hidden = true;
                            });
                        }


                    }else{
                        sAlert('', '저장에 실패하였습니다.');
                    }
                });
            });
        }

        // 이용권 연장 모달
        function userlistGoodsDayPlusModal(){
            // 밖의 연장일 가져오기.
            const day_sum = document.querySelector('#userlist_inp_day_manage').value;

            //초기화
            userlistGoodsDayPlusModalClear();

            // 체크 회원 없으면 리턴
            if(userlistChkUser('zero')) return;

            // 체크 확인.
            let first_tr = null;
            let student_seqs = '';
            const chkbox = document.querySelectorAll('.tr_userinfo input[type=checkbox]:checked');
            chkbox.forEach(function(el){
                const tr = el.closest('tr');
                // goods_period 가 '' 이면 체크 해제 continue
                const goods_period = tr.querySelector('.goods_period').value;
                if(goods_period == ''){
                    el.checked = false;
                    return;
                }
                const user_key = tr.querySelector('.inp_user_key').value;
                if(student_seqs != '')
                    student_seqs += ',';

                student_seqs += user_key;
            });

            // 체크 회원 없으면 리턴
            if(userlistChkUser('zero')) return;

            const student_seq = student_seqs;

            const modal = document.querySelector('#userlist_modal_goods_day_plus');
            modal.querySelector('.student_seq').value = student_seq;
            modal.querySelector('.plus_day_cnt').value = day_sum;


            //로그 이용권 정지 내역 가져오기.
            //학생의 goods_details 정보 가져오기.
            userlistGoodsDayInfo('plus', function(){
                modal.querySelector('.plus_day_cnt').onchange();
                const myModal = new bootstrap.Modal(document.getElementById('userlist_modal_goods_day_plus'), {});
                myModal.show();
            });   
        }

        // 이용권 연장 모달 초기화(클리어)
        function userlistGoodsDayPlusModalClear(){
            const modal = document.querySelector('#userlist_modal_goods_day_plus');
            modal.querySelector('.inp_log_remark').value = '';
            modal.querySelector('.student_seq').value = '';
            modal.querySelector('.log_content').innerText = '';
            modal.querySelector('.log_remark').innerText = '';
            modal.querySelector('.log_created_at').innerText = '';
            modal.querySelector('.goods_start_date').innerText = '';
            modal.querySelector('.goods_end_date').innerText = '';
            modal.querySelector('.after_goods_start_date').innerText = '';
            modal.querySelector('.after_goods_end_date').innerText = '';
            modal.querySelector('.plus_day_cnt').value = 0;
            modal.querySelectorAll('.tr_log_list').forEach(function(el){
                el.remove();
            });
            modal.querySelectorAll('.lenth_over_hidden').forEach(function(el){
                el.hidden = false;
            });
        }

        // 이용권 정지 모달
        function userlistGoodsDayStopModal(){
            event.stopPropagation();
            //초기화
            userlistGoodsDayStopModalClear();

            // 체크 회원 없으면 리턴
            if(userlistChkUser('zero')) return;

            //학생의 seq를 넣어준다.
            // 체크된 회원 상태에 따라 키 가져오기.
            let student_seqs = '';
            let student_name = '';
            let student_cnt = '';
            let first_tr = null;
            const chkbox = document.querySelectorAll('.tr_userinfo input[type=checkbox]:checked');
            chkbox.forEach(function(el){
                const tr = el.closest('tr');
                // goods_period 가 '' 이면 체크 해제 continue
                const goods_period = tr.querySelector('.goods_period').value;
                if(goods_period == ''){
                    el.checked = false;
                    return;
                }
                if(first_tr == null) first_tr = tr;
                const user_type = tr.querySelector('.inp_user_type').value;
                const user_key = tr.querySelector('.inp_user_key').value;
                const user_name = tr.querySelector('.inp_user_name').value;
                const child_key = tr.querySelector('.sp_child_name').getAttribute('student_seq');
                const child_name = tr.querySelector('.sp_child_name').innerText;
            
                // 타입에 따라 키 변경.
                const student_seq = user_type == 'student' ? user_key:child_key;

                if(student_seqs != '')
                    student_seqs += ',';

                if(student_name == '')
                    student_name = user_type == 'student' ? user_name:child_name;
                
                student_seqs += student_seq;
                student_cnt++;
            });

            // 체크 회원 없으면 리턴
            if(userlistChkUser('zero')) return;

            const student_seq = student_seqs;

            const modal = document.querySelector('#userlist_modal_goods_day_stop');
            modal.querySelector('.student_seq').value = student_seq;

            // stop_cnt_max goods_period 가 12면 2 6이면 1
            const goods_period = first_tr.querySelector('.goods_period').value;
            const stop_cnt_max = goods_period == 12 ? 2:1;
            modal.querySelector('.stop_cnt_max').innerText = stop_cnt_max;

            //로그 이용권 정지 내역 가져오기.
            //학생의 goods_details 정보 가져오기.
            userlistGoodsDayInfo('stop', function(){
                const myModal = new bootstrap.Modal(document.getElementById('userlist_modal_goods_day_stop'), {});
                myModal.show();
            });   
        }
        
        // 이용권 정지 모달 초기화(클리어)
        function userlistGoodsDayStopModalClear(){
            const modal = document.querySelector('#userlist_modal_goods_day_stop');
            modal.querySelector('.inp_log_remark').value = '';
            modal.querySelector('.student_seq').value = '';
            modal.querySelector('.stop_cnt_max').innerText = 0;
            modal.querySelector('.stop_cnt').innerText = 0;
            modal.querySelector('.log_content').innerText = '';
            modal.querySelector('.log_remark').innerText = '';
            modal.querySelector('.log_created_at').innerText = '';
            modal.querySelector('.goods_start_date').innerText = '';
            modal.querySelector('.goods_end_date').innerText = '';
            modal.querySelector('.after_goods_start_date').innerText = '';
            modal.querySelector('.after_goods_end_date').innerText = '';
            modal.querySelector('.stop_day_cnt').value = 0;
            modal.querySelectorAll('.tr_log_list').forEach(function(el){
                el.remove();
            });
            modal.querySelectorAll('.lenth_over_hidden').forEach(function(el){
                el.hidden = false;
            });
        }

        // 
        function userlistGoodsDayInfo(type, callback){
            const modal = document.querySelector(`#userlist_modal_goods_day_${type}`);
            const student_seq = modal.querySelector('.student_seq').value;

            // 전송
            const page = "/manage/userlist/goods/day/select";
            const parameter = {
                type:type,
                student_seq:student_seq
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    const gd = result.goods_detail;
                    const logs = result.logs;
                    const title1 = result.title1;
                    const title2 = result.title2;

                    //input name inp_cb_userinfo checked length - 1
                    const inp_cb_userinfo = document.querySelectorAll('.tr_userinfo input[type=checkbox]:checked');
                    const student_chk_len = inp_cb_userinfo.length - 1;
                    const after_str = student_chk_len > 0 ? ' 외 '+student_chk_len+'명':'의';
                    let title = '';

                    if(type == 'stop'){
                        modal.querySelector('.stop_cnt').innerText = gd.stop_cnt||0;
                        title = '<span class="fw-bold">'+title1 + '</span> '+after_str+' <span class="fw-bold">' + title2 + '</span> 이용권 정지합니다.';
                    }
                    else if(type == 'plus'){
                        // 내역 tr copy clone~ 초기화
                        title = '<span class="fw-bold">'+title1 + '</span> '+after_str+' <span class="fw-bold">' + title2 + '</span> 이용권을 연장합니다.';
                    }
                    if(logs.length > 0){
                        const copy_tr_log_list = modal.querySelector('.copy_tr_log_list');
                        for(let i = 0; i < logs.length; i++){
                            if(i == 0){
                                modal.querySelector('.log_content').innerText = logs[0].log_content;
                                modal.querySelector('.log_remark').innerText = logs[0].log_remark;
                                modal.querySelector('.log_created_at').innerText = (logs[0].created_at||'').substr(0,10);
                            }else{
                                const tr = copy_tr_log_list.cloneNode(true);
                                tr.classList.remove('copy_tr_log_list');
                                tr.classList.add('tr_log_list');
                                tr.querySelector('.log_content').innerText = logs[i].log_content;
                                tr.querySelector('.log_remark').innerText = logs[i].log_remark;
                                tr.querySelector('.log_created_at').innerText = (logs[i].created_at||'').substr(0,10);
                                copy_tr_log_list.after(tr);
                            }
                        }
                    }
                    if(student_chk_len > 0){
                        modal.querySelectorAll('.lenth_over_hidden').forEach(function(el){
                            el.hidden = true;
                        });
                    }
                    modal.querySelector('.modal-title').innerHTML = title;
                    modal.querySelector('.goods_start_date').innerText = gd.start_date;
                    modal.querySelector('.goods_end_date').innerText = gd.end_date;
                    modal.querySelector('.after_goods_start_date').innerText = gd.start_date;
                    modal.querySelector('.after_goods_end_date').innerText = gd.end_date;
                }
                if(callback != undefined) callback();
            });

        }

        // 이용권 연장 기간 조정
        function userlistModalPlusDayCnt(vthis, type){
            const modal = document.querySelector('#userlist_modal_goods_day_plus');
            const plus_day = modal.querySelector('.plus_day_cnt');
            if(type == 'up'){
                vthis.previousElementSibling.stepUp();
            }else if(type == 'down'){
                vthis.nextElementSibling.stepDown();
            }
            userlistModalPlusDayChange(modal, plus_day.value);
        }
        // 
        function userlistModalPlusDayChange(modal,day_cnt){
            if(modal == undefined) modal = document.querySelector('#userlist_modal_goods_day_plus');
            if(day_cnt == undefined) day_cnt = modal.querySelector('.plus_day_cnt').value || 0;

            // goods_end_date + day_cnt = after_goods_end_date
            const goods_end_date = modal.querySelector('.goods_end_date').innerText;
            const after_goods_end_date = modal.querySelector('.after_goods_end_date');

            const goodsEndDate = new Date(goods_end_date);
            const afterGoodsEndDate = new Date(goodsEndDate.getTime() + day_cnt * 24 * 60 * 60 * 1000);
            after_goods_end_date.innerText = afterGoodsEndDate.toISOString().split('T')[0];
        }
        // 이용권 정지 기간 조정
        function userlistModalStopDayCnt(vthis, type){
            const modal = document.querySelector('#userlist_modal_goods_day_stop');
            const stop_day = modal.querySelector('.stop_day_cnt');
            if(type == 'up'){
                vthis.previousElementSibling.stepUp();
            }else if(type == 'down'){
                vthis.nextElementSibling.stepDown();
            }
            userlistModalPlusDayChange(modal, stop_day.value);

            // 이용권 정지 시작일 변경시 종료일 변경
            userlistStopStartDateChange();
        }

        // 이용권 정지 저장
        function userlistGoodsDayStopModalSave(){
            const modal = document.querySelector('#userlist_modal_goods_day_stop');
            const student_seq = modal.querySelector('.student_seq').value;
            const stop_day_cnt = modal.querySelector('.stop_day_cnt').value;
            const log_remark = modal.querySelector('.inp_log_remark').value;
            const stop_start_date = modal.querySelector('.stop_start_date').value;
            const stop_end_date = modal.querySelector('.stop_end_date').value;

            // cnt가 0이면 리턴
            if(stop_day_cnt == 0){
                sAlert('', '정지일수를 입력해주세요.',1,function(){
                    modal.querySelector('.stop_day_cnt').focus();
                });
                return;
            }

            // 신청사유 입력 안되어 있으면 리턴
            if(log_remark == ''){
                sAlert('', '신청사유를 입력해주세요.',1,function(){
                    modal.querySelector('.inp_log_remark').focus();
                });
                return;
            }
            
            const page = "/manage/userlist/goods/day/stop/insert";
            const parameter = {
                student_seqs:student_seq,
                stop_day_cnt:stop_day_cnt,
                log_remark:log_remark,
                stop_start_date:stop_start_date,
                stop_end_date:stop_end_date
            };

            // 저장 확인 메세지
            const msg = '이용권을 정지하시겠습니까?';
            sAlert('', msg, 2, function(){
                queryFetch(page, parameter, function(result){
                    if(result.resultCode == 'success'){
                        if((result.cant_students||'') == '')
                            sAlert('', '저장되었습니다.');
                        else{
                            const names = result.cant_students.substr(0, result.cant_students.length - 1);
                            sAlert('', '저장되었으나,<br> 다음 학생('+names+')은 더 이상 정지할 수 없습니다.');
                        }
                        //모달 닫기
                        modal.querySelector('.btn-close').click();
                        //리스트 다시 가져오기
                        userlistSelectUser();
                    }else{
                        sAlert('', '저장에 실패하였습니다.');
                    }
                });
            });
        }

        // 이용권 정지 시작일 변경시 종료일 변경
        function userlistStopStartDateChange(){
            const modal = document.querySelector('#userlist_modal_goods_day_stop');
            const stop_day_cnt = modal.querySelector('.stop_day_cnt').value;
            //stop_start_date + stop_day_cnt = stop_end_date
            const stop_start_date = modal.querySelector('.stop_start_date').value;
            const stop_end_date = modal.querySelector('.stop_end_date');
            const stopStartDate = new Date(stop_start_date);
            const stopEndDate = new Date(stopStartDate.getTime() + stop_day_cnt * 24 * 60 * 60 * 1000);
            stop_end_date.value = stopEndDate.toISOString().split('T')[0];

            //현재일로 부터 과거는 선택 못하게 하기.
            const today = new Date();
            const today_str = today.toISOString().split('T')[0];
            if(stop_start_date < today_str){
                modal.querySelector('.stop_start_date').value = today_str;
                //오늘 + stop_day_cnt = stop_end_date
                modal.querySelector('.stop_end_date').value = new Date(today.getTime() + stop_day_cnt * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
            }
        }

        // 이용권 연장 저장(NEW)
        function userlistGoodsDayPlusModalSave(){
            const modal = document.querySelector('#userlist_modal_goods_day_plus');
            const student_seqs = modal.querySelector('.student_seq').value;
            const plus_day_cnt = modal.querySelector('.plus_day_cnt').value;
            const log_remark = modal.querySelector('.inp_log_remark').value;

            // cnt가 0이면 리턴
            if(plus_day_cnt == 0){
                sAlert('', '연장일수를 입력해주세요.',1,function(){
                    modal.querySelector('.plus_day_cnt').focus();
                });
                return;
            }

            // 신청사유 입력 안되어 있으면 리턴
            if(log_remark == ''){
                sAlert('', '신청사유를 입력해주세요.',1,function(){
                    modal.querySelector('.inp_log_remark').focus();
                });
                return;
            }

            const page = "/manage/userlist/day/update";
            const parameter = {
                student_seqs:student_seqs,
                day_addnum: plus_day_cnt,
                log_remark:log_remark
            };

            sAlert('', '이용권을 연장하시겠습니까?', 2, function(){
                queryFetch(page, parameter, function(result){
                    if(result.resultCode == 'success'){
                        sAlert('', '저장되었습니다.');
                        //모달 닫기
                        modal.querySelector('.btn-close').click();
                        //리스트 다시 가져오기
                        userlistSelectUser();
                    }else{
                        sAlert('', '저장에 실패하였습니다.');
                    }
                });
            });
        }

        // 이용권 상세내역에서 상세버튼 클릭
        function userlistOpenGoodsDetailLog(vthis){
            const tr = vthis.closest('tr');
            const student_seq = tr.querySelector('.student_seq').value;
            const goods_detail_seq = tr.querySelector('.goods_detail_seq').value;
            
            const page = "/manage/userlist/goods/detail/log/select";
            const parameter = {
                type:'all',
                student_seq:student_seq,
                goods_detail_seq:goods_detail_seq
            };
            if(vthis.classList.contains('active')){
                //active이면 닫기, tr_log_list 삭제
                vthis.classList.remove('active');
                const idx = tr.querySelector('.idx').innerText;
                document.querySelectorAll('.tr_log_list_'+idx).forEach(function(el){
                    el.remove();
                });
                return;
            }
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    const clone_tr = tr.cloneNode(true);
                    result.goods_detail_logs.forEach(function(log){
                        const type = log.type == 'plus' ? '연장':'정지';
                        const idx = clone_tr.querySelector('.idx').innerText;
                        clone_tr.classList.add('tr_log_list_'+idx);
                        clone_tr.querySelector('.idx').innerHTML = '이용권<br>'+type;
                        clone_tr.querySelector('.goods_end_date').innerText = '➡️'+(log.end_date||'').substr(0, 10); 
                        clone_tr.querySelector('.goods_end_date').hidden = false;
                        clone_tr.querySelector('.goods_status').innerHTML = type+'<br>('+log.day_cnt+'일)';
                        clone_tr.querySelector('.goods_pay_date').innerText = (log.created_at||'').substr(0, 10);
                        clone_tr.querySelector('.pay_auto_date').colSpan = 4;
                        clone_tr.querySelector('.pay_auto_date').innerText = log.remark;
                        clone_tr.querySelector('.goods_price').hidden = true;
                        clone_tr.querySelector('.is_use').hidden = true;
                        clone_tr.querySelector('.td_func').hidden = true;
                        tr.after(clone_tr);
                    });
                    if((result.goods_detail_logs||'').length > 0){
                        //class active toggle
                        vthis.classList.toggle('active');
                    }
                }
            });
        }
    </script>
@endsection
