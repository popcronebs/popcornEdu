@extends('layout.layout')

@section('head_title')
학생정보수정
@endsection

@section('add_css_js')
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
@endsection

{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
    <div class="row pt-2" data-div-main="user_add">
        <input type="hidden" data-main-student-seq value="{{ $student->id }}">
        <input type="hidden" data-main-parent-seq value="{{ $parent ? $parent->id:'' }}">
        <input type="hidden" data-main-class-seq value="{{ $student->class_seq }}">
        <div class="sub-title d-flex justify-content-between">
          <h2 class="text-sb-42px">
            <button data-btn-back-page="" class="btn p-0 row mx-0 all-center" onclick="teachStAfDtailBack();">
                <img src="{{ asset('images/black_arrow_left_tail.svg') }}" width="52" class="px-0">
            </button>
            <span class="me-2">{{ $student->student_name }} 학생</span>
            <span class="ht-make-title on text-r-20px py-1 px-3 ms-1 h-42 d-flex align-items-center">
                @if(!empty($student))
                    @if($student->team_type == 'after_school')
                        {{ $student->team_name }}
                    @else
                        {{ $student->school_name }}
                    @endif
                @endif
                /{{ $student->grade_name }}</span>
          </h2>
      </div>
    <div class="text-b-28px mb-4 ">
        <span> 학생 정보 </span>
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
            <tr class="text-start scale-bg-gray_01">
                {{-- <td class="text-start ps-4 scale-text-gray_06"> 회원 구분 </td> --}}
                {{-- <td class="text-start px-4 py-4"> --}}
                {{--     <div data-user-name data-text="" --}}
                {{--         class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3 is_content"> --}}
                {{--         {{-1- border -1-}} --}}
                {{--         {{ $team_type == 'after_school' ? '방과후 학생':'학원 학생' }} --}}
                {{--     </div> --}}
                {{-- </td> --}}


                <td class="text-start ps-4 scale-text-gray_06">학교</td>
                <td class="text-start px-4">
                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                        @if(!empty($student))
                            @if($student->team_type == 'after_school')
                                {{ $student->team_name }}
                            @else
                                {{ $student->school_name }}
                            @endif
                        @endif
                    </div>
                </td>
                <td class="text-start ps-4 scale-text-gray_06 scale-bg-gray_01">아이디</td>
                <td class="text-start px-4 scale-bg-gray_01">
                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative" data-main-student-id>
                    {{ $student->student_id }}
                    </div>
                </td>
            </tr>
            <tr class="text-start">
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
                        <button type="button" onclick="teachStAfDtailModifyToggle(this);" hidden data-modify
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3"> 이름 변경 </button>
                    </div>
                </div>
                </td>
                <td class="text-start ps-4 scale-text-gray_06">학년 / 반</td>
                <td class="text-start px-4">
                    <div class="h-center justify-content-between gap-2">
                        <div data-ori="student_grade"
                        class="row select-wrap py-0 w-100 position-relative">
                            {{ $student->grade_name }} / {{ $student->class_name }}
                        </div>
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
                        <input data-modify-editor="class_name" hidden
                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3 col" value=" {{ $student->class_name }}">
                        <button type="button" onclick="teachStAfDtailModifyToggle(this)" hidden data-modify
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 col-auto"> 학년/반 변경 </button>
                    </div>
                </td>
            </tr>
            <tr class="text-start">
                <td class="text-start ps-4 scale-text-gray_06">비밀번호</td>
                <td class="text-start p-4 ">
                <div class="h-center justify-content-between gap-2">
                    <div data-user-id data-text="" data-ori="student_pw"
                    class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3">
                        {{-- 비밀번호 숫자만큼 *넣기 --}}
                        ********
                    </div>
                    <input data-modify-editor="student_pw" hidden
                    class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" value="">
                    <div class="col-auto">
                        <button type="button" onclick="teachStAfDtailModifyToggle(this);" style="width:270px" hidden data-modify
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">초기화 및 임시 비밀번호 생성</button>
                    </div>
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
                    <input data-modify-editor="student_phone" hidden
                    class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3 col" value="{{ $student->student_phone }}">
                    <button type="button" onclick="teachStAfDtailModifyToggle(this)" hidden data-modify
                    class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 col-auto">연락처 수정</button>
                </div>
                </td>
            </tr>
            <tr class="text-start">
                <td class="text-start text-start ps-4 scale-text-gray_06">방과후 클래스</td>
                <td class="text-start text-start p-3 px-4">
                    <div class="h-center justify-content-between">
                        <div data-user-phone data-text="" data-ori="student_phone"
                        class="row align-items-center scale-text-gray_05 is_content col">
                            @if (!empty($after_classes))
                            {{ $after_classes }}
                            @endif
                        </div>
                        <div class="row select-wrap select-icon w-100 col" data-modify-editor hidden>
                            <select data-class-seq data-modify-editor="class_seq" hidden
                            class="search_type border-gray lg-select text-sb-20px w-100 h-62 border-none">
                                @if (!empty($classes))
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}" {{ $class->id == $student->class_seq ? 'selected':'' }}>
                                {{ $class->class_name }}
                                </option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                        <button type="button" onclick="teachStAfDtailModifyToggle(this)" hidden data-modify
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 col-auto">클래스 변경</button>
                    </div>

                </td>
               {{--  이메일 --}}
               <td class="text-start text-start ps-4 scale-text-gray_06">이메일</td>
                <td class="text-start text-start p-4" colspan="3">
                    <div class="h-center justify-content-between gap-2">
                        <div data-student-email data-text="" data-ori="student_email"
                        class="d-flex align-items-center scale-text-gray_05 is_content">
                            {{ $student->student_email }}
                        </div>
                        <input data-modify-editor="student_email" hidden
                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3 col" value="{{ $student->student_email }}">
                        <button type="button" onclick="teachStAfDtailModifyToggle(this)" hidden data-modify
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 col-auto">이메일 수정</button>
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
                        <input type="text" data-user-address data-modify-editor="student_address" onclick="execDaumPostcode();"  hidden
                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" placeholder="변경할 주소를 입력해주세요." value="{{ $student->student_address }}">
                        <div class="col-auto">
                            <button type="button" onclick="teachStAfDtailModifyToggle(this);" hidden data-modify
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">주소 검색</button>
                        </div>
                    </div>
                    <div id="teach_st_af_detail_div_address_wrap" style="border:1px solid;width:100%;height:300px;margin:5px 0;position:relative" hidden>
                        <img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode()" alt="접기 버튼">
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
            <tr class="text-start scale-bg-gray_01">
                <td class="text-start ps-4 scale-text-gray_06">학부모 성명</td>
                <td class="text-start px-4 py-4">
                    <div data-user-name data-text=""
                        class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3 is_content">
                        {{ $parent ? $parent->parent_name : ''}}
                    </div>
                </td>
                <td class="text-start ps-4 scale-text-gray_06 scale-bg-gray_01">학부모 아이디</td>
                <td class="text-start px-4 scale-bg-gray_01">
                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative" data-main-parent-id>
                        {{ $parent ? $parent->parent_id : ''}}
                    </div>
                </td>
            </tr>
            <tr>
            {{-- 학부모 연락처(1) --}}
                <td class="scale-bg-gray_01 text-start ps-4 scale-text-gray_06">학부모 연락처(1)</td>
                <td class="scale-bg-gray_01 text-start px-4 py-4">
                    <div class="d-flex justify-content-between">
                        <div data-text=""
                        class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3">
                            <span data-parent-phone> {{ $parent ? $parent->parent_phone : ''}} </span>
                            <span class="text-sb-24px text-primary" {{ !empty($parent) && $parent->is_auth_phone == 'Y' ? '':'hidden' }} >(인증됨)</span>
                        </div>
                        <div>
                            <button type="button" onclick="teachStAfDtailParentPhoneAuth()" style="width:110px"
                            {{ !empty($parent) && $parent->is_auth_phone == 'Y' ? 'hidden':'' }}
                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 all-center">본인인증</button>
                        </div>
                    </div>
                {{-- 학부모 연락처(2) --}}
                <td class="text-start ps-4 scale-text-gray_06">학부모 연락처(2)</td>
                <td class="text-start px-4 py-4">
                    <div class="h-center justify-content-between gap-2">
                        <div data-parent-phone2 data-text="" data-ori="parent_phone2"
                        class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3">
                        {{ $parent ? $parent->parent_phone2 : ''}}
                        </div>
                        <input data-modify-editor="parent_phone2" hidden
                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" value="{{ $parent ? $parent->parent_phone2 : ''}}">
                        <button type="button" onclick="teachStAfDtailModifyToggle(this)" hidden data-modify
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 col-auto">연락처 수정</button>
                    </div>
                </td>
            </tr>
            <tr class="scale-bg-gray_01">
                {{-- 학부모 이메일 --}}
                <td class="text-start ps-4 scale-text-gray_06">학부모 이메일</td>
                <td class="text-start px-4 py-4" colspan="3">
                    <div class="d-flex justify-content-between">
                        <div data-student-id data-text=""
                        class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3">
                            {{ $parent ? $parent->parent_email: ''}}
                        </div>
                    </div>
                </td>
            </tr>
       </tbody>
    </table>

    <div data-explain="32px">
        <div class="py-lg-3"></div>
    </div>

    <div data-explain="개인정보 수집 및 동의여부" hidden
    class="scale-bg-gray_01 rounded px-52 py-32 d-flex flex-column row-gap-3 text-sb-24px mt-52 mb-52" style="display: none !important;">
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

    <div class="w-center gap-4">
        <button type="button" onclick="teachStAfDtailGoBackList();" style="width:170px"
        class="btn-line-ms-secondary text-sb-20px rounded-pill border-gray scale-bg-white scale-text-gray_05 border w-center">목록으로 가기</button>

        <button data-btn-modify type="button" onclick="teachStAfDtailModify(true);" style="width:170px"
        class="btn-line-ms-secondary text-sb-20px rounded-pill bg-primary-y text-white border w-center">수정하기</button>
        <button data-btn-save type="button" onclick="teachStAfDtailSave();" style="width:170px"  hidden
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
                    <button class="btn btn-primary-y btn-lg col py-3" onclick="teachStAfDtailUpdatePw();">
                        <span class="ps-2 fs-4">저장하기</span>
                    </button>
                </div>
            </div>
        </div>
    </div>


    {{-- 모달 / 주소 수정 --}}
    <div class="modal fade" id="teach_st_af_detail_modal_update_address" tabindex="-1" aria-hidden="true" style="display: none;">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">주소 수정</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick=""></button>
                </div>
                <div class="modal-body">
                    <div>
                    </div>
                    <button class="btn text-sb-20px btn-primary-y"
                        onclick="">
                        주소 검색
                    </button>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-light btn-lg col py-3 ctext-gc1 btn_close"
                    data-bs-dismiss="modal" aria-label="Close"
                    onclick="">
                        <span class="ps-2 fs-4">닫기</span>
                    </button>
                    <button class="btn btn-primary-y btn-lg col py-3" onclick="teachStAfDtailUpdateAddress();">
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
                    <button class="btn btn-primary-y btn-lg col py-3" onclick="teachStAfDtailPhoneAuth();">
                        <span class="ps-2 fs-4">인증하기</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
   // 뒤로가기
    function teachStAfDtailBack(){
        sessionStorage.setItem('isBackNavigation', 'true');
        window.history.back();
    }
    // 학생정보 > 초기화 및 임시 비밀번호 생성
    function teachStAfDtailInitPw() {
        teachStAfDtailModalModalEdit('pw');
    }

    // 학생정보 > 주소 수정하기
    function teachStAfDtailEditAddress() {
        teachStAfDtailModalModalEdit('address');
    }

    // 학부모 연락처 본인인증
    function teachStAfDtailParentPhoneAuth() {
        const parent_id = document.querySelector("[data-main-parent-seq]").value;
        if(!parent_id){
            toast('학부모 정보가 없습니다.');
            return;
        }
        teachStAfDtailModalPhoneAuth();
    }
    // 목록으로 가기
    function teachInfoBack() {
        teachStAfDtailBack();
    }

    // 저장하기
    function teachStAfDtailGoBackList() {
        window.location.href = '/teacher/student/after';
    }

    // 타입별 수정.
    function teachStAfDtailModalModalEdit(type){
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
    async function teachStAfDtailUpdatePw(){
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
        const result = await teachStAfDtailUpdate(parameter);
        if((result.resultCode||'') == 'success'){
            toast('비밀번호가 변경되었습니다.');
        }else{
            toast('비밀번호 변경에 실패하였습니다. 다시 시도해주세요.');
        }

        // 모달 닫기 버튼 클릭
        const modal = document.getElementById('teach_st_af_detail_modal_update_pw');
        const btn_close = modal.querySelector('.btn_close');
        teachStAfDtailUpdate('pw')
        btn_close.click();
    }

    async function teachStAfDtailUpdate(parameter){
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
    const element_wrap = document.getElementById('teach_st_af_detail_div_address_wrap');
    function foldDaumPostcode() {
    // iframe을 넣은 element를 안보이게 한다.
        element_wrap.hidden = true;
        document.querySelector("[data-modify-editor='student_address']").focus();

    }
    //모달에서 주소 수정 저장 버튼 클릭 // 다음 주소 검색
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
                    document.querySelector("[data-modify-editor='student_address']").value = extraAddr;
                } else {
                    document.querySelector("[data-modify-editor='student_address']").value = '';
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                const zip_code = data.zonecode;
                const address = addr;
                document.querySelector("[data-modify-editor='student_address']").value = addr;
               document.querySelector("[data-modify-editor='student_address']").focus();

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
    async function teachStAfDtailUpdateAddress(){
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
        const result = await teachStAfDtailUpdate(parameter);
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
    function teachStAfDtailModalPhoneAuth(){
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
    function teachStAfDtailPhoneAuth(){
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
    function  teachStAfDtailModify(is_modify){
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
            // 스크롤 최상단으로 부드럽게 이동.
            window.scrollTo({top: 100, behavior: 'smooth'});
        }
        else{
            btn_modify.hidden = false;
            btn_save.hidden = true;
            data_modifys.forEach(function(item){
                item.hidden = true;
            });
            //data_oris.forEach(function(item){
            //    item.hidden = false;
            //});
        }
    }

    // 변경 버튼 클릭시 수정 모드 토글.
    function teachStAfDtailModifyToggle(vthis){
        const td = vthis.closest('td');
        // td 아래 data-ori 를 찾아서
        //  확인시 숨김처리 되어있으면 보이게 하고 반대면 숨김처리
        const data_ori = td.querySelector('[data-ori]');
        const data_modify_editors = td.querySelectorAll('[data-modify-editor]');
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
    function teachStAfDtailSave(){
       const student_id = document.querySelector("[data-main-student-id]").innerText.trim();
        const student_seq = document.querySelector("[data-main-student-seq]").value;
       const parent_id = document.querySelector("[data-main-parent-id]").innerText.trim();
        const parent_seq = document.querySelector("[data-main-parent-seq]").value;

        //학생 정보
        const student_name_el = document.querySelector("[data-modify-editor='student_name']");
        const student_grade_el = document.querySelector("[data-modify-editor='student_grade']");
        const student_class_name_el = document.querySelector("[data-modify-editor='class_name']");
        const student_pw_el = document.querySelector("[data-modify-editor='student_pw']");
        const student_phone_el = document.querySelector("[data-modify-editor='student_phone']");
        const class_seq_el = document.querySelector("[data-modify-editor='class_seq']");
        const student_email_el = document.querySelector("[data-modify-editor='student_email']");
        const student_address_el = document.querySelector("[data-modify-editor='student_address']");

        const prev_class_seq = document.querySelector("[data-main-class-seq]").value;

        //학부모 정보
        const parent_phone2_el = document.querySelector("[data-modify-editor='parent_phone2']");

        // 위 태그들이 모두 hidden 아니면 수정하는 것으로 판단
        const student_name = student_name_el.hidden != true ? student_name_el.value : '';
        const student_grade = student_grade_el.hidden != true ? student_grade_el.value : '';
        const student_class_name = student_class_name_el.hidden != true ? student_class_name_el.value : '';
        const student_pw = student_pw_el.hidden != true ? student_pw_el.value : '';
        const student_phone = student_phone_el.hidden != true ? student_phone_el.value : '';
        const class_seq = class_seq_el.hidden != true ? class_seq_el.value : '';
        const student_email = student_email_el.hidden != true ? student_email_el.value : '';
        const student_address = student_address_el.hidden != true ? student_address_el.value : '';

        const parent_phone2 = parent_phone2_el.hidden != true ? parent_phone2_el.value : '';

        const page = '/teacher/student/after/detail/update';
        const parameter = {
           student_id: student_id,
            student_seq: student_seq,
            student_name: student_name,
            student_grade: student_grade,
            st_class_name: student_class_name,
            student_pw: student_pw,
            student_phone: student_phone,
            class_seq: class_seq,
            student_email: student_email,
            student_address: student_address,
           parent_id: parent_id,
            parent_seq: parent_seq,
            parent_phone2: parent_phone2,
            prev_class_seq:prev_class_seq,
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
                   setTimeout(function(){
                       sAlert('', msg, 4, function(){
                            teachStAfDtailBack();
                        });
                   }, 200)

                }else{
                    toast('저장에 실패하였습니다. 다시 시도해주세요.');
                }
            });
        });
    }
</script>

@endsection


