@extends('layout.layout')
{{-- 타이틀 --}}
@section('head_title', '회원정보')

{{-- 학생 컨텐츠 --}}
@section('layout_coutent')
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<style>
    #member_info_tb_user_info1 td,
    #member_info_tb_user_info2 td {
        padding:32px;
    }
</style>
<div class="col mx-0 mb-3 pt-5 row position-relative" id="member_info_tb_main">
    <main data-main-user-info>
    {{-- 상단 --}}
    <article class="pt-5 px-0">
            <div class="row">
                <div class="col-auto pb-2 mb-4">
                    <div class="h-center">
                        <img src="{{ asset('images/graphic_memberinfo_icon.svg')}}" width="72" class="pt-2">
                        <span class="cfs-1 fw-semibold align-middle">회원정보</span>
                    </div>
                    <div class="pt-2 pb-2 mb-8">
                        <span class="cfs-3 fw-medium">여러가지 학습을 한 눈에 쉽게 알아볼 수 있어요.</span>
                    </div>
                </div>
                <div class="col position-relative">
                    <img src="{{ asset('images/character_robot_member_info.svg') }}" class="bottom-0 end-0 position-absolute" width="267">
                </div>
        </div>
    </article>

    {{-- padding 80px --}}
        <div>
            <div class="py-4"></div>
            <div class="pt-3"></div>

        </div>

        <article>
            {{-- 기본 회원정보 --}}
            <section>
                <h5 class="text-b-28px mb-4">기본 회원정보</h5>
                <div style="border-top: 2px solid #222;">
                    <table id="member_info_tb_user_info2" class="table">
                        <tbody>
                            <tr>
                                <input type="hidden" data-user-type value="{{ session()->get('login_type') }}">
                                <td class="col-2 align-middle">
                                        <p class="text-sb-24px scale-text-gray_06">프로필</p>
                                </td>
                                <td class="py-4">
                                    <div class="row justify-content-between">
                                        <div class="col-auto position-relative">
                                            <div class="rounded-circle overflow-hidden d-flex align-items-center justify-content-center"
                                            style="width:120px;height:120px;" >
                                            {{-- 학생 --}}

                                            @if(!empty($student))
                                                <img src="{{
                                                    !empty($student->profile_img_path) ? asset('storage/uploads/user_profile/student/'.$student->profile_img_path) : ''
                                                }}"
                                                width="120" data-profile-img >
                                            @endif
                                            </div>
                                            <button class="position-absolute end-0 bottom-0 scale-bg-gray_01 rounded-pill border-0 all-center"
                                            {{-- 프로필 이미지 업로드 --}}
                                            style="width:32px;height:32px" onclick="memberInfoModalProfile();">
                                                <img src="{{ asset('images/camera_icon.svg')}}" width="24">
                                            </button>
                                        </div>
                                        <div class="col-auto all-center select-wrap select-icon" hidden>
                                            <select class="border-gray sm-select text-sb-20px">
                                            <option value="" >캐릭터 선택</option>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            {{-- 아이디 / user-id --}}
                            <tr>
                                <td>
                                    <span class="text-sb-24px scale-text-gray_06">아이디</span>
                                </td>
                                <td>
                                    <span data-user-id class="text-sb-24px scale-text-black">
                                        {{ !empty($student) ? $student->student_id : ''}}
                                    </span>
                                </td>
                                <input type="hidden" data-student-seq value="{{ !empty($student) ? $student->id : ''}}">
                            </tr>
                            {{-- 이름 / user-name --}}
                            <tr>
                                <td>
                                    <span class="text-sb-24px scale-text-gray_06">이름</span>
                                </td>
                                <td>
                                    <span data-user-name class="text-sb-24px scale-text-black">
                                        {{ !empty($student) ? $student->student_name : ''}}
                                    </span>
                                </td>
                            </tr>
                            {{-- 비밀번호 / user-pw --}}
                            <tr>
                                <td>
                                    <span class="text-sb-24px scale-text-gray_06">비밀번호</span>
                                </td>
                                <td>
                                    <span data-user-pw class="text-sb-24px scale-text-black">********</span>
                                </td>
                            </tr>
                            {{-- 연락처 / user-phone --}}
                            <tr>
                                <td>
                                    <span class="text-sb-24px scale-text-gray_06">학부모 연락처</span>
                                </td>
                                <td>
                                    <div class="row justify-content-between">
                                        <div class="col-auto">
                                            <span data-user-phone class="text-sb-24px scale-text-black">
                                            {{ !empty($student) ? $student->pt_parent_phone : ''}}
                                            </span>
                                            <span class="text-sb-24px text-primary" {{ !empty($student) && $student->pt_is_auth_phone == 'Y' ? '':'hidden' }} >(인증됨)</span>
                                        </div>
                                        <button class="col-auto btn border scale-text-black text-sb-20px rounded-3" hidden
                                        {{ !empty($student) && $student->pt_is_auth_phone == 'Y' ? 'hidden':'' }}
                                        data-auth-phone-btn onclick="memberInfoModalPhoneAuth();">
                                            휴대폰 인증
                                        </button>
                                    </div>

                                </td>
                            </tr>
                            {{-- 이메일 / user-email --}}
                            <tr hidden>
                                <td>
                                    <span class="text-sb-24px scale-text-gray_06">이메일</span>
                                </td>
                                <td>
                                    <div class="row justify-content-between">
                                        <div class="col-auto">
                                            <span data-user-email class="text-sb-24px scale-text-black">
                                                {{ !empty($student) ? $student->student_email : ''}}
                                            </span>
                                            <span class="text-sb-24px text-danger" {{ !empty($student) && $student->is_auth_email == 'Y' ? '':'hidden' }}>(인증됨)</span>
                                        </div>
                                        <button class="col-auto btn border scale-text-black text-sb-20px rounded-3"
                                        {{ !empty($student) && $student->is_auth_email == 'Y' ? 'hidden':'' }}
                                        data-auth-mail-btn onclick="memberInfoModalMailAuth();">
                                            이메일 인증
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- padding 72px--}}
            <div>
                <div class="py-4"></div>
                <div class="pt-4"></div>
            </div>
            {{-- 추가 회원정보 --}}
            <section>
                <h5 class="text-b-28px mb-4">추가 회원정보</h5>
                <div style="border-top: 2px solid #222;">
                    <table id="member_info_tb_user_info1" class="table">
                        <tbody>
                            {{-- 생연월일 / user-birthday --}}
                            <tr hidden>
                                <td class="col-2">
                                    <span class="text-sb-24px scale-text-gray_06">생년월일</span>
                                </td>
                                <td>
                                    <span data-user-birthday class="text-sb-24px scale-text-black">
                                        {{ !empty($student) ? $student->student_birthday : ''}}
                                    </span>
                                </td>
                            </tr>
                            {{-- 성별 / user-sex --}}
                            <tr hidden>
                                <td>
                                    <span class="text-sb-24px scale-text-gray_06">성별</span>
                                </td>
                                <td>
                                    <span data-user-sex class="text-sb-24px scale-text-black">
                                        {{ !empty($student) ? $student->student_sex : ''}}
                                    </span>
                                </td>
                            </tr>
                            {{-- 주소 / user-address --}}
                            <tr hidden>
                                <td>
                                    <span class="text-sb-24px scale-text-gray_06">주소</span>

                                </td>
                                <td>
                                    <div class="row justify-content-between">
                                        <span  class="col-auto text-sb-24px scale-text-black" data-main-info-address>
                                            {{ !empty($student) ? $student->student_address : ''}}
                                        </span>

                                    </div>
                                </td>
                            </tr>
                            {{-- 학교 / user-school --}}
                            <tr>
                                <td>
                                    <span class="text-sb-24px scale-text-gray_06">학교</span>
                                </td>
                                <td>
                                    <span data-user-school class="text-sb-24px scale-text-black">
                                        @if(!empty($student))
                                            @if($student->team_type == 'after_school')
                                                {{ $student->team_name }}
                                            @else
                                                {{ $student->student_school }}
                                            @endif
                                        @endif
                                    </span>
                                </td>
                            </tr>
                            {{-- 학년 / user-grade --}}
                            <tr>
                                <td>
                                    <span class="text-sb-24px scale-text-gray_06">학년</span>
                                </td>
                                <td>
                                    <span data-user-grade class="text-sb-24px scale-text-black">
                                        {{ !empty($student) ? $student->grade_name : ''}}
                                    </span>
                                </td>
                            </tr>
                            {{-- 담잉 선생님 / user-teacher-name --}}
                            <tr>
                                <td>
                                    <span class="text-sb-24px scale-text-gray_06">담당 선생님</span>
                                </td>
                                <td>
                                    <span data-user-teacher-name class="text-sb-24px scale-text-black">
                                        @if(!empty($student))
                                            @if($student->team_type == 'after_school')
                                                @foreach($class_teachers as $i => $teacher)
                                                    {{$teacher->teach_name." (".$teacher->team_name." )".($i != 0 ? ', ' : '')}}
                                                @endforeach
                                            @else
                                                {{ $student->teach_name." (".($student->region_name)." ".$student->team_name." )" }}
                                            @endif
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
            {{-- padding 80px --}}
            <div style="height: 80px"></div>
            <section class="text-center">
                <button class="btn btn-primary-y cbtn-p-i py-3 rounded-pill text-sb-24px" onclick="memberInfoUserInfoUpdatePage();"> 회원정보 수정하기 </button>
            </section>
            {{-- pdding 160px --}}
            <div>
                <div class="py-5"></div>
                <div class="py-4"></div>
                <div class="py-2"></div>
            </div>
        </article>
    </main>

    {{-- 회원정보 수정 페이지 --}}
    <main data-main-user-info-update hidden>
        <article>
            <button class="btn d-flex align-items-center" onclick="memberInfoUserInfoPage();">
                <img src="{{ asset('images/black_arrow_left_tail.svg') }}" alt="">
                <span class="text-b-42px scale-text-black align-middle">개인정보 수정</span>
            </button>
        </article>

        {{-- padding 109px --}}
        <div style="height: 109px"></div>

        <article class="row container-lg mx-auto" style="max-width:1112px">
            <aside class="col-2"></aside>
            <div class="col">
                <section>
                    <h3 class="text-sb-24px scale-text-black pb-2 mb-1">기본 정보</h3>
                    <div class="scale-bg-black" style="height: 2px"></div>
                    {{-- 이름 --}}
                    <div class="row scale-bg-gray_01 mx-0" style="padding:32px">
                        <div class="col-2 text-sb-24px scale-text-gray_06">이름</div>
                        <div class="col text-sb-24px scale-text-gray_05">
                            {{ !empty($student) ? $student->student_name : ''}}
                        </div>
                    </div>
                    <div class="scale-bg-gray_03" style="height:1px"></div>
                    {{-- 아이디 --}}
                    <div class="row scale-bg-gray_01 mx-0" style="padding:32px">
                        <div class="col-2 text-sb-24px scale-text-gray_06">아이디</div>
                        <div class="col text-sb-24px scale-text-gray_05">
                            {{ !empty($student) ? $student->student_id : ''}}
                        </div>
                    </div>
                    <div class="scale-bg-gray_03" style="height:1px"></div>
                    {{-- 학교 --}}
                    <div class="row mx-0" style="padding:32px">
                        <div class="col-2 text-sb-24px scale-text-gray_06">학교</div>
                        <div class="col text-sb-24px scale-text-gray_05">
                            @if(!empty($student))
                                @if($student->team_type == 'after_school')
                                    {{ $student->team_name }}
                                @else
                                    {{ $student->student_school }}
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="scale-bg-gray_03" style="height:1px"></div>
                    {{-- 학년 --}}
                    <div class="row scale-bg-gray_01 mx-0" style="padding:32px">
                        <div class="col-2 text-sb-24px scale-text-gray_06">학년</div>
                        <div class="col text-sb-24px scale-text-gray_05">
                            {{ !empty($student) ? $student->grade_name : ''}}
                        </div>
                    </div>
                    <div class="scale-bg-gray_03" style="height:1px"></div>
                    {{-- 비밀번호 --}}
                    <div class="row mx-0" style="padding:32px">
                        <div class="col-2 text-sb-24px scale-text-gray_06 d-flex align-items-center">비밀번호</div>
                        <div class="col text-sb-24px scale-text-gray_05 d-flex align-items-center" data-main-update-div-pw>
                            ********
                        </div>
                        <div class="col-auto text-end">
                            <button class="col-auto btn border scale-text-black text-sb-20px rounded-3"
                            onclick="memberInfoModalModalEdit('pw');">
                                수정하기
                            </button>
                        </div>
                    </div>
                    <div class="scale-bg-gray_03" style="height:1px"></div>
                </section>

                {{-- padding 80px --}}
                <div style="height: 80px"></div>

                <section hidden>
                    <h3 class="text-sb-24px scale-text-black pb-2 mb-1 d-flex align-items-center">연락처 정보</h3>
                    <div class="scale-bg-black" style="height: 2px"></div>
                    {{-- 연락처 --}}
                    <div class="row mx-0" style="padding:32px">
                        <div class="col-2 text-sb-24px scale-text-gray_06 d-flex align-items-center">연락처</div>
                        <div class="col text-sb-24px scale-text-black d-flex align-items-center">
                            <span data-main-update-sp-phone>
                                {{ !empty($student) ? $student->student_phone : ''}}
                            </span>
                            <span class="text-sb-24px text-primary" {{ !empty($student) && $student->is_auth_phone == 'Y' ? '':'hidden' }} >(인증됨)</span>

                        </div>
                        <div class="col-auto text-end">
                            <button class="col-auto btn border scale-text-black text-sb-20px rounded-3"
                            onclick="memberInfoModalModalEdit('phone');">
                                수정하기
                            </button>
                        </div>
                    </div>
                    <div class="scale-bg-gray_03" style="height:1px"></div>
                    {{-- 주소 --}}
                    <div class="row mx-0" style="padding:32px">
                        <div class="col-2 text-sb-24px scale-text-gray_06 d-flex align-items-center">주소</div>
                        <div class="col text-sb-24px scale-text-black d-flex align-items-center">
                            <span data-main-update-sp-address>
                                {{ !empty($student) ? $student->student_address : ''}}
                            </span>
                        </div>
                        <div class="col-auto text-end">
                            <button class="col-auto btn border scale-text-black text-sb-20px rounded-3"
                            onclick="memberInfoModalModalEdit('address');">
                                수정하기
                            </button>
                        </div>
                    </div>
                    <div class="scale-bg-gray_03" style="height:1px"></div>
                    {{-- 이메일 --}}
                    <div class="row mx-0" style="padding:32px" hidden>
                        <div class="col-2 text-sb-24px scale-text-gray_06 d-flex align-items-center">이메일</div>
                        <div class="col text-sb-24px scale-text-black d-flex align-items-center">
                            <span data-main-update-sp-email>
                                {{ !empty($student) ? $student->student_email : ''}}
                            </span>
                            <span class="text-sb-24px text-danger" {{ !empty($student) && $student->is_auth_email == 'Y' ? '':'hidden' }}>(인증됨)</span>
                        </div>
                        <div class="col-auto text-end">
                            <button class="col-auto btn border scale-text-black text-sb-20px rounded-3"
                            onclick="memberInfoModalModalEdit('mail');">
                                수정하기
                            </button>
                        </div>
                    </div>
                    <div class="scale-bg-gray_03" style="height:1px"></div>
                </section>

                {{-- padding 80px --}}
                <div style="height: 80px"></div>

                <section class="text-end">
                    <button class="btn btn-outline-light border cbtn-p-i py-3 rounded-pill text-sb-24px scale-text-gray_05"
                    onclick="memberInfoUserInfoPage();" style="width:232px;margin-right: 12px">
                    취소하기
                    </button>
                    <button class="btn btn-primary-y cbtn-p-i py-3 rounded-pill text-sb-24px"
                    onclick="memberInfoUpdate();">
                    변경사항 저장하기
                    </button>
                </section>

                {{-- padding 160px --}}
                <div style="height: 160px"></div>
            </div>
        </article>
    </main>
</div>

{{-- 모달 / 이메일 인증 --}}
<div class="modal fade" id="member_info_modal_mail_auth" tabindex="-1" aria-hidden="true" style="display: none;">
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
                    <button class="col-auto btn btn-primary-y btn-lg col py-2" onclick="memberInfoSendMailAuth();">
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
                <button class="btn btn-primary-y btn-lg col py-3" onclick="memberInfoMailAuth();">
                    <span class="ps-2 fs-4">인증하기</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- 모달 / 휴대폰 인증 --}}
<div class="modal fade" id="member_info_modal_phone_auth" tabindex="-1" aria-hidden="true" style="display: none;">
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
                    <button class="col-auto btn btn-primary-y btn-lg col py-2" onclick="memberInfoSendPhoneAuth();">
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
                <button class="btn btn-primary-y btn-lg col py-3" onclick="memberInfoPhoneAuth();">
                    <span class="ps-2 fs-4">인증하기</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- 모달 / 프로필 사진 업로드 --}}
<div class="modal fade" id="member_info_modal_profile" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">프로필 사진 관리</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="" style="background-size: auto;"></button>
            </div>
            <div class="modal-body">
                <div class="text-center bg-light rounded-4" style="height: 150px">
                    <img class="img_data" id="member_info_file_edit" src=""
                        alt="" style="height:150px">
                </div>
                <div class="d-flex gap-3 mt-3">
                    <button class="col btn btn-sm btn-outline-danger"
                        onclick="document.querySelector('#member_info_file_edit').src='';return false;">이미지
                        첨부 취소</button>
                    <button class="col btn btn-sm btn-outline-secondary"
                        onclick="document.querySelector('#member_info_imgfile_edit').click();return false;">
                        이미지 첨부
                    </button>
                    <input type="file" id="member_info_imgfile_edit" accept="image/*"
                        onchange="memberInfoUpdateImgFileChange(this, 'member_info_file_edit');return false;"
                        hidden>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light btn-lg col py-3 ctext-gc1 btn_close" hidden
                data-bs-dismiss="modal" aria-label="Close"
                onclick="">
                    <span class="ps-2 fs-4">닫기</span>
                </button>
                <button class="btn btn-danger btn-lg col py-3" onclick="memberInfoDeleteProfileImg();">
                    <span class="ps-2 fs-4">업로드 이미지 삭제</span>
                </button>
                <button class="btn btn-primary-y btn-lg col py-3" onclick="memberInfoUpdateProfileImg();">
                    <span class="ps-2 fs-4">저장하기</span>
                </button>
            </div>
        </div>
    </div>
</div>

{{-- 모달 / 비밀번호 수정 --}}
<div class="modal fade" id="member_info_modal_update_pw" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">비밀번호 수정</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick=""></button>
            </div>
            <div class="modal-body">
                <div class="row mx-0">
                    <div class="col py-2">
                        <input type="password" class="form-control text-r-t20px" placeholder="기존 비밀번호를 입력해주세요."
                        data-inp-current-pw>
                    </div>
                    <button class="col-auto btn btn-primary-y" onclick="memberInfoChkPw();"
                    data-btn-pw-chk>
                        기존 비밀번호 인증
                    </button>
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
                <button class="btn btn-primary-y btn-lg col py-3" onclick="memberInfoUpdatePw();">
                    <span class="ps-2 fs-4">저장하기</span>
                </button>
            </div>
        </div>
    </div>
</div>
{{-- 모달 / 연락처 수정 --}}
<div class="modal fade" id="member_info_modal_update_phone" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">연락처 수정</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick=""></button>
            </div>
            <div class="modal-body">
                <div>
                    <input type="number" class="form-control text-r-t20px" placeholder="변경할 연락처를 입력해주세요."
                    data-inp-phone>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light btn-lg col py-3 ctext-gc1 btn_close"
                data-bs-dismiss="modal" aria-label="Close"
                onclick="">
                    <span class="ps-2 fs-4">닫기</span>
                </button>
                <button class="btn btn-primary-y btn-lg col py-3" onclick="memberInfoUpdatePhone();">
                    <span class="ps-2 fs-4">저장하기</span>
                </button>
            </div>
        </div>
    </div>
</div>
{{-- 모달 / 주소 수정 --}}
<div class="modal fade" id="member_info_modal_update_address" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">주소 수정</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick=""></button>
            </div>
            <div class="modal-body">
                <div>
                    <input type="text" class="form-control text-r-t20px" placeholder="변경할 주소를 입력해주세요."
                    data-user-address>
                </div>
                <div id="member_info_div_address_wrap" style="border:1px solid;width:100%;height:300px;margin:5px 0;position:relative" hidden>
                    <img src="//t1.daumcdn.net/postcode/resource/images/close.png" id="btnFoldWrap" style="cursor:pointer;position:absolute;right:0px;top:-1px;z-index:1" onclick="foldDaumPostcode()" alt="접기 버튼">
                </div>
                <button class="btn text-sb-20px btn-primary-y"
                    onclick="execDaumPostcode()">
                    주소 검색
                </button>

            </div>
            <div class="modal-footer">
                <button class="btn btn-light btn-lg col py-3 ctext-gc1 btn_close"
                data-bs-dismiss="modal" aria-label="Close"
                onclick="">
                    <span class="ps-2 fs-4">닫기</span>
                </button>
                <button class="btn btn-primary-y btn-lg col py-3" onclick="memberInfoUpdateAddress();">
                    <span class="ps-2 fs-4">저장하기</span>
                </button>
            </div>
        </div>
    </div>
</div>
{{-- 모달 / 이메일 수정 --}}
<div class="modal fade" id="member_info_modal_update_mail" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">이메일 수정</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick=""></button>
            </div>
            <div class="modal-body">
                <div>
                    <input type="text" class="form-control text-r-t20px" placeholder="변경할 이메일을 입력해주세요."
                    data-inp-mail>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light btn-lg col py-3 ctext-gc1 btn_close"
                data-bs-dismiss="modal" aria-label="Close"
                onclick="">
                    <span class="ps-2 fs-4">닫기</span>
                </button>
                <button class="btn btn-primary-y btn-lg col py-3" onclick="memberInfoUpdateMail();">
                    <span class="ps-2 fs-4">저장하기</span>
                </button>
            </div>
        </div>
    </div>
</div>



<script>
        //우편번호찾기. 숨기기 / 가져오기
        const element_wrap = document.getElementById('member_info_div_address_wrap');
        function foldDaumPostcode() {
        // iframe을 넣은 element를 안보이게 한다.
            element_wrap.hidden = true;
        }

        function execDaumPostcode() {
        // 현재 scroll 위치를 저장해놓는다.
            const basic_info = document.querySelector('#member_info_tb_main');
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
                        document.querySelector("[data-user-address]").value = extraAddr;
                    } else {
                        document.querySelector("[data-user-address]").value = '';
                    }

                    // 우편번호와 주소 정보를 해당 필드에 넣는다.
                    const zip_code = data.zonecode;
                    const address = addr;
                    document.querySelector("[data-user-address]").value = addr;

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
        function memberInfoModalMailAuth(){
            // data-auth-mail
            const user_email = document.querySelector("[data-user-email]").innerText;
            document.querySelector("[data-auth-mail]").innerText = user_email;
            // 이메일이 없을 경우.
            if(user_email == ''){
                toast('이메일을 입력해주세요.');
                return;
            }
           const myModal = new bootstrap.Modal(document.getElementById('member_info_modal_mail_auth'), {
                // keyboard: false
            });
            myModal.show();
        }

        // 이메일 인증번호 전송.
        function memberInfoSendMailAuth(){
            // 데이터
            const user_email = document.querySelector("[data-user-email]").innerText;
            const user_type = document.querySelector("[data-user-type]").value;
            const user_seq = document.querySelector("[data-"+user_type+"-seq]").value;
            const user_name = document.querySelector("[data-user-name]").innerText;

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
        function memberInfoMailAuth(){
            const modal = document.getElementById('member_info_modal_mail_auth');
            const user_seq = document.querySelector("[data-student-seq]").value;
            const user_type = document.querySelector("[data-user-type]").value;
            const user_email = document.querySelector("[data-user-email]").innerText;
            const user_auth = modal.querySelector("[data-auth]").value;

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
                    document.querySelector("[data-user-email]").nextElementSibling.hidden = false;
                    document.querySelector("[data-auth-mail-btn]").hidden = true;

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
        function memberInfoModalPhoneAuth(){
            // data-auth-mail
            const user_phone = document.querySelector("[data-user-phone]").innerText;

            // 휴대폰 번호가 없을경우
            if(user_phone == ''){
                toast('휴대폰 번호가 없습니다.');
                return;
            }
            document.querySelector("[data-auth-phone]").innerText = user_phone;

           const myModal = new bootstrap.Modal(document.getElementById('member_info_modal_phone_auth'), {
                // keyboard: false
            });
            myModal.show();
        }


        // 휴대폰 인증번호 전송.
        function memberInfoSendPhoneAuth(){
            // 데이터
            const user_phone = document.querySelector("[data-user-phone]").innerText;
            const user_type = document.querySelector("[data-user-type]").value;
            const user_seq = document.querySelector("[data-"+user_type+"-seq]").value;
            const user_name = document.querySelector("[data-user-name]").innerText;

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

        //
        function memberInfoPhoneAuth(){
            const modal = document.querySelector('#member_info_modal_phone_auth');
            //전역
            const user_seq = document.querySelector("[data-student-seq]").value;
            const user_type = document.querySelector("[data-user-type]").value;
            const user_phone = document.querySelector("[data-user-phone]").innerText;
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

        // 프로필 이미지 모달 오픈
        function memberInfoModalProfile(){
            memberInfoModalProfileClear();
            const myModal = new bootstrap.Modal(document.getElementById('member_info_modal_profile'), {
                // keyboard: false
            });
            myModal.show();
        }

        // 프로필 이미지 모달 초기화
        function memberInfoModalProfileClear(){
            const modal = document.getElementById('member_info_modal_profile');
            modal.querySelector('.img_data').src = '';
            modal.querySelector('.img_data').alt = '';
            modal.querySelector('.img_data').style.height = '150px';
        }

        // 프로필 이미지 파일 변경
        function memberInfoUpdateImgFileChange(vthis, img_id) {
            const profile_img_file = document.querySelector('#' + img_id);
            const file = vthis.files[0];
            const reader = new FileReader();
            const size = file.size;

            //용량이 5m 이상이면 리턴
            if (size > 5*1024*1024) {
                toast('용량이 5MB 이상입니다.');
                vthis.value = '';
                profile_img_file.src = '';
                return false;
            }

            reader.onload = function() {
                profile_img_file.src = reader.result;
            }
            reader.readAsDataURL(file);
        }

        // 프로필 이미지 업로드
        function memberInfoUpdateProfileImg(){
            // 이미지가 없을경우 return
            const profile_img_file = document.querySelector('#member_info_file_edit');
            const profile_inp_file = document.querySelector('#member_info_imgfile_edit');
            if(profile_img_file.src == '' || profile_inp_file.value == ''){
                toast('이미지를 선택해주세요.');
                return;
            }

            // 데이터
            const user_type = document.querySelector("[data-user-type]").value;
            const user_seq = document.querySelector("[data-"+user_type+"-seq]").value;
            const user_img = profile_inp_file.files[0];

            const page = '/student/member/info/profile/upload';
            let formData = new FormData();
            formData.append('user_type', user_type);
            formData.append('user_seq', user_seq);
            formData.append('user_img', user_img);

            queryFormFetch(page, formData, function(result){
                if((result.resultCode||'') == 'success'){
                    toast('프로필 이미지가 업로드 되었습니다.');
                    // 모달 닫기 버튼 클릭
                    const modal = document.getElementById('member_info_modal_profile');
                    const btn_close = modal.querySelector('.btn_close');
                    btn_close.click();

                    // 이미지 변경 reulst.profile_img_path
                    const profile_img = document.querySelector('[data-profile-img]');
                    profile_img.src = '/storage/uploads/user_profile/'+user_type+'/'+result.profile_img_path;
                    document.querySelector('#layout_main_top2 .profile-img img').src = '/storage/uploads/user_profile/'+user_type+'/'+result.profile_img_path;
                    document.querySelector('#layout_main_top2 .profile-img img').style.width = '56px';
                    document.querySelector('#layout_main_top2 .profile-img img').style.height = '56px';
                }
                else{
                    toast('프로필 이미지 업로드에 실패하였습니다. 다시 시도해주세요.');
                }
            });
        }
        // 프로필 이미지 삭제.
        function memberInfoDeleteProfileImg(){
            // 프로필 이미지가 없을경우에는 리턴.
            const profile_img = document.querySelector('[data-profile-img]');
            const top_profile_img = document.querySelector('#layout_main_top2 .profile-img img');
            const img_src = profile_img.src;
            if(img_src == '' || img_src == null || img_src == location.href){
                toast('프로필 이미지가 없습니다.');
                return;
            }
            const user_type = document.querySelector("[data-user-type]").value;
            const user_seq = document.querySelector("[data-"+user_type+"-seq]").value;
            const page = '/student/member/info/delete/profile/img';
            const parameter = {
                user_type: user_type,
                user_seq: user_seq,
            };
            const msg = `<div class="text-sb-24px">프로필 이미지를 삭제하시겠습니까?</div>`;
            sAlert('', msg, 3, function(){
                queryFetch(page, parameter, function(result){
                    if((result.resultCode||'') == 'success'){
                        toast('프로필 이미지가 삭제되었습니다.');
                        // 모달 닫기 버튼 클릭
                        const modal = document.getElementById('member_info_modal_profile');
                        const btn_close = modal.querySelector('.btn_close');
                        btn_close.click();

                        // 이미지 비우기
                        profile_img.src = '';
                        if(top_profile_img?.src != ''){
                            top_profile_img.src = '';
                        }
                    }else{}
                });
            }, '삭제');
        }

        // 회원정보 수정 페이지
        function memberInfoUserInfoUpdatePage(){
            const main_user_info = document.querySelector('[data-main-user-info]');
            const main_user_info_update = document.querySelector('[data-main-user-info-update]');
            main_user_info.hidden = true;
            main_user_info_update.hidden = false;

            // 스크롤 애니메이션 최상단으로 이동.
            const scrollTop = document.querySelector('[data-main-user-info-update]').offsetTop;
            window.scrollTo({top: scrollTop, behavior: 'smooth'});
        }

        // 회원정보 페이지로 돌아가기.
        function memberInfoUserInfoPage(){
            // const main_user_info = document.querySelector('[data-main-user-info]');
            // const main_user_info_update = document.querySelector('[data-main-user-info-update]');
            // main_user_info.hidden = false;
            // main_user_info_update.hidden = true;

            // 수정 4개 모달 초기화
            memberInfoUpdateModalClear();
        }

        // 회원정보 수정 > 수정 버튼 클릭
        function memberInfoModalModalEdit(type){
            //초기화
            switch(type){
                case 'pw':break;
                case 'phone':break;
                case 'address':break;
                case 'mail':break;
            }
            const myModal = new bootstrap.Modal(document.getElementById('member_info_modal_update_'+type), {});
            myModal.show();
        }

        // 비밀번호 인증
        function memberInfoChkPw(){
            const user_type = document.querySelector("[data-user-type]").value;
            const user_seq = document.querySelector("[data-"+user_type+"-seq]").value;
            const user_pw = document.querySelector("[data-inp-current-pw]").value;
            const user_pw_tag = document.querySelector("[data-inp-current-pw]");
            const btn_pw_chk = document.querySelector("[data-btn-pw-chk]");

            // 전송
            const page = '/student/member/info/check/pw';
            const parameter = {
                user_type: user_type,
                user_seq: user_seq,
                user_pw: user_pw
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    toast('비밀번호가 일치합니다.');
                    // 모달 닫기 버튼 클릭
                    user_pw_tag.classList.add('text-success');
                    user_pw_tag.setAttribute('data', '1');
                    user_pw_tag.setAttribute('readonly', 'readonly');
                    btn_pw_chk.setAttribute('disabled', 'disabled');
                }
                else{
                    toast('비밀번호가 일치하지 않습니다. 다시 확인해주세요.');
                }
            });
        }
        // 비밀번호 업데이트
        function memberInfoUpdatePw(){
            const user_pw_tag = document.querySelector("[data-inp-current-pw]");
            const user_new_pw = document.querySelector("[data-inp-new-pw]").value;
            const user_new_pw_chk = document.querySelector("[data-inp-new-pw-chk]").value;

            // 체크 확인 data == 1 class = text-success 있는지 확인
            if(user_pw_tag.getAttribute('data') != '1' && user_pw_tag.classList.contains('text-success') == false){
                toast('기존 비밀번호를 확인해주세요.');
                return;
            }

            // 비밀번호 확인
            if(user_new_pw != user_new_pw_chk){
                toast('변경 비밀번호가 일치하지 않습니다. 다시 확인해주세요.');
                return;
            }

            const update_div_pw = document.querySelector("[data-main-update-div-pw]");
            // update_div_pw.innerText = user_new_pw;
            update_div_pw.setAttribute('current_pw', user_pw_tag.value);
            update_div_pw.dataset.chgPw = user_new_pw;

            // 모달 닫기 버튼 클릭
            const modal = document.getElementById('member_info_modal_update_pw');
            const btn_close = modal.querySelector('.btn_close');
            btn_close.click();
        }

        // 연락처 적용
        function memberInfoUpdatePhone(){
            const user_phone = document.querySelector("[data-inp-phone]").value;
            const update_div_phone = document.querySelector("[data-main-update-sp-phone]");
            if(update_div_phone.getAttribute('current_phone') == null)
                update_div_phone.setAttribute('current_phone', update_div_phone.innerText);
            update_div_phone.innerText = user_phone;

            // 모달닫기
            const modal = document.getElementById('member_info_modal_update_phone');
            const btn_close = modal.querySelector('.btn_close');
            btn_close.click();
        }

        // 주소 적용
        function memberInfoUpdateAddress(){
            const user_address = document.querySelector("[data-user-address]").value;
            const update_div_address = document.querySelector("[data-main-update-sp-address]");
            if(update_div_address.getAttribute('current_address') == null)
                update_div_address.setAttribute('current_address', update_div_address.innerText);
            update_div_address.innerText = user_address;

            // 모달닫기
            const modal = document.getElementById('member_info_modal_update_address');
            const btn_close = modal.querySelector('.btn_close');
            btn_close.click();
        }

        // 이메일 적용
        function memberInfoUpdateMail(){
            const user_mail = document.querySelector("[data-inp-mail]").value;
            const update_div_mail = document.querySelector("[data-main-update-sp-email]");
            if(update_div_mail.getAttribute('current_mail') == null)
                update_div_mail.setAttribute('current_mail', update_div_mail.innerText);
            update_div_mail.innerText = user_mail;

            // 모달닫기
            const modal = document.getElementById('member_info_modal_update_mail');
            const btn_close = modal.querySelector('.btn_close');
            btn_close.click();
        }

        // 변경사항 저장하기.
        function memberInfoUpdate(){
            const user_type = document.querySelector("[data-user-type]").value;
            const user_seq = document.querySelector("[data-"+user_type+"-seq]").value;

            const user_pw_tag = document.querySelector("[data-main-update-div-pw]");
            const user_pw = user_pw_tag.dataset.chgPw;
            const user_current_pw = document.querySelector("[data-main-update-div-pw]").getAttribute('current_pw');
            const user_phone_tag = document.querySelector("[data-main-update-sp-phone]");
            const user_phone = user_phone_tag.innerText;
            const user_address_tag = document.querySelector("[data-main-update-sp-address]")
            const user_address = user_address_tag.innerText;
            const user_mail_tag = document.querySelector("[data-main-update-sp-email]");
            const user_mail = user_mail_tag.innerText;

            let is_pw = true;
            let is_phone = true;
            let is_address = true;
            let is_mail = true;

            // 수정 체크
            if(user_pw_tag.getAttribute('current_pw') == null){ is_pw = false; }
            if(user_phone_tag.getAttribute('current_phone') == null){ is_phone = false; }
            if(user_address_tag.getAttribute('current_address') == null){ is_address = false; }
            if(user_mail_tag.getAttribute('current_mail') == null){ is_mail = false; }

            // 전송
            const page = '/student/member/info/update';
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
            sAlert('정보변경', '인증된 정보일경우 변경시 인증이 해제됩니다.', 2, function(){
                queryFetch(page, parameter, function(result){
                    if((result.resultCode||'') == 'success'){
                        toast('변경사항이 저장되었습니다.');

                        // 4개의 모달 초기화 / 취소시에도 초기화
                        // memberInfoUpdateModalClear();
                        // 새로고침
                        location.reload();
                    }
                    else{
                        toast('비밀번호 변경에 실패하였습니다. 다시 시도해주세요.');
                    }
                });
            });
        }

        // 수정하기 4개의 모달 초기화
        function memberInfoUpdateModalClear(){
            //
            location.reload();
            return;
            // 추후 새로고침이 아니라 스크립트로 진행해야 할 경우. 아래 소스 코드 수정후 사용.

            // 비밀번호 모달 초기화
            const modal_pw = document.getElementById('member_info_modal_update_pw');
            modal_pw.querySelector("[data-inp-current-pw]").value = '';
            modal_pw.querySelector("[data-inp-new-pw]").value = '';
            modal_pw.querySelector("[data-inp-new-pw-chk]").value = '';
            modal_pw.querySelector("[data-inp-current-pw]").classList.remove('text-success');
            modal_pw.querySelector("[data-inp-current-pw]").removeAttribute('data');
            modal_pw.querySelector("[data-btn-pw-chk]").removeAttribute('disabled');

            // 휴대폰 모달 초기화
            const modal_phone = document.getElementById('member_info_modal_update_phone');
            modal_phone.querySelector("[data-inp-phone]").value = '';

            // 주소 모달 초기화
            const modal_address = document.getElementById('member_info_modal_update_address');
            modal_address.querySelector("[data-user-address]").value = '';

            // 이메일 모달 초기화
            const modal_mail = document.getElementById('member_info_modal_update_mail');
            modal_mail.querySelector("[data-inp-mail]").value = '';

            // current_pw, current_phone, current_address, current_mail 로 변경후 삭제
            const update_div_pw = document.querySelector("[data-main-update-div-pw]");
            const update_div_phone = document.querySelector("[data-main-update-sp-phone]");
            const update_div_address = document.querySelector("[data-main-update-sp-address]");
            const update_div_mail = document.querySelector("[data-main-update-sp-email]");


            update_div_pw.innerText = '********';
            if(update_div_phone.getAttribute('current_phone') != null)
                update_div_phone.innerText = update_div_phone.getAttribute('current_phone');
            if(update_div_address.getAttribute('current_address') != null)
                update_div_address.innerText = update_div_address.getAttribute('current_address');
            if(update_div_mail.getAttribute('current_mail') != null)
                update_div_mail.innerText = update_div_mail.getAttribute('current_mail');

            update_div_pw.removeAttribute('current_pw');
            update_div_phone.removeAttribute('current_phone');
            update_div_address.removeAttribute('current_address');
            update_div_mail.removeAttribute('current_mail');
        }
</script>
@endsection
