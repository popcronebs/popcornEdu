@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '회원정보수정')

@section('add_css_js')
    <script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
@endsection
{{-- 학부모 회원정보수정  --}}
<!-- TODO: 이용권 변경문의 / 어떻게 진행하는지에 대해서 확인후 기능 추가. -->
<!-- TODO: 이용권 재등록 / 어떻게 진행하는지에 대해서 확인후 기능 추가. -->
<!-- TODO: 이용권 만료 임박 기능 추가 -->

@section('layout_coutent')
<style>
aside .active svg path{
    fill:white;
}
.primary-bg-mian-hover.active{
    background-color: #FFBD19 !important;
    color: white !important;
}
</style>

<div class="col pe-3 ps-3 mb-3 row position-relative">
    <div class="sub-title row mx-0 justify-content-between" data-board-main>
        <h2 class="text-sb-42px px-0">
            <img src="{{ asset('images/graphic_memberinfo_icon.svg')}}" width="72">
            <span class="me-2">회원정보 수정</span>
        </h2>
    </div>

    <div class="row mx-0">
        <aside class="col-3">
            <div class="rounded-4 modal-shadow-style">
                <ul class="tab py-4 px-3 ">
                    <li class="mb-2">
                        <button onclick="ptMemberAsideTab(this)" data-pt-member-tab="1"
                            class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover {{$type == 'child' ? '':'active'}}">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_880_80320)">
                                    <path d="M16.0026 16.0026C18.9493 16.0026 21.3359 13.6159 21.3359 10.6693C21.3359 7.7226 18.9493 5.33594 16.0026 5.33594C13.0559 5.33594 10.6693 7.7226 10.6693 10.6693C10.6693 13.6159 13.0559 16.0026 16.0026 16.0026ZM16.0026 18.6693C12.4426 18.6693 5.33594 20.4559 5.33594 24.0026V26.6693H26.6693V24.0026C26.6693 20.4559 19.5626 18.6693 16.0026 18.6693Z" fill="#FFC747"/>
                                </g>
                                <defs>
                                    <clipPath id="clip0_880_80320">
                                        <rect width="32" height="32" fill="white"/>
                                    </clipPath>
                                </defs>
                            </svg>
                            학부모 정보 관리
                        </button>
                    </li>
                    <li class="">
                        <button onclick="ptMemberAsideTab(this)" data-pt-member-tab="2"
                            class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover {{$type == 'child' ? 'active':''}}">

                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_880_80320)">
                                    <path d="M16.0026 16.0026C18.9493 16.0026 21.3359 13.6159 21.3359 10.6693C21.3359 7.7226 18.9493 5.33594 16.0026 5.33594C13.0559 5.33594 10.6693 7.7226 10.6693 10.6693C10.6693 13.6159 13.0559 16.0026 16.0026 16.0026ZM16.0026 18.6693C12.4426 18.6693 5.33594 20.4559 5.33594 24.0026V26.6693H26.6693V24.0026C26.6693 20.4559 19.5626 18.6693 16.0026 18.6693Z" fill="#FFC747"/>
                                </g>
                                <defs>
                                    <clipPath id="clip0_880_80320">
                                        <rect width="32" height="32" fill="white"/>
                                    </clipPath>
                                </defs>
                            </svg>
                            자녀 정보 관리
                        </button>
                    </li>
                </ul>
            </div>
        </aside>

        <div class="col">
            <section data-pt-member-section="1" {{ $type == 'child' ? 'hidden':''}}>
                <div style="border-top: solid 2px #222;" class="w-100"></div>
                <table data-tb="0" data-user-type="parent" data-user-seq="{{ $parent ? $parent->id : '' }}" data-user-name="{{ $parent ? $parent->parent_name : ''}}"
                class="w-100 table-list-style table-border-xless table-h-92" style="border-top:0px;">
                    <colgroup>
                        <col style="width: 15%;">
                        <col style="width: 35%;">
                        <col style="width: 15%;">
                        <col style="width: 35%;">
                    </colgroup>
                    <tbody>
                        <tr class="text-start ">
                            <td class="text-start ps-4 scale-text-gray_06">아이디</td>
                            <td class="text-start px-4 py-4">
                                <div data-ori="parent_id" data-text=""
                                    class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3 is_content">
                                    {{ $parent ? $parent->parent_id : ''}}
                                </div>
                            </td>

                            <td class="text-start ps-4 scale-text-gray_06 ">성명</td>
                            <td class="text-start px-4 ">
                                <div class="h-center justify-content-between gap-2">
                                    <div data-text=""
                                        class="d-inline-block select-wrap py-0 d-flex position-relative">
                                        <span data-ori="parent_name">
                                            {{ $parent ? $parent->parent_name : ''}}
                                        </span>
                                        <span data-ori="parent_type">/ 모</span>
                                    </div>
                                    <input type="text" data-user-address data-modify-editor="parent_name" onclick=""  hidden
                                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" placeholder="" value="{{ $parent->parent_name }}">
                                    <button type="button" onclick="ptMemberDtailModifyToggle(this);" hidden data-modify
                                        class="col-auto btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">수정하기</button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <!-- 비밀번호  -->
                            <td class="text-start ps-4 scale-text-gray_06">비밀번호</td>
                            <td class="text-start p-4 ">
                                <div class="h-center justify-content-between gap-2">
                                    <div  data-text="" data-ori="parent_pw"
                                        class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3">
                                        ********
                                    </div>
                                </div>
                            </td>
                            <!-- 비밀번호 확인 -->
                            <td class="text-start ps-4 scale-text-gray_06">비밀번호 확인</td>
                            <td class="text-start p-4 ">
                                <div class="h-center justify-content-between gap-2">
                                    <div  data-text="" data-ori="parent_pw2"
                                        class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3">
                                        ********
                                    </div>
                                </div>
                            </td>

                        </tr>
                        <tr>

                            {{-- 학부모 --}}
                            <td class=" text-start ps-4 scale-text-gray_06">휴대전화</td>
                            <td class=" text-start px-4 py-4">
                                <div class="d-flex justify-content-between">
                                    <div data-text=""
                                        class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3">
                                        <span data-parent-phone data-phone> {{ $parent ? $parent->parent_phone : ''}} </span>
                                        <span class="text-sb-24px text-primary" {{ !empty($parent) && $parent->is_auth_phone == 'Y' ? '':'hidden' }} >(인증됨)</span>
                                    </div>
                                    <div>
                                        <button type="button" onclick="ptMemberModalPhoneAuth(this)" style="width:110px"
                                            data-auth-phone-btn="parent"
                                            {{ !empty($parent) && $parent->is_auth_phone == 'Y' ? 'hidden':'' }}
                                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 all-center">본인인증</button>
                                    </div>
                                </div>
                            </td>
                            <td class=" text-start ps-4 scale-text-gray_06">전화번호</td>
                            <td class=" text-start px-4 py-4">
                                <div class="h-center justify-content-between gap-2">
                                    <div data-text=""
                                        class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                                        (자택)
                                        <span data-ori="parent_tel">
                                            {{ $parent ? $parent->parent_tel : '' }}
                                        </span>
                                    </div>
                                    <input type="text" data-user-address data-modify-editor="parent_tel" onclick=""  hidden
                                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" placeholder="" value="{{ $parent->parent_tel }}">
                                    <button type="button" onclick="ptMemberDtailModifyToggle(this);" hidden data-modify
                                        class="col-auto btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">수정하기</button>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            {{-- 학부모 이메일 --}}
                            <td class="text-start ps-4 scale-text-gray_06">이메일</td>
                            <td class="text-start px-4 py-4" colspan="3">
                                <div class="d-flex justify-content-between">
                                    <div  data-text="" class="text-m-24px px-0 scale-text-gray_05 p-2 col-auto rounded-3">
                                        <span data-ori="parent_email" data-email>
                                            {{ $parent ? $parent->parent_email: ''}}
                                        </span>
                                        <span class="text-sb-24px text-danger"  {{ !empty($parent) && $parent->is_auth_email == 'Y' ? '':'hidden' }}>(인증됨)</span>
                                    </div>
                                    <div class="col row justify-content-end">
                                        <input data-modify-editor="parent_email" hidden placeholder="이메일을 입력해주세요."
                                            class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3 col" value="{{ $parent->parent_email }}">
                                        <button type="button" onclick="ptMemberDtailModifyToggle(this)" hidden data-modify
                                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 col-auto ms-2">이메일 수정</button>
                                        <button type="button" onclick="ptMemberModalMailAuth(this);" data-auth-mail-btn="parent"
                                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 col-auto ms-2"
                                            {{ !empty($parent) && $parent->is_auth_email == 'Y' ? 'hidden':'' }}
                                        >인증하기</button>
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
                                    <div  data-ori="parent_address"
                                        class="h-center" >
                                        {{ $parent->parent_address }}
                                    </div>
                                    <input type="text" data-user-address data-modify-editor="parent_address" data-address onclick=""  hidden
                                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" placeholder="변경할 주소를 입력해주세요." value="{{ $parent->parent_address }}">
                                    <div class="col-auto">
                                        <button type="button" onclick="ptMemberDtailModifyToggle(this, 1);" hidden data-modify=""
                                            class="col-auto btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">주소 수정하기</button>
                                    </div>
                                </div>
                                <div  data-daum-address="" style="border:1px solid;width:100%;height:300px;margin:5px 0;position:relative" hidden>
                                    <img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode()" alt="접기 버튼">
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>

                <div class="w-center gap-4 mt-5">
                    <button data-btn-modify type="button" onclick="ptMemberDtailModify(true);" style="width:170px"
                        class="btn-line-ms-secondary text-sb-20px rounded-pill bg-primary-y text-white border w-center">수정하기</button>
                    <button data-btn-modaiy-cancel type="button" onclick="ptMemberDtailModify(false);" style="width:170px"  hidden
                        class="btn-line-ms-secondary text-sb-20px rounded-pill bg-secondary text-white border w-center">수정취소</button>
                    <button data-btn-save type="button" onclick="ptMemberDtailSave();" style="width:170px"  hidden
                        class="btn-line-ms-secondary text-sb-20px rounded-pill bg-primary-y text-white border w-center">저장하기</button>


                </div>
            </section>

            {{-- 자녀 정보 관리 --}}
           <section data-pt-member-section="2" {{ $type == 'child' ? '':'hidden'}}>

                <div style="border-top: solid 2px #222;" class="w-100"></div>
                @if(!empty($students))
                @foreach($students as $idx => $student)
                <table data-tb="{{$idx}}" data-user-type="student" data-user-seq="{{$student? $student->id:''}}" data-user-name="{{$student ? $student->student_name:''}}"
                class="w-100 table-list-style table-border-xless table-h-92 mt-0" style="border-top:0px;">
                    <colgroup>
                        <col style="width: 15%;">
                        <col style="width: 35%;">
                        <col style="width: 15%;">
                        <col style="width: 35%;">
                    </colgroup>
                    <thead style="{{ $idx > 0 ? "border-top: solid 2px #222;":''}}">
                        <tr class="text-start ">
                            <td class="text-start ps-4 scale-text-gray_06">이름</td>
                            <td class="text-start px-4 py-4" colspan="3">
                                <div class="h-center justify-content-between gap-2">
                                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                                        <span data-ori="student_name">
                                             {{-- name, id --}}
                                           {{ $student ? $student->student_name : ''}}({{ $student? $student->student_id : ''}})
                                        </span>
                                    </div>
                                    <div class="col h-center justify-content-end">
                                        <button class="btn p-0 h-center {{$idx > 0 ? 'rotate-180':''}}" onclick="ptMemberChildOpen(this)">
                                            <img src="{{ asset('images/dropdown_arrow_up.svg') }}" alt="">
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </thead>
                    <tbody {{ $idx > 0 ? 'hidden':'' }}>
                        <tr class="text-start " hidden>
                            <td class="text-start ps-4 scale-text-gray_06">학교</td>
                            <td class="text-start px-4 py-4">
                                <div data-ori="student_id" data-text=""
                                    class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3 is_content">
                                    {{ $student ? $student->student_id : ''}}
                                </div>
                            </td>

                            <td class="text-start ps-4 scale-text-gray_06 ">성명</td>
                            <td class="text-start px-4 ">
                                <div class="h-center justify-content-between gap-2">
                                    <div data-text="" data-ori="student_name"
                                        class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                                            {{  $student->student_name }}
                                    </div>
                                    <input type="text" data-user-address data-modify-editor="student_name" onclick=""  hidden
                                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" placeholder="" value="{{ $student->student_name }}">
                                    <button type="button" onclick="ptMemberDtailModifyToggle(this, 2);" hidden data-modify
                                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">수정하기</button>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start ">
                            <td class="text-start ps-4 scale-text-gray_06">학교</td>
                            <td class="text-start px-4 py-4">
                                <div data-ori="school_name" data-text=""
                                    class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3 is_content">
                                    {{ $student ? $student->school_name : ''}}
                                </div>
                            </td>

                            <td class="text-start ps-4 scale-text-gray_06">학년</td>
                            <td class="text-start px-4 py-4">
                                <div data-ori="grade_name" data-text=""
                                    class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3 is_content">
                                    {{ $student ? $student->grade_name : ''}}
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start ">
                            <td class="text-start ps-4 scale-text-gray_06">비밀번호</td>
                            <td class="text-start px-4 py-4">
                                <div data-ori="school_name" data-text=""
                                    class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3 is_content">
                                        ********
                                </div>
                            </td>

                            <td class="text-start ps-4 scale-text-gray_06">비밀번호<br>확인</td>
                            <td class="text-start px-4 py-4">
                                <div data-ori="grade_name" data-text=""
                                    class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3 is_content">
                                        ********
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start " >
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
                                    <button type="button" onclick="ptMemberDetailGoodsStopModalOpen();" data-modify
                                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 col-auto">이용권 변경 문의</button>
                                </div>
                            </td>
                        </tr>
                         {{-- 휴대전화 / 이메일 --}}
                        <tr class="text-start " >
                            <td class="scale-bg-gray_01 text-start ps-4 scale-text-gray_06">휴대전화</td>
                            <td
                                class="text-start text-start p-4 scale-text-gray_05 is_content" colspan="3">
                                <div class="d-flex">
                                    <div data-text=""
                                        class="text-m-24px px-0 scale-text-gray_05 p-2 rounded-3 col-auto">
                                        <span data-student-phone data-phone data-ori="student_phone"> {{ $student ? $student->student_phone : ''}} </span>
                                        <span class="text-sb-24px text-primary" {{ !empty($student) && $student->is_auth_phone == 'Y' ? '':'hidden' }} >(인증됨)</span>
                                    </div>
                                    <div class="row gap-2 col justify-content-end">
                                        <input type="tel" data-modify-editor="student_phone"
                                        data-student-phone-chg value="{{$student ? $student->student_phone : ''}}" hidden class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3 col">
                                        <button type="button" style="width:110px" onclick="ptMemberNewEdit(this);" data-type="student_phone"
                                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 all-center">수정하기</button>
                                        <button type="button" onclick="ptMemberModalPhoneAuth(this)" style="width:110px"
                                            data-auth-phone-btn="student"
                                            {{ !empty($student) && $student->is_auth_phone == 'Y' ? 'hidden':'' }}
                                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 all-center">본인인증</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start " >
                            <td class="scale-bg-gray_01 text-start ps-4 scale-text-gray_06">이메일</td>
                            <td
                                class="text-start text-start p-4 scale-text-gray_05 is_content" colspan="3">
                                <div class="d-flex">
                                    <div data-text=""
                                        class="text-m-24px px-0 scale-text-gray_05 p-2 rounded-3 col-auto">
                                        <span data-student-email data-email data-ori="student_email"> {{ $student ? $student->student_email : ''}} </span>
                                        <span class="text-sb-24px text-primary" {{ !empty($student) && $student->is_auth_email == 'Y' ? '':'hidden' }} >(인증됨)</span>
                                    </div>
                                    <div class="row gap-2 col justify-content-end">
                                        <input type="email" data-modify-editor="student_email"
                                        data-student-email-chg value="{{ $student ? $student->student_email : ''}}" hidden class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3 col">
                                        <button type="button" style="width:110px" onclick="ptMemberNewEdit(this);" data-type="student_email"
                                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 all-center">수정하기</button>
                                        <button type="button" onclick="ptMemberModalMailAuth(this)" style="width:110px"
                                            data-auth-mail-btn="student"
                                            {{ !empty($student) && $student->is_auth_email == 'Y' ? 'hidden':'' }}
                                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 all-center">본인인증</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start " >
                            {{-- 자택주소 --}}
                            <td class="text-start text-start ps-4 scale-text-gray_06">자택 주소</td>
                            <td  data-text=""
                                class="text-start text-start p-4 scale-text-gray_05 is_content" colspan="3">
                                <div class="h-center justify-content-between gap-2">
                                    <div data-ori="student_address"
                                        class="h-center" >
                                        {{ $student->student_address }}
                                    </div>
                                    <input type="text" data-user-address data-modify-editor="student_address" data-address onclick=""  hidden
                                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" placeholder="변경할 주소를 입력해주세요." value="{{ $student->student_address }}">
                                    <div class="col-auto">
                                        <button type="button" onclick="ptMemberToggleAddressEdit(this)" data-modify="none"
                                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">주소 수정하기</button>
                                    </div>
                                </div>
                                <div data-daum-address="" style="border:1px solid;width:100%;height:300px;margin:5px 0;position:relative" hidden>
                                    <img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode()" alt="접기 버튼">
                                </div>
                            </td>
                        </tr>

                        <tr class="text-start scale-bg-gray_01" >
                            <td class="text-start ps-4 scale-text-gray_06">담임 선생님</td>
                            <td class="text-start px-4 py-4">
                                <div data-ori="teach_name" data-text=""
                                    class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3 is_content">
                                    {{ $student ? $student->teach_name : ''}}
                                </div>
                            </td>

                            <td class="text-start ps-4 scale-text-gray_06">휴대전화</td>
                            <td class="text-start px-4 py-4">
                                <div data-ori="teach_phone" data-text=""
                                    class="text-m-24px px-0 scale-text-gray_05 p-2 w-100 rounded-3 is_content">
                                    {{ $student ? $student->teach_phone : ''}}
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start scale-bg-gray_01">
                            <td colspan="4" class="p-4">
                                <div class="text-end">
                                    <button type="button" onclick=""  data-modify
                                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">이용권 재등록</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                @endforeach
                @endif

                <div data-explain="96px">
                    <div class="py-lg-15"></div>
                </div>

                <div class=" d-flex flex-column row-gap-3 text-sb-24px mt-52 mb-52">
                    <button onclick="teachUserAddUserInputAdd();"
                        class="d-flex border-none align-items-center justify-content-center h-120 scale-bg-gray_01">
                        <svg class="me-1" width="32" height="32" viewBox="0 0 32 32" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M24.4849 16L7.51472 16" stroke="#DCDCDC" stroke-width="3" stroke-linecap="round"></path>
                            <path d="M15.9998 24.4844L15.9998 7.51423" stroke="#DCDCDC" stroke-width="3" stroke-linecap="round">
                            </path>
                        </svg>
                        <span class="text-sb-24px scale-text-gray_05">추가 자녀 정보 등록</span>
                    </button>
                </div>


           </section>

        </div>
    </div>

    {{-- 160px --}}
    <div>
        <div class="py-lg-5"></div>
        <div class="py-lg-4"></div>
        <div class="pt-lg-3"></div>
    </div>
</div>

{{-- 모달 / 휴대폰 인증 --}}
<div class="modal fade" id="pt_member_modal_phone_auth" tabindex="-1" aria-hidden="true" style="display: none;">
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

{{-- 모달 / 이메일 인증 --}}
<div class="modal fade" id="pt_member_modal_mail_auth" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog  modal-dialog-centered modal-680">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">메일인증</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="" style="background-size: auto;"></button>
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

 {{--  모달 / 추가 자녀 정보 등록 --}}
<div class="modal fade " id="modal_pt_member_child_add" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-modal="true" role="dialog">
    <div class="modal-dialog rounded modal-lg" >
        <div class="modal-content border-none rounded p-3 modal-shadow-style">
            <div class="modal-header border-bottom-0">
                <h1 class="modal-title fs-5 text-b-24px" id="">
                    추가 자녀 정보 등록
                </h1>
                <button type="button" style="width:32px;height:32px"
                    class="btn-close close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table data-tb="" data-user-type="student" data-user-seq="0"
                    class="w-100 table-list-style table-border-xless table-h-92 mt-0" style="border-top:0px;">
                    <colgroup>
                        <col style="width: 15%;">
                        <col style="width: 35%;">
                        <col style="width: 15%;">
                        <col style="width: 35%;">
                    </colgroup>
                    <thead>
                        <tr class="text-start ">
                            <td class="text-start ps-4 scale-text-gray_06">이름</td>
                            <td class="text-start px-4 py-4" colspan="3">
                                <div class="h-center justify-content-between gap-2">
                                    <input type="text" data-modify-editor="student_name" onclick=""
                                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" placeholder="" value="">
                                    <div class="col h-center justify-content-end">
                                </div>
                                </div>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="text-start ">
                            <td class="text-start ps-4 scale-text-gray_06">학교</td>
                            <td class="text-start px-4 py-4">
                                <input type="text" data-modify-editor="school_name" onclick=""
                                    class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" placeholder="" value="">
                            </td>

                            <td class="text-start ps-4 scale-text-gray_06">학년</td>
                            <td class="text-start px-4 py-4">
                                <div class="d-inline-block select-wrap select-icon w-100" style="min-width:100px">
                                    <select data-modify-editor="grade"
                                    class="rounded-pill border-gray lg-select text-sb-18px ps-4 p-3 w-100" >
                                    @if(!empty($grade_codes))
                                        @foreach($grade_codes as $grade_code)
                                        <option value="{{ $grade_code->id }}">{{ $grade_code->code_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start ">
                            <td class="text-start ps-4 scale-text-gray_06">비밀번호</td>
                            <td class="text-start px-4 py-4">
                                <input type="password" data-modify-editor="student_pw1" onclick=""
                                    class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" placeholder="" value="">
                            </td>

                            <td class="text-start ps-4 scale-text-gray_06">비밀번호 확인</td>
                            <td class="text-start px-4 py-4">
                                <input type="password" data-modify-editor="student_pw2" onclick=""
                                    class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" placeholder="" value="">
                            </td>
                        </tr>
                        {{-- 휴대전화 / 이메일 --}}
                        <tr class="text-start " >
                            <td class=" text-start ps-4 scale-text-gray_06">휴대전화</td>
                            <td class=" text-start px-4 py-4">
                                <div class="d-flex justify-content-between">
                                    <input type="tel" data-modify-editor="student_phone" onclick=""
                                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" placeholder="" value="">
                                </div>
                            </td>
                            <td class=" text-start ps-4 scale-text-gray_06">이메일</td>
                            <td class=" text-start px-4 py-4">
                                <div class="d-flex justify-content-between">
                                    <input type="email" data-modify-editor="student_email" onclick=""
                                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" placeholder="" value="">
                                </div>
                            </td>
                        </tr>
                        <tr class="text-start " >
                            {{-- 자택주소 --}}
                            <td class="text-start text-start ps-4 scale-text-gray_06">자택 주소</td>
                            <td  data-text=""
                                class="text-start text-start p-4 scale-text-gray_05 is_content" colspan="3">
                                <div class="h-center justify-content-between gap-2">
                                    <input type="text" data-user-address data-modify-editor="student_address" data-address onclick=""
                                        class="text-m-20px px-4 scale-text-gray_05 border p-2 w-100 rounded-3" placeholder="주소를 입력해주세요." value="">
                                    <div class="col-auto">
                                        <button type="button" onclick="ptMemberDtailModifyToggle(this, 1)" data-modify="none"
                                            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">주소 검색</button>
                                    </div>
                                </div>
                                <div data-daum-address="" style="border:1px solid;width:100%;height:300px;margin:5px 0;position:relative" hidden>
                                    <img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode()" alt="접기 버튼">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="w-center">
                <button data-btn-save="" type="button" onclick="ptMemberChildInsert();" style="width:170px" class="btn-line-ms-secondary text-sb-20px rounded-pill bg-primary-y text-white border w-center">저장하기</button>
            </div>

        </div>
    </div>
    <div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    if('{{$type}}'){
        if('{{$type}}' == 'parent'){
            document.querySelector('[data-pt-member-tab="1"]').click();
        }else{
            document.querySelector('[data-pt-member-tab="2"]').click();
        }
    }else{
        if (sessionStorage.getItem('isPtMmeberTab') === 'parent') {
            sessionStorage.removeItem('isPtMmeberTab'); // 상태를 초기화합니다.
            // 1 click
            document.querySelector('[data-pt-member-tab="1"]').click();

        }
        if (sessionStorage.getItem('isPtMmeberTab') === 'child') {
            sessionStorage.removeItem('isPtMmeberTab'); // 상태를 초기화합니다.
            //data-pt-member-tab=2 click
            document.querySelector('[data-pt-member-tab="2"]').click();
        }
    }
    sessionStorage.setItem('isPtMmeberTab', '');
});

document.addEventListener('visibilitychange', function(event) {
});
// 학부모 회원정보 수정 탭
function ptMemberAsideTab(vthis){
// data-pt-noti-point-main-tab 모두 해제
    document.querySelectorAll('[data-pt-member-tab]').forEach(function(el){
        el.classList.remove('active');
    });
    vthis.classList.add('active');
    const type = vthis.dataset.ptMemberTab;
    // tab에 맞는 section 보여주기
    ptMemberSectionShow(type)
    if(type == 1) sessionStorage.setItem('isPtMmeberTab', 'parent');
    if(type == 2) sessionStorage.setItem('isPtMmeberTab', 'child');
}

// 학부모 회원정보 수정 section 보여주기
function ptMemberSectionShow(type){
    document.querySelectorAll('[data-pt-member-section]').forEach(function(el){
        el.hidden = true;
    });
    document.querySelector(`[data-pt-member-section="${type}"]`).hidden = false;
}


// 자녀정보 오픈
function ptMemberChildOpen(vthis){
    // 180 도 전환
    const table = vthis.closest('table');
    if(vthis.classList.contains('rotate-180')){
        //닫힘으로 열리는 상태작업.
        vthis.classList.remove('rotate-180');
        table.querySelector('tbody').hidden = false;
    }else{
        //열려 있으므로 닫히는 작업.
        vthis.classList.add('rotate-180');
        table.querySelector('tbody').hidden = true;
    }
}

// 학부모 정보 관리 > 수정하기 버튼 클릭
function  ptMemberDtailModify(is_modify){
    const section_tab = document.querySelector('[data-pt-member-section="1"]');
    const btn_modify = section_tab.querySelector('[data-btn-modify]');
    const btn_save = section_tab.querySelector('[data-btn-save]');
    const btn_modify_cancel = section_tab.querySelector('[data-btn-modaiy-cancel]');
    const data_modifys = section_tab.querySelectorAll('[data-modify]');
    const data_oris = section_tab.querySelectorAll('[data-ori]');
    const data_modify_editors = section_tab.querySelectorAll('[data-modify-editor]');

    if(is_modify){
        btn_modify.hidden = true;
        btn_save.hidden = false;
        btn_modify_cancel.hidden = false;
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
        btn_modify_cancel.hidden = true;
        data_modifys.forEach(function(item){
            item.hidden = true;
        });
        data_oris.forEach(function(item){
           item.hidden = false;
        });
        data_modify_editors.forEach(function(item){
            item.hidden = true;
        })
    }
}

// 학부모 정보 관리 > 수정하기 버튼 클릭 > 각각의 수정하기 버튼 클릭
function ptMemberDtailModifyToggle(vthis, num){
    if(num != undefined) element_wrap = vthis.closest('td').querySelector('[data-daum-address]');
    const td = vthis.closest('td');
    // td 아래 data-ori 를 찾아서
    //  확인시 숨김처리 되어있으면 보이게 하고 반대면 숨김처리
    const data_oris = td.querySelectorAll('[data-ori]');
    const data_modify_editors = td.querySelectorAll('[data-modify-editor]');

    // 숨김처리 되어있으면 보이게 하고 반대면 숨김처리
    if(data_oris[0]?.hidden){
        data_oris.forEach(function(el){el.hidden = false;});
        data_modify_editors.forEach(function(item){
            item.hidden = true;
        });
        if(num != undefined){
            foldDaumPostcode(num);
        }
    }
    else{
        data_oris.forEach(function(el){el.hidden = true;});
        data_modify_editors.forEach(function(item){
            item.hidden = false;
        });
        if(num != undefined){
            execDaumPostcode(num);
        }
    }
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
    // 전역으로 변경.
    // const element_wrap = document.getElementById('teach_st_af_detail_div_address_wrap'+num);
    let address_el = element_wrap.closest('td').querySelector('[data-address]');


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
                address_el.value = extraAddr;
            } else {
                address_el.value = '';
            }

            // 우편번호와 주소 정보를 해당 필드에 넣는다.
            const zip_code = data.zonecode;
            const address = addr;
            address_el.value = addr;
            address_el.focus();

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

// 저장하기 버튼 클릭
function ptMemberDtailSave(){
    const section_tab = document.querySelector('[data-pt-member-section="1"]');
    const parent_seq = document.querySelector("[data-main-parent-seq]").value;

    //학부모 정보
    const parent_name_el = section_tab.querySelector("[data-modify-editor='parent_name']");
    const parent_tel_el = section_tab.querySelector('[data-modify-editor="parent_tel"]');
    const parent_email_el = section_tab.querySelector('[data-modify-editor="parent_email"]');
    const parent_address_el = section_tab.querySelector('[data-modify-editor="parent_address"]');


    // 위 태그들이 모두 hidden 아니면 수정하는 것으로 판단
    const parent_name = parent_name_el.hidden != true ? parent_name_el.value : '';
    const parent_tel = parent_tel_el.hidden != true ? parent_tel_el.value : '';
    const parent_email = parent_email_el.hidden != true ? parent_email_el.value : '';
    const parent_address = parent_address_el.hidden != true ? parent_address_el.value : '';


    const page = '/parent/member/info/update';
    const parameter = {
        user_type:'parent',
        user_seq: parent_seq,
        user_name: parent_name,
        user_tel: parent_tel,
        user_mail: parent_email,
        user_address: parent_address
    };

    const msg = "<div class='text-sb-20px'>수정을 계속 진행하시겠습니가/?</div>";
    sAlert('', msg, 3, function(){
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                toast('수정이 완료되었습니다.');
                ptMemberUpdateSpan(parameter);
                // 취소버튼 클릭
                ptMemberDtailModify(false);
            }else{}
        });
    });
}

//  저장시 비동기로 진행하므로, [span] 태그 정보 변경.
function ptMemberUpdateSpan(data){
    if(data.user_type == 'parent'){
        const section_tab = document.querySelector('[data-pt-member-section="1"]');
        if(data.user_name) section_tab.querySelector('[data-ori="parent_name"]').innerHTML = data.user_name;
        if(data.user_tel) sectionjson.section_tab.querySelector('[data-ori="parent_tel"]').innerHTML = data.user_tel;
        if(data.user_mail) section_tab.querySelector('[data-ori="parent_email"]').innerHTML = data.user_mail;
        if(data.user_address) section_tab.querySelector('[data-ori="parent_address"]').innerHTML = data.user_address;
    }
}

// 이메일 인증 모달 오픈
var user_email_el = null;
var user_email_type = '';
var user_email_seq = '';
var user_email_name = '';

function ptMemberModalMailAuth(vthis){
    // data-auth-mail
    const table = vthis.closest('table');
    const td = vthis.closest('td');
    user_email_el = td.querySelector("[data-email]");
    user_email_type = table.dataset.userType;
    user_email_seq = table.dataset.userSeq;
    user_email_name = table.dataset.userName;

    const user_email = user_email_el.innerText;
    if(user_email == ''){
        toast('이메일이 없습니다.');
        return;
    }
    document.querySelector("[data-auth-mail]").innerText = user_email;

    const myModal = new bootstrap.Modal(document.getElementById('pt_member_modal_mail_auth'), {
        // keyboard: false
    });
    myModal.show();
}

// 이메일 인증번호 전송.
function ptMemberSendMailAuth(){
    // 데이터
    const user_email = user_email_el.innerText;
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

// :이메일 인증
function ptMemberMailAuth(){
    const modal = document.getElementById('pt_member_modal_mail_auth');
    const user_seq = user_email_seq;
    const user_type = user_email_type;
    const user_email = user_email_el.innerText.trim();
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
            user_email_el.nextElementSibling.hidden = false;
            user_email_el.closest('td').querySelector('[data-auth-mail-btn]').hidden = true;

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
var user_phone_el = null;
var user_phone_type = '';
var user_phone_seq = '';
var user_phone_name = '';
function ptMemberModalPhoneAuth(vthis){
    // data-auth-mail
    const table = vthis.closest('table');
    const td = vthis.closest('td');
    user_phone_el = td.querySelector("[data-phone]");
    user_phone_type = table.dataset.userType;
    user_phone_seq = table.dataset.userSeq;
    user_phone_name = table.dataset.userName;

    const user_phone = user_phone_el.innerText;

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
    const user_phone = user_phone_el.innerText;
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
    const user_phone = user_phone_el.innerText;
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
            user_phone_el.nextElementSibling.hidden = false;
            user_phone_el.closest('td').querySelector("[data-auth-phone-btn]").hidden = true;

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


// 학생 > 주소 수정하기 > 클릭
function ptMemberToggleAddressEdit(vthis){
    const table = vthis.closest('table');
    const act = vthis.dataset.modify;
    if(act == 'active'){
        const msg = "<div class='text-sb-24px'>주소를 수정하시겠습니까?</div>";
        sAlert('', msg, 3, function(){
            const page = '/parent/member/info/update';
            const parameter = {
                user_type:'student',
                user_seq: table.dataset.userSeq,
                user_name: table.dataset.userName,
                user_address:table.querySelector('[data-address]').value,
                is_address:'true'
            };

            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    toast('수정이 완료되었습니다.');
                    table.querySelector('[data-ori="student_address"]').innerHTML = parameter.user_address;
                    // 토글
                    ptMemberDtailModifyToggle(vthis, 1);
                }else{}
            });
        }, function(){
                    ptMemberDtailModifyToggle(vthis, 1);
        });
        vthis.innerHTML = '주소 수정하기';
        vthis.dataset.modify = 'none';
    }else{
        vthis.dataset.modify = 'active';
        vthis.innerHTML = '주소 저장';
        ptMemberDtailModifyToggle(vthis, 1);
    }
}

// NOTE: 의문 / 여기서 자녀를 만들었을때, 어느 학원/학교에 배치할지를 어디서 정하는지에 대해서.
// 자녀 정보 추가 등록
function ptMemberChildInsert(){
    const modal = document.querySelector('#modal_pt_member_child_add');
    const table = modal.querySelector('table');
    const student_name = table.querySelector('[data-modify-editor="student_name"]').value;
    const school_name = table.querySelector('[data-modify-editor="school_name"]').value;
    const grade = table.querySelector('[data-modify-editor="grade"]').value;
    const student_pw1 = table.querySelector('[data-modify-editor="student_pw1"]').value;
    const student_pw2 = table.querySelector('[data-modify-editor="student_pw2"]').value;
    const student_phone = table.querySelector('[data-modify-editor="student_name"]').value;
    const student_email = table.querySelector('[data-modify-editor="student_email"]').value;
    const student_address = table.querySelector('[data-modify-editor="student_address"]').value;

    if(student_pw1 != student_pw2){
        toast('비밀번호가 일치하지 않습니다.');
        return;
    }
    if(student_name == ''){ toast('이름을 입력해주세요'); return;}
    if(student_pw1 == ''){toast('암호를 입력해주세요'); return;}

    const student_id = ptMemberAddCreateId();
    const page = "/parent/member/info/child/insert";
    const parameter = {
        student_id:student_id,
        student_name:student_name,
        school_name:school_name,
        grade:grade,
        student_pw:student_pw1,
        student_phone:student_phone,
        student_email:student_email,
        student_addr:student_address
    };
    const msg = "<div class='text-sb-24px'>자녀를 추가 등록 하시겠습니까?</div>";
    sAlert('', msg, 3, function(){
        queryFetch(page, parameter, function(result){
            if((result.resultCode||'') == 'success'){
                toast('등록이 완료되었습니다.');
                //모달 닫기.
                modal.querySelector('.btn-close').click();
                // 화면 새로고침.
                location.reload();
            }
        });
    });
}

// 임시 아이디 생성
let idx = 0;
function ptMemberAddCreateId(){
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


// 자녀 정보 추가 등록 모달 오픈
function teachUserAddUserInputAdd(){
    const myModal = new bootstrap.Modal(document.getElementById('modal_pt_member_child_add'), {
        keyboard: false
    });
    myModal.show();
}

// 없던 요청으로 인한, 수정하기
// 학생 > 휴대전화 및 이메일 수정.
function ptMemberNewEdit(vthis){
    const type = vthis.dataset.type;
    const student_seq = vthis.closest('table').dataset.userSeq;
    const table = vthis.closest('table');
    const act = vthis.dataset.modify;
    if(act == 'active'){
        const msg = `<div class='text-sb-24px'>${type == 'student_phone' ? '학생 전화번호' : '학생 이메일'}를 수정하시겠습니까?</div>`;
        sAlert('', msg, 3, function(){
            const page = '/parent/member/info/update';
            const parameter = {
                user_type:'student',
                user_seq: table.dataset.userSeq,
            };
            let chg_value = '';
            if(type == 'student_phone'){
                parameter.user_phone = table.querySelector('[data-student-phone-chg]').value;
                parameter.is_phone = 'true';
                chg_value = parameter.user_phone;
            }else{
                parameter.user_mail = table.querySelector('[data-student-email-chg]').value;
                parameter.is_mail = 'true';
                chg_value = parameter.user_mail;
            }
            if(chg_value == ''){
                toast('수정할 내용을 입력해주세요.');
                return;
            }

            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    toast('수정이 완료되었습니다.');
                    if(type == 'student_phone'){
                        table.querySelector('[data-ori="student_phone"]').innerHTML = parameter.user_phone;
                    }else{
                        table.querySelector('[data-ori="student_email"]').innerHTML = parameter.user_mail;
                    }
                    // 토글
                    ptMemberDtailModifyToggle(vthis);
                }else{}
            });
        }, function(){
                    ptMemberDtailModifyToggle(vthis);
        });
        vthis.innerHTML = '수정하기';
        vthis.dataset.modify = 'none';
    }else{
        vthis.dataset.modify = 'active';
        vthis.innerHTML = '저장하기';
        ptMemberDtailModifyToggle(vthis);
    }

}
</script>

@endsection
