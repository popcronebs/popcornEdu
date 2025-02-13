@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '마이페이지')
@section('add_css_js')
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
@endsection

<!-- TODO: 개인 정보 수집 동의 부분이 명확하지 않아 우선 UI만 만들어 두었으므로, 추후 명확해지면 이부분도 DB 연동. -->
@section('layout_coutent')
<style>
/* 두번째 td의 글자색은 검은색. */
table tr td:nth-child(2){
    color: #000;
}

</style>
<input type="hidden" data-main-teach-seq value="{{$teacher->id}}">
<input type="hidden" data-main-teach-name value="{{$teacher->teach_name}}">

<div class="position-relative zoom_sm">
    <div class="sub-title d-flex justify-content-between">
        <h2 class="text-sb-42px h-center">
            <img src="{{ asset('images/graphic_memberinfo_icon.svg') }}" width="76">
            <span class="me-2">마이페이지</span>
        </h2>
    </div>


    <p class="text-b-28px mb-4">회원정보 수정</p>
    <table class="w-100 table-list-style table-border-xless table-h-92">
        <colgroup>
            <col style="width: 25%;">
            <col style="width: 75%;">
        </colgroup>
        <thead></thead>
        <tbody>
            <tr class="text-start">
                <td class="text-start ps-4 text-b-24px border-end">아이디</td>
                <td class="text-start px-4 text-black">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-b-24px">
                            {{ session()->get('teach_id') }}
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="text-start">
                <td class="text-start ps-4 text-b-24px border-end">비밀번호</td>
                <td class="text-start px-4">
                    <div data-target="password1" class="w-auto">
                        <div data-div-unedit>
                            <div class="d-flex justify-content-between">
                                <div class="d-flex align-items-center">
                                    <span class="text-b-24px" data-sp-unedit="password1">
                                        ********
                                    </span>
                                </div>
                                <div>
                                    <button onclick="teachMemberEdit('password1')"
                                        type="button" class="btn-line-xss-secondary text-sb-20px border-gray scale-bg-white scale-text-black rounded p-3">수정하기</button>
                                </div>
                            </div>
                        </div>
                        <div data-div-edit hidden>
                            <div class="d-flex justify-content-between">
                                <div class="w-75 gap-2 d-flex">
                                    <input data-inp-edit="password1" class="form-control w-50" style="max-width: 500px;" placeholder="변경할 비밀번호를 입력해주세요." type="password">
                                </div>
                                <div class="d-flex gap-2">
                                    <button onclick="teachMemberPasswordEdit();"
                                        type="button" class="btn-line-xss-secondary text-sb-20px border-gray scale-bg-white scale-text-black rounded p-3 px-4 col-auto">확인</button>
                                    <button onclick="teachMemberEdit('password1')"
                                        type="button" class="btn-line-xss-secondary text-sb-20px border-gray scale-bg-white scale-text-gray_05 rounded p-3 px-4 col-auto">취소</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="text-start">
                <td class="text-start ps-4 text-b-24px border-end">비밀번호 확인</td>
                <td colspan="3" class="text-start px-4">
                    <div data-target="password2" class="w-auto">
                        <div data-div-unedit>
                            <div class="d-flex justify-content-between">
                                <div>
                                    <span class="text-b-24px" data-sp-unedit="password2">
                                        ********
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div data-div-edit hidden>
                            <div class="d-flex justify-content-between">
                                <div class="w-75 gap-2 d-flex">
                                    <input data-inp-edit="password2" class="form-control w-50" id="" style="max-width: 500px;" placeholder="비밀번호를 재확인해주세요." type="password">
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="text-start">
                <td class="text-start ps-4 text-b-24px border-end">회원유형</td>
                <td colspan="3" class="text-start px-4">
                    <p class="text-b-24px">
                        {{ session()->get('group_name') }}
                    </p>
                </td>
            </tr>
            <tr class="text-start">
                <td class="text-start ps-4 text-b-24px border-end">이름</td>
                <td colspan="3" class="text-start px-4">
                    <p class="text-b-24px">
                        {{ session()->get('teach_name') }}
                    </p>
                </td>
            </tr>
            <tr class="text-start d-none">
                <td class="text-start ps-4 text-b-24px">주민등록번호</td>
                <td colspan="3" class="text-start px-4">
                    <p class="text-b-24px">
                        {{ $teacher->rrn }}
                    </p>
                </td>
            </tr>
            <tr class="text-start">
                <td class="text-start ps-4 text-b-24px">휴대전화</td>
                <td colspan="3" class="text-start px-4">
                    <div data-target="phone" class="w-auto">
                        <div data-div-unedit>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-b-24px" data-sp-unedit="phone">
                                        {{ preg_replace("/([0-9]{3})([0-9]{3,4})([0-9]{4})/", "$1-$2-$3", $teacher->teach_phone) }}
                                    </span>
                                    <span data-cert="phone" {{  $teacher->is_auth_phone == 'Y' ? '':'hidden'  }}
                                        class="rounded-4 primary-bg-bg p-2 px-3 text-sb-20px" style="color:#F4B20F">인증</span>
                                </div>
                                <div>
                                    <button
                                        onclick="teachMemberEdit('phone')"
                                        type="button"
                                        class="btn-line-xss-secondary text-sb-20px border-gray scale-bg-white scale-text-black rounded p-3">
                                        수정하기
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div data-div-edit hidden>
                            <div class="d-flex justify-content-between">
                                <div class="w-75 gap-2 d-flex">
                                    <input data-inp-edit="phone" class="form-control w-auto" id="" placeholder="변경할 휴대전화를 입력해주세요.">
                                    <button onclick="ptMemberModalPhoneAuth();" class="btn btn-primary-y">인증요청</button>
                                </div>
                                <div class="d-flex gap-2">
                                    <button onclick="teachMemberPhoneEdit();" type="button" class="btn-line-xss-secondary text-sb-20px border border-secondary scale-bg-white scale-text-black rounded p-3 px-4 col-auto">확인</button>
                                    <button onclick="teachMemberEdit('phone')"
                                        type="button" class="btn-line-xss-secondary text-sb-20px border-gray scale-bg-white scale-text-gray_05 rounded p-3 px-4 col-auto">취소</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="text-start">
                <td class="text-start ps-4 text-b-24px">이메일 주소</td>
                <td colspan="3" class="text-start px-4">
                    <div data-target="email" class="w-auto">
                        <div data-div-unedit>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-b-24px" data-sp-unedit="email">
                                        {{ $teacher->teach_email }}
                                    </span>
                                    <span data-cert="email"  {{ $teacher->is_auth_email == 'Y' ? '':'hidden' }}
                                        class="rounded-4 primary-bg-bg p-2 px-3 text-sb-20px" style="color:#F4B20F">인증</span>
                                </div>
                                <div>
                                    <button onclick="teachMemberEdit('email')"
                                        type="button" class="btn-line-xss-secondary text-sb-20px border-gray scale-bg-white scale-text-black rounded p-3">수정하기</button>
                                </div>
                            </div>
                        </div>
                        <div data-div-edit hidden>
                            <div class="d-flex justify-content-between">
                                <div class="w-75 gap-2 d-flex">
                                    <input data-inp-edit="email" class="form-control w-auto" id="" placeholder="변경할 이메일을 입력해주세요.">
                                    <button onclick="ptMemberModalMailAuth()" class="btn btn-primary-y">인증요청</button>
                                </div>
                                <div class="d-flex gap-2">
                                    <button onclick="teachMemberEmailEdit()"
                                        type="button" class="btn-line-xss-secondary text-sb-20px border-gray scale-bg-white scale-text-black rounded p-3 px-4 col-auto">확인</button>
                                    <button onclick="teachMemberEdit('email')"
                                        type="button" class="btn-line-xss-secondary text-sb-20px border-gray scale-bg-white scale-text-gray_05 rounded p-3 px-4 col-auto">취소</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            <tr class="text-start">
                <td class="text-start ps-4 text-b-24px">자택 주소</td>
                <td colspan="3" class="text-start px-4">
                    <div data-target="address" class="w-auto">
                        <div data-div-unedit>
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-b-24px" data-sp-unedit="address">
                                        {{ $teacher->teach_address }}
                                    </span>
                                </div>
                                <div>
                                    <button onclick="teachMemberEdit('address');execDaumPostcode();"
                                        type="button" class="btn-line-xss-secondary text-sb-20px border-gray scale-bg-white scale-text-black rounded p-3">수정하기</button>
                                </div>
                            </div>
                        </div>
                        <div data-div-edit hidden>
                            <div class="d-flex justify-content-between">
                                <div class="w-75 gap-2 d-flex">
                                    <input data-inp-edit="address" class="form-control w-50" id="" placeholder="변경할 주소를 입력해주세요.">
                                    <button class="btn btn-primary-y">주소검색</button>
                                </div>
                                <div class="d-flex gap-2">
                                    <button onclick="teachMemberAddressEdit();"
                                        type="button" class="btn-line-xss-secondary text-sb-20px border-gray scale-bg-white scale-text-black rounded p-3 px-4 col-auto">확인</button>
                                    <button onclick="teachMemberEdit('address');addressCancel();"
                                        type="button" class="btn-line-xss-secondary text-sb-20px border-gray scale-bg-white scale-text-gray_05 rounded p-3 px-4 col-auto">취소</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="teach_st_af_detail_div_address_wrap" style="border:1px solid;width:100%;height:300px;margin:5px 0;position:relative" hidden>
                        <img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode()" alt="접기 버튼">
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="scale-bg-gray_01 rounded px-52 py-32 d-flex flex-column row-gap-3 text-sb-24px mt-52 mb-52 d-none">
        <label class="checkbox d-flex align-items-center">
            <input type="checkbox" class="" disabled>
            <span class=""></span>
            <p class="ms-2">개인정보 수집 및 이용 동의여부</p>
            <p class="ms-1 scale-text-gray_05">(동의일자 : {{ \Carbon\Carbon::parse($teacher->created_at)->format('y.m.d') }})</p>
        </label>
        <label class="checkbox d-flex align-items-center">
            <input type="checkbox" class="" disabled>
            <span class=""></span>
            <p class="ms-2">이용약관 동의여부</p>
            <p class="ms-1 scale-text-gray_05">(동의일자 : {{ \Carbon\Carbon::parse($teacher->created_at)->format('y.m.d') }})</p>
        </label>
        <label class="checkbox d-flex align-items-center">
            <input type="checkbox" class="" disabled>
            <span class=""></span>
            <p class="ms-2">제3자 정보제공 동의여부</p>
            <p class="ms-1 scale-text-gray_05">(동의일자 : {{ \Carbon\Carbon::parse($teacher->created_at)->format('y.m.d') }})</p>
        </label>
        <label class="checkbox d-flex align-items-center">
            <input type="checkbox" class="" disabled>
            <span class=""></span>
            <p class="ms-2">마케팅 등 수집이용 동의여부</p>
            <p class="ms-1 scale-text-gray_05">(동의일자 : {{ \Carbon\Carbon::parse($teacher->created_at)->format('y.m.d') }})</p>
        </label>
    </div>

    <p class="text-b-28px mt-80 mb-4 d-none">계약정보</p>
    <table class="w-100 table-list-style table-border-xless table-h-92 d-none">
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
                <td class="text-start ps-4 ">계약채결일자</td>
                <td class="text-start px-4 scale-text-black">
                    {{ \Carbon\Carbon::parse($teacher->created_at)->format('Y.m.d') }}
                </td>
            </tr>
            <tr class="text-start">
                <td class="text-start ps-4 ">계약만료일자</td>
                <td class="text-start px-4 scale-text-black">
                    {{ \Carbon\Carbon::parse($teacher->created_at)->addYear()->format('Y.m.d') }}
                </td>
            </tr>
            <tr class="text-start">
                <td class="text-start ps-4 ">약정형태</td>
                <td class="text-start px-4 scale-text-gray_05">
                    비대면 전자계약
                </td>
            </tr>
            <tr class="text-start">
                <td class="text-start ps-4 ">약정형태</td>
                <td class="text-start px-4 py-4">
                    <ul>
                        <li class="scale-text-gray_05">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.1484 21.0787H9.49476C6.61996 21.0787 4.2915 18.8056 4.2915 15.9993C4.2915 13.1929 6.61996 10.9199 9.49476 10.9199H12.1484" stroke="#999999" stroke-width="3" stroke-miterlimit="10"/>
                                <path d="M19.9529 10.9199H22.5025C25.3773 10.9199 27.7058 13.1929 27.7058 15.9993C27.7058 18.8056 25.3773 21.0787 22.5025 21.0787H19.9009" stroke="#999999" stroke-width="3" stroke-miterlimit="10"/>
                                <path d="M10.8486 15.999H21.2551" stroke="#999999" stroke-width="3" stroke-miterlimit="10"/>
                            </svg>
                            주민등록증 사본(김선생) 1부
                        </li>
                        <li class="scale-text-gray_05">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.1484 21.0787H9.49476C6.61996 21.0787 4.2915 18.8056 4.2915 15.9993C4.2915 13.1929 6.61996 10.9199 9.49476 10.9199H12.1484" stroke="#999999" stroke-width="3" stroke-miterlimit="10"/>
                                <path d="M19.9529 10.9199H22.5025C25.3773 10.9199 27.7058 13.1929 27.7058 15.9993C27.7058 18.8056 25.3773 21.0787 22.5025 21.0787H19.9009" stroke="#999999" stroke-width="3" stroke-miterlimit="10"/>
                                <path d="M10.8486 15.999H21.2551" stroke="#999999" stroke-width="3" stroke-miterlimit="10"/>
                            </svg>
                            통장사본 (김선생 토스뱅크) 1부
                        </li>
                        <li class="scale-text-gray_05">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.1484 21.0787H9.49476C6.61996 21.0787 4.2915 18.8056 4.2915 15.9993C4.2915 13.1929 6.61996 10.9199 9.49476 10.9199H12.1484" stroke="#999999" stroke-width="3" stroke-miterlimit="10"/>
                                <path d="M19.9529 10.9199H22.5025C25.3773 10.9199 27.7058 13.1929 27.7058 15.9993C27.7058 18.8056 25.3773 21.0787 22.5025 21.0787H19.9009" stroke="#999999" stroke-width="3" stroke-miterlimit="10"/>
                                <path d="M10.8486 15.999H21.2551" stroke="#999999" stroke-width="3" stroke-miterlimit="10"/>
                            </svg>
                            범죄경력조회 회보서 (김선생 경찰청) 1부
                        </li>
                        <li class="scale-text-gray_05">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12.1484 21.0787H9.49476C6.61996 21.0787 4.2915 18.8056 4.2915 15.9993C4.2915 13.1929 6.61996 10.9199 9.49476 10.9199H12.1484" stroke="#999999" stroke-width="3" stroke-miterlimit="10"/>
                                <path d="M19.9529 10.9199H22.5025C25.3773 10.9199 27.7058 13.1929 27.7058 15.9993C27.7058 18.8056 25.3773 21.0787 22.5025 21.0787H19.9009" stroke="#999999" stroke-width="3" stroke-miterlimit="10"/>
                                <path d="M10.8486 15.999H21.2551" stroke="#999999" stroke-width="3" stroke-miterlimit="10"/>
                            </svg>
                            전자계약서(약관+수수료동의)
                        </li>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="zoom_sm">
    <div class="py-lg-5"></div>
    <div class="py-lg-4"></div>
    <div class="pt-lg-3"></div>
</div>

{{-- 모달 / 이메일 인증 --}}
<div class="modal fade zoom_sm" id="pt_member_modal_mail_auth" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog  modal-dialog-centered modal-680">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">메일인증</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick=""></button>
            </div>
            <div class="modal-body border-0">
                {{-- 학생 메일  --}}
                <div class="py-2">
                    <span class="text-r-24px">메일 주소</span>
                </div>
                <div class="row m-0 pb-3">
                    <span class="col text-r-24px" data-auth-mail></span>
                    <button class="col-auto btn btn-primary-y btn-lg col py-2" onclick="ptMemberSendMailAuth();">
                        <span class="ps-2 fs-5">인증번호 전송</span>
                    </button>
                </div>
                <div>
                    <input type="text" class="form-control fs-5" data-auth
                    placeholder="인증번호를 입력해주세요." style="height: 60px;">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light btn-lg col py-3 ctext-gc1 btn_close"
                data-bs-dismiss="modal" aria-label="Close"
                onclick="">
                    <span class="ps-2 fs-4">닫기</span>
                </button>
                <button class="btn btn-primary-y btn-lg col py-3" onclick="ptMemberMailAuth();">
                    <span class="ps-2 fs-4">인증하기</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- 모달 / 휴대폰 인증 --}}
<div class="modal fade zoom_sm" id="pt_member_modal_phone_auth" tabindex="-1" aria-hidden="true" style="display: none;">
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
                <div class="row m-0 pb-3">
                    <span class="col text-r-24px" data-auth-phone></span>
                    <button class="col-auto btn btn-primary-y btn-lg col py-2" onclick="ptMemberSendPhoneAuth();">
                        <div class="spinner-border spinner-border-sm" role="status" data-phone-spinner hidden></div>
                        <span class="ps-2 fs-5">인증번호 전송</span>
                    </button>
                </div>
                <div>
                    <input type="text" class="form-control fs-5" data-auth
                    placeholder="인증번호를 입력해주세요." style="height: 60px;">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light btn-lg col py-3 ctext-gc1 btn_close"
                data-bs-dismiss="modal" aria-label="Close"
                onclick="">
                    <span class="ps-2 fs-4">닫기</span>
                </button>
                <button class="btn btn-primary-y btn-lg col py-3" onclick="ptMemberPhoneAuth();">
                    <span class="ps-2 fs-4">인증하기</span>
                </button>
            </div>
        </div>
    </div>
</div>
<script>
function addressCancel(){
    const daumLayer = document.querySelector('[id*="__daum__layer_"]');
    if(daumLayer) {
        document.getElementById('teach_st_af_detail_div_address_wrap').style = '';
        daumLayer.remove();
    }
}
// 수정하기
function teachMemberEdit(type){
    // el가 있는지 확인.
    const target_el = document.querySelector('[data-target="'+type+'"]');
    if(target_el == null){
        return;
    }
    // password1 일때 2도 같이 해준다.
    if(type == 'password1'){
        teachMemberEdit('password2');
    }

    if(target_el.querySelector('[data-div-unedit]').hidden){
        target_el.querySelector('[data-div-unedit]').hidden = false;
        target_el.querySelector('[data-div-edit]').hidden = true;
    }else{
        target_el.querySelector('[data-div-unedit]').hidden = true;
        target_el.querySelector('[data-div-edit]').hidden = false;

        if(type.indexOf('password') != -1){
            return;
        }
        const ori_value = document.querySelector('[data-sp-unedit="'+type+'"]').innerText.trim();
        document.querySelector('[data-inp-edit="'+type+'"]').value = ori_value;
    }


}

// 암호 수정하기
function teachMemberPasswordEdit(){
    const password1 =  document.querySelector('[data-inp-edit="password1"]').value;
    const password2 =  document.querySelector('[data-inp-edit="password2"]').value;
    if(password1 != password2){
        toast('비밀번호가 일치하지 않습니다.');
        return;
    }
    const msg = '<div class="text-sb-24px">비밀번호를 변경하시겠습니까?</div>';
    sAlert('', msg, 3, function(){
        teachMemberInfoUpdate('user_pw', password1, function(){
            teachMemberEdit('password1');
        });
    });
}

// 전화번호 수정하기.
function teachMemberPhoneEdit(){
    const phone =  document.querySelector('[data-inp-edit="phone"]').value.trim().replace(/-/g, '');
    if(phone.length < 9){
        toast('전화번호를 정확히 입력해주세요.');
        return;
    }
    const msg = '<div class="text-sb-24px">전화번호를 변경하시겠습니까?</div>';
    sAlert('', msg, 3, function(){
        teachMemberInfoUpdate('user_phone', phone, function(){
            document.querySelector('[data-sp-unedit="phone"]').innerText = phone;
            document.querySelector('[data-cert="phone"]').hidden = true;
            teachMemberEdit('phone');
        });
    });
}

// 이메일 주소 수정하기.
function teachMemberEmailEdit(){
    const email =  document.querySelector('[data-inp-edit="email"]').value.trim();
    if(email.length < 5){
        toast('이메일 주소를 정확히 입력해주세요.');
        return;
    }
    const msg = '<div class="text-sb-24px">이메일 주소를 변경하시겠습니까?</div>';
    sAlert('', msg, 3, function(){
        teachMemberInfoUpdate('user_mail', email, function(){
            document.querySelector('[data-sp-unedit="email"]').innerText = email;
            document.querySelector('[data-cert="email"]').hidden = true;
            teachMemberEdit('email');
        });
    });
}

// 주소 수정하기.
function teachMemberAddressEdit(){
    const address =  document.querySelector('[data-inp-edit="address"]').value.trim();
    if(address.length < 5){
        toast('주소를 정확히 입력해주세요.');
        return;
    }
    const msg = '<div class="text-sb-24px">주소를 변경하시겠습니까?</div>';
    sAlert('', msg, 3, function(){
        teachMemberInfoUpdate('user_address', address, function(){
            document.querySelector('[data-sp-unedit="address"]').innerText = address;
            teachMemberEdit('address');addressCancel();
        });
    });
}

// 유저정보 업데이트.
function teachMemberInfoUpdate(type, value, callback){
    const teach_seq = document.querySelector('[data-main-teach-seq]').value;
    const page = "/teacher/member/info/update";
    const parameter = {
        user_seq:teach_seq,
        user_type:'teacher'
    };
    if(type){
        parameter[type] = value;
    }
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
           toast('수정되었습니다.');
            if(callback != undefined){
                callback();
            }
        }else{}
    });
}

//우편번호찾기. 숨기기 / 가져오기
let element_wrap = null;
function foldDaumPostcode() {
// iframe을 넣은 element를 안보이게 한다.
    element_wrap.hidden = true;
    // document.querySelector("[data-modify-editor='student_address']").focus();
}
//모달에서 주소 수정 저장 버튼 클릭 // 다음 주소 검색
function execDaumPostcode() {
// 현재 scroll 위치를 저장해놓는다.
    const element_wrap = document.getElementById('teach_st_af_detail_div_address_wrap');
    let id_address = '[data-inp-edit="address"]';

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


// 이메일 인증 모달 오픈
var user_email = null;
var user_email_type = '';
var user_email_seq = '';
var user_email_name = '';

function ptMemberModalMailAuth(vthis){
    // data-auth-mail
    const teach_seq = document.querySelector('[data-main-teach-seq]').value;
    user_email_type = 'teacher';
    user_email_seq = teach_seq;
    user_email_name = document.querySelector('[data-main-teach-name]').value;

     user_email = document.querySelector('[data-sp-unedit="email"]').innerText.trim();
    document.querySelector("[data-auth-mail]").innerText = user_email;

    const myModal = new bootstrap.Modal(document.getElementById('pt_member_modal_mail_auth'), {
        // keyboard: false
    });
    myModal.show();
}

// 이메일 인증번호 전송.
function ptMemberSendMailAuth(){
    // 데이터
    // const user_email = user_email_el.innerText;
    const user_type = user_email_type;
    const user_seq = user_email_seq;
    const user_name = user_email_name;

    // 전송
    const page = '/mail/auth/send/number';
    const parameter = {
        user_email: user_email,
        user_seq: user_seq,
        user_type: user_type,
        user_name: user_name
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            toast('인증번호가 전송되었습니다. 인증번호는 3분동안 유효합니다.');
        }
        else{
            toast('인증번호 전송에 실패하였습니다. 다시 시도해주세요. 유효한 메일인지 확인해주세요.');
        }
    });
}

// 이메일 인증
function ptMemberMailAuth(){
    const modal = document.getElementById('pt_member_modal_mail_auth');
    const user_seq = user_email_seq;
    const user_type = user_email_type;
    // const user_email = user_email_el.innerText.trim();
    const user_auth = modal.querySelector("[data-auth]").value.trim();

    const page = '/mail/auth/check/number';
    const parameter = {
        user_seq: user_seq,
        user_type: user_type,
        user_email: user_email,
        user_auth: user_auth,
    };
    queryFetch(page, parameter, function(result){
        if((result.resultCode||'') == 'success'){
            toast('메일이 인증되었습니다.');
            modal.querySelector("[data-auth]").value = '';
            document.querySelector('[data-cert="email"]').hidden = false;
            teachMemberEdit('email');
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

// 휴대폰 인증 모달 오픈
var user_phone = '';
var user_phone_type = '';
var user_phone_seq = '';
var user_phone_name = '';
function ptMemberModalPhoneAuth(vthis){
    // data-auth-mail
    user_phone_type = 'teacher';
    user_phone_seq = document.querySelector('[data-main-teach-seq]').value;
    user_phone_name = document.querySelector('[data-main-teach-name]').value;

    user_phone = document.querySelector('[data-sp-unedit="phone"]').innerText.trim();

    // 휴대폰 번호가 없을경우
    if(user_phone == ''){
        toast('휴대폰 번호가 없습니다.');
        return;
    }
    document.querySelector("[data-auth-phone]").innerText = user_phone;

    const myModal = new bootstrap.Modal(document.getElementById('pt_member_modal_phone_auth'), {
        // keyboard: false
    });
    myModal.show();
}

// 휴대폰 인증번호 전송.
function ptMemberSendPhoneAuth(){
    // 데이터
    // const user_phone = user_phone_el.innerText;
    const user_type = user_phone_type;
    const user_seq = user_phone_seq;
    const user_name = user_phone_name;

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
        }
        //already
        else if((result.resultCode||'') == 'already'){
            toast('이미 전송을 진행했습니다. 3분이 지난후 다시 전송해주세요.');
        }else{
            toast('인증번호 전송에 실패하였습니다. 다시 시도해주세요. 유효한 휴대폰 번호인지 확인해주세요.');
        }
    });
}

// 휴대폰 인증
function ptMemberPhoneAuth(){
    const modal = document.querySelector('#pt_member_modal_phone_auth');
    //전역
    const user_seq = user_phone_seq;
    const user_type = user_phone_type;
    // const user_phone = user_phone_el.innerText;
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
            document.querySelector('[data-cert="phone"]').hidden = false;
            teachMemberEdit('phone');

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
</script>

@endsection
