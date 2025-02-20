@extends('layout.layout')

{{-- 타이틀 --}}
@section('head_title')
쪽지함
@endsection
@section('add_css_js')
<link href="{{ asset('css/reset.css?5') }}" rel="stylesheet">
@endsection

<!-- : 학부모 1:1 학습 상담. -->
<!-- : 학부모 1:1 학습 상담에서 자주묻는 질문 답변 채워넣기. -->
<!-- : 보낸이, 받는이 내가 아닌 상대방이 나오도록. -->
<!-- : 보낸이, 받는이 위와 같음. -->
<!-- : 선생님 이미지 변경기능. -->
<!-- : 쪽지 검색 기능. -->
<!-- : SMS 문자 발송. -->


{{-- 관리자 컨텐트 --}}
@section('layout_coutent')

<style>
    .form-check-input {
        zoom: 1.1;
    }

    .form-check-input:checked {
        background-color: black;
        border-color: black;
    }

    .tab_menu {
        background: white;
        color: #999999;
    }

    .tab_menu.active {
        background: #ffbd19;
        color: white;
    }

    .modal-710 {
        --bs-modal-width: 710px;
    }

    .modal-595 {
        --bs-modal-width: 595px;
    }

    .editable:empty:before {
        content: attr(data-placeholder);
        color: #999999;
    }

    input::-moz-placeholder,
    input::-webkit-input-placeholder,
    input:-ms-input-placeholder {
        color: #999999;
    }

    .search-note {
        border-radius: 7.5rem;
    }

    @media all and (max-width: 1400px) {
        .search-note {
            border-radius: 1.5rem;
            width: 100%;
        }

        .inquiry-btn {
            flex: 1 0 auto;
            justify-content: center !important;
            padding: 0rem 1rem 1rem 1rem;
        }
    }
    .primary-bg-mian-hover.active{
        background:#ffc746 !important;
        color:white !important;
    }

</style>

<script>
    // document.querySelector('#layout_div_content').style.backgroundColor = '#f5f5f5';
</script>
<input type="hidden" id="mess_login_type" value="{{$login_type}}">
<input type="hidden" id="mess_teach_seq" value="{{ session()->get('teach_seq') ?? $teachers->id ?? ''}}">
{{-- 팀장, 총매니저 화면. --}}
@if(!empty($is_leader) && $is_leader)
<div class="container-fluid row my-120 zoom_sm" id="teach_mess_div_leader">
    <div class="row row-cols-1 gap-2">
        <div class="text-center pb-1">
            <img src="{{ asset('images/mess_icon.svg') }}" width="101" height="100" class="col-auto">
        </div>
        <span class="col cfs-5 fw-semibold text-center">
            팀장 또는 관리선생님이신가요?
        </span>
        <span class="col cfs-6 ctext-gc1 text-center">
            쪽지를 보내시려면 아래 버튼을 클릭해주세요.
        </span>
        <div class="text-center">
            <button class="btn btn-primary-y cbtn-p-i rounded-pill cpy-2 cfs-6 mt-4" onclick="teachMessModalSendOpen()">
                새로운 쪽지보내기
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.74756 16.5483L13.5431 12.7814C13.9335 12.3939 13.9359 11.7633 13.5484 11.3728L9.74756 7.54346" stroke="white" stroke-width="2.5" stroke-miterlimit="10" stroke-linecap="round" />
                </svg>
            </button>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="row shadow-sm border-top col-11 mt-120 search-note">
            <div class="col py-4 px-5">
                <div class="ctexPt-gc1 cfs-6">소속을 선택해주세요.</div>
                <select class="form-select cfs-5 fw-semibold border-0 w-auto pe-5 ps-0 region_seq" onchange="teachMessTeamSelect();" style="--bs-form-select-bg-img: url( /images/dropdown_arrow_down2.svg); background-size: 32px 32px; ">
                    <option value="">소속 선택</option>
                    @if(!empty($regions))
                    @foreach($regions as $region)
                    <option value="{{ $region->id }}">{{ $region->region_name }}</option>
                    @endforeach
                    @endif
                </select>
            </div>
            <span class="col-auto d-flex align-items-center d-none d-sm-flex">
                <svg width="2" height="12" viewBox="0 0 2 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="2" height="12" fill="#E5E5E5" />
                </svg>
            </span>
            <div class="col py-4 px-5">
                <div class="ctext-gc1 cfs-6">소속 팀을 선택해주세요.</div>
                <select class="form-select cfs-5 fw-semibold border-0 w-auto pe-5 ps-0 team_code" onchange="teachMessTeacherSelect()" style="--bs-form-select-bg-img: url( /images/dropdown_arrow_down2.svg); background-size: 32px 32px; ">
                    <option value="">팀 선택</option>
                </select>
            </div>
            <span class="col-auto d-flex align-items-center d-none d-sm-flex">
                <svg width="2" height="12" viewBox="0 0 2 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="2" height="12" fill="#E5E5E5" />
                </svg>
            </span>
            <div class="col py-4 px-5">
                <div class="ctext-gc1 cfs-6">관리선생님을 선택해주세요.</div>
                <select class="form-select cfs-5 fw-semibold border-0 w-auto pe-5 ps-0 teach_seq" style="--bs-form-select-bg-img: url( /images/dropdown_arrow_down2.svg); background-size: 32px 32px; ">
                    <option value="">선생님 선택</option>
                </select>
            </div>
            <span class="col-auto align-items-center d-none d-xxl-flex">
                <svg width="2" height="12" viewBox="0 0 2 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="2" height="12" fill="#E5E5E5" />
                </svg>
            </span>
            <div class="col row align-items-center justify-content-end inquiry-btn">
                <button class="btn btn-primary-y cbtn-p cpy-2 rounded-pill col-auto cfs-6" onclick="teachMessSelectTeacher();">조회하기</button>
            </div>
        </div>
    </div>

</div>
@endif
{{-- 팀장, 총 매니저 일때는 숨김 처리. --}}
<div class="container-fluid row mx-0 pe-3 ps-3 mb-3 pt-5 position-relative zoom_sm" @if(!empty($is_leader) && $is_leader){{ 'hidden' }} @endif id="teach_mess_div_sel_teach">
    {{-- 상단 화면 --}}
    <div class="row pt-5 mt-4">
        <div class="col">
            <div class="h-center">
                <img src="{{ asset('images/mess_icon.svg') }}" alt="">
                <span class="fw-bold text-b-42px align-middle">1:1 쪽지함</span>
            </div>
            <div class="pt-2" {{ $login_type == 'parent' ? 'hidden':'' }}>
                <span class="fs-3 fw-medium">여러가지 학습을 한 눈에 쉽게 알아볼 수 있어요.</span>
            </div>
        </div>
        <div class="col row mx-0 justify-content-end align-items-start mt-4" {{ $login_type == 'parent' ? 'hidden':'' }}>
            <button id="teach_mess_btn_receive" class="col-auto h-center btn btn-lg tab_menu active border-0 rounded-5 cbtn-p-i btn-light" onclick="teachMessTabMenu(this) " type="all">
                <img src="{{ asset('images/mess_icon_on.svg') }}" alt="">
                <img src="{{ asset('images/mess_icon_off.svg') }}" alt="" hidden>
                <span class="align-middle text-m-24px scale-text-white-hover">받은 쪽지</span>
            </button>
            <button id="teach_mess_btn_send" class="col-auto h-center btn btn-lg tab_menu border-0 rounded-5 cbtn-p-i btn-light" onclick="teachMessTabMenu(this)" type="current">
                <img src="{{ asset('images/mess_icon_on.svg') }}" alt="" hidden>
                <img src="{{ asset('images/mess_icon_off.svg') }}" alt="">
                <span class="align-middle text-m-24px scale-text-white-hover">보낸 쪽지</span>
            </button>
        </div>
    </div>
    {{-- padding 120px --}}
    <div>
        <div class="py-lg-5"></div>
        <div class="pt-lg-4"></div>
    </div>
    {{-- 왼쪽 화면 --}}
    <div class="col-3 mt-4 pe-3">
        {{-- 선생님 / 학생 --}}
        @if($login_type == 'teacher' || $login_type == 'student')
        <div class="col-12 modal-shadow-style p-3" id="teach_mess_div_teach_info">
            <div class=" col pb-3">
                <div class="d-flex align-items-center justify-content-center position-relative mt-3">
                    <div class="border rounded-circle overflow-hidden cursor-pointer" style="width:120px;height:120px;display: flex; flex-direction: column; align-items: center;" onclick="teachMessModalChgTeachImg() ">
                        <!-- <img src="https://i.pinimg.com/originals/51/63/cb/5163cb671778d96ea5f70438d8079898.jpg" width="120"> -->
                        @if(!empty($teachers->profile_img_path))
                            <img src="/storage/uploads/user_profile/teacher/{{ $teachers->profile_img_path}}" id="member_info_file_edit"
                                class="rounded-circle cursor-pointer" width="145" style="width:100%"
                                onclick="document.querySelector('#member_info_imgfile_edit').click();return false;">
                        @else
                            <img src="{{ asset('images/yellow_human_icon.svg')}}" id="member_info_file_edit"
                                class="rounded-circle cursor-pointer" width="145"
                                onclick="document.querySelector('#member_info_imgfile_edit').click();return false;">
                        @endif
                        @if($login_type == 'teacher')
                        <input onchange="memberInfoUpdateImgFileChange(this, 'member_info_file_edit');"
                            type="file" id="member_info_imgfile_edit" accept="image/*" hidden>
                        @endif
                    </div>
                    @if($login_type == 'teacher')
                    <div class="position-absolute top-0 end-0 me-2 mb-2">
                        <button class="btn btn-sm btn-outline-secondary btn-sm rounded-4 border" onclick="teachMessTeachInitSave();">수정</button>
                    </div>
                    @endif
                </div>
                <input type="hidden" class="login_type" value="{{ $login_type }}">
                <input type="hidden" class="teach_seq" value="{{ session()->get('teach_seq') ?? $teachers->id ?? ''}}">
                <input type="hidden" class="student_seq" value="{{ session()->get('student_seq') }}">
                <input type="hidden" class="parent_seq" value="">
                <div class="text-center fs-4 gap-2 pt-4 fw-semibold">
                    <span class="teach_name text-sb-24px">{{ session()->get('teach_name') ?? $teachers->teach_name ?? ''}}</span>
                    <span class="group_name text-sb-24px">{{ session()->get('group_name') ?? $teachers->group_name ?? ''}}</span>
                </div>
                <div class="col d-flex align-items-center pt-4">
                    <div class="w-100 border-0 bg-light p-4 scale-text-gray_05 fw-medium text-center fs-5" placeholder="내용을 입력해주세요."
                    style="resize: none" rows="3" id="teach_mess_div_teach_intro" maxlength="200" @if($login_type=='teacher' ) contenteditable="true" @endif>
                        @if(!empty($teachers))
                        {{ $teachers->message_intro }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <div class="pt-3">
                    <button class="btn btn-primary-y btn-lg col-12 py-3" onclick="teachMessModalSendOpen();">
                        <span class="ps-2 fs-4 text-white">새로운 쪽지 보내기</span>
                    </button>
                </div>
            </div>
        </div>
        @elseif($login_type == 'parent')
        {{-- 학부모  --}}
        <aside>
            <div class="rounded-4 modal-shadow-style">
                <ul class="tab py-4 px-3 ">
                    <li class="mb-2">
                        <button onclick="teachMessPtTab(this)" data-pt-tab="1" class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover active">
                            <img src="{{ asset('images/mess_icon_on.svg') }}" alt="">
                            <img src="{{ asset('images/mess_icon_off.svg') }}" alt="" hidden>
                            받은 쪽지
                        </button>
                    </li>
                    <li class="">
                        <button onclick="teachMessPtTab(this)" data-pt-tab="2" class="btn h-center w-100 text-start text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover">
                            <img src="{{ asset('images/mess_icon_on.svg') }}" alt="" hidden>
                            <img src="{{ asset('images/mess_icon_off.svg') }}" alt="">
                            보낸 쪽지
                        </button>
                    </li>
                </ul>
            </div>

            <div class="bg-white mt-4">
                <button onclick="teachMessModalCheeringSendOpen();" class="btn all-center w-100 text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover rounded-2 border ">
                    자녀 응원메시지
                </button>
                <button onclick="teachMessOneOneModalOpen()" class="btn all-center w-100 text-b-20px py-4 px-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover rounded-2 border mt-2">
                    1:1 학습 상담
                </button>

            </div>
            <div hidden id="teach_mess_div_teach_info">
                <input type="hidden" class="teach_seq">
                <input type="hidden" class="student_seq">
                <input type="hidden" class="parent_seq" value="{{$parent->id}}">
                <input type="hidden" class="login_type" value="{{$login_type}}">
            </div>
        </aside>
        @endif
    </div>
    {{-- 오른쪽 화면 --}}
    <div class="col mt-4">

        <div class="d-flex justify-content-between align-items-center mb-3 pb-2">
            <div class="bg-white rounded-3">
                <button class="btn btn-outline-secondary col-auto px-4  rounded-3 border py-2 " onclick="teachMessSelDel();">
                    <span class="text-b-20px scale-text-gray_05 py-1">선택 삭제하기</span>
                </button>
            </div>
            <div class="btn-group border rounded-5 px-3 col-4 bg-white">
                <input onkeyup="if(event.keyCode == 13) teachMessMessengerSelect();"
                    class="form-control text-sb-20px scale-text-gray_05 border-0 my-2 " placeholder="쪽지를 검색해보세요." id="teach_mess_inp_messenger_search">
                <button class="btn p-0" onclick="teachMessMessengerSelect();">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21.4985 14.9482C21.4985 18.9977 18.2156 22.2805 14.166 22.2805C10.1163 22.2805 6.8335 18.9977 6.8335 14.9482C6.8335 10.8988 10.1163 7.61597 14.166 7.61597C18.2156 7.61597 21.4985 10.8988 21.4985 14.9482Z" stroke="#222222" stroke-width="3"></path>
                        <path d="M20.3384 19.9297L26.6663 25.5102" stroke="#222222" stroke-width="3" stroke-linecap="round"></path>
                    </svg>
                </button>
            </div>
        </div>

        {{-- 리스트 header --}}
        <div class="row col-12 rounded-4 shadow-sm-2 bg-white mx-0" id="teachmess_div_mes_head">
            <div class="row m-0 py-0 px-3 text-secondary" style="min-height: 80px">
                <div class="col-7 row m-0 p-0">
                    <div class="col-auto p-2 d-flex align-items-center" onclick="event.stopPropagation();">
                        <input type="checkbox" class="form-check-input col-auto chk" onclick="event.stopPropagation();" onchange="teachMessAllChkMessenger(this);">
                    </div>

                    <div data-div-messge-sender class="col-4 d-flex align-items-center text-b-20px scale-text-gray_05 justify-content-center">
                        보낸이
                    </div>
                    <div class="col d-flex justify-content-center flex-column">
                        <div class="text-center text-b-20px scale-text-gray_05">
                            내용
                        </div>
                    </div>
                </div>
                <div class="col row m-0 p-0 align-items-center">
                    <span class="col text-center text-b-20px scale-text-gray_05">시간</span>
                    <span class="col text-center text-b-20px scale-text-gray_05">구분</span>
                    <span class="col text-center text-b-20px scale-text-gray_05">-</span>
                </div>
            </div>
        </div>
        {{-- 리스트 --}}
        <div class="row col-12 mx-0" id="teachmess_div_mes_bundle">
            <div class="row copy_mes_list m-0 py-0 px-3 border-top cursor-pointer text-secondary" hidden onclick="teachMessModalReadAndAnswerOpen(this)" style="min-height: 56px;">
                <input type="hidden" class="messenger_seq">
                <div class="col-7 row m-0 p-0">
                    <div class="col-auto p-2 d-flex align-items-center">
                        <input type="checkbox" class="form-check-input col-auto chk" onclick="event.stopPropagation()">
                    </div>
                    {{-- <img claass="col-auto" style="width:80px;height:80px"> --}}
                    <div class="col-4 d-flex align-items-center justify-content-center gap-2">
                        <div class="overflow-hidden rounded-3 my-3" style="width:56px;height:56px">
                            <img class="profile_img_path" src="" width="56" height="56" onerror="this.src='/images/svg/profile_emtiy_avata.svg'">
                        </div>
                        <div class="col ps-1">
                            <div class="fw-bold text-black pb-1">
                                <span class="fw-bold user_name text-b-20px" data="#이름"></span> <span class="user_type" data="#타입"></span>
                            </div>
                            <div><span class="student_grade text-sb-18px scale-text-gray_05" data="#3학년"></span></div>
                        </div>
                    </div>
                    <div class="col d-flex justify-content-center flex-column">
                        <div class="message text-sb-20px scale-text-gray_05" style="line-height: 1.8rem;" data="#메시지"></div>
                    </div>
                </div>
                <div class="col row m-0 p-0 align-items-center">
                    <div class="col text-center text-sb-20px scale-text-gray_05 created_at" data="#등록날짜"></div>
                    <div class="col text-center text-sb-20px scale-text-gray_05 contact_type" data="#학승상담"></div>
                    <div class="col text-center">
                        <span class="text-white px-3 py-2 rounded-4 fs-6 status text-b-16px" data="#NEW"></span>
                    </div>
                </div>
            </div>
        </div>
        {{-- 쪽지함 없을대 --}}
        <div id="teachmess_div_mes_empty" class="text-center" hidden>
            <div class="text-secondary fs-4 p-4">쪽지함이 비어있습니다.</div>
        </div>

        {{-- 하단 버튼 --}}
        <div class="text-center p-2 pt-5">
            <div class="d-inline-block rounded-5 bg-white  mt-3">
                @if($login_type == 'teacher')
                <button class="btn btn-outline-light border-secondary-subtle col-auto px-4 py-2 rounded-5" onclick="teachMessSmsModalOpen();">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-secondary">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.02588 12.4991V20.4035C8.02588 20.7321 8.29227 20.9985 8.62092 20.9985H23.3813C23.71 20.9985 23.9763 20.7321 23.9763 20.4035V12.4963L16.7667 18.1037C16.3153 18.4548 15.6832 18.4548 15.2319 18.1037L8.02588 12.4991ZM22.3639 10.5833H9.63467L15.9993 15.5335L22.3639 10.5833ZM8.62092 8.08325C6.91158 8.08325 5.52588 9.46894 5.52588 11.1783V20.4035C5.52588 22.1129 6.91159 23.4985 8.62092 23.4985H23.3813C25.0907 23.4985 26.4763 22.1129 26.4763 20.4035V11.1783C26.4763 9.46896 25.0907 8.08325 23.3813 8.08325H8.62092Z" fill="#999999"></path>
                    </svg>
                    <span class="align-middle fs-4 text-secondary">SMS 문자 발송하기</span>
                </button>
                @endif
            </div>
        </div>
    </div>
</div>


{{-- 모달 / 쪽지 읽기 / 답장 --}}
<div class="modal fade zoom_sm" id="teach_mess_modal_read" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog  modal-dialog-centered modal-595">
        <div class="modal-content">
            <div class="modal-header bg-primary-y text-white py-4">
                <div class="modal-title cfs-5 h-center text-white">
                    <img src="{{ asset('images/mess_icon_on.svg') }}" width="32">
                    <span class="message_title ps-1 text-white"></span>
                </div>
                <button type="button" data-bs-dismiss="modal" aria-label="Close" class="btn text-white modal_close" onclick="">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 10L10.0003 21.9997" stroke="white" stroke-width="3.33333" stroke-linecap="round" />
                        <path d="M10 10L21.9997 21.9997" stroke="white" stroke-width="3.33333" stroke-linecap="round" />
                    </svg>
                </button>
            </div>
            <div class="modal-body bg-primary-bg" style="padding-bottom: 200px;">
                <input type="hidden" class="messenger_seq">
                <div class="text-center pt-3 pb-1">
                    <span class="created_at rounded-5 bg-primary-y text-white border-0 p-1 px-3 cfs-9"></span>
                </div>

                <div class="row mt-4 px-4">
                    <div class="border rounded-circle overflow-hidden cursor-pointer col-auto p-0" style="width:55px;height:55px;"">
                            <img src="/images/svg/profile_emtiy_avata.svg" width="55" height="55" class="profile_img_path">
                    </div>
                    <div class="col">
                        <div class="row m-0">
                            <div class="col message w-100 form-control p-3 rounded-4" style="border-top-left-radius: 5px !important;"></div>
                            <div class="col-lg-3 pe-0">
                                <div class="row justify-content-end flex-column h-100">
                                    <span class="contact_type text-primary-y cfs-9 fw-light pe-0"></span>
                                    <span class="created_at_time text-secondary cfs-9 fw-light pe-0"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-2 px-4 div_comment ">
                    <div class="text-center pt-3 pb-1">
                        <span class="updated_at rounded-5 bg-primary-y text-white border-0 p-1 px-3 cfs-9"></span>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-3">
                            <div class="row justify-content-end flex-column h-100">
                                <span class="comment_status text-primary-y cfs-9 fw-light"></span>
                                <span class="updated_at_time text-secondary cfs-9 fw-light"></span>
                            </div>
                        </div>
                        <div class="col comment w-100 form-control  p-3 rounded-4 bg-primary-light" style="border-top-right-radius: 5px !important;"></div>
                    </div>

                </div>
            </div>
            <div class="modal-footer align-items-end">
                <div contenteditable="true" class="col form-control cfs-6 editable shadow-none div_send_comment border-0" data-placeholder="쪽지를 보내세요."></div>
                <button type="button" class="btn col-auto" onclick="teachMessModalReadAndAnswerSend()">
                    <img src="{{ asset('images/send_message_icon.svg') }}" alt="">
                </button>
            </div>
        </div>
    </div>
</div>

{{-- 모달 / 쪽지보내기 --}}
<div class="modal fade zoom_sm" id="teach_mess_modal_send" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog  modal-dialog-centered modal-710">
        <div class="modal-content py-2">
            <div class="modal-header border-0 p-4">
                <h1 class="modal-title fs-5 fw-semibold h-center">
                    <img src="{{ asset('images/mess_icon.svg') }}" width="32">
                    <span class="text-sb-24px ps-1">{{ $login_type == 'student' ? '담당선생님께 ':'' }}쪽지 보내기</span>
                </h1>

                <button type="button" class="btn type_send p-0" data-bs-dismiss="modal" aria-label="Close" hidden>
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 10L10.0003 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round" />
                        <path d="M10 10L21.9997 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round" />
                    </svg>
                </button>
                @if($login_type == 'teacher')
                <button type="button" class="btn btn-light btn-white border text-secondary fs-6 py-1 type_search btn_search_user_type" onclick="teachMessModalSearchUserTypeToggle(this);" data="student">학부모 보기</button>
                @else
                <button type="button" class="btn p-0" data-bs-dismiss="modal" aria-label="Close">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 10L10.0003 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round" />
                        <path d="M10 10L21.9997 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round" />
                    </svg>
                </button>
                @endif

            </div>
            <div class="modal-body">
                <div class="row px-4">
                    {{-- 왼쪽 --}}
                    <div class="col div_search px-2" {{ $login_type == 'teacher' ? '':'hidden' }}>
                        <div class="text-end">
                            {{-- <button class="btn btn-outline-secondary btn-sm" onclick="teachMessModalSendAddStudentClear()">선택 초기화</button> --}}
                        </div>

                        <div>
                            <div class="d-flex mt-2 gap-2">
                                <div class="col-3">
                                    <select class="form-select form-select-sm search_type fs-5 p-3">
                                        <option value="student_name">이름</option>
                                        <option value="grade">학년</option>
                                        <option value="class">반</option>
                                    </select>
                                </div>
                                <div class="div_dropdown_group col btn-group border rounded-3">
                                    <input type="text" class="form-control form-control-sm fs-5 border-0 rounded-3 search_str ps-3 dropdown-toggle shadow-none" placeholder="검색어를 입력하세요." {{-- data-bs-toggle="dropdown" aria-expanded="false" --}} onkeyup="if(event.keyCode == 13) teachMessStudentSelect();">
                                    <button class="btn " onclick="teachMessStudentSelect();">
                                        <svg width="24" height="24" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M21.4985 14.9482C21.4985 18.9977 18.2156 22.2805 14.166 22.2805C10.1163 22.2805 6.8335 18.9977 6.8335 14.9482C6.8335 10.8988 10.1163 7.61597 14.166 7.61597C18.2156 7.61597 21.4985 10.8988 21.4985 14.9482Z" stroke="#222222" stroke-width="3"></path>
                                            <path d="M20.3384 19.9297L26.6663 25.5102" stroke="#222222" stroke-width="3" stroke-linecap="round"></path>
                                        </svg>
                                    </button>
                                    <ul class="dropdown-menu w-100 rounded-0 rounded-bottom-3" style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 64px);">
                                        <div class="div_dropdown_bundle overflow-auto" style="max-height: 200px;">
                                            <li <li class="copy_div_dropdown_list row m-0 justify-content-betw p-2 pb-3" hidden>
                                                <a class="col link-dark text-decoration-none fw-semibold student_name"></a>
                                                <button class="col-auto btn btn-light rounded-circle p-0 me-2 d-flex align-items-center jsutify-content-center" style="width: 24px; height: 24px;" onclick="event.stopPropagation();teachMessModalDropDownSelUser(this)">
                                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <circle cx="12" cy="12" r="12" fill="#F9F9F9" />
                                                        <path d="M12 7.75732V16.2424" stroke="#999999" stroke-width="2" stroke-linecap="round" />
                                                        <path d="M16.2424 12H7.75736" stroke="#999999" stroke-width="2" stroke-linecap="round" />
                                                    </svg>
                                                </button>

                                                <input type="hidden" class="student_seq">
                                                <input type="hidden" class="grade_name">
                                            </li>
                                        </div>
                                        <div class="border-top row justify-content-between pt-2 px-2 m-0">
                                            <div class="col">
                                                <a class="link-secondary text-decoration-none cursor-pointer" onclick="teachMessModalDropDownSelAllUser();">전체 추가하기</a>
                                            </div>
                                            <div class="col text-end">
                                                <a class="link-secondary text-decoration-none cursor-pointer" onclick="teachMessModalDropDownToggle(false)">닫기</a>
                                            </div>

                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button class="btn btn-sm ms-2 text-dark btn-light btn-white cbtn-p fs-5 fw-semibold border mt-4 rounded-3" onclick="teachMessModalSendAddStudentAll();">전체 추가하기</button>
                        </div>

                        <div class="mt-4" style="font-size: 1.15rem">
                            <table class="table align-middle text-center">
                                <thead class="">
                                    <td class="text-secondary" style="width:30px;" onclick="event.stopPropagation();this.querySelector('input').click();">
                                        {{-- <input type="checkbox" class="form-check-input" onclick="teachMessModalSendCheckAll(this);"> --}}
                                        -
                                    </td>
                                    <td class="text-secondary">팀</td>
                                    <td class="text-secondary">선생님</td>
                                    <td class="text-secondary use_student">학생</td>
                                    <td class="text-secondary use_parent" hidden>학부모</td>
                                    <td class="text-secondary">학년</td>
                                    <td class="text-secondary">반</td>
                                    {{-- <td></td> --}}
                                </thead>
                                <tbody class="tby_st">
                                    <tr class="copy_tr_st" hidden>
                                        <td data="#checkbox" onclick="event.stopPropagation();this.querySelector('input').click();">
                                            <input type="checkbox" class="form-check-input chk" style="zoom: 1" onclick="event.stopPropagation()">
                                        </td>
                                        <td class="team_name text-secondary" data="#팀"></td>
                                        <td class="teach_name text-secondary" data="#선생님"></td>
                                        <td class="parent_name use_parent text-secondary" data="#학부모이름" hidden></td>
                                        <td class="student_name use_student text-secondary" data="#학생이름"></td>
                                        <td class="grade_name text-secondary" data="#학년"></td>
                                        <td class="student_class text-secondary" data="#반"></td>
                                        {{-- <td>
                                                <button class="btn btn-sm btn-light bg-primary-subtle" onclick="teachMessModalSendAddStudentOne(this);">+</button>
                                            </td> --}}
                                        <input type="hidden" class="student_seq">
                                        <input type="hidden" class="parent_seq">
                                    </tr>
                                </tbody>
                            </table>
                            {{-- 검색된 학생이 없습니다. --}}
                            <div class="text-center div_empty">
                                <span class="text-secondary">검색된 학생이 없습니다.</span>
                            </div>
                            <div class="text-center mt-4">
                                <button class="btn btn-light btn-white text-secondary btn-sm btn_del_st border fs-5 cbtn-p rounded-3" onclick="teachMessModalSendSelDelStudent();" hidden>선택 삭제하기</button>
                                <button class="btn btn-light btn-white text-black btn-sm btn_add_st border fs-5 cbtn-p rounded-3" onclick="teachMessModalSendAddStudent();" hidden>선택 추가하기</button>
                            </div>
                        </div>

                        <div class="row div_sel_st_btm mt-4 mb-5 bg-light px-5 py-4 m-0 row-cols-3">
                            <div class="col row m-0 copy_div_sel_st sel_st justify-content-betwwen p-0 cfs-7 ctext-bc0" hidden>
                                <span class="col m-0 p-0">
                                    <span class="student_name"></span>
                                    (<span class="grade_name"></span>)
                                    <span class="after_str"></span>
                                </span>
                                <button class="col-auto btn p-0 m-0" onclick="teachMessModalSendDelStudent(this)">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.49985 7.5L16.4996 16.4998" stroke="#999999" stroke-width="2.5" stroke-linecap="round" />
                                        <path d="M16.4998 7.5L7.49997 16.4998" stroke="#999999" stroke-width="2.5" stroke-linecap="round" />
                                    </svg>
                                </button>
                                <input type="hidden" class="student_seq">
                                <input type="hidden" class="parent_seq">
                                <input type="hidden" class="user_type">
                            </div>
                        </div>

                        <div class="row gap-2 p-0 m-0">
                            <button type="button" class="col modal_close btn btn-light text-secondary fs-4 py-3 rounded-3" data-bs-dismiss="modal" onclick="">닫기</button>
                            <button type="button" class="col modal_next btn btn-primary-y fs-4 py-3 rounded-3" onclick="teachMessModalNext();">
                                다음
                            </button>
                        </div>
                    </div>
                    {{-- 오른쪽 --}}
                    <div class="col div_send px-2" {{ $login_type == 'teacher' ? 'hidden' : ''}}>
                        <div class="mt-3">
                            <div class="cfs-6 fw-semibold ctext-bc0">쪽지 유형을 선택해주세요.</div>
                            <div class="cfs-6 my-2">
                                <select class="form-select fs-5 p-3 fw-medium ctext-bc0 contact_type" id="teach_mess_sel_contact" style="background-size: 24px 24px;--bs-form-select-bg-img:url(/images/dropdown_arrow_down.svg)">
                                    @if(!empty($contact_codes))
                                    @foreach($contact_codes as $contact_code)
                                    <option value="{{ $contact_code->id }}">{{ $contact_code->code_name }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div>
                            <div class="mt-5">
                                <span class="sel_student_names fs-5 fw-semibold ctext-bc0"></span>
                                <span class="sel_student_names_after fw-semibold ctext-bc0 fs-5 " hidden>에게</span>
                                {{-- <span class="text-secondary fs-5">보내기</span> --}}

                            </div>
                        </div>
                        <div class="mt-3">
                            <div contenteditable="true" class="form-control text_send_message fs-5 p-4" style="min-height: 320px;" placeholder="내용을 입력해주세요."></div>
                        </div>

                        <div class="row gap-2 p-0 m-0 mt-5">
                            <button type="button" class="col modal_next btn btn-primary-y fs-4 py-3 rounded-3" onclick="teachMessModalSend(this);">
                                <div class="sp_loding spinner-border text-light spinner-border-sm align-middle mb-1 me-2" role="status" hidden></div>
                                쪽지 보내기
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer" hidden>
            </div>
        </div>
    </div>
</div>

{{-- 모달 / 응원 메시지 (학부모 용) --}}
<div class="modal fade zoom_sm" id="teach_mess_modal_cheering_send" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog  modal-dialog-centered modal-710">
        <div class="modal-content py-2">
            <div class="modal-header border-0 p-4">
                <h1 class="modal-title fs-5 fw-semibold h-center">
                    <img src="{{ asset('images/cheering_icon.svg') }}" width="32">
                    <span class="text-sb-24px ps-1">응원 메시지</span>
                </h1>

                <button type="button" class="btn type_send p-0" data-bs-dismiss="modal" aria-label="Close">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 10L10.0003 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round" />
                        <path d="M10 10L21.9997 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round" />
                    </svg>
                </button>

            </div>
            <div class="modal-body">
                <div class="row px-4">
                    {{-- 왼쪽 --}}
                    <div class="col div_search px-2">
                        <div class="mt-4" style="font-size: 1.15rem">
                            <table class="table align-middle text-center">
                                <thead class="">
                                    <td class="text-secondary use_student">이름</td>
                                    <td class="text-secondary">학년</td>
                                    <td class="text-secondary">반</td>
                                    <td class="text-secondary">자녀추가</td>
                                </thead>
                                <tbody class="tby_st">
                                    @if(!empty($students))
                                    @foreach($students as $student)
                                    <tr class="tr_st">
                                        <td class="student_name use_student text-secondary" data="#학생이름">{{ $student->student_name}}</td>
                                        <td class="grade_name text-secondary" data="#학년">{{ $student->grade_name}}</td>
                                        <td class="student_class text-secondary" data="#반">{{ $student->class_name }}</td>
                                        <td>
                                            <button type="button" onclick="teachMessCheeringChildAdd(this);" class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3">추가하기</button>
                                        </td>
                                        <input type="hidden" class="student_seq" value="{{$student->id}}">
                                        <input type="hidden" class="parent_seq" vlaue="{{$student->parent_seq}}">
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="row div_sel_st_btm mt-4 mb-5 bg-light px-5 py-4 m-0 row-cols-3">
                            <div class="col row m-0 copy_div_sel_st sel_st justify-content-betwwen p-0 cfs-7 ctext-bc0" hidden>
                                <span class="col m-0">
                                    <span class="student_name"></span>
                                    (<span class="grade_name"></span>)
                                    <span class="after_str"></span>
                                </span>
                                <button class="col-auto btn p-0 m-0" onclick="teachMessModalCheeringSendDelStudent(this)">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M7.49985 7.5L16.4996 16.4998" stroke="#999999" stroke-width="2.5" stroke-linecap="round" />
                                        <path d="M16.4998 7.5L7.49997 16.4998" stroke="#999999" stroke-width="2.5" stroke-linecap="round" />
                                    </svg>
                                </button>
                                <input type="hidden" class="student_seq">
                                <input type="hidden" class="parent_seq">
                                <input type="hidden" class="user_type">
                            </div>
                        </div>

                        <div class="row gap-2 p-0 m-0">
                            <button type="button" class="col modal_close btn btn-light text-secondary fs-4 py-3 rounded-3" data-bs-dismiss="modal" onclick="">닫기</button>
                            <button type="button" class="col modal_next btn btn-primary-y fs-4 py-3 rounded-3" onclick="teachMessModalNext(true);">
                                다음
                            </button>
                        </div>
                    </div>
                    {{-- 오른쪽 --}}
                    <div class="col div_send px-2" hidden>
                        <div class="mt-3">
                            <div class="cfs-6 fw-semibold ctext-bc0">유형을 선택해주세요.</div>
                            <div class="cfs-6 my-2">
                                @if(!empty($cheering_codes))
                                @foreach($cheering_codes as $idx => $cheering_code)
                                <div class="border rounded-2 p-4 h-center gap-2 mb-2">
                                    {{-- :radio --}}
                                    <label class="radio">
                                        <input type="radio" value="{{$cheering_code->id}}" name="cheering_send_type" data-cheering-type="{{$cheering_code->function_code}}" {{ $idx == 0 ? 'checked':'' }}>
                                        <span class=""></span>
                                    </label>
                                    <span class="text-sb-20px cursor-pointer" onclick="this.closest('div').querySelector('span').click()">{{$cheering_code->code_name}}(으)로 보내기</span>
                                </div>
                                @endforeach
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="mt-5">
                                <span class="sel_student_names fs-5 fw-semibold ctext-bc0"></span>
                                <span class="sel_student_names_after fw-semibold ctext-bc0 fs-5 " hidden>에게</span>
                                <span class="text-secondary fs-5"> 보낼 내용</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div contenteditable="true" class="form-control text_send_message fs-5 p-4" style="min-height: 320px;" placeholder="내용을 입력해주세요."></div>
                        </div>

                        <div class="row gap-2 p-0 m-0 mt-5">
                            <button type="button" class="col modal_next btn btn-primary-y fs-4 py-3 rounded-3" onclick="teachMessModalSend(this, true);">
                                <div class="sp_loding spinner-border text-light spinner-border-sm align-middle mb-1 me-2" role="status" hidden></div>
                                쪽지 보내기
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer" hidden>
            </div>
        </div>
    </div>
</div>

{{-- 모달 / 1:1 상담 (학부모용) --}}
<div class="modal fade zoom_sm" id="teach_mess_modal_one_and_one" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog  modal-dialog-centered modal-710">
        <div class="modal-content py-2">
            <div class="modal-header border-0 p-4">
                <h1 class="modal-title fs-5 fw-semibold h-center">
                    <img src="{{ asset('images/robot_icon.svg') }}" width="32">
                    <span class="text-sb-24px ps-1">1:1 상담</span>
                </h1>

                <button type="button" class="btn type_send p-0 modal_close" data-bs-dismiss="modal" aria-label="Close">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22 10L10.0003 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round" />
                        <path d="M10 10L21.9997 21.9997" stroke="#222222" stroke-width="3.33333" stroke-linecap="round" />
                    </svg>
                </button>

            </div>
            <div class="modal-body">
                <div class="row px-4">
                    <div class="col div_send px-2">
                        <div class="mt-3">
                            <div class="cfs-6 fw-semibold ctext-bc0">자녀를 선택해주세요.</div>
                            <div class="cfs-6 my-2">
                                <select class="form-select fs-5 p-3 fw-medium ctext-bc0" id="teach_mess_sel_child" onchange="teachMessChgChildSelect(this)" style="background-size: 24px 24px;--bs-form-select-bg-img:url(/images/dropdown_arrow_down.svg)">
                                    @if(!empty($students))
                                    @foreach($students as $student)
                                    <option value="{{ $student->id }}" data-main-code="{{$student->main_code}}" data-teach-seq="{{$student->teach_seq}}">{{ $student->student_name.'('.$student->grade_name.')' }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="cfs-6 fw-semibold ctext-bc0">유형을 선택해주세요.</div>
                            <div class="cfs-6 my-2">
                                <select class="form-select fs-5 p-3 fw-medium ctext-bc0 contact_type" id="teach_mess_sel_contact" style="background-size: 24px 24px;--bs-form-select-bg-img:url(/images/dropdown_arrow_down.svg)">
                                </select>
                            </div>
                            <div class="cfs-6 fw-semibold ctext-bc0">자주 묻는 질문</div>
                            <div class="cfs-6 my-2 border rounded py-4" data-qna>
                                <div class="text-sb-20px row mx-0 jsutify-content-between">
                                    <span class="col">이용권은 얼마인가요?</span>
                                    <div class="col-auto h-center"><img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="24" onclick="teachMessScriptInfoToggle(this);"></div>
                                </div>
                                <div class="script_info px-3 text-sb-18px scale-text-gray_05 mt-2" hidden>이용권은 ???원 입니다.</div>
                            </div>
                            <div class="cfs-6 my-2 border rounded py-4" data-qna>
                                <div class="text-sb-20px row mx-0 jsutify-content-between">
                                    <span class="col">형제와 같이 사용할 수 있나요?</span>
                                    <div class="col-auto h-center"><img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="24" onclick="teachMessScriptInfoToggle(this);"></div>
                                </div>
                                <div class="script_info px-3 text-sb-18px scale-text-gray_05 mt-2" hidden>네 충분히 가능합니다.</div>
                            </div>
                            <div class="cfs-6 my-2 border rounded py-4" data-qna>
                                <div class="text-sb-20px row mx-0 jsutify-content-between">
                                    <span class="col">저가형과 고가형의 차이점은 뭔가요?</span>
                                    <div class="col-auto h-center"><img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="24" onclick="teachMessScriptInfoToggle(this);"></div>
                                </div>
                                <div class="script_info px-3 text-sb-18px scale-text-gray_05 mt-2" hidden>차이가 있습니다.</div>
                            </div>
                        </div>
                        <div>
                            <div class="mt-5">
                                <span class="sel_student_names fs-5 fw-semibold ctext-bc0"></span>
                                <span class="sel_student_names_after fw-semibold ctext-bc0 fs-5 " hidden>에게</span>
                                <span class="cfs-6 fw-semibold ctext-bc0">문의 내용</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div contenteditable="true" class="form-control text_send_message fs-5 p-4" style="min-height: 320px;" placeholder="내용을 입력해주세요."></div>
                        </div>
                        <div class="text-sb-20px text-danger p-3">※ 문의 내용을 검토 후, 영업일 기준 2일 이내 쪽지로 답변드립니다.</div>

                        <div class="row gap-2 p-0 m-0 mt-5">
                            <button type="button" class="col modal_next btn btn-primary-y fs-4 py-3 rounded-3" onclick="teachMessModalSendOneAOne();">
                                <div class="sp_loding spinner-border text-light spinner-border-sm align-middle mb-1 me-2" role="status" hidden></div>
                                쪽지 보내기
                            </button>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer" hidden>
            </div>
        </div>
    </div>
</div>

 {{-- 모달 / 상담일정 알림 발송 / 여기 안에 select_member 배열 있으므로, 확인 --}}
 @include('admin.admin_alarm_detail')

@if($login_type == 'parent')
<script>
    const json_contact_codes = @json($contact_codes_pt);
</script>
@endif
<script>
    teachMessMessengerSelect();
    initContentEditablePlaceholder('.text_send_message');
    initContentEditablePlaceholder('#teach_mess_div_teach_intro');

    // TAB 메뉴 클릭
    function teachMessTabMenu(vthis) {
        const tab_menu = document.querySelectorAll('.tab_menu');
        tab_menu.forEach(tab_menu => {
            tab_menu.classList.remove('active');
            tab_menu.querySelector('img:nth-child(1)').hidden = true;
            tab_menu.querySelector('img:nth-child(2)').hidden = false;
        });
        vthis.classList.add('active');
        vthis.querySelector('img:nth-child(1)').hidden = false;
        vthis.querySelector('img:nth-child(2)').hidden = true;

        // 이후 보낸쪽지 , 받은쪽지 리스트 불러오기.
        teachMessMessengerSelect();
    }

    // 모달 / 쪽지보내기 / 학생 검색
    function teachMessStudentSelect() {
        const modal = document.querySelector('#teach_mess_modal_send');
        const search_type = modal.querySelector('.search_type').value;
        const search_str = modal.querySelector('.search_str').value;
        const teach_seq = document.querySelector('#teach_mess_div_teach_info .teach_seq').value;
        const user_type = modal.querySelector('.btn_search_user_type').getAttribute('data');

        const page = "/teacher/messenger/student/select";
        const parameter = {
            search_type: search_type,
            search_str: search_str,
            teach_seq: teach_seq,
            user_type: user_type
        };
        queryFetch(page, parameter, function(result) {
            //초기화
            // const tby_st = modal.querySelector('.div_dropdown_bundle');
            // const copy_tr_st = tby_st.querySelector('.copy_div_dropdown_list').cloneNode(true);
            const tby_st = modal.querySelector('.tby_st');
            const copy_tr_st = tby_st.querySelector('.copy_tr_st').cloneNode(true);
            tby_st.innerHTML = '';
            tby_st.appendChild(copy_tr_st);

            if ((result.resultCode || '') == 'success') {
                let i = 0;
                result.students.forEach(student => {
                    i++;
                    const tr = copy_tr_st.cloneNode(true);
                    tr.hidden = false;
                    tr.classList.remove('copy_tr_st');
                    tr.classList.add('tr_st');
                    tr.querySelector('.team_name').innerText = student.team_name;
                    tr.querySelector('.teach_name').innerText = student.teach_name;
                    tr.querySelector('.student_name').innerText = student.student_name;
                    tr.querySelector('.parent_name').innerText = student.parent_name;
                    tr.querySelector('.grade_name').innerText = student.grade_name;
                    tr.querySelector('.student_seq').value = student.id;
                    tr.querySelector('.parent_seq').value = student.parent_seq;
                    //반?
                    //[추가 코드]
                    tby_st.appendChild(tr);
                });
            }
            // if(tby_st.querySelectorAll('.div_dropdown_list').length < 1){
            //     toast('검색된 학생이 없습니다.');
            //     teachMessModalDropDownToggle(false);
            // }else{
            //     teachMessModalDropDownToggle(true);
            // }
            // empty와 add, del 버튼 보여주기
            teachMessModalTrStChk();
        });
    }

    // 모달 / 쪽지보내기 / 학생 전체 선택
    function teachMessModalSendCheckAll(vthis) {
        event.stopPropagation();
        const modal = document.querySelector('#teach_mess_modal_send');
        const chk = modal.querySelectorAll('.tr_st .chk');
        chk.forEach(chk => {
            chk.checked = vthis.checked;
        });
    }

    // 모달 / 쪽지보내기 / 학생 선택 취소(삭제)
    function teachMessModalSendSelDelStudent() {
        // 학생이 선택이 되어있는지 확인
        const modal = document.querySelector('#teach_mess_modal_send');
        const chk = modal.querySelectorAll('.tr_st .chk:checked');
        if (chk.length < 1) {
            sAlert('', '선택된 학생이 없습니다.', 4);
            return;
        }

        // 체크된학생의 student_seq를 가져와서
        // .div_sel_st_btm 에서 student_seq 와 같은 div_sel_st를 찾아서 button onclick 실행
        chk.forEach(chk => {
            const student_seq = chk.closest('.tr_st').querySelector('.student_seq').value;
            const div_sel_st = modal.querySelector('.div_sel_st_btm').querySelector('.div_sel_st .student_seq[value="'+student_seq+'"]');
            if(div_sel_st != null){
                div_sel_st.closest('.div_sel_st').querySelector('button').click();
            }
            // tr remove()
            // chk.closest('.tr_st').remove();
        });


    }

    // 모달 / 쪽지보내기 / 학생 선택 추가
    function teachMessModalSendAddStudent() {
        // 학생이 선택이 되어있는지 확인
        const modal = document.querySelector('#teach_mess_modal_send');
        const chk = modal.querySelectorAll('.tr_st .chk:checked');
        if (chk.length < 1) {
            sAlert('', '선택된 학생이 없습니다.', 4);
            return;
        }

        //div_sel_st_btm, copy_div_sel_st 에 학생 추가
        //먼저 초기화후
        const div_sel_st_btm = modal.querySelector('.div_sel_st_btm');
        const copy_div_sel_st = div_sel_st_btm.querySelector('.copy_div_sel_st').cloneNode(true);
        // div_sel_st_btm.innerHTML = '';
        // div_sel_st_btm.appendChild(copy_div_sel_st);

        //추가
        //student_name = 학생이름 + (학년)
        const user_type = modal.querySelector('.btn_search_user_type').getAttribute('data');

        chk.forEach(chk => {
            const tr = chk.closest('.tr_st');
            const copy = copy_div_sel_st.cloneNode(true);
            const parent_seq = tr.querySelector('.parent_seq').value;
            const student_seq = tr.querySelector('.student_seq').value;

            //user_type = student 이면 student_seq, parent 이면 parent_seq 값이 있는지 tr과 div_sel_st 를 비교해서 추가
            const already_tag = document.querySelectorAll('.' + user_type + '_seq' + (user_type == 'student' ? student_seq : parent_seq));
            const already_cnt = already_tag.length;
            if (already_cnt > 0) {
                return;
            }
            copy.hidden = false;
            copy.classList.remove('copy_div_sel_st');
            copy.classList.add('div_sel_st');
            const student_name = tr.querySelector('.student_name').innerText;
            const grade_name = tr.querySelector('.grade_name').innerText;
            copy.querySelector('.student_name').innerText = student_name;
            copy.querySelector('.grade_name').innerText = (user_type == 'student' ? grade_name : '학부모');
            copy.querySelector('.student_seq').value = (user_type == 'student' ? student_seq : '');
            copy.querySelector('.parent_seq').value = (user_type == 'student' ? '' : parent_seq);
            copy.querySelector('.user_type').value = user_type;
            copy.classList.add(user_type + '_seq' + (user_type == 'student' ? student_seq : parent_seq));
            div_sel_st_btm.appendChild(copy);
        });
        //sel_student_names 에 학생이름(학년) 외 n명 추가
        teachMessStAddAfterStr();
        // div_sel_st_btm.hidden = false;
    }

    function teachMessStAddAfterStr(is_cheering) {
        let modal = document.querySelector('#teach_mess_modal_send');
        if (is_cheering) modal = document.querySelector('#teach_mess_modal_cheering_send');

        const tag_sel_students = document.querySelectorAll('.div_sel_st');
        if (tag_sel_students.length < 1) {
            return;
        }
        const sel_student_names = modal.querySelector('.sel_student_names');
        const student_name = tag_sel_students[0].querySelector('.student_name').innerText;
        const grade_name = '(' + tag_sel_students[0].querySelector('.grade_name').innerText + ')';
        const after_str = ' 외 ' + (tag_sel_students.length - 1) + '명';
        sel_student_names.innerText = student_name + grade_name + (tag_sel_students.length - 1 > 0 ? after_str : '');
        modal.querySelector('.sel_student_names_after').hidden = false;
    }

    // 모달 / 쪽지보내기 / 학생 선택 한명 추가
    function teachMessModalSendAddStudentOne(vthis) {
        const sel_tr = vthis.closest('tr');

        // teachMessModalSendAddStudent();

        const modal = document.querySelector('#teach_mess_modal_send');
        const div_sel_st_btm = modal.querySelector('.div_sel_st_btm');
        const copy_div_sel_st = div_sel_st_btm.querySelector('.copy_div_sel_st').cloneNode(true);

        // copy_div_sel_st 안에 sel_tr의 student_seq 와 같은 값이 있으면 return
        const student_seq = sel_tr.querySelector('.student_seq').value;
        const div_sel_sts = div_sel_st_btm.querySelectorAll('.div_sel_st');
        let is_exist = false;
        div_sel_sts.forEach(div_sel_st => {
            if (div_sel_st.querySelector('.student_seq').value == student_seq) {
                sAlert('', '이미 추가된 학생입니다.', 4);
                is_exist = true;
                return;
            }
        });
        if (is_exist) return;

        //추가
        const chk = sel_tr.querySelector('.chk');
        const tr = chk.closest('.tr_st');
        const copy = copy_div_sel_st.cloneNode(true);
        copy.hidden = false;
        copy.classList.remove('copy_div_sel_st');
        copy.classList.add('div_sel_st');
        const student_name = tr.querySelector('.student_name').innerText;
        const grade_name = '(' + tr.querySelector('.grade_name').innerText + ')';
        copy.querySelector('.student_name').innerText = student_name + grade_name;
        copy.querySelector('.student_seq').value = tr.querySelector('.student_seq').value;
        div_sel_st_btm.appendChild(copy);
        // div_sel_st_btm.hidden = false;
    }

    // 모달 / 쪽지보내기 / 학생 선택 초기화
    function teachMessModalSendAddStudentClear() {
        const modal = document.querySelector('#teach_mess_modal_send');
        const div_sel_st_btm = modal.querySelector('.div_sel_st_btm');
        const copy_div_sel_st = div_sel_st_btm.querySelector('.copy_div_sel_st').cloneNode(true);
        div_sel_st_btm.innerHTML = '';
        div_sel_st_btm.appendChild(copy_div_sel_st);
    }

    // 모달 / 쪽지보내기 / 학생 전체 추가
    function teachMessModalSendAddStudentAll() {
        const modal = document.querySelector('#teach_mess_modal_send');
        const chk = modal.querySelectorAll('.tr_st .chk');
        chk.forEach(chk => {
            chk.checked = true;
        });
        teachMessModalSendAddStudent();
    }

    // 모달 / 쪽지보내기 / 학생 선택 삭제
    function teachMessModalSendDelStudent(vthis) {
        event.stopPropagation();
        vthis.closest('.div_sel_st').remove();

        //sel_student_names 에 학생이름(학년) 외 n명 추가
        const modal = document.querySelector('#teach_mess_modal_send');
        const sel_student_names = modal.querySelector('.sel_student_names');
        const div_sel_st = modal.querySelectorAll('.div_sel_st');
        const student_seq = vthis.closest('.div_sel_st').querySelector('.student_seq').value;
        if (div_sel_st.length < 1) {
            sel_student_names.innerText = '';
            modal.querySelector('.sel_student_names_after').hidden = true;
        } else {
            const student_name = div_sel_st[0].querySelector('.student_name').innerText;
            const after_str = ' 외 ' + (div_sel_st.length - 1) + '명';
            sel_student_names.innerText = student_name + (div_sel_st.length - 1 > 0 ? after_str : '');
            modal.querySelector('.sel_student_names_after').hidden = false;
        }

        //chk 해제 student_seq 와 같은 tr의 chk
        const tr = modal.querySelector('.tby_st').querySelector('.tr_st .student_seq[value="' + student_seq + '"]').closest('tr');
        tr.querySelector('.chk').checked = false;
    }

    function teachMessModalCheeringSendDelStudent(vthis) {
        event.stopPropagation();
        vthis.closest('.div_sel_st').remove();
    }

    // 모달 / 쪽지보내기 / 다음
    function teachMessModalNext(is_cheering) {
        // div_sel_st_btm 의 학생이 있는지 확인
        let modal = document.querySelector('#teach_mess_modal_send');
        if (is_cheering) modal = document.querySelector('#teach_mess_modal_cheering_send');

        const div_sel_st = modal.querySelectorAll('.div_sel_st');
        if (div_sel_st.length < 1) {
            sAlert('', '선택된 학생이 없습니다.', 4);
            return;
        }

        // 보낼 내용 hidden = false, 검색 div hidden = true
        const div_search = modal.querySelector('.div_search');
        const div_send = modal.querySelector('.div_send');

        div_search.hidden = true;
        div_send.hidden = false;

        modal.querySelectorAll('.type_search').forEach(type_search => {
            type_search.hidden = true;
        });
        modal.querySelectorAll('.type_send').forEach(type_send => {
            type_send.hidden = false;
        });
        initContentEditablePlaceholder('.text_send_message');
    }

    // 쪽지 보내기
    function teachMessModalSend(vthis, is_cheering) {
        const parent_seq = document.querySelector('#teach_mess_div_teach_info .parent_seq').value;
        const student_seq = document.querySelector('#teach_mess_div_teach_info .student_seq').value;
        const teach_seq = document.querySelector('#teach_mess_div_teach_info .teach_seq').value;
        let modal = document.querySelector('#teach_mess_modal_send');
        if (is_cheering) modal = document.querySelector('#teach_mess_modal_cheering_send');

        // 먼저 div_sel_st 에 학생이 있는지 확인
        const div_sel_st = modal.querySelectorAll('.div_sel_st');
        const login_type = document.querySelector('#teach_mess_div_teach_info .login_type').value;
        if (div_sel_st.length < 1 && login_type == 'teacher') {
            sAlert('', '선택된 학생/학부모가 없습니다.', 4);
            return;
        }

        // 보낼 내용이 비어있는지 확인
        const div_message = modal.querySelector('.text_send_message');
        const message = div_message.innerHTML;
        if (div_message.innerText.trim().replace('내용을 입력해주세요.','') == '') {
            sAlert('', '보낼 내용을 입력하세요.', 4);
            return;
        }

        //로딩 시작
        vthis.querySelector('.sp_loding').hidden = false;

        // 보낼 학생 seqs
        const parent_seqs = [];
        const student_seqs = [];
        const student_name = [];
        const student_grade = [];
        div_sel_st.forEach(div_sel_st => {
            parent_seqs.push(div_sel_st.querySelector('.parent_seq').value);
            student_seqs.push(div_sel_st.querySelector('.student_seq').value);
            student_name.push(div_sel_st.querySelector('.student_name').innerText);
            student_grade.push(div_sel_st.querySelector('.grade_name').innerText);
        });
        let contact_seq = '';
        let contact_type = '';
        let cheering_seq = '';
        let cheering_type = '';
        if (is_cheering) {
            cheering_seq = modal.querySelector('[data-cheering-type]:checked').value;
            cheering_type = modal.querySelector('[data-cheering-type]:checked').dataset.cheeringType;
        } else {
            contact_seq = modal.querySelector('.contact_type').value;
            contact_type = modal.querySelector('.contact_type option[value="' + contact_seq + '"]').innerText;
        }

        // 전송
        const page = "/teacher/messenger/send/insert";
        const parameter = {
            parent_seqs: parent_seqs.join(','),
            student_seqs: student_seqs.join(','),
            student_name: student_name.join(','),
            student_grade: student_grade.join(','),
            teach_seq: teach_seq,
            student_seq: student_seq,
            parent_seq: parent_seq,
            message: message,
            contact_seq: contact_seq,
            contact_type: contact_type,
            send_type: login_type,
            cheering_seq: cheering_seq,
            cheering_type: cheering_type
        };
        queryFetch(page, parameter, function(result) {
            //로딩 종료
            vthis.querySelector('.sp_loding').hidden = true;

            if ((result.resultCode || '') == 'success') {
                if (login_type == 'teacher') {
                    sAlert('', '선택된 학생(들)에게 쪽지가 전송 되었습니다.', 4);
                } else if (login_type == 'student') {
                    sAlert('', '선생님께 쪽지가 전송 되었습니다.', 4);
                }

                modal.querySelector('.modal_close').click();
                // 이후 보낸쪽지 , 받은쪽지 리스트 불러오기.
                teachMessMessengerSelect();
            }
        });
    }

    // 모달 / 쪽지 보내기 / 열기
    function teachMessModalSendOpen() {
        // 로그인 유저가 학생이면서, 선생님이 없을경우.
        const login_type = document.querySelector('#mess_login_type').value;
        const teach_seq = document.querySelector('#mess_teach_seq').value;
        if(login_type == 'student' && teach_seq == ''){
            toast('담당 선생님이 없습니다.');
            return;
        }
        // 모달 초기화
        teachMessModalSendClear();
        teachMessStudentSelect();
        const myModal = new bootstrap.Modal(document.getElementById('teach_mess_modal_send'), {});
        myModal.show();
    }

    // 모달 / 쪽지 보내기 / 초기화
    function teachMessModalSendClear() {
        const modal = document.querySelector('#teach_mess_modal_send');
        const login_type = document.querySelector('#teach_mess_div_teach_info .login_type').value;
        if (login_type == 'teacher') {
            modal.querySelector('.div_search').hidden = false;
            modal.querySelector('.div_send').hidden = true;
        }
        modal.querySelector('.search_type').value = 'student_name';
        modal.querySelector('.search_str').value = '';
        modal.querySelector('.sel_student_names').innerText = '';
        modal.querySelector('.sel_student_names_after').hidden = true;
        modal.querySelector('.text_send_message').innerText = '';
        modal.querySelector('.div_empty').hidden = false;
        modal.querySelector('.btn_add_st').hidden = true;
        modal.querySelector('.btn_del_st').hidden = true;
        const div_sel_st_btm = modal.querySelector('.div_sel_st_btm')
        const copy_div_sel_st = div_sel_st_btm.querySelector('.copy_div_sel_st').cloneNode(true);
        div_sel_st_btm.innerHTML = '';
        div_sel_st_btm.appendChild(copy_div_sel_st);
        const copy_tr_st = modal.querySelector('.copy_tr_st').cloneNode(true)
        modal.querySelector('.tby_st').innerHTML = '';
        modal.querySelector('.tby_st').appendChild(copy_tr_st);

        modal.querySelectorAll('.type_search').forEach(type_search => {
            type_search.hidden = false;
        });
        modal.querySelectorAll('.type_send').forEach(type_send => {
            type_send.hidden = true;
        });
    }

    // 선택 삭제
    function teachMessSelDel() {
        // 선택된 쪽지가 있는지 확인
        const div_mes_bundle = document.querySelector('#teachmess_div_mes_bundle');
        const chk = div_mes_bundle.querySelectorAll('.chk:checked');
        if (chk.length < 1) {
            sAlert('', '선택된 쪽지가 없습니다.', 4);
            return;
        }
        const teach_seq = document.querySelector('#teach_mess_div_teach_info .teach_seq').value;
        const messenger_seqs = Array.from(chk).map(chk => chk.closest('.mes_list').querySelector('.messenger_seq').value).join(',');

        const page = "/teacher/messenger/delete";
        const parameter = {
            messenger_seqs: messenger_seqs,
            teach_seq: teach_seq
        };
        sAlert('쪽지삭제', '선택된 쪽지를 삭제하시겠습니까? <br>받은쪽지를 삭제하면 상대방에게도 쪽지가 삭제됩니다.', 2, function() {
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    sAlert('', '선택된 쪽지가 삭제되었습니다.', 4);
                    teachMessMessengerSelect();
                }
            });
        });
    }

    // 모달 / 쪽지 읽고 답변쓰기 / 열기
    function teachMessModalReadAndAnswerOpen(vthis) {
        // 모달 초기화
        teachMessModalReadAndAnswerClear();

        // 쪽지함 상세내용 불러오기.
        const profile_img_path = vthis.querySelector('.profile_img_path').src;
        const messenger_seq = vthis.querySelector('.messenger_seq').value;
        const contact_type = vthis.querySelector('.contact_type').value;
        const parent_seq = document.querySelector('#teach_mess_div_teach_info .parent_seq').value;
        const teach_seq = document.querySelector('#teach_mess_div_teach_info .teach_seq').value;
        const is_receive = document.querySelector('#teach_mess_btn_receive').classList.contains('active');
        const student_seq = document.querySelector('#teach_mess_div_teach_info .student_seq').value;
        const type = is_receive ? 'receive' : 'send';
        const login_type = document.querySelector('#teach_mess_div_teach_info .login_type').value;
        const page = "/teacher/messenger/select";
        const parameter = {
            messenger_seq: messenger_seq,
            teach_seq: teach_seq,
            student_seq: student_seq,
            parent_seq: parent_seq,
            type: type
        };

        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                const mess = result.messengers;
                const modal = document.querySelector('#teach_mess_modal_read');
                modal.querySelector('.messenger_seq').value = mess.id;
                modal.querySelector('.message_title').innerText = mess.contact_type;

                // 선생님 일경우
                if (login_type == 'teacher') {
                    // modal.querySelector('.student_name').innerText = mess.student_name;
                    // modal.querySelector('.school_name').innerText = mess.school_name;
                }
                // 학생일 경우
                else if (login_type == 'student') {
                    // modal.querySelector('.student_name').innerText = mess.teach_name;
                    // modal.querySelector('.school_name').innerText = mess.teach_group;
                }
                modal.querySelector('.contact_type').innerText = mess.contact_type;
                // date + 요일
                // 날짜로 요일 유추
                const date_e = new Date(mess.created_at).format('yyyy-MM-dd');
                const time_str = new Date(mess.created_at).format('a/p hh:mm');

                modal.querySelector('.created_at').innerText = date_e;
                modal.querySelector('.created_at_time').innerText = time_str;
                modal.querySelector('.message').innerHTML = mess.message;
                modal.querySelector('.profile_img_path').src = profile_img_path;

                //날짜가 다르면 답변을 했다는 의미이므로
                if (mess.updated_at != mess.created_at && (mess.comment || '') != '') {
                    const comnt_date_e = new Date(mess.updated_at).format('yyyy-MM-dd');
                    const comnt_time_str = new Date(mess.updated_at).format('a/p hh:mm');

                    modal.querySelector('.div_comment').hidden = false;
                    modal.querySelector('.updated_at').innerText = comnt_date_e;
                    modal.querySelector('.updated_at_time').innerText = comnt_time_str;
                    modal.querySelector('.comment').innerText = mess.comment;
                }

                if (mess.status == 'read') {
                    vthis.querySelector('.status').innerText = '읽음'
                    vthis.querySelector('.status').classList.remove('bg-danger');
                    vthis.querySelector('.status').classList.add('bg-primary-y');
                    vthis.querySelector('.status').classList.add('text-white');
                } else if (mess.status == 'complete') {
                    modal.querySelector('.comment_status').innerText = '답변완료';
                }
            }
            const myModal = new bootstrap.Modal(document.getElementById('teach_mess_modal_read'), {});
            myModal.show();
        });
    }

    // 모달 / 쪽지 읽고 답변쓰기 / 초기화
    function teachMessModalReadAndAnswerClear() {
        const modal = document.querySelector('#teach_mess_modal_read');
        const is_receive = document.querySelector('#teach_mess_btn_receive').classList.contains('active');
        modal.querySelector('.messenger_seq').value = '';
        // modal.querySelector('.student_name').innerText = '';
        // modal.querySelector('.school_name').innerText = '';
        modal.querySelector('.contact_type').innerText = '';
        modal.querySelector('.created_at').innerText = '';
        modal.querySelector('.message').innerText = '';
        modal.querySelector('.div_comment').hidden = true;
        modal.querySelector('.updated_at').innerText = '';
        modal.querySelector('.comment').innerText = '';
        modal.querySelector('.div_send_comment').innerText = '';
        if (is_receive) {
            modal.querySelector('.modal-footer').hidden = false;
        } else {
            modal.querySelector('.modal-footer').hidden = true;
        }
    }

    // 모달 / 쪽지 보내기 검색 학생 드롭다운 열기 / 닫기
    function teachMessModalDropDownToggle(is_bool) {
        const modal = document.querySelector('#teach_mess_modal_send');
        if (!is_bool) {
            modal.querySelector('.dropdown-menu').classList.remove('show');
            modal.querySelector('.div_dropdown_group').classList.remove('rounded-bottom-0');
        } else {
            modal.querySelector('.dropdown-menu').classList.add('show');
            modal.querySelector('.div_dropdown_group').classList.add('rounded-bottom-0');
        }
    }

    // 모달 / 족지보내기 / 검색 후 / 드롭다운 유저 선택.
    function teachMessModalDropDownSelUser(vthis, is_all) {
        const div_list = vthis.closest('.div_dropdown_list');
        const modal = document.querySelector('#teach_mess_modal_send');

        //선택된 학생을 tby_st에 추가.
        const copy_tr_st = modal.querySelector('.copy_tr_st').cloneNode(true);
        const tby_st = modal.querySelector('.tby_st');

        const tr = copy_tr_st.cloneNode(true);
        tr.hidden = false;
        tr.classList.remove('copy_tr_st');
        tr.classList.add('tr_st');
        // 구분은 순서대로 넣어야한다.
        tr.querySelector('.idx').innerText = tby_st.querySelectorAll('.tr_st').length + 1;
        tr.querySelector('.student_name').innerText = div_list.querySelector('.student_name').innerText;
        tr.querySelector('.grade_name').innerText = div_list.querySelector('.grade_name').value;
        // tr.querySelector('.student_class').innerText = div_list.querySelector('.student_class').innerText;
        tr.querySelector('.student_seq').value = div_list.querySelector('.student_seq').value;

        // 중복은 넣을 수 없다.
        const student_seqs = [];
        tby_st.querySelectorAll('.tr_st').forEach(tr => {
            student_seqs.push(tr.querySelector('.student_seq').value);
        });

        if (student_seqs.includes(div_list.querySelector('.student_seq').value)) {
            if (!is_all) {
                sAlert('', '이미 추가된 학생입니다.', 4);
            }
            return;
        }
        tby_st.appendChild(tr);
        // empty와 add, del 버튼 보여주기
        teachMessModalTrStChk();

        //반?
        //[추가 코드]
    }

    // 모달 / 족지 보내기 / 검색 후 / 드롭다운 유저 전체 선택.
    function teachMessModalDropDownSelAllUser() {
        const modal = document.querySelector('#teach_mess_modal_send');
        const div_list = modal.querySelectorAll('.div_dropdown_list');
        div_list.forEach(div_list => {
            teachMessModalDropDownSelUser(div_list, true);
        });
        //닫기
        teachMessModalDropDownToggle(false);
    }

    // 모달 / 쪽지 보내기 / 학생리스트 숫자 체크
    function teachMessModalTrStChk() {
        const modal = document.querySelector('#teach_mess_modal_send');
        const tby_st = modal.querySelector('.tby_st');
        if (tby_st.querySelectorAll('.tr_st').length < 1) {
            // btn_add_st, div_empty
            modal.querySelector('.div_empty').hidden = false;
            modal.querySelector('.btn_add_st').hidden = true;
            modal.querySelector('.btn_del_st').hidden = true;
        } else {
            modal.querySelector('.div_empty').hidden = true;
            modal.querySelector('.btn_add_st').hidden = false;
            modal.querySelector('.btn_del_st').hidden = false;
        }
    }

    // [추가 코드] 선생님 이미지 변경 우선은 코드만 남겨둠.
    // 모달 / 선생님 이미지 변경 / 열기
    function teachMessModalChgTeachImg() {
        const modal = document.querySelector('#teach_mess_modal_chg_teach_img');
    }

    // 선생님 학생에게 보이는 문구 변경.
    function teachMessTeachInitSave() {
        const div_teach_info = document.querySelector('#teach_mess_div_teach_info');
        const message_intro = document.querySelector('#teach_mess_div_teach_intro').innerText.replace('내용을 입력해주세요.', '');
        const teach_seq = div_teach_info.querySelector('.teach_seq').value;
        const page = "/teacher/messenger/intro/insert";
        const parameter = {
            message_intro: message_intro,
            teach_seq: teach_seq
        };

        sAlert('변경', '입려하신 내용 및 사진으로 정보를 변경하시 겠습니까? (200글자 내외)', 3, function() {
            memberInfoUpdateProfileImg();
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    sAlert('', '변경되었습니다.', 4);
                } else {
                    sAlert('', '변경에 실패하였습니다. 다시 시도해주세요.', 4);
                }
            });
        });
    }

    // 받은쪽지 / 보낸 쪽지 가져오기
    function teachMessMessengerSelect() {
        const parent_seq = document.querySelector('#teach_mess_div_teach_info .parent_seq').value;
        const teach_seq = document.querySelector('#teach_mess_div_teach_info .teach_seq').value;
        const search_str = document.querySelector('#teach_mess_inp_messenger_search').value;
        const is_receive = document.querySelector('#teach_mess_btn_receive').classList.contains('active');
        const student_seq = document.querySelector('#teach_mess_div_teach_info .student_seq').value;
        const type = is_receive ? 'receive' : 'send';

        if (is_receive) {
            document.querySelector('[data-div-messge-sender]').innerText = '보낸이';
        } else {
            document.querySelector('[data-div-messge-sender]').innerText = '받는이';
        }

        const page = "/teacher/messenger/select";
        const parameter = {
            teach_seq: teach_seq,
            search_str: search_str,
            student_seq: student_seq,
            parent_seq: parent_seq,
            type: type
        };

        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                const messengers = result.messengers;
                teachMessSelect(messengers);
            } else {
                toast('쪽지를 가져오는데 실패하였습니다. 다시 시도해주세요.');
            }
        });
    }

    function teachMessSelect(messengers) {
        //초기화
        //우선적으로 UI를 보여주기.
        const copy_mes_list = document.querySelector('.copy_mes_list').cloneNode(true);
        const div_mes_bundle = document.querySelector('#teachmess_div_mes_bundle');
        const login_type = document.querySelector('#teach_mess_div_teach_info .login_type').value;
        const is_receive = document.querySelector('#teach_mess_btn_receive').classList.contains('active');

        div_mes_bundle.innerHTML = '';
        div_mes_bundle.appendChild(copy_mes_list);
        copy_mes_list.hidden = true;

        let cnt = 0;
        messengers.forEach(mess => {
            cnt++;
            const list = copy_mes_list.cloneNode(true);
            if (cnt == 1) list.classList.remove('border-top');
            list.hidden = false;
            list.classList.remove('copy_mes_list');
            list.classList.add('mes_list');
            list.querySelector('.messenger_seq').value = mess.id;
            // 선생님이면서
            if (login_type == 'teacher') {
                //  학부모, 학생과 대화
                if (mess.send_type == 'student') {
                    teachMessUserList('student', list, mess);
                }
                if (mess.send_type == 'parent') {
                    teachMessUserList('parent', list, mess);
                } else {
                    if (mess.st_student_name) {
                        teachMessUserList('student', list, mess);
                    } else if (mess.parent_name) {
                        teachMessUserList('parent', list, mess);
                    }
                }
            }
            // 학생일때
            else if (login_type == 'student') {
                // 선생님, 학부모 대화
                if (mess.send_type == 'teacher') {
                    teachMessUserList('teacher', list, mess);
                } else if (mess.send_type == 'parent') {
                    teachMessUserList('parent', list, mess);
                }
                // 보낸 쪽지
                else {
                    if (mess.teach_name) {
                        teachMessUserList('teacher', list, mess);
                    } else if (mess.parent_name) {
                        teachMessUserList('parent', list, mess);
                    }
                }
            }
            // 학부모일때
            else if (login_type == 'parent') {
                // 선생님, 학생 대화 (받은 쪽지)
                if (mess.send_type == 'teacher') {
                    teachMessUserList('teacher', list, mess);
                } else if (mess.send_type == 'student') {
                    teachMessUserList('student', list, mess);
                }
                // 보낸 쪽지
                else if (mess.send_type == 'parent') {
                    if (mess.teach_name) {
                        teachMessUserList('teacher', list, mess);
                    } else if (mess.student_name) {
                        teachMessUserList('student', list, mess);
                    }
                }
            }


            if (mess.status == 'new') {
                list.querySelector('.status').innerText = is_receive ? 'NEW' : '대기중';
                list.querySelector('.status').classList.add('bg-danger');
            } else if (mess.status == 'read') {
                list.querySelector('.status').innerText = '읽음';
                list.querySelector('.status').classList.add('bg-primary-y');
                list.querySelector('.status').classList.add('text-white');
            } else if (mess.status == 'complete') {
                list.querySelector('.status').innerText = '답변 완료';
                list.querySelector('.status').classList.add('border-primary-y');
                list.querySelector('.status').classList.add('text-primary-y');
            }
            list.querySelector('.created_at').innerText = `${new Date(mess.created_at).format('yyyy.MM.dd')}\n${new Date(mess.created_at).toLocaleString('ko-KR', { hour: '2-digit', minute: '2-digit' })}`;
            list.querySelector('.message').innerHTML = mess.message;
            list.querySelector('.message').innerText = list.querySelector('.message').innerText.substr(0,200);

            list.querySelector('.contact_type').innerText = mess.contact_type || (mess.cheering_typee == 'message' ? '응원메시지' : '') || '';
            div_mes_bundle.appendChild(list);
        });

        //쪽지가 없을때
        if (messengers.length < 1) {
            document.querySelector('#teachmess_div_mes_empty').hidden = false;
        } else {
            document.querySelector('#teachmess_div_mes_empty').hidden = true;
        }
    }

    // 쪽지함 탭 메뉴 클릭
    function teachMessModalReadAndAnswerSend() {
        const modal = document.querySelector('#teach_mess_modal_read');
        const messenger_seq = modal.querySelector('.messenger_seq').value;
        const message = modal.querySelector('.div_send_comment').innerText;
        const teach_seq = document.querySelector('#teach_mess_div_teach_info .teach_seq').value;

        // 전송
        const page = "/teacher/messenger/comment/insert";
        const parameter = {
            messenger_seq: messenger_seq,
            message: message,
            teach_seq: teach_seq
        };
        sAlert('답변', '입력하신 내용으로 답변을 전송하시겠습니까?', 2, function() {
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    toast('답변이 전송되었습니다.');
                    teachMessMessengerSelect();
                    //테스트후 주석 해제
                    modal.querySelector('.modal_close').click();
                } else {
                    toast('답변 전송에 실패하였습니다. 다시 시도해주세요.');
                }
            });
        });
    }

    // 팀 정보 가져오기.
    function teachMessTeamSelect() {
        const region_seq = document.querySelector('#teach_mess_div_leader .region_seq').value;

        // 전송
        const page = "/manage/useradd/team/select";
        const parameter = {
            region_seq: region_seq
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                const teams = result.resultData;
                const select_team = document.querySelector('#teach_mess_div_leader .team_code');
                select_team.innerHTML = '<option value="">팀 선택</option>';
                teams.forEach(team => {
                    const option = document.createElement('option');
                    option.value = team.team_code;
                    option.innerText = team.team_name;
                    select_team.appendChild(option);
                });
            }
        });
    }

    // 소속, 팀 > 선생님 정보 가져오기.
    function teachMessTeacherSelect() {
        const search_team = document.querySelector('#teach_mess_div_leader .team_code').value;
        const group_type = 'teacher';
        const group_type2 = 'general,run,leader';
        const search_region = document.querySelector('#teach_mess_div_leader .region_seq').value;

        //전송
        const page = "/manage/userlist/teacher/select";
        const parameter = {
            search_team: search_team,
            group_type: group_type,
            group_type2: group_type2,
            search_region: search_region
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                const teachers = result.resultData;
                const select_teacher = document.querySelector('#teach_mess_div_leader .teach_seq');
                select_teacher.innerHTML = '<option value="">선생님 선택</option>';
                teachers.forEach(teacher => {
                    const option = document.createElement('option');
                    option.value = teacher.id;
                    option.innerText = teacher.teach_name;
                    option.setAttribute('message_intro', teacher.message_intro || '');
                    option.setAttribute('group_name', teacher.group_name || '');
                    select_teacher.appendChild(option);
                });
            }
        });
    }

    // 팀장, 총괄매니저 > 선생님 선택후 조회하기
    function teachMessSelectTeacher() {
        const div_leader = document.querySelector('#teach_mess_div_leader');
        const div_sel_teach = document.querySelector('#teach_mess_div_sel_teach');

        const teach_name = div_leader.querySelector('.teach_seq option:checked').innerText;
        const teach_seq = div_leader.querySelector('.teach_seq').value;
        const group_name = div_leader.querySelector('.teach_seq option:checked').getAttribute('group_name');
        const message_intro = div_leader.querySelector('.teach_seq option:checked').getAttribute('message_intro');

        // 선생님 선택후 조회하기
        if (teach_seq == '') {
            sAlert('', '선생님을 선택해주세요.', 4);
            return;
        }
        // 관리자 페이지 숨기기
        div_leader.hidden = true;

        // 선생님 페이지 teach_seq, teach_name, group_name 변경
        // 메시지 인트로 변경
        div_sel_teach.querySelector('.teach_seq').value = teach_seq;
        div_sel_teach.querySelector('.teach_name').innerText = teach_name;
        div_sel_teach.querySelector('.group_name').innerText = group_name;
        document.querySelector('#teach_mess_div_teach_intro').innerHTML = message_intro;

        const mes_list = document.querySelectorAll('.mes_list');
        mes_list.forEach(mes_list => {
            mes_list.remove();
        });

        teachMessMessengerSelect();

        // 선생님 페이지 보이기
        div_sel_teach.hidden = false;
    }

    // 쪽지보내기 > 학부모, 학생 검색 토글
    function teachMessModalSearchUserTypeToggle(vthis) {
        const modal = document.querySelector('#teach_mess_modal_send');
        const search_user_type = vthis.getAttribute('data');
        if (search_user_type == 'student') {
            vthis.setAttribute('data', 'parent');
            vthis.innerText = '학생 보기';
            modal.querySelectorAll('.use_parent').forEach(use_parent => {
                use_parent.hidden = false;
            });
            modal.querySelectorAll('.use_student').forEach(use_student => {
                use_student.hidden = true;
            });
        } else {
            vthis.setAttribute('data', 'student');
            vthis.innerText = '학부모 보기';
            modal.querySelectorAll('.use_parent').forEach(use_parent => {
                use_parent.hidden = true;
            });
            modal.querySelectorAll('.use_student').forEach(use_student => {
                use_student.hidden = false;
            });
        }
        // 전체를 불러와서 주석처리.
        //다시 불러오기.
        // teachMessStudentSelect();
    }


    // 학부모 일때 사이드 탭 클릭.
    function teachMessPtTab(vthis) {
        document.querySelectorAll('[data-pt-tab]').forEach(function(el) {
            el.classList.remove('active');
        });
        vthis.classList.add('active');
        const type = vthis.dataset.ptTab;
        if (type == '1') {
            document.querySelector('#teach_mess_btn_receive').click()
        } else if (type == '2') {
            document.querySelector('#teach_mess_btn_send').click()
        }
        const tab_menu = document.querySelectorAll('[data-pt-tab]');
        tab_menu.forEach(tab_menu => {
            tab_menu.classList.remove('active');
            tab_menu.querySelector('img:nth-child(1)').hidden = true;
            tab_menu.querySelector('img:nth-child(2)').hidden = false;
        });
        vthis.classList.add('active');
        vthis.querySelector('img:nth-child(1)').hidden = false;
        vthis.querySelector('img:nth-child(2)').hidden = true;
    }


    // 학부모 일때 > 응원 메시지 > 추가하기 버튼.
    function teachMessCheeringChildAdd(vthis) {
        // 학생이 선택이 되어있는지 확인
        const modal = document.querySelector('#teach_mess_modal_cheering_send');

        //div_sel_st_btm, copy_div_sel_st 에 학생 추가
        const div_sel_st_btm = modal.querySelector('.div_sel_st_btm');
        const copy_div_sel_st = div_sel_st_btm.querySelector('.copy_div_sel_st').cloneNode(true);
        const user_type = 'student';

        //추가
        //student_name = 학생이름 + (학년)
        const tr = vthis.closest('.tr_st');
        const copy = copy_div_sel_st.cloneNode(true);
        const parent_seq = tr.querySelector('.parent_seq').value;
        const student_seq = tr.querySelector('.student_seq').value;

        //user_type = student 이면 student_seq, parent 이면 parent_seq 값이 있는지 tr과 div_sel_st 를 비교해서 추가
        const already_tag = document.querySelectorAll('.' + user_type + '_seq' + (user_type == 'student' ? student_seq : parent_seq));
        const already_cnt = already_tag.length;
        if (already_cnt > 0) {
            return;
        }

        copy.hidden = false;
        copy.classList.remove('copy_div_sel_st');
        copy.classList.add('div_sel_st');
        const student_name = tr.querySelector('.student_name').innerText;
        const grade_name = tr.querySelector('.grade_name').innerText;
        copy.querySelector('.student_name').innerText = student_name;
        copy.querySelector('.grade_name').innerText = (user_type == 'student' ? grade_name : '학부모');
        copy.querySelector('.student_seq').value = (user_type == 'student' ? student_seq : '');
        copy.querySelector('.parent_seq').value = (user_type == 'student' ? '' : parent_seq);
        copy.querySelector('.user_type').value = user_type;
        copy.classList.add(user_type + '_seq' + (user_type == 'student' ? student_seq : parent_seq));
        div_sel_st_btm.appendChild(copy);

        //sel_student_names 에 학생이름(학년) 외 n명 추가
        teachMessStAddAfterStr(true);
        setTimeout(function() {
            modal.querySelector('.text_send_message').focus();
        }, 1000)
    }

    // 자녀 응원 메시지 모달 오픈.
    function teachMessModalCheeringSendOpen() {
        // 모달 초기화
        teachMessModalCheeringSendClear();
        const myModal = new bootstrap.Modal(document.getElementById('teach_mess_modal_cheering_send'), {});
        myModal.show();
    }
    // 자녀 응원 메시지 모달 초기화
    function teachMessModalCheeringSendClear() {
        const modal = document.querySelector('#teach_mess_modal_cheering_send');
        const search_str = modal.querySelector('.search_str');
        const sel_student_names = modal.querySelector('.sel_student_names');
        const div_sel_st_btm = modal.querySelector('.div_sel_st_btm');
        const copy_div_sel_st = div_sel_st_btm.querySelector('.copy_div_sel_st').cloneNode(true);
        div_sel_st_btm.innerHTML = '';
        div_sel_st_btm.appendChild(copy_div_sel_st);
        div_sel_st_btm.hidden = false;
        modal.querySelector('.text_send_message').innerText = '';

        // 응원 메시지로 활성화 되어있게.
        modal.querySelector('[data-cheering-type]').click();

        // 보낼 내용 hidden = false, 검색 div hidden = true
        const div_search = modal.querySelector('.div_search');
        const div_send = modal.querySelector('.div_send');

        div_search.hidden = false;
        div_send.hidden = true;
    }
    // 1:1 상담 모달 오픈.
    function teachMessOneOneModalOpen() {
        // 초기화
        teachMessOneOneModalClear();
        document.querySelector('#teach_mess_sel_child').onchange();

        const myModal = new bootstrap.Modal(document.getElementById('teach_mess_modal_one_and_one'), {});
        myModal.show();
    }
    // 1:1 상담 초기화
    function teachMessOneOneModalClear() {

    }
    // 1:1 상담 > 자녀를 선택 했을때, 유형 넣기.(다른 코드일 경우도 있어서.)
    function teachMessChgChildSelect(vthis) {
        const modal = document.querySelector('#teach_mess_modal_one_and_one');
        const select = modal.querySelector('#teach_mess_sel_contact');
        select.innerHTML = '';
        const main_code = vthis.selectedOptions[0].dataset.mainCode;
        if (json_contact_codes[main_code]) {
            const contact_codes = json_contact_codes[main_code];
            contact_codes.forEach(function(data) {
                const option = document.createElement('option');
                option.value = data.id;
                option.text = data.code_name;
                select.appendChild(option);
            });
        }
    }

    // 1:1 상담 자주묻는 질문 상세 내용 토글.
    function teachMessScriptInfoToggle(vthis) {
        const qna = vthis.closest('[data-qna]');
        if (qna.querySelector('img').classList.contains('rotate-180')) {
            qna.querySelector('img').classList.remove('rotate-180');
            qna.querySelector('.script_info').hidden = true;
        } else {
            qna.querySelector('img').classList.add('rotate-180');
            qna.querySelector('.script_info').hidden = false;
        }
    }
    // 1:1 상담 쪽지 보내기 담임 선생님?
    function teachMessModalSendOneAOne() {

        const modal = document.querySelector('#teach_mess_modal_one_and_one');
        const child_select = modal.querySelector('#teach_mess_sel_child');
        const teach_seq = child_select.selectedOptions[0].dataset.teachSeq;
        const parent_seq = document.querySelector('#teach_mess_div_teach_info .parent_seq').value;
        const message = modal.querySelector('.text_send_message').innerText;
        const contact_seq = modal.querySelector('.contact_type').value;
        const contact_type = modal.querySelector('.contact_type option[value="' + contact_seq + '"]').innerText;
        const login_type = document.querySelector('#teach_mess_div_teach_info .login_type').value;

        if (message.trim().replace('내용을 입력해주세요.','') == '') {
            sAlert('', '보낼 문의사항을 입력하세요.', 4);
            return;
        }
        // 전송
        const page = "/teacher/messenger/send/insert";
        const parameter = {
            parent_seqs: '',
            student_seqs: '',
            student_grade: '',
            teach_seq: teach_seq,
            student_seq: '',
            parent_seq: parent_seq,
            message: message,
            contact_seq: contact_seq,
            contact_type: contact_type,
            send_type: login_type,
        };
        queryFetch(page, parameter, function(result) {
            if ((result.resultCode || '') == 'success') {
                sAlert('', '선생님께 쪽지가 전송 되었습니다.', 4);
                modal.querySelector('.modal_close').click();
                // 이후 보낸쪽지 , 받은쪽지 리스트 불러오기.
                teachMessMessengerSelect();
            }
        });
    }

    function teachMessUserList(who, list, mess) {
        if (who == 'teacher') {
            list.querySelector('.user_name').innerText = mess.teach_name;
            list.querySelector('.student_grade').innerText = mess.teach_group || '';
            list.querySelector('.profile_img_path').setAttribute('src', '/storage/uploads/user_profile/teacher/' + mess.teach_profile_img_path);
        } else if (who == 'student') {
            list.querySelector('.user_name').innerText = mess.student_name;
            list.querySelector('.profile_img_path').setAttribute('src', '/storage/uploads/user_profile/student/' + mess.profile_img_path);
        } else if (who == 'parent') {
            list.querySelector('.user_name').innerText = mess.parent_name;
            list.querySelector('.student_grade').innerText = '학부모';
            list.querySelector('.profile_img_path').setAttribute('src', '/storage/uploads/user_profile/parent/' + mess.pt_profile_img_path);
        }
    }

    // 문자 모달 열기.
    async function teachMessSmsModalOpen(){
        // const keys = Object.keys(chks);
        // if(keys.length < 1){
        //     toast('선택된 상담 리스트의 학생이 없습니다.');
        //     return;
        // }
        // const student_seqs = [];
        // keys.forEach(function(key){
        //     student_seqs.push(chks[key].student_seq);
        // });
        // if(await alarmSendGetSmsInfo(student_seqs)){
            alarmSendSmsModalOpen();
            alarmSelectUser();
        // }
    }

    // 프로필 이미지 파일 변경
    function memberInfoUpdateImgFileChange(vthis, img_id) {
        const profile_img_file = document.querySelector('#' + img_id);
        const file = vthis.files[0];
        if(file == undefined){
            profile_img_file.src = '/images/yellow_human_icon.svg';
            profile_img_file.style.width = '';
            profile_img_file.dataset.is_chagne = '';
            return false;
        }
        const reader = new FileReader();
        const size = file.size;

        //용량이 5m 이상이면 리턴
        if (size > 5*1024*1024) {
            toast('용량이 5MB 이상입니다.');
            vthis.value = '';
            profile_img_file.src = '/images/yellow_human_icon.svg';
            profile_img_file.style.width = '';
            return false;
        }

        reader.onload = function() {
            profile_img_file.src = reader.result;
            profile_img_file.style.width = '100%';
            profile_img_file.dataset.is_chagne = 'Y';
        }
        reader.readAsDataURL(file);
    }

        // :프로필 이미지 업로드
        function memberInfoUpdateProfileImg(){
            const profile_img_file = document.querySelector('#member_info_file_edit');
            const profile_inp_file = document.querySelector('#member_info_imgfile_edit');
            const teach_seq = document.querySelector('#teach_mess_div_teach_info .teach_seq').value;

            // 이미지를 변경하지 않았으면 return
            if(profile_img_file.dataset.is_chagne != 'Y'){
                return;
            }

            // 데이터
            const user_type = 'teacher';
            const user_seq = teach_seq;
            const user_img = profile_inp_file.files[0];

            const page = '/teacher/messenger/info/profile/upload';
            let formData = new FormData();
            formData.append('user_type', user_type);
            formData.append('user_seq', user_seq);
            formData.append('user_img', user_img);

            queryFormFetch(page, formData, function(result){
                if((result.resultCode||'') == 'success'){
                    toast('프로필 이미지가 업로드 되었습니다.');
                }
                else{
                    toast('프로필 이미지 업로드에 실패하였습니다. 다시 시도해주세요.');
                }
            });
        }
    // 전체 인풋 박스 체크.
    function teachMessAllChkMessenger(vthis){
        const chk_bool = vthis.checked;
        const bundle = document.querySelector("#teachmess_div_mes_bundle");;
        const rows = bundle.querySelectorAll('.mes_list');
        rows.forEach(function(row){
            row.querySelector('.chk').checked = chk_bool;
        });
    }
    function initContentEditablePlaceholder(selector) {
      const elements = document.querySelectorAll(selector);
      elements.forEach(el => {
        const placeholderText = el.getAttribute('placeholder');
        if (!placeholderText) return;

        // placeholder를 추가하는 함수
        const addPlaceholder = () => {
          if (!el.textContent.trim()) {
            el.classList.add('placeholder-active');
            // 사용자 입력과 구분하기 위해 innerHTML에 placeholder 텍스트 설정
            el.innerHTML = placeholderText;
          }
        };

        // 포커스 시 placeholder가 있다면 지웁니다.
        const removePlaceholder = () => {
          if (el.classList.contains('placeholder-active')) {
            el.classList.remove('placeholder-active');
            el.innerHTML = '';
          }
        };

        el.addEventListener('focus', removePlaceholder);
        el.addEventListener('blur', addPlaceholder);

        // 초기 상태에 내용이 없으면 placeholder 추가
        addPlaceholder();
      });
    }
</script>
@endsection
