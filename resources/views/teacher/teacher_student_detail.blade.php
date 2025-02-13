@extends('layout.layout')

@section('head_title')
학생정보수정
@endsection

@section('add_css_js')
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
@endsection

{{--
    추가코드
    1. 상담관리, 학습관리, 학습플래너, 쪽지보내기 이동.
    2. 포인트 모달 디자인.
--}}
{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    <div class="row pt-2" data-div-main="user_add">
        <input type="hidden" data-main-student-seq value="{{ $student->id }}">
        <input type="hidden" data-main-parent-seq value="{{ $parent ? $parent->id:'' }}">
        <input type="hidden" data-main-class-seq value="{{ $student->class_seq }}">
        <input type="hidden" data-main-team-name value="{{ $student->team_name }}">
        <input type="hidden" data-main-region-name value="{{ $student->region_name }}">

        <div class="sub-title d-flex justify-content-between">
          <h2 class="text-sb-42px">
            <button data-btn-back-page="" class="btn p-0 row mx-0 all-center" onclick="teachStDtailBack();">
                <img src="{{ asset('images/black_arrow_left_tail.svg') }}" width="52" class="px-0">
            </button>
            <span class="me-2">{{ $student->student_name }} 학생</span>
            <span class="ht-make-title on text-r-20px py-1 px-3 ms-1 h-42 d-flex align-items-center">{{ $student->school_name }}/{{ $student->grade_name }}</span>
          </h2>
      </div>
    <div class="text-b-28px mb-4 h-center justify-content-between">
        <span> 학생 정보</span>
        <div>
            <button type="button" onclick="teachStDetailChangeLogShow()" data-btn-change-log
                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">변경내역 확인</button>
        </div>
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
            <tr class="text-start ">
                <td class="text-start text-start ps-4 scale-text-gray_06">이름</td>
                <td class="text-start text-start p-4">
                <div class="h-center justify-content-between gap-2">
                    <div data-text="" data-ori="student_name"
                        class="row align-items-center scale-text-gray_05 is_content">
                        {{ $student->student_name }}
                    </div>
                    <input data-modify-editor="student_name" hidden
                    class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" value="{{ $student->student_name }}">
                    <div class="col-auto">
                        <button type="button" onclick="teachStDtailModifyToggle(this);" hidden data-modify
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3"> 이름 변경 </button>
                    </div>
                </div>
                </td>

                <!-- 생년월일  -->
                <td class="text-start text-start ps-4 scale-text-gray_06">생년월일</td>
                <td class="text-start text-start p-4">
                    <div class="h-center justify-content-between gap-2">
                        <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative" data-student-point-now>
                            {{ $student->student_birthday??'' }}
                        </div>
                    </div>
                </td>

            </tr>
            <tr class="text-start">
                <td class="text-start ps-4 scale-text-gray_06 ">아이디</td>
                <td class="text-start px-4 ">
                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                    {{ $student->student_id }}
                    </div>
                </td>
                <td class="text-start ps-4 scale-text-gray_06">비밀번호</td>
                <td class="text-start p-4 ">
                <div class="h-center justify-content-between gap-2">
                    <div data-user-id data-text="" data-ori="student_pw"
                    class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3">
                        ********
                    </div>
                </div>
                </td>
            </tr>

            <tr class="text-start">
                <td class="text-start ps-4 scale-text-gray_06">성별</td>
                <td class="text-start px-4">
                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                        {{ $student->student_sex }}
                    </div>
                </td>
                {{-- 휴대전화 --}}
                <td class="text-start text-start ps-4 scale-text-gray_06">휴대전화</td>
                <td class="text-start text-start p-4">
                <div class="h-center justify-content-between gap-2">
                    <div data-student-phone data-text="" data-ori="student_phone"
                    class="row align-items-center scale-text-gray_05 is_content">
                        {{ $student->student_phone }}
                    </div>

                    {{--
                        <input data-modify-editor="student_phone" hidden
                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3 col" value="{{ $student->student_phone }}">
                        <button type="button" onclick="teachStDtailModifyToggle(this)" hidden data-modify
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 col-auto">연락처 수정</button>
                    --}}
                </div>
                </td>
            </tr>

            <tr class="text-start">
                <td class="text-start ps-4 scale-text-gray_06">학교/학년</td>
                <td class="text-start px-4">
                    <div class="h-center justify-content-between gap-2">
                        <div data-ori="school_name"
                        class="row mx-0 select-wrap py-0 w-100 position-relative">
                            {{ $student->school_name }} / {{ $student->grade_name }}
                        </div>
                        <input data-modify-editor="school_name" hidden
                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3 col" value=" {{ $student->school_name }}">
                        <select data-modify-editor="student_grade" hidden
                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3 col">
                            @if(!empty($grade_codes))
                                @foreach($grade_codes as $grade_code)
                                    <option value="{{ $grade_code->id }}" {{ $grade_code->code_name == $student->grade_name ? 'selected':'' }}>
                                        {{ $grade_code->code_name}}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        <button type="button" onclick="teachStDtailModifyToggle(this)" hidden data-modify
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 col-auto">수정하기</button>
                    </div>
                </td>
                <td class="text-start ps-4 scale-text-gray_06">포인트</td>
                <td class="text-start px-4">
                    <div class="h-center justify-content-between gap-2">
                        <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                            {{ $student->point_now }}
                        </div>
                        <button type="button" onclick="userlistSelPointManage()" hidden data-modify
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 col-auto">포인트 관리</button>
                    </div>
                </td>
            </tr>

            <tr class="text-start">
                <td class="text-start ps-4 scale-text-gray_06">이용권</td>
                <td class="text-start px-4" colspan="3">
                    <div class="h-center justify-content-between gap-2">
                        <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                            @if( !empty($student->goods_name) && strlen($student->goods_name) > 0 )
                            <span class="text-dark">
                                <span  data-goods-name>{{ $student->goods_name }}</span> (<span  data-goods-period>{{ $student->goods_period}}</span><span >개월</span>)
                            </span>
                            -

                            <span data-goods-start-date> {{ date('Y.m.d', strtotime($student->goods_start_date)) }} </span>
                            -
                            <span data-goods-end-date> {{ date('Y.m.d', strtotime($student->goods_end_date)) }} </span>
                            @endif
                        </div>
                        <button type="button" onclick="teachStDetailGoodsStopModalOpen();" hidden data-modify
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 col-auto">이용권 정지</button>
                        <button type="button" onclick="teachStDetailGoodsPlusModalOpen();" hidden data-modify
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 col-auto">이용권 연장</button>
                    </div>
                </td>
            </tr>

             <tr class="text-start">
                {{-- 자택주소 --}}
                <td class="text-start text-start ps-4 scale-text-gray_06">자택 주소</td>
                <td  data-text=""
                    class="text-start text-start p-4 scale-text-gray_05 is_content" colspan="3">
                    <div class="h-center justify-content-between gap-2">
                        <div data-student-address data-ori="student_address"
                        class="h-center" >
                            {{ $student->student_address }}
                        </div>
                        <input type="text" data-user-address data-modify-editor="student_address" onclick="execDaumPostcode(1);"  hidden
                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" placeholder="변경할 주소를 입력해주세요." value="{{ $student->student_address }}">
                        <div class="col-auto">
                            <button type="button" onclick="teachStDtailModifyToggle(this, 1);" hidden data-modify
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">주소 검색</button>
                        </div>
                    </div>
                    <div id="teach_st_af_detail_div_address_wrap1" style="border:1px solid;width:100%;height:300px;margin:5px 0;position:relative" hidden>
                        <img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode()" alt="접기 버튼">
                    </div>
                </td>
             </tr>

             <tr class="text-start scale-bg-gray_01">
                 <td class="text-start ps-4 scale-text-gray_06">관리 선생님 정보</td>
                 <td class="text-start px-4">
                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                        {{ $student->teach_name }}({{ $student->teach_id }})
                    </div>
                </td>

                 <td class="text-start ps-4 scale-text-gray_06">선생님 휴대전화</td>
                 <td class="text-start p-4">
                     <div class="h-center justify-content-between gap-2">
                         {{ $student->teach_phone }}
                    </div>
                </td>
             </tr>
        </tbody>
    </table>

    <div data-explain="48px">
        <div class="py-lg-5"></div>
    </div>

    <div class="text-b-28px mb-4">학부모 정보</div>
    <div style="border-top: solid 2px #222;" class="w-100"></div>
    <table data-tb="0" class="w-100 table-list-style table-border-xless table-h-92" style="border-top:0px;">
        <colgroup>
            <col style="width: 15%;">
            <col style="width: 35%;">
            <col style="width: 15%;">
            <col style="width: 35%;">
        </colgroup>
        <tbody>
            <tr class="text-start ">
                <td class="text-start ps-4 scale-text-gray_06">이름</td>
                <td class="text-start px-4 py-4">
                    <div data-user-name data-text=""
                        class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3 is_content">
                        {{ $parent ? $parent->parent_name : ''}}
                    </div>
                </td>

                <td class="text-start ps-4 scale-text-gray_06 ">회원구분</td>
                <td class="text-start px-4 ">
                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                        학부모
                    </div>
                </td>

            </tr>
            <tr>
                <td class="text-start ps-4 scale-text-gray_06 ">아이디</td>
                <td class="text-start px-4 ">
                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                        {{ $parent ? $parent->parent_id : ''}}
                    </div>
                </td>
                <!-- 비밀번호  -->
                <td class="text-start ps-4 scale-text-gray_06">비밀번호</td>
                <td class="text-start p-4 ">
                <div class="h-center justify-content-between gap-2">
                    <div  data-text="" data-ori="student_pw"
                    class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3">
                        ********
                    </div>
                </div>
                </td>

            </tr>

            <tr>
                {{-- 학부모 이메일 --}}
                <td class="text-start ps-4 scale-text-gray_06">이메일</td>
                <td class="text-start px-4 py-4">
                    <div class="d-flex justify-content-between">
                        <div data-student-id data-text=""
                        class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3">
                            {{ $parent ? $parent->parent_email: ''}}
                        </div>
                    </div>
                </td>
                {{-- 학부모 연락처(1) --}}
                <td class=" text-start ps-4 scale-text-gray_06">학부모 연락처(1)</td>
                <td class=" text-start px-4 py-4">
                    <div class="d-flex justify-content-between">
                        <div data-text=""
                        class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3">
                            <span data-parent-phone> {{ $parent ? $parent->parent_phone : ''}} </span>
                            <span class="text-sb-24px text-primary" {{ !empty($parent) && $parent->is_auth_phone == 'Y' ? '':'hidden' }} >(인증됨)</span>
                        </div>
                    </div>
                </td>
            </tr>

            <!-- 자택주소 -->
            <tr>
                {{-- 자택주소 --}}
                <td class="text-start text-start ps-4 scale-text-gray_06">자택 주소</td>
                <td  data-text=""
                    class="text-start text-start p-4 scale-text-gray_05 is_content" colspan="3">
                    <div class="h-center justify-content-between gap-2">
                        <div data-parent-address data-ori="parent_address"
                        class="h-center" >
                            {{ $parent->parent_address }}
                        </div>
                        <input type="text" data-user-address data-modify-editor="parent_address" onclick="execDaumPostcode(2);"  hidden
                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" placeholder="변경할 주소를 입력해주세요." value="{{ $parent->parent_address }}">
                        <div class="col-auto">
                            <button type="button" onclick="teachStDtailModifyToggle(this, 2);" hidden data-modify
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">주소 검색</button>
                        </div>
                    </div>
                    <div id="teach_st_af_detail_div_address_wrap2" style="border:1px solid;width:100%;height:300px;margin:5px 0;position:relative" hidden>
                        <img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode()" alt="접기 버튼">
                    </div>
                </td>
            </tr>

       </tbody>
    </table>


    @if(!empty($children) && count($children) > 0)
    <div data-explain="48px">
        <div class="py-lg-5"></div>
    </div>
        @foreach($children as $key => $child)
        <div class="text-b-28px mb-4">자녀정보({{ $key }})</div>
        <div style="border-top: solid 2px #222;" class="w-100"></div>
        <table data-tb="0" class="w-100 table-list-style table-border-xless table-h-92" style="border-top:0px;">
            <colgroup>
                <col style="width: 15%;">
                <col style="width: 35%;">
                <col style="width: 15%;">
                <col style="width: 35%;">
            </colgroup>
            <tbody>
            <tr>
                <td class="text-start ps-4 scale-text-gray_06">이름</td>
                <td class="text-start px-4 py-4">
                    <div data-text=""
                        class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3 is_content">
                        {{ $child->student_name }}
                    </div>
                </td>
                <!-- 학교/학년  -->
                <td class="text-start ps-4 scale-text-gray_06">학교/학년</td>
                <td class="text-start px-4">
                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                        {{ $child->school_name }} / {{ $child->grade_name }}
                    </div>
                </td>
            </tr>
            <tr>
                <!-- 휴대폰 번호  -->
                <td class="text-start ps-4 scale-text-gray_06">휴대폰 번호</td>
                <td class="text-start px-4 py-4">
                    <div class="d-flex justify-content-between">
                        <div data-text="" class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3">
                            {{ $child->student_phone }}
                        </div>
                    </div>
                </td>

                <!-- 이용권 -->
                <td class="text-start ps-4 scale-text-gray_06">이용권</td>
                <td class="text-start px-4 py-4">
                    <div class="d-flex justify-content-between">
                        <div data-text="" class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3">
                            {{ $child->goods_name }} - {{ $child->goods_start_date }} ~ {{ $child->goods_end_date }}
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="all-center mt-52 mb-80">
            <button type="button" onclick="teachStDetailUserEditPage('{{ $child->id }}')" style="width:170px"
            class="btn-line-ms-secondary text-sb-20px rounded-pill border-dark scale-bg-white text-dark border w-center">학생 정보관리</button>
        </div>
        @endforeach
    @endif


    <div data-explain="32px">
        <div class="py-lg-3"></div>
    </div>


    <div class="w-center gap-4 mt-52">
        <button type="button" onclick="teachStDtailGoBackList();" style="width:170px"
        class="btn-line-ms-secondary text-sb-20px rounded-pill border-gray scale-bg-white scale-text-gray_05 border w-center">뒤로 가기</button>

        <button data-btn-modify type="button" onclick="teachStDtailModify(true);" style="width:170px"
        class="btn-line-ms-secondary text-sb-20px rounded-pill bg-primary-y text-white border w-center">수정하기</button>

        <button data-btn-modify-cancel type="button" onclick="teachStDtailModify(false);" style="width:170px" hidden
        class="btn-line-ms-secondary text-sb-20px rounded-pill border-gray scale-bg-white scale-text-gray_05 border w-center">수정취소</button>

        <button data-btn-save type="button" onclick="teachStDtailSave();" style="width:170px"  hidden
        class="btn-line-ms-secondary text-sb-20px rounded-pill bg-primary-y text-white border w-center">저장하기</button>
    </div>

    {{-- 160px --}}
    <div>
        <div class="py-lg-5"></div>
        <div class="py-lg-4"></div>
        <div class="pt-lg-3"></div>
    </div>

    {{-- 모달 / 비밀번호 수정 --}}
    <div class="modal fade" id="teach_st_af_detail_modal_update_pw" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">비밀번호 수정</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick=""></button>
                </div>
                <div class="modal-body">
                    <div class="row mx-0">
                        <div class="py-2">
                            <input type="password" class="form-control text-r-t20px" placeholder="변경 비밀번호를 입력해주세요."
                            data-inp-new-pw>
                        </div>
                        <div class="py-2">
                            <input type="password" class="form-control text-r-t20px" placeholder="확인 비밀번호를 입력해주세요."
                            data-inp-new-pw-chk>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light btn-lg col py-3 ctext-gc1 btn_close"
                    data-bs-dismiss="modal" aria-label="Close"
                    onclick="">
                        <span class="ps-2 fs-4">닫기</span>
                    </button>
                    <button class="btn btn-primary-y btn-lg col py-3" onclick="teachStDtailUpdatePw();">
                        <span class="ps-2 fs-4">저장하기</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- 모달 / 휴대폰 인증 --}}
    <div class="modal fade" id="teach_st_af_detail_modal_phone_auth" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog  modal-dialog-centered modal-680">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">휴대폰 인증</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick=""></button>
                </div>
                <div class="modal-body border-0">
                    {{-- 학생 메일  --}}
                    <div class="py-2">
                        <span class="text-r-24px">휴대폰 번호</span>
                    </div>
                    <div>
                        <input type="text" class="form-control fs-5" data-auth-phone
                        placeholder="전화번호가 없습니다 먼저 입력해주세요." style="height: 60px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light btn-lg col py-3 ctext-gc1 btn_close"
                    data-bs-dismiss="modal" aria-label="Close"
                    onclick="">
                        <span class="ps-2 fs-4">닫기</span>
                    </button>
                    <button class="btn btn-primary-y btn-lg col py-3" onclick="teachStDtailPhoneAuth();">
                        <span class="ps-2 fs-4">인증하기</span>
                    </button>
                </div>
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

@include('utils.modal_goods_stop')
@include('utils.modal_goods_plus')
@include('utils.modal_edit_history')

{{-- 학생 상세 정보 이동.  --}}
<form action="/teacher/student/detail" method="post" data-form-student-info-detail hidden>
    @csrf
    <input type="hidden" name="student_seq">
</form>
<script>
   // 뒤로가기
    function teachStDtailBack(){
        sessionStorage.setItem('isBackNavigation', 'true');
        window.history.back();
    }
    // 학생정보 > 초기화 및 임시 비밀번호 생성
    function teachStDtailInitPw() {
        teachStDtailModalModalEdit('pw');
    }

    // 학생정보 > 주소 수정하기
    function teachStDtailEditAddress() {
        teachStDtailModalModalEdit('address');
    }

    // 학부모 연락처 본인인증
    function teachStDtailParentPhoneAuth() {
        const parent_id = document.querySelector("[data-main-parent-seq]").value;
        if(!parent_id){
            toast('학부모 정보가 없습니다.');
            return;
        }
        teachStDtailModalPhoneAuth();
    }
    // 목록으로 가기
    function teachInfoBack() {
        teachStDtailBack();
    }

    // 저장하기
    function teachStDtailGoBackList() {
        window.location.href = '/teacher/student/after';
    }

    // 타입별 수정.
    function teachStDtailModalModalEdit(type){
        //초기화
        switch(type){
           case 'pw':
               /* 비밀번호 input 초기화 */
               const inp_pw1 = document.querySelector("[data-inp-new-pw]");
               const inp_pw2 = document.querySelector("[data-inp-new-pw-chk]");
               inp_pw1.value = '';
               break;
            case 'address':
                /* 변경 주소 input 초기화 */
                const inp_address = document.querySelector("[data-user-address]");
                inp_address.value = '';
            break;
            case 'mail':break;
        }
        const myModal = new bootstrap.Modal(document.getElementById('teach_st_af_detail_modal_update_'+type), {});
        myModal.show();
    }

    //모달에서 비밀번호 수정 저장 버튼 클릭
    async function teachStDtailUpdatePw(){
        const user_pw_tag = document.querySelector("[data-inp-current-pw]");
        const user_new_pw = document.querySelector("[data-inp-new-pw]").value;
        const user_new_pw_chk = document.querySelector("[data-inp-new-pw-chk]").value;

        // 비밀번호 확인
        if(user_new_pw != user_new_pw_chk){
            toast('변경 비밀번호가 일치하지 않습니다. 다시 확인해주세요.');
            return;
        }

        // 비밀번호 변경
        const student_seq = document.querySelector("[data-main-student-seq]").value;
        const parameter = {
            user_type: 'student',
            user_seq: student_seq,
            user_pw: user_new_pw,
            is_pw: 'true',
        };
        const result = await teachStDtailUpdate(parameter);
        if((result.resultCode||'') == 'success'){
            toast('비밀번호가 변경되었습니다.');
        }else{
            toast('비밀번호 변경에 실패하였습니다. 다시 시도해주세요.');
        }

        // 모달 닫기 버튼 클릭
        const modal = document.getElementById('teach_st_af_detail_modal_update_pw');
        const btn_close = modal.querySelector('.btn_close');
        teachStDtailUpdate('pw')
        btn_close.click();
    }

    async function teachStDtailUpdate(parameter){
        const page = '/student/member/info/update';
        /*
        const parameter = {
            user_type: user_type,
            user_seq: user_seq,
            user_pw: user_pw,
            user_current_pw:user_current_pw,
            user_phone: user_phone,
            user_address: user_address,
            user_mail: user_mail,
            is_pw: is_pw,
            is_phone: is_phone,
            is_address: is_address,
            is_mail: is_mail
        };
        */
        return new Promise((resolve, reject) => {
            queryFetch(page, parameter, function(result){
                resolve(result);
            });
        });
    }

    //우편번호찾기. 숨기기 / 가져오기
    let element_wrap = null;
    function foldDaumPostcode(type) {
    // iframe을 넣은 element를 안보이게 한다.
        element_wrap.hidden = true;
        document.querySelector("[data-modify-editor='student_address']").focus();
    }
    //모달에서 주소 수정 저장 버튼 클릭 // 다음 주소 검색
    function execDaumPostcode(num) {
    // 현재 scroll 위치를 저장해놓는다.
        const element_wrap = document.getElementById('teach_st_af_detail_div_address_wrap'+num);
        let id_address = '';
        if(num == 1){ id_address = "[data-modify-editor='student_address']"}
        else if(num == 2){ id_address = "[data-modify-editor='parent_address']"}

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
                    // 법정동명이 있을 경우 추가한다. (법정리는 제외))
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
                    document.querySelector(id_address).value = extraAddr;
                } else {
                    document.querySelector(id_address).value = '';
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                const zip_code = data.zonecode;
                const address = addr;
                document.querySelector(id_address).value = addr;
               document.querySelector(id_address).focus();

                // 커서를 상세주소 필드로 이동한다.
                // basic_info.querySelector(".address_detail").focus();

                // iframe을 넣은 element를 안보이게 한다.
                // (autoClose:false 기능을 이용한다면, 아래 코드를 제거해야 화면에서 사라지지 않는다.)
                element_wrap.hidden = true;

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
        element_wrap.hidden = false;
    }

    // 주소 적용
    async function teachStDtailUpdateAddress(){
        const main_modal = document.getElementById('teach_st_af_detail_modal_update_address');
        const user_address = main_modal.querySelector("[data-user-address]").value;

        // table tag 쪽 주소 변경
        /* const update_div_address = document.querySelector("[data-student-address]"); */
        /* if(update_div_address.getAttribute('current_address') == null) */
        /*     update_div_address.setAttribute('current_address', update_div_address.innerText); */
        /* update_div_address.innerText = user_address; */

        // 주소 저장 및 닫기.
        const student_seq = document.querySelector("[data-main-student-seq]").value;
        const address = user_address;
        const parameter = {
            user_type: 'student',
            user_seq: student_seq,
            user_address: address,
            is_address: 'true',
        };
        const result = await teachStDtailUpdate(parameter);
        if((result.resultCode||'') == 'success'){
            toast('주소가 변경되었습니다.');
           const student_address_el = document.querySelector("[data-student-address]");
              student_address_el.innerText = address;
        }else{
            toast('주소 변경에 실패하였습니다. 다시 시도해주세요.');
        }
        // 모달닫기
        const modal = document.getElementById('teach_st_af_detail_modal_update_address');
        const btn_close = modal.querySelector('.btn_close');
        btn_close.click();
    }

    // 휴대폰 인증 모달 오픈
    function teachStDtailModalPhoneAuth(){
        // data-auth-mail
        const user_phone = document.querySelector("[data-parent-phone]").innerText;

        // 휴대폰 번호가 없을경우
        /* if(user_phone == ''){ */
        /*     toast('휴대폰 번호가 없습니다.'); */
        /*     return; */
        /* } */
        document.querySelector("[data-auth-phone]").value = user_phone;

       const myModal = new bootstrap.Modal(document.getElementById('teach_st_af_detail_modal_phone_auth'), {
            // keyboard: false
        });
        myModal.show();
    }

    // 휴대폰 인증하기.
    function teachStDtailPhoneAuth(){
        const modal = document.querySelector('#teach_st_af_detail_modal_phone_auth');
        //전역
        const user_seq = document.querySelector("[data-main-parent-seq]").value;
        const user_type = 'parent';
        const user_phone = modal.querySelector("[data-auth-phone]").value;
        //모달

        const page = '/phone/auth/check/number';
        const parameter = {
            user_seq: user_seq,
            user_type: user_type,
            user_phone: user_phone,
            is_chk_pass: 'Y',
        };
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                toast('휴대폰이 인증되었습니다.');
                document.querySelector("[data-parent-phone]").nextElementSibling.hidden = false;

                // 모달 닫기 버튼 클릭
                const btn_close = modal.querySelector('.btn_close');
                btn_close.click();
            }
            else{
                toast('휴대폰 인증에 실패하였습니다. 다시 시도해주세요.');
            }
        });
    }


    // 수정하기 버튼 클릭.
    function  teachStDtailModify(is_modify){
        const btn_modify = document.querySelector('[data-btn-modify]');
        const btn_save = document.querySelector('[data-btn-save]');
        const data_modifys = document.querySelectorAll('[data-modify]');
        const data_oris = document.querySelectorAll('[data-ori]');

        if(is_modify){
            btn_modify.hidden = true;
            btn_save.hidden = false;
            data_modifys.forEach(function(item){
                item.hidden = false;
            });
            //data_oris.forEach(function(item){
            //    item.hidden = true;
            //});
            // 스크롤 최상단으로 부드럽게 이동
            window.scrollTo({top: 100, behavior: 'smooth'});
            document.querySelector('[data-btn-modify-cancel]').hidden = false;
        }
        else{
            const data_modify_editor = document.querySelectorAll('[data-modify-editor]');
            btn_modify.hidden = false;
            btn_save.hidden = true;
            data_modifys.forEach(function(item){
                item.hidden = true;
            });
            data_oris.forEach(function(item){
               item.hidden = false;
            });
            data_modify_editor.forEach(function(item){
                item.hidden = true;
            });
            document.querySelector('[data-btn-modify-cancel]').hidden = true;
            btn_modify.hiddne = false;
        }
    }

    // 변경 버튼 클릭시 수정 모드 토글.
    function teachStDtailModifyToggle(vthis, num){
        element_wrap = document.getElementById('teach_st_af_detail_div_address_wrap'+num);
        const td = vthis.closest('td');
        // td 아래 data-ori 를 찾아서
        //  확인시 숨김처리 되어있으면 보이게 하고 반대면 숨김처리
        const data_ori = td.querySelector('[data-ori]');
        const data_modify_editors = td.querySelectorAll('[data-modify-editor]');

        // 숨김처리 되어있으면 보이게 하고 반대면 숨김처리
        if(data_ori.hidden){
            data_ori.hidden = false;
            data_modify_editors.forEach(function(item){
                item.hidden = true;
            });
        }
        else{
            data_ori.hidden = true;
            data_modify_editors.forEach(function(item){
                item.hidden = false;
            });
        }
    }

    // 저장하기 버튼 클릭
    function teachStDtailSave(){
        const student_seq = document.querySelector("[data-main-student-seq]").value;
        const parent_seq = document.querySelector("[data-main-parent-seq]").value;

        //학생 정보
        const student_name_el = document.querySelector("[data-modify-editor='student_name']");
        const student_grade_el = document.querySelector("[data-modify-editor='student_grade']");
        const student_school_name_el = document.querySelector("[data-modify-editor='school_name']");
        const student_address_el = document.querySelector("[data-modify-editor='student_address']");

        //학부모 정보
        const parent_address_el = document.querySelector("[data-modify-editor='parent_address']");

        // 위 태그들이 모두 hidden 아니면 수정하는 것으로 판단
        const student_name = student_name_el.hidden != true ? student_name_el.value : '';
        const student_grade = student_grade_el.hidden != true ? student_grade_el.value : '';
        const student_school_name = student_school_name_el.hidden != true ? student_school_name_el.value : '';
        const student_address = student_address_el.hidden != true ? student_address_el.value : '';

        const parent_address = parent_address_el.hidden != true ? parent_address_el.value : '';

        const page = '/teacher/student/after/detail/update';
        const parameter = {
            student_seq: student_seq,
            student_name: student_name,
            student_grade: student_grade,
            school_name: student_school_name,
            student_address: student_address,
            parent_seq: parent_seq,
            parent_address: parent_address
        };

        const msg =
        `
            <div class="text-center">
                <div class="text-b-24px">저장하시겠습니까?</div>
            </div>
        `;
        sAlert('', msg, 3, function(){
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    const msg =
                    `
                        <div class="text-center">
                            <div class="text-b-24px">저장되었습니다.</div>
                        </div>
                    `;
                   sAlert('', msg, 4, function(){
                        // teachStDtailBack();
                        //or reload
                    });

                }else{
                    toast('저장에 실패하였습니다. 다시 시도해주세요.');
                }
            });
        });
    }


    // 포인트 관리
    function teachStDetailPointManage(){

    }

    // 학생정보 상세보기
    function teachStDetailUserEditPage(student_seq){
        //form 을 만들어서 teach_seq 넣고 submit
        const form = document.querySelector('[data-form-student-info-detail]');
        form.querySelector('[name="student_seq"]').value = student_seq;
        form.method = 'post';
        form.target = '_self';
        form.submit();
    }

    // 선택 회원 포인트 관리
    function userlistSelPointManage(){
        //모달 div 변수 생성
        //선택 회원중에 학생만 선택되어있는지 확인
        //학생만 선택이 되어 있지 않으면 학생만 선택.
        const modal = document.querySelector('#userlist_modal_point_manage');
        const sel_tr = document.querySelectorAll('.tr_userinfo');


        //지정한 포인트가져오기
        const add_point = 0;
        modal.querySelector('#userlist_inp_point').value = add_point;

        //1명인지 확인
        // 내역 보여주기
        modal.querySelector('.div_point_history').hidden = false;
        // 모달 가로사이즈 조정
        modal.style.setProperty('--bs-modal-width', '800px');

        //포인트 히스토리 내역 가져오기.
        userlistPointHistorySelect();

        const myModal = new bootstrap.Modal(document.getElementById('userlist_modal_point_manage'), {});
        myModal.show();
    }

        // 포인트 히스토리 내역 가져오기.
        function userlistPointHistorySelect(){
            const modal = document.querySelector('#userlist_modal_point_manage');
            const user_key =  document.querySelector('[data-main-student-seq]').value;            //포인트 히스토리 내역 가져오기.

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
            let user_keys = document.querySelector('[data-main-student-seq]').value;

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

                    const page_num = document.querySelector('[data-page="1"] .active').innerText;
                    const point_now = result.point_now[0];
                    document.querySelector('[data-student-point-now]').innerText = point_now;

                    // 모달 닫기
                    document.querySelector('#userlist_modal_point_manage .btn-close').click();
                }else{
                    sAlert('', '저장에 실패하였습니다.');
                }
            });
        }


// 이용권 정지 모달 열기.
    function teachStDetailGoodsStopModalOpen(){
       const goods_period_el = document.querySelector('[data-goods-period]');
       if(goods_period_el == null){
          toast('이용권 정보가 없습니다.');
            return;
         }

       const student_seq = document.querySelector('[data-main-student-seq]').value;
       const student_name = document.querySelector('[data-ori="student_name"]').innerText;
       const region_name = document.querySelector('[data-main-region-name]').value;
       const team_name = document.querySelector('[data-main-team-name]').value;
       const goods_period = goods_period_el.value;

       const data = [
           {
               student_seq:student_seq,
               student_name:student_name,
               region_name:region_name,
               team_name:team_name,
               goods_period:goods_period,
           },
       ]

       utilsModalStopGoodsStopModal(data);
   }

   // 이용권 연장 모달 열기.
   function teachStDetailGoodsPlusModalOpen() {
       const goods_period_el = document.querySelector('[data-goods-period]');
       if(goods_period_el == null){
          toast('이용권 정보가 없습니다.');
            return;
         }

       const student_seq = document.querySelector('[data-main-student-seq]').value;
       const student_name = document.querySelector('[data-ori="student_name"]').innerText;
       const region_name = document.querySelector('[data-main-region-name]').value;
       const team_name = document.querySelector('[data-main-team-name]').value;
       const goods_period = goods_period_el.value;

       const data = [
           {
               student_seq:student_seq,
               student_name:student_name,
               region_name:region_name,
               team_name:team_name,
               goods_period:goods_period,
               day_sum:1,
           },
       ]

       utilsModalPlusGoodsPluspModal(data);
   }

   // 변경 내역 이력 열기.
   function teachStDetailChangeLogShow(){
       const student_seq = document.querySelector('[data-main-student-seq]').value;

       const data = {
           "user_type":"student",
           "user_key":student_seq
       }
       utilsModalEtHisUserHistoryModal(data);
   }

</script>

@endsection


