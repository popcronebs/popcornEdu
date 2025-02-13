@extends('layout.layout')

@section('head_title')
학생정보관리
@endsection

{{--
추가 코드
1. 팀 배정 변경(총괄만 가능) / 담당 선생님 배정 변경.
3. 상담 선생님일경우 / 하단 sub_tr에 신규 상담하기(상담 관리) 버튼만 보이게.
4. 결제관련 버튼 추가.
5. 포인트 차감. (사이드 메뉴에서)
--}}
{{-- 관리자 컨텐트 --}}
@section('layout_coutent')
<input type="hidden" data-main-group-type2 value="{{ $group_type2 }}">
<input type="hidden" data-main-group-type3 value="{{ $group_type3 }}">
<input type="hidden" data-main-student-seq value="">
<input type="hidden" data-main-teach-seq value="{{ session()->get('teach_seq') }}">
<input type="hidden" data-main-team-code value="{{ session()->get('team_code') }}">


{{-- :이미지+타이틀  --}}
<div class="sub-title" data-sub-title="basic">
    <h2 class="text-sb-42px">
    <img src="{{ asset('images/svg/calendar_in_user_icon.svg?1') }}" width="72">
        학생 정보관리
    </h2>
</div>


{{-- :뒤로가기 타이틀  --}}
<div class="sub-title" data-sub-title="back" hidden>
    <h2 class="text-sb-42px">
        <button data-btn-back-page class="btn p-0 row mx-0 all-center" onclick="teachStDetailBack();">
            <img src="{{ asset('images/black_arrow_left_tail.svg') }}" width="52" class="px-0">
        </button>
        <span class="me-2" data-title-student-name></span>
        <span data-title-school-grade
        class="ht-make-title on text-r-20px py-1 px-3 ms-1 h-42 d-flex align-items-center"></span>
    </h2>
</div>



{{-- 상단 지역, 팀, 선생님 --}}
<section>
    <table class="w-100 table-serach-style table-border-xless table-h-92 modal-shadow-style rounded-3">
        <colgroup>
            <col style="width: 33.33%;">
            <col style="width: 33.33%;">
            <col style="width: 33.33%;">
        </colgroup>
        <thead></thead>
        <tbody>
            <tr class="text-start">
                <td class="text-start p-4 scale-text-gray_06 py-4">
                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                        <select data-select-top="region" {{ $group_type2 != 'general' ? 'disabled' : '' }}
                            onchange="teachStGoodsSelectTop(this, 'region')"
                            class="border-none lg-select rounded-0 text-sb-20px {{ $group_type2 != 'general' ? 'scale-text-gray_05' : '' }} p-0 w-100 ">
                            @if ($group_type2 == 'general')
                                <option value="">소속을 선택해주세요.</option>
                            @endif
                            @if (!empty($regions))
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}">{{ $region->region_name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <img src="{{ asset('images/svg/btn_arrow_down.svg') }}"
                            class="position-absolute end-0 bottom-0 top-0 m-auto" alt="" width="32"
                            height="32">
                    </div>
                </td>
                <td class="text-start px-4">

                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative">
                        <select data-select-top="team" {{ $group_type2 != 'general' ? 'disabled' : '' }}
                            onchange="teachStGoodsSelectTop(this, 'team')"
                            class="border-none lg-select rounded-0 text-sb-20px {{ $group_type2 != 'general' && $group_type2 != 'leader' ? 'scale-text-gray_05' : '' }} p-0 w-100">
                            @if ($group_type2 == 'general')
                                <option value="">소속 팀을 선택해주세요.</option>
                            @else
                                @if (!empty($team))
                                @foreach ($team as $t)
                                    <option value="{{ $t['team_code'] }}">{{ $t['team_name'] }}</option>
                                @endforeach
                                @endif
                            @endif

                        </select>
                        <img src="{{ asset('images/svg/btn_arrow_down.svg') }}"
                            class="position-absolute end-0 bottom-0 top-0 m-auto" alt="" width="32"
                            height="32">
                    </div>
                </td>
                <td class="text-start ps-4 scale-text-gray_06">
                    <div class="d-inline-block select-wrap py-0 w-100 d-flex position-relative align-items-center">
                        <select data-select-top="teacher"
                            {{ $group_type2 != 'general' && $group_type2 != 'leader' ? 'disabled' : '' }}
                            onchange="teachStGoodsSelectTop(this, 'teacher')"
                            class="border-none lg-select rounded-0 text-sb-20px {{ $group_type2 != 'general' && $group_type2 != 'leader' ? 'scale-text-gray_05' : '' }} p-0 w-100">
                            @if ($group_type2 == 'general' || $group_type2 == 'team')
                                <option value="">소속 선생님을 선택해주세요.</option>
                            @else
                                <option value="{{ session()->get('teach_seq') }}" selected>
                                    {{ session()->get('teach_name') }}</option>
                            @endif
                        </select>
                        <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" class="position-absolute end-0"
                                alt="" width="32" height="32">
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="all-center gap-2 mt-52 mb-52">
        <button type="button" onclick="teachStMovePageUsersAdd();"
            class="btn-line-ms-secondary text-sb-24px rounded-pill border-block-hover scale-bg-white scale-text-black">
            <svg class="m-1" width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M10.8654 3.57031C10.2494 3.57031 9.75008 4.06966 9.75008 4.68565V9.33936L6.87265 9.33936C5.84457 9.33936 5.3355 10.5875 6.07034 11.3065L11.1712 16.2975C11.6171 16.7338 12.3299 16.7338 12.7758 16.2975L17.8767 11.3065C18.6115 10.5875 18.1024 9.33936 17.0744 9.33936L14.1665 9.33936V4.68565C14.1665 4.06966 13.6671 3.57031 13.0512 3.57031H10.8654Z"
                    fill="#222222"></path>
                <rect x="5.57031" y="17.8203" width="12.8027" height="1.75074" rx="0.875369" fill="#222222">
                </rect>
            </svg>
            Excel 불러오기
        </button>
        <button type="button" onclick="teachStMovePageUsersAdd('/teacher/users/add/list')"
            class="btn-line-ms-secondary text-sb-24px rounded-pill border-none scale-bg-white scale-text-white primary-bg-mian scale-text-gray_05 me-1">사용자
            등록하기</button>
    </div>

</section>

<section>
    <div class="row content-block mt-120">
        <div class="col-4">
            <div data-tab="student" onclick=""
                class="rounded-3 h-100 div-shadow-style d-flex justify-content-between align-items-center p-4 scale-text-gray_05 primary-bg-mian-hover scale-text-white-hover active">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('images/gray_gear_icon.svg') }}" width="32">
                    <p class="text-sb-24px  ms-1">회원정보 관리</p>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div data-tab="point" onclick=""
                class="rounded-3 h-100 div-shadow-style d-flex justify-content-between align-items-center p-4 scale-text-gray_05">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('images/gray_gear_icon.svg') }}" width="32">
                    <p class="text-sb-24px  ms-1">포인트 관리</p>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div data-tab="goods" onclick=""
                class="rounded-3 h-100 div-shadow-style d-flex justify-content-between align-items-center p-4 scale-text-gray_05">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('images/gray_gear_icon.svg') }}" width="32">
                    <p class="text-sb-24px  ms-1">이용권 관리</p>
                </div>
            </div>
        </div>
    </div>
</section>


<section>


<article data-tab-content="student">
    <div class="d-flex justify-content-end align-items-end mt-80 mb-32">
        <div class="h-center">
            <label class="d-inline-block select-wrap select-icon">
                <select data-search-type
                    onchange=""
                    class="date-change rounded-pill border-gray sm-select text-sb-20px me-2 h-52">
                    <option value="">검색기준</option>
                    <option value="grade">학년</option>
                    <option value="name">이름</option>
                    <option value="student_id">아이디</option>
                    <option value="goods_name">이용권</option>
                    <option value="student_phone">전화번호</option>
                </select>
            </label>
            <label class="label-search-wrap">
                <input type="text" onkeyup="if(event.keyCode == 13) teachStStudentSelect();" data-search-str
                class="ms-search border-gray rounded-pill text-m-20px" placeholder="검색어를 입력해주세요.">
            </label>
        </div>
    </div>
    <div class="col-12 ">
        <table class="table-style w-100" style="min-width: 100%;">
        <colgroup>
            <col style="width: 80px;">
        </colgroup>
        <thead class="">
            <tr class="text-sb-20px modal-shadow-style rounded">
                <th style="width: 80px">
                    <label class="checkbox mt-1">
                        <input type="checkbox" class="">
                        <span class="">
                        </span>
                    </label>
                </th>
                <th hidden>팀</th>
                <th>학생이름</th>
                <th>학생 휴대전화</th>
                <th>학부모 휴대전화</th>
                <th>포인트</th>
                <th>이용권</th>
                <th>이용 기간</th>
                @if($group_type3 != 'counsel')
                <th class="none_counsel">담당 선생님</th>
                @endif
                <th>이용 활성화</th>
                @if($group_type3 == 'counsel')
                <th class="use_counsel">-</th>
                @endif
                <th>더보기</th>
            </tr>
        </thead>
            <tbody data-bundle="tby_students">
                <tr class="text-m-20px h-104" data-row="copy" hidden>
                    <input type="hidden" data-teach-seq>
                    <input type="hidden" data-student-seq>
                    <input type="hidden" data-parent-seq>
                    <input type="hidden" data-school-name>
                    <input type="hidden" data-grade-name>

                   <td class="">
                        <label class="checkbox mt-1">
                            <input type="checkbox" class="chk">
                            <span class="">
                            </span>
                        </label>
                    </td>
                    <td class="text-sb-20px" data-explain="#팀" hidden>
                        <span data-team-name></span>
                    </td>
                    <td class="text-sb-20px" data-explain="#학생이름">
                        <span data-student-name></span>
                        <span data-student-id></span>
                    </td>
                    <td class="text-sb-20px" data-explain="#학생휴대전화">
                        <span data-student-phone></span>
                        <span class="text-primary" data-send-sms="student"></span>
                    </td>
                    <td class="text-sb-20px" data-explain="#학부모휴대전화">
                        <span data-parent-phone></span>
                        <span class="text-primary" data-send-sms="parent"></span>
                    </td>
                    <td class="text-sb-20px cursor-pointer" data-explain="#포인트" onclick="teachStOpenDetail(this, 'point');">
                        <span data-point-now class="primary-text-mian"></span>
                    </td>
                    <td class="text-sb-20px cursor-pointer" data-explain="#이용권" onclick="teachStOpenDetail(this, 'goods')">
                        <span data-goods-name></span>
                        <span data-goods-period></span>
                        <div data-readd-counsel hidden>
                            <span class="text-danger" >재등록 상담하기</span>
                        </div>
                    </td>
                    <td class="text-sb-20px" data-explain="#등록기간">
                        <span data-goods-start-date></span> ~ <span data-goods-end-date></span>
                    </td>
                    @if($group_type3 != 'counsel')
                        <td class="text-sb-20px text-dark none_counsel" data-explain="#담당 선생님">
                        <span data-teach-name></span>
                        <span data-teach-id></span>
                    </td>
                    @endif
                    <td class="text-sb-20px" data-explain="#이용활성화">
                        <label class="toggle">
                            <input type="checkbox" class="" data-is-use checked=""
                                onchange="teachStStIsUseUpdate(this)">
                            <span class=""></span>
                        </label>
                    </td>
                    @if($group_type3 == 'counsel')
                    <td class"text-sb-20px use_counsel" data-explain="#상세보기 버튼">
                        <button onclick="teachStUserEditPage(this)";
                        class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom">상세보기</button>
                    </td>
                    @endif
                    <td class="text-sb-20px" data-explain="#더보기">
                        <button data-btn-toggle-table class="btn p-0 m-0 h-center" onclick="teachStOpenTr(this);">
                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" width="32" height="32">
                        </button>
                    </td>
                </tr>
                <tr class="scale-bg-gray_01" data-sub-row="copy" hidden>
                    <td colspan="12">
                        <input type="hidden" data-student-seq>
                        <div class="h-center justify-content-between">
                            <div class="col-auto p-4">
                               <span data-student-name2 class="text-sb-20px"></span>
                               <span data-student-info class="text-sb-20px text-danger"></span>
                            </div>
                            <div class="col h-center gap-4 p-4 justify-content-end">
                                {{-- 상담관리, 학습관리, 학습플래너, 상세보기 --}}
                                @if($group_type3 == 'counsel')
                                <button onclick="";
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom">신규 상담하기</button>
                                @endif
                                @if($group_type3 != 'counsel')
                                <button onclick="";
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom">상담관리</button>

                                <button onclick="";
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom">학습관리</button>

                                <button onclick="";
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom">학습플래너</button>

                                <button onclick=""
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom">쪽지보내기</button>

                                <button onclick="teachStUserEditPage(this)";
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom">상세보기</button>
                                @endif
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-between align-items-center mt-52">
    {{-- 하단 엑셀내보내기, SMS 문자/알림톡, 페이징, 선택학생 쪽지보내기, 관리선생님 변경, 팀 변경 --}}
        <div class="">
            <button type="button" onclick="teachStlistExcelDownload()"
                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg" class="me-1">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M10.8649 16.6265C10.2489 16.6265 9.74959 16.1271 9.74959 15.5111V10.8574L6.87216 10.8574C5.84408 10.8574 5.33501 9.60924 6.06985 8.89023L11.1707 3.89928C11.6166 3.46299 12.3294 3.46299 12.7753 3.89928L17.8762 8.89024C18.611 9.60924 18.1019 10.8574 17.0739 10.8574L14.166 10.8574V15.5111C14.166 16.1271 13.6667 16.6265 13.0507 16.6265H10.8649Z"
                        fill="#DCDCDC"></path>
                    <rect x="5.57031" y="17.8208" width="12.8027" height="1.75074" rx="0.875369"
                        fill="#DCDCDC"></rect>
                </svg>
                Excel 내보내기
            </button>

            <button type="button"
                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2">
                SMS 문자/알림톡
            </button>
        </div>

        {{-- 페이징 기능. --}}
        <div class="my-custom-pagination">
            <div class="col d-flex justify-content-center">
                <ul class="pagination col-auto" data-page="1" hidden>
                    <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="1"
                        onclick="teachStPageFunc('1', 'prev')">
                        <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                    </button>
                    <li class="page-item" hidden>
                        <a class="page-link" onclick="">0</a>
                    </li>
                    <span class="page" data-page-first="1" hidden
                        onclick="teachStPageFunc('1', this.innerText);" disabled>0</span>
                    <button href="javascript:void(0)" class="btn p-0 next" data-page-next="1"
                        onclick="teachStPageFunc('1', 'next')" data-is-next="0">
                        <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                    </button>
                </ul>
            </div>
        </div>

        {{-- 선택 학생 쪽지, 관리선생님 변경, 팀 변경 --}}
        <div>
            <button onclick="";
            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom">선택 학생 쪽지보내기</button>

            <button onclick="teachStTeachAndTeamChangeModal('teacher');";
            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom">담당 선생님 변경</button>

            @if($group_type2 == 'general')
            <button onclick="teachStTeachAndTeamChangeModal('team');";
            class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom">팀 변경</button>
            @endif
        </div>
    </div>
    <div class="mt-5 scale-bg-gray_01 h-center justify-content-between p-4 rounded">
        <div class="col">
           <span class="text-sb-24px">선택 학생 기간 연장 및 포인트 지급</span>
        </div>
        <div class="col text-end">

            <div class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-1 me-2 align-bottom">
                <button class="text-sb-24px btn" onclick="teachStPMSetting(this,'m')">-</button>
                <input type="" class="form-control border-none text-center p-0" placeholder="0" id="userlist_inp_day_manage" style="width: 30px;"
                    aria-label="Example text with button addon" aria-describedby="button-addon2">
                <span>일</span>
                <button class="text-sb-24px btn" onclick="teachStPMSetting(this,'p')">+</button>
            </div>
            <button type="button" onclick="userlistGoodsDayPlusModal();"
                class="btn-line-xss-secondary text-sb-20px border-dark rounded scale-bg-white text-dark px-3 me-2 align-bottom " >기간 연장</button>


            <div class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-1 me-2 align-bottom">
                <button class="text-sb-24px btn" onclick="teachStPMSetting(this,'m')">-</button>
                <input type="" class="form-control border-none text-center p-0" placeholder="0" id="userlist_inp_point_manage" style="width: 30px;"
                    aria-label="Example text with button addon" aria-describedby="button-addon2">
                <span>점</span>
                <button class="text-sb-24px btn" onclick="teachStPMSetting(this,'p')">+</button>
            </div>
            <button type="button" onclick="userlistSelPointManage();"
                class="btn-line-xss-secondary text-sb-20px border-dark rounded scale-bg-white text-dark px-3 me-2 align-bottom" >포인트 지급</button>

        </div>

    </div>
</article>
<!-- TODO: 회원 왼쪽 포인트 관리 -->
<article data-tab-content="point" hidden>
    <div class="row mx-0">
        {{-- aside in 현재 포인트 --}}
        <aside class="col-lg-3">
            <div class="modal-shadow-style p-4 mt-52" data-point-pt="now">
              <div class="row pt-2">
                <div class="col h-center">
                <img src="{{asset('images/yellow_point_icon.svg')}}" width="32">
                  <span class="text-sb-24px">현재 포인트</span>
                </div>
                <div class="col-auto">
                  <button class="btn p-0 h-center" onclick="teachStPointAsideHidden(this)">
                    <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32">
                  </button>
                </div>
              </div>
              <div class="py-4 mb-1" data-point-div="now">
                <span class="text-b-42px text-primary-y" data-point-now>0</span>
                <span class="text-b-42px text-primary-y">p</span>
              </div>
            </div>

            <div class="modal-shadow-style p-4 mt-4" data-point-pt="give" >
              <div class="row pt-2">
                <div class="col h-center">
                    <img src="{{asset('images/yellow_point_icon.svg')}}" width="32">
                    <span class="text-sb-24px">회원 포인트 지급</span>
                </div>
                <div class="col-auto">
                  <button class="btn p-0 h-center" onclick="teachStPointAsideHidden(this)">
                    <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32">
                  </button>
                </div>
              </div>
              <div class="py-4 mb-1" hidden data-point-div="give">
                <div class="slide-effect">
                  <p class="text-b-20px pt-4 d-flex">포인트 지급</p>
                  <div class="border-gray d-flex align-items-center rounded mt-12 mb-12">
                    <input type="text" class="border-none text-sb-20px w-100 p-3 rounded" placeholder="포인트를 입력해주세요.">
                    <span class="text-m-20px px-3">P</span>
                  </div>
                  <div class="d-inline-block select-wrap select-icon w-100 mb-80">
                    <select class="border-gray lg-select text-sb-20px w-100">
                      <option value="">포인트 지급 내역</option>
                    </select>
                  </div>
                  <button type="button" class="btn-lg-primary text-b-24px rounded scale-text-white w-100 justify-content-center">포인트 지급하기</button>
                </div>
              </div>
            </div>

            <div class="modal-shadow-style p-4 mt-4" data-point-pt="sub" >
              <div class="row pt-2">
                <div class="col h-center">
                    <img src="{{asset('images/yellow_point_icon.svg')}}" width="32">
                    <span class="text-sb-24px">회원 포인트 차감</span>
                </div>
                <div class="col-auto">
                  <button class="btn p-0 h-center" onclick="teachStPointAsideHidden(this)">
                    <img src="{{ asset('images/dropdown_arrow_down.svg') }}" width="32">
                  </button>
                </div>
              </div>
              <div class="py-4 mb-1" hidden data-point-div="sub">
                <div class="slide-effect">
                  <p class="text-b-20px pt-4 d-flex">포인트 차감</p>
                  <div class="border-gray d-flex align-items-center rounded mt-12 mb-12">
                    <input type="text" class="border-none text-sb-20px w-100 p-3 rounded" placeholder="포인트를 입력해주세요.">
                    <span class="text-m-20px px-3">P</span>
                  </div>
                  <div class="d-inline-block select-wrap select-icon w-100 mb-80">
                    <select class="border-gray lg-select text-sb-20px w-100">
                      <option value="">포인트 차감 이유</option>
                    </select>
                  </div>
                  <button type="button" class="btn-lg-primary studyColor-bg-studyComplete text-b-24px rounded scale-text-white w-100 justify-content-center">포인트 차감하기</button>
                </div>
              </div>
            </div>
        </aside>

        <section class="col">
            <div class="d-flex justify-content-end align-items-end mt-80 mb-32">
                <div class="h-center">
                    <label class="d-inline-block select-wrap select-icon">
                        <select data-search-type="point"
                            onchange=""
                            class="date-change rounded-pill border-gray sm-select text-sb-20px me-2 h-52">
                            <option value="">검색기준</option>
                            <option value="grade">학년</option>
                            <option value="name">이름</option>
                            <option value="student_id">아이디</option>
                            <option value="goods_name">이용권</option>
                            <option value="student_phone">전화번호</option>
                        </select>
                    </label>
                    <label class="label-search-wrap">
                        <input type="text" onkeyup="" data-search-str="point"
                        class="ms-search border-gray rounded-pill text-m-20px" placeholder="검색어를 입력해주세요.">
                    </label>
                </div>
            </div>
            <div class="col-12 ">
                <table class="table-style w-100" style="min-width: 100%;">
                    <thead>
                        <tr class="text-sb-20px modal-shadow-style rounded">
                            <th>구분</th>
                            <th>거래일시</th>
                            <th>지급</th>
                            <th>차감</th>
                            <th>포인트 잔액</th>
                            <th>거래내용</th>
                            <th>지급</th>
                        </tr>
                    </thead>
                    <tbody data-bundle="tby_points">
                        <tr class="text-m-20px h-104" data-row="copy" hidden>
                        <input type="hidden" data-point-history-seq>
                            <td>
                                <span class="btn rounded-pill text-white" data-point-type></span>
                                <span data-point-process>선생님 처리</span>
                            </td>
                            <td>
                                <span data-use-date></span>
                            </td>
                            <td>
                                <span data-point-give class="text-primary-y"></span>
                            </td>
                            <td>
                                <span data-point-sub class="text-danger"></span>
                            </td>
                            <td>
                                <span data-point-now class="text-dark"></span>
                            </td>
                            <td>
                                <span data-point-content></span>
                            </td>
                            <td>
                                <button data-btn-point-cancel onclick="teachStPointCancel(this);"
                                class="btn-line-xss-secondary text-sb-20px border-gray rounded scale-bg-white scale-text-gray_05 px-3 me-2 align-bottom">거래취소</button>
                            </td>
                        </tr>
                    </tbody>
               </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-52">
                {{-- 페이징 기능. --}}
                <div class="my-custom-pagination">
                    <div class="col d-flex justify-content-center">
                        <ul class="pagination col-auto" data-page="3" hidden>
                            <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="3"
                                onclick="teachStPageFunc('3', 'prev')">
                                <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                            </button>
                            <li class="page-item" hidden>
                                <a class="page-link" onclick="">0</a>
                            </li>
                            <span class="page" data-page-first="3" hidden
                                onclick="teachStPageFunc('3', this.innerText);" disabled>0</span>
                            <button href="javascript:void(0)" class="btn p-0 next" data-page-next="3"
                                onclick="teachStPageFunc('3', 'next')" data-is-next="0">
                                <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                            </button>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </div>
</article>

<article data-tab-content="goods" hidden>
    <div class="d-flex justify-content-end align-items-end mt-80 mb-32">
        <div class="h-center">
            <label class="d-inline-block select-wrap select-icon">
                <select data-search-type="goods"
                    onchange=""
                    class="date-change rounded-pill border-gray sm-select text-sb-20px me-2 h-52">
                    <option value="">검색기준</option>
                    <option value="grade">학년</option>
                    <option value="name">이름</option>
                    <option value="student_id">아이디</option>
                    <option value="goods_name">이용권</option>
                    <option value="student_phone">전화번호</option>
                </select>
            </label>
            <label class="label-search-wrap">
                <input type="text" onkeyup="" data-search-str="goods"]
                class="ms-search border-gray rounded-pill text-m-20px" placeholder="검색어를 입력해주세요.">
            </label>
        </div>
    </div>
    <div class="col-12 ">
        <table class="table-style w-100" style="min-width: 100%;">
        <colgroup>
            <col style="width: 80px;">
        </colgroup>
        <thead class="">
            <tr class="text-sb-20px modal-shadow-style rounded">
                <th>구분</th>
                <th>이용권</th>
                <th>이용권 시작일</th>
                <th>이용권 만료일(변경된 만료일)</th>
                <th>상태구분</th>
                <th>결제일(변경일)</th>
                <th>자동 결제일</th>
                <th>결제금액</th>
                <th>열기</th>
            </tr>
        </thead>
            <tbody data-bundle="tby_goods">
                <tr class="text-m-20px h-104" data-row="copy" hidden>
                    <input type="hidden" data-teach-seq>
                    <input type="hidden" data-student-seq>
                    <input type="hidden" data-parent-seq>
                    <td class="text-sb-20px" data-explain="#구분">
                        <span data-gubun></span>
                    </td>
                    <td class="text-sb-20px" data-explain="#이용권">
                        <span data-goods-name></span>
                        <span data-goods-period></span>
                    </td>
                    <td class="text-sb-20px" data-explain="#이용권시작일">
                        <span data-goods-start-date></span>
                    </td>
                    <td class="text-sb-20px" data-explain="#이용권만료일">
                        <div hidden>
                            <span data-end-type="before" class="fw-medium text-dark">기존</span>
                            <span data-goods-end-date="before"></span>
                        </div>
                        <div>
                            <span data-end-type="after" class="fw-medium text-dark"></span>
                            <span data-goods-end-date="after"></span>
                        </div>
                    </td>
                    <td class="text-sb-20px" data-explain="#상태구분">
                        <span data-goods-status></span>
                        <span data-remain-cnt></span>
                    </td>
                    <td class="text-sb-20px" data-explain="#결제일/변경일">
                        <span data-goods-pay-date></span>
                        <span data-goods-chg-pay-date></span>
                    </td>
                    <td class="text-sb-20px" data-explain="#자동결제일">
                        <span data-goods-auto-pay-date></span>
                    </td>
                    <td class="text-sb-20px" data-explain="#결제금액">
                        <span data-goods-pay-amount class="fw-medium text-dark"></span>
                        <span data-payment-info></span>
                        <input data-card-name type="hidden">
                        <input data-is-auto-pay type="hidden" class="text-dark">
                    </td>
                    <td class="text-sb-20px" data-explain="#열기">
                        <button data-btn-toggle-table class="btn p-0 m-0 d-inline-flex" onclick="teachStGoodsHistory(this);">
                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" width="32" height="32">
                        </button>
                    </td>
                 </tr>
                <tr class="text-m-20px h-104" data-sub-row="copy" hidden>
                    <input type="hidden" data-student-seq>
                    <td class="text-sb-20px" data-explain="#구분">
                        <span data-gubun></span>
                    </td>

                    <td class="text-sb-20px" data-explain="#이용권">
                        <span data-goods-name></span>
                        <span data-goods-period></span>
                    </td>
                    <td class="text-sb-20px" data-explain="#이용권시작일">
                        <span data-goods-start-date></span>
                    </td>
                    <td class="text-sb-20px" data-explain="#이용권만료일">
                        <div hidden>
                            <span data-end-type="before" class="fw-medium text-dark">기존</span>
                            <span data-goods-end-date="before"></span>
                        </div>
                        <div>
                            <span data-end-type="after" class="fw-medium text-dark"></span>
                            <span data-goods-end-date="after"></span>
                        </div>
                    </td>
                    <td class="text-sb-20px" data-explain="#상태구분">
                        <span data-goods-status></span>
                        <span data-remain-cnt></span>
                    </td>
                    <td class="text-sb-20px" data-explain="#결제일/변경일">
                        <span data-goods-pay-date></span>
                        <span data-goods-chg-pay-date></span>
                    </td>
                    <td class="text-sb-20px" data-explain="#자동결제일">
                        <span data-goods-auto-pay-date></span>
                    </td>
                    <td class="text-sb-20px" data-explain="#결제금액">
                        <span data-goods-pay-amount class="fw-medium text-dark"></span>
                        <span data-payment-info></span>
                        <input data-card-name type="hidden">
                        <input data-is-auto-pay type="hidden" class="text-dark">
                    </td>
                    <td class="text-sb-20px" data-explain="#열기">
                        <button data-btn-toggle-table class="btn p-0 m-0 d-inline-flex" onclick="teachStGoodsHistory(this);">
                            <img src="{{ asset('images/svg/btn_arrow_down.svg') }}" width="32" height="32">
                        </button>
                    </td>
                 </tr>
            </tbody>
        </table>
    </div>
        <div class="d-flex justify-content-between align-items-center mt-52">
            {{-- 페이징 기능. --}}
            <div class="my-custom-pagination">
                <div class="col d-flex justify-content-center">
                    <ul class="pagination col-auto" data-page="2" hidden>
                        <button href="javascript:void(0)" class="btn p-0 prev" data-page-prev="2"
                            onclick="teachStPageFunc('2', 'prev')">
                            <img src="{{ asset('images/calendar_arrow_left.svg') }}" alt="">
                        </button>
                        <li class="page-item" hidden>
                            <a class="page-link" onclick="">0</a>
                        </li>
                        <span class="page" data-page-first="2" hidden
                            onclick="teachStPageFunc('2', this.innerText);" disabled>0</span>
                        <button href="javascript:void(0)" class="btn p-0 next" data-page-next="2"
                            onclick="teachStPageFunc('2', 'next')" data-is-next="0">
                            <img src="{{ asset('images/gray_arrow_right.svg') }}" alt="">
                        </button>
                    </ul>
                </div>
            </div>
        </div>
</article>



</section>

<div data-explain="160px">
    <div class="py-lg-5"></div>
    <div class="py-lg-4"></div>
    <div class="pt-lg-3"></div>
</div>


<form action="/teacher/users/add/excel" data-form-user-add-excel hidden>
    @csrf
    <input name="user_type">
    <input name="region_seq">
    <input name="team_code">
</form>

{{-- 모달 / 선택 회원 연장 --}}
<div class="modal fade" id="userlist_modal_goods_day_plus" tabindex="-1" aria-hidden="true" style="display: none;">
    <input type="hidden" class="student_seq">
    <div class="modal-dialog  modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">

                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="width:35px;height:35px;"
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



<div class="row px-5 pt-5 position-relative" id="teachst_div_maim" hidden>
{{-- 검색 조회 라인 --}}
<div class="row">
    <div class="col-1">
        담당지역<br>
        (대분류)
    </div>
    <div class="col-2">
        <div class="d-flex border rounded bg-light align-items-center">
            <input type="text" class="form-control border-0 bg-light" placeholder="담당지역(대분류)" disabled value="{{ !empty($sel_team) ? $sel_team->region_name : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-lock me-2" viewBox="0 0 16 16">
               <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/>
            </svg>
        </div>
    </div>
    <div class="col-1">
        담당지역<br>
        (중분류)
    </div>
    <div class="col-2">
        <div class="d-flex border rounded bg-light align-items-center">
            <input type="text" class="form-control border-0 bg-light" placeholder="담당지역(중분류)" disabled value="{{ !empty($sel_team) ? $sel_team->team_name : '' }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-lock me-2" viewBox="0 0 16 16">
               <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2zM5 8h6a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V9a1 1 0 0 1 1-1z"/>
            </svg>
        </div>
    </div>
</div>
<div class="row mt-2">
    <div class="col-1 d-flex align-items-center">
        통합검색
    </div>
    <div class="col-2">
        <select class="form-select bg-light" id="teachst_sel_search_type">
            <option value="">검색기준</option>
            <option value="student_name">학생이름</option>
            <option value="student_phone">휴대폰 번호</option>
            <option value="grade">학년</option>
        </select>
    </div>
    <div class="col">
        <input id="teachst_inp_search_str" type="text" class="form-control bg-light" placeholder="검색어" onkeyup="if(event.keyCode == 13) teachStStudentselect();">
    </div>
    <button class="btn btn-primary col-1" id="teachst_btn_search" onclick="teachStStudentselect();">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" hidden></span>
        조회</button>
</div>

{{-- 학생 목록 리스트 --}}
<div class="row mt-4">
    <div class="py-2 border-start border-end border-top text-end pe-3">
        총 <span id="teachst_sp_total_cnt" class="text-primary">0</span>건의 검색 결과가 있습니다.
    </div>
</div>
<div class="row tableFixedHead overflow-auto" style="max-height: calc(100vh - 420px);">
    <table class="table table-bordered text-center align-middle mb-0" id="teachst_tb_student">
        <thead class="table-light">
            <tr>
                    {{-- checkbox, 번호, 학생이름, 휴대폰 번호, 등록기간, 문자/알림 발송, 학습(정기) 상담, 일정관리, 학생정보관리 --}}
                    <th onclick="event.stopPropagation();this.querySelector('input').click();">
                        <input type="checkbox" onclick="event.stopPropagation();teachStTableAllCheck(this);">
                    </th>
                    <th>번호</th>
                    <th>학생이름</th>
                    <th>휴대폰 번호</th>
                    <th>등록기간</th>
                    <th>문자/알림 발송</th>
                    <th>학습(정기) 상담</th>
                    <th>일정관리</th>
                    <th>학생정보관리</th>
                </tr>
            </thead>
            <tbody id="teachst_tby_student_list">
                <tr class="copy_tr_student_list" hidden>
                    <td onclick="event.stopPropagation();this.querySelector('input').click();">
                        <input type="checkbox" onclick="event.stopPropagation();" name="inp_cb_stinfo">
                    </td>
                        <td class="idx" data="#번호"></td>
                        <td class="student_name" data="#학생이름"></td>
                        <td class="student_phone" data="#휴대폰 번호"></td>
                        <td data="#등록기간">
                            <div class="goods_name" hidden></div>
                            <div class="goods_date" hidden></div>
                            <div class="godds_info_status" hidden></div>
                        </td>
                        <td data="#문자/알림 발송">
                            <button class="btn btn-sm btn-outline-primary" onclick="console.log('target')">문자/알림 발송</button>
                        </td>
                        <td class="" data="#학습(정기) 상담">
                            <button class="btn btn-sm btn-outline-primary" onclick="console.log('target')">학습(정기) 상담</button>
                        </td>
                        <td class="" data="#일정관리">
                            <button class="btn btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calendar-week" viewBox="0 0 16 16">
                                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                                    <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/>
                                </svg>
                            </button>
                        </td>
                        <td class="" data="#학생정보관리">
                            <button class="btn btn-sm btn-outline-primary">학생정보관리</button>
                        </td>
                        <input type="hidden" class="student_seq">
                        <input type="hidden" class="student_id">
                        <input type="hidden" class="parent_seq">
                    </tr>
                </tbody>
                <tfoot hidden>
                    <tr>
                        <td colspan="9">
                            목록이 없습니다.
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="row">
            <div class="d-flex py-2 border-start border-end border-bottom gap-2">
                {{-- SMS 문자 / 알림톡, 선택 회원 활성화, 선택 회원 비활성화, 엑셀 내보내기 --}}
                <button class="col-auto px-4 btn btn-outline-secondary" onclick="teachStSendSms();">SMS 문자 / 알림톡</button>
                <button class="col-auto px-4 btn btn-outline-secondary" onclick="teachStChangeUseStatus(true)">선택 회원 활성화</button>
                <button class="col-auto px-4 btn btn-outline-secondary" onclick="teachStChangeUseStatus(false)">선택 회원 비활성화</button>
                <div class="text-end col">
                    <button class="col-auto px-4 btn btn-outline-success" onclick="teachStlistExcelDownload();">엑셀 내보내기</button>
                </div>
            </div>
        </div>

        {{-- 알림톡 / SMS / PUSH  --}}
        <div id="teachst_div_alarm" class="position-absolute justify-content-center vh-80 bg-white border rounded border-dark p-3 overflow-auto"
        style="z-index: 6;width:98%;height:85vh" hidden>
            <div class="text-end p-2">
                <button class="btn btn-close" onclick="teachStAlarmClose();"></button>
            </div>
            {{-- @include('admin.admin_alarm_detail') --}}
        </div>

        {{-- 학생 상세 정보 이동.  --}}
        <form action="/teacher/student/detail" method="post" data-form-student-info-detail hidden>
            @csrf
            <input type="hidden" name="student_seq">
        </form>
    </div>


@include('utils.modal_transfer_team')


    <script>
        // 학생정보관리 학생 정보 불러오기.
        document.addEventListener('DOMContentLoaded', function(){
           teachStStudentSelect();
        });

        // 상단 select_tag(el) 선택시
        function teachStGoodsSelectTop(vthis, type) {
            if (type == 'region' || type == 'region_modal') {
                const region_seq = vthis.value;
                teachStGoodsTeamSelect(region_seq);
            } else if (type == 'team' || type == 'team_modal') {
                const team_code = vthis.value;
                teachStTeacherSelect(team_code);
            } else if (type == 'teacher') {
                teachStStudentSelect();
            }
        }

        // 본부 선택시 팀 SELECT
        function teachStGoodsTeamSelect(region_seq) {
            const page = '/manage/useradd/team/select';
            const parameter = {
                region_seq: region_seq
            };
            queryFetch(page, parameter, function(result) {
                if ((result.resultCode || '') == 'success') {
                    let select_team = document.querySelector('[data-select-top="team"]');
                    select_team.innerHTML = '';
                    const option = document.createElement('option');
                    option.value = '';
                    option.innerText = '소속 팀을 선택해주세요.';
                    select_team.appendChild(option);
                    const teams = result.resultData;
                    teams.forEach(function(team) {
                        const option = document.createElement('option');
                        option.value = team.team_code;
                        option.innerText = team.team_name;
                        select_team.appendChild(option);
                    });
                }
            });
        }

        // 팀 선택시 선생님 SELECT
        function teachStTeacherSelect(team_code){
            const page = '/manage/userlist/teacher/select';
            const parameter = {
                serach_team: team_code
            };
            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    const select_teacher = document.querySelector('[data-select-top="teacher"]');
                    select_teacher.innerHTML = '';
                    const option = document.createElement('option');
                    option.value = '';
                    option.innerText = '소속 선생님을 선택해주세요.';
                    select_teacher.appendChild(option);
                    const teachers = result.resultData;
                    teachers.forEach(function(teacher){
                        const option = document.createElement('option');
                        option.value = teacher.id;
                        option.innerText = teacher.teach_name;
                        select_teacher.appendChild(option);
                    });
                }
            });
        }

         function teachStStudentSelect(page_num){
            const region_seq = document.querySelector('[data-select-top="region"]').value;
            const team_code = document.querySelector('[data-select-top="team"]').value;
            const teach_seq = document.querySelector('[data-select-top="teacher"]').value;
            const search_type = document.querySelector('[data-search-type]').value;
            const search_str = document.querySelector('[data-search-str]').value;

            if(region_seq == ''){
                toast('담당지역을 선택해주세요.');
                return;
            }

            if(search_str != '' && search_type == ''){
                toast('검색기준을 선택해주세요.');
                return;
            }

            let page = '/teacher/student/select'

            const parameter = {
                search_type: search_type,
                search_str: search_str,
                region_seq: region_seq,
                team_code: team_code,
                teach_seq: teach_seq,
                page: page_num,
                page_max:6,
                is_page:'Y',
            };

            queryFetch(page, parameter, function(result){
              if((result.resultCode||'') == 'success'){
                    teachStStudentList(result);
                }else{}
            });
        }

        // 학생 포인트 상세 조회 전송"
        function teachStStudentPointSelect(page_num){
            const student_seq = document.querySelector('[data-main-student-seq]').value;

            const page = "/manage/userlist/point/history/select";
            const parameter = {
                user_key: student_seq,
                page: page_num,
                page_max:6,
                is_page:'Y',
            };

            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    // 초기화
                    const bundle = document.querySelector('[data-bundle="tby_points"]');
                    const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
                    bundle.innerHTML = '';
                    bundle.appendChild(row_copy);

                    const points = result.resultData;
                    // 페이징 처리.
                    teachStAfterTablePaging(points, '3');
                    points.data.forEach(function(point){
                        const row = row_copy.cloneNode(true);
                        row.hidden = false;
                        let point_type = '';
                        let point_give = '';
                        let point_sub = '';
                        if(point.point*1 < 0){
                            point_type = '차감';
                            point_give = point.point;
                            row.querySelector('[data-point-type]').classList.add('bg-danger');
                        }else{
                            point_type = '지급';
                            point_sub = point.point;
                            row.querySelector('[data-point-type]').classList.add('bg-primary-y');
                        }

                        row.querySelector('[data-point-history-seq]').value = point.id;
                        row.querySelector('[data-point-type]').innerText =  point_type
                        row.querySelector('[data-use-date]').innerText = ( point.created_at||'' ).substr(0, 16).replace(/-/gi, '.');
                        row.querySelector('[data-point-give]').innerText = ((point_give||0)*1).toLocaleString()+'p';
                        row.querySelector('[data-point-sub]').innerText = ((point_sub||0)*1).toLocaleString()+ 'p';
                        row.querySelector('[data-point-now]').innerText = (point.point_now||0).toLocaleString() + 'p';
                        row.querySelector('[data-point-content]').innerText = point.remark;
                        bundle.appendChild(row);
                    });
                }
            })
        }

        // 학생 이용권 상세 조회. 전송.
         function teachStStudentGoodsSelect(page_num){

            const student_seq= document.querySelector('[data-main-student-seq]').value;
            const page = '/teacher/student/goods/select';

            const parameter = {
                student_seq: student_seq,
                page: page_num,
                page_max:6,
                is_page:'Y',
            };

            queryFetch(page, parameter, function(result){
              if((result.resultCode||'') == 'success'){
                teachStGoodsList(result);
              }
            });
        }

        // 학생정보관리 리스트
        function teachStStudentList(result){
            //초기화
            const bundle = document.querySelector('[data-bundle="tby_students"]');
            const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            const row_copy_sub = bundle.querySelector('[data-sub-row="copy"]').cloneNode(true);
            bundle.innerHTML = '';
            bundle.appendChild(row_copy);
            bundle.appendChild(row_copy_sub);

            const students = result.students;

            // 페이징 처리.
            teachStAfterTablePaging(students, '1');
            // 학생 목록 표시
            students.data.forEach(function(student){
                const row = row_copy.cloneNode(true);
                row.hidden = false;
                row.querySelector('[data-teach-seq]').value = student.id;
                row.querySelector('[data-team-name]').innerText = student.team_name;
                row.querySelector('[data-student-name]').innerText = student.student_name;
                row.querySelector('[data-student-id]').innerText = `(${student.student_id})`;
                row.querySelector('[data-student-id]').setAttribute('data-value', student.student_id);
                row.querySelector('[data-student-phone]').innerText = student.student_phone;
                row.querySelector('[data-parent-phone]').innerText = student.parent_phone;
                row.querySelector('[data-point-now]').innerText = (student.point_now*1).toLocaleString();
                row.querySelector('[data-grade-name]').value = student.grade_name;
                row.querySelector('[data-school-name]').value = student.school_name;

                if((student.goods_detail_seq||'') != ''){
                    row.querySelector('[data-goods-name]').innerText = student.goods_name;
                    row.querySelector('[data-goods-period]').innerText = `(${student.goods_period}개월)`;
                    row.querySelector('[data-goods-period]').setAttribute('data-value', student.goods_period);
                    row.querySelector('[data-goods-start-date]').innerText = student.goods_start_date;
                    row.querySelector('[data-goods-end-date]').innerText = student.goods_end_date;

                    // 만료 임박일 때, data-readd-counsel 보이기.
                    // 만료 임박 - goods_end_date 에서 30일 뺀 날자보다 오늘이 더 크면 만료임박.
                    if(new Date(student.goods_end_date) - new Date() < 1000*60*60*24*30){
                        row.querySelector('[data-readd-counsel]').hidden = false;
                    }
                }

                row.querySelector('[data-is-use]').checked = student.is_use == 'Y' ? true : false;
                let teach_name_el = row.querySelector('[data-teach-name]');
                if (teach_name_el !== null) { teach_name_el.innerText = student.teach_name; }
                // row.querySelector('[data-teach-name]')?.innerText = student.teach_name;
                let teach_id_el = row.querySelector('[data-teach-id]');
                if (teach_id_el !== null) { teach_id_el.innerText = student.teach_id; }
                // row.querySelector('[data-teach-id]').setAttribute('data-value', student.teach_id);



                row.querySelector('[data-student-seq]').value = student.id;
                row.querySelector('[data-parent-seq]').value = student.parent_seq;
                row.querySelector('[data-teach-seq]').value = student.id;

                bundle.appendChild(row);

                const row_sub = row_copy_sub.cloneNode(true);
                row_sub.querySelector('[data-student-seq]').value = student.id;
                row_sub.setAttribute('data-sub-row', 'copy');
                row_sub.querySelector('[data-student-name2]').innerText = student.student_name;
                if(student.is_use != 'Y')
                    row_sub.querySelector('[data-student-info]').innerText = `(정지된 학생입니다.)`;

                bundle.appendChild(row_sub);
            });
        }

        // 학생의 포인트 리스트
        function teachStPointList(result){
        }

        // 학생의 이용권 리스트
        function teachStGoodsList(result){
            const bundle = document.querySelector('[data-bundle="tby_goods"]');
            const row_copy = bundle.querySelector('[data-row="copy"]').cloneNode(true);
            bundle.innerText  = '';
            bundle.appendChild(row_copy);

            const goods = result.goods_details;

            // 페이징 처리.
            teachStAfterTablePaging(goods, '2');
            // 학생 목록 표시
            const now_date = new Date();
            goods.data.forEach(function(gds){
                const row = row_copy.cloneNode(true);
                row.hidden = false;
                row.setAttribute('data-row', gds.id);
                //row.querySelector('[data-teach-seq]').value = gds.teach_seq;
                row.querySelector('[data-student-seq]').value = gds.student_seq;
                //row.querySelector('[data-parent-seq]').value = gds.parent_seq;

                row.querySelector('[data-gubun]').innerText = teachStgoodsGetTypeDetail(gds.pay_student_type);
                //row.querySelector('[data-student-name]').innerText = gds.student_name;
                row.querySelector('[data-goods-name]').innerText = gds.goods_name;
                row.querySelector('[data-goods-period]').innerText = `(${gds.goods_period}개월)`;
                row.querySelector('[data-goods-start-date]').innerText = gds.start_date;
                row.querySelector('[data-goods-end-date="after"]').innerText = gds.end_date;
                row.querySelector('[data-goods-end-date="before"]').innerText = gds.origin_end_date;
                //if(gds.origin_end_date != gds.end_date){
                 //   row.querySelector('[data-goods-end-date="before"]').closest('div').hidden = false;
                  //  row.querySelector('[data-end-type="after"]').innerText = '변경';
                //}

                if((gds.start_date||'') != '' && (gds.end_date||'') != ''){
                    //현재 날짜와 이용권 만료일을 비교해서 (만료 또는 유효(남은일수 + '일')) 표기
                    let status_str = '';
                    let goods_end_date = new Date(gds.end_date);
                    let diff = goods_end_date.getTime() - now_date.getTime();
                    let diffDays = Math.ceil(diff / (1000 * 3600 * 24));
                    if(gds.is_use != "Y"){
                        row.querySelector('[data-goods-status]').innerText = '정지';
                    }
                    else if(diffDays < 0){
                        cloneTr.querySelector('[data-goods-status]').innerHTML = '만료';
                    }
                    else{
                        row.querySelector('[data-goods-status]').innerHTML = '유효';
                        row.querySelector('[data-remain-cnt]').innerText = `(${diffDays}일)`;
                        row.querySelector('[data-remain-cnt]').classList.add('text-danger');
                    }
                }

                row.querySelector('[data-goods-pay-date]').innerText = gds.payment_date;
                row.querySelector('[data-goods-auto-pay-date]').innerText = (gds.regular_date ? '매월 ' + gds.regular_date + '일' : '') ;
                if(gds.amount){
                    row.querySelector('[data-goods-pay-amount]').innerText = (gds.amount*1).toLocaleString();
                    row.querySelector('[data-payment-info]').innerHTML = `<br>(${gds.card_name}${gds.is_regular == 'Y' ? '/자동결제' : ''})`;
                    row.querySelector('[data-card-name]').value = gds.card_name;
                    row.querySelector('[data-is-auto-pay]').value = gds.is_regular;
                }

                bundle.appendChild(row);
            });
        }


        // 오픈/클로즈 /토글 테이블
        function teachStOpenTr(vthis){
            const tr = vthis.closest('tr');
            const tr_next = tr.nextElementSibling;
            if(vthis.classList.contains('rotate-180')){
                vthis.classList.remove('rotate-180');
                tr_next.hidden = true;
            }else{
                vthis.classList.add('rotate-180');
                tr_next.hidden = false;
            }
        }

    // 페이징  함수
        function teachStAfterTablePaging(rData, target){
            if(!rData) return;
            const from = rData.from;
            const last_page = rData.last_page;
            const per_page = rData.per_page;
            const total = rData.total;
            const to = rData.to;
            const current_page = rData.current_page;
            const data = rData.data;
            //페이징 처리
            const notice_ul_page = document.querySelector(`[data-page='${target}']`);
            //prev button, next_button
            const page_prev = notice_ul_page.querySelector(`[data-page-prev='${target}']`);
            const page_next = notice_ul_page.querySelector(`[data-page-next='${target}']`);
            //페이징 처리를 위해 기존 페이지 삭제
            notice_ul_page.querySelectorAll(".page_num").forEach(element => {
                element.remove();
            });
            //#page_first 클론
            const page_first = document.querySelector(`[data-page-first='${target}']`);
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
                copy_page_first.removeAttribute("data-page-first");
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
            else
                notice_ul_page.hidden = true;
        }

        //페이지 펑션
        function teachStPageFunc(target, type){
          if(type == 'next'){
              const page_next = document.querySelector(`[data-page-next="${target}"]`);
              if(page_next.getAttribute("data-is-next") == '0') return;
              // data-page 의 마지막 page_num 의 innerText를 가져온다
              const last_page = document.querySelector(`[data-page="${target}"] .page_num:last-of-type`).innerText;
              const page = parseInt(last_page) + 1;
              //학생
                if(target == "1")
                    teachStStudentSelect(page);
                else if(target == "2")
                    teachStStudentGoodsSelect(page);
                else if(target == "3")
                    teachStStudentPointSelect(page);
          }
          else if(type == 'prev'){
              // [data-page-first]  next tag 의 innerText를 가져온다
              const page_first = document.querySelector(`[data-page-first="${target}"]`);
              const page = page_first.innerText;
              if(page == 1) return;
              const page_num = page*1 -1;
                if(target == "1")
                    teachStStudentSelect(page);
                else if(target == "2")
                    teachStStudentGoodsSelect(page);
                else if(target == "3")
                    teachStStudentPointSelect(page);
          }
          else{
              if(target == "1")
                   teachStStudentSelect(type);
               else if(target == "2")
                   teachStStudentGoodsSelect(type);
               else if(target == "3")
                   teachStStudentPointSelect(type);
          }
        }

        // 학생 활성화 변경
        function teachStStIsUseUpdate(vthis) {
            const tr = vthis.closest('tr');
            const student_seq = tr.querySelector('[data-student-seq]').value;
            const is_use = vthis.checked ? 'Y' : 'N';

            const page = '/manage/userlist/user/use/update';
            const parameter = {
                user_key: student_seq,
                group_type: 'student',
                chk_val: is_use,
            };
            queryFetch(page, parameter, function(result) {
                if (result.resultCode == '1' || result.resultCode == 'success') {
                    toast('변경되었습니다.');
                }
            });
        }

        // 엑셀 내보내기
        function teachStlistExcelDownload(){
            const tby_table = document.querySelector('[data-bundle="tby_students"]');
            const clone_tag = pt_div_tbarentElement.cloneNode(true);
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
                item.querySelectorAll('input[type=checkbox]').forEach((item2)=>{
                    if(item2.checked)
                        item2.insertAdjacentHTML('afterend', '<span>Y</span>');
                    else
                        item2.insertAdjacentHTML('afterend', '<span>N</span>');
                    item2.remove();
                });
            });
            const html = clone_tag.outerHTML;
            _excelDown('학생목록.xls', '학생목록', html);
        }

        // 엑셀 일괄 등록 /  등록 페이지 이동.
        function teachStMovePageUsersAdd(url){
            //data-form-user-add-excel 에 user_type, region_seq, team_code 넣기.
            const form = document.querySelector('[data-form-user-add-excel]');
            const user_type = 'student';
            const team_code = document.querySelector('[data-select-top="team"]').value;
            const region_seq = document.querySelector('[data-select-top="region"]').value;

            form.querySelector('[name="user_type"]').value = "student" ;
            form.querySelector('[name="team_code"]').value = team_code;
            form.querySelector('[name="region_seq"]').value = region_seq;

            if(url) form.action = url;
            else form.action = '/teacher/users/add/excel';

            form.method = 'post';
            form.target = '_self';
            form.submit();
        }


        // 전체목록 > 상단 3개 탭 클릭
        function teachStClickListTab(type, data){
            //data-tab 모두 비활성화
            const tabs = document.querySelectorAll('[data-tab]');
            const tab_contents = document.querySelectorAll('[data-tab-content]');
            tabs.forEach(function(tab){
                tab.classList.remove('active');
                tab.classList.remove('primary-bg-mian-hover');
                tab.classList.remove('scale-text-white-hover');
            });
            tab_contents.forEach(function(tab_content){ tab_content.hidden = true; });

            //클릭한 탭 활성화
            document.querySelector(`[data-tab="${type}"]`).classList.add('primary-bg-mian-hover');
            document.querySelector(`[data-tab="${type}"]`).classList.add('scale-text-white-hover');
            document.querySelector(`[data-tab="${type}"]`).classList.add('active');

            document.querySelector(`[data-tab-content="${type}"]`).hidden = false;

            const list_type = type;

            if(list_type == 'goods'){
                teachStStudentGoodsSelect(1);
            }
            else if(list_type == 'point'){
                teachStStudentPointSelect(1);
            }
        }


    </script>

    <script>

        // 학생 목록 전체 체크
        function teachStTableAllCheck(vthis){
            const check = vthis.checked;
            const tby = document.querySelector('#teachst_tby_student_list');
            const chk_input = tby.querySelectorAll('.tr_student_list input[type=checkbox][name=inp_cb_stinfo]');
            chk_input.forEach((item)=>{
                item.checked = check;
            });
        }

        // 엑셀 다운로드
        // 엑셀 내보내기(다운)
        function teachStlistExcelDownload(){
            const clone_tag = document.querySelector('#teachst_tb_student').parentElement.cloneNode(true);
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
            _excelDown('학생목록.xls', '학생목록', html);
        }

        //사용자 활성/비활성 상태 변경
        function teachStChangeUseStatus(type){
            var page = 'user/use/update';
            const chk_msg = type ? '활성화' : '비활성화';
            sAlert('', '선택한 회원을 ' + chk_msg + ' 하시겠습니까?', 2, function(){
                teachStChangeValue(page, type).then(function(result){
                    if(result){
                        sAlert('', '변경되었습니다.');
                        teachStStudentselect();
                    }else{
                        sAlert('', '변경에 실패하였습니다.');
                    }
                });
            });
        }

        // 사용자 활성화/비활성화 변경
        function teachStChangeValue(type, value){
            return new Promise(function(resolve, reject){
                const userinfo_check_list = document.querySelectorAll('[data-bundle="tby_students"] .chk:checked');
                const userinfo_check_cnt = userinfo_check_list.length;

                if(userinfo_check_cnt < 1){
                    sAlert('', '선택한 내역이 없습니다.');
                    return;
                }

                let chk_val = "";
                if(type == 'user/use/update'){
                    if(value) chk_val = 'Y';
                    else chk_val = 'N';
                }

                let success_cnt = 0;
                let sum_cnt = 0;
                for(let i=0; i<userinfo_check_cnt; i++){
                    let sel_tr = userinfo_check_list[i].closest('tr');
                    let user_key = sel_tr.querySelector('.student_seq').value;
                    let group_type = 'student'

                    const parameter = {
                        user_key:user_key,
                        group_type:group_type,
                        chk_val:chk_val
                    };

                    const page = "/manage/userlist/"+type;
                    queryFetch(page,parameter,function(result){
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

        // SMS 문자 / 알림톡 버튼 클릭
        function teachStSendSms(){
            // 테이블 체크박스가 선택된 tr
            const chks = document.querySelectorAll("[data-bundle='tby_students'] .chk:checked");

            // 체크된 체크박스의 수를 세기 위한 변수
            let checkedCount = 0;

            select_member = [];
            chks.forEach(chk => {
                const row = chk.closest("tr");
                const checkbox = chk;
                const studentPhone = row.querySelector(".student_phone").textContent.trim();

                const member_id = row.querySelector('.student_id').value;
                const member_name = row.querySelector('.student_name').textContent;
                const user_type = '(학생)';
                const phone_num = row.querySelector('.student_phone').textContent;

                const member = {
                    member_id: member_id,
                    member_name: member_name+user_type,
                    grade: '',
                    phone: phone_num,
                    push_key: '',
                };
                // 푸시키는 추후에 추가
                // 추가 코드.

                // 체크박스가 체크되어 있고, 학생의 휴대폰 번호가 비어있는 경우 = 해제
                if (checkbox.checked && studentPhone === "") { checkbox.checked = false; }

                // 체크된 체크박스의 수를 업데이트합니다.
                if (checkbox.checked) { checkedCount++;  select_member.push(member);}

            });

            // 체크된 체크박스의 수가 1보다 작은 경우 경고 메시지를 표시합니다.
            if (checkedCount < 1) {
                sAlert('', "선택된 목록이 없습니다. 학생 체크박스를 체크해주세요.");
                return;
            }

            const msg = "선택 회원에게 문자를 보내시겠습니까?<br><span class='text-danger'>전화번호가 없거나 1teachStPageFunc0자리 이하인 회원은 자동으로 체크가 해제됩니다.</span>";
            sAlert('', msg, 2, function(){
                const div_main = document.querySelector('#teachst_div_maim');
                div_main.classList.remove('px-5');
                div_main.classList.add('px-2');
                div_main.classList.remove('pt-5');
                // 문자 div 보이기
                const div_alarm = document.querySelector('#teachst_div_alarm');
                div_alarm.hidden = false;
                alarmSelectUserAddList();
            });
        }
        // 문자 div 닫기 clear
        function teachStAlarmClose(){
            const div_alarm = document.querySelector('#teachst_div_alarm');
            const div_main = document.querySelector('#teachst_div_maim');
            div_main.classList.add('px-5');
            div_main.classList.add('pt-5');
            div_main.classList.remove('px-2');
            div_alarm.hidden = true;
            alarmSelectMemberClear();
            alarmMessageFormClearIn();
        }

          // 이용권상태 신규/만료임박/만료/재등록/휴먼회원
        function teachStgoodsGetTypeDetail(student_type){
                const today_date = new Date().format('yyyy-MM-dd');
                if(student_type == 'readd'){
                    return '재등록';
                }else
                    return '신규';
        }

        // 이용권 TD 클릭.
        // 포인트 TD 클릭.(포인트 화면으로 이동.)
        function teachStOpenDetail(vthis,type){
            const tr = vthis.closest('tr');
            const student_seq = tr.querySelector('[data-student-seq]').value;
            document.querySelector('[data-main-student-seq]').value = student_seq;

            const student_name = tr.querySelector('[data-student-name]').innerText;
            const school_name = tr.querySelector('[data-school-name]').value;
            const grade_name = tr.querySelector('[data-grade-name]').value;
            const point_now = tr.querySelector('[data-point-now]').innerText;

            const data = {
                student_name: student_name,
                school_name: school_name,
                grade_name: grade_name,
                point_now: point_now,
            };
            teachStSubTitleSet('back', data);
            teachStClickListTab(type);

        }


        // 페이지 뒤로가기.
        function teachStDetailBack() {
            teachStSubTitleSet('basic')
            teachStClickListTab('student')
        }

        // 상단 서브 타이틀 BACK 설정
        function teachStSubTitleSet(type, data) {
            const sub_title_back = document.querySelector('[data-sub-title="back"]');
            const sub_title_basic = document.querySelector('[data-sub-title="basic"]');

            if(type == 'back'){
                sub_title_back.hidden = false;
                sub_title_basic.hidden = true;

                const student_name_el = sub_title_back.querySelector('[data-title-student-name]');
                const school_grade = sub_title_back.querySelector('[data-title-school-grade]');
                const point_now = document.querySelector('[data-point-pt="now"] [data-point-now]');
                student_name_el.innerText = data.student_name;
                school_grade.innerText = `${data.school_name}/${data.grade_name}`;
                point_now.innerText = data.point_now;
            }else{
                sub_title_back.hidden = true;
                sub_title_basic.hidden = false;
            }
        }

        // 이용권 상세 내역
        function teachStGoodsHistory(vthis){
            const tr = vthis.closest('tr');
            const student_seq = tr.querySelector('[data-student-seq]').value;
            const goods_detail_seq = tr.dataset.row;

            const page = "/manage/userlist/goods/detail/log/select";
            const parameter = {
                type:'all',
                student_seq:student_seq,
                goods_detail_seq:goods_detail_seq
            };


            if(vthis.classList.contains('active')){
                //active이면 닫기, tr_log_list 삭제
                vthis.classList.remove('active');
                const idx = tr.dataset.row;
                document.querySelectorAll(`[data-sub-row="${idx}"]`).forEach(function(el){
                    el.remove();
                });
                return;
            }

            if(vthis.classList.contains('rotate-180')){
                vthis.classList.remove('rotate-180');
            }else{
                vthis.classList.add('rotate-180');
            }

            queryFetch(page, parameter, function(result){
                if((result.resultCode||'') == 'success'){
                    const clone_tr = tr.cloneNode(true);
                    result.goods_detail_logs.forEach(function(log){
                        const type = log.type == 'plus' ? '연장':'정지';
                        const idx = goods_detail_seq;
                        clone_tr.removeAttribute('data-row');
                        clone_tr.setAttribute('data-sub-row', idx);
                        clone_tr.querySelector('[data-gubun]').innerHTML = '이용권<br>'+type;


                        /* if(gds.origin_end_date != gds.end_date){ */
                        clone_tr.querySelector('[data-goods-end-date="before"]').closest('div').hidden = false;
                        clone_tr.querySelector('[data-goods-end-date="before"]').innerText = (log.end_date||'').substr(0, 10);
                        clone_tr.querySelector('[data-end-type="after"]').innerText = '변경';
                        const after_date = clone_tr.querySelector('[data-goods-end-date="after"]').innerText;
                        clone_tr.querySelector('[data-goods-end-date="after"]').innerText = after_date.substr(0,10);
                        /* } */

                        /* clone_tr.querySelector('.goods_end_date').innerText = '➡️'+(log.end_date||'').substr(0, 10); */
                        /* clone_tr.querySelector('.goods_end_date').hidden = false; */
                        clone_tr.querySelector('[data-goods-status]').innerHTML = type;
                        clone_tr.querySelector('[data-remain-cnt]').innerText = log.day_cnt;

                        clone_tr.querySelector('[data-goods-pay-date]').innerText = (log.created_at||'').substr(0, 10);
                        clone_tr.querySelector('[data-goods-auto-pay-date]').closest('td').colSpan = 3;
                        clone_tr.querySelector('[data-goods-auto-pay-date]').innerText = log.remark;
                        clone_tr.querySelector('[data-goods-pay-amount]').closest('td').hidden = true;
                        clone_tr.querySelector('[data-btn-toggle-table]').closest('td').hidden = true;
                        clone_tr.classList.add('scale-bg-gray_01');
                        tr.after(clone_tr);
                    });
                    if((result.goods_detail_logs||'').length > 0){
                        //class active toggle
                        vthis.classList.toggle('active');
                    }
                }
            });
        }

        function teachStPMSetting(vthis, type){
            const pt_div = vthis.closest('div');
            // +
            if(type == 'p'){
                const num = pt_div.querySelector('input');
                num.value = num.value*1 + 1;
            }
            // -
            else if(type == 'm'){
                // - 는 안되
                const num = pt_div.querySelector('input');
                num.value = num.value*1 - 1;
                if(num.value < 0) num.value = 0;
            }
        }

        // 이용권 연장 모달
        function userlistGoodsDayPlusModal(){
            // 밖의 연장일 가져오기.
            const day_sum = document.querySelector('#userlist_inp_day_manage').value;
            if(day_sum < 1 ){
                toast('연장일을 입력해주세요.');
                return;
            }

            //초기화
            userlistGoodsDayPlusModalClear();

            // 체크 회원 없으면 리턴
            if(userlistChkUser('zero')) return;

            // 체크 확인.
            let first_tr = null;
            let student_seqs = '';
            const chkbox = document.querySelectorAll('[data-bundle="tby_students"] .chk:checked');
            chkbox.forEach(function(el){
                const tr = el.closest('tr');
                // goods_period 가 '' 이면 체크 해제 continue
                const goods_period = tr.querySelector('[data-goods-period]').innerText;
                if(goods_period == ''){
                    el.checked = false;
                    return;
                }
                const user_key = tr.querySelector('[data-student-seq]').value;
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

        // 선택된 회원 체크.
        function userlistChkUser(type, msg){
            const chkd = document.querySelectorAll('[data-bundle="tby_students"] .chk:checked');
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

        //로그 이용권 정지 내역 가져오기.
        //학생의 goods_details 정보 가져오기.
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
                    const inp_cb_userinfo = document.querySelectorAll('[data-bundle="tby_students"] .chk:checked');
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
                        teachStStudentSelect();
                    }else{
                        sAlert('', '저장에 실패하였습니다.');
                    }
                });
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

            const inp_point = document.querySelector('#userlist_inp_point_manage').value;
            if(inp_point*1 < 1){
                toast('포인트를 입력해주세요.');
                return;
            }
            //0명 은 리턴
            if(userlistChkUser('zero')){
                //모달 닫기.
                document.querySelector('#userlist_modal_point_manage .btn-close').click();
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
            const chk_tr = document.querySelector('[data-bundle="tby_students"] .chk:checked').closest('tr');
            const user_key = chk_tr.querySelector('[data-student-seq]').value;
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
            const sel_tr = document.querySelectorAll('[data-bundle="tby_students"] tr[data-row]');
            for(let i = 0; i < sel_tr.length; i++){
                const tr = sel_tr[i];
                if(tr.querySelector('input[type=checkbox].chk').checked){
                    const user_key = tr.querySelector('[data-student-seq]').value;
                    if(user_keys != '')
                        user_keys += ',';
                    user_keys += user_key;
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

                    const page_num = document.querySelector('[data-page="1"] .active').innerText;
                    teachStStudentSelect(page_num);
                    // 모달 닫기
                    document.querySelector('#userlist_modal_point_manage .btn-close').click();
                }else{
                    sAlert('', '저장에 실패하였습니다.');
                }
            });
        }

        // Aside 포인트 내역 접기/펼치기
        function teachStPointAsideHidden(vthis){
            const pt_div = vthis.closest('[data-point-pt]');
            const hidden_div = pt_div.querySelector('[data-point-div]');
            if(hidden_div.hidden){
                hidden_div.hidden = false;
                vthis.classList.remove('rotate-180');
            }else{
                hidden_div.hidden = true;
                vthis.classList.add('rotate-180');
            }
        }

        // TODO: 포인트 거래 취소.
        function teachStPointCancel(vthis){
            const student_key = document.querySelector('[data-main-student-seq]').value;
            const page = "";
            const parameter = {
                point_seq: vthis.dataset.pointSeq
            };
            const msg =
            `   <div class="text-sb-24px">
                <div class="scale-text-gray_05">포인트 거래를 취소하시겠습니까?</span>
                <div class="text-danger">취소된 포인트는 복구되지 않습니다.</span>
                </div>
            `;

            sAlert('', msg, 3, function(){
                queryFetch(page, parameter, function(result){
                    if(result.resultCode == 'success'){
                        sAlert('', '거래가 취소되었습니다.');
                        teachStStudentPointSelect(1);
                    }else{
                        sAlert('', '취소에 실패하였습니다.');
                    }
                });
            });
        }

        // 담당 선생님, 상담 선생님 변경
        // 팀 변경.
        function teachStTeachAndTeamChangeModal(modal_type){
            const ss_teach_seq = document.querySelector('[data-main-teach-seq]').value;
            const team_code = document.querySelector('[data-main-team-code]').value;
            const returns = teachStChkStudentSelect();
            const student_seqs = returns.student_seqs;
            const student_names = returns.student_names;
            // 1명일때만 유효.
            const sel_teach_seq = returns.sel_teach_seq;

            if(student_seqs.length < 1){
                toast('선택된 학생이 없습니다.');
                return;
            }

            const data = {
                "modal_type": modal_type,
                "teach_seq": ss_teach_seq,
                "team_code": team_code,
                "sel_teach_seq":sel_teach_seq,
                "student_names": student_names,
                "student_seqs": student_seqs
            }
            utilsModalTransferTeam(data);
        }


        // 학생정보 상세보기
        function teachStUserEditPage(vthis){;
            const tr = vthis.closest('tr');
            const student_seq = tr.querySelector('[data-student-seq]').value;
            //form 을 만들어서 teach_seq 넣고 submit
            const form = document.querySelector('[data-form-student-info-detail]');
            form.querySelector('[name="student_seq"]').value = student_seq;
            form.method = 'post';
            form.target = '_self';
            form.submit();
        }

        // 체크된 학생 불러오기.
        function teachStChkStudentSelect(){
            const bundle = document.querySelector('[data-bundle="tby_students"]');
            const rows = bundle.querySelectorAll('tr .chk:checked');
            const student_seqs = [];
            const student_names = [];
            let sel_teach_seq = '';
            rows.forEach(function(row){
                const student_seq = row.closest('tr').querySelector('[data-student-seq]').value;
                const student_name = row.closest('tr').querySelector('[data-student-name]').innerText;
                const teach_seq = row.closest('tr').querySelector('[data-teach-seq]').value;
                student_seqs.push(student_seq);
                student_names.push(student_name);
                sel_teach_seq = teach_seq;

            });
            const returns = {
                student_seqs: student_seqs,
                sel_teach_seq: sel_teach_seq,
                student_names: student_names
            };
            return returns;
        }
    </script>
@endsection
